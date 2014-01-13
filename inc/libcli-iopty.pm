=pod

=head1 LIBRARY
libcli-iopty.pm

Net::Telnet/IO::Pty based Functions

Only needs libio-pty-perl and relies on the ssh binary for secured connections

Ubuntu install hint: apt-get install libio-pty-perl

PrepDev() tries to find credentials on new devices (or if cliport set to 0).
After that BridgeFwd() reads MAC table on supported switches if PrepDev()
confirmed support (only IOS at the time).

Config() fetches the configuration and stores the "interesting" part.
All functions use the universal EnableDev() to get into enable mode.
The $obj->get is used to avoid problems with ^M and Escape sequences...

-d option shows more details on errors and pre-match and actual matches and
also creates input.log and output.log (open extra terminals with tail -f
on them to see what's happening right away)

=head2 AUTHORS

Remo Rickli & NeDi Community

=cut

package cli;
use warnings;

use Net::Telnet;

eval 'use IO::Pty();';
if ($@){
	$misc::usessh = 'never';						# Keeps nedi working without io::pty
	&misc::Prt("PTY :Not available\n");
}else{
	&misc::Prt("PTY :Loaded\n");
}

use vars (%cmd);

=head2 Important Variables

$clipause: Increase if devices hang between connects


$cmd: Holds commands, expected prompts and other OS specific parameters needed to handle
CLI access.

=over

=item *
ropr: Read only prompt, if no readonly prompt is used set to some string which won't occur otherwhise.

=item *
enpr: Enable prompt

=item *
conf: Command to display config

=item *
strt: Match start of config (use a . to match anything)

=item *
page: Disable paging

=item *
dfwd: Show dynamic bridge forwarding table

=item *
sfwd: Show static bridge forwarding table

=item *
arp: Show arp table

=back

=head2 Tips & Tricks

=head3 OS Modes


OS        Read    Enable        Config

=============================================

IOS       Name>   Name#         Name(config)#

CatOS     Name>   Name>(enable) -

ProCurve  Name>   Name#         Name(config)# 

Comware   <Name>  -             [Name]

JunOS     -       Name>         Name#

ESX       -       Name#         -


=head3 Enable VMware ESX support

CDP: esxcfg-vswitch -B both vSwitch1
SNMP: Google is your friend...

=over

=item *
vi /etc/vmware/snmp.xml to <enabled>true</enabled><communities>public</communities>

=item *
/sbin/services.sh restart

=back

=cut

our $clipause = 1;

# CISCO
$cmd{'IOS-old'}{'ropr'} = '(.+?)>\s?$';
$cmd{'IOS-old'}{'enpr'} = '(.+?)#\s?$';
$cmd{'IOS-old'}{'conf'} = 'show run';
$cmd{'IOS-old'}{'strt'} = '^Current';
$cmd{'IOS-old'}{'page'} = 'terminal length 0';
$cmd{'IOS-old'}{'dfwd'} = 'sh mac-address-table dyn';							# Older IOS, tx Rufer & Eviltrooper
$cmd{'IOS-old'}{'sfwd'} = 'sh port-security addr';							# tx Duane Walker

$cmd{'IOS'}{'ropr'} = '(.+?)>\s?$';
$cmd{'IOS'}{'enpr'} = '(.+?)#\s?$';
$cmd{'IOS'}{'conf'} = 'show run';
$cmd{'IOS'}{'strt'} = '^Current';
$cmd{'IOS'}{'page'} = 'terminal length 0';
$cmd{'IOS'}{'dfwd'} = 'show mac address-table dynamic';
$cmd{'IOS'}{'sfwd'} = 'sh port-security addr';								# tx Duane Walker

$cmd{'IOS-wl'}{'ropr'} = $cmd{'IOS'}{'ropr'};
$cmd{'IOS-wl'}{'enpr'} = $cmd{'IOS'}{'enpr'};
$cmd{'IOS-wl'}{'conf'} = 'show run';
$cmd{'IOS-wl'}{'strt'} = '^Current';
$cmd{'IOS-wl'}{'page'} = 'terminal length 0';
$cmd{'IOS-wl'}{'dfwd'} = 'sh bridge | exclude \*\*\*';
$cmd{'IOS-wl'}{'wsnr'} = 'show dot11 statistics client-traffic'; # Credits to HB9DDO
#C-AP1231#show dot11 statistics client-traffic
# 3-0021.bdfc.0e39 pak in 4274 bytes in 236707 pak out 508 bytes out 140060
#      dup 1 decrpyt err 0 mic mismatch 0 mic miss 0
#      tx retries 24 data retries 24 rts retries 0
#      signal strength 63 signal quality 37
#
# SNMP snmpwalk -cpublic -v2c 10.10.10.7 1.3.6.1.4.1.9.9.273.1.3.1.1.4  (ssid, snr)

$cmd{'IOS-fw'}{'ropr'} = $cmd{'IOS'}{'ropr'};
$cmd{'IOS-fw'}{'enpr'} = $cmd{'IOS'}{'enpr'};
$cmd{'IOS-fw'}{'conf'} = 'show run';
$cmd{'IOS-fw'}{'strt'} = '^PIX|ASA';
#$cmd{'IOS-fw'}{'page'} = 'no pager';									# PIX 6.3
$cmd{'IOS-fw'}{'page'} = 'no terminal pager';								# PIX 8.0.3, ASA 8.2
$cmd{'IOS-fw'}{'arp'} = 'sh arp';

$cmd{'IOS-fv'}{'ropr'} = $cmd{'IOS'}{'ropr'};
$cmd{'IOS-fv'}{'enpr'} = $cmd{'IOS'}{'enpr'};
$cmd{'IOS-fv'}{'conf'} = 'show run';
$cmd{'IOS-fv'}{'strt'} = '^FWSM';
$cmd{'IOS-fv'}{'page'} = 'terminal pager 0';

$cmd{'IOS-css'}{'prom'} = '/# $/';									# Thanks to kai
$cmd{'IOS-css'}{'conf'} = 'show run';
$cmd{'IOS-css'}{'strt'} = '^!Generated' ;

$cmd{'CatOS'}{'ropr'} = '(.+)>\s?$';
$cmd{'CatOS'}{'enpr'} = '(.+)>\s?\(enable\)\s?$';
$cmd{'CatOS'}{'dfwd'} = 'sh cam dyn';
$cmd{'CatOS'}{'conf'} = 'show conf';
$cmd{'CatOS'}{'strt'} = '^begin';
$cmd{'CatOS'}{'page'} = 'set length 0';

$cmd{'NXOS'}{'ropr'} = $cmd{'IOS'}{'ropr'};
$cmd{'NXOS'}{'enpr'} = $cmd{'IOS'}{'enpr'};
$cmd{'NXOS'}{'dfwd'} = 'sh cam dyn';
$cmd{'NXOS'}{'conf'} = 'show running-config include-switch-profile';
$cmd{'NXOS'}{'strt'} = '^begin';
$cmd{'NXOS'}{'page'} = 'set length 0';

# # Software|!PLATFORM|# What:| General System Information)|\*\*\* CORE|<config>|\sversion TODO assign the 'strt' strings...

# BROCADE
$cmd{'Ironware'}{'ropr'} = $cmd{'IOS'}{'ropr'};
$cmd{'Ironware'}{'enpr'} = $cmd{'IOS'}{'enpr'};
$cmd{'Ironware'}{'conf'} = 'show run';
$cmd{'Ironware'}{'strt'} = '.';
$cmd{'Ironware'}{'page'} = 'skip-page-display';

# JUNIPER
$cmd{'JunOS'}{'ropr'} = 'GitsDoNid';
$cmd{'JunOS'}{'enpr'} = '(.+?)>\s?$';
$cmd{'JunOS'}{'conf'} = 'show configuration | no-more';
#$cmd{'JunOS'}{'dfwd'} = 'show ethernet-switching table | no-more';					# Sneuser: for switches only
$cmd{'JunOS'}{'strt'} = '^## Last commit';

# HP
$cmd{'Comware'}{'ropr'} = 'GitsDoNid';
$cmd{'Comware'}{'enpr'} = '(.+?)>\s?$';
$cmd{'Comware'}{'conf'} = 'display current';
$cmd{'Comware'}{'strt'} = '.';
$cmd{'Comware'}{'page'} = 'screen-length disable';

$cmd{'ProCurve'}{'ropr'} = '(.+?)>\s?(\x1b\[[;\?0-9A-Za-z]+)+$';					# Match them nasty Escapes!
$cmd{'ProCurve'}{'enpr'} = '(.+?)#\s?(\x1b\[[;\?0-9A-Za-z]+)+$';
$cmd{'ProCurve'}{'conf'} = 'show run';
$cmd{'ProCurve'}{'strt'} = '^Running';
$cmd{'ProCurve'}{'page'} = 'no page';

$cmd{'MSM'}{'ropr'} = $cmd{'IOS'}{'ropr'};
$cmd{'MSM'}{'enpr'} = $cmd{'IOS'}{'enpr'};
$cmd{'MSM'}{'conf'} = 'show all conf';
$cmd{'MSM'}{'strt'} = '.';

$cmd{'SROS'}{'ropr'} = $cmd{'IOS'}{'ropr'};
$cmd{'SROS'}{'enpr'} = $cmd{'IOS'}{'enpr'};
$cmd{'SROS'}{'conf'} = 'show run';
$cmd{'SROS'}{'strt'} = '.';
$cmd{'SROS'}{'page'} = 'terminal length 0';

$cmd{'TMS'}{'ropr'} = $cmd{'IOS'}{'ropr'};
$cmd{'TMS'}{'enpr'} = $cmd{'IOS'}{'enpr'};
$cmd{'TMS'}{'conf'} = 'show run';
$cmd{'TMS'}{'strt'} = '.';
$cmd{'TMS'}{'page'} = 'no page';

# ENTERASYS
$cmd{'EOS'}{'ropr'} = $cmd{'IOS'}{'ropr'};
$cmd{'EOS'}{'enpr'} = $cmd{'IOS'}{'enpr'};
$cmd{'EOS'}{'conf'} = 'show run';
$cmd{'EOS'}{'strt'} = '!PLATFORM';
$cmd{'EOS'}{'page'} = 'terminal length 0';

# AVAYA
$cmd{'Nortel'}{'ropr'} = $cmd{'IOS'}{'ropr'};
$cmd{'Nortel'}{'enpr'} = $cmd{'IOS'}{'enpr'};
$cmd{'Nortel'}{'conf'} = 'show run';
$cmd{'Nortel'}{'strt'} = '.';
$cmd{'Nortel'}{'page'} = 'terminal length 0';

# EXTREME
$cmd{'Xware'}{'ropr'} = $cmd{'IOS'}{'ropr'};
$cmd{'Xware'}{'enpr'} = $cmd{'IOS'}{'enpr'};
$cmd{'Xware'}{'conf'} = 'show configuration';
$cmd{'Xware'}{'strt'} = 'Software Version';
$cmd{'Xware'}{'page'} = 'disable clipaging session';

$cmd{'XOS'}{'ropr'} = $cmd{'IOS'}{'ropr'};
$cmd{'XOS'}{'enpr'} = $cmd{'IOS'}{'enpr'};
$cmd{'XOS'}{'conf'} = 'show configuration';
$cmd{'XOS'}{'strt'} = '^#';
$cmd{'XOS'}{'page'} = 'disable clipaging';

# VMWARE
$cmd{'ESX'}{'ropr'} = 'GitsDoNid';
$cmd{'ESX'}{'enpr'} = '(.+?)#\s$';
#$cmd{'ESX'}{'conf'} = 'cat /etc/vmware/esx.conf';							# Backs up main config
$cmd{'ESX'}{'conf'} = 'for file in /vmfs/volumes/datastore1/*/*.vmx; do echo \#== $file ================================; cat $file; done';	# List each VM config
$cmd{'ESX'}{'strt'} = '.';


=head2 FUNCTION Connect()

Connects to a device using telnet or SSH and sets user if enable is ok

Verbose output is divided into different stages:

CLI|SHH|TEL:check transport & policy conformance

CLI0:connecting

CLI1:detect anykey then login prompt

CLI2:login prompt without anykey

CLI3:1st level login

CLI4:Enable required?

CLI5:Enabling prompts for username

CLI6:Enable password with username

CLI7:Enable password without username

CLI8:Status feedback

B<Options> ip, port, user, os

B<Globals> -

B<Returns> session, status

=cut
sub Connect {

	my ($ip, $po, $us, $os) = @_;

	my ($session, $err, $pty, $pre, $match);
	my $next = $err = "";
	my $errmod = 0?'die':'return';									# set to 1 for debugging, if necessary
	my $inlog  = $main::opt{'d'}?'input.log':'';
	my $outlog = $main::opt{'d'}?'output.log':'';

	my ($realus,$usidx) = split(/;/,$us);								# This allows for multiple pw for same user indexed by ;x
	if($po == 22){
		my $known = "-o 'StrictHostKeyChecking no'";
		if($misc::usessh =~ /never/){
			&misc::Prt("CLI :ssh connection prohibited by usessh policy\n");
			return (undef, "connection prohibited by usessh policy");
		}elsif($misc::usessh =~ /known/){
			$known = "";
		}
		&misc::Prt("SSH :$us@$ip:$po Tout:${misc::timeout}s OS:$os EN:$cmd{$os}{'enpr'}\n");
		$pty  = &Spawn("ssh $known -l $realus $ip");
		$session = new Net::Telnet(	fhopen		=> $pty,
						Timeout		=> $misc::timeout + 4,			# Add 4s to factor in auth server and ssh on slow devs
						Prompt		=> "/$cmd{$os}{'enpr'}/",
						Output_log	=> $inlog,
						Input_log	=> $outlog,
						Telnetmode	=> 0,
						Cmd_remove_mode => 1,
						Output_record_separator => "\r",
						Errmode		=> $errmod);
	}elsif($po != 1){
		if($misc::usessh =~ /always/){
			&misc::Prt("CLI :telnet connection prohibited by usessh policy\n");
			return (undef, "connection prohibited by usessh policy");
		}
		&misc::Prt("TEL :$us@$ip:$po Tout:${misc::timeout}s OS:$os EN:$cmd{$os}{'enpr'}\n");
		$session = new Net::Telnet(	Host		=> $ip,
						Port		=> $po,
						Timeout		=> $misc::timeout + 2,			# Add 2s to factor in auth server timeout
						Prompt		=> "/$cmd{$os}{'enpr'}/",
						Input_log	=> $inlog,
						Output_log	=> $outlog,
						Errmode		=> $errmod);
	}else{
		&misc::Prt("CLI :connection disabled\n");
		return (undef, "connection disabled");
	}
	return (undef, "connection error on port $po") if !defined $session;						# To catch failed connections

	($pre, $match) = $session->waitfor("/are you sure|offending key|modulus too small|connection refused|ssh_exchange_identification|any key|Ctrl-Y|$misc::uselogin|password:|$cmd{$os}{ropr}|$cli::cmd{$os}{enpr}/i");
	$err = $session->errmsg;
	if($err){											# on OBSD $err=pattern match read eof
		$session->close if defined $session;
		&misc::Prt("ERR :$err\n");
		return (undef, "connection $err");
	}elsif($match =~ /connection refused/i){
		$session->close if defined $session;
		&misc::Prt("CLI0:Connection refused\n");						# on Linux $match=Connection refused
		return (undef, "connection refused");
	}elsif($match =~ /Selected cipher type not supported/){
		$session->close if defined $session;
		&misc::Prt("CLI :Selected cipher type not supported\n");				# Sneuser: Juniper with Export image, you need a domestic image!
		return (undef, "connection cipher type not supported");
	}elsif($match =~ /ssh_exchange_identification/i){
		$session->close if defined $session;
		&misc::Prt("CLI0:Connection ssh_exchange_identification\n");
		return (undef, "connection ssh_exchange_identification");
	}elsif($match =~ /are you sure/i){								# StrictHostKeyChecking
		$session->close if defined $session;
		&misc::Prt("CLI0:Turn StrictHostKeyChecking off or add key\n");
		return (undef, "connection hostkey not in known_hosts");
	}elsif($match =~ /offending key/i){								# Hostkey changed
		$session->close if defined $session;
		&misc::Prt("CLI0:Hostkey changed\n");
		return (undef, "connection hostkey changed");
	}elsif($match =~ /modulus too small/i){								# Size matters after all...
		$session->close if defined $session;
		&misc::Prt("CLI0:Hostkey too small\n");
		return (undef, "connection hostkey too small");
	}elsif($match =~ /any key|Ctrl-Y/i){
		&misc::Prt("CLI1:Matched $match, sending ctrl-Y\n");
		$session->put("\cY");									# Since Nortel wants Ctrl-Y...
		($pre, $match) = $session->waitfor("/$misc::uselogin|password:|$cmd{$os}{ropr}|$cmd{$os}{enpr}/i");
		if($match =~ /$misc::uselogin/i){
			&misc::Prt("CLI1:Matched $match, sending username\n");
			$next = "us";
		}elsif($match =~ /password:/i){
			&misc::Prt("CLI1:Matched $match, sending password\n");
			$next = "pw";
		}
	}elsif($match =~ /$misc::uselogin/i){
		&misc::Prt("CLI2:Matched $match, sending username\n");
		$next = "us";
	}elsif($match =~ /password:/i){
		&misc::Prt("CLI2:Matched $match, sending password\n");
		$next = "pw";
	}

	if($next eq "us"){
		$session->print($realus);
		&misc::Prt("CLI3:Username $realus sent\n");
		($pre, $match) = $session->waitfor("/password:|invalid|incorrect|denied|authentication failed|$misc::uselogin|$cmd{$os}{ropr}|$cmd{$os}{enpr}/i");
		if($match =~ /password:/i){
			&misc::Prt("CLI3:Matched $match, sending password\n");
			$next = "pw";
		}else{
			&misc::Prt("CLI3:Login, no match ($pre)\n");
		}
	}
	if($next eq "pw"){
		$session->print($misc::login{$us}{pw});
		&misc::Prt("CLI3:Password sent\n");
		($pre, $match) = $session->waitfor("/any key|Ctrl-Y|password:|invalid|incorrect|denied|authentication failed|$misc::uselogin|$cmd{$os}{ropr}|$cmd{$os}{enpr}/i");
		#print "PRE :$pre\nMTCH:$match\n" if $main::opt{'d'}; TODO find out why my enterasys disconnects here
	}
	$err = $session->errmsg;
	if($err){
		&misc::Prt("ERR :$err\n");
		$session->close;
		return (undef, "login error");
	}elsif($match =~ /password:|invalid|incorrect|denied|Authentication failed|$misc::uselogin/i){
		&misc::Prt("CLI3:Matched $match, login failed\n");
		$session->close;
		return (undef, "login failed");
	}elsif($match =~ /any key|Ctrl-Y/i){								# Some want this now (with SSH)...
		&misc::Prt("CLI3:Matched $match, sending ctrl-Y\n");
		$session->put("\cY");									# Since Nortel wants Ctrl-Y...
		($pre, $match) = $session->waitfor("/$cmd{$os}{enpr}/i");
		$err = $session->errmsg;
	}else{
		if ($match =~ /$cmd{$os}{ropr}/){							# Read-only prompt?
			&misc::Prt("CLI4:Matched $match, enabling\n");
			$session->print('enable');
			($pre, $match) = $session->waitfor("/$misc::uselogin|password:|$cmd{$os}{enpr}/i");
			$err = $session->errmsg;
			if($err){
				&misc::Prt("ERR :$err\n");
				$session->close;
				return (undef, "login error");
			}elsif($match =~ /$misc::uselogin/i){
				&misc::Prt("CLI5:Matched $match, sending username\n");
				$session->print($realus);
				($pre, $match) = $session->waitfor("/password:/i");
				$err = $session->errmsg;
				if($err){
					&misc::Prt("ERR :$err\n");
					$session->close;
					return (undef, "login error");
				}elsif($match =~ /password:/i){
					&misc::Prt("CLI6:Matched $match, sending password\n");
					$session->print($misc::login{$us}{en});
					($pre, $match) = $session->waitfor("/password:|invalid|incorrect|denied|authentication failed|$cmd{$os}{enpr}/i");
					$err = $session->errmsg;
				}else{
					&misc::Prt("CLI6:Enabling with user, no match in -->$pre<--\n");
				}
			}elsif($match =~ /password:/i){
				&misc::Prt("CLI7:Matched $match, sending password\n");
				$session->print($misc::login{$us}{en});
				($pre, $match) = $session->waitfor("/password:|invalid|incorrect|denied|authentication failed|$cmd{$os}{enpr}/i");
				$err = $session->errmsg;
			}else{
				&misc::Prt("CLI7:Enabling, no match PRE:$pre\n");
			}
		}else{
			$err = "no read-only prompt";
		}
	}
	if ($match =~ /$cmd{$os}{enpr}/i){								# Are we enabled?
		&misc::Prt("CLI8:Matched enable prompt, OK\n");
		return ($session, "OK");
	}else{
		&misc::Prt("ERR :$err\n");
		$session->close;
		return (undef, $err);
	}
}

=head2 FUNCTION PrepDev()

Find login, if device is compatible for mac-address-table or config retrieval

B<Options> device name, preparation mode (fwd table or config backup)

B<Globals> main::dev

B<Returns> status

=cut
sub PrepDev{

	my ($na, $mod) = @_;
	my ($session, $us);
	my $po    = 0;
	my $status= "init";
	my @users = @misc::users;

	&misc::Prt("\nPrepare (CLI)  ----------------------------------------------------------------\n");
	if($mod eq "fwd" and !$cmd{$main::dev{$na}{os}}{dfwd}){						# Bridge forwarding supported?
		return "not implemented";
	}elsif($mod eq "cfg" and !$cmd{$main::dev{$na}{os}}{conf}){					# Config backup supported?
		return "not implemented";
	}elsif($mod eq "arp" and !$cmd{$main::dev{$na}{os}}{arp}){					# Arp supported?
		return "not implemented";
	}
	if($main::dev{$na}{cp}){									# port=0 -> set to be prepd
		if(!$main::dev{$na}{us}){								# Do we have a  user?
			&misc::Prt("PREP:No working user\n");
			return "no working user";
		}elsif(exists $misc::login{$main::dev{$na}{us}}){					# OK if in nedi.conf
			&misc::Prt("PREP:$mod supported and user $main::dev{$na}{us} exists\n");
			return "OK-DB";									# OK from DB
		}else{
			&misc::Prt("PREP:No user $main::dev{$na}{us} in nedi.conf\n");			# User not in nedi.conf -> Prep
		}
	}

	$main::dev{$na}{us} = "";
	while ($status ne "OK"){									# Find a way to log in
		$us = shift (@users) unless $status =~ /^connection /;					# Try next user if connection worked
		if(!$us){
			$status= "no valid users";
			last;										# Not possible, no more tries!
		}
		unless($po){										# Port was set before
			if(exists $misc::map{$main::dev{$na}{ip}}{cp}){					# Port mapped
				$po = $misc::map{$main::dev{$na}{ip}}{cp};
			}elsif($misc::usessh eq "never"){
				$po = 23;
			}else{
				$po = 22;
			}
		}
		($session, $status) = Connect($main::dev{$na}{ip}, $po, $us, $main::dev{$na}{os});
		if($status eq "OK"){
			$main::dev{$na}{cp} = $po;
			$main::dev{$na}{us} = $us;
		}elsif($status =~ /^connection /){							# Connection problem
			if($po == 22 and $misc::usessh ne "always"){					# Telnet if ssh failed and ok with policy
				$po = 23;
			}else{
				$main::dev{$na}{cp} = 1;						# port=1, connect not possible
				last;									# Not possible, no more tries!
			}
		}else{
			$main::dev{$na}{cp} = $po;							# Connected, save port
			last if($#users == -1);
		}
		if(defined $session){									# OK, but we just found out
			$session->close;
			select(undef, undef, undef, $clipause);						# Wait to avoid hang in fwd or conf
		}
	}
	return $status;
}

=head2 FUNCTION BridgeFwd()

Get Ios mac address table

B<Options> device name

B<Globals> misc::portprop, misc::portnew

B<Returns> 0 on success, 1 on failure

=cut
sub BridgeFwd{

	my ($na) = @_;
	my ($line, @cam);
	my $nspo = 0;

	&misc::Prt("\nBridgeFwd (CLI)   -------------------------------------------------------------\n");
	($session, $status) = Connect($main::dev{$na}{ip}, $main::dev{$na}{cp}, $main::dev{$na}{us}, $main::dev{$na}{os});
	if($status ne "OK"){
		return $status;
	}else{
		$session->max_buffer_length(8 * 1024 * 1024);						# Increase buffer to 8Mb
		if ($cmd{$main::dev{$na}{os}}{page}){
			my @page = $session->cmd($cmd{$main::dev{$na}{os}}{page});
			&misc::Prt("CMD :$cmd{$main::dev{$na}{os}}{page}: @page\n");
		}
		@cam = $session->cmd($cmd{$main::dev{$na}{os}}{dfwd});
		&misc::Prt("CMD :$cmd{$main::dev{$na}{os}}{dfwd}\n");
		if ($misc::getfwd eq 'sec' and exists $cmd{$main::dev{$na}{os}}{sfwd}){
			&misc::Prt("CMD :$cmd{$main::dev{$na}{os}}{sfwd}\n");
			push @cam, $session->cmd($cmd{$main::dev{$na}{os}}{sfwd});
		}
		$session->close;
	}

	foreach my $l (@cam){
		my $mc = "";
		my $po = "";
		my $vl = "";
		my $ul = 0;
		my $rt = 0;
		if($main::dev{$na}{os} =~ /^IOS/){
			if ($l =~ /\s+(dynamic|forward|secure(dynamic|sticky))\s+/i){			# (secure) Thanks to Duane Walker 7/2007
				my @mactab = split (/\s+/,$l);
				foreach my $col (@mactab){
					if ($col =~ /^(Te|Gi|Fa|Do|Po|Vi)/){$po = &misc::Shif($col)}
					elsif ($col =~ /^[0-9|a-f]{4}\./){$mc = $col}
					elsif ($main::dev{$na}{os} ne "IOS-wl" and !$vl and $col =~ /^[0-9]{1,4}$/){$vl = $col} # Only use, if no vlan yet and it's not a Cisco AP
				}
				if($po =~ /[0-9]\.[0-9]/){						# Does it look like a subinterface?
					my @sub = split(/\./,$po);
					if(exists $misc::portprop{$na}{$sub[0]}){			# Parent IF exists, treat as sub
						$vl = $sub[1];
						if($misc::portprop{$na}{$sub[0]}{lnk}){			# inherit properties on subinterface
							$misc::portprop{$na}{$po}{lnk} = 1;
						}
						if($misc::portprop{$na}{$sub[0]}{rtr}){			# inherit properties on subinterface
							$misc::portprop{$na}{$po}{rtr} = 1;
						}
					}
				}
			}
		}elsif($main::dev{$na}{os} eq "CatOS"){
			if ($l =~ /^[0-9]{1,4}\s/){
				my @mactab = split (/\s+/,$l);
				foreach my $col (@mactab){
					if ($col =~ /^[0-9]{1,4}$/){$vl = $col}
					elsif ($col =~ /^([0-9|a-f]{2}-){5}[0-9|a-f]{2}$/){$mc = $col}
					elsif ($col =~ /[0-9]{1,2}\/[0-9]{1,2}/){$po = $col}
				}
			}
		}

		$mc =~ s/[^0-9a-f]//g;									# Strip to pure hex
		if($po and $mc and $mc !~ /$misc::ignoredmacs/){					# Make sure we've a valid MAC entry
			if(exists($misc::portprop{$na}{$po}) ){						# IF exists?
				if ($vl !~ /$misc::ignoredvlans/){
					$misc::portprop{$na}{$po}{pop}++;
					&misc::Prt("FWDC:$mc on $po vl$vl\n");
					$mc .= $vl if $vl =~ /$misc::useivl/;				# Add vlid to mac
					$misc::portnew{$mc}{$na}{po} = $po;
					$misc::portnew{$mc}{$na}{vl} = $vl;
					$nspo++;
				}

				if(exists $misc::ifmac{$mc}){
					&misc::Prt("LINK:Seeing ".join(", ",keys %{$misc::ifmac{$mc}})." on $po: is uplink\n");
					$misc::portprop{$na}{$po}{lnk} = 1;
					$main::int{$na}{$misc::portprop{$na}{$po}{idx}}{com} .= " MAC:".join(", ",keys %{$misc::ifmac{$mc}}) if $main::int{$na}{$misc::portprop{$na}{$po}{idx}}{com} !~ /^ (C|F|LL|N)DP:/;
				}
			}else{
				&misc::Prt("FWDC:$mc vl$vl, no IF $po\n");
			}
		}
	}
	&misc::Prt("FWDS:$nspo bridge forwarding entries found\n"," f$nspo");
	return "OK-Bridge";
}

=head2 FUNCTION BridgeFwd()

Get Ios-fw arp table

B<Options> device name

B<Globals> misc::portprop, misc::portnew

B<Returns> 0 on success, 1 on failure

=cut
sub Arp{

	my ($na) = @_;
	my ($line, @out);
	my $narp = 0;

	&misc::Prt("\nArp (CLI)   -------------------------------------------------------------------\n");
	($session, $status) = Connect($main::dev{$na}{ip}, $main::dev{$na}{cp}, $main::dev{$na}{us}, $main::dev{$na}{os});
	if($status ne "OK"){
		return $status;
	}else{
		$session->max_buffer_length(8 * 1024 * 1024);						# Increase buffer to 8Mb
		if ($cmd{$main::dev{$na}{os}}{page}){
			my @page = $session->cmd($cmd{$main::dev{$na}{os}}{page});
			&misc::Prt("CMD :$cmd{$main::dev{$na}{os}}{page}: @page\n");
		}
		@out = $session->cmd($cmd{$main::dev{$na}{os}}{arp});
		&misc::Prt("CMD :$cmd{$main::dev{$na}{os}}{arp}\n");
		$session->close;
	}

	foreach my $l (@out){
		my $mc = "";
		my $po = "";
		my $vl = "";
		my $ul = 0;
		my $rt = 0;
		if($main::dev{$na}{os} =~ /^IOS/){
			if ($l =~ /\s+([0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3})\s+/i){
				my @atab = split (/\s+/,$l);
				$po = $atab[1];
				$mc = $atab[3];
				$mc =~ s/[^0-9a-f]//g;							# Strip to pure hex
				if($mc !~ /$misc::ignoredmacs/){
					$misc::arp{$mc} = $atab[2];
					if( exists($misc::portprop{$na}{$po}) ){
						$misc::portprop{$na}{$po}{pop}++;
						&misc::Prt("ARPC:$mc $misc::arp{$mc} on $po\n");
						$misc::portnew{$mc}{$na}{po} = $po;
						$misc::portnew{$mc}{$na}{vl} = $vl;
						$nspo++;

						if(exists $misc::ifmac{$mc}){
							&misc::Prt("LINK:Seeing ".join(", ",keys %{$misc::ifmac{$mc}})." on $po: uplink\n");
							$misc::portprop{$na}{$po}{lnk} = 1;
							$main::int{$na}{$misc::portprop{$na}{$po}{idx}}{com} .= " MAC:".join(", ",keys %{$misc::ifmac{$mc}});
						}
					}else{
						&misc::Prt("ARPC:$mc $misc::arp{$mc}, no IF $po\n");
					}
				}
			}
		}
	}
	&misc::Prt("ARPC:$narp ARP entries found\n"," a$narp");
	return "OK-Arp";
}

=head2 FUNCTION Config()

Get Ios mac address table

B<Options> device name

B<Globals> misc::curcfg

B<Returns> 0 on success, error on failure

=cut
sub Config{

	my ($na) = @_;
	my ($session, $status, $go);
	my @run = ();

	&misc::Prt("\nConfig (CLI)   ----------------------------------------------------------------\n");
	($session, $status) = Connect($main::dev{$na}{ip}, $main::dev{$na}{cp}, $main::dev{$na}{us}, $main::dev{$na}{os});
	if($status ne "OK"){
		return $status;
	}else{
		$session->max_buffer_length(8 * 1024 * 1024);						# Increase buffer to 8Mb
		$session->timeout($misc::timeout + 20);							# Increase for building config
		if($cmd{$main::dev{$na}{os}}{page}){
			my @page = $session->cmd($cmd{$main::dev{$na}{os}}{page});
			&misc::Prt("CMD :$cmd{$main::dev{$na}{os}}{page}: @page\n");
		}
		&misc::Prt("CMD :$cmd{$main::dev{$na}{os}}{conf}\n");
		@run = $session->cmd($cmd{$main::dev{$na}{os}}{conf});
		#$session->print('logout'); TODO find way to terminate stale SSH sessions (this doesn't help)!
		$session->close;
	}

	foreach my $line (@run){
		$line =~ s/[\r\n]//g;
		if ($line =~ /$cmd{$main::dev{$na}{os}}{strt}/){$go = 1}
		if ($go){
			$line =~ s/\x1b\[(24;1H|2K|1;24r)//g;						# ProCurve special sauce
			&misc::Prt("CONF:$line\n");
			push @misc::curcfg,$line;
		}else{
			&misc::Prt("WAIT:$line\n");
		}
		if ($line =~ /^(: )?end$|<\/config>/){$go = 0}
	}
	if( scalar(@misc::curcfg) < 3 ){
		&misc::Prt("ERR :No config ($misc::curcfg[0])\n","Be");
		return "config is less than 3 lines";
	}else{
		while($misc::curcfg[$#misc::curcfg] eq ""){						# Remove empty trailing lines
			pop @misc::curcfg;
		}
		my $nl = scalar(@misc::curcfg);
		&misc::Prt("CONF:"," c$nl");
		return "OK-${nl}lines";
	}
}


=head2 FUNCTION SendCmd()

Send commands to device (used by the GUI helper Devsend.pl)

B<Options> IP, port, user, pass, OS, command file

B<Globals> -

B<Returns> -

=cut
sub SendCmd{

	my ($ip, $po, $us, $pw, $os, $cf) = @_;
	my $err = '';

	if($misc::guiauth =~ /-pass$/){
		$misc::login{$us}{pw} = $pw;
	}

	open  (CFG, "$cf" );
	my @cmd = <CFG>;
	close(CFG);
	chomp @cmd;
	$misc::timeout *= 10;

	&misc::Prt("$os $us CMD=$cf(". scalar @cmd .") T:${misc::timeout}s <p>\n");

	open  (LOG, ">$cf-$ip.log" ) or print " can't write to $cf-$ip.log";

	($session, $status) = Connect($ip, $po, $us, $os);
	if($status ne "OK"){
		if(defined $session){
			$err = $session->errmsg;
		}else{
			$err = $status;
		}
		close (LOG);
		&misc::Prt("ERR :$err\n");
	}else{
		$session->cmd($cmd{$os}{'page'}) if $cmd{$os}{'page'};
		foreach my $c (@cmd){
			print LOG "$c\n";
			&misc::Prt("CMD :$c\n");
			my @out = $session->cmd($c); 
			if($session->errmsg){					# TODO This...
				$err = $session->errmsg;
				&misc::Prt("ERR :$err\n");
			}
			foreach my $line (@out){
				$line =~ s/\x1b\[(24;1H|2K|1;24r)//g;
				print LOG $line;
				&misc::Prt("RES :$line");
				$err = $line if $line =~ /^(% )?(Invalid|Unknown)/;	# Catch errors, but ignore "% Warnings" (doesn't seem to work on ProCurve switches using SSH!)
			}
			if($err){
				print "X";
				last;
			}else{
				print ".";
			}
		}
		$session->close;
		close (LOG);
	}
	return $err;
}


=head2 FUNCTION Spawn()

Spawns a pty.

B<Options> command

B<Globals> -

B<Returns> pty

=cut
sub Spawn {

	my $pty = new IO::Pty or die $!;

	&misc::Prt("PTY :Forking $_[0]\n");
	unless (my $pid = fork) {
		die $! unless defined $pid;

		use POSIX ();
		POSIX::setsid or die $!;

		my $tty = $pty->slave;
		$pty->make_slave_controlling_terminal();
		my $tty_fd = $tty->fileno;
		close $pty;

		open STDIN, "<&$tty_fd" or die $!;
		open STDOUT, ">&$tty_fd" or die $!;
		open STDERR, ">&STDOUT" or die $!;
		close $tty;

		exec $_[0] or die $!;
	}
	$pty;
}

1;
