<?
//===============================
// Reports related functions.
//===============================

//===================================================================
// Device Config Stats
function DevConfigs($ina,$opa,$sta,$lim,$ord){
	
	global $link,$modgroup,$self,$verb1,$cfglbl,$srtlbl,$mico,$loclbl,$locsep,$conlbl,$chglbl,$updlbl,$woulbl;
?>

<table class="full fixed"><tr><td class="helper">

<h2>CLI Devices <?=$woulbl?> <?=$cfglbl?></h2>

<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th width="33%" colspan="2"><img src="img/16/dev.png"><br>Device</th>
<th><img src="img/16/glob.png"><br>IP</th>
<th><img src="img/16/cog.png"><br>OS</th>
</tr>
<?

	if($ord){
		$ocol = "devip";
		$srt = "$srtlbl: IP";
	}else{
		$ocol = "device";
		$srt = "$srtlbl: Device";
	}
	$query	= GenQuery('devices','s','device,devip,cliport,devos,contact,location,icon',$ocol,$lim,array('config','cliport',$ina),array('COL IS','>',$opa),array('NULL','0',$sta),array('AND','AND'),'LEFT JOIN configs USING (device)');
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$l = explode($locsep, $r[5]);
			echo "<tr class=\"$bg\"><th class=\"$bi\"><img src=\"img/dev/$r[6].png\" title=\"$conlbl: $r[4], $loclbl: $l[0] $l[1] $l[2]\"></a></th>\n";
			echo "<td><a href=\"Devices-Status.php?dev=".urlencode($r[0])."\">$r[0]</a></b></td>\n";
			echo "<td>".Devcli(long2ip($r[1]),$r[2])."</td><td>$r[3]</td></tr>\n";
		}
	}else{
		print @DbError($link);
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Devices, <?=$srt?></td></tr>
</table>

</td><td class="helper">

<h2><?=$cfglbl?> <?=$woulbl?> <?=$chglbl?></h2>

<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th width="33%" colspan="2"><img src="img/16/dev.png"><br>Device</th>
<th><img src="img/16/glob.png"><br>IP</th>
<th><img src="img/16/date.png"><br><?=$updlbl?></th>
</tr>
<?
	if($ord){
		$ocol = "devip";
		$srt = "$srtlbl: IP";
	}else{
		$ocol = "device";
		$srt = "$srtlbl: Device";
	}
	$query	= GenQuery('configs','s','device,devip,cliport,devos,time,contact,location,icon',$ocol,$lim,array('changes',$ina),array('regexp',$opa),array('^$',$sta),array('AND'),'LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$l = explode($locsep, $r[6]);
			list($u1c,$u2c) = Agecol($r[4],$r[4],$row % 2);
			echo "<tr class=\"$bg\"><th class=\"$bi\"><img src=\"img/dev/$r[7].png\" title=\"$conlbl: $r[5], $loclbl: $l[0] $l[1] $l[2]\"></a></th>\n";
			echo "<td><a href=\"Devices-Status.php?dev=".urlencode($r[0])."\"><b>$r[0]</b></a></td>\n";
			echo "<td>".Devcli(long2ip($r[1]),$r[2])."</td><td bgcolor=\"#$u1c\" nowrap>".date($_SESSION['date'],$r[4])."</td></tr>\n";
		}
	}else{
		print @DbError($link);
	}

?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Devices, <?=$srt?></td></tr>
</table>

</td></tr></table>
<p>
<?
}

//===================================================================
// Device Discovery History
function DevHistory($ina,$opa,$sta,$lim,$ord){

	global $link,$modgroup,$self,$srtlbl,$timlbl,$dsclbl,$fislbl,$laslbl,$hislbl,$lstlbl,$updlbl,$msglbl;
?>
<h2>Device <?=$hislbl?></h2>

<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th width="120"><img src="img/16/clock.png"><br><?=$timlbl?></th>
<th><img src="img/16/blft.png"><br><?=$fislbl?> <?=$dsclbl?></th>
<th><img src="img/16/brgt.png"><br><?=$laslbl?> <?=$dsclbl?></th>
</tr>
<?
	$query	= GenQuery('devices','g','firstdis',($ord)?'firstdis':'firstdis desc',$lim,array($ina),array($opa),array($sta));
	$res	= @DbQuery($query,$link);
	$fisr   = DbNumRows($res);
	if($res){
		while( $r = @DbFetchRow($res) ){
			$devup[$r[0]]['fs'] = $r[1];
		}
	}
	$query	= GenQuery('devices','g','lastdis',($ord)?'lastdis':'lastdis desc',$lim,array($ina),array($opa),array($sta));
	$res	= @DbQuery($query,$link);
	$lasr   = DbNumRows($res);
	if($res){
		while( $r = @DbFetchRow($res) ){
			$devup[$r[0]]['ls'] = $r[1];
		}
	}

	if($ord){
		ksort ($devup);
		$srt = "$srtlbl: $laslbl - $fislbl";
	}else{
		krsort ($devup);
		$srt = "$srtlbl: $fislbl - $laslbl";
	}
	$row = 0;
	foreach ( array_keys($devup) as $d ){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		$fd   = urlencode(date("m/d/Y H:i:s",$d));
		echo "<tr class=\"$bg\"><td class=\"$bi\"><b>".date($_SESSION['date'],$d)."</b></td><td>\n";
		if( array_key_exists('fs',$devup[$d]) ){echo Bar($devup[$d]['fs'],"lvl50",'mi')." <a href=\"Devices-List.php?ina=firstdis&opa==&sta=$fd\" title=\"Device $lstlbl\">".$devup[$d]['fs']."</a>";}
		echo "</td><td>\n";
		if( array_key_exists('ls',$devup[$d]) ){echo Bar($devup[$d]['ls'],"lvl250",'mi')." <a href=\"Devices-List.php?ina=lastdis&opa==&sta=$fd\" title=\"Device $lstlbl\">".$devup[$d]['ls']."</a>";}
		echo "</td></tr>\n";
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$msglbl?> (<?=$fisr?> <?=$fislbl?>, <?=$lasr?> <?=$laslbl?>), <?=$srt?></td></tr>
</table>
<p>
<?
}

//===================================================================
// Device Link Stats (idea by Steffen1)
function DevLink($ina,$opa,$sta,$lim,$ord){
	
	global $link,$modgroup,$self,$verb1,$srtlbl,$loclbl,$locsep,$conlbl,$isolbl,$undlbl,$neblbl,$typlbl;
?>
<table class="full fixed"><tr><td class="helper">

<h2><?=(($verb1)?"$isolbl Devices":"Devices $isolbl")?></h2>

<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th width="33%" colspan="2"><img src="img/16/dev.png"><br>Device</th>
<th><img src="img/16/glob.png"><br>IP</th>
<th><img src="img/16/cog.png"><br>OS</th>
</tr>
<?

	if($ord){
		$ocol = 'devip';
		$srt = "$srtlbl: IP";
	}else{
		$ocol = 'device';
		$srt = "$srtlbl: Device";
	}
	$query	= GenQuery('devices','s','distinct device,devip,cliport,devos,contact,location,icon',$ocol,$lim,array('links.device',$ina),array('COL IS',$opa),array('NULL',$sta),array('AND'),'LEFT JOIN links USING (device)');
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$l = explode($locsep, $r[5]);
			echo "<tr class=\"$bg\"><th class=\"$bi\"><img src=\"img/dev/$r[6].png\" title=\"$conlbl: $r[4], $loclbl: $l[0] $l[1] $l[2]\"></a></th>\n";
			echo "<td><a href=\"Devices-Status.php?dev=".urlencode($r[0])."\">$r[0]</a></b></td>\n";
			echo "<td>".Devcli(long2ip($r[1]),$r[2])."</td><td>$r[3]</td></tr>\n";
		}
	}else{
		print @DbError($link);
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Devices, <?=$srt?></td></tr>
</table>

</td><td class="helper">

<h2><?=$neblbl?> <?=$undlbl?></h2>

<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th><img src="img/16/dev.png"><br>Device</th>
<th><img src="img/16/abc.png"><br>Link <?=$typlbl?></th>
<th><img src="img/16/find.png"><br><?=$neblbl?></th>
</tr>
<?
	if($ord){
		$ocol = 'neighbor';
		$srt = "$srtlbl: $neblbl";
	}else{
		$ocol = 'device';
		$srt = "$srtlbl: Device";
	}
	$query	= GenQuery('links','s','distinct links.device,linktype,neighbor',$ocol,$lim,array('devices.device',$ina),array('COL IS',$opa),array('NULL',$sta),array('AND'),'LEFT JOIN devices ON devices.device = neighbor');
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			echo "<tr class=\"$bg\">";
			echo "<td><a href=\"Devices-Status.php?dev=".urlencode($r[0])."\"><b>$r[0]</b></td><td>$r[1]</td>\n";
			echo "<td><a href=\"Monitoring-Events.php?ina=info&opa=regexp&sta=".urlencode($r[2])."\"><b>$r[2]</b></a></td></tr>\n";
		}
	}else{
		print @DbError($link);
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$neblbl?> <?=$undlbl?>, <?=$srt?></td></tr>
</table>

</td></tr></table>
<p>
<?
}

//===================================================================
// List device software
function DevSW($ina,$opa,$sta,$lim,$ord){

	global $link,$modgroup,$self,$srtlbl,$numlbl,$lstlbl;
?>
<table class="full fixed"><tr><td class="helper">

<h2>OS <?=$lstlbl?></h2>

<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th><img src="img/16/cbox.png"><br>OS</th>
<th><img src="img/16/dev.png"><br>Devices</th>
</tr>
<?
	if($ord){
		$ocol = 'devos';
		$srt = "$srtlbl: OS";
	}else{
		$ocol = 'cnt desc';
		$srt = "$srtlbl: $numlbl";
	}
	$query	= GenQuery('devices','g','devos',$ocol,$lim,array($ina),array($opa),array($sta));
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			if(!$r[0]){$r[0] = "^$";}
			$tbar = Bar($r[1],0);
			echo "<tr class=\"$bg\">\n";
			echo "<td><a href=\"Devices-List.php?ina=devos&opa==&sta=".urlencode($r[0])."\" title=\"Device $lstlbl\">$r[0]</a></td>\n";
			echo "<td>$tbar $r[1]</td></tr>\n";
		}
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Operating Systems, <?=$srt?></td></tr>
</table>

</td><td class="helper">

<h2>Bootimages</h2>

<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th><img src="img/16/cbox.png"><br>Bootimage</th>
<th><img src="img/16/dev.png"><br>Devices</th>
</tr>
<?
	if($ord){
		$ocol = 'bootimage';
		$srt = "$srtlbl: Bootimage";
	}else{
		$ocol = 'cnt desc';
		$srt = "$srtlbl: $numlbl";
	}
	$query	= GenQuery('devices','g','bootimage',$ocol,$lim,array($ina),array($opa),array($sta),'',$join);
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$o = "=";
			if(!$r[0]){$r[0]="^$";$o="regexp";}
			$tbar = Bar($r[1],0);
			echo "<tr class=\"$bg\">\n";
			echo "<td><a href=Devices-List.php?ina=bootimage&opa=$o&sta=".urlencode($r[0]).">$r[0]</a></td>\n";
			echo "<td>$tbar $r[1]</td></tr>\n";
		}
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Bootimages, <?=$srt?></td></tr>
</table>

</td></tr></table>
<p>
<?
}

//===================================================================
// List duplicate device and module serials
function DevDupSer($ina,$opa,$sta,$lim,$ord){

	global $link,$modgroup,$self,$srtlbl,$numlbl,$duplbl,$typlbl,$totlbl;
?>
<table class="full fixed"><tr><td class="helper">

<h2><?=$duplbl?> Device Serials</h2>

<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th colspan="2"><img src="img/16/abc.png"><br><?=$typlbl?></th>
<th><img src="img/16/key.png"><br>Serial#</th>
<th><img src="img/16/dev.png"><br>Devices</th>
</tr>
<?
	if($ord){
		$ocol = 'serial';
		$srt = "$srtlbl: Serial";
	}else{
		$ocol = 'cnt desc';
		$srt = "$srtlbl: $numlbl";
	}
	$query	= GenQuery('devices','g','serial;type,icon;cnt>1',$ocol,$lim,array('CHAR_LENGTH(serial)',$ina),array('>',$opa),array('2',$sta),array('AND'));
	$res = @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			echo "<tr class=\"$bg\"><th class=\"$bi\"><img src=\"img/dev/$r[3].png\" title=\"$r[2]\"></th><td>$r[2]</td><td>$r[0]</td><td>";
			echo Bar($r[1],0)." <a href=\"Devices-List.php?ina=serial&opa==&sta=".urlencode($r[0])."\">$r[1]</a></td></tr>\n";
		}
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$duplbl?> Serials, <?=$srt?></td></tr>
</table>

</td><td class="helper">

<h2><?=$duplbl?> Module Serials</h2>

<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th><img src="img/16/abc.png"><br><?=$typlbl?></th>
<th><img src="img/16/key.png"><br>Serial#</th>
<th><img src="img/16/cubs.png"><br>Modules</th>
</tr>
<?
	if($ord){
		$ocol = 'modules.serial';
		$srt = "$srtlbl: Serial";
	}else{
		$ocol = 'cnt desc';
		$srt = "$srtlbl: $numlbl";
	}
	$query	= GenQuery('modules','g','modules.serial;model,moddesc;cnt>1',$ocol,$lim,array('CHAR_LENGTH(modules.serial)',$ina),array('>',$opa),array('2',$sta),array('AND'),'LEFT JOIN devices USING (device)');
	$res = @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			echo "<tr class=\"$bg\"><td><b>$r[2]</b> $r[3]</td><td>$r[0]</td><td>";
			echo Bar($r[1],0)." <a href=\"Devices-Modules.php?ina=modules.serial&opa==&sta=".urlencode($r[0])."\">$r[1]</a></td></tr>\n";
		}
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$duplbl?> Serials, <?=$srt?></td></tr>
</table>

</td></tr></table>
<p>
<?
}

//===================================================================
// List duplicate device IPs
function DevDupIP($ina,$opa,$sta,$lim,$ord){

	global $link,$modgroup,$self,$srtlbl,$manlbl,$orilbl,$numlbl,$duplbl,$totlbl;
?>
<table class="full fixed"><tr><td class="helper">

<h2><?=$duplbl?> <?=$manlbl?> IPs</h2>

<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th><img src="img/16/net.png"><br>IP</th>
<th><img src="img/16/dev.png"><br>Devices</th>
</tr>
<?
	if($ord){
		$ocol = 'devip';
		$srt = "$srtlbl: Serial";
	}else{
		$ocol = 'cnt desc';
		$srt = "$srtlbl: $numlbl";
	}
	$query	= GenQuery('devices','g','devip;type,icon;cnt>1',$ocol,$lim,array('devip',$ina),array('>',$opa),array('0',$sta),array('AND'));
	$res = @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			echo "<tr class=\"$bg\"><td>".long2ip($r[0])."</td><td>".Bar($r[1],0);
			echo " <a href=\"Devices-List.php?ina=origip&opa==&sta=$r[0]\">$r[1]</a></td></tr>\n";
		}
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$duplbl?> IPs, <?=$srt?></td></tr>
</table>

</td><td class="helper">

<h2><?=$duplbl?> <?=$orilbl?> IPs</h2>

<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th><img src="img/16/net.png"><br>IP</th>
<th><img src="img/16/dev.png"><br>Devices</th>
</tr>
<?
	if($ord){
		$ocol = 'origip';
		$srt = "$srtlbl: Serial";
	}else{
		$ocol = 'cnt desc';
		$srt = "$srtlbl: $numlbl";
	}
	$query	= GenQuery('devices','g','origip;type,icon;cnt>1',$ocol,$lim,array('origip',$ina),array('>',$opa),array('0',$sta),array('AND'));
	$res = @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			echo "<tr class=\"$bg\"><td>".long2ip($r[0])."</td><td>".Bar($r[1],0);
			echo " <a href=\"Devices-List.php?ina=origip&opa==&sta=$r[0]\">$r[1]</a></td></tr>\n";
		}
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$duplbl?> IPs, <?=$srt?></td></tr>
</table>

</td></tr></table>
<p>
<?
}

//===================================================================
// List device types
function DevType($ina,$opa,$sta,$lim,$ord){

	global $link,$modgroup,$self,$typlbl,$srtlbl,$lstlbl,$srvlbl,$numlbl,$invlbl,$totlbl;
?>
<table class="full fixed"><tr><td class="helper">

<h2>Device <?=$typlbl?></h2>

<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th colspan="2" width="33%"><img src="img/16/abc.png"><br><?=$typlbl?></th>
<th><img src="img/16/dev.png"><br>Devices</th>
</tr>
<?
	if($ord){
		$ocol = 'type';
		$srt = "$srtlbl: $typlbl";
	}else{
		$ocol = 'cnt desc';
		$srt = "$srtlbl: $numlbl";
	}
	$query	= GenQuery('devices','g','type,devos,icon',$ocol,$lim,array($ina),array($opa),array($sta));
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$tbar	= Bar($r[3],0);
			$utyp	= urlencode($r[0]);
			echo "<tr class=\"$bg\"><th class=\"$bi\" width=\"10%\">\n";
			echo "<img src=\"img/dev/$r[2].png\" title=\"$r[0]\"></th><td>\n";
			if($r[1] == 'ESX'){
				echo "<a href=\"Reports-Modules.php?rep[]=vms&ina=type&opa==&sta=$utyp\" title=\"$invlbl\">$r[0]</a>\n";
			}elseif($r[1] == 'Printer'){
				echo "<a href=\"Reports-Modules.php?rep[]=prt&ina=type&opa==&sta=$utyp\" title=\"$invlbl\">$r[0]</a>\n";
			}else{
				echo "<a href=\"Reports-Modules.php?rep[]=inv&ina=type&opa==&sta=$utyp\" title=\"$invlbl\">$r[0]</a>\n";
			}

			echo "</td><td>$tbar <a href=\"Devices-List.php?ina=type&opa==&sta=$utyp\" title=\"Device $lstlbl\">$r[3]</a></td></tr>\n";
		}
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$typlbl?>, <?=$srt?></td></tr>
</table>

</td><td class="helper">

<h2>Device <?=$srvlbl?></h2>

<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th><img src="img/16/cog.png"><br><?=$srvlbl?></th>
<th><img src="img/16/dev.png"><br>Devices</th>
</tr>
<?
	if($ord){
		$ocol = 'services';
		$srt = "$srtlbl: $srvlbl";
	}else{
		$ocol = 'cnt desc';
		$srt = "$srtlbl: $numlbl";
	}
	$query	= GenQuery('devices','g','services',$ocol,$lim,array($ina),array($opa),array($sta),'',$join);
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$o = "=";
			if(!$r[0]){$r[0]="^$";$o="regexp";}
			echo "<tr class=\"$bg\"><td>".Syssrv($r[0])." ($r[0])</td>\n";
			echo "<td>".Bar($r[1],0)." <a href=Devices-List.php?ina=services&opa=$o&sta=".urlencode($r[0]).">$r[1]</a></td></tr>\n";
		}
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$srvlbl?>, <?=$srt?></td></tr>
</table>

</td></tr></table>
<p>
<?
}

//===================================================================
// List VTP info
function DevVTP($ina,$opa,$sta,$lim,$ord){

	global $link,$modgroup,$self,$srtlbl,$numlbl,$lstlbl,$totlbl;

?>
<table class="full fixed"><tr><td class="helper">

<h2>VTP Domain</h2>

<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th width="33%"><img src="img/16/vlan.png"><br>VTP Domain</th>
<th><img src="img/16/dev.png"><br>Devices</th>
</tr>
<?
	if($ord){
		$ocol = 'vtpdomain';
		$srt = "$srtlbl: VTP Domain";
	}else{
		$ocol = 'cnt desc';
		$srt = "$srtlbl: $numlbl";
	}
	$query	= GenQuery('devices','g','vtpdomain',$ocol,$lim,array($ina),array($opa),array($sta));
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$op = "=";
			if(!$r[0]){$r[0] = "^$"; $op = "regexp";}
			$tbar = Bar($r[1],0);
			echo "<tr class=\"$bg\">\n";
			echo "<td><b>$r[0]</b></td><td>$tbar <a href=\"Devices-List.php?ina=vtpdomain&opa=$op&sta=".urlencode($r[0])."\" title=\"Device $lstlbl\">$r[1]</a></td></tr>\n";
		}
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> VTP Domains, <?=$srt?></td></tr>
</table>

</td><td class="helper">

<h2>VTP Mode</h2>

<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th width="33%"><img src="img/16/vlan.png"><br>VTP Mode</th>
<th><img src="img/16/dev.png"><br>Devices</th>
</tr>
<?
	if($ord){
		$ocol = 'vtpmode';
		$srt = "$srtlbl: VTP Modes";
	}else{
		$ocol = 'cnt desc';
		$srt = "$srtlbl: $numlbl";
	}
	$query	= GenQuery('devices','g','vtpmode',$ocol,$lim,array($ina),array($opa),array($sta));
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$tbar = Bar($r[1],0);
			echo "<tr class=\"$bg\">\n";
			echo "<td><b>".VTPmod($r[0])."</b></td><td>$tbar <a href=\"Devices-List.php?ina=vtpmode&opa==&sta=$r[0]\">$r[1]</a></td></tr>\n";
		}
	}
	?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> VTP Modes, <?=$srt?></td></tr>
</table>

</td></tr></table>
<p>
<?
}

//===================================================================
// Show Incident Acknowledge Stats
function IncAck($ina,$opa,$sta,$lim,$ord){

	global $link,$modgroup,$self,$srtlbl,$usrlbl,$acklbl,$numlbl,$timlbl,$tim,$avglbl;
?>

<h2>Incident <?=$acklbl?></h2>

<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th colspan="2"><img src="img/16/ucfg.png"><br><?=$usrlbl?></th>
<th><img src="img/16/bomb.png"><br><?=$numlbl?></th>
<th><img src="img/16/clock.png"><br><?=$avglbl?> <?=$acklbl?> <?=$timlbl?></th>
</tr>
<?
	if($ord){
		$ocol = 'user';
		$srt = "$srtlbl: $usrlbl";
	}else{
		$ocol = 'avg desc';
		$srt = "$srtlbl: $numlbl";
	}
	$query	= GenQuery('incidents','a','user;(time - start)/3600',$ocol,$lim,array('time',$ina),array('>',$opa),array('0',$sta),array('AND'),'LEFT JOIN devices USING (device)');
	$res = @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			echo "<tr class=\"$bg\"><th class=\"$bi\" width=\"50\">\n";
			echo Smilie($r[0])."</th><td>$r[0]</td><td>".Bar($r[1],0)." $r[1]</td><td>".Bar($r[2],24)." ".intval($r[2]/24)." $tim[d] ".intval($r[2]%24)." $tim[h]</td></tr>\n";
		}
	}else{
		echo @DbError($link);
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$grplbl?>, <?=$srt?></td></tr>
</table>
<p>
<?
}

//===================================================================
// Show Incident History
function IncHist($ina,$opa,$sta,$lim,$ord,$opt){

	global $link,$modgroup,$self,$igrp,$hislbl,$tim,$durlbl;

	$dat  = getdate();
	$year = $dat['year'];
	if($lim == 20){$year -= 1;}
	elseif($lim == 50){$year -= 2;}
	elseif($lim == 100){$year -= 3;}
?>
<h2>Incident <?=$hislbl?></h2><p>

<table class="content"><tr class="<?=$modgroup[$self]?>2">
<tr class="<?=$modgroup[$self]?>2"><th></th>
<?
	$query	= GenQuery('incidents','s','incidents.*','','',array($ina),array($opa),array($sta),'', 'LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	if($res){
		$tinc = 0;
		$insta	= array();
		$inusr  = array();
		while( $r = @DbFetchRow($res) ){
			$indev[$r[0]] = $r[2];
			$insta[$r[0]] = $r[4];
			$ingrp[$r[0]] = $r[8];
			if($r[5]){
				$inend[$r[0]] = $r[5];
			}else{
				$inend[$r[0]] = $dat[0];
			}
			$tinc++;
		}
		@DbFreeResult($res);
	}else{
		print @DbError($link);
	}

	for($d=1;$d < 32;$d++){
		echo "<th>$d</th>";
	}
	$row = 0;
	$prevm = "";
	for($t = strtotime("1/1/$year");$t < $dat[0];$t += 86400){
		$then = getdate($t);
		if($prevm != $then['month']){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			echo "</tr>\n<tr class=\"$bg\"><th class=\"$modgroup[$self]2\">". substr($then[month],0,3)." $then[year]</th>";
		}
		foreach($insta as $id => $st){
			if($st < ($t + 86400) ){
				if($inend[$id] < $t){
					unset($insta[$id]);				# Speeds up this nasty loop towards the end!
					unset($inend[$id]);
				}else{
					$curi[$t][] = $id;
				}
			}
		}
		if($then['wday'] == 0 or $then['wday'] == 6){
			$cl = "red";
		}else{
			$cl = "blu";
		}
		echo "<th class=\"$cl\">";
		if( isset($curi[$t]) ){
			sort($curi[$t]);
			if($opt){
				$ni = 0;
				foreach($curi[$t] as $id){
					$ni++;
					$tit  = $indev[$id] . ": " .$igrp[$ingrp[$id]] . ", $durlbl: ".date($_SESSION['date'],$insta[$id])." - ".date($_SESSION['date'],$inend[$id]);
					echo "<a href=Monitoring-Incidents.php?id=$id>";
					echo "<img src=\"img/16/".IncImg($ingrp[$id]).".png\" title=\"$tit\">";
					if ($ni == 4){echo "<br>";$ni = 0;}
					echo "</a>";
				}
			}else{
				$ninc = count($curi[$t]);
				if($ninc == 1){
					$ico = "fobl";
				}elseif($ninc < 3){
					$ico = "fovi";
				}elseif($ninc < 5){
					$ico = "foye";
				}elseif($ninc < 10){
					$ico = "foor";
				}else{
					$ico = "ford";
				}
				echo "<img src=\"img/16/$ico.png\" title=\"$then[weekday]: $ninc Incidents $totlbl\"></a>";
			}
		}else{
			echo substr($then['weekday'],0,1);
		}
		echo "</td>";
		$prevm = $then['month'];
	}
	echo "</table><p>\n";
}

//===================================================================
// Show Incident Groups
function IncGroup($ina,$opa,$sta,$lim,$ord){

	global $link,$modgroup,$self,$grplbl,$srtlbl,$dislbl,$numlbl,$igrp,$tim,$totlbl,$avglbl,$durlbl;
?>
<h2>Incident <?=$grplbl?></h2>

<table class="full fixed"><tr><td class="helper">

<h2><?=$grplbl?> <?=$dislbl?></h2>
<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th colspan="2" width="33%"><img src="img/16/ugrp.png"><br><?=$grplbl?></th>
<th><img src="img/16/bomb.png"><br><?=$numlbl?></th>
</tr>
<?
	if($ord){
		$ocol = 'grp';
		$srt = "$srtlbl: $grplbl";
	}else{
		$ocol = 'cnt desc';
		$srt = "$srtlbl: $numlbl";
	}
	if($ina == "class"){$ina = "grp";}
	$query	= GenQuery('incidents','g','grp',$ocol,$lim,array($ina),array($opa),array($sta),'','LEFT JOIN devices USING (device)');
	$res = @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			echo "<tr class=\"$bg\"><th class=\"$bi\" width=\"50\">\n";
			echo "<img src=\"img/16/".IncImg($r[0]).".png\"></th>\n<td><a href=\"Monitoring-Incidents.php?grp=$r[0]\">";
			echo $igrp[$r[0]]."</a></td><td>".Bar($r[1],'lvl100','mi')." $r[1]</td></tr>\n";
		}
	}else{
		echo @DbError($link);
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?>, <?=$srt?></td></tr>
</table>

</td><td class="helper">

<h2><?=$avglbl?> <?=$durlbl?></h2>
<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th colspan="2" width="33%"><img src="img/16/ugrp.png"><br><?=$grplbl?></th>
<th><img src="img/16/clock.png"><br><?=$avglbl?> <?=$durlbl?></th>
</tr>
<?
	if($ord){
		$ocol = 'grp';
	}else{
		$ocol = 'avg desc';
	}
	$query	= GenQuery('incidents','a','grp;(end - start)/60',$ocol,$lim,array('end',$ina),array('>',$opa),array('0',$sta),array('AND'),'LEFT JOIN devices USING (device)');
	$res = @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			echo "<tr class=\"$bg\"><th class=\"$bi\" width=\"50\">\n";
			echo "<img src=\"img/16/".IncImg($r[0]).".png\"></a></th>\n<td><a href=\"Monitoring-Incidents.php?grp=$r[0]\">";
			echo $igrp[$r[0]]."</a></td><td>".Bar($r[2],15,'mi')." ".intval($r[2]/60)." $tim[h] ".($r[2]%60)." $tim[i]</td></tr>\n";
		}
	}else{
		echo @DbError($link);
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?>, <?=$srt?></td></tr>
</table>

</td></tr></table>
<p>
<?
}

//===================================================================
// Show Incident Distribution 
function IncDist($ina,$opa,$sta,$lim,$ord){

	global $link,$modgroup,$self,$srtlbl,$conlbl,$srclbl,$mbak,$mico,$place,$locsep,$loclbl,$dislbl,$numlbl;
?>
<h2>Incident <?=$dislbl?></h2>

<table class="full fixed"><tr><td class="helper">

<h2><?=$srclbl?></h2>
<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th colspan="2"><img src="img/16/dev.png"><br><?=$srclbl?></th>
<th width="50%"><img src="img/16/bomb.png"><br><?=$numlbl?></th>
</tr>
<?
	if($ord){
		$ocol = 'name';
		$srt = "$srtlbl: $srclbl";
	}else{
		$ocol = 'cnt desc';
		$srt = "$srtlbl: $numlbl";
	}
	$areg	= array();
	$acty	= array();
	$abld	= array();
	$ireg	= array();
	$icty	= array();
	$ibld	= array();
	$query	= GenQuery('incidents','g','name;location,contact,level',$ocol,$lim,array($ina),array($opa),array($sta),'','LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$l = explode($locsep, $r[2]);
			$ireg["$l[0]"] += $r[1];
			$icty["$l[0]$locsep$l[1]"] += $r[1];
			$ibld["$l[0]$locsep$l[1]$locsep$l[2]"] += $r[1];
			echo "<tr class=\"$bg\"><th class=\"".$mbak[$r[4]]."\" width=\"50\">\n";
			echo "<a href=\"Monitoring-Setup.php?ina=name&opa=%3D&sta=".urlencode($r[0])."\">\n";
			echo "<img src=\"img/32/".$mico[$r[4]].".png\" title=\"$conlbl: $r[3], $loclbl: $l[0] $l[1] $l[2]\"></a></th>\n";
			echo "<td><a href=\"Monitoring-Setup.php?ina=name&opa=%3D&sta=".urlencode($r[0])."\">$r[0]</a></td>";
			echo "<td>".Bar($r[1],10)." $r[1]</td></tr>\n";
		}
	}else{
		echo @DbError($link);
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Incidents, <?=$srt?></td></tr>
</table>

</td><td class="helper">

<h2><?=$place['r']?></h2>
<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th colspan="2"><img src="img/16/glob.png"><br><?=$loclbl?></th>
<th width="50%"><img src="img/16/bomb.png"><br><?=$numlbl?></th>
</tr>
<?
	if($ord){
		ksort($ireg);
		ksort($icty);
		ksort($ibld);
	}else{
		arsort($ireg);
		arsort($icty);
		arsort($ibld);
	}
	$row = 0;
	foreach ($ireg as $r => $ni){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		echo "<tr class=\"$bg\"><th class=\"$bi\" width=\"50\"><img src=\"img/regg.png\" title=\"$place[r]\"></th>\n";
		echo "<td><a href=\"Monitoring-Setup.php?ina=location&opa=regexp&sta=^".urlencode($r)."$locsep\">$r</a></td><td>".Bar($ni,10)." $ni</td></tr>\n";
	}
?>
</table>
<p>
<h2><?=$place['c']?></h2>
<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th colspan="2"><img src="img/16/glob.png"><br><?=$loclbl?></th>
<th width="50%"><img src="img/16/bomb.png"><br><?=$numlbl?></th>
</tr>
<?
	foreach ($icty as $c => $ni){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		echo "<tr class=\"$bg\"><th class=\"$bi\" width=\"50\"><img src=\"img/cityg.png\" title=\"$place[c]\"></th>\n";
		echo "<td><a href=\"Monitoring-Setup.php?ina=location&opa=regexp&sta=^".urlencode($c)."$locsep\">".str_replace(";"," ",$c)."</a></td><td>".Bar($ni,10)." $ni</td></tr>\n";
	}
?>
</table>
<p>
<h2><?=$place['b']?></h2>
<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th colspan="2"><img src="img/16/glob.png"><br><?=$loclbl?></th>
<th width="50%"><img src="img/16/bomb.png"><br><?=$numlbl?></th>
</tr>
<?
	foreach ($ibld as $b => $ni){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		echo "<tr class=\"$bg\"><th class=\"$bi\" width=\"50\"><img src=\"img/blds.png\" title=\"$place[b]\"></th>\n";
		echo "<td><a href=\"Monitoring-Setup.php?ina=location&opa=regexp&sta=^".urlencode($b)."\">".str_replace(";"," ",$b)."</a></td><td>".Bar($ni,10)." $ni</td></tr>\n";
	}
?>
</table>

</td></tr></table>
<p>
<?
}

//===================================================================
// Show PoE "Charts"
function IntPoE($ina,$opa,$sta,$lim,$ord){

	global $link,$modgroup,$self,$loclbl,$locsep,$conlbl,$srtlbl,$numlbl,$totlbl,$avglbl;
?>
<table class="full fixed"><tr><td class="helper">

<h2><?=$totlbl?> PoE / Device</h2>

<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th width="33%" colspan="2"><img src="img/16/dev.png"><br>Device</th>
<th><img src="img/16/port.png"><br>PoE IF</th>
<th><img src="img/16/batt.png" title="Red threshold 1kW"><br>PoE <?=$totlbl?></th>
</tr>
<?
	if($ord){
		$ocol = 'device';
		$srt = "$srtlbl: Device";
	}else{
		$ocol = 'sum desc';
		$srt = "$srtlbl: $totlbl PoE";
	}
	$query	= GenQuery('interfaces','m','device,contact,location,icon;poe',$ocol,$lim,array('poe',$ina),array('>',$opa),array('0',$sta),array('AND'),'LEFT JOIN devices USING (device)');
	$res = @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$l = explode($locsep, $r[2]);
			echo "<tr class=\"$bg\"><th class=\"$bi\"><img src=\"img/dev/$r[3].png\" title=\"$conlbl: $r[1], $loclbl: $l[0] $l[1] $l[2]\"></a></th>\n";
			echo "<td><a href=\"Devices-Status.php?dev=".urlencode($r[0])."\">$r[0]</a></td>\n";
			echo "<th>$r[4]</th><td>".Bar($r[5]/1000,500)." ".round($r[5]/1000,2)." W</td></tr>\n";
		}
	}else{
		echo @DbError($link);
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> PoE Devices, <?=$srt?></td></tr>
</table>

</td><td class="helper">

<h2>PoE <?=$avglbl?> / Interface</h2>

<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th width="33%" colspan="2"><img src="img/16/dev.png"><br>Devices</th>
<th><img src="img/16/port.png"><br>PoE IF</th>
<th><img src="img/16/batt.png"><br>PoE <?=$avglbl?></th>
</tr>
<?
	if($ord){
		$ocol = 'device';
		$srt = "$srtlbl: Device";
	}else{
		$ocol = 'avg desc';
		$srt = "$srtlbl: $avglbl PoE";
	}
	$query	= GenQuery('interfaces','a','device,contact,location,icon;poe',$ocol,$lim,array('poe',$ina),array('>',$opa),array('0',$sta),array('AND'),'LEFT JOIN devices USING (device)');
	$res = @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$l = explode($locsep, $r[2]);
			echo "<tr class=\"$bg\"><th class=\"$bi\"><img src=\"img/dev/$r[3].png\" title=\"$conlbl: $r[1], $loclbl: $l[0] $l[1] $l[2]\"></a></th>\n";
			echo "<td><a href=\"Devices-Status.php?dev=".urlencode($r[0])."\">$r[0]</a></td>\n";
			echo "<th>$r[4]</th><td>".Bar($r[5]/100,70)." ".round($r[5]/1000,2)." W</td></tr>\n";
		}
	}else{
		echo @DbError($link);
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> PoE Devices, <?=$srt?></td></tr>
</table>

</td></tr></table>
<p>
<?
}

//===================================================================
// Active Interfaces based on inoctets
function IntActiv($ina,$opa,$sta,$lim,$ord){

	global $link,$opt,$modgroup,$self,$optlbl,$typlbl,$alllbl,$loclbl,$locsep,$conlbl,$fullbl,$emplbl,$totlbl,$stco;
?>

<table class="full fixed"><tr><td class="helper">

<h2><?=(($verb1)?"$fullbl Devices":"Devices $fullbl")?></h2>

<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th colspan="2" width="25%"><img src="img/16/dev.png"><br>Device</th>
<th><img src="img/16/port.png"><br><?=$totlbl?> IF</th>
<th><img src="img/16/nods.png"><br>IF <?=$stco['100']?></th>
</tr>
<?
	if($opt){
		$query	= GenQuery('interfaces','g','device;sum(inoct>"71") AS actif,round(sum(inoct>"71")/count(*)*100) AS usedif,contact,location,icon','usedif desc',$lim,array('iftype','services',$ina),array('regexp','COL &',$opa),array('6|117','2',$sta),array('AND','AND'),'LEFT JOIN devices USING (device)');
	}else{
		$query	= GenQuery('interfaces','g','device;sum(inoct>"71") AS actif,round(sum(inoct>"71")/count(*)*100) AS usedif,contact,location,icon','usedif desc',$lim,array($ina),array($opa),array($sta),'','LEFT JOIN devices USING (device)');
	}
	$res = @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$l = explode($locsep, $r[5]);
			$ico = ($r[6])?"dev/$r[6]":"32/bbox";
			echo "<tr class=\"$bg\"><th class=\"$bi\"><img src=\"img/$ico.png\" title=\"$conlbl: $r[4], $loclbl: $l[0] $l[1] $l[2]\"></a></th>\n";
			echo "</th><td><a href=\"Devices-Status.php?dev=".urlencode($r[0])."&shp=on\">$r[0]</a></td>\n";
			echo "<th>$r[1]</th><td>".Bar($r[3],48)." $r[3] % ($r[2])</td></tr>\n";
		}
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Devices, <?=($opt)?"$optlbl: Bridge & IF $typlbl = Ethernet":"IF $typlbl = $alllbl"?></td></tr>
</table>

</td><td class="helper">

<h2><?=(($verb1)?"$emplbl Devices":"Devices $emplbl")?></h2>
<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th colspan="2" width="25%"><img src="img/16/dev.png"><br>Device</th>
<th><img src="img/16/port.png"><br><?=$totlbl?> IF</th>
<th><img src="img/16/nods.png"><br>IF <?=$stco['100']?></th>
</tr>
<?
	if($opt){
		$query	= GenQuery('interfaces','g','device;sum(inoct>"71") AS actif,round(sum(inoct>"71")/count(*)*100) AS usedif,contact,location,icon','usedif',$lim,array('iftype','services',$ina),array('regexp','COL &',$opa),array('6|117','2',$sta),array('AND','AND'),'LEFT JOIN devices USING (device)');
	}else{
		$query	= GenQuery('interfaces','g','device;sum(inoct>"71") AS actif,round(sum(inoct>"71")/count(*)*100) AS usedif,contact,location,icon','usedif',$lim,array($ina),array($opa),array($sta),'','LEFT JOIN devices USING (device)');
	}
	$res = @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$l = explode($locsep, $r[5]);
			$ico = ($r[6])?"dev/$r[6]":"32/bbox";
			echo "<tr class=\"$bg\"><th class=\"$bi\"><img src=\"img/$ico.png\" title=\"$conlbl: $r[4], $loclbl: $l[0] $l[1] $l[2]\"></a></th>\n";
			echo "<td><a href=\"Devices-Status.php?dev=".urlencode($r[0])."&shp=on\">$r[0]</a></td>\n";
			echo "<th>$r[1]</th><td>".Bar($r[3],48)." $r[3] % ($r[2])</td></tr>\n";
		}
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Devices, <?=($opt)?"$optlbl: Bridge & IF $typlbl = Ethernet":"IF $typlbl = $alllbl"?></td></tr>
</table>

</td></tr></table>
<p>
<?
}

//===================================================================
// Disabled Interfaces
function IntDis($ina,$opa,$sta,$lim,$ord){

	global $link,$modgroup,$self,$srtlbl,$lstlbl,$loclbl,$locsep,$conlbl,$totlbl;

?>
<h2>Disabled Interfaces</h2>

<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th colspan="2" width="20%"><img src="img/16/dev.png"><br>Device</th>
<th><img src="img/16/glob.png"><br>IP</th>
<th><img src="img/16/bdis.png"><br>Disabled IF <?=$lstlbl?></th>
</tr>
<?
	if($ord){
		$ocol = 'devip';
		$srt = "$srtlbl: IP";
	}else{
		$ocol = 'device';
		$srt = "$srtlbl: Device";
	}
	$query	= GenQuery('interfaces','s','device,ifname,iftype,alias,devip,cliport,contact,location,icon',$ocol,$lim,array('ifstat',$ina),array('=',$opa),array('0',$sta),array('AND'),'LEFT JOIN devices USING (device)');
	$res = @DbQuery($query,$link);
	if($res){
		$row = 0;
		$nif = 0;
		while( $r = @DbFetchRow($res) ){
			list($ifimg,$iftyp) = Iftype($r[2]);
			$curi = "<img src=\"img/$ifimg\" title=\"$iftyp $r[3]\">$r[1] ";
			if($r[0] == $prev){
				echo $curi;
				$nif++;
			}else{
				$prev = $r[0];
				if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
				$row++;
				$l  = explode($locsep, $r[7]);
				echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\">\n";
				echo "<th class=\"$bi\"><img src=\"img/dev/$r[8].png\" title=\"$conlbl: $r[6], $loclbl: $l[0] $l[1] $l[2]\"></th>\n";
				echo "<td><a href=\"Devices-Status.php?dev=".urlencode($r[0])."\">$r[0]</a></td>\n";
				echo "<td>".Devcli(long2ip($r[4]),$r[5])."</td><td>$curi ";
				$nif++;
			}
		}
		echo "</td></tr></table>\n";
	}
?>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$nif?> Disabled IF, <?=$row?> Devices</td></tr>
</table>
<p>
<?
}

//===================================================================
// Interface Traffic
function IntTrf($ina,$opa,$sta,$lim,$ord){

?>
<table class="full fixed"><tr><td class="helper">

<?IntChart(0,0,$ina,$opa,$sta,$lim,$ord);?>

</td><td class="helper">

<?IntChart(0,1,$ina,$opa,$sta,$lim,$ord);?>

</td></tr></table>
<p>
<?
}

//===================================================================
// Interface Errors
function IntErr($ina,$opa,$sta,$lim,$ord){

?>
<table class="full fixed"><tr><td class="helper">

<?IntChart(1,0,$ina,$opa,$sta,$lim,$ord);?>

</td><td class="helper">

<?IntChart(1,1,$ina,$opa,$sta,$lim,$ord);?>

</td></tr></table>
<p>
<?
}

//===================================================================
// Interface Charts
// *800 at the end to avoid potential overflow due to very big numbers (might help?)
function IntChart($mode,$dir,$ina,$opa,$sta,$lim,$ord){

	global $link,$modgroup,$self,$trflbl,$errlbl,$inblbl,$oublbl,$rrdstep,$srtlbl,$loclbl,$locsep,$conlbl,$tim;

	if($mode){
		if($ord){
			$ocol = "aval desc";
			$srt = "$srtlbl: $errlbl/$trflbl";
		}else{
			$ocol = "rval desc";
			$srt = "$srtlbl: $errlbl %";
		}
		if($dir){
			$ti = "$errlbl $inblbl";
			$di = 'brup';
			$qy = GenQuery('interfaces','s',"device,contact,location,icon,ifname,speed,iftype,comment,alias,dinerr as aval,dinerr/dinoct as rval",$ocol,$lim,array('iftype','dinerr',$ina),array('!=','>',$opa),array('71',0,$sta),array('AND','AND'),'LEFT JOIN devices USING (device)');
		}else{
			$ti = "$errlbl $oublbl";
			$di = 'brdn';
			$qy = GenQuery('interfaces','s',"device,contact,location,icon,ifname,speed,iftype,comment,alias,douterr as aval,douterr/doutoct as rval",$ocol,$lim,array('iftype','douterr',$ina),array('!=','>',$opa),array('71',0,$sta),array('AND','AND'),'LEFT JOIN devices USING (device)');
		}
	}else{
		if($ord){
			$ocol = "aval desc";
			$srt = "$srtlbl: $trflbl";
		}else{
			$ocol = "rval desc";
			$srt = "$srtlbl: $trflbl %";
		}
		$un = "%";
		if($dir){
			$ti = "$trflbl $inblbl";
			$di = 'bbup';
			$qy = GenQuery('interfaces','s',"device,contact,location,icon,ifname,speed,iftype,comment,alias,dinoct as aval,round(dinoct/$rrdstep/speed*800,2) as rval",$ocol,$lim,array('speed',$ina),array('>',$opa),array('0',$sta),array('AND'),'LEFT JOIN devices USING (device)');
		}else{
			$ti = "$trflbl $oublbl";
			$di = 'bbdn';
			$qy = GenQuery('interfaces','s',"device,contact,location,icon,ifname,speed,iftype,comment,alias,doutoct as aval,round(doutoct/$rrdstep/speed*800,2) as rval",$ocol,$lim,array('speed',$ina),array('>',$opa),array('0',$sta),array('AND'),'LEFT JOIN devices USING (device)');
		}
	}
?>
<h2><?=$ti?></h2>
<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th colspan="2" width="25%"><img src="img/16/dev.png"><br>Device</th>
<th><img src="img/16/port.png"><br>IF</th>
<th colspan="2"><img src="img/16/<?=$di?>.png"><br><?=$ml?> (<?=$rrdstep/60?> <?=$tim['i']?>)</th>
</tr>
<?
	$res	= @DbQuery($qy,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$ud = urlencode($r[0]);
			$ui = urlencode($r[4]);
			$l  = explode($locsep, $r[2]);
			list($ifimg,$iftyp) = Iftype($r[6]);
			if($_SESSION['gsiz']){
				$gr = "<img src=\"inc/drawrrd.php?dv=$ud&if%5B%5D=$ui&s=".(($_SESSION['gsiz'] == 4)?2:1).(($mode)?"&t=err&o=1":"&t=trf&o=$r[5]")."\" title=\"".DecFix($r[9])."\">";
			}else{
				$gr = DecFix($r[9]);
			}
			if($mode){
				$bar = Bar($r[9],10).DecFix($r[9]);
			}else{
				$bar = Bar($r[10],45).DecFix($r[10]);
			}
			echo "<tr class=\"$bg\"><th class=\"$bi\"><img src=\"img/dev/$r[3].png\" title=\"$conlbl: $r[1], $loclbl: $l[0] $l[1] $l[2]\"></a></th>\n";
			echo "<td nowrap><a href=\"Devices-Status.php?dev=$ud\">$r[0]</a></b></td>\n";
			echo "<td><img src=\"img/$ifimg\" title=\"$iftyp ".DecFix($r[5])." $r[7] $r[8]\"><a href=\"Nodes-List.php?ina=device&opa==&sta=$ud&cop=AND&inb=ifname&opb==&stb=$ui\">$r[4]</a></td>\n";
			echo "<th><a href=\"Devices-Graph.php?dv=$ud&if%5B%5D=$ui\">$gr</a></th>\n";
			echo "<td>$bar$un</td></tr>\n";
		}
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> IF, <?=$srt?></td></tr>
</table>
<p>
<?
}

//===================================================================
// Link Status Errors
function LnkErr($ina,$opa,$sta,$lim,$ord){

	global $link,$modgroup,$self,$stalbl,$errlbl,$neblbl,$spdlbl,$typlbl;

	if($ord){
		$ocol = 'neighbor';
		$srt = "$srtlbl: $neblbl";
	}else{
		$ocol = 'device';
		$srt = "$srtlbl: Device";
	}
	$query	= GenQuery('links as l1 ','s','l1.device,l1.ifname,l1.neighbor,l1.nbrifname,l1.bandwidth,l2.bandwidth as l2bw',$ocol,$lim,array('l1.bandwidth',$ina),array('COL !=',$opa),array('l2.bandwidth',$sta),array('AND'),'JOIN links as l2 on (l1.device = l2.neighbor and l1.ifname = l2.nbrifname) LEFT JOIN devices on (l1.device = devices.device)');
	$res	= @DbQuery($query,$link);
	if( DbNumRows($res) ){
?>
<h2>Link <?=$spdlbl?> <?=$errlbl?></h2>
<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th><img src="img/16/dev.png"><br>Device</th>
<th><img src="img/16/port.png"><br>IF</th>
<th width="50"><img src="img/spd.png"><br><?=$spdlbl?></th>
<th><img src="img/16/dev.png"><br><?=$neblbl?></th>
<th><img src="img/16/port.png"><br>IF</th>
<th width="50"><img src="img/spd.png"><br><?=$spdlbl?></th>
</tr>
<?
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			echo "<tr class=\"$bg\"><td><a href=\"Devices-Status.php?dev=".urlencode($r[0])."\">$r[0]</a></td>\n";
			echo "<td>$r[1]</td><th class=\"$bi\">".DecFix($r[4])."</th><td><a href=\"Devices-Status.php?dev=".urlencode($r[2])."\">$r[2]</a></td><td>$r[3]</td><th class=\"$bi\">".DecFix($r[5])."</th></tr>\n";
		}
		echo "</table><table class=\"content\" ><tr class=\"$modgroup[$self]2\"><td>$row $spdlbl $errlbl, $srt</td></tr></table><br><p>";
	}

	if($ord){
		$ocol = 'neighbor';
		$srt = "$srtlbl: $neblbl";
	}else{
		$ocol = 'device';
		$srt = "$srtlbl: Device";
	}
	$query	= GenQuery('links as l1 ','s','l1.device,l1.ifname,l1.neighbor,l1.nbrifname,l1.nbrduplex,l2.nbrduplex as l2dup',$ocol,$lim,array('l1.nbrduplex',$ina),array('COL !=',$opa),array('l2.nbrduplex',$sta),array('AND'),'JOIN links as l2 on (l1.device = l2.neighbor and l1.ifname = l2.nbrifname) LEFT JOIN devices on (l1.device = devices.device)');
	$res	= @DbQuery($query,$link);
	if( DbNumRows($res) ){
?>
<h2>Link Duplex <?=$errlbl?></h2>
<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th><img src="img/16/dev.png"><br>Device</th>
<th><img src="img/16/port.png"><br>IF</th>
<th width="50"><img src="img/dpx.png"><br>Duplex</th>
<th><img src="img/16/dev.png"><br><?=$neblbl?></th>
<th><img src="img/16/port.png"><br>IF</th>
<th width="50"><img src="img/dpx.png"><br>Duplex</th>
</tr>
<?
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			echo "<tr class=\"$bg\"><td><a href=\"Devices-Status.php?dev=".urlencode($r[0])."\">$r[0]</a></td>\n";
			echo "<td>$r[1]</td><th class=\"$bi\">$r[4]</th><td><a href=\"Devices-Status.php?dev=".urlencode($r[2])."\">$r[2]</a></td><td>$r[3]</td><th class=\"$bi\">$r[5]</th></tr>\n";
		}
		echo "</table><table class=\"content\" ><tr class=\"$modgroup[$self]2\"><td>$row Duplex $errlbl, $srt</td></tr></table><br><p>";
	}

	if($ord){
		$ocol = 'neighbor';
		$srt = "$srtlbl: $neblbl";
	}else{
		$ocol = 'device';
		$srt = "$srtlbl: Device";
	}
	$query	= GenQuery('links as l1 ','s','l1.device,l1.ifname,l1.neighbor,l1.nbrifname,l1.nbrvlanid,l2.nbrvlanid as l2dup',$ocol,$lim,array('l1.nbrvlanid',$ina),array('COL !=',$opa),array('l2.nbrvlanid',$sta),array('AND'),'JOIN links as l2 on (l1.device = l2.neighbor and l1.ifname = l2.nbrifname) LEFT JOIN devices on (l1.device = devices.device)');
	$res	= @DbQuery($query,$link);
	if( DbNumRows($res) ){
?>
<h2>Link Vlan <?=$errlbl?></h2>
<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th><img src="img/16/dev.png"><br>Device</th>
<th><img src="img/16/port.png"><br>IF</th>
<th width="50"><img src="img/16/vlan.png"><br>Vlan</th>
<th><img src="img/16/dev.png"><br><?=$neblbl?></th>
<th><img src="img/16/port.png"><br>IF</th>
<th width="50"><img src="img/16/vlan.png"><br>Vlan</th>
</tr>
<?
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			echo "<tr class=\"$bg\"><td><a href=\"Devices-Status.php?dev=".urlencode($r[0])."\">$r[0]</a></td>\n";
			echo "<td>$r[1]</td><th class=\"$bi\">$r[4]</th><td><a href=\"Devices-Status.php?dev=".urlencode($r[2])."\">$r[2]</a></td><td>$r[3]</td><th class=\"$bi\">$r[5]</th></tr>\n";
		}
		echo "</table><table class=\"content\" ><tr class=\"$modgroup[$self]2\"><td>$row Vlan $errlbl, $srt</td></tr></table><br><p>";
	}

	if($ord){
		$ocol = 'neighbor';
		$srt = "$srtlbl: $neblbl";
	}else{
		$ocol = 'device';
		$srt = "$srtlbl: Device";
	}
	$query	= GenQuery('links as l1 ','s','l1.device,l1.ifname,l1.neighbor,l1.nbrifname,l1.linktype,l2.linktype as l2dup',$ocol,$lim,array('l1.linktype',$ina),array('COL !=',$opa),array('l2.linktype',$sta),array('AND'),'JOIN links as l2 on (l1.device = l2.neighbor and l1.ifname = l2.nbrifname) LEFT JOIN devices on (l1.device = devices.device)');
	$res	= @DbQuery($query,$link);
	if( DbNumRows($res) ){
?>
<h2>Link <?=$typlbl?> <?=$errlbl?></h2>
<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th><img src="img/16/dev.png"><br>Device</th>
<th><img src="img/16/port.png"><br>IF</th>
<th width="50"><img src="img/16/abc.png"><br><?=$typlbl?></th>
<th><img src="img/16/dev.png"><br><?=$neblbl?></th>
<th><img src="img/16/port.png"><br>IF</th>
<th width="50"><img src="img/16/abc.png"><br><?=$typblbl?></th>
</tr>
<?
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			echo "<tr class=\"$bg\"><td><a href=\"Devices-Status.php?dev=".urlencode($r[0])."\">$r[0]</a></td>\n";
			echo "<td>$r[1]</td><th class=\"$bi\">$r[4]</th><td><a href=\"Devices-Status.php?dev=".urlencode($r[2])."\">$r[2]</a></td><td>$r[3]</td><th class=\"$bi\">$r[5]</th></tr>\n";
		}
		echo "</table><table class=\"content\" ><tr class=\"$modgroup[$self]2\"><td>$row $typlbl $errlbl, $srt</td></tr></table><br><p>";
	}
?>
</table>
<p>
<?
}

//===================================================================
// Module Distribution
function ModDist($ina,$opa,$sta,$lim,$ord){

	global $link,$modgroup,$self,$mdllbl,$dislbl,$deslbl,$typlbl,$totlbl;

?>
<h2>Module <?=$dislbl?></h2>

<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th width="20%"><img src="img/16/abc.png"><br><?=$mdllbl?></th>
<th width="20%"><img src="img/16/find.png"><br><?=$deslbl?></th>
<th><img src="img/16/dev.png"><br>Devices</th>
<th width="80"><img src="img/16//cubs.png"><br><?=$totlbl?></th>
</tr>
<?
	$query	= GenQuery('modules','g','model,moddesc,modules.device','','',array("model",$ina),array('not regexp',$opa),array('Printsupply|VM-ESX',$sta),array('AND'),'LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	if($res){
		$nmod = 0;
		$nummo	= array();
		while( $r = @DbFetchRow($res) ){
			$nummo["$r[0];;$r[1]"] += $r[3];
			$modev["$r[0];;$r[1]"][$r[2]] = $r[3];
			$nmod += $r[3];
		}
		@DbFreeResult($res);
	}else{
		print @DbError($link);
		die;
	}
	if($ord){
		ksort($nummo);
	}else{
		arsort($nummo);
	}
	$row = 0;
	foreach ($nummo as $key => $n){
		list($mdl, $desc) = explode(";;", $key);
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		$tbar = Bar($n,0,'mi');
		echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\">\n";
		echo "<th class=\"$bi\">\n";
		echo "<a href=Devices-Modules.php?ina=model&opa==&sta=".urlencode($mdl)."><b>$mdl</b></a></th>\n";
		echo "<td>$desc</td><td>";
		foreach ($modev["$mdl;;$desc"] as $dv => $ndv){
			echo "<a href=Devices-Status.php?dev=".urlencode($dv).">$dv</a>: <b>$ndv</b> ";
		}
		echo "</td>\n";
		echo "<td nowrap>$tbar $n</td></tr>\n";
		if($row == $lim){break;}
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$nmod?> Modules, <?=$row?>  <?=$typlbl?></td></tr>
</table>
<p>
<?
}

//===================================================================
// Module & Device Inventory
function ModInventory($ina,$opa,$sta,$lim,$ord){

	global $link,$modgroup,$srtlbl,$typlbl,$self,$invlbl,$serlbl;

?>
<h2><?=$invlbl?></h2>

<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th><img src="img/16/dev.png"><br>Device / Slot</th>
<th><img src="img/16/find.png"><br>Info</th>
<th><img src="img/16/key.png"><br><?=$serlbl?></th>
<th><img src="img/16/cinf.png"><br>HW</th>
<th><img src="img/16/cog.png"><br>FW</th>
<th><img src="img/16/cbox.png"><br>SW</th>
</tr>
<?
	if($ord){
		$ocol = "type";
		$srt = "$srtlbl: $typlbl";
	}else{
		$ocol = "device";
		$srt = "$srtlbl: Device";
	}
	$query	= GenQuery('devices','s','distinct device,type,serial,bootimage',$ocol,'',array('devos',$ina),array('not regexp',$opa),array('Printer|ESX',$sta),array('AND'));
	$res	= @DbQuery($query,$link);
	if($res){
		$dev = 0;
		$mod = 0;
		while( $r = @DbFetchRow($res) ){
			$dev++;
			if($dev % 2){$bg = "imga";}else{$bg = "imgb";}
			echo "<tr class=\"warn\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='warn'\">\n";
			echo "<th align=\"left\"><a href=\"Devices-Status.php?dev=".urlencode($r[0])."\"><b>$r[0]</b></a></th>\n";
			echo "<td >$r[1]</td><td >$r[2]</td><td>-</td><td>-</td><td >$r[3]</td></tr>\n";
			$mquery	= GenQuery('modules','s','*','modidx','',array('device'),array('='),array($r[0]));
			$mres	= @DbQuery($mquery,$link);
			if($mres){
				while( ($m = @DbFetchRow($mres)) ){
					if ($mod % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
					$mod++;
					echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\">\n";
					echo "<td align=\"right\">$m[1]</td><td><b>$m[2]</b> $m[3]</td><td>$m[4]</td><td>$m[5]</td><td>$m[6]</td><td>$m[7]</td></tr>\n";
				}
				@DbFreeResult($mres);
			}else{
				echo @DbError($link);
				die;
			}
		}
		@DbFreeResult($res);
	}else{
		echo @DbError($link);
		die;
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$dev?> Devices, <?=$mod?> Modules, <?=$srt?></td></tr>
</table>
<p>
<?
}

//===================================================================
// Printsupplies Inventory & Levels
function ModPrint($ina,$opa,$sta,$lim,$ord){

	global $link,$modgroup,$self,$srtlbl,$stalbl,$typlbl,$locsep;
?>
<h2>Printsupplies</h2>

<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th width="33%"  colspan="3"><img src="img/16/print.png"><br>Printer</th>
<th colspan="3"><img src="img/16/file.png"><br>Supplies</th>
</tr>
<?
	$nprt = 0;
	if($ord){
		$ocol = "moddesc";
		$srt = "$srtlbl: Supply $typlbl";
	}else{
		$ocol = "abs(hw)";
		$srt = "$srtlbl: $stalbl";
	}
	$query	= GenQuery('modules','s','modules.*,location,contact,icon',$ocol,$lim,array('model',$ina),array('=',$opa),array('Printsupply',$sta),array('AND'),'LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$l = explode($locsep, $r[9]);
			echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\"><th class=\"$bi\">\n";
			echo "<img src=\"img/dev/$r[11].png\"></th><th><a href=\"Devices-Status.php?dev=".urlencode($r[0])."\">$r[0]</a></th>\n";
			echo "<td><img src=\"img/16/user.png\" title=\"$conlbl\"> $r[10]<br><img src=\"img/16/home.png\" title=\"$loclbl\"> $l[0] $l[1] $l[2]</td>";
			echo "<th class=\"$bi\">".PrintSupply($r[1])."</th>\n";
			echo "<td>$r[3]</td><td>".Bar($r[5],-33)." $r[5]%</td></tr>\n";
		}
		@DbFreeResult($res);
	}else{
		echo @DbError($link);
		die;
	}
?>
</table>
<table class="content">
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Printer, <?=$srt?></td></tr>
</table>
<p>
<?
}

//===================================================================
// Virtualmachine Inventory
function ModVM($ina,$opa,$sta,$lim,$ord){

	global $link,$modgroup,$self,$srtlbl,$poplbl,$dislbl,$locsep;
?>
<h2>VM <?=$dislbl?></h2>

<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th width="50%" colspan="3"><img src="img/16/cog.png"><br>Hypervisor</th>
<th><img src="img/16/node.png"><br>VM <?=$poplbl?></th>
<th valign="bottom"><img src="img/16/cpu.png"><br>CPUs</th>
<th valign="bottom" title="<?=$memlbl?>"><img src="img/16/mem.png"><br>Mem</th>
</tr>
<?
	$nprt = 0;
	if($ord){
		$ocol = "mem";
		$srt = "$srtlbl: Mem";
	}else{
		$ocol = "cnt desc";
		$srt = "$srtlbl: $poplbl";
	}
	$query	= GenQuery('modules','g','device;sum(modules.serial) as cpu,sum(fw)/1000 as mem,location,contact,icon',$ocol,$lim,array('model',$ina),array('=',$opa),array('VM-ESX',$sta),array('AND'),'LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$l = explode($locsep, $r[4]);
			$ud = urlencode($r[0]);
			echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\"><th class=\"$bi\">\n";
			echo "<img src=\"img/dev/$r[6].png\"></th><td><a href=Devices-Status.php?dev=$ud>$r[0]</a></td>\n";
			echo "<td><img src=\"img/16/user.png\" title=\"$conlbl\"> $r[5]<br><img src=\"img/16/home.png\" title=\"$loclbl\"> $l[0] $l[1] $l[2]";
			echo "<td>".Bar($r[1],100)." <a href=\"Devices-Modules.php?ina=device&opa==&sta=$ud\">$r[1]</a></td>\n";
			echo "<td>".Bar($r[2])." $r[2]</td><td>".Bar($r[3])." $r[3]Gb</td></tr>\n";
		}
		@DbFreeResult($res);
	}else{
		echo @DbError($link);
		die;
	}
?>
</table>
<table class="content">
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Hypervisors, <?=$srt?></td></tr>
</table>
<p>
<?
}

//===================================================================
// Monitoring Availability
function MonAvail($ina,$opa,$sta,$lim,$ord){

	global $link,$modgroup,$self,$tgtlbl,$place,$locsep,$loclbl,$srtlbl,$conlbl,$avalbl,$totlbl;
?>
<h2><?=$avalbl?></h2>

<table class="full fixed"><tr><td class="helper">

<h2><?=$tgtlbl?></h2>

<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th colspan="2" width="20%"><img src="img/16/bino.png"><br><?=$tgtlbl?></th>
<th colspan="2" width="50%"><img src="img/16/bchk.png"><br><?=$avalbl?></th>
<?
	if($ord){
		$ocol = "name";
		$srt = "$srtlbl: $tgtlbl";
	}else{
		$ocol = "relav";
		$srt = "$srtlbl: $avalbl";
	}
	$areg	= array();
	$acty	= array();
	$abld	= array();
	$query	= GenQuery('monitoring','s','name,test,ok/(lost+ok)*100 as relav,location,contact,class,icon',$ocol,$lim,array('(ok','lost',$ina),array('COL >','COL >',$opa),array('0','0)',$sta),array('OR','AND'),'LEFT JOIN devices USING (device)'); # Horrible workaround to solve (x OR y) AND z precedence!
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( ($r = @DbFetchRow($res)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$l = explode($locsep, $r[3]);
			$nreg["$l[0]"]++;
			$areg["$l[0]"] = (($nreg["$l[0]"] - 1) * $areg["$l[0]"] + $r[2])/$nreg["$l[0]"];
			$ncty["$l[0]$locsep$l[1]"]++;
			$acty["$l[0]$locsep$l[1]"] = (($ncty["$l[0]$locsep$l[1]"] - 1) * $acty["$l[0]$locsep$l[1]"] + $r[2])/$ncty["$l[0]$locsep$l[1]"];
			$nbld["$l[0]$locsep$l[1]$locsep$l[2]"]++;
			$abld["$l[0]$locsep$l[1]$locsep$l[2]"] = (($nbld["$l[0]$locsep$l[1]$locsep$l[2]"] - 1) * $abld["$l[0]$locsep$l[1]$locsep$l[2]"] + $r[2])/$nbld["$l[0]$locsep$l[1]$locsep$l[2]"];
			echo "<tr class=\"$bg\"><th class=\"$bi\"><img src=\"img/".(($r[5] == "dev")?"dev/$r[6]":"32/node").".png\" title=\"$conlbl: $r[4], $loclbl: $l[0] $l[1] $l[2]\"></th>\n";
			echo "<td><a href=\"Monitoring-Setup.php?ina=name&opa=%3D&sta=".urlencode($r[0])."\">$r[0]</a></td><th>".TestImg($r[1])."</th><td>".Bar($r[2],-99).sprintf("%01.2f",$r[2])."%</td></tr>\n";
		}
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$totlbl?>, <?=$srt?></td></tr>
</table>

</td><td class="helper">

<h2><?=$place['r']?> <?=$dislbl?></h2>

<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th colspan="2"><img src="img/16/glob.png"><br><?=$loclbl?></th>
<th width="50%"><img src="img/16/bchk.png"><br><?=$avalbl?></th>
</tr>
<?
	if($ord){
		ksort($areg);
		ksort($acty);
		ksort($abld);
	}else{
		asort($areg);
		asort($acty);
		asort($abld);
	}
	$row = 0;
	foreach ($areg as $r => $ra){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		echo "<tr class=\"$bg\"><th class=\"$bi\" width=\"50\"><img src=\"img/regg.png\" title=\"$place[r]\"></th>\n";
		echo "<td><a href=\"Monitoring-Setup.php?ina=location&opa=regexp&sta=^".urlencode($r)."$locsep\">$r</a></td><td>".Bar($ra,-99).sprintf("%01.2f",$ra)."%</td></tr>\n";
	}
?>
</table>
<p>
<h2><?=$place['c']?> <?=$dislbl?></h2>

<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th colspan="2"><img src="img/16/glob.png"><br><?=$loclbl?></th>
<th width="50%"><img src="img/16/bchk.png"><br><?=$avalbl?></th>
</tr>
<?
	foreach ($acty as $c => $ca){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		echo "<tr class=\"$bg\"><th class=\"$bi\" width=\"50\"><img src=\"img/cityg.png\" title=\"$place[c]\"></th>\n";
		echo "<td><a href=\"Monitoring-Setup.php?ina=location&opa=regexp&sta=^".urlencode($c)."$locsep\">".str_replace(";"," ",$c)."</a></td><td>".Bar($ca,-99).sprintf("%01.2f",$ca)."%</td></tr>\n";
	}
?>
</table>
<p>
<h2><?=$place['b']?> <?=$dislbl?></h2>

<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th colspan="2"><img src="img/16/glob.png"><br><?=$loclbl?></th>
<th width="50%"><img src="img/16/bchk.png"><br><?=$avalbl?></th>
</tr>
<?
	foreach ($abld as $b => $ba){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		echo "<tr class=\"$bg\"><th class=\"$bi\" width=\"50\"><img src=\"img/blds.png\" title=\"$place[b]\"></th>\n";
		echo "<td><a href=\"Monitoring-Setup.php?ina=location&opa=regexp&sta=^".urlencode($b)."\">".str_replace(";"," ",$b)."</a></td><td>".Bar($ba,-99)." ".sprintf("%01.2f",$ba)."%</td></tr>\n";
	}
?>
</table>

</td></tr></table>
<p>
<?
}

//===================================================================
// Monitoring Events
function MonEvent($ina,$opa,$sta,$lim,$ord,$opt){

	global $link,$opt,$modgroup,$self,$srtlbl,$optlbl,$levlbl,$clalbl,$stslbl,$srclbl,$loclbl,$locsep,$conlbl,$msglbl,$mico,$mlvl;
?>
<h2><?=$msglbl?> <?=$stslbl?></h2>

<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th colspan="2" width="20%"><img src="img/16/say.png"><br><?=$srclbl?></th>
<?if(!$opt){echo "<th><img src=\"img/16/$mico[10].png\"><br>$mlvl[10]</th>\n";}?>
<th><img src="img/16/<?=$mico['50']?>.png"><br><?=$mlvl['50']?></th>
<th><img src="img/16/<?=$mico['100']?>.png"><br><?=$mlvl['100']?></th>
<th><img src="img/16/<?=$mico['150']?>.png"><br><?=$mlvl['150']?></th>
<th><img src="img/16/<?=$mico['200']?>.png"><br><?=$mlvl['200']?></th>
<th><img src="img/16/<?=$mico['250']?>.png"><br><?=$mlvl['250']?></th>
</tr>
<?
	if($ord){
		$ocol = "source";
		$srt = "$srtlbl: $srclbl";
	}else{
		$ocol = "cnt desc";
		$srt = "$srtlbl: $msglbl";
	}
	if($opt){
		$query	= GenQuery('events','g','source;location,contact,class,icon,sum(level="10"),sum(level="50"),sum(level="100"),sum(level="150"),sum(level="200"),sum(level="250")',$ocol,$lim,array('class',$ina),array('regexp',$opa),array('dev|node',$sta),array('AND'),'LEFT JOIN devices USING (device)');
	}else{
		$ico = "32/fogy";
		$query	= GenQuery('events','g','source;location,contact,class,icon,sum(level="10"),sum(level="50"),sum(level="100"),sum(level="150"),sum(level="200"),sum(level="250")',$ocol,$lim,array($ina),array($opa),array($sta),'','LEFT JOIN devices USING (device)');
	}
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( ($r = @DbFetchRow($res)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$l = explode($locsep, $r[2]);
			if($opt){
				if($r[4] == "dev"){
					$ico = "dev/$r[5]";
				}else{
					$ico = "32/node";
				}
			}
			echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\"><th class=\"$bi\">\n";
			echo "<img src=\"img/$ico.png\" title=\"$conlbl: $r[3], $loclbl: $l[0] $l[1] $l[2]\"></th>\n";
			echo "<td><a href=\"Monitoring-Events.php?ina=source&opa=%3D&sta=".urlencode($r[0])."\">$r[0]</a></td>\n";
			if(!$opt){echo "<td>".(($r[6])?Bar($r[6],"lvl10",'mi')."<a href=\"Monitoring-Events.php?ina=source&opa==&sta=".urlencode($r[0])."&cop=AND&inb=level&opb==&stb=10\"> $r[6]</a>":"-")."</td>\n";}
			echo "<td>";
			if($r[7]){echo Bar($r[7],"lvl50",'mi')."<a href=\"Monitoring-Events.php?ina=source&opa==&sta=".urlencode($r[0])."&cop=AND&inb=level&opb==&stb=50\"> $r[7]</a>";}
			echo "</td><td>\n";
			if($r[8]){echo Bar($r[8],"lvl100",'mi')."<a href=\"Monitoring-Events.php?ina=source&opa==&sta=".urlencode($r[0])."&cop=AND&inb=level&opb==&stb=100\"> $r[8]</a>";}
			echo "</td><td>\n";
			if($r[9]){echo Bar($r[9],"lvl150",'mi')."<a href=\"Monitoring-Events.php?ina=source&opa==&sta=".urlencode($r[0])."&cop=AND&inb=level&opb==&stb=150\"> $r[9]</a>";}
			echo "</td><td>\n";
			if($r[10]){echo Bar($r[10],"lvl200",'mi')."<a href=\"Monitoring-Events.php?ina=source&opa==&sta=".urlencode($r[0])."&cop=AND&inb=level&opb==&stb=200\"> $r[10]</a>";}
			echo "</td><td>\n";
			if($r[11]){echo Bar($r[11],"lvl250",'mi')."<a href=\"Monitoring-Events.php?ina=source&opa==&sta=".urlencode($r[0])."&cop=AND&inb=level&opb==&stb=250\"> $r[11]</a>";}
			echo "</td></tr>\n";
		}
		@DbFreeResult($res);
	}else{
		print @DbError($link);
		die;
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$msglbl?>, <?=$srt?><?=($opt)?", $optlbl: $clalbl = dev & node":""?></td></tr>
</table>
<p>
<?
}

//===================================================================
// Monitoring Latency
function MonLatency($ina,$opa,$sta,$lim,$ord){

	global $link,$modgroup,$self,$srtlbl,$tgtlbl,$latlbl,$latw,$loclbl,$locsep,$conlbl,$stslbl,$laslbl,$avglbl,$maxlbl;
?>
<h2><?=$latlbl?> <?=$stslbl?></h2>

<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th colspan="2" width="20%"><img src="img/16/bino.png"><br><?=$tgtlbl?></th>
<th width="40"><img src="img/16/bchk.png"><br>Test</th>
<th><img src="img/16/bbrt.png"><br><?=$laslbl?></th>
<th><img src="img/16/form.png"><br><?=$avglbl?></th>
<th><img src="img/16/brup.png"><br><?=$maxlbl?></th>
</tr>
<?
	if($ord){
		$ocol = "name";
		$srt = "$srtlbl: $tgtlbl";
	}else{
		$ocol = "latavg desc";
		$srt = "$srtlbl: $avglbl $latlbl";
	}
	$query	= GenQuery('monitoring','s','name,test,latency,latmax,latavg,location,contact,class,icon',$ocol,$lim,array('latency',$ina),array('>',$opa),array('0',$sta),array('AND'),'LEFT JOIN devices USING (device)');
	$res = @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$l = explode($locsep, $r[5]);
			echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\">\n";
			echo "<th class=\"$bi\"><img src=\"img/".(($r[7] == "dev")?"dev/$r[8]":"32/node").".png\" title=\"$conlbl: $r[6], $loclbl: $l[0] $l[1] $l[2]\"></th>\n";
			echo "<td><a href=\"Monitoring-Setup.php?ina=name&opa=%3D&sta=".urlencode($r[0])."\">$r[0]</a></td><th class=\"$bi\">".TestImg($r[1])."</th><td>";
			echo Bar($r[2],$latw,'mi')." ${r[2]}ms</td><td>".Bar($r[4],$latw,'mi')." ${r[4]}ms</td><td>".Bar($r[3],$latw,'mi')." ${r[3]}ms</tr>\n";
		}
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Devices, <?=$srt?></td></tr>
</table>
<p>
<?
}

//===================================================================
// Monitoring Uptime
function MonUptime($ina,$opa,$sta,$lim,$ord){

	global $link,$modgroup,$self,$tgtlbl,$stslbl,$tim,$place,$locsep,$loclbl,$srtlbl,$conlbl;
?>
<h2>Uptime <?=$stslbl?></h2>

<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th colspan="3"  width="33%"><img src="img/16/dev.png"><br>Device</th>
<th><img src="img/16/find.png"><br>Info</th>
<th><img src="img/16/clock.png"><br>Uptime</th>
</tr>
<?
	if($ord){
		$ocol = 'name';
		$srt = "$srtlbl: $tgtlbl";
	}else{
		$ocol = 'uptime desc';
		$srt = "$srtlbl: Uptime";
	}
	$query	= GenQuery('monitoring','s','name,uptime/360000,devip,cliport,location,contact,icon',$ocol,$lim,array('test',$ina),array('=',$opa),array('uptime',$sta),array('AND'),'LEFT JOIN devices USING (device)');
	$res = @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$l = explode($locsep, $r[4]);
			echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\">\n";
			echo "<th class=\"$bi\"><img src=\"img/dev/$r[6].png\"><td><a href=Devices-Status.php?dev=".urlencode($r[0]).">$r[0]</a></td></th>\n";
			echo "<td>".Devcli(long2ip($r[2]),$r[3])."</td><td><img src=\"img/16/user.png\" title=\"$conlbl\"> $r[5]<br><img src=\"img/16/home.png\" title=\"$loclbl\"> $l[0] $l[1] $l[2]";
			echo "<td> ".Bar($r[1],365*24).intval($r[1]/24)." $tim[d] ".intval($r[1]%24)." $tim[h]</td></tr>\n";
		}
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Devices, <?=$srt?></td></tr>
</table>
<p>
<?
}

//===================================================================
// Network Distribution
function NetDist($ina,$opa,$sta,$lim,$ord){
	
	global $link,$modgroup,$self,$verb1,$netlbl,$dislbl,$adrlbl,$poplbl,$agelbl,$tim,$totlbl,$srtlbl;

	if($ina == "devip"){$ina = "ifip";}
	if($ord){
		$ocol = "device";
		$srt = "$srtlbl: Device";
	}else{
		$ocol = "ifip";
		$srt = "$srtlbl: IP $adrlbl";
	}
	$query	= GenQuery('networks','s','networks.*',$ocol,'',array($ina),array($opa),array($sta),'','LEFT JOIN devices USING (device)' );
	$res	= @DbQuery($query,$link);
	if ($res) {
		while( ($n = @DbFetchRow($res)) ){
			$n[2]	= ip2long(long2ip($n[2]));						# Hack to fix signing issue for 32bit vars in PHP!
			$n[3]	= ip2long(long2ip($n[3]));
			$dnet	= sprintf("%u",$n[2] & $n[3]);
			$vrf = ($n[4])?"<a href=\"Topology-Networks.php?ina=vrfname&opa==&sta=".urlencode($n[4])."\">$n[4]</a> ":"";

			if( isset($nets[$dnet]) ){
				if($nets[$dnet] != $n[3]){
					$devs[$dnet][$n[0]]	= "$n[1] $vrf<span class=\"red\">" .long2ip($n[3]) . "</span>";
				}else{
					if($devs[$dnet][$n[0]]){
						$devs[$dnet][$n[0]]	= "$n[1]  $vrf<span class=\"grn\">multiple ok</span>";
					}else{
						$devs[$dnet][$n[0]]	= "$n[1]  $vrf<span class=\"grn\">ok</span>";
					}
				}
			}elseif($n[3]){									# Ignore /0 networks...			
				$nets[$dnet] = $n[3];
				$pop[$dnet] = 0;
				$age[$dnet] = 0;
				if($n[3] == -1){
					$devs[$dnet][$n[0]] = "$n[1]  $vrf<span class=\"prp\">hostroute</span>";
				}else{
					$devs[$dnet][$n[0]] = "$n[1]  $vrf<span class=\"blu\">mask base</span>";
					$nquery	= GenQuery('nodes','s','count(*),round(avg(lastseen + 1 - firstseen)/86400)','','',array("nodip & $n[3]"),array('='),array($dnet) ); # add 1 sec to avoid ridiculous numbers on swift nodes
					$nodres	= @DbQuery($nquery,$link);
					$no	= @DbFetchRow($nodres);
					$pop[$dnet] = ($no[0])?$no[0]:0;
					$age[$dnet] = ($no[1])?$no[1]:0;
					@DbFreeResult($nodres);
				}
			}
		}
		@DbFreeResult($res);

		if($nets){
?>
<h2><?=$netlbl?> <?=$dislbl?></h2>

<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th colspan="2"><img src="img/16/net.png"><br>IP <?=$adrlbl?></th>
<th width="30%"><img src="img/16/dev.png"><br>Devices</th>
<th><img src="img/16/nods.png"><br><?=$poplbl?></th>
<th><img src="img/16/clock.png"><br>Node <?=$agelbl?> [<?=$tim[d]?>]</th>
</tr>
<?
			$row = 0;
			foreach(array_keys($nets) as $dn ){
				if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
				$row++;
				$net	                = long2ip($dn);
				list($pfix,$mask,$bmsk)	= Masker($nets[$dn]);
				list($ntimg,$ntit)	= Nettype($net);
				$dvs = "";
				foreach( array_keys($devs[$dn]) as $dv ){
					$dvs .= "<a href=\"Devices-Status.php?dev=".urlencode($dv)."\">$dv</a> ".$devs[$dn][$dv]."<br>\n";
				}
				echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\">\n";
				echo "<th class=\"$bi\" width=\"20\"><img src=\"img/$ntimg\" title=\"$ntit\"></th>\n";
				echo "<td>";
				if( !isset($_GET['print']) ){
					echo "<div style=\"float:right\">\n";
					echo "<a href=\"Topology-Networks.php?mod=f&fmt=png&ina=ifip&opa==&sta=$net%2F$pfix\"><img src=\"img/16/glob.png\" title=\"Topology-Networks\"></a>\n";
					echo "<a href=\"Topology-Map.php?mod=f&fmt=png&ina=ifip&opa==&sta=$net%2F$pfix\"><img src=\"img/16/paint.png\" title=\"Topology-Maps\"></a>\n";
					echo "<a href=\"Other-Calculator.php?ip=$net&nmsk=$pfix\"><img src=\"img/16/calc.png\" title=\"Other-Calculator\"></a></div>\n";
				}
				echo "<a href=\"?ina=devip&opa==&sta=$net%2F$pfix&rep%5B%5D=net\">$net/$pfix</a>\n";
				echo "<td>$dvs</td><td>";
				if($pop[$dn]){echo Bar($pop[$dn],110)." <a href=\"Nodes-List.php?ina=nodip&opa==&sta=$net/$pfix&ord=nodip\">$pop[$dn]</a>\n";}
				echo "</td><td>\n";
				if($age[$dn]){echo Bar($age[$dn],'lvl100')." $age[$dn]\n";}
				echo "</td></tr>\n";
				if($row == $lim){break;}
			}
?>
</table>
<table class="content">
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Subnets, <?=$srt?></td></tr>
</table>
<p>
<?
		}
	}
}

//===================================================================
// Network Population
// Using IP-strings as hash indexes to avoid signed int problems.
// Don't assume it works the same way on all 32-bit systems or PHP versions!
function NetPop($ina,$opa,$sta,$lim,$ord){
	
	global $link,$modgroup,$self,$verb1,$netlbl,$dislbl,$adrlbl,$poplbl,$agelbl,$tim,$totlbl,$srtlbl;

	if($ina == "devip"){$ina = "ifip";}
	if($ord){
		$ocol = "device";
		$srt = "$srtlbl: Device";
	}else{
		$ocol = "ifip";
		$srt = "$srtlbl: IP $adrlbl";
	}
	$query	= GenQuery('networks','s','networks.*,lastdis',$ocol,'',array($ina),array($opa),array($sta),'','LEFT JOIN devices USING (device)' );
	$res	= @DbQuery($query,$link);
	if ($res) {
		$row = 0;
		$netok = array();
		while( ($n = @DbFetchRow($res)) ){
			$n[2] = ip2long(long2ip($n[2]));				# Hack to fix signing issue for 32bit vars in PHP!
			$n[3] = ip2long(long2ip($n[3]));
			$dnet = long2ip($n[2] & $n[3]);
			list($pfix,$mask,$bmsk)	= Masker($n[3]);
			if($pfix > 16 and $pfix < 32){					# Only < /16 but not /32 networks
				if( !array_key_exists($dnet,$netok) ){			# Only if subnet hasn't been processed 
					$netok[$dnet] = 1;
					$nod[$dnet] = array();
					$nquery	= GenQuery('nodes','s','name,inet_ntoa(nodip)','nodip','',array("nodip & $n[3]"),array('='),array(sprintf("%u",$n[2] & $n[3])) );
					$nres	= @DbQuery($nquery,$link);
					if ($nres) {
						while( ($no = @DbFetchRow($nres)) ){
							$nod[$dnet][$no[1]] = $no[0];
						}
					}
					@DbFreeResult($nres);
				}
				$n[2] = long2ip($n[2]);
				$dev[$dnet][$n[2]] = $n[0];
				$nets[$dnet] = $pfix;
				if(count(array_keys($nets)) == $lim){break;}
			}
		}
		@DbFreeResult($res);
		if($nets){
?>
<h2><?=$netlbl?> <?=$poplbl?></h2>

<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th colspan="2"><img src="img/16/net.png"><br>IP <?=$adrlbl?></th>
<th><img src="img/16/nods.png"><br><?=$poplbl?></th>
</tr>
<?
			$row = 0;
			foreach(array_keys($nets) as $net){
				if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
				$row++;
				list($ntimg,$ntit) = Nettype($net);
				echo "<tr class=\"$bg\">\n";
				echo "<th class=\"$bi\" width=\"20\"><img src=\"img/$ntimg\" title=\"$ntit\"></th>\n";
				echo "<td><a href=\"?ina=devip&opa==&sta=$net%2F$nets[$net]&rep%5B%5D=pop\">$net/$nets[$net]</a><p>";
				echo "<a href=\"Topology-Networks.php?ina=ifip&opa==&sta=$net%2F$nets[$net]\"><img src=\"img/16/glob.png\" title=\"IF IPs\"> ".count(array_keys($dev[$net]))."</a><p>";
				echo "<a href=\"Nodes-List.php?ina=nodip&opa==&sta=$net%2F$nets[$net]\"><img src=\"img/16/nods.png\" title=\"Node IPs\"> ".count(array_keys($nod[$net]))."</a";
				echo "</td>";
				echo "<td><table><tr>";
				$col = 0;
				$dn = ip2long($net);
				$max = $dn + pow(2,(32-$nets[$net]));
				for($a = $dn; $a < $max; $a++){
					if($col == 64){$col = 0;echo "</tr>\n<tr>";}
					$ip = long2ip($a);
					if( array_key_exists($ip, $dev[$net]) and array_key_exists($ip, $nod[$net]) ){
						echo "<td title=\"$ip Dev:".$dev[$net][$ip]." Nod:".$nod[$net][$ip]."\" class=\"warn\"><a href=\"Topology-Networks.php?ina=ifip&opa==&sta=$ip\">&nbsp;</a></td>";
					}elseif( array_key_exists($ip, $nod[$net]) ){
						echo "<td title=\"$ip Nod:".$nod[$net][$ip]."\" class=\"good\"><a href=\"Nodes-List.php?ina=nodip&opa==&sta=$ip\">&nbsp;</a></td>";
					}elseif( array_key_exists($ip, $dev[$net]) ){
						echo "<td title=\"$ip Dev:".$dev[$net][$ip]."\" class=\"noti\"><a href=\"Topology-Networks.php?ina=ifip&opa==&sta=$ip\">&nbsp;</a></td>";
					}elseif($a == $dn or $a == $max -1){
						$netxt = ($a == $dn)?$netlbl:"Broadcast";
						echo "<td title=\"$netxt:$ip\" class=\"$bg part\">&nbsp;</td>";
					}else{
						echo "<td title=\"$ip\" class=\"$bi\">&nbsp;</td>";
					}
					$col++;
				}
				echo "</tr></table></td></tr>\n";
			}
?>
</table>
<table class="content">
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Subnets, <?=$srt?></td></tr>
</table>
<p>
<?
		}
	}
}

//===================================================================
// Node Discovery History
function NodHistory($ina,$opa,$sta,$lim,$ord){

	global $link,$modgroup,$self,$srtlbl,$timlbl,$dsclbl,$fislbl,$laslbl,$hislbl,$lstlbl,$updlbl,$msglbl;
?>
<h2>Nodes <?=$hislbl?></h2>

<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th width="100"><img src="img/16/clock.png"><br><?=$timlbl?></th>
<th><img src="img/16/blft.png"><br><?=$fislbl?> <?=$dsclbl?></th>
<th><img src="img/16/brgt.png"><br><?=$laslbl?> <?=$dsclbl?></th>
<th><img src="img/16/calc.png"><br>IP <?=$updlbl?></th>
<th><img src="img/16/walk.png"><br>IF <?=$updlbl?></th>
</tr>
<?
	$query	= GenQuery('nodes','g','firstseen',($ord)?'firstseen':'firstseen desc',$lim,array($ina),array($opa),array($sta),'','LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	$fisr   = DbNumRows($res);
	if($res){
		while( $r = @DbFetchRow($res) ){
			$nodup[$r[0]]['fs'] = $r[1];
		}
	}
	$query	= GenQuery('nodes','g','lastseen',($ord)?'lastseen':'lastseen desc',$lim,array($ina),array($opa),array($sta),'','LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	$lasr   = DbNumRows($res);
	if($res){
		while( $r = @DbFetchRow($res) ){
			$nodup[$r[0]]['ls'] = $r[1];
		}
	}
	$query	= GenQuery('nodes','g','ipupdate',($ord)?'ipupdate desc':'ipupdate',$lim,array('ipupdate',$ina),array('>',$opa),array('0',$sta),array('AND'),'LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	$iupr   = DbNumRows($res);
	if($res){
		while( $r = @DbFetchRow($res) ){
			$nodup[$r[0]]['au'] = $r[1];
		}
	}
	$query	= GenQuery('nodes','g','ifupdate',($ord)?'ifupdate desc':'ifupdate',$lim,array($ina),array($opa),array($sta),'','LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	$aupr   = DbNumRows($res);
	if($res){
		while( $r = @DbFetchRow($res) ){
			$nodup[$r[0]]['iu'] = $r[1];
		}
	}

	if($ord){
		ksort ($nodup);
		$srt = "$srtlbl: $laslbl - $fislbl";
	}else{
		krsort ($nodup);
		$srt = "$srtlbl: $fislbl - $laslbl";
	}
	$row = 0;
	foreach ( array_keys($nodup) as $d ){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		$fd   = urlencode(date("m/d/Y H:i:s",$d));
		echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\">\n";
		echo "<td class=\"$bi\"><b>".date($_SESSION['date'],$d)."</b></td><td>\n";
		if( array_key_exists('fs',$nodup[$d]) ){echo Bar($nodup[$d]['fs'],"lvl50",'mi')." <a href=\"Nodes-List.php?ina=firstseen&opa==&sta=$fd\" title=\"Node $lstlbl\">".$nodup[$d]['fs']."</a>";}
		echo "</td><td>\n";
		if( array_key_exists('ls',$nodup[$d]) ){echo Bar($nodup[$d]['ls'],"lvl250",'mi')." <a href=\"Nodes-List.php?ina=lastseen&opa==&sta=$fd\" title=\"Node $lstlbl\">".$nodup[$d]['ls']."</a>";}
		echo "</td><td>\n";
		if( array_key_exists('au',$nodup[$d]) ){echo Bar($nodup[$d]['au'],"lvl100",'mi')." <a href=\"Nodes-List.php?ina=ipupdate&opa==&sta=$fd\" title=\"Node $lstlbl\">".$nodup[$d]['au']."</a>";}
		echo "</td><td>\n";
		if( array_key_exists('iu',$nodup[$d]) ){echo Bar($nodup[$d]['iu'],"lvl150",'mi')." <a href=\"Nodes-List.php?ina=ifupdate&opa==&sta=$fd\" title=\"Node $lstlbl\">".$nodup[$d]['iu']."</a>";}
		echo "</tr>\n";
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$msglbl?> (<?=$fisr?> <?=$fislbl?>, <?=$lasr?> <?=$laslbl?>, <?=$iupr?> IF <?=$updlbl?>, <?=$aupr?> IP <?=$updlbl?>), <?=$srt?></td></tr>
</table>
<p>
<?
}

//===================================================================
// Node Distribution
function NodDist($ina,$opa,$sta,$lim,$ord){

	global $link,$modgroup,$self,$srtlbl,$poplbl,$loclbl,$locsep,$conlbl,$metlbl,$neblbl,$vallbl,$duplbl;
?>
<table class="full fixed"><tr><td class="helper">

<h2>Nodes / IF</h2>

<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th colspan="2" width="25%"><img src="img/16/dev.png"><br>Device</th>
<th width="15%"><img src="img/16/port.png"><br>IF</th>
<th width="10%"><img src="img/16/calc.png"><br><?=$metlbl?></th>
<th width="50%"><img src="img/16/nods.png"><br><?=$poplbl?></th>
<?
	if($ord){
		$ocol = 'device';
		$srt = "$srtlbl: Device";
	}else{
		$ocol = 'cnt desc';
		$srt = "$srtlbl: $poplbl";
	}
	$query	= GenQuery('nodes','g','device,ifname;contact,location,icon,ifmetric',$ocol,$lim,array($ina),array($opa),array($sta),'','LEFT JOIN devices USING (device)');
	$res = @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$ud = urlencode($r[0]);
			$ui = urlencode($r[1]);
			$l = explode($locsep, $r[4]);
			$ico = ($r[5])?"dev/$r[5]":"32/bbox";
			echo "<tr class=\"$bg\"><th class=\"$bi\"><img src=\"img/$ico.png\" title=\"$conlbl: $r[3], $loclbl: $l[0] $l[1] $l[2]\"></a></th>\n";
			echo "</th><td><a href=\"Devices-Status.php?dev=$ud&shp=on\">$r[0]</a></td><td><a href=Devices-Interfaces.php?ina=device&opa==&sta=$ud&cop=AND&inb=ifname&opb==&stb=$ui>$r[1]</a></td>\n";
			echo "<td align=\"right\">".(($r[6] > 255)?$r[6]:"SNR")."</td><td>".Bar($r[2],8)." <a href=Nodes-List.php?ina=device&opa==&sta=$ud&cop=AND&inb=ifname&opb==&stb=$ui>$r[2]</a></td></tr>\n";
		}
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Devices, <?=$srt?></td></tr>
</table>

</td><td class="helper">

<h2>Nodes / Device</h2>

<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th colspan="2" width="25%"><img src="img/16/dev.png"><br>Device</th>
<th width="50%"><img src="img/16/nods.png"><br><?=$poplbl?></th>
<?
	if($ord){
		$ocol = 'device';
		$srt = "$srtlbl: Device";
	}else{
		$ocol = 'cnt desc';
		$srt = "$srtlbl: $poplbl";
	}
	$query	= GenQuery('nodes','g','device;contact,location,icon',$ocol,$lim,array($ina),array($opa),array($sta),'','LEFT JOIN devices USING (device)');
	$res = @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$ud = urlencode($r[0]);
			$l = explode($locsep, $r[3]);
			$ico = ($r[4])?"dev/$r[4]":"32/bbox";
			echo "<tr class=\"$bg\"><th class=\"$bi\"><img src=\"img/$ico.png\" title=\"$conlbl: $r[2], $loclbl: $l[0] $l[1] $l[2]\"></a></th>\n";
			echo "</th><td><a href=\"Devices-Status.php?dev=$ud&shp=on\">$r[0]</a></td>\n";
			echo "<td>".Bar($r[1])." <a href=Nodes-List.php?ina=device&opa==&sta=$ud>$r[1]</a></td></tr>\n";
		}
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Devices, <?=$srt?></td></tr>
</table>

</td></tr>
<tr><td class="helper">

<h2>IF <?=$metlbl?> <?=$stslbl?></h2>

<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th width="25%"><img src="img/16/dcal.png" title="4096 Link (8192 <?=$neblbl?> OK), 2048 Router, 1024 Trunk/Channel, 512 No-SNMP-Dev, 256 Wired"><br><?=$metlbl?></th>
<th><img src="img/16/nods.png"><br>Nodes</th>
<?
	if($ord){
		$ocol = 'ifmetric';
		$srt = "$srtlbl: $metlbl";
	}else{
		$ocol = 'cnt desc';
		$srt = "$srtlbl: Nodes";
	}
	$query	= GenQuery('nodes','g','ifmetric',$ocol,$lim,array('ifmetric',$ina),array('>',$opa),array('255',$sta),array('AND'),'LEFT JOIN devices USING (device)');
	$res = @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			echo "<tr class=\"$bg\"><th class=\"$bi\">$r[0]</th>\n";
			echo "<td>".Bar($r[1])." <a href=Nodes-List.php?ina=ifmetric&opa==&sta=$r[0]>$r[1]</a></td></tr>\n";
		}
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$metlbl?> <?=$vallbl?>, <?=$srt?></td></tr>
</table>

</td><td class="helper">

</td></tr></table>
<p>
<?
}

//===================================================================
// List duplicate Nodes
function NodDup($ina,$opa,$sta,$lim,$ord){

	global $link,$modgroup,$self,$srtlbl,$manlbl,$namlbl,$numlbl,$duplbl,$typlbl,$totlbl;
?>
<table class="full fixed"><tr><td class="helper">

<h2><?=$duplbl?> Node <?=$namlbl?></h2>

<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th width="120"><img src="img/16/abc.png"><br><?=$namlbl?></th>
<th><img src="img/16/nods.png"><br>Nodes</th></tr>
<?
	if($ord){
		$ocol = 'devip';
		$srt = "$srtlbl: $namlbl";
	}else{
		$ocol = 'cnt desc';
		$srt = "$srtlbl: $numlbl";
	}
	$query	= GenQuery('nodes','g','name;oui;cnt>1',$ocol,$lim,array('CHAR_LENGTH(name)',$ina),array('>',$opa),array('1',$sta),array('AND'),'LEFT JOIN devices USING (device)');
	$res = @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			echo "<tr class=\"$bg\"><td>$r[0]</td><td>";
			echo Bar($r[1],0)." <a href=\"Nodes-List.php?ina=name&opa==&sta=$r[0]\">$r[1]</a></td></tr>\n";
		}
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$duplbl?> Nodes, <?=$srt?></td></tr>
</table>

</td><td class="helper">

<h2><?=$duplbl?> Node MACs</h2>

<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th colspan="2" width="25%"><img src="img/16/cinf.png"><br>MAC</th>
<th width="40"><img src="img/16/vlan.png"><br>Vlan</th>
<th><img src="img/16/nods.png"><br>Nodes</th></tr>
<?
	if($ord){
		$ocol = 'mac,vlanid';
		$srt = "$srtlbl: MAC, Vlan";
	}else{
		$ocol = 'cnt desc';
		$srt = "$srtlbl: Nodes";
	}
	$query	= GenQuery('nodes','g','mac,vlanid;oui;cnt>1',$ocol,$lim,array($ina),array($opa),array($sta),'','LEFT JOIN devices USING (device)');
	$res = @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			echo "<tr class=\"$bg\"><th class=\"$bi\"><img src=\"img/oui/".Nimg($r[3]).".png\"></th><td>$r[0]</td><td>$r[1]</td>\n";
			echo "<td>".Bar($r[2],0)." <a href=Nodes-List.php?ina=mac&opa==&sta=$r[0]>$r[2]</a></td></tr>\n";
		}
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$duplbl?> MACs, <?=$srt?></td></tr>
</table>

</td></tr></table>
<p>
<?
}

//===================================================================
// Node Operating Systems
function NodOS($ina,$opa,$sta,$lim,$ord){

	global $link,$modgroup,$self,$stslbl,$srtlbl,$typlbl,$numlbl;
?>
<table class="full fixed"><tr><td class="helper">

<h2>OS <?=$stslbl?></h2>
<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th colspan="2"><img src="img/16/cbox.png"><br>OS</th>
<th><img src="img/16/nods.png"><br>Nodes</th></tr>
<?

	if($ord){
		$ocol = "nodos";
		$srt = "$srtlbl: OS";
	}else{
		$ocol = "cnt desc";
		$srt = "$srtlbl: $numlbl";
	}
	$query	= GenQuery('nodes','g','nodos',$ocol,$lim,array($ina),array($opa),array($sta),'','LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( ($r = @DbFetchRow($res)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			if($r[0]){
				$uo = urlencode($r[0]);
				$op = "=";
			}else{
				$uo = "^$";
				$op = "regexp";
			}
			echo "<tr class=\"$bg\"><th class=\"$bi\">$row</th>\n";
			echo "<td>$r[0]</td><td nowrap>".Bar($r[1],0,'mi')." <a href=Nodes-List.php?ina=nodos&opa=$op&sta=$uo>$r[1]</a></td></tr>\n";
		}
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> OS, <?=$srt?></td></tr>
</table>

</td><td class="helper">

<h2><?=$typlbl?> <?=$stslbl?></h2>
<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th colspan="2"><img src="img/16/abc.png"><br><?=$typlbl?></th>
<th><img src="img/16/nods.png"><br>Nodes</th></tr>
<?
	if($ord){
		$ocol = "nodtype";
		$srt = "$srtlbl: $typlbl";
	}else{
		$ocol = "cnt desc";
		$srt = "$srtlbl: $numlbl";
	}
	$query	= GenQuery('nodes','g','nodtype',$ocol,$lim,array($ina),array($opa),array($sta),'','LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( ($r = @DbFetchRow($res)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			if($r[0]){
				$uo = urlencode($r[0]);
				$op = "=";
			}else{
				$uo = "^$";
				$op = "regexp";
			}
			echo "<tr class=\"$bg\"><th class=\"$bi\">$row</th>\n";
			echo "<td>$r[0]</td><td nowrap>".Bar($r[1],0,'mi')." <a href=Nodes-List.php?ina=nodos&opa=$op&sta=$uo>$r[1]</a></td></tr>\n";
		}
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$typlbl?>, <?=$srt?></td></tr>
</table>

</td></tr></table>
<p>
<?
}

//===================================================================
// Nomad Nodes
function NodNomad($ina,$opa,$sta,$lim,$ord){

	global $link,$modgroup,$self,$nomlbl,$srtlbl,$chglbl,$namlbl,$vallbl,$lstlbl,$numlbl;
?>
<h2><?=$nomlbl?> <?=$lstlbl?></h2>
<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th width="20"></th>
<th colspan="2"><img src="img/16/node.png"><br>Node</th>
<th><img src="img/16/dev.png"><br>IF</th>
<th><img src="img/16/calc.png"><br>IP <?=$chglbl?></th>
<th><img src="img/16/walk.png"><br>IF <?=$chglbl?></th>
<th><img src="img/16/find.png" title="<?=$nomlbl?> <?=$vallbl?> = IP <?=$chglbl?> * IF <?=$chglbl?>"><br><?=$nomlbl?> <?=$vallbl?></th></tr>
<?
	if($ord){
		$ocol = "name";
		$srt = "$srtlbl: $namlbl";
	}else{
		$ocol = "nom desc";
		$srt = "$srtlbl: $nomlbl $vallbl";
	}
	$query	= GenQuery('nodes','s','name,mac,oui,inet_ntoa(nodip),device,ifname,ifchanges,ipchanges,(ifchanges * ipchanges) as nom',$ocol,$lim,array('ifchanges','ipchanges',$ina),array('>','>',$opa),array('0','0',$sta),array('AND','AND'),'LEFT JOIN devices USING (device)');
	$res = @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$ud = urlencode($r[4]);
			$ui = urlencode($r[5]);
			echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\">\n";
			echo "<th class=\"$bi\"><img src=\"img/oui/".Nimg($r[2]).".png\"></th>\n";
			echo "<td>$r[0]</td><td><a href=Nodes-List.php?ina=nodip&opa==&sta=$r[3]>$r[3]</a></td>\n";
			echo "<td>";
			if( !isset($_GET['print']) and strpos($_SESSION['group'],$modgroup['Devices-Status']) !== false ){
				echo "<a href=\"Devices-Status.php?dev=$ud&pop=on\"><img src=\"img/16/sys.png\"></a>\n";
			}
			echo "$r[4] <a href=Nodes-List.php?ina=ifname&opa==&sta=$ui>$r[5]</td>";
			echo "<th><a href=Nodes-List.php?ina=ifchanges&opa==&sta=$r[6]>$r[6]</th>";
			echo "<th><a href=Nodes-List.php?ina=ipchanges&opa==&sta=$r[7]>$r[7]</th>";
			echo "<td>".Bar($r[8],100,'mi')."$r[8]</td></tr>\n";
		}
	}
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$nomlbl?>, <?=$srt?></td></tr>
</table>
<p>
<?
}

//===================================================================
// Node Summary
function NodSum($ina,$opa,$sta,$lim,$ord){

	global $link,$modgroup,$self,$rrdstep,$stco,$sumlbl,$srtlbl,$venlbl,$numlbl,$alllbl,$chglbl,$totlbl,$deslbl,$fislbl,$laslbl,$emplbl,$namlbl,$metlbl,$nonlbl,$loslbl,$qutlbl,$faslbl,$vallbl,$mullbl;

	$lasdis = time() - $rrdstep * 2;
	$query	= GenQuery('nodes','s',"count(*),sum(nodip = 0),sum(name = \"\"),sum(firstseen=lastseen),sum(iplost > 0),sum(ifmetric < 256),sum(firstseen > $lasdis),sum(lastseen > $lasdis),sum(ipchanges > 0),sum(ifchanges > 0),sum(arpval > 1)",'','',array($ina),array($opa),array($sta),'','LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	if ($res) {
		$r = @DbFetchRow($res);
	}else{
		print @DbError($link);
		die;
	}
?>
<table class="full fixed"><tr><td class="helper">

<h2>Node <?=$sumlbl?> </h2>
<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th width="33%" colspan="2"><img src="img/16/find.png" title="Nodes <?=$stslbl?>">
<br><?=$deslbl?></th><th><img src="img/16/nods.png"><br>Nodes</th>
<tr class="txtb"><th class="imgb"><img src="img/16/star.png" title="<?=$fislbl?> > <?=date($_SESSION['date'],$lasdis)?>"></th><td><b><?=$stco['10']?></b></td><td><?=Bar($r[6],0,'mi')?> <a href="Nodes-List.php?ina=firstseen&opa=>&sta=<?=$lasdis?>&ord=nodip"><?=$r[6]?></a></td></tr>
<tr class="txta"><th class="imga"><img src="img/16/exit.png" title="<?=$laslbl?> > <?=date($_SESSION['date'],$lasdis)?>"></th><td><b><?=$stco['100']?></b></td><td><?=Bar($r[7],0,'mi')?> <a href="Nodes-List.php?ina=lastseen&opa=>&sta=<?=$lasdis?>&ord=nodip"><?=$r[7]?></a></td></tr>
<tr class="txtb"><th class="imgb"><img src="img/16/wlan.png" title="IF <?=$metlbl?> < 256"></th><td><b>Wlan</th></b><td><?=Bar($r[5],0,'mi')?> <a href="Nodes-List.php?ina=ifmetric&opa=<&sta=256&ord=ifmetric+desc"> <?=$r[5]?></a></td></tr>
<tr class="txta"><th class="imga"><img src="img/16/calc.png" title="IP <?=$chglbl?> > 0"></th><td><b>IP <?=$chglbl?></b></td><td><?=Bar($r[8],0,'mi')?> <a href="Nodes-List.php?ina=ipchanges&opa=>&sta=0&ord=ipchanges+desc"><?=$r[8]?></a></td></tr>
<tr class="txtb"><th class="imgb"><img src="img/16/walk.png" title="IF <?=$chglbl?> > 0"></th><td><b>IF <?=$chglbl?></b></td><td><?=Bar($r[9],0,'mi')?> <a href="Nodes-List.php?ina=ifchanges&opa=>&sta=0&ord=ifchanges+desc"><?=$r[9]?></a></td></tr>
<tr class="txta"><th class="imga"><img src="img/16/abc.png"  title=" <?=$namlbl?> = ''"></th><td><b><?=$namlbl?> <?=$emplbl?></b></td><td><?=Bar($r[2],0,'mi')?> <a href="Nodes-List.php?ina=name&opa=regexp&sta=^$&ord=nodip"><?=$r[2]?></a></td></tr>
<tr class="txtb"><th class="imgb"><img src="img/16/glob.png" title="IP = 0"></th><td><b><?=$nonlbl?> IP</b></td><td><?=Bar($r[1],0,'mi')?> <a href="Nodes-List.php?ina=nodip&opa==&sta=0"> <?=$r[1]?></a></td></tr>
<tr class="txta"><th class="imga"><img src="img/16/grph.png" title="IP <?=$loslbl?> > 0"></th><td><b><?=$qutlbl?></b></td><td><?=Bar($r[4],0,'mi')?> <a href="Nodes-List.php?ina=iplost&opa=%3E&sta=0&ord=iplost+desc"><?=$r[4]?></a></td></tr>
<tr class="txtb"><th class="imgb"><img src="img/16/flas.png" title="<?=$fislbl?> = <?=$laslbl?>"></th><td><b><?=$faslbl?></b></td><td><?=Bar($r[3],0,'mi')?> <a href="Nodes-List.php?ina=firstseen&cop==&inb=lastseen&ord=firstseen"><?=$r[3]?></a></td></tr>
<tr class="txta"><th class="imga"><img src="img/16/hat.png" title="ARP <?=$vallbl?> > 1"></th><td><b><?=$mullbl?> ARP</b></td><td><?=Bar($r[10],0,'mi')?> <a href="Nodes-List.php?ina=arpval&opa=>&sta=1"><?=$r[10]?></a></td></tr>
<tr class="txtb"><th class="imgb"><img src="img/16/nods.png" title="<?=$alllbl?> Nodes"></th><td><b><?=$totlbl?></b></td><td><?=Bar($r[0],0,'mi')?> <?=$r[0]?></td></tr>
</table>

</td><td class="helper">

<h2>OUI <?=$venlbl?> </h2>
<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th colspan="2" width="50%"><img src="img/16/cinf.png"><br><?=$venlbl?></th>
<th><img src="img/16/nods.png"><br>Nodes</th>
<?
	if($ord){
		$ocol = 'oui';
		$srt = "$srtlbl: $venlbl";
	}else{
		$ocol = 'cnt desc';
		$srt = "$srtlbl: $numlbl";
	}
	$query	= GenQuery('nodes','g','oui',$ocol,$lim,array($ina),array($opa),array($sta),'','LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( $r = @DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$uo = urlencode($r[0]);
			echo "<tr class=\"$bg\"><th class=\"$bi\"><img src=\"img/oui/".Nimg($r[0]).".png\"></th>\n";
			echo "<td><a href=http://www.google.com/search?q=$uo&btnI=1>$r[0]</a></td><td>".Bar($r[1],0,'mi')." <a href=Nodes-List.php?ina=oui&opa==&sta=$uo>$r[1]</a></td></tr>\n";
		}
	}
	?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$venlbl?>, <?=$srt?></td></tr>
</table>

</td></tr></table>
<p>
<?
}

//===================================================================
// Empty Vlans
function VlanEmpty($ina,$opa,$sta,$lim,$ord){

	global $link,$modgroup,$self,$verb1,$srtlbl,$lstlbl,$loclbl,$locsep,$conlbl,$emplbl;

?>
<h2><?=(($verb1)?"$emplbl Vlans":"Vlans $emplbl")?></h2>

<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th colspan="2" width="20%"><img src="img/16/dev.png"><br>Device</th>
<th><img src="img/16/vlan.png"><br>Vlan <?=$lstlbl?></th></tr>
<?
	if($ord){
		$ocol = 'vlans.vlanid';
		$srt = "$srtlbl: Vlan";
	}else{
		$ocol = 'vlans.device';
		$srt = "$srtlbl: Device";
	}
	if($ina == "device"){$ina = "vlans.device";}
	if($ina == "vlanid"){$ina = "vlans.vlanid";}
	$query	= GenQuery('vlans','s','vlans.device,vlans.vlanid,vlans.vlanname,contact,location,icon',$ocol,$lim,array('mac',$ina),array('COL IS',$opa),array('NULL',$sta),array('AND'),'LEFT JOIN nodes on (vlans.device = nodes.device and vlans.vlanid = nodes.vlanid) LEFT JOIN devices on (vlans.device = devices.device)');
	$res = @DbQuery($query,$link);
	if($res){
		$row = 0;
		$nif = 0;
		while( $r = @DbFetchRow($res) ){
			$curi = "<img src=\"img/chip.png\" title=\"$r[2]\">$r[1] ";
			if($r[0] == $prev){
				echo $curi;
				$nif++;
			}else{
				$prev = $r[0];
				if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
				$row++;
				$l  = explode($locsep, $r[3]);
				$ico = ($r[5])?"dev/$r[5]":"32/bbox";
				echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\">\n";
				echo "<th class=\"$bi\"><img src=\"img/$ico.png\" title=\"$conlbl: $r[2], $loclbl: $l[0] $l[1] $l[2]\"></th>\n";
				echo "<td><a href=\"Devices-Status.php?dev=".urlencode($r[0])."\">$r[0]</a></td>\n";
				echo "<td>$curi ";
				$nif++;
			}
		}
		echo "</td></tr></table>\n";
	}
?>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$nif?> Vlans, <?=$row?> Devices, <?=$srt?></td></tr>
</table>
<p>
<?
}

?>
