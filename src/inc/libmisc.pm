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
use Socket;
use Socket6;
use bigint;

use vars qw($netfilter $webdev $nosnmpdev $border $ignoredesc $ignoreconf $getfwd $timeout $retry $ncmd);
use vars qw($nedipath $backend $dbname $dbuser $dbpass $dbhost $uselogin $usessh $usepoe $sms $stolen);
use vars qw($rrdcmd $rrdstep $rrdsize $nmapcmd $nagpipe $snmpwrite $redbuild $guiauth $locsep);
use vars qw($arpwatch $ignoredvlans $ignoredmacs $useivl $retire $revive $arppoison $macflood $seedlist);
use vars qw($notify $norep $latw $cpua $mema $tmpa $trfa $brca $poew $supa $pause $smtpserver $mailfrom $mailfoot);
use vars qw(%comm3 %login %map %useif %skippol %doip %seedini %sysobj %ifmac %ifip %useip);
use vars qw(%oui %arp  %arp6 %arpc %arpn %portprop %portnew %portdes %vlid);
use vars qw(@todo @comms @seeds @users @curcfg @nam2loc);

our @donenam = @doneid = @doneip = @failid = @failip = ();
our $ipchg = $ifchg = $mq = 0;
our $ouidev = '';

=head2 FUNCTION ReadConf()

Searches for nedi.conf in nedi folder first then fall back to /etc. Parse
it if found or die if not.

locsep is set to a space if commented.

B<Options> -

B<Globals> various misc:: varables

B<Returns> dies on missing nedi.conf

=cut
sub ReadConf{

	my $nconf = "$main::p/nedi.conf";

	$ignoredvlans = $ignoredmacs = $useivl = $border = $nosnmpdev = $ignoredesc = $ignoreconf = $usessh = $usepoe = "isch nid gsetzt!";
	$locsep   = " ";
	$rrdsize  = 1000;
	$macflood = 1000;

	if($main::opt{'U'}){
		$nconf = $main::opt{'U'}
	}
	my @conf = ();
	if($nconf eq '-'){
		@conf = <STDIN>
	}else{
		if(-e "$nconf"){
			open  ("CONF", $nconf);
		}elsif(-e "/etc/nedi.conf"){
			open  ("CONF", "/etc/nedi.conf");
		}else{
			die "Can't find $nconf: $!\n";
		}
		@conf = <CONF>;
		close("CONF");
	}

	foreach my $l (@conf){
		if($l !~ /^[#;]|^(\s)*$/){
			$l =~ s/[\r\n]//g;
			my @v  = split(/\s+/,$l);
			if($v[0] eq "comm"){
				push (@comms,$v[1]);
				$comm3{$v[1]}{aprot} = $v[2];
				$comm3{$v[1]}{apass} = $v[3];
				$comm3{$v[1]}{pprot} = $v[4];
				$comm3{$v[1]}{ppass} = $v[5];}
			elsif($v[0] eq "usr"){
				push (@users,$v[1]);
				$login{$v[1]}{pw} = $v[2];
				$login{$v[1]}{en} = $v[3];}
			elsif($v[0] eq "useip"){
				$useip{$v[1]} = $v[2];}
			elsif($v[0] eq "uselogin"){$uselogin = $v[1]}
			elsif($v[0] eq "ignoreconf"){$ignoreconf = $v[1]}
			elsif($v[0] eq "snmpwrite"){$snmpwrite = $v[1]}
			elsif($v[0] eq "usessh"){$usessh = $v[1]}
			elsif($v[0] eq "skippol"){$skippol{$v[1]} = (defined $v[2])?$v[2]:''}		# Avoid undef...
			elsif($v[0] eq "usepoe"){$usepoe = $v[1]}

			elsif($v[0] eq "mapip"){$map{$v[1]}{ip} = $v[2]}
			elsif($v[0] eq "maptp"){$map{$v[1]}{cp} = $v[2]}
			elsif($v[0] eq "mapsn"){$map{$v[1]}{sn} = $v[2]}
			elsif($v[0] eq "mapna"){$map{$v[1]}{na} = join ' ', splice @v,2}
			elsif($v[0] eq "maplo"){$map{$v[1]}{lo} = join ' ', splice @v,2}
			elsif($v[0] eq "mapco"){$map{$v[1]}{co} = join ' ', splice @v,2}
			elsif($v[0] eq "nam2loc"){
				$nam2loc[0] = $v[1];
				$nam2loc[1] = join ' ', splice @v,2;
			}

			elsif($v[0] eq "nosnmpdev"){$nosnmpdev = $v[1]}
			elsif($v[0] eq "webdev"){$webdev = $v[1]}
			elsif($v[0] eq "netfilter"){$netfilter = $v[1]}
			elsif($v[0] eq "border"){$border = join ' ', splice @v,1}
			elsif($v[0] eq "ouidev"){$ouidev = join ' ', splice @v,1}
			elsif($v[0] eq "ignoredesc"){$ignoredesc = $v[1]}

			elsif($v[0] eq "backend"){$backend = $v[1]}
			elsif($v[0] eq "dbname"){$dbname = $v[1]}
			elsif($v[0] eq "dbuser"){$dbuser = $v[1]}
			elsif($v[0] eq "dbpass"){$dbpass = (defined $v[1])?$v[1]:''}			# based on dirtyal's suggestion
			elsif($v[0] eq "dbhost"){$dbhost = $v[1]}

			elsif($v[0] eq "ignoredvlans"){$ignoredvlans = $v[1]}
			elsif($v[0] eq "ignoredmacs"){$ignoredmacs = $v[1]}
			elsif($v[0] eq "useivl"){$useivl = $v[1]}
			elsif($v[0] eq "getfwd"){$getfwd = $v[1]}
			elsif($v[0] eq "retire"){$retire = $main::now - $v[1] * 86400;$revive = $main::now - $v[1] * 43200;}# TODO make revive a bit random to distribute updates?
			elsif($v[0] eq "timeout"){$timeout = $v[1];$retry = (defined $v[2])?$v[2]:1}
			elsif($v[0] eq "arpwatch"){$arpwatch = $v[1]}
			elsif($v[0] eq "arppoison"){$arppoison = $v[1]}
			elsif($v[0] eq "macflood"){$macflood = $v[1]}

			elsif($v[0] eq "rrdstep"){$rrdstep = $v[1]}
			elsif($v[0] eq "rrdsize"){$rrdsize = $v[1]}
			elsif($v[0] eq "rrdcmd"){$rrdcmd = $v[1]}
			elsif($v[0] eq "nagpipe"){$nagpipe = $v[1]}

			elsif($v[0] eq "notify"){$notify = $v[1]}
			elsif($v[0] eq "noreply"){$norep = $v[1]}
			elsif($v[0] eq "latency-warn"){$latw = $v[1]}
			elsif($v[0] eq "cpu-alert"){$cpua = $v[1]}
			elsif($v[0] eq "mem-alert"){$mema = $v[1]}
			elsif($v[0] eq "temp-alert"){$tmpa = $v[1]}
			elsif($v[0] eq "traf-alert"){$trfa = $v[1]}
			elsif($v[0] eq "bcast-alert"){$brca = $v[1]}
			elsif($v[0] eq "poe-warn"){$poew = $v[1]}
			elsif($v[0] eq "supply-alert"){$supa = $v[1]}

			elsif($v[0] eq "pause"){$pause = $v[1]}
			elsif($v[0] eq "smtpserver"){$smtpserver = $v[1]}
			elsif($v[0] eq "mailfrom"){$mailfrom = $v[1]}
			elsif($v[0] eq "mailfooter"){$mailfoot = join ' ', splice @v,1}
			elsif($v[0] eq "sms"){$sms{$v[1]} = $v[2]}
			elsif($v[0] eq "guiauth"){$guiauth = $v[1]}
			elsif($v[0] eq "locsep"){$locsep = $v[1]}
			elsif($v[0] eq "redbuild"){$redbuild = $v[1]}

			elsif($v[0] eq "nedipath"){
				$nedipath = $v[1];
				if($main::p !~ /^\//){
					&Prt("Started with relative path!\n");
					$nedipath = $main::p;
				}else{
					if($nedipath ne $main::p){die "Please configure nedipath!\n";}
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
sub ReadSysobj{

	my ($so) = @_;

	unless( exists $sysobj{$so} ){									# Load .def if not done already
		if(-e "$main::p/sysobj/$so.def"){
			open  ("DEF", "$main::p/sysobj/$so.def");
			&Prt("SOBJ:Reading $so.def\n");
		}else{
			open  ("DEF","$main::p/sysobj/other.def");
			&Prt("SOBJ:$so.def not found, using other.def\n");
		}
		my @def = <DEF>;
		chomp @def;
		close("DEF");
		$sysobj{$so}{ty} = $so;
		$sysobj{$so}{hc} = $sysobj{$so}{mv} = $sysobj{$so}{ib} = 0;
		$sysobj{$so}{pm} = '-';
		$sysobj{$so}{st} = '';
		$sysobj{$so}{en} = '';
		$sysobj{$so}{px} = '';
		$sysobj{$so}{cul}= '';
		$sysobj{$so}{vrf}= '';

		foreach my $l (@def){
			if($l !~ /^[#;]|^\s*$/){
				$l =~ s/[\r\n]|\s+$//g;							# Chomp doesn't remove \r and trailing spaces
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
				elsif($v[0] eq "ArpND")		{$sysobj{$so}{ar} = $v[1]}
				elsif($v[0] eq "Dispro")	{$sysobj{$so}{dp} = $v[1]}
				elsif($v[0] eq "Typoid")	{$sysobj{$so}{to} = $v[1]}		# tx vtur

				elsif($v[0] eq "VLnams")	{$sysobj{$so}{vn} = $v[1]}
				elsif($v[0] eq "VLnamx")	{$sysobj{$so}{vl} = $v[1]}
				elsif($v[0] eq "Group")		{$sysobj{$so}{dg} = $v[1]}
				elsif($v[0] eq "Mode")		{$sysobj{$so}{dm} = $v[1]}
				elsif($v[0] eq "CfgChg")	{$sysobj{$so}{cc} = $v[1]}
				elsif($v[0] eq "CfgWrt")	{$sysobj{$so}{cw} = $v[1]}

				elsif($v[0] eq "StartX")	{$sysobj{$so}{st} = $v[1]}
				elsif($v[0] eq "EndX")		{$sysobj{$so}{en} = $v[1]}
				elsif($v[0] eq "IFname")	{$sysobj{$so}{in} = $v[1]}
				elsif($v[0] eq "IFaddr")	{
					$sysobj{$so}{ia} = $v[1];
					$sysobj{$so}{vrf}= $v[2] if $v[2];
				}
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


				elsif($v[0] eq "CPUutl")	{
					$sysobj{$so}{cpu} = $v[1];
					$sysobj{$so}{cmu} = ($v[2])?$v[2]:1;
				}
				elsif($v[0] eq "MemCPU")	{
					$sysobj{$so}{mem} = $v[1];
					$sysobj{$so}{mmu} = ($v[2])?$v[2]:1;
				}
				elsif($v[0] eq "Temp")		{
					$sysobj{$so}{tmp} = $v[1];
					$sysobj{$so}{tmu} = ($v[2])?$v[2]:1;
				}
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
sub ReadOUIs{

	open  ("OUI", "$main::p/inc/oui.txt" ) or die "no oui.txt in $main::p/inc!";			# Read OUI's first
	my @ouitxt = <OUI>;
	close("OUI");

	my @nics = grep /(base 16)/,@ouitxt;
	foreach my $l (@nics){
		$l =~ s/^\s*|[\r\n]$//g;
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
		$l =~ s/^\s*|[\r\n]$//g;
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
sub GetOui{

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
sub Strip{

	my ($str,$ret) = @_;

	$ret = (defined $ret)?$ret:'';
	if(defined $str and $str ne ''){								# only strip if it's worth it!
		$str =~ s/^\s*|\s*$//g;									# leading/trailing spaces
		$str =~ s/"//g;										# quotes
		$str =~ s/[\x00-\x1F]//g;								# below ASCII
		$str =~ s/[\x7F-\xff]//g;								# above ASCII
		$str =~ s/\s+/ /g;									# excess spaces
		$str = int($str + 0.5) if $str =~ /^\d+(\.\d+)?$/ and $ret =~ /^0$/;			# round to int, if it's float
		return $str;
	}else{
		return $ret;
	}
}


=head2 FUNCTION Shif()

Shorten interface names.

B<Options> IF name

B<Globals> -

B<Returns> shortened IF name

=cut
sub Shif{

	my ($n) = @_;

	if($n){
		$n =~ s/ten(-)?gigabitethernet/Te/i;
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

=head2 FUNCTION AvailIF()

Warn on less than supply-alert available access ports if device has more than 10 ethernet ports

B<Options> Device

B<Globals> -

B<Returns> -

=cut
sub AvAccess{

	my ($dv) = @_;
	my $avif = my $ethif = 0;

	foreach my $i ( keys %{$main::int{$dv}} ){
		if($main::int{$dv}{$i}{typ} =~ /^(6|7|117)$/){
			$avif++	if $main::int{$dv}{$i}{sta} < 3 and $main::int{$dv}{$i}{chg} < $retire;
			$ethif++;
		}
	}

	my $supa = (exists $main::mon{$dv})?$main::mon{$dv}{sa}:$supa;
	if($ethif > 10 and $avif < $supa ){
		$mq += &mon::Event('D',150,'nedf',$dv,$dv,"$avif available access port".(($avif==1)?' is':'s are')." below threshold of $supa");
	}
}


=head2 FUNCTION ProCount()

Process counter with respect to overflow and delta 

B<Options> Device, IF index, abs index, delta index, status, value

B<Globals> Interface abs and delta value

B<Returns> -

=cut
sub ProCount{

	my ($dv,$i,$abs,$dlt,$stat,$val) = @_;

	if($stat){
		$main::int{$dv}{$i}{$abs} = 0 unless $main::int{$dv}{$i}{$abs};
		$main::int{$dv}{$i}{$dlt} = 0 unless $main::int{$dv}{$i}{$dlt};
	}else{
		if($main::int{$dv}{$i}{old}){
			my $dval = $val - $main::int{$dv}{$i}{$abs};
			if($dval == abs $dval){
				$main::int{$dv}{$i}{$dlt} = $dval;
			}else{
				&misc::Prt("ERR :$abs overflow, not updating\n",'');				
			}
		}else{
			$main::int{$dv}{$i}{$dlt} = 0;
		}
		$main::int{$dv}{$i}{$abs} = $val;
	}
}

=head2 FUNCTION CheckIf()

Check interface against monitoring policy 

B<Options> Device, IF name, Skipstring

B<Globals> -

B<Returns> -

=cut
sub CheckIF{

	my ($dv,$i,$skip) = @_;
	
	return unless $main::int{$dv}{$i}{old};

	my $ele = 0;
	my $lvl = 100;
	my $cla = "if";
	my $iftxt = "$main::int{$dv}{$i}{ina} ";
	$iftxt .= "($main::int{$dv}{$i}{ali}) " if $main::int{$dv}{$i}{ali};
	if($main::int{$dv}{$i}{'lty'}){
		$iftxt .= ($main::int{$dv}{$i}{com})?substr($main::int{$dv}{$i}{com},0,20):$main::int{$dv}{$i}{lty};
		$lvl = 150;
		$cla = "ln";
		$ele = &mon::Elevate('L',0,$dv);
	}elsif($main::int{$dv}{$i}{'plt'}){
		$iftxt .= ($main::int{$dv}{$i}{pco})?substr($main::int{$dv}{$i}{pco},0,20):$main::int{$dv}{$i}{plt};
		$lvl = 150;
		$cla = "ln";
		$ele = &mon::Elevate('L',0,$dv);
	}
	if($main::dev{$dv}{'pls'} > $main::lasdis){							# Avoid > 100% events due to offline dev being rediscovered
		my $trfele = &mon::Elevate('T',$ele,$dv);
		my $errele = &mon::Elevate('E',$ele,$dv);
		my $dicele = &mon::Elevate('G',$ele,$dv);
		if($trfele and $main::int{$dv}{$i}{'spd'} and $skip !~ /t/){				# Ignore speed 0 and if traffic is skipped
			my $rioct = int( $main::int{$dv}{$i}{'dio'} / $main::int{$dv}{$i}{'spd'} / $rrdstep * 800 );
			my $rooct = int( $main::int{$dv}{$i}{'doo'} / $main::int{$dv}{$i}{'spd'} / $rrdstep * 800 );
			my $tral = ($main::int{$dv}{$i}{'tra'})?$main::int{$dv}{$i}{'tra'}:$trfa;
			if($tral and $rioct > $tral){							# Threshold of 0 means ignore
				$mq += &mon::Event($trfele,200,$cla.'ti',$dv,$dv,"$iftxt (".DecFix($main::int{$dv}{$i}{'spd'}).") having $rioct% inbound traffic for ${rrdstep}s, exceeds alert threshold of ${tral}%!");
			}
			if($tral and $rooct > $tral){
				$mq += &mon::Event($trfele,200,$cla.'to',$dv,$dv,"$iftxt (".DecFix($main::int{$dv}{$i}{'spd'}).") having $rooct% outbound traffic for ${rrdstep}s, exceeds alert threshold of ${tral}%!");
			}
			my $bcps = int($main::int{$dv}{$i}{'dib'}/$rrdstep);
			my $bral = ($main::int{$dv}{$i}{'bra'})?$main::int{$dv}{$i}{'bra'}:$brca;
			if($bral and $bcps > $bral){
				$mq += &mon::Event($trfele,200,$cla.'bi',$dv,$dv,"$iftxt having $bcps inbound broadcasts/s, exceeds alert threshold of ${bral}/s!");
			}
		}
		if($errele and $main::int{$dv}{$i}{typ} != 71 and $skip !~ /e/){			# Ignore Wlan IF
			if($main::int{$dv}{$i}{die} > $rrdstep){
				$mq += &mon::Event($errele,$lvl,$cla.'ei',$dv,$dv,"$iftxt having many ($main::int{$dv}{$i}{die}) inbound errors for ${rrdstep}s!");
			}elsif($main::int{$dv}{$i}{die} > $rrdstep / 60){
				$mq += &mon::Event( ($errele > 1)?1:0,$lvl,$cla.'ei',$dv,$dv,"$iftxt having some ($main::int{$dv}{$i}{die}) inbound errors for ${rrdstep}s");
			}
			if($main::int{$dv}{$i}{doe} > $rrdstep){
				$mq += &mon::Event($errele,$lvl,$cla.'eo',$dv,$dv,"$iftxt having many ($main::int{$dv}{$i}{doe}) outbound errors for ${rrdstep}s!");
			}elsif($main::int{$dv}{$i}{doe} > $rrdstep / 60){
				$mq += &mon::Event( ($errele > 1)?1:0,$lvl,$cla.'eo',$dv,$dv,"$iftxt having some ($main::int{$dv}{$i}{doe}) outbound errors for ${rrdstep}s");
			}
		}
		if($dicele and $main::int{$dv}{$i}{typ} != 71 and $skip !~ /d/){			# Ignore Wlan IF
			if($main::int{$dv}{$i}{did} > $rrdstep * 1000){
				$mq += &mon::Event($errele,$lvl,$cla.'di',$dv,$dv,"$iftxt having $main::int{$dv}{$i}{did} inbound discards for ${rrdstep}s!");
			}
			if($main::int{$dv}{$i}{dod} > $rrdstep * 1000){
				$mq += &mon::Event($errele,$lvl,$cla.'do',$dv,$dv,"$iftxt having $main::int{$dv}{$i}{dod} outbound discards for ${rrdstep}s!");
			}
		}
	}

	if($main::int{$dv}{$i}{sta} == 0 and $main::int{$dv}{$i}{pst} != 0 and $skip !~ /a/){
		$mq += &mon::Event( &mon::Elevate('A',$ele,$dv),$lvl,$cla.'ad',$dv,$dv,"$iftxt has been disabled, previous status change on ".localtime($main::int{$dv}{$i}{pcg}) );
	}elsif($main::int{$dv}{$i}{sta} == 1 and $main::int{$dv}{$i}{pst} > 1 and $skip !~ /o/){
		$mq += &mon::Event( &mon::Elevate('O',$ele,$dv),$lvl,$cla.'op',$dv,$dv,"$iftxt went down, previous status change on ".localtime($main::int{$dv}{$i}{pcg}) );
	}

	if($main::int{$dv}{$i}{lty} or $main::int{$dv}{$i}{plt} and $skip !~ /p/){
		my $typc = ($main::int{$dv}{$i}{lty} ne $main::int{$dv}{$i}{plt})?" type ".(($main::int{$dv}{$i}{plt})?" from $main::int{$dv}{$i}{plt}":"").(($main::int{$dv}{$i}{lty})?" to $main::int{$dv}{$i}{lty}":""):"";
		my $spdc = ($main::int{$dv}{$i}{spd} ne $main::int{$dv}{$i}{spd})?" speed from ".&DecFix($main::int{$dv}{$i}{psp})." to ".&DecFix($main::int{$dv}{$i}{spd}):"";
		my $dupc = ($main::int{$dv}{$i}{dpx} ne $main::int{$dv}{$i}{pdp})?" duplex from $main::int{$dv}{$i}{pdp} to $main::int{$dv}{$i}{dpx}":"";
		my $ndio = (!$main::int{$dv}{$i}{dio} and $main::int{$dv}{$i}{sta} & 3)?" did not receive any traffic":"";
		my $ndoo = (!$main::int{$dv}{$i}{doo} and $main::int{$dv}{$i}{sta} & 3)?" did not send any traffic":"";
		if( $typc or $spdc or $dupc or $ndio or $ndoo ){
			my $msg  = "$iftxt ".(($typc or $spdc or $dupc)?"changed":"")."$typc$spdc$dupc$ndio$ndoo";
			$mq += &mon::Event($ele,$lvl,$cla.'c',$dv,$dv,$msg);
		}
	}
}

=head2 FUNCTION MapIp()

Map values based on IP address if set in %misc::map.

The mapped value is returned with status=1 if a mapping exists, the given value along status=0 if not.
If typ is 'ip', it'll always return the IP address (value is ignored).
If typ is 'na' and nedi is called with -f (use IPs instead of names) the IP is returned as well.

B<Options> IP address, mode, value

B<Globals> -

B<Returns> mapped value

=cut
sub MapIp{
	my ($ip,$typ,$val) = @_;

	if($typ eq 'na' and $main::opt{'f'}){
		return ($ip,1);
        }elsif($typ eq 'lo' and @nam2loc){
                my $loc = $val;
                $loc =~ s/$nam2loc[0]/$nam2loc[1]/ee;
		&Prt("MAP :Mapped name to location $loc\n");
                return ($loc,1);
	}elsif( exists $map{$ip} and exists $map{$ip}{$typ} ){
		if($typ eq 'na' and $map{$ip}{$typ} eq 'map2DNS'){
			my $na = gethostbyaddr(inet_aton($ip), AF_INET);
			if($na){
				&Prt("MAP :Mapped name to DNS $na\n");
				return ($na,1);
			}else{
				&Prt("MAP :Error mapping name to DNS, mapped to IP $ip instead\n");
				return ($ip,1);
			}
		}elsif($typ eq 'na' and $map{$ip}{$typ} eq 'map2IP'){
			&Prt("MAP :Mapped name to IP $ip\n");
			return ($ip,1);
		}else{
			&Prt("MAP :Mapped $typ to $map{$ip}{$typ}\n");
			return ($map{$ip}{$typ},1);
		}
	}elsif( exists $map{'all'} and exists $map{'all'}{$typ} ){
			&Prt("MAP :Mapped $typ to $map{'all'}{$typ} for all\n");
			return ($map{'all'}{$typ},1);
	}else{
		$val = $ip if $typ eq 'ip';
		return ($val,0);
	}
}

=head2 FUNCTION MSM2I()

Converts HP MSM (former Colubris) IF type to IEEE types

B<Options>IF type

B<Globals> -

B<Returns> IEEE type

=cut
sub MSM2I{

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
sub Ip2Dec{
	if(!$_[0]){$_[0] = 0}
	return unpack N => pack CCCC => split /\./ => shift;
}

=head2 FUNCTION Dec2Ip()

Of course we need to convert them back.

B<Options> dec IP

B<Globals> -

B<Returns> IP address

=cut
sub Dec2Ip{
	if(!$_[0]){$_[0] = 0}
	return join '.' => map { ($_[0] >> 8*(3-$_)) % 256 } 0 .. 3;
}

=head2 FUNCTION IP6toDB()

Convert IPv6 for writing to DB (e.g. mysql-binary).
It'll return undef, if ip6 is not true, thus can easily be
used in functions like db::WriteNet()

B<Options> dbhandle

B<Globals> -

B<Returns> -

=cut
sub IP6toDB{

	my ($addr,$ip6) = @_;

	if($misc::backend eq 'Pg'){
		if($addr and $ip6){
			return  $addr;
		}else{
			return  undef;									# Pg accepts NULL but not empty :-/
		}
	}elsif($addr and $ip6){
		return inet_pton(AF_INET6, $addr);
	}
}

=head2 FUNCTION IP6Text()

Returns binary IPv6 as text

B<Options> binary IPv6

B<Globals> -

B<Returns> IPv6 as text

=cut
sub IP6Text{
	return inet_ntop(AF_INET6, $_[0]) if defined $_[0];
}

=head2 FUNCTION IP2Name()

Returns DNS name

B<Options> IP Address

B<Globals> -

B<Returns> DNS Name

=cut
sub IP2Name{

	my($family, $socktype, $proto, $saddr, $canonname) = getaddrinfo( $_[0], 0 );
	my($name, $port) = getnameinfo($saddr);

	return $name if $name ne $_[0] and $name !~ /:/;

#use Socket qw(AF_INET6 inet_ntop inet_pton getaddrinfo getnameinfo); TODO use when  getaddrinfo is exported in most Socket version (Perl > 5.10)?
#	my ( $err, @addrs ) = getaddrinfo( $_[0], 0 );
#	if($err){
#		&misc::Prt("IP2N:$_[0] -> $err\n");
#	}else{
#		( $err, $name ) = getnameinfo( $addrs[0]->{addr},0 );
#		if($err){
#			&misc::Prt("IP2N:$_[0] -> $err\n");
#		}elsif( $name ne $_[0] and $name !~ /:/ ){
#			return $name;
#		}
#	}
}

=head2 FUNCTION Mask2Bit()

Converts IP mask to # of bits.

B<Options> IP address

B<Globals> -

B<Returns> bitcount

=cut
sub Mask2Bit{
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
sub DecFix{

	if($_[0] >= 1000000000){
		return int($_[0]/1000000000)."G";
	}elsif($_[0] >= 1000000){
		return int($_[0]/1000000)."M";
	}elsif($_[0] >= 1000){
		return int($_[0]/1000)."k";
	}else{
		return $_[0];
	}
}

=head2 FUNCTION NodeMetric()

Return Node's metric letter:

A-F	100G - <10M speed on FD nodes
G-L	100G - <10M speed on HD nodes
M-Z	SNR of wlan nodes (3db steps)

B<Options> SNR, or speed & duplex

B<Globals> -

B<Returns> letter

=cut
sub NodeMetric{

	my ($s,$d) = @_;

	if($s < 100){											# It's SNR
		$s = 0 if $s < 0;									# Negative SNR even exist (DD-WRT)? Just make them 0
		return  ($s < 52)?chr( 90 - int($s/4) ):'M';						# SNR >= 50 (if ever found) is the Max
	}else{
		my $off = ($d eq 'FD')?76:82;
		$s = 1000000 if($s < 10000000);
		return chr( $off - log($s)/log(10) );
	}
}


=head2 FUNCTION NagPipe()

Pipe NeDi events into Nagios

B<Options> string of values

B<Globals> -

B<Returns> -

=cut
sub NagPipe{

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

=head2 FUNCTION Diff()

Find differences in to arrays.

B<Options> pointer to config arrays

B<Globals> -

B<Returns> differences as string

=cut
sub Diff{

	use Algorithm::Diff qw(diff);

	my $chg = '';
	my $row = 1000;
	my $accts_split_a = SplitArray(0,$row,@{$_[0]});						# tx dcec
	my $accts_split_b = SplitArray(0,$row,@{$_[1]});
	my $i = 0;
	foreach (@$accts_split_a){
		my $diffs = diff(@$accts_split_a[$i], @$accts_split_b[$i]);
		return '' unless @$diffs;

		foreach my $chunk (@$diffs) {
			foreach my $line (@$chunk) {
				my ($sign, $lineno, $l) = @$line;
				if( $l !~ /\#time:|ntp clock-period/){					# Ignore ever changing lines
					$chg .= sprintf "%4d$sign %s\n", $lineno+1+($row*$i), $l;
				}
			}
		}
		$i++
	}
	return $chg;
}

=head2 FUNCTION SplitArray()

Split Array in more SubArrays (tx dcec)

B<Options> pointer to use large arrays

B<Globals> -

B<Returns> pointer arrayjunks

=cut
sub SplitArray {

	my ($start, $length, @array) = @_;
	my @array_split;
	my $count =  @array / $length;
	for (my $i=0; $i <= $count; $i++){
		my $end = ($i == 9) ? $#array : $start + $length - 1;
		@{$array_split[$i]} = grep defined,@array[$start .. $end];
		$start += $length;
	}
	return \@array_split;
}

=head2 FUNCTION GetGw()

Get the default gateway of your system (should work on *nix and win).

B<Options> -

B<Globals> -

B<Returns> default gw IP

=cut
sub GetGw{

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

				
=head2 FUNCTION CheckTodo()

Add/remove entry to/from todolist

B<Options> -

B<Globals> misc::seedini, misc::doip, misc::todo

B<Returns> # of seeds queued

=cut
sub CheckTodo{

	my ($id,$tgt,$rc,$rv,$lo,$co) = @_;

	$tgt = $id if !defined $tgt;
	if($tgt =~ /^!/){
		my $del = substr($tgt,1);
		my $i = 0;
		$i++ until $todo[$i] eq $del;						# More efficient than grep...
		my $ndel = splice(@todo, $i, 1);
		delete $seedini{$del};
		delete $doip{$del};
		&Prt("TODO:Removing $del\t($id)\n");
		return -$ndel;
	}else{
		my $hexip = ($tgt)?gethostbyname($tgt):gethostbyname($id);		# Resolve $tgt (fallback to $id). If IP is given this should not create DNS query...
		if(defined $hexip){
			my $ip = join('.',unpack('C4',$hexip) );
			$seedini{$ip}{rc} = ($rc)?$rc:'';
			$seedini{$ip}{rv} = ($rv)?$rv:0;
			$seedini{$ip}{lo} = $lo if $lo;					# Agents only
			$seedini{$ip}{co} = $co if $co;					# Agents only
			$doip{$id} = $ip;
			push(@todo,$id);
			&Prt("TODO:Adding $ip\t($id)\t".(($seedini{$ip}{rc})?"C:$seedini{$ip}{rc}":"").(($seedini{$ip}{rv} != -1)?", V:$seedini{$ip}{rv}":"")."\n");
			return 1;
		}else{
			&Prt("ERR :Resolving $id!\n");
			return 0;
		}
	}
}

=head2 FUNCTION InitSeeds()

Queue devices to discover based on the seedlist.

B<Options> -

B<Globals> misc::doip, misc::todo

B<Returns> # of seeds queued

=cut
sub InitSeeds{

	my $s = 0;

	$seedlist = ($_[0])?"$nedipath/agentlist":"$nedipath/seedlist";

	@todo = ();
	%doip = ();
	
	if($main::opt{'u'}){
		$seedlist = "$main::opt{'u'}";
	}
	my $src = substr($seedlist, rindex($seedlist, '/')+1,5 );

	if($main::opt{'a'}){
		$seedlist = "-a $main::opt{'a'}";
		if( $main::opt{'a'} =~ /[a-zA-Z]+/ ){
			$s += CheckTodo($main::opt{'a'});
		}else{
			my @r = split(/\./,$main::opt{'a'});
			die "Incomplete Address, use 1-x for ranges" unless $r[3];
			foreach my $ipa ( ExpandRange($r[0]) ){
				foreach my $ipb ( ExpandRange($r[1]) ){
					foreach my $ipc ( ExpandRange($r[2]) ){
						foreach my $ipd ( ExpandRange($r[3]) ){
							$s += CheckTodo("$ipa.$ipb.$ipc.$ipd");
						}
					}
				}
			}
		}
	}elsif($main::opt{'A'}){
		$seedlist = "-A $main::opt{'A'}";
		&db::ReadDev($main::opt{'A'});
		foreach my $dv (keys %main::dev){
			if($main::dev{$dv}{rv}){
				$s += CheckTodo($dv,$main::dev{$dv}{ip},$main::dev{$dv}{rc},$main::dev{$dv}{rv});
			}
		}
		%main::dev = ();
	}elsif(-e "$seedlist"){
		&Prt("SEED:Using $seedlist\n");
		open  (LIST, "$seedlist");
		my @list = <LIST>;
		close(LIST);
		foreach my $l (@list){
			if($l !~ /^[#;]|^$/){
				$l =~ s/[\r\n]//g;
				my @f = split(/\s+/,$l);
				my @r = split(/\./,$f[0]);
				foreach my $ipa ( ExpandRange($r[0]) ){
					foreach my $ipb ( ExpandRange($r[1]) ){
						foreach my $ipc ( ExpandRange($r[2]) ){
							foreach my $ipd ( ExpandRange($r[3]) ){
								$s += CheckTodo("$ipa.$ipb.$ipc.$ipd","$ipa.$ipb.$ipc.$ipd",$f[1],$f[2],$f[3],$f[4]);
							}
						}
					}
				}
			}
		}
	}else{
		&Prt("SEED:$seedlist not found!\n");
	}
	if($arpwatch and $main::opt{'o'}) {
		$seedlist .= " arpwatch";
		$s = &misc::ArpWatch(1);
	}
	if(!$s and !$main::opt{'A'}) {									# Fall back to GW if no seeds were found, except if -A had no results
		$s += CheckTodo('default GW', GetGw() );
	}
	return $s;
}

sub ExpandRange{

	my @ip = ();

	if($_[0] =~ /,/){
		foreach my $d (split(/,/,$_[0])){
                        push @ip, ExpandRange($d);							# Recursion allows for multiple ranges separated by ,
		}
	}elsif($_[0] =~ /-/){
		my @r = split(/-/,$_[0]);
		for my $d ($r[0]..$r[1]){
			push @ip,$d;
		}
	}else{
		push @ip,$_[0];
	}

	return @ip;
}

=head2 FUNCTION Discover()

Discover a single device.

B<Options> device ID

B<Globals> misc::curcfg

B<Returns> -

=cut
sub Discover{

	my ($id)	= @_;
	my $start	= time;
	my $clistat	= 'Init';									# CLI access status
	my $dv		= '';
	my $skip	= $main::opt{'S'};

	$doid = 1;
	if($main::opt{'P'}){										# Ping requested
		my $latency = &mon::PingService($doip{$id},'',0,$main::opt{'P'});
		if($main::opt{'t'} eq 'p'){
			&misc::Prt('',"$doip{$id} TCP-Ping:".(($latency eq -1)?"---   \t":"${latency}ms   \t") );
			$doid = 0;
		}elsif($latency eq -1){									# No response, not ok to indentify
			$doid = 0;
			&Prt('',"-$doip{$id}\t");
		}elsif($skip =~ /s/ and $misc::seedini{$doip{$id}}{dv}){				# Skip system, create dv from DB if available...
			$dv = $misc::seedini{$doip{$id}}{na};
			&ReadSysobj($main::dev{$dv}{so});
			$doid = 0;
			&Prt('',"s$doip{$id}\t$dv\t");
		}else{
			&Prt('','+');
		}
	}
	$dv  = &snmp::Identify($id,$skip) if $doid;							# ...identify device otherwhise
	if($dv){											# Success?
		if(exists $skippol{$main::dev{$dv}{ty}}){
			$skip .= $skippol{$main::dev{$dv}{ty}};
			&Prt("DISC:skippol policy for $main::dev{$dv}{ty}=$skippol{$main::dev{$dv}{ty}}\n");
		}elsif(exists $skippol{'default'}){
			$skip .= &Strip($skippol{'default'});
			&Prt("DISC:default skip policy=$skip\n");
		}elsif($skip){
			&Prt("DISC:no skip policy using -S $skip\n");
		}
		my $noentrinf = &snmp::Enterprise($dv,$skip);						# Get enterprise info
		&db::ReadLink("device = ".$db::dbh->quote($dv)." AND linktype = 'STA'");		# Get static links of device
		my $iferr = &snmp::Interfaces($dv,$skip);						# Get interface info

		&DevRRD($dv,$skip) if(!$iferr and $rrdcmd and $skip !~ /g/);

		&AvAccess($dv) if $skip !~ /[ao]/;

		&snmp::IfAddresses($dv) if $sysobj{$main::dev{$dv}{so}}{ia} and $skip !~ /j/;		# Get IP addresses
		if($main::dev{$dv}{pip} and $main::dev{$dv}{pip} ne $main::dev{$dv}{ip}){		# Previous IP was different...
			$mq += &mon::Event('I',150,'nedj',$dv,$dv,"IP changed from $main::dev{$dv}{pip} to $main::dev{$dv}{ip} (update monitoring)");
		}
		if($sysobj{$main::dev{$dv}{so}}{dp} and $skip !~ /p/){
			&snmp::DisProtocol($dv,$id,$sysobj{$main::dev{$dv}{so}}{dp});			# Get neighbours via LLDP, CDP or FDP
		}

		my $moderr = 0;
		if($sysobj{$main::dev{$dv}{so}}{md}){
			if($skip =~ /m/){
				&Prt(""," ");
			}else{
				$moderr = &snmp::Modules($dv);
			}
		}else{
			$main::dev{$dv}{stk} = 0;
			&Prt(""," ");
		}

		&KeyScan($main::dev{$dv}{ip}) if $main::opt{'k'} or $main::opt{'K'};
		if( $sysobj{$main::dev{$dv}{so}}{ar} and $skip !~ /A/ ){				# Map IP to MAC addresses, if ARP/ND is in .def
			$clistat = &cli::PrepDev($dv,'arp');						# Prepare device for cli access
			if($clistat =~ /^OK/){
				$clistat = &cli::ArpND($dv);
			}else{
				&snmp::ArpND($dv);
			}
		}else{
			&Prt("","      ");								# Spacer instead of L3 info.
		}

		if($main::dev{$dv}{sv} & 4 and $main::opt{'r'}){					# User route discovery on L3 devs, if -r
			&snmp::Routes($dv);
		}else{
			&Prt(""," ");
		}

		if($sysobj{$main::dev{$dv}{so}}{bf} eq "Aruba"){					# Discover Wlan devices
			&snmp::ArubaAP($dv,$skip);
		}elsif($sysobj{$main::dev{$dv}{so}}{bf} eq "MSM"){
			&snmp::MSMAP($dv,$skip);
		}elsif($sysobj{$main::dev{$dv}{so}}{bf} =~ /WLC/){					# Cisco switches with integrated WLC as detected by SNMP::Interfaces()
			&snmp::WLCAP($dv,$skip);
		}
		if($sysobj{$main::dev{$dv}{so}}{bf} eq "CAP" and  $skip !~ /F/){
			&snmp::CAPFwd($dv);
		}elsif($sysobj{$main::dev{$dv}{so}}{bf} eq "DDWRT" and  $skip !~ /F/){
			&snmp::DDWRTFwd($dv);
		}elsif($sysobj{$main::dev{$dv}{so}}{bf} =~ /normal|qbri|VLX|VXP/ and  $skip !~ /F/){	# Get mac address table, if  bridging is set in .def
			if($getfwd =~ /dyn|sec/){							# Using CLI to fetch forwarding table is configured?
				$clistat = &cli::PrepDev($dv,'fwd');					# Prepare device for cli access
				if($clistat =~ /^OK/){
					$clistat = &cli::BridgeFwd($dv);
				}
			}
			if($clistat ne "OK-Bridge"){
				$mq += &mon::Event('C',150,'nede',$dv,$dv,"CLI Bridge Fwd error: $clistat") unless $clistat eq 'not implemented';
				if($sysobj{$main::dev{$dv}{so}}{bf} =~ /^V(LX|XP)$/ and  $skip =~ /v/){
					&Prt("ERR :Cannot get Vlan indexed forwarding entries with skipping v!\n");
				}else{
					&snmp::BridgeFwd($dv);						# Do SNMP if telnet fails or CLI not configured
				}
			}			
		}

		if($main::opt{'b'} or defined $main::opt{'B'}){						# Backup configurations
			if($skip =~ /s/ or !$main::dev{$dv}{pls} or $main::dev{$dv}{bup} ne 'A'){	# Skip sysinfo or new devs force backup (or non-active are updated)
				if($clistat =~ /^OK-/){							# Wait if we just got BridgeFWD or ARP via CLI to avoid hang
					&Prt("DISC:Cli waiting $cli::clipause seconds before reconnecting\n");
					select(undef, undef, undef, $cli::clipause);
				}else{
					$clistat = &cli::PrepDev($dv,'cmd');
				}
				&Prt("DISC:Cli config = $clistat\n");
				if($clistat =~ /^OK/){
					@curcfg = ();							# Empty config (global due to efficiency)
					$clistat = &cli::Config($dv);
					&Prt("\nConfigbackup ------------------------------------------------------------------\n");
					&db::BackupCfg($dv);
					if( $main::dev{$dv}{cfc} ){
						$main::dev{$dv}{bup} = 'A';
					}else{
						$main::dev{$dv}{bup} = 'U';
					}
				}elsif($clistat =~ /^not implemented/){
					$main::dev{$dv}{bup} = '-';
				}else{
					$mq += &mon::Event('B',150,'cfge',$dv,$dv,"Config backup error: $clistat");
					$main::dev{$dv}{bup} = 'E';
				}
			}else{
				&Prt("DISC:Config hasn't been changed. Not backing up.\n");
			}
		}
		print $misc::guiauth;
		if( $main::opt{'c'} ){									# Run CLI commands
			if($clistat =~ /^OK-/){
				&Prt("DISC:Cli waiting $cli::clipause seconds before reconnecting\n");
				select(undef, undef, undef, $cli::clipause);
			}else{
				$clistat = &cli::PrepDev($dv,"cfg");
			}
			&Prt("DISC:Cli cmd = $clistat\n");
			if($clistat =~ /^OK/){
				$clistat = &cli::Commands($dv, $main::dev{$dv}{ip}, $main::dev{$dv}{cp}, $main::dev{$dv}{us}, $pw, $main::dev{$dv}{os}, $main::opt{'c'});
			}
			if($clistat !~ /^OK-/){
				$mq += &mon::Event('C',150,'cfge',$dv,$dv,"Cli cmd error: $clistat");
			}
		}
		push (@doneid,$id);
		push (@doneip,$doip{$id});
		push (@donenam, $dv);
		unless($main::opt{'t'}){
			&Prt("\nWrite Device Info -------------------------------------------------------------\n");
			&db::WriteDev($dv);
			&db::WriteInt($dv,$skip)	unless $iferr;
			&db::WriteMod($dv)		unless $skip =~ /m/ or $moderr;
			&db::WriteVlan($dv) 		unless $skip =~ /v/;
			&db::WriteNet($dv)  		unless $skip =~ /j/;
			&db::Commit();
		}
		delete $main::mod{$dv};
		delete $main::vlan{$dv};
		delete $main::int{$dv};
		delete $main::net{$dv};
		#TODO needed for MAC&Half-links? delete $main::links{$dv};
	}else{
		push (@failid,$id);
		push (@failip,$doip{$id});
	}
	my @t = localtime;
	my $s = sprintf ("%4d/%d-%ds",scalar(@todo),scalar(@donenam),(time - $start) );
	$s .= sprintf ("\t%02d:%02d:%02d",$t[2],$t[1],$t[0] ) if $notify =~ /x/;
	&Prt("DISC:ToDo/Done-Time\t\t\t\t\t$s\n"," $s\n");
}


=head2 FUNCTION ArpWatch()

Build arp table from Arpwatch files (if set in nedi.conf).
First loop picks latest entry, second builds proper arp hash.

B<Options> -

B<Globals> misc::arp, misc::arpn, misc::arpc

B<Returns> -

=cut
sub ArpWatch{

	return unless defined $arpwatch;

	my $nad = 0;
	my %amc = ();
	my %arp = ();
	my @awf = glob($arpwatch);
	chomp @awf;

	&Prt("\nArpWatch     ------------------------------------------------------------------\n");
	foreach my $f (@awf){
		&Prt("\nARPW:Reading $f\n");
		open  ("ARPDAT", $f ) or die "ARP:$f not found!";					# read arp.dat
		my @adat = <ARPDAT>;
		close("ARPDAT");
		foreach my $l (@adat){
			$l =~ s/[\r\n]//g;
			my @ad = split(/\s/,$l);
			my $mc = sprintf "%02s%02s%02s%02s%02s%02s",split(/:/,$ad[0]);
			if(!exists $amc{$mc} or $ad[2] > $amc{$mc}{'time'}){
				$amc{$mc}{'time'} = $ad[2];
				&Prt("ARPW:$mc ".localtime($ad[2]) );
				if($_[0]){
					my $oui = &GetOui($mc);
					&Prt(" $oui ");
					if($mc =~ /$misc::border/ or $oui =~ /$misc::border/){
						&misc::Prt(" matches border /$misc::border/\n");
						$bd++;
					}elsif($oui =~ /$misc::ouidev/i or $mc =~ /$misc::ouidev/){
						&Prt(" matches ouidev /$misc::ouidev/\n");
						$nad += CheckTodo($mc,$ad[1]);
					}
				}else{
					$amc{$mc}{'ip'} = $ad[1];
					$amc{$mc}{'name'} = ($ad[3] and $main::opt{'N'} !~ /-iponly$/)?$ad[3]:'';
					&Prt(" $amc{$mc}{'ip'}\t$amc{$mc}{'name'}\tOK\n");
				}
			}
		}
	}
	foreach my $mc( keys %amc ){
		$arp{''}{$mc}{''}{$amc{$mc}{'ip'}} = $amc{$mc}{'time'};
		if($amc{$mc}{'name'} and $main::opt{'N'} !~ /-iponly$/){&db::WriteDNS($amc{$mc}{'ip'},0,$amc{$mc}{'name'},$amc{$mc}{'time'})}
		$nad++;
	}
	&Prt("ARPW:$nad arpwatch entries used.\n");
	&db::WriteArpND('',\%arp);

	return $nad;
}

=head2 FUNCTION FloodFind()

Detect potential Switch flooders, based on population.

B<Options> device

B<Globals> -

B<Returns> - (generates events)

=cut
sub FloodFind{

	my ($dv) = @_;
	my $nfld = 0;

	&Prt("\nFloodFind    ------------------------------------------------------------------\n");
	foreach my $if( keys %{$portprop{$dv}} ){
		my $mf = ($main::int{$dv}{$portprop{$dv}{$if}{idx}}{mcf})?$main::int{$dv}{$portprop{$dv}{$if}{idx}}{mcf}:$macflood;
		if( $portprop{$dv}{$if}{pop} > $mf and $mf ){
			$mq += &mon::Event('N',150,'secf',$dv,$dv,"$portprop{$dv}{$if}{pop} MAC entries exceed threshold of $mf on $dv,$if");
			$nfld++;
		}
	}
	&Prt("FLOD:$nfld IFs triggered a MACflood alert\n");
}

=head2 FUNCTION DevRRD()

Creates system and IF RRDs if necessary and then updates them.

B<Options> device name

B<Globals> -

B<Returns> -

=cut
sub DevRRD{

	my ($na,$skip) = @_;
	my $err = 0;
	my $dok = 1;
	my $dv  = $na;
	$dv     =~ s/([^a-zA-Z0-9_.-])/"%" . uc(sprintf("%2.2x",ord($1)))/eg;
	my $rra = '-';
	my $typ = 'GAUGE';
	if($main::dev{$na}{cul}){
		($rra, $typ) = split(/;/, $main::dev{$na}{cul});
		$rra =~ s/[^-a-zA-Z0-9]//g;
		$typ = ($typ eq "C")?"COUNTER":"GAUGE";
	}
	&Prt("\nDevRRD       ------------------------------------------------------------------\n");
	$dok = mkdir ("$nedipath/rrd/$dv", 0755) unless -e "$nedipath/rrd/$dv";
	if($dok){
		unless($main::opt{'t'}){
			unless(-e "$nedipath/rrd/$dv/system.rrd"){
				my $ds = 2 * $rrdstep;
				RRDs::create("$nedipath/rrd/$dv/system.rrd","-s","$rrdstep",
						"DS:cpu:GAUGE:$ds:0:100",
						"DS:memcpu:GAUGE:$ds:0:U",
						"DS:".lc($rra).":$typ:$ds:0:U",
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
					&Prt("ERR :RRD $nedipath/rrd/$dv/system.rrd $err\n","Ru");
				}else{
					&Prt("DRRD:Updated $nedipath/rrd/$dv/system.rrd\n");
				}
			}
		}
		&Prt("DRRD:CPU=$main::dev{$na}{cpu} MEM=$main::dev{$na}{mcp} TEMP=$main::dev{$na}{tmp} CUS=$main::dev{$na}{cuv}\n");

		return if $skip =~ /t/ and $skip =~ /e/ and $skip =~ /d/ and $skip =~ /b/ and $skip =~ /a/ and $skip =~ /o/;
		$err = 0;

		&Prt("DRRD:IFName        Inoct     Outoct  Inerr Outerr   Indis  Outdis Inbcst Stat\n");
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
				&Prt(sprintf ("DRRD:%-8.8s %10.10s %10.10s %6.6s %6.6s %7.7s %7.7s %6.6s %4.4s\n", $irf,$main::int{$na}{$i}{ioc},$main::int{$na}{$i}{ooc},$main::int{$na}{$i}{ier},$main::int{$na}{$i}{oer},$main::int{$na}{$i}{idi},$main::int{$na}{$i}{odi},$main::int{$na}{$i}{ibr},$main::int{$na}{$i}{sta}) );
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
sub TopRRD{

	my (%ec, %ifs);
	my $err = "";
	my $mok = my $msl = my $mal = 0;
	$ec{'50'} = $ec{'100'} = $ec{'150'} = $ec{'200'} = $ec{'250'} = 0;
	$ifs{'0'} = $ifs{'1'} = $ifs{'3'} = 0;
	# Access traffic using delta octets to avoid error from missing or rebooted switches. Needs to be divided by 1M*rrdstep to get MB/s
	my $tat = &db::Select('interfaces','',"round(sum(dinoct)/(1000000*$rrdstep),3),round(sum(doutoct)/(1000000*$rrdstep),3)","linktype = '' AND lastdis > $main::now - $rrdstep",'devices','device');

	# Wired interface (type not 71) errors/s
	my $twe = &db::Select('interfaces','',"round(sum(dinerr)/$rrdstep,3),round(sum(douterr)/$rrdstep,3),round(sum(dindis)/$rrdstep,3),round(sum(doutdis)/$rrdstep,3)","iftype != 71 AND lastdis > $main::now - $rrdstep",'devices','device');

	# Total nodes lastseen
	my $nodl = &db::Select('nodes','',"count(lastseen)","lastseen = $main::now");

	# Total nodes firstseen
	my $nodf = &db::Select('nodes','',"count(firstseen)","firstseen = $main::now");

	# Total power in Watts
	my $pwr = &db::Select('devices','',"sum(totpoe)","lastdis > $main::now - $rrdstep");

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
			&db::Insert('events','level,time,source,info,class',"150,$main::now,'NeDi','$msg','mons'");
			&Prt("TRRD:$msg\n");
		}
	}else{
		my $msg = "No successful check at all, is moni running?";
		$msg = "Last successful check on ".localtime($lck).", is moni running?" if $lck;
		&db::Insert('events','level,time,source,info,class',"150,$main::now,'NeDi','$msg','mons'");
		&Prt("TRRD:$msg\n");
	}

	# Number of cathegorized events during discovery cycle
	my $dbec = &db::Select('events','level',"level,count(*) as c","time > ".(time - $rrdstep)." GROUP BY level");
	foreach my $k (keys %$dbec ) {
		$ec{$k} = $dbec->{$k}{'c'} if $dbec->{$k}{'c'};
	}

	&Prt("TRRD:Trf=$tat->[0][0]/$tat->[0][1] Err=$twe->[0][0]/$twe->[0][1] Dis=$twe->[0][2]/$twe->[0][3]\n");
	&Prt("TRRD:Up/Dn/Dis=$ifs{'3'}/$ifs{'1'}/$ifs{'0'} Pwr=${pwr}W Nod=$nodl/$nodf Mon=$mok/$msl/$mal Event=$ec{'50'}/$ec{'100'}/$ec{'150'}/$ec{'200'}/$ec{'250'}\n");
	if($main::opt{'t'} or $main::opt{'a'}){
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
sub WriteCfg{

	use POSIX qw(strftime);

	my ($dv) = @_;
	$dv     =~ s/([^a-zA-Z0-9_.-])/"%" . uc(sprintf("%2.2x",ord($1)))/eg;
	my $ok = 1;
	unless(-e "$nedipath/conf/$dv"){
		&Prt("WCFF:Creating $nedipath/conf/$dv\n");
		$ok = mkdir ("$nedipath/conf/$dv", 0755);
	}
	my $wcf = "$nedipath/conf/$dv/".strftime ("%Y-%m%d-%H%M.cfg", localtime($main::now) );
	if($ok and open (CF, ">$wcf" ) ){
		foreach ( @curcfg ){ print CF "$_\n" }
		close (CF);
		&Prt("WCFF:Config written to $wcf\n");

		if($main::opt{'B'}){									# if >0 only keep that many, based on raider82's idea
			my @cfiles = sort {$b cmp $a} glob("$nedipath/conf/$dv/*.cfg");
			my $cur = 0;
			foreach my $cf (@cfiles) {
				$cur++;
				if($cur > $main::opt{'B'}){
					$dres = unlink ("$cf");
					if($dres){
						&Prt("WCFF:Deleted $cf\n");
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
sub Daemonize{

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
	my $ifip = retrieve("$main::p/ifip.db");
	%ifip = %$ifip;

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
	store \%ifip, "$main::p/ifip.db";

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

	if($main::opt{'K'}){										# Delete stored key, based on raider82's idea
		my $res = `ssh-keygen -R $_[0] -f ~/.ssh/known_hosts`;
		&Prt("DISC:Cli: key removed for $_[0]\n","Kr");
	}

	if($main::opt{'k'}){										# Scan key (tx jug)
		my $res = `ssh-keyscan $_[0] 2>&1 >> ~/.ssh/known_hosts`;
		if( $res =~ m/^$|no hostkey alg/ ){
			&Prt("ERR :ssh-keyscan rsa failed, trying dsa\n");
			$res = `ssh-keyscan -t dsa $_[0] 2>&1 >> ~/.ssh/known_hosts`;
			if( $res =~ m/^$|no hostkey alg/ ){
				&Prt("ERR :ssh-keyscan dsa failed, trying rsa1 as last resort\n");
				$res = `ssh-keyscan -t rsa1 $_[0] 2>&1 >> ~/.ssh/known_hosts`;
				if( $res =~ m/^$|no hostkey alg/ ){
					&Prt("ERR :ssh-keyscan for $_[0] failed\n","Ke");
				} else {
					chomp($res);
					&Prt("KEY :$res (RSA1) added to ~/.ssh/known_hosts\n","Ks");
				}
			} else {
				chomp($res);
				&Prt("KEY :$res (DSA) added to ~/.ssh/known_hosts\n","Ks");
			}
		}else{
			chomp($res);
			&Prt("KEY :$res (RSA) added to ~/.ssh/known_hosts\n","Ks");
		}
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

=head2 FUNCTION ValidIP()

Check whether IP is usable

B<Options> IP

B<Globals> -

B<Returns> Bool

=cut
sub ValidIP{
	if(!defined $_[0] or $_[0] =~ /^[0]?$|^127\.0\.0\.|^0\.0\.0\.0$|^255\.255\.255\.255$/){
		return 0;
	}else{
		return 1;
	}
}

=head2 FUNCTION ValidMAC()

Check whether MAC usable 

B<Options> IP

B<Globals> -

B<Returns> Bool

=cut
sub ValidMAC{
	if( length($_[0]) == 12 and $_[0] !~ /$ignoredmacs/){
		return 1;
	}else{
		return 0;
	}
}


1;
