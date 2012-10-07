<?php
include '../../share/global_config.php';

//Zugriffskontrolle ######################################################
IF (!$_COOKIE['login'])
{
  header('Location: ../../../index.php');
}
ELSE
{
	unset($username);
	IF ($_COOKIE['login'])
	{
		list($c_username) = preg_split('#,#',$_COOKIE['login']);
		IF(!hasPermission($c_username, 'adminlogin', $sr) AND (!hasPermission($c_username, 'editkattree', $sr)) AND (!hasPermission($c_username, 'editlocationname', $sr)))
		{
			header('Location: ../../../index.php');
		}
	}
}
//########################################################################

if(array_key_exists('item',$_GET))
{
	$item = $_GET['item']; 
}
else
{
	$item = '';
}
switch ($item)
{
	case "": include "adminhome.php"; break;
	case "adminshowusers": include "adminshowusers.php"; break;
	case "adminshowgroups": include "adminshowgroups.php"; break;
	case "adminadduser": include "adminadduser.php"; break;
	case "adminaddusergroup": include "adminaddusergroup.php"; break;
	case "adminaddcategory": include "adminaddcategory.php"; break;
	case "adminshowuser": include "adminshowuser.php"; break;
	case "adminshowgroup": include "adminshowgroup.php"; break;
	case "adminshowpermissions": include "adminshowpermissions.php"; break;
	case "adminaddpermission": include "adminaddpermission.php"; break;
	case "deleteuser": include "deleteuser.php"; break;
	CASE "del_group": include "del_group.php"; break;
	CASE "del_group_exe": include "del_group_exe.php"; break;
	CASE "editlocationname": include "show_locationname.php"; break;
	CASE "admineditlocation": include "edit_locationname.php"; break;
	CASE "admineditlocationnameaction": include "edit_locationname_action.php"; break;
}
?>