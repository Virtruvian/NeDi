=pod

=head1 LIBRARY
libdb-msq.pm

Functions for the MYSQL Database

=head2 AUTHORS

Remo Rickli & NeDi Community

=cut

package db;
use warnings;

use DBI;


=head2 FUNCTION InitDB()

Connect as admin, drop existing DB and create nedi db and add important
values (like admin user).

B<Options> $adminuser,$adminpass,$nedihost

B<Globals> -

B<Returns> -

=cut
sub InitDB {

	$dbh = DBI->connect("DBI:mysql:mysql:$misc::dbhost", "$_[0]", "$_[1]", { RaiseError => 1, AutoCommit => 1});
	my $mysqlVer;
	my $sth = $dbh->prepare("SELECT VERSION()");
	$sth->execute();
	while ((my @f) = $sth->fetchrow) {
		$mysqlVer = $f[0];
	}
	print "MySQL Version	: $mysqlVer\n";
	print "----------------------------------------------------------------------\n";
	$dbh->do("DROP DATABASE IF EXISTS $misc::dbname");
	print "Old DB $misc::dbname dropped!\n";

	print "Creating $misc::dbname ";
	$dbh->do("CREATE DATABASE $misc::dbname");
	$dbh->do("GRANT ALL PRIVILEGES ON $misc::dbname.* TO \'$misc::dbuser\'\@\'$_[2]\' IDENTIFIED BY \'$misc::dbpass\'");
	if($mysqlVer =~ /5\.0/) {									#fix for mysql 5.0 with old client libs
		$dbh->do("SET PASSWORD FOR \'$misc::dbuser\'\@\'$_[2]\' = OLD_PASSWORD(\'$misc::dbpass\')");
	}
	print "for $misc::dbuser\@$_[2]\n";
	$sth->finish if $sth;
	$dbh->disconnect();

#---Connect as nedi db user and create tables.
	$dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 0});

	print "INIT:Creating devices,";
	$dbh->do("CREATE TABLE devices	(	device VARCHAR(64) NOT NULL UNIQUE, devip INT unsigned default 0, serial VARCHAR(32), type VARCHAR(32),
						firstdis INT unsigned, lastdis INT unsigned, services TINYINT unsigned,
						description VARCHAR(255), devos VARCHAR(16), bootimage VARCHAR(64),
						location VARCHAR(255), contact VARCHAR(255),
						devgroup VARCHAR(32), devmode TINYINT unsigned, snmpversion TINYINT unsigned,
						readcomm VARCHAR(32), cliport SMALLINT unsigned, login VARCHAR(32),
						icon VARCHAR(16), origip INT unsigned, cpu TINYINT unsigned,memcpu BIGINT unsigned,
						temp TINYINT unsigned, cusvalue BIGINT unsigned, cuslabel VARCHAR(32), sysobjid VARCHAR(255),
						writecomm VARCHAR(32), devopts CHAR(32), size TINYINT unsigned, stack TINYINT unsigned,
						maxpoe SMALLINT unsigned, totpoe SMALLINT unsigned, INDEX (device(8)), PRIMARY KEY (device) )");
 	$dbh->commit;

	print "modules, ";
	$dbh->do("CREATE TABLE modules	(	device VARCHAR(64) NOT NULL, slot VARCHAR(64), model VARCHAR(32), moddesc VARCHAR(255),
						serial VARCHAR(32), hw VARCHAR(128), fw VARCHAR(128), sw VARCHAR(128), modidx VARCHAR(32), 
						modclass TINYINT unsigned, status SMALLINT unsigned, modloc VARCHAR(255), INDEX (device(8)), INDEX (slot(8)) ) ");# modidx can look like 1.2, thus needs to be varchar (and 32 e.g. for Aruba)
 	$dbh->commit;

	print "interfaces, ";
	$dbh->do("CREATE TABLE interfaces(	device VARCHAR(64) NOT NULL, ifname VARCHAR(32) NOT NULL, ifidx BIGINT unsigned,
						linktype CHAR(4), iftype INT unsigned, ifmac CHAR(12),
						ifdesc VARCHAR(255), alias VARCHAR(64), ifstat TINYINT unsigned default 0,
						speed BIGINT unsigned default 0, duplex CHAR(2), pvid SMALLINT unsigned default 0,
						inoct BIGINT unsigned default 0, inerr INT unsigned default 0,
						outoct BIGINT unsigned default 0, outerr INT unsigned default 0,
						dinoct BIGINT unsigned default 0, dinerr INT unsigned default 0,
						doutoct BIGINT unsigned default 0, douterr INT unsigned default 0,
						indis INT unsigned default 0, outdis INT unsigned default 0, 
						dindis INT unsigned default 0,	doutdis INT unsigned default 0,
						inbrc INT unsigned default 0, dinbrc INT unsigned default 0,
						lastchg INT unsigned default 0, poe SMALLINT unsigned default 0,
						comment VARCHAR(255), trafalert TINYINT unsigned default 95, 
						trafwarn TINYINT unsigned default 75, macflood SMALLINT unsigned default 50,
						INDEX (device(8)), INDEX (ifname(8)),INDEX (ifidx) )");
 	$dbh->commit;

	print "networks, ";
	$dbh->do("CREATE TABLE networks (	device VARCHAR(64) NOT NULL, ifname VARCHAR(32), ifip INT unsigned,
						ifip6 varbinary(16), prefix TINYINT unsigned, vrfname VARCHAR(32),
						status SMALLINT unsigned, INDEX (device(8)), INDEX (ifname), INDEX (ifip) )");
 	$dbh->commit;

	print "configs, ";
	$dbh->do("CREATE TABLE configs	(	device VARCHAR(64) NOT NULL UNIQUE, config MEDIUMTEXT, changes MEDIUMTEXT ,
						time INT unsigned, INDEX (device(8)), PRIMARY KEY  (device)  )");
 	$dbh->commit;

	print "stock,\n";
	$dbh->do("CREATE TABLE stock	(	serial VARCHAR(32) UNIQUE, type VARCHAR(32),user VARCHAR(32),
						time INT unsigned, location VARCHAR(255), state TINYINT unsigned, comment VARCHAR(255),
						lastwty INT unsigned, source VARCHAR(32) default '-', INDEX(serial) )");
 	$dbh->commit;

	print "vlans, ";
	$dbh->do("CREATE TABLE vlans	(	device VARCHAR(64) NOT NULL, vlanid SMALLINT unsigned,
						vlanname VARCHAR(32), INDEX(vlanid), INDEX(device(8)) )");
 	$dbh->commit;

	print "links, ";
	$dbh->do("CREATE TABLE links	(	id INT unsigned NOT NULL AUTO_INCREMENT, device VARCHAR(64) NOT NULL,
						ifname VARCHAR(32), neighbor VARCHAR(64) NOT NULL, nbrifname VARCHAR(32),
						bandwidth BIGINT unsigned, linktype CHAR(4), linkdesc VARCHAR(255), nbrduplex CHAR(2),
						nbrvlanid SMALLINT unsigned, time INT unsigned, INDEX(id), INDEX(device(8)),
						INDEX(ifname(8)), INDEX(neighbor(8)), INDEX(nbrifname(8)), PRIMARY KEY(id) )");
 	$dbh->commit;

	print "locations, ";
	$dbh->do("CREATE TABLE locations(	id INT unsigned NOT NULL AUTO_INCREMENT,region VARCHAR(32) NOT NULL,
						city VARCHAR(32), building VARCHAR(32), x SMALLINT unsigned, y SMALLINT unsigned,
						ns INT default 0, ew INT default 0, locdesc VARCHAR(255), 
						INDEX(region),PRIMARY KEY(id)  )");
 	$dbh->commit;

	print "events, ";
	$dbh->do("CREATE TABLE events(		id INT unsigned NOT NULL AUTO_INCREMENT, level TINYINT unsigned, time INT unsigned,
						source VARCHAR(64), info VARCHAR(255), class CHAR(4) default 'dev', device VARCHAR(64) default '',
						INDEX(id), INDEX(source(8)), INDEX(level), INDEX(time), INDEX(class), INDEX(device(8)), PRIMARY KEY(id) )");
 	$dbh->commit;

	print "monitoring, ";
	$dbh->do("CREATE TABLE monitoring(	name VARCHAR(64) NOT NULL UNIQUE, monip INT unsigned, class CHAR(4) default 'dev', 
						test CHAR(6) default '', testopt VARCHAR(64) default '', testres VARCHAR(64) default '',
						lastok INT unsigned default 0,	status INT unsigned default 0, lost INT unsigned default 0,
						ok INT unsigned default 0, latency SMALLINT unsigned default 0, latmax SMALLINT unsigned default 0,
						latavg SMALLINT unsigned default 0, uptime INT unsigned default 0, alert TINYINT unsigned default 0,
						eventfwd VARCHAR(255) default '', eventlvl TINYINT unsigned default 0, eventdel VARCHAR(255) default '',
						depend VARCHAR(64) default '-', device VARCHAR(64) NOT NULL, notify CHAR(32) default '', noreply TINYINT unsigned default 2,
						latwarn SMALLINT unsigned default 100, cpualert TINYINT unsigned default 75, memalert INT unsigned default 1024,
						tempalert TINYINT unsigned default 55, poewarn TINYINT unsigned default 8, arppoison SMALLINT unsigned default 1, 
						supplyalert TINYINT unsigned default 5, INDEX (name(8)), INDEX (device(8)) )");
 	$dbh->commit;

	print "incidents, ";
	$dbh->do("CREATE TABLE incidents(	id INT unsigned NOT NULL AUTO_INCREMENT, level TINYINT unsigned, name VARCHAR(64),
						deps INT unsigned, start INT unsigned, end INT unsigned, user VARCHAR(32),
						time INT unsigned, grp TINYINT unsigned, comment VARCHAR(255), device VARCHAR(64) default '',
						INDEX(id), INDEX(name(8)), INDEX(device(8)), PRIMARY KEY(id) )");
 	$dbh->commit;

	print "nodes, ";
	$dbh->do("CREATE TABLE nodes 	(	name VARCHAR(64), nodip INT unsigned, mac VARCHAR(16) NOT NULL, oui VARCHAR(32),
						firstseen INT unsigned, lastseen INT unsigned, device VARCHAR(64),
						ifname VARCHAR(32), vlanid SMALLINT unsigned, ifmetric INT unsigned,
						ifupdate INT unsigned, ifchanges INT unsigned,	ipupdate INT unsigned,
						ipchanges INT unsigned, iplost INT unsigned, arpval SMALLINT unsigned,
						nodip6 varbinary(16), tcpports VARCHAR(64), udpports VARCHAR(64), nodtype VARCHAR(64), 
						nodos VARCHAR(64), osupdate INT unsigned default 0, noduser VARCHAR(32),
						INDEX(name(8)), INDEX(nodip), INDEX(mac), INDEX(vlanid), INDEX(device(8)) )");
 	$dbh->commit;

	print "nodetrack,\n";
	$dbh->do("CREATE TABLE nodetrack(	device varchar(64), ifname varchar(32), value varchar(64), source char(8),
						user varchar(32),time int unsigned, INDEX(device(8)), INDEX(ifname(8)) )");
 	$dbh->commit;

	print "iftrack, ";
	$dbh->do("CREATE TABLE iftrack	(	mac VARCHAR(16) NOT NULL,ifupdate INT unsigned, device VARCHAR(64),
						ifname VARCHAR(32), vlanid SMALLINT unsigned,
						ifmetric INT unsigned, INDEX(mac), INDEX(vlanid), INDEX(device(8)) )");
 	$dbh->commit;
	print "iptrack,";
	$dbh->do("CREATE TABLE iptrack (	mac VARCHAR(16) NOT NULL,ipupdate INT unsigned, name VARCHAR(64), nodip INT unsigned,
						vlanid SMALLINT unsigned, device VARCHAR(64) NOT NULL default '',
						INDEX(mac), INDEX(vlanid), INDEX(device(8)) )");
 	$dbh->commit;

	print "stolen, ";
	$dbh->do("CREATE TABLE stolen 	(	name VARCHAR(64), stlip INT unsigned, mac CHAR(12) NOT NULL, device VARCHAR(64),
						ifname VARCHAR(32), user VARCHAR(32), time INT unsigned, comment VARCHAR(255) default '',
						INDEX(mac), INDEX(device(8)), PRIMARY KEY(mac) )");
 	$dbh->commit;

	print "users, ";
	$dbh->do("CREATE TABLE users 	(	user VARCHAR(32) NOT NULL UNIQUE, password VARCHAR(64) NOT NULL default '',
						groups SMALLINT unsigned NOT NULL default '0', email VARCHAR(64) default '', phone VARCHAR(32) default '',
						time INT unsigned, lastlogin INT unsigned, comment VARCHAR(255) default '',
						language VARCHAR(16) NOT NULL default 'english', theme VARCHAR(16) NOT NULL default 'default',
						volume TINYINT unsigned NOT NULL default '40', columns TINYINT unsigned NOT NULL default '6',
						msglimit TINYINT unsigned NOT NULL default '5', miscopts SMALLINT unsigned NOT NULL default '2',
						dateformat VARCHAR(16) NOT NULL default 'j.M y G:i470', viewdev VARCHAR(255) default '',
						PRIMARY KEY(user) )");

	$sth = $dbh->prepare("INSERT INTO users (user,password,groups,time,comment,volume,columns,msglimit,miscopts) VALUES ( ?,?,?,?,?,?,?,?,? )");
	$sth->execute ( 'admin','3cac26b5bd6addd1ba4f9c96a58ff8c2c2c8ac15018f61240f150a4a968b8562','255',$main::now,'default admin','40','8','10','3' );
 	$dbh->commit;

	print "system, ";
	$dbh->do("CREATE TABLE system	(	name VARCHAR(32) NOT NULL UNIQUE, value VARCHAR(32) default '',
						INDEX(name) )");
	$sth = $dbh->prepare("INSERT INTO system (name,value) VALUES ( ?,? )");
	$sth->execute ( 'nodlock','0' );
	$sth->execute ( 'threads','0' );
	$sth->execute ( 'first','0' );
 	$dbh->commit;

	print "chat, ";
	$dbh->do("CREATE TABLE chat (time INT unsigned,  user VARCHAR(32), message VARCHAR(255), INDEX(time),INDEX (user(8)) )");
	print "wlan";
	$dbh->do("CREATE TABLE wlan (mac CHAR(8),time INT unsigned, INDEX(mac) )");
	my @wlan = ();
	if(-e "$main::p/inc/wlan.txt"){
		open  ("WLAN", "$main::p/inc/wlan.txt" );
		@wlan = <WLAN>;
		close("WLAN");
		chomp(@wlan);
	}
	$sth = $dbh->prepare("INSERT INTO wlan (mac,time) VALUES ( ?,? )");
	for my $mc (sort @wlan ){ $sth->execute ( $mc,$main::now ) }
 	$dbh->commit;
	$sth->finish if $sth;
	$dbh->disconnect();
	print "... done.\n\n";
}


=head2 FUNCTION ReadDev()

Read devices table.

B<Options> match statement

B<Globals> main::dev

B<Returns> -

=cut
sub ReadDev {

	my $npdev = 0;
	my $where = (defined $_[0])?$_[0]:"";

	if($where eq 'all'){
		$where = "";
		&misc::Prt("RDEV:Reading all devices\n");
	}elsif($where){
		$where = "WHERE $where";
		&misc::Prt("RDEV:Reading devices $where\n");
	}
	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 1});
	my $sth = $dbh->prepare("SELECT * FROM devices $where");
	$sth->execute();
	if($sth->rows){
		undef (%main::dev);									# Replace entries only when we got some new ones. Avoid problems in moni.pl while nedi.pl updates devices
	}
	while ((my @f) = $sth->fetchrow_array) {
		$main::dev{$f[0]}{ip} = &misc::Dec2Ip($f[1]);
		$main::dev{$f[0]}{oi} = &misc::Dec2Ip($f[19]);
		$main::dev{$f[0]}{sn} = $f[2];
		$main::dev{$f[0]}{ty} = $f[3];
		$main::dev{$f[0]}{fs} = $f[4];
		$main::dev{$f[0]}{ls} = $f[5];
		$main::dev{$f[0]}{sv} = $f[6];
		$main::dev{$f[0]}{de} = $f[7];
		$main::dev{$f[0]}{os} = $f[8];
		$main::dev{$f[0]}{bi} = $f[9];
		$main::dev{$f[0]}{lo} = $f[10];
		$main::dev{$f[0]}{rc} = $f[11];
		$main::dev{$f[0]}{vd} = $f[12];
		$main::dev{$f[0]}{vm} = $f[13];
		$main::dev{$f[0]}{rv} = $f[14]  & 3;							# 1st 2 bits, SNMP read version
		$main::dev{$f[0]}{wv} = ($f[14] & 12) / 4;						# 2nd 2 bits, SNMP write version
		$main::dev{$f[0]}{hc} = $f[14]  & 192;							# 8th bit, HC, 7th bit using RFC2233
		$main::dev{$f[0]}{rc} = $f[15];								# SNMP read community
		$main::dev{$f[0]}{cp} = $f[16];								# CLI port (0=new,1=impossible,22=ssh,anything else=telnet)
		$main::dev{$f[0]}{us} = $f[17];
		$main::dev{$f[0]}{ic} = $f[18];
		$main::dev{$f[0]}{cpu}= $f[20];
		$main::dev{$f[0]}{mcp}= $f[21];
		$main::dev{$f[0]}{tmp}= $f[22];
		$main::dev{$f[0]}{cuv}= $f[23];
		$main::dev{$f[0]}{cul}= $f[24];
		$main::dev{$f[0]}{so} = $f[25];
		$main::dev{$f[0]}{wc} = $f[26];								# SNMP write community
		$main::dev{$f[0]}{opt}= $f[27];
		$main::dev{$f[0]}{siz}= $f[28];
		$main::dev{$f[0]}{stk}= $f[29];
		$main::dev{$f[0]}{mpw}= $f[30];
		$main::dev{$f[0]}{tpw}= $f[31];
		$main::dev{$f[0]}{pl} = $main::dev{$f[0]}{ls};						# Preserve lastseen for calculations
		$main::dev{$f[0]}{pi} = $main::dev{$f[0]}{ip};
		$misc::seedini{$main::dev{$f[0]}{ip}}{rv} = $main::dev{$f[0]}{rv};			# Tie comm & ver to IP,
		$misc::seedini{$main::dev{$f[0]}{ip}}{rc} = $main::dev{$f[0]}{rc};
		$misc::seedini{$main::dev{$f[0]}{ip}}{na} = $f[0];
		$misc::seedini{$main::dev{$f[0]}{oi}}{rv} = $main::dev{$f[0]}{rv};			# it's all we have at first
		$misc::seedini{$main::dev{$f[0]}{oi}}{rc} = $main::dev{$f[0]}{rc};
		$misc::seedini{$main::dev{$f[0]}{oi}}{na} = $f[0];
		$npdev++;
	}
	$sth->finish if $sth;
	$dbh->disconnect;

	&misc::Prt("RDEV:$npdev devices read from $misc::dbname.devices\n");

	return $npdev;
}


=head2 FUNCTION ReadLink()

Read links table.

B<Options> match statement

B<Globals> main::link

B<Returns> -

=cut
sub ReadLink {

	my $nlink = 0;

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 1});
	my $where = ($_[0])?"WHERE $_[0]":"";
	my $sth = $dbh->prepare("SELECT * FROM links $where");
	$sth->execute();
	while ((my @l) = $sth->fetchrow_array) {
		$main::link{$l[1]}{$l[2]}{$l[3]}{$l[4]}{bw} = $l[5];
		$main::link{$l[1]}{$l[2]}{$l[3]}{$l[4]}{ty} = $l[6];
		$main::link{$l[1]}{$l[2]}{$l[3]}{$l[4]}{pw} = $l[7];
		$main::link{$l[1]}{$l[2]}{$l[3]}{$l[4]}{du} = $l[8];
		$main::link{$l[1]}{$l[2]}{$l[3]}{$l[4]}{vl} = $l[9];
		$nlink++;
	}
	$sth->finish if $sth;
	$dbh->disconnect;

	&misc::Prt("RLNK:$nlink links ($where) read from $misc::dbname.links\n");
	return $nlink;
}


=head2 FUNCTION ReadNod()

Read nodes table.

B<Options> match statement

B<Globals> main::nod

B<Returns> -

=cut
sub ReadNod {

	my $nnod = 0;

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 1});
	my $where = ($_[0])?"WHERE $_[0]":"";
	my $sth = $dbh->prepare("SELECT * FROM nodes $where");
	$sth->execute();
	while ((my @f) = $sth->fetchrow_array) {
		$main::nod{$f[2]}{na} = $f[0];
		$main::nod{$f[2]}{ip} = &misc::Dec2Ip($f[1]);
		$main::nod{$f[2]}{nv} = $f[3];
		$main::nod{$f[2]}{fs} = $f[4];
		$main::nod{$f[2]}{ls} = $f[5];
		$main::nod{$f[2]}{dv} = $f[6];
		$main::nod{$f[2]}{if} = $f[7];
		$main::nod{$f[2]}{vl} = $f[8];
		$main::nod{$f[2]}{im} = $f[9];
		$main::nod{$f[2]}{iu} = $f[10];
		$main::nod{$f[2]}{ic} = $f[11];
		$main::nod{$f[2]}{au} = $f[12];
		$main::nod{$f[2]}{ac} = $f[13];
		$main::nod{$f[2]}{al} = $f[14];
		$main::nod{$f[2]}{av} = $f[15];
		$main::nod{$f[2]}{i6} = $f[16];
		$main::nod{$f[2]}{tp} = $f[17];
		$main::nod{$f[2]}{up} = $f[18];
		$main::nod{$f[2]}{os} = $f[19];
		$main::nod{$f[2]}{ty} = $f[20];
		$main::nod{$f[2]}{ou} = $f[21];
		$main::nod{$f[2]}{us} = $f[22];
		$nnod++;
	}
	$sth->finish if $sth;
	$dbh->disconnect;

	&misc::Prt("RNOD:$nnod nodes read ($where) from $misc::dbname.nodes\n");
	return $nnod;
}


=head2 FUNCTION BackupCfg()

Backup configuration and any changes.

B<Options> device name

B<Globals> -

B<Returns> -

=cut
sub BackupCfg {

	my ($dv) = @_;
	my $cfg  = join("\n",@misc::curcfg);
	my $chg  = "";

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 1});
	my $sth = $dbh->prepare("SELECT config,changes FROM configs where device = \"$dv\"");
	$sth->execute();

	if($sth->rows == 0 and !$main::opt{t}){								# No previous config found, therefore write new.
		$sth = $dbh->prepare("INSERT INTO configs(device,config,changes,time) VALUES ( ?,?,?,? )");
		$sth->execute ($dv,$cfg,$chg,$main::now);
		&misc::WriteCfg($dv) if defined $main::opt{'B'};
		&misc::Prt("","Bn");
		$misc::mq += &mon::Event('B','100','cfgn',$dv,$dv,"New config with ".length($cfg)." characters added");
	}elsif($sth->rows == 1){									# Previous config found, get changes
		my @pc = $sth->fetchrow_array;
		my @pcfg = split(/\n/,$pc[0]);
		my $achg = &misc::GetChanges(\@pcfg, \@misc::curcfg);
		if(!$main::opt{t}){
			if($achg){									# Only write new, if changed
				$chg  = $pc[1] . "#--- " . localtime($main::now) ." ---#\n". $achg;
				$dbh->do("DELETE FROM configs where device = \"$dv\"");
				$sth = $dbh->prepare("INSERT INTO configs(device,config,changes,time) VALUES ( ?,?,?,? )");
				$sth->execute ($dv,$cfg,$chg,$main::now);
				&misc::WriteCfg($dv) if defined $main::opt{B};
				my $len = length($achg);
				$achg =~ s/["']//g;
				my $msg = "Config changed by $len characters:\n$achg\n";
				my $lvl = ($len > 1000)?100:50;
				$misc::mq += &mon::Event('B',$lvl,'cfgc',$dv,$dv,$msg);
				&misc::Prt('',"Bu");
			} else {
			    &misc::WriteCfg($dv) if defined $main::opt{B} and ! -e "$misc::nedipath/conf/$dv";	# Write config file anyway if no dev folder exists
			}
		}
	}
	$sth->finish if $sth;
	$dbh->disconnect;
}


=head2 FUNCTION WriteDev()

Write a device to devices table.

B<Options> devicename

B<Globals> -

B<Returns> -

=cut
sub WriteDev {

	my ($dv) = @_;
	return if !$dv;

	my $snmpver = ((defined $main::dev{$dv}{rv})?$main::dev{$dv}{rv}:0) + ((defined $main::dev{$dv}{wv})?$main::dev{$dv}{wv}:0) * 4 + ((defined $main::dev{$dv}{hc})?$main::dev{$dv}{hc}:0);

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 0});
	$dbh->do("DELETE FROM  devices where device = \"$dv\"");
	$sth = $dbh->prepare("INSERT INTO devices(	device,devip,serial,type,firstdis,lastdis,services,
							description,devos,bootimage,location,contact,
							devgroup,devmode,snmpversion,readcomm,cliport,login,icon,
							origip,cpu,memcpu,temp,cusvalue,cuslabel,sysobjid,writecomm,devopts,size,stack,maxpoe,totpoe
							) VALUES ( ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,? )");
	$sth->execute (	$dv,
			&misc::Ip2Dec($main::dev{$dv}{ip}),
			$main::dev{$dv}{sn},
			$main::dev{$dv}{ty},
			$main::dev{$dv}{fs},
			$main::dev{$dv}{ls},
			$main::dev{$dv}{sv},
			$main::dev{$dv}{de},
			$main::dev{$dv}{os},
			$main::dev{$dv}{bi},
			$main::dev{$dv}{lo},
			$main::dev{$dv}{co},
			$main::dev{$dv}{dg},
			$main::dev{$dv}{dm},
			$snmpver,
			(defined $main::dev{$dv}{rc})?$main::dev{$dv}{rc}:"",
			(defined $main::dev{$dv}{cp})?$main::dev{$dv}{cp}:0,
			(defined $main::dev{$dv}{us})?$main::dev{$dv}{us}:"",
			&misc::DevIcon($main::dev{$dv}{sv},$main::dev{$dv}{ic}),
			&misc::Ip2Dec($main::dev{$dv}{oi}),
			$main::dev{$dv}{cpu},
			$main::dev{$dv}{mcp},
			$main::dev{$dv}{tmp},
			$main::dev{$dv}{cuv},
			$main::dev{$dv}{cul},
			$main::dev{$dv}{so},
			(defined $main::dev{$dv}{wc})?$main::dev{$dv}{wc}:"",
			$main::dev{$dv}{opt},
			$main::dev{$dv}{siz},
			$main::dev{$dv}{stk},
			$main::dev{$dv}{mpw},
			$main::dev{$dv}{tpw}
			);
	$dbh->commit;
	$sth->finish if $sth;
	$dbh->disconnect;

	&misc::Prt("WDEV:$dv written to $misc::dbname.devices\n");
}

=head2 FUNCTION ReadAddr()

Reads IP and MAC addresses of all IF in DB for topology awareness. 

B<Options> -

B<Globals> misc::ifmac, misc::ifip

B<Returns> -

=cut
sub ReadAddr {

	my $nmac = 0;
	my $nip  = 0;
	my $nip6 = 0;
		
	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 0});
	my $sth = $dbh->prepare("SELECT device,ifmac,ifidx FROM interfaces where ifmac !=\"\"");
	$sth->execute();
	while((my @i) = $sth->fetchrow_array){
		$misc::ifmac{$i[1]}{$i[0]} = $i[2];
		$nmac++;
	}

	$sth = $dbh->prepare("SELECT device,inet_ntoa(ifip),ifip6 FROM networks where ifip != 2130706433");# Ignore 127.0.0.1
	$sth->execute();
	while ((my @i) = $sth->fetchrow_array) {
		if($i[2]){
			$misc::ifip{$i[2]}{$i[0]}++;
			$nip6++;
		}else{
			$misc::ifip{$i[1]}{$i[0]}++;
			$nip++;
		}
	}
	$sth->finish if $sth;
	$dbh->disconnect;

	&misc::Prt("RADDR:$nmac MAC, $nip IP and $nip6 IPv6 addresses read.\n");
}

=head2 FUNCTION ReadInt()

Reads IF information.

B<Options> devicename

B<Globals> main::int

B<Returns> -

=cut
sub ReadInt {

	my $where   = ($_[0])?"WHERE $_[0]":"";
	my $nint = 0;

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 0});
	my $sth = $dbh->prepare("SELECT * FROM interfaces $where");
	$sth->execute();
	while((my @i) = $sth->fetchrow_array){
		$main::int{$i[0]}{$i[2]}{ina} = $i[1];
		$main::int{$i[0]}{$i[2]}{lty} = $i[3];
		$main::int{$i[0]}{$i[2]}{typ} = $i[4];
		$main::int{$i[0]}{$i[2]}{mac} = $i[5];
		$main::int{$i[0]}{$i[2]}{des} = $i[6];
		$main::int{$i[0]}{$i[2]}{ali} = $i[7];
		$main::int{$i[0]}{$i[2]}{sta} = $i[8];
		$main::int{$i[0]}{$i[2]}{spd} = $i[9];
		$main::int{$i[0]}{$i[2]}{dpx} = $i[10];
		$main::int{$i[0]}{$i[2]}{vid} = $i[11];
		$main::int{$i[0]}{$i[2]}{ioc} = $i[12];
		$main::int{$i[0]}{$i[2]}{ier} = $i[13];
		$main::int{$i[0]}{$i[2]}{ooc} = $i[14];
		$main::int{$i[0]}{$i[2]}{oer} = $i[15];
		$main::int{$i[0]}{$i[2]}{dio} = $i[16];
		$main::int{$i[0]}{$i[2]}{die} = $i[17];
		$main::int{$i[0]}{$i[2]}{doo} = $i[18];
		$main::int{$i[0]}{$i[2]}{doe} = $i[19];
		$main::int{$i[0]}{$i[2]}{poe} = $i[27];
		$main::int{$i[0]}{$i[2]}{idi} = $i[20];
		$main::int{$i[0]}{$i[2]}{odi} = $i[21];
		$main::int{$i[0]}{$i[2]}{did} = $i[22];
		$main::int{$i[0]}{$i[2]}{dod} = $i[23];
		$main::int{$i[0]}{$i[2]}{ibr} = $i[24];
		$main::int{$i[0]}{$i[2]}{dib} = $i[25];
		$main::int{$i[0]}{$i[2]}{chg} = $i[26];
		$main::int{$i[0]}{$i[2]}{poe} = $i[27];

		$main::int{$i[0]}{$i[2]}{plt} = $i[3];							# Needed for link tracking in misc::CheckIf
		$main::int{$i[0]}{$i[2]}{pst} = $i[8];
		$main::int{$i[0]}{$i[2]}{psp} = $i[9];
		$main::int{$i[0]}{$i[2]}{pdp} = $i[10];
		$main::int{$i[0]}{$i[2]}{pvi} = $i[11];
		$main::int{$i[0]}{$i[2]}{pcg} = $i[26];
		$main::int{$i[0]}{$i[2]}{pco} = $i[28];

		$nint++;
	}
	$sth->finish if $sth;
	$dbh->disconnect;

	&misc::Prt("RIF :$nint IF read ($where) from $misc::dbname.interfaces\n");
	return $nint;
}


=head2 FUNCTION WriteInt()

Write the interfaces table, calculate deltas and notify if desired.

B<Options> devicename

B<Globals> main::int

B<Returns> -

=cut
sub WriteInt {

	my ($dv,$skip) = @_;
	my $tint = 0;

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 0});
	$dbh->do("DELETE FROM  interfaces where device = \"$dv\"");
	$sth = $dbh->prepare("INSERT INTO interfaces(	device,ifname,ifidx,linktype,iftype,ifmac,ifdesc,alias,ifstat,speed,duplex,pvid,
							inoct,inerr,outoct,outerr,dinoct,dinerr,doutoct,douterr,indis,outdis,dindis,doutdis,inbrc,dinbrc,lastchg,poe,comment)
							VALUES ( ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,? )");
	foreach my $i ( sort keys %{$main::int{$dv}} ){
		if(!$main::int{$dv}{$i}{new}){
			&misc::Prt("WIF :Index $i not found, not writing\n");
		}else{
			&misc::CheckIf($dv,$i,$skip);
			$sth->execute (	$dv,
					$main::int{$dv}{$i}{ina},
					$i,
					$main::int{$dv}{$i}{lty},
					$main::int{$dv}{$i}{typ},
					$main::int{$dv}{$i}{mac},
					substr($main::int{$dv}{$i}{des},0,255),
					substr($main::int{$dv}{$i}{ali},0,64),
					$main::int{$dv}{$i}{sta},
					$main::int{$dv}{$i}{spd},
					$main::int{$dv}{$i}{dpx},
					$main::int{$dv}{$i}{vid},
					$main::int{$dv}{$i}{ioc},
					$main::int{$dv}{$i}{ier},
					$main::int{$dv}{$i}{ooc},
					$main::int{$dv}{$i}{oer},
					$main::int{$dv}{$i}{dio},
					$main::int{$dv}{$i}{die},
					$main::int{$dv}{$i}{doo},
					$main::int{$dv}{$i}{doe},
					$main::int{$dv}{$i}{idi},
					$main::int{$dv}{$i}{odi},
					$main::int{$dv}{$i}{did},
					$main::int{$dv}{$i}{dod},
					$main::int{$dv}{$i}{ibr},
					$main::int{$dv}{$i}{dib},
					$main::int{$dv}{$i}{chg},
					$main::int{$dv}{$i}{poe},
					substr($main::int{$dv}{$i}{com},0,255) );
			$tint++;
		}
	}
	$dbh->commit;
	$sth->finish if $sth;
	$dbh->disconnect;

	&misc::Prt("WIF :$tint interfaces written to $misc::dbname.interfaces\n");
}


=head2 FUNCTION ReadLink()

Write the modules table, detect changes and notify if desired.

B<Options> devicename

B<Globals> -

B<Returns> -

=cut
sub WriteMod {

	my ($dv) = @_;
	my $nmod = 0;
	my %dbmod= ();

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 0});
	if(exists $main::mon{$dv} and $misc::notify =~ /m/i){						# Track existing mods if enabled
		my $sth = $dbh->prepare("SELECT * FROM modules WHERE device = \"$dv\"");
		$sth->execute();
		while ((my @f) = $sth->fetchrow_array) {
			$dbmod{$f[8]} = 1;
			if(exists $main::mod{$dv}{$f[8]}){						# Check idx to avoid defining entry..
				if($f[3] ne $main::mod{$dv}{$f[8]}{de}){				# ..this would define!
					$misc::mq += &mon::Event('M',150,'nedo',$dv,$dv,"Module $f[3] SN:$f[4] in $f[1] was changed to a $main::mod{$dv}{$f[8]}{de} with SN:$main::mod{$dv}{$f[8]}{sn}");
				}elsif($f[4] and $f[4] ne $main::mod{$dv}{$f[8]}{sn}){
					$misc::mq += &mon::Event('M',150,'nedo',$dv,$dv,"Module $f[3] SN:$f[4] in $f[1] got replaced with same model and SN:$main::mod{$dv}{$f[8]}{sn}");
				}
			}else{
				$misc::mq += &mon::Event('M',150,'nedo',$dv,$dv,"Module $f[3] SN:$f[4] in $f[1] has been removed");
			}
		}
	}
	$sth->finish if $sth;
	$dbh->do("DELETE FROM  modules where device = \"$dv\"");
	my $sth = $dbh->prepare("INSERT INTO modules(device,slot,model,moddesc,serial,hw,fw,sw,modidx,modclass,status) VALUES ( ?,?,?,?,?,?,?,?,?,?,? )");
	foreach my $i ( sort keys %{$main::mod{$dv}} ){
		$sth->execute (	$dv,
				$main::mod{$dv}{$i}{sl},
				$main::mod{$dv}{$i}{mo},
				$main::mod{$dv}{$i}{de},
				$main::mod{$dv}{$i}{sn},
				$main::mod{$dv}{$i}{hw},
				$main::mod{$dv}{$i}{fw},
				$main::mod{$dv}{$i}{sw},
				$i,
				$main::mod{$dv}{$i}{mc},
				$main::mod{$dv}{$i}{st}
				);

		if(exists $main::mon{$dv} and $main::dev{$dv}{fs} ne $main::now and !exists $dbmod{$i}){
			$misc::mq += &mon::Event('M',150,'nedo',$dv,$dv,"New $main::mod{$dv}{$i}{de} module with SN:$main::mod{$dv}{$i}{sn} found in $main::mod{$dv}{$i}{sl}");
		}
		$nmod++;
	}
	$dbh->commit;
	$sth->finish if $sth;
	$dbh->disconnect;
	&misc::Prt("WMOD:$nmod modules written to $misc::dbname.modules\n");
}


=head2 FUNCTION WriteVlan()

Rewrites the vlans of a given device.

B<Options> devicename

B<Globals> -

B<Returns> -

=cut
sub WriteVlan {

	my ($dv) = @_;
	my $nvlans = 0;

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 0});
	$dbh->do("DELETE FROM  vlans where device = \"$dv\"");
	my $sth = $dbh->prepare("INSERT INTO vlans(device,vlanid,vlanname) VALUES ( ?,?,? )");
	foreach my $i ( sort keys %{$main::vlan{$dv}} ){
		$sth->execute ( $dv,$i,$main::vlan{$dv}{$i} );
		$nvlans++;
	}
	$dbh->commit;
	$sth->finish if $sth;
	$dbh->disconnect;
	&misc::Prt("WVLN:$nvlans vlans written to $misc::dbname.vlans\n");
}


=head2 FUNCTION ReadLink()

Rewrites the networks of a given device.

B<Options> devicename

B<Globals> -

B<Returns> -

=cut
sub WriteNet {

	my ($dv) = @_;
	my $nip  = 0;

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 0});
	$dbh->do("DELETE FROM  networks where device = \"$dv\"");
	my $sth = $dbh->prepare("INSERT INTO networks(	device,ifname,ifip,ifip6,prefix,vrfname,status ) VALUES ( ?,?,?,?,?,?,? )");
	foreach my $n ( sort keys %{$main::net{$dv}} ){
		$sth->execute (	$dv,
				$main::net{$dv}{$n}{ifn},
				((!$main::net{$dv}{$n}{ip6})?&misc::Ip2Dec($n):""),
				(($main::net{$dv}{$n}{ip6})?$n:""),
				$main::net{$dv}{$n}{pfx},
				$main::net{$dv}{$n}{vrf},
				$main::net{$dv}{$n}{sta} );
		$nip++;
	}
	$dbh->commit;
	$sth->finish if $sth;
	$dbh->disconnect;
	&misc::Prt("WNET:$nip networks written to $misc::dbname.networks\n");
}


=head2 FUNCTION WriteLink()

Writes the links of a given device. Will just return without argument
or if there are no links for this device.

B<Options> devicename

B<Globals> -

B<Returns> -

=cut
sub WriteLink {

	my ($dv) = @_;
	return if !$dv or !exists $main::link{$dv} ;

	my $nlink  = 0;
	my $nslink = 0;

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 0});
	$dbh->do("DELETE FROM links where device = \"$dv\" AND linktype != \"STAT\"");
	my $sth = $dbh->prepare("INSERT INTO links(device,ifname,neighbor,nbrifname,bandwidth,linktype,linkdesc,nbrduplex,nbrvlanid,time) VALUES ( ?,?,?,?,?,?,?,?,?,? )");

	foreach my $i ( sort keys %{$main::link{$dv}} ){
		foreach my $ne ( sort keys %{$main::link{$dv}{$i}} ){
			foreach my $ni ( sort keys %{$main::link{$dv}{$i}{$ne}} ){
				if($main::link{$dv}{$i}{$ne}{$ni}{ty} ne 'STAT'){
					if(!defined $main::link{$dv}{$i}{$ne}{$ni}{pw}){$main::link{$dv}{$i}{$ne}{$ni}{pw} = 0}
					$sth->execute (	$dv,$i,$ne,$ni,
							$main::link{$dv}{$i}{$ne}{$ni}{bw},
							$main::link{$dv}{$i}{$ne}{$ni}{ty},
							$main::link{$dv}{$i}{$ne}{$ni}{de},
							$main::link{$dv}{$i}{$ne}{$ni}{du},
							$main::link{$dv}{$i}{$ne}{$ni}{vl},
							$main::now );
					$nlink++;
				}else{
					$nslink++;
				}
			}
		}
	}
	$dbh->commit;
	$sth->finish if $sth;
	$dbh->disconnect;
	&misc::Prt("WLNK:$nlink (ignoring $nslink static) links written to $misc::dbname.links\n");
}


=head2 FUNCTION UnStock()

Update Devices/Modules in Stock, which are discovered on the network.

B<Options> devicename

B<Globals> -

B<Returns> -

=cut
sub UnStock {

	my $dv = $_[0];

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 1});
	if( $dbh->do("UPDATE stock SET time=\"$main::now\",comment=\"Discovered as $dv with IP $main::dev{$dv}{ip}\",state=100 where serial = \"$main::dev{$dv}{sn}\" and state != 100") + 0){
		&misc::Prt("STOK:Discovered device $main::dev{$dv}{sn} set active in $misc::dbname.stock\n");
	}
	foreach my $i ( sort keys %{$main::mod{$dv}} ){
		if($main::mod{$dv}{$i}{sn}){
			if( $dbh->do("UPDATE stock SET time=\"$main::now\",comment=\"Discovered in $dv $main::mod{$dv}{$i}{sl}\",state=100 where serial = \"$main::mod{$dv}{$i}{sn}\" and state != 100") + 0){
				&misc::Prt("STOK:Discovered module $main::mod{$dv}{$i}{sn} set active in $misc::dbname.stock\n");
			}
		}
	}
	$dbh->disconnect;
}


=head2 FUNCTION WriteNod()

Writes the nodes table by only connecting once and preparing all actions combined to scale for large networks using multiple threads.
In addition entries from IF and IP track tables are deleted upon retiring a node.

B<Options> -

B<Globals> main::nod

B<Returns> -

=cut
sub WriteNod {

	my $dnod = my $inod = my $unod = 0;

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 0});

	my $std = $dbh->prepare("DELETE FROM nodes WHERE mac=?");
	my $stf = $dbh->prepare("DELETE FROM iftrack WHERE mac=?");
	my $sta = $dbh->prepare("DELETE FROM iptrack WHERE mac=?");

	my $sti = $dbh->prepare("INSERT INTO nodes(	name,nodip,mac,oui,firstseen,lastseen,device,ifname,vlanid,ifmetric,ifupdate,ifchanges,
							ipupdate,ipchanges,iplost,arpval,nodip6,tcpports,udpports,nodtype,nodos,osupdate,noduser) VALUES ( ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,? )");

	my $stu = $dbh->prepare("UPDATE nodes SET	name=?,nodip=?,mac=?,oui=?,firstseen=?,lastseen=?,device=?,ifname=?,vlanid=?,ifmetric=?,ifupdate=?,ifchanges=?,
							ipupdate=?,ipchanges=?,iplost=?,arpval=?,nodip6=?,tcpports=?,udpports=?,nodtype=?,nodos=?,osupdate=?,noduser=? WHERE mac=? LIMIT 1");# update only 1st to let multiple nodes retire (should not happen anyway due to new nodelock with PID)

	foreach my $mcvl ( sort keys %main::nod ){							# Based on Lukas' idea
		if($main::nod{$mcvl}{ls} < $misc::retire){
			$std->execute($mcvl);
			$sta->execute($mcvl);
			$stf->execute($mcvl);
			$dnod++;
		}elsif($main::nod{$mcvl}{fs} == $main::now){
			$sti->execute(	$main::nod{$mcvl}{na},
					&misc::Ip2Dec($main::nod{$mcvl}{ip}),
					$mcvl,
					$main::nod{$mcvl}{nv},
					$main::nod{$mcvl}{fs},
					$main::nod{$mcvl}{ls},
					$main::nod{$mcvl}{dv},
					$main::nod{$mcvl}{if},
					$main::nod{$mcvl}{vl},
					$main::nod{$mcvl}{im},
					$main::nod{$mcvl}{iu},
					$main::nod{$mcvl}{ic},
					$main::nod{$mcvl}{au},
					$main::nod{$mcvl}{ac},
					$main::nod{$mcvl}{al},
					$main::nod{$mcvl}{av},
					$main::nod{$mcvl}{i6},
					$main::nod{$mcvl}{tp},
					$main::nod{$mcvl}{up},
					$main::nod{$mcvl}{os},
					$main::nod{$mcvl}{ty},
					$main::nod{$mcvl}{ou},
					$main::nod{$mcvl}{us} );
			$inod++;
		}else{
			$stu->execute(	$main::nod{$mcvl}{na},
					&misc::Ip2Dec($main::nod{$mcvl}{ip}),
					$mcvl,
					$main::nod{$mcvl}{nv},
					$main::nod{$mcvl}{fs},
					$main::nod{$mcvl}{ls},
					$main::nod{$mcvl}{dv},
					$main::nod{$mcvl}{if},
					$main::nod{$mcvl}{vl},
					$main::nod{$mcvl}{im},
					$main::nod{$mcvl}{iu},
					$main::nod{$mcvl}{ic},
					$main::nod{$mcvl}{au},
					$main::nod{$mcvl}{ac},
					$main::nod{$mcvl}{al},
					$main::nod{$mcvl}{av},
					$main::nod{$mcvl}{i6},
					$main::nod{$mcvl}{tp},
					$main::nod{$mcvl}{up},
					$main::nod{$mcvl}{os},
					$main::nod{$mcvl}{ty},
					$main::nod{$mcvl}{ou},
					$main::nod{$mcvl}{us},
					$mcvl );

			$unod++;
		}

	}
	$dbh->commit;
	$dbh->disconnect;
	&misc::Prt("WNOD:$dnod nodes retired, $inod inserted and $unod updated in $misc::dbname.nodes\n");
}

=head2 FUNCTION ReadMon()

Read monitoring table.

B<Options> type = dev, devip(decimal) or node

B<Globals> main::mon

B<Returns> -

=cut
sub ReadMon {

	my $nmon  = 0;
	my $sth = "";

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 1});

	if($_[0] =~ /^[0-9]+$/){									# For single dev (used in trap.pl)
		$sth = $dbh->prepare("SELECT * FROM monitoring WHERE monip = $_[0]");
	}elsif($_[0] eq "dev"){
		$sth = $dbh->prepare("SELECT monitoring.*,type,snmpversion & 3,readcomm FROM monitoring LEFT OUTER JOIN devices ON (monitoring.name = devices.device ) WHERE class = \"dev\"");
	}elsif($_[0] eq "node"){
		$sth = $dbh->prepare("SELECT * FROM monitoring WHERE class = \"node\"");
	}else{
		die "no ReadMon class!";
	}

	$sth->execute();
	while ((my @f) = $sth->fetchrow_array) {
		my $na = $f[0];
		my $ip = &misc::Dec2Ip($f[1]);
		$main::srcna{$ip} = $na;
		$main::mon{$na}{ip} = $ip;
		$main::mon{$na}{cl} = $f[2];
		$main::mon{$na}{te} = $f[3];
		$main::mon{$na}{to} = $f[4];
		$main::mon{$na}{tr} = $f[5];
		$main::mon{$na}{lk} = $f[6];
		$main::mon{$na}{st} = $f[7];
		$main::mon{$na}{lo} = $f[8];
		$main::mon{$na}{ok} = $f[9];
		$main::mon{$na}{ly} = $f[10];
		$main::mon{$na}{lm} = $f[11];
		$main::mon{$na}{la} = $f[12];
		$main::mon{$na}{up} = $f[13];
		$main::mon{$na}{al} = $f[14];
		$main::mon{$na}{ef} = $f[15];
		$main::mon{$na}{el} = $f[16];
		$main::mon{$na}{ed} = $f[17];
		$main::mon{$na}{dy} = $f[18];
		$main::mon{$na}{dv} = $f[19];								# Used for viewdev
		$main::mon{$na}{no} = $f[20];								# Per Target notify string
		$main::mon{$na}{nr} = $f[21];								# Per Target no-reply threshold
		$main::mon{$na}{lw} = $f[22];
		$main::mon{$na}{ca} = $f[23];
		$main::mon{$na}{ma} = $f[24];
		$main::mon{$na}{ta} = $f[25];
		$main::mon{$na}{pw} = $f[26];
		$main::mon{$na}{ap} = $f[27];
		$main::mon{$na}{sa} = $f[28];
		$main::mon{$na}{dc} = 0;								# Dependendant count
		$main::mon{$na}{ds} = 'up';								# Dependency status
		$main::mon{$na}{ty} = ($f[2] eq 'dev')?$f[29]:0;
		$main::mon{$na}{rv} = ($f[2] eq 'dev')?$f[30]:0;
		$main::mon{$na}{rc} = ($f[2] eq 'dev')?$f[31]:'';
		$nmon++;
	}
	$sth->finish if $sth;
	$dbh->disconnect;
	&misc::Prt("RMON:$nmon entries ($_[0]) read from $misc::dbname.monitoring\n");
	return $nmon;
}


=head2 FUNCTION ReadUser()

Read users table.

B<Options> match statement

B<Globals> -

B<Returns> -

=cut
sub ReadUser {

	my $nusr  = 0;

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 1});
	my $where = ($_[0])?"WHERE $_[0]":"";
	my $sth = $dbh->prepare("SELECT user,email,phone,viewdev FROM users $where");
	$sth->execute();
	while ((my @f) = $sth->fetchrow_array) {
		$main::usr{$f[0]}{ml} = $f[1];
		$main::usr{$f[0]}{ph} = $f[2];
		$main::usr{$f[0]}{ph} =~ s/\D//g;							# Strip anything that isn't a number
		$main::usr{$f[0]}{vd} = $f[3];
		$nusr++;
	}
	$sth->finish if $sth;
	$dbh->disconnect;
	&misc::Prt("RUSR:$nusr entries ($_[0]) read from $misc::dbname.users\n");

	return $nusr;
}


=head2 FUNCTION Insert()

Insert DB Record

B<Options> table, string of columns, string of values

B<Globals> -

B<Returns> -

=cut
sub Insert {# TODO consider using hashref as argument, with that this can be used for writing stuff with ' and " (like configs)

	&misc::NagPipe($_[2]) if $_[0] eq 'events' and $misc::nagpipe;
	
	my $r = 0;
	unless($main::opt{'t'}){
		my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 1});
		$r = $dbh->do("INSERT INTO $_[0] ($_[1]) VALUES ($_[2])") || die "ERR :INSERT INTO $_[0] ($_[1]) VALUES ($_[2])\n";
		$dbh->disconnect;
	}
	&misc::Prt("INS :$r ROWS INTO $_[0] ($_[1]) VALUES ($_[2])\n") if $main::opt{'d'};

	return $r;
}


=head2 FUNCTION Delete()

Delete DB Record.

B<Options> table,match statement

B<Globals> -

B<Returns> -

=cut
sub Delete {

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 1});
	my $r = $dbh->do("DELETE FROM  $_[0] WHERE $_[1]") || die "ERR : DELETE FROM  $_[0] WHERE $_[1]\n";

	&misc::Prt("ERR :$dbh->errstr\n") if(!$r);							# Something went wrong
	$r = 0 if($r eq '0E0');									# 0E0 actually means 0
	$dbh->disconnect;
	&misc::Prt("DEL :$r ROWS FROM $_[0] WHERE $_[1]\n") if $main::opt{'d'};

	return $r;
}


=head2 FUNCTION Update()

Update DB value(s).

B<Options> table, set statement, match statement

B<Globals> -

B<Returns> result

=cut
sub Update {

	my ($table, $set, $match) = @_;

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 1});
	my $r = $dbh->do("UPDATE $table SET $set WHERE $match") || die "ERR : UPDATE $table SET $set WHERE $match\n";
	$dbh->disconnect;
	&misc::Prt("UPDT:$r ROWS FROM $table SET $set WHERE $match\n") if $main::opt{'d'};

	return $r;
}

=head2 FUNCTION Select()

Select values from a table.

B<Options> table, [hashkey], columns, match statement, join, using column(s)

B<Globals> -

B<Returns> value if only 1 row and column is the result, hashref (if key provided) or arrayref otherwhise

=cut
sub Select{

	my ($t, $key, $c, $m, $j, $u) = @_;

	my $qry = ($c)?"SELECT $c FROM $t":"SELECT * FROM $t";
	$qry   .= ($j and $u)?" LEFT JOIN $j USING ($u)":"";
	$qry   .= ($m)?" WHERE $m":"";
	&misc::Prt("SEL :> $qry; ") if $main::opt{'d'};

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 0});
	my $res = "";
	my $nre = 0;
	if($key){
		$res = $dbh->selectall_hashref($qry, $key);
		$nre = scalar keys %$res;
	}else{
		my $a = $dbh->selectall_arrayref($qry);
		$nre = scalar @$a;
		if($c !~ /[,*]/ and $nre == 1){								# dereference single values
			$res =  $$a[0][0];
		}else{
			$res = $a;
		}
	}
	$dbh->disconnect;

	&misc::Prt(" yields $nre rows\n") if $main::opt{'d'};
	return $res;
}

1;
