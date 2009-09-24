<?php

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
$description = strip_tags($description);
//echo "Bild-Nr: ".$pic_id."<BR>Beschreibung: ".$description;
//echo "<p style='background-color:red';>Speicherung l&auml;ft...</p>";
$result1 = mysql($db, "UPDATE $table2 SET Description = '$description' WHERE pic_id = '$pic_id'");

IF (mysql_error() == '')
{
	echo "<script language='JavaScript'>window.close();</script>";
}
ELSE
{
	echo mysql_error();
}
?>