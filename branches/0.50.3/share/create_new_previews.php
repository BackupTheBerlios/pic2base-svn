<?php
IF (!$_COOKIE['login'])
{
	include '../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../index.php');
}

//File-Owner: K. Henneberg
include 'global_config.php';
include $sr.'/bin/share/functions/main_functions.php';

$dcraw = buildDcrawCommand($sr);
$conv = buildConvertCommand($sr);

$contr = '';
//fuer alle Dateinamen wird intern Kleinschreibung verwendet, deshalb hier Konvertierung:
//var_dump($_REQUEST);
IF(array_key_exists('file_name_raw', $_REQUEST))
{
	$file_name_raw = strtolower($_REQUEST['file_name_raw']);
}
//echo "RAW-Name: ".$file_name_raw."<BR>";
IF(array_key_exists('contrast', $_REQUEST))
{
	$contrast = str_replace('-cont ','',$_REQUEST['contrast']);
}
//echo $contrast;
IF(array_key_exists('modus', $_REQUEST))
{
	$modus = $_REQUEST['modus'];
}

IF(array_key_exists('wb', $_REQUEST))
{
	$wb = $_REQUEST['wb'];
}

IF(array_key_exists('color_temp', $_REQUEST))
{
	$color_temp = $_REQUEST['color_temp'];
}

IF(array_key_exists('rota', $_REQUEST))
{
	$rota = $_REQUEST['rota'];
}
IF(array_key_exists('col_inter', $_REQUEST))
{
	$col_inter = $_REQUEST['col_inter'];
}
IF(array_key_exists('targ_color', $_REQUEST))
{
	$targ_color = $_REQUEST['targ_color'];
}
IF(array_key_exists('gamma', $_REQUEST))
{
	$gamma = $_REQUEST['gamma'];
}
IF(array_key_exists('hl', $_REQUEST))
{
	$hl = $_REQUEST['hl'];
}
IF(array_key_exists('hsi', $_REQUEST))
{
	$hsi = $_REQUEST['hsi'];
}
IF(array_key_exists('Orientation', $_REQUEST))
{
	$Orientation = $_REQUEST['Orientation'];
}

IF($modus == 'tmp')
{
	//$parameter = $wb." ".$rota." ".$col_inter." ".$targ_color." ".$gamma." ".$hl." -c ".$hsi." -r 2.648 1 1.2556 1";
	$parameter = $wb." ".$rota." ".$col_inter." ".$targ_color." ".$gamma." ".$hl." -c ".$hsi;
	$new_filename = substr($file_name_raw,0,-4).".jpg";
	$command = $dcraw." ".$parameter." ".$pic_path."/".$file_name_raw." | ".$conv." -quality 90 -resize 450x450 - ".$pic_path."/tmp/".$new_filename."";
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
		$command_a = $conv." ".$pic_path."/tmp/".$new_filename." ".$contr." ".$pic_path."/tmp/".$new_filename;
		$output = shell_exec($command_a);
	}
	
	$x = time();
	//das Anhaengsel "?var=$x" dient dazu, den Browser zu zwingen, das Bild NICHT aus dem Cache zu laden:
	echo "<img src=\"$inst_path/pic2base/images/originale/tmp/$new_filename?var=$x\">";
}
ELSEIF($modus == 'new')
{
	echo "Die neuen Vorschaubilder wurden erzeugt.<BR><BR>";
	//die folgenden Schritte werden abgearbeitet:
	//	1) jpg-Bild in Originalgroesse neu erzeugen
	//	2) rot. jpg in Originalgroesse neu erzeugen
	//	3) jpg-HQ-Vorschau neu erzeugen
	//	4) jpg-Thumbnail neu erzeugen
	//	5) Graustufenbild neu erzeugen
	//	6) Datei-Attribute aller neu erzeugten Dateien auf 700 setzen
	
	//Schritt 1)
	$parameter = $wb." ".$rota." ".$col_inter." ".$targ_color." ".$gamma." ".$hl." -c ".$hsi;
	$new_filename = substr($file_name_raw,0,-4).".jpg";
	$command1 = $dcraw." ".$parameter." ".$pic_path."/".$file_name_raw." | ".$conv." -quality 100 - ".$pic_path."/".$new_filename."";
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
	
	//Schritt 2)
	//echo "Ausrichtung: ".$Orientation."<BR>";
	$file_info = pathinfo($file_name_raw);
	$ext = strtolower($file_info['extension']);
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
			$rot_filename = createQuickPreview($Orientation,$new_filename,$sr);
		}
	}
	
	// Schritt 3)
	$source = $pic_path."/".$new_filename;
	$FileNameHQ = substr($file_name_raw,0,-4)."_hq.jpg";
	$max_len = '800';
	//$command2 = $im_path."/convert -quality 80 -size ".$max_len."x0 ".$source." -resize ".$max_len."x0 ".$pic_hq_path."/".$FileNameHQ."";
	$command2 = $conv." -quality 80 ".$source." -resize ".$max_len."x".$max_len." ".$pic_hq_path."/".$FileNameHQ."";
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
		$command2_a = $im_path."/convert ".$pic_hq_path."/".$FileNameHQ." ".$contr." ".$pic_hq_path."/".$FileNameHQ;
		$output = shell_exec($command2_a);
	}
 	
 	// Schritt 4)
 	$FileNameV = substr($file_name_raw,0,-4)."_v.jpg";
 	//echo $FileNameV."<BR>";
 	$max_len = '160';
 	$command3 = $conv." -quality 80 ".$source." -resize ".$max_len."x".$max_len." ".$pic_thumbs_path."/".$FileNameV;
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
		$command3_a = $im_path."/convert ".$pic_thumbs_path."/".$FileNameV." ".$contr." ".$pic_thumbs_path."/".$FileNameV;
		$output = shell_exec($command3_a);
	}
	
	// Schritt 5) Graustufenbild neu erzeugen
	$FileNameMono = substr($file_name_raw,0,-4)."_mono.jpg";
	$command5 = $conv." ".$pic_hq_path."/".$FileNameHQ." -colorspace Gray -quality 80% ".$monochrome_path."/".$FileNameMono;
	$output = shell_exec($command5);
	
    // Schritt 6) Erzeugung des Anzeige-Bildes:
    $command4 = $conv." -quality 80 ".$source." -quality 90 -resize 350x350 - ".$pic_path."/tmp/".$new_filename;
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
		$command4_a = $conv." ".$pic_path."/tmp/".$new_filename." ".$contr." ".$pic_path."/tmp/".$new_filename;
		$output = shell_exec($command4_a);
	}
	
	$x = time();
	//das Anhaengsel "?var=$x" dient dazu, den Browser zu zwingen, das Bild NICHT aus dem Cache zu laden:
	echo "<img src=\"$inst_path/pic2base/images/originale/tmp/$new_filename?var=$x\"><BR><BR>
	Aktualisieren Sie abschlie&szlig;end bitte Ihre Filmstreifen-Ansicht.";
}

?>