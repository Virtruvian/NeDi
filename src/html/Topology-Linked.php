<?php
# Program: Topology-Linked.php
# Programmer: Remo Rickli

$printable = 1;
$exportxls = 0;

include_once ("inc/header.php");

$_GET = sanitize($_GET);
$dv = isset($_GET['dv']) ? $_GET['dv'] : "";
$if = isset($_GET['if']) ? $_GET['if'] : "";
$nb = isset($_GET['nb']) ? $_GET['nb'] : "";
$ni = isset($_GET['ni']) ? $_GET['ni'] : "";
$add = isset($_GET['add']) ? $_GET['add'] : "";
$del = isset($_GET['del']) ? $_GET['del'] : "";
$ddu = isset($_GET['ddu']) ? $_GET['ddu'] : "";
$dvl = isset($_GET['dvl']) ? $_GET['dvl'] : "";
$ndu = isset($_GET['ndu']) ? $_GET['ndu'] : "";
$nvl = isset($_GET['nvl']) ? $_GET['nvl'] : "";
$dbw = isset($_GET['dbw']) ? $_GET['dbw'] : "";
$nbw = isset($_GET['nbw']) ? $_GET['nbw'] : "";
$typ = isset($_GET['typ']) ? $_GET['typ'] : "";
$lde = isset($_GET['lde']) ? $_GET['lde'] : "Added $now by $_SESSION[user]";

echo "<h1>$cnclbl $edilbl</h1>\n";

$link	= DbConnect($dbhost,$dbuser,$dbpass,$dbname);
if ( $add and $dv and $if and $nb and $ni){
	$query	= GenQuery('links','i','','','',array('device','ifname','neighbor','nbrifname','bandwidth','linktype','linkdesc','nbrduplex','nbrvlanid','time'),'',array($dv,$if,$nb,$ni,$dbw,'STAT',$lde,$ndu,$nvl,time() ) );
	if( !DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>Link $dv - $nb $updlbl OK</h5>";}
	$query	= GenQuery('links','i','','','',array('device','ifname','neighbor','nbrifname','bandwidth','linktype','linkdesc','nbrduplex','nbrvlanid','time'),'',array($nb,$ni,$dv,$if,$nbw,'STAT',$lde,$ddu,$dvl,time() ) );
	if( !DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>Link $nb - $dv $updlbl OK</h5>";}
}elseif($del){
	$query	= GenQuery('links','d','','','',array('id'),array('='),array($del) );
	if( !DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>Link $_GET[del] $dellbl OK</h5>";}
}
?>

<?php  if( !isset($_GET['print']) ) { ?>
<form method="get" action="<?= $self ?>.php" name="li">
<table class="content" ><tr class="bgmain">
<th width="50"><a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png" title="<?= $self ?>"></a>
</th>
<th valign="top">
<select name="dv" onchange="this.form.submit();">
<option value="">Device ->
<?php
$devs   = array();
$dquery = GenQuery('devices','s','*','device','',array('devopts'),array('~'),array('^...I') );
$res    = DbQuery($dquery,$link);
if($res){
	while( ($d = DbFetchRow($res)) ){
		echo "<option value=\"$d[0]\" ";
		if($dv == $d[0]){echo "selected";}
		echo " >$d[0]\n";
		$devs[] = $d[0];
	}
	DbFreeResult($res);
}else{
	print DbError($link);
}
?>
</select>
<?php
if ($dv) {
	$query	= GenQuery('interfaces','s','ifname,alias,ifstat,speed,duplex,pvid,comment','ifname','',array('device'),array('='),array($dv) );
	$res	= DbQuery($query,$link);
	if($res){
?>
<select name="if" onchange="this.form.submit();">
<option value="">IF <?= $namlbl ?> ->
<?php
		while( ($i = DbFetchRow($res)) ){
			echo "<option value=\"$i[0]\" ".(($i[2] == 3)?'class="grn"':'');
			if($if == $i[0]){
				echo "selected";
				$dbw=$i[3];
				$ddu=$i[4];
				$dvl=$i[5];
			}
			echo " >$i[0] " . substr($i[1],0,$_SESSION['lsiz']).' '.substr($i[6],0,$_SESSION['lsiz'])."\n";
		}
		DbFreeResult($res);
		echo "</select>";
	}
}
if ($if) {
?>
<hr>
<img src="img/dpx.png" title="Duplex"><input type="text" name="ddu" class="xs" value="<?= $ddu ?>">
<img src="img/16/vlan.png" title="PVID"><input type="number" name="dvl" class="xs" value="<?= $dvl ?>">
<select size="1" name="dbs" onchange="document.li.dbw.value=document.li.dbs.options[document.li.dbs.selectedIndex].value">
<option value=""><?= $bwdlbl ?> ->
<option value="1544000">T1
<option value="2048000">E1
<option value="10000000">10M
<option value="100000000">100M
<option value="1000000000">1G
<option value="10000000000">10G

</select>
<input type="number" min="0" step="1000" name="dbw" class="s" value="<?= $dbw ?>">
</th>
<?php
}
?>
<th valign="top">
<select name="nb" onchange="this.form.submit();">
<option value=""><?= $neblbl ?> ->
<?php

foreach ($devs as $ndv){
	echo "<option value=\"$ndv\" ";
	if($nb == $ndv){echo "selected";}
	echo " >$ndv\n";
}

?>
</select>
<?php
if ($nb) {
	$query	= GenQuery('interfaces','s','ifname,alias,ifstat,speed,duplex,pvid,comment','ifname','',array('device'),array('='),array($nb) );
	$res	= DbQuery($query,$link);
	if($res){
?>
<select name="ni" onchange="this.form.submit();">
<option>IF <?= $namlbl ?> ->
<?php
		while( ($i = DbFetchRow($res)) ){
			echo "<option value=\"$i[0]\" ".(($i[2] == 3)?'class="grn"':'');
			if($if == $i[0]){
				echo "selected";
				$dbw=$i[3];
				$ddu=$i[4];
				$dvl=$i[5];
			}
			echo " >$i[0] " . substr($i[1],0,$_SESSION['lsiz']).' '.substr($i[6],0,$_SESSION['lsiz'])."\n";
		}
		DbFreeResult($res);
		echo "</select>";
	}
}
if ($ni) {
?>
<hr>
<img src="img/dpx.png" title="Duplex"><input type="text" name="ndu" class="xs" value="<?= $ndu ?>">
<img src="img/16/vlan.png" title="PVID"><input type="number" name="nvl" class="xs" value="<?= $nvl ?>">
<select size="1" name="nbs" onchange="document.li.nbw.value=document.li.nbs.options[document.li.nbs.selectedIndex].value">
<option value=""><?= $bwdlbl ?> ->
<option value="1544000">T1
<option value="2048000">E1
<option value="10000000">10M
<option value="100000000">100M
<option value="10000000000">10G
</select>
<input type="number" min="0" step="1000" name="nbw" class="s" value="<?= $nbw ?>">
<img src="img/16/say.png" title="<?= $cmtlbl ?>"><input type="text" name="lde" class="l" value="<?= $lde ?>">
<?php
}
?>
</th>
<th width="80">
<select size="1" name="typ" onchange="this.form.submit();">
<option value=""><?= $sholbl ?> ->
<option value="LLDP">LLDP
<option value="CDP">CDP
<option value="FDP">FDP
<option value="NDP">NDP
<option value="STAT">Static
<option value="FWD">BriFwd
<option value="MAC">IfMAC
<option value="IFI">IfIP
<option value="ISO"><?= $isolbl ?>
</select>
<p>
<input type="submit" class="button" name="add" value="<?= $addlbl ?>">
</th>
</tr></table></form><p>
<?php
}
if ($dv or $typ){
?>
<h2><?= ($typ)?$typ:$dv ?> - Links</h2>
<table class="content" ><tr class="bgsub">
<th><img src="img/16/dev.png"><br>Device</th>
<th><img src="img/16/port.png"><br>Interface</th>
<th><img src="img/16/abc.png" title="D=Discovery Protocol,O=Oui,V=VoIP,S=static"><br><?= $typlbl ?></th>
<th><img src="img/16/tap.png"><br><?= $bwdlbl ?></th>
<th><img src="img/16/dev.png"><br><?= $neblbl ?></th>
<th><img src="img/16/port.png"><br>Interface</th>
<th width="33%"><img src="img/16/find.png"><br><?= $deslbl ?></th>
<th><img src="img/16/clock.png"><br><?= $timlbl ?></th>
<th width="80"><img src="img/16/cog.png"><br><?= $cmdlbl ?></th>
</tr>
<?php
	if ($typ == "ISO"){
		$query	= GenQuery('links','s','links.*','ifname','',array('devices.device'),array('COL IS'),array('NULL'),array(),'LEFT JOIN devices USING (device)');
	}elseif ($typ){
		$query	= GenQuery('links','s','*','ifname','',array('linktype'),array('~'),array("^$typ"));
	}else{
		$query	= GenQuery('links','s','*','ifname','',array('device'),array('='),array($dv));
	}
	$res	= DbQuery($query,$link);
	if($res){
		$nli = 0;
		$row = 0;
		while( ($l = DbFetchRow($res)) ){
			$ud = rawurlencode($l[1]);
			$un = rawurlencode($l[3]);
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			TblRow($bg);
			list($tc,$tc) = Agecol($l[10],$l[10],$row % 2);
			TblCell($l[1],"?dv=$ud",'nw',"<a href=\"Devices-Status.php?dev=$ud\"><img src=\"img/16/sys.png\"></a>");
			echo "<td>$l[2]</td>\n";
			echo "<th>$l[6]</th>\n";
			echo "<td align=right>" . DecFix($l[5]) . "</td>\n";
			TblCell($l[3],"?dv=$un",'nw',"<a href=\"Devices-Status.php?dev=$un\"><img src=\"img/16/sys.png\"></a>");
			echo "<td>$l[4] (Vl$l[9] $l[8])</td><td>$l[7]</td><td bgcolor=\"$tc\" width=\"100\" nowrap>".date($_SESSION['timf'],$l[10])."</td>\n";
			echo "<th><a href=\"?del=$l[0]&dv=$ud\"><img src=\"img/16/bcnl.png\" onclick=\"return confirm('Link $l[0] $dellbl?');\" title=\"$l[0] $dellbl\"></a></th></tr>\n";
			$nli++;
		}
		DbFreeResult($res);
	}else{
		print DbError($link);
	}
	?>
</table>
<table class="content" >
<tr class="bgsub"><td><?= $row ?> Links</td></tr>
</table>
	<?php
}
include_once ("inc/footer.php");
?>
