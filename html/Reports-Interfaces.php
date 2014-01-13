<?php
# Program: Reports-Interfaces.php
# Programmer: Remo Rickli

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
		"ifname"=>"IF $namlbl",
		"iftype"=>"IF $typlbl",
		"linktype"=>"Link $typlbl",
		"ifdesc"=>"IF $deslbl",
		"comment"=>"IF $cmtlbl",
		"vlid"=>"Vlan ID",
		"alias"=>"Alias",
		"lastchg"=>"$laslbl $chglbl"
		);
?>
<h1>Interface Reports</h1>

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
<option value="use" <?php if(in_array("use",$rep)){echo "selected";} ?> >IF <?= $stco['100'] ?>
<option value="dis" <?php if(in_array("dis",$rep)){echo "selected";} ?> >IF <?= $dsalbl ?>
<option value="poe" <?php if(in_array("poe",$rep)){echo "selected";} ?> >PoE <?= $stslbl ?>
<option value="trf" <?php if(in_array("trf",$rep)){echo "selected";} ?> ><?= $trflbl ?>
<option value="err" <?php if(in_array("err",$rep)){echo "selected";} ?> ><?= $errlbl ?>
<option value="dsc" <?php if(in_array("dsc",$rep)){echo "selected";} ?> >Discards
<option value="brc" <?php if(in_array("brc",$rep)){echo "selected";} ?> >Broadcasts
<option value="net" <?php if(in_array("net",$rep)){echo "selected";} ?> ><?= $netlbl ?> <?= $dislbl ?>
<option value="pop" <?php if(in_array("pop",$rep)){echo "selected";} ?> ><?= $netlbl ?> <?= $poplbl ?>
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

<input type="submit" name="do" value="<?= $sholbl ?>"></th>
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

	if ( in_array("use",$rep) ){
		IntActiv($ina,$opa,$sta,$lim,$ord,$opt);
	}
	if ( in_array("poe",$rep) ){
		IntPoE($ina,$opa,$sta,$lim,$ord);
	}
	if ( in_array("dis",$rep) ){
		IntDis($ina,$opa,$sta,$lim,$ord);
	}
	if ( in_array("trf",$rep) ){
		IntTrf($ina,$opa,$sta,$lim,$ord,$opt);
	}
	if ( in_array("err",$rep) ){
		IntErr($ina,$opa,$sta,$lim,$ord,$opt);
	}
	if ( in_array("dsc",$rep) ){
		IntDsc($ina,$opa,$sta,$lim,$ord,$opt);
	}
	if ( in_array("brc",$rep) ){
		IntBrc($ina,$opa,$sta,$lim,$ord,$opt);
	}
	if ( in_array("net",$rep) ){
		NetDist($ina,$opa,$sta,$lim,$ord);
	}

	if ( in_array("pop",$rep) ){
		NetPop($ina,$opa,$sta,$lim,$ord);
	}
}

include_once ("inc/footer.php");

?>
