<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Bild-Details</title>
  <meta name="GENERATOR" content="Quanta Plus">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="Content-Style-Type" content="text/css">
  <link rel=stylesheet type="text/css" href='../css/format1.css'>
  <link rel="shortcut icon" href="images/favicon.ico">
</head>
<body style='background-color:#999999'>

<?php
//skript speichert die geänderten Meta-Daten in die exif_data-Tabelle und in das betreffende Bild
IF(array_key_exists('pic_id', $_POST))
{
	$pic_id = $_POST['pic_id'];
}
echo "&Auml;nderungen zum Bild-ID: ".$pic_id." werden gespeichert...<BR>";
include_once 'global_config.php';
include_once 'db_connect1.php';
include_once $sr.'/bin/share/functions/main_functions.php';
$FN = $pic_path."/".restoreOriFilename($pic_id, $sr);

FOREACH($_POST AS $key => $value)
{
	//Festlegung: Kleinschreibung: Original-Werte; Großschreibung: formatierte Werte
	//Kontrolle, ob ein Datum geändert werden soll:
	IF(stristr($key,'date'))
	{
		//um das Datum in ein gültiges Format zu wandeln, müssen die Doppelpunkte im Datum ersetzt werden:
		$VAL = explode(" ",$value);
		$VALUE = str_replace(":","-",$VAL[0])." ".$VAL[1];
		$KEY = $key;
		//echo "Datum formatieren: ".$KEY." / ".$VALUE."<BR>";
	}
	ELSE
	{
		SWITCH($key)
		{
			CASE 'Orientation':
			$VALUE = convertOrientationTextToNumber($value);
			$KEY = 'Orientation';
			break;
			
			default:
			$VALUE = utf8_decode($value);
			$KEY = str_replace("-","_",$key);
			break;
		}
		//echo $KEY." / ".$VALUE."<BR>";
	}
	
	$result1 = mysql_query( "UPDATE $table14 SET $KEY = '$VALUE' WHERE pic_id = '$pic_id'");
	echo mysql_error();
	//Aktualisierung der Meta-Daten des Bildes:
	shell_exec($et_path."/exiftool -".$key."='$value' ".$FN." -overwrite_original");
}

flush();
sleep(1);
?>
<script language="JavaScript">
window.close();
</script>

</body>
</html>
