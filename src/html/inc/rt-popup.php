<?php 

session_start();
if( !preg_match("/net/",$_SESSION['group']) ){
	echo $nokmsg;
	die;
}
include_once ("libmisc.php");
include_once ("../languages/$_SESSION[lang]/gui.php");							# Don't require, GUI still works if missing

$_GET = sanitize($_GET);
$t    = isset($_GET['t']) ? $_GET['t'] : 't';
$r    = isset($_GET['r']) ? $_GET['r'] : 5;

$graph = "rt-svg.php?t=$t&r=$r&ip=$_GET[ip]&c=$_GET[c]&v=$_GET[v]&i=$_GET[i]&in=".urlencode($_GET['in']);
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=<?= $charset ?>">
	<link href="../themes/<?= $_SESSION['theme'] ?>.css" type="text/css" rel="stylesheet">
</head>

<body>
<div class="gmain">
	<form method="get" name="iv">
		<img src="../img/16/grph.png">
		<span class="b">
			<?= $_GET['d'] ?>
		</span>
		&nbsp;<?= $typlbl ?>
		
		<input type="hidden" name="ip" value="<?= $_GET['ip'] ?>">
		<input type="hidden" name="c" value="<?= $_GET['c'] ?>">
		<input type="hidden" name="d" value="<?= $_GET['d'] ?>">
		<input type="hidden" name="v" value="<?= $_GET['v'] ?>">
		<input type="hidden" name="i" value="<?= $_GET['i'] ?>">
		<input type="hidden" name="in" value="<?=  $_GET['in'] ?>">
		<select name="t" size="1" title="<?= $typlbl ?>" onchange="this.form.submit();">
			<option value="t" <?= ($t == 't')?"selected":"" ?>><?= $trflbl ?>
			<option value="u" <?= ($t == 'u')?"selected":"" ?>>Unicast
			<option value="b" <?= ($t == 'b')?"selected":"" ?>>Broadcast
			<option value="m" <?= ($t == 'm')?"selected":"" ?>>Multicast
			<option value="e" <?= ($t == 'e')?"selected":"" ?>><?= $errlbl ?>
			<option value="d" <?= ($t == 'd')?"selected":"" ?>><?= $dcalbl ?>
		</select>
		<?= $rptlbl ?>
		
		<select name="r" size="1" onchange="this.form.submit();">
			<option value="1" <?= ($r == 1)?"selected":"" ?>>1
			<option value="3" <?= ($r == 3)?"selected":"" ?>>3
			<option value="5" <?= ($r == 5)?"selected":"" ?>>5
			<option value="30" <?= ($r == 30)?"selected":"" ?>>30
			<option value="60" <?= ($r == 60)?"selected":"" ?>>60
			<option value="300" <?= ($r == 300)?"selected":"" ?>>300
		</select>
		<?= $tim['s'] ?>
		
	</form>
</div>
<object data="<?= $graph ?>" type="image/svg+xml" width="100%" height="90%">
<param name="src" value="<?= $graph ?>" />
Your browser does not support the type SVG! You need to either use Firefox or download the Adobe SVG plugin.
</object>

</body>
</html>
