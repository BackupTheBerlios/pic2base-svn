<?php
IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
  	header('Location: ../../index.php');
}


IF ($_COOKIE['uid'])
{
	$uid = $_COOKIE['uid'];
}
ELSE
{
	echo "User-Check meldet: Kein Cookie gesetzt?";
}
include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
$result1 = mysql_query( "SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$berechtigung = mysql_result($result1, $i1, 'berechtigung');
IF ($berechtigung > '8')
{
	echo "<meta http-equiv='refresh', content='5; URL=$inst_path/pic2base/index.php?hinweis=1'>";
	RETURN;
}
mysql_close($conn);
?>