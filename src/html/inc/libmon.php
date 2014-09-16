<?PHP

//===============================
// Monitoring related functions (and variables)
//===============================

// Event icons & colors based on level
$mico['10']  = "fogy";
$mico['50']  = "fogr";
$mico['100'] = "fobl";
$mico['150'] = "foye";
$mico['200'] = "foor";
$mico['250'] = "ford";

$mbak['10']  = "txta";
$mbak['50']  = "good";
$mbak['100'] = "noti";
$mbak['150'] = "warn";
$mbak['200'] = "alrm";
$mbak['250'] = "crit";

//===================================================================
// Return icon and title for an event class
function EvClass($c){
	
	global $cfglbl,$chglbl,$memlbl,$msglbl,$notlbl,$dsclbl,$acslbl,$dcalbl,$usrlbl,$cnclbl;
	global $mlvl,$lodlbl,$trflbl,$tmplbl,$errlbl,$inblbl,$oublbl,$stco,$porlbl,$stalbl,$frelbl;

	$if = (strpos($c,'ln') !== false)?$cnclbl:$porlbl;
	if($c == 'dev'){
		return array('img/16/dev.png','Device Syslog');
	}elseif($c == 'node'){
		return array('img/16/node.png','Node Syslog');
	}elseif($c == 'trap'){
		return array('img/16/warn.png','SNMP Trap');
	}elseif($c == 'neda'){
		return array('img/16/bchk.png',"SNMP $acslbl OK");
	}elseif($c == 'nedc'){
		return array('img/16/cpu.png',"CPU $lodlbl");
	}elseif($c == 'nedd'){
		return array('img/16/radr.png',"$dsclbl");
	}elseif($c == 'nede'){
		return array('img/16/kons.png',"CLI $errlbl");
	}elseif($c == 'nedf'){
		return array('img/p45.png',"$acslbl $porlbl $frelbl");
	}elseif($c == 'nedj'){
		return array('img/16/ncfg.png',"IP $msglbl");
	}elseif($c == 'nedm'){
		return array('img/16/mem',$memlbl);
	}elseif($c == 'nedn'){
		return array('img/16/bcls.png',"$notlbl $dsclbl");
	}elseif($c == 'nedo'){
		return array('img/16/pcm.png','Module');
	}elseif($c == 'nedp'){
		return array('img/16/batt.png','PoE');
	}elseif($c == 'neds'){
		return array('img/16/sys.png',"System $chglbl");
	}elseif($c == 'nedt'){
		return array('img/16/temp.png',$tmplbl);
	}elseif($c == 'nedu'){
		return array('img/16/mark.png','Supplies');
	}elseif($c == 'secf'){
		return array('img/16/nods.png',"MAC Flood");
	}elseif($c == 'secj'){
		return array('img/16/net.png',"IP $chglbl");
	}elseif($c == 'secn'){
		return array('img/16/add.png',"$stco[10] Node");
	}elseif($c == 'secp'){
		return array('img/16/drop.png','ARP Poison');
	}elseif($c == 'secs'){
		return array('img/16/step.png','Stolen');
	}elseif($c == 'mast'){
		return array('img/16/hat3.png','Master');
	}elseif($c == 'lnc'){
		return array('img/16/link.png',"$cnclbl $chglbl");
	}elseif( strpos($c,'ti') ){
		return array('img/16/bbup.png',"$if $inblbl $trflbl");
	}elseif( strpos($c,'to') ){
		return array('img/16/bbdn.png',"$if $oublbl $trflbl");
	}elseif( strpos($c,'ei') ){
		return array('img/16/brup.png',"$if $inblbl $errlbl");
	}elseif( strpos($c,'eo') ){
		return array('img/16/brdn.png',"$if $oublbl $errlbl");
	}elseif( strpos($c,'di') ){
		return array('img/16/bbu2.png',"$if $inblbl $dcalbl");
	}elseif( strpos($c,'do') ){
		return array('img/16/bbd2.png',"$if $oublbl $dcalbl");
	}elseif( strpos($c,'bi') ){
		return array('img/16/brc.png',"$if $inblbl Broadcast");
	}elseif( strpos($c,'op') ){
		return array('img/16/swit.png',"$if $stalbl $chglbl");
	}elseif( strpos($c,'ad') ){
		return array('img/16/bdis.png',"$if $dislbl");
	}elseif($c == 'bugn'){
		return array('img/16/bug.png','Debug');
	}elseif($c == 'bugx'){
		return array('img/16/bug.png','Extended Debug');
	}elseif(strpos($c,'cfg') !== false){
		return array('img/16/conf.png',$cfglbl);
	}elseif(strpos($c,'mon') !== false){
		return array('img/16/bino.png','Monitoring');
	}elseif(strpos($c,'usr') !== false){
		return array('img/16/user.png',$usrlbl);
	}else{
		return array('img/16/say.png',$mlvl['10']);
	}
}

//===================================================================
// Return icon for an incident group
function IncImg($cat){

	if($cat == 1)		{return "add";}
	elseif($cat == 11)	{return "flas";}
	elseif($cat == 12)	{return "dril";}
	elseif($cat == 13)	{return "star";}
	elseif($cat == 14)	{return "ncon";}
	elseif($cat == 15)	{return "ele";}
	elseif($cat == 16)	{return "wthr";}
	elseif($cat < 20)	{return "home";}
	elseif($cat == 21)	{return "batt";}
	elseif($cat == 22)	{return "dev";}
	elseif($cat == 23)	{return "cubs";}
	elseif($cat == 24)	{return "cbox";}
	elseif($cat == 25)	{return "grph";}
	elseif($cat < 30)	{return "cinf";}
	elseif($cat == 31)	{return "ncfg";}
	elseif($cat == 32)	{return "conf";}
	elseif($cat == 33)	{return "eyes";}
	elseif($cat == 34)	{return "hat";}
	elseif($cat < 40)	{return "user";}
	else			{return "bbox";}
}

//===================================================================
// Return bg color based on monitoring status
function StatusBg($nd,$mn,$alerts,$bg=''){

	global $pause,$tim,$errlbl,$mullbl,$alllbl,$stco;

	$partial = ($nd == $mn)?"":" part";
	if ($mn == 1){
		$onetgt = ($nd == 1)?"":", 1 $stco[200] ";
		$out = $alerts * $pause;
		if( $out > 86400){
			return array("crit$partial",(intval($out/8640)/10)." $tim[d]");
		}elseif( $out > 3600){
			return array("crit$partial",(intval($out/360)/10)." $tim[h]");
		}elseif( $out > 600){
			return array("alrm$partial",(intval($out/6)/10)." $tim[i]");
		}elseif( $out ){
			return array("warn$partial","$out $tim[s]");
		}else{
			return array("good$partial","OK");
		}
	}elseif ($mn > 1){
		if($alerts > 1){
			return array("crit$partial","$mullbl $errlbl");
		}elseif($a){
			return array("alrm$partial","$errlbl $tim[n]");
		}else{
			return array("good$partial","$alllbl OK");
		}
	}else{
		return array ($bg,'');
	}
}

//===================================================================
// Generate Target status table
function StatusMon($srrd=0){

	global $link,$monctr,$monarr,$rrdstep,$mlvl,$stco,$tgtlbl,$laslbl,$dsclbl,$nonlbl,$avglbl,$latlbl,$debug;

	$query	= GenQuery('devices','s','count(lastdis)','','',array('lastdis'),array('>'),array(time() - $rrdstep));
	$res	= DbQuery($query,$link);
	if($res){
		$ndsc = DbFetchRow($res);
		DbFreeResult($res);
	}else{
		print DbError($link);
	}

	$mtit = "$monctr[1]/$monctr[0] $tgtlbl OK, $ndsc[0] Devices $laslbl $dsclbl";

	if(!$srrd or $srrd == 6){
		echo "<img src=\"img/32/bino.png\" title=\"$mtit\">\n";
	}else{
?>
<a href="Devices-Graph.php?dv=Totals&if[]=mon&sho=1"><img src="inc/drawrrd.php?t=mon&s=<?= $srrd ?>" title="<?= $avalbl ?> <?= $gralbl ?> (<?= $mtit ?>)"></a>
<a href="Devices-Graph.php?dv=Totals&if[]=msg&sho=1"><img src="inc/drawrrd.php?t=msg&s=<?= $srrd ?>" title="<?= $msglbl ?> <?= $sumlbl ?>"></a>
<?php
	}

	if($debug){
		echo '<div class="textpad code alrm">';
		print_r($monctr);
		print_r($monarr);
		echo "</div>\n";
	}

	if( $monctr[2] or $monctr[3] ){
		if( $monctr[2] == 1 ){
			if(!$srrd or $srrd == 6){echo "<img src=\"img/32/fobl.png\" title=\"1 $mlvl[200]\">";}
			if($_SESSION['vol']){echo "<embed src=\"inc/alarm1.mp3\" volume=\"$_SESSION[vol]\" hidden=\"true\">\n";}
		}elseif( $monctr[2] and $monctr[2] < 10 ){
			if($ni[0] < 3){
				$ico = "fovi";
			}elseif($ni[0] < 5){
				$ico = "foye";
			}else{
				$ico = "foor";
			}
			if(!$srrd or $srrd == 6){echo "<img src=\"img/32/$ico.png\" title=\"$nalr $mlvl[200]\">";}
			if($_SESSION['vol']){echo "<embed src=\"inc/alarm2.mp3\" volume=\"$_SESSION[vol]\" hidden=\"true\">\n";}
		}elseif($monctr[2]){
			if(!$srrd or $srrd == 6){echo "<img src=\"img/32/ford.png\" title=\"$nalr $mlvl[200]!\">";}
			if($_SESSION['vol']){echo "<embed src=\"inc/alarm3.mp3\" volume=\"$_SESSION[vol]\" hidden=\"true\">\n";}
		}
?>
<p>
<table class="content">
	<tr class="bgsub">
		<th>
			<img src="img/16/trgt.png"><br><?= $tgtlbl ?>

		</th>
		<th class="m">
			<img src="img/16/flag.png"><br><?= $mlvl['200'] ?>

		</th>
	</tr>
<?php
		$row = 0;
		foreach(array_keys($monarr) as $t){
			if( $monarr[$t]['st'] or  $monarr[$t]['lw'] < $monarr[$t]['la'] ){
				if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
				$row++;
				echo "	<tr>\n		<td class=\"$bi lft b\">\n			";
				if( $srrd != 6 ) echo "<a href=\"Monitoring-Setup.php?in[]=name&op[]=%3D&st[]=".urlencode($t)."\">".TestImg($monarr[$t]['te'])."</a> ";
				list($statbg,$stat) = StatusBg(1,1,$monarr[$t]['st'],$bi);
				if( $monarr[$t]['st'] ){
					echo substr($t,0,$_SESSION['lsiz'])."\n		</td>\n		<td class=\"$statbg lft b\">\n			$stat\n		</td>\n	</tr>\n";
				}elseif( $monarr[$t]['lw'] < $monarr[$t]['la'] ){
					echo substr($t,0,$_SESSION['lsiz'])."\n		</td>\n		<td class=\"$statbg lft\">".Bar($monarr[$t]['la'],$monarr[$t]['lw'],'si')." ".$monarr[$t]['la']."ms\n		</td>\n	</tr>\n";
				}
			}
		}
?>
</table>
<?php
	}else{
		if($monctr[1]){
			if(!$srrd or $srrd == 6){echo "<img src=\"img/32/bchk.png\" title=\"Monitoring $srvlbl $stco[100]\">";}
		}else{
			if(!$srrd or $srrd == 6){echo "<img src=\"img/32/bcls.png\" title=\"$nonlbl OK\">";}
			if($_SESSION['vol']){echo "<embed src=\"inc/enter2.mp3\" volume=\"$_SESSION[vol]\" hidden=\"true\">\n";}
		}
		if($monctr[4] and (!$srrd or $srrd == 6) ) echo "<h3><img src=\"img/32/clock.png\" title=\"$avglbl $latlbl\">$monctr[4] ms</h3>\n";
	}
}

//===================================================================
// Generate If status tables
function StatusIf($loc,$mode,$srrd){

	global $link,$rrdstep,$trfa,$trflbl,$errlbl,$inblbl,$oublbl,$tim,$firstmsg;

	if($mode   == "brup"){
		$label = "$inblbl $errlbl";
		$query = GenQuery('interfaces','s','device,ifname,speed,iftype,dinerr','dinerr desc',$_SESSION['lim'],array('dinerr','iftype','location'),array('>','!=','LIKE'),array("$rrdstep",71,$loc),array('AND','AND'),'JOIN devices USING (device)');
	}elseif($mode  == "brdn"){
		$label = "$oublbl $errlbl";
		$query = GenQuery('interfaces','s','device,ifname,speed,iftype,douterr','douterr desc',$_SESSION['lim'],array('douterr','iftype','location'),array('>','!=','LIKE'),array("$rrdstep",71,$loc),array('AND','AND'),'JOIN devices USING (device)');
	}elseif($mode  == "bbup"){
		$label = "$inblbl $trflbl";
		$query = GenQuery('interfaces','s',"device,ifname,speed,iftype,dinoct/speed/$rrdstep*800",'dinoct/speed desc',$_SESSION['lim'],array('speed',"dinoct/speed/$rrdstep*800",'iftype','location'),array('>','>','!=','LIKE'),array(0,$trfa,53,$loc),array('AND','AND','AND'),'JOIN devices USING (device)');
	}elseif($mode  == "bbdn"){
		$label = "$oublbl $trflbl";
		$query = GenQuery('interfaces','s',"device,ifname,speed,iftype,doutoct/speed/$rrdstep*800",'doutoct/speed desc',$_SESSION['lim'],array('speed',"doutoct/speed/$rrdstep*800",'iftype','location'),array('>','>','!=','LIKE'),array(0,$trfa,53,$loc),array('AND','AND','AND'),'JOIN devices USING (device)');
	}elseif($mode  == "bdis"){
		$label = "Disabled $tim[t]";
		$query = GenQuery('interfaces','s','device,ifname,speed,iftype,ifstat,lastchg','lastchg desc',$_SESSION['lim'],array('ifstat','iftype','lastchg','location'),array('=','!=','>','LIKE'),array('0','53',$firstmsg,$loc),array('AND','AND'),'JOIN devices USING (device)');
	}
	$res	= DbQuery($query,$link);
	if($res){
		$nr = DbNumRows($res);
		if($nr){
?>
<p>
<table class="content"><tr class="bgsub">
<th colspan="2"><img src="img/16/port.png" title="Top <?= $_SESSION['lim'] ?>"><br>Interface</th><th><img src="img/16/<?= $mode ?>.png" title="<?= $label ?>"><br><?= (substr($label,0,3)) ?></th>
<?php
			$row = 0;
			while( ($r = DbFetchRow($res)) ){
				if ($row % 2){$bg = "txta"; $bi = "imga";$off=200;}else{$bg = "txtb"; $bi = "imgb";$off=185;}
				$row++;
				$bg3= sprintf("%02x",$off);
				$tb = ($type)?$r[4]*5:($r[4]-$trfa)*2;
				if ($tb > 55){$tb = 55;}
				$rb = sprintf("%02x",$tb + $off);
				$t  = substr($r[0],0,strpos($r[0],'.') );
				$t  = (strlen($t) < 4)?$r[0]:$t;
				$ud = urlencode($r[0]);
				$ui = urlencode($r[1]);
				if($mode == "bdis"){
					$rb = $bg3;
					$stat = date($_SESSION['timf'],$r[5]);
				}elseif($mode == "brup" or $mode == "brdn"){
					$stat = DecFix($r[4]);
				}else{
					$stat = sprintf("%1.1f",$r[4])." %";
				}
				list($ifimg,$iftit) = Iftype($r[3]);
				echo "<tr class=\"$bg\"><th class=\"$bi s\"><a href=\"Devices-Interfaces.php?in[]=device&op[]==&st[]=$ud&co[]=AND&in[]=ifname&op[]==&st[]=$ui&col[]=imBL&col[]=ifname&col[]=alias&col[]=comment&col[]=poNS&col[]=gfNS&col[]=rdrNS\"><img src=img/$ifimg title=\"$iftit\"></a>";
				if($srrd == 6){
					echo "</th><td>$t $r[1]</td><th bgcolor=\"#$rb$rb$bg3\">";
				}else{
					echo "</th><td><a href=\"Devices-Status.php?dev=$ud&pop=on\">$t</a> ";
					echo "<a href=Nodes-List.php?in[]=device&op[]==&st[]=$ud&co[]=AND&in[]=ifname&op[]==&st[]=$ui>$r[1]</a>$r[1]</a> ".DecFix($r[2])."</td><th bgcolor=\"#$rb$rb$bg3\">\n";
				}
				echo "$stat</th></tr>\n";
			}
			echo "</table>\n";
		}elseif(!$srrd or $srrd == 6){
?>
<p><img src="img/32/<?= $mode ?>.png" title="<?= $label ?>" hspace="8"><img src="img/32/bchk.png" title="OK">
<?php
		}
		DbFreeResult($res);
	}else{
		print DbError($link);
	}
}

//===================================================================
// Generate cpu status table
function StatusCpu($loc,$srrd){

	global $link,$tgtlbl,$lodlbl,$limlbl;

	$query = GenQuery('monitoring','s','name,cpu,cpualert','cpu desc',$_SESSION['lim'],array('cpu','location'),array('COL >','LIKE'),array('cpualert',$loc),array('AND'),'LEFT JOIN devices USING (device)');
	$res	= DbQuery($query,$link);
	if($res){
		$nr = DbNumRows($res);
		if($nr){
?>
<p>
<table class="content">
	<tr class="bgsub">
		<th colspan="2" class="nw"><img src="img/16/trgt.png" title="Top <?= $_SESSION['lim'] ?> CPU <?= $lodlbl ?>"><br><?= $tgtlbl ?></th>
		<th class="nw"><img src="img/16/cpu.png"><br><?= $lodlbl ?></th>
	</tr>
<?php
			$row = 0;
			while( ($t = DbFetchRow($res)) ){
				if ($row % 2){$bg = "txta"; $bi = "imga";$off="b8";}else{$bg = "txtb"; $bi = "imgb";$off="c8";}
				$row++;
				$lv  = $t[1]-$r[2];
				$hi  = sprintf("%02x",(($lv > 55)?55:$lv) + 200);
				$na  = substr($t[0],0,$_SESSION['lsiz']);
				$ud  = urlencode($t[0]);
				if($srrd == 6){
					echo "\t<tr bgcolor=\"#$hi$off$off\">\n\t\t<td class=\"$bi ctr b\">$row</td>\n\t\t<td>$na</td>\n\t\t<td class=\"nw\">$t[1]%</td>\n\t</tr>\n";
				}else{
					echo "\t<tr bgcolor=\"#$hi$off$off\">\n\t\t<td class=\"$bi ctr b\">$row</td>\n\t\t<td><a href=\"Monitoring-Setup.php?in[]=name&op[]=%3D&st[]=$ud\">$na</a></td>\n";
					echo "\t\t<td class=\"nw\" title=\"$limlbl $t[2]%\">$t[1]%</td>\n\t</tr>\n";
				}
			}
			echo "</table>\n";
		}else{
			$isiz = ($srrd == 2)?"16":"32";
?>
<p>
<img src="img/<?= $isiz ?>/cpu.png" title="CPU <?= $lodlbl ?>" hspace="8"> <img src="img/<?= $isiz ?>/bchk.png" title="OK">
<?php
		}
		DbFreeResult($res);
	}else{
		print DbError($link);
	}
}

//===================================================================
// Generate mem availabilty table
function StatusMem($loc,$srrd){#TODO like cpu

	global $link,$tgtlbl,$limlbl,$frelbl,$memlbl;

	$aquery = GenQuery('monitoring','s','name,memcpu,memalert','memcpu desc',$_SESSION['lim'],array('memcpu/1024','memcpu','location'),array('COL <','>','LIKE'),array('memalert',100,$loc),array('AND','AND'),'LEFT JOIN devices USING (device)');
	$ares	= DbQuery($aquery,$link);
	$nar    = DbNumRows($ares);

	$pquery = GenQuery('monitoring','s','name,memcpu,memalert','memcpu desc',$_SESSION['lim'],array('memcpu','memcpu','memcpu','location'),array('COL <','>','<','LIKE'),array('memalert',0,100,$loc),array('AND','AND','AND'),'LEFT JOIN devices USING (device)');
	$pres	= DbQuery($pquery,$link);
	$npr    = DbNumRows($pres);

	if($nar or $npr){
?>
<p>
<table class="content">
	<tr class="bgsub">
		<th colspan="2"><img src="img/16/trgt.png" title="Top <?= $_SESSION['lim'] ?> <?= $memlbl ?> <?= $frelbl ?>"><br><?= $tgtlbl ?></th>
		<th class="nw"><img src="img/16/mem.png"><br><?= $frelbl ?></th>
	</tr>
<?php
		$row = 0;
		while( ($t = DbFetchRow($ares)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";$off="b8";}else{$bg = "txtb"; $bi = "imgb";$off="c8";}
			$row++;
			$lv  = pow($ma[0]*1024/$t[1],8);
			$hi  = sprintf("%02x",(($lv > 55)?55:$lv) + 200);
			$na  = substr($t[0],0,$_SESSION['lsiz']);
			$ud  = urlencode($t[0]);
			if($srrd == 6){
				echo "\t<tr bgcolor=\"#$hi$hi$off\">\n\t\t<td class=\"$bi ctr b\">$row</td>\n\t\t<td>$na</td>\n\t\t<td class=\"nw\">".DecFix($t[1])."B</td>\n\t</tr>\n";
			}else{
				echo "\t<tr bgcolor=\"#$hi$hi$off\">\n\t\t<td class=\"$bi ctr b\">$row</td>\n\t\t<td><a href=Monitoring-Setup.php?in[]=name&op[]=%3D&st[]=$ud>$na</a></td>\n";
				echo "\t\t<td class=\"nw rgt b\" title=\"$limlbl ".DecFix($t[2]*1024)."B\">".DecFix($t[1])."B</td>\n\t</tr>\n";
			}
		}
		while( ($t = DbFetchRow($pres)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";$off="b8";}else{$bg = "txtb"; $bi = "imgb";$off="c8";}
			$row++;
			$lv  = $t[1]-$m[1];
			$hi  = sprintf("%02x",(($lv > 55)?55:$lv) + 200);
			$na  = substr($t[0],0,$_SESSION['lsiz']);
			$ud  = urlencode($t[0]);
			if($srrd == 6){
				echo "<tr bgcolor=\"#$hi$hi$off\"><th class=\"$bi\">$row</th><td>$na</td><th class=\"nw\">$t[1]%</th></tr>\n";
			}else{
				echo "<tr bgcolor=\"#$hi$hi$off\"><th class=\"$bi\">$row</th><td><a href=Monitoring-Setup.php?in[]=name&op[]=%3D&st[]=$ud>$na</a></td>\n";
				echo "<td class=\"nw rgt b\" title=\"$limlbl $t[2]%\">$t[1]%</td></tr>\n";
			}
		}
		echo "</table>\n";
	}else{
		$isiz = ($srrd == 2)?"16":"32";
?>
<p>
<img src="img/<?= $isiz ?>/mem.png" title="<?= $memlbl ?> <?= $frelbl ?>" hspace="8"> <img src="img/<?= $isiz ?>/bchk.png" title="OK">
<?php
	}
	DbFreeResult($ares);
	DbFreeResult($pres);
}

//===================================================================
// Generate temperature status table
function StatusTmp($loc,$srrd){

	global $link,$tmpa,$tgtlbl,$tmplbl,$limlbl;

	$query = GenQuery('monitoring','s','name,temp,tempalert','temp desc',$_SESSION['lim'],array('temp','location'),array('COL >','LIKE'),array('tempalert',$loc),array('AND'),'LEFT JOIN devices USING (device)');
	$res	= DbQuery($query,$link);
	if($res){
		$nr = DbNumRows($res);
		if($nr){
?>
<p>
<table class="content">
	<tr class="bgsub">
		<th colspan="2"><img src="img/16/trgt.png" title="Top <?= $_SESSION['lim'] ?> <?= $tmplbl ?>"><br><?= $tgtlbl ?></th>
		<th><img src="img/16/temp.png"><br>Temp</th>
	</tr>
<?php
			$row = 0;
			while( ($t = DbFetchRow($res)) ){
				if ($row % 2){$bg = "txta"; $bi = "imga";$off="b8";}else{$bg = "txtb"; $bi = "imgb";$off="c8";}
				$row++;
				$lv  = pow(($t[1]-$tmpa),2);
				$hi  = sprintf("%02x",(($lv > 55)?55:$lv) + 200);
				$na  = substr($t[0],0,$_SESSION['lsiz']);
				$ud  = urlencode($t[0]);
				if($srrd == 6){
					echo "\t<tr bgcolor=\"#$hi$off$hi\"><td class=\"$bi ctr b\">$row</td>\n\t\t<td>$na</td>\n\t\t<td class=\"nw\">$t[1]C</td></tr>\n";
				}else{
					echo "\t<tr bgcolor=\"#$hi$off$hi\">\n\t\t<td class=\"$bi ctr b\">$row</td>\n\t\t<td><a href=\"Monitoring-Setup.php?in[]=name&op[]=%3D&st[]=$ud\">$na</a></td>\n";
					echo "\t\t<td class=\"nw rgt b\" title=\"$limlbl $t[2]C\">$t[1]C</td></tr>\n";
				}
			}
			echo "</table>\n";
		}else{
			$isiz = ($srrd == 2)?"16":"32";
?>
<p>
<img src="img/<?= $isiz ?>/temp.png" title="<?= $tmplbl ?>" hspace="8"> <img src="img/<?= $isiz ?>/bchk.png" title="OK">
<?php
		}
		DbFreeResult($res);
	}else{
		print DbError($link);
	}
}

//===================================================================
// Show unacknowledged incidents
function StatusIncidents($loc,$srrd,$opt=0){

	global $link,$levlbl,$inclbl,$sttlbl,$endlbl,$tgtlbl,$loclbl,$conlbl,$acklbl,$nonlbl,$mbak,$mico,$locsep;

	$ilnk = ($srrd == 6)?'mh.php':'Monitoring-Incidents.php?grp=1';
	$isiz = ($srrd == 2)?'16':'32';

	if($opt){
		$query	= GenQuery('incidents','s','level,name,start,end,device,location,contact,type,readcomm','id desc',$_SESSION['lim'],array('time','location'),array('=','LIKE'),array(0,$loc),array('AND'),'LEFT JOIN devices USING (device)');
		$res	= DbQuery($query,$link);
		if($res){
			$nr = DbNumRows($res);
			if($nr){
?>
<p>
<table class="content">
	<tr class="bgsub">
		<th class="s"><img src="img/16/idea.png"><br><?= $levlbl ?></th>
		<th><img src="img/16/trgt.png"><br><?= $tgtlbl ?></th>
		<th><img src="img/16/bblf.png"><br><?= $sttlbl ?></th>
		<th><img src="img/16/bbrt.png"><br><?= $endlbl ?></th>
		<th><img src="img/16/dev.png"><br>Device</th>
		<th><img src="img/16/home.png"><br><?= $loclbl ?></th>
		<th><img src="img/16/umgr.png"><br><?= $conlbl ?></th>
</tr>
<?php
				$row = 0;
				while( ($i = DbFetchRow($res)) ){
					if ($row % 2){$bg = "txta"; $bi = "imga";$off="b8";}else{$bg = "txtb"; $bi = "imgb";$off="c8";}
					$row++;
					$ut  = urlencode($i[1]);
					$ud  = urlencode($i[4]);
					echo "\t<tr class=\"$bg\">\n";
					echo "\t\t<td class=\"".$mbak[$i[0]]." ctr\"><img src=\"img/16/" . $mico[$i[0]] . ".png\" title=\"" . $mlvl[$i[0]] . "\"></td>\n";
					echo "\t\t<td><a href=\"$i[8]://$ud/Monitoring-Setup.php?in[]=name&op[]=%3D&st[]=$ut\">".substr($i[1],0,$_SESSION['lsiz'])."</a></td>\n";
					echo "\t\t<td>".date($_SESSION['timf'],$i[2])."</td><td ".(($i[3])?">".date($_SESSION['timf'],$i[3]):"class=\"warn\">-")."</td>\n";
					echo "\t\t<td><a href=\"$i[8]://$ud/Monitoring-Incidents.php?grp=1\">".substr($i[4],0,$_SESSION['lsiz'])."</a></td>\n";
					$l = explode($locsep, $i[5]);
					echo "\t\t<td>".substr("$l[1], $l[0]",0,$_SESSION['lsiz'])."</td>\n\t\t<td>$i[6]</td>\n\t</tr>\n";
				}
				echo "</table>\n";
				if($nr == 1){
					if($_SESSION['vol']){echo "<embed src=\"inc/alarm1.mp3\" volume=\"$_SESSION[vol]\" hidden=\"true\">\n";}
				}elseif($nr < 10){
					if($_SESSION['vol']){echo "<embed src=\"inc/alarm2.mp3\" volume=\"$_SESSION[vol]\" hidden=\"true\">\n";}
				}else{
					if($_SESSION['vol']){echo "<embed src=\"inc/alarm2.mp3\" volume=\"$_SESSION[vol]\" hidden=\"true\">\n";}
				}
			}else{
?>
<p>
<img src="img/<?= $isiz ?>/bomb.png" title="<?= $inclbl ?>" hspace="8"> <img src="img/<?= $isiz ?>/bchk.png" title="<?= $nonlbl ?>">
<?php
			}
			DbFreeResult($res);
		}else{
			print DbError($link);
		}
	}else{
		$ico = "fogy";
		$inctit = "?";
		$query	= GenQuery('incidents','s','count(*)','','',array('time','location'),array('=','LIKE'),array(0,$loc),array('AND'),'LEFT JOIN devices USING (device)');
		$res	= DbQuery($query,$link);
		if($res){
			$ni = DbFetchRow($res);
			$inctit = $ni[0];
			if($ni[0] == 0){
				$ico = "bchk";
				$inctit = $nonlbl;
			}elseif($ni[0] == 1){
				$ico = "fobl";
			}elseif($ni[0] < 3){
				$ico = "fovi";
			}elseif($ni[0] < 5){
				$ico = "foye";
			}elseif($ni[0] < 10){
				$ico = "foor";
			}else{
				$ico = "ford";
			}
		}else{
			print DbError($link);
		}
?>
<p>
<a href="<?= $ilnk ?>"><img src="img/<?= $isiz ?>/bomb.png" title="<?= $inclbl ?>" hspace="8">
<img src="img/<?= $isiz ?>/<?= $ico ?>.png" title="<?= $acklbl ?>: <?= $inctit ?>"></a>
<p>
<?php
	}
}

//===================================================================
// Displays Events based on query (mod 0=full, 1=full-joindev 2=full-master 3=small, 4=mobile)
function Events($lim,$in,$op,$st,$co,$mod=0){

	global $link,$bg,$bi,$mico,$mbak,$mlvl,$noiplink;
	global $gralbl,$lstlbl,$levlbl,$timlbl,$tgtlbl,$srclbl,$monlbl,$msglbl,$stalbl,$cfglbl,$cmdlbl,$nonlbl,$clalbl,$limlbl;

		if($mod){										# Need to join dev due to 'filter' or 'view'
			$query = GenQuery('events','s','id,level,time,source,info,class,device,type,readcomm','id desc',$lim,$in,$op,$st,$co,'LEFT JOIN devices USING (device)');
		}else{											# No join is faster
			$query = GenQuery('events','s','id,level,time,source,info,class,device','id desc',$lim,$in,$op,$st,$co);
		}
	$res	= DbQuery($query,$link);
	if($res){
		$nmsg = DbNumRows($res);
		if($nmsg){
			$row  = 0;
			if($mod > 2){
				if($mod == 3){
?>
<table class="content">
	<tr>
		<th class="s"><img src="img/16/idea.png"><br><?= $levlbl ?></th>
		<th><img src="img/16/clock.png"><br><?= $timlbl ?></th>
		<th><img src="img/16/say.png"><br><?= $srclbl ?></th>
		<th><img src="img/16/find.png"><br>Info</th>
	</tr>
<?php
				}
				while( ($m = DbFetchRow($res)) ){
					if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
					$row++;
					$time = date($_SESSION['timf'],$m[2]);
					$fd   = urlencode(date("m/d/Y H:i:s",$m[2]));
					$usrc = urlencode($m[3]);
					$ssrc = substr($m[3],0,$_SESSION['lsiz']);
					$sinf = (strlen($m[4]) > 60)?substr($m[4],0,60)."...":$m[4];
					if($mod == 3){
						TblRow($bg);
						echo "\t\t<td class=\"".$mbak[$m[1]]." ctr\"><a href=\"Monitoring-Events.php?lvl=$m[1]\"><img src=\"img/16/". $mico[$m[1]] .".png\" title=\"". $mlvl[$m[1]] ."\"></a></td>\n";
						echo "\t\t<td><a href=\"Monitoring-Events.php?in[]=time&op[]==&st[]=$fd\">$time</a></td>\n";
						echo "\t\t<td class=\"nw\"><a href=\"Monitoring-Events.php?in[]=source&op[]==&st[]=$usrc\">$ssrc</a></td>\n\t\t<td>$sinf</td>\n\t</tr>\n";
					}else{								# Mobile mode, mh.php
						echo "\t<tr class=\"".$mbak[$m[1]]."\">\n\t\t<td class=\"nw\">$ssrc</td>\n\t\t<td>$time</td>\n\t\t<td>$sinf</td>\t</tr>\n";
					}
				}
				echo "</table>\n";
			}else{
?>
<table class="content">
	<tr class="bgsub">
		<th class="s"><img src="img/16/key.png"><br>Id</th>
		<th class="xs"><img src="img/16/idea.png" title="10=<?= $mlvl['10'] ?>,50=<?= $mlvl['50'] ?>, 100=<?= $mlvl['100'] ?>, 150=<?= $mlvl['150'] ?>, 200=<?= $mlvl['200'] ?>, 250=<?= $mlvl['250'] ?>"><br><?= $levlbl ?></th>
		<th><img src="img/16/clock.png"><br><?= $timlbl ?></th>
		<th><img src="img/16/say.png" title="<?= $monlbl ?> <?= $tgtlbl ?> || IP (<?= $msglbl ?> <?= $levlbl ?> < 50)"><br><?= $srclbl ?></th>
		<th class="xs"><img src="img/16/abc.png" title="<?= $msglbl ?> <?= $clalbl ?>:<?= $cmdlbl ?>"><br><?= $clalbl ?></th>
		<th><img src="img/16/find.png"><br>Info</th>
	</tr>
<?php
				while( ($m = DbFetchRow($res)) ){
					if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
					$row++;
					$time = date($_SESSION['timf'],$m[2]);
					$fd   = urlencode(date("m/d/Y H:i:s",$m[2]));
					$usrc = urlencode($m[3]);
					$utgt = urlencode($m[6]);
					list($ei,$et)   = EvClass($m[5]);
					TblRow($bg);
					echo "\t\t<td><a href=\"Monitoring-Events.php?in[]=id&op[]==&st[]=$m[0]\">$m[0]</a></td>\n";
					echo "\t\t<td class=\"".$mbak[$m[1]]." ctr\"><a href=\"Monitoring-Events.php?in[]=level&op[]==&st[]=$m[1]&co[]=$co[0]&in[]=$in[1]&op[]=$op[1]&st[]=".urlencode($st[1])."\"><img src=\"img/16/". $mico[$m[1]] .".png\" title=\"". $mlvl[$m[1]] ."\"></a></td>\n";
					echo "\t\t<td><a href=\"Monitoring-Events.php?in[]=time&op[]==&st[]=$fd\">$time</a></td>\n";
					if($mod == 1 and $m[7] == 'NeDi Agent'){
						$agnt = "$m[8]://$utgt/";
						$alnk = "on <a href=\"Devices-Status.php?dev=$utgt\">$utgt</a> ";
					}else{
						$agnt  = '';
						$alnk  = '';
					}
					echo "\t\t<td><a href=\"Monitoring-Events.php?in[]=source&op[]==&st[]=$usrc\"><strong>$m[3]</strong></a> $alnk</td>\n";

					$action = "<a href=\"${agnt}Devices-Status.php?dev=$usrc&pop=1\"><img src=\"$ei\" title=\"$et ($m[5]), Device $stalbl\"></a>";
					if($m[5] == "node"){			# Syslog from a node
						$action = "<a href=\"${agnt}Nodes-List.php?in[]=name&op[]==&st[]=$m[3]\"><img src=\"$ei\" title=\"$et ($m[5]), Node $lstlbl\"></a>";
					}elseif($m[3] == "NeDi"){		# Not related to a dev or node!
						$action = "<a href=\"${agnt}System-Files.php\"><img src=\"$ei\" title=\"$et ($m[5]), NeDi $cfglbl\"></a>";
					}elseif($m[5] == "moni"){		# Monitoring events
						$action = "<a href=\"${agnt}Monitoring-Setup.php?in[]=name&op[]=%3D&st[]=$usrc\"><img src=\"$ei\" title=\"$et ($m[5]), Monitoring Setup\"></a>";
					}elseif($m[5] == "cfgn" or $m[5] == "cfgc"){	# New config or changes
						$action =  "<a href=\"Devices-Config.php?shc=$usrc\"><img src=\"$ei\" title=\"$et ($m[5]), Device $cfglbl\"></a>";
					}elseif($m[5] == "secs"){		# Security Stolen
						$action = "<a href=\"${agnt}Nodes-Stolen.php\"><img src=\"$ei\" title=\"$et ($m[5]), Nodes Stolen\"></a>";
					}elseif($m[1] < 50){
						$action = "<a href=\"${agnt}Nodes-List.php?in[]=nodip&op[]==&st[]=$m[3]\"><img src=\"$ei\" title=\"$et ($m[5]), Node $lstlbl\"></a>";
					}
					echo "\t\t<td class=\"$bi ctr\">$action</td>\n\t\t<td>";
					if($noiplink or isset($_GET['print'])){
						$info = preg_replace('/[\s:]([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})(\s|:|,|$)/', " <span class=\"blu\">$1</span> ", $m[4]);
						echo preg_replace('/[\s:]([0-9a-f]{4}[\.-]?[0-9a-f]{4}[\.-]?[0-9a-f]{4}|[0-9a-f]{2}[-:][0-9a-f]{2}[-:][0-9a-f]{2}[-:][0-9a-f]{2}[-:][0-9a-f]{2}[-:][0-9a-f]{2})(\s|$)/', " <span class=\"mrn\">$1</span> ", $info);
					}else{
						$info = preg_replace('/[\s:]([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})(\s|:|,|$)/', " <span class=\"blu\">$1</span>
			<a href=\"Nodes-Toolbox.php?Dest=$1\"><img src=\"img/16/dril.png\" title=\"Lookup\"></a>
			<a href=\"Nodes-List.php?in[]=nodip&op[]=%3D&st[]=$1\"><img src=\"img/16/nods.png\" title=\"Nodes $lstlbl\"></a>
			<a href=\"?in[]=info&op[]=~&st[]=$1\"><img src=\"img/16/bell.png\" title=\"Monitoring-Events\"></a>", $m[4]);
						echo preg_replace('/[\s:]([0-9a-f]{4}[\.-]?[0-9a-f]{4}[\.-]?[0-9a-f]{4}|[0-9a-f]{2}[-:][0-9a-f]{2}[-:][0-9a-f]{2}[-:][0-9a-f]{2}[-:][0-9a-f]{2}[-:][0-9a-f]{2})(\s|$)/', " <span class=\"mrn\">$1</span><a href=\"Nodes-Status.php?mac=$1\"><img src=\"img/16/node.png\" title=\"Node $stalbl\"></a>\n", $info);
					}
					echo "</td>\n\t</tr>\n";
				}
				TblFoot("bgsub", 6, "$row $msglbl".(($lim)?", $limlbl: $lim":"") );
			}
		}else{
			echo "<p><h5>$nonlbl</h5>";
		}
		DbFreeResult($res);
	}else{
		print DbError($link);
	}
}

//===================================================================
// Generate topology based monitoring metainfo
function TopoMon($loc){

	global $link,$pause,$monctr,$monarr;

	$monctr = array(0,0,0,0);
	$monarr = array();
	$query  = GenQuery('monitoring','s','name,test,lastok,status,latency,latwarn','status desc','',array('test','location'),array('!=','LIKE'),array('none',$loc),array('AND'),'LEFT JOIN devices USING (device)');# Need every monitored target, thus no further optimization!
	$res    = DbQuery($query,$link);
	if($res){
		while( ($m = DbFetchRow($res)) ){
			if($m[2] > (time() - 2*$pause) ){$monctr[1]++;}
			$monarr[$m[0]]['te'] = $m[1];
			$monarr[$m[0]]['st'] = $m[3];
			$monarr[$m[0]]['la'] = $m[4];
			$monctr[4] += $m[4];
			$monarr[$m[0]]['lw'] = $m[5];
			if($m[3]) $monctr[2]++;
			if($m[4] > $m[5]) $monctr[3]++;
			$monctr[0]++;
		}
		if($monctr[0]) $monctr[4] = round($monctr[4]/$monctr[0],1);

		DbFreeResult($res);
	}
}

//===================================================================
// Generate device metainfo for topology based device tables
function TopoTable($reg="",$cty="",$bld="",$flr="",$rom="",$nsd=0){

	global $link,$dev,$noloc,$monarr,$dreg,$dcity,$dbuild,$locsep,$bldsep,$now,$retire;

	$dreg = array();

	if($nsd){
		$query	= GenQuery('devices','s','*','','',array('location'),array('LIKE'),array( TopoLoc($reg,$cty,$bld,$flr,$rom) ) );
	}else{
		$query	= GenQuery('devices','s','*','','',array('snmpversion','location'),array('>','LIKE'),array('0',TopoLoc($reg,$cty,$bld,$flr,$rom)),array('AND') );
	}
	$res	= DbQuery($query,$link);
	if($res){
		while( ($d = DbFetchRow($res)) ){
			$mn = array_key_exists($d[0],$monarr)?1:0;
			if( preg_match("/.+$locsep.+$locsep.+/",$d[10]) ){
				$l = explode($locsep, $d[10]);
				$b = explode($bldsep, $l[2]);
				if($mn){
					$dreg[$l[0]]['mn']++;
					$dreg[$l[0]]['al'] += $monarr[$d[0]]['st'];
					$dcity[$l[0]][$l[1]]['mn']++;
					$dcity[$l[0]][$l[1]]['al'] += $monarr[$d[0]]['st'];
					$dbuild[$l[0]][$l[1]][$b[0]]['mn']++;
					$dbuild[$l[0]][$l[1]][$b[0]]['al'] += $monarr[$d[0]]['st'];
				}
				$dreg[$l[0]]['nd']++;
				$dcity[$l[0]][$l[1]]['nd']++;
				$dbuild[$l[0]][$l[1]][$b[0]]['nd']++;
				if($b[1]) $dbuild[$l[0]][$l[1]][$b[0]]['sb'][$b[1]]++;
				if($reg and $cty and $bld){
					$dev[$l[2]][$l[3]][$l[4]][$d[0]]['rk'] = $l[5];
					$dev[$l[2]][$l[3]][$l[4]][$d[0]]['ru'] = $l[6];
					$dev[$l[2]][$l[3]][$l[4]][$d[0]]['ip'] = long2ip($d[1]);
					$dev[$l[2]][$l[3]][$l[4]][$d[0]]['ty'] = $d[3];
					$dev[$l[2]][$l[3]][$l[4]][$d[0]]['co'] = $d[11];
					$dev[$l[2]][$l[3]][$l[4]][$d[0]]['po'] = $d[16];
					$dev[$l[2]][$l[3]][$l[4]][$d[0]]['ic'] = $d[18];
					$dev[$l[2]][$l[3]][$l[4]][$d[0]]['mn'] = $mn;
					$dev[$l[2]][$l[3]][$l[4]][$d[0]]['al'] = $monarr[$d[0]]['st'];
					$dev[$l[2]][$l[3]][$l[4]][$d[0]]['op'] = $d[27]; 
					$dev[$l[2]][$l[3]][$l[4]][$d[0]]['sz'] = $d[28]; 
					$dev[$l[2]][$l[3]][$l[4]][$d[0]]['sk'] = ($d[29])?$d[29]:1;
				}
			}else{
				$noloc[$d[0]]['ip'] = long2ip($d[1]);
				$noloc[$d[0]]['ty'] = $d[3];
				$noloc[$d[0]]['lo'] = $d[10];
				$noloc[$d[0]]['co'] = $d[11];
				$noloc[$d[0]]['po'] = $d[16];
				$noloc[$d[0]]['ic'] = $d[18];
				$noloc[$d[0]]['mn'] = $mn;
				if($mn) $noloc[$d[0]]['al'] = $monarr[$d[0]]['st'];
			}
		}
		DbFreeResult($res);
	}else{
		print DbError($link);
	}
}

//===================================================================
// Generate world table
function TopoRegs($siz=0){

	global $link,$debug,$map,$pop,$manlbl,$dreg,$locsep,$bg2,$netlbl,$addlbl,$loclbl,$poplbl,$errlbl;

	echo "<h2>$manlbl $netlbl</h2>\n\n";
	echo "<table class=\"content fixed\">\n\t<tr>\n";

	$col = 0;
	ksort($dreg);
	foreach (array_keys($dreg) as $r){
		$ur = urlencode($r);
		$nd = $dreg[$r]['nd'];
		$mn = isset( $dreg[$r]['mn']) ? $dreg[$r]['mn'] : 0;
		$al = isset( $dreg[$r]['al']) ? $dreg[$r]['al'] : 0;
		list($statbg,$stat) = StatusBg($nd,$mn,$al,'imga');
		if ($col == $_SESSION['col']){
			$col = 0;
			echo "\t</tr>\n	<tr>\n";
		}
	        echo "\t\t<td class=\"ctr $statbg\">\n";
	        $mstat = ($mn)?"$mn Monitored $stat":"";
		if($siz){
			echo "\t\t\t<a href=\"?reg=$ur\"><img src=\"img/32/glob.png\" title=\"$nd Devices $mstat\"></a><br>".substr($r,0,$_SESSION['lsiz'])."\n";
		}else{
			$qmap = $ur;
			$s    = ($_SESSION['gsiz'] < 3)?"160x120":"240x160";
			$rp   = preg_replace('/\W/','', $r);
			if($rp and $map){
				if( !file_exists("topo/$rp")  and !$_SESSION['snap'] ) mkdir("topo/$rp");
				if($map > 1){
					$loced = '';
					$ns = $ew = 0;
					$query	= GenQuery('locations','s','id,x,y,ns,ew,locdesc','','',array('region','city','building'),array('=','=','='),array($r,'',''),array('AND','AND'));
					$res	= DbQuery($query,$link);
					if (DbNumRows($res)){
						list($id,$x,$y,$ns,$ew,$des) = DbFetchRow($res);
						echo "\t\t\t$des<br>\n";
					}else{
						$loced = "\t\t\t<a href=\"Topology-Loced.php?reg=$ur\"><img src=\"img/16/ncfg.png\" title=\"$addlbl\"></a>\n";
					}
					if($ns and $ew){
						$ns /= 10000000;
						$ew /= 10000000;
						$qmap= "$ns,$ew";
					}
					if($_SESSION['map']){
						echo "\t\t\t<a href=\"?reg=$ur&map=$map\"><img src=\"http://maps.google.com/maps/api/staticmap?zoom=5&size=$s&maptype=roadmap&sensor=false&markers=color:blue%7C$qmap\" title=\"$nd Devices $mstat, $com\" style=\"border:1px solid black\"></a><br>\n";
						echo "$loced\t\t\t<a href=\"http://maps.google.com/maps?q=$qmap\" target=\"window\"><img src=\"img/16/map.png\" title=\"Googlemaps\"></a>\n";
					}else{
						if( $_SESSION['snap'] ){
							$cache = "img/32/glob.png";
						}else{
							$cache = "topo/$rp/osm-$s.png";
							if( !file_exists($cache) and ini_get('allow_url_fopen') ){
								if(!$ns and !$ew){
									$url = "http://nominatim.openstreetmap.org/search?format=json&limit=1&q=$qmap";
									$geo = json_decode( file_get_contents($url), TRUE);
									if($debug){echo "<div class=\"textpad code good\"><strong>$url</strong><p>";print_r($geo); echo '</div>';}
									if($geo){
										$qmap= $geo[0][lat].",".$geo[0][lon];
									}
								}
								$cfurl = "http://staticmap.openstreetmap.de/staticmap.php?center=$qmap&zoom=5&size=$s";
								$cdata = file_get_contents($cfurl);
								$csize = ($cdata)?DecFix(file_put_contents($cache, $cdata)):$errlbl;
								if($debug){echo "<div class=\"textpad code good\">$cfurl\n$cache: $csize</div>";}
							}
						}
						echo "\t\t\t<a href=\"?reg=$ur&map=$map\"><img src=\"$cache\" title=\"$nd Devices $mstat\" style=\"border:1px solid black\"></a><br>\n";
						echo "$loced\t\t\t<a href=\"http://nominatim.openstreetmap.org/search.php?q=$qmap\" target=\"window\"><img src=\"img/16/osm.png\" title=\"Openstreetmap\"></a>\n";
					}
				}else{
					if( file_exists("topo/$rp/map-$s.png") ){
						echo "\t\t\t<a href=\"?reg=$ur&map=$map\"><img src=\"topo/$rp/map-$s.png\" title=\"$nd Devices $mstat\" style=\"border:1px solid black\"></a><br>\n";
					}else{
						echo "\t\t\t<a href=\"?reg=$ur&map=$map\"><img src=\"inc/drawmap.php?st[]=^$ur&dim=$s&lev=2&pos=s\" title=\"$nd Devices $mstat\" style=\"border:1px solid black\"></a><br>\n";
					}
				}
			}else{
				echo "\t\t\t<a href=\"?reg=$ur\"><img src=\"img/32/glob.png\" title=\"$nd Devices $mstat\"></a><br>\n";
			}
			echo "			<a href=\"Topology-Map.php?st[]=$ur$locsep%&lev=2&fmt=png\"><img src=\"img/16/paint.png\" title=\"Topology-Map\"></a>\n";				
			if( $mn ) echo "			<a href=\"Monitoring-Setup.php?in[]=location&op[]=LIKE&st[]=$ur$locsep%&lev=2&fmt=png\"><img src=\"img/16/bino.png\" title=\"Monitoring-Setup\"></a>\n";				
			echo "			<a href=\"Devices-List.php?in[]=location&op[]=LIKE&st[]=$ur$locsep%\">".substr($r,0,$_SESSION['lsiz'])."</a>\n";
		}
		if($pop){
			$myp = NodPop( array('location'),array('LIKE'),array("$r$locsep%"),array() );
			if($myp) echo "			<a href=\"Nodes-List.php?in[]=location&op[]=LIKE&st[]=$ur$locsep%\"><img src=\"img/16/nods.png\" title=\"$loclbl $poplbl\">$myp</a>\n";
		}
		echo "		</td>\n";
	        $col++;
	}
	echo "	</tr>\n</table>\n";
}

//===================================================================
// Generate region table
function TopoCities($r,$siz=0){

	global $link,$map,$pop,$debug,$dcity,$locsep,$bg2,$netlbl,$errlbl,$tmplbl,$loclbl,$addlbl,$poplbl,$igrp,$notlbl,$rcvlbl,$errlbl;

	$ur  = urlencode($r);

	$query	= GenQuery('locations','s','id,x,y,ns,ew,locdesc','','',array('region','city','building'),array('=','=','='),array($r,'',''),array('AND','AND'));
	$res	= DbQuery($query,$link);
	if (DbNumRows($res)){
		list($id,$x,$y,$ns,$ew,$des) = DbFetchRow($res);
		echo "<h2>$r - $des</h2>\n\n";
	}else{
		echo "<h2>$r $netlbl</h2>\n\n";
	}
	echo "<table class=\"content fixed\"><tr>\n";

	$col = 0;
	ksort($dcity[$r]);
	foreach (array_keys($dcity[$r]) as $c){
		$nd = $dcity[$r][$c]['nd'];
		$ci = CtyImg($dcity[$r][$c]['nd']);
		$mn = isset( $dcity[$r][$c]['mn']) ? $dcity[$r][$c]['mn'] : 0;
		$al = isset( $dcity[$r][$c]['al']) ? $dcity[$r][$c]['al'] : 0;
		list($statbg,$stat) = StatusBg($nd,$mn,$al,'imga');
		$uc = urlencode($c);
		if ($col == $_SESSION['col']){
			$col = 0;
			echo "\t</tr>\n\t<tr>\n";
		}
		echo "\t\t<td class=\"btm ctr $statbg\">\n";
		$mstat = ($mn)?"$mn Monitored $stat":"";
		if($siz){
			echo "\t\t\t<a href=\"?reg=$ur&cty=$uc\"><img src=\"img/$ci.png\" title=\"$nd Devices $mstat\"></a><br>".substr($c,0,$_SESSION['lsiz'])."\n";
		}else{
			$qmap = "$uc,$ur";
			$s    = ($_SESSION['gsiz'] < 3)?"160x120":"240x160";
			$cp   = preg_replace('/\W/','', $r).'/'.preg_replace('/\W/','', $c);
			if($cp and $map){
				if($map > 1){
					if( !file_exists("topo/$cp") and !$_SESSION['snap'] ) mkdir("topo/$cp", 0755, true);
					$ns = $ew = 0;
					$query	= GenQuery('locations','s','id,x,y,ns,ew,locdesc','','',array('region','city','building'),array('=','=','='),array($r,$c,''),array('AND','AND'));
					$res	= DbQuery($query,$link);

					if (DbNumRows($res)){
						list($id,$x,$y,$ns,$ew,$des) = DbFetchRow($res);
						echo "$des<br>";
						$loced = '';
					}else{
						$loced = "\t\t\t<a href=\"Topology-Loced.php?reg=$ur&cty=$uc\"><img src=\"img/16/ncfg.png\" title=\"$addlbl\"></a>\n";
					}
					if($_SESSION['map']){
						$cachd = 2;							# Google maps mustn't be cached
					}else{
						$cache = "topo/$cp/osm-$s.png";
						$cachd = file_exists($cache);					# OSM is cached
					}
					if($ns and $ew){
						$ns /= 10000000;
						$ew /= 10000000;
						$qmap= "$ns,$ew";
					}elseif( ($map == 3 or !$cachd) and ini_get('allow_url_fopen') ){	# Weather and OSM only works with coordinates...
						$url = "http://nominatim.openstreetmap.org/search?format=json&limit=1&q=$qmap";
						$geo = json_decode( file_get_contents($url), TRUE);
						if($debug){echo "<div class=\"textpad code good\"><strong>$url</strong><p>";print_r($geo); echo '</div>';}
						if($geo){
							$ns = $geo[0][lat];
							$ew = $geo[0][lon];
							$qmap= "$ns,$ew";
						}
					}

					if($map == 3 and ini_get('allow_url_fopen') ){
						if($_SESSION['far']){
							$mod = 'imperial';
							$teu = 'F';
							$wiu = 'mph';
						}else{
							$mod = 'metric';
							$teu = 'C';
							$wiu = 'm/s';
						}
						$url = "http://api.openweathermap.org/data/2.5/weather?lat=$ns&lon=$ew&units=$mod";
						$wtr = json_decode( file_get_contents($url), TRUE);
						if($debug){echo "<div class=\"textpad code good\"><strong>$url</strong><p>";print_r($wtr); echo '</div>';}
						if( is_array($wtr) ){
							echo "\t\t\t<a href=\"http://openweathermap.org/city/".$wtr[id]."\" target=\"window\"><img src=\"http://openweathermap.org/img/w/".$wtr[weather][0][icon].".png\" title=\"".$wtr[weather][0][description]."\"></a>\n"; 
							echo "<img src=\"img/16/temp.png\" title=\"$tmplbl\">".round($wtr[main][temp])."$teu <img src=\"img/16/drop.png\" title=\"Humidity\">".$wtr[main][humidity]."% <img src=\"img/16/fan.png\" title=\"Wind\">".round($wtr[wind][speed])."$wiu<br>";
						}else{
							echo "$igrp[16] $notlbl $rcvlbl<br>\n";
						}
					}else{
						if($debug){echo "<div class=\"textpad code alrm\">Skip $igrp[16]: map=$map, allow_url_fopen ".ini_get('allow_url_fopen').'</div>';}
					}

					if($_SESSION['map']){
						echo "\t\t\t<a href=\"?reg=$ur&cty=$uc&map=$map\"><img src=\"http://maps.google.com/maps/api/staticmap?center=$qmap&zoom=11&size=$s&maptype=roadmap&sensor=false\" title=\"$nd Devices $mstat, $com\" style=\"border:1px solid black\"></a><br>\n";
						echo "$loced\t\t\t<a href=\"http://maps.google.com/maps?q=$qmap\" target=\"window\"><img src=\"img/16/map.png\" title=\"Googlemap\"></a>\n";
					}else{
						if( $_SESSION['snap'] ){
							$cache = "img/$ci.png";
						}elseif( !$cachd and ini_get('allow_url_fopen') ){						
							$cfurl = "http://staticmap.openstreetmap.de/staticmap.php?center=$qmap&zoom=12&size=$s";
							$cdata = file_get_contents($cfurl);
							$csize = ($cdata)?DecFix(file_put_contents($cache, $cdata)):$errlbl;
							if($debug){echo "<div class=\"textpad code good\">$cfurl\n$cache: $csize</div>\n";}
						}
						echo "\t\t\t<a href=\"?reg=$ur&cty=$uc&map=$map\"><img src=\"$cache\" title=\"$nd Devices $mstat\" style=\"border:1px solid black\"></a><br>\n";
						echo "$loced\t\t\t<a href=\"http://nominatim.openstreetmap.org/search.php?q=$qmap\" target=\"window\"><img src=\"img/16/osm.png\" title=\"Openstreetmap\"></a>\n";
					}
				}else{
					echo "\t\t\t<a href=\"?reg=$ur&cty=$uc&map=$map\"><img src=\"inc/drawmap.php?st[]=^$ur$locsep$uc&dim=$s&lev=3&pos=s\" title=\"$nd Devices $mstat\" style=\"border:1px solid black\"></a><br>\n";
				}
			}else{
				echo "\t\t\t<a href=\"?reg=$ur&cty=$uc\"><img src=\"img/$ci.png\" title=\"$nd Devices $mstat\"></a><br>\n";
			}
			echo "\t\t\t<a href=\"Topology-Map.php?st[]=$ur$locsep$uc$locsep%&lev=3&fmt=png\"><img src=\"img/16/paint.png\" title=\"Topology-Map\"></a>\n";
			if( $mn ) echo "\t\t\t<a href=\"Monitoring-Setup.php?in[]=location&op[]=LIKE&st[]=$ur$locsep$uc$locsep%&lev=2&fmt=png\"><img src=\"img/16/bino.png\" title=\"Monitoring-Setup\"></a>\n";
			echo "\t\t\t<a href=\"Devices-List.php?in[]=location&op[]=LIKE&st[]=$ur$locsep$uc$locsep%\"><strong>".substr($c,0,$_SESSION['lsiz'])."</strong></a>\n";
		}
		if($pop){
			$myp = NodPop( array('location'),array('LIKE'),array("$r$locsep$c$locsep%"),array() );
			if($myp) echo "\t\t\t<a href=\"Nodes-List.php?in[]=location&op[]=LIKE&st[]=$ur$locsep$uc$locsep%\"><img src=\"img/16/nods.png\" title=\"$loclbl $poplbl\">$myp</a>\n";
		}
		echo "\t\t</td>\n";
		$col++;
	}
	echo "\t</tr>\n</table>\n";
}

//===================================================================
// Generate city table
function TopoBuilds($r,$c,$siz=0){

	global $link,$map,$pop,$debug,$dbuild,$locsep,$bg2,$netlbl,$loclbl,$poplbl,$errlbl;

	$ur = urlencode($r);
	$uc = urlencode($c);

	$query	= GenQuery('locations','s','id,x,y,ns,ew,locdesc','','',array('region','city','building'),array('=','=','='),array($r,$c,''),array('AND','AND'));
	$res	= DbQuery($query,$link);
	if (DbNumRows($res)){
		list($id,$x,$y,$ns,$ew,$des) = DbFetchRow($res);
		echo "<h2>$c - $des</h2>\n";
	}else{
		echo "<h2>$c, $r $netlbl</h2>\n";
	}
	echo "<table class=\"content fixed\"><tr>\n";

	$col = 0;
	ksort($dbuild[$r][$c]);
	foreach (array_keys($dbuild[$r][$c]) as $b){
		$sb =  count($dbuild[$r][$c][$b]['sb']);
		$nd =  $dbuild[$r][$c][$b]['nd'];
		$mn = isset( $dbuild[$r][$c][$b]['mn']) ? $dbuild[$r][$c][$b]['mn'] : 0;
		$al = isset( $dbuild[$r][$c][$b]['al']) ? $dbuild[$r][$c][$b]['al'] : 0;
		$bi = BldImg($nd,$b);
		list($statbg,$stat) = StatusBg($nd,$mn,$al,"imga");
		$ub = urlencode($b);
		if ($col == $_SESSION['col']){
			$col = 0;
			echo "\t</tr>\n\t<tr>\n";
		}
	        echo "\t\t<td class=\"btm $statbg ctr\">\n";
	        $mstat = ($mn)?"$mn Monitored $stat":"";
		if($siz){
			echo "\t\t\t<a href=\"?reg=$ur&cty=$uc&bld=$ub\"><img src=\"img/$bi.png\" title=\"$nd Devices $mstat\"></a><br>".substr($b,0,$_SESSION['lsiz'])."\n";
		}else{
			$qmap = "$ub+$uc,$ur";
			$s    = ($_SESSION['gsiz'] < 3)?"160x120":"240x160";
			$cp   = preg_replace('/\W/','', $r).'/'.preg_replace('/\W/','', $c);
			if($cp and $map){
				if($map > 1){
					if( !file_exists("topo/$cp") and !$_SESSION['snap'] ) mkdir("topo/$cp", 0755, true);
					$ns = $ew = 0;
					$query	= GenQuery('locations','s','id,x,y,ns,ew,locdesc','','',array('region','city','building'),array('=','=','='),array($r,$c,$b),array('AND','AND'));
					$res	= DbQuery($query,$link);
					if (DbNumRows($res)){
						list($id,$x,$y,$ns,$ew,$des) = DbFetchRow($res);
						echo "\t\t\t$des<br>";
						$loced = '';
					}else{
						$loced = "\t\t\t<a href=\"Topology-Loced.php?reg=$ur&cty=$uc&bld=$ub\"><img src=\"img/16/ncfg.png\" title=\"$addlbl\"><a/>";
					}
					if($ns and $ew){
						$ns /= 10000000;
						$ew /= 10000000;
						$qmap= "$ns,$ew";
					}
					if($_SESSION['map']){
						echo "\t\t\t<a href=\"?reg=$ur&cty=$uc&bld=$ub&map=$map\"><img src=\"http://maps.google.com/maps/api/staticmap?center=$qmap&zoom=16&size=$s&maptype=roadmap&sensor=false\" title=\"$nd Devices $mstat $com\" style=\"border:1px solid black\"></a><br>\n";
						echo "$loced\t\t\t<a href=\"http://maps.google.com/maps?q=$qmap\" target=\"window\"><img src=\"img/16/map.png\" title=\"Googlemap\"></a>\n";
					}else{
						if( $_SESSION['snap'] ){
							$cache = "img/$bi.png";
						}else{
							$cache = "topo/$cp/osm-".preg_replace('/\W/','',$b)."-$s.png";
							if( !file_exists($cache) and ini_get('allow_url_fopen') ){
								if(!$ns and !$ew){
									$url = "http://nominatim.openstreetmap.org/search?format=json&limit=1&q=$qmap";
									$geo = json_decode( file_get_contents($url), TRUE);
									if($debug){echo "<div class=\"textpad code good\"><strong>$url</strong><p>";print_r($geo); echo '</div>';}
									if($geo){
										$qmap= $geo[0][lat].",".$geo[0][lon];
									}
								}
								$cfurl = "http://staticmap.openstreetmap.de/staticmap.php?center=$qmap&zoom=16&size=$s";
								$cdata = file_get_contents($cfurl);
								$csize = ($cdata)?DecFix(file_put_contents($cache, $cdata)):$errlbl;
								if($debug){echo "<div class=\"textpad code good\">$cfurl\n$cache: $csize</div>";}
							}
						}
						echo "\t\t\t<a href=\"?reg=$ur&cty=$uc&bld=$ub&map=$map\"><img src=\"$cache\" title=\"$nd Devices $mstat\" style=\"border:1px solid black\"></a><br>\n";
						echo "$loced\t\t\t<a href=\"http://nominatim.openstreetmap.org/search.php?q=$qmap\" target=\"window\"><img src=\"img/16/osm.png\" title=\"Openstreetmap\"></a>\n";
					}
				}else{
					echo "\t\t\t<a href=\"?reg=$ur&cty=$uc&bld=$ub&map=$map\"><img src=\"inc/drawmap.php?st[]=^$ur$locsep$uc$locsep$ub&dim=$s&lev=4&pos=d&xo=-20\" title=\"$nd Devices $mstat\" style=\"border:1px solid black\"></a><br>\n";
				}
			}else{
				echo "\t\t\t<a href=\"?reg=$ur&cty=$uc&bld=$ub\"><img src=\"img/$bi.png\" title=\"$nd Devices $mstat\"></a>".Digit($sb)."<br>\n";
			}
			echo "\t\t\t<a href=\"Topology-Map.php?st[]=$ur$locsep$uc$locsep$ub%&lev=4&fmt=png\"><img src=\"img/16/paint.png\" title=\"Topology-Map\"></a>\n";				
			if( $mn ) echo "			<a href=\"Monitoring-Setup.php?in[]=location&op[]=LIKE&st[]=$ur$locsep$uc$locsep$ub%&lev=2&fmt=png\"><img src=\"img/16/bino.png\" title=\"Monitoring-Setup\"></a>\n";				
			echo "\t\t\t<a href=\"Devices-List.php?in[]=location&op[]=LIKE&st[]=$ur$locsep$uc$locsep$ub%\"><strong>".substr($b,0,$_SESSION['lsiz'])."</strong></a>\n";
		}
		if($pop){
			$myp = NodPop( array('location'),array('LIKE'),array("$r$locsep$c$locsep$b%"),array() );
			if($myp) echo "\t\t\t<a href=\"Nodes-List.php?in[]=location&op[]=LIKE&st[]=$ur$locsep$uc$locsep$ub%\"><img src=\"img/16/nods.png\" title=\"$loclbl $poplbl\">$myp</a>\n";
		}
		echo "\t\t</td>\n";
		$col++;
	}
	echo "\t</tr>\n</table>\n";
}

//===================================================================
// Generate building table
function TopoFloors($r,$c,$b,$siz=0){

	global $link,$dev,$img,$pop,$v,$locsep,$place,$netlbl,$acslbl,$porlbl,$frelbl,$refresh;

	foreach (array_keys($dev) as $sb){
		$query	= GenQuery('locations','s','id,x,y,ns,ew,locdesc','','',array('region','city','building'),array('=','=','='),array($r,$c,$sb),array('AND','AND'));
		$res	= DbQuery($query,$link);
		if (DbNumRows($res)){
			list($id,$x,$y,$ns,$ew,$des) = DbFetchRow($res);
		}else{
			$des = $place[b];
		}
		echo "<h2>$sb - $des</h2>\n";
		echo "<table class=\"content fixed\">\n";
		uksort($dev[$sb], "floorsort");
		foreach (array_keys($dev[$sb]) as $fl){
			echo "\t<tr>\n\t\t<td class=\"bgsub s\">\n";
			if($siz){
				echo "\t\t\t<h3>$fl</h3>\n";
			}else{
				echo "\t\t\t<h3><img src=\"img/stair.png\"><br><a href=\"Devices-List.php?in[]=location&op[]=LIKE&st[]=".urlencode($r.$locsep.$c.$locsep.$b.$locsep.$fl.'%')."\">$fl</a></h3>\n";
				$bas = "topo/".preg_replace('/\W/','', $r).'/'.preg_replace('/\W/','', $c).'/'.preg_replace('/\W/','', $b).'-'.preg_replace('/\W/','', $fl);
				foreach(glob("$bas*") as $f){
					list($ico,$ed) = FileImg($f);
					echo "$ico ";
				}
			}
			echo "\t\t</td>\n";
			$col = 0;
			$prm = "";
			ksort( $dev[$sb][$fl] );
			foreach(array_keys($dev[$sb][$fl]) as $rm){
				if($prm != $rm){
					$bi = ($bi == "imga")?"imgb":"imga";
				}
				$prm = $rm;
				foreach (array_keys($dev[$sb][$fl][$rm]) as $d){
					$ip = $dev[$sb][$fl][$rm][$d]['ip'];
					$po = $dev[$sb][$fl][$rm][$d]['po'];
					$ty = $dev[$sb][$fl][$rm][$d]['ty'];
					$di = $dev[$sb][$fl][$rm][$d]['ic'];
					$co = $dev[$sb][$fl][$rm][$d]['co'];
					$rk = $dev[$sb][$fl][$rm][$d]['rk'];
					$mn = $dev[$sb][$fl][$rm][$d]['mn'];
					$al = $dev[$sb][$fl][$rm][$d]['al'];
					$sz = $dev[$sb][$fl][$rm][$d]['sz'];
					$sk = Digit($dev[$sb][$fl][$rm][$d]['sk']);
					list($statbg,$stat) = StatusBg(1,$mn,$al,$bi);
					$tit = ($stat)?$stat:$ty;
					$ud = urlencode($d);
					$ur = urlencode($r);
					$uc = urlencode($c);
					$ub = urlencode($sb);
					$uf = urlencode($fl);
					$um = urlencode($rm);
					if ($col == $_SESSION['col']){
						$col = 0;
						echo "\t</tr>\n\t<tr>\n\t\t<td>\n\t\t\t&nbsp;\n\t\t</td>\n";
					}
					if($siz){
						echo "\t\t<td class=\"top ctr $statbg\">\n\t\t\t<img src=\"img/dev/$di.png\" title=\"$ip\"><br>$d\n\t\t</td>\n";
					}else{
						$inif = '';
						if($pop){
							$myp = NodPop( array('device'),array('='),array("$d"),array() );
							$pi = ($myp)?"\t\t\t<a href=\"Nodes-List.php?in[]=device&op[]==&st[]=$ud\"><img src=\"img/16/nods.png\" title=\"$loclbl $poplbl\">$myp</a>":"";
							$ii = ($refresh)?0:IfFree($d);
							if($ii or $pi) $inif = "<div style=\"float:right\">$pi <a href=\"Devices-Interfaces.php?in[]=device&op[]==&st[]=$ud&co[]=AND&in[]=ifstat&op[]=<&st[]=3&co[]=AND&in[]=iftype&op[]=~&st[]=^(6|7|117)$&col[]=imBL&col[]=ifname&col[]=device&col[]=linktype&col[]=ifdesc&col[]=alias&col[]=lastchg&col[]=inoct&col[]=outoct&ord=lastchg\"><img src=\"img/p45.png\" title=\"$acslbl $porlbl $frelbl\">$ii</a></div>\n";
						}
						$rkv = ($dev[$sb][$fl][$rm][$d]['ru'])?"<a href=\"Topology-Table.php?reg=$ur&cty=$uc&bld=$ub&fl=$uf&rm=$um\">$rm</a>":$rm;
						echo "\t\t<td class=\"top $statbg\">\n\t\t\t<strong>$rkv</strong> $rk $inif<p>\n";
						echo "\t\t\t<div class=\"ctr\"><a href=\"Devices-Status.php?dev=$ud\">";
						echo "<img src=\"".(($img)?DevPanel($ty,$di,$sz)."\" width=\"".(($sz)?'100':'50')."\"":"img/dev/$di.png\"")." title=\"$tit\"></a>$sk<br>\n";
						if( !strpos($dev[$sb][$fl][$rm][$d]['op'],'a') ) echo "\t\t\t<strong>$d</strong><br>\n";
						echo "\t\t\t".Devcli($ip,$po);
						echo"<p>$co</div>\n\t\t</td>\n";
					}
					$col++;
				}
			}
		}
		echo "\t</tr>\n</table>\n<br>\n";
	}
}

//===================================================================
// Generate room with a rackview
function TopoRoom($r,$c,$b,$f,$m){

	global $link,$dev,$locsep,$bg2,$addlbl,$invlbl,$stalbl,$lstlbl,$debug;

	echo "<h2>$b $f-$m</h2>\n";
	echo "<table class=\"fixed\"><tr>\n";

	$query	= GenQuery('inventory','s','serial,type,asset,location','','',array('type','location'),array('LIKE','LIKE'),array('gen-%',TopoLoc($r,$c,$b,$f,$m) ),array('AND') );
	$res	= DbQuery($query,$link);
	if($res){
		while( ($i = DbFetchRow($res)) ){
			$l = explode($locsep, $i[3]);
			$d = "$l[6]-$i[2]";
			$dev[$b][$f][$m][$d]['wdh'] = 250;
			$dev[$b][$f][$m][$d]['ty'] = $i[1];
			$dev[$b][$f][$m][$d]['sn'] = $i[0];
			$dev[$b][$f][$m][$d]['sz'] = substr($i[1],-1);
			$dev[$b][$f][$m][$d]['sk'] = 1;
			$dev[$b][$f][$m][$d]['rk']  = $l[5];
			$dev[$b][$f][$m][$d]['ru']  = $l[6];
			$dev[$b][$f][$m][$d]['lwd'] = 24;
			$dev[$b][$f][$m][$d]['ip'] = 0;
		}
	}

	$col = 0;
	if($debug){echo '<div class="textpad code noti">';}
	$rsiz[$dev[$b][$f][$m][$d]['rk']] = 0;
	foreach( array_keys($dev[$b][$f][$m]) as $d ){
		if( $dev[$b][$f][$m][$d]['ru'] ){
			if($dev[$b][$f][$m][$d]['sz'] < 1){
				$dev[$b][$f][$m][$d]['wdh'] = 125;
				$dev[$b][$f][$m][$d]['hgt'] = $dev[$b][$f][$m][$d]['sk'];
				$dev[$b][$f][$m][$d]['lwd'] = 8;
			}else{
				$dev[$b][$f][$m][$d]['wdh'] = 250;
				$dev[$b][$f][$m][$d]['hgt'] = $dev[$b][$f][$m][$d]['sk'] * $dev[$b][$f][$m][$d]['sz'];
				$dev[$b][$f][$m][$d]['lwd'] = 24;
			}
			if( is_array($rack[$dev[$b][$f][$m][$d]['rk']]) and array_key_exists($dev[$b][$f][$m][$d]['ru'].";-4",$rack[$dev[$b][$f][$m][$d]['rk']]) ){
				$xpos = 121;
			}else{
				$xpos = -4;
			}
			$rack[$dev[$b][$f][$m][$d]['rk']][$dev[$b][$f][$m][$d]['ru'].";".$xpos] = $d;
			$top = $dev[$b][$f][$m][$d]['ru'] + $dev[$b][$f][$m][$d]['hgt'];
			if( $top > $rsiz[$dev[$b][$f][$m][$d]['rk']] ) $rsiz[$dev[$b][$f][$m][$d]['rk']] = $top;
			if($debug){echo "$d Rack:".$dev[$b][$f][$m][$d]['rk']." Top:$top RU:".$dev[$b][$f][$m][$d]['ru']." H:".$dev[$b][$f][$m][$d]['hgt']."<br>\n";}
		}else{
			if($debug){echo "$d Rack:".$dev[$b][$f][$m][$d]['rk']." no RU<br>\n";}
		}
	}
	if($debug){echo '</div>';}


	ksort( $rack );
	foreach( array_keys($rack) as $rk ){
		if( $col == $_SESSION['col'] ){
			$col = 0;
			echo "</tr>\n<tr>";
		}
		$rupx = 23;
		$urk = urlencode($r.$locsep.$c.$locsep.$b.$locsep.$f.$locsep.$m.$locsep.$rk.$locsep);
		echo "<td class=\"txta btm\"><h3>";
		if( isset($_GET['print'])){
			echo "<h3>$rk</h3>\n";
		}else{
			echo "<a href=\"Assets-Inventory.php?sn=rk".strlen($urk).time()."&lo=$urk&sta=150\"><img src=\"img/16/pkg.png\" title=\"$addlbl $invlbl\"></a>";
			echo "<a href=\"Devices-List.php?in[]=location&op[]=LIKE&st[]=$urk%\">$rk</a></h3>\n";
		}
		echo "<div style=\"height:".($rsiz[$rk]*$rupx-$rupx)."px;width:240px;border:12px solid #444;background-color:#aaa\">\n";
		echo "<div style=\"position:relative;bottom:0px;left:-6px;height:".($rsiz[$rk]*$rupx-$rupx)."px;width:244px;border-width:1px 4px;border-style:solid dotted;border-color:#888\">\n";
		$rus = array_keys($rack[$rk]);
		sort($rus);
		foreach ($rus as $rup){
			$p = explode(';', $rup);
			if($debug){echo "$rup ".$rack[$rk][$rup]."<br>\n";}
			$ud = urlencode($rack[$rk][$rup]);
			$bgpanel = DevPanel($dev[$b][$f][$m][$rack[$rk][$rup]]['ty'],$dev[$b][$f][$m][$rack[$rk][$rup]]['ic'],$dev[$b][$f][$m][$rack[$rk][$rup]]['sz']);
			$lbl     = "<span style=\"border: 1px solid black;background-color:#e0e0e0;font-size:80%\" title=\"RU:$p[0]\">".substr($rack[$rk][$rup],0,$dev[$b][$f][$m][$rack[$rk][$rup]]['lwd'])."&nbsp;</span>\n";
			$lbl    .= (($dev[$b][$f][$m][$rack[$rk][$rup]]['sk'] > 1)?"<img src=\"img/".$dev[$b][$f][$m][$rack[$rk][$rup]]['sk'].".png\" style=\"background-color:#999\" title=\"Stack\">":"");
			if( !isset($_GET['print'])){
				$lbl .= "<div style=\"float:right;background-color:#aaa;border: 1px solid black\">";
				if($dev[$b][$f][$m][$rack[$rk][$rup]]['sn']){
					$lbl .= "<a href=\"Assets-Inventory.php?chg=".$dev[$b][$f][$m][$rack[$rk][$rup]]['sn']."\"><img src=\"img/16/pkg.png\" title=\"$invlbl\"></a></div>";
				}else{
					$lbl .= Devcli($dev[$b][$f][$m][$rack[$rk][$rup]]['ip'],$dev[$b][$f][$m][$rack[$rk][$rup]]['po'],2);
					$lbl .= "<a href=\"Devices-Status.php?dev=$ud\"><img src=\"img/16/sys.png\" title=\"Device $stalbl\"></a>";
					$lbl .= "<a href=\"Nodes-List.php?in[]=device&op[]==&st[]=$ud&ord=ifname\"><img src=\"img/16/nods.png\" title=\"Nodes $lstlbl\"></a></div>";
				}
			}
			$rpos = $rsiz[$rk] * $rupx - ($p[0] + $dev[$b][$f][$m][$rack[$rk][$rup]]['hgt']) * $rupx;
			echo "<div style=\"position:absolute;top:${rpos}px;left:$p[1]px;height:".($dev[$b][$f][$m][$rack[$rk][$rup]]['hgt']*23)."px;width:".$dev[$b][$f][$m][$rack[$rk][$rup]]['wdh']."px;border:1px solid black;background-image: URL($bgpanel);\">\n$lbl</div>\n";
		}
		echo "</div></div></td>\n";
		$col++;
	}
	echo "</tr></table>\n";
}

//===================================================================
// Show the misfits
function TopoLocErr($siz=0){

	global $noloc,$img,$debug,$manlbl,$bg2,$loclbl,$errlbl;

	if( !count($noloc) ) return;

	echo "<p>\n\n<h2>$loclbl $errlbl</h2>\n\n";
	echo "<table class=\"content fixed\">\n	<tr>\n";

	$col = 0;
	foreach (array_keys($noloc) as $d){
		$ip = $noloc[$d]['ip'];
		$ty = $noloc[$d]['ty'];
		$di = $noloc[$d]['ic'];
		$lo = $noloc[$d]['lo'];
		$co = $noloc[$d]['co'];
		$po = $noloc[$d]['po'];
		$mn = $noloc[$d]['mn'];
		$al = $noloc[$d]['al'];
		list($statbg,$stat) = StatusBg(1,$mn,$al,'imga');
		$tit = ($stat)?$stat:$ty;
		$ud = urlencode($d);
		if ($col == $_SESSION['col']){
			$col = 0;
			echo "\n	</tr>\n	<tr>\n";
		}
		if($siz){
			echo "		<td class=\"$statbg ctr\">\n";
			echo "			<img src=\"img/dev/$di.png\" title=\"$lo, $co\"><br>$d\n";
			echo "		</td>\n";
		}else{
			echo "		<td class=\"$statbg ctr\">\n";
			echo "			<a href=\"Devices-Status.php?dev=$ud\"><img src=\"img/dev/$di.png\" title=\"$tit\"></a>\n";
			echo "			$sk<br><strong>$d</strong><br>".Devcli($ip,$po)."<br>$lo<br><span class=\"gry\">$co</span>\n";
			echo "		</td>\n";
		}
		$col++;
	}
	echo "	</tr>\n</table>\n";
}

//===================================================================
// Return image for test
function TestImg($srv,$topt="",$tres=""){

	global $nonlbl,$tstlbl,$sndlbl,$rcvlbl;

	if($srv == "ping")	{$img =  "relo";}
	elseif($srv == "uptime"){$img =  "clock";}
	elseif($srv == "dns")	{$img =  "abc";}
	elseif($srv == "ntp")	{$img =  "date";}
	elseif($srv == "http")	{$img =  "glob";}
	elseif($srv == "https")	{$img =  "glok";}
	elseif($srv == "telnet"){$img =  "loko";}
	elseif($srv == "ssh")	{$img =  "lokc";}
	elseif($srv == "mysql")	{$img =  "db";}
	elseif($srv == "cifs")	{$img =  "nwin";}
	elseif($srv == "none")	{$img =  "bcls";}
	else{$img =  "bdis";$srv = "$nonlbl Monitor";}

	return "<img src=\"img/16/$img.png\" title=\"$tstlbl: $srv".(($topt or $tres)?" $sndlbl $topt, $rcvlbl $tres":"")."\">";
}

?>
