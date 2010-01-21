<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-1">
	<TITLE>pic2base - Stapel-Upload</TITLE>
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
 * File: stapel1.php
 *
 * Copyright (c) 2003 - 2009 Klaus Henneberg
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
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

//log-file schreiben:
$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
fwrite($fh,date('d.m.Y H:i:s')." ".isset($REMOTE_ADDR)." ".$_SERVER['PHP_SELF']." ".$_SERVER['HTTP_USER_AGENT']." ".$c_username."\n");
fclose($fh);

//var_dump($_POST);
if (array_key_exists('ordner',$_POST))
{
	$ordner = $_POST['ordner']; // f�r register_globals = off
}
if (array_key_exists('ordner',$_GET))
{
	$ordner = $_GET['ordner']; // f�r register_globals = off
}

$x = 0;
$n = 0;				//Z&auml;hlvariable f&uuml;r die zu bearbeitenden Bilder (Bilder im Upload-Ordner)
$del = 0;			//Z&auml;hlvariable f&uuml;r die nach dem Upload aus dem Upload-Ordner gel&ouml;schten Bilder
$verz=opendir($ordner);		//$ordner: Upload-Ordner des angemeldeten Users (wird von start.php geliefert)
$hinweis = '';
//Ermittlung, wieviel Bilddateien sich in dem angegebenen Ordner befinden und Abspeicherung der Dateinamen in einem Array:
$bild_datei = array();
while($datei_name=readdir($verz))
{
	if($datei_name != "" && $datei_name != "." && $datei_name != "..")
	{
		$info = pathinfo($datei_name);
		$extension = strtolower($info['extension']);
		IF(in_array($extension,$supported_filetypes) OR $extension == 'jpg')
		{
			$bild_datei[] = $datei_name;
			$n++;
		}
		ELSE
		{
			$hinweis .= $datei_name." besitzt kein g&uuml;ltiges Bild-Dateiformat!<BR>";
		}
	}
}

$meldung = "Bitte haben Sie ein wenig Geduld...";
echo "
<div class='page'>
	<p id='kopf'>pic2base :: Stapelverarbeitung Bild-Upload <span class='klein'>(User: ".$c_username.")</span></p>
		
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>
		</div>
	</div>
		
	<div class='content'>
		<span style='font-size:12px;'>
		<p style='margin:120px 0px; text-align:center'>
		
		<center>
			Status der Bild-Erfassung
			<div id='prog_bar' style='border:solid; border-color:red; width:500px; height:12px; margin-top:50px; text-align:left; vertical-align:middle'>
				<img src='../../share/images/green.gif' name='bar' />
			</div>
			<p id='zaehler'>Anzahl der hochzuladenden Bild-Dateien: ".$n."</p>
			<p id='meldung'>".$meldung."</p>
		</center>
		
		</p>
		</span>
	</div>
	<br style='clear:both;' />
	<p id='fuss'><A style='margin-right:745px;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>".$cr."</p>
</div>";

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

// $n - Anzahl der Bilddateien;
// $x - Nummer der aktuell hochgeladenen Datei

/*
pic2base - Bild-Erfassung    +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

Bild erfassen

ermitteln, wieviel Bilder im Upload-Ordner des Users liegen

f&uuml;r jedes Bild:
Ausrichtung des Originalbildes (dsc_123.nef) ermitteln
Dummy-Datensatz mit Original-Dateinamen und User-ID  anlegen
pic_id ermitteln
Bild unter neuem Namen im Originalformat speichern (dsc_123.nef ? 12345.nef)
Kontrolle, ob Upload erfolgreich war
pr&uuml;fen, ob hochgeladenes Bild als jpg vorliegt
wenn nicht: jpg-File erzeugen (neben dem 12345.nef wird das 12345.jpg angelegt; Dieses Bild wird f&uuml;r alle weiteren Verarbeitungen innerhalb von pic2base verwendet!)

Wenn Ausrichtung nicht 'Horizontal' ist: lagerichtige Kopie des 12345.jpg im Ordner /rotated ablegen

Pr&uuml;fsumme / Vorschaubilder / Histogramme erzeugen

Pr&uuml;fsumme aus dem Original-(jpg)Bild erstellen
HQ-Vorschaubild aus (rotiertem) 12345.jpg erstellen
V-Vorschaubild aus (rotiertem) 12345.jpg erstellen
Histogramme aus 12345.jpg erstellen

Bild-Eigenschaften ermitteln (Meta-Daten auslesen)

mittels exiftool alle verf&uuml;gbaren Metadaten auslesen und, wenn f&uuml;r die einzelnen Parameter Felder in der Tabelle meta_data existieren, die ermittelten Werte dort speichern
Kontrolle, ob mindestens die Parameter Width, Height und ImageSize ausgelesen wurden. Wenn nicht, diese Parameter mit PHP-Routinen ermitteln und in der DB speichern
Die Ausrichtung wird intern immer mit '1' verwendet.
Formatvorgaben der Popup-Vorschaufenster werden aus den tats�chlichen Bild-Abmessungen mittel getimagesize() ermittelt!

Erfassung eines Bildes abgeschlossen    ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/
//$start1 = microtime();					//Startzeit-Variable zur Laufzeitermittlung
flush();
FOR ($x='0';$x<$n;$x++)
{
//  +++  Datei-Upload  +++
	$X = $x + 1;
	$datei_name = $bild_datei[$x];
	$bild = $ordner."/".$datei_name;
	
	$Ori_arr = preg_split('# : #',shell_exec($et_path."/exiftool -Orientation -n ".$bild)); //num. Wert der Ausrichtung des Ori.-bildes
	
	if (count($Ori_arr) > 1 )
	{
		$Orientation = $Ori_arr[1];
	}
	else
	{
		$Orientation = '';	
	}
	//echo "...bearbeite Bild ".$bild."<BR>Ausrichtung: ".$Orientation."<BR>";
	IF($Orientation == '')
	{
		$Orientation = '1';
	}
	
//  +++  Vergabe eines eindeutigen Dateinamens  +++++
	/*Zur eindeutigen Identifizierung der Bilddateien wird seit dem 12.06.2008 die Datensatz-Nr. des jeweiligen Bildes verwendet. Hierzu wird vor dem eigentlichen Upload ein Dummy-Datensatz angelegt und die Datensatz-Nr. ermittelt:*/
	$result1 = mysql_query( "SELECT id FROM $table1 WHERE username = '$c_username' AND aktiv = '1'");echo mysql_error();
	$user_id = mysql_result($result1, isset($i), 'id');
	$DateInsert = date('Y-m-d H:i:s');
	$result2 = mysql_query( "INSERT INTO $table2 (Owner,FileNameOri,DateInsert) VALUES ('$user_id', '$datei_name', '$DateInsert')");
	echo mysql_error();
	$pic_id = mysql_insert_id();			//echo "User-ID: ".$user_id."; Rec-ID: ".$pic_id."<BR>";
	$info2 = pathinfo($datei_name);
	$tmp_filename = $pic_id.".".strtolower($info2['extension']);		//Dateiname z.B.: 112233.nef
	//echo "tempor&auml;rer Dateiname: ".$tmp_filename."<BR>";
	
	//  Kontrolle, ob Upload erfolgreich war  +++++
	@copy("$bild","$path_copy/$tmp_filename")	// Bild wird nach (z.B.) ....images/originale/12345.nef kopiert
	or die("Upload fehlgeschlagen!");
	$tmp_file = $sr."/images/originale/".$tmp_filename;
	clearstatcache();  
	chmod ($tmp_file, 0700);
	clearstatcache();	
//  +++  Egal, was reinkommt: alle Bilder werden in JPEG gewandelt, um bei der Ausgabe die Meta-Daten mitliefern zu k&ouml;nnen:
	$file_info = pathinfo($tmp_filename);
	//Pr&uuml;fung auf unterst&uuml;tzte Datei-Formate:
	IF($file_info['extension'] !== 'jpg')
	{
		$base_name = $file_info['basename'];
		$ext = strtolower($file_info['extension']);		//echo "Ext: ".$ext."<BR>";
		$new_filename = str_replace($ext,'',$base_name)."jpg";	//z.B: 122344.jpg
		
		//RAW-Dateien m&uuml;ssen mit dcraw gesondert behandelt werden!!:
		//welche Dateitypen sind in supported_filetypes und nicht in supported_extensions?
		$arr_raw = array();
		$arr_raw = array_diff($supported_filetypes, $supported_extensions);
		IF(in_array($ext,$arr_raw))
		{
			//echo "aus RAW-Bildern werden JPGs erzeugt<BR>";
			$command = $dcraw_path."/dcraw -w -c ".$pic_path."/".$tmp_filename." | convert - ".$pic_path."/".$new_filename."";
			$output = shell_exec($command);
			//das Original(jpg-)Bild wird ggf als rotierte Kopie abgelegt
			IF($Orientation == 3 OR $Orientation == 6 OR $Orientation == 8)
			{
				IF($ext == 'nef')
				{
					copy("$pic_path/$new_filename", "$pic_rot_path/$new_filename");
					clearstatcache();
					chmod ($pic_rot_path."/".$new_filename, 0700);
					clearstatcache();
				}
				ELSE
				{
					$rot_filename = createQuickPreview($Orientation,$new_filename);
				}
			}
			$result4 = mysql_query( "UPDATE $table2 SET FileName = '$new_filename' WHERE pic_id = '$pic_id'");
			$z = '1';
		}
		//alle anderen Dateien, die weder RAW noch jpg sind werden hier behandelt:
		ELSEIF(in_array($ext,$supported_extensions)) 
		{
			//das oder die jpg-Bilder werden erzeugt:
			$command = $im_path."/convert ".$pic_path."/".$tmp_filename." ".$pic_path."/".$new_filename."";
			$output = shell_exec($command);
			IF(file_exists($pic_path."/".$new_filename))
			{
				//das Original(jpg-)Bild wird ggf als rotierte Kopie abgelegt
				IF($Orientation == '3' OR $Orientation == '6' OR $Orientation == '8')
				{
					$rot_filename = createQuickPreview($Orientation,$new_filename);
				}
				$result4 = mysql_query( "UPDATE $table2 SET FileName = '$new_filename' WHERE pic_id = '$pic_id'");
				$z = '1';//echo "Die Datei enth&auml;lt nur EIN Bild.!";
			}
			ELSE
			{
				//es war vermutlich eine Datei mit mehreren Bildern (max. 100 Bilder werden je Datei verarbeitet!)
				$z = '0';
				WHILE($z<100)
				{
					$new_filename = str_replace('.'.$ext,'',$base_name)."-".$z.".jpg";
					IF(file_exists($pic_path."/".$new_filename))
					{
						//das Original(jpg)Bild wird ggf als rotierte Kopie abgelegt
						IF($Orientation == 3 OR $Orientation == 6 OR $Orientation == 8)
						{
							$rot_filename = createQuickPreview($Orientation,$new_filename);
						}
						$result4 = mysql_query( "UPDATE $table2 SET FileName = '$new_filename' WHERE pic_id = '$pic_id'");
						$z++;
					}
					ELSE
					{
						break; //echo "Die Datei beinhaltet ".$z." Bilder.";
					}
				}
				//Wenn eine Datei mehrere Bilder enth&auml;lt, wird nur der Datei-Rumpf als Parameter &uuml;bergeben:
				$new_filename = str_replace('.'.$ext,'',$base_name);
			}
		}
		ELSE
		{
			//nicht unterst&uuml;tzte Datei-Typen werden gel&ouml;scht und Meldung ausgegeben:
			unlink($pic_path."/".$tmp_filename);
			echo "Dateien des Typs *.".$ext." werden nicht unterst&uuml;tzt.";
			$del++;
		}
	}
	ELSE
	{
		$z = '1';
		$new_filename = $tmp_filename;	//jpg-Dateien behalten ihren eindeutigen Dateinamen
		$result4 = mysql_query( "UPDATE $table2 SET FileName = '$new_filename' WHERE pic_id = '$pic_id'");
	}
	
	//die Datei-Attribute werden f&uuml;r die hochgeladene Original-(jpg)Bilddatei auf 0700 gesetzt:
	$fileOri = $pic_path."/".$new_filename;
	clearstatcache();
	chmod ($fileOri, 0700);
	clearstatcache();
/*
$end1 = microtime();
list($start1msec, $start1sec) = explode(" ",$start1);
list($end1msec, $end1sec) = explode(" ",$end1);
$runtime1 = ($end1sec + $end1msec) - ($start1sec + $start1msec);
echo "Zeit f&uuml;r Bildupload: ".$runtime1."<BR>";
*/
	//Funktions-Parameter: Bild-ID, Anzahl der Scenen; User-ID; Ausrichtung
	savePicture($pic_id,$z,$user_id,$Orientation);	//Parameter sollten reichen, da sich alles weitere erzeugen l&auml;�t
/*
$end2 = microtime();
list($end2msec, $end2sec) = explode(" ",$end2);
$runtime2 = ($end2sec + $end2msec) - ($start1sec + $start1msec);
echo "Zeit bis Bilddatenspeicherung: ".$runtime2."<BR>";	
*/
	//Aus Preformance-Gr&uuml;nden werden die Histogramme aus den HQ-Bildern gewonnen:
	$result3 = mysql_query( "SELECT * FROM $table2 WHERE pic_id = '$pic_id'");
	$FNHQ = mysql_result($result3, isset($i3), 'FileNameHQ');
	$FileName = $FNHQ;
	//Parameter: Bild-ID, Name der HQ-Datei (z.B. 21456_hq.jpg), Server-Root
	generateHistogram($pic_id,$FileName,$sr);
/*
$end3 = microtime();
list($end3msec, $end3sec) = explode(" ",$end3);
$runtime3 = ($end3sec + $end3msec) - ($start1sec + $start1msec);
echo "Zeit bis Histogrammerstellung: ".$runtime3."<BR>";		
*/
	//Meta-Daten aus dem Bild auslesen und in die Tabelle meta_data schreiben:
	//Parameter: Bild-ID, Server-Root
	extractExifData($pic_id,$sr);
/*
$end4 = microtime();
list($end4msec, $end4sec) = explode(" ",$end4);
$runtime4 = ($end4sec + $end4msec) - ($start1sec + $start1msec);
echo "Zeit bis Meta-Daten-Auslesen: ".$runtime4."<BR>";	
*/
//  +++  l&ouml;schen der soeben in die DB aufgenommene Datei aus dem Upload-Ordner:  +++
	IF($datei_name != "." && $datei_name != "..")
	{
		$datei_name = $ftp_path."/".$c_username."/uploads/".$datei_name;
		IF(!@unlink($datei_name))
		{
			echo "Konnte die Datei $datei_name nicht l&ouml;schen!<BR>";
		}
		ELSE
		{
			$del++;
		}
	}
	//echo "gel&ouml;schte Dateien: ".$del." von ".$n."<BR>";
	//Erzeugung des Fortschrittsbalkens und Hinweistextes:
	$laenge = (round(($X / $n) * 500));
	$anteil = number_format((($X / $n)*100),2,',','.');
	$text = "...erfasse Bild ".$new_filename." (Datensatz ".$X." von ".$n.")<BR>".$anteil."%";
	?>
	
	<SCRIPT language="JavaScript">
	document.bar.src='../../share/images/green.gif';
	document.bar.width=<?echo $laenge?>;
	document.bar.height='11';
	document.getElementById('zaehler').innerHTML='<?echo $text?>';
	</SCRIPT>
	
	<?
	IF($n == $del)
	{
		$meldung = "Erfassung abgeschlossen: ". date('d.m.Y, H:i:s')."<BR>";
		$meldung .= "<input type=\"button\" VALUE = \"Fertig - zur&uuml;ck zur Startseite\" onClick=\"location.href=\'../start.php\'\">";
		//echo $meldung;
		?>
		<SCRIPT language="JavaScript">
		document.getElementById('meldung').innerHTML='<?echo $meldung?>';
		</SCRIPT>
		<?
	}
	flush();
}
mysql_close($conn);

?>
</DIV>
</CENTER>
</BODY>
</HTML>