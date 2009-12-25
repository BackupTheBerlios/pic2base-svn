<?php
include 'global_config.php';
include 'db_connect1.php';

//var_dump($_GET);
//var_dump($_POST);
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
//echo $datei;
$target = $ftp_path."/".$c_username."/downloads/".$FileName;
if(@copy($datei,$target))
{
	include 'exif.php';
	$result1 = mysql_query( "UPDATE $table2 SET ranking = ranking + 1 WHERE pic_id = '$pic_id'");
	echo "	<TD align='center'>
	<SPAN style='cursor:pointer;' onClick='delPicture(\"$FileName\",\"$c_username\",\"$pic_id\")'><img src='$inst_path/pic2base/bin/share/images/selected.gif' width='12' height='12' hspace='0' vspace='0'/></SPAN>	
	</TD>";
	//auslesen der zusätzlichen Informationen aus der Datenbank und übertragen in die EXIF-Daten der bereits kopierten Bild-Datei:
	$result2 = mysql_query( "SELECT * FROM $table2 WHERE pic_id = '$pic_id'");
	$result4 = mysql_query( "SELECT * FROM $table14 WHERE pic_id = '$pic_id'");
	$loc_id = mysql_result($result2, isset($i2), 'loc_id');
	IF($loc_id !== '' OR $loc_id !== '0')
	{
		$result3 = mysql_query( "SELECT * FROM $table12 WHERE loc_id = '$loc_id'");
		@$longitude = mysql_result($result3, isset($i3), 'longitude');
		@$latitude = mysql_result($result3, isset($i3), 'latitude');
		@$altitude = mysql_result($result3, isset($i3), 'altitude');
		//echo "Datei: ".$FileName.", L&auml;nge: ".$longitude.", Breite: ".$latitude.", H&ouml;he: ".$altitude."<BR>";
		addGPSdata($target,$target,$latitude,$longitude,$altitude);
		
	}
	$input = $target;
	$output = $target;	
	$description = htmlentities(mysql_result($result4, isset($i4), 'Caption_Abstract'));
	include $sr.'/bin/html/edit/edit_exif_desc.php';
}
else
{
	echo "Konnte die Datei $FileName nicht kopieren!<BR>";
}
//es wird geprüft, ob ggf. ein Originalbild als NICHT-JPG vorliegt
$file_info = pathinfo($datei);
//$base_name = substr($file_info['basename'],0,10);
$base_name = substr($file_info['basename'],0,-4);
//echo $base_name;
$k = '0';
FOREACH($supported_filetypes AS $sft)
{
	IF(file_exists($pic_path."/".$base_name.".".$sft))
	{
		//echo "Es gibt eine ".$sft."-Datei";
		copy($pic_path."/".$base_name.".".$sft, $ftp_path."/".$c_username."/downloads/".$base_name.".".$sft);
		$k++;
	}
}
IF($k == '0')
{
 //echo "keine weiteren Dateien.";
}
?>