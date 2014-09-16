<?php
# Program: Devices-List.php
# Programmer: Remo Rickli

$printable = 1;
$exportxls = 1;

error_reporting(1);
snmp_set_quick_print(1);
snmp_set_oid_numeric_print(1);
snmp_set_valueretrieval(SNMP_VALUE_LIBRARY);

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

$mon = isset($_GET['mon']) ? 1 : 0;
$del = isset($_GET['del']) ? $_GET['del'] : "";

if( isset($_GET['col']) ){
	$col = $_GET['col'];
	if($_SESSION['opt']) $_SESSION['devcol'] = $col;
}elseif( isset($_SESSION['devcol']) ){
	$col = $_SESSION['devcol'];
}else{
	$col = array('imgNS','serial','type','asset','location','endmaint','lastwty');
}

$cols = array(
		'state'=>$stalbl,
		'serial'=>$serlbl,
		'assetclass'=>$clalbl,
		'assettype'=>$typlbl,
		'assetnumber'=>"$invlbl $numlbl",
		'assetlocation'=>$loclbl,
		'assetcontact'=>$conlbl,
		'assetupdate'=>$updlbl,
		'pursource'=>$srclbl,
		'purcost'=>"$purlbl $coslbl",
		'purnumber'=>"$purlbl $numlbl",
		'purtime'=>"$purlbl $timlbl",
		'maintpartner'=>$igrp['17'],
		'maintsla'=>"$igrp[31] $levlbl",
		'maintdesc'=>"$igrp[31] $detlbl",
		'maintcost'=>"$igrp[31] $coslbl",
		'maintstatus'=>"$igrp[31] $stalbl",
		'startmaint'=>"$igrp[31] $sttlbl",
		'endmaint'=>"$igrp[31] $endlbl",
		'endwarranty'=>"$wtylbl $endlbl",
		'endsupport'=>"$wtylbl $endlbl",
		'endlife'=>$endlbl,
		'comment'=>$cmtlbl,
		'usrname'=>$usrlbl
		);

$link = DbConnect($dbhost,$dbuser,$dbpass,$dbname);							# Above print-header!
?>
<h1><?= $igrp['20'] ?> <?= $lstlbl ?></h1>

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
	<a href="?in[]=snmpversion&op[]=>&st[]=0&lim=<?= $listlim ?>"><img src="img/16/dev.png" title="SNMP Devices"></a>
	<a href="?in[]=cliport&op[]=%3D&st[]=1&co[]=&in[]=lastdis&op[]=~&st[]=&co[]=&in[]=device&op[]=~&st[]=&co[]=&in[]=device&op[]=~&st[]=&col[]=device&col[]=devip&col[]=location&col[]=contact&col[]=firstdis&col[]=lastdis&ord=lastdis+desc"><img src="img/16/kons.png" title="CLI <?= $errlbl ?>"></a>
	<br>
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
<?php  if($isadmin) { ?>
	<br>
	<input type="submit" class="button" name="mon" value="<?= $monlbl ?>" onclick="return confirm('<?= $monlbl ?> <?= $addlbl ?>?')" >
	<br>
	<input type="submit" class="button" name="del" value="<?= $dellbl ?>" onclick="return confirm('<?= $dellbl ?>, <?= $cfmmsg ?>')" >
<?php } ?>
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

	if(  in_array('device',$in) or in_array('device',$col) ){
		$moq = 1;
		$in = array_map("AddDevs", $in);
		$query	= GenQuery('inventory','s','inventory.*,device,devices.location,devices.contact',$ord,$lim,$in,$op,$st,$co,'LEFT JOIN devices USING (serial)' );
	}else{
		$query	= GenQuery('inventory','s','*',$ord,$lim,$in,$op,$st,$co);
	}

	Condition($in,$op,$st,$co);

	TblHead("bgsub",1);

	$res	= DbQuery($query,$link);
	if($res){
		$row   = 0;
		$most = '';
		while( ($item = DbFetchRow($res)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			TblRow($bg);
			list($mcl,$img) = ModClass($item[2]);
			list($fc,$lc)	= Agecol($dev[4],$dev[5],$row % 2);
			if( in_array('state',$col) )	TblCell("<a href=\"Assets-Inventory.php?chg=".urlencode($item[1])."&lst=$lst&val=$uv\">".Staimg($item[0])."</a>",'',"ctr $bi xs" );
			if(in_array('serial',$col))	TblCell( $item[1] );
			if(in_array('assetclass',$col))	TblCell( "$mcl ($item[2])" );
			if(in_array('assettype',$col))	TblCell( $item[3] );
			if(in_array('assetnumber',$col))TblCell( $item[4] );
			if(in_array('assetlocation',$col))TblCell( $item[5] );
			if(in_array('assetcontact',$col))TblCell( $item[6] );
			if(in_array('assetupdate',$col))TblCell( $item[7] );
			if(in_array('pursource',$col))	TblCell( $item[8],"?in[]=pursource&op[]==&st[]=".urlencode($item[8]) );
			if(in_array('purcost',$col))	TblCell( $item[9] );
			if(in_array('purnumber',$col))	TblCell( $item[10],"?in[]=location&op[]==&st[]=".urlencode($item[4]) );
			if(in_array('purtime',$col))	TblCell( $item[11],"?in[]=location&op[]==&st[]=".urlencode($item[4]) );
			if(in_array('maintpartner',$col))TblCell( $item[12],"?in[]=location&op[]==&st[]=".urlencode($item[4]) );
			if(in_array('maintsla',$col))	TblCell( $item[13],"?in[]=location&op[]==&st[]=".urlencode($item[4]) );
			if(in_array('maintdesc',$col))	TblCell( $item[14],"?in[]=location&op[]==&st[]=".urlencode($item[4]) );
			if(in_array('maintcost',$col))	TblCell( $item[15],"?in[]=location&op[]==&st[]=".urlencode($item[4]) );
			if(in_array('maintstatus',$col))TblCell( $item[16],"?in[]=location&op[]==&st[]=".urlencode($item[4]) );
			if(in_array('startmaint',$col))	TblCell( $item[17],"?in[]=location&op[]==&st[]=".urlencode($item[4]) );
			if(in_array('endmaint',$col))	TblCell( $item[18],"?in[]=location&op[]==&st[]=".urlencode($item[4]) );
			if(in_array('endwarranty',$col))TblCell( $item[19],"?in[]=location&op[]==&st[]=".urlencode($item[4]) );
			if(in_array('endsupport',$col))	TblCell( $item[20],"?in[]=location&op[]==&st[]=".urlencode($item[4]) );
			if(in_array('endlife',$col))	TblCell( $item[21],"?in[]=location&op[]==&st[]=".urlencode($item[4]) );
			if(in_array('comment',$col))	TblCell( $item[22],"?in[]=location&op[]==&st[]=".urlencode($item[4]) );
			if(in_array('usrname',$col))	TblCell( $item[23],"?in[]=location&op[]==&st[]=".urlencode($item[4]) );
			echo "	</tr>\n";
		}
		DbFreeResult($res);
	}else{
		print DbError($link);
	}
	TblFoot("bgsub", count($col), "$row Devices".(($ord)?", $srtlbl: $ord":"").(($lim)?", $limlbl: $lim":"") );
}
include_once ("inc/footer.php");
?>
