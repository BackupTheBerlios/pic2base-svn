<?php
IF (!$_COOKIE['login'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
	header('Location: ../../../index.php');
}

setcookie('bewertung',$qual);
echo "<script language='JavaScript'>window.close();</script>";
?>