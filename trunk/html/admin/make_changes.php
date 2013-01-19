<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../../index.php');
}
else
{
	$uid = $_COOKIE['uid'];
}
//##################################################################################################
//
//Skript wird von adminshowusers.php aufgerufen, um die Gruppenzugehoerigkeit eines Users zu aendern
//
//##################################################################################################
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Gruppe wechseln</TITLE>
	<META NAME="GENERATOR" CONTENT="eclipse">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format2.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script language="JavaScript" src="../../share/functions/resize_elements.js"></script>
	<script language="JavaScript" src="../../share/functions/jquery-1.8.2.min.js"></script>
	<script language="JavaScript">
	  	jQuery.noConflict()
		jQuery(document).ready(checkWindowSize);
		jQuery(window).resize(checkWindowSize); 
	</script>
</HEAD>

<BODY>

<CENTER>

<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: make_changes.php
 *
 * Copyright (c) 2003 - 2012 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 */

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/permissions.php';

$mod = $_GET['mod'];
$id = $_GET['id'];
$gruppe = $_POST['gruppe'];

IF(hasPermission($uid, 'adminlogin', $sr))
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
		<div class='page' id='page'>

			<div class='head' id='head'>
				pic2base :: Admin-Bereich
			</div>
			
			<div class='navi' id='navi'>
				<div class='menucontainer'>
				</div>
			</div>
			
			<div class='content' id='content'>
			<p style='margin:120px 0px; text-align:center;'>Die gew&uuml;nschte Aktion wird ausgef&uuml;hrt.</p>
			</div>
			<div class='foot' id='foot'>
				<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
			</div>
		</div>
		<meta http-equiv='Refresh', Content='1; URL=adminframe.php?item=adminshowusers'>";
		break;
	}
}
ELSE
{
	echo "
		<div class='page' id='page'>

			<div class='head' id='head'>
				pic2base :: Admin-Bereich
			</div>
			
			<div class='navi' id='navi'>
				<div class='menucontainer'>
				</div>
			</div>
			
			<div class='content' id='content'>
			<p style='margin:120px 0px; text-align:center; color:red;'>Sie haben keine ausreichende Rechte!<br><br>Die Aktion wird abgebrochen...</p>
			</div>
			<div class='foot' id='foot'>
				<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
			</div>
		</div>
		<meta http-equiv='Refresh', Content='4; URL=../../../index.php'>";
	return;
}
mysql_close($conn);
?>
</DIV>
</CENTER>
</BODY>
</HTML>