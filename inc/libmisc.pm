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

use vars qw($netfilter $webdev $nosnmpdev $border $ouidev $descfilter $getfwd $timeout);
use vars qw($nedipath $backend $dbname $dbuser $dbpass $dbhost $clilib $uselogin $usessh $usepoe);
use vars qw($rrdcmd $rrdstep $rrdsize $nmapcmd $nagpipe $snmpwrite $redbuild $guiauth $locsep);
use vars qw($arpwatch $ignoredvlans $ignoredmacs $useivl $retire $arppoison $macflood $seedlist);
use vars qw($notify $chka $latw $cpua $mema $tmpa $trfa $trfw $poew $supa $pause $smtpserver $mailfrom $mailfoot);
use vars qw(%comms %login %map %useif %skipif %doip %seedini %ouineb %sysobj %ifmac %ifip %useip);
use vars qw(%oui %arp  %arp6 %arpc %arpn %portprop %portnew %portdes %vlid %prif);
use vars qw(@todo @comms @seeds @users @curcfg);

our @donenam = @doneid = @doneip = @failid = @failip = ();
our $ipchg = $ifchg = $mq = 0;

=head2 FUNCTION ReadConf()

Searches for nedi.conf in nedi folder first then fall back to /etc. Parse
it if found or die if not.

locsep is set to a space if commented.

B<Options> -

B<Globals> various misc:: varables

B<Returns> dies on missing nedi.conf

=cut
sub ReadConf {

	my $nconf = "$main::p/nedi.conf";

	$ignoredvlans = $ignoredmacs = $useivl = $border = $nosnmpdev = $descfilter = $usessh = $usepoe = "isch nid gsetzt!";
	$locsep  = " ";
	$supa    = 0;
	$rrdsize = 1000;

	if($main::opt{U}){
		$nconf = $main::opt{U}
	}
	if(-e "$nconf"){
		open  ("CONF", $nconf);
	}elsif(-e "/etc/nedi.conf"){
		open  ("CONF", "/etc/nedi.conf");
	}else{
		die "Can't find $nconf: $!\n";
	}
	my @conf = <CONF>;
	close("CONF");

	foreach my $l (@conf){
		if($l !~ /^[#;]|^(\s)*$/){
			$l =~ s/[\r\n]//g;
			my @v  = split(/\s+/,$l);
			if($v[0] eq "comm"){
				push (@comms,$v[1]);
				$comms{$v[1]}{aprot} = $v[2];
				$comms{$v[1]}{apass} = $v[3];
				$comms{$v[1]}{pprot} = $v[4];
				$comms{$v[1]}{ppass} = $v[5];}
			elsif($v[0] eq "usr"){
				push (@users,$v[1]);
				$login{$v[1]}{pw} = $v[2];
				$login{$v[1]}{en} = $v[3];}
			elsif($v[0] eq "useip"){
				$useip{$v[1]} = $v[2];}
			elsif($v[0] eq "uselogin"){$uselogin = $v[1]}
			elsif($v[0] eq "snmpwrite"){$snmpwrite = $v[1]}
			elsif($v[0] eq "usessh"){$usessh = $v[1]}
			elsif($v[0] eq "skipif"){$skipif{$v[1]} = $v[2]}
			elsif($v[0] eq "usepoe"){$usepoe = $v[1]}

			elsif($v[0] eq "mapip"){$map{$v[1]}{ip} = $v[2]}
			elsif($v[0] eq "maptp"){$map{$v[1]}{cp} = $v[2]}
			elsif($v[0] eq "mapna"){$map{$v[1]}{na} = join ' ', splice @v,2}
			elsif($v[0] eq "maplo"){$map{$v[1]}{lo} = join ' ', splice @v,2}
			elsif($v[0] eq "nosnmpdev"){$nosnmpdev = $v[1]}
			elsif($v[0] eq "webdev"){$webdev = $v[1]}
			elsif($v[0] eq "netfilter"){$netfilter = $v[1]}
			elsif($v[0] eq "border"){$border = join ' ', splice @v,1}
			elsif($v[0] eq "ouidev"){$ouidev = join ' ', splice @v,1}
			elsif($v[0] eq "descfilter"){$descfilter = $v[1]}

			elsif($v[0] eq "backend"){$backend = $v[1]}
			elsif($v[0] eq "dbname"){$dbname = $v[1]}
			elsif($v[0] eq "dbuser"){$dbuser = $v[1]}
			elsif($v[0] eq "dbpass"){$dbpass = (defined $v[1])?$v[1]:""}			# based on dirtyal's suggestion
			elsif($v[0] eq "dbhost"){$dbhost = $v[1]}

			elsif($v[0] eq "clilib"){$clilib = $v[1]}

			elsif($v[0] eq "ignoredvlans"){$ignoredvlans = $v[1]}
			elsif($v[0] eq "ignoredmacs"){$ignoredmacs = $v[1]}
			elsif($v[0] eq "useivl"){$useivl = $v[1]}
			elsif($v[0] eq "getfwd"){$getfwd = $v[1]}
			elsif($v[0] eq "retire"){$retire = $main::now - $v[1] * 86400;}
			elsif($v[0] eq "timeout"){$timeout = $v[1]}
			elsif($v[0] eq "arpwatch"){$arpwatch = $v[1]}
			elsif($v[0] eq "arppoison"){$arppoison = $v[1]}
			elsif($v[0] eq "macflood"){$macflood = $v[1]}

			elsif($v[0] eq "rrdstep"){$rrdstep = $v[1]}
			elsif($v[0] eq "rrdsize"){$rrdsize = $v[1]}
			elsif($v[0] eq "rrdcmd"){$rrdcmd = $v[1]}
			elsif($v[0] eq "nagpipe"){$nagpipe = $v[1]}

			elsif($v[0] eq "notify"){$notify = $v[1]}
			elsif($v[0] eq "check-alert"){$chka = $v[1]}
			elsif($v[0] eq "latency-warn"){$latw = $v[1]}
			elsif($v[0] eq "cpu-alert"){$cpua = $v[1]}
			elsif($v[0] eq "mem-alert"){$mema = $v[1]}
			elsif($v[0] eq "temp-alert"){$tmpa = $v[1]}
			elsif($v[0] eq "traf-alert"){$trfa = $v[1]}
			elsif($v[0] eq "traf-warn"){$trfw = $v[1]}
			elsif($v[0] eq "poe-warn"){$poew = $v[1]}
			elsif($v[0] eq "supply-alert"){$supa = $v[1]}

			elsif($v[0] eq "pause"){$pause = $v[1]}
			elsif($v[0] eq "smtpserver"){$smtpserver = $v[1]}
			elsif($v[0] eq "mailfrom"){$mailfrom = $v[1]}
			elsif($v[0] eq "mailfooter"){$mailfoot = join ' ', splice @v,1}
			elsif($v[0] eq "guiauth"){$guiauth = $v[1]}
			elsif($v[0] eq "locsep"){$locsep = $v[1]}
			elsif($v[0] eq "redbuild"){$redbuild = $v[1]}

			elsif($v[0] eq "nedipath"){
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
		if(-e "$main::p/sysobj/$so.def"){
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
		$sysobj{$so}{pm} = '-';
		$sysobj{$so}{cul}= '-;;';

		foreach my $l (@def){
			if($l !~ /^[#;]|^\s*$/){
				$l =~ s/[\r\n]//g;			# Chomp doesn't remove \r
				my @v  = split(/\t+/,$l);
				if(!defined $v[1]){$v[1] = ""}
				if($v[0] eq "Type")		{$sysobj{$so}{ty} = $v[1]}
				elsif($v[0] eq "OS")		{$sysobj{$so}{os} = $v[1]}
				elsif($v[0] eq "Icon")		{$sysobj{$so}{ic} = $v[1]}
				elsif($v[0] eq "Size")		{$sysobj{$so}{sz} = $v[1]}
				elsif($v[0] eq "SNMPv"){
					$sysobj{$so}{rv} = substr($v[1],0,1);
					if(substr($v[1],1,2) eq 'HC'){
						$sysobj{$so}{hc} = 128;					# Pure Highspeed 64bit counters
					}elsif(substr($v[1],1,2) eq 'MC'){
						$sysobj{$so}{hc} = 192;					# Merge Counters
					}else{
						$sysobj{$so}{hc} = 64;					# 32bit counters only
					}
				}
				elsif($v[0] eq "Serial")	{$sysobj{$so}{sn} = $v[1]}
				elsif($v[0] eq "Bimage")	{$sysobj{$so}{bi} = $v[1]}
				elsif($v[0] eq "Sysdes")	{$sysobj{$so}{de} = $v[1]}
				elsif($v[0] eq "Bridge")	{$sysobj{$so}{bf} = $v[1]}
				elsif($v[0] eq "ArpND")	{$sysobj{$so}{ar} = $v[1]}
				elsif($v[0] eq "Dispro")	{$sysobj{$so}{dp} = $v[1]}
				elsif($v[0] eq "Typoid")	{$sysobj{$so}{to} = $v[1]}		# tx vtur

				elsif($v[0] eq "VLnams")	{$sysobj{$so}{vn} = $v[1]}
				elsif($v[0] eq "VLnamx")	{$sysobj{$so}{vl} = $v[1]}
				elsif($v[0] eq "Group")	{$sysobj{$so}{dg} = $v[1]}
				elsif($v[0] eq "Mode")		{$sysobj{$so}{dm} = $v[1]}

				elsif($v[0] eq "StartX")	{$sysobj{$so}{st} = $v[1]}
				elsif($v[0] eq "EndX")		{$sysobj{$so}{en} = $v[1]}
				elsif($v[0] eq "IFname")	{$sysobj{$so}{in} = $v[1]}
				elsif($v[0] eq "IFaddr")	{$sysobj{$so}{ia} = $v[1]}
				elsif($v[0] eq "IFalia")	{$sysobj{$so}{al} = $v[1]}
				elsif($v[0] eq "IFalix")	{$sysobj{$so}{ax} = $v[1]}
				elsif($v[0] eq "IFdupl")	{$sysobj{$so}{du} = $v[1]}
				elsif($v[0] eq "IFduix")	{$sysobj{$so}{dx} = $v[1]}
				elsif($v[0] eq "Halfdp")	{$sysobj{$so}{hd} = $v[1]}
				elsif($v[0] eq "Fulldp")	{$sysobj{$so}{fd} = $v[1]}
				elsif($v[0] eq "InBcast")	{$sysobj{$so}{ib} = $v[1]}
				elsif($v[0] eq "InDisc")	{$sysobj{$so}{id} = $v[1]}
				elsif($v[0] eq "OutDisc")	{$sysobj{$so}{od} = $v[1]}
				elsif($v[0] eq "IFvlan")	{$sysobj{$so}{vi} = $v[1]}
				elsif($v[0] eq "IFvlix")	{$sysobj{$so}{vx} = $v[1]}
				elsif($v[0] eq "IFpowr")	{
					$sysobj{$so}{pw} = $v[1];
					$sysobj{$so}{pm} = $v[2] if $v[2];
				}
				elsif($v[0] eq "IFpwix")	{$sysobj{$so}{px} = $v[1]}

				elsif($v[0] eq "Modesc")	{$sysobj{$so}{md} = $v[1]}
				elsif($v[0] eq "Moclas")	{$sysobj{$so}{mc} = $v[1]}
				elsif($v[0] eq "Movalu")	{$sysobj{$so}{mv} = $v[1]}
				elsif($v[0] eq "Mostep")	{$sysobj{$so}{mp} = $v[1]}
				elsif($v[0] eq "Moslot")	{$sysobj{$so}{mt} = $v[1]}
				elsif($v[0] eq "Modhw")	{$sysobj{$so}{mh} = $v[1]}
				elsif($v[0] eq "Modsw")	{$sysobj{$so}{ms} = $v[1]}
				elsif($v[0] eq "Modfw")	{$sysobj{$so}{mf} = $v[1]}
				elsif($v[0] eq "Modser")	{$sysobj{$so}{mn} = $v[1]}
				elsif($v[0] eq "Momodl")	{$sysobj{$so}{mm} = $v[1]}


				elsif($v[0] eq "CPUutl")	{$sysobj{$so}{cpu} = $v[1]}
				elsif($v[0] eq "MemCPU")	{
					$sysobj{$so}{mem} = $v[1];
					$sysobj{$so}{mmu} = ($v[2])?$v[2]:1;
				}
				elsif($v[0] eq "Temp")		{
					$sysobj{$so}{tmp} = $v[1];
					$sysobj{$so}{tmu} = ($v[2])?$v[2]:1;
				}
				elsif($v[0] eq "MemIO")	{$sysobj{$so}{cuv} = $v[1];$sysobj{$so}{cul} = "MemIO;G;Bytes"}	# Support legacy .defs
				elsif($v[0] eq "Custom" and $v[2]){$sysobj{$so}{cuv} = $v[2];$sysobj{$so}{cul} = $v[1]}
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
		$l =~ s/^\s+|[\r\n]$//g;
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
		$l =~ s/^\s+|[\r\n]$//g;
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

	if($_[0] =~ /^0050C2/i) {
		$coui = $oui{substr($_[0],0,9)};
	} else {
		$coui = $oui{substr($_[0],0,6)};
	}
	if(!$coui){$coui =  "?"}
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

	my ($n) = @_;

	if($n){
		$n =~ s/tengigabitethernet/Te/i;
		$n =~ s/gigabit[\s]{0,1}ethernet/Gi/i;
		$n =~ s/fast[\s]{0,1}ethernet/Fa/i;
		$n =~ s/^eth(ernet)?/Et/i;								# NXOS uses Eth in CLI, but Ethernet in SNMP...tx sk95, Matthias
		$n =~ s/^Serial/Se/;
		$n =~ s/^Dot11Radio/Do/;
		$n =~ s/^Wireless port\s?/Wp/;								# Former Colubris controllers
		$n =~ s/^[F|G]EC-//;									# Doesn't match telnet CAM table!
		$n =~ s/^Alcatel-Lucent //;								# ALU specific
		$n =~ s/^BayStack (.*?)- //;								# Nortel specific
		$n =~ s/^Vlan/Vl/;									# MSFC2 and Cat6k5 discrepancy!
                $n =~ s/port-channel/Po/i;								# N5K requires this, Tx Matthias
		$n =~ s/(Port\d): .*/$1/g;								# Ruby specific
		$n =~ s/PIX Firewall|pci|motorola|power|switch|network|interface//ig;			# Strip other garbage (removed management for asa)
		$n =~ s/\s+|'//g;									# Strip unwanted characters
		return $n;
	}else{
		return "-";
	}
}



=head2 FUNCTION CheckIf()

Check interface against monitoring policy 

B<Options> Device, IF name, Skipstring

B<Globals> -

B<Returns> -

=cut
sub CheckIf {

	my ($dv,$i,$skip) = @_;
	
	return unless $main::int{$dv}{$i}{old} and exists $main::mon{$dv};

	my $iftxt = "$main::int{$dv}{$i}{ina} ";
	$iftxt .= " - $main::int{$dv}{$i}{ali} " if $main::int{$dv}{$i}{ali};
	my $ele = 0;
	my $lvl = 100;
	my $cla = "nedi";
	if($main::int{$dv}{$i}{lty}){
		$iftxt .= ($main::int{$dv}{$i}{com})?"(is $main::int{$dv}{$i}{com})":"(is $main::int{$dv}{$i}{lty})";
		$lvl = 150;
		$cla = "nedl";
		$ele = &mon::Elevate('L',0);
	}elsif($main::int{$dv}{$i}{plt}){
		$iftxt .= ($main::int{$dv}{$i}{pco})?"(was $main::int{$dv}{$i}{pco})":"(was $main::int{$dv}{$i}{plt})";
		$lvl = 150;
		$cla = "nedl";
		$ele = &mon::Elevate('L',0);
	}
	if($main::dev{$dv}{pl} > $main::lasdis){							# Avoid > 100% events due to offline dev being rediscovered
		my $trfele = &mon::Elevate('T',$ele);
		my $errele = &mon::Elevate('E',$ele);
		if($trfele and $main::int{$dv}{$i}{spd} and $skip !~ /t/){
			my $rioct = int( $main::int{$dv}{$i}{dio} * 800 / ($rrdstep * $main::int{$dv}{$i}{spd}) );
			my $rooct = int( $main::int{$dv}{$i}{doo} * 800 / ($rrdstep * $main::int{$dv}{$i}{spd}) );

			if($rioct > $trfa){
				$mq += &mon::Event($trfele,200,$cla,$dv,$dv,"$iftxt having $rioct% inbound traffic exeeds alert threshold of ${trfa}% for ${rrdstep}s!");
			}elsif($rioct > $trfw){								# No alerts on warnings, thus only elevate to 1
				$mq += &mon::Event( ($trfele > 1)?1:0,$lvl,$cla,$dv,$dv,"$iftxt having $rioct% inbound traffic exeeds warning threshold of ${trfw}% for ${rrdstep}s");
			}
			if($rooct > $trfa){
				$mq += &mon::Event($trfele,200,$cla,$dv,$dv,"$iftxt having $rooct% outbound traffic exeeds alert threshold of ${trfa}% for ${rrdstep}s!");
			}elsif($rooct > $trfw){
				$mq += &mon::Event( ($trfele > 1)?1:0,$lvl,$cla,$dv,$dv,"$iftxt having $rooct% outbound traffic exeeds warning threshold of ${trfw}% for ${rrdstep}s");
			}
		}

		if($errele and $main::int{$dv}{$i}{typ} != 71 and $skip !~ /e/){			# Ignore Wlan IF
			if($main::int{$dv}{$i}{die} > $rrdstep){
				$mq += &mon::Event($errele,$lvl,$cla,$dv,$dv,"$iftxt having $main::int{$dv}{$i}{die} inbound errors for ${rrdstep}s!");
			}elsif($main::int{$dv}{$i}{die} > $rrdstep / 60){
				$mq += &mon::Event( ($errele > 1)?1:0,$lvl,$cla,$dv,$dv,"$iftxt having $main::int{$dv}{$i}{die} inbound errors for ${rrdstep}s");
			}
			if($main::int{$dv}{$i}{doe} > $rrdstep){
				$mq += &mon::Event($errele,$lvl,$cla,$dv,$dv,"$iftxt having $main::int{$dv}{$i}{doe} outbound errors for ${rrdstep}s!");
			}elsif($main::int{$dv}{$i}{doe} > $rrdstep / 60){
				$mq += &mon::Event( ($errele > 1)?1:0,$lvl,$cla,$dv,$dv,"$iftxt having $main::int{$dv}{$i}{doe} outbound errors for ${rrdstep}s");
			}
		}
	}

	if($main::int{$dv}{$i}{sta} == 0 and $main::int{$dv}{$i}{pst} != 0 and $skip !~ /A/){
		$mq += &mon::Event( &mon::Elevate('A',$ele),$lvl,$cla,$dv,$dv,"$iftxt went down, previous status change on ".localtime($main::int{$dv}{$i}{pcg}) );
	}elsif($main::int{$dv}{$i}{sta} == 1 and $main::int{$dv}{$i}{pst} > 1 and $skip !~ /O/){
		$mq += &mon::Event( &mon::Elevate('O',$ele),$lvl,$cla,$dv,$dv,"$iftxt went down, previous status change on ".localtime($main::int{$dv}{$i}{pcg}) );
	}

	if($main::int{$dv}{$i}{lty} or $main::int{$dv}{$i}{plt}){
		my $typc = ($main::int{$dv}{$i}{lty} ne $main::int{$dv}{$i}{plt})?" type ".(($main::int{$dv}{$i}{plt})?" from $main::int{$dv}{$i}{plt}":"").(($main::int{$dv}{$i}{lty})?" to $main::int{$dv}{$i}{lty}":""):"";
		my $spdc = ($main::int{$dv}{$i}{spd} ne $main::int{$dv}{$i}{spd})?" speed from ".&DecFix($main::int{$dv}{$i}{psp})." to ".&DecFix($main::int{$dv}{$i}{spd}):"";
		my $dupc = ($main::int{$dv}{$i}{dpx} ne $main::int{$dv}{$i}{pdp})?" duplex from $main::int{$dv}{$i}{pdp} to $main::int{$dv}{$i}{dpx}":"";
		my $ndio = (!$main::int{$dv}{$i}{dio} and $main::int{$dv}{$i}{sta} & 3)?" did not receive any traffic":"";
		my $ndoo = (!$main::int{$dv}{$i}{doo} and $main::int{$dv}{$i}{sta} & 3)?" did not send any traffic":"";
		my $didi = ($main::int{$dv}{$i}{did} > $rrdstep)?" has $main::int{$dv}{$i}{idi} in-discards":"";
		my $dodi = ($main::int{$dv}{$i}{dod} > $rrdstep)?" has $main::int{$dv}{$i}{odi} out-discards":"";
		if($typc or $spdc or $dupc or $ndio or $ndoo or $didi or $dodi){
			my $msg  = "$iftxt ".(($typc or $spdc or $dupc)?"changed":"")."$typc$spdc$dupc$ndio$ndoo$didi$dodi";
			$mq += &mon::Event($ele,$lvl,$cla,$dv,$dv,$msg);
		}
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
	if($map{$_[0]}{ip}){
		$ip = $map{$_[0]}{ip};
		&Prt("MAPI:IP $_[0] to $ip\n");
	}
	return $ip;
}

=head2 FUNCTION MSM2I()

Converts HP MSM (former Colubris) IF type to IEEE types

B<Options>IF type

B<Globals> -

B<Returns> IEEE type

=cut
sub MSM2I {

	my ($t) = @_;

	if($t == 2){
		return 6;
	}elsif($t == 3){
		return 53;
	}elsif($t == 4){
		return 209;
	}elsif($t == 5){
		return 71;
	}else{
		return $t;
	}
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

=head2 FUNCTION Mask2Bit()

Converts IP mask to # of bits.

B<Options> IP address

B<Globals> -

B<Returns> bitcount

=cut
sub Mask2Bit {
	$_[0] = 0 if !$_[0];
	my $bit = sprintf("%b", unpack N => pack CCCC => split /\./ => shift);
	$bit =~ s/0//g;
	return length($bit);
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

=head2 FUNCTION NagPipe()

Pipe NeDi events into Nagios

B<Options> string of values

B<Globals> -

B<Returns> -

=cut
sub NagPipe {

	my $nag_event_service = 'Events';

	if(-p $nagpipe) {										# Nagios Handler by S.Neuser
		my ($level_str,$time,$source,$msg) = split /,/, $_[0];
		$level_str =~ s/\"//g;
		my $level = int $level_str;
		my $status = 3;
		if(! defined $level) { $status = 3; }
		elsif($level < 0) { $status = 3; }							# UNKNOWN
		elsif($level < 100) { $status = 0; }							# OK
		elsif($level < 200) { $status = 1; }							# WARN
		else { $status = 2; }									# CRIT
		my $lsource = lc ($source);
		$lsource =~ s/\"//g;
		$time =~ s/\"//g;
		$msg =~ s/\"//g;
		$msg =~ s/\n/;/g;
		open (NPIPE, ">>$nagpipe");
		print NPIPE "[$time] PROCESS_SERVICE_CHECK_RESULT;$lsource;$nag_event_service;$status;NeDi:$msg\n";
		close NPIPE;
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
			if( $l !~ /\#time:|ntp clock-period/){
				$chg .= sprintf "%4d$sign %s\n", $lineno+1, $l;
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
	return "" unless $l[0];

	my @gw = split(/\s+/,$l[0]);

	if($gw[1] eq "0.0.0.0"){
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

	$seedlist = ($_[0])?"$nedipath/agentlist":"$nedipath/seedlist";

	@todo = ();
	%doip = ();
	
	if($main::opt{'u'}){
		$seedlist = "$main::opt{u}";
	}
	my $id = substr($seedlist, rindex($seedlist, '/')+1,5 );

	if(!$_[0] and $main::opt{'t'}){
		push (@todo,"testing");
		$doip{"testing"} = join('.',unpack( 'C4',gethostbyname($main::opt{'t'}) ) );
		&Prt("SEED:$main::opt{t} added for testing\n");
		$s = 1;
	}elsif($main::opt{'a'}){
		$seedlist = "-a $main::opt{'a'}";
		if($main::opt{'a'} =~ /^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/){
			for($i=1;$i<255;$i++){
				my $latency = &mon::PingService("$main::opt{a}.$i",'',0,0.5);
				if($latency ne -1){
					push(@todo,"ping$i");
					$doip{"ping$i"} = "$main::opt{a}.$i";
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
	}elsif($main::opt{'A'}){
		$seedlist = "-A $main::opt{'A'}";
		foreach my $dv (keys %main::dev){
			if($main::dev{$dv}{rv}){
				push(@todo,$dv);
				$doip{$dv}		 = $main::dev{$dv}{ip};
				print "$dv, $main::dev{$dv}{ip} added for discovery\n" if $main::opt{'v'};
				$s++;
			}
		}
	}elsif(-e "$seedlist"){
		&Prt("SEED:Using $seedlist\n");
		open  (LIST, "$seedlist");
		my @list = <LIST>;
		close(LIST);
		foreach my $l (@list){
			if($l !~ /^[#;]|^$/){
				$l =~ s/[\r\n]//g;
				my @f   = split(/\s+/,$l);
				my $hip = gethostbyname($f[0]);
				if(defined $hip){
					my $ip = join('.',unpack('C4',$hip) );
					$seedini{$ip}{rc} = ($f[1])?$f[1]:"";
					$seedini{$ip}{rv} = ($f[2])?$f[2]:"";
					$seedini{$ip}{lo} = ($f[3])?$f[3]:"";
					$seedini{$ip}{co} = ($f[4])?$f[4]:"";
					push(@todo,"$id$s");
					$doip{"$id$s"} = $ip;
					&Prt("SEED:$ip $id$s added for discovery\n");
					$s++;
				}else{
					&Prt("SEED:Error resolving $f[0]!\n");
				}
			}
		}
	}else{
		&Prt("SEED:$seedlist not found!\n");
	}
	if(!$s and !$main::opt{'A'}) {									# Fall back to GW if no seeds found.
		$seedlist = "default GW";
		&Prt("SEED:No seeds, trying default gw!\n");
		$todo[0] 	= 'defgw';
		$doip{'defgw'}	= &GetGw();
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

	&Prt("\nDiscover     ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++\n",sprintf("%-15.15s ",$doip{$id}) );
	if($main::opt{'A'} and $skip =~ /s/){
		my $latency = &mon::PingService($main::dev{$id}{ip},'',0,0.5);
		if($latency ne -1){
			&Prt("","UP:${latency}ms ");
			&ReadSysobj($main::dev{$id}{so});
			$dv = $id;
		}
	}else{
		$dv  = &snmp::Identify($id,$skip);
	}
	if($dv){
		my $skip = $main::opt{'S'};
		if(exists $skipif{$main::dev{$dv}{ty}}){
			$skip .= $skipif{$main::dev{$dv}{ty}};
			&Prt("DISC:skipif policy for $main::dev{$dv}{ty}=$skipif{$main::dev{$dv}{ty}}\n");
		}elsif(exists $skipif{'default'}){
			$skip .= $skipif{'default'};
			&Prt("DISC:default skipif policy=$skipif{'default'}\n");
		}else{
			&Prt("DISC:no skipif policy using -S ($main::opt{S})\n");
		}
		my $noentrinf = &snmp::Enterprise($dv,$skip);						# Get enterprise info
		my $noifwrite = &snmp::Interfaces($dv,$skip);						# Get interface info
		&snmp::IfAddresses($dv) if $sysobj{$main::dev{$dv}{so}}{ia} and $skip !~ /j/;		# Get IP addresses
		if($main::dev{$dv}{pi} and $main::dev{$dv}{pi} ne $main::dev{$dv}{ip}){			# Previous IP was different...
			$mq += &mon::Event('I',150,'nedi',$dv,$dv,"IP changed from $main::dev{$dv}{pi} to $main::dev{$dv}{ip} (update monitoring)");
		}
		if($sysobj{$main::dev{$dv}{so}}{dp} and $skip !~ /p/){
			&snmp::DisProtocol($dv,$id,$sysobj{$main::dev{$dv}{so}}{dp});			# Get neighbours via LLDP, CDP or FDP
		}
		if($sysobj{$main::dev{$dv}{so}}{mt}){
			my $pstk = (defined $main::dev{$dv}{stk})?$main::dev{$dv}{stk}:0;
			if($skip =~ /m/){
				&Prt("","  ");
			}else{
				&snmp::Modules($dv);
			}
		}else{
			$main::dev{$dv}{stk} = 0;
			&Prt("","  ");
		}
		&KeyScan($main::dev{$dv}{ip}) if $main::opt{'k'} or $main::opt{'K'};

		if($sysobj{$main::dev{$dv}{so}}{ar} and $skip !~ /a/){					# Map IP to MAC addresses, if ARP/ND is in .def
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
		if($getfwd and $skip !~ /f/){
			if($sysobj{$main::dev{$dv}{so}}{bf} eq "Aruba"){# Redesign getfwd and skip-f!!!
				&snmp::ArubaFwd($dv);
			}elsif($sysobj{$main::dev{$dv}{so}}{bf} eq "MSM"){
				&snmp::MSMFwd($dv);
			}elsif($sysobj{$main::dev{$dv}{so}}{bf} eq "WLC"){
				&snmp::WLCFwd($dv);
			}elsif($sysobj{$main::dev{$dv}{so}}{bf} eq "CAP"){
				&snmp::CAPFwd($dv);
			}elsif($sysobj{$main::dev{$dv}{so}}{bf} eq "WRT"){
				&snmp::WRTFwd($dv);
			}elsif($sysobj{$main::dev{$dv}{so}}{bf}){					# Get mac address table, if  bridging is set in .def
				if($getfwd =~ /dyn|sec/){						# Using CLI to fetch forwarding table is configured?
					$clistat = &cli::PrepDev($dv,"fwd");				# Prepare device for cli access
					if($clistat =~ /^OK/){
						$clistat = &cli::BridgeFwd($dv);
					}
					&Prt("DISC:Cli bridge fwd = $clistat\n");
				}
				if($clistat ne "OK-Bridge"){
					if($sysobj{$main::dev{$dv}{so}}{bf} =~ /^V(LX|XP)$/ and  $skip =~ /s/){
						&Prt("ERR :Cannot get Vlan indexed forwarding entries with skipping s!\n");
					}else{
						&snmp::BridgeFwd($dv);					# Do SNMP if telnet fails or CLI not configured
					}
				}
				&FloodFind($dv) if $notify =~ /n/i;
			}
		}

		if($main::opt{'b'} or defined $main::opt{'B'}){						# Backup configurations
			if($clistat eq "OK-Bridge" or $clistat eq "OK-Arp"){				# Wait if we just got BridgeFWD or ARP via CLI to avoid hang
				select(undef, undef, undef, $cli::clipause);
			}else{
				$clistat = &cli::PrepDev($dv,"cfg");
			}
			&Prt("DISC:Cli config = $clistat\n");
			if($clistat =~ /^OK/){
				@curcfg = ();								# Empty config (global due to efficiency)
				$clistat = &cli::Config($dv);
				&db::BackupCfg($dv) if $clistat =~ /^OK/;
			}
			if($clistat !~ /^(OK|not implemented)/){					# If not ok, but supported...
					$mq += &mon::Event('B',150,'cfge',$dv,$dv,"Config backup error: $clistat");
			}
		}

		push (@doneid,$id);
		push (@doneip,$doip{$id});
		push (@donenam, $dv);
		&DevRRD($dv,$skip) if($rrdcmd and $skip !~ /g/);					# RRD if enabled (after MSM BridgeFwd)
		unless($main::opt{'t'}){
			&Prt("\nWriting Dev  ------------------------------------------------------------------\n");
			&db::UnStock($dv);
			&db::WriteDev($dv);
			&db::WriteInt($dv,$skip) unless $noifwrite;
			&db::WriteMod($dv)  if $skip !~ /m/;
			&db::WriteVlan($dv) if $skip !~ /s/;
			&db::WriteNet($dv)  if $skip !~ /j/;
			&db::WriteLink($dv) if $skip !~ /p/;						# TODO review after writeint rewrite!
		}
#		%main::int  = ();									# Not needed anymore, preserve space! TODO uncomment, if really true!
	}else{
		push (@failid,$id);
		push (@failip,$doip{$id});
	}
	my $s = sprintf ("%4d/%d-%ds",scalar(@todo),scalar(@donenam),(time - $start) );
	&Prt("DISC:ToDo/Done-Time\t\t\t\t\t\t\t$s\n"," $s\n");
}


=head2 FUNCTION BuildArp()

Build arp table from Arpwatch files (if set in nedi.conf).

B<Options> -

B<Globals> misc::arp, misc::arpn, misc::arpc

B<Returns> -

=cut
sub BuildArp {

	return unless defined $misc::arpwatch;
 
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
				$arp{$m} = $ad[1];
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
4096: A device link (all the above combined mustn't be higher). If the neighbor was discovered as well, it becomes 8192. In addition number of devices found on this interface are added.

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

B<Options> MAC address (with vlid if useivl is configured), mode

B<Globals> -

B<Returns> -, $newdv, $newif if mode is 2 (finding MAC links)

=cut
sub UpNodIF {

	my $newdv = "";
	my $newif = "";
	my $newus = "";
	my $vlan  = "";
	my $newmet= 32768;										# This should never be seen in DB!
	my $mc    = substr($_[0],0,12);									# Strip vlid from MAC

	if($_[1] == 1){											# Node exists already...
		if($main::nod{$_[0]}{iu} < $retire){
			$newmet = 16384;								# Really bad metric forces update if interface hasn't been updated within the retirement period.
		}else{
			$newmet = $main::nod{$_[0]}{im};						# Use old if value if available
		}
	}
	&Prt("UPIF:DB$newmet ");
	foreach my $dv ( keys %{$portnew{$_[0]}} ){							# Cycle thru ports and use new IF, if metric is equal or better than the old one
		my $if = $portnew{$_[0]}{$dv}{po};

		my $metric = (defined $portnew{$_[0]}{$dv}{snr})?$portnew{$_[0]}{$dv}{snr}:256;
		$metric   += ($portprop{$dv}{$if}{chn})?1024:0;
		$metric   += ($portprop{$dv}{$if}{rtr})?2048:0;

		if($portprop{$dv}{$if}{lnk}){
			if($portprop{$dv}{$if}{nsd}){
				&Prt("N");
				$metric += 512;
			}elsif($portprop{$dv}{$if}{nal}){						# Double metric if nbr was discovered too to keep nodes on links to unreachable devs and not on any other link
				&Prt("A");
				$metric += 8192;
			}else{
				&Prt("L");
				$metric += 4096;
			}
			$metric += $portprop{$dv}{$if}{lnk};						# Add # of link detections to metric (needed for MAC links)
		}else{
			&Prt("M");
		}

		if($metric < 100 or $metric <= $newmet){						# SNR is always updated and wins over any wired interface
			$newdv  = $dv;
			$newif  = $if;
			$newmet = $metric;
			$newus  = (defined $portnew{$_[0]}{$dv}{usr})?$portnew{$_[0]}{$dv}{usr}:"";
			$vlan   = $portnew{$_[0]}{$newdv}{vl};
			&Prt("$newmet>$newdv,$newif ");
		}else{
			&Prt("$metric($dv,$if) ");
		}
	}
	&Prt("\n");

	if($newdv){
		if($_[1] == 2){
			return ($newdv,$newif);
		}elsif($_[1] and ($main::nod{$_[0]}{dv} ne $newdv or $main::nod{$_[0]}{if} ne $newif) ){
			$main::nod{$_[0]}{ic}++;
			&db::Insert('iftrack','mac,ifupdate,device,ifname,vlanid,ifmetric',"\"$mc\",\"$main::nod{$_[0]}{iu}\",\"$main::nod{$_[0]}{dv}\",\"$main::nod{$_[0]}{if}\",\"$main::nod{$_[0]}{vl}\",\"$main::nod{$_[0]}{im}\"") unless $main::opt{'t'};
			$ifchg++;
		}
		$main::nod{$_[0]}{im} = $newmet;
		$main::nod{$_[0]}{dv} = $newdv;
		$main::nod{$_[0]}{if} = $newif;
		$main::nod{$_[0]}{us} = $newus;
		$main::nod{$_[0]}{vl} = ($vlan =~ /^\d+$/)?$vlan:0;
		$main::nod{$_[0]}{iu} = $main::now;
		&Prt("UPIF:$newdv,$newif vl$main::nod{$_[0]}{vl} M$main::nod{$_[0]}{im}\n");
	}else{
		&Prt("UPIF:Old IF kept $main::nod{$_[0]}{dv},$main::nod{$_[0]}{if} M$main::nod{$_[0]}{im}\n");
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
		&Prt("\nUPIP:$mc EXISTING");
		if(exists $arp{$_[0]}){
			$hasip = 1;
			if($main::nod{$_[0]}{ip} ne $arp{$_[0]} ){
				$upip = 1;
				$main::nod{$_[0]}{ac}++;
				my $dip = &Ip2Dec($main::nod{$_[0]}{ip});
				if($dip){
					$vl = ($vl =~ /^\d+$/)?$vl:0;
					$misc::mq += &mon::Event('J',100,'secj',$main::nod{$_[0]}{dv},$main::nod{$_[0]}{dv},"IP address on $_[0] changed from $main::nod{$_[0]}{ip} to $arp{$_[0]}");
					&db::Insert('iptrack','mac,ipupdate,name,nodip,vlanid,device',"\"$mc\",\"$main::now\",\"$main::nod{$_[0]}{na}\",\"$dip\",\"$vl\",\"$main::nod{$_[0]}{dv}\"");
					$ipchg++;
				}else{
					$misc::mq += &mon::Event('J',100,'secj',$main::nod{$_[0]}{dv},$main::nod{$_[0]}{dv},"New IP address $arp{$_[0]} found for $_[0]");
				}
			}elsif($main::nod{$_[0]}{au} < $retire){					# Same IP forever, force update
				$upip = 1;
			}
		}else{
			$main::nod{$_[0]}{al}++ if $main::nod{$_[0]}{ip} ne '0.0.0.0';			# IP lost (aged out of router's arp table) if node got one before
		}
	}else{
		&Prt("\nUPIP:$mc NEW");
		if(exists $arp{$_[0]}){
			$hasip = 1;
			$upip  = 1;
		}else{
			$main::nod{$_[0]}{ip} = '0.0.0.0';
			&Prt(" no IP","n");
		}
	}
	if($upip){
		$main::nod{$_[0]}{au} = $main::now;
		$main::nod{$_[0]}{ip} = $arp{$_[0]};
		$main::nod{$_[0]}{av} = $arpc{$_[0]};
		if(exists $arpn{$_[0]} and $arpn{$_[0]}){						# ARPwatch got a name, ...
			$main::nod{$_[0]}{na} = $arpn{$_[0]};
			&Prt(" Arpwatch name ","a");
		}elsif(!$main::opt{n}){
			my $dnsna = gethostbyaddr(inet_aton($arp{$_[0]}), AF_INET);
			if($dnsna){
				$main::nod{$_[0]}{na} = $dnsna;						# Only use if we got something!
				&Prt(" DNS","d");
			}else{
				$main::nod{$_[0]}{na} = "";
				&Prt(" no DNS","i");
			}
		}
	}else{
		&Prt(" no update","o");
	}
	&Prt(" $main::nod{$_[0]}{ip} = $main::nod{$_[0]}{na}\n");

	if( exists $arp6{$_[0]} ){
		if($main::nod{$_[0]}{i6}){
			my $oldip6 = sprintf("%x:%x:%x:%x:%x:%x:%x:%x",unpack("n8",$main::nod{$_[0]}{i6}));
			my $newip6 = sprintf("%x:%x:%x:%x:%x:%x:%x:%x",unpack("n8",$arp6{$_[0]}));
			if($oldip6 ne $newip6){
				if($oldip6 =~ /^fe80/ or $newip6 !~ /^fe80/){				# Avoid reverting to link-local addresses again
					$mq += &mon::Event('J',100,'secj',$main::nod{$_[0]}{dv},$main::nod{$_[0]}{dv},"IPv6 address on $_[0] changed from $oldip6 to $newip6");
				}
			}
		}else{
			$mq += &mon::Event('J',100,'secj',$main::nod{$_[0]}{dv},$main::nod{$_[0]}{dv},"New IPv6 address ".sprintf("%x:%x:%x:%x:%x:%x:%x:%x",unpack("n8",$arp6{$_[0]}))." found for $_[0]" );
		}
		$main::nod{$_[0]}{i6} = $arp6{$_[0]};
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

	my $nip = my $nnip = 0;

	&Prt("\nBuildNod     ------------------------------------------------------------------\n");
	my $stolen = &db::Select('stolen','mac');
	foreach my $mcvl ( keys %portnew ){
		my $mc = substr($mcvl,0,12);
		my $isdev = "";

		for (@doneid){										# Allegedly more efficient than grepping
			if($_ eq $mc){$isdev = $mc;last;}
		}

		if(exists $arp{$mcvl}){
			$isdev = $arp{$mcvl} if exists $ifip{$arp{$mcvl}};
			for (@doneip){
				if($_ eq $arp{$mcvl}){$isdev = $arp{$mcvl};last;}
			}
		}

		if(exists $ifmac{$mc}){
			$isdev = join(", ",keys %{$ifmac{$mc}});
		}

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
				$main::nod{$mcvl}{i6} = '';
				$main::nod{$mcvl}{tp} = '';
				$main::nod{$mcvl}{up} = '';
				$main::nod{$mcvl}{os} = '';
				$main::nod{$mcvl}{ty} = '';
				$main::nod{$mcvl}{ou} = 0;
			}
			$main::nod{$mcvl}{nv} = &GetOui($mc);
			$main::nod{$mcvl}{ls} = $main::now;

			&UpNodIF($mcvl,$nodex);

			if(&UpNodip($mcvl,$nodex)){
				$nip++;
			}else{
				$nnip++;
			}
			$mq += &mon::Event('F',100,'secn',$main::nod{$mcvl}{dv},$main::nod{$mcvl}{dv},"Node $mc appeared on $main::nod{$mcvl}{if} Vl$main::nod{$mcvl}{vl} as $main::nod{$mcvl}{na} with IP $main::nod{$mcvl}{ip}") unless($nodex);
		}else{
			&Prt("\nBNOD:$mc DEVICE $isdev ");
			if(exists $ifmac{$mc}){
				&Prt("with IFmac");
				my @md = keys %{$ifmac{$mc}};
				if(scalar @md == 1){
					my $nolnk = (exists $main::link{$md[0]})?"":"unlinked";
					my $activ = ($main::dev{$md[0]}{ls} == $main::now)?"active":"";
					&Prt("-unique $nolnk $activ \n");
					if($nolnk and $activ){
						my ($nb, $ni) = &UpNodIF($mcvl,2);
						my $mi = $main::int{$md[0]}{$ifmac{$mc}{$md[0]}}{ina};
						if($nb and $mi and 0){# Disabled until really usable
							&Prt("BNOD:Linking device $md[0],$mi to $nb,$ni\n");
							$main::link{$md[0]}{$mi}{$nb}{$ni}{bw} = $misc::portprop{$nb}{$ni}{spd};
							$main::link{$md[0]}{$mi}{$nb}{$ni}{ty} = "MAC";
							$main::link{$md[0]}{$mi}{$nb}{$ni}{de} = "Discovered ".localtime($main::now);
							$main::link{$md[0]}{$mi}{$nb}{$ni}{du} = $misc::portprop{$nb}{$ni}{dpx};
							$main::link{$md[0]}{$mi}{$nb}{$ni}{vl} = $misc::portprop{$nb}{$ni}{vid};
							&db::WriteLink($md[0]);
							unless(exists $main::link{$nb}{$ni}){
								$main::link{$nb}{$ni}{$md[0]}{$mi}{bw} = $misc::portprop{$md[0]}{$mi}{spd};
								$main::link{$nb}{$ni}{$md[0]}{$mi}{ty} = "MAC";
								$main::link{$nb}{$ni}{$md[0]}{$mi}{de} = "Discovered ".localtime($main::now);
								$main::link{$nb}{$ni}{$md[0]}{$mi}{du} = $misc::portprop{$md[0]}{$mi}{dpx};
								$main::link{$nb}{$ni}{$md[0]}{$mi}{vl} = $misc::portprop{$md[0]}{$mi}{vid};
								&db::WriteLink($nb); #TODO improve write link to write single links (and update linktype on IF?)
							}
						}
					}
				}else{
					&Prt("(ambiguous)\n");
				}
			}else{
				&Prt("without IFmac\n");
			}
		}

		if(exists $stolen->{$mc}){
			$mq += &mon::Event('N',150,'secs',$main::nod{$mcvl}{dv},$main::nod{$mcvl}{dv},"Node $mc reappeared on $main::nod{$mcvl}{if} as $main::nod{$mcvl}{na} with IP $main::nod{$mcvl}{ip}");
		}
		&Prt('',"\n") unless ($nip + $nnip) % 80;
	}
	&Prt("BNOD:FINISHED $nip IP and $nnip non-IP nodes processed\n");
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
	foreach my $if( keys %{$portprop{$dv}} ){
		if(	$portprop{$dv}{$if}{pop} and
			!$portprop{$dv}{$if}{rtr} and
			!$portprop{$dv}{$if}{lnk} and
			!$portprop{$dv}{$if}{chn} and
			!$portprop{$dv}{$if}{nsd} and
			$portprop{$dv}{$if}{pop} > $macflood){

			$mq += &mon::Event('N',150,'secf',$dv,$dv,"$portprop{$dv}{$if}{pop} MAC entries exceed threshold of $macflood on $dv,$if");
			$nfld++;
		}
	}
	&Prt("FLOD:$nfld IFs learned more than $macflood MACs\n");
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
		unless($main::opt{'t'}){
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
			}else{
				RRDs::update "$nedipath/rrd/$dv/system.rrd","N:$main::dev{$na}{cpu}:$main::dev{$na}{mcp}:$main::dev{$na}{cuv}:$main::dev{$na}{tmp}";
				$err = RRDs::error;
				if($err){
					&Prt("DRRD:Can't update $nedipath/rrd/$dv/system.rrd\n","Ru");
				}else{
					&Prt("DRRD:Updated $nedipath/rrd/$dv/system.rrd\n");
				}
			}
		}
		&Prt("DRRD:CPU=$main::dev{$na}{cpu} MEM=$main::dev{$na}{mcp} TEMP=$main::dev{$na}{tmp} CUS=$main::dev{$na}{cuv}\n");

		return if $skip =~ /t/ and $skip =~ /e/ and $skip =~ /b/ and $skip =~ /d/;
		$err = 0;

		&Prt("DRRD:IFName   Inoct    Outoct   Inerr  Outerr Indis  Outdis Inbcst Stat\n");
		foreach my $i ( keys %{$main::int{$na}} ){
			if(exists $main::int{$na}{$i}{ina}){						# Avoid errors due empty ifnames
				$irf =  $main::int{$na}{$i}{ina};
				$irf =~ s/([^a-zA-Z0-9_.-])/"%" . uc(sprintf("%2.2x",ord($1)))/eg;
				unless($main::opt{'t'}){
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
								"DS:status:GAUGE:$ds:0:3",
								"RRA:AVERAGE:0.5:1:$rrdsize",
								"RRA:AVERAGE:0.5:10:$rrdsize"
								);
						$err = RRDs::error;
					}
					if($err){
						&Prt("ERR :RRD $nedipath/rrd/$dv/$irf.rrd $err\n","Ri($irf)");
					}else{
						RRDs::update "$nedipath/rrd/$dv/$irf.rrd","N:$main::int{$na}{$i}{ioc}:$main::int{$na}{$i}{ooc}:$main::int{$na}{$i}{ier}:$main::int{$na}{$i}{oer}:$main::int{$na}{$i}{idi}:$main::int{$na}{$i}{odi}:$main::int{$na}{$i}{ibr}:".($main::int{$na}{$i}{sta} & 3);
						$err = RRDs::error;
						if($err){
							&Prt("ERR :$irf.rrd $err\n","Ru($irf)");
						}
					}
				}
				&Prt(sprintf ("DRRD:%-8.8s %8.8s %8.8s %6.6s %6.6s %6.6s %6.6s %6.6s %4.4s\n", $irf,$main::int{$na}{$i}{ioc},$main::int{$na}{$i}{ooc},$main::int{$na}{$i}{ier},$main::int{$na}{$i}{oer},$main::int{$na}{$i}{idi},$main::int{$na}{$i}{odi},$main::int{$na}{$i}{ibr},$main::int{$na}{$i}{sta} & 3) );
			}else{
				&Prt("DRRD:No IF name for IF-index $i\n","Rn($i)");
			}
		}
	}else{
		&Prt("DRRD:Can't create directory $nedipath/rrd/$dv\n","Rd");
	}
}

=head2 FUNCTION TopRRD()

Update Top traffic, error, power & monitoring RRDs.

B<Options> -

B<Globals> -

B<Returns> -

=cut
sub TopRRD {

	my (%ec, %ifs);
	my $err = "";
	my $mok = my $msl = my $mal = 0;
	$ec{'50'} = $ec{'100'} = $ec{'150'} = $ec{'200'} = $ec{'250'} = 0;
	$ifs{'0'} = $ifs{'1'} = $ifs{'3'} = 0;
	&Prt("\nTopRRD       ------------------------------------------------------------------\n");
	# Access traffic using delta octets to avoid error from missing or rebooted switches. Needs to be divided by rrdstep*1M to get MB/s
	my $tat = &db::Select('interfaces','',"sum(dinoct)/(1000000*$rrdstep),sum(doutoct)/(1000000*$rrdstep)","iftype != 71 AND lastdis > $main::now - $rrdstep",'devices','device');

	# Wired interface (type not 71) errors/s
	my $twe = &db::Select('interfaces','',"sum(dinerr)/$rrdstep,sum(douterr)/$rrdstep,sum(dindis)/$rrdstep,sum(doutdis)/$rrdstep","linktype = \"\" AND lastdis > $main::now - $rrdstep",'devices','device');

	# Total nodes lastseen
	my $nodl = &db::Select('nodes','',"count(lastseen)","lastseen = $main::now");

	# Total nodes firstseen
	my $nodf = &db::Select('nodes','',"count(firstseen)","firstseen = $main::now");

	# Total power in Watts
	my $pwr = &db::Select('interfaces','',"sum(poe)/1000","lastdis > $main::now - $rrdstep",'devices','device');

	# Count IF ifstat up=3, down=1 and admin down=0
	my $ifdb = &db::Select('interfaces','ifstat','ifstat,count(ifstat) as c',"lastdis > $main::now - $rrdstep group by ifstat",'devices','device');
	foreach my $k (keys %$ifdb ) {
		$ifs{$k} = $ifdb->{$k}{'c'} if $ifdb->{$k}{'c'};
	}

	# Number of monitored targets / check if moni's running...
	my $lck = &db::Select('monitoring','',"max(lastok)");
	if($lck and $lck > (time - 2 * $pause) ){
		$mok = &db::Select('monitoring','',"count(status)","test != '' AND latency < $latw AND status = 0");
		if($mok){
			# Number of slow targets
			$msl = &db::Select('monitoring','',"count(status)","test != '' AND latency > $latw AND status = 0");

			# Number of dead targets
			$mal = &db::Select('monitoring','',"count(status)","test != '' AND status > 0");
		}else{
			my $msg = "Last successful check on ".localtime($lastcheck).", is moni running?";
			&db::Insert('events','level,time,source,info,class',"\"150\",\"$main::now\",\"NeDi\",\"$msg\",\"nedi\"");
			&Prt("TRRD:$msg\n");
		}
	}else{
		my $msg = "No successful check at all, is moni running?";
		$msg = "Last successful check on ".localtime($lck).", is moni running?" if $lck;
		&db::Insert('events','level,time,source,info,class',"\"150\",\"$main::now\",\"NeDi\",\"$msg\",\"nedi\"");
		&Prt("TRRD:$msg\n");
	}

	# Number of cathegorized events during discovery cycle
	my $dbec = &db::Select('events','level',"level,count(*) as c","time > ".(time - $rrdstep)." GROUP BY level");
	foreach my $k (keys %$dbec ) {
		$ec{$k} = $dbec->{$k}{'c'} if $dbec->{$k}{'c'};
	}

	&Prt("TRRD:Trf=$tat->[0][0]/$tat->[0][1] Err=$twe->[0][0]/$twe->[0][1] Dis=$twe->[0][2]/$twe->[0][3]\n");
	&Prt("TRRD:Up/Dn/Dis=$ifs{'3'}/$ifs{'1'}/$ifs{'0'} Pwr=${pwr}W Nod=$nodl/$nodf Mon=$mok/$msl/$mal Event=$ec{'50'}/$ec{'100'}/$ec{'150'}/$ec{'200'}/$ec{'250'}\n");
	if($main::opt{t} or $main::opt{a}){
		&Prt("TRRD:Not writing when testing or adding a single device\n");
	}else{
		unless(-e "$nedipath/rrd/top.rrd"){
			my $ds = 2 * $rrdstep;
			RRDs::create(	"$nedipath/rrd/top.rrd",
					"-s","$rrdstep",
					"DS:tinoct:GAUGE:$ds:0:U",
					"DS:totoct:GAUGE:$ds:0:U",
					"DS:tinerr:GAUGE:$ds:0:U",
					"DS:toterr:GAUGE:$ds:0:U",
					"DS:tindis:GAUGE:$ds:0:U",
					"DS:totdis:GAUGE:$ds:0:U",
					"DS:nodls:GAUGE:$ds:0:U",
					"DS:nodfs:GAUGE:$ds:0:U",
					"DS:tpoe:GAUGE:$ds:0:U",
					"DS:upif:GAUGE:$ds:0:U",
					"DS:downif:GAUGE:$ds:0:U",
					"DS:disif:GAUGE:$ds:0:U",
					"DS:monok:GAUGE:$ds:0:U",
					"DS:monsl:GAUGE:$ds:0:U",
					"DS:monal:GAUGE:$ds:0:U",
					"DS:msg50:GAUGE:$ds:0:U",
					"DS:msg100:GAUGE:$ds:0:U",
					"DS:msg150:GAUGE:$ds:0:U",
					"DS:msg200:GAUGE:$ds:0:U",
					"DS:msg250:GAUGE:$ds:0:U",
					"RRA:AVERAGE:0.5:1:$rrdsize",
					"RRA:AVERAGE:0.5:10:$rrdsize");
			$err = RRDs::error;
		}
		if($err){
			&Prt("ERR :$err\n");
		}else{
			RRDs::update "$nedipath/rrd/top.rrd","N:$tat->[0][0]:$tat->[0][1]:$twe->[0][0]:$twe->[0][1]:$twe->[0][2]:$twe->[0][3]:$nodl:$nodf:$pwr:$ifs{'3'}:$ifs{'1'}:$ifs{'0'}:$mok:$msl:$mal:$ec{'50'}:$ec{'100'}:$ec{'150'}:$ec{'200'}:$ec{'250'}";
			$err = RRDs::error;
			if($err){
				&Prt("ERR :$err\n");
			}else{
				&Prt("TRRD:$nedipath/rrd/top.rrd update OK\n");
			}
		}
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
	$dv     =~ s/([^a-zA-Z0-9_.-])/"%" . uc(sprintf("%2.2x",ord($1)))/eg;
	if(-e "$nedipath/conf/$dv"){
		$ok = 1;
	}else{
		&misc::Prt("WCFF:Creating $nedipath/conf/$dv\n");
		$ok = mkdir ("$nedipath/conf/$dv", 0755);
	}
	my $wcf = "$nedipath/conf/$dv/".strftime ("%Y-%m%d-%H%M.cfg", localtime($main::now) );
	if($ok and open (CF, ">$wcf" ) ){
		foreach ( @curcfg ){
			print CF "$_\n";
		}
		close (CF);
		&misc::Prt("WCFF:Config written to $wcf\n");

		if($main::opt{'B'}){									# if >0 only keep that many, based on raider82's idea
			my @cfiles = sort {$b cmp $a} glob("$nedipath/conf/$dv/*.cfg");
			my $cur = 0;
			foreach my $cf (@cfiles) {
				$cur++;
				if($cur > $main::opt{'B'}){
					$dres = unlink ("$cf");
					if($dres){
						&misc::Prt("WCFF:Deleted $cf\n");
					}else{
						&Prt("ERR :Deleting config $cf\n","Bd");
					}
				}
			}
		}
	}else{
		&Prt("ERR :Writing config $nedipath/conf/$dv","Bw");
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
	my $link = retrieve("$main::p/link.db");
	%main::link = %$link;
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
	store \%main::link, "$main::p/link.db";
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

=head2 FUNCTION DevIcon()

Assign icon based on services or use existing one

B<Options> icon, services

B<Globals> -

B<Returns> icon

=cut
sub DevIcon{
	if($_[1]){
		return $_[1];
	}else{
		if($_[0] > 8){
			return 'csan';
		}elsif($_[0] > 4){
			return 'w3an';
		}elsif($_[0] > 1){
			return 'w2an';
		}else{
			return 'w1an';
		}
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

	if($main::opt{'K'}){										# Delete stored key first, based on raider82's idea
		my $res = `ssh-keygen -R $_[0] -f ~/.ssh/known_hosts`;
		&misc::Prt("DISC:Cli: key removed for $_[0]\n","Kr");
	}

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
