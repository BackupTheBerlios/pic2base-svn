<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Neune Eigent&uuml;mer festlegen</title>
  <meta name="GENERATOR" content="Quanta Plus">
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Content-Style-Type" content="text/css">
  <link rel=stylesheet type="text/css" href='../../css/format1.css'>
  <link rel="shortcut icon" href="../../share/images/favicon.ico">
  <script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
</head>

<body style='background-color:#DDDDDD'>

<?php
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';

//var_dump($_POST);
if ( array_key_exists('pic_id',$_POST) )
{
	$pic_id = $_POST['pic_id'];
}

if ( array_key_exists('new_owner',$_POST) )
{
	$new_owner = $_POST['new_owner'];
}
else
{
	$new_owner = '';
}
//echo "neuer Owner: ".$new_owner;

IF( $new_owner !== '' AND $new_owner !== NULL)
{
	$result1 = mysql_query( "SELECT * FROM $table1 WHERE id = '$new_owner'");
	$num1 = mysql_num_rows($result1);
	$row = mysql_fetch_array($result1);
	$vorname = $row['vorname'];
	$name = $row['name'];
//	$vorname = mysql_result($result1, $i1, 'vorname');
//	$name = mysql_result($result1, $i1, 'name');
	$result2 = mysql_query( "UPDATE $table2 SET Owner = '$new_owner' WHERE pic_id = '$pic_id'");
	IF(mysql_error() == '')
	{
		echo "<div id='content' style='width:740px; height:300px; margin-top:200px;'>Bild ".$pic_id." wurde der neue Eigent&uuml;mer ".$vorname." ".$name." zugewiesen.<BR>
		<input type='button' value='Fenster schliessen' onClick='window.close()'; style='margin-top:50px;'>";
	}
	ELSE
	{
		echo "<div id='content' style='width:740px; height:300px; margin-top:200px;'>Bei der Zuordnung ist ein Fehler aufgetreten<BR>
		Bitte informieren Sie Ihren System-Administrator.";
	}
}
ELSE
{
	echo "<div id='content' style='width:740px; height:300px; margin-top:200px; color:red;'>Bitte w&auml;hlen Sie einen neuen Eigent&uuml;mer aus oder brechen Sie den Vorgang ab.<BR>
	<input type='button' value='Vorgang abbrechen' onClick='window.close()'; style='margin-top:50px;'>
	<input type='button' value='Zur&uuml;ck zur Auswahl' onClick='javascript:history.back()'; style='margin-top:50px; margin-left:20px;'>";
}
?>
</body>
</html>