<?
# Program: Devices-Status.php
# Programmer: Remo Rickli

error_reporting(1);
snmp_set_quick_print(1);
snmp_set_oid_numeric_print(1);
snmp_set_valueretrieval(SNMP_VALUE_LIBRARY);

$printable = 1;

include_once ("inc/header.php");
include_once ("inc/libdev.php");
include_once ("inc/libmon.php");
include_once ("inc/libsnmp.php");

$_GET = sanitize($_GET);
$shd = isset($_GET['dev']) ? $_GET['dev'] : "";
$rtl = isset($_GET['rtl']) ? 1:0;
$cif = isset($_GET['cif']) ? $_GET['cif'] : "";
$loc = isset($_GET['loc']) ? $_GET['loc'] : "";
$con = isset($_GET['con']) ? $_GET['con'] : "";
$mon = isset($_GET['mon']) ? 1 : "";
$dld = isset($_GET['del']) ? $_GET['del'] : "";
$trg = isset($_GET['trg']) ? $_GET['trg'] : "";
$erg = isset($_GET['erg']) ? $_GET['erg'] : "";
$brg = isset($_GET['brg']) ? $_GET['brg'] : "";
$dig = isset($_GET['dig']) ? $_GET['dig'] : "";
$pop = isset($_GET['pop']) ? $_GET['pop'] : "";

$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
?>
<h1>Device <?=$stalbl?></h1>

<?if( !isset($_GET['print']) ){?>

<form method="get" action="<?=$self?>.php">
<table class="content"><tr class="<?=$modgroup[$self]?>1">
<th width="50"><a href="<?=$self?>.php"><img src="img/32/<?=$selfi?>.png"></a></th>
<th>
<select size="1" name="dev">
<option value="">Device ->
<?
$query	= GenQuery('devices','s','device','device');
$res	= @DbQuery($query,$link);
if($res){
	while( $d = @DbFetchRow($res) ){
		echo "<option value=\"$d[0]\"".( ($shd == $d[0])?"selected":"").">$d[0]\n";
	}
	@DbFreeResult($res);
}else{
	print @DbError($link);
	die ( mysql_error() );
}
?>
</select>

</th>
<? if($rrdcmd){ ?>
<th>

<img src="img/16/grph.png" title="IF <?=$gralbl?>">
<input type="checkbox" name="trg" <?=($trg)?"checked":""?>><?=$trflbl?>
<input type="checkbox" name="erg" <?=($erg)?"checked":""?>><?=$errlbl?>
<input type="checkbox" name="dig" <?=($dig)?"checked":""?>>Discards
<input type="checkbox" name="brg" <?=($brg)?"checked":""?>>Broadcasts <?=$inblbl?>

</th>
<?}?>

<th>
<img src="img/16/nods.png" title="<?=$sholbl?> <?=$poplbl?>">
<input type="checkbox" name="pop" <?=($pop)?"checked":""?>>
</th>
<th width="80">
<input type="submit" value="<?=$sholbl?>">
</th>
</tr></table></form><p>
<?
}
if ($dld){
	if($isadmin){
		$query	= GenQuery('devices','d','','','',array('device'),array('='),array($dld) );
		if( !@DbQuery($query,$link) ){echo "<h4>Device ".DbError($link)."</h4>";}else{echo "<h5>Device $dld $dellbl OK</h5>";}
		$query	= GenQuery('interfaces','d','','','',array('device'),array('='),array($dld) );
		if( !@DbQuery($query,$link) ){echo "<h4>IF ".DbError($link)."</h4>";}else{echo "<h5>IF $dld $dellbl OK</h5>";}
		$query	= GenQuery('modules','d','','','',array('device'),array('='),array($dld) );
		if( !@DbQuery($query,$link) ){echo "<h4>Modules ".DbError($link)."</h4>";}else{echo "<h5>Modules $dld $dellbl OK</h5>";}
		$query	= GenQuery('links','d','','','',array('device'),array('='),array($dld) );
		if( !@DbQuery($query,$link) ){echo "<h4>Links ".DbError($link)."</h4>";}else{echo "<h5>Links $dld $dellbl OK</h5>";}
		$query	= GenQuery('links','d','','','',array('neighbor'),array('='),array($dld) );
		if( !@DbQuery($query,$link) ){echo "<h4>Links ".DbError($link)."</h4>";}else{echo "<h5>Links $dld $dellbl OK</h5>";}
		$query	= GenQuery('configs','d','','','',array('device'),array('='),array($dld) );
		if( !@DbQuery($query,$link) ){echo "<h4>Config ".DbError($link)."</h4>";}else{echo "<h5>Config $dld $dellbl OK</h5>";}
		$query	= GenQuery('monitoring','d','','','',array('name'),array('='),array($dld) );
		if( !@DbQuery($query,$link) ){echo "<h4>Monitoring ".DbError($link)."</h4>";}else{echo "<h5>Monitoring $dld $dellbl OK</h5>";}
		$query	= GenQuery('incidents','d','','','',array('name'),array('='),array($dld) );
		if( !@DbQuery($query,$link) ){echo "<h4>Incidents ".DbError($link)."</h4>";}else{echo "<h5>Incidents $dld $dellbl OK</h5>";}
		$query	= GenQuery('vlans','d','','','',array('device'),array('='),array($dld) );
		if( !@DbQuery($query,$link) ){echo "<h4>Vlans ".DbError($link)."</h4>";}else{echo "<h5>Vlans $dld $dellbl OK</h5>";}
				$query	= GenQuery('networks','d','','','',array('device'),array('='),array($dld) );
		if( !@DbQuery($query,$link) ){echo "<h4>Networks ".DbError($link)."</h4>";}else{echo "<h5>Networks $dld $dellbl OK</h5>";}
		$query	= GenQuery('events','d','','','',array('source'),array('='),array($dld) );
		if( !@DbQuery($query,$link) ){echo "<h4>Events ".DbError($link)."</h4>";}else{echo "<h5>Events $dld $dellbl OK</h5>";}

		$query	= GenQuery('devdel','i','','','',array('device','user','time'),'',array($dld,$_SESSION['user'],time()) );
		if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$dellbl $dld $updlbl OK</h5>";}

		$query = GenQuery('events','i','','','',array('level','time','source','info','class','device'),'',array('100',time(),$dld,"User $_SESSION[user] deleted this device",'usrd',$shd) );
		if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$msglbl $updlbl OK</h5>";}

?>
<script language="JavaScript"><!--
setTimeout("history.go(-2)",2000);
//--></script>
<?
	}else{
		echo $nokmsg;
	}
}elseif ($shd){
if ($cactiuser and $cactihost and $cactidb){
	$clink  = @dbConnect($cactihost,$cactiuser,$cactipass,$cactidb);
	$cquery = GenQuery('host','s','id','','',array('description','hostname'),array('=','='),array($shd,$shd),array('OR') );
	$cres   = @DbQuery($cquery,$clink);
	if ( @DbNumRows($cres) == 1) {
	        $caho = @DbFetchRow($cres);
	}
	@DbFreeResult($cres);
}
$query	= GenQuery('networks','s','*','','',array('device'),array('='),array($shd) );
$res	= @DbQuery($query,$link);
while( $n = @DbFetchRow($res) ){
	$net[$n[1]][$n[2]] = ip2long(long2ip($n[3]));		// thanks again PHP (for increased grey hair count to fix netmask)!
	$vrf[$n[1]][$n[2]] = $n[4];

}
@DbFreeResult($res);

$query	= GenQuery('devices','s','*','','',array('device'),array('='),array($shd) );
$res	= @DbQuery($query,$link);
$ndev	= @DbNumRows($res);
if ($ndev != 1) {
	echo "<h4>$shd: $nonlbl</h4>";
	@DbFreeResult($res);
	die;
}
$dev	= @DbFetchRow($res);
@DbFreeResult($res);

$ud		= rawurlencode($dev[0]);
$ip		= ($dev[1]) ? long2ip($dev[1]) : 0;
$us		= (strlen($dev[2]) > 1)?urlencode($dev[2]):0;
$oi		= ($dev[19]) ? long2ip($dev[19]) : 0;
$img		= $dev[18];
list($fc,$lc)	= Agecol($dev[4],$dev[5],0);
$wasup		= ($dev[5] > time() - $rrdstep*2)?1:0;
$fs		= date($datfmt,$dev[4]);
$ls		= date($datfmt,$dev[5]);
$os		= $dev[8];
$rcomm		= (($guiauth != 'none')?$dev[15]:"***");
$wcomm		= (($isadmin and $guiauth != 'none')?$dev[26]:"***");
$rver		= $dev[14] & 3;
$wver		= ($dev[14] & 12) >> 2;
$cliport	= $dev[16];
$login		= $dev[17];
$sysobj		= $dev[25];

$query	= GenQuery('interfaces','s','*','ifidx','',array('device'),array('='),array($shd) );
$res	= @DbQuery($query,$link);
while( $i = @DbFetchRow($res) ){
	$ifn[$i[2]] = $i[1];
	$ift[$i[2]] = $i[4];
	$ifa[$i[2]] = $i[8];
	$ifs[$i[2]] = DecFix($i[9]);
	$ifd[$i[2]] = $i[10];
	$ifi[$i[2]] = "$i[6] <i>$i[7]</i> $i[20]";
	$ifv[$i[2]] = $i[11];
	$ifm[$i[2]] = $i[5];
	$ino[$i[2]] = $i[12];
	$oto[$i[2]] = $i[14];
	$dio[$i[2]] = $i[16];
	$die[$i[2]] = $i[17];
	$doo[$i[2]] = $i[18];
	$doe[$i[2]] = $i[19];
	$ifp[$i[2]] = $i[21];
}

$query	= GenQuery('monitoring','s','*','','',array('name'),array('='),array($ud) );
$res	= @DbQuery($query,$link);
if (@DbNumRows($res) == 1){
	$most = @DbFetchRow($res);
	list($statbg,$stat) = StatusBg(1,($most[3])?1:0,$most[5]);
	if(!$wasup){
		$statbg .= " part";
		$stat    = "Mon $stat, $laslbl $dsclbl < $rrdstep $tim[s]?";
	}
}else{
	$statbg = "imga";
}

if($us){
	$query	= GenQuery('stock','s','*','','',array('serial'),array('='),array($dev[2]) );
	$res	= @DbQuery($query,$link);
	if (@DbNumRows($res) == 1) {
		$stock = @DbFetchRow($res);
	}
}

@DbFreeResult($res);

if($isadmin){
	if ($rtl){
		$cliport = 0;
		$query	= GenQuery('devices','u',"device=\"$shd\"",'','',array('cliport'),'',array('0') );
		if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>CLI $reslbl OK</h5>";}
	}elseif ($cif){
		$s = substr($cif,0,1);
		$act = ($s == 1)?"enabled":"disabled";
		$i = substr($cif,1);
		if( Set($ip, $wver, $dev[26], "1.3.6.1.2.1.2.2.1.7.$i", 'i', ($s)?1:2 ) ){
			echo "<h5>SNMP IF $chglbl OK</h5>";
			$query	= GenQuery('interfaces','u',"device=\"$shd\" AND ifname=\"$ifn[$i]\"",'','',array('ifstat'),'',array($s) );
			if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>DB $ifn[$i] $act OK</h5>";$ifa[$i] = $s;}
			$query = GenQuery('events','i','','','',array('level','time','source','info','class','device'),'',array('100',time(),$shd,"User $_SESSION[user] $act interface $ifn[$i]",'usrd',$shd) );
			if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$msglbl $updlbl OK</h5>";}
		}else{
			echo "<h4>IF $chglbl $errlbl</h4>";
		}
	}elseif ($loc){
		if( Set($ip, $wver, $dev[26], "1.3.6.1.2.1.1.6.0", 's', $loc ) ){
			echo "<h5>SNMP $loclbl $chglbl OK</h5>";
			$dev[10] = $loc;
			$query	= GenQuery('devices','u',"device=\"$shd\"",'','',array('location'),'',array($loc) );
			if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>DB $loclbl $chglbl OK</h5>";}
			$query = GenQuery('events','i','','','',array('level','time','source','info','class','device'),'',array('100',time(),$shd,"User $_SESSION[user] changed location to $loc",'usrd',$shd) );
			if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$msglbl $updlbl OK</h5>";}
		}else{
			echo "<h4>$loclbl $chglbl $errlbl</h4>";
		}
	}elseif ($con){
		if( Set($ip, $wver, $dev[26], "1.3.6.1.2.1.1.4.0", 's', $con ) ){
			$dev[11] = $con;
			echo "<h5>SNMP $conlbl $chglbl OK</h5>";
			$query	= GenQuery('devices','u',"device=\"$shd\"",'','',array('contact'),'',array($con) );
			if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>DB $conlbl $chglbl OK</h5>";}
			$query = GenQuery('events','i','','','',array('level','time','source','info','class','device'),'',array('100',time(),$shd,"User $_SESSION[user] changed contact to $con",'usrd',$shd) );
			if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$msglbl $updlbl OK</h5>";}
		}else{
			echo "<h4>$loclbl $chglbl $errlbl</h4>";
		}
	}
}
?>

<table class="full fixed"><tr><td class="helper">

<h2><?=$sumlbl?></h2><p>
<table class="content"><tr>
<th class="<?=$statbg?>"><a href="?dev=<?=$ud?>"><img src="img/dev/<?=$img?>.png" title="<?=$stat?>" vspace=4></a><br><?=$dev[0]?></th>
<th class="<?=$modgroup[$self]?>2">

<div style="float:left">
<?if ($rver){?>
<?
	if ( isset($caho[0]) ){
?><a target="window" href="<?=$cactiurl?>/graph_view.php?action=preview&host_id=<?=$caho[0]?>"><img src="img/cacti.png"></a><?
	}
	if (file_exists("log/devtools.php")) {						# Based on Steffen's idea
		include_once ("log/devtools.php");
	}

}
?>
</div>
<div style="float:right">
<?if($rver){?>
<a href="Other-Defgen.php?so=<?=$sysobj?>&ip=<?=$ip?>&co=<?=$dev[15]?>"><img src="img/16/geom.png" title="Edit Def"></a>
<?
}
if($isadmin){
	if(!is_array($most) ){
		if ($mon == 1){
			if($dev[14]){
				echo AddRecord('monitoring',"name=\"$dev[0]\"","name,monip,test,device","\"$dev[0]\",\"$dev[1]\",\"uptime\",\"$dev[0]\"");
			}else{
				echo AddRecord('monitoring',"name=\"$dev[0]\"","name,monip,test,device","\"$dev[0]\",\"$dev[1]\",\"ping\",\"$dev[0]\"");
			}
		}else{
			echo "<a href=\"?dev=$ud&mon=1\"><img src=\"img/16/bino.png\" title=\"Monitor $addlbl\"></a>";
		}
	}else{
		echo "<a href=\"Monitoring-Setup.php?ina=name&opa=%3D&sta=$ud\">".TestImg($most[3])."</a>";
	}
?>
<a href="<?=$self?>.php?del=<?=$ud?>"><img src="img/16/bcnl.png" title="<?=$dellbl?>!" onclick="return confirm('<?=$dellbl?> <?=$dev[0]?> ?')"></a>
<?}?>

</div>

</th></tr>
<tr><th class="<?=$modgroup[$self]?>2"><?=$manlbl?> IP</th><td class="txtb">

<div style="float:right">
<a href="telnet://<?=$ip?>"><img src="img/16/loko.png" title="Telnet"></a>
<a href="ssh://<?=$ip?>"><img src="img/16/lokc.png" title="SSH"></a>
<a href="http://<?=$ip?>" target="window"><img src="img/16/glob.png" title="HTTP"></a>
<a href="https://<?=$ip?>" target="window"><img src="img/16/glok.png" title="HTTPS"></a>
<a href="Nodes-Toolbox.php?Dest=<?=$ip?>"><img src="img/16/dril.png" title="Toolbox"></a>
</div>

<?=(Devcli($ip,$cliport))?>
</td></tr>
<tr><th class="<?=$modgroup[$self]?>2"><?=$orilbl?> IP</th><td class="txta">

<div style="float:right">
<a href="telnet://<?=$oi?>"><img src="img/16/loko.png" title="Telnet"></a>
<a href="ssh://<?=$oi?>"><img src="img/16/lokc.png" title="SSH"></a>
<a href="http://<?=$oi?>" target="window"><img src="img/16/glob.png" title="HTTP"></a>
<a href="https://<?=$oi?>" target="window"><img src="img/16/glok.png" title="HTTPS"></a>
<a href="Nodes-Toolbox.php?Dest=<?=$oi?>"><img src="img/16/dril.png" title="Toolbox"></a>
</div>

<?=(Devcli($oi,$cliport))?>
</td></tr>
<tr><th class="<?=$modgroup[$self]?>2"><?=$srvlbl?></th><td class="txtb">
<div style="float:right">
<?if($dev[6] > 3){?>
<a href="Topology-Routes.php?rtr=<?=$ud?>"><img src="img/16/rout.png" title="Topology-Routes"></a>
<a href="Topology-Multicast.php?rtr=<?=$ud?>"><img src="img/16/cam.png" title="Topology-Multicast <?=$lstlbl?>"></a>
<?}
if($dev[6] & 2){?>
<a href="Topology-Spanningtree.php?dev=<?=$ud?>"><img src="img/16/traf.png" title="Topology-Spanningtree"></a>
<?}?>
<a href="Nodes-List.php?ina=device&opa==&sta=<?=$ud?>&ord=ifname"><img src="img/16/nods.png" title="Nodes <?=$lstlbl?>"></a>
</div>
<?=Syssrv($dev[6])?>
</td></tr>
<tr><th class="<?=$modgroup[$self]?>2">Bootimage</th>	<td class="txta"><?=$dev[9]?> (<?=$os?>)</td></tr>
<tr><th class="<?=$modgroup[$self]?>2"><?=$serlbl?></th>
<td class="txtb">
<?
	if( is_array($stock) ){
		echo "<a href=\"Devices-Stock.php?chg=$us\">$dev[2]</a>";
	}else{
		echo "$dev[2]";
		if($us){
?>
<div style="float:right">
<a href="Devices-Stock.php?ser=<?=$us?>&typ=<?=urlencode($dev[3])?>&loc=<?=urlencode($dev[10])?>&com=Discovered+as+<?=$ud?>&sta=150"><img src="img/16/pkg.png" title="<?=$addlbl?> Stock"></a>
</div>
<?		}
	}
?>
</td></tr>
<tr><th class="<?=$modgroup[$self]?>2"><?=$deslbl?></th><td class="txta"><b><?=$dev[3]?></b> <?=$dev[7]?></td></tr>
<tr><th class="<?=$modgroup[$self]?>2"><?=$loclbl?></th><td class="txtb">
<?
	if($isadmin and $dev[26]){
?>
<form method="get">
<div style="float:right">
<input type="image" src="img/16/ncfg.png" value="Submit" onclick="return confirm('<?=$updlbl?> <?=$loclbl?>, <?=$cfmmsg?>');">
</div>
<input type="hidden" name="dev" value="<?=$dev[0]?>">
<input type="text" name="loc" size="30" value="<?=$dev[10]?>">
</form>
</td></tr>
<?
	}else{
		echo "$dev[10]</td></tr>\n";
	}
?>
<tr><th class="<?=$modgroup[$self]?>2"><?=$conlbl?></th><td class="txta">
<?
	if($isadmin and $dev[26]){
?>
<form method="get">
<div style="float:right">
<input type="image" src="img/16/ucfg.png" value="Submit" onclick="return confirm('<?=$updlbl?> <?=$conlbl?>, <?=$cfmmsg?>');">
</div>
<input type="hidden" name="dev" value="<?=$dev[0]?>">
<input type="text" name="con" size="30" value="<?=$dev[11]?>">
</form>
</td></tr>
<?
	}else{
		echo "$dev[11]</td></tr>\n";
	}
?>
<tr><th class="<?=$modgroup[$self]?>2">VTP</th>		<td class="txtb">Domain: <?=$dev[12]?> Mode: <?=VTPmod($dev[13])?></td></tr>
<tr><th class="<?=$modgroup[$self]?>2">SNMP</th>	<td class="txta">
<? if($isadmin){?>
<div style="float:right;margin:2px 2px">
<form method="post" name="nedi" action="System-NeDi.php">
<input type="hidden" name="mod" value="d">
<input type="hidden" name="bup" value="b">
<input type="hidden" name="sed" value="a">
<input type="hidden" name="vrb" value="v">
<input type="hidden" name="opt" value="<?=$ip?>">
<input type="image" src="img/16/radr.png" value="Submit" title="<?=$dsclbl?> & <?=$cfglbl?> <?=$buplbl?>">
</form>
</div>
<?}?>
<?=($rver and $dev[26])?"<img src=\"img/bulbg.png\">":"<img src=\"img/bulbr.png\">"?>
<?=$realbl?> <?=$rcomm?> v<?=($rver  . (($dev[14] & 128)?(($dev[14] & 64)?"-MC":"-HC"):""));?>&nbsp;
<?=($wver and $dev[26])?"<img src=\"img/bulbg.png\"> $wrtlbl $wcomm v$wver":"<img src=\"img/bulbr.png\"> $wrtlbl"?>
</td></tr>
<tr><th class="<?=$modgroup[$self]?>2">CLI</th><td class="txtb">
<?
$query	= GenQuery('configs','s','*','','',array('device'),array('='),array($shd) );
$res	= @DbQuery($query,$link);
if (@DbNumRows($res) == 1) {
?>
<div style="float:right">
<a href="Devices-Config.php?shc=<?=$ud?>"><img src="img/16/conf.png" title="<?=$cfglbl?>"></a>
<a href="Devices-Doctor.php?dev=<?=$ud?>"><img src="img/16/cinf.png" title="<?=$cfglbl?> <?=$sumlbl?>"></a>
</div>
<?

}
if($cliport){
	if($isadmin){
		if($login){
			if( preg_match("/^(IOS|EOS|Ironware|ProCurve|Nortel)/",$os) ){
				$shlog = "show log";
			}elseif($os == "CatOS"){
				$shlog = "show logging buf";
			}elseif($os == "ESX"){
				$shlog = "tail -100 /var/log/messages";
			}elseif($os == "Comware"){
				$shlog = "dis log";
			}else{
				$shlog = "";
			}
			if($shlog){
?>
<div style="float:right;margin:2px 2px">
<form method="post" action="Devices-Write.php">
<input type="hidden" name="sta" value="<?=$dev[0]?>">
<input type="hidden" name="cmd" value="<?=$shlog?>">
<input type="hidden" name="ina" value="device">
<input type="hidden" name="opa" value="=">
<input type="hidden" name="scm" value="1">
<input type="image" src="img/16/note.png" value="Submit" title="<?=$sholbl?> Log">
</form>
</div>
<?			}
		}
	}
?>
<div style="float:right">
<a href="?dev=<?=$ud?>&rtl=1"><img src="img/16/kons.png" title="<?=$replbl?>"></a>
</div>
<?
}?>

<?=($cliport and $login)?"<img src=\"img/bulbg.png\">":"<img src=\"img/bulbr.png\">"?> <?=$login?> Port <?=($cliport)?$cliport:"-"?></td></tr>
<tr><th class="<?=$modgroup[$self]?>2"><?=$fislbl?></td><td bgcolor="#<?=$fc?>"><?=$fs?></td></tr>
<tr><th class="<?=$modgroup[$self]?>2"><?=$laslbl?></td><td bgcolor="#<?=$lc?>"><?=$ls?></td></tr>
<?
if($rver and $rrdcmd){
	$gsiz = ($_SESSION['gsiz'] == 4)?2:1;
	echo "<tr><th class=\"$modgroup[$self]2\">System</th><th class=\"txtb\">";
	echo "<a href=\"Devices-Graph.php?dv=$ud&if[]=cpu\"><img src=\"inc/drawrrd.php?dv=$ud&t=cpu&s=$gsiz\" title=\"CPU $dev[20]%\">";
	if($dev[24] and $dev[24] != "MemIO"){
		list($ct,$cy,$cu,$mu) = explode(";", $dev[24]);
	}else{
		$ct = "$memlbl IO";
		$cu = "Bytes $frelbl";
	}
	$mlbl = (strstr($mu,'%'))?"% $frelbl":"Bytes $frelbl";
	if($dev[21]){echo "<a href=\"Devices-Graph.php?dv=$ud&if[]=mem\"><img src=\"inc/drawrrd.php?dv=$ud&t=mem&s=$gsiz\" title=\"$memlbl ".DecFix($dev[21])."$mlbl\">";}
	if($dev[22]){
		$tmp = ($_SESSION['gfar'])?($dev[22]*1.8+32)."F":"$dev[22]C";
		echo "<a href=\"Devices-Graph.php?dv=$ud&if[]=tmp\"><img src=\"inc/drawrrd.php?dv=$ud&t=tmp&s=$gsiz\" title=\"$tmplbl $tmp\">";
	}
	if($dev[23]){
		echo "<a href=\"Devices-Graph.php?dv=$ud&if[]=cuv\"><img src=\"inc/drawrrd.php?dv=$ud&if[]=".urlencode($ct)."&if[]=".urlencode($cu)."&s=$gsiz&t=cuv\" title=\"$ct: ".DecFix($dev[23])."$cu\">";
	}
	echo "</th></tr>";
}

flush();
if ($rver){
	echo "<tr><th class=\"$modgroup[$self]2\">Uptime</th><th class=\"txta\">";
	$uptime	= ($wasup)?Get($ip, $rver, $dev[15], "1.3.6.1.2.1.1.3.0"):0;
	if ($uptime){
		sscanf($uptime, "%d:%d:%d:%d.%d",$upd,$uph,$upm,$ups,$ticks);
		$upmin	= $upm + 60 * $uph + 1440 * $upd;
		if ($upd  < 1) {echo "<img src=\"img/16/warn.png\" title=\"< 24 $tim[h]\"> ";} else { echo "<img src=\"img/16/bchk.png\"> ";}
		echo sprintf("%d D %d:%02d:%02d",$upd,$uph,$upm,$ups);
	}else{
		echo "$toumsg";
		if($_SESSION['vol']){echo "<embed src=\"inc/enter2.mp3\" volume=\"$_SESSION[vol]\" hidden=\"true\">\n";}
	}
	echo "</th></tr>\n";
}
flush();
?>
</table>

</td><td class="helper">

<h2>
<a href="Topology-Linked.php?dv=<?=$ud?>"><img src="img/16/ncon.png" title="Edit Links"></a>
Top <?=$_SESSION['lim']?> Links</h2>

<?
$query	= GenQuery('links','s','*','ifname',$_SESSION['lim'],array('device'),array('='),array($shd) );
$res	= @DbQuery($query,$link);
$row  = 0;
$nlink = @DbNumRows($res);
if($nlink){
?>
<table class="content" ><tr class="<?=$modgroup[$self]?>2">
<th valign="bottom" width="80"><img src="img/16/port.png"><br>Interface</th>
<th valign="bottom"><img src="img/16/dev.png"><br><?=$neblbl?></th>
<th><img src="img/16/tap.png"><br><?=$bwdlbl?></th>
<th><img src="img/16/abc.png"><br><?=$typlbl?></th>
<th><img src="img/16/say.png"><br><?=$cmtlbl?></th>
</tr>
<?	while( $l = @DbFetchRow($res) ){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		$ul = rawurlencode($l[3]);
		echo "<tr class=\"$bg\"><th class=\"$bi\">\n";
		echo "$l[2]</th><td><a href=?dev=$ul>$l[3]</a> on $l[4] (Vlan$l[9] $l[8])</td>";
		echo "<td align=\"right\">" . DecFix($l[5]) . "</td><td align=\"right\">$l[6]</td><td>$l[7]</td></tr>\n";
	}
	@DbFreeResult($res);
	?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Links</td></tr>
</table>
	<?
}else{
	echo "<h4>$nonlbl</h4>";
}
if ($rver){
	$query	= GenQuery('vlans','s','*','vlanid',$_SESSION['lim'],array('device'),array('='),array($shd) );
	$res	= @DbQuery($query,$link);
	if($res){
		$nvlan = @DbNumRows($res);
		if($nvlan){
?>
<h2>
<a href="Devices-Vlans.php?ina=device&opa==&sta=<?=$ud?>"><img src="img/16/vlan.png" title="Vlan <?=$lstlbl?>"></a>
Top <?=$_SESSION['lim']?> Vlans</h2>

<table class="content" ><tr class="<?=$modgroup[$self]?>2">
<th valign="bottom" width="80"><img src="img/16/vlan.png" title="SSIDs on some Wlan Controllers"><br>Vlan</th>
<th valign="bottom"><img src="img/16/say.png"><br><?=$namlbl?></th></tr>
<?
				$row  = 0;
				while( $v = @DbFetchRow($res) ){
					if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
					$row++;
					echo "<tr class=\"$bg\"><th class=\"$bi\">\n";
					echo "<a href=\"Devices-Vlans.php?ina=vlanid&opa==&sta=$v[1]\">$v[1]</a></th>\n";
					echo "<td>$v[2]</td></tr>\n";
					$nvlan++;
				}
				@DbFreeResult($res);
?>
</table>
<table class="content">
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Vlans</td></tr>
</table>
<?
		}
	}else{
		print @DbError($link);
	}

	$query	= GenQuery('modules','s','*',($os == "MSM")?'modidx':'slot',($os == "ESX")?'':$_SESSION['lim'],array('device'),array('='),array($shd) );
	$res	= @DbQuery($query,$link);
	if($res){
		$nmod = @DbNumRows($res);
		if($nmod){
?>
<h2>
<a href="Devices-Modules.php?ina=device&opa==&sta=<?=$ud?>&ord=slot"><img src="img/16/cubs.png" title="<?=$sholbl?> <?=$lstlbl?>"></a>
<?
		if($os == "Printer"){
?>
Top <?=$_SESSION['lim']?> Supplies</h2>
<table class="content" ><tr class="<?=$modgroup[$self]?>2">
<th valign="bottom" colspan="2"><img src="img/16/file.png" title="<?=$typlbl?>,<?=$deslbl?>"><br><?=$typlbl?></th>
<th valign="bottom" width="200"><img src="img/16/form.png"><br><?=$levlbl?></th>
</tr>
<?
			$row  = 0;
			while( $m = @DbFetchRow($res) ){
				if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
				$row++;
				echo "<tr class=\"$bg\"><th class=\"$bi\">".PrintSupply($m[1])."</th>\n";
				echo "<td>$m[3]</td><td>".Bar($m[5],-33)." $m[5]%</td></tr>\n";
			}
			@DbFreeResult($res);
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Supplies</td></tr>
</table>
<?
		}elseif($os == "ESX"){#TODO check XEN (xe vm-start name-label=)
?>
Virtual Machines</h2>
<table class="content" ><tr class="<?=$modgroup[$self]?>2">
<th valign="bottom" colspan="2"><img src="img/16/node.png" title="<?=$stalbl?>, <?=$namlbl?>"><br>VM</th>
<th valign="bottom" colspan="2" width="200" title="# CPUs, <?=$memlbl?>"><img src="img/16/cinf.png"><br>HW</th>
</tr>
<?
			if($uptime){
				foreach( Walk($ip, $rver, $dev[15],"1.3.6.1.4.1.6876.2.1.1.6") as $ix => $val){
					$vmpwr[substr(strrchr($ix, "."), 1 )] = $val;
				}
			}
			$row  = 0;
			$tmem = 0;
			$tact = 0;
			while( $m = @DbFetchRow($res) ){
				if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
				$row++;
				echo "<tr class=\"$bg\"><th class=\"$bi\">\n";
				if( preg_match("/^win/i",$m[7]) ){
					$shut    = "power.shutdown";
					$vmtools = "<img src=\"img/16/nwin.png\" align=\"right\" title=\"VMtools: $m[7]\">";
				}elseif( preg_match("/^freebsd/",$m[7]) ){
					$shut    = "power.shutdown";
					$vmtools = "<img src=\"img/16/nbsd.png\" align=\"right\" title=\"VMtools: $m[7]\">";
				}elseif( preg_match("/linux|rhel|redhat|sles|suse|ubuntu/i",$m[7]) ){
					$shut    = "power.shutdown";
					$vmtools = "<img src=\"img/16/nlin.png\" align=\"right\" title=\"VMtools: $m[7]\">";
				}else{
					$shut    = "power.off";
					$vmtools = '';
				}
				if($isadmin and $login and $cliport){
					$vmx = substr($m[3], 0, strrpos($m[3],'/'));
					$vmp = substr($vmx, 0, strrpos($vmx,'/'));
					if($vmpwr[$m[8]] == '"poweredOn"'){
						$tmem += $m[6];
						$tact++;
?>
<form method="post" action="Devices-Write.php">
<input type="hidden" name="ina" value="device">
<input type="hidden" name="opa" value="=">
<input type="hidden" name="sta" value="<?=$dev[0]?>">
<input type="hidden" name="cmd" value="vim-cmd vmsvc/<?=$shut?> <?=$m[8]?>">
<input type="hidden" name="scm" value="1">
<input type="image" src="img/16/exit.png" value="Submit" title="On, click to shutdown <?=$m[1]?> ID:<?=$m[8]?>" onclick="return confirm('<?=$shut?> <?=$m[1]?>?')">
</form>
<?
					}else{
?>
<form method="post" action="Devices-Write.php">
<input type="hidden" name="ina" value="device">
<input type="hidden" name="opa" value="=">
<input type="hidden" name="sta" value="<?=$dev[0]?>">
<input type="hidden" name="cmd" value="vim-cmd vmsvc/power.on <?=$m[8]?>">
<input type="hidden" name="scm" value="1">
<input type="image" src="img/16/bcls.png" value="Submit" title="Off, click to turn on <?=$m[1]?> ID:<?=$m[8]?>">
</form>
<?
					}
/*
<div style="float:right;margin:2px 2px">
<a href="Node-Create.php?dev=<?=urlencode($dev[0])?>&vmp=<?=urlencode($vmp)?>">
<img src="img/16/file.png" title="<?=(($verb1)?"$addlbl VM: $vmp":"VM $addlbl: $vmp")?>">
</a>
</form>
</div>
*/
?>
</th><td>

<?if($vmpwr[$m[8]] != '"poweredOn"'){?>
<div style="float:right;margin:2px 2px">
<form method="post" action="Devices-Write.php">
<input type="hidden" name="ina" value="device">
<input type="hidden" name="opa" value="=">
<input type="hidden" name="sta" value="<?=$dev[0]?>">
<input type="hidden" name="cmd" value="vim-cmd vmsvc/destroy <?=$m[8]?>">
<input type="hidden" name="scm" value="1">
<input type="image" src="img/16/bcnl.png" value="Submit" onclick="return confirm('<?=$dellbl?>, <?=$cfmmsg?>')"  title="<?=$dellbl?> <?=$m[1]?>">
</form>
</div>
<?}?>
<b><?=$m[1]?></b>
</td><th>
<?
				}else{
					echo "<img src=\"img/16/".(($m[5] == "poweredOn")?"exit":"bcls");
					echo ".png\" title=\"$m[5] (ID$m[8])\"></th>";
					echo "<td><b>$m[1]</b></td><th>";
				}
				for ($i = 1; $i <= $m[4]; $i++) {
					echo "<img src=\"img/16/cpu.png\" title=\"CPU $i\">";
				}
				echo "</th><td nowrap>$vmtools $m[6] Mb</td></tr>\n";
			}
			@DbFreeResult($res);
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> VMs <?=$totlbl?>, <?=$tact?>VMs & <?=round($tmem/1000,2)?>Gb Ram <?=$stco['100']?></td></tr>
</table>
<?
		}else{	
?>
Top <?=$_SESSION['lim']?> Modules</h2>
<table class="content" ><tr class="<?=$modgroup[$self]?>2">
<th valign="bottom" colspan="3"><img src="img/16/find.png" title="Index, Slot, <?=$typlbl?> <?=$deslbl?>"><br><?=$deslbl?></th>
<th valign="bottom"><img src="img/16/key.png"><br><?=$serlbl?></th>
<th valign="bottom" colspan="3" title="HW / FW / SW"><img src="img/16/cbox.png"><br>Version</th>
</tr>
<?
			$row  = 0;
			while( $m = @DbFetchRow($res) ){
				if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
				$row++;
				echo "<tr class=\"".(($os == "MSM" and !$m[2])?"alrm":$bg)."\"><th class=\"$bi\">\n";	# Highlight offline APs on MSM controllers
				echo "$m[1]</th><td>$m[2]</td><td>$m[3]</td><td>$m[4]</td><td>$m[5]</td><td>$m[6]</td><td>$m[7]</td></tr>\n";
			}
			@DbFreeResult($res);
?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Modules</td></tr>
</table>
<?
		}
		}
	}else{
		print @DbError($link);
	}
	echo "</td></tr></table>\n";

	flush();

	if( count($ifn) ){
?>
<h2>
<a href="Devices-Interfaces.php?ina=device&opa==&sta=<?=$ud?>&ord=ifname"><img src="img/16/port.png" title="Interface <?=$lstlbl?>"></a>
<a href="Topology-Networks.php?ina=device&opa==&sta=<?=$ud?>&ord=ifname"><img src="img/16/net.png" title="<?=$netlbl?> <?=$lstlbl?>"></a>
Interfaces</h2><p>
<table class="content" ><tr class="<?=$modgroup[$self]?>2">
<th colspan="2" valign="bottom"><img src="img/16/port.png" title="IF <?=$stalbl?> (<?=$rltlbl?>)"><br><?=$namlbl?></th>
<th valign="bottom"><img src="img/16/vlan.png" title="Vlanid"><br>Vlan</th>
<th valign="bottom"><img src="img/16/find.png" title="<?=$deslbl?>"><br>Info</th>
<th valign="bottom"><img src="img/spd.png" title="<?=$spdlbl?>"><br><?=substr($spdlbl,0,5)?></th>
<th valign="bottom"><img src="img/dpx.png"><br>Duplex</th>
<th valign="bottom"><img src="img/16/swit.png" title="<?=$stalbl?> <?=$chglbl?> (<?=$rltlbl?>)"><br><?=$laslbl?></th>
<?
	if($pop){
?>
<th valign="bottom"><img src="img/16/nods.png"><br><?=(substr($poplbl,0,3))?></th>
<?
		$query	= GenQuery('nodes','g','ifname;name,nodip','','',array('device'),array('='),array($shd) );
		$res	= @DbQuery($query,$link);
		if($res){
			while( ($nc = @DbFetchRow($res)) ){
				$anode[$nc[0]] = "$nc[2] ".long2ip($nc[3]);
				$ncount[$nc[0]] = $nc[1];
			}
		}
		$query	= GenQuery('iftrack','g','mac,ifupdate,ifname','','',array('device'),array('='),array($shd) );
		$res	= @DbQuery($query,$link);
		if($res){
			while( ($nl = @DbFetchRow($res)) ){
				$niflog[$nl[2]] = "$nl[0] $laslbl ". date($datfmt,$nl[1]);
			}
		}
	}
	if($trg or $erg or $brg or $dig){
?>
<th valign="bottom"><img src="img/16/grph.png"><br>IF <?=$gralbl?></th>
<?
	}else{
?>
<th valign="bottom"><img src="img/16/bbup.png" title="Octets/<?=$rrdstep?> <?=$tim['s']?>"><br><?=(substr($inblbl,0,3))?></th>
<th valign="bottom"><img src="img/16/bbdn.png" title="Blue: <?=$trflbl?> <?=$totlbl?>"><br><?=(substr($oublbl,0,3))?></th>
<th valign="bottom"><img src="img/16/brup.png" title=" <?=$errlbl?>/<?=$rrdstep?> <?=$tim['s']?>"><br><?=(substr($inblbl,0,3))?></th>
<th valign="bottom"><img src="img/16/brdn.png" title="Blue: <?=$errlbl?> <?=$totlbl?>"><br><?=(substr($oublbl,0,3))?></th>
<?
	}
?>
<th><img src="img/16/batt.png" title="PoE [mW]"><br>PoE</th>
<th valign="bottom" width="10%"><img src="img/netg.png" title="MAC IP VRF"><br><?=$adrlbl?></th>
<?
	if($uptime){
		foreach( Walk($ip, $rver, $dev[15],"1.3.6.1.2.1.2.2.1.8") as $ix => $val){
			$ifost[substr(strrchr($ix, "."), 1 )] = $val;
		}
		foreach( Walk($ip, $rver, $dev[15],"1.3.6.1.2.1.2.2.1.9") as $ix => $val){
			$iflac[substr(strrchr($ix, "."), 1 )] = $val;
		}
	}
	$tpow= 0;							# China in your hand ;-)
	$row = 0;
	foreach ( $ifn as $i => $in){
		if ($row % 2){$bg = "txta"; $bi = "imga";$off=200;}else{$bg = "txtb"; $bi = "imgb";$off=185;}
		$row++;
		$bg3= sprintf("%02x",$off);
		$blc = $bio = $bie = $boo = $boe = "";
		$rs = $gs = $bg3;
		$ui = urlencode($in);

		if($ifost[$i] == "1" or $ifost[$i] == "up"){
			$ifstat = "good";
		}elseif($ifost[$i] == "2" or $ifost[$i] == "down"){
			$ifstat = "warn";
		}else{
			$ifstat = "imga";
		}
		if ($ifa[$i] & 4){
			$cif = 0;
		}else{
			if ($ifa[$i] & 1){
				$cif = "0$i";
				$actmsg = "Disable $ifn[$i], $cfmmsg?";
			}else{
				$cif = "1$i";
				$actmsg = "Enable $ifn[$i], $cfmmsg?";
				if($ifost[$i] == "1" or $ifost[$i] == "up"){
					$ifstat = "crit";
				}elseif($ifost[$i] == "2" or $ifost[$i] == "down"){
					$ifstat = "alrm";
				}else{
					$ifstat = "imgb";
				}
			}
		}

		if ($ino[$i] > 70){									// Ignore the first 70  bytes...
			$bio = "bgcolor=\"#$bg3$bg3".sprintf("%02x","40" + $off)."\"";
			$ier = $die[$i] * $die[$i]/(($dio[$i])?$dio[$i]:1);				// Relative inerr^2 with fix for / by 0
			if ($ier){
				if ($ier > 55){$ier = 55;}
				$bie = "bgcolor=\"#".sprintf("%02x", $ier+$off)."$bg3$bg3\"";
			}
		}
		if ($oto[$i] > 70){									// ...cauz some devs don't default to 0!
			$boo = "bgcolor=#$bg3$bg3".sprintf("%02x","40" + $off);
			$oer = $doe[$i] * $doe[$i]/(($doo[$i])?$doo[$i]:1);
			if ($oer){
				if ($oer > 55){$oer = 55;}
				$boe = "bgcolor=#".sprintf("%02x", $oer+ $off)."$bg3$bg3";
			}
		}
		sscanf($iflac[$i], "%d:%d:%d:%d.%d",$lcd,$lch,$lcm,$lcs,$ticks);
		$il		= $upmin - ($lcm + 60 * $lch + 1440 * $lcd);
		if($il <= 0){
			$iflch	= "-";
		}else{
			$ild	= intval($il / 1440);
			$ilh	= intval(($il - $ild * 1440)/60);
			$ilm	= intval($il - $ild * 1440 - $ilh * 60);
			$iflch	= sprintf("%d D %d:%02d",$ild,$ilh,$ilm);
			$rblcm	= 10000/($il + 1);
			if($rblcm > 1){
				if($rblcm > 55){$rblcm = 55;}
				$blc = "bgcolor=\"#".sprintf("%02x",$rblcm + $off)."$bg3$bg3\"";
			}
		}
		list($ifimg,$iftit) = Iftype($ift[$i]);

		echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\"><th class=\"$ifstat\" width=\"20\">";
		list($ifbg,$ifdb) = Ifdbstat($ifa[$i]);
		if($isadmin and $dev[26] and $wasup and $cif){
			echo "<a href=\"?dev=$ud&cif=$cif\"><img src=\"img/$ifimg\" onclick=\"return confirm('$actmsg')\" title=\"$i - $iftit DB:$ifdb\"></a>\n";
		}else{
			echo "<img src=\"img/$ifimg\" title=\"$i - $iftit DB:$ifdb\">\n";
		}
		echo "</th><td nowrap>";
		if($ifstat == "good" and $guiauth != 'none' and !isset($_GET['print'])){
			echo "<img src=\"img/16/grph.png\" align=\"right\" title=\"$rltlbl $trflbl\" onclick=\"window.open('inc/rt-popup.php?d=$debug&ip=$ip&v=$dev[14]&c=$dev[15]&i=$i&t=$ud&in=$ui','$dev[1]_$i','scrollbars=0,menubar=0,resizable=1,width=600,height=400')\">";
		}
		echo "<b>$in</b></td>\n";
		echo "<td align=\"center\">$ifv[$i]</td><td>$ifi[$i]</td>\n";
		echo "<td align=\"right\" nowrap>$ifs[$i]</td><td align=\"center\">$ifd[$i]</td>\n";
		echo "<td align=\"right\" $blc nowrap>$iflch</td>\n";

		if($pop){
			if($niflog[$in]){
				$bnl = sprintf("%02x","40" + $off);
				echo "<td bgcolor=\"#$bg3$bg3$bnl\" title=\"$niflog[$in]\" nowrap>";
			}else{
				echo "<td nowrap>";
			}

			if($ncount[$in]){
				echo Bar($ncount[$in],8,'mi') . " <a href=Nodes-List.php?ina=device&opa==&sta=$ud&cop=AND&inb=ifname&opb==&stb=$ui title=\"Nodes-$lstlbl ($anode[$in])\">$ncount[$in]</a>\n";
			}
			echo "</td>\n";
		}

		if($trg or $erg or $brg or $dig){
			echo "<td nowrap align=\"center\">\n";
			echo "<a href=Devices-Graph.php?dv=$ud&if%5B%5D=$ui>\n";
			if($trg){echo "<img src=\"inc/drawrrd.php?dv=$ud&if%5B%5D=$ui&s=$gsiz&t=trf\" title=\"$in $trflbl\">\n";}
			if($erg){echo "<img src=\"inc/drawrrd.php?dv=$ud&if%5B%5D=$ui&s=$gsiz&t=err\" title=\"$in $errlbl\">\n";}
			if($dig){echo "<img src=\"inc/drawrrd.php?dv=$ud&if%5B%5D=$ui&s=$gsiz&t=dsc\" title=\"$in Discards\">\n";}
			if($brg){echo "<img src=\"inc/drawrrd.php?dv=$ud&if%5B%5D=$ui&s=$gsiz&t=brc\" title=\"$in Broadcasts\">\n";}
			echo "</a>\n";
		}else{
			echo "<td $bio align=\"right\">".DecFix($dio[$i])."B</td>\n";
			echo "<td $boo align=\"right\">".DecFix($doo[$i])."B</td>\n";
			echo "<td $bie align=\"right\">".DecFix($die[$i])."</td>\n";
			echo "<td $boe align=\"right\">".DecFix($doe[$i])."</td>\n";
		}

		if($ifp[$i]){
			$tpow += $ifp[$i]/1000;
			$bp1 = sprintf("%02x",$ifp[$i]/280 + $off);
			echo "<td align=\"right\" bgcolor=\"#$bp1$bp1$bg3\">$ifp[$i]</td>\n";
		}else{
			echo "<td></td>\n";
		}

		echo "<td class=\"code\">";
		if($ifm[$i]){echo "<span class=\"drd\">$ifm[$i]</span><br>";}
		foreach ($net[$in] as $ifip => $dmsk){
			list($pfix,$msk,$bmsk)	= Masker($dmsk);
			$dnet = long2ip($ifip);
			echo "<a href=\"Reports-Interfaces.php?ina=devip&opa=%3D&sta=$dnet%2F$pfix&rep[]=net\">$dnet/$pfix</a>\n";
			if($vrf[$in][$ifip]){echo "<a href=\"Topology-Networks.php?ina=vrfname&opa==&sta=".urlencode($vrf[$in][$ifip])."\">".$vrf[$in][$ifip]."</a>\n";}
		}
		echo "</td></tr>\n";
	}
	?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Interfaces<?=($tpow)?", ${tpow}W $totlbl PoE":""?></td></tr>
</table>
	<?
}
}else{
	echo "</td></tr></table>\n";
}

if($ip){
?>
<h2>
<a href="Monitoring-Events.php?ina=source&opa==&sta=<?=$ud?>"><img src="img/16/bell.png" title="<?=$msglbl?>"></a>
<?=$mlvl[150]?> <?=$laslbl?></h2>
<?
	Events($_SESSION['lim'],array('level','source'),array('>=','='),array(150,$dev[0]),array('AND') );
}
}
include_once ("inc/footer.php");
?>
