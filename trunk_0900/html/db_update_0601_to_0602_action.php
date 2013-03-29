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
 * File: db_update_061_to_062_action.php
 * 
 * Umstrukturierung der Datenbank von Version 0.60.1 auf 0.60.2
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
// Tabelle tag_trans wird mit den Feldern id und lang neu angelegt
// die Benutzerrechte fuer den user pic_base werden für die Tabelle tag_trans auf ALTER erweitert, damit
// dynamisch Felder eingefuegt werden koennen
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
	<p id='kopf'>pic2base - Datenbank-Umstrukturierung auf Version 0.60.2</p>
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

//=========================================================================================================
// 1) Zur Optimierung der Metadaten-Verwaltung wird ab Version 0.60.2 eine neue DB-Struktur verwendet
// In der Tabelle tag_trans werden die Tag-Namen in den Uebersetzungen gespeichert, die von exiftool mitgeliefert werden.
// Die Uebersetzungen werden bei der ersten Anforderung einer anderen Sprache in die Tabelle geschrieben 
// und dann immer daraus gelesen.
// Diese Tabelle wird dynamisch um weitere Matadatenfelder ergaenzt, wenn ein Bild bisher nicht verwendete Felder mitbringt
//
// 2) Speziell fuer Installationen auf Nicht-LAMPP-Umgebungen wird bei der Installation die UID/GID des Apache automatisch
// ermittelt und in der Tabelle pfade gespeichert:
// dazu Struktur um die Felder apache_uid, apache_gid erweitern
//=========================================================================================================
	$error = 0;		//Zaehlvariable fuer Fehlermeldungen
	// es wird kontrolliert, ob die Tabelle tag_trans vorhanden ist. Wenn nicht, wird umstruturiert:
	$res = mysql_query("SHOW tables LIKE 'tag_trans'");
	IF(!mysql_num_rows($res))
	{
		
		
		echo "Alte DB-Struktur wird in Version 0.60.2 &uuml;berf&uuml;hrt...<BR><BR>";
		
		$res1 = mysql_query("TRUNCATE TABLE `meta_protect`");
		IF(mysql_error() !== '')
		{
			echo "Fehler beim leeren der Tabelle \"meta_protect\"<BR>";
			$error++;
		}
		ELSE
		{
			echo "Tabelle \"meta_protect\" wurde geleert.<BR>";
		}
		
		$res11 = mysql_query( "CREATE TABLE IF NOT EXISTS `tag_trans` (
			`id` INT NOT NULL AUTO_INCREMENT ,
			`lang` VARCHAR( 5 ) NOT NULL COMMENT 'Benutzersprache',
			`Make` VARCHAR( 50 ) NOT NULL ,
			PRIMARY KEY ( `id` )
			) ENGINE = MYISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=0;");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Anlage der Tabelle \"tag_trans\"<BR>";
				$error++;
			}
			ELSE
			{
				echo "Tabelle \"tag_trans\" wurde angelegt.<BR>";
			}

			$res11_1 = mysql_query( "INSERT INTO `tag_trans` (`id` ,`lang` ,`Make`) VALUES 
			(NULL , 'en', 'Make'),
			(NULL , 'de', 'Hersteller');");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Bef&uuml;llung der Tabelle \"tag_trans\"<BR>";
				$error++;
			}
			ELSE
			{
				echo "Tabelle \"tag_trans\" wurde mit Daten vorbelegt.<BR>";
			}
			
			//Die Tabelle pfade wird um die Felder apache_uid und apache_gid ergaenzt::
			$res12 = mysql_query("ALTER TABLE `$table16` ADD 
			(`apache_uid` smallint(5) unsigned NOT NULL COMMENT 'UID des Webservers' default '65534',
			`apache_gid` smallint(5) unsigned NOT NULL COMMENT 'GID des Webservers' default '65534' ,
			`p2b_version` VARCHAR( 10 ) NOT NULL DEFAULT '0.00.0' COMMENT 'Update-Kriterium, verwendet ab Version 0.60.2')");
		
			IF(mysql_error() <> '')
			{
				echo mysql_error()."<BR><BR>";
				echo "Fehler beim Hinzufuegen der Felder in die Tabelle Pfade.<BR><BR>";
				$error++;
			}
			ELSE
			{
				echo "Die Tabelle \"pfade\" wurde modifiziert.<BR><BR>";
			}
			
			$res118 = mysql_query("GRANT ALTER ON `pic2base`.`pictures` TO 'pb'@'localhost';");
			IF(mysql_error() <> '')
			{
				echo "Fehler bei der Rechteaenderung fuer User pb auf die Tabelle pictures.<BR><BR>";
				$error++;
			}
			ELSE
			{
				echo "Die Rechte fuer User pb auf die Tabelle pictures wurden angepasst..<BR><BR>";
			}
			
			$res119 = mysql_query("GRANT ALTER ON `pic2base`.`tag_trans` TO 'pb'@'localhost';");
			IF(mysql_error() <> '')
			{
				echo "Fehler bei der Rechteaenderung fuer User pb auf die Tabelle tag_trans.<BR><BR>";
				$error++;
			}
			ELSE
			{
				echo "Die Rechte fuer User pb auf die Tabelle tag_trans wurden angepasst..<BR><BR>";
			}
			
			IF($error !== 0)
			{
				echo "<font color='red'>Es traten Probleme bei der Datenbank-Umstrukturierung auf.<BR><BR>
				Bitte notieren Sie die oben gezeigten Meldungen<BR>und wenden Sie sich an Ihren Administrator.</font><BR><BR>";
			}
			ELSE
			{
				//nur wenn alle anderen Operationen erfolgreich waren, wird die neue p2b-Version gespeichert:
				$res121 = mysql_query("INSERT INTO `pfade` (p2b_version) VALUES ('0.60.2')");
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