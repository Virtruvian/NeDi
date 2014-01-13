<?php 
//===============================
// SNMPwalk utility.
//===============================

session_start(); 
$debug  = isset($_GET['d']) ? $_GET['d'] : "";
$nedipath = preg_replace( "/^(\/.+)\/html\/.+.php/","$1",$_SERVER['SCRIPT_FILENAME']);			# Guess NeDi path for nedi.conf

if( !preg_match("/net/",$_SESSION['group']) ){
	echo $nokmsg;
	die;
}
include_once ("libmisc.php");
ReadConf('nomenu');
require_once ("libsnmp.php");
require_once ("../languages/$_SESSION[lang]/gui.php");

$_GET = sanitize($_GET);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=<?= $charset ?>">
<link href="../themes/<?= $_SESSION['theme'] ?>.css" type="text/css" rel="stylesheet">
</head>
<body>
<h1><?= $_GET['ip'] ?> <?= $_GET['c'] ?> v<?= $_GET['v'] ?></h1>
<div class="net1">
<h2><img src="../img/32/bdwn.png" hspace="10"> <?= $_GET['oid'] ?></h2>
</div>
<div class="net2 code">
<?
if($_GET['ip'] and $_GET['v'] and $_GET['c'] and $_GET['oid']){
	foreach( Walk($_GET['ip'], $_GET['v'], $_GET['c'], $_GET['oid'], $timeout*300000) as $ix => $val){
			echo substr($ix, strlen($_GET['oid'])+2 ).": <b>$val</b>\n";
	}
}else{
	echo "<h4>$nonlbl IP, version, community, OID?</h4>";
}

?>
</div>
</body>
</html>