<?php

echo "<FONT COLOR = 'red'><b><center>HABEN SIE DIE ORDNER /images/histogramme UND /images/monochrome ANGELEGT?!?</center><BR><BR></b></FONT>";
echo "<FONT COLOR = 'red'><b><center>BEACHTEN SIE BITTE DIE HINWEISE AM ENDE DIESER SEITE!!</center><BR><BR></b></FONT>";

set_time_limit(240);		//MaxExecutionTime wird hochgesetzt
ignore_user_abort(true);	//Skript läuft weiter, egal was der User macht...

include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/ajax_functions.php';

// #########################################################################################
//                                                                                         #
//      dieses Skript einmalig bei dem Update auf Version 0.41 laufen lassen!              #
//      Hinweis:                                                                           #
//                                                                                         #
//##########################################################################################


// Ermittlung der Programm-Version anhand der DB-Struktur:
$ta = array();
$result1 = mysql_query("SHOW TABLES FROM $db");
echo mysql_error();
$num1 = mysql_num_rows($result1);
echo "Es wurden die folgenden ".$num1." Tabellen gefunden:<BR>";

FOR($i1=0;$i1<$num1;$i1++)
{
	$table_arr = mysql_fetch_row($result1);	
	echo "Tabelle ".$i1.": ".$table_arr[0]."<BR>";
	$ta[] = $table_arr[0];
}
//echo COUNT($ta);
//wenn 16 Tabellen vorhanden sind und diese die erforderlichen Namen haben, liegt die Struktur für V 0.41 vor:
IF(COUNT($ta) == 16 AND in_array('meta_protect',$ta) AND in_array('ftp_transfer',$ta) AND in_array('geo_tmp',$ta) AND in_array('grouppermissions',$ta) AND in_array('kategorien',$ta) AND in_array('kat_lex',$ta) AND in_array('locations',$ta) AND in_array('meta_data',$ta) AND in_array('permissions',$ta) AND in_array('pictures',$ta) AND in_array('pic_kat',$ta) AND in_array('rights',$ta) AND in_array('tmp_tree',$ta) AND in_array('usergroups',$ta) AND in_array('userpermissions',$ta) AND in_array('users',$ta))
{
	echo "Vohandene DB-Version: 0.41!<BR>
	Es sind keine &Auml;nderungen notwendig.";
	break;
}
ELSE
{
	echo "<BR><FONT COLOR='red'>Vohandene DB-Version ist NICHT 0.41!</FONT><BR><BR>
	Die Datenbank wird modifiziert.";
	
	echo "<BR><u>Schritt 1:</u> Die Datenbank <i>pic2base</i> wird nach <i>pic2base_bkf</i> gesichert.<BR>";
	
	//Quell-Datenbank
	$database_source = $db; 
	//Ziel-Datenbank
	$database_target = 'pic2base_bkf';
	
	//##########################################################################################
	//hier die Zugangsparameter fuer MySQL-User mit root-Rechten eintragen:                    #
	$db_server = 'localhost';                                                                  #
	$db_user = '';                                                                             #
	$pwd = '';                                                                                 #
	//##########################################################################################
	
	$conn_admin = mysql_connect($db_server,$db_user,$pwd);
	//Mit Datenbank-Server verbinden
	if ($conn_admin === false)
	{
		die("<FONT COLOR='red'>Fehler! Keine Datenbank-Verbindung!: </FONT>".mysql_error());
	}
	ELSE
	{
		echo "<FONT COLOR='green'>Datenbankverbindung erfolgreich hergestellt.</FONT><BR>";
		
		
		//dem Benutzer pb Rechte auf die Zieldatenbank erteilen:
		$res116 = mysql($database_target, "GRANT USAGE ON * . * TO 'pb'@'localhost' IDENTIFIED BY 'pic_base' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;");
		$res117 = mysql($database_target, "GRANT SELECT , INSERT , UPDATE , DELETE , ALTER ON `$database_target` . * TO 'pb'@'localhost';"); 
		$res118 = mysql($db, "GRANT ALTER , CREATE ON `$db` . * TO 'pb'@'localhost';"); 
		
		//wenn bereits eine Sicherheitskopie der DB vorliegt, wird dieser Schritt übersprungen:
		$sql1 = "SHOW DATABASES LIKE 'pic2base_bkf'";
		$query1 = mysql_query($sql1, $conn_admin) or die("Fehler 1 ".mysql_error($conn_admin));
		$num_db = mysql_num_rows($query1);
		IF($num_db == '0')
		{
			//Ziel-Datenbank erstellen
			$sql = "CREATE database ".$database_target;
			$query = mysql_query($sql, $conn_admin) or die("Fehler 1 ".mysql_error($conn_admin));
			
			//Tabellen der Quell-Datenbank auslesen
			$sql = "SHOW TABLES FROM ".$database_source;
			$query = mysql_query($sql, $conn_admin) or die("Fehler 2: ".mysql_error($conn_admin));
			$num_tables = mysql_num_rows($query);
			
			FOR($i = 0; $i < $num_tables; $i++)
			{
				//Name der Tabelle
				$table_name = mysql_result($query, $i);			
			
				//Query zur Erstellung der Tabelle auslesen
				$sql = "SHOW CREATE TABLE ".$database_source.".".$table_name;
				$sub_query = mysql_query($sql, $conn_admin) or die("Fehler 3: ".mysql_error($conn_admin));
				$sub_query_data_arr = mysql_fetch_row($sub_query);	
			
				//Query zur Erstellung der neuen Tabelle konstruieren
				$sql = str_replace("`".$table_name."`",$database_target.".".$table_name, $sub_query_data_arr[1]);
			
				//Tabelle in Ziel-Datenbank erstellen
				$sub_query = mysql_query($sql, $conn_admin) or die("Fehler 4: ".mysql_error($conn_admin));
			
				//Daten Einfuegen
				$sql = "INSERT INTO ".$database_target.".".$table_name." SELECT * FROM ".$database_source.".".$table_name;
				$sub_query = mysql_query($sql, $conn_admin) or die("Fehler 5: ".mysql_error($conn_admin));
			}
			//mysql_close($conn_admin);
			echo "Datenbank ".$database_source." wurde nach ".$database_target." gesichert.<BR>";
		}
		ELSE
		{
			echo "Datenbanksicherung war nicht erforderlich.<BR>";
		}
	}
	
	
	//Tabellen tmp_tree, kat_lex, meta_data, meta_protect werden erzeugt; pb_column_info wird gelöscht:
	echo "<BR><u>Schritt 2:</u> Die fehlenden Tabellen werden angelegt.<BR>";
	
	IF(!in_array('tmp_tree', $ta))
	{
		$result2 = mysql($db, "CREATE TABLE IF NOT EXISTS `tmp_tree` (
				`lfdnr` int(11) NOT NULL auto_increment,
				`kat_id` int(11) NOT NULL,
				`old_level` int(11) NOT NULL,
				`kat_name` varchar(75) NOT NULL,
				`user_id` int(11) NOT NULL,
				`new_level` int(11) NOT NULL,
				`new_parent` int(11) NOT NULL,
				PRIMARY KEY  (`lfdnr`)
				) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0;");
		IF(mysql_error() == '')
		{
			echo "<FONT COLOR='green'>Tabelle <i>tmp_tree</i> wurde erfolgreich angelegt.</FONT><BR>";
		}
		ELSE
		{
			echo "<FONT COLOR='red'>FEHLER!<BR>Tabelle <i>tmp_tree</i> wurde NICHT angelegt.</FONT><BR>".mysql_error();
			return;
		}
	}
	
	IF(!in_array('kat_lex', $ta))
	{
		$result3 = mysql($db, "CREATE TABLE IF NOT EXISTS `kat_lex` (
		`lfdnr` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`kat_id` INT NOT NULL ,
		`info` TEXT NOT NULL ,
		UNIQUE (`kat_id`)
		) ENGINE = MYISAM ;");
				
		IF(mysql_error() == '')
		{
			echo "<FONT COLOR='green'>Tabelle <i>kat_lex</i> wurde erfolgreich angelegt.</FONT><BR>";
		}
		ELSE
		{
			echo "<FONT COLOR='red'>FEHLER!<BR>Tabelle <i>kat_lex</i> wurde NICHT angelegt.</FONT><BR>".mysql_error();
			return;
		}
	}
	
	IF(!in_array('meta_protect', $ta))
	{
		$res2 = mysql($db, "CREATE TABLE IF NOT EXISTS `meta_protect` (
		`lfdnr` int(11) NOT NULL auto_increment,
		`field_name` varchar(50) NOT NULL default '0',
		`writable` BOOL NOT NULL DEFAULT '0',
		PRIMARY KEY  (`lfdnr`)
		) ENGINE=MyISAM COMMENT='Sperr-Tabelle fuer META-Daten' AUTO_INCREMENT=0 CHARACTER SET utf8 COLLATE utf8_unicode_ci;");
		
		IF(mysql_error() == '')
		{
			echo "<FONT COLOR='green'>Tabelle <i>meta_protect</i> wurde erfolgreich angelegt.</FONT><BR>";
		}
		ELSE
		{
			echo "<FONT COLOR='red'>FEHLER!<BR>Tabelle <i>meta_protect</i> wurde NICHT angelegt.</FONT><BR>".mysql_error();
			return;
		}
	}
	
	
	echo "<BR><u>Schritt 3:</u> Die Tabelle <i>pb_column_info</i> wird gel&ouml;scht.<BR>";
	
	IF(in_array('pb_column_info', $ta))
	{
		$result5 = mysql($db, "DROP TABLE IF EXISTS pb_column_info");
		
		IF(mysql_error() == "")
		{
			echo "<FONT COLOR='green'>Tabelle <i>pb_column_info</i> wurde gel&ouml;scht.</FONT><BR>";
		}
		ELSE
		{
			echo "<FONT COLOR='red'>FEHLER!<BR>Tabelle <i>pb_column_info</i> wurde NICHT gel&ouml;scht!.</FONT><BR>".mysql_error();
			return;
		}
	}
	
	
	echo "<BR><u>Schritt 4:</u> Die Tabelle <i>pictures</i> wird nach <i>meta_data</i> kopiert, falls noch keine existiert.<BR>";
	
	IF(!in_array('meta_data', $ta))
	{
		$result6 = mysql($db, "CREATE TABLE IF NOT EXISTS `meta_data` (
		`pic_id` int(11) NOT NULL,
		`FileNameOri` varchar(75) NOT NULL COMMENT 'Original-Dateiname',
		`FileName` varchar(25) NOT NULL COMMENT 'interner Dateiname',
		`FileNameHQ` varchar(25) NOT NULL COMMENT 'Name des HQ-Vorschau-Bildes',
		`FileNameV` varchar(25) NOT NULL default '',
		`Owner` varchar(50) NOT NULL COMMENT 'user_id',
		`DateInsert` datetime NOT NULL default '0000-00-00 00:00:00',
		`DateChange` datetime NOT NULL default '0000-00-00 00:00:00',
		`FileDateTime` varchar(11) default NULL,
		`FileSize` int(11) NOT NULL default '0',
		`FileType` int(11) NOT NULL default '0',
		`MimeType` varchar(15) NOT NULL default '',
		`Height` int(11) NOT NULL default '0',
		`Width` int(11) NOT NULL default '0',
		`IsColor` char(1) NOT NULL default '0',
		`ByteOrderMotorola` char(1) NOT NULL default '',
		`ApertureFNumber` varchar(10) NOT NULL default '',
		`FNumber` varchar(10) NOT NULL default '',
		`UserComment` text NOT NULL,
		`Make` varchar(50) NOT NULL default '',
		`Model` varchar(50) NOT NULL default '',
		`Orientation` int(11) NOT NULL default '0',
		`XResolution` varchar(10) NOT NULL default '',
		`YResolution` varchar(10) NOT NULL default '',
		`ResolutionUnit` int(11) NOT NULL default '0',
		`Software` varchar(50) NOT NULL,
		`DateTime` datetime NOT NULL default '0000-00-00 00:00:00',
		`YCbCrPositioning` int(11) NOT NULL default '0',
		`Exif_IFD_Pointer` int(11) NOT NULL default '0',
		`ExposureTime` varchar(10) NOT NULL default '',
		`ExposureProgram` int(11) NOT NULL default '0',
		`ExifVersion` varchar(10) NOT NULL default '',
		`DateTimeOriginal` datetime NOT NULL default '0000-00-00 00:00:00',
		`DateTimeDigitized` datetime NOT NULL default '0000-00-00 00:00:00',
		`ComponentsConfiguration` varchar(5) NOT NULL default '',
		`CompressedBitsPerPixel` varchar(10) NOT NULL default '',
		`ExposureBiasValue` varchar(5) NOT NULL default '',
		`MaxApertureValue` varchar(10) NOT NULL default '',
		`MeteringMode` char(2) NOT NULL default '',
		`LightSource` int(11) NOT NULL default '0',
		`Flash` int(1) default '0',
		`FocalLength` varchar(10) NOT NULL default '',
		`FileSource` char(2) NOT NULL default '',
		`WhitePoint` varchar(10) NOT NULL default '',
		`Gamma` varchar(10) NOT NULL default '',
		`Copyright` varchar(250) NOT NULL default '',
		`IsoSpeedRatings` varchar(25) NOT NULL default '',
		`WhiteBalance` varchar(25) NOT NULL default '',
		`FocalLengthIn35mmFilm` int(11) NOT NULL default '0',
		`DigitalZoomRatio` varchar(10) NOT NULL default '',
		`GPSLatitude` varchar(20) NOT NULL default '',
		`GPSLongitude` varchar(20) NOT NULL default '',
		`GPSAltitude` varchar(20) NOT NULL default '',
		`GPSTimeStamp` time NOT NULL default '00:00:00',
		`Description` text NOT NULL,
		`loc_id` int(11) NOT NULL default '0' COMMENT 'location_id fuer geo-Referenzierung',
		`ranking` int(11) NOT NULL default '0',
		`note` int(11) NOT NULL default '5' COMMENT 'Bewertung (Note 1 - 5)',
		`md5sum` varchar(50) NOT NULL,
		KEY `pic_id` (`pic_id`),
		KEY `FileName` (`FileName`),
		KEY `FileNameHQ` (`FileNameHQ`),
		KEY `FileNameV` (`FileNameV`),
		KEY `DateInsert` (`DateInsert`),
		KEY `DateChange` (`DateChange`),
		KEY `FileSize` (`FileSize`),
		KEY `Height` (`Height`),
		KEY `Width` (`Width`),
		KEY `Owner` (`Owner`),
		KEY `DateTimeOriginal` (`DateTimeOriginal`),
		KEY `ranking` (`ranking`),
		KEY `note` (`note`),
		KEY `md5sum` (`md5sum`)
		) ENGINE=MyISAM AUTO_INCREMENT=0 CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT='Bilddaten';");
		IF(mysql_error() == "")
		{
			echo "<FONT COLOR='green'>Tabelle <i>meta_data</i> wurde erfolgreich erzeugt.</FONT><BR>";
		}
		ELSE
		{
			echo "<FONT COLOR='red'>FEHLER!<BR>Tabelle <i>meta_data</i> wurde NICHT erzeugt!</FONT><BR>".mysql_error();
			return;
		}
		
		$result7 = mysql($db, "INSERT INTO `meta_data` SELECT * FROM `pictures`");
				
		IF(mysql_error() == "")
		{
			echo "<FONT COLOR='green'>Inhalt der Tabelle <i>pictures</i> wurde erfolgreich nach <i>meta_data</i> kopiert.</FONT><BR>";
		}
		ELSE
		{
			echo "<FONT COLOR='red'>FEHLER!<BR>Tabelle <i>pictures</i> wurde NICHT kopiert!</FONT><BR>".mysql_error();
			return;
		}
		
		$result8 = mysql($db, "ALTER TABLE meta_data ADD City varchar(50) NOT NULL, ADD ed_id int(11) NOT NULL auto_increment FIRST, ADD INDEX (ed_id), ADD ImageHeight int(11) NOT NULL, ADD ImageWidth int(11) NOT NULL, ADD Quality varchar(13), DROP FileNameOri, DROP FileName, DROP FileNameHQ, DROP FileNameV, DROP Owner, DROP DateInsert, DROP loc_id, DROP ranking, DROP note, DROP md5sum, CHANGE Description Caption_Abstract text NOT NULL, CHANGE Height ExifImageHeight int(11) NOT NULL, CHANGE Width ExifImageWidth int(11) NOT NULL");
		
		IF(mysql_error() == "")
		{
			echo "<FONT COLOR='green'>Tabelle <i>meta_data</i> wurde erfolgreich modifiziert.</FONT><BR>";
		}
		ELSE
		{
			echo "<FONT COLOR='red'>FEHLER!<BR>Tabelle <i>meta_data</i> wurde NICHT modifiziert!</FONT><BR>".mysql_error();
			return;
		}
		
		$result10 = mysql($db, "ALTER TABLE pictures ADD has_kat tinyint(1) NOT NULL default '0' COMMENT 'Neuzugang = 0, sonst 1', ADD FileNameHist varchar(17) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'Dateiname des Farb-Histogramms', ADD FileNameHist_r varchar(17) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'Dateiname des Rot-Kanal-Histogramms', ADD FileNameHist_g varchar(17) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL, ADD FileNameHist_b varchar(17) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL, ADD FileNameMono varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'Dateiname des Monochrom-Bildes'");
	
		IF(mysql_error() == "")
		{
			echo "<FONT COLOR='green'>Tabelle <i>pictures</i> um weitere Feld erweitert.</FONT><BR>";
		}
		ELSE
		{
			echo "<FONT COLOR='red'>FEHLER!<BR>Tabelle <i>pictures</i> wurde NICHT um erforderliche Felder  erweitert!</FONT><BR>".mysql_error();
			return;
		}
		
		
	}
	ELSE
	{
		echo "Erzeugung der meta_data-Tabelle war nicht erforderlich";
	}
	
	echo "<BR><u>Schritt 5:</u> Kategorie-IDs werden von der Tabelle kategorie nach kat_lex &uuml;bretragen:<BR>";
	
	$result11 = mysql($db, "SELECT kat_id FROM $table4 ORDER BY 'kat_id'");
	$num11 = mysql_num_rows($result11);
	FOR($i11='0'; $i11<$num11; $i11++)
	{
		$kat_id = mysql_result($result11, $i11, 'kat_id');
		$result12 = mysql($db, "INSERT INTO kat_lex (kat_id, info) VALUES ('$kat_id', '')");
		//echo "Kategorie ".$kat_id." wurde &uuml;bertragen<BR>";
	}
	
	IF(mysql_error() == "")
	{
		echo "<FONT COLOR='green'>&Uuml;bernahme der KAT-IDs erfolgreich beendet.</FONT><BR>";
	}
	ELSE
	{
		echo "<FONT COLOR='red'>FEHLER!<BR>&Uuml;bernahme der KAT-IDs NICHT erfolgreich beendet.!</FONT><BR>".mysql_error();
		return;
	}
	
	echo "<BR><u>Schritt 6:</u> Meta-Datenfelder werden von der Tabelle meta_data nach meta_protect.field_name &uuml;bretragen:<BR>";
	
	$result13 = mysql($db, "SHOW COLUMNS FROM $table14");
	$struktur = array();
	$i = 0;
	if($result13 != false)
	{
		while($liste = mysql_fetch_row($result13))
		{
			$tab_fieldname[$i] = $liste[0];	//vorh. Tabellen-Feldname
			$tab_fieldtype[$i] = $liste[1];	//vorh. Tabellen-Feldtyp
			$i++;
		}
	}
	else die('Fehler bei der Datenbankabfrage');
	
	$c = count($tab_fieldtype);
	FOR($j0='0'; $j0<$c; $j0++)
	{
		//echo $tab_fieldname[$j0].", ".$tab_fieldtype[$j0]."<BR>";
		$tfn = $tab_fieldname[$j0];
		$result14 = mysql($db, "INSERT INTO $table5 (field_name, writable) VALUES ('$tfn', '0')");
	}
	echo mysql_error();
	
	echo "<BR><u>Schritt 7:</u> Aus der Tabelle <i>pictures</i> werden nicht ben&ouml;tigte Felder entfernt.<BR>";
	
	$result17 = mysql($db, "ALTER TABLE pictures DROP DateChange, DROP FileDateTime, DROP FileSize, DROP MimeType, DROP Height, DROP Width, DROP IsColor, DROP ByteOrderMotorola, DROP ApertureFNumber, DROP FNumber, DROP UserComment, DROP Make, DROP Model, DROP Orientation, DROP XResolution, DROP YResolution, DROP ResolutionUnit, DROP Software, DROP DateTime, DROP YCbCrPositioning, DROP Exif_IFD_Pointer, DROP ExposureTime, DROP ExifVersion, DROP DateTimeOriginal, DROP DateTimeDigitized, DROP ComponentsConfiguration, DROP CompressedBitsPerPixel, DROP ExposureBiasValue, DROP MaxApertureValue, DROP MeteringMode, DROP LightSource, DROP Flash, DROP FocalLength, DROP FileSource, DROP WhitePoint, DROP Gamma, DROP CopyRight, DROP IsoSpeedRatings, DROP WhiteBalance, DROP FocalLengthIn35mmFilm, DROP DigitalZoomRatio, DROP GPSLatitude, DROP GPSLongitude, DROP GPSAltitude, DROP GPSTimeStamp, DROP Description, DROP FileType, DROP ExposureProgram");
	
	IF(mysql_error() == "")
	{
		echo "<FONT COLOR='green'>Die Tabelle <i>pictures</i> wurde erfolgreich modifiziert.</FONT><BR>";
	}
	ELSE
	{
		echo "<FONT COLOR='red'>FEHLER!<BR>Tabelle <i>pictures</i> wurde NICHT modifiziert!</FONT><BR>".mysql_error();
		return;
	}
	
	
	echo "<BR><u>Schritt 8:</u> monochrome Bilder werden erzeugt;";
	
	$result18 = mysql($db, "SELECT * FROM $table2");
	$num18 = mysql_num_rows($result18);
	FOR($i18='0'; $i18<$num18; $i18++)
	{
		$pic_id = mysql_result($result18, $i18, 'pic_id');
		$FNHQ = mysql_result($result18, $i18, 'FileNameHQ');
						
		//Wenn das monochrome Bild nicht vorhanden ist, wird es erstellt:
		$file_mono = $monochrome_path."/".$pic_id."_mono.jpg";
		
		IF(@!fopen($file_mono, 'r'))
		{
			$file = $pic_hq_preview."/".$FNHQ;
			shell_exec($im_path."/convert ".$file." -colorspace Gray -quality 80% ".$monochrome_path."/".$pic_id."_mono.jpg");
			$mono_file = $pic_id."_mono.jpg";
			$result19 = mysql($db, "UPDATE $table2 SET FileNameMono = '$mono_file' WHERE pic_id = '$pic_id'");
			IF(mysql_error() !== '')
			{
				echo "Fehler beim speichern des monochrome-Bildverweises: ".mysql_error()."<BR>~~~~~~~~~~~~~~~~~~~~~~<BR>";
			}
			ELSE
			{
				//echo $i18."<BR>";
			}
		}
	}
	
	
	echo "<BR><u>Schritt 9:</u> Bilder mit Kategoriezuweisung werden gekennzeichnet (has_kat=1);";
	
	$result19 = mysql($db, "SELECT DISTINCT pic_id FROM $table10");
	$num19 = mysql_num_rows($result19);

	FOR ($i19=1; $i19<$num19; $i19++)	//Als Start wurde "1" gewaehlt, da die Wurzel uninteressant ist!
	{
		$pic_id = mysql_result($result19, $i19, 'pic_id');
		$result20 = mysql($db, "UPDATE $table2 SET has_kat = '1' WHERE pic_id = '$pic_id'");
	}
	
	IF(mysql_error() == "")
	{
		echo "<FONT COLOR='green'>".$num19." Bilder wurden in der Tabelle <i>pictures</i> mit \"has_kat=1\" gekennzeichnet.</FONT><BR>";
	}
	ELSE
	{
		echo "<FONT COLOR='red'>FEHLER!<BR>Tabelle <i>pictures</i> wurde NICHT mit has_kat-Daten best&uuml;ckt!</FONT><BR>".mysql_error();
		return;
	}
	

}

echo "<HR><BR><BR>Die Datenbamk-Modifikation ist abgeschlossen.<BR><BR>Wenn die Datenbank fehlerfrei l&auml;uft, k&ouml;nnen die Tabellen pictures_bkf und exif_protect_bkf z.B. mittels phpmyadmin gel&ouml;scht werden.<HR>";


?>