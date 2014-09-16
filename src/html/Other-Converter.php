<?php
# Program: Other-Converter.php
# Programmer: Remo Rickli

$printable = 1;
$exportxls = 0;

include_once ("inc/header.php");

$_GET = sanitize($_GET);
$txt  = isset($_GET['txt']) ? $_GET['txt'] : "";

if( !isset($_GET['print']) ) {
?>
<h1>Text Converter</h1>
<form method="get" action="<?= $self ?>.php">
<table class="content" ><tr class="bgmain">
<th width="50"><a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png" title="<?= $self ?>"></a>
</th>
<th>
<?= $inflbl ?>: <input type="text" name="txt" value="<?= $txt ?>" class="xl" onfocus="select();">
</th>
<th width="80">
<input type="submit" class="button" value="<?= $sholbl ?>">
</th>
</tr>
</table></form>
<?php
}

?>
<h2>Decimal, ASCII, HEX</h2>
<table class="content fixed" ><tr class="bgsub">
<?php

$ord = preg_split('/\D/', $txt);
for($i=0;$i<count($ord);$i++){
	echo "<td>$i</td>";
}

?>
</tr><tr class="txta code">
<?php

foreach ($ord as $o){
	echo "<td>$o</td>";
}

?>
</tr><tr class="txtb code">
<?php

foreach ($ord as $o){
	if($o > 31 and $o < 122){
		echo "<td>".chr($o)."</td>";
	}else{
		echo "<td></td>";
	}
}

?>
</tr><tr class="txta code">
<?php

foreach ($ord as $o){
	echo "<td>".dechex($o)."</td>";
}
?>

</tr>
</table>

<?php
include_once ("inc/footer.php");
?>
