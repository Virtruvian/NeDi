<?PHP
//===============================
// mySQL functions.
//===============================

function DbConnect($host,$user,$pass,$db){
	$l = @mysql_connect($host,$user,$pass) or die("Could not connect to $db@$host with $user");
	mysql_select_db($db) or die("could not select $db");
	return $l;
}

function DbQuery($q,$l){
	return @mysql_query($q,$l);
}

function DbClose($l){
        return @mysql_close($l);
}

function DbFieldName($r, $f){
        return @mysql_field_name($r, $f);
}

function DbNumFields($r){
        return @mysql_num_fields($r);
}

function DbNumRows($r){
        return @mysql_num_rows($r);
}

function DbFetchRow($r){
        return @mysql_fetch_row($r);
}

function DbFetchArray($r){
        return mysql_fetch_assoc($r);
}

function DbFreeResult($r){
        return @mysql_free_result($r);
}

function DbAffectedRows($r){
        return @mysql_affected_rows($r);
}

function DbEscapeString($r){
        return @mysql_real_escape_string($r);
}

function DbError($r){
        return @mysql_error($r);
}

//===================================================================
// Add record if it doesn't exist yet
function AddRecord($table,$key,$col,$val){

	global $link, $alrlbl, $addlbl;

	$mres	= @DbQuery("SELECT * FROM $table WHERE $key",$link);
	if($mres){
		if( @DbNumRows($mres) ){
			$status = "<img src=\"img/16/bdis.png\" title=\"$alrlbl OK\">";
		}else{
			if( !@DbQuery("INSERT INTO $table ($col) VALUES ($val)",$link) ){
				$status = "<img src=\"img/16/bcnl.png\" title=\"" .DbError($link)."\">";
			}else{
				$status = "<img src=\"img/16/bchk.png\" title=\"$addlbl OK\">";
			}
		}
	}else{
		print @DbError($link);
	}
	return $status;
}

//===============================================================================
// Returns join based on column TODO forget about this (too complex fir librep)?

function JoinDev($ina){

	if($ina == 'vlanid'){
		return array($ina,'LEFT JOIN vlans USING (device)');
	}elseif($ina == 'ifip' or $ina == 'vrfname'){
		return array($ina,'LEFT JOIN networks USING (device)');
	}elseif($ina == 'neighbor'){
		$ina = "CONCAT_WS(',',device,neighbor)";
		return array($ina,'LEFT JOIN links USING (device)');
	}else{
		return array($ina,'');
	}
}

//===============================================================================
// Generates SQL queries:
//
// $tbl	= table to apply query to
// $do 	's'= select (is default), 'i'=insert (using $in for columns and $st for values), 't'=show tables, 'c'=show columns,
//	'u'=update (using $in,$st to set and $col,$ord to match), 'o'=optimize, 'd'=delete, 'g'=group, 'a'=average, 'm'=sum, 'x'=max 
// $col	= column(s) to display or to group by (separate with ; to exlude from grouping or to calculate with $do= a, m or x)
// $ord	= order by (where device also takes numerical interface sorting (with /) into account)
// $lim	= limiting results
// $in,op,st	= array of columns,operators and strings to be used for WHERE in UPDATE, INSERT, SELECT and DELETE queries
// $co	= combines current values with the next series of $in,op,st
//
// SELECT and DELETE columns treatment: 
// * ip:	Input will be converted to decimal, in case of dotted notation and masked if a prefix is set.
// * time:	Time will be turned into EPOC, if it's not a number already.
// * mac:	. : - are removed
//
function GenQuery($tbl,$do='s',$col='*',$ord='',$lim='',$in=array(),$op=array(),$st=array(),$co=array(),$jn=""){
#TODO add sanitization here using mysql_real_escape_string() or addslashes()
	global $debug;

	if($do == 'i'){
		$qry = "INSERT INTO $tbl (". implode(',',$in) .") VALUES (\"". implode('","',$st) ."\")";
	}elseif($do == 'u'){
		if( $in[0] ){
			$x = 0;
			foreach ($in as $c){
				if($c){$s[]="$c=\"$st[$x]\"";}
				$x++;
			}
			$w   = ($ord)?" WHERE $col=\"$ord\"":" WHERE $col";
			$qry = "UPDATE $tbl SET ". implode(',',$s) ." $w";
		}
	}elseif($do ==  'h'){
		$qry = "SHOW TABLES $tbl";
	}elseif($do ==  't'){
		$qry = "TRUNCATE $tbl";
	}elseif($do ==  'o'){
		$qry = "OPTIMIZE TABLE $tbl";
	}elseif($do == 'c'){
		$qry = "SHOW COLUMNS FROM $tbl";
	}elseif($do == 'r'){
		$qry = "REPAIR TABLE $tbl";
	}else{
		$l = ($lim) ? "LIMIT $lim" : "";
		if( strstr($ord, 'ifname') ){
			$desc = strpos($ord, 'desc')?" desc":"";
			$ord  = ($desc)?substr($ord,0,-5):$ord;		# Cut away desc for proper handling below
			$colarr = explode(".", $ord);			# Handle table in join queries
			$icol = ($colarr[0] == 'ifname')?'ifname':"$colarr[0].ifname";
			$dcol = ($colarr[0] == 'ifname')?'device':"$colarr[0].device";
			$od = "ORDER BY $dcol $desc,SUBSTRING_INDEX($icol, '/', 1), SUBSTRING_INDEX($icol, '/', -1)*1+0";
		}elseif($ord){
			$od = "ORDER BY $ord";
		}else{
			$od = "";
		}

		if( isset($st[0]) and $st[0] !== ""  ){
			$w = "WHERE";
			$x = 0;
			do{
				$cop = ( isset($co[$x]) and $in[$x+1] ) ? $co[$x] : "";
				if($op[$x] and $in[$x]){
					$c = $in[$x];
					$v = $st[$x];
					$o = $op[$x];
					if( preg_match("/^(first|last|start|end|time|(if|ip|os)?update)/",$c) and !preg_match("/^[0-9]+$/",$v) ){
						$v = strtotime($v);
					}elseif($c == 'mac'){
						$v = preg_replace("/[.:-]/","", $v);
					}elseif(preg_match("/^(dev|orig|nod|if|mon)ip$/",$c) and !preg_match('/^[0-9]+$/',$v) ){			# Do we have an dotted IP?
						if( strstr($v,'/') ){									# CIDR?
							list($ip, $prefix) = explode('/', $v);
							$dip = sprintf("%u", ip2long($ip));
							$dmsk = 0xffffffff << (32 - $prefix);
							$dnet = sprintf("%u", ip2long($ip) & $dmsk );
							$c = "$c & $dmsk";
							$v = $dnet;
						}else{
							if( preg_match('/regexp/',$o)){							# regexp operator?
								$c = "inet_ntoa($c)";
							}else{										# converting plain address
								$v = sprintf("%u", ip2long($v));
							}
						}
					}
					if( strpos($o,'CI') ){
						$c = "LCASE($in[$x])";
						$o = substr($op[$x],0,-2);
					}
					if(strstr($o,'regexp') and $v == '' ){$v = '.';}
					if( strstr($o, 'COL ') ){
						$o = substr($o,4);
					}else{
						$v = "\"$v\"";
					}
					$w .= " $c $o $v $cop";
				}
				$x++;
			}while($cop);
		}elseif( isset($co[0]) and $co[0] != "" ){
			$w = "WHERE $in[0] $co[0] $in[1]";
		}else{
			$w = "";
		}

		if(isset($_SESSION['view']) and $_SESSION['view'] and (strstr($jn,'JOIN devices') or $tbl == 'devices')){
			$w = ($w)?"$w AND $_SESSION[view]":"WHERE $_SESSION[view]";
		}

		if($do == 'd'){
			$qry = "DELETE FROM $tbl $w $od $l";
		}elseif($do == 's'){
			$qry = "SELECT $col FROM $tbl $jn $w $od $l";
		}else{
			$cal = "";
			$hav = "";
			$excol = explode(";",$col);
			if(array_key_exists(1,$excol) and $excol[1]){
				$col = $excol[0];
				if(array_key_exists(2,$excol) and $excol[2]){$hav = "having($excol[2])";}
				if($do == 'a'){$cal = ", avg($excol[1]) as avg";}
				elseif($do == 'm'){$cal = ", sum($excol[1]) as sum";}
				elseif($do == 'x'){$cal = ", max($excol[1]) as max";}
				else{$cal = ", $excol[1]";}
			}
			$qry = "SELECT $col,count(*) as cnt$cal FROM  $tbl $jn $w GROUP BY $col $hav $od $l";
		}
	}

	if($debug){echo "<div class=\"textpad noti\"><a href=\"System-Export.php?action=export&query=".urlencode($qry)."\">$qry</a></div>\n";}

	return $qry;
}

?>
