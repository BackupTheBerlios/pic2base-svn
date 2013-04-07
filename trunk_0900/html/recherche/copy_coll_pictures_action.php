<?php

IF ($_COOKIE['uid'])
{
	$uid = $_COOKIE['uid'];
}

if (array_key_exists('pic_id',$_GET))
{
	$pic_id = $_GET['pic_id'];		//dieses Bild soll kopiert werden
}

if (array_key_exists('coll_id',$_GET))
{
	$coll_id = $_GET['coll_id'];		//dieses Bild soll kopiert werden
}

$error_code = NULL;

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');

//	$error_code = 0;	//fehlerfrei
//	$error_code = 1;	//Fehler!

$exiftool = buildExiftoolCommand($sr);
$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');

$result1 = mysql_query("SELECT * FROM $table24 WHERE coll_id = '$coll_id'");

//Der Kollektionsname wird von div. Sonderzeichen bereinigt und auf max. 50 Zeichen verkuerzt:
$array_1 = array('ä', 'Ä', 'ö', 'Ö', 'ü', 'Ü', 'ß');	//Array der moeglichen Vorkommen
$array_2 = array('ae', 'Ae', 'oe', 'Oe', 'ue', 'Ue', 'ss'); 	//Array der Ersetzungen
$anzahl = count($array_1);
$coll_name = mysql_result($result1, isset($i), 'coll_name');

for($x=0; $x<$anzahl; $x++)
{
	$coll_name = str_replace($array_1[$x], $array_2[$x], $coll_name);
}
//diese Zeichen lassen sich per array nicht verarbeiten:				
$coll_name = str_replace(' ', '_', $coll_name);
$coll_name = str_replace(',', '_', $coll_name);
$coll_name = str_replace('.', '_', $coll_name);
$coll_name = str_replace(':', '_', $coll_name);

$coll_name = substr($coll_name, 0, 50);

// Bestimmung des int. Dateinamens der herunterzuladenden Bilddatei:
$result2 = mysql_query("SELECT FileName FROM $table2 WHERE pic_id = '$pic_id'");
$FileName = mysql_result($result2, isset($i2), 'FileName');
$datei = $pic_path."/".$FileName;
$target = $ftp_path."/".$uid."/downloads/".$FileName;

//Bestimmung der Position des Bildes innerhalb der Kollektion:
$result3 = mysql_query("SELECT position FROM $table25 WHERE pic_id = '$pic_id' AND coll_id = '$coll_id'");
//zur Umbenennung der Bilder entsprechend dem Schema 'coll_name..pic_position' wird der Kollektionsname und die Position des Bildes innerhalb dieser bestimmt:
$position = mysql_result($result3, isset($i3), 'position');
if($position < 10)
{
	$position = '000'.$position;
}
elseif($position < 100 AND $position >= 10)
{
	$position = '00'.$position;
}
elseif($position < 1000 AND $position >= 100)
{
	$position = '0'.$position;
}
//daraus ergibt sich als Ziel:
$coll_filename = "coll_".$coll_name."_pic_".$position;
$target = $ftp_path."/".$uid."/downloads/".$coll_filename.".jpg";
	
if(@copy($datei,$target))
{
	$result2 = mysql_query( "UPDATE $table2 SET ranking = ranking + 1 WHERE pic_id = '$pic_id'");
	//Log-Datei schreiben:
	$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
	fwrite($fh,date('d.m.Y H:i:s').": Bild ".$pic_id." wurde von ".$username." heruntergeladen. (Zugriff von ".$_SERVER['REMOTE_ADDR'].")\n");
	fclose($fh);
	$error_code = 0;
}
else
{
	//echo "Konnte die Datei $FileName nicht kopieren!<BR>";
	$error_code = 1;
}

//es wird geprueft, ob ggf. ein Originalbild als NICHT-JPG vorliegt
$file_info = pathinfo($datei);
$base_name = substr($file_info['basename'],0,-4);
//echo $base_name;
$k = '0';
FOREACH($supported_filetypes AS $sft)
{
	IF(file_exists($pic_path."/".$base_name.".".$sft) AND $sft !== 'jpg')
	{
		copy($pic_path."/".$base_name.".".$sft, $ftp_path."/".$uid."/downloads/".$coll_filename.".".$sft);
		//die Meta-Daten dieses nicht-jpg-Bildes werden in das bereits herauskopierte jpg-Bild uebertragen:
		$command = $exiftool." -tagsFromFile ".$ftp_path."/".$uid."/downloads/".$coll_filename.".".$sft." ".$ftp_path."/".$uid."/downloads/".$coll_filename.".jpg -overwrite_original";
		//echo $command;
		shell_exec($command." > /dev/null &");
		$k++;
	}
}

$obj1 = new stdClass();
$obj1->coll_id = $coll_id;
$obj1->errorCode = $error_code;
$obj1->Datei = $pic_id;
$obj1->Userid = $uid;
$output = json_encode($obj1);
echo $output;
?>