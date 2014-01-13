#!/usr/bin/perl

=pod

=head1 PROGRAM nedi.pl

Main program which can initialize the DB and perform discovery and
other useful tasks.

=head1 SYNOPSIS

nedi.pl -lotsofoptions, see HELP!

=head2 LICENSE

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

=head2 AUTHORS

Remo Rickli & NeDi Community

Visit http://www.nedi.ch for more information.

=cut

$VERSION = "1.0.7";

use strict;
use warnings;
use bignum;

use Getopt::Std;
use File::Path;

#my $threads = 5; TODO make configurable when adding real thread support!
#use threads;
#use threads::shared;

use vars qw($p $warn $now $nediconf $cdp $lldp $oui);
use vars qw(%nod %dev %int %mod %link %vlan %opt %net %usr);
@misc::donenam = ();											# Avoid 'used only once' warning (won't break evals like LWP in libweb this way)
$misc::pause = $misc::notify = $misc::dbhost = "";
$misc::nnip = $misc::nip = $misc::mq = 0;

getopts('a:A:bBdDFiIkNnoprs:S:t:Tu:U:vw:Wy',\%opt) || &HELP_MESSAGE;
if(!defined $opt{'S'}){$opt{'S'} = ""}									# Avoid warnings if unused

select(STDOUT); $| = 1;											# Disable buffering

$now = time;
$p   = $0;
$p   =~ s/(.*)\/(.*)/$1/;
if($0 eq $p){$p = "."};

require "$p/inc/libmisc.pm";										# Use the miscellaneous nedi library
&misc::ReadConf();
require "$p/inc/libsnmp.pm";										# Use the SNMP function library
require "$p/inc/libmon.pm";										# Use the Monitoring lib for notifications
require "$p/inc/libdb-" . lc($misc::backend) . ".pm" || die "Backend error ($misc::backend)!";
require "$p/inc/libcli-" . lc($misc::clilib) . ".pm" || die "Clilib error ($misc::clilib)!";

# Disable buffering so we can see what's going on right away.
select(STDOUT); $| = 1;

=head2 Debug Mode

The -D option skips discovery and loads internal variables (using misc::RetrVar), which where previousely
stored in the .db files (with -d, debug output). This works like a snapshot and allows for debugging the
post discovery functions.

=cut
if ($opt{'D'}){
	&misc::ReadOUIs();
	&db::ReadDev();
	&db::ReadAddr();
	&misc::RetrVar();

# Functions to be debugged go here
#	&db::UnStock();
	&misc::BuildArp() if(defined $misc::arpwatch);

	&db::ReadNod();
	&misc::BuildNod();
#	$misc::retire = $now - 1 * 86400;								# Test Retiring, e.g. with 1 day
	&misc::RetireNod();
#	&misc::FloodFind() if $misc::notify =~ /n/i
#	&db::WriteNod();
}elsif ($opt{'w'}){
	&db::WlanUp();
}elsif($opt{'i'}){
	my $adminuser;
	my $adminpass;
	my $nedihost = 'localhost';
	if ($#ARGV eq 1){										# 2 arguments for -i?
		$adminuser = $ARGV[0];
		$adminpass = $ARGV[1];
	}else{												# interactive credentials then...
		print "\nDB, RRDs and configs files will be cleared, bail out if don't want this!\n";
		print "------------------------------------------------------------------------\n";
		print "MySQL admin user: ";
		$adminuser = <STDIN>;
		print "MySQL admin pass: ";
		$adminpass = <STDIN>;
		if($misc::dbhost ne 'localhost'){
			print "NeDi host (where the discovery runs on: ";
			$nedihost = <STDIN>;
		}
	}
	$adminuser = (defined $adminuser)?$adminuser:"";						# Avoid errors on empty Webform
	$adminpass = (defined $adminpass)?$adminpass:"";
	chomp($adminuser,$adminpass,$nedihost);
	&db::InitDB($adminuser, $adminpass, $nedihost);

	my $nrrd = rmtree( "$misc::nedipath/rrd", {keep_root => 1} );
	my $ncfg = rmtree( "$misc::nedipath/conf", {keep_root => 1} );
	mkpath("$misc::nedipath/rrd");
	mkpath("$misc::nedipath/conf");
	print "INIT: $nrrd RRDs and $ncfg configurations (with dirs) deleted!\n\n";
}elsif($opt{'y'}){
	print "Supported Devices (NeDi $main::VERSION) ----------------------------------------\n";
	chdir("$p/sysobj");
	my $nd = 0;
	my @defs = glob("*.def");
	foreach my $df (sort @defs){
		$nd++;
		open F, $df or print "couldn't open $df\n" && return;
		while (<F>) {
			next unless s/^Type\s*//;
			$_ =~ s/\r|\n//g;
			printf ("%-30s%s\n",$_,$df );
		}
	}
	print "$nd Definitions (and counting) ------------------------------------------------\n\n";
}elsif($opt{'s'}){
	print "Scanning Nodes (NeDi $main::VERSION) ---------------------------------------------\n";
	if($opt{'s'} =~ /update/) {&db::ReadNod("lastseen > $now-$misc::discostep order by osupdate")}
	elsif($opt{'s'} =~ /ip=/){
		my $ip = $opt{'s'};
		$ip =~ s/.*ip=//;
		&db::ReadNod("nodip = \"".&misc::Ip2Dec(join('.',unpack( 'C4',gethostbyname($ip))))."\"");
	}
	my $fp = ($opt{'s'} =~ /O/)?"-O":"";
	my $fm = ($opt{'s'} =~ /F/)?"-F":"";
	my $up = ($opt{'s'} =~ /U/)?"-sU":"";
	my $tp = ($opt{'s'} =~ /T/)?"-sS":"";

	&misc::Prt("SCAN:Using nmap $up $tp $fp $fm\n");
	foreach my $mc (keys %main::nod){
		if ($main::nod{$mc}{ip} ne "0.0.0.0"){
			my $alive = 1;
			my $tcp = my $udp = "";
			my $os = my $typ = "?";
			&misc::Prt("SCAN:$main::nod{$mc}{ip} $main::nod{$mc}{na}:");
			my @res = split(/\n/,`nmap $up $tp $fp $fm $main::nod{$mc}{ip} 2> /dev/null`);
			foreach my $l (@res){
				if($l =~ /Host seems down/){$alive = 0;}
				elsif($l =~ /^([0-9]+)\/tcp/){$tcp .= "$1 ";}
				elsif($l =~ /^([0-9]+)\/udp/){$udp .= "$1 ";}
				elsif($l =~ /^Running.*?:\s*(.*)/){$os = "$1";}
				elsif($l =~ /^OS details:\s*(.*)/){$os = "$1";}			# Comment out if not better than Running:
				elsif($l =~ /^Device type:\s*(.*)/){$typ = "$1";}
				#elsif($typ =~ /general purpose|\?/ and $l =~ /Device:\s*(.*)/){$typ = "$1";}
			}
			if($alive){
				if($misc::notify =~ /p/ and $main::nod{$mc}{ou}){		# Log port changes if desired and nodes was scanned before
					if($main::nod{$mc}{tp} ne $tcp){
						&db::Insert('events','level,time,source,info,class,device',"\"10\",\"$main::now\",\"$main::nod{$mc}{ip}\",\"TCP Port change from '$main::nod{$mc}{tp}' to '$tcp' detected\",\"node\",\"$main::nod{$mc}{dv}\"");
					}
					if($main::nod{$mc}{up} ne $udp){
						&db::Insert('events','level,time,source,info,class,device',"\"10\",\"$main::now\",\"$main::nod{$mc}{ip}\",\"UDP Port change from '$main::nod{$mc}{up}' to '$udp' detected\",\"node\",\"$main::nod{$mc}{dv}\"");
					}
				}
				&misc::Prt("$typ TCP:$tcp UDP:$udp\n");
				&db::Update('nodes',"tcpports=\"$tcp\",udpports=\"$udp\",nodtype=\"$typ\",nodos=\"$os\",osupdate=\"$now\"","mac = \"$mc\"") if(!$opt{t});
			}else{
				&misc::Prt("is down\n");
			}
		}
		if(time - $misc::discostep > $now){
			&misc::Prt("SCAN:Running longer than ${misc::discostep}s, ending now.\n");
			last;
		}
	}
}else{
	require "$p/inc/libweb.pm";									# Use the WEB functions for webdevs

	&misc::ReadOUIs();
	&db::ReadDev($opt{'A'});
	&db::ReadAddr();
	&db::ReadLink('linktype = "STAT"');								# Static links will override DP
	my $nusr = &db::ReadUser("groups & 8 AND (phone != \"\" OR email != \"\")");			# Read users for Mail alerts

	my $nseed = &misc::InitSeeds();
	my $nthrd = &db::Select('system','value','name="threads"');
	my $first = &db::Select('system','value','name="first"');

	if ($opt{t}){
		print "MAIN:Ignoring $nthrd thread".(($nthrd > 1)?"s":"")." for testing\n" if $opt{'v'};
	}elsif ($nthrd > 0){
		print "MAIN:$nthrd thread".(($nthrd > 1)?"s":"").", 1st from ".localtime($first) if $opt{'v'};
		if (($now - $first + 60) > $misc::discostep ){						# Check for stale threads (based on OXO's idea)
			print " is stale, resetting!\n" if $opt{'v'};
			&db::Update('system',"value=\"$now\"",'name="first"');
			&db::Update('system','value="1"','name="threads"');				# Make sure we're back to 1!
			&db::Insert('events','level,time,source,info,class',"\"200\",\"$now\",\"NeDi\",\"$nthrd thread(s), 1st from ".localtime($first)." is older than $misc::discostep seconds!\",\"nedi\"");
		}else{
			print " seems ok adding this one\n" if $opt{'v'};
			&db::Update('system','value=value+1','name="threads"');				# Register new thread
		}
	}else{
		if ($nthrd < 0){
			my $err = "$nthrd thread(s) error, 1st from ".localtime($first)." make sure interval is longer than discovery takes!";
			print "MAIN:$err" if $opt{'v'};
			&db::Insert('events','level,time,source,info,class',"\"200\",\"$now\",\"NeDi\",\"$err\",\"nedi\"");
		}else{
			print "MAIN:No threads, set 1st at ".localtime($now)."\n" if $opt{'v'};
		}
		&db::Update('system','value="1"','name="threads"');					# Register first thread
		&db::Update('system',"value=\"$now\"",'name="first"');
	}
	print "\n";
	print "DP-"   if($opt{p});
	print "OUI-"   if($opt{o});
	print "Route-" if($opt{r});
	print "Manual-" if($opt{a});
	print "Discovery ($main::VERSION) with $nseed seed".(($nseed > 1)?"s":"")." at ". localtime($now)."\n";
	print "-------------------------------------------------------------------------------\n";
	print "Device				Status				Todo/Done-Time\n";
	print "===============================================================================\n";
	while ($#misc::todo ne "-1"){
		my $id = shift(@misc::todo);
		&misc::Discover($id);
	}
	print "===============================================================================\n";
	my $ndev = scalar @misc::donenam;
	if ($ndev){
		&misc::Prt("MAIN:$ndev devices discovered\n");
		&misc::StorVar() if ($opt{'d'});
		if ($opt{'t'}){										# We're only testing
			&db::ReadNod();
			&misc::BuildNod();
			&misc::RetireNod();
		}else{
			&db::DelDev();
			unless($opt{'S'} =~ /f/ and $opt{'S'} =~ /r/){
				&misc::BuildArp() if(defined $misc::arpwatch);
				while( my $nlock = &db::Select('system','value','name="nodlock"') ){	# Wait until nodes are unlocked by a parallel thread;
					if( ($now - $nlock + 60) > $misc::discostep ){			# Force unlock, if stale
# TODO: Use PID instead of date and check if it's still running? (based on Steffen's idea): print "Process ($pid) is ",(kill(SIGCHLD,$pid)!=0)?'running':'not running';
						&misc::Prt("MAIN:Nodelock at ".localtime($nlock)." is stale!\n");
						&db::Update('system','value="0"','name="nodlock"');
						&db::Insert('events','level,time,source,info,class',"\"200\",\"$now\",\"NeDi\",\"Nodelock @".localtime($nlock)." is stale!\",\"nedi\"");
					}else{
						&misc::Prt("MAIN:Nodelock at ".localtime($nlock)." waiting for unlock\n","N");
						sleep $misc::pause;
					}
				}
				&db::Update('system',"value=\"$now\"",'name="nodlock"');		# Set node lock in system table...
				&db::Insert('events','level,time,source,info,class',"\"50\",\"$now\",\"NeDi\",\"MAIN:Nodelock set by PID $$\",\"nedi\"") if $misc::notify =~ /N/; 
				&misc::Prt("MAIN:Nodes table locked at ".localtime(time)."\n");
				&db::ReadNod();
				&misc::BuildNod();
				my $nret = &misc::RetireNod();
				&db::WriteNod() if !$opt{'t'};
				&db::Update('system','value="0"','name="nodlock"');			# ...unlock them again
				&misc::Prt("MAIN:Nodes table unlocked at ".localtime(time)."\n") if $misc::notify =~ /d/i;

				&misc::Prt("MAIN:$misc::nip IP and $misc::nnip non-IP nodes processed, $nret retired\n");
				&misc::Prt("MAIN:$misc::ipchg IP and $misc::ifchg IF changes detected\n") if ($misc::ipchg or $misc::ifchg);
			}
			my $nthrd = &db::Select('system','value','name="threads"');
			&misc::Prt("MAIN:$nthrd threads running right now\n");
			&db::TopRRD() if $nthrd == 1 and $opt{'S'} !~ /o/;
			&mon::AlertFlush("NeDi Discovery Alert",$misc::mq);
		}
	}else{
		print "Nothing discovered, nothing written...\n";
	}
	&db::Update('system','value=value-1','name="threads"') if !$opt{t};				# Deregister thread, if not testing
	print "END :Took " . int((time - $now)/60) . " minutes\n\n";
}

=head2 FUNCTION HELP_MESSAGE()

Display some help

B<Options> -

B<Globals> -

B<Returns> -

=cut
sub HELP_MESSAGE{
	print "\n";
	print "usage: nedi.pl [-i|-D|-t|-w|-y|-s|] <more option(s)>\n";
	print "Discovery Options  --------------------------------------------------------\n";
	print "-a ip	Add single device or ip range (e.g. 10.10.10)\n";
	print "-t ip	Test IP only, but don't write anything\n";
	print "-A cond	Add devices from DB cond=all or e.g.'loc regexp \"here\"'\n";
	print "-p	Discover LLDP,CDP,FDP or NDP neighbours\n";
	print "-o	OUI discovery (based on ARP chache entries)\n";
	print "-r	Route table discovery (on L3 devices)\n";
	print "-u file	Use specified seedlist\n";
	print "-U file	Use specified configuration\n";
	print "Actions -------------------------------------------------------------------\n";
	print "-b|B	Backup running configs|and write files to /conf\n";
	print "-S opt	Skip s=sysinfo m=modules g=sysrrd r=arp f=fwd p=disprot o=toprrd\n";
	print "	i=IFinfo, t=traf e=err d=discard b=bcast w=poe a=IPaddr A=admin O=oper\n";
	print "-F 	Use FQDNs for devices. Allows \".\" in dev names (can mess up links!)\n";
	print "-n 	Don't resolve node names via DNS\n";
	print "-N 	Don't exclude devices from nodes\n";
	print "-W 	Retry SNMP writeaccess\n\n";
	print "Other Options -------------------------------------------------------------\n";
	print "-i u pw	Initialize database and delete all RRDs & Configs\n";
	print "-k	Get ssh key and store in ~/.ssh/known_hosts\n";
	print "-s Xopt	Scan nodes, X=Tcp,Udp,Os,Fast opt=update or ip=(ip) e.g. TUFupdate\n";
	print "-w dir	Add Kismet csv files in directory to WLAN database\n";
	print "-d|D	Store internal variables and write to CLI logs|skip to debug mode\n";
	print "-v	Verbose output\n";
	print "-y	Show supported devices based on .def files (in sysobj)\n\n";
	print "Output Legend -------------------------------------------------------------\n";
	print "Statistics (lower case letters):\n";
	print "i#	Interfaces\n";
	print "j#	IF IP addresses\n";
	print "a#	ARP entries\n";
	print "f#	Forwarding entries\n";
	print "m#	Modules\n";
	print "v#	Vlans\n";
	print "c#	Config lines\n";
	print "pro x/y	Discovery protocol, route or OUI queueing (# added/# done already)\n";
	print "b#	Border hits\n\n";
	print "Warnings (upper case letters) -------------------------------------------\n";
	print "Jx	IF Addresses (a=IP m=Mask v=vrf)\n";
	print "Ax	ARP (a=arptable i=no IF index)\n";
	print "Fx(#)	Forwarding table (i=IF p=Port #=vlan x=idx w=wlan)\n";
	print "Ix	Interface d=desc n=name t=type S/s=HC/speed m=mac a=admin p=oper status,\n";
	print "	c=disc b=bcast I/O=HC octet i/o=octet e=error l=alias x=duplex v=vlan w=PoE\n";
	print "Kx	SSH keyscan e=error, s=success\n";
	print "Mx	Modules t=slot d=desc c=class h=hw f=fw s=sw n=SN m=model\n";
	print "Dx	Discover p=poe a=IP l=LLDP c=CDP f=FDP r=rout o=IP127/0 L=loop x=IFix\n";
	print "Px	CLI prep p=prev err u=no user n=user not in conf\n";
	print "Cx	CLI main c=connect p=pw e=enable i=impossible m=cmd\n";
	print "Rx	RRD d=mkdir u=update s=make sys i=make IF t=make top n=IF name\n";
	print "Sx	SNMP c=conn o=no name n=SN b=BI u=CPU util m=Mem t=Tmp y=type\n";
	print "Bx	Backup f=fetched n=new u=update w=write e=empty\n";
	print "Vx	VTP/Vlan d=VTPdom m=VTPmode n=Vl name x=ID index i=ID not #\n";
	print "---------------------------------------------------------------------------\n";
	print "NeDi $main::VERSION (C) 2001-2012 Remo Rickli & contributors\n\n";
	exit;
}
