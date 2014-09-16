<?php 
//===============================
// Browse Device Icon
//===============================
session_start(); 
require_once ('libmisc.php');
require_once ("../languages/$_SESSION[lang]/gui.php");

if( !preg_match("/net/",$_SESSION['group']) ){
	echo $nokmsg;
	die;
}
$_GET = sanitize($_GET);
if( isset($_GET['t']) and $_GET['t'] == 'p'){
	$dir = '../img/panel';
	$flt = 'gen-';
	$suf = '.jpg';
	$txt = 'typ';
	$sst = 4;
	$sse = 3;
}else{
	$dir = '../img/dev';
	$flt = '.png';
	$suf = '.png';
	$txt = 'ico';
	$sst = 0;
	$sse = 2;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=<?= $charset ?>">
	<link href="../themes/<?= $_SESSION['theme'] ?>.css" type="text/css" rel="stylesheet">
	<script language="JavaScript">
	function update(img){
		opener.document.bld.<?= $txt ?>.value=img;
		self.close();
	}
	</script>
</head>

<body>
	<div class="genpad">
	<?= $imglbl ?><a href="http://www.nedi.ch/expand" target="Window"> <?= $inflbl ?></a>
<?php

if ( $handle = opendir($dir) ){
	while (false !== ($f = readdir($handle))) {
		if ( stristr($f, $flt) ){
			$icon[] = $f;
		}
	}
	closedir($handle);
	sort($icon);
	$p = '';
	foreach ($icon as $i){
			$n = str_replace($suf,"",$i);
			$t = substr($i, $sst, $sse);
			if ($t != $p){
				echo "		<p><div class=\"txta b\">$t</div>";
			}
			$p = $t;
			echo "		<img src=$dir/$i title=\"$n\" hspace=\"4\" vspace=\"4\" onClick=\"update('$n');\">\n";

	}
}
?>
	</div>
</body>

</html>
