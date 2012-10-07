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
 * Copyright (c) 2003 - 2012 Klaus Henneberg
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
echo "<div id='blend' style='display:block; z-index:99;'>
<p style='color:white; position:absolute; top:100px; left:400px; z-index:102;' />Die &Auml;nderungen werden ausgef&uuml;hrt, bitte warten Sie...</p>
</div>";

ob_flush();

flush();

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/permissions.php';
include $sr.'/bin/share/functions/main_functions.php';

IF(hasPermission($c_username, 'editkattree', $sr))
{
	$navigation = "<a class='navi' href='kategorie0.php?kat_id=0'>Zur&uuml;ck</a>";
}

// ~~~~~~  Skript zur Neustrukturierung des Kategoriebaumes, wenn eine Kategorie umgehaengt werden soll  ~~~~~~~~~~~~~~~~~~~~~~~
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~  Was passiert?  ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// Tabelle 4 (Kategorien) betreffend:
// 1) Ermittlung, wo die Kategorie bisher hang und wohin sie umgehaengt werden soll
// 2) aus der Tabelle 4 (kategorien) sind die zu loeschenden Kategoriezuordnungen vom old_parent bis 
// einschliesslich der Wurzelkategorie (1) zu bestimmen und in das Array $kat_del[] zu schreiben
// 3) aus der Tabelle 4 (kategorien) sind die neu zuzuordnenden Kategorien vom kat_dest bis 
// einschliesslich der Wurzelkategorie (1) zu bestimmen und in das Array $kat_add[] zu schreiben
// 4) der umzuhaengenden Kategorie ist das neue Parent-Element zuzuordnen, sein neuer level ist zu bestimmen und allen
// Elementen im umzuhaengenden Zweig sind die daraus folgenden neuen Level zuzuordnen (Aktualisierung der Tabelle 4: kategorien)
//
// Tabelle 10 (pic_kat) betreffend
// 5) aus der Tabelle 10 (pic_kat) sind fuer alle betreffenden Bilder die Kategoriezuordnungen vom old_parent
// bis einschliesslich der Wurzelkategorie (1) zu entfernen 
// 6) Die Tabelle 10 (pic_kat) ist fuer alle betreffenden Bilder mit den neuen Kategoriezuordnungen jeweils von der kat_dest
// bis zur Wurzel (1) zu aktualisieren
//
// Tabelle 2 (pictures) betreffend
// 7) Die Tabelle 2 (pictures), Feld Keywords fuer jedes Bild ist entsprechend der aktuellen Kategoriezuweisung zu aktualisieren
// 8) Bei jedem Bild ist das Tag IPTC:keywords entsprechend der aktuellen Kategoriezuweisung zu aktualisieren
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

// 1)

@$kat_source = $_POST['kat_source'];		// diese Kategorie soll samt aller unterkategorien umgehaengt werden
@$kat_dest = $_POST['kat_dest'];			// unter diese Kategorie soll der Zweig eingehaengt werden
$exiftool = buildExiftoolCommand($sr);
$result0 = mysql_query("SELECT id FROM $table1 WHERE username = '$c_username'");
$user_id = mysql_result($result0, isset($i0), 'id');

IF($kat_source !== $kat_dest AND $kat_source !== '' AND $kat_source !== NULL AND $kat_dest !== '' AND $kat_dest !== NULL)
{
	$result1 = mysql_query("SELECT kategorie, parent, level FROM $table4 WHERE kat_id = '$kat_source'");
	$source_name = mysql_result($result1, isset($i1), 'kategorie');
	$old_parent = mysql_result($result1, isset($i1), 'parent'); //unterhalb dieser Kategorie war die umzuhaengende Kat. bisher eingehaengt
	$old_level = mysql_result($result1, isset($i1), 'level');
	
//	echo "Benutzer: ".$user_id."<BR>";
//	echo "umzuh&auml;ngende Kategorie: ".$kat_source."<BR>";
//	echo "Diese Kategorie war bisher unter ".$old_parent." eingeh&auml;ngt.<BR>";
//	echo "Sie soll neu unter Kat. ".$kat_dest." eingeh&auml;ngt werden.<BR><BR>";
	
// 2)
	$kat_id1 = $old_parent;
	$kat_del = array();		//Array leeren
	$kat_del[] = $kat_id1;	//Array mit dem Startwert fuellen
	WHILE ($kat_id1 > '1')
	{
		$result3 = mysql_query( "SELECT parent FROM $table4 WHERE kat_id='$kat_id1'");
		echo mysql_error();
		$row = mysql_fetch_array($result3);
		$kat_id1 = $row['parent'];
		$kat_del[]=$kat_id1;
	}
//	echo "Diese Kategoriezuordnungen muessen aufgehoben werden:<BR>";
//	print_r($kat_del);
//	echo "<BR><BR>";
	
// 3)
	$kat_id2 = $kat_dest;
	$kat_add = array();		//Array leeren
	$kat_add[] = $kat_id2;	//Array mit dem Startwert fuellen
	WHILE ($kat_id2 > '1')
	{
		$result4 = mysql_query( "SELECT parent FROM $table4 WHERE kat_id='$kat_id2'");
		echo mysql_error();
		$row = mysql_fetch_array($result4);
		$kat_id2 = $row['parent'];
		$kat_add[]=$kat_id2;
	}
//	echo "Diese Kategoriezuordnungen muessen neu zugewiesen werden:<BR>";
//	print_r($kat_add);
//	echo "<BR><BR>";
	
// 4)
	//Wenn ein Kategoriezweig umgehaengt wird, wird dem Wurzelelement des Zweiges das neue Parent-Element 
	//zugewiesen und bei allen Elementen dez Zweiges muss der level aktualisiert werden.
	$result2 = mysql_query( "SELECT * FROM $table4 WHERE kat_id = \"$kat_dest\"");
	$dest_name = mysql_result($result2, isset($i2), 'kategorie');
	$dest_level = mysql_result($result2, isset($i2), 'level');
	$new_level = $dest_level + 1;	//neuer Level der umzuhaengenden Kategorie
	
	//Ermittlung aller Unterkategorien unter der umzuhaengenden Kategorie:
	//zur Sicherheit Bereinigung der tmp_tree-Tabelle:
	$result3 = mysql_query( "DELETE FROM $table15 WHERE user_id = '$user_id'");
	//Parameter der umzuhaengenden Kat. werden in die Tabelle tmp_tree geschrieben:
	$result4 = mysql_query( "INSERT INTO $table15 (kat_id, old_level, kat_name, user_id, new_level, new_parent) VALUES (\"$kat_source\", \"$old_level\", \"$source_name\", '$user_id', \"$new_level\", \"$kat_dest\")");
	echo mysql_error();
	
	$delta_level = $new_level - $old_level;
//	echo "Die Level-Aenderung betraegt ".$delta_level."<BR><BR>";

	$kat_id = $kat_source;
	$k = -1;
	function getTree($kat_id,$k) 
	{
	    include '../../share/global_config.php';
		include $sr.'/bin/share/db_connect1.php';
		$result5 = mysql_query("SELECT * FROM $table4 WHERE parent='$kat_id'");
	    echo mysql_error();
	    $sub_kats = "";
	    $kat_arr = array();
	    global $kat_arr;
	    
	    while($einzeln = @mysql_fetch_assoc($result5)) 
	    { 
	    	if(hasChildKats($einzeln['kat_id'])) 
		    {
		      	$k++; 
		        $kat_arr[$k][0] = $einzeln['kat_id'];
		        $kat_arr[$k][1] = $einzeln['level'];
		        $sub_kats = getTree($einzeln['kat_id'],$k);
		    } 
		    else 
		    {
		        $k++; 
		        $kat_arr[$k][0] = $einzeln['kat_id'];
		        $kat_arr[$k][1] = $einzeln['level'];
		    }
	    }
	    return $kat_arr;
  	}
 
	function hasChildKats($katID) 
	{
	    include '../../share/global_config.php';
		include $sr.'/bin/share/db_connect1.php';
	  	$result6 = mysql_query("SELECT kat_id FROM $table4 WHERE parent='$katID'");
	    if(mysql_num_rows($result6)>0) return true; else return false;
	}
		
	$kategorien = getTree($kat_id,$k);  
	$kat_arr_anz = count($kategorien);	//Anzahl der Arrays (Kategorie / Level) im Ergebnis-Array
	FOR($m='0'; $m<$kat_arr_anz; $m++)
	{
			$element = $kategorien[$m];
			$Kat = $element[0];
			$Level = $element[1];
			$korr_level = $Level + $delta_level;
			$result7 = mysql_query("UPDATE $table4 SET level = '$korr_level' WHERE kat_id = '$Kat'");
			echo mysql_error();
	}
	// parent und level fuer die umzuhaengende Kategorie wird in der Tabelle 4 (kategorien) geaendert:
	// (Uebertragung der Daten aus der tmp-Tabelle in die Kategorie-Tabelle)
	$result8 = mysql_query( "SELECT * FROM $table15 WHERE user_id = '$user_id'");
	$num8 = mysql_num_rows($result8);
	FOR($i8='0'; $i8<$num8; $i8++)
	{
		$kat_id = mysql_result($result8, $i8, 'kat_id');
		$new_level = mysql_result($result8, $i8, 'new_level');
		$new_parent = mysql_result($result8, $i8, 'new_parent');
		IF($new_parent !== '0')
		{
			$result9 = mysql_query( "UPDATE $table4 SET level = '$new_level', parent = '$new_parent' WHERE kat_id = '$kat_id'");
		}
		ELSEIF($new_parent == '0')
		{
			$result9 = mysql_query( "UPDATE $table4 SET level = '$new_level' WHERE kat_id = '$kat_id'");
		}
	}

// 5)
	// Bestimmung aller von der Umstrukturierung betroffenen Bilder:
	$result10 = mysql_query("SELECT * FROM $table10 WHERE kat_id = '$kat_source'");
	$num10 = mysql_num_rows($result10);
	IF($num10 > 0)
	{
//		echo "Es sind ".$num10." Bilder betroffen.<BR>";
		FOR($i10=0; $i10<$num10; $i10++)
		{
			$bildnr = mysql_result($result10, $i10, 'pic_id');
//			echo "... verarbeite Bild: ".$bildnr."<BR>";
			FOREACH($kat_del AS $kd)
			{
//				echo "geloescht: ".$kd."<BR>";
				$result11 = mysql_query("DELETE FROM $table10 WHERE pic_id = '$bildnr' AND kat_id = '$kd'");
				echo mysql_error();
			}
			
// 6)
			FOREACH($kat_add AS $ka)
			{
//				echo "hinzugefuegt: ".$ka."<BR>";
				$result12 = mysql_query("INSERT INTO $table10 (pic_id, kat_id) VALUES ('$bildnr', '$ka')");
				//Ein INSERT nur ausführen, falls es die betreffende Kombination pic_id, kat_id nicht bereits existiert (2012.04.24/ICE)
//				$result12 = mysql_query("INSERT INTO $table10 (pic_id, kat_id) SELECT ('$bildnr', '$ka') FROM $table10 WHERE NOT EXISTS(SELECT * FROM $table10 WHERE pic_id = '$bildnr' and kat_id = '$ka' LIMIT 1, 1 ");
				// Test in MySQL:
				// INSERT INTO pic_kat (pic_id, kat_id)
				// SELECT 1,1 FROM pic_kat
				// WHERE NOT EXISTS(SELECT * FROM pic_kat WHERE pic_id = 1 and kat_id = 1)
				//LIMIT 1, 1
				// (2012.04.12/ICE)
				echo mysql_error();
			}
		}
// 7)
		FOR($i10=0; $i10<$num10; $i10++)
		{
			$bildnr = mysql_result($result10, $i10, 'pic_id');
			$FN = strtolower($pic_path."/".restoreOriFilename($bildnr, $sr));
			// alten keywords-Eintrag entfernen:
			$command = $exiftool." -IPTC:Keywords='' -overwrite_original ".$FN;
			shell_exec($command);
			
			$result13 = mysql_query("SELECT $table10.pic_id, $table10.kat_id, $table4.kat_id, $table4.kategorie
			FROM $table4, $table10
			WHERE $table10.pic_id = '$bildnr'
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
// 8)			Schluesselworte fuer IPTC:Keywords erzeugen
				$sel_kat = utf8_encode($sel_kategorie);
				$keyword_string.= " -IPTC:Keywords=\"$sel_kat\"";
			}
			
// weiter mit 7)
//			echo "pictures.Kategorien wird fuer Bild ".$bildnr." mit ".$kategorie." belegt.<BR>";
			$result14 = mysql_query( "UPDATE $table2 SET Keywords = \"$kategorie\", has_kat = '1' WHERE pic_id = '$bildnr'");
			
// weiter mit 8)
			//echo "Schluesselworte weden ins Bild ".$bildnr." geschrieben<BR>";
			$command = $exiftool."".$keyword_string." -overwrite_original ".$FN;
			//echo $command."<BR>";
			shell_exec($command);
		}
	}
	//abschliessend wird die tabelle tmp_tree gesaeubert:
	$result15 = mysql_query( "DELETE FROM $table15 WHERE user_id = '$user_id'");
	echo "<BR><BR><input type='button' style='margin-top:180px;' Value='Fertig - Zur&uuml;ck' onClick='location.href=\"kat_ausw1.php\"'>";
}
ELSE
{
	echo "<p style='color:yellow;'>Es ist ein Fehler aufgetreten!<BR><BR>
	Sie m&uuml;ssen eine Quell- und eine Zielkategorie ausw&auml;hlen!</style><BR><BR>";
	echo "<input type='button' Value='Zur&uuml;ck' onClick='location.href=\"javascript:history.back()\"'>";
}

mysql_close($conn);
?>
</DIV>
</CENTER>
</BODY>
</HTML>