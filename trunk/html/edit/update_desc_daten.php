<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
  	header('Location: ../../../index.php');
}
else
{
	$uid = $_COOKIE['uid'];
}

/*
 * Project: pic2base
 * File: update_desc_daten_.php
 *
 * Copyright (c) 2005 - 2012 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 */

if(array_key_exists('pic_id',$_GET))
{
	$pic_id = $_GET['pic_id'];
}
else
{
	$pic_id = 0;
}

if(array_key_exists('description',$_GET))
{
	$description = $_GET['description'];
}
else
{
	$description = NULL;
}

//#####################################################################################
//
// Verwendung bei der Bearbeitung / Beschreibung zuweisen (Auswahl nach Kategorien)
// Skript weist jedem Bild (pic_id) die neue Beschreibung zu
// wird aufgerufen bei Bearbeitung | Beschreibung zuweisen ( von edit_beschreibung_action2.php)
//
//#####################################################################################

$error_code = 0;

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
$exiftool = buildExiftoolCommand($sr);

$result0 = mysql_query( "SELECT username FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');

IF ( isset($pic_id) AND count($pic_id) > 0 AND $description !== "")
{
	$bild_id = $pic_id;
	//echo $bild_id." <-Bild_id<BR>";
	$res1 = mysql_query( "SELECT Caption_Abstract FROM $table2 WHERE pic_id = '$bild_id'");
	$row = mysql_fetch_array($res1);
	$desc = $row['Caption_Abstract'];
	echo mysql_error();
	IF ($desc == '')
	{
		$Description = $description;
	}
	ELSE
	{
		$Description =$desc."\n".$description;
	}
	$res3 = mysql_query( "UPDATE $table2 SET Caption_Abstract = '$Description' WHERE pic_id = '$bild_id'");
	IF(!mysql_error())
	{
		//Log-Datei schreiben:
		$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
		fwrite($fh,date('d.m.Y H:i:s').": Beschreibung von Bild ".$bild_id." wurde von ".$username." modifiziert. (Zugriff von ".$_SERVER['REMOTE_ADDR']."\nalt: ".$desc.", neu: ".$Description."\n");
		fclose($fh);
	}

	$FN = $pic_path."/".restoreOriFilename($bild_id, $sr);
	$desc = $Description;
//	shell_exec($exiftool." -IPTC:Caption-Abstract=\"$desc\" ".$FN." -overwrite_original > /dev/null &"); //schnelle Variante, treibt aber den Server an die Grenze!
	shell_exec($exiftool." -IPTC:Caption-Abstract=\"$desc\" ".$FN." -overwrite_original");
			
	IF (mysql_errno() == '0')
	{
		$error_code = 0;
	}
	ELSE
	{
		$error_code = 1;
	}
}
ELSE
{
	$error_code = 2; 
}
mysql_close($conn);

$obj1 = new stdClass();
$obj1->errorCode = $error_code;
$obj1->Userid = $uid;
$output = json_encode($obj1);
echo $output;
?>