<?php
if ( array_key_exists('user',$_GET) )
{
	$uname = $_GET['user'];
}
echo "Georeferenzierung wird abgebrochen...<BR>";
//echo "&Uuml;bergebener Username: ".$uname."<BR>";
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
//
//Ermittlung aller Bilder des Users $uname, fuer die gilt:
//In der Tabelle locations sind Datensaetze mit der Ortsbezeichnung "Ortsbezeichnung" enthalten,
//deren loc_id den Bilden des Users $uname zugewiesen sind.
//dann sind diese Datensaetze in der Tabelle locations zu loeschen und in dem korrespondierenden Datensatz in der
//pictures-Tabelle die loc_id auf 0 zu setzen.
//

$result1 = mysql_query("SELECT $table1.id, $table1.username, $table2.owner, $table2.pic_id, $table2.City
FROM $table1, $table2
WHERE $table2.owner = $table1.id
AND $table1.username = '$uname'
AND ($table2.City = 'Ortsbezeichnung' OR $table2.City = ''");
echo mysql_error();
$num1 = mysql_num_rows($result1);
//echo $num1." Treffer.<BR>";
FOR ($i1='0'; $i1<$num1; $i1++)
{
	$pic_id = mysql_result($result1, $i1, 'pic_id');
	$result2 = mysql_query("UPDATE $table2 SET City = 'Ortsbezeichnung' WHERE pic_id = '$pic_id'");
	echo mysql_error();
}
//dann zurueck zur Edit-Start-Seite:
echo "<meta http-equiv = 'refresh', content='0; url=edit_start.php' >";
//08.06.2011 (kh.)
?>