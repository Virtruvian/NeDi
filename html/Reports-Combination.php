<?php
# Program: Reports-Combination.php
# Programmer: Remo Rickli (and contributors)

error_reporting(E_ALL ^ E_NOTICE);

$printable = 1;
$calendar  = 1;
$exportxls = 0;

include_once ("inc/header.php");
include_once ("inc/libdev.php");
include_once ("inc/librep.php");
include_once ("inc/libmon.php");
include_once ("inc/libnod.php");

$_GET = sanitize($_GET);
$ina = isset($_GET['ina']) ? $_GET['ina'] : "";
$opa = isset($_GET['opa']) ? $_GET['opa'] : "";
$sta = (isset($_GET['sta']) && $ina != "") ? $_GET['sta'] : "";

$rep = isset($_GET['rep']) ? $_GET['rep'] : "";
$gra = isset($_GET['gra']) ? $_GET['gra'] : array();

$lim = isset($_GET['lim']) ? preg_replace('/\D+/','',$_GET['lim']) : 10;
$gsz = isset($_GET['gsz']) ? $_GET['gsz'] : "";

$map = isset($_GET['map']) ? "checked" : "";
$ord = isset($_GET['ord']) ? "checked" : "";
$opt = isset($_GET['opt']) ? "checked" : "";

$cols = array(	"device"=>"Device",
		"devip"=>"IP $adrlbl",
		"type"=>"Device $typlbl",
		"firstdis"=>"$fislbl $dsclbl",
		"lastdis"=>"$laslbl $dsclbl",
		"devgroup"=>$grplbl,
		"location"=>$loclbl,
		"contact"=>$conlbl
		);

$reps = array(	"ass"=>"Assets",
		"pop"=>$poplbl,
		"mon"=>$monlbl,
		"err"=>$errlbl
		);
?>
<h1><?= ($rep)?"$reps[$rep] Report":"Reports $cmblbl" ?></h1>

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
<select name="rep" size="4">
<?php
foreach ($reps as $k => $v){
	echo "<option value=\"$k\" ".(($rep == $k)?" selected":"").">$v\n";
}
?>
</select>

<select multiple size="4" name="gra[]">
<option value="" style="color: blue">- <?= (($verb1)?"$sholbl $gralbl":"$gralbl $sholbl") ?> -
<option value="msg"<?= (in_array("msg",$gra))?" selected":"" ?>> <?= $msglbl ?> <?= $sumlbl ?>
<option value="mon"<?= (in_array("mon",$gra))?" selected":"" ?>> <?= $tgtlbl ?> <?= $avalbl ?>
<option value="nod"<?= (in_array("nod",$gra))?" selected":"" ?>> <?= $totlbl ?> Nodes
<option value="tpw"<?= (in_array("tpw",$gra))?" selected":"" ?>> <?= $totlbl ?> PoE
<option value="ttr"<?= (in_array("ttr",$gra))?" selected":"" ?>> <?= $totlbl ?> non-link <?= $trflbl ?>
<option value="ter"<?= (in_array("ter",$gra))?" selected":"" ?>> <?= $totlbl ?> non-Wlan <?= $errlbl ?>
<option value="ifs"<?= (in_array("ifs",$gra))?" selected":"" ?>> IF <?= $stalbl ?>  <?= $sumlbl ?>
</select>

</th>
<td>

<img src="img/16/form.png" title="<?= $limlbl ?>"> 
<select size="1" name="lim">
<?php selectbox("limit",$lim) ?>
</select>
<p>
<img src="img/16/grph.png" title="<?= $gralbl ?> <?= $sizlbl ?>"> 
<select size="1" name="gsz">
<option value=""><?= $siz['x'] ?>
<option value="4" <?= ($gsz == "4")?" selected":"" ?> ><?= $siz['l'] ?>
<option value="3" <?= ($gsz == "3")?" selected":"" ?> ><?= $siz['m'] ?>
<option value="2" <?= ($gsz == "2")?" selected":"" ?> ><?= $siz['s'] ?>
</select>

</td>
<td align="left">

<img src="img/16/paint.png" title="<?= (($verb1)?"$sholbl $laslbl Map":"Map $laslbl $sholbl") ?>"> 
<input type="checkbox" name="map" <?= $map ?>><br>
<img src="img/16/abc.png" title="<?= $altlbl ?> <?= $srtlbl ?>"> 
<input type="checkbox" name="ord" <?= $ord ?>><br>
<img src="img/16/hat2.png" title="<?= $optlbl ?>"> 
<input type="checkbox" name="opt" <?= $opt ?>>

</td>
<th width="80">

<input type="submit" name="do" value="<?= $sholbl ?>">

</th></tr></table></form><p>
<?php
}
echo "<center>\n";
if ($map and file_exists("map/map_$_SESSION[user].php")) {
	echo "<h2>$netlbl Map</h2>\n";
	echo "<img src=\"map/map_$_SESSION[user].php\" style=\"border:1px solid black\"><p>\n";
}

if($gra[0]){
	echo "<h2>$totlbl $gralbl</h2>\n";
	echo( in_array("msg",$gra) )?"<img src=\"inc/drawrrd.php?&s=$gsz&t=msg&a=$st&e=$en\" title=\"$sholbl Timeline\"></a>\n":"";
	echo( in_array("mon",$gra) )?"<img src=\"inc/drawrrd.php?&s=$gsz&t=mon&a=$st&e=$en\" title=\"$tgtlbl $avalbl\"></a>\n":"";
	echo( in_array("nod",$gra) )?"<img src=\"inc/drawrrd.php?&s=$gsz&t=nod&a=$st&e=$en\" title=\"$totlbl Nodes\">\n":"";
	echo( in_array("tpw",$gra) )?"<img src=\"inc/drawrrd.php?&s=$gsz&t=tpw&a=$st&e=$en\" title=\"$totlbl PoE\">\n":"";
	echo( in_array("ifs",$gra) )?"<img src=\"inc/drawrrd.php?&s=$gsz&t=ifs&a=$st&e=$en\" title=\"IF $stslbl\">\n":"";
	echo( in_array("ttr",$gra) )?"<img src=\"inc/drawrrd.php?&s=$gsz&t=ttr&a=$st&e=$en\" title=\"$totlbl $trflbl\">\n":"";
	echo( in_array("ter",$gra) )?"<img src=\"inc/drawrrd.php?&s=$gsz&t=ter&a=$st&e=$en\" title=\"$totlbl $errlbl\"></a>\n":"";
	echo "<p>\n";
}
echo "</center>\n";

if($sta and !array_key_exists($ina, $cols) ){echo "<h4>($fltlbl $limlbl)</h4>";$sta ="";$ina ="";}

ConHead($ina, $opa, $sta, $cop, $inb, $opb, $stb);
$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
if($rep){
	if($rep == "ass"){
		DevType($ina,$opa,$sta,$lim,$ord);
		DevSW($ina,$opa,$sta,$lim,$ord);
		ModDist($ina,$opa,$sta,$lim,$ord);
		ModInventory($ina,$opa,$sta,$lim,$ord);
	}
	
	if($rep == "pop"){
		NodSum($ina,$opa,$sta,$lim,$ord);
		IntActiv($ina,$opa,$sta,$lim,$ord,$opt);
		NodDist($ina,$opa,$sta,$lim,$ord);
		NetDist($ina,$opa,$sta,$lim,$ord);
		NetPop($ina,$opa,$sta,$lim,$ord);
	}

	if($rep == "mon"){
		MonAvail($ina,$opa,$sta,$lim,$ord);
		IncDist($ina,$opa,$sta,$lim,$ord);
		IncGroup($ina,$opa,$sta,$lim,$ord);
		IncHist($ina,$opa,$sta,$lim,$ord,$opt);
	}

	if($rep == "err"){
		DevDupIP($ina,$opa,$sta,$lim,$ord);
		NodDup($ina,$opa,$sta,$lim,$ord);
		IntErr($ina,$opa,$sta,$lim,$ord,$opt);
		IntDsc($ina,$opa,$sta,$lim,$ord,$opt);
		LnkErr($ina,$opa,$sta,$lim,$ord);
	}
}

include_once ("inc/footer.php");
?>
