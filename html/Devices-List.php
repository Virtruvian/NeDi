<?php
# Program: Devices-List.php
# Programmer: Remo Rickli

$calendar  = 1;
$printable = 1;
$exportxls = 1;

error_reporting(1);
snmp_set_quick_print(1);
snmp_set_oid_numeric_print(1);
snmp_set_valueretrieval(SNMP_VALUE_LIBRARY);

include_once ("inc/header.php");
include_once ("inc/libdev.php");
include_once ("inc/libsnmp.php");

$_GET = sanitize($_GET);
$sta = isset($_GET['sta']) ? $_GET['sta'] : "";
$stb = isset($_GET['stb']) ? $_GET['stb'] : "";
$ina = isset($_GET['ina']) ? $_GET['ina'] : "";
$inb = isset($_GET['inb']) ? $_GET['inb'] : "";
$opa = isset($_GET['opa']) ? $_GET['opa'] : "";
$opb = isset($_GET['opb']) ? $_GET['opb'] : "";
$cop = isset($_GET['cop']) ? $_GET['cop'] : "";
$ord = isset($_GET['ord']) ? $_GET['ord'] : "";

$map = isset($_GET['map']) ? "checked" : "";
$lim = isset($_GET['lim']) ? preg_replace('/\D+/','',$_GET['lim']) : $listlim;
$mon = isset($_GET['mon']) ? 1 : 0;

if( isset($_GET['col']) ){
	$col = $_GET['col'];
	if($_SESSION['opt']){
		$_SESSION['devcol'] = $col;
		if(!$ord and $ina){$ord = $ina;}
	}
}elseif( isset($_SESSION['devcol']) ){
	$col = $_SESSION['devcol'];
}else{
	$col = array('device','devip','serial','location','contact','lastdis');
}

$cols = array(	"device"=>"Device",
		"panel"=>$imglbl,
		"devip"=>"$manlbl IP",
		"origip"=>"$orilbl IP",
		"serial"=>"$serlbl",
		"type"=>"Device $typlbl",
		"services"=>$srvlbl,
		"description"=>$deslbl,
		"devos"=>"Device OS",
		"bootimage"=>"Bootimage",
		"location"=>$loclbl,
		"contact"=>$conlbl,
		"devgroup"=>$grplbl,
		"devmode"=>$modlbl,
		"snmpversion"=>"SNMP $verlbl",
		"readcomm"=>"$realbl Community",
		"writecomm"=>"$wrtlbl Community",
		"login"=>"Login",
		"cliport"=>"CLI $porlbl",
		"firstdis"=>"$fislbl $dsclbl",
		"lastdis"=>"$laslbl $dsclbl",
		"cpu"=>"CPU $lodlbl",
		"memcpu"=>"$memlbl $frelbl",
		"temp"=>$tmplbl,
		"cusvalue"=>"$cuslbl $vallbl",
		"cuslabel"=>"$cuslbl $titlbl",
		"sysobjid"=>"SysObjID",
		"devopts"=>$opolbl,
		"size"=>$sizlbl,
		"stack"=>"Stack",
		"maxpoe"=>"$maxlbl PoE",
		"totpoe"=>"$totlbl PoE",
		"stpNS"=>"$rltlbl STP",
		"gfNS"=>"$gralbl"
		);

$link = @DbConnect($dbhost,$dbuser,$dbpass,$dbname);							# Above print-header!
?>
<h1>Device <?= $lstlbl ?></h1>

<?php  if( !isset($_GET['print']) and !isset($_GET['xls']) ) { ?>

<form method="get" name="list" action="<?= $self ?>.php">
<table class="content"><tr class="<?= $modgroup[$self] ?>1">
<th width="50"><a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png"></a>

</th>
<th valign="top">

<?= $cndlbl ?> A<p>
<select size="1" name="ina">
<?php
foreach ($cols as $k => $v){
	if( !preg_match('/(BL|IG|NS)$/',$k) ){
		echo "<option value=\"$k\"".( ($ina == $k)?" selected":"").">$v\n";
	}
}
?>
</select>
<select size="1" name="opa">
<?php selectbox("oper",$opa) ?>
</select>
<p><a href="javascript:show_calendar('list.sta');"><img src="img/16/date.png"></a>
<input type="text" name="sta" value="<?= $sta ?>" size="20">

</th>
<th valign="top">

<?= $cmblbl ?><p>
<select size="1" name="cop">
<?php selectbox("comop",$cop) ?>
</select>

</th>
<th valign="top">

<?= $cndlbl ?> B<p>
<select size="1" name="inb">
<?php
foreach ($cols as $k => $v){
	if( !preg_match('/(BL|IG|NS)$/',$k) ){
		echo "<option value=\"$k\"".( ($inb == $k)?" selected":"").">$v\n";
	}
}
?>
</select>
<select size="1" name="opb">
<?php selectbox("oper",$opb) ?>
</select>
<p><a href="javascript:show_calendar('list.stb');"><img src="img/16/date.png"></a>
<input type="text" name="stb" value="<?= $stb ?>" size="20">

</th>
<th valign="top">

<?= $collbl ?><p>
<select multiple name="col[]" size="4">
<?php
foreach ($cols as $k => $v){
       echo "<option value=\"$k\"".((in_array($k,$col))?" selected":"").">$v\n";
}
?>

</select>

</th>
<th valign="top">

<?= $optlbl ?><p>
<div align="left">
<img src="img/16/paint.png" title="<?= (($verb1)?"$sholbl $laslbl Map":"Map $laslbl $sholbl") ?>"> 
<input type="checkbox" name="map" <?= $map ?>><br>
<img src="img/16/form.png" title="<?= $limlbl ?>"> 
<select size="1" name="lim">
<?php selectbox("limit",$lim) ?>
</select>
</div>

</th>
<th width="80">

<input type="submit" value="<?= $sholbl ?>">

<?php  if($isadmin) { ?>
<p>
<input type="submit" name="mon" value="<?= $monlbl ?>" onclick="return confirm('<?= $monlbl ?> <?= $addlbl ?>?')" >
<?}?>
</th>
</tr></table></form>
<p>
<?php
}

if($ina){
	if ($map and !isset($_GET['xls']) and file_exists("map/map_$_SESSION[user].php")) {
		echo "<center><h2>$netlbl Map</h2>\n";
		echo "<img src=\"map/map_$_SESSION[user].php\" style=\"border:1px solid black\"></center><p>\n";
	}
	ConHead($ina, $opa, $sta, $cop, $inb, $opb, $stb);
	TblHead("$modgroup[$self]2",1);

	$query	= GenQuery('devices','s','*',$ord,$lim,array($ina,$inb),array($opa,$opb),array($sta,$stb),array($cop) );
	$res	= @DbQuery($query,$link);
	if($res){
		$row   = 0;
		$most = '';
		while( ($dev = @DbFetchRow($res)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			if($isadmin and $mon and $dev[1]){
				if($dev[14] & 3){
					$most = AddRecord('monitoring',"name=\"$dev[0]\"","name,monip,test,device","\"$dev[0]\",\"$dev[1]\",\"uptime\",\"$dev[0]\"");
				}else{
					$most = AddRecord('monitoring',"name=\"$dev[0]\"","name,monip,test,device","\"$dev[0]\",\"$dev[1]\",\"ping\",\"$dev[0]\"");
				}
			}
			$ip  = long2ip($dev[1]);
			$oi  = long2ip($dev[19]);
			$ud  = urlencode($dev[0]);
			$stk = ($dev[29] > 1)?"<img src=\"img/$dev[29].png\" title=\"Stack\">":"";
			list($fc,$lc) = Agecol($dev[4],$dev[5],$row % 2);

			TblRow($bg);
			if( in_array("device",$col) ){
				TblCell($dev[0],"","class=\"$bi\" width=\"100px\"","<a href=\"Devices-Status.php?dev=$ud\"><img src=\"img/dev/$dev[18].png\" title=\"$dev[3]\"></a>$stk $most<br>","th-img");
			}
			if( in_array("panel",$col) ){
				TblCell('','',"bgcolor=\"white\"","<a href=\"Devices-Status.php?dev=$ud\"><img width=\"100\" src=\"".DevPanel($dev[3],$dev[18])."\" title=\"$dev[3]\"></a>$stk $most","th-img");
			}
			if(in_array("devip",$col)){
				$dvip = Devcli( $ip, $dev[16] );
				if( !in_array("device",$col) ){$dvip .= " ($dev[0])";}
				TblCell($dvip);
			}
			if(in_array("origip",$col)){
				TblCell( Devcli($oi,$dev[16]) );
			}
			if(in_array("serial",$col)){
				TblCell($dev[2]);
			}
			if(in_array("type",$col)){
				TblCell( $dev[3],"?ina=type&opa==&sta=".urlencode($dev[3]),'',"<a href=\"http://www.google.com/search?q=".urlencode($dev[3])."&btnI=1\" target=\"window\"><img src=\"img/16/find.png\" title=\"Google IT\"></a>
");
			}
			if(in_array("services",$col)){
				TblCell( Syssrv($dev[6])." ($dev[6])","?ina=services&opa==&sta=$dev[6]");
			}
			if(in_array("description",$col)){
				TblCell($dev[7]);
			}
			if(in_array("devos",$col)){
				TblCell( $dev[8],"?ina=devos&opa==&sta=".urlencode($dev[8]) );
			}
			if(in_array("bootimage",$col)){
				TblCell( $dev[9],"?ina=bootimage&opa==&sta=".urlencode($dev[9]) );
			}
			if(in_array("location",$col)){
				TblCell( $dev[10],"?ina=location&opa==&sta=".urlencode($dev[10]) );
			}
			if(in_array("contact",$col)){
				TblCell( $dev[11],"?ina=contact&opa==&sta=".urlencode($dev[11]) );
			}
			if(in_array("devgroup",$col)){
				TblCell( $dev[12],"?ina=devgroup&opa==&sta=".urlencode($dev[12]) );
			}
			if(in_array("devmode",$col)){
				TblCell( DevMode($dev[13]),"?ina=devmode&opa==&sta=".urlencode($dev[13]) );
			}
			if(in_array("snmpversion",$col)){
				TblCell( "Read:". ($dev[14] & 3) . (($dev[14] & 128)?"-HC ":" ") . (($dev[14] & 12)?" Write:".($dev[14] & 12 >> 2):"") );
			}
			if(in_array("readcomm",$col)){
				TblCell( (($guiauth != 'none')?$dev[15]:"***") );
			}
			if(in_array("writecomm",$col)){
				TblCell( (($isadmin and $guiauth != 'none')?$dev[26]:"***") );
			}
			if(in_array("login",$col)){
				TblCell( $dev[17],"?ina=login&opa==&sta=".urlencode($dev[17]) );
			}
			if(in_array("cliport",$col)){
				TblCell( $dev[16],"?ina=cliport&opa==&sta=".urlencode($dev[16]) );
			}
			if( in_array("firstdis",$col) ){
				TblCell( date($datfmt,$dev[4]),"?ina=firstdis&opa==&sta=$dev[4]","bgcolor=\"#$fc\"" );
			}
			if( in_array("lastdis",$col) ){
				TblCell( date($datfmt,$dev[5]),"?ina=lastdis&opa==&sta=$dev[5]","bgcolor=\"#$lc\"" );
			}
			if(in_array("cpu",$col)){
				TblCell("$dev[20]%",'','',Bar($dev[20],$cpua/2,'si')." ");
			}
			if(in_array("memcpu",$col)){
				if($dev[21] > 100){
					$mem = DecFix($dev[21])."Bytes";
				}elseif($dev[21] > 0){
					$mem = "$dev[21]%";
				}else{
					$mem = "";
				}
				TblCell( $mem,"","align=\"right\"" );
			}
			if(in_array("temp",$col)){
				TblCell( ($_SESSION['far'])?($dev[22]*1.8+32)."F":"$dev[22]C",'','',Bar($dev[22],$tmpa/2,'si')." " );
			}
			if(in_array("cusvalue",$col)){
				TblCell( $dev[23],"","align=\"right\"" );
			}
			if(in_array("cuslabel",$col)){
				TblCell( $dev[24],"","align=\"right\"" );
			}
			if(in_array("sysobjid",$col)){
				if( strstr($dev[25],'1.3.6.1.4.1.') ){
					TblCell($dev[25],"Other-Defgen.php?so=$dev[25]&ip=$ip&co=$dev[15]");
				}else{
					TblCell($dev[25],"?ina=sysobjid&opa==&sta=$dev[25]");
				}
			}
			if(in_array("devopts",$col)){
				TblCell($dev[27]);
			}
			if(in_array("size",$col)){
				TblCell($dev[28],"?ina=size&opa==&sta=$dev[28]","align=\"right\"");
			}
			if(in_array("stack",$col)){
				TblCell($dev[29],"?ina=stack&opa==&sta=$dev[29]","align=\"right\"");
			}
			if(in_array("maxpoe",$col)){
				TblCell($dev[30],"?ina=maxpoe&opa==&sta=$dev[30]","align=\"right\"");
			}
			if(in_array("totpoe",$col)){
				TblCell($dev[31]);
			}

			if( in_array("stpNS",$col) and !isset($_GET['xls']) ){
				if($dev[14] and $dev[5] > time() - $rrdstep*2){
					$stppri	= str_replace('"','', Get($ip, $dev[14] & 3, $dev[15], "1.3.6.1.2.1.17.2.2.0") );
					if( preg_match("/^No Such|^$/",$stppri) ){
						TblCell("?");
					}else{
						$numchg	= str_replace('"','', Get($ip, $dev[14] & 3, $dev[15], "1.3.6.1.2.1.17.2.4.0") );
						if( preg_match("/^No Such|^$/",$numchg) ){
							TblCell("TC:?");
						}else{
							$laschg	= str_replace('"','', Get($ip, $dev[14] & 3, $dev[15], "1.3.6.1.2.1.17.2.3.0") );
							sscanf($laschg, "%d:%d:%0d:%0d.%d",$tcd,$tch,$tcm,$tcs,$ticks);
							$tcstr  = sprintf("%dD-%d:%02d:%02d",$tcd,$tch,$tcm,$tcs);
							$rport	= str_replace('"','', Get($ip, $dev[14] & 3, $dev[15], "1.3.6.1.2.1.17.2.7.0") );
							if($rport){
								$rootif	 = str_replace('"','', Get($ip, $dev[14] & 3, $dev[15], "1.3.6.1.2.1.17.1.4.1.2.$rport") );
								$ifquery = GenQuery('interfaces','s','*','','',array('device','ifidx'),array('=','='),array($dev[0],$rootif),array('AND') );
								$ifres	 = @DbQuery($ifquery,$link);
								if(@DbNumRows($ifres) == 1){
									$if = @DbFetchRow($ifres);
									$it = "RP:<span class=\"grn\">$if[1] <i>$if[7]</i></span>";
								}else{
									$it = "Rootport n/a!";
								}
							}else{
								$it = "<span class=\"drd\">Root</span>";
							}
							TblCell("$prilbl:<span class=\"prp\">$stppri</span> $it TC:<span class=\"blu\">$numchg</span> $tcstr","","","<a href=\"Topology-Spanningtree.php?dev=$ud\"><img src=\"img/16/traf.png\" title=\"Topology-Spanningtree\"></a>");
						}
					}
				}else{
					TblCell("-");
				}
			}
			if( in_array("gfNS",$col) and !isset($_GET['xls']) ){
				echo "<td>";
				$gsiz = ($_SESSION['gsiz'] == 4)?2:1;
				if( substr($dev[27],1,1) == "C" ) echo "<a href=\"Devices-Graph.php?dv=$ud&if[]=cpu\"><img src=\"inc/drawrrd.php?dv=$ud&t=cpu&s=$gsiz\" title=\"$dev[20]% CPU $lodlbl\">\n";
				if($dev[21]) echo "<a href=\"Devices-Graph.php?dv=$ud&if[]=mem\"><img src=\"inc/drawrrd.php?dv=$ud&t=mem&s=$gsiz\" title=\"$mem $memlbl $frelbl\">\n";
				if($dev[22]) echo "<a href=\"Devices-Graph.php?dv=$ud&if[]=tmp\"><img src=\"inc/drawrrd.php?dv=$ud&t=tmp&s=$gsiz\" title=\"$tmplbl $tmp\">\n";
				if($dev[23]){
					if($dev[24] and $dev[24] != 'MemIO'){
						list($ct,$cy,$cu) = explode(";", $dev[24]);
					}else{
						$ct = "$memlbl IO";
						$cu = "Bytes $frelbl";
					}
					echo "<a href=\"Devices-Graph.php?dv=$ud&if[]=cuv\"><img src=\"inc/drawrrd.php?dv=$ud&if[]=".urlencode($ct)."&if[]=".urlencode($cu)."&s=$gsiz&t=cuv\" title=\"$ct ".DecFix($dev[23])." $cu\">";
				}
				echo "</td>";
			}
			echo "</tr>\n";
		}
		@DbFreeResult($res);
	}else{
		print @DbError($link);
	}
	?>
</table>

<table class="content">
<tr class="<?= $modgroup[$self] ?>2"><td><?= $row ?> Devices<?= ($ord)?", $srtlbl: $ord":"" ?><?= ($lim)?", $limlbl: $lim":"" ?></td></tr>
</table>
<?php
}
include_once ("inc/footer.php");
?>
