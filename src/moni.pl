#!/usr/bin/perl

=pod

=head1 PROGRAM moni.pl

2 Stage monitoring daemon for network infrastructure:

1. All SNMP targets are queried non-blocking for their uptime.
2. Other services like DNS and NTP are tested (single-thread)

Up to 2 dependencies can be set per target. They should be network devices
(set for testing uptime) or won't be used in calculation

Use -c 200 or 250 to simulate an alert for the 1st target. If set, it'll send
a testmail and SMS as well. Make sure you have at least one user in the monitoring
group with mail address or phone number set.

=head1 SYNOPSIS

moni.pl [-D -v -d -t -c<level>]

=head2 DESCRIPTION

=head2 LICENSE

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

=head2 AUTHORS

Remo Rickli & NeDi Community

Visit http://www.nedi.ch for more information.

=cut

use strict;
use warnings;
no warnings qw(once);

use Getopt::Std;
use Net::SNMP qw(snmp_dispatcher ticks_to_time);
use Net::Ping;

use vars qw($dnsok $ntpok $now $warn $p $mq $upst $useq %opt %dev %usr %mon %depdevs %depdown %depcount %msgq);

#my %response = get_ntp_response('localhost');
#use Data::Dumper;
#&misc::Prt(' '.Dumper(%response)."\n");

getopts('c:Dd:ts:v',\%opt) || &HELP_MESSAGE;
if(!defined $opt{'d'}){$opt{'d'} = ''}									# Avoid warnings if unused

$now = time;
$p   = $0;
$p   =~ s/(.*)\/(.*)/$1/;
if($0 eq $p){$p = "."};
require "$p/inc/libmisc.pm";										# Include required libraries
require "$p/inc/libsnmp.pm";
require "$p/inc/libmon.pm";
require "$p/inc/libdb.pm";										# Use the DB function library

&misc::ReadConf();

$dnsok = 0;
eval 'use Net::DNS::Resolver;';
if ($@){
	&misc::Prt("PERL:Net::DNS::Resolver not available\n");
}else{
	$dnsok = 1;
	&misc::Prt("PERL:Net::DNS::Resolver loaded\n");
}

$ntpok = 0;
eval 'use Net::NTP;';
if ($@){
	&misc::Prt("PERL:Net::NTP not available\n");
}else{
	$ntpok = 1;
	&misc::Prt("PERL:Net::NTP loaded\n");
}

$misc::lwpok = 0;
require "$p/inc/libweb.pm";

if ($opt{'c'}){												# Creates incidents and bails
	use Data::Dumper;
	&db::Connect($misc::dbname,$misc::dbhost,$misc::dbuser,$misc::dbpass);
	my $ntgt = &mon::InitMon();
	if( $ntgt ){
		my @mky = keys %mon;
		my $t   = pop @mky;
		my $lvl = ($opt{c} == 250)?250:200;
		&db::Insert('incidents','level,name,deps,startinc,endinc,usrname,time,grp,comment,device',"$lvl,'$t',0,$now,0,'',0,1,'','$mon{$t}{dv}'");
		&db::Insert('incidents','level,name,deps,startinc,endinc,usrname,time,grp,comment,device',"$lvl,'$t',0,".($now+1).",".($now+$misc::pause).",'',0,1,'','$mon{$t}{dv}'");
		my $mq = &mon::Event(7,$lvl,'moni',$t,$mon{$t}{dv},"Test alert level $lvl using 1st target $t","SMS test");
		my $af = &mon::AlertFlush("Monitoring Test",$mq);
		&db::Commit();
	}else{
		&misc::Prt("ERR :Need at least one monitored target!\n");
	}
	&db::Disconnect();
	exit;
}elsif ($opt{'D'}) {											# Daemonize or...
	&misc::Daemonize;
}else{
	select(STDOUT); $| = 1;										# ...disable buffering.
}

while(1){
	$now = time;
	&db::Connect($misc::dbname,$misc::dbhost,$misc::dbuser,$misc::dbpass);
	my $ntgt = &mon::InitMon();
	&misc::Prt("\nPreparing SNMP Targets " . localtime($now) . " --------------\n");
	$mq  = 0;

	foreach my $tgt (keys %mon){
		&AddDependant($tgt,'1');
		&AddDependant($tgt,'2');
		if( $mon{$tgt}{te} eq 'uptime' ){
			my ($session, $err) = &snmp::Connect($mon{$tgt}{ip}, $mon{$tgt}{rv}, $mon{$tgt}{rc}, $misc::timeout * 2, 1472, 1, 1);
			if (!defined($session)) {
				printf("ERR : %s.\n", $err);
				exit 1;
			}
			$session->get_request(	-varbindlist => ['1.3.6.1.2.1.1.3.0'],
						-callback    => [\&ProcessUptime, $tgt]
			);
		}
	}
	&misc::Prt("\nNon-Blocking SNMP Targets ---------------------------------------------------\n");
	$useq = 0;
	$upst = Time::HiRes::time;									# Start time to measure delay since we can't reach into dispatcher!
	snmp_dispatcher();										# Fire away 

	&misc::Prt("\nProcess and Test other Services ---------------------------------------------\n");
	
	foreach my $tgt (keys %mon){
		if( $mon{$tgt}{'dc'} and $mon{$tgt}{'dc'} == $mon{$tgt}{'dd'} ){			# Target has dependencies and all are down!
			&misc::Prt("DEPS:$tgt\tignored due to $mon{$tgt}{'dd'}/$mon{$tgt}{'dc'} deps down\n");
			$mon{$tgt}{st}++;
			$mon{$tgt}{lo}++;
			&db::Update('monitoring',"status=$mon{$tgt}{st},lost=$mon{$tgt}{lo}","name ='$tgt'");
		}else{
			TestTgt($tgt);
		}
	}

	&mon::AlertFlush("Monitoring Alert",$mq);
	&misc::Prt("===============================================================================\n");
	my $took = time - $now;
	if ($misc::pause > $took){
		my $sl = $misc::pause - $took;
		&misc::Prt("Tests on $ntgt targets took ${took}s, sleeping ${sl}s\n\n");
		&db::Commit();#TODO check commit on Update()
		&db::Disconnect();									# Disconnect DB before sleep, TODO more efficient to stay connected?
		my $slept = sleep($sl);
		&misc::Prt("Paused ${slept}s, why am I doing this?\n\n") if $slept > $sl;		# VM seemed to have slept longer, TOOD remove if proven wrong...
	}else{
		&db::Insert('events','level,time,source,info,class',"150,$now,'NeDi','Monitoring took ${took}s, increase pause!','moni'");
		&misc::Prt("Tests on $ntgt targets took ${took}s, no time to pause!\n\n");
		&db::Commit();
		&db::Disconnect();
	}
}

=head2 FUNCTION TestTgt()

Perform actual test on target

B<Options> target name

B<Globals> -

B<Returns> -

=cut
sub TestTgt{

	my ($tgt) = @_;
	my $latency = 0;

	if($mon{$tgt}{te} eq 'uptime'){
		$latency = $mon{$tgt}{nla};								# Using result from non-blocking test
	}elsif($mon{$tgt}{te} eq "ping"){
		$latency = &mon::PingService($mon{$tgt}{'ip'});
	}elsif($mon{$tgt}{te} =~ /^dns$/ and $mon{$tgt}{to}){
			if($main::dnsok){
				my $start = Time::HiRes::time;
				my $res = Net::DNS::Resolver->new(nameservers => [qw($mon{$tgt}{ip})]);
				my $query = $res->search($mon{$tgt}{to});
				foreach my $rr ($query->answer) {
					next unless $rr->type eq "A";
					my $rip = $rr->address;
					if( $rip =~ /$mon{$tgt}{tr}/){
						$latency = int(1000 * (Time::HiRes::time - $start) );
						&misc::Prt("DNS :Latency=${latency}ms Reply to $mon{$tgt}{to} is $rip and matches /$mon{$tgt}{tr}/\n");
					}else{
						$latency = -1;
						&misc::Prt("DNS :Reply to $mon{$tgt}{to} is $rip and does not match /$mon{$tgt}{tr}/\n");
					}
				}
				}else{
					&misc::Prt("ERR :Net::DNS::Resolver not available!\n");
					$latency = -1;
				}
	}elsif($mon{$tgt}{te} =~ /^ntp$/){
			if($main::ntpok){
				my $start = Time::HiRes::time;
				my %res = ();
				eval{
					%res = &main::get_ntp_response( $mon{$tgt}{ip} );
				};
				if( $@ ){
					$latency = -1;
					&misc::Prt("NTP :$@\n");
				}elsif( $res{$mon{$tgt}{to}} =~ /$mon{$tgt}{tr}/){
					$latency = int(1000 * (Time::HiRes::time - $start) );
					&misc::Prt("NTP :Latency=${latency}ms Reply to $mon{$tgt}{to} is $res{$mon{$tgt}{to}} and matches /$mon{$tgt}{tr}/\n");
				}else{
					$latency = -1;
					&misc::Prt("NTP :Reply to $mon{$tgt}{to} is $res{$mon{$tgt}{to}} and does not match /$mon{$tgt}{tr}/\n");
				}
			}else{
				&misc::Prt("ERR :Net::NTP not available!\n");
				$latency = -1;
			}
	}elsif($mon{$tgt}{te} =~ /^(http|https)$/ and $mon{$tgt}{to}){
			if($web::lwpok){
				my $start = Time::HiRes::time;
				my $res = &web::GetHTTP($mon{$tgt}{ip},$mon{$tgt}{te},$mon{$tgt}{to});
				if($res =~ /$mon{$tgt}{tr}/){
					$latency = int(1000 * (Time::HiRes::time - $start) );
					&misc::Prt("WEB :Latency=${latency}ms Reply (${latency}ms) to $mon{$tgt}{to} is $res and matches /$mon{$tgt}{tr}/\n");
				}else{
					$latency = -1;
					&misc::Prt("WEB :Reply to $mon{$tgt}{to} does not match $mon{$tgt}{tr}\n");
				}
			}else{
				&misc::Prt("ERR :LWP not available!\n");
				$latency = -1;
			}
	}elsif($mon{$tgt}{te} =~ /^(http|https|telnet|ssh|mysql|cifs)$/){
		$latency = &mon::PingService($mon{$tgt}{'ip'},'tcp',$mon{$tgt}{te});
	}else{
		&misc::Prt("SKIP:$tgt has no test set\n");
		return;
	}

	if($latency != -1){
		my $ok = ++$mon{$tgt}{ok};
		my $nup = (exists $mon{$tgt}{nup})?$mon{$tgt}{nup}:0;
		my $latmax = ($latency > $mon{$tgt}{lm})?$latency:$mon{$tgt}{lm};			# Update max if higher than previous
		my $latavg = sprintf("%.0f",( ($ok - 1) * $mon{$tgt}{la} + $latency)/$ok);		# This is where school stuff comes in handy (sprintf to round)
		&db::Update('monitoring',"status=0,lastok=$now,ok=$ok,uptime=$nup,latency=$latency,latmax=$latmax,latavg=$latavg","name ='$tgt'");
		&misc::Prt(sprintf ("UP  :%-15.15s lost %s before, %s/%s deps are down\n", $tgt, $mon{$tgt}{st}, $mon{$tgt}{'dd'},$mon{$tgt}{'dc'}) );
		if($mon{$tgt}{st} >= $mon{$tgt}{nr}){
			&db::Update('incidents',"endinc=$now","name ='$tgt' AND endinc=0");
			my $msg = "recovered, ".sprintf( "was down for %.1fh", ($now - $mon{$tgt}{lk})/3600 );
			my $aff = ( exists $mon{$tgt}{da} )?", affects ".scalar @{$mon{$tgt}{da}}." directly attached targets!":'';
			$mq += &mon::Event($mon{$tgt}{al},50,'moni',$tgt,$mon{$tgt}{dv},"$msg$aff",$msg);
		}
		if( $mon{$tgt}{nup} ){
			if( $mon{$tgt}{up} > 4294966396 ){						# Ignore uptime, 15m before 32bit counter overflow
				$mq += &mon::Event(1,100,'moni',$tgt,$mon{$tgt}{dv},'Was up for '.ticks_to_time($mon{$tgt}{up}).', ignoring uptime due to potential counter overflow');
			}elsif( $mon{$tgt}{up} > $mon{$tgt}{nup} ){
				$mq += &mon::Event($mon{$tgt}{al},150,'moni',$tgt,$mon{$tgt}{dv},'Rebooted '.ticks_to_time($mon{$tgt}{nup}).' ago! Was up for '.ticks_to_time($mon{$tgt}{up}),'Rebooted!');
			}
		}
		&db::Insert('events','level,time,source,info,class,device',"'150',$now,'$tgt','Latency ${latency}ms exceeds threshold of $mon{$tgt}{lw}ms','moni','$mon{$tgt}{dv}'") if $latency > $mon{$tgt}{lw} and $mon{$tgt}{al};
	}else{
		my $lvl = 200;
		$mon{$tgt}{st}++;
		$mon{$tgt}{lo}++;
		&db::Update('monitoring',"status=$mon{$tgt}{st},lost=$mon{$tgt}{lo}","name ='$tgt'");
		my $msg = "$mon{$tgt}{te} test failed $mon{$tgt}{st} times";
		if( exists $mon{$tgt}{da} ){
			$lvl = 250;
			$msg .= ", affects ".scalar @{$mon{$tgt}{da}}." directly attached targets";
		}
		if($mon{$tgt}{st} == $mon{$tgt}{nr}){
			&db::Insert('incidents','level,name,deps,startinc,endinc,usrname,time,grp,comment,device',"$lvl,'$tgt',$mon{$tgt}{dc},$now,0,'',0,1,'','$mon{$tgt}{dv}'");
			$mq += &mon::Event($mon{$tgt}{al},$lvl,'moni',$tgt,$mon{$tgt}{dv},"$msg, $mon{$tgt}{'dd'}/$mon{$tgt}{'dc'} deps are down",$msg);
		}elsif( !($mon{$tgt}{st} % 100) and $mon{$tgt}{al} & 128){		# Keep nagging every 100th time, if enabled
			$msg .= " for $mon{$tgt}{st} times";
			$mq += &mon::Event($mon{$tgt}{al},$lvl,'moni',$tgt,$mon{$tgt}{dv},$msg);
		}else{
			&misc::Prt("DOWN:$tgt\t$msg\n");
		}
	}
}

=head2 FUNCTION ProcessUptime()

Callback function for non blocking SNMP uptime and calculating delay

B<Options> session, device name

B<Globals> main::mon

B<Returns> -

=cut
sub ProcessUptime{

	my ($session, $tgt) = @_;
	my $err = $session->error;

	if( defined($session->var_bind_list) ){
		$useq++;
		$mon{$tgt}{nla} = int(1000 * (Time::HiRes::time - $upst) - int($useq*1.0) );		# Accuracy :-( trying to compensate with sequence#
		$mon{$tgt}{nup} = $session->var_bind_list->{'1.3.6.1.2.1.1.3.0'};
		&misc::Prt(sprintf ("SNMP:%-15.15s OK uptime %20.20s  latency=%sms\n", $tgt, ticks_to_time($mon{$tgt}{nup}), $mon{$tgt}{nla}) );
	}else{
		&misc::Prt(sprintf ("SNMP:%-15.15s DOWN %s\n", $tgt, $err) );
		&MarkDep($tgt);
		$mon{$tgt}{nla} = -1;
		$mon{$tgt}{nup} = 0;
	}
}

=head2 FUNCTION MarkDep()

Mark dependendants dependencies down

B<Options> target name

B<Globals> main::mon

B<Returns> -

=cut
sub MarkDep{

	my ($tgt) = @_;

	if( exists $mon{$tgt}{da} ){
		foreach my $d ( @{$mon{$tgt}{da}} ){
			$mon{$d}{dd}++;
		}
	}
}

=head2 FUNCTION AddDependant()

Check if target's dependency exists and add target to dependency's dependant list
Remove dependency, if it doesn't exist

B<Options> target, dependency

B<Globals> main::mon

B<Returns> -

=cut
sub AddDependant{

	my ($tgt,$dep) = @_;

	&misc::Prt(sprintf ("DEP%s:%-15.15s", $dep, $tgt) );
	if( $mon{$tgt}{"d$dep"} eq '' ){								# Dependency configured?
		&misc::Prt( " -\n" );
	}else{
	&misc::Prt(sprintf (" %-15.15s", $mon{$tgt}{"d$dep"}) );
		if( exists $mon{$mon{$tgt}{"d$dep"}} ){							# Does it exist?
			push @{$mon{$mon{$tgt}{"d$dep"}}{'da'}},$tgt;					# Add target to dependency's dependendant list
			$mon{$tgt}{'dc'}++;
			&misc::Prt(" and exists in monitoring, OK\n");
		}else{
			&db::Update('monitoring',"depend$dep=''","name ='$tgt'");
			&db::Insert('events','level,time,source,info,class,device',"50,$now,'$tgt','Non existant dependency $mon{$tgt}{'d'.$dep} removed','moni','$mon{$tgt}{dv}'");
			&misc::Prt(" doesn't exist, removed!\n");
		}
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
	print "-c lvl	create 1 event, 2 incidents, a mail and SMS for 1st target\n";
	print "-t	testing only, not writing to DB\n";
	print "-v		verbose output\n";
	print "-d		debug output\n";
	print "-D		daemonize moni.pl\n\n";
	print "(C) 2001-2014 Remo Rickli (and contributors)\n\n";
	exit;
}
