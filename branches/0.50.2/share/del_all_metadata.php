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
echo "<p align='center'>l&ouml;sche Meta-Daten...</p>";
shell_exec('exiftool -all= -comment="Meta-Daten wurden entfernt." '.$down_dir.'/*.* -overwrite_original');
?>

<SCRIPT LANGUAGE='Javascript'>
window.setTimeout("window.close()",3000);
</SCRIPT>
