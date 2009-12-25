<?
	unset($username);
	IF ($_COOKIE['login'])
	{
	list($c_username) = split(',',$_COOKIE['login']);
	//echo $c_username;
	}
	//var_dump($_POST);
	// für register_globals = off
	if(array_key_exists('description',$_POST))
	{
	    $description = $_POST['description'];
	}
	if(array_key_exists('shortdescription',$_POST))
	{
	    $shortdescription = $_POST['shortdescription'];
	}
	
	include '../../share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	include $sr.'/bin/share/functions/permissions.php';

	if ((hasPermission($c_username, 'addpermission')) AND ($description !== '') AND ($shortdescription !== ''))
	{
		mysql_connect ($db_server, $user, $PWD);
		$result = mysql_query("INSERT INTO $table8 (description, shortdescription) VALUES ('".$description."', '".$shortdescription."')");
		$result1 = mysql_query( "SELECT * FROM $table8 WHERE description = '$description' AND shortdescription = '$shortdescription'");
		$perm_id = mysql_result($result1, 0, 'id');
		//in die Tabelle grouppermissions wird für jede existierende Gruppe die neue Berechtigung mit der Eigenschaft "nicht aktiviert" eingetragen:
		$result2 =mysql_query( "SELECT * FROM $table9");
		$num2 = mysql_num_rows($result2);
		FOR($i2='0'; $i2<$num2; $i2++)
		{
			$group_id = mysql_result($result2, $i2, 'id');
			$result3 = mysql_query( "INSERT INTO $table6 (group_id, permission_id, enabled) VALUES ('$group_id', '$perm_id', '0')");
			
		}
		//in die Tabelle userpermissions wird für alle User die neue Berechtigung mit der Eigenschaft "nicht aktiviert" eingetragen:
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
		echo "Sie haben nicht genügend Berechtigungen oder das Formular wurde nicht vollst&auml;ndig ausgef&uuml;llt!";
	}
?>