<?
// php 5.3
/*
 * Project: pic2base
 * File: abfrage.php
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

//echo "Abfrage-Kriterium: ".$feld."\n";
include '../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';

$result1 = mysql_query("SELECT DISTINCT $feld FROM $table2 ORDER BY $feld");
$num1 = mysql_num_rows($result1);
FOR ($i1=0; $i1<$num1; $i1++)
{
	$inhalt = mysql_result($result1, $i1, $feld);
	$content[] = $inhalt;
}
/*
echo "M�gliche Werte f�r das gew�hlte Abfragekriterium sind:\n";
FOREACH ($content AS $inh)
{
 	echo $inh."\n";
}
*/
$antwort = "<entries>";
FOREACH ($content AS $inh)
{
$antwort .= "	<data>
		<name>$inh</name>
		</data>";
}
$antwort .= "</entries>";
echo $antwort;
mysql_close($conn);

?>