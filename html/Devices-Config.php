<?
# Program: Devices-Config.php
# Programmer: Remo Rickli

error_reporting(E_ALL ^ E_NOTICE);

$printable = 1;

include_once ("inc/header.php");
include_once ("inc/libdev.php");
include_once ("inc/libmon.php");

$_GET = sanitize($_GET);
$gen = isset($_GET['gen']) ? $_GET['gen'] : "";
$shc = isset($_GET['shc']) ? $_GET['shc'] : "";
$sln = isset($_GET['sln']) ? $_GET['sln'] : "";
$smo = isset($_GET['smo']) ? $_GET['smo'] : "";
$dch = isset($_GET['dch']) ? $_GET['dch'] : "";
$dco = isset($_GET['dco']) ? $_GET['dco'] : "";
$ord = isset($_GET['ord']) ? $_GET['ord'] : "";

$shl = isset($_GET['shl']) ? $_GET['shl'] : "";
$str = isset($_GET['str']) ? $_GET['str'] : "";
$ld = isset($_GET['ld']) ? $_GET['ld'] : "";

$dd = isset($_GET['dd']) ? $_GET['dd'] : "";
$cm = isset($_GET['cm']) ? $_GET['cm'] : "";

$lim = isset($_GET['lim']) ? $_GET['lim'] : 100;
$lid = isset($_GET['lid']) ? $_GET['lid'] : 10;

$cfgup = array();

$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
$query	= GenQuery('configs','s','device,time','device','','','','','','LEFT JOIN devices USING (device)');
$res	= @DbQuery($query,$link);
if($res){
	while( ($c = @DbFetchRow($res)) ){
		$cfgup[$c[0]] = $c[1];
	}
	@DbFreeResult($res);
}else{
	print @DbError($link);
}
?>
<h1>Devices <?=$cfglbl?></h1>

<?if( !isset($_GET['print']) ){?>

<form method="get" action="<?=$self?>.php" name="cfg">
<table class="content"><tr class="<?=$modgroup[$self]?>1">
<th width="50"><a href="<?=$self?>.php"><img src="img/32/<?=$selfi?>.png"></a></th>
<th valign="top"><h3><?=$lstlbl?></h3>
<select size="1" name="shl">
<option value="n"><?=$cfglbl?> regexp
<option value="i" <?=($shl == "i")?"selected":""?>><?=$cfglbl?> !regexp
<option value="c" <?=($shl == "c")?"selected":""?>><?=$chglbl?> regexp
<option value="t" <?=($shl == "t")?"selected":""?>>Device <?=$typlbl?> regexp
<option value="d" <?=($shl == "d")?"selected":""?>>device =
</select>

<input type="text" name="str" value="<?=$str?>" size="20">

<select size="1" name="ld" onchange="document.cfg.str.value=document.cfg.ld.options[document.cfg.ld.selectedIndex].value">
<option value=""><?=$sellbl?> ->
<?
foreach (array_keys($cfgup) as $d){
	echo "<option value=\"$d\"". (($ld == $d)?" selected":"").">$d\n";
}
?>
</select>

</th>
<th valign="top"><h3><?=$cmplbl?></h3>

<select size="1" name="dd">
<option value="">- <?=$typlbl?> -
<?
foreach (array_keys($cfgup) as $d){
	echo "<option value=\"$d\"". (($dd == $d)?" selected":"").">$d\n";
}
?>
</select>
<select size="1" name="cm" title="Verbose show access vlans and random numbers too">
<option value=""><?=$sellbl?> ->
<option value="v" <?=($cm == "v")?"selected":""?>>Side by Side
<option value="i" <?=($cm == "i")?"selected":""?>>IOS <?=$optlbl?>
<option value="p" <?=($cm == "p")?"selected":""?>>Procurve <?=$optlbl?>
<option value="f" <?=($cm == "f")?"selected":""?>>Ironware <?=$optlbl?>
</select>

</th>
<th valign="top"><h3><?=$limlbl?></h3>

<select size="1" name="lim" title="<?=$cfglbl?>/<?=$chglbl?>">
<? selectbox("limit",$lim);?>
</select>

<select size="1" name="lid" title="Devices">
<? selectbox("limit",$lid);?>
</select>

</th>
<th width="80">

<input type="submit" value="<?=$sholbl?>" name="gen">
</th>
</table></form><p>
<?
}

if ($dch){
	if($isadmin){
		$query	= GenQuery('configs','u',"device=\"$dch\"",'','',array('changes'),'',array('') );
		if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5> $dch $dellbl $chglbl $lstlbl OK</h5>";}
	}else{
		echo $nokmsg;
	}
	$shc = $dch;
}
if ($dco){
	if($isadmin){
		$query	= GenQuery('configs','d','','','',array('device'),array('='),array($dco) );
		if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5> $dco $dellbl $cfglbl OK</h5>";}
?><script language="JavaScript"><!--
setTimeout("history.go(-2)",2000);
//--></script><?		

	}else{
		echo $nokmsg;
	}
}

if ($gen){
	$query	= GenQuery('devices','s','device,devip,type,devos,icon,cliport');
	$res	= @DbQuery($query,$link);
	if($res){
		while( ($d = @DbFetchRow($res)) ){
			$devip[$d[0]] = long2ip($d[1]);
			$devty[$d[0]] = $d[2];
			$devos[$d[0]] = $d[3];
			$devic[$d[0]] = $d[4];
			$devpo[$d[0]] = $d[5];
		}
		@DbFreeResult($res);
	}else{
		print @DbError($link);
	}

	if(($dd or $cm)){
		$query	= GenQuery('configs','s','configs.*','','',array('device'),array('='),array($ld),'','LEFT JOIN devices USING (device)');
		$res	= @DbQuery($query,$link);
		$srcok	= @DbNumRows($res);
		if ($srcok == 1) {
			$rdvc = @DbFetchRow($res);
			@DbFreeResult($res);
		}else{
			echo "<h4>$srcok $vallbl</h4>";
			die;
		}
		echo "<h2>$cmplbl $lstlbl</h2>\n";
		if($dd){
			$cmpdev = array($ld,$dd);
		}else{
			foreach (array_keys($cfgup) as $d){
				if($devty[$ld] == $devty[$d]){
					$cmpdev[] = $d;
				}
			}
		}
		sort ($cmpdev);
		foreach ($cmpdev as $ddv){
			$ud	= rawurlencode($ddv);
?>
<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th><a href="Devices-Status.php?dev=<?=$ud?>"><img src="img/dev/<?=$devic[$ddv]?>.png"></a><br><?=($ld == $ddv)?"$ddv ($srclbl)":"$ddv"?></th>
<tr class="txta"><td valign="top">
<a href="?shc=<?=$ud?>"><img src="img/16/note.png" title="<?=$sholbl?>"></a>
<div class="code">
<?
			if($ld == $ddv){
				$lnr = 0;
				foreach ( explode("\n",$rdvc[1]) as $cl ){
					$lnr++;
					echo Shoconf($cl,$smo,$lnr);
				}
				echo "</div></td></tr></table><p>";
			}else{
				$query	= GenQuery('configs','s','configs.*','','',array('device'),array('='),array($ddv),'LEFT JOIN devices USING (device)');
				$res	= @DbQuery($query,$link);
				$cfgok	= @DbNumRows($res);
				if ($cfgok == 1) {
					$ddvc = @DbFetchRow($res);
					@DbFreeResult($res);
					echo PHPDiff( Opdiff($rdvc[1],$cm), Opdiff($ddvc[1],$cm),($cm == 'v')?1:0 );		
					echo "</div></td></tr></table><p>";
				}else{
					echo "<h4>$ddv: $cfgok $vallbl</h4>";
				}
			}
		}
	}else{
		$ina	='config';
		$opa	= 'regexp';
		if($shl == 'i'){
			$opa	= 'not regexp';
		}elseif($shl == 'c'){
			$ina	='changes';
		}elseif($shl == 't'){
			$ina	='type';
		}elseif($shl == 'd'){
			$opa	= '=';
			$ina	='device';
		}
?>
<h2><?=$lstlbl?></h2>
<table class="content"><tr class="<?=$modgroup[$self]?>2">
<?

		ColHead('device',80);
		?><th>IP</th><?
		?><th>OS</th><?
		ColHead('config');
		ColHead('length(config)');
		ColHead('changes');
		ColHead('length(changes)');
		ColHead('time');

		$query	= GenQuery('configs','s','configs.*,length(config),length(changes)',$ord,$lid,array($ina),array($opa),array($str),'','LEFT JOIN devices USING (device)');

		$res	= @DbQuery($query,$link);
		if($res){
			$row = 0;
			if (!$lim){$lim = 1000000;}						# Yepp, not really oo...
			while( ($con = @DbFetchRow($res)) ){
				if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
				$row++;
				$img = $devic[$con[0]];
				$typ = $devty[$con[0]];
				$ud  = rawurlencode($con[0]);
				echo "<tr class=\"$bg\"><th class=\"$bi\">\n";
				echo "<a href=Devices-Status.php?dev=$ud><img src=\"img/dev/$img.png\" title=\"$typ\"></a><br>$con[0]</td>\n";
				echo "<td>".Devcli($devip[$con[0]],$devpo[$con[0]])."</td><td>".$devos[$con[0]]."</td>\n";
				echo "<td><a href=$_SERVER[PHP_SELF]?shc=$ud><div class=\"code\">\n";
				echo substr(implode("\n",preg_grep("/$str/i",explode("\n",$con[1]) ) ),0,$lim);
				echo "</div></a></td><td>$con[4]</td>\n";
				$cu  = date($_SESSION['date'],$con[3]);
				list($u1c,$u2c) = Agecol($con[3],$con[3],$row % 2);
				echo "<td><div class=\"code\">";
				echo substr(implode("\n",preg_grep("/$str/i",explode("\n",$con[2]) ) ),0,$lim);
				echo "</div></td><td>$con[5]</td><td bgcolor=\"#$u1c\" nowrap>$cu</td>\n";
				echo "</td></tr>\n";
			}
			@DbFreeResult($res);
		}else{
			print @DbError($link);
		}
	?>
</table>
<table class="content">
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Devices</td></tr>
</table>
	<?
	}

}elseif($shc){
	echo "<h2>$shc</h2>\n";

	$query	= GenQuery('configs','s','*','','',array('device'),array('='),array($shc),'','LEFT JOIN devices USING (device)');
	$res	= @DbQuery($query,$link);
	$cfgok	= @DbNumRows($res);
	if ($cfgok == 1) {
		$cfg = @DbFetchRow($res);
		@DbFreeResult($res);
	}else{
		echo "<h4>$shc: $cfgok $vallbl</h4>";
		die;
	}
	$ucfg	= rawurlencode($cfg[0]);
	$charr	= explode("\n",$cfg[2]);
	$charr	= preg_replace("/^#(.*)$/","<span class='gry'>#$1</span>",$charr);
	$charr	= preg_replace("/(^\s*[0-9]{1,3}\-.*)$/","<span class='drd'>$1</span>",$charr);
	$charr	= preg_replace("/(^\s*[0-9]{1,3}\+.*)$/","<span class='olv'>$1</span>",$charr);
?>
<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th><img src="img/32/note.png"><br><?=$cfglbl?> (<?=date($_SESSION['date'],$cfg[3])?>)</th>
<th><img src="img/32/news.png"><br><?=$chglbl?></th></tr>
<tr class="txta"><td valign="top">
<a href="?shc=<?=$ucfg?>&sln=<?=!$sln?>&smo=<?=$smo?>"><img src="img/16/form.png" title="Line #"></a>
<a href="?shc=<?=$ucfg?>&sln=<?=$sln?>&smo=<?=!$smo?>"><img src="img/16/say.png" title="motd"></a>
<a href="System-Export.php?action=export&exptbl=configs&query=SELECT+config+FROM+configs+where+DEVICE%3D%22<?=$ucfg?>%22&type=plain"><img src="img/16/flop.png" title="<?=(($verb1)?"$explbl $cfglbl":"$cfglbl $explbl")?>"></a>
<a href="Devices-Status.php?dev=<?=$ucfg?>"><img src="img/16/sys.png" title="Device-Status"></a>
<? if($isadmin){?>
<a href="<?=$_SERVER[PHP_SELF]?>?dco=$ucfg"><img src="img/16/bcnl.png" onclick="return confirm('$cfg[0]: $dellbl $cfglbl?')" title="$dellbl $cfglbl!"></a>
<?}?>

<div class="code">
<?
	$lnr = 0;
	foreach ( explode("\n",$cfg[1]) as $cl ){
		if ($sln) $lnr++;
		echo Shoconf($cl,$smo,$lnr);
	}
echo "</div></td><td valign=top>";
?>

<form method="post" action="System-Files.php">
<select name="cfg" id="cfg" onchange="this.form.submit();">
<option value=""><?=(($verb1)?"$edilbl $fillbl":"$fillbl $edilbl")?> ->
<?
if (is_dir("$nedipath/conf/$shc")){
	foreach (glob("$nedipath/conf/$shc/*.cfg") as $f) {
		$l = substr(strrchr($f, '/'),1);
		echo "<option value=\"$shc/$l\">$l\n";
	}
}
?>
</select>
<?
if($isadmin)
	echo "<a href=$_SERVER[PHP_SELF]?dch=$ucfg><img src=\"img/16/bcnl.png\" onclick=\"return confirm('$cfg[0]: $dellbl $chglbl $lstlbl?')\" title=\"$dellbl $chglbl!\"></a>\n";
?>
</form>

<div class="code"><?=implode("\n",$charr)?>
</div></td></tr></table>
<?
}else{

	echo "<p><br><h2>$buplbl $errlbl</h2>";
	Events($_SESSION['lim'],array('class'),array('='),array('cfge'),'');

	echo "<p><br><h2>".(($verb1)?"$laslbl $chglbl":"$chglbl $laslbl")."</h2>";
	Events($_SESSION['lim'],array('class'),array('='),array('cfgc'),'');

	echo "<p><br><h2>".(($verb1)?"$newlbl $cfglbl":"$cfglbl $newlbl")."</h2>";
	Events($_SESSION['lim'],array('class'),array('='),array('cfgn'),'');
}

include_once ("inc/footer.php");

// Optimize configuration before comparison
// Sidebyside added by Remo 1.2011
function Opdiff($cfg,$mo){
	
	global $lim;

	$config = "";
	foreach ( explode("\n",$cfg) as $l ){
		$row++;
		if($mo == 'i' and preg_match("/secret 5|hostname|password 7|key 7|access vlan|clock-period|engineID|Current\s|change|updated/",$l) ){
			$config .= "\n";
		}elseif($mo == 'p' and preg_match("/untagged /",$l) ){
			$config .= "\n";
		}elseif($mo == 'f' and preg_match("/untagged /",$l) ){
			$config .= "\n";
		}else{
			$config .= "$l\n";
		}
		#if($row == $lim){$config .= "---\n";break;} TODO remove or make useful...
	}
	return $config;
}

    /**
        Diff implemented in pure php, written from scratch.
        Copyright (C) 2003  Daniel Unterberger <diff.phpnet@holomind.de>
        Copyright (C) 2005  Nils Knappmeier next version 
        
        This program is free software; you can redistribute it and/or
        modify it under the terms of the GNU General Public License
        as published by the Free Software Foundation; either version 2
        of the License, or (at your option) any later version.
        
        This program is distributed in the hope that it will be useful,
        but WITHOUT ANY WARRANTY; without even the implied warranty of
        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
        GNU General Public License for more details.
        
        You should have received a copy of the GNU General Public License
        along with this program; if not, write to the Free Software
        Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
        
        http://www.gnu.org/licenses/gpl.html

        About:
        I searched a function to compare arrays and the array_diff()
        was not specific enough. It ignores the order of the array-values.
        So I reimplemented the diff-function which is found on unix-systems
        but this you can use directly in your code and adopt for your needs.
        Simply adopt the formatline-function. with the third-parameter of arr_diff()
        you can hide matching lines. Hope someone has use for this.

        Contact: d.u.diff@holomind.de <daniel unterberger>
    **/

    
## PHPDiff returns the differences between $old and $new, formatted
## in the standard diff(1) output format.
function PHPDiff($old,$new,$mode) 
{
   # split the source text into arrays of lines
   $t1 = explode("\n",$old);
   $x=array_pop($t1); 
   if ($x>'') $t1[]="$x\n\\ No newline at end of file";
   $t2 = explode("\n",$new);
   $x=array_pop($t2); 
   if ($x>'') $t2[]="$x\n\\ No newline at end of file";

   # build a reverse-index array using the line as key and line number as value
   # don't store blank lines, so they won't be inas of the shortest distance
   # search
   foreach($t1 as $i=>$x) if ($x>'') $r1[$x][]=$i;
   foreach($t2 as $i=>$x) if ($x>'') $r2[$x][]=$i;

   $a1=0; $a2=0;   # start at beginning of each list
   $actions=array();

   # walk this loop until we reach the end of one of the lists
   while ($a1<count($t1) && $a2<count($t2)) {
     # if we have a common element, save it and go to the next
     if ($t1[$a1]==$t2[$a2]) { $actions[]=4; $a1++; $a2++; continue; } 

     # otherwise, find the shortest move (Manhattan-distance) from the
     # current location
     $best1=count($t1); $best2=count($t2);
     $s1=$a1; $s2=$a2;
     while(($s1+$s2-$a1-$a2) < ($best1+$best2-$a1-$a2)) {
       $d=-1;
       foreach((array)@$r1[$t2[$s2]] as $n) 
         if ($n>=$s1) { $d=$n; break; }
       if ($d>=$s1 && ($d+$s2-$a1-$a2)<($best1+$best2-$a1-$a2))
         { $best1=$d; $best2=$s2; }
       $d=-1;
       foreach((array)@$r2[$t1[$s1]] as $n) 
         if ($n>=$s2) { $d=$n; break; }
       if ($d>=$s2 && ($s1+$d-$a1-$a2)<($best1+$best2-$a1-$a2))
         { $best1=$s1; $best2=$d; }
       $s1++; $s2++;
     }
     while ($a1<$best1) { $actions[]=1; $a1++; }  # deleted elements
     while ($a2<$best2) { $actions[]=2; $a2++; }  # added elements
  }

  # we've reached the end of one list, now walk to the end of the other
  while($a1<count($t1)) { $actions[]=1; $a1++; }  # deleted elements
  while($a2<count($t2)) { $actions[]=2; $a2++; }  # added elements

  # and this marks our ending point
  $actions[]=8;

  # now, let's follow the path we just took and report the added/deleted
  # elements into $out.
  $op = 0;
  $x0=$x1=0; $y0=$y1=0;
  $out = array();
  if($mode){# Sidebyside by Remo
	$out[] = "<table class='full'>";
	foreach($actions as $act) {
		if ($act==1) { $op|=$act; $x1++; continue; }
		if ($act==2) { $op|=$act; $y1++; continue; }
		$out[] = "<tr onmouseover=\"this.className='imga'\" onmouseout=\"this.className='txta'\">";
		if ($op>0) {
			$xstr = ($x1==($x0+1)) ? $x1 : ($x0+1).",$x1";
			$ystr = ($y1==($y0+1)) ? $y1 : ($y0+1).",$y1";
			if($op==1){
				$out[] = "<td class='alrm'>";
				while ($x0<$x1) {$out[] = "$t1[$x0]\n";$x0++;}   # deleted elems
				$out[] = "</td><td></td>\n";
			}elseif($op==2){
				$out[] = "<td></td><td class='good'>";
				while ($y0<$y1) {$out[] = "$t2[$y0]\n";$y0++;}   # added elems
				$out[] = "</td>\n";
			}else{ 
				$out[] = "<td class='warn' valign=\"top\">";
				while ($x0<$x1) {$out[] = "$t1[$x0]\n";$x0++;}   # changed elems
				$out[] = "</td><td class='warn' valign=\"top\">";
				while ($y0<$y1) {$out[] = "$t2[$y0]\n";$y0++;}   # changed elems
				$out[] = "</td>\n";
			}
		}else{
			$out[] = "<td>$t2[$y0]</td><td>$t2[$y0]</td>\n";
		}
		$out[] = "</tr>\n";
		$x1++; $x0=$x1;
		$y1++; $y0=$y1;
		$op=0;
	}
	$out[] = "</table>";
  }else{
	foreach($actions as $act) {
		if ($act==1) { $op|=$act; $x1++; continue; }
		if ($act==2) { $op|=$act; $y1++; continue; }
		if ($op>0) {
			$xstr = ($x1==($x0+1)) ? $x1 : ($x0+1).",$x1";
			$ystr = ($y1==($y0+1)) ? $y1 : ($y0+1).",$y1";
			if ($op==1) $out[] = "{$xstr}d{$y1}\n";
			elseif ($op==3) $out[] = "{$xstr}c{$ystr}\n";
			while ($x0<$x1) {$out[] = "<span class=\"drd\">< $t1[$x0]</span>\n";$x0++;}   # deleted elems
			if ($op==2) $out[] = "{$x1}a{$ystr}\n";
			elseif ($op==3) $out[] = "<span class=\"gry\">---</span>\n";
			while ($y0<$y1) {$out[] = "<span class=\"olv\">> $t2[$y0]</span>\n";$y0++;}   # added elems
		}
		$x1++; $x0=$x1;
		$y1++; $y0=$y1;
		$op=0;
	}
  }
  return join("",$out);
}

?>