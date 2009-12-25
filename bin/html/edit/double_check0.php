<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Datensatz-Bearbeitung</TITLE>
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
 * File: double_check0.php
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

echo "
<div class='page'>

	<p id='kopf'>pic2base :: Doubletten-Pr&uuml;fung (User: $c_username)</p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
		createNavi3_1($c_username);
		echo "</div>
	</div>
	
	<div class='content'>";
	//Prüfung auf übereinstimmende Bildgröße, Original-Dateinamen, Dateigröße:
	echo "<CENTER>
		<table class='liste1' border='0'>
		<tbody>
		
		<TR class='normal' style='height:3px;'>
		<TD class='normal' align='center' bgcolor='#FF9900' colspan='10'>
		</TD>
		</TR>
		
		<TR class='normal'>
		<TD class='normal' align='center' colspan='10'>
		Die folgenden Bilder weisen in Breite, Höhe, Original-Dateinamen und Datei-Größe Übereinstimmung auf.<BR>
		Wenn Sie ein Bild mit der Maus überfahren, erhalten Sie eine verg&ouml;ßerte Vorschau.<BR>
		<FONT COLOR='red'>Wenn Sie auf ein Bild klicken, wird dies OHNE R&Uuml;CKFRAGE GEL&Ouml;SCHT!</FONT>
		</TD>
		</TR>
		
		<TR class='normal' style='height:15px;'>
		<TD class='normal' align='center' width = '10%'>
		<TD class='normal' align='center' width = '10%'>
		<TD class='normal' align='center' width = '10%'>
		<TD class='normal' align='center' width = '10%'>
		<TD class='normal' align='center' width = '10%'>
		<TD class='normal' align='center' width = '10%'>
		<TD class='normal' align='center' width = '10%'>
		<TD class='normal' align='center' width = '10%'>
		<TD class='normal' align='center' width = '10%'>
		<TD class='normal' align='center' width = '10%'>
		</TD>
		</TR>";
	
	$result2 = mysql_query( "SELECT DISTINCT Height FROM $table2");
	$num2 = mysql_num_rows($result2);
	//echo $num2."<BR>";
	FOR($i2='0'; $i2<$num2; $i2++)
	{
		$height = mysql_result($result2, $i2, 'Height');
		$result3 = mysql_query( "SELECT DISTINCT Width FROM $table2 WHERE Height = '$height'");
		$num3 = mysql_num_rows($result3);
		FOR($i3='0'; $i3<$num3; $i3++)
		{
			$width = mysql_result($result3, $i3, 'Width');
			//echo "Breite: ".$width.", Höhe: ".$height."<BR>";
			$result4 = mysql_query( "SELECT * FROM $table2 WHERE Width = '$width' AND Height = '$height'");
			$num4 = mysql_num_rows($result4);
			
			IF($num4 > '1')
			{
				//echo "Es gibt ".$num4." Bilder mit den Maßen ".$width." x ".$height.", <BR>";
				$summe = $summe + $num4;
				//Wenn es mehr als ein Bild mit den Maßen gibt, wird auf Übereinstimmung des Original-Dateinamens geprüft:
				$result5 = mysql_query( "SELECT FileNameOri FROM $table2 WHERE Width = '$width' AND Height = '$height' GROUP BY FileNameOri");
				$num5 = mysql_num_rows($result5);
				IF($num4 > $num5)
				{
					echo "Es gibt ".$num4." Bilder mit den Maßen ".$width." x ".$height.", <BR>
					aber nur ".$num5." Dateinamen<BR>";
				}
				FOR($i5='0'; $i5<$num5; $i5++)
				{
					//Prüfung auf Übereinstimmung der Dateigröße bei den Bildern mit übereinstimmenden Datei-Namen:
					$filenameori = mysql_result($result5, $i5, 'FileNameOri');
					//echo $filenameori."<BR>";
					$result6 = mysql_query( "SELECT FileSize FROM $table2 WHERE Width = '$width' AND Height = '$height' AND FileNameOri = '$filenameori' ORDER BY FileSize");
					$num6 = mysql_num_rows($result6);
					IF($num6 > '1')
					{
						echo "Der Dateiname ".$filenameori." wurde ".$num6." mal vergeben.<BR>";
						
						
						$filesize = mysql_result($result6, $i6, 'FileSize');
						//$filenameori = mysql_result($result6, $i6, 'FileNameOri');
						$result7 = mysql_query( "SELECT * FROM $table2 WHERE Width = '$width' AND Height = '$height' AND FileNameOri = '$filenameori' AND FileSize = '$filesize'");
						$num7 = mysql_num_rows($result7);
						//echo "Anzahl identischer Bilder: ".$num7."<BR>";
						IF($num7 > '1')
						{
							
							echo "<TR>";
							FOR($i7='0'; $i7<$num7; $i7++)
							{
								$pic_id = mysql_result($result7, $i7, 'pic_id');
								$filenameori = mysql_result($result7, $i7, 'FileNameOri');
								$description = mysql_result($result7, $i7, 'Description');
								$loc_id = mysql_result($result7, $i7, 'loc_id');
								$result8 = mysql_query( "SELECT * FROM $table10 WHERE pic_id = '$pic_id'");
								$num8 = mysql_num_rows($result8);
								IF($description == '' OR $loc_id == '0' OR $num8 > '0')
								{
									//Wenn das Bild schon bearbeitet wurde, kam#nn
									$ref = "#";
								}
								ELSE
								{
									$ref = "del_pic.php?pic_id=$pic_id";
								}
								$file = mysql_result($result7, $i7, 'FileNameHQ');
								$bild = '../../../images/vorschau/hq-preview/'.$file;
								$param = getimagesize('../../../images/vorschau/hq-preview/'.$file);
								$width = $param[0];
								$height = $param[1];
								//echo $width."/".$height."<BR>";
								
								$hoehe = 40;
								$breite = $hoehe / $height * $width;
								
								echo "<SPAN style='cursor:pointer;'>
								<td class='normal' align='center'>
								<a href='$ref' target=\"vollbild\" onMouseOut=\"ZeigeBild('$bild', '$width', '$height');return false\"  title='Bild $pic_id, $filenameori'><img src='../../../images/vorschau/hq-preview/$file' alt='Bild $pic_id', width='$breite', height='$hoehe'>
								</a>
								</TD>
								</span>";
							}
							echo "</TR>";
							
						}
					}
					
				}
			}
		}
	}
	/*
	$result2 = mysql_query( "SELECT DISTINCT Height FROM $table2");
	$num2 = mysql_num_rows($result2);
	//echo $num2."<BR>";
	FOR($i2='0'; $i2<$num2; $i2++)
	{
		$height = mysql_result($result2, $i2, 'Height');
		$result3 = mysql_query( "SELECT DISTINCT Width FROM $table2 WHERE Height = '$height'");
		$num3 = mysql_num_rows($result3);
		FOR($i3='0'; $i3<$num3; $i3++)
		{
			$width = mysql_result($result3, $i3, 'Width');
			//echo "Breite: ".$width.", Höhe: ".$height."<BR>";
			$result4 = mysql_query( "SELECT FileNameOri FROM $table2 WHERE Width = '$width' AND Height = '$height'");
			echo mysql_error();
			unset($filename_arr);
			unset($duplikat_arr);
			
			$num4 = mysql_num_rows($result4);
			IF($num4 > '1')
			{
				echo "Es gibt ".$num4." Bild(er) mit den Maßen ".$width." x ".$height."<BR>";
				FOR($i4='0'; $i4<$num4; $i4++)
				{
					$filenameori = mysql_result($result4, $i4, 'FileNameOri');
					IF(in_array($filenameori,$filename_arr))
					{
						$duplikat_arr = $filenameori;
					}
					ELSE
					{
						$filename_arr = $filenameori;
					}
				}
				FOREACH($duplikat_arr AS $duplikat)
				{
					echo "doppelter Dateiname: ".$duplikat."<BR>";
				}
				/*
				FOR($i4='0'; $i4<$num4; $i4++)
				{
					$result5 = mysql_query( "SELECT FileNameOri FROM $table2 WHERE Width = '$width' AND Height = '$height'");
					$num5 = mysql_num_rows($result5);
					FOR($i5='0'; $i5<$num5; $i5++)
					{
						//$anz = mysql_result($result5, $i5, 'COUNT(FileNameOri');
						$anz = '???';
						$filenameori = mysql_result($result5, $i5, 'FileNameOri');
						echo "Der Dateiname ".$filenameori." wird ".$anz." mal verwendet.<BR>";
					}
				}
				*//*
			}
		}
	}
	*/
	
	
	echo "
		
	</tbody>
	</table>
	</div>
	<br style='clear:both;' />

	<p id='fuss'><A style='margin-right:745px;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>".$cr."</p>

</div>";


mysql_close($conn);
?>

<SCRIPT language="javascript">
	function ZeigeBild(bildname,breite,hoehe)
	{
	anotherWindow = window.open("", "bildfenster", "");
	
	// Wird bereits ein Bild in der "Großansicht" angezeigt? - dann wird es geschlossen:
	if (anotherWindow != null)
	{
		//alert("Zu!");
		anotherWindow.close();
	}  
	
	var ref,parameter,dateiname,htmlcode,b=breite,h=hoehe;
	
	dateiname=bildname.substring(bildname.indexOf("/")+1,bildname.length);
	
	htmlcode="<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
	htmlcode+="<html style=\"height: 100%\">\n<head>\n<title>"+dateiname+"<\/title>\n";
	htmlcode+="<\/head>\n<body style=\"margin: 0; padding: 0; height: 100%\">\n";
	htmlcode+="<img src=\""+bildname+"\" width=\"100%\" height=\"100%\" alt=\""+bildname+"\" title=\"[Mausklick schlie&szlig;t Fenster!]\" onclick=\"window.close()\">\n<\/body>\n<\/html>\n";
	
	parameter="width="+b+",height="+h+",screenX="+(screen.width-b)/2+",screenY="+(screen.height-h)/2+",left="+(screen.width-b)/2+",top="+(screen.height-h)/2;
	
	ref=window.open("","bildfenster",parameter);
	ref.document.open("text/html");
	ref.document.write(htmlcode);
	ref.document.close();
	ref.focus();
	}
</Script>

</DIV>
</CENTER>
</BODY>
</HTML>