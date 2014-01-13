<p>
<div id="footer" class="<?=$modgroup[$self]?>1">
<?
if( isset($_GET['print']) or isset($_GET['xls']) ){
	echo "$_SESSION[user], $now";
}else{
?>
&copy; 2001-2012 Remo Rickli & contributors
<?
}
?>
</div>
</body>
</html>
