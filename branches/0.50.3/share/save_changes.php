<?php
IF (!$_COOKIE['login'])
{
	include '../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../index.php');
}

include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

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

$result1 = mysql_query("SELECT FileName FROM $table2 WHERE pic_id = '$pic_id'");
// $fn ist der interne Dateiname
$fn = mysql_result($result1,0,'FileName');
$fn = $pic_path."/".$fn;
//echo $fn."<BR>";
//$FN ist der Original-Dateiname
$FN = $pic_path."/".restoreOriFilename($pic_id, $sr);
//echo $FN."<BR>";
//$description = str_replace('?','',$description);
$description = substr(utf8_decode(strip_tags($description)),'0','1990');
//echo "Bild-Nr: ".$pic_id."<BR>Beschreibung: ".$description."<BR>Aufn.-Datum: ".$aufn_dat."<BR>";

IF($aufn_dat == '')
{
	$result2 = mysql_query( "UPDATE $table14 SET Caption_Abstract = \"$description\" WHERE pic_id = '$pic_id'");
	$desc = htmlentities($description);
	//Aenderungen in Original-Datei speichern, wenn moeglich:
	shell_exec($exiftool." -IPTC:Caption-Abstract=\"$desc\" ".$FN." -overwrite_original > /dev/null &");
	//Aenderungen in jpg-Datei speichern:
	shell_exec($exiftool." -IPTC:Caption-Abstract=\"$desc\" ".$fn." -overwrite_original > /dev/null &");
}
ELSE
{
	//zuerst eventuelle Leerzeichen aus dem Datum entfernen:
	$aufn_dat = trim(str_replace(' ','',$aufn_dat));
	$year = substr($aufn_dat,6,4);
	$month = substr($aufn_dat,3,2);
	$day = substr($aufn_dat,0,2);
	IF(checkdate($month,$day,$year))
	{
		$aufndat = $year."-".$month."-".$day." 00:00:00";
		$dto = $year.":".$month.":".$day." 00:00:00";
		//echo $aufndat."<BR>";
		//echo $dto."<BR>";
		$result2 = mysql_query( "UPDATE $table14 SET Caption_Abstract = \"$description\", DateTimeOriginal = '$aufndat' WHERE pic_id = '$pic_id'");
		$desc = htmlentities($description);
		//Aenderungen in Original-Datei speichern, wenn moeglich:
		shell_exec($exiftool." -IPTC:Caption-Abstract=\"$desc\" ".$FN." -overwrite_original > /dev/null &");
		shell_exec($exiftool." -EXIF:DateTimeOriginal='$dto' ".$FN." -overwrite_original > /dev/null &");
		//Aenderungen in jpg-Datei speichern:
		shell_exec($exiftool." -IPTC:Caption-Abstract=\"$desc\" ".$fn." -overwrite_original > /dev/null &");
		shell_exec($exiftool." -EXIF:DateTimeOriginal='$dto' ".$fn." -overwrite_original > /dev/null &");
	}
	ELSE
	{
		echo "Das Datum hat ein falsches Format!<BR>Bitte pr&uuml;fen Sie die Eingabe.";
	}
}

echo "<FONT COLOR='red'>OK!</FONT>";
echo "<textarea name='description' wordwrap style='width:380px; height:90px; background-color:#DFEFFf; font-size:9pt; font-family:Helvitica,Arial;'>".htmlentities($description)."</textarea>";
?>