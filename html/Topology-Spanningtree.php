<?
# Program: Topology-Spanningtree.php
# Programmer: Remo Rickli

error_reporting(1);
snmp_set_quick_print(1);
snmp_set_oid_numeric_print(1);
snmp_set_valueretrieval(SNMP_VALUE_LIBRARY);

$printable = 1;

include_once ("inc/header.php");
include_once ("inc/libdev.php");
include_once ("inc/libsnmp.php");

$_GET = sanitize($_GET);
$dev = isset($_GET['dev']) ? $_GET['dev'] : "";
$shg = isset($_GET['shg']) ? "checked" : "";
$vln = isset($_GET['vln']) ? $_GET['vln'] : "";
?>
<h1>Spanningtree Tool</h1>
<form method="get" action="<?=$self?>.php" name="stree">
<table class="content"><tr class="<?=$modgroup[$self]?>1">
<th width="50"><a href="<?=$self?>.php"><img src="img/32/<?=$selfi?>.png"></a></th>
<th>
Device
<select size="1" name="dev" onchange="document.stree.vln.value=''">
<option value=""><?=$sellbl?> ->
<?
$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
$query	= GenQuery('devices','s','device,devip,services,snmpversion,readcomm,location,contact,cliport,icon','device','',array('services & 2','snmpversion'),array('=','!='),array('2','0'),array('AND') );
$res	= @DbQuery($query,$link);
if($res){
	while( ($d = @DbFetchRow($res)) ){
		echo "<option value=\"$d[0]\" ";
		if($dev == $d[0]){
			echo "selected";
			$ud = rawurlencode($d[0]);
			$ip	= long2ip($d[1]);
			$sv	= Syssrv($d[2]);
			$rv	= $d[3] & 3;
			$rc	= $d[4];
			$loc	= $d[5];
			$con	= $d[6];
			$cli	= $d[7];
			$img	= $d[8];
		}
		echo " >$d[0]\n";
	}
	@DbFreeResult($res);
}else{
	print @DbError($link);
}
echo "</select>";
if ($dev) {
	$query	= GenQuery('vlans','s','*','','',array('device'),array('='),array($dev) );
	$res	= @DbQuery($query,$link);
	$nvln	= @DbNumRows($res);

	if($res and $nvln){
?>
 Vlan
<select size="1" name="vln">
<option value="">---
<?

		while( ($v = @DbFetchRow($res)) ){
			echo "<OPTION VALUE=\"$v[1]\" ";
			if($vln == $v[1]){echo "selected";}
			echo " >$v[1] $v[2]\n";
		}
		@DbFreeResult($res);
		echo "</select>";
	}
}
?>
</th><th><input type="checkbox" name="shg" <?=$shg?>> <?=$gralbl?></th>
<th width="80">
<input type="submit" value="<?=$sholbl?>">
</th>
</tr></table></form>
<?
if ($dev) {
	$query	= GenQuery('interfaces','s','ifidx,ifname,iftype,speed,alias,comment,ifdesc,ifstat','','',array('device'),array('='),array($dev) );
	$res	= @DbQuery($query,$link);
	while( ($i = @DbFetchRow($res)) ){
		$ifn[$i[0]] = $i[1];
		$ift[$i[0]] = $i[2];
		$ifs[$i[0]] = $i[3];
		$ifa[$i[0]] = $i[7];
		if($i[5]){
			$uneb = urlencode( preg_replace('/.+DP:(.+),.+/','$1',$i[5]) );
			$neb  = ($uneb)?"<a href=\"Topology-Spanningtree.php?dev=$uneb\"><img src=\"img/16/traf.png\"></a>":"";
			$ifi[$i[0]] = "$i[6] - <i>$i[4]</i> - $i[5] $neb";
		}else{
			$ifi[$i[0]] = "$i[6] - <i>$i[4]</i>";
		}
	}
	@DbFreeResult($res);
if('0.0.0.0' == $ip){
	echo "<h4>$nonlbl IP!</h4>";
	die;
}

?>

<table class="full fixed"><tr><td class="helper">

<h2>Device <?=$sumlbl?></h2>
<table class="content">
<tr><th class="imga" width="80">
<a href="Devices-Status.php?dev=<?=$ud?>"><img src="img/dev/<?=$img?>.png" title="<?=$stalbl?>"></a>
<br><?=$dev?></th><td class="txta"><?=(Devcli($ip,$cli))?></td></tr>
<tr><th class="<?=$modgroup[$self]?>2"><?=$srvlbl?></th><td class="txtb"><?=($sv)?$sv:"&nbsp;"?></td></tr>
<tr><th class="<?=$modgroup[$self]?>2"><?=$loclbl?></th><td class="txta"><?=$loc?></td></tr>
<tr><th class="<?=$modgroup[$self]?>2"><?=$conlbl?></th><td class="txtb"><?=$con?></td></tr>
<tr><th class="<?=$modgroup[$self]?>2">SNMP</th><td class="txta">v<?=$rv?> <?=$rc?></td></tr>
</table>

</td><td class="helper">

<h2>Spanningtree <?=$sumlbl?><?=($vln)?"for vlan $vln":""?></h2>
<table class="content"><tr>
<th class="<?=$modgroup[$self]?>2">Bridge <?=$adrlbl?></th><td  class="txta">
<?
	$braddr	= str_replace('"','', Get($ip, $rv, $rc, "1.3.6.1.2.1.17.1.1.0") );
	if ($braddr){
		echo "$braddr</td></tr>\n";
	}else{
		echo "<h4>$toumsg</h4></td></tr></table></th></tr></table>\n";
		if($_SESSION['vol']){echo "<embed src=\"inc/enter2.mp3\" volume=\"$_SESSION[vol]\" hidden=\"true>\"\n";}
		include_once ("inc/footer.php");
		die;
	}
?>
<tr><th class="<?=$modgroup[$self]?>2">STP Priority</th><td class="txtb">
<?
	if($vln){$rc = "$rc@$vln";}
	$stppri	= str_replace('"','', Get($ip, $rv, $rc, "1.3.6.1.2.1.17.2.2.0") );
	if($stppri != 'No Such Instance currently exists at this OID'){
		echo "$stppri</td></tr>\n";
	}else{
		echo "$toumsg</td></tr></table></th></tr></table>\n";
		include_once ("inc/footer.php");
		die;
	}
	$laschg	= str_replace('"','', Get($ip, $rv, $rc, "1.3.6.1.2.1.17.2.3.0") );
	sscanf($laschg, "%d:%d:%0d:%0d.%d",$tcd,$tch,$tcm,$tcs,$ticks);
	$tcstr  = sprintf("%d D %d:%02d:%02d",$tcd,$tch,$tcm,$tcs);
	$numchg	= str_replace('"','', Get($ip, $rv, $rc, "1.3.6.1.2.1.17.2.4.0") );

	$droot	= str_replace('"','', Get($ip, $rv, $rc, "1.3.6.1.2.1.17.2.5.0") );
	$rport	= str_replace('"','', Get($ip, $rv, $rc, "1.3.6.1.2.1.17.2.7.0") );

	$rootif = substr(str_replace(' ','', $droot),4); # TODO lookup root, check am I root?
?>
<tr><th class="<?=$modgroup[$self]?>2">Topology <?=$chglbl?></th><td class="txta"><?=$numchg?></td></tr>
<tr><th class="<?=$modgroup[$self]?>2"><?=$chglbl?> <?=$timlbl?></th><td class="txtb"><?=$tcstr?></td></tr>
<tr><th class="<?=$modgroup[$self]?>2">Designated Root</th><td class="txta"><?=$droot?>
<a href="Devices-Interfaces.php?ina=ifmac&opa=%3D&sta=<?=$rootif?>"><img src="img/16/port.png" title="IF <?=$lstlbl?>"></a></td></tr>
</table>

</td></tr></table>

<h2>Interfaces <?=$lstlbl?></h2>
<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th colspan="2" ><img src="img/16/port.png"><br>IF</th>
<th><img src="img/16/find.png"><br><?=$deslbl?></th>
<? if($shg){?><th><img src="img/16/grph.png"><br>IF <?=$gralbl?></th><?}?>
<th colspan="2"><img src="img/16/swit.png"><br>STP <?=$stalbl?></th>
<th><img src="img/16/dcal.png"><br>Cost</th>
<?
	if( !is_array($ifn) ){
		echo "</table>\n";
		echo "$lstlbl $emplbl";
		echo "<div align=center>$query</dev>";
		include_once ("inc/footer.php");
		die;
	}
	foreach( Walk($ip,$rv,$rc,"1.3.6.1.2.1.17.1.4.1.2") as $ix => $val){
		$pidx[substr(strrchr($ix, "."), 1 )] = $val;
	}
	foreach( Walk($ip,$rv,$rc,"1.3.6.1.2.1.17.2.15.1.3") as $ix => $val){
		$pstate[substr(strrchr($ix, "."), 1 )] = $val;
	}
	foreach( Walk($ip,$rv,$rc,"1.3.6.1.2.1.17.2.15.1.4") as $ix => $val){
		$stpen[substr(strrchr($ix, "."), 1 )] = $val;
	}
	foreach( Walk($ip,$rv,$rc,"1.3.6.1.2.1.17.2.15.1.5") as $ix => $val){
		$pcost[substr(strrchr($ix, "."), 1 )] = $val;
	}
	asort($pidx);

	$row = 0;
	foreach($pidx as $po => $ix){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		$rpimg = "";
		if($rport == $po){$rpimg = "<img src=\"img/16/home.png\" title=Rootport>";}
		if($pstate[$po] == 1 or $pstate[$po] == "disabled"){$pst = "<img src=\"img/16/bcls.png\" title=\"disabled\">";}
		elseif($pstate[$po] == 2 or $pstate[$po] == "blocking"){$pst = "<img src=\"img/16/bstp.png\" title=\"blocking\">";}
		elseif($pstate[$po] == 3 or $pstate[$po] == "listening"){$pst = "<img src=\"img/16/bup.png\" title=\"listening\">";}
		elseif($pstate[$po] == 4 or $pstate[$po] == "learning"){$pst = "<img src=\"img/16/brld.png\" title=\"learning\">";}
		elseif($pstate[$po] == 5 or $pstate[$po] == "forwarding"){$pst = "<img src=\"img/16/brgt.png\" title=\"forwarding\">";}
		else{$pst = "<img src=\"img/16/bcls.png\" title=\"broken\">";}

		if($stpen[$po] == 1 or $stpen[$po] == "enabled"){$sten = "<img src=\"img/16/bchk.png\" title=\"enabled\">";}
		else{$sten = "<img src=\"img/16/bdis.png\" title=\"disabled\">";}
		$ud = urlencode($dev);
		$ui = urlencode($ifn[$ix]);
		list($ifimg,$iftit) = Iftype($ift[$ix]);
		list($ifbg,$ifst)   = Ifdbstat($ifa[$ix]);
		echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\">\n";
		echo "<th class=\"$ifbg\"><img src=\"img/$ifimg\" title=\"$iftit $ifst\"></th><td>\n";
		if($ifbg == "good" and !isset($_GET['print'])){
			echo "<img src=\"img/16/grph.png\" align=\"right\" title=\"$rltlbl $trflbl\" onclick=\"window.open('inc/rt-popup.php?d=$debug&ip=$ip&v=$rv&c=$rc&i=$ix&t=$ud&in=$ui','$ip-$ix','scrollbars=0,menubar=0,resizable=1,width=600,height=400')\">";
		}
		echo "<b>$ifn[$ix]</b></td>\n";

		echo "<td>$ifi[$ix]</td>\n";
		if($shg){
			if($ud and $ui){
				$gsiz = ($_SESSION['gsiz'] == 4)?2:1;
				echo "<td nowrap align=\"center\">\n";
				echo "<a href=\"Devices-Graph.php?dv=$ud&if%5B%5D=$ui\"><img src=\"inc/drawrrd.php?dv=$ud&if%5B%5D=$ui&s=$gsiz&t=trf&o=$ifs[$ix]\" title=\"$trflbl\">\n";
				echo "<img src=\"inc/drawrrd.php?dv=$ud&if%5B%5D=$ui&s=$gsiz&t=err&o=1\" title=\"$errlbl\">";
				echo "<img src=inc/drawrrd.php?dv=$ud&if%5B%5D=$ui&s=$gsiz&t=dsc title=\"Discards\">\n";
				echo "<img src=inc/drawrrd.php?dv=$ud&if%5B%5D=$ui&s=$gsiz&t=brc title=\"Broadcasts\"></a>\n";
			}else{
				echo "<td></td>";
			}
		}
		echo "<th>$pst $rpimg</th><th>$sten</th></td><td align=center>$pcost[$po]</td>\n";
		echo "</tr>\n";
	}
?>
</table>
<table class="content">
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Interfaces</td></tr>
</table>
<?
}

include_once ("inc/footer.php");
?>
