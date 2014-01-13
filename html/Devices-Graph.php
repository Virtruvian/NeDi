<?
# Program: Devices-Graph.php
# Programmer: Remo Rickli
#TODO decide on JS framework and add slider widgets -> or wait for HTML5 <input type="range"> (and datetime)!!!!

error_reporting(E_ALL ^ E_NOTICE);

$nocache   = 1;
$refresh   = 600;
$calendar  = 1;
$printable = 1;

include_once ("inc/header.php");

$_GET = sanitize($_GET);
$dv = isset($_GET['dv']) ? $_GET['dv'] : "";
$if = isset($_GET['if']) ? $_GET['if'] : array();
$sze = isset($_GET['sze']) ? $_GET['sze'] : "5";
$sho = isset($_GET['sho']) ? 1 : 0;
$cad = isset($_GET['cad']) ? 1 : 0;
$tem = isset($_GET['tem']) ? $_GET['tem'] : 2;

$strsta = (isset($_GET['sta']) ) ? $_GET['sta'] : date("m/d/Y H:i", time() - $rrdstep * 800);
$strend = (isset($_GET['end']) ) ? $_GET['end'] : date("m/d/Y H:i");
if(!$sho){# Let graph follow autoupdate
	$strsta = date("m/d/Y H:i",strtotime($strsta) + $refresh);
	$strend = date("m/d/Y H:i");
}
$sta = strtotime($strsta);
$end = strtotime($strend);
$qstr = strpos($_SERVER['QUERY_STRING'], "sta")?$_SERVER['QUERY_STRING']:$_SERVER['QUERY_STRING']."&sta=".urlencode($strsta)."&end=".urlencode($strend);
?>
<h1>Device <?=$gralbl?></h1>

<?if( !isset($_GET['print']) ){?>

<form method="get" action="<?=$self?>.php" name="dynfrm">
<table class="content">
<tr class="<?=$modgroup[$self]?>1">
<th width="50"><a href="<?=$self?>.php"><img src="img/32/<?=$selfi?>.png"></a></th>
<th>
<select size="6" name="dv" onchange="this.form.submit();">
<?
$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
$query	= GenQuery('devices','s','device,devip,snmpversion,readcomm,memcpu,temp,cuslabel,cusvalue','device','',array('snmpversion'),array('!='),array('0') );
$res	= @DbQuery($query,$link);
if($res){
	echo "<option value=\"Totals\"".(($dv == "Totals")?" selected":"")."> Network Totals";
	echo "<option value=\"\" style=\"color: blue\">- Devices -";
	while( ($d = @DbFetchRow($res)) ){
		echo "<option value=\"$d[0]\"";
		if($dv == $d[0]){
			echo " selected";
			$ip = long2ip($d[1]);
			$sp = ($d[2] & 127);
			$hc = ($d[2] & 128);
			$co = $d[3];
			$mem = $d[4];
			$tmp = $d[5];
			$cg =$d[6];
			$cv = $d[7];
			if($cg){
				list($ct,$cy,$cu) = explode(";", $cg);
			}else{# TODO should only be necessary until all .defs are discovered with 1.0.6
				$ct = "Mem IO";
				$cu = "Bytes free";
			}
		}
		echo " >$d[0]\n";
	}
	@DbFreeResult($res);
}else{
	print @DbError($link);
}
?>
</select>
<select multiple size="6" name="if[]">
<?
if ($dv == "Totals") {
?>
<option value="msg"<?=(in_array("msg",$if))?" selected":"";?>> <?=$msglbl?> <?=$sumlbl?>
<option value="mon"<?=(in_array("mon",$if))?" selected":"";?>> <?=$tgtlbl?> <?=$avalbl?>
<option value="nod"<?=(in_array("nod",$if))?" selected":"";?>> <?=$totlbl?> Nodes
<option value="tpw"<?=(in_array("tpw",$if))?" selected":"";?>> <?=$totlbl?> PoE
<option value="ttr"<?=(in_array("ttr",$if))?" selected":"";?>> <?=$totlbl?> non-link <?=$trflbl?>
<option value="ter"<?=(in_array("ter",$if))?" selected":"";?>> <?=$totlbl?> non-Wlan <?=$errlbl?>
<option value="ifs"<?=(in_array("ifs",$if))?" selected":"";?>> IF <?=$stalbl?>  <?=$sumlbl?>
<?
}elseif ($dv) {
?>
<option value="cpu"<?=(in_array("cpu",$if))?" selected":"";?>> CPU
<?
if($mem){
?>
<option value="mem"<?=(in_array("mem",$if))?" selected":"";?>> Mem
<?
}
if($tmp){
?>
<option value="tmp"<?=(in_array("tmp",$if))?" selected":"";?>> <?=$tmplbl?>
<?
}
if($ct != "-"){
?>
<option value="cuv"<?=(in_array("cuv",$if))?" selected":"";?>> <?=$ct?>
<?
}
?>
<option value="" style="color: blue">- Interfaces -
<?
	$query	= GenQuery('interfaces','s','ifname,alias,comment','ifidx','',array('device'),array('='),array($dv) );
	$res	= @DbQuery($query,$link);
	if($res){
		while( ($i = @DbFetchRow($res)) ){
			echo "<option value=\"$i[0]\" ";
			if(in_array($i[0],$if)){echo "selected";}
			echo " >$i[0] " . substr("$i[1] $i[2]\n",0,30);
		}
		@DbFreeResult($res);
	}
}
?>
</select>
</th>
<th>

<a href="?<?=SkewTime($qstr,"sta", -7)?>"><img src="img/16/blft.png" title="<?=$sttlbl?> -<?=$tim['w']?>"></a>
<a href="?<?=SkewTime($qstr,"sta", -1)?>"><img src="img/16/bblf.png" title="<?=$sttlbl?> -<?=$tim['d']?>"></a>
<a href="javascript:show_calendar('dynfrm.sta');"><img src="img/16/date.png" title="<?=$sttlbl?> <?=$strsta?>"></a>
<input type="hidden" name="sta" value="<?=$strsta?>">
<a href="?<?=SkewTime($qstr,"sta", 1)?>"><img src="img/16/bbrt.png" title="<?=$sttlbl?> +<?=$tim['d']?>"></a>
<a href="?<?=SkewTime($qstr,"sta", 7)?>"><img src="img/16/brgt.png" title="<?=$sttlbl?> +<?=$tim['w']?>"></a>
<p>
<a href="?<?=SkewTime($qstr,"all", -7)?>"><img src="img/16/blft.png" title="<?=$gralbl?> -<?=$tim['w']?>"></a>
<a href="?<?=SkewTime($qstr,"all", -1)?>"><img src="img/16/bblf.png" title="<?=$gralbl?> -<?=$tim['d']?>"></a>
<img src="img/16/grph.png" title="<?=$alllbl?>">
<a href="?<?=SkewTime($qstr,"all", 1)?>"><img src="img/16/bbrt.png" title="<?=$gralbl?> +<?=$tim['d']?>"></a>
<a href="?<?=SkewTime($qstr,"all", 7)?>"><img src="img/16/brgt.png" title="<?=$gralbl?> +<?=$tim['w']?>"></a>
<p>
<a href="?<?=SkewTime($qstr,"end", -7)?>"><img src="img/16/blft.png" title="<?=$endlbl?> -<?=$tim['w']?>"></a>
<a href="?<?=SkewTime($qstr,"end", -1)?>"><img src="img/16/bblf.png" title="<?=$endlbl?> -<?=$tim['d']?>"></a>
<a href="javascript:show_calendar('dynfrm.end');"><img src="img/16/date.png" title="<?=$endlbl?> <?=$strend?>"></a>
<input type="hidden" name="end" value="<?=$strend?>">
<a href="?<?=SkewTime($qstr,"end", 1)?>"><img src="img/16/bbrt.png" title="<?=$endlbl?> +<?=$tim['d']?>"></a>
<a href="?<?=SkewTime($qstr,"end", 7)?>"><img src="img/16/brgt.png" title="<?=$endlbl?> +<?=$tim['w']?>"></a>
</th>
<?if($cacticli){?>
<td align="center"><h3>Cacti</h3>
<select size="1" name="tem">
<option value="2"><?=$trflbl?>
<option value="22"><?=$errlbl?>
<option value="24">Broadcast
</select><p>
<input type="submit" name="cad" value="<?=$addlbl?>">
</td>
<?}?>
<th width="80">
<select size="1" name="sze">
<option value=""><?=$siz['x']?>
<option value="4" <?=($sze == "4")?"selected":""?> ><?=$siz['l']?>
<option value="3" <?=($sze == "3")?"selected":""?> ><?=$siz['m']?>
<option value="2" <?=($sze == "2")?"selected":""?> ><?=$siz['s']?>
</select>
<p>
<input type="submit" name="sho" value="<?=$sholbl?>">
<p>
<span id="counter"><?=$refresh?></span>
<img src="img/16/exit.png" title="Stop" onClick="stop_countdown(interval);">
</th>
</tr></table></form>

<?
}
if($dv){
	$ud = rawurlencode($dv);
	if($dv != "Totals" and !isset($_GET['print']) and strpos($_SESSION['group'],$modgroup['Devices-Status']) !== false ){
		echo "<h2><a href=\"Devices-Status.php?dev=$ud\"><img src=\"img/16/sys.png\"></a> $dv</h2>\n";
	}else{
		echo "<h2>$dv</h2>";
	}
}
?>
<div align="center"><p>
<?

if($sta > $end){
	echo "<h4>$fislbl > $laslbl</h4>";
}elseif($cad){
	if($debug){echo "$cacticli/add_device.php --description=\"$dv\" --ip=\"$ip\" --template=1 --version=\"$sp\" --community=\"$co\"";}
	$adev = exec("$cacticli/add_device.php --description=\"$dv\" --ip=\"$ip\" --template=1 --version=\"$sp\" --community=\"$co\"");
	echo "<div class=\"textpad code txta\">$adev</div>";
	flush();
	$devid = preg_replace("/.*device-id: \((\d+)\).*/","$1",$adev);
	if($devid){
		if($tem == 22){
			$qtyp = 2;
		}elseif($tem == 24){
			$qtyp = 3;
		}elseif($hc){
			$qtyp = 14;
		}else{
			$qtyp = 13;
		}
		foreach ($if as $i){
			if($debug){echo "$cacticli/add_graphs.php --graph-type=ds --graph-template-id=$tem --host-id=$devid --snmp-query-id=1 --snmp-query-type-id=$qtyp --snmp-field=ifName --snmp-value=\"$i\"";}
			$agrf = exec("$cacticli/add_graphs.php --graph-type=ds --graph-template-id=$tem --host-id=$devid --snmp-query-id=1 --snmp-query-type-id=$qtyp --snmp-field=ifName --snmp-value=\"$i\"");
			echo "<div class=\"textpad code txtb\">$agrf</div>";
			flush();
		}
	}
}elseif ($dv == "Totals") {
	if( in_array("msg",$if) ){
		echo "<a href=\"Monitoring-Timeline.php?srt=".urlencode($strsta)."&end=".urlencode($strend)."&det=level\">\n";
		echo "<img src=\"inc/drawrrd.php?&s=$sze&t=msg&a=$sta&e=$end\" title=\"$sholbl Timeline\"></a>\n";
	}
	if( in_array("mon",$if) ){
		echo "<a href=\"Monitoring-Timeline.php?ina=class&opa==&sta=moni&srt=".urlencode($strsta)."&end=".urlencode($strend)."&det=level\">\n";
		echo "<img src=\"inc/drawrrd.php?&s=$sze&t=mon&a=$sta&e=$end\" title=\"$tgtlbl $avalbl\"></a>\n";
	}
	echo ( in_array("nod",$if) )?"<img src=\"inc/drawrrd.php?&s=$sze&t=nod&a=$sta&e=$end\" title=\"$totlbl Nodes\">\n":"";
	echo ( in_array("tpw",$if) )?"<img src=\"inc/drawrrd.php?&s=$sze&t=tpw&a=$sta&e=$end\" title=\"$totlbl PoE\">\n":"";
	if( in_array("ttr",$if) ){
		echo "<a href=\"Monitoring-Timeline.php?ina=class&opa=regexp&sta=trf[aw]&srt=".urlencode($strsta)."&end=".urlencode($strend)."&det=level\">\n";
		echo "<img src=\"inc/drawrrd.php?&s=$sze&t=ttr&a=$sta&e=$end\" title=\"$totlbl $trflbl\">\n";
	}
	if( in_array("ter",$if) ){
		echo "<a href=\"Monitoring-Timeline.php?ina=class&opa==&sta=trfe&srt=".urlencode($strsta)."&end=".urlencode($strend)."&det=level\">\n";
		echo "<img src=\"inc/drawrrd.php?&s=$sze&t=ter&a=$sta&e=$end\" title=\"$totlbl $errlbl\"></a>\n";
	}
	echo ( in_array("ifs",$if) )?"<img src=\"inc/drawrrd.php?&s=$sze&t=ifs&a=$sta&e=$end\" title=\"IF $stalbl $sumlbl\">\n":"";
}else{
	if( in_array("cpu",$if) ){echo "<img src=\"inc/drawrrd.php?dv=$ud&s=$sze&t=cpu&a=$sta&e=$end\" title=\"% CPU\">\n";}
	echo ( in_array("mem",$if) )?"<img src=\"inc/drawrrd.php?dv=$ud&s=$sze&t=mem&a=$sta&e=$end\" title=\"Mem $frelbl\">\n":"";
	echo ( in_array("tmp",$if) )?"<img src=\"inc/drawrrd.php?dv=$ud&s=$sze&t=tmp&a=$sta&e=$end\" title=\"$tmplbl\">\n":"";
	echo ( in_array("cuv",$if) )?"<img src=\"inc/drawrrd.php?dv=$ud&&if[]=".urlencode($ct)."&if[]=".urlencode($cu)."&s=$sze&t=cuv&a=$sta&e=$end\" title=\"$ct [$cu]\">\n":"";
	if( isset($if[0]) ){
		$uif = "";
		foreach ( $if as $i){
			if( !preg_match('/cpu|mem|tmp|cuv/',$i) ){
				$uif .= '&if[]='.rawurlencode($i);
			}
		}
		if($uif){
			echo "<img src=\"inc/drawrrd.php?dv=$ud$uif&s=$sze&t=trf&a=$sta&e=$end\" title=\"$trflbl\">\n";
			echo "<img src=\"inc/drawrrd.php?dv=$ud$uif&s=$sze&t=err&a=$sta&e=$end\" title=\"$errlbl\">\n";
			echo "<img src=\"inc/drawrrd.php?dv=$ud$uif&s=$sze&t=dsc&a=$sta&e=$end\" title=\"Discards\">\n";
			echo "<img src=\"inc/drawrrd.php?dv=$ud$uif&s=$sze&t=brc&a=$sta&e=$end\" title=\"Broadcasts\">\n";
		}
	}
}
?>
</div>
<?
include_once ("inc/footer.php");
?>
