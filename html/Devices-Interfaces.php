<?php
# Program: Devices-Interfaces.php
# Programmer: Remo Rickli

$calendar  = 1;
$printable = 1;
$exportxls = 1;

include_once ("inc/header.php");
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

if( isset($_GET['col']) ){
	$col = $_GET['col'];
	if($_SESSION['opt']){$_SESSION['intcol'] = $col;}
}elseif( isset($_SESSION['intcol']) ){
	$col = $_SESSION['intcol'];
}else{
	$col = array('imBL','ifname','device','ifdesc','alias','comment');
}

$trk = isset($_GET['trk']) ? $_GET['trk'] : "";

$cols = array(	"imBL"=>$imglbl,
		"ifname"=>"IF $namlbl",
		"ifidx"=>"IF $idxlbl",
		"device"=>"Device $namlbl",
		"type"=>"Device $typlbl",
		"location"=>$loclbl,
		"contact"=>$conlbl,
		"firstdis"=>"$fislbl $dsclbl",
		"lastdis"=>"$laslbl $dsclbl",
		"linktype"=>"Link $typlbl",
		"iftype"=>"IF $typlbl",
		"ifmac"=>"MAC $adrlbl",
		"ifdesc"=>$deslbl,
		"alias"=>"Alias",
		"ifstat"=>$stalbl,
		"lastchg"=>"$stalbl $chglbl",
		"speed"=>$spdlbl,
		"duplex"=>"Duplex",
		"pvid"=>"Vlan $idxlbl",
		"inoct"=>"$totlbl $trflbl ".substr($inblbl,0,3),
		"outoct"=>"$totlbl $trflbl ".substr($oublbl,0,3),
		"inerr"=>"$totlbl $errlbl ".substr($inblbl,0,3),
		"outerr"=>"$totlbl $errlbl ".substr($oublbl,0,3),
		"indis"=>"$totlbl Discards ".substr($inblbl,0,3),
		"outdis"=>"$totlbl Discards ".substr($oublbl,0,3),
		"inbrc"=>"$totlbl Broadcasts ".substr($inblbl,0,3),
		"dinoct"=>"$laslbl $trflbl ".substr($inblbl,0,3),
		"doutoct"=>"$laslbl $trflbl ".substr($oublbl,0,3),
		"dinerr"=>"$laslbl $errlbl ".substr($inblbl,0,3),
		"douterr"=>"$laslbl $errlbl ".substr($oublbl,0,3),
		"dindis"=>"$laslbl Discards ".substr($inblbl,0,3),
		"doutdis"=>"$laslbl Discards ".substr($oublbl,0,3),
		"dinbrc"=>"$laslbl Broadcasts ".substr($inblbl,0,3),
		"poe"=>"PoE",
		"comment"=>$cmtlbl,
		"poNS"=>$poplbl,
		"gfNS"=>"IF $gralbl"
		);

$link = @DbConnect($dbhost,$dbuser,$dbpass,$dbname);							# Above print-header!
?>
<h1>Interface <?= $lstlbl ?></h1>

<?php  if( !isset($_GET['print']) and !isset($_GET['xls']) ) { ?>

<form method="get" name="list" action="<?= $self ?>.php">
<table class="content"><tr class="<?= $modgroup[$self] ?>1">
<th width="50"><a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png"></a></th>
<th valign="top"><?= $cndlbl ?> A<p>
<select size="1" name="ina">
<?php
foreach ($cols as $k => $v){
	if( !preg_match('/(BL|IG|NS)$/',$k) ){
		echo "<option value=\"$k\" ".( ($ina == $k)?" selected":"").">$v\n";
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
<th valign="top"><?= $cmblbl ?><p>
<select size="1" name="cop">
<?php selectbox("comop",$cop) ?>
</select>
</th>
<th valign="top"><?= $cndlbl ?> B<p>
<select size="1" name="inb">
<?php
foreach ($cols as $k => $v){
	if( !preg_match('/(BL|IG|NS)$/',$k) ){
		echo "<option value=\"$k\" ".( ($inb == $k)?" selected":"").">$v\n";
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
<select MULTIPLE name="col[]" size="4">
<?php
foreach ($cols as $k => $v){
       echo "<option value=\"$k\" ".((in_array($k,$col))?" selected":"").">$v\n";
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
<p>
<input type="submit" name="trk" value="Track">
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
	$query	= GenQuery('interfaces','s','interfaces.*,type,firstdis,lastdis,location,contact',$ord,$lim,array($ina,$inb),array($opa,$opb),array($sta,$stb),array($cop),'LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		$trkst = '';
		while( ($if = @DbFetchRow($res)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$ud = urlencode($if[0]);
			$ui = urlencode($if[1]);
			list($fc,$lc) = Agecol($if[33],$if[34],$row % 2);
			list($cc,$cc) = Agecol($if[26],$if[26],$row % 2);

			if($isadmin and $trk){
					$trkst = AddRecord('nodetrack',"device=\"$if[0]\" AND ifname=\"$if[1]\"","device,ifname,value,source,user,time","\"$if[0]\",\"$if[1]\",\"-\",\"-\",\"$_SESSION[user]\",\"".time()."\"");
			}

			TblRow($bg);
			if(in_array("imBL",$col)){
				list($ifbg,$ifst)	= Ifdbstat($if[8]);
				list($ifimg,$iftyp)	= Iftype($if[4]);
				TblCell("","","width=\"50\" class=\"".(($ifbg)?$ifbg:$bi)."\"","<img src=\"img/$ifimg\" title=\"$iftyp - $ifst\">","th-img");
			}

			if( in_array("ifname",$col) ){
				TblCell("$if[1] $trkst","?ina=ifname&opa==&sta=$ui","align=\"left\"","","th");
			}
			if( in_array("ifidx",$col) ){TblCell($if[2],"?ina=ifidx&opa==&sta=$if[2]","align=\"right\"");}

			if( in_array("device",$col) ){
				TblCell($if[0],"?ina=device&opa==&sta=$ud&ord=ifname","nowrap","<a href=\"Devices-Status.php?dev=$ud\"><img src=\"img/16/sys.png\"></a>");
			}
			if( in_array("type",$col) ){TblCell($if[32],"?ina=type&opa==&sta=$if[32]");}
			if( in_array("location",$col) ){TblCell( $if[35],"?ina=location&opa==&sta=".urlencode($if[35]) );}
			if( in_array("contact",$col) ){TblCell( $if[36],"?ina=contact&opa==&sta=".urlencode($if[36]) );}
			if( in_array("firstdis",$col) ){
				TblCell( date($datfmt,$if[33]),"?ina=firstdis&opa==&sta=$if[33]","bgcolor=\"#$fc\"" );
			}
			if( in_array("lastdis",$col) ){
				TblCell( date($datfmt,$if[34]),"?ina=lastdis&opa==&sta=$if[34]","bgcolor=\"#$lc\"" );
			}
			if( in_array("linktype",$col) ){TblCell($if[3],"?ina=linktype&opa==&sta=$if[3]");}
			if( in_array("iftype",$col) ){TblCell($if[4],"?ina=iftype&opa==&sta=$if[4]");}
			if( in_array("ifmac",$col) ){TblCell($if[5],"","class=\"mrn code\"");}
			if( in_array("ifdesc",$col) ){TblCell($if[6]);}
			if( in_array("alias",$col) ){TblCell($if[7]);}
			if( in_array("ifstat",$col) ){TblCell($if[8],"","align=\"right\"");}
			if( in_array("lastchg",$col) ){TblCell(date($datfmt,$if[26]),"?ina=lastchg&opa==&sta=$if[26]","bgcolor=\"#$cc\"");}
			if( in_array("speed",$col) ){TblCell( DecFix($if[9]),"","align=\"right\"" );}
			if( in_array("duplex",$col) ){TblCell($if[10]);}
			if( in_array("pvid",$col) ){TblCell($if[11],"","align=\"right\"");}

			if( in_array("inoct",$col) ){TblCell( DecFix($if[12])."B","","align=\"right\"" );}
			if( in_array("outoct",$col) ){TblCell( DecFix($if[14])."B","","align=\"right\"" );}
			if( in_array("inerr",$col) ){TblCell( DecFix($if[13]),"","align=\"right\"" );}
			if( in_array("outerr",$col) ){TblCell( DecFix($if[15]),"","align=\"right\"" );}
			if( in_array("indis",$col) ){TblCell( DecFix($if[20]),"","align=\"right\"" );}
			if( in_array("outdis",$col) ){TblCell( DecFix($if[21]),"","align=\"right\"" );}
			if( in_array("inbrc",$col) ){TblCell( DecFix($if[24]),"","align=\"right\"" );}

			if( in_array("dinoct",$col) ){TblCell( DecFix($if[16])."B","","align=\"right\"" );}
			if( in_array("doutoct",$col) ){TblCell( DecFix($if[18])."B","","align=\"right\"" );}
			if( in_array("dinerr",$col) ){TblCell( DecFix($if[17]),"","align=\"right\"" );}
			if( in_array("douterr",$col) ){TblCell( DecFix($if[19]),"","align=\"right\"" );}
			if( in_array("dindis",$col) ){TblCell( DecFix($if[22]),"","align=\"right\"" );}
			if( in_array("doutdis",$col) ){TblCell( DecFix($if[23]),"","align=\"right\"" );}
			if( in_array("dinbrc",$col) ){TblCell( DecFix($if[25]),"","align=\"right\"" );}

			if( in_array("poe",$col) ){TblCell($if[27]."mW","?ina=poe&opa==&sta=$if[27]","align=\"right\"");}
			if( in_array("comment",$col) ){TblCell($if[28]);}
			if( in_array("poNS",$col) and !isset($_GET['xls']) ){
				$nquery	= GenQuery('nodes','g','ifname','','',array('device','ifname'),array('=','='),array($if[0],$if[1]),array('AND') );
				$np  = @DbQuery($nquery,$link);
				$nnp = @DbNumRows($np);
				if ($nnp == 1) {
					$ifpop = @DbFetchRow($np);
					echo "<td nowrap>".Bar($ifpop[1],24,'mi')." <a href=Nodes-List.php?ina=device&opa==&sta=$ud&inb=ifname&opb==&stb=$if[1]&cop=AND>$ifpop[1]</td>";
				}else{
					TblCell("");
				}
				@DbFreeResult($np);
			}
			if( in_array("gfNS",$col) and !isset($_GET['xls']) ){
				$gsiz = ($_SESSION['gsiz'] == 4)?2:1;
				echo "<td nowrap align=\"center\">\n";
				IfGraphs($ud, $ui, $if[9], ($_SESSION['gsiz'] == 4)?2:1 );
				echo "</td>\n";
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
<tr class="<?= $modgroup[$self] ?>2"><td><?= $row ?> Interfaces<?= ($ord)?", $srtlbl: $ord":"" ?><?= ($lim)?", $limlbl: $lim":"" ?></td></tr>
</table>
	<?php
}
include_once ("inc/footer.php");
?>
