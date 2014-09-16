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

use vars qw($dbh $ac);

=head2 FUNCTION Connect()

Connect to Backend according to nedi.conf settings. Dies upon failure...

B<Options> dbuser, dbpass

B<Globals> -

B<Returns> -

=cut
sub Connect{

	my ($dbname,$dbhost,$dbuser,$dbpass,$setac) = @_;
	$ac = (!defined $setac)?0:$setac;								# Set $db::ac so functions like Insert() are autocommit aware
	&misc::Prt("DBG :Connecting to '$dbname\@$dbhost' as '$dbuser' with".(($ac)?'':'out')." autocommit\n") if $main::opt{'d'} =~ /d/;
	$dbh = DBI->connect("DBI:$misc::backend:dbname=$dbname;host=$dbhost", $dbuser, $dbpass, { RaiseError => 1, AutoCommit => $ac} ) or die $DBI::errstr;
	&misc::Prt("DBG :Connection ".((defined $dbh)?'OK':'FAIL')."\n") if $main::opt{'d'} =~ /d/;
}

=head2 FUNCTION Commit()

Commit commands

B<Options> dbhandle

B<Globals> -

B<Returns> -

=cut
sub Commit{

	&misc::Prt("DBG :Committing Changes\n") if $main::opt{'d'} =~ /d/;
	$dbh->commit;
}

=head2 FUNCTION Disconnect()

Disconnect from backend

B<Options> dbhandle

B<Globals> -

B<Returns> -

=cut
sub Disconnect{

	$dbh->disconnect;
}

=head2 FUNCTION InitDB()

Connect as admin, drop existing DB and create nedi db and add important
values (like the admin user).

B<Options> $adminuser,$adminpass,$nedihost

B<Globals> -

B<Returns> -

=cut
sub InitDB{

	my ($adminuser, $adminpass, $nedihost) = @_;

	my $dbcon = 'mysql';
	my $serid = 'INT UNSIGNED NOT NULL AUTO_INCREMENT';
	my $tinun = 'TINYINT UNSIGNED';
	my $smaun = 'SMALLINT UNSIGNED';
	my $intun = 'INT UNSIGNED';
	my $bigun = 'BIGINT UNSIGNED';
	my $char  = 'CHAR';
	my $vchar = 'VARCHAR';
	my $ipv6  = "VARBINARY(16) DEFAULT ''";
	my $text  = 'MEDIUMTEXT';
	if($misc::backend eq 'Pg'){
		$dbcon = 'postgres';
		$serid = 'serial';
		$tinun = 'smallint';
		$smaun = 'integer';
		$intun = 'bigint';
		$bigun = 'bigint';
		$char  = 'character varying';
		$vchar = 'character varying';
		$ipv6  = 'inet';
		$text  = 'text';
	}

	unless($adminuser eq 'nodrop'){									# Skip creating DB doesn't require admin rights
		&db::Connect( $dbcon,$misc::dbhost,$adminuser,$adminpass,1);
		my $sth = $dbh->prepare("SELECT VERSION()");
		$sth->execute();
		my @dbver = $sth->fetchrow;
		$sth->finish if $sth;
		print "DB Version	: $dbver[0]\n";
		print "----------------------------------------------------------------------\n";
		$dbh->do("DROP DATABASE IF EXISTS $misc::dbname");
		print "Old DB '$misc::dbname' dropped!\n";

		print "Creating '$misc::dbname' for $misc::dbuser\@$nedihost:\n";
		if($misc::backend eq 'Pg'){
			#$dbh->do("DROP FUNCTION public.inet_ntoa(bigint)");TODO remove, if not needed
			unless( &Select('pg_user','','usename',"usename = '$misc::dbuser'") ){
				$dbh->do("CREATE ROLE $misc::dbuser WITH login PASSWORD '$misc::dbpass';");
			}
			$dbh->do("CREATE DATABASE $misc::dbname OWNER=$misc::dbuser");
			$dbh->do("GRANT ALL PRIVILEGES ON DATABASE $misc::dbname TO $misc::dbuser");
		}else{
			$dbh->do("CREATE DATABASE $misc::dbname");
			$dbh->do("GRANT ALL PRIVILEGES ON $misc::dbname.* TO \'$misc::dbuser\'\@\'$nedihost\' IDENTIFIED BY \'$misc::dbpass\'");
			if($dbver[0] =~ /5\.0/) {							#fix for mysql 5.0 with old client libs
				$dbh->do("SET PASSWORD FOR \'$misc::dbuser\'\@\'$nedihost\' = OLD_PASSWORD(\'$misc::dbpass\')");
			}
		}
		&db::Disconnect();
	}

#---Connect as nedi db user and create tables.
	&db::Connect($misc::dbname,$misc::dbhost,$misc::dbuser,$misc::dbpass);

	if($adminuser eq 'nodrop'){									# Clean existing tables, if DB wasn't dropped before
		$dbh->do("DROP TABLE IF EXISTS devices");
		$dbh->do("DROP TABLE IF EXISTS modules");
		$dbh->do("DROP TABLE IF EXISTS interfaces");
		$dbh->do("DROP TABLE IF EXISTS networks");
		$dbh->do("DROP TABLE IF EXISTS configs");
		$dbh->do("DROP TABLE IF EXISTS inventory");
		$dbh->do("DROP TABLE IF EXISTS vlans");
		$dbh->do("DROP TABLE IF EXISTS links");
		$dbh->do("DROP TABLE IF EXISTS locations");
		$dbh->do("DROP TABLE IF EXISTS events");
		$dbh->do("DROP TABLE IF EXISTS monitoring");
		$dbh->do("DROP TABLE IF EXISTS incidents");
		$dbh->do("DROP TABLE IF EXISTS nodes");
		$dbh->do("DROP TABLE IF EXISTS nodarp");
		$dbh->do("DROP TABLE IF EXISTS nodnd");
		$dbh->do("DROP TABLE IF EXISTS nodetrack");
		$dbh->do("DROP TABLE IF EXISTS iftrack");
		$dbh->do("DROP TABLE IF EXISTS iptrack");
		$dbh->do("DROP TABLE IF EXISTS dns");
		$dbh->do("DROP TABLE IF EXISTS dns6");
		$dbh->do("DROP TABLE IF EXISTS stolen");
		$dbh->do("DROP TABLE IF EXISTS users");
		$dbh->do("DROP TABLE IF EXISTS system");
		$dbh->do("DROP TABLE IF EXISTS chat");
		$dbh->do("DROP TABLE IF EXISTS wlan");
		$dbh->commit;
	}

	print "devices\n";
	my $index = ($misc::backend eq 'Pg')?'':',INDEX (device),PRIMARY KEY (device)';
	$dbh->do("CREATE TABLE devices(
		device $vchar(64) NOT NULL UNIQUE,
		devip $intun DEFAULT 0,
		serial $vchar(32) DEFAULT '',
		type $vchar(32)  DEFAULT '',
		firstdis $intun DEFAULT 0,
		lastdis $intun DEFAULT 0,
		services $tinun DEFAULT 0,
		description $vchar(255) DEFAULT '',
		devos $vchar(16) DEFAULT '',
		bootimage $vchar(64) DEFAULT '',
		location $vchar(255) DEFAULT '',
		contact $vchar(255) DEFAULT '',
		devgroup $vchar(32) DEFAULT '',
		devmode $tinun DEFAULT 0,
		snmpversion $tinun DEFAULT 0,
		readcomm $vchar(32) DEFAULT '',
		cliport $smaun DEFAULT 0,
		login $vchar(32) DEFAULT '',
		icon $vchar(16) DEFAULT '',
		origip $intun DEFAULT 0,
		cpu $tinun DEFAULT 0,
		memcpu $bigun DEFAULT 0,
		temp $tinun DEFAULT 0,
		cusvalue $bigun DEFAULT 0,
		cuslabel $vchar(32) DEFAULT '',
		sysobjid $vchar(255) DEFAULT '',
		writecomm $vchar(32) DEFAULT '',
		devopts $char(32) DEFAULT '',
		size $tinun DEFAULT 0,
		stack $tinun DEFAULT 1,
		maxpoe $smaun DEFAULT 0,
		totpoe $smaun DEFAULT 0,
		cfgchange $intun DEFAULT 0,
		cfgstatus $char(2) DEFAULT '--'
		$index)" );
 	$dbh->commit;

	print "modules\n";
	$index = ($misc::backend eq 'Pg')?'':', INDEX(device), INDEX(slot(8))';
	$dbh->do("CREATE TABLE modules(
		device $vchar(64) NOT NULL,
		slot $vchar(64) DEFAULT '',
		model $vchar(32) DEFAULT '',
		moddesc $vchar(255) DEFAULT '',
		serial $vchar(32) DEFAULT '',
		hw $vchar(128) DEFAULT '',
		fw $vchar(128) DEFAULT '',
		sw $vchar(128) DEFAULT '',
		modidx $intun DEFAULT 0,
		modclass $tinun DEFAULT 1,
		status $smaun DEFAULT 0,
		modloc $vchar(255) DEFAULT ''
		$index)" );
 	$dbh->commit;

	print "interfaces\n";
	$index = ($misc::backend eq 'Pg')?'':', INDEX(device), INDEX(ifname), INDEX(ifidx)';
	$dbh->do("CREATE TABLE interfaces(
		device $vchar(64) NOT NULL,
		ifname $vchar(32) NOT NULL,
		ifidx $bigun NOT NULL,
		linktype $char(4) DEFAULT '',
		iftype $smaun DEFAULT 0,
		ifmac $char(12) DEFAULT '',
		ifdesc $vchar(255) DEFAULT '',
		alias $vchar(64) DEFAULT '',
		ifstat $tinun DEFAULT 0,
		speed $bigun DEFAULT 0,
		duplex $char(2) DEFAULT '',
		pvid $smaun DEFAULT 0,
		inoct $bigun DEFAULT 0,
		inerr $intun DEFAULT 0,
		outoct $bigun DEFAULT 0,
		outerr $intun DEFAULT 0,
		dinoct $bigun DEFAULT 0,
		dinerr $intun DEFAULT 0,
		doutoct $bigun DEFAULT 0,
		douterr $intun DEFAULT 0,
		indis $intun DEFAULT 0,
		outdis $intun DEFAULT 0,
		dindis $intun DEFAULT 0,
		doutdis $intun DEFAULT 0,
		inbrc $bigun DEFAULT 0,
		dinbrc $intun DEFAULT 0,
		lastchg $intun DEFAULT 0,
		poe $smaun DEFAULT 0,
		comment $vchar(255) DEFAULT '',
		trafalert $tinun DEFAULT 0,
		brcalert $smaun DEFAULT 0,
		macflood $smaun DEFAULT 0
		$index)" );
 	$dbh->commit;

	print "networks\n";
	$index = ($misc::backend eq 'Pg')?'':', INDEX(device), INDEX(ifname), INDEX(ifip)';
	$dbh->do("CREATE TABLE networks(
		device $vchar(64) NOT NULL,
		ifname $vchar(32) DEFAULT '',
		ifip $intun DEFAULT 0,
		ifip6 $ipv6,
		prefix $tinun DEFAULT 0,
		vrfname $vchar(32) DEFAULT '',
		vrfrd $vchar(16) DEFAULT '',
		status $smaun DEFAULT 0
		$index)" );
 	$dbh->commit;

	print "configs\n";
	$index = ($misc::backend eq 'Pg')?'':', INDEX(device), PRIMARY KEY(device)';
	$dbh->do("CREATE TABLE configs(
		device $vchar(64) NOT NULL UNIQUE,
		config $text,
		changes $text,
		time $intun DEFAULT 0
		$index)" );
 	$dbh->commit;

	print "inventory\n";
	$index = ($misc::backend eq 'Pg')?'':', INDEX(serial)';
	$dbh->do("CREATE TABLE inventory(
		state $tinun DEFAULT 0,
		serial $vchar(32) NOT NULL UNIQUE,
		assetclass $tinun DEFAULT 1,
		assettype $vchar(32) DEFAULT 0,
		assetnumber $vchar(32) DEFAULT '',
		assetlocation $vchar(255) DEFAULT '',
		assetcontact $vchar(255) DEFAULT '',
		assetupdate $intun DEFAULT 0,
		pursource $vchar(32) default '-',
		purcost $intun DEFAULT 0,
		purnumber $vchar(32) DEFAULT '',
		purtime $intun DEFAULT 0,
		maintpartner $vchar(32) DEFAULT '',
		maintsla $vchar(32) DEFAULT '',
		maintdesc $vchar(32) DEFAULT '',
		maintcost $intun DEFAULT 0,
		maintstatus $tinun DEFAULT 0,
		startmaint $intun DEFAULT 0,
		endmaint $intun DEFAULT 0,
		endwarranty $intun DEFAULT 0,
		endsupport $intun DEFAULT 0,
		endlife $intun DEFAULT 0,
		comment $vchar(255) DEFAULT '',
		usrname $vchar(32) DEFAULT '-'
		$index)" );
 	$dbh->commit;

	print "vlans\n";
	$index = ($misc::backend eq 'Pg')?'':', INDEX(vlanid), INDEX(device)';
	$dbh->do("CREATE TABLE vlans(
		device $vchar(64) NOT NULL,
		vlanid $smaun DEFAULT 0,
		vlanname $vchar(32) DEFAULT ''
		$index)" );
 	$dbh->commit;

	print "links\n";
	$index = ($misc::backend eq 'Pg')?'':', INDEX(id), INDEX(device), INDEX(ifname), INDEX(neighbor), INDEX(nbrifname), PRIMARY KEY(id)';
	$dbh->do("CREATE TABLE links(
		id $serid,
		device $vchar(64) NOT NULL,
		ifname $vchar(32) DEFAULT '',
		neighbor $vchar(64) NOT NULL,
		nbrifname $vchar(32) DEFAULT '',
		bandwidth $bigun DEFAULT 0,
		linktype $char(4) DEFAULT '',
		linkdesc $vchar(255) DEFAULT '',
		nbrduplex $char(2) DEFAULT '',
		nbrvlanid $smaun DEFAULT 0,
		time $intun DEFAULT 0
		$index)" );
 	$dbh->commit;

	print "locations\n";
	$index = ($misc::backend eq 'Pg')?'':', INDEX(region), PRIMARY KEY(id)';
	$dbh->do("CREATE TABLE locations(
		id $serid,
		region $vchar(32) NOT NULL,
		city $vchar(32) DEFAULT '',
		building $vchar(32) DEFAULT '',
		x $smaun DEFAULT 0,
		y $smaun DEFAULT 0,
		ns INT DEFAULT 0,
		ew INT DEFAULT 0,
		locdesc $vchar(255) DEFAULT ''
		$index)" );
 	$dbh->commit;

	print "events\n";
	$index = ($misc::backend eq 'Pg')?'':', INDEX(id), INDEX(source), INDEX(level), INDEX(time), INDEX(class), INDEX(device), PRIMARY KEY(id)';
	$dbh->do("CREATE TABLE events(
		id $serid,
		level $tinun DEFAULT 0,
		time $intun DEFAULT 0,
		source $vchar(64) DEFAULT '',
		info $vchar(255) DEFAULT '',
		class $char(4) DEFAULT 'dev',
		device $vchar(64) DEFAULT ''
		$index)" );
 	$dbh->commit;

	print "monitoring\n";
	$index = ($misc::backend eq 'Pg')?'':', INDEX(name), INDEX(device)';
	$dbh->do("CREATE TABLE monitoring(
		name $vchar(64) NOT NULL UNIQUE,
		monip $intun,
		class $char(4) DEFAULT 'dev',
		test $char(6) DEFAULT '',
		testopt $vchar(64) DEFAULT '',
		testres $vchar(64) DEFAULT '',
		lastok $intun DEFAULT 0,
		status $intun DEFAULT 0,
		lost $intun DEFAULT 0,
		ok $intun DEFAULT 0,
		latency $smaun DEFAULT 0,
		latmax $smaun DEFAULT 0,
		latavg $smaun DEFAULT 0,
		uptime $intun DEFAULT 0,
		alert $tinun DEFAULT 0,
		eventfwd $vchar(255) DEFAULT '',
		eventlvl $tinun DEFAULT 0,
		eventdel $vchar(255) DEFAULT '',
		depend1 $vchar(64) DEFAULT '',
		depend2 $vchar(64) DEFAULT '',
		device $vchar(64) NOT NULL,
		notify $char(32) DEFAULT '',
		noreply $tinun DEFAULT 2,
		latwarn $smaun DEFAULT 100,
		cpualert $tinun DEFAULT 75,
		memalert $intun DEFAULT 1024,
		tempalert $tinun DEFAULT 60,
		poewarn $tinun DEFAULT 75,
		arppoison $smaun DEFAULT 1,
		supplyalert $tinun DEFAULT 5
		$index)" );
 	$dbh->commit;

	print "incidents\n";
	$index = ($misc::backend eq 'Pg')?'':', INDEX(id), INDEX(name), INDEX(device), PRIMARY KEY(id)';
	$dbh->do("CREATE TABLE incidents(
		id $serid,
		level $tinun DEFAULT 0,
		name $vchar(64) DEFAULT '',
		deps $intun DEFAULT 0,
		startinc $intun DEFAULT 0,
		endinc $intun DEFAULT 0,
		usrname $vchar(32) DEFAULT '',
		time $intun DEFAULT 0,
		grp $tinun DEFAULT 0,
		comment $vchar(255) DEFAULT '',
		device $vchar(64) DEFAULT ''
		$index)" );
 	$dbh->commit;

	print "nodes\n";
	$index = ($misc::backend eq 'Pg')?'':', INDEX(mac), INDEX(device), INDEX(ifname), INDEX(vlanid), INDEX(noduser)';
	$dbh->do("CREATE TABLE nodes(
		mac $vchar(16) NOT NULL,
		oui $vchar(32) DEFAULT '',
		firstseen $intun DEFAULT 0,
		lastseen $intun DEFAULT 0,
		device $vchar(64) DEFAULT '',
		ifname $vchar(32) DEFAULT '',
		vlanid $smaun DEFAULT 0,
		metric $vchar(10) DEFAULT '',
		ifupdate $intun DEFAULT 0,
		ifchanges $intun DEFAULT 0,
		noduser $vchar(32) DEFAULT '',
		nodesc $vchar(255) DEFAULT ''
		$index)" );
 	$dbh->commit;

	print "nodarp\n";
	$index = ($misc::backend eq 'Pg')?'':', INDEX(mac), INDEX(nodip), INDEX(arpdevice), INDEX(arpifname)';
	$dbh->do("CREATE TABLE nodarp(
		mac $vchar(16) DEFAULT '',
		nodip $intun DEFAULT 0,
		ipchanges $intun DEFAULT 0,
		ipupdate $intun DEFAULT 0,
		tcpports $vchar(64) DEFAULT '',
		udpports $vchar(64) DEFAULT '',
		srvtype $vchar(64) DEFAULT '',
		srvos $vchar(64) DEFAULT '',
		srvupdate $intun DEFAULT 0,
		arpdevice $vchar(64) DEFAULT '',
		arpifname $vchar(32) DEFAULT ''
		$index)" );
 	$dbh->commit;

	print "nodnd\n";
	$index = ($misc::backend eq 'Pg')?'':', INDEX(mac), INDEX(nodip6), INDEX(nddevice), INDEX(ndifname)';
	$dbh->do("CREATE TABLE nodnd(
		mac $vchar(16) DEFAULT '',
		nodip6 $ipv6,
		ip6changes $intun DEFAULT 0,
		ip6update $intun DEFAULT 0,
		tcp6ports $vchar(64) DEFAULT '',
		udp6ports $vchar(64) DEFAULT '',
		srv6type $vchar(64) DEFAULT '',
		srv6os $vchar(64) DEFAULT '',
		srv6update $intun DEFAULT 0,
		nddevice $vchar(64) DEFAULT '',
		ndifname $vchar(32) DEFAULT ''
		$index)" );
 	$dbh->commit;

	print "nodetrack\n";
	$index = ($misc::backend eq 'Pg')?'':', INDEX(device), INDEX(ifname)';
	$dbh->do("CREATE TABLE nodetrack(
		device $vchar(64) DEFAULT '',
		ifname $vchar(32) DEFAULT '',
		value $vchar(64) DEFAULT '',
		source $char(8) DEFAULT '',
		usrname $vchar(32) DEFAULT '',
		time $intun DEFAULT 0
		$index)" );
 	$dbh->commit;

	print "iftrack\n";
	$index = ($misc::backend eq 'Pg')?'':', INDEX(mac), INDEX(device), INDEX(vlanid)';
	$dbh->do("CREATE TABLE iftrack(
		mac $vchar(16) NOT NULL,
		ifupdate $intun DEFAULT 0,
		device $vchar(64) DEFAULT '',
		ifname $vchar(32) DEFAULT '',
		vlanid $smaun DEFAULT 0
		$index)" );
 	$dbh->commit;

	print "iptrack\n";
	$index = ($misc::backend eq 'Pg')?'':', INDEX(mac), INDEX(vlanid), INDEX(device)';
	$dbh->do("CREATE TABLE iptrack(
		mac $vchar(16) NOT NULL,
		ipupdate $intun DEFAULT 0,
		aname $vchar(64) DEFAULT '',
		nodip $intun DEFAULT 0,
		vlanid $smaun DEFAULT 0,
		device $vchar(64) NOT NULL DEFAULT ''
		$index)" );
 	$dbh->commit;

	print "dns\n";
	$index = ($misc::backend eq 'Pg')?'':', INDEX(nodip), INDEX(aname)';
	$dbh->do("CREATE TABLE dns(
		nodip $intun DEFAULT 0,
		aname $vchar(64) DEFAULT '',
		dnsupdate $intun DEFAULT 0
		$index)" );
 	$dbh->commit;

	print "dns6\n";
	$index = ($misc::backend eq 'Pg')?'':', INDEX(nodip6), INDEX(aaaaname)';
	$dbh->do("CREATE TABLE dns6(
		nodip6 $ipv6,
		aaaaname $vchar(64) DEFAULT '',
		dns6update $intun DEFAULT 0
		$index)" );
 	$dbh->commit;

	print "stolen\n";
	$index = ($misc::backend eq 'Pg')?'':', INDEX(mac), INDEX(device), PRIMARY KEY(mac)';
	$dbh->do("CREATE TABLE stolen(
		name $vchar(64) DEFAULT '',
		stlip $intun DEFAULT 0,
		mac $char(12) NOT NULL UNIQUE,
		device $vchar(64) DEFAULT '',
		ifname $vchar(32) DEFAULT '',
		usrname $vchar(32) DEFAULT '',
		time $intun DEFAULT 0,
		comment $vchar(255) DEFAULT ''
		$index)" );
 	$dbh->commit;

	print "users\n";
	$index = ($misc::backend eq 'Pg')?'':', PRIMARY KEY(usrname)';
	$dbh->do("CREATE TABLE users(
		usrname $vchar(32) NOT NULL UNIQUE,
		password $vchar(64) NOT NULL DEFAULT '',
		groups $smaun NOT NULL DEFAULT '0',
		email $vchar(64) DEFAULT '',
		phone $vchar(32) DEFAULT '',
		time $intun DEFAULT 0,
		lastlogin $intun DEFAULT 0,
		comment $vchar(255) DEFAULT '',
		language $vchar(16) NOT NULL DEFAULT 'english',
		theme $vchar(16) NOT NULL DEFAULT 'default',
		volume $tinun NOT NULL DEFAULT '60',
		columns $tinun NOT NULL DEFAULT '6',
		msglimit $tinun NOT NULL DEFAULT '5',
		miscopts $smaun NOT NULL DEFAULT '35',
		dateformat $vchar(16) NOT NULL DEFAULT 'j.M y G:i470',
		viewdev $vchar(255) DEFAULT ''
		$index)" );
	$sth = $dbh->prepare("INSERT INTO users (usrname,password,groups,time,comment,volume,columns,msglimit,miscopts) VALUES ( ?,?,?,?,?,?,?,?,? )");
	$sth->execute ( 'admin','3cac26b5bd6addd1ba4f9c96a58ff8c2c2c8ac15018f61240f150a4a968b8562','255',$main::now,'default admin','75','8','10','35' );
 	$dbh->commit;

	print "system\n";
	$index = ($misc::backend eq 'Pg')?'':', INDEX(name)';
	$dbh->do("CREATE TABLE system (
		name $vchar(32) NOT NULL UNIQUE,
		value $vchar(32) DEFAULT ''
		$index)" );
	$sth = $dbh->prepare("INSERT INTO system (name,value) VALUES ( ?,? )");
	$sth->execute ( 'nodlock','0' );
	$sth->execute ( 'threads','0' );
	$sth->execute ( 'first','0' );
	$sth->execute ( 'version','1.1' );
 	$dbh->commit;

	print "chat\n";
	$index = ($misc::backend eq 'Pg')?'':', INDEX(time), INDEX (usrname)';
	$dbh->do("CREATE TABLE chat (
		time $intun,
		usrname $vchar(32) DEFAULT '',
		message $vchar(255) DEFAULT ''
		$index)" );
 	$dbh->commit;

	print "wlan";
	$index = ($misc::backend eq 'Pg')?'':', INDEX(mac)';
	$dbh->do("CREATE TABLE wlan (
		mac $char(8) NOT NULL,
		time $intun DEFAULT 0
		$index)" );
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

	if($misc::backend eq 'Pg'){
		print "\nFUNCTIONS";
		my $inet_ntoa = <<END;

create or replace function inet_ntoa(bigint) returns inet as '
	select ''0.0.0.0''::inet+\$1;'
	language sql immutable;
END

		$dbh->do($inet_ntoa);
#		$dbh->do("ALTER FUNCTION inet_ntoa(bigint) OWNER TO nedi;");TODO remove, if not needed

		$dbh->do('ALTER TABLE ONLY events    ADD CONSTRAINT events_pkey    PRIMARY KEY (id);');
		$dbh->do('ALTER TABLE ONLY incidents ADD CONSTRAINT incidents_pkey PRIMARY KEY (id);');
		$dbh->do('ALTER TABLE ONLY links     ADD CONSTRAINT links_pkey PRIMARY KEY (id);');
		$dbh->do('ALTER TABLE ONLY locations ADD CONSTRAINT locations_pkey PRIMARY KEY (id);');

		$dbh->do('CREATE INDEX chat_time         ON chat       USING btree (time);');
		$dbh->do('CREATE INDEX chat_usrname      ON chat       USING btree (usrname);');
		$dbh->do('CREATE INDEX configs_device    ON configs    USING btree (device);');
		$dbh->do('CREATE INDEX devices_device    ON devices    USING btree (device);');
		$dbh->do('CREATE INDEX dns_aname         ON dns        USING btree (aname);');
		$dbh->do('CREATE INDEX dns_dnsupdate     ON dns        USING btree (dnsupdate);');
		$dbh->do('CREATE INDEX dns_nodip         ON dns        USING btree (nodip);');
		$dbh->do('CREATE INDEX dns6_aaaaname     ON dns6       USING btree (aaaaname);');
		$dbh->do('CREATE INDEX dns6_dns6update   ON dns6       USING btree (dns6update);');
		$dbh->do('CREATE INDEX dns6_nodip6       ON dns6       USING btree (nodip6);');
		$dbh->do('CREATE INDEX events_class      ON events     USING btree (class);');
		$dbh->do('CREATE INDEX events_device     ON events     USING btree (device);');
		$dbh->do('CREATE INDEX events_level      ON events     USING btree (level);');
		$dbh->do('CREATE INDEX events_source     ON events     USING btree (source);');
		$dbh->do('CREATE INDEX events_time       ON events     USING btree (time);');
		$dbh->do('CREATE INDEX iftrack_device    ON iftrack    USING btree (device);');
		$dbh->do('CREATE INDEX iftrack_mac       ON iftrack    USING btree (mac);');
		$dbh->do('CREATE INDEX iftrack_vlanid    ON iftrack    USING btree (vlanid);');
		$dbh->do('CREATE INDEX incidents_device  ON incidents  USING btree (device);');
		$dbh->do('CREATE INDEX incidents_name    ON incidents  USING btree (name);');
		$dbh->do('CREATE INDEX inventory_serial  ON inventory  USING btree (serial);');
		$dbh->do('CREATE INDEX interfaces_device ON interfaces USING btree (device);');
		$dbh->do('CREATE INDEX interfaces_ifidx  ON interfaces USING btree (ifidx);');
		$dbh->do('CREATE INDEX interfaces_ifname ON interfaces USING btree (ifname);');
		$dbh->do('CREATE INDEX iptrack_device    ON iptrack    USING btree (device);');
		$dbh->do('CREATE INDEX iptrack_mac       ON iptrack    USING btree (mac);');
		$dbh->do('CREATE INDEX iptrack_vlanid    ON iptrack    USING btree (vlanid);');
		$dbh->do('CREATE INDEX links_device      ON links      USING btree (device);');
		$dbh->do('CREATE INDEX links_ifname      ON links      USING btree (ifname);');
		$dbh->do('CREATE INDEX links_nbrifname   ON links      USING btree (nbrifname);');
		$dbh->do('CREATE INDEX links_neighbor    ON links      USING btree (neighbor);');
		$dbh->do('CREATE INDEX locations_region  ON locations  USING btree (region);');
		$dbh->do('CREATE INDEX modules_device    ON modules    USING btree (device);');
		$dbh->do('CREATE INDEX modules_slot      ON modules    USING btree (slot);');
		$dbh->do('CREATE INDEX monitoring_name   ON monitoring USING btree (name);');
		$dbh->do('CREATE INDEX monitoring_device ON monitoring USING btree (device);');
		$dbh->do('CREATE INDEX networks_device   ON networks   USING btree (device);');
		$dbh->do('CREATE INDEX networks_ifip     ON networks   USING btree (ifip);');
		$dbh->do('CREATE INDEX networks_ifname   ON networks   USING btree (ifname);');
		$dbh->do('CREATE INDEX nodarp_arpdevice  ON nodarp     USING btree (arpdevice);');
		$dbh->do('CREATE INDEX nodarp_arpifname  ON nodarp     USING btree (arpifname);');
		$dbh->do('CREATE INDEX nodarp_ipupdate   ON nodarp     USING btree (ipupdate);');
		$dbh->do('CREATE INDEX nodarp_nodip      ON nodarp     USING btree (nodip);');
		$dbh->do('CREATE INDEX nodarp_mac        ON nodarp     USING btree (mac);');
		$dbh->do('CREATE INDEX nodarp_srvupdate  ON nodarp     USING btree (srvupdate);');
		$dbh->do('CREATE INDEX nodnd_nddevice    ON nodnd      USING btree (nddevice);');
		$dbh->do('CREATE INDEX nodnd_ndifname    ON nodnd      USING btree (ndifname);');
		$dbh->do('CREATE INDEX nodnd_ip6update   ON nodnd      USING btree (ip6update);');
		$dbh->do('CREATE INDEX nodnd_nodip6      ON nodnd      USING btree (nodip6);');
		$dbh->do('CREATE INDEX nodnd_mac         ON nodnd      USING btree (mac);');
		$dbh->do('CREATE INDEX nodnd_srv6update  ON nodnd      USING btree (srv6update);');
		$dbh->do('CREATE INDEX nodes_device      ON nodes      USING btree (device);');
		$dbh->do('CREATE INDEX nodes_mac         ON nodes      USING btree (mac);');
		$dbh->do('CREATE INDEX nodes_vlanid      ON nodes      USING btree (vlanid);');
		$dbh->do('CREATE INDEX nodetrack_device  ON nodetrack  USING btree (device);');
		$dbh->do('CREATE INDEX nodetrack_ifname  ON nodetrack  USING btree (ifname);');
		$dbh->do('CREATE INDEX stolen_device     ON stolen     USING btree (device);');
		$dbh->do('CREATE INDEX stolen_mac        ON stolen     USING btree (mac);');
		$dbh->do('CREATE INDEX system_name       ON system     USING btree (name);');
		$dbh->do('CREATE INDEX vlans_device      ON vlans      USING btree (device);');
		$dbh->do('CREATE INDEX vlans_vlanid      ON vlans      USING btree (vlanid);');
		$dbh->do('CREATE INDEX wlan_mac          ON wlan       USING btree (mac);');

		$dbh->commit;
	}

	$sth->finish if $sth;
	&db::Disconnect();
	print "... done.\n\n";
}


=head2 FUNCTION ReadDev()

Read devices table.

B<Options> match statement

B<Globals> main::dev

B<Returns> -

=cut
sub ReadDev{

	my $npdev = 0;
	my $where = (defined $_[0])?$_[0]:'';

	if($where eq 'all'){
		$where = '';
		&misc::Prt("RDEV:Reading all devices\n");
	}elsif($where){
		$where = "WHERE $where";
		&misc::Prt("RDEV:Reading devices $where\n");
	}

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
		$main::dev{$f[0]}{co} = $f[11];
		$main::dev{$f[0]}{dg} = $f[12];
		$main::dev{$f[0]}{dm} = $f[13];
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
		$main::dev{$f[0]}{cfc}= $f[32];
		$main::dev{$f[0]}{bup}= ($f[33])?substr($f[33],0,1):'?';
		$main::dev{$f[0]}{cst}= ($f[33])?substr($f[33],1,1):'?';

		$main::dev{$f[0]}{pls} = $main::dev{$f[0]}{ls};						# Preserve lastseen for calculations
		$main::dev{$f[0]}{pip} = $main::dev{$f[0]}{ip};

		$misc::seedini{$main::dev{$f[0]}{ip}}{dv} = $main::dev{$f[0]}{rv};			# Tie comm & ver to IP,
		$misc::seedini{$main::dev{$f[0]}{ip}}{dc} = $main::dev{$f[0]}{rc};
		$misc::seedini{$main::dev{$f[0]}{ip}}{na} = $f[0];
		$misc::seedini{$main::dev{$f[0]}{oi}}{dv} = $main::dev{$f[0]}{rv};			# it's all we have at first
		$misc::seedini{$main::dev{$f[0]}{oi}}{dc} = $main::dev{$f[0]}{rc};
		$misc::seedini{$main::dev{$f[0]}{oi}}{na} = $f[0];

		$misc::map{$main::dev{$f[0]}{ip}}{na} = $f[0] if $main::dev{$f[0]}{os} eq 'MSMc';	# MSM APs always send their SN via CDP!

		$npdev++;
	}
	$sth->finish if $sth;

	&misc::Prt("RDEV:$npdev devices read from $misc::dbname.devices\n");
	return $npdev;
}


=head2 FUNCTION ReadLink()

Read links table.

B<Options> match statement

B<Globals> main::link

B<Returns> -

=cut
sub ReadLink{

	my $nlink = 0;
	my $where = ($_[0])?"WHERE $_[0]":'';

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

	&misc::Prt("RLNK:$nlink links ($where) read from $misc::dbname.links\n");
	return $nlink;
}


=head2 FUNCTION ReadNod()

Read nodes table.

B<Options> match statement

B<Globals> main::nod

B<Returns> -

=cut
sub ReadNod{

	my $nnod = 0;
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
		if($f[16]){
			$main::nod{$f[2]}{i6} = ($misc::backend eq 'Pg')?inet_pton(AF_INET6,$f[16]):$f[16];
		}else{
			$main::nod{$f[2]}{i6} = '';
		}
		$main::nod{$f[2]}{tp} = $f[17];
		$main::nod{$f[2]}{up} = $f[18];
		$main::nod{$f[2]}{os} = $f[19];
		$main::nod{$f[2]}{ty} = $f[20];
		$main::nod{$f[2]}{ou} = $f[21];
		$main::nod{$f[2]}{us} = $f[22];
		$nnod++;
	}
	$sth->finish if $sth;

	&misc::Prt("RNOD:$nnod nodes read ($where) from $misc::dbname.nodes\n");
	return $nnod;
}


=head2 FUNCTION BackupCfg()

Backup configuration and any changes.

B<Options> device name

B<Globals> -

B<Returns> -

=cut
sub BackupCfg{

	my ($dv) = @_;
	my $cfg  = join("\n",@misc::curcfg);
	my $chg  = "";

	my $sth = $dbh->prepare("SELECT config,changes FROM configs where device = ".$dbh->quote($dv) );
	$sth->execute();

	if($sth->rows == 0 and !$main::opt{'t'}){								# No previous config found, therefore write new.
		$sth = $dbh->prepare("INSERT INTO configs(device,config,changes,time) VALUES ( ?,?,?,? )");
		$sth->execute ($dv,$cfg,$chg,$main::now);
		&misc::WriteCfg($dv) if defined $main::opt{'B'};
		&misc::Prt('','Bn');
		$misc::mq += &mon::Event('B','100','cfgn',$dv,$dv,"New config with ".length($cfg)." characters added");
	}elsif($sth->rows == 1){									# Previous config found, get changes
		my @pc = $sth->fetchrow_array;
		my @pcfg = split(/\n/,$pc[0]);
		my $achg = &misc::Diff(\@pcfg, \@misc::curcfg);
		if(!$main::opt{'t'}){
			if($achg){									# Only write new, if changed
				$chg  = $pc[1] . "#--- " . localtime($main::now) ." ---#\n". $achg;
				$dbh->do("DELETE FROM configs where device = ".$dbh->quote($dv) );
				$sth = $dbh->prepare("INSERT INTO configs(device,config,changes,time) VALUES ( ?,?,?,? )");
				$sth->execute ($dv,$cfg,$chg,$main::now);
				&misc::WriteCfg($dv) if defined $main::opt{'B'};
				my $len = length($achg);
				$achg =~ s/["']//g;
				my $msg = "Config changed by $len characters: $achg";
				my $lvl = ($len > 1000)?100:50;
				$misc::mq += &mon::Event('B',$lvl,'cfgc',$dv,$dv,$msg);
				&misc::Prt('',"Bu");
			} else {
			    &misc::WriteCfg($dv) if defined $main::opt{'B'} and ! -e "$misc::nedipath/conf/$dv";	# Write config file anyway if no dev folder exists
			}
		}
	}
	$sth->finish if $sth;
}


=head2 FUNCTION WriteDev()

Write a device to devices table.

B<Options> devicename

B<Globals> -

B<Returns> -

=cut
sub WriteDev{

	my ($dv) = @_;

	$dbh->do("DELETE FROM  devices where device = ".$dbh->quote($dv) );
	$sth = $dbh->prepare("INSERT INTO devices(	device,devip,serial,type,firstdis,lastdis,services,
							description,devos,bootimage,location,contact,
							devgroup,devmode,snmpversion,readcomm,cliport,login,icon,
							origip,cpu,memcpu,temp,cusvalue,cuslabel,sysobjid,writecomm,devopts,size,stack,maxpoe,totpoe,cfgchange,cfgstatus
							) VALUES ( ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,? )");
	$sth->execute (	$dv,
			&misc::Ip2Dec($main::dev{$dv}{ip}),
			(defined $main::dev{$dv}{sn})?$main::dev{$dv}{sn}:'',
			(defined $main::dev{$dv}{ty})?substr($main::dev{$dv}{ty},0,31):'',		# substr here to catch all sources
			$main::dev{$dv}{fs},
			$main::dev{$dv}{ls},
			(defined $main::dev{$dv}{sv})?$main::dev{$dv}{sv}:0,
			(defined $main::dev{$dv}{de})?substr($main::dev{$dv}{de},0,255):'',
			(defined $main::dev{$dv}{os})?$main::dev{$dv}{os}:'',
			(defined $main::dev{$dv}{bi})?$main::dev{$dv}{bi}:'',
			(defined $main::dev{$dv}{lo})?$main::dev{$dv}{lo}:'',
			(defined $main::dev{$dv}{co})?$main::dev{$dv}{co}:'',
			(defined $main::dev{$dv}{dg})?$main::dev{$dv}{dg}:'',
			(defined $main::dev{$dv}{dm})?$main::dev{$dv}{dm}:0,
			((defined $main::dev{$dv}{rv})?$main::dev{$dv}{rv}:0) + ((defined $main::dev{$dv}{wv})?$main::dev{$dv}{wv}:0) * 4 + ((defined $main::dev{$dv}{hc})?$main::dev{$dv}{hc}:0),
			(defined $main::dev{$dv}{rc})?$main::dev{$dv}{rc}:'',
			(defined $main::dev{$dv}{cp})?$main::dev{$dv}{cp}:0,
			(defined $main::dev{$dv}{us})?$main::dev{$dv}{us}:'',
			&misc::DevIcon($main::dev{$dv}{sv},$main::dev{$dv}{ic}),
			&misc::Ip2Dec($main::dev{$dv}{oi}),
			(defined $main::dev{$dv}{cpu})?$main::dev{$dv}{cpu}:0,
			(defined $main::dev{$dv}{mcp})?$main::dev{$dv}{mcp}:0,
			(defined $main::dev{$dv}{tmp})?$main::dev{$dv}{tmp}:0,
			(defined $main::dev{$dv}{cuv})?$main::dev{$dv}{cuv}:0,
			(defined $main::dev{$dv}{cul})?$main::dev{$dv}{cul}:'',
			(defined $main::dev{$dv}{so})?$main::dev{$dv}{so}:'',
			(defined $main::dev{$dv}{wc})?$main::dev{$dv}{wc}:'',
			(defined $main::dev{$dv}{opt})?$main::dev{$dv}{opt}:'',
			(defined $main::dev{$dv}{siz})?$main::dev{$dv}{siz}:0,
			($main::dev{$dv}{stk})?$main::dev{$dv}{stk}:1,
			(defined $main::dev{$dv}{mpw})?$main::dev{$dv}{mpw}:0,
			(defined $main::dev{$dv}{tpw})?$main::dev{$dv}{tpw}:0,
			(defined $main::dev{$dv}{cfc})?$main::dev{$dv}{cfc}:0,
			((defined $main::dev{$dv}{bup})?$main::dev{$dv}{bup}:'-').((defined $main::dev{$dv}{cst})?$main::dev{$dv}{cst}:'-')
			);

	$sth->finish if $sth;

	&misc::Prt("WDEV:$dv written to $misc::dbname.devices\n");

	&Inventory($dv) if $main::opt{'Y'};

	my $regex = ($misc::backend eq 'Pg')?'!~':'not regexp';
	my $ldel = &db::Delete('links',"device = ".$dbh->quote($dv)." AND linktype $regex '^STA' AND time < '$misc::retire'");
	$mq += &mon::Event('l',100,'nedl',$dv,$dv,"$ldel dynamic links older than ".localtime($misc::retire)." have been retired") if $ldel;
}

=head2 FUNCTION ReadAddr()

Reads IP and MAC addresses of all IF in DB for topology awareness.

B<Options> -

B<Globals> misc::ifmac, misc::ifip

B<Returns> -

=cut
sub ReadAddr{

	my $nmac = 0;
	my $nip  = 0;
	my $nip6 = 0;

	my $sth = $dbh->prepare("SELECT device,ifmac,ifname FROM interfaces where ifmac != ''");
	$sth->execute();
	while((my @i) = $sth->fetchrow_array){
		push @{$misc::ifmac{$i[1]}{$i[0]}},$i[2];
 		$nmac++;
	}
	$sth->finish if $sth;

	$sth = $dbh->prepare("SELECT device,ifname,ifip,ifip6 FROM networks where ifip != 2130706433");# Ignore 127.0.0.1
	$sth->execute();
	while ((my @i) = $sth->fetchrow_array) {
		if($i[2]){
			push @{$misc::ifip{ &misc::Dec2Ip($i[2]) }{$i[0]}},$i[1];
			$nip++;
		}elsif($i[3]){										# Dies on empty or 0
			my $ip6 = ($misc::backend eq 'mysql')?misc::IP6Text($i[3]):$i[3];
			push @{$misc::ifip{$ip6}{$i[0]}},$i[1];
			$nip6++;
		}
	}
	$sth->finish if $sth;

	&misc::Prt("RADDR:$nmac MAC, $nip IP and $nip6 IPv6 addresses read.\n");
}

=head2 FUNCTION ReadInt()

Reads IF information.

B<Options> devicename

B<Globals> main::int

B<Returns> -

=cut
sub ReadInt{

	my $where   = ($_[0])?"WHERE $_[0]":"";
	my $nint = 0;

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
		$main::int{$i[0]}{$i[2]}{idi} = $i[20];
		$main::int{$i[0]}{$i[2]}{odi} = $i[21];
		$main::int{$i[0]}{$i[2]}{did} = $i[22];
		$main::int{$i[0]}{$i[2]}{dod} = $i[23];
		$main::int{$i[0]}{$i[2]}{ibr} = $i[24];
		$main::int{$i[0]}{$i[2]}{dib} = $i[25];
		$main::int{$i[0]}{$i[2]}{chg} = $i[26];
		$main::int{$i[0]}{$i[2]}{poe} = $i[27];
		$main::int{$i[0]}{$i[2]}{tra} = $i[29];
		$main::int{$i[0]}{$i[2]}{bra} = $i[30];
		$main::int{$i[0]}{$i[2]}{mcf} = $i[31];

		$main::int{$i[0]}{$i[2]}{plt} = $i[3];							# Previous... (lt=linktype)
		$main::int{$i[0]}{$i[2]}{pst} = $i[8];
		$main::int{$i[0]}{$i[2]}{psp} = $i[9];
		$main::int{$i[0]}{$i[2]}{pdp} = $i[10];
		$main::int{$i[0]}{$i[2]}{pvi} = $i[11];
		$main::int{$i[0]}{$i[2]}{pcg} = $i[26];
		$main::int{$i[0]}{$i[2]}{pco} = $i[28];

		$nint++;
	}
	$sth->finish if $sth;

	&misc::Prt("RIF :$nint IF read ($where) from $misc::dbname.interfaces\n");
	return $nint;
}


=head2 FUNCTION WriteInt()

Write the interfaces table, calculate deltas and notify if desired.

B<Options> devicename

B<Globals> main::int

B<Returns> -

=cut
sub WriteInt{

	my ($dv,$skip) = @_;
	my $tint = 0;

	$dbh->do("DELETE FROM  interfaces where device = ".$dbh->quote($dv) );
	$sth = $dbh->prepare("INSERT INTO interfaces(	device,ifname,ifidx,linktype,iftype,ifmac,ifdesc,alias,ifstat,speed,duplex,pvid,
							inoct,inerr,outoct,outerr,dinoct,dinerr,doutoct,douterr,indis,outdis,dindis,doutdis,inbrc,dinbrc,lastchg,poe,comment)
							VALUES ( ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,? )");
	foreach my $i ( sort keys %{$main::int{$dv}} ){
		if(!$main::int{$dv}{$i}{new}){
			&misc::Prt("WIF :Index $i not found, not writing\n");
		}else{
			&misc::CheckIF($dv,$i,$skip);
			$sth->execute (	$dv,
					$main::int{$dv}{$i}{ina},
					$i,
					($main::int{$dv}{$i}{lty})?$main::int{$dv}{$i}{lty}:'',
					($main::int{$dv}{$i}{typ})?$main::int{$dv}{$i}{typ}:0,
					($main::int{$dv}{$i}{mac})?$main::int{$dv}{$i}{mac}:'',
					($main::int{$dv}{$i}{des})?substr($main::int{$dv}{$i}{des},0,255):'',
					($main::int{$dv}{$i}{ali})?substr($main::int{$dv}{$i}{ali},0,64):'',
					($main::int{$dv}{$i}{sta})?$main::int{$dv}{$i}{sta}:0,
					($main::int{$dv}{$i}{spd})?$main::int{$dv}{$i}{spd}:0,
					($main::int{$dv}{$i}{dpx})?$main::int{$dv}{$i}{dpx}:'',
					($main::int{$dv}{$i}{vid})?$main::int{$dv}{$i}{vid}:0,
					($main::int{$dv}{$i}{ioc})?$main::int{$dv}{$i}{ioc}:0,
					($main::int{$dv}{$i}{ier})?$main::int{$dv}{$i}{ier}:0,
					($main::int{$dv}{$i}{ooc})?$main::int{$dv}{$i}{ooc}:0,
					($main::int{$dv}{$i}{oer})?$main::int{$dv}{$i}{oer}:0,
					($main::int{$dv}{$i}{dio})?$main::int{$dv}{$i}{dio}:0,
					($main::int{$dv}{$i}{die})?$main::int{$dv}{$i}{die}:0,
					($main::int{$dv}{$i}{doo})?$main::int{$dv}{$i}{doo}:0,
					($main::int{$dv}{$i}{doe})?$main::int{$dv}{$i}{doe}:0,
					($main::int{$dv}{$i}{idi})?$main::int{$dv}{$i}{idi}:0,
					($main::int{$dv}{$i}{odi})?$main::int{$dv}{$i}{odi}:0,
					($main::int{$dv}{$i}{did})?$main::int{$dv}{$i}{did}:0,
					($main::int{$dv}{$i}{dod})?$main::int{$dv}{$i}{dod}:0,
					($main::int{$dv}{$i}{ibr})?$main::int{$dv}{$i}{ibr}:0,
					($main::int{$dv}{$i}{dib})?$main::int{$dv}{$i}{dib}:0,
					($main::int{$dv}{$i}{chg})?$main::int{$dv}{$i}{chg}:0,
					($main::int{$dv}{$i}{poe})?$main::int{$dv}{$i}{poe}:0,
					($main::int{$dv}{$i}{com})?substr($main::int{$dv}{$i}{com},0,255):'' );
			$tint++;
		}
	}

	$sth->finish if $sth;

	&misc::Prt("WIF :$tint interfaces written to $misc::dbname.interfaces\n");
}


=head2 FUNCTION WriteMod()

Write the modules table, detect changes and notify if desired.

B<Options> devicename

B<Globals> -

B<Returns> -

=cut
sub WriteMod{

	my ($dv) = @_;
	my $nmod = 0;
	my %dbmod= ();

	if(exists $main::mon{$dv} and $misc::notify =~ /m/i){						# Track existing mods if enabled
		my $sth = $dbh->prepare("SELECT * FROM modules WHERE device = ".$dbh->quote($dv) );
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
	$dbh->do("DELETE FROM  modules where device = ".$dbh->quote($dv) );
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

	$sth->finish if $sth;

	&misc::Prt("WMOD:$nmod modules written to $misc::dbname.modules\n");
}


=head2 FUNCTION WriteVlan()

Rewrites the vlans of a given device.

B<Options> devicename

B<Globals> -

B<Returns> -

=cut
sub WriteVlan{

	my ($dv) = @_;
	my $nvlans = 0;

	$dbh->do("DELETE FROM  vlans where device = ".$dbh->quote($dv) );
	my $sth = $dbh->prepare("INSERT INTO vlans(device,vlanid,vlanname) VALUES ( ?,?,? )");
	foreach my $i ( sort keys %{$main::vlan{$dv}} ){
		$sth->execute ( $dv,
				$i,
				$main::vlan{$dv}{$i}
				);
		$nvlans++;
	}

	$sth->finish if $sth;

	&misc::Prt("WVLN:$nvlans vlans written to $misc::dbname.vlans\n");
}


=head2 FUNCTION WriteNet()

Rewrites the networks of a given device.

B<Options> devicename

B<Globals> -

B<Returns> -

=cut
sub WriteNet{

	my ($dv) = @_;
	my $nip  = 0;

	$dbh->do("DELETE FROM  networks where device = ".$dbh->quote($dv) );
	my $sth = $dbh->prepare("INSERT INTO networks( device,ifname,ifip,ifip6,prefix,vrfname,vrfrd,status ) VALUES ( ?,?,?,?,?,?,?,? )");
	foreach my $n ( sort keys %{$main::net{$dv}} ){
		$sth->execute (	$dv,
				$main::net{$dv}{$n}{ifn},
				((!$main::net{$dv}{$n}{ip6})?misc::Ip2Dec($n):0),
				misc::IP6toDB($n,$main::net{$dv}{$n}{ip6}),
				$main::net{$dv}{$n}{pfx},
				$main::net{$dv}{$n}{vrf},
				$main::net{$dv}{$n}{vrd},
				$main::net{$dv}{$n}{sta} );
		$nip++;
	}

	$sth->finish if $sth;

	&misc::Prt("WNET:$nip networks written to $misc::dbname.networks\n");
}

=head2 FUNCTION WriteArpND()

Process Address information and write to arp table

B<Options> device, pointer to Address hash

B<Globals> -

B<Returns> -

=cut
sub WriteArpND{#TODO refactor to insert ?,?,? and update ?,? for commit efficiency?

	my ($dv,$n) = @_;

	my %arp   = ();
	my $narin = my $narup = my $narp6 = my $ad = my $dn = my $bd = my $fl = 0;
	my $arpp  = (exists $main::mon{$dv})?$main::mon{$dv}{ap}:$misc::arppoison;
	my @keys6 = ('mac', 'nodip6');
	my @keys  = ('mac', 'nodip');

	&misc::Prt("\nWrite ArpND -------------------------------------------------------------------\n");
	foreach my $ver ( keys %$n ){
		foreach my $mc ( keys %{$n->{$ver}} ){
			if( scalar keys %{$n->{$ver}{$mc}} > 1 ){
				&misc::Prt("WAND:$mc seen on ".join(', ', keys %{$n->{$ver}{$mc}})." and IVL=$misc::useivl\n");
			}
			foreach my $po ( keys %{$n->{$ver}{$mc}} ){
				my $mcvl = $mc;
				my $vl   = ($po =~ /^Vl[-]?(\d+)$/) ? $1 : 0;
				$mcvl    = $mc.(($misc::useivl and $vl =~ /$misc::useivl/)?$vl:'');	# Make MAC unique by adding vlid if IVL is requested
				if($ver){
					my $nips = scalar keys %{$n->{$ver}{$mc}{$po}};
					&misc::Prt("DBG :$mc has $nips IPv6 addresses\n") if $main::opt{'d'};
					my $dbmc = &db::Select('nodnd',\@keys6,'*',"mac='$mcvl'");	# What of this MAC's in the DB?
					foreach my $ip ( keys %{$n->{$ver}{$mc}{$po}} ){
						my $add = 0;
						my $dbip = misc::IP6toDB($ip,1);			# This means binary comparison with mysql backend...
						if(  exists $dbmc->{$mcvl} ){				# MAC exists in DB
							my $ndbips = scalar keys %{$dbmc->{$mcvl}};
							&misc::Prt("DBG :$ndbips IPv6 addresses in DB\n") if $main::opt{'d'};
							if( exists $dbmc->{$mcvl}{$dbip} ){		# MAC-IP exists in DB, only update if DB entry exeeds revive (retire/2) days
								if( $n->{$ver}{$mc}{$po}{$ip} > $misc::revive and $dbmc->{$mcvl}{$dbip}{'ip6update'} < $misc::revive ){
									my $dnsna = &db::WriteDNS($ip,6);
									&db::Update('nodnd',"ip6update=$n->{$ver}{$mc}{$po}{$ip},nddevice=".$db::dbh->quote($dv).",ndifname='$po'","mac='$mcvl' and nodip6=".$db::dbh->quote($dbip) );
									$narup++;
								}
							}else{						# MAC-IP doesn't exist...
								if( $nips == 1 and  $ndbips == 1 ){	# Currently have 1 and DB has 1 -> IP changed
									my $dnsna = &db::WriteDNS($ip,6);
									my @k = keys %{$dbmc->{$mcvl}};
									my $ip6c = $dbmc->{$mcvl}{$k[0]}{'ip6changes'} + 1;
									$misc::mq += &mon::Event('J',100,'secj',$dv,$dv,"Node $mc changed IPv6 to $ip".(($dnsna)?" and name $dnsna":"") );
									&db::Update('nodnd',"nodip6=".$db::dbh->quote($dbip).",ip6update=$n->{$ver}{$mc}{$po}{$ip},ip6changes=$ip6c,nddevice=".$db::dbh->quote($dv).",ndifname='$po'","mac='$mcvl'");
									$narup++;
								}else{
									$add = 1;
								}
							}							
						}else{							# MAC doesn't exist at all add...
							$add = 1;
						}
						if( $add ){						# Add if logic above conlcuded to!
							my $dnsna = &db::WriteDNS($ip,6);
							$misc::mq += &mon::Event('J',100,'secj',$dv,$dv,"Node $mc has new IP address $ip".(($dnsna)?" and name $dnsna":"") );
							&db::Insert('nodnd','mac,nodip6,ip6update,nddevice,ndifname',"'$mcvl',".$db::dbh->quote($dbip).",$n->{$ver}{$mc}{$po}{$ip},".$db::dbh->quote($dv).",'$po'");
							$narin++;
						}
					}
				}else{
					my $nips = scalar keys %{$n->{$ver}{$mc}{$po}};
					&misc::Prt("DBG :$mc has $nips IP addresses\n") if $main::opt{'d'};
					my $dbmc = &db::Select('nodarp',\@keys,'*',"mac='$mcvl'");	# What of this MAC's in the DB?
					if($arpp and $nips > $arpp){					# Check for ARP poisoning
						$misc::mq += &mon::Event('N',150,'secp',$dv,$dv,"$nips IP addresses for $mc exceed ARP poison threshold of $arpp");
					}					
					foreach my $ip ( keys %{$n->{$ver}{$mc}{$po}} ){
						my $add = 0;
						my $dip = &misc::Ip2Dec($ip);
						if(  exists $dbmc->{$mcvl} ){				# MAC exists in DB
							my $ndbips = scalar keys %{$dbmc->{$mcvl}};
							if( exists $dbmc->{$mcvl}{$dip} ){		# MAC-IP exists in DB
								if( $n->{$ver}{$mc}{$po}{$ip} > $misc::revive and $dbmc->{$mcvl}{$dip}{'ipupdate'} < $misc::revive ){
									my $dnsna = &db::WriteDNS($ip);
									&db::Update('nodarp',"ipupdate=$n->{$ver}{$mc}{$po}{$ip},arpdevice=".$db::dbh->quote($dv).",arpifname='$po'","mac='$mcvl' and nodip=$dip");
									$narup++;
								}
							}else{						# MAC-IP doesn't exist...
								if( $nips == 1 and  $ndbips == 1 ){	# Currently have 1 and DB has 1 -> IP changed
									my $dnsna = &db::WriteDNS($ip);
									my @k = keys %{$dbmc->{$mcvl}};
									my $ipc = $dbmc->{$mcvl}{$k[0]}{'ipchanges'} + 1;
									$misc::mq += &mon::Event('J',100,'secj',$dv,$dv,"Node $mc changed IP to $ip".(($dnsna)?" and name $dnsna":"") );
									&db::Insert('iptrack','mac,ipupdate,Aname,nodip,vlanid,device',"'$mcvl',$main::now,'$dnsna',$dip,$vl,".$db::dbh->quote($dv) );
									&db::Update('nodarp',"nodip=$dip,ipupdate=$n->{$ver}{$mc}{$po}{$ip},ipchanges=$ipc,arpdevice=".$db::dbh->quote($dv).",arpifname='$po'","mac='$mcvl'");
									$narup++;
								}else{
									$add = 1;
								}
							}							
						}else{							# MAC doesn't exist at all add...
							$add = 1;
						}
						if( $add ){						# Add if logic above conlcuded to!
							my $dnsna = &db::WriteDNS($ip);
							$misc::mq += &mon::Event('J',100,'secj',$dv,$dv,"Node $mc has new IP address $ip".(($dnsna)?" and name $dnsna":"") );
							&db::Insert('nodarp','mac,nodip,ipupdate,arpdevice,arpifname',"'$mcvl',$dip,$n->{$ver}{$mc}{$po}{$ip},".$db::dbh->quote($dv).",'$po'");
							$narin++;
						}
						if($main::opt{'o'}){					# Only add if OUI discovery set
							my $oui = &misc::GetOui($mc);
							if($oui =~ /$misc::ouidev/i or $mc =~ /$misc::ouidev/){
								if(grep /\Q$mc\E/,(@misc::doneid,@misc::failid,@misc::todo) ){# Don't queue if done or queued.
									&misc::Prt("OUI :Device done already\n");
									$dn++;
								}elsif($mc =~ /$misc::border/ or $oui =~ /$misc::border/){# ...or matching the border...
									&misc::Prt("OUI :$mc or $oui matches border /$misc::border/\n");
									$bd++;
								}else{
									&misc::Prt("OUI :MAC or '$oui' matches ouidev\n");
									$ad += misc::CheckTodo($mc,&misc::MapIp($ip,'ip') );
								}
							}else{
								&misc::Prt("DBG :OUI no match with $oui =~ /$misc::ouidev/i or $mc =~ /$misc::ouidev/\n") if $main::opt{'d'};
							}
						}
					}
				}
			}
		}
	}
	&misc::Prt("WAND:$narin new IPs and $narup updates written\n"," a".($narup + $narin)." o$ad/$dn b$bd".($warn?" ":"   ") );
}

=head2 FUNCTION WriteDNS()

Resolve DNS (or use optional name e.g. from arpwatch) and write to dns or dns6

B<Options> IP, version, name, timestamp

B<Globals> -

B<Returns> -

=cut
sub WriteDNS(){

	my ($ip,$ver,$name,$time) = @_;

	my $dnsup = ($time)?$time:$main::now;
	my $dnsna = '';
	my @keys6 = ('aaaaname', 'nodip6');
	my @keys  = ('aname', 'nodip');


	if($name){
		$dnsna = $name;
	}else{
		$dnsna = misc::IP2Name($ip);
	}

	if($dnsna and $ver){
		&misc::Prt( sprintf("WDNS:%-39.39s AAAAname %s\n",$ip,$dnsna) ) if $main::opt{'d'};
		my $dbip = misc::IP6toDB($ip,1);							# This means binary comparison with mysql backend...
		my $dbnams = &db::Select('dns6',\@keys6,'*',"nodip6=".$db::dbh->quote($dbip)." AND aaaaname='$dnsna'");
		if( exists $dbnams->{$dnsna}{$dbip} ){
			if( $dnsup > $misc::revive and $dbnams->{$dnsna}{$dbip}{'dns6update'} < $misc::revive ){
				&db::Update('dns6',"dns6update=$dnsup","aaaaname='$dnsna' and nodip6=".$db::dbh->quote($dbip) );					
			}
		}else{
			&db::Insert('dns6','nodip6,aaaaname,dns6update',$db::dbh->quote($dbip).",'$dnsna',$main::now");
		}
		return $dnsna;
	}elsif($dnsna){
		&misc::Prt( sprintf("WDNS:%-15.15s Aname %s\n",$ip,$dnsna) ) if $main::opt{'d'};
		my $dip    = &misc::Ip2Dec($ip);
		my $dbnams = &db::Select('dns',\@keys,'*',"aname='$dnsna' AND nodip=$dip");
		if( exists $dbnams->{$dnsna}{$dip} ){
			if( $dnsup > $misc::revive and $dbnams->{$dnsna}{$dip}{'dnsupdate'} < $misc::revive ){
				&db::Update('dns',"dnsupdate=$dnsup","aname='$dnsna' AND nodip=$dip");					
			}
		}else{
			&db::Insert('dns','nodip,aname,dnsupdate',"$dip,'$dnsna',$main::now");
		}
		return $dnsna;
	}
	return '';
}

=head2 FUNCTION WriteLink()

Writes the links of a given device.

B<Options> devicename

B<Globals> -

B<Returns> -

=cut
sub WriteLink{

	my ($dv,$i,$ne,$ni) = @_;

	my $sth = $dbh->prepare("SELECT * FROM links WHERE device=".$dbh->quote($dv)." AND ifname='$i' AND neighbor=".$dbh->quote($ne)." AND nbrifname='$ni' AND linktype='$main::link{$dv}{$i}{$ne}{$ni}{ty}'");
	$sth->execute();
	if($sth->rows){
		&misc::Prt("WLNK:Link exists from $dv,$i to $ne,$ni. Updating $main::link{$dv}{$i}{$ne}{$ni}{ty} link\n");
		$sth = $dbh->prepare("UPDATE links SET bandwidth=?,linkdesc=?,nbrduplex=?,nbrvlanid=?,time=? WHERE device=".$dbh->quote($dv)." AND ifname='$i' AND neighbor=".$dbh->quote($ne)." AND nbrifname='$ni' AND linktype='$main::link{$dv}{$i}{$ne}{$ni}{ty}'");
		$sth->execute (	$main::link{$dv}{$i}{$ne}{$ni}{bw},
				substr($main::link{$dv}{$i}{$ne}{$ni}{de},0,255),
				$main::link{$dv}{$i}{$ne}{$ni}{du},
				$main::link{$dv}{$i}{$ne}{$ni}{vl},
				$main::now );
	}else{
		&misc::Prt("WLNK:No link from $dv,$i to $ne,$ni. Creating $main::link{$dv}{$i}{$ne}{$ni}{ty} link\n");
		$sth = $dbh->prepare("INSERT INTO links(device,ifname,neighbor,nbrifname,bandwidth,linktype,linkdesc,nbrduplex,nbrvlanid,time) VALUES ( ?,?,?,?,?,?,?,?,?,? )");
		$sth->execute (	$dv,
				$i,
				$ne,
				$ni,
				$main::link{$dv}{$i}{$ne}{$ni}{bw},
				$main::link{$dv}{$i}{$ne}{$ni}{ty},
				$main::link{$dv}{$i}{$ne}{$ni}{de},
				$main::link{$dv}{$i}{$ne}{$ni}{du},
				$main::link{$dv}{$i}{$ne}{$ni}{vl},
				$main::now );
	}

	$sth->finish if $sth;
}


=head2 FUNCTION Inventory()

Update Devices/Modules in Stock, which are discovered on the network.

B<Options> devicename

B<Globals> -

B<Returns> -

=cut
sub Inventory{

	my $dv     = $_[0];
	my $notnew = ($main::opt{'Y'} =~ /n/ and $main::dev{$dv}{pls})?1:0;

	if( $dbh->do("UPDATE inventory SET assetupdate=$main::now,comment=".$dbh->quote("Rediscovered as $dv with IP $main::dev{$dv}{ip}").",state=100 where serial = '$main::dev{$dv}{sn}' ") + 0){
		&misc::Prt("INV :Device $main::dev{$dv}{sn} ($dv) updated in $misc::dbname.inventory\n");
	}elsif( $main::opt{'Y'} =~/[a]/ or $main::opt{'Y'} =~/[s]/ and $main::dev{$dv}{rv} ){
		if( $notnew ){
			&misc::Prt("INV :Only adding new devices (due to -Yn)\n");
		}elsif(length $main::dev{$dv}{sn} > 3){
			$r = $dbh->do("INSERT INTO inventory (state,serial,assetclass,assettype,assetlocation,assetcontact,comment,assetupdate) VALUES
			(100,'$main::dev{$dv}{sn}',3,'$main::dev{$dv}{ty}',".$dbh->quote($main::dev{$dv}{lo}).",".$dbh->quote($main::dev{$dv}{co}).",".$dbh->quote("Discovered as $dv with IP $main::dev{$dv}{ip}").",".$main::now.')' );
			&misc::Prt("INV :Device $main::dev{$dv}{sn} ($dv) added to $misc::dbname.inventory\n") if $r;
		}
	}
	foreach my $i ( sort keys %{$main::mod{$dv}} ){
		if(length $main::mod{$dv}{$i}{sn} > 3){
			if( $dbh->do("UPDATE inventory SET assetupdate=$main::now,comment=".$dbh->quote("Rediscovered in $dv $main::mod{$dv}{$i}{sl}").",state=100 where serial = '$main::mod{$dv}{$i}{sn}'") + 0){
				&misc::Prt("INV :Module $i $main::mod{$dv}{$i}{sn} updated in $misc::dbname.inventory\n");
			}elsif( $main::opt{'Y'} =~/[m]/ ){
				if( $notnew ){
					&misc::Prt("INV :Only adding modules of new devices (due to -Yn)\n");
				}else{
					$r = $dbh->do("INSERT INTO inventory (state,serial,assetclass,assettype,assetlocation,assetcontact,comment,assetupdate) VALUES (100,'$main::mod{$dv}{$i}{sn}',$main::mod{$dv}{$i}{mc},'$main::mod{$dv}{$i}{mo}',".$dbh->quote($main::dev{$dv}{lo}).",".$dbh->quote($main::dev{$dv}{co}).",".$dbh->quote("Discovered in $dv $main::mod{$dv}{$i}{sl}").",".$main::now.')' );
					&misc::Prt("INV :Device $main::dev{$dv}{sn} ($dv) added to $misc::dbname.inventory\n") if $r;
				}
			}
		}
	}
	$dbh->commit;
}


=head2 FUNCTION WriteNod()


In AP mode the nodes are written even if the device (controlled AP) is new. This
is necessary since they're are deleted after writing to free up memory.

B<Options> -

B<Globals> main::nod, apmode

B<Returns> -

=cut
sub WriteNod{

	my ($m, $ap) = @_;

	my $nchg = my $inod = my $unod = 0;

	&misc::Prt("\nWrite Nodes ------------------------------------------------------------------\n");

	foreach my $dv ( keys %$m ){
		foreach my $mcvl ( keys %{$m->{$dv}} ){
			my $mc = substr($mcvl,0,12);
			my $vl = $m->{$dv}{$mcvl}{vl};
			my $if = $m->{$dv}{$mcvl}{if};
			my $me = $m->{$dv}{$mcvl}{me};
			my $us = ($m->{$dv}{$mcvl}{us})?$m->{$dv}{$mcvl}{us}:'';
			if( $misc::portprop{$dv}{$if}{lnk} ){
				&misc::Prt("DBG :$mc on $if not written, is on a link\n") if $main::opt{'d'};
			}elsif( !$ap and !$main::dev{$dv}{pls} ){
				&misc::Prt("DBG :$mc on $if not written, first discovery of $dv\n") if $main::opt{'d'};
			}else{
				my $dbmac  = &db::Select('nodes','mac','*',"mac='$mcvl'");
				if( exists $dbmac->{$mcvl} ){
					my $mehist = substr($me.$dbmac->{$mcvl}{'metric'},0,9);
					if($dbmac->{$mcvl}{'device'} ne $dv or $dbmac->{$mcvl}{'ifname'} ne $if){
						$dbmac->{$mcvl}{'ifchanges'}++;
						$nchg += &db::Update('nodes',"lastseen=$main::now,device=".$db::dbh->quote($dv).",ifname='$if',vlanid=$vl,ifchanges=$dbmac->{$mcvl}{'ifchanges'},metric='$mehist',noduser='$us'","mac='$mcvl'");
						&db::Insert('iftrack','mac,ifupdate,device,ifname,vlanid',"'$mcvl',$main::now,'$dbmac->{$mcvl}{'device'}','$dbmac->{$mcvl}{'ifname'}',$dbmac->{$mcvl}{'vlanid'}");
					}else{
						$unod += &db::Update('nodes',"vlanid=$vl,lastseen=$main::now,metric='$mehist',noduser='$us'","mac='$mcvl'");
					}
				}else{
					$inod += &db::Insert('nodes','mac,oui,firstseen,lastseen,device,ifname,vlanid,metric,ifupdate,noduser',"'$mcvl',".$db::dbh->quote( &misc::GetOui($mcvl) ).",$main::now,$main::now,".$db::dbh->quote($dv).",'$if',$vl,'$me',$main::now,'$us'");
					$misc::mq += &mon::Event('F',100,'secn',$dv,$dv,"Node $mc appeared on $if Vl$vl");
				}
				if( exists $misc::stolen->{$mc} ){
					$misc::mq += &mon::Event('N',150,'secs',$dv,$dv,"Node $mc marked stolen on $stolen->{$mc}{'device'}, $stolen->{$mc}{'ifname'} reappeared on $if Vl$vl");
				}
				&misc::Prt("DBG :$mc on $dv, $if vl$vl metric $me processed\n") if $main::opt{'d'};
			}
		}
	}

	&misc::Prt("WNOD:$inod inserted, $nchg nodes moved and $unod updated in $misc::dbname.nodes\n");
}

=head2 FUNCTION ReadMon()

Read monitoring table.

B<Options> type = dev, devip(decimal) or node

B<Globals> main::mon

B<Returns> -

=cut
sub ReadMon{

	my $nmon  = 0;
	my $sth = "";

	if($_[0] =~ /^[0-9]+$/){									# For single dev (used in trap.pl)
		$sth = $dbh->prepare("SELECT * FROM monitoring WHERE monip = $_[0]");
	}elsif($_[0] eq 'dev'){
		$sth = $dbh->prepare("SELECT monitoring.*,type,snmpversion & 3,readcomm FROM monitoring LEFT OUTER JOIN devices ON (monitoring.name = devices.device ) WHERE class = 'dev'");
	}elsif($_[0] eq 'node'){
		$sth = $dbh->prepare("SELECT * FROM monitoring WHERE class = 'node'");
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
		$main::mon{$na}{d1} = $f[18];
		$main::mon{$na}{d2} = $f[19];
		$main::mon{$na}{dv} = $f[20];								# Used for viewdev
		$main::mon{$na}{no} = $f[21];								# Per Target notify string
		$main::mon{$na}{nr} = $f[22];								# Per Target no-reply threshold
		$main::mon{$na}{lw} = $f[23];
		$main::mon{$na}{ca} = $f[24];
		$main::mon{$na}{ma} = $f[25];
		$main::mon{$na}{ta} = $f[26];
		$main::mon{$na}{pw} = $f[27];
		$main::mon{$na}{ap} = $f[28];
		$main::mon{$na}{sa} = $f[29];
		$main::mon{$na}{ty} = ($f[2] eq 'dev')?$f[30]:0;
		$main::mon{$na}{rv} = ($f[2] eq 'dev')?$f[31]:0;
		$main::mon{$na}{rc} = ($f[2] eq 'dev')?$f[32]:'';
		$main::mon{$na}{dc} = 0;								# #dependencies
		$main::mon{$na}{dd} = 0;								# #dependencies down
		$main::mon{$na}{nup}= 0;								# Just so 'new uptime' defined
		$nmon++;
	}
	$sth->finish if $sth;

	&misc::Prt("RMON:$nmon entries ($_[0]) read from $misc::dbname.monitoring\n");
	return $nmon;
}


=head2 FUNCTION ReadUser()

Read users table.

B<Options> match statement

B<Globals> -

B<Returns> -

=cut
sub ReadUser{

	my $nusr  = 0;
	my $where = ($_[0])?"WHERE $_[0]":'';

	my $sth = $dbh->prepare("SELECT usrname,email,phone,viewdev FROM users $where");
	$sth->execute();
	while ((my @f) = $sth->fetchrow_array) {
		$main::usr{$f[0]}{ml} = $f[1];
		$main::usr{$f[0]}{ph} = $f[2];
		$main::usr{$f[0]}{ph} =~ s/\D//g;							# Strip anything that isn't a number
		$main::usr{$f[0]}{vd} = '';
		if($f[3]){
			my @vd =  split(' ', $f[3]);
			my $vdin = shift @vd;
			my $vdop = shift @vd;
			my $vdst = join(' ', @vd);
			$vdst =~ s/["']//g;								# pre 1.0.9 had quotes in string
			if($misc::backend eq 'Pg'){
				$vdin = "CAST($vdin AS text)" if $vdop =~ /~/;
			}else{
				if( $vdop eq '!~' ){
					$vdop = 'not regexp';
				}elsif( $vdop eq '~' ){
					$vdop = 'regexp';
				}
			}
			$main::usr{$f[0]}{vd} = "$vdin $vdop '$vdst'";
		}
		$main::usr{$f[0]}{sms}= '';
		@{$main::usr{$f[0]}{mail}} = ();
		$nusr++;
	}
	$sth->finish if $sth;

	&misc::Prt("RUSR:$nusr entries ($_[0]) read from $misc::dbname.users\n");
	return $nusr;
}


=head2 FUNCTION Insert()

Insert DB Record

B<Options> table, string of columns, string of values

B<Globals> -

B<Returns> -

=cut
sub Insert{# TODO consider using hashref as argument, with that this can be used for writing stuff with ' and " (like configs) or simply try dbh->quote!!!

	my ($table, $cols, $vals) = @_;

	&misc::NagPipe($vals) if $table eq 'events' and $misc::nagpipe;

	my $r = 0;
	&misc::Prt("DBG :INSERT INTO $table ($cols) VALUES ($vals)\n") if $main::opt{'d'} =~ /d/;
	if(!$main::opt{'t'} or $main::opt{'t'} eq 'a'){							# Only write events, when testing access
		$r = $dbh->do("INSERT INTO $table ($cols) VALUES ($vals)") || die "ERR :INSERT INTO $table ($cols) VALUES ($vals)\n";
		$dbh->commit unless $ac;
	}
	&misc::Prt("DBG :INSERTED $r ROWS\n") if $main::opt{'d'} =~ /d/;

	return $r;
}


=head2 FUNCTION Delete()

Delete DB Record.

B<Options> table,match statement

B<Globals> -

B<Returns> -

=cut
sub Delete{

	my ($table, $match) = @_;

	&misc::Prt("DBG :DELETE FROM  $table WHERE $match\n") if $main::opt{'d'} =~ /d/;
	my $r = $dbh->do("DELETE FROM  $table WHERE $match") || die "ERR : DELETE FROM  $table WHERE $match\n";
	$dbh->commit unless $ac;

	&misc::Prt("ERR :$dbh->errstr\n") if(!$r);							# Something went wrong
	$r = 0 if($r eq '0E0');										# 0E0 actually means 0

	&misc::Prt("DBG :DELETED $r ROWS\n") if $main::opt{'d'} =~ /d/;
	return $r;
}


=head2 FUNCTION Update()

Update DB value(s).

B<Options> table, set statement, match statement

B<Globals> -

B<Returns> result

=cut
sub Update{

	my ($table, $set, $match) = @_;

	my $r = 0;
	&misc::Prt("DBG :UPDATE $table SET $set WHERE $match\n") if $main::opt{'d'} =~ /d/;
	if(!$main::opt{'t'}){
		$r = $dbh->do("UPDATE $table SET $set WHERE $match") || die "ERR : UPDATE $table SET $set WHERE $match\n";
		$dbh->commit unless $ac;
	}
	&misc::Prt("DBG :UPDATED $r ROWS\n") if $main::opt{'d'} =~ /d/;
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

	my $res = "";
	my $nre = 0;
	&misc::Prt("DBG :$qry\n") if $main::opt{'d'} =~ /d/;
	if($key){
		$res = $dbh->selectall_hashref($qry, $key);
		$nre = scalar keys %$res;
	}else{
		my $a = $dbh->selectall_arrayref($qry);
		$nre = scalar @$a;
		if($c !~ /[,*]/ and $nre == 1){								# dereference single values
			$res =  $$a[0][0];
		}elsif($nre == 0){
			$res = '';
		}else{
			$res = $a;
		}
	}
	&misc::Prt(' '.&main::Dumper($res)."\n") if $main::opt{'d'} =~ /d/;

	return $res;
}

1;
