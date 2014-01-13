<?
# Program: User-Profile.php
# Programmer: Remo Rickli

error_reporting(E_ALL ^ E_NOTICE);

$printable = 1;

$msgfile   = "log/msg.txt";

include_once ("inc/header.php");

$name = $_SESSION['user'];
$_POST = sanitize($_POST);
$msg = isset($_POST['msg']) ? $_POST['msg'] : "";

$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
if(isset($_POST['up']) ){
	if($_POST['pass'] AND $_POST['ackpass']){
		if($_POST['pass'] == $_POST['ackpass']){
			$pass = md5( $_POST['pass'] );
			$query	= GenQuery('users','u',"user=\"$name\"",'','',array('password'),array('='),array($pass) );
			if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>Password $updlbl OK</h5>";}
		}else{
			echo "<h4>Password: $mullbl $vallbl!</h4>";
		}
	}
	$graphs = $_POST['gsiz'] + ($_POST['gbit']?8:0) + ($_POST['gfar']?16:0) + ($_POST['olic']?32:0) + ($_POST['gmap']?64:0);
	$query	= GenQuery('users','u',"user=\"$name\"",'','',array('email','phone','comment','language','theme','volume','columns','msglimit','graphs','dateformat'),array(''),array($_POST['email'],$_POST['phone'],$_POST['cmt'],$_POST['lang'],$_POST['theme'],$_POST['vol'],$_POST['col'],$_POST['lim'],$graphs,$_POST['date']) );
	if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$name $updlbl OK</h5>";}
	$_SESSION['lang'] = $_POST['lang'];
	$_SESSION['theme']= $_POST['theme'];
	$_SESSION['vol']  = $_POST['vol'];
	$_SESSION['col']  = $_POST['col'];
	$_SESSION['olic']  = $_POST['olic'];
	$_SESSION['lim']  = $_POST['lim'];
	$_SESSION['gsiz'] = $_POST['gsiz'];
	$_SESSION['gbit'] = $_POST['gbit'];
	$_SESSION['gfar'] = $_POST['gfar'];
	$_SESSION['gmap'] = $_POST['gmap'];
	$_SESSION['date'] = $_POST['date'];
}
$query	= GenQuery('users','s','*','','',array('user'),array('='),array($name) );
$res	= @DbQuery($query,$link);
$uok	= @DbNumRows($res);
if ($uok == 1) {
	$usr = @DbFetchRow($res);
}else{
	echo "<h4>No user $usrlbl!?!! ($uok $vallbl)</h4>";
	die;
}
?>
<h1><?=$usrlbl?> Profile</h1>
<form method="post" action="<?=$self?>.php" name="pro">
<table class="content"><tr class="<?=$modgroup[$self]?>1">
<th width="50"><a href="<?=$self?>.php"><?=Smilie($usr[0])?></a>
<br><?=$name?></th>
<td valign="top">
<h3><?=$stalbl?> / Password</h3>
<img src="img/16/star.png" title="<?=$usrlbl?> <?=$addlbl?>"> <?=date($datfmt,$usr[5])?>
<p>
<img src="img/16/eyes.png" title="<?=$laslbl?>"> <?=date($datfmt,$usr[6])?>
<p>
<img src="img/16/loko.png" title="<?=$chglbl?>">
<input type="password" name="pass" size="10"><p>
<img src="img/16/lokc.png" title="<?=$acklbl?>">
<input type="password" name="ackpass" size="10">

</td>
<td valign="top">

<h3><?=$grplbl?> / <?=$conlbl?> Info</h3>
<div align="center">
<img src="img/16/<?=($usr[2] &  1)?"ucfg":"bcls"?>.png" title="Admin">
<img src="img/16/<?=($usr[2] &  2)?"net":"bcls"?>.png" title="<?=$netlbl?>">
<img src="img/16/<?=($usr[2] &  4)?"ring":"bcls"?>.png" title="Helpdesk">
<img src="img/16/<?=($usr[2] &  8)?"bino":"bcls"?>.png" title="Monitoring">
<img src="img/16/<?=($usr[2] & 16)?"umgr":"bcls"?>.png" title="Manager">
<img src="img/16/<?=($usr[2] & 32)?"ugrp":"bcls"?>.png" title="<?=$mlvl['10']?>">
</div>
<p>
<img src="img/16/sms.png" title="Mobile #">
<input type="text" name="phone" size="20" value="<?=$usr[4]?>" >
<p>
<img src="img/16/mail.png" title="Email <?=$adrlbl?>">
<input type="text" name="email" size="20" value="<?=$usr[3]?>" >
<p>
<img src="img/16/say.png" title="<?=$cmtlbl?>">
<input type="text" name="cmt" size="20" value="<?=$usr[7]?>" >

</td>
<td valign="top">

<h3><?=$frmlbl?></h3>
<img src="img/16/list.png"  title="<?=$collbl?> <?=$optlbl?>">
<input type="checkbox" name="olic" <?=($_SESSION['olic'])?"checked":""?>>
<br>
<img src="img/16/map.png"  title="Google Maps">
<input type="checkbox" name="gmap" <?=($_SESSION['gmap'])?"checked":""?>>
<p>
<img src="img/16/form.png" title="# <?=$msglbl?>, Vlans, Modules...">
<input type="text" name="lim" size="2" value="<?=$_SESSION['lim']?>">
<p>
<img src="img/16/icon.png" title="# <?=$collbl?>">
<input type="text" name="col" size="2" value="<?=$_SESSION['col']?>">

</td>
<td valign="top">

<h3><?=$place['r']?></h3>
<img src="img/16/home.png"  title="<?=$tmplbl?> Fahrenheit">
<input type="checkbox" name="gfar" <?=($_SESSION['gfar'])?"checked":""?>>
<p>
<a href="http://php.net/manual/en/function.date.php" target="window"><img src="img/16/date.png" title="<?=$timlbl?> <?=$frmlbl?>"></a>
<input type="text" name="date" size="6" value="<?=$_SESSION['date']?>" >
<p>
<img src="img/16/say.png" title="Language">
<select name="lang">
<?
if ($dh = opendir("languages")) {
	while (($f = readdir($dh)) !== false) {
		if($f != "." && $f != ".."){
			echo "<OPTION VALUE=\"$f\" ".(($_SESSION['lang'] == $f)?"selected":"").">$f\n";
		}
	}
	closedir($dh);
}
?>
</select>
<p>
<img src="img/16/paint.png" title="Theme">
<select name="theme">
<?
foreach (glob("themes/*.css") as $f) {
	$t = substr($f, 7, strpos($f, ".css") -7);
	echo "<OPTION VALUE=\"$t\" ".(($_SESSION['theme'] == $t)?"selected":"").">$t\n";
}
?>
</select>

</td>
<td valign="top">

<h3>Monitoring</h3>
<img src="img/16/bbup.png"  title="<?=$trflbl?> <?=$sholbl?> Bit/s">
<input type="checkbox" name="gbit" <?=($_SESSION['gbit'])?"checked":""?>>
<p>
<img src="img/16/bell.png" title="Volume">
<select size="1" name="vol">
<option value="0"> -
<option value="5"<?=( ($_SESSION['vol'] == "5")?" selected":"")?>>5 %
<option value="10"<?=( ($_SESSION['vol'] == "10")?" selected":"")?>>10 %
<option value="50"<?=( ($_SESSION['vol'] == "50")?" selected":"")?>>50 %
<option value="100"<?=( ($_SESSION['vol'] == "100")?" selected":"")?>>100 %
</select>
<p>
<img src="img/16/grph.png"  title="<?=$gralbl?>">
<select size="1" name="gsiz">
<option value=""><?=$nonlbl?>
<option value="2"<?=( ($_SESSION['gsiz'] == "2")?" selected":"")?>><?=$siz['s']?>
<option value="3"<?=( ($_SESSION['gsiz'] == "3")?" selected":"")?>><?=$siz['m']?>
<option value="4"<?=( ($_SESSION['gsiz'] == "4")?" selected":"")?>><?=$siz['l']?>
</select>
</td>
<th width="80"><input type="submit" name="up" value="<?=$updlbl?>"></th>
</tr></table></form>
<p>
<?
if($usr[2]){
	if(isset($_POST['cam']) ){
		unlink($msgfile);
	}elseif(isset($_POST['sam']) ){
		$fh = fopen($msgfile, 'w') or die("Cannot write $msgfile!");
		fwrite($fh, "$msg");
		fclose($fh);
	}
	if(isset($_GET['eam']) ){
?>
<p>
<form method="post" action="<?=$self?>.php" name="ano">
<table class="content">
<tr class="warn">
<th width="80">
<input type="button" value="Bold" OnClick='document.ano.msg.value = document.ano.msg.value + "<b></b>"';>
<p>
<input type="button" value="Italic" OnClick='document.ano.msg.value = document.ano.msg.value + "<i></i>"';>
<p>
<input type="button" value="Pre" OnClick='document.ano.msg.value = document.ano.msg.value + "<pre></pre>"';>
<p>
<input type="button" value="Break" OnClick='document.ano.msg.value = document.ano.msg.value + "<br>\n"';>
<p>
<input type="button" value="Title" OnClick='document.ano.msg.value = document.ano.msg.value + "<h2></h2>\n"';>
<p>
<input type="button" value="List" OnClick='document.ano.msg.value = document.ano.msg.value + "<ul>\n<li>\n<li>\n</ul>\n"';>
</th><th>
<textarea rows="16" name="msg" cols="100">
<?
	if (file_exists($msgfile)) {
		readfile($msgfile);
	};
?>
</textarea>
</th>
<th width="80">
<input type="submit" name="cam" value="<?=$dellbl?>">
<p>
<input type="submit" name="sam" value="<?=$wrtlbl?>">
</th></table>
<?
	}else{
		$editam = "<a href=\"?eam=1\"><img src=\"img/16/note.png\" title=\"$chglbl\"></a>";
	}
}
if (file_exists($msgfile)) {
	echo "<h2>$editam Admin $msglbl</h2><div class=\"textpad warn\">\n";
	include_once ($msgfile);
	echo "</div><br>";
}
?>
<p>
<?
$query = GenQuery('chat','s','*','time desc',$_SESSION['lim']);
$res   = @DbQuery($query,$link);
$nchat= @DbNumRows($res);
if($nchat){
?>
<p>
<h2>
<a href="User-Chat.php"><img src="img/16/say.png" title="Chat"></a>
<?=(($verb1)?"$laslbl Chat":"Chat $laslbl")?></h2>
<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th width="40"><img src="img/16/user.png"><br>User</th>
<th width="120"><img src="img/16/clock.png"><br><?=$timlbl?></th>
<th><img src="img/16/say.png"><br><?=$cmtlbl?></th>
</tr>
<?
	while( ($m = @DbFetchRow($res)) ){
		if ($_SESSION['user'] == $m[1]){$bg = "txta"; $bi = "imga";$me=1;}else{$bg = "txtb"; $bi = "imgb";$me=0;}
		list($fc,$lc) = Agecol($m[0],$m[0],$me);
		$time = date($datfmt,$m[0]);
		echo "<tr class=\"$bg\"><th class=\"$bi\">" . Smilie($m[1],1);
		echo "</th>\n";
		echo "<td bgcolor=#$fc>$time</td><td>$m[2]</td></tr>\n";
	}
	echo "</table>\n";
}

include_once ("inc/footer.php");
?>
