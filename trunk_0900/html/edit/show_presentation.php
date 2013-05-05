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

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/css/initial_layout_settings.php';

echo "
		<div id='galleria'>";
		$result1 = mysql_query("SELECT $table25.pic_id, $table25.coll_id, $table25.position, $table2.pic_id, $table2.FileName, $table2.FileNameHQ, $table2.FileNameV, $table2.Caption_Abstract
		FROM $table25, $table2
		WHERE $table25.coll_id = '$coll_id'
		AND $table25.pic_id = $table2.pic_id
		ORDER BY $table25.position");
		echo mysql_error();
		$num1 = mysql_num_rows($result1); 
		for($i1=0; $i1<$num1; $i1++)
		{
			$FileName = mysql_result($result1, $i1, 'FileName');
			$FileNameHQ = mysql_result($result1, $i1, 'FileNameHQ');
			$FileNameV = mysql_result($result1, $i1, 'FileNameV');
			$description = mysql_result($result1, $i1, 'Caption_Abstract');
			//echo "<img src='../../../images/originale/$FileName'>";
			echo "<a href='../../../images/vorschau/hq-preview/$FileNameHQ'><img src='../../../images/vorschau/thumbs/$FileNameV' data-big='../../../images/originale/$FileName' data-title='' data-description='$description'></a>";
		}
		
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
