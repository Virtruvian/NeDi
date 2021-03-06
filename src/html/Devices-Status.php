<?php
# Program: Devices-Status.php
# Programmer: Remo Rickli

snmp_set_quick_print(1);
snmp_set_oid_numeric_print(1);
snmp_set_valueretrieval(SNMP_VALUE_LIBRARY);

$printable = 1;
$exportxls = 0;

include_once ("inc/header.php");
include_once ("inc/libdev.php");
include_once ("inc/libsnmp.php");

$_GET = sanitize($_GET);
$dld = isset($_GET['del']) ? $_GET['del'] : '';
$dtx = isset($_GET['dtx']) ? $_GET['dtx'] : '';
$shd = isset($_GET['dev']) ? $_GET['dev'] : '';
$add = isset($_GET['add']) ? $_GET['add'] : '';
$adi = isset($_GET['adi']) ? $_GET['adi'] : '';

$loc = isset($_GET['loc']) ? $_GET['loc'] : '';
$con = isset($_GET['con']) ? $_GET['con'] : $_SESSION['user'];
$typ = isset($_GET['typ']) ? $_GET['typ'] : 'gen-cloud';
$srv = isset($_GET['srv']) ? $_GET['srv'] : 1;
$des = isset($_GET['des']) ? $_GET['des'] : 'User added';
$grp = isset($_GET['grp']) ? $_GET['grp'] : '';
$siz = isset($_GET['siz']) ? $_GET['siz'] : 1;
$ina = isset($_GET['ina']) ? $_GET['ina'] : '-';
$mac = isset($_GET['mac']) ? $_GET['mac'] : '';
$ali = isset($_GET['ali']) ? $_GET['ali'] : '';
$spd = isset($_GET['spd']) ? $_GET['spd'] : 100000000;
$ico = isset($_GET['ico']) ? $_GET['ico'] : 'clan';
$ip  = isset($_GET['ip'])  ? $_GET['ip'] : '0.0.0.0';
$sn  = isset($_GET['sn'])  ? $_GET['sn']  : '';

$clo = isset($_GET['clo']) ? $_GET['clo'] : '';
$cco = isset($_GET['cco']) ? $_GET['cco'] : '';
$cif = isset($_GET['cif']) ? $_GET['cif'] : '';
$pif = isset($_GET['pif']) ? $_GET['pif'] : '';
$ifx = isset($_GET['ifx']) ? $_GET['ifx'] : '';
$ali = isset($_GET['ali']) ? $_GET['ali'] : '';

$mon = isset($_GET['mon']) ? 1 : 0;
$shg = isset($_GET['shg']) ? "checked" : '';
$pop = isset($_GET['pop']) ? "checked" : '';
$pvi = isset($_GET['pvi']) ? $_GET['pvi'] : '';

$rtl = isset($_POST['rtl']) ? 1:0;									# Use POST to avoid accidental reset!

$link	= DbConnect($dbhost,$dbuser,$dbpass,$dbname);
?>
<h1>Device <?= $stalbl ?></h1>

<?php  if( !isset($_GET['print']) ) { ?>
<form method="get">
<table class="content"><tr class="bgmain">
<td class="ctr s">
	<a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png" title="<?= $self ?>"></a>
</td>
<td>
	<a href="?dev=new"><img src="img/16/add.png" title="<?= $addlbl ?>"></a>
	<select size="1" name="dev" onchange="this.form.submit();">
		<option value="">Device ->
<?php
$query	= GenQuery('devices','s','device','device');
$res	= DbQuery($query,$link);
if($res){
	while( $d = DbFetchRow($res) ){
		echo "		<option value=\"$d[0]\"".( ($shd == $d[0])?" selected":"").">$d[0]\n";
	}
	DbFreeResult($res);
}else{
	print DbError($link);
	die;
}
?>
	</select>

</td>
<td class="ctr">
<?php if($rrdcmd and $_SESSION['gsiz']){ ?>
	<img src="img/16/grph.png" title="<?= $porlbl ?> <?= $gralbl ?>">
	<input type="checkbox" name="shg" <?= $shg ?>>
<?php } ?>
	<img src="img/16/nods.png" title="<?= $sholbl ?> <?= $poplbl ?>">
	<input type="checkbox" name="pop" <?= $pop ?>>
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
if( $add ){
	if( $isadmin ){
		$dvst = AddRecord('devices',"device='$add'",'device,devip,serial,type,firstdis,lastdis,services,description,location,contact,devgroup,devmode,icon,sysobjid,devopts,size',"'$add','".ip2long($ip)."','$sn','$typ','".time()."','".time()."',0,'$des','$loc','$con','$grp',9,'$ico','NoSNMP-User','NNNI',$siz");
		$ifst = AddRecord('interfaces',"device='$add' AND ifidx='$adi'",'device,ifname,ifidx,ifmac,ifdesc,alias,iftype,ifstat,speed',"'$add','$ina','$adi','$mac','User added','$ali',53,3,$spd");
		echo "<h3><a href=\"?dev=".urlencode($add)."\">$add</a> $addlbl Device $dvst Interface $ifst</h3>\n";
	}else{
		echo $nokmsg;
	}
}elseif( $dld ){
	if( $isadmin ){
		DevDelete($dld,$dtx);
?>
<script language="JavaScript"><!--
setTimeout("history.go(-2)",1000);
//--></script>
<?php
	}else{
		echo $nokmsg;
	}
}elseif ($shd){

$ud	= urlencode($shd);										# Need raw for RRD filenames
$query	= GenQuery('devices','s','*','','',array('device'),array('='),array($shd) );
$res	= DbQuery($query,$link);
$ndev	= DbNumRows($res);
if( $ndev != 1 or $adi ){
	if( $ndev != 1 ){
		echo "<h4>$shd: $nonlbl <a href=\"?del=$ud\"><img src=\"img/16/bcnl.png\" title=\"".(($verb1)?"$dellbl $mlvl[10] $inflbl":"$mlvl[10] $inflbl $dellbl")."\"></a></h4>";
		echo "<h2>$stco[10] Device</h2><p>\n";
	}else{
		echo "<h2>$stco[10] $intlbl</h2><p>\n";
	}
	DbFreeResult($res);
?>
<form method="get" name="bld" action="<?= $self ?>.php">
<table class="content">
<tr class="bgmain">
<td class="top">
<?php
	if( $ndev != 1 ){
?>
	<h3><?= $manlbl ?></h3>
	<img src="img/16/dev.png" title="<?= $namlbl ?>">
	<input type="text" name="add" value="<?= $shd ?>" placeholder="<?= $namlbl ?>" class="m"><br>
	<img src="img/16/key.png" title="<?= $serlbl ?>">
	<input type="text" name="sn" value="<?= $sn ?>" placeholder="<?= $serlbl ?>" class="m"><br>
	<img src="img/16/icon.png" onClick="window.open('inc/browse-img.php','Icons','scrollbars=1,menubar=0,resizable=1,width=600,height=800');" title="<?= $sellbl ?> <?= $imglbl ?>">
	<input type="text" name="ico" value="<?= $ico ?>" class="m" placeholder="Icon"><br>
	<img src="img/16/abc.png" title="<?= $typlbl ?>" onClick="window.open('inc/browse-img.php?t=p','Panels','scrollbars=1,menubar=0,resizable=1,width=600,height=800');" title="<?= $sellbl ?> <?= $imglbl ?>">
	<input type="text" name="typ" value="<?= $typ ?>" placeholder="<?= $typlbl ?>" class="m"><br>

</td>
<td class="top">

	<h3><?= $inflbl ?></h3>
	<img src="img/16/form.png" title="<?= $sizlbl ?>">
	<input type="text" name="siz" value="<?= $siz ?>" placeholder="<?= $sizlbl ?>" class="s"><br>
	<img src="img/16/home.png" title="<?= $loclbl ?>">
	<input type="text" name="loc" value="<?= $loc ?>" placeholder="<?= $loclbl ?>" class="l"><br>
	<img src="img/16/user.png" title="<?= $conlbl ?>">
	<input type="text" name="con" value="<?= $con ?>" placeholder="<?= $conlbl ?>" class="l"><br>
	<img src="img/16/ugrp.png" title="<?= $grplbl ?>">
	<input type="text" name="grp" value="<?= $grp ?>" placeholder="<?= $grplbl ?>" class="l"><br>
	<img src="img/16/find.png" title="<?= $deslbl ?>">
	<input type="text" name="des" value="<?= $des ?>" placeholder="<?= $deslbl ?>" class="l"><br>
<?php
	}else{
		echo "	<h3>$idxlbl $adi</h3>\n";
		echo "	<input type=\"hidden\" name=\"add\" value=\"$shd\">\n";
		echo "	<input type=\"hidden\" name=\"adi\" value=\"$adi\">\n";
	}
?>
</td>
<td class="top">

	<h3><?= $intlbl ?></h3>
	<img src="img/16/port.png" title="IF <?= $namlbl ?>">
	<input type="text" name="ina" value="<?= $ina ?>" placeholder="IF <?= $namlbl ?>" class="m"><br>
	<img src="img/16/glob.png" title="IP <?= $adrlbl ?>">
	<input type="text" name="ip" value="<?= $ip ?>" placeholder="IP <?= $adrlbl ?>" class="m"><br>
	<img src="img/16/card.png" title="MAC <?= $adrlbl ?>">
	<input type="text" name="mac" value="<?= $mac ?>" placeholder="MAC <?= $adrlbl ?>" class="m"><br>
	<img src="img/16/find.png" title="Alias">
	<input type="text" name="ali" value="<?= $ali ?>" placeholder="Alias" class="l"><br>
	<img src="img/spd.png" title="<?= $spdlbl ?>">
	<input type="text" name="spd" value="<?= $spd ?>" placeholder="<?= $spdlbl ?>" class="m">
	<select size="1" name="sps" onchange="document.bld.spd.value=document.bld.sps.options[document.bld.sps.selectedIndex].value">
		<option value=""><?= $bwdlbl ?> ->
		<option value="1544000">T1
		<option value="2048000">E1
		<option value="10000000">10M
		<option value="100000000">100M
		<option value="1000000000">1G
		<option value="10000000000">10G
	</select>
</td>
<td class="ctr s">
	<input type="submit" class="button" value="<?= $addlbl ?>">
</td>
</tr>
</table>
</form>
<p>

<?php
	include_once ("inc/footer.php");
	exit(0);
}
$dev = DbFetchRow($res);
DbFreeResult($res);

$ip		= ($dev[1]) ? long2ip($dev[1]) : 0;
list($fc,$lc)	= Agecol($dev[4],$dev[5],0);
$fs		= date($_SESSION['timf'],$dev[4]);
$ls		= date($_SESSION['timf'],$dev[5]);
$wasup		= ($dev[5] > time() - $rrdstep*2)?1:0;
$os		= $dev[8];
$ug		= urlencode($dev[12]);
$rver		= $dev[14] & 3;
$wver		= ($dev[14] & 12) >> 2;
$rcomm		= (($guiauth != 'none')?$dev[15]:"***");
$cliport	= $dev[16];
$login		= $dev[17];
$ico		= $dev[18];
$oi		= ($dev[19]) ? long2ip($dev[19]) : 0;
$sysobj		= $dev[25];
$wcomm		= (($isadmin and $guiauth != 'none')?$dev[26]:"***");
$stk		= ($dev[29] > 1)?"<img src=\"img/$dev[29].png\" title=\"Stack\">":"";

if($dev[13] < 10){
	$query	= GenQuery('interfaces','s','*','ifidx','',array('device'),array('='),array($shd) );
	$res	= DbQuery($query,$link);
	while( $i = DbFetchRow($res) ){
		$ifn[$i[2]] = $i[1];
		if( strpos($dev[27],'N') and preg_match('/[A-Z][a-z](\d+)\/(\d+)$/',$i[1]) ){
			$nx = preg_replace('/[A-Z][a-z](\d+)\/(\d+)$/',"$1.$2",$i[1]);
			$ifnx[$i[2]] = $nx;
		}
		$ift[$i[2]] = $i[4];
		$ifa[$i[2]] = $i[8];
		$ifs[$i[2]] = $i[9];
		$ifd[$i[2]] = $i[10];
		$ifl[$i[2]] = $i[7];
		$ifi[$i[2]] = "$i[6] <span class=\"sbu\">$i[28]</span>";
		$ifv[$i[2]] = $i[11];
		$ifm[$i[2]] = $i[5];
		$ino[$i[2]] = $i[12];
		$oto[$i[2]] = $i[14];
		$dio[$i[2]] = $i[16];
		$die[$i[2]] = $i[17];
		$doo[$i[2]] = $i[18];
		$doe[$i[2]] = $i[19];
		$did[$i[2]] = $i[22];
		$dod[$i[2]] = $i[23];
		$dib[$i[2]] = $i[25];
		$ifp[$i[2]] = $i[27];
	}

	$net6   = array();
	$net    = array();
	$query	= GenQuery('networks','s','*','ifip','',array('device'),array('='),array($shd) );
	$res	= DbQuery($query,$link);
	while( $n = DbFetchRow($res) ){
		if($n[2]){
			$ifip = long2ip($n[2]);
			$net[$n[1]][$ifip]['ifip'] = $n[4];
			if($n[5]) $net[$n[1]][$ifip]['vrf'] = $n[5];
		}else{
			$ifip = DbIPv6($n[3]);
			$net[$n[1]][$ifip]['ifip6'] = $n[4];
			if($n[5]) $net6[$n[1]][$ifip]['vrf'] = $n[5];
		}
	}
	DbFreeResult($res);
}

$query	= GenQuery('monitoring','s','*','','',array('name'),array('='),array($dev[0]) );
$res	= DbQuery($query,$link);
$statbg = 'imga';
$stat   = '';
if (DbNumRows($res) == 1){
	include_once ("inc/libmon.php");
	$mon = DbFetchRow($res);
	list($statbg,$stat) = StatusBg(1,($mon[3] != 'none')?1:0,$mon[7]);
	if(!$wasup){
		$statbg .= " part";
		$stat    = "$stat, $laslbl $dsclbl < $rrdstep $tim[s]?";
	}	
}
DbFreeResult($res);

if($isadmin and $guiauth != 'none'){
	if ($rtl){
		$cliport = 0;
		$query	= GenQuery('devices','u',"device = '".DbEscapeString($shd)."'",'','',array('cliport'),array(),array('0') );
		if( !DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>CLI $reslbl OK</h5>";}
	}elseif ($clo){
		if($sysobj == 'NoSNMP-User'){
			$ok = 1;
		}else{
			if( Set($ip, $wver, $dev[26], "1.3.6.1.2.1.1.6.0", 's', $clo ) ){
				$ok = 1;
				echo "<h5>SNMP $loclbl $chglbl OK</h5>";
			}else{
				echo "<h4>SNMP $loclbl $chglbl $errlbl</h4>";
			}
		}
		if( $ok ){	
			$dev[10] = $clo;
			$query	= GenQuery('devices','u',"device = '".DbEscapeString($shd)."'",'','',array('location'),array(),array($clo) );
			if( !DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>DB $loclbl $chglbl OK</h5>";}
			$query = GenQuery('events','i','','','',array('level','time','source','info','class','device'),array(),array('100',time(),$shd,"User $_SESSION[user] changed location to $clo",'usrd',$shd) );
			DbQuery($query,$link);
		}
	}elseif ($cco){
		if($sysobj == 'NoSNMP-User'){
			$ok = 1;
		}else{
			if( Set($ip, $wver, $dev[26], "1.3.6.1.2.1.1.4.0", 's', $cco ) ){
				$ok = 1;
				echo "<h5>SNMP $conlbl $chglbl OK</h5>";
			}else{
				echo "<h4>SNMP $conlbl $chglbl $errlbl</h4>";
			}
		}
		if( $ok ){	
			$dev[11] = $cco;
			$query	= GenQuery('devices','u',"device = '".DbEscapeString($shd)."'",'','',array('contact'),array(),array($cco) );
			if( !DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>DB $conlbl $chglbl OK</h5>";}
			$query = GenQuery('events','i','','','',array('level','time','source','info','class','device'),array(),array('100',time(),$shd,"User $_SESSION[user] changed contact to $cco",'usrd',$shd) );
			DbQuery($query,$link);
		}
	}elseif ($cif){
		$s = substr($cif,0,1);
		$act = ($s == 1)?"enabled":"disabled";
		$i = substr($cif,1);
		if( Set($ip, $wver, $dev[26], "1.3.6.1.2.1.2.2.1.7.$i", 'i', ($s)?1:2 ) ){
			echo "<h5>SNMP IF $chglbl OK</h5>";
			$query	= GenQuery('interfaces','u',"device = '".DbEscapeString($shd)."' AND ifname = '$ifn[$i]'",'','',array('ifstat'),array(),array($s) );
			if( !DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>DB $ifn[$i] $act OK</h5>";$ifa[$i] = $s;}
			$query = GenQuery('events','i','','','',array('level','time','source','info','class','device'),array(),array('100',time(),$shd,"User $_SESSION[user] $act interface $ifn[$i]",'usrd',$shd) );
			if( !DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$msglbl $updlbl OK</h5>";}
		}else{
			echo "<h4>IF $chglbl $errlbl</h4>";
		}
	}elseif ($pif){
		$s = substr($pif,0,1);
		$act = ($s == 1)?"enabled PoE on":"disabled PoE on";
		list($o,$i) = explode('.', substr($pif,1));
		if( Set($ip, $wver, $dev[26], "1.3.6.1.2.1.105.1.1.1.3.$o.$i", 'i', ($s)?1:2 ) ){
			echo "<h5>SNMP IF $chglbl PoE OK</h5>";
			$query = GenQuery('events','i','','','',array('level','time','source','info','class','device'),array(),array('100',time(),$shd,"User $_SESSION[user] $act interface $ifn[$i]",'usrd',$shd) );
			if( !DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$msglbl $updlbl OK</h5>";}
		}else{
			echo "<h4>IF $chglbl $errlbl</h4>";
		}
	}elseif ($ali and $ifn[$ifx]){
		if($ali == "-"){$ali = "";}
		if( Set($ip, $wver, $dev[26], "1.3.6.1.2.1.31.1.1.1.18.$ifx", 's', $ali) ){
			echo "<h5>SNMP IF $ifx Alias = $ali OK</h5>";
			$query	= GenQuery('interfaces','u',"device = '".DbEscapeString($shd)."' AND ifname = '$ifn[$i]'",'','',array('alias'),array(),array($ali) );
			if( !DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>DB IF $ifn[$ifx] $updlbl OK</h5>";$ifl[$ifx] = $ali;}
			$query = GenQuery('events','i','','','',array('level','time','source','info','class','device'),array(),array('100',time(),$shd,"User $_SESSION[user] set interface $ifn[$ifx] alias to $ali",'usrd',$shd) );
			if( !DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$msglbl $updlbl OK</h5>";}
		}else{
			echo "<h4>IF $chglbl $errlbl</h4>";
		}
	}
}
?>
<table class="full fixed"><tr><td class="helper">

<h2><?= $sumlbl ?></h2><p>
<table class="content"><tr>
<td class="<?= $statbg ?> ctr s b"><a href="?dev=<?= $ud ?>"><img src="img/dev/<?= $ico ?>.png" title="<?= $stat ?>" vspace="4"></a><?= Digit($dev[29]) ?><br><?= $dev[0] ?></td>
<td class="bgsub">

<div style="float:left">
<?php  if ($rver) { ?>
<?php
	if ($cactiuser and $cactihost and $cactidb){
		$clink  = DbConnect($cactihost,$cactiuser,$cactipass,$cactidb);
		$cquery = GenQuery('host','s','id','','',array('description','hostname'),array('=','='),array($shd,$shd),array('OR') );
		$cres   = DbQuery($cquery,$clink);
		if ( DbNumRows($cres) == 1) {
			$caho = DbFetchRow($cres);
		}
		DbFreeResult($cres);
	}

	if ( isset($caho[0]) ){
?><a target="window" href="<?= $cactiurl ?>/graph_view.php?action=preview&host_id=<?= $caho[0] ?>"><img src="img/cacti.png"></a><?php
	}
	if (file_exists("log/devtools.php")) {						# Based on Steffen's idea
		include_once ("log/devtools.php");
	}

}
?>
</div>
<div style="float:right">
<?php  if($rver and $guiauth != 'none'){ ?>
<a href="Other-Defgen.php?so=<?= $sysobj ?>&ip=<?= $ip ?>&co=<?= urlencode($dev[15]) ?>"><img src="img/16/geom.png" title="<?= (($verb1)?"$edilbl Def $fillbl":"Def $fillbl $edilbl") ?>"></a>
<?php
}
if($isadmin){
	if( is_array($mon) ){
		$most = TestImg($mon[3]);
	}else{
		if ($mon == 1 and $dev[1]){
			if($dev[14] & 3){
				$mma  = explode('/', $mema);
				if($dev[21] > 100){
					$most = AddRecord('monitoring',"name='$dev[0]'",'name,monip,test,device,memalert',"'$dev[0]','$dev[1]','uptime','$dev[0]','$mma[0]'");
				}else{
					$most = AddRecord('monitoring',"name='$dev[0]'","name,monip,test,device,memalert","'$dev[0]','$dev[1]','uptime','$dev[0]','$mma[1]'");
				}
			}else{
				echo AddRecord('monitoring',"name='$dev[0]'",'name,monip,test,device',"'$dev[0]','$dev[1]','ping','$dev[0]'");
			}
		}else{
			echo "<a href=\"?dev=$ud&mon=1\"><img src=\"img/16/bino.png\" title=\"Monitor $addlbl\"></a>";
		}
	}
	echo "<a href=\"Monitoring-Setup.php?in[]=name&op[]=%3D&st[]=$ud\">$most</a>";
?>
<a href="?del=<?= $ud ?>&dtx=+with+IP+<?= $ip ?>+SN+<?= urlencode($dev[2]) ?>"><img src="img/16/bcnl.png" title="<?= $dellbl ?>!" onclick="return confirm('<?= $dellbl ?> <?= $dev[0] ?>, <?= $cfmmsg ?>')"></a>
<?php } ?>

</div>
</th>

</tr>
<tr class="txta">

<td class="imga s b"><?= $manlbl ?> IP</th><td>
<div style="float:right">
<a href="telnet://<?= $ip ?>"><img src="img/16/loko.png" title="Telnet"></a>
<a href="ssh://<?= $ip ?>"><img src="img/16/lokc.png" title="SSH"></a>
<a href="http://<?= $ip ?>" target="window"><img src="img/16/glob.png" title="HTTP"></a>
<a href="https://<?= $ip ?>" target="window"><img src="img/16/glok.png" title="HTTPS"></a>
<a href="Nodes-Toolbox.php?Dest=<?= $ip ?>"><img src="img/16/dril.png" title="Toolbox"></a>
</div>
<?= (Devcli($ip,$cliport)) ?>
</td>

</tr>
<?php  if($ip != $oi and $oi){ ?>
<tr class="txtb">
	
<td class="imgb s b"><?= $orilbl ?> IP</th><td>
<div style="float:right">
<a href="telnet://<?= $oi ?>"><img src="img/16/loko.png" title="Telnet"></a>
<a href="ssh://<?= $oi ?>"><img src="img/16/lokc.png" title="SSH"></a>
<a href="http://<?= $oi ?>" target="window"><img src="img/16/glob.png" title="HTTP"></a>
<a href="https://<?= $oi ?>" target="window"><img src="img/16/glok.png" title="HTTPS"></a>
<a href="Nodes-Toolbox.php?Dest=<?= $oi ?>"><img src="img/16/dril.png" title="Toolbox"></a>
</div>
<?= (Devcli($oi,$cliport)) ?>
</td>

</tr>
<?php } ?>
<tr class="txta">

<td class="imga s b"><?= $srvlbl ?></th><td>
<div style="float:right">
<?php  if($dev[6] > 3) { ?>
<a href="Topology-Routes.php?rtr=<?= $ud ?>"><img src="img/16/rout.png" title="Topology-Routes"></a>
<a href="Topology-Multicast.php?dev=<?= $ud ?>"><img src="img/16/cam.png" title="Topology-Multicast <?= $lstlbl ?>"></a>
<?php } 
if($dev[6] & 2) { ?>
<a href="Topology-Spanningtree.php?dev=<?= $ud ?>"><img src="img/16/traf.png" title="Topology-Spanningtree"></a>
<?php } ?>
</div>
<?=Syssrv($dev[6]) ?>
</td></tr>

<tr class="txtb"><td class="imgb s b"><?= $dsclbl ?></td><td class="ctr">
<span class="genpad" style="background-color: #<?= $fc ?>" title="<?= $fislbl ?>"> <?= $fs ?> </span>
<?= Bar( intval(($dev[5]-$dev[4])/86400),0,'mi',$tim[d]) ?>
<span class="genpad" style="background-color:#<?= $lc ?>" title="<?= $laslbl ?>"> <?= $ls ?> </span>
</th></tr class="txta">

<tr class="txta"><td class="imga s b">Bootimage</th>	<td><a href="Reports-Devices.php?in[]=bootimage&op[]=%3D&st[]=<?= urlencode($dev[9]) ?>&rep[]=sft"><?= $dev[9] ?></a> (<a href="Reports-Devices.php?in[]=devos&op[]=%3D&st[]=<?= urlencode($os) ?>&rep[]=sft"><?= $os ?></a>)</td></tr>
<tr class="txtb"><td class="imgb s b"><?= $serlbl ?></td>
<td>
<?php
	InvCheck( $dev[2],$dev[3],$dev[10] );

	$dbloc = explode($locsep, $dev[10]);
	if($dbloc[2]){
		$dvloc = 'reg='.urlencode($dbloc[0]).'&cty='.urlencode($dbloc[1]).'&bld='.urlencode($dbloc[2]);
		$lolbl = "$place[b]";
	}elseif($dbloc[1]){
		$dvloc = 'reg='.urlencode($dbloc[0]).'&cty='.urlencode($dbloc[1]);
		$lolbl = "$place[c]";
	}else{
		$dvloc = 'reg='.urlencode($dev[10]);
		$lolbl = "$place[r]";
	}

	list($vn,$ic) = DevVendor($dev[25],substr($ico,2,1));
?>
</td></tr>
<tr class="txta"><td class="imga s b"><?= $deslbl ?></th><td>
<a href="http://www.google.com/search?q=<?= urlencode($dev[3]) ?>&btnI=1" target="window"><img src="img/oui/<?= $ic ?>.png" title="<?= $vn ?>"></a>
<a href="Devices-List.php?in[]=type&op[]==&st[]=<?= urlencode($dev[3]) ?>"><?= $dev[3] ?></a> <?= $dev[7] ?></td></tr>
<tr class="txtb"><td class="imgb s b"><?= $loclbl ?></td>
<td>
<div style="float:right">
<a href="Topology-Table.php?<?= $dvloc ?>"><img src="img/16/icon.png" title="<?= (($verb1)?"$sholbl $lolbl":"$lolbl $sholbl") ?>"></a>
<a href="Monitoring-Health.php?<?= $dvloc ?>"><img src="img/16/hlth.png" title="<?= (($verb1)?"$sholbl $lolbl":"$lolbl $sholbl") ?>"></a>
</div>
<?php
	if($isadmin and $guiauth != 'none' and ($wasup and $dev[26] or $sysobj == 'NoSNMP-User') and !isset($_GET['print']) ){# Admin, write access or user-created
?>
<form method="get" name="locfrm">
<input type="hidden" name="dev" value="<?= $dev[0] ?>">
<input type="text" name="clo" class="xl" value="<?= $dev[10] ?>" onkeypress="if(event.keyCode==13)this.form.submit()">
</form>
<?php
	}else{
		echo "$dev[10]\n";
	}
?>
</td></tr>
<tr class="txta"><td class="imga s b"><?= $conlbl ?></th><td>
<?php
	if($isadmin and $guiauth != 'none' and ($wasup and $dev[26] or $sysobj == 'NoSNMP-User')  and !isset($_GET['print']) ){# Admin, write access or user-created
?>
<div style="float:right">
<a href="Devices-List.php?in[]=contact&op[]==&st[]=<?= urlencode($dev[11]) ?>">
<img src="img/16/dev.png" title="<?= $dev[11] ?> Device <?= $lstlbl ?>">
</a></div>
<form method="get">
<input type="hidden" name="dev" value="<?= $dev[0] ?>">
<input type="text" name="cco" class="xl" value="<?= $dev[11] ?>" onkeypress="if(event.keyCode==13)this.form.submit()">
</form>
</td></tr>
<?php
	}else{
		echo "$dev[11]</td></tr>\n";
	}
?>
<tr class="txtb"><td class="imgb s b"><?= $grplbl ?></td><td>
<a href="Devices-List.php?in[]=devgroup&op[]==&st[]=<?= $ug ?>"><?= $dev[12] ?></a> <?= $modlbl ?>: <a href="Devices-List.php?in[]=devmode&op[]==&st[]=<?= $dev[13] ?>"><?= DevMode($dev[13]) ?></a>
</td></tr>

<?php if($rver) { ?>
<tr class="txta"><td class="imga s b">SNMP</th>	<td>

<?php if($isadmin) { ?>
<div class="frgt" style="float:right;margin:2px 2px">
<form method="post" name="nedi" action="System-NeDi.php">
<input type="hidden" name="mde" value="d">
<input type="hidden" name="sed" value="a">
<input type="hidden" name="vrb" value="v">
<?= strpos($dev[27],'a')?'<input type="hidden" name="uip" value="a">':'' ?>
<input type="hidden" name="opt" value="<?=$ip?>">
<input type="image" src="img/16/radr.png" value="Submit" title="<?= (($verb1)?"$dsclbl $tim[n]":"$tim[n] $dsclbl") ?>">
</form>
</div>
<?php } ?>

<?= ($rver and $dev[15])?"<img src=\"img/bulbg.png\">":"<img src=\"img/bulba.png\">" ?>
<?= $realbl ?> <?= $rcomm ?> v<?= ($rver  . (($dev[14] & 128)?(($dev[14] & 64)?"-MC":"-HC"):"")) ?>&nbsp;
<?= ($wver and $dev[26])?"<img src=\"img/bulbg.png\"> $wrtlbl $wcomm v$wver":"<img src=\"img/bulba.png\"> $wrtlbl" ?>
</td></tr>
<?php } ?>

<tr class="txtb"><td class="imgb s b">
<?php
if($dev[13] == 8){
	echo "Controller</td><td><a href=\"Devices-Status.php?dev=".urlencode($login)."\"><img src=\"img/16/sys.png\" title=\"Devices-Status\"></a> <a href=\"Devices-List.php?in[]=login&op[]==&st[]=".urlencode($login)."\">$login</a>";
}else{
	echo "CLI</td><td>";

	if($isadmin and $rver){
		if($cliport){
			if($login){
				if( preg_match("/^(IOS|EOS|Ironware|ProCurve|Nortel)/",$os) ){
					$shlog = 'show log';
				}elseif($os == 'CatOS'){
					$shlog = 'show logging buf';
				}elseif($os == 'ESX'){
					$shlog = 'tail -100 /var/log/syslog.log';
				}elseif($os == 'Comware'){
					$shlog = 'dis log';
                                }elseif($os == 'SAOS'){
                                        $shlog = 'log flash view';
                                }elseif($os == 'LEOS'){
                                        $shlog = 'log flash view tail 50';
                                }elseif($os == 'ROS'){
                                        $shlog = 'log print';
				}else{
					$shlog = '';
				}
				if($shlog){
?>
<div style="float:right;margin:2px 2px">
	<form method="post" action="Devices-Write.php">
		<input type="hidden" name="in[]" value="device">
		<input type="hidden" name="op[]" value="=">
		<input type="hidden" name="st[]" value="<?= $dev[0] ?>">
		<input type="hidden" name="cmd" value="<?= $shlog ?>">
		<input type="hidden" name="scm" value="1">
		<input type="image" src="img/16/note.png" value="Submit" title="<?= $sholbl ?> Log">
	</form>
</div>
<?php				}
			}
?>
<div style="float:right;margin:2px 2px">
	<form method="post" action="Devices-Status.php?dev=<?= $ud ?>">
		<input type="hidden" name="rtl" value="1">
		<input type="image" src="img/16/key.png" value="Submit" title="<?= $reslbl ?> CLI <?= $acslbl ?>">
	</form>
</div>
<?php
		}
	}
	echo ($cliport and $login)?"<img src=\"img/bulbg.png\">":"<img src=\"img/bulba.png\">";
	echo " <a href=\"Devices-List.php?in[]=login&op[]==&st[]=".urlencode($login)."\">$login</a>";
	echo " $porlbl <a href=\"Devices-List.php?in[]=cliport&op[]==&st[]=$cliport\">$cliport</a>";
	}
	echo "</td></tr>\n";
	echo "<tr class=\"txta\"><td class=\"imga s b\">$cfglbl</th><td>";
	echo DevCfg($dev[33],$dev[32]);

if($isadmin){
?>
<div style="float:right;margin:2px 2px">
	<form method="post" name="nedi" action="System-NeDi.php">
		<input type="hidden" name="mde" value="d">
		<input type="hidden" name="sed" value="a">
		<input type="hidden" name="bup" value="B0">
		<input type="hidden" name="skp" value="AFGgsjmvpadobewit">
<?= strpos($dev[27],'a')?'<input type="hidden" name="uip" value="a">':'' ?>
		<input type="hidden" name="vrb" value="on">
		<input type="hidden" name="opt" value="<?=$ip?>">
		<input type="image" src="img/16/radr.png" value="Submit" title="<?= "$cfglbl $buplbl" ?>">
	</form>
</div>
<?php
}
if($isadmin and $cliport and $login){
?>
<div style="float:right;margin:2px 2px">
	<form method="post" action="Devices-Write.php">
		<input type="hidden" name="in[]" value="device">
		<input type="hidden" name="op[]" value="=">
		<input type="hidden" name="st[]" value="<?= $dev[0] ?>">
		<input type="hidden" name="con" value="1">
		<input type="image" src="img/16/wrte.png" value="Submit" title="<?= (($verb1)?"$wrtlbl $cfglbl":"$cfglbl $wrtlbl") ?>">
	</form>
</div>
<?php
}
	$query	= GenQuery('configs','s','length(config),time','','',array('device'),array('='),array($shd) );
	$res	= DbQuery($query,$link);
	if (DbNumRows($res) == 1) {
		$dbcfg = DbFetchRow($res);
?>
<div style="float:right">
	<a href="Devices-Config.php?shc=<?= $ud ?>"><img src="img/16/conf.png" title="Devices-Config"></a>
	<a href="Devices-Doctor.php?dev=<?= $ud ?>"><img src="img/16/cinf.png" title="<?= $cfglbl ?> <?= $sumlbl ?>"></a>
</div>
<?= $buplbl ?> <?= date($_SESSION['timf'],$dbcfg[1]) ?> <?= $sizlbl ?> <?= DecFix($dbcfg[0]) ?>B
<?php
	}
?>

</td></tr>

<?php
$tmp = "";
if($dev[22]){
	$tmp = ($_SESSION['far'])?($dev[22]*1.8+32)."F":"$dev[22]C";
}
if($rver and $rrdcmd and $_SESSION['gsiz']){
	$gsiz = ($_SESSION['gsiz'] == 4)?2:1;
	echo "<tr class=\"txtb\"><td class=\"imgb s b\">$gralbl</th><td class=\"ctr\">";
	if( substr($dev[27],1,1) == "C" ){
		echo "<a href=\"Devices-Graph.php?dv=$ud&if[]=cpu\"><img src=\"inc/drawrrd.php?dv=$ud&t=cpu&s=$gsiz\" title=\"CPU $lodlbl $dev[20]%\"></a>\n";
	}
	$mlbl = ($dev[21] < 100)?"% $frelbl":"Bytes $frelbl";
	if($dev[21]){
		echo "<a href=\"Devices-Graph.php?dv=$ud&if[]=mem\"><img src=\"inc/drawrrd.php?dv=$ud&t=mem&s=$gsiz\" title=\"$memlbl ".DecFix($dev[21])."$mlbl\"></a>\n";
	}
	if($tmp){
		echo "<a href=\"Devices-Graph.php?dv=$ud&if[]=tmp\"><img src=\"inc/drawrrd.php?dv=$ud&t=tmp&s=$gsiz\" title=\"$tmplbl $tmp\"></a>\n";
	}
	if($dev[24]){
		list($ct,$cy,$cu) = explode(";", $dev[24]);
		echo "<a href=\"Devices-Graph.php?dv=$ud&if[]=cuv\"><img src=\"inc/drawrrd.php?dv=$ud&if[]=".urlencode($ct)."&if[]=".urlencode($cu)."&s=$gsiz&t=cuv\" title=\"$ct: ".DecFix($dev[23])."$cu\"></a>\n";
	}
	echo "</td></tr>\n";
}

flush();

echo "<tr class=\"bgsub\"><td class=\"b\">$stalbl</th><td>";
if( substr($dev[27],1,1) == "C" ){
	echo " <img src=\"img/16/cpu.png\" title=\"CPU $lodlbl\">".Bar($dev[20],$cpua/2,'si')." $dev[20]% &nbsp;&nbsp;";
}
if( $dev[30] > 1 and preg_match('/^..[PNS]/',$dev[27]) ){ 
	$putil = round($dev[31] / $dev[30] * 100,1);
	echo " <img src=\"img/16/batt.png\" title=\"PoE $lodlbl\">".Bar($putil,$poew/2,'si')." $putil% &nbsp;&nbsp;";
}
if($tmp){
	echo " <img src=\"img/16/temp.png\" title=\"$tmplbl\">".Bar($dev[22],$tmpa/2,'si')." $tmp ";
}

$pcnt = NodPop( array('device'),array('='),array($dev[0]),array() );
echo " <a href=\"Nodes-List.php?in[]=device&op[]==&st[]=$ud&ord=ifname\"><img src=\"img/16/nods.png\" title=\"$poplbl\">".Bar($pcnt,100,'si')." $pcnt</a>";

if ($rver){
	$ii = IfFree($dev[0]);
	echo " <a href=\"Devices-Interfaces.php?in[]=device&op[]==&st[]=$ud&co[]=AND&in[]=ifstat&op[]=<&st[]=3&co[]=AND&in[]=iftype&op[]=~&st[]=^(6|7|117)$&col[]=imBL&col[]=ifname&col[]=device&col[]=linktype&col[]=ifdesc&col[]=alias&col[]=lastchg&col[]=inoct&col[]=outoct&ord=lastchg\"><img src=\"img/p45.png\" title=\"$acslbl $porlbl $frelbl\">".Bar($ii,-5,'si')." $ii</a> ";

	if( $wasup ){
		$uptime = Get($ip, $rver, $dev[15], "1.3.6.1.2.1.1.3.0");
		if($uptime){
			$upsec = Tic2Sec($uptime);
			$sttit = date($_SESSION['timf'],time() - $upsec);
			$uptit = sprintf("%d %s %d:%02d",floor($upsec/86400),$tim['d'],floor(($upsec%86400)/3600),floor(($upsec%3600)/60));
			if ($upsec  < 86400){
				echo " <img src=\"img/16/warn.png\" title=\"$uptlbl < 24 $tim[h], $sttlbl $sttit\"> $uptit";
			}else{
				echo " <img src=\"img/16/bchk.png\" title=\"$uptlbl > 24 $tim[h], $sttlbl $sttit\"> $uptit";
			}

			if( array_key_exists($ip,$mapip) ){
				echo "<img src=\"img/16/cog.png\" title=\"$namlbl -> mapip\">".$mapip[$ip]['na'];
			}elseif( strpos($dev[27],'a') ){
				echo "<img src=\"img/16/net.png\" title=\"IP = $namlbl ($dsclbl -f) \">";
			}else{
				$sysnam = Get($ip, $rver, $dev[15], "1.3.6.1.2.1.1.5.0");
				$sysnar = preg_replace('/^"|"$/','',explode(".", $sysnam) );
				$devnar = explode(".", $dev[0]);
				if($sysnar[0] and $sysnar[0] != $devnar[0]){
					echo "<h4>$namlbl $chglbl: <a href=\"?dev=".urlencode($sysnar[0])."\">$sysnar[0]</a>!</h4>";
				}
			}
		}else{
			echo "$toumsg";
			if($_SESSION['vol']){echo "<embed src=\"inc/enter2.mp3\" volume=\"$_SESSION[vol]\" hidden=\"true\">\n";}
		}
	}else{
		echo "<h4>$dsclbl $stco[160]</h4>";
	}
}
echo "</td></tr>\n";

flush();
?>
</table>

</td><td class="helper">

<h2>
<?= ($isadmin)?"<a href=\"Topology-Linked.php?dv=$ud\"><img src=\"img/16/ncon.png\" title=\"".(($verb1)?"$edilbl Links":"Links $edilbl")."\"></a>":"" ?> 
<a href="Topology-Links.php?in[]=device&op[]==&st[]=<?= $ud ?>"><img src="img/16/link.png" title="Link <?= $lstlbl ?>"></a> <?= $cnclbl ?></h2>
<?php
$query = GenQuery('links','s','*','ifname','',array('device'),array('='),array($shd) );
$res   = DbQuery($query,$link);
if( DbNumRows($res) ){
?>
<table class="content" >
<tr class="bgsub">
<th width="120"><img src="img/16/port.png"><br>Interface</th>
<th><img src="img/16/dev.png"><br><?= $neblbl ?></th>
<th width="60"><img src="img/16/tap.png"><br><?= $bwdlbl ?></th>
<th width="50"><img src="img/16/abc.png"><br><?= $typlbl ?></th>
<th width="105"><img src="img/16/clock.png"><br><?= $timlbl ?></th>
</tr>
</table>
<div class="scroller">
<table class="content" >
<?php
	$row  = 0;
	while( $l = DbFetchRow($res) ){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		$ul = rawurlencode($l[3]);
		list($tc,$tc) = Agecol($l[10],$l[10],$row % 2);
		echo "<tr class=\"$bg\"><th class=\"$bi\" width=\"120\">\n";
		echo "$l[2]</th><td><a href=?dev=$ul>$l[3]</a>, $l[4]</td><td align=\"right\" width=\"70\">" . DecFix($l[5]) . " $l[8]</td>";
		echo "<td width=\"50\">$l[6]</td><td style=\"background-color:#$tc\" width=\"100\" nowrap>".date($_SESSION['timf'],$l[10])."</td></tr>\n";
	}
	DbFreeResult($res);
?>
</table>
</div>
<table class="content" >
	<tr class="bgsub"><td><?= $row ?> Links <?= $totlbl ?></td></tr>
</table>
<?php
}else{
?>
<h5><?= $nonlbl ?></h5>
<?php
}

if ($rver){
	$query = GenQuery('vlans','s','*','vlanid','',array('device'),array('='),array($shd) );
	$res   = DbQuery($query,$link);
	if( DbNumRows($res) ){
?>
<h2>
<a href="Devices-Vlans.php?in[]=device&op[]==&st[]=<?= $ud ?>"><img src="img/16/vlan.png" title="Vlan <?= $lstlbl ?>"></a>
Vlans</h2>

<table class="content" >
	<tr class="bgsub">
		<th class="s"><img src="img/16/vlan.png" title="SSIDs on some Wlan Controllers"><br>Vlan</th>
		<th><img src="img/16/say.png"><br><?= $namlbl ?></th>
	</tr>
</table>
<div class="scroller">
<table class="content" >
<?php
		$row  = 0;
		while( $v = DbFetchRow($res) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			echo "	<tr class=\"$bg\">\n";
			echo "		<td class=\"$bi ctr s\"><a href=\"?dev=$ud&pvi=$v[1]\" title=\"$fltlbl IF -> PVID\">$v[1]</a></td>\n";
			echo "		<td>$v[2]<div style=\"float:right\">";
			echo "<a href=\"Topology-Map.php?tit=Vlan+$v[1]+Map&in[]=vlanid&op[]==&st[]=$v[1]&fmt=png&mde=f&lev=4\"><img title=\"Vlan Map\" src=\"img/16/paint.png\"><a/>";
			echo "<a href=\"Devices-Vlans.php?in[]=vlanid&op[]==&st[]=$v[1]\"><img title=\"Device $lstlbl\" src=\"img/16/dev.png\"><a/>";
			echo "<a href=\"Devices-Interfaces.php?in[]=pvid&op[]==&st[]=$v[1]\"><img title=\"Vlan $idxlbl\" src=\"img/16/port.png\"><a/>";
			echo "<a href=\"Nodes-List.php?in[]=vlanid&op[]==&st[]=$v[1]\"><img title=\"IF Vlan $poplbl\" src=\"img/16/nods.png\"><a/>";
			echo "</div>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
		}
		DbFreeResult($res);
?>
</table>
</div>
<table class="content">
	<tr class="bgsub"><td><?= $row ?> Vlans <?= $totlbl ?></td></tr>
</table>
<?php
	}else{
?>
<h2>Vlans</h2>
<h5><?= $nonlbl ?></h5>
<?php
	}
}

if ($rver or $dev[13] == 11){										# Phones can have extensions like keypads and cameras
	$query	= GenQuery('modules','s','count(slot)','','',array('device'),array('='),array($shd) );
	$res	= DbQuery($query,$link);
	if($res){
		$tmod = DbFetchRow($res);
		DbFreeResult($res);
		if($tmod[0]){
			$query	= GenQuery('modules','s','*',($os == 'Printer')?'status':'slot','',array('device'),array('='),array($shd) );
			$res	= DbQuery($query,$link);
?>
<h2>
<a href="Devices-Modules.php?in[]=device&op[]==&st[]=<?= $ud ?>&ord=slot"><img src="img/16/cubs.png" title="<?= $sholbl ?> <?= $lstlbl ?>"></a>
<?php
		if($os == "Printer"){
?>
Supplies</h2>
<table class="content" >
<tr class="bgsub">
<th width="350"><img src="img/16/file.png" title="<?= $typlbl ?>,<?= $deslbl ?>"><br><?= $typlbl ?></th>
<th><img src="img/16/form.png"><br><?= $levlbl ?></th></tr>
</table>
<div class="scroller">
<table class="content" >
<?php
			$row  = 0;
			while( $m = DbFetchRow($res) ){
				if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
				$row++;
				echo "<tr class=\"$bg\"><th class=\"$bi\" width=\"40\">".PrintSupply($m[1])."</th>\n";
				echo "<td width=\"300\">$m[3]</td><td>".Bar($m[10],-33)." $m[10]%</td></tr>\n";
			}
			DbFreeResult($res);
?>
</table>
</div>
<table class="content" >
<tr class="bgsub">
<td><?= $tmod[0] ?> Supplies <?= $totlbl ?></td></tr>
</table>
<?php
		}elseif($os == "ESX"){#TODO check XEN (xe vm-start name-label=)
?>
Virtual Machines</h2>
<table class="content" >
	<tr class="bgsub">
		<th><img src="img/16/node.png" title="<?= $stalbl ?>, <?= $namlbl ?>"><br>VM</th>
		<th title="# CPUs, <?= $memlbl ?>" width="300"><img src="img/16/cinf.png"><br>HW</th>
	</tr>
</table>
<div class="scroller">
<table class="content" >
<?php
			if($uptime){
				foreach( Walk($ip, $rver, $dev[15],"1.3.6.1.4.1.6876.2.1.1.6") as $ix => $val){
					$vmpwr[substr(strrchr($ix, "."), 1 )] = $val;
				}
			}
			$row  = 0;
			$tmem = 0;
			$tact = 0;
			while( $m = DbFetchRow($res) ){
				if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
				$row++;
				$pwr = ($vmpwr[$m[8]] == '"poweredOn"' or $vmpwr[$m[8]] == '"powered on"')?1:0;
				echo "	<tr class=\"$bg\">\n";
				echo "		<td class=\"$bi ctr s\">";
				if( preg_match("/^win/i",$m[7]) ){
					$shut    = "power.shutdown";
					$vmtools = "<div style=\"float:right\"><img src=\"img/16/nwin.png\" title=\"VMtools: $m[7]\"></div>";
				}elseif( preg_match("/^freebsd/i",$m[7]) ){
					$shut    = "power.shutdown";
					$vmtools = "<div style=\"float:right\"><img src=\"img/16/fbsd.png\" title=\"VMtools: $m[7]\"></div>";
				}elseif( preg_match("/^openbsd/i",$m[7]) ){
					$shut    = "power.shutdown";
					$vmtools = "<div style=\"float:right\"><img src=\"img/16/obsd.png\" title=\"VMtools: $m[7]\"></div>";
				}elseif( preg_match("/linux|rhel|redhat|sles|suse|ubuntu/i",$m[7]) ){
					$shut    = "power.shutdown";
					$vmtools = "<div style=\"float:right\"><img src=\"img/16/nlin.png\" title=\"VMtools: $m[7]\"></div>";
				}else{
					$shut    = "power.off";
					$vmtools = '';
				}
				if($isadmin and $login and $cliport){
					$vmx = substr($m[3], 0, strrpos($m[3],'/'));
					$vmp = substr($vmx, 0, strrpos($vmx,'/'));
					if($pwr){
						$tmem += $m[6];
						$tact++;
?>
<form method="post" action="Devices-Write.php">
<input type="hidden" name="in[]" value="device">
<input type="hidden" name="op[]" value="=">
<input type="hidden" name="st[]" value="<?= $dev[0] ?>">
<input type="hidden" name="cmd" value="vim-cmd vmsvc/<?= $shut ?> <?= $m[8] ?>">
<input type="hidden" name="scm" value="1">
<input type="image" src="img/16/exit.png" value="Submit" title="On, click to shutdown <?= $m[1] ?> ID:<?= $m[8] ?>" onclick="return confirm('<?= $shut ?> <?= $m[1] ?>?')">
</form>
<?php					}else{?>
<form method="post" action="Devices-Write.php">
<input type="hidden" name="in[]" value="device">
<input type="hidden" name="op[]" value="=">
<input type="hidden" name="st[]" value="<?= $dev[0] ?>">
<input type="hidden" name="cmd" value="vim-cmd vmsvc/power.on <?= $m[8] ?>">
<input type="hidden" name="scm" value="1">
<input type="image" src="img/16/bcls.png" value="Submit" title="Off, click to turn on <?= $m[1] ?> ID:<?= $m[8] ?>">
</form>
<?php					}?>

</th>
<td width="250">

<?php  if($vmpwr[$m[8]] != '"poweredOn"') { ?>
<div style="float:right;margin:2px 2px">
<form method="post" action="Devices-Write.php">
<input type="hidden" name="in[]" value="device">
<input type="hidden" name="op[]" value="=">
<input type="hidden" name="st[]" value="<?= $dev[0] ?>">
<input type="hidden" name="cmd" value="vim-cmd vmsvc/destroy <?= $m[8] ?>">
<input type="hidden" name="scm" value="1">
<input type="image" src="img/16/bcnl.png" value="Submit" onclick="return confirm('<?= $dellbl ?>, <?= $cfmmsg ?>')"  title="<?= $dellbl ?> <?= $m[1] ?>">
</form>
</div>
<?php } ?>
<strong><?= $m[1] ?></strong>
</td>
<?php
				}else{
					echo "<img src=\"img/16/".(($pwr)?"exit":"bcls").".png\" title=\"$m[2] (ID$m[8])\"></td>\n";
					echo "		<td class=\"b\">".substr($m[1],0,$_SESSION['lsiz'])."</td>\n";
				}
				echo "		<td class=\"$bi ctr s\"><a href=\"Nodes-Status.php?mac=$m[5]\" title=\"Nodes-Status $m[5]\"><img src=\"img/16/node.png\"></a></td>\n";
				echo "		<td class=\"m\">";
				for ($i = 1; $i <= $m[4]; $i++) {
					echo "<img src=\"img/16/cpu.png\" title=\"CPU $i\">";
				}
				echo "		</td>\n<td nowrap width=\"300\">$vmtools".Bar($m[6],0,'mi')." $m[6] Mb</td></tr>\n";
			}
			DbFreeResult($res);
?>
</table>
</div>
<table class="content" >
	<tr class="bgsub">
		<td><?= $row ?> VMs <?= $totlbl ?>, <?= $tact ?>VMs & <?= round($tmem/1000,2) ?>Gb Ram <?= $stco['100'] ?></td>
	</tr>
</table>
<?php
		}else{	
?>
Modules</h2>
<table class="content" >
	<tr class="bgsub">
		<th><img src="img/16/find.png"><br>Slot / <?= $mdllbl ?></th>
		<th width="130"><img src="img/16/key.png"><br><?= $serlbl ?></th>
	</tr>
</table>
<div class="scroller">
<table class="content" >
<?php
			$row  = 0;
			while( $m = DbFetchRow($res) ){
				if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
				$row++;
				list($mcl,$img) = ModClass($m[9]);
				echo "	<tr class=\"".($m[10]?"alrm":$bg)."\">\n";
				echo "		<th class=\"$bi s\">\n";				# status = 0 is ok...TODO add modstatus to DefGen?
				echo "<img src=\"img/16/$img.png\" title=\"$mcl\"></th><td>$m[1]</td><td>$m[2]</td><td width=\"120\">$m[4]</td>\n";
				echo "	</tr>\n";
			}
			DbFreeResult($res);
?>
</table>
</div>
<table class="content" >
	<tr class="bgsub">
		<td><?= $tmod[0] ?> Modules <?= $totlbl ?></td>
	</tr>
</table>
<?php
		}
		}
	}else{
		print DbError($link);
	}
}

echo "</td></tr></table>\n";
flush();

if($_SESSION['gsiz'] < 3){
	$mw = 240;
	$mh = 160;
}else{
	$mw = 320;
	$mh = 240;
}
?>

<h2>Maps</h2>
<table class="content">
	<tr class="bgsub">
<?php
	$query	= GenQuery('events','g','level','level desc','',array('time','source'),array('>','='),array( (time() - $rrdstep),$shd),array('AND'));
	$res	= DbQuery($query,$link);
	if($res){
		while( ($m = DbFetchRow($res)) ){
			$evlvl[$m[0]] = $m[1];
		}
		DbFreeResult($res);
	}else{
		print DbError($link);
	}

	if( is_array($evlvl) ){
?>
		<th>
			<script src="inc/Chart.min.js"></script>
			<a href="Monitoring-Events.php?in[]=source&op[]==&st[]=<?= $ud ?>">
			<canvas id="evlev"class="genpad" width="<?= $mw ?>" height="<?= $mh ?>"></canvas>
			</a>
			<script language="javascript">
var data = [
	{
		value : <?= ($evlvl['10'])?$evlvl['10']:0 ?>,
		color: "#aaaaaa"
	},
	{
		value : <?= ($evlvl['50'])?$evlvl['50']:0 ?>,
		color: "#44aa44"
	},
	{
		value : <?= ($evlvl['100'])?$evlvl['100']:0 ?>,
		color: "#4444aa"
	},
	{
		value : <?= ($evlvl['150'])?$evlvl['150']:0 ?>,
		color: "#aaaa44"
	},
	{
		value : <?= ($evlvl['200'])?$evlvl['200']:0 ?>,
		color: "#aa4444"
	},
	{
		value : <?= ($evlvl['250'])?$evlvl['250']:0 ?>,
		color: "#ccc2222"
	},
]
var ctx = document.getElementById("evlev").getContext("2d");
var myNewChart = new Chart(ctx).PolarArea(data,{segmentStrokeColor : "#aaa"});
			</script>
			<br>
<?= $msglbl ?>
		</th>
<?php } ?>
		<th><a href="Topology-Map.php?tit=<?= $ud ?>+<?= $neblbl ?>+Map&in[]=device&op[]==&st[]=<?= $ud ?>&co[]=OR&in[]=neighbor&op[]==&st[]=<?= $ud ?>&fmt=png&mde=f&lev=4"><img class="genpad" src="inc/drawmap.php?dim=<?= $mw ?>x<?= $mh ?>&in[]=device&op[]==&st[]=<?= $ud ?>&co[]=OR&in[]=neighbor&op[]==&st[]=<?= $ud ?>&mde=f&lev=4&pos=s"></a><br><?= $neblbl ?></th>
<?php if($pcnt > 0 and $pcnt < 100){ ?>
		<th><a href="Topology-Map.php?tit=<?= $ud ?>+Nodes+Map&in[]=device&op[]==&st[]=<?= $ud ?>&fmt=png&mde=f&lev=6&lal=70&ifi=on"><img class="genpad" src="inc/drawmap.php?dim=<?= $mw ?>x<?= $mh ?>&in[]=device&op[]==&st[]=<?= $ud ?>&lev=6&mde=f&pos=s"></a><br>Nodes</th>
<?php } ?>
<?php if($dev[12] != '-'){ ?>
		<th><a href="Topology-Map.php?tit=<?= $ug ?>+<?= $grplbl ?>+Map&in[]=devgroup&op[]==&st[]=<?= $ug ?>&fmt=png&mde=f&dmo=1&lev=4"><img class="genpad" src="inc/drawmap.php?dim=<?= $mw ?>x<?= $mh ?>&in[]=devgroup&op[]==&st[]=<?= $ug ?>&tit=%23&mde=f&dmo=1&lev=4&pos=d"></a><br><?= $dev[12] ?> <?= $grplbl ?></th>
<?php } ?>
	</tr>
</table>

<?php
if( count($ifn) ){
?>
<h2>
<a href="Devices-Interfaces.php?in[]=device&op[]==&st[]=<?= $ud ?>&ord=ifname"><img src="img/16/port.png" title="Interface <?= $lstlbl ?>"></a>
<a href="Topology-Networks.php?in[]=device&op[]==&st[]=<?= $ud ?>&ord=ifname"><img src="img/16/net.png" title="<?= $netlbl ?> <?= $lstlbl ?>"></a>
<a href="Reports-Interfaces.php?in[]=device&op[]==&st[]=<?= $ud ?>&ord=ifname&rep[]=trf&rep[]=err&rep[]=dsc&rep[]=brc&rep[]=net&rep[]=pop&lir=5"><img src="img/16/dif.png" title="Interface <?= $sumlbl ?>"></a>
Interfaces <?= ($pvi)?"(PVID $pvi)":'' ?></h2><p>
<table class="content" >
	<tr class="bgsub">
		<th colspan="2" class="m"><img src="img/16/port.png" title="IF <?= $stalbl ?> (<?= $rltlbl ?>)"><br><?= $namlbl ?></th>
		<th><img src="img/16/abc.png"><br>Alias</th>
		<th><img src="img/16/find.png"><br><?= $deslbl ?></th>
		<th><img src="img/16/vlan.png" title="pvid"><br>Vlan</th>
		<th><img src="img/spd.png" title="<?= $spdlbl ?>"><br><?= substr($spdlbl,0,5) ?></th>
		<th><img src="img/dpx.png"><br>Duplex</th>
		<th><img src="img/16/swit.png" title="<?= $stalbl ?> <?= $chglbl ?> (<?= $rltlbl ?>)"><br><?= $laslbl ?></th>
<?php
	if($pop){
?>
		<th><img src="img/16/nods.png"><br><?= (substr($poplbl,0,3)) ?></th>
<?php
		$query	= GenQuery('nodes','g','ifname','','',array('device'),array('='),array($shd) );
		$res	= DbQuery($query,$link);
		if($res){
			while( ($nc = DbFetchRow($res)) ){
				$ncount[$nc[0]] = $nc[1];
			}
		}
		$query	= GenQuery('iftrack','g','mac,ifupdate,ifname','','',array('device'),array('='),array($shd) );
		$res	= DbQuery($query,$link);
		if($res){
			while( ($nl = DbFetchRow($res)) ){
				$niflog[$nl[2]] = "$nl[0] $laslbl ". date($_SESSION['timf'],$nl[1]);
			}
		}
	}
	if($shg and $_SESSION['gsiz']){
?>
		<th><img src="img/16/grph.png"><br>IF <?= $gralbl ?></th>
<?php
	}else{
		$rrdt = ($rrdstep/60)." $tim[i]";
?>
		<th><img src="img/16/bbup.png" title="Octets/<?= $rrdt ?>"><br><?= (substr($inblbl,0,3)) ?></th>
		<th><img src="img/16/bbdn.png" title="Blue: Abs <?= $trflbl ?>"><br><?= (substr($oublbl,0,3)) ?></th>
		<th><img src="img/16/brup.png" title=" <?= $errlbl ?>/<?= $rrdt ?>"><br><?= (substr($inblbl,0,3)) ?></th>
		<th><img src="img/16/brdn.png" title="Red: <?= $mullbl ?> <?= $errlbl ?>"><br><?= (substr($oublbl,0,3)) ?></th>
		<th><img src="img/16/bbu2.png" title="Discards/<?= $rrdt ?>"><br><?= (substr($inblbl,0,3)) ?></th>
		<th><img src="img/16/bbd2.png"><br><?= (substr($oublbl,0,3)) ?></th>
		<th><img src="img/16/brc.png" title="Broadcasts/<?= $rrdt ?>"><br><?= (substr($inblbl,0,3)) ?></th>
<?php
	}
?>
		<th><img src="img/16/batt.png" title="PoE [mW]"><br>PoE</th>
		<th width="10%"><img src="img/netg.png" title="MAC IP VRF"><br><?= $adrlbl ?></th>
	</tr>

<?php
	if($uptime){
		foreach( Walk($ip, $rver, $dev[15],"1.3.6.1.2.1.2.2.1.8") as $ix => $val){
			$ifost[substr(strrchr($ix, "."), 1 )] = $val;
		}
		foreach( Walk($ip, $rver, $dev[15],"1.3.6.1.2.1.2.2.1.9") as $ix => $val){
			$iflac[substr(strrchr($ix, "."), 1 )] = $val;
		}
		if( strpos($dev[27],'S') ){
			foreach( Walk($ip, $rver, $dev[15],"1.3.6.1.4.1.9.5.1.4.1.1.11") as $ix => $val){
				$stkx[substr($ix,28)] = $val;
			}
		}
		if( preg_match('/^..[PNS]/',$dev[27]) ){ 
			foreach( Walk($ip, $rver, $dev[15],"1.3.6.1.2.1.105.1.1.1.3") as $ix => $val){
				$x = explode('.', $ix);
				if( strpos($dev[27],'S') ){
					$ifpst[$stkx["$x[12].$x[13]"]] = $val;
					$ifpsx[$stkx["$x[12].$x[13]"]] = "$x[12].$x[13]";
				}elseif( strpos($dev[27],'N') ){
					$ifpst[$ifnx["$x[12].$x[13]"]] = $val;
					$ifpsx[$ifnx["$x[12].$x[13]"]] = "$x[12].$x[13]";
				}else{
					$ifpst[$x[13]] = $val;
					$ifpsx[$x[13]] = "$x[12].$x[13]";
					#echo "$x[12].$x[13] $val<br>";
				}
			}
		}
	}
	$tpow= 0;
	$row = 0;
	foreach ( $ifn as $i => $in){
		if( !$pvi or $pvi == $ifv[$i] ){
			if ($row % 2){$bg = "txta"; $bi = "imga";$off=200;}else{$bg = "txtb"; $bi = "imgb";$off=185;}
			$row++;
			$blc = $bio = $bie = $boo = $boe = '';
			$bg3 = sprintf("%02x",$off);
			$ui  = urlencode($in);

			list($ifimg,$iftit) = Iftype($ift[$i]);
			if($uptime){
				if($ifost[$i] == "1" or $ifost[$i] == "up"){
					$ifstat = "good";
					$iftit .= " (Up)";
				}elseif($ifost[$i] == "2" or $ifost[$i] == "down"){
					$ifstat = "warn";
					$iftit .= " (Down)";
				}elseif($ifost[$i] == "5" or $ifost[$i] == "dormant"){
					$ifstat = "noti";
					$iftit .= " (Dormant)";
				}elseif($ifost[$i] == "6" or $ifost[$i] == "notPresent"){
					$ifstat = "txta";
					$iftit .= " ($nonlbl)";
				}else{
					$ifstat = "imga";
				}

				if ($ifa[$i] & 1){
					$cif = "0$i";
					$actmsg = "Disable $ifn[$i], $cfmmsg";
				}else{
					$cif = "1$i";
					$actmsg = "Enable $ifn[$i], $cfmmsg";
					if($ifost[$i] == "1" or $ifost[$i] == "up"){
						$ifstat = "noti";						# admin status down, but oper up?
					}elseif($ifost[$i] == "2" or $ifost[$i] == "down"){
						$ifstat = "alrm";
					}else{
						$ifstat = "imgb";
					}
				}
			}else{
				list($ifstat,$ifdb) = Ifdbstat($ifa[$i]);
				$iftit .= " DB:$ifdb";
			}

			$lcs = $upsec - Tic2Sec($iflac[$i]);
			if($lcs < 1){
				$iflch	= "-";
			}else{
				$iflch	= sprintf("%d-%d:%02d",floor($lcs/86400),floor(($lcs%86400)/3600),floor(($lcs%3600)/60));
				$redlc	= 360000/($lcs + 1);
				$blc = "title=\"$chglbl ".date($_SESSION['timf'],time() - $lcs)."\"";
				if($redlc > 5){
					if($redlc > 55){$redlc = 55;}
					$blc .= "style=\"background-color:#".sprintf("%02x",$redlc + $off)."$bg3$bg3\"";
				}
			}

			TblRow($bg);
			echo "		<td class=\"$ifstat ctr xs\">";
			if($isadmin and $dev[26] and $guiauth != 'none' and $wasup and $cif){
				echo "<a href=\"?dev=$ud&cif=$cif\"><img src=\"img/$ifimg\" onclick=\"return confirm('$actmsg')\" title=\"$i - $iftit\"></a></td>\n";
			}else{
				echo "<img src=\"img/$ifimg\" title=\"$i - $iftit\"></td>\n";
			}
			echo "		<td class=\"$bi b nw\">";
			if($ifstat == "good" and $guiauth != 'none' and $wasup and !isset($_GET['print'])){
				echo "<span class=\"blu\" title=\"$rltlbl $gralbl\" onclick=\"window.open('inc/rt-popup.php?d=$ud&ip=$ip&v=$dev[14]&c=$dev[15]&i=$i&in=$ui','$dev[1]_$i','scrollbars=0,menubar=0,resizable=1,width=600,height=400')\">$in</span></td>\n";
			}else{
				echo "$in</td>\n";
			}
			if(substr($dev[27],0,1) == "A" and $isadmin and $dev[26] and $guiauth != 'none' and $wasup and !isset($_GET['print'])){
?>
		<td>
			<form method="get">
			<input type="hidden" name="dev" value="<?= $dev[0] ?>">
			<input type="hidden" name="ifx" value="<?= $i ?>">
			<input type="text" name="ali" class="l" value="<?= $ifl[$i] ?>" onkeypress="if(event.keyCode==13)this.form.submit()">
			</form>
		</td>
<?php
			}else{
				echo "		<td>$ifl[$i]</td>\n";
			}
			$iclass = ($lcs < $rrdstep)?'gry':'';
			echo "<td>$ifi[$i]</td><td class=\"$iclass rgt\">$ifv[$i]</td>\n";
			echo "<td class=\"$iclass rgt\">".DecFix($ifs[$i])."</td>\n";
			echo "<td class=\"$iclass ctr\">$ifd[$i]</td>\n";
			echo "<td class=\"$iclass rgt nw\" $blc>$iflch</td>\n";

			if($pop){
				if($niflog[$in]){
					$bnl = sprintf("%02x","40" + $off);
					echo "<td style=\"background-color:#$bg3$bg3$bnl\" title=\"$niflog[$in]\">";
				}else{
					echo "<td>";
				}

				if($ncount[$in]){
					echo Bar($ncount[$in],8,'mi') . " <a href=Nodes-List.php?in[]=device&op[]==&st[]=$ud&co[]=AND&in[]=ifname&op[]==&st[]=$ui title=\"Nodes-List\">$ncount[$in]</a>\n";
				}
				echo "</td>\n";
			}

			if($shg and $_SESSION['gsiz']){
				echo "<td nowrap align=\"center\">\n";
				IfGraphs($ud, $ui, $ifs[$i], $_SESSION['gsiz']);
				echo "</td>\n";
			}else{

				if ($ino[$i] > 70){								# Ignore the first 70  bytes...
					$rio[$i] = ($ifs[$i])?round($dio[$i] / $ifs[$i] / $rrdstep * 800,1):0;
					$bio = "title=\"$rio[$i]%\" style=\"background-color:#".(($rio[$i] > $trfa)?sprintf("%02x","40" + $off):$bg3)."$bg3".sprintf("%02x","40" + $off)."\"";
					if ($die[$i]){
						$ier = $die[$i] * $die[$i] * 8/(($dio[$i])?$dio[$i]:1);
						if ($ier > 55){$ier = 55;}
						$bie = "style=\"background-color:#".sprintf("%02x", $ier+$off)."$bg3$bg3\"";
					}
				}
				if ($oto[$i] > 70){								# ...cauz some devs don't default to 0!
					$roo[$i] = ($ifs[$i])?round($doo[$i] / $ifs[$i] / $rrdstep * 800,1):0;
					$boo = "title=\"$roo[$i]%\" style=\"background-color:#".(($roo[$i] > $trfa)?sprintf("%02x","40" + $off):$bg3)."$bg3".sprintf("%02x","40" + $off)."\"";
					if ($doe[$i]){
						$oer = $doe[$i] * $doe[$i] * 8/(($doo[$i])?$doo[$i]:1);		# Relative err^2 with fix for / by 0
						if ($oer > 55){$oer = 55;}
						$boe = "bgcolor=#".sprintf("%02x", $oer+ $off)."$bg3$bg3";
					}
				}
				echo "<td $bio align=\"right\">".DecFix($dio[$i])."</td>\n";
				echo "<td $boo align=\"right\">".DecFix($doo[$i])."</td>\n";
				echo "<td $bie align=\"right\">".DecFix($die[$i])."</td>\n";
				echo "<td $boe align=\"right\">".DecFix($doe[$i])."</td>\n";
				echo "<td align=\"right\">".DecFix($did[$i])."</td>\n";
				echo "<td align=\"right\">".DecFix($dod[$i])."</td>\n";
				echo "<td align=\"right\">".DecFix($dib[$i])."</td>\n";
			}

			if($ifp[$i]){
				$tpow += $ifp[$i]/1000;
				$bp1 = sprintf("%02x",$ifp[$i]/280 + $off);
				echo "<td nowrap align=\"right\" style=\"background-color:#$bp1$bp1$bg3\">$ifp[$i]";
			}else{
				echo "<td nowrap align=\"right\" >";
			}
			if( preg_match('/^..[PNS]/',$dev[27]) and $ifpst[$i] ){ 
				if($isadmin and $wver){
					echo (($ifpst[$i] == 1)?"<a href=\"?dev=$ud&pif=0$ifpsx[$i]\"><img src=\"img/16/bchk.png\" title=\"PoE $stco[100]\"></a>":"<a href=\"?dev=$ud&pif=1$ifpsx[$i]\"><img src=\"img/16/bdis.png\" title=\"PoE $dsalbl\"></a>");
				}else{
					echo (($ifpst[$i] == 1)?"<img src=\"img/16/bchk.png\" title=\"PoE $stco[100]\">":"<img src=\"img/16/bdis.png\" title=\"PoE $dsalbl\">");
				}
			}
			echo "<td class=\"code\">";
			echo "<div class=\"frgt\">";
			include ("log/iftools.php");
			echo "</div>\n";
			if($ifm[$i]){echo "<span class=\"drd\">$ifm[$i]</span><br>";}
			if( array_key_exists($in, $net) ){
				foreach( array_keys($net[$in]) as $addr ){
					if( array_key_exists('ifip',$net[$in][$addr]) ){
						echo "<a href=\"Reports-Interfaces.php?in[]=devip&op[]=%3D&st[]=$addr%2F".$net[$in][$addr]['ifip']."&rep[]=net\">$addr/".$net[$in][$addr]['ifip']."</a>\n";
					}elseif( array_key_exists('ifip6',$net[$in][$addr]) ){
						echo "<span class=\"prp\">$addr/".$net[$in][$addr]['ifip6']."</span>\n";
					}
					if( array_key_exists('vrf',$net[$in][$addr]) ) echo "<a href=\"Topology-Networks.php?in[]=vrfname&op[]==&st[]=".urlencode($net[$in][$addr]['vrf'])."\">".$net[$in][$addr]['vrf']."</a>\n";
				}
			}
			echo "</td></tr>\n";
			}
	}
	?>
</table>
<table class="content" ><tr class="bgsub"><td>
	<?= $row ?>/<?= count( array_keys($ifn) ) ?> Interfaces<?= ($tpow)?", ${tpow}W $totlbl PoE":"" ?>
	<?= ($dev[13] == 9)?"<a href=\"?dev=$ud&adi=".($row+1)."\"><img src=\"img/16/add.png\" title=\"$addlbl\"></a>":''; ?>
</td></tr></table>
	<?php
}

if($stat){
	include_once ("inc/librep.php");
?>
<h2>
<a href="Monitoring-Events.php?in[]=source&op[]==&st[]=<?= $ud ?>"><img src="img/16/bell.png" title="<?= $msglbl ?>"></a>
<?= $mlvl[150] ?> <?= $laslbl ?></h2>
<?php
	Events($_SESSION['lim'],array('level','source'),array('>=','='),array(150,$dev[0]),array('AND') );

	MonLatency("device","=",$dev[0],1,0);
	IncGroup("device","=",$dev[0],$_SESSION['lim'],0);
	MonAvail("device","=",$dev[0],1,0);
}
}
include_once ("inc/footer.php");
?>
