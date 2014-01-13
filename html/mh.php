<?
# Program: indexm.php
# Programmer: Remo Rickli

error_reporting(E_ALL ^ E_NOTICE);

$refresh   = 60;
$printable = 0;

$_SESSION['lim']  = 3;
$_SESSION['col']  = 4;
$_SESSION['gsiz'] = 6;
$_SESSION['lsiz'] = 8;
$_SESSION['view'] = "";
$_SESSION['date'] = 'j.M y G:i';

require_once ("inc/libmisc.php");
ReadConf('mon');
include_once ("./languages/english/gui.php");							# Don't require, GUI still works if missing
include_once ("inc/libdb-" . strtolower($backend) . ".php");
include_once ("inc/libdev.php");
include_once ("inc/libmon.php");

$self     = preg_replace("/.*\/(.+).php/","$1",$_SERVER['SCRIPT_NAME']);

$modgroup[$self] = 'mon';

$_GET = sanitize($_GET);
$reg = isset($_GET['reg']) ? $_GET['reg'] : "";
$cty = isset($_GET['cty']) ? $_GET['cty'] : "";
$bld = isset($_GET['bld']) ? $_GET['bld'] : "";
$loc = TopoLoc($reg,$cty,$bld);

$slow  = array();
$link  = @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
$query = GenQuery('monitoring','s','name,lastok,status,latency','name','',array('test','location'),array('regexp','regexp'),array('.',$loc),array('AND'),'LEFT JOIN devices USING (device)');
#TODO optmize to only use what's needed -> several queries?
$res	= @DbQuery($query,$link);
if($res){
	$nmon= 0;
	$mal = 0;
	$lok = 0;
	while( ($m = @DbFetchRow($res)) ){
		if($m[1] > $lok){$lok = $m[1];}
		$deval[$m[0]] = $m[2];
		if($m[2]){$mal++;}
		if($m[3] > $latw){$slow[$m[0]] = $m[3];}
		$nmon++;
	}
	@DbFreeResult($res);
}else{
	print @DbError($link);
}

$monok = 0;
if( time() < (2*$pause + $lok) ){$monok = 1;}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>

<head>
<title>NeDi Mobile Health</title>
<meta http-equiv="refresh" content="60">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1">
<link href="inc/print.css" type="text/css" rel="stylesheet">
<link rel="shortcut icon" href="img/favicon.ico">
</head>

<body>

<table width="640"><tr class="<?=$modgroup[$self]?>2">
<td valign="top" align="center">
<p>
<a href="mh.php"><img src="img/32/dev.png" title="<?=$nmon?> <?=$tgtlbl?> Monitored"></a>
<?

if($mal == 0){
	if($monok){
		echo "<img src=\"img/32/bchk.png\" title=\"moni.pl OK\">";
	}else{
		echo "<img src=\"img/32/bcls.png\" title=\"moni.pl Down!\">";
	}
}else{
	if($mal == 1){
		echo "<img src=\"img/32/foye.png\" title=\"1 $mlvl[200]\">";
	}elseif($mal < 10){
		if($ni[0] < 3){
			$ico = "fovi";
		}elseif($ni[0] < 5){
			$ico = "foye";
		}else{
			$ico = "foor";
		}
		echo "<img src=\"img/32/$ico.png\" title=\"$mal $mlvl[200]\">";
	}else{
		echo "<img src=\"img/32/ford.png\" title=\"$mal $mlvl[200]!\">";
	}

?>
<p>
<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th><img src="img/16/dev.png"><br>Device</th><th><img src="img/16/flag.png"><br><?=$mlvl['200']?></th>
<?
	$row = 0;
	foreach(array_keys($deval) as $d){
		if($deval[$d]){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$t = substr($d,0,$_SESSION['lsiz']);					# Shorten targets
			list($statbg,$stat) = StatusBg(1,1,$deval[$d],$bi);
			echo "<tr class=\"$bg\"><td>\n";
			echo "<b>$t</b></a></td><td class=\"$statbg\">$stat</td></tr>\n";
		}
	}
?>
</table>
<?
}
StatusIncidents($loc);

if( count($slow) ){
?>
<p>
<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th><img src="img/16/dev.png"><br>Device</th><th><img src="img/16/clock.png"><br><?=$latlbl?></th>
<?
	$row = 0;
	foreach(array_keys($slow) as $d){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		$t    = substr($d,0,$_SESSION['lsiz']);						# Shorten targets
		$dbar = Bar($slow[$d],$latw,'si');
		echo "<tr class=\"$bg\"><td><b>$t</b></td><td>$dbar $slow[$d]ms</td></tr>\n";
	}
?>
</table>
<?
}
?>

</td>
<td valign="top" align="center">

<?

StatusIf($loc,'bbup',$inblbl);
StatusIf($loc,'bbdn',$oublbl);

$query	= GenQuery('interfaces','s','count(*),round(sum(poe)/1000)','','',array('poe','location'),array('>','regexp'),array('0',$loc),array('AND'),'JOIN devices USING (device)');
$res	= @DbQuery($query,$link);
if($res){
	$m = @DbFetchRow($res);
	if($m[0]){echo "<p><b><img src=\"img/32/batt.png\" title=\"$m[0] PoE IF\">$m[1] W</b>\n";}
	@DbFreeResult($res);
}else{
	print @DbError($link);
}
?>

</td>
<td valign="top" align="center">

<?
StatusIf($loc,'brup',$inblbl);
StatusIf($loc,'brdn',$oublbl);
StatusIf($loc,'bdis',"Disabled IF $tim[n]");
?>

</td>
<td valign="top" align="center">

<?
StatusCpu($loc);
StatusMem($loc);
StatusTmp($loc);
?>

</td></tr>
<tr><td colspan="4">

<h2><?=$mlvl[200]?> & <?=$mlvl[250]?> <?=$tim['t']?></h2>
<?
	$firstmsg = time() - 86400;
	Events($_SESSION['lim'],array('level','time','location'),array('>=','>','regexp'),array(200,$firstmsg,$loc),array('AND','AND'),2);

TopoTable($reg,$cty,$bld);

if(!$reg and count($dreg) > 1){
	TopoRegs(1);
}elseif(!$cty){
	TopoCities($reg,1);
}elseif(!$bld){
	TopoBuilds($reg,$cty,1);
}else{
	TopoFloors($reg,$cty,$bld,1);
}

?>

</tr></table>
</body>
