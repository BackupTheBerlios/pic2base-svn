<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Kategorieverwaltung</TITLE>
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
 * File: kategorie0.php
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

IF ($_COOKIE['uid'])
{
	$uid = $_COOKIE['uid'];
}
 
INCLUDE '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/permissions.php';

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
}
ELSE
{
	header('Location: ../../../index.php');
}

echo "
<div class='page'>

	<p id='kopf'>pic2base :: Admin-Bereich - Kategorieverwaltung</p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>".
		$navigation
		."</div>
	</div>
	
	<div  id='spalte1'>";

	//Erzeugung der Baumstruktur:
	//Beim ersten Aufruf der Seite wird nur das Wurzel-Element angezeigt.
	//  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

	// fuer register_globals = off
	if(array_key_exists('kat_id',$_GET))
	{
		$kat_id = $_GET['kat_id']; 
	}
	else
	{
		$kat_id = 0;
	}

	$KAT_ID = $kat_id;
	//  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//Ermittlung aller 'Knoten-Elemente' (Elemente, an denen in die Tiefe verzweigt wird)
	$knoten_arr[]=$kat_id;
	
	WHILE ($kat_id > '1')
	{
		//include '../../share/db_connect1.php';
		//INCLUDE '../../share/global_config.php';
		$res0 = mysql_query( "SELECT parent FROM $table4 WHERE kat_id='$kat_id'");
		echo mysql_error();
		$kat_id = mysql_result($res0, isset($i0), 'parent');
		//echo "Kat-ID in der Funktion: ".$kat_id."<BR>";
		$knoten_arr[]=$kat_id;
	}
	$knoten_arr = array_reverse($knoten_arr);
	
	echo "<TABLE id='kat'>";
	
	function getElements($kat_id, $knoten_arr, $KAT_ID)
	{
		include '../../share/db_connect1.php';
		INCLUDE '../../share/global_config.php';
		$result10 = mysql_query( "SELECT * FROM $table4 WHERE parent='$kat_id' ORDER BY kategorie");
		$num10 = mysql_num_rows($result10);
		FOR ($i10=0; $i10<$num10; $i10++)
		{
			$kategorie = mysql_result($result10, $i10, 'kategorie');
			$parent = mysql_result($result10, $i10, 'parent');
			$level = mysql_result($result10, $i10, 'level');
			$kat_id = mysql_result($result10, $i10, 'kat_id');
			$space='';
			FOR ($N=0; $N<$level; $N++)
			{
				$space .="&#160;&#160;&#160;";
			}
			
			$kat_id_pos = array_search($kat_id, $knoten_arr);
			if( $kat_id_pos > 0)
			{
				$kat_id_back = $knoten_arr[$kat_id_pos - 1];
			}
			IF (in_array($kat_id, $knoten_arr))
			{
				
				//echo $kat_id_back;
				$img = "<IMG src='../../share/images/minus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
				echo 	"<TR id='kat'>
					<TD id='kat3'>
					".$space."<a href='kategorie0.php?kat_id=$kat_id_back'>".$img."</a>&#160;
					<a href='kat_add.php?kat_id=$KAT_ID&level=$level&ID=$kat_id' title='Neue Unterkategorie einf&uuml;gen' id='std'>".$kategorie."</a>
					</TD>
					
					<TD id='kat2'><a href='kat_edit.php?kat_id=$KAT_ID&ID=$kat_id' title='Kategorie bearbeiten'><img src='../../share/images/edit.gif' style='border:none;' width='11' height='11' hspace='0' vspace='0' border='0' alt='Edit-Icon'></a>
					</TD>";
					
					IF($kat_id !== '1')
					{
						echo "
						<TD id='kat2'><a href='kat_delete.php?kat_id=$kat_id&ID=$kat_id' title='Kategorie l&ouml;schen'><img src=\"../../share/images/delete.gif\" width=\"11\" height=\"11\" hspace=\"0\" vspace=\"0\" border=\"0\" alt='Delete-Icon' /></a>
						</TD>";
					}
					ELSE
					{
						echo "<TD id='kat2'><BR></TD>";
					}
					
					echo "
					
					</TR>";
				getElements($kat_id, $knoten_arr, $KAT_ID);
			}
			ELSE
			{
				$img = "<IMG src='../../share/images/plus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
				echo 	"<TR id='kat'>
					<TD id='kat3'>
					".$space."<a href='kategorie0.php?kat_id=$kat_id'>".$img."</a>&#160;
					<a href='kat_add.php?kat_id=$KAT_ID&level=$level&ID=$kat_id' title='Neue Unterkategorie einf&uuml;gen' id='std'>".$kategorie."</a>
					</TD>
					
					<TD id='kat2'><a href='kat_edit.php?kat_id=$KAT_ID&ID=$kat_id' title='Kategorie bearbeiten'><img src='../../share/images/edit.gif' style='border:none;' width='11' height='11' hspace='0' vspace='0' border='0' alt='Edit-Icon'></a>
					</TD>";
					
					IF($kat_id !== '1')
					{
						echo "
						<TD id='kat2'><a href='kat_delete.php?kat_id=$kat_id&ID=$kat_id' title='Kategorie l&ouml;schen'><img src=\"../../share/images/delete.gif\" width=\"11\" height=\"11\" hspace=\"0\" vspace=\"0\" border=\"0\" alt='Delete-Icon' /></a>
						</TD>";
					}
					ELSE
					{
						echo "<TD id='kat2'><BR></TD>";
					}
					
					echo "
					
					</TR>";
			}
		}
	}
	
	$result10 = mysql_query( "SELECT * FROM $table4 WHERE kat_id='1'");
	$num10 = mysql_num_rows($result10);
	FOR ($i10=0; $i10<$num10; $i10++)
	{
		$kategorie = mysql_result($result10, $i10, 'kategorie');
		$parent = mysql_result($result10, $i10, 'parent');
		$level = mysql_result($result10, $i10, 'level');
		$kat_id = mysql_result($result10, $i10, 'kat_id');
		$space='';
		//echo $level;
		FOR ($N=0; $N<$level; $N++)
		{
			$space .="&#160;&#160;&#160;";
		}
		
		//Link fuer den Ruecksprung erzeugen, d.h. naechst hoeheren Knoten aufrufen:
		$kat_id_back = array_search($kat_id, $knoten_arr);
		IF (in_array($kat_id, $knoten_arr))
		{
			
			//echo "Space: ".$space."<BR>";
			//echo $kat_id_back;
			$img = "<IMG src='../../share/images/minus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
			echo 	"<TR id='kat'>
				<TD id='kat3'>
				".$space."<a href='kategorie0.php?kat_id=$kat_id_back'>".$img."</a>&#160;
				<a href='kat_add.php?kat_id=$KAT_ID&level=$level&ID=$kat_id' title='Neue Unterkategorie einf&uuml;gen' id='std'>".$kategorie."</a>
				</TD>
				
				<TD id='kat2'><a href='kat_edit.php?kat_id=$KAT_ID&ID=$kat_id' title='Kategorie bearbeiten'><img src='../../share/images/edit.gif' style='border:none;' width='11' height='11' hspace='0' vspace='0' border='0' alt='Edit-Icon'></a>
				</TD>";
					
				IF($kat_id !== '1')
				{
					echo "
					<TD id='kat2'><a href='kat_delete.php?kat_id=$kat_id&ID=$kat_id' title='Kategorie l&ouml;schen'><img src=\"../../share/images/delete.gif\" width=\"11\" height=\"11\" hspace=\"0\" vspace=\"0\" border=\"0\" alt='Delete-Icon' /></a>
					</TD>";
				}
				ELSE
				{
					echo "<TD id='kat2'><BR></TD>";
				}
				
				echo "
				
				</TR>";
			getElements($kat_id, $knoten_arr, $KAT_ID);
		}
		ELSE
		{
			//echo "Space: ".$space."|<BR>";
			$img = "<IMG src='../../share/images/plus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
			echo 	"<TR id='kat'>
				<TD id='kat3'>
				".$space."<a href='kategorie0.php?kat_id=$kat_id'>".$img."</a>&#160;
				<a href='kat_add.php?kat_id=$KAT_ID&level=$level&ID=$kat_id' title='Neue Unterkategorie einf&uuml;gen' id='std'>".$kategorie."</a>
				</TD>
				
				<TD id='kat2'><a href='kat_edit.php?kat_id=$KAT_ID&ID=$kat_id' title='Kategorie bearbeiten'><img src='../../share/images/edit.gif' style='border:none;' width='11' height='11' hspace='0' vspace='0' border='0' alt='Edit-Icon'></a>
				</TD>";
					
				IF($kat_id !== '1')
				{
					echo "
					<TD id='kat2'><a href='kat_delete.php?kat_id=$kat_id&ID=$kat_id' title='Kategorie l&ouml;schen'><img src=\"../../share/images/delete.gif\" width=\"11\" height=\"11\" hspace=\"0\" vspace=\"0\" border=\"0\" alt='Delete-Icon' /></a>
					</TD>";
				}
				ELSE
				{
					echo "<TD id='kat2'><BR></TD>";
				}
					
				echo "
				
				</TR>";
		}
	}
	echo "</TABLE>
	</div>
	
	<DIV id='spalte2'>
		<p id='elf'' style='background-color:white; padding: 5px; width: 365px; margin-top: 20px; margin-left: 20px;'>
		Hinweis:<BR><BR>
		Mit einem Klick auf einen Kategorie-Namen f&uuml;gen Sie eine neue Unter-Kategorie unterhalb der gew&auml;hlten Kategorie ein.<BR><BR>
		Mit einem Klick auf das Bearbeiten-Icon &#160;<img src='../../share/images/edit.gif' style='border:none;' width='11' height='11' hspace='0' vspace='0' border='0' alt='Edit-Icon'>&#160; k&ouml;nnen Sie die Bezeichnung f&uuml;r die ausgew&auml;hlte Kategorie &auml;ndern.<BR><BR>
		Mit einem Klick auf das L&ouml;schen-Icon &#160;<img src='../../share/images/delete.gif' style='border:none;' width='11' height='11' hspace='0' vspace='0' border='0' alt='Delete-Icon'>&#160; k&ouml;nnen Sie die ausgew&auml;hlte Kategorie l&ouml;schen.<BR>
		<u>Wichtig!</u><BR>
		Hierbei werden auch ALLE Unterkategorien der gew&auml;hlten Kategorie gel&ouml;scht!<BR><BR>
		&Uuml;ber den Men&uuml;punkt \"Sortierung\" k&ouml;nnen Sie die Kategorie-Struktur neu ordnen. Weitere Informationen erhalten Sie auf der Sortieren-Seite.<BR><BR>
		Werden Kategorien gel&ouml;scht oder neu sortiert, hat dies auch Auswirkungen auf die darin befindlichen Bilder. In diesem Fall m&uuml;ssen die jeweiligen Meta-Daten konsistent gehalten werden, was - je nach Anzahl der Bilder und der Rechenleistung des Servers - einige Zeit in Anspruch nehmen kann.<BR><BR>
		Mit dem Men&uuml;punkt \"Wartung\" haben Sie die M&ouml;glichkeit, die Tabelle der Bild-Kategorie-Zuweisungen auf fehlerhafte Eintr&auml;ge zu &uuml;berpr&uuml;fen und ggf. zu reparieren. Die Aktion wird sofort nach Klick auf den Button gestartet und kann eine Weile dauern.<BR>
		Am Ende der Wartung wird Ihnen ein Bericht &uuml;ber den Zustand der Tabelle angezeigt.</p>
	</DIV>
	
	<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>

</div>";

mysql_close($conn);
?>
</DIV></CENTER>
</BODY>
</HTML>