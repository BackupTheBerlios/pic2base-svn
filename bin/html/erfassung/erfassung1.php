<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Willkommen bei pic2base</title>
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="AUTHOR" content="k. henneberg">
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15">
  <link rel=stylesheet type="text/css" href="../../css/format1.css">
</head>

<!--
/*
 * Project: pic2base
 * File: erfassung1.php
 *
 * Copyright (c) 2005 - 2009 Klaus Henneberg
 *
 * Project owner:
 * Klaus Henneberg
 * Finkenweg 18
 * 38889 Blankenburg, BRD
 *
 * All files of this project are licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 * @license http://www.opensource.org/licenses/osl-2.1.php Open Software License
 */
 -->

<body>
<DIV Class="klein">
<?php
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = split(',',$_COOKIE['login']);
//echo $c_username;
}


$ACTION = $_SERVER['PHP_SELF'];
$link = "http://{$_SERVER['SERVER_NAME']}$ACTION";
foreach($_SERVER as $schluessel => $wert)
{
//echo $schluessel." - ".$wert."<BR>";
};
//phpinfo();
?>

<div class="page">

	<p id='kopf'>pic2base :: Einzelbild-Erfassung  <span class='klein'>(User: <?php echo $c_username;?>)</span></p>
	
	<div class="navi" style="clear:right;">
		<div class="menucontainer">
		<?php
		createNavi1($c_username);
		?>
		</div>
	</div>
	
	<div class="content">
	<p style="margin:50px 0px; text-align:center">
	Wählen Sie die hochzuladende Datei hier aus:</p>
	
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
	<br style="clear:both;" />

	<p id='fuss'><A style='margin-right:745px;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A><?php echo $cr; ?></p>

</div>
</div>
</body>
</html>
