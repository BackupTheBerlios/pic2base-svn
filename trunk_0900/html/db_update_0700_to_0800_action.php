<!doctype html>
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Datenbank-Update</TITLE>
	<META NAME="GENERATOR" CONTENT="Eclipse">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../css/format2.css'>
	<link rel="shortcut icon" href="../share/images/favicon.ico">
	<script language="JavaScript" src="../share/functions/resize_elements.js"></script>
	<script language="JavaScript" src="../share/functions/jquery-1.8.2.min.js"></script>
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
error_reporting(0);
/*
 * Project: pic2base
 * File: db_update_0700_to_0800_action.php
 * 
 * Umstrukturierung der Datenbank von Version 0.70.0 auf 0.80.0
 *
 * Copyright (c) 2013 Klaus Henneberg
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

$log_file = "update_0700-0800.log";

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
// (Voraussetzung: /bin-Ordner wurde bereits durch die aktuelle Version ersetzt)
//
// 1) Tabelle 'label_translation' anlegen
// 2) Tabelle 'label_translation' mit Werten vorbelegen
//
// Tabelle 'pfade':
// 6) version auf 0.80.0 setzen																			OK
// 7) Auswertung																						OK
// /share/global_config muss manuell uebernommen und angepasst werden
// /pic2base/index.php muss manuell aus dem Installationspaket uebernommen werden
//
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// eine bestehende Datenbank-Verbindung wird geschlossen:
mysql_close($conn);
// und mit Admin-Rechten neu aufgebaut:
@$conn = mysql_connect('localhost',$user,$pwd);
//
//##########################################################################################################

$res0 = mysql_select_db('pic2base');
echo "
<div class='page' id='page'>

	<div id='head'>
			pic2base :: Datenbank-Update auf Version 0.80.0
	</div>
	
	<div class='navi' id='navi'>
		<div class='menucontainer'>
		</div>
	</div>
	
	<div class='content' id='content'>
	<p style='margin:170px 0px; text-align:center'>";

	$error = 0;		//Zaehlvariable fuer Fehlermeldungen
	
//	1)

	$result1 = mysql_query("CREATE TABLE `pic2base`.`label_translation` (
	`id` INT NOT NULL AUTO_INCREMENT ,
	`label_name` VARCHAR( 100 ) NOT NULL ,
	`language` VARCHAR( 5 ) NOT NULL ,
	`value` TEXT NOT NULL COMMENT 'Inhalt des Labels / Textes',
	`used_in` VARCHAR( 100 ) NOT NULL COMMENT 'verwendet in welchem Skript',
	PRIMARY KEY ( `id` )
	) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT = 'Tabelle der Label-Uebersetzungen';");

	if(mysql_error() !== "")
	{
		$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
		fwrite($fh,date('d.m.Y H:i:s').": Fehler: Tabelle label_translation konnte nicht angelegt werden.\n");
		fclose($fh);
		echo "Die Tabelle 'label_translation' wurde NICHT angelegt.<BR>";
		$error++;
	}
	else
	{
		$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
		fwrite($fh,date('d.m.Y H:i:s').": Tabelle label_translation wurden angelegt.\n");
		fclose($fh);
		echo "Die Tabelle 'label_translation' wurde angelegt.<BR>";
	}

//	2)

	$result2 = mysql_query("INSERT INTO `label_translation` (`id`, `label_name`, `language`, `value`, `used_in`) VALUES
	(1, 'online_hinweis1', 'de', 'Es konnte keine &Uuml;berpr&uuml;fung auf Online-Updates erfolgen.<BR>M&ouml;glicherweise haben Sie keinen Internet-Zugang.', '/bin/html/start.php'),
	(2, 'online_hinweis1', 'en', 'It wasn''t possible to check for online updates.<br>May be there is no online connection.', '/bin/html/start.php'),
	(3, 'online_hinweis3', 'de', '<FONT COLOR=''green''>Es sind keine Online-Updates verf&uuml;gbar.</font>', '/bin/html/start.php'),
	(4, 'online_hinweis3', 'en', '<FONT COLOR=''green''>There are no updates available.</font>', '/bin/html/start.php'),
	(5, 'loesch_text', 'de', 'Hinweis zur Datenbank-Wartung:', '/bin/html/start.php'),
	(6, 'loesch_hinweis1', 'de', '<FONT COLOR=''red''>Es wurde ein Bild zum L&ouml;schen vorgemerkt.</FONT>', '/bin/html/start.php'),
	(7, 'loesch_hinweis2', 'de', '<FONT COLOR=''red''>Zum L&ouml;schen vorgemerkte Bilder</FONT>', '/bin/html/start.php'),
	(8, 'loesch_hinweis3', 'de', '<FONT COLOR=''green''>Es wurden keine Bilder zum L&ouml;schen vorgemerkt.</FONT>', '/bin/html/start.php'),
	(9, 'loesch_hinweis3', 'en', '<FONT COLOR=''green''>There are no pictures marked for deleting.</FONT>', '/bin/html/start.php'),
	(10, 'loesch_hinweis2', 'en', '<FONT COLOR=''red''>Marked pictures for deleting</font>', '/bin/html/start.php');");
	
	if(mysql_error() !== "")
	{
		$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
		fwrite($fh,date('d.m.Y H:i:s').": Fehler: Tabelle label_translation konnte nicht mit Inhalt befuellt werden.\n");
		fclose($fh);
		echo "Die Tabelle 'label_translation' wurde N I C H T mit Inhalt befuellt.<BR>";
		$error++;
	}
	else
	{
		$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
		fwrite($fh,date('d.m.Y H:i:s').": Tabelle label_translation wurden mit Inhalt befuellt.\n");
		fclose($fh);
		echo "Die Tabelle 'label_translation' wurde mit Inhalt befuellt.<BR>";
	}
	
	
//	3)

	
	

//	4)
	
	

//	5)

	
	

//	6)

	$result10 = mysql_query("UPDATE $table16 SET p2b_version='0.80.0'");
	if(mysql_error() !== "")
	{
		echo mysql_error();
		$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
		fwrite($fh,date('d.m.Y H:i:s').": Fehler: Update der Pfade-Tabelle; Version konnte nicht aktualisiert werden.\n");
		fclose($fh);
		$error++;
	}
	else
	{
		$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
		fwrite($fh,date('d.m.Y H:i:s').": Update der Pfade-Tabelle; Version wurde auf 0.70.0 aktualisiert.\n");
		fclose($fh);
	}
	echo "In der Tabelle 'pfade' wurde die pic2base-Version aktualisiert.<BR>";


// 	7) Auswertung des Update-Verlaufs

	if($error !== 0)
	{
		echo "<BR><FONT COLOR='red'>Das Update wurde <b>fehlerhaft</b> beendet.</FONT><BR><BR>
		<FONT COLOR='red'>Bitte informieren Sie zur Fehleranalyse Ihren Administrator, bevor Sie die Arbeit fortsetzen.</FONT><BR><BR>
		Das Update-Protokoll finden Sie <a href=\"../../log/$log_file\">hier</a>.<BR><BR>";
	}
	else
	{
		echo "<BR><FONT COLOR='green'>Das Update wurde <b>erfolgreich</b> beendet.</FONT><BR><BR>
		Das Update-Protokoll finden Sie <a href=\"../../log/$log_file\">hier</a>.<BR><BR>
		<INPUT type='button' value='Zur Startseite' onClick=location.href='start.php?check=0'>";
	}
	

    echo "</p>
	</div>

	<div id='foot'>
			<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
	</div>

</div>";

mysql_close($conn);
error_reporting(-1);

// falls das Skript mehrfach aufgerufen wird, werden zur besseren Trennung Leerzeilen eingefuegt:
$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
fwrite($fh,"########################################################################################################\n\n\n");
fclose($fh);
?>
</DIV>
</CENTER>
</BODY>
</HTML>