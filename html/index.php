<?
/*
#============================================================================
# Program: index.php (NeDi GUI)
# Programmers: Remo Rickli & community
#
#    This program is free software: you can redistribute it and/or modify
#    it under the terms of the GNU General Public License as published by
#    the Free Software Foundation, either version 3 of the License, or
#    (at your option) any later version.

#    This program is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU General Public License for more details.

#    You should have received a copy of the GNU General Public License
#    along with this program.  If not, see <http://www.gnu.org/licenses/>.
#============================================================================
# Visit http://www.nedi.ch/ for more information.
#============================================================================
# DATE		COMMENT
# -----------------------------------------------------------
# 01/08/10	Refer to NeDi Forum for changes...
*/
#error_reporting(E_ALL);

require_once ("inc/libmisc.php");
ReadConf('usr');
require_once ("inc/libdb-" . strtolower($backend) . ".php");
require_once ("inc/libldap.php");

$_POST = sanitize($_POST);
$_GET  = str_replace(";","", sanitize($_GET) );						# Avoid attacks with ;
$goto  = isset($_GET['goto']) ? $_GET['goto'] : "User-Profile.php";

$raderr = "";

if(isset( $_POST['user']) and !preg_match('/\W/',$_POST['user']) ){			# Avoid SQL injection
	$pass = md5( $_POST['pass'] );

	$link	= @DbConnect($dbhost,$dbuser,$dbpass,$dbname);
	if ( strstr($guiauth,'none') ){
		$uok	= 1;
		$query	= GenQuery('users','s','*','','',array('user'),array('='),array($_POST[user]) );
		$res    = @DbQuery($query,$link);
	}elseif ( strstr($guiauth,'pam') && $_POST['user'] != "admin" ){		# PAM code by Owen Brotherhood & Bruberg
		if (!extension_loaded ('pam_auth')){dl("pam_auth.so");}
		$uok	= pam_auth($_POST['user'],$_POST['pass']);
		$query	= GenQuery('users','s','*','','',array('user'),array('='),array($_POST[user]) );
		$res    = @DbQuery($query,$link);
	}elseif ( strstr($guiauth,'radius') && $_POST['user'] != "admin" ){		# Radius code by Till Elsner
		$radres = radius_auth_open();
		if (!$radres) {
			$raderr = "Error while preparing RADIUS authentication: ".radius_strerror($radres);
		}
		foreach ($radsrv as $rs){
			if (!radius_add_server($radres, $rs[0], $rs[1], $rs[2], $rs[3], $rs[4])){
				echo "<h4>RADIUS: ".radius_strerror($radres)."</h4>";
			}
		}
		if (!radius_create_request($radres, RADIUS_ACCESS_REQUEST)) {
			$raderr = "RADIUS create: ".radius_strerror($radres);
		}
		if (!( radius_put_string($radres, RADIUS_USER_NAME, $_POST['user']) && radius_put_string($radres, RADIUS_USER_PASSWORD, $_POST['pass']) )){
			$raderr = "RADIUS put: ".radius_strerror($radres);
		}
		$radauth = radius_send_request($radres);
		if (!$radauth){
			$raderr = "RADIUS send: ".radius_strerror($radres);
		}else{
			switch ($radauth){
				case RADIUS_ACCESS_ACCEPT:
					$query	= GenQuery('user','s','*','','',array('name'),array('='),array($_POST['user']) );
					$res    = @DbQuery($query,$link);
					$uok	= mysql_num_rows($res);
					break;
				case RADIUS_ACCESS_REJECT:
					$raderr = "Incorrect RADIUS login!";
					break;
				case RADIUS_ACCESS_CHALLENGE:
					$raderr = "No RADIUS challenge handling yet!";
					break;
				default:
					$raderr = "Unknown RADIUS error!";
			}
		}
	}elseif( strstr($guiauth,'ldap') && $_POST['user'] != "admin" ){		# Ldap code by Stephane Garret & vtur
		if (user_from_ldap_servers($_POST['user'],$_POST['pass'], false)){
		
			$query	= GenQuery('users','s','*','','',array('user'),array('='),array($_POST['user']) );
			$res    = @DbQuery($query,$link);
			$uok = 1;
			$ldaperr = "<h4>Authentication LDAP OK</h4>";
		}else {
			$uok = 0;
			$ldaperr = "<h4>Authentication LDAP Failed </h4>";
		}
	}else{
		$pass = md5( $_POST['pass'] );
		$query	= GenQuery('users','s','*','','',array('user','password'),array('=','='),array($_POST['user'],$pass),array('AND') );
		$res    = @DbQuery($query,$link);
		$uok    = @DbNumRows($res);
	}

	if ($uok == 1) {
		$usr = @DbFetchRow($res);
		session_start(); 
		$_SESSION['user']  = $_POST['user'];
		$_SESSION['group'] = "usr,";
		$_SESSION['view'] = $usr[15];
		if(strstr($guiauth,'ldap') && $_POST['user'] != "admin"){
			if (($ldapmap[0]) and in_array($ldapmap[0],$ldapusersgrp)){
				$_SESSION['group']   .= "adm,";
			}
			if (($ldapmap[1]) and in_array($ldapmap[1],$ldapusersgrp)){
				$_SESSION['group']   .= "net,";
			}
			if (($ldapmap[2]) and in_array($ldapmap[2],$ldapusersgrp)){
				$_SESSION['group']   .= "dsk,";
			}
			if (($ldapmap[3]) and in_array($ldapmap[3],$ldapusersgrp)){
				$_SESSION['group']   .= "mon,";
			}
			if (($ldapmap[4]) and in_array($ldapmap[4],$ldapusersgrp)){
				$_SESSION['group']   .= "mgr,";
			}
			if (($ldapmap[5]) and in_array($ldapmap[5],$ldapusersgrp)){
				$_SESSION['group']   .= "oth,";
			}
			if(@DbNumRows($res)>0){
			    $_SESSION['lang'] = $usr[8];
                	    $_SESSION['theme']= $usr[9];
                	    $_SESSION['vol']  = $usr[10];
                	    $_SESSION['col']  = $usr[11];
                	    $_SESSION['lim']  = $usr[12];
                	    $_SESSION['gsiz'] = $usr[13] & 7;
                	    $_SESSION['gbit'] = $usr[13] & 8;
                	    $_SESSION['gfar'] = $usr[13] & 16;
                	    $_SESSION['olic'] = $usr[13] & 32;
                	    $_SESSION['gmap'] = $usr[13] & 64;
                	    $_SESSION['date'] = ($usr[14])?$usr[14]:'j.M y G:i';
			}else{
			    $_SESSION['date'] = 'j.M y G:i';
                    	    $_SESSION['theme']= 'default';
                	    $_SESSION['lang'] = 'english';
                	    $_SESSION['vol']  = 10;
                	    $_SESSION['col']  = 5;
                	    $_SESSION['lim']  = 5;
			}
		}else{
			if ($usr[2] &  1) {$_SESSION['group']	.= "adm,";}
			if ($usr[2] &  2) {$_SESSION['group']	.= "net,";}
			if ($usr[2] &  4) {$_SESSION['group']	.= "dsk,";}
			if ($usr[2] &  8) {$_SESSION['group']	.= "mon,";}
			if ($usr[2] & 16) {$_SESSION['group']	.= "mgr,";}
			if ($usr[2] & 32) {$_SESSION['group']	.= "oth,";}


			$_SESSION['lang'] = $usr[8];
			$_SESSION['theme']= $usr[9];
			$_SESSION['vol']  = $usr[10];
			$_SESSION['col']  = $usr[11];
			$_SESSION['lim']  = $usr[12];
			$_SESSION['gsiz'] = $usr[13] & 7;
			$_SESSION['gbit'] = $usr[13] & 8;
			$_SESSION['gfar'] = $usr[13] & 16;
			$_SESSION['olic'] = $usr[13] & 32;
			$_SESSION['gmap'] = $usr[13] & 64;
			$_SESSION['date'] = ($usr[14])?$usr[14]:'j.M y G:i';
			$query	= GenQuery('users','u','user',$_POST['user'],'',array('lastlogin'),'',array(time()) );
			@DbQuery($query,$link);
		}
	}else{
	    print @DbError($link);
	}
	if(isset ($_SESSION['group'])){
		echo "<body background=\"themes/bgsteel.jpg\"><script>document.location.href='$goto';</script></body>\n";
	}elseif($raderr){
		$disc = "<h4>$raderr</h4>";
	} else {
		$disc = "<h4>Incorrect login!</h4>";
	}

}
?>
<html>
<head><title>NeDi Login</title>
<link href="themes/default.css" type="text/css" rel="stylesheet">
<link rel="shortcut icon" href="img/favicon.ico">
</head>
<body onLoad="document.login.user.focus();">
<div align="center">
<form name="login" method="post" action="index.php?goto=<?=rawurlencode($goto)?>">
<table class="login">
<tr class="loginbg"><th colspan="3"><a href='http://www.nedi.ch'><img src="img/nedib.png"></a></th></tr>
<tr class="txta">
<th align="center" colspan="3">
<img src="img/nedie<?=rand(1,7)?>.jpg">
<p><hr>
<?=$disc?>
</th></tr>
<tr class="loginbg">
<th>User <input type="text" name="user" size="12"></th>
<th>Pass <input type="password" name="pass" size="12"></th>
<th><input type="submit" value="Login">
</th>
</tr>
</table>
</form>
</div>

</body>
