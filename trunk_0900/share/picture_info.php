<?php
IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../index.php');
}

//##################################################################
// Ausgabe von Bildinformationen                                   #
//##################################################################
include 'global_config.php';
include 'db_connect1.php';
include 'functions/permissions.php';
include 'functions/main_functions.php';

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
if ( array_key_exists('inforequest',$_GET) )
{
	$inforequest = $_GET['inforequest'];
}

$datei = $ftp_path."/".$c_username."/downloads/".$FileName;
//echo $datei;

if ($inforequest == 'DownloadStatusIconPreview')
{
	if(file_exists($datei))
	{
		echo "
		<SPAN style='cursor:pointer;' onClick='downloadButtonPressed(\"$FileName\",\"$c_username\",\"$pic_id\", 100)'><img src='$inst_path/pic2base/bin/share/images/downloaded.png' hspace='0' vspace='0' title='Bild aus dem FTP-Download-Ordner entfernen' /></SPAN>";
	}
	else
	{
		echo "
		<SPAN style='cursor:pointer;' onClick='downloadButtonPressed(\"$FileName\",\"$c_username\",\"$pic_id\", 1)'><img src='$inst_path/pic2base/bin/share/images/download.png' hspace='0' vspace='0' title='Bild in den FTP-Download-Ordner kopieren' /></SPAN>";
	}
}

?>