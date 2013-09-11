<?php
IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../index.php');
}
else
{
	$uid = $_COOKIE['uid'];
}
echo "Zeit-Modus: ".$_COOKIE['show_mod']."<BR>";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-1">
	<TITLE>pic2base - Datei l&ouml;schen...</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../css/format1.css'>
	<link rel="shortcut icon" href="images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>
<DIV Class="klein">

<?php 
//###############################################################
//wird beim loeschen von Bildern aus der DB verwendet
//###############################################################

include 'global_config.php';
include 'db_connect1.php';
include 'functions/ajax_functions.php';
include 'functions/permissions.php';

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');

if ( array_key_exists('pic_id',$_GET) )
{
	$pic_id = $_GET['pic_id'];
}

// normale Benutzer setzen beim loeschen nur den Bildstatus auf inaktiv (pictures.aktiv = 0); Ein Admin loescht wirklich
if(hasPermission($uid, 'adminlogin', $sr))
{
	//echo "User ist Mitglied der Admin-Gruppe, Bild wird geloescht, wenn er darf<BR>";
	if (hasPermission($uid, 'deletemypics', $sr) OR hasPermission($uid, 'deleteallpics', $sr)) 
	{
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
		
		echo "<p style='color:white; font-wight:bold;'>";
		echo "... l&ouml;sche Bild ".$pic_id.":<BR><BR>";
	
		//Tabelle pic_kat wird um alle Eintraege bereinigt fuer die Bild-ID:
		$result2 = mysql_query( "DELETE FROM $table10 WHERE pic_id = '$pic_id'");
		echo mysql_affected_rows(). " Eintr&auml;ge wurden aus der Kategorie-Zuordnungstabelle gel&ouml;scht<BR>";
		
		//Das Bild wird aus der Tabelle pictures geloescht:
		$result4 = mysql_query( "DELETE FROM $table2 WHERE pic_id = '$pic_id' AND FileName = '$FileName'");
		echo mysql_affected_rows(). " Eintrag wurden aus der Bild-Tabelle gel&ouml;scht<BR>";
		
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
		$l5 = unlink($datei_mono);
		IF($l5)
		{
			echo "Monochrome-Vorschau wurde gel&ouml;scht<BR>";
		}
		$l6 = unlink($datei_hist);
		IF($l6)
		{
			echo "Histogramm (gr) wurde gel&ouml;scht<BR>";
		}
		$l7 = unlink($datei_hist_r);
		IF($l7)
		{
			echo "Histogramm (r) wurde gel&ouml;scht<BR>";
		}
		$l8 = unlink($datei_hist_g);
		IF($l8)
		{
			echo "Histogramm (g) wurde gel&ouml;scht<BR>";
		}
		$l9 = unlink($datei_hist_b);
		IF($l9)
		{
			echo "Histogramm (b) wurde gel&ouml;scht<BR>";
		}
		IF(@fopen($datei_tmp,'r'))
		{
			$l10 = unlink($datei_tmp);
			IF($l10)
			{
				echo "tempor&auml;re Vorschaudatei wurde gel&ouml;scht<BR>";
			}
		}
		ELSE
		{
			echo "Es war kein temp. Vorschaubild vorhanden.<BR>";
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
		//echo "Bild-ID: ".$pic_id."<BR>";
		$num6 = 0;
		$result6 = mysql_query("SELECT $table21.old_pic_id, $table21.new_pic_id, $table2.pic_id, $table2.FileNameOri 
		FROM $table2, $table21 
		WHERE $table21.new_pic_id = '$pic_id'
		AND $table2.pic_id = $table21.old_pic_id");
		$FileNameOri_ori = mysql_result($result6, isset($i6), 'FileNameOri');
		//echo $FileNameOri_ori;
		@$result7 = mysql_query("DELETE FROM $table21 WHERE new_pic_id = '$pic_id'");
		//echo mysql_error();
		//log-file in Abhaengigkeit der Art der Loeschung (Doublette oder normales Bild) im Klartext schreiben:
		$num7 = mysql_affected_rows();
		//echo "Anz. betroffener Zeilen: ".$num6."<BR>";
		if($num7 > 0)
		{
			//es wurde eine Doublette entfernt
			$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
			fwrite($fh,"##########\n".date('d.m.Y H:i:s').": Doublette ".$FileNameOri." zum Original ".$FileNameOri_ori." wurde von ".$username." geloescht. (Aufruf von ".$_SERVER['REMOTE_ADDR'].")\nBild-Daten:\nKategorie: ".$Keywords."\nBeschreibung: ".$CaptionAbstract."\n##########\n");
			fclose($fh);
		}
		else
		{
			//es wurde ein normales Bild geloescht
			$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
			fwrite($fh,"##########\n".date('d.m.Y H:i:s').": Bild ".$pic_id." (".$FileNameOri.") wurde von ".$username." geloescht. (Aufruf von ".$_SERVER['REMOTE_ADDR'].")\nBild-Daten:\nKategorie: ".$Keywords."\nBeschreibung: ".$CaptionAbstract."\n##########\n");
			fclose($fh);
		}
		
		
		echo "<BR>Die Original-Datei wurde gel&ouml;scht.<BR><BR>
		<BR><CENTER><FORM name='zu'><INPUT TYPE='button' name='close' VALUE='Fenster schlie&szlig;en' OnClick='javascript:window.close();window.opener.location.reload();' tabindex='1'></FORM></CENTER></p>";
	}
	ELSE
	{
		echo "Sie haben keine ausreichenden Rechte, um diese Aktion auszuf&uuml;hren!<BR>
		<A HREF='javascript:window.close()'>Fenster schliessen</A></p>"; 
	}
}
ELSE
{
	//echo "User darf nur vormerken<BR>";
	//darf der user ueberhaupt loeschen?
	if (hasPermission($uid, 'deletemypics', $sr) OR hasPermission($uid, 'deleteallpics', $sr)) 
	{
		//Bild-Status wird auf inaktiv gesetzt (aktiv = 0)
		$result1 = mysql_query("UPDATE $table2 SET aktiv = 0 WHERE pic_id = '$pic_id'");
		echo mysql_error();
		IF(!mysql_error())
		{
			echo "<p style='color:white; font-wight:bold;'>Bild ".$pic_id." wurde gel&ouml;scht.</p><BR>
			<BR><CENTER><FORM name='zu'><INPUT TYPE='button' name='close' VALUE='Fenster schlie&szlig;en' OnClick='javascript:window.close();window.opener.location.reload();' tabindex='1'></FORM></CENTER></p>";
			//log-file im Klartext schreiben:
			$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
			fwrite($fh,">>>>>>>>>>\n".date('d.m.Y H:i:s').": Bild ".$pic_id." wurde von ".$username." zum loeschen vorgemerkt. (Aufruf von ".$_SERVER['REMOTE_ADDR'].")\n<<<<<<<<<<\n");
			fclose($fh);
		}
	}
	ELSE
	{
		echo "<p style='color:yellow; font-wight:bold;'>Sie haben keine ausreichenden Rechte, um diese Aktion auszuf&uuml;hren!<BR>
		<A HREF='javascript:window.close()'>Fenster schliessen</A></p>"; 
	}
}

?>
<script language="javascript">
document.zu.close.focus();
setTimeout("opener.location.reload(); window.close()", 500);
</script>
</DIV>
</CENTER>
</BODY>
</HTML>