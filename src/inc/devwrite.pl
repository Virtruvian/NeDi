#!/usr/bin/perl -w

=pod

=head1 PROGRAM devwrite.pl

Send commands to devices via libcli-iopty

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

$VERSION = "1.4";

use strict;

use vars qw($p $now $dv $ip $po $us $pw $os $cf %opt);
$now = time;												# Expected in libmisc.pm
$opt{'d'} = '';

#$opt{'v'} = "1";											# Turn debugging on

#select(STDOUT);
#$| = 1;

die "7 arguments needed not " . @ARGV . "!\n" if @ARGV != 8;
($p, $dv, $ip, $po, $us, $pw, $os, $cf) = @ARGV;

require "$p/inc/libmisc.pm";
require "$p/inc/libmon.pm";
&misc::ReadConf();
require "$p/inc/libcli.pm";

my $status = &cli::Commands($dv, $ip, $po, $us, $pw, $os, $cf);
if($status !~ /^OK-/){
	print $status if $opt{'v'};
	exit 1;
}
