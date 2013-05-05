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
  <title>Kollektion ansehen</title>
  <meta name="GENERATOR" content="eclipse">
  <meta name="AUTHOR" content="k. henneberg">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel=stylesheet type='text/css' href='../../css/format2.css'>
  <link rel="shortcut icon" href="../../share/images/favicon.ico">
  <script language="JavaScript" src="../../share/functions/resize_elements.js"></script>
  <script language="JavaScript" src="../../share/functions/jquery-1.8.2.min.js"></script>
  <script language="JavaScript">
  	jQuery.noConflict();
	jQuery(document).ready(checkWindowSize);
	jQuery(window).resize(checkWindowSize); 
  </script>
</head>

<body>

<?php

/*
 * Project: pic2base
 * File: view_collection.php
 * Ansicht der ausgewaehlten Kollektion (Uebersicht aller Bilder)
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
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/css/initial_layout_settings.php';

$max_size = 200;	//max. Ausdehnung des Vorschaubildbereichs in px

$result1 = mysql_query("SELECT $table25.pic_id, $table25.coll_id, $table24.coll_id, $table24.coll_name, $table2.pic_id, $table2.FileNameHQ 
FROM $table2, $table25, $table24 
WHERE $table25.coll_id = '$coll_id'
AND $table24.coll_id = $table25.coll_id
AND $table25.pic_id = $table2.pic_id
 ORDER BY position");
$num1 = mysql_num_rows($result1);

$coll_name = mysql_result($result1, $i1, 'coll_name');
if(strlen($coll_name) > 65)
{
	$coll_name = substr($coll_name, 0, 65)."...";
}

echo "
<DIV Class='klein'>
	<div id='page'>
	
		<div id='head'>
			pic2base :: Ansicht der Kollektion \"".$coll_name."\"
		</div>
		
		<div id='navi'>
			<div class='menucontainer'>";
			createNavi2_2($uid);
			echo "
			</div>
		</div>
		
		<div id='content' style='background-color:darkgrey;'>
		
			<fieldset style='background-color:none; margin-top:10px;'>
			<legend style='color:blue; font-weight:bold;'>Ansicht der ausgew&auml;hlten Kollektion</legend>
			<div id='scrollbox3' style='overflow-y:scroll;'>";

				for($i1=0; $i1<$num1; $i1++)
				{
					$pic_id = mysql_result($result1, $i1, 'pic_id');
					$FileNameHQ = mysql_result($result1, $i1, 'FileNameHQ');
					$pic_size = getimagesize($sr."/images/vorschau/hq-preview/$FileNameHQ");
					$pic_breite = $pic_size[0];
					$pic_hoehe = $pic_size[1];
					
					//echo $pic_breite;
					if($pic_breite > $pic_hoehe)
					{
						$oberer_abstand = ($max_size - (176 / $pic_breite) * $pic_hoehe) / 2;
						echo "<div style='width:".$max_size."px; height:".$max_size."px; background-color:#222222; float:left; margin:2px; text-align:center; border-radius:10px;'>					
						<img src='../../../images/vorschau/hq-preview/$FileNameHQ' style='width:176px; margin-top:".$oberer_abstand."px; border-radius:5px;' />
						</div>";
					}
					else
					{
						$oberer_abstand = ($max_size - 176) / 2;
						echo "<div style='width:".$max_size."px; height:".$max_size."px; background-color:#222222; float:left; margin:2px; text-align:center; border-radius:10px;'>					
						<img src='../../../images/vorschau/hq-preview/$FileNameHQ' style='height:176px; margin-top:".$oberer_abstand."px; border-radius:5px;' />
						</div>";
					}
				}

			echo "
			</div>
			</fieldset>
			
			<fieldset style='background-color:none; margin-top:1px;'>
			<legend style='color:blue; font-weight:bold;'>Aktion</legend>
				<div id='scrollbox4' style='overflow-y:scroll;'><center>
					<input type='button' name='slideshow' value='Dia-Show' style='width:180px; margin-right:10px;' title='Dia-Show starten' onclick='location.href=\"../edit/show_presentation.php?coll_id=$coll_id\"'>
					<input type='button' name='cancel' value='Abbrechen und zur&uuml;ck' style='width:180px;' title='Die Sortierung wird verworfen' onclick='location.href=\"recherche2.php?pic_id=0&mod=collection\"'>
					</center>
				</div>
			</fieldset>
			
		</div>
		
		<div id='foot'>
			<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
		</div>
	
	</div>
</DIV>";
?>

</body>
</html>
