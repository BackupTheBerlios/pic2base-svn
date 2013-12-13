<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Log-Datei</TITLE>
	<META NAME="GENERATOR" CONTENT="eclipse">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format2.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script language="JavaScript" src="../../share/functions/resize_elements.js"></script>
	<script language="JavaScript" src="../../share/functions/jquery-1.8.2.min.js"></script>
	<script language="JavaScript">
	  	jQuery.noConflict()
		jQuery(document).ready(checkWindowSize);
		jQuery(window).resize(checkWindowSize); 
	</script>
</HEAD>

<BODY>

<CENTER>

<DIV Class="klein">

<?

/*
 * Project: pic2base
 * File: p2b_log.php
 *
 * Copyright (c) 2006 - 2013 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 */

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';

echo "
<div class='page' id='page'>

	<div class='head' id='head'>
		pic2base :: pic2base :: Log-Datei
	</div>
	
	<div class='navi' id='navi'>
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

	<div style=''>
		<p style='margin:10px 0px; text-align:center'>
		<iframe id='log_frame' src='../../../log/p2b.log' frameborder='0' style='width:750px; height:550px; border:thin solid grey; background-color:RGB(240,240,240);'>Ihr Browser unterst&uuml;tzt leider keine eingebetteten Frames.
		</iframe>
	</div>
	
	<div class='foot' id='foot'>
		<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
	</div>
	
</div>
</DIV>
</CENTER>
</BODY>
</HTML>";
?>

