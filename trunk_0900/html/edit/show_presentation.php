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
<html>

<head>
  <title>Pr&auml;sentation l&auml;uft...</title>
  <meta name="GENERATOR" content="eclipse">
  <meta name="AUTHOR" content="k. henneberg">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel=stylesheet type='text/css' href='../../css/format2.css'>
  <link rel="shortcut icon" href="../../share/images/favicon.ico">
  <script language="JavaScript" src="../../share/functions/resize_elements.js"></script>
  <script language="JavaScript" src="../../share/functions/jquery-1.8.2.min.js"></script>
  <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>-->
  <script language="JavaScript" src="../../share/galleria/galleria-1.2.9.min.js"></script>
<!-- <script language="JavaScript" src="../../share/galleria/themes/classic/galleria.classic.min.js"></script>-->
  
  <script language="JavaScript">
//  	jQuery.noConflict();
	jQuery(document).ready(checkWindowSize);
	jQuery(window).resize(checkWindowSize); 
  </script>
  
  <style>
    #galleria{ background: #999 }
  </style>
  
</head>

<body>

<?php

/*
 * Project: pic2base
 * File: show_presentation.php
 * Copyright (c) 2013 Klaus Henneberg
 *
 * Project owner:
 * Klaus Henneberg
 * Finkenweg 18
 * 38889 Blankenburg, BRD
 *
 * All files of this project are licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 */

if(array_key_exists('coll_id', $_GET))
{
	$coll_id = $_GET['coll_id'];
}

if(array_key_exists('qual', $_GET))
{
	$quality = $_GET['qual'];
}
else
{
	$quality = 'lq';
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/css/initial_layout_settings.php';
//phpinfo();
/*
$result0 = mysql_query("SELECT name, vorname FROM $table1 WHERE id = '$uid'");
$presentation_author = mysql_result($result0, isset($i0), 'vorname')." ".mysql_result($result0, isset($i0), 'name');
*/
echo "
		<div id='galleria'>";
		$result1 = mysql_query("SELECT $table25.pic_id, $table25.coll_id, $table25.position, $table2.pic_id, $table2.FileName, $table2.FileNameHQ, $table2.FileNameV, $table2.Caption_Abstract, $table2.owner, $table1.id, $table1.vorname, $table1.name
		FROM $table25, $table2, $table1
		WHERE $table25.coll_id = '$coll_id'
		AND $table25.pic_id = $table2.pic_id
		AND $table2.owner = $table1.id
		ORDER BY $table25.position");
		echo mysql_error();
		$num1 = mysql_num_rows($result1);
		
		$result2 = mysql_query("SELECT * FROM $table24 WHERE coll_id = '$coll_id'");
		$coll_name = utf8_decode(mysql_result($result2, isset($i2), 'coll_name'));
		$created = date('d.m.Y', strtotime(mysql_result($result2, isset($i2), 'created')));
		$last_modification = date('d.m.Y', strtotime(mysql_result($result2, isset($i2), 'last_modification')));
		
		// Vorspann-Bild erzeugen  #############################################
		// Titel der Kollektion, Format: 1000 x 750, weiss auf schwarz 
		$image = ImageCreate(1200, 750);
		$background_color = ImageColorAllocate($image, 0, 0, 0);
		$font_color = ImageColorAllocate($image, 240, 240, 240);
//		ImageString($image, 5, 100, 100, $coll_name, $font_color); // Variante mit GD-Schriftarten
		ImageTTFText($image, 18, 0, 100, 575, $font_color, '/usr/share/fonts/truetype/DejaVuSerif.ttf', 'pic2base präsentiert:');
		ImageTTFText($image, 24, 0, 100, 650, $font_color, '/usr/share/fonts/truetype/DejaVuSerif.ttf', $coll_name);
		ImagePNG($image, $p2b_path.'pic2base/tmp/vorspann.png'); //  ################# muss noch benutzerspezifisch gemacht werden. ##############
		echo "<img src='../../../tmp/vorspann.png'>";
		// Vorspann Ende  ######################################################
		
		$pic_owner = array();
		for($i1=0; $i1<$num1; $i1++)
		{
			$FileName = mysql_result($result1, $i1, 'FileName');
			$FileNameHQ = mysql_result($result1, $i1, 'FileNameHQ');
			$FileNameV = mysql_result($result1, $i1, 'FileNameV');
			$description = mysql_result($result1, $i1, 'Caption_Abstract');
			$owner = mysql_result($result1, $i1, 'vorname')." ".mysql_result($result1, $i1, 'name');
			
			if(!in_array($owner, $pic_owner))
			{
				$pic_owner[] = $owner;
			}
			if($quality == 'lq')
			{
				echo "<a href='../../../images/vorschau/hq-preview/$FileNameHQ'><img src='../../../images/vorschau/thumbs/$FileNameV' data-big='../../../images/originale/$FileName' data-title='' data-description='$description'></a>";
			}
			elseif($quality == 'hq')
			{
				echo "<a href='../../../images/originale/$FileName'><img src='../../../images/vorschau/thumbs/$FileNameV' data-big='../../../images/originale/$FileName' data-title='' data-description='$description'></a>";
			}
		}
		
		// Abspann-Bild erzeugen   ##############################################
		// Ersteller der Präsentation, Autoren aller Bilder, Erstellungsdatum, weiss auf schwarz
		$image = ImageCreate(1200, 750);
		$background_color = ImageColorAllocate($image, 0, 0, 0);
		$font_color = ImageColorAllocate($image, 240, 240, 240);
		// bei Textgroesse 18 werden fuer jede Zeile 30px veranschlagt
		
		if(count($pic_owner) == 1)
		{
			//ImageTTFText($image, 18, 0, 10, 670, $font_color, '/usr/share/fonts/truetype/DejaVuSerif.ttf', 'Diese Präsentation wurde angefertigt von '.$presentation_author);
			ImageTTFText($image, 18, 0, 10, 700, $font_color, '/usr/share/fonts/truetype/DejaVuSerif.ttf', '(C) '.$pic_owner[0].', '.$created.' / '.$last_modification);
		}
		else
		{
			$n = count($pic_owner);
			
			ImageTTFText($image, 14, 0, 10, (700 - ($n * 30) - 60), $font_color, '/usr/share/fonts/truetype/DejaVuSerif.ttf', 'Für die Präsentation wurde Bildmaterial u.a. der folgenden Autoren verwendet:');
			$y = 690 - ($n * 30);
			for($x=0; $x < 21; $x++)
			{
				ImageTTFText($image, 18, 0, 10, $y, $font_color, '/usr/share/fonts/truetype/DejaVuSerif.ttf', $pic_owner[$x]);
				$y =$y + 30;
			}
			if($n > 20)
			{
				ImageTTFText($image, 18, 0, 10, 720, $font_color, '/usr/share/fonts/truetype/DejaVuSerif.ttf', 'u.v.a. (C) '.$pic_owner[0].', '.$created.' / '.$last_modification);
			}
			
		}
		ImagePNG($image, $p2b_path.'pic2base/tmp/abspann.png'); //  ##############  muss noch benutzerspezifisch gemacht werden.  ######################
		echo "<img src='../../../tmp/abspann.png'>";
		// Abspann Ende  #########################################################
		echo "
       	</div>";
       	?>
		<script>
			Galleria.configure({
			//	imageCrop: true,
				transition: 'fade',	//fade, flash, pulse, slide, fadeslide
				transitionSpeed: 400,
			//	lightbox: true,
				showCounter: true,
				clicknext: true,
				autoplay: false		// true oder Zeit in ms
				});
   			Galleria.loadTheme('../../share/galleria/themes/classic/galleria.classic.min.js');
   			Galleria.run('#galleria');
   		</script>
</body>
</html>