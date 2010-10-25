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
 * Copyright (c) 2003 - 2010 Klaus Henneberg
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
/*
$result1 = mysql_query( "SELECT * FROM $table1 WHERE username = '$c_username' AND aktiv = '1'");
$user_id = mysql_result($result1, isset($i1), 'id');
$result2 = mysql_query( "SELECT * FROM $table7 WHERE user_id = '$user_id' AND enabled = '1' AND permission_id = '999'");
$num2 = mysql_num_rows($result2);

IF($num2 == '1')
*/
IF(hasPermission($c_username, 'adminlogin'))
{
	SWITCH($mod)
	{
		CASE 'user':
		//echo $gruppe."<BR>";
		//Dem benutzer wird die neue Gruppe zugewiesen:
		$result3 = mysql_query( "UPDATE $table1 SET group_id = '$gruppe' WHERE id='$id'");
		//die alten Benutzer-Rechte werden geloescht:
		$result4 = mysql_query( "DELETE FROM $table7 WHERE user_id = '$id'");
		//Die neuen Benutzer-Rechte werden entsprechend der neuen Gruppe zugewiesen:
		$result5 = mysql_query( "SELECT * FROM $table6 WHERE group_id = '$gruppe' AND enabled = '1'");
		$num5 = mysql_num_rows($result5);
		FOR($i5=0; $i5<$num5; $i5++)
		{
			$perm_id = mysql_result($result5, $i5, 'permission_id');
			$result6 = mysql_query( "INSERT INTO $table7 (user_id, permission_id, enabled) VALUES ('$id', '$perm_id', '1')");
		}
		echo mysql_error();
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
	echo "Falsche Rechte!<meta http-equiv='Refresh', Content='5; URL=../../../index.php'>";
	return;
}
mysql_close($conn);
?>
</DIV>
</CENTER>
</BODY>
</HTML>