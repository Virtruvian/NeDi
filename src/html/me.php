<?php
# Program: me.php (Locate me IP)
# Programmer: Remo Rickli

error_reporting(E_ALL ^ E_NOTICE);

$refresh   = 60;
$printable = 0;

$_SESSION['gsiz'] = 6;
$_SESSION['lsiz'] = 8;
$_SESSION['view'] = "";
$_SESSION['timf'] = 'j.M y G:i';
$_SESSION['tz'] = "GMT";

require_once ("inc/libmisc.php");
ReadConf();
include_once ("./languages/english/gui.php");							# Don't require, GUI still works if missing
include_once ("inc/libdb-" . strtolower($backend) . ".php");
require_once ("inc/libnod.php");
include_once ("inc/libdev.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>

<head>
<title>NeDi Find Me</title>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1">
<link href="inc/print.css" type="text/css" rel="stylesheet">
<link rel="shortcut icon" href="img/favicon.ico">
</head>

<body>
<script src="inc/Chart.min.js"></script>

<?php
$link  = DbConnect($dbhost,$dbuser,$dbpass,$dbname);
$query = GenQuery('nodes','s','nodes.*,location,speed,duplex,pvid,dinoct,doutoct,dinerr,douterr,dindis,doutdis,dinbrc,nodip,aname','lastseen','1',array('nodip'),array('='),array( ip2long($_SERVER[REMOTE_ADDR]) ),array(),'LEFT JOIN devices USING (device) LEFT JOIN nodarp USING (mac) LEFT JOIN dns USING (nodip) LEFT JOIN interfaces USING (device,ifname)');
$res   = DbQuery($query,$link);
if($res){
	$n = DbFetchRow($res);
	if($n[2]){
		$img = Nimg($n[1]);
		$l   = explode($locsep,$n[12]);
		echo "<table class=\"xxl\">";
		echo "<tr class=\"bgmain\"><td class=\"imga ctr xs\"><img src=\"img/oui/$img.png\" title=\"$n[1]\"></td><td><strong>$n[24]</strong></td><td class=\"mrn code\">$n[0]</td></tr>\n";
		echo "<tr class=\"txtb\"><td class=\"imgb ctr\"><img src=\"img/16/net.png\" title=\"Network\"></td><td class=\"blu code\">".long2ip($n[23])."</td><td title=\"$laslbl\">".date($_SESSION['timf'],$n[2])."</td></tr>\n";
		echo "<tr class=\"txta\"><td class=\"imga ctr\"><img src=\"img/16/dev.png\" title=\"Device\"></td><td>$n[4]</td><td>$l[2] $l[3]</td></tr>\n";
		echo "<tr class=\"txtb\"><td class=\"imgb ctr\"><img src=\"img/16/port.png\" title=\"Interface\"></td><td>$n[5]</td><td>".DecFix($n[13])."-$n[14] vl$n[6]</td></tr>\n";
		echo "<tr class=\"txta\"><td class=\"imga ctr\"><img src=\"img/16/grph.png\" title=\"In/Out\"></td><td colspan=\"2\">\n";
		MetricChart("met",4, $n[7]);
		IfRadar('radlast',4,'284',$n[16],$n[17],$n[18],$n[19],$n[20],$n[21],$n[22],1);
		echo "</td></tr>\n";
		echo "</table>";
	}else{
		echo "<h4>$_SERVER[REMOTE_ADDR] was not found</h4>";
	}
	DbFreeResult($res);
}else{
	print DbError($link);
}
?>

</body>
