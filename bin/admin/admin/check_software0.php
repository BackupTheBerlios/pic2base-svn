<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Software-Check</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
	<!--<meta http-equiv="Refresh" Content="3600; URL=generate_exifdata0.php">-->
	<style type="text/css">
	<!--
	.tablenormal	{
			width:200px;
			margin-left:280px;
			}
			
	.trflach	{
			height:3px;
			background-color:#FF9900
			}
			
	.tdleft	{
			width:80px;
			text-align:left;
			}
			
	.tdright	{
			width:120px;
			text-align:center;
			}
	-->
	</style>
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: check_software0.php
 *
 * Copyright (c) 2006 - 2009 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 *pr&uuml;ft, ob die erforderliche Software auf dem System verf&uuml;gbar ist
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

//####################################################################################################
/*
function checkSoftware()
{
	//Kontrolle, ob erforderliche Software-Komponenten installiert sind:
	
	$et = shell_exec("rpm -q exiftool");
	$im = shell_exec("rpm -q ImageMagick");
	$dc = shell_exec("rpm -q dcraw");
	$gb = shell_exec("rpm -q gpsbabel");
	$loc = shell_exec("rpm -q kio-locate");
	
	echo "	<TABLE class='tablenormal' border='0'>
		<TR>
		<TD colspan='2'>Ergebnis der Software-Kontrolle:</TD>
		</TR>
		
		<TR class='trflach'>
		<TD colspan='2'></TD>
		</TR>";
	
	IF(stristr($loc, 'package'))
	{
		echo "<TR>
		<TD class='tdleft'>locate</TD>
		<TD class='tdright'><FONT COLOR='red'>nicht installiert</FONT></TD>
		</TR>";
	}
	ELSE
	{
		$loc_db = shell_exec("locate locatedb");
		IF($loc_db == '')
		{
			shell_exec("updatedb");
		}
		echo "<TR>
		<TD class='tdleft'>locate</TD>
		<TD class='tdright'><FONT COLOR='green'>ist installiert</FONT></TD>
		</TR>";
	}
	
	IF(stristr($et, 'package'))
	{
		$et_test = shell_exec("locate *exiftool");
		IF($et_test == '')
		{
			echo "<TR>
			<TD class='tdleft'>ExifTool</TD>
			<TD class='tdright'><FONT COLOR='red'>nicht installiert</FONT></TD>
			</TR>";
		}
		ELSE
		{
			echo "<TR>
			<TD class='tdleft'>ExifTool</TD>
			<TD class='tdright'><FONT COLOR='green'>ist installiert</FONT></TD>
			</TR>";
		}
	}
	ELSE
	{
	echo "<TR>
		<TD class='tdleft'>ExifTool</TD>
		<TD class='tdright'><FONT COLOR='green'>ist installiert</FONT></TD>
		</TR>";
	}
	
	IF(stristr($im, 'package'))
	{
		echo "<TR>
		<TD class='tdleft'>ImageMagick</TD>
		<TD class='tdright'><FONT COLOR='red'>nicht installiert</FONT></TD>
		</TR>";
	}
	ELSE
	{
		echo "<TR>
		<TD class='tdleft'>ImageMagick</TD>
		<TD class='tdright'><FONT COLOR='green'>ist installiert</FONT></TD>
		</TR>";
	}
	
	IF(stristr($dc, 'package'))
	{
		echo "<TR>
		<TD class='tdleft'>dcraw</TD>
		<TD class='tdright'><FONT COLOR='red'>nicht installiert</FONT></TD>
		</TR>";
	}
	ELSE
	{
		echo "<TR>
		<TD class='tdleft'>dcraw</TD>
		<TD class='tdright'><FONT COLOR='green'>ist installiert</FONT></TD>
		</TR>";
	}
	
	IF(stristr($gb, 'package'))
	{
		$gb_test = shell_exec("locate *gpsbabel");
		IF($gb_test == '')
		{
			echo "<TR>
			<TD class='tdleft'>GPSBabel</TD>
			<TD class='tdright'><FONT COLOR='red'>nicht installiert</FONT></TD>
			</TR>";
		}
		ELSE
		{
			echo "<TR>
			<TD class='tdleft'>GPSBabel</TD>
			<TD class='tdright'><FONT COLOR='green'>ist installiert</FONT></TD>
			</TR>";
		}
	}
	
	echo "	<TR class='trflach'>
		<TD colspan='2'></TD>
		</TR>
		</TABLE>";
}
*/
//####################################################################################################

echo "
	<div class='page'>
	
		<p id='kopf'>pic2base :: Software-Kontrolle <span class='klein'>(User: ".$c_username.")</span></p>
		
		<div class='navi' style='clear:right;'>
			<div class='menucontainer'>";
			include '../../html/admin/adminnavigation.php';
			echo "
			</div>
		</div>
		
		<div class='content'>
		<p style='margin-top:120px; margin-left:10px; text-align:center'>";
		checkSoftware();
		echo "</p>
		</div>
		<br style='clear:both;' />
	
		<p id='fuss'><A style='margin-right:745px;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>".$cr." </p>
	
	</div>
</DIV>
</CENTER>
</BODY>
</HTML>";
?>