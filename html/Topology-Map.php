<?php
# Program: Topology-Map.php
# Programmer: Remo Rickli

error_reporting(E_ALL ^ E_NOTICE);

$nocache   = 1;
$refresh   = 600;
$calendar  = 1;
$printable = 1;

include_once ("inc/header.php");
include_once ("inc/libdev.php");
include_once ("inc/libgraph.php");

$dev  = array();
$reg  = array();
$nlnk = array();

$imgmap    = "";
$mapinfo   = "";
$mapframes = "";
$maplinks  = "";
$mapitems = "";

$_GET = sanitize($_GET);

$sta = isset($_GET['sta']) ? $_GET['sta'] : "";
$ina = isset($_GET['ina']) ? $_GET['ina'] : "location";
$opa = isset($_GET['opa']) ? $_GET['opa'] : "regexp";

$fmt = isset($_GET['fmt']) ? $_GET['fmt'] : "";

$dim = isset($_GET['dim']) ? $_GET['dim'] : "800x600";
list($xm,$ym) = explode("x",$dim);

$fsz = isset($_GET['fsz']) ? $_GET['fsz'] : intval($xm)/8;
$len = isset($_GET['len']) ? $_GET['len'] : intval($xm)/4;

$tit = isset($_GET['tit']) ? $_GET['tit'] : "$netlbl";
$mde = isset($_GET['mde']) ? $_GET['mde'] : "b";
$lev = isset($_GET['lev']) ? $_GET['lev'] : 1;

if ($mde == "f" and $lev < 4){$lev = 4;}
$xo  = isset($_GET['xo']) ? $_GET['xo'] : 0;
$yo  = isset($_GET['yo']) ? $_GET['yo'] : 0;
$rot = isset($_GET['rot']) ? $_GET['rot'] : 0;
$cro = isset($_GET['cro']) ? $_GET['cro'] : 0;
$bro = isset($_GET['bro']) ? $_GET['bro'] : 0;

$ifi = isset($_GET['ifi']) ? "checked" : "";
$ifa = isset($_GET['ifa']) ? "checked" : "";
$ipi = isset($_GET['ipi']) ? "checked" : "";
$ipd = isset($_GET['ipd']) ? "checked" : "";
$loo = isset($_GET['loo']) ? "checked" : "";
$loa = isset($_GET['loa']) ? "checked" : "";
$loi = (($loo)?1:0) + (($loa)?2:0);
$coi = isset($_GET['coi']) ? "checked" : "";

$lis = isset($_GET['lis']) ? $_GET['lis'] : "";
$lit = isset($_GET['lit']) ? $_GET['lit'] : "";
$lil = isset($_GET['lil']) ? $_GET['lil'] : 0;
$lal = isset($_GET['lal']) ? $_GET['lal'] : 50;
$pos = isset($_GET['pos']) ? $_GET['pos'] : "";
$pwt = isset($_GET['pwt']) ? $_GET['pwt'] : 10;
$lsf = isset($_GET['lsf']) ? $_GET['lsf']/10 : 1.2;
$fco = isset($_GET['fco']) ? $_GET['fco'] : 6;

if($pos == "d"){
	$imas = 4;
	#$fsz = ($fsz > 100)?10:$fsz;messes up ring mode...
}else{
	$imas = 18;
}

$oc = "";
$oi = "";
$dyn= "";
if($_GET['dyn']){
	# $oi = 'oninput="this.form.submit();"'; deactivated cauz Safari goes haywire
	$oi = $oc = 'onchange="this.form.submit();"';
	$dyn = "checked";
}

$cols = array(	"device"=>"Device",
		"devip"=>"IP $adrlbl",
		"type"=>"Device $typlbl",
		"firstdis"=>$fislbl,
		"lastdis"=>$laslbl,
		"services"=>$srvlbl,
		"description"=>$deslbl,
		"devos"=>"Device OS",
		"bootimage"=>"Bootimage",
		"contact"=>$conlbl,
		"location"=>$loclbl,
		"devgroup"=>$grplbl,
		"snmpversion"=>"SNMP $verlbl",
		"login"=>"Login",
		"cpu"=>"% CPU",
		"temp"=>$tmplbl,
		"vlanid"=>"Vlan",
		"vrfname"=>"VRF",
		"ifip"=>$netlbl,
		"neighbor"=>$neblbl
		);

$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);

?>
<h1>Topology Map</h1>

<?php  if( !isset($_GET['print']) ) { ?>

<form method="get" name="dynfrm" action="<?= $self ?>.php">
<table class="content" ><tr class="<?= $modgroup[$self] ?>1">
<th width="50">

<a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png"></a>

</th>
<th valign="top">

<h3><?= $namlbl ?> & <?= $fltlbl ?></h3>
<img src="img/16/say.png" title="Map <?= $namlbl ?>">
<input type="text" <?= $oc ?> name="tit" value="<?= $tit ?>" size="18">
<p>
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
<p>
<a href="javascript:show_calendar('dynfrm.sta');"><img src="img/16/date.png"></a>
<input type="text" <?= $oc ?> name="sta" placeholder="<?= $fltlbl ?>" value="<?= $sta ?>" size="18">

</th>
<td valign="top">

<h3><?= $manlbl ?></h3>
<img src="img/16/img.png" title="<?= $sizlbl ?> & <?= $frmlbl ?>">
<select size="1" <?= $oc ?> name="dim">
<?= ($dim)?"<option value=\"$dim\">$dim</option>":"" ?>
<option value="320x200">320x200
<option value="320x240">320x240
<option value="640x400">640x400
<option value="640x480">640x480
<option value="800x600">800x600
<option value="1024x600">1024x600
<option value="1024x768">1024x768
<option value="1280x768">1280x768
<option value="1280x1024">1280x1024
<option value="1600x1200">1600x1200
<option value="1920x1200">1920x1200
</select>
<select size="1" <?= $oc ?> name="fmt">
<option value="png">png
<option value="png8" <?= ($fmt == "png8")?" selected":"" ?>>8bit
<option value="svg" <?= ($fmt == "svg")?" selected":"" ?>>svg
<option value="json" <?= ($fmt == "json")?" selected":"" ?>>json
</select>
<p>
<img src="img/16/abc.png" title="Map <?= $typlbl ?>">
<select size="1" <?= $oc ?> name="lev" title="<?= $levlbl ?>">
<option value="1"><?= $place['r'] ?>
<option value="2" <?= ($lev == "2")?" selected":"" ?>><?= $place['c'] ?>
<option value="3" <?= ($lev == "3")?" selected":"" ?>><?= $place['b'] ?>
<option value="4" <?= ($lev == "4")?" selected":"" ?>>SNMP Devs
<option value="5" <?= ($lev == "5")?" selected":"" ?>><?= $alllbl ?>  Devs
<option value="6" <?= ($lev == "6")?" selected":"" ?>>Nodes
</select>
<select size="1" <?= $oc ?> name="mde" title="Map <?= $typlbl ?>">
<option value="b">bld
<option value="r" <?= ($mde == "r")?" selected":"" ?>>ring
<option value="f" <?= ($mde == "f")?" selected":"" ?>>flat
<option value="g" <?= ($mde == "g")?" selected":"" ?>>geo
</select>
<p>
<img src="img/16/geom.png" title="Map <?= $loclbl ?>">
<input type="number" min="-1000" max="1000" step="10" <?= $oi ?> name="xo" value="<?= $xo ?>" size="3" title="X <?= $loclbl ?>">X
<input type="number" min="-1000" max="1000" step="10" <?= $oi ?> name="yo" value="<?= $yo ?>" size="3" title="Y <?= $loclbl ?>">Y
<input type="number" min="-180" max="180" <?= $oi ?> name="rot" value="<?= $rot ?>" size="4" title="<?= $place['r'] ?> <?= $rotlbl ?>">R

</td>
<td valign="top"><h3>Layout</h3>

<img src="img/16/ncon.png" title="Link <?= $frmlbl ?>">

<select size="1" <?= $oc ?> name="lis">
<option value=""><?= $strlbl ?>
<option value="a1" <?= ($lis == "a1")?" selected":"" ?>><?= $arclbl ?>
<option value="a2" <?= ($lis == "a2")?" selected":"" ?>><?= $arclbl ?> 2
<option value="a3" <?= ($lis == "a3")?" selected":"" ?>><?= $arclbl ?> 3
<option value="a4" <?= ($lis == "a4")?" selected":"" ?>><?= $arclbl ?> 4
</select>

<select size="1" <?= $oc ?> name="lit">
<option value="">(<?= $nonlbl ?>)
<option value="w" <?= ($lit == "w")?" selected":"" ?>><?= $bwdlbl ?>
<option value="l" <?= ($lit == "l")?" selected":"" ?>>Link <?= $lodlbl ?>
<?php if($rrdcmd){ ?>
<option value="" style="color: DarkBlue"><?= $trflbl ?> <?= $gralbl ?>
<option value="f1" <?= ($lit == "f1")?" selected":"" ?>>- <?= $siz['t'] ?>
<option value="f2" <?= ($lit == "f2")?" selected":"" ?>>- <?= $siz['s'] ?>
<option value="f3" <?= ($lit == "f3")?" selected":"" ?>>- <?= $siz['m'] ?>
<option value="f4" <?= ($lit == "f4")?" selected":"" ?>>- <?= $siz['l'] ?>
<option value="" style="color: DarkRed "><?= $errlbl ?> <?= $gralbl ?>
<option value="e1" <?= ($lit == "e1")?" selected":"" ?>>- <?= $siz['t'] ?>
<option value="e2" <?= ($lit == "e2")?" selected":"" ?>>- <?= $siz['s'] ?>
<option value="e3" <?= ($lit == "e3")?" selected":"" ?>>- <?= $siz['m'] ?>
<option value="e4" <?= ($lit == "e4")?" selected":"" ?>>- <?= $siz['l'] ?>
<option value="" style="color: DarkMagenta">Bcast <?= $gralbl ?>
<option value="b1" <?= ($lit == "b1")?" selected":"" ?>>- <?= $siz['t'] ?>
<option value="b2" <?= ($lit == "b2")?" selected":"" ?>>- <?= $siz['s'] ?>
<option value="b3" <?= ($lit == "b3")?" selected":"" ?>>- <?= $siz['m'] ?>
<option value="b4" <?= ($lit == "b4")?" selected":"" ?>>- <?= $siz['l'] ?>
<option value="" style="color: GoldenRod">Discard  <?= $gralbl ?>
<option value="d1" <?= ($lit == "d1")?" selected":"" ?>>- <?= $siz['t'] ?>
<option value="d2" <?= ($lit == "d2")?" selected":"" ?>>- <?= $siz['s'] ?>
<option value="d3" <?= ($lit == "d3")?" selected":"" ?>>- <?= $siz['m'] ?>
<option value="d4" <?= ($lit == "d4")?" selected":"" ?>>- <?= $siz['l'] ?>
<?}?>
</select>

<input type="number" min="-100" max="100" <?= $oi ?> name="lil" <?= (!$lit and $dyn)?"disabled":"" ?> value="<?= $lil ?>" size="3" title="Link <?= $inflbl ?> <?= $loclbl ?>">I

<input type="number" min="0" max="100" <?= $oi ?> step="5" name="lal" <?= (!$ifi and !$ifa and !$ipi and $dyn)?"disabled":"" ?> value="<?= $lal ?>" size="4" title="Link IF/IP <?= $loclbl ?>">E
<p>
<img src="img/16/dev.png" title="<?= $nodlbl ?> & Links <?= $cfglbl ?>">
<select size="1" <?= $oc ?> name="pos" title="<?= $nodlbl ?> <?= $typlbl ?>">
<option value="">Icon
<option value="p" <?= ($pos == "p")?" selected":"" ?>><?= $imglbl ?>
<option value="s" <?= ($pos == "s")?" selected":"" ?>><?= $shplbl ?> <?= $siz['m'] ?>
<option value="d" <?= ($pos == "d")?" selected":"" ?>><?= $shplbl ?> <?= $siz['t'] ?>
<option value="c" <?= ($pos == "c")?" selected":"" ?>>CPU <?= $lodlbl ?>
<option value="h" <?= ($pos == "h")?" selected":"" ?>><?= $tmplbl ?>

</select>
<input type="number" min="0" max="100" <?= $oi ?> name="pwt" value="<?= $pwt ?>" size="3" title="<?= $nodlbl ?> <?= $loclbl ?> * #Links">W
<input type="number" min="0" max="1000"  <?= $oi ?> step="10" name="len" value="<?= $len ?>" size="4" title="Link <?= $lenlbl ?>">L
<input type="number" min="1" max="100"  <?= $oi ?> name="lsf" <?= ($mde == "f" and $lev < 6 and $dyn)?"disabled":"" ?> value="<?= $lsf*10 ?>" size="3" title="Link <?= $lenlbl ?>/<?= $levlbl ?>">S
<p>
<img src="img/16/map.png" title="<?= $loclbl ?> <?= $cfglbl ?>">
<input type="number" min="-180" max="180" <?= $oi ?> name="cro" <?= ($mde == "f" or $lev < 2 and $dyn)?"disabled":"" ?> value="<?= $cro ?>" size="4" title="<?= $place['c'] ?> <?= $rotlbl ?>">C
<input type="number" min="-180" max="180" <?= $oi ?> name="bro" <?= ($mde == "f" or $lev < 3 and $dyn)?"disabled":"" ?> value="<?= $bro ?>" size="4" title="<?= $place['b'] ?> <?= $rotlbl ?>">B
<input type="number" min="6" max="1000" <?= $oi ?> name="fsz" <?= ($mde == "f" or $lev < 4 and $dyn)?"disabled":"" ?> value="<?= $fsz ?>" size="4" title="<?= $place['f'] ?> <?= $sizlbl ?>">F
<input type="number" min="1" max="50" <?= $oi ?> name="fco" <?= ($mde == "f" or $lev < 4 and $dyn)?"disabled":"" ?> value="<?= $fco ?>" size="3" title="<?= $place['o'] ?> <?= $collbl ?>">R

</td>
<td valign="top"><h3><?= $sholbl ?></h3>

<img src="img/16/port.png" title="IF <?= $inflbl ?>"> 
<input type="checkbox" title="IF <?= $namlbl ?>" <?= $oc ?> name="ifi" <?= $ifi ?>> <input type="checkbox" title="IF Alias" <?= $oc ?> name="ifa" <?= $ifa ?>><br>
<img src="img/16/glob.png" title="IP <?= $adrlbl ?>"> 
<input type="checkbox" title="Device IP" <?= $oc ?> name="ipd" <?= $ipd ?>> <input type="checkbox" title="IF IP" <?= $oc ?> name="ipi" <?= $ipi ?>><br>
<img src="img/16/home.png" title="<?= $loclbl ?>"> <input type="checkbox" <?= $oc ?> name="loo" title="<?= $place['o'] ?>" <?= $loo ?>> <input type="checkbox" <?= $oc ?> name="loa" title="<?= $place['a'] ?>" <?= $loa ?>><br>
<img src="img/16/user.png" title="<?= $conlbl ?>"> <input type="checkbox" <?= $oc ?> name="coi" <?= $coi ?>>

</td>
<th width="80" valign="top">

<h3>
<span id="counter"><?= $refresh ?></span>
<img src="img/16/exit.png" title="Stop" onClick="stop_countdown(interval);">
</h3>
<br>
<img src="img/16/walk.png" title="Dynamic-<?= $edilbl ?>"> <input type="checkbox" onchange="this.form.submit();" name="dyn" <?= $dyn ?>><br>

<p>
<input type="submit" value="<?= $cmdlbl ?>">

</th></tr>
</tr></table></form><p>
<?php
}

echo "<div align=\"center\">";
if($fmt == 'json'){
	if( !isset($_GET['print']) ){echo "<h3>Json Map</h3>";}
	Map();
	WriteJson($_SESSION['user'],count($dev) );
}elseif($fmt == 'svg'){
	if( !isset($_GET['print']) ){echo "<h3>SVG Map</h3>";}
	Map();
	WriteSVG($_SESSION['user'],count($dev) );
?>
	<embed width="<?= $xm ?>" height="<?= $ym ?>" src="map/map_<?= $_SESSION[user] ?>.svg" name="SVG Map" type="image/svg+xml" style="border:1px solid black">
<?php
}else{
	if($fmt){
		if( !isset($_GET['print']) ){
			echo "<h3><a href=\"Reports-Combination.php?ina=$ina&opa=$opa&sta=$sta&rep=ass&map=1\"><img src=\"img/16/chrt.png\" title=\"$sholbl Asset Report\"></a> PNG Map</h3>";
		}
		Map();
		WritePNG($_SESSION['user'],count($dev) );
	}else{
		if( !isset($_GET['print']) ){echo "<h3>PNG Map ($laslbl)</h3>";}
	}
	if (file_exists("map/map_$_SESSION[user].php")) {
?>
<img usemap="#net" src="map/map_<?= $_SESSION['user'] ?>.php" style="border:1px solid black">
<map name="net">
<?= $imgmap ?>
</map>
<?php
	}
}
?>
</div>
<?php

include_once ("inc/footer.php");

#===================================================================
# Generate the PHP script for the image.

function WritePNG($usr,$nd) {

	global $xm,$ym,$mde,$fmt,$tit,$ina,$opa,$sta,$now;
	global $mapbg,$mapinfo,$mapframes,$maplinks,$mapitems;

	$maphdr   = array();
	$mapftr   = array();

       	$map  = "<?PHP\n";
	$map .= "# PNG Map for $nd devices created on $now by $_SESSION[user] using NeDi (visit http://www.nedi.ch for more info)\n";
	$map .= "ini_set(\"memory_limit\",\"64M\");\n";
	$map .= "header(\"Content-type: image/png\");\n";
	$map .= "error_reporting(0);\n";
	if($mde == "g"){
		$map .= "\$image = Imagecreatefrompng(\"$mapbg\");\n";
		$map .= "Imagealphablending(\$image,true);\n";
		$map .= "\$gainsboro  = Imagecolorallocatealpha(\$image, 230, 230, 230, 40);\n";
		$map .= "\$whitesmoke = Imagecolorallocatealpha(\$image, 245, 245, 245, 40);\n";
	}elseif ($fmt == "png"){
		$map .= "\$image = Imagecreatetruecolor($xm, $ym);\n";
		$map .= "Imagealphablending(\$image,true);\n";
		$map .= "\$gainsboro  = Imagecolorallocatealpha(\$image, 230, 230, 230, 40);\n";
		$map .= "\$whitesmoke = Imagecolorallocatealpha(\$image, 245, 245, 245, 40);\n";
		$map .= "\$white      = ImageColorAllocate(\$image, 255, 255, 255);\n";
		$map .= "ImageFilledRectangle(\$image, 0, 0, $xm, $ym, \$white);\n";
	}else{
		$map .= "\$image = Imagecreate($xm, $ym);\n";
		$map .= "\$gainsboro  = ImageColorAllocate(\$image, 230, 230, 230);\n";
		$map .= "\$whitesmoke = ImageColorAllocate(\$image, 245, 245, 245);\n";
		$map .= "\$white      = ImageColorAllocate(\$image, 255, 255, 255);\n";
		$map .= "ImageFilledRectangle(\$image, 0, 0, $xm, $ym, \$white);\n";
	}
	$map .= "\$red       = ImageColorAllocate(\$image, 200, 0, 0);\n";
	$map .= "\$purple    = ImageColorAllocate(\$image, 128, 0,128 );\n";
	$map .= "\$yellow    = ImageColorAllocate(\$image, 220, 200, 0);\n";
	$map .= "\$orange    = ImageColorAllocate(\$image, 250, 150, 0);\n";
	$map .= "\$green     = ImageColorAllocate(\$image, 0, 130, 0);\n";
	$map .= "\$limegreen = ImageColorAllocate(\$image, 50, 200, 50);\n";
	$map .= "\$navy      = ImageColorAllocate(\$image, 0, 0, 130);\n";
	$map .= "\$blue      = ImageColorAllocate(\$image, 0, 0, 250);\n";
	$map .= "\$burlywood = ImageColorAllocate(\$image,222,184,135);\n";
	$map .= "\$cornflowerblue      = ImageColorAllocate(\$image, 100, 150, 220);\n";
	$map .= "\$gray      = ImageColorAllocate(\$image, 100, 100, 100);\n";
	$map .= "\$lightgray = ImageColorAllocate(\$image, 211, 211, 211);\n";
	$map .= "\$black     = ImageColorAllocate(\$image, 0, 0, 0);\n";
	$map .= "ImageString(\$image, 5, 8, 8, \"$tit\", \$black);\n";
	$map .= "ImageString(\$image, 1, 8, 26, \"$nd devices ".(($sta)?"($ina $opa $sta)":"")."\", \$gray);\n";
	$map .= "ImageString(\$image, 1, ".($xm - 120).",".($ym - 10).", \"$usr $now\", \$gray);\n";

	$map .= $mapinfo . $mapframes . $maplinks . "imagesetthickness(\$image,1);\n" . $mapitems;

	$map .= "Imagepng(\$image);\n";
	$map .= "Imagedestroy(\$image);\n";
	$map .= " ?>\n";

	$fd =  @fopen("map/map_$usr.php","w") or die ("can't create map/map_$usr.php");
	fwrite($fd,$map);
	fclose($fd);
}

#===================================================================
# Generate the SVG xml.

function WriteSVG($usr,$nd) {

	global $xm,$ym,$mde,$tit,$ina,$sta,$now,$mapinfo,$mapframes,$maplinks,$mapitems;

       	$map  = "<?php xml version=\"1.0\" encoding=\"iso-8859-1\" standalone=\"no\" ?>\n";
	$map .= "<!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.0//EN\" \"http://www.w3.org/TR/SVG/DTD/svg10.dtd\">\n";
	$map .= "<svg viewBox=\"0 0 $xm $ym\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\">\n";
	$map .= "<g id=\"main\" font-size=\"9\">\n";
	$map .= "<rect id=\"canvas\" width=\"$xm\" height=\"$ym\" x=\"0\" y=\"0\" stroke=\"black\" fill=\"white\" />\n";
	$map .= "<g id=\"title\">\n";
	$map .= "	<text x=\"8\" y=\"20\" font-size=\"16\" font-weight=\"bold\">$tit</text>\n";
	$map .= "	<text x=\"8\" y=\"32\" style=\"fill:gray;\">$nd Devices ".(($sta)?"($ina $opa $sta)":"")."</text>\n";
	$map .= "	<text x=\"".($xm - 120)."\" y=\"".($ym - 5)."\" style=\"fill:gray;\">$usr $now</text>\n";
	$map .= "</g>\n";

	$map .= "<g id=\"info\">\n";
	$map .= $mapinfo;
	$map .= "</g>\n";

	$map .= "<g id=\"frames\">\n";
	$map .= $mapframes;
	$map .= "</g>\n";

	$map .= "<g id=\"links\">\n";
	$map .= $maplinks;
	$map .= "</g>\n";

	$map .= "<g id=\"items\">\n";
	$map .= $mapitems;
	$map .= "</g>\n";

	$map .= "</g></svg>\n";

	$fd =  @fopen("map/map_$usr.svg","w") or die ("can't create map/map_$usr.svg");
	fwrite($fd,$map);
	fclose($fd);
}

#===================================================================
# Generate the Json script.
function WriteJson($usr,$nd) {

	global $xm,$ym,$mde,$tit,$ina,$sta,$mapinfo,$mapframes,$maplinks,$mapitems,$cmdlbl;

$map = <<<EOD
var labelType, useGradients, nativeTextSupport, animate;

(function() {
  var ua = navigator.userAgent,
      iStuff = ua.match(/iPhone/i) || ua.match(/iPad/i),
      typeOfCanvas = typeof HTMLCanvasElement,
      nativeCanvasSupport = (typeOfCanvas == 'object' || typeOfCanvas == 'function'),
      textSupport = nativeCanvasSupport 
        && (typeof document.createElement('canvas').getContext('2d').fillText == 'function');
  //I'm setting this based on the fact that ExCanvas provides text support for IE
  //and that as of today iPhone/iPad current text support is lame
  labelType = (!nativeCanvasSupport || (textSupport && !iStuff))? 'Native' : 'HTML';
  nativeTextSupport = labelType == 'Native';
  useGradients = nativeCanvasSupport;
  animate = !(iStuff || !nativeCanvasSupport);
})();

var Log = {
  elem: false,
  write: function(text){
    if (!this.elem) 
      this.elem = document.getElementById('log');
    this.elem.innerHTML = text;
    this.elem.style.left = (500 - this.elem.offsetWidth / 2) + 'px';
  }
};


function init(){
    //init data
    //If a node in this JSON structure
    //has the "\$type" or "\$dim" parameters
    //defined it will override the "type" and
    //"dim" parameters globally defined in the
    //RGraph constructor.
    var json = [
$mapitems
	];

    //end
    //init RGraph
    var rgraph = new \$jit.RGraph({
      'injectInto': 'infovis',
      //Optional: Add a background canvas
      //that draws some concentric circles.
      'background': {
        'CanvasStyles': {
          'strokeStyle': '#ccf',
          'shadowBlur': 10,
          'shadowColor': '#778'
        }
      },
        //Nodes and Edges parameters
        //can be overridden if defined in 
        //the JSON input data.
        //This way we can define different node
        //types individually.
        Node: {
            'overridable': true,
             'color': '#555',
        },
        Edge: {
            'overridable': true,
            '\$type': 'square'
        },
        //Set polar interpolation.
        //Default's linear.
        interpolation: 'polar',
        //Change the transition effect from linear
        //to elastic.
        transition: \$jit.Trans.Elastic.easeOut,
        //Change other animation parameters.
        duration:1000,
        fps: 30,
        //Change father-child distance.
        levelDistance: 100,
        //This method is called right before plotting
        //an edge. This method is useful to change edge styles
        //individually.
        onBeforePlotLine: function(adj){
            //Add some random lineWidth to each edge.
            if (!adj.data.\$lineWidth) 
                adj.data.\$lineWidth = Math.random() * 5 + 1;
        },
        
        onBeforeCompute: function(node){
            Log.write("centering " + node.name + "...");
            
            //Make right column relations list.
            var html = "<h4>" + node.name + "</h4><b>Connections:</b>";
            html += "<ul>";
            node.eachAdjacency(function(adj){
                var child = adj.nodeTo;
                html += "<li>" + child.name + "</li>";
            });
            html += "</ul>";
            \$jit.id('inner-details').innerHTML = html;
        },
        //Add node click handler and some styles.
        //This method is called only once for each node/label crated.
        onCreateLabel: function(domElement, node){
            domElement.innerHTML = node.name;
            domElement.onclick = function () {
                rgraph.onClick(node.id, { hideLabels: false });
            };
            var style = domElement.style;
            style.cursor = 'pointer';
            style.fontSize = "1em";
            style.color = "#000";
        },
        //This method is called when rendering/moving a label.
        //This is method is useful to make some last minute changes
        //to node labels like adding some position offset.
        onPlaceLabel: function(domElement, node){
            var style = domElement.style;
            var left = parseInt(style.left);
            var w = domElement.offsetWidth;
            style.left = (left - w / 2) + 'px';
        },
        
        onAfterCompute: function(){
            Log.write("done");
        }
        
    });
    //load graph.
    rgraph.loadJSON(json, 1);
    
    //compute positions and plot
    rgraph.refresh();
    //end
    rgraph.controller.onBeforeCompute(rgraph.graph.getNode(rgraph.root));
    rgraph.controller.onAfterCompute();
    
}

window.onload = function() {
   init(); 
}

EOD;

	$fd =  @fopen("map/map_$usr.js","w") or die ("can't create map/map_$usr.js");
	fwrite($fd,$map);
	fclose($fd);

?>
<script language="javascript" type="text/javascript" src="inc/jit-rgraph.js"></script>
<script language="javascript" type="text/javascript" src="map/map_<?= $usr ?>.js"></script>

<a href="map/map_<?= $usr ?>.js">Source</a>

<div id="infovis" class="imga" style="width:<?= $xm ?>px;height:<?= $ym ?>px;margin:auto;overflow:hidden;"></div>

<table class="fixed"><tr>
<td id="inner-details" class="textpad warn"></td>
<td id="log" class="textpad good"></td>
</tr></table>
<?php
}

#===================================================================
# Draws a link.
function DrawLink($x1,$y1,$x2,$y2,$opt) {

	global $fmt,$lev,$lix,$liy,$lis,$lit,$lil,$lal,$ipi,$ifi,$ifa,$pos,$xm,$ym,$debug;
	global $dev,$maplinks,$mapitems,$errlbl,$trflbl,$rrdcmd,$rrdstep,$nedipath,$liy;
	
	$liy["$x1,$y1,$y2"] = ($liy["$x1,$y1,$y2"])?0:9;				# offset coherent if/ip info on start of links from a node where link end is same y
	$liy["$x2,$y2,$y1"] = ($liy["$x2,$y2,$y1"])?0:9;				# offset coherent if/ip info on end of links from a node where link end is same y
        if($x1 == $x2){									# offset coherent, horizontal links...
                $lix[$x1]+= 2;
                $x1 += $lix[$x1];
                $x2 = $x1;
        }elseif($y1 == $y2){								# offset coherent, verical links...
                $liy[$y1]+= 2;
                $y1 += $liy[$y1];
                $y2 = $y1;
        }
	$xlm = intval($x1 + $x2) / 2;							# middle of link
	$ylm = intval($y1 + $y2) / 2;

	$dctr1 = sqrt( pow(($x1 - $xm/2),2) + pow(($y2 - $ym/2),2) );			# Pythagoras tells distance to map center of either possible arc centerpoint
	$dctr2 = sqrt( pow(($x2 - $xm/2),2) + pow(($y1 - $ym/2),2) );

	if($dctr1 < $dctr2){
		$xctr = $x1;
		$yctr = $y2;
		$xedg = $xr2 = $x2;
		$xr1  = $xedg-intval(($xedg-$xctr)/8);
		$yedg = $yr1 = $y1;
		$yr2  = $yedg-intval(($yedg-$yctr)/8);
	}else{
		$xctr = $x2;
		$yctr = $y1;
		$xedg = $xr1 = $x1;
		$xr2  = $xedg-intval(($xedg-$xctr)/8);
		$yedg = $yr2 = $y2;
		$yr1  = $yedg-intval(($yedg-$yctr)/8);
	}
	#$maplinks .= "ImageString(\$image, 3, $xctr,$yctr,\"C\", \$blue);\n";
	#$maplinks .= "ImageString(\$image, 3, $xedg,$yedg,\"E\", \$blue);\n";

	list($t,$cf) = LinkStyle($opt['fbw'],(($lit == 'l')?$opt['ftr']:-1) );
	list($t,$cr) = LinkStyle($opt['rbw'],(($lit == 'l')?$opt['rtr']:-1) );
	#$maplinks .= "ImageString(\$image, 3, $x1,$y1,\"Start\", \$blue);\n";

	$maplinks .= "\$stylarr = array(\$$cf,\$$cf,\$$cf,\$$cf,\$$cf,\$$cf,\$$cf,\$$cf,
					\$$cf,\$$cf,\$$cf,\$$cf,\$$cf,\$$cf,\$$cf,\$$cf, 
					\$$cf,\$$cf,\$$cf,\$$cf,\$$cf,\$$cf,\$$cf,\$$cf, 
					\$$cf,\$$cf,\$$cf,\$$cf,\$$cf,\$$cf,\$$cf,\$$cf, 
					\$$cr,\$$cr,\$$cr,\$$cr,\$$cr,\$$cr,\$$cr,\$$cr,
					\$$cr,\$$cr,\$$cr,\$$cr,\$$cr,\$$cr,\$$cr,\$$cr,
					\$$cr,\$$cr,\$$cr,\$$cr,\$$cr,\$$cr,\$$cr,\$$cr,
					\$$cr,\$$cr,\$$cr,\$$cr,\$$cr,\$$cr,\$$cr,\$$cr);\n";
	$maplinks .= "imagesetstyle(\$image,\$stylarr);\n";
	$lsty = 'IMG_COLOR_STYLED';

	#$maplinks .= "\$bru = imagecreatefrompng('../img/netr.png');\n";
	#$maplinks .= "imagesetbrush(\$image, \$bru);\n";
	#$lsty = 'IMG_COLOR_BRUSHED';

	if($lis == "a1" or $x2 == $x1 and $y2 == $y1){
		if($x2 == $x1 and $y2 == $y1){
			$y1 += 1;
			$w = 60;
			$h = 60;
			$s = 0;
			$e = 360;
			$xctr  += 35;
		}else{
			$w = 2*abs($x2-$x1);
			$h = 2*abs($y2-$y1);

			$l = sqrt($w*$h)/10;
			
			if($xctr > $xedg){								# Left half
				if($yctr > $yedg){							# Upper Quadrant
					$s = 180;$e = 270;$ylm -= $l;
				}else{
					$s = 90;$e = 180;$ylm += $l;
				}
				$xlm -= $l;
			}else{
				if($yctr > $yedg){
					$s = 270;$e = 0;$ylm -= $l;
				}else{
					$s = 0;$e = 90;$ylm += $l;
				}
				$xlm += $l;
			}
		}
		if($fmt == "svg"){
			$maplinks .= "<path d=\"M $x1 $y1 A $w $h 0 0 1 $x2 $y2\" stroke=\"$cf\" stroke-width=\"$t\" fill = \"none\"/>\n";
		}else{
			$maplinks .= "imagesetthickness(\$image,$t);\n";
			$maplinks .= "imagearc(\$image, $xctr, $yctr, $w, $h, $s, $e, $lsty);\n";
		}
	}elseif($lis == "a2" or $lis == "a3"){
		$xlm = $xedg;
		$ylm = $yedg;
		if($fmt == "svg"){
			$maplinks .= "<line x1=\"$x1\" y1=\"$y1\" x2=\"$xlm\" y2=\"$ylm\" stroke=\"$cf\" stroke-width=\"$t\"/>\n";
			$maplinks .= "<line x1=\"$xlm\" y1=\"$ylm\" x2=\"$x2\" y2=\"$y2\" stroke=\"$cf\" stroke-width=\"$t\"/>\n";
		}elseif($lis == "a3"){
			$maplinks .= "imagesetthickness(\$image,$t);\n";
			$maplinks .= "imageline(\$image,$x1,$y1,$xr1,$yr1,$lsty);\n";
			$maplinks .= "imageline(\$image,$xr2,$yr2,$x2,$y2,$lsty);\n";
			$maplinks .= "imageline(\$image,$xr1,$yr1,$xr2,$yr2,$lsty);\n";
		}else{
			$maplinks .= "imagesetthickness(\$image,$t);\n";
			$maplinks .= "imageline(\$image,$x1,$y1,$xlm,$ylm,$lsty);\n";
			$maplinks .= "imageline(\$image,$xlm,$ylm,$x2,$y2,$lsty);\n";
		}
	}elseif($lis == "a4"){
		$xlm = $xedg-intval(($xedg-$xctr)/5);
		$ylm = $yedg-intval(($yedg-$yctr)/5);
		if($fmt == "svg"){
			$maplinks .= "<line x1=\"$x1\" y1=\"$y1\" x2=\"$xlm\" y2=\"$ylm\" stroke=\"$cf\" stroke-width=\"$t\"/>\n";
			$maplinks .= "<line x1=\"$xlm\" y1=\"$ylm\" x2=\"$x2\" y2=\"$y2\" stroke=\"$cf\" stroke-width=\"$t\"/>\n";
		}else{
			$maplinks .= "imagesetthickness(\$image,$t);\n";
			$maplinks .= "imageline(\$image,$x1,$y1,$xlm,$ylm,$lsty);\n";
			$maplinks .= "imageline(\$image,$xlm,$ylm,$x2,$y2,$lsty);\n";
		}
	}else{
		if($fmt == "svg"){
			$maplinks .= "<line x1=\"$x1\" y1=\"$y1\" x2=\"$x2\" y2=\"$y2\" stroke=\"$cf\" stroke-width=\"$t\"/>\n";
		}else{
			$maplinks .= "imagesetthickness(\$image,$t);\n";
			$maplinks .= "imageline(\$image,$x1,$y1,$x2,$y2,$lsty);\n";
		}
	}

	$xlm = $xlm + $lil/10*intval($xm/($xlm - $xm/2.1));				# move info on a ray from the center
	$ylm = $ylm + $lil/10*intval($ym/($ylm - $ym/2.1));				# .1 to avoid div 0

	if( is_array($opt['fif']) ){
		$yof = 2 + $liy["$x1,$y1,$y2"];
		foreach ($opt['fif'] as $fi){
			$f = explode(';;', $fi);
			$ifal = ($ifa and $dev[$f[0]]['ifal'][$f[1]])?" ".$dev[$f[0]]['ifal'][$f[1]]:"";
			if( preg_match('/^[febd]/',$lit) and $rrdcmd ){
				$rrd = "$nedipath/rrd/" . rawurlencode($f[0]) . "/" . rawurlencode($f[1]) . ".rrd";
				if (file_exists($rrd)){
					$rrdif["$f[0]-$f[1]"] = $rrd;
				}elseif($debug){
					echo "FRRD:$rrd not found!<br>\n";
				}
			}
			if($lev > 3){
				$ifl = (($ifi)?$f[1]:"");
			}else{
				$ifl = ($ifi)?"$f[0] $f[1]":"";
			}
			$ipl = ($ipi)?$dev[$f[0]]['ifip'][$f[1]]:"";
			$alpha = atan2( ($ylm-$y1),($xlm-$x1) );
			$mapitems .= DrawLabel(	$x1+cos($alpha)*$lal,
						$y1+sin($alpha)*$lal+$yof,
						Safelabel("$ifl$ipl$ifal"),1,"gray");
			$yof += 9;
		}
	}
	if( is_array($opt['rif']) ){
		$yof = 2 + $liy["$x2,$y2,$y1"];
		foreach ($opt['rif'] as $ri){
			$r = explode(';;', $ri);
			$ifal = ($ifa and $dev[$r[0]]['ifal'][$r[1]])?" ".$dev[$r[0]]['ifal'][$r[1]]:"";
			if($lev > 3){
				$ifl = ($ifi)?$r[1]:"";
			}else{
				$ifl = ($ifi)?"$r[0] $r[1]":"";
			}
			$ipl = ($ipi)?$dev[$r[0]]['ifip'][$r[1]]:"";
			$alpha = atan2( ($ylm-$y2),($xlm-$x2) );
			$mapitems .= DrawLabel(	$x2+cos($alpha)*$lal,
						$y2+sin($alpha)*$lal+$yof,
						Safelabel("$ifl$ipl$ifal"),1,"gray");
			$yof += 9;
		}
	}

	if($lit == 'w'){
		$mapitems .= DrawLabel($xlm,$ylm-8,DecFix($opt['fbw']) . "/" . DecFix($opt['rbw']),1,"green");
	}elseif($lit == 'l' and $pos != "d"){
		if($_SESSION['gbit']){
			$linklbl = DecFix(intval($opt['ftr']/$rrdstep*8))."/".DecFix(intval($opt['rtr']/$rrdstep*8));
		}else{
			$linklbl = DecFix(intval($opt['ftr']/$rrdstep))."/".DecFix(intval($opt['rtr']/$rrdstep));
		}
		$mapitems .= DrawLabel($xlm,$ylm-8,$linklbl,2,"black");
	}elseif( is_array($rrdif) ){
		if( preg_match('/^f/',$lit) ){
			$opts = GraphOpts(substr($lit,1),0,0,$trflbl,$opt['fbw']);
			list($draw,$tit) = GraphTraffic($rrdif,'trf');
			$mapitems .= DrawLabel($xlm,$ylm-25,DecFix($opt['fbw']) . "/" . DecFix($opt['rbw']),1,"green");
		}elseif( preg_match('/^e/',$lit) ){
			$opts = GraphOpts(substr($lit,1),0,0,$errlbl,1);
			list($draw,$tit) = GraphTraffic($rrdif,'err');
		}elseif( preg_match('/^d/',$lit) ){
			$opts = GraphOpts(substr($lit,1),0,0,"Discards",0);
			list($draw,$tit) = GraphTraffic($rrdif,'dsc');
		}else{
			$opts = GraphOpts(substr($lit,1),0,0,"Broadcasts",0);
			list($draw,$tit) = GraphTraffic($rrdif,'brc');
		}
		exec("$rrdcmd graph map/$xlm$ylm.png -a PNG $opts $draw");
		if($fmt == "json"){
		}elseif($fmt == "svg"){
			$mapitems .= "	<text x=\"$xlm\" y=\"$ylm\" fill=\"gray\">no RRDs in SVG!</text>\n";
		}else{
			$mapitems .= "\$icon = Imagecreatefrompng(\"$xlm$ylm.png\");\n";
			$mapitems .= "\$w = Imagesx(\$icon);\n";
			$mapitems .= "\$h = Imagesy(\$icon);\n";
			$mapitems .= "Imagecopy(\$image, \$icon,$xlm-\$w/2,$ylm-\$h/2,0,0,\$w,\$h);\n";
			$mapitems .= "Imagedestroy(\$icon);\n";
			$mapitems .= "unlink(\"$xlm$ylm.png\");\n";
		}
	}
}
#===================================================================
# Draws box

function DrawBuilding($x,$y,$r,$c,$b) {

	global $lev,$flr,$fsz,$fco,$fmt,$imas;
	global $pos,$dev,$mapframes,$mapitems,$imgmap;

	$row = $rows = $cols = 0;
	foreach(array_keys($flr[$r][$c][$b]) as $f){					# Determine building size
		$curcol = count($flr[$r][$c][$b][$f]);
		$cols   = max($curcol,$cols);
		if($curcol > $fco){							# Break row, if > Floor columns
			$rows += ceil($curcol / $fco);					# How many rows result?
			$cols = $fco;
		}else{
			$rows++;
		}
	}
	$woff = intval($fsz*($cols-1)/2);
	$hoff = intval($fsz*($rows-1)/2);

	$x1 = $x - $woff - intval($fsz/2) - $imas/2;
	$y1 = $y - $hoff - intval($fsz/2);
	$x2 = $x + $woff + intval($fsz/2);
	$y2 = $y + $hoff + intval($fsz/2);

	if($fmt == "json"){
	}elseif($fmt == "svg"){
		$mapframes .= "	<rect fill=\"whitesmoke\" x=\"$x1\" y=\"$y1\" width=\"".($x2-$x1)."\" height=\"".($y2-$y1)."\" fill-opacity=\"0.6\" />\n";
		if($pos == "d"){
			$mapframes .= "	<text x=\"$x1\" y=\"".($y1-4)."\" font-size=\"12\" fill=\"blue\">$b</text>\n";
		}else{
			$mapframes .= "	<rect fill=\"gainsboro\" x=\"$x1\" y=\"".($y1+15)."\" width=\"20\" height=\"".($y2-$y1-20)."\" fill-opacity=\"0.6\" />\n";
			$mapframes .= "	<text x=\"".($x1+4)."\" y=\"".($y1+12)."\" font-size=\"12\" fill=\"blue\">$b</text>\n";
		}
		$mapframes .= "	<rect fill=\"none\" stroke=\"black\" x=\"$x1\" y=\"$y1\" width=\"".($x2-$x1)."\" height=\"".($y2-$y1)."\"/>\n";
	}else{
		$mapframes .= "Imagefilledrectangle(\$image, $x1, $y1, $x2, $y2, \$whitesmoke);\n";
		if($pos == "d"){
			$mapframes .= "ImageString(\$image, 3, $x1, ".($y1-14).",\"$b\", \$blue);\n";
		}else{
			$mapframes .= "Imagefilledrectangle(\$image, $x1, ".($y1+15).", ".($x1+20).", $y2, \$gainsboro);\n";
			$mapframes .= "ImageString(\$image, 3, ".($x1+4).", $y1,\"$b\", \$blue);\n";
		}
		$mapframes .= "Imagerectangle(\$image, $x1, $y1, $x2, $y2, \$black);\n";
	}
	uksort($flr[$r][$c][$b], "Floorsort");
	foreach(array_keys($flr[$r][$c][$b]) as $f){
		$mapitems .= DrawItem(	$x - $woff - intval($fsz/2),
					$y - $hoff + $row*$fsz,
					0,$f,'fl');
		usort( $flr[$r][$c][$b][$f],"Roomsort" );
		$col = 0;
		foreach($flr[$r][$c][$b][$f] as $dv){
			if($col == $fco){
				$col = 0;
				$row++;
			}
			$dev[$dv]['x'] = $x - $woff + $col*$fsz;
			$dev[$dv]['y'] = $y - $hoff + $row*$fsz;
			$mapitems .= DrawItem($dev[$dv]['x'],$dev[$dv]['y'],'0',$dv,'d');
			$imgmap   .= "<area href=\"Devices-Status.php?dev=".urlencode($dv)."\" coords=\"".($dev[$dv]['x']-$imas) .",". ($dev[$dv]['y']-$imas) .",". ($dev[$dv]['x']+$imas) .",". ($dev[$dv]['y']+$imas)."\" shape=\"rect\" title=\"$dv ".$dev[$dv]['ip']." CPU:".$dev[$dv]['cpu']."%  T:".$dev[$dv]['tmp']."C\">\n";
			if( $lev == 6){DrawNodes($dv);}
			$col++;
		}
		$row++;
	}

}

#===================================================================
# Draws a single item

function DrawItem($x,$y,$opt,$label,$typ) {

	global $fmt,$dev,$nod,$pos,$loi,$coi,$ipd,$redbuild,$cpua,$tmpa;

	$r  = ($opt > 2)?12:6;
	$lx = intval($x-strlen($label) * 2);

	if($typ == 3){									# Building
		$bc = ( preg_match('/$redbuild/',$label) )?"red":"burlywood";
		if($pos == "s"){
			$itxt .= IconRect($x,$y,$r,$r,$bc).DrawLabel($x,$y+$r,Safelabel($label),2,"navy");;
		}elseif($pos == "d"){
			$itxt .= IconRect($x,$y,$r/3,$r/3,$bc).DrawLabel($x,$y+$r/2,Safelabel($label),1,"navy");;
		}else{
			$itxt .= IconPng($x,$y,BldImg($opt,$label),30).DrawLabel($x,$y+$r*2,Safelabel($label),3,"navy");;
		}
	}elseif($typ == 2){								# City
		if($pos == "s"){
			$itxt .= IconCircle($x,$y,$r,$r,"purple").DrawLabel($x,$y+$r,Safelabel($label),2,"navy");;
		}elseif($pos == "d"){
			$itxt .= IconCircle($x,$y,$r/3,$r/3,"purple").DrawLabel($x,$y+4,Safelabel($label),1,"navy");;
		}else{
			$itxt .= IconPng($x,$y,CtyImg($opt),48).DrawLabel($x,$y+25,Safelabel($label),4,"navy");;
		}
	}elseif($typ == 1){								# Region
		if($pos == "s"){
			$itxt .= IconCircle($x,$y,$r,$r,"cornflowerblue").DrawLabel($x,$y+$r,Safelabel($label),2,"navy");
		}elseif($pos == "d"){
			$itxt .= IconCircle($x,$y,$r/3,$r/3,"cornflowerblue").DrawLabel($x,$y+$r/2,Safelabel($label),2,"navy");
		}else{
			$itxt .= IconPng($x,$y,"32/glob",32).DrawLabel($x,$y+12,Safelabel($label),4,"navy");
		}
	}elseif($typ == "ri"){								# Regioninfo
		if($pos == "s"){
			$itxt .= IconCircle($x,$y,10,6,"gainsboro");
		}elseif($pos == "d"){
			$itxt .= IconCircle($x,$y,4,2,"gainsboro");
		}else{
			$itxt .= IconPng($x,$y,"regg",25);
		}
		$itxt .= DrawLabel($x,$y+10,Safelabel($label),2,"cornflowerblue");
	}elseif($typ == "ci"){								# Cityinfo
		if($pos == "s"){
			$itxt .= IconRect($x,$y,10,6,"whitesmoke");
		}elseif($pos == "d"){
			$itxt .= IconRect($x,$y,4,2,"whitesmoke");
		}else{
			$itxt .= IconPng($x,$y,"cityg",30);
		}
		$itxt .= DrawLabel($x,$y+10,Safelabel($label),2,"cornflowerblue");
	}elseif($typ == "bi"){								# Bldinfo
		if($pos == "s"){
			$itxt .= IconRect($x,$y,4,4,"whitesmoke");
		}elseif($pos == "d"){
			$itxt .= IconRect($x,$y,2,2,"whitesmoke");
		}else{
			$itxt .= IconPng($x,$y,"bldg",30);
		}
		$itxt .= DrawLabel($x,$y+10,Safelabel($label),2,"cornflowerblue");
	}elseif($typ == "fl"){								# Floorinfo
		if($pos == "s"){
			$itxt .= IconRect($x,$y,3,2,"black");
			$itxt .= DrawLabel($x,$y+6,Safelabel($label),3,"navy");
		}elseif($pos == "d"){
			$itxt .= IconRect($x,$y,1,0.5,"black");
		}else{
			$itxt .= IconPng($x,$y,"stair",10);
			$itxt .= DrawLabel($x,$y+6,Safelabel($label),3,"navy");
		}
	}elseif($typ == "d"){								# Device
		list($clr,$siz) = Devshape($dev[$label]['ico']);
		if($pos == "s"){
			$itxt .= IconRect($x,$y,$siz,($siz/2),$clr);
			if($dev[$label]['stk'] > 1){
				$itxt .= DrawLabel($x+20,$y-6,$dev[$label]['stk'],2,"blue");
			}
		}elseif($pos == "d"){
			$itxt .= IconRect($x,$y,3,2,$clr);
		}elseif($pos == "c"){
			if($dev[$label]['cpu'] == "-"){
				$itxt .= IconRect($x,$y,10,4,"gray");
			}elseif($dev[$label]['cpu'] < $cpua/2){
				$itxt .= IconRect($x,$y,10,4,"green");
			}elseif($dev[$label]['cpu'] < $cpua){
				$itxt .= IconRect($x,$y,10,4,"yellow");
			}else{
				$itxt .= IconRect($x,$y,intval(0.5*$dev[$label]['cpu']),intval(0.2*$dev[$label]['cpu']),"orange");
			}
			$itxt .= DrawLabel($x,$y-3,$dev[$label]['cpu']."%",1,"whitesmoke");
			if($dev[$label]['stk'] > 1){
				$itxt .= DrawLabel($x+20,$y-6,$dev[$label]['stk'],2,"blue");
			}
		}elseif($pos == "h"){
			if(!$dev[$label]['tmp']){
				$itxt .= IconRect($x,$y,6,3,"gray");
			}elseif($dev[$label]['tmp'] < $tmpa/2){
				$itxt .= IconRect($x,$y,10,4,"blue");
			}elseif($dev[$label]['tmp'] < $tmpa){
				$itxt .= IconRect($x,$y,10,4,"purple");
			}else{
				$itxt .= IconRect($x,$y,intval(0.5*$dev[$label]['tmp']),intval(0.2*$dev[$label]['tmp']),"red");
			}
			$itxt .= DrawLabel($x,$y-3,$dev[$label]['tlb'],1,"whitesmoke");
			if($dev[$label]['stk'] > 1){
				$itxt .= DrawLabel($x+20,$y-6,$dev[$label]['stk'],2,"blue");
			}
		}elseif($pos == "p"){
			$itxt .= Panel($x,$y,$dev[$label]['typ'],$dev[$label]['stk'],$dev[$label]['ico']);
			$pof = (($ipd)?4:0) + (($coi)?4:0);
			$itxt .= IconRect($x,$y+24+$pof/2,46,8+$pof,"whitesmoke");
		}else{
			$itxt .= IconPng($x,$y,"dev/" . $dev[$label]['ico'],30);
			if($dev[$label]['stk'] > 1){
				$itxt .= IconPng($x+30,$y,$dev[$label]['stk'],16);
			}
		}
		if($pos != "d"){
			$itxt .= DrawLabel($x,$y+18,Safelabel($label),1,"black");
			if($loi){
				if($loi == 1){
					$locl = $dev[$label]['rom'];
				}elseif($loi == 2){
					$locl = $dev[$label]['rak'];
				}elseif($loi == 3){
					$locl = $dev[$label]['rom']." ".$dev[$label]['rak'];
				}
				$itxt .= DrawLabel($x,$y-28,Safelabel($locl),1,"cornflowerblue");
			}
			if($ipd){$itxt .= DrawLabel($x,$y+26,$dev[$label]['ip'],1,"gray");}
			if($coi){$itxt .= DrawLabel($x,$y+(($ipd)?34:26),Safelabel($dev[$label]['con']),1,"cornflowerblue");}
		}
	}elseif($typ == "n"){
		if($pos == "s"){
			$itxt .= IconCircle($x,$y,6,4,"limegreen");
		}elseif($pos == "d"){
			$itxt .= IconCircle($x,$y,2,1,"limegreen");
		}else{
			$itxt .= IconPng($x,$y,"oui/" . $nod[$label]['ico'],30);
		}
		if($pos != "d"){
			$itxt .= DrawLabel($x,$y+8,Safelabel($nod[$label]['nam']),1,"black");
			if ($ipd){$itxt .= DrawLabel($x,$y+16,$nod[$label]['ip'],1,"gray");}
		}
	}
	return $itxt;
}

#===================================================================
# Draws nodes around device
function DrawNodes($dv){

	global $link,$fsz,$fco,$fmt,$len,$lsf,$ina,$opa,$sta,$imas;
	global $dev,$nod,$nlnk,$mapframes,$mapitems,$imgmap;

	include_once ('inc/libnod.php');
	if($ina == "vlanid"){
		$nquery	= GenQuery('nodes','s','name,nodip,mac,oui,ifname,ifmetric,iftype,speed,duplex,pvid,alias,dinoct,doutoct','ifname','',array('device','vlanid'),array('=',$opa),array($dv,$sta),array('AND'),'LEFT JOIN interfaces USING (device,ifname)');
	}else{
		$nquery	= GenQuery('nodes','s','name,nodip,mac,oui,ifname,ifmetric,iftype,speed,duplex,pvid,alias,dinoct,doutoct','ifname','',array('device'),array('='),array($dv),array(),'LEFT JOIN interfaces USING (device,ifname)');
	}
	$nres	= @DbQuery($nquery,$link);
	if($nres){
		$cun = 0;
		$nn  = @DbNumRows($nres);
		while( ($n = @DbFetchRow($nres)) ){
			$nod[$n[2]]['nam'] = $n[0];
			$nod[$n[2]]['ip'] = long2ip($n[1]).(($n[9])?" Vl$n[9]":"");
			$nod[$n[2]]['ico'] = Nimg("$n[2];$n[3]");
			list($nod[$n[2]]['x'],$nod[$n[2]]['y']) = CircleCoords($dev[$dv]['x'],$dev[$dv]['y'],$cun,$nn,8*($cun % 2),$len/($lsf*$lsf*$lsf),0,0);
			$mapitems .= DrawItem($nod[$n[2]]['x'],$nod[$n[2]]['y'],'0',$n[2],'n');
			$imgmap   .= "<area href=\"Nodes-Status.php?mac=$n[2]\" coords=\"".($nod[$n[2]]['x']-$imas) .",". ($nod[$n[2]]['y']-$imas) .",". ($nod[$n[2]]['x']+$imas) .",". ($nod[$n[2]]['y']+$imas)."\" shape=\"rect\" title=\"".$nod[$n[2]]['nam']." ".$nod[$n[2]]['ip']."\">\n";
			$nlnk["$dv;;$n[2]"]['fbw'] = $n[7];
			$nlnk["$dv;;$n[2]"]['rbw'] = ($n[8] == "FD")?$n[7]:0;
			$nlnk["$dv;;$n[2]"]['ftr'] = $n[11];
			$nlnk["$dv;;$n[2]"]['rtr'] = $n[12];
			$nlnk["$dv;;$n[2]"]['ifal'][] = $n[10];
			$nlnk["$dv;;$n[2]"]['fif'][] = "$dv;;$n[4]";
			$nlnk["$dv;;$n[2]"]['rif'][] = ($n[5] < 256)?";;$n[5]db":"";		# Draws SNR...
			@DbFreeResult($ires);
			$cun++;

		}
		@DbFreeResult($nres);
	}else{
		echo @DbError($link);
	}
}

#===================================================================
# Generate PNG icon text
function IconPng($x,$y,$i,$s){

	global $fmt;

	if($i){
		if($fmt == "json"){
		}elseif($fmt == "svg"){
			return "<image x=\"".($x-$s/2)."\" y=\"".($y-$s/2)."\" width=\"$s\" height=\"$s\" xlink:href=\"../img/$i.png\"/>\n";
		}else{
			$icon = "\$icon = Imagecreatefrompng(\"../img/$i.png\");\n";
			$icon .= "\$w = Imagesx(\$icon);\n";
			$icon .= "\$h = Imagesy(\$icon);\n";
			$icon .= "Imagecopy(\$image, \$icon,intval($x - \$w/2),intval($y - \$h/2),0,0,\$w,\$h);\n";
			$icon .= "Imagedestroy(\$icon);\n";
			return $icon;
		}
	}
}

#===================================================================
# Generate Jpeg icon text
function Panel($x,$y,$t,$s,$i){

	global $fmt;

	$pnl = DevPanel($t,$i);
	
	if($fmt == "json"){
	}elseif($fmt == "svg"){
		$stk = "";
		if($s > 1){
			$stk = DrawLabel($x+55,$y,$s,2,"blue");
		}
		return "<image x=\"".($x-50)."\" y=\"".($y-25)."\" width=\"100\" height=\"50\" xlink:href=\"../$pnl\"/>$stk\n";
	}else{
		$icon = "\$icon = imagecreatefromjpeg(\"../$pnl\");\n";
		$icon .= "\$w = Imagesx(\$icon);\n";
		$icon .= "\$h = Imagesy(\$icon);\n";
		for ($c = 1; $c <= $s; $c++) {
			$icon .= "imagecopyresized(\$image, \$icon,intval($x-\$w/5), intval($y-$c*\$h/2.5+($s*\$h/5) ),0,0,intval(\$w/2.5),intval(\$h/2.5+1),\$w,\$h );\n";
		}
		$icon .= "Imagedestroy(\$icon);\n";
		return $icon;
	}
}

#===================================================================
# Generate rectangular shape (and set $h to height for following labeloffset)
function IconRect($x,$y,$w,$h,$c){

	global $fmt;

	if($fmt == "json"){
	}elseif($fmt == "svg"){
		return "<rect fill=\"$c\" stroke=\"black\" x=\"".($x-$w)."\" y=\"".($y-$h)."\" width=\"".(2*$w)."\" height=\"".(2*$h)."\" />\n";
	}else{
		$icon = "Imagefilledrectangle(\$image, ".($x-$w).", ".($y-$h).", ".($x+$w).", ".($y+$h).", \$$c);\n";
		$icon .= "Imagerectangle(\$image, ".($x-$w).", ".($y-$h).", ".($x+$w).", ".($y+$h).", \"\$black\");\n";
		$icon .= "\$h = $h;";
		return $icon;
	}
}

#===================================================================
# Generate circular shape  (and set $h to height for following labeloffset)
function IconCircle($x,$y,$rx,$ry,$c){

	global $fmt;

	if($fmt == "json"){
	}elseif($fmt == "svg"){
		return "<ellipse  fill=\"$c\" stroke=\"black\" cx=\"$x\" cy=\"$y\" rx=\"$rx\" ry=\"$ry\"/>\n";
	}else{
		$icon = "Imagefilledellipse(\$image, $x, $y, ".(2*$rx).", ".(2*$ry).", \"\$$c\");\n";
		$icon .= "Imageellipse(\$image, $x, $y, ".(2*$rx).", ".(2*$ry).", \"\$black\");\n";
		$icon .= "\$h = $ry;";
		return $icon;
	}
}

#===================================================================
# Generate label text
function DrawLabel($x,$y,$t,$s,$c){

	global $fmt;

	if($t != ""){
		$fs = ($s == 1)?9:(4*$s);
		$lx = intval($x-strlen($t) * $fs/4);

		if($fmt == "json"){
		}elseif($fmt == "svg"){
			return "<text x=\"$lx\" y=\"".($y+$fs)."\" font-size=\"$fs\" fill=\"$c\">$t</text>\n";
		}else{
			return "ImageString(\$image, $s, $lx, $y, \"$t\", \$$c);\n";
		}
	}

}

//===================================================================
// Return link style based on forward bandwidth or utilisation
function LinkStyle($bw=0,$trf=0){

	global $rrdstep;

	if($trf == -1){
		if($bw == 0){									# No bandwidth
			return array('1','lightgray');
		}elseif($bw == 11000000 or $bw == 54000000 or $bw == 300000000 or $bw == 450000000){		# Most likely Wlan
			return array('5','gainsboro');
		}elseif($bw < 10000000){							# Most likely serial links
			return array(intval($bw/1000000),'limegreen');
		}elseif($bw < 100000000){							# 10 Mbit Ethernet
			return array(intval($bw/10000000),'blue');
		}elseif($bw < 1000000000){							# 100 Mbit Ethernet
			return array(intval($bw/100000000),'orange');
		}elseif($bw < 10000000000){							# 1 Gbit Ethernet
			return array(intval($bw/1000000000),'red');
		}else{										# 10 Gbit Ethernet
			return array(intval($bw/10000000000),'purple');
		}
	}else{
		$w = 4;
		$utl = $trf*800/$bw/$rrdstep;
		if($utl == 0){									# No traffic
			return array($w,'gainsboro');
		}elseif($utl < 25){
			return array($w,'limegreen');
		}elseif($utl < 50){
			return array($w,'blue');
		}elseif($utl < 75){
			return array($w,'orange');
		}else{
			return array($w,'red');
		}
	}
}

#===================================================================
# Generate the map.
function Map() {

	global $debug,$link,$locsep,$vallbl,$sholbl,$sumlbl,$imas,$fmt,$lit,$fsz;
	global $xm,$ym,$xo,$yo,$rot,$cro,$bro,$len,$lsf,$mde,$ina,$opa,$sta,$lev,$loi,$ipi,$ifa;
	global $mapbg,$mapitems,$maplinks,$mapinfo,$imgmap,$reg,$cty,$bld,$flr,$dev,$nod,$nlnk;

	$rlnk = array();
	$clnk = array();
	$blnk = array();
	$dlnk = array();

	list($ina,$join) = JoinDev($ina);

	if($lev > 4){
		$query	= GenQuery('devices','s','distinct device,devip,type,location,contact,icon,snmpversion,cpu,temp,devopts,stack','','',array($ina),array($opa),array($sta),'', $join);
	}else{
		$query	= GenQuery('devices','s','distinct device,devip,type,location,contact,icon,snmpversion,cpu,temp,devopts,stack','','',array("snmpversion",$ina),array('!=',$opa),array(0,$sta),array("AND"), $join);
	}

	$res	= @DbQuery($query,$link);
	if($res){
		while( ($d = @DbFetchRow($res)) ){
			$l = explode($locsep, $d[3]);
			$reg[$l[0]]['ndv']++;
			$cty[$l[0]][$l[1]]['ndv']++;
			$dev[$d[0]]['reg'] = $l[0];
			if($d[6] and $ipi){								# Get IP info for interfaces on snmpdevs
				$nquery	= GenQuery('networks','s','ifname,ifip,ifip6,vrfname','','',array('device'),array('='),array($d[0]) );
				$nres	= @DbQuery($nquery,$link);
				if($nres){
					while( ($n = @DbFetchRow($nres)) ){
						if($n[1]){
							$dev[$d[0]]['ifip'][$n[0]] .= " ". long2ip($n[1]).(($n[3])?" ($n[3])":"");
						}else{
							$dev[$d[0]]['ifip'][$n[0]] .= " ". inet_ntop($n[2]).(($n[3])?" ($n[3])":"");
						}
					}
				}else{
					echo @DbError($nlink);
				}
				@DbFreeResult($nres);
			}
			if($d[6] and ($ifa or $lit == 'l') ){						# Get IF alias TODO use iftype to determine links?
				$nquery	= GenQuery('interfaces','s','ifname,ifidx,iftype,alias,dinoct,doutoct','','',array('device'),array('='),array($d[0]) );
				$nres	= @DbQuery($nquery,$link);
				if($nres){
					while( ($n = @DbFetchRow($nres)) ){
						$dev[$d[0]]['ifty'][$n[0]] = $n[1];
						$dev[$d[0]]['ifix'][$n[0]] = $n[2];
						$dev[$d[0]]['ifal'][$n[0]] = $n[3];
						$dev[$d[0]]['ifin'][$n[0]] = $n[4];
						$dev[$d[0]]['ifout'][$n[0]] = $n[5];
					}
				}else{
					echo @DbError($nlink);
				}
				@DbFreeResult($nres);
			}
			if($lev > 1){
				$dev[$d[0]]['cty'] = $l[1];
			}
			if($lev > 2){
				$bld[$l[0]][$l[1]][$l[2]]['ndv']++;
				$dev[$d[0]]['bld'] = $l[2];
			}
			if($lev > 3){
				if ($mde == "r") {
					$flr[$l[0]][$l[1]][$l[2]][$d[0]]['ndv']++;
				}else{
					$flr[$l[0]][$l[1]][$l[2]][$l[3]][] = $d[0];
				}
				$dev[$d[0]]['ip']  = long2ip($d[1]);
				$dev[$d[0]]['rom'] = $l[4];
				$dev[$d[0]]['rak'] = ($l[5])?$l[5]:"";
				$dev[$d[0]]['typ'] = $d[2];
				$dev[$d[0]]['con'] = $d[4];
				$dev[$d[0]]['ico'] = $d[5];
				$dev[$d[0]]['ver'] = $d[6];
				if( substr($d[9],1,1) == "C" ){
					$dev[$d[0]]['cpu'] = $d[7];
				}else{
					$dev[$d[0]]['cpu'] = "-";
				}
				$dev[$d[0]]['tmp'] = $d[8];
				if($d[8] != 0){
					$dev[$d[0]]['tlb'] = ($_SESSION['far'])?intval($dev[$d[0]]['tmp']*1.8+32)."F":$dev[$d[0]]['tmp']."C";
				}else{
					$dev[$d[0]]['tlb'] = "-";
				}
				$dev[$d[0]]['stk'] = ($d[10] > 1)?$d[10]:1;

			}
		}
		@DbFreeResult($res);
	}else{
		echo @DbError($link);
	}

# Precalculate Links
	foreach(array_keys($dev) as $d){
		$lquery	= GenQuery('links','s','*','','',array('device'),array('='),array($d));
		$lres	= @DbQuery($lquery,$link);
		while( ($k = @DbFetchRow($lres)) ){
			if( isset($dev[$k[3]]['reg']) ){				# Only use, if we have complete devs
				$rlquery = GenQuery('links','s','*','','',array('device','neighbor'),array('=','='),array($k[3],$k[1]),array('AND'));
				$rlres	 = @DbQuery($rlquery,$link);
				$rlnum   = @DbNumRows($rlres);
				if($debug){echo "LNK :Read $k[1] to $k[3] with BW of $k[5]<br>\n";}
				if( array_key_exists("$k[3];;$k[1]",$dlnk) ){
					$dlnk["$k[3];;$k[1]"]['rbw'] += $k[5];
					$dlnk["$k[3];;$k[1]"]['rtr'] += $dev[$k[3]]['ifin'][$k[4]];
					$dlnk["$k[3];;$k[1]"]['rif'][] = "$k[1];;$k[2]";
				}elseif( isset($dev[$k[3]]['ico']) ){
					if(!$rlnum){
						if($debug){echo "RLNK: Fixing missing link from $k[3] to $k[1]<br>\n";}
						$dlnk["$k[1];;$k[3]"]['rbw'] += $k[5];
						$dlnk["$k[1];;$k[3]"]['rtr'] += $dev[$k[1]]['ifin'][$k[2]];
						$dlnk["$k[1];;$k[3]"]['rif'][] = "$k[3];;$k[4]";
					}
					$dlnk["$k[1];;$k[3]"]['fbw'] += $k[5];
					$dlnk["$k[1];;$k[3]"]['ftr'] += $dev[$k[1]]['ifout'][$k[2]];
					$dlnk["$k[1];;$k[3]"]['fif'][] = "$k[1];;$k[2]";
				}
				$ra = $dev[$k[1]]['reg'];
				$rb = $dev[$k[3]]['reg'];
				$ca = $dev[$k[1]]['cty'];
				$cb = $dev[$k[3]]['cty'];
				$ba = $dev[$k[1]]['bld'];
				$bb = $dev[$k[3]]['bld'];

				if($mde != "f" and $ra != $rb ){
					$reg[$ra]['nlk']++;
					$reg[$ra]['alk'][$rb]++;			# Needed for arranging
					if( array_key_exists("$rb;;$ra",$rlnk) ){	# Reverse link exists?
						$rlnk["$rb;;$ra"]['rbw']  += $k[5];
						$rlnk["$rb;;$ra"]['rtr'] += $dev[$k[1]]['ifin'][$k[2]];
						$rlnk["$rb;;$ra"]['rif'][] = "$k[1];;$k[2]";
					}else{
						if(!$rlnum){
							$reg[$rb]['nlk']++;
							$reg[$rb]['alk'][$rb]++;
							$rlnk["$ra;;$rb"]['rbw'] += $k[5];
							$rlnk["$ra;;$rb"]['rtr'] += $dev[$k[1]]['ifin'][$k[2]];
							$rlnk["$ra;;$rb"]['rif'][] = "$k[3];;$k[4]";
						}
						$rlnk["$ra;;$rb"]['fbw']  += $k[5];
						$rlnk["$ra;;$rb"]['ftr']  += $dev[$k[1]]['ifout'][$k[2]];
						$rlnk["$ra;;$rb"]['fif'][] = "$k[1];;$k[2]";
					}
				}
				if($mde != "f" and $lev > 1){
					if("$ra;;$ca" != "$rb;;$cb"){
						$cty[$ra][$ca]['nlk']++;
						if($ra == $rb){$cty[$ra][$ca]['alk'][$cb]++;}#TODO test whether this improves arranging!
						if( array_key_exists("$rb;;$cb;;$ra;;$ca",$clnk) ){
							$clnk["$rb;;$cb;;$ra;;$ca"]['rbw']  += $k[5];
							$clnk["$rb;;$cb;;$ra;;$ca"]['rtr']  += $dev[$k[1]]['ifin'][$k[2]];
							$clnk["$rb;;$cb;;$ra;;$ca"]['rif'][] = "$k[1];;$k[2]";
						}else{
							if(!$rlnum){
								$cty[$rb][$cb]['nlk']++;
								if($ra == $rb){$cty[$rb][$cb]['alk'][$ca]++;}
								$clnk["$ra;;$ca;;$rb;;$cb"]['rbw']  += $k[5];
								$clnk["$ra;;$ca;;$rb;;$cb"]['rtr']  += $dev[$k[1]]['ifin'][$k[2]];
								$clnk["$ra;;$ca;;$rb;;$cb"]['rif'][] = "$k[3];;$k[4]";
							}
							$clnk["$ra;;$ca;;$rb;;$cb"]['fbw']  += $k[5];
							$clnk["$ra;;$ca;;$rb;;$cb"]['ftr']  += $dev[$k[1]]['ifout'][$k[2]];
							$clnk["$ra;;$ca;;$rb;;$cb"]['fif'][] = "$k[1];;$k[2]";

						}
					}
				}
				if($mde != "f" and $lev > 2){
					if("$ra;;$ca;;$ba" != "$rb;;$cb;;$bb"){
						$bld[$ra][$ca][$ba]['nlk']++;
						if("$ra;;$ca" == "$rb;;$cb"){$bld[$ra][$ca][$ba]['alk'][$bb]++;}
						if( array_key_exists("$rb;;$cb;;$bb;;$ra;;$ca;;$ba",$blnk) ){
							$blnk["$rb;;$cb;;$bb;;$ra;;$ca;;$ba"]['rbw']  += $k[5];
							$blnk["$rb;;$cb;;$bb;;$ra;;$ca;;$ba"]['rtr']  += $dev[$k[1]]['ifin'][$k[2]];
							$blnk["$rb;;$cb;;$bb;;$ra;;$ca;;$ba"]['rif'][] = "$k[1];;$k[2]";
						}else{
							if(!$rlnum){
								$bld[$rb][$cb][$bb]['nlk']++;
								if("$ra;;$ca" == "$rb;;$cb"){$bld[$rb][$cb][$bb]['alk'][$ba]++;}
								$blnk["$ra;;$ca;;$ba;;$rb;;$cb;;$bb"]['rbw']  += $k[5];
								$blnk["$ra;;$ca;;$ba;;$rb;;$cb;;$bb"]['rtr']  += $dev[$k[1]]['ifin'][$k[2]];
								$blnk["$ra;;$ca;;$ba;;$rb;;$cb;;$bb"]['rif'][] = "$k[3];;$k[4]";
							}
							$blnk["$ra;;$ca;;$ba;;$rb;;$cb;;$bb"]['fbw']  += $k[5];
							$blnk["$ra;;$ca;;$ba;;$rb;;$cb;;$bb"]['ftr']  += $dev[$k[1]]['ifout'][$k[2]];
							$blnk["$ra;;$ca;;$ba;;$rb;;$cb;;$bb"]['fif'][] = "$k[1];;$k[2]";
						}
					}
				}
				if($lev > 3){
					$dev[$k[1]]['nlk']++;					# Count devlinks for flatmode
					$dev[$k[1]]['alk'][$k[3]]++;				# Needed for arranging
					#if ($mde == "r") {# TODO find arrange method for building rings (only links within bld matter!)
					#	$flr[$l[0]][$l[1]][$l[2]][$k[1]]['alk'][$k[3]]++;
					#}
					if(!$rlnum){
						$dev[$k[3]]['nlk']++;
						$dev[$k[3]]['alk'][$k[1]]++;
					}
				}
			}
		}
		@DbFreeResult($lres);
	}
	$nr = count( array_keys($reg) );


# Draw Layout
	if($mde == "f"){
		$cud = 0;
		$fstnod = 1;
		$nd = count( array_keys($dev) );
		foreach(Arrange($dev) as $dv){
			if($fmt == "json"){
				list($clr,$siz,$shp) = Devshape($dev[$dv]['ico']);
				$mapitems .= ($fstnod)?"":",";
				$mapitems .= "{\"id\": \"$dv\",\n";
				$mapitems .= "	\"name\": \"$dv\",\n";
				$mapitems .= "	\"data\": {\"\$dim\": $siz,\"\$type\": \"$shp\",\"\$color\": \"$clr\"},\n";
				$mapitems .= " \"adjacencies\": [\n";

				$fstlnk = 1;
				foreach(array_keys($dlnk) as $li){
					$l = explode(';;', $li);
					if($l[0] == $dv){
						$nto = $l[1];
						$bw = $dlnk[$li]['fbw'];
					}elseif($l[1] == $dv){
						$nto = $l[0];
						$bw = $dlnk[$li]['rbw'];
					}else{
						$nto = 0;
					}
					if($nto){
						list($t,$c) = LinkStyle($bw,-1);
						$mapitems .= ($fstlnk)?"	":"	,";
						$mapitems .= "{\"nodeTo\": \"$nto\",";
						$mapitems .= "	\"data\": {\n";
						$mapitems .= "		\"weight\": ".intval($bw/1000000).",\n";
						$mapitems .= "		\"\$color\": \"$c\",\n";
						$mapitems .= "		\"\$dim\":$t}\n	}\n";
						$fstlnk = 0;
					#	echo "WID $t - ";
					}
				}
				$fstnod = 0;
				$mapitems .= " ]\n}\n";
			}else{
				list($dev[$dv]['x'],$dev[$dv]['y']) = CircleCoords(intval($xm/2 + $xo),intval($ym/2 - $yo),$cud,$nd,$dev[$dv]['nlk'],$len,$rot);
				$mapitems .= DrawItem($dev[$dv]['x'],$dev[$dv]['y'],'0',$dv,'d');
				if( $lev == 6){
					DrawNodes($dv);
				}
				$imgmap .= "<area href=\"Devices-Status.php?dev=".urlencode($dv)."\" coords=\"".($dev[$dv]['x']-$imas) .",". ($dev[$dv]['y']-$imas) .",". ($dev[$dv]['x']+$imas) .",". ($dev[$dv]['y']+$imas)."\" shape=\"rect\" title=\"$dv ".$dev[$dv]['ip']." CPU:".$dev[$dv]['cpu']."% Temp:".$dev[$dv]['tlb']."\">\n";
				if ($loi){
					$mapinfo .= DrawLabel($dev[$dv]['x'],$dev[$dv]['y']-40,Safelabel($dev[$dv]['cty']." ".$dev[$dv]['bld']),1,"cornflowerblue");
				}elseif ($debug){
					$mapinfo .= DrawLabel($dev[$dv]['x'],$dev[$dv]['y']-40,"Pos$cud",1,"cornflowerblue");
				}
				$cud++;
			}
		}
	}else{
		if ($mde == "g") {							# Prepare geographic stuff
			if($nr == 1){
				$r   = array_keys($reg);
				if(count($reg[$r[0]]) == 1){
					$c = array_keys($cty[$r[0]]);
					$mapbg = TopoMap($r[0],$c[0]);
				}else{
					$mapbg = TopoMap($r[0]);
				}
			}else{
				$mapbg = TopoMap();
			}
			$bg = Imagecreatefrompng("log/$mapbg");
			$xm = Imagesx($bg);
			$ym = Imagesy($bg);
			Imagedestroy($bg);
		}

		$cur = 0;
		foreach(Arrange($reg) as $r){
			if ($mde == "g") {
				list($reg[$r]['x'],$reg[$r]['y'],$reg[$r]['cmt']) = DbCoords($r);
			}
			if(!$reg[$r]['x']){
				list($reg[$r]['x'],$reg[$r]['y']) = CircleCoords(intval($xm/2 + $xo),intval($ym/2 - $yo),$cur,$nr,$reg[$r]['nlk'],$len,$rot);
			}
			$rnum++;
			if( $lev == 1){
				$mapitems .= DrawItem($reg[$r]['x'],$reg[$r]['y'],$reg[$r]['ndv'],$r,1);
				$imgmap   .= "<area href=\"?lev=2&mde=$mde&fmt=png&loi=$loi&sta=". urlencode( TopoLoc($r) ) ."\" coords=\"".($reg[$r]['x']-$imas) .",". ($reg[$r]['y']-$imas) .",". ($reg[$r]['x']+$imas) .",". ($reg[$r]['y']+$imas)."\" shape=\"rect\" title=\"$sholbl\">\n";
			}else{
				if ($loi){
					if(count($cty[$r]) > 1){
						$mapinfo .= DrawItem($reg[$r]['x'],$reg[$r]['y'],'0',$r." ".$reg[$r]['cmt'],'ri');
					}else{
						$mapinfo .= DrawLabel($reg[$r]['x'],$reg[$r]['y']-42,Safelabel($r),1,"cornflowerblue");
					}
				}
				$cuc = 0;
				$nc = count( array_keys($cty[$r]) );
				foreach(Arrange($cty[$r]) as $c){
					if ($mde == "g") {
						list($cty[$r][$c]['x'],$cty[$r][$c]['y'],$cty[$r][$c]['cmt']) = DbCoords($r,$c);
					}
					if(!$cty[$r][$c]['x']){
						list($cty[$r][$c]['x'],$cty[$r][$c]['y']) = CircleCoords($reg[$r]['x'],$reg[$r]['y'],$cuc,$nc,$cty[$r][$c]['nlk'],$len/$lsf,$cro);
					}
					if( $lev == 2){
						$mapitems .= DrawItem($cty[$r][$c]['x'],$cty[$r][$c]['y'],$cty[$r][$c]['ndv'],$c,2);
						$imgmap   .= "<area href=\"?lev=3&mde=$mde&fmt=png&loi=$loi&sta=". urlencode( TopoLoc($r,$c) ) ."\" coords=\"".($cty[$r][$c]['x']-$imas) .",". ($cty[$r][$c]['y']-$imas) .",". ($cty[$r][$c]['x']+$imas) .",". ($cty[$r][$c]['y']+$imas)."\" shape=\"rect\" title=\"$sholbl\">\n";
					}else{
						if ($loi){
							if(count($bld[$r][$c]) > 1){
								$mapinfo .= DrawItem($cty[$r][$c]['x'],$cty[$r][$c]['y'],'0',$c." ".$cty[$r][$c]['cmt'],'ci');
							}else{
								$mapinfo .= DrawLabel($cty[$r][$c]['x'],$cty[$r][$c]['y']-30,Safelabel($c),1,"cornflowerblue");
							}
						}
						$cb = 0;
						$nb = count( array_keys($bld[$r][$c]) );
						foreach(Arrange($bld[$r][$c]) as $b){
							if ($mde == "g") {
								list($bld[$r][$c][$b]['x'],$bld[$r][$c][$b]['y'],$bld[$r][$c][$b]['cmt']) = DbCoords($r,$c,$b);
							}
							if(!$bld[$r][$c][$b]['x']){
								list($bld[$r][$c][$b]['x'],$bld[$r][$c][$b]['y']) = CircleCoords($cty[$r][$c]['x'],$cty[$r][$c]['y'],$cb,$nb,$bld[$r][$c][$b]['nlk']*(($mde == "b")?($cb % 2)+0.3:1),$len/($lsf*$lsf),$bro);
							}
							if($lev == 3){
								$mapitems .= DrawItem($bld[$r][$c][$b]['x'],$bld[$r][$c][$b]['y'],$bld[$r][$c][$b]['ndv'],$b,3);
								$imgmap   .= "<area href=\"?lev=4&mde=$mde&fmt=png&loi=$loi&sta=". urlencode( TopoLoc($r,$c,$b) ) ."\" coords=\"".($bld[$r][$c][$b]['x']-$imas) .",". ($bld[$r][$c][$b]['y']-$imas) .",". ($bld[$r][$c][$b]['x']+$imas) .",". ($bld[$r][$c][$b]['y']+$imas)."\" shape=\"rect\" title=\"$sholbl\">\n";
							}elseif ($mde == "b"){
								DrawBuilding($bld[$r][$c][$b]['x'],$bld[$r][$c][$b]['y'],$r,$c,$b);
							}else{
								if ($loi){
									if(count($flr[$r][$c][$b]) > 1){
										$mapinfo .= DrawItem($bld[$r][$c][$b]['x'],$bld[$r][$c][$b]['y'],'0',$b." ".$bld[$r][$c][$b]['cmt'],'bi');
									}else{
										$mapinfo .= DrawLabel($bld[$r][$c][$b]['x'],$bld[$r][$c][$b]['y']-38,Safelabel($b),1,"cornflowerblue");
									}
								}
								$cd = 0;
								$nd = count( array_keys($flr[$r][$c][$b]) );
								foreach(Arrange($flr[$r][$c][$b]) as $d){
									list($dev[$d]['x'],$dev[$d]['y']) = CircleCoords($bld[$r][$c][$b]['x'],$bld[$r][$c][$b]['y'],$cd,$nd,$dev[$d]['nlk'],$fsz,0,0);
									$mapitems .= DrawItem($dev[$d]['x'],$dev[$d]['y'],'0',$d,'d');
									$imgmap   .= "<area href=\"Devices-Status.php?dev=".urlencode($d)."\" coords=\"".($dev[$d]['x']-$imas) .",". ($dev[$d]['y']-$imas) .",". ($dev[$d]['x']+$imas) .",". ($dev[$d]['y']+$imas)."\" shape=\"rect\" title=\"$dv ".$dev[$d]['ip']." CPU:".$dev[$d]['cpu']."%  T:".$dev[$d]['tmp']."C\">\n";
									if( $lev == 6){DrawNodes($d);}
									$cd++;
								}
							}
							$cb++;
						}
					}
					$cuc++;
				}
			}
			$cur++;
		}
	}

# Draw Links
	if($lev == 1){
		foreach(array_keys($rlnk) as $li){
			$l = explode(';;', $li);
			DrawLink($reg[$l[0]]['x'],
				$reg[$l[0]]['y'],
				$reg[$l[1]]['x'],
				$reg[$l[1]]['y'],
				$rlnk[$li]);
		}
	}elseif($lev == 2){
		foreach(array_keys($clnk) as $li){
			$l = explode(';;', $li);
			DrawLink($cty[$l[0]][$l[1]]['x'],
				$cty[$l[0]][$l[1]]['y'],
				$cty[$l[2]][$l[3]]['x'],
				$cty[$l[2]][$l[3]]['y'],
				$clnk[$li]);
		}
	}elseif($lev == 3){
		foreach(array_keys($blnk) as $li){
			$l = explode(';;', $li);
			DrawLink($bld[$l[0]][$l[1]][$l[2]]['x'],
				$bld[$l[0]][$l[1]][$l[2]]['y'],
				$bld[$l[3]][$l[4]][$l[5]]['x'],
				$bld[$l[3]][$l[4]][$l[5]]['y'],
				$blnk[$li]);
		}
	}elseif($lev > 3){
		foreach(array_keys($dlnk) as $li){
			$l = explode(';;', $li);
			DrawLink($dev[$l[0]]['x'],
				$dev[$l[0]]['y'],
				$dev[$l[1]]['x'],
				$dev[$l[1]]['y'],
				$dlnk[$li]);
		}
		if($lev == 6){
			foreach(array_keys($nlnk) as $li){
				$l = explode(';;', $li);
				DrawLink($dev[$l[0]]['x'],
					$dev[$l[0]]['y'],
					$nod[$l[1]]['x'],
					$nod[$l[1]]['y'],
					$nlnk[$li]);
			}
		}
	}
}

#===================================================================
# Calculate circular coordinates, dynlen sets radius to 0 on single points (except nodes)
function CircleCoords($x,$y,$curp,$nump,$nl,$l,$r,$dynlen=1){

	global $pwt;

	if($nump == 1 and $dynlen){
		$l = 0;
	}
	$mywt  = pow( ($nl)?$nl:1,$pwt/50);
	$phi   = $r * 0.0174533 + 2 * $curp * M_PI / $nump;
	return array( intval($x + $l * cos($phi) * 1.3 / $mywt), intval($y + $l * sin($phi) / $mywt) );
}

#===================================================================
# Lookup coordinates and return if map matches
function DbCoords($r='', $c='', $b=''){

	global $mapbg,$link;

	$query	= GenQuery('locations','s','x,y,locdesc','','',array('region','city','building'),array('=','=','='),array($r,$c,$b),array('AND','AND'));
	$res	= @DbQuery($query,$link);
	$nloc	= @DbNumRows($res);
	if(!$c){$r="";}elseif(!$b){$c="";}						# Clear those for Topomap()
	if ($nloc == 1 and $mapbg == TopoMap($r,$c) ) {
		return @DbFetchRow($res);
	}
}

#===================================================================
# Arrange locations according to links
function Arrange($circle){

	global $debug;

	$nodcircle  = array();
	$sortednod  = array();
	$hubweight  = array();
	$nbrnumber  = array();

	if($debug){echo "<div align=\"left\" class=\"code\">Prepare:\n";}

	foreach(array_keys($circle) as $node){
		if( is_array($circle[$node]['alk']) ){
			$nbr = array_keys($circle[$node]['alk']);
			if (count($nbr) == 1 ){							# 1 neighbor
				$nodcircle[$node] = $nbr[0];
				if($debug){echo "LEAF:$node -> $nbr[0]<br>";}
			}else{									# Several neighbors
				if($debug){echo "<p>HUB :$node<br>";}
				$nodcircle[$node] = $node;
				foreach($nbr as $n){
					if( is_array($circle[$n]['alk']) ){
						$hubweight[$node] += (count(array_keys($circle[$n]['alk'])) > 1)?2:1;
					}
					if($debug){echo "NBR :$n<br>";}
				}
				if($debug){echo "WGHT:$hubweight[$node]<br>";}
			}
		}else{
			$nodcircle[$node] = 0;
			if($debug){echo "UNL :$node<br>";}
		}
	}

	if($debug){echo "Align Hubs:\n";}
	arsort($hubweight);
 	foreach($hubweight as $curh => $cw){
		if($cw < 4){
			if($debug){echo "HUB :$curh pos$cw<br>";}
			foreach($hubweight as $nexth => $nw){
				if( in_array($curh, array_keys($circle[$nexth]['alk'])) and $cw < $nw){
					if($debug){echo "HLNK:$curh $nexth = $nw<br>";}
					if($nodcircle[$curh] == $curh){				# Only align hub, if not done before
						$nodcircle[$curh] = $nexth."0".$curh;		# Hub will come in before the one it's aligned to
						if($debug){echo "HALI:$nexth to $curh<br>";}
					}else{
						if($debug){echo "HDON:$nexth is $nodcircle[$nexth]<br>";}
					}
				}
			}
		}
	}

	if($debug){echo "Arrange:\n";}

	asort($nodcircle);
	foreach ($nodcircle as $node => $nbr){
		if(array_key_exists($node,$hubweight) ){
			$sortednod[$node] = $nbr . "2";						# Hubs weight 2
			if($debug){echo "<p>HUB :$nbr<br>";}
		}else{
			$nbrnumber[$nbr]++;
			if($nbrnumber[$nbr]%2 ){						# Distribute LEAFs around HUBs
				$sortednod[$node] = $nodcircle[$nbr] . "1$node";
				if($debug){echo "LEAF:$node = $nbr BELOW<br>";}
			}else{
				$sortednod[$node] = $nodcircle[$nbr] . "3$node";
				if($debug){echo "LEAF:$node = $nbr ABOVE<br>";}
			}
		}
		if($debug){echo "SORT:$sortednod[$node]<br>";}
	}

	asort($sortednod);
	$sortedkeys = array_keys($sortednod);
	$csiz = count($sortedkeys);
	$iter = 0;
	if($debug){echo "\nReposition nodes with 2 links crossing ($csiz total):\n";}
	do{
		$kpos = 0;
		foreach($sortedkeys as $k){
			if($debug){echo "REPO:iter$iter $k ";}
			if( is_array($circle[$k]['alk']) ){						# Any links?
				$nbr = array_keys($circle[$k]['alk']);
				if(count($nbr) == 2){							# We got 2 links?
					$npos1 = array_search($nbr[0],$sortedkeys);
					$npos2 = array_search($nbr[1],$sortedkeys);
					$ndst1 = abs(Dist($kpos,$npos1,$csiz) );
					$ndst2 = abs(Dist($kpos,$npos2,$csiz) );
					$ktonb = abs($ndst1)+abs($ndst2);
					$nbdst = Dist($npos1,$npos2,$csiz);
					if($debug){echo "pos$kpos distonbr $ndst1/$ndst2 ($npos1--[$nbdst]--$npos2) ";}
					if( $ktonb > abs($nbdst) + 1){					# add 1 to avoid flapping
						$nb1 = count(array_keys($circle[$nbr[0]]['alk']));
						$nb2 = count(array_keys($circle[$nbr[1]]['alk']));
						$mpos = ($nb1 < $nb2)?$npos1:$npos2;
						if($debug){echo "$nbr[0]-$nb1 vs $nbr[1]-$nb2 moving to $mpos (nbrdistance $nbdst)\n";}
						array_splice($sortedkeys,$kpos,1);			# remove it
						if($debug){echo "moving to $mpos\n";}
						if($nbdst > 0){
							array_splice($sortedkeys,$mpos-1,0,$k);		# and instert after first nbr
						}else{
							array_splice($sortedkeys,$mpos,0,$k);		# or before if 0 crossing
						}
						break 1;
					}else{
						if($debug){echo "stays\n";}
					}
				}else{
					if($debug){echo count($nbr)." neighbor(s)\n";}
				}
			}else{
				if($debug){echo "no links\n";}
			}
			$kpos++;
		}
		if($kpos == $csiz){									# Went through whole array lets end
			if($debug){echo "REPO:iter$iter reached pos$kpos, done!\n";}
			$iter = $csiz;
		}
		$iter++;
	}while($iter < $csiz);
	if($debug){echo "<p>Return Sorted Array:\n";print_r($sortedkeys);echo "</div>";}
	return $sortedkeys;
	}

#===================================================================
# Return shorter distance between 2 nodes
function Dist($a, $b,$s){
	$d1 = abs($a - $b);
	$d2 = $d1 - $s;											# Negative number for 0 crossing
	return ($d1 <= abs($d2) )?$d1:$d2;
}

#===================================================================
# Sort by room and #device links within floor
function Roomsort($a, $b){

	global $dev,$debug;

        if ($dev[$a]['rom'] == $dev[$b]['rom']){
		if($debug){echo $dev[$a]['nlk']." == ".$dev[$b]['nlk']." linksort $a,$b<br>";}
		if ($dev[$a]['nlk'] == $dev[$b]['nlk']) return 0;
		return ($dev[$a]['nlk'] > $dev[$b]['nlk']) ? -1 : 1;
		#return ($a < $b) ? -1 : 1; Former name based sorting
	}
        return ($dev[$a]['rom'] < $dev[$b]['rom']) ? -1 : 1;
}

//===============================================================================
// Returns join based on column TODO forget about this (too complex fir librep)?

function JoinDev($ina){

	if($ina == 'vlanid'){
		return array($ina,'LEFT JOIN vlans USING (device)');
	}elseif($ina == 'ifip' or $ina == 'vrfname'){
		return array($ina,'LEFT JOIN networks USING (device)');
	}elseif($ina == 'neighbor'){
		$ina = "CONCAT(device,neighbor)";#TODO This only works with like! Find better solution (new function in DB to gather devs within #of hops?)
		return array($ina,'LEFT JOIN links USING (device)');
	}else{
		return array($ina,'');
	}
}

?>
