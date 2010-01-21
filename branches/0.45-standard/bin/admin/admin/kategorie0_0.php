<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
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
 * File: kategorie0.php
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
 * @copyright 2003-2005 Klaus Henneberg
 * @author Klaus Henneberg
 * @package INTRAPLAN
 * @license http://www.opensource.org/licenses/osl-2.1.php Open Software License
 */

unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = preg_split('#,#',$_COOKIE['login']);
//echo $c_username;
}
 
INCLUDE '../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';

$result1 = mysql_query( "SELECT * FROM $table1 WHERE username = '$c_username' AND aktiv = '1'");
$berechtigung = mysql_result($result1, $i1, 'berechtigung');
SWITCH ($berechtigung)
{
	//Admin
	CASE $berechtigung == '1':
	$navigation = 	"<a class='navi' href='admin0.php'>Kategorien</a>
			<a class='navi' href='erfassung0.php'>Stichworte</a>
			<a class='navi' href='recherche1.php'>Testbereich</a>
			<a class='navi' href='vorschau.php'>Bearbeitung</a>
			<a class='navi' href='hilfe1.php'>Hilfe</a>
			<a class='navi' href='start.php'>Home</a>";
	break;
	
	//alle anderen
	CASE $berechtigung > '1':
	$navigation = 	"<a class='navi' href='../../index.php'>Logout</a>";
	break;
}

//Erzeugung der Baumstruktur:
//Beim Aufruf der Seite werden nur alle Elemente der h�chsten Ebene angezeigt.
//Zun�chst wird die h�chste Ebene ermittelt, diese sollte '0' sein:
$result2 = mysql_query( "SELECT MIN(level) FROM $table4");
$num2 = mysql_num_rows($result2);
$min_level = mysql_result($result2, $i2, 'MIN(level)');
//echo "H�chste Ebene: ".$min_level."<BR>";
//$i2='1';
$result3 = mysql_query( "SELECT * FROM $table4 WHERE level='$min_level'");
$num3 = mysql_num_rows($result3);					//Anzahl Kategorien h�chsten Ebene
echo "<TABLE>";
FOR ($i3=0; $i3<$num3; $i3++)
{
	//echo "Anzahl der Elemente in der Wurzel: ".$num3."; Kat.-ID: ".$kat_id."<BR>";
	IF ($levl == '0')
	{
		
		//Das ist der Zustand beim ersten Aufruf der Seite
		echo "<TR><TD>";
		$kat_ID = mysql_result($result3, $i3, 'kat_id');
		$kategorie = mysql_result($result3, $i3, 'kategorie');
		$parent = mysql_result($result3, $i3, 'parent');
		$level = mysql_result($result3, $i3, 'level') + 1;
		echo "<a href='kategorie0.php?parent=$kat_ID&levl=$level'><IMG src='../share/images/plus.gif' width='11' height='11' hspace='0' vspace='0' border='0'></a>&#160;".$kategorie;
		echo "</TD></TR>";
		
	}
	ELSE
	{
		//echo "Level: ".$levl."<BR>";
		//Der aufgeklappte Baum wird erzeugt. Hierzu wird die Verzweigungs-Tiefe �ber die Position der Ebene benutzt:
		$par_arr[] = $parent;
		$sql_string = "SELECT * FROM $table4 WHERE (parent='$parent' AND level='$levl')";
		FOR ($Z=$levl; $Z>0; $Z--)
		{
			$level = $Z - 1;
			$result4 = mysql_query( "SELECT parent FROM $table4 WHERE kat_id='$parent'");
			$parent = mysql_result($result4, $i4, 'parent');
			$par_arr[]=$parent;
			$sql_string .= " OR (parent='$parent' AND level='$level')";
		}
		echo $sql_string;
		$result5 = mysql_query( $sql_string);
		$num5 = mysql_num_rows($result5);
		
		FOR ($i5=0; $i5<$num5; $i5++)
		{
		echo "<TR><TD>";
		$kat_ID = mysql_result($result5, $i5, 'kat_id');
		$kategorie = mysql_result($result5, $i5, 'kategorie');
		$parent = mysql_result($result5, $i5, 'parent');
		$level = mysql_result($result5, $i5, 'level');
		$space = '';
		FOR ($sp=0; $sp<$level; $sp++)
		{
			$space .= "&#160;&#160;&#160;&#160;";
		}
		FOREACH ($par_arr as $PA)
		{
			//echo $PA."<BR>";
			IF($PA == $kat_ID)
			{
				$img ="<IMG src='../share/images/minus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
				break;
			}
			ELSE
			{
				$img ="<IMG src='../share/images/plus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
				break;
			}
		}
			//$img ="<IMG src='../share/images/plus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
		//echo "Level: ".$level."Levl: ".$levl."<BR>";
		$level++;
		echo $space."<a href='kategorie0.php?parent=$kat_ID&levl=$level'>".$img."</a>&#160;".$kategorie;
		echo "</TD></TR>";
		}
	}
	
}
echo "</TABLE>";
?>

<div class="page">

	<div class="title">
	<!--<img src="" style="float:right;width:156; height:39;margin-left:3px;" alt="Logo">-->
	<h1>pic2base :: Admin-Bereich - Kategorie-Verwaltung</h1>
	</div>
	
	<div class="navi" style="clear:right;">
		<div class="menucontainer">
		<?
		echo $navigation;
		?>
		</div>
	</div>
	
	<div class="content">
	<p style="margin:70px 0px; text-align:center">
	<?php
	echo $content;
	?>
	</p>
	</div>
	<br style="clear:both;" />

	<div class="fuss">
	<p>(C)2006 Logiqu</p>
	</div>

</div>

<?
mysql_close($conn);
?>
<p class="klein">- KH 09/2006 -</P>
</DIV></CENTER>
</BODY>
</HTML>