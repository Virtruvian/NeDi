=pod

=head1 LIBRARY
libsnmp.pm

SNMP based Functions

=head2 AUTHORS

Remo Rickli & NeDi Community

=cut

package snmp;
use warnings;

use Net::SNMP;


=head2 FUNCTION Connect()

Establish SNMP session.

maxmessagesize: Nexus needs 5500 according to whitehatguy, but foundry turboiron fragments FDP OIDs and responses are ignored from a extreme x450 stack, so it'll only be changed on demand!

B<Options> IP address, version, community, timeout, [maxmessagesize]

B<Globals> -

B<Returns> session, error

=cut
sub Connect {

	my ($ip, $ver, $comm, $tout, $maxms) = @_;

	$tout  = ($tout)?$tout:$misc::timeout;
	$maxms = ($maxms)? $maxms:1472;

	if ($ver == 3) {
		if($misc::comms{$comm}{pprot}){
			($session, $error) = Net::SNMP->session(-hostname	=> $ip,
								-version	=> $ver,
								-timeout	=> $tout,
								-username	=> $comm,
								-authpassword	=> $misc::comms{$comm}{apass},
								-authprotocol	=> $misc::comms{$comm}{aprot},
								-privpassword	=> $misc::comms{$comm}{ppass},
								-privprotocol	=> $misc::comms{$comm}{pprot},
								-maxmsgsize	=> $maxms,
								-translate => [-timeticks => 0, -octetstring => 0]
								);
		}else{
			($session, $error) = Net::SNMP->session(-hostname	=> $ip,
								-version	=> $ver,
								-timeout	=> $tout,
								-username	=> $comm,
								-authpassword	=> $misc::comms{$comm}{apass},
								-authprotocol	=> $misc::comms{$comm}{aprot},
								-maxmsgsize	=> $maxms,
								-translate => [-timeticks => 0, -octetstring => 0]
								);
		}
	}else{
		($session, $error) = Net::SNMP->session(-hostname	=> $ip,
							-version	=> $ver,
							-timeout	=> $tout,
							-community	=> $comm,
							-maxmsgsize	=> $maxms,
							-translate => [-timeticks => 0, -octetstring => 0]
							);
	}

	&misc::Prt("SNMP:Connect $ip $comm v$ver Tout:${tout}s MaxMS:$maxms\n");
	return ($session, $error);
}


=head2 FUNCTION Identify()

Find community and identify device based on sysobj definition

B<Options> IP address

B<Globals> -

B<Returns> name on success, empty string on failure

=cut
sub Identify {

	my ($id) = @_;
	my ($ver, $comm, $wver, $wcomm, $session, $err, $r, $na);
	my $sysO = '1.3.6.1.2.1.1.2.0';
	my $conO = '1.3.6.1.2.1.1.4.0';
	my $namO = '1.3.6.1.2.1.1.5.0';
	my $locO = '1.3.6.1.2.1.1.6.0';
	my $srvO = '1.3.6.1.2.1.1.7.0';
	my $ip	 = $misc::doip{$id};

	&misc::Prt("\nIdentify     ------------------------------------------------------------------\n");

	if($ip =~ /^$|0.0.0.0|^127/){
		if($misc::notify =~ /d/i){
			&db::Insert('events','level,time,source,info,class',"\"100\",\"$main::now\",\"$ip\",\"IP=$ip, not discoverable\",\"nedi\"");
		}

		&misc::Prt("IDNT:$ip is not discoverable\n","Not discoverable\t\t");
		return "";
	}elsif( grep /^\Q$ip}\E$/,(@misc::doneip,@misc::failip) ){
		&misc::Prt("IDNT:$ip done already\n","Done already\t\t");
	}elsif($ip !~ /$misc::netfilter/){
		if($misc::notify =~ /d/i){
			&db::Insert('events','level,time,source,info,class',"\"100\",\"$main::now\",\"$ip\",\"Netfilter $misc::netfilter, not discoverable\",\"nedi\"");
		}
		&misc::Prt("IDNT:$ip doesn't match netfilter $misc::netfilter\n","Netfilter $misc::netfilter\t\t");
		return "";
	}

	my @comms = @misc::comms;									# Build Community list, preferring existing
	unshift(@comms,$misc::snmpini{$ip}{rc}) if $misc::snmpini{$ip}{rc};
	do{
		$comm = shift (@comms);
		if($misc::comms{$comm}{aprot}){								# Force v3, if auth proto is set!
			$ver = 3;
		}elsif($misc::snmpini{$ip}{rv}){							# Set SNMP version, preferring existing
			$ver  = $misc::snmpini{$ip}{rv};
		}else{
			$ver = 2;
		}

		($session, $err) = &Connect($ip,$ver,$comm);
		if(defined $session){
			$r = $session->get_request($namO);						# Get sysobjid to find the right community
			$err	= $session->error;
			if($err and $ver == 2){								# Fall back to version 1 if 2 failed TODO: only with new dev!
				$ver = 1;
				&misc::Prt("ERR :$err\n");
				($session, $err) = &Connect($ip,$ver,$comm);
				if(defined $session){							# Should always be with v1!
					$r   = $session->get_request($namO);				# Get sysobjid to find the right community
					$err = $session->error;
				}
			}
			if($err){
				$session->close if defined $session;
				&misc::Prt("ERR :$err\n");
			}elsif(&misc::Strip($r->{$namO}) eq 'noSuchObject'){				# Some communities are too restrictive (tx, sneuser)
				$session->close if defined $session;
				$err = $r->{$namO};
				&misc::Prt("ERR :Name $err\n");
			}
		}
	}while ($#comms ne "-1" and $err);								# And stop once a community worked or we ran out of them.

	if($err){
		$na = "";
		&misc::Prt("ERR :$err\n","No response from $id, not discoverable\t\t");
		if($misc::notify =~ /d/i){
			&db::Insert('events','level,time,source,info,class',"\"100\",\"$main::now\",\"$ip\",\"No response from $id, not discoverable\",\"nedi\"");
		}
	}else{
		$na = &misc::Strip($r->{$namO});
		if (exists $misc::map{$ip}{na}){
			$na = $misc::map{$ip}{na};
			&misc::Prt("MAPN:Name mapped to $misc::map{$ip}{na}\n","Mn");
		}else{
			if ($na =~ /^\s*$/){								# Catch really bad SNMP implementations
				&misc::Prt("IDNT:No name using IP $ip\n","So");
				$na = $ip;
			}else{
				$na =~ s/^(.*?)\.(.*)/$1/ if !$main::opt{'F'};				# FQDN can mess up links
			}
		}

		&misc::Prt("IDNT:Name = $na\n",sprintf("%-12.12s ",$na) );
		if(grep /^\Q$na\E$/, @misc::donenam){							# Bail, if we've done it already
			&misc::Prt("IDNT:Done already\n","Done already\t");
			return;
		}

		my $so	= "other";
		$r	= $session->get_request($sysO);
		$err	= $session->error;
		if(!$err and defined $r->{$sysO} and length $r->{$sysO} > 10){				# Some vendors think of 1.3. as appropriate!
			$so = &misc::Strip($r->{$sysO});
			if(exists $main::dev{$na} and $main::dev{$na}{so} ne $so and $misc::notify =~ /s/i){
				my $msg = "Sysobjid changed from $main::dev{$na}{so} to $so";
				&db::Insert('events','level,time,source,info,class,device',"\"150\",\"$main::now\",\"$na\",\"$msg\",\"nedi\",\"$na\"");
				$misc::mq += &mon::AlertQ("$na: $msg\n","",1,$na) if $misc::notify =~ /S/;
			}
		}
		&misc::ReadSysobj($so);

		my $desO = ($misc::sysobj{$so}{de})?$misc::sysobj{$so}{de}:'1.3.6.1.2.1.1.1.0';		# Use sysdesc OID from .def, if set
		$r = $session->get_request($desO);
		$err = $session->error;
		my $de = "err";
		if(!$err and defined $r->{$desO}){$de = &misc::Strip($r->{$desO});}
		if ($de =~ /$misc::descfilter/){							# Only define device, if not filtered
			$session->close;
			&db::Insert('events','level,time,source,info,class',"\"100\",\"$main::now\",\"$ip\",\"Descfilter $misc::descfilter, not discoverable\",\"nedi\"") if $misc::notify =~ /d/i;
			&misc::Prt("IDNT:Descfilter $misc::descfilter\n","Descfilter $misc::descfilter\t");
			return;
		}else{
			if(!exists $main::dev{$na} or $main::opt{W}){
				unless(exists $main::dev{$na}){
					my $msg = "New Device with ID $id and IP $ip found";
					&misc::Prt("IDNT:$msg\n");
					&db::Insert('events','level,time,source,info,class,device',"\"100\",\"$main::now\",\"$na\",\"$msg\",\"nedi\",\"$na\"") if $misc::notify =~ /d/i;
					$misc::mq += &mon::AlertQ("$na: $msg\n","",1,$na) if $misc::notify =~ /D/;
					$main::dev{$na}{fs} = $main::now;
					$main::dev{$na}{rv} = $ver;					# Only set SNMP readversion upon 1st or re-discovery to avoid v1 fallback in case of communication problems!
				}
				if($misc::snmpwrite){							# Write access enabled?
					my $woid = '1.3.6.1.2.1.11.30.0';				# Use snmpEnableAuthenTraps to check write access...
					$r  = $session->get_request($woid);				# Use snmpEnableAuthenTraps to check write access...
					$err = $session->error;
					if($err or $r->{$woid} !~ /^\d+$/){
						&misc::Prt("ERR :Writetest $err\n");
					}else{
						my $rvar = $r->{$woid};
						&misc::Prt("IDNT:Testing write access with $woid set to $rvar\n");
						my $wvar = ($rvar == 2)?1:2;
						my @wcomms = @misc::comms;				# Build Community list for write test
						do{
							$wver = 2;
							$wcomm = shift (@wcomms);
							if($misc::comms{$wcomm}{aprot}){
								$wver = 3;
							}
							if($wver >= $misc::snmpwrite){			# Policy compliant?
								my ($wsess, $werr) = &Connect($ip,$wver,$wcomm);
								if(defined $wsess){
									my $wr = $wsess->set_request(-varbindlist => [ $woid, INTEGER, $wvar ]);
									$err = $wsess->error;
									if($err and $err !~ /inconsistentValue/){	# Means it can't be enabled, but community itself works!
										&misc::Prt("ERR :Writetest, $err\n");
									}else{
										my $nvar = ($err =~ /inconsistentValue/)?$wvar:$wr->{$woid};	# So, just set it to what it is already..
										if($nvar eq $wvar){
											&misc::Prt("IDNT:Writetest set to $nvar OK\n");
											$wr = $wsess->set_request(-varbindlist => [ $woid, INTEGER, $rvar ]);
											$err = $wsess->error;
											if($err){
												$err = $wsess->error();
												&misc::Prt("ERR :Writetest, $err\n");
											}else{
												my $nvar = $wr->{$woid};
												if($nvar eq $rvar){
													$main::dev{$na}{wc} = $wcomm;
													$main::dev{$na}{wv} = $wver;
													&misc::Prt("IDNT:Writetest restore $nvar, using $wcomm v$wver\n");
												}else{
													$err = "restore $rvar failed (is $nvar)";
													@wcomms = ();
													$wcomm = '-';
													&misc::Prt("ERR :Writetest $err\n");
												}
											}
										}else{
											$err = "write $rvar failed (is $nvar)";
											@wcomms = ();
											$wcomm = '-';
											&misc::Prt("ERR :Writetest $err\n");
										}
									}
								}else{
									&misc::Prt("ERR :Failed to create session\n");
								}
								$wsess->close if defined $wsess;
							}else{
								$err = "$wcomm v$wver conflicts with snmpwrite policy of v$misc::snmpwrite";
								&misc::Prt("ERR :$err\n");
							}
						}while ($#wcomms ne "-1" and $err);			# And stop once a community worked or we ran out of them.
					}
				}else{
					&misc::Prt("IDNT:SNMP write policy not enabled\n");
				}
			}
			$main::dev{$na}{ls} = $main::now;
			$main::dev{$na}{so} = $so;
			$main::dev{$na}{ty} = $misc::sysobj{$so}{ty};
			$main::dev{$na}{ip} = $ip;
			$main::dev{$na}{oi} = $ip;
			$main::dev{$na}{rc} = $comm;
			$main::dev{$na}{de} = $de;
			$main::dev{$na}{os} = $misc::sysobj{$so}{os};
			$main::dev{$na}{ic} = $misc::sysobj{$so}{ic};
			$main::dev{$na}{hc} = $misc::sysobj{$so}{hc};
			$main::dev{$na}{cul}= "$misc::sysobj{$so}{cul};$misc::sysobj{$so}{mmu}";

			$r = $session->get_request($conO);
			$err = $session->error;
			if(!$err){$main::dev{$na}{co} = &misc::Strip($r->{$conO})}
			&misc::Prt("SYS :Con=$main::dev{$na}{co} ");

			if (exists $misc::map{$ip}{lo}){
				$main::dev{$na}{lo} = $misc::map{$ip}{lo};
				&misc::Prt("MapLo=$main::dev{$na}{lo}\n","Ml");
			}else{
				$r = $session->get_request($locO);
				$err = $session->error;
				if(!$err){$main::dev{$na}{lo} = &misc::Strip($r->{$locO})}
				$main::dev{$na}{lo} =~ s/^$/-$misc::locsep-$misc::locsep-/;
				&misc::Prt("Loc=$main::dev{$na}{lo}\n");
			}

			$r = $session->get_request($srvO);
			$err = $session->error;
			if($err or $r->{$srvO} !~ /^\d+$/){
				&misc::Prt("ERR :SysServices $err\n");
				$main::dev{$na}{sv} = 6; 						# Could be a buggy SNMP implementation, so we set this to 6 and check the device anyway
			}else{
				$main::dev{$na}{sv} = &misc::Strip($r->{$srvO});
			}
			&misc::Prt("SYS :OS=$main::dev{$na}{os} SV=$main::dev{$na}{sv} TY=$main::dev{$na}{ty}\n");
		}
		$session->close;
	}
	return $na;
}


=head2 FUNCTION Enterprise()

Get enterprise specific information using sysobj.def file

B<Options> device name

B<Globals> main::dev

B<Returns> -

=cut
sub Enterprise {

	my ($na,$skip) = @_;
	my ($session, $err, $r);
	my $nv = 0;
	my $so = $main::dev{$na}{so};

	&misc::Prt("\nEnterprise   ------------------------------------------------------------------\n");
	return if $skip =~ /s/ and $skip =~ /g/;

	($session, $err) = &Connect($main::dev{$na}{ip}, $main::dev{$na}{rv}, $main::dev{$na}{rc});
	return unless defined $session;

	if($skip !~ /s/){
		if($misc::sysobj{$so}{sn}){
			my $trans = $session->translate();
			$session->translate(1);								# Needed for some devs returning HEX-SNs
			$r  = $session->get_request($misc::sysobj{$so}{sn});
			$err = $session->error;
			$session->translate($trans);
			if ($err){
				$main::dev{$na}{sn} = "err";
				&misc::Prt("ERR :$err\n","Sn");
			}else{
				my $sn = substr(&misc::Strip($r->{$misc::sysobj{$so}{sn}}),0,31);
				$sn = 'err' if $sn eq "noSuchObject";
				if($main::dev{$na}{sn} and $main::dev{$na}{sn} ne $sn and $misc::notify =~ /s/i){
					my $msg = "Serial number changed from $main::dev{$na}{sn} to $sn";
					&db::Insert('events','level,time,source,info,class,device',"\"150\",\"$main::now\",\"$na\",\"$msg\",\"nedi\",\"$na\"");
					$misc::mq += &mon::AlertQ("$na: $msg\n","",1,$na) if $misc::notify =~ /S/;
				}
				$main::dev{$na}{sn} = $sn;
				&misc::Prt("SERN:Serial number = $sn\n");
			}
		}else{
			$main::dev{$na}{sn} = "-";
		}

		if($misc::sysobj{$so}{bi}){
			$r  = $session->get_request($misc::sysobj{$so}{bi});
			$err = $session->error;
			if ($err){
				$main::dev{$na}{bi} = "err";
				&misc::Prt("ERR :$err\n","Sb");
			}else{
				my $bimg = &misc::Strip($r->{$misc::sysobj{$so}{bi}});
				$bimg =~ s/^flash:|^bootflash:|^slot[0-9]:|^sup-boot(flash|disk):|^disk0:|FIRMWARE REVISION: //;
				$bimg =~ s/.*\/(.*)/$1/;
				my $bi = substr($bimg,0,63);
				if($main::dev{$na}{bi} and $main::dev{$na}{bi} ne $bi and $misc::notify =~ /s/i){
					my $msg = "Bootimage changed from $main::dev{$na}{bi} to $bi";
					&db::Insert('events','level,time,source,info,class,device',"\"150\",\"$main::now\",\"$na\",\"$msg\",\"nedi\",\"$na\"");
					$misc::mq += &mon::AlertQ("$na: $msg\n","",1,$na) if $misc::notify =~ /S/;
				}
				$main::dev{$na}{bi} = $bi;
				&misc::Prt("BOOT:Image = $main::dev{$na}{bi}\n");
			}
		}else{
			$main::dev{$na}{bi} = "-";
		}

		if($misc::sysobj{$so}{vd}){
			$r  = $session->get_request($misc::sysobj{$so}{vd});
			$err = $session->error;
			if ($err){
				&misc::Prt("ERR :VTP domain, $err\n","Vd");
				$main::dev{$na}{vd} = "?";
			}else{
				$main::dev{$na}{vd} = &misc::Strip($r->{$misc::sysobj{$so}{vd}});
				&misc::Prt("VTP :Domain = $main::dev{$na}{vd}\n");
			}
		}else{
			$main::dev{$na}{vd} = "-";
		}
		if($misc::sysobj{$so}{vm}){
			$r  = $session->get_request($misc::sysobj{$so}{vm});
			$err = $session->error;
			if ($err or $r->{$misc::sysobj{$so}{vm}} !~ /^[1-3]$/){
				&misc::Prt("ERR :VTP mode,$err\n","Vm");
				$main::dev{$na}{vm} = 9;
			}else{
				$main::dev{$na}{vm} = $r->{$misc::sysobj{$so}{vm}};
				&misc::Prt("VTP :Mode = $main::dev{$na}{vm}\n");
			}
		}else{
			$main::dev{$na}{vm} = 0;
		}

		if($misc::sysobj{$so}{vn}){
			$r = $session->get_table($misc::sysobj{$so}{vn});				# Get Vlan names
			$err = $session->error;
			if ($err){
				&misc::Prt("ERR :Vlans, $err\n","Vn");
			}else{
				my %vna  = %{$r};
				my %vnx  = ();
				if($misc::sysobj{$so}{vl}){
					$r = $session->get_table($misc::sysobj{$so}{vl});		# Get Vlan name to id index (e.g. Extreme)
					$err = $session->error;
					if ($err){
						&misc::Prt("ERR :VlanIX, $err\n","Vl");
					}else{
						%vnx = %{$r};
					}
				}
				unless($err){
					while ( (my $vO,my $vn) =  each(%vna) ){
						my $x = substr($vO,rindex($vO,'.') + 1);
						my $vl = ($misc::sysobj{$so}{vl})?$vnx{"$misc::sysobj{$so}{vl}.$x"}:$x;
						if($vl =~ /^[0-9]+$/){					# Use if vlanid is number!
							&misc::Prt(sprintf("VLAN:%4.4s = %s\n",$vl,$vn) );
							$main::vlan{$na}{$vl} = $vn;
							$misc::vlid{$na}{$vn} = $vl;
						}else{
							&misc::Prt("VLAN:No numeric vlid: $vl","Vi");
						}
						$nv++;
					}
					&misc::Prt("","v$nv");
				}
			}
		}

		if($misc::sysobj{$so}{to}){
			$r = $session->get_request($misc::sysobj{$so}{to});
			$err = $session->error;
			my $to = &misc::Strip($r->{$misc::sysobj{$so}{to}});
			if(!$err and $to and $to !~ /noSuch(Instance|Object)/ ){
				$main::dev{$na}{ty} = $to;
				&misc::Prt("TYPE:Using PhysicalModelName $to\n");
			}else{
				&misc::Prt("ERR :Type $err, $to\n","Sy");
			}
		}
	}

	if($skip !~ /g/){
		if($misc::sysobj{$so}{cpu}){
			$r  = $session->get_request($misc::sysobj{$so}{cpu});
			$err = $session->error;
			if ($err or $r->{$misc::sysobj{$so}{cpu}} !~ /^[0-9]+$/){
				&misc::Prt("ERR :CPU ".(($err)?$err:$r->{$misc::sysobj{$so}{cpu}}." is not numeric")."\n","Su");
				$main::dev{$na}{cpu} = 0;
			}else{
				$main::dev{$na}{cpu} = &misc::Strip($r->{$misc::sysobj{$so}{cpu}});
				if($misc::notify =~ /s/i and $main::dev{$na}{cpu} > $misc::cpua){
					my $msg = "CPU load of $main::dev{$na}{cpu}% exceeds threshold of ${misc::cpua}%";
					&db::Insert('events','level,time,source,info,class,device',"\"200\",\"$main::now\",\"$na\",\"$msg\",\"nedi\",\"$na\"");
					$misc::mq += &mon::AlertQ("$na: $msg\n","",1,$na) if $misc::notify =~ /S/;
				}
				&misc::Prt("CPU :Utilization = $main::dev{$na}{cpu}%\n");
			}
		}else{
			$main::dev{$na}{cpu} = 0;
		}

		if($misc::sysobj{$so}{mem}){
			$r  = $session->get_request($misc::sysobj{$so}{mem});
			$err = $session->error;
			if ($err or $r->{$misc::sysobj{$so}{mem}} !~ /^[0-9]+$/){
				&misc::Prt("ERR :Mem ".(($err)?$err:$r->{$misc::sysobj{$so}{mem}}." is not numeric")."\n","Sm");
				$main::dev{$na}{mcp} = 0;
			}else{
				my $mem = &misc::Strip($r->{$misc::sysobj{$so}{mem}});
				my $al  = "";
				my $un  = "%";
				my @mal = split(/\//,$misc::mema);
				if($misc::sysobj{$so}{mmu} eq "-%"){
					$main::dev{$na}{mcp} = 100 - $mem;
					$al = "is below threshold of $mal[1]%" if $main::dev{$na}{mcp} < $mal[1];
				}elsif($misc::sysobj{$so}{mmu} eq "%"){
					$main::dev{$na}{mcp} = $mem;
					$al = "is below threshold of $mal[1]%" if $main::dev{$na}{mcp} < $mal[1];
				}else{
					$un = " Bytes";
					$main::dev{$na}{mcp} = int($mem * $misc::sysobj{$so}{mmu});
					$al = "is below threshold of $mal[0]KBytes" if $main::dev{$na}{mcp} < $mal[0]*1024;
				}
				my $msg = "Available memory $main::dev{$na}{mcp}$un $al";
				&misc::Prt("MEM :$msg\n");

				if($misc::notify =~ /s/i and $al){
					&db::Insert('events','level,time,source,info,class,device',"\"200\",\"$main::now\",\"$na\",\"$msg\",\"nedi\",\"$na\"");
					$misc::mq += &mon::AlertQ("$na: $msg\n","",1,$na) if $misc::notify =~ /S/;
				}
			}
		}else{
			$main::dev{$na}{mcp} = 0;
		}

		if($misc::sysobj{$so}{cuv}){
			$r  = $session->get_request($misc::sysobj{$so}{cuv});
			$err = $session->error;
			if ($err or $r->{$misc::sysobj{$so}{cuv}} !~ /^[0-9]+$/){
				&misc::Prt("ERR :Custom, $err\n","Si");
				$main::dev{$na}{cuv} = 0;
			}else{
				$main::dev{$na}{cuv} = $r->{$misc::sysobj{$so}{cuv}};
				&misc::Prt("CUS :$main::dev{$na}{cul} = $main::dev{$na}{cuv}\n");
			}
		}else{
			$main::dev{$na}{cuv} = 0;
		}

		if($misc::sysobj{$so}{tmp}){
			$r  = $session->get_request($misc::sysobj{$so}{tmp});
			$err = $session->error;
			if ($err or $r->{$misc::sysobj{$so}{tmp}} !~ /^[0-9]+$/){
				&misc::Prt("ERR :Temp, $err\n","St");
				$main::dev{$na}{tmp} = 0;
			}else{
				$main::dev{$na}{tmp} = int(&misc::Strip($r->{$misc::sysobj{$so}{tmp}}) * $misc::sysobj{$so}{tmu});
				if($misc::notify =~ /s/i and $main::dev{$na}{tmp} > $misc::tmpa){
					my $msg = "Temperature of $main::dev{$na}{tmp}C exceeds threshold of ${misc::tmpa}C";
					&db::Insert('events','level,time,source,info,class,device',"\"200\",\"$main::now\",\"$na\",\"$msg\",\"nedi\",\"$na\"");
					$misc::mq += &mon::AlertQ("$na: $msg\n","",1,$na) if $misc::notify =~ /S/;
				}
				&misc::Prt("TEMP:Environment = $main::dev{$na}{tmp} Degrees Celcius\n");
			}
		}else{
			$main::dev{$na}{tmp} = 0;
		}
	}
	$session->close;
}


=head2 FUNCTION Interfaces()

Get interface information

B<Options> device name

B<Globals> main::dev, main::int, misc::ifmac, misc::portprop

B<Returns> -

=cut
sub Interfaces {

	my ($na,$skip) = @_;
	my ($session, $err, $r);
	my $warn = my $ni = 0;
	my (%ifde, %iftp, %ifsp, %ifhs, %ifmc, %ifas, %ifos, %ifio, %ifie, %ifoo, %ifoe, %ifna, %ifpw, %ifpx, %poe);
	my (%ifal, %ifax, %alias, %ifvl, %ifvx, %vlid, %ifbr, %ifidi, %ifodi, %ifdp, %ifdx, %duplex, %usedoid);
	my (@ifx);

	my $ifdesO = '1.3.6.1.2.1.2.2.1.2';
	my $iftypO = '1.3.6.1.2.1.2.2.1.3';
	my $ifspdO = '1.3.6.1.2.1.2.2.1.5';
 	my $ifmacO = '1.3.6.1.2.1.2.2.1.6';
	my $ifadmO = '1.3.6.1.2.1.2.2.1.7';
	my $ifoprO = '1.3.6.1.2.1.2.2.1.8';
	my $ifinoO = '1.3.6.1.2.1.2.2.1.10';
	my $ifineO = '1.3.6.1.2.1.2.2.1.14';
	my $ifotoO = '1.3.6.1.2.1.2.2.1.16';
	my $ifoteO = '1.3.6.1.2.1.2.2.1.20';
	my $ifhioO = '1.3.6.1.2.1.31.1.1.1.6';
	my $ifhooO = '1.3.6.1.2.1.31.1.1.1.10';
	my $ifhspO = '1.3.6.1.2.1.31.1.1.1.15';

	my $so     = $main::dev{$na}{so};
	my $ifnamO = $misc::sysobj{$so}{in};
	my $ifaliO = $misc::sysobj{$so}{al};
	my $ifalxO = $misc::sysobj{$so}{ax};
	my $ifibrO = $misc::sysobj{$so}{ib};
	my $ifidiO = $misc::sysobj{$so}{id};
	my $ifodiO = $misc::sysobj{$so}{od};
	my $ifvlaO = $misc::sysobj{$so}{vi};
	my $ifvlxO = $misc::sysobj{$so}{vx};
	my $ifdupO = $misc::sysobj{$so}{du};
	my $ifduxO = $misc::sysobj{$so}{dx};

	&misc::Prt("\nInterfaces   ------------------------------------------------------------------\n");

	&db::ReadInt("device = \"$na\"");
	if($skip =~ /i/ and $skip =~ /t/ and $skip =~ /e/ and $skip =~ /d/ and $skip =~ /b/ and $skip =~ /w/ and $skip =~ /A/ and $skip =~ /O/){	# Don't create session, if everything's skipped
		&misc::Prt("IF  :Skipping all IF data, no write\n");
		return 1;
	}else{
		my $maxmsg = ($main::dev{$na}{os} eq "Nortel" or $main::dev{$na}{os} eq "NXOS")?"5500":"2048";					# TODO implement properly, if other OS cause problems too
		($session, $err) = &Connect($main::dev{$na}{ip}, $main::dev{$na}{rv}, $main::dev{$na}{rc}, $misc::timeout + 5, $maxmsg);
		return 1 unless defined $session;
	}

	if($main::dev{$na}{hc} & 128){									# Walk interface HC if set
		if($skip !~ /i/){
			$r = $session->get_table($ifhspO);						# Walk interface high-speed
			$err = $session->error;
			if ($err){
				&misc::Prt("ERR :64bit $err\n","IS");
				$warn++
			}else{
				while( my($key, $val) = each(%{$r}) ) {
					$ifsp{substr($key,rindex($key,'.')+1)} = $val * 1000000;
				}
			}
		}

		if($skip !~ /t/){
			&misc::Prt("IF  :Walking 64bit counters\n");
			$r = $session->get_table($ifhioO);
			$err = $session->error;
			if ($err){
				&misc::Prt("ERR :64bit-in $err\n","II");
				$warn++;
			}else{
				while( my($key, $val) = each(%{$r}) ) {
					$ifio{substr($key,rindex($key,'.')+1)} = $val;
				}
				$r = $session->get_table($ifhooO);					# Walk interface HC out octets
				$err = $session->error;
				if ($err){
					&misc::Prt("ERR :64bit-out $err\n","IO");
					$warn++
				}else{
					while( my($key, $val) = each(%{$r}) ) {
						$ifoo{substr($key,rindex($key,'.')+1)} = $val;
					}
				}
			}
		}
	}
	if($main::dev{$na}{hc} & 64){									# Merge Counters by .def now! Tx for Vasily's RFC2233 fix
		if($skip !~ /i/){
			$r = $session->get_table($ifspdO);						# Walk interface speed
			$err = $session->error;
			if ($err){
				&misc::Prt("ERR :Speed $err\n","Is");
				$warn++
			}else{
				while( my($key, $val) = each(%{$r}) ) {
					my $x = substr($key,rindex($key,'.')+1);
					$ifsp{$x} = $val if !$ifsp{$x};					# Combine 64-bit & 32-bit speeds
				}
			}
		}

		if($skip !~ /t/){
			&misc::Prt("IF  :Walking 32bit counters\n");
			$r = $session->get_table($ifinoO);						# Walk interface in octets
			$err = $session->error;
			if ($err){
				&misc::Prt("ERR :32bit-in $err\n","Ii");
				$warn++;
			}else{
				while( my($key, $val) = each(%{$r}) ) {
					my $x = substr($key,rindex($key,'.')+1);
					$ifio{$x} = $val if !$ifio{$x};
				}

				$r = $session->get_table($ifotoO);					# Walk interface out octets
				$err = $session->error;
				if ($err){
					&misc::Prt("ERR :32bit-out $err\n","Io");
					$warn++;
				}else{
					while( my($key, $val) = each(%{$r}) ) {
						my $x = substr($key,rindex($key,'.')+1);
						$ifoo{$x} = $val if !$ifoo{$x};
					}
				}
			}
		}
	}

	if($skip !~ /e/){
		&misc::Prt("IF  :Walking errors\n");
		$r = $session->get_table($ifineO);							# Walk interface in errors
		$err = $session->error;
		if ($err){&misc::Prt("ERR :In-errors $err\n","Ie");$warn++}else{ %ifie  = %{$r}}

		$r = $session->get_table($ifoteO);							# Walk interface in errors
		$err = $session->error;
		if ($err){&misc::Prt("ERR :Out-errors $err\n","Ie");$warn++}else{ %ifoe  = %{$r}}
	}

	if($skip !~ /b/ and $ifibrO){
		&misc::Prt("IF  :Walking in-broadcasts\n");
		$r = $session->get_table($ifibrO);							# Walk IF inbcasts
		$err = $session->error;
		if ($err){&misc::Prt("ERR :In-broadcasts $err\n","Ib");$warn++}else{ %ifbr  = %{$r}}
	}

	if($skip !~ /d/ and $ifidiO){
		&misc::Prt("IF  :Walking in-discards\n");
		$r = $session->get_table($ifidiO);							# Walk IF indiscards
		$err = $session->error;
		if ($err){&misc::Prt("ERR :In-discards $err\n","Ic");$warn++}else{ %ifidi  = %{$r}}
	}
	if($skip !~ /d/ and $ifodiO){
		&misc::Prt("IF  :Walking out-discards\n");
		$r = $session->get_table($ifodiO);							# Walk IF indiscards
		$err = $session->error;
		if ($err){&misc::Prt("ERR :Out-discards $err\n","Ic");$warn++}else{ %ifodi  = %{$r}}
	}

	if($skip =~ /i/){
		@ifx = sort { $a <=> $b } keys %{$main::int{$na}};					# Use Indexes from DB
	}else{
		&misc::Prt("IF  :Walking info\n");
		if($ifnamO){
			$r = $session->get_table($ifnamO);						# Walk IF name.
			$err = $session->error;
			if ($err){&misc::Prt("ERR :IF Name $err\n","In");$warn++}else{%ifna = %{$r}}
		}

		if($ifnamO eq $ifdesO){									# Copy IF desc, if used as name
			%ifde  = %{$r};
		}else{
			$r = $session->get_table($ifdesO);						# Walk IF description.
			$err = $session->error;
			if ($err){&misc::Prt("ERR :IF Desc $err\n","Id");$warn++}else{%ifde  = %{$r}}
		}
		@ifx = map(substr($_,20), keys %ifde);							# cut OIDs down to indexes in 1 step (gotta love perl!)

		$r = $session->get_table($iftypO);							# Walk IF type.
		$err = $session->error;
		if ($err){&misc::Prt("ERR :IF Type $err\n","It");$warn++}else{%iftp  = %{$r}}

		$r = $session->get_table($ifmacO);							# Walk IF mac address.
		$err = $session->error;
		if ($err){&misc::Prt("ERR :IF MAC $err\n","Im");$warn++}else{%ifmc  = %{$r}}

		if($ifaliO){										# Same for IF vlans...
			$r = $session->get_table($ifaliO);						# Walk IF alias.
			$err = $session->error;
			if ($err){&misc::Prt("ERR :Alias $ifaliO $err\n","Il");$warn++}else{ %ifal  = %{$r}}
		}
		if($ifalxO){
			$r = $session->get_table($ifalxO);						# Walk index table if specified...
			$err = $session->error;
			if ($err){
				&misc::Prt("ERR :Alias index $err\n","Il");
			}else{
				%ifax  = %{$r};
				$usedoid{$ifalxO} = \%ifax;						# (store in case it's the same for vlans or duplex)
				foreach my $x ( keys %ifax ){						# ...and map directly to if indexes
					my $i = $x;
					$i =~ s/$ifalxO\.//;
					$alias{$ifax{$x}} = $ifal{"$ifaliO.$i"};
				}
			}
		}else{											# Else use indexes directly
			foreach my $x ( keys %ifal ){
				my $i = $x;
				$i =~ s/$misc::sysobj{$so}{al}\.//;
				$alias{$i} = $ifal{$x};
			}
		}

		if($ifvlaO){										# Same for IF vlans...
			$r = $session->get_table($ifvlaO);
			$err = $session->error;
			if ($err){&misc::Prt("ERR :Vlan $err\n","Iv");$warn++}else{ %ifvl  = %{$r}}
		}
		if($ifvlxO){										# If vlans use a different index
			if(exists $usedoid{$ifvlxO}){							# and if it's been used before
				%ifvx = %{$usedoid{$ifvlxO}};						# assign the vlan oid to where the used one points to.
			}else{										# Otherwhise walk it
				$r = $session->get_table($ifvlxO);
				$err = $session->error;
				if ($err){
					&misc::Prt("ERR :Vlan index $err\n","Iv");
				}else{
					%ifvx  = %{$r};
					$usedoid{$ifvlxO} = \%ifvx;
				}
			}
			foreach my $x ( keys %ifvx ){
				my $i = $x;
				$i =~ s/$ifvlxO\.//;
				$vlid{$ifvx{$x}} = $ifvl{"$ifvlaO.$i"};
			}
		}else{
			foreach my $x ( keys %ifvl ){
				my $i = $x;
				$i =~ s/$ifvlaO\.//;
				$vlid{$i} = $ifvl{$x};
			}
		}

		if($ifdupO){										# ...and IF duplex
			if($ifdupO eq "doublespeed"){							# If duplex is shown by speed...
				foreach my $x ( keys %ifsp ){
					if($ifsp{$x} =~ /^20/){
						$ifsp{$x} /= 2;
						$duplex{$x} = "FD";
					}elsif($ifsp{$x} =~ /^10/){
						$duplex{$x} = "HD";
					}
				}
			}else{
				$r = $session->get_table($ifdupO);
				$err = $session->error;
				if ($err){&misc::Prt("ERR :Duplex $err\n","Ix");$warn++}else{ %ifdp  = %{$r}}
			}
		}
		if($ifduxO){										# If duplex uses a different index
			if(exists $usedoid{$ifduxO}){							# and if it's been used before
				%ifdx = %{$usedoid{$ifduxO}};						# assign the duplex oid to where the used one points to.
			}else{										# Otherwhise walk it
				$r = $session->get_table($ifduxO);
				$err = $session->error;
				if ($err){
					&misc::Prt("ERR :Duplex index $err\n","Ix");
				}else{
					%ifdx  = %{$r};
					$usedoid{$ifduxO} = \%ifdx;
				}
			}
			foreach my $x ( keys %ifdx ){
				my $i = $x;
				$i =~ s/$ifduxO\.//;
				$duplex{$ifdx{$x}} = $ifdp{"$ifdupO.$i"};
			}
		}else{
			foreach my $x ( keys %ifdp ){
				my $i = $x;
				$i =~ s/$ifdupO\.//;
				$duplex{$i} = $ifdp{$x};
			}
		}
	}

	if($skip !~ /A/){
		$r = $session->get_table($ifadmO);							# Walk interface admin status
		$err = $session->error;
		if ($err){&misc::Prt("ERR :IF Adminstat $err\n","Ia");$warn++}else{%ifas  = %{$r}}
	}

	if($skip !~ /O/){
		$r = $session->get_table($ifoprO);							# Walk interface oper status
		$err = $session->error;
		if ($err){&misc::Prt("ERR :IF Operstat $err\n","Ip");$warn++}else{%ifos  = %{$r}}
	}

	if($misc::usepoe eq "ifmib" and $misc::sysobj{$so}{pw} and $skip !~ /w/){
		$r = $session->get_table($misc::sysobj{$so}{pw});
		$err = $session->error;
		if ($err){&misc::Prt("ERR :IF PoE $err\n","Iw");$warn++}else{ %ifpw  = %{$r}}
		if($misc::sysobj{$so}{px} and !$err){							# If poe uses a different index
			if(exists $usedoid{$misc::sysobj{$so}{px}}){
				%ifpx = %{$usedoid{$misc::sysobj{$so}{px}}};
			}else{
				$r = $session->get_table($misc::sysobj{$so}{px});
				$err = $session->error;
				if ($err){
					&misc::Prt("ERR :IF PoE index $err\n","Ip");
				}else{
					%ifpx  = %{$r};
					$usedoid{$misc::sysobj{$so}{px}} = \%ifpx;
				}
			}
			foreach my $x ( keys %ifpx ){
				my $i = $x;
				$i =~ s/$misc::sysobj{$so}{px}\.//;
				$poe{$ifpx{$x}} = $ifpw{"$misc::sysobj{$so}{pw}.$i"};
			}
		}else{
			foreach my $x ( keys %ifpw ){
				my $i = $x;
				$i =~ s/$misc::sysobj{$so}{pw}\.//;
				$poe{$i} = $ifpw{$x};
			}
		}
	}
	$session->close if defined $session;								# Happens if everything was skipped

	&misc::Prt("IF  :Index Name         Spd Dup St Pvid Description      Alias            PoE\n");
	foreach my $i (@ifx){
		my $ifex = ($main::int{$na}{$i}{ina})?1:0;
		if($skip !~ /i/){
			my $ina = $i;									# strip OID up to index & use as name fallback
			if ($ifna{"$ifnamO.$i"}){
				if(exists $misc::portprop{$na}{$ifna{"$ifnamO.$i"}}{idx} ){		# IF name used before?
					$ina = &misc::Shif($ifna{"$ifnamO.$i"} . "-$i");		# Make unique using index
				}else{
					$ina = &misc::Shif($ifna{"$ifnamO.$i"});
				}
			}
			$main::int{$na}{$i}{ina} = $ina;
			$main::int{$na}{$i}{des} = &misc::Strip($ifde{"$ifdesO.$i"});
			$main::int{$na}{$i}{typ} = &misc::Strip($iftp{"$iftypO.$i"},0);
			$main::int{$na}{$i}{spd} = &misc::Strip($ifsp{"$i"},0);
			$main::int{$na}{$i}{ali} = &misc::Strip($alias{$i});
			$main::int{$na}{$i}{vid} = &misc::Strip($vlid{$i},0);
			if($ifmc{"$ifmacO.$i"}){
				my $imac = unpack('H12', $ifmc{"$ifmacO.$i"});
				$main::int{$na}{$i}{mac} = $imac;
				$misc::ifmac{$imac}{$na}++;
			}else{
				$main::int{$na}{$i}{mac} = "";
			}
			if(defined $duplex{$i} ){							# Did we get a duplex value?
				if($duplex{$i} =~ /^[FH]D$/){						# Use if set properly already...
					$main::int{$na}{$i}{dpx} = $duplex{$i};
#				}elsif($main::int{$na}{$i}{typ} == 56){					# Use Duplex for port mode (E,N) on FC devices TODO, finish & fetch connected WWN?
#					$main::int{$na}{$i}{com} = "WWN:$duplex{$i}";
				}else{									# ...or assign defined HD,FD key
					if($duplex{$i} eq $misc::sysobj{$so}{fd}){$main::int{$na}{$i}{dpx} = "FD"}
					elsif($duplex{$i} eq $misc::sysobj{$so}{hd}){$main::int{$na}{$i}{dpx} = "HD"}
					else{$main::int{$na}{$i}{dpx} = "?"}
				}
			}else{
				$main::int{$na}{$i}{dpx} = "-";
			}
		}
		my $ast = 4;
		my $ost = 8;
		unless($skip =~ /A/){
			$ast = (&misc::Strip($ifas{"$ifadmO.$i"},0) == 1)?1:0;
			$ast += 128 if exists $main::int{$na}{$i}{pst} and $main::int{$na}{$i}{pst} & 1 and !$ast;
		}
		unless($skip =~ /O/){
			$ost = (&misc::Strip($ifos{"$ifoprO.$i"},0) == 1)?2:0;
			$ost += 64 if exists $main::int{$na}{$i}{pst} and $main::int{$na}{$i}{pst} & 2 and !$ost;
		}
		$main::int{$na}{$i}{sta} = $ast + $ost;

		unless($skip =~ /t/){
			my $ic = &misc::Strip($ifio{"$i"},0);
			my $oc = &misc::Strip($ifoo{"$i"},0);
			#if($main::int{$na}{$i}{ioc} or $main::int{$na}{$i}{ooc}){			# Testing whether IF existed before would be more efficient if counters = 0
			if($ifex){
				$main::int{$na}{$i}{dio} = $ic - $main::int{$na}{$i}{ioc};
				$main::int{$na}{$i}{dio} = 0 if $main::int{$na}{$i}{dio} < 0;
				$main::int{$na}{$i}{doo} = $oc - $main::int{$na}{$i}{ooc};
				$main::int{$na}{$i}{doo} = 0 if $main::int{$na}{$i}{doo} < 0;
			}else{
				$main::int{$na}{$i}{dio} = 0;
				$main::int{$na}{$i}{doo} = 0;
			}
			$main::int{$na}{$i}{ioc} = $ic;
			$main::int{$na}{$i}{ooc} = $oc;
		}
		unless($skip =~ /e/){
			my $ic = &misc::Strip($ifie{"$i"},0);
			my $oc = &misc::Strip($ifoe{"$i"},0);
			if($ifex){
				$main::int{$na}{$i}{die} = $ic - $main::int{$na}{$i}{ier};
				$main::int{$na}{$i}{die} = 0 if $main::int{$na}{$i}{die} < 0;
				$main::int{$na}{$i}{doe} = $oc - $main::int{$na}{$i}{oer};
				$main::int{$na}{$i}{doe} = 0 if $main::int{$na}{$i}{doe} < 0;
			}else{
				$main::int{$na}{$i}{die} = 0;
				$main::int{$na}{$i}{doe} = 0;
			}
			$main::int{$na}{$i}{ier} = $ic;
			$main::int{$na}{$i}{oer} = $oc;
		}

		$main::int{$na}{$i}{com} = "";
		$main::int{$na}{$i}{idi} = &misc::Strip($ifidi{"$ifidiO.$i"},0) if $skip !~ /d/;#TODO add to DB
		$main::int{$na}{$i}{odi} = &misc::Strip($ifodi{"$ifodiO.$i"},0) if $skip !~ /d/;
		$main::int{$na}{$i}{ibr} = &misc::Strip($ifbr{"$ifibrO.$i"},0) if $skip !~ /b/;

		$main::int{$na}{$i}{poe} = &misc::Strip($poe{$i},0) if $skip !~ /w/;

		if($main::int{$na}{$i}{ina} =~ /^[0-9]+[-,][0-9]|^(Po|Trk|Bridge-Aggregation)[0-9]|channel/ and $main::int{$na}{$i}{typ} !~ /^(6|7|117)$/){	# A channel is not ethernet
			$misc::portprop{$na}{$main::int{$na}{$i}{ina}}{chn} = 1;
		}
		if(exists $main::link{$na} and exists $main::link{$na}{$main::int{$na}{$i}{ina}}){	# Use both to avoid defining
			$main::int{$na}{$i}{lty} = "STAT";
			$misc::portprop{$na}{$main::int{$na}{$i}{ina}}{lnk} = 1;
		}elsif($skip =~ /i/ and $main::int{$na}{$i}{lty}){									# Keep existing linktype and set port property if IF info is skipped
			$misc::portprop{$na}{$main::int{$na}{$i}{ina}}{lnk} = 1;
		}else{
			$main::int{$na}{$i}{lty} = "";
		}
		$misc::portprop{$na}{$main::int{$na}{$i}{ina}}{idx} = $i;
		$misc::portprop{$na}{$main::int{$na}{$i}{ina}}{spd} = $main::int{$na}{$i}{spd};
		$misc::portprop{$na}{$main::int{$na}{$i}{ina}}{vid} = $main::int{$na}{$i}{vid};
		$misc::portprop{$na}{$main::int{$na}{$i}{ina}}{typ} = $main::int{$na}{$i}{typ};

		if($misc::sysobj{$so}{dp} =~ /LLDPXA/){							# Some index on Desc some on Alias!
			$misc::portdes{$na}{$main::int{$na}{$i}{ali}} = $i;
		}else{
			$misc::portdes{$na}{$main::int{$na}{$i}{des}} = $i;
		}

		&misc::Prt(sprintf ("IF  :%5.5s %-8.8s  %5.5sM  %-2.2s %2.1d %4.4s %-16.16s %-16.16s  %sW\n",$i,$main::int{$na}{$i}{ina},$main::int{$na}{$i}{spd}/1000000,$main::int{$na}{$i}{dpx},$main::int{$na}{$i}{sta},$main::int{$na}{$i}{vid},$main::int{$na}{$i}{des},$main::int{$na}{$i}{ali},$main::int{$na}{$i}{poe}) );
		$ni++;
	}
	&misc::Prt(""," i$ni".($warn?" ":"\t") );
	
	return 0;											# Or noifwrite will be true!
}


=head2 FUNCTION IfAddresses()

Get IP address tables and tries to find best mgmt IP (based on idea from Duane Walker)

B<Options> device name

B<Globals> main::dev, main::net

B<Returns> -

=cut
sub IfAddresses {

	my ($na) = @_;
	my ($session, $err, $r, $newip);
	my (%aifx, %ainm, %vrfs, %vrfna, %vrfst, %typri);
	my $warn  = my $nia = 0;
	my $ippri = my $dnspri = 16;
	my $iaixO = "1.3.6.1.2.1.4.20.1.2";
	my $ianmO = "1.3.6.1.2.1.4.20.1.3";
	my $vrfO  = "1.3.6.1.3.118.1.2.1.1.6"; #TODO handle in .def? check rfc3814-mpls-ftn-std.mib


	&misc::Prt("\nIfAddresses  ------------------------------------------------------------------\n");
	if(exists $misc::useip{$main::dev{$na}{ty}}){							# Type based IF priority? Define typri only if configured
		&misc::Prt("IFIP:useip policy for $main::dev{$na}{ty}=$misc::useip{$main::dev{$na}{ty}}\n");
		$typri{6} = $typri{7}= $typri{117} = index(" $misc::useip{$main::dev{$na}{ty}}",'e')*4 if index(" $misc::useip{$main::dev{$na}{ty}}",'e') ne -1;
		$typri{24} = index(" $misc::useip{$main::dev{$na}{ty}}",'l')*4 if index(" $misc::useip{$main::dev{$na}{ty}}",'l') ne -1;
		$typri{53} = index(" $misc::useip{$main::dev{$na}{ty}}",'v')*4 if index(" $misc::useip{$main::dev{$na}{ty}}",'v') ne -1;
		$dnspri = index(" $misc::useip{$main::dev{$na}{ty}}",'n')*4 if index(" $misc::useip{$main::dev{$na}{ty}}",'n') ne -1;
	}elsif(exists $misc::useip{'default'}){								# Default set?
		&misc::Prt("IFIP:default useip policy=$misc::useip{'default'}\n");
		$typri{6} = $typri{7}= $typri{117} = index(" $misc::useip{'default'}",'e')*4 if index(" $misc::useip{'default'}",'e') ne -1;
		$typri{24} = index(" $misc::useip{'default'}",'l')*4 if index(" $misc::useip{'default'}",'l') ne -1;
		$typri{53} = index(" $misc::useip{'default'}",'v')*4 if index(" $misc::useip{'default'}",'v') ne -1;
		$dnspri = index(" $misc::useip{'default'}",'n')*4 if index(" $misc::useip{'default'}",'n') ne -1;
	}else{												# Don't change IP
		&misc::Prt("IFIP:No useip policy set, always using discovered IPs\n");
	}

	($session, $err) = &Connect($main::dev{$na}{ip}, $main::dev{$na}{rv}, $main::dev{$na}{rc});
	return unless defined $session;

	$r   = $session->get_table($iaixO);
	$err = $session->error;
	if ($err){
		$session->close;
		&misc::Prt("ERR :IP $err\n","Ja");
		return 1;
	}else{%aifx = %{$r}}

	$r   = $session->get_table($ianmO);
	$err = $session->error;
	if ($err){&misc::Prt("ERR :Mask $err\n","Jm");$warn++}else{%ainm = %{$r}}
	if ($main::dev{$na}{sv} > 3 and $main::dev{$na}{os} =~/IOS/){					# Try only Cisco routers for now
		$r   = $session->get_table($vrfO);
		$err = $session->error;
		if ($err){&misc::Prt("ERR :VRF $err\n","Jv");$warn++}else{ %vrfs = %{$r} }
		foreach my $k ( keys %vrfs ){
			my @karr = split(/\./,substr($k, 24));
			shift(@karr);
			my $ix = pop(@karr);								# Showstopper, no index shown in perl???
			my $vrfna = "";
			foreach my $char (@karr){							# VRF Name is OID...
				$vrfna .= chr($char);
			}
			$vrfna{$main::int{$na}{$ix}{ina}} = $vrfna;
			$vrfst{$main::int{$na}{$ix}{ina}} = $vrfs{$k};
		}
	}

	$session->close;

	foreach my $k ( sort keys %aifx ){								# lowest IPs first
		if(exists $main::int{$na}{$aifx{$k}}){							# Avoid non existant IFs (e.g. idx=0 on  cisco2970 and 3750 with IOS 12.1)
			my @i		= split(/\./,$k);
			if (defined $i[13]){								# Some devs have incomplete IPs here!
				my $iaddr	= "$i[10].$i[11].$i[12].$i[13]";
				$main::net{$na}{$iaddr}{ifn} = $main::int{$na}{$aifx{$k}}{ina};
				$main::net{$na}{$iaddr}{msk} = $ainm{"$ianmO.$iaddr"};
				if( exists $vrfna{$main::int{$na}{$aifx{$k}}{ina}} ){
					$main::net{$na}{$iaddr}{vrf} = $vrfna{$main::int{$na}{$aifx{$k}}{ina}};
					$main::net{$na}{$iaddr}{vrs} = $vrfst{$main::int{$na}{$aifx{$k}}{ina}};
				}
				&misc::Prt(sprintf ("IFIP:%-8.8s %-15.15s %-15.15s ",$main::net{$na}{$iaddr}{ifn},$iaddr,$main::net{$na}{$iaddr}{msk}) );
				my $nok = 0;
				if($iaddr =~ /^(0|127).0/ or $iaddr !~ /$misc::netfilter/){
					&misc::Prt("not usable\n");
				}else{
					my $valip = 0;
					if(exists $misc::ifip{$iaddr}){					# IP used on other devs or just this?
						if(exists $misc::ifip{$iaddr}{$na} and scalar keys %{$misc::ifip{$iaddr}} == 1){
							$valip = 1;
						}else{
							if($misc::notify =~ /u/i and $main::int{$na}{$aifx{$k}}{sta}){# Don't notify if IF is shutdown
								my $msg = "Duplicate IP $iaddr found on " . join(', ', keys %{$misc::ifip{$iaddr}});
								&misc::Prt("$msg\n");
								&db::Insert('events','level,time,source,info,class,device',"\"150\",\"$main::now\",\"$na\",\"$msg\",\"nedi\",\"$na\"");
								$misc::mq += &mon::AlertQ("$na: $msg\n","",1,$na) if $misc::notify =~ /U/;
							}
						}
					}else{
						$misc::ifip{$iaddr}{$na} = 1;
						$valip = 1;
					}
					if($valip){
						&misc::Prt("ok & unique\n");
						if(defined $typri{$main::int{$na}{$aifx{$k}}{typ}} and $ippri >= $typri{$main::int{$na}{$aifx{$k}}{typ}}){

							if($iaddr eq $main::dev{$na}{ip}){
								$ippri = $typri{$main::int{$na}{$aifx{$k}}{typ}} - 1;
								$newip = $iaddr;
								&misc::Prt("IFIP:$iaddr is original IP pri=$ippri\n");
							}elsif( &mon::PingService($iaddr) ){		# Only use if reachable
								$ippri = $typri{$main::int{$na}{$aifx{$k}}{typ}};
								$newip = $iaddr;
								&misc::Prt("IFIP:$iaddr is new IP pri=$ippri\n");
							}else{
								&misc::Prt("IFIP:$iaddr is not reachable\n");
							}
						}
					}
				}
				$nia++;
			}
		}
	}

	if( $ippri > $dnspri){
		my $iaddr = &misc::ResolveName($na);
		if($iaddr and &mon::PingService($iaddr) ){						# Only use if reachable
			$ippri = $dnspri;
			$newip = $iaddr;
			&misc::Prt("DNS :$na resolves to $iaddr priority $ippri\n");
		}
	}

	if ($ippri < 15){
		$main::dev{$na}{ip} = &misc::MapIp($newip);
		&misc::Prt("IFIP:Using $main::dev{$na}{ip} with priority $ippri out of $nia addresses\n");
	}
	&misc::Prt("","j$nia".($warn)?" ":"\t");
}


=head2 FUNCTION CDPCap2Sv()

Converts CDP capabilities to sys services alike format

B<Options> CDP services string

B<Globals> -

B<Returns> SNMP services decimal

=cut
sub CDPCap2Sv {

	my $srv = 0;
	my $sv  = hex(unpack("C",substr($_[0],length($_[0])-1,1)));
	if ($sv & 1)		{$srv =   4}
	if ($sv & (8|4|2))	{$srv +=  2}
	if ($sv & 16)		{$srv += 64}
	if ($sv & 64)		{$srv +=  1}
	return $srv;
}


=head2 FUNCTION FDPCap2Sv()

Converts FDP capabilities to sys services alike format

B<Options> FDP services string

B<Globals> -

B<Returns> SNMP services decimal

=cut
sub FDPCap2Sv {

	my $srv = 0;
	my $sv  = $_[0];
	if ($sv eq "Switch")	{$srv =   2}
	if ($sv eq "Router")	{$srv +=  4}
	return $srv;
}


=head2 FUNCTION LLDPCap2Sv()

Converts LLDP capabilities to sys services alike format

B<Options> LLDP services string

B<Globals> -

B<Returns> SNMP services decimal

=cut
sub LLDPCap2Sv {

	my $srv = 0;
	my $sv  = unpack("C",$_[0]);
	if ($sv & 2)		{$srv =  1}								# repeater = L1
	if ($sv & (4|8|64))	{$srv += 2}								# bridge, AP, cablemodem = L2
	if ($sv & 16)		{$srv += 4}								# router = L3
	if ($sv & 32)		{$srv += 32}								# phone = terminal (more benefits than treating as station)
	if ($sv & (1|128))	{$srv += 64}								# other, station = L7
	return $srv;
}


=head2 FUNCTION DisProtocol()

Use discovery protocol to find neighbours.

B<Options> device name, discovery protocol id (usually name), discovery protocol

B<Globals> main::dev, main::int, main::link, misc::portprop, (misc::doip if opt{p})

B<Returns> -

=cut
sub DisProtocol {

	use Socket; 											# DNS support

	my ($na, $id, $dp) = @_;
	my ($session, $err, $r);
	my (%lneb, %lix, %neb);
	my $warn = my $ad = my $dn = my $bd = 0;

	my $maxmsg = "4095";
	if($main::dev{$na}{os} eq "NXOS"){
		$maxmsg = "5500";
	}elsif($main::dev{$na}{os} eq "Ironware"){
		$maxmsg = "";
	}												# TODO implement properly, if other OS cause problems too (Foundry)
	&misc::Prt("\nDisProtocol  ------------------------------------------------------------------\n");
	($session, $err) = &Connect($main::dev{$na}{ip}, $main::dev{$na}{rv}, $main::dev{$na}{rc}, $misc::timeout + 5, $maxmsg);
	return unless defined $session;

	if ($dp =~ /LLDP/){
	# LLDP perl-bulkwalk fails on HP-E, avoiding it seems to help...
		$r = $session->get_table(-baseoid => '1.0.8802.1.1.2.1.4.1.1',-maxrepetitions  => 1);
		$err = $session->error;
		if ($err){
			&misc::Prt("ERR :LLDP nbr $err\n","Dl");
		}else{
			%lneb = %{$r};
		}

		if($dp =~ /LLDPXN/){									# Some don't simply use IF index, thus we need to match on IF desc or name:
			$r = $session->get_table(-baseoid => '1.0.8802.1.1.2.1.3.7.1.3',-maxrepetitions  => 1);
			$err = $session->error;
			if ($err){
				&misc::Prt("ERR :LLDP IF name $err\n","Dl");
			}else{
				while( my($key, $val) = each(%{$r}) ) {
					my @k = split (/\./,$key);
					if(exists $misc::portprop{$na}{&misc::Shif($val)}){
						$lix{$k[11]} = $misc::portprop{$na}{&misc::Shif($val)}{idx};
						&misc::Prt("LLDP:$val index $k[11] is IF index $lix{$k[11]}\n");
					}else{
						$lix{$k[11]} = 0;
						&misc::Prt("LLDP:$val index $k[11] has no IF index!\n");
					}
				}
			}
		}elsif($dp =~ /LLDPX/){
			$r = $session->get_table(-baseoid => '1.0.8802.1.1.2.1.3.7.1.4',-maxrepetitions  => 1);
			$err = $session->error;
			if ($err){
				&misc::Prt("ERR :LLDP IF desc $err\n","Dl");
			}else{
				while( my($key, $val) = each(%{$r}) ) {
					my @k = split (/\./,$key);
					if($val){
						$lix{$k[11]} = &misc::Strip($misc::portdes{$na}{$val});	# Avoid uninit by Strip()
						&misc::Prt("LLDP:$val index $k[11] is IF index $lix{$k[11]}\n");
					}
				}
			}
		}

		if($misc::usepoe eq "disprot"){
			$r = $session->get_table(-baseoid => '1.0.8802.1.1.2.1.5.4795.1.2.11.1.1',-maxrepetitions  => 1);# Get LLDP PoE
			$err = $session->error;
			if ($err){
				&misc::Prt("ERR :LLDP PoE $err\n","Dp");
			}else{
				while( my($key, $val) = each(%{$r}) ) {
					my @k = split (/\./,$key);
					my $x = ($dp =~ /LLDPX/)?$lix{$k[14]}:$k[14];
					if($x){
						$main::int{$na}{$x}{poe} = $val * 100;
						&misc::Prt("LLDP:IF index $x delivering $main::int{$na}{$x}{poe}mW\n");
					}
				}
			}
		}

		$r = $session->get_table(-baseoid => '1.0.8802.1.1.2.1.4.2.1.3',-maxrepetitions  => 1);	# Get Remote IPs
		$err = $session->error;
		if ($err){
			&misc::Prt("ERR :LLDP IP $err\n","Da");
		}else{
			while( my($key, $val) = each(%{$r}) ) {
				my @k = split (/\./,$key);
				my $x = ($dp =~ /LLDPX/)?$lix{$k[12]}:$k[12];
				if($x){
					if(scalar @k == 20){						# IP in decimal
						$neb{$x}{$k[13]}{'ip'} = &misc::MapIp("$k[16].$k[17].$k[18].$k[19]");
					}else{
						my $aip = "";
						foreach my $i (splice(@k,16)){				# IP in ASCII (Extreme)
							$aip .= chr($i);
						}
						$neb{$x}{$k[13]}{'ip'} = &misc::MapIp($aip);
					}
					$neb{$x}{$k[13]}{'id'} = $neb{$x}{$k[13]}{'ip'};		# Make sure we got some ID
				}
			}
		}

		while( my($key, $val) = each(%lneb) ) {
#(1)lldpRemTimeMark (2)lldpRemLocalPortNum (3)lldpRemIndex (4)lldpRemChassisIdSubtype (5)lldpRemChassisId (6)lldpRemPortIdSubtype (7)lldpRemPortId (8)lldpRemPortDesc (9)lldpRemSysName
			my @k = split (/\./,$key);
			my $x = ($dp =~ /LLDPX/)?$lix{$k[12]}:$k[12];
			if($x){
				$neb{$x}{$k[13]}{'dp'} = 'LLDP';
				if($k[10] == 5){
	#lldpRemChassisIdSubtype(4.1.1.4): Component(1),interfaceAlias(2),portComponent(3),macAddress(4),networkAddress(5),interfaceName(6),local(7)
					if($lneb{"1.0.8802.1.1.2.1.4.1.1.4.$k[11].$k[12].$k[13]"} == 5){	# if subtype is IP address use if not set above
						$neb{$x}{$k[13]}{'ip'} = join('.',unpack('C*',substr($val,1) )) if !$neb{$x}{$k[13]}{'ip'};#TODO rather use C4 instead of C*?
					}elsif($lneb{"1.0.8802.1.1.2.1.4.1.1.4.$k[11].$k[12].$k[13]"} == 4){	# if subtype is MAC address
						$neb{$x}{$k[13]}{'id'} = unpack("H16",$val);
						$neb{$x}{$k[13]}{'na'} = unpack("H16",$val) if !$neb{$x}{$k[13]}{'na'};
					}else{
						$neb{$x}{$k[13]}{'id'} = &misc::Strip($val);
						$neb{$x}{$k[13]}{'na'} = &misc::Strip($val) if !$neb{$x}{$k[13]}{'na'};
					}
				}elsif($k[10] == 7){								# lldpRemPortId
	#lldpRemPortIdSubtype(4.1.1.6): interfaceAlias(1), portComponent(2),macAddress(3),networkAddress(4),interfaceName(5),agentCircuitId(6),local(7)
					if($lneb{"1.0.8802.1.1.2.1.4.1.1.6.$k[11].$k[12].$k[13]"} eq 7 and $val =~ /^[0-9]+$/){	# Prefer Descr if subtype is local and a #
						$neb{$x}{$k[13]}{'if'} = &misc::Shif($lneb{"1.0.8802.1.1.2.1.4.1.1.8.$k[11].$k[12].$k[13]"});
					}elsif($lneb{"1.0.8802.1.1.2.1.4.1.1.6.$k[11].$k[12].$k[13]"} eq 3){	# if subtype is MAC address
						$neb{$x}{$k[13]}{'if'} = unpack("H16",$val);
                                                $neb{$x}{$k[13]}{'na'} = unpack("H16",$val) if !$neb{$x}{$k[13]}{'na'};
                                                $neb{$x}{$k[13]}{'id'} = unpack("H16",$val) if !$neb{$x}{$k[13]}{'id'};
					}else{
						$neb{$x}{$k[13]}{'if'} = &misc::Shif($val);
					}
				}elsif($k[10] == 8 and $val){							# Remote Port Description
					$neb{$x}{$k[13]}{'if'} = &misc::Shif($val) if !$neb{$x}{$k[13]}{'if'};	# Use IF desc, if no name yet
				}elsif($k[10] == 9 and $val){							# lldpRemSysName
					$neb{$x}{$k[13]}{'na'} = &misc::Strip($val);
				}elsif($k[10] == 10){
					$neb{$x}{$k[13]}{'de'} = &misc::Strip($val);
					$neb{$x}{$k[13]}{'ty'} = $neb{$x}{$k[13]}{'de'};			# No Type with LLDP :-(
				}elsif($k[10] == 11){
					$neb{$x}{$k[13]}{'sv'} = &LLDPCap2Sv($val);
				}
			}
		}
	}

	if ($dp =~ /CDP/){
		$r = $session->get_table('1.3.6.1.4.1.9.9.23.1.2.1.1');
		$err = $session->error;
		if ($err){
			&misc::Prt("ERR :CDP $err\n","Dc");
		}else{
			%lneb = %{$r};
			while( my($key, $val) = each(%lneb) ) {
				my @k = split (/\./,$key);
				$neb{$k[14]}{$k[15]}{'dp'} = 'CDP';
				if($k[13] == 4){
					if($val){
						$neb{$k[14]}{$k[15]}{'ip'} = &misc::MapIp(unpack("C",substr($val,0,1)).".".
											unpack("C",substr($val,1,1)).".".
											unpack("C",substr($val,2,1)).".".
											unpack("C",substr($val,3,1)) );#TODO use C4 instead
					}else{$neb{$k[14]}{$k[15]}{'ip'} = ''}
				}elsif($k[13] == 5){
					$neb{$k[14]}{$k[15]}{'de'} = &misc::Strip($val);
				}elsif($k[13] == 6){
					my $nebid = &misc::Strip($val);
					$neb{$k[14]}{$k[15]}{'id'} = $nebid;
					$neb{$k[14]}{$k[15]}{'na'} = $nebid;
					if($lneb{"1.3.6.1.4.1.9.9.23.1.2.1.1.8.$k[14].$k[15]" =~ /^WS-C/}){
						$neb{$k[14]}{$k[15]}{'na'} =~ s/(.*?)\((.*?)\)/$2/;			# Extract from CatOS
					}else{
						$neb{$k[14]}{$k[15]}{'na'} =~ s/(.*?)\((.*?)\)/$1/;			# Extract from other (e.g. NxK)
					}
					$neb{$k[14]}{$k[15]}{'na'} =~ s/(\xff){1,}/BadCDP-$k[15]/;		# Fixes some phone weirdness
				}elsif($k[13] == 7){
					$neb{$k[14]}{$k[15]}{'if'} = &misc::Shif($val);
				}elsif($k[13] == 8){
					$neb{$k[14]}{$k[15]}{'ty'} = &misc::Strip($val);
				}elsif($k[13] == 9){
					$neb{$k[14]}{$k[15]}{'sv'} = &CDPCap2Sv($val);
				}elsif($k[13] == 10){
					$neb{$k[14]}{$k[15]}{'vd'} = &misc::Strip($val);
				}elsif($k[13] == 12){
					if($val == 2){
						$neb{$k[14]}{$k[15]}{'dx'} = "HD";
					}elsif($val == 3){
						$neb{$k[14]}{$k[15]}{'dx'} = "FD";
					}
				}elsif($k[13] == 14){
					$neb{$k[14]}{$k[15]}{'vl'} = &misc::Strip($val);
				}elsif($k[13] == 15 and $misc::usepoe eq "disprot"){
					$main::int{$na}{$k[14]}{poe} = &misc::Strip( ($val > 4000000000 or !$val)?0:$val );		# Some devs report weirdness!
					&misc::Prt("CDP :IF index $k[14] delivering $main::int{$na}{$k[14]}{poe}mW\n");
				}
			}
		}
	}

	if ($dp =~ /FDP/){
		$r = $session->get_table('1.3.6.1.4.1.1991.1.1.3.20.1.2.1.1');
		$err = $session->error;
		if ($err){
			&misc::Prt("ERR :FDP $err\n","Df");
		}else{
			while( my($key, $val) = each(%{$r}) ) {
				my @k = split (/\./,$key);
				$neb{$k[16]}{$k[17]}{'dp'} = 'FDP';
				if($k[15] == 5){
					if($val){
						$neb{$k[16]}{$k[17]}{'ip'} = &misc::MapIp(unpack("C",substr($val,0,1)).".".
											unpack("C",substr($val,1,1)).".".
											unpack("C",substr($val,2,1)).".".
											unpack("C",substr($val,3,1)) );#TODO use C4 instead
					}else{$neb{$k[16]}{$k[17]}{'ip'} = ''}
				}elsif($k[15] == 6){
					$neb{$k[16]}{$k[17]}{'de'} = &misc::Strip($val);
				}elsif($k[15] == 3){
					my $nebid = &misc::Strip($val);
					$neb{$k[16]}{$k[17]}{'id'} = $nebid;
					$neb{$k[16]}{$k[17]}{'na'} = $nebid;
				}elsif($k[15] == 7){
					$neb{$k[16]}{$k[17]}{'if'} = &misc::Shif($val);
				}elsif($k[15] == 8){
					$neb{$k[16]}{$k[17]}{'ty'} = &misc::Strip($val);
				}elsif($k[15] == 9){
					$neb{$k[16]}{$k[17]}{'sv'} = &FDPCap2Sv($val);
				}elsif($k[15] == 10){
					$neb{$k[16]}{$k[17]}{'vd'} = &misc::Strip($val);
				}elsif($k[15] == 12){
					if($val == 2){
						$neb{$k[16]}{$k[17]}{'dx'} = "HD";
					}elsif($val == 3){
						$neb{$k[16]}{$k[17]}{'dx'} = "FD";
					}
				}elsif($k[15] == 14){
					$neb{$k[16]}{$k[17]}{'vl'} = &misc::Strip($val);
				}
			}
		}
	}

	if ($dp =~ /NDP/){
		$r = $session->get_table('1.3.6.1.4.1.45.1.6.13.2.1.1');
		$err = $session->error;
		if ($err){
			&misc::Prt("ERR :NDP $err\n","Df");
		}else{
			while( my($key, $val) = each(%{$r}) ) {
				my @k = split (/\./,$key);
				if($k[15] != 0){								# 0 is me
					if($k[13] == 5){
						$neb{$k[15]}{$k[14]}{'dp'} = 'NDP';
						$neb{$k[15]}{$k[14]}{'ip'} = &misc::MapIp("$k[16].$k[17].$k[18].$k[19]");
						$neb{$k[15]}{$k[14]}{'if'} = int($k[20]/256).'/'.$k[20]%256;
						$neb{$k[15]}{$k[14]}{'na'} = (exists $misc::snmpini{$neb{$k[15]}{$k[14]}{'ip'}})?$misc::snmpini{$neb{$k[15]}{$k[14]}{'ip'}}{na}:'';
						$neb{$k[15]}{$k[14]}{'de'} = 'NDP-Device';
						$neb{$k[15]}{$k[14]}{'id'} = unpack("H16",$val);
					}elsif($k[13] == 6){
						$neb{$k[15]}{$k[14]}{'ty'} = $val;
					}
				}
			}
		}
	}

	$session->close;

	foreach my $i ( keys %neb ){
		my $lif = "";
		if(!exists $main::int{$na}{$i}){								# Assign interfacename, if IF exists
			&misc::Prt("DIPR:No IF with index $i (try LLDPX or LLDPXN in .def)!\n","Dx");
		}else{
			$lif = $main::int{$na}{$i}{ina};
			foreach my $n ( keys %{$neb{$i}} ){
				if(exists $main::link{$na}{$lif}{$neb{$i}{$n}{'na'}}){									# Avoid duplicates (can happen with Cisco phones on ProCurve switches)
					&misc::Prt("DIPR:Ignoring duplicate neighbor $neb{$i}{$n}{'na'} on $lif\n","Dx");
				}else{
					if (exists $neb{$i}{$n}{'ip'}){
						if(exists $misc::map{$neb{$i}{$n}{'ip'}}{na}){
							$neb{$i}{$n}{'na'} = $misc::map{$neb{$i}{$n}{'ip'}}{na};	# Map neighbor name if configured
						}elsif(!$neb{$i}{$n}{'na'}){						# No name? Resolve IP (or use id if this fails)
							$neb{$i}{$n}{'na'} = gethostbyaddr(inet_aton($neb{$i}{$n}{'ip'}), AF_INET) or $neb{$i}{$n}{'na'} = $neb{$i}{$n}{'id'};
						}
					}else{
						$neb{$i}{$n}{'ip'} = "";
						&misc::Prt("DIPR:No IP found for $neb{$i}{$n}{'na'}!\n");
					}
					$neb{$i}{$n}{'na'} =~ s/^(.*?)\.(.*)/$1/ if !$main::opt{'F'};			# Strip domain
					if($neb{$i}{$n}{'ty'} =~ /VMware/){						# Until VMware considers sending the mgmt IP with CDP
						$neb{$i}{$n}{'ip'} = &misc::ResolveName($neb{$i}{$n}{'na'});
						$main::link{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{$na}{$lif}{bw} = $main::int{$na}{$i}{spd};
						$main::link{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{$na}{$lif}{ty} = $neb{$i}{$n}{'dp'};
						$main::link{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{$na}{$lif}{de} = "Discovered ".localtime($main::now);
						$main::link{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{$na}{$lif}{du} = $main::int{$na}{$i}{dpx};
						$main::link{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{$na}{$lif}{vl} = $main::int{$na}{$i}{vid};
						$misc::portprop{$na}{$lif}{nsd} = 1;					# No-SNMP-Device IF metric to keep VMs on this IF
					}
					&misc::Prt( sprintf("%-4.4s:%-10.10s %-6.6s on %-6.6s %-15.15s %-10.10s %-15.15s\n",$neb{$i}{$n}{'dp'},$neb{$i}{$n}{'na'},$neb{$i}{$n}{'if'},$lif,$neb{$i}{$n}{'ip'},$neb{$i}{$n}{'ty'},$neb{$i}{$n}{'de'}) );
					$main::int{$na}{$i}{com} .= " $neb{$i}{$n}{'dp'}:$neb{$i}{$n}{'na'},$neb{$i}{$n}{'if'}";
					if($id eq $neb{$i}{$n}{'id'} or $na eq  $neb{$i}{$n}{'na'}){			# Seeing myself?
						&misc::Prt(sprintf ("%-4.4s:Loop on %s\n",$neb{$i}{$n}{'dp'},$lif),"DL");
						$main::int{$na}{$i}{com} .= " Loop!";
						$misc::portprop{$na}{$lif}{lnk} = 1;
						if($misc::notify =~ /d/i){
							my $msg = "Potential $neb{$i}{$n}{'dp'} loop on $lif!";
							&db::Insert('events','level,time,source,info,class,device',"\"150\",\"$main::now\",\"$na\",\"$msg\",\"nedi\",\"$na\"");
							$misc::mq += &mon::AlertQ("$na: $msg\n","",1,$na) if $misc::notify =~ /D/;
						}
					}elsif($neb{$i}{$n}{'na'} =~ /$misc::border/){
						&misc::Prt( sprintf ("%-4.4s:ID %s matches border /%s/\n",$neb{$i}{$n}{'dp'},$neb{$i}{$n}{'id'},$misc::border) );
						$misc::portprop{$na}{$lif}{nsd} = 1;					# NoSnmpDev to keep nodes behind IP phones, if set as border, but prevent all unknowns wandering off to this link...
						$bd++;
					}else{
						if($main::int{$na}{$i}{lty} ne "STAT"){					# No DP link if static exists
							$main::link{$na}{$lif}{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{bw} = $main::int{$na}{$i}{spd};
							$main::link{$na}{$lif}{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{ty} = $neb{$i}{$n}{'dp'};
							$main::link{$na}{$lif}{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{de} = "Discovered ".localtime($main::now);
							$main::link{$na}{$lif}{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{du} = $neb{$i}{$n}{'dx'};
							$main::link{$na}{$lif}{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{vl} = $neb{$i}{$n}{'vl'};
							$main::int{$na}{$i}{lty} = $neb{$i}{$n}{'dp'};
						}
						if("$neb{$i}{$n}{'de'}$neb{$i}{$n}{'ty'}" =~ /$misc::nosnmpdev/ or ($neb{$i}{$n}{'na'} =~ /^AV\w/ and $neb{$i}{$n}{'sv'} & 32) ){
							$misc::portprop{$na}{$lif}{nsd} = 1;				# No-SNMP-Device IF metric
							$main::dev{$neb{$i}{$n}{'na'}}{ip} = $neb{$i}{$n}{'ip'};
							$main::dev{$neb{$i}{$n}{'na'}}{sn} = "-";
							$main::dev{$neb{$i}{$n}{'na'}}{bi} = "-";
							$main::dev{$neb{$i}{$n}{'na'}}{de} = $neb{$i}{$n}{'de'};
							$main::dev{$neb{$i}{$n}{'na'}}{sv} = $neb{$i}{$n}{'sv'};
							$main::dev{$neb{$i}{$n}{'na'}}{ty} = $neb{$i}{$n}{'ty'};
							$main::dev{$neb{$i}{$n}{'na'}}{os} = "-";
							$main::dev{$neb{$i}{$n}{'na'}}{lo} = $main::dev{$na}{'lo'};
							$main::dev{$neb{$i}{$n}{'na'}}{co} = $main::dev{$na}{'co'};
							$main::dev{$neb{$i}{$n}{'na'}}{vd} = $main::dev{$na}{'vd'};
							$main::dev{$neb{$i}{$n}{'na'}}{vm} = 0;
							if (!$main::dev{$neb{$i}{$n}{'na'}}{fs}){$main::dev{$neb{$i}{$n}{'na'}}{fs} = $main::now}
							$main::dev{$neb{$i}{$n}{'na'}}{ls} = $main::now;
							push (@misc::doneip,$neb{$i}{$n}{'ip'});
							if($neb{$i}{$n}{'ty'} =~ /Aastra IP Phone/){
								if($web::lwpok){
									&web::AastraPhone($neb{$i}{$n}{'na'});
								}
								$main::dev{$neb{$i}{$n}{'na'}}{ty} = "Aastra IP Phone";
								$main::dev{$neb{$i}{$n}{'na'}}{ic} = "phan";
								$main::dev{$neb{$i}{$n}{'na'}}{sv} = 34;
							}elsif($neb{$i}{$n}{'na'} =~ /^AV\w/){
								$main::dev{$neb{$i}{$n}{'na'}}{ty} = "Avaya IP Phone";
								$main::dev{$neb{$i}{$n}{'na'}}{ic} = "phon";
								$main::dev{$neb{$i}{$n}{'na'}}{sv} = 34;
							}elsif($neb{$i}{$n}{'ty'} =~ /Nortel IP Telephone\s*(.*)$/){
								$main::dev{$neb{$i}{$n}{'na'}}{ty} = "Nortel $1";
								$main::dev{$neb{$i}{$n}{'na'}}{ic} = "phon";
								$main::dev{$neb{$i}{$n}{'na'}}{sv} = 34;
							}elsif($neb{$i}{$n}{'ty'} =~ /Cisco IP Phone\s*(.*)$/){
								if($web::lwpok){
									&web::CiscoPhone($neb{$i}{$n}{'na'});
								}
								$main::dev{$neb{$i}{$n}{'na'}}{ty} = "Cisco $1";
								$main::dev{$neb{$i}{$n}{'na'}}{ic} = "phbn";
								$main::dev{$neb{$i}{$n}{'na'}}{sv} = 34;
							}elsif($neb{$i}{$n}{'ty'} =~ /ATA/){
								if($web::lwpok){
									&web::CiscoAta($neb{$i}{$n}{'na'});
								}
								$main::dev{$neb{$i}{$n}{'na'}}{ty} = "Cisco ATA Box";
								$main::dev{$neb{$i}{$n}{'na'}}{ic} = "atbn";
								$main::dev{$neb{$i}{$n}{'na'}}{sv} = 34;
							}elsif($neb{$i}{$n}{'ty'} =~ /(MAP-.*)/){
								$main::dev{$neb{$i}{$n}{'na'}}{ty} = "HP $1";
								$main::dev{$neb{$i}{$n}{'na'}}{ic} = "wagn";
								$main::dev{$neb{$i}{$n}{'na'}}{sv} = 2;
							}elsif($neb{$i}{$n}{'ty'} =~ /AP[\s_]Controlled,(.*),(.*),(.*)$/){
								$main::dev{$neb{$i}{$n}{'na'}}{de} = "HP MSM controlled mode";
								$main::dev{$neb{$i}{$n}{'na'}}{ty} = "HP $2";
								$main::dev{$neb{$i}{$n}{'na'}}{sn} = $1;
								$main::dev{$neb{$i}{$n}{'na'}}{bi} = $3;
								$main::dev{$neb{$i}{$n}{'na'}}{ic} = "wagn";
								$main::dev{$neb{$i}{$n}{'na'}}{sv} = 2;
							}elsif($neb{$i}{$n}{'ty'} =~ /AIR-BR/){
								$main::dev{$neb{$i}{$n}{'na'}}{ty} = "Cisco Wlan Bridge";
								$main::dev{$neb{$i}{$n}{'na'}}{ic} = "wbbn";
								$main::dev{$neb{$i}{$n}{'na'}}{sv} = 2;
							}elsif($neb{$i}{$n}{'ty'} =~ /AIR-/){
								$main::dev{$neb{$i}{$n}{'na'}}{ty} = "Cisco Wlan AP";
								$main::dev{$neb{$i}{$n}{'na'}}{ic} = "wabn";
								$main::dev{$neb{$i}{$n}{'na'}}{sv} = 2;
							}elsif($neb{$i}{$n}{'ty'} =~ /Linksys IP Phone\s*(.*)$/){
								$main::dev{$neb{$i}{$n}{'na'}}{ty} = "Linksys $1";
								$main::dev{$neb{$i}{$n}{'na'}}{ic} = "phbn";
								$main::dev{$neb{$i}{$n}{'na'}}{sv} = 34;
							}elsif($neb{$i}{$n}{'ty'} =~ /Linksys IP Phone\s*(.*)$/){
								$main::dev{$neb{$i}{$n}{'na'}}{ty} = "Linksys $1";
								$main::dev{$neb{$i}{$n}{'na'}}{ic} = "phbn";
								$main::dev{$neb{$i}{$n}{'na'}}{sv} = 34;
							}elsif($neb{$i}{$n}{'ty'} =~ /VMware/){				# Let VMs stay on this link
								$main::dev{$neb{$i}{$n}{'na'}}{ty} = "vSwitch";
								$main::dev{$neb{$i}{$n}{'na'}}{ic} = "v2an";
								$main::dev{$neb{$i}{$n}{'na'}}{sv} = 2;
								$misc::portprop{$na}{$lif}{lnk} = 0;
							}
							&misc::Prt( sprintf ("%-4.4s:No-SNMP TY:%-15.15s SV:%s\n",$neb{$i}{$n}{'dp'},$neb{$i}{$n}{'ty'},$neb{$i}{$n}{'sv'}) );
							&db::WriteDev($neb{$i}{$n}{'na'}) unless $main::opt{'t'};
							$main::link{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{$na}{$lif}{bw} = $main::int{$na}{$i}{spd};
							$main::link{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{$na}{$lif}{ty} = $neb{$i}{$n}{'dp'};
							$main::link{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{$na}{$lif}{de} = "Discovered ".localtime($main::now);
							$main::link{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{$na}{$lif}{du} = $main::int{$na}{$i}{dpx};
							$main::link{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{$na}{$lif}{vl} = $main::int{$na}{$i}{vid};
							&db::WriteLink($neb{$i}{$n}{'na'})if !$main::opt{'t'};
							push (@misc::doneip,$neb{$i}{$n}{'ip'});
							push (@misc::doneid,$neb{$i}{$n}{'id'});
						}elsif($neb{$i}{$n}{'ip'} =~ /^$|^0\.0\.0\.0$|^127\.0\.0/){		# Check only SNMP devs for usable IP
							&misc::Prt( sprintf ("%-4.4s:IP address: %s\n",$neb{$i}{$n}{'dp'},$neb{$i}{$n}{'ip'}) );
							$misc::portprop{$na}{$lif}{nsd} = 1;
							if($misc::notify =~ /d/i){
								&db::Insert('events','level,time,source,info,class,device',"\"100\",\"$main::now\",\"$na\",\"$neb{$i}{$n}{'dp'}:$neb{$i}{$n}{'na'} IP=$neb{$i}{$n}{'ip'} on $neb{$i}{$n}{'if'}\",\"nedi\",\"$na\"");
							}
						}else{
							&misc::Prt( sprintf ("%-4.4s:Queueing of %s ",$neb{$i}{$n}{'dp'},$neb{$i}{$n}{'na'}) );
							$misc::portprop{$na}{$lif}{lnk} = 1;
							$misc::portprop{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{nal} = 1;# Neighbor alive will be used for node IF calculation
							if(grep /^\Q$neb{$i}{$n}{'na'}\E$/,(@misc::doneid,@misc::failid,@misc::todo) or
							   grep /^\Q$neb{$i}{$n}{'ip'}\E$/,(@misc::doneip,@misc::failip) ){# Don't add if done or already queued... (The \Q \E is to prevent interpreting the CDPid as a regexp)
								&misc::Prt("was already done\n");
								$dn++;
							}elsif($main::opt{'p'} and $main::opt{'S'} !~ /s/){			# Only add if protocol discovery set and sysinfo not skipped
								&misc::Prt("is ok\n");
								push (@misc::todo,"$neb{$i}{$n}{'na'}");
								$misc::doip{$neb{$i}{$n}{'na'}} = $neb{$i}{$n}{'ip'};
								$ad++;
							}else{
								&misc::Prt("is not desired\n");
							}
						}
					}
				}
			}
		}
	}
	&misc::Prt("","p$ad/$dn ".($bd?"b$bd ":"") );
}


=head2 FUNCTION Routes()

Get routing table information and queue next hop IPs

B<Options> device name

B<Globals> main::dev, main::int, main::link, misc::portprop, misc::doip

B<Returns> -

=cut
sub Routes {

	my ($na) = @_;
	my ($session, $err, $r);
	my $warn  = my $ad = my $dn = my $bd = 0;
	my $cinhO = "1.3.6.1.2.1.4.24.4.1.4";
	my $rtnhO = "1.3.6.1.2.1.4.21.1.7";

	&misc::Prt("\nRoutes       ------------------------------------------------------------------\n");
	($session, $err) = &Connect($main::dev{$na}{ip}, $main::dev{$na}{rv}, $main::dev{$na}{rc});
	return unless defined $session;

	$r   = $session->get_table($cinhO);
	$err = $session->error;
	if ($err){
		&misc::Prt("ERR :CIDR $err\n","Dr");
		$r   = $session->get_table($rtnhO);							# Fallback to RFC1213
		$err = $session->error;
		if ($err){
			$warn++;
			$session->close;
			&misc::Prt("ERR :RFC $err\n","Dr");
			return 1;
		}
	}
	$session->close;

	while ( (my $key,my $val) =  each(%{$r}) ){
		my $nh = &misc::MapIp($val);								# Map IP if configured...
		&misc::Prt("ROUT:Queueing $nh: ");
		if (!exists $main::net{$na}{$nh} and $nh !~ /^$|0.0.0.0|^127/){				# IP is non-local or 0?
			if(grep /^\Q$nh\E$/,(@misc::doneid,@misc::doneip,@misc::failip,@misc::todo) ){	# Then queue if not done...
				$dn++;
				&misc::Prt("already done\n");
			}elsif($nh =~ /$misc::border/){							# ...or matching the border.
				$bd++;
				&misc::Prt("$nh  matches border /$misc::border/\n");
			}else{
				push (@misc::todo,"$nh");
				$misc::doip{$nh} = $nh;
				&misc::Prt("ok\n");
				$ad++;
			}
		}else{
			&misc::Prt(" is local or unusable\n");
		}
	}
	&misc::Prt(""," r$ad/$dn".($bd?"b$bd":"").($warn)?" ":"\t");
}


=head2 FUNCTION Arp()

Get ARP tables from Layer 3 device and queue entries for OUI discovery, if desired

B<Options> device name

B<Globals> misc::arp, misc::arpn, misc::arpc, misc::portprop, misc::portnew, (misc::doip if opt{o})

B<Returns> -

=cut
sub Arp {

	my ($na) = @_;
	my ($session, $err, $r);
	my (%at, %ntmtab, %myarpc);
	my $warn = my $narp = my $ad = my $dn = my $bd = 0;
	my $NmifO	= '1.3.6.1.2.1.4.22.1.2';
	my $NmfvO	= '1.3.6.1.2.1.4.35.1.4';
	my $ip1=11;
	my $ip2=12;
	my $ip3=13;
	my $ip4=14;

	&misc::Prt("\nArp (SNMP)   ------------------------------------------------------------------\n");
	($session, $err) = &Connect($main::dev{$na}{ip}, $main::dev{$na}{rv}, $main::dev{$na}{rc});
	return unless defined $session;
	$r   = $session->get_table($NmifO);

	if ($main::dev{$na}{os} eq "IOS-fw"){								# tx to rufer
	       $r   = $session->get_table($NmfvO);			       				# fwsm arp table
	       $ip1=13; $ip2=14; $ip3=15; $ip4=16;							# fwsm arp table has ip at index 13-16
	}else{
	       $r   = $session->get_table($NmifO);
	}
	$err = $session->error;
	$session->close;
	if ($err){
		&misc::Prt("ERR :$err\n","Aa");
	}else{
		%at  = %{$r};
	}

	foreach my $k ( keys %at ){
		if ($k !~ /127.0.0/ and defined substr($at{$k},5,1) ){					# complete MAC and not loopback?
			my @i   = split(/\./,$k);
			my $mc   = unpack("H2",substr($at{$k},0,1)).unpack("H2",substr($at{$k},1,1)).unpack("H2",substr($at{$k},2,1)).unpack("H2",substr($at{$k},3,1)).unpack("H2",substr($at{$k},4,1)).unpack("H2",substr($at{$k},5,1));#TODO use H12 instead?
			if ($mc !~/$misc::ignoredmacs/){
				$misc::arp{$mc} = "$i[$ip1].$i[$ip2].$i[$ip3].$i[$ip4]";
				$misc::rarp{"$i[$ip1].$i[$ip2].$i[$ip3].$i[$ip4]"} = $mc;               # will be needed to identify OUI uplinks;TODO remove if not needed?
				$myarpc{$mc}++;								# Find multiple MACs
				$narp++;
				&misc::Prt("ARPS:$mc=$i[$ip1].$i[$ip2].$i[$ip3].$i[$ip4]");
				if( exists $main::int{$na}{$i[10]} ){
					my $vl = 0;
					my $po = $main::int{$na}{$i[10]}{ina};
					$misc::portprop{$na}{$po}{rtr} = 1;
					$misc::portprop{$na}{$po}{pop}++;
					$vl = ($po =~ /^Vl(\d+)$/) ? $1 : "";
					&misc::Prt(" on $po vl$vl");					# print before adding vlid
					$mc .= $mc.$vl if($useivl and $vl =~ /$useivl/); 		# Add vlid to mac
					$misc::portnew{$mc}{$na}{po} = $po;
					$misc::portnew{$mc}{$na}{vl} = $vl;
					if ($main::opt{'o'} and $main::opt{'S'} !~ /[s]/){		# Only add if OUI discovery set and sysinfo not skipped
						my $oui = &misc::GetOui($mc);
						if($oui =~ /$misc::ouidev/i){
							if (grep /\Q$mc\E/,(@misc::doneid,@misc::failid,@misc::todo) ){	# Don't queue if done or queued.
								&misc::Prt(" done already");
								$dn++;
							}elsif ($mc =~ /$misc::border/ or $oui =~ /$misc::border/){	# ...or matching the border...
								&misc::Prt(" matches border /$misc::border/");
								$bd++;
							}elsif ($misc::arp{$mc} eq '0.0.0.0'){		# ...or no IP found
								&misc::Prt(" no IP found");
								if($misc::notify =~ /d/i){
									&db::Insert('events','level,time,source,info,class,device',"\"100\",\"$main::now\",\"$mc\",\"OUI device ($oui) IP=0.0.0.0\",\"nedi\",\"$na\"");
								}
							}else{
								push (@misc::todo,"$mc");
								$misc::doip{$mc} = &misc::MapIp($misc::arp{$mc});
								&misc::Prt(" OUI $oui added");
								$ad++;
							}
						}
					}
				}
				&misc::Prt("\n");
			}
		}
	}

	foreach my $mc ( keys %myarpc ){
		$misc::arpc{$mc} = $myarpc{$mc};
		if(!exists $misc::ifmac{$mc} and $myarpc{$mc} > $misc::arppoison and $misc::notify =~ /n/i){	# Check for ARP poisoning
			&misc::Prt("ARP :$myarpc{$mc} IPs for $mc\n");
			my $fmc = substr($mc,0,4) . "." . substr($mc,4,4) . "." . substr($mc,8,4);
			my $msg = "$myarpc{$mc} IP addresses found for $fmc";
			&db::Insert('events','level,time,source,info,class,device',"\"150\",\"$main::now\",\"$na\",\"$msg\",\"sec\",\"$na\"");
			$misc::mq += &mon::AlertQ("$na: $msg\n","",1,$na) if $misc::notify =~ /N/;
		}
	}
	&misc::Prt("ARPS:$narp ARP entries found\n"," a$narp o$ad/$dn".($bd?"b$bd":"").($warn)?" ":"\t");
}


=head2 FUNCTION MSM2I()

Converts MSM IF type to IEEE types

B<Options> Colubris IF type

B<Globals> -

B<Returns> IEEE type

=cut
sub MSM2I {

	if ($na == 2){
		return 6;
	}elsif ($na == 3){
		return 53;
	}elsif ($na == 4){
		return 209;
	}elsif ($na == 5){
		return 71;
	}else{
		return $na;
	}
}


=head2 FUNCTION BridgeFwd()

Get MAC address table from a device with optional community indexing

B<Options> device name

B<Globals> misc::portprop, misc::portnew

B<Returns> -

=cut
sub BridgeFwd {

	my ($na) = @_;
	my ($session, $err, $r, $ifx);
	my $nspo  = 0;
	my @vlans = ();
	my $fwdxO  = '1.3.6.1.2.1.17.1.4.1.2';
	my $fwdpO  = '1.3.6.1.2.1.17.4.3.1.2';
	my $qbriO  = '1.3.6.1.2.1.17.7.1.2.2.1.2';							# The more recent Qbridge-mib provides vlan of the mac as well.
	my $fwdsO  = '1.3.6.1.2.1.17.5.1.1.1';								# Security table not supported with SNMP (yet)...
	my $m1=11;
	my $m2=12;
	my $m3=13;
	my $m4=14;
	my $m5=15;
	my $m6=16;

	&misc::Prt("\nBridgeFwd (SNMP) --------------------------------------------------------------\n");
	if($misc::sysobj{$main::dev{$na}{so}}{bf} eq "VLX"){
		@vlans = keys %{$main::vlan{$na}};#TODO this is empty with -Ss?
	}else{
		$vlans[0] = "";
	}

	foreach my $vl (@vlans){
		if ($vl !~ /$misc::ignoredvlans/){
			my %fwdix = ();
			my %fwdpo = ();
			my @context = ();								# v3 context handling, tx to P.Spruyt
			my $commcxt = $main::dev{$na}{rc};
			unless($vl eq ""){
				$commcxt = "$main::dev{$na}{rc}\@$vl";					# Add vlan indexing to community for v2
				@context = ( -contextname => "vlan-$vl" );				# Add context, for v3
			}
			if($main::dev{$na}{rv} == 3){
				($session, $err) = &Connect($main::dev{$na}{ip}, $main::dev{$na}{rv}, $main::dev{$na}{rc}, $misc::timeout + 8 );
			}else{
				($session, $err) = &Connect($main::dev{$na}{ip}, $main::dev{$na}{rv}, $commcxt, $misc::timeout + 8 );
				@context = ();								# Clear it again for v2
			}
			if($misc::sysobj{$main::dev{$na}{so}}{bf} =~ /^qbri/){
				&misc::Prt("FWDS:Walking Q-BridgeFwd, reassign indexes\n");
				$r = $session->get_table(-baseoid => $qbriO, @context);#TODO context not needed, always empty?
				$m1=14; $m2=15; $m3=16; $m4=17; $m5=18; $m6=19;#TODO check additional OIDs on Extreme..
			}else{
				&misc::Prt("FWDS:Walking BridgeFwd\n");
				$r = $session->get_table(-baseoid => $fwdpO, @context);
			}

			$err = $session->error;
			if ($err){&misc::Prt("ERR :Fp$vl $err\n","Fp$vl")}else{%fwdpo = %{$r} }

			if($misc::sysobj{$main::dev{$na}{so}}{bf} =~ /X$/){
				&misc::Prt("FWDS:Walking FWD Port to IF index\n");
				$r = $session->get_table(-baseoid => $fwdxO, @context);
				$err = $session->error;
				if ($err){&misc::Prt("ERR :Fx$vl $err\n","Fx$vl")}else{%fwdix = %{$r} }
			}
			$session->close;

			foreach my $fpo ( keys %fwdpo ){
				my @dmac = split(/\./,$fpo);
				if (defined $dmac[$m6]){								# Ignore incomplete MACs!
					my $mc   = sprintf "%02x%02x%02x%02x%02x%02x",$dmac[$m1],$dmac[$m2],$dmac[$m3],$dmac[$m4],$dmac[$m5],$dmac[$m6];
					if($mc !~ /$misc::ignoredmacs/){
						if($misc::sysobj{$main::dev{$na}{so}}{bf} =~ /X$/){
							$ifx  = $fwdix{"$fwdxO.$fwdpo{$fpo}"};
						}else{
							$ifx  = $fwdpo{$fpo};
						}
						if (defined $ifx){
							if (defined $main::int{$na}{$ifx}){
								if($mc ne $main::int{$na}{$ifx}{mac}){			# Cisco's 3500XL do that! (can't be caught before IFX is found)
									my $po   = $main::int{$na}{$ifx}{ina};
									if($misc::sysobj{$main::dev{$na}{so}}{bf} =~ /^normal/){
										$vl = $misc::portprop{$na}{$po}{vid};
									}elsif($misc::sysobj{$main::dev{$na}{so}}{bf} =~ /^qbri/){

										$vl = $dmac[13];			# Vlanid in Qbridge MIB
									}
									&misc::Prt("FWDS:$mc on $po Vl$vl $dmac[11] $dmac[12]\n");		# Print before adding vlid
									$mc .= $vl if($misc::useivl and $vl =~ /$misc::useivl/);# Add vlid to mac
									$misc::portnew{$mc}{$na}{vl} = $vl;
									$misc::portnew{$mc}{$na}{po} = $po;
									$misc::portprop{$na}{$po}{pop}++;
									$nspo++;

									if(exists $misc::ifmac{$mc}){
										&misc::Prt("LINK:Seeing ".join(", ",keys %{$misc::ifmac{$mc}})." on $po\n");
										$misc::portprop{$na}{$po}{lnk} = 1;
										#TODO optimize MAC links ->
										$main::int{$na}{$ifx}{com} .= " MAC:".join(", ",keys %{$misc::ifmac{$mc}}) if $main::int{$na}{$ifx}{com} !~ /^ (C|F|LL|N)DP:/;
									}
								}
							}else{
								&misc::Prt("FWDS:$mc no IFname for index $ifx\n");
							}
						}else{
							&misc::Prt("FWDS:$mc no IFindex ($fwdpo{$fpo})\n");# Happens for switch's own MAC
						}
					}
				}
			}
		}
	}
	&misc::Prt("FWDS:$nspo bridge forwarding entries found\n","f$nspo");
}

=head2 FUNCTION CAPFwd()

Get MAC address table and SNR of Wlan clients from Cisco APs

B<Options> device name

B<Globals> misc::portprop, misc::portnew

B<Returns> -

=cut
sub CAPFwd {

	my ($na) = @_;
	my ($session, $err, $r, $ifx);
	my $nspo  = 0;
	my @vlans = ();
	my $snrO = '1.3.6.1.4.1.9.9.273.1.3.1.1.4';

	&misc::Prt("\nCAPFwd ------------------------------------------------------------------------\n");
	my %snr = ();

	($session, $err) = &Connect($main::dev{$na}{ip}, $main::dev{$na}{rv}, $main::dev{$na}{rc});
	return unless defined $session;

	$r   = $session->get_table("$snrO");
	$err = $session->error;
	if($err){
		&misc::Prt("ERR :$err\n","Fw2");
	}else{
		%snr = %{$r};
	}
	$session->close;

	unless($err){
		foreach my $k ( keys %snr ){
			my @i = split(/\./,$k);
			my $n = @i;
	 		my $po = $main::int{$na}{$i[14]}{ina};
			my $mc = sprintf("%2.2x%2.2x%2.2x%2.2x%2.2x%2.2x",$i[$n-6],$i[$n-5],$i[$n-4],$i[$n-3],$i[$n-2],$i[$n-1]);
			my $id = "";
			for ($c = 16; $c < ($n-6); $c++){
				$id .= chr($i[$c]);
			}			
			$misc::portnew{$mc}{$na}{vl} = $misc::vlid{$na}{$id};
			$misc::portnew{$mc}{$na}{po} = $po;
			$misc::portnew{$mc}{$na}{snr} = $snr{$k};

			$misc::portprop{$na}{$po}{pop}++;
			&misc::Prt("CAPF:$mc on $po ($i[14]) SNR:$snr{$k} SSID:$id\n");
			$nspo++;
		}
		&misc::Prt("","f$nspo");
	}
}

=head2 FUNCTION MSMFwd()

Get MAC address table and SNR of Wlan clients from HP MSM Controller

B<Options> device name

B<Globals> misc::portprop, misc::portnew

B<Returns> -

=cut
sub MSMFwd {

	my ($na) = @_;
	my ($session, $err, $r, $ifx);
	my $nspo  = 0;
	my @vlans = ();
	my $msmixO = '1.3.6.1.4.1.8744.5.25.1.2.1.1.2';							# if to wireless-if index
	my $msmclO = '1.3.6.1.4.1.8744.5.25.1.7.1.1';							# 2=mac,3=vsc,7=snr,17=ip

	&misc::Prt("\nMSMFwd ------------------------------------------------------------------------\n");
	my %ix = ();
	my %wm = ();
	my %wv = ();
	my %ws = ();
	my %wa = ();

	($session, $err) = &Connect($main::dev{$na}{ip}, $main::dev{$na}{rv}, $main::dev{$na}{rc});
	return unless defined $session;

	$r   = $session->get_table("$msmclO.2");
	$err = $session->error;
	if($err){
		&misc::Prt("ERR :$err\n","Fw2");
	}else{
		%wm = %{$r};
	}

	$r   = $session->get_table("$msmclO.3");
	$err = $session->error;
	if($err){
		&misc::Prt("ERR :$err\n","Fw3");
	}else{
		%wv = %{$r};
	}

	$r   = $session->get_table("$msmclO.7");
	$err = $session->error;
	if($err){
		&misc::Prt("ERR :$err\n","Fw7");
	}else{
		%ws = %{$r};
	}

	$r   = $session->get_table("$msmclO.17");
	$err = $session->error;
	if($err){
		&misc::Prt("ERR :$err\n","Fw17");
	}else{
		%wa = %{$r};
	}

#	$r   = $session->get_table("$msmixO");
#	$err = $session->error;
#	if($err){
#		&misc::Prt("ERR :$err\n","FwX");
#	}else{
#		%ix = %{$r};
#	}
	$session->close;

	unless($err){
		foreach my $k ( keys %wm ){
			my $mc   = unpack("H2",substr($wm{$k},0,1)).unpack("H2",substr($wm{$k},1,1)).unpack("H2",substr($wm{$k},2,1)).unpack("H2",substr($wm{$k},3,1)).unpack("H2",substr($wm{$k},4,1)).unpack("H2",substr($wm{$k},5,1));#TODO use H12 instead?
			my @i = split(/\./,$k);
			my $ifx = 32000 + $i[14] * 10 + $i[15] + 2;#$ix{"$msmixO.$i[14].$i[15]"};TODO find real IFidx! -> Use IFalias & 1.3.6.1.4.1.8744.5.24.1.1.1.1.2.3?
			my $po  = $main::int{$na}{$ifx}{ina};
			$misc::portnew{$mc}{$na}{vl} = $wv{"$msmclO.3.$i[14].$i[15].$i[16]"};
			$misc::portnew{$mc}{$na}{po} = $po;
			$misc::portnew{$mc}{$na}{snr} = $ws{"$msmclO.7.$i[14].$i[15].$i[16]"}; 	# tx HB9DDO
			$misc::portprop{$na}{$po}{pop}++;
			$misc::arp{$mc} = $wa{"$msmclO.17.$i[14].$i[15].$i[16]"};
			&misc::Prt("MSMF:$mc IP:$misc::arp{$mc} on $po ($ifx) SNR:$misc::portnew{$mc}{$na}{snr} VSC:$misc::portnew{$mc}{$na}{vl}\n");
			$nspo++;
		}
		&misc::Prt("","f$nspo");
	}
}

=head2 FUNCTION Modules()

Get module list according to .def file

In verbose mode, lines starting with MODA: indicate entries which are
recognized and added as modules.

B<Options> device name

B<Globals> main::mod

B<Returns> -

=cut
sub Modules {

	my ($na) = @_;
	my ($session, $err, $r);
	my (%mde, %mcl, %msl, %mhw, %msw, %mfw, %msn, %mmo);
	my $warn = my $nmod = 0;
	my $so	 = $main::dev{$na}{so};
	my $maxmsg = ($main::dev{$na}{os} eq "NXOS")?"5500":"4095";					# TODO implement properly, if other OS cause problems too

	&misc::Prt("\nModules      ------------------------------------------------------------------\n");
	($session, $err) = &Connect($main::dev{$na}{ip}, $main::dev{$na}{rv}, $main::dev{$na}{rc}, '', $maxmsg);
	return unless defined $session;

	$session->translate(1);										# Needed for some devs returning HEX-SNs/MACs
	$r = $session->get_table($misc::sysobj{$so}{mt});						# Walk slot/supplyclass
	$err = $session->error;
	if ($err){
		&misc::Prt("ERR :Slot $err\n","Mt");
		$warn++;
	}else{
		%msl  = %{$r};
		if($misc::sysobj{$so}{md}){
			$r = $session->get_table($misc::sysobj{$so}{md});				# Walk module description
			$err = $session->error;
			if ($err){&misc::Prt("ERR :Desc $err\n","Md");return 1;}else{%mde  = %{$r}}
		}
		if($misc::sysobj{$so}{mc}){
			$r = $session->get_table($misc::sysobj{$so}{mc});				# Walk module classes
			$err = $session->error;
			if ($err){&misc::Prt("ERR :Class $err\n","Mc");$warn++}else{%mcl  = %{$r}}
		}
		if($misc::sysobj{$so}{mh}){
			$r = $session->get_table($misc::sysobj{$so}{mh});				# Walk module HW/supply capacity
			$err = $session->error;
			if ($err){&misc::Prt("ERR :HW $err\n","Mh");$warn++}else{%mhw  = %{$r}}
		}
		if($misc::sysobj{$so}{ms}){
			$r = $session->get_table($misc::sysobj{$so}{ms});				# Walk module software version
			$err = $session->error;
			if ($err){&misc::Prt("ERR :SW $err\n","Ms");$warn++}else{%msw  = %{$r}}
		}
		if($misc::sysobj{$so}{mf}){
			$r = $session->get_table($misc::sysobj{$so}{mf});				# Walk module FW/supply level
			$err = $session->error;
			if ($err){&misc::Prt("ERR :FW $err\n","Mf");$warn++}else{%mfw  = %{$r}}
		}
		if($misc::sysobj{$so}{mn}){
			$r = $session->get_table($misc::sysobj{$so}{mn});				# Walk module serial number
			$err = $session->error;
			if ($err){&misc::Prt("ERR :SN $err\n","Mn");$warn++}else{%msn  = %{$r}}
		}
		if($misc::sysobj{$so}{mm}){
			$r = $session->get_table($misc::sysobj{$so}{mm});				# Walk module model
			$err = $session->error;
			if ($err){&misc::Prt("ERR :Model $err\n","Mm");$warn++}else{%mmo  = %{$r}}
		}
	}
	$session->close;

	foreach my $i ( keys %msl ){
		my $nomod = "no class";
		my $s = $msl{$i};
		$i =~ s/$misc::sysobj{$so}{mt}\.//;							# Cut common part and use rest as index
		if (exists $mcl{"$misc::sysobj{$so}{mc}.$i"}){
			if($mcl{"$misc::sysobj{$so}{mc}.$i"} =~ /$misc::sysobj{$so}{mv}/){
				if($mcl{"$misc::sysobj{$so}{mc}.$i"} == 10){				# Class 10 and a SN is most likely a transceiver
					$nomod = "" if $msn{"$misc::sysobj{$so}{mn}.$i"};
				}else{
					$nomod = "";
				}
			}else{
				$nomod = "class " . $mcl{"$misc::sysobj{$so}{mc}.$i"}." no match with /$misc::sysobj{$so}{mv}/";
			}
		}else{
			$nomod = "";
		}
		my $modl = &misc::Strip($mmo{"$misc::sysobj{$so}{mm}.$i"});
		my $mdes = &misc::Strip($mde{"$misc::sysobj{$so}{md}.$i"});
		if(!$nomod and ($modl or $mdes) ){							# Only add if model or describtion exists
			$main::mod{$na}{$i}{sl} = &misc::Strip( substr($s,0,63) );
			$main::mod{$na}{$i}{de} = $mdes;
			$main::mod{$na}{$i}{sn} = &misc::Strip($msn{"$misc::sysobj{$so}{mn}.$i"});
			if ($main::dev{$na}{os} eq "Printer"){
				$main::mod{$na}{$i}{hw} = ($mfw{"$misc::sysobj{$so}{mf}.$i"})?int(100*$mhw{"$misc::sysobj{$so}{mh}.$i"} / $mfw{"$misc::sysobj{$so}{mf}.$i"}):"0";
				$main::mod{$na}{$i}{mo} = "Printsupply";
			}else{ #TODO needed or less errors without? -> if ($mmo{"$misc::sysobj{$so}{mm}.$i"} or $mde{"$misc::sysobj{$so}{md}.$i"}){
				my $sern = &misc::Strip($msn{"$misc::sysobj{$so}{mn}.$i"});
				if ($main::dev{$na}{os} eq "ESX"){
					$main::mod{$na}{$i}{mo} = "VM-ESX";
				}else{
					$main::mod{$na}{$i}{mo} = ($modl ne $sern)?$modl:"";		# Some transceivers report serial as model (rufer)
				}
				$main::mod{$na}{$i}{hw} = &misc::Strip($mhw{"$misc::sysobj{$so}{mh}.$i"});
				$main::mod{$na}{$i}{fw} = &misc::Strip($mfw{"$misc::sysobj{$so}{mf}.$i"});
				$main::mod{$na}{$i}{sw} = &misc::Strip($msw{"$misc::sysobj{$so}{ms}.$i"});
			}
			&misc::Prt("MODA:$i-$s\t$main::mod{$na}{$i}{mo}\t$main::mod{$na}{$i}{de} $main::mod{$na}{$i}{sn}\n");
			$nmod++;
		}else{
			&misc::Prt("MOD :$i $s $nomod or no info\n");
		}
	}
	&misc::Prt("","m$nmod".($warn)?" ":"\t");
}

1;
