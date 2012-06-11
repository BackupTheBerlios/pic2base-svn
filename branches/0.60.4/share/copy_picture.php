<?php
IF (!$_COOKIE['login'])
{
	include '../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../index.php');
}

include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/permissions.php';

$exiftool = buildExiftoolCommand($sr);

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

$datei = $pic_path."/".$FileName;
$target = $ftp_path."/".$c_username."/downloads/".$FileName;
if(@copy($datei,$target))
{
	$result1 = mysql_query( "UPDATE $table2 SET ranking = ranking + 1 WHERE pic_id = '$pic_id'");
	$result2 = mysql_query( "SELECT FileNameV FROM $table2 WHERE pic_id = '$pic_id'");
	$FileNameV = mysql_result($result2, isset($i2), 'FileNameV');
	IF(hasPermission($c_username, 'rotatepicture'))
	{
		echo "
		<SPAN style='cursor:pointer;' onClick='rotPrevPic(\"8\", \"$FileNameV\", \"$pic_id\", \"$fs_hoehe\")'><img src=\"$inst_path/pic2base/bin/share/images/90-ccw.gif\" width=\"8\" height=\"8\" style='margin-right:5px;' title='Vorschaubild 90&#176; links drehen' /></span>
		<SPAN style='cursor:pointer;' onClick='delPicture(\"$FileName\",\"$c_username\",\"$pic_id\")'><img src='$inst_path/pic2base/bin/share/images/selected.gif' width='12' height='12' hspace='0' vspace='0' title='Bild aus dem FTP-Download-Ordner entfernen' /></SPAN>
		<SPAN style='cursor:pointer;' onClick='rotPrevPic(\"6\", \"$FileNameV\", \"$pic_id\", \"$fs_hoehe\")'><img src=\"$inst_path/pic2base/bin/share/images/90-cw.gif\" width=\"8\" height=\"8\" style='margin-left:5px;' title='Vorschaubild 90&#176; rechts drehen' /></span>";
	}
	ELSE
	{
		echo "<SPAN style='cursor:pointer;' onClick='delPicture(\"$FileName\",\"$c_username\",\"$pic_id\")'><img src='$inst_path/pic2base/bin/share/images/selected.gif' width='12' height='12' hspace='0' vspace='0' title='Bild aus dem FTP-Download-Ordner entfernen' /></SPAN>";
	}
	//Log-Datei schreiben:
	$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
	fwrite($fh,date('d.m.Y H:i:s').": Bild ".$pic_id." wurde von ".$c_username." heruntergeladen. (Zugriff von ".$_SERVER['REMOTE_ADDR'].")\n");
	fclose($fh);
}
else
{
	echo "Konnte die Datei $FileName nicht kopieren!<BR>";
}
//es wird geprueft, ob ggf. ein Originalbild als NICHT-JPG vorliegt
$file_info = pathinfo($datei);
$base_name = substr($file_info['basename'],0,-4);
//echo $base_name;
$k = '0';
FOREACH($supported_filetypes AS $sft)
{
	IF(file_exists($pic_path."/".$base_name.".".$sft) AND $sft !== 'jpg')
	{
		copy($pic_path."/".$base_name.".".$sft, $ftp_path."/".$c_username."/downloads/".$base_name.".".$sft);
		//die Meta-Daten dieses nicht-jpg-Bildes werden in das bereits herauskopierte jpg-Bild uebertragen:
		$command = $exiftool." -tagsFromFile ".$ftp_path."/".$c_username."/downloads/".$base_name.".".$sft." ".$ftp_path."/".$c_username."/downloads/".$FileName." -overwrite_original";
		//echo $command;
		shell_exec($command." > /dev/null &");
		$k++;
	}
}
?>