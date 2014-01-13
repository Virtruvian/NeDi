<?
# Program: User-Accounts.php
# Programmer: Remo Rickli

error_reporting(E_ALL ^ E_NOTICE);

$printable = 1;

include_once ("inc/header.php");
include_once ("inc/libldap.php");

$_GET = sanitize($_GET);
$ord = isset( $_GET['ord']) ? $_GET['ord'] : "";
$grp = isset( $_GET['grp']) ? $_GET['grp'] : "";
$del = isset( $_GET['del']) ? $_GET['del'] : "";

$inv = isset($_GET['inv']) ? $_GET['inv'] : "";
$opv = isset($_GET['opv']) ? $_GET['opv'] : "";
$stv = isset($_GET['stv']) ? $_GET['stv'] : "";

$cols = array(	"user"=>"$namlbl",
		"email"=>"Email",
		"phone"=>"Phone",
		"time"=>"$addlbl",
		"lastlogin"=>$laslbl,
		"comment"=>"$cmtlbl",
		"viewdev"=>"$fltlbl Devices"
		);

$gnam = array(	"1" =>"Admins",
		"2" =>$netlbl,
		"4" =>"Helpdesk",
		"8" =>"Monitor",
		"16"=>"Manager",
		"32"=>$mlvl['10']
		);

$dcol = array(	"device"=>"Device",
		"serial"=>"$serlbl",
		"type"=>"Device $typlbl",
		"services"=>$srvlbl,
		"description"=>$deslbl,
		"devos"=>"Device OS",
		"bootimage"=>"Bootimage",
		"location"=>$loclbl,
		"contact"=>$conlbl,
		"vtpdomain"=>"VTP Domain",
		"vtpmode"=>"VTP Mode",
		"readcomm"=>"SNMP $realbl",
		"writecomm"=>"SNMP $wrtlbl",
		"login"=>"Login",
		"cpu"=>"% CPU",
		"memcpu"=>"$memlbl $frelbl",
		"temp"=>$tmplbl,
		"cusvalue"=>"$cuslbl Value",
		);

?>
<h1><?=$usrlbl?> Management</h1>

<?if( !isset($_GET['print']) ){?>

<form method="get" action="<?=$self?>.php">
<table class="content" ><tr class="<?=$modgroup[$self]?>1">
<th width="50"><a href="<?=$self?>.php"><img src="img/32/<?=$selfi?>.png"></a></th>
<th><?=$grplbl?> 
<select size="1" name="grp" onchange="this.form.submit();">
<option value=""><?=$sellbl?> ->
<option value="1" <?=($grp == "1")?"selected":""?> ><?=$gnam['1']?>
<option value="2" <?=($grp == "2")?"selected":""?> ><?=$gnam['2']?>
<option value="4" <?=($grp == "4")?"selected":""?> ><?=$gnam['4']?>
<option value="8" <?=($grp == "8")?"selected":""?> ><?=$gnam['8']?>
<option value="16" <?=($grp == "16")?"selected":""?> ><?=$gnam['16']?>
<option value="32" <?=($grp == "32")?"selected":""?> ><?=$gnam['32']?>
</select>
<input type="hidden" name="ord" value="<?=$ord?>">
</th>
<th><?=$usrlbl?> 
<input type="text" name="usr" size="12">
<input type="submit" name="add" value="<?=$addlbl?>">
<?if( strstr($guiauth,'ldap') ){?>
<input type="submit" name="ldap" value="<?=$addlbl?> LDAP">
<?}?>
</th>
</table></form>
<p>
<?
}
$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
if (isset($_GET['add']) and $_GET['usr']){
	$pass = md5( $_GET['usr'] );
	$query	= GenQuery('users','i','','','',array('user','password','time','language','theme'),'',array($_GET['usr'],$pass,time(),'english','default') );
	if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$usrlbl $_GET[usr]: $addlbl OK</h5>";}
}elseif(isset($_GET['ldap']) and $_GET['usr']){
	$now = time();
	if ( user_from_ldap_servers($_GET['usr']) ){
		$query	= GenQuery('users','i','','','',array('user','email','phone','password','time','language','theme'),'',array($fields['ldap_login'] ,$fields['ldap_field_email'],$fields['ldap_field_phone'],'',time(),'english','default') );
		if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$usrlbl $_GET[usr]: $addbtn OK</h5>";}
	}else{
		echo "<h4>No $usrlbl $_GET[usr] in LDAP!</h4>";
	}
}elseif(isset($_GET['psw']) ){
	$pass = md5( $_GET['psw'] );
	$query	= GenQuery('users','u',"user=\"$_GET[psw]\"",'','',array('password'),'',array($pass) );
	if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$usrlbl $_GET[psw]: $reslbl password OK</h5>";}
}elseif(isset($_GET['gup']) ){
	$query	= GenQuery('users','u',"user=\"$_GET[usr]\"",'','',array('groups'),'',array($_GET['gup']) );
	if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$usrlbl $grplbl $updlbl OK</h5>";}
}elseif($del){
	$query	= GenQuery('users','d','','','',array('user'),array('='),array($_GET['del']) );
	if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>$usrlbl $_GET[del]: $dellbl OK</h5>";}
}elseif($stv){
	$viewdev = ($stv == '-')?"":DbEscapeString("$inv $opv \"$stv\"");
	$query	= GenQuery('users','u',"user=\"$_GET[usr]\"",'','',array('viewdev'),'',array($viewdev) );
	if( !@DbQuery($query,$link) ){echo "<h4>".DbError($link)."</h4>";}else{echo "<h5>Device $acslbl $updlbl OK</h5>";}
}
?>
<h2><?=$usrlbl?> <?=$lstlbl?></h2>
<table class="content"><tr class="<?=$modgroup[$self]?>2">

<?
ColHead('user');
ColHead('email');
ColHead('phone');
ColHead('comment');
ColHead('time');
ColHead('lastlogin');
ColHead('viewdev');

echo "<th>$grplbl</th><th>GUI</th><th>$cmdlbl</th></tr>\n";

if ($grp){
	$query	= GenQuery('users','s','*',$ord,'',array('groups'),array('&'),array($grp) );
}else{
	$query	= GenQuery('users','s','*',$ord );
}
$res	= @DbQuery($query,$link);
if($res){
	$row = 0;
	while( ($usr = @DbFetchRow($res)) ){
		if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
		$row++;
		list($cc,$lc) = Agecol($usr[5],$usr[6],$row % 2);
		echo "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\">\n";
?>
<th class="<?=$bi?>">
<?=Smilie($usr[0])?><br><?=$usr[0]?></th>
<td nowrap><?=$usr[3]?></td>
<td nowrap><?=$usr[4]?></td>
<td nowrap><?=$usr[7]?></td>
<td bgcolor="#<?=$cc?>"><?=(date($datfmt,$usr[5]))?></td>
<td bgcolor="#<?=$lc?>"><?=(date($datfmt,$usr[6]))?></td>
<td>
<?if( !($usr[2] & 1) ){?>
<form method="get">
<input type="hidden" name="usr" value="<?=$usr[0]?>">
<select size="1" name="inv">
<?

list($inv,$opv,$stv) = explode(" ",str_replace('"','',$usr[15]) );
foreach ($dcol as $k => $v){
       echo "<option value=\"$k\"".( ($inv == $k)?"selected":"").">$v\n";
}
?>
</select>

<select size="1" name="opv">
<? selectbox("oper",$opv);?>
</select><br>
<input type="text" name="stv" size="16" value="<?=$stv?>" onfocus="select();"  onchange="this.form.submit();" title="Device <?=$acslbl?> <?=$limlbl?>">
<?=(($stv)?"<a href=\"Devices-List.php?ina=$inv&opa=$opv&sta=$stv\"><img src=\"img/16/eyes.png\" title=\"Device $lstlbl\"></a>":"")?>
</form> 
<?}?>

</td>
<th>
<?
GroupButton($usr[0],$usr[2],1,'ucfg');
GroupButton($usr[0],$usr[2],2,'net');
GroupButton($usr[0],$usr[2],4,'ring');
GroupButton($usr[0],$usr[2],8,'bino');
GroupButton($usr[0],$usr[2],16,'umgr');
GroupButton($usr[0],$usr[2],32,'ugrp');
?>
</th>
<td align="center"><?=$usr[8]?><br><?=$usr[9]?></td>
<th>
<a href="Devices-Stock.php?lst=us&val=<?=$usr[0]?>"><img src="img/16/pkg.png" title="Stock <?=$lstlbl?>"></a>
<a href="Devices-List.php?ina=contact&opa=%3D&sta=<?=$usr[0]?>"><img src="img/16/dev.png" title="Device <?=$lstlbl?>"></a>
<a href="?grp=<?=$grp?>&ord=<?=$ord?>&psw=<?=$usr[0]?>"><img src="img/16/key.png" title="Password <?=$reslbl?>" onclick="return confirm('<?=$reslbl?>:<?=$cfmmsg?>')"></a>
<a href="?grp=<?=$grp?>&ord=<?=$ord?>&del=<?=$usr[0]?>"><img src="img/16/bcnl.png" title="<?=$dellbl?>" onclick="return confirm('<?=$dellbl?>, <?=$cfmmsg?>')"></a>
</th></tr>
<?
	}
	@DbFreeResult($res);
}else{
	print @DbError($link);
}
?>
</table>
<table class="content">
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$usrlbl?></td></tr>
</table>
<?

include_once ("inc/footer.php");

//===================================================================
// Draw group button
function GroupButton($us,$st,$gp,$ic){
	
	global $gnam,$grp,$ord,$addlbl,$dellbl;

	if($st & $gp){
		echo "<a href=\"?grp=$grp&ord=$ord&usr=$us&gup=".($st-$gp)."\">\n";
		echo "<img src=\"img/16/$ic.png\" title=\"$gnam[$gp]: $dellbl\"></a>\n";
	}else{
		echo "<a href=\"?grp=$grp&ord=$ord&usr=$us&gup=".($st+$gp)."\">\n";
		echo "<img src=\"img/16/bcls.png\" title=\"$gnam[$gp]: $addlbl\"></a>\n";
	}
}
?>
