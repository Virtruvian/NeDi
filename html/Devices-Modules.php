<?
# Program: Devices-Modules.php
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
	if($_SESSION['olic']){$_SESSION['modcol'] = $_GET['col'];}
}elseif( isset($_SESSION['modcol']) ){
	$col = $_SESSION['modcol'];
}else{
	$col = array('device','slot','model','moddesc','modules.serial');
}

$cols = array(	"device"=>"Device $namlbl",
		"type"=>"Device $typlbl",
		"location"=>$loclbl,
		"contact"=>$conlbl,
		"slot"=>"Slot",
		"model"=>$mdllbl,
		"moddesc"=>$deslbl,
		"modules.serial"=>$serlbl,
		"hw"=>"Hardware",
		"fw"=>"Firmware",
		"sw"=>"Software",
		"modidx"=>"Index"
		);

$link = @DbConnect($dbhost,$dbuser,$dbpass,$dbname);							# Above print-header!
$listalert = "";
if($listwarn){
	$cnr  = @DbFetchRow(DbQuery(GenQuery('modules','s','count(*)','','','','','','','LEFT JOIN devices USING (device)'), $link));
	if($cnr[0] > $listwarn){
		$listalert = "onclick=\"if(document.list.sta.value == ''){return confirm('".(($verb1)?"$sholbl $alllbl $cnr[0]":"$alllbl $cnr[0] $sholbl")."?');}\"";
	}
}
?>
<h1>Module <?=$lstlbl?></h1>

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
<select size=1 name="opa">
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
<select multiple name="col[]" size=4>
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
	?>
<table class="content"><tr class="<?=$modgroup[$self]?>2">
	<?
	foreach($col as $h){
		ColHead($h);
	}
	echo "</tr>\n";

	$query	= GenQuery('modules','s','modules.*,type,location,contact',$ord,'',array($ina,$inb),array($opa,$opb),array($sta,$stb),array($cop),'LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( ($m = @DbFetchRow($res)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$ud = rawurlencode($m[0]);
			echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\">";
			if(in_array("device",$col)){
				echo "<td nowrap>\n";
				if( !isset($_GET['print']) and strpos($_SESSION['group'],$modgroup['Devices-Status']) !== false ){
					echo "<a href=\"Devices-Status.php?dev=$ud\"><img src=\"img/16/sys.png\"></a>\n";
				}
				echo "<a href=?ina=device&opa==&sta=$ud>$m[0]</a></td>\n";
			}
			if(in_array("type",$col)){echo "<td><a href=?ina=type&opa==&sta=\"$m[9]\">$m[9]</a>";}
			if(in_array("location",$col)){echo "<td><a href=\"?ina=location&opa==&sta=".urlencode($m[10])."\">$m[10]</a></td>";}
			if(in_array("contact",$col)){echo "<td><a href=\"?ina=contact&opa==&sta=".urlencode($m[11])."\">$m[11]</a></td>";}
			if(in_array("slot",$col)){echo "<td><a href=?ina=slot&opa==&sta=".urlencode($m[1]).">$m[1]</a>";}
			if(in_array("model",$col)){echo "<td><a href=?ina=model&opa==&sta=".urlencode($m[2]).">$m[2]</a>";}
			if(in_array("moddesc",$col)){echo "<td>$m[3]</td>";}
			if(in_array("modules.serial",$col)){ echo "<td>$m[4]</td>";}
			if(in_array("hw",$col)){echo "<td><a href=?ina=hw&opa==&sta=".urlencode($m[5]).">$m[5]</a>";}
			if(in_array("fw",$col)){echo "<td><a href=?ina=fw&opa==&sta=".urlencode($m[6]).">$m[6]</a>";}
			if(in_array("sw",$col)){echo "<td><a href=?ina=sw&opa==&sta=".urlencode($m[7]).">$m[7]</a>";}
			if(in_array("modidx",$col)){echo "<td><a href=?ina=modidx&opa==&sta=".urlencode($m[8]).">$m[8]</a>";}
			echo "</tr>\n";
		}
		@DbFreeResult($res);
	}else{
		print @DbError($link);
	}
	?>
</table>
<table class="content">
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Modules<?=($ord)?", $srtlbl: $ord":""?></td></tr>
</table>
	<?
}
include_once ("inc/footer.php");
?>
