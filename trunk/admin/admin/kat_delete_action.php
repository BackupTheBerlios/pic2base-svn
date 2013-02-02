<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Startseite</TITLE>
	<META NAME="GENERATOR" CONTENT="eclipse">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format2.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script language="JavaScript" src="../share/functions/resize_elements.js"></script>
	<script language="JavaScript" src="../share/functions/jquery-1.8.2.min.js"></script>
	<script language="JavaScript">
	  	jQuery.noConflict()
		jQuery(document).ready(checkWindowSize);
		jQuery(window).resize(checkWindowSize); 
	</script>
</HEAD>

<BODY LANG="de-DE" scroll = "auto">
<CENTER>
<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: kat_delete_action.php
 *
 * Copyright (c) 2003 - 2012 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 */

IF ($_COOKIE['uid'])
{
	$uid = $_COOKIE['uid'];
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/permissions.php';
include $sr.'/bin/css/initial_layout_settings.php';

IF(hasPermission($uid, 'editkattree', $sr))
{
	$navigation = "
	<a class='navi' href='kat_sort1.php'>Sortierung</a>
	<a class='navi' href='db_wartung1.php'>Wartung</a>
	<a class='navi' href='../../html/admin/adminframe.php'>Zur&uuml;ck</a>
	<a class='navi_blind'></a>
	<a class='navi_blind'></a>
	<a class='navi_blind'></a>
	<a class='navi_blind'></a>
	<a class='navi' href='../../html/start.php'>zur Startseite</a>
	<a class='navi' href='../../html/help/help1.php?page=5'>Hilfe</a>
	<a class='navi' href='$inst_path/pic2base/index.php'>Logout</a>";
	
	//Ausgehend von der gewaehlten Kategorie muessen alle Unterkategorien bestimmt werden. Dann muessen aus der Tabelle 10 (pic_kat) alle Eintraege geloescht werden, welche auf die gewaehlte Kategorie bzw. deren Unterkategorie verweisen.
	// fuer register_globals = off
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
		//echo $child."<BR>";			//$child - Nummer der zu loeschenden Kategorie
		//Loeschvorgang der Kategorie in der Kategorie-Tabelle ($table4):
		$result4 = mysql_query( "DELETE FROM $table4 WHERE kat_id = '$child'");
		//Eintrag aus der kat_lex loeschen:
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
	
	//loeschen der Bild-Zuordnungen in der pic_kat-Tabelle:
	FOREACH($pic_arr AS $pic)
	{
		FOREACH($child_arr AS $child)
		{
			$result5 = mysql_query( "DELETE FROM $table10 WHERE pic_id = '$pic' AND kat_id = '$child'"); 
		}
	}
}
ELSE
{
	header('Location: ../../../index.php');
}
echo "
<div class='page' id='page'>

	<div class='head' id='head'>
		pic2base :: Admin-Bereich - Kategorie l&ouml;schen
	</div>
	
	<div class='navi' id='navi'>
		<div class='menucontainer'>". $navigation."</div>
	</div>
	
	<div class='content' id='content'>
		<p style='margin:20px 0px; text-align:center'>Die folgenden Kategorien wurden gel&ouml;scht:<BR><BR>".$u_kategorie."<BR><BR>".
		count($pic_arr)." Bildzuordnung(en) wurde(n) aufgehoben.</p>
	</div>
	
	<div class='foot' id='foot'>
		<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
	</div>

</div>";

mysql_close($conn);
?>
</DIV>
</CENTER>
</BODY>
</HTML>