<?
# Program: Devices-Vlans.php
# Programmer: Remo Rickli

$printable = 1;

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
	if($_SESSION['olic']){$_SESSION['vlcol'] = $col;}
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
		"vlanname"=>"Vlan $namlbl"
		);

?>
<h1>Vlan <?=$lstlbl?></h1>

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
       echo "<option value=\"$k\"".( ($inb == $k)?"selected":"").">$v\n";
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
<option value="pop" <?=(in_array("pop",$col))?"selected":""?> ><?=$poplbl?>
</select>
</th>
<th width="80"><input type="submit" value="<?=$sholbl?>"></th>
</tr></table></form><p>
<?
}
if ($ina){
ConHead($ina, $opa, $sta, $cop, $inb, $opb, $stb);
	?>
<table class="content"><tr class="<?=$modgroup[$self]?>2">
	<?
	foreach($col as $h){
		if($h != 'pop'){
			ColHead($h);
		}
	}
	if( in_array("pop",$col) ){echo "<th>$poplbl</th>";}
	echo "</tr>\n";

	$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
	$query	= GenQuery('vlans','s','*',$ord,'',array($ina,$inb),array($opa,$opb),array($sta,$stb),array($cop),'LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( ($v = @DbFetchRow($res)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$ud = rawurlencode($v[0]);
			echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\">";
			if(in_array("device",$col)){
				echo "<td>\n";
				if( !isset($_GET['print']) and strpos($_SESSION['group'],$modgroup['Devices-Status']) !== false ){
					echo "<a href=\"Devices-Status.php?dev=$ud\"><img src=\"img/16/sys.png\"></a>\n";
				}
				echo "<a href=?ina=device&opa==&sta=$ud&ord=vlanid>$v[0]</a></td>\n";
			}
			if(in_array("type",$col)){echo "<td><a href=?ina=type&opa==&sta=\"$v[5]\">$v[5]</a>";}
			if(in_array("location",$col)){echo "<td><a href=\"?ina=location&opa==&sta=".urlencode($v[12])."\">$v[12]</a></td>";}
			if(in_array("contact",$col)){echo "<td><a href=\"?ina=contact&opa==&sta=".urlencode($v[13])."\">$v[13]</a></td>";}
			if(in_array("vlanid",$col)){echo "<td><a href=?ina=vlanid&opa==&sta=$v[1]>$v[1]</a>";}
			if(in_array("vlanname",$col)){echo "<td><a href=?ina=vlanname&opa==&sta=".urlencode($v[2]).">$v[2]</a>";}
			if(in_array("pop",$col)){
				$nquery	= GenQuery('nodes','g','vlanid','','',array('device','vlanid'),array('=','='),array($v[0],$v[1]),array('AND') );
				$np  = @DbQuery($nquery,$link);
				$nnp = @DbNumRows($np);
				if ($nnp == 1) {
					$vpop = @DbFetchRow($np);
					$pbar = Bar($vpop[1],110);
					echo "<td>$pbar <a href=Nodes-List.php?ina=device&opa==&sta=$ud&inb=vlanid&opb==&stb=$v[1]&cop=AND>$vpop[1]</td>";
				}else{
					echo "<td></td>";
				}
				@DbFreeResult($np);
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
