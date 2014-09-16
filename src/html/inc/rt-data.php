<?php

session_start();
if( !preg_match("/net/",$_SESSION['group']) ){
	echo ":-P";
	die;
}

require_once ("libsnmp.php");
include_once ("libmisc.php");
$_GET = sanitize($_GET);
$ver  = $_GET['v'] & 3;

if($ver == 3){												# Need to load credentials for SNMPv3
	$nedipath  = preg_replace( "/^(\/.+)\/ht\w+\/.+.php/","$1",$_SERVER['SCRIPT_FILENAME']);	# Guess NeDi path for nedi.conf
	ReadConf();
}

if($_GET['ip'] and $ver and $_GET['c'] and $_GET['t'] and $_GET['i']){
	$uhc = (($_GET['v'] & 192) == 128)?1:0;
	if($_GET['t'] == 't'){
		$ioctO = ($uhc)?'1.3.6.1.2.1.31.1.1.1.6':'1.3.6.1.2.1.2.2.1.10';				# 128=HC, 64=Merge with 32bit thus resort to 32bit as occasional drops (e.g. on 10G router IF) are better than a flatline.
		$ooctO = ($uhc)?'1.3.6.1.2.1.31.1.1.1.10':'1.3.6.1.2.1.2.2.1.16';
	# I don't understand why PHP sometimes returns the types as well....only sometimes?!??!?
		$ic = preg_replace("/Counter[0-9]{2}: /","",Get($_GET['ip'], $ver, $_GET['c'], "$ioctO.$_GET[i]",3000000));
		$oc = preg_replace("/Counter[0-9]{2}: /","",Get($_GET['ip'], $ver, $_GET['c'], "$ooctO.$_GET[i]",3000000));
	}elseif($_GET['t'] == 'u'){
		$ic = preg_replace("/Counter[0-9]{2}: /","",Get($_GET['ip'], $ver, $_GET['c'], "1.3.6.1.2.1.2.2.1.11.$_GET[i]",3000000));
		$oc = preg_replace("/Counter[0-9]{2}: /","",Get($_GET['ip'], $ver, $_GET['c'], "1.3.6.1.2.1.2.2.1.17.$_GET[i]",3000000));
	}elseif($_GET['t'] == 'e'){
		$ic = preg_replace("/Counter[0-9]{2}: /","",Get($_GET['ip'], $ver, $_GET['c'], "1.3.6.1.2.1.2.2.1.14.$_GET[i]",3000000));
		$oc = preg_replace("/Counter[0-9]{2}: /","",Get($_GET['ip'], $ver, $_GET['c'], "1.3.6.1.2.1.2.2.1.20.$_GET[i]",3000000));
	}elseif($_GET['t'] == 'd'){
		$ic = preg_replace("/Counter[0-9]{2}: /","",Get($_GET['ip'], $ver, $_GET['c'], "1.3.6.1.2.1.2.2.1.13.$_GET[i]",3000000));
		$oc = preg_replace("/Counter[0-9]{2}: /","",Get($_GET['ip'], $ver, $_GET['c'], "1.3.6.1.2.1.2.2.1.19.$_GET[i]",3000000));
	}elseif($_GET['t'] == 'b'){
		$ic = preg_replace("/Counter[0-9]{2}: /","",Get($_GET['ip'], $ver, $_GET['c'], "1.3.6.1.2.1.31.1.1.1.3.$_GET[i]",3000000));
		$oc = preg_replace("/Counter[0-9]{2}: /","",Get($_GET['ip'], $ver, $_GET['c'], "1.3.6.1.2.1.31.1.1.1.5.$_GET[i]",3000000));
	}elseif($_GET['t'] == 'm'){
		$ic = preg_replace("/Counter[0-9]{2}: /","",Get($_GET['ip'], $ver, $_GET['c'], "1.3.6.1.2.1.31.1.1.1.2.$_GET[i]",3000000));
		$oc = preg_replace("/Counter[0-9]{2}: /","",Get($_GET['ip'], $ver, $_GET['c'], "1.3.6.1.2.1.31.1.1.1.4.$_GET[i]",3000000));
	}
	echo microtime(true)."|$ic|$oc";
}
?>
