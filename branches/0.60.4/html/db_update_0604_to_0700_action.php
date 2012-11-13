<!doctype html>
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Datenbank-Update</TITLE>
	<META NAME="GENERATOR" CONTENT="Eclipse">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../css/format1.css'>
	<link rel="shortcut icon" href="../share/images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>
<DIV Class="klein">

<?php
error_reporting(0);
/*
 * Project: pic2base
 * File: db_update_0604_to_0700_action.php
 * 
 * Umstrukturierung der Datenbank von Version 0.60.4 auf 0.70.0
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

$log_file = "update_0604-0700.log";

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
// Tabelle 'users': 
// 1) Eintraege fuer user_dir, up_dir und down_dir von username auf uid umstellen						OK
// 2) Feldeigenschaft username von varbinary(15) auf varchar(15) aendern								OK
// 3) Spalten berechtigung und note entfernen															OK
// 4) im Ordner /pic2base/userdata alle Ordnerbezeichnungen umbenennen (username → user_id)				OK
// 5) nicht benoetigte Skripte lt. Liste loeschen														OK
//
// Tabelle 'pfade':
// 6) version auf 0.70.0 setzen																			OK
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
<div class='page'>

	<div class='title'>
	<p id='kopf'>pic2base :: Datenbank-Update auf Version 0.70.0</p>
	</div>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>
		</div>
	</div>
	
	<div class='content'>
	<p style='margin:170px 0px; text-align:center'>";

	$error = 0;		//Zaehlvariable fuer Fehlermeldungen
	
//	1)

	$result1 = mysql_query("SELECT * FROM $table1 ORDER BY id");
	$num1 = mysql_num_rows($result1); //echo $num1;
	FOR($i1='0'; $i1<$num1; $i1++)
	{
		
		$user_id = mysql_result($result1, $i1, 'id');
		$username = utf8_encode(mysql_result($result1, $i1, 'username'));
		if($username !== 'pb' AND $user_id !== '1')
		{
			// fuer alle user ausser pb werden die userpfade korrigiert (username -> uid)
			$user_dir = mysql_result($result1, $i1, 'user_dir');
			$up_dir = mysql_result($result1, $i1, 'up_dir');
			$down_dir = mysql_result($result1, $i1, 'down_dir');
			$new_user_dir = str_replace($username, $user_id, $user_dir);
			$new_up_dir = str_replace($username, $user_id, $up_dir);
			$new_down_dir = str_replace($username, $user_id, $down_dir);
			$result2 = mysql_query("UPDATE $table1 SET user_dir='$new_user_dir', up_dir='$new_up_dir', down_dir='$new_down_dir' WHERE id='$user_id'");
			echo mysql_error();
			if(mysql_error() !== "")
			{
				$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
				fwrite($fh,date('d.m.Y H:i:s').": Fehler: Update der Users-Tabelle; Benutzer ".$username.", UID ".$user_id." konnte nicht aktualisiert werden\n");
				fclose($fh);
				$error++;
			}
			else
			{
				$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
				fwrite($fh,date('d.m.Y H:i:s').": Update der Users-Tabelle; Pfade des Benutzers ".$username.", UID ".$user_id." wurden aktualisiert.\n");
				fclose($fh);
			}
		}
	}
	echo "Die Pfadangaben der Userverzeichnisse wurden in der Tabelle 'users' korrigiert.<BR>";

//	2)

	$result2 = mysql_query("ALTER TABLE $table1 CHANGE `berechtigung` `berechtigung` VARCHAR( 15 ) NOT NULL ");
	echo mysql_error();
	if(mysql_error() !== "")
	{
		$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
		fwrite($fh,date('d.m.Y H:i:s').": Fehler: Update der Users-Tabelle; Eigenschaft der Spalte 'username' konnte nicht aktualisiert werden\n");
		fclose($fh);
		$error++;
	}
	else
	{
		$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
		fwrite($fh,date('d.m.Y H:i:s').": Update der Users-Tabelle; Eigenschaft der Spalte 'username' wurde auf varchar(15) aktualisiert.\n");
		fclose($fh);
	}
	echo "Eigenschaft des Feldes 'username' in der Tabelle 'users' wurde korrigiert.<BR>";
	
//	3)

	$fields = mysql_list_fields('pic2base', $table1);
	$soll = 2;												// zwei Felder sollen entfernt werden: berechtigung und note
	$columns = mysql_num_fields($fields);
	for ($i = 0; $i < $columns; $i++) 
	{
		if(mysql_field_name($fields, $i) == 'berechtigung' OR mysql_field_name($fields, $i) == 'note')
		{
			$soll--;
		}
		
	}
	if($soll == 0)
	{
		
		$result3 = mysql_query("ALTER TABLE $table1 DROP `berechtigung`, DROP `note`;");
		if(mysql_error() !== "")
		{
			echo mysql_error();
			$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
			fwrite($fh,date('d.m.Y H:i:s').": Fehler: Update der Users-Tabelle; Spalten 'berechtigung' und/oder 'note' konnten nicht entfernt werden.\n");
			fclose($fh);
			$error++;
		}
		else
		{
			$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
			fwrite($fh,date('d.m.Y H:i:s').": Update der Users-Tabelle; Spalten 'berechtigung' und 'note' wurden entfernt.\n");
			fclose($fh);
		}
		
	}
	else
	{
		$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
		fwrite($fh,date('d.m.Y H:i:s').": Update der Users-Tabelle; Spalten 'berechtigung' und/oder 'note' waren nicht mehr vorhanden.\n");
		fclose($fh);
	}
	echo "&Uuml;berfl&uuml;ssige Spalten wurden aus der Tabelle 'users' entfernt.<BR>";

//	4)
	$result4 = mysql_query("SELECT * FROM $table1 ORDER BY id");
	$num4 = mysql_num_rows($result4);
	FOR($i4=0; $i4<$num4; $i4++)
	{
		$user_id = mysql_result($result4, $i4, 'id');
		$username = utf8_encode(mysql_result($result4, $i4, 'username'));
		$user_dir = mysql_result($result4, $i4, 'user_dir');
		$old_user_dir = str_replace($user_id, $username, $user_dir);
		clearstatcache();
		if(is_dir($old_user_dir))
		{
			if(!rename($old_user_dir, $user_dir))
			{
				$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
				fwrite($fh,date('d.m.Y H:i:s').": Fehler: Das Benutzerverzeichnis ".$old_user_dir." konnte nicht umbenannt werden.\n");
				fclose($fh);
				$error++;
			}
			else
			{
				$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
				fwrite($fh,date('d.m.Y H:i:s').": Das Benutzerverzeichnis ".$old_user_dir." wurde zu ".$user_dir." umbenannt.\n");
				fclose($fh);
			}
		}
		elseif(is_dir($user_dir))
		{
			$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
			fwrite($fh,date('d.m.Y H:i:s').": Das Benutzerverzeichnis ".$user_dir." ist bereits vorhanden.\n");
			fclose($fh);
		}
		elseif(!is_dir($old_user_dir) AND !is_dir($user_dir))
		{
			if($username !== 'pb' AND $user_id !== '1')
			{
				// wenn das Userverzeichnis nicht vorhanden ist, wird es angelegt, nicht aber fuer User 'pb'
				$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
				fwrite($fh,date('d.m.Y H:i:s').": Die Verzeichnisse ".$old_user_dir." und ".$user_dir." existieren nicht!\n");
				fclose($fh);
				$up_dir = $user_dir.'/uploads';
				$down_dir = $user_dir.'/downloads';
				$kml_dir = $user_dir.'/kml_files';
				
				mkdir($user_dir, 0700);
				clearstatcache();
				mkdir($up_dir, 0700);
				clearstatcache();
				mkdir($down_dir, 0700);
				clearstatcache();
				mkdir($kml_dir, 0700);
				clearstatcache();
				
				if(is_dir($user_dir) AND is_dir($up_dir) AND is_dir($down_dir) AND is_dir($kml_dir))
				{
					$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
					fwrite($fh,date('d.m.Y H:i:s').": Die fehlenden User-Verzeichnisse fuer ".$username." / (UID ".$user_id.") wurden angelegt.\n");
					fclose($fh);
				}
				else
				{
					$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
					fwrite($fh,date('d.m.Y H:i:s').": Fehler: Die fehlenden User-Verzeichnisse fuer ".$username." / (UID ".$user_id.") konnten nicht angelegt werden!\n");
					fclose($fh);
					$error++;
				}
			}
		}
	}
	echo "Im Ordner /userdata wurden die Ordnerbezeichnungen korrigiert.<BR>";

//	5)

	$file = array();
	$file[] = $sr.'/bin/admin/admin/generate_exifdata0.php';
	$file[] = $sr.'/bin/admin/admin/generate_histogram0.php';
	$file[] = $sr.'/bin/admin/admin/kat_repair1.php';
	$file[] = $sr.'/bin/admin/admin/kategorie0_0.php';
	$file[] = $sr.'/bin/admin/admin/md5_add.php';
	$file[] = $sr.'/bin/html/admin/prev_image_repair1.php';
	$file[] = $sr.'/bin/html/edit/del_pic.php';
	$file[] = $sr.'/bin/html/edit/edit_desc_daten_action.php';
	$file[] = $sr.'/bin/html/edit/edit_exif_desc.php';
	$file[] = $sr.'/bin/html/edit/edit_kat_daten_action.php';
	$file[] = $sr.'/bin/html/edit/save_desc.php';
	$file[] = $sr.'/bin/html/erfassung/stapel1.php';
	$file[] = $sr.'/bin/html/recherche/abfrage.php';
	$file[] = $sr.'/bin/html/recherche/get_data.php';
	$file[] = $sr.'/bin/html/recherche/switch_bewertung.php';
	$file[] = $sr.'/bin/share/bearbeitung1.php';
	$file[] = $sr.'/bin/share/convert2.php';
	$file[] = $sr.'/bin/share/exif_data1.php';
	$file[] = $sr.'/bin/share/user_check1.php';
	
	foreach($file AS $skriptfile)
	{
		//$skriptfile = $skriptfile."0";
		if(file_exists($skriptfile))
		{
//			echo "Datei ".$skriptfile." ist vorhanden.<BR>";
			if(@unlink($skriptfile))
			{
//				echo "Datei ".$skriptfile." wurde gel&ouml;scht.<BR>";
				$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
				fwrite($fh,date('d.m.Y H:i:s').": Datei ".$skriptfile." wurde geloescht.\n");
				fclose($fh);
			}
			else
			{
//				echo "Datei ".$skriptfile." konnte <b>nicht</b> gel&ouml;scht werden.<BR>";
				$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
				fwrite($fh,date('d.m.Y H:i:s').": Fehler: Datei ".$skriptfile." konnte nicht geloescht werden.\n");
				fclose($fh);
				$error++;
			}
		}
		else
		{
//			echo "Datei ".$skriptfile." ist N I C H T vorhanden.<BR>";
			$fh = fopen($p2b_path.'pic2base/log/'.$log_file,'a');
			fwrite($fh,date('d.m.Y H:i:s').": Datei ".$skriptfile." konnte nicht gefunden werden.\n");
			fclose($fh);
		}
	}
	echo "Das Skript-Verzeichnis wurde von unbenutzten Dateien bereinigt.<BR>";

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
	<br style='clear:both;' />

	<div class='fuss'>
	<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>
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