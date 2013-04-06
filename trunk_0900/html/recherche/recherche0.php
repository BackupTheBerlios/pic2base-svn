<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Recherche</TITLE>
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
		
		function switchBewertung(bewertung)
		{
			document.cookie = "bewertung=" + bewertung;
		} 
		
	</script>
</HEAD>

<?php
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/permissions.php';

//Zugriffskontrolle ######################################################
IF (!$_COOKIE['uid'])
{
	//var_dump($sr);
  	header('Location: ../../../index.php');
}
ELSE
{
	$uid = $_COOKIE['uid'];
	IF(!hasPermission($uid, 'searchpic', $sr))
	{
		header('Location: ../../../index.php');
	}
}
//########################################################################
//############## gibt es eine eingestellte Bewertung? ####################
if(!isset($_COOKIE['bewertung']))
{
	if( array_key_exists('bewertung',$_POST) AND !empty($_POST['bewertung']) )
	{
		$bewertung = $_POST['bewertung'];
		setcookie('bewertung',$_POST['bewertung']);
	}
	else
	{
		$bewertung = '';
		setcookie('bewertung',$bewertung);
	}
}
//########################################################################
//############## sind wir evtl. im Kollektions-Modus? ####################
if($_COOKIE['search_modus'])
{
	$search_modus = $_COOKIE['search_modus'];	//echo $search_modus;
}
else
{
	$search_modus = 'normal';
}
?>



<BODY>
<DIV Class="klein">
<?php

/*
 * Project: pic2base
 * File: recherche0.php
 *
 * Copyright (c) 2003 - 2013 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 */

//include $sr.'/bin/css/initial_layout_settings.php';

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');

$result2 = mysql_query("SELECT * FROM $table2");
$num2 = mysql_num_rows($result2);

echo "
<div class='page' id='page'>";

	if($search_modus !== 'collection')
	{
		echo "
		<div class='head' id='head'>
			pic2base :: Recherche-&Uuml;bersicht <span class='klein'>(User: ".$username.")</span>
		</div>";
	}
	else
	{
		echo "
		<div class='head' id='head'>
			pic2base :: Recherche-&Uuml;bersicht <span class='klein'>(User: ".$username.")</span> Sie befinden sich im Suchmodus f&uuml;r Kollektionen! <input type='button' style='vertical-align:middle;' value='Zum Normalmodus' onCLick='document.cookie = \"search_modus=normal; path=/\"; location.reload();'>
		</div>";
	}
	
	echo "
	<div class='navi' id='navi'>
		<div class='menucontainer'>";
		createNavi2($uid);
		echo "
		</div>
	</div>

	<div id='spalte1'>";
	
	IF( array_key_exists('bewertung',$_COOKIE) )
	{
		$bewertung = $_COOKIE['bewertung'];
	}
	if ( empty($bewertung) OR !isset($bewertung) )
	{
		$bewertung = '';
	}
	IF ($num2 > 0)
	{
		$action = $_SERVER['PHP_SELF'];
		$sel1 = $sel2 = $sel3 = $sel4 = $sel5 = $sel6 = '';
		$sel21 = $sel22 =$sel31 = $sel32 = $sel41 = $sel42 = '';
		$bew = $bewertung;
		SWITCH($bew)
		{
			CASE '=1':
			$sel1 = 'selected';
			break;
			
			CASE '>=2':
			$sel21 = 'selected';
			break;
			
			CASE '=2':
			$sel2 = 'selected';
			break;
			
			CASE '<=2':
			$sel22 = 'selected';
			break;
			
			CASE '>=3':
			$sel31 = 'selected';
			break;
			
			CASE '=3':
			$sel3 = 'selected';
			break;
			
			CASE '<=3':
			$sel32 = 'selected';
			break;
			
			CASE '>=4':
			$sel41 = 'selected';
			break;
			
			CASE '=4':
			$sel4 = 'selected';
			break;
			
			CASE '<=4':
			$sel42 = 'selected';
			break;
			
			CASE '=5':
			$sel5 = 'selected';
			break;
			
			CASE '6':
			CASE '':
			$sel6 = 'selected';
			break;
		}
		echo "
		
		<font color='#efeff7'>
			<p  style='margin-top:20px;'>.</p>
		</font>
		<fieldset style='background-color:none; margin-top:10px;'>
		<legend style='color:blue; font-weight:bold;'>Auswahl der Qualit&auml;t</legend>
			<br>Legen Sie hier bitte zun&auml;chst fest, nach welchen<BR>Qualit&auml;tsmerkmalen recherchiert werden soll:<BR><BR>
			Finde alle Bilder, f&uuml;r die gilt: Es sind ...<br><br>
			
			<center>
				<form name='quality' action=$action method='post'>
				<select name = 'bewertung' size = '1' class='Auswahl250' style='width:304px;' OnChange='switchBewertung(quality.bewertung.value)'>
				<option value = '=1' $sel1>sehr gute Bilder</option>
				<option value = '=2' $sel2>gute Bilder</option>
				<option value = '=3' $sel3>befriedigende Bilder</option>
				<option value = '=4' $sel4>gen&uuml;gende Bilder</option>
				<option value = '=5' $sel5>ungen&uuml;gende Bilder</option>
				<option value = '>=2' $sel21>gute oder bessere Bilder</option>
				<option value = '<=2' $sel22>gute oder schlechtere Bilder</option>
				<option value = '>=3' $sel31>befriedigende oder bessere Bilder</option>
				<option value = '<=3' $sel32>befriedigende oder schlechtere Bilder</option>
				<option value = '>=4' $sel41>gen&uuml;gende oder bessere Bilder</option>
				<option value = '<=4' $sel42>gen&uuml;gende oder schlechtere Bilder</option>
				<option value = '6' $sel6>alle Bilder</option>
				</select>
				</FORM>
				<br>
			</center>
			
		</fieldset>
			
		<fieldset style='background-color:none; margin-top:20px;'>
		<legend style='color:blue; font-weight:bold;'>Auswahl der Suchoption</legend>
			<center>
			<br>
			<a class='subnavi' href='recherche2.php?pic_id=0&mod=zeit&s_m=J'>Suche nach Aufnahmedatum</a>
			<a class='subnavi' href='recherche2.php?pic_id=0&mod=kat'>Suche nach Kategorien</a>
			<a class='subnavi' href='recherche2.php?pic_id=0&mod=exif'>Suche nach Meta-Daten</a>
			<a class='subnavi' href='recherche2.php?pic_id=0&mod=desc'>Suche nach Beschreibungstext</a>
			<a class='subnavi' href='recherche2.php?pic_id=0&mod=geo'>Suche nach Geo-Daten</a>
			<a class='subnavi_blind'></a>
			<a class='subnavi' href='recherche2.php?pic_id=0&mod=collection'>Suche nach Kollektionen</a>
			<!--<a class='subnavi' href='recherche2.php?pic_id=0&mod=expert_d'>Experten-Suche (nach Aufnahme-Datum)</a>
			<a class='subnavi' href='recherche2.php?pic_id=0&mod=expert_kd'>Experten-Suche (nach Kategorie u. Datum)</a>-->
			<BR>
			</center>
		</fieldset>";
	}
	ELSE
	{
		echo "
		<font color='#efeff7'>
			<p  style='margin-top:20px;'>.</p>
		</font>	
		<fieldset style='background-color:lightyellow; margin-top:10px;'>
		<legend style='color:blue; font-weight:bold;'>Hinweis</legend>
			<center>
			<br>
			<p style='color:red; text-align:center; font-weight:bold;'>Es gibt zur Zeit keine Eintr&auml;ge in der Datenbank!</p>
			<BR>
			</center>
		</fieldset>";
	}
	echo "
	</div>
	
	<div id='spalte2'>
	
		<font color='#efeff7'>
			<p  style='margin-top:20px;'>.</p>
		</font>	
		<fieldset style='background-color:none; margin-top:10px;'>
		<legend style='color:blue; font-weight:bold;'>Hilfe zu den Suchm&ouml;glichkeiten</legend>
			<div id='help' style='padding-top:10px;'>
			Ausf&uuml;hrliche Hilfe zu den Suchm&ouml;glichkeiten finden Sie &uuml;ber den Button 'Hilfe' in der linken Navigationsleiste oder direkt <a href='../help/help1.php?page=2'>hier</a>.
			<br><br>
			Wenn Sie sich im Suchmodus f&uuml;r Kollektionen befinden, werden ausgew&auml;hlte Bilder der aktiven Kollektion zugeordnet. Es erfolgt kein Download der Bilder.<br>
			</div>
		</fieldset>
	
	</div>

	<div class='foot' id='foot'>
		<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
	</div>

</div>";

mysql_close($conn);
?>
</DIV>
</BODY>
</HTML>