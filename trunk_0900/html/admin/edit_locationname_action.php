<?php
IF (!$_COOKIE['uid'])
{
  	header('Location: ../../../index.php');
}
else
{
	$uid = $_COOKIE['uid'];
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
$exiftool = buildExiftoolCommand($sr);

if(array_key_exists('record',$_GET))
{
	$pic_id = $_GET['record']; 
}

if(array_key_exists('oldCityname',$_GET))
{
	$oldCityname = $_GET['oldCityname']; 
}

if(array_key_exists('newCityname',$_GET))
{
	$newCityname = $_GET['newCityname'];
	$iptc_city = $newCityname; 
}

$error_code = 0;

IF($newCityname !== $oldCityname)
{  
  	//Aktualisierung der Tabelle pictures:
  	$result1 = mysql_query("UPDATE $table2 SET City = \"$newCityname\" WHERE pic_id = \"$pic_id\"");
  	
  	//Aenderung des Caption_Abstracts und IPTC-Eintrages fuer das betreffende Bild:
  	$FN = strtolower($pic_path."/".restoreOriFilename($pic_id, $sr));
  	
  	//Aktualisierung der Tabelle pictures.Caption_Abstract:
  	$result4 = mysql_query("SELECT Caption_Abstract FROM $table2 WHERE pic_id = '$pic_id'");
  	$CA = mysql_result($result4, isset($i4), 'Caption_Abstract');
  	//Textersetzung:
  	$search = 'Kamerastandort: '.$oldCityname;  		// alter Textbestandteil
  	$replace = 'Kamerastandort: '.$newCityname;			// neue Textergaenzung
  	$CA_new = str_replace($search, $replace, $CA);		// das neue CaptionAbstract
  	IF($CA_new == $CA)									// Wenn es bisher keine Standort-Angabe gab...
  	{
  		$CA_new = $CA.", Kamerastandort: ".$newCityname;
  	}
  	$result3 = mysql_query("UPDATE $table2 SET Caption_Abstract = \"$CA_new\" WHERE pic_id = '$pic_id'");
  	
  	//IPTC.City aendern:
  	$iptc_city = strip_tags($iptc_city);
  	$command = " -IPTC:City=\"$iptc_city\" ".$FN." -overwrite_original -execute -IPTC:Caption-Abstract=\"$CA_new\" ".$FN." -overwrite_original";
  	shell_exec($exiftool." ".$command);
	//Log-Datei schreiben:
	$fh_log = fopen($p2b_path.'pic2base/log/p2b.log','a');
	@fwrite($fh_log,date('d.m.Y H:i:s').": Bild ".$pic_id.": alte Ortsbezeichnung: \"".$oldCityname."\" wurde in \"".$newCityname."\" von User ".$uid." geaendert. (Zugriff von ".$_SERVER['REMOTE_ADDR'].")\n");
	fclose($fh_log); 
}
ELSE
{
	$error_code = 2; //neuer Ortsname ist identisch mit dem alten Ortsnamen -> keine Aenderung notwendig
}

//$text = "Bild-ID: ".$pic_id.", alter Name: ".$oldCityname.", neuer Name: ".$newCityname;

$obj1 = new stdClass();
$obj1->errorCode = $error_code;
//$obj1->text = $text;
$obj1->Userid = $uid;
$output = json_encode($obj1);
echo $output;
?>