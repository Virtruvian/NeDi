<?php
# Program: Nodes-Stolen.php
# Programmer: Remo Rickli

$printable = 1;
$exportxls = 0;

include_once ("inc/header.php");
include_once ("inc/libnod.php");

$_GET = sanitize($_GET);
$na = isset($_GET['na']) ? $_GET['na'] : "-";
$ip = isset($_GET['ip']) ? $_GET['ip'] : "";
$stl = isset($_GET['stl']) ? strtolower(preg_replace("/[^0-9a-f]/i", "",$_GET['stl'])) : "";
$dev = isset($_GET['dev']) ? $_GET['dev'] : "";
$ifn = isset($_GET['ifn']) ? $_GET['ifn'] : "";
$ord = isset($_GET['ord']) ? $_GET['ord'] : "";
$del = isset($_GET['del']) ? $_GET['del'] : "";

?>
<h1>Stolen Nodes</h1>

<?php
$link	= DbConnect($dbhost,$dbuser,$dbpass,$dbname);

if ($stl){
	$query	= GenQuery('stolen','i','','','',array('name','stlip','mac','device','ifname','usrname','time'),'',array($na,ip2long($ip),$stl,$dev,$ifn,$_SESSION['user'],time()) );
	if( !DbQuery($query,$link) ){echo "<h4 align=center>".DbError($link)."</h4>";}else{echo "<h5>$stl $updlbl OK</h5>";}
}elseif ($del){
	$query	= GenQuery('stolen','d','','','',array('mac'),array('='),array($del) );
	if( !DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$dellbl $del ok</h5>";}
}

if( !isset($_GET['print']) ) { ?>

<table class="content"><tr class="bgmain">
<th width="50"><a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png" title="<?= $self ?>"></a>
</th>
<th>
<form method="get" action="<?= $self ?>.php">
Sort
<select name="ord" size="1" onChange="submit();">
<option value="name" <?= ($ord == "name")?" selected":"" ?> ><?= $namlbl ?>
<option value="stlip" <?= ($ord == "stlip")?" selected":"" ?> >IP <?= $adrlbl ?>
<option value="mac" <?= ($ord == "mac")?" selected":"" ?> >MAC <?= $adrlbl ?>
<option value="device" <?= ($ord == "device")?" selected":"" ?> >Device
<option value="time" <?= ($ord == "updated")?" selected":"" ?> ><?= $timlbl ?>
</select>
</form>
</th>
<th align="right">
<form method="get" action="<?= $self ?>.php">
<?= $namlbl ?> <input type="text" name="na" value="<?= $na ?>" class="m">
IP <input type="text" name="stlip" value="<?= $ip ?>" class="m">
MAC <input type="text" name="stl" value="<?= $stl ?>" class="m">
<p>
Device <input type="text" name="dev" value="<?= $dev ?>" class="m">
IF <input type="text" name="ifn" value="<?= $ifn ?>" class="m">

</th>
<th width="80"><input type="submit" class="button" value="<?= $addlbl ?>">
</form>
</th>
</tr></table><p>
<?php
}

$query	= GenQuery('stolen','s','stolen.*',$ord,'',array(),array(),array(),array(),'LEFT JOIN devices USING (device)');
$res	= DbQuery($query,$link);
if($res){
?>
<h2>Stolen Nodes <?= $lstlbl ?></h2>
<table class="content"><tr class="bgsub">
<th colspan="3"><img src="img/16/node.png"><br>Node <?= $inflbl ?></th>
<th colspan="2"><img src="img/16/dev.png"><br>Device - IF</th>
<th><img src="img/16/eyes.png"><br><?= $laslbl ?> / <?= $timlbl ?></th>
<th><img src="img/16/user.png"><br><?= $actlbl ?> / <?= $usrlbl ?></th>

<?php
	$row = 0;
	while( ($s = DbFetchRow($res)) ){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		$nquery	= GenQuery('nodes','s','*','','',array('mac'),array('='),array($s[2]));
		$nres	= DbQuery($nquery,$link);
		$nnod	= DbNumRows($nres);
		if ($nnod == 1) {
			$n	= DbFetchRow($nres);
			DbFreeResult($nres);
		}else{
			$n = array($s[0],$s[1],$s[2],'-',0,0,'Not in nodes','-');
		}
		$img	= Nimg("$n[0];$n[1]");
		$ls	= date($_SESSION['timf'],$n[3]);
		list($fc,$lc) = Agecol($n[2],$n[3],$row % 2);
		$na	= preg_replace("/^(.*?)\.(.*)/","$1", $s[0]);
		$ip	= long2ip($s[1]);
		$sup	= date($_SESSION['timf'],$s[6]);
		$simg	= "";
		list($s1c,$s2c) = Agecol($s[6],$s[6],$row % 2);
		if ($n[3] > $s[6]){$bi = "alrm";}

		echo "<tr class=\"$bg\">";
		echo "<th class=\"$bi\" width=120 rowspan=2><a href=\"Nodes-Status.php?mac=$n[0]\"><img src=\"img/oui/$img.png\" title=\"Nodes-Status\" vspace=8></a><br>$s[2]\n";
		echo "<td>-</td><td>-</td><td>$n[4]</td><td>$n[5]</td><td bgcolor=#$lc>$ls</td>\n";
		echo "<th><a href=\"?del=$s[2]\"><img src=\"img/16/bcnl.png\" onclick=\"return confirm('$dellbl $s[2]?')\"></a></th>\n";
		echo "</tr><tr class=\"$bg\"><td>$na</td><td>$ip</td><td>$s[3]</td><td>$s[4]</td><td bgcolor=#$s1c>$sup</td><td align=center>$s[5]</td>\n";
		echo "";
		echo "</tr>\n";
	}
	DbFreeResult($res);
}else{
	print DbError($link);
}
	?>
</table>
<table class="content">
<tr class="bgsub"><td><?= $row ?> Nodes</td></tr>
</table>
	<?php

include_once ("inc/footer.php");
?>
