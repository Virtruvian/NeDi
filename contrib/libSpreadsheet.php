<?php
#################################################################################################
# libSpreadheet.php	
#	
# used to export NeDi lists to the Excel spreadsheet format (.xls)	
# only device list and nodes list implemented yet	
#	
# This script takes the same arguments that are used by the web interface to fetch lists. As	
# additinal parameter, $_GET['page'] holds the name of the list page.	
#	
# This script requires the PEAR package Spreadsheet_Excel_Writer to be installed.	
#	
# written by gumba, 2008
#################################################################################################

require_once("libmisc.php");
require_once("libmsq.php");

# specify handling for different pages here
switch ($_GET['page']):
case "Devices-List":
$tblname = "devices";
$colName['name'] = "Name";
$colName['ip'] = "Main IP";
$colName['serial'] = "Serial Number";
$colName['type'] = "Type";
$colName['firstseen'] = "First seen";
$colName['lastseen'] = "Last seen";
$colName['services'] = "Services";
$colName['description'] = "Description";
$colName['os'] = "OS";
$colName['bootimage'] = "Bootimage";
$colName['location'] = "Location";
$colName['contact'] = "Contact";
$colName['vtpdomain'] = "VTP Domain";
$colName['vtpmode'] = "VTP Mode";
$colName['snmpversion'] = "SNMP Version";
$colName['community'] = "Community";
$colName['cliport'] = "CLI port";
$colName['login'] = "Login";
$colName['icon'] = "Icon";
$colName['origip'] = "Original IP";
$colName['cpu'] = "% CPU";
$colName['memcpu'] = "Available CPU Mem";
$colName['memio'] = "Available IO Mem";
$colName['temp'] = "Temperature";
break;
case "Nodes-List":
$tblname = "nodes";
$colName['name'] = "Name";
$colName['ip'] = "IP Address";
$colName['mac'] = "MAC Update";
$colName['oui'] = "OUI Vendor";
$colName['firstseen'] = "First seen";
$colName['lastseen'] = "Last seen";
$colName['device'] = "Device";
$colName['ifname'] = "IF Name";
$colName['vlanid'] = "Vlan";
$colName['ifmetric'] = "IF Metric";
$colName['ifupdate'] = "IF Update";
$colName['ifchanges'] = "IF Changes";
$colName['ipupdate'] = "IP Update";
$colName['ipchanges'] = "IP Changes";
$colName['iplost'] = "IP Lost";
$colName['arp'] = "ARP Values";
break;
default: ?>
<html>
<script>
alert("Lists from page '<?= $_GET['page'] ?>' not supported!"); 
history.back();
</script>
</html>
<?php
die();
endswitch;

require_once("Spreadsheet/Excel/Writer.php");

$workbook = new Spreadsheet_Excel_Writer();
$workbook->setVersion(8);
$workbook->send('Devices_List.xls');
$worksheet =& $workbook->addWorksheet('Devices List');

$style_bold =& $workbook->addFormat(array('Bold' => 1));
$color_warning =& $workbook->addFormat();
$color_warning->setFgColor('yellow');
$color_critical =& $workbook->addFormat();
$color_critical->setFgColor('red');
$style_none =& $workbook->addFormat();
$style_none->setFgColor('white');
$style_none->setColor('black');
$style_none->setAlign('left');

$rownr = 0;
$colnr = 0;
$colwidth = array();
foreach ($_GET['col'] as $col):
$worksheet->write($rownr, $colnr, $colName[$col], $style_bold);
$colwidth[$colnr] = strlen($colName[$col]);
$colnr++;
endforeach;
$rownr++;

$link = DbConnect("localhost", "nedi", "dbpa55", "nedi");
$query = GenQuery($tblname, 's', '*', $_GET['ord'], '', array($_GET['ina'], $_GET['inb']), array($_GET['opa'], $_GET['opb']), array($_GET['sta'], $_GET['stb']), array($_GET['cop'], ''));
$result = DbQuery($query, $link);
while ($dev = DbFetchArray($result)):
$colnr = 0;
foreach ($_GET['col'] as $col):
switch ($col):
case "ip":
$value = long2ip($dev['ip']);
$style = $style_none;
break;
case "lastseen":
$value = date("j. M. Y, H:i:s", $dev['lastseen']);
if ($dev['lastseen'] < time() - 2419200):
$style = $color_critical;
elseif ($dev['lastseen'] < time() - 1209600):
$style = $color_warning;
else:
$style = $style_none;
endif;
break;
case "firstseen":
$value = date("j. M. Y, H:i:s", $dev['firstseen']);
break;
default:
$value = $dev[$col];
$style = $style_none;
break;
endswitch;
$worksheet->write($rownr, $colnr, $value, $style);
$colwidth[$colnr] = max($colwidth[$colnr], strlen($value) + 2);
$colnr++;
endforeach;
$rownr++;
endwhile;

foreach ($colwidth as $i => $width):
$worksheet->setColumn($i, $i, $width);
endforeach;

$workbook->close();
?>
