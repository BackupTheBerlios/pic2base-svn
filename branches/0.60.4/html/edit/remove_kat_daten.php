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
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
	<script type="text/javascript" src="../../share/functions/ShowPicture.js"></script>
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel=stylesheet type="text/css" href='../../css/tooltips.css'>
</HEAD>
<script language="JavaScript">
<!--
function showKatInfo(kat_id)
{
	Fenster1 = window.open('../../share/edit_kat_info.php?kat_id='+kat_id, 'Kategorie-Informationen', "width=675,height=768,scrollbars,resizable=no,");
	Fenster1.focus();
}
-->
</script>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: remove_kat_daten.php
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

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');

//var_dump($_REQUEST);
if(array_key_exists('mod',$_GET))
{
	$mod = $_GET['mod'];
}

if(array_key_exists('pic_id',$_GET))
{
	$pic_id = $_GET['pic_id'];
}

if(array_key_exists('kat_id',$_GET))
{
	$kat_id = $_GET['kat_id']; 
}
else
{
	$kat_id = 0;
}
if(array_key_exists('ID',$_REQUEST))
{
	$ID = $_GET['ID'];
}
//echo"<br>kat_id: ".$kat_id."<br>";

if(!isset($ID))
{
	$ID = '';
}

echo "
<div class='page'>
	<FORM name='kat-aufhebung', method='post' action='remove_kat_daten_action2.php?kat_id=$kat_id&mod=$mod'>
	<p id='kopf'>pic2base :: Datensatz-Bearbeitung (Kategorie-Zuweisungen entfernen) <span class='klein'>(User: $username)</span></p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
			createNavi3_1($uid);
			echo "<INPUT type='submit' class='button3' value = 'Speichern'><BR><INPUT type='button' class='button3a' value='Abbrechen' OnClick='location.href=\"edit_start.php\"'>
		</div>
	</div>";
			
	SWITCH($mod)
	{
		CASE "edit_remove":
			echo "<div id='spalte1F'>
			<p id='elf' style='background-color:white; padding: 5px; margin-top: 4px; margin-left: 0px; text-align:center;'>Bildauswahl nach Kategorien<BR></p>";
			$ziel = '../../html/edit/remove_kat_daten.php';
			$modus='edit';
			$mod='kat';
			$base_file = 'remove_kat_daten';
			include $sr.'/bin/share/kat_treeview.php';
			echo "
			</div>";
		break;
	}

	echo "
	<div id='spalte2F'>
		<p id='elf' style='background-color:white; padding: 5px; margin-top: 4px; margin-left: 0px; text-align:center;'>Hier k&ouml;nnen Sie Kategoriezuweisungen wieder aufheben.<BR><BR>
		W&auml;hlen Sie dazu links die gew&uuml;nschte Kategorie aus und legen Sie in der Filmstreifenansicht fest, welches Bild aus dieser Kategorie entfernt werden soll, indem Sie unter dem jeweiligen Bild die Checkbox ausw&auml;hlen.<BR><BR>
		Die gew&uuml;nschten &Auml;nderungen werden durch einen Klick auf den Speichern-Button &uuml;bernommen.</p>
	</div>
	
	<div id='filmstreifen'>";
	$modus='edit_remove';
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