<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - DB-Wartung</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll = "auto" onLoad = 'getMissingFiles()'>

<CENTER>

<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: kat_repair1.php
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

unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
}
INCLUDE '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/permissions.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/ajax_functions.php';

$user_id = getUserId($c_username, $sr);

IF(hasPermission($c_username, 'editkattree'))
{
	$navigation = "
			<a class='navi' href='kat_sort1.php'>Sortierung</a>
			<a class='navi_blind' href='kat_repair1.php'>Wartung</a>
			<a class='navi' href='../../html/admin/adminframe.php'>Zur&uuml;ck</a>
			<a class='navi_blind'></a>
			<a class='navi_blind'></a>
			<a class='navi_blind'></a>
			<a class='navi_blind'></a>
			<a class='navi' href='../../html/start.php'>zur Startseite</a>
			<a class='navi' href='../../html/help/help1.php?page=5'>Hilfe</a>
			<a class='navi' href='$inst_path/pic2base/index.php'>Logout</a>";
}
ELSE
{
	header('Location: ../../../index.php');
}

echo "
<div class='page'>

	<p id='kopf'>pic2base :: Admin-Bereich - Datenbank-Wartung</p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
		//echo $navigation."
		 include '../adminnavigation.php';
		echo "</div>
	</div>
	
	<div id='spalte1'>";
//########################################################################################################
		echo "<p style='margin-top:50px;'><u>Test 1: Kontrolle auf mehrfache Kategoriezuweisungen</u></p>";
		
		// Zum entfernen von Dubletten (unter der Annahme, dass die Spalte lfdnr nirgends im Programmcode verwendet wird):
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
		
		<p id='hq' style='color:green; margin-top:20px;'></p>
		<p id='thumbs' style='color:green; margin-top:0px;'></p>
		<p id='mono_hist' style='color:green; margin-top:0px;'></p>
		
		<p id='meldung' style='color:green; margin-top:30px;'></p>
		
		<p id='meldung_0' style='color:green; margin-top:50px;'>".$meldung_0."</p>
	
		<p id='meldung_1' style='color:green; margin-top:50px;'>".$meldung_1."</p>
	</div>	
		
	<DIV id='spalte2'>
		<p id='elf' style='background-color:white; padding: 5px; width: 365px; margin-top: 20px; margin-left: 20px;'>Hinweis:<BR><BR>
		Auf der linken Seite sehen Sie das Ergebnis der &Uuml;berpr&uuml;fung.<BR><BR>
		Bevor Sie weitere Schritte unternehmen, sollten Sie abschlie&szlig;end die Dublettenpr&uuml;fung vornehmen.<BR>
		Dies dauert nur einen Moment, stellt aber sicher, da&szlig; Sie keine Datens&auml;tze doppelt erfa&szlig;t haben.<BR><BR>
		Nach Abschlu&szlig; der Kontrolle klicken Sie hierzu auf diesen Button:</p>
		<p align='center' id='button'><input type='button' value='zur Dublettenpr&uuml;fung' onClick='location.href=\"#\"' style='color:lightgrey;'></p>
	</DIV>
	
	<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>

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
	missingFiles = JSON.parse( responseText, null );
	if(missingFiles.anzahl > 0)
	{
		gesamtzahl = missingFiles.anzahl;
		anzahl_v = missingFiles.v_files_array.length;
		anzahl_hq = missingFiles.hq_files_array.length;
		anzahl_hist_mono = missingFiles.hist_mono_files_array.length;
//		alert( "Es sind " + gesamtzahl + " Dateien neu zu erzeugen.\nDavon Thumbs: " + anzahl_v + "\nHQ: " + anzahl_hq + "\nMono/Hist: " + anzahl_hist_mono);
		if(	anzahl_hq > 0)
		{
			processFile( missingFiles, "hq" );
		}
		else if( anzahl_v > 0)
		{
			processFile( missingFiles, "v" );
		}
		else if( anzahl_hist_mono > 0)
		{
			processFile( missingFiles, "hist_mono" );
		}
	}
	else
	{
		document.getElementById("meldung").innerHTML = "Die Bearbeitung ist abgeschlossen.";
		document.getElementById("button").innerHTML = "<input type='button' value='zur Dublettenpr&uuml;fung' onClick='location.href=\"../../html/erfassung/doublettenliste1.php?method=all&user_id=<?php echo $user_id; ?>\"'>";
//		window.location="../start.php";
	}	
}

function processFile( missingFiles, filetype )
{
	var client = new XMLHttpRequest();
	
	if( filetype == "hq" )
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
			var result = JSON.parse( client.responseText );
			
			if( result.errorCode != 0 )
			{
				alert( "Fehler: Datei wurde nicht korrekt erzeugt." );
			}
					
			if( arrayToEdit.length > 0 )
			{
				if( filetype == "hq" )
				{
					document.getElementById("hq").innerHTML = "Es werden die fehlenden HQ-Vorschaubilder erstellt...";
					document.getElementById("button").innerHTML = "<input type='button' value='zur Dublettenpr&uuml;fung' onClick='location.href=\"#\"' style='color:lightgrey;'>";
					soll = anzahl_hq;
				}
				else if( filetype == "v" )
				{
					document.getElementById("thumbs").innerHTML = "Es werden die fehlenden Thumbs erzeugt...";
					document.getElementById("button").innerHTML = "<input type='button' value='zur Dublettenpr&uuml;fung' onClick='location.href=\"#\"' style='color:lightgrey;'>";
					soll = anzahl_v;
				}
				else if(filetype == "hist_mono" )
				{
					document.getElementById("mono_hist").innerHTML = "Histogramme und monochrome Bilder werden erstellt...";
					document.getElementById("button").innerHTML = "<input type='button' value='zur Dublettenpr&uuml;fung' onClick='location.href=\"#\"' style='color:lightgrey;'>";
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
				if( filetype == "hq" )
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
				document.getElementById("button").innerHTML = "<input type='button' value='zur Dublettenpr&uuml;fung' onClick='location.href=\"../../html/erfassung/doublettenliste1.php?method=all&user_id=<?php echo $user_id; ?>\"'>";
			}
		}
	};
	client.send( null );
}

</script>
<HTML>