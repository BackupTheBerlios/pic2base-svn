<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
  	header('Location: ../../../index.php');
}
else
{
	$uid = $_COOKIE['uid'];
}
	include '../../share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	include $sr.'/bin/share/functions/permissions.php';
	
	$user_id = $_GET['user_id']; // fuer register_globals = off
	if (hasPermission($uid, 'adminlogin', $sr))
	{
		$result = mysql_query("SELECT * FROM $table1 WHERE id='$user_id'");
		IF (mysql_result($result, 0, 'aktiv') == 0)
		{
		 	$result = mysql_query("UPDATE $table1 SET aktiv = '1' WHERE id = $user_id"); 
		}
		ELSE
		{
		  $result = mysql_query("UPDATE $table1 SET aktiv = '0' WHERE id = $user_id");
		}
		//echo mysql_error();
		echo "<meta http-equiv='Refresh' content='0; URL=adminframe.php?item=adminshowusers'>";
	}
	ELSE
	{
		echo "Sie haben nicht gen&uuml;gend Berechtigungen!";
	}
?>