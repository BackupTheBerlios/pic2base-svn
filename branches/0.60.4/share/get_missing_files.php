<?php
unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
	$benutzername = $c_username;
}

include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';

//bei 50000 Datensaetzen waren 250M erforderlich, daher wird hier vorsorglich auf 300M erhoeht: 
if(ini_get('memory_limit') < 300)
{
	ini_set('memory_limit', '300M');
}

//Ermittlung der Anzahl fehlender Dateien im Rahmen der DB-Wartung (Vorschau, Hist, Mono):
//temporaer genutzte Tabelle wird geleert:
$result22 = mysql_query("TRUNCATE `ICE_V_pic_kat_dubls`");
echo mysql_error();
//#########################	
//es wird fuer alle Eintraege in der tabelle pictures geprueft, ob die Vorschaubilder
//FileNameHQ, FileNameV, FileNameHist, FileNameHist_r, FileNameHist_g, FileNameHost_b und FileNameMono vorhanden sind. 
//Wenn nicht, werden die fehlenden Bilder ermittelt
//#########################################
//$start1 = microtime();	 //Beginn der Laufzeitmessung:
//#########################################

//In der Tabelle pictures werden Felder, in denen eigentlich ein Dateiname stehen muesste, mit dem vorgesehenen Eintrag belegt, damit es weiter unten zu einer Differenbildung kommen kann:
$result23 = mysql_query("UPDATE $table2 SET FileNameHQ = CONCAT(pic_id,'_hq.jpg') WHERE FileNameHQ = ''");
$result24 = mysql_query("UPDATE $table2 SET FileNameV = CONCAT(pic_id,'_v.jpg') WHERE FileNameV = ''");
$result25 = mysql_query("UPDATE $table2 SET FileNameHist = CONCAT(pic_id,'_hist.gif') WHERE FileNameHist = ''");
$result26 = mysql_query("UPDATE $table2 SET FileNameHist_r = CONCAT(pic_id,'_hist_r.gif') WHERE FileNameHist_r = ''");
$result27 = mysql_query("UPDATE $table2 SET FileNameHist_g = CONCAT(pic_id,'_hist_g.gif') WHERE FileNameHist_g = ''");
$result28 = mysql_query("UPDATE $table2 SET FileNameHist_b = CONCAT(pic_id,'_hist_b.gif') WHERE FileNameHist_b = ''");
$result29 = mysql_query("UPDATE $table2 SET FileNameMono = CONCAT(pic_id,'_mono.jpg') WHERE FileNameMono = ''");
echo mysql_error();
$result30 = mysql_query( "SELECT * FROM $table2");
echo mysql_error();

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

ksort($hq_files_soll);
ksort($v_files_soll);
ksort($hist_files_soll);	//print_r($hist_files_soll);
ksort($hist_r_files_soll);
ksort($hist_g_files_soll);
ksort($hist_b_files_soll);	
ksort($mono_files_soll);	//print_r($mono_files_soll);
/*
//#########################################
$end1 = microtime();
list($start1msec, $start1sec) = explode(" ",$start1);
list($end1msec, $end1sec) = explode(" ",$end1);
$runtime1 = round(($end1sec + $end1msec) - ($start1sec + $start1msec),4);
$meldung_0 .= "Lesen / sortieren der Soll-Dateien nach: <b>".$runtime1."</b> Sek. beendet.<br />";
//#########################################
*/
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
/*
//#########################################
$end2 = microtime();
list($start1msec, $start1sec) = explode(" ",$start1);
list($end2msec, $end2sec) = explode(" ",$end2);
$runtime2 = round(($end2sec + $end2msec) - ($start1sec + $start1msec),4);
$meldung_0 .= "Lesen / sortieren der Ist-Dateien nach: <b>".$runtime2."</b> Sek. beendet.<br />";
//#########################################
*/
//Ermittlung der Differenzen zwischen Soll und Ist:
	
$hq_files_diff = array_diff($hq_files_soll, $hq_files_ist);
$v_files_diff = array_diff($v_files_soll, $v_files_ist);
$hist_files_diff = array_diff($hist_files_soll, $hist_files_ist);
$hist_r_files_diff = array_diff($hist_r_files_soll, $hist_r_files_ist);
$hist_g_files_diff = array_diff($hist_g_files_soll, $hist_g_files_ist);
$hist_b_files_diff = array_diff($hist_b_files_soll, $hist_b_files_ist);
$mono_files_diff = array_diff($mono_files_soll, $mono_files_ist);

/*
function hist_array_diff($a, $b) 
{
    $map = $out = array();
    foreach($a as $val) $map[$val] = 1;
    foreach($b as $val) if(isset($map[$val])) $map[$val] = 0;
    foreach($map as $val => $ok) if($ok) $out[] = $val;
    return $out;
}
$a = $hist_files_soll;
$b = $hist_files_ist;
//echo "fehlende hist-files nach neuer Berechnung:<BR>";
$hist_files_diff = hist_array_diff($a, $b);
print_r($hist_files_diff);
//echo "<BR><BR>";

function hist_r_array_diff($c, $d) 
{
    $map = $out = array();
    foreach($c as $val) $map[$val] = 1;
    foreach($d as $val) if(isset($map[$val])) $map[$val] = 0;
    foreach($map as $val => $ok) if($ok) $out[] = $val;
    return $out;
}
$c = $hist_r_files_soll;
$d = $hist_r_files_ist;
//echo "fehlende hist-R-files nach neuer Berechnung:<BR>";
$hist_r_files_diff = hist_r_array_diff($c, $d);
//print_r($hist_r_files_diff);
//echo "<BR><BR>";


function hist_g_array_diff($e, $f) 
{
    $map = $out = array();
    foreach($e as $val) $map[$val] = 1;
    foreach($f as $val) if(isset($map[$val])) $map[$val] = 0;
    foreach($map as $val => $ok) if($ok) $out[] = $val;
    return $out;
}
$e = $hist_g_files_soll;
$f = $hist_g_files_ist;
//echo "fehlende hist-G-files nach neuer Berechnung:<BR>";
$hist_g_files_diff = hist_g_array_diff($e, $f);
//print_r($hist_g_files_diff);
//echo "<BR><BR>";


function hist_b_array_diff($g, $h) 
{
    $map = $out = array();
    foreach($g as $val) $map[$val] = 1;
    foreach($h as $val) if(isset($map[$val])) $map[$val] = 0;
    foreach($map as $val => $ok) if($ok) $out[] = $val;
    return $out;
}
$g = $hist_b_files_soll;
$h = $hist_b_files_ist;
//echo "fehlende hist-B-files nach neuer Berechnung:<BR>";
$hist_b_files_diff = hist_b_array_diff($g, $h);
//print_r($hist_b_files_diff);
//echo "<BR><BR>";


function mono_array_diff($i, $j) 
{
    $map = $out = array();
    foreach($i as $val) $map[$val] = 1;
    foreach($j as $val) if(isset($map[$val])) $map[$val] = 0;
    foreach($map as $val => $ok) if($ok) $out[] = $val;
    return $out;
}
$i = $mono_files_soll;
$j = $mono_files_ist;
//echo "fehlende mono-files nach neuer Berechnung:<BR>";
$mono_files_diff = mono_array_diff($i, $j);
//print_r($mono_files_diff);
//echo "<BR><BR>";


function hq_array_diff($k, $l) 
{
    $map = $out = array();
    foreach($k as $val) $map[$val] = 1;
    foreach($l as $val) if(isset($map[$val])) $map[$val] = 0;
    foreach($map as $val => $ok) if($ok) $out[] = $val;
    return $out;
}
$k = $hq_files_soll;
$l = $hq_files_ist;
//echo "fehlende hq-files nach neuer Berechnung:<BR>";
$hq_files_diff = hq_array_diff($k, $l);
//print_r($hq_files_diff);
//echo "<BR><BR>";


function v_array_diff($m, $n) 
{
    $map = $out = array();
    foreach($m as $val) $map[$val] = 1;
    foreach($n as $val) if(isset($map[$val])) $map[$val] = 0;
    foreach($map as $val => $ok) if($ok) $out[] = $val;
    return $out;
}
$m = $v_files_soll;
$n = $v_files_ist;
//echo "fehlende v-files nach neuer Berechnung:<BR>";
$v_files_diff = v_array_diff($m, $n);
//print_r($v_files_diff);
//echo "<BR><BR>";
*/
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
/*
//#########################################
$end3 = microtime();
list($start1msec, $start1sec) = explode(" ",$start1);
list($end3msec, $end3sec) = explode(" ",$end3);
$runtime3 = round(($end3sec + $end3msec) - ($start1sec + $start1msec),4);
$meldung_0 .= "Vergleich des Soll-Ist-Bestandes nach <b>".$runtime3."</b> Sek. beendet.<br />";
//#########################################
*/
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
/*
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
*/
/*
//#########################################
$end4 = microtime();
list($start1msec, $start1sec) = explode(" ",$start1);
list($end4msec, $end4sec) = explode(" ",$end4);
$runtime4 = round(($end4sec + $end4msec) - ($start1sec + $start1msec),4);
$meldung_0 .= "Aufgabenliste nach <b>".$runtime4."</b> Sekunden zusammengestellt.<br />";
//#########################################
ob_start();
*/
//es werden nur die pic_id's benoetigt, die Dateinamen werden aus den Arrays entfernt:
$v_files = array();
$hq_files = array();
foreach($v_files_diff AS $key => $value)
{
	$v_files[] = $key;
}

foreach($hq_files_diff AS $key => $value)
{
	$hq_files[] = $key;
}

$obj = new stdClass();
$obj->anzahl = $i_neu;									//Anzahl der neu zu erstellenden Dateien
$obj->hist_mono_files_array = $hist_mono_files_diff;	//Array der fehlenden mono- und Hist-Dateien
$obj->hq_files_array = $hq_files;					//Array der fehlenden HQ-Bilder
$obj->v_files_array = $v_files;					//Array der fehlenden Thumbs
$output = json_encode($obj);
echo $output;
?>