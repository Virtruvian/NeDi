<?php
# Program: Topology-Locations.php
# Programmer: Remo Rickli (based on ideas of Steffen Scholz)

$printable = 1;
$exportxls = 1;

include_once ("inc/header.php");
include_once ("inc/libdev.php");

$_GET = sanitize($_GET);
$in = isset($_GET['in']) ? $_GET['in'] : array();
$op = isset($_GET['op']) ? $_GET['op'] : array();
$st = isset($_GET['st']) ? $_GET['st'] : array();
$co = isset($_GET['co']) ? $_GET['co'] : array();

$ord = isset($_GET['ord']) ? $_GET['ord'] : "";
if($_SESSION['opt'] and !$ord and $in[0]) $ord = $in[0];

$map = isset($_GET['map']) ? $_GET['map'] : "";
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
		"dvNS"=>"Devices",
		"poNS"=>$poplbl,
		"filNS"=>$fillbl,
		"cmdNS"=>$cmdlbl
		);

$link	= DbConnect($dbhost,$dbuser,$dbpass,$dbname);
if( isset($_GET['del']) ){
	if($isadmin){
		$query	= GenQuery('locations','d','*','','',$in,$op,$st,$co);
		if( !DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$loclbl $dellbl OK</h5>";}
	}else{
		echo $nokmsg;
	}
}

?>
<h1><?= $loclbl ?> <?= $lstlbl ?></h1>

<?php  if( !isset($_GET['print']) ) { ?>

<form method="get" name="list" action="<?= $self ?>.php">
<table class="content"><tr class="bgmain">
<th width="50"><a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png" title="<?= $self ?>"></a>
</th>
<td>

<?php Filters(); ?>

</td>
<th>

<select multiple name="col[]" size="6">
<?php
foreach ($cols as $k => $v){
       echo "<option value=\"$k\"".((in_array($k,$col))?" selected":"").">$v\n";
}
?>
</select>

</th>
<td>

<img src="img/16/form.png" title="<?= $limlbl ?>"> 
<select size="1" name="lim">
<?php selectbox("limit",$lim) ?>
</select>
<p>
<img src="img/16/paint.png" title="<?= (($verb1)?"$addlbl Map":"Map $addlbl") ?>"> 
<select size="1" name="map">
<option value=""><?= $nonlbl ?>
<option value="1"<?= ($map == 1)?" selected":"" ?>><?= $laslbl ?> NeDimap
<option value="2"<?= ($map == 2)?" selected":"" ?>>Googlemap
<option value="3"<?= ($map == 3)?" selected":"" ?>>Openstreetmap
</select>

</td>
<th width="80">

<input type="submit" class="button" value="<?= $sholbl ?>">
<p>
<input type="submit" class="button" name="del" value="<?= $dellbl ?>" onclick="return confirm('<?= $dellbl ?>, <?= $cfmmsg ?>')" >

</th>
</tr></table></form><p>
<?php
}

if( count($in) ){
	Condition($in,$op,$st,$co);

	TblHead("bgsub",1);
	$query	= GenQuery('locations','s','*',$ord,$lim,$in,$op,$st,$co );
	$res	= DbQuery($query,$link);
	if($res){
		$row = 0;
		$gmk = '';
		$omk = array();
		$minew = 180;
		$maxew = -180;
		$minns = 90;
		$maxns = -90;
		while( ($l = DbFetchRow($res)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$ns = $l[6]/10000000;
			$ew = $l[7]/10000000;
			if($ns > $maxns){$maxns = $ns;} 
			if($ns < $maxns){$minns = $ns;} 
			if($ew > $maxew){$maxew = $ew;} 
			if($ew < $maxew){$minew = $ew;} 

			if($l[3]){
				$ico = 'blds';
				$tit = "$place[b]-$l[0]";
				$clr = (preg_match("/$redbuild/",$l[3]) )?'red':'brown';
				if($ns and $ew){
					$gmk .= "&markers=color:$clr%7Clabel:".chr($row+64)."%7C$ns,$ew";
					$omk[]= "$ns,$ew,ol-marker-$clr";
				}
			}elseif($l[2]){
				$tit = "$place[c]-$l[0]";
				$ico = 'cityg';
				if($ns and $ew){
					$gmk .= "&markers=color:purple%7Clabel:".chr($row+64)."%7C$ns,$ew";
					$omk[]= "$ns,$ew,ol-marker-purple";
				}
			}else{
				$tit = "$place[r]-$l[0]";
				$ico = 'regg';
				if($ns and $ew){
					$gmk .= "&markers=color:blue%7Clabel:".chr($row+64)."%7C$ns,$ew";
					$omk[]= "$ns,$ew,ol-marker-blue";
				}
			}
			TblRow($bg);
			if(in_array("locBL",$col))	TblCell("<img src=\"img/$ico.png\" title=\"$tit\"> ".(($map==2)?chr($row+64):''),'',"$bi ctr s" );
			if(in_array("id",$col))		TblCell($l[0],"?in[]=id&op[]==&st[]=$l[0]&map=$map",'rgt s');
			if(in_array("region",$col)) 	TblCell($l[1],"?in[]=region&op[]==&st[]=".urlencode($l[1])."&map=$map");
			if(in_array("city",$col))	TblCell($l[2],"?in[]=city&op[]==&st[]=".urlencode($l[2])."&map=$map");
			if(in_array("building",$col))	TblCell($l[3],"?in[]=building&op[]==&st[]=".urlencode($l[3])."&map=$map");
			if(in_array("x",$col))		TblCell($l[4],'','rgt');
			if(in_array("y",$col))		TblCell($l[5],'','rgt');
			if(in_array("ns",$col))		TblCell($ns);
			if(in_array("ew",$col))		TblCell($ew);
			if(in_array("locdesc",$col))	TblCell($l[8]);
			if(in_array("dvNS",$col)){
				$lor = TopoLoc($l[1],$l[2],$l[3]);
				$pop = DevPop(array('location'),array('like'),array($lor));
				if($pop){
					TblCell(' '.$pop,"Devices-List.php?in[]=location&op[]=like&st[]=".urlencode($lor),'','+'.Bar($pop,100,'si'));
				}else{
					TblCell();
				}
			}
			if(in_array("poNS",$col)){
				$lor = TopoLoc($l[1],$l[2],$l[3]);
				$pop = NodPop( array('location'),array('like'),array($lor),array() );
				if($pop){
					TblCell(' '.$pop,"Nodes-List.php?in[]=location&op[]=like&st[]=".urlencode($lor),'','+'.Bar($pop,100,'si'));
				}else{
					TblCell();
				}
			}
			if(in_array("filNS",$col) and !isset($_GET['xls']) ){
				echo "		<td>";
				$fp = 'topo';
				if($l[1]) $fp .= '/'.preg_replace('/\W/','', $l[1]);
				if($l[2]) $fp .= '/'.preg_replace('/\W/','', $l[2]);
				if($l[3]) $fp .= '/'.preg_replace('/\W/','', $l[3]);
				foreach (glob("$fp/*.*") as $fil){
					$lbl = basename($fil);
					list($ico,$ed) = FileImg($fil);
					echo "$ico ";
				}
				echo "		</td>";
			}
			if(in_array("cmdNS",$col) and !isset($_GET['xls']) ){
				$ur = urlencode($l[1]);
				$uc = urlencode($l[2]);
				$ub = urlencode($l[3]);
				$ul = urlencode(TopoLoc($l[1],$l[2],$l[3]));
				echo "\t\t<td align=\"right\">\n";
				if($ns and $ew){
					echo "\t\t\t<a href=\"http://nominatim.openstreetmap.org/search.php?q=$ns,$ew\" target=\"window\"><img src=\"img/16/osm.png\" title=\"Openstreetmap\"></a>\n";
					echo "\t\t\t<a href=\"http://www.google.com/maps?q=$ns,$ew\" target=\"window\"><img src=\"img/16/map.png\" title=\"Google Maps, Coords\"></a>\n";
				}
				echo "\t\t\t<a href=\"http://www.google.com/maps?q=".(($ub)?"$ub ":'').(($uc)?"$uc ":'').",$ur\" target=\"window\"><img src=\"img/16/map.png\" title=\"Google Maps, $namlbl\"></a>\n";
				echo "\t\t\t<a href=\"Topology-Map.php?in[]=location&op[]=~&st[]=$ul&fmt=png&lev=5&ipi=on\"><img src=\"img/16/paint.png\" title=\"Topology Map\"></a>\n";
				echo "\t\t\t<a href=\"Topology-Table.php?reg=$ur&cty=$uc&bld=$ub\"><img src=\"img/16/icon.png\" title=\"Topology Table\"></a>\n";
				echo "\t\t\t<a href=\"Devices-List.php?in[]=location&op[]=~&st[]=$ul\"><img src=\"img/16/dev.png\" title=\"Device $lstlbl\"></a>\n";
				echo "\t\t\t<a href=\"Nodes-List.php?in[]=location&op[]=~&st[]=$ul\"><img src=\"img/16/nods.png\" title=\"Nodes $lstlbl\"></a>\n";
				if($isadmin){
					echo "\t\t\t<a href=\"Topology-Loced.php?id=1&del=1\"><img src=\"img/16/bcnl.png\" title=\"$dellbl\"></a>\n";
				}
				echo "\t\t</td>\n";
			}
			echo "\t</tr>\n";
		}
		DbFreeResult($res);
	}else{
		print DbError($link);
	}
	TblFoot("bgsub", count($col), "$row $loclbl".(($ord)?", $srtlbl: $ord":"").(($lim)?", $limlbl: $lim":"") );

	if ( $map and !isset($_GET['xls']) ){
		echo "<p><center>\n";
		if($map == 3){
			echo "<img src=\"http://staticmap.openstreetmap.de/staticmap.php?zoom=2&size=800x500&markers=".implode('|',$omk)."\" style=\"border:1px solid black\">\n";
		}elseif($map == 2){
			echo "<img src=\"http://maps.google.com/maps/api/staticmap?size=800x500&maptype=roadmap&sensor=false$gmk\" style=\"border:1px solid black\">\n";
		}elseif( file_exists("map/map_$_SESSION[user].php") ){
			echo "<img src=\"map/map_$_SESSION[user].php\" style=\"border:1px solid black\">\n";
		}
		echo "</center><p>\n";
	}
}

include_once ("inc/footer.php");
?>
