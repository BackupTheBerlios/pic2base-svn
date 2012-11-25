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
	<TITLE>pic2base - Datenerfassung</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<DIV>

<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: erfassung_action.php
 *
 * Copyright (c) 2003 - 2012 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 * @license http://www.opensource.org/licenses/osl-2.1.php Open Software License
*/
//################################################################################################################################
/*
Es werden die folgenden Schritte abgearbeitet:
die Datei wird in den Upload-Ordner des Users heladen und dann der Stapel-Upload gestartet
*/
//################################################################################################################################

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$result1 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result1, isset($i1), 'username');

/*
Dateinamens-Konventionen:
$datei_name		Name der hochzuladenden Datei
$datei			Name der Datei im Cache
*/

//var_dump($_FILES);
$datei_name = $_FILES['datei']['name'];
$tmp_name = $_FILES['datei']['tmp_name'];
//echo "Name der hochzuladenden Datei: ".$datei_name."<BR>";
//echo " Name der Datei im Cache: ".$datei."<BR>";

IF ($datei_name != "" && $datei_name !='.' && $datei_name != '..')
{
	$target = $ftp_path."/".$uid."/uploads/".$datei_name;
	
	if ( !move_uploaded_file($tmp_name,$target) )
	{
		switch ($_FILES['datei']['error']) 
		{
			case UPLOAD_ERR_INI_SIZE:
				$fehler = "Die Dateigr&ouml;&szlig;e des Bildes: ".$datei_name." ist zu gro&szlig;!";
				break;
			case UPLOAD_ERR_FORM_SIZE:
				$fehler = "Die Dateigr&ouml;&szlig;e des Bildes: ".$datei_name." ist zu gro&szlig;!";
				break;
			case UPLOAD_ERR_PARTIAL:
				$fehler = "Das Bild: ".$datei_name." konnte nicht vollst&auml;ndig hochgeladen werden!";
				break;
			case UPLOAD_ERR_NO_FILE:
				$fehler = "Das Bild: ".$datei_name." konnte nicht hochgeladen werden!";
				break;
			case UPLOAD_ERR_NO_TMP_DIR:
				$fehler = "Das Bild: ".$datei_name." konnte nicht hochgeladen werden: Es fehlt das tempor&auml;re Verzeichnis!";
				break;
			case UPLOAD_ERR_CANT_WRITE:
				$fehler = "Das Bild: ".$datei_name." konnte nicht hochgeladen werden: Schreibfehler auf dem Server!";
				break;
			case UPLOAD_ERR_EXTENSION:
				$fehler = "Das Bild: ".$datei_name." konnte nicht hochgeladen werden: File upload stopped by extension.";
				break;
			default:
				$fehler = "Das Bild: ".$datei_name." konnte nicht hochgeladen werden: Unknown upload error.";
				break;
		} 		
		echo "
		<div class='page'>
		<p id='kopf'>pic2base :: Hinweis  <span class='klein'>(User: ".$username.")</span></p>
			<div class='navi' style='clear:right;'>
				<div class='menucontainer'>
				<!--<a class='navi' href='erfassung1.php'>Erfassung</a>
				<a class='navi' href='recherche1.php'>Recherche</a>
				<a class='navi' href='vorschau.php'>Bearbeitung</a>
				<a class='navi' href='hilfe1.php'>Hilfe</a>
				<a class='navi' href='index.php'>Logout</a>-->
				</div>
			</div>
		
			<div class='content'>
			<p style='margin:80px 0px; text-align:center; color:red;'>".$fehler."<p style='text-align:center; color:black;'>
			Bitte w&auml;hlen Sie eine andere Bilddatei aus!<BR><BR>
			<INPUT TYPE='button' value='Zur&uuml;ck' onclick=\"location.href='javascript:history.back()'\"></P>
			</p>
			</div>
			<br style='clear:both;' />
			<p id='fuss'><A style='margin-right:745px;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>".$cr."</p>
		</div>";
	}
	else
	{
		clearstatcache();
		// alt:	chmod($tmp_file, 0777);
		chmod($target, 0777);
		clearstatcache();
	
		echo "
		<div class='page'>
			<p id='kopf'>pic2base :: Hinweis  <span class='klein'>(User: ".$username.")</span></p>
		
			<div class='navi' style='clear:right;'>
				<div class='menucontainer'>
				<!--<a class='navi' href='erfassung1.php'>Erfassung</a>
				<a class='navi' href='recherche1.php'>Recherche</a>
				<a class='navi' href='vorschau.php'>Bearbeitung</a>
				<a class='navi' href='hilfe1.php'>Hilfe</a>
				<a class='navi' href='index.php'>Logout</a>-->
				</div>
			</div>
		
			<div class='content'>
			<p style='margin:70px 0px; text-align:center'>".isset($meldung)."<BR></p>
			<p style='margin:70px 0px; text-align:center; color:green;'>".$datei_name.isset($hinweis)." wurde erfolgreich hochgeladen.<BR></p>";
		
			echo "
			</div>
			<br style='clear:both;' />
			<p id='fuss'>".$cr."</p>
		</div>
		<meta http-equiv='Refresh' Content='1; URL=stapel2.php?ordner=$ftp_path/$uid/uploads'>";
	}
}
ELSE
{
	echo "
	<div class='page'>
		<p id='kopf'>pic2base :: Hinweis  <span class='klein'>(User: ".$username.")</span></p>
		
		<div class='navi' style='clear:right;'>
			<div class='menucontainer'>
			<!--<a class='navi' href='erfassung1.php'>Erfassung</a>
			<a class='navi' href='recherche1.php'>Recherche</a>
			<a class='navi' href='vorschau.php'>Bearbeitung</a>
			<a class='navi' href='hilfe1.php'>Hilfe</a>
			<a class='navi' href='index.php'>Logout</a>-->
			</div>
		</div>
		
		<div class='content'>
		<p style='margin:80px 0px; text-align:center'>
		Bitte w&auml;hlen Sie eine Bilddatei aus!<BR><BR>
		<INPUT TYPE='button' value='Zur&uuml;ck' onclick=\"location.href='javascript:history.back()'\"></P>
		</p>
		</div>
		<br style='clear:both;' />
		<p id='fuss'><A style='margin-right:745px;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>".$cr."</p>
	</div>";
}
mysql_close($conn);
?>
</DIV></CENTER>
</BODY>
</HTML>