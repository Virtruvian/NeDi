<?
# Program: Nodes-List.php
# Programmer: Remo Rickli

$calendar  = 1;
$printable = 1;

include_once ("inc/header.php");
include_once ("inc/libnod.php");
include_once ("inc/libdev.php");

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
	if($_SESSION['olic']){$_SESSION['nodcol'] = $col;}
}elseif( isset($_SESSION['nodcol']) ){
	$col = $_SESSION['nodcol'];
}else{
	$col = array('name','nodip','firstseen','lastseen','ifname','vlanid');
}

$cols = array(	"name"=>$namlbl,
		"nodip"=>"IP $adrlbl",
		"ipupdate"=>"IP $updlbl",
		"ipchanges"=>"IP $chglbl",
		"iplost"=>"IP $loslbl",
		"arpval"=>"ARP $vallbl",
		"mac"=>"MAC $adrlbl",
		"oui"=>"OUI $venlbl",
		"firstseen"=>$fislbl,
		"lastseen"=>$laslbl,
		"type"=>"Device $typlbl",
		"location"=>$loclbl,
		"contact"=>$conlbl,
		"device"=>"Device $namlbl",
		"ifname"=>"Interface",
		"ifmetric"=>"IF $metlbl",
		"ifupdate"=>"IF $updlbl",
		"ifchanges"=>"IF $chglbl",
		"ifdesc"=>"IF $deslbl",
		"alias"=>"IF Alias",
		"speed"=>$spdlbl,
		"duplex"=>"Duplex",
		"vlanid"=>"Vlan",
		"tcpports"=>"TCP Ports",
		"udpports"=>"UDP Ports",
		"nodtype"=>$typlbl,
		"nodos"=>"Node OS",
		"osupdate"=>"OS $updlbl"
		);

$link = @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
if( isset($_GET['del']) ){
	if($isadmin){
		$query	= GenQuery('nodes','d','*','','',array($ina,$inb),array($opa,$opb),array($sta,$stb),array($cop));
		if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>Nodes $dellbl OK</h5>";}
	}else{
		echo $nokmsg;
	}
}

?>
<h1>Node <?=$lstlbl?></h1>

<?if( !isset($_GET['print']) ){?>

<form method="get" name="list" action="<?=$self?>.php" name="list">
<table class="content" ><tr class="<?=$modgroup[$self]?>1">
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
<option value="ssh" <?=(in_array("ssh",$col))?"selected":""?> >SSH Server
<option value="tel" <?=(in_array("tel",$col))?"selected":""?> >Telnet Server
<option value="www" <?=(in_array("www",$col))?"selected":""?> >Web Server
<option value="nbt" <?=(in_array("nbt",$col))?"selected":""?> >Netbios
<option value="graph" <?=(in_array("graph",$col))?"selected":""?> ><?=$gralbl?>
</select>
</th>
<th width="80">
<input type="submit" value="<?=$sholbl?>">

<?if($isadmin){?>
<p>
<input type="submit" name="mon" value="Monitor" onclick="return confirm('Monitor <?=$addlbl?>?')" >
<p>
<input type="submit" name="del" value="<?=$dellbl?>" onclick="return confirm('<?=$dellbl?> Nodes (<?=$cndlbl?>)?')" >
<?}?>
</th>
</tr></table></form><p>
<?
}
if ($ina){
ConHead($ina, $opa, $sta, $cop, $inb, $opb, $stb);
?>
<table class="content"><tr class="<?=$modgroup[$self]?>2">
<td width="20"></td>
<?
	if( in_array("name",$col) )	{ColHead('name');}
	if( in_array("nodip",$col) )	{ColHead('nodip');}
	if( in_array("ipupdate",$col) )	{ColHead('ipupdate');}
	if( in_array("ipchanges",$col) ){ColHead('ipchanges');}
	if( in_array("iplost",$col) )	{ColHead('iplost');}
	if( in_array("arpval",$col) )	{ColHead('arpval');}
	if( in_array("mac",$col) )	{ColHead('mac');}
	if( in_array("oui",$col) )	{ColHead('oui');}
	if( in_array("firstseen",$col) ){ColHead('firstseen');}
	if( in_array("lastseen",$col) )	{ColHead('lastseen');}
	if( in_array("type",$col) )	{ColHead('type');}
	if( in_array("location",$col) )	{ColHead('location');}
	if( in_array("contact",$col) )	{ColHead('contact');}
	if(in_array("device",$col) or  in_array('ifname',$col) ){ColHead('ifname');}
	if( in_array("ifmetric",$col) )	{ColHead('ifmetric');}
	if( in_array("ifupdate",$col) )	{ColHead('ifupdate');}
	if( in_array("ifchanges",$col) ){ColHead('ifchanges');}
	if( in_array("ifdesc",$col) ){ColHead('ifdesc');}
	if( in_array("alias",$col) ){ColHead('alias');}
	if( in_array("speed",$col) ){ColHead('speed');}
	if( in_array("duplex",$col) ){ColHead('duplex');}
	if( in_array("vlanid",$col) )	{ColHead('vlanid');}
	if( in_array("tcpports",$col) )	{ColHead('tcpports');}
	if( in_array("udpports",$col) )	{ColHead('udpports');}
	if( in_array("nodtype",$col) )	{ColHead('nodtype');}
	if( in_array("nodos",$col) )	{ColHead('nodos');}
	if( in_array("osupdate",$col) )	{ColHead('osupdate');}
	if( in_array("graph",$col))	{echo "<th>IF $gralbl</th>";}
	if( in_array("ssh",$col) )	{echo "<th>SSH $srvlbl</th>";}
	if( in_array("tel",$col) )	{echo "<th>Telnet $srvlbl</th>";}
	if( in_array("www",$col) )	{echo "<th>Web $srvlbl</th>";}
	if( in_array("nbt",$col) )	{echo "<th>Netbios $srvlbl</th>";}
	echo "</tr>\n";

	$query	= GenQuery('nodes','s','nodes.*,type,location,contact,iftype,ifdesc,alias,ifstat,speed,duplex,pvid',$ord,'',array($ina,$inb),array($opa,$opb),array($sta,$stb),array($cop),'LEFT JOIN devices USING (device) LEFT JOIN interfaces USING (device,ifname)');
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( ($n = @DbFetchRow($res)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$most		= '';
			$name		= preg_replace("/^(.*?)\.(.*)/","$1", $n[0]);
			$ip		= long2ip($n[1]);
			$img		= Nimg($n[3]);
			list($fc,$lc)	= Agecol($n[4],$n[5],$row % 2);
			$wasup		= ($n[5] > time() - $rrdstep)?1:0;
			$ud = urlencode($n[6]);
			$ui = urlencode($n[7]);

			if($isadmin and $mon and $n[1]){
				$mona  = ($n[0])?$n[0]:$ip;
				$most = AddRecord('monitoring',"name=\"$mona\"","name,monip,class,test,device,depend","\"$mona\",\"$n[1]\",\"node\",\"ping\",\"$n[6]\",\"$n[6]\"");
			}

			echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\"><th class=\"$bi\">\n";
			echo "<a href=\"Nodes-Status.php?mac=$n[2]&vid=$n[8]\"><img src=\"img/oui/$img.png\" title=\"$n[3] ($n[2])\"></a></th>\n";
			if(in_array("name",$col)){ echo "<td><b>$n[0]</b> $most</td>";}
			if(in_array("nodip",$col)){
				echo "<td>";
				echo ($noiplink)?$ip:"<a href=\"?ina=nodip&opa==&sta=$ip\">$ip</a>";
				echo "</td>";
			}
			if(in_array("ipupdate",$col)){
				$au      	= date($datfmt,$n[12]);
				list($a1c,$a2c) = Agecol($n[12],$n[12],$row % 2);
				echo "<td nowrap bgcolor=#$a1c>$au</td>";
			}
			if(in_array("ipchanges",$col))	{echo "<td align=\"right\">$n[13]</td>";}
			if(in_array("iplost",$col))	{echo "<td align=\"right\">$n[14]</td>";}
			if(in_array("arpval",$col))	{echo "<td align=\"right\">$n[15]</td>";}
			if(in_array("mac",$col))	{echo "<td class=\"drd code\">$n[2]</td>";}
			if(in_array("oui",$col))	{echo "<td><a href=\"http://www.google.com/search?q=".urlencode($n[3])."&btnI=1\">$n[3]</a></td>";}
			if(in_array("firstseen",$col)){
				$fs       = date($datfmt,$n[4]);
				echo "<td nowrap bgcolor=#$fc><a href=\"?ina=firstseen&opa==&sta=$n[4]\">$fs</a></td>";
			}
			if(in_array("lastseen",$col)){
				$ls       = date($datfmt,$n[5]);
				echo "<td nowrap bgcolor=#$lc><a href=\"?ina=lastseen&opa==&sta=$n[5]\">$ls</a></td>";
			}
			if(in_array("type",$col))	{echo "<td><a href=\"?ina=type&opa==&sta=$n[21]\">$n[21]</a></td>";}
			if(in_array("location",$col))	{echo "<td><a href=\"?ina=location&opa==&sta=$n[22]\">$n[22]</a></td>";}
			if(in_array("contact",$col))	{echo "<td><a href=\"?ina=contact&opa==&sta=$n[23]\">$n[23]</a></td>";}
			if(in_array("device",$col) or in_array("ifname",$col)){
				list($ifimg,$iftit) = Iftype($n[24]);
				list($ifbg,$ifst)   = Ifdbstat($n[27]);
				echo "<td nowrap class=\"$ifbg\"><a href=\"Devices-Status.php?dev=$ud&pop=on\"><img src=\"img/$ifimg\" title=\"$iftit, $ifst\"></a>\n";
				echo "<a href=\"?ina=device&opa==&sta=$ud&ord=ifname\">$n[6]</a>\n";
				echo " <a href=\"?ina=device&opa==&inb=ifname&opb==&sta=$ud&cop=AND&stb=$ui\">$n[7]</a></td>\n";
			}
			if(in_array("ifmetric",$col))	{echo "<td nowrap> ".(($n[9] < 255)?Bar($n[9],-30,'mi')."$n[9]db":"$n[9]")."</td>";}
			if(in_array("ifupdate",$col)){
				$iu       = date($datfmt,$n[10]);
				list($i1c,$i2c) = Agecol($n[10],$n[10],$row % 2);
				echo "<td nowrap bgcolor=#$i1c>$iu</td>";
			}
			if(in_array("ifchanges",$col))	{echo "<td align=\"right\">$n[11]</td>";}
			if(in_array("ifdesc",$col))	{echo "<td>$n[25]</td>";}
			if(in_array("alias",$col))	{echo "<td>$n[26]</td>";}
			if(in_array("speed",$col))	{echo "<td align=\"right\">".Zfix($n[28])."</td>";}
			if(in_array("duplex",$col))	{echo "<td>$n[29]</td>";}
			if(in_array("vlanid",$col))	{echo "<td><a href=\"?ina=vlanid&opa==&sta=$n[8]\">$n[8]</a></td>";}
			if(in_array("tcpports",$col))	{echo "<td>$n[16]</td>";}
			if(in_array("udpports",$col))	{echo "<td>$n[17]</td>";}
			if(in_array("nodtype",$col))	{echo "<td>$n[18]</td>";}
			if(in_array("nodos",$col))	{echo "<td>$n[19]</td>";}
			if(in_array("osupdate",$col)){
				$ou		= date($datfmt,$n[20]);
				list($o1c,$o2c) = Agecol($n[20],$n[20],$row % 2);
				echo "<td nowrap bgcolor=#$o1c>$ou</td>";
			}
			if(in_array("graph",$col)){
				$gsiz = ($_SESSION['gsiz'] == 4)?2:1;
				echo "<td nowrap align=\"center\">\n";
				echo "<a href=Devices-Graph.php?dv=$ud&if%5B%5D=$ui>\n";
				echo "<img src=inc/drawrrd.php?dv=$ud&if%5B%5D=$ui&s=$gsiz&t=trf&o=$n[28] title=\"$trflbl\">\n";
				echo "<img src=inc/drawrrd.php?dv=$ud&if%5B%5D=$ui&s=$gsiz&t=err&o=1 title=\"$errlbl\">\n";
				echo "<img src=inc/drawrrd.php?dv=$ud&if%5B%5D=$ui&s=$gsiz&t=dsc title=\"Discards\">\n";
				echo "<img src=inc/drawrrd.php?dv=$ud&if%5B%5D=$ui&s=$gsiz&t=brc title=\"Broadcasts\"></a></td>\n";
			}
			if(in_array("ssh",$col)){
				echo "<td><a href=ssh://$ip><img src=\"img/16/lokc.png\"></a>\n";
				echo (($wasup)?CheckTCP($ip,'22',''):"-") ."</td>";
			}
			if(in_array("tel",$col)){
				echo "<td><a href=telnet://$ip><img src=\"img/16/loko.png\"></a>\n";
				echo (($wasup)?CheckTCP($ip,'23',''):"-") ."</td>";
			}
			if(in_array("www",$col)){
				echo "<td><a href=http://$ip target=window><img src=\"img/16/glob.png\"></a>\n";
				echo (($wasup)?CheckTCP($ip,'80',"GET / HTTP/1.0\r\n\r\n"):"-") ."</td>";
			}
			if(in_array("nbt",$col)){
				echo "<td><img src=\"img/16/nwin.png\">\n";
				echo (($wasup)?NbtStat($ip):"-") ."</td>";
			}
			echo "</tr>\n";
		}
		@DbFreeResult($res);
	}else{
		print @DbError($link);
	}
	?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Nodes<?=($ord)?", $srtlbl: $ord":""?></td></tr>
</table>
	<?
}
include_once ("inc/footer.php");
?>
