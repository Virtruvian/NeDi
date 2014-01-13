<?
//===============================
// Node related functions.
//===============================

//===================================================================
// Return nodeicon
function Nimg($m) {

	if     (stristr($m,"Apple, Inc"))			{return  "appl1";}
	elseif (stristr($m,"Apple Computer Inc"))		{return  "appl2";}
	elseif (stristr($m,"APPLE"))				{return  "appl3";}
	elseif (stristr($m,"AASTRA"))				{return  "aas";}
	elseif (stristr($m,"ACCTON"))				{return  "acc";}
	elseif (stristr($m,"ABIT"))				{return  "abit";}
	elseif (stristr($m,"ACER"))				{return  "acr";}
	elseif (stristr($m,"ACTIONTEC"))			{return  "atec";}
	elseif (stristr($m,"Acrosser"))				{return  "acs";}
	elseif (stristr($m,"Alcatel-Lucent"))			{return  "alu";}
	elseif (stristr($m,"ADVANTECH"))			{return  "adv";}
	elseif (stristr($m,"ADVANCED DIGITAL INFORMATION"))	{return  "adi";}
	elseif (stristr($m,"ADAPTEC"))				{return  "adt";}
	elseif (stristr($m,"ADVANCED TECHNOLOGY &"))		{return  "adtx";}
	elseif (stristr($m,"AGILENT"))				{return  "agi";}
	elseif (stristr($m,"ALLEN BRAD"))			{return  "ab";}
	elseif (stristr($m,"AMBIT"))				{return  "amb";}
	elseif (stristr($m,"AMERICAN POWER "))			{return  "apc";}
	elseif (stristr($m,"Aopen"))				{return  "aop";}
	elseif (stristr($m,"ASUS"))				{return  "asu";}
	elseif (stristr($m,"Asiarock"))				{return  "asr";}
	elseif (stristr($m,"AVM GmbH"))				{return  "avm";}
	elseif (stristr($m,"AXIS"))				{return  "axis";}
	elseif (stristr($m,"BECKHOFF"))				{return  "bek";}
	elseif (stristr($m,"BROADCOM"))				{return  "bcm";}
	elseif (stristr($m,"BROCADE"))				{return  "brc";}
	elseif (stristr($m,"BROTHER INDUSTRIES"))		{return  "bro";}
	elseif (stristr($m,"Buffalo"))				{return  "buf";}
	elseif (stristr($m,"CANON"))				{return  "can";}
	elseif (stristr($m,"CAB GmbH"))				{return  "cab";}
	elseif (stristr($m,"Cellvision Systems"))		{return  "cev";}
	elseif (stristr($m,"CLEVO CO"))				{return  "clv";}
	elseif (stristr($m,"COMPAQ"))				{return  "q";}
	elseif (stristr($m,"COMPAL"))				{return  "cpl";}
	elseif (stristr($m,"DELL"))				{return  "de";}
	elseif (stristr($m,"D-LINK"))				{return  "dli";}
	elseif (stristr($m,"DIGITAL EQUIPMENT"))		{return  "dec";}
	elseif (stristr($m,"DOT HILL"))				{return  "dhi";}
	elseif (stristr($m,"EDIMAX"))				{return  "edi";}
	elseif (stristr($m,"EGENERA"))				{return  "egn";}
	elseif (stristr($m,"ELECTRONICS FOR IMAGING"))		{return  "efi";}
	elseif (stristr($m,"Elitegroup"))			{return  "ecs";}
	elseif (stristr($m,"EMULEX"))				{return  "emx";}
	elseif (stristr($m,"ENTRADA"))				{return  "ent";}
	elseif (stristr($m,"EPSON"))				{return  "eps";}
	elseif (stristr($m,"FIRST INTERNAT"))			{return  "fic";}
	elseif (stristr($m,";F5"))				{return  "f5";}		# ; to avoid accidental matches on MAC
	elseif (stristr($m,"Fortinet"))				{return  "for";}
	elseif (stristr($m,"FOXCONN"))				{return  "fox";}
	elseif (stristr($m,"FUJITSU"))				{return  "fs";}
	elseif (stristr($m,"GemTek Technology"))		{return  "gmt";}
	elseif (stristr($m,"GIGA-BYTE"))			{return  "gig";}
	elseif (stristr($m,"High Tech Computer"))		{return  "htc";}
	elseif (stristr($m,"Hon Hai Precision"))		{return  "amb";}
	elseif (stristr($m,"IBM"))				{return  "ibm";}
	elseif (stristr($m,"INTEL"))				{return  "int";}
	elseif (stristr($m,"INTERFLEX"))			{return  "intr";}
	elseif (stristr($m,"INTERGRAPH"))			{return  "igr";}
	elseif (stristr($m,"INVENTEC CORPORATION"))		{return  "inv";}
	elseif (stristr($m,"IWILL"))				{return  "iwi";}
	elseif (stristr($m,"KABA"))				{return  "kaba";}
	elseif (stristr($m,"KINGSTON"))				{return  "ktc";}
	elseif (stristr($m,"KYOCERA"))				{return  "kyo";}
	elseif (stristr($m,"LANCOM"))				{return  "lac";}
	elseif (stristr($m,"LANTRONIX"))			{return  "ltx";}
	elseif (stristr($m,"LEXMARK"))				{return  "lex";}
	elseif (stristr($m,"LITE-ON Communications"))		{return  "lio";}
	elseif (stristr($m,"Microsoft Corporation"))		{return  "ms";}
	elseif (stristr($m,"MINOLTA"))				{return  "min";}
	elseif (stristr($m,"LINKSYS"))				{return  "lsy";}
	elseif (stristr($m,"MICRO-STAR"))			{return  "msi";}
	elseif (stristr($m,"MITAC INTERNATIONAL"))		{return  "mit";}
	elseif (stristr($m,"MSI"))				{return  "msi";}
	elseif (stristr($m,"MOTOROLA"))				{return  "mot";}
	elseif (stristr($m,"NATIONAL INSTRUMENTS"))		{return  "ni";}
	elseif (stristr($m,"NETWORK COMP"))			{return  "ncd";}
	elseif (stristr($m,"NETGEAR"))				{return  "ngr";}
	elseif (stristr($m,"NEXT"))				{return  "nxt";}
	elseif (stristr($m,"NOKIA"))				{return  "nok";}
	elseif (stristr($m,"OVERLAND"))				{return  "ovl";}
	elseif (stristr($m,"PLANET"))				{return  "pla";}
	elseif (stristr($m,"PAUL SCHERRER"))			{return  "psi";}
	elseif (stristr($m,"PHILIPS"))				{return  "plp";}
	elseif (stristr($m,"POLYCOM"))				{return  "ply";}
	elseif (stristr($m,"PROXIM"))				{return  "prx";}
	elseif (stristr($m,"QUANTA"))				{return  "qnt";}
	elseif (stristr($m,"RARITAN"))				{return  "rar";}
	elseif (stristr($m,"RAD DATA"))				{return  "rad";}
	elseif (stristr($m,"REALTEK"))				{return  "rtk";}
	elseif (stristr($m,"RICOH"))				{return  "rco";}
	elseif (stristr($m,"RUBY TECH"))			{return  "rub";}
	elseif (stristr($m,"SAMSUNG"))				{return  "sam";}
	elseif (stristr($m,"SERCOM"))				{return  "ser";}
	elseif (stristr($m,"SILICON GRAPHICS"))			{return  "sgi";}
	elseif (stristr($m,"SHIVA"))				{return  "sva";}
	elseif (stristr($m,"SHUTTLE"))				{return  "shu";}
	elseif (stristr($m,"SIEMENS"))				{return  "si";}
	elseif (stristr($m,"SNOM"))				{return  "snom";}
	elseif (stristr($m,"Sony Ericsson"))			{return  "se";}
	elseif (stristr($m,"Sony Computer Entertainment"))	{return  "sps";}
	elseif (stristr($m,"SONY"))				{return  "sony";}
	elseif (stristr($m,"STRATUS"))				{return  "sts";}
	elseif (stristr($m,"SUN MICROSYSTEMS"))			{return  "sun";}
	elseif (stristr($m,"SUPERMICRO"))			{return  "sum";}
	elseif (stristr($m,"HUGHES"))				{return  "wsw";}
	elseif (stristr($m,"FOUNDRY"))				{return  "fdry";}
	elseif (stristr($m,"NUCLEAR"))				{return  "atom";}
	elseif (stristr($m,"TECO INFORMATION "))		{return  "tec";}
	elseif (stristr($m,"TEKTRONIX"))			{return  "tek";}
	elseif (stristr($m,"TOSHIBA"))				{return  "tsa";}
	elseif (stristr($m,"TYAN"))				{return  "tya";}
	elseif (stristr($m,"U.S. Robotics"))			{return  "usr";}
	elseif (stristr($m,"USC CORPORATION"))			{return  "usc";}
	elseif (stristr($m,"USI"))				{return  "usi";}
	elseif (stristr($m,"VMWARE"))				{return  "vm";}
	elseif (stristr($m,"VIA TECHNOLOGIES"))			{return  "via";}
	elseif (stristr($m,"WESTERN"))				{return  "wdc";}
	elseif (stristr($m,"WIESEMANN & THEIS"))		{return  "wt";}
	elseif (stristr($m,"WISTRON"))				{return  "wis";}
	elseif (stristr($m,"WYSE"))				{return  "wys";}
	elseif (stristr($m,"WW PCBA"))				{return  "de";}
	elseif (stristr($m,"XYLAN"))				{return  "xylan";}
	elseif (stristr($m,"XEROX"))				{return  "xrx";}
	elseif (preg_match("/3\s*COM|MEGAHERTZ/i",$m))		{return  "3com";}
	elseif (preg_match("/AIRONET|CISCO/i",$m))		{return  "cis";}
	elseif (preg_match("/AVAYA|LANNET/i",$m))		{return  "ava";}
	elseif (preg_match("/BAY|NORTEL|NETICS|XYLOGICS/i",$m))	{return  "nort";}
	elseif (preg_match("/EMC|CLARIION/i",$m))		{return  "emc";}
	elseif (preg_match("/HEWLETT|ProCurve|Colubris|Hangzhou|Palm,|3 par/i",$m))	{return  "hp";}
	elseif (preg_match("/JUNIPER|PERIBIT|Netscreen/i",$m))  {return  "jun";}
	elseif (preg_match("/SMC Net|STANDARD MICROSYS/i",$m))	{return  "smc";}
	elseif (preg_match("/ZYXEL|ZyGate/i",$m))		{return  "zyx";}
	else							{return  "gen";}
}

//===================================================================
// Emulate good old nbtstat on port 137
function NbtStat($ip) {

	$nbts	= pack('C50',129,98,00,00,00,01,00,00,00,00,00,00,32,67,75,65,65,65,65,65,65,65,65,65,65,65,65,65,65,65,65,65,65,65,65,65,65,65,65,65,65,65,65,65,65,00,00,33,00,01);
	$fp		= @fsockopen("udp://$ip", 137, $errno, $errstr);
	if (!$fp) {
		return "ERROR! $errno $errstr";
	}else {
		fwrite($fp, "$nbts");
		stream_set_timeout($fp, 0, 1000000 );
		$data =  fread($fp, 400);
		fclose($fp);

		if (preg_match("/AAAAAAAAAA/",$data) ){
			$nna = unpack('cnam',substr($data,56,1));  							# Get number of names
			$out = substr($data,57);                							# get rid of WINS header

			for ($i = 0; $i < $nna['nam'];$i++){
				$nam = preg_replace("/ +/","",substr($out,18*$i,15));
				$id = unpack('cid',substr($out,18*$i+15,1));
				$fl = unpack('cfl',substr($out,18*$i+16,1));
				$na = "";
				$gr = "";
				$co = "";
				if ($fl['fl'] > 0){
					if ($id['id'] == "3"){
						if ($na == ""){
							$na = $nam;
						}else{
							$co = $nam;
						}
					}
				}else{
					if ($na == ""){
						$gr = $nam;
					}
				}
			}
			return "<img src=\"img/16/bchk.png\"> $na $gr $co";
		}else{
			return "<img src=\"img/16/bstp.png\"> No response";
		}
	}
}

//===================================================================
// Check for open port and return server information, if possible.
function CheckTCP ($ip, $p,$d){

	global $debug, $sndlbl;

	if ($ip == "0.0.0.0") {
		return "<img src=\"img/16/bcls.png\"> No IP!";
	}else{
		if($debug){echo "<div class=\"textpad noti \">$sndlbl $ip:$p \"$d\"</div>\n";}

		$fp = @fsockopen($ip, $p, $errno, $errstr, 1 );

		flush();
		if (!$fp) {
			return "<img src=\"img/16/bstp.png\"> $errstr";
		} else {
			fwrite($fp,$d);
			stream_set_timeout($fp, 0, 500000 );
			$ans = fread($fp, 255);
			$ans .= fread($fp, 255);
			$ans .= fread($fp, 255);
			fclose($fp);

			if( preg_match("/Server:(.*)/i",$ans,$mstr) ){
				$srv = "<i>$mstr[1]</i>";
			}else{
				$srv = "";
			}
			if( preg_match("/<address>(.*)<\/address>/i",$ans,$mstr) ){
				return "<img src=\"img/16/bchk.png\"> $mstr[1] $srv";
			}elseif( preg_match("/<title>(.*)<\/title>/i",$ans,$mstr) ){
				return "<img src=\"img/16/bchk.png\"> $mstr[1] $srv";
			}elseif( preg_match("/content=\"(.*)\">/i",$ans,$mstr) ){
				return "<img src=\"img/16/bchk.png\"> $mstr[1] $srv";
			}else{
				$mstr = substr(preg_replace("/[^\x20-\x7e]|<!|!>|(<script.*)/i",'',$ans),0,50);
				return "<img src=\"img/16/bchk.png\"> $mstr $srv";
			}
		}
	}
}

//===================================================================
// Create and send magic packet (copied from the PHP webiste)
function wake($ip, $mac, $port){
	$nic = fsockopen("udp://" . $ip, $port);
	if($nic){
		$packet = "";
		for($i = 0; $i < 6; $i++)
			$packet .= chr(0xFF);
		for($j = 0; $j < 16; $j++){
			for($k = 0; $k < 6; $k++){
				$str = substr($mac, $k * 2, 2);
				$dec = hexdec($str);
				$packet .= chr($dec);
			}
		}
		$ret = fwrite($nic, $packet);
		fclose($nic);
		if($ret)
			return true;
	}
	return false;
}

?>
