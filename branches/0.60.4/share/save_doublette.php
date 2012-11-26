<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Doubletten-Best&auml;tigung</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../css/format1.css'>
	<link rel="shortcut icon" href="images/favicon.ico">
</HEAD>
<BODY LANG="de-DE" scroll = "auto">

<CENTER>
<DIV Class="klein">
<?php

/*
 * Diese Routine funktioniert so nicht!
 * Ein bild, das als Doublette eines anderen in der Datenbank verbleiben soll, muß als solches explizit gekennzeichnet werden, oder aber es muß z.B. durch einen Meta-Daten-Eintrag verändert werden, damit sich
 * das Identifikationskriterium (die md5-Summe) ändert
 */

if(array_key_exists('user_id',$_GET))
{
	$user_id = $_GET['user_id']; 
}
if(array_key_exists('pic_id',$_GET))
{
	$pic_id = $_GET['pic_id']; 
}

include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$user_id' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');

echo "
<div class='page'>
	<p id='kopf'>pic2base :: Doubletten-Best&auml;tigung <span class='klein'>(User: ".$username.")</span></p>
		
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>
		</div>
	</div>
		
	<div class='content'>
		<span style='font-size:12px;'>
		<p style='margin:120px 0px; text-align:center'>
		
		<center>";
		
		//echo "Bild ".$pic_id." wird aus der Tabelle ".$table21." gel&ouml;scht.";
		echo "Bild ".$pic_id." wird in die Datenbank &uuml;bernommen.";
		$result1 = mysql_query("DELETE FROM $table21 WHERE new_pic_id = '$pic_id'");
		// Beim nächsten Aufruf der Doublettenprüfung (siehe unten!) wird dieser Eintrag wieder in die Tabelle 21 geschrieben, also wieder der alte Zustand hergestellt!
		echo mysql_error();

		echo "	
		</center>
		
		</p>
		</span>
	</div>
	<br style='clear:both;' />
	<meta http-equiv='Refresh', content='1; ../html/erfassung/doublettenliste1.php?user_id=$user_id'>
	<p id='fuss'><A style='margin-right:780px;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>".$cr."</p>
</div>
</DIV>
</CENTER>
</BODY>";

?>


