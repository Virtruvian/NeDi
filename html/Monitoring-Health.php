<?
# Program: Monitoring-Health.php
# Programmer: Remo Rickli

error_reporting(E_ALL ^ E_NOTICE);

$refresh   = 60;
$printable = 1;

include_once ("inc/header.php");
include_once ("inc/libdev.php");
include_once ("inc/libmon.php");

$_GET = sanitize($_GET);
$reg = isset($_GET['reg']) ? $_GET['reg'] : "";
$cty = isset($_GET['cty']) ? $_GET['cty'] : "";
$bld = isset($_GET['bld']) ? $_GET['bld'] : "";
$loc = TopoLoc($reg,$cty,$bld);
$evloc = ($loc)?"&cop=AND&inb=location&opb=regexp&stb=".urlencode($loc)."":"";

$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
$query	= GenQuery('monitoring','s','name,lastok,status,latency','name','',array('test','location'),array('regexp','regexp'),array('.',$loc),array('AND'),'LEFT JOIN devices USING (device)');
#TODO optmize to only use what's needed -> several queries?
$res	= @DbQuery($query,$link);
if($res){
	$nmon= 0;
	$mal = 0;
	$lok = 0;
	while( ($m = @DbFetchRow($res)) ){
		if($m[1] > $lok){$lok = $m[1];}
		$deval[$m[0]] = $m[2];
		if($m[2]){$mal++;}
		if($m[3] > $latw){$slow[$m[0]] = $m[3];}
		$nmon++;
	}
	@DbFreeResult($res);
}else{
	print @DbError($link);
}

$query	= GenQuery('devices','g','lastdis','','',array('lastdis'),array('>'),array(time() - $rrdstep));
$res	= @DbQuery($query,$link);
if($res){
	$ndis = @DbFetchRow($res);
	@DbFreeResult($res);
}else{
	print @DbError($link);
}

$monok = 0;
if( time() < (2*$pause + $lok) ){$monok = 1;}
?>
<h1>Monitoring Health</h1>
<form method="get" name="dynfrm" action="<?=$self?>.php">
<input type="hidden" name="reg" value="<?=$reg?>">
<input type="hidden" name="cty" value="<?=$cty?>">
<input type="hidden" name="bld" value="<?=$bld?>">
<table class="content"><tr class="<?=$modgroup[$self]?>1">
<th width="50"><a href="<?=$self?>.php"><img src="img/32/<?=$selfi?>.png"></a></th>
<td valign="top" align="center">
<h3>

<?
if($monok){
	echo "<a href=\"Reports-Monitoring.php?rep[]=mav\"><img src=\"img/16/bchk.png\" title=\"$avalbl $stslbl ($nmon $tgtlbl Monitored, $ndis[1] Device $laslbl $dsclbl)\"></a>\n";
}else{
	echo "<a href=\"System-Services.php\"><img src=\"img/16/bdis.png\" title=\"System $srvlbl (Monitoring $stco[100]?)\"></a>\n";
}
?>
<a href="Monitoring-Timeline.php?det=level&bsz=si"><img src="img/16/news.png" title="<?=$msglbl?> <?=$hislbl?>"></a> <?=$stalbl?>

</h3><p>
<?
if($_SESSION['gsiz']){

?>
<a href="Devices-Graph.php?dv=Totals&if[]=mon"><img src="inc/drawrrd.php?t=mon&s=<?=$_SESSION['gsiz']?>" title="<?=$avalbl?> <?=$gralbl?>"></a>
<a href="Devices-Graph.php?dv=Totals&if[]=msg"><img src="inc/drawrrd.php?t=msg&s=<?=$_SESSION['gsiz']?>" title="<?=$msglbl?> <?=$sumlbl?>"></a>
<?
}else{
?>
<img src="img/32/dev.png" title="Checking <?=$nmon?> <?=$totlbl?>">
<?
}
if($mal == 0){
	if($monok){
		if(!$_SESSION['gsiz']){echo "<img src=\"img/32/bchk.png\" title=\"$nmon $tgtlbl Monitored, $ndis[1] Device $laslbl $dsclbl\">";}
	}else{
		if(!$_SESSION['gsiz']){echo "<img src=\"img/32/bcls.png\" title=\"$nonlbl Monitored, $ndis[1] Device $laslbl $dsclbl\">";}
		if($_SESSION['vol']){echo "<embed src=\"inc/enter2.mp3\" volume=\"$_SESSION[vol]\" hidden=\"true\">\n";}
	}
}else{
	if($mal == 1){
		if(!$_SESSION['gsiz']){echo "<img src=\"img/32/fobl.png\" title=\"1 $mlvl[200]\">";}
		if($_SESSION['vol']){echo "<embed src=\"inc/alarm1.mp3\" volume=\"$_SESSION[vol]\" hidden=\"true\">\n";}
	}elseif($mal < 10){
		if($ni[0] < 3){
			$ico = "fovi";
		}elseif($ni[0] < 5){
			$ico = "foye";
		}else{
			$ico = "foor";
		}
		if(!$_SESSION['gsiz']){echo "<img src=\"img/32/$ico.png\" title=\"$mal $mlvl[200]\">";}
		if($_SESSION['vol']){echo "<embed src=\"inc/alarm2.mp3\" volume=\"$_SESSION[vol]\" hidden=\"true\">\n";}
	}else{
		if(!$_SESSION['gsiz']){echo "<img src=\"img/32/ford.png\" title=\"$mal $mlvl[200]!\">";}
		if($_SESSION['vol']){echo "<embed src=\"inc/alarm3.mp3\" volume=\"$_SESSION[vol]\" hidden=\"true\">\n";}
	}

?>
<p>
<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th><img src="img/16/dev.png"><br>Device</th><th><img src="img/16/flag.png"><br><?=$mlvl['200']?></th>
<?
	$row = 0;
	foreach(array_keys($deval) as $d){
		if($deval[$d]){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$t = substr($d,0,$_SESSION['lsiz']);					# Shorten targets
			list($statbg,$stat) = StatusBg(1,1,$deval[$d],$bi);
			echo "<tr class=\"$bg\"><td>\n";
			echo "<a href=\"Monitoring-Setup.php?ina=name&opa=%3D&sta=".urlencode($d)."\"><b>$t</b></a></td><td class=\"$statbg\">$stat</td></tr>\n";
		}
	}
?>
</table>
<?
}

if(!$_SESSION['gsiz']){StatusIncidents($loc,$_SESSION['gsiz']);}

if( count($slow) ){
?>
<p>
<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th><img src="img/16/dev.png"><br>Device</th><th><img src="img/16/clock.png"><br><?=$latlbl?></th>
<?
	$row = 0;
	foreach(array_keys($slow) as $d){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		$t    = substr($d,0,$_SESSION['lsiz']);						# Shorten targets
		$dbar = Bar($slow[$d],$latw,'si');
		echo "<tr class=\"$bg\"><td>\n";
		echo "<a href=\"Monitoring-Setup.php?ina=name&opa==&sta=".urlencode($d)."\"><b>$t</b></a></td><td>$dbar $slow[$d]ms</td></tr>\n";
	}
?>
</table>
<?
}
?>
</td>

<td valign="top" align="center">
<h3>
<a href="Reports-Interfaces.php?rep[]=trf"><img src="img/16/bbup.png" title="<?=$trflbl?> <?=$stslbl?>"></a>
<a href="Reports-Interfaces.php?rep[]=poe"><img src="img/16/batt.png" title="PoE <?=$stslbl?>"></a>
<?=$lodlbl?></h3><p>
<?
if($_SESSION['gsiz']){
?>
<a href="Devices-Graph.php?dv=Totals&if[]=ttr"><img src="inc/drawrrd.php?t=ttr&s=<?=$_SESSION['gsiz']?>" title="<?=$totlbl?> non-Link <?=$trflbl?>"></a>
<a href="Devices-Graph.php?dv=Totals&if[]=tpw"><img src="inc/drawrrd.php?t=tpw&s=<?=$_SESSION['gsiz']?>" title="<?=$totlbl?> PoE <?=$lodlbl?>"></a>
<?
}

StatusIf($loc,'bbup',$inblbl,$_SESSION['lim'],$_SESSION['gsiz']);
StatusIf($loc,'bbdn',$oublbl,$_SESSION['lim'],$_SESSION['gsiz']);

if(!$_SESSION['gsiz']){
		$query	= GenQuery('interfaces','s','count(*),round(sum(poe)/1000)','','',array('poe','location'),array('>','regexp'),array('0',$loc),array('AND'),'JOIN devices USING (device)');
		$res	= @DbQuery($query,$link);
		if($res){
			$m = @DbFetchRow($res);
			if($m[0]){echo "<p><b><img src=\"img/32/batt.png\" title=\"$m[0] PoE IF\">$m[1] W</b>\n";}
			@DbFreeResult($res);
		}else{
			print @DbError($link);
		}
}

?>
</td>

<td valign="top" align="center">
<h3>
<a href="Reports-Interfaces.php?rep[]=err"><img src="img/16/brup.png" title="<?=$errlbl?> <?=$stslbl?>"></a>
<a href="Reports-Interfaces.php?rep[]=dis"><img src="img/16/bdis.png" title="Disabled IF"></a>
<?=$errlbl?></h3><p>
<?
if($_SESSION['gsiz']){
?>
<a href="Devices-Graph.php?dv=Totals&if[]=ter"><img src="inc/drawrrd.php?t=ter&s=<?=$_SESSION['gsiz']?>" title="<?=$totlbl?> non-Wlan <?=$errlbl?>"></a>
<a href="Devices-Graph.php?dv=Totals&if[]=ifs"><img src="inc/drawrrd.php?t=ifs&s=<?=$_SESSION['gsiz']?>" title="IF <?=$stalbl?> <?=$sumlbl?>"></a>
<?
}
StatusIf($loc,'brup',$inblbl);
StatusIf($loc,'brdn',$oublbl);
StatusIf($loc,'bdis',"Disabled IF $tim[n]");
?>
</td>

<td valign="top" align="center" width="100">
<h3>
<span id="counter"><?=$refresh?></span>
<img src="img/16/exit.png" title="Stop" onClick="stop_countdown(interval);">
</h3>
<?
StatusCpu($loc);
StatusMem($loc);
StatusTmp($loc);

if($_SESSION['gsiz']){StatusIncidents($loc);}

?>
</td></tr></table>
</form>
<p>
<?
if($_SESSION['lim']){
?>

<h2><?=$msglbl?> <?=$tim['t']?></h2>

<table class="full"><tr>
<td  width="13%" class="helper">

<h3><?=$levlbl?></h3>
<?
	$firstmsg = time() - 86400;
	$query	= GenQuery('events','g','level','level desc',$_SESSION['lim'],array('time','location'),array('>','regexp'),array($firstmsg,$loc),array('AND'),'LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	if($res){
		$nlev = @DbNumRows($res);
		if($nlev){
?>
<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th width="40"><img src="img/16/idea.png"><br><?=$levlbl?></th>
<th><img src="img/16/bell.png"><br><?=$msglbl?></th>
<?
			$row = 0;
			while( ($m = @DbFetchRow($res)) ){
				if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
				$row++;
				$mbar = Bar($m[1],0,'si');
				echo "<tr class=\"$bg\"><th class=\"".$mbak[$m[0]]."\">\n";
				echo "<img src=\"img/16/".$mico[$m[0]].".png\" title=\"".$mlvl[$m[0]]."\"></th><td nowrap>$mbar <a href=Monitoring-Events.php?ina=level&opa==&sta=$m[0]$evloc>$m[1]</a></td></tr>\n";
			}
			echo "</table>\n";
		}else{
			echo "<p><h5>$nonlbl</h5>";
		}
		@DbFreeResult($res);
	}else{
		print @DbError($link);
	}
?>

</td>
<td  width="13%" class="helper">

<h3><?=$clalbl?></h3>
<?
	$query	= GenQuery('events','g','class','cnt desc',$_SESSION['lim'],array('time','location'),array('>','regexp'),array($firstmsg,$loc),array('AND'),'LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	if($res){
		$nlev = @DbNumRows($res);
		if($nlev){
?>
<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th width="40"><img src="img/16/abc.png"><br><?=$clalbl?></th>
<th><img src="img/16/bell.png"><br><?=$msglbl?></th>
<?
			$row = 0;
			while( ($m = @DbFetchRow($res)) ){
				if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
				$row++;
				list($ei,$et)   = EvClass($m[0]);
				$mbar = Bar($m[1],"lvl$m[0]",'si');
				echo "<tr class=\"$bg\"><th class=\"$bi\">\n";
				echo "<img src=\"img/16/$ei.png\" title=\"$et\"></th><td nowrap>$mbar <a href=Monitoring-Events.php?ina=class&opa==&sta=$m[0]$evloc>$m[1]</a></td></tr>\n";
			}
			echo "</table>\n";
		}else{
			echo "<p><h5>$nonlbl</h5>";
		}
		@DbFreeResult($res);
	}else{
		print @DbError($link);
	}
?>

</td>
<td  width="13%" class="helper">

<h3><?=$srclbl?></h3>
<?
	$query	= GenQuery('events','g','source','cnt desc',$_SESSION['lim'],array('time','location'),array('>','regexp'),array($firstmsg,$loc),array('AND'),'LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	if($res){
		$nlev = @DbNumRows($res);
		if($nlev){
?>
<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th><img src="img/16/say.png"><br><?=$srclbl?></th>
<th><img src="img/16/bell.png"><br><?=$msglbl?></th>
<?
			$row = 0;
			while( ($r = @DbFetchRow($res)) ){
				if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
				$row++;
				$s    = substr($r[0],0,$_SESSION['lsiz']);		# Shorten sources
				$mbar = Bar($r[1],0,'si');
				echo "<tr class=\"$bg\"><th class=\"$bi\" align=\"left\" nowrap><img src=\"img/16/say.png\" title=\"$r[0]\">$s</th>\n";
				echo "<td nowrap>$mbar <a href=Monitoring-Events.php?ina=source&opa==&sta=".urlencode($r[0])."$evloc>$r[1]</a></td></tr>\n";
			}
			echo "</table>\n";
		}else{
			echo "<p><h5>$nonlbl</h5>";
		}
		@DbFreeResult($res);
	}else{
		print @DbError($link);
	}
?>

</td>
<td width="61%" class="helper">

<h3><?=$mlvl[200]?> & <?=$mlvl[250]?> <?=$lstlbl?></h3>
<?
	Events($_SESSION['lim'],array('level','time','location'),array('>=','>','regexp'),array(200,$firstmsg,$loc),array('AND','AND'),1);
	echo "</td></tr></table>";
}

if($_SESSION['col']){

	TopoTable($reg,$cty,$bld);

	if(!$reg and count($dreg) > 1){
		TopoRegs();
	}elseif(!$cty){
		TopoCities($reg);
	}elseif(!$bld){
		TopoBuilds($reg,$cty);
	}else{
		TopoFloors($reg,$cty,$bld);
	}
}

include_once ("inc/footer.php");

?>
