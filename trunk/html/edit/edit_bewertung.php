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
	<META NAME="GENERATOR" CONTENT="eclipse">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format2.css'>
	<link rel=stylesheet type="text/css" href='../../css/tooltips.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
	<script type="text/javascript" src="../../share/functions/ShowPicture.js"></script>
	<script language="JavaScript" src="../../share/functions/resize_elements.js"></script>
	<script language="JavaScript" src="../../share/functions/jquery-1.8.2.min.js"></script>
	<script language="JavaScript">
	  	jQuery.noConflict();
		jQuery(document).ready(checkWindowSize);
		jQuery(window).resize(checkWindowSize);

		function showKatInfo(kat_id)
		{
			Fenster1 = window.open('../../share/edit_kat_info.php?kat_id='+kat_id, 'Kategorie-Informationen', "width=675,height=768,scrollbars,resizable=no,");
			Fenster1.focus();
		} 
	</script>
</HEAD>

<?php
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/ajax_functions.php';

echo "<BODY onLoad=\"getKatTreeview('0','0','kat','0','edit','edit_bewertung')\">
<CENTER>
<DIV Class='klein'>";

/*
 * Project: pic2base
 * File: edit_bewertung.php
 *
 * Copyright (c) 2007 - 2013 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 */

$pic_id = $_GET['pic_id'];
$mod = $_GET['mod'];
if(array_key_exists('kat_id',$_GET))
{
	$kat_id = $_GET['kat_id'];
}
else
{
	$kat_id = 0;
}
if(!isset($art))
{
	$art = '';
}
if(!isset($ID))
{
	$ID = '';
}

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');

echo "
<div class='page' id='page'>
	<FORM name='bew-zuweisung', method='post' action='edit_bew_daten_action.php?kat_id=$kat_id&ID=$ID&art=$art'>
	
	<div class='head' id='head'>
			pic2base :: Datensatz-Bearbeitung (Bewertung bearbeiten) <span class='klein'>(User: $username)</span>
	</div>
	
	<div class='navi' id='navi'>
		<div class='menucontainer'>";
			createNavi3_1($uid);
			echo "
		</div>
	</div>
	
	<div id='spalte1F'>
		<center>
			<fieldset  style='background-color:none; margin-top:10px;'>
			<legend style='color:blue; font-weight:bold;'>Bildauswahl nach Kategorien</legend>
				<div id='scrollbox0' style='overflow-y:scroll;'>";
				$ziel = '../../html/edit/edit_bewertung.php';
				$modus='edit';
				$mod='kat';
				$base_file = 'edit_bewertung';
				echo "
				</div>
			</fieldset>
		</center>
	</div>
	
	<div id='spalte2F'>
		<fieldset id='fieldset_spalte2' style='background-color:none; margin-top:10px;'>
		<legend style='color:blue; font-weight:bold;'>Vorschau des gew&auml;hlten Bildes</legend>
			
		</fieldset>
	</div>
	
	<div id='filmstreifen'>";
	$modus='edit';
	$mod='kat';
	echo "
	</div>
	
	<div class='foot' id='foot'>
			<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
	</div>

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