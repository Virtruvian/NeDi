<?
session_start();
if( !preg_match("/net/",$_SESSION['group']) ){
	echo ":-P";
	die;
}

require_once ("libsnmp.php");
include_once ("libmisc.php");
$_GET = sanitize($_GET);
$debug  = isset($_GET['debug']) ? $_GET['debug'] : "";

if($_GET['ip'] and $_GET['v'] and $_GET['c'] and $_GET['i']){
	$ioctO = ($_GET['v'] & 128)?'1.3.6.1.2.1.31.1.1.1.6':'1.3.6.1.2.1.2.2.1.10';
	$ooctO = ($_GET['v'] & 128)?'1.3.6.1.2.1.31.1.1.1.10':'1.3.6.1.2.1.2.2.1.16';
# I don't understand why PHP sometimes returns the types as well....only sometimes?!??!?
	$io = preg_replace("/Counter[0-9]{2}: /","",Get($_GET['ip'], $_GET['v'] & 3, $_GET['c'], "$ioctO.$_GET[i]",3000000));
	$oo = preg_replace("/Counter[0-9]{2}: /","",Get($_GET['ip'], $_GET['v'] & 3, $_GET['c'], "$ooctO.$_GET[i]",3000000));
	echo microtime(true)."|$io|$oo";
}
?>