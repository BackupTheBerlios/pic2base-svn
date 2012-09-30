<?php
IF (!$_COOKIE['login'])
{
	include '../../share/global_config.php';
  	header('Location: ../../../index.php');
}
ini_set('memory_limit','300M');
$pic_ID = array();
$kat_ID = array();
$obj = new stdClass();
FOREACH ($_POST AS $key => $post)
{
	//echo "Schluessel / Wert: ".$key." / ".$post."<BR>";
	IF (substr($key,0,3) == 'pic')
	{
		//echo substr($key,7,strlen($key)-7)."<BR>";
		$pic_ID[] = substr($key,7,strlen($key)-7);
	}
	
	IF (substr($key,0,3) == 'kat')
	{
		//echo substr($key,3,strlen($key)-3)."<BR>";
		$kat_ID[] = substr($key,3,strlen($key)-3);
	}
}
if(array_key_exists('mod',$_GET))
{
	$mod = $_GET['mod'];
	//echo "mod: ".$mod."<BR>"; 
}
$obj->mod = $mod;
$obj->pic_anzahl = count($pic_ID);
$obj->pic_array = $pic_ID;
$obj->kat_array = $kat_ID;
$output = json_encode($obj);
//echo "<font color='white'>".$output." - ".ini_get('memory_limit')."</font>";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Kategorie-Zuweisung</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll = "auto" onLoad='picKatList(<?php echo json_encode($obj); ?>)'>
<!-- <BODY>-->

<CENTER>

<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: edit_kat_daten_action2.php
 * Version mit AJAX-basierter Fortschrittsanzeige (verwendet ab V. 0.60.4)
 *
 * Copyright (c) 2005 - 2012 Klaus Henneberg
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

if(array_key_exists('kat_id',$_GET))
{
	$kat_id = $_GET['kat_id'];
	//echo "kat_id: ".$kat_id."<BR>"; 
}
else
{
	$kat_id = 0;
}
if(array_key_exists('ID',$_GET))
{
	$ID = $_GET['ID'];
	//echo "ID: ".$ID."<BR>"; 
}
else
{
	$ID = 0;
}
if(array_key_exists('mod',$_GET))
{
	$mod = $_GET['mod'];
	//echo "mod: ".$mod."<BR>"; 
}
else
{
	$mod = 0;
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/ajax_functions.php';
$result1 = mysql_query( "SELECT id FROM $table1 WHERE username = '$c_username' AND aktiv = '1'"); echo mysql_error();
$user_id = mysql_result($result1, isset($i), 'id');

echo "
<div class='page'>
	<p id='kopf'>pic2base :: Kategorie-Zuweisung - &Auml;nderungen speichern<span class='klein'>(User: ".$c_username.")</span></p>
		
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>
		</div>
	</div>
		
	<div class='content'>
		<span style='font-size:12px;'>
		<p style='margin:120px 0px; text-align:center'>
		
		<center>
			<p id = 'headline'>Status der Kategorie-Zuweisung</p>
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
var timeout = 1;
var gesamtanzahl;
var starttime = new Date();
var avgTime;
var mod = "<?php echo $mod;?>";

function picKatList( params )
{
	if(params.pic_anzahl > 0)
	{
		gesamtanzahl = params.pic_array.length;
		document.getElementById("zaehler").innerHTML = "Anzahl der zu verarbeitenden Bilder: " + gesamtanzahl;
		processFile( params.pic_array,params.kat_array,params.mod );
	}
	else
	{
		alert( "Es sind keine Dateien zu bearbeiten.\nBitte legen Sie Bilder und Kategorien fest, die diesen zugeordnet werden sollen." );
		window.location='edit_kat_daten.php?kat_id=' + <?php echo $kat_id;?> + '&mod=' + params.mod + '&pic_id=0';
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
	window.location='edit_kat_daten.php?kat_id=' + <?php echo $kat_id;?> + '&mod=' + mod + '&pic_id=0';
}

function processFile( pic_array, kat_array, mod )
{
	//Bild fuer Bild werden die neuen Kategorien zugewiesen und das Bild-Array nach jeder Bearbeitung gekuerzt
	//nach jedem bearbeiteten Bild wird der Fortschrittsbalken aktualisiert
	
	var client = new XMLHttpRequest();
	client.open("GET", "update_kat_daten.php?pic_id=" + pic_array[0] + "&kat_liste=" + kat_array, true);
	pic_array.splice( 0, 1 );
	client.onreadystatechange = function()
	{
		if( client.readyState == 4 )
		{
			//alert(client.responseText);
			var result = JSON.parse( client.responseText );
			if( result.errorCode == 1 )
			{
				alert( "Es trat ein Fehler auf!" );
			}
			else if( result.errorCode == 2 )
			{
				alert("Sie haben entweder kein Bild oder keine Kategorie festgelegt!");
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
				processFile( pic_array, kat_array, mod );
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
				setTimeout("showReady(avgTime,mod)", 1000);
				//showReady(avgTime,mod)
			}
		}
	};
	client.send( null );
}

</script>
</HTML>