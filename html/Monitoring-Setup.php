<?php
# Program: Monitoring-Setup.php
# Programmer: Remo Rickli

error_reporting(E_ALL ^ E_NOTICE);

$calendar  = 1;
$printable = 1;
$exportxls = 0;

include_once ("inc/header.php");
include_once ("inc/libdev.php");
include_once ("inc/libmon.php");

$_GET = sanitize($_GET);
$ina = isset($_GET['ina']) ? $_GET['ina'] : "";
$opa = isset($_GET['opa']) ? $_GET['opa'] : "";
$sta = (isset($_GET['sta']) && $ina != "") ? $_GET['sta'] : "";

$tst = isset($_GET['tst']) ? $_GET['tst'] : "";
$top = isset($_GET['top']) ? $_GET['top'] : "";
$trs = isset($_GET['trs']) ? $_GET['trs'] : "";
$adp = isset($_GET['adp']) ? $_GET['adp'] : "";
$rav = isset($_GET['rav']) ? $_GET['rav'] : "";
$uip = isset($_GET['uip']) ? $_GET['uip'] : "";
$efd = isset($_GET['efd']) ? $_GET['efd'] : "";
$elv = isset($_GET['elv']) ? $_GET['elv'] : "";
$inf = isset($_GET['inf']) ? $_GET['inf'] : "";
$al  = isset($_GET['al']) ? $_GET['al'] : "";

$upd = isset($_GET['upd']) ? $_GET['upd'] : "";
$del = isset($_GET['del']) ? $_GET['del'] : "";

$des = isset($_GET['des']) ? $_GET['des'] : "";
$dpt = isset($_GET['dpt']) ? $_GET['dpt'] : "";
$dps = isset($_GET['dps']) ? $_GET['dps'] : "";

$cols = array(	"name"=>"Name",
		"monip"=>"IP $adrlbl",
		"class"=>$clalbl,
		"depend"=>$deplbl,
		"test"=>"$tstlbl",
		"testopt"=>"$tstlbl $sndlbl",
		"testres"=>"$tstlbl $realbl",
		"lastok"=>"$laslbl OK",
		"status"=>$stalbl,
		"lost"=>$loslbl,
		"alert"=>$mlvl['200'],
		"eventdel"=>"$msglbl $dcalbl",
		"eventlvl"=>"$levlbl $limlbl",
		"eventfwd"=>"$msglbl $fwdlbl",
		"type"=>"Device $typlbl",
		"devos"=>"Device OS",
		"bootimage"=>"Bootimage",
		"location"=>$loclbl,
		"contact"=>$conlbl,
		"devgroup"=>$grplbl
		);

$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
?>
<h1>Monitoring Setup</h1>

<?php  if( !isset($_GET['print']) ) { ?>

<form method="get" 'action'="<?= $self ?>.php" name="mons">
<table class="content"><tr class="<?= $modgroup[$self] ?>1">
<th width="50">

<a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png"></a>

</th>
<th valign="top">

<h3><?= $fltlbl ?></h3>
<select size="1" name="ina">
<option value=""><?= $fltlbl ?>->
<?php
foreach ($cols as $k => $v){
       echo "<option value=\"$k\"".( ($ina == $k)?" selected":"").">$v\n";
}
?>
</select>

<select size="1" name="opa">
<?php selectbox("oper",$opa) ?>
</select>
<p>
<a href="javascript:show_calendar('mons.sta');"><img src="img/16/date.png"></a>
<input type="text" name="sta" value="<?= $sta ?>" size="20">

</th>
<td valign="top">

<h3><?= $cfglbl ?></h3>
<img src="img/16/bchk.png" title="<?= $tstlbl ?>">
<select size="1" name="tst">
<option value=""><?= $sellbl ?>->
<option value="-">(<?= $nonlbl ?>)
<option value="uptime">uptime
<option value="ping">ping
<option value="http">http
<option value="https">https
<option value="telnet">telnet
<option value="ssh">ssh
<option value="mysql">mysql
<option value="cifs">cifs
</select>

<img src="img/16/flag.png" title="<?= $mlvl['200'] ?>">
<select size="1" name="al">
<option value="">-
<option value="1"><?= $nonlbl ?>
<option value="2"><?= $msglbl ?>
<option value="3">Mail
<option value="131">Mail (<?= $rptlbl ?>)
<option value="7">Mail & SMS
<option value="135">Mail & SMS (<?= $rptlbl ?>)
</select>

<!--
<img src="img/16/bbrt.png" title="<?= $sndlbl ?>">
<input type="text" name="top" value="<?= $top ?>" size="40" disabled="disabled" >
<img src="img/16/bblf.png" title="<?= $realbl ?>">
<input type="text" name="trs" value="<?= $trs ?>" size="40" disabled="disabled" >
--!>
<p>
<img src="img/16/bell.png" title="<?= $msglbl ?> <?= $actlbl ?>">
<select size="1" name="efd">
<option value="fwd"><?= $fwdlbl ?>
<option value="del"><?= $dcalbl ?>
</select>
<select size="1" name="elv">
<option value=""><?= $levlbl ?> <?= $limlbl ?> ->
<option value="1"><?= $nonlbl ?>
<option value="11" class="txtb"><?= $mlvl['10'] ?>
<option value="51" class="good"><?= $mlvl['50'] ?>
<option value="101" class="noti"><?= $mlvl['100'] ?>
<option value="151" class="warn"><?= $mlvl['150'] ?>
<option value="201" class="alrm"><?= $mlvl['200'] ?>
<option value="251" class="crit"><?= $mlvl['250'] ?>
</select>
<input type="text" name="inf" size="40">

</td>
<th valign="top">

<h3><?= $reslbl ?></h3>
<img src="img/16/ncon.png" title="Auto <?= $deplbl ?>"> 
<input type="checkbox" name="adp">
<br>
<img src="img/16/net.png" title="IP <?= $updlbl ?>"> 
<input type="checkbox" name="uip">
<br>
<img src="img/16/walk.png" title="<?= $avalbl ?>">
<input type="checkbox" name="rav">
</th>

<th width="80">
<input type="submit" value="<?= $sholbl ?>">
<p>
<input type="submit" name="upd" value="<?= $updlbl ?>">
<p>
<input type="submit" name="del" value="<?= $dellbl ?>" onclick="return confirm('Monitor <?= $dellbl ?>, <?= $cfmmsg ?>')" >

</th>
</tr></table></form><p>
<?php
}
if($del){
	$query	= GenQuery('monitoring','d','','','',array($ina),array($opa),array($sta) );
	if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$dellbl $ina $opa $sta OK</h5>";}
}

if( isset($_GET['ina']) ){
ConHead($ina, $opa, $sta);
?>

<table class="content"><tr class="<?= $modgroup[$self] ?>2">
<th colspan="2"><img src="img/16/trgt.png"><br><?= $tgtlbl ?></th>
<th><img src="img/16/chrt.png"><br><?= $stslbl ?></th>
<th><img src="img/16/bchk.png"><br><?= $tstlbl ?></th>
<th><img src="img/16/ncon.png"><br><?= $deplbl ?></th>
<th><img src="img/16/flag.png"><br><?= $mlvl['200'] ?> </th>
<th><img src="img/16/bell.png"><br><?= $msglbl ?> <?= $actlbl ?></th></tr>

<?php
	$query	= GenQuery('monitoring','s','monitoring.*,devip','monitoring.name','',array($ina),array($opa),array($sta),array(),'LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	if($res){
		$row  = 0;
		$nnod = 0;
		$ndev = 0;
		$srcip= 0;
		while( ($mon = @DbFetchRow($res)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$una = urlencode($mon[0]);
			list($statbg,$stat) = StatusBg(1,($mon[3])?1:0,$mon[7],$bi);

			TblRow($bg);
			$cmpip = 1;
			if ($mon[2] == "dev"){
				$ndev++;
				$srcip = $mon[29];
				$query	= GenQuery('links','s','neighbor,nbrifname','','',array('device'),array('='),array($mon[0]) );
				$dres	= @DbQuery($query,$link);
				$neb	= array();
				if($dres){
					if ( @DbNumRows($dres) ) {
						while( ($l = @DbFetchRow($dres)) ){
							$neb[$l[0]] = $l[1];
						}
						@DbFreeResult($dres);
					}
				}else{
					print @DbError($link);
				}
				echo "<th class=\"$statbg\"><a href=\"Devices-Status.php?dev=$una\"><img src=\"img/16/dev.png\" title=\"$stat\"></a>";
			}elseif($mon[2] == "node"){
				$nnod++;
				$query	= GenQuery('nodes','s','nodip,device,ifname','','',array('name'),array('='),array($mon[0]) );
				$dres	= @DbQuery($query,$link);
				$neb	= array();
				if($dres){
					$nnod = @DbNumRows($dres);
					if($nnod == 1) {
						echo "<th class=\"$statbg\"><a href=\"Nodes-List.php?ina=name&opa=%3D&sta=$una\"><img src=\"img/16/node.png\"  title=\"$stat\"></a>";
						$l = @DbFetchRow($dres);
						$srcip = $l[0];
						$neb[$l[1]] = $l[2];
					}elseif($nnod > 1){
						$cmpip = 0;
						echo "<th class=\"warn part\"><a href=\"Nodes-List.php?ina=name&opa=%3D&sta=$una\"><img src=\"img/16/nods.png\" title=\"$mullbl Nodes $namlbl!\"></a>";
					}else{
						$cmpip = 0;
						echo "<th class=\"warn part\"><a href=\"Nodes-List.php?ina=nodip&opa=%3D&sta=$mon[1]\"><img src=\"img/16/bcls.png\" title=\"$nonlbl Nodes! (IP $stat)\"></a>";
					}
					@DbFreeResult($dres);
				}else{
					print @DbError($link);
				}
			}else{
				echo "<th class=\"txtb\"><img src=\"img/16/bbox.png\">";
			}

			$depst = "";
			$alst  = "";
			$elst  = "";

			if($upd){
				if($adp){
					if(count(array_keys($neb) ) == 1){
						$dquery	= GenQuery('monitoring','u','name','=',$mon[0],array('depend'),array(),array( key($neb) ) );
						if( !@DbQuery($dquery,$link) ){
							$depst = "<img src=\"img/16/bcnl.png\" title=\"" .DbError($link)."\">";
						}else{
							$depst = "<img src=\"img/16/bchk.png\" title=\"Auto $deplbl OK\">";
							$mon[18] = key($neb);
						}
					}else{
						$depst = "<img src=\"img/16/bdis.png\" title=\"$mullbl $deplbl\">";
					}
				
				}

				if($rav){
					$uquery	= GenQuery('monitoring','u','name','=',$mon[0],array('lastok','status','lost','ok','latency','latmax','latavg'),array(),array(0,0,0,0,0,0,0) );
					if( !@DbQuery($uquery,$link) ){
						$ravst = "<img src=\"img/16/bcnl.png\" title=\"" .DbError($link)."\">";
					}else{
						$ravst = "<img src=\"img/16/bchk.png\" title=\"$avalbl $reslbl OK\">";
						$mon[6] = 0;
						$mon[7] = 0;
						$mon[8] = 0;
						$mon[9] = 0;
						$mon[10] = 0;
						$mon[11] = 0;
						$mon[12] = 0;
					}
				}

				if($uip){
					$uquery	= GenQuery('monitoring','u','name','=',$mon[0],array('monip'),array(),array($srcip) );
					if( !@DbQuery($uquery,$link) ){
						$uipst = "<img src=\"img/16/bcnl.png\" title=\"" .DbError($link)."\">";
					}else{
						$uipst = "<img src=\"img/16/bchk.png\" title=\"IP $updlbl OK\">";
						$mon[1] = $srcip;
					}
				}

				if($tst){
					$c = ($tst == "-")?"":$tst;
					$equery	= GenQuery('monitoring','u','name','=',$mon[0],array('test'),array(),array($c) );
					if( !@DbQuery($equery,$link) ){
						$testst = "<img src=\"img/16/bcnl.png\" title=\"" .DbError($link)."\">";
					}else{
						$testst = "<img src=\"img/16/bchk.png\" title=\"$tstlbl $updlbl OK\">";
						$mon[3] = $c;
					}
				}

				if($top){
					$c = ($top == "-")?"":$top;
					$equery	= GenQuery('monitoring','u','name','=',$mon[0],array('testopt'),array(),array($c) );
					if( !@DbQuery($equery,$link) ){
						$topst = "<img src=\"img/16/bcnl.png\" title=\"" .DbError($link)."\">";
					}else{
						$topst = "<img src=\"img/16/bchk.png\" title=\"$tstlbl $sndlbl $updlbl OK\">";
						$mon[4] = $c;
					}
				}

				if($trs){
					$c = ($trs == "-")?"":$trs;
					$equery	= GenQuery('monitoring','u','name','=',$mon[0],array('testres'),array(),array($c) );
					if( !@DbQuery($equery,$link) ){
						$trsst = "<img src=\"img/16/bcnl.png\" title=\"" .DbError($link)."\">";
					}else{
						$trsst = "<img src=\"img/16/bchk.png\" title=\"$tstlbl $rcvlbl $updlbl OK\">";
						$mon[5] = $c;
					}
				}

				if($al){
					$fquery	= GenQuery('monitoring','u','name','=',$mon[0],array('alert'),array(),array($al-1) );	# Adding 1 in the form, so it's still true with 0
					if( !@DbQuery($fquery,$link) ){
						$alst = "<img src=\"img/16/bcnl.png\" title=\"" .DbError($link)."\">";
					}else{
						$alst = "<img src=\"img/16/bchk.png\" title=\"$fwdlbl $updlbl OK\">";
						$mon[14] = $al-1;
					}
				}

				if($elv){
					$myelv = ($efd == "fwd" or $elv == 1)?$elv-1:$elv;				# Adding 1 in the form, so it's still true with 0 (remove eventlevel)
					$fquery	= GenQuery('monitoring','u','name','=',$mon[0],array('eventlvl'),array(),array($myelv) );
					if( !@DbQuery($fquery,$link) ){
						$elst = "<img src=\"img/16/bcnl.png\" title=\"" .DbError($link)."\" align=\"right\">";
					}else{
						$elst = "<img src=\"img/16/bchk.png\" title=\"$levlbl $limlbl $updlbl OK\" align=\"right\">";
						$mon[16] = $myelv;
					}
				}
				if($inf){
					$i = ($inf == "-")?"":$inf;
					if($efd == "fwd"){
						$c = "eventfwd";
						$t = "$fwdlbl $fltlbl";
						$mon[15] = $i;
					}else{
						$c = "eventdel";
						$t = "$dcalbl $fltlbl";
						$mon[17] = $i;
					}
					$equery	= GenQuery('monitoring','u','name','=',$mon[0],array($c),array(),array($i) );
					if( !@DbQuery($equery,$link) ){
						$infst = "<img src=\"img/16/bcnl.png\" title=\"" .DbError($link)."\" align=\"right\">";
					}else{
						$infst = "<img src=\"img/16/bchk.png\" title=\"$t $updlbl OK\" align=\"right\">";
					}
				}
			}elseif($des and $des ==  $mon[0] and ($dps or $dpt) ){
				$dpt = ($dps)?$dps:$dpt;
				$dquery	= GenQuery('monitoring','u','name','=',$mon[0],array('depend'),array(),array($dpt) );
				if( !@DbQuery($dquery,$link) ){
					$depst = "<img src=\"img/16/bcnl.png\" title=\"" .DbError($link)."\">";
				}else{
					$depst = "<img src=\"img/16/bchk.png\" title=\"$deplbl = $dpt OK\">";
					$mon[18] = $dpt;
				}
			}
			
			if($mon[1] != $srcip and $cmpip){
				echo "<img src=\"img/16/bdis.png\" title=\"IP $chglbl ".long2ip($mon[1])." -> ".long2ip($srcip).": $updlbl!\">";
			}

?>
<td><b><a href="?ina=name&opa=%3D&sta=<?= $una ?>"><?= $mon[0] ?></a> <?= $uipst ?></b>

</td>
<td>

<?php
			if ($mon[6]){
				$lac = ($mon[10] > $latw)?'drd':'grn';
				$lmc = ($mon[11] > $latw)?'drd':'grn';
				$lvc = ($mon[12] > $latw)?'drd':'grn';
				$los = ($mon[8])?'drd':'grn';
				$las = ($mon[6] < (time() - $rrdstep) )?'drd':'grn';
				echo "$latlbl: <span class=\"$lac\">$mon[10]ms </span>\n";
				echo "avg:<span class=\"$lvc\">$mon[12]ms</span>\n";
				echo "max:<span class=\"$lmc\">$mon[11]ms</span><br>\n";
				echo "$loslbl/OK: <span class=\"$los\">$mon[8]/$mon[9]</span>\n";
				echo " $laslbl: <span class=\"$las\">". date($datfmt,$mon[6]) . "</span>\n";
			}
			echo $ravst;
?>

</td>
<th>

<a href="?ina=test&opa=regexp&sta=<?= ($mon[3])?$mon[3]:"^$" ?>"><?=TestImg($mon[3],$mon[4],$mon[5]) ?> <?= $testst ?> <?= $topst ?> <?= $trsst ?></a>

</th>
<td>

<?php  if( isset($_GET['print']) ){ ?>
<?= $mon[18] ?>
<?php  }else{ ?>
<form method="get">
<input type="hidden" name="ina" value="<?= $ina ?>">
<input type="hidden" name="opa" value="<?= $opa ?>">
<input type="hidden" name="sta" value="<?= $sta ?>">
<input type="hidden" name="des" value="<?= $mon[0] ?>">
<input type="text" name="dpt" size="12" value="<?= $mon[18] ?>" onfocus="select();" onchange="this.form.submit();" title="<?= $wrtlbl ?> <?= $namlbl ?>">
<select size="1" name="dps" onchange="this.form.submit();" title="<?= $namlbl ?>">
<option value=""><?= $sellbl ?>
<option value="-">(<?= $nonlbl ?>)
<?php
			if($neb){
				foreach ($neb as $nen => $nif){
					echo "<option value=\"$nen\">$nen-$nif\n";
				}
			}
?>
</select> <?= $depst ?>
</form>
<?php } ?>

</td>
<th>

<a href="?ina=action&opa==&sta=<?= $mon[14] ?>">
<?php
if($mon[14] & 128){
	echo "<img src=\"img/16/brld.png\" title=\"Mail $rptlbl\">";
}elseif($mon[14] & 2){
	echo "<img src=\"img/16/mail.png\" title=\"Mail\">";
}elseif($mon[14] & 1){
	echo "<img src=\"img/16/bell.png\" title=\"$msglbl\">";
}else{
	echo "<img src=\"img/16/bcls.png\" title=\"$nonlbl Mail\">";
}
if($mon[14] & 4){
	echo "<img src=\"img/16/sms.png\" title=\"SMS\">";
}else{
	echo "<img src=\"img/16/bcls.png\" title=\"$nonlbl SMS\">";
}
?>
</a>
<?= $alst ?>

</th>
<td>

<?php
if($mon[15] or $mon[16] and !($mon[16]%2) ){
?>
<img src="img/16/mail.png" title="<?= $fwdlbl ?>">
<?php
	if($mon[16] and !($mon[16]%2) ){
?>
<a href="?ina=eventlvl&opa==&sta=<?= $mon[16] ?>"><img src="img/16/<?= $mico[$mon[16]] ?>.png" title="<?= $mlvl[$mon[16]] ?>"></a>
<?	}
	if($mon[15]){
?>
<a href="?ina=eventfwd&opa==&sta=<?= $mon[15] ?>"><?= $mon[15] ?></a>
<?
	}
}

if($mon[16]%2 or $mon[17]){
?>
<br><img src="img/16/bdis.png" title="<?= $dcalbl ?>">
<?php
	if($mon[16]%2){
?>
<a href="?ina=eventlvl&opa==&sta=<?= $mon[16] ?>"><img src="img/16/<?= $mico[$mon[16]-1] ?>.png" title="<?= $mlvl[$mon[16]-1] ?>"></a>
<?	}
	if($mon[17]){
?>
<a href="?ina=eventdel&opa==&sta=<?= $mon[17] ?>"><?= $mon[17] ?></a> 
<?	}
}
?>
<?= $infst ?><?= $elst ?>
</td>
</tr>

<?php
		}
		@DbFreeResult($res);
	}else{
		print @DbError($link);
	}
?>
</table>
<table class="content">
<tr class="<?= $modgroup[$self] ?>2"><td><?= $nnod ?> Nodes, <?= $ndev ?> Devices <?= $totlbl ?></td></tr>
</table>
<?php
}
include_once ("inc/footer.php");
?>
