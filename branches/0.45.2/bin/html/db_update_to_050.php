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

unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = preg_split('#,#',$_COOKIE['login']);
//echo $c_username;
}

include '../share/db_connect1.php';
include '../share/functions/permissions.php';


	mysql_close($conn);
	@$conn = mysql_connect('localhost','root','cx4dd');
	$res0 = mysql_select_db('pic2base');
	echo "
	<div class='page'>
	
		<div class='title'>
		<!--<img src='' style='float:right;width:156; height:39;margin-left:3px;' alt='Logo'>-->
		<h1>pic2base - Datenbank-Umstrukturierung</h1>
		</div>
		
		<div class='navi' style='clear:right;'>
			<div class='menucontainer'>
			<!---<a class='navi' href='erfassung1.php'>Erfassung</a>
			<a class='navi' href='recherche1.php'>Recherche</a>
			<a class='navi' href='vorschau.php'>Bearbeitung</a>-->
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
			echo "Sicherheitskopie \"permissions_bak\" wurde angelegt<BR><BR>";

		
			$res2 = mysql_query( "CREATE TABLE IF NOT EXISTS `pic2base`.`permissions` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `perm_id` int(11) NOT NULL,
				  `description` text COLLATE utf8_unicode_ci NOT NULL,
				  `shortdescription` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=36 ;");
			echo mysql_error()."<BR>";
		}
		IF(mysql_error() == '')
		{
			mysql_set_charset('utf8', $conn);
			$res3 = mysql_query( "INSERT INTO `pic2base`.`permissions` (`id`, `perm_id`, `description`, `shortdescription`) VALUES
				(1, 999, 'Admin-Login', 'adminlogin'),
				(2, 799, 'Bilder erfassen', 'addpic'),
				(3, 519, 'eigene Bilder löschen', 'deletemypics'),
				(4, 219, 'eigene Bilder downloaden', 'downloadmypics'),
				(5, 100, 'Bilder suchen', 'searchpic'),
				(6, 629, 'alle Bilder bearbeiten', 'editallpics'),
				(7, 989, 'Rechte hinzufügen', 'addpermission'),
				(8, 859, 'eigenes Benutzer-Profil bearbeiten', 'editmyprofile'),
				(9, 619, 'eigene Bilder bearbeiten', 'editmypics'),
				(10, 529, 'alle Bilder löschen', 'deleteallpics'),
				(11, 229, 'alle Bilder downloaden', 'downloadallpics'),
				(12, 899, 'Kategoriebaum bearbeiten', 'editkattree'),
				(13, 869, 'alle Benutzer-Profile bearbeiten', 'editallprofiles'),
				(14, 849, 'Benutzer anzeigen', 'showusers'),
				(15, 539, 'eigene Bilder geo-referenzieren', 'georefmypics'),
				(16, 549, 'alle Bilder geo-referenzieren', 'georefallpics'),
				(17, 639, 'Tagebuch bearbeiten', 'editdiary'),
				(18, 649, 'Kategorielexikon bearbeiten', 'editkatlex');");
			echo mysql_error()."<BR>";
			IF(mysql_error() == '')
			{
				echo "Neue Tabelle \"permissions\" wurde angelegt<BR><BR>";
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
					echo "Tabelle \"userpermissions\" wurde neu aufgebaut.<BR><BR>";
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
						echo "Tabelle \"grouppermissions\" wurde neu aufgebaut.<BR>";
						//Aenderung derFelder locations.longitude, locations.latitude, locations.altitude in DOUBLE:
						$res17 = mysql_query("ALTER TABLE `pic2base`.`locations` CHANGE `longitude` `longitude` DOUBLE NOT NULL COMMENT 'geo-Laenge',
						CHANGE `latitude` `latitude` DOUBLE NOT NULL COMMENT 'geo-Breite',
						CHANGE `altitude` `altitude` DOUBLE NOT NULL COMMENT 'Hoehe'");
						echo mysql_error()."<BR>";
						IF(mysql_error() == '')
						{
							echo "Die Tabelle \"locations\"wurde aktualisiert.<BR><BR>
							Die Datenbank-Reorganisation wurde erfolgreich abgeschlossen.";
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
			echo "Sicherungskopie der Tabelle \"permissions\" konnte NICHT erstellt werden.";
		}
	    echo "</p>
		</div>
		<br style='clear:both;' />
	
		<div class='fuss'>
		<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>
		</div>
	
	</div>";
//}
mysql_close($conn);

?>
</DIV>
</CENTER>
</BODY>
</HTML>