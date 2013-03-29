<?php
IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
	header('Location: ../../index.php');
}
else
{
	$uid = $_COOKIE['uid'];
}

include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/permissions.php';

//#######################################################################################################
// Skript kann verwendet werden, um alle verfuegbaren Rechte in die Gruppen- und Userabellen zu schreiben
// # # # # # # # # # hidden Feature # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
//
// 1 auslesen aller Rechte aus der Tabelle permissions (table8)
// 2 Ermittlung, welche Gruppen es gibt Tabelle usergroups (table9)
// 3 Pruefung, ob fuer alle Gruppen alle Rechte in der Tabelle grouppermissions vorhanden sind (table6);
// ggf. nachtragen -> dann aber mit enabled = 0
// 4 Ermittlung welche User es in Tabelle users gibt (table1)
// 5 Pruefung, ob fuer alle User alle Rechte in der Tabelle userpermissions vorhanden sind (table7);
// ggf. nachtragen -> dann aber mit enabled = 0
//
//########################################################################################################

// 1
$result1 = mysql_query("SELECT * FROM $table8");
$num1 = mysql_num_rows($result1);
FOR($i1='0'; $i1<$num1; $i1++)
{
	$perm_id = mysql_result($result1, $i1, 'perm_id');
//2
	$result2 = mysql_query("SELECT * FROM $table9");
	$num2 = mysql_num_rows($result2);
	FOR($i2='0'; $i2<$num2; $i2++)
	{
		$group_id = mysql_result($result2, $i2, 'id');
//3
		$result3 = mysql_query("SELECT * FROM $table6 WHERE group_id = '$group_id' AND permission_id = '$perm_id'");
		IF(mysql_num_rows($result3) == 0)
		{
			echo "Fuer Gruppe ".$group_id." / Berechtigung ".$perm_id." ist kein Eintrag vorhanden<BR>";
			//Nachtrag der Berechtigung:
			$result31 = mysql_query("INSERT INTO $table6 (group_id, permission_id, enabled) VALUES ('$group_id', '$perm_id', '0')");
			echo mysql_error();
		}
	}
	
//4
	$result4 = mysql_query("SELECT * FROM $table1");
	$num4 = mysql_num_rows($result4);
	FOR($i4='0'; $i4<$num4; $i4++)
	{
		$user_id = mysql_result($result4, $i4, 'id');
//5
		$result5 = mysql_query("SELECT * FROM $table7 WHERE user_id = '$user_id' AND permission_id = '$perm_id'");
		IF(mysql_num_rows($result5) == 0)
		{
			echo "Fuer User ".$user_id." / Berechtigung ".$perm_id." ist kein Eintrag vorhanden<BR>";
			//Nachtrag der Berechtigung:
			$result51 = mysql_query("INSERT INTO $table7 (user_id, permission_id, enabled) VALUES ('$user_id', '$perm_id', '0')");
			echo mysql_error();
		}
	}
}

echo "<meta http-equiv='Refresh', Content='1; ../html/start.php'>";

?>