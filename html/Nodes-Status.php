<?
# Program: Nodes-Status.php
# Programmer: Remo Rickli

$printable = 1;

include_once ("inc/header.php");
include_once ("inc/libnod.php");
include_once ("inc/libgraph.php");								# Just so we know $rrdcmd
include_once ("inc/libmon.php");
include_once ("inc/libdev.php");

$_GET = sanitize($_GET);
$mac = isset($_GET['mac']) ? $_GET['mac'] : "";
$vid = isset($_GET['vid']) ? $_GET['vid'] : "";
$wol = isset($_GET['wol']) ? $_GET['wol'] : "";
$del = isset($_GET['del']) ? $_GET['del'] : "";
$trk = isset($_GET['trk']) ? $_GET['trk'] : "";
$mon = isset($_GET['mon']) ? $_GET['mon'] : "";
?>
<h1>Node <?=$stalbl?></h1>

<?if( !isset($_GET['print']) ){?>

<form method="get" action="<?=$self?>.php">
<table class="content"><tr class="<?=$modgroup[$self]?>1">
<th width="50"><a href="<?=$self?>.php"><img src="img/32/<?=$selfi?>.png"></a></th>
<th>
MAC <?=$adrlbl?> <input type="text" name="mac" value="<?=$mac?>" size="15">
<?if($useivl){?>
Vlan <input type="text" name="vid" value="<?=$vid?>" size="4">
<?}?>
</th>
<th width="80"><input type="submit" value="<?=$sholbl?>"></th>
</tr></table></form><p>
<?
}
$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
if ($trk){
	$mac = $trk;
	if($isadmin){
		$query	= GenQuery('nodes','u',"mac=\"$mac\"",'','',array('ipchanges'),'',array('0') );
		if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$mac ipchanges $updlbl OK</h5>";}
		$query	= GenQuery('nodes','u',"mac=\"$mac\"",'','',array('ifchanges'),'',array('0') );
		if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$mac ifchanges $updlbl OK</h5>";}
		$query	= GenQuery('iptrack','d','','','',array('mac'),array('='),array($mac) );
		if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$mac iptrack $dellbl OK</h5>";}
		$query	= GenQuery('iftrack','d','','','',array('mac'),array('='),array($mac) );
		if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$mac iptrack $dellbl OK</h5>";}
	}else{
		echo $nokmsg;
	}
}

if ($mac){
	if ($vid){$cop = "AND";}else{$cop = "";}
	$query	= GenQuery('nodes','s','nodes.*','','',array('mac','vlanid'),array('=','='),array($mac,$vid),array($cop),'LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	$nnod	= @DbNumRows($res);
	if ($nnod != 1) {
		echo "<h4>$mac = $nonlbl</h4>";
		die;
	}else{
		$n		= @DbFetchRow($res);
		@DbFreeResult($res);

		$name		= preg_replace("/^(.*?)\.(.*)/","$1", $n[0]);
		$ip		= long2ip($n[1]);
		$img		= Nimg($n[3]);
		$fs		= date($datfmt,$n[4]);
		$ls		= date($datfmt,$n[5]);
		list($fc,$lc)	= Agecol($n[4],$n[5],0);
		$wasup		= ($n[5] > time() - $rrdstep + 60)?1:0;
		$ud 		= urlencode($n[6]);
		$ui 		= urlencode($n[7]);
		$au		= date($datfmt,$n[12]);
		list($a1c,$a2c) = Agecol($n[12],$n[12],0);
		$fu		= date($datfmt,$n[20]);
		list($f1c,$f2c) = Agecol($n[20],$n[20],0);

		if($n[7]){
			$query	= GenQuery('interfaces','s','*','','',array('device','ifname'),array('=','='),array($n[6],$n[7]),array('AND') );
			$res	= @DbQuery($query,$link);
			$nif	= @DbNumRows($res);
			if ($nif != 1) {
				echo "<h4>$nonlbl</h4>";
			}else{
				$if	= @DbFetchRow($res);
				list($ifbg,$ifst)   = Ifdbstat($if[8]);
				list($ifimg,$iftyp) = Iftype($if[4]);
			}
			@DbFreeResult($res);
			$iu		= date($datfmt,$n[10]);
			list($i1c,$i2c) = Agecol($n[10],$n[10],1);
		}
		$vl[2] = "-";
		if($n[8]){
			$query	= GenQuery('vlans','s','*','','',array('device','vlanid'),array('=','='),array($n[6],$n[8]),array('AND') );
			$res	= @DbQuery($query,$link);
			$nvl	= @DbNumRows($res);
			if ($nvl == 1) {
				$vl	= @DbFetchRow($res);
			}
			@DbFreeResult($res);
		}
	}
?>

<table class="full fixed"><tr><td class="helper">

<h2><?=$sumlbl?></h2><p>
<table class="content"><tr>
<th class="imga" width="80"><a href="?mac=<?=$n[2]?>"><img src="img/oui/<?=$img?>.png" title="<?=$n[3]?>"></a><br><?=$name?></th>
<th class="<?=$modgroup[$self]?>2">

<div  style="float:left">
<?
if(preg_match("/dsk/",$_SESSION['group']) ){
	echo "<a href=Nodes-Stolen.php?na=$n[0]&ip=$ip&stl=$n[2]&dev=$ud&ifn=$ui><img src=\"img/16/hat.png\" title=\"Mark as stolen!\"></a>";
	if(!$wasup){echo "<a href=$_SERVER[PHP_SELF]?wol=$n[2]><img src=\"img/16/exit.png\" title=\"WOL $srvlbl\"></a>";}
}
$src = $mac.(($n[0] == "" or $n[0] == "-")?"":"|$n[0]").(($ip)?"|^$ip$":"");
?>
<a href="Monitoring-Events.php?ina=source&inb=info&opa=regexp&opb=regexp&sta=<?=$src?>&stb=<?=$src?>&cop=or"><img src="img/16/bell.png" title="<?=$msglbl?>"></a>

</div><div  style="float:right">

<?
if($isadmin){
	if($n[1]){
		if ($mon == 1){
			$mona  = ($n[0])?$n[0]:$ip;
			echo AddRecord('monitoring',"name=\"$mona\"","name,monip,class,test,device,depend","\"$mona\",\"$n[1]\",\"node\",\"ping\",\"$n[6]\",\"$n[6]\"");
		}else{
			echo "<a href=\"?mac=$mac&mon=1\" onclick=\"return confirm('Monitor $addlbl?')\"><img src=\"img/16/bino.png\" title=\"Monitor $addlbl?\"></a>";
		}
	}
	echo "<a href=\"?trk=$n[2]\"><img src=\"img/16/star.png\" onclick=\"return confirm('$dellbl IF/IP $chglbl  $n[2]?')\" title=\"$dellbl IF/IP $chglbl\"></a>";
	echo "<a href=\"?del=$n[2]\"><img src=\"img/16/bcnl.png\" onclick=\"return confirm('$dellbl $n[2] ?')\" title=\"$dellbl Node!\"></a>";
}
?>

</div>

</th></tr>
<tr><th class="<?=$modgroup[$self]?>2">MAC <?=$adrlbl?></th>	<td class="txta">
<b class="drd"><?=rtrim(chunk_split($n[2],2,"-"),"-")?></b> -
<b class="drd"><?=rtrim(chunk_split($n[2],2,":"),":")?></b> -
<b class="drd"><?=rtrim(chunk_split($n[2],4,"."),".")?></b></td></tr>
<tr><th class="<?=$modgroup[$self]?>2">NIC <?=$venlbl?></th>	<td class="txtb"><a href="http://www.google.com/search?q=<?=urlencode($n[3])?>&btnI=1" target="window"><?=$n[3]?></a></td></tr>
<tr><th class="<?=$modgroup[$self]?>2"><?=$fislbl?></th>	<td bgcolor=#<?=$fc?>><?=$fs?></td></tr>
<tr><th class="<?=$modgroup[$self]?>2"><?=$laslbl?></th>	<td bgcolor=#<?=$lc?>><?=$ls?></td></tr>
<tr><th class="<?=$modgroup[$self]?>2">IP <?=$adrlbl?></th>	<td class="txta">

<div style="float:right;margin:2px 2px">
<a href="Nodes-Toolbox.php?Dest=<?=$ip?>"><img src="img/16/dril.png" title="Toolbox"></a>
</div>

<?
if($n[1] and $wasup and $isadmin){?>
<div style="float:right;margin:2px 2px">
<form method="post" name="nedi" action="System-NeDi.php">
<input type="hidden" name="mod" value="d">
<input type="hidden" name="sed" value="a">
<input type="hidden" name="bup" value="b">
<input type="hidden" name="vrb" value="v">
<input type="hidden" name="opt" value="<?=$ip?>">
<input type="image" src="img/16/radr.png" value="Submit" title="Discover & <?=$cfglbl?> <?=$buplbl?>">
</form>
</div>

<div style="float:right;margin:2px 2px">
<form method="post" name="nedi" action="System-NeDi.php">
<input type="hidden" name="mod" value="s">
<input type="hidden" name="opt" value="<?=$ip?>">
<input type="hidden" name="vrb" value="v">
<input type="image" src="img/16/find.png" value="Submit" title="Scan Node">
</form>
</div>
<?
}
?>

<?=$ip?> (<?=($n[1])?gethostbyaddr($ip):"";?>)
</td></tr>
<tr><th class="<?=$modgroup[$self]?>2">IP <?=$updlbl?></th>	<td bgcolor=#<?=$a1c?>><?=$au?> (<?=$n[13]?> <?=$chglbl?> / <?=$n[14]?> <?=$loslbl?> / <?=$n[15]?> ARP <?=$vallbl?>)</td></tr>
<tr><th class="<?=$modgroup[$self]?>2">Device</th>		<td class="txta"><a href="Devices-Status.php?dev=<?=$ud?>"><img src="img/16/sys.png" title="<?=$n[6]?> <?=$stalbl?>"></a><b><?=$n[6]?></b></td></tr>
<tr><th class="<?=$modgroup[$self]?>2">Interface</th>		<td class="<?=($ifbg)?$ifbg:"txtb"?>"><img src="img/<?=$ifimg?>" title="<?=$iftyp?> - <?=$ifst?>"><b><?=$n[7]?></b> (<?=ZFix($if[9])?>-<?=$if[10]?>) <i><?=$if[7]?> <?=$if[20]?></i></td></tr>
<tr><th class="<?=$modgroup[$self]?>2">Vlan</th>		<td class="txta"><?=$n[8]?> <?=$vl[2]?></td></tr>
<tr><th class="<?=$modgroup[$self]?>2"><?=$laslbl?></th>	<td class="txtb"><?=$trflbl?> <?=Zfix($if[16])?>/<?=Zfix($if[18])?> <?=$errlbl?> <?=Zfix($if[17])?>/<?=Zfix($if[19])?></td></tr>
<tr><th class="<?=$modgroup[$self]?>2"><?=$totlbl?></th>	<td class="txta"><?=$trflbl?> <?=Zfix($if[12])?>/<?=Zfix($if[14])?> <?=$errlbl?> <?=Zfix($if[13])?>/<?=Zfix($if[15])?></td></tr>
<tr><th class="<?=$modgroup[$self]?>2">IF <?=$updlbl?></th>	<td bgcolor=#<?=$i1c?>><?=$iu?> - <?=$chglbl?>: <?=$n[11]?> - <?=($n[9] < 255)?"SNR ".Bar($n[9],-30,'mi')."$n[9]db":"Metric $n[9]"?></td></tr>
<tr><th class="<?=$modgroup[$self]?>2">TCP Ports</th>		<td class="txtb"><?=$n[16]?></td></tr>
<tr><th class="<?=$modgroup[$self]?>2">UDP Ports</th>		<td class="txta"><?=$n[17]?></td></tr>
<tr><th class="<?=$modgroup[$self]?>2">OS/<?=$typlbl?></th>	<td class="txtb"><?=$n[18]?> / <?=$n[19]?></td></tr>
<tr><th class="<?=$modgroup[$self]?>2">OS <?=$updlbl?></th>	<td bgcolor=#<?=$f1c?>><?=$fu?></td></tr>

</table>

</td><td class="helper">

<?
flush();
if($n[1]){
?>
<h2><?=$srvlbl?></h2><p>
<table class="content"><tr>
<th class="<?=$modgroup[$self]?>2" width="80"><img src="img/32/nwin.png"><br>Netbios</th><td class="txta"><?=(($wasup)?NbtStat($ip):"-")?></td></tr>
<tr><th class="<?=$modgroup[$self]?>2"><a href="http://<?=$ip?>" target="window"><img src="img/32/glob.png"></a><br>HTTP</th>
<td class="txtb"><?=(($wasup)?CheckTCP($ip,'80',"GET / HTTP/1.0\r\n\r\n"):"-")?></td></tr>
<tr><th class="<?=$modgroup[$self]?>2"><a href="https://<?=$ip?>" target="window"><img src="img/32/glok.png"></a><br>HTTPS</th>
<td class="txta"><?=(($wasup)?CheckTCP($ip,'443',''):"-")?></td></tr>
<tr><th class="<?=$modgroup[$self]?>2"><a href="ssh://<?=$ip?>"><img src="img/32/lokc.png"></a><br>SSH</th>
<td class="txtb"><?=(($wasup)?CheckTCP($ip,'22',''):"-")?></td></tr>
<tr><th class="<?=$modgroup[$self]?>2"><a href="telnet://<?=$ip?>"><img src="img/32/loko.png"></a><br>Telnet</th>
<td class="txta"><?=(($wasup)?CheckTCP($ip,'23','\n'):"-")?></td></tr>
</table>
<?
}else{
	echo "<h4>No IP!</h4>";
}

if($rrdcmd){ ?>

</td></tr><tr>
<td class="helper" align="center" colspan="2">
<h2><?=$n[6]?>-<?=$n[7]?> <?=$gralbl?></h2>
<a href="Devices-Graph.php?dv=<?=$ud?>&if%5B%5D=<?=$ui?>">
<img src="inc/drawrrd.php?dv=<?=$ud?>&if%5B%5D=<?=$ui?>&s=<?=$_SESSION['gsiz']?>&t=trf" title="<?=Zfix($if[16])?>/<?=Zfix($if[18])?>">
<img src="inc/drawrrd.php?dv=<?=$ud?>&if%5B%5D=<?=$ui?>&s=<?=$_SESSION['gsiz']?>&t=err" title="<?=Zfix($if[17])?>/<?=Zfix($if[19])?>">
<img src="inc/drawrrd.php?dv=<?=$ud?>&if%5B%5D=<?=$ui?>&s=<?=$_SESSION['gsiz']?>&t=dsc">
<img src="inc/drawrrd.php?dv=<?=$ud?>&if%5B%5D=<?=$ui?>&s=<?=$_SESSION['gsiz']?>&t=brc">
</a>

</td></tr>
<?}?>
<tr><td class="helper">

<h2>IP <?=$chglbl?></h2>

<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th colspan=2><img src="img/32/clock.png"><br><?=$updlbl?></th>
<th><img src="img/32/abc.png"><br><?=$namlbl?></th>
<th><img src="img/32/net.png"><br>IP <?=$adrlbl?></th>
<?

$query	= GenQuery('iptrack','s','*','ipupdate','',array('mac'),array('='),array($n[2]) );
$res	= @DbQuery($query,$link);
if($res){
	$row = 0;
	while( $l = @DbFetchRow($res) ){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		$lip = long2ip($l[3]);
		echo "<tr class=\"$bg\"><th class=\"$bi\">\n";
		echo "$row</th><td>". date($datfmt,$l[1]) ."</td><td>$l[2]</td><td><a href=Nodes-List.php?ina=nodip&opa==&sta=$lip>$lip</a></td></tr>\n";
	}
	@DbFreeResult($res);
	}else{
		print @DbError($link);
	}
	?>
</table>
<table class="content">
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> IP <?=$chglbl?></td></tr>
</table>

</td><td class="helper">

<h2>IF <?=$chglbl?></h2>
<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th colspan=2><img src="img/32/clock.png"><br><?=$updlbl?></th>
<th><img src="img/32/dev.png"><br>Device</th>
<th><img src="img/32/port.png"><br>IF</th>
<th><img src="img/32/vlan.png"><br>Vlan</th>
<th><img src="img/32/dcal.png"><br>Metric</th>
<?

$query	= GenQuery('iftrack','s','*','ifupdate','',array('mac'),array('='),array($n[2]) );
$res	= @DbQuery($query,$link);
if($res){
	$row = 0;
	while( $l = @DbFetchRow($res) ){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		$utd = rawurlencode($l[2]);
		$uti = rawurlencode($l[3]);
		echo "<tr class=\"$bg\"><th class=\"$bi\">\n";
		echo "$row</th><td>". date($datfmt,$l[1]) ."</td>\n";
		echo "<td><a href=Devices-Status.php?dev=$utd&shp=on>$l[2]</a></td><td>";
		echo "<a href=Nodes-List.php?ina=device&opa==&sta=$utd&cop=AND&inb=ifname&opb==&stb=$uti>$l[3]</td><td>$l[4]</td><td>$l[5]</td></tr>\n";
	}
	@DbFreeResult($res);
	}else{
		print @DbError($link);
	}
	?>
</table>
<table class="content">
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> IF <?=$chglbl?></td></tr>
</table>

</td></tr></table>

<?
}elseif ($wol){
	if(preg_match("/dsk/",$_SESSION['group']) ){
		$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
		$query	= GenQuery('nodes','s','*','','',array('mac'),array('='),array($wol));
		$res	= @DbQuery($query,$link);
		$nnod	= @DbNumRows($res);
		if ($nnod != 1) {
			echo "<h4>$wol: $nnod $vallbl!</h4>";
			@DbFreeResult($res);
			die;
		}else{
			$n  = @DbFetchRow($res);
			@DbFreeResult($res);
			$ip = long2ip($n[1]);
		}
		wake($ip,$wol, 9);
	}else{
		echo $nokmsg;
	}
?>
<h5>WoL <?=$srvlbl?> <?=$ip?> OK, <?=$updlbl?> = 10 <?=$tim['s']?></h5>
<script language="JavaScript"><!--
setTimeout("history.go(-1)",10000);
//--></script>
<?
}elseif ($del){
	if($isadmin){
		$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
		$query	= GenQuery('nodes','d','','','',array('mac'),array('='),array($del) );
		if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$del $dellbl OK</h5>";}
		$query	= GenQuery('iptrack','d','','','',array('mac'),array('='),array($del) );
		if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$del iptrack $dellbl OK</h5>";}
		$query	= GenQuery('iftrack','d','','','',array('mac'),array('='),array($del) );
		if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$del iptrack $dellbl OK</h5>";}
?>
<script language="JavaScript"><!--
setTimeout("history.go(-2)",2000);
//--></script>
<?
	}else{
		echo $nokmsg;
	}
}

include_once ("inc/footer.php");
?>
