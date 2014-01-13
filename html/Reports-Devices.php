<?
# Program: Reports-Devices.php
# Programmer: Remo Rickli (and contributors)

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
		"vtpdomain"=>"VTP Domain"
		);
?>
<h1>Device Reports</h1>

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
<option value="typ" <? if(in_array("typ",$rep)){echo "selected";} ?> ><?=$typlbl?> <?=$dislbl?>
<option value="sft" <? if(in_array("sft",$rep)){echo "selected";} ?> >Software <?=$dislbl?>
<option value="dus" <? if(in_array("dus",$rep)){echo "selected";} ?> ><?=$duplbl?> Serials
<option value="dui" <? if(in_array("dui",$rep)){echo "selected";} ?> ><?=$duplbl?> IPs
<option value="vtp" <? if(in_array("vtp",$rep)){echo "selected";} ?> >VTP <?=$dislbl?>
<option value="cfg" <? if(in_array("cfg",$rep)){echo "selected";} ?> ><?=$cfglbl?>
<option value="dli" <? if(in_array("dli",$rep)){echo "selected";} ?> >Device <?=$cnclbl?>
<option value="ler" <? if(in_array("ler",$rep)){echo "selected";} ?> >Link <?=$stalbl?> <?=$errlbl?>
<option value="hst" <? if(in_array("hst",$rep)){echo "selected";} ?> ><?=$dsclbl?> <?=$hislbl?>
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
	
<input type="submit" value="<?=$sholbl?>"></th>
</tr></table></form><p>
<?
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
	if ( in_array("cfg",$rep) ){
		DevConfigs($ina,$opa,$sta,$lim,$ord);
	}
	if ( in_array("vtp",$rep) ){
		DevVTP($ina,$opa,$sta,$lim,$ord);
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
