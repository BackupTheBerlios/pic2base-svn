<?php

IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
}

if (array_key_exists('file',$_GET))
{
	$file = $_GET['file'];
}

$error_code = NULL;

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$conv = buildConvertCommand($sr);
$exiftool = buildExiftoolCommand($sr);
$dcraw = buildDcrawCommand($sr);

//Diese Datei soll erfasst werden:
$bild = $ftp_path."/".$c_username."/uploads/".$file;

//Pruefung, ob die Datei eine datei ist:
if($bild != "" && $bild != "." && $bild != "..")
{
	$info = pathinfo($bild);
	$extension = strtolower($info['extension']);
	IF(in_array($extension,$supported_filetypes) OR $extension == 'jpg')
	{
		// wenn ein gueltiges Dateiformat vorliegt, erfolgt die Datenuebernahme:
		$Ori_arr = preg_split('# : #',shell_exec($exiftool." -Orientation -n ".$bild)); //num. Wert der Ausrichtung des Ori.-bildes

		if (count($Ori_arr) > 1 )
		{
			$Orientation = $Ori_arr[1];
		}
		else
		{
			$Orientation = '1';	
		}
		
		$Orientation = trim($Orientation);
		
	//  +++  Vergabe eines eindeutigen Dateinamens  +++++
		//Zur eindeutigen Identifizierung der Bilddateien wird seit dem 12.06.2008 die Datensatz-Nr. des jeweiligen Bildes verwendet. 
		// Hierzu wird vor dem eigentlichen Upload ein Dummy-Datensatz angelegt und die Datensatz-Nr. ermittelt:
		$result1 = mysql_query( "SELECT id FROM $table1 WHERE username = '$c_username' AND aktiv = '1'");
		echo mysql_error();
		$user_id = mysql_result($result1, isset($i), 'id');
		$DateInsert = date('Y-m-d H:i:s');
		$result2 = mysql_query( "INSERT INTO $table2 (Owner,FileNameOri,DateInsert) VALUES ('$user_id', '$file', '$DateInsert')");
		echo mysql_error();
		$pic_id = mysql_insert_id();									//echo "User-ID: ".$user_id."; Rec-ID: ".$pic_id."<BR>";
		$tmp_filename = $pic_id.".".$extension;							//Dateiname z.B.: 112233.nef
		
		//  Kontrolle, ob Upload erfolgreich war  +++++
		@copy("$bild","$pic_path/$tmp_filename")						// Bild wird vom Uploadordner in den Originale-Ordner kopiert (z.B. nach ....images/originale/12345.nef)
		or die("Upload fehlgeschlagen!");
		$tmp_file = $sr."/images/originale/".$tmp_filename;
		clearstatcache();  
		chmod ($tmp_file, 0700);
		clearstatcache();	
	//  +++  Egal, was reinkommt: alle Bilder werden in JPEG gewandelt, um bei der Ausgabe die Meta-Daten mitliefern zu koennen:
		$file_info = pathinfo($tmp_filename);
		//Pruefung auf unterstuetzte Datei-Formate:
		//... wenn es keine jpg-Datei ist:
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
			//...alle anderen Dateien, die weder RAW noch jpg sind werden hier behandelt:
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

		//Funktions-Parameter: Bild-ID, Anzahl der Szenen; User-ID; Ausrichtung
		savePicture($pic_id,'1',$user_id,$Orientation,$sr);	//Parameter sollten reichen, da sich alles weitere erzeugen laesst

		//Aus Preformance-Gruenden werden die Histogramme aus den HQ-Bildern gewonnen:
		$result3 = mysql_query( "SELECT * FROM $table2 WHERE pic_id = '$pic_id'");
		$FNHQ = mysql_result($result3, isset($i3), 'FileNameHQ');
		$md5sum = mysql_result($result3, isset($i3), 'md5sum');		//wird bei der Doublettenpruefung benoetigt
		$FileName = $FNHQ;
		//Parameter: Bild-ID, Name der HQ-Datei (z.B. 21456_hq.jpg), Server-Root
		generateHistogram($pic_id,$FileName,$sr);
	
		//Meta-Daten aus dem Bild auslesen und in die Tabelle pictures schreiben:
		//Parameter: Bild-ID, Server-Root, ermittelte Bildausrichtung
		extractExifData($pic_id,$sr,$Orientation);
	
		//  +++  loeschen der soeben in die DB aufgenommene Datei aus dem Upload-Ordner, aber nur, wenn sich die Originaldatei lesen laesst:  +++
		$fh = fopen($pic_path.'/'.$new_filename, 'r');
		if($fh)
		{
			IF($bild != "." && $bild != "..")
			{
				$bild = $ftp_path."/".$c_username."/uploads/".$file;
				IF(!@unlink($bild))
				{
					echo "Konnte die Datei $bild nicht l&ouml;schen!<BR>";
					$error_code = 1;
				}
				ELSE
				{
					//Log-Datei schreiben:
					$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
					fwrite($fh,date('d.m.Y H:i:s').": Bild ".$pic_id." wurde von ".$c_username." erfasst. (".$_SERVER['REMOTE_ADDR'].")\n");
					fclose($fh);
					$error_code = 0;
					
					//#####################     Doublettenpruefung     ###########################################
	
					//Es wird kontrolliert, ob es bereits ein (oder mehrere) Bilder in der DB gibt,bei denen Uebereinstimmung besteht in:
					//Original-Dateinamen und md5-Pruefsumme
					
					$neue_datei = basename($bild);
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
							// $bild_id ist ein Datensatz in der DB mit dem gleichen Original-Dateinamen und der gleichen Pruefsumme wie das soeben erfasste Bild
							IF($bild_id !== $pic_id)
							{
								$result6 = mysql_query("INSERT INTO $table21 (new_pic_id, old_pic_id, user_id) VALUES ('$pic_id', '$bild_id', '$user_id')");
								echo mysql_error();	
							}
						}
					}
					
					//############################################################################################
					
				}
			}
		}
		ELSE
		{
			//ansonsten wird der soeben angelegte Datensatz und alle Vorschaubilder geloescht, womit der Datensatz erneut angelegt werden muss
			include $sr.'/bin/share/delete_picture.php?pic_id='.$pic_id.'&c_username='.$c_username;
		}
	}
	ELSE
	{
		$hinweis .= $datei." besitzt kein g&uuml;ltiges Bild-Dateiformat!<BR>";
	}
}

$obj1 = new stdClass();
$obj1->errorCode = $error_code;
$obj1->Datei = $bild;
$obj1->Username = $c_username;
$output = json_encode($obj1);
echo $output;
?>
