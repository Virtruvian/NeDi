<?php

session_start();
if( !preg_match("/net/",$_SESSION['group']) ){
	echo $nokmsg;
	die;
}
include_once ("libmisc.php");
include_once ("../languages/$_SESSION[lang]/gui.php");							# Don't require, GUI still works if missing

$_GET = sanitize($_GET);
$debug  = isset($_GET['debug']) ? $_GET['debug'] : "";

$graph = "rt-svg.php?ip=$_GET[ip]&c=$_GET[c]&v=$_GET[v]&i=$_GET[i]&in=".urlencode($_GET[in]);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=<?=$charset?>">
<link href="../themes/<?=$_SESSION['theme']?>.css" type="text/css" rel="stylesheet">
</head>
<body>

<div class="net2">
<h2><img src="../img/16/grph.png" hspace="10"><?=$_GET['t']?> <?=$rltlbl?> <?=$trflbl?></h2>
</div>

<object data="<?=$graph?>" type="image/svg+xml" width="100%" height="80%">
<param name="src" value="<?=$graph?>" />
Your browser does not support the type SVG! You need to either use Firefox or download the Adobe SVG plugin.
</object>

</body>
</html>
