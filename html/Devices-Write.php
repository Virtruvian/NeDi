<?php
# Program: Devices-Write.php
# Programmer: Remo Rickli

$printable = 1;
$calendar  = 1;
$exportxls = 0;

include_once ("inc/header.php");
include_once ("inc/libdev.php");

$_POST = sanitize($_POST);
$sta = isset( $_POST['sta']) ? $_POST['sta'] : "";
$stb = isset( $_POST['stb']) ? $_POST['stb'] : "";
$ina = isset( $_POST['ina']) ? $_POST['ina'] : "";
$inb = isset( $_POST['inb']) ? $_POST['inb'] : "";
$opa = isset( $_POST['opa']) ? $_POST['opa'] : "";
$opb = isset( $_POST['opb']) ? $_POST['opb'] : "";
$cop = isset( $_POST['cop']) ? $_POST['cop'] : "";

$cmd = isset( $_POST['cmd']) ? $_POST['cmd'] : "";
$sub = isset( $_POST['sub']) ? $_POST['sub'] : "";
$int = isset( $_POST['int']) ? $_POST['int'] : "";
$sim = isset( $_POST['sim']) ? $_POST['sim'] : "";
$scm = isset( $_POST['scm']) ? $_POST['scm'] : "";
$con = isset( $_POST['con']) ? $_POST['con'] : "";
$pwd = isset( $_POST['pwd']) ? $_POST['pwd'] : "";
$ssub = isset( $_POST['ssub']) ? $_POST['ssub'] : "";
$esub = isset( $_POST['esub']) ? $_POST['esub'] : "";
$sint = isset( $_POST['sint']) ? $_POST['sint'] : 1;
$eint = isset( $_POST['eint']) ? $_POST['eint'] : 1;
$emod = isset( $_POST['emod']) ? $_POST['emod'] : 0;
$smod = isset( $_POST['smod']) ? $_POST['smod'] : 0;
$icfg = isset( $_POST['icfg']) ? $_POST['icfg'] : "";

$cols = array(	"device"=>$namlbl,
		"devip"=>"$manlbl IP",
		"origip"=>"$orilbl IP",
		"serial"=>$serlbl,
		"type"=>$typlbl,
		"services"=>$srvlbl,
		"description"=>$deslbl,
		"devos"=>"Device OS",
		"bootimage"=>"Bootimage",
		"location"=>$loclbl,
		"contact"=>$conlbl,
		"devgroup"=>$grplbl,
		"devmode"=>$modlbl,
		"snmpversion"=>"SNMP $verlbl",
		"community"=>"Community",
		"cliport"=>"CLI $porlbl",
		"login"=>"Login",
		"firstdis"=>"$fislbl $dsclbl",
		"lastdis"=>"$laslbl $dsclbl"
		);
?>
<h1>Device Write</h1>

<form method="post" name="list" action="<?= $self ?>.php">
<table class="content"><tr class="<?= $modgroup[$self] ?>1">
<th width="50" rowspan="3"><a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png"></a></th>
<th valign="top"><?= $cndlbl ?> A<p>
<select size="1" name="ina">
<?php
foreach ($cols as $k => $v){
       echo "<option value=\"$k\"".( ($ina == $k)?" selected":"").">$v\n";
}
?>
</select>
<select size="1" name="opa">
<?php selectbox("oper",$opa) ?>
</select>
<p><a href="javascript:show_calendar('list.sta');"><img src="img/16/date.png"></a>
<input type="text" name="sta" value="<?= $sta ?>" size="20" OnFocus="select();">
</th>
<th valign="top"><?= $cmblbl ?><p>
<select size="1" name="cop">
<?php selectbox("comop",$cop) ?>
</select>
</th>
<th valign="top"><?= $cndlbl ?> B<p>
<select size="1" name="inb">
<?php
foreach ($cols as $k => $v){
       echo "<option value=\"$k\"".( ($inb == $k)?" selected":"").">$v\n";
}
?>
</select>
<select size="1" name="opb">
<?php selectbox("oper",$opb) ?>
</select>
<p><a href="javascript:show_calendar('list.stb');"><img src="img/16/date.png"></a>
<input type="text" name="stb" value="<?= $stb ?>" size="20" OnFocus="select();">
<input type="text" name="sub" value="<?= $sub ?>" size="20" OnFocus="select();" title="Substitutes this search string and use result as command argument">
</th>

</tr>
<tr class="<?= $modgroup[$self] ?>2">

<th valign="top" colspan=2>
<?= $cmdlbl ?> / <?= $cfglbl ?><p>
<textarea rows="6" name="cmd" cols="60"><?= $cmd ?></textarea>
</th>

<th valign="top">Interface <?= $cfglbl ?><p>
<select size="1" name="int">
	<option value=""><?= $sellbl ?> ->
	<option value="Et" <?php if($int == "Et"){echo "selected";} ?>>Ethernet
	<option value="Fa" <?php if($int == "Fa"){echo "selected";} ?>>Fastethernet
	<option value="Gi" <?php if($int == "Gi"){echo "selected";} ?>>Gigabit
	<option value="Te" <?php if($int == "Te"){echo "selected";} ?>>TenGigabit
	<option value="Vi" <?php if($int == "Vi"){echo "selected";} ?>>Vlan IF
	<option value="Vl" <?php if($int == "Vl"){echo "selected";} ?>>Vlan
</select>
 <input type="text" size="2"name="smod" value="<?= $smod ?>" name="smod" OnFocus="select();">
 / <input type="text" size="2" name="sint" value="<?= $sint ?>" OnFocus="select();">
 / <input type="text" size="2" name="ssub" value="<?= $ssub ?>" OnFocus="select();">
 - <input type="text" size="2" name="emod" value="<?= $emod ?>" OnFocus="select();">
 / <input type="text" size="2" name="eint" value="<?= $eint ?>" OnFocus="select();">
 / <input type="text" size="2" name="esub" value="<?= $esub ?>" OnFocus="select();">
<p>
<textarea rows="4" name="icfg" cols="50"><?= $icfg ?></textarea>
</th>

</tr>
<tr class="<?= $modgroup[$self] ?>1">

<th valign="top" colspan="3">
<?php
if ( strstr($guiauth,'-pass') ){
	?>
	Password <input type="password" value="<?= $pwd ?>" name="pwd">
	<?php
}
?>
<input type="submit" value="<?= $sholbl ?>" name="sim">
<input type="submit" value="<?= $sndlbl ?>" name="scm">
<input type="submit" value="<?= $cfglbl ?>" name="con">
</th></tr>
</table>
</form>
<p>

<?php

if($ina){
	$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
	$query	= GenQuery('devices','s','*','','',array($ina,$inb),array($opa,$opb),array($sta,$stb),array($cop) );
	$res	= @DbQuery($query,$link);
	if($res){
		$prevos = "";
		$oserr = 0;
		while( ($d = @DbFetchArray($res)) ){
			if($d['login'] and $d['cliport']){
				$devip[$d['device']] = long2ip($d['devip']);
				if ($prevos and $prevos != $d['devos']){$oserr = 1;}
				$prevos = $d['devos'];
				$devos[$d['device']] = $d['devos'];
				$devsta[$d['device']] = $d[$ina];
				if($inb){$devstb[$d['device']] = $d[$inb];}
				$devpo[$d['device']] = $d['cliport'];
				$devlo[$d['device']] = $d['login'];
			}else{
				echo "<h4>No login for $d[device]!</h4>\n";
			}
		}
		$cf = "log/cmd_$_SESSION[user]";
		if ($oserr){echo "<h4>$mullbl OS!</h4>";die;}
	}else{
		print @DbError($link);
	}
	if(!isset($devip) ){echo "<h4>0 Devices!</h4>";die;}
	$cfgos = ($con or $sim)?$prevos:"";# TODO Change $con to checkbox!
	if($sim){
		if(!$sub){
			echo "<h2>$cmdlbl</h2>\n";
			echo "<div class=\"textpad code txta\">\n";
			echo Buildcmd('',$cfgos);
			echo "</div><br>\n";
		}
		echo "<h2>Devices</h2>\n";
		echo "<table class=\"content\"><tr class=\"$modgroup[$self]2\">";
		echo "<th colspan=2>Device</th><th>$cols[$ina]</th><th>".(($sub)?"Command":"$cols[$inb]</th>");
		echo "<th>Login</th><th>IP $adrlbl</th><th>Port</th></tr>\n";
		$row = 0;
		foreach ($devip as $dv => $ip){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			echo "<tr class=\"$bg\"><th class=\"$bi\">\n";
			echo "$row</th><td><b>$dv</b></td>";
			echo "<td>$devsta[$dv]</td><td>" . (($sub)?Buildcmd($devstb[$dv]):$devstb[$dv]) . "</td>\n";
			echo "<td>$devlo[$dv]</td><td>". DevCli($ip,$devpo[$dv]);
			echo "</td><td>$devpo[$dv]</td></tr>\n";
		}
	?>
</table>
<table class="content">
<tr class="<?= $modgroup[$self] ?>2"><td><?= $row ?> Devices (<?= $query ?>)</td></tr>
</table>
	<?php
	}elseif($scm or $con){
		if(!$sub){
			$fd =  @fopen("log/cmd_$_SESSION[user]","w") or die ("$errlbl $wrtlbl log/cmd_$_SESSION[user]");
			$cmds = Buildcmd('',$cfgos);
			fwrite($fd, $cmds);
			fclose($fd);
		}
		echo "<h2>Device $lstlbl</h2></center><div class=\"textpad warn code\">\n";
		foreach ($devip as $dv => $ip){
			flush();
			if($sub){
				$fd =  @fopen("log/cmd_$_SESSION[user]","w") or die ("can't create log/cmd_$_SESSION[user]");
				fwrite($fd,Buildcmd($devstb[$dv],$cfgos) );
				fclose($fd);
			}
			echo "<b>$dv</b> ".DevCli($ip,$devpo[$dv])." ";
			$cred = ( strstr($guiauth,'-pass') )?"$_SESSION[user] $pwd":"$devlo[$dv] dummy";
			$cred = addcslashes($cred,';$!');
			$out  = system("perl inc/devwrite.pl $nedipath $ip $devpo[$dv] $cred $devos[$dv] log/cmd_$_SESSION[user]", $err);
			echo " <a href=\"$cf-$ip.log\" target=window><img src=\"img/16/note.png\" title='view output'></a><br>";
			$cstr = preg_replace('/\n|"|\'/',' ',$cmds);
			if( strlen($cstr) > 40 ){$cstr = substr( $cstr,0,40)."...";}
			$msg  = "User $_SESSION[user] wrote $cstr";
			if($err){
				$lvl = 150;
				$msg = "User $_SESSION[user] wrote $cstr causing errors";
			}else{
				$lvl = 100;
				$msg = "User $_SESSION[user] wrote $cstr successfully";
			}
			$query = GenQuery('events','i','','','',array('level','time','source','info','class','device'),array(),array($lvl,time(),$dv,$msg,'usrd',$dv) );
			if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}
		}
		echo "</div><br><p>";
	}
}
include_once ("inc/footer.php");

function Buildcmd($arg="",$configureos=""){

	global $sub, $cmd, $stb, $sint, $eint, $smod, $emod, $ssub, $esub, $int, $icfg;

	$config = "";
	if($configureos == "IOS" or $configureos == "ProCurve"){
		$config .= "conf t\n";
	}elseif($configureos == "Comware"){
		$config .= "sys\n";
	}
	$config .= $cmd . (preg_match('/\n$/',$cmd)?"":"\n");						# Add return on last line, if missing (tx Tristan)
	if($sub){
		$config .= preg_replace("/$stb/",$sub,$arg);
	}else{
		if($int){
			for($m = $smod;$m <= $emod;$m++){
				for($i = $sint;$i <= $eint;$i++){
					if($ssub and $esub){
						for($s = $ssub;$s <= $esub;$s++){
							$config .= "int $int $m/$i/$s\n";
							$config .= "$icfg\n";
						}
					}elseif($int == "Vl" or $int == "Vi"){
						$config .= ($int == "Vl")?"Vlan $m\n":"int Vlan $m\n";
						$config .= "$icfg\n";
					}else{
						$config .= "int $int $m/$i\n";
						$config .= "$icfg\n";
					}
				}
			}
		}
	}
	if($configureos == "IOS" or $configureos == "ProCurve"){
		$config .= "end\nwrite mem\n";
	}elseif($configureos == "Comware"){
		$config .= "quit\nsave\ny\n\ny\n";
	}
	return "$config\n";
}

?>
