<?php
# Program: Devices-Interfaces.php
# Programmer: Remo Rickli

$printable = 1;
$exportxls = 1;

include_once ("inc/header.php");
include_once ("inc/libdev.php");

$_GET = sanitize($_GET);
$in = isset($_GET['in']) ? $_GET['in'] : array();
$op = isset($_GET['op']) ? $_GET['op'] : array();
$st = isset($_GET['st']) ? $_GET['st'] : array();
$co = isset($_GET['co']) ? $_GET['co'] : array();

$ord = isset($_GET['ord']) ? $_GET['ord'] : "";
if($_SESSION['opt'] and !$ord and $in[0]) $ord = $in[0];

$map = isset($_GET['map']) ? "checked" : "";
$lim = isset($_GET['lim']) ? preg_replace('/\D+/','',$_GET['lim']) : $listlim;

if( is_numeric($_GET['tal']) ) $colup['trafalert'] = $_GET['tal'];
if( is_numeric($_GET['bal']) ) $colup['brcalert'] = $_GET['bal'];
if( is_numeric($_GET['maf']) ) $colup['macflood'] = $_GET['maf'];

if( isset($_GET['col']) ){
	$col = $_GET['col'];
	if($_SESSION['opt']) $_SESSION['intcol'] = $col;
}elseif( isset($_SESSION['intcol']) ){
	$col = $_SESSION['intcol'];
}else{
	$col = array('imBL','ifname','device','ifdesc','alias','comment');
}

$cols = array(	"imBL"=>$imglbl,
		"ifname"=>"IF $namlbl",
		"ifidx"=>"IF $idxlbl",
		"device"=>"Device $namlbl",
		"type"=>"Device $typlbl",
		"location"=>$loclbl,
		"contact"=>$conlbl,
		"firstdis"=>"$fislbl $dsclbl",
		"lastdis"=>"$laslbl $dsclbl",
		"linktype"=>"Link $typlbl",
		"iftype"=>"IF $typlbl",
		"ifmac"=>"MAC $adrlbl",
		"ifdesc"=>$deslbl,
		"alias"=>"Alias",
		"ifstat"=>$stalbl,
		"lastchg"=>"$laslbl $chglbl",
		"speed"=>$spdlbl,
		"duplex"=>"Duplex",
		"pvid"=>"Port Vlan $idxlbl",
		"inoct"=>"$totlbl $trflbl ".substr($inblbl,0,3),
		"outoct"=>"$totlbl $trflbl ".substr($oublbl,0,3),
		"inerr"=>"$totlbl $errlbl ".substr($inblbl,0,3),
		"outerr"=>"$totlbl $errlbl ".substr($oublbl,0,3),
		"indis"=>"$totlbl $dcalbl ".substr($inblbl,0,3),
		"outdis"=>"$totlbl $dcalbl ".substr($oublbl,0,3),
		"inbrc"=>"$totlbl Broadcasts ".substr($inblbl,0,3),
		"dinoct"=>"$laslbl $trflbl ".substr($inblbl,0,3),
		"doutoct"=>"$laslbl $trflbl ".substr($oublbl,0,3),
		"dinerr"=>"$laslbl $errlbl ".substr($inblbl,0,3),
		"douterr"=>"$laslbl $errlbl ".substr($oublbl,0,3),
		"dindis"=>"$laslbl $dcalbl ".substr($inblbl,0,3),
		"doutdis"=>"$laslbl $dcalbl ".substr($oublbl,0,3),
		"dinbrc"=>"$laslbl Broadcasts ".substr($inblbl,0,3),
		"poe"=>'PoE',
		"comment"=>$cmtlbl,
		"trafalert"=>"$trflbl $mlvl[200]",
		"bcastalert"=>"Bcast $mlvl[200]",
		"macflood"=>"MAC Flood",
		"poNS"=>$poplbl,
		"gfNS"=>"IF $gralbl",
		"rdrNS"=>"Radar $gralbl"
		);

$link = DbConnect($dbhost,$dbuser,$dbpass,$dbname);							# Above print-header!
?>
<script src="inc/Chart.min.js"></script>

<h1>Interface <?= $lstlbl ?></h1>

<?php  if( !isset($_GET['print']) and !isset($_GET['xls']) ) { ?>
<form method="get" name="list" action="<?= $self ?>.php">
<table class="content"><tr class="bgmain">
<td class="ctr s">
	<a href="<?= $self ?>.php"><img src="img/32/<?= $selfi ?>.png" title="<?= $self ?>"></a>
</td>
<td>
<?php Filters(); ?>
</td>
<td class="ctr">
	<a href="?in[]=comment&op[]=~&st[]=Loop!"><img src="img/16/brld.png" title="<?= $cnclbl ?> Loops"></a>
	<a href="?in[]=linktype&op[]=~&st[]=F%24&col[]=imBL&col[]=ifname&col[]=device&col[]=linktype&col[]=ifdesc&col[]=alias&col[]=comment&col[]=poNS&lim=100&ord=ifname"><img src="img/tel.png" title="VoIP <?= $cnclbl ?>"></a>
	<br>
	<a href="?in[]=linktype&op[]=~&st[]=.&co[]=AND&in[]=lastchg&op[]=>&st[]=<?= strtotime("-1 day") ?>&col[]=imBL&col[]=ifname&col[]=device&col[]=linktype&col[]=alias&col[]=lastchg&col[]=rdrNS&lim=10&ord=lastchg+desc" class="warn" ><img src="img/16/link.png" title="<?= $cnclbl ?> <?= $chglbl ?> <?= $tim['t'] ?>"></a>
	<a href="?in[]=iftype&op[]=~&st[]=^(6|7|117)$&co[]=AND&in[]=ifstat&op[]=%3D&st[]=0&ord=lastchg+desc" class="alrm" ><img src="img/p45.png" title="<?= $porlbl ?> <?= $dsalbl ?>"></a>
</td>
<td class="ctr">
	<select multiple name="col[]" size="6" title="<?= $collbl ?>">
<?php
foreach ($cols as $k => $v){
       echo "		<option value=\"$k\" ".((in_array($k,$col))?" selected":"").">$v\n";
}
?>
	</select>
</td>
<td>
	<img src="img/16/paint.png" title="<?= (($verb1)?"$sholbl $laslbl Map":"Map $laslbl $sholbl") ?>"> 
	<input type="checkbox" name="map" <?= $map ?>>
	<br>
	<img src="img/16/form.png" title="<?= $limlbl ?>"> 
	<select size="1" name="lim">
<?php selectbox("limit",$lim) ?>
	</select>
	<p>
	<img src="img/16/bino.png" title="<?= $vallbl ?> <?= $mlvl['200'] ?>">
	<input type="number" min="0" max="100" step="5" name="tal" class="xs" title="<?= $trflbl ?> <?= $mlvl[200] ?> <?= $trslbl ?> %">
	<input type="number" min="0" max="500" step="5"  name="bal" class="xs" title="<?= $inblbl ?> Broadcast  <?= $mlvl[200] ?> <?= $trslbl ?> 1/s">
	<input type="number" name="maf" class="xs" title="MAC <?= $mlvl[200] ?> <?= $trslbl ?>">
</td>
<td class="ctr s">
	<input type="submit" class="button" value="<?= $sholbl ?>">
	<br>
	<input type="submit" class="button" name="trk" value="Track">
	<br>
	<input type="submit" class="button" name="upm" value="<?= $updlbl ?>">
</td>
</tr></table>
</form>
<p>
<?php
}
if( count($in) ){
	if ($map and !isset($_GET['xls']) and file_exists("map/map_$_SESSION[user].php")) {
		echo "<div class=\"ctr\">\n	<h2>$netlbl Map</h2>\n";
		echo "	<img src=\"map/map_$_SESSION[user].php\" style=\"border:1px solid black\">\n</div>\n<p>\n";
	}
	Condition($in,$op,$st,$co);
	TblHead("bgsub",1);
	$query	= GenQuery('interfaces','s','interfaces.*,type,firstdis,lastdis,location,contact',$ord,$lim,$in,$op,$st,$co,'LEFT JOIN devices USING (device)');
	$res	= DbQuery($query,$link);
	if($res){
		$row = 0;
		$trkst = '';
		$monst = '';
		while( ($if = DbFetchRow($res)) ){
			if ($row % 2){$bg = "txta"; $bi = "imga";}else{$bg = "txtb"; $bi = "imgb";}
			$row++;
			$ud		= urlencode($if[0]);
			$ui		= urlencode($if[1]);
			list($fc,$lc)	= Agecol($if[33],$if[34],$row % 2);
			list($cc,$cc)	= Agecol($if[26],$if[26],$row % 2);
			list($ifb,$ifs)	= Ifdbstat($if[8]);
			list($ifi,$ift)	= Iftype($if[4]);

			if($isadmin and $_GET['trk']){
				$trkst = AddRecord('nodetrack',"device='".DbEscapeString($if[0])."' AND ifname='$if[1]'","device,ifname,value,source,usrname,time","'".DbEscapeString($if[0])."','$if[1]','-','-','$_SESSION[user]','".time()."'");
			}
			if($isadmin and $_GET['upm'] and count($colup) ){
				$query	= GenQuery('interfaces','u',"device = '".DbEscapeString($if[0])."' and ifname = '$if[1]'",'','',array_keys($colup),array(),array_values($colup) );
				$monst = DbQuery($query,$link)?"<img src=\"img/16/bchk.png\" title=\" $trslbl $updlbl OK\" vspace=\"4\">":"<img src=\"img/16/bcnl.png\" title=\"".DbError($link)."\" vspace=\"4\">";				
			}

			TblRow($bg);
			if(in_array("imBL",$col))	TblCell("<img src=\"img/$ifi\" title=\"$ift - $ifs\">",'',(($ifb)?$ifb:$bi).' ctr xs');
			if( in_array("ifname",$col) )	TblCell("$if[1] $trkst $monst","?in[]=ifname&op[]==&st[]=$ui","$bi b");
			if( in_array("ifidx",$col) )	TblCell($if[2],"?in[]=ifidx&op[]==&st[]=$if[2]",'rgt');
			if( in_array("device",$col) )	TblCell($if[0],"?in[]=device&op[]==&st[]=$ud&ord=ifname",'nw',"<a href=\"Devices-Status.php?dev=$ud\"><img src=\"img/16/sys.png\"></a>");
			if( in_array("type",$col) )	TblCell($if[32],"?in[]=type&op[]==&st[]=$if[32]");
			if( in_array("location",$col) )	TblCell( $if[35],"?in[]=location&op[]==&st[]=".urlencode($if[35]) );
			if( in_array("contact",$col) )	TblCell( $if[36],"?in[]=contact&op[]==&st[]=".urlencode($if[36]) );
			if( in_array("firstdis",$col) )	TblCell( date($_SESSION['timf'],$if[33]),"?in[]=firstdis&op[]==&st[]=$if[33]",'','',"background-color:#$fc" );
			if( in_array("lastdis",$col) )	TblCell( date($_SESSION['timf'],$if[34]),"?in[]=lastdis&op[]==&st[]=$if[34]",'','',"background-color:#$lc" );
			if( in_array("linktype",$col) )	TblCell($if[3],"?in[]=linktype&op[]==&st[]=$if[3]");
			if( in_array("iftype",$col) )	TblCell($if[4],"?in[]=iftype&op[]==&st[]=$if[4]");
			if( in_array("ifmac",$col) )	TblCell($if[5],'','mrn code');
			if( in_array("ifdesc",$col) )	TblCell($if[6]);
			if( in_array("alias",$col) )	TblCell($if[7],"?in[]=alias&op[]==&st[]=$if[7]");
			if( in_array("ifstat",$col) )	TblCell($if[8],'','ctr');
			if( in_array("lastchg",$col) )	TblCell(date($_SESSION['timf'],$if[26]),"?in[]=lastchg&op[]==&st[]=$if[26]",'','',"background-color:#$cc");
			if( in_array("speed",$col) )	TblCell( DecFix($if[9]),'','rgt' );
			if( in_array("duplex",$col) )	TblCell($if[10],'','ctr');
			if( in_array("pvid",$col) )	TblCell($if[11],'','rgt');
			if( in_array("inoct",$col) )	TblCell( DecFix($if[12]),'','rgt' );
			if( in_array("outoct",$col) )	TblCell( DecFix($if[14]),'','rgt' );
			if( in_array("inerr",$col) )	TblCell( DecFix($if[13]),'','rgt' );
			if( in_array("outerr",$col) )	TblCell( DecFix($if[15]),'','rgt' );
			if( in_array("indis",$col) )	TblCell( DecFix($if[20]),'','rgt' );
			if( in_array("outdis",$col) )	TblCell( DecFix($if[21]),'','rgt' );
			if( in_array("inbrc",$col) )	TblCell( DecFix($if[24]),'','rgt' );
			if( in_array("dinoct",$col) )	TblCell( DecFix($if[16]),'','rgt' );
			if( in_array("doutoct",$col) )	TblCell( DecFix($if[18]),'','rgt' );
			if( in_array("dinerr",$col) )	TblCell( DecFix($if[17]),'','rgt' );
			if( in_array("douterr",$col) )	TblCell( DecFix($if[19]),'','rgt' );
			if( in_array("dindis",$col) )	TblCell( DecFix($if[22]),'','rgt' );
			if( in_array("doutdis",$col) )	TblCell( DecFix($if[23]),'','rgt' );
			if( in_array("dinbrc",$col) )	TblCell( DecFix($if[25]),'','rgt' );
			if( in_array("poe",$col) )	TblCell($if[27]."mW","?in[]=poe&op[]==&st[]=$if[27]",'rgt');
			if( in_array("comment",$col) )	TblCell($if[28]);
			if( in_array("trafalert",$col) )TblCell($if[29].'%');
			if( in_array("bcastalert",$col) )TblCell($if[30].'/s');
			if( in_array("macflood",$col) )	TblCell($if[31]);
			if( in_array("poNS",$col) and !isset($_GET['xls']) ){
				$pop = NodPop(array('device','ifname'),array('=','='),array($if[0],$if[1]),array('AND'));
				if($pop){
					TblCell(' '.$pop,"Nodes-List.php?in[]=device&op[]==&st[]=$ud&in[]=ifname&op[]==&st[]=$ui&co[]=AND",'nw','+'.Bar($pop,24,'mi'));
				}else{
					TblCell();
				}
				DbFreeResult($np);
			}
			if( in_array("gfNS",$col) and !isset($_GET['xls']) ){
				echo "		<td class=\"ctr nw\">\n";
				IfGraphs($ud,$ui,$if[9], $_SESSION['gsiz']);
				echo "		</td>\n";
			}
			if( in_array("rdrNS",$col) and !isset($_GET['xls']) ){
				echo "		<td class=\"ctr nw\">\n";
				IfRadar("rt$row",$_SESSION['gsiz'],'248',$if[12],$if[14],$if[13],$if[15],$if[20],$if[21],$if[24]);
				IfRadar("rl$row",$_SESSION['gsiz'],'284',$if[16],$if[18],$if[17],$if[19],$if[22],$if[23],$if[25]);
				echo "		</td>\n";
			}
			echo "	</tr>\n";
		}
		DbFreeResult($res);
	}else{
		print DbError($link);
	}
	TblFoot("bgsub", count($col), "$row Interfaces".(($ord)?", $srtlbl: $ord":"").(($lim)?", $limlbl: $lim":"") );
}
include_once ("inc/footer.php");
?>
