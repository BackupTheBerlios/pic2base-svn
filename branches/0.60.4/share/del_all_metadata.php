<?php
IF (!$_COOKIE['login'])
{
include '../share/global_config.php';
//var_dump($sr);
  header('Location: ../../index.php');
}


unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = preg_split('#,#',$_COOKIE['login']);
//echo $c_username;
$benutzername = $c_username;
}
 
include 'global_config.php';
echo "<link rel='stylesheet' type='text/css' href='../css/format1.css'><h1 align='center'>...L&ouml;sche Meta-Daten...</h1>";
shell_exec('exiftool -all= -comment="Meta-Daten wurden entfernt." '.$down_dir.'/*.* -overwrite_original > /dev/null &');
?>

<SCRIPT LANGUAGE='Javascript'>
window.setTimeout("window.close()",1000);
</SCRIPT>
