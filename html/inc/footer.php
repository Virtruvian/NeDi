<p>
<div id="footer" class="<?=$modgroup[$self]?>1">
<?
if( isset($_GET['print']) ){
	echo "$_SESSION[user], $now";
}else{
?>
&copy; 2001-2011 Remo Rickli & contributors
<?
}
?>
</div>
</body>
</html>
