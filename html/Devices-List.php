<?
# Program: Devices-List.php
# Programmer: Remo Rickli

$printable = 1;
$calendar  = 1;

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

$mon = isset($_GET['mon']) ? $_GET['mon'] : "";

if( isset($_GET['col']) ){
	$col = $_GET['col'];
	if($_SESSION['olic']){$_SESSION['devcol'] = $col;}
}elseif( isset($_SESSION['devcol']) ){
	$col = $_SESSION['devcol'];
}else{
	$col = array('device','devip','serial','location','contact','lastdis');
}

$cols = array(	"device"=>"Device",
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
		"vtpdomain"=>"VTP Domain",
		"vtpmode"=>"VTP Mode",
		"snmpversion"=>"SNMP $verlbl",
		"readcomm"=>"$realbl Community",
		"writecomm"=>"$wrtlbl Community",
		"login"=>"Login",
		"cliport"=>"CLI port",
		"firstdis"=>"$fislbl $dsclbl",
		"lastdis"=>"$laslbl $dsclbl",
		"cpu"=>"% CPU",
		"memcpu"=>"$memlbl $frelbl",
		"temp"=>$tmplbl,
		"cusvalue"=>"$cuslbl Value",
		"cuslabel"=>"$cuslbl Label",
		"sysobjid"=>"SysObjID"
		);

$link = @DbConnect($dbhost,$dbuser,$dbpass,$dbname);							# Above print-header!
$listalert = "";
if($listwarn){
	$cnr  = @DbFetchRow(DbQuery(GenQuery('devices','s','count(*)'), $link));
	if($cnr[0] > $listwarn){
		$listalert = "onclick=\"if(document.list.sta.value == ''){return confirm('".(($verb1)?"$sholbl $alllbl $cnr[0]":"$alllbl $cnr[0] $sholbl")."?');}\"";
	}
}
?>
<h1>Device <?=$lstlbl?></h1>

<?if( !isset($_GET['print']) ){?>

<form method="get" name="list" action="<?=$self?>.php">
<table class="content"><tr class="<?=$modgroup[$self]?>1">
<th width="50"><a href="<?=$self?>.php"><img src="img/32/<?=$selfi?>.png"></a></th>
<th valign="top"><?=$cndlbl?> A<p>
<select size="1" name="ina">
<?
foreach ($cols as $k => $v){
       echo "<option value=\"$k\"".( ($ina == $k)?"selected":"").">$v\n";
}
?>
</select>
<select size="1" name="opa">
<? selectbox("oper",$opa);?>
</select>
<p><a href="javascript:show_calendar('list.sta');"><img src="img/16/date.png"></a>
<input type="text" name="sta" value="<?=$sta?>" size="20">
</th>
<th valign="top"><?=$cmblbl?><p>
<select size="1" name="cop">
<? selectbox("comop",$cop);?>
</select>
</th>
<th valign="top"><?=$cndlbl?> B<p>
<select size="1" name="inb">
<?
foreach ($cols as $k => $v){
       echo "<option value=\"$k\"".( ($inb == $k)?"selected":"").">$v\n";
}
?>
</select>
<select size="1" name="opb">
<? selectbox("oper",$opb);?>
</select>
<p><a href="javascript:show_calendar('list.stb');"><img src="img/16/date.png"></a>
<input type="text" name="stb" value="<?=$stb?>" size="20">
</th>
<th valign="top"><?=$collbl?><p>
<select multiple name="col[]" size="4">
<?
foreach ($cols as $k => $v){
       echo "<option value=\"$k\"".((in_array($k,$col))?"selected":"").">$v\n";
}
?>
<option value="stp" <?=(in_array("stp",$col))?"selected":""?> ><?=$rltlbl?> STP
<option value="graphs" <?=(in_array("graphs",$col))?"selected":""?> ><?=$gralbl?>
</select>
</th>
<th width="80">
<input type="submit" value="<?=$sholbl?>" <?=$listalert?>>

<?if($isadmin){?>
<p>
<input type="submit" name="mon" value="Monitor" onclick="return confirm('Monitor <?=$addlbl?>?')" >
<?}?>
</th>
</tr></table></form>
<p>
<?
}

if($ina){
	ConHead($ina, $opa, $sta, $cop, $inb, $opb, $stb);
?>
<table class="content"><tr class="<?=$modgroup[$self]?>2">
<?
	ColHead('device',80);
	foreach($col as $h){
		if($h != 'graphs' and $h != 'stp' and $h != 'device'){
			ColHead($h);
		}
	}
	if( in_array("stp",$col) ){echo "<th>STP $stalbl</th>";}
	if( in_array("graphs",$col) ){echo "<th>$gralbl</th>";}
	echo "</tr>\n";

	$query	= GenQuery('devices','s','*',$ord,'',array($ina,$inb),array($opa,$opb),array($sta,$stb),array($cop) );
	$res	= @DbQuery($query,$link);
	if($res){
		$row   = 0;
		$most = '';
		while( ($dev = @DbFetchRow($res)) ){

			if($isadmin and $mon){
				if($dev[14]){
					$most = AddRecord('monitoring',"name=\"$dev[0]\"","name,monip,test,device","\"$dev[0]\",\"$dev[1]\",\"uptime\",\"$dev[0]\"");
				}else{
					$most = AddRecord('monitoring',"name=\"$dev[0]\"","name,monip,test,device","\"$dev[0]\",\"$dev[1]\",\"ping\",\"$dev[0]\"");
				}
			}

			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$ip  = long2ip($dev[1]);
			$oi  = long2ip($dev[19]);
			$ud  = urlencode($dev[0]);
			if($dev[21] > 100){
				$mem = DecFix($dev[21])."Bytes";
			}elseif($dev[21] > 0){
				$mem = "$dev[21]%";
			}else{
				$mem = "";
			}
			$tmp = ($_SESSION['gfar'])?($dev[22]*1.8+32)."F":"$dev[22]C";
			list($fc,$lc) = Agecol($dev[4],$dev[5],$row % 2);
			echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\"><th class=\"$bi\">\n";
			if(in_array("device",$col)){
				echo "<a href=\"Devices-Status.php?dev=$ud\"><img src=\"img/dev/$dev[18].png\" title=\"$dev[3]\" vspace=4></a>$most<br>\n";
				}
			echo "<b>$dev[0]</b>\n";
			if(in_array("devip",$col)){
				echo "<td>".Devcli($ip,$dev[16])."</td>";
			}
			if(in_array("origip",$col)){
				echo "<td>".Devcli($oi,$dev[16])."</td>";
			}
			if(in_array("serial",$col)){ echo "<td>$dev[2]</td>";}
			if(in_array("type",$col)){   echo "<td><a href=\"?ina=type&opa==&sta=".urlencode($dev[3])."\">$dev[3]</a></td>";}
			if(in_array("services",$col)){
				$sv = Syssrv($dev[6]);
				echo "<td>$sv ($dev[6])</td>";
			}
			if(in_array("description",$col)){echo "<td>$dev[7]</td>";}
			if(in_array("devos",$col))	{echo "<td><a href=\"?ina=devos&opa==&sta=".urlencode($dev[8])."\">$dev[8]</a></td>";}
			if(in_array("bootimage",$col))	{echo "<td><a href=\"?ina=bootimage&opa==&sta=".urlencode($dev[9])."\">$dev[9]</a></td>";}
			if(in_array("location",$col))	{echo "<td><a href=\"?ina=location&opa==&sta=".urlencode($dev[10])."\">$dev[10]</a></td>";}
			if(in_array("contact",$col))	{echo "<td><a href=\"?ina=contact&opa==&sta=".urlencode($dev[11])."\">$dev[11]</a></td>";}
			if(in_array("vtpdomain",$col))	{echo "<td><a href=\"?ina=vtpdomain&opa==&sta=".urlencode($dev[12])."\">$dev[12]</a></td>";}
			if(in_array("vtpmode",$col))	{echo "<td><a href=\"?ina=vtpmode&opa==&sta=$dev[13]\">$dev[13]".VTPmod($dev[13])."</a></td>";}
			if(in_array("snmpversion",$col)){echo "<td>Read:". ($dev[14] & 3) . (($dev[14] & 128)?"-HC ":" ") . (($dev[14] & 12)?" Write:".($dev[14] & 12 >> 2):"")."</td>";}
			if(in_array("readcomm",$col))	{echo "<td>".(($guiauth != 'none')?$dev[15]:"***")."</td>";}
			if(in_array("writecomm",$col))	{echo "<td>".(($isadmin and $guiauth != 'none')?$dev[26]:"***")."</td>";}
			if(in_array("login",$col))	{echo "<td><a href=\"?ina=location&opa==&sta=".urlencode($dev[17])."\">$dev[17]</a></td>";}
			if(in_array("cliport",$col))	{echo "<td>$dev[16]</td>";}
			if( in_array("firstdis",$col) ){
				$fs       = date($datfmt,$dev[4]);
				echo "<td bgcolor=\"#$fc\">$fs</td>";
			}
			if( in_array("lastdis",$col) ){
				$ls       = date($datfmt,$dev[5]);
				echo "<td bgcolor=\"#$lc\">$ls</td>";
			}
			if(in_array("cpu",$col))	{echo "<td align=right>$dev[20]</td>";}
			if(in_array("memcpu",$col))	{echo "<td align=right>$mem</td>";}
			if(in_array("temp",$col))	{echo "<td align=right>$tmp</td>";}
			if(in_array("cusvalue",$col)){echo "<td align=right>$dev[23]</td>";}
			if(in_array("cuslabel",$col))	{echo "<td align=right>$dev[24]</td>";}
			if(in_array("sysobjid",$col))	{
				echo "<td><a href=Other-Defgen.php?so=$dev[25]&ip=$ip&co=$dev[15]>$dev[25]</a></td>";
			}
			if(in_array("stp",$col)){
				echo "<td>";
				if($dev[14] and $dev[5] > time() - $rrdstep*2){
					$stppri	= str_replace('"','', Get($ip, $dev[14] & 3, $dev[15], "1.3.6.1.2.1.17.2.2.0") );
					if( preg_match("/^No Such|^$/",$stppri) ){
						echo "-";
					}else{
						echo "<a href=\"Topology-Spanningtree.php?dev=$ud\"><img src=\"img/16/traf.png\" title=\"Topology-Spanningtree\"></a>";
						$numchg	= str_replace('"','', Get($ip, $dev[14] & 3, $dev[15], "1.3.6.1.2.1.17.2.4.0") );
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
						echo "$prilbl:<span class=\"prp\">$stppri</span> $it TC:<span class=\"blu\">$numchg</span> $tcstr";
					}
				}
				echo "</td>";
			}
			if(in_array("graphs",$col)){
				echo "<td>";
				$gsiz = ($_SESSION['gsiz'] == 4)?2:1;
				if($dev[14]){echo "<a href=\"Devices-Graph.php?dv=$ud&if[]=cpu\"><img src=\"inc/drawrrd.php?dv=$ud&t=cpu&s=$gsiz\" title=\"$dev[20]% CPU $lodlbl\">\n";}
				if($dev[21]){echo "<a href=\"Devices-Graph.php?dv=$ud&if[]=mem\"><img src=\"inc/drawrrd.php?dv=$ud&t=mem&s=$gsiz\" title=\"$mem $memlbl $frelbl\">\n";}
				if($dev[22]){echo "<a href=\"Devices-Graph.php?dv=$ud&if[]=tmp\"><img src=\"inc/drawrrd.php?dv=$ud&t=tmp&s=$gsiz\" title=\"$tmplbl $tmp\">\n";}
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
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Devices<?=($ord)?", $srtlbl: $ord":""?></td></tr>
</table>
	<?
}
include_once ("inc/footer.php");
?>
