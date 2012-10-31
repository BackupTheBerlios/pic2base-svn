<?php
IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../index.php');
}

//##################################################################
//wird beim loeschen von Bildern aus dem Download-Ordner verwendet #
//##################################################################

//var_dump($_GET);
if ( array_key_exists('FileName',$_GET) )
{
	$FileName = $_GET['FileName'];
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

//echo "Datei l&ouml;schen??"
$datei = $ftp_path."/".$uid."/downloads/".$FileName;
//echo $datei;

if(@unlink($datei))
{
	$result1 = mysql_query( "UPDATE $table2 SET ranking = ranking - 1 WHERE pic_id = '$pic_id'");
	$result2 = mysql_query( "SELECT FileNameV FROM $table2 WHERE pic_id = '$pic_id'");
	$FileNameV = mysql_result($result2, isset($i2), 'FileNameV');
	IF(hasPermission($uid, 'rotatepicture', $sr))
	{
		echo "
		<SPAN style='cursor:pointer;' onClick='rotPrevPic(\"8\", \"$FileNameV\", \"$pic_id\", \"$fs_hoehe\")'><img src=\"$inst_path/pic2base/bin/share/images/90-ccw.gif\" width=\"8\" height=\"8\" style='margin-right:5px;' title='Vorschaubild 90&#176; links drehen' /></span>
		<SPAN style='cursor:pointer;' onClick='copyPicture(\"$FileName\",\"$uid\",\"$pic_id\")'><img src='$inst_path/pic2base/bin/share/images/download.gif' width='12' height='12' hspace='0' vspace='0' title='Bild in den FTP-Download-Ordner kopieren' /></SPAN>
		<SPAN style='cursor:pointer;' onClick='rotPrevPic(\"6\", \"$FileNameV\", \"$pic_id\", \"$fs_hoehe\")'><img src=\"$inst_path/pic2base/bin/share/images/90-cw.gif\" width=\"8\" height=\"8\" style='margin-left:5px;' title='Vorschaubild 90&#176; rechts drehen' /></span>";
	}
	ELSE
	{
		echo "<SPAN style='cursor:pointer;' onClick='copyPicture(\"$FileName\",\"$uid\",\"$pic_id\")'><img src='$inst_path/pic2base/bin/share/images/download.gif' width='12' height='12' hspace='0' vspace='0' title='Bild in den FTP-Download-Ordner kopieren' /></SPAN>";
	}
	//Log-Datei schreiben:
	$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
	fwrite($fh,date('d.m.Y H:i:s').": Download des Bildes ".$pic_id." wurde von ".$uid." abgebrochen. (Zugriff von ".$_SERVER['REMOTE_ADDR'].")\n");
	fclose($fh);
}
else
{
	echo "Konnte die Datei $FileName nicht l&ouml;schen!<BR>";
}

//es wird ermittelt, ob im Download-Ordner weitere Dateien mit dem Stamm-Namen existieren (z.B. 1234567676)
//Wenn ja, wird geprueft, wieviel hiervon Scene-Dateien sind (z.B. 1234567676-1.jpg) und wieviele Nicht-JPG-Bilder sind (z.B. 1234567676.bmp)
//nur wenn keine scene-Dateien mehr im Download-Ordner sind, wird auch die Nicht-JPG-Datei geloescht

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
	IF(file_exists($ftp_path."/".$uid."/downloads/".$file_name))
	{
		$k++;
	}
}
//es befinden sich nun noch weitere $k Dateien im Download-Ordner.
//echo "davon ".$k." noch im Download-Ordner.";
IF($k == '0')
{
	//wenn keine Stamm-Datei mehr im Download-Ordner mehr ist wird eine evtl. vorh. Nicht-JPG-Datei geloescht:
	FOREACH($supported_filetypes AS $sft)
	{
		IF(file_exists($ftp_path."/".$uid."/downloads/".$base_name.".".$sft))
		{
			//echo "Es gibt eine Nicht-JPG-Datei";
			unlink($ftp_path."/".$uid."/downloads/".$base_name.".".$sft);
		}
	}
}

?>