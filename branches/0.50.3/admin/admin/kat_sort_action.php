<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Kategorie-Sortierung</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: kat_sort_action.php
 *
 * Copyright (c) 2003 - 2010 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 */

unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
	//echo $c_username;
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/permissions.php';
include $sr.'/bin/share/functions/main_functions.php';

IF(hasPermission($c_username, 'editkattree'))
{
	$navigation = "<a class='navi' href='kategorie0.php?kat_id=0'>Zur&uuml;ck</a>";
}

@$kat_source = $_POST['kat_source'];
@$kat_dest = $_POST['kat_dest'];

$exiftool = buildExiftoolCommand($sr);

//##############  Kategorie-Neusortierung mit Mitnahme der Unterkategorien der Quellkategorie   ######################
IF($kat_source !== $kat_dest AND $kat_source !== '' AND $kat_source !== NULL AND $kat_dest !== '' AND $kat_dest !== NULL)
{
	//Wenn ein Kategoriezweig umgehaengt wird, wird dem Wurzelelement des Zweiges das neue Parent-Element zugewiesen und bei allen Elementen dez Zweiges muss der level aktualisiert werden.
	
	$result1 = mysql_query( "SELECT * FROM $table4 WHERE kat_id = \"$kat_source\"");
	$source_name = mysql_result($result1, isset($i1), 'kategorie');
	$source_parent = mysql_result($result1, isset($i1), 'parent');
	//$source_level = mysql_result($result1, isset($i1), 'level');
	$result2 = mysql_query( "SELECT * FROM $table4 WHERE kat_id = \"$kat_dest\"");
	$dest_name = mysql_result($result2, isset($i2), 'kategorie');
	$dest_level = mysql_result($result2, isset($i2), 'level');
	echo "Quell-Kategorie: ".$kat_source." (".$source_name."), Ziel-Kategorie: ".$kat_dest." (".$dest_name.")<BR><BR>";
	
	//Ermittlung aller Unterkategorien unter der Quell-Kategorie:
	//zur Sicherheit Bereinigung der tmp_tree-Tabelle:
	$result3 = mysql_query( "DELETE FROM $table15 WHERE user_id = '$user_id'");
	//Parameter der Quell-Kat. werden in die Tabelle tmp_tree geschrieben:
	$result4 = mysql_query( "INSERT INTO $table15 (kat_id, old_level, kat_name, user_id, new_level, new_parent) VALUES (\"$kat_source\", '0', \"$source_name\", '$user_id', '0', '0')");
	echo mysql_error();
	
	//Bestimmung der Unterkategorieen ausgehend von der Quell-Kategorie
	$res1 = mysql_query( "SELECT max(level) FROM $table4");
	$max_level = mysql_result($res1, isset($i1), 'max(level)');
//	echo "max. Level: ".$max_level."<BR>";
	$result5 = mysql_query( "SELECT * FROM $table4 WHERE parent = \"$kat_source\"");
	$num5 = mysql_num_rows($result5);
	$child_arr[] = $kat_source;
	//$child_arr = array();
	IF($num5 > '0')
	{
		$curr_level = mysql_result($result5, isset($i5), 'level');
		WHILE($curr_level <= $max_level)
		{
			FOREACH($child_arr AS $child)
			{
				$result6 = mysql_query( "SELECT * FROM $table4 WHERE parent = \"$child\" AND level = '$curr_level'");
				$num6 = mysql_num_rows($result6);
				IF($num6 > '0')
				{
					FOR($i6='0'; $i6<$num6; $i6++)
					{
						$source_kat_id = mysql_result($result6, $i6, 'kat_id');
						$source_kat_name = mysql_result($result6, $i6, 'kategorie');
						$result7 = mysql_query( "INSERT INTO $table15 (kat_id, old_level, kat_name, user_id, new_level, new_parent) VALUES ('$source_kat_id', '$curr_level', \"$source_kat_name\", '$user_id', '0', '0')");
						$child_arr[] = $source_kat_id;
					}
				}
			}
			$curr_level++;
		}
	}
	//print_r($child_arr);
	//Bestimmung, ueber wieviele Ebenen sich der zu uebertragende Kategoriezweig erstreckt:
	$result8 = mysql_query( "SELECT * FROM $table15 GROUP BY 'old_level' ORDER BY 'old_level'");
	$num8 = mysql_num_rows($result8);
//	echo $num8." Ebenen<BR>";
	
	//das Wurzelelement des Kategoriezweigs wird neu eingehaengt und das neue parent- und level-Element in die tmp-Tabelle eingetragen:
	$result9 = mysql_query( "SELECT * FROM $table15 WHERE old_level = '0'");
	$kat_id = mysql_result($result9, isset($i9), 'kat_id');
	$new_level = $dest_level + 1;
	$result12 = mysql_query( "UPDATE $table15 SET new_parent = \"$kat_dest\", new_level = '$new_level' WHERE kat_id = '$kat_id' AND user_id = '$user_id'");
	echo mysql_error();
	
	//die Levelzuordnung aller Zweig-Elemente muss korrigiert werden
	FOR($i8='0'; $i8<$num8; $i8++)
	{
		$old_level = mysql_result($result8, $i8, 'old_level');
		IF($old_level !== '0')
		{
			$result10 = mysql_query( "UPDATE $table15 SET new_level = '$new_level' WHERE old_level = '$old_level' AND user_id = '$user_id'");
		}
		$new_level++;
	}
	
	//Uebertragung der Daten aus der tmp-Tabelle in die Kategorie-Tabelle:
	$result11 = mysql_query( "SELECT * FROM $table15 WHERE user_id = '$user_id'");
	$num11 = mysql_num_rows($result11);
	FOR($i11='0'; $i11<$num11; $i11++)
	{
		$kat_id = mysql_result($result11, $i11, 'kat_id');
		$new_level = mysql_result($result11, $i11, 'new_level');
		$new_parent = mysql_result($result11, $i11, 'new_parent');
		IF($new_parent !== '0')
		{
			$result13 = mysql_query( "UPDATE $table4 SET level = '$new_level', parent = '$new_parent' WHERE kat_id = '$kat_id'");
		}
		ELSEIF($new_parent == '0')
		{
			$result13 = mysql_query( "UPDATE $table4 SET level = '$new_level' WHERE kat_id = '$kat_id'");
		}
	}
	
//###########################   Umstrukturierung des Kategoriebaumes abgeschlossen   #######################################	

	//Aktualisierung der Meta-Daten (im Bild und in der meta_data-Tabelle:
	//Ermittlung aller Bilder, die von der Umstrukturierung betroffen sind:
	$result14 = mysql_query( "SELECT * FROM $table10 WHERE kat_id = '$kat_source'");
	$num14 = mysql_num_rows($result14);
	echo "Betroffene Bilder:<BR>";
	FOR($i14='0'; $i14<$num14; $i14++)
	{
		$pic_id = mysql_result($result14, $i14, 'pic_id');
		echo "Bild ".$pic_id."<BR>";
		//es werden aus der pic_kat-Tabelle alle Verweise zwischen dem betr. Bild und der ehemalig ueber der Source-Kat stehenden Kategorie entfernt: 
		$result20 = mysql_query( "DELETE FROM $table10 WHERE (kat_id = '$source_parent' AND pic_id = '$pic_id')");
		//es wird ermittelt welches die tiefste zugewiesene Kategorie ist (die mit dem groessten level):
		$result15 = mysql_query( "SELECT * FROM $table10 WHERE pic_id = '$pic_id'");
		echo mysql_error();
		$num15 = mysql_num_rows($result15);
		$max_level = '0';
		$max_kat = '0';
		FOR($i15='0'; $i15<$num15; $i15++)
		{
			$kat_id = mysql_result($result15, $i15, 'kat_id');
			$result16 = mysql_query( "SELECT * FROM $table4 WHERE kat_id = '$kat_id'");
			echo mysql_error();
			$row = mysql_fetch_array($result16);
			$level = $row['level'];
			$kat_name = $row['kategorie'];			
			$level = $row['level'];
	//		$kat_name = mysql_result($result16, $i16, 'kategorie');
//			echo "Kategorie: ".$kat_id.", Level: ".$level."<BR>";
			IF($level > $max_level)
			{
				$max_level = $level;
				$max_kat = $kat_id;
				$max_kat_name = $kat_name;
			}
		}
//		echo "Bild: ".$pic_id.", tiefste Kategorie: ".$max_kat.", (".$max_kat_name.") max. Level: ".$max_level."<BR>";
		
		//Bestimmung aller Eltern-Elemente zu der tiefsten Kategorie; gleichzeitig Entfernung der pic_kat-Eintraege aus der pic_kat-Tabelle:
		$kat_id = $max_kat;
		$KAT_ID = array();	//Array leeren
		$KAT_ID[] = $kat_id;	//Array mit der tiefsten Kategorie fuellen
		WHILE ($kat_id > '1')
		{
			$result17 = mysql_query( "DELETE FROM $table10 WHERE (pic_id = '$pic_id' AND kat_id = '$kat_id')");
			echo mysql_error();
//			echo "Zuordnung zwischen Bild ".$pic_id." und Kategorie ".$kat_id." wurde geloescht.<BR>";
			$res0 = mysql_query( "SELECT parent FROM $table4 WHERE kat_id='$kat_id'");
			echo mysql_error();
			$row = mysql_fetch_array($res0);
			$kat_id = $row['parent'];
			$KAT_ID[]=$kat_id; //$KAT_ID: Array mit den Eltern-Elementen des betreffenden Bildes
		}
		//print_r($KAT_ID);
		//Neuzuweisung der Kategorien zum Bild
		$kategorie = '';
		FOREACH ($KAT_ID AS $kat_id)
		{
			$res0 = mysql_query( "SELECT * FROM $table10 WHERE pic_id = '$pic_id' AND kat_id = '$kat_id'");
			IF (mysql_num_rows($res0) == '0')
			{
				$res1 = mysql_query( "INSERT INTO $table10 (pic_id, kat_id) VALUES ('$pic_id', '$kat_id')");
				IF($kat_id !== '1')
				{
					//Ermittlung aller Kategorien:
					$result2 = mysql_query( "SELECT kategorie FROM $table4 WHERE kat_id = '$kat_id'");
					//echo mysql_error()."<BR>";
					$row = mysql_fetch_array($result2);
					$kategorie = htmlentities($row['kategorie'])." ".$kategorie;
				}
			}
		}
		
		//Kategorien werden ins Bild geschrieben:
		//Ermittlung des Dateinamens des Originalbildes:
		$FN = strtolower($pic_path."/".restoreOriFilename($pic_id, $sr));
		//echo $FN."<BR>";
		//eintragen der Kategorien in IPTC:Keywords
		$command = $exiftool." -IPTC:Keywords=\"$kategorie\" -overwrite_original ".$FN." > /dev/null &";
		//echo $command."<BR>";
		shell_exec($command);
		
		//Aktualisierung des betreffenden Datensatzes in der exif_data Tabelle:
		$result3 = mysql_query( "UPDATE $table14 SET Keywords = \"$kategorie\" WHERE pic_id = '$pic_id'");
		$result4 = mysql_query( "UPDATE $table2 SET has_kat = '1' WHERE pic_id = '$pic_id'");
	}
	//abschliessend wird die tabelle tmp_tree gesaeubert:
	$result18 = mysql_query( "DELETE FROM $table15 WHERE user_id = '$user_id'");
	echo "<BR><BR><input type='button' Value='Zur&uuml;ck' onClick='location.href=\"javascript:history.back()\"'>";
}
ELSE
{
	echo "<p style='color:yellow;'>Es ist ein Fehler aufgetreten!<BR><BR>
	Sie m&uuml;ssen eine Quell- und eine Zielkategorie ausw&auml;hlen!</style><BR><BR>";
	echo "<input type='button' Value='Zur&uuml;ck' onClick='location.href=\"javascript:history.back()\"'>";
}
//#####   Ende   ##########################################################################################################

mysql_close($conn);
?>
</DIV>
</CENTER>
</BODY>
</HTML>