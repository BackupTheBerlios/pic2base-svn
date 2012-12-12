<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
  	header('Location: ../../../index.php');
}

setcookie('bewertung',$qual);
echo "<script language='JavaScript'>window.close();</script>";
?>