
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-1">
	<TITLE>pic2base - Doubletten-Best&auml;tigung</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../css/format1.css'>
	<link rel="shortcut icon" href="images/favicon.ico">
</HEAD>
<BODY LANG="de-DE" scroll = "auto">

<CENTER>
<DIV Class="klein">
<?php

unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
}

if(array_key_exists('user_id',$_GET))
{
	$user_id = $_GET['user_id']; 
}
if(array_key_exists('pic_id',$_GET))
{
	$pic_id = $_GET['pic_id']; 
}

include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

echo "
<div class='page'>
	<p id='kopf'>pic2base :: Doubletten-Best&auml;tigung <span class='klein'>(User: ".$c_username.")</span></p>
		
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>
		</div>
	</div>
		
	<div class='content'>
		<span style='font-size:12px;'>
		<p style='margin:120px 0px; text-align:center'>
		
		<center>";
		
		//echo "Bild ".$pic_id." wird aus der Tabelle ".$table21." gel&ouml;scht.";
		echo "Bild ".$pic_id." wird in die Datenbank &uuml;bernommen.";
		$result1 = mysql_query("DELETE FROM $table21 WHERE new_pic_id = '$pic_id'");
		echo mysql_error();

		echo "	
		</center>
		
		</p>
		</span>
	</div>
	<br style='clear:both;' />
	<meta http-equiv='Refresh', content='1; ../html/erfassung/doublettenliste1.php?user_id=$user_id'>
	<p id='fuss'><A style='margin-right:780px;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>".$cr."</p>
</div>
</DIV>
</CENTER>
</BODY>";

?>

