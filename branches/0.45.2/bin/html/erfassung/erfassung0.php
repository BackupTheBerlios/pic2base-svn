<?php
IF (!$_COOKIE['login'])
{
include '../../share/global_config.php';
//var_dump($sr);
  header('Location: ../../../index.php');
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Willkommen bei pic2base</title>
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="AUTHOR" content="k. henneberg">
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15">
  <link rel=stylesheet type="text/css" href="../../css/format1.css">
</head>

<!--
/*
 * Project: pic2base
 * File: erfassung0.php
 *
 * Copyright (c) 2005 - 2006 Klaus Henneberg
 *
 * Project owner:
 * Klaus Henneberg
 * Finkenweg 18
 * 38889 Blankenburg, BRD
 *
 * All files of this project are licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 * @license http://www.opensource.org/licenses/osl-2.1.php Open Software License
 */
 -->

<BODY LANG="de-DE" scroll = "auto">
<DIV Class="klein">
<?php
unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = preg_split('#,#',$_COOKIE['login']);
}

$ACTION = $_SERVER['PHP_SELF'];
$link = "http://{$_SERVER['SERVER_NAME']}$ACTION";

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

echo "
<div class='page'>

	<p id='kopf'>pic2base :: Bilddaten-Erfassung <span class='klein'>(User: $c_username)</span></p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
		createNavi1($c_username);
		echo "</div>
	</div>
	
	<div class='content'>
		<center>
		<table class='normal' style='background-color:rgb(255,250,150); color:black; margin-top:80px; margin-bottom:40px; border-style:solid; border-width:2px; border-color:#FF9900;'>
		<tbody>
		<TR>
			<TD align='center'>
			Wichtiger Hinweis:<BR>
			Akzeptiert werden die in der <a href=\"../help/help1.php?page=1\" title='Liste der unterst&uuml;tzen Dateiformate'>Hilfe</a> gelisteten Dateiformate, wobei die Dateigr&ouml;&szlig;e<BR>
			von 5 MB je Bild beim Einzelbild-Upload nicht &uuml;berschritten werden darf.<BR>
			Wollen SIe gr&ouml;&szlig;ere Bilder oder mehrere Bilder in einem Vorgang auf den Server laden, benutzen Sie bitte den <a href=\"../help/help1.php?page=1\" title='Hinweise zum Batch-Prozess per FTP'>Batch-Prozess</a>.
			</TD>
		</TR>
		</tbody>
		</table>
		
		<INPUT type='button' class='button1' value='Zum Einzelbild-Upload' onclick=location.href='erfassung1.php'><BR><BR><BR>
		</center>
	</div>
	<br style='clear:both;' />

	<p id='fuss'><A style='margin-right:745px;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>".$cr."</p>
</div>";
?>

</div>
</body>
</html>
