<?php 
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
  	header('Location: ../../../index.php');
}
else
{
	$uid = $_COOKIE['uid'];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - DB-Wartung</TITLE>
	<META NAME="GENERATOR" CONTENT="eclipse">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format2.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script language="JavaScript" src="../../share/functions/resize_elements.js"></script>
	<script language="JavaScript" src="../../share/functions/jquery-1.8.2.min.js"></script>
	<script language="JavaScript">
	  	jQuery.noConflict();
		jQuery(document).ready(checkWindowSize);
		jQuery(window).resize(checkWindowSize); 
	</script>
</HEAD>

<BODY onLoad = 'getMissingFiles()'>

<CENTER>

<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: db_wartung1.php
 *
 * Copyright (c) 2003 - 2012 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 */

INCLUDE '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
//include $sr.'/bin/share/functions/permissions.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/ajax_functions.php';

//memory_limit dynamisch anpassen, falls der berechnete Wert groesser als der vorhandene Wert ist.
$result1 = mysql_query("SELECT * FROM $table2");
$pic_records = mysql_num_rows($result1);	//Anzahl der Datensaetze in der pictures-Tabelle
//bei 50000 Datensaetzen waren 250M erforderlich, daher wird hier vorsorglich dynamisch angepasst:
$steps = ceil($pic_records / 10000);
//echo "<font color='white'>".$steps." - </font>";
$memory_avail = ini_get('memory_limit');
$memory_value = $steps * 50;
if($memory_value > $memory_avail)
{
	$memory = $memory_value."M";
	ini_set('memory_limit', $memory);
}
//echo "<font color='white'>".ini_get('memory_limit')."</font>";

$user_id = $uid;

echo "
<div class='page' id='page'>

	<div class='head' id='head'>
		pic2base :: Admin-Bereich - Datenbank-Wartung
	</div>
	
	<div class='navi' id='navi'>
		<div class='menucontainer'>";
		//echo $navigation."
		 include $sr.'/bin/html/admin/adminnavigation.php';
		echo "</div>
	</div>
	
	<div id='spalte1'>
		<font color='#efeff7'>
			<p  style='margin-top:20px;'>.</p>
		</font>
		<fieldset style='background-color:none; margin-top:10px;'>
		<legend style='color:blue; font-weight:bold;'>Status der Wartungsarbeiten</legend>";
//########################################################################################################
		echo "<p style='margin-top:50px;'><u>Test 1: Kontrolle auf mehrfache Kategoriezuweisungen</u></p>";
		
		// Zum entfernen von Doubletten (unter der Annahme, dass die Spalte lfdnr nirgends im Programmcode verwendet wird):
		// Alle Mehrfacheinträge von pic_id und kat_id zusammen mit der kleinsten lfdnr in die Tabelle ICE_V_pic_kat_dubls eintragen
		$result20 = mysql_query("INSERT ICE_V_pic_kat_dubls(lfdnr, pic_id, kat_id, anzahl) SELECT MIN(lfdnr) as lfdnr, pic_id, kat_id, count(*) as anzahl FROM pic_kat GROUP BY pic_id, kat_id HAVING COUNT(*) > 1");
		echo mysql_error();
		// Auf der Basis dieser Steuertabelle löschen wir alle korrespondierende Datensätze aus pic_kat
		$result21 = mysql_query("DELETE pk FROM `pic_kat` as pk, `ICE_V_pic_kat_dubls` as pkd WHERE pk.pic_id = pkd.pic_id AND pk.kat_id = pkd.kat_id AND NOT(pk.lfdnr = pkd.lfdnr)");
		echo mysql_error();
		$num21 = mysql_affected_rows();
		IF($num21 == '0')
		{
			$meldung_2 = "<p style='color:green;'>Es gab keine mehrfachen Kategoriezuweisungen.</p>";
		}
		ELSE
		{
			$meldung_2 = "<p style='color:red;'>Anzahl der korrigierten Mehrfachzuweisungen: ".$num21."</p>";
		}
		echo $meldung_2;
//########################################################################################################
		$X = "";
		$meldung_0 = "";
		$meldung_1 = "";
		echo "	
		<p style='margin-top: 50px; margin-bottom:30px;'><u>Test 2: Kontrolle, ob alle Vorschaubilder vorhanden sind</u></p>
		<center>
		Status der &Uuml;berpr&uuml;fung
		<div id='prog_bar' style='border:solid; border-color:red; width:300px; height:12px; margin-top:20px; text-align:left; vertical-align:middle'>
			<img src='../../share/images/green.gif' name='bar' /><br>
			<center>
			<p id = 'record_nr'>".$X."</p>
			</center>
		</div>
		</center>
		
		<p id='ori' style='color:green; margin-top:20px;'></p>
		<p id='hq' style='color:green; margin-top:0px;'></p>
		<p id='thumbs' style='color:green; margin-top:0px;'></p>
		<p id='mono_hist' style='color:green; margin-top:0px;'></p>
		
		<p id='meldung' style='color:green; margin-top:30px;'>Bitte warten Sie einen Moment.<BR>Die Ermittlung der fehlenden Dateien kann eine Weile dauern.</p>
		
		<p id='meldung_0' style='color:green; margin-top:50px;'>".$meldung_0."</p>
	
		<p id='meldung_1' style='color:green; margin-top:50px;'>".$meldung_1."</p>
		</fieldset>
	</div>	
		
	<DIV id='spalte2'>
	
		<font color='#efeff7'>
			<p  style='margin-top:20px;'>.</p>
		</font>
		<fieldset style='background-color:none; margin-top:10px;'>
		<legend style='color:blue; font-weight:bold;'>Hinweis</legend>
			Auf der linken Seite sehen Sie das Ergebnis der &Uuml;berpr&uuml;fung.<BR><BR>
			Bevor Sie weitere Schritte unternehmen, sollten Sie abschlie&szlig;end die Doublettenpr&uuml;fung vornehmen.<BR>
			Dies dauert nur einen Moment, stellt aber sicher, da&szlig; Sie keine Datens&auml;tze doppelt erfa&szlig;t haben.<BR><BR>
			Nach Abschlu&szlig; der Kontrolle klicken Sie hierzu auf diesen Button:
		</fieldset>
		
		<p align='center' id='button'><input type='button' value='zur Doublettenpr&uuml;fung' onClick='location.href=\"#\"' style='color:lightgrey;'></p>
	</DIV>
	
	<div class='foot' id='foot'>
		<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
	</div>

</div>
</CENTER>
</BODY>";

?>

<script type = text/javascript>

var missingFiles = null;
var gesamtzahl;
var anzahl_v;
var anzahl_hq;
var anzahl_hist_mono;
var soll;
var arrayToEdit = null;

function missingFilesReceived( responseText )
{
//	alert(responseText);
	missingFiles = JSON.parse( responseText, null );
	if(missingFiles.anzahl > 0)
	{
		gesamtzahl = missingFiles.anzahl;
		anzahl_v = missingFiles.v_files_array.length;
		anzahl_hq = missingFiles.hq_files_array.length;
		anzahl_ori = missingFiles.ori_files_array.length;
		anzahl_hist_mono = missingFiles.hist_mono_files_array.length;
//		alert( "Es sind " + gesamtzahl + " Dateien neu zu erzeugen.\nDavon Originale: " + anzahl_ori + "\nThumbs: " + anzahl_v + "\nHQ: " + anzahl_hq + "\nMono/Hist: " + anzahl_hist_mono);
		if(	anzahl_ori > 0)
		{
//			alert("erzeuge fehlende Original-Dateien...");
			processFile( missingFiles, "ori" );
		}
		else if( anzahl_hq > 0)
		{
//			alert("erzeuge fehlende hq-Dateien...");
			processFile( missingFiles, "hq" );
		}
		else if( anzahl_v > 0)
		{
//			alert("erzeuge fehlende v-Dateien...");
			processFile( missingFiles, "v" );
		}
		else if( anzahl_hist_mono > 0)
		{
//			alert("erzeuge fehlende hist-Dateien...");
			processFile( missingFiles, "hist_mono" );
		}
	}
	else
	{
		document.getElementById("meldung").innerHTML = "Die Bearbeitung ist abgeschlossen.";
		document.getElementById("button").innerHTML = "<input type='button' value='zur Doublettenpr&uuml;fung' onClick='location.href=\"../../html/erfassung/doublettenliste1.php?method=all&user_id=<?php echo $user_id; ?>\"'>";
		document.bar.src = '../../share/images/green.gif';
		document.bar.width = '300';
		document.bar.height = '11';
	}	
}

function processFile( missingFiles, filetype )
{
	var client = new XMLHttpRequest();
	
	if( filetype == "ori" )
	{
		arrayToEdit = missingFiles.ori_files_array;
	}
	else if( filetype == "hq" )
	{
		arrayToEdit = missingFiles.hq_files_array;
	}
	else if( filetype == "v" )
	{
		arrayToEdit = missingFiles.v_files_array;
	}
	else if( filetype == "hist_mono" )
	{
		arrayToEdit = missingFiles.hist_mono_files_array;
	}
	
	client.open("GET", "../../share/restore_missing_files.php?pic_id=" + arrayToEdit[0] + "&filetype=" + filetype, true);
	
	arrayToEdit.splice( 0, 1 );
	
	client.onreadystatechange = function()
	{
		if( client.readyState == 4 )
		{
//			alert("db_wartung1.php, processFile: " + client.responseText);
			var result = JSON.parse( client.responseText );
			
			if( result.errorCode != 0 )
			{
//				alert( "Fehler: Datei wurde nicht korrekt erzeugt." );
//				Wenn der Datensatz entfernt werden musste, muss die Seite neu geladen werden, da die Berechnung der fehlenden Bilder aktualisiert werden muss!:
				if (result.errorCode == 99)
				{
					alert("Es musste ein Bild aus der Datenbank entfernt werden.\n\nWeitere Informationen entnehmen Sie bitte der Log-Datei.");
					location.href='db_wartung1.php';
				}
			}
					
			if( arrayToEdit.length > 0 )
			{
				if( filetype == "ori" )
				{
					document.getElementById("ori").innerHTML = "Es werden die fehlenden Original-Bilder erstellt...";
					document.getElementById("button").innerHTML = "<input type='button' value='zur Doublettenpr&uuml;fung' onClick='location.href=\"#\"' style='color:lightgrey;'>";
					soll = anzahl_hq;
				}
				else if( filetype == "hq" )
				{
					document.getElementById("hq").innerHTML = "Es werden die fehlenden HQ-Vorschaubilder erstellt...";
					document.getElementById("button").innerHTML = "<input type='button' value='zur Doublettenpr&uuml;fung' onClick='location.href=\"#\"' style='color:lightgrey;'>";
					soll = anzahl_hq;
				}
				else if( filetype == "v" )
				{
					document.getElementById("thumbs").innerHTML = "Es werden die fehlenden Thumbs erzeugt...";
					document.getElementById("button").innerHTML = "<input type='button' value='zur Doublettenpr&uuml;fung' onClick='location.href=\"#\"' style='color:lightgrey;'>";
					soll = anzahl_v;
				}
				else if(filetype == "hist_mono" )
				{
					document.getElementById("mono_hist").innerHTML = "Histogramme und monochrome Bilder werden erstellt...";
					document.getElementById("button").innerHTML = "<input type='button' value='zur Doublettenpr&uuml;fung' onClick='location.href=\"#\"' style='color:lightgrey;'>";
					soll = anzahl_hist_mono;
				}
				
				if( arrayToEdit.length > 1)
				{
					document.getElementById("meldung").innerHTML = "...es verbleiben noch " + arrayToEdit.length + " Bilder...";
				}
				else
				{
					document.getElementById("meldung").innerHTML = "...es verbleibt noch " + arrayToEdit.length + " Bild...";
				}
				var laenge = ((soll - arrayToEdit.length) / soll) * 300;
				document.bar.src = '../../share/images/green.gif';
				document.bar.width = laenge;
				document.bar.height = '11';
				processFile( missingFiles, filetype );
			}
			else
			{
				if( filetype == "ori" )
				{
					document.getElementById("ori").innerHTML = "Die fehlenden Original-Bilder wurden erstellt...";
					getMissingFiles();
				}
				else if( filetype == "hq" )
				{
					document.getElementById("hq").innerHTML = "Die fehlenden HQ-Vorschaubilder wurden erstellt...";
					getMissingFiles();
				}
				else if( filetype == "v" )
				{
					document.getElementById("thumbs").innerHTML = "Die fehlenden Thumbs wurden erstellt...";
					getMissingFiles();
				}
				else if(filetype == "hist_mono" )
				{
					document.getElementById("mono_hist").innerHTML = "Histogramme und monochrome Bilder wurden erstellt...";
				}
				//Aktualisierung des Fortschrittsbalkens auf 100%:
				var laenge = (gesamtzahl - arrayToEdit.length) / gesamtzahl * 300;
				document.bar.src = '../../share/images/green.gif';
				document.bar.width = laenge;
				document.bar.height = '11';
				document.getElementById("meldung").innerHTML = "Alle fehlenden Bilder wurden erzeugt.";
				document.getElementById("button").innerHTML = "<input type='button' value='zur Doublettenpr&uuml;fung' onClick='location.href=\"../../html/erfassung/doublettenliste1.php?method=all&user_id=<?php echo $user_id; ?>\"'>";
			}
		}
	};
	client.send( null );
}

</script>
<HTML>