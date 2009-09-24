<!--<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>-->
<?php

include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/ajax_functions.php';
include $sr.'/bin/share/functions/main_functions.php';
$note = strip_tags($note);
//echo "Bild-Nr: ".$pic_id."<BR>neue Note: ".$note;

//echo "<p style='background-color:red';>Speicherung l&auml;uft...</p>";
$result1 = mysql($db, "UPDATE $table2 SET note = '$note' WHERE pic_id = '$pic_id'");

showStars($pic_id);
mysql_close($conn);
?>