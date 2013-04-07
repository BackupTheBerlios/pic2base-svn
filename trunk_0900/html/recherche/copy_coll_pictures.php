<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Kopiere Kollektion</title>
  <meta name="GENERATOR" content="eclipse">
  <meta name="AUTHOR" content="k. henneberg">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel=stylesheet type='text/css' href='../../css/format2.css'>
  <link rel="shortcut icon" href="../../share/images/favicon.ico">
  <script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
  <script language="JavaScript" src="../../share/functions/resize_elements.js"></script>
  <script language="JavaScript" src="../../share/functions/jquery-1.8.2.min.js"></script>
  <script language="JavaScript">
  	jQuery.noConflict();
	jQuery(document).ready(checkWindowSize);
	jQuery(window).resize(checkWindowSize); 
  </script>
</head>

<?php
IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
  	header('Location: ../../index.php');
}

if ( array_key_exists('uid',$_COOKIE) )
{
	$uid = $_COOKIE['uid'];
}

if ( array_key_exists('coll_id',$_GET) )
{
	$coll_id = $_GET['coll_id'];
}
//#######################################################################################################################################################
//
//Datei wird verwendet, um alle Bilder einer Kollektion in den Download-Ordner des angemeldeten Users zu kopieren, falls dieser die Berechtigung dazu hat
//
//#######################################################################################################################################################

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/ajax_functions.php';
include $sr.'/bin/share/functions/permissions.php';

echo "
<BODY onLoad=\"getCollectionDownloadFiles('$coll_id')\">
	<DIV Class='klein'>
		<div id='page'>
		
			<div id='head'>
				pic2base :: Bilder kopieren
			</div>
			
			<div id='navi'>
				<div class='menucontainer'></div>
			</div>
			
			<div id='content'>		
				<fieldset style='background-color:none; margin-top:10px;'>
				<legend style='color:blue; font-weight:bold;'>Kopiere alle Bilder der Kollektion in den Download-Ordner...</legend>
				<div id='scrollbox0' style='overflow-y:scroll;'>";

					echo "
					<center>
						<p id = 'headline' style='margin-top:100px;'>Status der Bild-Erfassung</p>
						<p id='zaehler'></p>
							<div id='prog_bar' style='border:solid; border-color:red; width:500px; height:12px; margin-top:30px; margin-bottom:30px; text-align:left; vertical-align:middle'>
								<img src='../../share/images/green.gif' name='bar' />
							</div>
						<p id='meldung'>Bitte haben Sie ein wenig Geduld...</p>
						<p id='counter'></p>
					</center>";
		
					echo "
				</div>
				</fieldset>
			</div>
			
			<div id='foot'>
				<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
			</div>
		
		</div>
	</DIV>
</body>";
?>

<script type = text/javascript>
var timeout = 1;
var fileList = null;
var gesamtanzahl;
var starttime = new Date();
var avgTime;

function fileListReceived( responseText )
{
	//alert("Fkt. fileListReceived: " + responseText);
	fileList = JSON.parse( responseText, null );
	//alert("Fkt. fileListReceived - Coll-ID : " + fileList.coll_id);
	if(fileList.anzahl > 0)
	{
		coll_id = fileList.coll_id;
		gesamtanzahl = fileList.file_array.length;
		document.getElementById("zaehler").innerHTML = "Anzahl der herunterzuladenden Bild-Dateien: " + gesamtanzahl;
		processFile( fileList, coll_id );
	}
	else
	{
		alert( "Es sind keine Dateien zu kopieren." );
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
	alert( "Der Kopiervorgang ist abgschlossen.\nDie durchschnittliche Bearbeitungszeit pro Bild betrug " + avgTime + " Sekunden.");
	countDown();
}

function processFile( fileList, coll_id )
{
	var client = new XMLHttpRequest();
	client.open("GET", "copy_coll_pictures_action.php?pic_id=" + fileList.file_array[0] + "&coll_id=" + coll_id, true);
	fileList.file_array.splice( 0, 1 );
	client.onreadystatechange = function()
	{
		if( client.readyState == 4 )
		{
			//alert("Fkt. ProcessFile: " + client.responseText);
			var result = JSON.parse( client.responseText );
			
			if( result.errorCode != 0 )
			{
				alert( "Unbekannter Fehler: Datei " + result.Datei + ".jpg konnte nicht kopiert werden." );
			}
					
			if( fileList.file_array.length > 0 )
			{
				if( fileList.file_array.length > 1)
				{
					document.getElementById("meldung").innerHTML = "...es verbleiben noch " + fileList.file_array.length + " Dateien...";
				}
				else
				{
					document.getElementById("meldung").innerHTML = "...es verbleibt noch " + fileList.file_array.length + " Datei...";
				}
				var laenge = (gesamtanzahl - fileList.file_array.length) / gesamtanzahl * 500;
				document.bar.src = '../../share/images/green.gif';
				document.bar.width = laenge;
				document.bar.height = '11';
				processFile( fileList, coll_id );
			}
			else
			{
				//Bearbeitung ist abgeschlossen
				//Berechnung der durchschnittlichen Bearbeitungszeit pro Bild:
				var endtime = new Date();
				var runtime = (endtime.getTime() - starttime.getTime()) / 1000;
				avgTime = Math.round((runtime / gesamtanzahl) * 100) / 100;
				document.getElementById("meldung").innerHTML = "Kopiervorgang abgeschlossen.";
				//Aktualisierung des Fortschrittsbalkens auf 100%:
				var laenge = (gesamtanzahl - fileList.file_array.length) / gesamtanzahl * 500;
				document.bar.src = '../../share/images/green.gif';
				document.bar.width = laenge;
				document.bar.height = '11';
				setTimeout("showReady(avgTime)", 500);
			}
		}
	};
	client.send( null );
}

function countDown()
{
	if( timeout < 0 )
	{
		window.location='recherche2.php?pic_id=0&mod=collection';
	}
	else
	{
		if( timeout > 0 )
		{
			document.getElementById( "counter" ).innerHTML = "Sie werden in " + timeout.toString() + " Sekunden zur Kollektione-Auswahlseite weitergeleitet.";
		}
		else if(timeout == 0)
		{
			document.getElementById( "counter" ).innerHTML = "Weiterleitung l&auml;uft...";
		}
		setTimeout( "countDown()", 500 );	
	}
	timeout --;
}
</script>
</html>