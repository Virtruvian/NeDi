<?php
session_start(); 

if(session_is_registered('group')){
	session_unregister('group');
	session_unregister('user');
	session_unregister('theme');
	session_unregister('lang');
	session_destroy();
}
echo "<script>document.location.href='index.php';</script>\n";

?>
