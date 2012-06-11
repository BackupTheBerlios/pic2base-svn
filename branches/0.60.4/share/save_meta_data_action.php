<?php
IF (!$_COOKIE['login'])
{
include '../share/global_config.php';
//var_dump($sr);
  header('Location: ../../index.php');
}
?>

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
//skript speichert die geaenderten Meta-Daten in die pictures-Tabelle und in das betreffende Bild
IF(array_key_exists('pic_id', $_POST))
{
	$pic_id = $_POST['pic_id'];
}
echo "&Auml;nderungen zum Bild-ID: ".$pic_id." werden gespeichert...<BR>";
include_once 'global_config.php';
include_once 'db_connect1.php';
include_once $sr.'/bin/share/functions/main_functions.php';
$exiftool = buildExiftoolCommand($sr);
$FN = $pic_path."/".restoreOriFilename($pic_id, $sr);//echo $FN."<BR>";

//Ermittlung, welche Metadaten-Felder in der Tabelle pictures enthalten sind:
$result0 = mysql_query("SELECT * FROM $table2");
$num0 = mysql_num_fields($result0);
FOR($i0='0'; $i0<$num0; $i0++)
{
	$row_arr[] = mysql_field_name($result0,$i0);
}
//print_r($row_arr);

FOREACH($_POST AS $key => $value)
{
	//Festlegung: Kleinschreibung: Original-Werte; Grossschreibung: formatierte Werte
	//Kontrolle, ob ein Datum geaendert werden soll:
	IF(stristr($key,'date'))
	{
		//um das Datum in ein gueltiges Format zu wandeln, muessen die Doppelpunkte im Datum ersetzt werden:
		IF($value != '')
		{
			$VAL = explode(" ",$value);
			$VALUE = str_replace(":","-",$VAL[0])." ".$VAL[1];
			$KEY = $key;
			$KEY_db = $key;
			$VALUE_db = $VALUE;
		}
		ELSE
		{
			$KEY = $key;
			$KEY_db = $key;
			$VALUE = "";	
		}
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
			$VALUE_db = utf8_decode($value);
			$VALUE = $value;
			$KEY_db = str_replace("-","_",$key);
			$KEY = $key;
			break;
		}
		//echo $KEY_db." / ".$VALUE."<BR>";
	}
	//Wenn das Metadaten-Feld auch in der Tabelle pictures enthalten ist, wird auch dies aktualisiert:
	IF(in_array($KEY_db, $row_arr))
	{
		$result1 = mysql_query( "UPDATE $table2 SET $KEY_db = '$VALUE_db' WHERE pic_id = '$pic_id'");
		echo mysql_error()."<BR>";
	}
	
	//Aktualisierung der Meta-Daten des Bildes:
	$command = $exiftool. " -".$KEY."='$VALUE' ".$FN." -overwrite_original";
	//echo $command."<BR><BR>";
	shell_exec($command);
}

flush();
sleep(0);
?>

<script language="JavaScript">
window.close();
</script>

</body>
</html>
