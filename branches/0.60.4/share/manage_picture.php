<?php
include 'global_config.php';
include 'db_connect1.php';

if ( array_key_exists('pic_id',$_GET) )
{
	$pic_id = $_GET['pic_id'];
}

if ( array_key_exists('modus',$_GET) )
{
	$modus = $_GET['modus'];
}

IF($modus == 'save')
{
	$result1 = mysql_query("UPDATE $table2 SET aktiv = '1' WHERE pic_id = $pic_id");
	echo mysql_error();
	IF(!mysql_error())
	{
		echo "<TD colspan='6'><font color='green'>Bild ".$pic_id." wurde in der Datenbank belassen.</font></TD>";
	}
	ELSE
	{
		echo "<TD colspan='6'>
		<p style='color:red; font-wight:bold;'>Es ist ein Fehler aufgetreten. Bild ".$pic_id." konnte nicht wieder hergestellt werden. Bitte informieren Sie Ihren Administrator.</p>
		</TD";
	}
}
ELSEIF($modus == 'delete')
{
	//# # # # # Code basiert auf delete_piture.php # # # # # # # # # # 
	//
	//Die Bild-Daten werden ermittelt:
	$result1 = mysql_query( "SELECT * FROM $table2 WHERE pic_id = '$pic_id'");
	@$num1 = mysql_num_rows($result1);
	//echo $num1."<BR>";
	IF($num1 == '1')
	{
		$row = mysql_fetch_array($result1);
		$pic_id = $row['pic_id'];
		$FileName = $row['FileName'];
		$FileNameOri = $row['FileNameOri'];
		//Angaben fuer die Log-Datei:
		$CaptionAbstract = $row['Caption_Abstract'];
		$Keywords = $row['Keywords'];
		
		$datei_mono = $monochrome_path."/".$pic_id."_mono.jpg";
		$datei_hist = $hist_path."/".$pic_id."_hist.gif";
		$datei_hist_r = $hist_path."/".$pic_id."_hist_0.gif";
		$datei_hist_g = $hist_path."/".$pic_id."_hist_1.gif";
		$datei_hist_b = $hist_path."/".$pic_id."_hist_2.gif";
		$datei_tmp = $pic_path."/tmp/".$pic_id.".jpg";
		
		$datei_ori = $pic_path."/".$FileName;
		$datei_rotated = $pic_rot_path."/".$FileName;
		$datei_hq = $pic_hq_path."/".str_replace('.','_hq.',$FileName);
		$datei_thumbs = $pic_thumbs_path."/".str_replace('.','_v.',$FileName);
	}
	ELSE
	{
		echo "<p style='color:red; font-wight:bold;'>ES LIEGT EIN PROBLEM VOR!<BR>
		ES EXISTIERT KEIN oder MEHR ALS EIN DATENSATZ F&Uuml;R DAS GEW&Auml;HLTE BILD!!</p>";
		return;
	}
	//Tabelle pic_kat wird um alle Eintraege bereinigt fuer die Bild-ID:
	$result2 = mysql_query( "DELETE FROM $table10 WHERE pic_id = '$pic_id'");
	
	//Das Bild wird aus der Tabelle pictures geloescht:
	$result4 = mysql_query( "DELETE FROM $table2 WHERE pic_id = '$pic_id' AND FileName = '$FileName'");
	
	//alle Bilder werden aus der Verzeichnis-Struktur geloescht:
	$l1 = unlink($datei_ori);
	@$l2 = unlink($datei_rotated);
	$l3 = unlink($datei_hq);
	$l4 = unlink($datei_thumbs);
	$l5 = unlink($datei_mono);
	$l6 = unlink($datei_hist);
	$l7 = unlink($datei_hist_r);
	$l8 = unlink($datei_hist_g);
	$l9 = unlink($datei_hist_b);
	IF(@fopen($datei_tmp,'r'))
	{
		$l10 = unlink($datei_tmp);
	}
	
	//Behandlung eventuell vorhandener Nicht-JPG-Bilder:
	//es wird ermittelt, ob im Originale-Ordner weitere Dateien mit dem Stamm-Namen existieren (z.B. 1234567676)
	//Wenn ja, wird geprueft, wieviel hiervon Scene-Dateien sind (z.B. 1234567676-1.jpg) und wieviele Nicht-JPG-Bilder sind (z.B. 1234567676.bmp)
	//nur wenn keine scene-Dateien mehr im Originale-Ordner sind, wird auch die Nicht-JPG-Datei geloescht
	
	$file_info = pathinfo($datei_ori);
	$ext = ".".$file_info['extension'];							//Dateiendung mit Punkt
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
	//wenn keine Stamm-Datei mehr im Originale-Ordner mehr ist wird eine evtl. vorh. Nicht-JPG-Datei geloescht:
		FOREACH($supported_filetypes AS $sft)
		{
			IF(file_exists($pic_path."/".$base_name.".".$sft))
			{
				//echo "Es gibt eine Nicht-JPG-Datei";
				unlink($pic_path."/".$base_name.".".$sft);
			}
		}
	}
	//falls es zu dem geloeschten Bild einen Eintrag in der Tabelle21 (doubletten) gab, wird dieser entfernt:
	$result6 = mysql_query("DELETE FROM $table21 WHERE new_pic_id = '$pic_id'");
	echo mysql_error();
	//log-file im Klartext schreiben:
	$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
	fwrite($fh,"##########\n".date('d.m.Y H:i:s').": vorgemerktes Bild ".$pic_id." (".$FileNameOri.") wurde vom Administrator geloescht. Zugriff von ".$_SERVER['REMOTE_ADDR'].")\nBild-Daten:\nKategorie: ".$Keywords."\nBeschreibung:\n".$CaptionAbstract."\n##########\n");
	fclose($fh);
			
	echo "<TD colspan='6'><font color='red'>Bild ".$pic_id." wurde aus der Datenbank gel&ouml;scht.</font></TD>";
}

?>
