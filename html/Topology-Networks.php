<?
# Program: Topology-Networks.php
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
	if($_SESSION['olic']){$_SESSION['netcol'] = $col;}
}elseif( isset($_SESSION['netcol']) ){
	$col = $_SESSION['netcol'];
}else{
	$col = array('device','ifname','ifip','vrfname');
}

$cols = array(	"device"=>"Device $namlbl",
		"type"=>"Device $typlbl",
		"location"=>$loclbl,
		"contact"=>$conlbl,
		"ifip"=>"IP $adrlbl",
		"mask"=>$msklbl,
		"ifname"=>"IF $namlbl",
		"vrfname"=>"VRF $namlbl",
		"status"=>$stalbl
		);

?>
<h1><?=$netlbl?> <?=$lstlbl?></h1>

<?if( !isset($_GET['print']) ){?>

<form method="get" name="list" action="<?=$self?>.php">
<table class="content"><tr class="<?=$modgroup[$self]?>1">
<th width="50"><a href="<?=$self?>.php"><img src="img/32/<?=$selfi?>.png"></a></th>
<th valign="top"><?=$cndlbl?> A<p>
<SELECT size="1" name="ina">
<?
foreach ($cols as $k => $v){
       echo "<option value=\"$k\"".( ($ina == $k)?"selected":"").">$v\n";
}
?>
</SELECT>
<SELECT size="1" name="opa">
<? selectbox("oper",$opa);?>
</SELECT>
<p>
<input type="text" name="sta" value="<?=$sta?>" size="20">
</th>
<th valign="top"><?=$cmblbl?><p>
<SELECT size="1" name="cop">
<? selectbox("comop",$cop);?>
</SELECT>
</th>
<th valign="top"><?=$cndlbl?> B<p>
<SELECT size="1" name="inb">
<?
foreach ($cols as $k => $v){
       echo "<option value=\"$k\"".( ($inb == $k)?"selected":"").">$v\n";
}
?>
</SELECT>
<SELECT size="1" name="opb">
<? selectbox("oper",$opb);?>
</SELECT>
<p>
<input type="text" name="stb" value="<?=$stb?>" size="20">
</th>
<th valign="top"><?=$collbl?><p>
<SELECT MULTIPLE name="col[]" size=4>
<?
foreach ($cols as $k => $v){
       echo "<option value=\"$k\"".((in_array($k,$col))?"selected":"").">$v\n";
}
?>
</SELECT>
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
	echo "<td></td>\n";
	foreach($col as $h){
		ColHead($h);
	}
	echo "</tr>\n";

	$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
	$query	= GenQuery('networks','s','networks.*,type,location,contact',$ord,'',array($ina,$inb),array($opa,$opb),array($sta,$stb),array($cop),'LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( ($m = @DbFetchRow($res)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$ip  = long2ip($m[2]);
			$msk = long2ip($m[3]);
			$ud  = rawurlencode($m[0]);
			list($ntimg,$ntit) = Nettype($m[2]);
			echo "<tr class=\"$bg\" onmouseover=\"this.className='$bi'\" onmouseout=\"this.className='$bg'\">";
			echo "<th class=\"$bi\" width=20><img src=\"img/$ntimg\" title=$ntit></th>\n";
			if(in_array("device",$col)){
				echo "<td nowrap>\n";
				if( !isset($_GET['print']) and strpos($_SESSION['group'],$modgroup['Devices-Status']) !== false ){
					echo "<a href=\"Devices-Status.php?dev=$ud\"><img src=\"img/16/sys.png\"></a>\n";
				}
				echo "<a href=\"?ina=device&opa==&sta=$ud\">$m[0]</a></td>\n";
			}
			if(in_array("type",$col)){echo "<td><a href=?ina=type&opa==&sta=\"$m[6]\">$m[6]</a>";}
			if(in_array("location",$col)){echo "<td><a href=\"?ina=location&opa==&sta=".urlencode($m[7])."\">$m[7]</a></td>";}
			if(in_array("contact",$col)){echo "<td><a href=\"?ina=contact&opa==&sta=".urlencode($m[8])."\">$m[8]</a></td>";}
			if(in_array("ifip",$col)){
				echo "<td><a href=\"?ina=ifip&opa==&sta=$ip\">$ip</a>";
				if( !isset($_GET['print']) ){
					echo "<a href=\"Nodes-Toolbox.php?Dest=$ip\"><img src=\"img/16/dril.png\" align=\"right\"></a>\n";
				}
				echo "</td>";
			}
			if(in_array("mask",$col)){echo "<td>$msk</td>";}
			if(in_array("ifname",$col)){echo "<td>$m[1]</td>";}
			if(in_array("vrfname",$col)){ echo "<td><a href=\"?ina=vrfname&opa==&sta=".urlencode($m[4])."\">$m[4]</a></td>";}
			if(in_array("status",$col)){ echo "<td>$m[5]</td>";}
			echo "</tr>\n";
		}
		@DbFreeResult($res);
	}else{
		print @DbError($link);
	}
	?>
</table>
<table class="content">
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$vallbl?></td></tr>
</table>
	<?
}
include_once ("inc/footer.php");
?>
