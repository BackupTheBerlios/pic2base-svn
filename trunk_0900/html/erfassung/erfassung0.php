<?php
IF (!$_COOKIE['uid'])
{
include '../../share/global_config.php';
//var_dump($sr);
  header('Location: ../../../index.php');
}
$uid = $_COOKIE['uid'];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>pic2base - Erfassung</title>
  <meta name="GENERATOR" content="eclipse">
  <meta name="AUTHOR" content="k. henneberg">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel=stylesheet type="text/css" href="../../css/format2.css">
  <link rel="shortcut icon" href="../../share/images/favicon.ico">
  <script language="JavaScript" src="../../share/functions/resize_elements.js"></script>
  <script language="JavaScript" src="../../share/functions/jquery-1.8.2.min.js"></script>
  <script language="JavaScript">
  	jQuery.noConflict()
	jQuery(document).ready(checkWindowSize);
	jQuery(window).resize(checkWindowSize); 
  </script>
</head>

<!--
/*
 * Project: pic2base
 * File: erfassung0.php
 *
 * Copyright (c) 2005 - 2013 Klaus Henneberg
 *
 * Project owner:
 * Klaus Henneberg
 * Finkenweg 18
 * 38889 Blankenburg, BRD
 *
 * All files of this project are licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 */
 -->

<BODY>
<DIV Class="klein">
<?php

$ACTION = $_SERVER['PHP_SELF'];
$link = "http://{$_SERVER['SERVER_NAME']}$ACTION";

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$result1 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result1, isset($i1), 'username');

echo "
<div class='page' id='page'>

	<div class='head' id='head'>
	pic2base :: Bilddaten-Erfassung <span class='klein'>(User: $username)</span>
	</div>
	
	<div class='navi' id='navi'>
		<div class='menucontainer'>";
		createNavi1($uid);
		echo "</div>
	</div>
	
	<div class='content' id='content'>
		<center>
		<table class='normal' style='background-color:rgb(255,250,150); color:black; margin-top:80px; margin-bottom:40px; border-style:solid; border-width:2px; border-color:#FF9900;'>
		<tbody>
		<TR>
			<TD align='center'>
			Wichtiger Hinweis:<BR>
			Akzeptiert werden die in der <a href=\"../help/help1.php?page=1\" title='Liste der unterst&uuml;tzen Dateiformate'>Hilfe</a> gelisteten Dateiformate, wobei die Dateigr&ouml;&szlig;e<BR>
			von 5 MB je Bild beim Einzelbild-Upload nicht &uuml;berschritten werden darf.<BR>
			Wollen SIe gr&ouml;&szlig;ere Bilder oder mehrere Bilder in einem Vorgang auf den Server laden, benutzen Sie bitte den <a href=\"../help/help1.php?page=1\" title='Hinweise zum Batch-Prozess per FTP'>Batch-Prozess</a>.
			</TD>
		</TR>
		</tbody>
		</table>
		
		<INPUT type='button' class='button1' value='Zum Einzelbild-Upload' onclick=location.href='erfassung1.php'><BR><BR><BR>
		</center>
	</div>
	
	<div class='foot' id='foot'>
	<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
	</div>
</div>";
?>

</div>
</body>
</html>
