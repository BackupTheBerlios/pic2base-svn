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
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Datensatz-Bearbeitung</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel=stylesheet type="text/css" href='../../css/tooltips.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
	<script language="javascript" type="text/javascript" src="../../share/calendar.js"></script>
	<script type="text/javascript" src="../../share/functions/ShowPicture.js"></script>
</HEAD>


<script language="JavaScript" type="text/javascript">
function showAllDetails(mod, pic_id)
{
Fenster1 = window.open('../../share/details.php?view=kompact&pic_id='+pic_id, 'Details', "width=550,height=768,scrollbars,resizable=no,");
Fenster1.focus();
}

function saveChanges(pic_id, desc, aufn_dat)
{
Fenster1 = window.open('../../share/save_changes.php?pic_id='+pic_id + '&description=' + desc + '&aufn_dat=' + aufn_dat, 'Speicherung', "width=10,height=10,scrollbars,resizable=no,");
}
</script>

<BODY LANG="de-DE" scroll = "auto" onload="javascript:CloseWindow()">

<CENTER>

<DIV Class="klein">

<?php
/***********************************************************************************
 * Project: pic2base
 * File: edit_remove_kat.php
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
 ***********************************************************************************/
unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = preg_split('#,#',$_COOKIE['login']);
//echo $c_username;
}
 
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
$base_file = 'recherche2';

//var_dump($_GET);
if(array_key_exists('mod',$_GET))
{
	$mod = $_GET['mod'];
}

echo "
<div class='page'>

	<p id='kopf'>pic2base :: Datensatz-Bearbeitung (Kategoriezuweisung aufheben) <span class='klein'>(User: $c_username)</span></p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
		createNavi3_1($c_username);
		echo "</div>
	</div>";
//################################################################################################################
SWITCH ($mod)
	{
		CASE 'kat':
		echo "
		<div id='spalte1F'>
		
			<p id='elf' style='background-color:white; padding: 5px; margin-top: 4px; margin-left: 0px; text-align:center;'>Bildauswahl nach Kategorien<BR>";
			$ziel = "../../html/edit/edit_remove_kat.php";
			$base_file = 'edit_remove_kat';
			$mod='kat';
			$modus='edit';
			include $sr.'/bin/share/kat_treeview.php';
		
		echo "
		</div>";
		break;
		
		CASE 'desc':
		echo "
		<div id='spalte1F'>";
			$mod='desc';
		echo "
		</div>";
		break;
	}
//###############################################################################################################
	echo "	
	<div id='spalte2F'>
	</div>";
//###############################################################################################################	
	echo "
	<div id='filmstreifen'>";
	
	SWITCH($mod)
	{		
		CASE 'kat':
		$modus='edit';	//bedeutet, dass keine Checkboxen angezeigt werden und der Hinweistext entsprechend	angepasst wird
		$mod='kat';
		break;
		
		CASE 'desc':
		$modus='edit';	//bedeutet, dass keine Checkboxen angezeigt werden und der Hinweistext entsprechend	angepasst wird
		$mod='desc';		
		break;
	}
	
	echo "
	</div>";
//###############################################################################################################	
	echo "
	<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>
	
</div>

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