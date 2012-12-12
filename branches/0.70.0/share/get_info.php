<?php
IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
  	header('Location: ../../index.php');
}

//Info-Bereitstellung
SWITCH($id)
{
	CASE '1':
	echo "Klicken Sie hier, wenn Sie sich die Monats&uuml;bersicht f&uuml;r ".$zeit." anzeigen lassen wollen.";
	break;
	
	CASE '2':
	echo "Klicken Sie hier, wenn Sie sich alle Bilder vom ".$zeit." anzeigen lassen wollen.";
	break;
	
	CASE '3':
	echo "Klicken Sie hier, wenn Sie sich alle Bilder ohne zeitliche Zuordnung anzeigen lassen wollen.";
	break;
	
	CASE '4':
	echo "Klicken Sie hier, wenn Sie die Tages-Auswahl f&uuml;r den ".$zeit." &ouml;ffnen wollen.";
	break;
	
	CASE '5':
	echo "Klicken Sie hier, wenn Sie die Tages-Auswahl f&uuml;r den ".$zeit." schlie&szlig;en wollen.";
	break;
	
	CASE '6':
	echo "Klicken Sie hier, wenn Sie sich alle Bilder des Jahres ".$zeit." ansehen wollen.";
	break;
	
	CASE '7':
	echo "Klicken Sie hier, wenn Sie die Monats&uuml;bersicht f&uuml;r ".$zeit." schlie&szlig;en wollen.";
	break;
	
	CASE '9':
	echo "
	<p id='elf' style='background-color:white; padding: 5px; width: 365px; margin-top: 4px; margin-left: 10px;'><b>Hinweis zur Anzeige der Bilder:</b><BR><BR>Wenn Sie ein Bild in der Filmstreifen-Ansicht mit der Maus &uuml;berfahren, erhalten Sie hier in der rechten Spalte einige Details zu diesem Bild angezeigt.<BR>Klicken Sie auf dieses Bild in dem Filmstreifen, erhalten Sie eine Vorschau in mittlerer Qualit&auml;t.<BR>Klicken Sie hingegen auf das Bild in der Detail-Ansicht, erhalten Sie eine Darstellung in Original-Qualit&auml;t.</p>
	<p id='elf' style='background-color:white; padding: 5px; width: 365px; margin-top: 4px; margin-left: 10px;'><b>Hilfe zu den Suchm&ouml;glichkeiten:</b><BR><BR>
	Ausf&uuml;hrliche Hilfe zu den Suchm&ouml;glichkeiten finden Sie &uuml;ber den Button \"Hilfe\" in der Navigationsleiste oder direkt <a href='../help/help1.php?page=2'>hier</a>.
	</p>
	<p id='elf' style='background-color:white; padding: 5px; width: 365px; margin-top: 4px; margin-left: 10px;'><b>Hinweis zur eingestellten Bewertung:</b><BR><BR>
	In der Kopfzeile sehen Sie die eingestellte Bewertung durch 5 Sterne symbolosiert. Diese haben folgende Bedeutung:<BR>
	Der linke Stern symbolisiert die niedrigste Benotung (Note \"ungen&uuml;gend\"), entsprechend der rechte Stern die h&ouml;chste (Note \"sehr gut\").<BR>Wenn als Bewerungskriterium \"gute Bilder\" gew&auml;hlt wurde, wird der 4. Stern (von links gez&auml;hlt) gelb dargestellt, alle anderen grau.<BR>Wird hingegen als Bewertungskriterium \"befriedigende oder schlechtere Bilder\" gew&auml;hl, werden die linken 3 Sterne gelb dargestellt (entsprechend der Noten \"ungen&uuml;gend\", \"gen&uuml;gend\" und \"befriedigend\").<BR>Wird &uuml;ber alle Bilder recherchiert, werden <u>alle</u> Sterne gelb dargestellt.
	</p>";
	break;
}
?>