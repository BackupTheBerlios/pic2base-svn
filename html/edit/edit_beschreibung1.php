<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Datensatz-Bearbeitung</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
</HEAD>

<script language="JavaScript" type="text/javascript">
<!--

function ZeigeBild(bildname,breite,hoehe)
{
  var ref,parameter,dateiname,htmlcode,b=breite,h=hoehe;

  dateiname=bildname.substring(bildname.indexOf("/")+1,bildname.length);

  htmlcode="<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
  htmlcode+="<html style=\"height: 100%\">\n<head>\n<title>"+dateiname+"<\/title>\n";
  htmlcode+="<\/head>\n<body style=\"margin: 0; padding: 0; height: 100%\">\n";
  htmlcode+="<img src=\""+bildname+"\" width=\"100%\" height=\"100%\" alt=\""+bildname+"\" title=\"[Mausklick schlie&szlig;t Fenster!]\" onclick=\"window.close()\">\n<\/body>\n<\/html>\n";

  parameter="width="+b+",height="+h+",screenX="+(screen.width-b)/2+",screenY="+(screen.height-h)/2+",left="+(screen.width-b)/2+",top="+(screen.height-h)/2;

  ref=window.open("","bildfenster",parameter);
  ref.document.open("text/html");
  ref.document.write(htmlcode);
  ref.document.close();
  ref.focus();
}

//-->
</script>

<script type="text/javascript" src="../../share/FCKeditor/fckeditor.js">
</script>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?

/*
 * Project: pic2base
 * File: start.php
 *
 * Copyright (c) 2003 - 2005 Klaus Henneberg
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
 
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';

$result1 = mysql($db, "SELECT * FROM $table1 WHERE username = '$c_username' AND aktiv = '1'");
$berechtigung = mysql_result($result1, $i1, 'berechtigung');
SWITCH ($berechtigung)
{
	//Admin
	CASE $berechtigung == '1':
	$navigation = 	"<a class='navi' href='adminframe.php'>Administration</a>
			<a class='navi' href='../erfassung/erfassung0.php'>Erfassung</a>
			<a class='navi' href='../recherche/recherche0.php'>Suche</a>
			<a class='navi' href='../start.php'>Zurück</a>
			<a class='navi' href='hilfe1.php'>Hilfe</a>
			<a class='navi' href='../../../index.php'>Logout</a>";
	break;
	
	//Owner
	CASE $berechtigung == '5':
	$navigation = 	"<a class='navi' href='../erfassung/erfassung0.php'>Erfassung</a>
			<a class='navi' href='../recherche/recherche0.php'>Suche</a>
			<a class='navi' href='hilfe1.php'>Hilfe</a>
			<a class='navi' href='../../../index.php'>Logout</a>";
	break;
	
	//Web-User
	CASE $berechtigung == '9':
	$navigation = 	"<a class='navi' href='../recherche/recherche0.php'>Suche</a>
			<a class='navi' href='hilfe1.php'>Hilfe</a>
			<a class='navi' href='../../../index.php'>Logout</a>";
	break;
}

echo "
<div class='page'>
	
	<p id='kopf'>pic2base :: Datensatz-Bearbeitung</p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>
			".$navigation."
		</div>
	</div>
	
	<div id='spalte1F'>";
	?>
	<script type="text/javascript">
	var oFCKeditor = new FCKeditor('entry');
	
	oFCKeditor.BasePath="../../share/FCKeditor/";
	oFCKeditor.Create();
	oFCKeditor.DefaultLanguage="de";
	oFCKeditor.Height=300;
	
	</script>
	<?
	echo "	
	</div>
	
	<div id='spalte2F'>
		
	</div>
	
	<div id='filmstreifen'>
	</div>
	

</FORM>
	<p id='fuss'><A style='margin-right:745px;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>".$cr."</p>
	
</div>";

mysql_close($conn);
?>
</DIV>
</CENTER>
</BODY>
</HTML>