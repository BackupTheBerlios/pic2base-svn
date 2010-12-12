<?php
IF (!$_COOKIE['login'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
	header('Location: ../../../index.php');
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
<TITLE>pic2base - Administration</TITLE>
<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel=stylesheet type="text/css" href='../../css/format1.css'>
<link rel="shortcut icon" href="../../share/images/favicon.ico">
<!--sollte die EXIF-Daten-Erzeugung abstuerzen, erfolgt nach 300 Sek. ein automat. Neustart-->
<!--<meta http-equiv="Refresh" Content="300; URL=../../admin/admin/generate_exifdata0.php">-->
<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
</HEAD>

<BODY LANG="de-DE" scroll="auto">

<CENTER>

<DIV Class="klein"><?php

unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
	//echo $c_username;
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';

//log-file schreiben:
$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
fwrite($fh,date('d.m.Y H:i:s')." ".isset($REMOTE_ADDR)." ".$_SERVER['PHP_SELF']." ".$_SERVER['HTTP_USER_AGENT']." ".$c_username."\n");
fclose($fh);

echo "<div class='page'>

	<p id='kopf'>pic2base :: Admin-Bereich</p>

	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
include 'adminnavigation.php';
echo "</div>
	</div>

	<div class='content'>
		<p style='margin:30px 0px; text-align:center'>";
include 'admincontent.php';
echo "</p>
	</div>
	
	<br style='clear:both;' />
	<!--<p id='fuss'>$cr</p>-->
	<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>
</div>
</DIV>";
?>

</CENTER>
</BODY>
</HTML>
