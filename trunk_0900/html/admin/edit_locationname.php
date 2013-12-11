<?php
IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../../index.php');
}
else
{
	$uid = $_COOKIE['uid'];
}

if(array_key_exists('city',$_GET))
{
	$city = $_GET['city']; 
}

if(array_key_exists('id',$_GET))
{
	$id = $_GET['id']; 
}
else
{
	break;
}
//###################################################################################################
//																									#
//Datei wird im Admin-Bereich zur nachtraeglichen Aenderung von Ortsnamen verwendet                 #
//																									#
//###################################################################################################

include '../../share/global_config.php';
include $sr.'/bin/share/functions/ajax_functions.php';

echo "
  <center>
  	
	  	<table class='normal' border='0'>
			<tr>
				<td colspan='4' style='font-size:12pt; text-align:center;'>Ortsnamen bearbeiten</td>
			</tr>
			
			<tr style='height:3px;'>
				<td class='normal' align='center' bgcolor='darkred' colspan='4'></TD>
			</TR>
			
			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>
			
		  	<TR>
				<TD align='CENTER' colspan='4' style='padding-left:20px;'>
				<INPUT TYPE='text' name='locationname_new' id='locationname_new' VALUE=\"$city\" style='width:400px;'></INPUT>
				<INPUT TYPE='hidden' name='locationname' VALUE=\"$city\" style='width:400px;'></INPUT>
				</TD>
			</TR>
			
			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>
			
			<TR>
				<TD align='CENTER' colspan='4' style='padding-left:20px;'>
				<INPUT TYPE='button' id='b1' VALUE='Neue Ortsbezeichnung speichern' onClick='getAffectedRecords(\"$city\", document.getElementById(\"locationname_new\").value)'>
				<INPUT TYPE='button' id='b2' VALUE='Abbrechen' onClick='javascript:history.back()'>	
				</TD>
			</TR>
			
			<tr>
			
				<td colspan='4' align='center'><p id='zaehler'></p></td>
			</tr>
			
			<tr>
				<td colspan='4' align='center'>
				<div id='prog_bar' style='border:solid; border-color:red; width:500px; height:12px; margin-top:30px; margin-bottom:30px; text-align:left; vertical-align:middle'>
					<img src='../../share/images/green.gif' name='bar' />
				</div>
				</td>
			</tr>
			
			<tr>
				<td colspan='4' align='center'><p id='meldung'></p></td>
			</tr>
			
			<tr>
				<td colspan='4' align='center'><p id='counter'></p></td>
			</tr>
			
			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>
			
			<tr style='height:3px;'>
				<td class='normal' align='center' bgcolor='darkred' colspan='4'></TD>
			</TR>
		  
		  	<TR>
				<TD align='center' colspan = '4'></TD>
			</TR>
		</table>
	
</center>
</body>";
?>

<script type = text/javascript>
var timeout = 1;
var fileList = null;
var gesamtanzahl;
var starttime = new Date();
var avgTime;
//var oldCityname = "<?php echo $city; ?>";

function recordListReceived( responseText )
{
	//alert("An edit_locationname.php zurueckgeliefertes Objekt: " + responseText);
	recordList = JSON.parse( responseText, null );
	//alert(recordList.record_array[0] + recordList.newCityname);
	if(recordList.anzahl > 0)
	{
		gesamtanzahl = recordList.record_array.length;
		document.getElementById("zaehler").innerHTML = "Anzahl der zu ändernden Ortsbezeichnungen: " + gesamtanzahl;
		document.getElementById("b1").disabled = "true";
		document.getElementById("b2").disabled = "true";
		document.getElementById("locationname_new").disabled = "true";
		processFile( recordList.oldCityname, recordList.newCityname, recordList);
	}
	else
	{
		alert( "Es sind keine Ortsbezeichnungen zu &auml;ndern." );
		window.location="../start.php";
	}	
}

function showReady(avgTime)
{
	//Erfassungsfenster leeren:
	document.getElementById("zaehler").innerHTML = "";
	document.getElementById("meldung").innerHTML = "";
	document.getElementById( "prog_bar" ).style.border = "none";
	document.bar.width = '0';
	document.bar.height = '0';
	//Fertigmeldung ausgeben:
	//alert( "Die Bearbeitung ist abgschlossen.\nDie durchschnittliche Bearbeitungszeit pro Datensatz (Bild betrug " + avgTime + " Sekunden.");
	countDown();
}

function processFile( oldCityname, newCityname, recordList)
{
	var client = new XMLHttpRequest();
	client.open("GET", "edit_locationname_action.php?oldCityname=" + oldCityname + "&newCityname=" + newCityname + "&record=" + recordList.record_array[0], true);
	//alert( "Es wird Datensatz " + recordList.record_array[0] + " bearbeitet. Alter Name: " + oldCityname + ", neuer Name: " + newCityname);
	recordList.record_array.splice( 0, 1 );
	
	client.onreadystatechange = function()
	{
		if( client.readyState == 4 )
		{
			
			var result = JSON.parse( client.responseText );
			//alert("Fehler-Code: " + result.errorCode);
							
			if( result.errorCode != 0 )
			{
				//alert( "Es trat ein Fehler auf!" );
				if( result.errorCode == 2 && recordList.record_array.length < 1)
				{
					alert ("Die neue Ortsbezeichnung entspricht der alten Ortsbezeichnung. Der Vorgang wird abgebrochen.");
				}
			}
					
			if( recordList.record_array.length > 0 )
			{
				if( recordList.record_array.length > 1)
				{
					document.getElementById("meldung").innerHTML = "...es verbleiben noch " + recordList.record_array.length + " Bilder...";
				}
				else
				{
					document.getElementById("meldung").innerHTML = "...es verbleibt noch " + recordList.record_array.length + " Bild...";
				}
				var laenge = (gesamtanzahl - recordList.record_array.length) / gesamtanzahl * 500;
				document.bar.src = '../../share/images/green.gif';
				document.bar.width = laenge;
				document.bar.height = '11';
				processFile( oldCityname, newCityname, recordList );
			}
			else
			{
				//Bearbeitung ist abgeschlossen
				//Berechnung der durchschnittlichen Bearbeitungszeit pro Bild:
				var endtime = new Date();
				var runtime = (endtime.getTime() - starttime.getTime()) / 1000;
				avgTime = Math.round((runtime / gesamtanzahl) * 100) / 100;
				document.getElementById("meldung").innerHTML = "Alle Bilder wurden bearbeitet.";
				//Aktualisierung des Fortschrittsbalkens auf 100%:
				var laenge = (gesamtanzahl - recordList.record_array.length) / gesamtanzahl * 500;
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
		window.location='adminframe.php';
	}
	else
	{
		if( timeout > 0 )
		{
			document.getElementById( "counter" ).innerHTML = "Sie werden in " + timeout.toString() + " Sekunden zum Admin-Bereich weitergeleitet.";
		}
		else if(timeout == 0)
		{
			document.getElementById( "counter" ).innerHTML = "angekommen...";
		}
		setTimeout( "countDown()", 1000 );	
	}
	timeout --;
}
</script>