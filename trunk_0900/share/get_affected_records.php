<?php
//######################################################################################################
//
// Skript ermittelt alle Datensaetze die betroffen sind, wenn eine Ortsbezeichnung geaendert werden soll
//
//######################################################################################################

unset($username);
IF ($_COOKIE['uid'])
{
	$uid = $_COOKIE['uid'];
}

if (array_key_exists('oldCityname',$_GET))
{
	$oldCityname = $_GET['oldCityname'];
}

if (array_key_exists('newCityname',$_GET))
{
	$newCityname = $_GET['newCityname'];
}

include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
//Ermittlung der Anzahl von Datensaetzen, bei denen der Ortsnamen geaendert werden soll:
//Ermittlung, wieviel Datensaetzte betroffen sind und Abspeicherung der pic_id's in einem Array:
$records = array();
$obj = new stdClass();

$result1 = mysql_query("SELECT * FROM $table2 WHERE City='$oldCityname'");
$num1 = mysql_num_rows($result1);

FOR($i1=0; $i1<$num1; $i1++)
{
	$record_number = mysql_result($result1, $i1, 'pic_id');
	$records[] = $record_number;
}

$obj->anzahl = $num1;
$obj->oldCityname = $oldCityname;
$obj->newCityname = $newCityname;
$obj->record_array = $records;
$output = json_encode($obj);
echo $output;
?>