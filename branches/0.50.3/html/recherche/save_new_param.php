<?php
IF (!$_COOKIE['login'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../../index.php');
}

//var_dump($_REQUEST);
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$exiftool = buildExiftoolCommand($sr);

IF(array_key_exists('location', $_REQUEST))
{
	$location = $_REQUEST['location'];
}
IF(array_key_exists('ort', $_REQUEST))
{
	$ort = utf8_decode($_REQUEST['ort']);
}
IF(array_key_exists('pic_id', $_REQUEST))
{
	$pic_id = $_REQUEST['pic_id'];
}

IF(array_key_exists('vorh_location', $_REQUEST))
{
	$vorh_location = $_REQUEST['vorh_location'];
}

//echo htmlentities($ort)."<BR>";

$location = strip_tags($location);	//Breite u. Laenge in einem String
$loc_arr = explode(",",$location);
$lat = $loc_arr[0];					//Breite
$long = $loc_arr[1];				//Laenge
$elevation = file_get_contents("http://maps.google.com/maps/api/elevation/xml?locations=".$location."&sensor=false");
$ele_arr = explode(" ",$elevation);
$elevat = strip_tags($ele_arr[16]);
settype($elevat,'float');
$ele = number_format($elevat,2);
$ort = strip_tags($ort);			//Ortsbezeichnung
//IPTC::City darf nur max. 32? Zeichen lang sein:
IF(strlen($ort) > '32')
{
	$ort_iptc = utf8_encode(substr($ort,0,29))."...";
}
ELSE
{
	$ort_iptc = utf8_encode($ort);
}

//Parameter fuer naechste Referenzierung speichern:
$parameter = $location.",".$ort;
setcookie("parameter", $parameter, time()+3600, "/");

$FN = strtolower($pic_path."/".restoreOriFilename($pic_id, $sr));
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
//wenn keine location vorliegt und keine Aenderungen vorgenommen wurden:  ##########################################
IF($ort == '' AND $location == '')
{
	echo "<script language='JavaScript'>window.close();</script>";
}
//es gab bereits eine Referenzierung, dann wird diese aktualisiert:  ############################################
ELSEIF(($vorh_location !== '' AND $vorh_location !== 'Ortsbezeichnung') AND $ort !== '' AND $location !== '')
{
	echo "<p style='font-family:Helvitica,Arial; color:red; text-align:center;'>Bitte warten,<BR>die &Auml;nderungen werden gespeichert...</p>";
	flush();
	$result01 = mysql_query( "SELECT City FROM $table2 WHERE pic_id = '$pic_id'");
	$ort_alt = mysql_result($result01,0, 'City');
	//echo "Es gab eine Referenzierung, - alte Ortsbezeichnung: ".$ort_alt."<BR>";
	$result1 = mysql_query( "UPDATE $table2 SET GPSLongitude = '$long', GPSLatitude = '$lat', GPSAltitude = '$ele', City = \"$ort\" WHERE pic_id = '$pic_id'");
	echo mysql_error();
	//Eintragung der Geo-Daten in den EXIF-Block des Originalbildes:
	shell_exec($exiftool." -IPTC:city='$ort_iptc' ".$FN." -overwrite_original -execute -EXIF:GPSLongitude=".$long." ".$FN." -overwrite_original -execute -EXIF:GPSLatitude=".$lat." ".$FN." -overwrite_original -execute -EXIF:GPSAltitude=".$ele." ".$FN." -overwrite_original");
}
//es gab bisher KEINE Referenzierung und es wird eine neue hinzugefuegt;  #########################################
ELSEIF(($vorh_location == '' OR $vorh_location == 'Ortsbezeichnung') AND $ort !== '' AND $location !== '')
{
	echo "<p style='font-family:Helvitica,Arial; color:red; text-align:center;'>Bitte warten,<BR>die &Auml;nderungen werden gespeichert...</p>";
	flush();
	$ort_alt = '';
	$result1 = mysql_query( "UPDATE $table2 SET GPSLongitude = '$long', GPSLatitude = '$lat', GPSAltitude = '$ele', City = \"$ort\" WHERE pic_id = '$pic_id'");
	echo mysql_error();
	//Eintragung der Geo-Daten in den EXIF-Block des Originalbildes:
	shell_exec($exiftool." -IPTC:city='$ort_iptc' ".$FN." -overwrite_original -execute -EXIF:GPSLongitude=".$long." ".$FN." -overwrite_original -execute -EXIF:GPSLatitude=".$lat." ".$FN." -overwrite_original -execute -EXIF:GPSAltitude=".$ele." ".$FN." -overwrite_original");
}
//abschliessend wird die Ortsbezeichnung in der Bildbeschreibung aktualisiert:
IF($ort_alt !== $ort)
{
	$result4 = mysql_query( "SELECT Caption_Abstract FROM $table2 WHERE pic_id = '$pic_id'");
	$description = mysql_result($result4, 0, 'Caption_Abstract');
	//echo "Beschreibung: ".$description."<BR>";
	IF($ort_alt !== '')
	{
		$description_neu = str_replace($ort_alt, $ort, $description);
		//wenn bisher keine Ortsbezeichnung in der Beschreibung gespeichert war, wird sie hier erstmalig zugefuegt:
		IF($description_neu == $description)
		{
			$description_neu = $description.", Kamerastandort: ".$ort;
		}
	}
	ELSE
	{
		$description_neu = $description.", Kamerastandort: ".$ort;
	}
	echo "alter Ort: ".$ort_alt."<BR>alte Beschreibung: ".$description."<BR>neuer Ort: ".$ort."<BR>neue Beschreibung: ".$description_neu."<BR>";
	$result5 = mysql_query( "UPDATE $table2 SET Caption_Abstract = \"$description_neu\" WHERE pic_id = '$pic_id'");
	//Vermerk im IPTC:Caption-Abstract-Tag:
	$desc_neu = htmlentities($description_neu);
	shell_exec($exiftool." -IPTC:Caption-Abstract='$desc_neu' ".$FN." -overwrite_original > /dev/null &")."<BR>";
}

IF (mysql_error() == '')
{
	//fuer Testzwecke haelt der Parameter das Karten-Fenster offen...
	echo "<p style='font-family:Helvitica,Arial; color:green; text-align:center;'>Fertig...</p>";
	
	flush(0);
	sleep(0);	
	
	
	echo "<script language='JavaScript'>
	anotherWindow = window.open('', 'Karte', '');
	if (anotherWindow != null)
	{
		anotherWindow.close();
	}
	</script>";
	
}
ELSE
{
	echo "Fehler";
	echo mysql_error();
}

?>