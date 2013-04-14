<?php

include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';

//bei 50000 Datensaetzen waren 250M erforderlich, daher wird hier vorsorglich auf 350M erhoeht: 
if(ini_get('memory_limit') < 350)
{
	ini_set('memory_limit', '350M');
}
ini_set('max_execution_time', '60');

//Ermittlung der Anzahl fehlender Dateien im Rahmen der DB-Wartung (Originale, Vorschau, Hist, Mono):
//temporaer genutzte Tabelle wird geleert:
$result21 = mysql_query("TRUNCATE `ICE_V_pic_kat_dubls`");
echo mysql_error();
//#########################	
//es wird fuer alle Eintraege in der tabelle pictures geprueft, ob die Vorschaubilder
//FileName, FileNameHQ, FileNameV, FileNameHist, FileNameHist_r, FileNameHist_g, FileNameHost_b und FileNameMono vorhanden sind. 
//Wenn nicht, werden die fehlenden Bilder ermittelt

//In der Tabelle pictures werden Felder, in denen eigentlich ein Dateiname stehen muesste, mit dem vorgesehenen Eintrag belegt, damit es weiter unten zu einer Differenbildung kommen kann:
$result22 = mysql_query("UPDATE $table2 SET FileName = CONCAT(pic_id,'.jpg') WHERE FileName = ''");
$result23 = mysql_query("UPDATE $table2 SET FileNameHQ = CONCAT(pic_id,'_hq.jpg') WHERE FileNameHQ = ''");
$result24 = mysql_query("UPDATE $table2 SET FileNameV = CONCAT(pic_id,'_v.jpg') WHERE FileNameV = ''");
$result25 = mysql_query("UPDATE $table2 SET FileNameHist = CONCAT(pic_id,'_hist.gif') WHERE FileNameHist = ''");
$result26 = mysql_query("UPDATE $table2 SET FileNameHist_r = CONCAT(pic_id,'_hist_r.gif') WHERE FileNameHist_r = ''");
$result27 = mysql_query("UPDATE $table2 SET FileNameHist_g = CONCAT(pic_id,'_hist_g.gif') WHERE FileNameHist_g = ''");
$result28 = mysql_query("UPDATE $table2 SET FileNameHist_b = CONCAT(pic_id,'_hist_b.gif') WHERE FileNameHist_b = ''");
$result29 = mysql_query("UPDATE $table2 SET FileNameMono = CONCAT(pic_id,'_mono.jpg') WHERE FileNameMono = ''");
//echo mysql_error();
$result30 = mysql_query( "SELECT * FROM $table2");
//echo mysql_error();

$ori_files_soll = array();
$hq_files_soll = array();
$v_files_soll = array();
$hist_files_soll = array();
$hist_r_files_soll = array();
$hist_g_files_soll = array();
$hist_b_files_soll = array();
$mono_files_soll = array();

WHILE($row = mysql_fetch_array($result30))
{
	foreach($row AS $key => $value)
	{
//		echo "Element: ".$row[0].", Key: ".$key." - Wert: ".$value."<BR>";
		switch(trim($key))
		{
			CASE 'FileName':
				$ori_files_soll[$row[0]] = $value;	
			break;
			
			CASE 'FileNameHQ':
				$hq_files_soll[$row[0]] = $value;	
			break;
			
			CASE 'FileNameV':
				$v_files_soll[$row[0]] = $value;
			break;
			
			CASE 'FileNameHist':
				$hist_files_soll[$row[0]] = $value;
			break;
			
			CASE 'FileNameHist_r':
				$hist_r_files_soll[$row[0]] = $value;
			break;
			
			CASE 'FileNameHist_g':
				$hist_g_files_soll[$row[0]] = $value;
			break;
			
			CASE 'FileNameHist_b':
				$hist_b_files_soll[$row[0]] = $value;
			break;
			
			CASE 'FileNameMono':
				$mono_files_soll[$row[0]] = $value;
			break;
		}
	}
}

ksort($ori_files_soll);
ksort($hq_files_soll);
ksort($v_files_soll);
ksort($hist_files_soll);	//print_r($hist_files_soll);
ksort($hist_r_files_soll);
ksort($hist_g_files_soll);
ksort($hist_b_files_soll);	
ksort($mono_files_soll);	//print_r($mono_files_soll);

$erledigt = 0;	//Anzahl der erledigten Datei-Wiederherstellungen
$n_hq = 0;
$n_v = 0;
$mf = 0;
//echo $kml_dir;
//Ermittlung der Dateien, die in den Bild-Ordnern liegen:
$a[] = exec("ls ".$pic_hq_path."/", $hq_files_ist);	//print_r($retval2);
$b[] = exec("ls ".$pic_thumbs_path."/", $v_files_ist);
$c[] = exec("ls ".$hist_path."/", $hist_files_ist);
$d[] = exec("ls ".$monochrome_path."/", $mono_files_ist);
$e[] = exec("ls ".$pic_path."/", $ori_files_ist);

//#####################################	
ksort($ori_files_ist);
ksort($hq_files_ist);		
ksort($v_files_ist);
sort($hist_files_ist);	//print_r($hist_files_ist);
//Das Hist-Array des Ist-Bestandes ist aus Performance-Gruenden in die 4 Bestandteile (grau / rot / gruen / blau) zu zerlegen,
//ansonsten gibt es bei der folgenden Differenzbildung erhebliche Verzoegerungen:

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
//Ermittlung der Differenzen zwischen Soll und Ist:
	
$ori_files_diff = array_diff($ori_files_soll, $ori_files_ist);
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
unset($ori_files_ist);

unset($hist_files_soll);
unset($hist_r_files_soll);
unset($hist_g_files_soll);
unset($hist_b_files_soll);
unset($hq_files_soll);
unset($v_files_soll);
unset($ori_files_soll);

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
$i_neu = count($ori_files_diff) + count($hq_files_diff) + count($v_files_diff) + count($hist_files_diff) + count($hist_r_files_diff) + count($hist_g_files_diff) + count($hist_b_files_diff) + count($mono_files_diff);

//es werden nur die pic_id's benoetigt, die Dateinamen werden aus den Arrays entfernt:
$v_files = array();
$hq_files = array();
$ori_files = array();
foreach($v_files_diff AS $key => $value)
{
	$v_files[] = $key;
}

foreach($hq_files_diff AS $key => $value)
{
	$hq_files[] = $key;
}

foreach($ori_files_diff AS $key => $value)
{
	$ori_files[] = $key;
}

$obj = new stdClass();
$obj->anzahl = $i_neu;									//Anzahl der neu zu erstellenden Dateien
$obj->hist_mono_files_array = $hist_mono_files_diff;	//Array der fehlenden mono- und Hist-Dateien
$obj->ori_files_array = $ori_files;						//Array der fehlenden Original-Bilder
$obj->hq_files_array = $hq_files;						//Array der fehlenden HQ-Bilder
$obj->v_files_array = $v_files;							//Array der fehlenden Thumbs
$output = json_encode($obj);
echo $output;
?>