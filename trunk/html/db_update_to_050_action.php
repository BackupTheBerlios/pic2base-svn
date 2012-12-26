<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Datenbank-Umstrukturierung</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
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
 * File: db_update_to_050.php
 *
 * Copyright (c) 2010 Klaus Henneberg
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
//
// Rechte-Tabelle (permissions) wird gesichert, mit neuer Struktur wieder angelegt und den Standard-Werten befuellt
// Tabelle der Benutzerrechte wird gesichert, geleert und neu befuellt
// Tabelle der Gruppenrechte wird gesichert, geleert und neu befuellt
// Struktur der locations-Tabelle wird korrigiert
// nicht weiter verwendete Tabelle 'rights' wird entfernt
// Tabelle der Usereinstellungen wird modifiziert
// Tabelle der Software-Pfade wird neu angelegt
// Tabelle der unterstuetzten Dateiformate wird neu angelegt und mit der Funktion
// checkSoftware mit den aktuellen Pfadangaben befuellt
// Bild-Ausrichtung der Tabelle meta_data wird neu eingelesen
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
	<h1>pic2base - Datenbank-Umstrukturierung</h1>
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
	//Sicherheitskopien der Tabellen permissions, userpermissions und grouppermissions erstellen:
	$res1 = mysql_query("RENAME TABLE `pic2base`.`permissions` TO `pic2base`.`permissions_bak`");
	IF(mysql_error() == '')
	{
		//echo "Sicherheitskopie \"permissions_bak\" wurde angelegt<BR>";
		$res2 = mysql_query( "CREATE TABLE IF NOT EXISTS `pic2base`.`permissions` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `perm_id` int(11) NOT NULL,
			  `description` text COLLATE utf8_unicode_ci NOT NULL,
			  `shortdescription` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=36 ;");
		//echo mysql_error()."<BR>";
	}
	IF(mysql_error() == '')
	{
		$php_version = phpversion();
		$res1 = mysql_query("SELECT VERSION()");
		$mysql_version = mysql_result($res1, isset($i), 'VERSION()');
		IF((substr($php_version,0,3) > 5.1) AND (substr($mysql_version,0,3) > 5.0))
		{
			//echo "PHP-Version ist h&ouml;her als 5.2.3. und MySQL-Version ist h&ouml;her als 5.0.7";
			mysql_set_charset('utf8', $conn);// (verwendbar, wenn PHP 5 >= 5.2.3 und MySQL >= 5.0.7
		}
		ELSE
		{
			//echo "Version ist niedriger oder gleich 5.2.3. oder / und MySQL-Version ist niedriger als 5.0.7";
			$res30 = mysql_query("SET NAMES 'utf8'");//fuer aeltere PHP / MySQL-Versionen 
		}
		$res3 = mysql_query( "INSERT INTO `pic2base`.`permissions` (`id`, `perm_id`, `description`, `shortdescription`) VALUES
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
			(19, 649, 'Kategorielexikon bearbeiten', 'editkatlex');");
		echo mysql_error()."<BR>";
		IF(mysql_error() == '')
		{
			//echo "Neue Tabelle \"permissions\" wurde angelegt<BR><BR>";
			//Tabelle userpermissions neu befuellen: hierzu
			// 1)Sicherheitskopie userpermissions_bak erzeugen
			// 2)userpermissions leeren
			// 3)userpermissions neu befuellen
			//
			// 1)
			$res10 = mysql_query("CREATE TABLE `pic2base`.`userpermissions_bak` (
			`id` int( 11 ) NOT NULL AUTO_INCREMENT ,
			`user_id` int( 11 ) NOT NULL DEFAULT '0',
			`permission_id` int( 11 ) NOT NULL DEFAULT '0',
			`enabled` smallint( 1 ) NOT NULL DEFAULT '0',
			PRIMARY KEY ( `id` )
			) ENGINE = MYISAM DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;");
			echo mysql_error();
			$res11 = mysql_query("INSERT INTO `pic2base`.`userpermissions_bak`
			SELECT * FROM `pic2base`.`userpermissions`");
			echo mysql_error();
			
			// 2)
			$res12 = mysql_query("TRUNCATE TABLE $table7");
			
			// 3)
			// ermitteln, welche User zur Gruppe Admin gehoeren. Diesen werden alle Rechte zugewiesen.
			//Alle anderen User erhalten zunaechst keine Rechte.
			$res13 = mysql_query("SELECT $table1.id, $table1.group_id, $table9.id, $table9.description 
			FROM $table1, $table9 
			WHERE $table9.description = 'Admin' 
			AND $table1.group_id = $table9.id");
			echo mysql_error();
			$num13 = mysql_num_rows($res13);
			$admin_user = array();
			FOR($i13='0'; $i13<$num13; $i13++)
			{
				$admin_user[] = mysql_result($res13, $i13, 'users.id');
			}
			$res14 = mysql_query("SELECT * FROM $table1");
			$num14 = mysql_num_rows($res14);
			FOR($i14='0'; $i14<$num14; $i14++)
			{
				$user_id = mysql_result($res14, $i14, 'id');
				IF(in_array($user_id, $admin_user))
				{
					$enabled = '1';
				}
				ELSE
				{
					$enabled = '0';
				}
				$res15 = mysql_query("SELECT * FROM $table8");
				$num15 = mysql_num_rows($res15);
				FOR($i15='0'; $i15<$num15; $i15++)
				{
					$perm_id = mysql_result($res15, $i15, 'perm_id');
					$res16 = mysql_query("INSERT INTO $table7 (user_id, permission_id, enabled) VALUES 
					('$user_id', '$perm_id', '$enabled')");
				}
			}
			
			IF(mysql_error() == '')
			{
				//echo "Tabelle \"userpermissions\" wurde neu aufgebaut.<BR><BR>";
				//#############################################################
				//Tabelle grouppermissions neu befuellen: hierzu
				// 1)Sicherheitskopie grouppermissions_bak erzeugen
				// 2)grouppermissions leeren
				// 3)grouppermissions neu befuellen
				//
				// 1)
				$res7 = mysql_query("CREATE TABLE `pic2base`.`grouppermissions_bak` (
				`id` int( 11 ) NOT NULL AUTO_INCREMENT ,
				`group_id` int( 11 ) NOT NULL DEFAULT '0',
				`permission_id` int( 11 ) NOT NULL DEFAULT '0',
				`enabled` smallint( 1 ) NOT NULL DEFAULT '0',
				PRIMARY KEY ( `id` )
				) ENGINE = MYISAM DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;"); 
				$res8 = mysql_query("INSERT INTO `grouppermissions_bak` SELECT * FROM `grouppermissions`");
				
				// 2)
				$res7 = mysql_query("TRUNCATE TABLE $table6");
				
				// 3)
				//Ermittlung, welche Group-ID zur Admin-Gruppe gehoert. Dieser Gruppe werden alle Rechte zugewiesen,
				//allen anderen Gruppen zur Sicherheit zunaechst kein Recht
				$res4 = mysql_query("SELECT * FROM $table9 WHERE description = 'Admin'");
				$admin_id = mysql_result($res4, 0, 'id');
				$res5 = mysql_query("SELECT * FROM $table9");
				$num5 = mysql_num_rows($res5);
				FOR($i5='0'; $i5<$num5; $i5++)
				{
					$group_id = mysql_result($res5, $i5, 'id');
					IF($group_id == $admin_id)
					{
						$enabled = '1';
					}
					ELSE
					{
						$enabled = '0';
					}
					$res6 = mysql_query("SELECT * FROM $table8");
					$num6 = mysql_num_rows($res6);
					FOR($i6='0'; $i6<$num6; $i6++)
					{
						$perm_id = mysql_result($res6, $i6, 'perm_id');
						$res9 = mysql_query("INSERT INTO $table6 (group_id, permission_id, enabled) VALUES 
						('$group_id', '$perm_id', '$enabled')");
					}
				}
				//###############################################################
				IF(mysql_error() == '')
				{
					//echo "Tabelle \"grouppermissions\" wurde neu aufgebaut.<BR><BR>";
					//Aenderung der Felder locations.longitude, locations.latitude, locations.altitude in DOUBLE:
					$res17 = mysql_query("ALTER TABLE `pic2base`.`locations` CHANGE `longitude` `longitude` DOUBLE NOT NULL COMMENT 'geo-Laenge',
					CHANGE `latitude` `latitude` DOUBLE NOT NULL COMMENT 'geo-Breite',
					CHANGE `altitude` `altitude` DOUBLE NOT NULL COMMENT 'Hoehe'");
					IF(mysql_error() == '')
					{
						//echo "Die Tabelle \"locations\"wurde aktualisiert.<BR><BR>";
						//Tabelle rights loeschen, da sie nicht mehr verwendet wird:
						$res18 = mysql_query("DROP TABLE IF EXISTS `pic2base`.`rights`");
						//echo mysql_error()."<BR>";
						IF(mysql_error() == '')
						{
							//echo "Die nicht mehr genutzte Tabelle \"rights\" wurde entfernt.<BR><BR>";
							$res19 = mysql_query("ALTER TABLE `pic2base`.`users`
							ADD `direkt_download` TINYINT( 4 ) NOT NULL DEFAULT '0' 
							COMMENT '0-FTP-Download, 1-direkter Download' ");
							//echo mysql_error()."<BR>";
							IF(mysql_error() == '')
							{
								//echo "Die Tabelle \"users\" wurde erfolgreich angepasst.<BR><BR>";
								$res20 = mysql_query( "CREATE TABLE IF NOT EXISTS `pic2base`.`pfade` (
								  `path_id` int(11) NOT NULL AUTO_INCREMENT,
								  `dcraw_path` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
								  `conv_path` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
								  `et_path` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
								  `gpsb_path` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
								  `md5sum_path` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
								  PRIMARY KEY (`path_id`)
								) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci 
								COMMENT='Pfade zur Hilfssoftware' AUTO_INCREMENT=1 ;");
								//echo mysql_error()."<BR>";
								IF(mysql_error() == '')
								{
									//echo "Die Tabelle \"pfade\" wurde erfolgreich angelegt.<BR><BR>";
									$res21 = mysql_query("CREATE TABLE IF NOT EXISTS `pic2base`.`fileformats` (
									`format_id` INT NOT NULL AUTO_INCREMENT, 
									`format` VARCHAR(10) NOT NULL, 
									`raw` TINYINT(1) NOT NULL DEFAULT '0', 
									PRIMARY KEY (`format_id`), 
									INDEX (`format`)
									) ENGINE = MyISAM COMMENT = 'von ImageMagick unterstützte Dateiformate';");
									IF(mysql_error() == '')
									{
										//echo "Die Tabelle \"fileformats\" wurde erfolgreich angelegt.<BR><BR>
										echo "Die Datenbank-Reorganisation wurde erfolgreich abgeschlossen.";
										//abschliessender Software-Check:
										checkSoftware($sr);
										
										//Bild-Ausrichtung in der Tabelle meta_data korrigieren:
										$res100 = mysql_query("SELECT $table14.*, $table2.pic_id, $table2.FileNameOri 
										FROM $table2, $table14 
										WHERE $table2.pic_id = $table14.pic_id");
										$num100 = mysql_num_rows($res100);
										FOR($i100=0; $i100<$num100; $i100++)
										{
											$FNO = mysql_result($res100, $i100, 'FileNameOri');
											//Bestimmung der Dateinamen-Extension
											$FN_elements = explode(".", $FNO);
											$anz = count($FN_elements);
											$FNO_extension = strtolower($FN_elements[$anz-1]);
											//###################################
											$pic_id = mysql_result($res100, $i100, 'pic_id');
											$FN = $pic_id.".".$FNO_extension;
											$ETC = buildExiftoolCommand($sr);
											$command = $ETC." -Orientation -n ".$pic_path."/".$FN;
											$or = shell_exec($command);
											$Ori_arr = preg_split('# : #', $or);
											IF ($or !== NULL)
											{
												$Ori = $Ori_arr[1];
											}
											ELSE
											{
												$Ori = 0;
											}
											//echo $FN." / ".$Ori."<BR>";
											//Ausrichtung in Tabelle meta_data speichern:
											$res101 = mysql_query("UPDATE $table14 SET Orientation = '$Ori' WHERE pic_id = '$pic_id'");
											IF(mysql_error() == '')
											{
												//echo "Datensatz ".($i100 + 1)." von ".$num100."; Datei ".$FN." / Ausrichtung: ".$Ori."<BR>";
											}
											ELSE
											{
												echo "Es ist ein Fehler bei Datensatz ".($i100 + 1)." aufgetreten!:<BR>";
												echo mysql_error();
											}
											IF(($i100 +1) == $num100)
											{
												echo "<BR><b>Neuerfassung der Bild-Ausrichtungen wurde erfolgreich abgeschlossen.<BR>
												Damit ist das Update beendet.</b>";
											}
										}
									}
									ELSE
									{
										echo "Die Tabelle \"fileformats\" konnte NICHT angelegt werden.";
									}
								}
								ELSE
								{
									echo "Die Tabelle \"pfade\" konnte NICHT angelegt werden.";
								}
							}
							ELSE
							{
								echo "Die Tabelle \"users\" konnte NICHT bearbeitet werden.";
							}
						}
						ELSE
						{
							echo "Die Tabelle \"rights\" konnte NICHT entfernt werden.";
						}
					}
					ELSE
					{
						echo "Tabelle \"locations\" konnte NICHT aktualisiert werden.";
					}
				}
				ELSE
				{
					echo "Tabelle \"grouppermissions\" konnte NICHT neu aufgebaut werden.";
				}
			}
			ELSE
			{
				echo "Tabelle \"userpermissions\" konnte NICHT neu aufgebaut werden.";
			}
		}
		ELSE
		{
			echo "Neue Tabelle \"permissions\" konnte NICHT erstellt werden.";
		}
	}
	ELSE
	{
		echo "Sicherungskopie der Tabelle \"permissions\" konnte NICHT erstellt werden.<BR><BR>
		WICHTIG: In Zeile 48 dieses Skriptes muss ein User mit Admin-Rechten<BR>
		auf der Datenbank und dessen Passwort eingetragen werden!";
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