<?
# Program: Other-Info.php
# Programmer: Remo Rickli

$printable = 1;

include_once ("inc/header.php");
?>

<h1>Information</h1>

<table class="content"><tr class="<?=$modgroup[$self]?>1">
<th width="50"><a href="<?=$self?>.php"><img src="img/32/<?=$selfi?>.png"></a></th>
<td>

	<table class="full">
	<tr class="<?=$modgroup[$self]?>2">
	<th>Language: <?=$_SESSION['lang'] ?></th>
	<th>Theme: <?=$_SESSION['theme']?></th>
	<th>List Col Optimize: <?=$_SESSION['olic']?></th>

	</tr>
	<tr class="<?=$modgroup[$self]?>2">

	<th>Volume: <?=$_SESSION['vol']  ?></th>
	<th>Table Columns: <?=$_SESSION['col']  ?></th>
	<th>Message Limit: <?=$_SESSION['lim']  ?></th>

	</tr>
	<tr class="<?=$modgroup[$self]?>2">

	<th>Graphsize: <?=$_SESSION['gsiz'] ?></th>
	<th>Graph Bit/s: <?=$_SESSION['gbit'] ?></th>
	<th>Graph Fahrenheit: <?=$_SESSION['gfar'] ?></th>

	</tr></table>
</td>
</tr></table>
<p>
<? phpinfo(); ?>

<style type="text/css">
td, th { border: 1px solid #000000; font-size: 100%; vertical-align: baseline;}
</style>
