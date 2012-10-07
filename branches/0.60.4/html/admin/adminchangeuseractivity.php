<?php
IF (!$_COOKIE['login'])
{
include '../../share/global_config.php';
//var_dump($sr);
  header('Location: ../../../index.php');
}

	unset($username);
	IF ($_COOKIE['login'])
	{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
	//echo $c_username;
	}
	include '../../share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	include $sr.'/bin/share/functions/permissions.php';
	
	$user_id = $_GET['user_id']; // fuer register_globals = off
	if (hasPermission($c_username, 'adminlogin', $sr))
	{
		mysql_connect ($db_server, $user, $PWD);
		$result = mysql_query("SELECT * FROM users WHERE id='$user_id'");
		IF (mysql_result($result, 0, 'aktiv') == 0)
		{
		 	$result = mysql_query("UPDATE users SET aktiv = '1' WHERE id = $user_id"); 
		}
		ELSE
		{
		  $result = mysql_query("UPDATE users SET aktiv = '0' WHERE id = $user_id");
		}
		//echo mysql_error();
		echo "<meta http-equiv='Refresh' content='0; URL=adminframe.php?item=adminshowusers'>";
	}
	ELSE
	{
		echo "Sie haben nicht gen&uuml;gend Berechtigungen!";
	}
?>