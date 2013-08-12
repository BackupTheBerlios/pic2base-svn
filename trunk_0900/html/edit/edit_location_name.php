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

if(isset($_COOKIE['auto_ref']))
{
	$status = "checked";
	$default_location = $_COOKIE['auto_ref'];
}
else
{
	$status = "";
	$default_location = "";
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Geo-Referenzierung (Ortsnamen)</TITLE>
	<META NAME="GENERATOR" CONTENT="eclipse">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format2.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script language="JavaScript" src="../../share/functions/resize_elements.js"></script>
  	<script language="JavaScript" src="../../share/functions/jquery-1.8.2.min.js"></script>
	<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
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
	jQuery.noConflict();
	jQuery(document).ready(checkWindowSize);
	jQuery(window).resize(checkWindowSize); 
  	</script>
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: edit_location_name.php
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
//#######################################################################################
//# Datei wird bei der Bearbeitung / Geo-Referenzierung verwendet (Benennung der Orte) ##
//#######################################################################################

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/geo_functions.php';
include $sr.'/bin/share/functions/main_functions.php';

$result1 = mysql_query( "SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$row = mysql_fetch_array($result1);

$num2 = '0';
$loc_id = '';

IF(array_key_exists('stat',$_REQUEST))
{
	$stat = $_REQUEST['stat'];
}
else
{
	$stat = '';
}

IF(!isset($i2))
{
	$i2 = 0;
}
echo "
<div class='page' id='page'>
	
	<div id='head'>
			pic2base :: Datensatz-Bearbeitung (Benennung der Ortsnamen)
	</div>
	
	<div class='navi' id='navi'>
		<div class='menucontainer'>"; //echo $default_location;
			createNavi3_1($uid);
		echo "</div>
	</div>
	
	<div id='spalte1'>
	
		<fieldset style='background-color:none; margin-top:10px;'>
			<legend style='color:blue; font-weight:bold;'>Ortsbezeichnung festlegen</legend>
			<div id='scrollbox0' style='overflow-y:scroll; padding-top:0px;'>";
		
				$result2 = mysql_query("SELECT pic_id, Owner, FileNameV, City, GPSLongitude, GPSLatitude, aktiv 
				FROM $table2 
				WHERE Owner = '$uid' 
				AND (City = 'Ortsbezeichnung' OR City = '')
				AND GPSLongitude <> 'NULL'
				AND GPSLatitude <> 'NULL'
				AND aktiv = '1'");
				echo mysql_error();
				$num2 = mysql_num_rows($result2);
				//echo "Trefferzahl: ".$num2."<BR>";
				//echo "Dateiname: ".$FileNameV.", Owner: ".$c_username.", Ort: ".$ort."<br>";
				echo "
				<FORM name = 'ortsbezeichnung' method='post' action='edit_location_name_action.php' onSubmit='return chkOrt()'>
				<center>
				<TABLE class='kat'>
					<TR class='kat'>
						<TD class='kat'>";
						SWITCH($num2)
						{
							//Erzeugung des Hinweistextes ueber dem Vorschaubild:
							CASE '0':
							echo "Es gibt kein Bild mehr ohne GPS-Ortszuweisung.<BR>";
							//wenn alle Bilder referenziert wurden, wird bei den uebersprungenen (skipped) Bildern des angemeldeten Users City wieder auf 'Ortsbezeichnung' gesetzt:
							
							$result4 = mysql_query("SELECT $table2.Owner, $table2.pic_id, $table2.City
							FROM $table2
							WHERE $table2.Owner = $uid
							AND $table2.City = 'skipped'");
							//echo mysql_error();
							$num4 = mysql_num_rows($result4);
							//echo $num4." Treffer.<BR>";
							FOR ($i4='0'; $i4<$num4; $i4++)
							{
								$pic_id = mysql_result($result4, $i4, 'pic_id');
								$result5 = mysql_query("UPDATE $table2 SET City = 'Ortsbezeichnung' WHERE pic_id = '$pic_id'");
								//echo mysql_error();
							}
							
							echo "<meta http-equiv='refresh', content='0; URL=edit_start.php'>";
							break;
							
							CASE '1':
							$FileNameV = mysql_result($result2,$i2,$table2.'.FileNameV');
							$pic_id = mysql_result($result2,$i2,$table2.'.pic_id');
							$ort = mysql_result($result2,$i2,$table2.'.City');
							echo "Es gibt noch dieses Bild ohne GPS-Ortszuweisung.";
							break;
							
							default:
							$FileNameV = mysql_result($result2,$i2,$table2.'.FileNameV');
							$pic_id = mysql_result($result2,$i2,$table2.'.pic_id');
							$ort = mysql_result($result2,$i2,$table2.'.City');
							echo "Es gibt noch ".$num2." Bilder ohne GPS-Ortszuweisung.";
							break;
						}
						//echo "File: ".$FileNameV."<BR>Ort: ".$ort."<BR>";
						IF ($num2 > '0')
						{
							$bildinfo = getimagesize($pic_thumbs_path."/".$FileNameV);
							$breite = $bildinfo[0];
							$hoehe = $bildinfo[1];
							$img_string = "http://".$_SERVER['SERVER_NAME'].$inst_path."/pic2base/images/vorschau/thumbs/".$FileNameV;
							//Datenzelle ist 300x300px gross; hier einpassen entsprechend der Ausrichtung des Vorschaubildes:
							IF($breite > $hoehe)
							{
								$imgsrc = "<img src='$img_string' width=\"300\" />";
							}
							ELSE
							{
								$imgsrc = "<img src='$img_string' height=\"270\" />";
							}
							
							//Bestimmung der Geo-Koordinaten am Aufnahmeort:
							$result3 = mysql_query( "SELECT * FROM $table2 WHERE pic_id = '$pic_id' AND aktiv = '1'");
							@$long = mysql_result($result3, $i3, 'GPSLongitude');
							@$lat = mysql_result($result3, $i3, 'GPSLatitude');
							//echo "Bild: ".$pic_id.", Long: ".$long.", Lat: ".$lat."<BR>";
							//Radius: 5 km, um welchen die vorhandenen Orte ermittelt werden:
							$radius = 10000;
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
							// Ab Version 0.60.4: es wird geprueft, ob die Tabelle geo_locations existiert. Wenn ja, werden die Tabellen pictures 
							// und geo_locations zum auffinden bekannter Orte in der Naehe verwendet, wenn nicht, nur die Tabelle pictures:
							$res = mysql_query("show tables LIKE 'geo_locations'");
							//$res = 0;
							IF(mysql_num_rows($res) == 1)
							{
								//echo "Tabelle vorhanden";
								$result5 = mysql_query( "select pic_id, City, GPSLongitude, GPSLatitude
								from 	(SELECT pic_id, City, GPSLongitude, GPSLatitude
										FROM pictures as P2
										union all
										select locid, City, GPSLongitude, GPSLatitude from geo_locations) 
						 				as X_union
								where GPSLongitude between $long_min and $long_max
								and GPSLatitude between $lat_min and $lat_max");
							}
							ELSE
							{
								//echo "Tabelle nicht da!!!";
								$result5 = mysql_query( "SELECT * FROM $table2 
								WHERE (GPSLongitude > $long_min 
								AND GPSLongitude < $long_max) 
								AND (GPSLatitude > $lat_min 
								AND GPSLatitude < $lat_max)");
							}
							
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
								$picture_id = mysql_result($result5, $i5, 'pic_id');
								$city = mysql_result($result5, $i5, 'City');
								$longitude = mysql_result($result5, $i5, 'GPSLongitude');
								$latitude = mysql_result($result5, $i5, 'GPSLatitude');
								$delta = sqrt(pow(($long - $longitude),2) + pow(($lat - $latitude),2));
								
								//echo $picture_id.", ".$City.", ".$delta."<BR>";
								IF (!in_array($city,$ort_arr) AND ($city !== 'Ortsbezeichnung'))
								{
									$locid[$it] = $picture_id;
									$ort_arr[$it] = $city;
									$abstand[$it] = $delta;
									$it++;
									//echo $location." wird im Array aufgenommen<BR>";
								}
								ELSEIF($city !== 'Ortsbezeichnung')
								{
									$position = array_search($city,$ort_arr);
									//echo $ort[$position].": bisheriger Abstand: ".$abstand[$position].", neuer Abstand: ".$delta."<BR>";
									IF($abstand[$position] > $delta)
									{
										$locid[$position] = $picture_id;
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
						<TR class='kat'>
							<TD style='height:280px;'>",$imgsrc."
							</TD>
						</TR>
						
						<TR class='kat'>
							<TD class='kat1'>
							Tragen Sie hier bitte die Ortsbezeichnung f&uuml;r dieses Bild ein:
							</TD>
						</TR>
		
						<TR class='kat'>
						<TD class='kat'>";
						IF($stat !== 'new' AND $stat !== 'skip')
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
								<SELECT name="ort" id="ort" SIZE="1" style = "width:303px;">
								<OPTION VALUE = "">neuen Ort anlegen</OPTION>';
								$zv = 0;									// $zv - Zaehlvariable
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
										
										IF(mb_detect_encoding($ort_arr[$pos]) == 'UTF-8')
										{
											$ort_arr[$pos] = $ort_arr[$pos];
										}
										ELSEIF(mb_detect_encoding($ort_arr[$pos]) == 'ASCII')
										{
											$ort_arr[$pos] = $ort_arr[$pos];
										}
							
										echo "<OPTION VALUE='$ort_arr[$pos]' $auswahl>".$ort_arr[$pos]."</OPTION>";
										//echo $locid[$pos]."&#160;&#160;&#160;&#160;".$ort_arr[$pos]."&#160;&#160;&#160;&#160;".$ABST."<BR>";
										//echo $ABST.", zugeh. Ort: ".$ort[$pos]."<BR>";
										$zv++;
									}
								echo '	
								</SELECT>';
							}
						}
						elseif($stat == 'new' AND $stat !== 'skip')
						{
							//Variante, wenn ein euer Ort angelegt werden soll:
							echo "
							<INPUT TYPE = 'text' name='ort' id='ort' maxlength='50' style = 'width:300px;' />";
						}
						elseif($stat !== 'new' AND $stat == 'skip')
						{
							// der aktuelle Bilddatensatz wird aus der Tabelle geo_tmp geloescht und die Ortsbezeichnung wird mit dem naechsten Datensatz fortgesetzt
						}
						echo "</TD>
						</TR>
						
						<TR class='kat'>
							<TD class='kat'>
							weitere Bilder autom. mit diesem Ort referenzieren: <INPUT TYPE='checkbox' name='default_location' $status>
							</TD>
						</TR>
		
						<TR class='kat'>
							<TD class='kat'>
							<p align='center'><INPUT type='button' value='&Uuml;berspringen' title='Referenzierung dieses Bildes &uuml;berspringen' onClick='location.href=\"skip_georef.php?pic_id=$pic_id&uid=$uid\"'><INPUT id='button0' type='button' value='Abbrechen' title='Gesamte Referenzierung abbrechen' style='margin-right:5px;' onClick='location.href=\"cancel_georef.php?userid=$uid\"'><INPUT id='button1' type='submit' value='Weiter'></p>
							</TD>
						</TR>";
					}
					else
					{
						$pic_id = '';
					}
					echo "
					</center>
				</TABLE>
				<input type='hidden' name='pic_id' value='$pic_id'>
				</FORM>
			</div>
		</fieldset>
	</div>
	
	<div id='spalte2'>
	
		<fieldset style='background-color:none; margin-top:10px;'>
			<legend style='color:blue; font-weight:bold;'>Hilfe zur Ortsbezeichnung</legend>
			<div id='scrollbox1' style='overflow-y:scroll; padding-top:50px; text-align:center;'>";
				IF($pic_id !== '0' AND $pic_id !== '')
				{
					$result12 = mysql_query( "SELECT * FROM $table2 WHERE pic_id = '$pic_id'");
					@$longitude = mysql_result($result12, $i12, 'GPSLongitude');
					@$latitude = mysql_result($result12, $i12, 'GPSLatitude');
					echo "<iframe src='../recherche/show_map.php?lat=$latitude&long=$longitude&width=399&height=404' frameborder='0' style='width:405px; height:410px;'>Ihr Browser unterst&uuml;tzt leider keine eingebetteten Frames.</iframe>";
				}
				echo "
			</div>
		</fieldset>
			
	</div>
	
	<div id='foot'>
		<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
	</div>

</div>";

mysql_close($conn);

?>
<script language="javascript">
var status="<?php echo $status;?>";
var default_location="<?php echo $default_location;?>";
document.ortsbezeichnung.button1.focus();
//Variante, wenn viele Bilder mit dem gleichen Ort referenziert werden sollen
//alert(document.ortsbezeichnung.ort.value);
//alert(status + ", " + default_location);
//##########  ab hier aktivieren und Ortsbezeichnung eintragen  ################
if(document.ortsbezeichnung.ort.value == default_location)
{
	//alert(document.ortsbezeichnung.ort.value);
	document.ortsbezeichnung.button1.click();
}
else
{
	//alert(document.ortsbezeichnung.ort.value);
}
//##############################################################################
</script>
</DIV>
</CENTER>
</BODY>
</HTML>