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

ini_set('memory_limit', '500M');

echo "<font color='white'>Speicher-Nutzung: ".memory_get_usage()."</font>";

/*
 * Project: pic2base
 * File: kat_repair1.php
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

unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
}
//$benutzername = $c_username;
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
//########################################################################################################
		echo "<p style='margin-top:50px;'><u>Test 1: Kontrolle auf mehrfache Kategoriezuweisungen</u></p>";
		
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
//########################################################################################################
		echo "	<p style='margin-top: 50px; margin-bottom:30px;'><u>Test 2: Kontrolle, ob alle Vorschaubilder vorhanden sind</u></p>
				<center>
				Status der &Uuml;berpr&uuml;fung
				<div id='prog_bar' style='border:solid; border-color:red; width:300px; height:12px; margin-top:20px; text-align:left; vertical-align:middle'>
					<img src='../../share/images/green.gif' name='bar' /><br>
					<center>
					<p id = 'record_nr'>".$X."</p>
					</center>
				</div>
				</center>
				
				<p id='meldung_0' style='color:green; margin-top:50px;'>".$meldung_0."</p>
			
				<p id='meldung_1' style='color:green; margin-top:50px;'>".$meldung_1."</p>
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

</div>
</CENTER>
</BODY>";
//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX	
	//temporaer genutzte Tabelle wird geleert:
	$result22 = mysql_query("TRUNCATE `ICE_V_pic_kat_dubls`");
	echo mysql_error();
//#########################	
	//es wird fuer alle Eintraege in der tabelle pictures geprueft, ob die Vorschaubilder
	//FileNameHQ, FileNameV, FileNameHist, FileNameHist_r, FileNameHist_g, FileNameHost_b und FileNameMono vorhanden sind. 
	//Wenn nicht, werden diese erzeugt.
	//#########################################
	$start1 = microtime();	 //Beginn der Laufzeitmessung:
	//#########################################

	//In der Tabelle pictures werden Felder, in denen eigentlich ein Dateiname stehen muesste, mit dem vorgesehenen Eintrag belegt, damit es weiter unten zu einer Differenbildung kommen kann:
	$result23 = mysql_query("UPDATE $table2 SET FileNameHQ = CONCAT(pic_id,'_hq.jpg') WHERE FileNameHQ = ''");
	$result24 = mysql_query("UPDATE $table2 SET FileNameV = CONCAT(pic_id,'_v.jpg') WHERE FileNameV = ''");
	$result25 = mysql_query("UPDATE $table2 SET FileNameHist = CONCAT(pic_id,'_hist.gif') WHERE FileNameHist = ''");
	$result26 = mysql_query("UPDATE $table2 SET FileNameHist_r = CONCAT(pic_id,'_hist_r.gif') WHERE FileNameHist_r = ''");
	$result27 = mysql_query("UPDATE $table2 SET FileNameHist_g = CONCAT(pic_id,'_hist_g.gif') WHERE FileNameHist_g = ''");
	$result28 = mysql_query("UPDATE $table2 SET FileNameHist_b = CONCAT(pic_id,'_hist_b.gif') WHERE FileNameHist_b = ''");
	$result29 = mysql_query("UPDATE $table2 SET FileNameMono = CONCAT(pic_id,'_mono.jpg') WHERE FileNameMono = ''");
	//$limit = 1000;	
	echo mysql_error();
	//$result30 = mysql_query( "SELECT * FROM $table2 LIMIT $limit");
	$result30 = mysql_query( "SELECT * FROM $table2");
	echo mysql_error();
	WHILE($row = mysql_fetch_array($result30))
	{
		$hq_files_soll[$row[0]] = $row[3];
		$v_files_soll[$row[0]] = $row[4];
		$hist_files_soll[$row[0]] = $row[5];
		$hist_r_files_soll[$row[0]] = $row[6];
		$hist_g_files_soll[$row[0]] = $row[7];
		$hist_b_files_soll[$row[0]] = $row[8];	
		$mono_files_soll[$row[0]] = $row[9];
	}
	
	ksort($hq_files_soll);
	ksort($v_files_soll);
	ksort($hist_files_soll);	//print_r($hist_files_soll);
	ksort($hist_r_files_soll);
	ksort($hist_g_files_soll);
	ksort($hist_b_files_soll);	
	ksort($mono_files_soll);	//print_r($mono_files_soll);
	
	//#########################################
	$end1 = microtime();
	list($start1msec, $start1sec) = explode(" ",$start1);
	list($end1msec, $end1sec) = explode(" ",$end1);
	$runtime1 = round(($end1sec + $end1msec) - ($start1sec + $start1msec),4);
	$meldung_0 .= "Lesen / sortieren der Soll-Dateien nach: <b>".$runtime1."</b> Sek. beendet.<br />";
	//#########################################
	
	$erledigt = 0;	//Anzahl der erledigten Datei-Wiederherstellungen
	$n_hq = 0;
	$n_v = 0;
	$mf = 0;
	//echo $kml_dir;
	//Ermittlung der Dateien, die in den Vorschau-Ordnern liegen:
	$a[] = exec("ls ".$pic_hq_path."/", $hq_files_ist);	//print_r($retval2);
	$b[] = exec("ls ".$pic_thumbs_path."/", $v_files_ist);
	$c[] = exec("ls ".$hist_path."/", $hist_files_ist);
	$d[] = exec("ls ".$monochrome_path."/", $mono_files_ist);

//#####################################	
	ksort($hq_files_ist);		
	ksort($v_files_ist);
	sort($hist_files_ist);	//print_r($hist_files_ist);
	//Das Hist-Array des Ist-Bestandes ist aus Performance-Gruenden in die 4 Bestandteile (grau / rot / gruen / blau) zu zerlegen,
	//ansonsten gibt es bei der folgenden Differenzbildung erhebliche Verzoegerungen:
	echo "<BR><BR>";
	$hist_r_files_ist = array();
	$hist_g_files_ist = array();
	$hist_b_files_ist = array();
	foreach($hist_files_ist AS $key => $value)
	{
		//echo $key." - ".$value."<BR>";
		IF(stristr($value, 'hist_0.gif'))
		{
			$hist_r_files_ist[] = $value;
			unset($hist_files_ist[$key]);
		}
		ELSEIF(stristr($value, 'hist_1.gif'))
		{
			$hist_g_files_ist[] = $value;
			unset($hist_files_ist[$key]);
		}
		ELSEIF(stristr($value, 'hist_2.gif'))
		{
			$hist_b_files_ist[] = $value;
			unset($hist_files_ist[$key]);
		}
	}
	ksort($hist_files_ist);
	ksort($hist_r_files_ist);
	ksort($hist_g_files_ist);
	ksort($hist_b_files_ist);
	/*
	echo "Grau-Array:<BR>";
	print_r($hist_files_ist);
	echo "<BR>Rot-Array:<BR>";
	print_r($hist_r_files_ist);
	echo "<BR>Gruen-Array:<BR>";
	print_r($hist_g_files_ist);
	echo "<BR>Blau-Array:<BR>";
	print_r($hist_b_files_ist);
	*/
	ksort($mono_files_ist);	
//####################################

	//#########################################
	$end2 = microtime();
	list($start1msec, $start1sec) = explode(" ",$start1);
	list($end2msec, $end2sec) = explode(" ",$end2);
	$runtime2 = round(($end2sec + $end2msec) - ($start1sec + $start1msec),4);
	$meldung_0 .= "Lesen / sortieren der Ist-Dateien nach: <b>".$runtime2."</b> Sek. beendet.<br />";
	//#########################################

	//Ermittlung der Differenzen zwischen Soll und Ist:	
	$hq_files_diff = array_diff($hq_files_soll, $hq_files_ist);
	$v_files_diff = array_diff($v_files_soll, $v_files_ist);
	$hist_files_diff = array_diff($hist_files_soll, $hist_files_ist);
	$hist_r_files_diff = array_diff($hist_r_files_soll, $hist_r_files_ist);
	$hist_g_files_diff = array_diff($hist_g_files_soll, $hist_g_files_ist);
	$hist_b_files_diff = array_diff($hist_b_files_soll, $hist_b_files_ist);
	$mono_files_diff = array_diff($mono_files_soll, $mono_files_ist);
//###################################
	
	//nicht mehr bnoetigte Arrays werden geleert:	
	unset($hist_files_ist);
	unset($hist_r_files_ist);
	unset($hist_g_files_ist);
	unset($hist_b_files_ist);
	unset($hq_files_ist);
	unset($v_files_ist);
	
	unset($hist_files_soll);
	unset($hist_r_files_soll);
	unset($hist_g_files_soll);
	unset($hist_b_files_soll);
	unset($hq_files_soll);
	unset($v_files_soll);
	
	//#########################################
	$end3 = microtime();
	list($start1msec, $start1sec) = explode(" ",$start1);
	list($end3msec, $end3sec) = explode(" ",$end3);
	$runtime3 = round(($end3sec + $end3msec) - ($start1sec + $start1msec),4);
	$meldung_0 .= "Vergleich des Soll-Ist-Bestandes nach <b>".$runtime3."</b> Sek. beendet.<br />";
	//#########################################

	//fehlende Histogramme und monochr. Dateien werden im Array $hist_mono_files_diff zusammengefasst:
	$hist_mono_files_diff = array();
	foreach($hist_files_diff AS $key => $value)
	{
		$hist_mono_files_diff[] = $key;	
	}
	foreach($hist_r_files_diff AS $key => $value)
	{
		$hist_mono_files_diff[] = $key;		
	}
	foreach($hist_g_files_diff AS $key => $value)
	{
		$hist_mono_files_diff[] = $key;		
	}
	foreach($hist_b_files_diff AS $key => $value)
	{
		$hist_mono_files_diff[] = $key;		
	}
	foreach($mono_files_diff AS $key => $value)
	{
		$hist_mono_files_diff[] = $key;		
	}
	
	//fuer den Fortschrittsbalken wird die Anzahl der neu zu erstellenden Dateien ermittelt:
	$i_neu = count($hq_files_diff) + count($v_files_diff) + count($hist_files_diff) + count($hist_r_files_diff) + count($hist_g_files_diff) + count($hist_b_files_diff) + count($mono_files_diff);
	if($i_neu == 0)
	{
		?>
			<SCRIPT language="JavaScript">
			document.bar.src='../../share/images/green.gif';
			document.bar.width=<?php echo '300'?>;
			document.bar.height='11';
			document.getElementById('record_nr').innerHTML='<?php echo "Alle erforderlichen Dateien sind vorhanden.";?>';
			</SCRIPT>
		<?php
	}
	else
	{
		$meldung_0 .= "davon<BR>HQ: ".count($hq_files_diff)."<BR>V: ".count($v_files_diff)."<BR>Hist: ".count($hist_files_diff)."<BR>Hist_R: ".count($hist_r_files_diff)."<BR>Hist_G: ".count($hist_g_files_diff)."<BR>Hist_B: ".count($hist_b_files_diff)."<BR>Mono: ".count($mono_files_diff)."<BR>Hist+Mono: ".count($hist_mono_files_diff)."<BR>";
	}
	//#########################################
	$end4 = microtime();
	list($start1msec, $start1sec) = explode(" ",$start1);
	list($end4msec, $end4sec) = explode(" ",$end4);
	$runtime4 = round(($end4sec + $end4msec) - ($start1sec + $start1msec),4);
	$meldung_0 .= "Aufgabenliste nach <b>".$runtime4."</b> Sekunden zusammengestellt.<br />";
	//#########################################
	ob_start();
//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX	
//##############################################___________Erstellung der fehlenden Bilder___________###############################################################################	

	// Erstellung fehlender HQ-Vorschaubilder:
	// Die Datensatz-ID der unvollstaendigen Vorschaubilder ergibt sich aus dem Schluessel des jeweiligen Diff-Arrays:
	foreach($hq_files_diff AS $key => $value)
	{
		//echo $key."<BR>";
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
		$result31 = mysql_query("SELECT FileName FROM $table2 WHERE pic_id = '$key'");
		echo mysql_error();
		$FileName = mysql_result($result31, isset($i31), 'FileName');
		$FILE = $pic_path."/".$FileName;
		$dest_path = $pic_hq_path;
		$max_len = 800;
		$FileNameHQ = resizeOriginalPicture($FILE, $dest_path, $max_len, $sr);
		$fileHQ = $pic_hq_path."/".$FileNameHQ;
		clearstatcache();  
		chmod ($fileHQ, 0700);
		clearstatcache();
		$n_hq++;
		$erledigt++;
		if($i_neu > 0)
		{
			$laenge = (round(($erledigt / $i_neu) * 300));
			$anteil = number_format((($erledigt / $i_neu)*100),2,',','.');
		}
		else
		{
			$laenge = 300;
			$anteil = 100;
		}
		?>
			<SCRIPT language="JavaScript">
			document.bar.src='../../share/images/green.gif';
			document.bar.width=<?php echo $laenge?>;
			document.bar.height='11';
			document.getElementById('record_nr').innerHTML='<?php echo "... bearbeite Datensatz ".$erledigt." von ".$i_neu."...<BR>(".$anteil." %)";?>';
			</SCRIPT>
		<?php
	}
	
	
	// Erstellung fehlender V-Vorschaubilder:
	// Die Datensatz-ID der unvollstaendigen Vorschaubilder ergibt sich aus dem Schluessel des jeweiligen Diff-Arrays:
	foreach($v_files_diff AS $key => $value)
	{
		//echo $key."<BR>";
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
		$result32 = mysql_query("SELECT FileNameHQ FROM $table2 WHERE pic_id = '$key'");
		echo mysql_error();
		$FileNameHQ = mysql_result($result32, isset($i32), 'FileNameHQ');
		$FILE = $pic_hq_path."/".$FileNameHQ;
		$dest_path = $pic_thumbs_path;
		$max_len = 160;
		$FileNameV = createPreviewPicture($FILE, $dest_path, $max_len, $sr);
		$fileV = $pic_thumbs_path."/".$FileNameV;
		clearstatcache();  
		chmod ($fileV, 0700);
		clearstatcache();
		$n_v++;
		$erledigt++;
		if($i_neu > 0)
		{
			$laenge = (round(($erledigt / $i_neu) * 300));
			$anteil = number_format((($erledigt / $i_neu)*100),2,',','.');
		}
		else
		{
			$laenge = 300;
			$anteil = 100;
		}
		?>
			<SCRIPT language="JavaScript">
			document.bar.src='../../share/images/green.gif';
			document.bar.width=<?php echo $laenge?>;
			document.bar.height='11';
			document.getElementById('record_nr').innerHTML='<?php echo "... bearbeite Datensatz ".$erledigt." von ".$i_neu."...<BR>(".$anteil." %)";?>';
			</SCRIPT>
		<?php
	}
	
	// Erstellung fehlender HIST-Vorschaubilder:
	// Die Datensatz-ID der unvollstaendigen Vorschaubilder ergibt sich aus dem Schluessel des jeweiligen Diff-Arrays:
	foreach($hist_mono_files_diff AS $pid)
	{
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
		$pic_id = $pid;
		$result33 = mysql_query("SELECT FileNameHQ FROM $table2 WHERE pic_id = '$pic_id'");
		echo mysql_error();
		$FileNameHQ = mysql_result($result33, isset($i33), 'FileNameHQ');
		//Erstellung fehlender Histogramme und monochrome-Bilder:
		//$mf - missing files -> Zahl der erneuerten Histogramme / monochrome Bilder
		$zv = generateHistogram($pic_id,$FileNameHQ,$sr);
		if($zv > 0)
		{
			$mf = $mf + $zv;
			$erledigt = $erledigt + $zv;	
		}
		else
		{
			//echo "Gibt es ein Problem?";
		}
		if($i_neu > 0)
		{
			$laenge = (round(($erledigt / $i_neu) * 300));
			$anteil = number_format((($erledigt / $i_neu)*100),2,',','.');
		}
		else
		{
			$laenge = 300;
			$anteil = 100;
		}
		?>
			<SCRIPT language="JavaScript">
			document.bar.src='../../share/images/green.gif';
			document.bar.width=<?php echo $laenge?>;
			document.bar.height='11';
			document.getElementById('record_nr').innerHTML='<?php echo "... bearbeite Datensatz ".$erledigt." von ".$i_neu."...<BR>(".$anteil." %)";?>';
			</SCRIPT>
		<?php
	}

	
	
//#######################################################___________Bilderstellung abgeschlossen____________#######################################################

	
	
	//#########################################
	$end5 = microtime();
	list($start1msec, $start1sec) = explode(" ",$start1);
	list($end5msec, $end5sec) = explode(" ",$end5);
	$runtime5 = round(($end5sec + $end5msec) - ($start1sec + $start1msec),4);
	$meldung_0 .= "Aufgabe nach <b>".$runtime5."</b> Sekunden erledigt (".$erledigt." Bilder)";
	//#########################################
	
	mysql_close($conn);
	
	
	if($i_neu > 0)
	{
		$meldung_1 = "Es wurden <BR>".$n_hq." HQ-Vorschaubilder und <BR>".$n_v." Thumb-Vorschaubilder neu erzeugt.<BR>Bei dem Histogramm- und Monochrome-Bestand<BR>wurden ".$mf." Korrekturen vorgenommen.<BR>Ausf&uuml;hrungsdauer: ".$runtime5." Sekunden";
	}
	//echo $meldung_1;
	?>
	
	<SCRIPT language="JavaScript">
	document.getElementById('meldung_0').innerHTML='<?php echo $meldung_0?>';
	</SCRIPT>
	
	<SCRIPT language="JavaScript">
	document.getElementById('meldung_1').innerHTML='<?php echo $meldung_1?>';
	</SCRIPT>

</HTML>