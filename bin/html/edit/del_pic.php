<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Datensatz-Bearbeitung</TITLE>
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
 * File: del_pic.php
 *
 * Copyright (c) 2006 - 2007 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 * @copyright 2003-2005 Klaus Henneberg
 * @author Klaus Henneberg
 * @package pic2base
 * @license http://www.opensource.org/licenses/osl-2.1.php Open Software License
 */

unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = split(',',$_COOKIE['login']);
//echo $c_username;
}
include '../../share/global_config.php'; 
include $sr.'/bin/share/db_connect1.php';


echo "
<div class='page'>

	<p id='kopf'>pic2base :: Lösche Duplikat (User: $c_username)</p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>
		</div>
	</div>
	
	<div class='content'>";
	echo "Lösche Bild ".$pic_id."<BR>";
	//Ermittlung aller zugehörigen Bild-Dateien (Vorschau, HQ-Preview, rotiertes Bild, Original-Bild):
	$result1 = mysql($db, "SELECT * FROM $table2 WHERE pic_id = '$pic_id'");
	$num1 = mysql_num_rows($result1);
	IF($num1 == '1')
	{
		$filenameori = mysql_Result($result1, $i1, 'FileNameOri');
		$filename = mysql_Result($result1, $i1, 'FileName');
		$filenamehq = mysql_Result($result1, $i1, 'FileNameHQ');
		$filenamev = mysql_Result($result1, $i1, 'FileNameV');
		//löschen der Bild-Dateien:
		IF(@unlink($path_copy.'/'.$filename))
		{
			echo "Originalbild wurde gelöscht.<BR>";
		}
		ELSE
		{
			echo "ACHTUNG! Das Originalbild konnte nicht gelöscht werden!<BR>";
		}
		
		IF(@unlink($path_copy.'/rotated/'.$filename))
		{
			echo "Das rotierte Originalbild wurde gelöscht.<BR>";
		}
		ELSE
		{
			echo "ACHTUNG! Das rotierte Originalbild konnte nicht gelöscht werden!<BR>";
		}
		
		IF(@unlink($vorschau_verzeichnis.'/'.$filenamev))
		{
			echo "Vorschaubild wurde gelöscht.<BR>";
		}
		ELSE
		{
			echo "ACHTUNG! Das Vorschaubild konnte nicht gelöscht werden!<BR>";
		}
		
		IF(@unlink($HQ_verzeichnis.'/'.$filenamehq))
		{
			echo "HQ-Vorschaubild wurde gelöscht.<BR>";
		}
		ELSE
		{
			echo "ACHTUNG! Das HQ-Vorschaubild konnte nicht gelöscht werden!<BR>";
		}
		
		//löschen des Bild-Datensatzes
		$result2 = mysql($db, "DELETE FROM $table2 WHERE pic_id = '$pic_id'");
		
		//löschen der Kategorie-Zuordnung
		$result3 = mysql($db, "DELETE FROM $table10 WHERE pic_id = '$pic_id'");
		
		echo "<meta http-equiv='Refresh' Content='1; URL=double_check0.php'>";
	}
	ELSE
	{
		echo "Es gibt keine oder mehrere Dateien mit dieser Bild-ID!<BR>Der Vorgang wird abgebrochen.";
		//echo "<meta http-equiv='Refresh' Content=2; URL=double_check0.php'>";
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