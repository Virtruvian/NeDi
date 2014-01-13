<?php
# Program: Reports-Nodes.php
# Programmer: Remo Rickli (and contributors) 

error_reporting(E_ALL ^ E_NOTICE);

$calendar  = 1;
$printable = 1;

include_once ("inc/header.php");
include_once ("inc/libnod.php");
include_once ("inc/librep.php");

$_GET = sanitize($_GET);
$ina = isset($_GET['ina']) ? $_GET['ina'] : "";
$opa = isset($_GET['opa']) ? $_GET['opa'] : "";
$sta = (isset($_GET['sta']) && $ina != "") ? $_GET['sta'] : "";

$rep = isset($_GET['rep']) ? $_GET['rep'] : array();

$lim = isset($_GET['lim']) ? preg_replace('/\D+/','',$_GET['lim']) : 10;

$map = isset($_GET['map']) ? "checked" : "";
$ord = isset($_GET['ord']) ? "checked" : "";

$cols = array(	"device"=>"Device $namlbl",
		"devip"=>"IP $adrlbl",
		"type"=>"Device $typlbl",
		"firstdis"=>"$fislbl $dsclbl",
		"lastdis"=>"$laslbl $dsclbl",
		"services"=>$srvlbl,
		"description"=>$deslbl,
		"devos"=>"Device OS",
		"bootimage"=>"Bootimage",
		"location"=>$loclbl,
		"contact"=>$conlbl,
		"group"=>$grplbl,
		"snmpversion"=>"SNMP $verlbl",
		"name"=>"Node $namlbl",
		"nodip"=>"Node IP",
		"oui"=>$venlbl,
		"firstseen"=>$fislbl,
		"lastseen"=>$laslbl,
		"vlanid"=>"Vlan ID",
		"ifmetric"=>"IF $metlbl",
		"ifupdate"=>"IF $updlbl",
		"ifchanges"=>"IF $chglbl",
		"ipupdate"=>"IP $updlbl",
		"ipchanges"=>"IP $chglbl",
		"tcpports"=>"TCP $porlbl",
		"udpports"=>"UDP $porlbl",
		"nodtype"=>"Node $typlbl",
		"nodos"=>"Node OS",
		"osupdate"=>"OS $updlbl"
		);
?>
<h1>Node Reports</h1>

<?php  if( !isset($_GET['print']) ) { ?>

<form method="get" name="report" action="<?= $self ?>.php">
<table class="content"><tr class="<?= $modgroup[$self] ?>1">
<th width="50"><a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png"></a></th>
<th>

<select size="1" name="ina">
<option value=""><?= $fltlbl ?>->
<?php
foreach ($cols as $k => $v){
       echo "<option value=\"$k\"".( ($ina == $k)?" selected":"").">$v\n";
}
?>
</select>

<select size="1" name="opa">
<?php selectbox("oper",$opa) ?>
</select>
<p>
<a href="javascript:show_calendar('report.sta');"><img src="img/16/date.png"></a>
<input type="text" name="sta" value="<?= $sta ?>" size="20">

</th>
<th>

<select multiple name="rep[]" size="4">
<option value="sum" <?php if(in_array("sum",$rep)){echo "selected";} ?> ><?= $sumlbl ?>
<option value="dis" <?php if(in_array("dis",$rep)){echo "selected";} ?> >Node <?= $dislbl ?>
<option value="dup" <?php if(in_array("dup",$rep)){echo "selected";} ?> ><?= $duplbl ?> Nodes
<option value="nos" <?php if(in_array("nos",$rep)){echo "selected";} ?> >OS <?= $stslbl ?>
<option value="nom" <?php if(in_array("nom",$rep)){echo "selected";} ?> ><?= $nomlbl ?>
<option value="vem" <?php if(in_array("vem",$rep)){echo "selected";} ?> ><?= (($verb1)?"$emplbl Vlans":"Vlans $emplbl") ?>
<option value="nhs" <?php if(in_array("nhs",$rep)){echo "selected";} ?> ><?= $dsclbl ?> <?= $hislbl ?>
</select>

</th>
<th>

<img src="img/16/form.png" title="<?= $limlbl ?>"> 
<select size="1" name="lim">
<?php selectbox("limit",$lim) ?>
</select>

</th>
<th align="left">

<img src="img/16/paint.png" title="<?= (($verb1)?"$sholbl $laslbl Map":"Map $laslbl $sholbl") ?>"> 
<input type="checkbox" name="map" <?= $map ?>><br>
<img src="img/16/abc.png" title="<?= $altlbl ?> <?= $srtlbl ?>"> 
<input type="checkbox" name="ord" <?= $ord ?>><br>

</th>
<th width="80">
	
<input type="submit" name="gen" value="<?= $sholbl ?>"></th>

</tr></table></form><p>
<?php
}
if ($map and !isset($_GET['xls']) and file_exists("map/map_$_SESSION[user].php")) {
	echo "<center><h2>$netlbl Map</h2>\n";
	echo "<img src=\"map/map_$_SESSION[user].php\" style=\"border:1px solid black\"></center><p>\n";
}

if($rep){
	ConHead($ina, $opa, $sta, $cop, $inb, $opb, $stb);
	$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);

	if ( in_array("sum",$rep) ){
		NodSum($ina,$opa,$sta,$lim,$ord);
	}
	if ( in_array("dup",$rep) ){
		NodDup($ina,$opa,$sta,$lim,$ord);
	}
	if ( in_array("dis",$rep) ){
		NodDist($ina,$opa,$sta,$lim,$ord);
	}
	if ( in_array("nos",$rep) ){
		NodOS($ina,$opa,$sta,$lim,$ord);
	}
	if ( in_array("nom",$rep) ){
		NodNomad($ina,$opa,$sta,$lim,$ord);
	}
	if ( in_array("nhs",$rep) ){
		NodHistory($ina,$opa,$sta,$lim,$ord);
	}
	if ( in_array("vem",$rep) ){
		VlanEmpty($ina,$opa,$sta,$lim,$ord);
	}
}

include_once ("inc/footer.php");
?>
