<?php
//===============================
// NeDi header.
//===============================

ini_set("memory_limit","64M");										# Enterprise network support

session_start();
$self     = preg_replace("/.*\/(.+).php/","$1",$_SERVER['SCRIPT_NAME']);
#$uripath  = preg_replace("/^(.*\/).+.php/","$1",$_SERVER['SCRIPT_NAME']);				# HTML path on the webserver
#$guipath  = preg_replace( "/^(\/.+)\/.+.php/","$1",$_SERVER['SCRIPT_FILENAME']);			# Path to PHP scripts
$nedipath = preg_replace( "/^(\/.+)\/ht\w+\/.+.php/","$1",$_SERVER['SCRIPT_FILENAME']);			# Guess NeDi path for nedi.conf
$noiplink = 0;												# Disables telnet:// and ssh:// links (to allow browser add-ons) TODO move to Profile?

if( isset($_SESSION['group']) ){
	require_once ("libmisc.php");
	ReadConf($_SESSION['group']);
	$mos   = split("-", $self);
	$selfi = $mod[$mos[0]][$mos[1]];

}else{
	echo "<script>document.location.href='index.php?goto=".rawurlencode($_SERVER["REQUEST_URI"])."';</script>\n";
	die;
}
include_once ("./languages/$_SESSION[lang]/gui.php");							# Don't require, GUI still works if missing
include_once ("libdb-" . strtolower($backend) . ".php");

$datfmt = $_SESSION['date'];										# TODO replace datfmt with Session...
$now    = date($_SESSION['date']);
$isadmin= (preg_match("/adm/",$_SESSION['group']) )?1:0;
$debug  = (isset($_GET['debug']) and $isadmin)? $_GET['debug'] : "";

if( isset($_GET['print']) ){
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<link href="inc/print.css" type="text/css" rel="stylesheet">

<body>
<div id="header" class="<?=$modgroup[$self]?>1">
<div style="float:right"><img src="img/32/<?=$selfi?>.png"></div>

<img src="img/n.png" onClick="window.print();">
<?=$_SERVER['SERVER_NAME']?>
</div>
<?
}else{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
<title>NeDi <?=$self?></title>
<meta http-equiv="Content-Type" content="text/html;charset=<?=$charset?>">
<link href="themes/<?=$_SESSION['theme']?>.css" type="text/css" rel="stylesheet">
<link rel="shortcut icon" href="img/favicon.ico">

<script language="JavaScript" src="inc/JSCookMenu.js"></script>
<script language="JavaScript" src="inc/menutheme.js"></script>

<?=(isset($nocache))?"<meta http-equiv=\"cache-control\" content=\"no-cache\">\n":""?>
<?=(isset($calendar))?"<script language=\"JavaScript\" src=\"inc/cal.js\"></script>\n":""?>
</head>

<?
if( isset($refresh) ){
?>
<body onload="countdown()">
<script language="javascript">
var interval = "";
var secs = <?=$refresh-1?>;

function countdown(){
	interval = window.setInterval("tick()",1000);
}

function tick(){
	var theElement = document.getElementById("counter");
	theElement.innerHTML = secs--;
	if (secs < 9){
		document.getElementById("counter").setAttribute("class", "drd");
	}
	if (secs == -1) {
		document.dynfrm.submit();
	}
}

// Idea richard.lajaunie
function stop_countdown(id){
	window.clearInterval(id);
	document.getElementById('counter').setAttribute('class', 'gry');
}

</script>
<?}else{?>
<body>
<?}?>
<table id="header">
<tr class="<?=$modgroup[$self]?>1">
<th width="50"><a href="http://www.nedi.ch"><img src="img/n.png"></a></th>
<td ID="MainMenuID"></td>
<th width="100">
<img src="img/16/ring.png" title="Help" onclick="window.open('<?="languages/$_SESSION[lang]/$self.html"?>','Help','width=640,height=480,scrollbars');">
<?if($printable){?>
<img src="img/dev/pgan.png" width="16" title="Print" onclick="window.open('?<?=$_SERVER['QUERY_STRING']?>&print','Help','width=1000,height=800,scrollbars');">
<?}
if($isadmin){?>
<img src="img/16/cog.png" onclick="document.location.href='?<?=$_SERVER['QUERY_STRING']?>&debug=1';" title="Debug Info">
<?}?>
</th>

<th width="80">
<?
if($isadmin){
	#echo "<img src=\"img/16/loko.png\" title=\"Administrator\">";
}elseif($_SESSION['view']){
	#echo "<img src=\"img/16/lokc.png\" title=\"$seclbl $stco[100]\">";
}?>
<?=$_SESSION['user']?></th></tr></table>
<script language="JavaScript"><!--
var mainmenu = [
<?
	foreach (array_keys($mod) as $m) {
		echo "	[null,'$m',null,null,null,\n";
		foreach ($mod[$m] as $s => $i) {
			echo "		['<img src=./img/16/$i.png>','$s','$m-$s.php',null,null],\n";
		}
		echo "	],\n";
	}
?>
];
cmDraw ('MainMenuID', mainmenu, 'hbr', cmThemeN, 'ThemeN');

function pop(URL,LBL){
	win = window.open("","Image",'location=no,toolbar=no,titlebar=no,status=no,resizable=1,width=640,height=480');
	win.document.write('<head><title>'+LBL+'</title></head><body style="margin:0;" onClick="window.close()"> <img src='+URL+' alt="Click to close" width="100%" height="100%" onClick="self.close();" ></body>');
}
--></script>
<p>
<?
	if($debug){
		echo "<div class=\"textpad code good\">Self:	$self\n";
		echo "Npath:	$nedipath\n";
		echo "DB:	$dbhost,$dbuser,$dbname\n";
		echo "User:	$_SESSION[lang], $_SESSION[theme]\n";
		echo "Now:	$now (Format $_SESSION[date])\n";
		echo "View:	$_SESSION[view]</div>\n";

		echo "<div class=\"textpad code alrm\">SERVER:";
		print_r($_SERVER);
		echo "</div>\n";
	}

	if( strpos($_SESSION['group'],$modgroup[$self]) === false){
		echo $nokmsg;
		die;
	}
}
?>
