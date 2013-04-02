<?php
header('Content-Type: text/html; charset=utf-8');
IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../index.php');
}

//###################################################################
//wird beim loeschen von Bildern aus der pic_coll-Tabelle verwendet #
//###################################################################

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

//Bestimmung der Position des zu loeschenden Bildes innerhalb der Kollektion:
$result1 = mysql_query("SELECT position FROM $table25 WHERE coll_id = '$coll_id' AND pic_id = '$pic_id'");
echo mysql_error();
$position = mysql_result($result1, isset($i1), 'position');	
//echo $position;

//entfernen des eigentlichen Bildes:
$result2 = mysql_query( "DELETE FROM $table25 WHERE coll_id = '$coll_id' AND pic_id = '$pic_id'");
echo mysql_error();
//Korrigieren der Positionen aller folgender Bilder (falls vorhanden):
$result3 = mysql_query("SELECT * FROM $table25 WHERE coll_id = '$coll_id' AND position > '$position' ORDER BY position");
$num3 = mysql_num_rows($result3); 
//echo $num3. "Treffer";
if($num3 > 0)
{
	for($i3=0; $i3<$num3; $i3++)
	{
		$old_position = mysql_result($result3, $i3, 'position');
		$new_position = $old_position - 1;
		$result4 = mysql_query("UPDATE $table25 SET position = '$new_position' WHERE position = '$old_position' AND coll_id = '$coll_id'");
		echo mysql_error();
	}
}

echo "<SPAN style='cursor:pointer;' onClick='addPicture(\"$coll_id\",\"$pic_id\",\"$uid\")'><img src='$inst_path/pic2base/bin/share/images/download.gif' width='12' height='12' hspace='0' vspace='0' title='Bild zur Kollektion hinzuf&uuml;gen' /></SPAN>";

//Log-Datei schreiben:
$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
fwrite($fh,date('d.m.Y H:i:s').": Bild ".$pic_id." wurde aus Kollektion ".$coll_id." entfernt. (Benutzer ".$uid."; Zugriff von ".$_SERVER['REMOTE_ADDR'].")\n");
fclose($fh);

if(mysql_error() !== "")
{
	echo "Konnte die Dateizuordnung nicht l&ouml;schen!<BR>";
}
?>