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
 * Copyright (c) 2006 - 2012 Klaus Henneberg
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

//welche Meta-Daten sind in der Tabelle pictures enthalten?
//sind diese als Felder in der Tabelle exif_protect enthalten? -> ggf. Aktualisierung
//Darstellung aller Meta-Daten-Felder in tabellarischer Form mit der Option diese writable zu schalten / zu sperren

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/ajax_functions.php';

$exiftool = buildExiftoolCommand($sr);

//Ermittlung von Username, User-Sprache zur Uebersetzung der Meta-Tags
$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');
$lang = mysql_result($result0, isset($i0), 'language');

//Erzeugung verschiedener Arrays zur Identifizierung der Meta-Tag-Gruppen:
$iptc_tags = array();
$exif_tags = array();
$xmp_tags = array();

$iptc_tags = explode(' ', shell_exec($exiftool." -list -IPTC:All"));
$exif_tags = explode(' ', shell_exec($exiftool." -list -EXIF:All"));
$xmp_tags = explode(' ', shell_exec($exiftool." -list -XMP:All"));

//Bereinigung der Array-Inhalte:

FOR ($i=0 ; $i<count($iptc_tags);$i++) 
{
	if ($iptc_tags[$i]== "") 
    {
    	unset ( $iptc_tags[$i] );
    }
}

FOR ($i1=0 ; $i1<count($iptc_tags);$i1++) 
{
	@$IPTCTAGS = trim($iptc_tags[$i1]);
	$iptc_tags[$i1] = $IPTCTAGS;
}

FOR ($j=0 ; $j<count($exif_tags);$j++) 
{
    
	if ($exif_tags[$j]== "") 
    {
    	unset ( $exif_tags[$j] );
    }
}

FOR ($j1=0 ; $j1<count($exif_tags);$j1++) 
{
	@$EXIFTAGS = trim($exif_tags[$j1]);
	$exif_tags[$j1] = $EXIFTAGS;
}

FOR ($k=0 ; $k<count($xmp_tags);$k++) 
{
    
	if ($xmp_tags[$k]== "") 
    {
    	unset ( $xmp_tags[$k] );
    }
}

FOR ($k1=0 ; $k1<count($xmp_tags);$k1++) 
{
	@$XMPTAGS = trim($xmp_tags[$k1]);
	$xmp_tags[$k1] = $XMPTAGS;
}

//Ermittlung aller Metadaten-Felder anhand der Tabelle meta_protect
//$result1 = mysql_query( "SELECT * FROM $table5");
$result1 = mysql_query( "SELECT field_name, lfdnr, writable, viewable FROM $table5 GROUP BY field_name");
$num1 = mysql_num_rows($result1);
//echo "Alle: ".$num1."<BR>";

$result2 = mysql_query( "SELECT field_name, lfdnr, writable, viewable FROM $table5 GROUP BY field_name");
$num2 = mysql_num_rows($result2);
//echo "ohne Dubletten: ".$num2."<BR>";


$ed_fieldname = array();

FOR($i1='0'; $i1<$num1; $i1++)
{
	$field_name = mysql_result($result1, $i1, 'field_name');
	$ed_fieldname[$i1] = $field_name;		// allgem. vorh. Tabellen-Feldname in der Tabelle meta_protect (nur Metadatenfelder)
}

$elements_number = count($ed_fieldname);

$col_groups = 3;		//3 Spaltengruppen ; je Gruppe eine Spalte field_name und eine Spalte writable
//Berechnung der Zeilenzahl bei 6 Spalten (entspr. 3 Feldwerten + 3 Checkboxen):
$rows = ceil($elements_number / 3);

//echo $elements_number." Felder ergeben ".$rows." Zeilen<BR>";
$content = "<TABLE class = 'tablenormal' border='0'>
		<TR class='trflach'>
		<TD colspan = '6'></TD>
		</TR>
		
		<TR>
		<TD colspan = '6' align='center'><FONT COLOR='red'>NUR F&Uuml;R EXPERTEN!</FONT>&#160;&#160;Hier legen Sie fest, welche Meta-Daten manuell ver&auml;ndert werden d&uuml;rfen&#160;&#160;<FONT COLOR='red'>NUR F&Uuml;R EXPERTEN!</FONT></TD>
		</TR>
		
		<TR>
		<TD colspan = '6' align='center'>Farb-Codierung: <FONT COLOR='red'>EXIF-Daten</FONT>&#160;&#160;<FONT COLOR='green'>IPTC-Daten</FONT>&#160;&#160;<FONT COLOR='blue'>XMP-Daten</FONT>&#160;&#160;<FONT COLOR='black'>sonstige Meta-Daten</FONT></TD>
		</TR>
		
		<TR class='trflach'>
		<TD colspan = '6'></TD>
		</TR>";

FOR($r='0'; $r<$rows; $r++)
{
	$content = $content."<TR>";
	FOR($cg='0'; $cg<$col_groups; $cg++)
	{
		$i1 = ($r * 3) + $cg;
		@$lfdnr = mysql_result($result1, $i1, 'lfdnr');
		@$field_name = (string)mysql_result($result1, $i1, 'field_name');
		//echo $field_name."<BR>";
		@$writable = mysql_result($result1, $i1, 'writable');
		IF($writable == '1')
		{
			$checked = 'checked';
		}
		ELSEIF($writable == '0')
		{
			$checked = '';
		}
		IF($field_name != '')
		{
			//den Meta-tag-Gruppen werden individuelle Farben zugewiesen:
			IF(in_array($field_name, $iptc_tags))
			{
				$color = 'green';
				$title = 'IPTC-Tag, kann zur Bearbeitung freigegeben werden';
			}
			
			ELSEIF(in_array($field_name, $exif_tags))
			{
				$color = 'red';
				$title = 'EXIF-Tag, sollte nicht ver&auml;ndert werden!';
			}
			
			ELSEIF(in_array($field_name, $xmp_tags))
			{
				$color = 'blue';
				$title = 'XMP-Tag, sollte nicht ver&auml;ndert werden!';
			}
			
			ELSE
			{
				$color = 'black';
				$title = 'sonstige Angabe, sollte nicht ver&auml;ndert werden!';
			}

			//Uebersetzung des Metadaten-Feldes in die Benutzersprache:
			$result2 = mysql_query("SELECT `$field_name` FROM $table20 WHERE lang = '$lang'");
			@$fnt = mysql_result($result2, isset($i2), `$field_name`); // $fnt: field_name_translated; in die Sprache des angemeldeten Users uebersetzter Feldname
			if($fnt != '')
			{
				$content = $content."<TD class='tdbreit'><a href=# title = \"$title\", style=\"color:".$color."; text-decoration:none;\">".$fnt."</a></TD>
				<TD class='tdschmal'>
				<div id='$lfdnr'>
				<INPUT TYPE=CHECKBOX $checked name='cb' value='$writable' onClick='changeWritable(\"$lfdnr\",\"$checked\",\"$sr\")'>
				</div>
				</TD>";
			}
			else
			{
				$content = $content."<TD class='tdbreit'><a href=# title = \"$title\", style=\"color:".$color."; text-decoration:none;\">".$field_name."</a></TD>
				<TD class='tdschmal'>
				<div id='$lfdnr'>
				<INPUT TYPE=CHECKBOX $checked name='cb' value='$writable' onClick='changeWritable(\"$lfdnr\",\"$checked\",\"$sr\")'>
				</div>
				</TD>";
			}
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

echo "
	<div class='page'>
	
		<p id='kopf'>pic2base :: Meta-Daten-Freigabe <span class='klein'>(User: ".$username.")</span></p>
		
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