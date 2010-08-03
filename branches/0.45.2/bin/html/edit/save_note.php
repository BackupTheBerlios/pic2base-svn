<?php
IF (!$_COOKIE['login'])
{
include '../../share/global_config.php';
//var_dump($sr);
  header('Location: ../../../index.php');
}
?>

<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
<?php

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/ajax_functions.php';
$note = strip_tags($note);
//echo "Bild-Nr: ".$pic_id."<BR>neue Note: ".$note;
$result1 = mysql_query( "UPDATE $table2 SET note = '$note' WHERE pic_id = '$pic_id'");
mysql_close($conn);
?>