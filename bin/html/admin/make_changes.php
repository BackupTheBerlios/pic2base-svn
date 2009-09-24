<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Startseite</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?

/*
 * Project: pic2base
 * File: start.php
 *
 * Copyright (c) 2003 - 2005 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 * @copyright 2003-2005 Klaus Henneberg
 * @author Klaus Henneberg
 * @package pic2base
 * @license http://www.opensource.org/licenses/osl-2.1.php Open Software License
 */

unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = split(',',$_COOKIE['login']);
//echo $c_username;
}
 
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';

$result1 = mysql($db, "SELECT * FROM $table1 WHERE username = '$c_username' AND aktiv = '1'");
$user_id = mysql_result($result1, $i1, 'id');
$result2 = mysql($db, "SELECT * FROM $table7 WHERE user_id = '$user_id' AND enabled = '1' AND permission_id = '1'");
$num2 = mysql_num_rows($result2);
IF($num2 == '1')
{
	SWITCH($mod)
	{
		CASE 'user':
		//echo $gruppe."<BR>";
		//Dem benutzer wird die neue Gruppe zugewiesen:
		$result3 = mysql($db, "UPDATE $table1 SET group_id = '$gruppe' WHERE id='$id'");
		//die alten Benutzer-Rechte werden gelöscht:
		$result4 = mysql($db, "DELETE FROM $table7 WHERE user_id = '$id'");
		//Die neuen Benutzer-Rechte werden entsprechend der neuen Gruppe zugewiesen:
		$result5 = mysql($db, "SELECT * FROM $table6 WHERE group_id = '$gruppe' AND enabled = '1'");
		$num5 = mysql_num_rows($result5);
		//echo $num5."<BR>";
		FOR($i5=0; $i5<$num5; $i5++)
		{
			$perm_id = mysql_result($result5, $i5, 'permission_id');
			$result6 = mysql($db, "INSERT INTO $table7 (user_id, permission_id, enabled) VALUES ('$id', '$perm_id', '1')");
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
			<p style='margin:120px 0px; text-align:center'>Die gewünschte Aktion wird ausgef&uuml;hrt.</p>
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
	echo "<meta http-equiv='Refresh', Content='0; URL=../../../index.php'>";
	return;
}
mysql_close($conn);
?>
<p class="klein">- KH 09/2007 -</P>
</DIV>
</CENTER>
</BODY>
</HTML>