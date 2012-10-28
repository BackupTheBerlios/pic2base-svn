<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Log-Datei</TITLE>
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
 * File: p2b_log.php
 *
 * Copyright (c) 2006 - 2012 Klaus Henneberg
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
}
*/
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';

echo "
<div class='page'>

	<p id='kopf'>pic2base :: Log-Datei</p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>
			<a class = 'navi_blind'></a>
			<a class = 'navi_blind'></a>
			<a class = 'navi_blind'></a>
			<a class = 'navi_blind'></a>
			<a class = 'navi_blind'></a>
			<a class = 'navi_blind'></a>
			<a class = 'navi' href='../../html/admin/adminframe.php'>Zur&uuml;ck</a>
			<a class = 'navi_blind'></a>
			<a class = 'navi_blind'></a>
			<a class = 'navi_blind'></a>
			<a class = 'navi_blind'></a>
			<a class = 'navi_blind'></a>
			<a class = 'navi_blind'></a>
			<a class='navi' href='../../html/start.php'>zur Startseite</a>
			<a class='navi' href='../../html/help/help1.php?page=5'>Hilfe</a>
			<a class='navi' href='../../../index.php'>Logout</a>
		</div>
	</div>

	<div class='content'>
	<p style='margin:20px 0px; text-align:center'><iframe src='../../../log/p2b.log' frameborder='0' style='width:750px; height:550px; border:thin solid grey;'>Ihr Browser unterst&uuml;tzt leider keine eingebetteten Frames.
	</iframe>
	</div>
	<br style='clear:both;' />

	<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>
</div>";
?>
</DIV>
</CENTER>
</BODY>
</HTML>
