<?php
# Program: Nodes-Toolbox.php
# Programmer: Eli Gill, modifications by Remo Rickli, additional support Marco Rauchenstein

$printable = 1;
$exportxls = 1;


include_once ("inc/header.php");

$_GET		= sanitize($_GET);
$dest		= isset($_GET['Dest']) ? preg_replace('/[^\w+\/\.-:]/','',$_GET['Dest']) : $_SERVER['REMOTE_ADDR'];
$ping_count	= isset($_GET['Count']) ? preg_replace('/[^\d+]/','',$_GET['Count']) : 3;
$ping_size	= isset($_GET['Size']) ? preg_replace('/[^\d+]/','',$_GET['Size']) : 32;
$do		= isset($_GET['Do']) ? $_GET['Do'] : '';

?>
<h1><?= $netlbl ?> Tool Box</h1>

<?php  if( !isset($_GET['print']) ) { ?>

<form method="get" action="<?= $self ?>.php" name="nettools">
<table class="content" ><tr class="bgmain">
<th width="50"><a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png" title="<?= $self ?>"></a>
</th>
<th>
<a href="log/kitty.exe"><img src="img/16/kons.png" title="Kitty"></a>
<a href="log/iperf.exe"><img src="img/16/tap.png" title="Iperf"></a>
</th><th>
<?= $dstlbl ?> <input type="text" name="Dest" value="<?= $dest ?>" class="m">
<?= $qtylbl ?> <input type="number" name="Count" value="<?= $ping_count ?>" class="s">
<?= $sizlbl ?> <input type="number" name="Size" value="<?= $ping_size ?>" class="s">
<input type="submit" class="button" value="Lookup" name="Do"/>
<input type="submit" class="button" value="Ping" name="Do"/>
<input type="submit" class="button" value="Ping Range" name="Do"/>
<input type="submit" class="button" value="Traceroute" name="Do"/>
<input type="submit" class="button" value="Scan" name="Do"/>
</th>
</table>
</form>

<?php
}
session_write_close();
ob_end_flush();
if($do == "Ping"){
	$ping = ( strpos($dest,':') )?'ping6':'ping';
	echo "<h2>$ping_count Ping(s) with $ping_size bytes to $dest</h2><div class=\"textpad code txtb half\">";
	if(preg_match("/OpenBSD|Linux/",PHP_OS) ){
		system("$ping -c $ping_count -s $ping_size $dest");
	}elseif(preg_match("/^win/",PHP_OS) ){
		system("$ping -n $ping_count -l $ping_size $dest");
	}
}elseif($do == "Lookup"){
	echo "<h2>DNS Lookup $dest</h2><div class=\"textpad code txtb tqrt\">";
	system("nslookup $dest");
	echo "</div><br><p><h2>Whois $dest</h2><div class=\"textpad code txtb tqrt\">";
	system("whois $dest");
}elseif($do == "Scan"){
	echo "<h2>Nmap Scan of $dest</h2><div class=\"textpad code txtb\">";
	system("nmap -sSU -F $dest");
}elseif($do == "Ping Range"){
	echo "<h2>Nmap Ping Range of $dest</h2><div class=\"textpad code txtb tqrt\">";
	system("nmap -sP $dest");
}elseif($do == "Traceroute"){
	echo "<h2>Traceroute to $dest</h2><div class=\"textpad code txtb half\">";
	if(preg_match("/OpenBSD|Linux/",PHP_OS) ){
		system("traceroute $dest");
	}elseif(preg_match("/^win/",PHP_OS) ){
		system("tracert $dest");
	}
}else{
# Based on Steffen's idea
?>
<h2><img src="img/32/nwin.png"> Windows <?= $cfglbl ?></h2>

<div class="textpad txtb tqrt">

<h3>Kitty & Firefox</h3>
<div class="txta code">

<?= $sellbl ?> telnet:// -> Kitty.exe

<b>about:config</b>
network.protocol-handler.app.ssh		STRING "C:\Program Files\Kitty.exe"
network.protocol-handler.external.ssh		BOOL   true
network.protocol-handler.expose.ssh		BOOL   true (false seems to work better now??!)
network.protocol-handler.warn-external.ssh	BOOL   false
</div>

<h3>Kitty & IE</h3>
<div class="txta code">
<?= $cmdlbl ?> kitty.exe -sshhandler || <?= $cmdlbl ?> <a href="log/telnet-ssh-kitty.reg">telnet-ssh-kitty.reg</a>
</div>

<h3>Radmin</h3>
<div class="txta code">
<b>radminlink.reg</b>
REGEDIT4

[HKEY_CLASSES_ROOT\radmin]
@="URL:radmin Protocol"
"URL Protocol"=""

[HKEY_CLASSES_ROOT\radmin\shell]

[HKEY_CLASSES_ROOT\radmin\shell\open]

[HKEY_CLASSES_ROOT\radmin\shell\open\command]
@="\"C:\\Program Files\\admin Viewer 3.0\\RADMINlink.bat\" \"%1\""
<hr>
<b>radminlink.bat</b>
@ECHO OFF

SET HOSTIP=%1
echo Uebergabewert vom Browser: %HOSTIP%
SET HOSTIP=%HOSTIP:~10,-2%
echo Herausgefilterte IP: %HOSTIP%

"C:\Program Files\Radmin Viewer 3.0\Radmin.exe" /connect:%HOSTIP%
</div>

</div>
<br>

<h2><img src="img/32/nlin.png"> *nix <?= $cfglbl ?></h2>

<div class="textpad txtb tqrt">

<h3>Xterm & Firefox</h3>

<div class="txta code">

<b>about:config</b>
network.protocol-handler.expose.ssh;false
network.protocol-handler.expose.telnet;false
<hr>

<b>cli.sh</b>
#!/bin/sh

case "$1" in
   *telnet*)
   xterm -e telnet `echo $1 | sed -e "s/telnet:\/\///"`
   ;;
   *ssh*)
      xterm -e ./ssh.sh `echo $1 | sed -e "s/ssh:\/\///"`
   ;;
esac
<hr>

<b>ssh.sh</b>
#!/bin/sh

echo -n "username:"
read name
ssh -l $name $1
<hr>

chmod 755 cli.sh
chmod 755 ssh.sh

<?= $sellbl ?> telnet:// & ssh:// -> cli.sh
</div>

<?php
}
?>
</div>
<br><p>
<?php
include_once ("inc/footer.php");
?>
