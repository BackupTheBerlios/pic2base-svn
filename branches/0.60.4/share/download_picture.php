<?php
IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../index.php');
}
else
{
	$uid = $_COOKIE['uid'];
}

include 'global_config.php';
include 'db_connect1.php';
//var_dump($_GET);
if ( array_key_exists('FileName',$_GET) )
{
	$FileName = $_GET['FileName'];
}

if ( array_key_exists('pic_id',$_GET) )
{
	$pic_id = $_GET['pic_id'];
}

//Die Bild-ID wird ermittelt:
$result1 = mysql_query( "SELECT * FROM $table2 WHERE FileName = '$FileName'");
@$num1 = mysql_num_rows($result1);
//echo $num1."<BR>";
IF($num1 == '1')
{
  $row = mysql_fetch_array($result1);
//  $pic_id = $row['pic_id'];
  $FileNameOri = $row['FileNameOri'];
}
ELSE
{
  echo "<p style='color:red; font-wight:bold;'>ES LIEGT EIN PROBLEM VOR!<BR>
  ES EXISTIERT KEIN oder MEHR ALS EIN DATENSATZ F&Uuml;R DAS GEW&Auml;HLTE BILD!!</p>";
  return;
}
//echo $FileNameOri."<br>";

$file_info = pathinfo($pic_path."/".$FileName);
$ext = ".".$file_info['extension'];                             //Dateiendung mit Punkt
$base_name = str_replace($ext,'',$file_info['basename']);       //Dateiname ohne Punkt und Rumpf

$file_info = pathinfo($pic_path."/".$FileNameOri);
$ext = ".".$file_info['extension'];
$datei = $pic_path."/".$base_name.strtolower($ext);
//echo $datei."<br>";

// Passenden Datentyp erzeugen.
header("Content-Type: application/octet-stream");
 
// Dateinamen im Download-Requester vorgeben
$save_as = $FileNameOri;
header("Content-Disposition: attachment; filename=\"$save_as\"");
 
// Datei ausgeben.
readfile($datei);

//Downloadzaehler aktualisieren:
$result1 = mysql_query("UPDATE $table2 SET ranking = ranking + 1 WHERE pic_id = '$pic_id'");

?>