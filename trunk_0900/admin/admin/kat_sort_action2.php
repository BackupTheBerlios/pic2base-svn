<?php 
IF ($_COOKIE['uid'])
{
	$uid = $_COOKIE['uid'];
}

@$kat_source = $_POST['kat_source'];		// diese Kategorie soll samt aller Unterkategorien umgehaengt werden
@$kat_dest = $_POST['kat_dest'];			// unter diese Kategorie soll der Zweig wieder eingehaengt werden

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/permissions.php';
include $sr.'/bin/share/functions/main_functions.php';

		
// ~~~~~~  Skript zur Neustrukturierung des Kategoriebaumes, wenn eine Kategorie umgehaengt werden soll  ~~~~~~~~~~~~~~~~~~~~~~~
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~  Was passiert?  ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// Tabelle 4 (Kategorien) betreffend:
// 1) Ermittlung, wo die Kategorie bisher hang und wohin sie umgehaengt werden soll
// 2) aus der Tabelle 4 (kategorien) sind die zu loeschenden Kategoriezuordnungen vom old_parent bis 
// einschliesslich der Wurzelkategorie (1) zu bestimmen und in das Array $kat_del[] zu schreiben
// 3) aus der Tabelle 4 (kategorien) sind die neu zuzuordnenden Kategorien vom kat_dest bis 
// einschliesslich der Wurzelkategorie (1) zu bestimmen und in das Array $kat_add[] zu schreiben
// 4) der umzuhaengenden Kategorie ist das neue Parent-Element zuzuordnen, sein neuer level ist zu bestimmen und allen
// Elementen im umzuhaengenden Zweig sind die daraus folgenden neuen Level zuzuordnen (Aktualisierung der Tabelle 4: kategorien)
//
// Tabelle 10 (pic_kat) betreffend
// 5) aus der Tabelle 10 (pic_kat) sind fuer alle betreffenden Bilder die Kategoriezuordnungen vom old_parent
// bis einschliesslich der Wurzelkategorie (1) zu entfernen 
// 6) Die Tabelle 10 (pic_kat) ist fuer alle betreffenden Bilder mit den neuen Kategoriezuordnungen jeweils von der kat_dest
// bis zur Wurzel (1) zu aktualisieren
//
// Tabelle 2 (pictures) betreffend
// 7) Die Tabelle 2 (pictures), Feld Keywords fuer jedes Bild ist entsprechend der aktuellen Kategoriezuweisung zu aktualisieren
// 8) Bei jedem Bild ist das Tag IPTC:keywords entsprechend der aktuellen Kategoriezuweisung zu aktualisieren
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

// 1)

$exiftool = buildExiftoolCommand($sr);

IF($kat_source !== $kat_dest AND $kat_source !== '' AND $kat_source !== NULL AND $kat_dest !== '' AND $kat_dest !== NULL)
{
	$result1 = mysql_query("SELECT kategorie, parent, level FROM $table4 WHERE kat_id = '$kat_source'");
	$source_name = mysql_result($result1, isset($i1), 'kategorie');
	$old_parent = mysql_result($result1, isset($i1), 'parent'); //unterhalb dieser Kategorie war die umzuhaengende Kat. bisher eingehaengt
	$old_level = mysql_result($result1, isset($i1), 'level');

// 2)
	$kat_id1 = $old_parent;
	$kat_del = array();		//Array leeren
	$kat_del[] = $kat_id1;	//Array mit dem Startwert fuellen
	WHILE ($kat_id1 > '1')
	{
		$result3 = mysql_query( "SELECT parent FROM $table4 WHERE kat_id='$kat_id1'");
		echo mysql_error();
		$row = mysql_fetch_array($result3);
		$kat_id1 = $row['parent'];
		$kat_del[]=$kat_id1;
	}
	
// 3)
	$kat_id2 = $kat_dest;
	$kat_add = array();		//Array leeren
	$kat_add[] = $kat_id2;	//Array mit dem Startwert fuellen
	WHILE ($kat_id2 > '1')
	{
		$result4 = mysql_query( "SELECT parent FROM $table4 WHERE kat_id='$kat_id2'");
		echo mysql_error();
		$row = mysql_fetch_array($result4);
		$kat_id2 = $row['parent'];
		$kat_add[]=$kat_id2;
	}
	
// 4)
	//Wenn ein Kategoriezweig umgehaengt wird, wird dem Wurzelelement des Zweiges das neue Parent-Element 
	//zugewiesen und bei allen Elementen dez Zweiges muss der level aktualisiert werden.
	$result2 = mysql_query( "SELECT * FROM $table4 WHERE kat_id = \"$kat_dest\"");
	$dest_name = mysql_result($result2, isset($i2), 'kategorie');
	$dest_level = mysql_result($result2, isset($i2), 'level');
	$new_level = $dest_level + 1;	//neuer Level der umzuhaengenden Kategorie
	
	//Ermittlung aller Unterkategorien unter der umzuhaengenden Kategorie:
	//zur Sicherheit Bereinigung der tmp_tree-Tabelle:
	$result3 = mysql_query( "DELETE FROM $table15 WHERE user_id = '$uid'");
	//Parameter der umzuhaengenden Kat. werden in die Tabelle tmp_tree geschrieben:
	$result4 = mysql_query( "INSERT INTO $table15 (kat_id, old_level, kat_name, user_id, new_level, new_parent) VALUES (\"$kat_source\", \"$old_level\", \"$source_name\", '$uid', \"$new_level\", \"$kat_dest\")");
	echo mysql_error();
	
	$delta_level = $new_level - $old_level;		
	$kat_id = $kat_source;
	$k = -1;
	function getTree($kat_id,$k) 
	{
	    include '../../share/global_config.php';
		include $sr.'/bin/share/db_connect1.php';
		$result5 = mysql_query("SELECT * FROM $table4 WHERE parent='$kat_id'");
	    echo mysql_error();
	    $sub_kats = "";
	    $kat_arr = array();
	    global $kat_arr;
	    
	    while($einzeln = @mysql_fetch_assoc($result5)) 
	    { 
	    	if(hasChildKats($einzeln['kat_id'])) 
		    {
		      	$k++; 
		        $kat_arr[$k][0] = $einzeln['kat_id'];
		        $kat_arr[$k][1] = $einzeln['level'];
		        $sub_kats = getTree($einzeln['kat_id'],$k);
		    } 
		    else 
		    {
		        $k++; 
		        $kat_arr[$k][0] = $einzeln['kat_id'];
		        $kat_arr[$k][1] = $einzeln['level'];
		    }
	    }
	    return $kat_arr;
  	}
 
	function hasChildKats($katID) 
	{
	    include '../../share/global_config.php';
		include $sr.'/bin/share/db_connect1.php';
	  	$result6 = mysql_query("SELECT kat_id FROM $table4 WHERE parent='$katID'");
	    if(mysql_num_rows($result6)>0) return true; else return false;
	}
		
	$kategorien = getTree($kat_id,$k);  
	$kat_arr_anz = count($kategorien);	//Anzahl der Arrays (Kategorie / Level) im Ergebnis-Array
	FOR($m='0'; $m<$kat_arr_anz; $m++)
	{
			$element = $kategorien[$m];
			$Kat = $element[0];
			$Level = $element[1];
			$korr_level = $Level + $delta_level;
			$result7 = mysql_query("UPDATE $table4 SET level = '$korr_level' WHERE kat_id = '$Kat'");
			echo mysql_error();
	}
	// parent und level fuer die umzuhaengende Kategorie wird in der Tabelle 4 (kategorien) geaendert:
	// (Uebertragung der Daten aus der tmp-Tabelle in die Kategorie-Tabelle)
	$result8 = mysql_query( "SELECT * FROM $table15 WHERE user_id = '$uid'");
	$num8 = mysql_num_rows($result8);
	FOR($i8='0'; $i8<$num8; $i8++)
	{
		$kat_id = mysql_result($result8, $i8, 'kat_id');
		$new_level = mysql_result($result8, $i8, 'new_level');
		$new_parent = mysql_result($result8, $i8, 'new_parent');
		IF($new_parent !== '0')
		{
			$result9 = mysql_query( "UPDATE $table4 SET level = '$new_level', parent = '$new_parent' WHERE kat_id = '$kat_id'");
		}
		ELSEIF($new_parent == '0')
		{
			$result9 = mysql_query( "UPDATE $table4 SET level = '$new_level' WHERE kat_id = '$kat_id'");
		}
	}

// 5)
	// Bestimmung aller von der Umstrukturierung betroffenen Bilder:
	$pic_arr = array();
	$result10 = mysql_query("SELECT * FROM $table10 WHERE kat_id = '$kat_source'");
	$num10 = mysql_num_rows($result10);
	IF($num10 > 0)
	{
//		echo "Es sind ".$num10." Bilder betroffen.<BR>";
		FOR($i10=0; $i10<$num10; $i10++)
		{
			$bildnr = mysql_result($result10, $i10, 'pic_id');
			$pic_arr[] = $bildnr;	// neu
			FOREACH($kat_del AS $kd)
			{
				$result11 = mysql_query("DELETE FROM $table10 WHERE pic_id = '$bildnr' AND kat_id = '$kd'");
				if(mysql_error() !== '')
				{
					echo "Fehler beim Kategorie-L&ouml;schen: ".mysql_error()."<BR>";
				}
			}
			
// 6)
			FOREACH($kat_add AS $ka)
			{
				$result12 = mysql_query("INSERT INTO $table10 (pic_id, kat_id) VALUES ('$bildnr', '$ka')");  //Tabelle pic_kat aktualisieren
				if(mysql_error() !== '')
				{
					echo "Fehler bei der Kategorie-Neuzuweisung: ".mysql_error()."<BR>";
				}
			}
		}
// 7)
// Dieser Teil wird in externem Skript erledigt
	}
}

$obj = new stdClass();
$obj->pic_anzahl = count($pic_arr);
$obj->pic_array = $pic_arr;
$output=json_encode($obj);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Kategorie-Sortierung</TITLE>
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

<BODY LANG="de-DE" onLoad='doKatSort(<?php echo json_encode($obj); ?>)'>

<CENTER>

<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: kat_sort_action2.php
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
 * Skript wird bei der Umsortierung des Kategoriebaumes mit Anzeige des Fortschrittsbalkens verwendet (ab Juni 2013)
 */

echo "
	<div id='page'>
	
		<div id='head'>
			pic2base :: Kategorie-Umsortierung
		</div>
		
		<div id='navi'>
			<div class='menucontainer'>
			.
			</div>
		</div>
		
		<div id='content'>
		
			<span style='font-size:12px;'>
				<p style='margin:120px 0px; text-align:center'>
				
				<center>
					<p id = 'headline'>Status der Kategorie-Umsortierung</p>
					<p id='zaehler'></p>
					<div id='prog_bar' style='border:solid; border-color:red; width:500px; height:12px; margin-top:30px; margin-bottom:30px; text-align:left; vertical-align:middle'>
						<img src='../../share/images/green.gif' name='bar' />
					</div>
					<p id='meldung'>Bitte haben Sie ein wenig Geduld...</p>
					<p id='counter'></p>
				</center>
				
				</p>
			</span>

			<div id='blend' style='display:block; z-index:99;'>
				<!--<p style='color:blue; margin-top:100px; text-align:center; z-index:102;' />Die &Auml;nderungen werden ausgef&uuml;hrt, bitte warten Sie...<BR><br>Sie werden nach Beendigung der Sortierung wieder automatisch zur vorhergehenden Seite geleitet.</p>-->
			</div>";
			
			IF(hasPermission($uid, 'editkattree', $sr))
			{
				$navigation = "<a class='navi' href='kategorie0.php?kat_id=0'>Zur&uuml;ck</a>";
			}

			mysql_close($conn);
		echo "
		</div>
		
		<div id='foot'>
			<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
		</div>
	
	</div>";
?>
</DIV>
</CENTER>
</BODY>

<script type = text/javascript>

var timeout = 1;
var gesamtanzahl;
var starttime = new Date();
var avgTime;

function doKatSort( params )
{
	if( params.pic_anzahl > 0 )
	{
		//alert(params.pic_array.length);
		gesamtanzahl = params.pic_array.length;
		document.getElementById("zaehler").innerHTML = "Anzahl der zu verarbeitenden Bilder: " + gesamtanzahl;
		processFile( params.pic_array );
	}
	else
	{
		alert( "Es sind keine Aufgaben zu bearbeiten.\nBitte legen Sie die Quell- und Ziel-Kategorien fest, die neu zugeordnet werden sollen." );
		window.location='kat_ausw1.php';
	}	
}

function showReady( avgTime )
{
	//Erfassungsfenster leeren:
	document.getElementById("headline").innerHTML = "";
	document.getElementById("zaehler").innerHTML = "";
	document.getElementById("meldung").innerHTML = "";
	document.getElementById( "prog_bar" ).style.border = "none";
	document.bar.width = '0';
	document.bar.height = '0';
	//Fertigmeldung ausgeben bzw. Ruecksprung zur Auswahlseite:
//	alert( "Die Bearbeitung ist abgschlossen.\nDie durchschnittliche Bearbeitungszeit pro Bild betrug " + avgTime + " Sekunden.");
	window.location='kat_ausw1.php';
}

function processFile( pic_array )
{
	//nach jedem bearbeiteten Bild wird der Fortschrittsbalken aktualisiert
	var client = new XMLHttpRequest();
	client.open("GET", "update_pic_kategory.php?pic_id=" + pic_array[0], true);
	pic_array.splice( 0, 1 );
	client.onreadystatechange = function()
	{
		if( client.readyState == 4 )
		{
			//alert(client.responseText);
			var result = JSON.parse( client.responseText );
			if( result.errorCode == 1 )
			{
				alert( "Es trat ein allgemeiner Fehler auf!" );
			}
			else if( result.errorCode == 2 )
			{
				alert("Sie haben entweder keine Quell- oder keine Ziel-Kategorie festgelegt!");
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
				processFile( pic_array );
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
				showReady(avgTime)
			}
		}
		else
		{
//			alert(client.readyState);
		}
	};
	client.send( null );
}

</script>

</HTML>