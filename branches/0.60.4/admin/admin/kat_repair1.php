<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - DB-Wartung</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<!--<script type="text/javascript" src="../../../ajax/inc/prototype.js"></script>-->
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: kat_repair1.php
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
 */

unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
}
 
INCLUDE '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/permissions.php';
include $sr.'/bin/share/functions/main_functions.php';
//include $sr.'/bin/share/functions/ajax_functions.php';

IF(hasPermission($c_username, 'editkattree'))
{
	$navigation = "
			<a class='navi' href='kat_sort1.php'>Sortierung</a>
			<a class='navi_blind' href='kat_repair1.php'>Wartung</a>
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

echo "
<div class='page'>

	<p id='kopf'>pic2base :: Admin-Bereich - Datenbank-Wartung</p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
		//echo $navigation."
		 include '../adminnavigation.php';
		echo "</div>
	</div>
	
	<div id='spalte1'>";

	echo "<BR><BR><BR><u>Test 1: Kontrolle auf mehrfache Kategoriezuweisungen</u><BR><BR>";
	
	// Zum entfernen von Dubletten (unter der Annahme, dass die Spalte lfdnr nirgends im Programmcode verwendet wird):
	// Alle Mehrfacheinträge von pic_id und kat_id zusammen mit der kleinsten lfdnr in die Tabelle ICE_V_pic_kat_dubls eintragen
	$result20 = mysql_query("INSERT ICE_V_pic_kat_dubls(lfdnr, pic_id, kat_id, anzahl) SELECT MIN(lfdnr) as lfdnr, pic_id, kat_id, count(*) as anzahl FROM pic_kat GROUP BY pic_id, kat_id HAVING COUNT(*) > 1");
	echo mysql_error();
	// Auf der Basis dieser Steuertabelle löschen wir alle korrespondierende Datensätze aus pic_kat
	$result21 = mysql_query("DELETE pk FROM `pic_kat` as pk, `ICE_V_pic_kat_dubls` as pkd WHERE pk.pic_id = pkd.pic_id AND pk.kat_id = pkd.kat_id AND NOT(pk.lfdnr = pkd.lfdnr)");
	echo mysql_error();
	$num21 = mysql_affected_rows();
	IF($num21 == '0')
	{
		$meldung_2 = "<p style='color:green;'>Es gab keine mehrfachen Kategoriezuweisungen.</p>";
	}
	ELSE
	{
		$meldung_2 = "<p style='color:red;'>Anzahl der korrigierten Mehrfachzuweisungen: ".$num21."</p>";
	}
	echo $meldung_2;
	
	//temporaer genutzte Tabelle wird geleert:
	$result22 = mysql_query("TRUNCATE `ICE_V_pic_kat_dubls`");
	echo mysql_error();
//#########################	
	echo "<BR><BR><BR><u>Test 2: Kontrolle, ob alle Vorschaubilder vorhanden sind</u><BR><BR>";
	//es wird fuer alle Eintraege in der tabelle pictures geprueft, ob die Vorschaubilder
	//FileNameHQ, FileNameV, FileNameHist, FileNameHist_r, FileNameHist_g, FileNameHost_b und FileNameMono vorhanden sind. 
	//Wenn nicht, werden diese erzeugt.
	$result30 = mysql_query( "SELECT * FROM $table2");
	$num30 = mysql_num_rows($result30);
	$n_hq = 0;
	$n_v = 0;
	
	echo "<center>
			Status der &Uuml;berpr&uuml;fung
			<div id='prog_bar' style='border:solid; border-color:red; width:300px; height:12px; margin-top:20px; text-align:left; vertical-align:middle'>
				<img src='../../share/images/green.gif' name='bar' />
			</div>
		</center>";
	
	FOR($i30='0'; $i30<$num30; $i30++)
	{
		$X = $i30 + 1;						//$X - Zaehlvariable fuer den Fortschrittsbalken
		
		//Errechnung der Parameter fuer den Fortschrittsbalken:
		
		// Bei neueren Browsern muessen mind. 256 Byte uebertragen werden, damit der Inhalt des OutputBuffers geliefert wird
		// und der Fortschrittsbalken korrekt aktualisiert wird.
		// Also wird ein wenig Muell erzeugt...
		//#################################################################################################################
		FOR($z=0; $z<300; $z++)
		{
			echo "                                                                                                              ";
		}
		usleep(100);
		if( ob_get_level() > 0 ) ob_flush();
		//######################################  genug Muell erzeugt  ####################################################
		
		$laenge = (round(($X / $num30) * 300));
		$anteil = number_format((($X / $num30)*100),2,',','.');?>
		
		<SCRIPT language="JavaScript">
		document.bar.src='../../share/images/green.gif';
		document.bar.width=<?php echo $laenge?>;
		document.bar.height='11';
		</SCRIPT>
		
		<?php
		$pic_id = mysql_result($result30, $i30, 'pic_id');
		$FileName = mysql_result($result30, $i30, 'FileName');
		$FileNameHQ = mysql_result($result30, $i30, 'FileNameHQ');
		$FileNameV = mysql_result($result30, $i30, 'FileNameV');
		
		IF(!file_exists($pic_hq_path."/".$FileNameHQ))
		{
			//echo "HQ-Vorschau zum Bild ".$FileName." fehlt.<BR>";
			$FILE = $pic_path."/".$FileName;
			$dest_path = $pic_hq_path;
			$max_len = 800;
			$FileNameHQ = resizeOriginalPicture($FILE, $dest_path, $max_len, $sr);
			$fileHQ = $pic_hq_path."/".$FileNameHQ;
			clearstatcache();  
			chmod ($fileHQ, 0700);
			clearstatcache();
			$n_hq++;
		}
		
		IF(!file_exists($pic_thumbs_path."/".$FileNameV))
		{
			//echo "Thumbnail-Vorschau zum Bild ".$FileName." fehlt.<BR>";
			$FILE = $pic_hq_path."/".$FileNameHQ;
			$dest_path = $pic_thumbs_path;
			$max_len = 160;
			$FileNameV = createPreviewPicture($FILE, $dest_path, $max_len, $sr);
			$fileV = $pic_thumbs_path."/".$FileNameV;
			clearstatcache();  
			chmod ($fileV, 0700);
			clearstatcache();
			$n_v++;
		}
		
		//Parameter: Bild-ID, Name der HQ-Datei (z.B. 21456_hq.jpg), Server-Root
		//Funktion beinhaltet eine interne Pruefung, ob eine Bilddatei fehlt
		generateHistogram($pic_id,$FileNameHQ,$sr);
	}
	
	echo "<p style='color:green;'>Es wurden <BR>".$n_hq." HQ-Vorschaubilder und <BR>".$n_v." Thumb-Vorschaubilder neu erzeugt.<BR>
	Die Histogramme und Monochrome-Vorschaubilder wurden ebenfalls &uuml;berpr&uuml;ft.</p>";
	//flush();
	
//#########################	
	echo "
	</div>
	
	<DIV id='spalte2'>
		<p id='elf' style='background-color:white; padding: 5px; width: 365px; margin-top: 20px; margin-left: 20px;'>Hinweis:<BR><BR>
		Die &Uuml;berpr&uuml;fung ist abgeschlossen<BR>und lieferte die links stehenden Ergebnisse.<BR><BR>
		Bevor Sie weitere Schritte unternehmen, sollten Sie abschlie&szlig;end die Dublettenpr&uuml;fung vornehmen.<BR>
		Dies dauert nur einen Moment, stellt aber sicher, da&szlig; Sie keine Datens&auml;tze doppelt erfa&szlig;t haben.<BR><BR>
		Klicken Sie hierzu auf diesen Button:<BR>
		<center><input type='button' value='zur Dublettenpr&uuml;fung' onClick='location.href=\"../../html/erfassung/doublettenliste1.php?method=all&c_username=$c_username\"'></center></p>
	</DIV>
	
	<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>

</div>";

mysql_close($conn);
?>
</DIV></CENTER>
</BODY>
</HTML>