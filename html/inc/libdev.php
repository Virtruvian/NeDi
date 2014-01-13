<?PHP

//===================================================================
// Device related functions
//===================================================================

//===================================================================
// sort based on floor
function Floorsort($a, $b){

	if (is_numeric($a) and is_numeric($b) ){
		if ($a == $b) return 0;
		return ($a > $b) ? -1 : 1;
	}else{
		return strnatcmp ( $a,$b );
	}
}

//===================================================================
// Return Sys Services
function Syssrv($sv){

	$srv = "";

	if ($sv &  1) {$srv = " Repeater"; }
	if ($sv &  2) {$srv = "$srv Bridge"; }
	if ($sv &  4) {$srv = "$srv Router"; }
	if ($sv &  8) {$srv = "$srv Gateway"; }
	if ($sv & 16) {$srv = "$srv Session"; }
	if ($sv & 32) {$srv = "$srv Terminal"; }				# VoIP phones are kind of a terminal too...
	if ($sv & 64) {$srv = "$srv Application"; }
	if (!$sv)     {$srv = "-"; }

	return $srv;
}

//===================================================================
// Return VTP mode
function VTPmod($vn){

	global $errlbl, $nonlbl;

	if 	($vn == 1)	{return "Client"; }
	elseif	($vn == 2)	{return "Server"; }
	elseif	($vn == 3)	{return "Transparent"; }
	elseif	($vn == 4)	{return "Off"; }
	elseif	($vn == 9)	{return "$errlbl"; }
	else			{return "$nonlbl"; }
}

//===================================================================
// Return city image
function CtyImg($nd){

	if($nd > 19){
		return "cityx";
	}elseif($nd > 9){
		return "cityl";
	}elseif($nd > 2){
		return "citym";
	}else{
		return "citys";
	}
}

//===================================================================
// Returns link for CLI access based on IP and port
function DevCli($ip,$p){

	global $noiplink;

	if(!$ip or $ip == "0.0.0.0" or !$p or $noiplink){
		return "$ip";
	}else{
		if($p == 22){
			return "<a href=\"ssh://$ip\">$ip</a>";
		}elseif($p == 23){
			return "<a href=\"telnet://$ip\">$ip</a>";
		}else{
			return "<a href=\"telnet://$ip:$p\">$ip</a>";
		}
	}
}

//===================================================================
// Return building image
function BldImg($nd,$na){

	global $redbuild;

	if( preg_match("/$redbuild/",$na) ){
		$bc = "r";
	}else{
		$bc = "";
	}
	if($nd > 19){
		return "bldh$bc";
	}elseif($nd > 9){
		return "bldb$bc";
	}elseif($nd > 2){
		return "bldm$bc";
	}else{
		return "blds$bc";
	}
}

//===================================================================
// Return Interface Type
function Iftype($it){

	if ($it == "5"){$img = "tel";$tit="rfc877x25";
	}elseif ($it == "6"){$img = "p45";$tit="Ethernet";
	}elseif ($it == "7"){$img = "p45";$tit="iso88023Csmacd";
	}elseif ($it == "18"){$img = "tel";$tit="ds1";
	}elseif ($it == "19"){$img = "tel";$tit="E1";
	}elseif ($it == "22"){$img = "ppp";$tit="Point to Point Serial";
	}elseif ($it == "23"){$img = "ppp";$tit="PPP";
	}elseif ($it == "24"){$img = "tape";$tit="Software Loopback";
	}elseif ($it == "28"){$img = "ppp";$tit="slip";
	}elseif ($it == "32"){$img = "ppp";$tit="Frame Relay DTE only";
	}elseif ($it == "37"){$img = "ppp";$tit="atm";
	}elseif ($it == "39"){$img = "fibr";$tit="sonet";
	}elseif ($it == "44"){$img = "plug";$tit="Frame Relay Service";
	}elseif ($it == "49"){$img = "netr";$tit="AAL5 over ATM";
	}elseif ($it == "50"){$img = "fibr";$tit="sonetPath";
	}elseif ($it == "51"){$img = "fibr";$tit="sonetVT";
	}elseif ($it == "53"){$img = "chip";$tit="Virtual Interface";
	}elseif ($it == "56"){$img = "fibr";$tit="fibreChannel";
	}elseif ($it == "58"){$img = "cell";$tit="frameRelayInterconnect";
	}elseif ($it == "63"){$img = "tel";$tit="isdn";
	}elseif ($it == "71"){$img = "ant";$tit="radio spread spectrum";
	}elseif ($it == "75"){$img = "tel";$tit="isdns";
	}elseif ($it == "77"){$img = "plug";$tit="lapd";
	}elseif ($it == "81"){$img = "tel";$tit="ds0";
	}elseif ($it == "101"){$img = "tel";$tit="voiceFX0";
	}elseif ($it == "102"){$img = "tel";$tit="voiceFXS";
	}elseif ($it == "103"){$img = "tel";$tit="voiceEncap";
	}elseif ($it == "104"){$img = "tel";$tit="voiceOverlp";
	}elseif ($it == "117"){$img = "p45";$tit="Gigabit Ethernet";
	}elseif ($it == "131"){$img = "plug";$tit="Encapsulation Interface";
	}elseif ($it == "134"){$img = "cell";$tit="ATM Sub Interface";
	}elseif ($it == "135"){$img = "chip";$tit="Layer 2 Virtual LAN";
	}elseif ($it == "136"){$img = "chip";$tit="Layer 3 IP Virtual LAN";
	}elseif ($it == "150"){$img = "tun";$tit="mplsTunnel";
	}elseif ($it == "161"){$img = "lag";$tit="ieee8023adLag";
	}elseif ($it == "166"){$img = "mpls";$tit="mpls";
	}elseif ($it == "171"){$img = "cell";$tit="Packet over SONET/SDH Interface";
	}elseif ($it == "209"){$img = "bri";$tit="Transparent bridge Interface";
	}elseif ($it == "244"){$img = "ppp";$tit="3GPP2 WWAN";
	}else{$img = "qg";$tit="Other-$it";}

	return array("$img.png",$tit);
}

//===================================================================
// Return IF status from DB value:
// 128=adm just went down , 8=opr skipped, 4=adm skipped, 2=opr up, 1=adm up
function Ifdbstat($s){

	if($s == 12){
			return array("","Link ?/Admin ?");
	}elseif($s & 8){
		if($s & 1){
			return array("noti","Link ?/Admin up");
		}else{
			return array("alrm","Link ?/Admin down");
		}
	}elseif($s & 4){
		if($s & 2){
			return array("good","Link up/Admin ?");
		}else{
			return array("alrm","Link down/Admin ?");
		}
	}else{
		if(($s & 3) == 3){
			return array("good","Link up/Admin up");
		}elseif($s & 1){
			return array("warn","Link down/Admin up");
		}elseif($s & 2){
			return array("crit","Link up/Admin down?");
		}else{
			return array("alrm","Link down/Admin down");
		}

	}
}

//===================================================================
// Generate location string for DB query
function TopoLoc($reg="",$cty="",$bld=""){

	global $locsep;

	$l = "";
	if($reg == "-"){
		$l = "^$";
	}elseif($reg or $cty or $bld){								# Any sub locations?
		$l  = "^$reg$locsep";								# Start at region level
		$l .= ($cty)?"$cty$locsep":"";							# Append city if set
		$l .= ($bld)?"$bld$locsep":"";							# Append building if set
	}
	return $l;
}

//===================================================================
// Find best map using a nice recursive function
function TopoMap($reg="",$cty=""){
	if($reg){
		if($cty){
			if (file_exists("log/map-$reg-$cty.png")) {
				return "map-$reg-$cty.png";
			}else{
				return TopoMap($reg);
			}
		}else{
			if (file_exists("log/map-$reg.png")) {
				return "map-$reg.png";
			}
		}
	}
	return "map-top.png";
}

//===================================================================
// Return device shape style based on icon
function Devshape($ico="xxan"){

	$col = substr($ico,2,1);
	$typ = substr($ico,0,1);
	$shd = substr($ico,3,1);

	if($shd == "d"){
		$siz = 24;
	}elseif($shd == "n"){
		$siz = 12;
	}elseif($shd == "p"){
		$siz = 10;
	}else{
		$siz = 8;
	}

	if($typ == "c"){
		$shp = 'square';
	}elseif($typ == "r"){
		$shp = 'circle';
	}elseif($typ == "w"){
		$shp = 'triangle';
	}else{
		$shp = 'star';
	}

	if($col == "b"){
		return array("blue",$siz,$shp);
	}elseif($col == "g"){
		return array("green",$siz,$shp);
	}elseif($col == "o"){
		return array("orange",$siz,$shp);
	}elseif($col == "r"){
		return array("red",$siz,$shp);
	}elseif($col == "p"){
		return array("purple",$siz,$shp);
	}elseif($col == "y"){
		return array("yellow",$siz,$shp);
	}else{
		return array("gray",$siz,$shp);
	}
}

//===================================================================
// Show a configuration
function Shoconf($l,$smo,$lnr){
	if($smo)
		$l = preg_replace("/(\^)([\w])$/","$1",$l);
	if( preg_match("/^\s*([!#;])|description/",$l) )
		$l = "<span class='gry'>$l</span>";
	elseif( preg_match("/^\s*((host|sys)?name|fault-finder|object-group|group-object)/i",$l) )
		$l = "<span class='dgy'>$l</span>";
	elseif( preg_match("/^\s*(no|undo)\s*|shutdown|disable|access-list|access-class|permit|rules/i",$l) )
		$l = "<span class='red'>$l</span>";
	elseif( preg_match("/user|login|password|inspect|network-object|port-object/i",$l) )
		$l = "<span class='prp'>$l</span>";
	elseif( preg_match("/^\s*(service|snmp|telnet|ssh|logging|boot|ntp|clock|http)/i",$l) )
		$l = "<span class='mrn'>$l</span>";
	elseif( preg_match("/root|cost|spanning-tree|stp|failover/i",$l) )
		$l = "<span class='grn'>$l</span>";
	elseif( preg_match("/passive-interface|default-gateway|redistribute|bgp/i",$l) )
		$l = "<span class='olv'>$l</span>";
	elseif( preg_match("/network|ip cef|neighbor|route/i",$l) )
		$l = "<span class='blu'>$l</span>";
	elseif( preg_match("/interface|vlan|line|\Wport/i",$l) )
		$l = "<span class='sbu'>$l</span>";
	elseif( preg_match("/address|broadcast|netmask|area/i",$l) )
		$l = "<span class='org'>$l</span>";
	elseif( preg_match("/^ standby.*|trunk|channel|access/i",$l) )
		$l = "<span class='sna'>$l</span>";
	elseif( preg_match("/^\s?aaa|radius|authentication|policy|crypto/i",$l) )
		$l = "<span class='sbu'>$l</span>";
	elseif( preg_match("/capabilities|vrf|mpls|vpn/i",$l) )
		$l = "<span class='drd'>$l</span>";
	if($lnr)
		return sprintf("<span class='txtb'>%3d</span>",$lnr) . " $l<br>";
	else
		return "$l<br>";
}

//===================================================================
// Return Printer Supply Type
function PrintSupply($t){

	if 	($t == 1)	{return "<img src=\"img/16/paint.png\" title=\"other\">";}
	elseif	($t == 2)	{return "<img src=\"img/16/paint.png\" title=\"unknown\">";}
	elseif	($t == 3)	{return "<img src=\"img/16/paint.png\" title=\"toner\">";}
	elseif	($t == 4)	{return "<img src=\"img/16/bdis.png\" title=\"wasteToner\">";}
	elseif	($t == 5)	{return "<img src=\"img/16/hlth.png\" title=\"ink\">";}
	elseif	($t == 6)	{return "<img src=\"img/16/pcm.png\" title=\"inkCartridge\">";}
	elseif	($t == 7)	{return "<img src=\"img/16/pcm.png\" title=\"inkRibbon\">";}
	elseif	($t == 8)	{return "<img src=\"img/16/bdis.png\" title=\"wasteInk\">";}
	elseif	($t == 9)	{return "opc";}
	elseif	($t == 10)	{return "<img src=\"img/16/geom.png\" title=\"developer\">";}
	elseif	($t == 11)	{return "<img src=\"img/16/tap.png\" title=\"fuserOil\">";}
	elseif	($t == 12)	{return "solidWax";}
	elseif	($t == 13)	{return "<img src=\"img/16/pcm.png\" title=\"ribbonWax\">";}
	elseif	($t == 14)	{return "<img src=\"img/16/bdis.png\" title=\"wasteWax\">";}
	elseif	($t == 15)	{return "<img src=\"img/16/tap.png\" title=\"fuser\">";}
	elseif	($t == 16)	{return "coronaWire";}
	elseif	($t == 17)	{return "fuserOilWick";}
	elseif	($t == 18)	{return "cleanerUnit";}
	elseif	($t == 19)	{return "transferUnit";}
	elseif	($t == 20)	{return "<img src=\"img/16/pcm.png\" title=\"tonerCartridge\">";}
	elseif	($t == 21)	{return "<img src=\"img/16/pcm.png\" title=\"tonerCartridge\">";}
	elseif	($t == 22)	{return "<img src=\"img/16/tap.png\" title=\"fuserOiler\">";}
	elseif	($t == 23)	{return "<img src=\"img/16/tap.png\" title=\"water\">";}
	elseif	($t == 24)	{return "<img src=\"img/16/bdis.png\" title=\"wasteWater\">";}
	elseif	($t == 25)	{return "<img src=\"img/16/tap.png\" title=\"glueWaterAdditive\">";}
	elseif	($t == 26)	{return "<img src=\"img/16/bcnl.png\" title=\"wastePaper\">";}
	elseif	($t == 27)	{return "<img src=\"img/16/clip.png\" title=\"bindingSupply\">";}
	elseif	($t == 28)	{return "<img src=\"img/16/clip.png\" title=\"bandingSupply\">";}
	elseif	($t == 29)	{return "<img src=\"img/16/clip.png\" title=\"stitchingWire\">";}
	elseif	($t == 30)	{return "<img src=\"img/16/news.png\" title=\"shrinkWrap\">";}
	elseif	($t == 31)	{return "<img src=\"img/16/news.png\" title=\"paperWrap\">";}
	elseif	($t == 32)	{return "<img src=\"img/16/clip.png\" title=\"staples\">";}
	elseif	($t == 33)	{return "<img src=\"img/16/icon.png\" title=\"inserts\">";}
	elseif	($t == 34)	{return "<img src=\"img/16/fobl.png\" title=\"covers\">";}
	else			{return "-";}
}
?>
