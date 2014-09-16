<?php
# Program: Devices-Inventory.php
# Programmer: Remo Rickli

$printable = 1;
$exportxls = 0;
if( isset($_GET['lst']) ){$exportxls = 1;}

include_once ("inc/header.php");
include_once ("inc/libdev.php");
$_GET = sanitize($_GET);
$chg = isset($_GET['chg']) ? $_GET['chg'] : "";
$add = isset($_GET['add']) ? $_GET['add'] : "";
$upd = isset($_GET['upd']) ? $_GET['upd'] : "";
$del = isset($_GET['del']) ? $_GET['del'] : "";

$lst = isset($_GET['lst']) ? $_GET['lst'] : "";
$val = isset($_GET['val']) ? $_GET['val'] : "";

$link	= DbConnect($dbhost,$dbuser,$dbpass,$dbname);
if($chg){
	$query	= GenQuery('inventory','s','*','','',array('serial'),array('='),array($chg) );
	$res	= DbQuery($query,$link);
	$nitm	= DbNumRows($res);
	if ($nitm != 1) {
		echo "<h4>$chg: $nitm $vallbl!</h4>";
		DbFreeResult($res);
	}else{
		$item = DbFetchRow($res);
	}
	DbFreeResult($res);
	$sta  = $item[0];

	$sn   = $item[1];
	$typ  = $item[2];
	$as   = $item[3];
	$lo   = $item[4];

	$ps   = $item[5];
	$pc   = $item[6];
	$pn   = $item[7];
	$ti   = date("m/d/Y",$item[8]);

	$mp   = $item[9];
	$sm   = date("m/d/Y",$item[10]);
	$em   = date("m/d/Y",$item[11]);
	$lw   = date("m/d/Y",$item[12]);

	$com  = $item[13];
	$usr  = $item[14];
}else{
	$sta = isset($_GET['sta']) ? $_GET['sta'] : (($lst == 'st') ? $val : 10);

	$sn = isset($_GET['sn']) ? $_GET['sn'] : "";
	$typ= isset($_GET['typ']) ? $_GET['typ'] : (($lst == 'typ') ? $val : '');
	$as = isset($_GET['as']) ? $_GET['as'] : (($lst == 'as') ? $val : '');
	$lo = isset($_GET['lo']) ? $_GET['lo'] : (($lst == 'lo') ? $val : '');

	$ps = isset($_GET['ps']) ? $_GET['ps'] : (($lst == 'ps') ? $val : '');
	$pc = isset($_GET['pc']) ? $_GET['pc'] : (($lst == 'pc') ? $val : '');
	$pn = isset($_GET['pn']) ? $_GET['pn'] : (($lst == 'pn') ? $val : '');
	$ti = isset($_GET['ti']) ? $_GET['ti'] : (($lst == 'ti') ? $val : '');

	$mp = isset($_GET['mp']) ? $_GET['mp'] : (($lst == 'mp') ? $val : '');
	$sm = isset($_GET['sm']) ? $_GET['sm'] : (($lst == 'sm') ? $val : '');
	$em = isset($_GET['em']) ? $_GET['em'] : (($lst == 'em') ? $val : '');
	$lw = isset($_GET['lw']) ? $_GET['lw'] : (($lst == 'lw') ? $val : '');

	$com = isset($_GET['com']) ? preg_replace('/[\r\n]+/', ' ', $_GET['com']) : '';
}

echo strtotime('');
?>
<h1>Device <?= $invlbl ?></h1>

<?php  if( !isset($_GET['print']) and !isset($_GET['xls']) ) { ?>
<form method="get" action="<?= $self ?>.php" name="bld">
<table class="content"><tr class="<?= $modgroup[$self] ?>1">
<td class="ctr s">
	<a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png"></a>
</td>
<td>
	<select size="4" name="sta">
<?php
foreach (array_keys($stco) as $c){
	echo "		<option value=\"$c\" ".( ($c == $sta)?" selected":"").">$stco[$c]\n";
}
?>
	</select>
</td>
<td>
	<img src="img/16/bbox.png" title="<?= $invlbl ?>">
	<input type="text" title="<?= $invlbl ?> <?= $serlbl ?>" placeholder="<?= $serlbl ?>" name="sn" value="<?= $sn ?>" class="m" OnFocus="select();" <?= (($chg)?"readonly":"") ?>>
	<input type="text" title="<?= $invlbl ?> <?= $typlbl ?>" placeholder="<?= $typlbl ?>" name="typ" value="<?= $typ ?>" class="m" OnFocus="select();" onClick="window.open('inc/browse-img.php?t=p','Panels','scrollbars=1,menubar=0,resizable=1,width=600,height=800');" title="<?= $sellbl ?> <?= $imglbl ?>">
	<input type="text" title="<?= $invlbl ?> <?= $numlbl ?>" placeholder="<?= $numlbl ?>" name="as" value="<?= $as ?>" class="m" OnFocus="select();">
	<input type="text" title="<?= $invlbl ?> <?= $loclbl ?>" placeholder="<?= $loclbl ?>" name="lo" value="<?= $lo ?>" class="xl" OnFocus="select();">
	<br>
	<img src="img/16/cash.png" title="<?= $purlbl ?>"> 
	<input type="text" title="<?= $purlbl ?> <?= $coslbl ?>" placeholder="<?= $coslbl ?>" name="pc" value="<?= $pc ?>" class="m" OnFocus="select();">
	<input type="text" title="<?= $purlbl ?> <?= $numlbl ?>" placeholder="<?= $numlbl ?>" name="pn" value="<?= $pn ?>" class="m" OnFocus="select();">
	<input type="text" title="<?= $purlbl ?> <?= $timlbl ?>" placeholder="<?= $timlbl ?>" name="ti" id="ti" value="<?= $ti ?>" class="m" OnFocus="select();">
	<input type="text" title="<?= $purlbl ?> <?= $srclbl ?>" placeholder="<?= $srclbl ?>" name="ps" value="<?= $ps ?>" class="l" OnFocus="select();">
	<br>
	<img src="img/16/dril.png" title="<?= $igrp['31'] ?>"> 
	<input type="text" title="<?= $igrp['31'] ?> <?= $sttlbl ?>" placeholder="<?= $sttlbl ?>" name="sm" id="sm" value="<?= $sm ?>" class="m" OnFocus="select();">
	<input type="text" title="<?= $igrp['31'] ?> <?= $endlbl ?>" placeholder="<?= $endlbl ?>" name="em" id="em" value="<?= $em ?>" class="m" OnFocus="select();">
	<input type="text" title="<?= $venlbl ?> <?= $wtylbl ?>" placeholder="<?= $wtylbl ?>/EoL" name="lw" id="lw" value="<?= $lw ?>" class="m" OnFocus="select();">
	<input type="text" title="<?= $igrp['31'] ?> <?= $igrp['17'] ?>" placeholder="<?= $igrp['17'] ?>" name="mp" value="<?= $mp ?>" class="l" OnFocus="select();">
	<br>
	<script type="text/javascript" src="inc/datepickr.js"></script>
	<link rel="stylesheet" type="text/css" href="inc/datepickr.css" />
	<script>
	new datepickr('ti', {'dateFormat': 'm/d/y'});
	new datepickr('sm', {'dateFormat': 'm/d/y'});
	new datepickr('em', {'dateFormat': 'm/d/y'});
	new datepickr('lw', {'dateFormat': 'm/d/y'});
	</script>
</td>
<td>
	<textarea rows="3" name="com" cols="20" placeholder="<?= $cmtlbl ?>"><?= $com ?></textarea>
</td>
<td class="ctr s">
	<input type="hidden" value="<?= $lst ?>" name="lst">
	<input type="hidden" value="<?= $val ?>" name="val">
<?php
if($chg or $upd or $add){
	echo "	<input type=\"submit\" class=\"button\" value=\"$updlbl\" name=\"upd\"><p>\n";
	echo "	<input type=\"submit\" class=\"button\" value=\"$dellbl\" name=\"del\">";
}else{
	echo "	<input type=\"submit\" class=\"button\" value=\"$addlbl\" name=\"add\">";
}
?>
</td></tr></table>
</form>
<p>

<script type="text/javascript">
<?php
if($chg){
	echo "document.add.lo.focus();\n";
}else{
	echo "document.add.sn.focus();\n";
}
?>
</script>
<?php
}

$tis = strtotime( preg_replace("/\s.*$/", "", $ti) );							# Forget the hour TODO fix in datepickr!
$sms = strtotime( preg_replace("/\s.*$/", "", $sm) );
$ems = strtotime( preg_replace("/\s.*$/", "", $em) );
$wint= strtotime( preg_replace("/\s.*$/", "", $lw) );

if ($add and $sn and $typ){
	$query	= GenQuery('inventory','i','','','',array('serial','type','usrname','asupdate','location','state','comment','lastwty','source','asset','cost','ponumber','time','partner','startmaint','endmaint'),array(),array($sn,$typ,$_SESSION['user'],time(),$lo,$sta,$com,($wint === FALSE)?0:$wint,$ps,$as,($pc)?$pc:0,$pn,($tis === FALSE)?0:$tis,$mp,($sms === FALSE)?0:$sms,($ems === FALSE)?0:$ems) );
	if( !DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$serlbl $sn $updlbl OK</h5>";}
}elseif ($upd and $sn and $typ and $lo){
	$query	= GenQuery('inventory','u',"serial = '".DbEscapeString($sn)."'",'','',array('type','usrname','asupdate','location','state','comment','lastwty','source','asset','cost','ponumber','time','partner','startmaint','endmaint'),array(),array($typ,$_SESSION['user'],time(),$lo,$sta,$com,($wint === FALSE)?0:$wint,$ps,$as,($pc)?$pc:0,$pn,($tis === FALSE)?0:$tis,$mp,($sms === FALSE)?0:$sms,($ems === FALSE)?0:$ems) );
	if( !DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$serlbl $sn $updlbl OK</h5>";}
}elseif($del ){
	$query	= GenQuery('inventory','d','','','',array('serial'),array('='),array($sn) );
	if( !DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$serlbl $del $dellbl OK</h5>";}
}

if($lst){
	if($lst == "ty"){
		echo "<h2>$typlbl \"$val\" $lstlbl</h2>\n";
		$col = "type";
	}elseif($lst == "lo"){
		echo "<h2>$loclbl \"$val\" $lstlbl</h2>\n";
		$col = "location";
	}elseif($lst == "st"){
		echo "<h2>$stalbl \"$stco[$val]\" $lstlbl</h2>\n";
		$col = "state";
	}elseif($lst == "us"){
		echo "<h2>$usrlbl \"$val\" $lstlbl</h2>\n";
		$col = "usrname";
	}elseif($lst == "ps"){
		echo "<h2>$srclbl \"$val\" $lstlbl</h2>\n";
		$col = "source";
	}elseif($lst == "mp"){
		echo "<h2>$igrp[14] \"$val\" $lstlbl</h2>\n";
		$col = "partner";
	}elseif($lst == "em"){
		echo "<h2>$igrp[31] \"".date($_SESSION['datf'],$val)."\" $lstlbl</h2>\n";
		$col = "endmaint";
	}else{
		echo "<h2>$wtylbl \"".date($_SESSION['datf'],$val)."\" $lstlbl</h2>\n";
		$col = "lastwty";
	}
?>
<table class="content">
	<tr class="<?= $modgroup[$self] ?>2">
<?php
	TblCell('','s');
	TblCell($serlbl,'','ctr b',"+<img src=\"img/16/key.png\"><br>");
	TblCell($typlbl,'','ctr b',"+<img src=\"img/16/abc.png\"><br>");
	TblCell($invlbl,'','ctr b',"+<img src=\"img/16/bbox.png\"><br>");
	TblCell($loclbl,'','ctr b',"+<img src=\"img/16/home.png\"><br>");
	TblCell($coslbl,'','ctr b',"+<img src=\"img/16/cash.png\"><br>");
	TblCell($numlbl,'','ctr b',"+<img src=\"img/16/form.png\"><br>");
	TblCell($purlbl,'','ctr b',"+<img src=\"img/16/date.png\"><br>");
	TblCell($srclbl,'','ctr b',"+<img src=\"img/16/ugrp.png\"><br>");
	TblCell($igrp['17'],'','ctr b',"+<img src=\"img/16/dril.png\"><br>");
	TblCell($sttlbl,'','ctr b',"+<img src=\"img/16/bblf.png\"><br>");
	TblCell($endlbl,'','ctr b',"+<img src=\"img/16/bbrt.png\"><br>");
	TblCell($wtylbl,'','ctr b',"+<img src=\"img/16/bbr2.png\"><br>");
	TblCell($cmtlbl,'','ctr b',"+<img src=\"img/16/say.png\"><br>");
	TblCell($usrlbl,'','ctr b',"+<img src=\"img/16/user.png\"><br>");
	TblCell($updlbl,'','ctr b',"+<img src=\"img/16/clock.png\"><br>");
	echo "	</tr>\n";

	$query	= GenQuery('inventory','s','*','type,serial','',array("$col"),array('='),array("$val") );
	$res	= DbQuery($query,$link);
	if($res){
		$row = 0;
		$uv  = urlencode($val);
		while( ($item = DbFetchRow($res)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			list($a1c,$a2c) = Agecol($item[15],$item[15],$row % 2);
			TblRow($bg);
			TblCell( "<a href=\"?chg=".urlencode($item[1])."&lst=$lst&val=$uv\">".Staimg($item[0])."</a>",'',"ctr $bi");
			TblCell( $item[1],'',"mrn" );
			TblCell( $item[2],"?lst=ty&val=".urlencode($item[2]) );
			TblCell( $item[3] );
			TblCell( $item[4],"?lst=lo&val=".urlencode($item[4]) );
			TblCell( $item[6] );
			TblCell( $item[7] );
			TblCell( ($item[8])?date($_SESSION['datf'],$item[8]):'-' );
			TblCell( $item[5],"?lst=ps&val=".urlencode($item[5]) );
			TblCell( $item[9],"?lst=mp&val=".urlencode($item[9]) );
			TblCell( ($item[10])?date($_SESSION['datf'],$item[10]):'-' );
			TblCell( ($item[11])?date($_SESSION['datf'],$item[11]):'-',"?lst=em&val=$item[11]",''.SupportBg($item[11]) );
			TblCell( ($item[12])?date($_SESSION['datf'],$item[12]):'-',"?lst=lw&val=$item[12]",''.SupportBg($item[12]) );
			TblCell( $item[13] );
			TblCell( $item[14],"?lst=us&val=".urlencode($item[14]) );
			TblCell( date($_SESSION['datf'],$item[15]),'','','',"background-color:#$a1c" );
			echo "	</tr>\n";
		}
		DbFreeResult($res);
	}else{
		print DbError($link);
	}
?>
</table>
<table class="content"><tr class="<?= $modgroup[$self] ?>2"><td>
<?= $row ?> <?= $vallbl ?>
</td></tr></table>
<?php
	include_once ("inc/footer.php");
	exit;
}
?>

<table class="full fixed"><tr><td class="helper">

<h2><?= $invlbl ?></h2>
<table class="content">
	<tr class="<?= $modgroup[$self] ?>2">
		<th><img src="img/16/abc.png"><br><?= $typlbl ?></th>
		<th><img src="img/16/form.png"><br><?= $qtylbl ?></th>

<?php
$query	= GenQuery('inventory','g','type');
$res	= DbQuery($query,$link);
if($res){
	$row = 0;
	while( ($item = DbFetchRow($res)) ){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		$stbar = Bar($item[1],-10,'mi');
		echo "	<tr class=\"$bg\">\n";
		echo "		<td><a href=\"?lst=ty&val=".urlencode($item[0])."\">$item[0]</a></td>\n";
		echo "		<td>$stbar $item[1]</td>\n";
		echo "	</tr>\n";
	}
}
?>
</table>
<table class="content"><tr class="<?= $modgroup[$self] ?>2"><td>
<?= $row ?> <?= $vallbl ?>
</td></tr></table>

</td><td class="helper">

<h2><?= $chglbl ?> <?= $sumlbl ?></h2>
<table class="content">
	<tr class="<?= $modgroup[$self] ?>2">
		<th colspan="2"><img src="img/16/clock.png"><br><?= $updlbl ?></th>
		<th><img src="img/16/abc.png"><br><?= $typlbl ?></th>
		<th><img src="img/16/user.png"><br><?= $usrlbl ?></th>
		<th><img src="img/16/say.png"><br><?= $cmtlbl ?></th>
	</tr>
<?php
$query	= GenQuery('inventory','s','*','time desc',$_SESSION['lim']);
$res	= DbQuery($query,$link);
if($res){
	$row = 0;
	while( ($item = DbFetchRow($res)) ){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		list($a1c,$a2c) = Agecol($item[15],$item[15],$row % 2);
		echo "	<tr class=\"$bg\">\n";
		echo "		<td class=\"$bi ctr\">".Staimg($item[0])."</th>\n";
		echo "		<td style=\"background-color:#$a1c\">".date($_SESSION['timf'],$item[15])."</td>\n";
		echo "		<td><a href=\"?lst=ty&val=".urlencode($item[2])."\">$item[2]</a></td>\n";
		echo "		<td><a href=\"?lst=us&val=".urlencode($item[14])."\">$item[14]</a></td>\n";
		echo "		<td>$item[13]</td>";
		echo "	</tr>\n";
	}
}
?>
</table>
<table class="content"><tr class="<?= $modgroup[$self] ?>2"><td>
<?= $row ?> <?= $vallbl ?>
</td></tr></table>

<h2><?= $loclbl ?> <?= $sumlbl ?></h2>
<table class="content">
	<tr class="<?= $modgroup[$self] ?>2">
		<th><img src="img/16/home.png"><br><?= $loclbl ?></th>
		<th class="l"><img src="img/16/form.png"><br><?= $qtylbl ?></th>
	</tr>
<?php
$query	= GenQuery('inventory','g','location');
$res	= DbQuery($query,$link);
if($res){
	$row = 0;
	while( ($item = DbFetchRow($res)) ){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		$stbar = Bar($item[1],0,'mi');
		echo "	<tr class=\"$bg\">\n";
		echo "		<td><a href=\"?lst=lo&val=".urlencode($item[0])."\">$item[0]</a></td>\n";
		echo "		<td>$stbar $item[1]</td>\n";
		echo "	</tr>\n";
	}
}
?>
</table>
<table class="content"><tr class="<?= $modgroup[$self] ?>2"><td>
<?= $row ?> <?= $vallbl ?>
</td></tr></table>

<h2><?= $srclbl ?> <?= $sumlbl ?></h2>
<table class="content">
	<tr class="<?= $modgroup[$self] ?>2">
		<th><img src="img/16/ugrp.png"><br><?= $srclbl ?></th>
		<th class="l"><img src="img/16/form.png"><br><?= $qtylbl ?></th>
	</tr>
<?php
$query	= GenQuery('inventory','g','source');
$res	= DbQuery($query,$link);
if($res){
	$row = 0;
	while( ($item = DbFetchRow($res)) ){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		$stbar = Bar($item[1],0,'mi');
		echo "	<tr class=\"$bg\">\n";
		echo "		<td><a href=\"?lst=ps&val=".urlencode($item[0])."\">$item[0]</a></td>\n";
		echo "		<td>$stbar $item[1]</td>\n";
		echo "	</tr>\n";
	}
}
?>
</table>
<table class="content"><tr class="<?= $modgroup[$self] ?>2"><td>
<?= $row ?> <?= $vallbl ?>
</td></tr></table>

<h2><?= $igrp['17'] ?> <?= $sumlbl ?></h2>
<table class="content">
	<tr class="<?= $modgroup[$self] ?>2">
		<th><img src="img/16/dril.png"><br><?= $igrp['17'] ?></th>
		<th class="l"><img src="img/16/form.png"><br><?= $qtylbl ?></th>
	</tr>
<?php
$query	= GenQuery('inventory','g','partner');
$res	= DbQuery($query,$link);
if($res){
	$row = 0;
	while( ($item = DbFetchRow($res)) ){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		$stbar = Bar($item[1],0,'mi');
		echo "<tr class=\"$bg\">\n";
		echo "<td><a href=\"?lst=mp&val=".urlencode($item[0])."\">$item[0]</a></td><td>$stbar $item[1]</td></tr>\n";
	}
}
?>
</table>
<table class="content"><tr class="<?= $modgroup[$self] ?>2"><td>
<?= $row ?> <?= $vallbl ?>
</td></tr></table>

<h2><?= $usrlbl ?> <?= $sumlbl ?></h2>
<table class="content">
	<tr class="<?= $modgroup[$self] ?>2">
		<th><img src="img/16/user.png"><br><?= $usrlbl ?></th>
		<th class="l"><img src="img/16/form.png"><br><?= $qtylbl ?></th>
<?php
$query	= GenQuery('inventory','g','usrname');
$res	= DbQuery($query,$link);
if($res){
	$row = 0;
	while( ($item = DbFetchRow($res)) ){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		$stbar = Bar($item[1],0,'mi');
		echo "	<tr class=\"$bg\">\n";
		echo "		<td><a href=\"?lst=us&val=".urlencode($item[0])."\">$item[0]</a></td>\n";
		echo "		<td>$stbar $item[1]</td>\n";
		echo "	</tr>\n";
	}
}
?>
</table>
<table class="content"><tr class="<?= $modgroup[$self] ?>2"><td>
<?= $row ?> <?= $vallbl ?>
</td></tr></table>

<h2><?= $stalbl ?> <?= $sumlbl ?></h2>
<table class="content">
	<tr class="<?= $modgroup[$self] ?>2">
		<th colspan="2"><img src="img/16/find.png"><br><?= $stalbl ?></th>
		<th class="l"><img src="img/16/form.png"><br><?= $qtylbl ?></th>
<?php
$query	= GenQuery('inventory','g','state');
$res	= DbQuery($query,$link);
if($res){
	$row = 0;
	while( ($item = DbFetchRow($res)) ){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		$stbar = Bar($item[1],0,'mi');
		echo "	<tr class=\"$bg\">\n";
		echo "		<td class=\"$bi ctr s\">".Staimg($item[0])."</td>\n";
		echo "		<td><a href=\"?lst=st&val=$item[0]\">". $stco[$item[0]] ."</a></td>\n";
		echo "		<td>$stbar $item[1]</td>\n";
		echo "	</tr>\n";
	}
}
?>
</table>
<table class="content"><tr class="<?= $modgroup[$self] ?>2"><td>
<?= $row ?> <?= $vallbl ?>
</td></tr></table>

</td></tr></table>

<?php
include_once ("inc/footer.php");
?>
