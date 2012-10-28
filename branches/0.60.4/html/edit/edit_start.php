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
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Datensatz-Bearbeitung</TITLE>
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
 * File: edit_start.php
 *
 * Copyright (c) 2003 - 2012 Klaus Henneberg
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
//echo $c_username;
}
*/
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$result1 = mysql_query("SELECT username FROM $table1 WHERE id = '$uid'");
$username = mysql_result($result1, isset($i1), 'username');

echo "
<div class='page'>

	<p id='kopf'>pic2base :: Datensatz-Bearbeitung <span class='klein'>(User: ".$username.")</span></p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
		createNavi3($uid);
		echo "</div>
	</div>
	
	<div id='spalte1'>
		<!--<a class='subnavi' href='#'>EXIF-Daten bearbeiten</a>-->
		<center>
		<a class='subnavi' href='edit_geo_daten.php?pic_id=0&mod=kat' title='Standortbestimmung mittels aufgezeichneter Trackdaten'>Geo-Referenzierung</a>
		<!--<a class='subnavi_blind'></a>-->
		<a class='subnavi' href='edit_bewertung.php?pic_id=0&mod=kat' title='Bilder qualitativ bewerten, Noten vergeben'>Bilder bewerten / Bewertung &auml;ndern</a>
		<!--<a class='subnavi_blind'></a>-->
		<a class='subnavi' href='edit_beschreibung.php?pic_id=0&mod=kat' title='mehreren Bildern eine gemeinsame Beschreibung zuweisen'>Beschreibungen zuweisen</a>
		<!--<a class='subnavi_blind'></a>-->
		<a class='subnavi' href='edit_kat_daten.php?pic_id=0&mod=edit' title='Kategorien zuweisen, Bildauswahl erfolgt nach Kategorien'>Kategorie-Zuweisung - Auswahl nach Kategorien</a>
		<a class='subnavi' href='edit_kat_daten.php?pic_id=0&mod=zeit' title='Kategorien zuweisen, Bildauswahl erfolgt nach Aufnahmedatum'>Kategorie-Zuweisung - Auswahl nach Datum</a>
		<!--<a class='subnavi_blind'></a>-->
		<!--<a class='subnavi' href='edit_remove_kat.php?pic_id=0&mod=kat' title='Bilder aus zugewiesenen Kategorien entfernen'>Kategorie-Zuweisungen aufheben</a>-->
		<a class='subnavi' href='remove_kat_daten.php?pic_id=0&mod=edit_remove' title='Bilder aus zugewiesenen Kategorien entfernen'>Kategorie-Zuweisungen aufheben</a>
		<a class='subnavi_blind'></a>
		<a class='subnavi' href='../erfassung/doublettenliste1.php?user_id=$uid'>Doubletten-Pr&uuml;fung</a>
		<a class='subnavi' href='generate_quickpreview0.php?num=X' title='Service-Funktion'>Quick-Preview hochformatiger Bilder erzeugen</a>
		</center>
	</div>
	
	<div id='spalte2'><p id='elf' style='background-color:white; padding: 5px; width: 365px; margin-top: 4px; margin-left: 10px;'><b>Hinweise zu den Bearbeitungsm&ouml;glichkeiten:</b><BR><BR>
	Ausf&uuml;hrliche Hilfe zu den Bearbeitungsm&ouml;glichkeiten finden Sie &uuml;ber die Navigationsleiste in der <a href='../help/help1.php?page=3'>Online-Hilfe</a>.
	</p>
	</div>

	<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>
</div>";

mysql_close($conn);
?>
</DIV>
</CENTER>
</BODY>
</HTML>