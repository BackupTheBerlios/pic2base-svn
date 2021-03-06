<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Kategorie hinzuf&uuml;gen</TITLE>
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
		function countChars () 
		{
		  document.kat_neu.Kontrolle.value = document.kat_neu.kategorie.value.length + 1;
		  if(document.kat_neu.Kontrolle.value > 30)
			  alert("30 Zeichen sind erlaubt!\nWeitere Zeichen werden ignoriert.");
		  return true;
		} 
	</script>
</HEAD>

<BODY LANG="de-DE" scroll = "auto">
<CENTER>
<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: kat_add.php
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
include $sr.'/bin/share/functions/permissions.php';

IF(hasPermission($uid, 'editkattree', $sr))
{
	$navigation = "
			<a class='navi' href='kat_sort1.php'>Sortierung</a>
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

$level = $_GET['level']; 
$level_neu = $level + 1;

$ID = $_GET['ID']; 
$kat_id = $_GET['kat_id']; 

function setFontColor($ID, $kat_id)
{
	IF ($ID == $kat_id)
	{
		$f_color = 'red';
	}
	ELSE
	{
		$f_color = 'black';
	}
	return $f_color;
}

echo "
<div class='page' id='page'>

	<div id='head'>
		pic2base :: Admin-Bereich - Unterkategorie anlegen
	</div>
	
	<div class='navi' id='navi'>
		<div class='menucontainer'>";
			echo $navigation;
			echo "
		</div>
	</div>
	
	<div id='spalte1'>
	<fieldset style='background-color:none; margin-top:10px;'>
			<legend style='color:blue; font-weight:bold;'>Kategorie-Auswahl</legend>
			<div id='scrollbox0' style='overflow-y:scroll;'>
			<center>";

		//Erzeugung der Baumstruktur:
		//Beim ersten Aufruf der Seite wird nur das Wurzel-Element angezeigt.
		//  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		$KAT_ID = $kat_id;
		//  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//Ermittlung aller 'Knoten-Elemente' (Elemente, an denen in die Tiefe verzweigt wird)
		$knoten_arr[]=$kat_id;
		
		WHILE ($kat_id > '1')
		{
			include '../../share/db_connect1.php';
			INCLUDE '../../share/global_config.php';
			$res0 = mysql_query( "SELECT parent FROM $table4 WHERE kat_id='$kat_id'");
			echo mysql_error();
			$kat_id = mysql_result($res0, isset($i0), 'parent');
			//echo "Kat-ID in der Funktion: ".$kat_id."<BR>";
			$knoten_arr[]=$kat_id;
		}
		$knoten_arr = array_reverse($knoten_arr);
		
		echo "<TABLE class='kat'>";
		
		function getElements($kat_id, $knoten_arr, $KAT_ID, $ID)
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
				if ($kat_id_pos > 0)
				{
				    $kat_id_back = $knoten_arr[$kat_id_pos - 1];
				}
	
				//echo "Kat-ID: ".$kat_id.", ID: ".$ID.", Font_Color: ".setFontColor($ID, $kat_id)."<BR>";
				IF (in_array($kat_id, $knoten_arr))
				{
					$img = "<IMG src='../../share/images/minus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
					echo 	"<TR class='kat'><TD class='kat1'>
						".$space."<a href='kategorie0.php?kat_id=$kat_id_back'>".$img."</a>&#160;&#160;<font color=".setFontColor($ID, $kat_id).">".$kategorie."</font>
						</TD>
						<TD class='kat2'><a href='kat_edit.php?kat_id=$KAT_ID&ID=$kat_id' title='Kategorie bearbeiten'><img src='../../share/images/edit.gif' style='border:none;' width='11' height='11' hspace='0' vspace='0' border='0' alt='Edit-Icon'></a>
						</TD></TR>";
					getElements($kat_id, $knoten_arr, $KAT_ID, $ID);
				}
				ELSE
				{
					$img = "<IMG src='../../share/images/plus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
					echo 	"<TR class='kat'><TD class='kat1'>
						".$space."<a href='kategorie0.php?kat_id=$kat_id'>".$img."</a>&#160;&#160;<font color=".setFontColor($ID, $kat_id).">".$kategorie."</font>
						</TD>
						<TD class='kat2'><a href='kat_edit.php?kat_id=$KAT_ID&ID=$kat_id' title='Kategorie bearbeiten'><img src='../../share/images/edit.gif' style='border:none;' width='11' height='11' hspace='0' vspace='0' border='0' alt='Edit-Icon'></a>
						</TD></TR>";
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
			FOR ($N=0; $N<$level; $N++)
			{
				$space .="&#160;&#160;&#160;";
			}
			
			//Link fuer den Ruecksprung erzeugen, d.h. naechst hoeheren Knoten aufrufen:
			$kat_id_back = array_search($kat_id, $knoten_arr);
			
			IF (in_array($kat_id, $knoten_arr))
			{
				//echo $kat_id_back;
				$img = "<IMG src='../../share/images/minus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
				echo 	"<TR class='kat'><TD class='kat1'>
					".$space."<a href='kategorie0.php?kat_id=$kat_id_back'>".$img."</a>&#160;&#160;<font color=".setFontColor($ID, $kat_id).">".$kategorie."</font><BR>
					</TD>
					<TD class='kat2'><a href='kat_edit.php?kat_id=$KAT_ID&ID=$kat_id' title='Kategorie bearbeiten'><img src='../../share/images/edit.gif' style='border:none;' width='11' height='11' hspace='0' vspace='0' border='0' alt='Edit-Icon'></a>
					</TD></TR>";
				getElements($kat_id, $knoten_arr, $KAT_ID, $ID);
			}
			ELSE
			{
				$img = "<IMG src='../../share/images/plus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
				echo 	"<TR class='kat'><TD class='kat1'>
					".$space."<a href='kategorie0.php?kat_id=$kat_id'>".$img."</a>&#160;&#160;<font color=".setFontColor($ID, $kat_id).">".$kategorie."</font><BR>
					</TD>
					<TD class='kat2'><a href='kat_edit.php?kat_id=$KAT_ID&ID=$kat_id' title='Kategorie bearbeiten'><img src='../../share/images/edit.gif' style='border:none;' width='11' height='11' hspace='0' vspace='0' border='0' alt='Edit-Icon'></a>
					</TD></TR>";
			}
		}
	echo "</TABLE>
	</center>
	</div>
	</fieldset>
	</div>
	
	<div id='spalte2'>
		<fieldset style='background-color:none; margin-top:10px;'>
			<legend style='color:blue; font-weight:bold;'>Name der neuen Unterkategorie</legend>
			<div id='scrollbox1' style='overflow-y:scroll;'>
				<center>";
				//das eigentliche Bearbeitungs-Formular:
				$result2 = mysql_query( "SELECT * FROM $table4 WHERE kat_id='$ID'");
				$kategorie_alt = mysql_result($result2, isset($i2), 'kategorie');
				echo "<p id='elf' style='padding: 5px; width: 400px; margin-top: 40px;'>
				Tragen Sie hier bitte den Namen<BR>der neuen Unter-Kategorie ein:<BR><BR>
				Diese wird unterhalb der Kategorie <BR><BR><font color='red'>\"".$kategorie_alt."\"</font><BR><BR>angelegt und darf	max. 30 Zeichen lang sein.<BR><BR></P>";
				
				echo "<FORM name='kat_neu' action='kat_add_action1.php?kat_id=$KAT_ID&level=$level&ID=$ID' method='POST'>
				<INPUT type='hidden' name='parent' value='$ID'>
				<INPUT type='hidden' name='level' value='$level_neu'>
				<INPUT type='text' name='kategorie' value='' size='30' maxlength='30' onkeydown='countChars(this.value)'>&#160;
				<input type='hidden' value='0' readonly='readonly' size='3' name='Kontrolle'>
				<INPUT type='submit' value='Speichern'>&#160;
				<INPUT TYPE = 'button' VALUE = 'Abbrechen' OnClick='location.href=\"kategorie0.php?kat_id=0\"'>
				</FORM>
				</center>
			</div>
		</fieldset>
	</div>
	
	<div id='foot'>
		<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
	</div>

</div>";

mysql_close($conn);
?>
<script language="javascript">
document.kat_neu.kategorie.focus();
</script>
</DIV></CENTER>
</BODY>
</HTML>