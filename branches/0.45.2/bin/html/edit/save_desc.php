<?php
IF (!$_COOKIE['login'])
{
include '../../share/global_config.php';
//var_dump($sr);
  header('Location: ../../../index.php');
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
$description = strip_tags($description);
//echo "Bild-Nr: ".$pic_id."<BR>Beschreibung: ".$description;
$result1 = mysql_query( "UPDATE $table2 SET Description = '$description' WHERE pic_id = '$pic_id'");

IF (mysql_error() == '')
{
	echo "<script language='JavaScript'>window.close();</script>";
}
ELSE
{
	echo mysql_error();
}
?>