<html>
<head>
<meta http-equiv=’Content-Type’ content=’text/html; charset=UTF-8′ />
</head>
<body>

<?php
IF (!$_COOKIE['login'])
{
	include '../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../index.php');
}

include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/ajax_functions.php';
include $sr.'/bin/share/functions/main_functions.php';

$exiftool = buildExiftoolCommand($sr);

unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
}
$result15 = mysql_query( "SELECT id FROM $table1 WHERE username = '$c_username' AND aktiv = '1'");
$user_id = mysql_result($result15, isset($i15), 'id');
include $sr.'/bin/share/functions/permissions.php';

//var_dump($_REQUEST);
if(array_key_exists('pic_id',$_GET))
{
	$pic_id = $_GET['pic_id']; 
}
if(array_key_exists('mod',$_GET))
{
	$mod = $_GET['mod']; 
}
if(array_key_exists('base_file',$_GET))
{
	$base_file = $_GET['base_file']; 
}
if(array_key_exists('form_name',$_GET))
{
	$form_name = $_GET['form_name']; 
}
ELSE
{
	$form_name = '';
}

//Darstellung der Detailangaben zum gewaehlten Bild:
//echo "Bild-ID: ".$pic_id."<BR>BaseFile: ".$base_file."<BR>";
IF ($pic_id !=='0')
{
	$result8 = mysql_query( "SELECT pic_id, FileName, FileNameOri, Owner, note, ExifImageHeight, ExifImageWidth, FileSize, Orientation, DateTimeOriginal, Caption_Abstract 
	FROM $table2
	WHERE pic_id = '$pic_id'");
	echo mysql_error();
	IF(mysql_num_rows($result8) > '0')
	{
		echo "<p id='elf' style='background-color:white; padding: 5px; margin-top: 4px; margin-left: 0px; text-align:center;'>Details zum ausgew&auml;hlten Bild:<BR>";
		
		$DateTimeOriginal = mysql_result($result8, isset($i8), 'DateTimeOriginal');
		$FileName = mysql_result($result8, isset($i8), 'FileName');
		$FileNameOri = mysql_result($result8, isset($i8), 'FileNameOri');
		$user_id = mysql_result($result8, isset($i8), 'Owner');
		
		$result10 = mysql_query( "SELECT username, titel, vorname, name, ort FROM $table1 WHERE id = '$user_id'");
		@$Owner = mysql_result($result10, $i10, 'username');
		@$titel = mysql_result($result10, $i10, 'titel');
		@$vorname = mysql_result($result10, $i10, 'vorname');
		@$name = mysql_result($result10, $i10, 'name');
		@$ort = mysql_result($result10, $i10, 'ort');
		
		$FileSize = mysql_result($result8, isset($i8), 'FileSize');
		$Width = mysql_result($result8, isset($i8), 'ExifImageWidth');
		$Height = mysql_result($result8, isset($i8), 'ExifImageHeight');
		$Description = mysql_result($result8, isset($i8), 'Caption_Abstract');
//		echo mb_detect_encoding($Description)."<BR>";
		IF(mb_detect_encoding($Description) == 'UTF-8')
		{
			$Description = utf8_encode($Description);
		}
		$note = mysql_result($result8, isset($i8), 'note');
		$bild = $pic_path."/".restoreOriFilename($pic_id, $sr);
		$Orientation = trim(shell_exec($exiftool." -s -S '-Orientation' ".$bild));
		//echo $Orientation;
		if (isset($Orientation) AND ($Orientation !== ''))
		{
			$ausrichtung = $Orientation;
		}
		ELSE
		{
			$ausrichtung = "nicht vermerkt";
		}
		//Welche Kategorien wurden dem Bild zugewiesen?
		$result9 = mysql_query( "SELECT DISTINCT $table10.pic_id, $table10.kat_id, $table4.kat_id, $table4.kategorie, $table4.level 
		FROM $table10 INNER JOIN $table4 
		ON ($table10.kat_id = $table4.kat_id AND $table10.pic_id = '$pic_id') 
		ORDER BY $table4.level");
		$num9 = mysql_num_rows($result9);
			
		$kat_info='';
		FOR ($i9=1; $i9<$num9; $i9++)	//Als Start wurde "1" gewaehlt, da die Wurzel uninteressant ist!
		{
			//echo $num5."<BR>";
			$kategorie = htmlentities(mysql_result($result9, $i9, 'kategorie'));
			$kat_id = mysql_result($result9, $i9, $table4.'.kat_id');
			$pic_id = mysql_result($result9, $i9, $table10.'.pic_id');
			//echo $kat_id;

			IF ($i9 < $num9 - 1)
			{
				$kat_info .=$kategorie." - ";
			}
			ELSE
			{
				$kat_info .=$kategorie;
			}
		}
		//echo "Kategorien: ".$kat_info."<BR>";
		echo mysql_error();
		//$size = round($FileSize / 1024);
		$max_size = 90;			//max. Seitenlaenge des Vorschau-Bildes
		$quality = '3';
		//echo "<FORM name = beschr method=post action=save_desc.php?pic_id=$pic_id&base_file=$base_file>";
		echo "<FORM name = beschr method=post accept-encoding=”UTF-8>";
		echo "<TABLE id='detail1'>
		<TR id='detail1'>
		<TD id='detail1'>Orig.-Dateiname:</TD>
		<TD id='detail2' colspan='2'>";
		IF(strlen($FileNameOri) > 14)
		{
			$fn_text = 	substr($FileNameOri,0,10)."...";
			echo "<a href='#' title='$FileNameOri' style='text-decoration:none;'>".$fn_text."</a>";
		}
		ELSE
		{
			$fn_text = $FileNameOri;
			echo $fn_text;	
		}
		echo "</TD>
		<TD id='detailuo1' colspan = '5' style='text-align:center;'>";
		SWITCH($note)
		{
			CASE '1':
			echo "<span style='cursor:pointer;'><img src=\"$inst_path/pic2base/bin/share/images/note1.gif\" style=\"width:90px; height:18px;\" title = 'Note \"Sehr gut\"'></span>";
			break;
			
			CASE '2':
			echo "<span style='cursor:pointer;'><img src=\"$inst_path/pic2base/bin/share/images/note2.gif\" style=\"width:90px; height:18px;\" title='Note \"Gut\"'></span>";
			break;
			
			CASE '3':
			echo "<span style='cursor:pointer;'><img src=\"$inst_path/pic2base/bin/share/images/note3.gif\" style=\"width:90px; height:18px;\" title = 'Note \"Befriedigend\"'></span>";
			break;
			
			CASE '4':
			echo "<span style='cursor:pointer;'><img src=\"$inst_path/pic2base/bin/share/images/note4.gif\" style=\"width:90px; height:18px;\" title = 'Note \"Gen&uuml;gend\"'></span>";
			break;
			
			CASE '5':
			echo "<span style='cursor:pointer;'><img src=\"$inst_path/pic2base/bin/share/images/note5.gif\" style=\"width:90px; height:18px;\" title = 'Note \"Ungen&uuml;gend\"'></span>";
			break;
		}
		echo "</TD>
		</TR>
		
		<TR id='detail1'>
		<TD id='detail1'>Int. Dateiname:</TD>
		<TD id='detail2' colspan='2'>$FileName</TD>
		<TD id='detail3' rowspan='6' colspan = '5'>";
		createPreviewAjax($pic_id, $max_size, $quality);
		echo "</TD>
		</TR>
		
		<TR id='detail1'>
		<TD id='detail1'>Aufn.-Datum/Zeit:</TD>
		<TD id='detail2' colspan='2'>";
		
		$file = $sr."/images/originale/".$FileName; //echo $file;
		IF(@exif_read_data($file,"",true,false) !== false)
		{ 
			@$exifdata=exif_read_data($file,"",true,false);
			//var_dump($exifdata);
			if( array_key_exists('EXIF',$exifdata) AND @$exifdata["EXIF"]["DateTimeOriginal"])
			{
				$DTO=$exifdata["EXIF"]["DateTimeOriginal"];
			}
			//echo "Datei: ".$file.", Datum: ".$DTO."<BR>";
		}
		ELSE
		{
			$DTO = '';
			//echo "Konnte EXIF-Daten der Datei ".$file." nicht lesen.<BR>";
		}
		if ( !isset($DTO) )
		{
			$DTO = '';
		}
		IF ($DateTimeOriginal == '0000-00-00 00:00:00' AND ($DTO == '' OR $DTO == NULL OR $DTO == '1970-01-01 00:00:00'))
		{
			IF($DateTimeOriginal == '0000-00-00 00:00:00')
			{
				$DateTimeOriginal = '';
			}
			ELSE
			{
				$DateTimeOriginal = date('d.m.Y - H:i:s', strtotime($DateTimeOriginal));
			}

			IF($Owner == $c_username)
			{
				//Eigentuemer darf Aufnahme-Datum manuell ergaenzen
				echo "<input type='text' id='aufn_dat' name='aufn_dat' value = '$DateTimeOriginal' style='width:70px; height:16px;font-size:10px;text-align:center;'>&#160;<span style='cursor:pointer'><img src=\"$inst_path/pic2base/bin/share/images/calendar.png\" style=\"width:14px; height:14px; vertical-align:middle;\" title='Hier klicken, um Datum auszuw&auml;hlen' onClick='JavaScript:Kalender.anzeige(null,null,\"aufn_dat\",-3650,3651,\"%d.%m.%y\")'></span>";
				//echo "Kalender";
			}
			ELSE
			{
				echo "nicht vermerkt";
			}
			
		}
		ELSE
		{
			echo date('d.m.Y - H:i:s', strtotime($DateTimeOriginal));
			//dies ist ein Dummy, damit bei bereits vorhandenem Datum der Wert fuer $aufn_dat ordentlich als "leer" uebergeben wird:
			echo "<input type='hidden' name = 'aufn_dat'>";
		}
		echo "</TD>
		</TR>";
		
		IF(strlen(substr($vorname,0,1).". ".$name) > '14')
		{
			$autor = substr((substr($vorname,0,1).". ".$name),0,12)."...";
		}
		ELSE
		{
			$autor = substr((substr($vorname,0,1).". ".$name),0,14);
		}
		echo "
		<TR id='detail1'>
		<TD id='detail1'>Autor:</TD>
		<TD id='detail5'><span style='cursor:pointer;' title= \"$vorname $name, $ort\">".$autor."</span></TD>
		";
		
		IF($Owner == $c_username AND ((hasPermission($c_username, 'adminlogin') OR hasPermission($c_username, 'editpic'))))
		{
			echo "<TD id='detail6'><span style='cursor:pointer;'>
			<img src=\"$inst_path/pic2base/bin/share/images/change_owner.gif\" width='30' height='15' border='0'  alt='Owner wechseln' title='Bild-Eigent&uuml;merschaft &uuml;bertragen' OnClick=\"changeOwner('$pic_id', '$c_username')\"/>
			</span>";
		}
		ELSE
		{
			echo "<TD id='detail6'><span style='cursor:pointer;'>
			<img src=\"$inst_path/pic2base/bin/share/images/no_change_owner.gif\" width='30' height='15' border='0' title='Keine Berechtigung - nur Administratoren k&ouml;nnen den Eigent&uuml;mer &auml;ndern.' />
			</span>";
		}
		
		echo "
		</TD>
		</TR>
		
		<TR id='detail1'>
		<TD id='detail1'>Bild-Gr&ouml;&#223;e:</TD>
		<TD id='detail2' colspan='2'>$Width x $Height</TD>
		</TR>
		
		<TR id='detail1'>
		<TD id='detail1'>Datei-Gr&ouml;&#223;e:</TD>
		<TD id='detail2' colspan='2'>";
		getShortFS($FileSize);
		echo "</TD>
		</TR>
		
		<TR id='detail1'>
		<TD id='detail1'>Ausrichtung:</TD>
		<TD id='detail2' colspan='2'>".$ausrichtung."</TD>
		</TR>
		
		<TR id='detail1'>
		<TD id='detail1'>Alle Bilddaten:</TD>
		<TD id='detail5'><div id='tooltip1'><span style='cursor:pointer;'>
		<img src=\"$inst_path/pic2base/bin/share/images/info.gif\" width=\"15\" height=\"15\" OnClick=\"showAllDetails('$mod', '$pic_id')\" title='Detaillierte Informationen zum Bild $pic_id' alt='Info' />";
		$result13 = mysql_query( "SELECT * FROM $table2 WHERE pic_id = '$pic_id'");
		$Copyright = htmlentities(mysql_result($result13, isset($i13), 'Copyright'));
		IF($Copyright !== '')
		{
			echo "<a href='#'><img src=\"$inst_path/pic2base/bin/share/images/copyright_notice.gif\" width=\"15\" height=\"15\" title='(C)-Info' alt='Copyright-Vermerk' border='none' style='margin-left:5px;' />
			<span>
			<strong>Bitte Copyright-Vermerk beachten:</strong><br />".$Copyright."
			</span>
			</a></div>
			</span>";
		}
		ELSE
		{
			echo "</span>";
		}
		
		echo "
		</TD>";
		
		$result11 = mysql_query( "SELECT * FROM $table2 WHERE pic_id = '$pic_id'");
		$location = mysql_result($result11, isset($i11), 'City');
		IF($location !== 'Ortsbezeichnung' AND $location !== '')
		{
			$longitude = mysql_result($result11,isset($i11), 'GPSLongitude');
			$latitude = mysql_result($result11,isset($i11), 'GPSLatitude');
			$altitude = mysql_result($result11,isset($i11), 'GPSAltitude');
			echo "<TD id='detail6'><span style='cursor:pointer;'>
			<img src='$inst_path/pic2base/bin/share/images/googlemap.gif' width='30' height='15' border='0' alt='GoogleMap' title='Aufnahmestandort in GoogleMaps darstellen' OnClick=\"showMap($latitude, $longitude)\"/>
			</span>";
		}
		ELSE
		{
			$longitude2 = mysql_result($result11,isset($i11), 'GPSLongitude');
			$latitude2 = mysql_result($result11,isset($i11), 'GPSLatitude');
			$altitude2 = mysql_result($result11,isset($i11), 'GPSAltitude');
			IF(($longitude2 == "0") OR ($latitude2 == "0") OR ($longitude2 =='') OR ($latitude2 == ''))
			{
				echo "<TD id='detail6'><span style='cursor:pointer;'>
				<img src='$inst_path/pic2base/bin/share/images/no_googlemap.gif' width='30' height='15' border='0' alt='GoogleMap' title='keine Geo-Informationen' />
				</span>";
			}
			ELSE
			{
				echo "<TD id='detail6'><span style='cursor:pointer;'>
				<img src='$inst_path/pic2base/bin/share/images/googlemap.gif' width='30' height='15' border='0' alt='GoogleMap' title='Aufnahmestandort in GoogleMaps darstellen' OnClick=\"showMap($latitude2, $longitude2)\"/>
				</span>";
			}
		}
		
		echo "
		</TD>";
		$symb1 = "<BR>";
		$symb5 = "<BR>";
		
		//IF($Owner == $c_username AND (hasPermission($c_username, 'adminlogin') OR hasPermission($c_username, 'editpic')))
		IF($Owner == $c_username AND (hasPermission($c_username, 'georefmypics')) OR ($Owner !== $c_username AND (hasPermission($c_username, 'georefallpics'))))
		{
			$symb4 = "<SPAN style='cursor:pointer;'>
			<img src=\"$inst_path/pic2base/bin/share/images/del_geo_ref.gif\" width=\"15\" height=\"15\" hspace=\"0\" vspace=\"0\" title=\"Geo-Referenzierung &auml;ndern\" onClick=\"changeGeoParam('$FileName','$c_username','$pic_id')\" />
			</SPAN>";
		}
		ELSE
		{
			$symb4 = "<SPAN style='cursor:pointer;'>
			<img src=\"$inst_path/pic2base/bin/share/images/no_del_geo_ref.gif\" width=\"15\" height=\"15\" hspace=\"0\" vspace=\"0\" title='keine Berechtigung' />
			</span>";
		}
		
		//Bild kann nachtraeglich mit neuen Parametern eingelesen werden, wenn es sich um ein RAW-Format handelt:
		//welche RAW-Formate werden unterstuetzt?
		$supp_rawformats = array_diff($supported_filetypes, $supported_extensions);
		//print_r($supp_rawformats);
		$ext = strtolower(substr($FileNameOri,-3,3));
		IF(($Owner == $c_username AND (hasPermission($c_username, 'editmypics')) 
		OR ($Owner !== $c_username AND (hasPermission($c_username, 'editallpics')))) 
		AND in_array($ext, $supp_rawformats))
		{
			$symb3 = "<SPAN style='cursor:pointer;'>
			<img src=\"$inst_path/pic2base/bin/share/images/reload.png\" width=\"15\" height=\"15\" hspace=\"0\" vspace=\"0\" title=\"Vorschaubilder mit neuen Parametern einlesen\" onClick=\"reloadPreviews('$pic_id', '$c_username')\" />
			</SPAN>";
		}
		ELSE
		{
			$symb3 = "<SPAN style='cursor:pointer;'>
			<img src=\"$inst_path/pic2base/bin/share/images/no_reload.gif\" width=\"15\" height=\"15\" hspace=\"0\" vspace=\"0\" title=\"kein RAW-Format!\" />
			</SPAN>";
		}
		
		//wenn der User Bilder loeschen darf, wird das Trash-Icon angezeigt:
		IF($Owner == $c_username AND (hasPermission($c_username, 'deletemypics')) OR ($Owner !== $c_username AND (hasPermission($c_username, 'deleteallpics'))))
		{
			$symb2 = "<A HREF = '#' onClick=\"showDelWarning('$FileName', '$c_username', '$pic_id')\";><img src='$inst_path/pic2base/bin/share/images/trash.gif' style='width:15px; height:15px; border:none;' title=\"Bild aus dem Archiv l&ouml;schen\" /></A>";
		}
		ELSE
		{
			$symb2 = "<SPAN style='cursor:pointer;'>
			<img src='$inst_path/pic2base/bin/share/images/notrash.gif' style='width:15px; height:15px; border:none;' title='keine Berechtigung' />
			</span>";
		}
		
		echo "
		<TD id='detailoro1'>".$symb1."</TD>
		<TD id='detailorlo1'>".$symb2."</TD>
		<TD id='detailorlo1'>".$symb3."</TD>
		<TD id='detailorlo1'>".$symb4."</TD>
		<TD id='detailolo1'>".$symb5."</TD>
		</TR>
		
		<TR id='detail2'>
		<TD id='detail4' colspan='8' bgcolor='#bdbec6' height=5px></TD>
		</TR>";

		echo "
		<TR id='detail2'>
		<TD id='detail4' colspan='8' height=70px valign=top><b>zugewiesene Kategorien:</b><BR>".$kat_info."</TD>
		</TR>
		
		<TR id='detail2'>
		<TD id='detail4' colspan='8' bgcolor='#bdbec6' height=5px></TD>
		</TR>
		
		<TR id='detail2'>
		<TD id='detail4' colspan='8'><b>Bildbeschreibung:</b><BR>
		<div id='description'>
			<textarea name='description' wordwrap style='width:380px; height:105px; background-color:#DFEFFf; font-size:9pt; font-family:Helvitica,Arial;'>".$Description."</textarea>
		</div>
		</TD>
		</TR>
		
		</TABLE>";
		IF($Owner == $c_username AND hasPermission($c_username, 'editmypics')
		OR $Owner !== $c_username AND hasPermission($c_username, 'editallpics'))
		{
			//saveChanges ist in ajax_functions.php:
			echo "<CENTER><input type=button value=\"&Auml;nderungen speichern\" OnClick='saveChanges(\"$pic_id\", encodeURIComponent(beschr.description.value), beschr.aufn_dat.value);'></CENTER>";
		}
		ELSE
		{
			echo "<span style='color:grey; font-size:10px;'><center>Sie haben keine Berechtigung, die Bildbeschreibung zu &auml;ndern.</center></span>";
		}
		echo "
		</FORM>
		<input type='hidden' name='PIC_id' value='$pic_id'>";
		
	}
	ELSE
	{
		echo "<p class='zwoelf' style='background-color:white; padding: 5px; margin-top: 4px; margin-left: 0px; text-align:center; color:red;'>Das ausgew&auml;hlte Bild befindet sich nicht mehr in der Datenbank!<BR><BR>
		Bitte aktualisieren Sie die Browser-Ansicht, bevor Sie fortfahren.<BR><BR>
		<input type='button' value='Browser-Ansicht aktualisieren' onClick='location.reload()'></P>";
	}
}
?>
</body></html>
