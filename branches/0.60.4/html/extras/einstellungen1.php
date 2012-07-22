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
 * Copyright (c) 2006 - 2012 Klaus Henneberg
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
list($c_username) = preg_split('/,/',$_COOKIE['login']);
//echo $c_username;
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/ajax_functions.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/permissions.php';

IF(hasPermission($c_username, 'editmyprofile') AND !hasPermission($c_username, 'editallprofiles'))
{
	$result1 = mysql_query("SELECT * FROM $table1 WHERE username = '$c_username'");
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
	$language = mysql_result($result1, isset($i1), 'language');
	$direkt_download = mysql_result($result1, isset($i1), 'direkt_download');
	
	echo "
	<div class='page'>
	
		<p id='kopf'>pic2base :: Pers&ouml;nliche Einstellungen anpassen <span class='klein'>(User: $c_username)</span></p>
			
		<div class='navi' style='clear:right;'>
			<div class='menucontainer'>";
			createNavi5($c_username);
			echo "</div>
		</div>
		
		<div id='spalte1'>
		<p id='elf' style='background-color:white; padding: 5px; margin-top: 4px; margin-left: 0px; text-align:center;'>Legen Sie hier Ihre pers&ouml;nlichen Einstellungen fest:<BR>
		<FORM name = 'pwd' method = post action = 'save_pwd1.php?mod=my'>
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
			<TD id='kat1'>Sprache:</TD>
			<TD id='kat1' colspan='2'>
			<SELECT name='language' style='width:200px;'>";
			SWITCH($language)
			{
				CASE 'de':
					$de = 'selected';
				break;
				
				CASE 'en':
					$en = 'selected';
				break;
				
				CASE 'ru':
					$ru = 'selected';
				break;
				
				CASE 'cs':
					$cs = 'selected';
				break;
				
				CASE 'es':
					$es = 'selected';
				break;
				
				CASE 'fr':
					$fr = 'selected';
				break;
				
				CASE 'it':
					$it = 'selected';
				break;
				
				CASE 'ja':
					$ja = 'selected';
				break;
				
				CASE 'ko':
					$ko = 'selected';
				break;
				
				CASE 'nl':
					$nl = 'selected';
				break;
				
				CASE 'pl':
					$pl = 'selected';
				break;
				
				CASE 'sv':
					$sv = 'selected';
				break;
				
				CASE 'tr':
					$tr = 'selected';
				break;
			}
			
				echo "
				<option value='de' $de>Deutsch</option>
				<option value='en' $en>English</option>
				<option value='ru' $ru>Russisch</option>
				<option value='cs' $cs>Czech</option>
				<option value='es' $es>Espanol</option>
				<option value='fr' $fr>Francias</option>
				<option value='it' $it>Italiano</option>
				<option value='ja' $ja>Japanese</option>
				<option value='ko' $ko>Korean</option>
				<option value='nl' $nl>Nederlands</option>
				<option value='pl' $pl>Polski</option>
				<option value='sv' $sv>Svenska</option>
				<option value='tr' $tr>T&uuml;rkce</option>";
			
			echo "
			</TD>
		</TR>
		
		<TR id='kat'>
			<TD id='kat1'>Bild-Download:</TD>
			<TD id='kat1' colspan='2'>
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
		
		<TR id='kat'>
			<TD id='kat1'>Altes Passwort:</TD>
			<TD id='kat1' colspan='2'><input type='password' name='old_pwd' style='width:200px;'></TD>
		</TR>
		
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
			<TD id='kat1' colspan='3' style='text-align:center;'><INPUT type=\"submit\" value=\"Speichern\" style='margin-right:20px;'><INPUT type=\"button\" value=\"Abbrechen\" onClick=\"location.href='../start.php'\"></TD>
		</TR>
		
		<TR id='kat' style='height:3px;'>
			<TD class='normal' style='background-color:#ff9900;' colspan = '3'></TD>
		</TR>
		
		</TABLE>
		</FORM>
		</div>
		
		<div id='spalte2'>
		<p id='elf' style='background-color:white; padding: 5px; width: 385px; margin-top: 4px; margin-left: 10px;'><b>Hilfe zu den Bearbeitungsm&ouml;glichkeiten:</b><BR><BR>
		Sie k&ouml;nnen Ihre pers&ouml;nlichen Angaben unver&auml;ndert lassen und nur Ihr Passwort &auml;ndern.<BR>
		F&uuml;llen Sie hierzu lediglich die drei unteren Eingabefelder aus.<BR><BR>
		Wenn Sie Ihre pers&ouml;nlichen Daten ver&auml;ndern wollen, m&uuml;ssen Sie die &Auml;nderungen mit Ihrem Passwort best&auml;tigen.<BR>
		F&uuml;llen Sie hierzu ebenfalls die drei unteren Passwort-Felder aus.<BR><BR>
		Hinweis: <BR>
		Wollen Sie Ihr altes Passwort beibehalten, tragen Sie dies in alle drei Passwort-Felder ein.</p>
		</div>
	
		<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>
	
	</div>";
}
ELSEIF(hasPermission($c_username, 'editallprofiles'))
{
	$result2 = mysql_query("SELECT * FROM $table1");
	echo mysql_error();
	$num2 = mysql_num_rows($result2);
	
	echo "
	<div class='page'>
	
		<p id='kopf'>pic2base :: Benutzerdaten anpassen <span class='klein'>(User: $c_username)</span></p>
			
		<div class='navi' style='clear:right;'>
			<div class='menucontainer'>";
			createNavi5($c_username);
			echo "</div>
		</div>
		
		<div id='spalte1'>
		<p id='elf' style='background-color:white; padding: 5px; margin-top: 4px; margin-left: 0px; text-align:center;'>Von welchem Benutzer wollen Sie die pers. Einstellungen &auml;ndern?<BR>
		
		<TABLE align=center style='width:90%;border-width:1px;border-color:#DDDDFF;border-style:none;padding:0px;margin-top:6px;margin-bottom:0px;
	    	text-align:center;'>
		<TR id='kat' style='height:3px;'>
			<TD class='normal' style='background-color:#ff9900;' colspan = '4'></TD>
		</TR>
		<TR id='normal' style='height:10px;>
			<TD id='normal' colspan='3'></TD>
		</TR>";
		
		FOR($i2='0'; $i2<$num2; $i2++)
		{
			$user_id = mysql_result($result2, $i2, 'id');
			$username = mysql_result($result2, $i2, 'username');
			$titel = mysql_result($result2, $i2, 'titel');
			$vorname = mysql_result($result2, $i2, 'vorname');
			$name = mysql_result($result2, $i2, 'name');
			//IF($username !== 'pb')
			IF($username !== 'pb' AND ($username !== $c_username OR hasPermission($username, 'editmyprofile')))
			{
				echo "
				<TR id='kat'>
				<TD id='kat1'>".$titel."</TD>
				<TD id='kat1'>".$vorname."</TD>
				<TD id='kat1'>".$name."</TD>
				<TD id='kat1'><center><span style='cursor:pointer'><img src='../../share/images/edit.gif' style='border:none' onClick=\"location.href='einstellungen2.php?id=$user_id'\" title='Pers&ouml;nliche Daten bearbeiten' /></span></center></TD>
				</TR>";
			}
		}
	
		echo "
		<TR id='normal' style='height:10px;>
			<TD id='normal' colspan='3'></TD>
		</TR>
		
		<TR id='kat' style='height:3px;'>
			<TD class='normal' style='background-color:#ff9900;' colspan = '4'></TD>
		</TR>
		
		</TABLE>
		
		</div>
		
		<div id='spalte2'>
		<p id='elf' style='background-color:white; padding: 5px; width: 385px; margin-top: 4px; margin-left: 10px;'><b>Hilfe zu den Bearbeitungsm&ouml;glichkeiten:</b><BR><BR>
		Klicken Sie auf den Bearbeiten-Button, wenn Sie die pers&ouml;nlichen Daten eines Benutzers &auml;ndern wollen.</p>
		</div>
	
		<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>
	
	</div>";
}
ELSEIF(!hasPermission($c_username, 'editmyprofile') AND !hasPermission($c_username, 'editallprofiles'))
{
	echo "<meta http-equiv='refresh' content = '10; URL=../start.php'>";
}

mysql_close($conn);
?>
</DIV>
</CENTER>
</BODY>
</HTML>