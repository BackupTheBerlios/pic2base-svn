<?php
IF (!$_COOKIE['login'])
{
include '../share/global_config.php';
//var_dump($sr);
  header('Location: ../../index.php');
}

unset($c_username);
IF ($_COOKIE['login'])
{
list($c_username) = preg_split('#,#',$_COOKIE['login']);
//echo $c_username;
}
ELSE
{
	echo "User-Check meldet: Kein Cookie gesetzt?";
}
include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
$result1 = mysql_query( "SELECT * FROM $table1 WHERE username = '$c_username' AND aktiv = '1'");
$berechtigung = mysql_result($result1, $i1, 'berechtigung');
IF ($berechtigung > '8')
{
	echo "<meta http-equiv='refresh', content='5; URL=$inst_path/pic2base/index.php?hinweis=1'>";
	RETURN;
}
mysql_close($conn);
?>