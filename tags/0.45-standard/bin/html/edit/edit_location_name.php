<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Geo-Referenzierung (Ortsnamen)</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
	<!--<script type="text/javascript" src="../../ajax/inc/vorschau.js"></script>-->
	<script language="JavaScript" type="text/javascript">
	function chkOrt()
	{
		if(document.ortsbezeichnung.ort.value == "")
		{
			alert("Bitte legen Sie im folgenden Schritt eine Ortsbezeichnung fest!");
			document.ortsbezeichnung.ort.focus();
			document.location.href="edit_location_name.php?stat=new";
			return false;
		}
	}
  	</script>
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?

/*
 * Project: pic2base
 * File: edit_location_name.php
 *
 * Copyright (c) 2003 - 2008 Klaus Henneberg
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
//echo $c_username;
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/geo_functions.php';
include $sr.'/bin/share/functions/main_functions.php';
//echo $geo_path_copy;

$result1 = mysql_query( "SELECT * FROM $table1 WHERE username = '$c_username' AND aktiv = '1'");
$row = mysql_fetch_array($result1);
$berechtigung = $row['berechtigung'];
$user_id = $row['id'];
//echo "User-ID: ".$user_id."<BR>";
$num2 = '0';
$loc_id = '';
IF(!isset($stat))
{
	$stat = '';
}
IF(!isset($i2))
{
	$i2 = 0;
}

echo "
<div class='page'>
	<p id='kopf'>pic2base :: Datensatz-Bearbeitung (Benennung der Ortsnamen)</p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
			createNavi3_1($c_username);
		echo "</div>
	</div>
	
	<div id='spalte1F'>
		<p id='elf' style='background-color:white; padding: 5px; margin-top: 4px; margin-left: 0px; text-align:center;'>Ortsbezeichnung<BR></p>";
		$result2 = mysql_query( "SELECT $table2.Owner, $table2.loc_id, $table2.FileNameV, $table12.loc_id, $table12.location FROM $table12 LEFT JOIN $table2 ON ($table2.loc_id = $table12.loc_id) WHERE ($table2.Owner = '$user_id' AND $table12.location = 'Ortsbezeichnung')");
		echo mysql_error();
		$num2 = mysql_num_rows($result2);
		//echo "Trefferzahl: ".$num2."<BR>";
		
		//echo "Dateiname: ".$FileNameV.", Location-ID: ".$loc_id.", Owner: ".$c_username.", Ort: ".$ort."<br>";
		echo "
		<FORM name = 'ortsbezeichnung' method='post' action='edit_location_name_action.php' onSubmit='return chkOrt()'> 
		<TABLE id='kat'>
			<TR id='kat'>
				<TD id='kat1'>";
				SWITCH($num2)
				{
					//Erzeugung des Hinweistextes �ber dem Vorschaubild:
					CASE '0':
					echo "Es gibt kein Bild mehr ohne GPS-Ortszuweisung.<BR>
					<meta http-equiv='refresh', content='0; URL=edit_start.php'>";
					break;
					
					CASE '1':
					$FileNameV = mysql_result($result2,$i2,$table2.'.FileNameV');
					$loc_id = mysql_result($result2,$i2,$table12.'.loc_id');
					$ort = mysql_result($result2,$i2,$table12.'.location');
					echo "Es gibt noch dieses Bild ohne GPS-Ortszuweisung.";
					break;
					
					default:
					$FileNameV = mysql_result($result2,$i2,$table2.'.FileNameV');
					$loc_id = mysql_result($result2,$i2,$table12.'.loc_id');
					$ort = mysql_result($result2,$i2,$table12.'.location');
					echo "Es gibt noch ".$num2." Bilder ohne GPS-Ortszuweisung.";
					break;
				}
				//echo "Loc_ID: ".$loc_id."<BR>File: ".$FileNameV."<BR>Ort: ".$ort."<BR>";
				IF ($num2 > '0')
				{
					$bildinfo = getimagesize($vorschau_verzeichnis."/".$FileNameV);
					$breite = $bildinfo[0];
					$hoehe = $bildinfo[1];
					IF($breite > $hoehe)
					{
						$imgsrc = "<img src=\"$vorschau_verzeichnis/$FileNameV\" width= \"300\" />";
					}
					ELSE
					{
						$imgsrc = "<img src=\"$vorschau_verzeichnis/$FileNameV\" height= \"270\" />";
					}
					
					//Bestimmung der Geo-Koordinaten am Aufnahmeort:
					$result3 = mysql_query( "SELECT * FROM $table12 WHERE loc_id = '$loc_id'");
					@$long = mysql_result($result3, $i3, 'longitude');
					@$lat = mysql_result($result3, $i3, 'latitude');
					//echo "Long: ".$long.", Lat: ".$lat."<BR>";
					//Radius: 5 km, um welchen die vorhandenen Orte ermittelt werden:
					$radius = 5000;
					$diff_lat = 0.000008999280058; //(Winkelaenderung je Meter)
					$delta_lat = $radius * $diff_lat;
					$lat_min = $lat - $delta_lat;
					$lat_max = $lat + $delta_lat;
					//echo "Breite: ".$lat.", min. Breite: ".$lat_min.", max. Breite: ".$lat_max."<BR>";
					
					//geogr. Laenge: hier ist die Winkelaenderung / Entfernung von der geogr. Breite abhaengig:
					$delta_long = getDeltaLong($lat, $radius);
					$long_min = $long - $delta_long;
					$long_max = $long + $delta_long;
					//echo "L&auml;nge: ".$long.", min. L&auml;nge: ".$long_min.", max. L&auml;nge: ".$long_max."<BR>";
					
					//qudratischer Auswahlbereich:
					$result5 = mysql_query( "SELECT * FROM $table12 WHERE (longitude > $long_min AND longitude < $long_max) AND (latitude > $lat_min AND latitude < $lat_max)");
					echo mysql_error();
					$num5 = mysql_num_rows($result5);
					//echo $num5." Orte in der Umgebung wurden gefunden.<BR>";
					//von allen Treffern werden die Ortsbezeichnungen in aufsteigender Entfernung vom Aufnahmeort ermittelt:
					unset($locid);
					unset($ort_arr);
					unset($abstand);
					$locid = array();
					$ort_arr = array();
					$abstand = array();
				
					$it = 0;
					FOR($i5='0'; $i5<$num5; $i5++)
					{
						$location_id = mysql_result($result5, $i5, 'loc_id');
						$location = mysql_result($result5, $i5, 'location');
						$longitude = mysql_result($result5, $i5, 'longitude');
						$latitude = mysql_result($result5, $i5, 'latitude');
						$delta = sqrt(pow(($long - $longitude),2) + pow(($lat - $latitude),2));
						//echo $location_id.", ".$location.", ".$delta."<BR>";
						IF (!in_array($location,$ort_arr) AND ($location !== 'Ortsbezeichnung'))
						{
							$locid[$it] = $location_id;
							$ort_arr[$it] = $location;
							$abstand[$it] = $delta;
							$it++;
							//echo $location." wird im Array aufgenommen<BR>";
						}
						ELSEIF($location !== 'Ortsbezeichnung')
						{
							$position = array_search($location,$ort_arr);
							//echo $ort[$position].": bisheriger Abstand: ".$abstand[$position].", neuer Abstand: ".$delta."<BR>";
							IF($abstand[$position] > $delta)
							{
								$locid[$position] = $location_id;
								$abstand[$position] = $delta;
								//echo "neuer Abstand wurde uebernommen<BR>";
							}
						}
					}
					$elements = count($locid);
					//echo "Es sind ".$elements." Elemente im Array.<BR>Unsortierte Auflisteung:<BR>";
					asort($abstand);
				}
				echo "
				</TD>
			</TR>";
			IF($num2 > '0')
			{
				echo "
				
				<TR id='kat'>
					<TD style='height:280px;'>",$imgsrc."
					</TD>
				</TR>
				
				<TR id='kat'>
					<TD id='kat1'>
					Tragen Sie hier bitte die Ortsbezeichnung f&uuml;r dieses Bild ein:
					</TD>
				</TR>

				<TR id='kat'>
				<TD id='kat'>";
				IF($stat !== 'new')
				{
					IF($elements == '0')
					{
						//wenn weit und breit kein bekannter Ort vorhanden ist, wird nur das Textfeld angezeigt:
						echo '
						<INPUT TYPE = "text" name="ort" id="ort" maxlength="50" style = "width:200px;" />';
					}
					ELSE
					{
						echo '
						<SELECT name="ort" id="ort" SIZE="1" style = "width:203px;">
						<OPTION VALUE = "">neuen Ort anlegen</OPTION>';
						$zv = 0;
						FOREACH($abstand AS $ABST)
							{
								IF($zv == 0)
								{
									$auswahl = 'SELECTED';
								}
								ELSE
								{
									$auswahl = '';
								}
								$pos = array_search($ABST,$abstand);
								echo "<OPTION VALUE='$ort_arr[$pos]' $auswahl>".$ort_arr[$pos]."</OPTION>";
								//echo $locid[$pos]."&#160;&#160;&#160;&#160;".$ort_arr[$pos]."&#160;&#160;&#160;&#160;".$ABST."<BR>";
								//echo $ABST.", zugeh. Ort: ".$ort[$pos]."<BR>";
								$zv++;
							}
						echo '	
						</SELECT>';
					}
				}
				ELSE
				{
					//Variante, wenn ein euer Ort angelegt werden soll:
					echo '
					<INPUT TYPE = "text" name="ort" id="ort" maxlength="50" style = "width:200px;" />';
				}
				echo "</TD>
				</TR>

				<TR id='kat'>
					<TD id='kat1'>
					<p align='center'><INPUT type='submit' value='Weiter'></p>
					</TD>
				</TR>";
			}
			echo "
		</TABLE>
		<input type='hidden' name='loc_id' value='$loc_id'>
		</FORM>
	</div>
	
	<div id='spalte2F'>
		<p id='elf' style='background-color:white; padding: 5px; margin-top: 4px; margin-left: 0px; text-align:center;'>Hilfe zur Ortsbezeichnung<BR></p>";
		IF($loc_id !== '0' AND $loc_id !== '')
		{
		$result12 = mysql_query( "SELECT * FROM $table12 WHERE loc_id = '$loc_id'");
		@$longitude = mysql_result($result12, $i12, 'longitude');
		@$latitude = mysql_result($result12, $i12, 'latitude');
		
		echo "<iframe src='../recherche/show_map.php?lat=$latitude&long=$longitude&width=399&height=404' frameborder='0' style='width:405px; height:410px;'>Ihr Browser unterst&uuml;tzt leider keine eingebetteten Frames.</iframe>";
		}
	echo "	
	</div>
	
	<div id='filmstreifen'>
	</div>
	
	<p id='fuss'><A style='margin-right:745px;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>".$cr."</p>

</div>";

mysql_close($conn);

?>
<script language="javascript">
document.ortsbezeichnung.ort.focus();
</script>
</DIV>
</CENTER>
</BODY>
</HTML>