<?php

IF ($_COOKIE['uid'])
{
	$uid = $_COOKIE['uid'];
}

if (array_key_exists('pic_id',$_GET))
{
	$pic_id = $_GET['pic_id'];
}

if (array_key_exists('filetype',$_GET))
{
	$filetype = $_GET['filetype'];
}

$error_code = 0;

include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
SWITCH($filetype)
{
	CASE 'ori':
		
		// ##########################################################################################################################################################
		// Wenn selbst das Original fehlt, wird versucht, aus jeweils dem Bild mit hoechster verfuegbarer Qualitaet das fehlende Bild Original-Ersatzbild zu erzeugen
		// In dem Caption-Abstract wird ein entsprechender Hinweis hinterlegt
		// Das Ergebnis wird in die Log-Datei geschrieben.
		// ##########################################################################################################################################################
		
		$result311 = mysql_query("SELECT FileNameHQ, FileNameV FROM $table2 WHERE pic_id = '$pic_id'");
		$FileNameHQ = mysql_result($result311, isset($i311), 'FileNameHQ');
		$FileNameV = mysql_result($result311, isset($i311), 'FileNameV');
		$FILE_HQ = $pic_hq_path."/".$FileNameHQ;
		$FILE_V = $pic_thumbs_path."/".$FileNameV;
		@$fh_hq = fopen($FILE_HQ, 'r');	//existiert eine hq-Vorschau?
		@$fh_v = fopen($FILE_V, 'r');	//existiert eine Thumbs-Vorschau?
		if($fh_hq)
		{
			$datei = $FILE_HQ;
			$target = $pic_path."/".$pic_id.".jpg";
			if(@copy($datei,$target))
			{
				$ca_text = "Original-Bild wurde durch HQ-Vorschau ersetzt.";
				$result312 = mysql_query( "UPDATE $table2 SET Caption_Abstract = '$ca_text' WHERE pic_id = '$pic_id'");
				//Log-Datei schreiben:
				$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
				fwrite($fh,date('d.m.Y H:i:s').": > > > DB-Wartung > > > Das Original von Bild ".$pic_id." wurde durch die HQ-Vorschau ersetzt! (Zugriff von ".$_SERVER['REMOTE_ADDR'].")\n");
				fclose($fh);
				$error_code = 0;
			}
			else
			{
				$error_code = "Fehler bei Bild ".$pic_id.", konnte Original nicht durch HQ-Vorschau ersetzen.";
			}
		}
		elseif($fh_v)
		{
			$datei = $FILE_V;
			$target = $pic_path."/".$pic_id.".jpg";
			if(@copy($datei,$target))
			{
				$ca_text = "Original-Bild wurde durch Thumbs-Vorschau ersetzt.";
				$result313 = mysql_query( "UPDATE $table2 SET Caption_Abstract = '$ca_text' WHERE pic_id = '$pic_id'");
				//Log-Datei schreiben:
				$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
				fwrite($fh,date('d.m.Y H:i:s').": > > > DB-Wartung > > > Das Original von Bild ".$pic_id." wurde durch die Thumbs-Vorschau ersetzt! (Zugriff von ".$_SERVER['REMOTE_ADDR'].")\n");
				fclose($fh);
				$error_code = 0;
			}
			else
			{
				$error_code = "Fehler bei Bild ".$pic_id.", konnte Original nicht durch Thumbs-Vorschau ersetzen.";
			}
		}
		else
		{
			//wenn weder Original noch eines der Vorschaubilder existiert, wird der betreffende Datensatz komplett geloescht
				
			$result314 = mysql_query( "SELECT * FROM $table2 WHERE pic_id = '$pic_id'");
			@$num314 = mysql_num_rows($result314);
			IF($num314 == '1')
			{
				$row = mysql_fetch_array($result314);
				$pic_id = $row['pic_id'];
				$FileName = $row['FileName'];
				$FileNameOri = $row['FileNameOri'];
				//Angaben fuer die Log-Datei:
				$CaptionAbstract = $row['Caption_Abstract'];
				$Keywords = $row['Keywords'];
				
				$datei_mono = $monochrome_path."/".$pic_id."_mono.jpg";
				$datei_hist = $hist_path."/".$pic_id."_hist.gif";
				$datei_hist_r = $hist_path."/".$pic_id."_hist_0.gif";
				$datei_hist_g = $hist_path."/".$pic_id."_hist_1.gif";
				$datei_hist_b = $hist_path."/".$pic_id."_hist_2.gif";
				$datei_tmp = $pic_path."/tmp/".$pic_id.".jpg";
				
				$datei_ori = $pic_path."/".$FileName;
				$datei_rotated = $pic_rot_path."/".$FileName;
				$datei_hq = $pic_hq_path."/".str_replace('.','_hq.',$FileName);
				$datei_thumbs = $pic_thumbs_path."/".str_replace('.','_v.',$FileName);
			}
			
			$result315 = mysql_query( "DELETE FROM $table10 WHERE pic_id = '$pic_id'");
			$result316 = mysql_query( "DELETE FROM $table2 WHERE pic_id = '$pic_id' AND FileName = '$FileName'");
			@$l1 = unlink($datei_ori);
			@$l2 = unlink($datei_rotated);
			@$l3 = unlink($datei_hq);
			@$l4 = unlink($datei_thumbs);
			@$l5 = unlink($datei_mono);
			@$l6 = unlink($datei_hist);
			@$l7 = unlink($datei_hist_r);
			@$l8 = unlink($datei_hist_g);
			@$l9 = unlink($datei_hist_b);
			
			IF(@fopen($datei_tmp,'r'))
			{
				@$l10 = unlink($datei_tmp);
			}
			
			$file_info = pathinfo($datei_ori);
			$ext = ".".$file_info['extension'];				//Dateiendung mit Punkt
			$base_name = str_replace($ext,'',$file_info['basename']);	//Dateiname ohne Punkt und Rumpf 
			//echo $base_name;
			$base_name1 = $base_name.".";
			$base_name2 = $base_name."_";
			//Damit werden alle Dateien mit beliebiger Endung erfasst (12345.jpg; 12345.nef usw.) aber auch alle Scene-Bilder (12345_1.jpg, 12345_2.jpg usw)
			$result317 = mysql_query( "SELECT * FROM $table2 WHERE (FileName LIKE '$base_name1%' OR FileName LIKE '$base_name2%')");
			$num317 = mysql_num_rows($result317);	//es gibt in der DB insges. $num5 Dateien mit dem Stammnamen.
			//echo $num5." Dateien mit Stammnamen in DB<BR>";
			$k = '0';
			FOR($i317='0'; $i317<$num317; $i317++)
			{
				$file_name = mysql_result($result317, $i317, 'FileName');
				IF(file_exists($pic_path."/".$file_name))
				{
					$k++;
				}
			}
			//es befinden sich nun noch weitere $k Dateien im Originale-Ordner.
			//echo "davon ".$k." noch im Originale-Ordner.";
			IF($k == '0')
			{
			//wenn keine Stamm-Datei mehr im Originale-Ordner mehr ist wird eine evtl. vorh. Nicht-JPG-Datei geloescht:
				FOREACH($supported_filetypes AS $sft)
				{
					IF(file_exists($pic_path."/".$base_name.".".$sft))
					{
						//echo "Es gibt eine Nicht-JPG-Datei";
						unlink($pic_path."/".$base_name.".".$sft);
					}
				}
			}
			$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
			fwrite($fh,date('d.m.Y H:i:s').": > > > DB-Wartung > > > Der Datensatz zum Bild ".$pic_id." (Beschreibung: ".$CaptionAbstract.", Kategorie: ".$Keywords.") wurde entfernt! (Zugriff von ".$_SERVER['REMOTE_ADDR'].")\n");
			fclose($fh);
			$error_code = 99;
		}	
		$obj1 = new stdClass();
		$obj1->errorCode = $error_code;
		$obj1->pic_id = $pic_id;
		$obj1->Userid = $uid;
		$obj1->filetype = $filetype;
		$output = json_encode($obj1);
		echo $output;
			
	break;
	
	CASE 'hq':
		// es wird versucht, ein fehlendes HQ-Vorschaubild aus dem Original neu zu erzeugen:
		$result31 = mysql_query("SELECT FileName FROM $table2 WHERE pic_id = '$pic_id'");
		if(mysql_error() !== "")
		{
			$error_code = 1;
		}
		$FileName = mysql_result($result31, isset($i31), 'FileName');
		$FILE = $pic_path."/".$FileName;
		@$file_handle = fopen($FILE, 'r');
		if($file_handle)
		{
			fclose($file_handle);
			$dest_path = $pic_hq_path;
			$max_len = 800;
			$FileNameHQ = resizeOriginalPicture($FILE, $dest_path, $max_len, $sr);
			$fileHQ = $pic_hq_path."/".$FileNameHQ;	//echo $fileHQ;
			clearstatcache();  
			@chmod ($fileHQ, 0700);
			clearstatcache();
			@$fh = fopen($fileHQ, 'r');
			if(!$fh)
			{
				$error_code = "No Original for ".$pic_id;
			}
			else
			{
				fclose($fh);
				$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
				fwrite($fh,date('d.m.Y H:i:s').": > > > DB-Wartung > > > HQ-Vorschau zum Bild ".$pic_id." wurde neu erzeugt. (Zugriff von ".$_SERVER['REMOTE_ADDR'].")\n");
				fclose($fh);
			}
		}
		$obj1 = new stdClass();
		$obj1->errorCode = $error_code;
		$obj1->pic_id = $pic_id;
		$obj1->Userid = $uid;
		$obj1->filetype = $filetype;
		$output = json_encode($obj1);
		echo $output;	
	break;		
	
	CASE 'v':
		$result32 = mysql_query("SELECT FileNameHQ FROM $table2 WHERE pic_id = '$pic_id'");
		if(mysql_error() !== "")
		{
			$error_code = 1;
		}
		$FileNameHQ = mysql_result($result32, isset($i32), 'FileNameHQ');
		$FILE = $pic_hq_path."/".$FileNameHQ;
		$dest_path = $pic_thumbs_path;
		$max_len = 160;
		$FileNameV = createPreviewPicture($FILE, $dest_path, $max_len, $sr);
		$fileV = $pic_thumbs_path."/".$FileNameV;
		clearstatcache();  
		@chmod ($fileV, 0700);
		clearstatcache();
		@$fh = fopen($fileV, 'r');
		if(!$fh)
		{
			$error_code = 1;
		}
		else
		{
			fclose($fh);
			$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
			fwrite($fh,date('d.m.Y H:i:s').": > > > DB-Wartung > > > Thumb-Vorschau zum Bild ".$pic_id." wurde neu erzeugt. (Zugriff von ".$_SERVER['REMOTE_ADDR'].")\n");
			fclose($fh);
		}
		$obj1 = new stdClass();
		$obj1->errorCode = $error_code;
		$obj1->pic_id = $pic_id;
		$obj1->Userid = $uid;
		$obj1->filetype = $filetype;
		$output = json_encode($obj1);
		echo $output;	
	break;
	
	CASE 'hist_mono':
		$result33 = mysql_query("SELECT FileNameHQ FROM $table2 WHERE pic_id = '$pic_id'");
		echo mysql_error();
		$FileNameHQ = mysql_result($result33, isset($i33), 'FileNameHQ');
		//$mf - missing files -> Zahl der erneuerten Histogramme / monochrome Bilder
		$zv = generateHistogram($pic_id,$FileNameHQ,$sr);
		
		//Log-Datei schreiben:
		$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
		fwrite($fh,date('d.m.Y H:i:s').": > > > DB-Wartung > > > Histogramme zum Bild ".$pic_id." wurden neu erzeugt. (Zugriff von ".$_SERVER['REMOTE_ADDR'].")\n");
		fclose($fh);
		
		$obj1 = new stdClass();
		$obj1->errorCode = $error_code;
		$obj1->pic_id = $pic_id;
		$obj1->Userid = $uid;
		$obj1->filetype = $filetype;
		$output = json_encode($obj1);
		echo $output;	
	break;
}


?>