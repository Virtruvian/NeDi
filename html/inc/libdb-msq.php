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
			$status = "<img src=\"img/16/bdis.png\" title=\"$alrlbl OK\" vspace=\"4\">";
		}else{
			if( !@DbQuery("INSERT INTO $table ($col) VALUES ($val)",$link) ){
				$status = "<img src=\"img/16/bcnl.png\" title=\"" .DbError($link)."\" vspace=\"4\">";
			}else{
				$status = "<img src=\"img/16/bchk.png\" title=\"$addlbl OK\" vspace=\"4\">";
			}
		}
	}else{
		print @DbError($link);
	}
	return $status;
}

//===============================================================================
// Generates SQL queries:
//
// $tbl	= table to apply query to
// $do 	s= select (is default), i=insert (using $in for columns and $st for values), o=optimize, d=delete, p=drop db
//	b=show DBs ($col used as operator with $tbl), h=show tables, c=show columns, t=truncate, u=update (using $in,$op,$st to set values 
//	and "WHERE $col $ord $lim" to match), g=group, a=average, m=sum, x=max 
// $col	= column(s) to display or to group by (separate with ; to exlude from grouping or to calculate with $do= a, m or x)
// $ord	= order by (where ifname also takes numerical interface sorting (e.g. 0/1) into account)
// $lim	= limiting results
// $in,op,st	= array of columns,operators and strings to be used for WHERE in UPDATE, INSERT, SELECT and DELETE queries
// $co	= combines current values with the next series of $in,op,st
//
// SELECT and DELETE columns treatment: 
// * ip:	Input will be converted to decimal, in case of dotted notation and masked if a prefix is set.
// * time:	Time will be turned into EPOC, if it's not a number already.
// * mac:	. : - are removed
//
function GenQuery($tbl,$do='s',$col='*',$ord='',$lim='',$rawin=array(),$rawop=array(),$rawst=array(),$rawco=array(),$jn=""){

	global $debug;

	$tbl = mysql_real_escape_string($tbl);								# Mitigate SQL injection
	$ord = mysql_real_escape_string($ord);
	$lim = mysql_real_escape_string($lim);
	
	$in = array_map( 'mysql_real_escape_string', $rawin );
	$op = array_map( 'mysql_real_escape_string', $rawop );
	$st = array_map( 'mysql_real_escape_string', $rawst );
	$co = array_map( 'mysql_real_escape_string', $rawco );
	if($do == 'i'){
		$qry = "INSERT INTO $tbl (". implode(',',$in) .") VALUES (\"". implode('","',$st) ."\")";
	}elseif($do == 'u'){
		if( $in[0] ){
			$x = 0;
			foreach ($in as $c){
				$o = ( array_key_exists($x, $op) )?$op[$x]:'=';				# Use '=' if no operator is set
				if($c){$s[]="$c $o \"$st[$x]\"";}
				$x++;
			}
			$qry = "UPDATE $tbl SET ". implode(',',$s) ." WHERE $col $ord \"$lim\"";
		}
	}elseif($do ==  'b'){
		$qry = "SHOW DATABASES $col \"$tbl\"";
	}elseif($do ==  'p'){
		$qry = "DROP DATABASE $tbl";
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
			$ord  = ($desc)?substr($ord,0,-5):$ord;						# Cut away desc for proper handling below
			$oar = explode(".", $ord);							# Handle table in join queries
			$icol = ($oar[0] == 'ifname' or $oar[0] == 'nbrifname')?'ifname':"$oar[0].ifname";
			$dcol = ($oar[0] == 'ifname' or $oar[0] == 'nbrifname')?'device':"$oar[0].device";
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
					}elseif(preg_match("/^(dev|orig|nod|if|mon)ip$/",$c) and !preg_match('/^[0-9]+$/',$v) ){	# Do we have an dotted IP?
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
					}elseif( preg_match("/^(if|nod|mon)ip6$/",$c) ){
						$c = "HEX($c)";
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

	if($debug){
		echo "<div class=\"textpad warn\">";
		debug_print_backtrace();
		echo "<p><a href=\"System-Export.php?action=export&query=".urlencode($qry)."\">$qry</a></div>\n";
	}

	return $qry;
}

?>
