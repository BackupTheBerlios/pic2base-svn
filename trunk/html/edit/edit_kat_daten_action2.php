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
ini_set('memory_limit','300M');
$pic_ID = array();
$kat_ID = array();
$obj = new stdClass();
FOREACH ($_POST AS $key => $post)
{
	IF (substr($key,0,3) == 'pic')
	{
		$pic_ID[] = substr($key,7,strlen($key)-7);
	}
	
	IF (substr($key,0,3) == 'kat')
	{
		$kat_ID[] = substr($key,3,strlen($key)-3);
	}
}
if(array_key_exists('mod',$_GET))
{
	$mod = $_GET['mod'];
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
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Kategorie-Zuweisung</TITLE>
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

<BODY LANG="de-DE" onLoad='picKatList(<?php echo json_encode($obj); ?>)'>
<CENTER>
<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: edit_kat_daten_action2.php
 * Version mit AJAX-basierter Fortschrittsanzeige (verwendet ab V. 0.60.4)
 *
 * Copyright (c) 2005 - 2013 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 */

//$source_kat_id: die Kategorie, deren Bilder bearbeitet werden sollen
if(array_key_exists('source_kat_id',$_POST))
{
	$source_kat_id = $_POST['source_kat_id'];
}
else
{
	$source_kat_id = 0;
}
if(array_key_exists('ID',$_GET))
{
	$ID = $_GET['ID'];
}
else
{
	$ID = 0;
}
if(array_key_exists('mod',$_GET))
{
	$mod = $_GET['mod'];
}
else
{
	$mod = 0;
}

if(array_key_exists('show_mod',$_POST))
{
	$show_mod = $_POST['show_mod'];
}
else
{
	$show_mod = 'J';
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/ajax_functions.php';

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');

echo "
<div class='page' id='page'>

	<div class='head' id='head'>
		pic2base :: Kategorie-Zuweisung - &Auml;nderungen speichern <span class='klein'>(User: ".$username.")</span>
	</div>
	
	<div class='navi' id='navi'>
		<div class='menucontainer'>
		</div>
	</div>
		
	<div class='content' id='content'>
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
	
	<div class='foot' id='foot'>
		<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
	</div>
	
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
var show_mod = "<?php echo $show_mod;?>";

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
		window.location='edit_kat_daten.php?kat_id=' + <?php echo $source_kat_id;?> + '&mod=' + params.mod + '&pic_id=0&show_mod=' + show_mod;
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
	window.location='edit_kat_daten.php?kat_id=' + <?php echo $source_kat_id;?> + '&mod=' + mod + '&pic_id=0&show_mod=' + show_mod;
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