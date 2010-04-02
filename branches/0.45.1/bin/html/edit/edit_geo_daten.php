<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Geo-Referenzierung</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
	<!--<script type="text/javascript" src="../../ajax/inc/vorschau.js"></script>-->
	<script language="JavaScript" type="text/javascript">
	function chkLogger()
	{
		if(document.geo_zuweisung.data_logger.value == "")
		{
			alert("Bitte wählen Sie Ihren Daten-Logger aus!");
			document.geo_zuweisung.data_logger.focus();
			return false;
		}
		if(document.geo_zuweisung.geo_file.value == "")
		{
			alert("Bitte wählen Sie eine Track-Datei aus!");
			document.geo_zuweisung.geo_file.focus();
			return false;
		}
	}
	</script>
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: edit_geo_daten.php
 *
 * Copyright (c) 2003 - 2005 Klaus Henneberg
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
include $sr.'/bin/share/functions/ajax_functions.php';
include $sr.'/bin/share/functions/main_functions.php';

//var_dump($_GET);

echo "
<div class='page'>
	<FORM name='geo_zuweisung', method='post', action='edit_geo_daten_action.php', ENCTYPE='multipart/form-data' onSubmit='return chkLogger()'>
	<p id='kopf'>pic2base :: Datensatz-Bearbeitung (Geo-Referenzierung)<span class='klein'> User: $c_username)</span></p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
			createNavi3_1($c_username);
		echo "</div>
	</div>
	
	<div id='spalte1F'>
		<p id='elf' style='background-color:white; padding: 5px; margin-top: 4px; margin-left: 0px; text-align:center;'>Auswahl der Track-Datei<BR></p>
		<SPAN style='text-align:center';>
		<table id='kat'>

			<tr>
				<td align='center' colspan = '2'><p style='text-align:left; margin-left:12px;'>
					In welcher Zeitzone wurden die Bilder aufgenommen?</p>
					<SELECT name='timezone' style='width:328px; margin-bottom:20px;'>
					<OPTION VALUE='-12'>Bakerinsel (UTC - 12h)</OPTION>
					<OPTION VALUE='-11'>Midway-Inseln, Samoa (UTC - 11h)</OPTION>
					<OPTION VALUE='-10'>Alaska Hawaii (UTC - 10h)</OPTION>
					<OPTION VALUE='-9'>Franz. Polynesien, USA (AKST / UTC - 9h)</OPTION>
					<OPTION VALUE='-8'>Kanada, Mexico, USA (PST / UTC - 8h)</OPTION>
					<OPTION VALUE='-7'>Kanada, Mexico, USA (MST / UTC - 7h)</OPTION>
					<OPTION VALUE='-6'>Chile, Costa Rica, Honduras (CST / UTC - 6h)</OPTION>
					<OPTION VALUE='-5'>Bahamas, Haiti, USA (EST / UTC - 5h)</OPTION>
					<OPTION VALUE='-4'>Barbados, Grenada, Gr&ouml;nland (AST / UTC - 4h)</OPTION>
					<OPTION VALUE='-3'>Argentinien, Brasilien, Uruguay (UTC - 3h)</OPTION>
					<OPTION VALUE='-2'>Brasilien (UTC - 2h)</OPTION>
					<OPTION VALUE='-1'>Gr&ouml;nland, Kap Verde (UTC - 1h)</OPTION>
					<OPTION VALUE='0'>London, Lissabon, Reykjavik (GMT / WET / UTC)</OPTION>
					<OPTION VALUE='1' SELECTED>Berlin, Prag, Rom (CET / MEZ)</OPTION>
					<OPTION VALUE='2'>Helsinki, Kairo, Sofia (CESZ / EET)</OPTION>
					<OPTION VALUE='3'>Baghdad, Moskau, Sankt Petersburg (MSK, BT)</OPTION>
					<OPTION VALUE='4'>Armenien, Georgien, VAR (UTC + 4h)</OPTION>
					<OPTION VALUE='5'>Pakistan (UTC + 5h)</OPTION>
					<OPTION VALUE='6'>Bangladesh (UTC + 6h)</OPTION>
					<OPTION VALUE='7'>Pnom Phen, Saigon, Hanoi (UTC + 7h)</OPTION>
					<OPTION VALUE='8'>Peking (UTC + 8h)</OPTION>
					<OPTION VALUE='9'>Seoul, Tokio (UTC + 9h)</OPTION>
					<OPTION VALUE='10'>Sidney (UTC + 10h)</OPTION>
					<OPTION VALUE='11'>Neukaledonien(UTC + 11h)</OPTION>
					<OPTION VALUE='12'>Fidschi, Kiribati, Neuseeland (IDLE / UTC + 12h)</OPTION>
					
					</SELECT>
					
					<SELECT name='data_logger' style='width:328px;'>
					<OPTION VALUE='' SELECT>----- W&auml;hlen Sie hier Ihren Daten-Logger aus -----</OPTION>
					<OPTION VALUE='1'>Sony CS1 oder kompatible (nmea-Format)</OPTION>
					<OPTION VALUE='2'>Garmin GPSmap 60CS(x) - gpx-Datei</OPTION>
					<OPTION VALUE='3' disabled>Garmin GPSmap 60CS(x) - txt-Datei</OPTION>
					<OPTION VALUE='4' disabled>##########  Testbereich:  ##########</OPTION>
					<OPTION VALUE='5' disabled>Alan Map500 tracklogs (.trl)</OPTION>
					<OPTION VALUE='6' disabled>CarteSurTable data file</OPTION>
					<OPTION VALUE='7' disabled>CarteSurTable data file</OPTION
					<OPTION VALUE='8' disabled>CompeGPS data files (.wpt/.trk/.rte)</OPTION>
					<OPTION VALUE='9' disabled>cotoGPS for Palm/OS</OPTION>
					<OPTION VALUE='10' disabled>Data Logger iBlue747 csv</OPTION>
					<OPTION VALUE='11' disabled>Dell Axim Navigation System (.gpb) file format</OPTION>
					<OPTION VALUE='12' disabled>DeLorme Street Atlas Route</OPTION>
					<OPTION VALUE='13' disabled>Destinator TrackLogs (.dat)</OPTION>
					<OPTION VALUE='14' disabled>FAI/IGC Flight Recorder Data Format</OPTION>
					<OPTION VALUE='15' disabled>G7ToWin data files (.g7t)</OPTION>
					<OPTION VALUE='16' disabled>Garmin Logbook XML</OPTION>
					<OPTION VALUE='17'>Garmin MapSource - gdb</OPTION>
					<OPTION VALUE='18' disabled>Garmin MapSource - mps</OPTION>
					<OPTION VALUE='19' disabled>Garmin MapSource - txt (tab delimited)</OPTION>
					<OPTION VALUE='20' disabled>Garmin PCX5</OPTION>
					<OPTION VALUE='21' disabled>Geogrid-Viewer tracklogs (.log)</OPTION
					<OPTION VALUE='22' disabled>Google Earth (Keyhole) Markup Language</OPTION>
					<OPTION VALUE='23' disabled>Google Maps XML</OPTION>
					<OPTION VALUE='24' disabled>GoPal GPS track log (.trk)</OPTION>
					<OPTION VALUE='25' disabled>GPS TrackMaker</OPTION>
					<OPTION VALUE='26' disabled>GPX XML</OPTION>
					<OPTION VALUE='27' disabled>HikeTech</OPTION>
					<OPTION VALUE='28' disabled>Holux M-241 (MTK based) Binary File Format</OPTION>
					<OPTION VALUE='29' disabled>IGN Rando track files</OPTION>
					<OPTION VALUE='30' disabled>IGO8 .trk</OPTION>
					<OPTION VALUE='31' disabled>Kartex 5 Track File</OPTION>
					<OPTION VALUE='32' disabled>Kompass (DAV) Track (.tk)</OPTION>
					<OPTION VALUE='33' disabled>KuDaTa PsiTrex text</option>
					<OPTION VALUE='34' disabled>Lowrance USR</option>
					<OPTION VALUE='35' disabled>Magellan Mapsend</OPTION>
					<OPTION VALUE='36' disabled>Magellan SD files (as for eXplorist)</option>
					<OPTION VALUE='37' disabled>Magellan SD files (as for Meridian)</option>
					<OPTION VALUE='38' disabled>MagicMaps IK3D project file (.ikt)</OPTION>
					<OPTION VALUE='39' disabled>MTK Logger (iBlue 747,...)</OPTION>
					<OPTION VALUE='40' disabled>National Geographic Topo 2.x .tpo</OPTION>
					<OPTION VALUE='41' disabled>National Geographic Topo 3.x/4.x .tpo</option>
					<OPTION VALUE='42' disabled>NMEA 0183 sentences</OPTION>
					<OPTION VALUE='43' disabled>OziExplorer</OPTION>
					<OPTION VALUE='44' disabled>PathAway Database for Palm/OS</option>
					<OPTION VALUE='45' disabled>Sportsim track files (part of zipped .ssz files)</OPTION>
					<OPTION VALUE='46' disabled>Suunto Trek Manager (STM) .sdf files</OPTION>
					<OPTION VALUE='47' disabled>Suunto Trek Manager (STM) WaypointPlus files</option>
					<OPTION VALUE='48' disabled>Swiss Map 25/50/100 (.xol)</option>
					<OPTION VALUE='49' disabled>TrackLogs digital mapping (.trl)</OPTION>
					<OPTION VALUE='50' disabled>Universal csv with field structure in first line</OPTION>
					<OPTION VALUE='51' disabled>VidaOne GPS for Pocket PC (.gpb)</option>
					<OPTION VALUE='52' disabled>Vito Navigator II tracks</OPTION>
					<OPTION VALUE='53' disabled>Vito SmartMap tracks (.vtt)</OPTION>
					<OPTION VALUE='54' disabled>Wintec WBT-100/200 Binary File Format</OPTION>
					<OPTION VALUE='55' disabled>Wintec WBT-201/G-Rays 2 Binary File Format</OPTION>
					<OPTION VALUE='56' disabled>##########  Testbereich  ##########</OPTION>
					</SELECT>
				</td>
			</tr>
			
			<tr>
				<td colspan='2'>
					&nbsp;
				</td>
			</tr>
			
			<tr>
				<td align='center' colspan = '2'>
					W&auml;hlen Sie hier die Track-Datei aus:
				</td>
			</tr>
			
			<tr>
				<td colspan='2'>
					&nbsp;
				</td>
			</tr>
		
			<tr>
				<td colspan = '2' align='center'>
					<input type='file' name='geo_file' size='32'>
				</td>
			</tr>
			
			<tr>
				<td colspan='2'>
					&nbsp;
				</td>
			</tr>
			
			<tr>
				<td align='center'>
					<input type='hidden' name='MAX_FILE_SIZE' value='1000'>
					<input type=submit name='ge' value='Los!' class='button1'>
				</td>
				<TD align='center'>
					<input type=button name='cancel' value='Abbrechen' class='button1' OnClick=\"javascript:history.back()\">
				</td>
			</tr>
			
			<tr>
				<td colspan='2'>
					&nbsp;
				</td>
			</tr>
			
			<tr>
				<td colspan='2'>
					Wenn Sie Ihre Trackdaten zun&auml;chst nur in GoogleEarth darstellen wollen ohne eine Geo-Referenzierung vorzunehmen,<BR>klicken Sie auf den Button \"Track ansehen\".
				</td>
			</tr>
			
			<tr>
				<td colspan='2'>
					&nbsp;
				</td>
			</tr>
			
			<tr>
				<td colspan='2' align='center'>
					<input type='submit' name='ge' value='Track ansehen' style='width:330px;'>
				</td>
			</tr>
		</table>
		</SPAN>
	</div>
	
	<div id='spalte2F'>
		<p id='elf' style='background-color:white; padding: 5px; margin-top: 4px; margin-left: 0px; text-align:center;'>Hinweis<BR></p>
		<SPAN style='text-align:center';>
		<table id='kat'>
			<tr>
				<td align='left'>
					Hilfe zur Geo-Referenzierung finden Sie in der <a href='../help/help1.php?page=4'>Online-Hilfe</a>.
				</td>
			</tr>
			
			<tr>
				<td>
					
				</td>
			</tr>
		</table>
		</SPAN>
	</div>
	
	<div id='filmstreifen'>
	</div>
	
	<p id='fuss'><A style='margin-right:745px;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>".$cr."</p>

</div>
</FORM>";

mysql_close($conn);
?>
</DIV>
</CENTER>
</BODY>
</HTML>