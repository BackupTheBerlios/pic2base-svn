<?php
IF (!$_COOKIE['login'])
{
	include '../share/global_config.php';
	header('Location: ../../index.php');
}

unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
}

$sr = $_GET['sr'];
include '../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/permissions.php';

//var_dump($_GET);
if ( array_key_exists('group_id',$_GET) )
{
	$group_id = $_GET['group_id'];
}
if ( array_key_exists('perm_id',$_GET) )
{
	$perm_id = $_GET['perm_id'];
}
if ( array_key_exists('checked',$_GET) )
{
	$checked = $_GET['checked'];
}

IF($checked == '')
{
	$new_status = '1';
	$checked = 'checked';
	$text = 'Berechtigung erteilt';
}
ELSEIF($checked == 'checked')
{
	$new_status = '0';
	$checked = '';
	$text = 'keine Berechtigung';
}

IF ($group_id !== '' AND $perm_id !== '')
{
	if (hasPermission($c_username, 'adminlogin', $sr))
	{
		$result0 = mysql_query("SELECT * FROM $table6 WHERE group_id='".$group_id."' AND permission_id='".$perm_id."'");
		$enabled = mysql_result($result0, 0, 'enabled');
		
		IF($enabled == '1')
		{
			$en = '0';
		}
		ELSE
		{
			$en = '1';
		}
		
		$result1 = mysql_query("UPDATE $table6 SET enabled='$en' WHERE group_id='".$group_id."' AND permission_id='".$perm_id."'");
		//Es werden die Benutzer der Gruppe ermittelt:
		$result2 = mysql_query( "SELECT * FROM $table1 WHERE group_id = '$group_id'");
		$num2 = mysql_num_rows($result2);
		//echo "Anzahl User in der Gruppe: ".$num2."<BR>";
		//Fuer alle User dieser Gruppe wird das gewaehlte Recht neu gesetzt
		FOR($i2=0; $i2<$num2; $i2++)
		{
			$user_id = mysql_result($result2, $i2, 'id');
			//echo "User-ID: ".$user_id."<BR>";
			//echo "Recht-ID: ".$perm_id."<BR>";
			$result3 = mysql_query( "UPDATE $table7 SET enabled='$en' WHERE user_id = '$user_id' AND permission_id='$perm_id'");
		}
		
		echo "<INPUT TYPE=CHECKBOX $checked value='$new_status' title = '$text' onClick='changeGrouppermission(\"$group_id\",\"$perm_id\",\"$checked\",\"$sr\")'>";
	}
	ELSE
	{
		echo "Sie haben nicht gen&uuml;gend Berechtigungen!";
	}
}
ELSE
{
	echo "Es liegt ein Fehler vor!";
}
mysql_close($conn);
?>