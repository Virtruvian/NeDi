<?
# Program: System-NeDi.php
# Programmer: Remo Rickli

$printable = 1;

include_once ("inc/header.php");

$_POST = sanitize($_POST);

$mod = isset($_POST['mod']) ? $_POST['mod'] : "h";

$vrb = isset($_POST['vrb']) ? "checked" : "";
$wco = isset($_POST['wco']) ? "checked" : "";
$quk = isset($_POST['quk']) ? "checked" : "";

$dip = isset($_POST['dip']) ? "checked" : "";
$rte = isset($_POST['rte']) ? "checked" : "";
$oui = isset($_POST['oui']) ? "checked" : "";

$ndv = isset($_POST['ndv']) ? "checked" : "";
$ndn = isset($_POST['ndn']) ? "checked" : "";
$fqd = isset($_POST['fqd']) ? "checked" : "";

$sed = isset($_POST['sed']) ? $_POST['sed'] : "";
$opt = isset($_POST['opt']) ? $_POST['opt'] : "";
$bup = isset($_POST['bup']) ? $_POST['bup'] : "";

$usr = isset($_POST['usr']) ? $_POST['usr'] : "";
$psw = isset($_POST['psw']) ? $_POST['psw'] : "";

$cmd = "$nedipath/nedi.pl";

if($mod == "i"){
	$cmd .= " -i $usr $psw";
}elseif($mod == "y"){
	$cmd .= " -y";
}elseif($opt and $mod == "s"){
	$cmd .= (($vrb)?" -v":"")." -s TUFip=$opt";
}elseif($mod == "d"){
	$arg = "";

	if($vrb){$arg .= "v";}
	if($bup == "b"){
		$arg .= "b";
	}elseif($bup == "B"){
		$arg .= "B";
	}

	if($wco){$arg .= "W";}
	if($dip){$arg .= "p";}
	if($rte){$arg .= "r";}
	if($oui){$arg .= "o";}
	if($ndn){$arg .= "n";}
	if($ndv){$arg .= "N";}
	if($fqd){$arg .= "F";}
	if($quk){$arg .= "SrfgtedbpoAO";}
	if($arg){$arg = "-" . $arg;}

	if($sed){
		$cmd .= " $arg -$sed $opt";
	}else{
		$cmd .= " $arg";
	}
}else{
	$cmd .= " --help";
}
$cmd = escapeshellcmd($cmd);
?>
<script language="JavaScript">
<!--
chg = 0;

function ConfirmSubmit(){

	if (document.nedi.mod[4].checked == true){
		if( confirm('NeDi <?=$reslbl?>, <?=$cfmmsg?>?') ){
			document.nedi.submit();
		}else{
			return;
		}
	}
	document.nedi.submit();
}

// rufers idea
function UpCmd(){

	var arg = "";
	if(document.nedi.mod[0].checked){
		if(document.nedi.vrb.checked){arg += "v"}
		if(document.nedi.bup.selectedIndex){arg += document.nedi.bup.options[document.nedi.bup.selectedIndex].value}
		if(document.nedi.wco.checked){arg += "W"}
		if(document.nedi.dip.checked){arg += "p"}
		if(document.nedi.rte.checked){arg += "r"}
		if(document.nedi.oui.checked){arg += "o"}
		if(document.nedi.ndv.checked){arg += "N"}
		if(document.nedi.ndn.checked){arg += "n"}
		if(document.nedi.fqd.checked){arg += "F"}
		if(document.nedi.quk.checked){arg += "SrfgtedbpoAO"}
		if(arg != ""){arg = "-" + arg + " "}
		if(document.nedi.sed.selectedIndex){arg += " -" + document.nedi.sed.options[document.nedi.sed.selectedIndex].value + document.nedi.opt.value}
	}else if(document.nedi.mod[1].checked){
		if(document.nedi.vrb.checked){arg = "-v"}
		arg += " -s ip=" + document.nedi.opt.value;
	}else if(document.nedi.mod[2].checked){
		arg = "-y";
	}else if(document.nedi.mod[3].checked){
		arg = "--help";
	}

		cmd = document.getElementById('cmd');
		cmd.innerHTML = "<?=$nedipath?>/nedi.pl " + arg;
		cmd.style.opacity = 0.6;
}
//--></script>

<h1><?=(($verb1)?"$cmdlbl NeDi":"NeDi $cmdlbl")?></h1>
<form name="nedi" action="<?=$self?>.php" method="post">
<table class="content">
<tr class="<?=$modgroup[$self]?>1">
<th width="50" class="<?=$modgroup[$self]?>1"><a href="<?=$self?>.php"><img src="img/32/<?=$selfi?>.png"></a></th>

<td valign="top">

<h3>
<input type="radio" name="mod" value="d" <?=($mod == "d")?"checked":""?> onchange="UpCmd();"> <?=$dsclbl?>
<input type="radio" name="mod" value="s" <?=($mod == "s")?"checked":""?> onchange="UpCmd();"> Scan IP
<input type="radio" name="mod" value="y" <?=($mod == "y")?"checked":""?> onchange="UpCmd();"> Definitions
<input type="radio" name="mod" value="h" <?=($mod == "h")?"checked":""?> onchange="UpCmd();"> Help
</h3>

<table>
<tr>
<td>
<select size="1" name="sed" onchange="UpCmd();">
<option value="">Seed ->
<option value="a" <?=($sed == "a")?"selected":""?> ><?=$addlbl?> IP
<option value="A" <?=($sed == "A")?"selected":""?> ><?=$addlbl?> DB
<option value="t" <?=($sed == "t")?"selected":""?> >Test IP
</select>
</td>
<td><input type="checkbox" name="vrb" <?=$vrb?> title="<?=(($verb1)?"$sholbl $deslbl":"$deslbl $sholbl")?>" onchange="UpCmd();">Verbose</td>
<td><input type="checkbox" name="wco" <?=$wco?> title="<?=$dsclbl?> Write Community" onchange="UpCmd();"> Write</td>
<td><input type="checkbox" name="quk" <?=$quk?> title="Skip Info" onchange="UpCmd();"> Quick</td>
</tr>

<tr>
<td>
<input type="text" name="opt" value="<?=$opt?>" size="15" title="seed/scan IP" onfocus="select();" onchange="UpCmd();">
</td>
<td><input type="checkbox" name="dip" <?=$dip?> title="LLDP, CDP, FDP, NDP..." onchange="UpCmd();"> Protocol</td>
<td><input type="checkbox" name="rte" <?=$rte?> title="<?=(($verb1)?"$dsclbl Routes":"Routes $dsclbl")?>" onchange="UpCmd();"> Route</td>
<td><input type="checkbox" name="oui" <?=$oui?> title="<?=(($verb1)?"$dsclbl OUI $venlbl":"OUI $venlbl $dsclbl")?>" onchange="UpCmd();"> OUI</td>
</tr>

<tr>
<td>
<select size="1" name="bup" onchange="UpCmd();">
<option value=""><?=$cfglbl?>
<option value="b" <?=($bup == "b")?"selected":""?> >DB <?=$buplbl?>
<option value="B" <?=($bup == "B")?"selected":""?> >DB & <?=$fillbl?>
</select>
</td>
<td><input type="checkbox" name="ndv" <?=$ndv?> title="Devices in Nodes" onchange="UpCmd();"> Node Dev</td>
<td><input type="checkbox" name="ndn" <?=$ndn?> title="<?=$nonlbl?> Node <?=$namlbl?>" onchange="UpCmd();"> No DNS</td>
<td><input type="checkbox" name="fqd" <?=$fqd?> title="Device <?=$namlbl?> & Domain" onchange="UpCmd();"> FQDN</td>
</tr>
</table>

</td><td valign="top">

<h3><input type="radio" name="mod" value="i" <?=($mod == "i")?"checked":""?>> Init</h3>
<table>
	<tr><td>User:</td><td><input type="text" name="usr" size="10"></td>
	<tr><td>Pass:</td><td><input type="password" name="psw" size="10"></td></tr>
</table>

</td>
<th width="80" >

<input type="button" name="go" value="<?=$cmdlbl?>" onClick="ConfirmSubmit();"></th>

</tr></table>
</form>

<h2 id="cmd"><?=$cmd?></h2>
<div class="textpad code txta" name="out">
<?
ob_end_flush();
system("$cmd 2>&1");
?>
</div><br>

<?

include_once ("inc/footer.php");
?>
