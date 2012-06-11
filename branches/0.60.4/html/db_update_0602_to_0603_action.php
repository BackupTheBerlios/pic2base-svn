<!doctype html>
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Datenbank-Umstrukturierung</TITLE>
	<META NAME="GENERATOR" CONTENT="Eclipse">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../css/format1.css'>
	<link rel="shortcut icon" href="../share/images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>
<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: db_update_062_to_063_action.php
 * 
 * Umstrukturierung der Datenbank von Version 0.60.2 auf 0.60.3
 *
 * Copyright (c) 2012 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 */

include '../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/permissions.php';
include $sr.'/bin/share/functions/main_functions.php';

IF($_POST['user'] !== '')
{
	$user = $_POST['user'];
}
ELSE
{
	$user = '';
}

IF($_POST['pwd'] !== '')
{
	$pwd = $_POST['pwd'];
}
ELSE
{
	$pwd = '';
}

IF($user == '' OR $pwd == '')
{
	echo "<CENTER><fieldset style='width:700px; background-color:yellow; margin-top:50px;'>
	<legend style='color:blue; font-weight:bold;'>FEHLER</legend>
	<p style='font-size:14px; font-weight:bold; margin-top:20px; margin-bottom:20px; color:red;'>Sie m&uuml;ssen die Zugangsdaten eines Datenbank-Administrators eingeben,<BR><BR>
	sonst kann das Update nicht ausgef&uuml;hrt werden!<BR><BR>
	<input type='button' value='Zur vorherigen Seite' onClick='javaScript:history.back()'>
	</p>
	</fieldset>
	</CENTER>";
	return;
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// Welche Schritte werden ausgefuehrt?
// Tabelle doubletten wird mit den Feldern id , old_pic_id, new_pic_id und user_id angelegt
//
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// eine bestehende Datenbank-Verbindung wird geschlossen:
mysql_close($conn);
//
// und mit Admin-Rechten neu aufgebaut:
@$conn = mysql_connect('localhost',$user,$pwd);
//
//##########################################################################################################



$res0 = mysql_select_db('pic2base');
echo "
<div class='page'>

	<div class='title'>
	<p id='kopf'>pic2base - Datenbank-Umstrukturierung auf Version 0.60.3</p>
	</div>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>
		<a class='navi_blind'></a>
		<a class='navi' href='start.php?check=0'>Startseite</a>
		<a class='navi' href='index.php'>Logout</a>
		</div>
	</div>
	
	<div class='content'>
	<p style='margin:170px 0px; text-align:center'>";

	$error = 0;		//Zaehlvariable fuer Fehlermeldungen
	// es wird kontrolliert, ob die Tabelle doubletten vorhanden ist. Wenn nicht, wird sie angelegt:
	$res = mysql_query("SHOW tables LIKE 'doubletten'");
	IF(!mysql_num_rows($res))
	{
		echo "Alte DB-Struktur wird in Version 0.60.3 &uuml;berf&uuml;hrt...<BR><BR>";
		$res11 = mysql_query( "CREATE TABLE IF NOT EXISTS `doubletten` (
			`id` INT NOT NULL AUTO_INCREMENT ,
			`old_pic_id` INT NOT NULL COMMENT 'alte Bild-ID',
			`new_pic_id` INT NOT NULL COMMENT 'neue Bild-ID',
			`user_id` INT NOT NULL COMMENT 'Benutzer-ID',
			PRIMARY KEY ( `id` )
			) ENGINE = MYISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=0;");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Anlage der Tabelle \"doubletten\"<BR>";
				$error++;
			}
			ELSE
			{
				echo "Tabelle \"doubletten\" wurde angelegt.<BR>";
			}
			
			IF($error !== 0)
			{
				echo "<font color='red'>Es traten Probleme bei der Datenbank-Umstrukturierung auf.<BR><BR>
				Bitte notieren Sie die oben gezeigten Meldungen<BR>und wenden Sie sich an Ihren Administrator.</font><BR><BR>";
			}
			ELSE
			{
				//nur wenn alle anderen Operationen erfolgreich waren, wird die neue p2b-Version gespeichert:
				$res121 = mysql_query("UPDATE `pfade` SET p2b_version ='0.60.3'");
				IF(mysql_error() == '')
				{
					echo "<b><font color='green'>Die Datenbank-Umstrukturierung wurde erfolgreich beendet.<BR><BR>
					Bitte kontrollieren Sie das Ergebnis nochmals mit des Software-Check.</font></b><BR><BR>";
				}
				ELSE
				{
					echo "<b><font color='red'>Die pic2base-Version konnte nicht gespeichert werden.</font></b><BR><BR>";
					echo mysql_error();
				}
			}
	}
	ELSE
	{
		echo "Die neue DB-Struktur ist bereits vorhanden.<BR>
		Kehren Sie bitte zur Startseite zur&uuml;ck.<BR>";
	} 

    echo "</p>
	</div>
	<br style='clear:both;' />

	<div class='fuss'>
	<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>
	</div>

</div>";

mysql_close($conn);

?>
</DIV>
</CENTER>
</BODY>
</HTML>