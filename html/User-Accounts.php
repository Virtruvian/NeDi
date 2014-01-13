<?
# Program: User-Accounts.php
# Programmer: Remo Rickli

$printable = 1;

include_once ("inc/header.php");
include_once ("inc/libldap.php");

$_GET = sanitize($_GET);
$ord = isset( $_GET['ord']) ? $_GET['ord'] : "";
$grp = isset( $_GET['grp']) ? $_GET['grp'] : "";
$del = isset( $_GET['del']) ? $_GET['del'] : "";

$cols = array(	"user"=>"$namlbl",
		"email"=>"Email",
		"phone"=>"Phone",
		"time"=>"$addlbl",
		"lastlogin"=>$laslbl,
		"comment"=>"$cmtlbl"
		);

$gnam = array(	"1" =>"Admins",
		"2" =>$netlbl,
		"4" =>"Helpdesk",
		"8" =>"Monitor",
		"16"=>"Manager",
		"32"=>$mlvl['10']
		);

?>
<h1><?=$usrlbl?> Management</h1>

<?if( !isset($_GET['print']) ){?>

<form method="get" action="<?=$self?>.php">
<table class="content" ><tr class="<?=$modgroup[$self]?>1">
<th width="50"><a href="<?=$self?>.php"><img src="img/32/<?=$selfi?>.png"></a></th>
<th><?=$grplbl?> 
<SELECT size="1" name="grp" onchange="this.form.submit();">
<OPTION VALUE=""><?=$sellbl?> ->
<OPTION VALUE="1" <?=($grp == "1")?"selected":""?> ><?=$gnam['1']?>
<OPTION VALUE="2" <?=($grp == "2")?"selected":""?> ><?=$gnam['2']?>
<OPTION VALUE="4" <?=($grp == "4")?"selected":""?> ><?=$gnam['4']?>
<OPTION VALUE="8" <?=($grp == "8")?"selected":""?> ><?=$gnam['8']?>
<OPTION VALUE="16" <?=($grp == "16")?"selected":""?> ><?=$gnam['16']?>
<OPTION VALUE="32" <?=($grp == "32")?"selected":""?> ><?=$gnam['32']?>
</SELECT>
<input type="hidden" name="ord" value="<?=$ord?>">
</th>
<th><?=$usrlbl?> 
<input type="text" name="usr" size="12">
<input type="submit" name="create" value="<?=$addlbl?>">
<?if( strstr($guiauth,'ldap') ){?>
<input type="submit" name="ldap" value="<?=$addlbl?> LDAP">
<?}?>
</th>
</table></form>
<p>
<?
}
$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
if (isset($_GET['create']) and $_GET['usr']){
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
?>
<tr class="<?=$bg?>"><th class="<?=$bi?>">
<?=Smilie($usr[0])?><br><?=$usr[0]?></th>
<td><?=$usr[3]?></td><td align="center"><?=$usr[4]?></td>
<td><?=$usr[7]?></td>
<td bgcolor="#<?=$cc?>"><?=(date($datfmt,$usr[5]))?></td>
<td bgcolor="#<?=$lc?>"><?=(date($datfmt,$usr[6]))?></td>
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
<a href="Devices-Stock.php?lst=us&val=<?=$usr[0]?>"><img src="img/16/pkg.png" title="Stock <?=$lstlbl?>"</a>
<a href="Devices-List.php?ina=contact&opa=%3D&sta=<?=$usr[0]?>"><img src="img/16/dev.png" title="Device <?=$lstlbl?>"</a>
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
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> Users (<?=$query?>)</td></tr>
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
