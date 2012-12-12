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
 * File: update_kat_remove_.php
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

if(array_key_exists('kat_id',$_GET))
{
	$kat_id = $_GET['kat_id'];
}
else
{
	$kat_id = NULL;
}

if(array_key_exists('mod',$_GET))
{
	$mod = $_GET['mod'];
}
else
{
	$mod = 0;
}
//echo "Bild-ID: ".$pic_id.", Kat-ID: ".$kat_id.", Mod.: ".$mod."<BR>";
//#####################################################################################
//
// Verwendung bei der Bearbeitung / Kategoriezuweisung aufheben (Auswahl nach Kategorien)
// Skript entfernt bei jedem Bild (pic_id) die gewaehlte Kategorien (kat_id)
// wird aufgerufen bei Bearbeitung | Kategorie-Zuweisung aufheben (von remove_kat_daten_action2.php)
//
//#####################################################################################

$error_code = 0;

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');

$exiftool = buildExiftoolCommand($sr);

//++++++++++++++++++++++++++++++++++++++++++++++++++

//HINWEIS: Wenn eine Kategorie entfernt werden soll, werden gleichzeitig alle Child-Kategorien mit entfernt...

//++++++++++++++++++++++++++++++++++++++++++++++++++

IF ( isset($pic_id) AND count($pic_id) > 0 AND count($kat_id) > 0)
{


	function getTree($kat_id,$pic_id) 
	{
	    include '../../share/global_config.php';
		include $sr.'/bin/share/db_connect1.php';
		$result2 = mysql_query("SELECT kat_id, kategorie FROM $table4 WHERE parent='".$kat_id."' ORDER BY kategorie");
	    while($einzeln = @mysql_fetch_assoc($result2)) 
	    {
	      if(hasChildKats($einzeln['kat_id'],$pic_id)) 
	      {
	        $KA = $einzeln['kat_id'];
	        $result3 = mysql_query("DELETE FROM $table10 WHERE pic_id = '$pic_id' AND kat_id = '$KA'");
	       	$KAE = getTree($einzeln['kat_id'],$pic_id);
	      } 
	      else 
	      {
	        $KA = $einzeln['kat_id'];
	        $result4 = mysql_query("DELETE FROM $table10 WHERE pic_id = '$pic_id' AND kat_id = '$KA'");
	      }
	    }
	}
	
	function hasChildKats($katID,$pic_id) 
	{
	    include '../../share/global_config.php';
		include $sr.'/bin/share/db_connect1.php';
		$result5 = mysql_query("SELECT kat_id FROM $table4 WHERE parent='".$katID."'");
	    if(mysql_num_rows($result5)>0) return true; else return false;
	}		
			
//	########################################
	
	$result1 = mysql_query("DELETE FROM $table10 WHERE pic_id = '$pic_id' AND kat_id = '$kat_id'");
	echo mysql_error();

	$KA = getTree($kat_id,$pic_id);
	
	//Kontrolle, ob dem Bild noch mindestens eine Kategorie zugewiesen ist, sonst: has_kat = 0
	$result6 = mysql_query("SELECT * FROM $table10 WHERE pic_id = '$pic_id'");
	$num6 = mysql_num_rows($result6);

	$FN = strtolower($pic_path."/".restoreOriFilename($pic_id, $sr));
	shell_exec($exiftool." -IPTC:Keywords='' -overwrite_original ".$FN);
	
	IF($num6 == '1')
	{
		$result6_1 = mysql_query("DELETE FROM $table10 WHERE pic_id = '$pic_id'");
		$result7 = mysql_query("UPDATE $table2 SET has_kat = '0' WHERE pic_id = '$pic_id'");
		echo mysql_error();
	}
	ELSE
	{
		if ( !isset($kw) )
		{
			$kw = '';
		}
		$result8 = mysql_query( "SELECT * FROM $table10 WHERE pic_id = '$pic_id'");
		echo mysql_error();
		@$num8 = mysql_num_rows($result8);
		
		FOR($i8='0'; $i8<$num8; $i8++)
		{
			$KAT_ID = mysql_result($result8, $i8, 'kat_id');
			IF($KAT_ID !== '1')
			{
				$result9 = mysql_query( "SELECT kategorie FROM $table4 WHERE kat_id = '$KAT_ID'");
				$keywords = mysql_result($result9, isset($i9), 'kategorie');
				$command = $exiftool." -IPTC:Keywords+=\"$keywords\" -overwrite_original ".$FN;
				shell_exec($command);
				$kw .= $keywords." ";
			}
		}
		
		//Log-Datei schreiben:
		$result10 = mysql_query("SELECT Keywords, pic_id FROM $table2 WHERE pic_id = '$pic_id'");
		$kategorie_alt = mysql_result($result10, isset($i10), 'Keywords');
		$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
		fwrite($fh,date('d.m.Y H:i:s').": Kategoriezuordnung von Bild ".$pic_id." wurde von ".$username." modifiziert. (Zugriff von ".$_SERVER['REMOTE_ADDR']."\nalt: ".$kategorie_alt.", neu: ".$kw."\n");
		fclose($fh);
		
		$result11 = mysql_query( "UPDATE $table2 SET Keywords = \"$kw\" WHERE pic_id = '$pic_id'");
		echo mysql_error();	
	}

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
$obj1->mod = $mod;
$output = json_encode($obj1);
echo $output;
?>