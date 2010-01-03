<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Kategorie-Verwaltung</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?

/*
 * Project: pic2base
 * File: katrepair1.php
 *
 * Copyright (c) 2003 - 2005 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 * @copyright 2003-2005 Klaus Henneberg
 * @author Klaus Henneberg
 * @package INTRAPLAN
 * @license http://www.opensource.org/licenses/osl-2.1.php Open Software License
 */

unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = preg_split('#,#',$_COOKIE['login']);
//echo $c_username;
}
 
include '../../share/db_connect1.php';
INCLUDE '../../share/global_config.php';

$result1 = mysql_query( "SELECT * FROM $table1 WHERE username = '$c_username' AND aktiv = '1'");
$berechtigung = mysql_result($result1, isset($i1), 'berechtigung');
SWITCH ($berechtigung)
{
	//Admin
	CASE $berechtigung == '1':
	$navigation = 	"
			<a class='navi_blind'></a>
			<a class='navi' href='../../html/admin/adminframe.php'>Zur&uuml;ck</a>
			<a class='navi' href='../../html/start.php'>zur Startseite</a>";
	break;
	
	//alle anderen
	CASE $berechtigung > '1':
	$navigation = 	"<a class='navi' href='../../../index.php'>Logout</a>";
	break;
}

?>

<div class="page">

	<p id="kopf">pic2base :: Admin-Bereich - Datenbank-Wartung</p>
	
	<div class="navi" style="clear:right;">
		<div class="menucontainer">
		<?
		echo $navigation;
		?>
		</div>
	</div>
	
	<div id="spalte1">
	<?php
	//Wartungs-Routine zur Bereinigung der pic-kat-Tabelle, wenn Bilder existieren, welchen nur die kat_id = 1 zugewiesen wurde. Dies ist gleichbedeutend, dass den Bildern noch KEINE Kategorie zugewiesen wurden.
	$Z = '0';
	$result10 = mysql_query( "SELECT DISTINCT pic_id FROM $table10");
	$num10 = mysql_num_rows($result10);
	echo "Gefundene Eintr&auml;ge in der Bild-Kategorie-Tabelle: ".$num10."<BR><BR>";
	FOR($i10='0'; $i10<$num10; $i10++)
	{
		$pic_id = mysql_result($result10, isset($i10), 'pic_id');
		//echo $pic_id."<BR>";
		$result11 = mysql_query( "SELECT * FROM $table10 WHERE pic_id = '$pic_id'");
		$num11 = mysql_num_rows($result11);
		IF($num11 == '1')
		{
			$result12 = mysql_query( "SELECT * FROM $table10 WHERE pic_id = '$pic_id' AND kat_id = '1'");
			$num12 = mysql_num_rows($result12);
			IF($num12 == '1')
			{
				echo "Bild ".$pic_id." ist einmal in der pic_kat mit der Kategorie 1 gelistet.<BR>";
				$Z++;
			}
		}
	}
	IF($Z == '0')
	{
		$meldung = "Es gab keine fehlerhaften Datens&auml;tze.<BR>";
	}
	ELSE
	{
		$meldung = "Anzahl der fehlerhaften und korrigierten Datens&auml;tze: ".$Z."<BR>";
	}
	echo $meldung."
	</div>
	
	<DIV id='spalte2'>
		<p id='elf' style='background-color:white; padding: 5px; width: 365px; margin-top: 20px; margin-left: 20px;'>Hinweis:<BR><BR>
		Die &Uuml;berpr&uuml;fung ist abgeschlossen<BR>und lieferte die links stehende Ergebnis-Meldung.</p>
	</DIV>
	
	<p id='fuss'><?php echo $cr; ?></p>

</div>";

mysql_close($conn);
?>
<p class="klein">- KH 09/2006 -</P>
</DIV></CENTER>
</BODY>
</HTML>