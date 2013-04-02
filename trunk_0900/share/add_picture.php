<?php
IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../index.php');
}

//####################################################################
//wird beim hinzufuegen von Bildern in die pic_coll-Tabelle verwendet #
//####################################################################

//var_dump($_GET);
if ( array_key_exists('coll_id',$_GET) )
{
	$coll_id = $_GET['coll_id'];
}
if ( array_key_exists('uid',$_GET) )
{
	$uid = $_GET['uid'];
}
if ( array_key_exists('pic_id',$_GET) )
{
	$pic_id = $_GET['pic_id'];
}

include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/permissions.php';

//echo "Dateizuordnung ergaenzen??"
//ein neues Bild wird immer an das Ende der bestehenden Kollektion angehaengt
$result1 = mysql_query("SELECT MAX(position) FROM $table25 WHERE coll_id = '$coll_id'");
if(mysql_num_rows($result1) > 0)
{
	$last_position = mysql_result($result1, isset($i1), 'MAX(position)');
	$position = $last_position + 1;
}
else
{
	$position = 1;
}

// duration und transition sind willkuerlich gewaehlt:
$result2 = mysql_query( "INSERT INTO $table25 (coll_id, pic_id, position, duration, transition_id) VALUES ('$coll_id', '$pic_id', '$position', '5', '1')" );
echo mysql_error();
echo "<SPAN style='cursor:pointer;' onClick='removePicture(\"$coll_id\",\"$pic_id\",\"$uid\")'><img src='$inst_path/pic2base/bin/share/images/selected.gif' width='12' height='12' hspace='0' vspace='0' title='Bild aus der Kollektion entfernen' /></SPAN>";

//Log-Datei schreiben:
$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
fwrite($fh,date('d.m.Y H:i:s').": Bild ".$pic_id." wurde zur Kollektion ".$coll_id." hinzugefuegt. (Benutzer ".$uid."; Zugriff von ".$_SERVER['REMOTE_ADDR'].")\n");
fclose($fh);

if(mysql_error() !== "")
{
	echo "Konnte die Dateizuordnung nicht schreiben!<BR>";
}
?>