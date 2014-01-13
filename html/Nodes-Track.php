<?
# Program: Nodes-Track.php
# Programmer: Remo Rickli

# load data local infile '/home/rickli/Downloads/switchprot_delta_report2010-08-18 21-10-33.csv' into table nodetrack fields terminated by ',' enclosed by '"' lines terminated by '\n' IGNORE 1 LINES (device,Ifname,Realname,alias,Name,@dip,preferred,manual,switchport,finalname) SET IP = INET_ATON(@dip)

error_reporting(E_ALL ^ E_NOTICE);

$calendar  = 1;
$printable = 1;

include_once ("inc/header.php");
include_once ("inc/libdev.php");
include_once ("inc/libmon.php");
include_once ("inc/libnod.php");

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
	if($_SESSION['olic']){$_SESSION['ntrcol'] = $col;}
}elseif( isset($_SESSION['ntrcol']) ){
	$col = $_SESSION['ntrcol'];
}else{
	$col = array('nodetrack.device','nodetrack.ifname','value','source','alias','name');
}

$del = isset($_GET['del']) ? $_GET['del'] : "";

$dev = isset($_GET['dev']) ? $_GET['dev'] : "";
$ifn = isset($_GET['ifn']) ? $_GET['ifn'] : "";
$val = isset($_GET['val']) ? $_GET['val'] : "";
$src = isset($_GET['src']) ? $_GET['src'] : "";

$cols = array(	"nodetrack.device"=>"Device",
		"nodetrack.ifname"=>"IF $namlbl",
		"value"=>"$vallbl",
		"source"=>$srclbl,
		"alias"=>"IF Alias",
		"comment"=>"IF $cmtlbl",
		"name"=>$namlbl,
		"nodes.mac"=>"MAC $adrlbl",
		"nodes.vlanid"=>"Vlan",
		"oui"=>"OUI $venlbl",
		"user"=>$usrlbl,
		"time"=>$timlbl
		);

?>
<h1>Node Tracker</h1>

<?if( !isset($_GET['print']) ){?>

<form method="get" action="<?=$self?>.php" name="track">
<table class="content"><tr class="<?=$modgroup[$self]?>1">
<th width="50">
<a href="<?=$self?>.php"><img src="img/32/<?=$selfi?>.png"></a>

</th>
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
<p><a href="javascript:show_calendar('track.sta');"><img src="img/16/date.png"></a>
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
<p><a href="javascript:show_calendar('track.stb');"><img src="img/16/date.png"></a>
<input type="text" name="stb" value="<?=$stb?>" size="20">
</th>
 
 <th valign="top"><?=$dislbl?><p>
<select multiple name="col[]" size="4">
<?
foreach ($cols as $k => $v){
       echo "<option value=\"$k\"".((in_array($k,$col))?"selected":"").">$v\n";
}
?>
<option value="cfg" <?=(in_array("cfg",$col))?"selected":""?> ><?=$cfglbl?>
</select>
</th>

</th>
<th width="80">

<input type="submit" value="<?=$sholbl?>">
<p>
<input type="submit" name="del" value="<?=$dellbl?>" onclick="return confirm('Tracker <?=$dellbl?>?')" >

</th>
</tr></table></form><p>
<?
}
$link = @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
if($del){
	$query	= GenQuery('nodetrack','d','','','',array($ina),array($opa),array($sta) );
	if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$dellbl $ina $opa $sta OK</h5>";}
}

if( isset($_GET['ina']) ){
ConHead($ina, $opa, $sta, $cop, $inb, $opb, $stb);

?>

<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th width="20"></th>
<?
	foreach($col as $h){
		if($h != 'cfg'){
			ColHead($h);
		}
	}
	$shocol = $col;
	if( in_array("cfg",$col) )	{
		echo "<th>$cfglbl</th>";
		array_pop($shocol);
	}
?>
</tr>
<?
	$query	= GenQuery('nodetrack','s','nodetrack.device as device,nodetrack.ifname as ifname,value,source,alias,comment,name,nodes.mac as mac,oui,nodes.vlanid as vlanid,user,time',$ord,'',array($ina,$inb),array($opa,$opb),array($sta,$stb),array($cop), 'JOIN interfaces USING (device,ifname) LEFT JOIN nodes USING (device,ifname)');
	$res	= @DbQuery($query,$link);
	if($res){
		$usta = urlencode($sta);
		$uopa = urlencode($opa);
		$row = 0;
		while( ($trk = @DbFetchArray($res)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$cfgst	= "";
			list($cc,$lc) = Agecol($trk['time'],$trk['time'],$row % 2);

			if($dev and $dev ==  $trk['device'] and $ifn and $ifn == $trk['ifname']){
				$time = time();
				if($src){
					if($src == '-'){
						$setd = '';
					}elseif($src == 'comment'){
						$trk[$src] = preg_replace('/.+DP:(.+),.+/','$1',$trk[$src]);
						$setd = "value=\"$trk[$src]\",";
					}else{
						$setd = "value=\"$trk[$src]\",";
					}
					if( !@DbQuery("UPDATE nodetrack SET ${setd}source=\"$src\",user=\"$_SESSION[user]\",time=\"$time\" WHERE device = \"$dev\" AND Ifname = \"$ifn\";",$link) ){
						$cfgst = "<img src=\"img/16/bcnl.png\" title=\"" .DbError($link)."\">";
					}else{
						$cfgst = "<img src=\"img/16/bchk.png\" title=\"$srclbl = $src OK\">";
						$trk['source'] = $src;
						if($src != '-'){
							$trk['value'] = $trk[$src];
						}
					}
				}elseif($val){
					if( !@DbQuery("UPDATE nodetrack SET value=\"$val\",user=\"$_SESSION[user]\",time=\"$time\" WHERE device = \"$dev\" AND Ifname = \"$ifn\";",$link) ){
						$cfgst = "<img src=\"img/16/bcnl.png\" title=\"" .DbError($link)."\">";
					}else{
						$cfgst = "<img src=\"img/16/bchk.png\" title=\"$vallbl = $val OK\">";
						$trk['value'] = $val;
					}
				}
				$trk['time'] = $time;
				$trk['user'] = $_SESSION['user'];
			}
			$bst = 'good';
			if($trk['source'] == '-' or $trk['source'] == ''){
				$bst = $bi;
			}elseif($trk['source'] == 'comment'){
				if($trk['value'] != preg_replace('/.+DP:(.+),.+/','$1',$trk['comment']) ){$bst = 'warn';}
			}else{
				if($trk['value'] != $trk[$trk['source']]){$bst = 'warn';}
			}
			echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\"><th class=\"$bst\">\n";
			if($trk['mac']){
				$img = Nimg("$trk[mac];$trk[oui]");
?>
<a href="Nodes-Status.php?mac=<?=$trk['mac']?>&vid=<?=$trk['vlanid']?>"><img src="img/oui/<?=$img?>.png" title=""<?=$trk['mac']?> (<?=$trk['oui']?>)"></a>
</th>
<?
			}else{
				echo "<img src=\"img/p45.png\">";
			}
			foreach ($shocol as $c){
				if( $p = strpos($c,".") ){$c = substr($c,$p+1);}
				if($c == 'value'){
					echo "<td class=\"blu\"><b>$trk[$c]</b></td>";
				}elseif($c == 'device'){
					echo "<td nowrap>\n";
					if( !isset($_GET['print']) and strpos($_SESSION['group'],$modgroup['Devices-Status']) !== false ){
						echo "<a href=\"Devices-Status.php?dev=".urlencode($trk[$c])."\"><img src=\"img/16/sys.png\"></a>\n";
					}
					echo "<a href=\"?ina=nodetrack.device&opa==&sta=".urlencode($trk[$c])."\">$trk[$c]</a></td>";
				}elseif($c == $trk['source']){
					echo "<td class=\"blu\">$trk[$c]</td>";
				}elseif($c == "time"){
					echo "<td bgcolor=\"#$cc\">".date($datfmt, $trk[$c])."</td>";
				}else{
					echo "<td>$trk[$c]</td>";
				}
			}
			if(in_array("cfg",$col)){
?>
<td>
<form method="get">
<input type="hidden" name="ina" value="<?=$ina?>">
<input type="hidden" name="opa" value="<?=$opa?>">
<input type="hidden" name="sta" value="<?=$sta?>">
<input type="hidden" name="cop" value="<?=$cop?>">
<input type="hidden" name="inb" value="<?=$inb?>">
<input type="hidden" name="opb" value="<?=$opb?>">
<input type="hidden" name="stb" value="<?=$stb?>">

<input type="hidden" name="dev" value="<?=$trk['device']?>">
<input type="hidden" name="ifn" value="<?=$trk['ifname']?>">
<input type="text" name="val" size="15" value="<?=$trk['value']?>" onfocus="select();"  onchange="this.form.submit();" title="<?=$wrtlbl?> <?=$namlbl?>">
<SELECT size="1" name="src" onchange="this.form.submit();" title="<?=$namlbl?> <?=$srclbl?>">
<OPTION VALUE=""><?=$sellbl?>
<OPTION VALUE="-">-
<OPTION VALUE="name"><?=$namlbl?>
<OPTION VALUE="mac">MAC <?=$adrlbl?>
<OPTION VALUE="alias">IF Alias
<OPTION VALUE="comment">IF <?=$cmtlbl?>
</select> <?=$cfgst?>
</form>
</td>
<?
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
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$vallbl?></td></tr>
</table>
<?
}
include_once ("inc/footer.php");
?>
