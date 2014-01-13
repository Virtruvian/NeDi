<?php
# Program: System-Files.php
# Programmer: Remo Rickli

$printable = 1;
$exportxls = 0;

include_once ("inc/header.php");

# Edit to fit your system...
$sysfiles = array(	"log/msg.txt",
			"log/devtools.php",
			"$nedipath/inc/crontab",
			"$nedipath/nedi.conf",
			"$nedipath/seedlist",
			"$nedipath/agentlist",
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

$ocol[1] = "DarkBlue";
$ocol[2] = "DarkRed";
$ocol[4] = "DarkMagenta";
$ocol[5] = "Brown";
$ocol[6] = "DarkGreen";
$ocol[7] = "GoldenRod";

$tftpboot = "/var/tftpboot";

$_GET = sanitize($_GET);
$del   = isset($_GET['del']) ? $_GET['del'] : "";

$_POST = sanitize($_POST);
$mde = isset($_POST['mde']) ? $_POST['mde'] : "";
$txt = isset($_POST['txt']) ? $_POST['txt'] : "";
$log = isset($_POST['log']) ? $_POST['log'] : "";
$cfg = isset($_POST['cfg']) ? $_POST['cfg'] : "";
$file= isset($_POST['file']) ? $_POST['file'] : "";
$tftp= isset($_POST['tftp']) ? preg_replace('/[<>\/\\\]/','',$_POST['tftp']) : "";
$wrt = isset($_POST['wrt']) ? 1 : "";
$all = isset($_POST['all']) ? "checked" : "";

$editable = 0;
$delable  = 0;
$tftpable = 0;

if( $isadmin and $file){
	$file  = ( in_array($file, $sysfiles) ) ? $file : "";
	$editable = 1;
}elseif( preg_match("/net/",$_SESSION['group']) ){
	if($log){
		$file = ( file_exists("/tmp/$log") )? "/tmp/$log":"";
		$delable = 1;
	}elseif($cfg){
		$file = ( file_exists("$nedipath/conf/$cfg") )? "$nedipath/conf/$cfg":"";
		$delable  = 1;
		$tftpable = 1;
	}elseif($tftp){
		$file  = "$tftpboot/$tftp";
		$tftpable = 1;
		$delable  = 1;
	}else{
		$file = "";
	}
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
		if( confirm('<?= $chglbl ?> <?= $loslbl ?>, <?= $cfmmsg ?>') ){
			document.file.submit();
		}else{
			return;
		}
	}else{
		document.file.submit();
	}
}

// apparently from PHPMyAdmin
function insertAtCursor(myField, myValue) {
	var curPos = myField.scrollTop; 
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
	myField.focus(); 
	myField.scrollTop = curPos;
}
//--></script>

<h1>System Files</h1>

<form method="post" action="<?= $self ?>.php" name="file" enctype="multipart/form-data">
<table class="content" ><tr class="<?= $modgroup[$self] ?>1">
<th width="50"><a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png"></a></th>
<td>
<?php
if($isadmin){
?>

<img src="img/16/cog.png" title="System">
<select name="file" id="file" onchange="ConfirmSubmit('log','cfg');">
<option value=""><?= $edilbl ?> ->
<?php
foreach ($sysfiles as $sf){
	$op = intval(strrpos($sf, "/")/2);
	echo "<option value=\"$sf\"".( ($file == $sf)?" selected":"")." style=\"color: $ocol[$op]\">$sf\n";
	}
?>
</select>

<?php
}

if( preg_match("/net/",$_SESSION['group']) ){
?>
<p>

<img src="img/16/conf.png" title="Device <?= $cfglbl ?>">

<select name="cfg" id="cfg" onchange="ConfirmSubmit('file','log');">
<option value=""><?= $sholbl ?> ->
<?php
$plen = strlen($nedipath);
foreach (glob("$nedipath/conf/*") as $d){
        if (is_dir($d)){
		$cfgd = substr($d,$plen+6);
		echo "<option value=\"\" style=\"color: DarkBlue\">- $cfgd -\n";
		foreach (glob("$d/*.cfg") as $f) {
 			$l = substr($f,strlen($d)+1);
			echo "<option value=\"$cfgd/$l\" ".( ($file == $f)?" selected":"").">$l\n";
		}
	}
}
?>
</select>

<p>
<img src="img/16/note.png" title="<?= $dsclbl ?> <?= $loglbl ?>">

<select name="log" id="log" onchange="ConfirmSubmit('file','cfg');">
<option value=""><?= $sholbl ?> ->
<?php
foreach (glob("/tmp/nedi*") as $f) {
	$l = substr($f,5);
	echo "<option value=\"$l\"".( ($file == $f)?" selected":"").">$l\n";
}
?>
</select>
<?}?>

</td>
<th>

<select name="mde" onchange="if(document.file.mde.selectedIndex == 1){alert('System <?= $cfglbl ?> <?= $delmsg ?>!');}">
<option value="">Task ->
<option value="u" <?= ($mde == "u")?" selected":""?>><?= (($verb1)?"$updlbl NeDi":"NeDi $updlbl") ?> (<?= (($verb1)?"$rpllbl $cfglbl":"$cfglbl $rpllbl") ?>)
<option value="b" <?= ($mde == "b")?" selected":""?>><?= (($verb1)?"$updlbl NeDi":"NeDi $updlbl") ?> (<?= (($verb1)?"$buplbl $cfglbl":"$cfglbl $buplbl") ?>)
<option value="g" <?= ($mde == "g")?" selected":""?>><?= (($verb1)?"$updlbl $imglbl":"$imglbl $updlbl") ?>
<option value="i" <?= ($mde == "i")?" selected":""?>><?= (($verb1)?"$implbl DB":"DB $implbl") ?>
<option value="m" <?= ($mde == "m")?" selected":""?>><?= $upllbl ?>-map
<option value="f" <?= ($mde == "f")?" selected":""?>><?= $upllbl ?>-foto
<option value="l" <?= ($mde == "l")?" selected":""?>><?= $upllbl ?>-log
<option value="t" <?= ($mde == "t")?" selected":""?>><?= $upllbl ?>-tftp
<option value="r" <?= ($mde == "r")?" selected":""?>><?= $dellbl ?> <?= $stco['200'] ?> RRDs
</select>
<p>
<img src="img/16/clip.png" title="<?= $fillbl ?>"> <input name="tgz" type="file" size="20" accept="archive/tar"> 

</th>
<th>

<input type="submit" name="up" value="<?= $cmdlbl ?>">
</td>

</tr></table>
</form>

<?php

if($isadmin){
	if($del){
		if( unlink ($del) ){
			echo "<h5>$dellbl $del OK</h5>";
		}else{
			echo "<h4>$errlbl $dellbl $del</h4>";
		}
	}elseif($wrt and $file){
		$wbytes = file_put_contents($file, preg_replace("/\r/", "", $txt ) );
		if( $wbytes === FALSE ){
			echo "<h4>$errlbl: $wrtlbl $file!</h4>\n";
		}else{
			echo "<h5>$wrtlbl $file ($wbytes bytes) OK</h5>\n";
		}
		if($file == "$nedipath/inc/crontab"){
			system("crontab $file", $fail);
			if($fail){
				echo "<h4>Crontab $updlbl $errlbl</h4>\n";
			}else{
				echo "<h5>Crontab $updlbl OK</h5>\n";
			}
		}elseif($tftpable and $all){
			if(chmod($file,0666) ){
				echo "<h5>$cmdlbl $alllbl $wrtlbl $acslbl OK</h5>";
			}else{
				echo "<h4>$errlbl $wrtlbl $alllbl $acslbl</h4>\n";
			}
		}
	}
}

if($file){
?>

<h2><?= basename($file) ?></h2>

<table class="content">
<tr class="<?= $modgroup[$self] ?>2"><th>
<?php
	$contents = "";
	if (file_exists($file)) {
		$contents = file_get_contents ("$file");
	};

	if($isadmin){
		if($editable or $tftpable){
?>
<form method="post" action="<?= $self ?>.php" name="edit">
<?= $addlbl ?> &nbsp;
<input type="button" value="Tab" OnClick="insertAtCursor(document.edit.txt, '	');";>
<input type="button" value="#" OnClick="insertAtCursor(document.edit.txt, '#');";>
<input type="button" value=";" OnClick="insertAtCursor(document.edit.txt, ';');";>
<input type="button" value="|" OnClick="insertAtCursor(document.edit.txt, '|');";>
<input type="button" value="/" OnClick="insertAtCursor(document.edit.txt, '/');";>
<input type="button" value="$" OnClick="insertAtCursor(document.edit.txt, '$');";>
 -
<?php
			if($tftpable){
?>
<input type="text" name="tftp" value="<?= basename($file) ?>" onfocus="select();" >
<input type="checkbox" name="all" <?= $all ?> title="<?= $alllbl ?> <?= $wrtlbl ?> <?= $acslbl ?>">
<input type="submit" name="wrt" value="<?= $wrtlbl ?> TFTP">
<?php
			}else{
?>
<input type="hidden" name="file" value="<?= $file ?>">
<input type="submit" name="wrt" value="<?= $wrtlbl ?>">
<?php
			}
		}
		if($delable and $contents){
?>
<div style="float:right"><a href="?del=<?= urlencode($file) ?>"><img src="img/16/bcnl.png" onclick="return confirm('<?= $dellbl ?>, <?= $cfmmsg ?>')" title="<?= $dellbl ?>!"></a></div>
<?php
		}
	}
	
?>

<br>
<textarea rows="30" name="txt" cols="120" onChange="chg='1';" class="code">
<?= $contents ?>
</textarea>
</th>
</tr></table>
</form>

<?php
}elseif($isadmin and ($mde == "u" or $mde == "b") and $_SESSION['ver']  != "%VERSION%"){
?>
<h1>NeDi <?= $updlbl ?></h1>
<div class="textpad code txta" name="out">
<?php
	if(array_key_exists('tgz',$_FILES)){
		if(file_exists($_FILES['tgz']['tmp_name'])) {
			if($mde == "b"){
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
					echo "$buplbl seedlist\n";
				}
				if (!copy("$nedipath/agentlist", "/tmp/agentlist")) {
					echo "<h4>$errlbl $wrtlbl /tmp/agentlist</h4>\n";
					die;
				}else{
					echo "$buplbl agentlist\n\n";
				}
			}

			echo system("tar zxf ".$_FILES['tgz']['tmp_name']." -C $nedipath", $stat);
			if($stat){
				echo "<h4>$errlbl $wrtlbl ".$_FILES['tgz']['name']."</h4>\n";
				die;
			}else{
				echo "$wrtlbl ".$_FILES['tgz']['name']."\n\n";
			}

			if($mde == "b"){
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
					echo "$wrtlbl $nedipath/seedlist\n";
				}
				if (!copy("/tmp/agentlist", "$nedipath/agentlist")) {
					echo "<h4>$errlbl $wrtlbl $nedipath/agentlist</h4>\n";
				}else{
					echo "$wrtlbl $nedipath/agentlist\n\n";
				}
			}
			echo "<h5>$updlbl OK</h5>";
			include_once("$nedipath/Readme.txt");
		}else{
			echo "<h4>$errlbl $realbl ".$_FILES['tgz']['name']."</h4>";
		}
	}
?>
</div><br>

<?php
}elseif($isadmin and $mde == "i"){
?>

<h2>NeDi <?= $implbl ?></h2>
<div class="textpad code txta" name="out">
<?php
	if(array_key_exists('tgz',$_FILES)){
		if(file_exists($_FILES['tgz']['tmp_name'])) {
			echo "<h5>$realbl ".$_FILES['tgz']['name']."</h5>\n";
			echo system("zcat ".$_FILES['tgz']['tmp_name']." | mysql $dbname --user=$dbuser ".(($dbpass)?"--password=$dbpass":""), $stat);
			if($stat){
				echo "<h4>$errlbl $wrtlbl ".$_FILES['tgz']['name']."</h4>\n";
			}else{
				echo "<h5>$implbl OK</h5>\n";
			}
		}else{
			echo "<h4>$errlbl $realbl ".$_FILES['tgz']['name']."</h4>";
		}
	}
?>
</div><br>
<?php
}elseif($isadmin and $mde == "g"){
?>

<h2><?= $imglbl ?> <?= $updlbl ?></h2>
<div class="textpad code txta" name="out">
<?php
	if(array_key_exists('tgz',$_FILES)){
		if(file_exists($_FILES['tgz']['tmp_name'])) {
			echo "<h5>$realbl ".$_FILES['tgz']['name']."</h5>\n";
			echo system("tar zxvf ".$_FILES['tgz']['tmp_name']." -C img", $stat);
			if($stat){
				echo "<h4>$errlbl $wrtlbl ".$_FILES['tgz']['name']."</h4>\n";
			}else{
				echo "<h5>$chglbl OK</h5>";
			}
		}else{
			echo "<h4>$errlbl $realbl ".$_FILES['tgz']['name']."</h4>";
		}
	}
?>
</div><br>
<?php
}elseif($mde == "l" or $mde == "t" or $mde == "m" or $mde == "f"){
?>
<h2><?= $fillbl ?> <?= $upllbl ?></h2>
<div class="textpad code txta" name="out">
<?php

	$dir = $tftpboot;
	if($mde == "l"){
		$dir = "log";
	}elseif($mde == "m"){
		$dir = "map";
	}elseif($mde == "f"){
		$dir = "foto";
	}
	if(array_key_exists('tgz',$_FILES)){
		if(file_exists($_FILES['tgz']['tmp_name'])) {
			echo "$realbl ".$_FILES['tgz']['name']."\n\n";
			if( rename($_FILES['tgz']['tmp_name'],"$dir/".$_FILES['tgz']['name']) ){
				echo "$wrtlbl \"$dir/".$_FILES['tgz']['name']."\"\n";
				if($mde == "t"){
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

<?php
}elseif($isadmin and $mde == "r"){
?>
<h2>RRDs <?= $updlbl ?> > <?= $retire ?> <?= $tim['d'] ?></h2>
<div class="textpad code txta" name="out">
<?php
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
<?php
}else{
?>

<h2><?= $fillbl ?> <?= $lstlbl ?></h2>

<table class="full fixed">
<tr><td class="helper">

<?FileList('map',"web") ?>

</td><td class="helper">

<?FileList('foto',"web") ?>

</td></tr>
<tr><td class="helper">

<?FileList('log',"web") ?>

</td><td class="helper">

<?FileList($tftpboot,"tftp") ?>

</td></tr>
</table>
<?php
}

//===================================================================
// Return fileicon
function Fimg($f) {
	
	global $hislbl,$fillbl,$imglbl,$cfglbl,$cmdlbl,$mlvl;

	$l  = "";
	$ed = 0;
	if(preg_match("/\.(zip|tgz|tar|gz|7z|bz2|rar)$/i",$f))	{$i = "pkg"; $t = "Archive";}
	elseif(stristr($f,".csv"))				{$i = "list";$t = "CSV $fillbl";}
	elseif(stristr($f,".def"))				{$i = "geom";$t = "Device Definition";$l = "Other-Defgen.php?so=".urlencode(basename($f,".def"));}
	elseif(stristr($f,".log"))				{$i = "note";$t = "$hislbl";}
	elseif(stristr($f,".js"))				{$i = "dbmb";$t = "Javascript";}
	elseif(stristr($f,".php"))				{$i = "php"; $t = "PHP Script";}
	elseif(stristr($f,".patch"))				{$i = "hlth";$t = "System Patch";}
	elseif(stristr($f,".reg"))				{$i = "nwin";$t = "Registry $fillbl";}
	elseif(stristr($f,".sql"))				{$i = "db";  $t = "DB $fillbl";}
	elseif(stristr($f,".xml"))				{$i = "dcub";$t = "XML $fillbl";$ed = 1;}
	elseif(preg_match("/\.(txt|text)$/i",$f))		{$i = "abc"; $t = "TXT $fillbl";$ed = 1;}
	elseif(preg_match("/[.-](cfg|conf|config)$/i",$f))	{$i = "conf";$t = "$cfglbl";$ed = 1;}
	elseif(preg_match("/\.(exe)$/i",$f))			{$i = "cog";$t = "$cmdlbl";}
	elseif(preg_match("/\.(htm|html)$/i",$f))		{$i = "dif";$t = "HTML $fillbl";}
	elseif(preg_match("/\.(pcm|raw)$/i",$f))		{$i = "bell";$t = "Ringtone";}
	elseif(preg_match("/\.(btm|loads)$/i",$f))		{$i = "nhdd"; $t = "Boot Image";}
	elseif(preg_match("/\.(app|bin|img|sbn|swi|ipe)$/i",$f)){$i = "cbox"; $t = "Binary Image";}
	elseif(preg_match("/\.(bmp|gif|jpg|png|svg)$/i",$f))	{$i = "img";$t = "$imglbl";}
	else							{$i = "bbox";$t = "$mlvl[10]";}

	if($l){
		return array("<a href=\"$l\"><img src=\"img/16/$i.png\" title=\"$t\"></a>",$ed);
	}else{
		return array("<img src=\"img/16/$i.png\" title=\"$t\">",$ed);
	}
}

function FileList($dir,$opt=""){
	
	global $modgroup,$self,$stco,$namlbl,$fillbl,$totlbl,$sizlbl,$updlbl,$dellbl,$edilbl,$cfmmsg,$isadmin;

	echo "<h3>";
	echo "$dir </h3>\n";
?>
<table class="content">
<tr class="<?= $modgroup[$self] ?>1"><th colspan="2">
<?PHP if($opt == "tftp"){ ?>
<span style="float:left;margin:2px 2px">
<form method="post" name="edit" action="System-Files.php">
<input type="hidden" name="tftp" value="my.txt">
<input type="image" src="img/16/star.png" value="Submit" title="<?= $stco['10'] ?>">
</form>
</span>
<?PHP } ?>
<?= $namlbl ?></th><th><?= $sizlbl ?></th><th>
<?= $updlbl ?></th></tr>
<?php
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
			list($ico,$ed) = Fimg($f);
			TblRow($bg);
			echo "<th class=\"$bi\" width=\"20\">$ico</th><td>";
			if($opt == "web"){
				echo "<a href=\"$f\" target=\"window\">$t</a>";
			}else{
				echo "$t\n";
			}
			echo "</td>\n";
			$siz = round(filesize($f) / 1024,1);
			$tsiz += $siz;
			echo "<td align=\"right\">$siz KB</td><td>".date ($_SESSION['date'],filemtime($f));
			if($isadmin){
				echo "<span style=\"float:right\"><a href=\"?del=".urlencode($f)."\"> <img src=\"img/16/bcnl.png\" onclick=\"return confirm('$dellbl $t, $cfmmsg')\" title=\"$dellbl $t!\"></a></span>";
				if($opt == "tftp" and $ed){
?>
<span style="float:right;margin:2px 2px">
<form method="post" name="edit" action="System-Files.php">
<input type="hidden" name="tftp" value="<?= $t ?>">
<input type="image" src="img/16/note.png" value="Submit" title="<?= $edilbl ?>">
</form>
</span>
<?php
				}
			}
			echo "</td></tr>\n";
		}
	}
?>
<tr class="<?= $modgroup[$self] ?>1"><td colspan="4"><?= $row ?> <?= $fillbl ?>, <?= $totlbl ?> <?= $tsiz ?> KB</td></tr>
</table>
<?php
}

include_once ("inc/footer.php");
?>
