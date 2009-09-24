<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Startseite</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<!--sollte die EXIF-Daten-Erzeugung abstürzen, erfolgt nach 300 Sek. ein automat. Neustart-->
	<!--<meta http-equiv="Refresh" Content="300; URL=../../admin/admin/generate_exifdata0.php">-->
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?

unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = split(',',$_COOKIE['login']);
//echo $c_username;
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
	

//log-file schreiben:
$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
fwrite($fh,date('d.m.Y H:i:s')." ".$REMOTE_ADDR." ".$_SERVER['PHP_SELF']." ".$_SERVER['HTTP_USER_AGENT']." ".$c_username."\n");
fclose($fh);

?>

<div class="page">

	<p id="kopf">pic2base :: Admin-Bereich</p>

	<div class="navi" style="clear:right;">
		<div class="menucontainer">
		<?
		  include "adminnavigation.php";
		?>
		</div>
	</div>

	<div class="content">
		<p style="margin:30px 0px; text-align:center">
		<?
			include "admincontent.php";
		?>
		</p>
	</div>
	<br style="clear:both;" />

	<p id="fuss"><?php echo $cr; ?></p>

</div>
</DIV></CENTER>
</BODY>
</HTML>