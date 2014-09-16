<?php
# Program: Nodes-List.php
# Programmer: Remo Rickli

$printable = 1;
$exportxls = 1;

include_once ("inc/header.php");
include_once ("inc/libnod.php");
include_once ("inc/libdev.php");

$_GET = sanitize($_GET);
$in = isset($_GET['in']) ? $_GET['in'] : array();
$op = isset($_GET['op']) ? $_GET['op'] : array();
$st = isset($_GET['st']) ? $_GET['st'] : array();
$co = isset($_GET['co']) ? $_GET['co'] : array();

$ord = isset($_GET['ord']) ? $_GET['ord'] : '';
if($_SESSION['opt'] and !$ord and $in[0]) $ord = 'mac';							# Sort by aname without filter is very slow with lots of nodes!

$map = isset($_GET['map']) ? "checked" : "";
$lim = isset($_GET['lim']) ? preg_replace('/\D+/','',$_GET['lim']) : $listlim;

$mon = isset($_GET['mon']) ? $_GET['mon'] : "";
$del = isset($_GET['del']) ? $_GET['del'] : "";

if( isset($_GET['col']) ){
	$col = $_GET['col'];
	if($_SESSION['opt']) $_SESSION['nodcol'] = $col;
}elseif( isset($_SESSION['nodcol']) ){
	$col = $_SESSION['nodcol'];
}else{
	$col = array('imBL','aname','nodip','firstseen','lastseen','device','ifname','vlanid');
}

$cols = array(	"imBL"=>$imglbl,
		"aname"=>$namlbl,
		"mac"=>"MAC $adrlbl",
		"oui"=>"$venlbl",
		"nodip"=>"IP $adrlbl",
		"ipupdate"=>"IP $updlbl",
		"nodip6"=>"IPv6 $adrlbl",
		"ip6update"=>"IP6 $updlbl",
		"firstseen"=>$fislbl,
		"lastseen"=>$laslbl,
		"noduser"=>"$usrlbl",
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
		"pvid"=>"Port Vlan $idxlbl",
		"metric"=>$metlbl,
		"ifupdate"=>"IF $updlbl",
		"ifchanges"=>"#IF $chglbl",
		"lastchg"=>"IF $stalbl $chglbl",
		"dinoct"=>"$laslbl $trflbl ".substr($inblbl,0,3),
		"doutoct"=>"$laslbl $trflbl ".substr($oublbl,0,3),
		"dinerr"=>"$laslbl $errlbl ".substr($inblbl,0,3),
		"douterr"=>"$laslbl $errlbl ".substr($oublbl,0,3),
		"dindis"=>"$laslbl $dcalbl ".substr($inblbl,0,3),
		"doutdis"=>"$laslbl $dcalbl ".substr($oublbl,0,3),
		"dinbrc"=>"$laslbl Broadcasts ".substr($inblbl,0,3),
		"nodarp.tcpports"=>"TCP $porlbl",
		"nodarp.udpports"=>"UDP $porlbl",
		"nodarp.nodtype"=>$typlbl,
		"nodarp.nodos"=>"Node OS",
		"nodarp.osupdate"=>"OS $updlbl",
		"sshNS"=>"SSH $srvlbl",
		"telNS"=>"Telnet $srvlbl",
		"wwwNS"=>"HTTP $srvlbl",
		"nbtNS"=>"Netbios $srvlbl",
		"metNS"=>"$metlbl $gralbl",
		"rdrNS"=>"Radar $gralbl",
		"gfNS"=>"IF $gralbl"
		);

$link = DbConnect($dbhost,$dbuser,$dbpass,$dbname);

?>
<script src="inc/Chart.min.js"></script>

<h1>Node <?= $lstlbl ?></h1>

<?php  if( !isset($_GET['print']) and !isset($_GET['xls']) ) { ?>

<form method="get" name="list" action="<?= $self ?>.php">
<table class="content" ><tr class="bgmain">
<th width="50"><a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png" title="<?= $self ?>"></a>
</th>
<td>
<?php Filters(); ?>

</td>
<th valign="top">

<h3><?= $fltlbl ?></h3>
<a href="?in[]=dinerr&op[]=>&st[]=0&co[]=OR&in[]=douterr&op[]=>&st[]=0"><img src="img/16/brup.png" title="IF <?= $errlbl ?>"></a>
<a href="?in[]=dindis&op[]=>&st[]=0&co[]=OR&in[]=doutdis&op[]=>&st[]=0"><img src="img/16/bbu2.png" title="IF <?= $dcalbl ?>"></a>
<br>
<a href="?in[]=vlanid&op[]=~&st[]=&co[]=!%3D&in[]=pvid&op[]=~&st[]=&co[]=AND&in[]=metric&op[]=~&st[]=[A-L]"><img src="img/16/vlan.png" title="PVID != Vlan"></a>
<a href="?in[]=metric&op[]=~&st[]=[M-Z]"><img src="img/16/wlan.png" title="Wlan Nodes"></a>

</th>
<th>

<select multiple name="col[]" size="6" title="<?= $collbl ?>">
<?php
foreach ($cols as $k => $v){
       echo "<option value=\"$k\"".((in_array($k,$col))?" selected":"").">$v\n";
}
?>
</select>

</th>
<td>

<img src="img/16/paint.png" title="<?= (($verb1)?"$sholbl $laslbl Map":"Map $laslbl $sholbl") ?>"> 
<input type="checkbox" name="map" <?= $map ?>><br>
<img src="img/16/form.png" title="<?= $limlbl ?>"> 
<select size="1" name="lim">
<?php selectbox("limit",$lim) ?>
</select>

</td>
<th width="80">

<input type="submit" class="button" value="<?= $sholbl ?>">

<?php  if($isadmin) { ?>
<br>
<input type="submit" class="button" name="mon" value="<?= $monlbl ?>" onclick="return confirm('Monitor <?= $addlbl ?>?')" >
<br>
<input type="submit" class="button" name="del" value="<?= $dellbl ?>" onclick="return confirm('<?= $dellbl ?>, <?= $cfmmsg ?>')" >
<?php } ?>
</th>
</tr></table></form><p>
<?php
}
if( count($in) ){
	if ($map and !isset($_GET['xls']) and file_exists("map/map_$_SESSION[user].php")) {
		echo "<center><h2>$netlbl Map</h2>\n";
		echo "<img src=\"map/map_$_SESSION[user].php\" style=\"border:1px solid black\"></center><p>\n";
	}
	Condition($in,$op,$st,$co);
	if( $del ){ 
		array_push($col, "del");
		$cols['del'] = "$dellbl $stalbl";
	}
	TblHead("bgsub",1);
	$query	= GenQuery('nodes','s','nodes.*,location,contact,type,iftype,ifdesc,alias,ifstat,speed,duplex,pvid,lastchg,dinoct,doutoct,dinerr,douterr,dindis,doutdis,dinbrc,nodip,ipupdate,aname,nodip6,ip6update,aaaaname',$ord,$lim,$in,$op,$st,$co,'LEFT JOIN devices USING (device) LEFT JOIN interfaces USING (device,ifname) LEFT JOIN nodarp USING (mac) LEFT JOIN dns USING (nodip) LEFT JOIN nodnd USING (mac) LEFT JOIN dns6 USING (nodip6)');
	$res	= DbQuery($query,$link);
	if($res){
		$row = 0;
		while( ($n = DbFetchRow($res)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$most		= '';
			$name		= preg_replace("/^(.*?)\.(.*)/","$1", $n[32]);
			$ip		= long2ip($n[30]);
			$img		= Nimg($n[1]);
			list($fc,$lc)	= Agecol($n[2],$n[3],$row % 2);
			list($i1c,$i2c) = Agecol($n[8],$n[8],$row % 2);
			list($i1l,$i2l) = Agecol($n[22],$n[22],$row % 2);
			list($ifi,$ift)	= Iftype($n[15]);
			list($ifb,$ifs)	= Ifdbstat($n[18]);
			$wasup		= ($n[3] > time() - $rrdstep * 1.5)?1:0;
			$ud             = urlencode($n[4]);
			$ui             = urlencode($n[5]);

			if($isadmin and $mon and $n[30]){
				$mona = ($n[32])?$n[32]:$ip;
				$most = AddRecord('monitoring',"name='$mona'","name,monip,class,test,device,depend1","'$mona','$n[30]','node','ping','".DbEscapeString($n[4])."','".DbEscapeString($n[4])."'");
			}
			TblRow($bg);
			if(in_array("imBL",$col))	TblCell('',"Nodes-Status.php?mac=$n[0]&vid=$n[21]","$bi ctr xs","+<img src=\"img/oui/$img.png\" title=\"$n[1] ($n[0])\">");
			if(in_array("aname",$col))	TblCell("$name $most","?in[]=aname&op[]==&st[]=".urlencode($n[32]),'b');			
			if( in_array("mac",$col) )	TblCell($n[0],"?in[]=mac&op[]==&st[]=$n[0]",'code',( array_key_exists('Flower', $mod['Other']) )?"<a href=\"Other-Flower.php?fsm=".rtrim(chunk_split($n[0],2,":"),":")."\"><img src=\"img/16/".$mod['Other']['Flower'].".png\"></a>":'');
			if(in_array("oui",$col))	TblCell($n[1],"?in[]=oui&op[]==&st[]=".urlencode($n[1]) );
			if(in_array("nodip",$col))	TblCell($ip,"?in[]=nodip&op[]==&st[]=$ip",'',( array_key_exists('Flower', $mod['Other']) )?"<a href=\"Other-Flower.php?fet=2048&fsi=$ip\"><img src=\"img/16/".$mod['Other']['Flower'].".png\"></a>":'');
			if(in_array("ipupdate",$col))	TblCell( date($_SESSION['timf'],$n[31]),($n[31])?"?in[]=ipupdate&op[]==&st[]=$n[31]":"",'nw','',"background-color:#$i1c");
			if(in_array("nodip6",$col))	TblCell( DbIPv6($n[33]),'','prp code' );
			if(in_array("ip6update",$col))	TblCell( date($_SESSION['timf'],$n[34]),($n[34])?"?in[]=ip6update&op[]==&st[]=$n[34]":"",'nw','',"background-color:#$i1c");
			if(in_array("firstseen",$col))	TblCell(date($_SESSION['timf'],$n[2]),"?in[]=firstseen&op[]==&st[]=$n[2]",'nw','',"background-color:#$fc");
			if(in_array("lastseen",$col))	TblCell(date($_SESSION['timf'],$n[3]),"?in[]=lastseen&op[]==&st[]=$n[3]",'nw','',"background-color:#$lc");
			if(in_array("noduser",$col))	TblCell($n[22],"?in[]=noduser&op[]==&st[]=$n[22]");
			if( in_array("device",$col) )	TblCell($n[4],"?in[]=device&op[]==&st[]=$ud&ord=ifname",'nw',"<a href=\"Devices-Status.php?dev=$ud&pop=on\"><img src=\"img/16/sys.png\"></a>");
			if(in_array("type",$col))	TblCell( $n[14],"?in[]=type&op[]==&st[]=".urlencode($n[14]) );
			if(in_array("location",$col))	TblCell( $n[12],"?in[]=location&op[]==&st[]=".urlencode($n[12]) );
			if(in_array("contact",$col))	TblCell( $n[13],"?in[]=contact&op[]==&st[]=".urlencode($n[13]) );
			if( in_array("ifname",$col) )	TblCell($n[5],"?in[]=device&op[]==&in[]=ifname&op[]==&st[]=$ud&co[]=AND&st[]=$ui",$ifb,"<img src=\"img/$ifi\" title=\"$ift, $ifs\"> ");
			if(in_array("ifdesc",$col))	TblCell($n[16]);
			if(in_array("alias",$col))	TblCell($n[17],"?in[]=alias&op[]==&st[]=".urlencode($n[17]) );
			if(in_array("speed",$col))	TblCell( DecFix($n[19]),"","align=\"right\"" );
			if(in_array("duplex",$col))	TblCell($n[20]);
			if(in_array("vlanid",$col))	TblCell( (preg_match('/[M-Z]/',$n[7]) )?"SSID:$n[6]":$n[6],"?in[]=vlanid&op[]==&st[]=$n[6]",'rgt');
			if(in_array("pvid",$col))	TblCell( (preg_match('/[M-Z]/',$n[7]))?"CH:$n[21]":$n[21],"?in[]=pvid&op[]==&st[]=$n[21]",'rgt');
			if(in_array("metric",$col))	TblCell( $n[7] );
			if(in_array("ifupdate",$col))	TblCell( date($_SESSION['timf'],$n[8]),"?in[]=ifupdate&op[]==&st[]=$n[8]",'nw','',"background-color:#$i1c");
			if(in_array("ifchanges",$col))	TblCell($n[9],"?in[]=ifchanges&op[]==&st[]=$n[9]");
			if(in_array("lastchg",$col))	TblCell(date($_SESSION['timf'],$n[22]),"?in[]=lastchg&op[]==&st[]=$n[22]",'nw','',"background-color:#$i1l");
			if(in_array("dinoct",$col))	TblCell($n[23]);
			if(in_array("doutoct",$col))	TblCell($n[24]);
			if(in_array("dinerr",$col))	TblCell($n[25]);
			if(in_array("douterr",$col))	TblCell($n[26]);
			if(in_array("dindis",$col))	TblCell($n[27]);
			if(in_array("doutdis",$col))	TblCell($n[28]);
			if(in_array("dinbrc",$col))	TblCell($n[29]);

			if( !isset($_GET['xls']) ){
				if(in_array("sshNS",$col)){
					echo "		<td><a href=ssh://$ip><img src=\"img/16/lokc.png\"></a>\n";
					echo (($wasup)?CheckTCP($ip,'22',''):"-") ."</td>";
				}
				if(in_array("telNS",$col)){
					echo "		<td><a href=telnet://$ip><img src=\"img/16/loko.png\"></a>\n";
					echo (($wasup)?CheckTCP($ip,'23',''):"-") ."</td>";
				}
				if(in_array("wwwNS",$col)){
					echo "		<td><a href=http://$ip target=window><img src=\"img/16/glob.png\"></a>\n";
					echo (($wasup)?CheckTCP($ip,'80',"GET / HTTP/1.0\r\n\r\n"):"-") ."</td>";
				}
				if(in_array("nbtNS",$col)){
					echo "		<td><img src=\"img/16/nwin.png\">\n";
					echo (($wasup)?NbtStat($ip):"-") ."</td>";
				}
				if( in_array("metNS",$col) ){
					echo "		<td class=\"ctr nw\">\n";
					MetricChart("me$row",$_SESSION['gsiz'], $n[7]);
					echo "		</td>\n";
				}
				if( in_array("rdrNS",$col) ){
					echo "		<td class=\"ctr nw\">\n";
					IfRadar("ra$row",$_SESSION['gsiz'],'284',$n[23],$n[24],$n[25],$n[26],$n[27],$n[28],$n[29]);
					echo "		</td>\n";
				}
				if( in_array("gfNS",$col) ){
					echo "		<td nowrap align=\"center\">";
					IfGraphs($ud, $ui, $n[30],($_SESSION['gsiz'] == 4)?2:1 );
					echo "	</td>\n";
				}
				if( $del ){
					echo "		<td>";
					if($isadmin){
						NodDelete($n[0]);
					}else{
						echo $nokmsg;
					}
					echo "	</td>\n";
				}
			}
			echo "	</tr>\n";
		}
		DbFreeResult($res);
	}else{
		print DbError($link);
	}
	TblFoot("bgsub", count($col), "$row $vallbl".(($ord)?", $srtlbl: $ord":"").(($lim)?", $limlbl: $lim":"") );
}
include_once ("inc/footer.php");
?>
