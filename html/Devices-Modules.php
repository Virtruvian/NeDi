<?php
# Program: Devices-Modules.php
# Programmer: Remo Rickli

$calendar  = 1;
$printable = 1;
$exportxls = 1;

include_once ("inc/header.php");
include_once ("inc/libdev.php");

$_GET = sanitize($_GET);
$sta = isset($_GET['sta']) ? $_GET['sta'] : "";
$stb = isset($_GET['stb']) ? $_GET['stb'] : "";
$ina = isset($_GET['ina']) ? $_GET['ina'] : "";
$inb = isset($_GET['inb']) ? $_GET['inb'] : "";
$opa = isset($_GET['opa']) ? $_GET['opa'] : "";
$opb = isset($_GET['opb']) ? $_GET['opb'] : "";
$cop = isset($_GET['cop']) ? $_GET['cop'] : "";
$ord = isset($_GET['ord']) ? $_GET['ord'] : "";

$map = isset($_GET['map']) ? "checked" : "";
$lim = isset($_GET['lim']) ? preg_replace('/\D+/','',$_GET['lim']) : $listlim;

if( isset($_GET['col']) ){
	$col = $_GET['col'];
	if($_SESSION['opt']){$_SESSION['modcol'] = $_GET['col'];}
}elseif( isset($_SESSION['modcol']) ){
	$col = $_SESSION['modcol'];
}else{
	$col = array('imBL','device','slot','model','moddesc','modules.serial');
}

$cols = array(	"imBL"=>$imglbl,
		"modclass"=>$clalbl,
		"device"=>"Device $namlbl",
		"type"=>"Device $typlbl",
		"location"=>$loclbl,
		"contact"=>$conlbl,
		"firstdis"=>"$fislbl $dsclbl",
		"lastdis"=>"$laslbl $dsclbl",
		"slot"=>"Slot",
		"model"=>$mdllbl,
		"moddesc"=>$deslbl,
		"modules.serial"=>$serlbl,
		"hw"=>"Hardware",
		"fw"=>"Firmware",
		"sw"=>"Software",
		"modidx"=>$idxlbl,
		"status"=>$stalbl,
		"modloc"=>"Module $loclbl"
		);

$link = @DbConnect($dbhost,$dbuser,$dbpass,$dbname);							# Above print-header!
?>
<h1>Module <?= $lstlbl ?></h1>

<?php  if( !isset($_GET['print']) and !isset($_GET['xls']) ) { ?>

<form method="get" name="list" action="<?= $self ?>.php">
<table class="content"><tr class="<?= $modgroup[$self] ?>1">
<th width="50"><a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png"></a></th>
<th valign="top"><?= $cndlbl ?> A<p>
<select size="1" name="ina">
<?php
foreach ($cols as $k => $v){
	if( !preg_match('/(BL|IG|NS)$/',$k) ){
		echo "<option value=\"$k\"".( ($ina == $k)?" selected":"").">$v\n";
	}
}
?>
</select>
<select size=1 name="opa">
<?php selectbox("oper",$opa) ?>
</select>
<p><a href="javascript:show_calendar('list.sta');"><img src="img/16/date.png"></a>
<input type="text" name="sta" value="<?= $sta ?>" size="20">
</th>
<th valign="top"><?= $cmblbl ?><p>
<select size="1" name="cop">
<?php selectbox("comop",$cop) ?>
</select>
</th>
<th valign="top"><?= $cndlbl ?> B<p>
<select size="1" name="inb">
<?php
foreach ($cols as $k => $v){
	if( !preg_match('/(BL|IG|NS)$/',$k) ){
		echo "<option value=\"$k\"".( ($inb == $k)?" selected":"").">$v\n";
	}
}
?>
</select>
<select size="1" name="opb">
<?php selectbox("oper",$opb) ?>
</select>
<p><a href="javascript:show_calendar('list.stb');"><img src="img/16/date.png"></a>
<input type="text" name="stb" value="<?= $stb ?>" size="20">
</th>
<th valign="top"><?= $collbl ?><p>
<select multiple name="col[]" size=4>
<?php
foreach ($cols as $k => $v){
       echo "<option value=\"$k\"".((in_array($k,$col))?" selected":"").">$v\n";
}
?>
</select>

</th>
<th valign="top">

<?= $optlbl ?><p>
<div align="left">
<img src="img/16/paint.png" title="<?= (($verb1)?"$sholbl $laslbl Map":"Map $laslbl $sholbl") ?>"> 
<input type="checkbox" name="map" <?= $map ?>><br>
<img src="img/16/form.png" title="<?= $limlbl ?>"> 
<select size="1" name="lim">
<?php selectbox("limit",$lim) ?>
</select>
</div>

</th>
<th width="80">

<input type="submit" value="<?= $sholbl ?>">
</th>
</tr></table></form><p>
<?php
}
if($ina){
	if ($map and !isset($_GET['xls']) and file_exists("map/map_$_SESSION[user].php")) {
		echo "<center><h2>$netlbl Map</h2>\n";
		echo "<img src=\"map/map_$_SESSION[user].php\" style=\"border:1px solid black\"></center><p>\n";
	}
	ConHead($ina, $opa, $sta, $cop, $inb, $opb, $stb);
	TblHead("$modgroup[$self]2",1);

	$query	= GenQuery('modules','s','modules.*,type,firstdis,lastdis,location,contact',$ord,$lim,array($ina,$inb),array($opa,$opb),array($sta,$stb),array($cop),'LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( ($m = @DbFetchRow($res)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$ud = urlencode($m[0]);
			list($fc,$lc) = Agecol($m[13],$m[14],$row % 2);
			list($mcl,$img) = ModClass($m[9]);

			TblRow($bg);
			if(in_array("imBL",$col)){
				TblCell("","","class=\"$bi\" width=\"50\"","<img src=\"img/16/$img.png\" title=\"$m[9]\">","th-img");
			}
			if(in_array("modclass",$col)){
				TblCell( "$mcl","?ina=modclass&opa==&sta=$m[9]","nowrap");
			}
			if(in_array("device",$col)){
				TblCell($m[0],"?ina=device&opa==&sta=$ud","nowrap","<a href=\"Devices-Status.php?dev=$ud\"><img src=\"img/16/sys.png\"></a>");
			}
			if(in_array("type",$col)){
				TblCell( $m[12],"?ina=type&opa==&sta=".urlencode($m[12]) );
			}
			if(in_array("location",$col)){
				TblCell( $m[15],"?ina=location&opa==&sta=".urlencode($m[15]) );
			}
			if(in_array("contact",$col)){
				TblCell( $m[16],"?ina=contact&opa==&sta=".urlencode($m[16]) );
			}
			if( in_array("firstdis",$col) ){
				TblCell( date($datfmt,$m[13]),"?ina=firstdis&opa==&sta=$m[10]","bgcolor=\"#$fc\"" );
			}
			if( in_array("lastdis",$col) ){
				TblCell( date($datfmt,$m[14]),"?ina=lastdis&opa==&sta=$m[11]","bgcolor=\"#$lc\"" );
			}
			if(in_array("slot",$col)){
				TblCell( $m[1],"?ina=slot&opa==&sta=".urlencode($m[1]));
			}
			if(in_array("model",$col)){
				TblCell( $m[2],"?ina=model&opa==&sta=".urlencode($m[2]) );
			}
			if(in_array("moddesc",$col)){
				$vmac = "000c29".substr($m[5],-6);
				TblCell($m[3],"","nowrap",($m[9] == "vmwESX")?"<a href=\"Nodes-Status.php?mac=$vmac\" title=\"Nodes-Status $vmac\"><img src=\"img/16/node.png\" align=\"right\"></a>":"");
			}
			if(in_array("modules.serial",$col)){
				TblCell( $m[4],"?ina=modules.serial&opa==&sta=".urlencode($m[4]),"align=\"left\"" );
			}
			if(in_array("hw",$col)){
				TblCell( $m[5],"?ina=hw&opa==&sta=".urlencode($m[5]) );
			}
			if(in_array("fw",$col)){
				TblCell( $m[6],"?ina=fw&opa==&sta=".urlencode($m[6]) );
			}
			if(in_array("sw",$col)){
				TblCell( $m[7],"?ina=sw&opa==&sta=".urlencode($m[7]) );
			}
			if(in_array("modidx",$col)){
				TblCell($m[8],"?ina=modidx&opa==&sta=$m[8]");
			}
			if(in_array("status",$col)){
				TblCell($m[10],"?ina=status&opa==&sta=$m[10]");
			}
			if(in_array("modloc",$col)){
				TblCell( $m[11],"?ina=modloc&opa==&sta=".urlencode($m[11]) );
			}
			echo "</tr>\n";
		}
		@DbFreeResult($res);
	}else{
		print @DbError($link);
	}
	?>
</table>
<table class="content">
<tr class="<?= $modgroup[$self] ?>2"><td><?= $row ?> Modules<?= ($ord)?", $srtlbl: $ord":"" ?><?= ($lim)?", $limlbl: $lim":"" ?></td></tr>
</table>
	<?php
}
include_once ("inc/footer.php");
?>
