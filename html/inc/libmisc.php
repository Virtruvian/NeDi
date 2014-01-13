<?PHP
//===================================================================
// Miscellaneous functions
//===================================================================

//===================================================================
// Read configuration
function ReadConf($group=''){

	global $locsep,$lang,$redbuild,$modgroup,$disc,$fahrtmp;
	global $comms,$mod,$backend,$dbhost,$dbname,$dbuser,$dbpass,$retire;
	global $timeout,$ignoredvlans,$useivl,$cpua,$mema,$tmpa,$trfa,$trfw;
	global $poew,$pause,$latw,$rrdcmd,$nedipath,$rrdstep;
	global $cacticli,$cactiuser,$cactipass,$cactidb,$cactihost,$cactiurl;
	global $guiauth,$radsrv, $ldapsrv, $ldapmap;

	if (file_exists("$nedipath/nedi.conf")) {
		$conf = file("$nedipath/nedi.conf");
	}elseif (file_exists("/etc/nedi.conf")) {
		$conf = file("/etc/nedi.conf");
	}elseif (file_exists("../nedi.conf")) {
		$conf = file("../nedi.conf");
	}else{
		echo "Can't find nedi.conf!";
		die;
	}

	$locsep	= " ";
	foreach ($conf as $cl) {
		if ( !preg_match("/^#|^$/",$cl) ){
			$v =  preg_split('/[\t\s]+/', rtrim($cl,"\n\r\0") );

			if ($v[0] == "module"){
				$v[4] = isset($v[4]) ? $v[4] : "usr";
				$modgroup["$v[1]-$v[2]"] = $v[4];
				if( strpos($group,$v[4]) !== false){
					$mod[$v[1]][$v[2]] = $v[3];
				}
			}
			if ($v[0] == "comm"){
				$comms[$v[1]]['aprot'] = (isset($v[3]))?$v[2]:"";
				$comms[$v[1]]['apass'] = (isset($v[3]))?$v[3]:"";
				$comms[$v[1]]['pprot'] = (isset($v[5]))?$v[4]:"";
				$comms[$v[1]]['ppass'] = (isset($v[5]))?$v[5]:"";
			}
			elseif ($v[0] == "backend")	{$backend = $v[1];}
			elseif ($v[0] == "dbhost")	{$dbhost  = $v[1];}
			elseif ($v[0] == "dbname")	{$dbname  = isset($_SESSION['snap'])?$_SESSION['snap']:$v[1];}
			elseif ($v[0] == "dbuser")	{$dbuser  = $v[1];}
			elseif ($v[0] == "dbpass")	{$dbpass  = $v[1];}

			elseif ($v[0] == "cpu-alert")	{$cpua = $v[1];}
			elseif ($v[0] == "mem-alert")	{$mema = $v[1];}
			elseif ($v[0] == "temp-alert")	{$tmpa = $v[1];}
			elseif ($v[0] == "poe-warn"){	$poew  = $v[1];}
			elseif ($v[0] == "traf-alert")	{$trfa = $v[1];}
			elseif ($v[0] == "traf-warn")	{$trfw = $v[1];}

			elseif ($v[0] == "latency-warn"){$latw         = $v[1];}
			elseif ($v[0] == "pause")	{$pause        = $v[1];}
			elseif ($v[0] == "ignoredvlans"){$ignoredvlans = $v[1];}
			elseif ($v[0] == "useivl")	{$useivl       = $v[1];}
			elseif ($v[0] == "retire")	{$retire       = $v[1];}
			elseif ($v[0] == "timeout")	{$timeout      = $v[1];}

			elseif ($v[0] == "rrdcmd")	{$rrdcmd   = $v[1];}
			elseif ($v[0] == "nedipath")	{$nedipath = $v[1];}
			elseif ($v[0] == "rrdstep")	{$rrdstep  = $v[1];}

			elseif ($v[0] == "locsep")	{$locsep   = $v[1];}
			elseif ($v[0] == "guiauth")	{$guiauth  = $v[1];}
			elseif ($v[0] == "radserver")	{$radsrv[] = array($v[1],$v[2],$v[3],$v[4],$v[5]);}
			elseif ($v[0] == "ldapsrv")	{$ldapsrv  = array($v[1],$v[2],$v[3],$v[4],$v[5],$v[6]);}
			elseif ($v[0] == "ldapmap")	{$ldapmap  = array($v[1],$v[2],$v[3],$v[4],$v[5],$v[6],$v[7],$v[8]);}
			elseif ($v[0] == "redbuild")	{array_shift($v);$redbuild = implode(" ",$v);}
			elseif ($v[0] == "disclaimer")	{array_shift($v);$disc = implode(" ",$v);}

			elseif ($v[0] == "cacticli")	{array_shift($v);$cacticli = implode(" ",$v);}

			elseif ($v[0] == "cactihost")	{$cactihost = $v[1];}
			elseif ($v[0] == "cactidb")	{$cactidb   = $v[1];}
			elseif ($v[0] == "cactiuser")	{$cactiuser = $v[1];}
			elseif ($v[0] == "cactipass")	{$cactipass = $v[1];}
			elseif ($v[0] == "cactiurl")	{$cactiurl  = $v[1];}
		}
	}
}

//===================================================================
// Avoid directory traversal attacks (../ or ..\)
// Remove <script> tags
//       Avoid condition exclusion (e.g. attacking viewdev) with mysql comment --
// Recursive because array elements can be array as well
function sanitize( $arr ){
	if ( is_array($arr) ){
		return array_map( 'sanitize', $arr );
	}
	return preg_replace( "/\.\.\/|<(\/)?script>|javascript/i","", $arr );
}

//===================================================================
// Return IP address from hex value
function hex2ip($hip){
	return  hexdec(substr($hip, 0, 2)).".".hexdec(substr($hip, 2, 2)).".".hexdec(substr($hip, 4, 2)).".".hexdec(substr($hip, 6, 2));
}

//===================================================================
// Return IP address as hex
function ip2hex($ip){
	$i =  explode('.', str_replace( "*", "", $ip ) );
	return  sprintf("%02x%02x%02x%02x",$i[0],$i[1],$i[2],$i[3]);
}

//===================================================================
// Return IP address as bin
function ip2bin($ip){
	$i	=  explode('.',$ip);
	return sprintf(".%08b.%08b.%08b.%08b",$i[0],$i[1],$i[2],$i[3]);
}

//===================================================================
// Invert IP address
function ipinv($ip){
	$i	=  explode('.',$ip);
	return (255-$i[0]).".".(255-$i[1]).".".(255-$i[2]).".".(255-$i[3]);
}

//===================================================================
// convert netmask to various formats and check whether it's valid.
function Masker($in){

	if(preg_match("/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/",$in) ){
		$mask = $in;
		list($n1,$n2,$n3,$n4) = explode('.', $in);
		$bits = str_pad(decbin($n1),8,0,STR_PAD_LEFT) .
			str_pad(decbin($n2),8,0,STR_PAD_LEFT) .
			str_pad(decbin($n3),8,0,STR_PAD_LEFT) .
			str_pad(decbin($n4),8,0,STR_PAD_LEFT);
		#$bits = str_pad(decbin($n1) . decbin($n2) . decbin($n3) . decbin($n4),32,0);
		$nbit = count_chars($bits);
		$pfix = $nbit[49];										// the 49th char is "1"...
		$dec  = ip2long($in);
	}elseif(preg_match("/^[-]|\d{3,10}$/",$in ) ){
		if( is_int($in) ){
			$in = sprintf("%u",$in);
		}
		$mask = long2ip($in);
		$bits = base_convert($in, 10, 2);
		$nbit = count_chars($bits);
		$pfix = $nbit[49];
		$dec  = $in;
	}elseif(preg_match("/^\d{1,2}$/",$in) ){
		#shift left of 255.255.255.255 will be 255.255.255.255.0! Trim after SHL (Vasily)
		#$bits = base_convert(sprintf("%u",0xffffffff << (32 - $in) ),10,2);
		$bits = base_convert(sprintf("%u",0xffffffff & (0xffffffff << (32 - $in)) ),10,2);
		$mask = bindec(substr($bits, 0,8)).".".bindec(substr($bits, 8,8)).".".bindec(substr($bits, 16,8)).".".bindec(substr($bits, 24,8));
		$pfix = $in;
		$dec  = 0xffffffff << (32 - $in);
	}
	$bin	= preg_replace( "/(\d{8})/", ".\$1", $bits );
	if(strstr($bits,'01') ){
		return array($pfix,'Illegal Mask',$bin,$dec);
	}else{
		return array($pfix,$mask,$bin,$dec);	
	}
}

//===================================================================
// Replace ridiculously big numbers with readable ones
function DecFix($n){

	if($n >= 1000000000){
		return round($n/1000000000,1)."G";
	}elseif($n >= 1000000){
		return round($n/1000000,1)."M";
	}elseif($n >= 1000){
		return round($n/1000,1)."K";
	}else{
		return $n;
	}

}

//===================================================================
// Colorize html bg according to timestamps
function Agecol($fs, $ls,$row){

	global $retire;

        $o = 120 + 20 * $row;
	if(!$ls){$ls = $fs;}

        $tmpf = round(100 - 100 * (time() - $fs) / ($retire * 86400));
        if ($tmpf < 0){$tmpf = 0;}

        $tmpl = round(100 * (time() - $ls) / ($retire * 86400));
        if ($tmpl > 100){$tmpl = 100;}

        $tmpd = round(100 * ($ls  - $fs) / ($retire * 86400));
        if ($tmpd > 100){$tmpd = 100;}

        $f = sprintf("%02x",$tmpf + $o);
        $l = sprintf("%02x",$tmpl + $o);
        $d = sprintf("%02x",$tmpd + $o);
        $g = sprintf("%02x",$o);

        return array("$g$f$d","$l$g$d");
}

//===================================================================
// Generate html select box
function selectbox($type,$sel=""){

	global $cndlbl;
	
	if($type == "oper"){
		$options = array("regexp"=>"regexp","not regexp"=>"!regexp","like"=>"like",">"=>">","="=>"=","!="=>"!=",">="=>">=","<"=>"<","&"=>"and","|"=>"or");
	}elseif($type == "comop"){
		$options = array(""=>"$cndlbl A","AND"=>"A and B","OR"=>"A or B",">"=>"colA > colB","="=>"colA = colB","!="=>"colA != colB","<"=>"colA < colB");
	}elseif($type == "limit"){
		$options = array("5"=>"5","10"=>"10","20"=>"20","50"=>"50","100"=>"100","200"=>"200","500"=>"500","1000"=>"1000","2000"=>"2000","0"=>"none!");
	}
	foreach ($options as $key => $txt){
	       $selopt = ($sel == "$key")?" selected":"";
	       echo "<option value=\"$key\"$selopt>$txt\n";
	}
	#TODO add this and opening tag to function? echo "</select>\n" or just return array, which can be used for sanity checks?
}

//===================================================================
// Generate filter controls TODO implement!
function FltCtrl($in,$op,$st,$co){

	global $self, $selfi, $modgroup, $cndlbl, $cmblbl, $calendar, $cols, $col;
?>
<form method="get" name="list" action="<?= $self ?>.php">
<table class="content"><tr class="<?= $modgroup[$self] ?>1">
<th width="50"><a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png"></a></th>
<th valign="top"><?= $cndlbl ?> A<p>
<select size="1" name="ina">
<?php
foreach ($cols as $k => $v){
       echo "<option value=\"$k\"".( ($ina == $k)?" selected":"").">$v\n";
}
?>
</select>
<select size="1" name="opa">
<?php selectbox("oper",$opa) ?>
</select><p>
<?PHP if($calendar){echo "<a href=\"javascript:show_calendar('list.stb');\"><img src=\"img/16/date.png\"></a>\n";} ?>
<input type="text" name="sta" value="<?= $sta ?>" size="20">
</th>
<th valign="top"><?= $cmblbl ?><p>
<select size="1" name="cop">
<?php selectbox("comop",$cop) ?>
</select>
</th>
<th valign="top"><?= $cndlbl ?> B<p>
<select size="1" name="inb">
<?php
foreach ($cols as $k => $v){
       echo "<option value=\"$k\"".( ($inb == $k)?" selected":"").">$v\n";
}
?>
</select>
<select size="1" name="opb">
<?php selectbox("oper",$opb) ?>
</select><p>
<?PHP if($calendar){echo "<a href=\"javascript:show_calendar('list.stb');\"><img src=\"img/16/date.png\"></a>\n";} ?>
<input type="text" name="stb" value="<?= $stb ?>" size="20">
</th>
<th valign="top"><?= $collbl ?><p>
<select multiple name="col[]" size="4">
<?php
foreach ($cols as $k => $v){
       echo "<option value=\"$k\"".((in_array($k,$col))?"selected":"").">$v\n";
}
?>

</select>
</th>
<?PHP
}

//===================================================================
// Generate condition header
function ConHead($ina, $opa, $sta, $cop="", $inb="", $opb="", $stb=""){

	global $fltlbl;

	if($sta == ""){
	#	echo "<h3>$fltlbl: $nonlbl</h3>";
	}else{
		if($cop == ""){ 
			echo "<h3>$fltlbl: $ina $opa \"$sta\"</h3>\n";
		}elseif($cop =="AND" or $cop =="OR"){ 
			echo "<h3>$fltlbl: $ina $opa \"$sta\" $cop $inb $opb \"$stb\"</h3>\n";
		}else{
			echo "<h3>$fltlbl: $ina $cop $inb</h3>\n";
		}
	}
}

//===================================================================
// Generate table header
// Opt	Bgcolor, column mode: 2 or 3=use all, 0 or 3=no sorting (1 shows selected columns with sorting arrow)
// Keys BL=blank, IG=ignored, NS=no-sort
function TblHead($bkg,$mode = 0){

	global $ord,$cols,$col,$altlbl,$srtlbl;

	if( isset($_GET['xls']) ){
		echo "<table><tr>";
	}else{
		echo "<table class=\"content\"><tr>";
	}

	if($mode == 2 or $mode == 3){
		$mycol = array_keys($cols);
	}else{
		$mycol = $col;
	}
	foreach( $mycol as $n ){
		if( !preg_match('/IG$/',$n) ){
			if( preg_match('/BL$/',$n) ){
				echo "<th class=\"$bkg\">&nbsp;</th>";
			}elseif( isset($_GET['xls']) or preg_match('/NS$/',$n) or $mode == 3 or !$mode ){
				echo "<th class=\"$bkg\">$cols[$n]</th>";
			}elseif( !array_key_exists($n,$cols) ){
				echo "<th class=\"$bkg\">$n</th>";
			}else{
				if( !$ord ){
					echo "<th nowrap class=\"$bkg\">$cols[$n]<a href=\"?$_SERVER[QUERY_STRING]&ord=$n+desc\"><img src=img/dwn.png title=\"Sort by $n\"></a></th>\n";
				}elseif($ord == $n){
					echo "<th nowrap class=\"$bkg mrn\">$cols[$n] <a href=\"?";
					echo preg_replace('/&ord=(.*)/',"",$_SERVER['QUERY_STRING']);
					echo "&ord=$n+desc\"><img src=\"img/up.png\" title=\"$srtlbl\"></a></th>\n";
				}elseif($ord == "$n desc"){
					echo "<th nowrap class=\"$bkg mrn\">$cols[$n] <a href=\"?";
					echo preg_replace('/&ord=(.*)/',"",$_SERVER['QUERY_STRING']);
					echo "&ord=$n\"><img src=\"img/dwn.png\" title=\"$altlbl $srtlbl\"></a></th>\n";
				}else{
					echo "<th nowrap class=\"$bkg\">$cols[$n] <a href=\"?";
					echo preg_replace('/&ord=(.*)/',"",$_SERVER['QUERY_STRING']);
					echo "&ord=$n+desc\"><img src=\"img/dwn.png\" title=\"$srtlbl $n\"></a></th>\n";
				}
			}
		}
	}
	echo "</tr>\n";
}

//===================================================================
// Generate table row
function TblRow($bg,$static=0){


	if( isset($_GET['xls']) ){
		echo "<tr>";
	}elseif($static){
		echo "<tr class=\"$bg\">";
	}elseif(isset($_GET['print']) ){
		echo "<tr class=\"$bg\" onclick=\"this.className='noti'\" ondblclick=\"this.className='$bg'\">";
	}else{
		echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\">";
	}
}

//===================================================================
// Generate table cell
function TblCell($val="",$href="",$fmt="",$img="",$typ=""){
#TODO clean code!
	$cval = ( !isset($_GET['print']) and !isset($_GET['xls']) and $href )?"<a href=\"$href\">$val</a>":$val;
	$cimg = ( !(isset($_GET['print']) and !strstr($typ,"-img") ) and !isset($_GET['xls']) and $img )?$img:"";

	if( strstr($typ,"th") ){
		echo "<th $fmt>$cimg$cval</th>";
	}else{
		echo "<td $fmt>$cimg$cval</td>";
	}
}

//===================================================================
// Generate coloured bar graph element
// mode determines color (used as threshold, if numeric)
// style si=small icon, mi=medium icon, ms=medium shape, li=large icon (default)
function Bar($val=1,$mode=0,$style="",$title=""){

	$tit   = $val;
	$tresh = ($mode)?$mode:"";							# 0 yields wrong results
	if($tresh == "lvl250"){
			$img = "red";
			$bg = "crit";
	}elseif( preg_match('/lvl(200|cfge|trfe|usrd)/',$tresh) ){
			$img = "org";
			$bg = "alrm";
	}elseif($tresh == "lvl150"){
			$img = "yel";
			$bg = "warn";
	}elseif($tresh == "lvl100"){
			$img = "blu";
			$bg = "noti";
	}elseif( preg_match('/lvl(50|cfgn)/',$tresh) ){
			$img = "grn";
			$bg = "good";
	}elseif($tresh == "lvl10"){
			$img = "gry";
			$bg = "imgb";
	}elseif($tresh > 0){
		if($val < $tresh){
			$img = "grn";
			$bg = "good";
		}elseif($val < 2 * $tresh){
			$img = "org";
			$bg = "alrm";
		}else{
			$img = "red";
			$bg = "crit";
		}
	}elseif($tresh < 0){
		if($val < -$tresh/2){
			$img = "red";
			$bg = "crit";
		}elseif($val < -$tresh){
			$img = "org";
			$bg = "alrm";
		}else{
			$img = "grn";
			$bg = "good";
		}
	}else{
		$img = "gry";
		$bg  = "imga";
		$tit = $title;
	}
	if($style == "ms"){
		$length = round(log($val)*10+2);
		return "<div style=\"float:left;padding:1px;width:${length}px;height:14px;border:1px solid #000\" class=\"$bg\">$tit</div>";
	}elseif($style == "mi"){
		$length = round(log($val)*10+2);
		return "<img src=img/$img.png width=$length class=\"smallbar\" title=\"$val\">";
	}elseif($style == "si"){
		$length = round(log($val)*4+1);
		return "<img src=img/$img.png width=$length class=\"smallbar\" title=\"$val\">";
	}
	if($val > 100000){
		$length = round($val / 10000 - 10);	
		return "<img src=\"img/$img.png\" width=\"400\" class=\"bigbar\" title=\">100000\"><img src=\"img/$img.png\" width=\"$length\" class=\"bigbar\" title=\"$val\">";
	}elseif($val > 10000){
		$length = round($val / 1000 - 10);	
		return "<img src=\"img/$img.png\" width=\"300\" class=\"bigbar\" title=\">10000\"><img src=\"img/$img.png\" width=\"$length\" class=\"bigbar\" title=\"$val\">";
	}elseif($val > 1000){
		$length = round($val / 100 - 10);	
		return "<img src=\"img/$img.png\" width=\"200\" class=\"bigbar\" title=\">1000\"><img src=\"img/$img.png\" width=\"$length\" class=\"bigbar\" title=\"$val\">";
	}elseif($val > 100){
		$length = round($val / 10 - 10);		
		return "<img src=\"img/$img.png\" width=\"100\" class=\"bigbar\" title=\">100\" ><img src=\"img/$img.png\" width=\"$length\" class=\"bigbar\" title=\"$val\">";
	}else{
		$length = round($val) + 3;
		return "<img src=\"img/$img.png\" width=\"$length\" class=\"bigbar\" title=\"$val\">";
	}
}

//===================================================================
// Return network type
function Nettype($ip,$ip6=""){
echo $ipv6;
	#if ($ip == "0.0.0.0"){$img = "netr";$tit="Default";
	if (preg_match("/^127\.0\.0/",$ip) or preg_match("/^::1/",$ip6) ){$img = "netr";$tit="LocalLoopback";
	}elseif (preg_match("/^192\.168/",$ip)){$img = "nety";$tit="Private 192.168/16";
	}elseif (preg_match("/^10\./",$ip)){$img = "netp";$tit="Private 10/8";
	}elseif (preg_match("/^172\.[1][6-9]/",$ip)){$img = "neto";$tit="Private 172.16/12";
	}elseif (preg_match("/^172\.[2][0-9]/",$ip)){$img = "neto";$tit="Private 172.16/12";
	}elseif (preg_match("/^172\.[3][0-1]/",$ip)){$img = "neto";$tit="Private 172.16/12";

	}elseif (preg_match("/^224\.0\.0/",$ip)){$img = "netb";$tit="Local Multicast-224.0.0/24";
	}elseif (preg_match("/^224\.0\.1/",$ip)){$img = "netb";$tit="Internetwork  Multicast-224.0.1/24";
	}elseif (preg_match("/^(224|233)/",$ip)){$img = "netb";$tit="AD-HOC Multicast-224~233";
	}elseif (preg_match("/^232\./",$ip)){$img = "netb";$tit="Source-specific Multicast-232/8";
	}elseif (preg_match("/^233\./",$ip)){$img = "netb";$tit="GLOP Multicast-233/8";
	}elseif (preg_match("/^234\./",$ip)){$img = "netb";$tit="Unicast-Prefix Multicast-234/8";
	}elseif (preg_match("/^239\./",$ip)){$img = "netb";$tit="Public Multicast-239/8";

	}elseif (preg_match("/^fe80/",$ip6)){$img = "nety";$tit="IPv6 Link Local";
	}elseif (preg_match("/^fc00/",$ip6)){$img = "neto";$tit="IPv6 Unique Local";
	}elseif (preg_match("/^ff01/",$ip6)){$img = "netb";$tit="IPv6 Interface Local Multicast";
	}elseif (preg_match("/^ff02/",$ip6)){$img = "netb";$tit="IPv6 Link Local Multicast";
	}elseif (preg_match("/^2001:0000/",$ip6)){$img = "netp";$tit="IPv6 Teredo";

	}else{$img = "netg";$tit="Public";}
	
	return array("$img.png",$tit);
}

//===================================================================
// Return Smilie based on name
function Smilie($usr,$s=0){
	
	global $stslbl, $cfglbl, $dsclbl, $msglbl;

	$n = strtolower($usr);
	if($n == "statc"){
		return "<img src=\"img/32/conf.png\"".($s?"width=\"20\"":"")." title=\"$cfglbl $stslbl\">";
	}elseif($n == "statd"){
		return "<img src=\"img/32/radr.png\"".($s?"width=\"20\"":"")." title=\"$dsclbl $stslbl\">";
	}elseif($n == "state"){
		return "<img src=\"img/32/bell.png\"".($s?"width=\"20\"":"")." title=\"$msglbl $stslbl\">";
	}elseif($n == "stati"){
		return "<img src=\"img/32/port.png\"".($s?"width=\"20\"":"")." title=\"Interface $stslbl\">";
	}else{
		$si = ( ord($n) + ord(substr($n,1)) + ord(substr($n,-1)) + ord(substr($n,-2)) ) % 99;
		return "<img src=\"img/usr/$si.png\"".($s?"width=\"20\"":"")." title=\"$n\">";
	}
}

//===================================================================
// Replace time of a variable in query string
function SkewTime($istr,$var,$days){

	global $sta, $end;

	$s = $days * 86400;
		
	if( !preg_match('/&sho=/',$istr) ){$istr .= '&sho=1';}
	if($var == "all"){
		$repl = "sta=".urlencode(date("m/d/Y H:i", ($sta + $s)))."&";
		$ostr = preg_replace("/sta=[0-9a-z%\+]+&/i",$repl,$istr);
		$repl = "end=".urlencode(date("m/d/Y H:i",($end + $s)))."&";
		$ostr = preg_replace("/end=[0-9a-z%\+]+(&|$)/i",$repl,$ostr);
	}else{
		$repl = "$var=".urlencode(date("m/d/Y H:i",($$var + $s)))."&";
		$ostr = preg_replace("/$var=[0-9a-z%\+]+(&|$)/i",$repl,$istr);
	}

	return $ostr;
}

//===================================================================
// Return Hex Address
// echo IP6('fe80::3ee5:a6ff:feca:ea41');
function IP6($addr) {
	return bin2hex( inet_pton($addr) );
}

?>
