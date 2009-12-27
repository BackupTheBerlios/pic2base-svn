<?

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
		$text = "<center><font color='green'><b>Willkommen bei pic2base!</b><BR> Noch konnte keine Verbindung zum Datenbank-Server ".$db_server." hergestellt werden.<BR>Das kann daran liegen, da� Sie pic2base zum ersten Mal starten.<BR><BR>
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
		//Wenn die Verbindung auf den DB-Server erfolgreich hergestellt werden konnte, wird versucht auf die Datenbank zuzugreifen:
		if (!mysql_select_db($db)) 
		{
			$res1 = mysql_query("CREATE DATABASE IF NOT EXISTS pic2base CHARACTER SET utf8 COLLATE utf8_unicode_ci",$conn);
			
			$text =  "pic2base-Datenbank wurde angelegt.<BR>Lege nun die Tabellen an...<BR>";
			mysql_close($conn);
			$conn_neu = mysql_connect('localhost', $user, $PWD);
			mysql_select_db($db);
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
			
			
			$res4 = mysql_query( "CREATE TABLE IF NOT EXISTS `grouppermissions` (
			`id` int(11) NOT NULL auto_increment,
			`group_id` int(11) NOT NULL default '0',
			`permission_id` int(11) NOT NULL default '0',
			`enabled` smallint(1) NOT NULL default '0',
			PRIMARY KEY  (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=42 CHARACTER SET utf8 COLLATE utf8_unicode_ci;");
			$res4_1 = mysql_query( "INSERT INTO `grouppermissions` (`id`, `group_id`, `permission_id`, `enabled`) VALUES 
			(1, 1, 1, 1),
			(2, 0, 0, 0),
			(3, 1, 2, 1),
			(4, 1, 3, 1),
			(5, 1, 19, 1),
			(6, 1, 20, 1),
			(7, 1, 21, 1),
			(8, 2, 20, 1),
			(9, 4, 20, 1),
			(10, 3, 19, 1),
			(11, 3, 21, 1),
			(12, 3, 2, 1),
			(13, 3, 3, 1),
			(14, 3, 20, 1);");
			
			
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
			
			$res5_1 = mysql_query( "INSERT INTO `kategorien` (`kategorie`, `parent`, `level`) VALUES ('Neuzugaenge','0','0');");
			
			
			$res7 = mysql_query( "CREATE TABLE IF NOT EXISTS `kat_lex` (
			`lfdnr` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`kat_id` INT NOT NULL ,
			`info` TEXT NOT NULL ,
			UNIQUE (`kat_id`)
			) ENGINE = MYISAM ;");
			
			
			$res6 = mysql_query( "CREATE TABLE IF NOT EXISTS `locations` (
			`loc_id` int(11) NOT NULL auto_increment COMMENT 'location-ID',
			`longitude` varchar(25) NOT NULL COMMENT 'geo-Laenge',
			`latitude` varchar(25) NOT NULL COMMENT 'geo-Breite',
			`altitude` decimal(6,1) NOT NULL COMMENT 'Hoehe',
			`location` varchar(50) NOT NULL default 'Ortsbezeichnung' COMMENT 'Ortname',
			PRIMARY KEY  (`loc_id`),
			KEY `location` (`location`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 CHARACTER SET utf8 COLLATE utf8_unicode_ci;");
			
			
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
			KEY `DateTimeOriginal` (`DateTimeOriginal`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0;");
			
			
			$res2 = mysql_query( "CREATE TABLE IF NOT EXISTS `meta_protect` (
			`lfdnr` int(11) NOT NULL auto_increment,
			`field_name` varchar(50) NOT NULL default '0',
			`writable` BOOL NOT NULL DEFAULT '0',
			PRIMARY KEY  (`lfdnr`)
			) ENGINE=MyISAM COMMENT='Sperr-Tabelle fuer EXIF-Daten' AUTO_INCREMENT=0 CHARACTER SET utf8 COLLATE utf8_unicode_ci;");
			
			
			$res8 = mysql_query( "CREATE TABLE IF NOT EXISTS `permissions` (
			`id` int(11) NOT NULL auto_increment,
			`description` text NOT NULL,
			`shortdescription` varchar(100) NOT NULL default '',
			PRIMARY KEY  (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=19 CHARACTER SET utf8 COLLATE utf8_unicode_ci;");
			$res8_1 = mysql_query( "INSERT INTO `permissions` (`id`, `description`, `shortdescription`) VALUES 
			(1, 'Admin-Login', 'adminlogin'),
			(2, 'Bilder erfassen', 'addpic'),
			(3, 'Bilder loeschen', 'deletepic'),
			(21, 'Bilder downloaden', 'downloadpic'),
			(20, 'Bilder suchen', 'searchpic'),
			(19, 'Bilder bearbeiten', 'editpic');");
			
			
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
			
			
			$res9 = mysql_query( "CREATE TABLE IF NOT EXISTS `pic_kat` (
			`lfdnr` int(11) NOT NULL auto_increment,
			`pic_id` int(11) NOT NULL,
			`kat_id` int(11) NOT NULL,
			KEY `lfdnr` (`lfdnr`)
			) ENGINE=MyISAM AUTO_INCREMENT=0 CHARACTER SET utf8 COLLATE utf8_unicode_ci;");
			
			
			$res11 = mysql_query( "CREATE TABLE IF NOT EXISTS `rights` (
			`right_id` int(11) NOT NULL auto_increment,
			`user_group` varchar(25) NOT NULL,
			`description` text NOT NULL,
			KEY `right_id` (`right_id`)
			) ENGINE=MyISAM COMMENT='Definition der Gruppen-Rechte' AUTO_INCREMENT=10 CHARACTER SET utf8 COLLATE utf8_unicode_ci;");
			$res11_1 = mysql_query( "INSERT INTO `rights` (`right_id`, `user_group`, `description`) VALUES 
			(1, 'Administrator', 'Hat alle Schreib-, Lese- und Loeschrechte'),
			(2, 'NN', 'Platzhalter'),
			(3, 'NN', 'Platzhalter'),
			(4, 'NN', 'Platzhalter'),
			(5, 'Fotograf', 'kann seine Bilder speichern und bearbeiten, nicht jedoch loeschen\r\nkann ueber alle Bilder recherchieren'),
			(6, 'NN', 'Platzhalter'),
			(7, 'NN', 'Platzhalter'),
			(8, 'NN', 'Platzhalter'),
			(9, 'Web-User', 'kann nur ueber das Web-Frontend freigegebene Dateien recherchieren, Leserecht fuer freigegebene Dateien, keine Bearbeitungs- oder Loeschrechte');");
			
			
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
			
			
			$res12 = mysql_query( "CREATE TABLE IF NOT EXISTS `usergroups` (
			`id` int(11) NOT NULL auto_increment,
			`description` varchar(200) NOT NULL default '',
			PRIMARY KEY  (`id`,`description`)
			) ENGINE=MyISAM AUTO_INCREMENT=4 CHARACTER SET utf8 COLLATE utf8_unicode_ci;");
			$res12_1 = mysql_query( "INSERT INTO `usergroups` (`id`, `description`) VALUES 
			(1, 'Admin'),
			(2, 'WebUsers'),
			(3, 'Fotograf'),
			(4, 'Gast');");
			
			
			$res13 = mysql_query( "CREATE TABLE IF NOT EXISTS `userpermissions` (
			`id` int(11) NOT NULL auto_increment,
			`user_id` int(11) NOT NULL default '0',
			`permission_id` int(11) NOT NULL default '0',
			`enabled` smallint(1) NOT NULL default '0',
			PRIMARY KEY  (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=2 CHARACTER SET utf8 COLLATE utf8_unicode_ci;");
			$res13_1 = mysql_query( "INSERT INTO `userpermissions` (`id`, `user_id`, `permission_id`, `enabled`) VALUES 
			(1, 1, 1, 1),
			(2, 1, 2, 1),
			(3, 1, 3, 1),
			(4, 1, 19, 1),
			(5, 1, 20, 1),
			(6, 1, 21, 1);");
			
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
			`uid` int(5) NOT NULL default '65534',
			`gid` int(5) NOT NULL default '65534',
			UNIQUE KEY `homedir` (`up_dir`),
			KEY `id` (`id`),
			KEY `username` (`username`)
			) ENGINE=MyISAM COMMENT='Benutzeraccounts' AUTO_INCREMENT=1 CHARACTER SET utf8 COLLATE utf8_unicode_ci;");
			
			$res20 = mysql_query( "CREATE TABLE `diary` (
			`diary_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`user_id` INT NOT NULL ,
			`datum` DATE NOT NULL ,
			`info` TEXT NOT NULL
			) ENGINE = MYISAM COMMENT = 'p2b-Tagebuch' CHARACTER SET utf8 COLLATE utf8_unicode_ci;");
			
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
			
			$res117 = mysql_query( "GRANT SELECT , INSERT , UPDATE , DELETE , ALTER ON `pic2base` . * TO 'pb'@'localhost';");
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
			echo "<meta http-equiv='Refresh' Content=3; URL=index.php'>";
			return;
		}
		ELSE
		{
			echo "";
		}
		
	}
}
?>
