<?
# Program: Devices-Vlans.php
# Programmer: Remo Rickli

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

if( isset($_GET['col']) ){
	$col = $_GET['col'];
	if($_SESSION['olic']){
		$_SESSION['vlcol'] = $col;
		if(!$ord and $ina){						# Default order by ina as suggested by community
			$ord = $ina;
		}
	}
}elseif( isset($_SESSION['vlcol']) ){
	$col = $_SESSION['vlcol'];
}else{
	$col = array('device','vlanid','vlanname');
}

$cols = array(	"device"=>"Device $namlbl",
		"type"=>"Device $typlbl",
		"location"=>$loclbl,
		"contact"=>$conlbl,
		"vlanid"=>"Vlan ID",
		"vlanname"=>"Vlan $namlbl",
		"pop"=>$poplbl
		);
		
$link = @DbConnect($dbhost,$dbuser,$dbpass,$dbname);							# Above print-header!
$listalert = "";
if($listwarn){
	$cnr  = @DbFetchRow(DbQuery(GenQuery('vlans','s','count(*)','','','','','','','LEFT JOIN devices USING (device)'), $link));
	if($cnr[0] > $listwarn){
		$listalert = "onclick=\"if(document.list.sta.value == ''){return confirm('".(($verb1)?"$sholbl $alllbl $cnr[0]":"$alllbl $cnr[0] $sholbl")."?');}\"";
	}
}

if( !isset($_GET['xls']) ){echo "<h1>Vlan $lstlbl</h1>";}
?>

<?if( !isset($_GET['print']) and !isset($_GET['xls']) ){?>

<form method="get" name="list" action="<?=$self?>.php">
<table class="content"><tr class="<?=$modgroup[$self]?>1">
<th width="50"><a href="<?=$self?>.php"><img src="img/32/<?=$selfi?>.png"></a></th>
<th valign="top"><?=$cndlbl?> A<p>
<select size="1" name="ina">
<?
foreach ($cols as $k => $v){
	if($k != 'pop'){										# Can't be used here
		echo "<option value=\"$k\"".( ($ina == $k)?"selected":"").">$v\n";
	}
}
?>
</select>
<select size="1" name="opa">
<? selectbox("oper",$opa);?>
</select>
<p>
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
	if($k != 'pop'){
		echo "<option value=\"$k\"".( ($inb == $k)?"selected":"").">$v\n";
	}
}
?>
</select>
<select size="1" name="opb">
<? selectbox("oper",$opb);?>
</select>
<p>
<input type="text" name="stb" value="<?=$stb?>" size="20">
</th>
<th valign="top"><?=$collbl?><p>
<select MULTIPLE name="col[]" size=4>
<?
foreach ($cols as $k => $v){
       echo "<option value=\"$k\"".((in_array($k,$col))?"selected":"").">$v\n";
}
?>
</select>
</th>
<th width="80">
<input type="submit" value="<?=$sholbl?>" <?=$listalert?>>
</th>
</tr></table></form><p>
<?
}

if($ina){
	ConHead($ina, $opa, $sta, $cop, $inb, $opb, $stb);
	TblHead("$modgroup[$self]2",TRUE);

	$query	= GenQuery('vlans','g','device,vlanid;vlanname,type,location,contact,count(mac) as pop',$ord,'',array($ina,$inb),array($opa,$opb),array($sta,$stb),array($cop),'LEFT JOIN devices USING (device)  LEFT JOIN nodes USING (device,vlanid)');
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( ($v = @DbFetchRow($res)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$ud = rawurlencode($v[0]);
			TblRow(TRUE);
			if(in_array("device",$col)){
				TblCell($v[0],"?ina=device&opa==&sta=$ud&ord=vlanid","","<a href=\"Devices-Status.php?dev=$ud\"><img src=\"img/16/sys.png\"></a>");
			}
			if(in_array("type",$col)){TblCell($v[4],"?ina=type&opa==&sta=".urlencode($v[4]));}
			if(in_array("location",$col)){TblCell($v[5],"?ina=location&opa==&sta=".urlencode($v[5]));}
			if(in_array("contact",$col)){TblCell($v[6],"?ina=contact&opa==&sta=".urlencode($v[6]));}
			if(in_array("vlanid",$col)){TblCell($v[1],"?ina=vlanid&opa==&sta=".urlencode($v[1]));}
			if(in_array("vlanname",$col)){TblCell($v[3],"?ina=vlanname&opa==&sta=".urlencode($v[3]));}
			if(in_array("pop",$col)){
				if ($v[7]){
					TblCell($v[7],'','',Bar($v[7],110,'mi'));
					#echo (!$xls)?"<td>$pbar <a href=Nodes-List.php?ina=device&opa==&sta=$ud&inb=vlanid&opb==&stb=$v[1]&cop=AND>$v[7]</td>":"<td>$v[7]</td>\n";
				}else{
					echo "<td></td>";
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
<table class="content">
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Vlans<?=($ord)?", $srtlbl: $ord":""?></td></tr>
</table>
<?
}

include_once ("inc/footer.php");
?>
