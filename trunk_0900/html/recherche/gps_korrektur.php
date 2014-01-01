<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - GPS-Korrektur</TITLE>
	<META NAME="GENERATOR" CONTENT="Eclipse">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
</HEAD>


<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../../index.php');
}
header("Content-type: text/html; charset=utf-8");
//var_dump($_REQUEST);
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/ajax_functions.php';

#######################################################################################################################################
//
// Skript dient NUR zur nachträglichen Vervollständigung der Geo-Daten im EXIF-Block mit den Angaben GPSLongitudeRef und GPSLatitudeRef
// damit sichergestellt ist, daß auch Bilder korrekt referenziert wurden, die in westlicher Länge oder südlicher Breite
// aufgenommen wurden.
//
// Skript benoetigt gps_korrektur_action.php und Fkt. debug_gps() in ajax_functions.php
//
#######################################################################################################################################

$result0 = mysql_query("SELECT pic_id FROM $table2 WHERE (GPSLongitude <> 'NULL' AND GPSLatitude <> 'NULL' AND (GPSLongitudeRef IS NULL OR GPSLatitudeRef IS NULL)) ORDER BY pic_id");
echo mysql_error();
$num0 = mysql_num_rows($result0);
//echo $num0." Bilder werden bearbeitet...";
FOR($i0=0; $i0<$num0; $i0++)
{
	$pic_id = mysql_result($result0, $i0, 'pic_id');
	$rest= $num0 - $i0 - 1;
	?>
	<script type=text/javascript>
	var pic_id=<?php echo $pic_id;?>;
	var all=<?php echo $num0;?>;
	var rest=<?php echo $rest;?>;
	//alert (pic_id + ": " + all + "  " + rest);
	debug_gps(pic_id,all,rest);
	</script>
	<?php
}
echo "<div id='tip'></div>";
?>