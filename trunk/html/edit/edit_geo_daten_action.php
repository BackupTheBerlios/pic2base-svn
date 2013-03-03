<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../../index.php');
}
else
{
	$uid = $_COOKIE['uid'];
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Geo-Referenzierung</TITLE>
	<META NAME="GENERATOR" CONTENT="eclipse">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format2.css'>
	<link rel=stylesheet type="text/css" href='../../css/tooltips.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
	<script language="JavaScript" src="../../share/functions/resize_elements.js"></script>
	<script language="JavaScript" src="../../share/functions/jquery-1.8.2.min.js"></script>
	<script language="JavaScript">
	  	jQuery.noConflict();
		jQuery(document).ready(checkWindowSize);
		jQuery(window).resize(checkWindowSize); 
	</script>
</HEAD>

<BODY LANG="de-DE" scroll = "auto">
<CENTER>
<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: edit_geo_daten_action.php
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

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/geo_functions.php';
include $sr.'/bin/share/functions/main_functions.php';

//var_dump($_POST);

if ( array_key_exists('timezone',$_POST) )
{
	$timezone = $_POST['timezone'];
}
if ( array_key_exists('data_logger',$_POST) )
{
	$data_logger = $_POST['data_logger'];
}
if ( array_key_exists('ge',$_POST) )
{
	$ge = $_POST['ge'];
}

$result0 = mysql_query("UPDATE $table1 SET timezone = '$timezone', logger_type = '$data_logger' WHERE id = '$uid'");
$result1 = mysql_query( "SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$row = mysql_fetch_array($result1);
$username = $row['username'];
//var_dump($_FILES);
$geo_file = $_FILES['geo_file']['name'];
$geo_file_name = $track_path."/".$geo_file;

IF($geo_file == '')
{
	echo mysql_error();
	echo "<H3 style='color:red; margin-top:150px;'>Fehler!<BR><BR>Sie m&uuml;ssen eine g&uuml;ltige Track-Datei ausw&auml;hlen!</H3>";
	echo "<meta http-equiv='Refresh', content='2; url=edit_geo_daten.php'>";
	return;
}

IF ($geo_file_name != "" && $geo_file_name !='.' && $geo_file_name != '..')
{
	move_uploaded_file($_FILES['geo_file']['tmp_name'],$geo_file_name)
	or die("Upload fehlgeschlagen!");
	$error = getFileError($_FILES['geo_file']['error']);
}


$fh = fopen($geo_file_name, 'r');
//join() -> Alais zu implode()
$txt_file = implode('',file($track_path."/".$geo_file));
$line_number = 0;	//Initialisierung des Zeilen-Zaehlers

//Ermittlung des Datei-Formates:
$info = pathinfo($geo_file_name);
//echo "Datei-Extension: ".strtolower($info['extension'])."<BR>";
//die ausgewaehlte Trackdatei wird ueberprueft, bei Erfolg in eine kml-Datei konvertiert und der Inhalt
//(Geo-Koordinaten und Zeitstempel in die geo_tmp (table13) geschrieben
convertFile($sr,$data_logger,$info,$geo_file_name,$uid,$timezone);
//Bestimmung der verwertbaren Datensaetze:
$result2 = mysql_query( "SELECT * FROM $table13 WHERE user_id = '$uid'");
$line_number = mysql_num_rows($result2);

SWITCH($ge)
{
	CASE 'Los!':
	// Wenn Geo-Referenzierung ausgewaehlt wurde:
	SWITCH($line_number)
	{
		CASE '0':
		$hinweis1 = "Zeilen-Zahl: ".$line_number."<BR>
		Bei der ausgew&auml;hlten Datei handelt es sich nicht um eine gltige Trackpunkte-Datei!";
		return;
		
		DEFAULT:
		$hinweis1 = "Die Track-Datei enth&auml;lt ".$line_number." verwertbare Trackpunkte.<BR><BR>";
		//aus der temp. Tabelle wird fuer jeden Tag die Zeitspanne (der frueheste und spaeteste Zeitpunkt) ermittelt:
		$result4 = mysql_query( "SELECT DISTINCT date FROM $table13 WHERE user_id = '$uid'");
		$num4 = mysql_num_rows($result4);
		$hinweis2 = '';
		FOR ($i4=0; $i4<$num4; $i4++)
		{
			$datum = mysql_result($result4, $i4, 'date');
			//echo "vorh. Datum: ".$datum."<BR>";
			//Zu jedem vorh. Datum wird die Zeitspanne vorh. Trackdaten bestimmt:
			$result5 = mysql_query( "SELECT MIN(time), MAX(time) FROM $table13 WHERE date = '$datum' AND user_id = '$uid'");
			$row = mysql_fetch_array($result5);
			$min_time = $row['MIN(time)'];
			$max_time = $row['MAX(time)'];
			
			//echo "Zeitspanne: ".$min_time." - ".$max_time."<BR>";
			//alle Bilder des Users werden ermittelt, welche an dem besagten Tag in der ermittelten Zeitspanne aufgenommen wurden und denen noch keine Koordinaten zugewiesen wurden:
			$start_time = $datum." ".$min_time;
			$end_time = $datum." ".$max_time;
			//echo "Startzeit: ".$start_time.", Endzeit: ".$end_time."<BR>";
			$result6 = mysql_query( "SELECT pic_id, FileName, Owner, DateTimeOriginal, City, GPSLatitude, GPSLongitude, aktiv
			FROM $table2
			WHERE DateTimeOriginal >= '$start_time' 
			AND DateTimeOriginal <= '$end_time' 
			AND (City ='Ortsbezeichnung' OR City = '' OR GPSLongitude = '0' OR GPSLatitude = '0')
			AND Owner = '$uid'
			AND aktiv = '1'");
			$num6 = mysql_num_rows($result6);
			//echo "<p style='color:white';>Treffer fuer User ".$uid.": ".$num6."</p><BR>";
			IF($num6 > '0')
			{
				FOR($i6=0; $i6<$num6; $i6++)
				{
					$pic_id = mysql_result($result6, $i6, 'pic_id');
					$FileName = mysql_result($result6, $i6, 'FileName');
					$pic_time = mysql_result($result6, $i6, 'DateTimeOriginal');
					//echo $FileName.", Aufnahme-Zeit: ".$pic_time."<BR>";
					//Fr jedes Bild wird ermittelt, welche Trackpunkte mit einer zeitlichen Abweichung von +/- $delta Sekunden existieren:
					$delta = 60;
					$anz = getTrackPoints($delta,$pic_time,$datum);
					SWITCH($anz)
					{
						CASE '0':
						//Wenn keine passenden Trackdaten gefunden wurden, passiert nix ausser einer Info.
						$hinweis2 .= "Zum Bild ".$pic_id." gibt es <font color='red'>KEINE</font> passenden Trackpunkte bei Delta = ".$delta."<BR>";
						break;
			
						default:
						//Wenn es Trackpunkte gibt, welche innerhalb des Zeitfensters liegen, wird dieses von 0 beginnend so lange vergroessert, bis mind. ein Trackpunkt gefunden wurde:
						$delta = -1;
						$anz = 0;
						WHILE($anz == 0)
						{
							$delta++;
							$anz = getTrackPoints($delta,$pic_time,$datum);
						}
						//Die Daten der betreffenden Punkte werden ermittelt, in die Tabelle 'pictures' zur passenden pic_id geschrieben
						findTrackData($delta,$pic_time,$datum,$pic_id);
						$hinweis2 .= "Bild ".$pic_id.": <font color='green'>".$anz."</font> Trackpunkt(e) (Delta = ".$delta.")<BR>";
						break;
					}
				}
			}
			ELSE
			{
				$hinweis2 = "Es wurden keine Bilder am ".date('d.m.Y',strtotime($datum)).", von ".$min_time." Uhr bis ".$max_time." Uhr aufgenommen, oder alle in diesem Zeitraum aufgenommenen Bilder wurden bereits mit Geo-Daten referenziert.<BR><BR>
				M&ouml;glicherweise haben aber noch nicht alle Bilder eine korrekte Ortsbezeichnung.<BR>Dies wird im n&auml;chsten Schritt gepr&uuml;ft.<BR>";
			}
		}
		break;
	}
	
	echo "
	<div class='page' id='page'>
		
		<div id='head'>
			pic2base :: Datensatz-Bearbeitung (Geo-Referenzierung) <span class='klein'>(User: $username)</span>
		</div>
		
		<div class='navi' id='navi'>
			<div class='menucontainer'>";
				createNavi3_1($uid);
			echo "</div>
		</div>
		
		<div id='spalte1'>
		
			<fieldset style='background-color:none; margin-top:10px;'>
			<legend style='color:blue; font-weight:bold;'>Details zur Track-Datei</legend>
				<div id='scrollbox0' style='overflow-y:scroll; padding-top:50px;'>
					Name der Track-Datei: ".($_FILES['geo_file']['name'])."<BR>
					Trackdatei-Gr&ouml;sse: ".($_FILES['geo_file']['size'])." Byte<BR>".
					$error.$hinweis1."
				</div>
			</fieldset>
			
		</div>
		
		<div id='spalte2'>
		
			<fieldset style='background-color:none; margin-top:10px;'>
			<legend style='color:blue; font-weight:bold;'>Hinweise zur Referenzierung</legend>
				<div id='scrollbox1' style='overflow-y:scroll; padding-top:50px; text-align:center;'>";
					//Wenn alle Bilder referenziert wurden, werden die benutzereignen Datens&auml;tze aus der temp. Tabelle 'geo_tmp' gel&ouml;scht und nochmals alle Bilder dargestellt, damit den Koordinaten eine Ortsbezeichnung hinzugefgt werden kann:
					$result8 = mysql_query( "DELETE FROM $table13 WHERE user_id = '$uid'");
					echo "Die Geo-Referenzierung wurde im ersten Schritt abgeschlossen.<BR>
					Klicken Sie auf \"Weiter\", um zur Ortzuweisung zu gelangen.<BR><BR>
					<p align='center'><INPUT type='button' value='Weiter' OnClick='location.href=\"edit_location_name.php\"'></p><BR>
					Ergebnis der Koordinaten-Zuweisung:<BR><BR>".$hinweis2."
				</div>
			</fieldset>
			
		</div>

		<div id='foot'>
			<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
		</div>
	
	</div>
	</FORM>";
	break;
	
//#############################################################################################################################	

	CASE 'Track ansehen':
	//Wenn der Track nur in GE angezeigt werden soll:
	//Da die gpx-Datei schrittweise vom Anfang an abgearbeitet wird, erfolgt der Eintrag in die Tabelle in zeitlicher Reihenfolge. Somit genuegt eine Sortierung beim auslesen nach loc_id.
	$result2 = mysql_query( "SELECT * FROM $table13 
	WHERE user_id = '$uid' AND longitude <> '' AND latitude <> '' 
	ORDER BY loc_id");
	$num2 = mysql_num_rows($result2);
	$date = mysql_result($result2, 0, 'date');
	IF ($date !== '0000-00-00')
	{
		$datum = date('d.m.Y', strtotime($date));
	}
	ELSE
	{
		$datum = date('d.m.Y');
	}
	$content = '<?xml version="1.0" encoding="UTF-8"?>
			<kml xmlns="http://earth.google.com/kml/2.1">
			<Document>
			<name>'.$datum.'</name>
			<description>Ansicht des Streckenverlaufs</description>
			<Style id="yellowLineGreenPoly">
			<LineStyle>
			<color>7f00ffff</color>
			<width>4</width>
			</LineStyle>
			<PolyStyle>
			<color>77f00ff00</color>
			</PolyStyle>
			</Style>
			<Placemark>
			<name>Track-Ansicht</name>
			<description>Transparente gr&#252;ne Mauer mit gelben Umriss</description>
			<styleUrl>#yellowLineGreenPoly</styleUrl>
			<LineString>
			<extrude>1</extrude>
			<tessellate>1</tessellate>
			<altitudeMode>relative</altitudeMode>
			<coordinates>';
	
	FOR($i2='0'; $i2<$num2; $i2++)
	{
		$lat = mysql_result($result2, $i2, 'latitude');
		$long = mysql_result($result2, $i2, 'longitude');
		$content .= $long.','.$lat."\n"; 
	}
	$content .= '	</coordinates>
			</LineString>
			</Placemark>
			</Document>
			</kml>';
	//kml-Datei erzeugen und mit Inhalt ($content) fllen
	$file = time().'.kml';
	$file_name = $kml_dir.'/'.$file;
	//echo $file_name;
	$fh = fopen($file_name,"w");
	fwrite($fh,$content);
	fclose($fh);
	
	//Wenn kml-Track-Datei erzeugt wurde, werden die benutzereignen Datensaeze aus der temp. Tabelle 'geo_tmp' geloescht:
	$result9 = mysql_query( "DELETE FROM $table13 WHERE user_id = '$uid'");
	
	echo "
	<div class='page' id='page'>
		
		<div id='head'>
			pic2base :: Track-Darstellung in GoogleEarth <span class='klein'>(User: $username)</span>
		</div>
				
		<div class='navi' id='navi'>
			<div class='menucontainer'>";
				createNavi3_1($uid);
			echo "</div>
		</div>
		
		<div id='spalte1'>
		
			<fieldset style='background-color:none; margin-top:10px;'>
			<legend style='color:blue; font-weight:bold;'>Zur Ansicht in GoogleEarth</legend>
				<div id='scrollbox0' style='overflow-y:scroll; padding-top:50px;'>
					<div id='tooltip1'>Streckenverlauf in <a href='../../../userdata/$uid/kml_files/$file'>GoogleEarth 
						<span>
						<strong>Zur Anzeige des Streckenverlaufs in GoogleEarth ist es erforderlich, da&#223; GoogleEarth auf Ihrem Rechner installiert ist.</strong><br />
						<br />
						Ein kostenfreier Download steht unter http://earth.google.de zur Verf&uuml;gung.
						</span>
						</a>darstellen
					</div>
				</div>
			</fieldset>
			
			
		</div>
		
		<div id='spalte2'>
		
			<fieldset style='background-color:none; margin-top:10px;'>
			<legend style='color:blue; font-weight:bold;'>Hinweise</legend>
				<div id='scrollbox1' style='overflow-y:scroll; padding-top:50px; text-align:center;'>
					Klicken Sie in der linken Spalte auf 'GoogleEarth' um den ausgew&auml;hlten Track in GoogleEarth darzustellen.
				</div>
			</fieldset>

		</div>
	
		<div id='foot'>
			<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
		</div>
	
	</div>";
	break;
}

mysql_close($conn);
?>
</DIV>
</CENTER>
</BODY>
</HTML>