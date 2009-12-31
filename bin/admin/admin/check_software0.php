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
			width:400px;
			margin-left:200px;
			}
			
	.trflach	{
			height:3px;
			background-color:#FF9900
			}
			
	.tdleft	{
			width:120px;
			text-align:left;
			}
			
	.tdright	{
			width:280px;
			text-align:left;
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
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

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