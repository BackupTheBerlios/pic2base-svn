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
	<TITLE>pic2base - Datensatz-Bearbeitung</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: del_pic.php
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

include '../../share/global_config.php'; 
include $sr.'/bin/share/db_connect1.php';

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = utf8_encode(mysql_result($result0, isset($i0), 'username'));

echo "
<div class='page'>

	<p id='kopf'>pic2base :: L&ouml;sche Duplikat (User: $username)</p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>
		</div>
	</div>
	
	<div class='content'>";
	echo "L&ouml;sche Bild ".$pic_id."<BR>";
	//Ermittlung aller zugeh&ouml;rigen Bild-Dateien (Vorschau, HQ-Preview, rotiertes Bild, Original-Bild):
	$result1 = mysql_query( "SELECT * FROM $table2 WHERE pic_id = '$pic_id'");
	$num1 = mysql_num_rows($result1);
	IF($num1 == '1')
	{
		$filenameori = mysql_Result($result1, $i1, 'FileNameOri');
		$filename = mysql_Result($result1, $i1, 'FileName');
		$filenamehq = mysql_Result($result1, $i1, 'FileNameHQ');
		$filenamev = mysql_Result($result1, $i1, 'FileNameV');
		//loeschen der Bild-Dateien:
		IF(@unlink($pic_path.'/'.$filename))
		{
			echo "Originalbild wurde gel&ouml;scht.<BR>";
		}
		ELSE
		{
			echo "ACHTUNG! Das Originalbild konnte nicht gel&ouml;scht werden!<BR>";
		}
		
		IF(@unlink($pic_rot_path.'/'.$filename))
		{
			echo "Das rotierte Originalbild wurde gel&ouml;scht.<BR>";
		}
		ELSE
		{
			echo "ACHTUNG! Das rotierte Originalbild konnte nicht gel&ouml;scht werden!<BR>";
		}
		
		IF(@unlink($pic_thumbs_path.'/'.$filenamev))
		{
			echo "Vorschaubild wurde gel&ouml;scht.<BR>";
		}
		ELSE
		{
			echo "ACHTUNG! Das Vorschaubild konnte nicht gel&ouml;scht werden!<BR>";
		}
		
		IF(@unlink($pic_hq_path.'/'.$filenamehq))
		{
			echo "HQ-Vorschaubild wurde gel&ouml;scht.<BR>";
		}
		ELSE
		{
			echo "ACHTUNG! Das HQ-Vorschaubild konnte nicht gel&ouml;scht werden!<BR>";
		}
		
		//loeschen des Bild-Datensatzes
		$result2 = mysql_query( "DELETE FROM $table2 WHERE pic_id = '$pic_id'");
		
		//loeschen der Kategorie-Zuordnung
		$result3 = mysql_query( "DELETE FROM $table10 WHERE pic_id = '$pic_id'");
		
		echo "<meta http-equiv='Refresh' Content='1; URL=double_check0.php'>";
	}
	ELSE
	{
		echo "Es gibt keine oder mehrere Dateien mit dieser Bild-ID!<BR>Der Vorgang wird abgebrochen.";
	}
	echo "
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