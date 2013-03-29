<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Pers&ouml;nliche Einstellungen</TITLE>
	<META NAME="GENERATOR" CONTENT="eclipse">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format2.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script language="JavaScript" src="../../share/functions/resize_elements.js"></script>
	<script language="JavaScript" src="../../share/functions/jquery-1.8.2.min.js"></script>
	<script language="JavaScript">
	  	jQuery.noConflict();
		jQuery(document).ready(checkWindowSize);
		jQuery(window).resize(checkWindowSize); 
	</script>
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

IF ($_COOKIE['uid'])
{
	$uid = $_COOKIE['uid'];
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/ajax_functions.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/permissions.php';

$result1 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result1, isset($i1), 'username');

IF(hasPermission($uid, 'editmyprofile', $sr) AND !hasPermission($uid, 'editallprofiles', $sr))
{
//	Benutzer darf nur seine eigenen Daten aendern
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
	<div class='page' id='page'>
	
		<div id='head'>
			pic2base :: Pers&ouml;nliche Einstellungen anpassen <span class='klein'>(User: $username)</span>
		</div>
			
		<div class='navi' id='navi'>
			<div class='menucontainer'>";
			createNavi5($uid);
			echo "</div>
		</div>
		
		<div id='spalte1'>
			<fieldset style='background-color:none; margin-top:10px;'>
				<legend style='color:blue; font-weight:bold;'>Legen Sie hier Ihre pers&ouml;nlichen Einstellungen fest:</legend>
				<div id='scrollbox0' style='overflow-y:scroll;'>
			
				<FORM name = 'pwd' method = post action = 'save_pwd1.php?mod=my'>
				<TABLE align=center style='width:90%;border-width:1px;border-color:#DDDDFF;border-style:none;padding:0px;margin-top:6px;margin-bottom:0px;
			    	text-align:center;'>
				<TR class='kat' style='height:3px;'>
					<TD class='normal' style='background-color:#ff9900;' colspan = '3'></TD>
				</TR>
				
				<TR class='kat'>
					<TD class='kat1'>Titel:</TD>
					<TD class='kat1' colspan='2'><input type='text' name='titel' value = '$titel' style='width:200px;'></TD>
				</TR>
				
				<TR class='kat'>
					<TD class='kat1'>Name:</TD>
					<TD class='kat1' colspan='2'><input type='text' name='name' value = '$name' style='width:200px;'></TD>
				</TR>
				
				<TR class='kat'>
					<TD class='kat1'>Vorname:</TD>
					<TD class='kat1' colspan='2'><input type='text' name='vorname' value = '$vorname' style='width:200px;'></TD>
				</TR>
				
				<TR class='kat'>
					<TD class='kat1'>Strasse:</TD>
					<TD class='kat1' colspan='2'><input type='text' name='strasse' value = '$strasse' style='width:200px;'></TD>
				</TR>
				
				<TR class='kat'>
					<TD class='kat1'>PLZ:</TD>
					<TD class='kat1' colspan='2'><input type='text' name='plz' value = '$plz' style='width:200px;'></TD>
				</TR>
				
				<TR class='kat'>
					<TD class='kat1'>Ort:</TD>
					<TD class='kat1' colspan='2'><input type='text' name='ort' value = '$ort' style='width:200px;'></TD>
				</TR>
				
				<TR class='kat'>
					<TD class='kat1'>Telefon:</TD>
					<TD class='kat1' colspan='2'><input type='text' name='tel' value = '$tel' style='width:200px;'></TD>
				</TR>
				
				<TR class='kat'>
					<TD class='kat1'>eMail:</TD>
					<TD class='kat1' colspan='2'><input type='text' name='email' value = '$email' style='width:200px;'></TD>
				</TR>
				
				<TR class='kat'>
					<TD class='kat1'>Internet:</TD>
					<TD class='kat1' colspan='2'><input type='text' name='internet' value = '$internet' style='width:200px;'></TD>
				</TR>
				
				<TR class='kat'>
					<TD class='kat1'>Sprache:</TD>
					<TD class='kat1' colspan='2'>
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
				
				<TR class='kat'>
					<TD class='kat1'>Bild-Download:</TD>
					<TD class='kat1' colspan='2'>
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
				
				<TR class='normal'>
					<TD class='kat1' colspan='3'>Passwort &auml;ndern</TD>
				</TR>
				
				<TR class='kat'>
					<TD class='kat1'>Altes Passwort:</TD>
					<TD class='kat1' colspan='2'><input type='password' name='old_pwd' style='width:200px;'></TD>
				</TR>
				
				<TR class='kat'>
					<TD class='kat1'>Neues Passwort:</TD>
					<TD class='kat1' colspan='2'><input type='password' name='new_pwd_1' style='width:200px;' value='$u_name'></TD>
				</TR>
				
				<TR class='kat'>
					<TD class='kat1'>Passwort wiederholen:</TD>
					<TD class='kat1' colspan='2'><input type='password' name='new_pwd_2' style='width:200px;' value='$u_name'></TD>
				</TR>
				
				<TR class='normal' style='height:10px;>
					<TD class='normal' colspan='3'></TD>
				</TR>
				<input type='hidden' name='u_name' value = '$u_name' style='width:200px;'>
				<TR class='kat'>
					<TD class='kat1' colspan='3' style='text-align:center;'><INPUT type=\"submit\" value=\"Speichern\" style='margin-right:20px;'><INPUT type=\"button\" value=\"Abbrechen\" onClick=\"location.href='../start.php'\"></TD>
				</TR>
				
				<TR class='kat' style='height:3px;'>
					<TD class='normal' style='background-color:#ff9900;' colspan = '3'></TD>
				</TR>
				
				</TABLE>
				</FORM>
				</div>
			</fieldset>
		</div>
		
		<div id='spalte2'>
		<fieldset style='background-color:none; margin-top:10px;'>
				<legend style='color:blue; font-weight:bold;'>Hilfe zu den Bearbeitungsm&ouml;glichkeiten:</legend>
				<div id='scrollbox1' style='overflow-y:scroll;'>
				Sie k&ouml;nnen Ihre pers&ouml;nlichen Angaben unver&auml;ndert lassen und nur Ihr Passwort &auml;ndern.<BR>
				F&uuml;llen Sie hierzu lediglich die drei unteren Eingabefelder aus.<BR><BR>
				Wenn Sie Ihre pers&ouml;nlichen Daten ver&auml;ndern wollen, m&uuml;ssen Sie die &Auml;nderungen mit Ihrem Passwort best&auml;tigen.<BR>
				F&uuml;llen Sie hierzu ebenfalls die drei unteren Passwort-Felder aus.<BR><BR>
				Hinweis: <BR>
				Wollen Sie Ihr altes Passwort beibehalten, tragen Sie dies in alle drei Passwort-Felder ein.
				</div>
			</fieldset>
		</div>
	
		<div id='foot'>
			<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
		</div>
	
	</div>";
}
ELSEIF(hasPermission($uid, 'editallprofiles', $sr))
{
	// Benutzer darf auch Daten der anderen User aendern
	$result2 = mysql_query("SELECT * FROM $table1");
	echo mysql_error();
	$num2 = mysql_num_rows($result2);
	
	echo "
	<div class='page' id='page'>
	
		<div id='head'>
			pic2base :: Benutzerdaten anpassen <span class='klein'>(User: $username)</span>
		</div>
			
		<div class='navi' id='navi'>
			<div class='menucontainer'>";
			createNavi5($uid);
			echo "</div>
		</div>
		
		<div id='spalte1'>
			<fieldset style='background-color:none; margin-top:10px;'>
				<legend style='color:blue; font-weight:bold;'>Von welchem Benutzer wollen Sie die pers. Einstellungen &auml;ndern?</legend>
				<div id='scrollbox0' style='overflow-y:scroll;'>
		
				<TABLE align=center style='width:90%;border-width:1px;border-color:#DDDDFF;border-style:none;padding:0px;margin-top:6px;margin-bottom:0px;
			    	text-align:center;'>
				<TR class='kat' style='height:3px;'>
					<TD class='normal' style='background-color:#ff9900;' colspan = '4'></TD>
				</TR>
				<TR class='normal' style='height:10px;>
					<TD class='normal' colspan='3'></TD>
				</TR>";
				
				FOR($i2='0'; $i2<$num2; $i2++)
				{
					$user_id = mysql_result($result2, $i2, 'id');
					$benutzername = mysql_result($result2, $i2, 'username');	//Namen der registrierten Benutzer, nicht des bearbeitenden Benutzers
					$titel = mysql_result($result2, $i2, 'titel');
					$vorname = mysql_result($result2, $i2, 'vorname');
					$name = mysql_result($result2, $i2, 'name');
					//IF($username !== 'pb')
					IF($benutzername !== 'pb' AND ($benutzername !== $username OR hasPermission($uid, 'editmyprofile', $sr)))
					{
						echo "
						<TR class='kat'>
						<TD class='kat1'>".$titel."</TD>
						<TD class='kat1'>".$vorname."</TD>
						<TD class='kat1'>".$name."</TD>
						<TD class='kat1'><center><span style='cursor:pointer'><img src='../../share/images/edit.gif' style='border:none' onClick=\"location.href='einstellungen2.php?id=$user_id'\" title='Pers&ouml;nliche Daten bearbeiten' /></span></center></TD>
						</TR>";
					}
				}
			
				echo "
				<TR class='normal' style='height:10px;>
					<TD class='normal' colspan='3'></TD>
				</TR>
				
				<TR class='kat' style='height:3px;'>
					<TD class='normal' style='background-color:#ff9900;' colspan = '4'></TD>
				</TR>
				
				</TABLE>
				
				</div>
			</fieldset>
		
		</div>
		
		<div id='spalte2'>
		<fieldset style='background-color:none; margin-top:10px;'>
				<legend style='color:blue; font-weight:bold;'>Hilfe zu den Bearbeitungsm&ouml;glichkeiten:</legend>
				<div id='scrollbox1' style='overflow-y:scroll;'>
				Klicken Sie auf den Bearbeiten-Button, wenn Sie die pers&ouml;nlichen Daten eines Benutzers &auml;ndern wollen.
				</div>
			</fieldset>
		</div>
	
		<div id='foot'>
			<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
		</div>
	
	</div>";
}
ELSEIF(!hasPermission($uid, 'editmyprofile', $sr) AND !hasPermission($uid, 'editallprofiles', $sr))
{
	echo "<meta http-equiv='refresh' content = '10; URL=../start.php'>";
}

mysql_close($conn);
?>
</DIV>
</CENTER>
</BODY>
</HTML>