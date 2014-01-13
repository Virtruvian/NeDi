<?PHP
//===============================
// SNMP related functions
// Based on libsnmp.php v0.3 by Steffen Neuser
// Using a 1/4 of timeout to avoid hanging GUI
//===============================

function Walk($ip, $ver, $cm, $oid, $t=1000000, $r=2){

	global $debug, $comms;

	if($ver == 3 and $comms[$cm]['pprot']){
		if($debug){echo "<div class=\"textpad warn \">snmpwalk -v3 -c$cm $ip (privacy) $oid ($t usec * $r)</div>";}
		return snmp3_real_walk($ip, $cm, 'authPriv', $comms[$cm]['aprot'], $comms[$cm]['apass'], $comms[$cm]['pprot'], $comms[$cm]['ppass'], ".$oid", $t );
	}elseif ($ver == 3 and $aprot){
		if($debug){echo "<div class=\"textpad warn \">snmpwalk -v3 -c$cm $ip (no privacy) $oid ($t usec * $r)</div>";}
		return snmp3_real_walk($ip, $cm, 'authNoPriv', $comms[$cm]['aprot'], $comms[$cm]['apass'], ".$oid", $t );
	}elseif ($ver == 2){
		if($debug){echo "<div class=\"textpad warn \">snmpwalk -v2c -c$cm $ip $oid ($t usec * $r)</div>";}
		return snmp2_real_walk($ip, $cm, ".$oid", $t );
	}else{
		if($debug){echo "<div class=\"textpad warn \">snmpwalk -v1 -c$cm $ip $oid ($t usec * $r)</div>";}
		return snmprealwalk($ip, $cm, ".$oid", $t );
	}
}

function Get($ip, $ver, $cm, $oid, $t=1000000, $r=2){

	global $debug, $comms;

	if($ver == 3 and $comms[$cm]['pprot']){
		if($debug){echo "<div class=\"textpad warn \">snmpget -v3 -c$cm $ip (and privacy) $oid ($t usec * $r)</div>";}
		return snmp3_get($ip, $cm, 'authPriv', $comms[$cm]['aprot'], $comms[$cm]['apass'], $comms[$cm]['pprot'], $comms[$cm]['ppass'], ".$oid", $t, $r);
	}elseif ($ver == 3 and $aprot){
		if($debug){echo "<div class=\"textpad warn \">snmpget -v3 -c$cm $ip (no privacy) $oid ($t usec * $r)</div>";}
		return snmp3_get($ip, $cm, 'authNoPriv', $comms[$cm]['aprot'], $comms[$cm]['apass'], ".$oid", $t, $r);
	}elseif ($ver == 2){
		if($debug){echo "<div class=\"textpad warn \">snmpget -v2c -c$cm $ip $oid ($t usec * $r)</div>";}
		return snmp2_get($ip, $cm, ".$oid", $t, $r);
	}else{
		if($debug){echo "<div class=\"textpad warn \">snmpget -v1 -c$cm $ip $oid ($t usec * $r)</div>";}
		return snmpget($ip, $cm, ".$oid", $t, $r);
	}
}

function Set($ip, $ver, $cm, $oid, $f, $v, $t=1000000, $r=2){

	global $debug, $comms;

	if($ver == 3 and $comms[$cm]['pprot']){
		if($debug){echo "<div class=\"textpad warn \">snmpset -v3 -c$cm $ip (and privacy) $oid $f $v ($t usec * $r)</div>";}
		return snmp3_set($ip, $cm, 'authPriv', $comms[$cm]['aprot'], $comms[$cm]['apass'], $comms[$cm]['pprot'], $comms[$cm]['ppass'], ".$oid", $f, $v, $t );
	}elseif ($ver == 3 and $aprot){
		if($debug){echo "<div class=\"textpad warn \">snmpset -v3 -c$cm $ip (no privacy) $oid $f $v ($t usec * $r)</div>";}
		return snmp3_set($ip, $cm, 'authNoPriv', $comms[$cm]['aprot'], $comms[$cm]['apass'], ".$oid", $f, $v, $t );
	}elseif ($ver == 2){
		if($debug){echo "<div class=\"textpad warn \">snmpset -v2c -c$cm $ip $oid $f $v ($t usec * $r)</div>";}
		return snmp2_set($ip, $cm, ".$oid", $f, $v, $t );
	}else{
		if($debug){echo "<div class=\"textpad warn \">snmpset -v1 -c$cm $ip $oid $f $v ($t usec * $r)</div>";}
		return snmpset($ip, $cm, ".$oid", $f, $v, $t );
	}
}

?>
