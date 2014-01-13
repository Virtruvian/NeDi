<?
	include("inc/header.php");

	$link = @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
	
	if ($_POST['rmdevs']):
		foreach ($_POST['delete'] as $rmdev):
			DbQuery("DELETE FROM devices WHERE name = '".$rmdev."' LIMIT 1", $link);
		endforeach;
	endif;

	$collisions = array();
	$coll_serials = array();
	$devices = DbQuery("SELECT name, ip, serial, firstseen, lastseen FROM devices WHERE serial REGEXP BINARY '^[-() A-Z0-9]{2,}$'", $link);
	while ($device = DbFetchArray($devices)):
		if (@!in_array($device['serial'], $coll_serials)):
			$lookup = DbQuery("SELECT name, ip, firstseen, lastseen FROM devices WHERE serial = '".$device['serial']."' AND NOT name = '".$device['name']."'", $link);
			while ($colldev = DbFetchArray($lookup)):
				# determine reason for serial number collision
				# use 
				#	1 for change from dev1 to dev2
				#	2 for change from dev2 to dev1
				#	0 for unknown collision reason (i.e. devices appearance times overlap)
				if ($colldev['firstseen'] > $device['lastseen']):
					$reason = 1;
				elseif ($colldev['lastseen'] < $device['firstseen']):
					$reason = 2;
				else:
					$reason = 0;
				endif;
				$collisions[] = array('serial' => $device['serial'], 'dev1_name' => $device['name'], 'dev1_ip' => $device['ip'], 'dev2_name' => $colldev['name'], 'dev2_ip' => $colldev['ip'], 'reason' => $reason);
				$coll_serials[] = $device['serial'];
			endwhile;
		endif;
	endwhile;
?>
<h1>Serial number collisions</h1>
<br>
<?
	$collnr = count($collisions);
	if ($collnr): ?>
<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
<table class="content">
	<tr class="<?= $modgroup[$self] ?>1">
		<td rowspan="<?= $collnr + 1 ?>"><img src="img/32/coll.png"></td>
		<th>Serial number</th>
		<th>Device 1</th>
		<th>Reason</th>
		<th>Device 2</th>
		<th>Action</th>
	</tr>
<?
		foreach ($collisions as $collision): 
			if ($collision['reason'] == 2):
				$devOld = 'dev2';
				$devNew = 'dev1';
			else:
				$devOld = 'dev1';
				$devNew = 'dev2';
			endif;
?>
	<tr class="<?= $modgroup[$self] ?>2">
		<td><?= $collision['serial'] ?></td>
		<td><?= $collision[$devOld.'_name']." at ".long2ip($collision[$devOld.'_ip']) ?></td>
		<td><?= $collision['reason'] == 0 ? "has the same serial number as" : "seems to have become" ?></td>
		<td><?= $collision[$devNew.'_name']." at ".long2ip($collision[$devNew.'_ip']) ?></td>
		<td><?
			switch ($collision['reason']):
				case 1:
				case 2: ?>
			<input type="checkbox" name="delete[]" value="<?= $collision[$devOld.'_name'] ?>"> Delete <?= $collision[$devOld.'_name'] ?><br>		
<?
					break;
				case 0: ?>
			<input type="checkbox" name="delete[]" value="<?= $collision[$devOld.'_name'] ?>"> Delete <?= $collision[$devOld.'_name'] ?><br>
			<input type="checkbox" name="delete[]" value="<?= $collision[$devNew.'_name'] ?>"> Delete <?= $collision[$devNew.'_name'] ?><br>
<?
					break;
			endswitch; ?>
			<a href="Devices-List.php?ina=name&amp;opa=%3D&amp;sta=<?= $collision[$devOld.'_name'] ?>&amp;cop=OR&amp;inb=name&amp;opb=%3D&amp;stb=<?= $collision[$devNew.'_name'] ?>&amp;col%5B%5D=name&amp;col%5B%5D=ip&amp;col%5B%5D=serial&amp;col%5B%5D=location&amp;col%5B%5D=firstseen&amp;col%5B%5D=lastseen">Details</a></td>
	</tr>
<?
		endforeach;
?>
</table>
<br>
<input type="submit" name="rmdevs" value="Delete selected devices">
</form>
<?
	else: ?>
<div class="<?= $modgroup[$self] ?>">
No serial number collisions have been found!
</div>
<?
	endif;
	
	include("inc/footer.php");
?>