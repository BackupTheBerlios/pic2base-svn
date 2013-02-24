<?php
IF (!$_COOKIE['uid'])
{
include '../share/global_config.php';
//var_dump($sr);
  header('Location: ../../index.php');
}

//skript speichert den geaenderten Tagebuch-Eintrag in die diary-Tabelle
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
	$result2 = mysql_query( "INSERT INTO $table3 (info, datum) VALUES(\"$FCKeditor1\", '$aufn_dat')");
	if(mysql_error() !== '')
	{
		echo "Insert-Fehler in Tabelle diary!<br>";
		echo mysql_error();
	}
}
ELSE
{
	$result2 = mysql_query( "UPDATE $table3 SET info = \"$FCKeditor1\" WHERE datum = '$aufn_dat'");
	if(mysql_error() !== '')
	{
		echo "UPDATE-Fehler in Tabelle diary!<br>";
		echo mysql_error();
	}
}

?>
<script language="JavaScript">
history.back();
</script>