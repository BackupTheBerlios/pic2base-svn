<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Startseite</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<!-- <script type="text/javascript" src="../../ajax/inc/prototype.js"></script>-->
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: kategorie0.php
 *
 * Copyright (c) 2003 - 2012 Klaus Henneberg
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
include $sr.'/bin/share/functions/ajax_functions.php';

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

$kat_id = $_GET['kat_id'];
$ID = $_GET['ID'];

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

?>

<div class="page">

	<p id="kopf">pic2base :: Admin-Bereich - Kategorie-Bearbeitung</p>
	
	<div class="navi" style="clear:right;">
		<div class="menucontainer">
			<?php
			echo $navigation;
			?>
		</div>
	</div>
	
	<div id="spalte1">
		<?php
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
		
		echo "<TABLE id='kat'>";
		
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
			if($kat_id_pos > 0)
			{
				$kat_id_back = $knoten_arr[$kat_id_pos - 1];
			}
			//echo "Kat-ID: ".$kat_id.", ID: ".$ID.", Font_Color: ".setFontColor($ID, $kat_id)."<BR>";
			IF (in_array($kat_id, $knoten_arr))
			{
				//echo $kat_id_back;
				$img = "<IMG src='../../share/images/minus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
				echo 	"<TR id='kat'><TD id='kat1'>
					".$space."<a href='kategorie0.php?kat_id=$kat_id_back'>".$img."</a>&#160;&#160;<font color=".setFontColor($ID, $kat_id).">".$kategorie."</font>
					</TD>
					<TD id='kat2'><a href='kat_edit.php?kat_id=$KAT_ID&ID=$kat_id' title='Kategorie bearbeiten'><img src='../../share/images/edit.gif' style='border:none;' width='11' height='11' hspace='0' vspace='0' border='0' alt='Edit-Icon'></a>
					</TD></TR>";
				getElements($kat_id, $knoten_arr, $KAT_ID, $ID);
			}
			ELSE
			{
				$img = "<IMG src='../../share/images/plus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
				echo 	"<TR id='kat'><TD id='kat1'>
					".$space."<a href='kategorie0.php?kat_id=$kat_id'>".$img."</a>&#160;&#160;<font color=".setFontColor($ID, $kat_id).">".$kategorie."</font>
					</TD>
					<TD id='kat2'><a href='kat_edit.php?kat_id=$KAT_ID&ID=$kat_id' title='Kategorie bearbeiten'><img src='../../share/images/edit.gif' style='border:none;' width='11' height='11' hspace='0' vspace='0' border='0' alt='Edit-Icon'></a>
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
			echo 	"<TR id='kat'><TD id='kat1'>
				".$space."<a href='kategorie0.php?kat_id=$kat_id_back'>".$img."</a>&#160;&#160;<font color=".setFontColor($ID, $kat_id).">".$kategorie."</font><BR>
				</TD>
				<TD id='kat2'><a href='kat_edit.php?kat_id=$KAT_ID&ID=$kat_id' title='Kategorie bearbeiten'><img src='../../share/images/edit.gif' style='border:none;' width='11' height='11' hspace='0' vspace='0' border='0' alt='Edit-Icon'></a>
				</TD></TR>";
			getElements($kat_id, $knoten_arr, $KAT_ID, $ID);
		}
		ELSE
		{
			$img = "<IMG src='../../share/images/plus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
			echo 	"<TR id='kat'><TD id='kat1'>
				".$space."<a href='kategorie0.php?kat_id=$kat_id'>".$img."</a>&#160;&#160;<font color=".setFontColor($ID, $kat_id).">".$kategorie."</font><BR>
				</TD>
				<TD id='kat2'><a href='kat_edit.php?kat_id=$KAT_ID&ID=$kat_id' title='Kategorie bearbeiten'><img src='../../share/images/edit.gif' style='border:none;' width='11' height='11' hspace='0' vspace='0' border='0' alt='Edit-Icon'></a>
				</TD></TR>";
		}
	}
	echo "</TABLE>
	</p>
	</div>
	
	<div id='spalte2'><center>";
	//das eigentliche Bearbeitungs-Formular:
	$result2 = mysql_query( "SELECT * FROM $table4 WHERE kat_id='$ID'");
	$kategorie_alt = mysql_result($result2, isset($i2), 'kategorie');
	echo "<div id='bar_1'>
	<p id='elf' style='padding: 5px; width: 350px; margin-top: 40px; margin-bottom:50px;'>
	Nehmen Sie hier bitte die &Auml;nderungen<BR>f&uuml;r die ausgew&auml;hlte Kategorie vor:</P>";
	
	echo "<FORM name='kat_neu' action='kat_edit_action1.php?kat_id=$KAT_ID&ID=$ID' method='POST'>
         <INPUT type='text' name='kategorie' value=\"$kategorie_alt\" size='30' maxlength='30'>&#160;
         <INPUT type='submit' value='&Auml;ndern'>&#160;
         <!--<INPUT TYPE='button' VALUE='&Auml;ndern 2' onClick='changeLocationName($KAT_ID, $ID, document.kat_neu.kategorie.value)'>-->
         <INPUT TYPE = 'button' VALUE = 'Abbrechen' OnClick='location.href=\"kategorie0.php?kat_id=0\"'>
         <p style='margin-top:60px; width:350px;'>
         <u>Bitte beachten Sie:</u></p>
         <p style='margin-top:20px; width:350px;'>
         Nachdem die Bezeichnung der Kategorie ge&auml;ndert wurde, werden auch alle Eintr&auml;ge in den Meta-Daten der betreffenden Bilder aktualisiert.</p>
         <p style='margin-top:20px; width:350px;'>
         Dies kann - je nach Rechenleistung und Anzahl der Bilder - eine Weile dauern.</p>
         <p style='margin-top:20px; width:350px;'>
         Der Vorgang ist abgeschlossen, wenn in der linken Spalte<BR>der ge&auml;nderte Kategoriename angezeigt wird.</p>
       </FORM>
       
	</center>
	</div>
	
	<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>

</div>";

mysql_close($conn);
?>

</DIV></CENTER>
</BODY>
</HTML>