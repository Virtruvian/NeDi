<?
# Program: Topology-Linked.php
# Programmer: Remo Rickli

$printable = 1;

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

$lde = "Added $now by $_SESSION[user]";
$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
if ( $add and $dv and $if and $nb and $ni){
	$query	= GenQuery('links','i','','','',array('device','ifname','neighbor','nbrifname','bandwidth','linktype','linkdesc','nbrduplex','nbrvlanid'),'',array($dv,$if,$nb,$ni,$dbw,'STAT',$lde,$ndu,$nvl) );
	if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>Link $dv - $nb $updlbl OK</h5>";}
	$query	= GenQuery('links','i','','','',array('device','ifname','neighbor','nbrifname','bandwidth','linktype','linkdesc','nbrduplex','nbrvlanid'),'',array($nb,$ni,$dv,$if,$nbw,'STAT',$lde,$ddu,$dvl) );
	if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>Link $nb - $dv $updlbl OK</h5>";}
}elseif($del){
	$query	= GenQuery('links','d','','','',array('id'),array('='),array($del) );
	if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>Link $_GET[del] $dellbl OK</h5>";}
}
?>
<h1>Link Editor</h1>

<?if( !isset($_GET['print']) ){?>

<form method="get" action="<?=$self?>.php" name="li">
<table class="content" ><tr class="<?=$modgroup[$self]?>1">
<th width="50"><a href="<?=$self?>.php"><img src="img/32/<?=$selfi?>.png"></a></th>
<th valign="top"><h3>Device</h3>
<select size="6" name="dv" onchange="this.form.submit();">
<?
$dquery	= GenQuery('devices','s','*','device','',array('snmpversion'),array('!='),array('0') );
$res	= @DbQuery($dquery,$link);
if($res){
	while( ($d = @DbFetchRow($res)) ){
		echo "<option value=\"$d[0]\" ";
		if($dv == $d[0]){echo "selected";}
		echo " >$d[0]\n";
	}
	@DbFreeResult($res);
}else{
	print @DbError($link);
}
?>
</select>
<?
if ($dv) {
	$query	= GenQuery('interfaces','s','*','ifname','',array('device'),array('='),array($dv) );
	$res	= @DbQuery($query,$link);
	if($res){
?>
<select size="6" name="if" onchange="this.form.submit();">
<?
		while( ($i = @DbFetchRow($res)) ){
			echo "<OPTION VALUE=\"$i[1]\" ";
			if($if == $i[1]){
				echo "selected";
				$dbw=$i[9];
				$ddu=$i[10];
				$dvl=$i[11];
			}
			echo " >$i[1]  " . substr("$i[7] $i[20]\n",0,30);
		}
		@DbFreeResult($res);
		echo "</select>";
	}
}
if ($if) {
?>
<hr>
Duplex/Vlan
<input type="text" name="ddu" size="4" value="<?=$ddu?>">
<input type="text" name="dvl" size="4" value="<?=$dvl?>">
<select size="1" name="dbs" onchange="document.li.dbw.value=document.li.dbs.options[document.li.dbs.selectedIndex].value">
<option value=""><?=$bwdlbl?> ->
<option value="1544000">T1
<option value="2048000">E1
<option value="10000000">10M
<option value="100000000">100M
<option value="1000000000">1G
<option value="4294967295">>4G
<option value="10000000000">10G

</select>
<input type="text" name="dbw" size=12 value="<?=$dbw?>">
</th>
<?
}
?>
<th valign="top"><h3><?=$neblbl?></h3>
<select size="6" name="nb" onchange="this.form.submit();">
<?
$res	= @DbQuery($dquery,$link);
if($res){
	while( ($d = @DbFetchRow($res)) ){
		echo "<option value=\"$d[0]\" ";
		if($nb == $d[0]){echo "selected";}
		echo " >$d[0]\n";
	}
	@DbFreeResult($res);
}else{
	print @DbError($link);
}
?>
</select>
<?
if ($nb) {
	$query	= GenQuery('interfaces','s','*','ifname','',array('device'),array('='),array($nb) );
	$res	= @DbQuery($query,$link);
	if($res){
?>
<select size="6" name="ni" onchange="this.form.submit();">
<?
		while( ($i = @DbFetchRow($res)) ){
			echo "<OPTION VALUE=\"$i[1]\" ";
			if($ni == $i[1]){
				echo "selected";
				$nbw=$i[9];
				$ndu=$i[10];
				$nvl=$i[11];
			}
			echo " >$i[1]  " . substr("$i[7] $i[20]\n",0,30);
		}
		@DbFreeResult($res);
		echo "</select>";
	}
}
if ($ni) {
?>
<hr>
Duplex/Vlan
<input type="text" name="ndu" size="4" value="<?=$ndu?>">
<input type="text" name="nvl" size="4" value="<?=$nvl?>">
<select size="1" name="nbs" onchange="document.li.nbw.value=document.li.nbs.options[document.li.nbs.selectedIndex].value">
<option value=""><?=$bwdlbl?> ->
<option value="1544000">T1
<option value="2048000">E1
<option value="10000000">10M
<option value="100000000">100M
<option value="1000000000">1G
<option value="10000000000">10G
</select>
<input type="text" name="nbw" size="12" value="<?=$nbw?>">
<?
}
?>
</th>
<th width="80">
<select size="1" name="typ" onchange="this.form.submit();">
<option value=""><?=$sholbl?> ->
<option value="STAT">Static
<option value="LLDP">LLDP
<option value="CDP">CDP
<option value="FDP">FDP
<option value="NDP">NDP
<option value="ISO"><?=$isolbl?>
</select>
<p>
<input type="submit" name="add" value="<?=$addlbl?>">
</th>
</tr></table></form><p>
<?
}
if ($dv or $typ){
?>
<h2><?=($typ)?$typ:$dv?> - Links</h2>
<table class="content" ><tr class="<?=$modgroup[$self]?>2">
<th><img src="img/16/dev.png"><br>Device</th>
<th><img src="img/16/port.png"><br>Interface</th>
<th><img src="img/16/abc.png" title="D=Discovery Protocol,O=Oui,V=VoIP,S=static"><br><?=$typlbl?></th>
<th><img src="img/16/tap.png"><br><?=$bwdlbl?></th>
<th><img src="img/16/dev.png"><br><?=$neblbl?></th>
<th><img src="img/16/port.png"><br>Interface</th>
<th><img src="img/16/say.png"><br><?=$cmtlbl?></th>
<th width="80"><img src="img/16/cog.png"><br><?=$cmdlbl?></th></tr>
</tr>
<?
	if ($typ == "ISO"){
		$query	= GenQuery('links','s','links.*','ifname','',array('devices.device'),array('COL IS'),array('NULL'),'','LEFT JOIN devices USING (device)');
	}elseif ($typ){
		$query	= GenQuery('links','s','*','ifname','',array('linktype'),array('='),array($typ));
	}else{
		$query	= GenQuery('links','s','*','ifname','',array('device'),array('='),array($dv));
	}
	$res	= @DbQuery($query,$link);
	if($res){
		$nli = 0;
		$row = 0;
		while( ($l = @DbFetchRow($res)) ){
			$ud = rawurlencode($l[1]);
			$un = rawurlencode($l[3]);
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\">\n";
			echo "<td><a href=\"Devices-Status.php?dev=$ud\"><img src=\"img/16/sys.png\"></a>\n";
			echo " <a href=?dv=$ud>$l[1]</a></td><td>$l[2]</td>\n";
			echo "<th>$l[6]</th>\n";
			echo "<td align=right>" . DecFix($l[5]) . "</td>\n";
			echo "<td><a href=\"Devices-Status.php?dev=$un\"><img src=\"img/16/sys.png\"></a>\n";
			echo " <a href=?dv=$un>$l[3]</a></td><td>$l[4] (Vlan$l[9] $l[8])</td><td>$l[7]</td>\n";
			echo "<th><a href=?del=$l[0]&dv=$ud><img src=\"img/16/bcnl.png\" onclick=\"return confirm('Link $l[0] $dellbl?');\" title=\"$l[0] $dellbl\"></a></th></tr>\n";
			$nli++;
		}
		@DbFreeResult($res);
	}else{
		print @DbError($link);
	}
	?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Links</td></tr>
</table>
	<?
}
include_once ("inc/footer.php");
?>
