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
sub InitDB{

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
	if ($mysqlVer =~ /5\./) {									#fix for mysql 5.0 with old client libs
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
						vtpdomain VARCHAR(32), vtpmode TINYINT unsigned, snmpversion TINYINT unsigned,
						readcomm VARCHAR(32), cliport SMALLINT unsigned, login VARCHAR(32),
						icon VARCHAR(16), origip INT unsigned, cpu TINYINT unsigned,memcpu BIGINT unsigned,
						temp TINYINT unsigned, cusvalue BIGINT unsigned, cuslabel VARCHAR(32), sysobjid VARCHAR(255),
						writecomm VARCHAR(32), INDEX (device(8)), PRIMARY KEY (device) )");
 	$dbh->commit;

	print "devdel, ";
	$dbh->do("CREATE TABLE devdel	(	device VARCHAR(64) NOT NULL UNIQUE, user VARCHAR(32), time INT unsigned,
						INDEX (device(8)), PRIMARY KEY  (device) )");
 	$dbh->commit;

	print "modules, ";
	$dbh->do("CREATE TABLE modules	(	device VARCHAR(64) NOT NULL, slot VARCHAR(64), model VARCHAR(32), moddesc VARCHAR(255),
						serial VARCHAR(32), hw VARCHAR(128), fw VARCHAR(128), sw VARCHAR(128),
						modidx VARCHAR(8), INDEX (device(8)), INDEX (slot(8)) ) ");# modidx can look like 1.2, thus needs to be varchar
 	$dbh->commit;

	print "interfaces, ";
	$dbh->do("CREATE TABLE interfaces(	device VARCHAR(64) NOT NULL, ifname VARCHAR(32) NOT NULL, ifidx BIGINT unsigned,
						linktype CHAR(4), iftype INT unsigned, ifmac CHAR(12),
						ifdesc VARCHAR(255), alias VARCHAR(64), ifstat TINYINT unsigned,
						speed BIGINT unsigned, duplex CHAR(2), pvid SMALLINT unsigned default 0,
						inoct BIGINT unsigned, inerr INT unsigned, outoct BIGINT unsigned, outerr INT unsigned,
						dinoct BIGINT signed default 0, dinerr INT signed default 0, doutoct BIGINT signed default 0,
						douterr INT signed default 0, comment VARCHAR(255), poe SMALLINT unsigned default 0,
						INDEX (device(8)), INDEX (ifname(8)),INDEX (ifidx) )");
 	$dbh->commit;

	print "networks, ";
	$dbh->do("CREATE TABLE networks (	device VARCHAR(64) NOT NULL, ifname VARCHAR(32), ifip INT unsigned, mask INT unsigned,
						vrfname VARCHAR(32), status TINYINT unsigned,
						INDEX (device(8)), INDEX (ifname), INDEX (ifip) )");
 	$dbh->commit;

	print "configs, ";
	$dbh->do("CREATE TABLE configs	(	device VARCHAR(64) NOT NULL UNIQUE, config MEDIUMTEXT, changes MEDIUMTEXT ,
						time INT unsigned, INDEX (device(8)), PRIMARY KEY  (device)  )");
 	$dbh->commit;

	print "stock, ";
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
						nbrvlanid SMALLINT unsigned, INDEX(id), INDEX(device(8)), INDEX(ifname(8)),
						INDEX(neighbor(8)), INDEX(nbrifname(8)), PRIMARY KEY(id) )");
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
						test CHAR(6) default '', lastok INT unsigned default 0,	status INT unsigned default 0,
						lost INT unsigned default 0, ok INT unsigned default 0,	latency SMALLINT unsigned default 0, latmax SMALLINT unsigned default 0,
						latavg SMALLINT unsigned default 0, uptime INT unsigned default 0, alert TINYINT unsigned default 0, eventfwd VARCHAR(255) default '',
						eventdel VARCHAR(255) default '', depend VARCHAR(64) default '-', device VARCHAR(64) NOT NULL, INDEX (name(8)), INDEX (device(8)) )");
 	$dbh->commit;

	print "incidents, ";
	$dbh->do("CREATE TABLE incidents(	id INT unsigned NOT NULL AUTO_INCREMENT, level TINYINT unsigned, name VARCHAR(64),
						deps INT unsigned, start INT unsigned, end INT unsigned, user VARCHAR(32),
						time INT unsigned, grp TINYINT unsigned, comment VARCHAR(255), device VARCHAR(64) default '',
						INDEX(id), INDEX(name(8)), INDEX(device(8)), PRIMARY KEY(id) )");
 	$dbh->commit;

	print "nodes, ";
	$dbh->do("CREATE TABLE nodes 	(	name VARCHAR(64), nodip INT unsigned, mac CHAR(12) NOT NULL, oui VARCHAR(32),
						firstseen INT unsigned, lastseen INT unsigned, device VARCHAR(64),
						ifname VARCHAR(32), vlanid SMALLINT unsigned, ifmetric INT unsigned,
						ifupdate INT unsigned, ifchanges INT unsigned,	ipupdate INT unsigned,
						ipchanges INT unsigned, iplost INT unsigned, arpval SMALLINT unsigned,
						tcpports VARCHAR(64), udpports VARCHAR(64), nodtype VARCHAR(64) default '-',
						nodos VARCHAR(64) default '-', osupdate INT unsigned default 0,
						INDEX(name(8)), INDEX(nodip), INDEX(mac), INDEX(vlanid), INDEX(device(8)) )");
 	$dbh->commit;

	print "nodetrack, ";
	$dbh->do("CREATE TABLE nodetrack(	device varchar(64), ifname varchar(32), value varchar(64), source char(8),
						user varchar(32),time int unsigned, INDEX(device(8)), INDEX(ifname(8)) )");
 	$dbh->commit;

	print "iftrack, ";
	$dbh->do("CREATE TABLE iftrack	(	mac CHAR(12) NOT NULL,ifupdate INT unsigned, device VARCHAR(64),
						ifname VARCHAR(32), vlanid SMALLINT unsigned,
						ifmetric TINYINT unsigned, INDEX(mac), INDEX(vlanid), INDEX(device(8)) )");
 	$dbh->commit;
	print "iptrack, ";
	$dbh->do("CREATE TABLE iptrack (	mac CHAR(12) NOT NULL,ipupdate INT unsigned, name VARCHAR(64), nodip INT unsigned,
						vlanid SMALLINT unsigned, device VARCHAR(64) NOT NULL default '',
						INDEX(mac), INDEX(vlanid), INDEX(device(8)) )");
 	$dbh->commit;

	print "stolen, ";
	$dbh->do("CREATE TABLE stolen 	(	name VARCHAR(64), stlip INT unsigned, mac CHAR(12) NOT NULL, device VARCHAR(64),
						ifname VARCHAR(32), user VARCHAR(32), time INT unsigned, comment VARCHAR(255) default '',
						INDEX(mac), INDEX(device(8)), PRIMARY KEY(mac) )");
 	$dbh->commit;

	print "users, ";
	$dbh->do("CREATE TABLE users 	(	user VARCHAR(32) NOT NULL UNIQUE, password VARCHAR(32) NOT NULL default '',
						groups TINYINT unsigned NOT NULL default '0', email VARCHAR(64) default '', phone VARCHAR(32) default '',
						time INT unsigned, lastlogin INT unsigned, comment VARCHAR(255) default '',
						language VARCHAR(16) NOT NULL default 'english', theme VARCHAR(16) NOT NULL default 'default',
						volume TINYINT unsigned NOT NULL default '10', columns TINYINT unsigned NOT NULL default '5',
						msglimit TINYINT unsigned NOT NULL default '5', graphs TINYINT unsigned NOT NULL default '2',
						dateformat VARCHAR(16) NOT NULL default 'j.M y G:i', view VARCHAR(255) default '',
						PRIMARY KEY(user) )");

	$sth = $dbh->prepare("INSERT INTO users (user,password,groups,time,comment,volume,columns,msglimit,graphs) VALUES ( ?,?,?,?,?,?,?,?,? )");
	$sth->execute ( 'admin','21232f297a57a5a743894a0e4a801fc3','255',$main::now,'default admin','100','8','10','3' );
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
	$dbh->do("CREATE TABLE wlan (mac VARCHAR(12),time INT unsigned, INDEX(mac) )");
	my @wlan = ();
	if (-e "$main::p/inc/wlan.txt"){
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
	print " done.\n";
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
		$misc::snmpini{$main::dev{$f[0]}{ip}}{rv} = $main::dev{$f[0]}{rv};			# Tie comm & ver to IP,
		$misc::snmpini{$main::dev{$f[0]}{ip}}{rc} = $main::dev{$f[0]}{rc};
		$misc::snmpini{$main::dev{$f[0]}{oi}}{rv} = $main::dev{$f[0]}{rv};			# it's all we have at first
		$misc::snmpini{$main::dev{$f[0]}{oi}}{rc} = $main::dev{$f[0]}{rc};
		$npdev++;
	}
	$sth->finish if $sth;
	$dbh->disconnect;

	&misc::Prt("RDEV:$npdev devices read ($where) from $misc::dbname.devices\n");
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
		if($f[8] and $misc::useivl and $f[8] =~ /$misc::useivl/){				# Avoid using IVL with vlid 0
			$mc = $f[2].$f[8];
		}else{
			$mc = $f[2];
		}
		$main::nod{$mc}{na} = $f[0];
		$main::nod{$mc}{ip} = &misc::Dec2Ip($f[1]);
		$main::nod{$mc}{nv} = $f[3];
		$main::nod{$mc}{fs} = $f[4];
		$main::nod{$mc}{ls} = $f[5];
		$main::nod{$mc}{dv} = $f[6];
		$main::nod{$mc}{if} = $f[7];
		$main::nod{$mc}{vl} = $f[8];
		$main::nod{$mc}{im} = $f[9];
		$main::nod{$mc}{iu} = $f[10];
		$main::nod{$mc}{ic} = $f[11];
		$main::nod{$mc}{au} = $f[12];
		$main::nod{$mc}{ac} = $f[13];
		$main::nod{$mc}{al} = $f[14];
		$main::nod{$mc}{av} = $f[15];
		$main::nod{$mc}{tp} = $f[16];
		$main::nod{$mc}{up} = $f[17];
		$main::nod{$mc}{os} = $f[18];
		$main::nod{$mc}{ty} = $f[19];
		$main::nod{$mc}{ou} = $f[20];
		$nnod++;
	}
	$sth->finish if $sth;
	$dbh->disconnect;

	&misc::Prt("RNOD:$nnod nodes read ($where) from $misc::dbname.nodes\n");
}


=head2 FUNCTION BackupCfg()

Backup configuration and any changes.

B<Options> device name

B<Globals> -

B<Returns> -

=cut
sub BackupCfg {

	my ($na) = @_;
	my $cfg  = join("\n",@misc::curcfg);
	my $chg  = "";

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 1});
	my $sth = $dbh->prepare("SELECT config,changes FROM configs where device = \"$na\"");
	$sth->execute();

	if($sth->rows == 0 and !$main::opt{t}){								# No previous config found, therefore write new.
		$sth = $dbh->prepare("INSERT INTO configs(device,config,changes,time) VALUES ( ?,?,?,? )");
		$sth->execute ($na,$cfg,$chg,$main::now);
		&misc::WriteCfg($na) if $main::opt{'B'};
		&misc::Prt("","Bn");
		&Insert('events','level,time,source,info,class,device',"\"100\",\"$main::now\",\"$na\",\"New config with ".length($cfg)." characters added\",\"cfgn\",\"$na\"") if $misc::notify =~ /c/i;
	}elsif($sth->rows == 1){									# Previous config found, get changes
		my @pc = $sth->fetchrow_array;
		my @pcfg = split(/\n/,$pc[0]);
		my $achg = &misc::GetChanges(\@pcfg, \@misc::curcfg);
		if(!$main::opt{t}){
			if($achg){									# Only write new, if changed
				$chg  = $pc[1] . "#--- " . localtime($main::now) ." ---#\n". $achg;
				$dbh->do("DELETE FROM configs where device = \"$na\"");
				$sth = $dbh->prepare("INSERT INTO configs(device,config,changes,time) VALUES ( ?,?,?,? )");
				$sth->execute ($na,$cfg,$chg,$main::now);
				&misc::WriteCfg($na) if $main::opt{B};
				my $len = length($achg);
				my $msg = "Config changed by $len characters";
				&misc::Prt("WCFG:$msg\n","Bu");
				if($misc::notify =~ /c/i){
					my $lev = ($len > 1000)?100:50;
					&Insert('events','level,time,source,info,class,device',"\"$lev\",\"$main::now\",\"$na\",\"$msg\",\"cfgc\",\"$na\"");
					&mon::SendMail("Config Changed on $na","$msg:\n\n$achg") if $misc::notify =~ /C/;
				}
			} else {
			    &misc::WriteCfg($na) if $main::opt{B} and ! -e "$misc::nedipath/conf/$na";	# Write config file anyway if no dev folder exists
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

	$main::dev{$dv}{rv} = 0  unless defined $main::dev{$dv}{rv};
	$main::dev{$dv}{rc} = "" unless defined $main::dev{$dv}{rc};
	$main::dev{$dv}{wv} = 0  unless defined $main::dev{$dv}{wv};
	$main::dev{$dv}{wc} = "" unless defined $main::dev{$dv}{wc};
	$main::dev{$dv}{hc} = 0  unless defined $main::dev{$dv}{hc};

	$main::dev{$dv}{oi} = 0  unless defined $main::dev{$dv}{oi};

	$main::dev{$dv}{us} = "" unless defined $main::dev{$dv}{us};
	$main::dev{$dv}{cp} = 0  unless defined $main::dev{$dv}{cp};

	if(!$main::dev{$dv}{ic}){
		if($main::dev{$dv}{sv} > 8){
			$main::dev{$dv}{ic} = 'csan';
		}elsif($main::dev{$dv}{sv} > 4){
			$main::dev{$dv}{ic} = 'w3an';
		}elsif($main::dev{$dv}{sv} > 1){
			$main::dev{$dv}{ic} = 'w2an';
		}else{
			$main::dev{$dv}{ic} = 'w1an';
		}
	}
	my $snmpver = $main::dev{$dv}{rv} + $main::dev{$dv}{wv} * 4 + $main::dev{$dv}{hc};
	my $dip = &misc::Ip2Dec($main::dev{$dv}{ip});
	my $doi = &misc::Ip2Dec($main::dev{$dv}{oi});

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 0});
	$dbh->do("DELETE FROM  devices where device = \"$dv\"");
	$sth = $dbh->prepare("INSERT INTO devices(	device,devip,serial,type,firstdis,lastdis,services,
							description,devos,bootimage,location,contact,
							vtpdomain,vtpmode,snmpversion,readcomm,cliport,login,
							icon,origip,cpu,memcpu, temp, cusvalue, cuslabel, sysobjid, writecomm
							) VALUES ( ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,? )");
	$sth->execute (	$dv,
			$dip,
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
			$main::dev{$dv}{vd},
			$main::dev{$dv}{vm},
			$snmpver,
			$main::dev{$dv}{rc},
			$main::dev{$dv}{cp},
			$main::dev{$dv}{us},
			$main::dev{$dv}{ic},
			$doi,
			$main::dev{$dv}{cpu},
			$main::dev{$dv}{mcp},
			$main::dev{$dv}{tmp},
			$main::dev{$dv}{cuv},
			$main::dev{$dv}{cul},
			$main::dev{$dv}{so},
			$main::dev{$dv}{wc}
			);
	$dbh->commit;
	$sth->finish if $sth;
	$dbh->disconnect;

	&misc::Prt("WDEV:$dv written to $misc::dbname.devices\n");
}


=head2 FUNCTION ReadInt()

Read IF mac & IPs of all but given devices.

B<Options> devicename

B<Globals> misc::ifmac, misc::ifip

B<Returns> -

=cut
sub ReadInt {

	my $nint    = 0;
	my $where   = ($_[0])?"WHERE $_[0]":"";
	%misc::ifip = %misc::ifmac = ();

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 0});
	my $sth = $dbh->prepare("SELECT * FROM interfaces $where");
	$sth->execute();
	while((my @i) = $sth->fetchrow_array){
		$nint++;
		$misc::ifmac{$i[5]}{$i[0]}++ if $i[5];							# only use if a MAC exists!
		#TODO &Prt("IFDB:IF $misc::ifmac{$i[5]}{$i[0]\n") if $misc::ifmac{$i[5]}{$i[0]} > 1;
		if($main::opt{'S'} =~ /i/){
			$main::int{$i[0]}{$i[2]}{ina} = $i[1];
			$main::int{$i[0]}{$i[2]}{lty} = $i[3];
			$main::int{$i[0]}{$i[2]}{typ} = $i[4];
			$main::int{$i[0]}{$i[2]}{mac} = $i[5];
			$main::int{$i[0]}{$i[2]}{des} = $i[6];
			$main::int{$i[0]}{$i[2]}{ali} = $i[7];
			$main::int{$i[0]}{$i[2]}{spd} = $i[9];
			$main::int{$i[0]}{$i[2]}{dup} = $i[10];
			$main::int{$i[0]}{$i[2]}{vid} = $i[11];
			$main::int{$i[0]}{$i[2]}{com} = $i[20];
		}
		if($main::opt{'S'} =~ /t/){
			$main::int{$i[0]}{$i[2]}{ioc} = $i[12];
			$main::int{$i[0]}{$i[2]}{ooc} = $i[14];
			$main::int{$i[0]}{$i[2]}{dio} = $i[16];
			$main::int{$i[0]}{$i[2]}{doo} = $i[18];
		}
		if($main::opt{'S'} =~ /e/){
			$main::int{$i[0]}{$i[2]}{ier} = $i[13];
			$main::int{$i[0]}{$i[2]}{oer} = $i[15];
			$main::int{$i[0]}{$i[2]}{die} = $i[17];
			$main::int{$i[0]}{$i[2]}{doe} = $i[19];
		}
		if($main::opt{'S'} =~ /p/){
			$main::int{$i[0]}{$i[2]}{poe} = $i[21];
		}
		if($main::opt{'S'} =~ /[AO]/){
			$main::int{$i[0]}{$i[2]}{sta} = $i[8];
		}
	}
	$sth->finish if $sth;

	$sth = $dbh->prepare("SELECT device,inet_ntoa(ifip) FROM networks $where");
	$sth->execute();
	while ((my @i) = $sth->fetchrow_array) {
		$misc::ifip{$i[1]}{$i[0]}++;
	}
	$sth->finish if $sth;
	$dbh->disconnect;

	&misc::Prt("RIF :$nint IF read ($where) from $misc::dbname.interfaces\n");
}


=head2 FUNCTION WriteInt()

Write the interfaces table, calculate deltas and notify if desired.

B<Options> devicename

B<Globals> main::int

B<Returns> -

=cut
sub WriteInt {

	my ($dv) = @_;
	my $nint = 0;
	my $nwar = 0;
	my $nalr = 0;

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 0});
	my $sth = $dbh->prepare("SELECT * FROM interfaces WHERE device = \"$dv\"");
	$sth->execute();
	while ((my @f) = $sth->fetchrow_array) {
		if(exists $main::int{$f[0]}{$f[2]}){
			$main::int{$f[0]}{$f[2]}{dio} = $main::int{$f[0]}{$f[2]}{ioc} - $f[12] if $main::opt{'S'} !~ /t/;
			if ($main::int{$f[0]}{$f[2]}{dio} < 0){
				$main::int{$f[0]}{$f[2]}{dio} = 0;
				$main::int{$f[0]}{$f[2]}{ioc} = 71;					# Some switches start with 70, thus 71 means overflow
			}
			$main::int{$f[0]}{$f[2]}{die} = $main::int{$f[0]}{$f[2]}{ier} - $f[13] if $main::opt{'S'} !~ /e/;
			if ($main::int{$f[0]}{$f[2]}{die} < 0){
				$main::int{$f[0]}{$f[2]}{die} = 0;
			}
			$main::int{$f[0]}{$f[2]}{doo} = $main::int{$f[0]}{$f[2]}{ooc} - $f[14] if $main::opt{'S'} !~ /t/;
			if ($main::int{$f[0]}{$f[2]}{doo} < 0){
				$main::int{$f[0]}{$f[2]}{doo} = 0;
				$main::int{$f[0]}{$f[2]}{ooc} = 71;
			}
			$main::int{$f[0]}{$f[2]}{doe} = $main::int{$f[0]}{$f[2]}{oer} - $f[15] if $main::opt{'S'} !~ /e/;
			if ($main::int{$f[0]}{$f[2]}{doe} < 0){
				$main::int{$f[0]}{$f[2]}{doe} = 0;
			}
			if(!($main::int{$f[0]}{$f[2]}{sta} & 4) and !($main::int{$f[0]}{$f[2]}{sta} & 1) and $f[8] & 1){	# 4 means adm stat is skipped
				$main::int{$f[0]}{$f[2]}{sta} = $main::int{$f[0]}{$f[2]}{sta} + 128;
				my $msg = "IF $main::int{$f[0]}{$f[2]}{ina} has been disabled";
				&misc::Prt("WIF :$msg\n");
				if($misc::notify =~ /a/i){
					&Insert('events','level,time,source,info,class,device',"\"150\",\"$main::now\",\"$dv\",\"$msg\",\"nedi\",\"$dv\"");
					&mon::SendMail("IF disabled on $dv",$msg) if $misc::notify =~ /A/;

				}
			}
			if(!($main::int{$f[0]}{$f[2]}{sta} & 8) and !($main::int{$f[0]}{$f[2]}{sta} & 2) and $f[8] & 2){	# 8 means opr stat is skipped
				my $msg = "IF $main::int{$f[0]}{$f[2]}{ina} went down";
				&misc::Prt("WIF :$msg\n");
				if($misc::notify =~ /o/i){
					&Insert('events','level,time,source,info,class,device',"\"150\",\"$main::now\",\"$dv\",\"$msg\",\"nedi\",\"$dv\"");
					&mon::SendMail("IF down on $dv",$msg) if $misc::notify =~ /O/;

				}
				if($main::int{$f[0]}{$f[2]}{lty} and $misc::notify =~ /l/i){
					&Insert('events','level,time,source,info,class,device',"\"150\",\"$main::now\",\"$dv\",\"$msg\",\"nedi\",\"$dv\"") if $misc::notify !~ /z/;
					&mon::SendMail("$main::int{$f[0]}{$f[2]}{lty} Link down on $dv",$msg) if $misc::notify =~ /L/;
				}
			}
			if($main::int{$f[0]}{$f[2]}{lty} ne $f[3]){
				my $msg ="Linktype on $main::int{$f[0]}{$f[2]}{ina} changed ".(($f[3])?"from $f[3] ":"").(($main::int{$f[0]}{$f[2]}{lty})?"to $main::int{$f[0]}{$f[2]}{lty}":"");
				&misc::Prt("WIF :$msg\n");
				if($misc::notify =~ /l/i){
					&Insert('events','level,time,source,info,class,device',"\"150\",\"$main::now\",\"$dv\",\"$msg\",\"nedi\",\"$dv\"");
					&mon::SendMail("Linkchange on $dv",$msg) if $misc::notify =~ /L/;
				}
			}
		}
	}
	$dbh->do("DELETE FROM  interfaces where device = \"$dv\"");
	$sth = $dbh->prepare("INSERT INTO interfaces(	device,ifname,ifidx,linktype,iftype,ifmac,ifdesc,alias,ifstat,speed,duplex,pvid,
							inoct,inerr,outoct,outerr,dinoct,dinerr,doutoct,douterr,comment,poe)
							VALUES ( ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,? )");
	my $nethif = 0;
	my $totpoe = 0;
	my $avgpoe = 0;
	foreach my $i ( sort keys %{$main::int{$dv}} ){
		if($main::int{$dv}{$i}{ina}){
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
					substr($main::int{$dv}{$i}{com},0,255),
					$main::int{$dv}{$i}{poe} );
			$totpoe += $main::int{$dv}{$i}{poe}/1000;
			$nethif++ if $main::int{$dv}{$i}{typ} =~ /^(6|7|117)$/;
			$nint++;
			if($misc::notify =~ /t/i and $main::int{$dv}{$i}{spd}){
				my $rioct = int( $main::int{$dv}{$i}{dio} * 800 / ($misc::discostep * $main::int{$dv}{$i}{spd}) );
				my $rooct = int( $main::int{$dv}{$i}{doo} * 800 / ($misc::discostep * $main::int{$dv}{$i}{spd}) );
				if($rioct > $misc::trfa and $rioct < 101){
					my $msg = "Average inbound traffic on $main::int{$dv}{$i}{ina} exeeds alert threshold of ${misc::trfa}% with $rioct% for ${misc::discostep}s!";
					&Insert('events','level,time,source,info,class,device',"\"200\",\"$main::now\",\"$dv\",\"$msg\",\"trfa\",\"$dv\"");
					&mon::SendMail("High Inbound Traffic on $dv",$msg) if $misc::notify =~ /T/;
					$nalr++;
				}elsif($rioct > $misc::trfw and $rioct < 101){
					my $msg = "Average inbound traffic on $main::int{$dv}{$i}{ina} exeeds warning threshold of ${misc::trfw}% with $rioct% for ${misc::discostep}s";
					&Insert('events','level,time,source,info,class,device',"\"150\",\"$main::now\",\"$dv\",\"$msg\",\"trfw\",\"$dv\"");
					$nwar++;
				}
				if($rooct > $misc::trfa and $rooct < 101){
					my $msg = "Average outbound traffic on $main::int{$dv}{$i}{ina} exeeds alert threshold of ${misc::trfa}% with $rooct% for ${misc::discostep}s!";
					&Insert('events','level,time,source,info,class,device',"\"200\",\"$main::now\",\"$dv\",\"$msg\",\"trfa\",\"$dv\"");
					&mon::SendMail("High Outbound Traffic on $dv",$msg) if $misc::notify =~ /T/;
					$nalr++;
				}elsif($rooct > $misc::trfw and $rooct < 101){
					my $msg = "Average outbound traffic on $main::int{$dv}{$i}{ina} exeeds warning threshold of ${misc::trfw}% with $rooct% for ${misc::discostep}s";
					&Insert('events','level,time,source,info,class,device',"\"150\",\"$main::now\",\"$dv\",\"$msg\",\"trfw\",\"$dv\"");
					$nwar++;
				}
			}

			if($misc::notify =~ /e/i){
				if($main::int{$dv}{$i}{typ} != 71){						# Ignore Wlan IF
					if($main::int{$dv}{$i}{die} > $misc::discostep){
						my $msg = "$main::int{$dv}{$i}{die} inbound errors on $main::int{$dv}{$i}{ina} within ${misc::discostep}s!";
						&Insert('events','level,time,source,info,class,device',"\"200\",\"$main::now\",\"$dv\",\"$msg\",\"trfe\",\"$dv\"");
						&mon::SendMail("Many Inbound Errors on $dv",$msg) if $misc::notify =~ /E/;
						$nalr++;
					}elsif($main::int{$dv}{$i}{die} > $misc::discostep / 60){
						my $msg = "$main::int{$dv}{$i}{die} inbound errors on $main::int{$dv}{$i}{ina} in ${misc::discostep}s";
						&Insert('events','level,time,source,info,class,device',"\"150\",\"$main::now\",\"$dv\",\"$msg\",\"trfe\",\"$dv\"");						
						$nwar++;
					}
					if($main::int{$dv}{$i}{doe} > $misc::discostep){
						my $msg = "$main::int{$dv}{$i}{doe} outbound errors on $main::int{$dv}{$i}{ina} within ${misc::discostep}s!";
						&Insert('events','level,time,source,info,class,device',"\"200\",\"$main::now\",\"$dv\",\"$msg\",\"trfe\",\"$dv\"");
						&mon::SendMail("Many Outbound on $dv",$msg) if $misc::notify =~ /E/;
						$nalr++;
					}elsif($main::int{$dv}{$i}{doe} > $misc::discostep / 60){
						my $msg = "$main::int{$dv}{$i}{doe} outbound errors on $main::int{$dv}{$i}{ina} in ${misc::discostep}s";
						&Insert('events','level,time,source,info,class,device',"\"150\",\"$main::now\",\"$dv\",\"$msg\",\"trfe\",\"$dv\"");						
						$nwar++;
					}
				}
			}
		}else{
			&misc::Prt("WIF :No name for index $i, potential error in .def\n");
		}
	}
	$dbh->commit;
	$sth->finish if $sth;
	$dbh->disconnect;
	$avgpoe = sprintf("%.0f",$totpoe/$nethif) if $nethif;
	if($avgpoe > $misc::poew){
		my $msg = "Average PoE delivery ${avgpoe}W exceeds threshold of ${misc::poew}W (${totpoe}W total)";
		&misc::Prt("WIF :$msg\n");
		&Insert('events','level,time,source,info,class,device',"\"150\",\"$main::now\",\"$dv\",\"$msg\",\"nedi\",\"$dv\"") if $misc::notify !~ /z/;		
		$nwar++;
	}
	&misc::Prt("WIF :$nint interfaces written to $misc::dbname.interfaces\n");
	&misc::Prt("WIF :$nwar warnings/$nalr alerts written to $misc::dbname.events\n") if ($nwar or $nalr);
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
	my %dbmod = ();

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 0});
	if($misc::notify =~ /m/i){									# Track existing mods if enabled
		my $sth = $dbh->prepare("SELECT * FROM modules WHERE device = \"$dv\"");# TODO adapt for printers and ESX
		$sth->execute();
		while ((my @f) = $sth->fetchrow_array) {
			$dbmod{$f[8]} = 1;
			if(exists $main::mod{$dv}{$f[8]}){						# Check idx to avoid defining entry..
				if($f[3] ne $main::mod{$dv}{$f[8]}{de}){				# ..this would define!
					my $msg = "Module $f[3] SN:$f[4] in slot $f[1] was changed to a $main::mod{$dv}{$f[8]}{de} with SN:$main::mod{$dv}{$f[8]}{sn}";
					&misc::Prt("WMOD:$msg\n");
					&Insert('events','level,time,source,info,class,device',"\"150\",\"$main::now\",\"$dv\",\"$msg\",\"nedi\",\"$dv\"");
					&mon::SendMail("Module Changed on $dv",$msg) if $misc::notify =~ /M/;
				}elsif($f[4] and $f[4] ne $main::mod{$dv}{$f[8]}{sn}){
					my $msg = "Module $f[3] SN:$f[4] in slot $f[1] got replaced with same model and SN:$main::mod{$dv}{$f[8]}{sn}";
					&misc::Prt("WMOD:$msg\n");
					&Insert('events','level,time,source,info,class,device',"\"150\",\"$main::now\",\"$dv\",\"$msg\",\"nedi\",\"$dv\"");
					&mon::SendMail("Module Replaced on $dv",$msg) if $misc::notify =~ /M/;

				}
			}else{
				my $msg = "Module $f[3] SN:$f[4] in slot $f[1] has been removed";
				&misc::Prt("WMOD:$msg\n");
				&Insert('events','level,time,source,info,class,device',"\"150\",\"$main::now\",\"$dv\",\"$msg\",\"nedi\",\"$dv\"");
				&mon::SendMail("Module Removed on $dv",$msg) if $misc::notify =~ /M/;
			}
		}

	}
	$sth->finish if $sth;
	$dbh->do("DELETE FROM  modules where device = \"$dv\"");
	my $sth = $dbh->prepare("INSERT INTO modules(device,slot,model,moddesc,serial,hw,fw,sw,modidx) VALUES ( ?,?,?,?,?,?,?,?,? )");
	foreach my $i ( sort keys %{$main::mod{$dv}} ){
		$sth->execute (	$dv,
				$main::mod{$dv}{$i}{sl},
				$main::mod{$dv}{$i}{mo},
				$main::mod{$dv}{$i}{de},
				$main::mod{$dv}{$i}{sn},
				$main::mod{$dv}{$i}{hw},
				$main::mod{$dv}{$i}{fw},
				$main::mod{$dv}{$i}{sw},
				$i );
		if($main::dev{$dv}{fs} ne $main::now and $misc::notify =~ /m/i and !exists $dbmod{$i}){
			my $msg = "New $main::mod{$dv}{$i}{de} module with SN:$main::mod{$dv}{$i}{sn} found in slot $main::mod{$dv}{$i}{sl}";
			&misc::Prt("WMOD:$msg\n");
			&Insert('events','level,time,source,info,class,device',"\"150\",\"$main::now\",\"$dv\",\"$msg\",\"nedi\",\"$dv\"");
			&mon::SendMail("Module Removed on $dv",$msg) if $misc::notify =~ /M/;
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

	my $nvlans = 0;

	my ($dv) = @_;

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
	my $nnet = 0;

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 0});
	$dbh->do("DELETE FROM  networks where device = \"$dv\"");
	my $sth = $dbh->prepare("INSERT INTO networks(	device,ifname,ifip,mask,vrfname,status ) VALUES ( ?,?,?,?,?,? )");
	foreach my $n ( sort keys %{$main::net{$dv}} ){
		$sth->execute (	$dv,
						$main::net{$dv}{$n}{ifn},
						&misc::Ip2Dec($n),
						&misc::Ip2Dec($main::net{$dv}{$n}{msk}),
						$main::net{$dv}{$n}{vrf},
						$main::net{$dv}{$n}{vrs} );
		$nnet++;
	}
	$dbh->commit;
	$sth->finish if $sth;
	$dbh->disconnect;
	&misc::Prt("WNET:$nnet networks written to $misc::dbname.networks\n");
}


=head2 FUNCTION WriteLink()

Rewrites the links of a given device.

B<Options> devicename

B<Globals> -

B<Returns> -

=cut
sub WriteLink {

	my ($dv) = @_;
	return if !$dv;

	my $nlink  = 0;
	my $nslink = 0;

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 0});
	$dbh->do("DELETE FROM links where device = \"$dv\" AND linktype != \"STAT\"");
	my $sth = $dbh->prepare("INSERT INTO links(device,ifname,neighbor,nbrifname,bandwidth,linktype,linkdesc,nbrduplex,nbrvlanid) VALUES ( ?,?,?,?,?,?,?,?,? )");

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
							$main::link{$dv}{$i}{$ne}{$ni}{vl} );
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
	if( $dbh->do("UPDATE stock SET time=\"$main::now\",comment=\"Discovered as $dv with IP $main::dev{$dv}{ip}\",state=100 where serial = \"$main::dev{$dv}{sn}\" and state !=100") + 0){
		&misc::Prt("STOK:Discovered device $main::dev{$dv}{sn} set active in $misc::dbname.stock\n");
	}
	foreach my $i ( sort keys %{$main::mod{$dv}} ){# TODO add stocktracking options for discovered devs?
		if($main::mod{$dv}{$i}{sn}){
			if( $dbh->do("UPDATE stock SET time=\"$main::now\",comment=\"Discovered in $dv slot $main::mod{$dv}{$i}{sl}\",state=100 where serial = \"$main::mod{$dv}{$i}{sn}\" and state !=100") + 0){
				&misc::Prt("STOK:Discovered module $main::mod{$dv}{$i}{sn} set active in $misc::dbname.stock\n");
			}
		}
	}
	$dbh->disconnect;
}


=head2 FUNCTION WriteNod()

Write the nodes table.

B<Options> match statement

B<Globals> main::link

B<Returns> -

=cut
sub WriteNod {

	my $nnod = 0;

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 0});
	my $sth = $dbh->prepare("SELECT * FROM stolen");
	$sth->execute();
	my %stomac = ();
	while ((my @smac) = $sth->fetchrow_array) {
		$stomac{$smac[2]} = "$smac[6]";
	}
	$dbh->do("TRUNCATE nodes");
	$sth = $dbh->prepare("INSERT INTO nodes(	name,nodip,mac,oui,firstseen,lastseen,device,ifname,vlanid,ifmetric,ifupdate,ifchanges,
							ipupdate,ipchanges,iplost,arpval,tcpports,udpports,nodtype,nodos,osupdate) VALUES ( ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,? )");

	foreach my $mcvl ( sort keys %main::nod ){
		my $mc = substr($mcvl,0,12);
		if (exists $stomac{$mc} and $main::nod{$mcvl}{ls} == $main::now and $misc::notify =~ /n/i){
			my $msg = "Node $mc reappeared as $main::nod{$mcvl}{na}/$main::nod{$mcvl}{ip} on $main::nod{$mcvl}{if}";
			&misc::Prt("WNOD:$msg\n");
			&Insert('events','level,time,source,info,class,device',"\"150\",\"$main::now\",\"$main::nod{$mcvl}{dv}\",\"$msg\",\"sec\",\"$main::nod{$mcvl}{dv}\"");
			&mon::SendMail("Node reappeared on $main::nod{$mcvl}{dv}",$msg) if $misc::notify =~ /N/;
		}

		$sth->execute (	$main::nod{$mcvl}{na},
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
				$main::nod{$mcvl}{tp},
				$main::nod{$mcvl}{up},
				$main::nod{$mcvl}{os},
				$main::nod{$mcvl}{ty},
				$main::nod{$mcvl}{ou} );
		$nnod++;
	}
	$dbh->commit;
	$sth->finish if $sth;
	$dbh->disconnect;
	&misc::Prt("WNOD:$nnod nodes written to $misc::dbname.nodes\n");
}


=head2 FUNCTION DelDev()

Delete RRDs & Configs of deleted devices.

B<Options> -

B<Globals> -

B<Returns> -

=cut
sub DelDev {

	my $nrrd = 0;
	my $ncfg = 0;

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 1});
	my $sth = $dbh->prepare("SELECT * FROM devdel");
	$sth->execute();
	my %devdel = ();
	while (my @dd = $sth->fetchrow_array) {
		$devdel{$dd[0]} = "$dd[1] - ". localtime($dd[2]);
	}
	$dbh->do("TRUNCATE devdel");
	$sth->finish if $sth;
	$dbh->disconnect;

	foreach my $dv ( keys %devdel ){
		my $nrrd = &main::rmtree( "$misc::nedipath/rrd/$dv");
		my $ncfg = &main::rmtree( "$misc::nedipath/conf/$dv");
		&misc::Prt("DELD:$nrrd RRD & $ncfg Config files (with dirs) deleted of $dv for $devdel{$dv}\n");
	}
}


=head2 FUNCTION TopRRD()

Update Top traffic, error, power & monitoring RRDs.

B<Options> -

B<Globals> -

B<Returns> -

=cut
sub TopRRD {

	my (@mok, @msl, @mal, %ifstat);
	my $err = $m50 = my $m100 = my $m150 = my $m200 = my $m250 = 0;

	&misc::Prt("\nTopRRD       ------------------------------------------------------------------\n");
	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 1});

	# Using delta octets to avoid error from missing or rebooted switches. Needs to be divided by rrdstep*1M to get MB/s
	my $sth = $dbh->prepare("SELECT sum(dinoct)/(1000000*$misc::rrdstep),sum(doutoct)/(1000000*$misc::rrdstep) FROM interfaces WHERE linktype = \"\"");
	$sth->execute();
	(my @tet) = $sth->fetchrow_array;
	$sth->finish if $sth;

	unless($tet[0]){
		$tet[0] = 0;
		$tet[1] = 0;
	}

	# Wired interface (type not 71) errors/s
	$sth = $dbh->prepare("SELECT sum(dinerr)/$misc::rrdstep,sum(douterr)/$misc::rrdstep FROM interfaces WHERE iftype != 71");
	$sth->execute();
	(my @twe) = $sth->fetchrow_array;
	$sth->finish if $sth;

	# Total Nodes lastseen
	$sth = $dbh->prepare("SELECT count(lastseen) FROM nodes where lastseen = $main::now");
	$sth->execute();
	(my @nodl) = $sth->fetchrow_array;
	$sth->finish if $sth;

	# Total Nodes firstseen
	$sth = $dbh->prepare("SELECT count(firstseen) FROM nodes where firstseen = $main::now");
	$sth->execute();
	(my @nodf) = $sth->fetchrow_array;
	$sth->finish if $sth;

	# Total power in Watts
	$sth = $dbh->prepare("SELECT sum(poe)/1000 FROM interfaces");
	$sth->execute();
	(my @pwr) = $sth->fetchrow_array;
	$sth->finish if $sth;
	unless($pwr[0]){
		$pwr[0] = 0;
	}

	# Count IF ifstat up=3, down=1 and admin down=0 ignoring transients (+128)
	$sth = $dbh->prepare("SELECT ifstat & 127,count(ifstat & 127) from interfaces group by ifstat & 127");
	$sth->execute();
	$ifstat[0] = 0;
	$ifstat[1] = 0;
	$ifstat[3] = 0;
	while (my @is = $sth->fetchrow_array) {
		$ifstat[$is[0]] = $is[1];
	}
	$sth->finish if $sth;

	# Number of monitored targets and last check
	$sth = $dbh->prepare("select count(status),lastok from monitoring WHERE test != '' AND latency < $misc::latw AND status = 0");
	$sth->execute();
	@mok = $sth->fetchrow_array;
	$sth->finish if $sth;

	if($mok[0]){
		# Number of slow targets
		$sth = $dbh->prepare("select count(status) from monitoring WHERE test != '' AND latency > $misc::latw AND status = 0");
		$sth->execute();
		@msl = $sth->fetchrow_array;
		$sth->finish if $sth;

		# Number of dead targets
		$sth = $dbh->prepare("select count(status) from monitoring WHERE test != '' AND status > 0");
		$sth->execute();
		@mal = $sth->fetchrow_array;
		$sth->finish if $sth;
		if($mok[1] < (time - $misc::chka * $misc::pause) ){					# moni.pl not running or no one alive
			$mok[0] = 0;
			$mal[0] = 0;
			my $msg = "Last successful check on ".localtime($mok[1]).", is moni running?";
			&Insert('events','level,time,source,info,class',"\"150\",\"$main::now\",\"NeDi\",\"$msg\",\"nedi\"");
			&misc::Prt("TRRD:$msg\n");
		}
	}else{
		$mok[0] = 0;
		$msl[0] = 0;
		$mal[0] = 0;
		&misc::Prt("TRRD:Nothing up or moni not running!\n");
	}

	# Number of cathegorized events during discovery cycle
	$sth = $dbh->prepare("SELECT level,count(*) FROM events WHERE time > ".(time - $misc::rrdstep)." GROUP BY level");
	$sth->execute();
	while ((my @m) = $sth->fetchrow_array) {
		if($m[0] eq 50){
			$m50 = $m[1];
		}elsif($m[0] eq 100){
			$m100 = $m[1];
		}elsif($m[0] eq 150){
			$m150 = $m[1];
		}elsif($m[0] eq 200){
			$m200 = $m[1];
		}elsif($m[0] eq 250){
			$m250 = $m[1];
		}
	}

	$sth->finish if $sth;
	$dbh->disconnect;

	&misc::Prt("TRRD:$misc::nedipath/rrd/top.rrd: Trf=$tet[0]/$tet[1] Err=$twe[0]/$twe[1] Nod=$nodl[0]/$nodf[0]\n");
	&misc::Prt("TRRD:Pwr=$pwr[0]W IF=$ifstat[3]/$ifstat[1]/$ifstat[0] Mon=$mok[0]/$msl[0]/$mal[0] MSG=$m50/$m100/$m150/$m200/$m250\n");
	if ($main::opt{t} or $main::opt{a}){
		&misc::Prt("TRRD:Not writing when testing or adding single device\n");
	}else{
		unless(-e "$misc::nedipath/rrd/top.rrd"){
			my $ds = 2 * $misc::rrdstep;
			RRDs::create(	"$misc::nedipath/rrd/top.rrd",
					"-s","$misc::rrdstep",
					"DS:tinoct:GAUGE:$ds:0:U",
					"DS:totoct:GAUGE:$ds:0:U",
					"DS:tinerr:GAUGE:$ds:0:U",
					"DS:toterr:GAUGE:$ds:0:U",
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
					"RRA:AVERAGE:0.5:1:2000",
					"RRA:AVERAGE:0.5:10:2000");
			$err = RRDs::error;
		}
		if($err){
			&misc::Prt("TRRD:File error!\n");
		}else{
			RRDs::update "$misc::nedipath/rrd/top.rrd","N:$tet[0]:$tet[1]:$twe[0]:$twe[1]:$nodl[0]:$nodf[0]:$pwr[0]:$ifstat[3]:$ifstat[1]:$ifstat[0]:$mok[0]:$msl[0]:$mal[0]:$m50:$m100:$m150:$m200:$m250";
			$err = RRDs::error;
			if($err){
				&misc::Prt("TRRD:Update error!\n");
			}else{
				&misc::Prt("TRRD:Update OK\n");
			}
		}
	}
}


=head2 FUNCTION WlanUp()

Update WLAN table.

B<Options> -

B<Globals> -

B<Returns> -

=cut
sub WlanUp {

	use File::Find;

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 0});
	my $sth = $dbh->prepare("SELECT * FROM wlan");
	$sth->execute();

	while ((my @wrow) = $sth->fetchrow_array) {
			my $mc = $wrow[0];
			$ap{$mc} = $main::now;
	}
	my $wprev = keys %ap;
	print "WLAN:$wprev old Wlan entries read.\n";

	find(\&misc::GetAp, $main::opt{w});								# Calls GetAp() in libmisc.pl

	$dbh->do("TRUNCATE wlan");
	$sth = $dbh->prepare("INSERT INTO wlan(mac,time) VALUES ( ?,? )");
	for my $mc (sort keys %ap ){ $sth->execute ( $mc,$ap{$mc} ) }
	$dbh->commit;
	$sth->finish if $sth;
	$dbh->disconnect;

	my $wnew = scalar keys %ap;
	print "WLAN:$wnew new entries written.\n";
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
		$sth = $dbh->prepare("SELECT monitoring.name,devip,class,alert,eventfwd,eventdel FROM monitoring LEFT OUTER JOIN devices ON (monitoring.name = devices.device ) WHERE ip = $_[0]");
	}elsif($_[0] eq "dev"){
		$sth = $dbh->prepare("SELECT monitoring.name,devip,class,snmpversion & 3,readcomm,test,status,lost,ok,latmax,latavg,uptime,alert,eventfwd,eventdel,depend,monitoring.device FROM monitoring LEFT OUTER JOIN devices ON (monitoring.name = devices.device ) WHERE class = \"dev\"");
	}elsif($_[0] eq "node"){
		$sth = $dbh->prepare("SELECT monitoring.name,monip,class,class,class,test,status,lost,ok,latmax,latavg,uptime,alert,eventfwd,eventdel,depend,device FROM monitoring WHERE class = \"node\"");
	}else{
		die "no ReadMon class!";
	}

	$sth->execute();
	while ((my @f) = $sth->fetchrow_array) {
		my $na = $f[0];
		$main::mon{$na}{ip} = &misc::Dec2Ip($f[1]);
		$main::mon{$na}{cl} = $f[2];# Consider devp,devh for printers,hypervisors OR syslog,traps to distinguish event icon?
		$main::mon{$na}{rv} = $f[3];
		$main::mon{$na}{rc} = $f[4];
		$main::mon{$na}{te} = $f[5];
		$main::mon{$na}{st} = $f[6];
		$main::mon{$na}{lo} = $f[7];
		$main::mon{$na}{ok} = $f[8];
		$main::mon{$na}{lm} = $f[9];
		$main::mon{$na}{la} = $f[10];
		$main::mon{$na}{up} = $f[11];
		$main::mon{$na}{al} = $f[12];
		$main::mon{$na}{ef} = $f[13];
		$main::mon{$na}{ed} = $f[14];
		$main::mon{$na}{dy} = $f[15];
		$main::mon{$na}{dv} = $f[16];
		$main::mon{$na}{dc} = 0;								# Dependendant count
		$main::mon{$na}{ds} = 'up';								# Dependency status
		$nmon++;
	}
	$sth->finish if $sth;
	$dbh->disconnect;
	printf ("MON: %4.4s %4.4s entries read from %s.monitoring\n",$nmon,$_[0],$misc::dbname) if $main::opt{v};
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
	my $sth = $dbh->prepare("SELECT user,email,phone FROM users $where");
	$sth->execute();
	while ((my @f) = $sth->fetchrow_array) {
		$main::usr{$f[0]}{ml} = $f[1];
		$main::usr{$f[0]}{ph} = $f[2];
		$main::usr{$f[0]}{ph} =~ s/[^0-9]//g;							# Strip anything that isn't a number
		$nusr++;
	}
	$sth->finish if $sth;
	$dbh->disconnect;
	&misc::Prt("RUSR:$nusr entries ($_[0]) read from $misc::dbname.users\n");
}


=head2 FUNCTION Insert()

Insert DB Record

B<Options> table, string of columns, string of values

B<Globals> -

B<Returns> -

=cut
sub Insert {

	my $nag_event_service = 'Events';

	if ($_[0] eq 'events') {
		if ($misc::nagpipe) {
			if (-p $misc::nagpipe) {							# Nagios Handler by S.Neuser
				my ($level_str,$time,$source,$msg) = split /,/, $_[2];
				$level_str =~ s/\"//g;
				my $level = int $level_str;
				my $status = 3;
				if (! defined $level) { $status = 3; }
				elsif ($level < 0) { $status = 3; }					# UNKNOWN
				elsif ($level < 100) { $status = 0; }					# OK
				elsif ($level < 200) { $status = 1; }					# WARN
				else { $status = 2; }							# CRIT
				my $lsource = lc ($source);
				$lsource =~ s/\"//g;
				$time =~ s/\"//g;
				$msg =~ s/\"//g;
				$msg =~ s/\n/;/g;
				open (NPIPE, ">>$misc::nagpipe");
				print NPIPE "[$time] PROCESS_SERVICE_CHECK_RESULT;$lsource;$nag_event_service;$status;NeDi:$msg\n";
				close NPIPE;
			}
		}
	}
	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 1});
	my $r = $dbh->do("INSERT INTO $_[0] ($_[1]) VALUES ($_[2])") || die "ERR :INSERT INTO $_[0] ($_[1]) VALUES ($_[2])\n";
	$dbh->disconnect;

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
	my $r = $dbh->do("DELETE FROM  $_[0] WHERE $_[1]") || die "DB-ERR: DELETE FROM  $_[0] WHERE $_[1]\n";

	print "ERR :$dbh->errstr" if (!$r);								# Something went wrong
	$r = 0 if ($r eq '0E0');									# 0E0 actually means 0

	$dbh->disconnect;

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
	my $r = $dbh->do("UPDATE $table SET $set WHERE $match") || die "DB-ERR: UPDATE $table SET $set WHERE $match\n";
	$dbh->disconnect;
	&misc::Prt("UPDT:$table SET $set WHERE $match\n");

	return $r;
}


=head2 FUNCTION Select()

Select 1 row of values from a table.

B<Options> table, columns, match statement

B<Globals> -

B<Returns> array (if multiple columns) or value

=cut
sub Select {

	my ($table, $col, $match) = @_;

	my $dbh = DBI->connect("DBI:mysql:$misc::dbname:$misc::dbhost", "$misc::dbuser", "$misc::dbpass", { RaiseError => 1, AutoCommit => 1});
	my $sth = $dbh->prepare("SELECT $col FROM $table WHERE $match") || die "DB-ERR: SELECT $col FROM $table WHERE $match\n";
	$sth->execute()|| die "DB-ERR: SELECT $col FROM $table WHERE $match\n";
	my @val = $sth->fetchrow_array;
	$sth->finish if $sth;
	$dbh->disconnect;
	if($col =~ /,/ ){
		return @val;
	}else{
		return $val[0];
	}
}

1;
