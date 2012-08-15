<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - DB-Export</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll = "auto" onLoad = 'getMissingFiles()'>

<CENTER>

<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: db_export1.php
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

unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
	$benutzername = $c_username;
}
INCLUDE '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
//include $sr.'/bin/share/functions/permissions.php';
//include $sr.'/bin/share/functions/main_functions.php';
//include $sr.'/bin/share/functions/ajax_functions.php';
/*
IF(hasPermission($c_username, 'editkattree'))
{
	$navigation = "
			<a class='navi' href='kat_sort1.php'>Sortierung</a>
			<a class='navi_blind' href='kat_repair1.php'>Wartung</a>
			<a class='navi' href='../../html/admin/adminframe.php'>Zur&uuml;ck</a>
			<a class='navi_blind'></a>
			<a class='navi_blind'></a>
			<a class='navi_blind'></a>
			<a class='navi_blind'></a>
			<a class='navi' href='../../html/start.php'>zur Startseite</a>
			<a class='navi' href='../../html/help/help1.php?page=5'>Hilfe</a>
			<a class='navi' href='$inst_path/pic2base/index.php'>Logout</a>";
}
ELSE
{
	header('Location: ../../../index.php');
}
*/
echo "
<div class='page'>

	<p id='kopf'>pic2base :: Admin-Bereich - Datenbank-Export</p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
//		echo $navigation;
		 include '../../html/admin/adminnavigation.php';
		echo "</div>
	</div>
	
	<div id='spalte1'>
	<!--DB-Export<BR><BR><BR>Diese Funktion steht noch nicht zur Verf&uuml;gung.-->
	
	<font color='green'>
	<p  style='margin-top:20px;'><b>pic2base - Export</b></p>
	<fieldset style='width:390px; background-color:yellow; margin-top:10px;'>
	<legend style='color:blue; font-weight:bold;'>SQL-Export</legend>
		F&uuml;r den Datenbank-Export der pic2base-Datenbank<BR>tragen Sie hier bitte den Benutzernamen und das Passwort<BR>eines <b>vorhandenen</b> MySQL-Benutzers<BR>mit <b>Administrator-Rechten</b> ein:
		<FORM name = 'db_export' method='post' action='db_export_action.php' label='X'>
		<center>
		<TABLE border = '0' style='margin-top:20px;'>
		<TR>
		<TD>User-Name (Admin):</TD><TD><INPUT type='text' name='db_user'></TD>
		</TR>
		<TR>
		<TD>Passwort:</TD><TD><input type='password' name='PWD'></TD>
		</TR>
		<TR>
		<TD colspan='2' align='center'><INPUT type='submit' value='DB exportieren'></TD>
		</TR>
		</TABLE>
		</center>
		<input type='hidden' name='method' value='sql'>
		</FORM>
		</fieldset>
	</font>
	</div>	
		
	<DIV id='spalte2'>
		<p id='elf' style='background-color:white; padding: 5px; width: 365px; margin-top: 54px; margin-left: 20px;'>Hinweis:<BR><BR>Hier haben Sie die M&ouml;glichkeit, den gesamten Datenbank-Inhalt als SQL-Statement, CSV- oder XML-Datei zu exportieren.</p>
	</DIV>
	
	<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>

</div>
</CENTER>
</BODY>";
?>