<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
	header('Location: ../../../index.php');
}
$uid = $_COOKIE['uid'];
?>

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

<?php

/*
 * Project: pic2base
 * File: stapel1.php			#################################  ab 29.07.2012 durch stapel2.php, stapel2_action.php und show_files.php ersetzt!  ##########################################
 *
 * Copyright (c) 2003 - 2010 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 */
/*
unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
}
*/
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');

$conv = buildConvertCommand($sr);
$exiftool = buildExiftoolCommand($sr);
$dcraw = buildDcrawCommand($sr);

//log-file schreiben:
$start_time = date('d.m.Y, H:i:s');

//var_dump($_POST);
if (array_key_exists('ordner',$_POST))
{
	$ordner = $_POST['ordner'];
}
if (array_key_exists('ordner',$_GET))
{
	$ordner = $_GET['ordner'];
}

$x = 0;						//Nummer der aktuell hochgeladenen Datei
$n = 0;						//Zaehlvariable fuer die zu bearbeitenden Bilder (Bilder im Upload-Ordner)
$del = 0;					//Zaehlvariable fuer die nach dem Upload aus dem Upload-Ordner geloeschten Bilder
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
	<p id='kopf'>pic2base :: Stapelverarbeitung Bild-Upload <span class='klein'>(User: ".$username.")</span></p>
		
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
</div>

</DIV>
</CENTER>
</BODY>";

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

// $n - Anzahl der Bilddateien;
// $x - Nummer der aktuell hochgeladenen Datei

/*
pic2base - Bild-Erfassung    +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

Bild erfassen

ermitteln, wieviel Bilder im Upload-Ordner des Users liegen

fuer jedes Bild:
Ausrichtung des Originalbildes (dsc_123.nef) ermitteln
Dummy-Datensatz mit Original-Dateinamen und User-ID  anlegen
pic_id ermitteln
Bild unter neuem Namen im Originalformat speichern (dsc_123.nef ? 12345.nef)
Kontrolle, ob Upload erfolgreich war
pruefen, ob hochgeladenes Bild als jpg vorliegt
wenn nicht: jpg-File erzeugen (neben dem 12345.nef wird das 12345.jpg angelegt;
Dieses Bild wird fuer alle weiteren Verarbeitungen innerhalb von pic2base verwendet, falls die Ausrichtung = 1 ist!)

Wenn Ausrichtung nicht 'Horizontal' ist (<>1): lagerichtige Kopie des 12345.jpg im Ordner /rotated ablegen

Pruefsumme / Vorschaubilder / Histogramme erzeugen

Pruefsumme aus dem Original-(jpg)Bild erstellen
HQ-Vorschaubild aus (rotiertem) 12345.jpg erstellen
V-Vorschaubild aus (rotiertem) 12345.jpg erstellen
Histogramme aus 12345.jpg erstellen

Bild-Eigenschaften ermitteln (Meta-Daten auslesen)

mittels exiftool alle verfuegbaren Metadaten auslesen und, wenn fuer die einzelnen Parameter Felder in der Tabelle 
meta_data existieren, die ermittelten Werte dort speichern
Kontrolle, ob mindestens die Parameter Width, Height und ImageSize ausgelesen wurden. Wenn nicht, diese Parameter 
mit PHP-Routinen ermitteln und in der DB speichern
Bringt ein Bild Geo-Daten mit, werden diese in der location-Tabelle hinterlegt
Bringt ein Bild keine Ausrichtungsinformationen mit (Orientation = ' '): Ausrichtung wird mit '1' festgelegt.
Formatvorgaben der Popup-Vorschaufenster werden aus den tatsaechlichen Bild-Abmessungen 
 - OHNE Beruecksichtigung der Ausrichtung - mittels getimagesize() ermittelt!
Die Ausrichtung (Orientation) wird spaeter nur noch verwendet, um zu entscheiden, ob bei der Anzeige eines Originals
ggf. die rotierte Kopie verwendet wird.

Erfassung eines Bildes abgeschlossen    ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/
$start = microtime();					//Startzeitvariable zur Ermittlung der Gesamtzeit
$start1 = microtime();					//Startzeit-Variable zur Laufzeitermittlung
$doublette = 0;							//Initialisierung der Doubletten-Kontrolle
ob_start();

FOR ($x='0';$x<$n;$x++)
{
//  +++  Datei-Upload  +++
	$X = $x + 1;						//$X - Zaehlvariable fuer den Fortschrittsbalken
	
	//Errechnung der Parameter fuer den Fortschrittsbalken:
	
	// Bei neueren Browsern muessen mind. 256 Byte uebertragen werden, damit der Inhalt des OutputBuffers geliefert wird
	// und der Fortschrittsbalken korrekt aktualisiert wird.
	// Also wird ein wenig Muell erzeugt...
	//#################################################################################################################
	FOR($z=0; $z<300; $z++)
	{
		echo "                                                                                                              ";
	}
	usleep(10000);
	ob_flush();
	//######################################  genug Muell erzeugt  ####################################################
	
	$laenge = (round(($X / $n) * 500));
	$anteil = number_format((($X / $n)*100),2,',','.');
	$text = "...erfasse Datensatz ".$X." von ".$n."<BR>".$anteil."%";?>
	
	<SCRIPT language="JavaScript">
	document.bar.src='../../share/images/green.gif';
	document.bar.width=<?php echo $laenge?>;
	document.bar.height='11';
	document.getElementById('zaehler').innerHTML='<?php echo $text?>';
	</SCRIPT>
	
	<?php
	$datei_name = $bild_datei[$x];
	$bild = $ordner."/".$datei_name;
	//echo "... bearbeite ".$bild." (".$x.")<BR>";//#############################################################################################################################################
	$Ori_arr = preg_split('# : #',shell_exec($exiftool." -Orientation -n ".$bild)); //num. Wert der Ausrichtung des Ori.-bildes
	
	if (count($Ori_arr) > 1 )
	{
		$Orientation = $Ori_arr[1];
	}
	else
	{
		$Orientation = '';	
	}
	
	IF($Orientation == '')
	{
		$Orientation = '1';
	}
	$Orientation = trim($Orientation);
	
//  +++  Vergabe eines eindeutigen Dateinamens  +++++
	/*Zur eindeutigen Identifizierung der Bilddateien wird seit dem 12.06.2008 die Datensatz-Nr. des jeweiligen Bildes verwendet. 
	 * Hierzu wird vor dem eigentlichen Upload ein Dummy-Datensatz angelegt und die Datensatz-Nr. ermittelt:*/
//	$result1 = mysql_query( "SELECT id FROM $table1 WHERE username = '$c_username' AND aktiv = '1'");echo mysql_error();
//	$user_id = mysql_result($result1, isset($i), 'id');
	$user_id = $uid;
	$DateInsert = date('Y-m-d H:i:s');
	$result2 = mysql_query( "INSERT INTO $table2 (Owner,FileNameOri,DateInsert) VALUES ('$user_id', '$datei_name', '$DateInsert')");
	echo mysql_error();
	$pic_id = mysql_insert_id();									//echo "User-ID: ".$user_id."; Rec-ID: ".$pic_id."<BR>";
	$info2 = pathinfo($datei_name);
	$tmp_filename = $pic_id.".".strtolower($info2['extension']);	//Dateiname z.B.: 112233.nef
	
	//  Kontrolle, ob Upload erfolgreich war  +++++
	@copy("$bild","$pic_path/$tmp_filename")						// Bild wird nach (z.B.) ....images/originale/12345.nef kopiert
	or die("Upload fehlgeschlagen!");
	$tmp_file = $sr."/images/originale/".$tmp_filename;
	clearstatcache();  
	chmod ($tmp_file, 0700);
	clearstatcache();	
//  +++  Egal, was reinkommt: alle Bilder werden in JPEG gewandelt, um bei der Ausgabe die Meta-Daten mitliefern zu koennen:
	$file_info = pathinfo($tmp_filename);
	//Pruefung auf unterstuetzte Datei-Formate:
	IF($file_info['extension'] !== 'jpg')
	{
		$base_name = $file_info['basename'];
		$ext = strtolower($file_info['extension']);		//echo "Ext: ".$ext."<BR>";
		$new_filename = str_replace($ext,'',$base_name)."jpg";	//z.B: 122344.jpg
		//RAW-Dateien muessen mit dcraw gesondert behandelt werden!!:
		//welche Dateitypen sind in supported_filetypes und nicht in supported_extensions?
		$arr_raw = array();
		$arr_raw = array_diff($supported_filetypes, $supported_extensions);
		IF(in_array($ext,$arr_raw))
		{
			//echo "aus RAW-Bildern werden JPGs erzeugt<BR>";
			$command = $dcraw." -w -c ".$pic_path."/".$tmp_filename." | ".$conv." - ".$pic_path."/".$new_filename."";
			$output = shell_exec($command);
			//das Original(jpg-)Bild wird ggf als rotierte Kopie abgelegt
			IF($Orientation == '3' OR $Orientation == '6' OR $Orientation == '8')
			{
				$command2 = $exiftool." -Quality ".$pic_path."/".$tmp_filename;
				$output2 = shell_exec($command2);
				$output2_array = explode(' : ', $output2);
				IF(trim($output2_array[0]) == 'Quality' AND strtolower(trim($output2_array[1])) == 'raw')
				{
					//aus raw erzeugte jpg sind bereits lagerichtig:
					copy("$pic_path/$new_filename", "$pic_rot_path/$new_filename");
					clearstatcache();
					chmod ($pic_rot_path."/".$new_filename, 0700);
					clearstatcache();
				}
				ELSE
				{
					//aus anderen raw erzeugte jpg muessen noch gedreht werden:
					$rot_filename = createQuickPreview($Orientation,$new_filename,$sr);
				}
			}
			$result4 = mysql_query( "UPDATE $table2 SET FileName = '$new_filename' WHERE pic_id = '$pic_id'");
			$z = '1';
		}
		//alle anderen Dateien, die weder RAW noch jpg sind werden hier behandelt:
		ELSEIF(in_array($ext,$supported_extensions)) 
		{
			//das oder die jpg-Bilder werden erzeugt:
			$command = $conv." -flatten ".$pic_path."/".$tmp_filename." ".$pic_path."/".$new_filename."";
			$output = shell_exec($command);
			IF(file_exists($pic_path."/".$new_filename))
			{
				//das Original(jpg-)Bild wird ggf als rotierte Kopie abgelegt
				IF($Orientation == '3' OR $Orientation == '6' OR $Orientation == '8')
				{
					$rot_filename = createQuickPreview($Orientation,$new_filename,$sr);
				}
				$result4 = mysql_query( "UPDATE $table2 SET FileName = '$new_filename' WHERE pic_id = '$pic_id'");
				//die Datei-Attribute werden fuer die hochgeladene Original-(jpg)Bilddatei auf 0700 gesetzt:
				$fileOri = $pic_path."/".$new_filename;
				clearstatcache();
				chmod ($fileOri, 0700);
				clearstatcache();
				$z = '1';//echo "Die Datei enth&auml;lt nur EIN Bild.!";
			}
			ELSE
			{
				echo "Es liegt ein Problem bei der Datenerfassung vor!";
				/*
				//es war vermutlich eine Datei mit mehreren Ebenen
				$z = '0';
				WHILE($z<100)
				{
					//########################################################################
					//jedes Bild der Datei muss wie ein eigenstaendiges Bild behandelt werden!
					//########################################################################
					$result2 = mysql_query( "INSERT INTO $table2 (Owner,FileNameOri,DateInsert) VALUES ('$user_id', '$datei_name', '$DateInsert')");
					echo mysql_error();
					$pic_id = mysql_insert_id();			//echo "User-ID: ".$user_id."; Rec-ID: ".$pic_id."<BR>";
					$new_filename = str_replace('.'.$ext,'',$base_name)."-".$z.".jpg";
					IF(file_exists($pic_path."/".$new_filename))
					{
						//das Original(jpg)Bild wird ggf als rotierte Kopie abgelegt
						IF($Orientation == '3' OR $Orientation == '6' OR $Orientation == '8')
						{
							$rot_filename = createQuickPreview($Orientation,$new_filename,$sr);
						}
						$result4 = mysql_query( "UPDATE $table2 SET FileName = '$new_filename' WHERE pic_id = '$pic_id'");
						//die Datei-Attribute werden fuer die hochgeladene Original-(jpg)Bilddatei auf 0700 gesetzt:
						$fileOri = $pic_path."/".$new_filename;
						clearstatcache();
						chmod ($fileOri, 0700);
						clearstatcache();
						$z++;
					}
					ELSE
					{
						break; //echo "Die Datei beinhaltet ".$z." Bilder.";
					}
				}
				//Wenn eine Datei mehrere Bilder enthaelt, wird nur der Datei-Rumpf als Parameter uebergeben:
				$new_filename = str_replace('.'.$ext,'',$base_name);
				*/
			}
		}
		ELSE
		{
			//nicht unterstuetzte Datei-Typen werden geloescht und Meldung ausgegeben:
			unlink($pic_path."/".$tmp_filename);
			echo "Dateien des Typs *.".$ext." werden nicht unterst&uuml;tzt.";
			$del++;
		}
	}
	ELSE
	{
		//hier werden *.jpg-s bearbeitet:
		$z = '1';
		$new_filename = $tmp_filename;	//jpg-Dateien behalten ihren eindeutigen Dateinamen
		//2, 4, 5 und 7 sind Spiegelungen, nur 3, 6 und 8 sind reine Rotationen:
		//Erzeugung der lagerichtigen Kopie in /rotated:
		IF($Orientation == '3' OR $Orientation == '6' OR $Orientation == '8')
		{
			$rot_filename = createQuickPreview($Orientation,$new_filename,$sr);
		}
		
		$result4 = mysql_query( "UPDATE $table2 SET FileName = '$new_filename' WHERE pic_id = '$pic_id'");
		//die Datei-Attribute werden fuer die hochgeladene Original-(jpg)Bilddatei auf 0700 gesetzt:
		$fileOri = $pic_path."/".$new_filename;
		clearstatcache();
		chmod ($fileOri, 0700);
		clearstatcache();
	}
//Ausrichtungsinformationen lagen vor: jpg's und ggf gedrehte Kopien der Originale wurden bis hierher erzeugt.
/*
$end1 = microtime();
list($start1msec, $start1sec) = explode(" ",$start1);
list($end1msec, $end1sec) = explode(" ",$end1);
$runtime1 = round(($end1sec + $end1msec) - ($start1sec + $start1msec),2);
echo "Bildupload beendet nach : ".$runtime1." Sekunden, <b>Differenz: ".$runtime1." Sekunden</b><BR>";
*/
	//Funktions-Parameter: Bild-ID, Anzahl der Szenen; User-ID; Ausrichtung
	savePicture($pic_id,$z,$user_id,$Orientation,$sr);	//Parameter sollten reichen, da sich alles weitere erzeugen laesst
/*
$end2 = microtime();
list($end2msec, $end2sec) = explode(" ",$end2);
$runtime2 = round(($end2sec + $end2msec) - ($start1sec + $start1msec),2);
echo "Bilddatenspeicherung beendet nach : ".$runtime2." Sekunden, <b>Differenz: ".($runtime2 - $runtime1)." Sekunden</b><BR>
(md5-Summe ermitteln, HQ/Thumnail erzeugen, Daten in die DB schreiben, Berechtigungen auf die Dateien setzen)<BR>";	
*/
	//Aus Preformance-Gruenden werden die Histogramme aus den HQ-Bildern gewonnen:
	$result3 = mysql_query( "SELECT * FROM $table2 WHERE pic_id = '$pic_id'");
	$FNHQ = mysql_result($result3, isset($i3), 'FileNameHQ');
	$md5sum = mysql_result($result3, isset($i3), 'md5sum');		//wird bei der Doublettenpruefung benoetigt
	$FileName = $FNHQ;
	//Parameter: Bild-ID, Name der HQ-Datei (z.B. 21456_hq.jpg), Server-Root
	generateHistogram($pic_id,$FileName,$sr);
/*
$end3 = microtime();
list($end3msec, $end3sec) = explode(" ",$end3);
$runtime3 = round(($end3sec + $end3msec) - ($start1sec + $start1msec),2);
echo "Histogrammerstellung beendet nach : ".$runtime3." Sekunden, <b>Differenz: ".($runtime3 - $runtime2)." Sekunden</b><BR>
(R/G/B/G-Bilder, Datenbank-Update)<BR>";		
*/
	//Meta-Daten aus dem Bild auslesen und in die Tabelle pictures schreiben:
	//Parameter: Bild-ID, Server-Root, ermittelte Bildausrichtung
	extractExifData($pic_id,$sr,$Orientation);
/*
$end4 = microtime();
list($start1msec, $start1sec) = explode(" ",$start1);
list($end4msec, $end4sec) = explode(" ",$end4);
$runtime4 = round(($end4sec + $end4msec) - ($start1sec + $start1msec),2);
echo "Meta-Daten-Auslesen beendet nach : ".$runtime4." Sekunden, <b>Differenz: ".($runtime4 - $runtime3)." Sekunden</b><BR>(Meta-Daten auslesen und in die DB schreiben)<BR>";	
*/
//  +++  loeschen der soeben in die DB aufgenommene Datei aus dem Upload-Ordner, aber nur, wenn sich die Originaldatei lesen laesst:  +++
	$fh = fopen($pic_path.'/'.$new_filename, 'r');
	if($fh)
	{
		IF($datei_name != "." && $datei_name != "..")
		{
			$datei_name = $ftp_path."/".$uid."/uploads/".$datei_name;
			IF(!@unlink($datei_name))
			{
				echo "Konnte die Datei $datei_name nicht l&ouml;schen!<BR>";
			}
			ELSE
			{
				//Log-Datei schreiben:
				$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
				fwrite($fh,date('d.m.Y H:i:s').": Bild ".$pic_id." wurde von ".$username." erfasst. (Zugriff von ".$_SERVER['REMOTE_ADDR'].")\n");
				fclose($fh);
				$del++;
			}
		}
	}
	ELSE
	{
		//ansonsten wird der soeben angelegte Datensatz und alle Vorschaubilder geloescht, womit der Datensatz erneut angelegt werden muss
//		include $sr.'/bin/share/delete_picture.php?pic_id='.$pic_id.'&c_username='.$username;
		include $sr.'/bin/share/delete_picture.php?pic_id='.$pic_id.'&uid='.$uid;
	}
		
//######################  Ende der reinen Datenerfassung ohne weitere Aktionen  ####################################################################################
//
//	
	//echo "gel&ouml;schte Dateien: ".$del." von ".$n."<BR>";
	
	//#####################     Doublettenpruefung     ###########################################
	
	//Es wird kontrolliert, ob es bereits ein (oder mehrere) Bilder in der DB gibt,bei denen Uebereinstimmung besteht in:
	//Original-Dateinamen und md5-Pruefsumme
	
	$neue_datei = basename($datei_name);
	//echo "neuer Dateiname: ".$neue_datei.", MD5-Summe: ".$md5sum.", User-ID: ".$user_id."<BR>";
	
	$result5 = mysql_query("SELECT * FROM $table2 WHERE FileNameOri = '$neue_datei' AND md5sum = '$md5sum'");
	$num5 = mysql_num_rows($result5);
	echo mysql_error();
	//echo $num5 - 1 ." Uebereinstimmungen<BR>";
	IF($num5 > 1) //einenTreffer gibt es immer - das soeben erfasste Bild!
	{
		//doppelte Vorkommen werden in der Tabelle 'doubletten' vermerkt und nach der vollst. Erfassung zur Bearbeitung angezeigt
		FOR($i5=0; $i5<$num5; $i5++)
		{
			$bild_id = mysql_result($result5, $i5, 'pic_id');
			// $pic_id ist der neu eingelesene Datensatz
			// $bild_id ist ein Datensatz in der DB mit dem gleichen Original-Dateinamen und der gleichen Pruefsumme
			IF($bild_id !== $pic_id)
			{
				$result6 = mysql_query("INSERT INTO $table21 (new_pic_id, old_pic_id, user_id) VALUES ('$pic_id', '$bild_id', '$user_id')");
				echo mysql_error();	
			}
		}
		$doublette = 1;
	}
	
	//############################################################################################

	IF($n == $del)
	{
		//Berechnung der Zeit bis zur vollstaendigen Erfassung aller Bilder:
		$end5 = microtime();
		list($end5msec, $end5sec) = explode(" ",$end5);
		list($startmsec, $startsec) = explode(" ",$start);
		$runtime5 = ($end5sec + $end5msec) - ($startsec + $startmsec);
		$rt5 = number_format(($runtime5 / 60),2);
		//echo "Zeit bis zur Fertigstellung: ".$rt5." Minuten<BR>";
		//Berechnung der durchschnittlichen Zeit pro Bild:
		$av_rt = number_format(($runtime5 / $n),2);

		$meldung = "Erfassung begonnen: ". $start_time."<BR>";
		$meldung .= "Erfassung abgeschlossen: ". date('d.m.Y, H:i:s')."<BR>";
		$meldung .= "Gesamtzeit: ".$rt5." Minuten (durchschnittliche Bearbeitungszeit: ".$av_rt." sek / Bild)<BR><BR>";
		IF($doublette == 0)
		{
			$meldung .= "<input type=\"button\" VALUE = \"Fertig - zur&uuml;ck zur Startseite\" onClick=\"location.href=\'../start.php\'\">";
		}
		ELSE
		{
			$meldung .= "Ein oder mehrere Bilder sind nach dem Upload mehrfach in der Datenbank enthalten.<BR>Bitte pr&uuml;fen Sie die neu erfassten Bilder auf eventuelle Doubletten.<BR><BR>";
			$meldung .= "<input type=\"button\" VALUE = \"Zur Dublettenpr&uuml;fung\" onClick=\"location.href=\'doublettenliste1.php?user_id=$user_id\'\">";	
		}
		
		//echo $meldung;
		?>
		<SCRIPT language="JavaScript">
		document.getElementById('meldung').innerHTML='<?php echo $meldung?>';
		</SCRIPT>
		<?php
	}
	flush();
}
mysql_close($conn);
?>
</HTML>