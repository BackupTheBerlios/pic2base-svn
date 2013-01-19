<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
	header('Location: ../../../index.php');
}
else
{
	$uid = $_COOKIE['uid'];
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>pic2base - Einzelbild-Erfassung</title>
  <meta name="GENERATOR" content="Eclipse">
  <meta name="AUTHOR" content="k. henneberg">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel=stylesheet type="text/css" href="../../css/format2.css">
  <link rel="shortcut icon" href="../../share/images/favicon.ico">
  <script language="JavaScript" src="../../share/functions/resize_elements.js"></script>
  <script language="JavaScript" src="../../share/functions/jquery-1.8.2.min.js"></script>
  <script language="JavaScript">
  	jQuery.noConflict()
	jQuery(document).ready(checkWindowSize);
	jQuery(window).resize(checkWindowSize); 
  </script>
</head>

<!--
/*
 * Project: pic2base
 * File: erfassung1.php
 *
 * Copyright (c) 2005 - 2013 Klaus Henneberg
 *
 * Project owner:
 * Klaus Henneberg
 * Finkenweg 18
 * 38889 Blankenburg, BRD
 *
 * All files of this project are licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 */
 -->

<body>
<DIV Class="klein">
<?php
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');
?>

	<div class="page" id="page">
	
		<div class='head' id='head'>
		pic2base :: Einzelbild-Erfassung  <span class='klein'>(User: <?php echo $username;?>)</span>
		</div>
		
		<div class="navi" id="navi">
			<div class="menucontainer">
			<?php
			createNavi1($uid);
			?>
			</div>
		</div>
		
		<div class="content" id="content">
		<p style="margin:50px 0px; text-align:center">
		W&auml;hlen Sie die hochzuladende Datei hier aus:</p>
		
		<center>
		<form name='files' action='erfassung_action.php' method='post' ENCTYPE='multipart/form-data'>
		<table class="normal" border='0'>
		<tbody>
		
		
		
		<tr class="normal">
		<td class="normal" align = 'center' colspan = "2"><u>Wichtiger Hinweis:</u></td>
		</tr>
		
		<tr class="normal">
		<td class="normal" align = 'right' colspan = "2"><BR></td>
		</tr>
		
		<tr class="normal">
		<td class="normal" align = 'left' colspan = "2">Drehen Sie bitte alle Bilder, welche KEINE META-DATEN enthalten vor dem Upload in die lagerichtige (aufrechte) Position!<BR>Nur Bilder, deren Meta-Daten Angaben zur Kamera-Ausrichtung beinhalten, werden automatich in die richtige Position gedreht!<BR>
		Dies finden Sie heraus, indem Sie das betreffende Bild mit einem Bild-Editor (z.B. GIMP) &ouml;ffnen und sich die Bild-Eigenschaften anzeigen lassen. Hier sollte in der Rubrik 'EXIF-Daten' der Parameter 'Orientation' einen numerischen Wert enthalten.<BR><BR></td>
		</tr>
		
		<tr class="normal">
		<td class="normal" width="70">Bild-Datei:</td>
		<td class="normal" align = 'right'><input type='file' name='datei' size='50' accept='image/*'></td>
		</tr>
		
		<tr class="normal">
		<td class="normal"><BR></td>
		<td class="normal"><BR></td>
		</tr>
		
		<tr class="normal">
		<td class="normal"></td>
		<td class="normal" align = 'right'>
		<input type='button' value='Abbrechen' onclick="location.href='../start.php'">&#160;&#160;
		<input type='submit' value='Bild speichern'>
		</td>
		</tr>
		</tbody>
		</table>
		</form>
		</center>
		
		</div>
		<div class='foot' id='foot'>
		<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
		</div>
	
	</div>
</div>
</body>
</html>
