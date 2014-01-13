<?
# Program: Monitoring-Events.php
# Programmer: Remo Rickli

$refresh   = 60;
$calendar  = 1;
$printable = 1;

include_once ("inc/header.php");
include_once ("inc/libmon.php");

$_GET = sanitize($_GET);
$sta = isset($_GET['sta']) ? $_GET['sta'] : "";
$stb = isset($_GET['stb']) ? $_GET['stb'] : "";
$ina = isset($_GET['ina']) ? $_GET['ina'] : "";
$inb = isset($_GET['inb']) ? $_GET['inb'] : "";
$opa = isset($_GET['opa']) ? $_GET['opa'] : "";
$opb = isset($_GET['opb']) ? $_GET['opb'] : "";
$cop = isset($_GET['cop']) ? $_GET['cop'] : "";
$lvl = isset($_GET['lvl']) ? $_GET['lvl'] : "";
$lim = isset($_GET['lim']) ? $_GET['lim'] : 10;
$off = (isset($_GET['off']) and !isset($_GET['sho']))? $_GET['off'] : 0;

if($lvl){
	$in[] = 'level';
	$op[] = '=';
	$st[] = $lvl;
	if($sta or $cop){$co[] = 'AND';}
}
$in[] = $ina;
$in[] = $inb;
$op[] = $opa;
$op[] = $opb;
$st[] = $sta;
$st[] = $stb;
$co[] = $cop;

$nof = $off;
if( isset($_GET['p']) ){
	$nof = abs($off - $lim);
}elseif( isset($_GET['n']) ){
	$nof = $off + $lim;
}
$dlim = ($lim)?"$nof,$lim":0;

$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
if( isset($_GET['del']) ){
	if($isadmin){
		$query	= GenQuery('events','d','*','id desc',$lim,$in,$op,$st,$co );
		if(@DbQuery($query,$link) ){
			echo "<h5> $msglbl $dellbl OK </h5>";
		}else{
			echo "<h4>".DbError($link)."</h4>";
		}
	}else{
		echo $nokmsg;
	}
}

$cols = array(	"info"=>"Info",
		"id"=>"ID",
		"level"=>"$levlbl",
		"time"=>$timlbl,
		"source"=>$srclbl,
		"class"=>$clalbl,
		"location"=>$loclbl,
		"contact"=>$conlbl

		);

?>
<h1>Monitoring <?=$msglbl?></h1>

<?if( !isset($_GET['print']) ){?>

<form method="get" name="dynfrm" action="<?=$self?>.php">
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
<p><a href="javascript:show_calendar('dynfrm.sta');"><img src="img/16/date.png"></a>
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
<p><a href="javascript:show_calendar('dynfrm.stb');"><img src="img/16/date.png"></a>
<input type="text" name="stb" value="<?=$stb?>" size="20">
</th>
<th valign="top"><?=$levlbl?><p>
<select size="1" name="lvl">
<option VALUE=""><?=$sellbl?> ->
<?
foreach (array_keys($mlvl) as $ml){
	echo "<option value=\"$ml\" ";
	if($ml == $lvl){echo "selected";}
	echo ">$mlvl[$ml]\n";
}
?>
</select>
<p>
<a href="?ina=class&opa=like&sta=trf%&lim=<?=$lim?>"><img src="img/16/grph.png" title=" <?=$trflbl?>"></a>
<a href="?ina=class&opa=like&sta=cfg%&lim=<?=$lim?>"><img src="img/16/conf.png" title="<?=$cfglbl?>"></a>
<a href="?ina=class&opa==&sta=nedi&lim=<?=$lim?>"><img src="img/16/radr.png" title="<?=$dsclbl?>"></a>
<a href="?ina=class&opa==&sta=dev&lim=<?=$lim?>"><img src="img/16/dev.png" title="Device <?=$msglbl?>"></a>
<br>
<a href="?ina=class&opa==&sta=sec&lim=<?=$lim?>"><img src="img/16/hat.png" title="Security <?=$msglbl?>"></a>
<a href="?ina=class&opa=like&sta=usr%&lim=<?=$lim?>"><img src="img/16/user.png" title="<?=$usrlbl?> <?=$msglbl?>"></a>
<a href="?ina=class&opa==&sta=moni&lim=<?=$lim?>"><img src="img/16/bino.png" title="Monitoring"></a>
<a href="?ina=class&opa==&sta=node&lim=<?=$lim?>"><img src="img/16/node.png" title="Node <?=$msglbl?>"></a>
</th>
<th valign="top"><?=$limlbl?><p>
<select size="1" name="lim">
<? selectbox("limit",$lim);?>
</select>

<br><p>
<span id="counter"><?=$refresh?></span>
<img src="img/16/exit.png" title="Stop" onClick="stop_countdown(interval);">
</th>

<th width="80">
<input type="submit" name="sho" value="<?=$sholbl?>">
<p>
<input type="hidden" name="off" value="<?=$nof?>">
<input type="submit" name="p" value=" < ">
<input type="submit" name="n" value=" > ">
<p>

<input type="submit" name="del" value="<?=$dellbl?>" onclick="return confirm('<?=$dellbl?>, <?=$cfmmsg?>')" >
</th></tr>
</table></form>
<p>
<?
}
ConHead($ina, $opa, $sta, $cop, $inb, $opb, $stb);

Events($dlim,$in,$op,$st,$co);

include_once ("inc/footer.php");
?>
