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
 * File: update_pic_kategory.php
 *
 * Copyright (c) 2005 - 2013 Klaus Henneberg
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

//#######################################################################################
//
// Verwendung bei der Umsortierung des Kategorie-Baumes (Aufruf von kat_sort_action2.php)
// Skript weist jedem Bild (pic_id) die neuen Kategorien zu
//
//#####################################################################################''

$error_code = 0;

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');
IF ( isset($pic_id) AND count($pic_id) > 0 )
{
	$exiftool = buildExiftoolCommand($sr);
	$FN = strtolower($pic_path."/".restoreOriFilename($pic_id, $sr));
	// alten keywords-Eintrag aus dem Bild entfernen:
	$command = $exiftool." -IPTC:Keywords='' -overwrite_original ".$FN;
	shell_exec($command);
	
	$result13 = mysql_query("SELECT $table10.pic_id, $table10.kat_id, $table4.kat_id, $table4.kategorie
	FROM $table4, $table10
	WHERE $table10.pic_id = '$pic_id'
	AND $table4.kat_id = $table10.kat_id
	AND $table4.kat_id <> '1'");
	$num13 = mysql_num_rows($result13);
	$kategorie = '';
	$keyword_string = '';
	FOR($i13=0; $i13<$num13; $i13++)
	{
		$sel_kategorie = mysql_result($result13, $i13, 'kategorie');
		// Feldinhalt fuer die pictures.Keywords-Spalte generieren:
		$kategorie .= " ".$sel_kategorie;
		//Schluesselworte fuer IPTC:Keywords erzeugen
		$keyword_string.= " -IPTC:Keywords=\"$sel_kategorie\"";
	}
	
	//echo "pictures.Kategorien wird fuer Bild ".$bildnr." mit ".$kategorie." belegt.<BR>";
	$result14 = mysql_query( "UPDATE $table2 SET Keywords = \"$kategorie\", has_kat = '1' WHERE pic_id = '$pic_id'");
	
	//echo "Schluesselworte ins Bild ".$bildnr." schreiben<BR>";
	$command = $exiftool."".$keyword_string." -overwrite_original ".$FN;
	shell_exec($command);
	
	IF (mysql_errno() == '0')
	{
		$error_code = 0;
		// im Erfolgsfall Log-Datei schreiben:
		$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
		fwrite($fh,date('d.m.Y H:i:s').": Kategoriezuordnung von Bild ".$pic_id." wurde von ".$username." modifiziert. (Zugriff von ".$_SERVER['REMOTE_ADDR']."\n(Kategorie-Umsortierung)\n");
		fclose($fh);
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

//mysql_close($conn);

$obj1 = new stdClass();
$obj1->errorCode = $error_code;
$obj1->Userid = $uid;
$output = json_encode($obj1);
echo $output;
?>