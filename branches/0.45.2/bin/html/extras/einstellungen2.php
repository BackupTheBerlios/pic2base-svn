<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
<TITLE>pic2base - Pers&ouml;nliche Einstellungen</TITLE>
<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel=stylesheet type="text/css" href='../../css/format1.css'>
<link rel="shortcut icon" href="../../share/images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll="auto">

<CENTER>

<DIV Class="klein"><?php

/*
 * Project: pic2base
 * File: einstellungen1.php
 *
 * Copyright (c) 2006 - 2008 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 * @copyright 2003-2008 Klaus Henneberg
 * @author Klaus Henneberg
 * @package pic2base
 * @license http://www.opensource.org/licenses/osl-2.1.php Open Software License
 */

unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('/,/',$_COOKIE['login']);
	//echo $c_username;
}

$id = $_REQUEST['id'];

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/ajax_functions.php';
include $sr.'/bin/share/functions/main_functions.php';
include_once $sr.'/bin/share/functions/permissions.php';

IF(hasPermission($c_username, 'editallprofiles'))
{
	$result1 = mysql_query("SELECT * FROM $table1 WHERE id = '$id'");
	echo mysql_error();
	$titel = mysql_result($result1, isset($i1), 'titel');
	$vorname = mysql_result($result1, isset($i1), 'vorname');
	$name = mysql_result($result1, isset($i1), 'name');
	$u_name = mysql_result($result1, isset($i1), 'username');
	$strasse = mysql_result($result1, isset($i1), 'strasse');
	$plz = mysql_result($result1, isset($i1), 'plz');
	$ort = mysql_result($result1, isset($i1), 'ort');
	$tel = mysql_result($result1, isset($i1), 'tel');
	$email = mysql_result($result1, isset($i1), 'email');
	$internet = mysql_result($result1, isset($i1), 'internet');
	$direkt_download = mysql_result($result1, isset($i1), 'direkt_download');

	echo "
	<div class='page'>
	
		<p id='kopf'>pic2base :: Benutzerdaten anpassen <span class='klein'>(User: $c_username)</span></p>
			
		<div class='navi' style='clear:right;'>
			<div class='menucontainer'>";
	createNavi5($c_username);
	echo "</div>
		</div>
		
		<div id='spalte1'>
		<p id='elf' style='background-color:white; padding: 5px; margin-top: 4px; margin-left: 0px; text-align:center;'>Pers. Einstellungen f&uuml;r ".$vorname." ".$name." (".$u_name."):<BR>
		<FORM name = 'pwd' method = post action = 'save_pwd1.php?mod=all'>
		<TABLE align=center style='width:90%;border-width:1px;border-color:#DDDDFF;border-style:none;padding:0px;margin-top:6px;margin-bottom:0px;
	    	text-align:center;'>
		<TR id='kat' style='height:3px;'>
			<TD class='normal' style='background-color:#ff9900;' colspan = '3'></TD>
		</TR>
		
		<TR id='kat'>
			<TD id='kat1'>Titel:</TD>
			<TD id='kat1' colspan='2'><input type='text' name='titel' value = '$titel' style='width:200px;'></TD>
		</TR>
		
		<TR id='kat'>
			<TD id='kat1'>Name:</TD>
			<TD id='kat1' colspan='2'><input type='text' name='name' value = '$name' style='width:200px;'></TD>
		</TR>
		
		<TR id='kat'>
			<TD id='kat1'>Vorname:</TD>
			<TD id='kat1' colspan='2'><input type='text' name='vorname' value = '$vorname' style='width:200px;'></TD>
		</TR>
		
		<TR id='kat'>
			<TD id='kat1'>Strasse:</TD>
			<TD id='kat1' colspan='2'><input type='text' name='strasse' value = '$strasse' style='width:200px;'></TD>
		</TR>
		
		<TR id='kat'>
			<TD id='kat1'>PLZ:</TD>
			<TD id='kat1' colspan='2'><input type='text' name='plz' value = '$plz' style='width:200px;'></TD>
		</TR>
		
		<TR id='kat'>
			<TD id='kat1'>Ort:</TD>
			<TD id='kat1' colspan='2'><input type='text' name='ort' value = '$ort' style='width:200px;'></TD>
		</TR>
		
		<TR id='kat'>
			<TD id='kat1'>Telefon:</TD>
			<TD id='kat1' colspan='2'><input type='text' name='tel' value = '$tel' style='width:200px;'></TD>
		</TR>
		
		<TR id='kat'>
			<TD id='kat1'>eMail:</TD>
			<TD id='kat1' colspan='2'><input type='text' name='email' value = '$email' style='width:200px;'></TD>
		</TR>
		
		<TR id='kat'>
			<TD id='kat1'>Internet:</TD>
			<TD id='kat1' colspan='2'><input type='text' name='internet' value = '$internet' style='width:200px;'></TD>
		</TR>
		
		<TR id='kat'>
			<TD id='kat1'>Bild-Download:</TD>
			<TD id='kat1' colspan='2'>
			<!--<input type='text' name='direkt_download' value = '$direkt_download' style='width:200px;'>-->
			<SELECT name='direkt_download' style='width:200px;'>";
	IF($direkt_download == '0')
	{
		echo "
				<option value='0' selected>per FTP</option>
				<option value='1'>Direkt-Download</option>";
	}
	ELSEIF($direkt_download == '1')
	{
		echo "
				<option value='0'>per FTP</option>
				<option value='1' selected>Direkt-Download</option>";
	}

	echo "
			</TD>
		</TR>
		
		<TR id='normal'>
			<TD id='kat1' colspan='3'>Passwort &auml;ndern</TD>
		</TR>
		
		<!--<TR id='kat'>
			<TD id='kat1'>Altes Passwort:</TD>
			<TD id='kat1' colspan='2'><input type='password' name='old_pwd' style='width:200px;'></TD>
		</TR>-->
		
		<TR id='kat'>
			<TD id='kat1'>Neues Passwort:</TD>
			<TD id='kat1' colspan='2'><input type='password' name='new_pwd_1' style='width:200px;' value='$u_name'></TD>
		</TR>
		
		<TR id='kat'>
			<TD id='kat1'>Passwort wiederholen:</TD>
			<TD id='kat1' colspan='2'><input type='password' name='new_pwd_2' style='width:200px;' value='$u_name'></TD>
		</TR>
		
		<TR id='normal' style='height:10px;>
			<TD id='normal' colspan='3'></TD>
		</TR>
		<input type='hidden' name='u_name' value = '$u_name' style='width:200px;'>
		<TR id='kat'>
			<TD id='kat1' colspan='3' style='text-align:center;'><INPUT type=\"submit\" value=\"Speichern\" style='margin-right:20px;'><INPUT type=\"button\" value=\"Abbrechen\" onClick=\"javascript:history.back()\"></TD>
		</TR>
		
		<TR id='kat' style='height:3px;'>
			<TD class='normal' style='background-color:#ff9900;' colspan = '3'></TD>
		</TR>
		
		</TABLE>
		</FORM>
		</div>
		
		<div id='spalte2'>
		<p id='elf' style='background-color:white; padding: 5px; width: 385px; margin-top: 4px; margin-left: 10px;'><b>Hilfe zu den Bearbeitungsm&ouml;glichkeiten:</b><BR><BR>
		Sie k&ouml;nnen die pers&ouml;nlichen Angaben unver&auml;ndert lassen und nur das Passwort &auml;ndern.<BR>
		F&uuml;llen Sie hierzu lediglich die beiden unteren Eingabefelder aus.<BR>
		Standardm&auml;&szlig;ig ist hier der betreffende Benutzername als Passwort vorbelegt.<BR><BR>
		Wenn Sie nur die pers&ouml;nlichen Daten ver&auml;ndern wollen, m&uuml;ssen Sie die &Auml;nderungen mit dem Passwort des betreffenden Benutzers best&auml;tigen.<BR>
		Dies gilt auch, wenn Sie nur die Downloadoption &auml;ndern wollen.<BR><BR>
		F&uuml;llen Sie hierzu - nachdem Sie die entsprechenden &Auml;nderungen an den pers&ouml;nlichen Daten vorgenommen haben - die beiden unteren Passwort-Felder mit dem bisherigen Passwort des betreffenden Benutzers aus.<BR>
		Anderenfalls wird das bisherige Passwort durch den Benutzernamen des betreffenden Benutzers ersetzt.<BR>
		Dies ist die Voreinstellung.</p>
		</div>
	
		<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>
	
	</div>";
}
ELSEIF(!hasPermission($c_username, 'editmyprofile') AND !hasPermission($c_username, 'editallprofiles'))
{
	echo "<meta http-equiv='refresh' content = '0; URL=../start.php'>";
}

mysql_close($conn);
?></DIV>
</CENTER>
</BODY>
</HTML>
