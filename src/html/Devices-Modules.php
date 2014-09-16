<?php
# Program: Devices-Modules.php
# Programmer: Remo Rickli

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

$map = isset($_GET['map']) ? "checked" : "";
$lim = isset($_GET['lim']) ? preg_replace('/\D+/','',$_GET['lim']) : $listlim;

if( isset($_GET['col']) ){
	$col = $_GET['col'];
	if($_SESSION['opt']) $_SESSION['modcol'] = $_GET['col'];
}elseif( isset($_SESSION['modcol']) ){
	$col = $_SESSION['modcol'];
}else{
	$col = array('imBL','device','slot','model','moddesc','modules.serial');
}

$cols = array(	"imBL"=>$imglbl,
		"modclass"=>$clalbl,
		"device"=>"Device $namlbl",
		"type"=>"Device $typlbl",
		"description"=>"Device $deslbl",
		"location"=>$loclbl,
		"contact"=>$conlbl,
		"firstdis"=>"$fislbl $dsclbl",
		"lastdis"=>"$laslbl $dsclbl",
		"slot"=>"Slot",
		"model"=>$mdllbl,
		"moddesc"=>"Module $deslbl",
		"modules.serial"=>$serlbl,
		"hw"=>"Hardware",
		"fw"=>"Firmware",
		"sw"=>"Software",
		"modidx"=>$idxlbl,
		"status"=>$stalbl,
		"modloc"=>"Module $loclbl"
		);

$link = DbConnect($dbhost,$dbuser,$dbpass,$dbname);							# Above print-header!
?>
<h1>Module <?= $lstlbl ?></h1>

<?php  if( !isset($_GET['print']) and !isset($_GET['xls']) ) { ?>
<form method="get" name="list" action="<?= $self ?>.php">
<table class="content"><tr class="bgmain">
<td class="ctr s">
	<a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png" title="<?= $self ?>"></a>
</td>
<td>
<?php Filters(); ?>
</td>
<td class="ctr">
	<select multiple name="col[]" size="6" title="<?= $collbl ?>">
<?php
foreach ($cols as $k => $v){
       echo "		<option value=\"$k\"".((in_array($k,$col))?" selected":"").">$v\n";
}
?>
	</select>
</td>
<td>
	<img src="img/16/paint.png" title="<?= (($verb1)?"$sholbl $laslbl Map":"Map $laslbl $sholbl") ?>"> 
	<input type="checkbox" name="map" <?= $map ?>>
	<br>
	<img src="img/16/form.png" title="<?= $limlbl ?>"> 
	<select size="1" name="lim">
<?php selectbox("limit",$lim) ?>
	</select>
</td>
<td class="ctr s">
	<input type="submit" class="button" value="<?= $sholbl ?>">
</td>
</tr></table>
</form>
<p>
<?php
}
if( count($in) ){
	if ($map and !isset($_GET['xls']) and file_exists("map/map_$_SESSION[user].php")) {
		echo "<div class=\"ctr\">\n	<h2>$netlbl Map</h2>\n";
		echo "	<img src=\"map/map_$_SESSION[user].php\" style=\"border:1px solid black\">\n</div>\n<p>\n";
	}
	Condition($in,$op,$st,$co);
	TblHead("bgsub",1);

	$query	= GenQuery('modules','s','modules.*,type,firstdis,lastdis,description,location,contact',$ord,$lim,$in,$op,$st,$co,'LEFT JOIN devices USING (device)');
	$res	= DbQuery($query,$link);
	if($res){
		$row = 0;
		while( ($m = DbFetchRow($res)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$ud = urlencode($m[0]);
			list($fc,$lc) = Agecol($m[13],$m[14],$row % 2);
			list($mcl,$img) = ModClass($m[9]);

			TblRow($bg);
			if(in_array("imBL",$col))	TblCell("<img src=\"img/16/$img.png\" title=\"$mcl ($m[9])\">",'',"$bi ctr s");
			if(in_array("modclass",$col))	TblCell( $mcl,"?in[]=modclass&op[]==&st[]=$m[9]");
			if(in_array("device",$col))	TblCell($m[0],"?in[]=device&op[]==&st[]=$ud",'',"<a href=\"Devices-Status.php?dev=$ud\"><img src=\"img/16/sys.png\"></a>");
			if(in_array("type",$col))	TblCell( $m[12],"?in[]=type&op[]==&st[]=".urlencode($m[12]) );
			if(in_array("description",$col))TblCell( $m[15],"?in[]=type&op[]==&st[]=".urlencode($m[15]) );
			if(in_array("location",$col))	TblCell( $m[16],"?in[]=location&op[]==&st[]=".urlencode($m[16]) );
			if(in_array("contact",$col))	TblCell( $m[17],"?in[]=contact&op[]==&st[]=".urlencode($m[17]) );
			if( in_array("firstdis",$col) )	TblCell( date($_SESSION['timf'],$m[13]),"?in[]=firstdis&op[]==&st[]=$m[13]",'nw','',"background-color:#$fc" );
			if( in_array("lastdis",$col) )	TblCell( date($_SESSION['timf'],$m[14]),"?in[]=lastdis&op[]==&st[]=$m[14]",'nw','',"background-color:#$lc" );
			if(in_array("slot",$col))	TblCell( $m[1],"?in[]=slot&op[]==&st[]=".urlencode($m[1]));
			if(in_array("model",$col))	TblCell( $m[2],"?in[]=model&op[]==&st[]=".urlencode($m[2]) );
			if(in_array("moddesc",$col))	TblCell( $m[3] );
			if(in_array("modules.serial",$col))TblCell( $m[4],"?in[]=modules.serial&op[]==&st[]=".urlencode($m[4]) );
			if(in_array("hw",$col))		TblCell( $m[5],"Nodes-Status.php?mac=$m[5]" );
			if(in_array("fw",$col))		TblCell( $m[6],"?in[]=fw&op[]==&st[]=".urlencode($m[6]) );
			if(in_array("sw",$col))		TblCell( $m[7],"?in[]=sw&op[]==&st[]=".urlencode($m[7]) );
			if(in_array("modidx",$col))	TblCell($m[8],"?in[]=modidx&op[]==&st[]=$m[8]");
			if(in_array("status",$col))	TblCell($m[10],"?in[]=status&op[]==&st[]=$m[10]");
			if(in_array("modloc",$col))	TblCell( $m[11],"?in[]=modloc&op[]==&st[]=".urlencode($m[11]) );
			echo "	</tr>\n";
		}
		DbFreeResult($res);
	}else{
		print DbError($link);
	}
	TblFoot("bgsub", count($col), "$row Modules".(($ord)?", $srtlbl: $ord":"").(($lim)?", $limlbl: $lim":"") );
}
include_once ("inc/footer.php");
?>
