<?php
# Program: Other-Info.php
# Programmer: Remo Rickli

$printable = 1;
$exportxls = 0;

include_once ("inc/header.php");
?>

<h1>Information</h1>

<table class="content"><tr class="bgmain">
<th width="50"><a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png" title="<?= $self ?>"></a>
</th>
<td>

	<table class="full">
	<tr class="bgsub">
	<th>Language: <?= $_SESSION['lang'] ?></th>
	<th>Theme: <?= $_SESSION['theme'] ?></th>
	<th><?= $optlbl ?>: <?= $_SESSION['opt'] ?></th>

	</tr>
	<tr class="bgsub">

	<th>Volume: <?= $_SESSION['vol']  ?></th>
	<th><?= $collbl ?>: <?= $_SESSION['col']  ?></th>
	<th><?= $limlbl ?>: <?= $_SESSION['lim']  ?>/<?= $_SESSION['lsiz']  ?></th>

	</tr>
	<tr class="bgsub">

	<th><?= $gralbl ?> <?= $sizlbl ?>: <?= $_SESSION['gsiz'] ?></th>
	<th><?= $trflbl ?> Bit/s: <?= $_SESSION['gbit'] ?></th>
	<th>Fahrenheit: <?= $_SESSION['far'] ?></th>

	</tr></table>
</td>
</tr></table>
<p>
<div class="textpad txta tqrt" id="phpinfo">
<?php

// Fixed CSS issues, with help from php.net: 
ob_start () ;
phpinfo () ;
$pinfo = ob_get_contents () ;
ob_end_clean () ;

// the name attribute "module_Zend Optimizer" of an anker-tag is not xhtml valide, so replace it with "module_Zend_Optimizer"
echo ( str_replace ( "module_Zend Optimizer", "module_Zend_Optimizer", preg_replace ( '%^.*<body>(.*)</body>.*$%ms', '$1', $pinfo ) ) ) ;

?>
</div>
<style type="text/css">
td, th { border: 1px solid #000000; font-size: 100%; vertical-align: baseline;}
</style>
