<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
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
$perm_id = $_GET['perm_id']; // fuer register_globals = off
if (hasPermission($uid, 'adminlogin', $sr))
{
	$result = mysql_query("SELECT * FROM $table7 WHERE user_id='".$user_id."' AND permission_id='".$perm_id."'");
	$enabled = mysql_result($result, 0, 'enabled');
	IF($enabled == '1')
	{
		$en = '0';
	}
	ELSE
	{
		$en = '1';
	}
	
	$result = mysql_query("UPDATE $table7 SET enabled='$en' WHERE user_id='".$user_id."' AND permission_id='".$perm_id."'");
	echo mysql_error();
	echo "<meta http-equiv='Refresh' content='0; URL=adminframe.php?item=adminshowuser&id=".$user_id."&del=0'>";
}
ELSE
{
	echo "Sie haben nicht gen&uuml;gend Berechtigungen!";
}
?>