<?
# Program: Reports-Interfaces.php
# Programmer: Remo Rickli

error_reporting(E_ALL ^ E_NOTICE);

$calendar  = 1;
$printable = 1;

include_once ("inc/header.php");
include_once ("inc/libdev.php");
include_once ("inc/librep.php");

$_GET = sanitize($_GET);
$rep = isset($_GET['rep']) ? $_GET['rep'] : array();
$ina = isset($_GET['ina']) ? $_GET['ina'] : "";
$opa = isset($_GET['opa']) ? $_GET['opa'] : "";
$sta = (isset($_GET['sta']) && $ina != "") ? $_GET['sta'] : "";

$lim = isset($_GET['lim']) ? $_GET['lim'] : 10;

$ord = isset($_GET['ord']) ? "checked" : "";

$cols = array(	"device"=>"Device",
		"devip"=>"IP $adrlbl",
		"type"=>"Device $typlbl",
		"firstdis"=>"$fislbl $dsclbl",
		"lastdis"=>"$laslbl $dsclbl",
		"vtpdomain"=>"VTP Domain",
		"location"=>$loclbl,
		"contact"=>$conlbl,
		"ifname"=>"IF $namlbl",
		"iftype"=>"IF $typlbl",
		"linktype"=>"Link $typlbl",
		"ifdesc"=>"IF $deslbl",
		"comment"=>"IF $cmtlbl",
		"vlid"=>"Vlan ID",
		"alias"=>"Alias"
		);
?>
<h1>Interface Reports</h1>

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
<option value="use" <? if(in_array("use",$rep)){echo "selected";} ?> >IF <?=$stco['100']?>
<option value="dis" <? if(in_array("dis",$rep)){echo "selected";} ?> >Disabled IF
<option value="poe" <? if(in_array("poe",$rep)){echo "selected";} ?> >PoE <?=$stslbl?>
<option value="trf" <? if(in_array("trf",$rep)){echo "selected";} ?> ><?=$trflbl?>
<option value="err" <? if(in_array("err",$rep)){echo "selected";} ?> ><?=$errlbl?>
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

</th>
<th width="80">

<input type="submit" name="do" value="<?=$sholbl?>"></th>
</tr></table></form><p>
<?
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
		IntTrf($ina,$opa,$sta,$lim,$ord);
	}
	if ( in_array("err",$rep) ){
		IntErr($ina,$opa,$sta,$lim,$ord);
	}
}

include_once ("inc/footer.php");

?>
