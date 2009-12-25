<?php
//var_dump($_REQUEST);
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
IF(array_key_exists('location', $_REQUEST))
{
	$location = $_REQUEST['location'];
}
IF(array_key_exists('ort', $_REQUEST))
{
	$ort = $_REQUEST['ort'];
}
IF(array_key_exists('pic_id', $_REQUEST))
{
	$pic_id = $_REQUEST['pic_id'];
}
IF(array_key_exists('loc_id', $_REQUEST))
{
	$loc_id = $_REQUEST['loc_id'];
}
$location = strip_tags($location);	//Breite u. Länge in einem String
$loc_arr = split(',',$location);
$lat = $loc_arr[0];			//Breite
$long = $loc_arr[1];			//Länge
$ort = strip_tags($ort);		//Ortsbezeichnung
//IPTC::City darf nur max. 18? Zeichen lang sein:
IF(strlen($ort) > '18')
{
	$ort_iptc = utf8_encode(substr($ort,0,15))."...";
}
ELSE
{
	$ort_iptc = utf8_encode($ort);
}
//Parameter für nächste Referenzierung speichern:
$parameter = $location.",".$ort;
setcookie("parameter", $parameter, time()+3600, "/");

$FN = strtolower($pic_path."/".restoreOriFilename($pic_id, $sr));
//echo $FN."<BR>";
//echo "Bild-Nr: ".$pic_id."<BR>Beschreibung (Ort): ".$ort."<BR>L&auml;nge: ".$long."<BR>Breite: ".$lat."<BR>";
//Fallunterscheidungen:
//Es ist nur eine der erforderlichen Angaben vorhanden (Koordinaten oder Ortsbezeichnung):
IF(($location == '' AND $ort !== '') OR ($location !== '' AND $ort == ''))
{
	echo "<div style='background-color:red; color:white; font-weight:bold'>Es liegt ein Fehler vor!<BR>
	Die Koordinaten oder die Ortsbezeichnung fehlen!</div><BR>
	<div  style='text-align:right;'>
	<input type='button' value = 'Fenster schlie&szlig;en' OnClick='javascript:window.close()'></div>";
	return;
}
//wenn keine loc_id vorliegt und keine Änderungen vorgenommen wurden:  ##########################################
IF($loc_id == '' AND $ort == '' AND $location == '')
{
	echo "<script language='JavaScript'>window.close();</script>";
}
//es gab bereits eine Referenzierung, dann wird diese aktualisiert:  ############################################
ELSEIF(($loc_id !== '' AND $loc_id !== '0') AND $ort !== '' AND $location !== '')
{
	echo "<center>Bitte warten,<BR>&Auml;nderungen werden gespeichert...</center>";
	flush();
	$result01 = mysql_query( "SELECT location FROM $table12 WHERE loc_id = '$loc_id'");
	$ort_alt = mysql_result($result01,0, 'location');
	//echo "alte Ortsbezeichnung: ".$ort_alt."<BR>";
	$result1 = mysql_query( "UPDATE $table12 SET longitude = '$long', latitude = '$lat', location = '$ort' WHERE loc_id = '$loc_id'");
	$result2 = mysql_query( "UPDATE $table14 SET GPSLongitude = '$long', GPSLatitude = '$lat', City = '$ort' WHERE pic_id = '$pic_id'");
	//Eintragung der Geo-Daten in den EXIF-Block des Originalbildes:
	shell_exec($et_path."/exiftool -IPTC:city='$ort_iptc' ".$FN." -overwrite_original -execute -EXIF:GPSLongitude=".$long." ".$FN." -overwrite_original -execute -EXIF:GPSLatitude=".$lat." ".$FN." -overwrite_original -execute -EXIF:GPSAltitude=".$alt." ".$FN." -overwrite_original");
}
//es gab bisher KEINE Referenzierung und es wird eine neue hinzugefügt; Die Höhe wird mit 0.1 festgelegt, um bei der Suche diese Bilder mit  der Bedingung ">0" mit zu erwischen  #########################################
ELSEIF(($loc_id == '' OR $loc_id == '0') AND $ort !== '' AND $location !== '')
{
	echo "<center>Bitte warten,<BR>&Auml;nderungen werden gespeichert...</center>";
	flush();
	$ort_alt = '';
	$result1 = mysql_query( "INSERT INTO $table12 (longitude, latitude, altitude, location) VALUES ('$long', '$lat', '0.1', '$ort')");
	echo mysql_error();
	$result2 = mysql_query( "SELECT max(loc_id) FROM $table12");
	echo mysql_error();
	$loc_id = mysql_result($result2, $i2, 'max(loc_id)');
	$result3 = mysql_query( "UPDATE $table2 SET loc_id = '$loc_id' WHERE pic_id = '$pic_id'");
	echo mysql_error();
	$result4 = mysql_query( "INSERT INTO $table14 (GPSLongitude, GPSLatitude, GPSAltitude, City) VALUES ('$long', '$lat', '0.1', '$ort')");
	//Eintragung der Geo-Daten in den EXIF-Block des Originalbildes:
	shell_exec($et_path."/exiftool -IPTC:city='$ort_iptc' ".$FN." -overwrite_original -execute -EXIF:GPSLongitude=".$long." ".$FN." -overwrite_original -execute -EXIF:GPSLatitude=".$lat." ".$FN." -overwrite_original -execute -EXIF:GPSAltitude=0.1 ".$FN." -overwrite_original");
}
//abschließend wird die Ortsbezeichnung in der Bildbeschreibung aktualisiert:
IF($ort_alt !== $ort)
{
	$result4 = mysql_query( "SELECT Caption_Abstract FROM $table14 WHERE pic_id = '$pic_id'");
	$description = mysql_result($result4, $i4, 'Caption_Abstract');
	IF($ort_alt !== '')
	{
		$description_neu = str_replace($ort_alt, $ort, $description);
		//wenn bisher keine Ortsbezeichnung in der Beschreibung gespeichert war, wird sie hier erstmalig zugefügt:
		IF($description_neu == $description)
		{
			$description_neu = $description.", ".$ort;
		}
	}
	ELSE
	{
		$description_neu = $description.", Kamerastandort: ".$ort;
	}
	//echo "alter Ort: ".$ort_alt."<BR>alte Beschreibung: ".$description."<BR>neuer Ort: ".$ort."<BR>neue Beschreibung: ".$desc_neu."<BR>";
	//$result5 = mysql_query( "UPDATE $table2 SET description = '$description_neu' WHERE pic_id = '$pic_id'");
	$result5 = mysql_query( "UPDATE $table14 SET Caption_Abstract = '$description_neu' WHERE pic_id = '$pic_id'");
	//Vermerk im IPTC:Caption-Abstract-Tag:
	$desc_neu = htmlentities($description_neu);
	echo shell_exec($et_path."/exiftool -IPTC:Caption-Abstract='$desc_neu' ".$FN)."<BR>";
}

IF (mysql_error() == '')
{
	//für Testzwecke hält der Parameter das Karten-Fenster offen...
	//echo "Bilddaten wurden aktualisiert";
	//flush();
	//sleep(1);	
	
	echo "<script language='JavaScript'>
	anotherWindow = window.open('', 'Karte', '');
	if (anotherWindow != null)
	{
		anotherWindow.close();
	}
	window.close('Speicherung2');
	</script>";
	
	//print_r($_COOKIE);
}
ELSE
{
	echo mysql_error();
}
?>