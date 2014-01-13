#!/usr/bin/perl

=pod

=head1 PROGRAM moni.pl

Monitoring daemon for polling uptime and checking connectivity of
services (not threaded for now, thus consider bigger pause, if you
monitor many targets). Targets will be skipped if it can't be
contacted (missing IP, doesn't exist in nodes or devices etc.) or if
 dependency is down.

=head1 SYNOPSIS

moni.pl [-D -v -t<level>]

=head2 DESCRIPTION

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

$VERSION = "1.2";

use strict;
use warnings;

use Getopt::Std;
use Net::SNMP qw(ticks_to_time);

use vars qw($now $warn $p %opt %dev %usr %mon %depdevs %depdown %depcount);

getopts('Dt:vV',\%opt) || &HELP_MESSAGE;

BEGIN{													# To avoid perl thinking var is used once
	$now = time;
	$p   = $0;
	$p   =~ s/(.*)\/(.*)/$1/;
	if($0 eq $p){$p = "."};
	require "$p/inc/libmisc.pm";									# Include required libraries
	&misc::ReadConf();
	require "$p/inc/libdb-" . lc($misc::backend) . ".pm" || die "Backend error ($misc::backend)!";
	require "$p/inc/libmon.pm";
	require "$p/inc/libsnmp.pm";
}

if ($opt{'t'}) {											# Creates incidents and bails
	my $msg = "moni.pl test message";
	&db::Insert('events','level,time,source,info,class',"\"$opt{t}\",\"$now\",\"Test\",\"$msg\",\"moni\"");
	&db::Insert('incidents','level,name,deps,start,end,user,time,grp,comment,device',"\"$opt{t}\",\"Test\",\"0\",\"$now\",\"0\",\"\",\"0\",\"1\",\"\",\"Testdev\"");
	&db::Insert('incidents','level,name,deps,start,end,user,time,grp,comment,device',"\"$opt{t}\",\"Test\",\"0\",\"$now\",\"".($now+$misc::pause)."\",\"\",\"0\",\"1\",\"\",\"Testdev\"");
	my $nsms  = &mon::SendSMS($msg);
	my $nmail = &mon::SendMail("moni.pl Test",$msg);
	die "1 Message, 2 Incidents, $nsms SMS and $nmail mail created\n";
}elsif ($opt{'D'}) {											# Daemonize or...
	&misc::Daemonize;
}else{
	select(STDOUT); $| = 1;										# ...disable buffering.
}

while(1) {# TODO support dependency aware threading for better scalability!

	%mon   = ();											# Remove potential "leftovers"
	$now   = time;

	my $ndev  = &db::ReadMon('dev');
	my $nnod  = &db::ReadMon('node');

	&misc::Prt("\nInitializing " . localtime($now) . " --------------\n");
	foreach my $d (keys %mon){
		&misc::Prt(sprintf ("DEPS:%-10.10s = %-8.8s ", $d, $mon{$d}{dy}) );
		if( $mon{$d}{dy} ne '-' ){								# Dependency configured?
			if( exists $mon{$mon{$d}{dy}} ){						# Does it exist?
				push @{$mon{$mon{$d}{dy}}{da}},$d;					# Add to parent dependendants
			}else{
				&db::Update('monitoring','depend="-"',"name =\"$d\"");
				&db::Insert('events','level,time,source,info,class,device',"\"50\",\"$now\",\"$d\",\"Non existant dependency $mon{$d}{dy} removed.\",\"moni\",\"$mon{$d}{dv}\"");
				&misc::Prt(" doesn't exist!");
			}
		}
		&misc::Prt("\n");
	}

	&misc::Prt("\nBuilding Tree ----------------------------------\n");
	foreach my $d (keys %mon){									# Recursively count all dependants
		&misc::Prt(sprintf ("TREE:%-12.12s", $d) );
		$mon{$d}{dc} = &CountDep($d,0);
		&misc::Prt(" Deps=$mon{$d}{dc}\n");
	}

	&misc::Prt("\nChecking ".($ndev+$nnod)." targets in total, Pause ${misc::pause}s ---------------\n");
	foreach my $d (sort { $mon{$b}{dc} <=> $mon{$a}{dc} } keys %mon){				# Check sorted by # of dependants to optimize checks
		my $latency = my $uptime = 0;
		&misc::Prt(sprintf ("\nTRGT:%-12.12s Deps=%-4.4s Chk=%-6.6s\n", $d, $mon{$d}{dc}, $mon{$d}{te}) );

		if($mon{$d}{ds} ne 'up'){								# Check if dep is up
			&misc::Prt("SKIP:Deps=$mon{$d}{ds}($mon{$d}{dc})\n");
		}elsif($mon{$d}{te}){
			if($mon{$d}{ip} eq '0.0.0.0'){							# Check is set and target exists
				&db::Insert('events','level,time,source,info,class,device',"'150',\"$now\",\"$d\",\"No IP found to check $mon{$d}{te}! Not in devices or nodes?\",\"moni\",\"$mon{$d}{dv}\"") if $mon{$d}{ds} eq 'up';
				&misc::Prt("SKIP:No IP found! Not in devices or nodes?\n");
			}else{
				if($mon{$d}{te} eq "ping"){
					$latency = &mon::PingService($mon{$d}{'ip'});
				}elsif($mon{$d}{te} =~ /^(http|https|telnet|ssh|mysql|cifs)$/){
					$latency = &mon::PingService($mon{$d}{'ip'},'tcp',$mon{$d}{te});
				}elsif($mon{$d}{te} eq "uptime"){
					($latency, $uptime) = &mon::GetUptime($mon{$d}{'ip'},$mon{$d}{'rv'},$mon{$d}{'rc'});
					if($latency and $mon{$d}{up} > $uptime and $mon{$d}{up} < 4294900000){	# Ignore alledged reboot, due to 32bit overflow
						my $wup = ticks_to_time($mon{$d}{up});
						&misc::Prt("BOOT:Rebooted was up for $wup\n");
						&db::Insert('events','level,time,source,info,class,device',"'150',\"$now\",\"$d\",\"Rebooted (was up for $wup)!\",\"moni\",\"$mon{$d}{dv}\"");
						&mon::SendMail("$d rebooted!","Was up for $wup") if ($mon{$d}{al} & 1);
						&mon::SendSMS("$d rebooted!") if ($mon{$d}{al} & 2);
					}
				}
				if($latency){
					my $ok = ++$mon{$d}{ok};
					my $latmax = ($latency > $mon{$d}{lm})?$latency:$mon{$d}{lm};		# Update max if higher than previous
					my $latavg = sprintf("%.0f",( ($ok - 1) * $mon{$d}{la} + $latency)/$ok);# This is where school stuff comes in handy (sprintf to round)

					&db::Update('monitoring',"status=\"0\",lastok=\"$now\",uptime=\"$uptime\",ok=\"$ok\",latency=\"$latency\",latmax=\"$latmax\",latavg=\"$latavg\"","name =\"$d\"");
					&misc::Prt("UP  :");
					&MarkDep($d,'up',0);							# Mark everytime to avoid errors when moni is restarted
					if($mon{$d}{st} >= $misc::chka){
						&misc::Prt("Recovered affects $mon{$d}{dc} targets\n");
						my $dt = sprintf("%.0f", $mon{$d}{st} * $misc::pause / 3600);
						&db::Insert('events','level,time,source,info,class,device',"'50',\"$now\",\"$d\",\"Recovered, Latency ${latency}ms, was down for ${dt}h\",\"moni\",\"$mon{$d}{dv}\"");
						&db::Update('incidents',"end=\"$now\"","name =\"$d\" AND end=0");
						&mon::SendMail("$d recovered","Latency ${latency}ms, was down for ${dt}h")  if ($mon{$d}{al} & 1);
						&mon::SendSMS("$d recovered, down for ${dt}h") if ($mon{$d}{al} & 2);
					}else{
						&misc::Prt("Last status=$mon{$d}{st}\n");
					}
					&db::Insert('events','level,time,source,info,class,device',"'150',\"$now\",\"$d\",\"Latency ${latency}ms exceeds threshold of ${misc::latw}ms\",\"moni\",\"$mon{$d}{dv}\"") if($latency > $misc::latw);
				}else{
					my $st = ++$mon{$d}{st};
					my $lo = ++$mon{$d}{lo};
					&db::Update('monitoring',"status=\"$st\",lost=\"$lo\"","name =\"$d\"");
					&misc::Prt("DOWN:");
					&MarkDep($d,'down',0);							# Mark everytime to avoid errors when moni is restarted
					if($mon{$d}{st} == $misc::chka){
						my $lvl = 200;
						my $downmsg = "Is down, not affecting any other targets";
						if($mon{$d}{dc}){
							$lvl = 250;
							$downmsg = "Is down, affects $mon{$d}{dc} targets!";
						}
						&misc::Prt("$downmsg\n");
						&db::Insert('events','level,time,source,info,class,device',"\"$lvl\",\"$now\",\"$d\",\"$downmsg\",\"moni\",\"$mon{$d}{dv}\"");
						&db::Insert('incidents','level,name,deps,start,end,user,time,grp,comment,device',"\"$lvl\",\"$d\",\"$mon{$d}{dc}\",\"$now\",\"0\",\"\",\"0\",\"1\",\"\",\"$mon{$d}{dv}\"");
						&mon::SendMail("$d went down", $downmsg) if ($mon{$d}{al} & 1);
						&mon::SendSMS("$d $downmsg") if ($mon{$d}{al} & 2);
					}elsif( !($mon{$d}{st} % 10) ){						# Notify every 10th time
						&db::Insert('events','level,time,source,info,class,device',"\"150\",\"$now\",\"$d\",\"Is unreachable for $mon{$d}{st} time".(($mon{$d}{st} > 1)?"s":"")."\",\"moni\",\"$mon{$d}{dv}\"");
					}

				}
				select(undef, undef, undef, 0.01);						# Wait a msec... (TODO until threading is implemented)
			}
		}else{
			&misc::Prt("SKIP:No check configured...\n");
		}
	}
	my $took = time - $now;
	&misc::Prt("===============================================================================\n");
	if ($misc::pause > $took){
		my $sl = $misc::pause - $took;
		&misc::Prt("Took ${took}s, sleeping ${sl}s\n\n");
		sleep($sl);
	}else{
		&db::Insert('events','level,time,source,info,class',"\"150\",\"$now\",\"NeDi\",\"Monitoring took ${took}s, increase pause!\",\"moni\"");
		&misc::Prt("No time to pause!\n\n");
	}
}

=head2 FUNCTION MarkDep()

Recursively mark dependendants

B<Options> target name, up/down, iteration

B<Globals> main::depdown

B<Returns> -

=cut
sub MarkDep {

	my ($d, $stat, $iter) = @_;

	if($iter < 100 and exists $mon{$d}{da} ){
		foreach my $d (@{$mon{$d}{da}}){
#			&misc::Prt(" $d");
			$mon{$d}{ds} = $stat;
			&MarkDep($d,$stat,$iter+1);
		}
#	}else{
#			&misc::Prt("(NoDeps)");
	}
}


=head2 FUNCTION CountDep()

Recursively count dependants. If you see perl warnings about deep
recursion, you should look for loops in your dependecy settings.

B<Options> target, iteration

B<Globals> -

B<Returns> # of dependants

=cut
sub CountDep {

	my ($d, $iter) = @_;

	if($iter < 100 and exists $mon{$d}{da} ){
		my $c = scalar @{$mon{$d}{da}};
		&misc::Prt(" I=$iter:$d+$c");
		foreach my $d (@{$mon{$d}{da}}){
			$c += &CountDep($d,$iter+1);
		}
		return $c;
	}else{
		return 0;
	}
}

=head2 FUNCTION HELP_MESSAGE()

Display some help

B<Options> -

B<Globals> -

B<Returns> -

=cut
sub HELP_MESSAGE{
	print "\n";
	print "usage: moni.pl <Option(s)>\n\n";
	print "---------------------------------------------------------------------------\n";
	print "Options:\n";
	print "-t <lev>	test with level (creates mail (on msgxxx match), SMS, message and incident)\n";
	print "-v	verbose output\n";
	print "-D	daemonize moni.pl\n\n";
	print "Moni $main::VERSION (C) 2001-2011 Remo Rickli (and contributors)\n\n";
	die;
}