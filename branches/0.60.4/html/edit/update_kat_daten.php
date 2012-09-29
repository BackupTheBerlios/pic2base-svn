<?php
IF (!$_COOKIE['login'])
{
	include '../../share/global_config.php';
  	header('Location: ../../../index.php');
}

/*
 * Project: pic2base
 * File: update_kat_daten_.php
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

unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
}
if(array_key_exists('pic_id',$_GET))
{
	$pic_id = $_GET['pic_id'];
}
else
{
	$pic_id = 0;
}

if(array_key_exists('kat_liste',$_GET))
{
	$kat_liste = $_GET['kat_liste'];
}
else
{
	$kat_liste = NULL;
}

if(array_key_exists('mod',$_GET))
{
	$mod = $_GET['mod'];
}
else
{
	$mod = 0;
}
//#####################################################################################
//
// Verwendung bei der Bearbeitung / Kategoriezuordnung (Auswahl nach Kategorien)
// Skript weist jedem Bild (pic_id) die neuen Kategorien zu
// wird aufgerufen bei Bearbeitung | Kategorie zuweisen ( von edit_kat_daten_action2.php)
//
//#####################################################################################

$error_code = 0;

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$exiftool = buildExiftoolCommand($sr);

$result1 = mysql_query( "SELECT * FROM $table1 WHERE username = '$c_username' AND aktiv = '1'");
$kat_array = array();
$kat_array = explode(",", $kat_liste);


//++++++++++++++++++++++++++++++++++++++++++++++++++

//HINWEIS: Wenn einem Bild eine Kategorie zugewiesen werden soll, werden ihm gleichzeitig alle Eltern-Kategorien mit zugewiesen, denn ein Motiv in Blankenburg ist zwangslaeufig in Sachsen-Anhalt, der BRD und Europa...

//++++++++++++++++++++++++++++++++++++++++++++++++++

//Fuer alle Elemente in dem Ziel-Kategorie-Array werden alle zugehoerigen Eltern-Elemente bestimmt und ebenfalls in das Array geschrieben:
IF (isset($kat_array) AND count($kat_array) > 0)
{
	FOREACH($kat_array AS $kat_id)
	{
		WHILE ($kat_id > '1')
		{
			$res0 = mysql_query( "SELECT parent FROM $table4 WHERE kat_id='$kat_id'");
			//echo mysql_error()."<BR>";
			$kat_id = mysql_result($res0, isset($i0), 'parent');
			//echo "Kat-ID in der Funktion: ".$kat_id."<BR>";
			$KAT_ID[]=$kat_id; //$KAT_ID: Array mit den Eltern-Elementen
		}
	}
}
//Zufuegen der Eltern-Elemente zu dem Kategorien-Array:
@$kat_array = array_merge($kat_array, $KAT_ID);
//echo "Kategorie-Anzahl: ".count($kat_array)."<BR>";//$kat_array ist das Array mit allen Kategorie-IDs und deren Eltern-Kategorie-IDs


IF ( isset($pic_id) AND count($pic_id) > 0 AND count($kat_array) > 0)
{
		$bild_id = $pic_id;
		$kategorie = '';
		$kategorie_iptc = '';
		FOREACH ($kat_array AS $kat_id)
		{
			$res0 = mysql_query( "SELECT * FROM $table10 WHERE pic_id = '$bild_id' AND kat_id = '$kat_id'");
			IF (mysql_num_rows($res0) == '0')
			{
				$res1 = mysql_query( "INSERT INTO $table10 (pic_id, kat_id) VALUES ('$bild_id', '$kat_id')");
				IF($kat_id !== '1')
				{
					//Ermittlung aller Kategorien:
					$result2 = mysql_query( "SELECT kategorie FROM $table4 WHERE kat_id = '$kat_id'");
					//echo mysql_error()."<BR>";
					$kategorie = mysql_result($result2, isset($i2), 'kategorie')." ".$kategorie;
				}
			}
		}
		
		//Log-Datei schreiben:
		$result3 = mysql_query("SELECT Keywords, pic_id FROM $table2 WHERE pic_id = '$bild_id'");
		$kategorie_alt = mysql_result($result3, isset($i3), 'Keywords');
		$kategorie = $kategorie_alt."".$kategorie;	//die neue Kat-Zuweisung entspricht der alten zzgl. der neu hinzugekommenen Kategorien
		$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
		fwrite($fh,date('d.m.Y H:i:s').": Kategoriezuordnung von Bild ".$bild_id." wurde von ".$c_username." modifiziert. (Zugriff von ".$_SERVER['REMOTE_ADDR']."\nalt: ".$kategorie_alt.", neu: ".$kategorie."\n");
		fclose($fh);
		//echo $kategorie;
		
		$kategorie = htmlentities($kategorie);
		//Aktualisierung des betreffenden Datensatzes in der pictures Tabelle:
		$result4 = mysql_query( "UPDATE $table2 SET Keywords = \"$kategorie\", has_kat = '1' WHERE pic_id = '$bild_id'");

		//wenn dem Bild die Kategorien zugewiesen wurden, werden diese als keywords in den IPTC-Block des Bildes geschrieben:
		$bild_id = $pic_id;
		$FN = strtolower($pic_path."/".restoreOriFilename($bild_id, $sr));
		//fuer den IPTC-Block muessen die keywords kommasepariert und jeweils kuerzer als 64 Zeichen sein:
		$result5 = mysql_query("SELECT $table10.pic_id, $table10.kat_id, $table4.kat_id, $table4.kategorie
		FROM $table10, $table4
		WHERE $table10.pic_id = '$bild_id'
		AND $table10.kat_id = $table4.kat_id
		AND $table4.kat_id <> '1'");
		$num5 = mysql_num_rows($result5);
		//echo "Anz. der Kat.: ".$num5."<BR>";
		FOR($i5='0'; $i5<$num5; $i5++)
		{
			$kat_iptc = mysql_result($result5, $i5, 'kategorie');
			$kategorie_iptc = utf8_encode($kat_iptc);
			//echo "Bild: ".$bild_id." - ".$kategorie_iptc."<BR>";
			//es wird geprueft, ob das keyword bereits im IPTC-Block enthalten ist:
			$keyw = shell_exec($exiftool." -IPTC:Keywords ".$FN);
			$kwa = explode(":", $keyw);
			@$kw_array = explode(",", $kwa[1]); 					//Array aller keywords des Bildes
			
			FOR ($i1=0 ; $i1<count($kw_array);$i1++) 
			{
				@$KWords = trim($kw_array[$i1]);
				$kw_array[$i1] = $KWords;							//Array mit allen bereinigten Keywords des Bildes
			}
			
			IF(in_array($kategorie_iptc, $kw_array))
			{
				//echo $kategorie_iptc." ist bereits enthalten<BR>";
			}
			ELSE
			{
				//echo $kategorie_iptc." wird in das Bild geschrieben<BR>";
				$command = $exiftool." -IPTC:Keywords+=\"$kategorie_iptc\" -overwrite_original ".$FN." > /dev/null &";
				shell_exec($command);
			}
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
$obj1->Username = $c_username;
$obj1->mod = $mod;
$output = json_encode($obj1);
echo $output;
?>