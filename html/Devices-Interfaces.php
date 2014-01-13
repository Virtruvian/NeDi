<?
# Program: Devices-Interfaces.php
# Programmer: Remo Rickli

$printable = 1;
$calendar  = 1;

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

if( isset($_GET['col']) ){
	$col = $_GET['col'];
	if($_SESSION['olic']){$_SESSION['intcol'] = $col;}
}elseif( isset($_SESSION['intcol']) ){
	$col = $_SESSION['intcol'];
}else{
	$col = array('device','ifname','ifdesc','alias','comment');
}

$trk = isset($_GET['trk']) ? $_GET['trk'] : "";

$cols = array(	"device"=>"Device $namlbl",
		"type"=>"Device $typlbl",
		"location"=>$loclbl,
		"contact"=>$conlbl,
		"ifidx"=>"IF Index",
		"ifname"=>"IF $namlbl",
		"linktype"=>"Link $typlbl",
		"iftype"=>"IF $typlbl",
		"ifmac"=>"MAC $adrlbl",
		"ifdesc"=>$deslbl,
		"alias"=>"Alias",
		"ifstat"=>$stalbl,
		"speed"=>$spdlbl,
		"duplex"=>"Duplex",
		"pvid"=>"Port Vlan ID",
		"inoct"=>"Abs. $trflbl $inblbl",
		"inerr"=>"Abs. $errlbl $inblbl",
		"outoct"=>"Abs. $trflbl $oublbl",
		"outerr"=>"Abs. $errlbl $oublbl",
		"dinoct"=>"$trflbl $inblbl",
		"dinerr"=>"$errlbl $inblbl",
		"doutoct"=>"$trflbl $oublbl",
		"douterr"=>"$errlbl $oublbl",
		"comment"=>$cmtlbl,
		"poe"=>"PoE"
		);

$link = @DbConnect($dbhost,$dbuser,$dbpass,$dbname);							# Above print-header!
$listalert = "";
if($listwarn){
	$cnr  = @DbFetchRow(DbQuery(GenQuery('interfaces','s','count(*)','','','','','','','LEFT JOIN devices USING (device)'), $link));
	if($cnr[0] > $listwarn){
		$listalert = "onclick=\"if(document.list.sta.value == ''){return confirm('".(($verb1)?"$sholbl $alllbl $cnr[0]":"$alllbl $cnr[0] $sholbl")."?');}\"";
	}
}

?>
<h1>Interface <?=$lstlbl?></h1>

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
<select MULTIPLE name="col[]" size="4">
<?
foreach ($cols as $k => $v){
       echo "<option value=\"$k\"".((in_array($k,$col))?"selected":"").">$v\n";
}
?>
<option value="pop" <?=(in_array("pop",$col))?"selected":""?> ><?=$poplbl?>
<option value="graph" <?=(in_array("graph",$col))?"selected":""?> >IF <?=$gralbl?>
</select>
</th>
<th width="80">
<input type="submit" value="<?=$sholbl?>" <?=$listalert?>>
<p>
<input type="submit" name="trk" value="Track" onclick="return confirm('<?=$cfmmsg?>')">
</th>
</tr></table></form><p>
<?
}
if($ina){
ConHead($ina, $opa, $sta, $cop, $inb, $opb, $stb);
	?>
<table class="content"><tr class="<?=$modgroup[$self]?>2">
	<?
	if( in_array("ifname",$col) ){echo "<td width=30></td>";}
	ColHead('ifname',80);
	foreach($col as $h){
		if($h != 'graph' and $h != 'ifname' and $h != 'pop'){
			ColHead($h);
		}
	}
	if( in_array("pop",$col) ){echo "<th>$poplbl</th>";}
	if( in_array("graph",$col) ){echo "<th>IF $gralbl</th>";}
	echo "</tr>\n";

	$query	= GenQuery('interfaces','s','*',$ord,'',array($ina,$inb),array($opa,$opb),array($sta,$stb),array($cop),'LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		$trkst = '';
		while( ($if = @DbFetchRow($res)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$ud = urlencode($if[0]);
			$ui = urlencode($if[1]);

			if($isadmin and $trk){
					$trkst = AddRecord('nodetrack',"device=\"$if[0]\" AND ifname=\"$if[1]\"","device,ifname,value,source,user,time","\"$if[0]\",\"$if[1]\",\"-\",\"-\",\"$_SESSION[user]\",\"".time()."\"");
			}

			echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\">";
			if(in_array("ifname",$col)){
				list($ifbg,$ifst)	= Ifdbstat($if[8]);
				list($ifimg,$iftyp)	= Iftype($if[4]);
				echo "<th class=\"".(($ifbg)?$ifbg:$bi)."\"><img src=\"img/$ifimg\" title=\"$iftyp - $ifst\"></th>";
			}
			echo "<td><a href=?ina=ifname&opa==&sta=$ui>$if[1]</a> $trkst</td>\n";
			if(in_array("device",$col)){
				echo "<td nowrap>\n";
				if( !isset($_GET['print']) and strpos($_SESSION['group'],$modgroup['Devices-Status']) !== false ){
					echo "<a href=\"Devices-Status.php?dev=$ud\"><img src=\"img/16/sys.png\"></a>\n";
				}
				echo "<a href=?ina=device&opa==&sta=$ud&ord=ifname>$if[0]</a></td>\n";
			}
			if(in_array("type",$col)){echo "<td><a href=\"?ina=type&opa==&sta=$if[24]\">$if[24]</a>";}
			if(in_array("location",$col)){echo "<td><a href=\"?ina=location&opa==&sta=".urlencode($if[31])."\">$if[31]</a></td>";}
			if(in_array("contact",$col)){echo "<td><a href=\"?ina=contact&opa==&sta=".urlencode($if[32])."\">$if[32]</a></td>";}
			if(in_array("ifidx",$col)){echo "<td align=\"right\">$if[2]</td>";}
			if(in_array("linktype",$col)){echo "<td><a href=\"?ina=linktype&opa==&sta=$if[3]\">$if[3]</a>";}
			if(in_array("iftype",$col)){echo "<td align=\"right\"><a href=?ina=iftype&opa==&sta=\"$if[4]\">$if[4]</a></td>";}
			if(in_array("ifmac",$col)){echo "<td class=\"mrn\">$if[5]</td>";}
			if(in_array("ifdesc",$col)){echo "<td>$if[6]</td>";}
			if(in_array("alias",$col)){echo "<td>$if[7]</td>";}
			if(in_array("ifstat",$col)){echo "<td align=\"right\">$if[8]</td>";}
			if(in_array("speed",$col)){echo "<td align=\"right\">".DecFix($if[9])."</td>";}
			if(in_array("duplex",$col)){echo "<td>$if[10]</td>";}
			if(in_array("pvid",$col)){echo "<td align=\"right\">$if[11]</td>";}

			if(in_array("inoct",$col)){echo "<td align=\"right\">".DecFix($if[12])."B</td>";}
			if(in_array("inerr",$col)){echo "<td align=\"right\">".DecFix($if[13])."</td>";}
			if(in_array("outoct",$col)){echo "<td align=\"right\">".DecFix($if[14])."B</td>";}
			if(in_array("outerr",$col)){echo "<td align=\"right\">".DecFix($if[15])."</td>";}
			if(in_array("dinoct",$col)){echo "<td align=\"right\">".DecFix($if[16])."B</td>";}
			if(in_array("dinerr",$col)){echo "<td align=\"right\">".DecFix($if[17])."</td>";}
			if(in_array("doutoct",$col)){echo "<td align=\"right\">".DecFix($if[18])."B</td>";}
			if(in_array("douterr",$col)){echo "<td align=\"right\">".DecFix($if[19])."</td>";}

			if(in_array("comment",$col)){echo "<td>$if[20]</td>";}
			if(in_array("poe",$col)){echo "<td>$if[21]</td>";}
			if(in_array("pop",$col)){
				$nquery	= GenQuery('nodes','g','ifname','','',array('device','ifname'),array('=','='),array($if[0],$if[1]),array('AND') );
				$np  = @DbQuery($nquery,$link);
				$nnp = @DbNumRows($np);
				if ($nnp == 1) {
					$ifpop = @DbFetchRow($np);
					echo "<td nowrap>".Bar($ifpop[1],24,'mi')." <a href=Nodes-List.php?ina=device&opa==&sta=$ud&inb=ifname&opb==&stb=$if[1]&cop=AND>$ifpop[1]</td>";
				}else{
					echo "<td><?=$nonlbl></td>";
				}
				@DbFreeResult($np);
			}
			if(in_array("graph",$col)){
				$gsiz = ($_SESSION['gsiz'] == 4)?2:1;
				echo "<td nowrap align=\"center\">\n";
				echo "<a href=\"Devices-Graph.php?dv=$ud&if%5B%5D=$ui\">\n";
				echo "<img src=\"inc/drawrrd.php?dv=$ud&if%5B%5D=$ui&s=$gsiz&t=trf&o=$if[9]\" title=\"$trflbl\">\n";
				echo "<img src=\"inc/drawrrd.php?dv=$ud&if%5B%5D=$ui&s=$gsiz&t=err&o=1\" title=\"$errlbl\">\n";
				echo "<img src=\"inc/drawrrd.php?dv=$ud&if%5B%5D=$ui&s=$gsiz&t=dsc\" title=\"Discards\">\n";
				echo "<img src=\"inc/drawrrd.php?dv=$ud&if%5B%5D=$ui&s=$gsiz&t=brc\" title=\"Broadcasts\"></a></td>\n";
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
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Interfaces<?=($ord)?", $srtlbl: $ord":""?></td></tr>
</table>
	<?
}
include_once ("inc/footer.php");
?>
