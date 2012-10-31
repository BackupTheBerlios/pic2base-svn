<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
	header('Location: ../../../index.php');
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Vorschaubild-Resizing</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: prev_image_repair1.php
 *
 * Copyright (c) 2011 - 2011 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 * 
 * Mit dem Skript koennen falsch skalierte HQ-Vorschaubilder nachtraeglich korrekt auf max. 800 px skaliert werden
 * Der Skalierungsfehler trat vereinzelt in einer sehr fruehen p2b-Version auf
 */

if(array_key_exists('param',$_GET))
{
	$param = $_GET['param']; 
}
else
{
	$param = '';
}
//echo $param;

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';	

echo "<div class='page'>

	<p id='kopf'>pic2base :: Admin-Bereich - Korrektur der HQ-Vorschaubild-Gr&ouml;&szlig;e</p>

	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
		  include 'adminnavigation.php';
		echo "</div>
	</div>

	<div class='content'>
		<p style='margin:30px 0px; text-align:center'>";
		//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		
		echo "Es wird ermittelt, wieviel hq-Vorschaubilder eine von 800 x X (Quer)<BR>
		bzw. X x 800 Pixel (Hoch) haben und korrigiert werden m&uuml;ssen:<BR><BR>
		Dies kann eine Weile dauern.<BR>
		Bitte haben Sie einen Moment Geduld...<BR><BR>";
				
		//Ermittlung der Querformat-Bilder mit falschem Format:
		$ordner = $pic_hq_path;
		$nq = 0;					//Zaehlvariable fuer die zu bearbeitenden Bilder (Bilder im Querformat)
		$verz=opendir($ordner);		//$ordner: Ordner, in dem sich die hq-Previews befinden
		$hinweis = '';
		while($datei_name=readdir($verz))
		{
			if($datei_name != "" && $datei_name != "." && $datei_name != "..")
			{
				//echo $datei_name;
				$info = pathinfo($datei_name);
				$extension = strtolower($info['extension']);
				IF($extension == 'jpg')
				{
					//Kontrolle, ob eine der Seiten gr&ouml;&szlig;er als 800 Pixel ist:					
					@$params=getimagesize($pic_hq_path."/".$datei_name);
					$breite = $params[0];
					$hoehe = $params[1];
					
					IF($breite > $hoehe)
					{
						$format = 'Quer';
						IF($breite > 800)
						{
							$hinweis = 'Korrektur ist erforderlich f&uuml;r: ';
							$nq++;
							
							//HQ aus Original erzeugen
							$FILE = $pic_path."/".str_replace("_hq","",$datei_name);	//Original(jpg)-Datei
							//echo $FILE."<BR>";
							$max_len = '800';
							// Hier erfolgt die eigentliche Neuskalierung der HQ-Bilder:										
							IF($param == 'start')
							{
								//echo "Neu-Skalierung erfolgt f&uuml;r Bild ".$nq."...<BR>";
								//$FileNameHQ = resizeOriginalPicture($FILE, $pic_hq_path, $max_len, $sr);
							}
						}
						ELSE
						{
							$hinweis = '';
						}
					}
					//echo $text.", Breite: ".$breite.", H&ouml;he: ".$hoehe.", Format: ".$format." ".$hinweis."<BR>";
				}
			}
		}

		echo "Bei ".$nq." Dateien im Querformat ist eine Korrektur erfordelich.<BR>";
	
		
		$ordner = $pic_hq_path;
		$nh = 0;					//Zaehlvariable fuer die zu bearbeitenden Bilder (Bilder im Hochformat)
		$verz=opendir($ordner);		//$ordner: Ordner, in dem sich die hq-Previews befinden
		$hinweis = '';
		while($datei_name=readdir($verz))
		{
			if($datei_name != "" && $datei_name != "." && $datei_name != "..")
			{
				//echo $datei_name;
				$info = pathinfo($datei_name);
				$extension = strtolower($info['extension']);
				IF($extension == 'jpg')
				{
					//Kontrolle, ob eine der Seiten gr&ouml;&szlig;er als 800 Pixel ist:					
					@$params=getimagesize($ordner."/".$datei_name);
					$breite = $params[0];
					$hoehe = $params[1];
					//echo "H: ".$hoehe.", B: ".$breite."<BR>";
					
					IF($hoehe > $breite)
					{
						$format = 'Hoch';
						IF($hoehe > 800)
						{
							$hinweis = 'Korrektur ist erforderlich f&uuml;r ein Hochformat-Bild!';
							$nh++;
							
							//HQ des gedrehten Bildes neu skalieren:
							
							$FILE = $ordner."/".$datei_name;	//bisherige rotierte HQ-Datei
							//echo $FILE."<BR>";
							$max_len = '800';
							// Hier erfolgt die eigentliche Neuskalierung der rotierten HQ-Bilder:										
							IF($param == 'start')
							{								
								echo "Neu-Skalierung erfolgt f&uuml;r Bild ".$nq."...<BR>";
								$conv = buildConvertCommand($sr);
						      	$command = $conv." -quality 80 -size ".$max_len."x".$max_len." ".$FILE." -resize ".$max_len."x".$max_len." ".$FILE."";
						      	$output = shell_exec($command);
							}
						}
						ELSE
						{
							$hinweis = '';
						}
					}
				}
			}
		}
		echo "Bei ".$nh." Dateien im Hochformat ist eine Korrektur erfordelich.<BR><BR>";
		IF($nq > 0 OR $nh > 0)
		{
			echo "Soll die Korrektur nun erfolgen?<BR><BR>
			<input type='button' Value='Ja' onClick='location.href=\"prev_image_repair1.php?param=start\"' style=margin-right:20px;>
			<input type='button' Value='Nein' onClick='location.href=\"$inst_path/pic2base/bin/html/start.php\"'>";
			IF($param == 'start')
			{
				echo "<meta http-equiv='Refresh', Content='0; prev_image_repair1.php'>";
			}
		}
		ELSE
		{
			echo "<b>Es ist keine Korrektur erforderlich.</b><BR><BR>
			<input type='button' Value='zur Startseite' onClick='location.href=\"$inst_path/pic2base/bin/html/start.php\"'>";
		}
		
		//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		echo "</p>
	</div>
	
	<br style='clear:both;' />
	<!--<p id='fuss'>$cr</p>-->
	<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>
</div>
</DIV>";
?>
</CENTER>
</BODY>
</HTML>