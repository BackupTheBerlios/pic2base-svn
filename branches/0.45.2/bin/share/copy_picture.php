<?php
IF (!$_COOKIE['login'])
{
include '../share/global_config.php';
//var_dump($sr);
  header('Location: ../../index.php');
}

include 'global_config.php';
include 'db_connect1.php';

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
	echo "	<TD align='center'>
	<SPAN style='cursor:pointer;' onClick='delPicture(\"$FileName\",\"$c_username\",\"$pic_id\")'><img src='$inst_path/pic2base/bin/share/images/selected.gif' width='12' height='12' hspace='0' vspace='0'/></SPAN>	
	</TD>";
}
else
{
	echo "Konnte die Datei $FileName nicht kopieren!<BR>";
}
//es wird geprüft, ob ggf. ein Originalbild als NICHT-JPG vorliegt
$file_info = pathinfo($datei);
$base_name = substr($file_info['basename'],0,-4);
//echo $base_name;
$k = '0';
FOREACH($supported_filetypes AS $sft)
{
	IF(file_exists($pic_path."/".$base_name.".".$sft) AND $sft !== 'jpg')
	{
		//echo "Es gibt eine ".$sft."-Datei";
		copy($pic_path."/".$base_name.".".$sft, $ftp_path."/".$c_username."/downloads/".$base_name.".".$sft);
		//die Meta-Daten dieses nicht-jpg-Bildes werden in das bereits herauskopierte jpg-Bild uebertragen:
		$command = $et_path."/exiftool -tagsFromFile ".$ftp_path."/".$c_username."/downloads/".$base_name.".".$sft." ".$ftp_path."/".$c_username."/downloads/".$FileName." -overwrite_original";
		//echo $command;
		shell_exec($command);
		$k++;
	}
}
IF($k == '0')
{
 //echo "keine weiteren Dateien.";
}
?>