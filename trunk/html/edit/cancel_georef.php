<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Geo-Referenzierung abbrechen</title>
  <meta name="GENERATOR" content="eclipse">
  <meta name="AUTHOR" content="k. henneberg">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel=stylesheet type='text/css' href='../css/format2.css'>
  <link rel="shortcut icon" href="../share/images/favicon.ico">
  <script language="JavaScript" src="../share/functions/resize_elements.js"></script>
  <script language="JavaScript" src="../share/functions/jquery-1.8.2.min.js"></script>
  <script language="JavaScript">
  	jQuery.noConflict();
	jQuery(document).ready(checkWindowSize);
	jQuery(window).resize(checkWindowSize); 
  </script>
</head>

<body>
<?php
if ( array_key_exists('userid',$_GET) )
{
	$userid = $_GET['userid'];
}
echo "Georeferenzierung wird abgebrochen...<BR>";
//echo "&Uuml;bergebener Username: ".$uname."<BR>";
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';

$result1 = mysql_query("SELECT $table2.Owner, $table2.pic_id, $table2.City
FROM $table2
WHERE $table2.Owner = $userid
AND ($table2.City = 'Ortsbezeichnung' OR $table2.City = '' OR $table2.City = 'skipped'");
//echo mysql_error();
$num1 = mysql_num_rows($result1);
//echo $num1." Treffer.<BR>";
FOR ($i1='0'; $i1<$num1; $i1++)
{
	$pic_id = mysql_result($result1, $i1, 'pic_id');
	$result2 = mysql_query("UPDATE $table2 SET City = 'Ortsbezeichnung' WHERE pic_id = '$pic_id'");
	echo mysql_error();
}
//dann zurueck zur Edit-Start-Seite:
echo "<meta http-equiv = 'refresh', content='0; url=edit_start.php' >";
//08.06.2011 (kh.)
?>

</body>
</html>