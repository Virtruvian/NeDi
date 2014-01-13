<?
# Program: Monitoring-Setup.php
# Programmer: Remo Rickli

error_reporting(E_ALL ^ E_NOTICE);

$calendar  = 1;
$printable = 1;

include_once ("inc/header.php");
include_once ("inc/libdev.php");
include_once ("inc/libmon.php");

$_GET = sanitize($_GET);
$ina = isset($_GET['ina']) ? $_GET['ina'] : "";
$opa = isset($_GET['opa']) ? $_GET['opa'] : "";
$sta = (isset($_GET['sta']) && $ina != "") ? $_GET['sta'] : "";

$tst = isset($_GET['tst']) ? $_GET['tst'] : "";
$adp = isset($_GET['adp']) ? $_GET['adp'] : "";
$rav = isset($_GET['rav']) ? $_GET['rav'] : "";
$efd = isset($_GET['efd']) ? $_GET['efd'] : "";
$inf = isset($_GET['inf']) ? $_GET['inf'] : "";
$al  = isset($_GET['al']) ? $_GET['al'] : "";

$upd = isset($_GET['upd']) ? $_GET['upd'] : "";
$del = isset($_GET['del']) ? $_GET['del'] : "";

$des = isset($_GET['des']) ? $_GET['des'] : "";
$dpt = isset($_GET['dpt']) ? $_GET['dpt'] : "";
$dps = isset($_GET['dps']) ? $_GET['dps'] : "";

$cols = array(	"name"=>"Name",
		"class"=>$clalbl,
		"depend"=>$deplbl,
		"test"=>"Test",
		"lastok"=>"$laslbl OK",
		"status"=>$stalbl,
		"lost"=>$loslbl,
		"alert"=>$mlvl['200'],
		"eventfwd"=>"$msglbl $fwdlbl",
		"eventdel"=>"$msglbl $dellbl",
		"type"=>"Device $typlbl",
		"devos"=>"Device OS",
		"bootimage"=>"Bootimage",
		"location"=>$loclbl,
		"contact"=>$conlbl,
		"vtpdomain"=>"VTP Domain"
		);

$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
?>
<h1>Monitoring Setup</h1>

<?if( !isset($_GET['print']) ){?>

<form method="get" action="<?=$self?>.php" name="mons">
<table class="content"><tr class="<?=$modgroup[$self]?>1">
<th width="50">

<a href="<?=$self?>.php"><img src="img/32/<?=$selfi?>.png"></a>

</th>
<th valign="top">

<h3><?=$fltlbl?></h3>
<select size="1" name="ina">
<option value=""><?=$fltlbl?>->
<?
foreach ($cols as $k => $v){
       echo "<option value=\"$k\"".( ($ina == $k)?"selected":"").">$v\n";
}
?>
</select>

<select size="1" name="opa">
<? selectbox("oper",$opa);?>
</select>
<p>
<a href="javascript:show_calendar('mons.sta');"><img src="img/16/date.png"></a>
<input type="text" name="sta" value="<?=$sta?>" size="20">

</th>
<td valign="top">

<h3><?=$cfglbl?></h3>
<img src="img/16/bchk.png" title="Test">
<select size="1" name="tst">
<option value=""><?=$sellbl?>->
<option value="-">-
<option value="uptime">uptime
<option value="ping">ping
<option value="http">http
<option value="https">https
<option value="telnet">telnet
<option value="ssh">ssh
<option value="mysql">mysql
<option value="cifs">cifs
</select>

<img src="img/16/flag.png" title="<?=$mlvl['200']?>">
<select size="1" name="al">
<option value="">-
<option value="1"><?=$nonlbl?>
<option value="2">Mail
<option value="3">SMS
<option value="4">Mail & SMS
</select>

<p>
<img src="img/16/bell.png" title="<?=$msglbl?> <?=$fwdlbl?>/<?=$dellbl?>">
<select size="1" name="efd">
<option value="fwd"><?=$fwdlbl?>
<option value="del"><?=$dellbl?>
</select>
<input type="text" name="inf" size="40">

</td>
<th valign="top">

<h3><?=$reslbl?></h3>
<img src="img/16/ncon.png" title="Auto <?=$deplbl?>"> 
<input type="checkbox" name="adp">
<p>
<img src="img/16/bchk.png" title="<?=$avalbl?>">
<input type="checkbox" name="rav">
</th>

<th width="80">
<input type="submit" value="<?=$sholbl?>">
<p>
<input type="submit" name="upd" value="<?=$updlbl?>">
<p>
<input type="submit" name="del" value="<?=$dellbl?>" onclick="return confirm('Monitor <?=$dellbl?>?')" >

</th>
</tr></table></form><p>
<?
}
if($del){
	$query	= GenQuery('monitoring','d','','','',array($ina),array($opa),array($sta) );
	if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$dellbl $ina $opa $sta OK</h5>";}
}

if( isset($_GET['ina']) ){
ConHead($ina, $opa, $sta);
?>

<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th colspan="2"><img src="img/16/find.png"><br><?=$tgtlbl?></th>
<th><img src="img/16/chrt.png"><br><?=$stslbl?></th>
<th><img src="img/16/ncon.png"><br><?=$deplbl?></th>
<th><img src="img/16/bchk.png"><br>Test</th>
<th><img src="img/16/bell.png"><br><?=$fwdlbl?>/<?=$dellbl?></th>
<th><img src="img/16/flag.png"><br><?=$mlvl['200']?> </th></tr>

<?
	$query	= GenQuery('monitoring','s','monitoring.*','monitoring.name','',array($ina),array($opa),array($sta),'','LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		$nnod = 0;
		$ndev = 0;
		while( ($mon = @DbFetchRow($res)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$una = urlencode($mon[0]);
			list($statbg,$stat) = StatusBg(1,($mon[3])?1:0,$mon[5],$bi);

			echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\">\n";

			if ($mon[2] == "dev"){
				$ndev++;
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
			}elseif ($mon[2] == "node"){
				$nnod++;
				$query	= GenQuery('nodes','s','device,ifname','','',array('name'),array('='),array($mon[0]) );
				$dres	= @DbQuery($query,$link);
				$neb	= array();
				if($dres){
					if ( @DbNumRows($dres) ) {
						while( ($l = @DbFetchRow($dres)) ){
							$neb[$l[0]] = $l[1];
						}
					}
					@DbFreeResult($dres);
				}else{
					print @DbError($link);
				}
				echo "<th class=\"$statbg\"><a href=\"Nodes-List.php?ina=nodip&opa=%3D&sta=$mon[1]\"><img src=\"img/16/node.png\"  title=\"$stat\"></a>";
			}else{
				echo "<th class=\"txtb\"><img src=\"img/16/bbox.png\">";
			}

			$depst = "";
			$alst  = "";
			if($upd){
				if($adp){
					if(count(array_keys($neb) ) == 1){
						$dquery	= GenQuery('monitoring','u',"name=\"$mon[0]\"",'','',array('depend'),'',array( key($neb) ) );
						if( !@DbQuery($dquery,$link) ){
							$depst = "<img src=\"img/16/bcnl.png\" title=\"" .DbError($link)."\">";
						}else{
							$depst = "<img src=\"img/16/bchk.png\" title=\"Auto $deplbl OK\">";
							$mon[15] = key($neb);
						}
					}else{
						$depst = "<img src=\"img/16/bdis.png\" title=\"$mullbl $deplbl\">";
					}
				
				}

				if($rav){
					$uquery	= GenQuery('monitoring','u',"name=\"$mon[0]\"",'','',array('lastok','status','lost','ok','latency','latmax','latavg'),'',array(0,0,0,0,0,0,0) );
					if( !@DbQuery($uquery,$link) ){
						$ravst = "<img src=\"img/16/bcnl.png\" title=\"" .DbError($link)."\">";
					}else{
						$ravst = "<img src=\"img/16/bchk.png\" title=\"$avalbl $reslbl OK\">";
						$mon[4] = 0;
						$mon[5] = 0;
						$mon[6] = 0;
						$mon[7] = 0;
						$mon[8] = 0;
						$mon[9] = 0;
						$mon[10] = 0;
					}
				}

				if($tst){
					$c = ($tst == "-")?"":$tst;
					$equery	= GenQuery('monitoring','u',"name=\"$mon[0]\"",'','',array('test'),'',array($c) );
					if( !@DbQuery($equery,$link) ){
						$testst = "<img src=\"img/16/bcnl.png\" title=\"" .DbError($link)."\">";
					}else{
						$testst = "<img src=\"img/16/bchk.png\" title=\"Test $updlbl OK\">";
						$mon[3] = $c;
					}
				}

				if($inf){
					$i = ($inf == "-")?"":$inf;
					if($efd == "fwd"){
						$c = "eventfwd";
						$mon[13] = $i;
					}else{
						$c = "eventdel";
						$mon[14] = $i;
					}
					$equery	= GenQuery('monitoring','u',"name=\"$mon[0]\"",'','',array($c),'',array($i) );
					if( !@DbQuery($equery,$link) ){
						$infst = "<img src=\"img/16/bcnl.png\" title=\"" .DbError($link)."\">";
					}else{
						$infst = "<img src=\"img/16/bchk.png\" title=\"$c $updlbl OK\">";
					}
				}
				if($al){
					$fquery	= GenQuery('monitoring','u',"name=\"$mon[0]\"",'','',array('alert'),'',array($al-1) );				# Adding 1 in the form, so it's true with $al=0
					if( !@DbQuery($fquery,$link) ){
						$alst = "<img src=\"img/16/bcnl.png\" title=\"" .DbError($link)."\">";
					}else{
						$alst = "<img src=\"img/16/bchk.png\" title=\"$fwdlbl $updlbl OK\">";
						$mon[12] = $al-1;
					}
				}
			}elseif($des and $des ==  $mon[0] and ($dps or $dpt) ){
				$dpt = ($dps)?$dps:$dpt;
				$dquery	= GenQuery('monitoring','u',"name=\"$des\"",'','',array('depend'),'',array($dpt) );
				if( !@DbQuery($dquery,$link) ){
					$depst = "<img src=\"img/16/bcnl.png\" title=\"" .DbError($link)."\">";
				}else{
					$depst = "<img src=\"img/16/bchk.png\" title=\"$deplbl = $dpt OK\">";
					$mon[15] = $dpt;
				}
			}
?>
<td><b><a href="?ina=name&opa=%3D&sta=<?=$una?>"><?=$mon[0]?></a></b></td>
</td><td>
<?
			if ($mon[4]){
				$lac = ($mon[8] > $latw)?'drd':'grn';
				$lmc = ($mon[9] > $latw)?'drd':'grn';
				$lvc = ($mon[10] > $latw)?'drd':'grn';
				$los = ($mon[6])?'drd':'grn';
				$las = ($mon[4] < (time() - $rrdstep) )?'drd':'grn';
				echo "$latlbl: <span class=\"$lac\">$mon[8]ms </span>\n";
				echo "avg:<span class=\"$lvc\">$mon[10]ms</span>\n";
				echo "max:<span class=\"$lmc\">$mon[9]ms</span><br>\n";
				echo "$loslbl/OK: <span class=\"$los\">$mon[6]/$mon[7]</span>\n";
				echo " $laslbl: <span class=\"$las\">". date($datfmt,$mon[4]) . "</span>\n";
			}
			echo $ravst;
?>
</td>
<td>
<form method="get">
<input type="hidden" name="ina" value="<?=$ina?>">
<input type="hidden" name="opa" value="<?=$opa?>">
<input type="hidden" name="sta" value="<?=$sta?>">
<input type="hidden" name="des" value="<?=$mon[0]?>">
<input type="text" name="dpt" size="12" value="<?=$mon[15]?>" onfocus="select();"  onchange="this.form.submit();" title="<?=$wrtlbl?> <?=$namlbl?>">
<select size="1" name="dps" onchange="this.form.submit();" title="<?=$namlbl?>">
<option value=""><?=$sellbl?>
<option value="-">-
<?
			if($neb){
				foreach ($neb as $nen => $nif){
					echo "<option value=\"$nen\">$nen-$nif\n";
				}
			}
?>
</select> <?=$depst?>
</form>
</td>

<th>
<a href="?ina=test&opa=regexp&sta=<?=($mon[3])?$mon[3]:"^$"?>"><?=TEimg($mon[3])?> <?=$testst?></a>
</th>

<td>
<?
if($mon[13]){
?>
<a href="?ina=eventfwd&opa==&sta=<?=$mon[13]?>"><img src="img/16/flag.png"></a> <?=$mon[13]?><br>
<?}?>
<?
if($mon[14]){
?>
<a href="?ina=eventdel&opa==&sta=<?=$mon[14]?>"><img src="img/16/bdis.png"></a> <?=$mon[14]?>
<?}?>
<?=$infst?>
</td>

<th>
<a href="?ina=alert&opa==&sta=<?=$mon[12]?>"><img src="img/16/<?=(($mon[12] & 1)?"mail":"bcls")?>.png"></a>
<a href="?ina=alert&opa==&sta=<?=$mon[12]?>"><img src="img/16/<?=(($mon[12] & 2)?"sms":"bcls")?>.png"></a>
<?=$alst?>
</th>

</tr>
<?
		}
		@DbFreeResult($res);
	}else{
		print @DbError($link);
	}
?>
</table>
<table class="content">
<tr class="<?=$modgroup[$self]?>2"><td><?=$nnod?> Nodes, <?=$ndev?> Devices <?=$totlbl?></td></tr>
</table>
<?
}
include_once ("inc/footer.php");
?>
