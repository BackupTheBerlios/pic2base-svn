<?php
IF (!$_COOKIE['login'])
{
include '../share/global_config.php';
//var_dump($sr);
  header('Location: ../../index.php');
}

include 'global_config.php';
include 'db_connect1.php';
//var_dump($_GET);
if ( array_key_exists('FileName',$_GET) )
{
	$FileName = $_GET['FileName'];
}
if ( array_key_exists('c_username',$_GET) )
{
	$c_username = $_GET['c_username'];
}
if ( array_key_exists('pic_id',$_GET) )
{
	$pic_id = $_GET['pic_id'];
}

$datei = $pic_path."/".$FileName;

// Passenden Datentyp erzeugen.
header("Content-Type: application/octet-stream");
 
// Dateinamen im Download-Requester vorgeben
$save_as = basename($datei);
header("Content-Disposition: attachment; filename=\"$save_as\"");
 
// Datei ausgeben.
readfile($datei);

//Downloadzaehler aktualisieren:
$result1 = mysql_query("UPDATE $table2 SET ranking = ranking + 1 WHERE pic_id = '$pic_id'");

?>