=pod

=head1 LIBRARY
libmisc.pm

Miscellaneous functions

=head2 AUTHORS

Remo Rickli & NeDi Community

=cut

package misc;
use warnings;

use RRDs;

use vars qw($seedlist $netfilter $webdev $nosnmpdev $border $ouidev $descfilter $getfwd $timeout);
use vars qw($nedipath $backend $dbname $dbuser $dbpass $dbhost $clilib $uselogin $usessh $usepoe);
use vars qw($rrdcmd $rrdstep $rrdsize $discostep $nmapcmd $nagpipe $snmpwrite $redbuild $guiauth $locsep);
use vars qw($arpwatch $ignoredvlans $ignoredmacs $useivl $retire $arppoison $macflood);
use vars qw($notify $chka $latw $cpua $mema $tmpa $trfa $trfw $poew $pause $smtpserver $mailfrom $mailfoot);
use vars qw(%comms %login %map %useif %skipif %doip %snmpini %ouineb %sysobj %ifmac %ifip %useip);
use vars qw(%oui %arp %arpc %arpn %portprop %portnew %portdes %vlid);
use vars qw(@todo @comms @seeds @users @curcfg);

our @donenam = @doneid = @doneip = @failid = @failip = ();
our $nnip = $nip = $ipchg = $ifchg = $mq = 0;

=head2 FUNCTION ReadConf()

Searches for nedi.conf in nedi folder first then fall back to /etc. Parse
it if found or die if not.

locsep is set to a space if commented.

=head3 A note on discostep:

This is the interval for the if statistics of each discovery. Leave it the same as rrdstep, if you
just want to use nedi the old fashioned way. You can also lower rrdstep and use -A
in combination with -SsmrfpiaAO for more granular graphs.

B<Options> -

B<Globals> various misc:: varables

B<Returns> dies on missing nedi.conf

=cut
sub ReadConf {

	my $nconf = "$main::p/nedi.conf";

	$ignoredvlans = $ignoredmacs = $useivl = $border = $nosnmpdev = $descfilter = $usessh = $usepoe = "isch nid gsetzt!";
	$locsep = " ";

	if($main::opt{U}){
		$nconf = $main::opt{U}
	}
	if (-e "$nconf"){
		open  ("CONF", $nconf);
	}elsif (-e "/etc/nedi.conf"){
		open  ("CONF", "/etc/nedi.conf");
	}else{
		die "Can't find $nconf: $!\n";
	}
	my @conf = <CONF>;
	close("CONF");

	foreach my $l (@conf){
		if ($l !~ /^[#;]|^$/){
			$l =~ s/[\r\n]//g;
			my @v  = split(/\s+/,$l);
			if ($v[0] eq "comm"){
				push (@comms,$v[1]);
				$comms{$v[1]}{aprot} = $v[2];
				$comms{$v[1]}{apass} = $v[3];
				$comms{$v[1]}{pprot} = $v[4];
				$comms{$v[1]}{ppass} = $v[5];}
			elsif ($v[0] eq "usr"){
				push (@users,$v[1]);
				$login{$v[1]}{pw} = $v[2];
				$login{$v[1]}{en} = $v[3];}
			elsif ($v[0] eq "useip"){
				$useip{$v[1]} = $v[2];}
			elsif ($v[0] eq "uselogin"){$uselogin = $v[1]}
			elsif ($v[0] eq "snmpwrite"){$snmpwrite = $v[1]}
			elsif ($v[0] eq "usessh"){$usessh = $v[1]}
			elsif ($v[0] eq "skipif"){$skipif{$v[1]} = $v[2]}
			elsif ($v[0] eq "usepoe"){$usepoe = $v[1]}

			elsif ($v[0] eq "mapip"){$map{$v[1]}{ip} = $v[2]}
			elsif ($v[0] eq "maptp"){$map{$v[1]}{cp} = $v[2]}
			elsif ($v[0] eq "mapna"){$map{$v[1]}{na} = $v[2]}
			elsif ($v[0] eq "maplo"){$map{$v[1]}{lo} = $v[2]}

			elsif ($v[0] eq "nosnmpdev"){$nosnmpdev = $v[1]}
			elsif ($v[0] eq "webdev"){$webdev = $v[1]}
			elsif ($v[0] eq "netfilter"){$netfilter = $v[1]}
			elsif ($v[0] eq "border"){$border = $v[1]}
			elsif ($v[0] eq "ouidev"){$ouidev = $v[1]}
			elsif ($v[0] eq "descfilter"){$descfilter = $v[1]}

			elsif ($v[0] eq "backend"){$backend = $v[1]}
			elsif ($v[0] eq "dbname"){$dbname = $v[1]}
			elsif ($v[0] eq "dbuser"){$dbuser = $v[1]}
			elsif ($v[0] eq "dbpass"){$dbpass = $v[1]}
			elsif ($v[0] eq "dbhost"){$dbhost = $v[1]}

			elsif ($v[0] eq "clilib"){$clilib = $v[1]}

			elsif ($v[0] eq "ignoredvlans"){$ignoredvlans = $v[1]}
			elsif ($v[0] eq "ignoredmacs"){$ignoredmacs = $v[1]}
			elsif ($v[0] eq "useivl"){$useivl = $v[1]}
			elsif ($v[0] eq "getfwd"){$getfwd = $v[1]}
			elsif ($v[0] eq "retire"){$retire = $main::now - $v[1] * 86400;}
			elsif ($v[0] eq "timeout"){$timeout = $v[1]}
			elsif ($v[0] eq "arpwatch"){$arpwatch = $v[1]}
			elsif ($v[0] eq "arppoison"){$arppoison = $v[1]}
			elsif ($v[0] eq "macflood"){$macflood = $v[1]}

			elsif ($v[0] eq "rrdstep"){$rrdstep = $v[1]}
			elsif ($v[0] eq "rrdsize"){$rrdsize = $v[1]}
			elsif ($v[0] eq "rrdcmd"){$rrdcmd = $v[1]}
			elsif ($v[0] eq "nagpipe"){$nagpipe = $v[1]}

			elsif ($v[0] eq "notify"){$notify = $v[1]}
			elsif ($v[0] eq "check-alert"){$chka = $v[1]}
			elsif ($v[0] eq "latency-warn"){$latw = $v[1]}
			elsif ($v[0] eq "cpu-alert"){$cpua = $v[1]}
			elsif ($v[0] eq "mem-alert"){$mema = $v[1]}
			elsif ($v[0] eq "temp-alert"){$tmpa = $v[1]}
			elsif ($v[0] eq "traf-alert"){$trfa = $v[1]}
			elsif ($v[0] eq "traf-warn"){$trfw = $v[1]}
			elsif ($v[0] eq "poe-warn"){$poew = $v[1]}

			elsif ($v[0] eq "pause"){$pause = $v[1]}
			elsif ($v[0] eq "smtpserver"){$smtpserver = $v[1]}
			elsif ($v[0] eq "mailfrom"){$mailfrom = $v[1]}
			elsif ($v[0] eq "mailfooter"){
				$mailfoot = $l;
				$mailfoot =~ s/mailfooter\s+//;
			}
			elsif ($v[0] eq "guiauth"){$guiauth = $v[1]}
			elsif ($v[0] eq "locsep"){$locsep = $v[1]}
			elsif ($v[0] eq "redbuild"){$redbuild = $v[1]}

			elsif ($v[0] eq "nedipath"){
				$nedipath = $v[1];
				if($main::p eq "."){
					&Prt("Started with relative path!\n");
					$nedipath = $main::p;
				}else{
					if($v[1] ne $main::p){die "Please configure nedipath!\n";}
				}
			}
		}
	}
	$discostep = ($discostep)?$discostep:$rrdstep;				
	$rrdsize = ($rrdsize)?$rrdsize:'1000';
}


=head2 FUNCTION ReadSysobj()

Reads Sysobj definition file

B<Options> -

B<Globals> misc::sysobj

B<Returns> -

=cut
sub ReadSysobj {

	my ($so) = @_;

	unless( exists $sysobj{$so} ){							# Load .def if not done already
		if (-e "$main::p/sysobj/$so.def"){
			open  ("DEF", "$main::p/sysobj/$so.def");
			&Prt("SOBJ:Reading $so.def\n");
		}else{
			open  ("DEF","$main::p/sysobj/other.def");
			&Prt("SOBJ:$so.def not found, using other.def\n");
		}
		my @def = <DEF>;
		close("DEF");
		$sysobj{$so}{ty} = $so;
		$sysobj{$so}{hc} = $sysobj{$so}{mv} = $sysobj{$so}{ib} = 0;
		$sysobj{$so}{cul} = '-;;';

		foreach my $l (@def){
			if ($l !~ /^[#;]|^\s*$/){
				$l =~ s/[\r\n]//g;			# Chomp doesn't remove \r
				my @v  = split(/\t+/,$l);
				if (!defined $v[1]){$v[1] = ""}
				if ($v[0] eq "Type")		{$sysobj{$so}{ty} = $v[1]}
				elsif ($v[0] eq "OS")		{$sysobj{$so}{os} = $v[1]}
				elsif ($v[0] eq "Icon")		{$sysobj{$so}{ic} = $v[1]}
				elsif ($v[0] eq "SNMPv"){
					$sysobj{$so}{rv} = substr($v[1],0,1);
					if(substr($v[1],1,2) eq 'HC'){
						$sysobj{$so}{hc} = 128;					# Pure Highspeed 64bit counters
					}elsif(substr($v[1],1,2) eq 'MC'){
						$sysobj{$so}{hc} = 192;					# Merge Counters
					}else{
						$sysobj{$so}{hc} = 64;					# 32bit counters only
					}
				}
				elsif ($v[0] eq "Serial")	{$sysobj{$so}{sn} = $v[1]}
				elsif ($v[0] eq "Bimage")	{$sysobj{$so}{bi} = $v[1]}
				elsif ($v[0] eq "Sysdes")	{$sysobj{$so}{de} = $v[1]}
				elsif ($v[0] eq "Bridge")	{$sysobj{$so}{bf} = $v[1]}
				elsif ($v[0] eq "Dispro")	{$sysobj{$so}{dp} = $v[1]}
				elsif ($v[0] eq "Typoid")	{$sysobj{$so}{to} = $v[1]}		# tx vtur

				elsif ($v[0] eq "VLnams")	{$sysobj{$so}{vn} = $v[1]}
				elsif ($v[0] eq "VLnamx")	{$sysobj{$so}{vl} = $v[1]}
				elsif ($v[0] eq "VTPdom")	{$sysobj{$so}{vd} = $v[1]}
				elsif ($v[0] eq "VTPmod")	{$sysobj{$so}{vm} = $v[1]}

				elsif ($v[0] eq "IFname")	{$sysobj{$so}{in} = $v[1]}
				elsif ($v[0] eq "IFalia")	{$sysobj{$so}{al} = $v[1]}
				elsif ($v[0] eq "IFalix")	{$sysobj{$so}{ax} = $v[1]}
				elsif ($v[0] eq "IFdupl")	{$sysobj{$so}{du} = $v[1]}
				elsif ($v[0] eq "IFduix")	{$sysobj{$so}{dx} = $v[1]}
				elsif ($v[0] eq "Halfdp")	{$sysobj{$so}{hd} = $v[1]}
				elsif ($v[0] eq "Fulldp")	{$sysobj{$so}{fd} = $v[1]}
				elsif ($v[0] eq "InBcast")	{$sysobj{$so}{ib} = $v[1]}
				elsif ($v[0] eq "InDisc")	{$sysobj{$so}{id} = $v[1]}
				elsif ($v[0] eq "OutDisc")	{$sysobj{$so}{od} = $v[1]}
				elsif ($v[0] eq "IFvlan")	{$sysobj{$so}{vi} = $v[1]}
				elsif ($v[0] eq "IFvlix")	{$sysobj{$so}{vx} = $v[1]}
				elsif ($v[0] eq "IFpowr")	{$sysobj{$so}{pw} = $v[1]}
				elsif ($v[0] eq "IFpwix")	{$sysobj{$so}{px} = $v[1]}

				elsif ($v[0] eq "Modesc")	{$sysobj{$so}{md} = $v[1]}
				elsif ($v[0] eq "Moclas")	{$sysobj{$so}{mc} = $v[1]}
				elsif ($v[0] eq "Movalu")	{$sysobj{$so}{mv} = $v[1]}
				elsif ($v[0] eq "Mostep")	{$sysobj{$so}{mp} = $v[1]}
				elsif ($v[0] eq "Moslot")	{$sysobj{$so}{mt} = $v[1]}
				elsif ($v[0] eq "Modhw")	{$sysobj{$so}{mh} = $v[1]}
				elsif ($v[0] eq "Modsw")	{$sysobj{$so}{ms} = $v[1]}
				elsif ($v[0] eq "Modfw")	{$sysobj{$so}{mf} = $v[1]}
				elsif ($v[0] eq "Modser")	{$sysobj{$so}{mn} = $v[1]}
				elsif ($v[0] eq "Momodl")	{$sysobj{$so}{mm} = $v[1]}


				elsif ($v[0] eq "CPUutl")	{$sysobj{$so}{cpu} = $v[1]}
				elsif ($v[0] eq "MemCPU")	{
					$sysobj{$so}{mem} = $v[1];
					$sysobj{$so}{mmu} = ($v[2])?$v[2]:1;
				}
				elsif ($v[0] eq "Temp")		{
					$sysobj{$so}{tmp} = $v[1];
					$sysobj{$so}{tmu} = ($v[2])?$v[2]:1;
				}
				elsif ($v[0] eq "MemIO")	{$sysobj{$so}{cuv} = $v[1];$sysobj{$so}{cul} = "MemIO;G;Bytes"}	# Support legacy .defs
				elsif ($v[0] eq "Custom" and $v[2]){$sysobj{$so}{cuv} = $v[2];$sysobj{$so}{cul} = $v[1]}
			}
		}
	}
}

=head2 FUNCTION ReadOUIs()

Load NIC vendor database (extracts vendor information from the oui.txt and iab.txt files)
download to ./inc from:

L<http://standards.ieee.org/regauth/oui/index.shtml>

B<Options> -

B<Globals> misc::oui

B<Returns> -

=cut
sub ReadOUIs {

	open  ("OUI", "$main::p/inc/oui.txt" ) or die "no oui.txt in $main::p/inc!";			# Read OUI's first
	my @ouitxt = <OUI>;
	close("OUI");

	my @nics = grep /(base 16)/,@ouitxt;
	foreach my $l (@nics){
		$l =~ s/[\r\n]//g;
		my @m = split(/\s\s+/,$l);
		if(defined $m[2]){
			$oui{lc($m[0])} = substr($m[2],0,32);
		}
	}
	open  ("IAB", "$main::p/inc/iab.txt" ) or die "no iab.txt in $main::p/inc!";			# Now add IAB's (00-50-C2)
	my @iabtxt = <IAB>;
	close("IAB");

	@nics = grep /(base 16)/,@iabtxt;
	foreach my $l (@nics){
		$l =~ s/[\r\n]//g;
		my @m = split(/\t+/,$l);
		if(defined $m[2]){
			$m[0] = "0050C2".substr($m[0],0,3);
			$oui{lc($m[0])} = substr($m[2],0,32);
		}
	}
	my $nnic = keys %oui;
	&Prt("OUI :$nnic NIC vendor entries read\n");
}


=head2 FUNCTION GetOui()

Returns OUI vendor.

B<Options> MAC address

B<Globals> -

B<Returns> vendor

=cut
sub GetOui {

	my $coui =  "?";

	if ($_[0] =~ /^0050C2/i) {
		$coui = $oui{substr($_[0],0,9)};
	} else {
		$coui = $oui{substr($_[0],0,6)};
	}
	if (!$coui){$coui =  "?"}
	return $coui;
}


=head2 FUNCTION Strip()

Strips unwanted characters from a string. Additionally the return value
for an empty string (e.g. 0) can be specified.

B<Options> string, return

B<Globals> misc::oui

B<Returns> cleaned string

=cut
sub Strip {

	my ($str,$ret) = @_;

	if(defined $str and $str ne ''){								# only strip if it's worth it!
		$str =~ s/^\s*|\s*$//g;									# leading/trailing spaces
		$str =~ s/"//g;										# quotes
		$str =~ s/[\x00-\x1F]//g;								# below ASCII
		$str =~ s/[\x7F-\xff]//g;								# above ASCII
		$str =~ s/\s+/ /g;									# excess spaces
		return $str;
	}else{
		return (defined $ret)?$ret:'';
	}
}


=head2 FUNCTION Shif()

Shorten interface names.

B<Options> IF name

B<Globals> -

B<Returns> shortened IF name

=cut
sub Shif {

	my $n = $_[0];

	if ($n){
		$n =~ s/tengigabitethernet/Te/i;
		$n =~ s/gigabit[\s]{0,1}ethernet/Gi/i;
		$n =~ s/fast[\s]{0,1}ethernet/Fa/i;
		$n =~ s/^Ethernet/Et/;
		$n =~ s/^Serial/Se/;
		$n =~ s/^Dot11Radio/Do/;
		$n =~ s/^Wireless port\s?/Wp/;								# Former Colubris controllers
		$n =~ s/^[F|G]EC-//;									# Doesn't match telnet CAM table!
		$n =~ s/^Alcatel-Lucent //;								# ALU specific
		$n =~ s/^BayStack (.*?)- //;								# Nortel specific
		$n =~ s/^Vlan/Vl/;									# MSFC2 and Cat6k5 discrepancy!
		$n =~ s/(Port\d): .*/$1/g;								# Ruby specific
		$n =~ s/PIX Firewall|pci|motorola|power|switch|network|interface//ig;			# Strip other garbage (removed management for asa)
		$n =~ s/\s+|'//g;									# Strip unwanted characters
		return $n;
	}else{
		return "-";
	}
}


=head2 FUNCTION MapIp()

Map IP address, if specified in config.

B<Options> IP address

B<Globals> -

B<Returns> mapped IP address

=cut
sub MapIp {
	my $ip = $_[0];
	if ($map{$_[0]}{ip}){
		$ip = $map{$_[0]}{ip};
		&Prt("MAP :IP $_[0] to $ip ");
	}
	return $ip;
}


=head2 FUNCTION Ip2Dec()

Converts IP addresses to dec for efficiency in DB.

B<Options> IP address

B<Globals> -

B<Returns> dec IP

=cut
sub Ip2Dec {
	if(!$_[0]){$_[0] = 0}
	return unpack N => pack CCCC => split /\./ => shift;
}


=head2 FUNCTION Dec2Ip()

Of course we need to convert them back.

B<Options> dec IP

B<Globals> -

B<Returns> IP address

=cut
sub Dec2Ip {
	if(!$_[0]){$_[0] = 0}
	return join '.' => map { ($_[0] >> 8*(3-$_)) % 256 } 0 .. 3;
}


=head2 FUNCTION DecFix()

Return big numbers in a more readable way

B<Options> number

B<Globals> -

B<Returns> readable number

=cut
sub DecFix(){

	if($_[0] >= 1000000000){
		return int($_[0]/1000000000)."G";
	}elsif($_[0] >= 1000000){
		return int($_[0]/1000000)."M";
	}elsif($_[0] >= 1000){
		return int($_[0]/1000)."K";
	}else{
		return $_[0];
	}
}

=head2 FUNCTION GetAp()

Get APs from Kismet CSV dumps upon DB init.

B<Options> -

B<Globals> db:ap

B<Returns> -

=cut
sub GetAp {


	my $file = $File::Find::name;

	return unless -f $file;
	return unless $file =~ /csv$/;

	open  ("KCSV", "$file" ) or print "couldn't open $file\n" && return '';
	my @kcsv = <KCSV>;
	close("KCSV");

	my @aps = grep /(infrastructure)/,@kcsv;
	foreach my $l (@aps){
			$l =~ s/[\r\n]//g;
			my @f = split(/;/,$l);
			$f[3] =~ s/^(..):(..):(..):(..):(..):(..)/\L$1$2$3$4\E/;
			$db::ap{lc($f[3])} = $main::now;
   	}
}


=head2 FUNCTION GetChanges()

Find changes in device configurations.

B<Options> pointer to config arrays

B<Globals> -

B<Returns> differences as string

=cut
sub GetChanges {

	use Algorithm::Diff qw(diff);

	my $chg = '';
	my $diffs = diff($_[0], $_[1]);
	return '' unless @$diffs;

	foreach my $chunk (@$diffs) {
		foreach $line (@$chunk) {
			my ($sign, $lineno, $l) = @$line;
			if ( $l !~ /\#time:|ntp clock-period/){
				$chg .=	sprintf "%4d$sign %s\n", $lineno+1, $l;
			}
		}
	}
	return $chg;
}


=head2 FUNCTION GetGw()

Get the default gateway of your system (should work on *nix and win).

B<Options> -

B<Globals> -

B<Returns> default gw IP

=cut
sub GetGw {

	my @routes = `netstat -rn`;
	my @l = grep(/^\s*(0\.0\.0\.0|default)/,@routes);
	my @gw = split(/\s+/,$l[0]);

	if ($gw[1] eq "0.0.0.0"){
		return $gw[3] ;
	}else{
		return $gw[1] ;
	}
}


=head2 FUNCTION InitSeeds()

Queue devices to discover based on the seedlist.

B<Options> -

B<Globals> misc::todo

B<Returns> # of seeds queued

=cut
sub InitSeeds {

	my $s = 0;
	my $ip;

	if($main::opt{'u'}){
		$seedlist = "$main::opt{u}";
	}else{
		$seedlist = "$misc::nedipath/seedlist";
	}
	if($main::opt{'t'}){
		push (@todo,"testing");
		$doip{"testing"} = join('.',unpack( 'C4',gethostbyname($main::opt{'t'}) ) );
		&Prt("SEED:$main::opt{t} added for testing\n");
		$s = 1;
	}elsif ($main::opt{'a'}){
		if($main::opt{'a'} =~ /^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/){
			for($i=1;$i<255;$i++){
				my $latency = &mon::PingService("$main::opt{a}.$i",'icmp',0,0.5);
				if ($latency){
					push(@todo,"seed$i");
					$doip{"seed$i"} = "$main::opt{a}.$i";
					&Prt("SEED:$main::opt{a}.$i added for discovery\n");
					$s++;
				}
			}
		}else{
			push (@todo,$main::opt{'a'});
			$doip{$main::opt{'a'}} = join('.',unpack( 'C4',gethostbyname($main::opt{'a'}) ) );
			&Prt("SEED:$main::opt{a} added for discovery\n");
			$s = 1;
		}
	}elsif ($main::opt{'A'}){
		foreach my $dv (keys %main::dev){
			if ($main::dev{$dv}{rv}){
				push(@todo,$dv);
				$doip{$dv}		 = $main::dev{$dv}{ip};
				$snmpini{$doip{$dv}}{rc} = $main::dev{$dv}{rc};
				$snmpini{$doip{$dv}}{rv} = $main::dev{$dv}{rv};
				print "$dv, $main::dev{$dv}{ip} added for discovery\n" if $main::opt{'v'};
				$s++;
			}
		}
	}elsif (-e "$seedlist"){
		&Prt("SEED:Using $seedlist\n");
		open  (LIST, "$seedlist");
		my @list = <LIST>;
		close(LIST);
		foreach my $l (@list){
			if ($l !~ /^[#;]|^$/){
				$l =~ s/[\r\n]//g;
				my @f  = split(/\s+/,$l);
				$ip = join('.',unpack( 'C4',gethostbyname($f[0]) ) );
				if($ip){
					if($f[1]){$snmpini{$ip}{rc} = $f[1]}
					if(defined $f[2] and $f[2] =~ /[0-9]/){$snmpini{$ip}{rv} = $f[2]}
					push(@todo,"seed$s");
					$doip{"seed$s"} = $ip;
					&Prt("SEED:$ip seed$s added for discovery\n");
					$s++;
				}else{
					&Prt("SEED:Error resolving $f[0]!\n");
				}
			}
		}
	}else{
		&Prt("SEED:$seedlist not found!\n");
	}
	if (!$s and !$main::opt{'A'}) {									# Fall back to GW if no seeds found.
		&Prt("SEED:No seeds, trying default gw!\n");
		$todo[0] 	= 'seed1';
		$doip{'seed1'}	= &GetGw();
		$s = 1;
	}
	return $s;
}


=head2 FUNCTION Discover()

Discover a single device.

B<Options> device ID

B<Globals> misc::curcfg

B<Returns> -

=cut
sub Discover {

	my ($id)	= @_;
	my $start	= time;
	my $clistat	= "Init";									# CLI access status
	my $dv		= "";
	my $skip	= $main::opt{'S'};

	&misc::Prt("\nDiscover     ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++\n",sprintf("%-15.15s ",$doip{$id}) );
	if($main::opt{'A'} and $skip =~ /s/){
		my ($latency, $uptime) = &mon::GetUptime($main::dev{$id}{ip},$main::dev{$id}{rv},$main::dev{$id}{rc});
		if($latency){
			&misc::Prt("","UP:${latency}ms ");
			&misc::ReadSysobj($main::dev{$id}{so});
			$dv = $id;
		}
	}else{
		$dv  = &snmp::Identify($id);
	}
	if($dv){
		my $skip = $main::opt{'S'};
		if(exists $misc::skipif{$main::dev{$dv}{ty}}){
			$skip .= $misc::skipif{$main::dev{$dv}{ty}};
			&misc::Prt("DISC:skipif policy for $main::dev{$dv}{ty}=$misc::skipif{$main::dev{$dv}{ty}}\n");
		}elsif(exists $misc::skipif{'default'}){
			$skip .= $misc::skipif{'default'};
			&misc::Prt("DISC:default skipif policy=$misc::skipif{'default'}\n");
		}else{
			&misc::Prt("DISC:no skipif policy using -S ($main::opt{S})\n");
		}

		&snmp::Enterprise($dv,$skip);
		my $noifwrite = &snmp::Interfaces($dv,$skip);						# Get interface info
		&snmp::IfAddresses($dv) if $skip !~ /a/;						# Get IP addresses
		if($sysobj{$main::dev{$dv}{so}}{dp} and $skip !~ /p/){
			&snmp::DisProtocol($dv,$id,$sysobj{$main::dev{$dv}{so}}{dp});			# Get neighbours via LLDP, CDP or FDP
		}
		if($sysobj{$main::dev{$dv}{so}}{mt} and $skip !~ /m/){
			&snmp::Modules($dv);
		}else{
			&Prt("","  ");
		}
		&KeyScan($main::dev{$dv}{ip}) if $main::opt{'k'};

		if ($main::dev{$dv}{sv} > 3 and $skip !~ /r/ and $main::dev{$dv}{os} ne "MSM"){
# Get arp table, if  it's a layer 3 device, but ignore HP MSMs, since there's no support.

			$clistat = &cli::PrepDev($dv,"arp");						# Prepare device for cli access
			if($clistat =~ /^OK/){
				$clistat = &cli::Arp($dv);
			}
			&Prt("DISC:Cli arp = $clistat\n");
			if($clistat ne "OK-Arp"){
				&snmp::Arp($dv);
			}
			if($main::opt{r}){								# User route discovery, if -r
				&snmp::Routes($dv);
			}else{
				&Prt(""," ");
			}
		}else{
			&Prt(""," ");									# Spacer instead of L3 info.
		}
		if($sysobj{$main::dev{$dv}{so}}{bf} eq "MSM" and $getfwd and $skip !~ /f/){
			&snmp::MSMFwd($dv);
		}elsif($sysobj{$main::dev{$dv}{so}}{bf} eq "CAP" and $getfwd and $skip !~ /f/){
			&snmp::CAPFwd($dv);
		}elsif($sysobj{$main::dev{$dv}{so}}{bf} and $getfwd and $skip !~ /f/){			# Get mac address table, if  bridging is set in .def
			if($getfwd =~ /dyn|sec/){							# Using CLI to fetch forwarding table is configured?
				$clistat = &cli::PrepDev($dv,"fwd");					# Prepare device for cli access
				if($clistat =~ /^OK/){
					$clistat = &cli::BridgeFwd($dv);
				}
				&Prt("DISC:Cli bridge fwd = $clistat\n");
			}
			if($clistat ne "OK-Bridge"){
				&snmp::BridgeFwd($dv);							# Do SNMP if telnet fails or CLI not configured
			}
			&FloodFind($dv) if $notify =~ /n/i;
		}

		if($main::opt{'b'} or $main::opt{'B'}){							# Backup configurations
			if($clistat eq "OK-Bridge" or $clistat eq "OK-Arp"){				# Wait if we just got BridgeFWD or ARP via CLI to avoid hang
				select(undef, undef, undef, $cli::clipause);
			}else{
				$clistat = &cli::PrepDev($dv,"cfg");
			}
			&Prt("DISC:Cli config = $clistat\n");
			if($clistat =~ /^OK/){
				@misc::curcfg = ();							# Empty config (global due to efficiency)
				$clistat = &cli::Config($dv);
				&db::BackupCfg($dv) if $clistat =~ /^OK/;
			}
			if($clistat !~ /^(OK|not implemented)/ and $notify =~ /b/i){			# If not ok, but supported...
					my $msg = "Config backup error: $clistat";
					&Prt("DISC:$msg\n");
					&db::Insert('events','level,time,source,info,class,device',"\"150\",\"$main::now\",\"$dv\",\"$msg\",\"cfge\",\"$dv\"");
					$mq += &mon::AlertQ("$dv: $msg\n","",1,$dv) if $misc::notify =~ /B/;
			}
		}

		push (@doneid,$id);
		push (@doneip,$misc::doip{$id});
		push (@donenam, $dv);
		&DevRRD($dv,$skip) if($rrdcmd);							# RRD if enabled (after MSM BridgeFwd)
		unless($main::opt{'t'}){
			&misc::Prt("\nWriting Dev  ------------------------------------------------------------------\n");
			&db::UnStock($dv);
			&db::WriteDev($dv);
			&db::WriteInt($dv,$skip) unless $noifwrite;
			&db::WriteMod($dv)  if $skip !~ /m/;
			&db::WriteVlan($dv) if $skip !~ /s/;
			&db::WriteNet($dv)  if $skip !~ /a/;
			&db::WriteLink($dv) if $skip !~ /p/;
		}
	}else{
		push (@misc::failid,$id);
		push (@misc::failip,$misc::doip{$id});
	}
	my $s = sprintf ("%4d/%d-%ds",scalar(@misc::todo),scalar(@misc::donenam),(time - $start) );
	&Prt("DISC:ToDo/Done-Time = $s\n"," $s\n");
}


=head2 FUNCTION BuildArp()

Build arp table from Arpwatch files (if set in nedi.conf).

B<Options> -

B<Globals> misc::arp, misc::arpn, misc::arpc

B<Returns> -

=cut
sub BuildArp {

	my $nad = 0;
	my @awf = glob($arpwatch);
	chomp @awf;

	&Prt("\nBuildArp     ------------------------------------------------------------------\n");
	foreach my $f (@awf){
		&Prt("FILE:Reading $f\n");
		open  ("ARPDAT", $f ) or die "ARP:$f not found!";					# read arp.dat
		my @adat = <ARPDAT>;
		close("ARPDAT");
		foreach my $l (@adat){
			$l =~ s/[\r\n]//g;
			my @ad = split(/\s/,$l);
			my $m = sprintf "%02s%02s%02s%02s%02s%02s",split(/:/,$ad[0]);
			&Prt("ARPW:$m ");
			if(exists $portnew{$m}){
				$arp{$m}  = $ad[1];
				$rarp{$ad[1]}  = $m;
				$arpc{$ad[1]}++;							# Find multiple MACs
				&Prt("$arp{$m} added\n");
				if($ad[3]){$arpn{$m} = $ad[3]}
				$nad++;
			}else{
				&Prt("No IF found!\n");
			}
		}
	}
	&Prt("ARPW:$nad arpwatch entries used.\n");
}


=head2 FUNCTION UpNodIF()

Find most appropriate interface for a MAC address based on its metric, which is the
sum of the following criterias:

=over

=item *
0: Wlan Radios (metric reflects SNR on supported APs)

=item *
256: Every other IF

=item *
512: Links to Non-SNMP Devices like phones or APs. This supersedes regular links, with that VMs stay on downlink
to an ESXi for example.

=item *
1024: Port channel/trunk

=item *
2048: Router IF from ARP table

=item *
4096: A device link (all the above combined mustn't be higher). If the neighbor was discovered as well, it becomes 8192

=back

A letter shows (in verbose mode) how the metric was assigned:

=over

=item *
N: No-SNMP-Dev link

=item *
A: Active neighbor link

=item *
L: Undiscovered neighbor link

=item *
M: Non link metric

=back

B<Options> MAC address (with vlid if useivl is configured)

B<Globals> -

B<Returns> $newdv, $newif (empty if no better one was found), $newmet

=cut
sub UpNodIF {

	my $newdv = "";
	my $newif = "";
	my $vlan  = "";
	my $newmet= 32768;										# This should never be seen in DB!
	my $mc    = substr($_[0],0,12);									# Strip vlid from MAC

	if($_[1]){											# Node exists already...
		if($main::nod{$_[0]}{iu} < $retire){
			$newmet = 16384;								# Really bad metric forces update if interface hasn't been updated within the retirement period.
		}else{
			$newmet = $main::nod{$_[0]}{im};						# Use old if value if available
		}
	}
	&Prt("M$newmet ");
	foreach my $dv ( keys %{$portnew{$_[0]}} ){							# Cycle thru ports and use new IF, if metric is equal or better than the old one
		my $if = $portnew{$_[0]}{$dv}{po};

		my $metric = ($portprop{$dv}{$if}{nsd})?512:0;
		$metric   += ($portprop{$dv}{$if}{chn})?1024:0;
		$metric   += ($portprop{$dv}{$if}{rtr})?2048:0;

		if($portprop{$dv}{$if}{lnk}){
			if($portprop{$dv}{$if}{nsd}){							# nsd overrides lnk to keep VMs here if a ESX is discovered as Device
				&Prt("N");
			}elsif($portprop{$dv}{$if}{nal}){						# Double metric if nbr was discovered too to keep nodes on links to unreachable devs and not on any other link
				&Prt("A");
				$metric   += 8192;
			}else{
				&Prt("L");
				$metric   += 4096;
			}
		}else{
			&Prt("M");
		}

		if($metric < $newmet + 768){								# 256(snr) and 512(nosnmpdev) offset are ignored in metric calculation
			$newdv  = $dv;
			$newif  = $if;
			$newmet = $metric + ((defined $portnew{$_[0]}{$dv}{snr})?$portnew{$_[0]}{$dv}{snr}:256);
			$vlan   = $portnew{$_[0]}{$newdv}{vl};
			&Prt("$newmet $newdv $newif ");
		}else{
			&Prt("$metric($dv $if) ");
		}
	}
	if($newdv){
		if($_[1] and ($main::nod{$_[0]}{dv} ne $newdv or $main::nod{$_[0]}{if} ne $newif) ){
			$main::nod{$_[0]}{ic}++;
			&db::Insert('iftrack','mac,ifupdate,device,ifname,vlanid,ifmetric',"\"$mc\",\"$main::nod{$_[0]}{iu}\",\"$main::nod{$_[0]}{dv}\",\"$main::nod{$_[0]}{if}\",\"$main::nod{$_[0]}{vl}\",\"$main::nod{$_[0]}{im}\"") if !$main::opt{t};
			$ifchg++;
		}
		$main::nod{$_[0]}{im} = $newmet;
		$main::nod{$_[0]}{dv} = $newdv;
		$main::nod{$_[0]}{if} = $newif;
		$main::nod{$_[0]}{vl} = ($vlan =~ /[0-9]+/)?$vlan:0;
		$main::nod{$_[0]}{iu} = $main::now;
		&Prt("= $newdv $newif\n");
	}else{
		&Prt("old IF kept $main::nod{$_[0]}{dv} $main::nod{$_[0]}{if} M$main::nod{$_[0]}{im}\n");
	}
}


=head2 FUNCTION UpNodip()

IP update of a node, if IP changed or last update < $retire days ago.

B<Options> MAC address

B<Globals> main::nod

B<Returns> -

=cut
sub UpNodip {

	use Socket;

	my $mc    = substr($_[0],0,12);									# Strip vlid from MAC
	my $vl    = substr($_[0],12);									# Strip MAC from vlid
	my $upip  = 0;
	my $hasip = 0;

	if($_[1]){
		if(exists $arp{$_[0]}){
			$hasip = 1;
			if($main::nod{$_[0]}{ip} ne $arp{$_[0]} ){
				$upip = 1;
				$main::nod{$_[0]}{ac}++;
				my $dip = &Ip2Dec($main::nod{$_[0]}{ip});
				&db::Insert('iptrack','mac,ipupdate,name,nodip,vlanid,device',"\"$mc\",\"$main::now\",\"$main::nod{$_[0]}{na}\",\"$dip\",\"$vl\",\"$main::nod{$_[0]}{dv}\"") unless $main::opt{'t'};
				$ipchg++;
			}elsif($main::nod{$_[0]}{au} < $retire){					# Same IP forever, force update
				$upip = 1;
			}
		}else{
			$main::nod{$_[0]}{al}++ if $main::nod{$_[0]}{ip} ne '0.0.0.0';			# IP lost (aged out of router's arp table) if node got one before
		}
	}else{
		if(exists $arp{$_[0]}){
			$hasip = 1;
			$upip  = 1;
		}else{
			$main::nod{$_[0]}{ip} = '0.0.0.0';
		}
	}

	if($upip){
		$main::nod{$_[0]}{au} = $main::now;
		$main::nod{$_[0]}{ip} = $arp{$_[0]};
		$main::nod{$_[0]}{av} = $arpc{$_[0]};
		if(exists $arpn{$_[0]} and $arpn{$_[0]}){						# ARPwatch got a name, ...
			$main::nod{$_[0]}{na} = $arpn{$_[0]};
		}elsif(!$main::opt{n}){
			my $dnsna = gethostbyaddr(inet_aton($arp{$_[0]}), AF_INET);
			$main::nod{$_[0]}{na} = ($dnsna)?$dnsna:"";					# Only use if we got something!
		}
	}
	return $hasip;
}


=head2 FUNCTION BuildNod()

Build the nodes from the arp and cam (for non-IP) tables.

B<Options> -

B<Globals> main::nod

B<Returns> -

=cut
sub BuildNod {

	&Prt("\nBuildNod     ------------------------------------------------------------------\n");
	foreach my $mcvl ( keys %portnew ){
		my $mc = substr($mcvl,0,12);
		my $isdev = (exists $ifmac{$mc})?join(", ",keys %{$misc::ifmac{$mc}}):0;
		for (@doneid){										# Allegedly more efficient than grepping
			if($_ eq $mc){$isdev = $mc;last;}
		}
		if(exists $arp{$mcvl}){
			$isdev = $arp{$mcvl} if exists $ifip{$arp{$mcvl}};
			for (@doneip){
				if($_ eq $arp{$mcvl}){$isdev = $arp{$mcvl};last;}
			}
		}
		&Prt("NODE:$mcvl ");
		if(!$isdev or $main::opt{'N'}){								# Don't add devices to nodes unless desired
			my $nodex = 0;
			if(exists $main::nod{$mcvl}){
				$nodex = 1;
			}else{
				$main::nod{$mcvl}{na} = "-";
				$main::nod{$mcvl}{fs} = $main::now;
				$main::nod{$mcvl}{ic} = 0;
				$main::nod{$mcvl}{ac} = 0;
				$main::nod{$mcvl}{al} = 0;
				$main::nod{$mcvl}{av} = 0;
				$main::nod{$mcvl}{ou} = 0;
				$main::nod{$mcvl}{tp} = 0;
				$main::nod{$mcvl}{up} = 0;
				$main::nod{$mcvl}{os} = '';
				$main::nod{$mcvl}{ty} = '';
			}
			$main::nod{$mcvl}{nv} = &GetOui($mc);
			$main::nod{$mcvl}{ls} = $main::now;
			if(&UpNodip($mcvl,$nodex)){
				$nip++;
			}else{
				$nnip++;
			}
			&Prt(sprintf("%-8.8s ",$main::nod{$mcvl}{na}) );
			&UpNodIF($mcvl,$nodex);
			if(!$nodex and $misc::notify =~ /f/i){
				my $msg = "Node $mc appeared on $main::nod{$mcvl}{if} Vl$main::nod{$mcvl}{vl} as $main::nod{$mcvl}{na} with IP $main::nod{$mcvl}{ip}";
				&Prt("NODE:$msg\n");
				&db::Insert('events','level,time,source,info,class,device',"\"100\",\"$main::now\",\"$main::nod{$mcvl}{dv}\",\"$msg\",\"sec\",\"$main::nod{$mcvl}{dv}\"");
				$mq += &mon::AlertQ("$main::nod{$mcvl}{dv}: $msg\n","",1,$main::nod{$mcvl}{dv}) if $notify =~ /F/;
			}
		}else{
			#delete $main::nod{$mcvl} if exists $main::nod{$mcvl};				# Delete if is device TODO really enable? Causes reappearing nodes, if ARP times out too quickly!
			&Prt("is device $isdev\n");
		}

	}
}


=head2 FUNCTION FloodFind()

Detect potential Switch flooders, based on population.

B<Options> device

B<Globals> -

B<Returns> - (generates events)

=cut
sub FloodFind {

	my ($dv) = @_;
	my $nfld = 0;

	&Prt("\nFloodFind    ------------------------------------------------------------------\n");
	foreach my $if ( keys %{$portprop{$dv}} ){
		if(	$portprop{$dv}{$if}{pop} and
			!$portprop{$dv}{$if}{rtr} and
			!$portprop{$dv}{$if}{lnk} and
			!$portprop{$dv}{$if}{chn} and
			!$portprop{$dv}{$if}{nsd} and
			$portprop{$dv}{$if}{pop} > $macflood){
			my $msg = "$portprop{$dv}{$if}{pop} MAC entries exceed threshold of $macflood on $if";
			&Prt("FLOD:$msg\n");
			&db::Insert('events','level,time,source,info,class,device',"\"150\",\"$main::now\",\"$dv\",\"$msg\",\"sec\",\"$dv\"");
			$mq += &mon::AlertQ("$dv: $msg\n","",1,$dv) if $notify =~ /N/;
			$nfld++;
		}
	}
	&Prt("FLOD:$nfld IFs learned more than $macflood MACs\n");
}


=head2 FUNCTION RetireNod()

Remove nodes  which have been inactive longer than $misc::retire days.

B<Options> -

B<Globals> main::nod

B<Returns> -

=cut
sub RetireNod {

	my $nret = 0;

	&Prt("\nRetireNod    ------------------------------------------------------------------\n");
	foreach my $mcvl ( keys %main::nod ){

		if ($main::nod{$mcvl}{ls} < $retire){
			my $mc  = substr($mcvl,0,12);
			my $vl  = substr($mcvl,12);

			my $vlmatch = ($useivl and $vl =~ /$useivl/)?"AND vlanid=\"$vl\"":"";
			my $ifd = &db::Delete('iftrack',"mac=\"$mc\" $vlmatch");
			my $ipd = &db::Delete('iptrack',"mac=\"$mc\" $vlmatch");
			&Prt("RNOD:Remove $mc $main::nod{$mcvl}{na} $main::nod{$mcvl}{ip} (".localtime($main::nod{$mcvl}{ls}).", $ifd iftrack, $ipd iptrack)\n");
			delete $main::nod{$mcvl};
			$nret++;
		}
	}
	return $nret;
}


=head2 FUNCTION DevRRD()

Creates system and IF RRDs if necessary and then updates them.

B<Options> device name

B<Globals> -

B<Returns> -

=cut
sub DevRRD {

	my ($na,$skip)= @_;
	my $err = 0;
	my $dok = 1;
	my $dv  = $na;
	$dv     =~ s/([^a-zA-Z0-9_.-])/"%" . uc(sprintf("%2.2x",ord($1)))/eg;
	my @cul = split(/;/, $main::dev{$na}{cul});
	$cul[0] =~ s/[^-a-zA-Z0-9]//g;
	my $typ = (defined $cul[1] and $cul[1] eq "C")?"COUNTER":"GAUGE";

	&Prt("\nDevRRD       ------------------------------------------------------------------\n");
	$dok = mkdir ("$nedipath/rrd/$dv", 0755) unless -e "$nedipath/rrd/$dv";
	if($dok){
		if($main::opt{'t'}){
			&misc::Prt("DRRD:Testing $nedipath/rrd/$dv/system.rrd CPU:$main::dev{$na}{cpu} MEM:$main::dev{$na}{mcp} CUS:$main::dev{$na}{cuv} TEMP:$main::dev{$na}{tmp}\n");
			&misc::Prt("DRRD:IFName   Inoct    Outoct   Inerr  Outerr Indis  Outdis Inbcst\n");
		}else{
			unless(-e "$nedipath/rrd/$dv/system.rrd"){
				my $ds = 2 * $rrdstep;
				RRDs::create("$nedipath/rrd/$dv/system.rrd","-s","$rrdstep",
						"DS:cpu:GAUGE:$ds:0:100",
						"DS:memcpu:GAUGE:$ds:0:U",
						"DS:".lc($cul[0]).":$typ:$ds:0:U",
						"DS:temp:GAUGE:$ds:-1000:1000",
						"RRA:AVERAGE:0.5:1:$rrdsize",
	      					"RRA:AVERAGE:0.5:10:$rrdsize"
						);
				$err = RRDs::error;
			}
			if($err){
				&Prt("DRRD:Can't create $nedipath/rrd/$dv/system.rrd\n","Rs");
			}elsif($skip !~ /g/){
				RRDs::update "$nedipath/rrd/$dv/system.rrd","N:$main::dev{$na}{cpu}:$main::dev{$na}{mcp}:$main::dev{$na}{cuv}:$main::dev{$na}{tmp}";
				$err = RRDs::error;
				if($err){
					&Prt("DRRD:Can't update $nedipath/rrd/$dv/system.rrd\n","Ru");
				}else{
					&Prt("DRRD:Updated $nedipath/rrd/$dv/system.rrd\n");
				}
			}
		}

		return if $skip =~ /t/ and $skip =~ /e/ and $skip =~ /b/ and $skip =~ /d/;
		$err = 0;

		foreach my $i ( keys %{$main::int{$na}} ){
			if(exists $main::int{$na}{$i}{ina}){						# Avoid errors due empty ifnames
				if($main::opt{'t'}){
					&misc::Prt(sprintf ("DRRD:%-8.8s %-8.8s %-8.8s %-6.6s %-6.6s %-6.6s %-6.6s %-6.6s\n",$main::int{$na}{$i}{ina},$main::int{$na}{$i}{ioc},$main::int{$na}{$i}{ooc},$main::int{$na}{$i}{ier},$main::int{$na}{$i}{oer},$main::int{$na}{$i}{idi},$main::int{$na}{$i}{odi},$main::int{$na}{$i}{ibr}) );
				}else{
					$irf =  $main::int{$na}{$i}{ina};
					$irf =~ s/([^a-zA-Z0-9_.-])/"%" . uc(sprintf("%2.2x",ord($1)))/eg;
					unless(-e "$nedipath/rrd/$dv/$irf.rrd"){
						my $ds = 2 * $rrdstep;
						RRDs::create("$nedipath/rrd/$dv/$irf.rrd","-s","$rrdstep",
								"DS:inoct:COUNTER:$ds:0:1E12",
								"DS:outoct:COUNTER:$ds:0:1E12",
								"DS:inerr:COUNTER:$ds:0:1E9",
								"DS:outerr:COUNTER:$ds:0:1E9",
								"DS:indisc:COUNTER:$ds:0:1E9",
								"DS:outdisc:COUNTER:$ds:0:1E9",
								"DS:inbcast:COUNTER:$ds:0:1E9",
								"RRA:AVERAGE:0.5:1:$rrdsize",
								"RRA:AVERAGE:0.5:10:$rrdsize"
								);
						$err = RRDs::error;
					}
					if($err){
						&Prt("ERR :RRD $nedipath/rrd/$dv/$irf.rrd $err\n","Ri($irf)");
					}else{
						RRDs::update "$nedipath/rrd/$dv/$irf.rrd","N:$main::int{$na}{$i}{ioc}:$main::int{$na}{$i}{ooc}:$main::int{$na}{$i}{ier}:$main::int{$na}{$i}{oer}:$main::int{$na}{$i}{idi}:$main::int{$na}{$i}{odi}:$main::int{$na}{$i}{ibr}";
						$err = RRDs::error;
						if($err){
							&Prt("ERR :RRD $nedipath/rrd/$dv/$irf.rrd $err\n","Ru($irf)");
						}else{
							&Prt("DRRD:Updated $nedipath/rrd/$dv/$irf.rrd\n");
						}
					}
				}
			}else{
				&Prt("DRRD:No IF name for IF-index $irf\n","Rn($irf)");
			}
		}
	}else{
		&Prt("DRRD:Can't create directory $nedipath/rrd/$dv\n","Rd");
	}
}


=head2 FUNCTION WriteCfg()

Creates a directory with device name, if necessary and writes its
configuration to a file (with a timestamp as name).

B<Options> device name

B<Globals> -

B<Returns> -

=cut
sub WriteCfg {

	use POSIX qw(strftime);

	my ($dv)= @_;
	if (-e "$nedipath/conf/$dv"){
		$ok = 1;
	}else{
		$ok = mkdir ("$nedipath/conf/$dv", 0755);
	}
	if($ok and open (CF, ">$nedipath/conf/$dv/".strftime ("%Y-%m%d-%H%M.cfg", localtime($main::now) ) ) ){
		foreach ( @curcfg ){
			print CF "$_\n";
		}
		close (CF);
	}else{
		&Prt("Err :Writing config $nedipath/conf/$dv","Bw");
	}
}


=head2 FUNCTION Daemonize()

Fork current programm and detatch from cli.

B<Options> -

B<Globals> -

B<Returns> -

=cut
sub Daemonize {

	use POSIX qw(setsid);

	&Prt(" daemonizing");
	defined(my $pid = fork)   or die "Can't fork: $!";
	exit if $pid;
	setsid                    or die "Can't start a new session: $!";
	umask 0;
}


=head2 FUNCTION RetrVar()

Retrieve variables previousely stored in .db files for debugging.

B<Options> -

B<Globals> all important globals (see code)

B<Returns> -

=cut
sub RetrVar{

	use Storable;

	my $sysobj = retrieve("$main::p/sysobj.db");
	%sysobj = %$sysobj;
	my $portnew = retrieve("$main::p/portnew.db");
	%portnew = %{$portnew};
	my $portprop = retrieve("$main::p/portprop.db");
	%portprop = %$portprop;
	my $doip = retrieve("$main::p/doip.db");
	%doip = %$doip;
	my $arp = retrieve("$main::p/arp.db");
	%arp = %$arp;
	my $ifmac = retrieve("$main::p/ifmac.db");
	%ifmac = %$ifmac;

	my $donenam = retrieve("$main::p/donenam.db");
	@donenam = @$donenam;
	my $doneid = retrieve("$main::p/doneid.db");
	@doneid = @$doneid;
	my $doneip = retrieve("$main::p/doneip.db");
	@doneip = @$doneip;


	my $dev = retrieve("$main::p/dev.db");
	%main::dev = %$dev;
	my $net = retrieve("$main::p/net.db");
	%main::net = %$net;
	my $int = retrieve("$main::p/int.db");
	%main::int = %$int;
	my $vlan = retrieve("$main::p/vlan.db");
	%main::vlan = %$vlan;
}


=head2 FUNCTION StorVar()
Write important variables in .db files for debugging.

B<Options> -

B<Globals> -

B<Returns> -

=cut
sub StorVar{

	use Storable;

	store \%sysobj, "$main::p/sysobj.db";
	store \%portnew, "$main::p/portnew.db";
	store \%portprop, "$main::p/portprop.db";
	store \%doip, "$main::p/doip.db";
	store \%arp, "$main::p/arp.db";
	store \%ifmac, "$main::p/ifmac.db";

	store \@donenam, "$main::p/donenam.db";
	store \@doneid, "$main::p/doneid.db";
	store \@doneip, "$main::p/doneip.db";

	store \%main::dev, "$main::p/dev.db";
	store \%main::int, "$main::p/int.db";
	store \%main::net, "$main::p/net.db";
	store \%main::vlan, "$main::p/vlan.db";
}


=head2 FUNCTION Prt()

Print output based on verbosity or buffer into variable in case
of multiple threads.

B<Options> Short output, verbose output

B<Globals> -

B<Returns> -

=cut
sub Prt{
	if($main::opt{'v'}){
		print "$_[0]" if $_[0];
	}elsif($_[1]){
		print "$_[1]";
	}
}

=head2 FUNCTION KeyScan()

Useful with strict host key checking enabled. Invoked with -k the ssh
keys will be stored in the users .ssh directory. Should only be used at
the first discovery.

B<Options> device IP

B<Globals> -

B<Returns> -

=cut
sub KeyScan{

	&Prt("\nKeyScan       -----------------------------------------------------------------\n");
	my $res = `ssh-keyscan $_[0] 2>&1 >> ~/.ssh/known_hosts`;
	if(!$res){
		&Prt("ERR :ssh-keyscan for $_[0] failed\n","Ke");
	}else{
		chomp($res);
		&Prt("KEY :$res added to ~/.ssh/known_hosts\n","Ks");
	}
}

=head2 FUNCTION ResolveName()

Resolves IP via DNS or find in DB

B<Options> DNS Name

B<Globals> -

B<Returns> IP/0

=cut
sub ResolveName{
	my $hip = gethostbyname($_[0]);
	if(defined $hip){
		return join('.',unpack( 'C4',$hip ) );
	}elsif(exists $main::dev{$_[0]}){
		return $main::dev{$_[0]}{ip};
	}else{
		return 0;
	}
}

1;
