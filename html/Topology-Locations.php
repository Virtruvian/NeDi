<?
# Program: Devices-Modules.php
# Programmer: Remo Rickli (based on idea of Steffen Scholz)

$printable = 1;

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

if( isset($_GET['col']) ){
	$col = $_GET['col'];
	if($_SESSION['olic']){$_SESSION['loccol'] = $_GET['col'];}
}elseif( isset($_SESSION['loccol']) ){
	$col = $_SESSION['loccol'];
}else{
	$col = array('loc','region','city','building','locdesc');
}

$cols = array(	"loc"=>$loclbl,
		"id"=>"ID",
		"region"=>$place['r'],
		"city"=>$place['c'],
		"building"=>$place['b'],
		"x"=>"Map X",
		"y"=>"Map Y",
		"ns"=>"Latitude (NS)",
		"ew"=>"Longitude (EW)",
		"locdesc"=>$deslbl,
		"img"=>$imglbl,
		"cmd"=>$cmdlbl
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
<h1><?=$loclbl?> <?=$lstlbl?></h1>

<?if( !isset($_GET['print']) ){?>

<form method="get" name="list" action="<?=$self?>.php">
<table class="content"><tr class="<?=$modgroup[$self]?>1">
<th width="50"><a href="<?=$self?>.php"><img src="img/32/<?=$selfi?>.png"></a></th>
<th valign="top"><?=$cndlbl?> A<p>
<select size="1" name="ina">
<?
foreach ($cols as $k => $v){
       echo "<option value=\"$k\"".( ($ina == $k)?"selected":"").">$v\n";
}
?>
</select>
<select size=1 name="opa">
<? selectbox("oper",$opa);?>
</select>
<p>
<input type="text" name="sta" value="<?=$sta?>" size="20">
</th>
<th valign="top"><?=$cmblbl?><p>
<select size="1" name="cop">
<? selectbox("comop",$cop);?>
</select>
</th>
<th valign="top"><?=$cndlbl?> B<p>
<select size="1" name="inb">
<?
foreach ($cols as $k => $v){
       echo "<option value=\"$k\"".( ($inb == $k)?"selected":"").">$v\n";
}
?>
</select>
<select size="1" name="opb">
<? selectbox("oper",$opb);?>
</select>
<p>
<input type="text" name="stb" value="<?=$stb?>" size="20">
</th>
<th valign="top"><?=$collbl?><p>
<select multiple name="col[]" size=4>
<?
foreach ($cols as $k => $v){
       echo "<option value=\"$k\"".((in_array($k,$col))?"selected":"").">$v\n";
}
?>
<?if($_SESSION['gmap']){?>
<option value="map" <?=(in_array("map",$col))?"selected":""?> >Map
<?}?>
</select>
</th>
<th width="80">
<input type="submit" value="<?=$sholbl?>">
<p>
<input type="submit" name="del" value="<?=$dellbl?>" onclick="return confirm('<?=$dellbl?> <?=$loclbl?>?')" >

</th>
</tr></table></form><p>
<?
}

function conloc($c){
	
	global $locsep;
	
	if($c == 'loc'){
		return "CONCAT_WS('$locsep',region,city,building)";
	}else{
		return $c;
	}
}

if ($ina){
	ConHead($ina, $opa, $sta, $cop, $inb, $opb, $stb);

	$query	= GenQuery('locations','s','*',$ord,'',array(conloc($ina),conloc($inb)),array($opa,$opb),array($sta,$stb),array($cop) );
	$res	= @DbQuery($query,$link);
	if($res){
		?>
<table class="content"><tr class="<?=$modgroup[$self]?>2">
		<?
		if( in_array('loc',$col) ){echo "<th width=\"50\">$loclbl</th>";}
		foreach($col as $h){
			if($h != 'loc' and $h != 'img' and $h != 'cmd' and $h != 'map'){
				ColHead($h);
			}
		}
		if( in_array('img',$col) ){echo "<th>$imglbl</th>";}
		if( in_array('cmd',$col) ){echo "<th>$cmdlbl</th>";}
		echo "</tr>\n";

		$mk  = "";
		$row = 0;
		while( ($l = @DbFetchRow($res)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$ns = $l[6]/10000000;
			$ew = $l[7]/10000000;
			echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\">";
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
			if(in_array("loc",$col)){echo "<th class=\"$bi\"><img src=\"img/$ico.png\" title=\"$tit\"></th>\n";}
			if(in_array("id",$col)){echo "<td>".chr($row+64)." - <a href=?ina=id&opa==&sta=$l[0]>$l[0]</a>";}
			if(in_array("region",$col)){echo "<td><a href=?ina=region&opa==&sta=".urlencode($l[1]).">$l[1]</a>";}
			if(in_array("city",$col)){echo "<td><a href=?ina=city&opa==&sta=".urlencode($l[2]).">$l[2]</a>";}
			if(in_array("building",$col)){echo "<td><a href=?ina=building&opa==&sta=".urlencode($l[3]).">$l[3]</a>";}
			if(in_array("x",$col)){echo "<td>$l[4]</td>";}
			if(in_array("y",$col)){echo "<td>$l[5]</td>";}
			if(in_array("ns",$col)){echo "<td>$ns</td>";}
			if(in_array("ew",$col)){echo "<td>$ew</td>";}
			if(in_array("locdesc",$col)){echo "<td>$l[8]</td>";}
			if(in_array("img",$col)){
				echo "<td>";
				if($l[3]){
					$base = "log/$l[1]-$l[2]-$l[3]";
					foreach (glob("$base*.jpg") as $pic) {
						$lbl = substr($pic, strlen($base)+1, -4);
						echo "<a href=\"javascript:pop('$pic','$lbl')\"><img src=\"img/16/img.png\" title=\"$lbl\"></a> ";
					}
				}
				echo "</td>";
			}
			if(in_array("cmd",$col)){
				$uloc = urlencode(TopoLoc($l[1],$l[2],$l[3]));
				echo "<td><a href=\"Devices-List.php?ina=location&opa=regexp&sta=$uloc\"><img src=\"img/16/dev.png\" title=\"Device $lstlbl\"></a>\n";
				echo "<a href=\"Topology-Map.php?ina=location&opa=regexp&sta=$uloc&mod=f&fmt=png&lev=5&ipi=on\"><img src=\"img/16/paint.png\" title=\"Topology Map\"></a>\n";
				echo "<a href=\"Nodes-List.php?ina=location&opa=regexp&sta=$uloc\"><img src=\"img/16/nods.png\" title=\"Nodes $lstlbl\"></a>\n";
				echo "<a href=\"http://maps.google.com/maps?q=".urlencode("$l[3] $l[2] $l[1]")."\" target=\"window\"><img src=\"img/16/map.png\" title=\"Google Maps, $namlbl\"></a>\n";
				if($ns and $ew){
					echo "<a href=\"http://maps.google.com/maps?q=$ns,$ew\" target=\"window\"><img src=\"img/16/map.png\" title=\"Google Maps, Coords\"></a>\n";
				}
				echo "</td>\n";
			}
			echo "</tr>\n";
		}
		@DbFreeResult($res);
		?>
</table>
<table class="content">
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$loclbl?></td></tr>
</table>
		<?
		if( in_array('map',$col) ){
			echo "<p><center><img src=\"http://maps.google.com/maps/api/staticmap?size=800x500&maptype=roadmap&sensor=false$mk\" style=\"border:1px solid black\"></center>\n";
		}
	}else{
		print @DbError($link);
	}
}
include_once ("inc/footer.php");
?>
