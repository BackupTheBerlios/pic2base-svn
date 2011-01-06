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
	//var_dump($_POST);
	if(array_key_exists('description',$_POST))
	{
	    $description = $_POST['description'];
	}
	if(array_key_exists('shortdescription',$_POST))
	{
	    $shortdescription = $_POST['shortdescription'];
	}
	if(array_key_exists('permission_id',$_POST))
	{
	    $permission_id = $_POST['permission_id'];
	}
	
	include '../../share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	include $sr.'/bin/share/functions/permissions.php';

	if ((hasPermission($c_username, 'addpermission')) AND ($description !== '') AND ($shortdescription !== '') AND ($permission_id !== ''))
	{
		mysql_connect ($db_server, $user, $PWD);
		$res1 = mysql_query("SELECT * FROM $table8 WHERE perm_id = '$permission_id'");
		$num1 = mysql_result($res1, isset($i1), 'perm_id');
		IF($num1 == '0')
		{
			$result = mysql_query("INSERT INTO $table8 (description, shortdescription, perm_id) VALUES ('".$description."', '".$shortdescription."', '".$permission_id."')");
			$result1 = mysql_query( "SELECT * FROM $table8 WHERE description = '$description' AND shortdescription = '$shortdescription' AND perm_id = '$permission_id'");
			$perm_id = mysql_result($result1, 0, 'perm_id');
			//in die Tabelle grouppermissions wird fuer jede existierende Gruppe die neue Berechtigung mit der Eigenschaft "nicht aktiviert" eingetragen:
			$result2 =mysql_query( "SELECT * FROM $table9");
			$num2 = mysql_num_rows($result2);
			FOR($i2='0'; $i2<$num2; $i2++)
			{
				$group_id = mysql_result($result2, $i2, 'id');
				$result3 = mysql_query( "INSERT INTO $table6 (group_id, permission_id, enabled) VALUES ('$group_id', '$perm_id', '0')");
				
			}
			//in die Tabelle userpermissions wird fuer alle User die neue Berechtigung mit der Eigenschaft "nicht aktiviert" eingetragen:
			$result4 =mysql_query( "SELECT * FROM $table1");
			$num4 = mysql_num_rows($result4);
			FOR($i4='0'; $i4<$num4; $i4++)
			{
				$user_id = mysql_result($result4, $i4, 'id');
				$result5 = mysql_query( "INSERT INTO $table7 (user_id, permission_id, enabled) VALUES ('$user_id', '$perm_id', '0')");
				
			}
			echo "<meta http-equiv='Refresh' content='0; URL=adminframe.php?item=adminshowpermissions'>";
		}
		ELSE
		{
			echo "DIe Berechtigungs-ID ist bereits vorhanden und darf nicht noch einmel verwendet werden!
			<meta http-equiv='Refresh' content='2; URL='javascript:history.back()'>";
		}
	}
	ELSE
	{
		echo "<center><BR><BR>Fehler!<BR><BR>Sie haben nicht gen&uuml;gend Berechtigungen oder das Formular wurde nicht vollst&auml;ndig ausgef&uuml;llt!<BR><BR>
		<input type='button' value='Zur&uuml;ck' onClick='javascript:history.back()'</center>";
	}
?>