<?php
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/permissions.php';

//Zugriffskontrolle ######################################################
IF (!$_COOKIE['login'])
{
//var_dump($sr);
  header('Location: ../../../index.php');
}
ELSE
{
	unset($c_username);
	IF ($_COOKIE['login'])
	{
		list($c_username) = preg_split('#,#',$_COOKIE['login']);
		IF(!hasPermission($c_username, 'searchpic', $sr))
		{
			header('Location: ../../../index.php');
		}
	}
}
//########################################################################

//var_dump($_COOKIE);
IF( array_key_exists('bewertung',$_POST) AND !empty($_POST['bewertung']) )
{
	setcookie('bewertung',$_POST['bewertung']);
}
else if ( array_key_exists('bewertung',$_COOKIE) )
	{
		$bewertung = $_COOKIE['bewertung'];
	}
	else
	{
		$bewertung = '';
		setcookie('bewertung',$bewertung);
	}
?>

<script language="JavaScript">
function switchBewertung(bewertung)
{
	document.cookie = "bewertung=" + bewertung;
}
</script>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Recherche</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: recherche0.php
 *
 * Copyright (c) 2003 - 2010 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 */

include $sr.'/bin/share/functions/ajax_functions.php';
$result2 = mysql_query("SELECT * FROM $table2");
$num2 = mysql_num_rows($result2);

?>
<div class="page">

	<p id="kopf">pic2base :: Recherche-&Uuml;bersicht <span class='klein'>(User: <?php echo $c_username;?>)</span></p>
	<div class="navi" style="clear:right;">
		<div class="menucontainer">
		<?php
		createNavi2($c_username);
		//echo $navigation;
		?>
		</div>
	</div>
	<div id="spalte1">
	
	<?php
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
		<TABLE border='0'>
		<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900'>
		</TD>
		</TR>
		
		<TR class='normal' style='height:12px;'>
		<TD class='normal'>
		</TD>
		</TR>
		
		<TR>
		<TD width='400px' height='3px' bgcolor='white'><p id='11' align='center'>
		Legen Sie hier bitte zun&auml;chst fest, nach welchen<BR>Qualit&auml;tsmerkmalen recherchiert werden soll:<BR><BR>
		Finde alle Bilder, f&uuml;r die gilt: Es sind ...</p>
		<form name='quality' action=$action method='post'>
		<CENTER>
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
		</CENTER>
		</FORM>
		</TD>
		</TR>
		
		<TR class='normal' style='height:12px;'>
		<TD class='normal'>
		</TD>
		</TR>
		
		<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900'>
		</TD>
		</TR>
		
		<TR class='normal' style='height:12px;'>
		<TD class='normal'>
		</TD>
		</TR>
		</TABLE>
		
		<center>
		<a class='subnavi' href='recherche2.php?pic_id=0&mod=zeit&s_m=J'>Suche nach Aufnahmedatum</a>
		<a class='subnavi' href='recherche2.php?pic_id=0&mod=kat'>Suche nach Kategorien</a>
		<a class='subnavi' href='recherche2.php?pic_id=0&mod=exif'>Suche nach Meta-Daten</a>
		<a class='subnavi' href='recherche2.php?pic_id=0&mod=desc'>Suche nach Beschreibungstext</a>
		<a class='subnavi' href='recherche2.php?pic_id=0&mod=geo'>Suche nach Geo-Daten</a>
		<!--<a class='subnavi_blind'></a>
		<a class='subnavi' href='recherche2.php?pic_id=0&mod=expert_k'>Experten-Suche (nach Kategorien)</a>
		<a class='subnavi' href='recherche2.php?pic_id=0&mod=expert_d'>Experten-Suche (nach Aufnahme-Datum)</a>
		<a class='subnavi' href='recherche2.php?pic_id=0&mod=expert_kd'>Experten-Suche (nach Kategorie u. Datum)</a>-->
		<BR>
		</center>";
	}
	ELSE
	{
		echo "<p style='color:red; text-align:center; font-weight:bold;'>Es gibt zur Zeit keine Eintr&auml;ge in der Datenbank!</P>";
	}
		
	echo "
	</div>
	
	<div id='spalte2'><p id='elf' style='background-color:white; padding: 5px; width: 385px; margin-top: 4px; margin-left: 10px;'><b>Hilfe zu den Suchm&ouml;glichkeiten:</b><BR><BR>
	Ausf&uuml;hrliche Hilfe zu den Suchm&ouml;glichkeiten finden Sie &uuml;ber den Button 'Hilfe' in der Navigationsleiste.</p>
	</div>

	<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>

</div>";

mysql_close($conn);
?>
</DIV>
</CENTER>
</BODY>
</HTML>