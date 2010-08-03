<?php
IF (!$_COOKIE['login'])
{
include '../share/global_config.php';
//var_dump($sr);
  header('Location: ../../index.php');
}

//in get_details; recherche2, edit_remove_kat zur nachträglichen, manuellen Rotation der Bilder verwendet
include 'global_config.php';
include 'db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
//echo "Ausrichtung: ".$orientation.", Bild-ID: ".$pic_id."<BR>";
$result1 = mysql_query( "SELECT FileName, FileNameV, FileNameHQ FROM $table2 WHERE pic_id = '$pic_id'");
//echo mysql_error();
$FileName = mysql_result($result1, $i1, 'FileName');
$FileNameV = mysql_result($result1, $i1, 'FileNameV');
$FileNameHQ = mysql_result($result1, $i1, 'FileNameHQ');
echo "Bild ".$FileName." wird gedreht.<BR>";

SWITCH($orientation)
{	
	case '6':
	//Das Bild muss 90 im Uhrzeigersinn gedreht werden:
	$command = "/usr/bin/convert ".$pic_thumbs."/".$FileNameV." -rotate 90 ".$pic_thumbs."/".$FileNameV."";
	$output = shell_exec($command);
	echo "Vorschaubild wurde gedreht<BR>";
	$command = "/usr/bin/convert ".$pic_hq_preview."/".$FileNameHQ." -rotate 90 ".$pic_hq_preview."/".$FileNameHQ."";
	$output = shell_exec($command);
	echo "HQ-Bild wurde gedreht<BR>";
	$command = "/usr/bin/convert ".$pic_path."/".$FileName." -rotate 90 ".$pic_rot_path."/".$FileName."";
	$output = shell_exec($command);
	echo "Original wurde gedreht<BR>";
	break;
	
	case '8':
	//Das Bild muss 90 entgegen dem Uhrzeigersinn gedreht werden:
	$command = "/usr/bin/convert ".$pic_thumbs."/".$FileNameV." -rotate 270 ".$pic_thumbs."/".$FileNameV."";
	$output = shell_exec($command);
	echo "Vorschaubild wurde gedreht<BR>";
	$command = "/usr/bin/convert ".$pic_hq_preview."/".$FileNameHQ." -rotate 270 ".$pic_hq_preview."/".$FileNameHQ."";
	$output = shell_exec($command);
	echo "HQ-Bild wurde gedreht<BR>";
	$command = "/usr/bin/convert ".$pic_path."/".$FileName." -rotate 270 ".$pic_rot_path."/".$FileName."";
	$output = shell_exec($command);
	echo "Original wurde gedreht<BR>";
	break;
}

//in der Tabelle vermerkte Werte für Breite und Höhe werden getauscht:
$result2 = mysql_query( "SELECT * FROM $table14 WHERE pic_id = '$pic_id'");
$Width  = mysql_result($result2, $i2, 'ImageWidth');
$Height  = mysql_result($result2, $i2, 'ImageHeight');
$result3 = mysql_query( "UPDATE $table14 SET ImageWidth = '$Height', ExifImageWidth = '$Height', ImageHeight = '$Width', ExifImageHeight = '$Width'  WHERE pic_id = '$pic_id'");
	
IF (mysql_error() == '')
{
	echo "Schlie&szlig;en Sie nun bitte dieses Fenster und aktualisieren Sie die pic2base-Ansicht.<BR><BR>
	<input type='button' value='Fenster schließen' align='right' onClick='JavaScript:window.close()'>";
}
ELSE
{
	echo mysql_error();
}
?>