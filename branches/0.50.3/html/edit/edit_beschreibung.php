<?php
IF (!$_COOKIE['login'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../../index.php');
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Datensatz-Bearbeitung</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel=stylesheet type="text/css" href='../../css/tooltips.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
	<!--<script type="text/javascript" src="../../ajax/inc/vorschau.js"></script>-->
	<script language="JavaScript" type="text/javascript" src="../../share/functions/ShowPicture.js"></script>
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: edit_beschreibung.php
 *
 * Copyright (c) 2003 - 2005 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 */

unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = preg_split('#,#',$_COOKIE['login']);
//echo $c_username;
}
 
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

//var_dump($_REQUEST);
if(array_key_exists('pic_id',$_GET))
{
	$pic_id = $_GET['pic_id'];
}

if(array_key_exists('mod',$_GET))
{
	$mod = $_GET['mod'];
}

if(array_key_exists('art',$_GET))
{
	$art = $_GET['art'];
}
else
{
	if(!isset($art))
	{
		$art = '';
	}
}

if(array_key_exists('kat_id',$_GET))
{
	$kat_id = $_GET['kat_id'];
}
else
{
	if(!isset($kat_id))
	{
		$kat_id = '';
	}
}

if(array_key_exists('ID',$_REQUEST))
{
	$ID = $_REQUEST['ID'];
}
else
{
	if(!isset($ID))
	{
		$ID = '';
	}
}

echo "
<div class='page'>
	<FORM name='desc-zuweisung', method='post' action='edit_desc_daten_action.php?kat_id=$kat_id&ID=$ID&art=$art'>
	<p id='kopf'>pic2base :: Datensatz-Bearbeitung (Beschreibung bearbeiten) <span class='klein'>(User: $c_username)</span></p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
			createNavi3_1($c_username);
			echo "<INPUT type='submit' class='button3' value='Speichern' style='margin-top:375px;'><INPUT type='button' class='button3' style='margin-top:5px;' value='Abbrechen' OnClick='location.href=\"edit_start.php\"'>
		</div>
	</div>
	
	<div id='spalte1F'>
		<p id='elf' style='background-color:white; padding: 5px; margin-top: 4px; margin-left: 0px; text-align:center;'>Bildauswahl nach Kategorien<BR>";
		$ziel = '../../html/edit/edit_beschreibung.php';
		$modus='edit';
		$mod='kat';
		$base_file = 'edit_beschreibung';
		//$modus='complete_view';
		//echo $ID;
		include $sr.'/bin/share/kat_treeview.php';
	echo "
	</div>
	
	<div id='spalte2F'>";
	echo "<p id='elf' style='background-color:white; padding: 5px; margin-top: 4px; margin-left: 0px; text-align:center;'>
			Ordne Beschreibungstext den ausgew&auml;hlten Bildern zu:<BR></p><u>Hinweis:</u><BR>
			Der hier eingetragene Text wird den vorhandenen Beschreibungen der ausgew&auml;hlten Bildern hinzugef&uuml;gt!<BR><BR>
			<textarea name='description' wordwrap style='width:380px; height:300px; background-color:#DFEFFf;'></textarea>";
	
	echo "
	</div>
	
	<div id='filmstreifen'>";
	$modus='edit';
	$mod='kat';
	
	echo "
	</div>

<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>
</div>
</FORM>

<div id='blend' style='display:none; z-index:99;'>
<IMG src='../../share/images/grey.png' style='z-index:100; position:absolute; top:0px; left:0px; width:100%; height:99%;' />
<img src=\"../../share/images/loading.gif\" style='position:absolute; top:200px; width:20px; z-index:101;' />
</div>";

mysql_close($conn);
?>
</DIV>
</CENTER>
</BODY>
</HTML>