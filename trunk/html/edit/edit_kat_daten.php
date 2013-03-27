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
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script type="text/javascript" src="../../share/functions/ShowPicture.js"></script>
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format2.css'>
	<link rel=stylesheet type="text/css" href='../../css/tooltips.css'>
	<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
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

/*
 * Project: pic2base
 * File: edit_kat_daten.php
 *
 * Copyright (c) 2003 - 2013 Klaus Henneberg
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
include $sr.'/bin/css/initial_layout_settings.php';

if(array_key_exists('mod',$_GET))
{
	$mod = $_GET['mod'];
}

if(array_key_exists('show_mod',$_GET))
{
	$show_mod = $_GET['show_mod'];
}
else
{
	$show_mod="J";
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

$base_file = 'edit_kat_daten';

if($mod == 'kat')
{
	include $sr.'/bin/share/functions/ajax_functions.php';
	echo "<BODY onLoad=\"getKatTreeview($kat_id,'0','kat','6','edit','edit_kat_daten'); getKatTreeview2('0','0','kat','6','complete_view','edit_kat_daten');\">";
}
elseif($mod == 'zeit')
{
	include $sr.'/bin/share/functions/ajax_functions.php';
	echo "<BODY onLoad=\"getTimeTreeview2('0','zeit','$show_mod','6'); getKatTreeview2('0','0','kat','6','complete_view','edit_kat_daten');\">";
}

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');

echo "
<CENTER>
	<DIV Class='klein'>
		<div class='page' id='page'>
			<FORM name='kat-zuweisung', method='post' action='edit_kat_daten_action2.php?kat_id=$kat_id&mod=$mod&ID=$ID'>
			
				<div class='head' id='head'>
					pic2base :: Datensatz-Bearbeitung (Kategorien zuweisen) <span class='klein'>(User: $username)</span>
				</div>
				
				<div class='navi' id='navi'>
					<div class='menucontainer'>";
						createNavi3_1($uid);
						echo "<INPUT type='submit' class='button3' id='button3' value = 'Speichern'><BR><INPUT type='button' class='button3a' id='button3a' value='Abbrechen' OnClick='location.href=\"edit_start.php\"'>
					</div>
				</div>";
						
				SWITCH($mod)
				{
					CASE "kat":
						echo "<div id='spalte1F'>
							<center>
								<fieldset  style='background-color:none; margin-top:10px;'>
								<legend style='color:blue; font-weight:bold;'>Bildauswahl nach Kategorien</legend>
									<div id='scrollbox0' style='overflow-y:scroll;'>";
									echo "</div>
								</fieldset>
								</center>
							</div>";
					break;
					
					CASE "zeit":
						echo "<div id='spalte1F'>
							<center>
							<fieldset  style='background-color:none; margin-top:10px;'>
							<legend style='color:blue; font-weight:bold;'>Bildauswahl nach Aufnahmedatum</legend>
								<div id='scrollbox0' style='overflow-y:scroll;'>";
								echo "</div>
							</fieldset>
							</center>
						</div>";
					break;
				}
			
				echo "
				<div id='spalte2F'>
					<fieldset  style='background-color:none; margin-top:10px;'>
						<legend style='color:blue; font-weight:bold;'>Ordne Kategorie den ausgew&auml;hlten Bildern zu</legend>
						<div id='scrollbox1' style='overflow-y:scroll;'>";
						$modus='complete_view';
//						include $sr.'/bin/share/kat_treeview2.php';
						echo "</div>
					</fieldset>	
				</div>
				
				<div id='filmstreifen'>";
				$modus='edit';
				echo "
				</div>
				
				<div class='foot' id='foot'>
					<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
				</div>
			
			</FORM>
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