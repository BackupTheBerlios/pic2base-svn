<?php
//Beim normalen Start wird versucht mit den Standard-Parametern eine Verbindung zur Datenbank herzustellen:
//echo "User: ".$user."<BR>PWD: ".$PWD."<BR>";
//var_dump($_REQUEST);
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

//var_dump($_REQUEST);
//register_globels =  off - Variante:
if( isset($_POST['user']) )
{
//    echo "Jetzt im isset()-Zweig. User: ".$_POST['user'];
	if( !empty($_POST['user']) )
        {
	    $user=$_POST['user'];
	    $PWD=$_POST['PWD'];
	}
}
else
{
	$user = '';
	$PWD = '';
}
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

IF($user == "")
{
	$db_server='localhost';
	$user='pb';
	$PWD='pic_base';
	$db = 'pic2base';
	//mit den Parametern wird versucht eine Datenbank-Verbindung aufzubauen:
	@$conn = mysql_connect($db_server,$user,$PWD);

	@$database = mysql_connect($db_server,$user,$PWD);
	//echo mysql_error();
	if (mysql_error() !=='') 
	{
		//die erforderliche Datenbank gibt es anscheinend noch nicht!
		$text = "<center><font color='green'><b>Willkommen bei pic2base!</b><BR> Noch konnte keine Verbindung zum Datenbank-Server ".$db_server." hergestellt werden.<BR>Das kann daran liegen, da&szlig; Sie pic2base zum ersten Mal starten.<BR><BR>
		Fuer die Erst-Eintichtung der pic2base-Datenbank<BR>tragen Sie hier bitte den Benutzernamen und das Passwort<BR>eines <b>vorhandenen</b> MySQL-Benutzers mit <b>Administrator-Rechten</b> ein:
		<FORM name = 'db_access' method='post' action='index.php'><BR>
		User-Name (Admin): <INPUT type='text' name='user'>&#160;&#160;&#160;Passwort: <input type='password' name='PWD'>&#160;&#160;<INPUT type='submit' value='OK'></font></center>
		</FORM>";
		return;
	}
	ELSE
	{
		//Wenn die Verbindung auf den DB-Server erfolgreich hergestellt werden konnte, wird versucht auf die Datenbank zuzugreifen:
		if (!mysql_select_db($db)) 
		{
			$text = "Verbindung zum Datenbank-Server steht.<BR>
			ABER:<BR>
			<u>F E H L E R!</u> Konnte die Verbindung zur Datenbank $db nicht herstellen!<br>\n";
			return;
		}
		ELSE
		{
			//echo "Datenbank ".$db." ausgewaehlt<BR>";
			$text = '';
		}
		
	}
}
ELSE
{
	$db_server='localhost';
	$db = 'pic2base';
echo "<br>++++ #### ++++<br>";
	//mit den neuen Parametern wird versucht eine Datenbank-Verbindung aufzubauen:
	@$conn = mysql_connect($db_server,$user,$PWD);
	@$database = mysql_pconnect($db_server,$user,$PWD);
	mysql_set_charset('utf8', $conn);
	
//	mysql_error();
	if (!$database) 
	{
		//die erforderliche Datenbank gibt es anscheinend noch nicht!
		$text = "F E H L E R! Es konnte keine Verbindung zum DB-Server $db_server aufgebaut werden!<br>\n";
		$text .= "Es gibt ein Problem mit Ihrer DMS-Konfiguration. Bitte stellen Sie sicher, dass der DB-Server l&auml;uft und die Zugangsdaten f&uuml;r den Administrator-Zugriff korrekt eingetragen wurden.";
		$text .= "<BR><BR><INPUT type='button' name='back' value='Zur&uuml;ck' onclick='javascript:history.back()'>";
		return;
	}
	ELSE
	{
		$err_count = 0;
		//Wenn die Verbindung auf den DB-Server erfolgreich hergestellt werden konnte, wird versucht auf die Datenbank zuzugreifen:
		if (!mysql_select_db($db)) 
		{
			$res1 = mysql_query("CREATE DATABASE IF NOT EXISTS pic2base CHARACTER SET utf8 COLLATE utf8_unicode_ci",$conn);
			
			$text =  "pic2base-Datenbank wurde angelegt.<BR>Lege nun die Tabellen an...<BR>";
			mysql_close($conn);
			$conn_neu = mysql_connect('localhost', $user, $PWD);
			mysql_select_db($db);
			mysql_set_charset('utf8', $conn_neu);
			$res15 = mysql_query( "CREATE TABLE IF NOT EXISTS `ftp_transfer` (
			`username` tinytext collate latin1_german1_ci,
			`filename` text collate latin1_german1_ci,
			`size` bigint(20) default NULL,
			`host` tinytext collate latin1_german1_ci,
			`ip` tinytext collate latin1_german1_ci,
			`aktion` tinytext collate latin1_german1_ci,
			`dauer` tinytext collate latin1_german1_ci,
			`lokale_zeit` datetime default NULL
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Anlage der Tabelle \"ftp_transfer\"<BR>";
				$err_count++;
			}
			ELSE
			{
				echo "Tabelle \"ftp_transfer\" wurde angelegt.<BR>";
			}			
			
			$res3 = mysql_query( "CREATE TABLE IF NOT EXISTS `geo_tmp` (
			`loc_id` int(11) NOT NULL auto_increment COMMENT 'location-ID',
			`longitude` varchar(25) NOT NULL COMMENT 'geo-Laenge',
			`latitude` varchar(25) NOT NULL COMMENT 'geo-Breite',
			`altitude` decimal(6,1) NOT NULL COMMENT 'Hoehe',
			`date` date NOT NULL default '0000-00-00',
			`time` time NOT NULL default '00:00:00',
			`user_id` int(11) NOT NULL,
			PRIMARY KEY  (`loc_id`)
			) ENGINE=MyISAM AUTO_INCREMENT=0 CHARACTER SET utf8 COLLATE utf8_unicode_ci;");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Anlage der Tabelle \"geo_tmp\"<BR>";
				$err_count++;
			}
			ELSE
			{
				echo "Tabelle \"geo_tmp\" wurde angelegt.<BR>";
			}		
			
			
			$res4 = mysql_query( "CREATE TABLE IF NOT EXISTS `grouppermissions` (
			`id` int(11) NOT NULL auto_increment,
			`group_id` int(11) NOT NULL default '0',
			`permission_id` int(11) NOT NULL default '0',
			`enabled` smallint(1) NOT NULL default '0',
			PRIMARY KEY  (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 CHARACTER SET utf8 COLLATE utf8_unicode_ci;");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Anlage der Tabelle \"grouppermissions\"<BR>";
				$err_count++;
			}
			ELSE
			{
				echo "Tabelle \"grouppermissions\" wurde angelegt.<BR>";
			}
			$res4_1 = mysql_query( "INSERT INTO `grouppermissions` (`id`, `group_id`, `permission_id`, `enabled`) VALUES 
			(1, 1, 999, 1),
			(2, 1, 929, 1),
			(3, 1, 799, 1),
			(4, 1, 519, 1),
			(5, 1, 219, 1),
			(6, 1, 100, 1),
			(7, 1, 629, 1),
			(8, 1, 989, 1),
			(9, 1, 859, 1),
			(10, 1, 619, 1),
			(11, 1, 529, 1),
			(12, 1, 229, 1),
			(13, 1, 899, 1),
			(14, 1, 869, 1),
			(15, 1, 849, 1),
			(16, 1, 539, 1),
			(17, 1, 549, 1),
			(18, 1, 639, 1),
			(19, 1, 649, 1),
			(20, 2, 999, 0),
			(21, 2, 929, 0),
			(22, 2, 799, 0),
			(23, 2, 519, 0),
			(24, 2, 219, 0),
			(25, 2, 100, 0),
			(26, 2, 629, 0),
			(27, 2, 989, 0),
			(28, 2, 859, 0),
			(29, 2, 619, 0),
			(30, 2, 529, 0),
			(31, 2, 229, 0),
			(32, 2, 899, 0),
			(33, 2, 869, 0),
			(34, 2, 849, 0),
			(35, 2, 539, 0),
			(36, 2, 549, 0),
			(37, 2, 639, 0),
			(38, 2, 649, 0),
			(39, 3, 999, 0),
			(40, 3, 929, 0),
			(41, 3, 799, 0),
			(42, 3, 519, 0),
			(43, 3, 219, 0),
			(44, 3, 100, 0),
			(45, 3, 629, 0),
			(46, 3, 989, 0),
			(47, 3, 859, 0),
			(48, 3, 619, 0),
			(49, 3, 529, 0),
			(50, 3, 229, 0),
			(51, 3, 899, 0),
			(52, 3, 869, 0),
			(53, 3, 849, 0),
			(54, 3, 539, 0),
			(55, 3, 549, 0),
			(56, 3, 639, 0),
			(57, 3, 649, 0),
			(58, 4, 999, 0),
			(59, 4, 929, 0),
			(60, 4, 799, 0),
			(61, 4, 519, 0),
			(62, 4, 219, 0),
			(63, 4, 100, 0),
			(64, 4, 629, 0),
			(65, 4, 989, 0),
			(66, 4, 859, 0),
			(67, 4, 619, 0),
			(68, 4, 529, 0),
			(69, 4, 229, 0),
			(70, 4, 899, 0),
			(71, 4, 869, 0),
			(72, 4, 849, 0),
			(73, 4, 539, 0),
			(74, 4, 549, 0),
			(75, 4, 639, 0),
			(76, 4, 649, 0),
			(77, 1, 889, 1);");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Bef&uuml;llung der Tabelle \"grouppermissions\"<BR>";
				$err_count++;
			}
			ELSE
			{
				echo "Tabelle \"grouppermissions\" wurde mit Daten vorbelegt.<BR>";
			}
			
			
			$res5 = mysql_query( "CREATE TABLE IF NOT EXISTS `kategorien` (
			`kat_id` int(11) NOT NULL auto_increment,
			`kategorie` varchar(50) NOT NULL default '',
			`parent` int(11) NOT NULL default '0',
			`level` int(11) NOT NULL default '0',
			KEY `kat_id` (`kat_id`),
			KEY `kategorie` (`kategorie`),
			KEY `parent` (`parent`),
			KEY `level` (`level`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 CHARACTER SET utf8 COLLATE utf8_unicode_ci;");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Anlage der Tabelle \"kategorien\"<BR>";
				$err_count++;
			}
			ELSE
			{
				echo "Tabelle \"kategorien\" wurde angelegt.<BR>";
			}
			
			$res5_1 = mysql_query( "INSERT INTO `kategorien` (`kategorie`, `parent`, `level`) VALUES ('Neuzugaenge','0','0');");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Bef&uuml;llung der Tabelle \"kategorien\"<BR>";
				$err_count++;
			}
			ELSE
			{
				echo "Tabelle \"kategorien\" wurde mit Daten vorbelegt.<BR>";
			}
			
			$res7 = mysql_query( "CREATE TABLE IF NOT EXISTS `kat_lex` (
			`lfdnr` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`kat_id` INT NOT NULL ,
			`info` TEXT NOT NULL ,
			UNIQUE (`kat_id`)
			) ENGINE = MYISAM ;");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Anlage der Tabelle \"kat_lex\"<BR>";
				$err_count++;
			}
			ELSE
			{
				echo "Tabelle \"kat_lex\" wurde angelegt.<BR>";
			}		
			
			
			$res6 = mysql_query( "CREATE TABLE IF NOT EXISTS `locations` (
			`loc_id` int(11) NOT NULL auto_increment COMMENT 'location-ID',
			`longitude` DOUBLE NOT NULL COMMENT 'geo-Laenge',
			`latitude` DOUBLE NOT NULL COMMENT 'geo-Breite',
			`altitude` DOUBLE NOT NULL COMMENT 'Hoehe',
			`location` varchar(50) NOT NULL default 'Ortsbezeichnung' COMMENT 'Ortname',
			PRIMARY KEY  (`loc_id`),
			KEY `location` (`location`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 CHARACTER SET utf8 COLLATE utf8_unicode_ci;");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Anlage der Tabelle \"locations\"<BR>";
				$err_count++;
			}
			ELSE
			{
				echo "Tabelle \"locations\" wurde angelegt.<BR>";
			}		
			
			
			$res16 = mysql_query( "CREATE TABLE IF NOT EXISTS `meta_data` (
			`ed_id` int(11) NOT NULL auto_increment,
			`pic_id` int(11) NOT NULL,
			`Make` varchar(37) default NULL,
			`Model` varchar(50) NOT NULL,
			`CameraModelName` varchar(50) default NULL,
			`Orientation` int(11) NOT NULL,
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
			`ISO` int(11) NOT NULL,
			`Quality` varchar(13) default NULL,
			`WhiteBalance` varchar(18) default NULL,
			`Sharpness` varchar(12) default NULL,
			`FocusMode` varchar(12) default NULL,
			`ISOSetting` int(11) NOT NULL,
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
			`GPSAltitude` varchar(13) default NULL,
			`GPSLatitude` varchar(29) default NULL,
			`GPSLongitude` varchar(29) default NULL,
			`City` varchar(50) NOT NULL,
			`Caption_Abstract` text NOT NULL,
			`GPSLatitudeRef` varchar(7) default NULL,
			`GPSLongitudeRef` varchar(7) default NULL,
			`GPSAltitudeRef` int(11) NOT NULL,
			PRIMARY KEY  (`ed_id`),
			KEY `Keywords` (`Keywords`),
			KEY `GPSLongitude` (`GPSLongitude`),
			KEY `GPSLatitude` (`GPSLatitude`),
			KEY `City` (`City`),
			KEY `Make` (`Make`),
			KEY `Model` (`CameraModelName`),
			KEY `FNumber` (`FNumber`),
			KEY `DateTimeOriginal` (`DateTimeOriginal`),
			KEY `pic_id` (`pic_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0;");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Anlage der Tabelle \"meta_data\"<BR>";
				$err_count++;
			}
			ELSE
			{
				echo "Tabelle \"meta_data\" wurde angelegt.<BR>";
			}		
			
			
			$res2 = mysql_query( "CREATE TABLE IF NOT EXISTS `meta_protect` (
			`lfdnr` int(11) NOT NULL auto_increment,
			`field_name` varchar(50) NOT NULL default '0',
			`writable` BOOL NOT NULL DEFAULT '0',
			`viewable` BOOL NOT NULL DEFAULT '0',
			PRIMARY KEY  (`lfdnr`)
			) ENGINE=MyISAM COMMENT='Sperr-Tabelle fuer EXIF-Daten' AUTO_INCREMENT=0 CHARACTER SET utf8 COLLATE utf8_unicode_ci;");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Anlage der Tabelle \"meta_protect\"<BR>";
				$err_count++;
			}
			ELSE
			{
				echo "Tabelle \"meta_protect\" wurde angelegt.<BR>";
			}		
			
			
			$res8 = mysql_query( "CREATE TABLE IF NOT EXISTS `permissions` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `perm_id` int(11) NOT NULL,
			  `description` text COLLATE utf8_unicode_ci NOT NULL,
			  `shortdescription` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=36 ;");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Anlage der Tabelle \"permissions\"<BR>";
				$err_count++;
			}
			ELSE
			{
				echo "Tabelle \"permissions\" wurde angelegt.<BR>";
			}		
			
			$res8_1 = mysql_query( "INSERT INTO `permissions` (`id`, `perm_id`, `description`, `shortdescription`) VALUES
			(1, 999, 'Admin-Login', 'adminlogin'),
			(2, 929, 'Vorschaubilder drehen', 'rotatepicture'),
			(3, 799, 'Bilder erfassen', 'addpic'),
			(4, 519, 'eigene Bilder löschen', 'deletemypics'),
			(5, 219, 'eigene Bilder downloaden', 'downloadmypics'),
			(6, 100, 'Bilder suchen', 'searchpic'),
			(7, 629, 'alle Bilder bearbeiten', 'editallpics'),
			(8, 989, 'Rechte hinzufügen', 'addpermission'),
			(9, 859, 'eigenes Benutzer-Profil bearbeiten', 'editmyprofile'),
			(10, 619, 'eigene Bilder bearbeiten', 'editmypics'),
			(11, 529, 'alle Bilder löschen', 'deleteallpics'),
			(12, 229, 'alle Bilder downloaden', 'downloadallpics'),
			(13, 899, 'Kategoriebaum bearbeiten', 'editkattree'),
			(14, 869, 'alle Benutzer-Profile bearbeiten', 'editallprofiles'),
			(15, 849, 'Benutzer anzeigen', 'showusers'),
			(16, 539, 'eigene Bilder geo-referenzieren', 'georefmypics'),
			(17, 549, 'alle Bilder geo-referenzieren', 'georefallpics'),
			(18, 639, 'Tagebuch bearbeiten', 'editdiary'),
			(19, 649, 'Kategorielexikon bearbeiten', 'editkatlex'),
			(20, 889, 'Ortsnamen bearbeiten', 'editlocationname');");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Bef&uuml;llung der Tabelle \"permissions\"<BR>";
				$err_count++;
			}
			ELSE
			{
				echo "Tabelle \"permissions\" wurde mit Daten vorbelegt.<BR>";
			}
			
			
			$res10 = mysql_query( "CREATE TABLE IF NOT EXISTS `pictures` (
			`pic_id` int(11) NOT NULL auto_increment,
			`FileNameOri` varchar(100) NOT NULL COMMENT 'Original-Dateiname',
			`FileName` varchar(25) NOT NULL COMMENT 'interner Dateiname',
			`FileNameHQ` varchar(25) NOT NULL COMMENT 'Name des HQ-Vorschau-Bildes',
			`FileNameV` varchar(25) NOT NULL default '',
			`FileNameHist` varchar(17) NOT NULL COMMENT 'Dateiname des Farb-Histogramms',
			`FileNameHist_r` varchar(17) NOT NULL COMMENT 'Dateiname des Rot-Kanal-Histogramms',
			`FileNameHist_g` varchar(17) NOT NULL,
			`FileNameHist_b` varchar(17) NOT NULL,
			`FileNameMono` varchar(20) NOT NULL COMMENT 'Dateiname des Monochrom-Bildes',
			`Owner` int(11) NOT NULL COMMENT 'user_id',
			`DateInsert` datetime NOT NULL default '0000-00-00 00:00:00',
			`loc_id` int(11) NOT NULL default '0' COMMENT 'location_id fuer geo-Referenzierung',
			`ranking` int(11) NOT NULL default '0',
			`note` tinyint(4) NOT NULL default '5' COMMENT 'Bewertung (Note 1 - 5)',
			`md5sum` varchar(50) NOT NULL,
			`has_kat` tinyint(1) NOT NULL default '0' COMMENT 'Neuzugang = 0, sonst 1',
			PRIMARY KEY  (`pic_id`),
			KEY `FileName` (`FileName`),
			KEY `FileNameHQ` (`FileNameHQ`),
			KEY `FileNameV` (`FileNameV`),
			KEY `DateInsert` (`DateInsert`),
			KEY `Owner` (`Owner`),
			KEY `ranking` (`ranking`),
			KEY `md5sum` (`md5sum`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Bilddaten' AUTO_INCREMENT=1;");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Anlage der Tabelle \"pictures\"<BR>";
				$err_count++;
			}
			ELSE
			{
				echo "Tabelle \"pictures\" wurde angelegt.<BR>";
			}		
			
			
			$res9 = mysql_query( "CREATE TABLE IF NOT EXISTS `pic_kat` (
			`lfdnr` int(11) NOT NULL auto_increment,
			`pic_id` int(11) NOT NULL,
			`kat_id` int(11) NOT NULL,
			KEY `lfdnr` (`lfdnr`)
			) ENGINE=MyISAM AUTO_INCREMENT=0 CHARACTER SET utf8 COLLATE utf8_unicode_ci;");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Anlage der Tabelle \"pic_kat\"<BR>";
				$err_count++;
			}
			ELSE
			{
				echo "Tabelle \"pic_kat\" wurde angelegt.<BR>";
			}		
			
			$res9 = mysql_query( "CREATE TABLE IF NOT EXISTS `tmp_tree` (
			`lfdnr` int(11) NOT NULL auto_increment,
			`kat_id` int(11) NOT NULL,
			`old_level` int(11) NOT NULL,
			`kat_name` varchar(75) NOT NULL,
			`user_id` int(11) NOT NULL,
			`new_level` int(11) NOT NULL,
			`new_parent` int(11) NOT NULL,
			PRIMARY KEY  (`lfdnr`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=306;");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Anlage der Tabelle \"tmp_tree\"<BR>";
				$err_count++;
			}
			ELSE
			{
				echo "Tabelle \"tmp_tree\" wurde angelegt.<BR>";
			}		
			
			
			$res12 = mysql_query( "CREATE TABLE IF NOT EXISTS `usergroups` (
			`id` int(11) NOT NULL auto_increment,
			`description` varchar(200) NOT NULL default '',
			PRIMARY KEY  (`id`,`description`)
			) ENGINE=MyISAM AUTO_INCREMENT=4 CHARACTER SET utf8 COLLATE utf8_unicode_ci;");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Anlage der Tabelle \"usergroups\"<BR>";
				$err_count++;
			}
			ELSE
			{
				echo "Tabelle \"usergroups\" wurde angelegt.<BR>";
			}		
			
			$res12_1 = mysql_query( "INSERT INTO `usergroups` (`id`, `description`) VALUES 
			(1, 'Admin'),
			(2, 'WebUsers'),
			(3, 'Fotograf'),
			(4, 'Gast');");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Bef&uuml;llung der Tabelle \"usergroups\"<BR>";
				$err_count++;
			}
			ELSE
			{
				echo "Tabelle \"usergroups\" wurde mit Daten vorbelegt.<BR>";
			}
			
			
			$res13 = mysql_query( "CREATE TABLE IF NOT EXISTS `userpermissions` (
			`id` int(11) NOT NULL auto_increment,
			`user_id` int(11) NOT NULL default '0',
			`permission_id` int(11) NOT NULL default '0',
			`enabled` smallint(1) NOT NULL default '0',
			PRIMARY KEY  (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=2 CHARACTER SET utf8 COLLATE utf8_unicode_ci;");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Anlage der Tabelle \"userpermissions\"<BR>";
				$err_count++;
			}
			ELSE
			{
				echo "Tabelle \"userpermissions\" wurde angelegt.<BR>";
			}		
			
			$res13_1 = mysql_query( "INSERT INTO `userpermissions` (`id`, `user_id`, `permission_id`, `enabled`) VALUES 
			(1, 1, 999, 1),
			(2, 1, 929, 1),
			(3, 1, 799, 1),
			(4, 1, 519, 1),
			(5, 1, 219, 1),
			(6, 1, 100, 1),
			(7, 1, 629, 1),
			(8, 1, 989, 1),
			(9, 1, 859, 1),
			(10, 1, 619, 1),
			(11, 1, 529, 1),
			(12, 1, 229, 1),
			(13, 1, 899, 1),
			(14, 1, 869, 1),
			(15, 1, 849, 1),
			(16, 1, 539, 1),
			(17, 1, 549, 1),
			(18, 1, 639, 1),
			(19, 1, 649, 1),
			(20, 1, 889, 1);");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Bef&uuml;llung der Tabelle \"userpermissions\"<BR>";
				$err_count++;
			}
			ELSE
			{
				echo "Tabelle \"userpermissions\" wurde mit Daten vorbelegt.<BR>";
			}
			
			$res14 = mysql_query( "CREATE TABLE IF NOT EXISTS `users` (
			`id` int(11) NOT NULL auto_increment,
			`username` varbinary(15) NOT NULL,
			`pwd` varchar(25) NOT NULL,
			`ftp_passwd` varchar(255) collate latin1_german1_ci NOT NULL default '',
			`shell` varchar(20) collate latin1_german1_ci NOT NULL default '/bin/sh',
			`note` text collate latin1_german1_ci,
			`count` int(11) NOT NULL default '0',
			`admin` int(1) NOT NULL default '0',
			`last_login` datetime default NULL,
			`dl_bytes` bigint(20) NOT NULL default '0',
			`ul_bytes` bigint(20) NOT NULL default '0',
			`group_id` int(11) NOT NULL default '0',
			`kennung` varchar(25) NOT NULL,
			`titel` varchar(20) NOT NULL,
			`name` varchar(25) NOT NULL,
			`vorname` varchar(25) NOT NULL,
			`strasse` varchar(25) NOT NULL,
			`plz` varchar(10) NOT NULL,
			`ort` varchar(50) NOT NULL,
			`tel` varchar(25) NOT NULL,
			`email` varchar(50) NOT NULL,
			`internet` varchar(50) NOT NULL,
			`berechtigung` char(1) NOT NULL,
			`aktiv` char(1) NOT NULL,
			`user_dir` varchar(75) NOT NULL COMMENT 'ftp-Stammverz. des Users',
			`up_dir` varchar(75) NOT NULL COMMENT 'ftp_uploadverzeichnis des Users',
			`down_dir` varchar(75) NOT NULL COMMENT 'ftp-Downloadverz. des Users',
			`direkt_download` int(1) NOT NULL DEFAULT '0' COMMENT '0-per FTP, 1-per Direkt-Download je Bild',
			`uid` int(5) NOT NULL default '65534',
			`gid` int(5) NOT NULL default '65534',
			UNIQUE KEY `homedir` (`up_dir`),
			KEY `id` (`id`),
			KEY `username` (`username`)
			) ENGINE=MyISAM COMMENT='Benutzeraccounts' AUTO_INCREMENT=1 CHARACTER SET utf8 COLLATE utf8_unicode_ci;");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Anlage der Tabelle \"users\"<BR>";
				$err_count++;
			}
			ELSE
			{
				echo "Tabelle \"users\" wurde angelegt.<BR>";
			}	
			
			$res20 = mysql_query( "CREATE TABLE IF NOT EXISTS `diary` (
			`diary_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`user_id` INT NOT NULL ,
			`datum` DATE NOT NULL ,
			`info` TEXT NOT NULL
			) ENGINE = MYISAM COMMENT = 'p2b-Tagebuch' CHARACTER SET utf8 COLLATE utf8_unicode_ci;");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Anlage der Tabelle \"diary\"<BR>";
				$err_count++;
			}
			ELSE
			{
				echo "Tabelle \"diary\" wurde angelegt.<BR>";
			}	
			
			// Ablage der Pfade zu den benoetigten Hilfsprogrammen, wird nach der Installation oder dem Softwarecheck neu befuellt
			$res21 = mysql_query("CREATE TABLE IF NOT EXISTS `pfade` (
			`path_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`dcraw_path` VARCHAR( 50 ) NOT NULL ,
			`conv_path` VARCHAR( 50 ) NOT NULL ,
			`et_path` VARCHAR( 50 ) NOT NULL ,
			`gpsb_path` VARCHAR( 50 ) NOT NULL ,
			`md5sum_path` VARCHAR( 50 ) NOT NULL
			) ENGINE = MYISAM COMMENT = 'Pfade zur Hilfssoftware';");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Anlage der Tabelle \"pfade\"<BR>";
				$err_count++;
			}
			ELSE
			{
				echo "Tabelle \"pfade\" wurde angelegt.<BR>";
			}	
			
			//Ablage der von dcraw unterstuetzten Dateiformate
			$res22 = mysql_query("CREATE TABLE IF NOT EXISTS `fileformats` (
			`format_id` INT NOT NULL AUTO_INCREMENT, 
			`format` VARCHAR(10) NOT NULL, 
			`raw` TINYINT(1) NOT NULL DEFAULT '0', 
			PRIMARY KEY (`format_id`), 
			INDEX (`format`)
			) ENGINE = MyISAM COMMENT = 'von ImageMagick unterstützte Dateiformate';");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Anlage der Tabelle \"fileformats\"<BR>";
				$err_count++;
			}
			ELSE
			{
				echo "Tabelle \"fileformats\" wurde angelegt.<BR>";
			}
			
			//Tabelle der Datenlogger
			$res23 = mysql_query("CREATE TABLE IF NOT EXISTS `data_logger` (
			`logger_id` int(11) NOT NULL AUTO_INCREMENT,
			`logger_number` int(11) NOT NULL,
			`logger_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
			`enabled` int(11) NOT NULL,
			PRIMARY KEY (`logger_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8;");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Anlage der Tabelle \"data_logger\"<BR>";
				$err_count++;
			}
			ELSE
			{
				echo "Tabelle \"data_logger\" wurde angelegt.<BR>";
			}
			
			$res23_1 = mysql_query("INSERT INTO `data_logger` (`logger_id`, `logger_number`, `logger_name`, `enabled`) VALUES
			(1, 1, 'Sony CS1 oder kompatible (nmea-Format)', 1),
			(2, 2, 'Garmin GPSmap 60CS(x) - gpx-Datei', 1),
			(3, 17, 'Garmin MapSource - gdb', 1),
			(4, 3, 'Garmin GPSmap 60CS(x) - txt-Datei', 0),
			(5, 5, 'Alan Map500 tracklogs (.trl)', 0),
			(6, 8, 'CompeGPS data files (.wpt/.trk/.rte)', 0),
			(7, 9, 'cotoGPS for Palm/OS', 0);");
			
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Bef&uuml;llung der Tabelle \"data_logger\"<BR>";
				$err_count++;
			}
			ELSE
			{
				echo "Tabelle \"data_logger\" wurde mit Daten vorbelegt.<BR>";
			}
			
			//Tabelle der Zeitzonen
			$res24 = mysql_query("CREATE TABLE IF NOT EXISTS `timezone` (
			`zone_id` int(11) NOT NULL AUTO_INCREMENT,
			`zone_number` int(11) NOT NULL,
			`zone_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
			PRIMARY KEY (`zone_id`),
			KEY `zone_number` (`zone_number`,`zone_name`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Tabelle der Zeitzonen' AUTO_INCREMENT=26;");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Anlage der Tabelle \"timezone\"<BR>";
				$err_count++;
			}
			ELSE
			{
				echo "Tabelle \"timezone\" wurde angelegt.<BR>";
			}
			
			$res24_1 = mysql_query( "INSERT INTO `timezone` (`zone_id`, `zone_number`, `zone_name`) VALUES
			(1, -12, 'Bakerinsel (UTC - 12h)'),
			(2, -11, 'Midway-Inseln, Samoa (UTC - 11h)'),
			(3, -10, 'Alaska Hawaii (UTC - 10h)'),
			(4, -9, 'Franz. Polynesien, USA (AKST / UTC - 9h)'),
			(5, -8, 'Kanada, Mexico, USA (PST / UTC - 8h)'),
			(6, -7, 'Kanada, Mexico, USA (PST / UTC - 7h)'),
			(7, -6, 'Chile, Costa Rica, Honduras (CST / UTC - 6h)'),
			(8, -5, 'Bahamas, Haiti, USA (EST / UTC - 5h)'),
			(9, -4, 'Barbados, Grenada, Gr&ouml;nland (AST / UTC - 4h)'),
			(10, -3, 'Argentinien, Brasilien, Uruguay (UTC - 3h)'),
			(11, -2, 'Brasilien (UTC - 2h)'),
			(12, -1, 'Gr&ouml;nland, Kap Verde (UTC - 1h)'),
			(13, 0, 'London, Lissabon, Reykjavik (GMT / WET / UTC)'),
			(14, 1, 'Berlin, Prag, Rom (CET / MEZ)'),
			(15, 2, 'Helsinki, Kairo, Sofia (CESZ / EET)'),
			(16, 3, 'Baghdad, Moskau, Sankt Petersburg (MSK, BT)'),
			(17, 4, 'Armenien, Georgien, VAR (UTC + 4h)'),
			(18, 5, 'Pakistan (UTC + 5h)'),
			(19, 6, 'Bangladesh (UTC + 6h)'),
			(20, 7, 'Pnom Phen, Saigon, Hanoi (UTC + 7h)'),
			(21, 8, 'Peking (UTC + 8h)'),
			(22, 9, 'Seoul, Tokio (UTC + 9h)'),
			(23, 10, 'Sidney (UTC + 10h)'),
			(24, 11, 'Neukaledonien(UTC + 11h)'),
			(25, 12, 'Fidschi, Kiribati, Neuseeland (IDLE / UTC + 12h)');");
			IF(mysql_error() !== '')
			{
				echo "Fehler bei der Bef&uuml;llung der Tabelle \"timezone\"<BR>";
				$err_count++;
			}
			ELSE
			{
				echo "Tabelle \"timezone\" wurde mit Daten vorbelegt.<BR>";
			}
			
			if(!isset($titel))
			{
				$titel = '';
			}
			if(!isset($name))
			{
				$name = '';
			}
			if(!isset($vorname))
			{
				$vorname = '';
			}
			if(!isset($strasse))
			{
				$strasse = '';
			}
			if(!isset($telefon))
			{
				$telefon = '';
			}
			if(!isset($berechtigung))
			{
				$berechtigung = '';
			}
			//ersten User anlegen:
			$res115 = mysql_query( "CREATE USER 'pb'@'localhost' IDENTIFIED BY 'pic_base';");
			//diesem User Rechte erteilen:
			$res116 = mysql_query( "GRANT USAGE ON * . * TO 'pb'@'localhost' IDENTIFIED BY 'pic_base' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;");
			
			$res117 = mysql_query( "GRANT SELECT , INSERT , UPDATE , DELETE , CREATE, DROP, ALTER ON `pic2base` . * TO 'pb'@'localhost';");
			//fuer die Uebergangszeit, solange md5sum in pictures eingefuehrt wird:
			$res118 = mysql_query( "GRANT ALTER ON `pic2base`.`pictures` TO 'pb'@'localhost';");
			//User pb in der user-Tabelle erzeugen:
			$key = '0815';
			$pwd = 'pic_base';
			$ftp_passwd = crypt('pic_base');
			$res119 = mysql_query( "INSERT INTO `users` (username, titel, name, vorname, strasse, plz, ort, pwd, ftp_passwd, tel, email, internet, uid, gid, aktiv, user_dir, up_dir, down_dir, berechtigung, group_id) VALUES ('pb', '$titel', '$name', '$vorname', '$strasse', '38889', 'Blankenburg (Harz)', ENCRYPT('$pwd','$key'), '$ftp_passwd', '$telefon', 'info@pic2base.de', 'http://www.pic2base.de', '65534', '65534', '1', '/opt/lampp/htdocs/events/admin/pic2base/userdata/pb', '/opt/lampp/htdocs/events/admin/pic2base/userdata/pb/uploads', '/opt/lampp/htdocs/events/admin/pic2base/userdata/pb/downloads','$berechtigung', '1')");
			
			//setzen der Rechte auf die Ordner: images, tracks und userdaten:
			include_once 'global_config.php';
			$pic_path = $sr."/images";
			$track_path = $sr."/tracks";
			$user_path = $sr."/userdata";
			
			clearstatcache();  
			exec("chmod -R 700 $sr");
			clearstatcache();
			
			$text .= "...fertig!";
			IF($err_count == 0)
			{
				echo "<meta http-equiv='Refresh' Content=3; URL=index.php'>";
			}
			ELSE
			{
				echo "Es traten ".$err_count." Fehler bei der Einrichtung der Datenbank auf!<BR>
				Bitte informieren Sie Ihren Administrator.<BR>
				<meta http-equiv='Refresh' Content=60; URL=index.php'>";
			}
			return;
		}
		ELSE
		{
			echo "";
		}
		
	}
}
?>
