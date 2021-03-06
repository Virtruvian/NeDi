<?php
# Program: Monitoring-Health.php
# Programmer: Remo Rickli

$refresh   = 60;
$printable = 0;
$firstmsg  = time() - 86400;

$printable = 1;
$exportxls = 0;

include_once ("inc/header.php");
include_once ("inc/libdev.php");
include_once ("inc/libmon.php");

$_GET = sanitize($_GET);
$reg   = isset($_GET['reg']) ? $_GET['reg'] : '';
$cty   = isset($_GET['cty']) ? $_GET['cty'] : '';
$bld   = isset($_GET['bld']) ? $_GET['bld'] : '';

$alarr = array();
$loc   = TopoLoc($reg,$cty,$bld);
$evloc = ($loc)?"&co[]=AND&in[]=location&op[]=like&st[]=".urlencode($loc):'';
$rploc = ($loc)?"&in[]=location&op[]=like&st[]=".urlencode($loc):'';

$shrrd = ($reg or !$_SESSION['gsiz'] or $_SESSION['view'])?0:$_SESSION['gsiz'];
$isiz  = ($srrd == 2)?"16":"32";

?>
<h1>Monitoring Health</h1>
<form method="get" name="dynfrm" action="<?= $self ?>.php">
<input type="hidden" name="reg" value="<?= $reg ?>">
<input type="hidden" name="cty" value="<?= $cty ?>">
<input type="hidden" name="bld" value="<?= $bld ?>">
<table class="content"><tr class="bgmain">
<th width="50"><a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png" title="<?= $self ?>"></a>
</th>
<td valign="top" align="center">
<h3>
<a href="Reports-Monitoring.php?rep[]=mav<?= $rploc ?>"><img src="img/16/dbin.png" title="<?= $avalbl ?> <?= $stslbl ?>"></a>
<a href="Monitoring-Timeline.php?det=level&bsz=si<?= $rploc ?>"><img src="img/16/news.png" title="<?= $msglbl ?> <?= $hislbl ?>"></a> <?= $stalbl ?>
</h3><p>
<?php

$link  = DbConnect($dbhost,$dbuser,$dbpass,$dbname);
TopoMon($loc);

if(!$shrrd){StatusIncidents($loc,0);}

StatusMon($shrrd);

?>
</td>

<td valign="top" align="center">
<h3>
<a href="Reports-Interfaces.php?rep[]=trf<?= $rploc ?>"><img src="img/16/bbup.png" title="<?= $trflbl ?> <?= $stslbl ?>"></a>
<a href="Reports-Combination.php?rep=poe<?= $rploc ?>"><img src="img/16/batt.png" title="PoE <?= $stslbl ?>"></a>
<?= $lodlbl ?></h3><p>
<?php
if($shrrd){
?>
<a href="Devices-Graph.php?dv=Totals&if[]=ttr&sho=1"><img src="inc/drawrrd.php?t=ttr&s=<?= $shrrd ?>" title="<?= $totlbl ?> <?= $acslbl ?> <?= $trflbl ?>"></a>
<a href="Devices-Graph.php?dv=Totals&if[]=tpw&sho=1"><img src="inc/drawrrd.php?t=tpw&s=<?= $shrrd ?>" title="<?= $totlbl ?> PoE <?= $lodlbl ?>"></a>
<?php
}

StatusIf($loc,'bbup',$shrrd);
StatusIf($loc,'bbdn',$shrrd);

if(!$shrrd){
		#$query	= GenQuery('interfaces','s','count(*),round(sum(poe)/1000)','','',array('poe','location'),array('>','like'),array('0',$loc),array('AND'),'JOIN devices USING (device)');
		$query	= GenQuery('devices','s','count(*),sum(totpoe)','','',array('totpoe','location'),array('>','like'),array('0',$loc),array('AND') );
		$res	= DbQuery($query,$link);
		if($res){
			$m = DbFetchRow($res);
			if($m[0]){echo "<h3><img src=\"img/32/batt.png\" title=\"$totlbl PoE, $m[0] Devices\">$m[1] W</h3>\n";}
			DbFreeResult($res);
		}else{
			print DbError($link);
		}
}

?>
</td>

<td valign="top" align="center">
<h3>
<a href="Reports-Interfaces.php?rep[]=err<?= $rploc ?>"><img src="img/16/brup.png" title="<?= $errlbl ?> <?= $stslbl ?>"></a>
<a href="Reports-Interfaces.php?rep[]=dis<?= $rploc ?>"><img src="img/16/bdis.png" title="<?= $dsalbl ?> IF <?= $tim['t'] ?>"></a>
<?= $errlbl ?></h3><p>
<?php
if($shrrd){
?>
<a href="Devices-Graph.php?dv=Totals&if[]=ter&sho=1"><img src="inc/drawrrd.php?t=ter&s=<?= $shrrd ?>" title="<?= $totlbl ?> non-Wlan <?= $errlbl ?>"></a>
<a href="Devices-Graph.php?dv=Totals&if[]=ifs&sho=1"><img src="inc/drawrrd.php?t=ifs&s=<?= $shrrd ?>" title="IF <?= $stalbl ?> <?= $sumlbl ?>"></a>
<?php
}
StatusIf($loc,'brup',$shrrd);
StatusIf($loc,'brdn',$shrrd);
StatusIf($loc,'bdis',$shrrd);
?>
</td>

<td valign="top" align="center" width="200">
<h3>
<img src="img/16/exit.png" title="Stop" onClick="stop_countdown(interval);">
<span id="counter"><?= $refresh ?></span>
</h3>
<?php
StatusCpu($loc,$shrrd,$isiz);
StatusMem($loc,$shrrd,$isiz);
StatusTmp($loc,$shrrd,$isiz);

if($shrrd) StatusIncidents($loc,$shrrd);

?>
</td></tr></table>
</form>
<p>
<?php
if($_SESSION['lim']){
	$jdev = ($_SESSION['view'] or $loc)?'LEFT JOIN devices USING (device)':'';			# Only join on devs if required makes it faster!
?>

<h2><?= $msglbl ?> <?= $tim['t'] ?></h2>

<table class="full"><tr>
<td  width="13%" class="helper">

<h3><?= $levlbl ?></h3>
<?php
	$query	= GenQuery('events','g','level','level desc',$_SESSION['lim'],array('time','location'),array('>','like'),array($firstmsg,$loc),array('AND'),$jdev);
	$res	= DbQuery($query,$link);
	if($res){
		$nlev = DbNumRows($res);
		if($nlev){
?>
<table class="content"><tr class="bgsub">
<th width="40"><img src="img/16/idea.png"><br><?= $levlbl ?></th>
<th><img src="img/16/bell.png"><br><?= $msglbl ?></th>
<?php
			$row = 0;
			while( ($m = DbFetchRow($res)) ){
				if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
				$row++;
				$mbar = Bar($m[1],0,'si');
				echo "<tr class=\"$bg\"><th class=\"".$mbak[$m[0]]."\">\n";
				echo "<img src=\"img/16/".$mico[$m[0]].".png\" title=\"".$mlvl[$m[0]]."\"></th><td nowrap>$mbar <a href=\"Monitoring-Events.php?in[]=level&op[]==&st[]=$m[0]$evloc\">$m[1]</a></td></tr>\n";
			}
			echo "</table>\n";
		}else{
			echo "<p><h5>$nonlbl</h5>";
		}
		DbFreeResult($res);
	}else{
		print DbError($link);
	}
?>

</td>
<td  width="13%" class="helper">

<h3><?= $clalbl ?></h3>
<?php
	$query	= GenQuery('events','g','class','cnt desc',$_SESSION['lim'],array('time','location'),array('>','like'),array($firstmsg,$loc),array('AND'),$jdev);
	$res	= DbQuery($query,$link);
	if($res){
		$nlev = DbNumRows($res);
		if($nlev){
?>
<table class="content"><tr class="bgsub">
<th width="40"><img src="img/16/abc.png"><br><?= $clalbl ?></th>
<th><img src="img/16/bell.png"><br><?= $msglbl ?></th>
<?php
			$row = 0;
			while( ($m = DbFetchRow($res)) ){
				if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
				$row++;
				list($ei,$et)   = EvClass($m[0]);
				$mbar = Bar($m[1],"lvl$m[0]",'si');
				echo "<tr class=\"$bg\"><th class=\"$bi\">\n";
				echo "<img src=\"$ei\" title=\"$et\"></th><td nowrap>$mbar <a href=\"Monitoring-Events.php?in[]=class&op[]==&st[]=$m[0]$evloc\">$m[1]</a></td></tr>\n";
			}
			echo "</table>\n";
		}else{
			echo "<p><h5>$nonlbl</h5>";
		}
		DbFreeResult($res);
	}else{
		print DbError($link);
	}
?>

</td>
<td  width="13%" class="helper">

<h3><?= $srclbl ?></h3>
<?php
	$query	= GenQuery('events','g','source','cnt desc',$_SESSION['lim'],array('time','location'),array('>','like'),array($firstmsg,$loc),array('AND'),$jdev);
	$res	= DbQuery($query,$link);
	if($res){
		$nlev = DbNumRows($res);
		if($nlev){
?>
<table class="content"><tr class="bgsub">
<th><img src="img/16/say.png"><br><?= $srclbl ?></th>
<th><img src="img/16/bell.png"><br><?= $msglbl ?></th>
<?php
			$row = 0;
			while( ($r = DbFetchRow($res)) ){
				if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
				$row++;
				$s    = substr($r[0],0,$_SESSION['lsiz']);		# Shorten sources
				$mbar = Bar($r[1],0,'si');
				echo "<tr class=\"$bg\"><th class=\"$bi\" align=\"left\" title=\"$r[0]\">$s</th>\n";
				echo "<td nowrap>$mbar <a href=\"Monitoring-Events.php?in[]=source&op[]==&st[]=".urlencode($r[0])."$evloc\">$r[1]</a></td></tr>\n";
			}
			echo "</table>\n";
		}else{
			echo "<p><h5>$nonlbl</h5>";
		}
		DbFreeResult($res);
	}else{
		print DbError($link);
	}
?>

</td>
<td width="61%" class="helper">

<h3><?= $mlvl[200] ?> & <?= $mlvl[250] ?> <?= $lstlbl ?></h3>
<?php
	Events($_SESSION['lim'],array('level','time','location'),array('>=','>','like'),array(200,$firstmsg,$loc),array('AND','AND'),($jdev)?1:0);
	echo "</td></tr></table>";
}

if($_SESSION['col']){

	TopoTable($reg,$cty,$bld);

	if(!$reg) $leok = 1;
	if( count($dreg) == 1 ){
		$reg = array_pop ( array_keys($dreg) );
		if( count($dcity[$reg]) == 1 ){
			$cty = array_pop ( array_keys($dcity[$reg]) );
		}
	}

	if(!$reg){
		TopoRegs();
	}elseif(!$cty){
		TopoCities($reg);
	}elseif(!$bld){
		TopoBuilds($reg,$cty);
	}else{
		TopoFloors($reg,$cty,$bld);
	}
	if($leok) TopoLocErr();
}elseif(file_exists("log/montools.php")) {
	include_once ("log/montools.php");
}


include_once ("inc/footer.php");

?>
