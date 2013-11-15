<?

#######################################################################################################################################
//
// Skript dient NUR zur nachträglichen Vervollständigung der Geo-Daten im EXIF-Block mit den Angaben GPSLongitudeRef und GPSLatitudeRef
// damit sichergestellt ist, daß auch Bilder korrekt referenziert wurden, die in westlicher Länge oder südlicher Breite
// aufgenommen wurden.
//
// Skript benoetigt gps_korrektur.php und Fkt. debug_gps() in ajax_functions.php
//
#######################################################################################################################################

if ( array_key_exists('pic_id',$_GET) )
{
	$pic_id = $_GET['pic_id'];
}
if ( array_key_exists('all',$_GET) )
{
	$all = $_GET['all'];
}
if ( array_key_exists('rest',$_GET) )
{
	$rest = $_GET['rest'];
}

$exiftool = buildExiftoolCommand($sr);
$result0 = mysql_query("SELECT FileName, GPSLongitude, GPSLatitude FROM $table2 WHERE pic_id = '$pic_id'");
	// $fn ist der interne Dateiname
	$fn = mysql_result($result0,0,'FileName');
	$fn = $pic_path."/".$fn;
	$FN = strtolower($pic_path."/".restoreOriFilename($pic_id, $sr));
	
	$lat = mysql_result($result0,0,'GPSLatitude');
	$long = mysql_result($result0,0,'GPSLongitude');
	
	//Long-/Lat-Referenzen:
	if($lat < 0)
	{
		$lat_ref = "S";
	}
	else
	{
		$lat_ref = "N";
	}
	
	if($long < 0)
	{
		$long_ref = "W";
	}
	else
	{
		$long_ref = "E";
	}
	//	echo $fn." | ".$lat." -> ".$lat_ref." || ".$long." -> ".$long_ref."<BR>"; 
	
	//Eintragung der Geo-Daten in den EXIF-Block des Originalbildes:
	@shell_exec($exiftool." -EXIF:GPSLongitude=".$long." ".$FN." -overwrite_original -execute -EXIF:GPSLongitudeRef=".$long_ref." ".$FN." -overwrite_original -execute -EXIF:GPSLatitude=".$lat." ".$FN." -overwrite_original -execute -EXIF:GPSLatitudeRef=".$lat_ref." ".$FN." -overwrite_original");
	//Eintragung der Geo-Daten in den EXIF-Block des internen jpg-Bildes:
	@shell_exec($exiftool." -EXIF:GPSLongitude=".$long." ".$fn." -overwrite_original -execute -EXIF:GPSLongitudeRef=".$long_ref." ".$fn." -overwrite_original -execute -EXIF:GPSLatitude=".$lat." ".$fn." -overwrite_original -execute -EXIF:GPSLatitudeRef=".$lat_ref." ".$fn." -overwrite_original");

echo "<center>... es verbleiben noch etwa ".$rest." Bilder...</center>";
?>
