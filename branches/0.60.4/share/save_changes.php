<?php
IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../index.php');
}
ELSE
{
	$uid = $_COOKIE['uid'];
}

include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');

$exiftool = buildExiftoolCommand($sr);

//var_dump($_GET);
if (array_key_exists('pic_id',$_GET) )
{
	$pic_id = $_GET['pic_id'];
}
if (array_key_exists('description',$_GET) )
{
	$description = $_GET['description'];
}
if (array_key_exists('aufn_dat',$_GET) )
{
	$aufn_dat = $_GET['aufn_dat'];
}

$result1 = mysql_query("SELECT FileName, Caption_Abstract FROM $table2 WHERE pic_id = '$pic_id'");
// $fn ist der interne Dateiname
$fn = mysql_result($result1,0,'FileName');
$fn = $pic_path."/".$fn;
//$FN ist der Original-Dateiname
$FN = $pic_path."/".restoreOriFilename($pic_id, $sr);
$description_old = mysql_result($result1, isset($i1), 'Caption_Abstract');

//Aufbereitung der neuen Parameter
$description = strip_tags($description);				//eventuelle Tags entfernen
$description = str_replace('"', "'",$description);		//Anfuehrungszeichen korrigieren
$description = substr($description,'0','1990'); 		//Kuerzung auf max. 2000 Zeichen

IF($aufn_dat == '')
{
	//wenn nur der Beschreibungstext geaendert wurde:
	$result2 = mysql_query( "UPDATE $table2 SET Caption_Abstract = \"$description\" WHERE pic_id = '$pic_id'");
	//Aenderungen in Original-Datei und jpg-Datei speichern:
	shell_exec($exiftool." -IPTC:Caption-Abstract=\"$description\" ".$FN." -overwrite_original -execute -IPTC:Caption-Abstract=\"$description\" ".$fn." -overwrite_original");
}
ELSE
{
	//wenn Datum und Beschreibungstext geaendert wurde:
	//zuerst eventuelle Leerzeichen aus dem Datum entfernen:
	$aufn_dat = trim(str_replace(' ','',$aufn_dat));
	$year = substr($aufn_dat,6,4);
	$month = substr($aufn_dat,3,2);
	$day = substr($aufn_dat,0,2);
	IF(checkdate($month,$day,$year))
	{
		$aufndat = $year."-".$month."-".$day." 00:00:00";
		$dto = $year.":".$month.":".$day." 00:00:00";
		$result2 = mysql_query( "UPDATE $table2 SET Caption_Abstract = \"$description\", DateTimeOriginal = '$aufndat' WHERE pic_id = '$pic_id'");
		//Aenderungen in Original-Datei und jpg-Datei speichern:
		shell_exec($exiftool." -IPTC:Caption-Abstract=\"$description\" ".$FN." -overwrite_original -execute -EXIF:DateTimeOriginal='$dto' ".$FN." -overwrite_original -execute -IPTC:Caption-Abstract=\"$description\" ".$fn." -overwrite_original -execute -EXIF:DateTimeOriginal='$dto' ".$fn." -overwrite_original");
	}
	ELSE
	{
		echo "Das Datum hat ein falsches Format!<BR>Bitte pr&uuml;fen Sie die Eingabe.";
	}
}

//Log-Datei schreiben:
$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
fwrite($fh,date('d.m.Y H:i:s').": Beschreibung von Bild ".$pic_id." wurde von ".$username." modifiziert. (Zugriff von ".$_SERVER['REMOTE_ADDR']."\nalt: ".$description_old.", neu: ".$description."\n");
fclose($fh);

echo "<FONT COLOR='red'>OK!</FONT>";
echo "<textarea name='description' wordwrap style='width:380px; height:90px; background-color:#DFEFFf; font-size:9pt; font-family:Helvitica,Arial;'>".$description."</textarea>";
?>