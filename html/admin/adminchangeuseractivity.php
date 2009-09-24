<?
	unset($username);
	IF ($_COOKIE['login'])
	{
	list($c_username) = split(',',$_COOKIE['login']);
	//echo $c_username;
	}
	include '../../share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	include $sr.'/bin/share/functions/permissions.php';
	if (hasPermission($c_username, 'adminlogin'))
	{
		mysql_connect ($db_server, $user, $PWD);
		$result = mysql ($db,"SELECT * FROM users WHERE id='$user_id'");
		IF (mysql_result($result, 0, 'aktiv') == 0)
		{
		 	$result = mysql ($db,"UPDATE users SET aktiv = '1' WHERE id = $user_id"); 
		}
		ELSE
		{
		  $result = mysql ($db,"UPDATE users SET aktiv = '0' WHERE id = $user_id");
		}
		//echo mysql_error();
		echo "<meta http-equiv='Refresh' content='0; URL=adminframe.php?item=adminshowusers'>";
	}
	ELSE
	{
		echo "Sie haben nicht genügend Berechtigungen!";
	}
?>