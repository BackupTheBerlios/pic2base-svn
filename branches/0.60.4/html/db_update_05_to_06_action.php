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
 * File: db_update_05_to_06_action.php
 * 
 * Umstrukturierung der Datenbank
 *
 * Copyright (c) 2011 Klaus Henneberg
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
// Tabelle meta_data wird in meta_data_0 umbenannt
// Tabelle locations wird in locations_0 umbenannt
// Tabelle pictures wird nach Tabelle pictures_0 kopiert (Sicherheitskopie)
// Tabelle pictures wird um die Felder der Tabellen locations und meta_data ergaenzt
// Die Inhalte der Tabellen locations und meta_data werden in die entsprechenden Felder der Tabelle pictures geschrieben
// alle in der Tabelle pictures nicht mehr verwendeten Felder erhalten den Zusatz _0
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
	<!--<img src='' style='float:right;width:156; height:39;margin-left:3px;' alt='Logo'>-->
	<p id='kopf'>pic2base - Datenbank-Umstrukturierung auf Version 0.60</p>
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
// Zur Performance-Optimierung wird ab Version 0.60 eine neue DB-Struktur verwendet
// In dieser befindet sich der Inhalt der bisherigen Tabellen pictures, meta_data und locations zusammen
// in der Tabelle pictures ($table2)
// Die Tabellen pictures ($table2), meta_data ($table14) und locations ($table12) werden zur Sicherheit als
// pictures_0, meta_data_0 und locations_0 erhalten.
//Der Tabelle users wird das Feld language zugewiesen
//=========================================================================================================
	$error = 0;		//Zaehlvariable fuer Fehlermeldungen
	$res = mysql_query("SHOW columns FROM $table14");
	IF(@mysql_num_rows($res))
	{
		// Wenn es die Tabelle meta_data noch gibt, wird umstruturiert:
		// 1) Umbenennung der Tabelle meta_data nach meta_data_0 und locations nach locations_0, 
		echo "Alte DB-Struktur wird in Version 0.60 &uuml;berf&uuml;hrt...<BR><BR>";
		
		$res1 = mysql_query("RENAME TABLE `$table14` TO `meta_data_0`");
		IF(mysql_error() <> '')
		{
			echo "Fehler bei der Umbenennung der Tabelle meta_data<BR>";
			$error++;
		}
		ELSE
		{
			//echo "Tabelle meta_data wurde in meta_data_0 umbenannt.<BR>";
		}
		
		$res2 = mysql_query("RENAME TABLE `$table12` TO `locations_0`");
		IF(mysql_error() <> '')
		{
			echo "Fehler bei der Umbenennung der Tabelle locations<BR>";
			$error++;
		}
		ELSE
		{
			//echo "Tabelle locations wurde in locations_0 umbenannt.<BR>";
		}
	
		// 2) Sicherung (Kopie) der Tabelle pictures nach pictures_0:
		$res3 = mysql_query("CREATE TABLE pictures_0 LIKE $table2");
		IF(mysql_error() <> '')
		{
			echo "Fehler bei der Anlage der Tabelle pictures_0<BR>";
			$error++;
		}
		ELSE
		{
			//echo "Tabelle pictures wurde in pictures_0 umbenannt.<BR>";
		}
		
		$res3 = mysql_query("INSERT INTO pictures_0 SELECT * FROM $table2");
		IF(mysql_error() <> '')
		{
			echo "Fehler bei der Daten&uuml;bernahme in die Tabelle pictures_0<BR>";
			$error++;
		}
		ELSE
		{
			//echo "Die Daten der Tabelle pictures wurden in die Tabelle pictures_0 &uuml;bernommen.<BR>";
		}
		
		// 3) Tabelle pictures um die Felder der Tabellen locations und meta_data ergaenzen:
		$res4 = mysql_query("ALTER TABLE `$table2` ADD 
		(`Make` varchar(37) default NULL,
		`Model` varchar(50) NOT NULL,
		`CameraModelName` varchar(50) default NULL,
		`Orientation` tinyint(4) NOT NULL,
		`XResolution` varchar(13) default NULL,
		`YResolution` varchar(13) default NULL,
		`ExposureTime` varchar(13) default NULL,
		`FNumber` decimal(3,1) default NULL,
		`ExposureProgram` int(11) NOT NULL,
		`DateTimeOriginal` datetime default '0000-00-00 00:00:00',
		`MaxApertureValue` varchar(13) default NULL,
		`MeteringMode` varchar(25) NOT NULL,
		`LightSource` varchar(17) default NULL,
		`Flash` varchar(25) NOT NULL,
		`FocalLength` varchar(13) default NULL,
		`ISO` smallint(11) NOT NULL,
		`Quality` varchar(13) default NULL,
		`WhiteBalance` varchar(18) default NULL,
		`Sharpness` varchar(12) default NULL,
		`FocusMode` varchar(12) default NULL,
		`ISOSetting` smallint(11) NOT NULL,
		`Lens` varchar(37) default NULL,
		`ShootingMode` int(11) NOT NULL,
		`NoiseReduction` varchar(10) default NULL,
		`SensorPixelSize` varchar(21) default NULL,
		`SerialNumber` varchar(26) default NULL,
		`FileSize` bigint(11) NOT NULL,
		`ShutterCount` int(11) NOT NULL,
		`UserComment` varchar(130) default NULL,
		`ColorSpace` varchar(25) NOT NULL,
		`ExifImageWidth` int(11) NOT NULL,
		`ExifImageHeight` int(11) NOT NULL,
		`FocalLengthIn35mmFormat` int(11) NOT NULL,
		`ImageDescription` varchar(69) default NULL,
		`ColorMode` varchar(11) default NULL,
		`ImageWidth` int(11) NOT NULL,
		`ImageHeight` int(11) NOT NULL,
		`Copyright` varchar(250) default NULL,
		`ShutterSpeedValue` varchar(13) default NULL,
		`ApertureValue` varchar(13) default NULL,
		`AFPointPosition` varchar(13) default NULL,
		`SelfTimer` int(11) NOT NULL,
		`ImageStabilization` int(11) NOT NULL,
		`Keywords` varchar(250) NOT NULL,
		`ActiveD_Lighting` int(11) NOT NULL,
		`HighISONoiseReduction` int(11) NOT NULL,
		`GPSVersionID` int(11) NOT NULL,
		`GPSAltitude` double default NULL,
		`GPSLatitude` double default NULL,
		`GPSLongitude` double default NULL,
		`City` varchar(50) NOT NULL default 'Ortsbezeichnung',
		`Caption_Abstract` text NOT NULL,
		`GPSLatitudeRef` varchar(7) default NULL,
		`GPSLongitudeRef` varchar(7) default NULL,
		`GPSAltitudeRef` int(11) NOT NULL)");
		IF(mysql_error() <> '')
		{
			echo mysql_error()."<BR><BR>";
			echo "Fehler bei der Felderstellung in der Tabelle pictures<BR>";
			$error++;
		}
		ELSE
		{
			//echo "Tabelle pictures wurde erfolgreich modifiziert.<BR>";
		}
		
		// 4) Die Inhalte der Tabellen locations und meta_data uebernehmen:
		$res5 = mysql_query("UPDATE $table2 
		SET $table2.Make = (SELECT Make FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.Model = (SELECT Model FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.CameraModelName = (SELECT CameraModelName FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.Orientation = (SELECT Orientation FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.XResolution = (SELECT XResolution FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.YResolution = (SELECT YResolution FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.ExposureTime = (SELECT ExposureTime FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.FNumber = (SELECT FNumber FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.ExposureProgram = (SELECT ExposureProgram FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.DateTimeOriginal = (SELECT DateTimeOriginal FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.MaxApertureValue = (SELECT MaxApertureValue FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.MeteringMode = (SELECT MeteringMode FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.LightSource = (SELECT LightSource FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.Flash = (SELECT Flash FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.FocalLength = (SELECT FocalLength FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.ISO = (SELECT ISO FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.Quality = (SELECT Quality FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.WhiteBalance = (SELECT WhiteBalance FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.Sharpness = (SELECT Sharpness FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.FocusMode = (SELECT FocusMode FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.ISOSetting = (SELECT ISOSetting FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.Lens = (SELECT Lens FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.ShootingMode = (SELECT ShootingMode FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.NoiseReduction = (SELECT NoiseReduction FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.SensorPixelSize = (SELECT SensorPixelSize FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.SerialNumber = (SELECT SerialNumber FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.FileSize = (SELECT FileSize FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.ShutterCount = (SELECT ShutterCount FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.UserComment = (SELECT UserComment FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.ColorSpace = (SELECT ColorSpace FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.ExifImageWidth = (SELECT ExifImageWidth FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.ExifImageHeight = (SELECT ExifImageHeight FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.FocalLengthIn35mmFormat = (SELECT FocalLengthIn35mmFormat FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.ImageDescription = (SELECT ImageDescription FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.ColorMode = (SELECT ColorMode FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.ImageWidth = (SELECT ImageWidth FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.ImageHeight = (SELECT ImageHeight FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.Copyright = (SELECT Copyright FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.ShutterSpeedValue = (SELECT ShutterSpeedValue FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.ApertureValue = (SELECT ApertureValue FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.AFPointPosition = (SELECT AFPointPosition FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.SelfTimer = (SELECT SelfTimer FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.ImageStabilization = (SELECT ImageStabilization FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.Keywords = (SELECT Keywords FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.ActiveD_Lighting = (SELECT ActiveD_Lighting FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.HighISONoiseReduction = (SELECT HighISONoiseReduction FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.GPSVersionID = (SELECT GPSVersionID FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.GPSAltitude = (SELECT altitude FROM locations_0 WHERE $table2.loc_id = loc_id),
		$table2.GPSLatitude = (SELECT latitude FROM locations_0 WHERE $table2.loc_id = loc_id),
		$table2.GPSLongitude = (SELECT longitude FROM locations_0 WHERE $table2.loc_id = loc_id),
		$table2.City = (SELECT location FROM locations_0 WHERE $table2.loc_id = loc_id),
		$table2.Caption_Abstract = (SELECT Caption_Abstract FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.GPSLatitudeRef = (SELECT GPSLatitudeRef FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.GPSLongitudeRef = (SELECT GPSLongitudeRef FROM meta_data_0 WHERE $table2.pic_id = pic_id),
		$table2.GPSAltitudeRef = (SELECT GPSAltitudeRef FROM meta_data_0 WHERE $table2.pic_id = pic_id)");
		
		IF(mysql_error() <> '')
		{
			echo mysql_error()."<BR><BR>";
			echo "Fehler bei der Daten&uuml;bernahme von meta_data nach pictures<BR>";
			$error++;
		}
		ELSE
		{
			//echo "Die Daten der Tabelle meta_data wurden in die Tabelle pictures &uuml;bernommen.<BR>";
		}
		
		//alle in der Tabelle pictures nicht mehr verwendeten Felder erhalten den Zusatz _0:
		$res6 = mysql_query("ALTER TABLE `$table2` CHANGE `loc_id` `loc_id_0` INT( 11 ) NOT NULL DEFAULT '0' COMMENT 'location_id fuer geo-Referenzierung'");
		
		IF(mysql_error() <> '')
		{
			echo mysql_error()."<BR><BR>";
			echo "Fehler bei der Umbenennung nicht mehr ben&ouml;tigter Felder in der Tabelle pictures.<BR><BR>";
			$error++;
		}
		ELSE
		{
			//echo "Nicht ben&ouml;tigte Felder der Tabelle pictures wurden umbenannt.<BR><BR><BR>";
		}
		
		//Die Tabelle users wird um das Feld language ergaenzt::
		$res6 = mysql_query("ALTER TABLE `$table1` ADD `language` VARCHAR( 25 ) NOT NULL COMMENT 'Sprache des Users'");
		
		IF(mysql_error() <> '')
		{
			echo mysql_error()."<BR><BR>";
			echo "Fehler beim Hinzufuegen des Feldes language in der Tabelle Users.<BR><BR>";
			$error++;
		}
		ELSE
		{
			//echo "Feld language wurde der Tabelle users hinzugefuegt.<BR><BR><BR>";
		}
		
		IF($error !== 0)
		{
			echo "<font color='red'>Es traten Probleme bei der Datenbank-Umstrukturierung auf.<BR><BR>
			Bitte notieren Sie die oben gezeigten Meldungen<BR>und wenden Sie sich an Ihren Administrator.</font><BR><BR>";
		}
		ELSE
		{
			echo "<b><font color='green'>Die Datenbank-Umstrukturierung wurde erfolgreich beendet.<BR><BR>
			Sie k&ouml;nnen nun zur Startseite zur&uuml;ckkehren.</font></b><BR><BR>
			Hinweis:<BR>
			Wenn Sie die einwandfreie Funktion Ihrer pic2base-Installation nachgewiesen haben,<BR>
			k&ouml;nnen sie die Tabellen<BR><BR>meta_data_0, locations_0 und pictures_0<BR><BR>
			aus der Datenbank entfernen.";
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