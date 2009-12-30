<?
	// fuer register_globals = off
	if(array_key_exists('item',$_GET))
	{
		if(array_key_exists('item',$_GET))
		{
			$item = $_GET['item']; 
		}
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
	}
?>