<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
	header('Location: ../../../index.php');
}
else
{
	$uid = $_COOKIE['uid'];
}

//soll verhindern, dass nachtraeglich installierte Software immer noch nicht gefunden wird: 
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Datum aus Vergangenheit
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
/*
 * Project: pic2base
 * File: check_software0.php
 *
 * Copyright (c) 2006 - 2013 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 * prueft, ob die erforderliche Software auf dem System verfuegbar ist
 */
?>


<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Software-Check</TITLE>
	<META NAME="GENERATOR" CONTENT="eclipse">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format2.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script language="JavaScript" src="../../share/functions/resize_elements.js"></script>
	<script language="JavaScript" src="../../share/functions/jquery-1.8.2.min.js"></script>
	<script language="JavaScript">
		jQuery.noConflict();
		jQuery(document).ready(checkWindowSize);
		jQuery(window).resize(checkWindowSize); 
	</script>	
	
	<style type="text/css">
	<!--
	.tablenormal	{
			width:450px;
			margin-left:0px;
			}
			
	.trflach	{
			height:3px;
			background-color:darkred
			}
			
	.tdleft	{
			width:120px;
			text-align:left;
			}
			
	.tdright	{
			width:330px;
			text-align:left;
			}
	-->
	</style>
</HEAD>

<BODY>

<CENTER>

<DIV Class="klein">

<?php

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/css/initial_layout_settings.php';

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');

echo "
	<div class='page' id='page'>
	
		<div class='head' id='head'>
			pic2base :: Software-Kontrolle <span class='klein'>(User: ".$username.")</span>
		</div>
		
		<div class='navi' id='navi'>
			<div class='menucontainer'>";
			include $sr.'/bin/html/admin/adminnavigation.php';
			echo "
			</div>
		</div>
		
		<div class='content' id='content'>
			<p id='check_result' style='margin-top:120px; margin-left:10px; text-align:center'>
				Die erforderlichen Software-Komponenten werden gesucht.<br/><br/>Bitte warten Sie einen Moment...
			</p>
		</div>
		
		<div class='foot' id='foot'>
			<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
		</div>
	
	</div>";
$sw_check = checkSoftware($sr);
echo "<input type='hidden' id = 'erg' value = \"$sw_check\">";
?>
</DIV>
</CENTER>
</BODY>
</HTML>

<script language="Javascript">
document.getElementById("check_result").innerHTML=document.getElementById("erg").value;
</script>