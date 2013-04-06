<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
	header('Location: ../../../index.php');
}
$uid = $_COOKIE['uid'];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - &Auml;nderungen speichern</TITLE>
	<META NAME="GENERATOR" CONTENT="Eclipse">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format2.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script language="JavaScript" src="../../share/functions/resize_elements.js"></script>
	<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
	<script language="JavaScript" src="../../share/functions/jquery-1.8.2.min.js"></script>
	<script language="JavaScript">
		jQuery.noConflict();
		jQuery(document).ready(checkWindowSize);
		jQuery(window).resize(checkWindowSize); 
		
	</script>	
</HEAD>

<BODY>

<CENTER>

<DIV Class="klein">

<?php
var_dump($_POST);
if(array_key_exists('coll_id', $_GET))
{
	$coll_id = $_GET['coll_id'];
}
else
{
	$coll_id = 0;
}

if(array_key_exists('coll_name', $_POST))
{
	$coll_name = $_POST['coll_name'];
}

if(array_key_exists('coll_description', $_POST))
{
	$coll_description = $_POST['coll_description'];
}

if(array_key_exists('coll_edit_right', $_POST))
{
	$coll_edit_right = $_POST['coll_edit_right'];
	if($coll_edit_right == 'on')
	{
		$locked = 0;
	}
	else
	{
		$locked = 1;
	}
}
else
{
	$locked = 1;
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/ajax_functions.php';

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');

//log-file schreiben:
$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
fwrite($fh,date('d.m.Y H:i:s').": Aenderungen zu Kollektion ".$coll_id." wurden von ".$username." gespeichert. (Zugriff von ".$_SERVER['REMOTE_ADDR'].")\n");
fclose($fh);

echo "<div class='page' id='page'>

	<div class='head' id='head'>
		pic2base :: &Auml;nderungen werden gespeichert
		</div>

	<div class='navi' id='navi'>
		<div class='menucontainer'>";
			createNavi3_1($uid);
		echo "</div>
	</div>

	<div class='content' id='content'>
		<fieldset style='background-color:none; margin-top:10px;'>
		<legend style='color:blue; font-weight:bold;'>&Auml;nderungen werden gespeichert</legend>
		<div id='scrollbox2' style='overflow-y:scroll;'>";
			//echo "Coll-Name: ".$coll_name."<BR><BR>Beschreibung: ".$coll_description."<BR>Locked: ".$locked;
			$result1 = mysql_query("UPDATE $table24 SET coll_name='$coll_name', coll_description='$coll_description', locked = '$locked' WHERE coll_id = '$coll_id'");
			echo mysql_error();
			if(mysql_error() == "")
			{
				echo "
				<center>
					<p style='margin-top:150px;'>Die &Auml;nderungen werden gespeichert.</p>
				</center>
				<meta http-equiv='refresh', content='0; url=edit_selected_collection.php?coll_id=$coll_id'>";
			}
			else
			{
				echo "
				<center>
					<p style='margin-top:150px;'>Es trat ein Fehler auf.<br><br>
					Die &Auml;nderungen konnten nicht gespeichert werden.<br><br>
					Bitte benachrichtigen Sie den Administrator.</p>
				</center>";
			}
		echo "
		</div>
		</fieldset>
	</div>
	
	<div class='foot' id='foot'>
		<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
	</div>
	
</div>
</DIV>
</CENTER>
</BODY>
</HTML>";
?>