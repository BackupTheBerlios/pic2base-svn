<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../../index.php');
}

/***********************************************************************************
 * Project: pic2base
 * File: del_kat_action.php
 *
 * Copyright (c) 2006 - 2012 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 ***********************************************************************************/

//var_dump($_GET);
if( array_key_exists('pic_id',$_GET) )
{
	$pic_id = $_GET['pic_id'];
}
if( array_key_exists('kat_id',$_GET) )
{
	$kat_id = $_GET['kat_id'];
}
if( array_key_exists('parent',$_GET) )
{
	$parent = $_GET['parent'];
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

//echo "Bild: ".$pic_id.", Start-Kategorie: ".$kat_id."<BR><BR>";
//echo "zuerst wird Kategorie ".$kat_id." gel&ouml;scht.<BR>";
$result1 = mysql_query("DELETE FROM $table10 WHERE pic_id = '$pic_id' AND kat_id = '$kat_id'");

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
        //echo "Kategorie ".$KA." wird gel&ouml;scht<BR>";
        $result3 = mysql_query("DELETE FROM $table10 WHERE pic_id = '$pic_id' AND kat_id = '$KA'");
       	$KAE = getTree($einzeln['kat_id'],$pic_id);
      } 
      else 
      {
        $KA = $einzeln['kat_id'];
        //echo "zum Schlss: Kategorie ".$KA." wird gel&ouml;scht<BR>";
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

$KA = getTree($kat_id,$pic_id);

//Kontrolle, ob dem Bild noch mindestens eine Kategorie zugewiesen ist, sonst: has_kat = 0
$result6 = mysql_query("SELECT * FROM $table10 WHERE pic_id = '$pic_id'");
$num6 = mysql_num_rows($result6);
//echo "Anzahl der verbleibenden Kategorien: ".$num6;
$exiftool = buildExiftoolCommand($sr);
$FN = strtolower($pic_path."/".restoreOriFilename($pic_id, $sr));
shell_exec($exiftool." -IPTC:Keywords='' -overwrite_original ".$FN);

IF($num6 == '1')
{
	echo "keine Kategorien mehr... ";
	$result6_1 = mysql_query("DELETE FROM $table10 WHERE pic_id = '$pic_id'");
	$result7 = mysql_query("UPDATE $table2 SET has_kat = '0' WHERE pic_id = '$pic_id'");
	echo mysql_error();
}
ELSE
{
	echo "trage die verbleibenden Kategorien ein... ";
	if ( !isset($kw) )
	{
		$kw = '';
	}
	$result8 = mysql_query( "SELECT * FROM $table10 WHERE pic_id = '$pic_id'");
	echo mysql_error();
	@$num8 = mysql_num_rows($result8);
	
	FOR($i8='0'; $i8<$num8; $i8++)
	{
		$kat_id = mysql_result($result8, $i8, 'kat_id');
		IF($kat_id !== '1')
		{
			$result9 = mysql_query( "SELECT kategorie FROM $table4 WHERE kat_id = '$kat_id'");
			$keywords = mysql_result($result9, isset($i9), 'kategorie');
			$command = $exiftool." -IPTC:Keywords+=\"$keywords\" -overwrite_original ".$FN;
			//echo $command;
			shell_exec($command);
			$kw .= $keywords." ";
		}
	}
	$result10 = mysql_query( "UPDATE $table2 SET Keywords = \"$kw\" WHERE pic_id = '$pic_id'");
	echo mysql_error();
}
header("location: edit_remove_kat.php?pic_id=0&mod=kat&kat_id=$parent");
exit();
?>