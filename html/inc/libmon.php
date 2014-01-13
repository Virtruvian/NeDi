<?PHP

//===============================
// Monitoring related functions (and variables)
//===============================

// Event icons & colors based on level
$mico['10']  = "fogy";#TODO convert to function?
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
// Return icon for an event class
function EvImg($c){

	if($c == 'dev'){
		return 'dev';
	}elseif($c == 'moni'){
		return 'bino';
	}elseif($c == 'node'){
		return 'node';
	}elseif($c == 'nedi'){
		return 'radr';
	}elseif($c == 'sec'){
		return 'hat';
	}elseif(strpos($c,'cfg') !== false){
		return 'conf';
	}elseif(strpos($c,'trf') !== false){
		return 'grph';
	}elseif(strpos($c,'usr') !== false){
		return 'user';
	}else{
		return 'say';
	}
}

//===================================================================
// Return icon for an incident group
function IncImg($cat){

	if($cat == 1)		{return "star";}
	elseif($cat == 11)	{return "flas";}
	elseif($cat == 12)	{return "dril";}
	elseif($cat == 13)	{return "ele";}
	elseif($cat == 14)	{return "ncon";}
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
function StatusBg($nd,$mn,$a,$bg){

	global $pause,$tim,$errlbl,$mullbl,$alllbl;

	$partial = ($nd == $mn)?"":" part";
	if ($mn == 1){
		$onetgt = ($nd == 1)?"":", 1 down ";
		$out = $a * $pause;
		if( $out > 86400){
			return array("crit$partial",(intval($out/8640)/10)." $tim[d]");
		}elseif( $out > 3600){
			return array("crit$partial",(intval($out/360)/10)." $tim[h]");
		}elseif( $out > 600){
			return array("alrm$partial",(intval($out/6)/10)." $tim[i]");
		}elseif( $out ){
			return array("warn$partial","Down $out $tim[s]");
		}else{
			return array("good$partial","OK");
		}
	}elseif ($mn > 1){
		if($a > 1){
			return array("crit$partial","$mullbl $errlbl");
		}elseif($a){
			return array("alrm$partial","$errlbl $tim[n]");
		}else{
			return array("good$partial","$alllbl OK");
		}
	}else{
		return array ($bg,"");
	}
}

//===================================================================
// Generate If status tables
function StatusIf($loc,$mode,$label,$lim,$siz=0){

	global $link,$rrdstep,$trfa,$modgroup,$self;

	if($mode   == "brup"){
		$type = 1;
		$query	= GenQuery('interfaces','s','device,ifname,speed,iftype,dinerr','dinerr desc',$lim,array('dinerr','iftype','location'),array('>','!=','regexp'),array("$rrdstep",'71',$loc),array('AND','AND'),'JOIN devices USING (device)');
	}elseif($mode   == "brdn"){
		$type = 1;
		$query	= GenQuery('interfaces','s','device,ifname,speed,iftype,douterr','douterr desc',$lim,array('douterr','iftype','location'),array('>','!=','regexp'),array("$rrdstep",'71',$loc),array('AND','AND'),'JOIN devices USING (device)');
	}elseif($mode   == "bbup"){
		$type = 0;
		$query	= GenQuery('interfaces','s',"device,ifname,speed,iftype,dinoct*800/speed/$rrdstep",'dinoct/speed desc',$lim,array("dinoct*800/speed/$rrdstep",'location'),array('>','regexp'),array($trfa,$loc),array('AND'),'JOIN devices USING (device)');
	}elseif($mode   == "bbdn"){
		$type = 0;
		$query	= GenQuery('interfaces','s',"device,ifname,speed,iftype,doutoct*800/speed/$rrdstep",'doutoct/speed desc',$lim,array("doutoct*800/speed/$rrdstep",'location'),array('>','regexp'),array($trfa,$loc),array('AND'),'JOIN devices USING (device)');
	}elseif($mode   == "bdis"){
		$type = 2;
		$query	= GenQuery('interfaces','s','device,ifname,speed,iftype,ifstat','device',$lim,array('ifstat','location'),array('&','regexp'),array('128',$loc),array('AND'),'JOIN devices USING (device)');
	}
	$res	= @DbQuery($query,$link);
	if($res){
		$nr = @DbNumRows($res);
		if($nr){
?>
<p>
<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th colspan="2"><img src="img/16/port.png" title="Top <?=$lim?>"><br>Interface</th><th><img src="img/16/<?=$mode?>.png"><br><?=(substr($label,0,3))?></th>
<?
			$row = 0;
			while( ($r = @DbFetchRow($res)) ){
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
				list($ifimg,$iftit) = Iftype($t[3]);
				echo "<tr class=\"$bg\"><th class=\"$bi\" width=\"25\"><img src=img/$ifimg title=\"$iftit\">";
				if($siz == 6){
					echo "</th><td>$t $r[1]</td><th bgcolor=\"#$rb$rb$bg3\">";
				}else{
					echo "</th><td><a href=Devices-Status.php?dev=$ud&pop=on>$t</a> ";
					echo "<a href=Nodes-List.php?ina=device&opa==&sta=$ud&cop=AND&inb=ifname&opb==&stb=$ui>$r[1]</a> ".Zfix($r[2])."</td><th bgcolor=\"#$rb$rb$bg3\">\n";
				}
				if($type == 2){
					$stat = ($r[4] & 128)?"Admin":"?";
				}elseif($type == 1){
					$stat = $r[4];
				}else{
					$stat = sprintf("%1.1f",$r[4])." %";
				}
				echo "$stat</th></tr>\n";
			}
			echo "</table>\n";
		}elseif(!$siz or $siz == 6){
?>
<p><img src="img/32/<?=$mode?>.png" title="<?=$label?>" hspace="8"><img src="img/32/bchk.png" title="OK">
<?
		}
		@DbFreeResult($res);
	}else{
		print @DbError($link);
	}
}

//===================================================================
// Generate cpu status table
function StatusCpu($loc,$lim,$siz){

	global $link,$cpua,$lodlbl,$modgroup,$self;

	$query = GenQuery('devices','s','device,cpu','cpu desc',$lim,array('cpu','location'),array('>','regexp'),array($cpua,$loc),array('AND'));
	$res	= @DbQuery($query,$link);
	if($res){
		$nr = @DbNumRows($res);
		if($nr){
?>
<p><table class="content"><tr class="<?=$modgroup[$self]?>2">
<th colspan="2" nowrap><img src="img/16/dev.png" title="Top <?=$lim?> CPU <?=$lodlbl?> > <?=$cpua?>%"><br>Device</th>
<th nowrap><img src="img/16/cpu.png"><br><?=$lodlbl?></th>
<?
			$row = 0;
			while( ($t = @DbFetchRow($res)) ){
				if ($row % 2){$bg = "txta"; $bi = "imga";$off="b8";}else{$bg = "txtb"; $bi = "imgb";$off="c8";}
				$row++;
				$lv  = $t[1]-$cpua;
				$hi  = sprintf("%02x",(($lv > 55)?55:$lv) + 200);
				$na  = substr($t[0],0,12);
				$ud  = urlencode($t[0]);
				if($_SESSION['gsiz'] == 6){
					echo "<tr bgcolor=\"#$hi$off$off\"><th class=\"$bi\">$row</th><td>$na</td><th nowrap>$t[1]%</th></tr>\n";
				}else{
					echo "<tr bgcolor=\"#$hi$off$off\"><th class=\"$bi\">$row</th><td><a href=Devices-Status.php?dev=$ud>$na</a></td>\n";
					echo "<th nowrap>$t[1]%</th></tr>\n";
				}
			}
			echo "</table>\n";
		}else{
			$isiz = ($siz == 2)?"16":"32";
?>
<p><img src="img/<?=$isiz?>/cpu.png" title="CPU <?=$lodlbl?>" hspace="8"> <img src="img/<?=$isiz?>/bchk.png" title="OK">
<?
		}
		@DbFreeResult($res);
	}else{
		print @DbError($link);
	}
}

//===================================================================
// Generate cpu status table
function StatusMem($loc,$lim,$siz){

	global $link,$mema,$frelbl,$modgroup,$self;

	$ma = explode('/', $mema);
	$aquery = GenQuery('devices','s','device,memcpu','memcpu desc',$lim,array('memcpu','memcpu','location'),array('<','>','regexp'),array($ma[0] * 1024,100,$loc),array('AND','AND'));
	$ares	= @DbQuery($aquery,$link);
	if($ares){
		$nar = @DbNumRows($ares);
		$pquery = GenQuery('devices','s','device,memcpu','memcpu desc',$lim,array('memcpu','memcpu','location'),array('<','>','regexp'),array($ma[1],0,$loc),array('AND','AND'));
		$pres	= @DbQuery($pquery,$link);
		$npr    = @DbNumRows($pres);

		if($nar or $npr){
?>
<p><table class="content"><tr class="<?=$modgroup[$self]?>2">
<th colspan="2"><img src="img/16/dev.png" title="Top <?=$lim?> Memory <?=$frelbl?> < <?=$ma[0]?>KB/<?=$ma[1]?>%"><br>Device</th>
<th nowrap><img src="img/16/mem.png"><br><?=$frelbl?></th>
<?
			$row = 0;
			while( ($t = @DbFetchRow($ares)) ){
				if ($row % 2){$bg = "txta"; $bi = "imga";$off="b8";}else{$bg = "txtb"; $bi = "imgb";$off="c8";}
				$row++;
				$lv  = intval($ma[0]/$t[1]);
				$hi  = sprintf("%02x",(($lv > 55)?55:$lv) + 200);
				$na  = substr($t[0],0,12);
				$ud  = urlencode($t[0]);
				if($_SESSION['gsiz'] == 6){
					echo "<tr bgcolor=\"#$hi$hi$off\"><th class=\"$bi\">$row</th><td>$na</td><th nowrap>$t[1]KB</th></tr>\n";
				}else{
					echo "<tr bgcolor=\"#$hi$hi$off\"><th class=\"$bi\">$row</th><td><a href=Devices-Status.php?dev=$ud>$na</a></td>\n";
					echo "<th nowrap>$t[1]KB</th></tr>\n";
				}
			}
			while( ($t = @DbFetchRow($pres)) ){
				if ($row % 2){$bg = "txta"; $bi = "imga";$off="b8";}else{$bg = "txtb"; $bi = "imgb";$off="c8";}
				$row++;
				$lv  = $t[1]-$m[1];
				$hi  = sprintf("%02x",(($lv > 55)?55:$lv) + 200);
				$na  = substr($t[0],0,12);
				$ud  = urlencode($t[0]);
				if($_SESSION['gsiz'] == 6){
					echo "<tr bgcolor=\"#$hi$hi$off\"><th class=\"$bi\">$row</th><td>$na</td><th nowrap>$t[1]%</th></tr>\n";
				}else{
					echo "<tr bgcolor=\"#$hi$hi$off\"><th class=\"$bi\">$row</th><td><a href=Devices-Status.php?dev=$ud>$na</a></td>\n";
					echo "<th nowrap>$t[1]%</th></tr>\n";
				}
			}
			echo "</table>\n";
		}else{
			$isiz = ($siz == 2)?"16":"32";
?>
<p><img src="img/<?=$isiz?>/mem.png" title="Memory <?=$frelbl?>" hspace="8"> <img src="img/<?=$isiz?>/bchk.png" title="OK">
<?
		}
		@DbFreeResult($res);
	}else{
		print @DbError($link);
	}
}

//===================================================================
// Generate temperature status table
function StatusTmp($loc,$lim,$siz){

	global $link,$tmpa,$modgroup,$self;

	$query = GenQuery('devices','s','device,temp','temp desc',$lim,array('temp','location'),array('>','regexp'),array($tmpa,$loc),array('AND'));
	$res	= @DbQuery($query,$link);
	if($res){
		$nr = @DbNumRows($res);
		if($nr){
?>
<p><table class="content"><tr class="<?=$modgroup[$self]?>2">
<th colspan="2"><img src="img/16/dev.png" title="Top <?=$lim?> Temperature > <?=$tmpa?>C"><br>Device</th>
<th><img src="img/16/home.png"><br>Temp</th>
<?
			$row = 0;
			while( ($t = @DbFetchRow($res)) ){
				if ($row % 2){$bg = "txta"; $bi = "imga";$off="b8";}else{$bg = "txtb"; $bi = "imgb";$off="c8";}
				$row++;
				$lv  = pow(($t[1]-$tmpa),2);
				$hi  = sprintf("%02x",(($lv > 55)?55:$lv) + 200);
				$na  = substr($t[0],0,12);
				$ud  = urlencode($t[0]);
				if($_SESSION['gsiz'] == 6){
					echo "<tr bgcolor=\"#$hi$off$hi\"><th class=\"$bi\">$row</th><td>$na</td><th nowrap>$t[1]C</th></tr>\n";
				}else{
					echo "<tr bgcolor=\"#$hi$off$hi\"><th class=\"$bi\">$row</th><td><a href=Devices-Status.php?dev=$ud>$na</a></td>\n";
					echo "<th nowrap>$t[1]C</th></tr>\n";
				}
			}
			echo "</table>\n";
		}else{
			$isiz = ($siz == 2)?"16":"32";
?>
<p><img src="img/<?=$isiz?>/home.png" title="Temp" hspace="8"> <img src="img/<?=$isiz?>/bchk.png" title="OK">
<?
		}
		@DbFreeResult($res);
	}else{
		print @DbError($link);
	}
}

//===================================================================
// Show unacknowledged incidents
function StatusIncidents($loc,$siz){

	global $link,$acklbl,$nonlbl;

	$ico = "fogy";
	$inctit = "?";
	$query	= GenQuery('incidents','s','count(*)','','',array('time','location'),array('=','regexp'),array(0,$loc),array('AND'),'LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	if($res){
		$ni = @DbFetchRow($res);
		$inctit = "$acklbl: $ni[0]";
		if($ni[0] == 0){
			$ico = "bchk";
			$inctit = "$acklbl: $nonlbl";
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
	}

	if($siz == 6){
?>
<p>
<img src="img/32/bomb.png" title="Incidents" hspace="8">
<img src="img/32/<?=$ico?>.png" title="<?=$inctit?>">
<?
	}else{
		$isiz = ($siz == 2)?"16":"32";
?>
<p>
<a href="Monitoring-Incidents.php"><img src="img/<?=$isiz?>/bomb.png" title="Incidents" hspace="8"></a>
<img src="img/<?=$isiz?>/<?=$ico?>.png" title="<?=$inctit?>">
<?
	}
}

//===================================================================
// Displays Events based on query in 3 sizes (0=full, 1=small, 2=mobile)
function Events($lim,$in,$op,$sta,$cop,$s=0){

	global $link,$modgroup,$self,$bg,$bi,$mico,$mbak,$mlvl,$datfmt;
	global $gralbl,$lstlbl,$levlbl,$timlbl,$srclbl,$msglbl,$stalbl,$cfglbl,$cmdlbl,$nonlbl,$clalbl;

	$query = GenQuery('events','s','id,level,time,source,info,class','id desc',$lim,$in,$op,$sta,$cop,'LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	if($res){
		$nmsg = @DbNumRows($res);
		if($nmsg){
			$row  = 0;
			if($s){
				echo "<table class=\"content\">";
				if($s == 1){
?>
<tr class="<?=$modgroup[$self]?>2">
<th><img src="img/16/idea.png"><br><?=$levlbl?></th>
<th><img src="img/16/clock.png"><br><?=$timlbl?></th>
<th><img src="img/16/say.png"><br><?=$srclbl?></th>
<th><img src="img/16/find.png"><br>Info</th>
</tr>
<?
				}
				while( ($m = @DbFetchRow($res)) ){
					if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
					$row++;
					$time = date($datfmt,$m[2]);
					if($s == 1){
						$fd   = urlencode(date("m/d/Y H:i:s",$m[2]));
						$usrc = urlencode($m[3]);
						echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\">\n";
						echo "<th class=\"".$mbak[$m[1]]."\"><a href=Monitoring-Events.php?lvl=$m[1]>\n";
						echo "<img src=\"img/16/" . $mico[$m[1]] . ".png\" title=\"" . $mlvl[$m[1]] . "\"></a></th>\n";
						echo "<td nowrap><a href=Monitoring-Events.php?ina=time&opa==&sta=$fd>$time</a></td><td nowrap>\n";
						echo "<a href=Monitoring-Events.php?ina=source&opa==&sta=$usrc>$m[3]</a></td><td>".((strlen($m[4]) > 60)?substr($m[4],0,60)."...":$m[4])."</td></tr>\n";
					}else{							# Mobile mode
						echo "<tr class=\"".$mbak[$m[1]]."\"><th nowrap>$m[3]</th><td nowrap>$time</td><td>".((strlen($m[4]) > 50)?substr($m[4],0,50)."...":$m[4])."</td></tr>\n";
					}
				}
				echo "</table>\n";
			}else{
?>
<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th width="80"><img src="img/16/eyes.png"><br>Id</th>
<th width="50"><img src="img/16/idea.png" title="10=<?=$mlvl['10']?>,50=<?=$mlvl['50']?>, 100=<?=$mlvl['100']?>, 150=<?=$mlvl['150']?>, 200=<?=$mlvl['200']?>, 250=<?=$mlvl['250']?>"><br><?=$levlbl?></th>
<th width="120"><img src="img/16/clock.png"><br><?=$timlbl?></th>
<th><img src="img/16/say.png" title="Name if added to monitoring or IP (events with level < 50)"><br><?=$srclbl?></th>
<th width="50"><img src="img/16/abc.png" title="<?=$msglbl?> <?=$clalbl?>:<?=$cmdlbl?>"><br><?=$clalbl?></th>
<th width="60%"><img src="img/16/find.png"><br>Info</th>
</tr>
<?
				while( ($m = @DbFetchRow($res)) ){
					if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
					$row++;
					$time = date($datfmt,$m[2]);
					$fd   = urlencode(date("m/d/Y H:i:s",$m[2]));
					$ud   = urlencode($m[3]);
					$ei   = EvImg($m[5]);
					echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\">\n";
					echo "<th><a href=\"Monitoring-Events.php?ina=id&opa==&sta=$m[0]\">$m[0]</a></th>\n";
					echo "<th class=\"".$mbak[$m[1]]."\"><a href=\"Monitoring-Events.php?lvl=$m[1]\"><img src=\"img/16/" . $mico[$m[1]] . ".png\" title=\"" . $mlvl[$m[1]] . "\"></a></th>\n";
					echo "<td nowrap><a href=\"Monitoring-Events.php?ina=time&opa==&sta=$fd\">$time</a></td><td nowrap><a href=\"Monitoring-Events.php?ina=source&opa==&sta=$ud\"><b>$m[3]</b></a></td>\n";

					$action = "<a href=\"Devices-Status.php?dev=$ud&pop=1\"><img src=\"img/16/$ei.png\" title=\"$m[5]: Device $stalbl\"></a>";
					if($m[5] == "node"){			# Syslog from a node
						$action = "<a href=\"Nodes-List.php?ina=name&opa==&sta=$m[3]\"><img src=\"img/16/$ei.png\" title=\"$m[5]: Node $lstlbl\"></a>";
					}elseif($m[5] == "moni"){		# Monitoring events
						$action = "<a href=\"Monitoring-Setup.php?ina=name&opa=%3D&sta=$ud\"><img src=\"img/16/$ei.png\" title=\"$m[5]: Monitoring Setup\"></a>";
					}elseif($m[5] == "usrs"){		# User changed stock
						$action = "<a href=\"Devices-Stock.php?chg=$m[3]\"><img src=\"img/16/$ei.png\" title=\"$m[5]: Device Stock\"></a>";
					}elseif($m[5] == "cfgn" or $m[5] == "cfgc"){	# New config or changes
						$action =  "<a href=\"Devices-Config.php?shc=$ud\"><img src=\"img/16/$ei.png\" title=\"$m[5]: Device $cfglbl\"></a>";
					}elseif(strpos($m[5],"trf") !== FALSE){	# Traffic warnings or alerts
						$action =  "<a href=\"Devices-Graph.php?dv=$ud\"><img src=\"img/16/$ei.png\" title=\"$m[5]: Device $gralbl\"></a>";
					}elseif($m[3] == "NeDi"){		# Not related to a dev or node!
						$action = "<a href=\"System-Files.php\"><img src=\"img/16/file.png\" title=\"$m[5]: NeDi $cfglbl\"></a>";
					}elseif( strpos($m[4],"not discoverable") or $m[1] < 50){
						$action = "<a href=\"Nodes-List.php?ina=nodip&opa==&sta=$m[3]\"><img src=\"img/16/$ei.png\" title=\"$m[5]: Node $lstlbl\"></a>";
					}elseif($m[5] == "ip"){			# syslog from unmonitored source
						$action = "<img src=\"img/16/$ei.png\" title=\"$m[5]:$msglbl $clalbl $m[5]\">";
					}
					echo "<th class=\"$bi\">$action</th><td>";
					$info = preg_replace('/[\s:]([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})(\s|:|$)/', " <span class=\"blu\">$1</span> <a href=\"Nodes-Toolbox.php?Dest=$1\"><img src=\"img/16/dril.png\" title=\"Lookup\"></a><a href=\"Nodes-List.php?ina=nodip&opa=%3D&sta=$1\"><img src=\"img/16/nods.png\" title=\"Nodes $lstlbl\"></a> ", $m[4]);
					echo preg_replace('/[\s:]([0-9a-f]{4}[\.-]?[0-9a-f]{4}[\.-]?[0-9a-f]{4}|[0-9a-f]{2}[-:][0-9a-f]{2}[-:][0-9a-f]{2}[-:][0-9a-f]{2}[-:][0-9a-f]{2}[-:][0-9a-f]{2})(\s|$)/', " <span class=\"mrn\">$1</span> <a href=\"Nodes-Status.php?mac=$1\"><img src=\"img/16/node.png\" title=\"Node $stalbl\"></a> ", $info);
					echo "</td></tr>\n";
				}
?>
</table>
<table class="content">
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$msglbl?></td></tr>
</table>
<?

			}
		}else{
			echo "<p><h5>$nonlbl</h5>";
		}
		@DbFreeResult($res);
	}else{
		print @DbError($link);
	}
}


//===================================================================
// Generate device metainfo for topology based device tables
function TopoTable($reg="",$cty="",$bld=""){

	global $link,$dev,$deval,$dreg,$dcity,$dbuild,$locsep;
# TODO optimize by dividing into grouped queries!
	$query	= GenQuery('devices','s','*','','',array('location'),array('regexp'),array( TopoLoc($reg,$cty,$bld) ) );
	$res	= @DbQuery($query,$link);
	if($res){
		while( ($d = @DbFetchRow($res)) ){
			if($d[10] == ""){$d[10] = "-$locsep-$locsep-";}
			$l = explode($locsep, $d[10]);
			if( array_key_exists($d[0],$deval) ){						# Device is monitored if key exists
				$dreg[$l[0]]['mn']++;
				$dreg[$l[0]]['al'] += $deval[$d[0]];
				$dcity[$l[0]][$l[1]]['mn']++;
				$dcity[$l[0]][$l[1]]['al'] += $deval[$d[0]];
				$dbuild[$l[0]][$l[1]][$l[2]]['mn']++;
				$dbuild[$l[0]][$l[1]][$l[2]]['al'] += $deval[$d[0]];
				$mn = 1;
			}else{
				$mn = 0;
			}

			if (!$cty){
				$dreg[$l[0]]['nd']++;
				$dcity[$l[0]][$l[1]]['nd']++;
			}elseif (!$bld){
				$dbuild[$l[0]][$l[1]][$l[2]]['nd']++;
				if($d[6] > 3){$dbuild[$l[0]][$l[1]][$l[2]]['nr']++;}
			}else{
				$dev[$l[3]][$l[4]][$d[0]]['rk'] = "$l[5] <i>$l[6]</i>";
				$dev[$l[3]][$l[4]][$d[0]]['ip'] = $d[1];
				$dev[$l[3]][$l[4]][$d[0]]['ty'] = $d[3];
				$dev[$l[3]][$l[4]][$d[0]]['co'] = $d[11];
				$dev[$l[3]][$l[4]][$d[0]]['po'] = $d[16];
				$dev[$l[3]][$l[4]][$d[0]]['ic'] = $d[18];
				$dev[$l[3]][$l[4]][$d[0]]['mn'] = $mn;
				$dev[$l[3]][$l[4]][$d[0]]['al'] = $deval[$d[0]];

			}
		}
		@DbFreeResult($res);
	}else{
		print @DbError($link);
	}
}

//===================================================================
// Generate world table
function TopoRegs($siz=0){

	global $link,$manlbl,$dreg,$locsep,$bg2,$netlbl;

	echo "<h2>$manlbl $netlbl</h2>\n";
	echo "<table class=\"content fixed\"><tr>\n";

	$col = 0;
	$rec = 0;
	ksort($dreg);
	foreach (array_keys($dreg) as $r){
		$ur = urlencode($r);
		$nd = $dreg[$r]['nd'];
		$mn = isset( $dreg[$r]['mn']) ? $dreg[$r]['mn'] : 0;
		$al = isset( $dreg[$r]['al']) ? $dreg[$r]['al'] : 0;
		list($statbg,$stat) = StatusBg($nd,$mn,$al,'imga');
		$uc = urlencode($c);
		if ($col == $_SESSION['col']){
			$col = 0;
			echo "</tr><tr>";
		}
	        echo "<td valign=\"top\" class=\"$statbg\">\n";
	        $mstat = ($mn)?"$mn Monitored $stat":"";
		if($siz){
			echo "<center><a href=?reg=$ur><img src=\"img/32/glob.png\" title=\"$nd Devices $mstat\"></a><br>$r\n";
		}else{
			$gmap = "$ur";
			if($_SESSION['gmap']){
				$ns = $ew = "";
				$query	= GenQuery('locations','s','id,x,y,ns,ew,locdesc','','',array('region','city','building'),array('=','=','='),array($r,$c,$b),array('AND','AND'));
				$res	= @DbQuery($query,$link);
				if (@DbNumRows($res)){
					list($id,$x,$y,$ns,$ew,$com) = @DbFetchRow($res);
					$ns /= 10000000;
					$ew /= 10000000;
				}
				$s = ($_SESSION['gsiz'] < 3)?"160x120":"240x160";
				if($ns and $ew){
					echo "<center><a href=?reg=$ur&cty=$uc><img src=\"http://maps.google.com/maps/api/staticmap?zoom=12&size=$s&maptype=roadmap&sensor=false&markers=color:blue%7C$ns,$ew\" title=\"$nd Devices $mstat, $com\" style=\"border:1px solid black\"></a><br>\n";
					$gmap = "$ns,$ew";
				}else{
					echo "<center><a href=?reg=$ur><img src=\"http://maps.google.com/maps/api/staticmap?center=$ur&zoom=4&size=$s&maptype=roadmap&sensor=false\" title=\"$nd Devices $mstat\" style=\"border:1px solid black\"></a><br>\n";
				}
			}else{
				echo "<center><a href=?reg=$ur><img src=\"img/32/glob.png\" title=\"$nd Devices $mstat\"></a><br>\n";
			}
			echo "<a href=\"http://maps.google.com/maps?q=$gmap\" target=\"window\"><img src=\"img/16/map.png\" title=\"Google Maps\"></a>\n";
			echo "<a href=\"Devices-List.php?ina=location&opa=regexp&sta=%5E$ur\">$r</a>\n";
		}
		echo "</center></td>\n";
	        $col++;
	}
	echo "</tr></table>\n";
}

//===================================================================
// Generate region table
function TopoCities($reg="",$siz=0){

	global $link,$manlbl,$dcity,$locsep,$bg2,$netlbl;

	echo "<h2>".(($reg)?$reg:$manlbl)." $netlbl</h2>\n";
	echo "<table class=\"content fixed\"><tr>\n";

	$col = 0;
	$rec = 0;
	ksort($dcity);
	foreach (array_keys($dcity) as $r){
		if(!$reg or $r == $reg){
			if ($rec == "1"){ $rec = "0"; $bi = "imga"; }
			else{ $rec = "1"; $bi = "imgb"; }
			$ur = urlencode($r);
			ksort($dcity[$r]);
			foreach (array_keys($dcity[$r]) as $c){
				$nd = $dcity[$r][$c]['nd'];
				$ci = CtyImg($dcity[$r][$c]['nd']);
				$mn = isset( $dcity[$r][$c]['mn']) ? $dcity[$r][$c]['mn'] : 0;
				$al = isset( $dcity[$r][$c]['al']) ? $dcity[$r][$c]['al'] : 0;
				list($statbg,$stat) = StatusBg($nd,$mn,$al,$bi);
				$uc = urlencode($c);
				if ($col == $_SESSION['col']){
					$col = 0;
					echo "</tr><tr>";
				}
			        echo "<td valign=\"top\" class=\"$statbg\">\n";
			        $mstat = ($mn)?"$mn Monitored $stat":"";
				if($siz){
					echo "<center><a href=?reg=$ur&cty=$uc><img src=\"img/$ci.png\" title=\"$nd Devices $mstat\"></a><br>$c\n";
				}else{
					echo "<a href=\"Devices-List.php?ina=location&opa=regexp&sta=%5E$ur\">$r</a><p>\n";
					$gmap = "$uc+$ur";
					if($_SESSION['gmap']){
						$ns = $ew = "";
						$query	= GenQuery('locations','s','id,x,y,ns,ew,locdesc','','',array('region','city','building'),array('=','=','='),array($r,$c,''),array('AND','AND'));
						$res	= @DbQuery($query,$link);
						if (@DbNumRows($res)){
							list($id,$x,$y,$ns,$ew,$com) = @DbFetchRow($res);
							$ns /= 10000000;
							$ew /= 10000000;
						}
						$s = ($_SESSION['gsiz'] < 3)?"160x120":"240x160";
						if($ns and $ew){
							echo "<center><a href=?reg=$ur&cty=$uc><img src=\"http://maps.google.com/maps/api/staticmap?zoom=12&size=$s&maptype=roadmap&sensor=false&markers=color:purple%7C$ns,$ew\" title=\"$nd Devices $mstat, $com\" style=\"border:1px solid black\"></a><br>\n";
							$gmap = "$ns,$ew";
						}else{
							echo "<center><a href=?reg=$ur&cty=$uc><img src=\"http://maps.google.com/maps/api/staticmap?center=$uc+$ur&zoom=12&size=$s&maptype=roadmap&sensor=false\" title=\"$nd Devices $mstat\" style=\"border:1px solid black\"></a><br>\n";
						}
					}else{
						echo "<center><a href=?reg=$ur&cty=$uc><img src=\"img/$ci.png\" title=\"$nd Devices $mstat\"></a><br>\n";
					}
					echo "<a href=\"http://maps.google.com/maps?q=$gmap\" target=\"window\"><img src=\"img/16/map.png\" title=\"Google Maps\"></a>\n";
					echo "<a href=\"Devices-List.php?ina=location&opa=regexp&sta=%5E$ur$locsep$uc$locsep\"><b>$c</b></a>";
				}
				echo "</center></td>\n";
			        $col++;
			}
		}
	}
	echo "</tr></table>\n";
}

//===================================================================
// Generate city table
function TopoBuilds($r,$c,$siz=0){

	global $link,$dbuild,$locsep,$bg2;

	$ur = urlencode($r);
	$uc = urlencode($c);

	echo "<h2>$r $c</h2>\n";
	echo "<table class=\"content fixed\"><tr>\n";

	$col = 0;
	ksort($dbuild[$r][$c]);
	foreach (array_keys($dbuild[$r][$c]) as $b){
		$nr =  $dbuild[$r][$c][$b]['nr'];
		$nd =  $dbuild[$r][$c][$b]['nd'];
		$mn = isset( $dbuild[$r][$c][$b]['mn']) ? $dbuild[$r][$c][$b]['mn'] : 0;
		$al = isset( $dbuild[$r][$c][$b]['al']) ? $dbuild[$r][$c][$b]['al'] : 0;
		$bi = BldImg($nd,$b);
		list($statbg,$stat) = StatusBg($nd,$mn,$al,"imga");
		$ub = urlencode($b);
		if ($col == $_SESSION['col']){
			$col = 0;
			echo "</tr><tr>";
		}
	        echo "<td valign=\"top\" class=\"$statbg\">\n";
	        $mstat = ($mn)?"$mn Monitored $stat":"";
		if($siz){
			echo "<center><a href=?reg=$ur&cty=$uc&bld=$ub><img src=\"img/$bi.png\" title=\"$nd Devices $mstat\">$ri</a><br>$b\n";
		}else{
			$gmap = "$ub+$uc+$ur";
			if($_SESSION['gmap']){
				$ns = $ew = "";
				$query	= GenQuery('locations','s','id,x,y,ns,ew,locdesc','','',array('region','city','building'),array('=','=','='),array($r,$c,$b),array('AND','AND'));
				$res	= @DbQuery($query,$link);
				if (@DbNumRows($res)){
					list($id,$x,$y,$ns,$ew,$com) = @DbFetchRow($res);
					$ns /= 10000000;
					$ew /= 10000000;
				}
				$s = ($_SESSION['gsiz'] < 3)?"160x120":"240x160";
				if($ns and $ew){
					echo "<center><a href=?reg=$ur&cty=$uc&bld=$ub><img src=\"http://maps.google.com/maps/api/staticmap?zoom=16&size=$s&maptype=roadmap&sensor=false&markers=color:".((strpos($bi,"r"))?"red":"brown")."%7C$ns,$ew\" title=\"$nd Devices $mstat, $com\" style=\"border:1px solid black\"></a><br>\n";
					$gmap = "$ns,$ew";
				}else{
					echo "<center><a href=?reg=$ur&cty=$uc&bld=$ub><img src=\"http://maps.google.com/maps/api/staticmap?center=$uc+$ur&zoom=16&size=$s&maptype=roadmap&sensor=false\" title=\"$nd Devices $mstat\" style=\"border:1px solid black\"></a><br>\n";
				}
			}else{
				echo "<center><a href=?reg=$ur&cty=$uc&bld=$ub><img src=\"img/$bi.png\" title=\"$nd Devices $mstat\"></a>\n";
				if($nr > 1){
					echo "<img src=\"img/rtr2.png\" title=\"$nr routers\">";
				}elseif($nr == 1){
					echo "<img src=\"img/rtr1.png\" title=\"1 router\">";
				}
				echo "<br>";
				
			}
			echo "<a href=\"http://maps.google.com/maps?q=$gmap\" target=\"window\"><img src=\"img/16/map.png\" title=\"Google Maps\"></a>\n";
			echo "<a href=\"Devices-List.php?ina=location&opa=regexp&sta=%5E$ur$locsep$uc$locsep$ub$locsep\" valign=\"bottom\"><b>$b</b></a>";
		}
		echo "</center></td>\n";
		$col++;
	}
	echo "</tr></table>\n";
}

//===================================================================
// Generate building table
function TopoFloors($r,$c,$b,$siz=0){

	global $dev,$modgroup,$self;
?>
<h2><?=$r?> <?=$c?> <?=$b?></h2>
<table class="content fixed">
<?
	uksort($dev, "floorsort");
	$room = 0;
	foreach (array_keys($dev) as $fl){
		echo "<tr>\n\t<td class=\"$modgroup[$self]2\" width=80><h3>\n";
		if(!$siz){echo "<img src=\"img/stair.png\"><br>\n";}
		echo "$fl</h3>\n";
		if(!$siz){
			$base = "log/$r-$c-$b-$fl";
			foreach (glob("$base*.jpg") as $pic) {
				$lbl = substr($pic, strlen($base)+1, -4);
				echo "<a href=\"javascript:pop('$pic','$lbl')\"><img src=\"img/16/img.png\" title=\"$lbl\"></a> ";
			}
		}
		echo "</td>\n";
		$col = 0;
		ksort( $dev[$fl] );
		foreach (array_keys($dev[$fl]) as $rm){
			if ($room == "1"){ $room = "0"; $bi = "imga"; }
			else{ $room = "1"; $bi = "imgb"; }

			foreach (array_keys($dev[$fl][$rm]) as $d){
				$ip = long2ip($dev[$fl][$rm][$d]['ip']);
				$po = $dev[$fl][$rm][$d]['po'];
				$di = $dev[$fl][$rm][$d]['ic'];
				$co = $dev[$fl][$rm][$d]['co'];
				$rk = $dev[$fl][$rm][$d]['rk'];
				$mn = $dev[$fl][$rm][$d]['mn'];
				$al = $dev[$fl][$rm][$d]['al'];
				list($statbg,$stat) = StatusBg(1,$mn,$al,$bi);
				$tit = ($stat)?$stat:$dev[$fl][$rm][$d]['ty'];
				$ud = urlencode($d);
				if ($col == $_SESSION['col']){
					$col = 0;
					echo "</tr><tr><td>&nbsp;</td>\n";
				}
				if($siz){
					echo "<td class=\"$statbg\" valign=\"top\"><center><img src=\"img/dev/$di.png\" title=\"$ip\"><br>$d</center></td>\n";
				}else{
					echo "<td class=\"$statbg\" valign=\"top\"><b>$rm</b> $rk<p><center>\n";
					echo "<a href=\"Devices-Status.php?dev=$ud\"><img src=\"img/dev/$di.png\" title=\"$tit\"></a><br>\n";
					echo "<b>$d</b><br>\n";
					echo Devcli($ip,$po);
					echo"<p>$co</center></td>\n";
				}
				$col++;
			}
		}
	}
	echo "</tr></table>\n";
}

//===================================================================
// Return image for test
function TEimg($srv){

	if($srv == "ping")	{$img =  "relo";}
	elseif($srv == "uptime"){$img =  "clock";}
	elseif($srv == "http")	{$img =  "glob";}
	elseif($srv == "https")	{$img =  "glok";}
	elseif($srv == "telnet"){$img =  "loko";}
	elseif($srv == "ssh")	{$img =  "lokc";}
	elseif($srv == "mysql")	{$img =  "db";}
	elseif($srv == "cifs")	{$img =  "nwin";}
	elseif($srv == "")	{$img =  "bcls";}
	else{$img =  "bbox";}

	return "<img src=\"img/16/$img.png\" title=\"$srv\">";
}

?>
