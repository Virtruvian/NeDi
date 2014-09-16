<?php
# Program: Nodes-Status.php
# Programmer: Remo Rickli

$printable = 1;
$exportxls = 0;

include_once ("inc/header.php");
include_once ("inc/libnod.php");
include_once ("inc/libmon.php");
include_once ("inc/libdev.php");

$_GET = sanitize($_GET);
$mac = isset($_GET['mac']) ? $_GET['mac'] : "";
$wol = isset($_GET['wol']) ? $_GET['wol'] : "";
$wip = isset($_GET['wip']) ? $_GET['wip'] : "";
$del = isset($_GET['del']) ? $_GET['del'] : "";
$trk = isset($_GET['trk']) ? $_GET['trk'] : "";
$dip = isset($_GET['dip']) ? $_GET['dip'] : "";
$mon = isset($_GET['mon']) ? $_GET['mon'] : "";
$shg = isset($_GET['shg']) ? "checked" : "";
$srv = isset($_GET['srv']) ? "checked" : "";
?>
<script src="inc/Chart.min.js"></script>

<h1>Node <?= $stalbl ?></h1>

<?php  if( !isset($_GET['print']) ) { ?>

<form method="get" action="<?= $self ?>.php">
<table class="content"><tr class="bgmain">
<td class="ctr s">
	<a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png" title="<?= $self ?>"></a>
</td>
<td class="ctr">
	MAC <?= $adrlbl ?>
	<input type="text" name="mac" value="<?= $mac ?>" class="m">
</td>
<td class="ctr">
<?php if($rrdcmd and $_SESSION['gsiz']){ ?>
	<img src="img/16/grph.png" title="<?= $porlbl ?> <?= $gralbl ?>">
	<input type="checkbox" name="shg" <?= $shg ?>>
<?php } ?>
</td>
<td class="ctr s">
	<input type="submit" class="button" value="<?= $sholbl ?>">
</td>
</tr>
</table>
</form>
<p>

<?php
}
$link	= DbConnect($dbhost,$dbuser,$dbpass,$dbname);
if ($trk){
	$mac = $trk;
	if($isadmin){
		$query	= GenQuery('nodes','u',"mac = '$mac'",'','',array('ifchanges'),array(),array('0') );
		if( !DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$mac ifchanges $updlbl OK</h5>";}
		$query	= GenQuery('iptrack','d','','','',array('mac'),array('='),array($mac) );
		if( !DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$mac iptrack $dellbl OK</h5>";}
		$query	= GenQuery('iftrack','d','','','',array('mac'),array('='),array($mac) );
		if( !DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$mac iftrack $dellbl OK</h5>";}
	}else{
		echo $nokmsg;
	}
}elseif ($dip){
	$mac = $dip;
	if($isadmin){
		$query	= GenQuery('nodarp','d','','','',array('mac'),array('='),array($mac) );
		if( !DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$mac ARP $dellbl OK</h5>";}
		$query	= GenQuery('nodnd','d','','','',array('mac'),array('='),array($mac) );
		if( !DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$mac ND $dellbl OK</h5>";}
	}else{
		echo $nokmsg;
	}
}

if ($mac){
	$query	= GenQuery('nodes','s','nodes.*,type,location,contact,snmpversion,icon,sysobjid,linktype,iftype,ifdesc,alias,ifstat,speed,duplex,pvid,lastchg,inoct,outoct,inerr,outerr,indis,outdis,inbrc,dinoct,doutoct,dinerr,douterr,dindis,doutdis,dinbrc','','',array('mac'),array('='),array($mac),array(),'LEFT JOIN devices USING (device) LEFT JOIN interfaces USING (device,ifname)');
	$nres	= DbQuery($query,$link);
	$nnod	= DbNumRows($nres);
	while( ($n = DbFetchRow($nres)) ){
		$ouicon	= Nimg($n[1]);
		$dur	= intval(($n[3]-$n[2])/86400);
		$wasup	= ($n[3] > time() - $rrdstep*2)?1:0;
		$ud 	= urlencode($n[4]);
		$ui 	= urlencode($n[5]);
		$loc	= explode($locsep, $n[13]);
		$lit	= '';
		list($firstcol,$lastcol)  = Agecol($n[2],$n[3],0);
		list($ifchgcol,$ifchgcol) = Agecol($n[8],$n[8],1);
		list($devnd,$vndic)	  = DevVendor($n[17],substr($n[16],2,1));
		list($ifimg,$iftyp)	  = Iftype($n[19]);
		list($ifbg,$ifst)	  = Ifdbstat($n[22]);
		list($lnkhgt,$lnkcol)	  = LinkStyle( $n[23],0 );

		$vl[2] = "-";
		if($n[6] and preg_match('/[A-L]/',$n[7]) ){
			$query	= GenQuery('vlans','s','*','','',array('device','vlanid'),array('=','='),array($n[4],$n[6]),array('AND') );
			$res	= DbQuery($query,$link);
			if (DbNumRows($res) == 1) {
				$vl = DbFetchRow($res);
			}else{
				echo "<h4>Vlan DB $errlbl</h4>";
			}
			DbFreeResult($res);
		}
?>

<table class="full"><tr>
<td class="helper xl">
	<table class="content">
		<tr class="bgsub">
			<td colspan="2">
				<h2>Node
				<div  class="frgt">
<?php
		if($isadmin){
			echo "				<a href=\"?trk=$n[0]\"><img src=\"img/16/bdis.png\" onclick=\"return confirm('$dellbl IF/IP $chglbl  $n[0]?')\" title=\"$dellbl IF/IP $chglbl\"></a>";
			echo "				<a href=\"?dip=$n[0]\"><img src=\"img/16/glob.png\" onclick=\"return confirm('$dellbl IP $adrlbl  $n[0]?')\" title=\"$dellbl IP $adrlbl\"></a>";
			echo "				<a href=\"?del=$n[0]\"><img src=\"img/16/bcnl.png\" onclick=\"return confirm('$dellbl $n[0] ?')\" title=\"$dellbl Node!\"></a>";
		}
?>
				</div>
				</h2>
			</td>
		</tr>
		<tr class="txta">
			<td class="imga s b">
				MAC <?= $adrlbl ?>
			</td>
			<td class="drd">
				<?= rtrim(chunk_split($n[0],2,"-"),"-") ?><br>
				<?= rtrim(chunk_split($n[0],2,":"),":") ?><br>
				<?= rtrim(chunk_split($n[0],4,"."),".") ?>&nbsp;
				<a href="Monitoring-Events.php?in[]=info&op[]=~&st[]=<?= $n[0] ?>" title="MAC ~ Monitoring-Events" class="frgt"><img src="img/16/bell.png"></a>
				<a href="Nodes-List.php?in[]=mac&op[]==&st[]=<?= $n[0] ?>" title="MAC = Nodes-List" class="frgt"><img src="img/16/nods.png"></a>
			</td>
		</tr>
		<tr class="txtb">
			<td class="imgb s b">
				<?= $venlbl ?>
			</td>
			<td>
				<a href="http://www.google.com/search?q=<?= urlencode($n[1]) ?>&btnI=1" target="window"><img src="img/oui/<?= $ouicon ?>.png" title="Google <?= $venlbl ?>"></a>
				<a href="Nodes-List.php?in[]=oui&op[]==&st[]=<?= urlencode($n[1]) ?>"><?= $n[1] ?></a>
				<?= ($ouicon == 'vm')?"<a href=\"Devices-Modules.php?in[]=hw&op[]==&st[]=$n[2]\"><img src=\"img/16/node.png\" title=\"VM, Devices-Modules\" class=\"frgt\"></a>":""; ?>
			</td>
		</tr>
		<tr class="txta">
			<td class="imga s b">
				<?= $dsclbl ?>
			</td>
			<td class="ctr">
				<span  class="genpad" style="background-color:#<?= $firstcol ?>" title="<?= $fislbl ?>"><a href="Nodes-List.php?in[]=firstseen&op[]==&st[]=<?= $n[2] ?>"><?= date($_SESSION['timf'],$n[2]) ?></a></span>
				<?= Bar($dur,0,'mi',"$dur $tim[d]") ?>
				<span  class="genpad" style="background-color:#<?= $lastcol ?>" title="<?= $laslbl ?>"><a href="Nodes-List.php?in[]=lastseen&op[]==&st[]=<?= $n[3] ?>"><?= date($_SESSION['timf'],$n[3]) ?></a></span>
				<?= ($wasup)?"<img src=\"img/16/flas.png\" title=\"$stco[100]\" class=\"frgt\">":"<img src=\"img/16/bcls.png\" title=\"$stco[160]\" class=\"frgt\">"; ?>
			</td>
		</tr>
		<tr class="txtb">
			<td class="imgb s b">
				<?= $usrlbl ?>
			</td>
			<td>
				<?= $n[10] ?>
			</td>
		</tr>
		<tr>
			<td class="imga ctr b" colspan="2">
				IP <?= $adrlbl ?>
			</td>
		</tr>
<?php
		$irow   = 1;
		$query	= GenQuery('nodarp','s','nodarp.*,aname,dnsupdate,test,status','','',array('mac'),array('='),array($mac),array(),'LEFT JOIN dns USING (nodip) LEFT JOIN monitoring on (nodip = monip)' );
		$res	= DbQuery($query,$link);
		while( ($arp = DbFetchRow($res)) ){
			if ($irow % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$ip                 = long2ip($arp[1]);
			list($statbg,$stat) = StatusBg(1,($arp[13])?1:0,$arp[14]);
			list($iuc,$iuc)     = Agecol($arp[3],$arp[3],1);
			TblRow($bg);
?>
			<td class="<?= $statbg ?>" colspan="2">
				<?= $irow ?>&nbsp;
				<a href="Nodes-List.php?in[]=nodip&op[]==&st[]=<?= $ip ?>" title="<?= $updlbl ?> <?= date($_SESSION['timf'],$arp[3]) ?><?= ($arp[9])?", Device:$arp[9]":"" ?>, Nodes-List"><?= $ip ?></a>
				<strong><a href="Nodes-List.php?in[]=aname&op[]==&st[]=<?= $arp[11] ?>" title="<?= $updlbl ?> <?= date($_SESSION['timf'],$arp[12]) ?>, Nodes-List"><?= $arp[11] ?></a></strong>

				<a href="Monitoring-Events.php?in[]=info&op[]=~&st[]=<?= $ip ?>" title="IP ~ Monitoring-Events" class="frgt"><img src="img/16/bell.png"></a>
				<?= ($stat)?"<a href=\"Monitoring-Setup.php?in[]=monip&op[]=%3D&st[]=$ip\" class=\"frgt\">".TestImg($arp[13])."</a>":"" ?>
				<a href="Nodes-Toolbox.php?Dest=<?= $ip ?>" class="frgt"><img src="img/16/dril.png" title="Nodes-Toolbox"></a>
				<a href="Nodes-Stolen.php?na=<?= $arp[11] ?>&ip=<?= $ip ?>&stl=<?= $n[0] ?>&dev=<?= $ud ?>&ifn=<?= $ui ?>" title="Nodes-Stolen <?= $addlbl ?>" class="frgt"><img src="img/16/step.png"></a>
				<a href="?wol=<?= $n[0] ?>&wip=<?= $arp[1] ?>"  title="WoL <?= $srvlbl ?>" class="frgt"><img src="img/16/exit.png"></a>
			</td>
		</tr>
<?php
			$irow++;
		}
		DbFreeResult($res);

		$irow   = 1;
		$query	= GenQuery('nodnd','s','nodnd.*,aaaaname,dns6update','','',array('mac'),array('='),array($mac),array(),'LEFT JOIN dns6 USING (nodip6)' );
		$res	= DbQuery($query,$link);
		while( ($arp = DbFetchRow($res)) ){
			if ($irow % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$ip                 = DbIPv6($arp[1]);
			list($statbg,$stat) = StatusBg(1,($arp[13])?1:0,$arp[14]);
			list($iuc,$iuc)     = Agecol($arp[3],$arp[3],1);
			TblRow($bg);
?>
			<td colspan="2">
				<?= $irow ?>&nbsp;
				<a href="Nodes-List.php?in[]=nodip6&op[]==&st[]=<?= $ip ?>" title="<?= $updlbl ?> <?= date($_SESSION['timf'],$arp[3]) ?><?= ($arp[9])?", Device:$arp[9]":"" ?>, Nodes-List"><?= $ip ?></a>
				<strong><a href="Nodes-List.php?in[]=aaaaname&op[]==&st[]=<?= $arp[11] ?>" title="<?= $updlbl ?> <?= date($_SESSION['timf'],$arp[12]) ?>, Nodes-List"><?= $arp[11] ?></a></strong>

				<a href="Monitoring-Events.php?in[]=info&op[]=~&st[]=<?= $ip ?>" title="IP ~ Monitoring-Events" class="frgt"><img src="img/16/bell.png"></a>
			</td>
		</tr>
<?php
			$irow++;
		}
		DbFreeResult($res);

		TblRow('imga');
?>
			<td class="ctr b" colspan="2">
				<?= $metlbl ?> <?= $hislbl ?><p>
				<?php MetricChart("meth",4, $n[7]); ?><br>
				<span class="drd">0 = FD, 1 = HD</span><br>
				<span class="blu">1 = 10M, 2 = 100M, 3 = 1G, 4 = 10G</span>
			</td>
		</tr>
	</table>
</td><td class="ctr l">
<?php
		if( $n[18] ){
			$query	= GenQuery('links','s','neighbor,type,contact,icon','','',array('links.device','ifname'),array('=','='),array($n[4],$n[5]),array('AND'),'LEFT JOIN devices on (neighbor = devices.device)' );
			$res	= DbQuery($query,$link);
			while( ($neb = DbFetchRow($res)) ){
?>
	<div  class="genpad imga">
		<a href="Devices-Status.php?dev=<?= urlencode($neb[0]) ?>"><img src="img/dev/<?= $neb[3] ?>.png" title="<?= $neb[1] ?>, <?= $conlbl ?> <?= $neb[2] ?>, Devices-Status"></a>
		<a href="Devices-List.php?in[]=device&op[]==&st[]=<?= urlencode($neb[0]) ?>" title="Devices-List"><strong><?= $neb[0] ?></strong></a><br>
		<?= DecFix($n[23]) ?> - <?= $n[24] ?>
	</div>
<?php
			}
		}else{
			echo "	<h2>".DecFix($n[23])." - $n[24]</h2>\n";
		}	
?>
	<p>
	<div  class="genpad ctr m" style="background-color:#<?= $ifchgcol ?>" title="<?= $cnclbl ?> <?= $updlbl ?>"><a href="Nodes-List.php?in[]=ifupdate&op[]==&st[]=<?= $n[8] ?>"><?= date($_SESSION['timf'],$n[8]) ?></a></div>
	<div style="background-color:<?= $lnkcol ?>;height:<?= ($lnkhgt*4) ?>px;"></div>
	<?php if($shg) IfGraphs($ud, $ui, $n[23],4); ?>
</td><td class="helper xl">
	<table class="content">
		<tr class="bgsub">
			<td colspan="2">
				<h2>Device</h2>
			</td>
		</tr>
		<tr class="txta">
			<td class="imga s b">
				<?= $namlbl ?>
			</td>
			<td>
				<a href="Devices-Status.php?dev=<?= $ud ?>&pop=on"><img src="img/dev/<?= $n[16] ?>.png" title="Devices-Status"></a>
				<a href="Devices-List.php?in[]=device&op[]==&st[]=<?= $ud ?>" title="Devices-List"><strong><?= $n[4] ?></strong></a>
			</td>
		</tr>
		<tr class="txtb">
			<td class="imgb s b">
				<?= $typlbl ?>
			</td>
			<td>
				<a href="http://www.google.com/search?q=<?= urlencode($n[17]) ?>&btnI=1" target="window"><img src="img/oui/<?= $vndic ?>.png" title="<?= $devnd ?>"></a>
				<a href="Devices-List.php?in[]=type&op[]==&st[]=<?= urlencode($n[12]) ?>" title="Devices-List"><?= $n[12] ?></a>
			</td>
		</tr>
		<tr class="txta">
			<td class="imga s b">
				<?= $conlbl ?>
			</td>
			<td>
				<a href="Devices-List.php?in[]=contact&op[]==&st[]=<?= urlencode($n[14]) ?>"><?= $n[14] ?></a>
			</td>
		</tr>
		<tr class="txtb">
			<td class="imgb s b">
				<?= $loclbl ?>
			</td>
			<td>
				<?= $loc[1] ?>,<?= $loc[0] ?> <?= $loc[2] ?>, <?= $place['f'] ?> <?= $loc[3] ?>
			</td>
		</tr>
		<tr class="<?= ($ifbg)?$ifbg:"txtb" ?>">
			<td class="imga s b">
				<?= $porlbl ?>
			</td>
			<td>
				<img src="img/<?= $ifimg ?>" title="<?= $iftyp ?> - <?= $ifst ?>, <?= $laslbl ?> <?= $stalbl ?> <?= $chglbl ?> <?= date($_SESSION['timf'],$n[26]) ?>">
				<strong><?= $n[5] ?></strong> <?= $n[21] ?> <span class="gry"><?= $n[20] ?></span>
			</td>
		</tr>
		<tr class="txtb">
			<td class="imgb s b">
				Vlan
			</td>
			<td>
				<a href="Devices-Vlans.php?in[]=vlanid&op[]==&st[]=<?= $n[6] ?>"><?= $n[6] ?></a> - <?= $vl[2] ?>
			</td>
		</tr>
<?php
		TblRow('imga');
?>
			<td class="ctr b" colspan="2">
				<?= $stslbl ?> <?= $totlbl ?> / <?= $laslbl ?><p>
				<div class="full ctr">
					<?php IfRadar('radtot',4,'248',$n[27],$n[28],$n[29],$n[30],$n[31],$n[32],$n[33],1); ?>
					<?php IfRadar('radlast',4,'284',$n[34],$n[35],$n[36],$n[37],$n[38],$n[39],$n[40],1); ?>
				</div>
			</td>
		</tr>
	</table>
</td>
</tr></table>

<table class="full fixed">
<tr><td class="helper">

	<h2>IP <?= $chglbl ?></h2>

<?php
		$query	= GenQuery('iptrack','s','*','ipupdate','',array('mac'),array('='),array($n[0]) );
		$res	= DbQuery($query,$link);
		if( DbNumRows($res) ){
?>
	<table class="content">
		<tr class="bgsub">
			<th colspan="2"><img src="img/16/clock.png"><br><?= $updlbl ?></th>
			<th><img src="img/16/abc.png"><br><?= $namlbl ?></th>
			<th><img src="img/16/net.png"><br>IP <?= $adrlbl ?></th>
		</tr>
	</table>
	<div class="scroller">
	<table class="content" >
<?php
			$row = 0;
			while( $r = DbFetchRow($res) ){
				if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
				$row++;
				$lip = long2ip($r[3]);
				echo "		<tr class=\"$bg\">\n";
				echo "			<td class=\"$bi ctr b\">\n				$row\n			</td>\n";
				echo "			<td>\n				".date($_SESSION['timf'],$r[1]) ."\n			</td>\n";
				echo "			<td>\n				$r[2]\n			</td>\n";
				echo "			<td>\n				<a href=\"Nodes-List.php?in[]=nodip&op[]==&st[]=$lip\">$lip</a>\n			</td>\n";
				echo "		</tr>\n";
			}
?>
	</table>
	</div>
	<table class="content">
		<tr class="bgsub"><td><?= $row ?> IP <?= $chglbl ?></td></tr>
	</table>
<?php
		}else{
?>
	<h5><?= $nonlbl ?></h5>
<?php
		}
		DbFreeResult($res);
?>

</td><td class="helper">

	<h2>IF <?= $chglbl ?></h2>

<?php
		$query	= GenQuery('iftrack','s','*','ifupdate','',array('mac'),array('='),array($n[0]) );
		$res	= DbQuery($query,$link);
		if( DbNumRows($res) ){
?>
	<table class="content">
		<tr class="bgsub">
			<th colspan="2"><img src="img/16/clock.png"><br><?= $updlbl ?></th>
			<th><img src="img/16/dev.png"><br>Device</th>
			<th><img src="img/16/port.png"><br>IF</th>
			<th><img src="img/16/vlan.png"><br>Vlan</th>
		</tr>
	</table>
	<div class="scroller">
	<table class="content" >
<?php
			$row = 0;
			while( $r = DbFetchRow($res) ){
				if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
				$row++;
				$utd = rawurlencode($r[2]);
				$uti = rawurlencode($r[3]);
				echo "		<tr class=\"$bg\">\n";
				echo "			<td class=\"$bi ctr b\">\n				$row\n			</td>\n";
				echo "			<td>\n				". date($_SESSION['timf'],$r[1]) ."\n			</td>\n";
				echo "			<td>\n				<a href=\"Devices-Status.php?dev=$utd&shp=on\">$r[2]</a>\n			</td>\n";
				echo "			<td>\n				<a href=\"Nodes-List.php?in[]=device&op[]==&st[]=$utd&co[]=AND&in[]=ifname&op[]==&st[]=$uti\">$r[3]</a>\n			</td>\n";
				echo "			<td>\n				$r[4]\n			</td>\n";
				echo "		</tr>\n";
			}
?>
	</table>
	</div>
	<table class="content">
		<tr class="bgsub"><td><?= $row ?> IF <?= $chglbl ?></td></tr>
	</table>
<?php
		}else{
?>
<h5><?= $nonlbl ?></h5>
<?php
		}
		DbFreeResult($res);
?>

</td></tr></table>
<?php
	}
	DbFreeResult($nres);
}elseif ($wol and $wip){
	if(preg_match("/dsk/",$_SESSION['group']) ){
		$query	= GenQuery('networks','s','inet_ntoa(ifip|power(2, 32 - prefix )-1)','','1',array('ifip','(ifip|power(2, 32 - prefix )-1)'),array('>','COL ='),array(0,"($wip|power(2, 32 - prefix )-1)"),array('AND'));
		$bres = DbQuery($query,$link);
		$bcst = DbFetchRow($bres);
		Wake($bcst[0],$wol, 9);
		Wake("255.255.255.255",$wol, 9);							# In case local broadcast addr is not allowed
	}else{
		echo $nokmsg;
	}
?>
<script language="JavaScript"><!--
setTimeout("history.go(-1)",3000);
//--></script>
<?php
}elseif ($del){
	if($isadmin){
		$link	= DbConnect($dbhost,$dbuser,$dbpass,$dbname);
		NodDelete($del);
?>
<script language="JavaScript"><!--
setTimeout("history.go(-2)",3000);
//--></script>
<?php
	}else{
		echo $nokmsg;
	}
}

include_once ("inc/footer.php");
?>
