=pod

=head1 LIBRARY
libweb.pm

LWP based Functions in order to fetch info from supported web only devices

=head2 AUTHORS

Remo Rickli & NeDi Community

=cut

package web;
use warnings;

use vars qw($lwpok);

eval 'use LWP::UserAgent;';
if ($@){
	&misc::Prt("LWP :Not available\n");
}else{
	$lwpok = 1;
	&misc::Prt("LWP :Loaded\n");
}


=head2 FUNCTION CiscoPhone()

Fetch info through webinterface of Cisco phones (tx Kyle Kniffin)

B<Options> device name

B<Globals> main::dev

B<Returns> 0 on success, 1 on failure

=cut
sub CiscoPhone{

	my ($na) = @_;

	my $ua = LWP::UserAgent->new;
	$ua->timeout($misc::timeout);

	my $response = $ua->get("http://$main::dev{$na}{ip}");
	if ($response->is_success) {
		my $devhtml = $response->content;
		if ( $devhtml =~ m/<b>([0-9]{2,8})<\/b>/i ) {						# Find Extension on Cisco 79-20,40,60,11,10,06
			$main::dev{$na}{co} = $1;
		}elsif( $devhtml =~ m/<td>([0-9]{2,8})\W+<tr>/i ){					# Find Extension on Cisco 7912
			$main::dev{$na}{co} = $1;
		}
		if ( $devhtml =~ m/<b>(FCH\w+|INM\w+)<\/b>/i ) {					# Find Serial  on Cisco 79-20,40,60,11,10,06
			$main::dev{$na}{sn} = $1;
		}elsif( $devhtml =~ m/<td>(FCH\w+|INM\w+)\W+<tr>/i ){					# Find Serial  on Cisco 7912
			$main::dev{$na}{sn} = $1;
		}elsif( $devhtml =~ m/: ([0-9]{2,8})<\/name>/i ){					# Find Extension on Cisco 7937, tx Kstadler
			$main::dev{$_[0]}{co} = $1;
		}
		&misc::Prt("LWP :Contact:$main::dev{$na}{co} SN:$main::dev{$na}{sn}\n");
	} else {
		&misc::Prt("LWP :Error " . $response->status_line ."\n");
		return 1;
	}
}


=head2 FUNCTION CiscoAta()

Fetch info through webinterface of Cisco ATA boxes (tx Kyle Kniffin)

B<Options> device name

B<Globals> main::dev

B<Returns> 0 on success, 1 on failure

=cut
sub CiscoAta{

	my ($na) = @_;

	my $ua = LWP::UserAgent->new;
	$ua->timeout($misc::timeout);

	my $response = $ua->get("http://$main::dev{$na}{ip}/DeviceInfo");
	if ($response->is_success) {
		my $devhtml = $response->content;
		if ( $devhtml =~ m/<td>([0-9]{2,8})<\/td>/i ) {						# Find Extension on Cisco ATA 186
			$main::dev{$na}{co} = $1;
		}
		if ( $devhtml =~ m/<td>(FCH\w+|INM\w+)<\/td>/i ) {					# Find Serial  on Cisco ATA 186
			$main::dev{$na}{sn} = $1;
		}
		&misc::Prt("LWP :Contact:$main::dev{$na}{co} SN:$main::dev{$na}{sn}");
	} else {
		&misc::Prt("LWP :Error " . $response->status_line ."\n");
		return 1;
	}
 }

=head2 FUNCTION AastraPhone()

Fetch info through webinterface of Aastra phones using default
credentials (experimental)

B<Options> device name

B<Globals> main::dev

B<Returns> 0 on success, 1 on failure

=cut
sub AastraPhone{

	my ($na) = @_;

	my $ua = LWP::UserAgent->new;
	$ua->timeout($misc::timeout);
	$req = HTTP::Request->new(GET => "http://$main::dev{$na}{ip}/");
	$req->authorization_basic('admin', '22222');
	my $res = $ua->request($req);

	if ($res->is_success) {
		if ( $res->decoded_content =~ m/<td>Firmware Version<\/td><td>([0-9\.]+)<\/td>/i ) {	# Find FW
			$main::dev{$na}{bi} = $1;
		}
		if( $res->decoded_content =~ m/<td>Platform<\/td><td>([\w\s]+)<\/td>/i ){		# Find description
			$main::dev{$na}{de} = $1;
		}
		if( $res->decoded_content =~ m/<tr><td>1<\/td><td>([0-9]+)@/i ){			# Find 1st extension
			$main::dev{$na}{co} = $1;
		}
		&misc::Prt("LWP :Contact:$main::dev{$na}{co} FW:$main::dev{$na}{bi} $main::dev{$na}{de}\n");
	} else {
		&misc::Prt("LWP :Error " . $res->status_line ."\n");
		return 1;
	}
}
	
1;
