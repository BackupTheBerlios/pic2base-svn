<?php 

function hasPermission($username, $permissionString)
{
	error_reporting($_SERVER["SERVER_NAME"] == "localhost" ? E_ALL : 0);
	include '../../share/global_config.php';
	error_reporting($_SERVER["SERVER_NAME"] == "localhost" ? E_ALL : 0);
	include '../share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	mysql_connect($db_server, $user, $PWD);
	mysql_select_db ($db);
	$result1 = mysql_query("SELECT $table1.id, $table1.username, $table1.aktiv, $table7.user_id, $table7.permission_id, 
	$table7.enabled, $table8.perm_id, $table8.shortdescription
	FROM $table1 inner join $table7 inner join $table8
	ON $table1.id = $table7.user_id 
	AND $table7.permission_id = $table8.perm_id
	AND $table1.username = '$username'
	AND $table1.aktiv = '1'
	AND $table8.shortdescription = '$permissionString' 
	AND $table7.enabled = '1'");
	echo mysql_error();
	IF(mysql_num_rows($result1) == 1)
	{
		return True;
	}
	ELSE
	{
		return False;
	}
}

function hasGroupPermission($group_id, $permissionString)
{
	//dirty trick:
	@include '../../share/global_config.php';
	@include '../share/global_config.php';
	@include '../bin/share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	mysql_connect($db_server, $user, $PWD);
	mysql_select_db ($db);
	$result2 = mysql_query("SELECT $table6.group_id, $table6.permission_id, $table6.enabled, 
	$table8.perm_id, $table8.shortdescription 
	FROM $table6 INNER JOIN $table8
	ON $table6.group_id = '$group_id'
	AND $table6.permission_id = $table8.perm_id
	AND $table6.enabled = '1'
	AND $table8.shortdescription = '$permissionString' ");
	echo mysql_error();
	IF(mysql_num_rows($result2) == 1)
	{
		return True;
	}
	ELSE
	{
		return False;
	}
}

?>