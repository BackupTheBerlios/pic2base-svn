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
	<TITLE>pic2base - Hilfe</TITLE>
	<META NAME="GENERATOR" CONTENT="Eclipse">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format2.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script language="JavaScript" src="../../share/functions/resize_elements.js"></script>
	<script language="JavaScript" src="../../share/functions/jquery-1.8.2.min.js"></script>
	<script language="JavaScript">
		jQuery.noConflict();
		jQuery(document).ready(checkWindowSize);
		jQuery(window).resize(checkWindowSize); 
	</script>
</HEAD>

<BODY>
<CENTER>
<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: help1.php
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

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');

$conv = buildConvertCommand($sr);
$identify = str_replace('convert', 'identify', $conv);

$sup_ft_arr = explode(chr(10),shell_exec($identify." -list format"));  //unterstuetzte Dateiformate

$page = $_GET['page']; 

FOREACH($sup_ft_arr AS $SFT)
{
	$SFT = str_replace("  ","",$SFT);
	$zeil_arr = explode(chr(32),$SFT);
	if(isset($zeil_arr[2]))
	{
		$mod = $zeil_arr[2];
	}
	else
	{
		$mod = ' ';
	}
	IF($mod !== ' ')
	{
		$file_format = strtolower(str_replace('*','',$zeil_arr[0]));
	}
}

$needle = array("(", ")");
$datum = str_replace($needle, "", $vom);

echo "

<div class='page' id='page'>

		<div class='head' id='head'>
			pic2base :: Online-Hilfe
		</div>
	
	<div class='navi' id='navi'>
		<div class='menucontainer'>";
		createNavi4_1($uid);
		echo "
		</div>
	</div>
	
	<div class='content' id='content'>";
	SWITCH($page)
	{
		CASE '0':
		echo "
		<fieldset id='fieldset_help0'>
		<legend style='color:blue; font-weight:bold;'>Hinweise zur Startseite</legend>
		
		Das pic2base-Fenster unterteilt sich fast immer in 3 Bereiche:<BR><BR>
		<u>Kopfzeile</u><BR>
		Hier findet der Benutzer Hinweise zur gerade aufgerufenen Seite, unter wessen Namen er arbeitet und ggf. welche Suchkriterien ausgew&auml;hlt wurden.<BR><BR>
		<u>Navigations-Leiste</u><BR>
		Am linken Fensterrand ist die Navigations-Leiste angeordnet. Je nach erteilten Benutzer-Rechten stehen die unterschiedlichen Men&uuml;-Buttons zur Auswahl:
		<ul> jeweils f&uuml;r die <li>System-Administration</li>
		<li>pers&ouml;nliche Einstellungen</li>
		<li>Erfassung</li>
		<li>Bearbeitung</li>
		<li>Suche</li>
		<li>Hilfe</li>
		<li>und / oder Abmeldung vom pic2base-Server</li>
		</ul>
		<BR>
		<u>Daten-Bereich</u><BR>
		Den gr&ouml;&#223;ten Platz nimmt der Datenbereich ein. Hier werden alle Auswahl-Men&uuml;s, Vorschau-Bilder oder Detail-Informationen dargestellt.<br />
		Das Aussehen des Datenbereichs variiert je nach gew&auml;hlter Programm-Funktion bzw. Berechtigung des Benutzers.<BR>
		Weitere themenbezogene Hilfe erhalten Sie direkt in dem gew&auml;hlten Programm-Fenster.
		<br /><br />
		<b>Hilfreiche Tipps zur Arbeit mit pic2base:</b><BR><BR>
		Die folgende Reihenfolge der Arbeitsschritte hat sich f&uuml;r eine effektive Daten-Erfassung als zweckm&auml;&szlig;ig erwiesen:<br /><br />
		1) Bilder per FTP oder Einzelbild-Upload auf den Server laden, von der Startseite aus die Erfassung starten<BR>
    	(Alle neuen Bilder liegen nun in der Kategorie \"Neuzug&auml;nge\")<BR>
		2) falls Track-Daten vorliegen: Bilder georeferenzieren,<BR>
		3) Beschreibungen editieren; dabei die bei der Geo-Referenzierung ggf. hinzugef&uuml;gten Ortsbezeichnungen ber&uuml;cksichtigen<BR>
		4) Qualit&auml;ts-Bewertung vornehmen,<BR>
		(damit sind die individuellen Informationen zugewiesen)<BR>
		5) im letzten Schritt Kategorien zuweisen<br /><br />
		Mit dieser Reihenfolge ist sichergestellt, da&szlig; alle unbearbeiteten Bilder in der Kategorie \"Neuzug&auml;nge\" zu finden sind, bis sie im letzten Schritt in die einzelnen Unterkategorien verteilt werden.<BR>
		Sollen gr&ouml;ssere Mengen an Bildern erfasst werden, ist es zweckm&auml;&szlig;ig, nicht mehr als etwa 100 Bilder in einem Durchgang zu verarbeiten.<BR>
		Damit werden die Ladezeiten der einzelnen Bearbeitungsseiten kurz gehalten und die Arbeit geht insgesamt fl&uuml;ssiger vonstatten.
		<br /><br />
		<b>Aktuelle Informationen</B><BR><BR>
		Weitere Informationen zu pic2base erhalten Sie auf unserer <A HREF='http://www.pic2base.de'>Homepage</A>.<BR>
		<br /><br />
		<b>&Uuml;ber pic2base</B><BR><BR>
		Auf Ihrem Server ist die Version: ".$version." (Release ".$rel.") vom ".$datum." installiert.<BR><BR>
		<b>Entwickler:</b><BR><BR>
		Klaus Henneberg, <a href='mailto:info@pic2base.de?subject=Supportanfrage zur Version .$version.'><img src = \"../../share/images/letter.gif\" height=\"15\" border='0' title = 'Mail senden' align='top'></a><BR>
		Holger R&ouml;mer, <a href='mailto:hr@roemix.eu'><img src = \"../../share/images/letter.gif\" height=\"15\" border='0' title = 'Mail senden' align='top'></a><BR>
		Jens Henneberg<BR>
		Daniel Richter<BR>
		<br /><br />
		<TABLE style='margin-left:5px;' border='0'>
			<TR>
				<TD colspan='5'><b>Dieses Projekt verwendet u.a. die folgenden Komponenten:</b></TD>
			</TR>
			
			<TR>
				<TD colspan='5' style='background-color:#FF9900;'></TD>
			</TR>
			
			<TR align='center'>
				<TD style='width:100px;' align='left'><a href='http://www.imagemagick.org' title='ImageMagick: Convert, Edit, and Compose Images' target='blank'>ImageMagick</A></TD>
				<TD style='width:100px;'><a href='http://www.cybercom.net/~dcoffin/dcraw/' title='Decoding raw digital photos in Linux' target='blank'>dcraw</a></TD>
				<TD style='width:100px;'><a href='http://www.sno.phy.queensu.ca/~phil/exiftool/' title='Read, Write and Edit Meta Information!' target='blank'>ExifTool</a></TD>
				<TD style='width:100px;'><a href='http://www.gpsbabel.org/' title='GPSBabel - Free software for GPS data conversion and transfer.' target='blank'>GPSBabel</a></TD>
				<TD style='width:100px;'><a href='http://addons.xampp.org' title='Ein ProFTPd - Tool' target='blank'>ProFTPd-Frontend</a></TD>
				
			</TR>
			
			<TR align='center'>
				<TD><img src=\"../../share/images/imagemagick.jpg\" width=\"95\" /></TD>
				<TD></TD>
				<TD></TD>
				<TD><img src=\"../../share/images/GPSBabel.png\" width=\"95\" /></TD>
				<TD></TD>
			</TR>
			
			<TR>
				<TD colspan='5' style='background-color:#FF9900;'></TD>
			</TR>
		</TABLE>
		
		</fieldset>";
		break;
		
		CASE '1':
		echo "
		<fieldset id='fieldset_help1'>
		<legend style='color:blue; font-weight:bold;'>Hinweise zum Bild-Upload per FTP (Batch-Prozess)</legend>
		
		Derzeit (Software-Version ".$version.") werden die folgenden Datei-Typen von pic2base unterst&uuml;tzt:<BR>";
		$i='0';
		FOREACH($supported_filetypes AS $sft)
		{
			IF($i<'10')
			{
				echo $sft.", ";
			}
			ELSE
			{
				echo $sft."<BR>";
				$i='0';
			}
			$i++;
		}
		
		
		
		echo "
		<BR><BR>
		Unter Windows verwenden Sie einen geeigneten FTP-Client (z.B. FileZilla, WS-FTP) um Ihre Bilder auf den pic2base-Server hochzuladen.<br /> 
		LINUX-Benutzer k&ouml;nnen z.B. den Konqueror f&uuml;r den Upload benutzen, wobei sich eine zweigeteilte Fenster-Ansicht als 
		vorteilhaft erwiesen hat.<br /><br />
		Die Adresse des pic2base-FTP-Servers lautet ".$_SERVER['SERVER_NAME'].". 
		Melden Sie sich mit Ihren pers&ouml;nlichen Zugangsdaten (Benutzername: ".$username." und entsprechendem Passwort) an und 
		kopieren Ihre Bild-Dateien in Ihren <b>UPLOAD</b>-Ordner. Wenn Sie nun zu pic2base zur&uuml;ckkehren erhalten Sie 
		einen Hinweis, da&#223; Bilddateien in Ihrem Upload-Ordner liegen. Klicken Sie zur &Uuml;bernahme der Bilder in die Datenbank auf den Button 'Upload starten'. 
		Je nach Anzahl der Bilder und deren Dateigr&ouml;&#223;e kann der Upload eine Weile dauern.<BR>
		Am Ende des Uploads erfolgt eine automatische Dublettenkontrolle. Damit wird sichergestellt, da&szlig; identische Bilder nicht versehentlich mehrfach erfa&szlig;t werden.<BR>Im Anschlu&#223; daran werden Sie aufgefordert, 
		den soeben &uuml;bernommenen Bildern weitere Informationen hinzuzuf&uuml;gen (Geo.Koordinaten, Bewertung, Beschreibung) und abschlie&#223;end 
		allen Bildern Kategorien zuzuweisen. Eine sorgf&auml;ltige Anreicherung der Bilder mit Zusatzinformationen ist f&uuml;r die sp&auml;tere Recherche sehr von Vorteil.<br /><br />
		Beachten Sie weiterhin, da&szlig; der Dateiname eines hochzuladenden Bildes <b>keine ung&uuml;ltigen Zeichen</b> (Umlaute, Leerzeichen, &szlig;, mehrere Punkte etc.; siehe hierzu auch: <a href='http://de.wikipedia.org/wiki/Dateiname' target='top'>Wikipedia zum Thema \"Dateiname\"</a>) enth&auml;lt. Erlaubte Zeichen sind Ziffern, alle Buchstaben des angels&auml;chsischen Alphabets und der Unterstrich. Anderenfalls wird die Erfassung mit einem entsprechenden Hinweis abgebrochen.
		
		</fieldset>";
		break;
		
		CASE '2':
		echo "<a name='top'></a>
		<fieldset id='fieldset_help2'>
		<legend style='color:blue; font-weight:bold;'>Hilfe zu den Suchm&ouml;glichkeiten</legend>

		<br /><b><u>Inhalt:</u></b><br /><br />
		<a href='#2_bewertung'>Auswahl der Bild-Bewertung</a><BR>
		<a href='#2_zeit'>Suche nach Aufnahmedatum</a><BR>
		<a href='#2_kat'>Suche nach Kategorien</a><BR>
		<a href='#2_meta'>Suche nach ausgew&auml;hlten Meta-Daten</a><BR>
		<a href='#2_desc'>Suche nach Beschreibungstext</a><BR>
		<a href='#2_geo'>Suche nach Geo-Daten</a><BR>
		<a href='#2_coll'>Suche nach Kollektionen</a><BR>
		<a href='#2_film'>Was kann der Filmstreifen?</a><BR>
		<a href='#2_edit'>Bearbeitungsm&ouml;glichkeiten der Suchergebnisse</a><BR><BR>
		
		<a name = '2_bewertung'></a><B>Auswahl der Bild-Bewertung</B><BR><BR>
		Um die Anzahl der dargestellten Bilder einzuschr&auml;nken ist es m&ouml;glich, diese mit einer \"Benotung\" zu versehen. (siehe Bearbeitung | Bilder bewerten / Bewertung &auml;ndern)<BR>
		Die Note 1 (\"Sehr gut\"; 5 Sterne) stellt hierbei das h&ouml;chste Qualit&auml;tsniveau dar und die Note 5 (\"Ungen&uuml;gend\"; 1 Stern) das niedrigste.<BR>
		Wurde ein Bild noch nicht bewertet, erh&auml;lt es automatisch w&auml;hrend der Daten-Erfassung die Note 6 (kein Stern).<BR>
		&Uuml;ber den Punkt \"alle Bilder anzeigen\" l&auml;sst sich jedoch auch der gesamte Bildbestand - unabh&auml;ngig von einer eventuell vorhandenen Benotung - darstellen.<BR>
		Die momentan gew&auml;hlte Qualit&auml;tsstufe wird in der pic2base-Statusleiste angezeigt. Die dargestellten Sterne haben die folgende Bedeutung:<BR><BR>
		nur der erste (linke) Stern - gew&auml;hlte Benotung: ungen&uuml;gend<BR>
		nur der zweite Stern - gew&auml;hlte Benotung: gen&uuml;gend<BR>
		nur der dritte Stern - gew&auml;hlte Benotung: befriedigendgend<BR>
		nur der vierte Stern - gew&auml;hlte Benotung: gut<BR>
		nur der f&uuml;nf (rechte) Stern - gew&auml;hlte Benotung: sehr gut<BR><BR>
		Werden mehrere Sterne dargestellt (z.B. der dritte bis f&uuml;nfte), so bedeutet dies, es sollen alle Bilder gesucht werden, die die Note 3 (befriedigend) oder besser haben.<BR><BR>
		
		Hinweis:<BR>F&uuml;r diese Funktion m&uuml;ssen Cookies zugelassen sein!<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '2_zeit'></a><B>Suche nach Aufnahmedatum</B><BR><BR>
		Der gesamte Bild-Bestand wird nach dem Erstellungsdatum der Bilder unterteilt und Jahrgangsweise dargestellt. Das Erstellungsdatum wird bei der Bilderfassung aus den Meta-Daten ausgelesen.
		Handelt es sich um Bilddateien ohne Meta-Daten, werden diese Bilder in einem gesonderten Ordner (Bilder ohne zeitliche Zuordnung) zusammengefasst. Diesen Bildern kann - die entsprechenden Berechtigung vorausgesetzt - sp&auml;ter manuell das Aufnahmedatum zugewiesen werden.<BR>
		Die zeitliche Darstellung kann bis auf die Tages-Ebene heruntergebrochen werden, indem man durch Klicks auf das Plus-Icon die jeweils tiefere Ebene aufruft.<BR>
		So gelangt man vom Jahr zum Monat und weiter zum Tag. Mit einem Klick auf das Minus-Icon gelangt man jeweils eine Ebene zur&uuml;ck.<BR>
		Klickt man hinhegen auf den Zeitraum (angezeigtes Jahr, Monat oder Tag), werden alle Bilder dieses Zeitraumes dargestellt.<BR>
		Wenn man sich auf der Tages-Ebene befindet, ist hinter dem Datum ein graues oder gr&uuml;nes Buch-Icon zu sehen.<BR>
		&Uuml;ber das graue Buch-Icon kann zu dem betreffenden Tag ein neuer Tagebucheintrag angelegt werden. Ist das Buch-Icon gr&uuml;n, gibt es bereits einen Tagebucheintrag. Dieser kann mit einem Klick auf das gr&uuml;ne Icon bearbeitet werden.<BR>
		Hinweis:<BR>
		Der Inhalt des Fototagebuchs wird in der Detailansicht zu dem entsprechenden Bild mit angezeigt.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '2_kat'></a><B>Suche nach Kategorien</B><BR><BR>
		In dieser Ansicht besteht die M&ouml;glichkeit, Bilder anhand der zugewiesenen Kategorie zu recherchieren.<BR>
		Die oberste Ebene (Neuzug&auml;nge) beinhaltet alle Bilder, welchen noch keine Kategorie zugewiesen wurden.<BR>
		Die Ebenen darunter beinhalten Bilder der jeweiligen Kategorien.<BR>
		Mit einem Klick auf das Plus-Zeichen vor einem Kategorienamen klappt man den Kategoriebaum an dieser Stelle auf. In der folgenden Ansicht beginnt die Teilansicht des Kategoriebaumes nun auf der Ebene der ausgew&auml;hlten Kategorie. In dieser Ebene folgt weiter rechts in der Zeile ein graues oder gr&uuml;nes Buch-Icon und dann eine Zahl, die die Summe aller Bilder in dieser Kategorie und aller Unterkategorien angibt.<BR>
		Klickt man nun auf den Kategorie-Namen, werden in der Filmstreifenansicht alle Bilder der gew&auml;hlten Kategorie und aller Unterkategorien (sofern vorhanden) angezeigt. Die Anzahl entspricht der Zahl hinter dem Buch-Icon.<BR>
		Mit einem Klick auf ein graues Buch-Icon hinter einem Kategorie-Namen k&ouml;nnen zu dieser Kategorie n&uuml;tzliche Informationen in dem sog. Kategorie-Lexikon abgelegt werden.<BR>
		Ist dieses Buch-Icon gr&uuml;n, wurden zu dieser Kategorie bereits zus&auml;tzliche Informationen gespeichert. Ein Klick auf das Symbol erm&ouml;glicht die Bearbeitung des vorhandenen Eintrags, sofern man das entsprechende Recht dazu hat.<BR><BR>
		Hinweis zur Kategorie \"Neuzug&auml;nge\":<BR>
		Hier wird mit der Zahl am Ende der Zeile nur die Anzahl aller Bilder <u>unterhalb</u> der Kategorie \"Neuzug&auml;nge\" angezeigt.<BR>
		Trotzdem kann bei einem Klick auf die Kategorie \"Neuzug&auml;nge\" die Meldung erscheinen \"Jedem Bild wurde mind. eine Kategorie zugewiesen.\".<BR>
		Das bedeutet, die Kategorie \"Neuzug&auml;nge\" selbst ist leer. Alle Bilder sind einer entsprechenden Kategorie zugeordnet worden.<BR>
		Dies sollte der Normalfall sein, da die Kategorie \"Neuzug&auml;nge\" nur ein vorl&auml;ufiger Ablageort f&uuml;r gerade in das System aufgenommene Bilder ist.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '2_meta'></a><b>Suche nach ausgew&auml;hlten Meta-Daten</b><BR><BR>
		Was sind Meta-Daten?<BR>
		Meta-Daten sind zus&auml;tzliche, meist nicht sichtbare Informationen zu einem Bild. Dies k&ouml;nnen z.B. sein:<BR> Kamera-Modell, Aufnahme-Datum, Blende, Belichtungszeit, aber auch Angaben zum Copyright, Stichworte oder Bildbeschreibungen.<BR>
		Um &uuml;ber die Meta-Daten recherchieren zu k&ouml;nnen, m&uuml;ssen Sie zuerst das entsprechende Meta-Daten-Feld in der ersten Zeile des Suchformulars ausw&auml;hlen.<BR>
		Dann bestimmen Sie, welche Bedingung dieser Meta-Wert erf&uuml;llen soll. (2. Auswahlfeld).<BR>
		Wenn dies erfolgt ist, steht Ihnen auch das 3. Auswahlfeld (Kriterium) zur Verf&uuml;gung. In diesem Auswahlfeld werden alle verf&uuml;gbaren Werte des jeweils gew&auml;hlten Meta-Feldes aufgelistet. D.h., der hier angezeigte Werte-Bereich variiert je nach der im Feld \"Meta-Daten-Feld\" getroffenen Auswahl.<BR>
		Wenn alle drei Felder ausgew&auml;hlt wurden, starten Sie die Suche mit einem Klick auf den Button \"Suche starten\".<BR>
		Beachten Sie bitte:<BR>
		Auch hier wird &uuml;ber all diejenigen Bilder recherchiert, welche der evtl. gew&auml;hlten Bewertung entsprechen. Einen entsprechenden Hinweis zur eingestellten Bewertung finden Sie in der pic2base-Kopfzeile.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '2_desc'></a><b>Suche nach Beschreibungstext</b><BR><BR>
		Innerhalb der Beschreibungstexte der einzelnen Bilder k&ouml;nnen Sie nach Vorkommen bestimmter Begriffe suchen.<BR>
		Insgesamt steht eine Suche &uuml;ber 5 Begriffe zur Verfgung, welche mit den Booleschen Operatoren UND und OR verkn&uuml;pft werden k&ouml;nnen.<BR>
		Die Suche bezieht sich immer auf das Vorhandensein des Begriffs an einer beliebigen Stelle im Beschreibungstext. So liefert die Suche nach dem Wort \"Haus\" alle Bilder, in deren Beschreibungen \"Haus\" aber auch \"Hausmeister\" oder \"Bauhaus\" vorkommt.<BR>
		Eine Unterscheidung zwischen Gro&#223;- und Kleinschreibung wird nicht vorgenommen.<BR>
		Bilder, welchen noch kein Beschreibungstext zugewiesen wurden, werden von der Suche ausgeschlossen.<BR>
		Die Verwendung von Platzhaltern ist (noch) nicht m&ouml;glich.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '2_geo'></a><b>Suche nach Geo-Daten</b><BR><BR>
		Bei der Suche nach Geo-Daten haben Sie zwei M&ouml;glichkeiten:<BR>
		- Die Suche anhand konkreter geografischer Koordinaten:<BR>
		Hier tragen Sie in die entsprechenden Felder die Angaben f&uuml;r L&auml;nge, Breite und ggf. H&ouml;he sowie den Umkreis, innerhalb dessen rund um den angegebenen Punkt gesucht werden soll, ein.<BR>
		Als Ergebnis erhalten Sie alle Bilder, die innerhalb des gew&uuml;nschten Umkreises um den angegebenen Punkt aufgenommen wurden.<BR>
		- Suche nach Ortsbezeichnungen:<BR>
		Wenn Sie bei der Geo-Referenzierung Ihrer Bilder diesen die entsprechenden Ortsnamen zugewiesen haben, haben Sie nun die M&ouml;glichkeit, in diesem Formular nach Bildern innerhalb eines bestimmten Umkreises um einen Ort zu suchen.<BR>
		Hierzu wird zun&auml;chst der geometrische Mittelpunkt aller Fotos ermittelt, welche an dem ausgew&auml;hlten Ort aufgenommen wurden. Von diesem Punkt aus werden alle Bilder ermittelt, welche sich innerhalb des angegebenen Umkreises befinden.<BR><BR>
		In beiden Suchm&ouml;glichkeiten kann als maximaler Umkreis eine Entfernung von 50 km angegeben werden.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '2_coll'></a><b>Suche nach Kollektionen</b><BR><BR>
		Wenn Sie nach Kollektionen suchen wollen, k&ouml;nnen sie dies &uuml;ber diesen Men&uuml;punkt erledigen.<BR>
		Im Kopf der Tabelle auf der folgenden Seite k&ouml;nnen Sie nach dem Kollektionsnamen oder der Kollektionsbeschreibung recherchieren, indem Sie in das entsprechende Eigabefeld den Suchbegriff eintippen.<BR>
		Die Liste der verf&uuml;gbaren Kollektionen verk&uuml;rzt sich entsprechend der Treffer auf Ihre Suchanfrage.<BR>
		In der rechten Spalte jeder Kollektions-Zeile befinden sich zwei Icons:<br>
		Mit einem Klick auf das \"Auge\" k&ouml;nnen Sie sich alle Bilder der Kollektion in der sogenannten Leucht-Tisch-Ansicht darstellen lassen.<br>
		Die Berechtigung vorausgesetzt, k&ouml;nnen Sie mit einem Klick auf das Download-Icon alle Bilder der gew&auml;hlten Kollektion in Ihren Download-Ordner kopieren.<br>
		Alle Bild-Dateien werden dabei mit einem Dateinamen versehen, der den Kollektionsnamen und die Position des Bildes innerhalb der Kollektion beinhaltet.<br>
		Damit ist es m&ouml;glich, die einmal vorsortierten Bilder komfortabel in externen Anwendungen weiter zu verarbeiten.
		
		<BR><BR><a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '2_film'></a><b>Was kann der Filmstreifen?</b><BR><BR>
		Im unteren Bereich des Arbeitsfensters befindet sich der sogenannte Filmstreifen. Hier werden die gefundenen Bilder und einige zus&auml;tzliche Informationen dazu dargestellt.<BR>
		(siehe Abb:)<BR><BR>
		<img src='img/filmstreifen1.png' width='400px'><BR><BR>
		&Uuml;ber den Bildern wird die Gesamtzahl und die Anzahl der hiervon georeferenzierten Bilder genannt.<BR>
		Wenn georeferenzierte Bilder dabei sind, wird weiter rechts die M&ouml;glichkeit angeboten, sich die Standorte in GooglEarth anzeigen zu lassen. Dazu wird zun&auml;chst die kml-Datei durch einen Klick auf das graue GoogleEarth-Icon angelegt.
		Danach &auml;ndert das Icon sein Aussehen -es wird farbig dargestellt.  Mit einem Klick auf das nun farbige Icon k&ouml;nnen die georeferenzierten Bilder in GoogleEarth dargestellt werden.<BR>(Fragen Sie Ihren Administrator, falls GoogleEarth nicht auf Ihrem Rechner installiert ist.)<BR><BR>
		Rechts daneben befindet sich ein Icon mit der Beschriftung \"Galerie vorbereiten\". Mit einem Klick darauf wird eine PDF-Datei mit der verkleinerten Darstellung aller gefundenen Bilder erzeugt, die man mit einem beliebigen PDF-Reader ansehen kann.<BR><BR>
		Den gr&ouml;&szlig;ten Teil der Filmstreifens nimmt die Darstellung der Bilder ein. Wenn Sie ein Bild mit dem Mauszeiger &uuml;berfahren, werden im rechten oberen Fensterbereich des Hauptfensters weitere Details zu dem gerade &uuml;berfahrenen Bild gezeigt. Gleichzeitig wird dieses Bild mit einem gr&uuml;nen Balken unterhalb des Download-Icons als \"aktiv\" markiert. Dies erleichtert die Orientierung innerhalb der Filmstreifen-Navigation.<BR>Klicken Sie hingegen in ein Bild im Filmstreifen, gelangen Sie in den \"Bl&auml;tter-Modus\".<BR>
		Hier haben Sie die M&ouml;glichkeit, sich in einer vergr&ouml;&szlig;erten Ansicht rasch durch alle gefundenen Bilder zu bewegen oder einzelne Bilder in Originalqualit&auml;t zu betrachten.<BR>
		Im Bl&auml;tter-Modus k&ouml;nnen Sie sowohl mit der Maus, als auch mit der Tastatur durch die gefundenen Bilder navigieren.<br />
		Wenn Sie die Tastatur benutzen, stehen Ihnen die folgenden Tasten zur Verf&uuml;gung:<br />
		<ul>
		<li>Pfeil rechts - ein Bild vor</li>
		<li>Pfeil runter - zehn Bilder zur&uuml;ck</li>
		<li>Pfeil links - ein Bild zur&uuml;ck</li>
		<li>Pfeil hoch - zehn Bilder vor</li>
		<li>F-Taste - Wechsel in die Vollbild-Ansicht</li>
		<li>F10 - Vollbild-Ansicht beenden</li>
		</ul>
		
		Wenn Sie den Bl&auml;tter-Modus durch Klick auf das Schlie&szlig;en-Icon verlassen, befinden Sie sich in der Filmstreifen-Ansicht an der Stelle des zuletzt betrachteten Bildes. Dies ist mit dem gr&uuml;nen Balken als \"aktiv\" markiert.<BR><BR>
		Unter jedem Bild befindet sich ein Download-Icon. Die entsprechende Berechtigung vorausgesetzt, k&ouml;nnen Sie hier&uuml;ber die gew&uuml;nschten Bilder herunterladen. Je nach Ihren pers&ouml;nlichen Einstellungen, wird das gew&uuml;nschte Bild in Ihren Download-Ordner kopiert oder Sie bekommen einen Datei-Auswahl-Dialog angezeigt.<BR><BR>
		Wenn das Suchergebnis mehr als 18 Bilder lieferte, sehen Sie in der Zeile unter den Download-Icons eine Reihe orangfarbener Rechtecke. Sie repr&auml;sentieren die Anzahl der Filmstreifen-Elemente und k&ouml;nnen durch einen Klick darauf direkt aufgerufen werden.<BR>
		So k&ouml;nnen Sie bei einer gro&szlig;en Trefferzahl schnell in alle Richtungen navigieren.
		<BR><BR><a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '2_edit'></a><b>Bearbeitungsm&ouml;glichkeiten der Suchergebnisse</b><BR><BR>
		Wenn der angemeldete Benutzer berechtigt ist, Eigenschaften der gefundenen Bilder zu bearbeiten, stehen ihm die folgenden M&ouml;glichkeiten zur Verf&uuml;gung:<BR></p>
		<ul style='margin:20px 15px; text-align:justify;'>
		<li>&Uuml;bertragung der Bild-Eigent&uuml;merschaft auf einen anderen User: <img src='../../share/images/change_owner.gif' height='12px' />
		<li>l&ouml;schen des Bildes: <img src='../../share/images/trash.gif' height='12px' />
		<li>Korrektur der Vorschau-Ansicht bei RAW-Bildern: <img src='../../share/images/reload.png' height='12px' />
		<li>manuelle Georeferenzierung bzw. Korrektur der bereits erfolgten automatischen Geo-Referenzierung: <img src='../../share/images/del_geo_ref.gif' height='12px' />
		<li>manuelle Bearbeitung freigegebener Meta-Daten: <img src='../../share/images/info.gif' height='12px' />
		</ul>

		Bei entsprechender Berechtigung k&ouml;nnen die o.g. Funktionen &uuml;ber die jeweiligen Icons aufgerufen werden. (siehe Abb.)<BR><BR>
		<img src='img/recherche1.png'></img><BR><BR>
		Weiterhin besteht bei entsprechender Berechtigung die M&ouml;glichkeit, die Bildbeschreibung zu &auml;ndern. Hierzu Nehmen Sie die &Auml;nderung direkt in dem angezeigten Textfeld vor und speichern diese dann durch einen Klick auf den Button \"&Auml;nderungen speichern\".<BR><BR>
		Zus&auml;tzlich haben Sie die M&ouml;glichkeit, ein sogenanntes Kategorie-Lexikon anzulegen. Hiermit besteht die Option, zu jeder Kategorie zus&auml;tzliche Informationen in der Datenbank zu hinterlegen. So k&ouml;nnte man z.B. der Kategorie \"Blankenburg (Harz)\" Informationen zur geografischen Lage, der Einwohnerzahl, der Erreichbarkeit mit Bus / Bahn / Auto usw. zuweisen.<BR>
		Die Zuweisung erfolgt durch Klick auf das Buch-Icon hinter dem Kategorienamen.<BR><BR>
		Analog kann ein Fototagebuch angelegt werden, indem man sich in der Ansicht \"Suche nach Aufnahmedatum\" den betreffenden Tag heraussucht, das Buch-Icon anklickt und die gew&uuml;nschte Information hinterlegt.<BR><BR>
		Der Inhalt des Kategorielexikons und des Fototagebuchs werden bei den betreffenden Bildern jeweils mit in der Komplett-Ansicht der Detailinformationen ausgegeben. (siehe Abb.)<BR><BR>
		<img src='img/recherche2.png' width='400px'></img><BR><BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		</fieldset>";
		break;
		
		CASE '3':
		echo "<a name='top'></a>
		<fieldset id='fieldset_help3'>
		<legend style='color:blue; font-weight:bold;'>&Uuml;bersicht &uuml;ber die Bearbeitungsm&ouml;glichkeiten</legend>
		
		<br /><b><u>Inhalt:</u></b><br /><br />
		<a href='#3_georef'>Geo-Referenzierung</a><BR>
		<a href='#3_bewertung'>Bild-Bewertung</a><BR>
		<a href='#3_desc'>Beschreibungen zuweisen</a><BR>
		<a href='#3_kat'>Kategorie-Zuweisung</a><BR>
		<a href='#3_del_kat'>Kategorie-Zuweisungen aufheben</a><BR>
		<a href='#3_edit_coll'>Kollektion anlegen / bearbeiten / arrangieren / löschen</a><br>
		<a href='#3_dubletten'>Dubletten-Pr&uuml;fung</a><BR>
		<a href='#3_qp'>Quick-Preview hochformatiger Bilder erstellen</a><BR><BR>
		
		<a name = '3_georef'><b>Geo-Referenzierung</b></a><BR><BR>
		Wenn Sie im Besitz von Geo-Daten sind, k&ouml;nnen Sie &uuml;ber diesen Men&uuml;punkt Ihren Bildern die geografischen Koordinaten des Aufnahme-Standortes zuweisen.<BR>
		Die derzeit unterst&uuml;tzten Datenlogger sind in dem entsprechenden Auswahlfeld schwarz dargestellt, vorbereitete, aber noch nicht getestete Modelle sind inaktiv dargestellt.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '3_bewertung'><b>Bilder bewerten</b></a><BR><BR>
		pic2base bietet die M&ouml;glichkeit, jedes Bild hinsichtlich bestimmter, pers&ouml;nlicher Ma&#223;st&auml;be zu bewerten und hierf&uuml;r \"Noten\" von 1 - 5 zu vergeben, wobei die 1 ein besonders gelungenes Bild kennzeichnet und die Note 5 Bilder der untersten Qualit&auml;tsstufe repr&auml;sentiert.<BR>
		Bei der Aufnahme der Bilder in der Datenbank wird allen Bildern zun&auml;chst keine Note vergeben. Besser einzustufende Bilder m&uuml;ssen also im Nachhinein h&ouml;her bewertet werden.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '3_desc'><b>Beschreibungen zuweisen</b></a><BR><BR>
		Jedem Bild kann in pic2base ein ausf&uuml;hrlicher Beschreibungstext zugef&uuml;gt werden.<BR>
		&Uuml;ber diesen Men&uuml;punkt haben Sie die M&ouml;glichkeit, mehreren Bildern in einem Durchgang den gleichen Beschreibungstext zuzuweisen,
		aber auch einzelne Bilder detailliert zu beschreiben.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '3_kat'><b>Kategorie-Zuweisung - Auswahl nach Kategorien</b></a><BR><BR>
		&Uuml;ber diesen Men&uuml;punkt k&ouml;nnen Sie den Bildern Kategorien zuweisen.<BR>
		Die Auswahl der zu bearbeitenden Bilder erfolgt dabei &uuml;ber die Suche nach Kategorien.<BR><BR>
		<b>Kategorie-Zuweisung - Auswahl nach Aufnahmedatum</b></a><BR><BR>
		Auch &uuml;ber diesen Men&uuml;punkt k&ouml;nnen Sie den Bildern Kategorien zuweisen.<BR>
		Die Auswahl der zu bearbeitenden Bilder erfolgt hier aber &uuml;ber die Suche nach dem Aufnahmedatum.<BR><BR>
		Die zu vergebenden Kategorien m&uuml;ssen in jedem Fall im Vorfeld durch den Administrator oder einem Benutzer mit entsprechenden Rechten angelegt worden seien.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '3_del_kat'><b>Kategorie-Zuweisung aufheben</b></a><BR><BR>
		Sollte versehentlich einem Bild eine falsche Kategorie zugewiesen worden sein, kann &uuml;ber diesen Men&uuml;punkt diese Zuweisung wieder r&uuml;ckg&auml;ngig gemacht werden.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		
		<a name = '3_edit_coll'><b>Kollektion anlegen / bearbeiten / arrangieren / löschen</b></a><br><br>
		Mit der Anlage einer Kollektion haben Sie die komfortable M&ouml;glichkeit, Ihre Suchergebnisse dauerhaft zu speichern.<br>
		Dazu legen Sie zun&auml;chst &uuml;ber diesen Men&uuml;punkt eine neue Kollektion an, indem Sie in dem Anlage-Formular einen Kollektions-Namen und eine Beschreibung hinterlegen.<br>
		Der Kollektions-Name sollte kurz aber verst&auml;ndlich sein, w&auml;hrend Sie in der Beschreibung ausf&uuml;hrliche Angaben zum Inhalt der Kollektion hinterlegen k&ouml;nnen.<br>
		Wenn Sie nun zur Kollektions-Auswahlseite im Bearbeiten-Men&uuml; zur&uuml;ckkehren, finden Sie in der Liste der verf&uuml;gbaren Kollektionen Ihre soeben angelegte Kollektion wieder.<BR>
		In der rechten Spalte jeder aufgef&uuml;hrten Kollektion finden Sie vier Symbole, die von links beginnend die folgenden Funktionen erm&ouml;glichen:<br>
		<ul>
		<li>Kollektion bearbeiten</li>
		<li>Kollektion arrangieren</li>
		<li>Kollektion l&ouml;schen</li>
		<li>Information zum Freigabe-Status der Kollektion</li>
		</ul>
		Weitere Informationen zur Bearbeitung der Kollektionen erhalten Sie auf der entsprechenden Unterseite.
		<br><a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '3_dubletten'><b>Dubletten-Pr&uuml;fung</b></a><BR><BR>
		pic2base bietet eine einfache M&ouml;glichkeit zu ermitteln, ob Dubletten in den Datenbestand aufgenommen wurden.<BR>
		Dazu wird bereits w&auml;hrend des Datei-Uploads kontrolliert, ob sich bereits ein identisches Bild im Datenbestand befindet.
		Wenn dieser Fall zutrifft, werden am Ende des Uploadprozesses alle Dubletten angezeigt und der Benutzer kann entscheiden, ob das doppelte Bild dennoch in die Datenbank aufgenommen werden soll, oder nicht.<br />
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '3_qp'><b>Quick-Preview hochformatiger Bilder erzeugen</b></a><BR><BR>
		Zur schnelleren Darstellung hochformatiger Bilder m&uuml;ssen diese einmalig vor der ersten Betrachtung in pic2base gedreht und als Kopie angelegt werden. Dies geschieht normalerweise beim ersten &Uuml;berfahren des entsprechenden Bildes mit dem Mauszeiger in der Filmstreifen-Ansicht, kostet aber entsprechend Rechenzeit auf dem Server. Dieser Vorgang kann aber auch f&uuml;r alle Bilder, f&uuml;r welche es noch kein entsprechend gedrehtes Vorschau-Bild gibt, auf einmal durchgef&uuml;hrt werden.<BR>
		Hinweis: Dieser Vorgang kann - je nach Anzahl der zu drehenden Bilder - erheblichen Rechenaufwand erfordern!<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		Weiterf&uuml;hrende Informationen erhalten Sie auf den entsprechenden Bearbeitungs-Seiten.<BR>
		</fieldset>";
		break;
		
		CASE '4':
		echo "<a name='top'></a>
		<fieldset id='fieldset_help3'>
		<legend style='color:blue; font-weight:bold;'>Hilfe zu den Bearbeitungsm&ouml;glichkeiten</legend>
		
		<br /><b><u>Inhalt:</u></b><br /><br />
		<a href='#4_georef'>Geo-Referenzierung</a><BR>
		<a href='#4_bewertung'>Bild-Bewertung</a><BR>
		<a href='#4_desc'>Beschreibungen zuweisen</a><BR>
		<a href='#4_kat'>Kategorien zuweisen</a><BR>
		<a href='#4_del_kat'>Kategorie-Zuweisungen aufheben</a><BR>
		<a href='#4_edit_coll'>Kollektion anlegen / bearbeiten / arrangieren / löschen</a><br>
		<a href='#4_dubletten'>Dubletten-Pr&uuml;fung</a><BR>
		<a href='#4_qp'>Quick-Preview hochformatiger Bilder erstellen</a><BR><BR>
		
		<a name = '4_georef'><b>Geo-Referenzierung</b></a><BR><BR>
		Welchen Vorteil bietet die Georeferenzierung?<BR>
		Bei der Geo-Referenzierung wird zu jedem Bild der Kamerastandort zum jeweiligen Aufnahmezeitpunkt vermerkt. Sp&auml;ter kann man dann nach Bildern suchen,
		die an einem bestimmten Standort entstanden sind, oder innerhalb eines bestimmten Umkreises um diesen Standort herum aufgenommen worden.<BR>
		Die Geo-Referenzierung anhand vorhandener Track-Aufzeichnungen geschieht folgenderma&szlig;en:<BR>
		W&auml;hlen Sie zuerst Ihren Daten-Logger und die gew&uuml;nschte Track-Datei aus. Nun k&ouml;nnen Sie entscheiden, ob Sie sich den gew&auml;hlten Track vorab in GoogleEarth anzeigen lassen (Klick auf \"Track ansehen\") oder gleich mit der Referenzierung beginnen (Klick auf \"Los!\") <BR>
		Im Ergebnis der Referenzierung werden Ihnen in der rechten Spalte alle Bilder aufgelistet, bei denen es eine &Uuml;bereinstimmung der Daten gab.<BR>
		Sollte es zu keinen 'Treffern' zwischen Bild- und Track-Dateien kommen, pr&uuml;fen Sie bitte zuerst, ob die Zeitstempel der Bild-Dateien mit denen der Track-Daten ann&auml;hernd &uuml;bereinstimmen.<BR>
		Viele Konvertierungsprogramme &auml;ndern die Zeitangaben bei der Speicherung der Track-Dateien von lokaler Zeit in UTC.<BR><BR>
		<font color = 'red'>pic2base geht davon aus, da&szlig; die Zeitangaben in der Track-Datei als UTC-Angabe vorliegen!<BR>
		Gleichzeitig geht pic2base davon aus, da&szlig; die interne Uhr der Kamera auf die aktuelle Ortszeit eingestellt war. Das bedeutet, wenn Aufnahmen in einer anderen Zeitzone als CET aufgenommen wurden, muss <b>VOR</b> der Referenzierung diese Zeitzone manuell eingestellt werden. (Drop-Down-Feld \"Zeitzone\")</font><BR><BR>
		Vor einer automatischen Geo-Referenzierung ist also immer die Frage zu beantworten: In welchem Verh&auml;ltnis stand die lokale Zeit am Aufnahmeort zur GMT?<BR><BR>
		Am Ende der rechten Spalte befindet sich der Button \"Weiter\" &uuml;ber welchen man zum folgenden Formular gelangt, in welchem in der linken Spalte das betreffende Bild und ein Eingabefeld zur Erfassung der Ortsbezeichnung des Aufnahmestandortes zu sehen ist und in der rechten Spalte - eine Internetverbindung vorausgesetzt - der Aufnahmestandort in der Karte dargestellt wird.<BR>
		M&ouml;glicherweise wird in dem Eingabefeld f&uuml;r die Ortsbezeichnung bereits ein Ort vorgeblendet. Dies liegt daran, da&#223; pic2base anhand der bereits in der Datenbank befindlichen Geo-Koordinaten versucht herauszufinden, welche Ortsbezeichnung zu den Aufnahmekoordinaten geh&ouml;rt.<BR>
		Wurde noch kein Ort gefunden, mu&#223; dieser manuell erfasst werden.<BR>
		Mit wachsendem Umfang der in der Datenbank gespeicherten Geo-Informationen \"wei&#223;\" pic2base im Laufe der Zeit immer besser von selbst, an welchem Ort die Aufnahme entstand und blendet dementsprechend die korrekte Ortsbezeichnung automatisch ein.<BR>
		Hinweis:<BR>
		Im Suche-Bereich gibt es die M&ouml;glichkeit der manuellen Geo-Referenzierung eines jeden einzelnen Bildes.<BR>
		Hier k&ouml;nnen die automatisch zugewiesenen Referenzierungen manuell korrigiert werden, oder Bildern, zu denen keine Trackdaten vorlagen, kann eine manuelle Referenzierung zugewiesen werden.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '4_bewertung'><b>Bild-Bewertung</b></a><BR><BR>
		Um die gezielte Suche nach Bildern mit bestimmten Qualit&auml;tsanforderungen zu erm&ouml;glichen, kann jedes Bild qualitativ bewertet werden. Die Bewertung reicht von \"1 Stern\" (ungen&uuml;gend) bis \"5 Sterne\" (sehr gut).<BR>
		Die eigentliche Vergabe der Sterne erfolgt durch anklicken des betreffenden Sterns unter dem jeweiligen Bild in der Vorschauansicht, wobei der linke Stern f&uuml;r \"1 Stern\" steht und der reche f&uuml;r \"5 Sterne\".<BR>
		Wenn ein Bild mit dem Mauszeiger &uuml;berfahren wird, wird dessen vergr&ouml;&szlig;erte Ansicht in der rechten Fensterh&auml;lfte dargestellt. Wird das Bild in der Vorschauansicht hingegen angeklickt, erh&auml;lt man eine nochmals vergr&ouml;&szlig;erte Ansicht.<BR>
		Bei der Erfassung erhalten die Bilder noch keinen Stern - also keine Qualit&auml;tsbewertung. Dennoch kann auch nach diesen Bildern gesucht werden, indem man in der Qualit&auml;tsauswahl den Punkt \"alle Bilder\" anklickt.<BR><BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '4_desc'><b>Beschreibungen zuweisen</b></a><BR><BR>
		pic2base bietet die M&ouml;glichkeit, einzelnen Bildern, als auch Gruppen von Bildern, Beschreibungstexte zuzuweisen.<BR>
		Dies erfolgt &uuml;ber den Punkt \"Beschreibungen zuweisen\" in dem Bearbeiten-Bereich.<BR>
		Zun&auml;chst sind die gew&uuml;nschten Bilder aus der entsprechenden Kategorie auszuw&auml;hlen. Es ist also die Kategorie zu w&auml;hlen und dann &uuml;ber die rechts daneben befindlichen
		Symbole sind die Bilder anzuzeigen. Hierbei besteht die M&ouml;glichkeit, alle oder keine Bilder vorzuselektieren.<BR>
		Wenn man also wei&szlig;, da&szlig; in der betrachteten Kategorie der &uuml;berwiegende Teil der Bilder eine Beschreibung erhalten sollen, kann man die Bilder &uuml;ber das Symbol \"alle Bilder dieser Kategorie ausw&auml;hlen\" anzeigen lassen.
		Bei dieser Auswahlmethode sind alle gefundenen Bilder standardm&auml;&szlig;ig vorselektiert. Die wenigen nicht gew&uuml;nschten Bilder k&ouml;nnen nun abgew&auml;hlt werden und der Beschreibungstext kann in der rechten Fensterh&auml;lfte eingetragen werden.<BR>
		Analog kann verfahren werden, wenn nur wenige Bilder der gew&auml;hlten Kategorie beschrieben werden sollen. Dann l&auml;&szlig;t man sich die Bilder mittels des Symbols \"einzelne Bilder dieser Kategorie ausw&auml;hlen\" anzeigen und verf&auml;hrt weiter wie oben beschrieben.<BR>
		Die Beschreibung wird den selektierten Bildern zugewiesen, indem der Button \"Speichern\" angeklickt wird. Alternativ kann der Vorgang &uuml;ber den Button \"Abbrechen\" ohne &Auml;nderungen beendet werden.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '4_kat'><b>Kategorien zuweisen</b></a><BR><BR>
		pic2base bietet die M&ouml;glichkeit, einzelne Bildern, als auch Gruppen von Bildern in Kategorien einzuordnen.<BR>
		Dies erfolgt &uuml;ber den Punkt \"Kategorien zuweisen\" in dem Bearbeiten-Bereich.<BR>
		Zun&auml;chst sind die gew&uuml;nschten Bilder aus der entsprechenden Kategorie auszuw&auml;hlen. Es ist also die Kategorie zu w&auml;hlen und &uuml;ber die rechts daneben befindlichen
		Symbole sind die Bilder per Klick anzuzeigen. Hierbei besteht die M&ouml;glichkeit, alle oder keine Bilder vorzuselektieren.<BR>
		Wenn man also wei&szlig;, da&szlig; aus der betrachteten Kategorie der &uuml;berwiegende Teil der Bilder einer weiteren Kategorie zugeordnet werden sollen, kann man die Bilder &uuml;ber das Symbol \"alle Bilder dieser Kategorie ausw&auml;hlen\" anzeigen lassen.
		Bei dieser Auswahlmethode sind alle gefundenen Bilder standardm&auml;&szlig;ig vorselektiert. Die wenigen nicht gew&uuml;nschten Bilder k&ouml;nnen nun abgew&auml;hlt werden und die neu hinzuzuf&uuml;gende Kategorie kann in der rechten Fensterh&auml;lfte ausgew&auml;hlt werden.<BR>
		Es ist auch m&ouml;glich, den gew&auml;hlten Bildern mehrere neue Kategorien in einem Vorgang zuzuweisen. Dazu w&auml;hlt man einfach in der Kategorieliste in der rechten Fensterh&auml;lfte alle gew&uuml;nschten Eintr&auml;ge aus.<BR>
		Hinweis:<BR>
		In der Kategorieliste stehen nur die Kategorien zur Verf&uuml;gung, die zuvor von einem berechtigten Benutzer angelegt wurden. Diese Einschr&auml;nkung soll verhindern, da&szlig; in dem Kategoriebaum \"Wildwuchs\" entsteht, denn eine wohldurchdachte Strukturierung
		der Kategorie-Hierachie erleichtert sp&auml;ter die Suche erheblich.<BR><BR>
		Analog zur Vorselektion aller Bilder kann verfahren werden, wenn nur wenige Bilder der gew&auml;hlten Kategorie bearbeitet werden sollen. Dann l&auml;&szlig;t man sich die Bilder mittels des Symbols \"einzelne Bilder dieser Kategorie ausw&auml;hlen\" anzeigen und verf&auml;hrt weiter wie oben beschrieben.<BR>
		Die neuen Kategorien werden den selektierten Bildern zugewiesen, indem der Button \"Speichern\" angeklickt wird. Alternativ kann der Vorgang &uuml;ber den Button \"Abbrechen\" ohne &Auml;nderungen beendet werden.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '4_del_kat'><b>Kategorie-Zuweisung aufheben</b></a><BR><BR>
		Sollte einmal einem oder mehreren Bildern versehentlich eine falsche Kategorie zugewiesen worden sein, hat man hier die M&ouml;glichkeit diese Zuweisung wieder r&uuml;ckg&auml;ngig zu machen.<BR>
		Dazu navigiert man zun&auml;chst im Kategoriebaum bis zu der Kategorie, in welcher ich das falsch eingeordnete Bild befindet. Nun hat man die M&ouml;glichkeit sich alle Bilder dieser Kategorie anzeigen zu lassen, wobei man w&auml;hlen kann, ob keines oder alle Bilder vorselektiert sein sollen. (Klick auf das leere oder angekreuzte Auswahl-Symbol)<BR>
		In der unteren Filmstreifenansicht kann man nun das oder die gew&uuml;nschten Bilder durch Aktivierung der Checkbox ausw&auml;hlen.<BR>
		Die Aufhebung der Kategorie-Zuweisung erfolgt mit einem Klick auf den Button \"Speichern\".<BR>
		Gleichzeitig werden auch alle Zuordnung zu den Unterkategorien der gew&auml;hlten Kategorie aufgehoben.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '4_edit_coll'><b>Kollektion anlegen / bearbeiten / arrangieren / löschen</b></a><br>
		Bevor Sie mit den Kollektionen arbeiten k&ouml;nnen, m&uuml;ssen sie mindestens eine neue angelegt haben.<br>
		Klicken Sie hierzu auf den Button \"Neue Kollektion anlegen\" und f&uuml;llen Sie in dem folgenden Formular die Felder \"Name der Kollektion\" und \"Beschreibung\" aus.<BR>
		Der Kollektions-Name sollte kurz aber verst&auml;ndlich sein, w&auml;hrend Sie in der Beschreibung ausf&uuml;hrliche Angaben zum Inhalt der Kollektion hinterlegen k&ouml;nnen.<br>
		Speichern Sie Ihre Eingaben ab.<br>
		Wenn Sie nun zur Kollektions-Auswahlseite im Bearbeiten-Men&uuml; zur&uuml;ckkehren, finden Sie in der Liste der verf&uuml;gbaren Kollektionen Ihre soeben angelegte Kollektion wieder.<BR>
		In der rechten Spalte jeder aufgef&uuml;hrten Kollektion finden Sie vier Symbole, die von links beginnend die folgenden Funktionen erm&ouml;glichen:<br>
		<ul>
		<li>Kollektion bearbeiten<br>
		&Uuml;ber diesen Button haben Sie die M&ouml;glichkeit, den Namen der Kollektion oder deren Beschreibung zu &auml;ndern, weitere Bilder hinzuzuf&uuml;gen oder Bilder aus bestehenden Kollektionen zu entfernen.</li>
		Weitere Bilder f&uuml;gen Sie einer bestehenden Kollektion zu, indem Sie den Button \"Weitere Bilder hinzuf&uuml;gen / Bilder entfernen\" anklicken und in dem sich dann &ouml;ffnenden Suchbereich die gew&uuml;nschten Bilder ausw&auml;hlen, wie Sie es vom normalen Download her gewohnt sind.<br>
		Bereits in der Kollektion befindliche Bilder sind im Filmstreifen mit dem gr&uuml;nen H&auml;ckchen gekennzeichnet und k&ouml;nnen durch erneutes Anklicken wieder aus der Kollektion entfernt werden.<br>
		Eine andere M&ouml;glichkeit, ein Bild aus der Kollektion zu entfernen besteht darin, im Fenster \"Ausgew&auml;hlte Kollektion bearbeiten\" auf das betreffende Bild in der Bilder-Leiste zu klicken. Nach entsprechender Beantwortung der Sicherheits-Abfrage wird das Bild aus der Kollektion gel&ouml;scht.
		<li>Kollektion arrangieren<br>
		Diese Button bietet die M&ouml;glichkeit, die Reihenfolge der Bilder innerhalb der Kollektion neu festzulegen. (Noch nicht verf&uuml;gbar!)</li>
		<li>Kollektion l&ouml;schen<br>
		Wenn Sie &uuml;ber die entsprechende Berechtigung verf&uuml;gen, k&ouml;nnen Sie an dieser Stelle Kollektionen l&ouml;schen.<br>
		Dabei werden lediglich die Kollektionen, nicht aber die darin enthaltenen Bilder gel&ouml;scht!</li>
		<li>Information zum Freigabe-Status der Kollektion<br>
		Wenn Sie eine Kollektion neu anlegen, k&ouml;nnen zun&auml;chst nur Sie diese bearbeiten. Ansehen k&ouml;nnen sie auch andere Benutzer, sofern sie die Berechtigung dazu haben.<br>
		Wollen Sie jedoch, da&szlig; auch andere Benutzer Ihre Kollektion bearbeiten k&ouml;nnen, m&uuml;ssen sie die entsprechende Freigabe &uuml;ber den Men&uuml;punkt \"Kollektion bearbeiten\"<br>
		und hier durch setzen des H&auml;ckchens \"Freigabe\" erteilen.<br>
		Wurde die Freigabe erteilt, zeigt dies der blaue Pfeil an. Anderenfalls weist der rote Kreis auf eine nicht erteilte Freigabe hin.</li>
		</ul>
		<b>Beachten Sie bitte:</b> Bei keiner der genannten Bearbeitungsm&ouml;glichkeiten f&uuml;r Kollektionen wird der eigentliche Bild-Datenbestand ver&auml;ndert!
		<br><br><a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '4_dubletten'><b>Dubletten-Pr&uuml;fung</b></a><BR><BR>
		pic2base bietet eine einfache M&ouml;glichkeit zu ermitteln, ob Dubletten in den Datenbestand aufgenommen wurden.<BR>
		Dazu wird bereits w&auml;hrend des Datei-Uploads kontrolliert, ob sich bereits eine Bilddatei mit dem gleichen Original-Dateinamen oder der gleichen Pr&uuml;fsumme im Datenbestand befindet.
		Wenn dieser Fall zutrifft, werden am Ende des Uploadprozesses alle Dubletten angezeigt und der Benutzer kann entscheiden, ob das doppelte Bild dennoch in die Datenbank aufgenommen werden soll, oder nicht.<BR>
		Eine Besonderheit ist zu beachten:<BR>
		Wenn ein Bild bereits in der Datenbank enthalten war und dort mit Kategorien, Beschreibungen oder Geo-Referenzierungen versehen war, und dieses Bild per Download aus der Datenbank entnommen wurde,
		wird es nach erneutem Upload nicht als Dublette erkannt.<BR>
		Das liegt daran, da&szlig; die Pr&uuml;fsumme des Bildes unmittelbar w&auml;hrend des Upload-Prozesses ermittelt wird. Sie repr&auml;sentiert also den Originalzustand des Bildes. Werden nun Kategorien oder Beschreibungen hinzugef&uuml;gt, &auml;ndert sich an den Bildinformationen zwar nichts, aber an der Bild-Datei, da diese zus&auml;tzlichen Informationen mit in das Bild geschrieben werden.<BR>
		Somit hat das aus der Datenbank entnommene Bild zwangsl&auml;ufig eine andere Pr&uuml;fsumme als das ehemalige Original-Bild.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '4_qp'><b>Quick-Preview hochformatiger Bilder erstellen</b></a><BR><BR>
		Zur schnelleren Darstellung hochformatiger Bilder m&uuml;ssen diese einmalig vor der ersten Betrachtung in pic2base gedreht und als Kopie angelegt werden. Dies geschieht normalerweise beim ersten &Uuml;berfahren des entsprechenden Bildes mit dem Mauszeiger in der Filmstreifen-Ansicht, kostet aber entsprechend Rechenzeit auf dem Server. Dieser Vorgang kann aber auch f&uuml;r alle Bilder, f&uuml;r welche es noch kein entsprechend gedrehtes Vorschau-Bild gibt, auf einmal durchgef&uuml;hrt werden.<BR>
		Hinweis: Dieser Vorgang kann - je nach Anzahl der zu drehenden Bilder - erheblichen Rechenaufwand erfordern und sollte deshalb in einer lastarmen Zeit erfolgen!<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		</fieldset>";
		break;
		
		CASE '5':
		echo "<a name='top'></a>
		<fieldset id='fieldset_help3'>
		<legend style='color:blue; font-weight:bold;'>Hilfe zum Administrations-Bereich</legend>
		
		<br /><b><u>Inhalt:</u></b><br /><br />
		<a href='#5_kat'>Kategotien</a><BR>
		<a href='#5_user'>Benutzer</a><BR>
		<a href='#5_groups'>Gruppen</a><BR>
		<a href='#5_rights'>Berechtigungen</a><BR>
		<a href='#5_ortsnamen'>Ortsnamen</a><BR>
		<a href='#5_ftp'>FTP-Statistik</a><BR>
		<a href='#5_log'>P2b-Log</a><BR>
		<a href='#5_db_wartung'>Datenbank-Wartung</a><BR>
		<a href='#5_db_export'>Datenbank-Export</a><BR>
		<a href='#5_loeschen'>Bilder endg&uuml;ltig l&ouml;schen</a><BR>
		<a href='#5_mp'>Meta-Protect</a><BR>
		<a href='#5_mv'>Meta-View</a><BR>
		<a href='#5_sw'>Software-Check</a><BR><BR>
		
		<a name = '5_kat'><b>Kategorien</b></a><BR><BR>

		Im Arbeitsbereich Kategorien kann der Kategoriebaum gepflegt werden:
		Es k&ouml;nnen Kategorien<br />
		<ul style='margin:20px 5px; text-align:justify; width:350px;'>
		<li>erzeugt oder gel&ouml;scht werden,
		<li>umbenannt werden,
		<li>umsortiert werden.
		</ul>
		Beim Aufruf des Kategorie-Arbeitsbereichs gelangt man zun&auml;chst in die Kategorie-Verwaltungsansicht. Von hier k&ouml;nnen die Bereiche Sortierung und Wartung aufgerufen werden.
		Weiterf&uuml;hrende Informationen stehen direkt auf den jeweiligen Seiten zur Verf&uuml;gung.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='5_user'><b>Benutzer</b></a><BR><BR>
		
		Im Arbeitsbereich Benutzer kann der Benutzerkreis f&uuml;r diese pic2base-Datenbank gepflegt werden.
		Es k&ouml;nnen Benutzer
		hinzugef&uuml;gt werden,
		gel&ouml;scht werden oder
		die Gruppenzugeh&ouml;rigkeit der Benutzer ver&auml;ndert werden.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='5_groups'><b>Gruppen</b></a><BR><BR>
		
		Im Arbeitsbereich Gruppen k&ouml;nnen Benutzergruppen und deren Zugriffsrechte definiert werden.
		Nach der Installation sind einige Gruppen bereits angelegt, wobei aber zun&auml;chst lediglich der Gruppe Admin alle Rechte zugeteilt wurden. Die Rechte der anderen Gruppen k&ouml;nnen entsprechend der Erfordernisse im jeweiligen Anwendungsfall individuell festgelegt werden.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='5_rights'><b>Berechtigungen</b></a><BR><BR>
		
		Im Arbeitsbereich Berechtigungen kann man sich informieren, welche Aktionen in pic2base durch Zugriffsbeschr&auml;nkungen reglementiert werden k&ouml;nnen.
		Die eigentliche Rechtevergabe erfolgt &uuml;ber die Erteilung der Gruppen- und Benutzerrechte.<BR>
		Im ersten Schritt werden Gruppen angelegt und diesen werden die gew&uuml;nschten Rechte zugeteilt.<BR>
		Wenn ein neuer Benutzer angelegt und einer bestehenden Gruppe zugeteilt wird, erbt er automatisch die Rechte dieser Gruppe.<BR>
		Diese geerbten Rechte k&ouml;nnen jedoch f&uuml;r jeden einzelnen Benutzer noch individuell angepa&szlig;t werden.<BR>
		Dies geschieht dann &uuml;ber die Vergabe der entsprechenden Benutzerrechte.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='5_ortsnamen'><b>Ortsnamen</b></a><BR><BR>
		
		&Uuml;ber diesen Link kann man Ortsnamen, die bei der Geo-Referenzierung zugewiesen wurden, nachtr&auml;glich bearbeiten. Dies kann z.B. hilfreich sein, wenn sich Ortsnamen infolge von Gebietsreformen ver&auml;ndert haben.<BR>
		Hierbei ist zu beachten, da&szlig; nicht nur die Eintr&auml;ge in der Datenbank korrigiert werden, sondern auch die Meta-Daten in den Bildern. Deshalb kann eine &Auml;nderung der Ortsnamen unter Umst&auml;nden lange Zeit in Anspruch nehmen, wenn viele Bilder davon betroffen sind.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='5_ftp'><b>FTP-Statistik</b></a><BR><BR>
		
		&Uuml;ber den Link FTP-Statistik gelangt man zu einem Tool, mit dessen Hilfe der Zugriff via FTP auf diesen pic2base-Server kontrolliert werden kann. Hier kann in die Protokolldatei eingesehen werden und der verursachte Traffic ausgelesen werden.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='5_log'><b>P2b-Log</b></a><BR><BR>
		
		In der p2b-Logdatei werden Aktivit&auml;ten protokolliert, die dem Administrator Auskunft &uuml;ber die Zugriffe auf die pic2base-Datenbank geben.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<!--<a name='5_md5'><b>md5-Check</b></a><BR><BR>
		
		Mit Hilfe des md5-Checks kann kontrolliert werden, ob zu jeder Bilddatei eine Pr&uuml;fsumme erzeugt wurde. Diese kann sp&auml;ter zur Identifikation von Dubletten herangezogen werden.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='5_hist'><b>Histogramme</b></a><BR><BR>
		
		Der Arbeitsbereich Histogramme bietet die M&ouml;glichkeit zu pr&uuml;fen, ob zu jedem in der Datenbank befindlichen Bild die entsprechenden Histogramme (R,G,B,Grey) existieren. Wenn nicht, werden diese &uuml;ber den Aufruf des Men&uuml;punktes Histogramme erzeugt. Normalerweise ist diese Kontrolle nicht erforderlich, da die Histogramme von pic2base (ab Version 0.40) bereits bei der Bild-Erfassung automatisch erzeugt werden bzw. beim Aufruf der Bilddetail-Infoseite diese Kontrolle nochmals erfolgt. Existieren die Histogramme f&uuml;r das betrachtete Bild nicht (z.B. wenn Bilder mit einer &auml;lteren Version von pic2base erfa&#223;t wurden), w&uuml;rden sie nun nachtr&auml;glich erzeugt werden. Dies erfordert jedoch zus&auml;tzlichen Rechenaufwand, der die Darstellung der Detail-Informationen verz&ouml;gert. Mit diesem Werkzeug ist der Administrator in der Lage, die Histogramm-Erzeugung in einer lastarmen Zeit durchzuf&uuml;hren.
		HINWEIS: Dieser Vorgang kann je nach Rechner-Leistung und Bildbestand erhebliche Zeit beanspruchen!<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR> -->
		
		<a name='5_db_wartung'><b>Datenbank-Wartung</b></a><BR><BR>
		
		Bei der Datenbank-Wartung werden grundlegende Kontrollen ausgef&uuml;hrt, die sicherstellen, da&szlig; die Kategoriezuordnungen korrekt erfolgten und alle erforderlichen Vorschaubilder vorhanden sind. Am Ende dieser Kontrolle wird die Dublettenpr&uuml;fung ausgef&uuml;hrt, mit der man die M&ouml;glichkeit hat, doppelte Eintr&auml;ge in der Datenbank zu finden und ggf. zu korrigieren.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='5_db_export'><b>Datenbank-Export</b></a><BR><BR>
		
		Mit Hilfe des Datenbank-Exports ist es m&ouml;glich, die komplette pic2base-Datenbank als XML- oder SQL-Datei zu exportieren. Dies ist hilfreich, wenn man sich z.B. mit der Absicht tr&auml;gt, die vorhandene Installation auf neue Hardware zu &uuml;bertragen.<BR>
		Siehe hierzu auch: <a href='http://www.pic2base.de/tipps1.php' target='top'>pic2base-Tipps</a><BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='5_loeschen'><b>Bilder endg&uuml;ltig l&ouml;schen</b></a><BR><BR>
		
		Um zu verhindern, da&szlig; nicht versehentlich Bilder aus der Datenbank gel&ouml;scht werden, haben nur die Mitglieder der Administratorengruppe die M&ouml;glichkeit, Datens&auml;tze endg&uuml;ltig zu l&ouml;schen. Wenn ein normaler Benutzer ein Bild l&ouml;schen m&ouml;chte, wird dies nur inaktiv gesetzt und im Bestand nicht mehr angezeigt.<BR>
		&Uuml;ber den Punkt \"Bilder l&ouml;schen\" k&ouml;nnen diese vorgemerkten Bilder von einem Administrator betrachtet, wieder hergestellt oder endg&uuml;ltig gel&ouml;scht werden.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='5_mp'><b>Meta-Protect</b></a> (Meta-Daten-Schutz)<BR><BR>
		
		Im Arbeitsbereich Meta-Protect kann eingestellt werden, welche Meta-Daten von berechtigten Usern nachtr&auml;glich manuell modifiziert werden d&uuml;rfen.
		Diese Einstellungen sollten mit gr&ouml;&#223;ter Sorgfalt erfolgen.
		Bereits mit den Grundeinstellungen von pic2base (alle Meta-Datenfelder sind abgew&auml;hlt) werden die folgenden Meta-Datenfelder trotzdem automatisch durch pic2base aktuell gehalten:</p>
		<ul style='margin:20px 5px; text-align:justify; width:550px;'>
		<li>Keywords: beinhaltet neben vorhandenen Daten die neu zugewiesenen Kategorien<BR>
		<li>Caption-Abstract: beinhaltet neben vorhandenen Daten die neu zugewiesene Bildbeschreibung<BR>
		<li>GPSLongitude: beinhaltet die GPS-L&auml;nge<BR>
		<li>GPSLatitude: beinhaltet die GPS-Breite<BR>
		<li>GPSAltitude: beinhaltet die GPS-H&ouml;he<BR>
		<li>City, GPSPosition: beinhaltet die Ortsbezeichnung des Aufnahmestandortes
		</ul>
		Hilfreich kann die Editier-Freigabe jedoch sein, wenn z.B. die interne Kamera-Uhr falsch gestellt war und das Aufnahme-Datum korrigiert werden soll. Aber auch die nachtr&auml;gliche Vergabe von Copyright-Vermerken l&auml;&#223;t sich &uuml;ber gezielte Freigabe-Einstellungen erm&ouml;glichen.<BR>
		Die zur Auswahl angebotene Anzahl Datenfelder kann sehr unterschiedlich sein, da pic2base nur diejenigen Felder zur Ansicht oder Bearbeitung anbietet, die die in der Datenbank befindlichen Bilder mitbrachten.
		Diese Ma&szlig;nahme soll verhindern, da&szlig; die komplette Datenfeld-Liste angezeigt wird (sie w&uuml;rde sehr lang sein!), obwohl nur wenige Felder mit Inhalt gef&uuml;llt waren.<BR>
		M&ouml;chte jemand bestimmte IPTC-Datenfelder editieren, die seine Bilder aber bisher nicht mitbrachten, kann <a href='http://www.pic2base.de/download/meta_test.jpg' target='top'>hier</a> ein Demo-Bild heruntergeladen werden, das mit dem (nahezu) vollst&auml;ndigen Satz IPTC-Felder ausgestattet ist.<BR>
		Dieses Bild ist einfach in der Datenbank zu erfassen. Wenn man sich nun die vollst&auml;ndige Ansicht der detaillierten Bildinformationen aufruft, werden alle mitgelieferten IPTC-Datenfelder dieses Bildes in die Datenbank &uuml;bernommen und stehen auch f&uuml;r andere Bilder zur Bearbeitung zur Verf&uuml;gung.<BR>
		<u>Hinweis:</u><BR>
		Bei der Verwendung der zus&auml;tzlichen IPTC-Datenfelder beachten Sie bitte die <a href='http://www.sno.phy.queensu.ca/~phil/exiftool/TagNames/IPTC.html' target='top'>Festlegungen zur Formatierung</a>.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='5_mv'><b>Meta-View</b></a> (Meta-Daten-Ansicht)<BR><BR>
		
		Im Arbeitsbereich Meta-View kann eingestellt werden, welche Meta-Daten in der Kompakt-Ansicht der Bilddetails angezeigt werden.<BR>
		Sollte es sich also f&uuml;r zweckm&auml;&szlig;ig erweisen, f&uuml;r bestimmte Recherche-Arbeiten immer sofort zu wissen, mit welcher Brennweite das Bild aufgenommen wurde,
		kann man festlegen, da&szlig; in der Kompaktansicht das Feld \"FocalLength\" oder \"FocalLengthIn35mmFormat\" angezeigt wird.<BR>
		Damit spart man sich u.a. den st&auml;ndigen Wechsel in die vollst&auml;ndige Detailansicht.<BR>
		In der vollst&auml;ndigen Ansicht werden hingegen immer alle verf&uuml;gbaren Informationen zu dem betreffenden Bild angezeigt.<BR>
		Die Anzahl der angezeigten Datenfelder ist von den gleichen Bedingungen abh&auml;ngig, wie dies bereits im vorigen <a href='#5_mp'>Absatz</a> beschrieben wurde.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='5_sw'><b>Software-Check</b></a><BR><BR>
		
		Mit dem Software-Check kontrolliert pic2base, ob alle ben&ouml;tigten externen Hilfsprogramme auf dem Server vorhanden sind. In der Regel wird dieses Tool nur w&auml;hrend der Erstinstallation von pic2base, bei der Installation von Updates
		oder nach Konfigurationsarbeiten (Software-Installationen / Deinstallationen) auf dem Server ben&ouml;tigt.<BR>
		Ab Version 0.41 startet das Tool automatisch beim ersten Start von pic2base nach der Installation und dann so lange, bis mindestens ein Bild in die Datenbank gestellt wurde.<BR>
		Dieser Check kann je nach Rechner-Ausstattung einige Zeit in Anspruch nehmen, stellt aber sicher, zuverl&auml;ssige Informationen &uuml;ber die notwendigen Softwarekomponenten zu erhalten.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		</fieldset>";
		break;
		
		CASE '6':
		echo "
		<fieldset id='fieldset_help3'>
		<legend style='color:blue; font-weight:bold;'>Hilfe zu den pers&ouml;nlichen Einstellungen</legend>
		Auf dieser Seite haben Sie die M&ouml;glichkeit, pers&ouml;nliche Einstellungen anzupassen sowie Passworte zu &auml;ndern.<BR>
		Es kann auch festgelegt werden, wie der Download der gefundenen Bilder erfolgen soll, per FTP oder als Direkt-Download.<BR><BR>
		Je nach erteilter Berechtigung, k&ouml;nnen Sie dies f&uuml;r Ihr eigenes Konto oder aber auch f&uuml;r die Konten der anderen Benutzer tun.<BR><BR>
		Weitere Hinweise finden Sie direkt auf der Einstellungen-Seite.
		</fielsdet>";
		break;
	}
	echo "
	</div>
	
	<div class='foot' id='foot'>
	<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
	</div>

</div>";

mysql_close($conn);
?>
</DIV>
</CENTER>
<script language="JavaScript" src="../../share/functions/resize_elements.js"></script>
</BODY>
</HTML>