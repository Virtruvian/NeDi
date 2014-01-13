<?
# Program: Topology-Loced.php
# Programmer: Remo Rickli

error_reporting(E_ALL ^ E_NOTICE);

$printable = 0;

include_once ("inc/header.php");
include_once ("inc/libdev.php");

$_GET = sanitize($_GET);
$id   = isset($_GET['id']) ? $_GET['id'] : "";
$reg  = isset($_GET['reg']) ? $_GET['reg'] : "";
$cty  = isset($_GET['cty']) ? $_GET['cty'] : "";
$bld  = isset($_GET['bld']) ? $_GET['bld'] : "";
$x    = isset($_GET['x']) ? $_GET['x'] : 0;
$y    = isset($_GET['y']) ? $_GET['y'] : 0;
$ns   = isset($_GET['ns']) ? $_GET['ns'] : 0;
$ew   = isset($_GET['ew']) ? $_GET['ew'] : 0;
$com  = isset($_GET['com']) ? $_GET['com'] : "";
$locex = 0;
$mapbg= TopoMap();

$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
if (isset($_GET['add']) and $reg){
	$query	= GenQuery('locations','i','','','',array('region','city','building','x','y','ns','ew','locdesc'),'',array($reg,$cty,$bld,$x,$y,$ns*10000000,$ew*10000000,$com) );
	if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$addlbl $reg $cty $bld OK</h5>";}
}elseif (isset($_GET['up']) and $id){
	$query	= GenQuery('locations','u','id',$id,'',array('region','city','building','x','y','ns','ew','locdesc'),'',array($reg,$cty,$bld,$x,$y,$ns*10000000,$ew*10000000,$com) );
	if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$updlbl $reg $cty $bld OK</h5>";}
}elseif(isset($_GET['del']) and $id){
	$query	= GenQuery('locations','d','','','',array('id'),array('='),array($id) );
	if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$dellbl $reg $cty $bld OK</h5>";}
	$id = $reg = $cty = $bld = $x = $y = $com = $ns = $ew = "";
}

$query	= GenQuery('devices','s','location');
$res	= @DbQuery($query,$link);
if($res){
	while( ($d = @DbFetchRow($res)) ){
		$l = explode($locsep, $d[0]);
		$lopt[$l[0]][$l[1]][$l[2]]++;
	}
	@DbFreeResult($res);
}else{
	echo @DbError($link);
}

if($bld){
	$query = GenQuery('locations','s','id,x,y,ns,ew,locdesc','','',array('region','city','building'),array('=','=','='),array($reg,$cty,$bld),array('AND','AND'));
	$mapbg = TopoMap($reg,$cty);
	$nam   = $bld;
	$com   = "$place[b], ".$lopt[$reg][$cty][$bld] ." devices ($now)";
	$ico   = preg_match("/$redbuild/",$bld)?'img/bldsr.png':'img/blds.png';
	$z     = "16";
}elseif($cty){
	$query	= GenQuery('locations','s','id,x,y,ns,ew,locdesc','','',array('region','city','building'),array('=','=','='),array($reg,$cty,''),array('AND','AND'));
	$mapbg = TopoMap($reg);
	$nam = $cty;
	$com = "$place[c], ".count(array_keys($lopt[$reg][$cty]))." buildings ($now)";
	$ico   = 'img/cityg.png';
	$z     = "12";
}elseif($reg){
	$query	= GenQuery('locations','s','id,x,y,ns,ew,locdesc','','',array('region','city','building'),array('=','=','='),array($reg,'',''),array('AND','AND'));
	$nam = $reg;
	$com = "$place[r], ".count(array_keys($lopt[$reg]))." cities ($now)";
	$ico   = 'img/regg.png';
	$z     = "6";
}else{
	$query = "";
}

if($query){
	$res	= @DbQuery($query,$link);
	$nloc	= @DbNumRows($res);
	if ($nloc){
		list($id,$x,$y,$ns,$ew,$com) = @DbFetchRow($res);
		$ns /= 10000000;
		$ew /= 10000000;
		$locex = 1;
	}else{
		$x = $y = $ns = $ew = "";
	}
}

?>
<h1><?=$loclbl?> Editor</h1>
<form method="get" action="<?=$self?>.php" name="lof">
<table class="content" ><tr class="<?=$modgroup[$self]?>1">
<th width="50"><a href="<?=$self?>.php"><img src="img/32/<?=$selfi?>.png"></a></th>
<th valign="top"><h3>Region</h3><select size="4" name="reg" onchange="document.lof.cty.selectedIndex = -1; document.lof.bld.selectedIndex = -1;this.form.submit();">
<?
ksort($lopt);
foreach(array_keys($lopt) as $r){
	echo "<option value=\"$r\"".(($reg == $r)?"selected":"").">$r\n";
}
?>
</select></th>
<th valign="top"><h3><?=$place[c]?></h3><select size="4" name="cty" onchange="document.lof.bld.selectedIndex = -1;this.form.submit();">
<?
if($reg){
ksort($lopt[$reg]);
	foreach(array_keys($lopt[$reg]) as $c){
		echo "<option value=\"$c\"".(($cty == $c)?"selected":"").">$c\n";
	}
}
?>
</select></th>
<th valign="top"><h3><?=$place[b]?></h3><select size="4" name="bld" onchange="this.form.submit();">
<?
if($cty){
ksort($lopt[$reg][$cty]);
	foreach(array_keys($lopt[$reg][$cty]) as $b){
		echo "<option value=\"$b\"".(($bld == $b)?"selected":"").">$b\n";
	}
}
?>
</select>

<?
if($bld){
	$base = "log/$reg-$cty-$bld";
	foreach (glob("$base*.jpg") as $pic) {
		$lbl = substr($pic, strlen($base)+1, -4);
		echo "<a href=\"javascript:pop('$pic','$lbl')\"><img src=\"img/16/img.png\" title=\"$lbl\"></a> ";
	}
}
?>

</th>
<th width="400" align= "left" valign="top">
<h3><?=$nam?> <?=$loclbl?></h3>
<img src="img/16/home.png" title="<?=$loclbl?>">
<input type="text" name="x" size="3" value="<?=$x?>">X
<input type="text" name="y" size="3" value="<?=$y?>">Y
<input type="text" name="ns" size="8" value="<?=$ns?>">NS
<input type="text" name="ew" size="8" value="<?=$ew?>">EW

<p>
<img src="img/16/say.png" title="<?=$deslbl?>">
<input type="text" name="com" size="36" value="<?=$com?>" onfocus="select();">
</th>
<th width="80">
<?if($locex){?>
<input type="hidden" name="id" value="<?=$id?>">
<input type="submit" name="up" value="<?=$updlbl?>"><p>
<input type="submit" name="del" value="<?=$dellbl?>">
<?}else{?>
<input type="submit" name="add" value="<?=$addlbl?>"><p>
<?}?>

</th>
</tr></table></form><p>

<?if($_SESSION['gmap']){?>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script language="JavaScript">

function posup(c) {
	document.lof.ns.value = c.lat();
	document.lof.ew.value = c.lng();
	document.lof.ns.style.color = "green";
	document.lof.ew.style.color = "green";
}

function initialize(){
	var coords    = new google.maps.LatLng(document.lof.ns.value, document.lof.ew.value);
	var myOptions = {zoom: <?=$z?>,center: coords,mapTypeId: google.maps.MapTypeId.ROADMAP}
<? if(!$locex or !$ns){?>
	var geocoder = new google.maps.Geocoder();
	var address = '<?=urlencode($bld)?>+<?=urlencode($cty)?>+<?=urlencode($reg)?>';

	geocoder.geocode( { 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			coords = results[0].geometry.location;
			posup(coords);
			map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
			map.setCenter(coords);
			var marker = new google.maps.Marker({map: map,draggable:true,animation: google.maps.Animation.DROP,position: coords,title:"<?=$com?>"});
			google.maps.event.addListener(marker, 'dragend', function(event){posup(event.latLng);});
		} else {
			alert("Geocode <?=$errlbl?>: " + status);
		}
	});
<?}else{?>

	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	var image = '<?=$ico?>';
	var marker = new google.maps.Marker({map: map,draggable:true,animation: google.maps.Animation.DROP,position: coords,title:"<?=$com?>",icon: image});
	google.maps.event.addListener(marker, 'dragend', function(event){posup(event.latLng);});
<?}?>
}
</script>

<? if($reg){?>
<script language="JavaScript">

window.onload = function() {
	initialize(); 
}
</script>
<center><div id="map_canvas" style="width:800px; height:400px;border:1px solid black"></div></center>
<?}?>

<?}else{
	$bgsize = getimagesize ("log/$mapbg");	
?>

<h2><?=$mapbg?></h2>
<div align="center">
<div id="map" onclick="getcoord(event)" style="background-image:url('log/<?=$mapbg?>');width:<?=$bgsize[0]?>px;height:<?=$bgsize[1]?>px;border:1px solid black">
<img src="<?=$ico?>" id="loc" style="position:relative;visibility:hidden;z-index:2;"></div>
</div>

<script language="JavaScript">

function getcoord(event){
	mapx = event.offsetX?(event.offsetX):event.pageX-document.getElementById("map").offsetLeft;
	mapy = event.offsetY?(event.offsetY):event.pageY-document.getElementById("map").offsetTop;
	document.lof.x.value = mapx;
	document.lof.y.value = mapy;
	document.getElementById("loc").style.visibility = "visible" ;
	document.getElementById("loc").style.left = (mapx-<?=$bgsize[0]/2?>)+'px';
	document.getElementById("loc").style.top = (mapy-15)+'px';
}

<? if($x and $y){?>
document.getElementById("loc").style.left = "<?=($x-$bgsize[0]/2)?>px";
document.getElementById("loc").style.top = "<?=($y-15)?>px" ;
document.getElementById("loc").style.visibility = "visible" ;
<?}?>

</script>
<?}?>

<?
include_once ("inc/footer.php");
?>
