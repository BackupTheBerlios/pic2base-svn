<?php

IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
	//echo $c_username;
	//$benutzername = $c_username;
}

if (array_key_exists('file',$_GET))
{
	$file = $_GET['file'];
}
echo $file;

$error_code = NULL;

include '../../share/global_config.php';

$datei = $ftp_path."/".$c_username."/uploads/".$file;

if(@unlink($datei))
{
	$error_code = 0;
}
else
{
	$error_code = 1;
}

$obj1 = new stdClass();
$obj1->errorCode = $error_code;
$output = json_encode($obj1);
echo $output;
?>
