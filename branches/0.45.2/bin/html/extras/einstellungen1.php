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

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?php

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

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/ajax_functions.php';
include $sr.'/bin/share/functions/main_functions.php';


echo "
<div class='page'>

	<p id='kopf'>pic2base :: Pers&ouml;nliche Einstellungen <span class='klein'>(User: $c_username)</span></p>
		
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
		createNavi5($c_username);
		echo "</div>
	</div>
	
	<div id='spalte1'>
	<p id='elf' style='background-color:white; padding: 5px; margin-top: 4px; margin-left: 0px; text-align:center;'>Legen Sie hier Ihre pers&ouml;nlichen Einstellungen fest:<BR>
	<FORM name = 'pwd' method = post action = 'save_pwd1.php'>
	<TABLE align=center style='width:90%;border-width:1px;border-color:#DDDDFF;border-style:none;padding:0px;margin-top:6px;margin-bottom:0px;
    	text-align:center;'>
	<TR id='kat' style='height:3px;'>
		<TD class='normal' style='background-color:#ff9900;' colspan = '3'></TD>
	</TR>
	
	<TR id='normal'>
		<TD id='kat1' colspan='3'>Passwort &auml;ndern</TD>
	</TR>
	
	<TR id='kat'>
		<TD id='kat1'>Altes Passwort:</TD>
		<TD id='kat1' colspan='2'><input type='password' name='old_pwd' style='width:200px;'</TD>
	</TR>
	
	<TR id='kat'>
		<TD id='kat1'>Neues Passwort:</TD>
		<TD id='kat1' colspan='2'><input type='password' name='new_pwd_1' style='width:200px;'</TD>
	</TR>
	
	<TR id='kat'>
		<TD id='kat1'>Passwort wiederholen:</TD>
		<TD id='kat1' colspan='2'><input type='password' name='new_pwd_2' style='width:200px;'</TD>
	</TR>
	
	<TR id='normal' style='height:10px;>
		<TD id='normal' colspan='3'></TD>
	</TR>
	
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
	
	</div>

	<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>

</div>";

mysql_close($conn);
?>
</DIV>
</CENTER>
</BODY>
</HTML>