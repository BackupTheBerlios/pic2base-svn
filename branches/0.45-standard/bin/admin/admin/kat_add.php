<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Kategorie hinzuf&uuml;gen</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?

/*
 * Project: pic2base
 * File: kat_add.php
 *
 * Copyright (c) 2003 - 2006 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 * @copyright 2003-2006 Klaus Henneberg
 * @author Klaus Henneberg
 * @package pic2base
 * @license http://www.opensource.org/licenses/osl-2.1.php Open Software License
 */

unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
}
 
INCLUDE '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';

$level = $_GET['level']; 
$level_neu = $level + 1;

$result1 = mysql_query( "SELECT * FROM $table1 WHERE username = '$c_username' AND aktiv = '1'");
$berechtigung = mysql_result($result1, isset($i1), 'berechtigung');
SWITCH ($berechtigung)
{
	//Admin
	CASE $berechtigung == '1':
	$navigation = 	"<a class='navi' href='../../html/admin/adminframe.php'>Zur&uuml;ck</a>	
			<a class='navi' href='../../html/start.php'>zur Startseite</a>
			<a class='navi' href='../../html/help/help1.php?page=5'>Hilfe</a>";
	break;
	
	//alle anderen
	CASE $berechtigung > '1':
	$navigation = 	"<a class='navi' href='../../../index.php'>Logout</a>";
	break;
}

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

?>

<div class="page">

	<p id="kopf">pic2base :: Admin-Bereich - Unterkategorie anlegen</p>
	
	<div class="navi" style="clear:right;">
		<div class="menucontainer">
			<?
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
			if ($kat_id_pos > 0)
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
	
	<div id='spalte2'>
		<center>";
		//das eigentliche Bearbeitungs-Formular:
		$result2 = mysql_query( "SELECT * FROM $table4 WHERE kat_id='$ID'");
		$kategorie_alt = mysql_result($result2, isset($i2), 'kategorie');
		echo "<p id='elf' style='padding: 5px; width: 400px; margin-top: 40px;'>
		Tragen Sie hier bitte den Namen der neuen Unter-Kategorie ein:<BR>
		Diese wird unterhalb der Kategorie <font color='red'>\"".$kategorie_alt."\"</font> angelegt und darf	max. 30 Zeichen lang sein.<BR><BR></P>";
		
		echo "<FORM name='kat_neu' action='kat_add_action1.php?kat_id=$KAT_ID&level=$level&ID=$ID' method='POST'>
		<INPUT type='hidden' name='parent' value='$ID'>
		<INPUT type='hidden' name='level' value='$level_neu'>
		<INPUT type='text' name='kategorie' value='' size='30' maxlength='30'>&#160;
		<INPUT type='submit' value='Speichern'>&#160;
		<INPUT TYPE = 'button' VALUE = 'Abbrechen' OnClick='location.href=\"kategorie0.php?kat_id=0\"'>
		</FORM>
		</center>
	</div>
	
	<p id='fuss'>(C)2006 Logiqu</p>

</div>";

mysql_close($conn);
?>
<script language="javascript">
document.kat_neu.kategorie.focus();
</script>
<p class="klein">- KH 09/2006 -</P>
</DIV></CENTER>
</BODY>
</HTML>