<?php
# Program: Nodes-List.php
# Programmer: Remo Rickli

$calendar  = 1;
$printable = 1;
$exportxls = 1;

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

$map = isset($_GET['map']) ? "checked" : "";
$lim = isset($_GET['lim']) ? preg_replace('/\D+/','',$_GET['lim']) : $listlim;

$mon = isset($_GET['mon']) ? $_GET['mon'] : "";

if( isset($_GET['col']) ){
	$col = $_GET['col'];
	if($_SESSION['opt']){$_SESSION['nodcol'] = $col;}
}elseif( isset($_SESSION['nodcol']) ){
	$col = $_SESSION['nodcol'];
}else{
	$col = array('imBL','name','nodip','firstseen','lastseen','device','ifname','vlanid');
}

$cols = array(	"imBL"=>$imglbl,
		"name"=>$namlbl,
		"mac"=>"MAC $adrlbl",
		"oui"=>"OUI $venlbl",
		"nodip"=>"IP $adrlbl",
		"nodip6"=>"IPv6 $adrlbl",
		"ipupdate"=>"IP $updlbl",
		"ipchanges"=>"IP $chglbl",
		"iplost"=>"IP $loslbl",
		"arpval"=>"ARP $vallbl",
		"firstseen"=>$fislbl,
		"lastseen"=>$laslbl,
		"device"=>"Device $namlbl",
		"type"=>"Device $typlbl",
		"location"=>$loclbl,
		"contact"=>$conlbl,
		"ifname"=>"IF $namlbl",
		"ifdesc"=>"IF $deslbl",
		"alias"=>"IF Alias",
		"speed"=>$spdlbl,
		"duplex"=>"Duplex",
		"vlanid"=>"Vlan",
		"ifmetric"=>"IF $metlbl",
		"ifupdate"=>"IF $updlbl",
		"ifchanges"=>"IF #$chglbl",
		"lastchg"=>"IF $stalbl $chglbl",
		"tcpports"=>"TCP $porlbl",
		"udpports"=>"UDP $porlbl",
		"nodtype"=>$typlbl,
		"nodos"=>"Node OS",
		"osupdate"=>"OS $updlbl",
		"noduser"=>"$usrlbl",
		"sshNS"=>"SSH $srvlbl",
		"telNS"=>"Telnet $srvlbl",
		"wwwNS"=>"HTTP $srvlbl",
		"nbtNS"=>"Netbios $srvlbl",
		"gfNS"=>"IF $gralbl"
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
<h1>Node <?= $lstlbl ?></h1>

<?php  if( !isset($_GET['print']) and !isset($_GET['xls']) ) { ?>

<form method="get" name="list" action="<?= $self ?>.php">
<table class="content" ><tr class="<?= $modgroup[$self] ?>1">
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
<th valign="top"><?= $collbl ?><p>
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
<input type="submit" name="mon" value="<?= $monlbl ?>" onclick="return confirm('Monitor <?= $addlbl ?>?')" >
<p>
<input type="submit" name="del" value="<?= $dellbl ?>" onclick="return confirm('<?= $dellbl ?>, <?= $cfmmsg ?>')" >
<?}?>
</th>
</tr></table></form><p>
<?php
}
if($ina){
	if ($map and !isset($_GET['xls']) and file_exists("map/map_$_SESSION[user].php")) {
		echo "<center><h2>$netlbl Map</h2>\n";
		echo "<img src=\"map/map_$_SESSION[user].php\" style=\"border:1px solid black\"></center><p>\n";
	}
	ConHead($ina, $opa, $sta, $cop, $inb, $opb, $stb);
	TblHead("$modgroup[$self]2",1);
	$query	= GenQuery('nodes','s','nodes.*,type,location,contact,iftype,ifdesc,alias,ifstat,speed,duplex,pvid,lastchg',$ord,$lim,array($ina,$inb),array($opa,$opb),array($sta,$stb),array($cop),'LEFT JOIN devices USING (device) LEFT JOIN interfaces USING (device,ifname)');
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
			$wasup		= ($n[5] > time() - $rrdstep * 1.5)?1:0;
			$ud = urlencode($n[6]);
			$ui = urlencode($n[7]);

			if($isadmin and $mon and $n[1]){
				$mona = ($n[0])?$n[0]:$ip;
				$most = AddRecord('monitoring',"name=\"$mona\"","name,monip,class,test,device,depend","\"$mona\",\"$n[1]\",\"node\",\"ping\",\"$n[6]\",\"$n[6]\"");
			}
			TblRow($bg);
			if(in_array("imBL",$col)){
				TblCell("","","class=\"$bi\" width=\"50\"","<a href=\"Nodes-Status.php?mac=$n[2]&vid=$n[8]\"><img src=\"img/oui/$img.png\" title=\"$n[3] ($n[2])\"></a>","th-img");
			}
			if(in_array("name",$col)){	TblCell("<b>$n[0]</b> $most");}			
			if( in_array("mac",$col) ){	TblCell($n[2],"","class=\"mrn code\"",( array_key_exists('Flower', $mod['Other']) )?"<a href=\"Other-Flower.php?fsm=".rtrim(chunk_split($n[2],2,":"),":")."\"><img src=\"img/16/".$mod['Other']['Flower'].".png\"></a>":"");}
			if(in_array("oui",$col)){	TblCell($n[3],"?ina=oui&opa==&sta=".urlencode($n[3]),"");}
			if(in_array("nodip",$col)){	TblCell($ip,"?ina=nodip&opa==&sta=$ip","",( array_key_exists('Flower', $mod['Other']) )?"<a href=\"Other-Flower.php?fet=2048&fsi=$ip\"><img src=\"img/16/".$mod['Other']['Flower'].".png\"></a>":"");}
			if(in_array("nodip6",$col)){
				if($n[16]){
					TblCell( inet_ntop($n[16]),"","class=\"prp code\"" );
				}else{
					TblCell();
				}
			}
			if(in_array("ipupdate",$col)){	
				list($a1c,$a2c) = Agecol($n[12],$n[12],$row % 2);
				TblCell( date($datfmt,$n[12]),"?ina=ipupdate&opa==&sta=$n[12]","nowrap bgcolor=\"#$a1c\"");
			}
			if(in_array("ipchanges",$col)){	TblCell($n[13],"?ina=ipchanges&opa==&sta=$n[13]","align=\"right\"");}
			if(in_array("iplost",$col)){	TblCell($n[14],"?ina=iplost&opa==&sta=$n[14]","align=\"right\"");}
			if(in_array("arpval",$col)){	TblCell($n[15],"?ina=arpval&opa==&sta=$n[15]","align=\"right\"");}
			if(in_array("firstseen",$col)){
				TblCell(date($datfmt,$n[4]),"?ina=firstseen&opa==&sta=$n[4]","nowrap bgcolor=\"#$fc\"");
			}
			if(in_array("lastseen",$col)){
				TblCell(date($datfmt,$n[5]),"?ina=lastseen&opa==&sta=$n[5]","nowrap bgcolor=\"#$lc\"");			
			}
			if( in_array("device",$col) ){
				TblCell($n[6],"?ina=device&opa==&sta=$ud&ord=ifname","nowrap","<a href=\"Devices-Status.php?dev=$ud&pop=on\"><img src=\"img/16/sys.png\"></a>");
			}
			if(in_array("type",$col)){	TblCell( $n[23],"?ina=type&opa==&sta=".urlencode($n[23]) );}
			if(in_array("location",$col)){	TblCell( $n[24],"?ina=location&opa==&sta=".urlencode($n[24]) );}
			if(in_array("contact",$col)){	TblCell( $n[25],"?ina=contact&opa==&sta=".urlencode($n[25]) );}

			if( in_array("ifname",$col) ){
				list($ifimg,$iftit) = Iftype($n[26]);
				list($ifbg,$ifst)   = Ifdbstat($n[29]);
				TblCell($n[7],"?ina=device&opa==&inb=ifname&opb==&sta=$ud&cop=AND&stb=$ui","class=\"$ifbg\"","<img src=\"img/$ifimg\" title=\"$iftit, $ifst\">","td-img");
			if(in_array("ifdesc",$col)){	TblCell($n[27]);}
			if(in_array("alias",$col)){	TblCell($n[28]);}
			if(in_array("speed",$col)){	TblCell( DecFix($n[30]),"","align=\"right\"" );}
			if(in_array("duplex",$col))	{TblCell($n[31]);}
			if(in_array("vlanid",$col))	{TblCell("$n[8] ($n[32])","?ina=vlanid&opa==&sta=$n[8]","align=\"right\"");}
			}
			if(in_array("ifmetric",$col)){	TblCell( (($n[9] < 255)?Bar($n[9],-30,'mi')." $n[9]db":"$n[9]"),"?ina=ifmetric&opa==&sta=$n[9]","nowrap" );}
			if(in_array("ifupdate",$col)){
				list($i1c,$i2c) = Agecol($n[10],$n[10],$row % 2);
				TblCell( date($datfmt,$n[10]),"","nowrap bgcolor=\"#$i1c\"");
			}
			if(in_array("ifchanges",$col)){	TblCell($n[11],"?ina=ifchanges&opa==&sta=$n[11]");}
			if(in_array("lastchg",$col)){
				list($i1l,$i2l) = Agecol($n[33],$n[33],$row % 2);
				TblCell(date($datfmt,$n[33]),"?ina=lastchg&opa==&sta=$n[33]","nowrap bgcolor=\"#$i1l\"");
			}
			if(in_array("tcpports",$col))	{TblCell($n[16],"?ina=tcpports&opa==&sta=$n[16]");}
			if(in_array("udpports",$col))	{TblCell($n[17],"?ina=udpports&opa==&sta=$n[17]");}
			if(in_array("nodtype",$col))	{TblCell($n[18],"?ina=nodtype&opa==&sta=$n[18]");}
			if(in_array("nodos",$col))	{TblCell($n[19],"?ina=nodos&opa==&sta=$n[19]");}
			if(in_array("osupdate",$col)){
				list($o1c,$o2c) = Agecol($n[20],$n[20],$row % 2);
				TblCell( date($datfmt,$n[20]),"?ina=osupdate&opa==&sta=$n[20]","nowrap bgcolor=\"#$o1c\"");
			}
			if(in_array("noduser",$col))	{TblCell($n[22],"?ina=noduser&opa==&sta=$n[22]");}

			if( !isset($_GET['xls']) ){
				if(in_array("sshNS",$col)){
					echo "<td><a href=ssh://$ip><img src=\"img/16/lokc.png\"></a>\n";
					echo (($wasup)?CheckTCP($ip,'22',''):"-") ."</td>";
				}
				if(in_array("telNS",$col)){
					echo "<td><a href=telnet://$ip><img src=\"img/16/loko.png\"></a>\n";
					echo (($wasup)?CheckTCP($ip,'23',''):"-") ."</td>";
				}
				if(in_array("wwwNS",$col)){
					echo "<td><a href=http://$ip target=window><img src=\"img/16/glob.png\"></a>\n";
					echo (($wasup)?CheckTCP($ip,'80',"GET / HTTP/1.0\r\n\r\n"):"-") ."</td>";
				}
				if(in_array("nbtNS",$col)){
					echo "<td><img src=\"img/16/nwin.png\">\n";
					echo (($wasup)?NbtStat($ip):"-") ."</td>";
				}
				if( in_array("gfNS",$col) ){
					echo "<td nowrap align=\"center\">\n";
					IfGraphs($ud, $ui, $n[30],($_SESSION['gsiz'] == 4)?2:1 );
					echo "</td>\n";
				}
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
<tr class="<?= $modgroup[$self] ?>2"><td><?= $row ?> Nodes<?= ($ord)?", $srtlbl: $ord":"" ?><?= ($lim)?", $limlbl: $lim":"" ?></td></tr>
</table>
	<?php
}
include_once ("inc/footer.php");
?>
