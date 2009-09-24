<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Datensatz-Bearbeitung</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll = "auto">
<CENTER>
<DIV Class="klein">

<?
// php 5.3
/*
 * Project: pic2base
 * File: start.php
 *
 * Copyright (c) 2003 - 2005 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 */

unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = split(',',$_COOKIE['login']);
//echo $c_username;
}
 
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

//log-file schreiben:
$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
fwrite($fh,date('d.m.Y H:i:s')." ".$REMOTE_ADDR." ".$_SERVER['PHP_SELF']." ".$_SERVER['HTTP_USER_AGENT']." ".$c_username."\n");
fclose($fh);

?>

<div class="page">

	<p id="kopf">pic2base :: Datensatz-Bearbeitung <span class='klein'>(User: <?echo $c_username;?>)</span></p>
	
	<div class="navi" style="clear:right;">
		<div class="menucontainer">
		<?
		createNavi3($c_username);
		//echo $navigation;
		?>
		</div>
	</div>
	
	<div id="spalte1">
		<!--<a class='subnavi' href='#'>EXIF-Daten bearbeiten</a>-->
		<center>
		<a class='subnavi' href='edit_geo_daten.php?pic_id=0&mod=kat'>Geo-Referenzierung</a>
		<a class='subnavi' href='edit_bewertung.php?pic_id=0&mod=kat'>Bilder bewerten / Bewertung &auml;ndern</a>
		<a class='subnavi' href='edit_beschreibung.php?pic_id=0&mod=kat'>Beschreibungen zuweisen / &auml;ndern</a>
		<a class='subnavi' href='edit_kat_daten.php?pic_id=0&mod=edit'>Kategorien zuweisen</a>
		<a class='subnavi' href='edit_remove_kat.php?pic_id=0&mod=kat'>Kategorie-Zuweisungen aufheben</a>
		<a class='subnavi_blind'></a>
		<!--<a class='subnavi' href='double_check0.php'>Doubletten-Pr&uuml;fung</a>-->
		<a class='subnavi' href='generate_quickpreview0.php?num=X'>Quick-Preview hochformatiger Bilder erzeugen</a>
		</center>
	</div>
	
	<div id='spalte2'><p id="elf" style="background-color:white; padding: 5px; width: 365px; margin-top: 4px; margin-left: 10px;"><b>Hinweise zu den Bearbeitungsm&ouml;glichkeiten:</b><BR><BR>
	Ausf&uuml;hrliche Hilfe zu den Bearbeitungsm&ouml;glichkeiten finden Sie &uuml;ber die Navigationsleiste in der <a href='../help/help1.php?page=3'>Online-Hilfe</a>.
	</p>
	</div>

	<p id="fuss"><A style='margin-right:745px;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A><?php echo $cr; ?></p>

</div>

<?
mysql_close($conn);
?>
</DIV>
</CENTER>
</BODY>
</HTML>