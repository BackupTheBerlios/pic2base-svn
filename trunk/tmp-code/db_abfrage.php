<?php
include '../share/global_config.php';
include '../share/db_connect1.php';
//$res1 = mysql_query("SELECT pictures.City, pictures.GPSLongitude, geo_locations.City, geo_locations.GPSLongitude FROM `pictures` RIGHT JOIN `geo_locations` ON (pictures.GPSLongitude > '10' AND pictures.GPSLongitude < '10.1' AND geo_locations.GPSLongitude > '10' AND geo_locations.GPSLongitude < '10.1')");
echo "Test-Skript<BR>";
/*
//existiert die Tabelle geo_locations?
$res = mysql_query("show tables LIKE 'geo_locations'");

IF(mysql_num_rows($res) == 1)
{
	//echo "Tabelle vorhanden";
	$res1 = mysql_query("select City, GPSLongitude, GPSLatitude
						from 	(SELECT City, GPSLongitude, GPSLatitude
			FROM pictures as P2
			union all
			select City, GPSLongitude, GPSLatitude from geo_locations) 
	 		as X_union
			where GPSLongitude between 9 and 11
			and GPSLatitude between 51.7 and 51.8
			Order by GPSLongitude DESC
			LIMIT 200");
	
	echo mysql_error();
	$num1 = mysql_num_rows($res1);
	echo $num1." Fundstellen<BR><BR>";
	FOR($i1='0'; $i1<$num1; $i1++)
	{
		$City = mysql_result($res1, $i1, 'City');
		$gpslat = mysql_result($res1, $i1, 'GPSLatitude');
		$gpslong = mysql_result($res1, $i1, 'GPSLongitude');
		echo $i1." - ".$City." - ".$gpslat." - ".$gpslong."<BR>";
	}
}
ELSE
{
	echo "Tabelle nicht da!!!";
}
*/

//alle Dateien eines Verzeichnisses auflisten:
//shell_exec(`ls /home/klaus/*.zip > /home/klaus/files.txt`);
shell_exec("ls -1 ".$pic_hq_path."/ > /opt/lampp/htdocs/pic2base/tmp/hq_files.txt");
shell_exec("ls -1 ".$pic_thumbs_path."/ > /opt/lampp/htdocs/pic2base/tmp/thumbs_files.txt");

$res1 = mysql_query("SELECT * FROM pictures");

WHILE($row = mysql_fetch_array($res1))
{
	//echo "FileName-HQ: ".$row[3]."<BR>";
	$hq_files_soll[] = $row[3];
	sort($hq_files_soll);
	//echo "FileName-V: ".$row[4]."<BR>";
	$v_files_soll[] = $row[4];
	sort($v_files_soll);
	//echo "FileName-hist: ".$row[5]."<BR>";
	$hist_files_soll[] = $row[5];
	sort($hist_files_soll);
	//echo "FileName-hist-R: ".$row[6]."<BR>";
	$hist_r_files_soll[] = $row[6];
	sort($hist_r_files_soll);
	//echo "FileName-hist-G: ".$row[7]."<BR>";
	$hist_g_files_soll[] = $row[7];
	sort($hist_g_files_soll);
	//echo "FileName-hist-B: ".$row[8]."<BR>";
	$hist_b_files_soll[] = $row[8];
	sort($hist_b_files_soll);
	//echo "FileName-mono: ".$row[9]."<BR>";
	$mono_files_soll[] = $row[9];
	sort($mono_files_soll);
}
echo mysql_error();
/*
echo "HQ-Soll-Bestand: ";
print_r($hq_files_soll);
echo "<BR><BR>";
echo "V-Soll-Bestand: ";
print_r($v_files_soll);
echo "<BR><BR>";
*/


$hq_files_ist = file("/opt/lampp/htdocs/pic2base/tmp/hq_files.txt");
sort($hq_files_ist);
//bei jedem Element dieses Arrays ist der Zeilenumbruch zu entfernen:
$hq_files_ist_neu = array();
foreach($hq_files_ist AS $HFI)
{
	$hfi = trim($HFI);
	$hq_files_ist_neu[] = $hfi;
}
/*
echo "HQ-Ist-Bestand: ";
print_r($hq_files_ist_neu);
echo "<BR><BR>";
*/
$v_files_ist = file("/opt/lampp/htdocs/pic2base/tmp/thumbs_files.txt");
sort($v_files_ist);
//bei jedem Element dieses Arrays ist der Zeilenumbruch zu entfernen:
$v_files_ist_neu = array();
foreach($v_files_ist AS $VFI)
{
	$vfi = trim($VFI);
	$v_files_ist_neu[] = $vfi;
}
/*
echo "V-Ist-Bestand: ";
print_r($v_files_ist_neu);
echo "<BR><BR>";
*/
$hq_files_diff = array_diff($hq_files_soll, $hq_files_ist_neu);
IF(count($hq_files_diff) > 0)
{
	echo "<BR><BR>Fehlende hq-Vorschaubilder:<BR>";
	//print_r($hq_files_diff);
	foreach($hq_files_diff AS $hqfd)
	{
		echo $hqfd."<BR>";
	}
}
ELSE
{
	echo "<BR><BR>Es fehlen keine HQ-Vorschaubilder<BR>";
}

$v_files_diff = array_diff($v_files_soll, $v_files_ist_neu);
IF(count($v_files_diff) > 0)
{
	echo "<BR><BR>Fehlende v-Vorschaubilder:<BR>";
	//print_r($v_files_diff);
	foreach($v_files_diff AS $vfd)
	{
		echo $vfd."<BR>";
	}
}
ELSE
{
	echo "<BR><BR>Es fehlen keine V-Vorschaubilder<BR>";
}

echo mysql_error();

?>



