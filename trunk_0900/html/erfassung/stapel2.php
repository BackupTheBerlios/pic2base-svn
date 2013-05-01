<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
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
	<TITLE>pic2base - Stapel-Upload</TITLE>
	<META NAME="GENERATOR" CONTENT="eclipse">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format2.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script language="JavaScript" src="../../share/functions/resize_elements.js"></script>
	<script language="JavaScript" src="../../share/functions/jquery-1.8.2.min.js"></script>
	<script language="JavaScript">
	  	jQuery.noConflict()
		jQuery(document).ready(checkWindowSize);
		jQuery(window).resize(checkWindowSize); 
	</script>
</HEAD>

<BODY onLoad='getUploadFiles()'>
<CENTER>
<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: stapel2.php
 *
 * Copyright (c) 2003 - 2013 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 */

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/ajax_functions.php';

$result1 = mysql_query( "SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'"); echo mysql_error();
$username = mysql_result($result1, isset($i1), 'username');
$user_id = $uid;

$start_time = date('d.m.Y, H:i:s');

echo "
<div class='page' id='page'>
	
	<div class='head' id='head'>
		pic2base :: Stapelverarbeitung Stapel-Upload <span class='klein'>(User: ".$username.")</span>
	</div>
		
	<div class='navi' id='navi'>
		<div class='menucontainer'>
		</div>
	</div>
		
	<div class='content' id='content'>
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
	
	<div class='foot' id='foot'>
		<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
	</div>
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
	//alert(responseText);
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
	//alert(fileList.file_array[0].search(/^[\w|-]{1,}[.]{1}[\w]{2,4}$/)); //Umlaute, SZ, Leerzeichen, mind. zwei Punkte, Steuerzeichen 
	if(fileList.file_array[0].search(/^[\w|-]{1,}[.]{1}[\w]{2,4}$/) == -1)
	{
		alert("Es ist ein Fehler aufgetreten!\n\nDer Dateiname "+ fileList.file_array[0] + " beinhaltet unerlaubte Zeichen (Umlaute, Leerzeichen, mehrere Punkte etc.).\nDie Erfassung wird abgebrochen.\nBitte korrigieren Sie den Dateinamen und starten dann die Erfassung neu.\nFragen Sie ggf. Ihren Administrator.");
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
	if( timeout < 0 )
	{
		window.location='doublettenliste1.php?user_id=' + <?php echo $user_id; ?>;
	}
	else
	{
		if( timeout > 0 )
		{
			document.getElementById( "counter" ).innerHTML = "Sie werden in " + timeout.toString() + " Sekunden zur Doublettenpr&uuml;fung weitergeleitet.";
		}
		else if(timeout == 0)
		{
			document.getElementById( "counter" ).innerHTML = "Doublettenpr&uuml;fung l&auml;uft...";
		}
		setTimeout( "countDown()", 1000 );	
	}
	timeout --;
}
</script>

</HTML>