<?php
# Program: Reports-Devices.php
# Programmer: Remo Rickli (and contributors)

error_reporting(E_ALL ^ E_NOTICE);

$calendar  = 1;
$printable = 1;

include_once ("inc/header.php");
include_once ("inc/libdev.php");
include_once ("inc/librep.php");

$_GET = sanitize($_GET);
$ina = isset($_GET['ina']) ? $_GET['ina'] : "";
$opa = isset($_GET['opa']) ? $_GET['opa'] : "";
$sta = (isset($_GET['sta']) && $ina != "") ? $_GET['sta'] : "";

$rep = isset($_GET['rep']) ? $_GET['rep'] : array();

$lim = isset($_GET['lim']) ? preg_replace('/\D+/','',$_GET['lim']) : 10;

$map = isset($_GET['map']) ? "checked" : "";
$ord = isset($_GET['ord']) ? "checked" : "";
$opt = isset($_GET['opt']) ? "checked" : "";

$cols = array(	"device"=>"Device $namlbl",
		"devip"=>"IP $adrlbl",
		"type"=>"Device $typlbl",
		"firstdis"=>"Device $fislbl $dsclbl",
		"lastdis"=>"Device $laslbl $dsclbl",
		"services"=>$srvlbl,
		"description"=>$deslbl,
		"devos"=>"Device OS",
		"bootimage"=>"Bootimage",
		"location"=>$loclbl,
		"contact"=>$conlbl,
		"group"=>$grplbl,
		"snmpversion"=>"SNMP $verlbl",
		);
?>
<h1>Device Reports</h1>

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
<option value="typ" <?php if(in_array("typ",$rep)){echo "selected";} ?> ><?= $typlbl ?> <?= $dislbl ?>
<option value="sft" <?php if(in_array("sft",$rep)){echo "selected";} ?> >SW <?= $dislbl ?>
<option value="dus" <?php if(in_array("dus",$rep)){echo "selected";} ?> ><?= $duplbl ?> <?= $serlbl ?>
<option value="dui" <?php if(in_array("dui",$rep)){echo "selected";} ?> ><?= $duplbl ?> IP
<option value="grp" <?php if(in_array("grp",$rep)){echo "selected";} ?> ><?= $grplbl ?> <?= $dislbl ?>
<option value="cfg" <?php if(in_array("cfg",$rep)){echo "selected";} ?> ><?= $cfglbl ?>
<option value="pem" <?php if(in_array("pem",$rep)){echo "selected";} ?> >Device PoE
<option value="dli" <?php if(in_array("dli",$rep)){echo "selected";} ?> >Device <?= $cnclbl ?>
<option value="ler" <?php if(in_array("ler",$rep)){echo "selected";} ?> >Link <?= $stalbl ?> <?= $errlbl ?>
<option value="hst" <?php if(in_array("hst",$rep)){echo "selected";} ?> ><?= $dsclbl ?> <?= $hislbl ?>
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
<input type="checkbox" name="ord" <?= $ord ?>>

</th>
<th width="80">
	
<input type="submit" value="<?= $sholbl ?>"></th>
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
	if ( in_array("typ",$rep) ){
		DevType($ina,$opa,$sta,$lim,$ord);
	}
	if ( in_array("sft",$rep) ){
		DevSW($ina,$opa,$sta,$lim,$ord);
	}
	if ( in_array("dus",$rep) ){
		DevDupSer($ina,$opa,$sta,$lim,$ord);
	}
	if ( in_array("dui",$rep) ){
		DevDupIP($ina,$opa,$sta,$lim,$ord);
	}
	if ( in_array("pem",$rep) ){
		DevPoE($ina,$opa,$sta,$lim,$ord);
	}
	if ( in_array("cfg",$rep) ){
		DevConfigs($ina,$opa,$sta,$lim,$ord);
	}
	if ( in_array("grp",$rep) ){
		DevGroup($ina,$opa,$sta,$lim,$ord);
	}
	if ( in_array("hst",$rep) ){
		DevHistory($ina,$opa,$sta,$lim,$ord);
	}

	if ( in_array("dli",$rep) ){
		DevLink($ina,$opa,$sta,$lim,$ord);
	}

	if ( in_array("ler",$rep) ){
		LnkErr($ina,$opa,$sta,$lim,$ord);
	}
}

include_once ("inc/footer.php");
?>
