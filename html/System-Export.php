<?
# Program: System-Export.php
# Programmer: Pascal Voegeli, Remo Rickli (minor additions)

$printable = 1;

// Header.php contains the navigation and general settings for the UI
include_once("inc/header.php");

if(!$isadmin){
	$_GET = sanitize($_GET);
}

// This is used later in the HTML form to ensure that there is always something selected,
// even if nothing has been passed to the script with GET
$sqltbl = isset($_GET['sqltbl']) ? $_GET['sqltbl'] : array("configs");
$action = isset($_GET['action']) ? $_GET['action'] : "";
$exptbl = isset($_GET['exptbl']) ? $_GET['exptbl'] : "";
$query  = isset($_GET['query']) ? stripslashes($_GET['query']) : "";
$sep    = isset($_GET['sep']) ? $_GET['sep'] : "";
$quotes = isset($_GET['quotes']) ? "checked" : "";
$colhdr = isset($_GET['colhdr']) ? "checked" : "";
$type   = isset($_GET['type']) ? $_GET['type'] : "htm";
$timest = isset($_GET['timest']) ? "checked" : "";

// A connection to the database has to be made
$dblink = DbConnect($dbhost, $dbuser, $dbpass, $dbname);
?>

<!-- Begin of the HTML part -->

<h1>Export</h1>

<?if( !isset($_GET['print']) ){?>

<form method="get" name="export" action="<?=$self?>.php">

<table class="content" >
	<tr class="<?=$modgroup[$self]?>1">
		<th width="50"><a href="<?=$self?>.php"><img src="img/32/<?=$selfi?>.png"></a></th>

		<!-- This <th> contains the export part of the form -->
		<td valign="top" align="center">

			<!-- If the module is loaded without any GET variables the selected action is "Export" -->
			<h3><input type="radio" name="action" value="export" <?=$action=="export"?"checked":""?>>Export</input></h3>
			<table><tr><td><?=$frmlbl?>:</td>
			<!-- There are 3 different types of things that can be selected in this box: -->
			<!-- If a database table is selected, a "SELECT * FROM..." query is automatically written to the text box -->
			<!-- If the "Device Config Files" entry is selected, the separator and quotes fields are disabled and a specific -->
			<!-- query is written to the text box -->
			<!-- If one of the meaningless entiries is selected nothing's changed in the text box -->
			<td><select size="1" name="exptbl"  size="1" onchange="
				if(document.forms['export'].exptbl.options[document.forms['export'].exptbl.selectedIndex].value=='none') {
					document.forms['export'].sep.disabled=false;
					document.forms['export'].quotes.disabled=false;
				}
				else if(document.forms['export'].exptbl.options[document.forms['export'].exptbl.selectedIndex].value=='cfgfiles') {
					document.forms['export'].query.value='SELECT device, config, time FROM configs';
					document.forms['export'].sep.disabled=true;
					document.forms['export'].quotes.disabled=true;
				}
				else if(document.forms['export'].exptbl.options[document.forms['export'].exptbl.selectedIndex].value=='eventret') {
					document.forms['export'].query.value='DELETE FROM events where time < <?=(time() - $retire * 86400)?>';
					document.forms['export'].sep.disabled=true;
					document.forms['export'].quotes.disabled=true;
				}
				else if(document.forms['export'].exptbl.options[document.forms['export'].exptbl.selectedIndex].value=='iftrkret') {
					document.forms['export'].query.value='DELETE FROM iftrack where ifupdate < <?=(time() - $retire * 86400)?>';
					document.forms['export'].sep.disabled=true;
					document.forms['export'].quotes.disabled=true;
				}
				else if(document.forms['export'].exptbl.options[document.forms['export'].exptbl.selectedIndex].value=='iptrkret') {
					document.forms['export'].query.value='DELETE FROM iptrack where ipupdate < <?=(time() - $retire * 86400)?>';
					document.forms['export'].sep.disabled=true;
					document.forms['export'].quotes.disabled=true;
				}
				else {
					document.forms['export'].query.value='SELECT * FROM '+document.forms['export'].exptbl.options[document.forms['export'].exptbl.selectedIndex].value;
					document.forms['export'].sep.disabled=false;
					document.forms['export'].quotes.disabled=false;
				}
			">
				<option value="none">select...</option>
				<option value="none">--- DB tables ---</option>
			<?  // Some PHP code
				// All the names of the database tables are collected and put into the select box
				$res = DbQuery(GenQuery("", "h"), $dblink);
				while($n = DbFetchRow($res)){
					echo "<option value=\"".$n[0]."\"".($n[0]==$exptbl?" selected":"").">".$n[0]."</option>\n";
				}
				echo "<option value=\"none\">--- Maintenance ---</option>";
				echo "<option value=\"cfgfiles\"".($exptbl=="cfgfiles"?" selected":"").">Archive Configs</option>\n";
				echo "<option value=\"eventret\"".($exptbl=="eventret"?" selected":"").">Retire $msglbl</option>\n";
				echo "<option value=\"iftrkret\"".($exptbl=="iftrkret"?" selected":"").">Retire IF track</option>\n";
				echo "<option value=\"iptrkret\"".($exptbl=="iptrkret"?" selected":"").">Retire IP track</option>\n";
			?>
			</select>
			Separator:
			<select size="1" name="sep">
			<?  // Some PHP code
				$separators = array(";", ";;", ":", "::", ",", "/");
				foreach($separators as $s){
					echo "<option value=\"$s\"".($s==$sep?" selected":"").">".$s."</option>\n";
					#echo "<option value=\"".$sep."\"".($s==$sep?" selected":"").">".$s."</option>\n";		<-- Pascals Kaese ;-)
				}
			?>
			</select>
			&nbsp;Quotes <input type="checkbox" name="quotes" <?=$quotes?>>
			Header <input type="checkbox" name="colhdr" <?=$colhdr?>></td></tr>
			<tr><td>Query:</td>
			<td>
			<textarea rows="3" name="query" cols="60"><?=$query?></textarea>
			</table>
		</td>
	
		<!-- This <th> contains the SQL dump part of the form -->
		<td valign="top" align="center">
			<h3><input type="radio" name="action" value="sqldump" <?=$action=="sqldump"?"checked":""?>>Dump Tables</input></h3>
			<p>
				<select multiple size="6" name="sqltbl[]">
				<?  // Some PHP code
					$res = DbQuery(GenQuery("", "h"), $dblink);
					while($n = DbFetchRow($res)){
						echo "<option value=\"".$n[0]."\"".(in_array($n[0], $sqltbl)?" selected":"").">".$n[0]."</option>\n";
					}
				?>
				</select>
			</p>
		</td>

		<!-- This <th> contains the archive settings -->
		<th width="80" valign="top" align="center">
			<h3><?=$dstlbl?></h3>
			<p>
			<select size="1" name="type">
				<option value="htm" <?=($type=="htm")?"selected":""?>>html</option>
				<option value="plain" <?=($type=="plain")?"selected":""?>>plain</option>
				<option value="gz" <?=($type=="gz")?"selected":""?>>Gzip</option>
				<option value="bz2" <?=($type=="bz2")?"selected":""?>>Bzip2</option>
				<option value="tar" <?=($type=="tar")?"selected":""?>>Tar</option>
			</select>
			<p>
			<input type="checkbox" name="timest" <?=$timest?>><img src="img/16/clock.png" title="<?=$addlbl?> <?=$timlbl?>">
			<p>
			<input type="submit" value="<?=$cmdlbl?>">
		</th>
	</tr>
</table>

</form>

<!-- End of the HTML part -->

<?
}
// If the "Export" radio button has been selected
if($action == "export") {
	// An empty query produces an error message
	if($query == "") {
		echo "<h4>Query $emplbl!</h4>";
	}
	// Execute and return status, if the query is not an SELECT query
	elseif(!preg_match ('/^(SELECT|EXPLAIN)/i',$query) ) {
		if($isadmin){
			if( $res = DbQuery($query, $dblink) ) {
				echo "<h4>$query $errlbl</h4>";
			}else{
				echo "<h5>$query OK</h5>";
			}
		}else{
			echo "$nokmsg";
		}
	}
	// And finally, if the query is invalid for any other reasons, an error message is printed
	elseif(!($res = DbQuery($query, $dblink))) {
		echo "<h4>".DbError($dblink)."</h4>";
	}
	// If the query starts with "SELECT device, config, time FROM configs " a config export is made
	// instead of a CSV export
	elseif(strtoupper(substr($query, 0, 43)) == "SELECT DEVICE, CONFIG, TIME FROM CONFIGS") {
		// This is the beginning of the output table
		echo "<h2>Log</h2><div class=\"textpad txta\">\n";
		echo "Retrieving data from database<br>\n";

		//The query from the text box is executed
		#$res = DbQuery($query, $dblink); Not needed as it's been done already...
		$row = array();
		$configs = array();

		echo "Found ".DbNumRows($res)." devices<br>\n";

		// For each device found a new .conf file with the device name and the date of the
		// last configuration change contained in the file name is created
		while($row = DbFetchArray($res)) {
			$filename = "./log/".rawurlencode($row['device'])."_".date("Ymd_Hi", $row['time']).".conf";

			$cfgfile = fopen($filename, "w");
			fwrite($cfgfile, $row['config']);
			fclose($cfgfile);

			// The filename is added to an array.
			// This array is later used to delete the .conf files after
			// they have been copied to the archive
			$configs[] = $filename;

			echo "Saved ".$filename."<br>\n";
			flush();
		}

		// CreateArchive() is called to make an archive out of all the configuration files that have been created
		if($type == "plain") $type = "tar";
		$archive = CreateArchive("./log/configs_".$_SESSION['user'], $type, $configs, ($timest=="checked"?1:0));
		echo "Created archive ".$archive."<br>\n";

		// Now all the .conf files are deleted
		foreach($configs as $cfg) {
			unlink($cfg);
		}
		echo "Cleaned configuration files<br>\n";

		// This is the end of the output table. It also contains the link to the archive
		echo "<p><a href=\"".$archive."\">NeDi device $cfglbl</a></div>\n";

		echo "<meta http-equiv=\"refresh\" content=\"0; URL=".$archive."\">\n";
	}
	// HTML Override
	elseif($type == "htm") {
		if($timest){
			echo "<h2>$query, $now</h2>";
		}else{
			echo "<h2>$query</h2>";
		}
		echo "<table class=\"content\"><tr class=\"$modgroup[$self]2\">";
		for ($i = 0; $i < @DbNumFields($res); ++$i) {
			$field = @DbFieldName($res, $i);
			echo  "<th>$i $field</th>\n";
		}
		echo  "</tr>\n";
		$row = 0;
		while($l = @DbFetchArray($res)) {
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			echo  "<tr class=\"$bg\" onmouseover=\"this.className='imga'\" onmouseout=\"this.className='$bg'\">";
			foreach($l as $id => $field) {
				if( preg_match("/^(origip|ip|\w+\.ip)$/",$id) ){
					echo "<td>".long2ip($field)."</td>";
				}elseif( preg_match("/^(first|last|time|(if|ip|os)?update)/",$id) ){
					echo "<td>".date($_SESSION['date'],$field)."</td>";
				}else{
					echo "<td>$field</td>";
				}
			}
			echo  "</tr>\n";
		}
		?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td><?=$row?> <?=$vallbl?></td></tr>
</table>
		<?
	}
	// For any other SQL query this is processed
	else {
		// This is the beginning of the output table
		echo "<h2>Log</h2><div class=\"textpad txta\">\n";

		// The CSV file is created by calling DbCsv()
		$csv = DbCsv($res, $sep, ($quotes=="checked"?"on":""), "./log/nedi.csv", $colhdr);
		echo "Created file ./log/nedi.csv from table ".$exptbl.($quotes=="checked"?" with surrounding quotes":"");
		echo " using separator '".$sep."'<br>\n";
		flush();

		// CreateArchive() is called to make an archive out of the CSV file that has been created
		$archive = CreateArchive("./log/export_".$_SESSION['user'], $type, "./log/nedi.csv", ($timest=="checked"?1:0));

		echo "Created archive ".$archive."<br>\n";

		// Now the CSV file is deleted
		unlink("./log/nedi.csv");
		echo "Cleaned ./log/nedi.csv<br>\n";

		// This is the end of the output table. It also contains the link to the archive
		echo "<p><a href=\"".$archive."\">Download NeDi CSV</a></div>\n";

		echo "<meta http-equiv=\"refresh\" content=\"0; URL=".$archive."\">\n";
	}
}
// If the "SQL Dump" radio button has been selected
else if($action == "sqldump") {
	// This is the beginning of the output table
	echo "<h2>Log</h2><div class=\"textpad txta\">\n";

	// The MySQL dump file is created by calling DbDump()
	$dump = DbDump($sqltbl, $dblink, "./log/nedi.sql");
	echo "Created file ./log/nedi.sql from table".(count($sqltbl)>1?"s":"")."<br>\n";
	foreach($sqltbl as $tbl) { echo "&nbsp;&nbsp;&nbsp;&nbsp;".$tbl."<br>\n"; }
	flush();

	// CreateArchive() is called to make an archive out of the SQL dump file that has been created
	$archive = CreateArchive("./log/dump_".$_SESSION['user'], $type, "./log/nedi.sql", ($timest=="checked"?1:0));
	echo "Created archive ".$archive."<br>\n";

	// Now the dump file is deleted
	unlink("./log/nedi.sql");
	echo "Cleaned ./log/nedi.sql<br>\n";

	// This is the end of the output table. It also contains the link to the archive
	echo "<p><a href=\"".$archive."\">Download NeDi dump</a></div>\n";

	echo "<meta http-equiv=\"refresh\" content=\"0; URL=".$archive."\">\n";
}
else if($isadmin and $action == "trunc") {
	$query = GenQuery("$sqltbl", "t");
	if( !@DbQuery($query,$dblink) ){echo "<h4>".DbError($dblink)."</h4>";}else{echo "<h5>".(($verb1)?"$sqltbl $dellbl $vallbl":"$sqltbl $vallbl $dellbl")." OK</h5>";}
}
else if($isadmin and $action == "opt") {
	$query = GenQuery("$sqltbl", "o");
	if( !@DbQuery($query,$dblink) ){echo "<h4>".DbError($dblink)."</h4>";}else{echo "<h5>".(($verb1)?"$optlbl $sqltbl":"$sqltbl $optlbl")." OK</h5>";}
}
else if($isadmin and $action == "rep") {
	$query = GenQuery("$sqltbl", "r");
	if( !@DbQuery($query,$dblink) ){echo "<h4>".DbError($dblink)."</h4>";}else{echo "<h5>".(($verb1)?"$replbl $sqltbl":"$sqltbl $replbl")." OK</h5>";}
}
else {
	echo "<h2>DB $dbname $sumlbl</h2>\n";
	$res = DbQuery(GenQuery("", "h"), $dblink);
	$col = 0;
	echo "<table class=\"full fixed\"><tr>\n";
	while($tab = DbFetchRow($res)){
		if($col == intval($_SESSION['col']/2)){echo "</tr><tr>";$col=0;}
		echo "<td class=\"helper\">\n\n<table class=\"content\" ><tr class=\"$modgroup[$self]2\">\n";
		echo "<th colspan=\"3\">$tab[0]</th><th>NULL</th><th>KEY</th><th>DEF</th></tr>\n";
		$cres = DbQuery(GenQuery($tab[0], "c"), $dblink);
		$row = 0;
		while($c = DbFetchRow($cres)){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			echo "<tr class=\"$bg\"><th class=\"$bi\">\n";
			echo "$row</th><td class=\"drd\">$c[0]</td><td>$c[1]</td><td class=\"prp\">$c[2]</td><td class=\"blu\">$c[3]</td><td class=\"grn\">$c[4]</td></tr>\n";
			$row++;
		}
		$recs = @DbFetchRow(DbQuery(GenQuery($tab[0], 's','count(*)'), $dblink));
		
	?>
</table>
<table class="content" >
<tr class="<?=$modgroup[$self]?>2"><td>
<div style="float:right">

<?if($recs[0]){?>
<a href="?action=export&exptbl=links&sep=%3B&query=SELECT+*+FROM+<?=$tab[0]?> limit 1000"><img src="img/16/eyes.png" title="<?=$sholbl?>"></a>
<?}
if($isadmin){?>
<a href="?action=opt&sqltbl=<?=$tab[0]?>"><img src="img/16/db.png" title="<?=$optlbl?>"></a>
<a href="?action=rep&sqltbl=<?=$tab[0]?>"><img src="img/16/dril.png" title="<?=$replbl?>"></a>
<a href="?action=trunc&sqltbl=<?=$tab[0]?>"><img src="img/16/bcnl.png" onclick="return confirm('<?=(($verb1)?"$dellbl $vallbl":"$vallbl $dellbl")?>, <?=$cfmmsg?>')" title="<?=(($verb1)?"$dellbl $vallbl":"$vallbl $dellbl")?>"></a>
<?}?>
</div>

<?=$recs[0]?> <?=$vallbl?></td></tr>
</table>

</td><?
		$col++;
	}
?>
</tr></table>
<?
}
// Now the database connection can be closed
@DbClose($dblink);

// This is the footer on the very bottom of the page
include_once("inc/footer.php");

//================================================================================
// Name: DbDump()
// 
// Description: Creates a MySQL dump of a given set of database tables.
//              The dump is written to a file, whose name has to be passed to the function
//              when calling it
//
// Parameters:
//     $tables	- An array containing the names of the database tables that
//            	  should be included in the dump
//     $link	- A valid database connection identifier
//     $outfile	- The name of the file that should be created
//
// Return value:
//     none
//
function DbDump($tables, $link, $outfile) {
	// The dump file is created and opened
	$sqlfile = fopen($outfile, "w");

	// The comment header for the MySQL dump is created...
	$sql = "--\n";
	$sql .= "-- NeDi MySQL Dump - ".date("d M Y H:i")."\n";
	$sql .= "-- ------------------------------------------------------\n\n";
	// ...and written to the file
	fwrite($sqlfile, $sql);
	$sql = "";

	// All the tables are dumped one after the other
	foreach($tables as $tbl) {

		// Some SQL comments
		$sql .= "--\n";
		$sql .= "-- Table structure for table `".$tbl."`\n";
		$sql .= "--\n\n";

		// This is to make sure, that there is no table with the same name
		$sql .= "DROP TABLE IF EXISTS `".$tbl."`;\n";

		// This query gives us the complete SQL query to create the table structure
		$res = DbQuery("SHOW CREATE TABLE `$tbl`;", $link);

		$field = array();
		while($field = DbFetchArray($res)) {
			// Now the SQL command used to create the table structure is read from the database
			$sql .= $field['Create Table'].";\n\n";
		}

		// Another block of SQL comments
		$sql .= "--\n";
		$sql .= "-- Dumping data for table `".$tbl."`\n";
		$sql .= "--\n\n";

		// To make sure, that we are the only one working on the table, when importing the dump,
		// this SQL command is used
		$sql .= "LOCK TABLES `".$tbl."` WRITE;\n";

		$chfields = array();
		$field = array();

		// We want to check each column of the table, if its datatype is numeric or not.
		// Because if it's not numeric, we want to surround the content in the INSERT command
		// with "". But if it is numeric we must not put "" around the content.
		$res = DbQuery("DESCRIBE `$tbl`;", $link);
		while($field = DbFetchArray($res)) {
			// If a field is either of type "varchar()" or "text" the we add a '1' to the array...
			if( preg_match("/char|text/",$field['Type']) ) {
				$chfields[] = 1;
			}
			// ...otherwise, we add a '0'
			else {
				$chfields[] = 0;
			}
		}

		// The data, which we gathered since the last time we wrote something to the file
		// is written down to the SQL dump file.
		fwrite($sqlfile, $sql);
		$sql = "";

		// Now we want to have all the data from the table
		$res = DbQuery(GenQuery($tbl, "s", "*"), $link);
// 		$res = DbQuery("SELECT * FROM `".$tbl."`;", $link);

		$field = array();
		while($field = DbFetchRow($res)) {
			// For each record a new INSERT command is created
			$sql .= "INSERT INTO `".$tbl."` VALUES (";
			// The fields of the record are inserted one after the other
			for($i=0; $i<count($field); $i++) {
				// If the current field is a "varchar()" or "text" field
				// then it is surrounded by "". The array $chfields[]
				// tells us, if the current field is numeric (0) or not (1).
				if(($chfields[$i] == 1)&&($field[$i]!="")) $sql .= "\"";
				if($field[$i] != "") {
					$field[$i] = str_replace("\"", "\\\"", $field[$i]);
					$sql .= $field[$i];
				}
				else {
					$sql .= "NULL";
				}
 				if(($chfields[$i] == 1)&&($field[$i]!="")) $sql .= "\"";
				if($i < count($field)-1) $sql .= ", ";
			}
			$sql .= ");\n";

			// The INSERT command for the current record is written to the dump file
			fwrite($sqlfile, $sql);
			$sql = "";
		}

		// After having inserted all the data to the database table
		// the table can be unlocked
		$sql .= "UNLOCK TABLES;\n\n";
		fwrite($sqlfile, $sql);
		$sql = "";
	}

	// Finally the SQL dump file is closed
	fclose($sqlfile);
}

//================================================================================
// Name: DbCsv()
// 
// Description: Creates a CSV file of a given MySQL query result.
//              When calling the function you can choose if you want
//              to have quotes around the elements of the CSV file.
//              The separator between the elements has to be provided when
//              calling DbCsv()
//
// Parameters:
//     $res		- A valid MySQL result identifier
//     $sep		- The separator to put between the elements
//         		  This can also be longer than one character
//     $quotes	- "on" to have quotes around the elements
//     $outfile	- The name of the file that should be created
//
// Return value:
//     none
//

function DbCsv($res, $sep, $quotes, $outfile, $head) {

	global $datfmt;
	// The CSV file is created and opened
	$csvfile = fopen($outfile, "w");

	// Add column header, if desired
	if($head){
		$csv = "";
		for ($i = 0; $i < @DbNumFields($res); ++$i) {
			if($quotes == "on") $csv .= "\"";
			$csv .= @DbFieldName($res, $i);
			echo "$csv ";
			if($quotes == "on") $csv .= "\"";
			$csv .= $sep;
		}
		// The last separator of a line is always cut off
		$csv = trim($csv, $sep);

		// For each row a single line of the file is used
		$csv .= "\r\n";

		// After having prepared the CSV row, it is written to the file
		fwrite($csvfile, $csv);
	}

	// The rows of the given result are processed one after the other
	while($row = DbFetchArray($res)) {
		$csv = "";
		// Each element is added to the string individually
		foreach($row as $id => $field) {
			if(preg_match("/^(origip|ip)$/",$id) ){$field = long2ip($field);}
			if(preg_match("/^(firstseen|lastseen|time|i[fp]update)$/",$id) ){$field = date($datfmt,$field);}
			// If quotes are wished, they are put around the element
			if($quotes == "on") $csv .= "\"";
			$csv .= $field;
			if($quotes == "on") $csv .= "\"";
			$csv .= $sep;
		}
		// The last separator of a line is always cut off
		$csv = trim($csv, $sep);

		// For each row a single line of the file is used
		$csv .= "\r\n";

		// After having prepared the CSV row, it is written to the file
		fwrite($csvfile, $csv);
	}

	// When finished, the CSV file is closed
	fclose($csvfile);
}

//===================================================================
// Name: CreateArchive()
// 
// Description: Creates an archive out of one ore more existing files.
//              You can have either a .tar, .gz or a .bz2 archive.
//              If you want, you can have the creation time included
//              in the file name of the archive.
//
// Parameters:
//     $outfile	- Name of the archive to create (without file extension)
//     $type	- The type of compression. Accepts "gz", "bz2" or "tar" (for
//          	  a simple .tar archive).
//     $infiles	- If it's only one file, this can be a string. For more files,
//             	  you can use an array.
//     $timest	- If you wish to have a timestamp in your archive's file name,
//            	  you can set this parameter to the value 1.
//
// Return value:
//     The complete file name of the created archive (including its file extension)
//
function CreateArchive($outfile, $type, $infiles, $timest) {

	// This is used to create .tar archives
	// It is contained in the PEAR package Archive_Tar
	include_once("Archive/Tar.php");

	// Multiple files cannot be provided in plain format.
	// Therefore they are packed in a tar archive.
	if(is_array($infiles) && ($type == "plain")) {
		$type = "tar";
	}

	// There may already be an archive for the current user
	// saved in the ./html/log directory. This file is deleted
	// to ensure that there can only be one archive with the same
	// archive name.
	$glob = glob($outfile."*");
	if(count($glob) > 0) {
		foreach(glob($outfile."*") as $file) {
			unlink($file);
		}
	}

	$tarname = $outfile;

	// If the user wishes to have the creation time in the archive's file name.
	// it gets added here
	if($timest == 1) {
		$tarname .= "_".date("Ymd_Hi");
	}

//	if($type != "plain") {
	if(is_array($infiles)) {
		$tarname .= ".tar";
	
		// Now a new Archive_Tar object is created
		// This object is used to create the .tar archive
		$tar = new Archive_Tar($tarname);
	
		// If $infile is only a string containing one single file name,
		// this string is put into an array. If there are more than one
		// input files, we already have an array and thus don't need to
		// create a new one.
		if(is_array($infiles)) {
			$tar->create($infiles); // This creates the .tar archive
		}
		else {
			$tar->create(array($infiles)); // This creates the .tar archive
		}
	}
	else {
		if(stristr($infiles, ".csv") != false) {
			$tarname .= ".csv";
		}
		elseif(stristr($infiles, ".sql") != false) {
			$tarname .= ".sql";
		}
		copy($infiles, $tarname);
	}
	
	// Depending on the parameter $type the archive gets compressed
	// If $type is empty or an invalid value, the .tar archive stays
	// unchanged
	switch($type) {
		case "gz":
			// The previously created .tar archive is opened for reading
		echo "<script language=\"JavaScript\">alert('$tarname');</script>";
			$archive = fopen($tarname, "r");
			
			// This is the new gzip archive that is going to be created
			$gzip = gzopen("$tarname.gz", "w");

			// The size of the .tar archive is counted and the number of
			// 2 MB blocks is counted
			$mb = ceil(filesize($tarname) / (1024*1024*2));
			
			// The .tar archive is split into $mb parts and these parts are
			// read and written to the gzip archive one after the other
			for($i=0; $i<$mb; $i++) {
				gzwrite($gzip, fread($archive, filesize($tarname)/$mb));
			}

			// Both archives, the .tar archive and the new gzip archive are closed
			gzclose($gzip);
			fclose($archive);

			// The .tar archive must be deleted manually
			unlink($tarname);
			
			// The name of the gzip file is returned, so the user does not have
			// to think about file extensions when calling this function
			return $tarname.".gz";
			break;
		case "bz2":
			// The previously created .tar archive is opened for reading
			$archive = fopen($tarname, "r");
			
			// This is the new bzip2 archive that is going to be created
			$bzip2 = bzopen("$tarname.bz2", "w");

			// The size of the .tar archive is counted and the number of
			// 2 MB blocks is counted
			$mb = ceil(filesize($tarname) / (1024*1024*5));
			
			// The .tar archive is split into $mb parts and these parts are
			// read and written to the bzip2 archive one after the other
			for($i=0; $i<$mb; $i++) {
				bzwrite($bzip2, fread($archive, filesize($tarname)/$mb));
			}

			// Both archives, the .tar archive and the new bzip2 archive are closed
			bzclose($bzip2);
			fclose($archive);

			// The .tar archive must be deleted manually
			unlink($tarname);
			
			// The name of the bzip2 file is returned, so the user does not have
			// to think about file extensions when calling this function
			return $tarname.".bz2";
			break;
		case "tar":
		case "plain":
		default:
			// In any other case the .tar file is left unchanged and its file name is returned
			return $tarname;
	}
}

?>
