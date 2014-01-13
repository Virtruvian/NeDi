<?
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
$nsd = isset($_GET['nsd']) ? $_GET['nsd'] : "";

$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
$deval  = array();
TopoTable($reg,$cty,$bld,$nsd);
?>
<h1>Topology Table</h1>

<?if( !isset($_GET['print']) ){?>

<table class="content"><tr class="<?=$modgroup[$self]?>1">
<th width="50"><a href="<?=$self?>.php"><img src="img/32/<?=$selfi?>.png"></a></th>
<td>
<?if($bld){
	echo "<img align=\"right\" src=\"img/tel.png\" onclick=\"document.location.href='?$_SERVER[QUERY_STRING]&nsd=1';\"  title=\"$nonlbl SNMP $sholbl\">";

}?>
</td>
</tr></table>
<p>
<?
}

if (!$reg and count($dreg) > 1){
	TopoRegs();
}elseif (!$cty){
	TopoCities($reg);
}elseif (!$bld){
	TopoBuilds($reg,$cty);
}else{
	TopoFloors($reg,$cty,$bld);
}

include_once ("inc/footer.php");

?>
