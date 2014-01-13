#!/usr/bin/perl
=pod

=head1 PROGRAM syslog.pl

Simple syslog daemon, which stores events directly in DB. Only monitored
targets receive classification of their events. They are forwarded
via SMS/mail or ignored completely depending on the settings in
Monitoring-Setup.

=head1 SYNOPSIS

syslog.pl [-D -v -p<port>]

=head2 DESCRIPTION

Incoming messages are translated as follows:

Sev.  Level        Comment

0,1,2 Alert  (200) Triggers notification

3     Warning(150) -

4     Notice (100) -

x     Info    (50) Default for devices

x     Other   (10) Default for any other IP

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

$VERSION = "1.1";

use strict;
use warnings;

use IO::Socket;
use Getopt::Std;

use vars qw($p $warn $now %opt %mon %dip %usr);
$misc::pause = "";											# Avoid 'used only once:' warning without breaking evals (like LWP in libweb)

getopts('Dvp:',\%opt)  || &HELP_MESSAGE;

select(STDOUT); $| = 1;											# Disable buffering

$now = time;
$p   = $0;
$p   =~ s/(.*)\/(.*)/$1/;
if($0 eq $p){$p = "."};
require "$p/inc/libmisc.pm";										# Use the miscellaneous nedi library
&misc::ReadConf();
require "$p/inc/libmon.pm";										# Use the SNMP function library
require "$p/inc/libdb-" . lc($misc::backend) . ".pm" || die "Backend error ($misc::backend)!";

if ($opt{'D'}) {
	&misc::Daemonize;
}
my $maxlen	= 512;
my $port	= ($opt{'p'})?$opt{'p'}:514;
my $desup	= time;
&TargetUp();

my $sock = IO::Socket::INET->new(LocalPort => $port, Proto => 'udp') or die "socket: $@";
&misc::Prt("Awaiting syslog events on port $port\n");
while ($sock->recv(my $info, $maxlen)) {
	$now = time;
	my($client_port, $client_ip) = sockaddr_in($sock->peername);
	my $ip = inet_ntoa($client_ip);

# TODO put some aggregation here
	&Process($ip,$info);

	if($now - $misc::pause > $desup){								# update targets if older than a monitoring cycle, after processing current event
		$desup = $now;
		&TargetUp();
	}
}
die "recv: $!";


=head2 FUNCTION TargetUp()

Read Monitoring Targets

B<Options> -

B<Globals> -

B<Returns> -

=cut
sub TargetUp {

	undef (%mon);
	undef (%dip);

	&db::ReadMon("dev");
	&db::ReadMon("node");
	foreach my $d (keys %mon){
		$dip{$mon{$d}{'ip'}} = $d;
	}
}


=head2 FUNCTION Process()

Process Message

B<Options> source IP, message

B<Globals> -

B<Returns> -

=cut
sub Process {

	my ($src,$pri) = @_;
	my $info  = $pri;
	my $level = 10;

	$info =~ s/<(\d+)>(.*)/$2/;
	$info =~ s/[^\w\t\/\Q(){}[]!@#$%^&*-+=',.:<>? \E]//g;
	$info = substr($info,0,255);

	if(exists $dip{$src}){
		$src = $dip{$src};
		$pri =~ s/<(\d+)>.*/$1/;
		my $sev = ($pri & 7);
		if   ($sev == 4)	{$level = 100}
		elsif($sev == 3)	{$level = 150}
		elsif($sev =~ /[012]/)	{$level = 200}
		else			{$level = 50}

		if($mon{$src}{ed} =~ /^\d+$/ and $mon{$src}{ed} > $level){					# skip if eventdel is number & higher than level
			&misc::Prt("DROP:$src ($_[0])\teventdel $mon{$src}{ed} higher than $level\n");
		}elsif($mon{$src}{ed} !~ /^\d+$|^$/ and $info =~ /$mon{$src}{ed}/){				# skip if eventdel matches info
			&misc::Prt("DROP:$src ($_[0])\tmatches eventdel /$mon{$src}{ed}/\n");
		}else{
			&misc::Prt("PROC:$src ($_[0])\tL:$level ($pri)\nMESG:$info\n");
			&db::Insert('events','level,time,source,info,class,device',"\"$level\",\"$now\",\"$src\",\"$info\",\"$mon{$src}{cl}\",\"$mon{$src}{dv}\"");
			if($mon{$src}{ef} ne "" and $info =~ /$mon{$src}{ef}/){
				&mon::SendMail("$src syslog event","$info") if ($mon{$src}{al} & 1);
				&mon::SendSMS("$src: $info") if ($mon{$src}{al} & 2);
			}
		}
	}else{
		&misc::Prt("PROC:$src ($_[0])\tL:$level ($pri)\nMESG:$info\n");
		&db::Insert('events','level,time,source,info,class',"\"$level\",\"$now\",\"$src\",\"$info\",\"-\"");
	}
}


=head2 FUNCTION HELP_MESSAGE()

Display some help

B<Options> -

B<Globals> -

B<Returns> -

=cut
sub HELP_MESSAGE {
	print "\n";
	print "usage: syslog.pl <Option(s)>\n\n";
	print "---------------------------------------------------------------------------\n";
	print "Options:\n";
	print "-D		daemonize moni.pl\n";
	print "-v		verbose output\n";
	print "-p x		listen on port x (default 514)\n\n";
	print "syslog $main::VERSION (C) 2001-2011 Remo Rickli (and contributors)\n\n";
	die;
}
