<?
# Program: User-Wlan.php
# Programmer: Remo Rickli

error_reporting(E_ALL ^ E_NOTICE);

$printable = 1;

include_once ("inc/header.php");

$_GET = sanitize($_GET);
$ip = isset($_GET['ip']) ? $_GET['ip'] : "";
$du = isset($_GET['du']) ? $_GET['du'] : "";
$us = isset($_GET['us']) ? $_GET['us'] : "";
$au = isset($_GET['au']) ? 1:0;

$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
?>
<h1><?=$usrlbl?> <?=$edilbl?></h1>

<?if( !isset($_GET['print']) ){?>

<form method="get" name="usr" action="<?=$self?>.php">
<table class="content"><tr class="<?=$modgroup[$self]?>1">
<th width="50"><a href="<?=$self?>.php"><img src="img/32/<?=$selfi?>.png"></a></th>
<th>
Device <SELECT size="1" name="ip" onchange="this.form.submit();">
<OPTION VALUE="">------------
<?
$query	= GenQuery('devices','s','device,devip','','',array('os'),array('='),array('MSM') );
$res	= @DbQuery($query,$link);
if($res){
	while( $d = @DbFetchRow($res) ){
		echo "<option value=\"$d[1]\"".( ($ip == $d[1])?"selected":"").">$d[0]\n";
	}
	@DbFreeResult($res);
}else{
	print @DbError($link);
	die ( mysql_error() );
}
?>
</SELECT>
<img src="img/16/brld.png" title="Reload with current IP" onClick="document.location.href='?ip='+document.usr.ip.value;">
</th>
<th>User <input type="text" name="us" size="12">
</th>
<th width="80">
<input type="submit" name="au" value="<?=$addlbl?>">
</th>
</tr></table></form><p>
<?
}

if($ip){
	require_once("inc/soapapi-inc.php");
	SoapApi::ClearWSDLCache();

	try {
		$url = sprintf("%s://%s:%d/SOAP", "http", long2ip($ip), 448);

		$c = new SoapApi("inc/soapapi-7901.wsdl", array('connection_timeout'=> $timeout,
							'location' => $url ,
							'local_cert' => "inc/soap-api-client.crt",
							'passphrase' => "clientcertpa55")
				);

		$rc = $c->soapGetSOAPVersion();
		if ($rc->version != "2.6.0") {
			echo "<h4>$errlbl SOAP API ".$rc->version."</h4>";
			exit(1);
		}

		if($au and $us){
			echo "<h3>$addlbl $usrlbl $us</h3>";
			flush();
			$c->soapAddUserAccount($us, $us, "Enabled", "Enabled");
			sleep(5);
		}elseif($du){
			$c->soapDeleteUserAccount($du);
		}

?>
<h2><?=$usrlbl?> <?=$lstlbl?></h2>
<table class="content"><tr class="<?=$modgroup[$self]?>2">
<th>ID</th>
<th><?=$namlbl?></th>
<th>Access Ctrl</th>
<th>Active</th>
<th>Expired</th>
<th>Exausted</th>
<th>1st Login Expired</th>
<th>Period</th>
<th>Not Begun</th>
<th>Not Ended</th>
<th><?=$timlbl?> Left</th>
<th><?=$fislbl?></th>
<th>Session Left</th>
<th><?=$laslbl?></th>
<th><?=$cmdlbl?></th>

</tr>
<?
		$users = SoapGetList("soapGetUserAccountList","username");

		foreach ($users as $i => $nam){
			$row++;
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			echo "<tr class=\"$bg\"><td>$i</td><th>$nam</th>";
			$rc = $c->soapGetUserAccount($nam);
			echo "<th>".StatImg($rc->accessControlledState,16)."</th>";
			echo "<th>".StatImg($rc->activeState,16)."</th>\n";
			
			$rc = $c->soapGetUserAccountStatus($nam);
			echo "<th>".StatImg($rc->result->item->isAccountExpired,16)."</th>";
			echo "<th>".StatImg($rc->result->item->isOnlineTimeExausted,16)."</th>";
			echo "<th>".StatImg($rc->result->item->isTimeSinceFirstLoginExpired,16)."</th>";
			echo "<th>".StatImg($rc->result->item->isTimeCurrentlyOutsideValidPeriodOfDay,16)."</th>";
			echo "<th>".StatImg($rc->result->item->isValidityPeriodNotBegun,16)."</th>";
			echo "<th>".StatImg($rc->result->item->isValidityPeriodEnded,16)."</th>\n";

			echo "<th>".$rc->result->item->remainingOnlineTime."</th>";
			echo "<th>".$rc->result->item->firstLogin."</th>";
			echo "<th>".$rc->result->item->remainingSessionTime."</th>";
			echo "<th>".$rc->result->item->expiration."</th>\n";

			echo "<th><a href=\"?ip=$ip&du=$nam\"><img src=\"img/16/bcnl.png\" title=\"$usrlbl $dellbl\" onclick=\"return confirm('$dellbl: $cfmmsg?')\"></a></th>";
			echo " <tr>\n";
		#	$us = $c->GetUserAccountValidity($val);
		#			print_r($rc);
		}
		echo "</table>";
	} catch (Exception $e) {
		echo "<h4>". $e->getMessage(), "</h4>";
	}
}

include_once ("inc/footer.php");

//===================================================================
// Get list using SOAP
function SoapGetList($list,$item) {
	
	global $c;

	$sobj = $c->$list();
	$size = sizeof($sobj->result->item);
	if ($size < 2) {
		$ret[0] = $sobj->result->item->$item;
	}else {
		for ($i=0; $i<$size; $i++) {
			$ret[$i] = $sobj->result->item[$i]->$item;
		}
	}
	return $ret;
}

//===================================================================
// Return statusimage
function StatImg($stat,$size) {

	if( preg_match("/1|enabled|on/i",$stat) ){
		return "<img src=\"img/$size/bchk.png\" title=\"$stat\">";
	}else{
		return "<img src=\"img/$size/bcls.png\" title=\"$stat\">";
	}
}

?>
