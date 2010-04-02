<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Startseite</TITLE>
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
 * File: help1.php
 *
 * Copyright (c) 2003 - 2005 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 */

unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
}
 
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$sup_ft_arr = explode(chr(10),shell_exec($im_path."/identify -list format"));
//$sup_ft_arr = explode(chr(10),$sup_ft);
//print_r($sup_ft_arr);

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
		IF($file_format !== '')
		{
			//echo $file_format."<BR>";
		}
	}
	//print_r($zeil_arr);	
}


echo "

<div class='page'>

	<p id='kopf'>pic2base :: Online-Hilfe</p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
		createNavi4_1($c_username);
		echo "
		</div>
	</div>
	
	<div class='content'>";
	SWITCH($page)
	{
		CASE '0':
		echo "<p style='margin:20px 150px; text-align:justify; width:400px;'>
		<b>Hinweise zur Startseite:</b><BR>
		Das pic2base-Fenster unterteilt sich im wesentlichen in 3 Bereiche:<BR><BR>
		<u>Kopfzeile</u><BR>
		Hier findet der Benutzer Hinweise zur gerade aufgerufenen Seite, unter wessen Namen er arbeitet und ggf. welche Suchkriterien ausgew&auml;hlt wurden.<BR><BR>
		<u>Navigations-Leiste</u><BR>
		Am linken Fensterrand ist die Navigations-Leiste angeordnet. Je nach erteilten Benutzer-Rechten stehen die unterschiedlichen Men&uuml;-Buttons zur Auswahl: jeweils f&uuml;r die System-Administration, Erfassung, Bearbeitung, Suche, Hilfe und / oder Abmeldung vom pic2base-Server.<BR><BR>
		<u>Daten-Bereich</u><BR>
		Den gr&ouml;&#223;ten Platz nimmt der Datenbereich ein. Hier werden alle Auswahl-Men&uuml;s, Vorschau-Bilder oder Detail-Informationen dargestellt. Das Aussehen des Datenbereichs variiert je nach gew&auml;hlter Programm-Funktion bzw. Berechtigung des Benutzers.<BR>
		Weitere themenbezogene Hilfe erhalten Sie direkt in dem gew&auml;hlten Programm-Fenster.
		</p>
		<p style='margin:20px 150px; text-align:left; width:400px;'>
		<b>Hilfreiche Tipps zur Arbeit mit pic2base:</b><BR>
		Die folgende Reihenfolge der Arbeitsschritte hat sich f&uuml;r eine effektive Daten-Erfassung als zweckm&auml;&szlig;ig erwiesen:<BR>
		1) Bilder auf den Server laden,<BR>
    		   (Alle neuen Bilder liegen nun in der Kategorie \"Neuzug&auml;nge\")<BR>
		2) falls Track-Daten vorliegen: Bilder georeferenzieren,<BR>
		3) Beschreibungen editieren; dabei die bei der Geo-Referenzierung ggf. hinzugef&uuml;gten Ortsbezeichnungen ber&uuml;cksichtigen<BR>
		4) Qualit&auml;ts-Bewertung vornehmen,<BR>
		(damit sind die individuellen Informationen zugewiesen)<BR>
		5) im letzten Schritt Kategorien zuweisen<BR>
		Mit dieser Reihenfolge ist sichergestellt, da&szlig; alle unbearbeiteten Bilder in der Kategorie \"Neuzug&auml;nge\" zu finden sind, bis sie im letzten Schritt in die einzelnen Unterkategorien verteilt werden.
		</p>
		<p style='margin:20px 150px; text-align:left'>
		<b>Aktuelle Informationen</B><BR>
		Weitere Informationen zu pic2base erhalten Sie auf unserer <A HREF='http://www.pic2base.de'>Homepage</A>.<BR>
		<p style='margin:20px 150px; text-align:left'>
		<b>&Uuml;ber pic2base</B><BR>
		aktuelle Version: ".$version."<BR><BR>
		<b>Entwickler:</b><BR>
		Klaus Henneberg, <a href='mailto:info@pic2base.de?subject=Supportanfrage zur Version .$version.'><img src = \"../../share/images/letter.gif\" height=\"15\" border='0' title = 'Mail senden' align='top'></a><BR>
		Holger R&ouml;mer, <a href='mailto:hr@bshr.de'><img src = \"../../share/images/letter.gif\" height=\"15\" border='0' title = 'Mail senden' align='top'></a><BR>
		Jens Henneberg<BR>
		Daniel Grzonkowski<BR>
		
		<TABLE style='margin-left:150px;' border='0'>
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
		</p>";
		break;
		
		CASE '1':
		echo "<p style='margin:120px 150px; text-align:justify; width:400px;'>
		<b>Hinweise zum Bild-Upload per FTP (Batch-Prozess):</b><BR><BR>
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
		Unter Windows verwenden Sie einen geeigneten FTP-Client (z.B. WS-FTP) um Ihre Bilder auf den pic2base-Server hochzuladen. LINUX-Benutzer k&ouml;nnen z.B. den Konqueror f&uuml;r den Upload benutzen, wobei sich eine zweigeteilte Fenster-Ansicht als vorteilhaft erwiesen hat.<BR>Die Adresse des pic2base-FTP-Servers lautet ".$_SERVER['SERVER_NAME'].". Melden Sie sich mit Ihren pers&ouml;nlichen Zugangsdaten (Benutzername: ".$c_username." und entsprechendem Passwort) an und kopieren Ihre Bild-Dateien in Ihren <b>UPLOAD</b>-Ordner. Wenn Sie nun zu pic2base zur&uuml;ckkehren erhalten Sie einen Hinweis, da&#223; Bilddateien in Ihrem Upload-Ordner liegen. Klicken Sie zur &Uuml;bernahme auf den Button 'Upload starten'. Je nach Anzahl der Bilder und deren Dateigr&ouml;&#223;e kann der Upload eine Weile dauern. Im Anschlu&#223; daran werden Sie aufgefordert, den soeben &uuml;bernommenen Bildern weitere Informationen hinzuzuf&uuml;gen (Geo.Koordinaten, Bewertung, Beschreibung) und abschlie&#223;end allen Bildern Kategorien zuzuweisen. Dies ist f&uuml;r die sp&auml;tere Recherche von Vorteil.<BR><BR>
		</p>";
		break;
		
		CASE '2':
		echo "<p style='margin:20px 150px; text-align:justify; width:400px;'>
		<b>Hilfe zu den Suchm&ouml;glichkeiten:</b><BR><BR>
		
		<u>Auswahl der Bild-Bewertung</u><BR>
		Um die Anzahl der dargestellten Bilder einzuschr&auml;nken ist es m&ouml;glich, diese mit einer \"Benotung\" zu versehen. (siehe Bearbeitung | Bilder bewerten / Bewertung &auml;ndern)<BR>
		Die Note 1 (\"Sehr gut\"; 5 Sterne) stellt hierbei das h&ouml;chste Qualit&auml;tsniveau dar und die Note 5 (\"Ungen&uuml;gend\"; 1 Stern) das niedrigste.<BR>
		Wurde ein Bild noch nicht bewertet, erh&auml;lt es automatisch w&auml;hrend der Daten-Erfassung die Note 5 (ein Stern).<BR>
		&Uuml;ber den Punkt \"alle Bilder anzeigen\" l&auml;sst sich jedoch auch der gesamte Bildbestand - unabh&auml;ngig von einer eventuell vorhandenen Benotung - darstellen.<BR>
		Die momentan gew&auml;hlte Qualit&auml;tsstufe wird in der pic2base-Statusleiste angezeigt. (ein bis f&uuml;nf gelbe Sterne)<BR>
		Hinweis:<BR>F&uuml;r diese Funktion m&uuml;ssen Cookies zugelassen sein!<BR><BR>
		
		<u>Auflistung nach Jahrg&auml;ngen sortiert</u><BR>
		Der gesamte Bild-Bestand wird nach dem Erstellungsdatum unterteilt und Jahrgangsweise dargestellt. Das Erstellungsdatum wird bei der Bilderfassung aus den Meta-Daten ausgelesen. Handelt es sich um Bilddateien ohne Meta-Daten, werden diese Bilder in einem gesonderten Ordner (Bilder ohne zeitliche Zuordnung) zusammengefa&#223;.<BR><BR>
		<u>Suche nach Kategorien</u><BR>
		In dieser Ansicht besteht die M&ouml;glichkeit, Bilder anhand der zugewiesenen Kategorie zu recherchieren.<BR>
		Die oberste Ebene (Neuzug&auml;nge) beinhaltet alle Bilder, welchen noch keine Kategorie zugewiesen wurden.<BR>
		Die Ebenen darunter beinhalten Bilder der jeweiligen Kategorien.<BR>
		Mit einem Klick auf das Plus-Zeichen vor einem Kategorienamen klappt man den Kategoriebaum an dieser Stelle auf. In der folgenden Ansicht beginnt die Teilansicht des Kategoriebaumes nun auf der Ebene der ausgew&auml;hlten Kategorie. In dieser Ebene folgt weiter rechts in der Zeile ein gr&uuml;nes H&auml;kchen und dann eine Zahl, die die Summe aller Bilder in dieser Kategorie und aller Unterkategorien angibt.<BR>
		Klickt man nun auf das gr&uuml;ne H&auml;kchen, werden in der Filmstreifenansicht alle Bilder der gew&auml;hlten Kategorie und aller Unterkategorien (sofern vorhanden) angezeigt. Die Anzahl entspricht der Zahl hinter dem gr&uuml;nen H&auml;kchen.<BR>
		Eine Ausnahme bildet die Kategorie \"Neuzug&auml;nge\":<BR>
		Hier wird mit der Zahl hinter dem gr&uuml;nen H&auml;kchen nur die Anzahl aller Bilder <u>unterhalb</u> der Kategorie \"Neuzug&auml;nge\" angezeigt.<BR>
		Trotzdem kann bei einem Klick auf das gr&uuml;ne H&auml;kchen hinter der Kategorie \"Neuzug&auml;nge\" die Meldung erscheinen \"Jedem Bild wurde mind. eine Kategorie zugewiesen.\".<BR>
		Das bedeutet, die Kategorie \"Neuzug&auml;nge\" selbst ist leer. Alle Bilder sind einer entsprechenden Kategorie zugeordnet worden.<BR>
		Dies sollte der Normalfall sein, da die Kategorie \"Neuzug&auml;nge\" nur ein vorl&auml;ufiger Ablageort f&uuml;r gerade in das System aufgenommene Bilder ist.<BR>
		<BR>
		<u>Suche nach ausgew&auml;hlten Meta-Daten</u><BR>
		Was sind Meta-Daten?<BR>
		Meta-Daten sind zus&auml;tzliche, meist nicht sichtbare Informationen zu einem Bild. Dies k&ouml;nnen z.B. sein:<BR> Kamera-Modell, Aufnahme-Datum, Blende, Belichtungszeit, aber auch Angaben zum Copyright, Stichworte oder Bildbeschreibungen.<BR>
		Um &uuml;ber die Meta-Daten recherchieren zu k&ouml;nnen, m&uuml;ssen Sie zuerst das entsprechende Meta-Daten-Feld in der ersten Zeile ausw&auml;hlen.<BR>
		Dann bestimmen Sie, welche Bedingung dieser Meta-Wert erf&uuml;llen soll. (2. Auswahlfeld).<BR>
		Wenn dies erfolgt ist, steht Ihnen auch das 3. Auswahlfeld (Kriterium) zur Verf&uuml;gung. In diesem Auswahlfeld werden alle verf&uuml;gbaren Werte des jeweils gew&auml;hlten Meta-Feldes aufgelistet. D.h., der hier angezeigte Werte-Bereich variiert je nach der im Feld \"Meta-Daten-Feld\" getroffenen Auswahl.<BR>
		Wenn alle drei Felder ausgew&auml;hlt wurden, starten Sie die Suche mit einem Klick auf den Button \"Suche starten\".<BR>
		Beachten Sie bitte:<BR>
		Auch hier wird &uuml;ber all diejenigen Bilder recherchiert, welche der evtl. gew&auml;hlten Bewertung entsprechen. Einen entsprechenden Hinweis zur eingestellten Bewertung finden Sie in der pic2base-Kopfzeile.
		<BR><BR>
		<u>Suche nach Beschreibungstext</u><BR>
		Innerhalb der Beschreibungstexte der einzelnen Bilder k&ouml;nnen Sie nach Vorkommen bestimmter Begriffe suchen.<BR>
		Insgesamt steht eine Suche &uuml;ber 5 Begriffe zur Verfgung, welche mit den Boolschen Operatoren UND und OR verkn&uuml;pft werden k&ouml;nnen.<BR>
		Die Suche bezieht sich immer auf das Vorhandensein des Begriffs an einer beliebigen Stelle im Beschreibungstext. So liefert die Suche nach dem Wort \"Haus\" alle Bilder, in deren Beschreibungen \"Haus\" aber auch \"Hausmeister\" oder \"Bauhaus\" vorkommt.<BR>
		Eine Unterscheidung zwischen Gro&#223;- und Kleinschreibung wird nicht vorgenommen.<BR>
		Bilder, welchen noch kein Beschreibungstext zugewiesen wurden, werden von der Suche ausgeschlossen.<BR>
		Die Verwendung von Platzhaltern ist (noch) nicht m&ouml;glich.
		<BR><BR>
		<u>Suche nach Geo-Daten</u><BR>
		Bei der Suche nach Geo-Daten haben Sie zwei M&ouml;glichkeiten:<BR>
		- Die Suche anhand konkreter geografischer Koordinaten:<BR>
		Hier tragen Sie in die entsprechenden Felder die Angaben f&uuml;r L&auml;nge, Breite und ggf. H&ouml;he sowie den Umkreis, innerhalb dessen rund um den angegebenen Punkt gesucht werden soll, ein.<BR>
		Als Ergebnis erhalten Sie alle Bilder, die innerhalb des gew&uuml;nschten Umkreises um den angegebenen Punkt aufgenommen wurden.<BR>
		- Suche nach Ortsbezeichnungen:<BR>
		Wenn Sie bei der Geo-Referenzierung Ihrer Bilder diesen die entsprechenden Ortsnamen zugewiesen haben, haben Sie nun die M&ouml;glichkeit, in diesem Formular nach Bildern innerhalb eines bestimmten Umkreises um einen Ort zu suchen.<BR>
		Hierzu wird zun&auml;chst der geometrische Mittelpunkt aller Fotos ermittelt, welche an dem ausgew&auml;hlten Ort aufgenommen wurden. Von diesem Punkt aus werden alle Bilder ermittelt, welche sich innerhalb des angegebenen Umkreises befinden.<BR><BR>
		In beiden Suchm&ouml;glichkeiten kann als maximaler Umkreis eine Entfernung von 50 km angegeben werden.
		<BR><BR>
		
		<U>Bearbeitungsm&ouml;glichkeiten w&auml;hrend der Suche</U><BR>
		
		Wenn der angemeldete Benutzer berechtigt ist, Eigenschaften der gefundenen Bilder zu bearbeiten, stehen ihm die folgenden M&ouml;glichkeiten zur Verf&uuml;gung:<BR></p>
		<ul style='margin:20px 150px; text-align:justify; width:350px;'>
		<li>&Uuml;bertragung der Bild-Eigent&uuml;merschaft auf einen anderen User
		<li>l&ouml;schen des Bildes
		<li>Korrektur der Vorschau-Ansicht bei RAW-Format-Bildern
		<li>manuelle Georeferenzierung bzw. Korrektur der bereits erfolgten automatischen Geo-Referenzierung
		<li>manuelle Bearbeitung freigegebener Meta-Daten
		</ul>
		<p style='margin:20px 150px; text-align:justify; width:400px;'>
		Die o.g. Funktionen werden &uuml;ber die jeweiligen Icons aufgerufen.<BR>
		Weiterhin besteht bei entsprechender Berechtigung die M&ouml;glichkeit, die Bildbeschreibung zu &auml;ndern. Hierzu Nehmen Sie die &Auml;nderung direkt in dem angezeigten Textfeld vor und speichern diese dann durch einen Klick auf den Button \"&Auml;nderungen speichern\".<BR><BR>
		Zus&auml;tzlich haben Sie die M&ouml;glichkeit, ein sogenanntes Kategorie-Lexikon anzulegen. Hiermit besteht die Option, zu jeder Kategorie zus&auml;tzliche Informationen in der Datenbank zu hinterlegen. So k&ouml;nnte man z.B. der Kategorie \"Blankenburg (Harz)\" Informationen zur geografischen Lage, der Einwohnerzahl, der Erreichbarkeit mit Bus / Bahn / Auto usw. zuweisen.<BR>
		Die Zuweisung erfolgt, indem Sie in der Ansicht \"Suche nach Kategorien\" auf den gew&uuml;nschten Kategorienamen klicken und dann die Informationen in dem sich &ouml;ffnenden Dialogfenster eintragen. Diese zus&auml;tzlichen Informationen werden formatiert abgespeichert und k&ouml;nnen neben Links auch Verweise auf externe Bilder beinhalten.<BR>
		Kategorien, denen bereits Informationen zugewiesen wurden, werden im Kategoriebaum blau hervorgehoben.<BR><BR>
		Analog kann ein Fototagebuch angelegt werden, indem man sich in der Ansicht \"Auflistung nach Jahrg&auml;ngen sortiert\" den betreffenden Tag heraussucht, anklickt und die gew&uuml;nschte Information hinterlegt.<BR>
		Auch hier wird ein Tag, zu dem bereits zus&auml;tzliche Informationen existieren, farblich hervorgehoben.
		</p>";
		break;
		
		CASE '3':
		echo "<p style='margin:20px 150px; text-align:justify; width:400px;'>
		<b>Hilfe zu den Bearbeitungsm&ouml;glichkeiten:</b><BR><BR>
		
		<span style='text-decoration:underline;'>Geo-Referenzierung:</span><BR>
		Wenn Sie im Besitz von Geo-Daten sind, k&ouml;nnen Sie &uuml;ber diesen Menpunkt Ihren Bildern die geografischen Koordinaten des Aufnahme-Standortes zuweisen.<BR>
		Die derzeit unterst&uuml;tzten Datenlogger sind in dem entsprechenden Auswahlfeld schwarz dargestellt, vorbereitete, aber noch nicht getestete Modelle sind inaktiv dargestellt.<BR><BR>
		
		<span style='text-decoration:underline;'>Bilder bewerten:</span><BR>
		pic2base bietet die M&ouml;glichkeit, jedes Bild hinsichtlich bestimmter, pers&ouml;nlicher Ma&#223;st&auml;be zu bewerten und hierf&uuml;r \"Noten\" von 1 - 5 zu vergeben, wobei die 1 ein besonders gelungenes Bild kennzeichnet und die Note 5 Bilder der untersten Qualit&auml;tsstufe repr&auml;sentiert.<BR>
		Bei der Aufnahme der Bilder in der Datenbank wird allen Bildern zun&auml;chst die Note 5 vergeben. Besser einzustufende Bilder m&uuml;ssen also im Nachhinein h&ouml;her bewertet werden.<BR><BR>
		
		<span style='text-decoration:underline;'>Beschreibungen zuweisen/&auml;ndern:</span><BR>
		Zu jedem Bild kann ein ausf&uuml;hrlicher Beschreibungstext hinzugef&uuml;gt werden.<BR>
		Dies nehmen Sie &uuml;ber diesen Men&uuml;punkt vor.<BR><BR>
		
		<span style='text-decoration:underline;'>Kategorie zuweisen:</span><BR>
		&Uuml;ber diesen Menpunkt k&ouml;nnen SIe den Bildern Kategorien zuweisen.<BR>
		Diese m&uuml;ssen im Vorfeld durch den Administrator angelegt worden seien.<BR><BR>
		
		<span style='text-decoration:underline;'>Quick-Preview hochformatiger Bilder erzeugen</span><BR>
		Zur schnelleren Darstellung hochformatiger Bilder m&uuml;ssen diese einmalig vor der ersten Betrachtung gedreht und als Kopie angelegt werden. Dies geschieht normalerweise beim ersten &Uuml;berfahren des entsprechenden Bildes mit dem Mauszeiger in der Filmstreifen-Ansicht, kostet aber entsprechend Rechenzeit auf dem Server. Dieser Vorgang kann aber auch f&uuml;r alle Bilder, f&uuml;r welche es noch kein entsprechend gedrehtes Vorschau-Bild gibt, auf einmal durchgef&uuml;hrt werden.<BR>
		Hinweis: Dieser Vorgang kann - je nach Anzahl der zu drehenden Bilder - erheblichen Rechenaufwand erfordern!<BR>
		</p>";
		break;
		
		CASE '4':
		echo "<p style='margin:20px 150px; text-align:justify; width:400px;'>
		<b>Hilfe zur Bild-Bewertung:</b><BR><BR>
		Um die gezielte Suche nach Bildern mit bestimmten Qualit&auml;tsanforderungen zu erm&ouml;glichen, kann jedes Bild qualitativ bewertet werden. Die Bewertung reicht von \"1 Stern\" (ungen&uuml;gend) bis \"5 Sterne\" (sehr gut).<BR>
		Die eigentliche Vergabe der Sterne erfolgt durch anklicken des betreffenden Sterns unter dem jeweiligen Bild, wobei der linke Stern f&uuml;r \"1 Stern\" steht und der reche f&uuml;r \"5 Sterne\".<BR>
		Standardm&auml;&szlig;ig wird jedem Bild bei der Erfassung ein Stern - also die niedrigste Qualit&auml;tsstufe - zugewiesen.<BR><BR>
		<b>Hilfe zur Geo-Referenzierung:</b><BR><BR>
		
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
		</p>";
		break;
		
		CASE '5':
		echo "<a name='top'></a><p style='margin:20px 150px; text-align:justify; width:400px;'>
		<b>Hilfe zum Administrations-Bereich:</b><BR><BR>
		
		Inhalt:<BR>
		<a href='#kat'>Kategotien</a><BR>
		<a href='#user'>Benutzer</a><BR>
		<a href='#groups'>Gruppen</a><BR>
		<a href='#rights'>Berechtigungen</a><BR>
		<a href='#ftp'>FTP-Statistik</a><BR>
		<a href='#log'>P2b-Log</a><BR>
		<a href='#md5'>md5-Check</a><BR>
		<a href='#hist'>Histogramme</a><BR>
		<a href='#md'>Meta-Daten</a><BR>
		<a href='#mp'>Meta-Protect</a><BR>
		<a href='#sw'>Software-Check</a><BR><BR>
		
		<a name = 'kat'>Kategorien</a><BR>

		Im Arbeitsbereich Kategorien kann der Kategoriebaum gepflegt werden:
		Es k&ouml;nnen Kategorien</p>
		<ul style='margin:20px 150px; text-align:justify; width:350px;'>
		<li>erzeugt oder gel&ouml;scht werden,
		<li>umbenannt werden,
		<li>umsortiert werden.
		</ul>
		<p style='margin:20px 150px; text-align:justify; width:400px;'>
		Beim Aufruf des Kategorie-Arbeitsbereichs gelangt man zun&auml;chst in die Kategorie-Verwaltungsansicht. Von hier k&ouml;nnen die Bereiche Sortierung und Wartung aufgerufen werden.
		Weiterf&uuml;hrende Informationen stehen auf den jeweiligen Seiten zur Verf&uuml;gung.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='user'>Benutzer</a><BR><BR>
		
		Im Arbeitsbereich Benutzer kann der Benutzerkreis f&uuml;r diese pic2base-Datenbank gepflegt werden.
		Es k&ouml;nnen Benutzer
		hinzugef&uuml;gt werden,
		gel&ouml;scht werden oder
		die Gruppenzugeh&ouml;rigkeit der Benutzer ver&auml;ndert werden.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='groups'>Gruppen</a><BR><BR>
		
		Im Arbeitsbereich Gruppen k&ouml;nnen Benutzergruppen und deren Zugriffsrechte definiert werden.
		Standardm&auml;&#223;ig sind die Gruppen 'Admin', 'Fotograf', 'Gast' und 'Web-User' mit vordefinierten Rechten angelegt. Diese k&ouml;nnen entsprechend der Erfordernisse im jeweiligen Anwendungsfall individuell angepa&#223;t werden.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='rights'>Berechtigungen</a><BR><BR>
		
		Im Arbeitsbereich Berechtigungen kann festgelegt werden, welche Handlungen in pic2base durch Zugriffsbeschr&auml;nkungen reglementiert werden sollen.
		Eine typische Frage ist, welcher Benutzergruppe der Datei-Upload gestattet wird. Dies wird &uuml;ber die Berechtigung 'Bilder erfassen' (addpic) geregelt. Wird einer Gruppe dies erlaubt. D&uuml;rfen alle Mitglieder dieser Gruppe Bilder erfassen (Upload ist m&ouml;glich)
		M&ouml;gliche Berechtigungen m&uuml;ssen programmseitig vorgegeben werden (fest einprogrammiert!)<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='ftp'>FTP-Statistik</a><BR><BR>
		
		&uuml;ber den Link FTP-Statistik gelangt man zu einem Tool, mit dessen Hilfe der Zugriff via FTP auf diesen pic2base-Server kontrolliert werden kann. Hier kann in die Protokolldatei eingesehen werden und der verursachte Traffic ausgelesen werden.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='log'>P2b-Log</a><BR><BR>
		
		In der p2b-Logdatei werden Aktivit&auml;ten protokolliert, die Auskunft &uuml;ber die Zugriffe auf die pic2base-Datenbank geben.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='md5'>md5-Check</a><BR><BR>
		
		Mit Hilfe des md5-Checks kann kontrolliert werden, ob zu jeder Bilddatei eine Pr&uuml;fsumme erzeugt wurde. Diese kann sp&auml;ter zur Identifikation von Duplikaten herangezogen werden.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='hist'>Histogramme</a><BR><BR>
		
		Der Arbeitsbereich Histogramme bietet die M&ouml;glichkeit zu pr&uuml;fen, ob zu jedem in der Datenbank befindlichen Bild die entsprechenden Histogramme (R,G,B,Grey) existieren. Wenn nicht, werden diese &uuml;ber den Aufruf des Men&uuml;punktes Histogramme erzeugt. Normalerweise ist diese Kontrolle nicht erforderlich, da die Histogramme von pic2base (ab Version 0.40) bereits bei der Bild-Erfassung automatisch erzeugt werden bzw. beim Aufruf der Bilddetail-Infoseite diese Kontrolle nochmals erfolgt. Existieren die Histogramme f&uuml;r das betrachtete Bild nicht (z.B. wenn Bilder mit einer &auml;lteren Version von pic2base erfa&#223;t wurden), w&uuml;rden sie nun nachtr&auml;glich erzeugt werden. Dies erfordert jedoch zus&auml;tzlichen Rechenaufwand, der die Darstellung der Detail-Informationen verz&ouml;gert. Mit diesem Werkzeug ist der Administrator in der Lage, die Histogramm-Erzeugung in einer lastarmen Zeit durchzuf&uuml;hren.
		HINWEIS: Dieser Vorgang kann je nach Rechner-Leistung und Bildbestand erhebliche Zeit beanspruchen!<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='md'>Meta-Daten</a><BR><BR>
		
		Im Arbeitsbereich Meta-Daten hat der Administrator die M&ouml;glichkeit zu kontrollieren, ob aus allen Fotos die vorhandenen Meta-Daten ausgelesen und in die Datenbank &uuml;bertragen wurden.
		Wichtiger Hinweis: Hier treffen die selben Einschr&auml;nkungen wie beim Punkt 'Histogramme' zu.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a ame='mp'>Meta-Protect</a></a><BR><BR>
		
		Im Arbeitsbereich Meta-Protect kann eingestellt werden, welche Meta-Daten von berechtigten Usern nachtr&auml;glich manuell modifiziert werden d&uuml;rfen.
		Diese Einstellungen sollten mit gr&ouml;&#223;ter Sorgfalt erfolgen.
		Bereits mit den Grundeinstellungen von pic2base (alle Meta-Datenfelder sind abgew&auml;hlt) werden die folgenden Meta-Datenfelder trotzdem aktuell gehalten:</p>
		<ul style='margin:20px 150px; text-align:justify; width:350px;'>
		<li>Keywords: beinhaltet neben vorhandenen Daten die neu zugewiesenen Kategorien<BR>
		<li>Caption-Abstract: beinhaltet neben vorhandenen Daten die neu zugewiesene Bildbeschreibung<BR>
		<li>GPSLongitude: beinhaltet die GPS-L&auml;nge<BR>
		<li>GPSLatitude: beinhaltet die GPS-Breite<BR>
		<li>GPSAltitude: beinhaltet die GPS-H&ouml;he<BR>
		<li>City, GPSPosition: beinhaltet die Ortsbezeichnung des Aufnahmestandortes
		</ul>
		<p style='margin:20px 150px; text-align:justify; width:400px;'>
		Hilfreich kann die Editier-Freigabe jedoch sein, wenn z.B. die interne Kamera-Uhr falsch gestellt war und das Aufnahme-Datum korrigiert werden soll. Aber auch die nachtr&auml;gliche Vergabe von Copyright-Vermerken l&auml;&#223;t sich &uuml;ber gezielte Freigabe-Einstellungen erm&ouml;glichen.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='sw'>Software-Check</a></a><BR><BR>
		
		Mit dem Software-Check kontrolliert pic2base, ob alle ben&ouml;tigten externen Hilfsprogramme auf dem Server vorhanden sind. In der Regel wird dieses Tool nur w&auml;hrend der Erstinstallation von pic2base oder nach Konfigurationsarbeiten (Software-Installationen / Deinstallationen) auf dem Server ben&ouml;tigt.<BR>
		Ab Version 0.41 startet das Tool automatisch beim ersten Start von pic2base nach der Installation und dann so lange, bis mindestens ein Bild in die Datenbank gestellt wurde.<BR>
		DIeser Check kann je nach Rechner-Ausstattung einige Zeit in Anspruch nehmen, stellt aber sicher, zuverl&auml;ssige Informationen &uuml;ber die notwendigen Softwarekomponenten zu erhalten.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		</p>";
		break;
		
		CASE '6':
		echo "<p style='margin:20px 150px; text-align:justify; width:400px;'>
		<b>Hilfe zu den pers&ouml;nlichen Einstellungen:</b><BR><BR>
		Auf dieser Seite haben Sie die M&ouml;glichkeit, Ihre pers&ouml;nlichen Einstellungen anzupassen.<BR><BR>
		Passwort &auml;ndern:<BR>
		Tragen Sie in dem ersten Feld Ihr derzeitiges Passwort ein und in den folgenden zwei Feldern das gew&uuml;nschte neue Passwort.<BR>
		Beachten Sie, da&szlig; das neue Passwort aus mindestens 5 Zeichen bestehen mu&szlig; und nicht leer sein darf.
		</p>";
		break;
	}
	echo "
	</div>
	<br style='clear:both;' />

	<p id='fuss'><A style='margin-right:745px;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>".$cr."</p>

</div>";

mysql_close($conn);
?>
</DIV>
</CENTER>
</BODY>
</HTML>