<?php
# Program: Reports-Monitoring.php
# Programmer: Remo Rickli

error_reporting(E_ALL ^ E_NOTICE);

$calendar  = 1;
$printable = 1;

include_once ("inc/header.php");
include_once ("inc/libdev.php");
include_once ("inc/libmon.php");
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

$cols = array(	"device"=>"Device",
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
		"name"=>"$tgtlbl/$srclbl",
		"lastok"=>"$laslbl OK",
		"class"=>$clalbl,
		"test"=>$tstlbl
		);

?>
<h1>Monitoring Reports</h1>

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
<option value="mav" <?= (in_array("mav",$rep))?" selected":"" ?>><?= $avalbl ?> <?= $dislbl ?>
<option value="lat" <?= (in_array("lat",$rep))?" selected":"" ?>><?= $latlbl ?> <?= $stslbl ?>
<option value="upt" <?= (in_array("upt",$rep))?" selected":"" ?>>Devices <?= $uptlbl ?>
<option value="evt" <?= (in_array("evt",$rep))?" selected":"" ?>><?= $msglbl ?> <?= $stslbl ?>
<option value="igr" <?php if(in_array("igr",$rep)){echo "selected";} ?> ><?= $inclbl ?> <?= $grplbl ?>
<option value="idi" <?php if(in_array("idi",$rep)){echo "selected";} ?> ><?= $inclbl ?> <?= $dislbl ?>
<option value="ack" <?php if(in_array("ack",$rep)){echo "selected";} ?> ><?= $inclbl ?> <?= $acklbl ?>
<option value="his" <?php if(in_array("his",$rep)){echo "selected";} ?> ><?= $inclbl ?> <?= $hislbl ?> 
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
<img src="img/16/hat2.png" title="<?= $optlbl ?>"> 
<input type="checkbox" name="opt" <?= $opt ?>>

</th>
<th width="80">

<input type="submit" name="do" value="<?= $sholbl ?>">

</th></tr></table></form><p>
<?php
}
if ($map and !isset($_GET['xls']) and file_exists("map/map_$_SESSION[user].php")) {
	echo "<center><h2>$netlbl Map</h2>\n";
	echo "<img src=\"map/map_$_SESSION[user].php\" style=\"border:1px solid black\"></center><p>\n";
}

if($rep){
	ConHead($ina, $opa, $sta, $cop, $inb, $opb, $stb);

	$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
	if ( in_array("mav",$rep) ){
		MonAvail($ina,$opa,$sta,$lim,$ord);
	}
	if ( in_array("lat",$rep) ){
		MonLatency($ina,$opa,$sta,$lim,$ord);
	}
	if ( in_array("upt",$rep) ){
		MonUptime($ina,$opa,$sta,$lim,$ord);
	}
	if ( in_array("evt",$rep) ){
		MonEvent($ina,$opa,$sta,$lim,$ord,$opt);
	}

	if ( in_array("igr",$rep) ){
		IncGroup($ina,$opa,$sta,$lim,$ord);
	}
	if ( in_array("idi",$rep) ){
		IncDist($ina,$opa,$sta,$lim,$ord);
	}
	if ( in_array("ack",$rep) ){
		IncAck($ina,$opa,$sta,$lim,$ord);
	}
	if ( in_array("his",$rep) ){
		IncHist($ina,$opa,$sta,$lim,$ord,$opt);
	}
}

include_once ("inc/footer.php");
?>
