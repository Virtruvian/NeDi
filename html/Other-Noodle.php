<?
# Program: Other-Noodle.php
# Programmer: Remo Rickli

$printable = 1;
$calendar  = 1;

include_once ("inc/header.php");
include_once ("inc/libdev.php");

$_GET = sanitize($_GET);
$str = isset($_GET['str']) ? $_GET['str'] : "";
$mod = isset($_GET['mod']) ? $_GET['mod'] : "";
$lim = isset($_GET['lim']) ? $_GET['lim'] : 10;


$tabs['dev'] = array(	'devices'	=> array ('device','type','serial','description','devos','bootimage','location','contact','vtpdomain'),
			'configs'	=> array ('device','config','changes'),
			'events'	=> array ('source','info'),
			'interfaces'	=> array ('device','ifname','ifdesc','comment'),
			'iftrack'	=> array ('device','ifname'),
			'incidents'	=> array ('name','comment'),
			'links'		=> array ('device','ifname','neighbor','nbrifname','linktype'),
			'locations'	=> array ('region','city','building','locdesc'),
			'modules'	=> array ('device','model','moddesc','serial','hw','fw','sw'),
			'monitoring'	=> array ('name','depend','test','eventfwd','eventdel'),
			'nodetrack'	=> array ('device','ifname','destination','user'),
			'networks'	=> array ('device','ifname','vrfname'),
			'stock'		=> array ('serial','type','user','location','comment','source'),
			'stolen'	=> array ('name','mac','device','ifname','user'),
			'vlans'		=> array ('device','vlanname')
			);

$tabs['node'] = array(	'nodes'		=> array ('name','mac','oui','device','ifname','nodos'),
			'events'	=> array ('source','info'),
			'nodetrack'	=> array ('device','ifname','destination','user'),
			'iptrack'	=> array ('mac','name','device'),
			'monitoring'	=> array ('name','depend','test','eventfwd','eventdel')
			);

$tabs['usr'] = array(	'users'		=> array ('user','email','comment'),
			'events'	=> array ('source','info'),
			'chat'		=> array ('user','message'),
			'nodetrack'	=> array ('device','ifname','destination','user'),
			'stock'		=> array ('serial','type','user','location','comment','source'),
			'stolen'	=> array ('name','mac','device','ifname','user')
			);

$ico = array(	'devices'	=> 'dev',
		'configs'	=> 'conf',
		'chat'		=> 'say',
		'events'	=> 'bell',
		'interfaces'	=> 'port',
		'iftrack'	=> 'cinf',
		'iptrack'	=> 'cinf',
		'incidents'	=> 'bomb',
		'links'		=> 'ncon',
		'locations'	=> 'home',
		'modules'	=> 'cubs',
		'monitoring'	=> 'bino',
		'nodes'		=> 'nods',
		'nodetrack'	=> 'note',
		'networks'	=> 'net',
		'stock'		=> 'pkg',
		'stolen'	=> 'hat',
		'vlans'		=> 'vlan',
		'users'		=> 'ugrp'
	);

$lnk = array(	'device'	=> 'Devices-Status.php?dev=',
		'source'	=> 'Monitoring-Events.php?ina=source&opa==&sta=',
		'depend'	=> 'Devices-Status.php?dev=',
		'ifname'	=> 'Devices-Interfaces.php?ina=ifname&opa==&sta=',
		'mac'		=> 'Nodes-Status.php?mac=',
		'neighbor'	=> 'Devices-Status.php?dev=',
		'nbrifname'	=> 'Devices-Interfaces.php?ina=ifname&opa==&sta=',
		'type'		=> 'Devices-List.php?ina=type&opa==&sta=',
		'vlanname'	=> 'Devices-Vlans.php?ina=vlanname&opa==&sta=',
	);


?>
<h1>Noodle Search</h1>

<?if( !isset($_GET['print']) ){?>
<form method="get" name="find" action="<?=$self?>.php">
<table class="content"><tr class="<?=$modgroup[$self]?>1">
<th width="50"><a href="<?=$self?>.php"><img src="img/32/<?=$selfi?>.png"></a>

</th>
<th valign="top"><?=$sholbl?><p>

<select size="1" name="mod">
<option value="dev"><?=$tgtlbl?> ->
<option value="dev" <?=($mod == "dev")?"selected":""?>>Device
<option value="node"<?=($mod == "node")?"selected":""?>>Node
<option value="usr" <?=($mod == "usr")?"selected":""?>><?=$usrlbl?>
</select> ~
<input type="search" name="str" value="<?=$str?>" size="40">

</th>
<th valign="top"><?=$limlbl?><p>

<select size="1" name="lim">
<? selectbox("limit",$lim);?>
</select>

</th>
<th width="80">

<input type="submit" value="Find IT">

</th>
</tr></table></form>
<p>
<?
}

if ($str){
	echo "<h3>";
	if    ($mod == "dev") {echo "<img src=\"img/16/dev.png\" title=\"Device $tgtlbl\">";}
	elseif($mod == "node"){echo "<img src=\"img/16/node.png\" title=\"Node $tgtlbl\">";}
	elseif($mod == "usr") {echo "<img src=\"img/16/user.png\" title=\"$usrlbl $tgtlbl\">";}
	echo " $cndlbl: $mod regexp \"$str\"</h3>";
	$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);

	foreach ($tabs[$mod] as $table => $cols){
		if($debug){print_r($cols); echo "<br>";}
		$incol  = "CONCAT_WS(',',".implode(",", $cols).")";
		$outcol = implode(",", $cols);
		$query	= GenQuery($table,'s',$outcol,'','',array($incol),array('regexp'),array($str) );
		$res	= @DbQuery($query,$link);

		if(@DbNumRows($res)){
			echo "<h2><img src=img/16/$ico[$table].png> $table</h2><table class=\"content\"><tr class=\"$modgroup[$self]2\">";
			for ($i = 0; $i < @DbNumFields($res); ++$i) {
				$id = @DbFieldName($res, $i);
				echo  "<th>$id</th>\n";
			}
			echo  "</tr>\n";
			$row = 0;
			while($l = @DbFetchArray($res)) {
				if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
				$row++;
				echo  "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\">";
				foreach($l as $id => $field) {
					if( strlen($field) > 100 ){
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
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$vallbl?></td></tr>
</table><br>
<?
		}
	}
}
include_once ("inc/footer.php");
?>
