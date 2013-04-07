<?php
unset($username);
IF ($_COOKIE['uid'])
{
	$uid = $_COOKIE['uid'];
}

include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
//#####################################################################################################################
//
//Ermittlung der Anzahl von Kollektions-Bildern, die in den Downloadordner des angemeldeten Users kopiert werden sollen:
//
//#####################################################################################################################

if(array_key_exists('coll_id', $_GET))
{
	$coll_id = $_GET['coll_id'];
}
else
{
	//$coll_id = 11;
}

$n = 0;				//Zaehlvariable fuer die zu kopierenden Bilder
//Ermittlung, wieviel Bilddateien kopiert werden sollen und Abspeicherung der pic-IDs in einem Array:
$bild_datei = array();

$obj = new stdClass();

$result1 = mysql_query("SELECT * FROM $table25 WHERE coll_id = '$coll_id'");
echo mysql_error();
$num1 = mysql_num_rows($result1);
for($i1=0; $i1<$num1; $i1++)
{
	$pic_id = mysql_result($result1, $i1, 'pic_id');
	$bild_datei[] = $pic_id;
	$n++;
}

$obj->coll_id = $coll_id;
$obj->anzahl = $n;
$obj->file_array = $bild_datei;
$output = json_encode($obj);
echo $output;

?>