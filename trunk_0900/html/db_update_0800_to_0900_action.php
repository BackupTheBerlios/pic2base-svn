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
 * File: db_update_0800_to_0900_action.php
 * 
 * Umstrukturierung der Datenbank von Version 0.80.0 auf 0.90.0
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
// 1) Tabelle 'collections' anlegen
// 2) Tabelle der Bild-Sammlungs-Zuordnung anlegen
// 3) Neue Rechte editmycolls/419 und editallcolls/429 einrichten (Meine bzw. alle Kollektionen bearbeiten)
//
// Tabelle 'pfade':
// 6) version auf 0.90.0 setzen																			OK
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
			pic2base :: Datenbank-Update auf Version 0.90.0
	</div>
	
	<div class='navi' id='navi'>
		<div class='menucontainer'>
		</div>
	</div>
	
	<div class='content' id='content'>
	<p style='margin:170px 0px; text-align:center'>";

	$error = 0;		//Zaehlvariable fuer Fehlermeldungen
	
//	1)

	$result1 = mysql_query("CREATE TABLE `pic2base`.`collections` (
	 `coll_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Kollektions-ID',
	`coll_name` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Name der Sammlung',
	`coll_description` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Beschreibung der Sammlung',
	`coll_owner` INT NOT NULL COMMENT 'Eigentuemer der Sammlung',
	`created` DATETIME NOT NULL,
	`last_modification` TIMESTAMP NOT NULL,
	`locked` INT NOT NULL DEFAULT '1' COMMENT '1 - gesperrt fuer die Bearbeitung durch andere User',
	INDEX ( `coll_name` )
	) ENGINE = MYISAM COMMENT = 'Kollektions-Tabelle';");

	if(mysql_error() !== "")
	{
		$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
		fwrite($fh,date('d.m.Y H:i:s').": Fehler: Tabelle collections konnte nicht angelegt werden.\n");
		fclose($fh);
		echo "Die Tabelle 'collections' wurde NICHT angelegt.<BR>";
		$error++;
	}
	else
	{
		$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
		fwrite($fh,date('d.m.Y H:i:s').": Tabelle collections wurde angelegt.\n");
		fclose($fh);
		echo "Die Tabelle 'collections' wurde angelegt.<BR>";
	}

//	2)

	$result2 = mysql_query("CREATE TABLE `pic2base`.`pic_coll` (
	`id` INT NOT NULL AUTO_INCREMENT COMMENT 'Datensatz-ID',
	`coll_id` INT NOT NULL COMMENT 'Sammlungs-ID',
	`pic_id` INT NOT NULL COMMENT 'Bild_id',
	`duration` INT NOT NULL COMMENT 'Anzeigedauer in Sekunden',
	`position` INT NOT NULL COMMENT 'Position innerhalb der Show',
	`transition_id` INT NOT NULL COMMENT 'Uebergangs-ID',
	PRIMARY KEY ( `id` )
	) ENGINE = MYISAM COMMENT = 'Tabelle der Bild-Sammlungs-Zuordnung';");	
	
	if(mysql_error() !== "")
	{
		$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
		fwrite($fh,date('d.m.Y H:i:s').": Fehler: Tabelle pic_coll konnte nicht angelegt werden.\n");
		fclose($fh);
		echo "Die Tabelle 'pic_coll' wurde NICHT angelegt.<BR>";
		$error++;
	}
	else
	{
		$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
		fwrite($fh,date('d.m.Y H:i:s').": Tabelle pic_coll wurde angelegt.\n");
		fclose($fh);
		echo "Die Tabelle 'pic_coll' wurde angelegt.<BR>";
	}
	
	
//	3)

	$result3 = mysql_query("INSERT INTO $table8 (perm_id, description, shortdescription) 
	VALUES 
	('429', 'alle Kollektionen bearbeiten', 'editallcolls'),
	('419', 'eigene Kollektionen bearbeiten', 'editmycolls')");
	echo mysql_error();
	if(mysql_error() !== "")
	{
		$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
		fwrite($fh,date('d.m.Y H:i:s').": Fehler: Tabelle permissions konnte nicht mit weiteren Rechten belegt werden.\n");
		fclose($fh);
		echo "Die Tabelle 'permissions' wurde NICHT aktualisiert.<BR>";
		$error++;
	}
	else
	{
		$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
		fwrite($fh,date('d.m.Y H:i:s').": Tabelle permissions wurde aktualisiert.\n");
		fclose($fh);
		echo "Die Tabelle 'permissions' wurde aktualisiert.<BR>";
	}
	
	

//	4)
	
	

//	5)

	
	

//	6)

	$result10 = mysql_query("UPDATE $table16 SET p2b_version='$version'");
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
		fwrite($fh,date('d.m.Y H:i:s').": Update der Pfade-Tabelle; Version wurde auf 0.90.0 aktualisiert.\n");
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