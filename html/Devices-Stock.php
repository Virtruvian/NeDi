<?
# Program: Devices-Stock.php
# Programmer: Remo Rickli

$printable = 1;
$calendar  = 1;

$cico['10']  = "star";
$cico['100'] = "flas";
$cico['150'] = "warn";
$cico['200'] = "bstp";

include_once ("inc/header.php");
include_once ("inc/libdev.php");
$_GET = sanitize($_GET);
$chg = isset($_GET['chg']) ? $_GET['chg'] : "";
$add = isset($_GET['add']) ? $_GET['add'] : "";
$upd = isset($_GET['upd']) ? $_GET['upd'] : "";
$del = isset($_GET['del']) ? $_GET['del'] : "";

$lst = isset($_GET['lst']) ? $_GET['lst'] : "";
$val = isset($_GET['val']) ? $_GET['val'] : "";

$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
if($chg){
	$query	= GenQuery('stock','s','*','','',array('serial'),array('='),array($chg) );
	$res	= @DbQuery($query,$link);
	$nitm	= @DbNumRows($res);
	if ($nitm != 1) {
		echo "<h4>$chg: $nitm $vallbl!</h4>";
		@DbFreeResult($res);
	}else{
		$item = @DbFetchRow($res);
	}
	@DbFreeResult($res);
	$ser   = $item[0];
	$typ   = $item[1];
	$loc   = $item[4];
	$sta   = $item[5];
	$com   = $item[6];
	$wty   = date("m/d/Y",$item[7]);
	$src   = $item[8];
	$oper  = "upd";
	$oplbl = "$updlbl";
}else{
	$ser = isset($_GET['ser']) ? $_GET['ser'] : "";
	$typ = isset($_GET['typ']) ? $_GET['typ'] : (($lst == 'ty') ? $val : "");
	$loc = isset($_GET['loc']) ? $_GET['loc'] : (($lst == 'lo') ? $val : "-");
	$sta = isset($_GET['sta']) ? $_GET['sta'] : (($lst == 'st') ? $val : 10);
	$com = isset($_GET['com']) ? $_GET['com'] : "";
	$wty = isset($_GET['wty']) ? $_GET['wty'] : (($lst == 'wy') ? $val : date("m/d/Y",time()+86400*365*3));
	$src = isset($_GET['src']) ? $_GET['src'] : (($lst == 'sc') ? $val : "-");
	$oper  = "add";
	$oplbl = "$addlbl";
}
$wint = strtotime( preg_replace("/\s.*$/", "", $wty) );					# Forget the hour on Warranty

?>
<h1>Stock <?=$mgtlbl?></h1>

<?if( !isset($_GET['print']) ){?>

<form method="get" action="<?=$self?>.php" name="add">
<table class="content"><tr class="<?=$modgroup[$self]?>1">
<th width="50"><a href="<?=$self?>.php"><img src="img/32/<?=$selfi?>.png"></a></th>

<th align="right">
<?=$serlbl?> <input type="text" autofocus name="ser" value="<?=$ser?>" size="20" OnFocus="select();" <?=(($chg)?"readonly":"")?>><br>
<?=$typlbl?> <input type="text" name="typ" value="<?=$typ?>" size="20" OnFocus="select();"><br>
<?=$loclbl?> <input type="text" name="loc" value="<?=$loc?>" size="20" OnFocus="select();"><br>
<?=$srclbl?> <input type="text" name="src" value="<?=$src?>" size="20" OnFocus="select();"><br>
<?=$wtylbl?> <a href="javascript:show_calendar('add.wty');"><img src="img/16/date.png"></a>
<input type="text" name="wty" value="<?=$wty?>" size="16" OnFocus="select();">
</th>
<th valign="top">
<?=$cmtlbl?><br>
<textarea rows="5" name="com" cols="30"><?=$com?></textarea>

</th>
<th valign="top">
<?=$stalbl?><br>
<select size="4" name="sta">
<?
foreach (array_keys($stco) as $c){
	echo "<option value=\"$c\" ".( ($c == $sta)?"selected":"").">$stco[$c]\n";
}
?>
</select>
</th>
<th width="80">
<input type="hidden" value="<?=$lst?>" name="lst">
<input type="hidden" value="<?=$val?>" name="val">
<input type="submit" value="<?=$oplbl?>" name="<?=$oper?>">
</th></tr>
</table></form>
<script type="text/javascript">
<?
if($oper  == "add"){
	echo "document.add.ser.focus();\n";
}else{
	echo "document.add.loc.focus();\n";
}
?>
</script>
<p>
<?
}

if ($add and $ser and $typ and $loc){
	$query	= GenQuery('stock','i','','','',array('serial','type','user','time','location','state','comment','lastwty','source'),'',array($ser,$typ,$_SESSION['user'],time(),$loc,$sta,$com,$wint,$src) );
	if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$serlbl $ser $updlbl OK</h5>";}
}elseif ($upd and $ser and $typ and $loc){
	$query	= GenQuery('stock','u',"serial=\"$ser\"",'','',array('type','user','time','location','state','comment','lastwty','source'),array(''),array($typ,$_SESSION['user'],time(),$loc,$sta,$com,$wint,$src ) );
	if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$serlbl $ser $updlbl OK</h5>";}
	$query = GenQuery('events','i','','','',array('level','time','source','info','type'),'',array('100',time(),$ser,"User $_SESSION[user] changed stock item: source=$src, loc=$loc, $com",'usrs') );
	if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$msglbl $updlbl OK</h5>";}

}elseif($del ){
	$query	= GenQuery('stock','d','','','',array('serial'),array('='),array($del) );
	if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$serlbl $del $dellbl OK</h5>";}
	$query = GenQuery('events','i','','','',array('level','time','source','info','class'),'',array('100',time(),$del,"User $_SESSION[user] deleted stock item",'usrs') );
	if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$msglbl $updlbl OK</h5>";}
}

if($lst){
	if($lst == "ty"){
		echo "<h2>$typlbl \"$val\" $lstlbl</h2>\n";
		$col = "type";
	}elseif($lst == "lo"){
		echo "<h2>$loclbl \"$val\" $lstlbl</h2>\n";
		$col = "location";
	}elseif($lst == "st"){
		echo "<h2>$stalbl \"$stco[$val]\" $lstlbl</h2>\n";
		$col = "state";
	}elseif($lst == "us"){
		echo "<h2>$usrlbl \"$val\" $lstlbl</h2>\n";
		$col = "user";
	}elseif($lst == "sc"){
		echo "<h2>$srclbl \"$val\" $lstlbl</h2>\n";
		$col = "source";
	}else{
		echo "<h2>$wtylbl \"".date($datfmt,$val)."\" $lstlbl</h2>\n";
		$col = "lastwty";
	}
?>
	<table class="content">
	<tr class="<?=$modgroup[$self]?>2">
	<th colspan="2"><img src="img/16/key.png"><br><?=$serlbl?></th>
	<th><img src="img/16/abc.png"><br><?=$typlbl?></th>
	<th><img src="img/16/user.png"><br><?=$usrlbl?></th>
	<th><img src="img/16/clock.png"><br><?=$updlbl?></th>
	<th><img src="img/16/home.png"><br><?=$loclbl?></th>
	<th><img src="img/16/find.png"><br><?=$deslbl?></th>
	<th><img src="img/16/ugrp.png"><br><?=$srclbl?></th>
	<th><img src="img/16/date.png"><br><?=$wtylbl?></th>
	<th><img src="img/16/cog.png"><br><?=$cmdlbl?></th></tr>	
<?
	$query	= GenQuery('stock','s','*','type,serial','',array("$col"),array('='),array("$val") );
	$res	= @DbQuery($query,$link);
	if($res){
		$row = 0;
		while( ($item = @DbFetchRow($res)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$us  = rawurlencode($item[0]);
			$uv  = rawurlencode($val);
			list($a1c,$a2c) = Agecol($item[3],$item[3],$row % 2);
			echo "<tr class=\"$bg\"><th class=\"$bi\">\n";
			echo "<img src=\"img/16/" . $cico[$item[5]] . ".png\" title=" . $stco[$item[5]] . "></th><td>$item[0]</td>\n";
			echo "<td><a href=\"?lst=ty&val=".rawurlencode($item[1])."\">$item[1]</a></td>\n";
			echo "<td><a href=\"?lst=us&val=".rawurlencode($item[2])."\">$item[2]</a></td><td bgcolor=#$a1c>".date($datfmt,$item[3])."</td>\n";
			echo "<td><a href=\"?lst=lo&val=".rawurlencode($item[4])."\">$item[4]</a></td><td>$item[6]</td>";
			echo "<td><a href=\"?lst=sc&val=".rawurlencode($item[8])."\">$item[8]</a></td><td>".date($datfmt,$item[7]);
			if(time() > $item[8]){echo "<img align=\"right\" src=\"img/16/flag.png\" title=\"$wtylbl $endlbl\">";}
			echo "</td><td align=center>\n";
			echo "<a href=?chg=$us&lst=$lst&val=$uv><img src=\"img/16/note.png\" title=\"$chglbl\"></a>\n";
			echo "<a href=?del=$us&lst=$lst&val=$uv><img src=\"img/16/bcnl.png\" onclick=\"return confirm('$dellbl $item[0]?')\" title=\"Delete!\"></a>\n";
			echo "</td></tr>\n";
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
	include_once ("inc/footer.php");
	exit;
}
?>

<table class="full fixed"><tr><td class="helper">

<h2><?=$invlbl?></h2>
<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th><img src="img/16/abc.png"><br><?=$typlbl?></th>
<th><img src="img/16/form.png"><br><?=$numlbl?></th>

<?
$query	= GenQuery('stock','g','type');
$res	= @DbQuery($query,$link);
if($res){
	$row = 0;
	while( ($item = @DbFetchRow($res)) ){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		$stbar = Bar($item[1],-10,'mi');
		echo "<tr class=\"$bg\">\n";
		echo "<td><a href=\"?lst=ty&val=".rawurlencode($item[0])."\">$item[0]</a></td><td>$stbar $item[1]</td></tr>\n";
	}
}
?>
</table>
<table class="content">
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$vallbl?></td></tr>
</table>

</td><td class="helper">

<h2><?=$chglbl?> <?=$sumlbl?></h2>
<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th colspan="2"><img src="img/16/clock.png"><br><?=$updlbl?></th>
<th><img src="img/16/abc.png"><br><?=$typlbl?></th>
<th><img src="img/16/user.png"><br><?=$usrlbl?></th>
<th><img src="img/16/home.png"><br><?=$loclbl?></th>
<th><img src="img/16/find.png"><br><?=$deslbl?></th>


<?
$query	= GenQuery('stock','s','*','time desc',$_SESSION['lim']);
$res	= @DbQuery($query,$link);
if($res){
	$row = 0;
	while( ($item = @DbFetchRow($res)) ){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		$us  = rawurlencode($item[0]);
		$uv  = rawurlencode($val);
		list($a1c,$a2c) = Agecol($item[3],$item[3],$row % 2);
		echo "<tr class=\"$bg\"><th class=\"$bi\">\n";
		echo "<img src=\"img/16/" . $cico[$item[5]] . ".png\" title=" . $stco[$item[5]] . "></th>\n";
		echo "<td nowrap bgcolor=\"#$a1c\">".date($datfmt,$item[3])."</td>\n";
		echo "<td><a href=\"?lst=ty&val=".rawurlencode($item[1])."\">$item[1]</a></td>\n";
		echo "<td><a href=\"?lst=us&val=".rawurlencode($item[2])."\">$item[2]</a></td>\n";
		echo "<td><a href=\"?lst=lo&val=".rawurlencode($item[4])."\">$item[4]</a></td><td>$item[6]</td>";
		echo "</tr>\n";
	}
}
?>
</table>
<table class="content">
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$vallbl?></td></tr>
</table>

<h2><?=$loclbl?> <?=$sumlbl?></h2>
<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th><img src="img/16/home.png"><br><?=$loclbl?></th>
<th><img src="img/16/form.png"><br><?=$numlbl?></th>

<?
$query	= GenQuery('stock','g','location');
$res	= @DbQuery($query,$link);
if($res){
	$row = 0;
	while( ($item = @DbFetchRow($res)) ){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		$stbar = Bar($item[1],0,'mi');
		echo "<tr class=\"$bg\">\n";
		echo "<td><a href=\"?lst=lo&val=".rawurlencode($item[0])."\">$item[0]</a></td><td>$stbar $item[1]</td></tr>\n";
	}
}
?>
</table>
<table class="content">
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$vallbl?></td></tr>
</table>

<h2><?=$wtylbl?> <?=$sumlbl?></h2>
<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th colspan="2"><img src="img/16/date.png"><br><?=$wtylbl?></th>
<th><img src="img/16/form.png"><br><?=$numlbl?></th>

<?
$query	= GenQuery('stock','g','lastwty','lastwty',$_SESSION['lim']);
$res	= @DbQuery($query,$link);
if($res){
	$row = 0;
	while( ($item = @DbFetchRow($res)) ){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		$stbar = Bar($item[1],0,'mi');
		echo "<tr class=\"$bg\">\n";
		echo "<th>$row</th>\n";
		echo "<td><a href=\"?lst=wy&val=$item[0]\">". date($datfmt,$item[0]);
		if(time() > $item[0]){echo "<img align=\"right\" src=\"img/16/flag.png\" title=\"$wtylbl $endlbl\">";}
		echo "</a></td><td>$stbar $item[1]</td></tr>\n";
	}
}
?>
</table>
<table class="content">
<tr class="<?=$modgroup[$self]?>2"><td>Top <?=$row?> <?=$vallbl?></td></tr>
</table>

<h2><?=$srclbl?> <?=$sumlbl?></h2>
<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th><img src="img/16/ugrp.png"><br><?=$srclbl?></th>
<th><img src="img/16/form.png"><br><?=$numlbl?></th>

<?
$query	= GenQuery('stock','g','source');
$res	= @DbQuery($query,$link);
if($res){
	$row = 0;
	while( ($item = @DbFetchRow($res)) ){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		$stbar = Bar($item[1],0,'mi');
		echo "<tr class=\"$bg\">\n";
		echo "<td><a href=\"?lst=sc&val=".rawurlencode($item[0])."\">$item[0]</a></td><td>$stbar $item[1]</td></tr>\n";
	}
}
?>
</table>
<table class="content">
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$vallbl?></td></tr>
</table>

<h2><?=$usrlbl?> <?=$sumlbl?></h2>
<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th><img src="img/16/user.png"><br><?=$usrlbl?></th>
<th><img src="img/16/form.png"><br><?=$numlbl?></th>
<?
$query	= GenQuery('stock','g','user');
$res	= @DbQuery($query,$link);
if($res){
	$row = 0;
	while( ($item = @DbFetchRow($res)) ){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		$stbar = Bar($item[1],0,'mi');
		echo "<tr class=\"$bg\">\n";
		echo "<td><a href=\"?lst=us&val=".rawurlencode($item[0])."\">$item[0]</a></td><td>$stbar $item[1]</td></tr>\n";
	}
}
?>
</table>
<table class="content">
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$vallbl?></td></tr>
</table>

<h2><?=$stalbl?> <?=$sumlbl?></h2>
<table class="content">
<tr class="<?=$modgroup[$self]?>2">
<th colspan="2"><img src="img/16/find.png"><br><?=$stalbl?></th>
<th><img src="img/16/form.png"><br><?=$numlbl?></th>
<?
$query	= GenQuery('stock','g','state');
$res	= @DbQuery($query,$link);
if($res){
	$row = 0;
	while( ($item = @DbFetchRow($res)) ){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		$stbar = Bar($item[1],0,'mi');
		echo "<tr class=\"$bg\">\n";
		echo "<th class=\"$bi\"><img src=\"img/16/" . $cico[$item[0]] . ".png\" title=" . $stco[$item[0]] . "></th>\n";
		echo "<td><a href=\"?lst=st&val=$item[0]\">". $stco[$item[0]] ."</a></td><td>$stbar $item[1]</td></tr>\n";
	}
}
?>
</table>
<table class="content">
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$vallbl?></td></tr>
</table>

</td></tr></table>

<?
include_once ("inc/footer.php");
?>
