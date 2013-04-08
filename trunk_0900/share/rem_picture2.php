<?php
header('Content-Type: text/html; charset=utf-8');
//echo "Antwort";

IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../index.php');
}

//############################################################################################################
//wird beim loeschen von Bildern aus der pic_coll-Tabelle verwendet; Aufruf aus edit_selected_collection.php #
//############################################################################################################

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
//echo mysql_error();
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
		//echo mysql_error();
	}
}

// Refresh des aktuellen Kollektionsbestandes in edit_selected_collection.php:
$result5 = mysql_query("SELECT $table25.pic_id, $table25.coll_id, $table25.position, $table2.pic_id, $table2.FileNameV, $table2.FileNameHQ, $table2.DateTimeOriginal, $table2.Caption_Abstract, $table2.City, $table2.Keywords
FROM $table2, $table25
WHERE $table25.coll_id = '$coll_id'
AND $table25.pic_id = $table2.pic_id
ORDER BY $table25.position");
//echo mysql_error();
$num5 = mysql_num_rows($result5);

echo "
<table border='0'>
<tr>";
for($i5=0; $i5<$num5; $i5++)
{
	$new_pic_id = mysql_result($result5, $i5, 'pic_id');
	$FileNameV = mysql_result($result5, $i5, 'FileNameV');		//echo $FileNameV." / ";
	$FileNameHQ = mysql_result($result5, $i5, 'FileNameHQ');	//echo $FileNameHQ." / ";
	$dto = date('d.m.Y, H:i:s', strtotime(mysql_result($result5, $i5, 'DateTimeOriginal')));
	$Caption_Abstract = mysql_result($result5, $i5, 'Caption_Abstract');
	$City = mysql_result($result5, $i5, 'City');
	$Keywords = mysql_result($result5, $i5, 'Keywords');
	
	$infotext = "<b>Bild-ID:</b> ".$pic_id."</br>";
	
	if(mysql_result($result5, $i5, 'DateTimeOriginal') !== '0000-00-00 00:00:00')
	{
		$infotext .= "</br><b>Aufnahmedatum:</b></br>".$dto."</br>";
	}
	if($Caption_Abstract !== '')
	{
		$infotext .= "</br><b>Bildbeschreibung:</b></br>".$Caption_Abstract."</br>";
	}
	
	if($City !== '')
	{
		$infotext .= "</br><b>Aufnahmestandort:</b></br>".$City."</br>";
	}
	
	if($Keywords !== '')
	{
		$infotext .= "</br><b>Kategorien:</b></br>".$Keywords."</br>";
	}
	echo "
	<td>
		<div class='tooltip4' style='float:left;'>
			<p style='margin:0px; cursor:pointer;'>
			<img src='../../../images/vorschau/thumbs/$FileNameV' style='margin-right:5px; margin-bottom:5px; margin-top:5px; height:145px;' title='Hier klicken, um das Bild ".$new_pic_id." aus der Kollektion ".$coll_id." zu entfernen' onClick='sicher(\"$coll_id\", \"$new_pic_id\", \"$uid\")'; />
				<span id='tt' style='text-align:center; margin:0px;'>
															
					<span style='float:left'>
						<img src='../../../images/vorschau/hq-preview/$FileNameHQ' style='height:300px; margin-right:0px; margin-bottom:0px; margin-top:0px;' />
					</span>
					
					<span class='ttinfo'>
						<b><u>Informationen zum Bild</u></b></br></br>
						".$infotext."
					</span>
				
				</span>
			</p>
		</div>
	</td>";
}
echo "
</tr>
</table>";

//letzte Aenderung in Tabelle collections speichern:
$update_date_time = date('Y-m-d H:i:s', time());	//echo $update_date_time;
$result6 = mysql_query("UPDATE $table24 SET last_modification = '$update_date_time' WHERE coll_id = '$coll_id'");

//Log-Datei schreiben:
$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
fwrite($fh,date('d.m.Y H:i:s').": Bild ".$pic_id." wurde aus Kollektion ".$coll_id." entfernt. (Benutzer ".$uid."; Zugriff von ".$_SERVER['REMOTE_ADDR'].")\n");
fclose($fh);

if(mysql_error() !== "")
{
	echo "Konnte die Dateizuordnung nicht l&ouml;schen!<BR>";
}
?>