<?php
# Program: Devices-Write.php
# Programmer: Remo Rickli

$printable = 1;
$exportxls = 0;

include_once ("inc/header.php");
include_once ("inc/libdev.php");

$_POST = sanitize($_POST);
$in = isset($_POST['in']) ? $_POST['in'] : array();
$op = isset($_POST['op']) ? $_POST['op'] : array();
$st = isset($_POST['st']) ? $_POST['st'] : array();
$co = isset($_POST['co']) ? $_POST['co'] : array();

$cmd = isset( $_POST['cmd']) ? $_POST['cmd'] : "";
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

$cols = array(	"device"=>" Device $namlbl",
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
		"lastdis"=>"$laslbl $dsclbl",
                "cfgstatus"=>"$cfglbl $stalbl"
		);
?>
<h1>Device Write</h1>

<form method="post" name="list" action="<?= $self ?>.php">
<table class="content">
<tr class="bgmain">
<td class="ctr s">
	<a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png" title="<?= $self ?>"></a>
</td>
<td colspan="2">
<?php Filters(); ?>
</td>
</tr><tr class="bgsub">
<td></td>
<td class="top ctr b">
	<?= $cmdlbl ?> / <?= $cfglbl ?>
	<p>
	<textarea rows="6" name="cmd" cols="60"><?= $cmd ?></textarea>
</td>
<td class="top ctr b">
	Interface <?= $cfglbl ?>
	<p>
	<select size="1" name="int">
		<option value=""><?= $sellbl ?> ->
		<option value="Et" <?php if($int == "Et"){echo "selected";} ?>>Ethernet
		<option value="Fa" <?php if($int == "Fa"){echo "selected";} ?>>Fastethernet
		<option value="Gi" <?php if($int == "Gi"){echo "selected";} ?>>Gigabit
		<option value="Te" <?php if($int == "Te"){echo "selected";} ?>>TenGigabit
		<option value="Vi" <?php if($int == "Vi"){echo "selected";} ?>>Vlan IF
		<option value="Vl" <?php if($int == "Vl"){echo "selected";} ?>>Vlan
	</select>
	   <input type="number" class="s" name="smod" value="<?= $smod ?>" OnFocus="select();">
	 / <input type="number" class="s" name="sint" value="<?= $sint ?>" OnFocus="select();">
	 / <input type="number" class="s" name="ssub" value="<?= $ssub ?>" OnFocus="select();">
	 - <input type="number" class="s" name="emod" value="<?= $emod ?>" OnFocus="select();">
	 / <input type="number" class="s" name="eint" value="<?= $eint ?>" OnFocus="select();">
	 / <input type="number" class="s" name="esub" value="<?= $esub ?>" OnFocus="select();">
	<p>
	<textarea rows="4" name="icfg" cols="50"><?= $icfg ?></textarea>
</td>
</tr><tr class="bgmain">
<td class="top ctr b" colspan="3">
<?php
if ( strstr($guiauth,'-pass') ){
?>
	Password <input type="password" value="<?= $pwd ?>" name="pwd">
<?php
}
?>
	<input type="submit" class="button" value="<?= $sholbl ?>" name="sim">
	<input type="submit" class="button" value="<?= $cmdlbl ?>" name="scm">
	<input type="submit" class="button" value="<?= $cfglbl ?>" name="con">
</td>
</tr></table>
</form>
<p>

<?php

session_write_close();
if( count($in) ){
	$link	= DbConnect($dbhost,$dbuser,$dbpass,$dbname);
	$query	= GenQuery('devices','s','*','','',$in,$op,$st,$co);
	$res	= DbQuery($query,$link);
	if($res){
		$prevos = "";
		$oserr  = 0;
		$ndev   = 0;
		while( ($d = DbFetchArray($res)) ){
			if($d['login'] and $d['cliport']){
				$devip[$d['device']] = long2ip($d['devip']);
				if ($prevos and $prevos != $d['devos']){$oserr = 1;}
				$prevos = $d['devos'];
				$devos[$d['device']] = $d['devos'];
				$devcfg[$d['device']] = $d['cfgstatus'];
				$devpo[$d['device']] = $d['cliport'];
				$devlo[$d['device']] = $d['login'];
				$ndev++;
			}else{
				echo "<h4>No login for $d[device]!</h4>\n";
			}
		}
		if ($oserr){
			echo "<h4>$mullbl OS!</h4>";
			include_once ("inc/footer.php");
			exit;}
	}else{
		print DbError($link);
	}
	if(!isset($devip) ){
		echo "<h4>0 Devices!</h4>";
		include_once ("inc/footer.php");
		exit;
	}
	$cfgos = ($con or $sim)?$prevos:"";# TODO Change $con to checkbox!
	if($sim){
		echo "<h3>$cmdlbl</h3>\n\n";
		echo "<div class=\"textpad code txta tqrt\">\n\n";
		echo Buildcmd('',$cfgos);
		echo "\n</div><br>\n";
		Condition($in,$op,$st,$co);
		echo "<table class=\"content\">\n	<tr class=\"bgsub\">\n";
		echo "		<th colspan=\"2\">Device</th>\n		<th>Device OS</th>\n";
		echo "		<th>Login</th>\n		<th>IP $adrlbl</th>\n		<th>$cfglbl $stalbl</th>\n	</tr>\n";
		$row = 0;
		foreach ($devip as $dv => $ip){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			echo "	<tr class=\"$bg\">\n";
			echo "		<td class=\"$bi ctr\">\n			$row\n		</td>\n";
			echo "		<td class=\"b\">\n			$dv\n		</td>\n";
			echo "		<td>\n			$devos[$dv]\n		</td>\n";
			echo "		<td>\n			$devlo[$dv]\n		</td>\n";
			echo "		<td>\n			". DevCli($ip,$devpo[$dv]).":$devpo[$dv]\n		</td>\n";
			echo "		<td>\n			". DevCfg($devcfg[$dv]).$devcfg[$dv]."\n		</td>\n	</tr>\n";
		}
		TblFoot("bgsub", 6, "$row Devices");
	}elseif($scm or $con){
		$fd =  @fopen("$nedipath/cli/cmd_$_SESSION[user]","w") or die ("$errlbl $wrtlbl $nedipath/cli/cmd_$_SESSION[user]");
		$cmds = Buildcmd('',$cfgos);
		fwrite($fd,$cmds);
		fclose($fd);
		echo "<h2>$actlbl $lstlbl</h2>\n";
		foreach ($devip as $dv => $ip){
			flush();
			$ud = urlencode($dv);
			echo "<h3>$dv</h3>\n";
			echo "<div class=\"textpad txtb qrt\">\n";
			echo "	<a href=\"Devices-Status.php?dev=$ud\">\n		<img src=\"img/16/sys.png\">\n	</a>\n";
			echo DevCli($ip,$devpo[$dv],1)." $rpylbl:";
			$cred = ( strstr($guiauth,'-pass') )?"$_SESSION[user] $pwd":"$devlo[$dv] dummy";
			$cred = addcslashes($cred,';$!');
			$out  = system("perl $nedipath/inc/devwrite.pl $nedipath $dv $ip $devpo[$dv] $cred $devos[$dv] cmd_$_SESSION[user]", $err);
			echo "\n</div>\n";
			$nout = array_sum( explode(' ',$out) );
			$outh = ($nout > 50)?500:$nout*20;
			echo "<div class=\"textpad code txta bctr tqrt\" height=\"$outh\">\n";
			include("$nedipath/cli/".rawurlencode($dv)."/cmd_$_SESSION[user].log");
			echo "</div>\n";
			$cstr = preg_replace('/[\r\n\"\']/',' ',$cmds);
			if( strlen($cstr) > 40 ){$cstr = substr( $cstr,0,40)."...";}
			if($err){
				$lvl = 150;
				$msg = "User $_SESSION[user] wrote $cstr causing errors";
			}else{
				$lvl = 100;
				$msg = "User $_SESSION[user] wrote \"$cstr\" successfully";
			}
			$query = GenQuery('events','i','','','',array('level','time','source','info','class','device'),array(),array($lvl,time(),$dv,$msg,'usrd',$dv) );
			if( !DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}
		}
	}
}
include_once ("inc/footer.php");

function Buildcmd($arg="",$configureos=""){

	global $cmd, $stb, $sint, $eint, $smod, $emod, $ssub, $esub, $int, $icfg;

	if($configureos == "IOS" or $configureos == "ProCurve"){
		$config .= "conf t\n";
	}elseif($configureos == "Comware"){
		$config .= "sys\n";
	}
	$config .= $cmd;
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
	if($configureos == "IOS" or $configureos == "ProCurve"){
		$config .= "end\nwrite mem\n";
	}elseif($configureos == "Comware"){
		$config .= "quit\nsave force\n";
	}
	return "$config\n";
}

?>
