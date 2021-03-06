<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Startseite</TITLE>
	<META NAME="GENERATOR" CONTENT="eclipse">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format2.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
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
 * File: kat_sort1.php
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
 
INCLUDE '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/permissions.php';

IF(hasPermission($uid, 'editkattree', $sr))
{
	$navigation = "
			<a class='navi_blind' href='kat_sort1.php'>Sortierung</a>
			<a class='navi' href='../../html/admin/adminframe.php'>Zur&uuml;ck</a>
			<a class='navi_blind'></a>
			<a class='navi_blind'></a>
			<a class='navi_blind'></a>
			<a class='navi_blind'></a>
			<a class='navi_blind'></a>
			<a class='navi_blind'></a>
			<a class='navi_blind'></a>
			<a class='navi_blind'></a>
			<a class='navi_blind'></a>
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
<div class='page' id='page'>

	<div id='head'>
		pic2base :: Admin-Bereich - Kategorie-Sortierung
	</div>
	
	<div class='navi' id='navi'>
		<div class='menucontainer'>";
		echo $navigation;
		echo "
		</div>
	</div>
	
	<div  id='spalte1'>
		<fieldset style='background-color:none; margin-top:10px;'>
		<legend style='color:blue; font-weight:bold;'>Kategorie-Umsortierung</legend>
			<div id='scrollbox0' style='overflow-y:scroll;'>";
/*			
			//Erzeugung der Baumstruktur:
			//Beim ersten Aufruf der Seite wird nur das Wurzel-Element angezeigt.
			//  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
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
				$res0 = mysql_query( "SELECT parent FROM $table4 WHERE kat_id='$kat_id'");
				echo mysql_error();
				$kat_id = mysql_result($res0, isset($i0), 'parent');
				//echo "Kat-ID in der Funktion: ".$kat_id."<BR>";
				$knoten_arr[]=$kat_id;
			}
			$knoten_arr = array_reverse($knoten_arr);
			
			echo "
				<center>
				<TABLE class='kat'>";
				
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
						$kat_id_back = $knoten_arr[$kat_id_pos - 1];
						IF (in_array($kat_id, $knoten_arr))
						{
							
							//echo $kat_id_back;
							$img = "<IMG src='../../share/images/minus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
							echo 	"<TR class='kat'>
								<TD class='kat3'>
								".$space."<a href='kategorie0.php?kat_id=$kat_id_back'>".$img."</a>&#160;
								<a href='kat_add.php?kat_id=$KAT_ID&level=$level&ID=$kat_id' title='Neue Unterkategorie einf&uuml;gen' id='std'>".$kategorie."</a>
								</TD>
								
								<TD class='kat2'><a href='kat_edit.php?kat_id=$KAT_ID&ID=$kat_id' title='Kategorie bearbeiten'><img src='../../share/images/edit.gif' style='border:none;' width='11' height='11' hspace='0' vspace='0' border='0' alt='Edit-Icon'></a>
								</TD>";
								
								IF($kat_id !== '1')
								{
									echo "
									<TD class='kat2'><a href='kat_delete.php?kat_id=$kat_id&ID=$kat_id' title='Kategorie l&ouml;schen'><img src=\"../../share/images/delete.gif\" width=\"11\" height=\"11\" hspace=\"0\" vspace=\"0\" border=\"0\" alt='Delete-Icon' /></a>
									</TD>";
								}
								ELSE
								{
									echo "<TD class='kat2'><BR></TD>";
								}
								
								echo "
								
								</TR>";
							getElements($kat_id, $knoten_arr, $KAT_ID);
						}
						ELSE
						{
							$img = "<IMG src='../../share/images/plus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
							echo 	"<TR class='kat'>
								<TD class='kat3'>
								".$space."<a href='kategorie0.php?kat_id=$kat_id'>".$img."</a>&#160;
								<a href='kat_add.php?kat_id=$KAT_ID&level=$level&ID=$kat_id' title='Neue Unterkategorie einf&uuml;gen' id='std'>".$kategorie."</a>
								</TD>
								
								<TD class='kat2'><a href='kat_edit.php?kat_id=$KAT_ID&ID=$kat_id' title='Kategorie bearbeiten'><img src='../../share/images/edit.gif' style='border:none;' width='11' height='11' hspace='0' vspace='0' border='0' alt='Edit-Icon'></a>
								</TD>";
								
								IF($kat_id !== '1')
								{
									echo "
									<TD class='kat2'><a href='kat_delete.php?kat_id=$kat_id&ID=$kat_id' title='Kategorie l&ouml;schen'><img src=\"../../share/images/delete.gif\" width=\"11\" height=\"11\" hspace=\"0\" vspace=\"0\" border=\"0\" alt='Delete-Icon' /></a>
									</TD>";
								}
								ELSE
								{
									echo "<TD class='kat2'><BR></TD>";
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
					
					//Link fuer den Ruecksprung erzeugen, d.h. naehst hoeheren Knoten aufrufen:
					$kat_id_back = array_search($kat_id, $knoten_arr);
					IF (in_array($kat_id, $knoten_arr))
					{
						
						//echo "Space: ".$space."<BR>";
						//echo $kat_id_back;
						$img = "<IMG src='../../share/images/minus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
						echo 	"<TR class='kat'>
							<TD class='kat3'>
							".$space."<a href='kategorie0.php?kat_id=$kat_id_back'>".$img."</a>&#160;
							<a href='kat_add.php?kat_id=$KAT_ID&level=$level&ID=$kat_id' title='Neue Unterkategorie einf&uuml;gen' id='std'>".$kategorie."</a>
							</TD>
							
							<TD class='kat2'><a href='kat_edit.php?kat_id=$KAT_ID&ID=$kat_id' title='Kategorie bearbeiten'><img src='../../share/images/edit.gif' style='border:none;' width='11' height='11' hspace='0' vspace='0' border='0' alt='Edit-Icon'></a>
							</TD>";
								
							IF($kat_id !== '1')
							{
								echo "
								<TD class='kat2'><a href='kat_delete.php?kat_id=$kat_id&ID=$kat_id' title='Kategorie l&ouml;schen'><img src=\"../../share/images/delete.gif\" width=\"11\" height=\"11\" hspace=\"0\" vspace=\"0\" border=\"0\" alt='Delete-Icon' /></a>
								</TD>";
							}
							ELSE
							{
								echo "<TD class='kat2'><BR></TD>";
							}
							
							echo "
							
							</TR>";
						getElements($kat_id, $knoten_arr, $KAT_ID);
					}
					ELSE
					{
						//echo "Space: ".$space."|<BR>";
						$img = "<IMG src='../../share/images/plus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
						echo 	"<TR class='kat'>
							<TD class='kat3'>
							".$space."<a href='kategorie0.php?kat_id=$kat_id'>".$img."</a>&#160;
							<a href='kat_add.php?kat_id=$KAT_ID&level=$level&ID=$kat_id' title='Neue Unterkategorie einf&uuml;gen' id='std'>".$kategorie."</a>
							</TD>
							
							<TD class='kat2'><a href='kat_edit.php?kat_id=$KAT_ID&ID=$kat_id' title='Kategorie bearbeiten'><img src='../../share/images/edit.gif' style='border:none;' width='11' height='11' hspace='0' vspace='0' border='0' alt='Edit-Icon'></a>
							</TD>";
								
							IF($kat_id !== '1')
							{
								echo "
								<TD class='kat2'><a href='kat_delete.php?kat_id=$kat_id&ID=$kat_id' title='Kategorie l&ouml;schen'><img src=\"../../share/images/delete.gif\" width=\"11\" height=\"11\" hspace=\"0\" vspace=\"0\" border=\"0\" alt='Delete-Icon' /></a>
								</TD>";
							}
							ELSE
							{
								echo "<TD class='kat2'><BR></TD>";
							}
								
							echo "
							
							</TR>";
					}
				}
				echo "</TABLE>
				</center>";
				*/
		echo "
			</div>
		</fieldset>
	</div>
	
	<DIV id='spalte2'>
	<fieldset style='background-color:none; margin-top:10px;'>
		<legend style='color:blue; font-weight:bold;'>Hinweis</legend>
			<div id='scrollbox1' style='overflow-y:scroll;'>
			
		
			Die Kategorie-Sortierung dient dem Zweck, Bilder, die bisher in der Kategorie A einsortiert waren nun einer neuen (oder bereits vorhandenen) Kategorie B zuzuordnen.<BR><BR>
			Anschlie&szlig;end wird die alte Kategorie A gel&ouml;scht.<BR><BR>
			Dies kann man nutzen, um eine Kategorie innerhalb des Kategoriebaumes \"umziehen\" zu lassen.<BR><BR>
			Dazu wird zun&auml;chst eine neue Kategorie B mit dem gleichen Namen der bisherigen Kategorie A an einer neuen Stelle im Kategoriebaum angelegt. Nun l&auml;&szlig;t man einfach die gew&uuml;nschte Kategorie von A nach B umziehen, womit die betroffenen Bilder zwar unter dem gleichen Kategorienamen, aber an einer anderen Stelle zu finden sind.<BR><BR>
			<b><u>Schritt 1:</u></b><BR>
			Pr&uuml;fen Sie in der linken Spalte, ob die gew&uuml;nschte Zielkategorie vorhanden ist. Wenn nicht, legen Sie sie an.<BR><BR>
			<b><u>Schritt 2:</u></b><BR>
			Klicken Sie hier, um zum Auswahlfenster f&uuml;r die Quell- und Zielkaterogie zu gelangen:<BR><br>
			<center>
			<INPUT type='button' value='Weiter zum Kategorie-Auswahlfenster' onClick='location.href=\"kat_ausw1.php\"'>
			</center>
			</div>
		</fieldset>
	</DIV>
	
	<div id='foot'>
		<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
	</div>
</div>";

mysql_close($conn);
?>
</DIV></CENTER>
</BODY>
</HTML>