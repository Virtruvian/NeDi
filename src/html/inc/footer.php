<p>

<div id="footer">
<?php
if( isset($_GET['print']) or isset($_GET['xls']) ){
	echo "	$_SESSION[user], $now";
}elseif($debug){
	echo "	$cmdlbl $timlbl ".round(microtime(1) - $debug,2)." $tim[s]";
}else{
	echo "	<span class=\"flft\">$self</span> &copy; 2001-2014 Remo Rickli & contributors\n";
}
?>
</div>

</body>
</html>
