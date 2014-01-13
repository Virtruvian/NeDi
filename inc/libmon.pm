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


=head2 FUNCTION GetUptime()

Gets uptime via SNMP

B<Options> IP address, SNMP version and community

B<Globals> -

B<Returns> array with (latency, uptime) or (0,0) upon timeout

=cut
sub GetUptime {

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
sub PingService {

	use Net::Ping;

	my ($ip, $proto, $srv) = @_;

	my $p = Net::Ping->new($proto);
	$p->hires();
	&misc::Prt("TEST:");
	if ($proto){
		$srv = "microsoft-ds" if $srv eq "cifs";
		$p->tcp_service_check(1);
		$p->{port_num} = getservbyname($srv, $proto);
		&misc::Prt("$ip proto=$proto srv=$srv ");
	}else{
		&misc::Prt("$ip tcp echo ");
	}
	(my $ret, my $latency, my $rip) = $p->ping($ip, $misc::timeout);
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


=head2 FUNCTION SendSMS()

Sends SMS (must use some non-blocking service like smsd)

B<Options> message

B<Globals> -

B<Returns> number of SMS successfully sent
=cut
sub SendSMS {

	undef (%main::usr);
	&db::ReadUser("groups & 8 AND phone != \"\"");

	&misc::Prt("SMS :Sendng \"$_[0]\" to:");

	my $m = 0;
	foreach my $u ( keys %main::usr ){
		$main::usr{$u}{ph} =~ s/\D//g;
		&misc::Prt(" $u/$main::usr{$u}{ph}");

		#1. Spooling to smsd:
		$m++ if open(SMS, ">/var/spool/sms/outgoing/$u");					# user is filename to avoid flooding
		print SMS "To:$main::usr{$u}{ph}\n\n$_[0]\n";
		close(SMS);

		#2. Calling gammu server:
		#$m++ if !system "gammu-smsd-inject TEXT $main::usr{$u}{ph} -text \"$_[0]\" >/dev/null";

		#3.My lab setup:
		#$m++ if !system "/usr/local/bin/svdrpsend.pl -d argus MESG \"$_[0]\" >/dev/null";
	}
	&misc::Prt(" done\n");

	return $m;
}


=head2 FUNCTION SendMail()

Sends Mails to configured smtp server.

B<Options> subject, message

B<Globals> -

B<Returns> number of mails successfully sent
=cut
sub SendMail {

	use Net::SMTP;

	undef (%main::usr);
	&db::ReadUser("groups & 8 AND email != \"\"");

	my $m   = 0;
	my $err = 0;

	my $smtp = Net::SMTP->new($misc::smtpserver, Timeout => $misc::timeout) || ($err = 1);
	if($err){
		&misc::Prt("ERR :Connecting to SMTP server $misc::smtpserver\n");
		return $m;
	}
	&misc::Prt("MAIL:Sending \"$_[0]\" from $misc::mailfrom via ".$smtp->domain."\n");
	foreach my $u ( keys %main::usr ){
		&misc::Prt("MAIL:Sending to $u <$main::usr{$u}{ml}>\n");
		$smtp->mail($misc::mailfrom);
		$smtp->to($main::usr{$u}{ml}) || return "failed to send to $main::usr{$u}{ml}!";
		$smtp->data();
		$smtp->datasend("To: $main::usr{$u}{ml}\n");
		$smtp->datasend("From: $misc::mailfrom\n");
		$smtp->datasend("Subject: $_[0]\n");
		$smtp->datasend("MIME-Version: 1.0\n");
		$smtp->datasend("$_[1]\n\n");
		&misc::Prt("MBOD:$_[1]\n");
		if($misc::mailfoot){
			foreach my $l (split /\\n/,$misc::mailfoot){
				&misc::Prt("MFOT:$l\n");
				$smtp->datasend("$l\n");
			}
		}
		$smtp->dataend();
		$m++;
	}
	$smtp->quit;
	&misc::Prt("MAIL:$m Mails sent\n");

	return $m;
}

1;
