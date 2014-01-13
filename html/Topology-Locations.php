<?php
# Program: Topology-Locations.php
# Programmer: Remo Rickli (based on ideas of Steffen Scholz)

$calendar  = 0;
$printable = 1;
$exportxls = 0;

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
	if($_SESSION['opt']){$_SESSION['loccol'] = $_GET['col'];}
}elseif( isset($_SESSION['loccol']) ){
	$col = $_SESSION['loccol'];
}else{
	$col = array('locBL','region','city','building','locdesc');
}

$cols = array(	"locBL"=>$loclbl,
		"id"=>"ID",
		"region"=>$place['r'],
		"city"=>$place['c'],
		"building"=>$place['b'],
		"x"=>"X",
		"y"=>"Y",
		"ns"=>"Latitude (NS)",
		"ew"=>"Longitude (EW)",
		"locdesc"=>$deslbl,
		"imgNS"=>$imglbl,
		"cmdNS"=>$cmdlbl
		);

$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
if( isset($_GET['del']) ){
	if($isadmin){
		$query	= GenQuery('locations','d','*','','',array($ina,$inb),array($opa,$opb),array($sta,$stb),array($cop));
		if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$loclbl $dellbl OK</h5>";}
	}else{
		echo $nokmsg;
	}
}

?>
<h1><?= $loclbl ?> <?= $lstlbl ?></h1>

<?php  if( !isset($_GET['print']) ) { ?>

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
	if( !preg_match('/(BL|IG|NS)$/',$k) ){
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
<p>
<input type="submit" name="del" value="<?= $dellbl ?>" onclick="return confirm('<?= $dellbl ?>, <?= $cfmmsg ?>')" >

</th>
</tr></table></form><p>
<?php
}

if ($ina){
	ConHead($ina, $opa, $sta, $cop, $inb, $opb, $stb);

	TblHead("$modgroup[$self]2",1);
	$query	= GenQuery('locations','s','*',$ord,$lim,array($ina,$inb),array($opa,$opb),array($sta,$stb),array($cop) );
	$res	= @DbQuery($query,$link);
	if($res){
		$mk  = "";
		$row = 0;
		while( ($l = @DbFetchRow($res)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$ns = $l[6]/10000000;
			$ew = $l[7]/10000000;
			TblRow($bg);
			if($l[3]){
				$tit = "$place[b]-$l[0]";
				if(preg_match("/$redbuild/",$l[3]) ){
					$ico = "bldsr";
					if($ns and $ew){$mk .= "&markers=color:red%7Clabel:".chr($row+64)."%7C$ns,$ew";}
				}else{
					$ico = "blds";
					if($ns and $ew){$mk .= "&markers=color:brown%7Clabel:".chr($row+64)."%7C$ns,$ew";}
				}
			}elseif($l[2]){
				$tit = "$place[c]-$l[0]";
				$ico = "cityg";
				if($ns and $ew){$mk .= "&markers=color:purple%7Clabel:".chr($row+64)."%7C$ns,$ew";}
			}else{
				$tit = "$place[r]-$l[0]";
				$ico = "regg";
				if($ns and $ew){$mk .= "&markers=color:blue%7Clabel:".chr($row+64)."%7C$ns,$ew";}
			}
			if(in_array("locBL",$col)){echo "<th class=\"$bi\"><img src=\"img/$ico.png\" title=\"$tit\"></th>\n";}
			if(in_array("id",$col)){echo "<td>".chr($row+64)." - <a href=?ina=id&opa==&sta=$l[0]>$l[0]</a>";}
			if(in_array("region",$col)){echo "<td><a href=?ina=region&opa==&sta=".urlencode($l[1]).">$l[1]</a>";}
			if(in_array("city",$col)){echo "<td><a href=?ina=city&opa==&sta=".urlencode($l[2]).">$l[2]</a>";}
			if(in_array("building",$col)){echo "<td><a href=?ina=building&opa==&sta=".urlencode($l[3]).">$l[3]</a>";}
			if(in_array("x",$col)){echo "<td>$l[4]</td>";}
			if(in_array("y",$col)){echo "<td>$l[5]</td>";}
			if(in_array("ns",$col)){echo "<td>$ns</td>";}
			if(in_array("ew",$col)){echo "<td>$ew</td>";}
			if(in_array("locdesc",$col)){echo "<td>$l[8]</td>";}
			if(in_array("imgNS",$col)){
				echo "<td>";
				if($l[3]){
					$base = "foto/$l[1]-$l[2]-$l[3]";
					foreach (glob("$base*.jpg") as $pic) {
						$lbl = basename($pic,"jpg");
						echo "<a href=\"javascript:pop('$pic','$lbl')\"><img src=\"img/16/img.png\" title=\"$lbl\"></a> ";
					}
				}
				echo "</td>";
			}
			if(in_array("cmdNS",$col)){
				$uloc = urlencode(TopoLoc($l[1],$l[2],$l[3]));
				echo "<td>\n";
				echo "<a href=\"http://maps.google.com/maps?q=".urlencode("$l[3] $l[2] $l[1]")."\" target=\"window\"><img src=\"img/16/map.png\" title=\"Google Maps, $namlbl\"></a>\n";
				if($ns and $ew){
					echo "<a href=\"http://maps.google.com/maps?q=$ns,$ew\" target=\"window\"><img src=\"img/16/map.png\" title=\"Google Maps, Coords\"></a>\n";
				}
				echo "<a href=\"Topology-Map.php?ina=location&opa=regexp&sta=$uloc&fmt=png&lev=5&ipi=on\"><img src=\"img/16/paint.png\" title=\"Topology Map\"></a>\n";
				echo "<a href=\"Devices-List.php?ina=location&opa=regexp&sta=$uloc\"><img src=\"img/16/dev.png\" title=\"Device $lstlbl\"></a>\n";
				echo "<a href=\"Nodes-List.php?ina=location&opa=regexp&sta=$uloc\"><img src=\"img/16/nods.png\" title=\"Nodes $lstlbl\"></a>\n";
				echo "</td>\n";
			}
			echo "</tr>\n";
		}
		@DbFreeResult($res);
		?>
</table>
<table class="content">
<tr class="<?= $modgroup[$self] ?>2"><td><?= $row ?> <?= $loclbl ?><?= ($ord)?", $srtlbl: $ord":"" ?><?= ($lim)?", $limlbl: $lim":"" ?></td></tr>
</table>

<?php
	if ( $map and !isset($_GET['xls']) ){
		echo "<p><center>\n";
		if($_SESSION['map']){
			echo "<img src=\"http://maps.google.com/maps/api/staticmap?size=800x500&maptype=roadmap&sensor=false$mk\" style=\"border:1px solid black\">\n";
		}elseif( file_exists("map/map_$_SESSION[user].php") ){
			echo "<img src=\"map/map_$_SESSION[user].php\" style=\"border:1px solid black\">\n";
		}
		echo "</center><p>\n";
	}

	}else{
		print @DbError($link);
	}
}

include_once ("inc/footer.php");
?>
