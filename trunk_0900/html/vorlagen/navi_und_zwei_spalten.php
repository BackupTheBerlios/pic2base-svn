<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - </TITLE>
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
 * File: ###.php
 *
 * Copyright (c) 2006 - 2012 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 */

IF ($_COOKIE['uid'])
{
	$uid = $_COOKIE['uid'];
}
 
include '../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';

$result1 = mysql($db, "SELECT * FROM $table1 WHERE username = '$c_username' AND aktiv = '1'");
$username = mysql_result($result1, isset($i1), 'username');
?>

<div class="page">

	<p id="kopf">pic2base :: </p>
	
	<div class="navi" style="clear:right;">
		<div class="menucontainer">
		<?
		echo $navigation;
		?>
		</div>
	</div>
	
	<div id="spalte1">
		
	</div>
	
	<div id='spalte2'>
	
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