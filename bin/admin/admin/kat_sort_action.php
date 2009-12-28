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

<?

/*
 * Project: pic2base
 * File: kat_delete_action.php
 *
 * Copyright (c) 2003 - 2008 Klaus Henneberg
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
list($c_username) = split(',',$_COOKIE['login']);
//echo $c_username;
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$result1 = mysql_query( "SELECT * FROM $table1 WHERE username = '$c_username' AND aktiv = '1'");
$user_id = mysql_result($result1, isset($i1), 'id');
$berechtigung = mysql_result($result1, isset($i1), 'berechtigung');
SWITCH ($berechtigung)
{
	//Admin
	CASE $berechtigung == '1':
	$navigation = "<a class='navi' href='kategorie0.php?kat_id=0'>Zur&uuml;ck</a>";
	break;
}

// für register_globals = off
$kat_source = $_POST['kat_source'];
$kat_dest = $_POST['kat_dest'];

//#####   Entwurf der Kategorie-Neusortierung mit Mitnahme der Unterkategorien der Quellkategorie   ######################
IF($kat_source !== $kat_dest AND $kat_source !== '' AND $kat_source !== NULL AND $kat_dest !== '' AND $kat_dest !== NULL)
{
	//Wenn ein Kategoriezweig umgehängt wird, wird dem Wurzelelement des Zweiges das neue Parent-Elemenz zugewiesen und bei allen Elementen dez Zweiges muß der level aktualisiert werden.
	
	$result1 = mysql_query( "SELECT * FROM $table4 WHERE kat_id = '$kat_source'");
	$source_name = mysql_result($result1, isset($i1), 'kategorie');
	$source_parent = mysql_result($result1, isset($i1), 'parent');
	//$source_level = mysql_result($result1, isset($i1), 'level');
	$result2 = mysql_query( "SELECT * FROM $table4 WHERE kat_id = '$kat_dest'");
	$dest_name = mysql_result($result2, isset($i2), 'kategorie');
	$dest_level = mysql_result($result2, isset($i2), 'level');
	echo "Quell-Kategorie: ".$kat_source." (".$source_name."), Ziel-Kategorie: ".$kat_dest." (".$dest_name.")<BR><BR>";
	
	//Ermittlung aller Unterkategorien unter der Quell-Kategorie:
	//zur Sicherheit Bereinigung der tmp_tree-Tabelle:
	$result3 = mysql_query( "DELETE FROM $table15 WHERE user_id = '$user_id'");
	//Parameter der Quell-Kat. werden in die Tabelle tmp_tree geschrieben:
	$result4 = mysql_query( "INSERT INTO $table15 (kat_id, old_level, kat_name, user_id, new_level, new_parent) VALUES ('$kat_source', '0', '$source_name', '$user_id', '0', '0')");
	echo mysql_error();
	
	//Bestimmung der Unterkategorieen ausgehend von der Quell-Kategorie
	$res1 = mysql_query( "SELECT max(level) FROM $table4");
	$max_level = mysql_result($res1, isset($i1), 'max(level)');
	//echo "max. Level: ".$max_level."<BR>";
	$result5 = mysql_query( "SELECT * FROM $table4 WHERE parent = '$kat_source'");
	$num5 = mysql_num_rows($result5);
	$child_arr[] = $kat_source;
	//$child_arr = array();
	IF($num5 > '0')
	{
		$curr_level = mysql_result($result5, $i5, 'level');
		WHILE($curr_level <= $max_level)
		{
			FOREACH($child_arr AS $child)
			{
				$result6 = mysql_query( "SELECT * FROM $table4 WHERE parent = '$child' AND level = '$curr_level'");
				$num6 = mysql_num_rows($result6);
				IF($num6 > '0')
				{
					FOR($i6='0'; $i6<$num6; $i6++)
					{
						$source_kat_id = mysql_result($result6, $i6, 'kat_id');
						$source_kat_name = mysql_result($result6, $i6, 'kategorie');
						$result7 = mysql_query( "INSERT INTO $table15 (kat_id, old_level, kat_name, user_id, new_level, new_parent) VALUES ('$source_kat_id', '$curr_level', '$source_kat_name', '$user_id', '0', '0')");
						$child_arr[] = $source_kat_id;
					}
				}
			}
			$curr_level++;
		}
	}
	//print_r($child_arr);
	//Bestimmung, über wieviele Ebenen sich der zu übertragende Kategoriezweig erstreckt:
	$result8 = mysql_query( "SELECT * FROM $table15 GROUP BY 'old_level' ORDER BY 'old_level'");
	$num8 = mysql_num_rows($result8);
//	echo $num8." Ebenen<BR>";
	
	//das Wurzelelement des Kategoriezweigs wird neu eingehängt und das neue parent- und level-Element in die tmp-Tabelle eingetragen:
	$result9 = mysql_query( "SELECT * FROM $table15 WHERE old_level = '0'");
	$kat_id = mysql_result($result9, isset($i9), 'kat_id');
	//$kat_name = mysql_result($result9, isset($i9), 'kat_name');
	$new_level = $dest_level + 1;
	$result12 = mysql_query( "UPDATE $table15 SET new_parent = '$kat_dest', new_level = '$new_level' WHERE kat_id = '$kat_id' AND user_id = '$user_id'");
	echo mysql_error();
	
	//die Levelzuordnung aller Zweig-Elemente muß korrigiert werden
	FOR($i8='0'; $i8<$num8; $i8++)
	{
		$old_level = mysql_result($result8, $i8, 'old_level');
		IF($old_level !== '0')
		{
			$result10 = mysql_query( "UPDATE $table15 SET new_level = '$new_level' WHERE old_level = '$old_level' AND user_id = '$user_id'");
		}
		$new_level++;
	}
	
	//Übertragung der Daten aus der tmp-Tabelle in die Kategorie-Tabelle:
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
		//es werden aus der pic_kat-Tabelle alle Verweise zwischen dem betr. Bild und der ehemalig über der Source-Kat stehenden Kategorie entfernt: 
		$result20 = mysql_query( "DELETE FROM $table10 WHERE (kat_id = '$source_parent' AND pic_id = '$pic_id')");
		//es wird ermittelt welches die tiefste zugewiesene Kategorie ist (die mit dem größten level):
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
	//		$level = mysql_result($result16, $i16, 'level');
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
		
		//Bestimmung aller Eltern-Elemente zu der tiefsten Kategorie; gleichzeitig Entfernung der pic_kat-Einträge aus der pic_kat-Tabelle:
		$kat_id = $max_kat;
		$KAT_ID = array();	//Array leeren
		$KAT_ID[] = $kat_id;	//Array mit der tiefsten Kategorie füllen
		WHILE ($kat_id > '1')
		{
			$result17 = mysql_query( "DELETE FROM $table10 WHERE (pic_id = '$pic_id' AND kat_id = '$kat_id')");
			echo mysql_error();
//			echo "Zuordnung zwischen Bild ".$pic_id." und Kategorie ".$kat_id." wurde gelöscht.<BR>";
			$res0 = mysql_query( "SELECT parent FROM $table4 WHERE kat_id='$kat_id'");
			echo mysql_error();
			$row = mysql_fetch_array($res0);
			$kat_id = $row['parent'];
	//		$kat_id = mysql_result($res0, $i0, 'parent');
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
				//	$kategorie = htmlentities(mysql_result($result2, $i2, 'kategorie'))." ".$kategorie;
				}
			}
		}
		
		//Kategorien werden ins Bild geschrieben:
		//Ermittlung des Dateinamens des Originalbildes:
		$FN = strtolower($pic_path."/".restoreOriFilename($pic_id, $sr));
		//echo $FN."<BR>";
		//eintragen der Kategorien in IPTC:Keywords
		shell_exec($et_path."/exiftool -IPTC:Keywords='$kategorie' -overwrite_original ".$FN);
		
		//Aktualisierung des betreffenden Datensatzes in der exif_data Tabelle:
		$result3 = mysql_query( "UPDATE $table14 SET Keywords = '$kategorie' WHERE pic_id = '$pic_id'");
		$result4 = mysql_query( "UPDATE $table2 SET has_kat = '1' WHERE pic_id = '$pic_id'");
	}
	//abschließend wird die tabelle tmp_tree gesäubert:
	$result18 = mysql_query( "DELETE FROM $table15 WHERE user_id = '$user_id'");


	echo "<BR><BR><input type='button' Value='Zurück' onClick='location.href=\"javascript:history.back()\"'>";
}
ELSE
{
	echo "Fehler!<BR><BR>";
	echo "<input type='button' Value='Zurück' onClick='location.href=\"javascript:history.back()\"'>";
}
//#####   Ende   ##########################################################################################################
//bisheriger Code (ohne Mitnahme der Unterkategorien der Quell-Kategorie)
/*
IF($kat_source !== $kat_dest AND $kat_source !== '' AND $kat_source !== NULL AND $kat_dest !== '' AND $kat_dest !== NULL)
{
	//Ausgehend von der gewählten Kategorie müssen alle Unterkategorien und Parent-Kategorien bis in die Wurzel bestimmt werden. Dann müssen aus der Tabelle 10 (pic_kat) alle Einträge gelöscht werden, welche auf die gewählte Kategorie bzw. deren Unter- und Parentkategorie verweisen.
	//Bestimmung der Unterkategorieen ausgehend von der Quell-Kategorie
	$res1 = mysql_query( "SELECT max(level) FROM $table4");
	$max_level = mysql_result($res1, $i1, 'max(level)');
	//echo "max. Level: ".$max_level."<BR>";
	$result2 = mysql_query( "SELECT * FROM $table4 WHERE parent = '$kat_source'");
	$num2 = mysql_num_rows($result2);
	$child_arr[] = $kat_source;
	//$child_arr = array();
	IF($num2 > '0')
	{
		$curr_level = mysql_result($result2, $i2, 'level');
		WHILE($curr_level <= $max_level)
		{
			FOREACH($child_arr AS $child)
			{
				$result3 = mysql_query( "SELECT * FROM $table4 WHERE parent = '$child' AND level = '$curr_level'");
				$num3 = mysql_num_rows($result3);
				IF($num3 > '0')
				{
					FOR($i3='0'; $i3<$num3; $i3++)
					{
						$child_arr[] = mysql_result($result3, $i3, 'kat_id');	
					}
				}
			}
			$curr_level++;
		}
	}
	//Bestimmung der Parentkategorieen ausgehend von der Quell-Kategorie
	$kat_source_parent_arr[] = $kat_source;
	$result9 = mysql_query( "SELECT * FROM $table4 WHERE kat_id = '$kat_source'");
	$parent = mysql_result($result9, $i9, 'parent');
	//echo $parent."<BR>";
	
	IF($parent !=='')
	{
		$kat_source_parent_arr[] = $parent;
	}
	
	WHILE($parent > '0')
	{
		$result10 = mysql_query( "SELECT * FROM $table4 WHERE kat_id = '$parent'");
		$num10 = mysql_num_rows($result10);
		FOR($i10='0'; $i10<$num10; $i10++)
		{
			$parent = mysql_result($result10, $i10, 'parent');
			IF($parent > '0' AND $parent !=='')
			{
				$kat_source_parent_arr[] = $parent;
			}
		}	
	}
	
	$pic_arr = array();
	FOREACH($child_arr AS $child)
	{
		$res3 = mysql_query( "SELECT * FROM $table4 WHERE kat_id = '$child'");
		$u_kategorie .= mysql_result($res3, $i, 'kategorie')."<BR>";
		//echo $child."<BR>";			//$child - Nummer der zu löschenden Kategorie
		//Löschvorgang der Kategorie in der Kategorie-Tabelle ($table4):  ###########################################
		$result4 = mysql_query( "DELETE FROM $table4 WHERE kat_id = '$child'");
		
		$res4 = mysql_query( "SELECT * FROM $table10 WHERE kat_id = '$child'");
		$num4 = mysql_num_rows($res4); 
		FOR($i4='0'; $i4<$num4; $i4++)
		{
			$pic_id = mysql_result($res4, $i4, 'pic_id');
			IF(!in_array($pic_id, $pic_arr))
			{
				$pic_arr[] = $pic_id;
			}
		}
	}
	
	//löschen der Bild-Zuordnungen in der pic_kat-Tabelle:
	//dazu werden zunächst das Child- und das Parent-Array zusammengefügt, um alle Kategorieverweise ober- und unterhalb der Quell-Kategorie aufzuheben:
	$kat_del_arr = array_merge($child_arr,$kat_source_parent_arr);
	
	FOREACH($pic_arr AS $pic)
	{
		FOREACH($kat_del_arr AS $DEL)
		{
			$result5 = mysql_query( "DELETE FROM $table10 WHERE pic_id = '$pic' AND kat_id = '$DEL'"); //###############
		}
	}
	
	//alle Bilder aus der bisherigen Kategorie werden der neuen Kategorie zugeordnet:
	//zunächst: Bestimmung der parent-Kategorien zur neuen Ziel-Kategorie:
	$kat_dest_arr[] = $kat_dest;
	$result6 = mysql_query( "SELECT * FROM $table4 WHERE kat_id = '$kat_dest'");
	$parent = mysql_result($result6, $i6, 'parent');
	//echo $parent."<BR>";
	IF($parent !=='')
	{
		$kat_dest_arr[] = $parent;
	}
	WHILE($parent > '0')
	{
		$result7 = mysql_query( "SELECT * FROM $table4 WHERE kat_id = '$parent'");
		$num7 = mysql_num_rows($result7);
		FOR($i7='0'; $i7<$num7; $i7++)
		{
			$parent = mysql_result($result7, $i7, 'parent');
			IF($parent > '0' AND $parent !=='')
			{
				$kat_dest_arr[] = $parent;
			}
		}	
	}

	FOREACH($pic_arr AS $pic)
	{
		$kategorie = '';
		$kat_arr = array();
		$kat_arr = array_splice($kat_arr,0);		//Array zur Initialisierung geleert
		FOREACH($kat_dest_arr AS $kat)
		{
			//unterdrückung doppelter Einträge, wenn das Bild schon in der Kategorie ist:
			$result8 = mysql_query( "SELECT * FROM $table10 WHERE pic_id = '$pic' AND kat_id = '$kat'");
			IF(mysql_num_rows($result8) == '0')
			{
				//echo "Bild ".$pic." wurde der Kategorie ".$kat." zugeordnet.<BR>";
				$result9 = mysql_query( "INSERT INTO $table10 (pic_id, kat_id) VALUES ('$pic', '$kat')");//###########
			}
			IF($kat !== '1')
			{
				$result11 = mysql_query( "SELECT * FROM $table4 WHERE kat_id = '$kat'");
				$kat_arr[] = htmlentities(mysql_result($result11, $i11, 'kategorie'));
			}
		}
		//Aktualisierung der Keywords in den EXIF-Daten des betreffenden Bildes:
		$FN = strtolower($pic_path."/".restoreOriFilename($pic, $sr));
		FOREACH($kat_arr AS $kat_name)
		{
			$kategorie .= $kat_name." "; 
		}
		//echo "Kategorien: ".$kategorie.", Dateiname: ".$FN."<BR>"; //Liste aller zugewiesenen Kategorien
		shell_exec($et_path."/exiftool -IPTC:Keywords='$kategorie' ".$FN);
	}	
	
	echo "
	<div class='page'>
	
		<p id='kopf'>pic2base :: Admin-Bereich - Kategorie neu sortieren</p>
		
		<div class='navi' style='clear:right;'>
			<div class='menucontainer'>". $navigation."</div>
		</div>
		
		<div class='content'>
		<p style='margin:20px 0px; text-align:center'>
		<font style='font-size:12pt; text-decoration:underline'>Protokoll der Neu-Sortierung</font><BR><BR>
		Die Quell-Kategorie-ID war: ".$kat_source."<BR>
		Die Ziel-Kat-ID war: ".$kat_dest."<BR><BR>
		Die folgenden Kategorien wurden gel&ouml;scht:<BR><BR>".$u_kategorie."<BR><BR>".
		count($pic_arr)." Bildzuordnung(en) wurde(n) aktualisiert.</p>
		</div>
		<br style='clear:both;' />
	
		<p id='fuss'><?php echo $cr; ?></p>
	
	</div>";
}
ELSE
{
	echo "
	<div class='page'>
	
		<p id='kopf'>pic2base :: Admin-Bereich - Kategorie neu sortieren</p>
		
		<div class='navi' style='clear:right;'>
			<div class='menucontainer'>". $navigation."</div>
		</div>
		
		<div class='content'>
		<p style='margin:80px 0px; text-align:center'>
		<font style='font-size:14pt; color:red'>Es ist ein Problem aufgetreten.</font><BR><BR>
		Die Ziel-Kategorie darf <u>nicht gleich</u> der Ausgangs-Kategorie sein<BR>
		und <u>beide</u> Kategorien m&uuml;ssen ausgew&auml;hlt sein.</p>
		</div>
		<br style='clear:both;' />
	
		<p id='fuss'><?php echo $cr; ?></p>
	
	</div>";
}
*/
mysql_close($conn);
?>
</DIV>
</CENTER>
</BODY>
</HTML>