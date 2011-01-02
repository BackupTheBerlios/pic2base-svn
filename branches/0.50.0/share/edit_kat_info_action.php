<?php
IF (!$_COOKIE['login'])
{
	include '../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../index.php');
}

//skript speichert die Aenderungen des Kategorie-Lexikons
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
//echo mysql_error();
?>
<script language="JavaScript">
//self.close();
self.history.back();
</script>