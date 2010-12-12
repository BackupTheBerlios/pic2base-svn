<?php
$myhost='localhost';
$myuser='pb';
$mypw='pic_base';
$mydb = 'pic2base';

$database = mysql_pconnect($myhost,$myuser,$mypw);
if (!$database) {
	echo "##ERROR##, could not connect to host $myhost<br>\n";
	echo "##MySQL ERRNO: " . mysql_errno() . "<br>\n";
	echo "##MySQL ERROR: " . mysql_error() . "<br>\n";
	return;
}
if (!mysql_select_db($mydb)) {
	echo "##ERROR##, could not select database $mydb<br>\n";
	echo "##MySQL ERRNO: " . mysql_errno() . "<br>\n";
	echo "##MySQL ERROR: " . mysql_error() . "<br>\n";
	return;
}
mysql_query("SET CHARACTER SET latin1");
?>
