<?
# Program: Reports-Modules.php
# Programmer: Remo Rickli (and contributors) 

error_reporting(E_ALL ^ E_NOTICE);

$calendar  = 1;
$printable = 1;

include_once ("inc/header.php");
include_once ("inc/libdev.php");
include_once ("inc/librep.php");

$_GET = sanitize($_GET);
$rep = isset($_GET['rep']) ? $_GET['rep'] : array();
$flt = isset($_GET['flt']) ? $_GET['flt'] : "";
$ord = isset($_GET['ord']) ? "checked" : "";
$ina = isset($_GET['ina']) ? $_GET['ina'] : "";
$opa = isset($_GET['opa']) ? $_GET['opa'] : "";
$sta = (isset($_GET['sta']) && $ina != "") ? $_GET['sta'] : "";

$cols = array(	"device"=>"Device $namlbl",
		"devip"=>"IP $adrlbl",
		"type"=>"Device $typlbl",
		"firstdis"=>"$fislbl $dsclbl",
		"lastdis"=>"$laslbl $dsclbl",
		"vtpdomain"=>"VTP Domain",
		"location"=>$loclbl,
		"contact"=>$conlbl,
		"model"=>"Module $typlbl",
		"moddesc"=>"Module $deslbl"
		);

?>
<h1>Module Reports</h1>
<?
if( !isset($_GET['print']) ){
?>

<form method="get" name="report" action="<?=$self?>.php">
<table class="content"><tr class="<?=$modgroup[$self]?>1">
<th width="50"><a href="<?=$self?>.php"><img src="img/32/<?=$selfi?>.png"></a></th>
<th>

<select size="1" name="ina">
<option value=""><?=$fltlbl?>->
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
<a href="javascript:show_calendar('report.sta');"><img src="img/16/date.png"></a>
<input type="text" name="sta" value="<?=$sta?>" size="20">

</th>
<th>
<select multiple name="rep[]" size="4">
<option value="sum" <?=(in_array("sum",$rep))?"selected":""?> ><?=$dislbl?>
<option value="inv" <?=(in_array("inv",$rep))?"selected":""?> ><?=$invlbl?>
<option value="prt" <? if(in_array("prt",$rep)){echo "selected";} ?> >Print Supplies
<option value="vms" <? if(in_array("vms",$rep)){echo "selected";} ?> >Virtual Machines
<?
$tquery = GenQuery("cisco_contracts", "t");					# Not printable yet :-(
$res    = DbQuery($tquery, $link);
if( DbFetchRow($res) ){								# Show item only, if cisco_contracts table exists
?>
<OPTION VALUE="ves" <?=(in_array("ves",$rep))?"selected":""?> ><?=$wtylbl?> <?=$stalbl?>
<?}?>
</SELECT>

</th>
<th align="left">

<input type="checkbox" name="ord" <?=$ord?>> <?=$altlbl?> <?=$srtlbl?><br>

</th>
<th width="80"><input type="submit" name="do" value="<?=$sholbl?>"></th>
</tr></table></form><p>
	
<?
}
if($rep){
	ConHead($ina, $opa, $sta, $cop, $inb, $opb, $stb);

	$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
	if ( in_array("sum",$rep) ){
		ModDist($ina,$opa,$sta,$lim,$ord);
	}

	if ( in_array("inv",$rep) ){
		ModInventory($ina,$opa,$sta,$lim,$ord);
	}

	if ( in_array("prt",$rep) ){
		ModPrint($ina,$opa,$sta,$lim,$ord);
	}

	if ( in_array("vms",$rep) ){
		ModVM($ina,$opa,$sta,$lim,$ord);
	}
}

// added for Cisco contract check by Andreas Wassatsch
if ( in_array("ves",$rep) ){
?>
<h2><?=$wtylbl?> <?=$stalbl?></h2>
<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th colspan="2"><img src="img/16//dev.png"><br>Device / Slot</th>
<th><img src="img/16//find.png"><br>Info</th>
<th><img src="img/16//form.png"><br><?=$serlbl?></th>
<th><img src="img/16//idea.png"><br><?=$stalbl?></th>
</tr>
<?
	if($ord){
		$sort = "type";
	}else{
		$sort = "name";
	}
	$query	= GenQuery('devices','s','name,type,serial,devos,bootimage',$sort,'',array('type'),array('regexp'),array($flt) );
	$res	= @DbQuery($query,$link);

	$link_ccc = @DbConnect($dbhost,$dbuser,$dbpass,$dbname);

	if($res){
		$dev = 0;
		$row = 0;
		while( $d = @DbFetchRow($res) ){
			$dev++;
			$ud = rawurlencode($d[0]);
			echo "<tr class=\"imgb\" class=\"blu\"><th>\n";
			echo "<a href=\"Devices-Status.php?dev=$ud\"><b>$d[0]</b></a></th>\n";

	//$query_ccc = GenQuery('cisco_contracts','s','service_level,contract_number,end_date,DATEDIFF(STR_TO_DATE(end_date, '%d-%b-%Y'),CURDATE())','','',array('serial'),array('='),array($d[2]));
	$query_ccc = "SELECT service_level,contract_number,end_date,DATEDIFF(STR_TO_DATE(end_date, '%d-%b-%Y'),CURDATE()) FROM cisco_contracts WHERE serial_number=\"$d[2]\"";
	$res_ccc = @DbQuery($query_ccc,$link_ccc);
	$ccc = @DbFetchRow($res_ccc);
	if ($ccc[3] > 30) {
		$color = "green";
		$ccc_message = "$ccc[0]<br>Contract #$ccc[1] valid till $ccc[2]";
	} elseif ($ccc[3] > 1) {
		$color = "darkyellow";
		$ccc_message = "$ccc[0]<br>Contract #$ccc[1] valid till $ccc[2]";
	} else {
		$color = "red";
		$ccc_message = "$ccc[0]<br>Contract #$ccc[1] expired since $ccc[2] !";
	}
	if ($ccc[1] == "") {
		$color = "blue";
		$ccc_message = "Unknown";
	}
			echo "<td align=right>-</td><td><b>$d[1]</b></td><td>$d[2]</td><td><font color=$color>$ccc_message</font></td></tr>\n";

			$mquery	= GenQuery('modules','s','*','slot','',array('device'),array('='),array($d[0]));
			$mres	= @DbQuery($mquery,$link);
			if($mres){
				while( ($m = @DbFetchRow($mres)) ){
					if ($row % 2){$bg = "txta";}else{$bg = "txtb";}
					$row++;
					echo "<tr class=\"$bg\"><th>\n";
					$query_ccc = "SELECT service_level,contract_number,end_date,DATEDIFF(STR_TO_DATE(end_date, '%d-%b-%Y'),CURDATE()) FROM cisco_contracts WHERE serial_number=\"$m[2]\"";
					$res_ccc = @DbQuery($query_ccc,$link_ccc);
					$ccc = @DbFetchRow($res_ccc);
					if ($ccc[3] > 30) {
						$color = "green";
						$ccc_message = "$ccc[0]<br>Contract #$ccc[1] valid till $ccc[2]";
					} elseif ($ccc[3] > 1) {
						$color = "darkyellow";
						$ccc_message = "$ccc[0]<br>Contract #$ccc[1] valid till $ccc[2]";
					} else {
						$color = "red";
						$ccc_message = "$ccc[0]<br>Contract #$ccc[1] expired since $ccc[2]";
					}
					if ($ccc[1] == "") {
						$color = "blue";
						$ccc_message = "Unknown";
					}
					echo "<td align=right>$m[1]</td><td><b>$m[2]</b> $m[3]</td><td>$m[4]</td><td><font color=$color>$ccc_message</font></td></tr>\n";
				}
				@DbFreeResult($mres);
			}else{
				print @DbError($link);
				die;
			}
		}
		@DbFreeResult($res);
	}else{
		print @DbError($link);
		die;
	}
	?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$dev?> devices, <?=$row?> modules</td></tr>
</table>
	<?
}

include_once ("inc/footer.php");
?>
