<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Startseite</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: kat_ausw1.php
 *
 * Copyright (c) 2003 - 2010 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 */
//setlocale(LC_CTYPE, 'de_DE');
unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
	//echo $c_username;
}
 
INCLUDE '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
//include_once $sr.'/bin/share/functions/permissions.php';
include $sr.'/bin/share/functions/main_functions.php';
include_once $sr.'/bin/share/functions/ajax_functions.php';
/*
IF(hasPermission($c_username, 'editkattree', $sr))
{
	$navigation = 	"<a class='navi' href='../../html/admin/adminframe.php'>Zur&uuml;ck</a>
					<a class='navi' href='../../html/start.php'>zur Startseite</a>
					<a class='navi' href='../../html/help/help1.php?page=5'>Hilfe</a>
					";
}
ELSE
{
	$navigation = 	"<a class='navi' href='../../../index.php'>Logout</a>";
}
*/
echo "
<div class='page'>
<FORM name='kat-zuweisung' method='post' action='kat_sort_action.php'>
	<p id='kopf'>pic2base :: Admin-Bereich - Kategorie-Sortierung</p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
		createNavi5_1($c_username);
		echo "<INPUT type='submit' class='button3' value = 'Speichern'><BR>
		<INPUT type='button' class='button3a' value='Abbrechen' OnClick='location.href=\"$inst_path/pic2base/bin/html/admin/adminframe.php\"'>
		</div>
	</div>
	
	<div  id='spalte1'>
	<TABLE id='kat'>
		<TR>
		<TD>Quell-Kategorie</TD>
		</TR>";
	
	$result10 = mysql_query( "SELECT * FROM $table4 WHERE kat_id='1'");
	$kategorie = utf8_encode(mysql_result($result10, isset($i10), 'kategorie'));
	$kat_id_s = mysql_result($result10, isset($i10), 'kat_id');
		
	$img = "<IMG src='../../share/images/plus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
	echo "<TR id='kat'>
	
		<TD id='kat1'>
		<span style='cursor:pointer;' onClick='reloadSourceTree(\"$kat_id_s\")'>".$img."</span>&#160;".$kategorie."
		</TD>
		
		<TD id='kat2'><BR></TD>
		
		</TR>
	</TABLE>
	</div>";
//############################################################################################################################
	
	echo "
	<DIV id='spalte2'>
	<p id='elf' style='background-color:white; padding: 5px; width: 365px; margin-top: 20px; margin-left: 20px;'>Hinweis:<BR><BR>
	W&auml;hlen Sie hier zun&auml;chst in der linken Spalte die Quell-Kategorie aus.<BR><BR>
	Wenn Sie den entsprechenden Auswahlknopf gedr&uuml;ckt haben, erscheint in der rechten Spalte das Auswahlmen&uuml; f&uuml;r die Ziel-Kategorie.<BR><BR>
	Wenn Sie auch diese ausgew&auml;hlt haben, best&auml;tigen Sie Ihre Auswahl mit einem Klick auf den Button \"Speichern\".<BR>
	</p>
	</DIV>
<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>
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