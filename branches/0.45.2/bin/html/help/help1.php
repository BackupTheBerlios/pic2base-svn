<?php
IF (!$_COOKIE['login'])
{
include '../../share/global_config.php';
//var_dump($sr);
  header('Location: ../../../index.php');
}
?>

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
		Am linken Fensterrand ist die Navigations-Leiste angeordnet. Je nach erteilten Benutzer-Rechten stehen die unterschiedlichen Men&uuml;-Buttons zur Auswahl: jeweils f&uuml;r die System-Administration, pers&ouml;nliche Einstellungen, Erfassung, Bearbeitung, Suche, Hilfe und / oder Abmeldung vom pic2base-Server.<BR><BR>
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
		Mit dieser Reihenfolge ist sichergestellt, da&szlig; alle unbearbeiteten Bilder in der Kategorie \"Neuzug&auml;nge\" zu finden sind, bis sie im letzten Schritt in die einzelnen Unterkategorien verteilt werden.<BR>
		Sollen gr&ouml;ssere Mengen an Bildern erfasst werden, ist es zweckm&auml;&szlig;ig, nicht mehr als etwa 100 Bilder in einem Durchgang zu verarbeiten.<BR>
		Damit werden die Ladezeiten der einzelnen Bearbeitungsseiten kurz gehalten und die Arbeit geht insgesamt fl&uuml;ssiger vonstatten.
		</p>
		<p style='margin:20px 150px; text-align:left'>
		<b>Aktuelle Informationen</B><BR>
		Weitere Informationen zu pic2base erhalten Sie auf unserer <A HREF='http://www.pic2base.de'>Homepage</A>.<BR>
		<p style='margin:20px 150px; text-align:left'>
		<b>&Uuml;ber pic2base</B><BR>
		installierte Version: ".$version." ".$vom."<BR><BR>
		<b>Entwickler:</b><BR>
		Klaus Henneberg, <a href='mailto:info@pic2base.de?subject=Supportanfrage zur Version .$version.'><img src = \"../../share/images/letter.gif\" height=\"15\" border='0' title = 'Mail senden' align='top'></a><BR>
		Holger R&ouml;mer, <a href='mailto:hr@roemix.eu'><img src = \"../../share/images/letter.gif\" height=\"15\" border='0' title = 'Mail senden' align='top'></a><BR>
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
		echo "<a name='top'></a><p style='margin:20px 150px; text-align:justify; width:400px;'>
		<b>Hilfe zu den Suchm&ouml;glichkeiten</b><BR><BR>
		
		Inhalt:<BR>
		<a href='#2_bewertung'>Auswahl der Bild-Bewertung</a><BR>
		<a href='#2_zeit'>Auflistung nach Jahrg&auml;ngen sortiert</a><BR>
		<a href='#2_kat'>Suche nach Kategorien</a><BR>
		<a href='#2_meta'>Suche nach ausgew&auml;hlten Meta-Daten</a><BR>
		<a href='#2_desc'>Suche nach Beschreibungstext</a><BR>
		<a href='#2_geo'>Suche nach Geo-Daten</a><BR>
		<a href='#2_edit'>Bearbeitungsm&ouml;glichkeiten der Suchergebnisse</a><BR><BR>
		
		<a name = '2_bewertung'></a>Auswahl der Bild-Bewertung<BR>
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
		
		<a name = '2_zeit'></a>Auflistung nach Jahrg&auml;ngen sortiert<BR>
		Der gesamte Bild-Bestand wird nach dem Erstellungsdatum der Bilder unterteilt und Jahrgangsweise dargestellt. Das Erstellungsdatum wird bei der Bilderfassung aus den Meta-Daten ausgelesen.
		Handelt es sich um Bilddateien ohne Meta-Daten, werden diese Bilder in einem gesonderten Ordner (Bilder ohne zeitliche Zuordnung) zusammengefasst. Diesen Bildern kann - die entsprechenden Berechtigung vorausgesetzt - sp&auml;ter manuell das Aufnahmedatum zugewiesen werden.<BR>
		Die zeitliche Darstellung kann bis auf die Tages-Ebene heruntergebrochen werden, indem zun&auml;chst das Aufnahmejahr mit einem Klick auf die betreffende Jahreszahl gew&auml;hlt wird,
		und dann der Monat analog mit einem Klick auf den Monatsnamen gew&auml;hlt wird.
		Unter dem Monat finden sich alle Tage, an welchen Aufnahmen angefertigt wurden. Mit einem Klick auf das gr&uuml;ne H&auml;kchen hinter dem Datum erh&auml;lt man in der Filmstreifenansicht die gesuchten Bilder.<BR>
		Hinweis:<BR>
		Wenn man auf das Datum klickt, kann man - je nach erteiltem Recht - einen Tagebucheintrag hinzuf&uuml;gen oder einsehen. Auf diese Weise kann auf bequeme Art ein sogenanntes Foto-Tagebuch gef&uuml;hrt werden.
		Der Inhalt des Fototagebuchs wird in der Detailansicht zu den entsprechenden Bildern mit angezeigt.<BR>
		Ein Datum, dem bereits Informationen zugewiesen wurden, wird in der Ansicht blau hervorgehoben.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '2_kat'></a>Suche nach Kategorien<BR>
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
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '2_meta'></a>Suche nach ausgew&auml;hlten Meta-Daten<BR>
		Was sind Meta-Daten?<BR>
		Meta-Daten sind zus&auml;tzliche, meist nicht sichtbare Informationen zu einem Bild. Dies k&ouml;nnen z.B. sein:<BR> Kamera-Modell, Aufnahme-Datum, Blende, Belichtungszeit, aber auch Angaben zum Copyright, Stichworte oder Bildbeschreibungen.<BR>
		Um &uuml;ber die Meta-Daten recherchieren zu k&ouml;nnen, m&uuml;ssen Sie zuerst das entsprechende Meta-Daten-Feld in der ersten Zeile des Suchformulars ausw&auml;hlen.<BR>
		Dann bestimmen Sie, welche Bedingung dieser Meta-Wert erf&uuml;llen soll. (2. Auswahlfeld).<BR>
		Wenn dies erfolgt ist, steht Ihnen auch das 3. Auswahlfeld (Kriterium) zur Verf&uuml;gung. In diesem Auswahlfeld werden alle verf&uuml;gbaren Werte des jeweils gew&auml;hlten Meta-Feldes aufgelistet. D.h., der hier angezeigte Werte-Bereich variiert je nach der im Feld \"Meta-Daten-Feld\" getroffenen Auswahl.<BR>
		Wenn alle drei Felder ausgew&auml;hlt wurden, starten Sie die Suche mit einem Klick auf den Button \"Suche starten\".<BR>
		Beachten Sie bitte:<BR>
		Auch hier wird &uuml;ber all diejenigen Bilder recherchiert, welche der evtl. gew&auml;hlten Bewertung entsprechen. Einen entsprechenden Hinweis zur eingestellten Bewertung finden Sie in der pic2base-Kopfzeile.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '2_desc'></a>Suche nach Beschreibungstext<BR>
		Innerhalb der Beschreibungstexte der einzelnen Bilder k&ouml;nnen Sie nach Vorkommen bestimmter Begriffe suchen.<BR>
		Insgesamt steht eine Suche &uuml;ber 5 Begriffe zur Verfgung, welche mit den Booleschen Operatoren UND und OR verkn&uuml;pft werden k&ouml;nnen.<BR>
		Die Suche bezieht sich immer auf das Vorhandensein des Begriffs an einer beliebigen Stelle im Beschreibungstext. So liefert die Suche nach dem Wort \"Haus\" alle Bilder, in deren Beschreibungen \"Haus\" aber auch \"Hausmeister\" oder \"Bauhaus\" vorkommt.<BR>
		Eine Unterscheidung zwischen Gro&#223;- und Kleinschreibung wird nicht vorgenommen.<BR>
		Bilder, welchen noch kein Beschreibungstext zugewiesen wurden, werden von der Suche ausgeschlossen.<BR>
		Die Verwendung von Platzhaltern ist (noch) nicht m&ouml;glich.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '2_geo'></a>Suche nach Geo-Daten<BR>
		Bei der Suche nach Geo-Daten haben Sie zwei M&ouml;glichkeiten:<BR>
		- Die Suche anhand konkreter geografischer Koordinaten:<BR>
		Hier tragen Sie in die entsprechenden Felder die Angaben f&uuml;r L&auml;nge, Breite und ggf. H&ouml;he sowie den Umkreis, innerhalb dessen rund um den angegebenen Punkt gesucht werden soll, ein.<BR>
		Als Ergebnis erhalten Sie alle Bilder, die innerhalb des gew&uuml;nschten Umkreises um den angegebenen Punkt aufgenommen wurden.<BR>
		- Suche nach Ortsbezeichnungen:<BR>
		Wenn Sie bei der Geo-Referenzierung Ihrer Bilder diesen die entsprechenden Ortsnamen zugewiesen haben, haben Sie nun die M&ouml;glichkeit, in diesem Formular nach Bildern innerhalb eines bestimmten Umkreises um einen Ort zu suchen.<BR>
		Hierzu wird zun&auml;chst der geometrische Mittelpunkt aller Fotos ermittelt, welche an dem ausgew&auml;hlten Ort aufgenommen wurden. Von diesem Punkt aus werden alle Bilder ermittelt, welche sich innerhalb des angegebenen Umkreises befinden.<BR><BR>
		In beiden Suchm&ouml;glichkeiten kann als maximaler Umkreis eine Entfernung von 50 km angegeben werden.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '2_edit'></a>Bearbeitungsm&ouml;glichkeiten der Suchergebnisse<BR>
		
		Wenn der angemeldete Benutzer berechtigt ist, Eigenschaften der gefundenen Bilder zu bearbeiten, stehen ihm die folgenden M&ouml;glichkeiten zur Verf&uuml;gung:<BR></p>
		<ul style='margin:20px 150px; text-align:justify; width:350px;'>
		<li>&Uuml;bertragung der Bild-Eigent&uuml;merschaft auf einen anderen User: <img src='../../share/images/change_owner.gif' height='12px' />
		<li>l&ouml;schen des Bildes: <img src='../../share/images/trash.gif' height='12px' />
		<li>Korrektur der Vorschau-Ansicht bei RAW-Bildern: <img src='../../share/images/reload.png' height='12px' />
		<li>manuelle Georeferenzierung bzw. Korrektur der bereits erfolgten automatischen Geo-Referenzierung: <img src='../../share/images/del_geo_ref.gif' height='12px' />
		<li>manuelle Bearbeitung freigegebener Meta-Daten: <img src='../../share/images/info.gif' height='12px' />
		</ul>
		<p style='margin:20px 150px; text-align:justify; width:400px;'>
		Bei entsprechender Berechtigung k&ouml;nnen die o.g. Funktionen &uuml;ber die jeweiligen Icons aufgerufen werden. (siehe Abb.:)<BR><BR>
		<img src='img/recherche1.png'></img><BR><BR>
		Weiterhin besteht bei entsprechender Berechtigung die M&ouml;glichkeit, die Bildbeschreibung zu &auml;ndern. Hierzu Nehmen Sie die &Auml;nderung direkt in dem angezeigten Textfeld vor und speichern diese dann durch einen Klick auf den Button \"&Auml;nderungen speichern\".<BR><BR>
		Zus&auml;tzlich haben Sie die M&ouml;glichkeit, ein sogenanntes Kategorie-Lexikon anzulegen. Hiermit besteht die Option, zu jeder Kategorie zus&auml;tzliche Informationen in der Datenbank zu hinterlegen. So k&ouml;nnte man z.B. der Kategorie \"Blankenburg (Harz)\" Informationen zur geografischen Lage, der Einwohnerzahl, der Erreichbarkeit mit Bus / Bahn / Auto usw. zuweisen.<BR>
		Die Zuweisung erfolgt, indem Sie in der Ansicht \"Suche nach Kategorien\" auf den gew&uuml;nschten Kategorienamen klicken und dann die Informationen in dem sich &ouml;ffnenden Dialogfenster eintragen. Diese zus&auml;tzlichen Informationen werden formatiert abgespeichert und k&ouml;nnen neben Links auch Verweise auf externe Bilder beinhalten.<BR>
		Kategorien, denen bereits Informationen zugewiesen wurden, werden im Kategoriebaum blau hervorgehoben.<BR><BR>
		Analog kann ein Fototagebuch angelegt werden, indem man sich in der Ansicht \"Auflistung nach Jahrg&auml;ngen sortiert\" den betreffenden Tag heraussucht, anklickt und die gew&uuml;nschte Information hinterlegt.<BR>
		Auch hier wird ein Tag, zu dem bereits zus&auml;tzliche Informationen existieren, farblich hervorgehoben.<BR><BR>
		Der Inhalt des Kategorielexikons und des Forttagebuchs werden bei den betreffenden Bildern jeweils mit in der Komplett-Ansicht der Detailinformationen ausgegeben. (siehe Abb.:)<BR><BR>
		<img src='img/recherche2.png' width='400px'></img><BR><BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		</p>";
		break;
		
		CASE '3':
		echo "<a name='top'></a><p style='margin:20px 150px; text-align:justify; width:400px;'>
		<b>&Uuml;bersicht &uuml;ber die Bearbeitungsm&ouml;glichkeiten</b><BR><BR>
		
		Inhalt:<BR>
		<a href='#3_georef'>Geo-Referenzierung</a><BR>
		<a href='#3_bewertung'>Bild-Bewertung</a><BR>
		<a href='#3_desc'>Beschreibungen zuweisen</a><BR>
		<a href='#3_kat'>Kategorien zuweisen</a><BR>
		<a href='#3_del_kat'>Kategorie-Zuweisungen aufheben</a><BR>
		<a href='#3_qp'>Quick-Preview hochformatiger Bilder erstellen</a><BR><BR>
		
		<a name = '3_georef'>Geo-Referenzierung</a><BR>
		Wenn Sie im Besitz von Geo-Daten sind, k&ouml;nnen Sie &uuml;ber diesen Menpunkt Ihren Bildern die geografischen Koordinaten des Aufnahme-Standortes zuweisen.<BR>
		Die derzeit unterst&uuml;tzten Datenlogger sind in dem entsprechenden Auswahlfeld schwarz dargestellt, vorbereitete, aber noch nicht getestete Modelle sind inaktiv dargestellt.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '3_bewertung'>Bilder bewerten</a><BR>
		pic2base bietet die M&ouml;glichkeit, jedes Bild hinsichtlich bestimmter, pers&ouml;nlicher Ma&#223;st&auml;be zu bewerten und hierf&uuml;r \"Noten\" von 1 - 5 zu vergeben, wobei die 1 ein besonders gelungenes Bild kennzeichnet und die Note 5 Bilder der untersten Qualit&auml;tsstufe repr&auml;sentiert.<BR>
		Bei der Aufnahme der Bilder in der Datenbank wird allen Bildern zun&auml;chst keine Note vergeben. Besser einzustufende Bilder m&uuml;ssen also im Nachhinein h&ouml;her bewertet werden.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '3_desc'>Beschreibungen zuweisen</a><BR>
		Jedem Bild kann in pic2base ein ausf&uuml;hrlicher Beschreibungstext zugef&uuml;gt werden.<BR>
		&Uuml;ber diesen Men&uuml;punkt haben Sie die M&ouml;glichkeit, mehreren Bildern in einem Durchgang den gleichen Beschreibungstext zuzuweisen,
		aber auch einzelne Bilder detailliert zu beschreiben.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '3_kat'>Kategorie zuweisen</a><BR>
		&Uuml;ber diesen Men&uuml;punkt k&ouml;nnen Sie den Bildern Kategorien zuweisen.<BR>
		Die Kategorien m&uuml;ssen im Vorfeld durch den Administrator oder einem Benutzer mit entsprechenden Rechten angelegt worden seien.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '3_del_kat'>Kategorie-Zuweisung aufheben</a><BR>
		Sollte versehentlich einem Bild eine falsche Kategorie zugewiesen worden sein, kann &uuml;ber diesen Men&uuml;punkt diese Zuweisung wieder r&uuml;ckg&auml;ngig gemacht werden.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '3_qp'>Quick-Preview hochformatiger Bilder erzeugen</a><BR>
		Zur schnelleren Darstellung hochformatiger Bilder m&uuml;ssen diese einmalig vor der ersten Betrachtung in pic2base gedreht und als Kopie angelegt werden. Dies geschieht normalerweise beim ersten &Uuml;berfahren des entsprechenden Bildes mit dem Mauszeiger in der Filmstreifen-Ansicht, kostet aber entsprechend Rechenzeit auf dem Server. Dieser Vorgang kann aber auch f&uuml;r alle Bilder, f&uuml;r welche es noch kein entsprechend gedrehtes Vorschau-Bild gibt, auf einmal durchgef&uuml;hrt werden.<BR>
		Hinweis: Dieser Vorgang kann - je nach Anzahl der zu drehenden Bilder - erheblichen Rechenaufwand erfordern!<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		Weiterf&uuml;hrende Informationen erhalten Sie auf den entsprechenden Bearbeitungs-Seiten.<BR>
		</p>";
		break;
		
		CASE '4':
		echo "<a name='top'></a><p style='margin:20px 150px; text-align:justify; width:400px;'>
		<b>Hilfe zu den Bearbeitungsm&ouml;glichkeiten</b><BR><BR>
		
		Inhalt:<BR>
		<a href='#4_georef'>Geo-Referenzierung</a><BR>
		<a href='#4_bewertung'>Bild-Bewertung</a><BR>
		<a href='#4_desc'>Beschreibungen zuweisen</a><BR>
		<a href='#4_kat'>Kategorien zuweisen</a><BR>
		<a href='#4_del_kat'>Kategorie-Zuweisungen aufheben</a><BR>
		<a href='#4_qp'>Quick-Preview hochformatiger Bilder erstellen</a><BR><BR>
		
		<a name = '4_georef'>Geo-Referenzierung</a><BR>
		Welchen Vorteil bietet die Georeferenzierung?<BR>
		Bei der Geo-Referenzierung wird zu jedem Bild der Kamerastandort zum jeweiligen Aufnahmezeitpunkt vermerkt. Sp&auml;ter kann man dann nach Bildern suchen,
		die an einem bestimmten Standort entstanden, oder innerhalb eines bestimmten Umkreises um diesen Standort herum.<BR>
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
		
		<a name = '4_bewertung'>Bild-Bewertung</a><BR>
		Um die gezielte Suche nach Bildern mit bestimmten Qualit&auml;tsanforderungen zu erm&ouml;glichen, kann jedes Bild qualitativ bewertet werden. Die Bewertung reicht von \"1 Stern\" (ungen&uuml;gend) bis \"5 Sterne\" (sehr gut).<BR>
		Die eigentliche Vergabe der Sterne erfolgt durch anklicken des betreffenden Sterns unter dem jeweiligen Bild in der Vorschauansicht, wobei der linke Stern f&uuml;r \"1 Stern\" steht und der reche f&uuml;r \"5 Sterne\".<BR>
		Wenn ein Bild mit dem Mauszeiger &uuml;berfahren wird, wird dessen vergr&ouml;&szlig;erte Ansicht in der rechten Fensterh&auml;lfte dargestellt. Wird das Bild in der Vorschauansicht hingegen angeklickt, erh&auml;lt man eine nochmals vergr&ouml;&szlig;erte Ansicht.<BR>
		Bei der Erfassung erhalten die Bilder noch keinen Stern - also keine Qualit&auml;tsbewertung. Dennoch kann auch nach diesen Bildern gesucht werden, indem man in der Qualit&auml;tsauswahl den Punkt \"alle Bilder\" anklickt.<BR><BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '4_desc'>Beschreibungen zuweisen</a><BR>
		pic2base bietet die M&ouml;glichkeit, einzelnen Bildern, als auch Gruppen von Bildern, Beschreibungstexte zuzuweisen.<BR>
		Dies erfolgt &uuml;ber den Punkt \"Beschreibungen zuweisen\" in dem Bearbeiten-Bereich.<BR>
		Zun&auml;chst sind die gew&uuml;nschten Bilder aus der entsprechenden Kategorie auszuw&auml;hlen. Es ist also die Kategorie zu w&auml;hlen und dann &uuml;ber die rechts daneben befindlichen
		Symbole sind die Bilder anzuzeigen. Hierbei besteht die M&ouml;glichkeit, alle oder keine Bilder vorzuselektieren.<BR>
		Wenn man also wei&szlig;, da&szlig; in der betrachteten Kategorie der &uuml;berwiegende Teil der Bilder eine Beschreibung erhalten sollen, kann man die Bilder &uuml;ber das Symbol \"alle Bilder dieser Kategorie ausw&auml;hlen\" anzeigen lassen.
		Bei dieser Auswahlmethode sind alle gefundenen Bilder standardm&auml;&szlig;ig vorselektiert. Die wenigen nicht gew&uuml;nschten Bilder k&ouml;nnen nun abgew&auml;hlt werden und der Beschreibungstext kann in der rechten Fensterh&auml;lfte eingetragen werden.<BR>
		Analog kann verfahren werden, wenn nur wenige Bilder der gew&auml;hlten Kategorie beschrieben werden sollen. Dann l&auml;&szlig;t man sich die Bilder mittels des Symbols \"einzelne Bilder dieser Kategorie ausw&auml;hlen\" anzeigen und verf&auml;hrt weiter wie oben beschrieben.<BR>
		Die Beschreibung wird den selektierten Bildern zugewiesen, indem der Button \"Speichern\" angeklickt wird. Alternativ kann der Vorgang &uuml;ber den Button \"Abbrechen\" ohne &Auml;nderungen beendet werden.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '4_kat'>Kategorien zuweisen</a><BR>
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
		
		<a name = '4_del_kat'>Kategorie-Zuweisung aufheben</a><BR>
		Sollte einmal einem Bild versehentlich eine falsche Kategorie zugewiesen worden sein, hat man hier die M&ouml;glichkeit diese Zuweisung wieder r&uuml;ckg&auml;ngig zu machen.<BR>
		Dazu navigiert man zun&auml;chst im Kategoriebaum bis zu der Kategorie, in welcher ich das falsch eingeordnete Bild befindet, und l&auml;&szlig;t sich dann diese Bilder mit einem Klick auf das gr&uuml;ne H&auml;kchen anzeigen.<BR>
		In der unteren Filmstreifenansicht scrollt man nun bis zu dem betreffenden Bild und &uuml;berf&auml;ht dieses mit dem Mauszeiger. Daraufhin werden dessen Bilddetails in der rechten Fensterh&auml;lfte angezeigt.<BR>
		In der Zeile \"zugewiesene Kategorien\" hat man die M&ouml;glichkeit, mit einem Klick auf einen Kategorienamen die Zuordnung dieses Bildes zu der gew&auml;hlten Kategorie aufzuheben. Gleichzeitig wird auch die Zuordnung zu allen Unterkategorien der gew&auml;hlten Kategorie aufgehoben.<BR>
		Vor der Ausf&uuml;hrung dieser Aktion erfolgt eine Sicherheitsabfrage, ob diese Bearbeitung gewollt ist.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name = '4_qp'>Quick-Preview hochformatiger Bilder erstellen</a><BR>
		Zur schnelleren Darstellung hochformatiger Bilder m&uuml;ssen diese einmalig vor der ersten Betrachtung in pic2base gedreht und als Kopie angelegt werden. Dies geschieht normalerweise beim ersten &Uuml;berfahren des entsprechenden Bildes mit dem Mauszeiger in der Filmstreifen-Ansicht, kostet aber entsprechend Rechenzeit auf dem Server. Dieser Vorgang kann aber auch f&uuml;r alle Bilder, f&uuml;r welche es noch kein entsprechend gedrehtes Vorschau-Bild gibt, auf einmal durchgef&uuml;hrt werden.<BR>
		Hinweis: Dieser Vorgang kann - je nach Anzahl der zu drehenden Bilder - erheblichen Rechenaufwand erfordern und sollte deshalb in einer lastarmen Zeit erfolgen!<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		</p>";
		break;
		
		CASE '5':
		echo "<a name='top'></a><p style='margin:20px 150px; text-align:justify; width:400px;'>
		<b>Hilfe zum Administrations-Bereich:</b><BR><BR>
		
		Inhalt:<BR>
		<a href='#5_kat'>Kategotien</a><BR>
		<a href='#5_user'>Benutzer</a><BR>
		<a href='#5_groups'>Gruppen</a><BR>
		<a href='#5_rights'>Berechtigungen</a><BR>
		<a href='#5_ftp'>FTP-Statistik</a><BR>
		<a href='#5_log'>P2b-Log</a><BR>
		<a href='#5_md5'>md5-Check</a><BR>
		<a href='#5_hist'>Histogramme</a><BR>
		<a href='#5_md'>Meta-Daten</a><BR>
		<a href='#5_mp'>Meta-Protect</a><BR>
		<a href='#5_mv'>Meta-View</a><BR>
		<a href='#5_sw'>Software-Check</a><BR><BR>
		
		<a name = '5_kat'>Kategorien</a><BR>

		Im Arbeitsbereich Kategorien kann der Kategoriebaum gepflegt werden:
		Es k&ouml;nnen Kategorien</p>
		<ul style='margin:20px 150px; text-align:justify; width:350px;'>
		<li>erzeugt oder gel&ouml;scht werden,
		<li>umbenannt werden,
		<li>umsortiert werden.
		</ul>
		<p style='margin:20px 150px; text-align:justify; width:400px;'>
		Beim Aufruf des Kategorie-Arbeitsbereichs gelangt man zun&auml;chst in die Kategorie-Verwaltungsansicht. Von hier k&ouml;nnen die Bereiche Sortierung und Wartung aufgerufen werden.
		Weiterf&uuml;hrende Informationen stehen direkt auf den jeweiligen Seiten zur Verf&uuml;gung.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='5_user'>Benutzer</a><BR><BR>
		
		Im Arbeitsbereich Benutzer kann der Benutzerkreis f&uuml;r diese pic2base-Datenbank gepflegt werden.
		Es k&ouml;nnen Benutzer
		hinzugef&uuml;gt werden,
		gel&ouml;scht werden oder
		die Gruppenzugeh&ouml;rigkeit der Benutzer ver&auml;ndert werden.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='5_groups'>Gruppen</a><BR><BR>
		
		Im Arbeitsbereich Gruppen k&ouml;nnen Benutzergruppen und deren Zugriffsrechte definiert werden.
		Nach der Installation sind einige Gruppen bereits angelegt, wobei aber zun&auml;chst lediglich der Gruppe Admin alle Rechte zugeteilt wurden. Die Rechte der anderen Gruppen k&ouml;nnen entsprechend der Erfordernisse im jeweiligen Anwendungsfall individuell festgelegt werden.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='5_rights'>Berechtigungen</a><BR><BR>
		
		Im Arbeitsbereich Berechtigungen kann man sich informieren, welche Aktionen in pic2base durch Zugriffsbeschr&auml;nkungen reglementiert werden k&ouml;nnen.
		Die eigentliche Rechtevergabe erfolgt &uuml;ber die Erteilung der Gruppen- und Benutzerrechte.<BR>
		Im ersten Schritt werden Gruppen angelegt und diesen werden die gew&uuml;nschten Rechte zugeteilt.<BR>
		Wenn ein neuer Benutzer angelegt und einer bestehenden Gruppe zugeteilt wird, erbt er automatisch die Rechte dieser Gruppe.<BR>
		Diese geerbten Rechte k&ouml;nnen jedoch f&uuml;r jeden einzelnen Benutzer noch individuell angepa&szlig;t werden.<BR>
		Dies geschieht dann &uuml;ber die Vergabe der entsprechenden Benutzerrechte.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='5_ftp'>FTP-Statistik</a><BR><BR>
		
		&uuml;ber den Link FTP-Statistik gelangt man zu einem Tool, mit dessen Hilfe der Zugriff via FTP auf diesen pic2base-Server kontrolliert werden kann. Hier kann in die Protokolldatei eingesehen werden und der verursachte Traffic ausgelesen werden.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='5_log'>P2b-Log</a><BR><BR>
		
		In der p2b-Logdatei werden Aktivit&auml;ten protokolliert, die dem Administrator Auskunft &uuml;ber die Zugriffe auf die pic2base-Datenbank geben.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='5_md5'>md5-Check</a><BR><BR>
		
		Mit Hilfe des md5-Checks kann kontrolliert werden, ob zu jeder Bilddatei eine Pr&uuml;fsumme erzeugt wurde. Diese kann sp&auml;ter zur Identifikation von Duplikaten herangezogen werden.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='5_hist'>Histogramme</a><BR><BR>
		
		Der Arbeitsbereich Histogramme bietet die M&ouml;glichkeit zu pr&uuml;fen, ob zu jedem in der Datenbank befindlichen Bild die entsprechenden Histogramme (R,G,B,Grey) existieren. Wenn nicht, werden diese &uuml;ber den Aufruf des Men&uuml;punktes Histogramme erzeugt. Normalerweise ist diese Kontrolle nicht erforderlich, da die Histogramme von pic2base (ab Version 0.40) bereits bei der Bild-Erfassung automatisch erzeugt werden bzw. beim Aufruf der Bilddetail-Infoseite diese Kontrolle nochmals erfolgt. Existieren die Histogramme f&uuml;r das betrachtete Bild nicht (z.B. wenn Bilder mit einer &auml;lteren Version von pic2base erfa&#223;t wurden), w&uuml;rden sie nun nachtr&auml;glich erzeugt werden. Dies erfordert jedoch zus&auml;tzlichen Rechenaufwand, der die Darstellung der Detail-Informationen verz&ouml;gert. Mit diesem Werkzeug ist der Administrator in der Lage, die Histogramm-Erzeugung in einer lastarmen Zeit durchzuf&uuml;hren.
		HINWEIS: Dieser Vorgang kann je nach Rechner-Leistung und Bildbestand erhebliche Zeit beanspruchen!<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='5_md'>Meta-Daten</a><BR><BR>
		
		Im Arbeitsbereich Meta-Daten hat der Administrator die M&ouml;glichkeit zu kontrollieren, ob aus allen Fotos die vorhandenen Meta-Daten ausgelesen und in die Datenbank &uuml;bertragen wurden.<BR>
		HINWEIS: Hier treffen die selben Einschr&auml;nkungen wie beim Punkt 'Histogramme' zu.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='5_mp'>Meta-Protect</a></a><BR><BR>
		
		Im Arbeitsbereich Meta-Protect kann eingestellt werden, welche Meta-Daten von berechtigten Usern nachtr&auml;glich manuell modifiziert werden d&uuml;rfen.
		Diese Einstellungen sollten mit gr&ouml;&#223;ter Sorgfalt erfolgen.
		Bereits mit den Grundeinstellungen von pic2base (alle Meta-Datenfelder sind abgew&auml;hlt) werden die folgenden Meta-Datenfelder trotzdem automatisch durch pic2base aktuell gehalten:</p>
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
		
		<a name='5_mv'>Meta-View</a></a><BR><BR>
		
		Im Arbeitsbereich Meta-View kann eingestellt werden, welche Meta-Daten in der Kompakt-Ansicht der Bilddetails angezeigt werden.<BR>
		Sollte es sich also f&uuml;r zweckm&auml;&szlig;ig erweisen, f&uuml;r bestimmte Recherche-Arbeiten immer sofort zu wissen, mit welcher Brennweite das Bild aufgenommen wurde,
		kann man festlegen, da&szlig; in der Kompaktansicht das Feld \"FocalLength\" oder \"FocalLengthIn35mmFormat\" angezeigt wird.<BR>
		Damit spart man sich u.a. den st&auml;ndigen Wechsel in die vollst&auml;ndige Detailansicht.<BR>
		In der vollst&auml;ndigen Ansicht werden hingegen immer alle verf&uuml;gbaren Informationen zu dem betreffenden Bild angezeigt.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		
		<a name='5_sw'>Software-Check</a></a><BR><BR>
		
		Mit dem Software-Check kontrolliert pic2base, ob alle ben&ouml;tigten externen Hilfsprogramme auf dem Server vorhanden sind. In der Regel wird dieses Tool nur w&auml;hrend der Erstinstallation von pic2base oder nach Konfigurationsarbeiten (Software-Installationen / Deinstallationen) auf dem Server ben&ouml;tigt.<BR>
		Ab Version 0.41 startet das Tool automatisch beim ersten Start von pic2base nach der Installation und dann so lange, bis mindestens ein Bild in die Datenbank gestellt wurde.<BR>
		Dieser Check kann je nach Rechner-Ausstattung einige Zeit in Anspruch nehmen, stellt aber sicher, zuverl&auml;ssige Informationen &uuml;ber die notwendigen Softwarekomponenten zu erhalten.<BR>
		<a href='#top'>Zum Seitenanfang</a><BR><BR>
		</p>";
		break;
		
		CASE '6':
		echo "<p style='margin:20px 150px; text-align:justify; width:400px;'>
		<b>Hilfe zu den pers&ouml;nlichen Einstellungen</b><BR><BR>
		Auf dieser Seite haben Sie die M&ouml;glichkeit, pers&ouml;nliche Einstellungen anzupassen sowie Passworte zu &auml;ndern.<BR>
		Je nach erteilter Berechtigung, k&ouml;nnen Sie dies f&uuml;r Ihr eigenes Konto oder aber auch f&uuml;r die Konten der anderen Benutzer tun.<BR><BR>
		Weitere Hinweise finden Sie auf der Einstellungen-Seite.
		</p>";
		break;
	}
	echo "
	</div>
	<br style='clear:both;' />

	<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>

</div>";

mysql_close($conn);
?>
</DIV>
</CENTER>
</BODY>
</HTML>