<?
# This file can be used to add links from Device-Status, with the following variables available for linking:
# $ip		Devices IP address
# $ud		URL encoded device name
# $os		Operating system
# $rcomm	SNMP read community
# $wcomm	SNMP write community
# $rver		SNMP read version
# $wver		SNMP write version
# $wasup	device was seen in last discovery
# $isadmin	current user is in admin group

# Usage example for  http link on port 8001 of non IOS devices:
#if($os != "IOS"){
#	echo "<a href=\"http://$ip:8001\"><img src=\"img/16/glob.png\"></a>";
#}
?>