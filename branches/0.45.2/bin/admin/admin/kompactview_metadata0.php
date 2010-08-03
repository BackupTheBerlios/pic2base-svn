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
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Meta-Daten-Freigabe</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
	<!--<meta http-equiv="Refresh" Content="3600; URL=generate_exifdata0.php">-->
	<style type="text/css">
	<!--
	.tablenormal	{
			width:720px;
			margin-left:40px;
			}
			
	.trflach	{
			height:3px;
			background-color:#FF9900
			}
			
	.tdbreit	{
			width:200px;
			text-align:right;
			}
			
	.tdschmal	{
			width:40px;
			text-align:center;
			}
	-->
	</style>
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: protect_metadata0.php
 *
 * Copyright (c) 2006 - 2009 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 *Formular zur Freigabe/Sperrung der Editierbarkeit von Metadaten
 */

//welche Meta-Daten sind in der Tabelle exif_data enthalten?
//sind diese als Felder in der Tabelle exif_protect enthalten? -> ggf. Aktualisierung
//Darstellung aller Meta-Daten-Felder in tabellarischer Form mit der Option diese writable zu schalten / zu sperren

unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = preg_split('#,#',$_COOKIE['login']);
//echo $c_username;
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/ajax_functions.php';

//####################################################################################################
$result1 = mysql_query( "SHOW COLUMNS FROM $table14");
//$struktur = array();
$i1 = 0;
if($result1 != false)
{
	//echo "Liste der Felder in meta_data:<BR>";
	while($liste1 = mysql_fetch_row($result1))
	{
		$ed_fieldname[$i1] = $liste1[0];	//vorh. Tabellen-Feldname in der Tabelle exif_data (ed)
		$ed_fieldtype[$i1] = $liste1[1];	//vorh. Tabellen-Feldtyp in der Tabelle exif_data (ed)
		$i1++;
	}
	FOREACH($ed_fieldname AS $EDFN)
	{
		//echo $EDFN."<BR>";
		$result2 = mysql_query( "SELECT * FROM $table5 WHERE field_name = '$EDFN' AND field_name <> 'ed_id' AND field_name <> 'pic_id'");
		@$treffer = mysql_num_rows($result2);
		SWITCH($treffer)
		{
			CASE 0:
			//echo "<FONT COLOR='red'>Das Feld ".$EDFN." ist nicht in der exif_protect-Tabelle.</FONT><BR>";
			//exif_protect-Tabelle wird aktualisiert:
			$result3 = mysql_query( "INSERT INTO $table5 (field_name, writable) VALUES ('$EDFN', '0')");
			break;
			
			CASE 1:
			//echo "<FONT COLOR='green'>Das Feld ".$EDFN." ist in der exif_protect-Tabelle.</FONT><BR>";
			break;
			
			CASE ($treffer > 1):
			echo "<FONT COLOR='red'>Das Feld ".$EDFN." ist MEHRFACH in der exif_protect-Tabelle enthalten!</FONT><BR>";
			break;
		}
	}
}

$elements_number = count($ed_fieldname) - 2;	//minus zwei, weil: Felder ed_id und pic_id werden nicht gezaehlt!
$col_groups = 3;		//3 Spaltengruppen ; je Gruppe eine Spalte field_name und eine Spalte viewable
//Berechnung der Zeilenzahl bei 6 Spalten (entspr. 3 Feldwerten):
$rows = ceil($elements_number / 3);
//echo $elements_number." Felder ergeben ".$rows." Zeilen<BR>";
$content = "<TABLE class = 'tablenormal' border='0'>
		<TR class='trflach'>
		<TD colspan = '6'></TD>
		</TR>
		
		<TR>
		<TD colspan = '6'>Hier legen Sie fest, welche Meta-Daten in der kompakten Detailansicht des Info-Fensters sichtbar sind</TD>
		</TR>
		
		<TR class='trflach'>
		<TD colspan = '6'></TD>
		</TR>";
$result2 = mysql_query( "SELECT * FROM $table5 WHERE field_name <> 'ed_id' AND field_name <> 'pic_id'");
FOR($r='0'; $r<$rows; $r++)
{
	$content = $content."<TR>";
	FOR($cg='0'; $cg<$col_groups; $cg++)
	{
		$i2 = ($r * 3) + $cg;
		@$lfdnr = mysql_result($result2, $i2, 'lfdnr');
		@$field_name = mysql_result($result2, $i2, 'field_name');
		@$viewable = mysql_result($result2, $i2, 'viewable');
		IF($viewable == '1')
		{
			$checked = 'checked';
		}
		ELSEIF($viewable == '0')
		{
			$checked = '';
		}
		IF($field_name !== '')
		{
			$content = $content."<TD class='tdbreit'>".$field_name."</TD>
			<TD class='tdschmal'>
			<div id='$lfdnr'>
			<INPUT TYPE=CHECKBOX '$checked' name='cb' value='$viewable' onClick='changeViewable(\"$lfdnr\",\"$checked\",\"$sr\")'>
			</div>
			</TD>";
		}
		ELSE
		{
			$content = $content."<TD class='tdbreit'></TD>
			<TD class='tdschmal'></TD>";
		}
	}
	$content = $content."</TR>";
}
$content = $content."</TABLE>";


//####################################################################################################

echo "
	<div class='page'>
	
		<p id='kopf'>pic2base :: Meta-Daten-Ansicht <span class='klein'>(User: ".$c_username.")</span></p>
		
		<div class='navi' style='clear:right;'>
			<div class='menucontainer'>";
			include '../../html/admin/adminnavigation.php';
			echo "
			</div>
		</div>
		
		<div class='content'>
		<p style='margin-top:20px; margin-left:10px; text-align:center'>";
		echo $content;
		echo "</p>
		</div>
		<br style='clear:both;' />
	
		<p id='fuss'><A style='margin-right:745px;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>".$cr." </p>
	
	</div>
</DIV>
</CENTER>
</BODY>
</HTML>";
?>