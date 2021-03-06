<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Startseite</TITLE>
	<META NAME="GENERATOR" CONTENT="eclipse">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format2.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
	<script language="JavaScript" src="../../share/functions/resize_elements.js"></script>
	<script language="JavaScript" src="../../share/functions/jquery-1.8.2.min.js"></script>
	<script language="JavaScript">
	  	jQuery.noConflict();
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
 * File: kat_ausw1.php
 *
 * Copyright (c) 2003 - 2013 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 */

IF ($_COOKIE['uid'])
{
	$uid = $_COOKIE['uid'];
}
 
INCLUDE '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
include_once $sr.'/bin/share/functions/ajax_functions.php';

echo "
<div class='page' id='page'>
<FORM name='kat-zuweisung' method='post' action='kat_sort_action.php'>
	
	<div id='head'>
		pic2base :: Admin-Bereich - Kategorie-Sortierung
	</div>
	
	<div class='navi' id='navi'>
		<div class='menucontainer'>";
		createNavi5_1($uid);
		echo "<INPUT type='submit' class='button3' id='button3' value = 'Speichern'><BR>
		<INPUT type='button' class='button3a' id='button3a' value='Abbrechen' OnClick='location.href=\"$inst_path/pic2base/bin/html/admin/adminframe.php\"'>
		</div>
	</div>
	
	<div  id='spalte1'>
		<fieldset style='background-color:none; margin-top:10px;'>
		<legend style='color:blue; font-weight:bold;'>Quell-Kategorie</legend>
			<div id='scrollbox0' style='overflow-y:scroll;'>
				<center>
					<TABLE class='kat'>";
					
						$result10 = mysql_query( "SELECT * FROM $table4 WHERE kat_id='1'");
						$kategorie = mysql_result($result10, isset($i10), 'kategorie');
						$kat_id_s = mysql_result($result10, isset($i10), 'kat_id');
						
						$img = "<IMG src='../../share/images/plus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
						echo "<TR class='kat'>
					
						<TD class='kat1'>
						<span style='cursor:pointer;' onClick='reloadSourceTree(\"$kat_id_s\")'>".$img."</span>&#160;".$kategorie."
						</TD>
						
						<TD class='kat2'><BR></TD>
						
						</TR>
					</TABLE>
				</center>
			</div>
		</fieldset>
	</div>";
//############################################################################################################################
	
	echo "
	<DIV id='spalte2'>
		<fieldset style='background-color:none; margin-top:10px;'>
			<legend style='color:blue; font-weight:bold;'>Zielkategorie</legend>
				<div id='scrollbox1' style='overflow-y:scroll;'>
			
				W&auml;hlen Sie hier zun&auml;chst in der linken Spalte die Quell-Kategorie aus.<BR><BR>
				Wenn Sie den entsprechenden Auswahlknopf gedr&uuml;ckt haben, erscheint hier in der rechten Spalte das Auswahlmen&uuml; f&uuml;r die Ziel-Kategorie.<BR><BR>
				Wenn Sie auch diese ausgew&auml;hlt haben, best&auml;tigen Sie Ihre Auswahl mit einem Klick auf den Button \"Speichern\".<BR>
				</div>
		</fieldset>
	</DIV>
	
	<div id='foot'>
		<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
	</div>
	
</div>
</FORM>

<div id='blend' style='display:none; z-index:99;'>
<IMG src='../../share/images/grey.png' style='z-index:100; position:absolute; top:0px; left:0px; width:100%; height:99%;' />
<img src=\"../../share/images/loading.gif\" style='position:absolute; top:200px; width:40px; z-index:101;' />
</div>";

mysql_close($conn);
?>
<p class="klein"></P>
</DIV></CENTER>
</BODY>
</HTML>