#!/usr/bin/perl
=pod

=head1 PROGRAM stati.pl

A simple statistics generator for NeDi.

=head2 DESCRIPTION

Run this by cron as you like...

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
use warnings;

use Getopt::Std;
use Net::SNMP qw(ticks_to_time);

use vars qw(%opt $p $now $days $from);

$days = 7;

getopts('v',\%opt) || &HELP_MESSAGE;

$now = time;
$from = $now - 86400 * $days;

$p   = $0;
$p   =~ s/(.*)\/(.*)/$1/;
if($0 eq $p){$p = "."};

require "$p/inc/libmisc.pm";										# Use the miscellaneous nedi library

&misc::ReadConf();
require "$p/inc/libdb-" . lc($misc::backend) . ".pm" || die "Backend error ($misc::backend)!";

my $cfg = &db::Select('configs','','count(device)',"time > $from");
my $cfe = &db::Select('events','','count(id)',"time > $from and class = 'cfge'");
&db::Insert('chat','time,user,message',"\"$now\",\"statc\",\"$cfg configs got updated, generating $cfe error events.\"");
sleep 1;												# With that sorting works in Chat...

my $upif = &db::Select('interfaces','','count(lastchg)',"lastchg > $from and iftype regexp '^(6|7|117)\$' and ifstat = 3");
my $dnif = &db::Select('interfaces','','count(lastchg)',"lastchg > $from and iftype regexp '^(6|7|117)\$' and ifstat = 1");
my $shif = &db::Select('interfaces','','count(lastchg)',"lastchg > $from and iftype regexp '^(6|7|117)\$' and ifstat = 0");
&db::Insert('chat','time,user,message',"\"$now\",\"stati\",\"$upif ethernet ports came up, $dnif went down and $shif got disabled.\"");
sleep 1;

my $liw = &db::Select('events','','count(id)',"time > $from and class = 'nedl'");
my $ifw = &db::Select('events','','count(id)',"time > $from and class = 'nedi'");
&db::Insert('chat','time,user,message',"\"$now\",\"state\",\"$liw link warnings and $ifw interface warnings occured.\"");
sleep 1;

my $ndev = &db::Select('devices','','count(firstdis)',"firstdis > $from");
my $nnod = &db::Select('nodes','','count(firstseen)',"firstseen > $from");

my $took = time - $now;
my $dbwarn = ($took > 60)?"(Those stats took ${took}s, you should consider optimizing the DB)":"";
&db::Insert('chat','time,user,message',"\"$now\",\"statd\",\"During the last $days days, $nnod new nodes and $ndev new devices were found. $dbwarn\"");


=head2 FUNCTION HELP_MESSAGE()

Display some help

B<Options> -

B<Globals> -

B<Returns> -

=cut
sub HELP_MESSAGE{
	print "\n";
	print "usage: stati.pl <Option(s)>\n\n";
	print "---------------------------------------------------------------------------\n";
	print "Options:\n";
	print "-v	verbose output\n";
	print "(C) 2013 Remo Rickli (and contributors)\n\n";
	exit;
}
