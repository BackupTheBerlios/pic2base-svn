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
 * File: db_update_0603_to_0604_action.php
 * 
 * Umstrukturierung der Datenbank von Version 0.60.3 auf 0.60.4
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
// Die folgenden Schritte werden ausgefuehrt:
// Es wird kontrolliert, ob die Tabelle pic_kat die unique indizes  hat - bei Bedarf wird dies nachgeholt
// die Tabelle IVE_V_pic_kat_dubls (temp. Tabelle fuer die DB-Wartung) wird angelegt
// die Spalte aktiv wird der Tabelle pictures hinzugefuegt
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
	<p id='kopf'>pic2base - Datenbank-Umstrukturierung auf Version 0.60.4</p>
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
	
	//hat die Tabelle pic_kat die neuen Indizes?
	$res1 = mysql_query("select INDEX_NAME from information_schema.statistics WHERE TABLE_SCHEMA = 'pic2base' AND TABLE_NAME = 'pic_kat'");
	echo mysql_error();
	$num1 = mysql_num_rows($res1);
	$index_indikator = '4';			// 4 Indizes muessen neu erzeugt werden
	FOR($i1='0'; $i1<$num1; $i1++)
	{
		$index_name = mysql_result($res1, $i1, 'INDEX_NAME');
		//echo $index_name."<BR>";
		IF($index_name == 'ix_kat_pic' OR $index_name == 'ix_pic_kat')
		{
			$index_indikator--;
		}
	}
	IF($index_indikator !== '0')
	{
		$res2 = mysql_query("CREATE UNIQUE INDEX ix_kat_pic ON pic_kat (kat_id, pic_id)");
		IF(mysql_error() == '')
		{
			$res3 = mysql_query("CREATE UNIQUE INDEX ix_pic_kat ON pic_kat (pic_id, kat_id)");
		}
		IF(mysql_error() !== '')
		{
			echo "Fehler bei der Erzeugung der neuen Indizes der Tabelle \"pic_kat\"<BR>";
			$error++;
		}
		ELSE
		{
			echo "Tabelle \"pic_kat\" wurde modifiziert.<BR>";
		}
	}
	
	$res6 = mysql_query("SELECT p2b_version FROM $table16");		// nochmalige Kontrolle, dass es sich um die aktualisierbare Vorgaengerversion handelt
	$p2b_version = mysql_result($res6, isset($i6), 'p2b_version');
	IF($p2b_version == '0.60.3')
	{
		echo "Alte DB-Struktur wird in Version 0.60.4 &uuml;berf&uuml;hrt...<BR><BR>";
		
		// Tabelle ICE_V_pic_kat_dubls (fuer temp. Dublettenablage) anlegen:	
		$res10 = mysql_query("CREATE TABLE IF NOT EXISTS `ICE_V_pic_kat_dubls` (
		  `lfdnr` int(11) DEFAULT NULL,
		  `pic_id` int(11) DEFAULT NULL,
		  `kat_id` int(11) DEFAULT NULL,
		  `anzahl` int(11) DEFAULT NULL
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
		IF(mysql_error() !== '')
		{
			echo "Fehler bei der Anlage der Tabelle \"IVE_V_pic_kat_dubls\"<BR>";
			$error++;
		}
		ELSE
		{
			echo "Tabelle \"IVE_V_pic_kat_dubls\" wurde angelegt.<BR>";
		}
		
		// Tabelle pictures um Feld 'aktiv' ergaenzen:
		$res20 = mysql_query("ALTER TABLE `pictures` ADD `aktiv` TINYINT NOT NULL DEFAULT '1' COMMENT '0 wenn bild geloescht werden soll'");
		IF(mysql_error() !== '')
		{
			echo "Fehler bei der Modifizierung der Tabelle \"pictures\"<BR>";
			$error++;
		}
		ELSE
		{
			echo "Tabelle \"pictures\" wurde modifiziert.<BR>";
		}
		
		IF($error !== 0)
		{
			echo "<font color='red'>Es traten Probleme bei der Datenbank-Umstrukturierung auf.<BR><BR>
			Bitte notieren Sie die oben gezeigten Meldungen<BR>und wenden Sie sich an Ihren Administrator.</font><BR><BR>";
		}
		ELSE
		{
			//nur wenn alle anderen Operationen erfolgreich waren, wird die neue p2b-Version gespeichert:
			$res121 = mysql_query("UPDATE `pfade` SET p2b_version ='0.60.4'");
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
	ELSEIF($p2b_version == '0.60.4')
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