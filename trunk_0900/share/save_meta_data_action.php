<?php
IF (!$_COOKIE['uid'])
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
ini_set('memory_limit', '500M');
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
$result0 = mysql_query("SELECT column_name FROM information_schema.columns WHERE table_name = '$table2'");echo mysql_error();
$num0 = mysql_num_rows($result0);
FOR($i0=0; $i0<$num0; $i0++)
{
	$row_arr[] = mysql_result($result0,$i0);
}
//print_r($row_arr);

$error = 0;		//Initialisierung des Fehler-Zaehlers
FOREACH($_POST AS $key => $value)
{
	//Festlegung: Kleinschreibung: Original-Werte; Grossschreibung: formatierte Werte
	//Kontrolle, ob ein Datum geaendert werden soll:
	
//	echo $key."<BR>";;
		SWITCH($key)
		{
			CASE stristr($key,'datetime'):
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
//			echo "Datum/Zeit formatiert: ".$KEY." / ".$VALUE."<BR>";	
			break;
			
			CASE stristr($key, 'DateCreated'):
			IF($value != '')
			{
				$VAL = explode(" ",$value);
				$VALUE = $VAL[0];
				$KEY = "IPTC:".$key;
				$KEY_db = $key;
				$VALUE_db = $VALUE;
			}
			ELSE
			{
				$KEY = $key;
				$KEY_db = $key;
				$VALUE = "";	
			}
//		echo "Datum formatiert: ".$KEY." / ".$VALUE."<BR>";	
			break;
			
			CASE stristr($key, 'TimeCreated'):
			IF($value != '')
			{
				$VAL = explode(" ",$value);
				$VALUE = $VAL[0];
				$KEY = "IPTC:".$key;
				$KEY_db = $key;
				$VALUE_db = $VALUE;
			}
			ELSE
			{
				$KEY = $key;
				$KEY_db = $key;
				$VALUE = "";	
			}
//		echo "Zeit formatiert: ".$KEY." / ".$VALUE."<BR>";	
			break;
			
			CASE 'Orientation':
			$VALUE = convertOrientationTextToNumber($value);
			$KEY = 'Orientation';
			break;
			
			default:
			$VALUE_db = $value;
			$VALUE = $value;
			$KEY_db = str_replace("-","_",$key);
			$KEY = $key;
			break;
		}
		//echo $KEY_db." / ".$VALUE."<BR>";
//	}
	//Wenn das Metadaten-Feld auch in der Tabelle pictures enthalten ist, wird auch dies aktualisiert:
	IF(in_array($KEY_db, $row_arr))
	{
		@$result1 = mysql_query( "UPDATE $table2 SET $KEY_db = '$VALUE_db' WHERE pic_id = '$pic_id'");
		//echo mysql_error()."<BR>";
	}
	
	//Aktualisierung der Meta-Daten des Bildes:
	if($KEY !== 'pic_id')
	{
		$command = $exiftool. " -".$KEY."='$VALUE' ".$FN." -overwrite_original";
		$return_value = shell_exec($command);
		if($return_value == '')
		{
			echo "<font color='red'><b>Fehler bei</b></font><BR>";
			echo "Key: ".$KEY.", Wert: ".$VALUE."<BR>";
			$error ++;
		}
	}
}
if($error !== 0)
{
	echo "<BR>Bitte beachten Sie die Formatierungsvorschriften f&uuml;r die eingetragenen Werte!";
}

flush();
sleep(0);
?>

<script language="JavaScript">
var error;
error=<?php echo $error;?>;
if(error==0)
{
	window.close();
}
</script>

</body>
</html>
