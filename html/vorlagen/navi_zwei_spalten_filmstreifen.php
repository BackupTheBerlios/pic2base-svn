<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Datensatz-Bearbeitung</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../css/format1.css'>
	<link rel="shortcut icon" href="../share/images/favicon.ico">
</HEAD>

<script language="JavaScript" type="text/javascript">
<!--

function ZeigeBild(bildname,breite,hoehe)
{
  var ref,parameter,dateiname,htmlcode,b=breite,h=hoehe;

  dateiname=bildname.substring(bildname.indexOf("/")+1,bildname.length);

  htmlcode="<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
  htmlcode+="<html style=\"height: 100%\">\n<head>\n<title>"+dateiname+"<\/title>\n";
  htmlcode+="<\/head>\n<body style=\"margin: 0; padding: 0; height: 100%\"><center>\n";
  htmlcode+="<img src=\""+bildname+"\" height=\"100%\" alt=\""+bildname+"\" title=\"[Mausklick schlie&szlig;t Fenster!]\" onclick=\"window.close()\">\n</center><\/body>\n<\/html>\n";

  parameter="width="+b+",height="+h+",screenX="+(screen.width-b)/2+",screenY="+(screen.height-h)/2+",left="+(screen.width-b)/2+",top="+(screen.height-h)/2;

  ref=window.open("","bildfenster",parameter);
  ref.document.open("text/html");
  ref.document.write(htmlcode);
  ref.document.close();
  ref.focus();
}

//-->
</script>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?

/*
 * Project: pic2base
 * File: ###.php
 *
 * Copyright (c) 2006 - 2007 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 * @copyright 2003-2005 Klaus Henneberg
 * @author Klaus Henneberg
 * @package pic2base
 * @license http://www.opensource.org/licenses/osl-2.1.php Open Software License
 */

unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = split(',',$_COOKIE['login']);
//echo $c_username;
}
 
include '../share/db_connect1.php';

$result1 = mysql($db, "SELECT * FROM $table1 WHERE username = '$c_username' AND aktiv = '1'");
$berechtigung = mysql_result($result1, $i1, 'berechtigung');
SWITCH ($berechtigung)
{
	//Admin
	CASE $berechtigung == '1':
	$navigation = 	"<a class='navi' href='adminframe.php'>Administration</a>
			<a class='navi' href='erfassung0.php'>Erfassung</a>
			<a class='navi' href='auswahl0.php?mod=rech'>Recherche</a>
			<a class='navi' href='start.php'>Zurück</a>
			<a class='navi' href='hilfe1.php'>Hilfe</a>
			<a class='navi' href='../../index.php'>Logout</a>";
	break;
	
	//Owner
	CASE $berechtigung == '5':
	$navigation = 	"<a class='navi' href='erfassung0.php'>Erfassung</a>
			<a class='navi' href='recherche1.php'>Recherche</a>
			<a class='navi' href='vorschau.php'>Bearbeitung</a>
			<a class='navi' href='hilfe1.php'>Hilfe</a>
			<a class='navi' href='../../index.php'>Logout</a>";
	break;
	
	//Web-User
	CASE $berechtigung == '9':
	$navigation = 	"<a class='navi' href='recherche1.php'>Recherche</a>
			<a class='navi' href='hilfe1.php'>Hilfe</a>
			<a class='navi' href='../../index.php'>Logout</a>";
	break;
}

echo "
<div class='page'>
	<FORM name='kat-zuweisung', method='post' action='edit_kat_daten_action.php'>
	<p id='kopf'>pic2base :: Datensatz-Bearbeitung</p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>
			".$navigation."
		</div>
	</div>
	
	<div id='spalte1F'>
		
	</div>
	
	<div id='spalte2F'>
		
	</div>
	
	<div id='filmstreifen'>";
	include '../share/get_preview.php';
	echo"
	</div>";
	IF ($button_view !=='0')
	{
		echo "<p align='right' style='margin-right:20px;'><INPUT type='submit'>&#160;&#160;&#160;<INPUT type='button' value='Abbrechen' OnClick='location.href=\"edit_start.php\"'></p>";
	}
	ELSE
	{
		echo "<p align='right' style='margin-right:20px;'><INPUT type='submit' disabled>&#160;&#160;&#160;<INPUT type='button' value='Abbrechen' OnClick='location.href=\"edit_start.php\"'></p>";
	}
echo "
</FORM>
	<p id='fuss'><A style='margin-right:745px;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>".$cr."</p>
	
</div>";

mysql_close($conn);
?>
</DIV>
</CENTER>
</BODY>
</HTML>