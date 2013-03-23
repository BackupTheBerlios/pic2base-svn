<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../../index.php');
}
ini_set('memory_limit','300M');
$pic_ID = array();
$obj = new stdClass();
FOREACH ($_POST AS $key => $post)
{
	//echo "Schluessel / Wert: ".$key." / ".$post."<BR>";
	IF (substr($key,0,3) == 'pic')
	{
		//echo substr($key,7,strlen($key)-7)."<BR>";
		$pic_ID[] = substr($key,7,strlen($key)-7);
	}
	
	if($key == 'description')
	{
		$description = $post;
		$description = strip_tags($description);
	}
}

$obj->description = $description;
$obj->pic_anzahl = count($pic_ID);
$obj->pic_array = $pic_ID;
$output = json_encode($obj);
//echo "<font color='white'>".$output." - ".ini_get('memory_limit')."</font>";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Beschreibung speichern</TITLE>
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

<BODY LANG="de-DE" scroll = "auto" onLoad='picDescList(<?php echo json_encode($obj); ?>)'>
<CENTER>
<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: edit_desc_daten_action2.php
 *
 * Copyright (c) 2005 - 2012 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 * ##########  ab Version 0.60.4 (30.09.2012) verwendet; Version mit AJAX-basierter Fortschrittsanzeige  ###########
 */
 
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$exiftool = buildExiftoolCommand($sr);

if ( array_key_exists('kat_id',$_POST) )
{
	$kat_id = $_POST['kat_id'];
}
else
{
	$kat_id = '0';
}
if ( array_key_exists('ID',$_GET) )
{
	$ID = $_GET['ID'];
}
if ( array_key_exists('art',$_GET) )
{
	$art = $_GET['art'];
}

//Variablen-Umbenennung fuer die Ruecksprung-Adresse:
// $kat_back = $kat_id;
// $ID_back = $ID;

echo "
<div class='page' id='page'>

	<div id='head'>
		pic2base :: Datensatz-Bearbeitung (&Auml;nderungen speichern)
	</div>
	
	<div class='navi' id='navi'>
		<div class='menucontainer'>
		</div>
	</div>
	
	<div class='content' id='content'>
		<span style='font-size:12px;'>
		<p style='margin:120px 0px; text-align:center'>
		
		<center>
			<p id = 'headline'>Status der Beschreibungs-Zuweisung</p>
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
	
	<div id='foot'>
		<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
	</div>
</div>";
mysql_close($conn);
?>
</DIV>
</CENTER>
</BODY>

<script type = text/javascript>
var timeout = 1;
var gesamtanzahl;
var starttime = new Date();
var avgTime;

function picDescList( params )
{
	if(params.pic_anzahl > 0)
	{
		gesamtanzahl = params.pic_array.length;
//		alert("Gesamtzahl: " + gesamtanzahl);
		document.getElementById("zaehler").innerHTML = "Anzahl der zu verarbeitenden Bilder: " + gesamtanzahl;
		processFile( params.pic_array,params.description );
	}
	else
	{
		alert( "Es sind keine Dateien zu bearbeiten.\nBitte legen Sie Bilder und den Beschreibungstext fest, der diesen zugeordnet werden soll." );
		window.location='edit_beschreibung.php?kat_id=' + <?php echo $kat_id;?> + '&pic_id=0';
	}	
}

function showReady( avgTime,mod )
{
	//Erfassungsfenster leeren:
	document.getElementById("headline").innerHTML = "";
	document.getElementById("zaehler").innerHTML = "";
	document.getElementById("meldung").innerHTML = "";
	document.getElementById( "prog_bar" ).style.border = "none";
	document.bar.width = '0';
	document.bar.height = '0';
	//Fertigmeldung ausgeben:
	alert( "Die Bearbeitung ist abgschlossen.\nDie durchschnittliche Bearbeitungszeit pro Bild betrug " + avgTime + " Sekunden.");
	window.location='edit_beschreibung.php?kat_id=' + <?php echo $kat_id;?> + '&pic_id=0';
}

function processFile( pic_array, description )
{
	//Bild fuer Bild wird der neue Beschreibungstext zugewiesen und das Bild-Array nach jeder Bearbeitung gekuerzt
	//nach jedem bearbeiteten Bild wird der Fortschrittsbalken aktualisiert
	
	var client = new XMLHttpRequest();
	client.open("GET", "update_desc_daten.php?pic_id=" + pic_array[0] + "&description=" + description, true);
	pic_array.splice( 0, 1 );
	client.onreadystatechange = function()
	{
		if( client.readyState == 4 )
		{
//			alert(client.responseText);
			var result = JSON.parse( client.responseText );
			if( result.errorCode == 1 )
			{
				alert( "Es trat ein Fehler auf!" );
			}
			else if( result.errorCode == 2 )
			{
				alert("Sie haben entweder kein Bild oder keinen Beschreibungstext festgelegt!");
			}
				
			if( pic_array.length > 0 )
			{
				if( pic_array.length > 1)
				{
					document.getElementById("meldung").innerHTML = "...es verbleiben noch " + pic_array.length + " Bilder...";
				}
				else
				{
					document.getElementById("meldung").innerHTML = "...es verbleibt noch " + pic_array.length + " Bild...";
				}
				var laenge = (gesamtanzahl - pic_array.length) / gesamtanzahl * 500;
				document.bar.src = '../../share/images/green.gif';
				document.bar.width = laenge;
				document.bar.height = '11';
				processFile( pic_array, description );
			}
			else
			{
				//Bearbeitung ist abgeschlossen
				//Berechnung der durchschnittlichen Bearbeitungszeit pro Bild:
				var endtime = new Date();
				var runtime = (endtime.getTime() - starttime.getTime()) / 1000;
				avgTime = Math.round((runtime / gesamtanzahl) * 100) / 100;
				document.getElementById("meldung").innerHTML = "Alle Bilder wurden aktualisiert.";
				//Aktualisierung des Fortschrittsbalkens auf 100%:
				var laenge = (gesamtanzahl - pic_array.length) / gesamtanzahl * 500;
				document.bar.src = '../../share/images/green.gif';
				document.bar.width = laenge;
				document.bar.height = '11';
				setTimeout("showReady(avgTime)", 1000);
				//showReady(avgTime,mod)
			}
		}
	};
	client.send( null );
}
</script>
</HTML>