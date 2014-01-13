<?
# Program: System-Files.php
# Programmer: Remo Rickli

$printable = 1;

include_once ("inc/header.php");

# Edit to fit your system...
$sysfiles = array(	"log/msg.txt",
			"log/devtools.php",
			"$nedipath/inc/crontab",
			"$nedipath/nedi.conf",
			"$nedipath/seedlist",
			"/etc/raddb/eap.conf",
			"/etc/raddb/radiusd.conf",
			"/etc/raddb/clients.conf",
			"/etc/raddb/users",
			"/etc/snmp/snmptrapd.conf",
			"/etc/dhcpd.conf",
			"/var/log/messages",
			"/var/log/smsd.log",
			"/var/log/radius/radius.log",
			"/var/www/logs/error_log"
		);

$_GET = sanitize($_GET);
$del   = isset($_GET['del']) ? $_GET['del'] : "";

#$_POST = sanitize($_POST); Not needed and would remove quotes from txt!
$bup = isset($_POST['bup']) ? "checked" : "";
$mod = isset($_POST['mod']) ? $_POST['mod'] : "";
$txt = isset($_POST['txt']) ? $_POST['txt'] : "";
$wrt = isset($_POST['wrt']) ? 1 : "";

$editable = 0;
$delable  = 0;

if( $isadmin and isset($_POST['file']) ){
	$file  = ( in_array($_POST['file'], $sysfiles) ) ? $_POST['file'] : "";
	$editable = 1;
}elseif( preg_match("/net/",$_SESSION['group']) and isset($_POST['log']) ){
	$file  = ( file_exists("/tmp/$_POST[log]") )? "/tmp/$_POST[log]":"";
	$delable  = 1;
}elseif( preg_match("/net/",$_SESSION['group']) and isset($_POST['cfg']) ){
	$file  = ( file_exists("$nedipath/conf/$_POST[cfg]") )? "$nedipath/conf/$_POST[cfg]":"";
	$delable  = 1;
}else{
	$file  = "";
}
?>

<script language="JavaScript"><!--
chg = 0;

function ConfirmSubmit(clr1, clr2) {

	clr = document.getElementById(clr1);
	if (clr != null)
		clr.selectedIndex = -1;

	clr = document.getElementById(clr2);
	if (clr != null)
		clr.selectedIndex = -1;

	if (chg){
		if( confirm('<?=$chglbl?> <?=$loslbl?>, <?=$cfmmsg?>') ){
			document.edit.submit();
		}else{
			return;
		}
	}else{
		document.edit.submit();
	}
}

// apparently from PHPMyAdmin
function insertAtCursor(myField, myValue) {
	//IE support
	if (document.selection) {
		myField.focus();
		sel = document.selection.createRange();
		sel.text = myValue;
	}
	//MOZILLA/NETSCAPE support
	else if (myField.selectionStart || myField.selectionStart == '0') {
		var startPos = myField.selectionStart;
		var endPos = myField.selectionEnd;
		myField.value = myField.value.substring(0, startPos)
		+ myValue
		+ myField.value.substring(endPos, myField.value.length);
	} else {
		myField.value += myValue;
	}
}
//--></script>

<h1>System <?=$fillbl?></h1>

<form method="post" action="<?=$self?>.php" name="edit" enctype="multipart/form-data">
<table class="content" ><tr class="<?=$modgroup[$self]?>1">
<th width="50"><a href="<?=$self?>.php"><img src="img/32/<?=$selfi?>.png"></a></th>
<td>
<?
if($isadmin){
?>

<img src="img/16/cog.png" title="System">
<select name="file" id="file" onchange="ConfirmSubmit('log','cfg');">
<option value=""><?=$edilbl?> ->
<?
foreach ($sysfiles as $sf){
	echo "<option value=\"$sf\"".( ($file == $sf)?"selected":"").">$sf\n";
}
?>
</select>

<?
}

if( preg_match("/net/",$_SESSION['group']) ){
?>
<p>

<img src="img/16/conf.png" title="Device <?=$cfglbl?>">

<select name="cfg" id="cfg" onchange="ConfirmSubmit('file','log');">
<option value=""><?=$sholbl?> ->
<?
$plen = strlen($nedipath);
foreach (glob("$nedipath/conf/*") as $d){
        if (is_dir($d)){
		$cfgd = substr($d,$plen+6);
		echo "<option value=\"\" style=\"color: blue\">- $cfgd -\n";
		foreach (glob("$d/*.cfg") as $f) {
 			$l = substr($f,strlen($d)+1);
			echo "<option value=\"$cfgd/$l\" ".( ($file == $f)?"selected":"").">$l\n";
		}
	}
}
?>
</select>

<p>
<img src="img/16/note.png" title="NeDi logs">

<select name="log" id="log" onchange="ConfirmSubmit('file','cfg');">
<option value=""><?=$sholbl?> ->
<?
foreach (glob("/tmp/nedi*") as $f) {
	$l = substr($f,5);
	echo "<option value=\"$l\"".( ($file == $f)?"selected":"").">$l\n";
}
?>
</select>
<?}?>

</td>
<td valign="top">
<h3>
<input type="radio" name="mod" value="u" <?=($mod == "u")?"checked":""?> onClick="document.edit.bup.disabled=false;";> <?=$updlbl?>
<input type="radio" name="mod" value="m" <?=($mod == "m")?"checked":""?> onClick="document.edit.bup.disabled=true;";> <?=$implbl?>
<input type="radio" name="mod" value="l" <?=($mod == "l")?"checked":""?> onClick="document.edit.bup.disabled=true;";> <?=$upllbl?>-log
<input type="radio" name="mod" value="t" <?=($mod == "t")?"checked":""?> onClick="document.edit.bup.disabled=true;";> <?=$upllbl?>-tftp
<input type="radio" name="mod" value="r" <?=($mod == "r")?"checked":""?> onClick="document.edit.bup.disabled=true;";> <?=$dellbl?> <?=$stco['200']?> RRDs
</h3>
<input name="tgz" type="file" size="20" accept="archive/tar"> 
<input type="checkbox" name="bup" <?=$bup?> title="" disabled="true"> <?=$cfglbl?> <?=$buplbl?>
</td>

<td width="80">
<input type="submit" name="up" value="<?=$cmdlbl?>"><p>
</td>

</tr></table>

<?

if($isadmin){
	if($del){
		if( unlink ($del) ){
			echo "<h5>$dellbl $del OK</h5>";
		}else{
			echo "<h4>$errlbl $dellbl $del</h4>";
		}
	}elseif($wrt and $file){
		$hdle = fopen($file, "w");
		if( fwrite($hdle, preg_replace("/\r/", "", $txt ) ) ){
			echo "<h5>$wrtlbl $file OK</h5>\n";
		}else{
			echo "<h4>$errlbl: $wrtlbl $file!</h4>\n";
		}
		fclose($hdle);
		if($file == "$nedipath/inc/crontab"){
			system("crontab $file", $fail);
			if($fail){
				echo "<h4>Crontab $updlbl $errlbl</h4>\n";
			}else{
				echo "<h5>Crontab $updlbl OK</h5>\n";
			}
		}
	}
}

if($file){
?>

<h2><?=$file?></h2>

<table class="content">
<tr class="<?=$modgroup[$self]?>2"><th>
<?
if($isadmin){
	if($editable){
?>
<?=$addlbl?> &nbsp;
<input type="button" value="Tab" OnClick="insertAtCursor(document.edit.txt, '	');";>
<input type="button" value="#" OnClick="insertAtCursor(document.edit.txt, '#');";>
<input type="button" value="|" OnClick="insertAtCursor(document.edit.txt, '|');";>
<input type="button" value="/" OnClick="insertAtCursor(document.edit.txt, '/');";>
 -
<input type="submit" name="wrt" value="<?=$wrtlbl?>">
<?
}
	if($delable){
?>
<div style="float:right"><a href="?del=<?=urlencode($file)?>"><img src="img/16/bcnl.png" onclick="return confirm('<?=$dellbl?>, <?=$cfmmsg?>')" title="<?=$dellbl?>!"></a></div>
<?
	}
}
?>
<br>
<textarea rows="30" name="txt" cols="120" onChange="chg='1';" class="code">
<?
	if (file_exists($file)) {
		readfile($file);
	};
?>
</textarea>
</th>
</tr></table>
</form>

<?
}elseif($isadmin and $mod == "u"){
?>
<h1>NeDi <?=$updlbl?></h1>
<div class="textpad code txta" name="out">
<?
	if(array_key_exists('tgz',$_FILES)){
		if(file_exists($_FILES['tgz']['tmp_name'])) {
			echo "$realbl ".$_FILES['tgz']['name']."\n\n";
			if (!copy("log/msg.txt", "/tmp/msg.txt")) {
				echo "<h4>$errlbl $wrtlbl /tmp/msg.txt</h4>\n";
				die;
			}else{
				echo "$buplbl msg.txt\n";
			}
			if (!copy("$nedipath/inc/crontab", "/tmp/crontab")) {
				echo "<h4>$errlbl $wrtlbl /tmp/crontab</h4>\n";
				die;
			}else{
				echo "$buplbl crontab\n";
			}
			if (!copy("$nedipath/nedi.conf", "/tmp/nedi.conf")) {
				echo "<h4>$errlbl $wrtlbl /tmp/nedi.conf</h4>\n";
				die;
			}else{
				echo "$buplbl nedi.conf\n";
			}
			if (!copy("$nedipath/seedlist", "/tmp/seedlist")) {
				echo "<h4>$errlbl $wrtlbl /tmp/seedlist</h4>\n";
				die;
			}else{
				echo "$buplbl seedlist\n\n";
			}

			echo system("tar zxf ".$_FILES['tgz']['tmp_name']." -C $nedipath", $stat);
			if($stat){
				echo "<h4>$errlbl $wrtlbl ".$_FILES['tgz']['name']."</h4>\n";
				die;
			}else{
				echo "$wrtlbl ".$_FILES['tgz']['name']."\n\n";
			}
			if($bup){
				if (!copy("/tmp/msg.txt", "log/msg.txt")) {
					echo "<h4>$errlbl $wrtlbl log/msg.txt</h4>\n";
				}else{
					echo "$wrtlbl log/msg.txt\n";
				}
				if (!copy("/tmp/crontab", "$nedipath/inc/crontab")) {
					echo "<h4>$errlbl $wrtlbl $nedipath/inc/crontab</h4>\n";
				}else{
					echo "$wrtlbl $nedipath/inc/crontab\n";
				}
				if (!copy("/tmp/nedi.conf", "$nedipath/nedi.conf")) {
					echo "<h4>$errlbl $wrtlbl $nedipath/nedi.conf</h4>\n";
				}else{
					echo "$wrtlbl $nedipath/nedi.conf\n";
				}
				if (!copy("/tmp/seedlist", "$nedipath/seedlist")) {
					echo "<h4>$errlbl $wrtlbl $nedipath/seedlist</h4>\n";
				}else{
					echo "$wrtlbl $nedipath/seedlist\n\n";
				}
			}
			echo "<h5>$updlbl OK</h5>";
		}else{
			echo "<h4>$errlbl $realbl ".$_FILES['tgz']['name']."</h4>";
		}
	}
?>
</div><br>

<?
}elseif($isadmin and $mod == "m"){
?>

<h2>NeDi <?=$implbl?></h2>
<div class="textpad code txta" name="out">
<?
	if(array_key_exists('tgz',$_FILES)){
		if(file_exists($_FILES['tgz']['tmp_name'])) {
			echo "$realbl ".$_FILES['tgz']['name']."\n\n";
			echo system("gzcat ".$_FILES['tgz']['tmp_name']." | mysql $dbname --user=$dbuser --password=$dbpass", $stat);
			if($stat){
				echo "<h4>$errlbl $wrtlbl ".$_FILES['tgz']['name']."</h4>\n";
			}else{
				echo "$wrtlbl ".$_FILES['tgz']['name']."\n\n";
			}
			echo "<h5>$implbl OK</h5>";
		}else{
			echo "<h4>$errlbl $realbl ".$_FILES['tgz']['name']."</h4>";
		}
	}
?>
</div><br>
<?
}elseif($mod == "l" or $mod == "t"){
?>
<h2><?=$fillbl?> <?=$upllbl?></h2>
<div class="textpad code txta" name="out">
<?
	$dir = ($mod == "l")?"log":"/var/tftpboot";
	if(array_key_exists('tgz',$_FILES)){
		if(file_exists($_FILES['tgz']['tmp_name'])) {
			echo "$realbl ".$_FILES['tgz']['name']."\n\n";
			if( rename($_FILES['tgz']['tmp_name'],"$dir/".$_FILES['tgz']['name']) ){
				echo "$wrtlbl \"$dir/".$_FILES['tgz']['name']."\"\n";
				if($mod == "t"){
					if(chmod("/$dir/".$_FILES['tgz']['name'],0644) ){
						echo "<h5>$cmdlbl $alllbl $realbl OK</h5>";
					}else{
						echo "<h4>$errlbl $realbl $alllbl</h4>\n";
					}
				}
				echo "<h5>$upllbl OK</h5>";
			}else{
				echo "<h4>$errlbl $wrtlbl \"$dir/".$_FILES['tgz']['name']."\"</h4>\n";
			}
		}else{
			echo "<h4>$errlbl $realbl \"".$_FILES['tgz']['tmp_name']."\"</h4>";
		}
	}
?>
</div><br>

<?
}elseif($isadmin and $mod == "r"){
?>
<h2>RRDs <?=$updlbl?> > <?=$retire?> <?=$tim[d]?></h2>
<div class="textpad code txta" name="out">
<?
	$nrrd = 0;
	foreach (glob("$nedipath/rrd/*") as $dv){
		if (is_dir($dv) && $dv != "." && $dv != "..") {
			foreach (glob("$dv/*.rrd") as $rrd){
				$mtime = filemtime($rrd);
				if( $mtime < (time() - $retire * 86400) ){
					$dstat = (unlink($rrd))?"OK":"$errlbl";
					echo date($_SESSION[date],$mtime)." $rrd: $dellbl $dstat\n";
				}
			}
		}
	}

?>
</div><br>
<?
}else{
?>

<h2><?=$fillbl?> <?=$lstlbl?></h2>

<table class="full fixed">
<tr><td class="helper">

<h3>html/log</h3>
<?FileList('log');?>

</td><td class="helper">

<h3>tftpboot</h3>
<?FileList('/var/tftpboot');?>

</td></tr></table>
<?
}

function FileList($dir){
	
	global $modgroup,$self,$namlbl,$fillbl,$totlbl,$sizlbl,$updlbl,$dellbl,$cfmmsg,$isadmin;

?>
<table class="content">
<tr class="<?=$modgroup[$self]?>1"><th colspan="2"><?=$namlbl?></th><th><?=$sizlbl?></th><th><?=$updlbl?></th></tr>
<?
	$row  = 0;
	$tsiz = 0;
	foreach (glob("$dir/*") as $f){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		$plen = strlen($dir);
		$t = substr($f,$plen+1);
		if(is_dir($f)){
			echo "<tr class=\"$bg\"><th class=\"$bi\" width=\"20\"><img src=\"img/16/fobl.png\" title=\"Folder\"></th><td colspan=\"3\"><b>$t</b></td></tr>\n";
		}else{
			echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\">";
			echo "<th class=\"$bi\" width=\"20\">".Fimg($f)."</th><td>";
			if($dir == "log"){echo "<a href=\"$f\" target=\"window\">";}
			echo "$t</a></td>\n";
			$siz = round(filesize($f) / 1024,1);
			$tsiz += $siz;
			echo "<td align=\"right\">$siz KB</td>\n";
			echo "<td align=\"right\">";
			if($isadmin){
				echo "<div style=\"float:right\"><a href=\"?del=".urlencode($f)."\"> <img src=\"img/16/bcnl.png\" onclick=\"return confirm('$dellbl $t, $cfmmsg')\" title=\"$dellbl $t!\"></a></div>";
			}
			echo date ($_SESSION['date'],filemtime($f))."</td></tr>\n";
		}
	}
?>
<tr class="<?=$modgroup[$self]?>1"><td colspan="4"><?=$row?> <?=$fillbl?>, <?=$totlbl?> <?=$tsiz?> KB</td></tr>
</table>
<?
}

include_once ("inc/footer.php");
?>
