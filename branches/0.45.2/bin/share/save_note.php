<?php
IF (!$_COOKIE['login'])
{
	include '../share/global_config.php';
	//var_dump($sr);
	header('Location: ../../index.php');
}
?>

<!--<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>-->
<?php

include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/ajax_functions.php';
include $sr.'/bin/share/functions/main_functions.php';

if (array_key_exists('note',$_GET) )
{
	$note = $_GET['note'];
}
if (array_key_exists('pic_id',$_GET) )
{
	$pic_id = $_GET['pic_id'];
}

$note = strip_tags($note);
//echo "Bild-Nr: ".$pic_id."<BR>neue Note: ".$note;
//echo "<p style='background-color:red';>Speicherung l&auml;uft...</p>";
$result1 = mysql_query( "UPDATE $table2 SET note = '$note' WHERE pic_id = '$pic_id'");

showStars($pic_id);
mysql_close($conn);
?>