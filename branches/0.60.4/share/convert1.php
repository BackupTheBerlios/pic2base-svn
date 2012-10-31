<?php
IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
  	header('Location: ../../index.php');
}

include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/ajax_functions.php';

// #####     Uebernahme der Beschreibungstexte     ######################################################################
/*
echo "Daten-&Uuml;bernahme-Routine<BR><BR>";
echo "&Uuml;bernahme der pictures.Description nach exif_data.Caption_Abstract, wenn in exif_data keine Beschreibung vorhanden ist, in pictures aber doch.<BR>
Mit Aktualisierung der Meta-Daten des betreffenden Bildes.<BR>
Caption-Abstract<BR>
Longitude<BR>
Latitude<BR>
Altitude<BR>
Keywords<BR>
Ort<BR><BR>";

//Kontroll-Abfragen:
$result1 = mysql_query( "SELECT * FROM $table14 WHERE Caption_Abstract IS NULL OR Caption_Abstract = ''");
$num1 = mysql_num_rows($result1);
echo "Betroffene Datens&auml;tze: ".$num1."<BR><BR>";
FOR($i1='0'; $i1<$num1; $i1++)
{
	echo "Durchlauf ".$i1."zur Daten&uuml;bernahme Beschreibungstext.<BR>";
	$pic_id = mysql_result($result1,$i1, 'pic_id');	
	$FN = $pic_path."/".restoreOriFilename($pic_id, $sr);
	$CA = mysql_result($result1,$i1, 'Caption_Abstract');
	$result2 = mysql_query( "SELECT * FROM $table2 WHERE pic_id = '$pic_id'");
	$num2 = mysql_num_rows($result2);
	
	$desc = str_replace('; ;', '', mysql_result($result2, $i2, 'Description'));
	$loc_id = mysql_result($result2, $i2, 'loc_id');
	
	IF($desc !== '' AND $desc !== NULL)
	{
		echo "Bild ".$pic_id.": Beschreibung in exif_data: ".$CA.", Beschreibung in pictures: ".$desc."<BR>";
		$result3 = mysql_query( "UPDATE $table14 SET Caption_Abstract = '$desc' WHERE pic_id = '$pic_id'");
		echo mysql_error();
		
		$desc = substr($desc,'0','1990');
		$desc = htmlentities($desc);
		shell_exec($et_path."/exiftool -IPTC:Caption-Abstract='$desc' ".$FN." -overwrite_original");
	}
	ELSE
	{
		//echo "KEIN EINTRAG VORHANDEN!<BR>";
	}
	
	//Ermittlung und Eintragung der Geo-Parameter:
	IF($loc_id !== '0')
	{
		$result4 = mysql_query( "SELECT * FROM $table12 WHERE loc_id = '$loc_id'");
		$long = mysql_result($result4, $i4, 'longitude');
		$lat = mysql_result($result4, $i4, 'latitude');
		$alt = mysql_result($result4, $i4, 'altitude');
		$ort = mysql_result($result4, $i4, 'location');
		IF(strlen($ort) > '18')
		{
			$ort_iptc = utf8_encode(substr($ort,0,15))."...";
		}
		ELSE
		{
			$ort_iptc = utf8_encode($ort);
		}
		shell_exec($et_path."/exiftool -IPTC:city='$ort_iptc' ".$FN." -overwrite_original -execute -EXIF:GPSLongitude=".$long." ".$FN." -overwrite_original -execute -EXIF:GPSLatitude=".$lat." ".$FN." -overwrite_original -execute -EXIF:GPSAltitude=".$alt." ".$FN." -overwrite_original");
		echo "Geo-Koordinaten ins Bild ".$pic_id." geschrieben.<BR>";
	}
	
	//Ermittlung und Eintragung der Keywords:
	$result9 = mysql_query( "SELECT DISTINCT $table10.pic_id, $table10.kat_id, $table4.kat_id, $table4.kategorie, $table4.level FROM $table10 INNER JOIN $table4 ON ($table10.kat_id = $table4.kat_id AND $table10.pic_id = '$pic_id') ORDER BY $table4.level");
	$num9 = mysql_num_rows($result9);
		
	$kat_info='';
	FOR ($i9=1; $i9<$num9; $i9++)	//Als Start wurde "1" gew�hlt, da die Wurzel uninteressant ist!
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
	echo "Bild: ".$pic_id.", Kategorien: ".$kat_info."<BR>";
	IF ($kat_info !== '')
	{
		$kategorie = htmlentities($kat_info);
		shell_exec($et_path."/exiftool -IPTC:Keywords='$kategorie' -overwrite_original ".$FN);
	}
}

*/
//  ######################################################################################################################
/*
//  #####   Vervollst�ndigung der Bildgr��enangaben (ImageDataSize, ExifImageWidth, ExifImageHeight, Orientation)   ######
$result5 = mysql_query( "SELECT * FROM $table14 WHERE ImageDataSize = '0'");
$num5 = mysql_num_rows($result5);
echo $num5." betroffene Datens&auml;tze, bei denen keine Dateiattribute in der exif_data hinterlegt sind.<BR>";
FOR($i5='0'; $i5<$num5; $i5++)
{
	echo "Durchlauf ".$i5."zur Daten&uuml;bernahme Geodaten.<BR>";
	clearstatcache;
	$pic_id = mysql_result($result5, $i5, 'pic_id');
	$orientation = mysql_result($result5, $i5, 'Orientation');
	$result8 = mysql_query( "SELECT * FROM $table2 WHERE pic_id = '$pic_id'");
	$FileName = mysql_result($result8, $i8, 'FileName');
	$FN = $pic_path."/".restoreOriFilename($pic_id, $sr);
	$filesize = filesize($FN);
	$fn = $pic_path."/".$FileName;
	//echo $fn."<BR>";
	$file_info = getimagesize($fn);
	//print_r($file_info);
	$width = $file_info[0];
	$height = $file_info[1];
	
	IF($orientation == '' OR $orientation == '0')
	{
		IF($width > $height)
		{
			$orientation = '1';
		}
		ELSE
		{
			$orientation = '8';
		}
		$result6 = mysql_query( "UPDATE $table14 SET Orientation = '$orientation' WHERE pic_id = '$pic_id'");
		echo mysql_error();
	}
	
	echo "Bild ".$pic_id.": Breite: ".$width.", H&ouml;he: ".$height.", Ausrichtung: ".$orientation.", Dateigr&ouml;&szlig;e: ".$filesize."<BR>";
	$result7 = mysql_query( "UPDATE $table14 SET ImageDataSize = '$filesize', ExifImageWidth = '$width', ExifImageHeight = '$height' WHERE pic_id = '$pic_id'");
	echo mysql_error();
}
*/
//  ######################################################################################################################
//Korrektur der Orientation f�r hochformatige RAW-Bilder:
/*
$result1 = mysql_query( "SELECT $table2.pic_id, $table2.FileNameOri, $table14.pic_id, $table14.Orientation FROM $table2, $table14 WHERE $table2.pic_id = $table14.pic_id AND $table2.FileNameOri LIKE '%.nef' AND $table14.Orientation <> '1'");
$num1 = mysql_num_rows($result1);
echo $num1." Bilder sind betroffen<BR>";
For($i1='0'; $i1<$num1; $i1++)
{
	$pic_id = mysql_result($result1, $i1, 'pic_id');
	$FNO = mysql_result($result1, $i1, 'FileNameOri');
	$ori = mysql_result($result1, $i1, 'Orientation');
	echo $i1." - ".$pic_id." - ".$FNO." - ".$ori."<BR>";
	$result2 = mysql_query( "UPDATE $table14 SET Orientation = '1' WHERE pic_id = '$pic_id'");
	echo mysql_error();
}
*/
//  ######################################################################################################################
//Ermittlung aller unterst�tzten RAW-Formate:
/*
$arr_raw = array();
$arr_raw = array_diff($supported_filetypes,$supported_extensions);
echo "Die folgenden RAW-Formate werden von pic2base unterst&uuml;tzt:<BR>";
FOREACH($arr_raw AS $AR)
{
	echo $AR."<BR>";
}

$ext = 'nef';
IF(in_array($ext,$arr_raw))
{
	echo $ext." ist im Array enthalten.";
}
*/
//  ######################################################################################################################
//�bernahme der Daten von ImageDataSize nach FileSize
/*
$result1 = mysql_query( "UPDATE $table14 SET FileSize = ImageDataSize0 WHERE ImageDataSize0 <> '0' AND FileSize = '0'");
echo mysql_error();
*/
//  ######################################################################################################################
//Tabelle exif_protect in meta_protect umbenennen:
//Sicherheitskopie der exif_protect anlegen:
/*
$result0 = mysql_query( "CREATE  TABLE  `exif_protect_ori` (  `lfdnr` int( 11  )  NOT  NULL  auto_increment ,
 `field_name` varchar( 50  )  NOT  NULL ,
 `writable` tinyint( 1  )  NOT  NULL default  '0',
 PRIMARY  KEY (  `lfdnr`  )  ) ENGINE  =  MyISAM  DEFAULT CHARSET  = latin1 COMMENT  =  'Sperr-Tabelle fuer EXIF-Daten';

INSERT INTO `exif_protect_ori` SELECT * FROM `exif_protect`");
//dann bisherige Tabelle umbenennen:
$result1 = mysql_query( "RENAME TABLE `exif_protect`  TO `meta_protect`");
echo mysql_error();
*/
//  ######################################################################################################################
//Testroutine zur Laufzeitoptimierung der Metadaten-Ermittlung und Speicherung
//Beispielbild:
/*
$bild = "/opt/lampp/htdocs/events/admin/pic2base/images/originale/13442.nef";
$start1 = microtime();
$result8 = mysql_query( "SHOW COLUMNS FROM $table14");
$i = 0;
if($result8 != false)
{
	while($liste = mysql_fetch_row($result8))
	{
		$tab_fieldname[$i] = str_replace('_','-',$liste[0]);	//vorh. Tabellen-Feldname
		$i++;
	}
}

$text = shell_exec($et_path."/exiftool ".$bild);
//echo $text;
$inf_arr = explode(chr(10), $text);
echo count($inf_arr)." Meta-Angaben im Bild<BR><BR>";;

FOREACH($inf_arr AS $IA)
{
	//echo $IA."<BR>";
	$F_W = explode(' : ', $IA);
	$fieldname = $F_W[0];
	//'Bereinigung des Feldnamen:
	$fieldname = str_replace(" ","",$fieldname);
	$fieldname = str_replace("/","",$fieldname);
	$fieldname = str_replace("-","_",$fieldname);
	$value = trim($F_W[1]);
	
	IF(in_array($fieldname,$tab_fieldname))
	{
		//$value = formatValues($fieldname,$bild,$et_path);
		IF($fieldname == 'DateTimeOriginal')
		{
			$tmp_value = explode(" ",$value);
			$value = str_replace(':','-',$tmp_value[0])." ".$tmp_value[1];
		}
		echo ">>> Feld ".$fieldname." hat den Wert ".$value."<BR>";
		//Daten in DB schreiben! ############################################################
		
		//Bildbreite- und H�he werden zur Sicherheit in 2 Felder (ExifImageHeight (Width) UND ImageHeight (WIdth)) geschrieben:
		IF(($fieldname == 'ExifImageHeight' OR $fieldname == 'ImageHeight') AND ($value !== '0' AND $value !== ''))
		{
			$result4 = mysql_query( "UPDATE $table14 SET ExifImageHeight = '$value', ImageHeight = '$value' WHERE pic_id = '$pic_id'");
		}
		ELSEIF(($fieldname == 'ExifImageWidth' OR $fieldname == 'ImageWidth') AND ($value !== '0' AND $value !== ''))
		{
			$result4 = mysql_query( "UPDATE $table14 SET ExifImageWidth = '$value', ImageWidth = '$value' WHERE pic_id = '$pic_id'");
		}
		ELSEIF(($fieldname == 'ExifImageHeight' OR $fieldname == 'ImageHeight' OR $fieldname == 'ExifImageWidth' OR $fieldname == 'ImageWidth') AND ($value == '0' OR $value == ''))
		{
			//keine Aktualisierung!
		}
		ELSE
		{
			$result4 = mysql_query( "UPDATE $table14 SET $fieldname = '$value' WHERE pic_id = '$pic_id'");
		}
		IF(mysql_error() !== '')
		{
			echo "Fehler beim speichern der Meta-Daten: ".mysql_error()."<BR>~~~~~~~~~~~~~~~~~~~~~~~~~~~<BR>";
		}
		
	}	
}
$end1 = microtime();
list($start1msec, $start1sec) = explode(" ",$start1);
list($end1msec, $end1sec) = explode(" ",$end1);
$runtime1 = ($end1sec + $end1msec) - ($start1sec + $start1msec);
echo "Zeit f&uuml;r Metadatenbestimmung: ".$runtime1."Sek.<BR>";
*/
//  ######################################################################################################################
//Routine zum Neueinlesen der FileSize in die Meta-Daten-Tabelle
/*
$result1 = mysql_query( "SELECT pic_id FROM $table14 ORDER BY pic_id");
$num1 = mysql_num_rows($result1);
FOR($i1='0'; $i1<$num1; $i1++)
{
	$FileSize_arr = array();
	$pic_id = mysql_result($result1, $i1, 'pic_id');
	$FN = $pic_path."/".restoreOriFilename($pic_id, $sr);
	//echo $FN." - ";
	$FileSize_arr = explode(" : ",shell_exec($et_path."/exiftool -FileSize -n ".$FN));
	$FileSize = $FileSize_arr[1];
	IF($FileSize == NULL)
	{
		$FileSize = filesize($FN);
	}
	echo "Datensatz ".$i1." von ".$num1.": ".$pic_id." - ".$FileSize."<BR>";
	
	$result2 = mysql_query( "UPDATE $table14 SET FileSize = '$FileSize' WHERE pic_id = '$pic_id'");
	echo mysql_error();
}
*/

//  ######################################################################################################################
//Routine zur Bestimmung der Bildanzahl nach Aufnahmedatum:
/*
$result1 = mysql_query( "SELECT DISTINCT YEAR(DateTimeOriginal) AS DTO FROM $table14 ORDER BY YEAR(DateTimeOriginal)");
$result2 = mysql_query( "SELECT MAX(YEAR(DateTimeOriginal)) AS MAX_DTO, MIN(YEAR(DateTimeOriginal)) AS MIN_DTO FROM $table14");
echo mysql_error();
$num1 = mysql_num_rows($result1);
//$anz = mysql_result($result1, $i1, 'count');
//echo $anz."<BR>";
$MIN_DTO = mysql_result($result2, $i2, 'MIN_DTO');
$MAX_DTO = mysql_result($result2, $i2, 'MAX_DTO');
echo "Fr&uuml;hestes Jahr: ".$MIN_DTO.", sp&auml;testes Jahr: ".$MAX_DTO."<BR>";
FOR($i1='0'; $i1<$num1; $i1++)
{
	$DTO = mysql_result($result1, $i1, 'DTO');
	echo $i1." - ".$DTO."<BR>";
}
*/

//  ######################################################################################################################
// Routine zur �bertragung der vorhandenen kat_id's von der kategorie-Tabelle zur kat_lex-Tabelle:

$result1 = mysql_query( "SELECT kat_id FROM $table4 ORDER BY 'kat_id'");
$num1 = mysql_num_rows($result1);
FOR($i1='0'; $i1<$num1; $i1++)
{
	$kat_id = mysql_result($result1, $i1, 'kat_id');
	$res2 = mysql_query( "INSERT INTO $table11 (kat_id, info) VALUES ('$kat_id', '')");
	echo "Kategorie ".$kat_id." wurde &uuml;bertragen<BR>";
}

?>