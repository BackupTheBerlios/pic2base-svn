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
	<TITLE>pic2base - Quick-Preview</TITLE>
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

<BODY LANG="de-DE">
<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: generate_quickpreview0.php
 *
 * Copyright (c) 2006 - 2013 Klaus Henneberg
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
include $sr.'/bin/share/functions/main_functions.php';

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');

//var_dump($_REQUEST);
if(array_key_exists('num',$_GET))
{
	$num = $_GET['num'];
}
if(array_key_exists('t_rest',$_REQUEST))
{
	$t_rest = $_REQUEST['t_rest'];
}
if(array_key_exists('t_0',$_REQUEST))
{
	$t_0 = $_REQUEST['t_0'];
}
if(array_key_exists('i4',$_REQUEST))
{
	$i4 = $_REQUEST['i4'];
}
if(array_key_exists('z_0',$_REQUEST))
{
	$z_0 = $_REQUEST['z_0'];
}

//fuer alle notwendigen Drehungen wird geprueft, ob bereits ein Vorschau-Bild existiert. Wenn nicht, wird dieses angelegt:
$result4 = mysql_query( "SELECT pic_id, Owner, FileName, Orientation 
FROM $table2
WHERE Owner = '$uid' 
AND (Orientation = '6' OR Orientation = '3' OR Orientation = '8')");
$num4 = mysql_num_rows($result4);
//echo $num4." Bilder, welche nicht im Querformat aufgenommen wurden.<BR>";

global $z_0; // ?????
global $t_0; // ?????
global $t_rest; // ?????

//Restzeit-Berechnung:
IF ($z_0 == '')
{
	$z_0 = 0;
}
ELSE
{
	$z_0++;
}
IF ($t_0 == '')
{
	$t_0 = mktime();
}
$t_akt = mktime();
//echo "T_0: ".$t_0.", T_akt: ".$t_akt."<BR>";

echo "
	<div class='page' id='page'>
		
		<div class='head' id='head'>
			pic2base :: Quick-Preview-Erzeugung <span class='klein'>(User:".$username.")</span>
		</div>
		
		<div class='navi' id='navi'>
			<div class='menucontainer'>";
			createNavi3_1($uid);
			echo "
			</div>
		</div>
		
		<div class='content' id='content'>
		<CENTER>
		<p style='margin:120px 0px; text-align:center'>";
		
		//echo "Es gibt ".$num1." kopfstehende Fotos.<BR>";
		//echo "Es gibt ".$num2." 90 (cw) gedrehte Fotos .<BR>";
		//echo "Es gibt ".$num3." -90 (ccw) gedrehte Fotos.<BR>";
		IF ($num > '0')
		{
			//echo $z_0."ter Durchlauf im aktuellen Prozess<BR>";
			SWITCH($num)
			{
				CASE 'X':
				//das X dient der Initialisierung bei Aufruf der Seite
				echo "Bitte haben Sie einen Moment Geduld.<BR><BR>";
				break;
				
				CASE ($num > '1'):
				echo $num." Bilder m&uuml;ssen noch bearbeitet werden.<BR>";
				break;
				
				CASE '1':
				echo $num." Bild muss noch bearbeitet werden.<BR>";
				break;
			}
			IF ($t_rest > '0')
			{
				$h = $t_rest / 3600;
				$min = round(($t_rest / 60),0);
				IF ($min > '1')
				{
					echo "Restzeit: etwa ".$min," Minuten<BR><BR>";
				}
				ELSE
				{
					echo "Restzeit: etwa ".$min," Minute<BR><BR>";
				}
			}
		}
		ELSE
		{
			echo "Es sind keine Bilder mehr zu bearbeiten.
				</CENTER>
			</div>
			
			<div class='foot' id='foot'>
				<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
			</div>
			<meta http-equiv='Refresh', content='1; url=edit_start.php'>";
			return;
		}
		
		FOR($i4='0'; $i4<$num4; $i4++)
		{
			$FileName = mysql_result($result4, $i4, 'FileName');
			
			//Feststellung, ob gedrehtes Bild existiert:
			//$verz=opendir('../../../images/originale/rotated');
			$verz=opendir($pic_rot_path);
			$n = 0;
			while($bilddatei=readdir($verz))
			{
				if($bilddatei != "." && $bilddatei != "..")
				{
					//$bildd=$bilder_verzeichnis."/".$bilddatei;
					//echo "Bild: ".$bilddatei."; Datei: ".$file_name."<BR>";
					IF ($bilddatei == $FileName)
					{
						$n++;
					}
				}
			}
			//echo "N: ".$n."<BR>";
			IF ($n == '0')
			{
				echo " Bild ".$FileName." muss gedreht werden.<BR>";
				$Orientation = mysql_result($result4, $i4, 'Orientation');
				//echo $FileName.", ".$orientation."<BR>";
				createQuickPreview($Orientation, $FileName);
				$rest = $num4 - $i4 - 1;
			}
			ELSE
			{
				$rest = '';
			}
			
			//Restzeit-Berechnung:
			IF ($z_0 > '0')
			{
				$t_rest = (($t_akt - $t_0) / $z_0) * $rest;
			}
			IF ($rest == '0')
			{
				echo "<meta http-equiv='Refresh', content='2; url=edit_start.php'>";
				return;
			}
			ELSE
			{
				echo "<meta http-equiv='Refresh', content='0; url=generate_quickpreview0.php?num=$rest&t_0=$t_0&t_rest=$t_rest&i4=$i4&z_0=$z_0'>";
			}
		}
		mysql_close($conn);
	
		IF ($num4 == 0)
		{
			echo "Es sind keine Bilder zu bearbeiten.
				</CENTER>
			</div>
			
			<div class='foot' id='foot'>
				<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
			</div>
			<meta http-equiv='Refresh', content='2; url=edit_start.php'>";
			return;
		}
		echo "
		</CENTER>
		</div>
		
		<div class='foot' id='foot'>
			<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
		</div>
		
	</div>";
?>
</div>
</BODY>
</HTML>