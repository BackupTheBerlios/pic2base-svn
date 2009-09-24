<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Quick-Preview</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?

/*
 * Project: pic2base
 * File: generate_quickpreview0.php
 *
 * Copyright (c) 2006 - 2007 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 * @copyright 2003-2005 Klaus Henneberg
 * @author Klaus Henneberg
 * @package pic2base
 * @license http://www.opensource.org/licenses/osl-2.1.php Open Software License
 */

unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = split(',',$_COOKIE['login']);
//echo $c_username;
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

//für alle notwendigen Drehungen wird geprüft, ob bereits ein Vorschau-Bild existiert. Wenn nicht, wird dieses angelegt:

$result0 = mysql($db, "SELECT * FROM $table1 WHERE username = '$c_username'");
$user_id = mysql_result($result0, $i0, 'id');

$result4 = mysql($db, "SELECT $table2.pic_id, $table2.Owner, $table2.FileName, $table14.pic_id, $table14.Orientation FROM $table2, $table14 WHERE $table2.pic_id = $table14.pic_id AND $table2.Owner = '$user_id' AND ($table14.Orientation = '6' OR $table14.Orientation = '3' OR $table14.Orientation = '8')");
$num4 = mysql_num_rows($result4);
//echo $num4." Bilder, welche nicht im Querformat aufgenommen wurden.<BR>";

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
?>

<div class="page">

	<p id="kopf">pic2base :: Quick-Preview-Erzeugung <span class='klein'>(User: <?echo $c_username;?>)</span></p>
	
	<div class="navi" style="clear:right;">
		<div class="menucontainer">
		<?
		createNavi3_1($c_username);
		//echo $navigation;
		?>
		</div>
	</div>
	
	<div class="content">
	<p style="margin:120px 0px; text-align:center">
	
	<?php
	//echo "Es gibt ".$num1." kopfstehende Fotos.<BR>";
	//echo "Es gibt ".$num2." 90° (cw) gedrehte Fotos .<BR>";
	//echo "Es gibt ".$num3." -90° (ccw) gedrehte Fotos.<BR>";
	IF ($num > '0')
	{
		//echo $z_0."ter Durchlauf im aktuellen Prozeß<BR>";
		SWITCH($num)
		{
			CASE 'X':
			//das X dient der Initialisierung bei Aufruf der Seite
			echo "Bitte haben Sie einen Moment Geduld.<BR>";
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
		echo "Es sind keine Bilder mehr zu bearbeiten.<BR>";
		echo "<meta http-equiv='Refresh', content='0; url=edit_start.php'>";
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

	?>
	
	</p>
	</div>
	<br style="clear:both;" />

	<p id="fuss"><A style='margin-right:745px;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A><?php echo $cr; ?></p>

</div>
</DIV>
</CENTER>
</BODY>
</HTML>