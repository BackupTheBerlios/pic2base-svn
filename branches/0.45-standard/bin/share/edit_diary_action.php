<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Tagebuch-Eintrag</title>
  <meta name="GENERATOR" content="Quanta Plus">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="Content-Style-Type" content="text/css">
  <link rel=stylesheet type="text/css" href='../css/format1.css'>
  <link rel="shortcut icon" href="images/favicon.ico">
</head>
<body style='background-color:#999999'>

<?php
//skript speichert den geänderten Tagebuch-Eintrag in die diary-Tabelle
echo "&Auml;nderungen werden gespeichert...<BR>";
include_once 'global_config.php';
include_once 'db_connect1.php';
include_once $sr.'/bin/share/functions/main_functions.php';

//var_dump($_REQUEST);
if ( array_key_exists('aufn_dat',$_REQUEST) )
{
	$aufn_dat = $_REQUEST['aufn_dat'];
}
if ( array_key_exists('FCKeditor1',$_POST) )
{
	$FCKeditor1 = $_POST['FCKeditor1'];
}

//Kontrolle, ob Update oder Insert erfolgen muss:
$result1 = mysql_query("SELECT datum FROM $table3 WHERE datum = '$aufn_dat'");
$row = mysql_fetch_array($result1);
IF($row['datum'] == '')
{
	$result2 = mysql_query( "INSERT INTO $table3 (info, datum) VALUES('$FCKeditor1', '$aufn_dat')");
	echo mysql_error();
}
ELSE
{
	$result2 = mysql_query( "UPDATE $table3 SET info = '$FCKeditor1' WHERE datum = '$aufn_dat'");
	echo mysql_error();
}

?>
<script language="JavaScript">
history.back();
</script>

</body>
</html>
