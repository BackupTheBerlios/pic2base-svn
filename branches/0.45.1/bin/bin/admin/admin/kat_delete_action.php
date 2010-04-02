<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Startseite</TITLE>
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
 * File: kat_delete_action.php
 *
 * Copyright (c) 2003 - 2008 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 */

unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = preg_split('#,#',$_COOKIE['login']);
//echo $c_username;
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';

$result1 = mysql_query( "SELECT * FROM $table1 WHERE username = '$c_username' AND aktiv = '1'");
$berechtigung = mysql_result($result1, isset($i1), 'berechtigung');
SWITCH ($berechtigung)
{
	//Admin
	CASE $berechtigung == '1':
	$navigation = "<a class='navi' href='kategorie0.php?kat_id=0'>Zur&uuml;ck</a>";
	break;
}

//Ausgehend von der gew�hlten Kategorie m�ssen alle Unterkategorien bestimmt werden. Dann m�ssen aus der Tabelle 10 (pic_kat) alle Eintr�ge gel�scht werden, welche auf die gew�hlte Kategorie bzw. deren Unterkategorie verweisen.

// f�r register_globals = off
$ID = $_GET['ID']; 
if(array_key_exists('kat_id',$_GET))
{
	$kat_id = $_GET['kat_id']; 
}

$res1 = mysql_query( "SELECT max(level) FROM $table4");
$max_level = mysql_result($res1, isset($i1), 'max(level)');
//echo "max. Level: ".$max_level."<BR>";
$result2 = mysql_query( "SELECT * FROM $table4 WHERE parent = '$ID'");
$num2 = mysql_num_rows($result2);
$child_arr[] = $ID;
IF($num2 > '0')
{
	$curr_level = mysql_result($result2, isset($i2), 'level');
	WHILE($curr_level <= $max_level)
	{
		FOREACH($child_arr AS $child)
		{
			$result3 = mysql_query( "SELECT * FROM $table4 WHERE parent = '$child' AND level = '$curr_level'");
			$num3 = mysql_num_rows($result3);
			IF($num3 > '0')
			{
				FOR($i3='0'; $i3<$num3; $i3++)
				{
					$child_arr[] = mysql_result($result3, $i3, 'kat_id');	
				}
			}
		}
		$curr_level++;
	}
}

$pic_arr = array();
FOREACH($child_arr AS $child)
{
	$res3 = mysql_query( "SELECT * FROM $table4 WHERE kat_id = '$child'");
	if(!isset($u_kategorie))
	{
		$u_kategorie = '';
	}
	$u_kategorie .= mysql_result($res3, isset($i), 'kategorie')."<BR>";
	//echo $child."<BR>";			//$child - Nummer der zu l�schenden Kategorie
	//L�schvorgang der Kategorie in der Kategorie-Tabelle ($table4):
	$result4 = mysql_query( "DELETE FROM $table4 WHERE kat_id = '$child'");
	//Eintrag aus der kat_lex l�schen:
	$result6 = mysql_query( "DELETE FROM $table11 WHERE kat_id = '$child'");
	
	$res4 = mysql_query( "SELECT * FROM $table10 WHERE kat_id = '$child'");
	$num4 = mysql_num_rows($res4); 
	FOR($i4='0'; $i4<$num4; $i4++)
	{
		$pic_id = mysql_result($res4, $i4, 'pic_id');
		IF(!in_array($pic_id, $pic_arr))
		{
			$pic_arr[] = $pic_id;
		}
	}
}

//l�schen der Bild-Zuordnungen in der pic_kat-Tabelle:
FOREACH($pic_arr AS $pic)
{
	FOREACH($child_arr AS $child)
	{
		$result5 = mysql_query( "DELETE FROM $table10 WHERE pic_id = '$pic' AND kat_id = '$child'"); 
	}
}

echo "
<div class='page'>

	<p id='kopf'>pic2base :: Admin-Bereich - Kategorie l&ouml;schen</p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>". $navigation."</div>
	</div>
	
	<div class='content'>
	<p style='margin:20px 0px; text-align:center'>Die folgenden Kategorien wurden gel&ouml;scht:<BR><BR>".$u_kategorie."<BR><BR>".
	count($pic_arr)." Bildzuordnung(en) wurde(n) aufgehoben.</p>
	</div>
	<br style='clear:both;' />

	<p id='fuss'><?php echo $cr; ?></p>

</div>";

mysql_close($conn);
?>
</DIV>
</CENTER>
</BODY>
</HTML>