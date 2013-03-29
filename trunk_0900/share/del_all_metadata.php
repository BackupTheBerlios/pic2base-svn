<?php
IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
  	header('Location: ../../index.php');
}
else
{
	$uid = $_COOKIE['uid'];
}
 
include 'global_config.php';
echo "<link rel='stylesheet' type='text/css' href='../css/format1.css'><h1 align='center'>...L&ouml;sche Meta-Daten...</h1>";
shell_exec('exiftool -all= -comment="Meta-Daten wurden entfernt." '.$down_dir.'/*.* -overwrite_original > /dev/null &');
?>

<SCRIPT LANGUAGE='Javascript'>
window.setTimeout("window.close()",1000);
</SCRIPT>
