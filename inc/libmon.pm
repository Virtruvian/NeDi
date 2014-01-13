=pod

=head1 LIBRARY
libmon.pm

Functions for monitoring

=head2 AUTHORS

Remo Rickli & NeDi Community

=cut

package mon;
use warnings;

use Time::HiRes;

=head2 FUNCTION InitMon()

Read monitoring targets and users

B<Options> -

B<Globals> -

B<Returns> -

=cut
sub InitMon{

	%srcna = ();
	%mon   = ();
	%usr   = ();
	my $nt = 0;

	$nt  = &db::ReadMon("dev");
	$nt += &db::ReadMon("node");

	&db::ReadUser("groups & 8 AND (phone != \"\" OR email != \"\")");
}


=head2 FUNCTION GetUptime()

Gets uptime via SNMP

B<Options> IP address, SNMP version and community

B<Globals> -

B<Returns> array with (latency, uptime) or (0,0) upon timeout

=cut
sub GetUptime{

	my ($ip, $ver, $comm) = @_;

	my $r;

	my $uptimeO = '1.3.6.1.2.1.1.3.0';

	my ($session, $err) = &snmp::Connect($ip,$ver,$comm);
	my $start = Time::HiRes::time;
	if(defined $session){
		$r   = $session->get_request($uptimeO);
		$err = $session->error;
		$session->close;
	}

	if($err){
		&misc::Prt("ERR :$err\n");
		return 0,0;
	}else{
		my $lat = int(1000 * (Time::HiRes::time - $start) );
		&misc::Prt("SNMP:Latency=${lat}ms Uptime=$r->{$uptimeO}s\n");
		return $lat, $r->{$uptimeO};
	}
}


=head2 FUNCTION PingService()

Pings a tcp service.

B<Options> IP address, protocoll and name of service

B<Globals> -

B<Returns> latency or nothing upon timeout

=cut
sub PingService{

	use Net::Ping;

	my ($ip, $proto, $srv, $tout) = @_;

	$tout = ($tout)?$tout:$misc::timeout;
	my $p = Net::Ping->new($proto);
	$p->hires();
	&misc::Prt("TEST:");
	if ($proto and $proto ne 'icmp'){
		$srv = "microsoft-ds" if $srv eq "cifs";
		$p->tcp_service_check(1);
		$p->{port_num} = getservbyname($srv, $proto);
		&misc::Prt("$ip proto=$proto srv=$srv ");
	}else{
		&misc::Prt("$ip tcp echo ");
	}
	(my $ret, my $latency, my $rip) = $p->ping($ip, $tout);
	$p->close();

	if($ret){
		my $lat = int($latency * 1000 + 1);
		&misc::Prt("Latency=${lat}ms\n");
		return $lat;
	}else{
		&misc::Prt("fail!\n");
		return 0;
	}
}

=head2 FUNCTION AlertQ()

Queues alerts for delivery within a single SMS or Mail

B<Options> mailmsg, smsmsg, service, device

B<Globals> main::usr

B<Returns> # of mails queued
=cut
sub AlertQ{
	
	my ($mail, $sms, $srv, $dev) = @_;

	return (0,0) if !$srv;										# No service no queue...

	my $nm = 0;
	foreach my $u ( keys %main::usr ){

		my $viewdev = ($main::usr{$u}{vd})?&db::Select('devices','device',"device=\"$dev\" AND $main::usr{$u}{vd}"):$dev;
		if(defined $viewdev and $viewdev eq $dev){						# Send mail only to those who can see the associated device

			if($main::usr{$u}{ml} and $mail and $srv & 1){
				$main::usr{$u}{mail} .= $mail;
				&misc::Prt("MLQ :$u+ $mail");
				$nm++;
			}

			if($main::usr{$u}{ph} and $sms and $srv & 2){
				$main::usr{$u}{sms} .= $sms;
				&misc::Prt("SMSQ:$u+ $sms");
			}

		}

	}

	return $nm;
}

=head2 FUNCTION AlertFlush()

Sends Mails and SMS. If there are no queued mails, the SMTP connection won't be established. Look at commented lines to adjust SMS part...

B<Options> subject for mails, #mails queued

B<Globals> -

B<Returns> -
=cut

sub AlertFlush{

	my ($sub,$mq) = @_;

	use Net::SMTP;

	my $err = 0;
	my $nm  = 0;
	my $ns  = 0;
	
	if($mq){
		my $smtp = Net::SMTP->new($misc::smtpserver, Timeout => $misc::timeout) || ($err = 1);
		if($err){
			&misc::Prt("ERR :Connecting to SMTP server $misc::smtpserver\n");
		}else{
			foreach my $u ( keys %main::usr ){
				if($main::usr{$u}{mail}){
					&misc::Prt("MAIL:$u/$main::usr{$u}{ml}\n");
					$smtp->mail($misc::mailfrom) || &ErrSMTP($smtp,"From");
					$smtp->to($main::usr{$u}{ml}) || &ErrSMTP($smtp,"To");
					$smtp->data();
					$smtp->datasend("To: $main::usr{$u}{ml}\n");
					$smtp->datasend("From: $misc::mailfrom\n");
					$smtp->datasend("Subject: $sub\n");
					#$smtp->datasend("MIME-Version: 1.0\n"); 			# Some need it, Exchange doesn't?
					$smtp->datasend("\n");
					$smtp->datasend("Hello $u\n");
					$smtp->datasend("\n");
					foreach my $l (split /\\n/,$main::usr{$u}{mail}){
						$smtp->datasend("$l\n");
					}
					if($misc::mailfoot){
						foreach my $l (split /\\n/,$misc::mailfoot){
							$smtp->datasend("$l\n");
						}
					}
					$smtp->dataend() || &ErrSMTP($smtp,"End");

					$main::usr{$u}{mail} = "";
					$nm++;
				}
			}
			$smtp->quit;
		}
	}

	foreach my $u ( keys %main::usr ){

		if($main::usr{$u}{sms}){
			&misc::Prt("SMS :$u/$main::usr{$u}{ph}\n");
			#1. Spooling to smsd:
			$ns++ if open(SMS, ">/var/spool/sms/outgoing/$u");			# User is filename to avoid flooding
			print SMS "To:$main::usr{$u}{ph}\n\n$main::usr{$u}{sms}\n";
			close(SMS);

			#2. Calling gammu server:
			#$ns++ if !system "gammu-smsd-inject TEXT $main::usr{$u}{ph} -text \"$main::usr{$u}{sms}\" >/dev/null";

			#3.My lab setup:
			#$ns++ if !system "/usr/local/bin/svdrpsend.pl -d argus MESG \"$main::usr{$u}{sms}\" >/dev/null";

			$main::usr{$u}{sms} = "";
		}
	}

	&misc::Prt("ALRT:$nm mails from $mq events and $ns SMS sent\n");
	
	return $nm;
}

=head2 FUNCTION ErrSMTP()

Handle SMTP errors

B<Options> SMTP code, Step of delivery

B<Globals> -

B<Returns> -
=cut

sub ErrSMTP{

	my ($smtp,$step) = @_;

	my $m = &misc::Strip(($smtp->message)[-1]);						# Avoid uninit with Strip()
	my $c = $smtp->code;
	chomp $m;
	&misc::Prt("ERR :$c, $m\n");
}

1;
