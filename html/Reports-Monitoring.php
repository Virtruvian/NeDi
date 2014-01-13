<?
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

$lim = isset($_GET['lim']) ? $_GET['lim'] : 10;

$ord = isset($_GET['ord']) ? "checked" : "";
$opt = isset($_GET['opt']) ? "checked" : "";

$cols = array(	"device"=>"Device",
		"devip"=>"IP $adrlbl",
		"type"=>"Device $typlbl",
		"firstdis"=>"$fislbl $dsclbl",
		"lastdis"=>"$laslbl $dsclbl",
		"vtpdomain"=>"VTP Domain",
		"location"=>$loclbl,
		"contact"=>$conlbl,
		"name"=>"$tgtlbl/$srclbl",
		"lastok"=>"$laslbl OK",
		"class"=>$clalbl,
		"test"=>"Test"
		);

?>
<h1>Monitoring Reports</h1>

<?if( !isset($_GET['print']) ){?>

<form method="get" name="report" action="<?=$self?>.php">
<table class="content"><tr class="<?=$modgroup[$self]?>1">
<th width="50"><a href="<?=$self?>.php"><img src="img/32/<?=$selfi?>.png"></a></th>
<th>

<select size="1" name="ina">
<option value=""><?=$fltlbl?>->
<?
foreach ($cols as $k => $v){
       echo "<option value=\"$k\"".( ($ina == $k)?"selected":"").">$v\n";
}
?>
</select>

<select size="1" name="opa">
<? selectbox("oper",$opa);?>
</select>
<p>
<a href="javascript:show_calendar('report.sta');"><img src="img/16/date.png"></a>
<input type="text" name="sta" value="<?=$sta?>" size="20">

</th>
<th>
<select multiple name="rep[]" size="4">
<option value="mav" <?=(in_array("mav",$rep))?"selected":""?>><?=$tgtlbl?> <?=$avalbl?>
<option value="lat" <?=(in_array("lat",$rep))?"selected":""?>><?=$latlbl?> <?=$stslbl?>
<option value="upt" <?=(in_array("upt",$rep))?"selected":""?>>Devices Uptime
<option value="evt" <?=(in_array("evt",$rep))?"selected":""?>><?=$msglbl?> <?=$stslbl?>
<option value="igr" <? if(in_array("igr",$rep)){echo "selected";} ?> >Incident <?=$grplbl?>
<option value="idi" <? if(in_array("idi",$rep)){echo "selected";} ?> >Incident <?=$dislbl?>
<option value="ack" <? if(in_array("ack",$rep)){echo "selected";} ?> >Incident <?=$acklbl?>
<option value="his" <? if(in_array("his",$rep)){echo "selected";} ?> >Incident <?=$hislbl?> 
</select>

</th>
<th>

<?=$limlbl?> 
<select size="1" name="lim">
<? selectbox("limit",$lim);?>
</select>
</th>
<th align="left">

<input type="checkbox" name="ord" <?=$ord?>> <?=$altlbl?> <?=$srtlbl?><br>
<input type="checkbox" name="opt" <?=$opt?>> <?=$optlbl?>

</th>
<th width="80">

<input type="submit" name="do" value="<?=$sholbl?>">

</th></tr></table></form><p>
<?
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
