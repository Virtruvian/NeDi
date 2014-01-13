#!/usr/bin/perl
=pod

=head1 PROGRAM trap.pl

A simple SNMP Trap handler for NeDi.

=head2 DESCRIPTION

Put this in /etc/snmp/snmptrapd.con:
disableAuthorization yes # optional, if traps are rejected
traphandle      /nedipath/trap.pl

Start snmptrapd (e.g. using System-Services). Incoming traps will be added to Monitoring-Events.

Upon receiving a trap, the script will check whether a device with the source IP exists. The default level will be set to 50 if it does (10 if not).

The script conaints some basic mappings to further raise authentication and configuration related events. Look at the source, if you want to add more mappings. Trap handling has not been further pursued in favour of syslog messages.

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

use strict;

use vars qw($p $now %mon);
$now = time;

$p = $0;
$p =~ s/(.*)\/(.*)/$1/;
if($0 eq $p){$p = "."};
require "$p/inc/libmisc.pm";										# Use the miscellaneous nedi library
&misc::ReadConf();
require "$p/inc/libdb-" . lc($misc::backend) . ".pm" || die "Backend error ($misc::backend)!";
require "$p/inc/libmon.pm";										# Use the SNMP function library
my $now = time;

# process the trap:
my $src = <STDIN>;
chomp($src);
my $ip = <STDIN>;
chomp($ip);
my $info = <STDIN>;
$info = <STDIN>;
$info = <STDIN>;
chomp($info);

my $level = 10;
my $src = $ip;
&db::ReadMon( &misc::Ip2Dec($ip) );

if(exists $mon{$src}){
	$src = $src;
	$level = 50;

	if($info =~ s/IF-MIB::ifIndex/Ifchange/){
	}elsif($info =~ s/SNMPv2-SMI::enterprises.45.1.6.4.3.5.1.0/Baystack Auth/){
	}elsif($info =~ s/SNMPv2-SMI::enterprises.9.2.9.3.1.1.1.1/Cisco Auth/){
	}elsif($info =~ s/SNMPv2-SMI::enterprises.9.2.1.5.0/Cisco Auth Failure!/){
		$level = 150;
	}elsif($info =~ s/SNMPv2-SMI::enterprises.9.2.9.3.1.1.2.1/Cisco TCPconnect/){
	}elsif($info =~ s/SNMPv2-SMI::enterprises.9.9.43/IOS Config change/){
		$level = 100;
	}elsif($info =~ s/SNMPv2-SMI::enterprises.9.5.1.1.28/CatOS Config change/){
		$level = 100;
	}elsif($info =~ s/SNMPv2-SMI::enterprises.9.9.46/Cisco VTP/){
	}
	if($mon{$src}{ef} ne "" and $info =~ /$mon{$src}{ef}/){
		&mon::SendMail("$src SNMP trap","$info") if ($mon{$src}{fw} & 1);
		&mon::SendSMS("$src: $info") if ($mon{$src}{fw} & 2);
	}
}

if( $mon{$src}{ed} ne "" and $info =~ /$mon{$src}{ed}/ ){					# insert only if drop doesn't match
	#print "$src ($_[0])\t dropped, matches /$mon{$src}{ed}/\n"  if $opt{'v'};
}else{
	&db::Insert('events','level,time,source,info,class,device',"\"$level\",\"$now\",\"$src\",\"$info\",\"trap\",\"$src\"");
}
