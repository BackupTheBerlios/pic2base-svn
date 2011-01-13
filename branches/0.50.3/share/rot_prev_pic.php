<?php
IF (!$_COOKIE['login'])
{
	include '../share/global_config.php';
	//var_dump($sr);
	 header('Location: ../../index.php');
}
?>

<script language="javascript" type="text/javascript" src="functions/ShowPicture.js"></script>

<?php
include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

//var_dump($_REQUEST);
/*
Nachtraegliche Lagekorrektur
Dies sollte nur erforderlich sein, wenn Bilder ohne Ausrichtungsinformationen in falscher Lage erfaßt wurden. 
(vor der Erfassung nicht in die lagerichtige Position gebracht wurden):
meta_data.Orientation wird auf 1 gesetzt
Original-, HQ- und V-Bild werden entsprechend der Anforderung gedreht 
*/

IF(array_key_exists('Orientation', $_REQUEST))
{
	$Orientation = $_REQUEST['Orientation'];
}

IF(array_key_exists('FileNameV', $_REQUEST))
{
	$FileNameV = $_REQUEST['FileNameV'];
}

IF(array_key_exists('pic_id', $_REQUEST))
{
	$pic_id = $_REQUEST['pic_id'];
}

IF(array_key_exists('fs_hoehe', $_REQUEST))
{
	$fs_hoehe = $_REQUEST['fs_hoehe'];
}

//bestimmung aller erforderlichen Dateinamen:
$result1 = mysql_query("SELECT * FROM $table2 WHERE pic_id = '$pic_id'");
//Name des Original(jpg)Bildes:
$fn_o = mysql_result($result1, isset($i1), 'FileName');
$file_info_array = explode('.', $fn_o);
$file_info = $file_info_array[0]; //Dateiname ohne Punkt und Extension
//Name des hq-Vorschaubildes:
$fn_hq = $file_info."_hq.jpg";
//Name des V-Vorschaubildes:
$fn_v = $file_info."_v.jpg"; 




SWITCH($Orientation)
{
	case '3':
		//die Bilder muessen 180 gedreht werden:
		$command = buildConvertCommand($sr)." ".$pic_path."/".$fn_o." -rotate 180 ".$pic_path."/".$fn_o."";
		$output = shell_exec($command);
		
		$command = buildConvertCommand($sr)." ".$pic_thumbs_path."/".$fn_v." -rotate 180 ".$pic_thumbs_path."/".$fn_v."";
		$output = shell_exec($command);
		
		$command = buildConvertCommand($sr)." ".$pic_hq_path."/".$fn_hq." -rotate 180 ".$pic_hq_path."/".$fn_hq."";
		$output = shell_exec($command);
	break;
	
	case '6':
		//die Bilder muessen 90 im Uhrzeigersinn gedreht werden:		
		$command = buildConvertCommand($sr)." ".$pic_path."/".$fn_o." -rotate 90 ".$pic_path."/".$fn_o."";
		$output = shell_exec($command);
		
		$command = buildConvertCommand($sr)." ".$pic_thumbs_path."/".$fn_v." -rotate 90 ".$pic_thumbs_path."/".$fn_v."";
		$output = shell_exec($command);
		
		$command = buildConvertCommand($sr)." ".$pic_hq_path."/".$fn_hq." -rotate 90 ".$pic_hq_path."/".$fn_hq."";
		$output = shell_exec($command);
	break;
	
	case '8':
		//die Bilder muessen 90 entgegen dem Uhrzeigersinn gedreht werden:		
		$command = buildConvertCommand($sr)." ".$pic_path."/".$fn_o." -rotate 270 ".$pic_path."/".$fn_o."";
		$output = shell_exec($command);
		
		$command = buildConvertCommand($sr)." ".$pic_thumbs_path."/".$fn_v." -rotate 270 ".$pic_thumbs_path."/".$fn_v."";
		$output = shell_exec($command);
		
		$command = buildConvertCommand($sr)." ".$pic_hq_path."/".$fn_hq." -rotate 270 ".$pic_hq_path."/".$fn_hq."";
		$output = shell_exec($command);
	break;
}
//dann wird lt. Festlegung die Ausrichtung auf 1 gesetzt
$result1 = mysql_query("UPDATE $table14 SET Orientation = '1' WHERE pic_id = '$pic_id'");



$time = time();

echo "Bitte Ansicht<BR> neu laden";
?>