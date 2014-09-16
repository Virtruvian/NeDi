<?php
# Program: Other-Noodle.php
# Programmer: Remo Rickli

$printable = 1;
$exportxls = 0;

include_once ("inc/header.php");
include_once ("inc/libdev.php");

$_GET = sanitize($_GET);
$str = isset($_GET['str']) ? $_GET['str'] : "";
$lim = isset($_GET['lim']) ? preg_replace('/\D+/','',$_GET['lim']) : $listlim;


$tabs = array(	'devices'	=> array ('device','inet_ntoa(devip)','serial','type','description','devos','bootimage','location','contact','devgroup'),
			'configs'	=> array ('device','config','changes'),
			'interfaces'	=> array ('device','ifname','ifmac','ifdesc','comment'),
			'incidents'	=> array ('name','comment'),
			'links'		=> array ('device','ifname','neighbor','nbrifname','linktype'),
			'locations'	=> array ('region','city','building','locdesc'),
			'modules'	=> array ('device','model','moddesc','modules.serial','hw','fw','sw'),
			'monitoring'	=> array ('name','depend','depend2','test','eventfwd','eventdel'),
			'networks'	=> array ('device','ifname','inet_ntoa(ifip)','vrfname'),
			'stock'		=> array ('serial','type','user','location','comment','source'),
			'stolen'	=> array ('name','mac','device','ifname','user'),
			'vlans'		=> array ('device','vlanname'),
			'nodes'		=> array ('name','mac','oui','inet_ntoa(nodip)','device','ifname','nodos'),
			'nodetrack'	=> array ('device','ifname','value','user'),
			'iftrack'	=> array ('mac','device','ifname'),
			'iptrack'	=> array ('mac','inet_ntoa(nodip)','name','device'),
			'stolen'	=> array ('name','mac','device','ifname','user'),
			'events'	=> array ('source','info'),
			'users'		=> array ('usrname','email','comment'),
			'chat'		=> array ('user','message')
			);

$ico = array(	'devices'	=> 'dev',
		'modules'	=> 'cubs',
		'interfaces'	=> 'port',
		'vlans'		=> 'vlan',
		'configs'	=> 'conf',
		'networks'	=> 'net',
		'links'		=> 'ncon',
		'locations'	=> 'home',
		'stock'		=> 'pkg',
		'monitoring'	=> 'bino',
		'incidents'	=> 'bomb',
		'nodes'		=> 'nods',
		'nodetrack'	=> 'note',
		'iftrack'	=> 'cinf',
		'iptrack'	=> 'cinf',
		'stolen'	=> 'hat',
		'users'		=> 'ugrp',
		'chat'		=> 'say',
		'events'	=> 'bell',
	);

$jdv = array(	'devices'	=> 0,
		'modules'	=> 1,
		'interfaces'	=> 1,
		'vlans'		=> 1,
		'configs'	=> 1,
		'networks'	=> 1,
		'links'		=> 1,
		'locations'	=> 0,
		'stock'		=> 0,
		'monitoring'	=> 1,
		'incidents'	=> 1,
		'nodes'		=> 1,
		'nodetrack'	=> 1,
		'iftrack'	=> 1,
		'iptrack'	=> 1,
		'stolen'	=> 1,
		'users'		=> 0,
		'chat'		=> 0,
		'events'	=> 1,
	);

$lnk = array(	'device'	=> 'Devices-Status.php?dev=',
		'source'	=> 'Monitoring-Events.php?in[]=source&op[]==&st[]=',
		'depend'	=> 'Devices-Status.php?dev=',
		'ifname'	=> 'Devices-Interfaces.php?in[]=ifname&op[]==&st[]=',
		'mac'		=> 'Nodes-Status.php?mac=',
		'neighbor'	=> 'Devices-Status.php?dev=',
		'nbrifname'	=> 'Devices-Interfaces.php?in[]=ifname&op[]==&st[]=',
		'type'		=> 'Devices-List.php?in[]=type&op[]==&st[]=',
		'vlanname'	=> 'Devices-Vlans.php?in[]=vlanname&op[]==&st[]=',
	);


?>
<h1>Noodle Search</h1>

<?php  if( !isset($_GET['print']) ) { ?>
<form method="get" name="find" action="<?= $self ?>.php">
<table class="content"><tr class="<?= $modgroup[$self] ?>1">
<th width="50"><a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png"></a>

</th>
<th valign="top"><?= $sholbl ?><p>

<input type="search" name="str" value="<?= $str ?>" class="l">

</th>
<th valign="top"><?= $limlbl ?><p>

<select size="1" name="lim">
<?php selectbox("limit",$lim) ?>
</select>

</th>
<th width="80">

<input type="submit" class="button" value="Find IT">

</th>
</tr></table></form>
<p>
<?php
}

if ($str){
	echo "<h3>$fltlbl \"$str\"</h3>";
	$link	= DbConnect($dbhost,$dbuser,$dbpass,$dbname);

	foreach ($tabs as $table => $cols){
		if($debug){
			echo "<div class=\"textpad noti\">";
			print_r($cols);
			echo "</div>";
		}
		$incol  = "CONCAT(".implode(",", $cols).")";
		$outcol = implode(",", $cols);
		$join   = ($jdv[$table])?'LEFT JOIN devices USING (device)':'';
		$query	= GenQuery($table,'s',$outcol,'','',array($incol),array('~'),array($str),array(),$join);
		$res	= DbQuery($query,$link);

		if(DbNumRows($res)){
			echo "<h2><img src=img/16/$ico[$table].png> $table</h2><table class=\"content\"><tr class=\"$modgroup[$self]2\">";
			for ($i = 0; $i < DbNumFields($res); ++$i) {
				$id = DbFieldName($res, $i);
				echo  "<th>$id</th>\n";
			}
			echo  "</tr>\n";
			$row = 0;
			while($l = DbFetchArray($res)) {
				if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
				$row++;
				TblRow($bg);
				foreach($l as $id => $field) {
					if( strlen($field) > 255 ){
						echo "<td>".substr(implode("\n",preg_grep("/$str/i",explode("\n",$field) ) ),0,100 ) . "...</td>";
					}else{
						if( array_key_exists($id,$lnk) ){
							echo "<td><a href=\"$lnk[$id]".urlencode($field)."\">$field</a></td>";
						}else{
							echo "<td>$field</td>";
						}
					}
				}
				echo  "</tr>\n";
				if($row == $lim){break;}
			}
?>
</table>
<table class="content" >
<tr class="<?= $modgroup[$self] ?>2"><td><?= $row ?> <?= $vallbl ?></td></tr>
</table><br>
<?php
		}
	}
}
include_once ("inc/footer.php");
?>
