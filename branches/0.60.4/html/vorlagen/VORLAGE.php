<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Startseite</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../css/format1.css'>
	<link rel="shortcut icon" href="../share/images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>
<DIV Class="klein">

<?

/*
 * Project: pic2base
 * File: vorlage.php
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
 * @copyright 2006-2007 Klaus Henneberg
 * @author Klaus Henneberg
 * @package pic2base
 * @license http://www.opensource.org/licenses/osl-2.1.php Open Software License
 */

unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = preg_split('#,#',$_COOKIE['login']);
//echo $c_username;
}
include '../share/user_check1.php';
include '../share/db_connect1.php';

echo "
<div class='page'>

	<p id='kopf'>pic2base - Startseite</p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>
		<a class='navi' href='erfassung1.php'>Erfassung</a>
		<a class='navi' href='recherche1.php'>Recherche</a>
		<a class='navi' href='vorschau.php'>Bearbeitung</a>
		<a class='navi' href='hilfe1.php'>Hilfe</a>
		<a class='navi' href='index.php'>Logout</a>
		</div>
	</div>
	
	<div class='content'>
	<p style='margin:170px 0px; text-align:center'>Willkommen bei pic2base, der web-basierten Bild-Archivierung von Logiqu!</p>
	</div>
	<br style='clear:both;' />

	<p id='fuss'><A style='margin-right:745px;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>".$cr."</p>

</div>";

mysql_close($conn);

?>
</DIV>
</CENTER>
</BODY>
</HTML>