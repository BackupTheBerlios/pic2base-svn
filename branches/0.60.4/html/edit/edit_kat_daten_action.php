<?php
IF (!$_COOKIE['login'])
{
	include '../../share/global_config.php';
  	header('Location: ../../../index.php');
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Startseite</TITLE>
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
 * File: edit_kat_daten_action.php
 *
 * Copyright (c) 2005 - 2011 Klaus Henneberg
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
//echo $c_username;
}
 
//var_dump($_GET);

if(array_key_exists('kat_id',$_GET))
{
	$kat_id = $_GET['kat_id']; 
}
else
{
	$kat_id = 0;
}
if(array_key_exists('ID',$_GET))
{
	$ID = $_GET['ID'];
}
else
{
	$ID = 0;
}
if(array_key_exists('mod',$_GET))
{
	$mod = $_GET['mod'];
}
else
{
	$mod = 0;
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$exiftool = buildExiftoolCommand($sr);

$result1 = mysql_query( "SELECT * FROM $table1 WHERE username = '$c_username' AND aktiv = '1'");

//Variablen-Umbenennung fuer die Ruecksprung-Adresse:
$kat_back = $kat_id;
$ID_back = $ID;

//Ermittlung der ausgewaehlten Checkboxen:
//echo count($_POST)."<BR>";
FOREACH ($_POST AS $key => $post)
{
	//echo "Schluessel / Wert: ".$key." / ".$post."<BR>";
	IF (substr($key,0,3) == 'pic')
	{
		//echo substr($key,7,strlen($key)-7)."<BR>";
		$pic_ID[] = substr($key,7,strlen($key)-7);
	}
	
	IF (substr($key,0,3) == 'kat')
	{
		//echo substr($key,3,strlen($key)-3)."<BR>";
		$kat_ID[] = substr($key,3,strlen($key)-3);
	}
}


echo "
<div class='page'>

	<p id='kopf'>pic2base :: Kategorie-Zuweisung (&Auml;nderungen speichern)</p>
	
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>
		</div>
	</div>
	
	<div class='content'>";
	
IF ( isset($pic_ID) AND count($pic_ID) > 0 AND count($kat_ID) > 0)
{
	echo "<div id='blend' style='display:block; z-index:99;'>
	<IMG src='../../share/images/grey.png' style='z-index:100; position:absolute; top:0px; left:0px; width:100%; height:99%;' />
	<img src=\"../../share/images/loading.gif\" style='position:relative; top:200px; left:400px; width:20px; z-index:101;' />
	<p style='color:white; position:relative; top:120px; left:250px; z-index:102; width:40%;'>Die &Auml;nderungen werden ausgef&uuml;hrt, bitte warten Sie...</p>
	</div>";
}
ob_flush();
flush();
	//++++++++++++++++++++++++++++++++++++++++++++++++++
	
	//HINWEIS: Wenn einem Bild eine Kategorie zugewiesen werden soll, werden ihm gleichzeitig alle Eltern-Kategorien mit zugewiesen, denn ein Motiv in Blankenburg ist zwangslaeufig in Sachsen-Anhalt, der BRD und Europa...
	
	//++++++++++++++++++++++++++++++++++++++++++++++++++

	//Fuer alle Elemente in dem Kategorie-Array werden die Eltern-Elemente bestimmt und ebenfalls in das Array geschrieben:
	
	IF (isset($kat_ID) AND count($kat_ID) > 0)
	{
		FOREACH($kat_ID AS $kat_id)
		{
			WHILE ($kat_id > '1')
			{
				$res0 = mysql_query( "SELECT parent FROM $table4 WHERE kat_id='$kat_id'");
				echo mysql_error();
				$kat_id = mysql_result($res0, isset($i0), 'parent');
				//echo "Kat-ID in der Funktion: ".$kat_id."<BR>";
				$KAT_ID[]=$kat_id; //$KAT_ID: Array mit den Eltern-Elementen
			}
		}
	}
	//Zufuegen der Eltern-Elemente zu dem Kategorien-Array:
	@$kat_ID = array_merge($kat_ID, $KAT_ID);
	//echo "Kategorie-Anzahl: ".count($kat_ID)."<BR>";//$kat_ID ist das Array mit allen Kategorie-IDs und deren Eltern-Kategorie-IDs
	
	IF ( isset($pic_ID) AND count($pic_ID) > 0 AND count($kat_ID) > 0)
	{
		FOREACH ($pic_ID AS $bild_id)
		{
			$kategorie = '';
			$kategorie_iptc = '';
			FOREACH ($kat_ID AS $kat_id)
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
		}
		//wenn dem Bild die Kategorien zugewiesen wurden, werden diese als keywords in den IPTC-Block des Bildes geschrieben:
		FOREACH($pic_ID AS $bild_id)
		{
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
				//print_r($kw_array);
				
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
		}
		
		
		//echo mysql_errno();
		IF (mysql_errno() == '0')
		{
			echo "<meta http-equiv='refresh' content='0; url=edit_kat_daten.php?kat_id=$kat_back&mod=$mod&ID=$ID_back'>";
			
		}
		ELSE
		{
			echo "Es ist ein Fehler aufgetreten!";
		}
	}
	ELSE
	{
		echo "<p class='zwoelfred' align='center'>Es wurde kein Bild oder keine Kategorie ausgew&auml;hlt!<BR><BR>
		Bitte w&auml;hlen Sie mindestens ein Bild aus<BR>oder verlassen Sie den vorhergehenden Dialog<BR>mit \"Abbrechen\"!</p>
		<meta http-equiv='refresh' content='5; url=edit_kat_daten.php?kat_id=$kat_back&mod=$mod&ID=$ID_back'>";
	}
	
	echo "
	</div>
	<br style='clear:both;' />
	<p id='fuss'><A style='margin-right:745px;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>".$cr."</p>
</div>";

mysql_close($conn);
?>
</DIV>
</CENTER>
</BODY>
</HTML>