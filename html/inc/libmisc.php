<?PHP
//===================================================================
// Miscellaneous functions
//===================================================================

//===================================================================
// Read configuration
function ReadConf($group){

	global $locsep,$lang,$redbuild,$modgroup,$disc,$fahrtmp;
	global $comms,$mod,$backend,$dbhost,$dbname,$dbuser,$dbpass,$retire;
	global $timeout,$ignoredvlans,$useivl,$cpua,$mema,$tmpa,$trfa,$trfw;
	global $pause,$latw,$rrdcmd,$nedipath,$rrdstep;
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
			elseif ($v[0] == "dbname")	{$dbname  = $v[1];}
			elseif ($v[0] == "dbuser")	{$dbuser  = $v[1];}
			elseif ($v[0] == "dbpass")	{$dbpass  = $v[1];}

			elseif ($v[0] == "cpu-alert")	{$cpua = $v[1];}
			elseif ($v[0] == "mem-alert")	{$mema = $v[1];}
			elseif ($v[0] == "temp-alert")	{$tmpa = $v[1];}
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
// Avoid condition exclusion (e.g. attacking viewdev) with mysql comment --
// Recursive because array elements can be array as well
function sanitize( $arr ){
	if ( is_array($arr) ){
		return array_map( 'sanitize', $arr );
	}
	return preg_replace( "/\.\.[\/]|--/","", $arr );
}

//===================================================================
// Return IP address from hex value
function hex2ip($hip){
	return  hexdec(substr($hip, 0, 2)).".".hexdec(substr($hip, 2, 2)).".".hexdec(substr($hip, 4, 2)).".".hexdec(substr($hip, 6, 2));
}

//===================================================================
// Return from IP address as hex
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
function Masker($nm){

	if(preg_match("/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/",$nm) ){
		$mask = $nm;
		list($n1,$n2,$n3,$n4) = explode('.', $nm);
		$bits = str_pad(decbin($n1),8,0,STR_PAD_LEFT) .
			str_pad(decbin($n2),8,0,STR_PAD_LEFT) .
			str_pad(decbin($n3),8,0,STR_PAD_LEFT) .
			str_pad(decbin($n4),8,0,STR_PAD_LEFT);
		#$bits = str_pad(decbin($n1) . decbin($n2) . decbin($n3) . decbin($n4),32,0);
		$nbit = count_chars($bits);
		$pfix = $nbit[49];										// the 49th char is "1"...
	}elseif(preg_match("/^[-]|\d{3,10}$/",$nm ) ){
		$nm   = sprintf("%u",$nm);
		$mask = long2ip($nm);
		$bits = base_convert($nm, 10, 2);
		$nbit = count_chars($bits);
		$pfix = $nbit[49];
	}elseif(preg_match("/^\d{1,2}$/",$nm) ){
		#shift left of 255.255.255.255 will be 255.255.255.255.0! Trim after SHL (Vasily)
		#$bits = base_convert(sprintf("%u",0xffffffff << (32 - $nm) ),10,2);
		$bits = base_convert(sprintf("%u",0xffffffff & (0xffffffff << (32 - $nm)) ),10,2);
		$mask = bindec(substr($bits, 0,8)).".".bindec(substr($bits, 8,8)).".".bindec(substr($bits, 16,8)).".".bindec(substr($bits, 24,8));
		$pfix = $nm;
	}
	$bin	= preg_replace( "/(\d{8})/", ".\$1", $bits );
	if(preg_match("/01/",$bits) ){
		return array($nm,'Illegal Mask',$bin);
	}else{
		return array($pfix,$mask,$bin);	
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
// Generate column headers with sorting (obsolete soon)
function ColHead($n,$w=0){

	global $ord,$cols,$altlbl,$srtlbl;

	$wi="nowrap ";
	if( isset($_GET['xls']) ){
		echo "<th>$cols[$n]</th>";
	}else{
		if($w){
			$wi .="width=$w";
		}

		if (!$ord){
			echo "<th $wi>$cols[$n]<a href=?$_SERVER[QUERY_STRING]&ord=$n+desc><img src=img/dwn.png title=\"Sort by $n\"></a></th>";
		}elseif($ord == $n){
			echo "<th $wi class=mrn>$cols[$n] <a href=?";
			echo preg_replace('/&ord=(.*)/',"&ord=$n+desc",$_SERVER['QUERY_STRING']);
			echo "><img src=img/up.png title=\"$srtlbl\"></a></th>";
		}elseif($ord == "$n desc"){
			echo "<th $wi class=olv>$cols[$n] <a href=?";
			echo preg_replace('/&ord=(.*)/',"&ord=$n",$_SERVER['QUERY_STRING']);
			echo "><img src=img/dwn.png title=\"$altlbl $srtlbl\"></a></th>";
		}else{
			echo "<th $wi>$cols[$n] <a href=?";
			echo preg_replace('/&ord=(.*)/',"&ord=$n+desc",$_SERVER['QUERY_STRING']);
			echo "><img src=img/dwn.png title=\"$srtlbl $n\"></a></th>";
		}
	}
}

//===================================================================
// Generate html select box
function selectbox($type,$sel=""){

	global $cndlbl;
	
	if($type == "oper"){
		$options = array("regexp"=>"regexp","not regexp"=>"!regexp","regexpCI"=>"reg CI","not regexpCI"=>"!reg CI","like"=>"like",">"=>">","="=>"=","!="=>"!=",">="=>">=","<"=>"<","&"=>"and","||"=>"or","COL IS"=>"is","COL IS NOT"=>"is not");
	}elseif($type == "comop"){
		$options = array(""=>"$cndlbl A","AND"=>"A and B","OR"=>"A or B",">"=>"colA > colB","="=>"colA = colB","!="=>"colA != colB","<"=>"colA < colB");
	}elseif($type == "limit"){
		$options = array("5"=>"5","10"=>"10","20"=>"20","50"=>"50","100"=>"100","200"=>"200","500"=>"500","1000"=>"1000","2000"=>"2000","0"=>"none!");
	}
	foreach ($options as $key => $txt){
	       $selopt = ($sel == "$key")?"selected":"";
	       echo "<option value=\"$key\" $selopt >$txt\n";
	}
}

//===================================================================
// Generate condition header
function ConHead($ina, $opa, $sta, $cop="", $inb="", $opb="", $stb=""){

	global $fltlbl;

	if($sta == ""){
	#	echo "<h3>$fltlbl: $nonlbl</h3>";
	}else{
		if($cop == ""){ 
			echo "<h3>$fltlbl: $ina $opa \"$sta\"</h3>";
		}elseif($cop =="AND" or $cop =="OR"){ 
			echo "<h3>$fltlbl: $ina $opa \"$sta\" $cop $inb $opb \"$stb\"</h3>";
		}else{
			echo "<h3>$fltlbl: $ina $cop $inb</h3>";
		}
	}
}

//===================================================================
// Generate table row
function TblHead($stl,$srt = 0){

	global $ord,$cols,$col,$altlbl,$srtlbl;

	echo "<table class=\"content\"><tr class=\"$stl\">";

	foreach($col as $n){
		if( !array_key_exists($n,$cols) or isset($_GET['xls']) or !$srt){
			echo "<th>$cols[$n]</th>";
		}else{
			if (!$ord){
				echo "<th nowrap>$cols[$n]<a href=?$_SERVER[QUERY_STRING]&ord=$n+desc><img src=img/dwn.png title=\"Sort by $n\"></a></th>";
			}elseif($ord == $n){
				echo "<th nowrap class=mrn>$cols[$n] <a href=?";
				echo preg_replace('/&ord=(.*)/',"&ord=$n+desc",$_SERVER['QUERY_STRING']);
				echo "><img src=img/up.png title=\"$srtlbl\"></a></th>";
			}elseif($ord == "$n desc"){
				echo "<th nowrap class=olv>$cols[$n] <a href=?";
				echo preg_replace('/&ord=(.*)/',"&ord=$n",$_SERVER['QUERY_STRING']);
				echo "><img src=img/dwn.png title=\"$altlbl $srtlbl\"></a></th>";
			}else{
				echo "<th nowrap>$cols[$n] <a href=?";
				echo preg_replace('/&ord=(.*)/',"&ord=$n+desc",$_SERVER['QUERY_STRING']);
				echo "><img src=img/dwn.png title=\"$srtlbl $n\"></a></th>";
			}
		}
	}

	echo "</tr>\n";
}

//===================================================================
// Generate table row
function TblRow($over=0){

	global $bg;

	if($over and !isset($_GET['print']) and !isset($_GET['xls']) ){
		echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\">";
	}else{
		echo "<tr class=\"$bg\">";
	}
}

//===================================================================
// Generate table cell
function TblCell($val,$href="",$class="",$img=""){

	$cval = ( !isset($_GET['print']) and !isset($_GET['xls']) and $href)?"<a href=\"$href\">$val</a>":$val;
	$cimg = ( !isset($_GET['print']) and !isset($_GET['xls']) and $img)?$img:"";
	$ccla = ($class)?"class=\"$class\"":"";

	echo "<td $ccla>$cimg $cval</td>";
}

//===================================================================
// Generate coloured bar for html
// mode determines color (threshold, if numeric)
// style si=small icon, ms=medium shape, li=large icon (default)
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
function Nettype($nt){

	if ($nt == "0.0.0.0"){$img = "netr";$tit="Default";
	}elseif (preg_match("/^127\.0\.0/",$nt)){$img = "netr";$tit="LocalLoopback";
	}elseif (preg_match("/^192\.168/",$nt)){$img = "neto";$tit="Private-192.168/16";
	}elseif (preg_match("/^10\./",$nt)){$img = "netp";$tit="Private-10/8";
	}elseif (preg_match("/^172\.[1][6-9]/",$nt)){$img = "netb";$tit="Private-172.16/12";
	}elseif (preg_match("/^172\.[2][0-9]/",$nt)){$img = "netb";$tit="Private-172.16/12";
	}elseif (preg_match("/^172\.[3][0-1]/",$nt)){$img = "netb";$tit="Private-172.16/12";
	}else{$img = "netg";$tit="Public";}
	
	return array("$img.png",$tit);
}

//===================================================================
// Return Smilie based on name
function Smilie($usr,$s=0){
	
	$n = strtolower($usr);
	$si = ( ord($n) + ord(substr($n,1)) + ord(substr($n,-1)) + ord(substr($n,-2)) ) % 70;
	return "<img src=\"img/smiles/$si.png\"".($s?"width=\"20\"":"")." title=\"Hello, I'm $n\">";
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
// Return fileicon
function Fimg($f) {
	
	global $hislbl,$fillbl,$imglbl,$cfglbl,$cmdlbl,$mlvl;

	if    (stristr($f,".csv"))				{$i = "list";$t = "CSV $fillbl";}
	elseif(stristr($f,".def"))				{$i = "geom";$t = "Device Definition";}
	elseif(stristr($f,".log"))				{$i = "note";$t = "$hislbl";}
	elseif(stristr($f,".js"))				{$i = "dbmb";$t = "Javascript";}
	elseif(stristr($f,".php"))				{$i = "php"; $t = "PHP Script";}
	elseif(stristr($f,".reg"))				{$i = "cog";  $t = "Registry $fillbl";}
	elseif(stristr($f,".sql"))				{$i = "db";  $t = "DB $fillbl";}
	elseif(stristr($f,".txt"))				{$i = "abc"; $t = "TXT $fillbl";}
	elseif(stristr($f,".xml"))				{$i = "dcub";$t = "XML $fillbl";}
	elseif(preg_match("/\.(cfg|conf)$/i",$f))		{$i = "conf";$t = "$cfglbl";}
	elseif(preg_match("/\.(exe)$/i",$f))			{$i = "cbox";$t = "$cmdlbl";}
	elseif(preg_match("/\.(pcm|raw)$/i",$f))		{$i = "bell";$t = "Ringtone";}
	elseif(preg_match("/\.(bin|loads|img|sbn|swi)$/i",$f))	{$i = "cog"; $t = "Binary Image";}
	elseif(preg_match("/\.(bmp|gif|jpg|png|svg)$/i",$f))	{$i = "img";$t = "$imglbl";}
	elseif(preg_match("/\.(zip|tgz|tar|gz|7z|bz2|rar)$/i",$f)){$i = "pkg"; $t = "Archive";}
	else							{$i = "bbox";$t = "$mlvl[10]";}
	
	return "<img src=\"img/16/$i.png\" title=\"$t\">";
}


?>
