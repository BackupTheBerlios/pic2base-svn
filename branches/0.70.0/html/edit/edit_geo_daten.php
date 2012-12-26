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
	<TITLE>pic2base - Geo-Referenzierung</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
	<script language="JavaScript" type="text/javascript">
	function chkLogger()
	{
		if(document.geo_zuweisung.data_logger.value == "")
		{
			alert("Geben Sie bitte Ihren Daten-Logger an!");
			document.geo_zuweisung.data_logger.focus();
			return false;
		}
		if(document.geo_zuweisung.geo_file.value == "")
		{
			alert("Bitte geben Sie eine Track-Datei an!");
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
 * Copyright (c) 2003 - 2012 Klaus Henneberg
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
include $sr.'/bin/share/functions/ajax_functions.php';
include $sr.'/bin/share/functions/main_functions.php';

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');

echo "
<div class='page'>
	<FORM name='geo_zuweisung', method='post', action='edit_geo_daten_action.php', ENCTYPE='multipart/form-data' onSubmit='return chkLogger()'>
	<p id='kopf'>pic2base :: Datensatz-Bearbeitung (Geo-Referenzierung)<span class='klein'> User: $username)</span></p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
			createNavi3_1($uid);
		echo "</div>
	</div>
	
	<div id='spalte1F'>
		<p id='elf' style='background-color:white; padding: 5px; margin-top: 4px; margin-left: 0px; text-align:center;'>Auswahl der Track-Datei<BR></p>
		<SPAN style='text-align:center';>
		<table id='kat'>

			<tr>
				<td align='center' colspan = '2'><p style='text-align:left; margin-left:12px;'>
					In welcher Zeitzone wurden die Bilder aufgenommen?</p>
					<SELECT name='timezone' style='width:328px; margin-bottom:20px;'>";
					//Welche Zeitzone hat der User ggf. bereits gespeichert?
					$timezone = mysql_result($result0, isset($i0), 'timezone');
					//Welchen Datenlogger hat der User ggf. bereits gespeichert?
					$logger_type = mysql_result($result0, isset($i0), 'logger_type');
					//Ermittlung aller Zeitzonen:
					$result1 = mysql_query("SELECT * FROM $table19");
					$num1 = mysql_num_rows($result1);
					FOR($i1=0; $i1<$num1; $i1++)
					{
						$zone_number = mysql_result($result1, $i1, 'zone_number');
						$zone_name = mysql_result($result1, $i1, 'zone_name');
						IF($zone_number == $timezone)
						{
							$sel = 'selected';
						}
						ELSE
						{
							$sel = '';
						}
						echo "<OPTION VALUE='$zone_number' $sel>".$zone_name."</OPTION>";
					}
					echo "
					</SELECT>
					
					<SELECT name='data_logger' style='width:328px;'>";
					//Ermittlung aller Logger-Typen:
					$result3 = mysql_query("SELECT * FROM $table18 ORDER BY logger_number");
					$num3 = mysql_num_rows($result3);
					FOR($i3=0; $i3<$num3; $i3++)
					{
						$logger_number = mysql_result($result3, $i3, 'logger_number');
						$logger_name = mysql_result($result3, $i3, 'logger_name');
						$enabled = mysql_result($result3, $i3, 'enabled');
						IF($logger_number == $logger_type)
						{
							$sel = 'selected';
						}
						ELSE
						{
							$sel = '';
						}
						
						IF($enabled == 0)
						{
							$enabled = 'disabled';
						}
						ELSE
						{
							$enabled = '';
						}
						echo "<OPTION VALUE='$logger_number' $sel $enabled>".$logger_name."</OPTION>";
					}
					echo "
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
	
	<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>

</div>
</FORM>";

mysql_close($conn);
?>
</DIV>
</CENTER>
</BODY>
</HTML>