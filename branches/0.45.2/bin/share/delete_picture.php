<?php
IF (!$_COOKIE['login'])
{
include '../share/global_config.php';
//var_dump($sr);
  header('Location: ../../index.php');
}

//###############################################################
//wird beim loeschen von Bildern aus der DB verwendet
//###############################################################

include 'global_config.php';
include 'db_connect1.php';
include 'functions/ajax_functions.php';
include 'functions/permissions.php';

//var_dump($_GET);
if ( array_key_exists('c_username',$_GET) )
{
	$c_username = $_GET['c_username'];
}
if ( array_key_exists('FileName',$_GET) )
{
	$FileName = $_GET['FileName'];
}
if ( array_key_exists('pic_id',$_GET) )
{
	$pic_id = $_GET['pic_id'];
}

//log-file schreiben:
$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
fwrite($fh,date('d.m.Y H:i:s')." ".isset($REMOTE_ADDR)." ".$_SERVER['PHP_SELF']." ".$_SERVER['HTTP_USER_AGENT']." ".$c_username."\n");
fclose($fh);
//nicht ganz sauber, aber die eigentliche Rechtekontrolle erfolgt eh schon einen Schritt vorher:
if (hasPermission($c_username, 'deletemypics') OR hasPermission($c_username, 'deleteallpics')) 
{
	//echo "Datei l&ouml;schen??"
	$datei_ori = $pic_path."/".$FileName;
	$datei_rotated = $pic_rot_path."/".$FileName;
	$datei_hq = $pic_hq_path."/".str_replace('.','_hq.',$FileName);
	$datei_thumbs = $pic_thumbs."/".str_replace('.','_v.',$FileName);
	
	//Die Bild-ID wird ermittelt:
	$result1 = mysql_query( "SELECT * FROM $table2 WHERE FileName = '$FileName'");
	@$num1 = mysql_num_rows($result1);
	//echo $num1."<BR>";
	IF($num1 == '1')
	{
		$row = mysql_fetch_array($result1);
		$pic_id = $row['pic_id'];
		$FileNameOri = $row['FileNameOri'];
	}
	ELSE
	{
		echo "<p style='color:red; font-wight:bold;'>ES LIEGT EIN PROBLEM VOR!<BR>
		ES EXISTIERT KEIN oder MEHR ALS EIN DATENSATZ F&Uuml;R DAS GEW&Auml;HLTE BILD!!</p>";
		return;
	}
	echo "... l&ouml;sche Bild ".$pic_id.":<BR><BR>";

	//Tabelle pic_kat wird um alle Eintraege bereinigt fuer die Bild-ID:
	$result2 = mysql_query( "DELETE FROM $table10 WHERE pic_id = '$pic_id'");
	echo mysql_affected_rows(). " Eintr&auml;ge wurden aus der Kategorie-Zuordnungstabelle gel&ouml;scht<BR>";
	
	//Das Bild wird aus der Tabelle pictures geloescht:
	$result4 = mysql_query( "DELETE FROM $table2 WHERE pic_id = '$pic_id' AND FileName = '$FileName'");
	echo mysql_affected_rows(). " Eintrag wurden aus der Bild-Tabelle gel&ouml;scht<BR>";
	
	//EXIF-Data-Taballe wird bereinigt;
	$result6 = mysql_query( "DELETE FROM $table14 WHERE pic_id = '$pic_id'");
	echo mysql_affected_rows(). " Eintrag wurden aus der Meta-Daten-Tabelle gel&ouml;scht<BR>";
	
	//alle Bilder werden aus der Verzeichnis-Struktur geloescht:
	$l1 = unlink($datei_ori);
	IF($l1)
	{
		echo "Datei in Original-Qualit&auml;t wurde gel&ouml;scht<BR>";
	}
	@$l2 = unlink($datei_rotated);
	IF($l2)
	{
		echo "Gedrehte Original-Datei wurde gel&ouml;scht<BR>";
	}
	$l3 = unlink($datei_hq);
	IF($l3)
	{
		echo "HQ-Datei wurde gel&ouml;scht<BR>";
	}
	$l4 = unlink($datei_thumbs);
	IF($l4)
	{
		echo "Vorschau-Bild (Thumb) wurde gel&ouml;scht<BR>";
	}
	//Behandlung eventuell vorhandener Nicht-JPG-Bilder:
	//es wird ermittelt, ob im Originale-Ordner weitere Dateien mit dem Stamm-Namen existieren (z.B. 1234567676)
	//Wenn ja, wird geprueft, wieviel hiervon Scene-Dateien sind (z.B. 1234567676-1.jpg) und wieviele Nicht-JPG-Bilder sind (z.B. 1234567676.bmp)
	//nur wenn keine scene-Dateien mehr im Originale-Ordner sind, wird auch die Nicht-JPG-Datei geloescht
	
	$file_info = pathinfo($datei_ori);
	$ext = ".".$file_info['extension'];				//Dateiendung mit Punkt
	$base_name = str_replace($ext,'',$file_info['basename']);	//Dateiname ohne Punkt und Rumpf 
	//echo $base_name;
	$base_name1 = $base_name.".";
	$base_name2 = $base_name."_";
	//Damit werden alle Dateien mit beliebiger Endung erfasst (12345.jpg; 12345.nef usw.) aber auch alle Scene-Bilder (12345_1.jpg, 12345_2.jpg usw)
	$result5 = mysql_query( "SELECT * FROM $table2 WHERE (FileName LIKE '$base_name1%' OR FileName LIKE '$base_name2%')");
	$num5 = mysql_num_rows($result5);	//es gibt in der DB insges. $num5 Dateien mit dem Stammnamen.
	//echo $num5." Dateien mit Stammnamen in DB<BR>";
	$k = '0';
	FOR($i5='0'; $i5<$num5; $i5++)
	{
		$file_name = mysql_result($result5, $i5, 'FileName');
		IF(file_exists($pic_path."/".$file_name))
		{
			$k++;
		}
	}
	//es befinden sich nun noch weitere $k Dateien im Originale-Ordner.
	//echo "davon ".$k." noch im Originale-Ordner.";
	IF($k == '0')
	{
	//wenn keine Stamm-Datei mehr im Originale-Ordner mehr ist wird eine evtl. vorh. Nicht-JPG-Datei gel�scht:
		FOREACH($supported_filetypes AS $sft)
		{
			IF(file_exists($pic_path."/".$base_name.".".$sft))
			{
				//echo "Es gibt eine Nicht-JPG-Datei";
				unlink($pic_path."/".$base_name.".".$sft);
			}
		}
	}
	echo "<BR>Das Bild im Original-Dateiformat wurde aus der Datenbank gel&ouml;scht.<BR><BR>
	Schlie&szlig;en Sie nun bitte dieses Fenster und aktualisieren dann das pic2base-Fenster.<BR>
	<BR><CENTER><FORM name='zu'><INPUT TYPE='button' name='close' VALUE='Fenster schlie&szlig;en' OnClick='javascript:window.close()' tabindex='1'></FORM></CENTER>";

}
ELSE
{
	echo "SIe haben keine ausreichenden Rechte, um diese Aktion auszuf&uuml;hren!<BR>
	<A HREF='javascript:window.close()'>Fenster schliessen</A>";
}
?>
<script language="javascript">
document.zu.close.focus();
</script>