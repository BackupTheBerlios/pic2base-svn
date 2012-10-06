<?php
IF (!$_COOKIE['login'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
	header('Location: ../../../index.php');
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-1">
	<TITLE>pic2base - Stapel-Upload</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
</HEAD>

<BODY onLoad='getUploadFiles()'>
<CENTER>
<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: stapel2.php
 *
 * Copyright (c) 2003 - 2012 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 */

unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/ajax_functions.php';
$result1 = mysql_query( "SELECT id FROM $table1 WHERE username = '$c_username' AND aktiv = '1'"); echo mysql_error();
$user_id = mysql_result($result1, isset($i), 'id');

$start_time = date('d.m.Y, H:i:s');

echo "
<div class='page'>
	<p id='kopf'>pic2base :: Stapelverarbeitung Bild-Upload <span class='klein'>(User: ".$c_username.")</span></p>
		
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>
		</div>
	</div>
		
	<div class='content'>
		<span style='font-size:12px;'>
		<p style='margin:120px 0px; text-align:center'>
		
		<center>
			<p id = 'headline'>Status der Bild-Erfassung</p>
			<p id='zaehler'></p>
			<div id='prog_bar' style='border:solid; border-color:red; width:500px; height:12px; margin-top:30px; margin-bottom:30px; text-align:left; vertical-align:middle'>
				<img src='../../share/images/green.gif' name='bar' />
			</div>
			<p id='meldung'>Bitte haben Sie ein wenig Geduld...</p>
			<p id='counter'></p>
		</center>
		
		</p>
		</span>
	</div>
	<br style='clear:both;' />
	<p id='fuss'><A style='margin-right:745px;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>".$cr."</p>
</div>

</DIV>
</CENTER>
</BODY>";
?>

<script type = text/javascript>
var timeout = 2;
var fileList = null;
var gesamtanzahl;
var starttime = new Date();
var avgTime;

function fileListReceived( responseText )
{
	fileList = JSON.parse( responseText, null );
	if(fileList.anzahl > 0)
	{
		gesamtanzahl = fileList.file_array.length;
		document.getElementById("zaehler").innerHTML = "Anzahl der hochzuladenden Bild-Dateien: " + gesamtanzahl;
		processFile( fileList );
	}
	else
	{
		alert( "Es sind keine Dateien zu bearbeiten." );
		window.location="../start.php";
	}	
}

function showReady(avgTime)
{
	//Erfassungsfenster leeren:
	document.getElementById("headline").innerHTML = "";
	document.getElementById("zaehler").innerHTML = "";
	document.getElementById("meldung").innerHTML = "";
	document.getElementById( "prog_bar" ).style.border = "none";
	document.bar.width = '0';
	document.bar.height = '0';
	//Fertigmeldung ausgeben:
	alert( "Die Erfassung ist abgschlossen.\nDie durchschnittliche Bearbeitungszeit pro Bild betrug " + avgTime + " Sekunden.");
	countDown();
}

function processFile( fileList )
{
	var client = new XMLHttpRequest();
	//alert(fileList.file_array[0].search(/ä|Ä|ö|Ö|ü|Ü|ß|\s/));
	if(fileList.file_array[0].search(/ä|Ä|ö|Ö|ü|Ü|ß|\s/) !== -1)
	{
		alert("Es ist ein Fehler aufgetreten!\n\nDer Dateiname "+ fileList.file_array[0] + " beinhaltet unerlaubte Zeichen.\nDie Erfassung wird abgebrochen.\nBitte korrigieren Sie den Dateinamen und starten dann die Erfassung neu.\nFragen Sie ggf. Ihren Administrator.");
		//alert(fileList.file_array[0]);
		location.href='../start.php';
	}
	else
	{
		client.open("GET", "stapel2_action.php?file=" + fileList.file_array[0], true);
		fileList.file_array.splice( 0, 1 );
		client.onreadystatechange = function()
		{
			if( client.readyState == 4 )
			{
				var result = JSON.parse( client.responseText );
				
				if( result.errorCode != 0 )
				{
					alert( "Fehler: Datei wurde nicht aus dem Upload-Ordner geloescht." );
				}
						
				if( fileList.file_array.length > 0 )
				{
					if( fileList.file_array.length > 1)
					{
						document.getElementById("meldung").innerHTML = "...es verbleiben noch " + fileList.file_array.length + " Bilder...";
					}
					else
					{
						document.getElementById("meldung").innerHTML = "...es verbleibt noch " + fileList.file_array.length + " Bild...";
					}
					var laenge = (gesamtanzahl - fileList.file_array.length) / gesamtanzahl * 500;
					document.bar.src = '../../share/images/green.gif';
					document.bar.width = laenge;
					document.bar.height = '11';
					processFile( fileList );
				}
				else
				{
					//Bearbeitung ist abgeschlossen
					//Berechnung der durchschnittlichen Bearbeitungszeit pro Bild:
					var endtime = new Date();
					var runtime = (endtime.getTime() - starttime.getTime()) / 1000;
					avgTime = Math.round((runtime / gesamtanzahl) * 100) / 100;
					document.getElementById("meldung").innerHTML = "Alle Bilder wurden erfasst.";
					//Aktualisierung des Fortschrittsbalkens auf 100%:
					var laenge = (gesamtanzahl - fileList.file_array.length) / gesamtanzahl * 500;
					document.bar.src = '../../share/images/green.gif';
					document.bar.width = laenge;
					document.bar.height = '11';
					setTimeout("showReady(avgTime)", 2000);
				}
			}
		}
	};
	client.send( null );
}

function countDown()
{
	document.getElementById( "counter" ).innerHTML = "Sie werden in " + timeout.toString() + " Sekunden zur Doublettenpr&uuml;fung weitergeleitet.";
	timeout --;
	if( timeout < 0 )
	{
		window.location='doublettenliste1.php?user_id=' + <?php echo $user_id; ?>;
	}
	else
	{
		setTimeout( "countDown()", 1000 );	
	}
}

</script>
</HTML>