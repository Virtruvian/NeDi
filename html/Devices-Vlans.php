<?php
# Program: Devices-Vlans.php
# Programmer: Remo Rickli

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
	if($_SESSION['opt']){
		$_SESSION['vlcol'] = $col;
		if(!$ord and $ina){$ord = $ina;}							# Default order by ina as suggested by community
	}
}elseif( isset($_SESSION['vlcol']) ){
	$col = $_SESSION['vlcol'];
}else{
	$col = array('device','vlanid','vlanname');
}

$cols = array(	"device"=>"Device $namlbl",
		"type"=>"Device $typlbl",
		"location"=>$loclbl,
		"contact"=>$conlbl,
		"firstdis"=>"$fislbl $dsclbl",
		"lastdis"=>"$laslbl $dsclbl",
		"vlanid"=>"Vlan $idxlbl",
		"vlanname"=>"Vlan $namlbl",
		"pop"=>$poplbl
		);
		
$link = @DbConnect($dbhost,$dbuser,$dbpass,$dbname);							# Above print-header!
?>
<h1>Vlan <?= $lstlbl ?></h1>

<?php  if( !isset($_GET['print']) and !isset($_GET['xls']) ) { ?>

<form method="get" name="list" action="<?= $self ?>.php">
<table class="content"><tr class="<?= $modgroup[$self] ?>1">
<th width="50"><a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png"></a></th>
<th valign="top"><?= $cndlbl ?> A<p>
<select size="1" name="ina">
<?php
foreach ($cols as $k => $v){
	if($k != 'pop'){										# Can't be used here
		echo "<option value=\"$k\"".( ($ina == $k)?" selected":"").">$v\n";
	}
}
?>
</select>
<select size="1" name="opa">
<?php selectbox("oper",$opa) ?>
</select>
<p>
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
	if($k != 'pop'){
		echo "<option value=\"$k\"".( ($inb == $k)?" selected":"").">$v\n";
	}
}
?>
</select>
<select size="1" name="opb">
<?php selectbox("oper",$opb) ?>
</select>
<p>
<input type="text" name="stb" value="<?= $stb ?>" size="20">
</th>
<th valign="top"><?= $collbl ?><p>
<select MULTIPLE name="col[]" size=4>
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
	$query	= GenQuery('vlans','g','device,vlanid;vlanname,type,firstdis,lastdis,location,contact,count(mac) as pop',$ord,$lim,array($ina,$inb),array($opa,$opb),array($sta,$stb),array($cop),'LEFT JOIN devices USING (device) LEFT JOIN nodes USING (device,vlanid)');
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( ($v = @DbFetchRow($res)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$ud = urlencode($v[0]);
			list($fc,$lc) = Agecol($v[5],$v[6],$row % 2);
			TblRow($bg);
			if(in_array("device",$col)){
				TblCell($v[0],"?ina=device&opa==&sta=$ud&ord=vlanid","","<a href=\"Devices-Status.php?dev=$ud\"><img src=\"img/16/sys.png\"></a>");
			}
			if(in_array("type",$col)){TblCell($v[4],"?ina=type&opa==&sta=".urlencode($v[4]));}
			if(in_array("location",$col)){TblCell($v[7],"?ina=location&opa==&sta=".urlencode($v[7]));}
			if(in_array("contact",$col)){TblCell($v[8],"?ina=contact&opa==&sta=".urlencode($v[8]));}
			if( in_array("firstdis",$col) ){
				TblCell( date($datfmt,$v[5]),"?ina=firstdis&opa==&sta=$v[5]","bgcolor=\"#$fc\"" );
			}
			if( in_array("lastdis",$col) ){
				TblCell( date($datfmt,$v[6]),"?ina=lastdis&opa==&sta=$v[6]","bgcolor=\"#$lc\"" );
			}
			if(in_array("vlanid",$col)){TblCell($v[1],"?ina=vlanid&opa==&sta=".urlencode($v[1]));}
			if(in_array("vlanname",$col)){TblCell($v[3],"?ina=vlanname&opa==&sta=".urlencode($v[3]));}
			if(in_array("pop",$col)){
				if ($v[9]){
					TblCell($v[9],"Nodes-List.php?ina=device&opa==&sta=$ud&inb=vlanid&opb==&stb=$v[1]&cop=AND","",Bar($v[9],110,"mi"));
				}else{
					echo "<td></td>";
				}
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
<tr class="<?= $modgroup[$self] ?>2"><td><?= $row ?> Vlans<?= ($ord)?", $srtlbl: $ord":"" ?><?= ($lim)?", $limlbl: $lim":"" ?></td></tr>
</table>
<?php
}

include_once ("inc/footer.php");
?>
