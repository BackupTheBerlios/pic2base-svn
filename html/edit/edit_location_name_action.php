<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Geo-Referenzierung (Ortsnamen)</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
	<!--<script type="text/javascript" src="../../ajax/inc/vorschau.js"></script>-->
</HEAD>

<BODY LANG="de-DE" scroll = "auto">
<CENTER>
<DIV Class="klein">

<?

/*
 * Project: pic2base
 * File: edit_location_name_action.php
 *
 * Copyright (c) 2005 - 2009 Klaus Henneberg
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
list($c_username) = split(',',$_COOKIE['login']);
//echo $c_username;
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/geo_functions.php';
include $sr.'/bin/share/functions/main_functions.php';
//echo $geo_path_copy;

echo "
<div class='page'>
	<p id='kopf'>pic2base :: Datensatz-Bearbeitung (Speichern des Ortsnamens)</p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
			createNavi3_1($c_username);
		echo "</div>
	</div>
	
	<div id='spalte1F'>
		<p id='elf' style='background-color:white; padding: 5px; margin-top: 4px; margin-left: 0px; text-align:center;'>Speicherung l&auml;uft...<BR></p>";
		$result2 = mysql($db, "UPDATE $table12 SET location='$ort' WHERE loc_id = '$loc_id'");
		
		//Eintragung des Ortsnamens an den Anfang des Beschreibungstextes des zugehörigen Bildes:
		$result3 = mysql($db, "SELECT * FROM $table2 WHERE (loc_id = '$loc_id' AND loc_id <> '')");
		$num3 = mysql_num_rows($result3);
		FOR ($i3=0; $i3<$num3; $i3++)
		{
			//zur vorhandenen Beschreibung wird die Ortsbezeichnung vorangestellt:
			$pic_id = mysql_result($result3, $i3, 'pic_id');
			$result6 = mysql($db, "SELECT * FROM $table14 WHERE pic_id = '$pic_id'");
			$description_alt = mysql_result($result6, $i6, 'Caption_Abstract');
			$description_neu = "Kamerastandort: ".$ort."; ".$description_alt;
			$result4 = mysql($db, "UPDATE $table14 SET Caption_Abstract = '$description_neu' WHERE pic_id = '$pic_id'");
			//Bestimmung der Geo-Koordinaten
			$result5 = mysql($db, "SELECT * FROM $table12 WHERE loc_id = '$loc_id'");
			$long = mysql_result($result5, $i5, 'longitude');
			$lat = mysql_result($result5, $i5, 'latitude');
			$alt = mysql_result($result5, $i5, 'altitude');
			$location = mysql_result($result5, $i5, 'location');
			$ort_iptc = utf8_decode($location);
			$desc_neu = htmlentities($description_neu);
			//Bestimmung des Dateinamens der Original-Datei:
			$FN = $pic_path."/".restoreOriFilename($pic_id, $sr);
			//Speicherung der Geo-Koordinaten, Ortsbezeichnung und Beschreibung in der Bild-Datei:
			//schnelle Version ohne Sicherung der Original-Datei:
			shell_exec($et_path."/exiftool -IPTC:city='$ort_iptc' ".$FN." -overwrite_original -execute -EXIF:GPSLongitude=".$long." ".$FN." -overwrite_original -execute -EXIF:GPSLatitude=".$lat." ".$FN." -overwrite_original -execute -EXIF:GPSAltitude=".$alt." ".$FN." -overwrite_original -execute -IPTC:Caption-Abstract='$desc_neu' ".$FN." -overwrite_original");
		}
		
		echo mysql_error();
	echo "<meta http-equiv='refresh', content='0; URL=edit_location_name.php'>
	</div>
	
	<div id='spalte2F'>
		<p id='elf' style='background-color:white; padding: 5px; margin-top: 4px; margin-left: 0px; text-align:center;'><BR></p>
	</div>
	
	<div id='filmstreifen'>
	</div>
	
	<p id='fuss'><A style='margin-right:745px;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>".$cr."</p>

</div>";

mysql_close($conn);

?>
</DIV>
</CENTER>
</BODY>
</HTML>