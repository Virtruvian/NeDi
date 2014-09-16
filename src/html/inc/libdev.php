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
// Return Physical Class
function ModClass($cl){

	global $mlvl, $nonlbl, $stco;

	if 	($cl == 1) {return array($mlvl['10'],"ugrp");}
	elseif	($cl == 2) {return array($stco['250'],"bbox");}
	elseif	($cl == 3) {return array("Chassis","dev");}
	elseif	($cl == 4) {return array("Backplane","cinf");}
	elseif	($cl == 5) {return array("Container","pkg");}
	elseif	($cl == 6) {return array("Power Supply","flas");}
	elseif	($cl == 7) {return array("Fan","fan");}
	elseif	($cl == 8) {return array("Sensor","radr");}
	elseif	($cl == 9) {return array("Module","pcm");}
	elseif	($cl == 10){return array("Port","port");}
	elseif	($cl == 11){return array("Stack","db");}

	elseif	($cl == 20){return array("CPU","cpu");}
	elseif	($cl == 21){return array("Mem","mem");}
	elseif	($cl == 22){return array("HDD","hdd");}
	elseif	($cl == 23){return array("Card","card");}

	elseif	($cl == 30){return array("Printsupply","file");}
	elseif	($cl == 40){return array("Virtual Machine","node");}
	elseif	($cl == 50){return array("Controlled AP","wlan");}
	elseif	($cl == 60){return array("Server","nhdd");}

	elseif	($cl == 90){return array("Keypad","calc");}
	elseif	($cl == 91){return array("Camera","cam");}

	else	{return array("?","find");}
}

//===================================================================
// Return Device category based on icon
function DevCat($i){

	global $mlvl,$siz;

	if( preg_match('/^rs/',$i) ){
		return "Router $siz[s]";
	}elseif( preg_match('/^rm/',$i) ){
		return "Router $siz[m]";
	}elseif( preg_match('/^rl/',$i) ){
		return "Router $siz[l]";
	}elseif( preg_match('/^w2/',$i) ){
		return "Workgroup L2 Switch";
	}elseif( preg_match('/^w3/',$i) ){
		return "Workgroup L3 Switch";
	}elseif( preg_match('/^c2/',$i) ){
		return "Chassis L2 Switch";
	}elseif( preg_match('/^c3/',$i) ){
		return "Chassis L3 Switch";
	}elseif( preg_match('/^fv/',$i) ){
		return "Virtual FW";
	}elseif( preg_match('/^fw/',$i) ){
		return "Firewall";
	}elseif( preg_match('/^vp/',$i) ){
		return "VPN FW";
	}elseif( preg_match('/^ap/',$i) ){
		return "Appliance";
	}elseif( preg_match('/^cs/',$i) ){
		return "Contentswitch";
	}elseif( preg_match('/^lb/',$i) ){
		return "Loadbalancer";
	}elseif( preg_match('/^ic/',$i) ){
		return "IP Camera";
	}elseif( preg_match('/^iv/',$i) ){
		return "Video Conferencing";
	}elseif( preg_match('/^bs/',$i) ){
		return "Bladeserver Chassis";
	}elseif( preg_match('/^sv/',$i) ){
		return "Server";
	}elseif( preg_match('/^ph/',$i) ){
		return "IP Phone";
	}elseif( preg_match('/^at/',$i) ){
		return "Voice Adapter";
	}elseif( preg_match('/^up/',$i) ){
		return "UPS";
	}elseif( preg_match('/^pg/',$i) ){
		return "B&W Printer";
	}elseif( preg_match('/^pc/',$i) ){
		return "Color Printer";
	}elseif( preg_match('/^hv/',$i) ){
		return "Hypervisor";
	}elseif( preg_match('/^vs/',$i) ){
		return "Virtual Switch";
	}elseif( preg_match('/^hv/',$i) ){
		return "Hypervisor";
	}elseif( preg_match('/^fc/',$i) ){
		return "Fibrechannel Switch";
	}elseif( preg_match('/^st/',$i) ){
		return "Storage";
	}elseif( preg_match('/^wc/',$i) ){
		return "Wireless Controller";
	}elseif( preg_match('/^wa/',$i) ){
		return "Wireless AP";
	}elseif( preg_match('/^wb/',$i) ){
		return "Wireless Bridge";
	}else{
		return $mlvl['10'];
	}
}

//===================================================================
// Return Device mode (VTP mode for Cisco switches)
function DevMode($m){

	global $errlbl,$notlbl,$usrlbl,$addlbl;

	if 	($m == 0)	{ return "-"; }
	elseif	($m == 1)	{ return "VTP Client"; }
	elseif	($m == 2)	{ return "VTP Server"; }
	elseif	($m == 3)	{ return "Transparent"; }
	elseif	($m == 4)	{ return "Off"; }
	elseif	($m == 5)	{ return "SNMP $errlbl"; }
	elseif	($m == 8)	{ return "Controlled AP"; }
	elseif	($m == 9)	{ return "$usrlbl $addlbl"; }
	elseif 	($m == 10)	{ return "$notlbl SNMP"; }
	elseif	($m == 11)	{ return "VoIP Phone"; }
	elseif	($m == 12)	{ return "VoIP Box"; }
	elseif	($m == 15)	{ return "Wlan AP"; }
	elseif	($m == 17)	{ return "Wlan Bridge"; }
	elseif	($m == 20)	{ return "Video Camera"; }
	elseif	($m == 21)	{ return "Video Conference"; }
	elseif	($m == 30)	{ return "Virtual Bridge"; }
	else			{ return $m; }
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
function DevCli($ip,$p,$t=0){

	global $nipl;

	if(!$ip or $ip == "0.0.0.0" or !$p or $nipl){
		if($t != 2){
			return "$ip";
		}
	}else{
		if($p == 22){
			return "<a href=\"ssh://$ip\">".(($t)?"<img src=\"img/16/lokc.png\"  title=\"SSH $ip\">":$ip)."</a>";
		}elseif($p == 23){
			return "<a href=\"telnet://$ip\">".(($t)?"<img src=\"img/16/loko.png\" title=\"Telnet  $ip\">":$ip)."</a>";
		}else{
			return "<a href=\"telnet://$ip:$p\">".(($t)?"<img src=\"img/16/loko.png\" title=\"Telnet $ip Port $p\">":$ip)."</a>";
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
// Return IANAifType
function Iftype($it){

	if ($it == "5"){$img = "tel";$tit="rfc877x25";
	}elseif ($it == "6"){$img = "p45";$tit="Ethernet";
	}elseif ($it == "7"){$img = "p45";$tit="iso88023Csmacd";
	}elseif ($it == "18"){$img = "tel";$tit="ds1";
	}elseif ($it == "19"){$img = "tel";$tit="E1";
	}elseif ($it == "20"){$img = "tel";$tit="basicISDN";
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
	}elseif ($it == "54"){$img = "mux";$tit="propMultiplexor";
	}elseif ($it == "56"){$img = "fibr";$tit="fibreChannel";
	}elseif ($it == "58"){$img = "cell";$tit="frameRelayInterconnect";
	}elseif ($it == "63"){$img = "tel";$tit="isdn";
	}elseif ($it == "71"){$img = "ant";$tit="radio spread spectrum";
	}elseif ($it == "75"){$img = "tel";$tit="isdns";
	}elseif ($it == "77"){$img = "plug";$tit="lapd";
	}elseif ($it == "81"){$img = "tel";$tit="ds0";
	}elseif ($it == "94"){$img = "plug";$tit="adsl";
	}elseif ($it == "97"){$img = "plug";$tit="vdsl";
	}elseif ($it == "101"){$img = "tel";$tit="voiceFX0";
	}elseif ($it == "102"){$img = "tel";$tit="voiceFXS";
	}elseif ($it == "103"){$img = "tel";$tit="voiceEncap";
	}elseif ($it == "104"){$img = "tel";$tit="voiceOverlp";
	}elseif ($it == "117"){$img = "p45";$tit="Gigabit Ethernet";
	}elseif ($it == "131"){$img = "tun";$tit="Encapsulation Interface";
	}elseif ($it == "134"){$img = "cell";$tit="ATM Sub Interface";
	}elseif ($it == "135"){$img = "chip";$tit="Layer 2 Virtual LAN";
	}elseif ($it == "136"){$img = "chip";$tit="Layer 3 IP Virtual LAN";
	}elseif ($it == "150"){$img = "tun";$tit="mplsTunnel";
	}elseif ($it == "161"){$img = "lag";$tit="ieee8023adLag";
	}elseif ($it == "166"){$img = "mpls";$tit="mpls";
	}elseif ($it == "171"){$img = "cell";$tit="Packet over SONET/SDH Interface";
	}elseif ($it == "209"){$img = "bri";$tit="Transparent bridge Interface";
	}elseif ($it == "244"){$img = "ppp";$tit="3GPP2 WWAN";
	}elseif ($it == "251"){$img = "plug";$tit="vdsl2";
	}elseif ($it == "258"){$img = "chip";$tit="vmwareVirtualNic";
	}else{$img = "qg";$tit="Other-$it";}

	return array("$img.png",$tit);
}

//===================================================================
// Return IF status from DB value:
// bit2=oper stat, bit1=admin stat
function Ifdbstat($s){

	if(($s & 3) == 3){									# & 3 in case more values will be added to status field
		return array("good","Link up/Admin up");
	}elseif($s & 1){
		return array("warn","Link down/Admin up");
	}elseif($s & 2){
		return array("noti","Link up/Admin down?");
	}else{
		return array("alrm","Link down/Admin down");
	}
}

//===================================================================
// Generate location string for DB query. Now supporting sub-buildings
// like Ricklicollege_Campus1
function TopoLoc($reg="",$cty="",$bld="",$flr="",$rom=""){

	global $locsep;

	if($rom){
		return "$reg$locsep$cty$locsep$bld$locsep$flr$locsep$rom$locsep%";
	}elseif($bld){
		$b = explode('_', $bld);
		return "$reg$locsep$cty$locsep$b[0]%";
	}elseif($cty){
		return "$reg$locsep$cty$locsep%";
	}elseif($reg){
		return "$reg$locsep%";
	}
}

//===================================================================
// Find best map using a nice recursive function
function TopoMap($reg="",$cty=""){

	global $sub,$debug;

	$cp = '';
	$rp = '';
	$p = ($sub)?"../topo":"topo";
	if($reg){
		if($cty){
			$cp = preg_replace('/\W/','', $reg).'/'.preg_replace('/\W/','', $cty);
			if (file_exists("$p/$cp/background.jpg")) {
				$mapbg = "$cp/background.jpg";
			}else{
				$mapbg = TopoMap($reg);
			}
		}else{
			$rp = preg_replace('/\W/','', $reg);
			if (file_exists("$p/$rp/background.jpg")) {
				$mapbg = "$rp/background.jpg";
			}
		}
	}
	if(!$mapbg) $mapbg = "background.jpg";
	if($debug){echo "<div class=\"textpad imga\">Mapbg:Sub=$sub Path=$p $rp $cp BG=$mapbg</div>\n";}
	return $mapbg;
}

//===================================================================
// Returns a device panel according to type or icon and size
function DevPanel($t,$i,$s=1){

	global $sub,$debug;

	$p = ($sub)?"../img/panel":"img/panel";

	if($debug){echo "<div class=\"textpad imga\">Panel:$sub Path=$p Type=$t Icon=$i</div>\n";}

	if( $t and file_exists("$p/$t.jpg") ){
		return "img/panel/$t.jpg";
	}elseif( preg_match('/^wa/',$i) ){
		return "img/panel/gen-ap.jpg";
	}elseif( preg_match('/^ph/',$i) ){
		return "img/panel/gen-phone.jpg";
	}elseif( preg_match('/^ic/',$i) ){
		return "img/panel/gen-camera.jpg";
	}elseif( preg_match('/^cl/',$i) ){
		return "img/panel/gen-cloud.jpg";
	}elseif( preg_match('/^(wc|cs|vp)/',$i) ){
		return "img/panel/gen-ctrl.jpg";
	}elseif( preg_match('/^(hv|sv)/',$i) ){
		$s = ($s > 4)?4:$s;
		return "img/panel/gen-srv$s.jpg";
	}else{
		return "img/panel/gen-switch.jpg";
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
	elseif( preg_match("/^\s*(service|snmp|telnet|ssh|logging|boot|ntp|clock|http)| log /i",$l) )
		$l = "<span class='mrn'>$l</span>";
	elseif( preg_match("/root|cost|spanning-tree|stp|failover/i",$l) )
		$l = "<span class='grn'>$l</span>";
	elseif( preg_match("/passive-interface|default-gateway|redistribute|bgp/i",$l) )
		$l = "<span class='olv'>$l</span>";
	elseif( preg_match("/network|ip cef|neighbor|route|lldp/i",$l) )
		$l = "<span class='blu'>$l</span>";
	elseif( preg_match("/interface|vlan|line|\Wport/i",$l) )
		$l = "<span class='sbu'>$l</span>";
	elseif( preg_match("/address|broadcast|netmask|area/i",$l) )
		$l = "<span class='org'>$l</span>";
	elseif( preg_match("/^ standby.*|trunk|channel|access/i",$l) )
		$l = "<span class='sna'>$l</span>";
	elseif( preg_match("/^\s?aaa|radius|authentication|policy|crypto/i",$l) )
		$l = "<span class='drd'>$l</span>";
	elseif( preg_match("/ (mld|igmp|pim) /i",$l) )
		$l = "<span class='olv'>$l</span>";
	elseif( preg_match("/capabilities|vrf|mpls|vpn/i",$l) )
		$l = "<span class='sbu'>$l</span>";
	if($lnr)
		return sprintf("<span class='txtb'>%3d</span>",$lnr) . " $l\n";
	else
		return "$l\n";
}

//===================================================================
// Return Printer Supply Type
function PrintSupply($t){

	if 	($t == 1)	{return "<img src=\"img/16/ugrp.png\" title=\"other\">";}
	elseif	($t == 2)	{return "<img src=\"img/16/bbox.png\" title=\"unknown\">";}
	elseif	($t == 3)	{return "<img src=\"img/16/pcm.png\" title=\"toner\">";}
	elseif	($t == 4)	{return "<img src=\"img/16/bdis.png\" title=\"wasteToner\">";}
	elseif	($t == 5)	{return "<img src=\"img/16/mark.png\" title=\"ink\">";}
	elseif	($t == 6)	{return "<img src=\"img/16/mark.png\" title=\"inkCartridge\">";}
	elseif	($t == 7)	{return "<img src=\"img/16/mark.png\" title=\"inkRibbon\">";}
	elseif	($t == 8)	{return "<img src=\"img/16/bdis.png\" title=\"wasteInk\">";}
	elseif	($t == 9)	{return "<img src=\"img/16/bbox.png\" title=\"opc\">";}
	elseif	($t == 10)	{return "<img src=\"img/16/foto.png\" title=\"developer\">";}
	elseif	($t == 11)	{return "<img src=\"img/16/bomb.png\" title=\"fuserOil\">";}
	elseif	($t == 12)	{return "<img src=\"img/16/3d.png\" title=\"solidWax\">";}
	elseif	($t == 13)	{return "<img src=\"img/16/3d.png\" title=\"ribbonWax\">";}
	elseif	($t == 14)	{return "<img src=\"img/16/bdis.png\" title=\"wasteWax\">";}
	elseif	($t == 15)	{return "<img src=\"img/16/bomb.png\" title=\"fuser\">";}
	elseif	($t == 16)	{return "<img src=\"img/16/clip.png\" title=\"coronaWire\">";}
	elseif	($t == 17)	{return "<img src=\"img/16/bomb.png\" title=\"fuserOilWick\">";}
	elseif	($t == 18)	{return "<img src=\"img/16/tap.png\" title=\"cleanerUnit\">";}
	elseif	($t == 19)	{return "<img src=\"img/16/bbr2.png\" title=\"transferUnit\">";}
	elseif	($t == 20)	{return "<img src=\"img/16/pcm.png\" title=\"tonerCartridge\">";}
	elseif	($t == 21)	{return "<img src=\"img/16/pcm.png\" title=\"tonerCartridge\">";}
	elseif	($t == 22)	{return "<img src=\"img/16/bomb.png\" title=\"fuserOiler\">";}
	elseif	($t == 23)	{return "<img src=\"img/16/drop.png\" title=\"water\">";}
	elseif	($t == 24)	{return "<img src=\"img/16/bdis.png\" title=\"wasteWater\">";}
	elseif	($t == 25)	{return "<img src=\"img/16/tap.png\" title=\"glueWaterAdditive\">";}
	elseif	($t == 26)	{return "<img src=\"img/16/bdis.png\" title=\"wastePaper\">";}
	elseif	($t == 27)	{return "<img src=\"img/16/clip.png\" title=\"bindingSupply\">";}
	elseif	($t == 28)	{return "<img src=\"img/16/clip.png\" title=\"bandingSupply\">";}
	elseif	($t == 29)	{return "<img src=\"img/16/clip.png\" title=\"stitchingWire\">";}
	elseif	($t == 30)	{return "<img src=\"img/16/pkg.png\" title=\"shrinkWrap\">";}
	elseif	($t == 31)	{return "<img src=\"img/16/pkg.png\" title=\"paperWrap\">";}
	elseif	($t == 32)	{return "<img src=\"img/16/clip.png\" title=\"staples\">";}
	elseif	($t == 33)	{return "<img src=\"img/16/icon.png\" title=\"inserts\">";}
	elseif	($t == 34)	{return "<img src=\"img/16/fobl.png\" title=\"covers\">";}
	else			{return "-";}
}

//===================================================================
// Print IF RDD graphs and provide appropriate links
// Tiny graphs don't show y-axis, thus scale traffic to bw and bcast to 100
// Err and discards are bad enough to show any of them...
// If graphs are disabled in User-Profile, they're not drawn at all
function IfGraphs($ud,$ui,$opt,$sz){

	global $trflbl,$errlbl,$stalbl, $inblbl, $maxlbl;
	
	if($sz){
		$sz -= 1;
?>
<a href="Devices-Graph.php?dv=<?= $ud ?>&if%5B%5D=<?= $ui ?>&it%5B%5D=t">
<img src="inc/drawrrd.php?dv=<?= $ud ?>&if%5B%5D=<?= $ui ?>&s=<?= $sz ?>&t=trf&o=<?= $opt ?>" title="<?= $trflbl ?> <?= ($sz == 1)?" $maxlbl ".DecFix($opt):"" ?>">
</a>

<a href="Devices-Graph.php?dv=<?= $ud ?>&if%5B%5D=<?= $ui ?>&it%5B%5D=e">
<img src="inc/drawrrd.php?dv=<?= $ud ?>&if%5B%5D=<?= $ui ?>&s=<?= $sz ?>&t=err&o=1" title="<?= $errlbl ?>">
</a>

<a href="Devices-Graph.php?dv=<?= $ud ?>&if%5B%5D=<?= $ui ?>&it%5B%5D=d">
<img src="inc/drawrrd.php?dv=<?= $ud ?>&if%5B%5D=<?= $ui ?>&s=<?= $sz ?>&t=dsc" title="Discards">
</a>

<a href="Devices-Graph.php?dv=<?= $ud ?>&if%5B%5D=<?= $ui ?>&it%5B%5D=b">
<img src="inc/drawrrd.php?dv=<?= $ud ?>&if%5B%5D=<?= $ui ?>&s=<?= $sz ?>&t=brc&o=100" title="Broadcast <?=$inblbl?> <?= ($sz == 1)?" $maxlbl 100":"" ?>">
</a>

<a href="Devices-Graph.php?dv=<?= $ud ?>&if%5B%5D=<?= $ui ?>&it%5B%5D=s">
<img src="inc/drawrrd.php?dv=<?= $ud ?>&if%5B%5D=<?= $ui ?>&s=<?= $sz ?>&t=sta" title="<?= $stalbl ?>">
</a>
<?PHP
	}else{
		echo "---";
	}
}

//===================================================================
// Creates a Radargraph using interface information
function IfRadar($id,$sz,$c,$ti,$to,$ei,$eo,$di,$do,$bi,$anim=0){

	global $trflbl,$errlbl,$dcalbl,$inblbl,$oublbl,$debug;

	if($sz == 4){
		$w = 220;
		$h = 200;
		$f = 9;
	}elseif($sz == 3){
		$w = 110;
		$h = 100;
		$f = 7;
	}else{
		$w = 80;
		$h = 70;
		$f = 0;
	}
	$in  = substr($inblbl,0,1);
	$out = substr($oublbl,0,1);
	$trf = substr($trflbl,0,3);
	$err = substr($errlbl,0,3);
	$dca = substr($dcalbl,0,3);
?>
<canvas id="<?= $id ?>" class="genpad" width="<?= $w ?>" height="<?= $h ?>"></canvas>

<script language="javascript">
var data = {

	labels : ["<?= $trf ?> <?= $in ?>","<?= $trf ?> <?= $out ?>","<?= $err ?> <?= $in ?>","<?= $err ?> <?= $out ?>","<?= $dca ?>  <?= $in ?>","<?= $dca ?> <?= $out ?>","Bcast"],
	datasets : [
		{
			fillColor : "rgba(<?= substr($c,0,1)*30 ?>,<?= substr($c,1,1)*30 ?>,<?= substr($c,2,1)*30 ?>,0.4)",
			strokeColor : "rgba(<?= substr($c,0,1)*25 ?>,<?= substr($c,1,1)*25 ?>,<?= substr($c,2,1)*25 ?>,1)",
			pointColor : "rgba(<?= substr($c,0,1)*20 ?>,<?= substr($c,1,1)*20 ?>,<?= substr($c,2,1)*20 ?>,1)",
			pointStrokeColor : "#fff",
			data : [<?= intval($ti/1000) ?>,<?= intval($to/1000) ?>,<?= $ei ?>,<?= $eo ?>,<?= $di ?>,<?= $do ?>,<?= $bi ?>]
		}
	]
}
var ctx = document.getElementById("<?= $id ?>").getContext("2d");
var myNewChart = new Chart(ctx).Radar(data,{pointLabelFontSize : <?= $f ?><?= ($anim)?'':',animation: false' ?>});
</script>

<?php
	if($debug){
		echo "<div class=\"textpad code txta\">\n";
		echo "$id,$sz,$c,$ti,$to,$ei,$eo,$di,$do,$bi,$anim";
		echo "</div>\n";
	}
}

//===================================================================
// Return link style based on forward bandwidth or utilisation
function LinkStyle($bw=0,$utl=0){

	global $lit;

	if($lit == 'l'){
		$w = 4;
		if($utl == 0){										# No traffic
			return array($w,'gainsboro');
		}elseif($utl < 2){
			return array($w,'cornflowerblue');
		}elseif($utl < 5){
			return array($w,'blue');
		}elseif($utl < 10){
			return array($w,'green');
		}elseif($utl < 25){
			return array($w,'limegreen');
		}elseif($utl < 50){
			return array($w,'yellow');
		}elseif($utl < 75){
			return array($w,'orange');
		}else{
			return array($w,'red');
		}
	}else{
		if($bw == 0){										# No bandwidth
			return array('1','lightgray');
		}elseif($bw == 11000000 or $bw == 54000000 or $bw == 300000000 or $bw == 450000000){	# Most likely Wlan
			return array('5','gainsboro');
		}elseif($bw < 10000000){								# Most likely serial links
			return array(intval($bw/1000000),'limegreen');
		}elseif($bw < 100000000){								# 10 Mbit Ethernet
			return array(intval($bw/10000000),'blue');
		}elseif($bw < 1000000000){								# 100 Mbit Ethernet
			return array(intval($bw/100000000),'orange');
		}elseif($bw < 10000000000){								# 1 Gbit Ethernet
			return array(intval($bw/1000000000),'red');
		}else{											# 10 Gbit Ethernet
			return array(intval($bw/10000000000),'purple');
		}
	}
}

//===================================================================
// Count Ethernet ports which are down and haven't changed status for retire days
function IfFree($dv){

	global $link,$retire;

	$query = GenQuery('interfaces','s','count(ifname)','','',array('device','iftype','ifstat','lastchg'),array('=','~','<','<'),array($dv,'^(6|7|117)$','3',time()-$retire*86400),array('AND','AND','AND') );
	$res   = DbQuery($query,$link);
	if( DbNumRows($res) ){
		$inaif = DbFetchRow($res);
		$rval = $inaif[0];
	}else{
		$rval = 0;
	}
	DbFreeResult($res);

	return $rval;
}

//===================================================================
// Count devices
function DevPop($in,$op,$st,$co=array() ){

	global $link,$retire;

	$query = GenQuery('devices','s','count(device)','','',$in,$op,$st,$co );
	$res   = DbQuery($query,$link);
	$lpop  = DbFetchRow($res);
	DbFreeResult($res);

	return $lpop[0];
}

//===================================================================
// Count nodes
function NodPop($in,$op,$st,$co){

	global $link,$retire;

	$query = GenQuery('nodes','s','count(mac)','','',$in,$op,$st,$co,'JOIN devices USING (device) JOIN nodarp USING (mac)' );
	$res   = DbQuery($query,$link);
	$lpop  = DbFetchRow($res);
	DbFreeResult($res);

	return $lpop[0];
}

//===================================================================
// Return device vendor based on sysobjid or icon
function DevVendor($so,$ic=''){

	global $stco,$mlvl;

	$s = explode('.',$so);
	if( $ic == 'b' or $s[6] == 9 or $s[6] == 14179 ){
		return array('Cisco','cis');
	}elseif( $ic == 'c' or $s[6] == 674 or $s[6] == 6027 ){
		return array('Dell','de');
	}elseif( $ic == 'g' or $ic == 'h' or $s[6] == 11 or $s[6] == 43 or $s[6] == 8744 or $s[6] == 25506  ){
		return array('Hewlett-Packard','hp');
	}elseif( $ic == 'r' or $s[6] == 1991 ){
		return array('Brocade','brc');
	}elseif( $ic == 'o' or $s[6] == 45 or $s[6] == 2272 ){
		return array('Avaya','ava');
	}elseif( $ic == 'y' or $s[6] == 6486 ){
		return array('Alcatel-Lucent','alu');
	}elseif( $ic == 'p' or $s[6] == 1916 ){
		return array('Extreme Networks','ext');
	}elseif( $s[6] == 2636 ){
		return array('Juniper','jun');
	}elseif( $s[6] == 890 ){
		return array('Zyxel','zyx');
	}elseif( $s[6] == 12356){
		return array('Fortinet','for');
	}elseif( $ic == 'v' or $s[6] == 6876 ){
		return array('VMware','vm');
	}else{
		return array($mlvl['10'],'gend');
	}
}

//===================================================================
// Returns support status background
function SupportBg($d){
	if($d){
		if( time() > $d ){
			return "crit";
		}elseif( time() + 30 * 86400 > $d ){
			return "warn";
		}else{
			return "good";
		}
	}else{
		return '';
	}
}

//===================================================================
// Returns asset status icon
function Staimg($s){

	global $stco,$nonlbl,$invlbl;

	if($s == 10){
		return "<img src=\"img/16/star.png\" title=\"$stco[$s]\">";
	}elseif($s == 100){
		return "<img src=\"img/16/flas.png\" title=\"$stco[$s]\">";
	}elseif($s == 150){
		return "<img src=\"img/16/warn.png\" title=\"$stco[$s]\">";
	}elseif($s == 160){
		return "<img src=\"img/16/ring.png\" title=\"$stco[$s]\">";
	}elseif($s == 200){
		return "<img src=\"img/16/bstp.png\" title=\"$stco[$s]\">";
	}elseif($s == 250){
		return "<img src=\"img/16/bbox.png\" title=\"$stco[$s]\">";
	}else{
		return "<img src=\"img/16/bcls.png\" title=\"$nonlbl $invlbl\">";
	}
}

//===================================================================
// Crosscheck Assets 
function InvCheck($sn,$ty,$lo){

	global $link,$chglbl,$invlbl,$addlbl,$igrp,$wtylbl,$endlbl;

	$usn    = urlencode($sn);
	$uty    = urlencode($ty);
	$ulo    = urlencode($lo);

	$query	= GenQuery('inventory','s','state,endmaint,endwarranty,comment','','',array('serial'),array('='),array($sn));
	$dires	= DbQuery($query,$link);
	$dinv = DbFetchRow($dires);
	DbFreeResult($dires);
	if( $dinv[0] ){
		$mst = SupportBg($dinv[1]);
		$wst = SupportBg($dinv[2]);
		$ist = '';
		if($mst == 'crit' or $wst == 'crit'){
			$ist = 'class="genpad crit"';
		}elseif($mst == 'warn' or $wst == 'warn'){
			$ist = 'class="genpad warn"';
		}elseif($mst == 'good' and $wst == 'good'){
			$ist = 'class="genpad good"';
		}elseif($mst == 'good' or $wst == 'good'){
			$ist = 'class="genpad good part"';
		}
		echo "<a href=\"Assets-Inventory.php?chg=$usn\" $ist title=\"$chglbl ($igrp[31]:$mst, $wtylbl:$wst)\">".Staimg($dinv[0])." $sn</a> $dinv[3]";
	}elseif($sn and $sn != '-'){
		echo "<a href=\"Assets-Inventory.php?sta=150&sn=$usn&typ=$uty&lst=ty&val=$uty&lo=$ulo\" title=\"$invlbl $addlbl\">$sn</a>";
	}
}

//===================================================================
// Return config status icon
function DevCfg($bucs){

	global $cfglbl,$chglbl,$stalbl,$wrtlbl,$buplbl,$errlbl,$nonlbl,$stco,$tim;

	$bup = substr($bucs,0,1);
	$sts = substr($bucs,1,1);

	if( $bup == 'A' ){
		$bst = "<img src=\"img/bulbg.png\" title=\"$buplbl: $stco[100]\">";
	}elseif( $bup == 'O' ){
		$bst = "<img src=\"img/bulby.png\" title=\"$buplbl: $stco[160]\">";
	}elseif( $bup == 'E' ){
		$bst = "<img src=\"img/bulbo.png\" title=\"$buplbl: $errlbl\">";
	}elseif( $bup == 'U' ){
		$bst = "<img src=\"img/bulbb.png\" title=\"$buplbl: OK, $stalbl $stco[250]\">";
	}else{
		$bst = "<img src=\"img/bulba.png\" title=\"$buplbl: $nonlbl\">";
	}

	if( $sts == 'W' ){
		$cst = "<img src=\"img/bulbg.png\" title=\"$cfglbl: $wrtlbl OK\">";
	}elseif( $sts == 'C' ){
		$cst = "<img src=\"img/bulbo.png\" title=\"$cfglbl: $chglbl $tim[a] $wrtlbl\">";
	}else{
		$cst = "<img src=\"img/bulba.png\" title=\"$cfglbl: $wrtlbl $stco[250]\">";
	}
	return $bst.$cst;
}

//===================================================================
// Return seconds from timeticks
function Tic2Sec($ticks){

	sscanf($ticks, "%d:%d:%d:%d.%d",$upd,$uph,$upm,$ups,$ticks);
	return $ups + $upm*60 + $uph*3600 + $upd*86400;
}

//===================================================================
// Delete device, related tables and files
function DevDelete($dld,$dtxt){

	global $link,$delbl,$errlbl,$updlbl,$nedipath;

	$query	= GenQuery('devices','d','','','',array('device'),array('='),array($dld) );
	if( !DbQuery($query,$link) ){echo "<h4>Device ".DbError($link)."</h4>";}else{echo "<h5>Device $dld $dellbl OK</h5>";}
	$query	= GenQuery('interfaces','d','','','',array('device'),array('='),array($dld) );
	if( !DbQuery($query,$link) ){echo "<h4>IF ".DbError($link)."</h4>";}else{echo "<h5>IF $dld $dellbl OK</h5>";}
	$query	= GenQuery('modules','d','','','',array('device'),array('='),array($dld) );
	if( !DbQuery($query,$link) ){echo "<h4>Modules ".DbError($link)."</h4>";}else{echo "<h5>Modules $dld $dellbl OK</h5>";}
	$query	= GenQuery('links','d','','','',array('device'),array('='),array($dld) );
	if( !DbQuery($query,$link) ){echo "<h4>Links ".DbError($link)."</h4>";}else{echo "<h5>Links $dld $dellbl OK</h5>";}
	$query	= GenQuery('links','d','','','',array('neighbor'),array('='),array($dld) );
	if( !DbQuery($query,$link) ){echo "<h4>Links ".DbError($link)."</h4>";}else{echo "<h5>Links $dld $dellbl OK</h5>";}
	$query	= GenQuery('configs','d','','','',array('device'),array('='),array($dld) );
	if( !DbQuery($query,$link) ){echo "<h4>Config ".DbError($link)."</h4>";}else{echo "<h5>Config $dld $dellbl OK</h5>";}
	$query	= GenQuery('monitoring','d','','','',array('name'),array('='),array($dld) );
	if( !DbQuery($query,$link) ){echo "<h4>Monitoring ".DbError($link)."</h4>";}else{echo "<h5>Monitoring $dld $dellbl OK</h5>";}
	$query	= GenQuery('incidents','d','','','',array('name'),array('='),array($dld) );
	if( !DbQuery($query,$link) ){echo "<h4>Incidents ".DbError($link)."</h4>";}else{echo "<h5>Incidents $dld $dellbl OK</h5>";}
	$query	= GenQuery('vlans','d','','','',array('device'),array('='),array($dld) );
	if( !DbQuery($query,$link) ){echo "<h4>Vlans ".DbError($link)."</h4>";}else{echo "<h5>Vlans $dld $dellbl OK</h5>";}
	$query	= GenQuery('networks','d','','','',array('device'),array('='),array($dld) );
	if( !DbQuery($query,$link) ){echo "<h4>Networks ".DbError($link)."</h4>";}else{echo "<h5>Networks $dld $dellbl OK</h5>";}
	$query	= GenQuery('events','d','','','',array('source'),array('='),array($dld) );
	if( !DbQuery($query,$link) ){echo "<h4>Events ".DbError($link)."</h4>";}else{echo "<h5>Events $dld $dellbl OK</h5>";}

	$devdir = rawurlencode($dld);
	if( file_exists ( "$nedipath/rrd/$devdir/*.rrd" ) ){
		foreach (glob("$nedipath/rrd/$devdir/*.rrd") as $rrd){
			echo (unlink($rrd))?"<h5>$rrd $dellbl OK</h5>":"<h4>$rrd $dellbl $errlbl</h4>";
		}
		echo (rmdir("$nedipath/rrd/$devdir"))?"<h5>$nedipath/rrd/$devdir $dellbl OK</h5>":"<h4>$nedipath/rrd/$devdir $dellbl $errlbl</h4>";
	}
	if( file_exists ( "$nedipath/conf/$devdir/*.rrd" ) ){
		foreach (glob("$nedipath/conf/$devdir/*.cfg") as $cfg){
			echo (unlink($cfg))?"<h5>$cfg $dellbl OK</h5>":"<h4>$cfg $dellbl $errlbl</h4>";
		}
		echo (rmdir("$nedipath/conf/$devdir"))?"<h5>$nedipath/conf/$devdir $dellbl OK</h5>":"<h4>$nedipath/conf/$devdir $dellbl $errlbl</h4>";
	}

	$query = GenQuery('events','i','','','',array('level','time','source','info','class','device'),array(),array('100',time(),$dld,"device$dtxt deleted by $_SESSION[user]",'usrd',$dld) );
}

?>
