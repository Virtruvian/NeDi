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

maxmessagesize: Nexus needs 5500 according to whitehatguy, but foundry turboiron fragments FDP OIDs and responses 
are ignored from a extreme x450 stack, so it'll only be changed on demand! 3.2013 found that certain N7K fail on
IF and modules, thus decreased to 4096...

B<Options> IP address, version, community, timeout, [maxmessagesize]

B<Globals> -

B<Returns> session, error

=cut
sub Connect {

	my ($ip, $ver, $comm, $tout, $maxms) = @_;

	$tout  = ($tout)?$tout:$misc::timeout;
	$maxms = ($maxms)? $maxms:1472;

	if($ver == 3) {
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

	my ($id, $skip) = @_;
	my ($ver, $comm, $wver, $wcomm, $session, $err, $r, $na);
	my $sysO = '1.3.6.1.2.1.1.2.0';
	my $conO = '1.3.6.1.2.1.1.4.0';
	my $namO = '1.3.6.1.2.1.1.5.0';
	my $locO = '1.3.6.1.2.1.1.6.0';
	my $srvO = '1.3.6.1.2.1.1.7.0';
	my $ip	 = $misc::doip{$id};

	&misc::Prt("\nIdentify $id -------------------------------------------------------\n");

	if($ip =~ /^$|0.0.0.0|^127/){
		&mon::Event('d',100,'nedn',$id,'',"Unusable IP $ip using $misc::seedlist");
		&misc::Prt('',"Unusable IP\t\t\t");
		return '';
	}elsif( grep /^\Q$ip}\E$/,(@misc::doneip,@misc::failip) ){
		&misc::Prt("IDNT:$id, $ip done already\n","Done already\t\t\t");
	}elsif($ip !~ /$misc::netfilter/){
		&mon::Event('d',50,'nedn',$id,'',"IP $ip not matching netfilter $misc::netfilter");
		&misc::Prt('',"Not matching netfilter $misc::netfilter\t");
		return '';
	}

	my @comms = @misc::comms;									# Build Community list, preferring existing
	unshift(@comms,$misc::seedini{$ip}{rc}) if $misc::seedini{$ip}{rc};
	do{
		$comm = shift (@comms);
		if($misc::comms{$comm}{aprot}){								# Force v3, if auth proto is set!
			$ver = 3;
		}elsif(exists $misc::seedini{$ip}{rv} and $misc::seedini{$ip}{rv} =~ /[0-9]/){		# Set SNMP version, preferring existing
			$ver  = $misc::seedini{$ip}{rv};
			if($ver == 0){
				&mon::Event('d',100,'nedn',$id,'',"IP $ip belongs to nosnmpdev $misc::seedini{$ip}{na}, not replacing");
				&misc::Prt('',"IP of ID $id belongs to nosnmpdev $misc::seedini{$ip}{na}\t");
				return '';
			}
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
		$na = '';
		&mon::Event('d',100,'nedn',$id,'',"No response from IP $ip using $misc::seedlist");	# Including seedlist, based on raider82's idea
		&misc::Prt('',"No response from $id\t\t");
	}else{
		$na = &misc::Strip($r->{$namO});
		if(exists $misc::map{$ip}{na}){
			$na = $misc::map{$ip}{na};
			&misc::Prt("MAPN:Name mapped to $misc::map{$ip}{na}\n","Mn");
		}else{
			if($na =~ /^\s*$/){								# Catch really bad SNMP implementations
				&misc::Prt("IDNT:No name using IP $ip\n","Sn");
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
		}
		&misc::ReadSysobj($so);

		my $desO = ($misc::sysobj{$so}{de})?$misc::sysobj{$so}{de}:'1.3.6.1.2.1.1.1.0';		# Use sysdesc OID from .def, if set
		$r = $session->get_request($desO);
		$err = $session->error;
		my $de = "err";
		if(!$err and defined $r->{$desO}){$de = &misc::Strip($r->{$desO});}
		if($de =~ /$misc::descfilter/){								# Only define device, if not filtered
			$session->close;
			&mon::Event('d',50,'nedn',$id,'',"Description $de matches descfilter $misc::descfilter");
			&misc::Prt('',"Descfilter $misc::descfilter\t");
			return;
		}else{
			if(exists $main::dev{$na}){
				if($main::dev{$na}{so} ne $so){
					$misc::mq += &mon::Event('S',150,'neds',$na,$na,"Sysobjid changed from $main::dev{$na}{so} to $so");
				}
			}else{
				$main::dev{$na}{sn} = "";
				$main::dev{$na}{bi} = "";
				$main::dev{$na}{cpu} = 0;
				$main::dev{$na}{mcp} = 0;
				$main::dev{$na}{tmp} = 0;
				$main::dev{$na}{cuv} = 0;
				$main::dev{$na}{fs} = $main::now;
				$misc::mq += &mon::Event('D',100,'nedd',$na,$na,"New Device with ID $id and IP $ip found");
			}

			if($main::dev{$na}{fs} == $main::now or $main::opt{W}){
				$main::dev{$na}{rv} = $ver;						# Only set SNMP readversion upon 1st or -W to avoid v1 fallback in case of communication problems!
				if($misc::snmpwrite){							# Write access enabled?
					my $woid = '1.3.6.1.2.1.11.30.0';				# Use snmpEnableAuthenTraps to check write access...
					$r  = $session->get_request($woid);
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
											&misc::Prt("ERR :Writetest $err\n","Sw");
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
			if($misc::sysobj{$so}{al} eq "1.3.6.1.2.1.31.1.1.1.18"){			# Regular IFalias OID supported in GUI
				$main::dev{$na}{opt} = "A";
			}else{
				$main::dev{$na}{opt} = "-";
			}
			if($misc::sysobj{$so}{cpu}){							# Device has CPU OID show graph
				$main::dev{$na}{opt} .= "C";
			}else{
				$main::dev{$na}{opt} .= "-";
			}
			$main::dev{$na}{opt} .= $misc::sysobj{$so}{pm};					# Assign POWER-ETHERNET-MIB support
			$main::dev{$na}{so} = $so;
			$main::dev{$na}{ls} = $main::now;
			$main::dev{$na}{ty} = $misc::sysobj{$so}{ty};
			$main::dev{$na}{ip} = $ip;
			$main::dev{$na}{oi} = $ip;
			$main::dev{$na}{rc} = $comm;
			$main::dev{$na}{de} = $de;
			$main::dev{$na}{os} = $misc::sysobj{$so}{os};
			$main::dev{$na}{ic} = $misc::sysobj{$so}{ic};
			$main::dev{$na}{hc} = $misc::sysobj{$so}{hc};
			$main::dev{$na}{siz}= $misc::sysobj{$so}{sz};
			$main::dev{$na}{cul}= $misc::sysobj{$so}{cul};

			if($skip !~ /s/ or $main::dev{$na}{fs} == $main::now){				# Only skip if desired and dev not new...

				$r = $session->get_request($conO);
				$err = $session->error;
				if($err){
					$main::dev{$na}{co} = "err";
					&misc::Prt("ERR :$err\n","Sc");
				}else{
					$main::dev{$na}{co} = &misc::Strip($r->{$conO});
				}
				&misc::Prt("SYS :Con=$main::dev{$na}{co} ");

				if(exists $misc::map{$ip}{lo}){
					$main::dev{$na}{lo} = $misc::map{$ip}{lo};
					&misc::Prt("MapLo=$main::dev{$na}{lo}\n","Ml");
				}else{
					$r = $session->get_request($locO);
					$err = $session->error;
					if($err){
						$main::dev{$na}{lo} = "err";
						&misc::Prt("ERR :$err\n","Sl");
					}else{
						$main::dev{$na}{lo} = &misc::Strip($r->{$locO});
						$main::dev{$na}{lo} =~ s/^$/-$misc::locsep-$misc::locsep-/;
					}
					&misc::Prt("Loc=$main::dev{$na}{lo}\n");
				}

				$r = $session->get_request($srvO);
				$err = $session->error;
				if($err or $r->{$srvO} !~ /^\d+$/){
					&misc::Prt("ERR :SysServices $err\n","Sv");
					$main::dev{$na}{sv} = 6; 					# Could be a buggy SNMP implementation, so we set this to 6 and check the device anyway
				}else{
					$main::dev{$na}{sv} = &misc::Strip($r->{$srvO},6);
				}
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
	return 1 if $skip =~ /s/ and $skip =~ /g/ and $main::dev{$na}{fs} != $main::now;

	($session, $err) = &Connect($main::dev{$na}{ip}, $main::dev{$na}{rv}, $main::dev{$na}{rc});
	return 1 unless defined $session;

	if($skip !~ /s/ or $main::dev{$na}{fs} == $main::now){
		if($misc::sysobj{$so}{sn}){
			my $trans = $session->translate();
			$session->translate(1);								# Needed for some devs returning HEX-SNs
			$r  = $session->get_request($misc::sysobj{$so}{sn});
			$err = $session->error;
			$session->translate($trans);
			if($err){
				$main::dev{$na}{sn} = "err";
				&misc::Prt("ERR :$err\n","S#");
			}else{
				my $sn = substr(&misc::Strip($r->{$misc::sysobj{$so}{sn}}),0,31);
				$sn = 'err' if $sn eq "noSuchObject";
				if($main::dev{$na}{sn} and $main::dev{$na}{sn} ne $sn){
					$misc::mq += &mon::Event('S',150,'neds',$na,$na,"Serial number changed from $main::dev{$na}{sn} to $sn");
				}else{
					&misc::Prt("SERN:Serial number is $sn\n");
				}
				$main::dev{$na}{sn} = $sn;
			}
		}else{
			$main::dev{$na}{sn} = "-";
		}

		if($misc::sysobj{$so}{bi}){
			$r   = $session->get_request($misc::sysobj{$so}{bi});
			$err = $session->error;
			if($err){
				$main::dev{$na}{bi} = "err";
				&misc::Prt("ERR :$err\n","Sb");
			}else{
				my $bimg = &misc::Strip($r->{$misc::sysobj{$so}{bi}});
				$bimg =~ s/^flash:|^bootflash:|^slot[0-9]:|^sup-boot(flash|disk):|^disk0:|FIRMWARE REVISION: //;
				$bimg =~ s/.*\/(.*)/$1/;
				my $bi = substr($bimg,0,63);
				if($main::dev{$na}{bi} and $main::dev{$na}{bi} ne $bi){
					$misc::mq += &mon::Event('S',150,'neds',$na,$na,"Bootimage changed from $main::dev{$na}{bi} to $bi");
				}else{
					&misc::Prt("BOOT:Image is $main::dev{$na}{bi}\n");
				}
				$main::dev{$na}{bi} = $bi;
			}
		}else{
			$main::dev{$na}{bi} = "-";
		}

		if($misc::sysobj{$so}{dg}){
			$r   = $session->get_request($misc::sysobj{$so}{dg});
			$err = $session->error;
			if($err){
				&misc::Prt("ERR :Device Group, $err\n","Sg");
				$main::dev{$na}{dg} = "?";
			}else{
				$main::dev{$na}{dg} = &misc::Strip($r->{$misc::sysobj{$so}{dg}});
				&misc::Prt("GRP :Group is $main::dev{$na}{dg}\n");
			}
		}else{
			$main::dev{$na}{dg} = "-";
		}
		if($misc::sysobj{$so}{dm}){
			$r   = $session->get_request($misc::sysobj{$so}{dm});
			$err = $session->error;
			if($err or $r->{$misc::sysobj{$so}{dm}} !~ /^[1-3]+$/){
				&misc::Prt("ERR :Mode, $err\n","So");
				$main::dev{$na}{dm} = 9;
			}else{
				$main::dev{$na}{dm} = $r->{$misc::sysobj{$so}{dm}};
				&misc::Prt("GRP :Mode is $main::dev{$na}{dm}\n");
			}
		}else{
			$main::dev{$na}{dm} = 0;
		}

		if($misc::sysobj{$so}{pm} ne '-'){
			my %mpar = ();
			$r   = $session->get_table('1.3.6.1.2.1.105.1.3.1.1.2');			# Get pethMainPsePower
			$err = $session->error;
			if($err){
				&misc::Prt("ERR :Max PoE, $err\n","Sw");
				$main::dev{$na}{mpw} = 1;
			}else{
				while( my($key, $val) = each(%{$r}) ) {
					my $x = substr($key,rindex($key,'.') + 1);
					$mpar{$x} = &misc::Strip($val,0);
				}
				my $mpw = 0;
				$mpw += $_ for (values %mpar);
				if($main::dev{$na}{mpw} and $main::dev{$na}{mpw} != $mpw){
					$misc::mq += &mon::Event('S',150,'nedp',$na,$na,"Max available power of ${mpw}W changed from $main::dev{$na}{mpw}W");
				}
				$main::dev{$na}{mpw} = $mpw;
				&misc::Prt("PWR :Max available power is $main::dev{$na}{mpw}W\n");
			}
			$r   = $session->get_table('1.3.6.1.2.1.105.1.3.1.1.4');			# Get pethMainPseConsumptionPower
			$err = $session->error;
			if($err){
				&misc::Prt("ERR :Total PoE, $err\n","Sw");
				$main::dev{$na}{tpw} = 1;
			}else{
				$main::dev{$na}{tpw} = my $tpw = 0;
				while( my($key, $val) = each(%{$r}) ) {
					my $x = substr($key,rindex($key,'.') + 1);
					$tpw = &misc::Strip($val,0);
					$main::dev{$na}{tpw} += $tpw;
					my $rtpoe = ($mpar{$x})?int($tpw/$mpar{$x}*100):0;
					if($rtpoe > $misc::poew){
						$misc::mq += &mon::Event('S',150,'nedp',$na,$na,"Total PoE load of $rtpoe% on PSE$x exceeds warning threshold of ${misc::poew}%");
					}
				}
				&misc::Prt("PWR :Total used power is $main::dev{$na}{tpw}W\n");
				
			}
		}else{
			$main::dev{$na}{mpw} = 0;
			$main::dev{$na}{tpw} = 0;
		}

		if($misc::sysobj{$so}{vn}){
			$r = $session->get_table($misc::sysobj{$so}{vn});				# Get Vlan names
			$err = $session->error;
			if($err){
				&misc::Prt("ERR :Vlans, $err\n","Sv");
			}else{
				my %vna  = %{$r};
				my %vnx  = ();
				if($misc::sysobj{$so}{vl}){
					$r = $session->get_table($misc::sysobj{$so}{vl});		# Get Vlan name to id index (e.g. Extreme)
					$err = $session->error;
					if($err){
						&misc::Prt("ERR :VlanIX, $err\n","Sv");
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
							&misc::Prt("VLAN:No numeric vlid: $vl","Sv");
						}
						$nv++;
					}
					&misc::Prt(""," v$nv ");
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

	if($skip !~ /g/ or $main::dev{$na}{fs} == $main::now){
		if($misc::sysobj{$so}{cpu}){
			$r  = $session->get_request($misc::sysobj{$so}{cpu});
			$err = $session->error;
			if($err or $r->{$misc::sysobj{$so}{cpu}} !~ /^[0-9]+$/){
				&misc::Prt("ERR :CPU ".(($err)?$err:$r->{$misc::sysobj{$so}{cpu}}." is not numeric")."\n","Su");
				$main::dev{$na}{cpu} = 0;
			}else{
				$main::dev{$na}{cpu} = &misc::Strip($r->{$misc::sysobj{$so}{cpu}},0);
				if($main::dev{$na}{cpu} > $misc::cpua){
					$misc::mq += &mon::Event('S',200,'nedc',$na,$na,"CPU load of $main::dev{$na}{cpu}% exceeds alert threshold of ${misc::cpua}%");
				}else{
					&misc::Prt("CPU :Load is $main::dev{$na}{cpu}%\n");
				}
			}
		}else{
			$main::dev{$na}{cpu} = 0;
		}

		if($misc::sysobj{$so}{mem}){
			$r  = $session->get_request($misc::sysobj{$so}{mem});
			$err = $session->error;
			if($err or $r->{$misc::sysobj{$so}{mem}} !~ /^[0-9]+$/){
				&misc::Prt("ERR :Mem ".(($err)?$err:$r->{$misc::sysobj{$so}{mem}}." is not numeric")."\n","Sm");
				$main::dev{$na}{mcp} = 0;
			}else{
				my $mem = &misc::Strip($r->{$misc::sysobj{$so}{mem}});
				my $al  = "";
				my @mal = split(/\//,$misc::mema);
				my $msg = "Available memory ";
				if($misc::sysobj{$so}{mmu} eq "-%"){
					$main::dev{$na}{mcp} = 100 - $mem;
					$al = "is below threshold of $mal[1]%" if $main::dev{$na}{mcp} < $mal[1];
					$msg .= "$main::dev{$na}{mcp}% $al";
				}elsif($misc::sysobj{$so}{mmu} eq "%"){
					$main::dev{$na}{mcp} = $mem;
					$al = "is below threshold of $mal[1]%" if $main::dev{$na}{mcp} < $mal[1];
					$msg .= "$main::dev{$na}{mcp}% $al";
				}else{
					$main::dev{$na}{mcp} = int($mem * $misc::sysobj{$so}{mmu});
					$al = "is below threshold of $mal[0] KBytes" if $main::dev{$na}{mcp} < $mal[0]*1024;
					$msg .= int($main::dev{$na}{mcp}/1024)." KBytes $al";
				}
				if($al){
					$misc::mq += &mon::Event('S',200,'nedm',$na,$na,$msg);
				}else{
					&misc::Prt("MEM :$msg\n");
				}
			}
		}else{
			$main::dev{$na}{mcp} = 0;
		}

		if($misc::sysobj{$so}{tmp}){
			$r  = $session->get_request($misc::sysobj{$so}{tmp});
			$err = $session->error;
			if($err or $r->{$misc::sysobj{$so}{tmp}} !~ /^[0-9]+$/){
				&misc::Prt("ERR :Temp, $err\n","St");
				$main::dev{$na}{tmp} = 0;
			}else{
				$main::dev{$na}{tmp} = int(&misc::Strip($r->{$misc::sysobj{$so}{tmp}}) * $misc::sysobj{$so}{tmu});
				if($main::dev{$na}{tmp} > $misc::tmpa){
					$misc::mq += &mon::Event('S',200,'nedt',$na,$na,"Temperature of $main::dev{$na}{tmp}C exceeds alert threshold of ${misc::tmpa}C");
				}else{
					&misc::Prt("TEMP:Temperature is $main::dev{$na}{tmp} Degrees Celcius\n");
				}
			}
		}else{
			$main::dev{$na}{tmp} = 0;
		}

		if($misc::sysobj{$so}{cuv}){
			$r  = $session->get_request($misc::sysobj{$so}{cuv});
			$err = $session->error;
			if($err or $r->{$misc::sysobj{$so}{cuv}} !~ /^[0-9]+$/){
				&misc::Prt("ERR :Custom, $err\n","Ss");
				$main::dev{$na}{cuv} = 0;
			}else{
				$main::dev{$na}{cuv} = $r->{$misc::sysobj{$so}{cuv}};
				&misc::Prt("CUS :$main::dev{$na}{cul} = $main::dev{$na}{cuv}\n");
			}
		}else{
			$main::dev{$na}{cuv} = 0;
		}
	}
	$session->close;

	return 0;
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
	my (%ifal, %ifax, %alias, %ifvl, %ifvx, %pvid, %ifbr, %ifidi, %ifodi, %ifdp, %ifdx, %duplex, %usedoid);
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
	my $ifpwrO = $misc::sysobj{$so}{pw};
	my $ifpwxO = $misc::sysobj{$so}{px};

	my @range = ();
	my @maxrep = ($main::dev{$na}{rv} == 2)?( -maxrepetitions  => 5 ):();				# Bulkwalk, hopefully without fragmented UDP
	if($misc::sysobj{$so}{st} and $misc::sysobj{$so}{en}){
		@range = ( -startindex => $misc::sysobj{$so}{st}, -endindex   => $misc::sysobj{$so}{en} );
	}

	&misc::Prt("\nInterfaces   ------------------------------------------------------------------\n");

	my $noifwrite = 0;
	&db::ReadInt("device = \"$na\"");
	my $walkinf = ($skip !~ /i/ or $main::dev{$na}{fs} == $main::now)?1:0;
	if(!$walkinf and $skip =~ /t/ and $skip =~ /e/ and $skip =~ /d/ and $skip =~ /b/ and $skip =~ /w/ and $skip =~ /A/ and $skip =~ /O/){	# Don't create session, if everything's skipped
		&misc::Prt("IF  :Skipping all IF data, no write\n");
		$noifwrite = 1;
	}else{
		($session, $err) = &Connect($main::dev{$na}{ip}, $main::dev{$na}{rv}, $main::dev{$na}{rc}, $misc::timeout + 3);
		return 1 unless defined $session;
	}

	if($main::dev{$na}{hc} & 128){									# Walk interface HC if set
		if($walkinf){
			&misc::Prt("IF  :Walking high-speed\n");
			$r = $session->get_entries(-columns => [$ifhspO],@range,@maxrep);
			$err = $session->error;
			if($err){
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
			$r = $session->get_entries(-columns => [$ifhioO],@range,@maxrep);
			$err = $session->error;
			if($err){
				&misc::Prt("ERR :64bit-in $err\n","II");
				$warn++;
			}else{
				while( my($key, $val) = each(%{$r}) ) {
					$ifio{substr($key,rindex($key,'.')+1)} = $val;
				}
				$r = $session->get_entries(-columns => [$ifhooO],@range,@maxrep);
				$err = $session->error;
				if($err){
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
		if($walkinf){
			&misc::Prt("IF  :Walking speed\n");
			$r = $session->get_entries(-columns => [$ifspdO],@range,@maxrep);
			$err = $session->error;
			if($err){
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
			$r = $session->get_entries(-columns => [$ifinoO],@range,@maxrep);
			$err = $session->error;
			if($err){
				&misc::Prt("ERR :32bit-in $err\n","Ii");
				$warn++;
			}else{
				while( my($key, $val) = each(%{$r}) ) {
					my $x = substr($key,rindex($key,'.')+1);
					$ifio{$x} = $val if !$ifio{$x};
				}
				$r = $session->get_entries(-columns => [$ifotoO],@range,@maxrep);
				$err = $session->error;
				if($err){
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
		$r = $session->get_entries(-columns => [$ifineO],@range,@maxrep);
		$err = $session->error;
		if($err){&misc::Prt("ERR :In-errors $err\n","Ie");$warn++}else{ %ifie  = %{$r}}

		$r = $session->get_entries(-columns => [$ifoteO],@range,@maxrep);
		$err = $session->error;
		if($err){&misc::Prt("ERR :Out-errors $err\n","Ie");$warn++}else{ %ifoe  = %{$r}}
	}

	if($skip !~ /d/ and $ifidiO){
		&misc::Prt("IF  :Walking discards\n");
		$r = $session->get_entries(-columns => [$ifidiO],@range,@maxrep);
		$err = $session->error;
		if($err){&misc::Prt("ERR :In-discards $err\n","Ic");$warn++}else{ %ifidi  = %{$r}}
	}
	if($skip !~ /d/ and $ifodiO){
		$r = $session->get_entries(-columns => [$ifodiO],@range,@maxrep);
		$err = $session->error;
		if($err){&misc::Prt("ERR :Out-discards $err\n","Ic");$warn++}else{ %ifodi  = %{$r}}
	}

	if($skip !~ /b/ and $ifibrO){
		&misc::Prt("IF  :Walking in-broadcasts\n");
		$r = $session->get_entries(-columns => [$ifibrO],@range,@maxrep);
		$err = $session->error;
		if($err){&misc::Prt("ERR :In-broadcasts $err\n","Ib");$warn++}else{ %ifbr  = %{$r}}
	}

	if(!$walkinf){
		@ifx = sort { $a <=> $b } keys %{$main::int{$na}};					# Use Indexes from DB
	}else{
		if($ifnamO){
			&misc::Prt("IF  :Walking name\n");
			$r = $session->get_entries(-columns => [$ifnamO],@range,@maxrep);
			$err = $session->error;
			if($err){&misc::Prt("ERR :IF Name $err\n","In");$warn++}else{%ifna = %{$r}}
		}

		if($ifnamO eq $ifdesO){									# Copy IF desc, if used as name
			%ifde  = %{$r};
		}else{
			&misc::Prt("IF  :Walking description\n");
			$r = $session->get_entries(-columns => [$ifdesO],@range,@maxrep);
			$err = $session->error;
			if($err){&misc::Prt("ERR :IF Desc $err\n","Id");$warn++}else{%ifde  = %{$r}}
		}
		@ifx = map(substr($_,20), keys %ifde);							# cut OIDs down to indexes in 1 step (gotta love perl!)

		&misc::Prt("IF  :Walking type\n");
		$r = $session->get_entries(-columns => [$iftypO],@range,@maxrep);
		$err = $session->error;
		if($err){&misc::Prt("ERR :IF Type $err\n","It");$warn++}else{%iftp  = %{$r}}

		$r = $session->get_entries(-columns => [$ifmacO],@range,@maxrep);
		$err = $session->error;
		if($err){&misc::Prt("ERR :IF MAC $err\n","Im");$warn++}else{%ifmc  = %{$r}}

		if($ifaliO){										# Same for IF vlans...
			&misc::Prt("IF  :Walking alias\n");
			$r = $session->get_entries(-columns => [$ifaliO],@range,@maxrep);
			$err = $session->error;
			if($err){&misc::Prt("ERR :Alias $ifaliO $err\n","Il");$warn++}else{ %ifal  = %{$r}}
		}
		if($ifalxO){
			&misc::Prt("IF  :Walking alias index\n");
			$r = $session->get_entries(-columns => [$ifalxO],@range,@maxrep);
			$err = $session->error;
			if($err){
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
			&misc::Prt("IF  :Walking vlan\n");
			$r = $session->get_entries(-columns => [$ifvlaO],@range,@maxrep);
			$err = $session->error;
			if($err){&misc::Prt("ERR :Vlan $err\n","Iv");$warn++}else{ %ifvl  = %{$r}}
		}
		if($ifvlxO){										# If vlans use a different index
			if(exists $usedoid{$ifvlxO}){							# and if it's been used before
				%ifvx = %{$usedoid{$ifvlxO}};						# assign the vlan oid to where the used one points to.
			}else{										# Otherwhise walk it
				&misc::Prt("IF  :Walking vlan index\n");
				$r = $session->get_entries(-columns => [$ifvlxO],@range,@maxrep);
				$err = $session->error;
				if($err){
					&misc::Prt("ERR :Vlan index $err\n","Iv");
				}else{
					%ifvx  = %{$r};
					$usedoid{$ifvlxO} = \%ifvx;
				}
			}
			foreach my $x ( keys %ifvx ){
				my $i = $x;
				$i =~ s/$ifvlxO\.//;
				$pvid{$ifvx{$x}} = $ifvl{"$ifvlaO.$i"};
			}
		}else{
			foreach my $x ( keys %ifvl ){
				my $i = $x;
				$i =~ s/$ifvlaO\.//;
				$pvid{$i} = $ifvl{$x};
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
				&misc::Prt("IF  :Walking duplex\n");
				$r = $session->get_entries(-columns => [$ifdupO],@range,@maxrep);
				$err = $session->error;
				if($err){&misc::Prt("ERR :Duplex $err\n","Ix");$warn++}else{ %ifdp  = %{$r}}
			}
		}
		if($ifduxO){										# If duplex uses a different index
			if(exists $usedoid{$ifduxO}){							# and if it's been used before
				%ifdx = %{$usedoid{$ifduxO}};						# assign the duplex oid to where the used one points to.
			}else{										# Otherwhise walk it
				&misc::Prt("IF  :Walking duplex index\n");
				$r = $session->get_entries(-columns => [$ifduxO],@range,@maxrep);
				$err = $session->error;
				if($err){
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
		&misc::Prt("IF  :Walking admin status\n");
		$r = $session->get_entries(-columns => [$ifadmO],@range,@maxrep);
		$err = $session->error;
		if($err){&misc::Prt("ERR :IF Adminstat $err\n","Ia");$warn++}else{%ifas  = %{$r}}
	}

	if($skip !~ /O/){
		&misc::Prt("IF  :Walking oper status\n");
		$r = $session->get_entries(-columns => [$ifoprO],@range,@maxrep);
		$err = $session->error;
		if($err){&misc::Prt("ERR :IF Operstat $err\n","Ip");$warn++}else{%ifos  = %{$r}}
	}

	if($misc::usepoe eq "ifmib" and $ifpwrO and $skip !~ /w/){
		&misc::Prt("IF  :Walking PoE\n");
		$r = $session->get_entries(-columns => [$ifpwrO],@range,@maxrep);
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :IF PoE $err\n","Iw");
			$warn++;
		}else{
			%ifpw  = %{$r};
			if($ifpwxO and $ifpwxO !~ /^[0-9]+$/){						# If poe uses a different index
				if(exists $usedoid{$ifpwxO}){
					%ifpx = %{$usedoid{$ifpwxO}};
				}else{
					&misc::Prt("IF  :Walking PoE index\n");
					$r = $session->get_entries(-columns => [$ifpwxO],@range,@maxrep);
					$err = $session->error;
					if($err){
						&misc::Prt("ERR :IF PoE index $err\n","Ip");
					}else{
						%ifpx  = %{$r};
						$usedoid{$ifpwxO} = \%ifpx;
					}
				}
				foreach my $x ( keys %ifpx ){
					my $i = $x;
					$i =~ s/$ifpwxO\.//;
					$poe{$ifpx{$x}} = $ifpw{"$ifpwrO.$i"};
				}
			}else{
				foreach my $x ( keys %ifpw ){
					my $i = $x;
					if($ifpwxO =~ /^[0-9]+$/){					# e.g. use 1000 as IFpwix to map 2.1 to 2001
						my $ti =~ s/$ifpwrO\.//;
						my @tk = split(/\./,$ti);
						$i = $tk[0] * $ifpwxO + $tk[1];
					}else{
						$i =~ s/$ifpwrO\.//;
					}
					$poe{$i} = $ifpw{$x};
				}
			}
		}
	}
	$session->close if defined $session;								# Happens if everything was skipped

	&misc::Prt("IF  :Index Name         Spd Dup St Pvid Description      Alias            PoE\n");
	foreach my $i (@ifx){
		$main::int{$na}{$i}{old} = (exists $main::int{$na}{$i})?1:0;
		$main::int{$na}{$i}{new} = 1;
		if($walkinf){
			my $ina = $i;									# Strip OID up to index & use as name fallback
			if($ifna{"$ifnamO.$i"}){
				my $ifbnam = &misc::Shif(&misc::Strip($ifna{"$ifnamO.$i"}));		# Some devs return special chars!
				if($ifbnam and !exists $misc::portprop{$na}{$ifbnam}{idx} ){		# IF name used before?
					$ina = $ifbnam;
				}else{
					$ina = $ifbnam . "-$i";						# Make unique using index
				}
			}
			$main::int{$na}{$i}{ina} = $ina;
			$main::int{$na}{$i}{des} = &misc::Strip($ifde{"$ifdesO.$i"});
			$main::int{$na}{$i}{typ} = &misc::Strip($iftp{"$iftypO.$i"},0);
			$main::int{$na}{$i}{spd} = &misc::Strip($ifsp{"$i"},0);
			$main::int{$na}{$i}{ali} = &misc::Strip($alias{$i});
			$main::int{$na}{$i}{vid} = &misc::Strip($pvid{$i},0);
			if($ifmc{"$ifmacO.$i"}){
				my $imac = unpack('H12', $ifmc{"$ifmacO.$i"});
				$main::int{$na}{$i}{mac} = $imac;
				$misc::ifmac{$imac}{$na} = $i;						# ifmacs should be unique w/o vlan...
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

		my $ast = 0;
		unless($skip =~ /A/){
			$ast = (&misc::Strip($ifas{"$ifadmO.$i"},0) == 1)?1:0;
			if($main::int{$na}{$i}{old}){
				if( ($main::int{$na}{$i}{sta} & 1) != $ast){
					$main::int{$na}{$i}{chg} = $main::now;
				}
			}else{
				$main::int{$na}{$i}{chg} = 0;
			}
		}
		my $ost = 0;
		unless($skip =~ /O/){
			$ost = (&misc::Strip($ifos{"$ifoprO.$i"},0) == 1)?2:0;
			if($main::int{$na}{$i}{old}){
				if( ($main::int{$na}{$i}{sta} & 2) != $ost){
					$main::int{$na}{$i}{chg} = $main::now;
				}
			}else{
				$main::int{$na}{$i}{chg} = 0;
			}
		}
		$main::int{$na}{$i}{sta} = $ast + $ost;

		unless($skip =~ /t/ and $main::int{$na}{$i}{old}){
			my $ic = &misc::Strip($ifio{"$i"},0);
			my $oc = &misc::Strip($ifoo{"$i"},0);
			if($main::int{$na}{$i}{old}){
				$main::int{$na}{$i}{dio} = ($ic > $main::int{$na}{$i}{ioc})?$ic - $main::int{$na}{$i}{ioc}:0;
				$main::int{$na}{$i}{doo} = ($oc > $main::int{$na}{$i}{ooc})?$oc - $main::int{$na}{$i}{ooc}:0;
			}else{
				$main::int{$na}{$i}{dio} = 0;
				$main::int{$na}{$i}{doo} = 0;
			}
			$main::int{$na}{$i}{ioc} = $ic;
			$main::int{$na}{$i}{ooc} = $oc;
		}

		unless($skip =~ /e/ and $main::int{$na}{$i}{old}){
			my $ie = &misc::Strip($ifie{"$ifineO.$i"},0);
			my $oe = &misc::Strip($ifoe{"$ifoteO.$i"},0);
			if($main::int{$na}{$i}{old}){
				$main::int{$na}{$i}{die} = ($ie > $main::int{$na}{$i}{ier})?$ie - $main::int{$na}{$i}{ier}:0;
				$main::int{$na}{$i}{doe} = ($oe > $main::int{$na}{$i}{oer})?$oe - $main::int{$na}{$i}{oer}:0;
			}else{
				$main::int{$na}{$i}{die} = 0;
				$main::int{$na}{$i}{doe} = 0;
			}
			$main::int{$na}{$i}{ier} = $ie;
			$main::int{$na}{$i}{oer} = $oe;
		}

		unless($skip =~ /d/ and $main::int{$na}{$i}{old}){
			my $id = &misc::Strip($ifidi{"$ifidiO.$i"},0);
			my $od = &misc::Strip($ifodi{"$ifodiO.$i"},0);
			if($main::int{$na}{$i}{old}){
				$main::int{$na}{$i}{did} = ($id > $main::int{$na}{$i}{idi})?$id - $main::int{$na}{$i}{idi}:0;
				$main::int{$na}{$i}{dod} = ($od > $main::int{$na}{$i}{odi})?$od - $main::int{$na}{$i}{odi}:0;
			}else{
				$main::int{$na}{$i}{did} = 0;
				$main::int{$na}{$i}{dod} = 0;
			}
			$main::int{$na}{$i}{idi} = $id;
			$main::int{$na}{$i}{odi} = $od;
		}

		unless($skip =~ /b/ and $main::int{$na}{$i}{old}){
			my $ib = &misc::Strip($ifbr{"$ifibrO.$i"},0);
			if($main::int{$na}{$i}{old}){
				$main::int{$na}{$i}{dib} = ($ib > $main::int{$na}{$i}{ibr})?$ib - $main::int{$na}{$i}{ibr}:0;
			}else{
				$main::int{$na}{$i}{dib} = 0;
			}
			$main::int{$na}{$i}{ibr} = $ib;
		}

		$main::int{$na}{$i}{poe} = &misc::Strip($poe{$i},0) unless $skip =~ /w/ and $main::int{$na}{$i}{old};
		$main::int{$na}{$i}{com} = "";

		if($main::int{$na}{$i}{ina} =~ /^[0-9]+[-,][0-9]|^(Po|Trk|Bridge-Aggregation|Mesh)[0-9]|channel/ and $main::int{$na}{$i}{typ} !~ /^(6|7|117)$/){	# A channel is not ethernet. Also treating ProCurve mesh links the same to avoid population in 1st discovery
			$misc::portprop{$na}{$main::int{$na}{$i}{ina}}{chn} = 1;
		}
		if(exists $main::link{$na} and exists $main::link{$na}{$main::int{$na}{$i}{ina}}){	# Use both to avoid defining
			$main::int{$na}{$i}{lty} = "STAT";
			$misc::portprop{$na}{$main::int{$na}{$i}{ina}}{lnk} = 1;
		}elsif($skip =~ /i/ and $main::int{$na}{$i}{lty}){													# Keep existing linktype and set port property if IF info is skipped
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
	
	return $noifwrite;											# We didn't read anything, just define portnew if TRUE...
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
	my (%vrfs, %vrfna, %vrfst, %typri);
	my $warn  = my $nia = 0;
	my $ippri = my $dnspri = 16;

	my $useMIB = $misc::sysobj{$main::dev{$na}{so}}{ia};
	my @maxrep = ($main::dev{$na}{rv} == 2)?( -maxrepetitions  => 5 ):();				# Bulkwalk, hopefully without fragmented UDP

	&misc::Prt("\nIfAddresses  ------------------------------------------------------------------\n");
	if(exists $misc::useip{$main::dev{$na}{ty}}){							# Type based IF priority? Define typri only if configured
		&misc::Prt("IFIP:useip policy for $main::dev{$na}{ty}=$misc::useip{$main::dev{$na}{ty}}\n");
		$typri{6}  = $typri{7}= $typri{117} = index(" $misc::useip{$main::dev{$na}{ty}}",'e')*4 if index(" $misc::useip{$main::dev{$na}{ty}}",'e') ne -1;
		$typri{24} = index(" $misc::useip{$main::dev{$na}{ty}}",'l')*4 if index(" $misc::useip{$main::dev{$na}{ty}}",'l') ne -1;
		$typri{53} = index(" $misc::useip{$main::dev{$na}{ty}}",'v')*4 if index(" $misc::useip{$main::dev{$na}{ty}}",'v') ne -1;
		$dnspri = index(" $misc::useip{$main::dev{$na}{ty}}",'n')*4 if index(" $misc::useip{$main::dev{$na}{ty}}",'n') ne -1;
	}elsif(exists $misc::useip{'default'}){								# Default set?
		&misc::Prt("IFIP:default useip policy=$misc::useip{'default'}\n");
		$typri{6}  = $typri{7}= $typri{117} = index(" $misc::useip{'default'}",'e')*4 if index(" $misc::useip{'default'}",'e') ne -1;
		$typri{24} = index(" $misc::useip{'default'}",'l')*4 if index(" $misc::useip{'default'}",'l') ne -1;
		$typri{53} = index(" $misc::useip{'default'}",'v')*4 if index(" $misc::useip{'default'}",'v') ne -1;
		$dnspri = index(" $misc::useip{'default'}",'n')*4 if index(" $misc::useip{'default'}",'n') ne -1;
	}else{												# Don't change IP
		&misc::Prt("IFIP:No useip policy set, always using discovered IPs\n");
	}

	($session, $err) = &Connect($main::dev{$na}{ip}, $main::dev{$na}{rv}, $main::dev{$na}{rc});
	return unless defined $session;

	if($useMIB =~ /^old/){
		my $ifadO = "1.3.6.1.2.1.4.20.1";
		&misc::Prt("IFIP:Walking old address table\n");
		$r   = $session->get_table("$ifadO.2",@maxrep);
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :ifAddr $err\n","Ja");
			$warn++;
		}else{
			my %aifx = %{$r};
			$r       = $session->get_table("$ifadO.3",@maxrep);
			$err     = $session->error;
			if($err){
				&misc::Prt("ERR :ifMask $err\n","Jm");
				$warn++;
			}else{
				my %ainm = %{$r};
				foreach my $k ( sort keys %aifx ){					# lowest IPs first
					if(exists $main::int{$na}{$aifx{$k}}){				# Avoid non existant IFs (e.g. idx=0 on  cisco2970 and 3750 with IOS 12.1)
						my @i = split(/\./,$k);
						if(defined $i[13]){					# Some devs have incomplete IPs here!
							my $ip = ($i[10] == 4 and @i == 15)?"$i[11].$i[12].$i[13].$i[14]":"$i[10].$i[11].$i[12].$i[13]"; # (Some) NXOS add a length? field and shift IP
							$main::net{$na}{$ip}{pfx} = &misc::Mask2Bit($ainm{"$ifadO.3.$ip"});
							$main::net{$na}{$ip}{ifn} = $main::int{$na}{$aifx{$k}}{ina};
							$main::net{$na}{$ip}{ift} = $main::int{$na}{$aifx{$k}}{typ};
							$main::net{$na}{$ip}{ifs} = $main::int{$na}{$aifx{$k}}{sta};
							$main::net{$na}{$ip}{ip6} = 0;
						}
					}
				}
			}
		}
	}

	if($useMIB =~ /adr$/){
		if($main::dev{$na}{os} eq "IOS"){							# At least on Cat3560, we can't always extract ifindex from prefix RowPointer!
			$r   = $session->get_table("1.3.6.1.2.1.4.34.1.3",@maxrep);
		}else{
			$r   = $session->get_table("1.3.6.1.2.1.4.34.1.5",@maxrep);
		}
			
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :ifAddressTable $err\n","Ja");
			$warn++
		}else{
			while( my($key, $val) = each(%{$r}) ) {
				my @k = split(/\./,$key);
				my @v = split(/\./,$val);
				if($k[10] == 1){
					my $ip = ($k[11] == 4 and @k == 16)?"$k[12].$k[13].$k[14].$k[15]":"$k[11].$k[12].$k[13].$k[14]"; # (Some) NXOS remove the 4 field and shift IP
					my $ix = $v[10];
					if(@v == 18){
						$main::net{$na}{$ip}{pfx} = $v[17];
					}elsif(@v == 16){
						$main::net{$na}{$ip}{pfx} = $v[15];
						$ix = $v[9];
					}else{
						&misc::Prt("ERR :ifAddressTable unkown value $val\n","Jp");
					}
					$main::net{$na}{$ip}{ifn} = $main::int{$na}{$ix}{ina};
					$main::net{$na}{$ip}{ift} = $main::int{$na}{$ix}{typ};
					$main::net{$na}{$ip}{ifs} = $main::int{$na}{$ix}{sta};
					$main::net{$na}{$ip}{ip6} = 0;
				}elsif($k[11] =~ /^(16|20)$/ and $main::dev{$na}{os} eq "IOS"){
					my $ip = pack("C16",$k[12],$k[13],$k[14],$k[15],$k[16],$k[17],$k[18],$k[19],$k[20],$k[21],$k[22],$k[23],$k[24],$k[25],$k[26],$k[27]);
					$main::net{$na}{$ip}{pfx} = 0;
					$main::net{$na}{$ip}{ifn} = $main::int{$na}{$val}{ina};
					$main::net{$na}{$ip}{ift} = $main::int{$na}{$val}{typ};
					$main::net{$na}{$ip}{ifs} = $main::int{$na}{$val}{sta};
					$main::net{$na}{$ip}{ip6} = 1;
				}elsif($k[10] == 2 and $k[11] == 16 and @k == 28){
					my $ip = pack("C16",$k[12],$k[13],$k[14],$k[15],$k[16],$k[17],$k[18],$k[19],$k[20],$k[21],$k[22],$k[23],$k[24],$k[25],$k[26],$k[27]);
					$main::net{$na}{$ip}{pfx} = $v[-1];
					$main::net{$na}{$ip}{ifn} = $main::int{$na}{$v[9]}{ina};
					$main::net{$na}{$ip}{ift} = $main::int{$na}{$v[9]}{typ};
					$main::net{$na}{$ip}{ifs} = $main::int{$na}{$v[9]}{sta};
					$main::net{$na}{$ip}{ip6} = 1;
				}elsif($k[10] == 2 and @k == 27){#TODO find N5K with IPv6 and test!
					my $ip = pack("C16",$k[11],$k[12],$k[13],$k[14],$k[15],$k[16],$k[17],$k[18],$k[19],$k[20],$k[21],$k[22],$k[23],$k[24],$k[25],$k[26]);
					$main::net{$na}{$ip}{pfx} = $v[-1];
					$main::net{$na}{$ip}{ifn} = $main::int{$na}{$v[9]}{ina};
					$main::net{$na}{$ip}{ift} = $main::int{$na}{$v[9]}{typ};
					$main::net{$na}{$ip}{ifs} = $main::int{$na}{$v[9]}{sta};
					$main::net{$na}{$ip}{ip6} = 1;
				}
			}
		}
	}elsif($useMIB =~ /ip6$/){
		$r   = $session->get_table("1.3.6.1.2.1.55.1.8.1.2",@maxrep);
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :ipv6AddrTable $err\n","Jm");
			$warn++
		}else{
			while( my($key, $val) = each(%{$r}) ) {
				my @k  = split(/\./,$key);
				my $ip = pack("C16",$k[12],$k[13],$k[14],$k[15],$k[16],$k[17],$k[18],$k[19],$k[20],$k[21],$k[22],$k[23],$k[24],$k[25],$k[26],$k[27]);
				$main::net{$na}{$ip}{pfx} = $val;
				$main::net{$na}{$ip}{ifn} = $main::int{$na}{$k[11]}{ina};
				$main::net{$na}{$ip}{ift} = $main::int{$na}{$k[11]}{typ};
				$main::net{$na}{$ip}{ifs} = $main::int{$na}{$k[11]}{sta};
				$main::net{$na}{$ip}{ip6} = 1;
			}
		}
	}elsif($useMIB =~ /cie$/){
		$r   = $session->get_table("1.3.6.1.4.1.9.10.86.1.1.2.1.3",@maxrep);
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :cIpAddressTable $err\n","Jm");
			$warn++
		}else{
			while( my($key, $val) = each(%{$r}) ) {
				my @k = split(/\./,$key);
				my $ip = pack("C16",$k[16],$k[17],$k[18],$k[19],$k[20],$k[21],$k[22],$k[23],$k[24],$k[25],$k[26],$k[27],$k[28],$k[29],$k[30],$k[31]);
				$main::net{$na}{$ip}{pfx} = 0;
				$main::net{$na}{$ip}{ifn} = $main::int{$na}{$val}{ina};
				$main::net{$na}{$ip}{ift} = $main::int{$na}{$val}{typ};
				$main::net{$na}{$ip}{ifs} = $main::int{$na}{$val}{sta};
				$main::net{$na}{$ip}{ip6} = 1;
			}
		}
	}

	if($main::dev{$na}{sv} > 3 and $main::dev{$na}{os} =~/IOS/){					# Try only Cisco routers for now TODO implement 1.3.6.1.2.1.10.166.11
		$r   = $session->get_table("1.3.6.1.3.118.1.2.1.1.6",@maxrep);
		$err = $session->error;
		if($err){&misc::Prt("ERR :VRF $err\n","Jv");$warn++}else{ %vrfs = %{$r} }
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

	foreach my $ip ( keys %{$main::net{$na}} ){
		if(exists $vrfna{$main::net{$na}{$ip}{ifn}}){
			$main::net{$na}{$ip}{vrf} = $vrfna{$main::net{$na}{$ip}{ifn}};
			$main::net{$na}{$ip}{sta} = $vrfst{$main::net{$na}{$ip}{ifn}};
		}
		my $prtip = ($main::net{$na}{$ip}{ip6})?sprintf("%x:%x:%x:%x:%x:%x:%x:%x",unpack("n8",$ip)):$ip;
		if($prtip =~ /^(0|127\.0|::1)/ or $prtip !~ /$misc::netfilter/){
			&misc::Prt("IFIP:$prtip/$main::net{$na}{$ip}{pfx} on $main::net{$na}{$ip}{ifn} is not usable\n");
		}else{
			my $valip = 0;
			if(exists $misc::ifip{$ip}){							# IP used on other devs or just this one?
				if(exists $misc::ifip{$ip}{$na} and scalar keys %{$misc::ifip{$ip}} == 1){
					&misc::Prt("IFIP:$prtip/$main::net{$na}{$ip}{pfx} on $main::net{$na}{$ip}{ifn} is ok & unique\n");
					$valip = 1;
				}else{
					my $msg = "$prtip/$main::net{$na}{$ip}{pfx} on $main::net{$na}{$ip}{ifn} is configured on " . join(', ', keys %{$misc::ifip{$ip}});
					if($main::net{$na}{$ip}{ifs}){					# Event only if not shut
						$misc::mq += &mon::Event('I',150,'nedj',$na,$na,$msg);
					}else{
						&misc::Prt("IFIP:$msg\n");
					}
				}
			}else{
				if($main::dev{$na}{fs} != $main::now){
					$misc::mq += &mon::Event('I',100,'nedj',$na,$na,"New IP $prtip/$main::net{$na}{$ip}{pfx} on $main::net{$na}{$ip}{ifn}");
				}else{
					&misc::Prt("IFIP:New device, IP $prtip/$main::net{$na}{$ip}{pfx} on $main::net{$na}{$ip}{ifn} not in DB yet\n");
				}
				$misc::ifip{$ip}{$na} = 1;
				$valip = 1;
			}
			if($valip and !$main::net{$na}{$ip}{ip6}){
				if(defined $typri{$main::net{$na}{$ip}{ift}} and $ippri >= $typri{$main::net{$na}{$ip}{ift}}){
					if($ip eq $main::dev{$na}{ip}){
						$ippri = $typri{$main::net{$na}{$ip}{ift}} - 1;
						$newip = $ip;
						&misc::Prt("IFIP:$ip is original IP pri=$ippri\n");
					}elsif( &mon::PingService($ip) ne -1){	# Only use if reachable
						$ippri = $typri{$main::net{$na}{$ip}{ift}};
						$newip = $ip;
						&misc::Prt("IFIP:$ip is new IP pri=$ippri\n");
					}else{
						&misc::Prt("IFIP:$ip is not reachable\n");
					}
				}
			}
		}
		$nia++;
	}

	if( $ippri > $dnspri){
		my $ip = &misc::ResolveName($na);
		if($ip and &mon::PingService($ip) ){						# Only use if reachable
			$ippri = $dnspri;
			$newip = $ip;
			&misc::Prt("DNS :$na resolves to $ip priority $ippri\n");
		}
	}

	if($ippri < 15){
		$main::dev{$na}{ip} = &misc::MapIp($newip);
		&misc::Prt("IFIP:Using $main::dev{$na}{ip} with priority $ippri out of $nia addresses\n");
	}
	&misc::Prt(""," j$nia".($warn?" ":"\t") );
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
	if($sv & 1)		{$srv =   4}
	if($sv & (8|4|2))	{$srv +=  2}
	if($sv & 16)		{$srv += 64}
	if($sv & 64)		{$srv +=  1}
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
	if($sv eq "Switch")	{$srv =   2}
	if($sv eq "Router")	{$srv +=  4}
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
	if($sv & 2)		{$srv =  1}								# repeater = L1
	if($sv & (4|8|64))	{$srv += 2}								# bridge, AP, cablemodem = L2
	if($sv & 16)		{$srv += 4}								# router = L3
	if($sv & 32)		{$srv += 32}								# phone = terminal (more benefits than treating as station)
	if($sv & (1|128))	{$srv += 64}								# other, station = L7
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

	my @neblos = split(/$misc::locsep/,$main::dev{$na}{'lo'});
	my $neblo  = (defined $neblos[4])?$neblos[0].$misc::locsep.$neblos[1].$misc::locsep.$neblos[2].$misc::locsep.$neblos[3].$misc::locsep.$neblos[4]:$main::dev{$na}{'lo'};

	&misc::Prt("\nDisProtocol  ------------------------------------------------------------------\n");
	my $maxmsg = ($main::dev{$na}{os} eq "Ironware")?"":"4095";					# maxrep=5 can fail on large CDP tables, so lets increase maxmesg except on Foundry as some struggle with it! TODO find proper fix, still!!!
	($session, $err) = &Connect($main::dev{$na}{ip}, $main::dev{$na}{rv}, $main::dev{$na}{rc}, $misc::timeout + 5, $maxmsg);
	return unless defined $session;

	my @maxrep = ();
	if($main::dev{$na}{rv} == 2){
		if($main::dev{$na}{os} eq "Omnistack"){
			@maxrep = (-maxrepetitions  => 3 );				# Some Alcatel only seem to handle 3...
		}else{
			@maxrep = (-maxrepetitions  => 5 );
		}
	}
	if($dp =~ /LLDP/){
		$r = $session->get_table(-baseoid => '1.0.8802.1.1.2.1.4.1.1',@maxrep);
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :LLDP nbr $err\n","Dl");
		}else{
			%lneb = %{$r};
		}

		if($dp =~ /LLDPXN/){									# Some don't simply use IF index, thus we need to match on IF desc or name:
			$r = $session->get_table(-baseoid => '1.0.8802.1.1.2.1.3.7.1.3',@maxrep);
			$err = $session->error;
			if($err){
				&misc::Prt("ERR :LLDP IF name $err\n","Dl");
			}else{
				while( my($key, $val) = each(%{$r}) ) {
					my @k = split (/\./,$key);
					if(exists $misc::portprop{$na}{&misc::Shif($val)}){
						$lix{$k[11]} = $misc::portprop{$na}{&misc::Shif($val)}{idx};
						&misc::Prt("LLXN:$val index $k[11] is IF index $lix{$k[11]}\n");
					}else{
						$lix{$k[11]} = 0;
						&misc::Prt("LLXN:$val index $k[11] has no IF index!\n");
					}
				}
			}
		}elsif($dp =~ /LLDPX/){									# Some index on ifdesc (or ifalias, but this is handled in Interfaces() with LLDPXA
			$r = $session->get_table(-baseoid => '1.0.8802.1.1.2.1.3.7.1.4',@maxrep);
			$err = $session->error;
			if($err){
				&misc::Prt("ERR :LLDP IF desc $err\n","Dl");
			}else{
				while( my($key, $val) = each(%{$r}) ) {
					my @k = split (/\./,$key);
					if($val){
						$lix{$k[11]} = &misc::Strip($misc::portdes{$na}{$val});
						&misc::Prt("LLXD:$val index $k[11] is IF index $lix{$k[11]}\n");
					}
				}
			}
		}

		if($misc::usepoe eq "disprot"){
			$r = $session->get_table(-baseoid => '1.0.8802.1.1.2.1.5.4795.1.2.11.1.1',@maxrep);# Get LLDP PoE
			$err = $session->error;
			if($err){
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

		$r = $session->get_table(-baseoid => '1.0.8802.1.1.2.1.4.2.1.3',@maxrep);		# Get Remote IPs
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :LLDP IP $err\n","Da");
		}else{
			while( my($key, $val) = each(%{$r}) ) {
				my @k = split (/\./,$key);
				my $x = ($dp =~ /LLDPX/)?$lix{$k[12]}:$k[12];
				if($x){
					if($k[15] == 4){						# IP in decimal
						$neb{$x}{$k[13]}{'ip'} = &misc::MapIp("$k[16].$k[17].$k[18].$k[19]");
					}elsif($k[15] == 16){						# IPv6
						my $ipv6 = sprintf "%02x%02x:%02x%02x:%02x%02x:%02x%02x:%02x%02x:%02x%02x:%02x%02x:%02x%02x",$k[16],$k[17],$k[18],$k[19],$k[20],$k[21],$k[22],$k[23],$k[24],$k[25],$k[26],$k[27],$k[28],$k[29],$k[30],$k[31];						# IPv6 in decimal
						&misc::Prt("LLDP:Ignoring IPv6 address $ipv6\n");
					}elsif($k[15] == 6){						# MAC (you never know!)
						my $mc   = sprintf "%02x%02x%02x%02x%02x%02x",$k[16],$k[17],$k[18],$k[19],$k[20],$k[21];
						&misc::Prt("LLDP:Ignoring MAC address $mc\n");
					}else{
						my $aip = "";
						foreach my $i (splice(@k,16)){				# IP in ASCII
							$aip .= chr($i);
						}
						$neb{$x}{$k[13]}{'ip'} = &misc::MapIp($aip);
						&misc::Prt("LLDP:ASCII address $aip\n");
					}
					$neb{$x}{$k[13]}{'id'} = $neb{$x}{$k[13]}{'ip'};		# Use IP as fallback ID if none is found later
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
						$neb{$x}{$k[13]}{'ip'} = join('.',unpack('C*',substr($val,1) )) if !$neb{$x}{$k[13]}{'ip'};
						$neb{$x}{$k[13]}{'id'} = $neb{$x}{$k[13]}{'ip'} if !$neb{$x}{$k[13]}{'id'};
						$neb{$x}{$k[13]}{'na'} = $neb{$x}{$k[13]}{'ip'} if !$neb{$x}{$k[13]}{'na'};
					}elsif($lneb{"1.0.8802.1.1.2.1.4.1.1.4.$k[11].$k[12].$k[13]"} == 4){	# if subtype is MAC address
						$neb{$x}{$k[13]}{'id'} = unpack("H16",$val);
						$neb{$x}{$k[13]}{'na'} = unpack("H16",$val) if !$neb{$x}{$k[13]}{'na'};
					}else{
						$neb{$x}{$k[13]}{'id'} = &misc::Strip($val);
						$neb{$x}{$k[13]}{'na'} = &misc::Strip($val) if !$neb{$x}{$k[13]}{'na'};
					}
				}elsif($k[10] == 7){							# lldpRemPortId
	#lldpRemPortIdSubtype(4.1.1.6): interfaceAlias(1), portComponent(2),macAddress(3),networkAddress(4),interfaceName(5),agentCircuitId(6),local(7)
					if($lneb{"1.0.8802.1.1.2.1.4.1.1.6.$k[11].$k[12].$k[13]"} eq 3 or $lneb{"1.0.8802.1.1.2.1.4.1.1.6.$k[11].$k[12].$k[13]"} eq 7 and $val =~ /^[0-9]+$/){	# Prefer Descr if subtype is mac or local and a #
						$neb{$x}{$k[13]}{'if'} = &misc::Shif($lneb{"1.0.8802.1.1.2.1.4.1.1.8.$k[11].$k[12].$k[13]"});
					}elsif($lneb{"1.0.8802.1.1.2.1.4.1.1.6.$k[11].$k[12].$k[13]"} eq 3){	# if subtype is MAC address
						$neb{$x}{$k[13]}{'if'} = unpack("H16",$val);
                                                $neb{$x}{$k[13]}{'na'} = unpack("H16",$val) if !$neb{$x}{$k[13]}{'na'};
                                                $neb{$x}{$k[13]}{'id'} = unpack("H16",$val) if !$neb{$x}{$k[13]}{'id'};
					}else{
						$neb{$x}{$k[13]}{'if'} = &misc::Shif($val);
					}
				}elsif($k[10] == 8 and $val){						# Remote Port Description
					$neb{$x}{$k[13]}{'if'} = &misc::Shif($val) if !$neb{$x}{$k[13]}{'if'};	# Use IF desc, if no name yet
				}elsif($k[10] == 9 and $val){						# lldpRemSysName
					$neb{$x}{$k[13]}{'na'} = &misc::Strip($val);
				}elsif($k[10] == 10){
					$neb{$x}{$k[13]}{'de'} = &misc::Strip($val);
					$neb{$x}{$k[13]}{'ty'} = $neb{$x}{$k[13]}{'de'};		# No Type with LLDP :-(
				}elsif($k[10] == 11){
					$neb{$x}{$k[13]}{'sv'} = &LLDPCap2Sv($val);
				}
			}
		}
	}

	if($dp =~ /CDP/){
		$r = $session->get_table('1.3.6.1.4.1.9.9.23.1.2.1.1',@maxrep);
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :CDP $err\n","Dc");
		}else{
			%lneb = %{$r};
			while( my($key, $val) = each(%lneb) ) {
				my @k = split (/\./,$key);
				$neb{$k[14]}{$k[15]}{'dp'} = 'CDP';
				if($k[13] == 4){
					if(length $val == 4){						# Avoid empty and IPv6 values
						$neb{$k[14]}{$k[15]}{'ip'} = &misc::MapIp(sprintf("%d.%d.%d.%d",unpack("C4",$val) ) );
					}else{
						$neb{$k[14]}{$k[15]}{'ip'} = '';
					}
				}elsif($k[13] == 5){
					$neb{$k[14]}{$k[15]}{'de'} = &misc::Strip($val);
				}elsif($k[13] == 6){
					$neb{$k[14]}{$k[15]}{'id'} = &misc::Strip($val);
					$neb{$k[14]}{$k[15]}{'na'} = &misc::Strip($val);
					if($lneb{"1.3.6.1.4.1.9.9.23.1.2.1.1.8.$k[14].$k[15]"} =~ /^WS-C/){
						$neb{$k[14]}{$k[15]}{'na'} =~ s/(.*?)\((.*?)\)/$2/;	# Extract from CatOS
					}else{
						$neb{$k[14]}{$k[15]}{'na'} =~ s/(.*?)\((.*?)\)/$1/;	# Extract from other (e.g. NxK)
					}
					$neb{$k[14]}{$k[15]}{'na'} =~ s/(\xff){1,}/BadCDP-$k[15]/;	# Fixes some phone weirdness
				}elsif($k[13] == 7){
					$neb{$k[14]}{$k[15]}{'if'} = &misc::Shif($val);
				}elsif($k[13] == 8){
					$neb{$k[14]}{$k[15]}{'ty'} = &misc::Strip($val);
				}elsif($k[13] == 9){
					$neb{$k[14]}{$k[15]}{'sv'} = &CDPCap2Sv($val);
				}elsif($k[13] == 10){
					$neb{$k[14]}{$k[15]}{'dg'} = &misc::Strip($val);
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

	if($dp =~ /FDP/){
		$r = $session->get_table('1.3.6.1.4.1.1991.1.1.3.20.1.2.1.1',@maxrep);
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :FDP $err\n","Df");
		}else{
			while( my($key, $val) = each(%{$r}) ) {
				my @k = split (/\./,$key);
				$neb{$k[16]}{$k[17]}{'dp'} = 'FDP';
				if($k[15] == 5){
					if(length $val == 4){
						$neb{$k[16]}{$k[17]}{'ip'} = &misc::MapIp(sprintf("%d.%d.%d.%d",unpack("C4",$val) ) );
					}else{
						$neb{$k[16]}{$k[17]}{'ip'} = '';
					}
				}elsif($k[15] == 6){
					$neb{$k[16]}{$k[17]}{'de'} = &misc::Strip($val);
				}elsif($k[15] == 3){
					$neb{$k[16]}{$k[17]}{'id'} = &misc::Strip($val);
					$neb{$k[16]}{$k[17]}{'na'} = &misc::Strip($val);
				}elsif($k[15] == 7){
					$neb{$k[16]}{$k[17]}{'if'} = &misc::Shif($val);
				}elsif($k[15] == 8){
					$neb{$k[16]}{$k[17]}{'ty'} = &misc::Strip($val);
				}elsif($k[15] == 9){
					$neb{$k[16]}{$k[17]}{'sv'} = &FDPCap2Sv($val);
				}elsif($k[15] == 10){
					$neb{$k[16]}{$k[17]}{'dg'} = &misc::Strip($val);
				}elsif($k[15] == 14){
					$neb{$k[16]}{$k[17]}{'vl'} = &misc::Strip($val);
				}
			}
		}
	}

	if($dp =~ /NDP/){# TODO this whole part is dodgy (with stacked 48 port switches for example) and should be removed!
		$r = $session->get_table('1.3.6.1.4.1.45.1.6.13.2.1.1', -maxrepetitions  => 2 );	# Set to 1 if you get "maxmesg exceeded" error to avoid bulkwalks altogether
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :NDP $err\n","Df");
		}else{
			while( my($key, $val) = each(%{$r}) ) {
				my @k = split (/\./,$key);
				if($k[15] != 0){							# 0 is me
					my $lx = ($k[14]-1) * 32 + $k[15];
					if($k[13] == 5){
						$neb{$lx}{$k[20]}{'dp'} = 'NDP';
						$neb{$lx}{$k[20]}{'ip'} = &misc::MapIp("$k[16].$k[17].$k[18].$k[19]");
						$neb{$lx}{$k[20]}{'if'} = int($k[20]/256-1)*32+$k[20]%256;	# $k[20]/256 = switch, each has 32 if indexes (which we use as ifname)
						$neb{$lx}{$k[20]}{'na'} = (exists $misc::seedini{$neb{$lx}{$k[20]}{'ip'}})?$misc::seedini{$neb{$lx}{$k[20]}{'ip'}}{na}:'';#Assign sysname from DB, if available
						$neb{$lx}{$k[20]}{'de'} = 'NDP-Device';
						$neb{$lx}{$k[20]}{'id'} = unpack("H16",$val);
					}elsif($k[13] == 6){
						$neb{$lx}{$k[20]}{'ty'} = $val;
					}
				}
			}
		}
	}

	$session->close;

	foreach my $i ( keys %neb ){
		my $lif = "";
		if(!exists $main::int{$na}{$i}){							# Assign ifname, if IF exists
			&misc::Prt("DIPR:No IF with index $i (try LLDPX or LLDPXN in .def)!\n","Dx");
		}else{
			$lif = $main::int{$na}{$i}{ina};
			foreach my $n ( keys %{$neb{$i}} ){
				if(exists $main::link{$na}{$lif}{$neb{$i}{$n}{'na'}}){			# Avoid duplicates (happens when several discovery protocols are running)
					&misc::Prt("DIPR:Ignoring duplicate neighbor $neb{$i}{$n}{'na'} on $lif\n","Dx");
				}elsif($neb{$i}{$n}{'na'} or $neb{$i}{$n}{'ip'}){			# VC for example adds empty IFs to LLDP Mib!
					$neb{$i}{$n}{'na'} =~ s/^(.*?)\.(.*)/$1/ if !$main::opt{'F'};	# Strip domain
					if($neb{$i}{$n}{'ip'}){
						if(exists $misc::map{$neb{$i}{$n}{'ip'}}{na}){		# Map neighbor name
							$neb{$i}{$n}{'na'} = $misc::map{$neb{$i}{$n}{'ip'}}{na};
							&Prt("DIPR:Name mapped to $neb{$i}{$n}{'na'}\n");
						}elsif(!$neb{$i}{$n}{'na'}){				# No name? Resolve IP (or use id if this fails)
							$neb{$i}{$n}{'na'} = gethostbyaddr(inet_aton($neb{$i}{$n}{'ip'}), AF_INET) or $neb{$i}{$n}{'na'} = $neb{$i}{$n}{'id'};
						}
						#TODO find solution for multiple IPs or drop this idea! if(exists $main::dev{$neb{$i}{$n}{'na'}} and $neb{$i}{$n}{'ip'} ne $main::dev{$neb{$i}{$n}{'na'}}{'oi'}){
						#	my $msg = "Neighbor $neb{$i}{$n}{'na'} IP address $neb{$i}{$n}{'ip'} changed from $main::dev{$neb{$i}{$n}{'na'}}{'oi'}";
						#	&misc::Prt("DIPR:$msg\n");
						#	if($misc::notify =~ /j/i){
						#		&db::Insert('events','level,time,source,info,class,device',"\"150\",\"$main::now\",\"$na\",\"$msg\",\"nedi\",\"$na\"") unless $main::opt{'t'};
						#	}
						#}
					}else{
						if(exists $main::dev{$neb{$i}{$n}{'na'}}){
							$neb{$i}{$n}{'ip'} = $main::dev{$neb{$i}{$n}{'na'}}{'ip'};
							&misc::Prt("DIPR:Using IP from DB for $neb{$i}{$n}{'na'}\n");
						}else{
							$neb{$i}{$n}{'ip'} = "";
							&misc::Prt("DIPR:No IP found for $neb{$i}{$n}{'na'}!\n");
						}
					}
					if($neb{$i}{$n}{'ty'} =~ /VMware/){				# Until VMware considers sending the mgmt IP with CDP
						$neb{$i}{$n}{'ip'} = &misc::ResolveName($neb{$i}{$n}{'na'});
						$main::link{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{$na}{$lif}{bw} = $main::int{$na}{$i}{spd};
						$main::link{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{$na}{$lif}{ty} = $neb{$i}{$n}{'dp'};
						$main::link{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{$na}{$lif}{de} = "Constructed ".localtime($main::now);
						$main::link{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{$na}{$lif}{du} = $main::int{$na}{$i}{dpx};
						$main::link{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{$na}{$lif}{vl} = $main::int{$na}{$i}{vid};
						$misc::portprop{$na}{$lif}{nsd} = 1;			# No-SNMP-Device IF metric to keep VMs on this IF
					}elsif($neb{$i}{$n}{'ty'} =~ /HP VC Flex/){			# They started using sysnames but added the SN...
						$neb{$i}{$n}{'na'} =~ s/\s.*$//;			# ...so lets cut it off
					}
					&misc::Prt( sprintf("%-4.4s:%-10.10s %-8.8s on %-8.8s %-15.15s %-10.10s %-15.15s\n",$neb{$i}{$n}{'dp'},$neb{$i}{$n}{'na'},$neb{$i}{$n}{'if'},$lif,$neb{$i}{$n}{'ip'},$neb{$i}{$n}{'ty'},$neb{$i}{$n}{'de'}) );
					$main::int{$na}{$i}{com} .= " $neb{$i}{$n}{'dp'}:$neb{$i}{$n}{'na'},$neb{$i}{$n}{'if'}";
					if($main::int{$na}{$i}{lty} ne "STAT"){				# No DP link if static exists
						$main::link{$na}{$lif}{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{bw} = $main::int{$na}{$i}{spd};
						$main::link{$na}{$lif}{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{ty} = $neb{$i}{$n}{'dp'};
						$main::link{$na}{$lif}{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{de} = "Discovered ".localtime($main::now);
						$main::link{$na}{$lif}{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{du} = $neb{$i}{$n}{'dx'};
						$main::link{$na}{$lif}{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{vl} = $neb{$i}{$n}{'vl'};
						$main::int{$na}{$i}{lty} = $neb{$i}{$n}{'dp'};
					}
					if($id eq $neb{$i}{$n}{'id'} or $na eq  $neb{$i}{$n}{'na'}){	# Seeing myself?
						$main::int{$na}{$i}{com} .= " Loop!";
						$misc::portprop{$na}{$lif}{lnk}++;
						$misc::mq += &mon::Event('D',150,'nedl',$na,$na,"Potential $neb{$i}{$n}{'dp'} loop on $lif!");
						&misc::Prt('','DL');
					}elsif($neb{$i}{$n}{'na'} =~ /$misc::border/){
						&misc::Prt( sprintf ("%-4.4s:ID %s matches border /%s/\n",$neb{$i}{$n}{'dp'},$neb{$i}{$n}{'id'},$misc::border) );
						$misc::portprop{$na}{$lif}{nsd} = 1;			# NoSnmpDev to keep nodes behind IP phones, if set as border, but prevent all unknowns wandering off to this link...
						$bd++;
					}else{
						if($neb{$i}{$n}{'de'} =~ /$misc::nosnmpdev/ or $neb{$i}{$n}{'ty'} =~ /$misc::nosnmpdev/ or ($neb{$i}{$n}{'na'} =~ /^AV\w/ and $neb{$i}{$n}{'sv'} & 32) ){
							if(exists $main::dev{$neb{$i}{$n}{'na'}} and $main::dev{$neb{$i}{$n}{'na'}}{'rv'}){
								$mq += &mon::Event('d',100,'nedn',$na,$na,"NoSNMP neighbor $neb{$i}{$n}{'na'} exists as SNMP device in DB, not replacing");
							}else{
								$misc::portprop{$na}{$lif}{nsd} = 1;	# No-SNMP-Device IF metric
								$main::dev{$neb{$i}{$n}{'na'}}{ip} = $neb{$i}{$n}{'ip'};
								$main::dev{$neb{$i}{$n}{'na'}}{sn} = "-" unless defined $main::dev{$neb{$i}{$n}{'na'}}{sn};
								$main::dev{$neb{$i}{$n}{'na'}}{bi} = "-" unless defined $main::dev{$neb{$i}{$n}{'na'}}{bi};
								$main::dev{$neb{$i}{$n}{'na'}}{de} = $neb{$i}{$n}{'de'};
								$main::dev{$neb{$i}{$n}{'na'}}{sv} = $neb{$i}{$n}{'sv'};
								$main::dev{$neb{$i}{$n}{'na'}}{ty} = $neb{$i}{$n}{'ty'};
								$main::dev{$neb{$i}{$n}{'na'}}{os} = "-" unless defined $main::dev{$neb{$i}{$n}{'na'}}{os};
								$main::dev{$neb{$i}{$n}{'na'}}{lo} = $neblo;
								$main::dev{$neb{$i}{$n}{'na'}}{co} = $main::dev{$na}{'co'};
								$main::dev{$neb{$i}{$n}{'na'}}{dg} = $main::dev{$na}{'dg'};
								$main::dev{$neb{$i}{$n}{'na'}}{dm} = 10;
								if(!defined $main::dev{$neb{$i}{$n}{'na'}}{fs}){$main::dev{$neb{$i}{$n}{'na'}}{fs} = $main::now}
								$main::dev{$neb{$i}{$n}{'na'}}{so} = "NoSNMP-$neb{$i}{$n}{dp}";
								$main::dev{$neb{$i}{$n}{'na'}}{opt} = "NNN";
								$main::dev{$neb{$i}{$n}{'na'}}{siz} = " 0";
								$main::dev{$neb{$i}{$n}{'na'}}{stk} = " 0";
								$main::dev{$neb{$i}{$n}{'na'}}{ls} = $main::now;
								push (@misc::doneip,$neb{$i}{$n}{'ip'});
								if($neb{$i}{$n}{'ty'} =~ /Aastra IP Phone/){
									&web::AastraPhone($neb{$i}{$n}{'na'}) if $web::lwpok;
									$main::dev{$neb{$i}{$n}{'na'}}{ty} = "Aastra IP Phone";
									$main::dev{$neb{$i}{$n}{'na'}}{ic} = "phan";
									$main::dev{$neb{$i}{$n}{'na'}}{sv} = 34;
									$main::dev{$neb{$i}{$n}{'na'}}{dm} = 11;
								}elsif($neb{$i}{$n}{'na'} =~ /^AV\w/){
									$main::dev{$neb{$i}{$n}{'na'}}{ty} = "Avaya IP Phone";
									$main::dev{$neb{$i}{$n}{'na'}}{ic} = "phon";
									$main::dev{$neb{$i}{$n}{'na'}}{sv} = 34;
									$main::dev{$neb{$i}{$n}{'na'}}{dm} = 11;
								}elsif($neb{$i}{$n}{'ty'} =~ /Nortel IP Telephone\s*(.*)$/){
									$main::dev{$neb{$i}{$n}{'na'}}{ty} = "Nortel $1";
									$main::dev{$neb{$i}{$n}{'na'}}{ic} = "phon";
									$main::dev{$neb{$i}{$n}{'na'}}{sv} = 34;
									$main::dev{$neb{$i}{$n}{'na'}}{dm} = 11;
								}elsif($neb{$i}{$n}{'ty'} =~ /Cisco IP Phone\s*(.*)$/){
									&web::CiscoPhone($neb{$i}{$n}{'na'}) if $web::lwpok;
									$main::dev{$neb{$i}{$n}{'na'}}{ty} = "Cisco $1";
									$main::dev{$neb{$i}{$n}{'na'}}{ic} = "phbn";
									$main::dev{$neb{$i}{$n}{'na'}}{sv} = 34;
									$main::dev{$neb{$i}{$n}{'na'}}{dm} = 11;
								}elsif($neb{$i}{$n}{'ty'} =~ /ATA/){
									&web::CiscoAta($neb{$i}{$n}{'na'}) if $web::lwpok;
									$main::dev{$neb{$i}{$n}{'na'}}{ty} = "Cisco ATA Box";
									$main::dev{$neb{$i}{$n}{'na'}}{ic} = "atbn";
									$main::dev{$neb{$i}{$n}{'na'}}{sv} = 34;
									$main::dev{$neb{$i}{$n}{'na'}}{dm} = 12;
								}elsif($neb{$i}{$n}{'ty'} =~ /(MAP-.*)/){
									$main::dev{$neb{$i}{$n}{'na'}}{ty} = "HP $1";
									$main::dev{$neb{$i}{$n}{'na'}}{ic} = "wagn";
									$main::dev{$neb{$i}{$n}{'na'}}{sv} = 2;
									$main::dev{$neb{$i}{$n}{'na'}}{dm} = 15;
								}elsif($neb{$i}{$n}{'ty'} =~ /AP[\s_]Controlled,(.*),(.*),(.*)$/){
									$main::dev{$neb{$i}{$n}{'na'}}{de} = "HP MSM controlled mode";
									$main::dev{$neb{$i}{$n}{'na'}}{ty} = "HP $2";
									$main::dev{$neb{$i}{$n}{'na'}}{sn} = $1;
									$main::dev{$neb{$i}{$n}{'na'}}{bi} = $3;
									$main::dev{$neb{$i}{$n}{'na'}}{ic} = "wagn";
									$main::dev{$neb{$i}{$n}{'na'}}{sv} = 2;
									$main::dev{$neb{$i}{$n}{'na'}}{dm} = 15;
								}elsif($neb{$i}{$n}{'ty'} =~ /AIR-BR/){
									#$main::dev{$neb{$i}{$n}{'na'}}{ty} = "Cisco Wlan Bridge";
									$main::dev{$neb{$i}{$n}{'na'}}{ic} = "wbbn";
									$main::dev{$neb{$i}{$n}{'na'}}{sv} = 2;
									$main::dev{$neb{$i}{$n}{'na'}}{dm} = 17;
								}elsif($neb{$i}{$n}{'ty'} =~ /AIR-/){
									#$main::dev{$neb{$i}{$n}{'na'}}{ty} = "Cisco Wlan AP";
									$main::dev{$neb{$i}{$n}{'na'}}{ic} = "wabn";
									$main::dev{$neb{$i}{$n}{'na'}}{sv} = 2;
									$main::dev{$neb{$i}{$n}{'na'}}{dm} = 15;
								}elsif($neb{$i}{$n}{'ty'} =~ /Linksys IP Phone\s*(.*)$/){
									$main::dev{$neb{$i}{$n}{'na'}}{ty} = "Linksys $1";
									$main::dev{$neb{$i}{$n}{'na'}}{ic} = "phbn";
									$main::dev{$neb{$i}{$n}{'na'}}{sv} = 34;
									$main::dev{$neb{$i}{$n}{'na'}}{dm} = 11;
								}elsif($neb{$i}{$n}{'ty'} =~ /^snom\s/){
									$main::dev{$neb{$i}{$n}{'na'}}{ty} = "Snom IP Phone";
									$main::dev{$neb{$i}{$n}{'na'}}{de} = $neb{$i}{$n}{'de'};
									$main::dev{$neb{$i}{$n}{'na'}}{ic} = "phan";
									$main::dev{$neb{$i}{$n}{'na'}}{sv} = 34;
									$main::dev{$neb{$i}{$n}{'na'}}{dm} = 11;
								}elsif($neb{$i}{$n}{'ty'} =~ /VMware/){		# Let VMs stay on this link
									$main::dev{$neb{$i}{$n}{'na'}}{ty} = "vSwitch";
									$main::dev{$neb{$i}{$n}{'na'}}{ic} = "v2an";
									$main::dev{$neb{$i}{$n}{'na'}}{sv} = 2;
									$misc::portprop{$na}{$lif}{lnk} = 0;
									$main::dev{$neb{$i}{$n}{'na'}}{dm} = 18;
								}
								&misc::Prt( sprintf ("%-4.4s:No-SNMP=%-15.15s SV=%s\n",$neb{$i}{$n}{'dp'},$neb{$i}{$n}{'ty'},$neb{$i}{$n}{'sv'}) );
								&db::WriteDev($neb{$i}{$n}{'na'}) unless $main::opt{'t'} or $main::dev{$neb{$i}{$n}{'na'}}{dm} == 16;# Don't overwrite APs when discovered on supported controller
								$main::link{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{$na}{$lif}{bw} = $main::int{$na}{$i}{spd};
								$main::link{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{$na}{$lif}{ty} = $neb{$i}{$n}{'dp'};
								$main::link{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{$na}{$lif}{de} = "Discovered ".localtime($main::now);
								$main::link{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{$na}{$lif}{du} = $main::int{$na}{$i}{dpx};#TODO consider $neb{$i}{$n}{'dx'}; for more consitency with LLDP?
								$main::link{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{$na}{$lif}{vl} = $main::int{$na}{$i}{vid};#TODO consider $neb{$i}{$n}{'vl'}; for more consitency with LLDP?
								&db::WriteLink($neb{$i}{$n}{'na'})if !$main::opt{'t'};
								push (@misc::doneip,$neb{$i}{$n}{'ip'});
								push (@misc::doneid,$neb{$i}{$n}{'id'});
							}
						}elsif($neb{$i}{$n}{'ip'} =~ /^$|^0\.0\.0\.0$|^127\.0\.0/){	# Check only SNMP devs for usable IP
							$misc::portprop{$na}{$lif}{nsd} = 1;
							&mon::Event('d',100,'nedn',$na,$na,"$neb{$i}{$n}{dp} sees $neb{$i}{$n}{na},$neb{$i}{$n}{'if'} with unusable IP $neb{$i}{$n}{'ip'} on $lif");
						}else{
							&misc::Prt( sprintf ("%-4.4s:Queueing of %s ",$neb{$i}{$n}{'dp'},$neb{$i}{$n}{'na'}) );
							$misc::portprop{$na}{$lif}{lnk}++;
							$misc::portprop{$neb{$i}{$n}{'na'}}{$neb{$i}{$n}{'if'}}{nal} = 1;# Neighbor alive will be used for node IF calculation
							if(grep /^\Q$neb{$i}{$n}{'na'}\E$/,(@misc::doneid,@misc::failid,@misc::todo) ){# Don't add if done or already queued... (The \Q \E is to prevent interpreting the nbrID as a regexp)
								&misc::Prt("ID already done\n");
								$dn++;
							}elsif(grep /^\Q$neb{$i}{$n}{'ip'}\E$/,(@misc::doneip,@misc::failip) ){
								&misc::Prt("IP already done\n");
								$dn++;
							}elsif($main::opt{'p'} and $main::opt{'S'} !~ /s/){	# Only add if protocol discovery set and sysinfo not skipped
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
	if($err){
		&misc::Prt("ERR :CIDR $err\n","Dr");
		$r   = $session->get_table($rtnhO);							# Fallback to RFC1213
		$err = $session->error;
		if($err){
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
		if($nh !~ /^$|0.0.0.0|^127/ and !exists $main::net{$na}{$nh}){				# IP is non-local or 0?
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
	&misc::Prt(""," r$ad/$dn".($bd?"b$bd":"").($warn?" ":"\t") );
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
	my (%n, %myarpc);
	my $warn = my $narp = my $narp6 = my $ad = my $dn = my $bd = my $fl = 0;

	my $useMIB = $misc::sysobj{$main::dev{$na}{so}}{ar};

	&misc::Prt("\nArp (SNMP)   ------------------------------------------------------------------\n");
	($session, $err) = &Connect($main::dev{$na}{ip}, $main::dev{$na}{rv}, $main::dev{$na}{rc});
	return unless defined $session;

	if(!defined $useMIB or $useMIB =~ /^old/){
		$r   = $session->get_table("1.3.6.1.2.1.4.22.1.2");
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :ipNetToMedia $err\n","Aa");
			$warn++
		}else{
			while( my($key, $val) = each(%{$r}) ) {
				my $mc = unpack("H12",$val);
				my @k  = split(/\./,$key);
				if($key !~ /127\.0\.0\.[0-9]/ and length($mc) == 12 and $mc !~ /$misc::ignoredmacs/){	# Ignore loopback and incomplete MACs
					my $ip = ($k[11] == 4 and @k == 16)?"$k[12].$k[13].$k[14].$k[15]":"$k[11].$k[12].$k[13].$k[14]"; # Nexus add a length? field and shift IP to the right
					$n{$k[10]}{$ip}{mc} = $mc;
					$n{$k[10]}{$ip}{i6} = 0;
					$myarpc{$mc}++;
				}
			}
		}
	}

	if($useMIB =~ /phy$/){
		$r   = $session->get_table("1.3.6.1.2.1.4.35.1.4");
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :ipNetToPhysical $err\n","Aa");
			$warn++
		}else{
			while( my($key, $val) = each(%{$r}) ) {
				my $mc = unpack("H12",$val);
				my @k  = split(/\./,$key);
				if($key !~ /127\.0\.0\.[0-9]/ and length($mc) == 12 and $mc !~ /$misc::ignoredmacs/){
					if($k[11] == 1 and @k == 16){							# Nexus remove length? field and shift IP to the left
						my $ip = "$k[12].$k[13].$k[14].$k[15]";
						$n{$k[10]}{$ip}{mc} = $mc;
						$n{$k[10]}{$ip}{i6} = 0;
						$myarpc{$mc}++;
					}elsif($k[11] == 2 and @k == 28){							# Nexus remove length? field and shift IP to the left
						my $ip = pack("C16",$k[12],$k[13],$k[14],$k[15],$k[16],$k[17],$k[18],$k[19],$k[20],$k[21],$k[22],$k[23],$k[24],$k[25],$k[26],$k[27]);
						$n{$k[10]}{$ip}{mc} = $mc;
						$n{$k[10]}{$ip}{i6} = 0;
						$myarpc{$mc}++;
					}elsif($k[12] == 4){
						my $ip = "$k[13].$k[14].$k[15].$k[16]";
						$n{$k[10]}{$ip}{mc} = $mc;
						$n{$k[10]}{$ip}{i6} = 0;
						$myarpc{$mc}++;
					}elsif($k[12] == 16){
						my $ip = pack("C16",$k[13],$k[14],$k[15],$k[16],$k[17],$k[18],$k[19],$k[20],$k[21],$k[22],$k[23],$k[24],$k[25],$k[26],$k[27],$k[28]);
						$n{$k[10]}{$ip}{mc} = $mc;
						$n{$k[10]}{$ip}{i6}++;
					}
				}
			}
		}
	}elsif($useMIB =~ /ip6$/){
		$r   = $session->get_table("1.3.6.1.2.1.55.1.12.1.2");
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :ipv6Addr $err\n","Aa");
			$warn++
		}else{
			while( my($key, $val) = each(%{$r}) ) {
				my $mc = unpack("H12",$val);
				my @k  = split(/\./,$key);
				if(length($mc) == 12 and $mc !~ /$misc::ignoredmacs/){
					my $ip = pack("C16",$k[12],$k[13],$k[14],$k[15],$k[16],$k[17],$k[18],$k[19],$k[20],$k[21],$k[22],$k[23],$k[24],$k[25],$k[26],$k[27]);
					$n{$k[10]}{$ip}{mc} = $mc;
					$n{$k[10]}{$ip}{i6}++;
				}
			}
		}
	}elsif($useMIB =~ /cie$/){
		$r   = $session->get_table("1.3.6.1.4.1.9.10.86.1.1.3.1.3");
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :cIpAddressTable $err\n","Aa");
			$warn++
		}else{
			while( my($key, $val) = each(%{$r}) ){
				my $mc = unpack("H12",$val);
				my @k  = split(/\./,$key);
				if(length($mc) == 12 and $mc !~ /$misc::ignoredmacs/){
					my $ip = pack("C16",$k[17],$k[18],$k[19],$k[20],$k[21],$k[22],$k[23],$k[24],$k[25],$k[26],$k[27],$k[28],$k[29],$k[30],$k[31],$k[32]);
					$n{$k[10]}{$ip}{mc} = $mc;
					$n{$k[10]}{$ip}{i6}++;
				}
			}
		}
	}

	foreach my $i ( keys %n ){
		foreach my $ip ( keys %{$n{$i}} ){
			if( exists $main::int{$na}{$i} ){
				my $po = $main::int{$na}{$i}{ina};
				$misc::portprop{$na}{$po}{rtr} = 1;
				$misc::portprop{$na}{$po}{pop}++;

				my $mc   = $n{$i}{$ip}{mc};
				my $vl   = ($po =~ /^Vl[-]?(\d+)$/) ? $1 : "";
				my $mcvl = $mc.(($misc::useivl and $vl =~ /$misc::useivl/)?$vl:""); 		# Make MAC unique by adding vlid

				$misc::portnew{$mcvl}{$na}{po} = $po;
				$misc::portnew{$mcvl}{$na}{vl} = $vl;

				if($n{$i}{$ip}{i6}){
					my $ip6txt = sprintf("%x:%x:%x:%x:%x:%x:%x:%x",unpack("n8",$ip) );
					my $ok = "";
					if(!exists $misc::arp6{$mcvl}){						# Assign any new IPv6 address, but...
						$ok = "OK new";
					}elsif($ip6txt !~ /^fe80/){						# ...only overwrite if it's not link-local and...
						$ok = "OK non-linklocal";
					}
					$misc::arp6{$mcvl} = $ip if $ok;
					&misc::Prt("ARPS:$mc $ip6txt on $po vl$vl $ok ($n{$i}{$ip}{i6} times)\n");
					$narp6++;								# ...increase counter to brag about how many addresses were found!
				}else{
					&misc::Prt("ARPS:$mc $ip on $po vl$vl");
					$misc::arp{$mcvl} = $ip;
					$narp++;

					if($main::opt{'o'} and $main::opt{'S'} !~ /[s]/){			# Only add if OUI discovery set and sysinfo not skipped
						my $oui = &misc::GetOui($mc);
						if($oui =~ /$misc::ouidev/i){
							if(grep /\Q$mc\E/,(@misc::doneid,@misc::failid,@misc::todo) ){	# Don't queue if done or queued.
								&misc::Prt(" done already");
								$dn++;
							}elsif($mc =~ /$misc::border/ or $oui =~ /$misc::border/){	# ...or matching the border...
								&misc::Prt(" $mc or $oui matches border /$misc::border/");
								$bd++;
							}elsif($ip eq '0.0.0.0' or $ip !~ /$misc::netfilter/){	# ...or not usable
								&misc::Prt(" not usable");
								$fl++;
							}else{
								push (@misc::todo,"$mc");
								$misc::doip{$mc} = &misc::MapIp($ip);
								&misc::Prt(" OUI $oui added");
								$ad++;
							}
						}
					}
					&misc::Prt("\n");

					if(exists $misc::arpc{$mc}){
						$misc::arpc{$mc} = $myarpc{$mc} if $misc::arpc{$mc} < $myarpc{$mc};			# Highest ARP value stays in DB
					}else{
						$misc::arpc{$mc} = $myarpc{$mc};
					}
					if(!exists $misc::ifmac{$mc} and $myarpc{$mc} > $misc::arppoison){	# Check for ARP poisoning
						$misc::mq += &mon::Event('N',150,'secp',$na,$na,"$myarpc{$mc} IP addresses for $mc exceed threshold of $misc::arppoison");
						$myarpc{$mc} = $misc::arppoison;							# Avoid repeated events on same MAC
					}
				}
			}
		}
	}

	&misc::Prt("ARPS:$narp IP and $narp6 IPv6 entries found\n"," a$narp o$ad/$dn b$bd/f$fl".($warn?" ":"\t") );
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
	my $nfwd  = 0;
	my @vlans = ();
	my %fwdix = ();
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
	if($misc::sysobj{$main::dev{$na}{so}}{bf} =~ /^(VXP|VLX)$/){
		@vlans = keys %{$main::vlan{$na}};
	}else{
		$vlans[0] = "";
	}

	if($misc::sysobj{$main::dev{$na}{so}}{bf} eq "VXP"){						# Vlan indexing, but not for the port mapping (e.g. N5K)
		($session, $err) = &Connect($main::dev{$na}{ip}, $main::dev{$na}{rv}, $main::dev{$na}{rc});
		$r = $session->get_table(-baseoid => $fwdxO);
		$err = $session->error;
		if($err){&misc::Prt("ERR :Fp- $err\n","Fp-")}else{%fwdix = %{$r} }
		$session->close;
	}
	foreach my $vl (@vlans){
		if($vl !~ /$misc::ignoredvlans/){
			%fwdix = () if $misc::sysobj{$main::dev{$na}{so}}{bf} ne "VXP";			# Keep non indexed port mappings
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
				$r = $session->get_table(-baseoid => $qbriO);
				$m1=14; $m2=15; $m3=16; $m4=17; $m5=18; $m6=19;#TODO check additional OIDs on Extreme..
			}else{
				&misc::Prt("FWDS:Walking BridgeFwd ($commcxt)\n");
				$r = $session->get_table(-baseoid => $fwdpO, @context);
			}

			$err = $session->error;
			if($err){&misc::Prt("ERR :Fp$vl $err\n","Fp$vl")}else{%fwdpo = %{$r} }

			if($misc::sysobj{$main::dev{$na}{so}}{bf} =~ /X$/){
				&misc::Prt("FWDS:Walking FWD Port to IF index\n");
				$r = $session->get_table(-baseoid => $fwdxO, @context);
				$err = $session->error;
				if($err){&misc::Prt("ERR :Fx$vl $err\n","Fx$vl")}else{%fwdix = %{$r} }
			}
			$session->close;

			foreach my $fpo ( keys %fwdpo ){
				my @dmac = split(/\./,$fpo);
				if(defined $dmac[$m6]){							# Ignore incomplete MACs!
					my $mc = sprintf "%02x%02x%02x%02x%02x%02x",$dmac[$m1],$dmac[$m2],$dmac[$m3],$dmac[$m4],$dmac[$m5],$dmac[$m6];
					if($mc !~ /$misc::ignoredmacs/){
						if($misc::sysobj{$main::dev{$na}{so}}{bf} =~ /^VXP$|X$/){
							$ifx  = $fwdix{"$fwdxO.$fwdpo{$fpo}"};
						}else{
							$ifx  = $fwdpo{$fpo};
						}
						if(defined $ifx){
							if(defined $main::int{$na}{$ifx}){
								if($mc ne $main::int{$na}{$ifx}{mac}){	# Cisco's 3500XL do that! (can't be caught before IFX is found)
									my $po   = $main::int{$na}{$ifx}{ina};
									if($misc::sysobj{$main::dev{$na}{so}}{bf} =~ /^normal/){
										$vl = $misc::portprop{$na}{$po}{vid};
									}elsif($misc::sysobj{$main::dev{$na}{so}}{bf} =~ /^qbri/){
										$vl = $dmac[13];	# Vlanid in Qbridge MIB
									}
									&misc::Prt("FWDS:$mc on $po Vl$vl");			# Print before adding vlid
									$mc .= $vl if($misc::useivl and $vl =~ /$misc::useivl/);# Add vlid to mac
									$misc::portnew{$mc}{$na}{vl} = $vl;
									$misc::portnew{$mc}{$na}{po} = $po;
									$misc::portprop{$na}{$po}{pop}++;
									$nfwd++;
									if(exists $misc::ifmac{$mc}){
										&misc::Prt(" belongs to ".join(", ",keys %{$misc::ifmac{$mc}}) );
										$misc::portprop{$na}{$po}{lnk}++;
										$main::int{$na}{$ifx}{com} .= " MAC:".join(", ",keys %{$misc::ifmac{$mc}}) if $main::int{$na}{$ifx}{com} !~ /^ (C|F|LL|N)DP:/;
									}
									&misc::Prt("\n");									
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
	&misc::Prt("FWDS:$nfwd bridge forwarding entries found\n","f$nfwd");
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
	my $nfwd = 0;
	my %snr  = ();
	my $snrO = '1.3.6.1.4.1.9.9.273.1.3.1.1.4';

	&misc::Prt("\nCAPFwd ------------------------------------------------------------------------\n");

	($session, $err) = &Connect($main::dev{$na}{ip}, $main::dev{$na}{rv}, $main::dev{$na}{rc});
	return unless defined $session;

	$r   = $session->get_table("$snrO");
	$err = $session->error;
	if($err){
		&misc::Prt("ERR :$err\n","FwSNR");
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
			$nfwd++;
		}
		&misc::Prt("","f$nfwd");
	}
}

=head2 FUNCTION WLCFwd()

Get Clients and managed AP info from Cisco WLC. Thanks to rufer, lukas, thierry and aurelien!

B<Options> device name

B<Globals> misc::portprop, misc::portnew

B<Returns> -

=cut
sub ArubaFwd {

	my ($na) = @_;
	my ($session, $err, $r, $ifx);
	my $nap = $nif = $nfwd = 0;
	my (%apnam, %aploc, %aptyp, %apsn, %apgrp, %apbi, %apip);
	my (%ifchn, %ifop, %ifad, %ifbs);
	my (%apbss, %apmac, %bsap, %cap, %clip, %cusr, %crad, %cssid, %csnr);

	my $essO = "1.3.6.1.4.1.14823.2.2.1.5.2.1.8.1";							# (index=ASCIIessid) 5=enctype
	my $ap1O = "1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1";							# (index=apmac) 3=name,2=ip,4=grp,6=sn,12=uptime,13=typ
	my $ap2O = "1.3.6.1.4.1.14823.2.2.1.5.2.1.7.1";							# (index=bssid) 4=port,13=apmac
	my $if1O = "1.3.6.1.4.1.14823.2.2.1.1.3.3.1";								# (index=bssid) 2=essid,4=radio,5=apip,8=ch
	my $cltO = "1.3.6.1.4.1.14823.2.2.1.1.2.2.1";								# (index=clientmac+bssid) 3=user,7=snr,10=APname,11=bssid

	&misc::Prt("\nARUFwd ------------------------------------------------------------------------\n");

	($session, $err) = &Connect($main::dev{$na}{ip}, $main::dev{$na}{rv}, $main::dev{$na}{rc});
	return unless defined $session;

	&misc::Prt("ARUF:Walking AP name\n");
	$r   = $session->get_table("$ap1O.3");
	$err = $session->error;
	if($err){
		&misc::Prt("ERR :$err\n","FwNam");
	}else{
		%apnam = %{$r};
		&misc::Prt("ARUF:Walking AP type\n");
		$r   = $session->get_table("$ap1O.13");
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :$err\n","FwTyp");
		}else{
			%aptyp = %{$r};
		}
		&misc::Prt("ARUF:Walking AP SN\n");
		$r   = $session->get_table("$ap1O.6");
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :$err\n","FwSN");
		}else{
			%apsn = %{$r};
		}
		&misc::Prt("ARUF:Walking AP group\n");
		$r   = $session->get_table("$ap1O.4");
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :$err\n","FwGrp");
		}else{
			%apgrp = %{$r};
		}
		&misc::Prt("ARUF:Walking AP IP\n");
		$r   = $session->get_table("$ap1O.2");
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :$err\n","FwAI");
		}else{
			%apip = %{$r};
		}

		&misc::Prt("ARUF:Walking IF channel\n");
		$r   = $session->get_table("$if1O.8");
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :$err\n","FwCh");
		}else{
			%ifch = %{$r};
		}
		&misc::Prt("ARUF:Walking bssid\n");
		$r   = $session->get_table("$ap2O.13");
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :$err\n","FwBS");
		}else{
			%apbss = %{$r};
		}

		my $eru = "";										# Update APs even if no users found!
		&misc::Prt("ARUF:Walking client user\n");
		$r   = $session->get_table("$cltO.3");
		$eru = $session->error;
		if($eru){
			&misc::Prt("ERR :$eru\n","FwUs");
		}else{
			%cusr = %{$r};

			&misc::Prt("ARUF:Walking client SNR\n");
			$r   = $session->get_table("$cltO.7");
			$eru = $session->error;
			if($eru){
				&misc::Prt("ERR :$eru\n","FwSR");
			}else{
				%csnr = %{$r};
			}
		}
	}
	$session->close;

	unless($err){
		foreach my $k ( keys %apnam ){
			my @i   = split(/\./,$k);
			my $mc  = sprintf "%02x%02x%02x%02x%02x%02x",$i[16],$i[17],$i[18],$i[19],$i[20],$i[21];
			my $apn = $apnam{$k};
			$apn    =~ s/^(.*?)\.(.*)/$1/ if !$main::opt{'F'};				# FQDN can mess up links
			$apmac{$mc} = $apn;
			$main::dev{$apn}{fs} = $main::now if !exists $main::dev{$apn};
			$main::dev{$apn}{ls} = $main::now;
			$main::dev{$apn}{os} = "AOSAP";
			$main::dev{$apn}{ic} = "wayn";
			$main::dev{$apn}{us} = $na;
			$main::dev{$apn}{sv} = 2;
			$main::dev{$apn}{dm} = 16;
			my $ip = &misc::Strip($apip{"$ap1O.2.$i[16].$i[17].$i[18].$i[19].$i[20].$i[21]"});
			if($main::dev{$apn}{ip} and $main::dev{$apn}{ip} ne "0.0.0.0" and $main::dev{$apn}{ip} ne $ip){
				&misc::Prt("AP  :$apn has IP $main::dev{$apn}{ip} but controller shows $ip\n");
			}
			$main::dev{$apn}{ip} = $ip;
			$main::dev{$apn}{ty} = "AP".&misc::Strip($aptyp{"$ap1O.13.$i[16].$i[17].$i[18].$i[19].$i[20].$i[21]"});
			$main::dev{$apn}{sn} = &misc::Strip($apsn{"$ap1O.6.$i[16].$i[17].$i[18].$i[19].$i[20].$i[21]"});
			$main::dev{$apn}{dg} = &misc::Strip($apgrp{"$ap1O.4.$i[16].$i[17].$i[18].$i[19].$i[20].$i[21]"});
			$main::dev{$apn}{bi} = "";
			&misc::Prt("AP  :$apn ($mc) $main::dev{$apn}{ip} $main::dev{$apn}{sn} $main::dev{$apn}{ty}\n");
			&db::WriteDev($apn) unless $main::opt{'t'};
			$nap++;
		}

		foreach my $k ( keys %apbss ){
			my @i     = split(/\./,$k);
			my $apmac = sprintf "%02x%02x%02x%02x%02x%02x",$i[16],$i[17],$i[18],$i[19],$i[20],$i[21];
			my $bssid = sprintf "%02x%02x%02x%02x%02x%02x",$i[23],$i[24],$i[25],$i[26],$i[27],$i[28];
			$bsap{$bssid}{'ap'}  = $apmac;
			$bsap{$bssid}{'rad'} = $i[22];
			&misc::Prt("BSS :$apmac{$apmac} $bssid Radio$i[22]\n");
		}

		foreach my $k ( keys %ifch ){
			my @i  = split(/\./,$k);
			my $bs = sprintf "%02x%02x%02x%02x%02x%02x",$i[15],$i[16],$i[17],$i[18],$i[19],$i[20];
			my $ap = $apmac{$bsap{$bs}{'ap'}};
			my $i  = $bsap{$bs}{'rad'};
			$main::int{$ap}{$i}{old} = 0;						# Avoid calculations since we don't have stats!
			$main::int{$ap}{$i}{new} = 1;
			$main::int{$ap}{$i}{ina} = "Radio$i";
			$main::int{$ap}{$i}{des} = "Dot11Radio$i";
			$main::int{$ap}{$i}{mac} = $mc;
			$main::int{$ap}{$i}{typ} = "71";
			$main::int{$ap}{$i}{spd} = 11000000;
			$main::int{$ap}{$i}{dpx} = "HD";
			$main::int{$ap}{$i}{ali} = "";
			$main::int{$ap}{$i}{lit} = "";
			$main::int{$ap}{$i}{poe} = 0;
			$main::int{$ap}{$i}{com} = "";
			$main::int{$ap}{$i}{vid} = &misc::Strip($ifch{$k},0);
			$main::int{$ap}{$i}{sta} = 3;
			&misc::Prt("IF :$ap-$main::int{$ap}{$i}{ina} ST:$main::int{$ap}{$i}{sta} CH:$main::int{$ap}{$i}{vid}\n");
			$nif++;
		}
		foreach my $mc ( keys %apmac ){
			&db::WriteInt($apmac{$mc},$skip);
		}

		foreach my $k ( keys %cusr ){
			my @i  = split(/\./,$k);
			my $mc = sprintf "%02x%02x%02x%02x%02x%02x",$i[15],$i[16],$i[17],$i[18],$i[19],$i[20];
			my $bs = sprintf "%02x%02x%02x%02x%02x%02x",$i[21],$i[22],$i[23],$i[24],$i[25],$i[26];
			my $ap = $apmac{$bsap{$bs}{'ap'}};
			$misc::portnew{$mc}{$ap}{po}  = "Radio".$bsap{$bs}{'rad'};
			$misc::portnew{$mc}{$ap}{vl}  = 0;
			$misc::portnew{$mc}{$ap}{usr} = &misc::Strip($cusr{$k});
			$misc::portnew{$mc}{$ap}{snr} = &misc::Strip($csnr{"$cltO.7.$i[15].$i[16].$i[17].$i[18].$i[19].$i[20].$i[21].$i[22].$i[23].$i[24].$i[25].$i[26]"},0);
			$misc::portprop{$ap}{$misc::portnew{$mc}{$ap}{po}}{pop}++;
			&misc::Prt("ARUF:$mc on $ap $misc::portnew{$mc}{$ap}{po} $misc::portnew{$mc}{$ap}{snr}db $misc::portnew{$mc}{$ap}{usr}\n");
			$nfwd++;
		}
		&misc::Prt("","f$nap/$nif/$nfwd");
	}
}

=head2 FUNCTION MSMFwd()

Get MAC address table and SNR of Wlan clients from HP MSM Controller

B<Options> device name

B<Globals> misc::portprop, misc::portnew

B<Returns> -

=cut
sub MSMFwd {
#TODO finish this and consider coDevWirCliDisassociate (.1.3.6.1.4.1.8744.5.25.1.7.1.1.27) support in Nodes-Status?
#TODO consider 1.3.6.1.4.1.8744.5.25.1.13.1.1 to get neighbors? 4=ssid,5=chn
	my ($na) = @_;
	my ($session, $err, $r, $ifx);
	my $nap = $nif = $nfwd = 0;
	my (%apnam, %aploc, %aptyp, %apsn, %apgrp, %apip);
	my (%ifchn, %ifop, %ifad);
	my (%radap, %cmac, %clip, %cusr, %crad, %cssid, %csnr);

	my @maxrep = ($main::dev{$na}{rv} == 2)?( -maxrepetitions  => 5 ):();				# Bulkwalk, hopefully without fragmented UDP
	my $ap1O = '1.3.6.1.4.1.8744.5.23.1.2.1.1';							# 2=sn,3=mac,4=ip,5=state,6=name,7=loc,8=con,9=grp
	my $ap2O = '1.3.6.1.4.1.8744.5.23.1.3.1.1';							# 2=typ,3=sw,4=fw,5=hw
	my $ap3O = '1.3.6.1.4.1.8744.5.23.1.4.1.1';							# 1=uptime,8=cpu,9=memtot,10=memfree
	my $if1O = '1.3.6.1.4.1.8744.5.24.1.1.1.1';							# 1=nam,3=iftyp,4=vlid,5=ip,6=msk,7=mac
	my $if2O = '1.3.6.1.4.1.8744.5.24.1.2.1.1';							# 1=inoct,3=inerr,4=outoct,6=outerr
	my $if3O = '1.3.6.1.4.1.8744.5.25.1.2.1.1';							# 2=idx-to-if1&2,3=mode,4=rfpwr,5=ch,6=radiomode,7=radiotyp,8=stat(1EN),9=#clnt
	my $cl1O = '1.3.6.1.4.1.8744.5.25.1.7.1.1';							# 2=mac,3=vsc,7=snr,8=vlid,17=ip
	my $cl2O = '1.3.6.1.4.1.8744.5.25.1.8.1.1';							# 3=inoct,4=outoct


	&misc::Prt("\nMSMFwd ------------------------------------------------------------------------\n");

	($session, $err) = &Connect($main::dev{$na}{ip}, $main::dev{$na}{rv}, $main::dev{$na}{rc});
	return unless defined $session;

	&misc::Prt("MSMF:Walking AP name\n");
	$r   = $session->get_table("$ap1O.6",@maxrep);
	$err = $session->error;
	if($err){
		&misc::Prt("ERR :$err\n","FwNam");
	}else{
		%apnam = %{$r};
		&misc::Prt("MSMF:Walking AP location\n");
		$r   = $session->get_table("$ap1O.7",@maxrep);
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :$err\n","FwLoc");
		}else{
			%aploc = %{$r};
		}
		&misc::Prt("MSMF:Walking AP IP\n");
		$r   = $session->get_table("$ap1O.4",@maxrep);
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :$err\n","FwAI");
		}else{
			%apip = %{$r};
		}

		&misc::Prt("MSMF:Walking AP type\n");
		$r   = $session->get_table("$ap2O.2",@maxrep);
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :$err\n","FwTyp");
		}else{
			%aptyp = %{$r};
		}
		&misc::Prt("MSMF:Walking AP SN\n");
		$r   = $session->get_table("$ap1O.2",@maxrep);
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :$err\n","FwSN");
		}else{
			%apsn = %{$r};
		}
		&misc::Prt("MSMF:Walking AP group\n");
		$r   = $session->get_table("$ap1O.9",@maxrep);
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :$err\n","FwGrp");
		}else{
			%apgrp = %{$r};
		}

		&misc::Prt("MSMF:Walking IF channel\n");
		$r   = $session->get_table("$if3O.5",@maxrep);
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :$err\n","FwCh");
		}else{
			%ifch = %{$r};
		}
		&misc::Prt("MSMF:Walking IF oper status\n");
		$r   = $session->get_table("$if3O.8",@maxrep);
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :$err\n","FwOP");
		}else{
			%ifop = %{$r};
		}

		my $eru = "";										# Update APs even if no users found!
		&misc::Prt("MSMF:Walking client MAC\n");
		$r   = $session->get_table("$cl1O.2",@maxrep);
		$eru = $session->error;
		if($eru){
			&misc::Prt("ERR :$eru\n","FwMAC");
		}else{
			%cmac = %{$r};

			&misc::Prt("MSMF:Walking client SSID index\n");
			$r   = $session->get_table("$cl1O.3",@maxrep);
			$eru = $session->error;
			if($eru){
				&misc::Prt("ERR :$eru\n","FwSX");
			}else{
				%cssid = %{$r};
			}
			&misc::Prt("MSMF:Walking client SNR\n");
			$r   = $session->get_table("$cl1O.7",@maxrep);
			$eru = $session->error;
			if($eru){
				&misc::Prt("ERR :$eru\n","FwSR");
			}else{
				%csnr = %{$r};
			}
			&misc::Prt("MSMF:Walking client IP\n");
			$r   = $session->get_table("$cl1O.17",@maxrep);
			$eru = $session->error;
			if($eru){
				&misc::Prt("ERR :$eru\n","FwCI");
			}else{
				%clip = %{$r};
			}
		}
	}
	$session->close;

	unless($err){
		foreach my $k ( keys %apnam ){
			my @i   = split(/\./,$k);
			my $apn = $apnam{$k};
			$apn    =~ s/^(.*?)\.(.*)/$1/ if !$main::opt{'F'};				# FQDN can mess up links
			my $ty = &misc::Strip($aptyp{"$ap2O.2.$i[14]"});
			if($ty){									# No type if AP is offline
				$radap{$i[14]} = $apn;
				$main::dev{$apn}{fs} = $main::now if !exists $main::dev{$apn};
				$main::dev{$apn}{ls} = $main::now;
				$main::dev{$apn}{os} = "MSMc";
				$main::dev{$apn}{ic} = "wagn";
				$main::dev{$apn}{us} = $na;
				$main::dev{$apn}{sv} = 2;
				$main::dev{$apn}{dm} = 16;
				my $ip = &misc::Strip($apip{"$ap1O.4.$i[14]"});
				if($main::dev{$apn}{ip} and $main::dev{$apn}{ip} ne "0.0.0.0" and $main::dev{$apn}{ip} ne $ip){
					&misc::Prt("AP  :$apn has $main::dev{$apn}{ip} but controller shows $ip\n");
				}
				$main::dev{$apn}{ip} = $ip;
				$main::dev{$apn}{lo} = &misc::Strip($aploc{"$ap1O.7.$i[14]"});
				$main::dev{$apn}{ty} = $ty;
				$main::dev{$apn}{sn} = &misc::Strip($apsn{"$ap1O.2.$i[14]"});
				$main::dev{$apn}{dg} = &misc::Strip($apgrp{"$ap1O.9.$i[14]"});
				&misc::Prt("AP  :$apn $main::dev{$apn}{ip} $main::dev{$apn}{sn} $main::dev{$apn}{ty} $main::dev{$apn}{lo}\n");
				&db::WriteDev($apn) unless $main::opt{'t'};
				$nap++;
			}else{
				&misc::Prt("AP  :$apn is currently offline\n");
			}
		}

		foreach my $k ( keys %ifch ){
			my @i  = split(/\./,$k);
			my $ap = $radap{$i[14]};
			if($ap){
				$main::int{$ap}{$i[15]}{old} = 0;						# Avoid calculations since we don't have stats!
				$main::int{$ap}{$i[15]}{new} = 1;
				$main::int{$ap}{$i[15]}{ina} = "Radio$i[15]";
				$main::int{$ap}{$i[15]}{des} = "Dot11Radio$i[15]";
				#$main::int{$ap}{$i[15]}{mac} = $mc;
				$main::int{$ap}{$i[15]}{typ} = "71";
				$main::int{$ap}{$i[15]}{spd} = 11000000;
				$main::int{$ap}{$i[15]}{dpx} = "HD";
				$main::int{$ap}{$i[15]}{ali} = "";
				$main::int{$ap}{$i[15]}{lit} = "";
				$main::int{$ap}{$i[15]}{poe} = 0;
				$main::int{$ap}{$i[15]}{com} = "";
				$main::int{$ap}{$i[15]}{vid} = &misc::Strip($ifch{$k},0);
				$main::int{$ap}{$i[15]}{sta} = (&misc::Strip($ifop{"$if3O.8.$i[14].$i[15]"},0))?3:0;
				&misc::Prt("IF :$ap-$main::int{$ap}{$i[15]}{ina} ST:$main::int{$ap}{$i[15]}{sta} CH:$main::int{$ap}{$i[15]}{vid}\n");
				$nif++;
			}
		}
		foreach my $i ( keys %radap ){
			&db::WriteInt($radap{$i},$skip);
		}

		foreach my $k ( keys %cmac ){
			my @i  = split(/\./,$k);
			my $mc = unpack('H12',$cmac{$k});
			if($mc and exists $radap{$i[14]}){							# Avoid errors on incomplete entries (Can't use Strip, as it cuts 000 etc.!!???!)
				my $ap = $radap{$i[14]};
				$misc::portnew{$mc}{$ap}{po}  = "Radio$i[15]";
				$misc::portnew{$mc}{$ap}{vl}  = &misc::Strip($cssid{"$cl1O.3.$i[14].$i[15].$i[16]"},0);
				$misc::portnew{$mc}{$ap}{snr} = &misc::Strip($csnr{"$cl1O.7.$i[14].$i[15].$i[16]"},0);
				$misc::portprop{$ap}{$misc::portnew{$mc}{$ap}{po}}{pop}++;
				my $ip = &misc::Strip($clip{"$cl1O.17.$i[14].$i[15].$i[16]"},0);
				$misc::arp{$mc} = $ip if $ip ne "0.0.0.0";
				&misc::Prt("MSMF:$mc on $ap $misc::portnew{$mc}{$ap}{po} $main::vlan{$na}{$misc::portnew{$mc}{$ap}{vl}} $misc::portnew{$mc}{$ap}{snr}db\n");
				$nfwd++;
			}
		}
		&misc::Prt("","f$nap/$nif/$nfwd");
	}
}


=head2 FUNCTION WLCFwd()

Get Clients and managed AP info from Cisco WLC. Thanks to rufer, lukas, thierry and aurelien!

B<Options> device name

B<Globals> misc::portprop, misc::portnew

B<Returns> -

=cut
sub WLCFwd {

	my ($na) = @_;
	my ($session, $err, $r, $ifx);
	my $nap = $nif = $nfwd = 0;
	my (%apnam, %aploc, %aptyp, %apsn, %apgrp, %apbi, %apip);
	my (%ifchn, %ifop, %ifad);
	my (%radap, %cap, %clip, %cusr, %crad, %cssid, %csnr);

	my $ap1O = "1.3.6.1.4.1.14179.2.2.1.1";								# (index=radmac) 3=name,4=loc,9=sw,10=ctlr1,16=typ,17=sn,19=ip,30=grp,31=sw,33=ethmac
	my $if1O = "1.3.6.1.4.1.14179.2.2.2.1";								# (index=radmac) 4=ch,12=opstat(1DWN,2UP),34=adminstat(1EN,2DIS)
	my $cltO = "1.3.6.1.4.1.14179.2.1.4.1";								# (index=clientmac) 2=ip,3=user,4=radmac,5=slotid,6=ssidX,7=ssid,9=status,21=port
	my $snrO = "1.3.6.1.4.1.14179.2.1.6.1";								# (index=clientmac) 1=RSSI,26=snr
	#my $if2O = "1.3.6.1.4.1.9.9.513.1.2.2.1";							# (index=radmac) 2=ethnam,10=ethduplex(3FD,2HD),11=ethspeed

	&misc::Prt("\nWLCFwd ------------------------------------------------------------------------\n");

	($session, $err) = &Connect($main::dev{$na}{ip}, $main::dev{$na}{rv}, $main::dev{$na}{rc});
	return unless defined $session;

	&misc::Prt("WLCF:Walking AP name\n");
	$r   = $session->get_table("$ap1O.3");
	$err = $session->error;
	if($err){
		&misc::Prt("ERR :$err\n","FwNam");
	}else{
		%apnam = %{$r};
		&misc::Prt("WLCF:Walking AP location\n");
		$r   = $session->get_table("$ap1O.4");
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :$err\n","FwLoc");
		}else{
			%aploc = %{$r};
		}
		&misc::Prt("WLCF:Walking AP type\n");
		$r   = $session->get_table("$ap1O.16");
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :$err\n","FwTyp");
		}else{
			%aptyp = %{$r};
		}
		&misc::Prt("WLCF:Walking AP SN\n");
		$r   = $session->get_table("$ap1O.17");
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :$err\n","FwSN");
		}else{
			%apsn = %{$r};
		}
		&misc::Prt("WLCF:Walking AP group\n");
		$r   = $session->get_table("$ap1O.30");
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :$err\n","FwGrp");
		}else{
			%apgrp = %{$r};
		}
		&misc::Prt("WLCF:Walking AP bootimage\n");
		$r   = $session->get_table("$ap1O.31");
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :$err\n","FwBI");
		}else{
			%apbi = %{$r};
		}
		&misc::Prt("MSMF:Walking AP IP\n");
		$r   = $session->get_table("$ap1O.19");
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :$err\n","FwAI");
		}else{
			%apip = %{$r};
		}

		&misc::Prt("WLCF:Walking IF channel\n");
		$r   = $session->get_table("$if1O.4");
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :$err\n","FwCh");
		}else{
			%ifch = %{$r};
		}
		&misc::Prt("WLCF:Walking IF oper status\n");
		$r   = $session->get_table("$if1O.12");
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :$err\n","FwOP");
		}else{
			%ifop = %{$r};
		}
		&misc::Prt("WLCF:Walking IF admin status\n");
		$r   = $session->get_table("$if1O.34");
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :$err\n","FwAP");
		}else{
			%ifad = %{$r};
		}

		my $eru = "";										# Update APs even if no users found!
		&misc::Prt("WLCF:Walking client user\n");
		$r   = $session->get_table("$cltO.3");
		$eru = $session->error;
		if($eru){
			&misc::Prt("ERR :$eru\n","FwUs");
		}else{
			%cusr = %{$r};

			&misc::Prt("WLCF:Walking client AP\n");
			$r   = $session->get_table("$cltO.4");
			$eru = $session->error;
			if($eru){
				&misc::Prt("ERR :$eru\n","FwAP");
			}else{
				%cap = %{$r};
			}
			&misc::Prt("WLCF:Walking client radio\n");
			$r   = $session->get_table("$cltO.5");
			$eru = $session->error;
			if($eru){
				&misc::Prt("ERR :$eru\n","FwRa");
			}else{
				%crad = %{$r};
			}
			&misc::Prt("WLCF:Walking client SSID index\n");
			$r   = $session->get_table("$cltO.6");
			$eru = $session->error;
			if($eru){
				&misc::Prt("ERR :$eru\n","FwSX");
			}else{
				%cssid = %{$r};
			}
			&misc::Prt("WLCF:Walking client SNR\n");
			$r   = $session->get_table("$snrO.26");
			$eru = $session->error;
			if($eru){
				&misc::Prt("ERR :$eru\n","FwSR");
			}else{
				%csnr = %{$r};
			}
			&misc::Prt("MSMF:Walking client IP\n");
			$r   = $session->get_table("$cltO.2");
			$eru = $session->error;
			if($eru){
				&misc::Prt("ERR :$eru\n","FwCI");
			}else{
				%clip = %{$r};
			}
		}
	}
	$session->close;

	unless($err){
		foreach my $k ( keys %apnam ){
			my @i   = split(/\./,$k);
			my $mc  = sprintf "%02x%02x%02x%02x%02x%02x",$i[12],$i[13],$i[14],$i[15],$i[16],$i[17];
			my $apn = $apnam{$k};
			$apn    =~ s/^(.*?)\.(.*)/$1/ if !$main::opt{'F'};				# FQDN can mess up links
			$radap{$mc} = $apn;
			$main::dev{$apn}{fs} = $main::now if !exists $main::dev{$apn};
			$main::dev{$apn}{ls} = $main::now;
			$main::dev{$apn}{os} = "LWAP";
			$main::dev{$apn}{ic} = "wabn";
			$main::dev{$apn}{us} = $na;
			$main::dev{$apn}{sv} = 2;
			$main::dev{$apn}{dm} = 16;
			my $ip = &misc::Strip($apip{"$ap1O.19.$i[12].$i[13].$i[14].$i[15].$i[16].$i[17]"});
			if($main::dev{$apn}{ip} and $main::dev{$apn}{ip} ne "0.0.0.0" and $main::dev{$apn}{ip} ne $ip){
				&misc::Prt("AP  :$apn has IP $main::dev{$apn}{ip} but controller shows $ip\n");
			}
			$main::dev{$apn}{lo} = &misc::Strip($aploc{"$ap1O.4.$i[12].$i[13].$i[14].$i[15].$i[16].$i[17]"});
			$main::dev{$apn}{ty} = &misc::Strip($aptyp{"$ap1O.16.$i[12].$i[13].$i[14].$i[15].$i[16].$i[17]"});
			$main::dev{$apn}{sn} = &misc::Strip($apsn{"$ap1O.17.$i[12].$i[13].$i[14].$i[15].$i[16].$i[17]"});
			$main::dev{$apn}{dg} = &misc::Strip($apgrp{"$ap1O.30.$i[12].$i[13].$i[14].$i[15].$i[16].$i[17]"});
			$main::dev{$apn}{bi} = &misc::Strip($apbi{"$ap1O.31.$i[12].$i[13].$i[14].$i[15].$i[16].$i[17]"});
			&misc::Prt("AP  :$apn ($mc) $main::dev{$apn}{ip} $main::dev{$apn}{sn} $main::dev{$apn}{ty} $main::dev{$apn}{lo}\n");
			&db::WriteDev($apn) unless $main::opt{'t'};
			$nap++;
		}

		foreach my $k ( keys %ifch ){
			my @i  = split(/\./,$k);
			my $mc = sprintf "%02x%02x%02x%02x%02x%02x",$i[12],$i[13],$i[14],$i[15],$i[16],$i[17];
			my $ap = $radap{$mc};
			$main::int{$ap}{$i[18]}{old} = 0;						# Avoid calculations since we don't have stats!
			$main::int{$ap}{$i[18]}{new} = 1;
			$main::int{$ap}{$i[18]}{ina} = "Do$i[18]";
			$main::int{$ap}{$i[18]}{des} = "Dot11Radio$i[18]";
			$main::int{$ap}{$i[18]}{mac} = $mc;
			$main::int{$ap}{$i[18]}{typ} = "71";
			$main::int{$ap}{$i[18]}{spd} = 11000000;
			$main::int{$ap}{$i[18]}{dpx} = "HD";
			$main::int{$ap}{$i[18]}{ali} = "";
			$main::int{$ap}{$i[18]}{lit} = "";
			$main::int{$ap}{$i[18]}{poe} = 0;
			$main::int{$ap}{$i[18]}{com} = "";
			$main::int{$ap}{$i[18]}{vid} = &misc::Strip($ifch{$k},0);
			my $ast = &misc::Strip($ifad{"$if1O.34.$i[12].$i[13].$i[14].$i[15].$i[16].$i[17].$i[18]"},0) & 1;
			my $ost = &misc::Strip($ifop{"$if1O.12.$i[12].$i[13].$i[14].$i[15].$i[16].$i[17].$i[18]"},0) & 2;
			$main::int{$ap}{$i[18]}{sta} = $ast + $ost;
			&misc::Prt("IF :$ap-$main::int{$ap}{$i[18]}{ina} ST:$main::int{$ap}{$i[18]}{sta} CH:$main::int{$ap}{$i[18]}{vid}\n");
			$nif++;
		}
		foreach my $mc ( keys %radap ){
			&db::WriteInt($radap{$mc},$skip);
		}

		foreach my $k ( keys %cap ){
			my @i  = split(/\./,$k);
			my $mc = sprintf "%02x%02x%02x%02x%02x%02x",$i[12],$i[13],$i[14],$i[15],$i[16],$i[17];
			my $ap = $radap{unpack('H12', $cap{$k})};
			$misc::portnew{$mc}{$ap}{po}  = "Do".$crad{"$cltO.5.$i[12].$i[13].$i[14].$i[15].$i[16].$i[17]"};
			$misc::portnew{$mc}{$ap}{usr} = &misc::Strip($cusr{"$cltO.3.$i[12].$i[13].$i[14].$i[15].$i[16].$i[17]"});
			$misc::portnew{$mc}{$ap}{vl}  = &misc::Strip($cssid{"$cltO.6.$i[12].$i[13].$i[14].$i[15].$i[16].$i[17]"},0);
			$misc::portnew{$mc}{$ap}{snr} = &misc::Strip($csnr{"$snrO.26.$i[12].$i[13].$i[14].$i[15].$i[16].$i[17]"},0);
			$misc::portprop{$ap}{$misc::portnew{$mc}{$ap}{po}}{pop}++;
			my $ip = &misc::Strip($clip{"$cltO.2.$i[12].$i[13].$i[14].$i[15].$i[16].$i[17]"},0);
			$misc::arp{$mc} = $ip if $ip ne "0.0.0.0";
			&misc::Prt("WLCF:$mc on $ap $misc::portnew{$mc}{$ap}{po} $main::vlan{$na}{$misc::portnew{$mc}{$ap}{vl}} $misc::portnew{$mc}{$ap}{snr}db $misc::portnew{$mc}{$ap}{usr}\n");
			$nfwd++;
		}
		&misc::Prt("","f$nap/$nif/$nfwd");
	}
}

=head2 FUNCTION DDWRTFwd()

Get MAC address table and SNR of Wlan clients from DD-WRT APs

B<Options> device name

B<Globals> misc::portprop, misc::portnew

B<Returns> -

=cut
sub WRTFwd{

	my ($na) = @_;
	my ($session, $err, $r, $ifx);
	my $nfwd = 0;
	my %snr  = my %mac  = ();
	my $macO = '1.3.6.1.4.1.2021.255.3.54.1.3.32.1.4';
	my $snrO = '1.3.6.1.4.1.2021.255.3.54.1.3.32.1.26';

	&misc::Prt("\nWRTFwd ----------------------------------------------------------------------\n");

	($session, $err) = &Connect($main::dev{$na}{ip}, $main::dev{$na}{rv}, $main::dev{$na}{rc});
	return unless defined $session;

	$session->translate(1);
	$r   = $session->get_table("$macO");
	$err = $session->error;
	if($err){
		&misc::Prt("ERR :$macO $err\n","FwMAC");
	}else{
		%mac = %{$r};
		$r   = $session->get_table("$snrO");
		$err = $session->error;
		if($err){
			&misc::Prt("ERR :$err\n","FwSNR");
		}else{
			%snr = %{$r};
		}
	}
	$session->close;

	unless($err){
		foreach my $k ( keys %mac ){
			my @i = split(/\./,$k);
			if( exists $main::int{$na}{$i[11]} ){
				my $po = $main::int{$na}{$i[11]}{ina};
				my $mc = substr($mac{$k},2);

				$misc::portnew{$mc}{$na}{vl} = 0;
				$misc::portnew{$mc}{$na}{po} = $po;
				$misc::portnew{$mc}{$na}{snr} = $snr{"$snrO.$i[15]"};
				$misc::portprop{$na}{$po}{pop}++;
				&misc::Prt("WRTF:$mc on $po ($i[11]) SNR:$misc::portnew{$mc}{$na}{snr}\n");
				$nfwd++;
			}else{
				&misc::Prt("ERR :No interface name for index $i[11]\n");
			}
		}
		&misc::Prt("","f$nfwd");
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
	my @maxrep = ($main::dev{$na}{rv} == 2)?( -maxrepetitions  => 5 ):();				# Bulkwalk, hopefully without fragmented UDP

	&misc::Prt("\nModules      ------------------------------------------------------------------\n");
	($session, $err) = &Connect($main::dev{$na}{ip}, $main::dev{$na}{rv}, $main::dev{$na}{rc});
	return unless defined $session;

	$session->translate(1);										# Needed for some devs returning HEX-SNs/MACs
	$r = $session->get_table($misc::sysobj{$so}{mt},@maxrep);					# Walk slot/supplyclass
	$err = $session->error;
	if($err){
		&misc::Prt("ERR :Slot $err\n","Mt");
		$warn++;
	}else{
		%msl  = %{$r};
		if($misc::sysobj{$so}{md}){
			&misc::Prt("MOD :Walking module description\n");
			$r = $session->get_table($misc::sysobj{$so}{md},@maxrep);			# Walk module description
			$err = $session->error;
			if($err){&misc::Prt("ERR :Desc $err\n","Md");return 1;}else{%mde  = %{$r}}
		}
		if($misc::sysobj{$so}{mc}){
			&misc::Prt("MOD :Walking module class\n");
			$r = $session->get_table($misc::sysobj{$so}{mc},@maxrep);			# Walk module classes
			$err = $session->error;
			if($err){&misc::Prt("ERR :Class $err\n","Mc");$warn++}else{%mcl  = %{$r}}
		}
		if($misc::sysobj{$so}{mh}){
			&misc::Prt("MOD :Walking module HW\n");
			$r = $session->get_table($misc::sysobj{$so}{mh},@maxrep);			# Walk module HW/supply capacity
			$err = $session->error;
			if($err){&misc::Prt("ERR :HW $err\n","Mh");$warn++}else{%mhw  = %{$r}}
		}
		if($misc::sysobj{$so}{ms}){
			&misc::Prt("MOD :Walking module SW\n");
			$r = $session->get_table($misc::sysobj{$so}{ms},@maxrep);			# Walk module software version
			$err = $session->error;
			if($err){&misc::Prt("ERR :SW $err\n","Ms");$warn++}else{%msw  = %{$r}}
		}
		if($misc::sysobj{$so}{mf}){
			&misc::Prt("MOD :Walking module FW\n");
			$r = $session->get_table($misc::sysobj{$so}{mf},@maxrep);			# Walk module FW/supply level
			$err = $session->error;
			if($err){&misc::Prt("ERR :FW $err\n","Mf");$warn++}else{%mfw  = %{$r}}
		}
		if($misc::sysobj{$so}{mn}){
			&misc::Prt("MOD :Walking module SN\n");
			$r = $session->get_table($misc::sysobj{$so}{mn},@maxrep);			# Walk module serial number
			$err = $session->error;
			if($err){&misc::Prt("ERR :SN $err\n","M#");$warn++}else{%msn  = %{$r}}
		}
		if($misc::sysobj{$so}{mm}){
			&misc::Prt("MOD :Walking module model\n");
			$r = $session->get_table($misc::sysobj{$so}{mm},@maxrep);			# Walk module model
			$err = $session->error;
			if($err){&misc::Prt("ERR :Model $err\n","Mm");$warn++}else{%mmo  = %{$r}}
		}
	}
	$session->close;

	my $stack = 0;
	foreach my $i ( keys %msl ){
		my $nomod = "no class";
		my $s     = $msl{$i};
		my $cl    = "";
		$i =~ s/$misc::sysobj{$so}{mt}\.//;							# Cut common part and use rest as index
		if(exists $mcl{"$misc::sysobj{$so}{mc}.$i"}){
			$cl = &misc::Strip($mcl{"$misc::sysobj{$so}{mc}.$i"});
			if($main::dev{$na}{os} eq "Baystack"){						# TODO quick fix to map class, create function if needed for other devs
				if($cl == 3){$cl = 9}
				elsif($cl == 5){$cl = 3}
			}
			if($cl =~ /$misc::sysobj{$so}{mv}/){
				if($cl =~ /^6|10$/){							# Ignore transceivers & PSUs without SN
					$nomod = "" if $msn{"$misc::sysobj{$so}{mn}.$i"};
				}elsif($cl =~ /^9$/){							# Ignore modules with same SN as chassis
					$nomod = "" if $main::dev{$na}{sn} ne $msn{"$misc::sysobj{$so}{mn}.$i"};
				}else{
					$nomod = "";
				}
			}else{
				$nomod = "class $cl !~ /$misc::sysobj{$so}{mv}/";
			}
			$stack++ if $cl == 3 and $main::dev{$na}{os} ne "NXOS";				# TODO introduce general handling for fabric extenders
		}else{
			$nomod = "";
		}
		my $modl = &misc::Strip($mmo{"$misc::sysobj{$so}{mm}.$i"});
		my $mdes = &misc::Strip($mde{"$misc::sysobj{$so}{md}.$i"});
		if(!$nomod and ($modl or $mdes) ){							# Only add if model or describtion exists
			$main::mod{$na}{$i}{sl} = &misc::Strip( substr($s,0,63) );
			$main::mod{$na}{$i}{de} = ($mdes)?$mdes:'-';
			$main::mod{$na}{$i}{sn} = &misc::Strip($msn{"$misc::sysobj{$so}{mn}.$i"});
			$main::mod{$na}{$i}{hw} = &misc::Strip($mhw{"$misc::sysobj{$so}{mh}.$i"});
			$main::mod{$na}{$i}{fw} = &misc::Strip($mfw{"$misc::sysobj{$so}{mf}.$i"});
			$main::mod{$na}{$i}{sw} = &misc::Strip($msw{"$misc::sysobj{$so}{ms}.$i"});
			$main::mod{$na}{$i}{mc} = ($cl)?$cl:$misc::sysobj{$so}{mv};
			if($main::dev{$na}{os} eq "Printer"){
				$main::mod{$na}{$i}{mo} = "Printsupply";
				if( $mfw{"$misc::sysobj{$so}{mf}.$i"} =~ /^[0-9]+$/ and $mhw{"$misc::sysobj{$so}{mh}.$i"} =~ /^[0-9]+$/ ){
					$main::mod{$na}{$i}{st} = int(100*$mhw{"$misc::sysobj{$so}{mh}.$i"} / $mfw{"$misc::sysobj{$so}{mf}.$i"});
					&misc::Prt("MOD+:$i-$s $main::mod{$na}{$i}{mo} $main::mod{$na}{$i}{de} is at\t$main::mod{$na}{$i}{st}%\n");
				}else{
					$main::mod{$na}{$i}{st} = 0;
					&misc::Prt("MODW:Capacity (".$mfw{"$misc::sysobj{$so}{mf}.$i"}.") or supply (".$mhw{"$misc::sysobj{$so}{mh}.$i"}.") is not numeric\n");
				}
				if($main::mod{$na}{$i}{st} < $misc::supa){
					$misc::mq += &mon::Event('M',200,'nedu',$na,$na,"Supply $mdes with $main::mod{$na}{$i}{st}% is below threshold of ${misc::supa}%");
				}
			}else{
				$main::mod{$na}{$i}{mo} = ($modl and $modl ne $main::mod{$na}{$i}{sn})?$modl:"-";# Some transceivers report serial as model (rufer), set to - in that case or if it's empty
				&misc::Prt("MOD+:$i-$s $main::mod{$na}{$i}{mo} $main::mod{$na}{$i}{de} $main::mod{$na}{$i}{sn}\n");
			}
			$nmod++;
		}else{
			&misc::Prt("MOD :$i-$s $nomod $modl $mdes\n");
		}
	}
	&misc::Prt("","m$nmod".($warn?" ":"\t"));

	if($main::dev{$na}{fs} != $main::now and $stack != $main::dev{$na}{stk}){			# Stack changed
		$misc::mq += &mon::Event('M',150,'nedo',$na,$na,"Stack changed from $main::dev{$na}{stk} units to $stack");
	}
	$main::dev{$na}{stk} = $stack;
}

1;
