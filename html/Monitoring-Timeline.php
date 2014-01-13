<?
# Program: Monitoring-Timeline.php
# Programmer: Remo Rickli

$calendar  = 1;
$printable = 1;

include_once ("inc/header.php");
include_once ("inc/libmon.php");

$_GET = sanitize($_GET);
$ina = isset($_GET['ina']) ? $_GET['ina'] : "";
$opa = isset($_GET['opa']) ? $_GET['opa'] : "";
$sta = (isset($_GET['sta']) && $ina != "") ? $_GET['sta'] : "";

$srt = isset($_GET['srt']) ? $_GET['srt'] : date('m/d/Y') . " 0:00";
$end = isset($_GET['end']) ? $_GET['end'] : date('m/d/Y H:i:s');
$gra = isset($_GET['gra']) ? $_GET['gra'] : 3600;
$det = isset($_GET['det']) ? $_GET['det'] : "";
$bsz = isset($_GET['bsz']) ? $_GET['bsz'] : "si";

$cols = array(	"info"=>"Info",
		"id"=>"ID",
		"level"=>"$levlbl",
		"source"=>$srclbl,
		"class"=>$clalbl,
		"type"=>"Device $typlbl",
		"devos"=>"Device OS",
		"bootimage"=>"Bootimage",
		"location"=>$loclbl,
		"contact"=>$conlbl,
		"vtpdomain"=>"VTP Domain",
		);

?>
<h1><?=$msglbl?> Timeline</h1>

<?if( !isset($_GET['print']) ){?>

<form method="get" name="dynfrm" action="<?=$self?>.php">
<table class="content"><tr class="<?=$modgroup[$self]?>1">
<th width="50"><a href="<?=$self?>.php"><img src="img/32/<?=$selfi?>.png"></a></th>

<th valign="top">

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
<input type="text" name="sta" value="<?=$sta?>" size="20">

</th>
<td>

<img src="img/16/eyes.png" title="<?=$sholbl?> <?=$grplbl?>">
<select size="1" name="det">
<option value=""><?=$nonlbl?>
<option value="level" <?=($det == "level")?"selected":""?>><?=$levlbl?>
<option value="source" <?=($det == "source")?"selected":""?>><?=$srclbl?>
<option value="class" <?=($det == "class")?"selected":""?>><?=$clalbl?>
</select>
<img src="img/16/abc.png" title="<?=$typlbl?>">
<select size="1" name="bsz">
<option value="si"><?=(($verb1)?"$siz[s] $imglbl":"$imglbl $siz[s]")?>
<option value="mi" <?=($bsz == "mi")?"selected":""?>><?=(($verb1)?"$siz[m] $imglbl":"$imglbl $siz[m]")?>
<option value="ms" <?=($bsz == "ms")?"selected":""?>><?=(($verb1)?"$siz[m] $shplbl":"$shplbl $siz[m]")?>
<option value="li" <?=($bsz == "li")?"selected":""?>><?=(($verb1)?"$siz[l] $imglbl":"$imglbl $siz[l]")?>
</select>
<p>
<img src="img/16/clock.png" title="<?=$timlbl?> <?=$sizlbl?>">
<select size="1" name="gra">
<option value="3600"><?=$tim['h']?>
<option value="86400" <?=($gra == "86400")?"selected":""?>><?=$tim['d']?>
<option value="604800" <?=($gra == "604800")?"selected":""?>><?=$tim['w']?>
<option value="2592000" <?=($gra == "2592000")?"selected":""?>><?=$tim['m']?>
</select>

</td>
<th>

<img src="img/16/blft.png" title="<?=$sttlbl?>"><a href="javascript:show_calendar('dynfrm.srt');">
<img src="img/16/date.png"></a><input type=text name="srt" value="<?=$srt?>" size="15">
<p>
</p><img src="img/16/brgt.png" title="<?=$endlbl?>"><a href="javascript:show_calendar('dynfrm.end');">
<img src="img/16/date.png"></a><input type=text name="end" value="<?=$end?>" size="15">
</th>

<th width="80">
<input type="submit" name="tml" value="<?=$sholbl?>">
</th>
</tr>
</table></form><p>
<?}?>

<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th width="80"><img src="img/32/clock.png"><br><?=$timlbl?></th>
<th><img src="img/32/bell.png"><br><?=$msglbl?></th>
</tr>

<?
ConHead($ina, $opa, $sta);

$from	= strtotime($srt);
$to	= strtotime($end);
$istart	= $from;
$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
$tmsg = 0;
$row = 0;
while($istart < $to){
	$iend = $istart + $gra;
	if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
	$row++;
	$fs   = urlencode(date("m/d/Y H:i:s",$istart));
	$fe   = urlencode(date("m/d/Y H:i:s",$iend));
	echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\"><th class=\"$bi\" nowrap>\n";
	echo "<a href=\"Monitoring-Events.php?ina=time&opa=%3E=&sta=$fs&cop=AND&inb=time&opb=%3C&stb=$fe&lim=0\">".date("j.M G:i",$istart)."</a></th><td>\n";
	if($det){
		$query	= GenQuery('events','g',"$det;icon",'','',array('time','time',$ina),array('>=','<',$opa),array($istart,$iend,$sta),array('AND','AND'),'LEFT JOIN devices USING (device)');
		$res	= @DbQuery($query,$link);
		if($res){
			$nmsg = 0;
			while( $m = @DbFetchRow($res) ){
				$mbar = Bar($m[1],"lvl$m[0]",$bsz,$m[0]);
				if($det == 'level'){
					if($bsz == 'ms'){
						echo "<a href=\"Monitoring-Events.php?ina=time&opa=%3E=&sta=$fs&cop=AND&inb=time&opb=%3C&stb=$fe&lvl=$m[0]&lim=0\">$mbar</a>\n";
					}else{
						echo "<a href=\"Monitoring-Events.php?ina=time&opa=%3E=&sta=$fs&cop=AND&inb=time&opb=%3C&stb=$fe&lvl=$m[0]&lim=0\">";
						echo "<img src=\"img/16/" . $mico[$m[0]] . ".png\" title=\"$m[1] " . $mlvl[$m[0]] . "\"></a>$mbar\n";
					}
				}else{
					$um = urlencode($m[0]);
					if($bsz == 'ms'){
						echo "<a href=\"Monitoring-Events.php?ina=$det&opa==&sta=$um&cop=AND&inb=time&opb=%3C&stb=$fe\">$mbar</a>\n";
					}else{
						if($det == 'source'){
							$icon = "<img src=\"img/".(($m[2])?"dev/$m[2]":"16/say").".png\" width=\"".(($bsz == 'li')?"24":"16")."\" title=\"$m[0]: $m[1]\">";
						}else{
							list($ei,$et) = EvClass($m[0]);
							$icon = "<img src=\"img/16/$ei.png\" title=\"$et, $m[1]\">";
						}
						echo "<a href=\"Monitoring-Events.php?ina=$det&opa==&sta=$um&cop=AND&inb=time&opb=%3C&stb=$fe\">$icon</a>$mbar\n";
					}
				}
				$nmsg += $m[1];
			}
			if($nmsg){
				echo "&nbsp;$nmsg $totlbl";
			}
			echo "</td></tr>\n";
			@DbFreeResult($res);
		}else{
			print @DbError($link);
		}
	}else{
		$query	= GenQuery('events','s','count(*)','','',array('time','time',$ina),array('>=','<',$opa),array($istart,$iend,$sta),array('AND','AND'),'LEFT JOIN devices USING (device)');
		$res	= @DbQuery($query,$link);
		if($res){
			$m = @DbFetchRow($res);
			if($m[0]){
				$mbar = Bar($m[0],0,$bsz);
				echo "<img src=\"img/16/fogy.png\" title=\"All Events\">$mbar $m[0]";
			}
			echo " </td></tr>\n";
			$tmsg += $m[0];
			@DbFreeResult($res);
		}else{
			print @DbError($link);
		}
	}
	$istart = $iend;
	flush();
}
	?>
</table>
<table class="content">
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$vallbl?>, <?=$tmsg?> <?=$msglbl?></td></tr>
</table>
	<?

include_once ("inc/footer.php");
?>
