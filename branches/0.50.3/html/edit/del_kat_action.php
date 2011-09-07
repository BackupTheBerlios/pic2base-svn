<?php
IF (!$_COOKIE['login'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../../index.php');
}

/***********************************************************************************
 * Project: pic2base
 * File: del_kat_action.php
 *
 * Copyright (c) 2006 - 2011 Klaus Henneberg
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
 
delChildKat($kat_id, $pic_id);

function delChildKat($kat_id, $pic_id)
{
	$kat_id1 = $kat_id;
	include '../../share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	//Ausgehend von der zu loeschenden Kategorie wird ermittelt, welch Child-Kategorien diese besitzt und welche davon wurde dem Bild zugewiesen:
	
	global $kat_ids;
	$kat_ids[] = $kat_id;
	function getChildIDs($kat_id, $kat_ids)
	{
		global $kat_ids;
		include '../../share/global_config.php';
		include $sr.'/bin/share/db_connect1.php';
		$result2 = mysql_query( "SELECT * FROM $table4 WHERE parent = '$kat_id'");
		$num2 = mysql_num_rows($result2);
		
		IF ($num2 > '0')
		{
			FOR ($i2=0; $i2<$num2; $i2++)
			{
				$kat_id = mysql_result($result2, $i2, 'kat_id');
				IF (!in_array($kat_id, $kat_ids))
				{
				$kat_ids[] = $kat_id;
				}
			}
		}
		RETURN $kat_ids;
	}
	
	FOREACH($kat_ids as $kat_id)
	{
		//echo "bestimme Child-Element vom Element ".$kat_id."<BR>";
		getChildIDs($kat_id, $kat_ids);
	}
	//echo "Anz. Child-Elemente: ".count(getChildIDs($kat_id, $kat_ids))."<BR>";
	//Wenn alle betreffenden child-ids ermittelt sind, werden die betreffenden Datensaetze aus der Tabelle pic_kat entfernt:
	FOREACH(getChildIDs($kat_id, $kat_ids) as $kat_id)
	{
		//echo $kat_id."&#160;&#160;";
		$result4 = mysql_query( "DELETE FROM $table10 WHERE (pic_id = '$pic_id' AND kat_id = '$kat_id') OR (pic_id = '$pic_id' AND kat_id = '$kat_id1')");
		//Wenn die letzten Kategorie-Zuweisungen aufgehoben wurden, wird auch die Verknuepfung zur Ersten Ebene (Wurzel) aufgehoben sowie der Vermerk has_kat in der pictures-Tabelle auf 0 gesetzt:
		$result5 = mysql_query( "SELECT * FROM $table10 WHERE pic_id = '$pic_id'");
		$num5 = mysql_num_rows($result5);
		IF ($num5 == '1')
		{
			$result6 = mysql_query( "DELETE FROM $table10 WHERE pic_id = '$pic_id' AND kat_id = '1'");
			$result10 = mysql_query( "UPDATE $table2 SET has_kat = '0' WHERE pic_id = '$pic_id'");
		}
	}	
}

//Update der exif-daten-Tabelle;
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$exiftool = buildExiftoolCommand($sr);

if ( !isset($kw) )
{
	$kw = '';
}
$FN = strtolower($pic_path."/".restoreOriFilename($pic_id, $sr));
shell_exec($exiftool." -IPTC:Keywords='' -overwrite_original ".$FN." > /dev/null &");
$result7 = mysql_query( "SELECT * FROM $table10 WHERE pic_id = '$pic_id'");
echo mysql_error();
$num7 = mysql_num_rows($result7);
FOR($i7='0'; $i7<$num7; $i7++)
{
	$kat_id = mysql_result($result7, $i7, 'kat_id');
	IF($kat_id !== '1')
	{
		$result8 = mysql_query( "SELECT kategorie FROM $table4 WHERE kat_id = '$kat_id'");
		$keywords = mysql_result($result8, isset($i8), 'kategorie');
		$kw .= $keywords.", ";
		shell_exec($exiftool." -IPTC:Keywords+='$keywords' ".$FN." > /dev/null &");
	}
}
//echo $kw;
$result9 = mysql_query( "UPDATE $table14 SET Keywords = '$kw' WHERE pic_id = '$pic_id'");
//echo mysql_error();
header("location: edit_remove_kat.php?pic_id=0&mod=kat&kat_id=$parent");
exit();
?>