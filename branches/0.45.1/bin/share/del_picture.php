<?php
//###############################################################
//wird beim l�schen von Bildern aus dem Download-Ordner verwendet
//###############################################################
include 'global_config.php';
include 'db_connect1.php';

//var_dump($_GET);
if ( array_key_exists('FileName',$_GET) )
{
	$FileName = $_GET['FileName'];
}
if ( array_key_exists('c_username',$_GET) )
{
	$c_username = $_GET['c_username'];
}
if ( array_key_exists('pic_id',$_GET) )
{
	$pic_id = $_GET['pic_id'];
}

//log-file schreiben:
$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
fwrite($fh,date('d.m.Y H:i:s')." ".isset($REMOTE_ADDR)." ".$_SERVER['PHP_SELF']." ".$_SERVER['HTTP_USER_AGENT']." ".$c_username."\n");
fclose($fh);

//echo "Datei l&ouml;schen??"
$datei = $ftp_path."/".$c_username."/downloads/".$FileName;
//echo $datei;

if(@unlink($datei))
{
	$result1 = mysql_query( "UPDATE $table2 SET ranking = ranking - 1 WHERE pic_id = '$pic_id'");
	echo "	<TD align='center'>
	<SPAN style='cursor:pointer;' onClick='copyPicture(\"$FileName\",\"$c_username\",\"$pic_id\")'><img src='$inst_path/pic2base/bin/share/images/download.gif' width='12' height='12' hspace='0' vspace='0' /></SPAN>	
	</TD>";
}
else
{
	echo "Konnte die Datei $FileName nicht l&ouml;schen!<BR>";
}

//es wird ermittelt, ob im Download-Ordner weitere Dateien mit dem Stamm-Namen existieren (z.B. 1234567676)
//Wenn ja, wird gepr�ft, wieviel hiervon Scene-Dateien sind (z.B. 1234567676-1.jpg) und wieviele Nicht-JPG-Bilder sind (z.B. 1234567676.bmp)
//nur wenn keine scene-Dateien mehr im Download-Ordner sind, wird auch die Nicht-JPG-Datei gel�scht

$file_info = pathinfo($datei);
$base_name = substr($file_info['basename'],0,-4);
//echo $base_name;
$result2 = mysql_query( "SELECT * FROM $table2 WHERE FileName LIKE '$base_name%'");
$num2 = mysql_num_rows($result2);	//es gibt in der DB insges. $num2 Dateien mit dem Stammnamen.
//echo $num2." Dateien mit Stammnamen in DB<BR>";
$k = '0';
FOR($i2='0'; $i2<$num2; $i2++)
{
	$file_name = mysql_result($result2, $i2, 'FileName');
	IF(file_exists($ftp_path."/".$c_username."/downloads/".$file_name))
	{
		$k++;
	}
}
//es befinden sich nun noch weitere $k Dateien im Download-Ordner.
//echo "davon ".$k." noch im Download-Ordner.";
IF($k == '0')
{
	//wenn keine Stamm-Datei mehr im Download-Ordner mehr ist wird eine evtl. vorh. Nicht-JPG-Datei gel�scht:
	FOREACH($supported_filetypes AS $sft)
	{
		IF(file_exists($ftp_path."/".$c_username."/downloads/".$base_name.".".$sft))
		{
			//echo "Es gibt eine Nicht-JPG-Datei";
			unlink($ftp_path."/".$c_username."/downloads/".$base_name.".".$sft);
		}
	}
}

?>