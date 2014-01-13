<?php
# Program: Other-Chat.php
# Programmer: Karel Stadler (adapted by Remo Rickli)

$refresh   = 60;
$printable = 1;
$exportxls = 0;

include_once ("inc/header.php");

$_GET = sanitize($_GET);
$msg = isset( $_GET['msg']) ? $_GET['msg'] : "";
$sms = isset( $_GET['sms']) ? $_GET['sms'] : "";

if($isadmin and $msg and $sms){
	$fh = fopen("/var/spool/sms/outgoing/$_SESSION[user]", 'w') or die("Cannot write SMS!");
	fwrite($fh, "To:$sms\n\n$msg\n");
	fclose($fh);
	$msg = "[SMS] $msg";
}

$link = @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
if($msg){
	$query	= GenQuery('chat','i','','','',array('time','user','message'),'',array(time(),$_SESSION['user'],$msg) );
	if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}
}
?>
<h1><?= $usrlbl ?> Chat</h1>

<?php  if( !isset($_GET['print']) ) { ?>

<form method="get" name="dynfrm" action="<?= $self ?>.php">
<table class="content" ><tr class="<?= $modgroup[$self] ?>1">
<th width="50">

<a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png"></a>

</th>
<th>

<img src="img/16/say.png"><input type="text" name="msg" size="80" OnFocus="select();">

<?php
if($isadmin){
	echo "<img src=\"img/16/sms.png\"><select size=\"1\" name=\"sms\">\n<option value=\"\">SMS ->\n";

	$query	= GenQuery('users','s','user,phone','user','',array('phone'),array('regexp'),array('[0-9]+') );
	$res	= @DbQuery($query,$link);
	if($res){
		while( $u = @DbFetchRow($res) ){
			echo "<option value=\"$u[1]\"s>$u[0]\n";
		}
		@DbFreeResult($res);
	}else{
		print @DbError($link);
		die;
	}
	echo "</select>\n";
}
?>

</th>
<th width="50">

<span id="counter"><?= $refresh ?></span>
<img src="img/16/exit.png" title="Stop" onClick="stop_countdown(interval);">

</th>
<th width="80">

<input type="submit" name="create" value="<?= $wrtlbl ?>"></th>
</table></form>
<p>
<?php
}
$query = GenQuery('chat','s','*','time desc',100);
$res   = @DbQuery($query,$link);
if($res){
?>
<h2><?= $msglbl ?></h2>
<table class="content"><tr class="<?= $modgroup[$self] ?>2">
<th width="40"><img src="img/16/user.png"><br>User</th>
<th width="100"><img src="img/16/clock.png"><br><?= $timlbl ?></th>
<th><img src="img/16/say.png"><br><?= $cmtlbl ?></th>
</tr>
<?php
	while( ($m = @DbFetchRow($res)) ){
		if ($_SESSION['user'] == $m[1]){$bg = "txta"; $bi = "imga";$me=1;}else{$bg = "txtb"; $bi = "imgb";$me=0;}
		list($fc,$lc) = Agecol($m[0],$m[0],$me);
		$time = date($datfmt,$m[0]);
		echo "<tr class=\"$bg\"><th class=\"$bi\">" . Smilie($m[1],1);
		echo "</th>\n";
		echo "<td bgcolor=#$fc>$time</td><td>";
		$smsmsg = str_replace('[SMS]','<img src="img/16/sms.png" title="SMS">',$m[2]);
		$lnkmsg = preg_replace('/(http[s]?:\/\/[^\s]*)/',"<a href=\"$1\" target=\"window\">$1</a>",$smsmsg);
		echo "$lnkmsg</td></tr>\n";
	}
	echo "</table>\n";
}

include_once ("inc/footer.php");
?>
