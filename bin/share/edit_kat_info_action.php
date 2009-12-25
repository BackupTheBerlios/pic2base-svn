<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Bild-Details</title>
  <meta name="GENERATOR" content="Quanta Plus">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="Content-Style-Type" content="text/css">
  <link rel=stylesheet type="text/css" href='../css/format1.css'>
  <link rel="shortcut icon" href="images/favicon.ico">
</head>
<body style='background-color:#999999'>

<?php
//skript speichert die geänderten Meta-Daten in die exif_data-Tabelle und in das betreffende Bild
echo "&Auml;nderungen zur Kategorie-ID: ".$kat_id." werden gespeichert...<BR>";
include_once 'global_config.php';
include_once 'db_connect1.php';
include_once $sr.'/bin/share/functions/main_functions.php';

//var_dump($_REQUEST);
if ( array_key_exists('kat_id',$_REQUEST) )
{
	$kat_id = $_REQUEST['kat_id'];
}
if ( array_key_exists('FCKeditor1',$_POST) )
{
	$FCKeditor1 = $_POST['FCKeditor1'];
}

//echo $FCKeditor1;
$result1 = mysql_query( "UPDATE $table11 SET info = '$FCKeditor1' WHERE kat_id = '$kat_id'");
echo mysql_error();

flush();
sleep(1);
?>
<script language="JavaScript">
window.close();
</script>

</body>
</html>
