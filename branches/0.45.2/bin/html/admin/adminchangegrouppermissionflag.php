<?php
	unset($username);
	IF ($_COOKIE['login'])
	{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
	//echo $c_username;
	}
	include '../../share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	include $sr.'/bin/share/functions/permissions.php';
	
	$group_id = $_GET['group_id']; // fuer register_globals = off
	$permission_id = $_GET['permission_id']; // fuer register_globals = off
	if (hasPermission($c_username, 'adminlogin'))
	{
		mysql_connect ($db_server, $user, $PWD);
		$result = mysql_query("SELECT * FROM $table6 WHERE group_id='".$group_id."' AND permission_id='".$permission_id."'");
		$enabled = mysql_result($result, 0, 'enabled');
		IF($enabled == '1')
		{
			$en = '0';
		}
		ELSE
		{
			$en = '1';
		}
		
		$result = mysql_query("UPDATE $table6 SET enabled='$en' WHERE group_id='".$group_id."' AND permission_id='".$permission_id."'");
		//Es werden die Benutzer der Gruppe ermittelt:
		$result2 = mysql_query( "SELECT * FROM $table1 WHERE group_id = '$group_id'");
		$num2 = mysql_num_rows($result2);
		//echo "Anzahl User in der Gruppe: ".$num2."<BR>";
		//Fuer alle User dieser Gruppe wird das gewaehlte Recht neu gesetzt
		FOR($i2=0; $i2<$num2; $i2++)
		{
			$user_id = mysql_result($result2, $i2, 'id');
			//echo "User-ID: ".$user_id."<BR>";
			//echo "Recht-ID: ".$permission_id."<BR>";
			$result3 = mysql_query( "UPDATE $table7 SET enabled='$en' WHERE user_id = '$user_id' AND permission_id='$permission_id'");
			//echo mysql_error();
		}
		//echo mysql_error();
		echo "<meta http-equiv='Refresh' content='0; URL=adminframe.php?item=adminshowgroup&id=".$group_id."'>";
	}
	ELSE
	{
		echo "Sie haben nicht gen&uuml;gend Berechtigungen!";
	}
?>