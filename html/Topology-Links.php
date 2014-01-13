<?php
# Program: Topology-Links.php
# Programmer: Remo Rickli (based on suggestion of richard.lajaunie)

$printable = 1;
$exportxls = 1;
$calendar  = 1;

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
	if($_SESSION['opt']){$_SESSION['lnkcol'] = $col;}
}elseif( isset($_SESSION['lnkcol']) ){
	$col = $_SESSION['lnkcol'];
}else{
	$col = array('device','ifname','neighbor','nbrifname','linktype','linkdesc');
}

$cols = array(	"id"=>"ID",
		"device"=>"Device $namlbl",
		"ifname"=>"IF $namlbl",
		"type"=>"Device $typlbl",
		"location"=>$loclbl,
		"contact"=>$conlbl,
		"firstdis"=>"$fislbl $dsclbl",
		"lastdis"=>"$laslbl $dsclbl",
		"devgroup"=>$grplbl,
		"neighbor"=>"$neblbl",
		"nbrifname"=>"$neblbl IF",
		"bandwidth"=>"$bwdlbl",
		"linktype"=>"$typlbl",
		"linkdesc"=>"$deslbl",
		"nbrduplex"=>"$neblbl Duplex",
		"nbrvlanid"=>"$neblbl Vlan",
		"time"=>$timlbl
		);

$link = @DbConnect($dbhost,$dbuser,$dbpass,$dbname);							# Above print-header!
?>
<h1>Link <?= $lstlbl ?></h1>

<?php  if( !isset($_GET['print']) and !isset($_GET['xls']) ) { ?>

<form method="get" name="list" action="<?= $self ?>.php">
<table class="content"><tr class="<?= $modgroup[$self] ?>1">
<th width="50"><a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png"></a>

</th>
<th valign="top">

<?= $cndlbl ?> A<p>
<select size="1" name="ina">
<?php
foreach ($cols as $k => $v){
	echo "<option value=\"$k\"".( ($ina == $k)?" selected":"").">$v\n";
}
?>
</select>
<select size="1" name="opa">
<?php selectbox("oper",$opa) ?>
</select>
<p><a href="javascript:show_calendar('list.sta');"><img src="img/16/date.png"></a>
<input type="text" name="sta" value="<?= $sta ?>" size="20">

</th>
<th valign="top">

<?= $cmblbl ?><p>
<select size="1" name="cop">
<?php selectbox("comop",$cop) ?>
</select>

</th>
<th valign="top">

<?= $cndlbl ?> B<p>
<select size="1" name="inb">
<?php
foreach ($cols as $k => $v){
	if($k != "imBL"){
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
<th valign="top">

<?= $collbl ?><p>
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
	$query	= GenQuery('links','s','links.*,type,firstdis,lastdis,location,contact',$ord,$lim,array($ina,$inb),array($opa,$opb),array($sta,$stb),array($cop),'LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( ($l = @DbFetchRow($res)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$ud = urlencode($l[1]);
			$un = urlencode($l[3]);
			list($fc,$lc) = Agecol($l[12],$l[13],$row % 2);
			list($tc,$tc) = Agecol($l[10],$l[10],$row % 2);

			TblRow($bg);
			if(in_array("id",$col)){
				TblCell($l[0]);
			}
			if( in_array("device",$col) ){
				TblCell($l[1],"?ina=device&opa==&sta=$ud&ord=ifname","nowrap","<a href=\"Devices-Status.php?dev=$ud\"><img src=\"img/16/sys.png\"></a>");
			}
			if(in_array("ifname",$col)){
				TblCell($l[2]);
			}
			if(in_array("type",$col)){
				TblCell( $l[10],"?ina=type&opa==&sta=".urlencode($l[10]) );
			}
			if(in_array("location",$col)){
				TblCell( $l[13],"?ina=location&opa==&sta=".urlencode($l[13]) );
			}
			if(in_array("contact",$col)){
				TblCell( $l[14],"?ina=contact&opa==&sta=".urlencode($l[14]) );
			}
			if( in_array("firstdis",$col) ){
				TblCell( date($datfmt,$l[12]),"?ina=firstdis&opa==&sta=$l[12]","bgcolor=\"#$fc\"" );
			}
			if( in_array("lastdis",$col) ){
				TblCell( date($datfmt,$l[13]),"?ina=lastdis&opa==&sta=$l[13]","bgcolor=\"#$lc\"" );
			}
			if( in_array("neighbor",$col) ){
				TblCell($l[3],"?ina=device&opa==&sta=$un&ord=ifname","nowrap","<a href=\"Devices-Status.php?dev=$un\"><img src=\"img/16/sys.png\"></a>");
			}
			if(in_array("nbrifname",$col)){
				TblCell($l[4]);
			}
			if(in_array("bandwidth",$col)){
				TblCell( DecFix($l[5]) );
			}
			if(in_array("linktype",$col)){
				TblCell( $l[6],"?ina=linktype&opa==&sta=$l[6]");
			}
			if(in_array("linkdesc",$col)){
				TblCell($l[7]);
			}
			if(in_array("nbrduplex",$col)){
				TblCell($l[8]);
			}
			if(in_array("nbrvlanid",$col)){
				TblCell($l[9]);
			}
			if(in_array("time",$col)){
				TblCell( date($datfmt,$l[10]),"?ina=time&opa==&sta=$l[10]","bgcolor=\"#$tc\"" );
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
<tr class="<?= $modgroup[$self] ?>2"><td><?= $row ?> Links<?= ($ord)?", $srtlbl: $ord":"" ?><?= ($lim)?", $limlbl: $lim":"" ?></td></tr>
</table>
	<?php
}
include_once ("inc/footer.php");
?>
