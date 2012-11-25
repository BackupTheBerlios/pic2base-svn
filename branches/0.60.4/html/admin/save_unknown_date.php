<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../../index.php');
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$exiftool = buildExiftoolCommand($sr);


/*
##############################################################################################################################
Mit diesem Skript koennen einer grossen Anzahl von Bildern ein fiktives Datum zugewiesen werden.
Dazu muss der ID-Bereich bekannt sein, in dem diese Bilder liegen (z.B > 34889) und das gewuenschte Datum muss angegeben werden.
wenn sehr viele Bilder ohne Datum vorliegen, empfiehlt es sich, jeweils etwa 100 Stück auf ein fiktives Datum zu legen.
Hierzu ist die Variable $limit zu belegen.
Als fiktives Datum können z.B. Angaben gewaehlt werden, in denen mit hinreichender Sicherheit keine Fotos entstanden.
Allerdings ist auch der Gueltigkeitsbereich des Datums in PHP zu beruecksichtigen! http://www.php.net/manual/de/function.date.php
##############################################################################################################################
*/

//Vorbelegung der erforderlichen Variablen:

$limit = 100;										//Anzahl der Bilder, denen das fikt. Datum zugewiesen wird
$first_fikt_date = mktime(0, 0, 0, 1, 14, 1902);	//erstes fikt. Datum: h - m - s - M - T - J
$mind_id = 41988;									//es werden nur Bilder beruecksichtigt, deren ID > $mind_id ist

//zuerst wird die Anzahl der Bilder ermittelt, die kein Erstellungsdatum haben und deren ID groesser als die o.a. ID ist:
$result1 = mysql_query("SELECT * FROM $table2 
WHERE DateTimeOriginal = '0000-00-00 00:00:00' 
AND pic_id > '$mind_id'");
$num1 = mysql_num_rows($result1);
echo "Bilder ohne Aufnahmedatum: ".$num1."<BR>";

//dann wird die Anzahl der Korrekturlaeufe ermittelt:
$x = floor($num1/$limit)+1;
echo "Es sind ".$x." Korrekturl&auml;ufe notwendig<BR>";

//Es wird ermittelt, welche Datums :-)) zugewiesen werden (jeweils $limit Bilder pro Tag):
echo "Die folgenden Datumsangaben werden vergeben:<BR>";
FOR($n='0'; $n<$x; $n++)
{
	$next_date = date('Y-m-d H:i:s', $first_fikt_date+$n*86400);
	echo $next_date."<BR>";
	$dto = date('Y:m:d H:i:s', $first_fikt_date+$n*86400);
	//echo $dto."<BR>";
	$result2 = mysql_query("SELECT * FROM $table2 
	WHERE DateTimeOriginal = '0000-00-00 00:00:00' 
	AND pic_id > '$mind_id'
	LIMIT $limit");
	$num2 = mysql_num_rows($result2);
	//echo "Bilder ohne Aufnahmedatum im ".$n."-ten Korrekturlauf: ".$num2."<BR>";
	
	//echo "Diese haben die laufenden Nummern:<BR>";
	FOR($m=0; $m<$num2; $m++)
	{
		@$pic_id = mysql_result($result1, ($m + $n*100), 'pic_id');
		IF ($pic_id != '')
		{
			//echo $pic_id."<BR>";
			/*
			 * Die eigentliche Datums-Vergabe beginnt.
			 * Gleichzeitig wird die Bildbeschreibung um den Vermerk ergaenzt, dass es sich um ein fiktives Aufnahmedatum handelt.
			 */
			$result3 = mysql_query("SELECT FileName, Caption_Abstract FROM $table2 WHERE pic_id = '$pic_id'");
			// $fn ist der interne Dateiname
			$fn = mysql_result($result3,0,'FileName');
			$fn = $pic_path."/".$fn;									//echo $fn."<BR>";			
			//$FN ist der Original-Dateiname
			$FN = $pic_path."/".restoreOriFilename($pic_id, $sr);		//echo $FN."<BR>";
			$ca = mysql_result($result3,0,'Caption_Abstract');			//Caption_Abstract - Bildbeschreibung
			$description = ", Das Aufnahmedatum ist ein fiktives Datum und wurde maschinell eingefuegt, da das Bild selbst keine Meta-Daten mitbrachte.";
			$ca_neu = $ca.$description;									//echo $ca_neu."<BR>";
			 
			//ohne die folgenden Anweisungen kann ein Trockenlauf erfolgen: ##########################
			$result4 = mysql_query( "UPDATE $table2 SET Caption_Abstract = \"$ca_neu\", DateTimeOriginal = '$next_date' WHERE pic_id = '$pic_id'");
//			$desc = htmlentities($ca_neu);
			$desc = $ca_neu;
			//Aenderungen in Original-Datei und jpg-Datei speichern:
			shell_exec($exiftool." -IPTC:Caption-Abstract=\"$desc\" ".$FN." -overwrite_original 
			-execute -EXIF:DateTimeOriginal='$dto' ".$FN." -overwrite_original
			-execute -IPTC:Caption-Abstract=\"$desc\" ".$fn." -overwrite_original 
			-execute -EXIF:DateTimeOriginal='$dto' ".$fn." -overwrite_original");
			//Ende des auszuklammernden Blocks #######################################################
			
		}
	}
}

echo "<FONT COLOR='red'>OK! - Fertig.</FONT>";

?>