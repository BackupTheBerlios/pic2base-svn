<?php
//File-Owner: K. Henneberg
include 'global_config.php';
include $sr.'/bin/share/functions/main_functions.php';

//für alle Dateinamen wird intern Kleinschreibung verwendet, deshalb hier Konvertierung:
$file_name_raw = strtolower($file_name_raw);
//echo "RAW-Name: ".$file_name_raw."<BR>";
$contrast = str_replace('-cont ','',$contrast);
//echo $contrast;
IF($modus == 'tmp')
{
	//$parameter = $wb." ".$rota." ".$col_inter." ".$targ_color." ".$gamma." ".$hl." -c ".$hsi." -r 2.648 1 1.2556 1";
	$parameter = $wb." ".$rota." ".$col_inter." ".$targ_color." ".$gamma." ".$hl." -c ".$hsi;
	$new_filename = substr($file_name_raw,0,-4).".jpg";
	$command = $dcraw_path."/dcraw ".$parameter." ".$pic_path."/".$file_name_raw." | convert -quality 90 -resize 450x450 - ".$pic_path."/tmp/".$new_filename."";
	//echo $command;
	$output = shell_exec($command);
	echo $output;
	//echo $contrast;
	IF($contrast !== '0')
	{
		$c = '0';
		IF($contrast < '0')
		{
			WHILE($c > $contrast)
			{
				$contr .= "+contrast ";
				$c--;
			}
		}
		ELSEIF($contrast > '0')
		{
			WHILE($c < $contrast)
			{
				$contr .= "-contrast ";
				$c++;
			}
		}
		$command_a = $im_path."/convert ".$pic_path."/tmp/".$new_filename." ".$contr." ".$pic_path."/tmp/".$new_filename;
		//echo $command_a;
		$output = shell_exec($command_a);
	}
	
//#########################################################################################
	/*
	//Experimentelle Routine zur Helligkeits-Änderung:
	$info = getImagesize($pic_path."/tmp/".$new_filename);
	$breite = $info[0];
	$hoehe = $info[1];
	echo $breite." x ".$hoehe;
	
	$im = ImageCreateFromJPEG($pic_path."/tmp/".$new_filename);
	$rgba = imagecolorat($im,449,297);
	$alpha = ($rgba & 0x7F000000) >> 24;
	$red = ($rgba & 0xFF0000) >> 16;
	$green = ($rgba & 0x00FF00) >> 8;
	$blue = ($rgba & 0x0000FF);
	
	echo "# " . $red." ".$green." ".$blue;  
	*/
//#########################################################################################
	$x = time(now);
	//das Anhängsel "?var=$x" dient dazu, den Browser zu zwingen, das Bild NICHT aus dem Cache zu laden:
	echo "<img src=\"$inst_path/pic2base/images/originale/tmp/$new_filename?var=$x\">";
}
ELSEIF($modus == 'new')
{
	echo "Die neuen Vorschaubilder wurden erzeugt.<BR><BR>";
	//die folgenden Schritte werden abgearbeitet:
	//	1) jpg-Bild in Originalgröße neu erzeugen
	//	2) jpg-HQ-Vorschau neu erzeugen
	//	3) jpg-Thumbnail neu erzeugen
	//	4) Datei-Attribute aller dre neu erzeugten Dateien auf 700 setzen
	
	//Schritt 1)
	$parameter = $wb." ".$rota." ".$col_inter." ".$targ_color." ".$gamma." ".$hl." -c ".$hsi;
	$new_filename = substr($file_name_raw,0,-4).".jpg";
	$command1 = $dcraw_path."/dcraw ".$parameter." ".$pic_path."/".$file_name_raw." | convert -quality 100 - ".$pic_path."/".$new_filename."";
	$output = shell_exec($command1);
	
	IF($contrast !== '0')
	{
		$c = '0';
		IF($contrast < '0')
		{
			WHILE($c > $contrast)
			{
				$contr .= "+contrast ";
				$c--;
			}
		}
		ELSEIF($contrast > '0')
		{
			WHILE($c < $contrast)
			{
				$contr .= "-contrast ";
				$c++;
			}
		}
		$command1_a = $im_path."/convert ".$pic_path."/".$new_filename." ".$contr." ".$pic_path."/".$new_filename;
		$output = shell_exec($command1_a);
	}
	
	// Schritt 2)
	$source = $pic_path."/".$new_filename;
	$FileNameHQ = substr($file_name_raw,0,-4)."_hq.jpg";
	$max_len = '800';
	//$command2 = $im_path."/convert -quality 80 -size ".$max_len."x0 ".$source." -resize ".$max_len."x0 ".$pic_hq_preview."/".$FileNameHQ."";
	$command2 = $im_path."/convert -quality 80 ".$source." -resize ".$max_len."x".$mac_len." ".$pic_hq_preview."/".$FileNameHQ."";
 	//echo $command;
 	$output = shell_exec($command2);
 	
	IF($contrast !== '0')
	{
		$c = '0';
		IF($contrast < '0')
		{
			WHILE($c > $contrast)
			{
				$contr .= "+contrast ";
				$c--;
			}
		}
		ELSEIF($contrast > '0')
		{
			WHILE($c < $contrast)
			{
				$contr .= "-contrast ";
				$c++;
			}
		}
		$command2_a = $im_path."/convert ".$pic_hq_preview."/".$FileNameHQ." ".$contr." ".$pic_hq_preview."/".$FileNameHQ;
		$output = shell_exec($command2_a);
	}
 	
 	// Schritt 3)
 	$FileNameV = substr($file_name_raw,0,-4)."_v.jpg";
 	//echo $FileNameV."<BR>";
 	$max_len = '160';
 	$command3 = $im_path."/convert -quality 80 ".$source." -resize ".$max_len."x".$max_len." ".$pic_thumbs."/".$FileNameV."";
      	//echo $command."<BR>";
      	$output = shell_exec($command3);
      	
	IF($contrast !== '0')
	{
		$c = '0';
		IF($contrast < '0')
		{
			WHILE($c > $contrast)
			{
				$contr .= "+contrast ";
				$c--;
			}
		}
		ELSEIF($contrast > '0')
		{
			WHILE($c < $contrast)
			{
				$contr .= "-contrast ";
				$c++;
			}
		}
		$command3_a = $im_path."/convert ".$pic_thumbs."/".$FileNameV." ".$contr." ".$pic_Thumbs."/".$FileNameV;
		$output = shell_exec($command3_a);
	}
      	
      	//abschlie0end Erzeugung des Anzeige-Bildes:
      	$command4 = $im_path."/convert -quality 80 ".$source." -quality 90 -resize 350x350 - ".$pic_path."/tmp/".$new_filename."";
	$output = shell_exec($command4);
	
	IF($contrast !== '0')
	{
		$c = '0';
		IF($contrast < '0')
		{
			WHILE($c > $contrast)
			{
				$contr .= "+contrast ";
				$c--;
			}
		}
		ELSEIF($contrast > '0')
		{
			WHILE($c < $contrast)
			{
				$contr .= "-contrast ";
				$c++;
			}
		}
		$command4_a = $im_path."/convert ".$pic_path."/tmp/".$new_filename." ".$contr." ".$pic_path."/tmp/".$new_filename;
		$output = shell_exec($command4_a);
	}
	
	$x = time(now);
	//das Anhängsel "?var=$x" dient dazu, den Browser zu zwingen, das Bild NICHT aus dem Cache zu laden:
	echo "<img src=\"$inst_path/pic2base/images/originale/tmp/$new_filename?var=$x\"><BR><BR>
	Aktualisieren Sie abschlie&szlig;end bitte Ihre Filmstreifen-Ansicht.";
}

?>