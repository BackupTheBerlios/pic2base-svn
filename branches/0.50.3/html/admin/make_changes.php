<?php
IF (!$_COOKIE['login'])
{
include '../../share/global_config.php';
//var_dump($sr);
  header('Location: ../../../index.php');
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Gruppe wechseln</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: make_changes.php
 *
 * Copyright (c) 2003 - 2011 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 */

unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = preg_split('#,#',$_COOKIE['login']);
//$c_username;
}
 
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/permissions.php';

$mod = $_GET['mod'];
$id = $_GET['id'];
$gruppe = $_POST['gruppe'];

IF(hasPermission($c_username, 'adminlogin'))
{
	SWITCH($mod)
	{
		CASE 'user':
		//echo $gruppe."<BR>";
		//Dem Benutzer wird die neue Gruppe zugewiesen:
		$result3 = mysql_query( "UPDATE $table1 SET group_id = '$gruppe' WHERE id='$id'");
		//die alten Benutzer-Rechte werden geloescht:
		$result4 = mysql_query( "DELETE FROM $table7 WHERE user_id = '$id'");
		//Die neuen Benutzer-Rechte werden entsprechend der neuen Gruppe zugewiesen:
		$result5 = mysql_query( "SELECT * FROM $table6 WHERE group_id = '$gruppe'");
		$num5 = mysql_num_rows($result5);
		FOR($i5=0; $i5<$num5; $i5++)
		{
			$perm_id = mysql_result($result5, $i5, 'permission_id');
			$enabled = mysql_result($result5, $i5, 'enabled');
			$result6 = mysql_query( "INSERT INTO $table7 (user_id, permission_id, enabled) VALUES ('$id', '$perm_id', '$enabled')");
		}
		echo mysql_error();
		//wenn der User erfolgreich geaendert wurde wird kontrolliert, ob mind. noch ein User mit Admin-Rechten existiert.
		//Wenn ja, wird der User pb inaktiv gesetzt (Sicherheit!), wenn nein, wird pb aktiviert
		$result6 = mysql_query("SELECT $table9.id, $table9.description, $table1.group_id 
		FROM $table9, $table1  
		WHERE $table9.description = 'Admin'
		AND $table9.id = $table1.group_id");
		$num6 = mysql_num_rows($result6);
		IF($num6 > 0)
		{
			$result7 = mysql_query("UPDATE $table1 SET aktiv = '0' WHERE username = 'pb'");
		}
		ELSEIF($num6 == 0)
		{
			$result7 = mysql_query("UPDATE $table1 SET aktiv = '1' WHERE username = 'pb'");
		}
		echo "
		<div class='page'>

			<p id='kopf'>pic2base :: Admin-Bereich</p>
			
			<div class='navi' style='clear:right;'>
				<div class='menucontainer'>
				</div>
			</div>
			
			<div class='content'>
			<p style='margin:120px 0px; text-align:center'>Die gew&uuml;nschte Aktion wird ausgef&uuml;hrt.</p>
			</div>
			<br style='clear:both;' />
			<p id='fuss'>$cr</p>
		</div>
		<meta http-equiv='Refresh', Content='1; URL=adminframe.php?item=adminshowusers'>";
		break;
	}
}
ELSE
{
	echo "Sie haben falsche Rechte!<meta http-equiv='Refresh', Content='2; URL=../../../index.php'>";
	return;
}
mysql_close($conn);
?>
</DIV>
</CENTER>
</BODY>
</HTML>