<?php
# Program: Topology-Table.php
# Programmer: Remo Rickli

error_reporting(E_ALL ^ E_NOTICE);

$printable = 1;

include_once ("inc/header.php");
include_once ("inc/libdev.php");
include_once ("inc/libmon.php");

$_GET = sanitize($_GET);
$reg = isset($_GET['reg']) ? $_GET['reg'] : "";
$cty = isset($_GET['cty']) ? $_GET['cty'] : "";
$bld = isset($_GET['bld']) ? $_GET['bld'] : "";
$flr = isset($_GET['fl']) ? $_GET['fl'] : "";
$rom = isset($_GET['rm']) ? $_GET['rm'] : "";
$nsd = isset($_GET['nsd']) ? $_GET['nsd'] : "";

?>
<h1>Topology Table</h1>

<?php
$deval = array();
$link  = @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
TopoTable($reg,$cty,$bld,$flr,$rom,$nsd);

if( count($dreg) == 1 ){
	$reg = array_pop ( array_keys($dreg) );
	if( count($dcity[$reg]) == 1 ){
		$cty = array_pop ( array_keys($dcity[$reg]) );
	}
}

if( !isset($_GET['print']) ) { ?>
<table class="content"><tr class="<?= $modgroup[$self] ?>1">
<th width="50"><a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png"></a></th>
<td>

<?php
echo "<div style=\"float:right\">\n";
if($cty){echo "<a href=\"?reg=".urlencode($reg)."\"><img src=\"img/16/glob.png\" title=\"$place[r] $reg\"></a>";}
if($bld){echo "<a href=\"?reg=".urlencode($reg)."&cty=".urlencode($cty)."\"><img src=\"img/16/map.png\" title=\"$place[c] $cty\"></a>";}
if($flr){echo "<a href=\"?reg=".urlencode($reg)."&cty=".urlencode($cty)."&bld=".urlencode($bld)."\"><img src=\"img/16/home.png\" title=\"$place[b] $bld\"></a>";}
if($bld and !$rom){
	if($nsd){
		echo "<img src=\"img/16/bcls.png\" onclick=\"document.location.href='?".str_replace("&nsd=1","",$_SERVER[QUERY_STRING])."';\"  title=\"$nonlbl SNMP hide\">\n";
	}else{
		echo "<img src=\"img/16/wlan.png\" onclick=\"document.location.href='?$_SERVER[QUERY_STRING]&nsd=1';\"  title=\"$nonlbl SNMP $sholbl\">\n";
	}
}
echo "</div></td></tr></table><p>\n";
}

if(!$reg){
	TopoRegs();
}elseif (!$cty){
	TopoCities($reg);
}elseif (!$bld){
	TopoBuilds($reg,$cty);
}elseif (!$rom){
	TopoFloors($reg,$cty,$bld);
}else{
	TopoRoom($reg,$cty,$bld,$flr,$rom);
}

include_once ("inc/footer.php");

?>
