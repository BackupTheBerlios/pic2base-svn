<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../../index.php');
}
else
{
	$uid = $_COOKIE['uid'];
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Admin-Bereich</TITLE>
	<META NAME="GENERATOR" CONTENT="eclipse">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format2.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script language="JavaScript" src="../../share/functions/resize_elements.js"></script>
	<script language="JavaScript" src="../../share/functions/jquery-1.8.2.min.js"></script>
	<script language="JavaScript">
	  	jQuery.noConflict();
		jQuery(document).ready(checkWindowSize);
		jQuery(window).resize(checkWindowSize); 
	</script>
</HEAD>

<BODY LANG="de-DE">
<CENTER>
<DIV Class="klein">

<?php

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';

echo "

<div class='page' id='page'>

	<div class='head' id='head'>
		pic2base :: Admin-Bereich - Neuanlage einer Benutzergruppe
	</div>
	
	<div class='navi' id='navi'>
		<div class='menucontainer'>";
		  include 'adminnavigation.php';
		echo "
		</div>
	</div>

	<div class='content' id='content'>
		<p style='margin:70px 0px; text-align:center'>";
				
		$groupname = $_POST['groupname'];
		
		$groupname = strip_tags($groupname);
		if ((hasPermission($uid, 'adminlogin', $sr)) AND ($groupname !== ''))
		{
			$result1 = mysql_query("INSERT INTO $table9 (description) VALUES ('".$groupname."')");
			$result2 = mysql_query( "SELECT id FROM $table9 WHERE description = '$groupname'");
			$groupid = mysql_result($result2, isset($i2), 'id');
			$result3 = mysql_query( "SELECT perm_id FROM $table8");
			$num3 = mysql_num_rows($result3);
			FOR($i3 = '0'; $i3<$num3; $i3++)
			{
				$perm_id = mysql_result($result3, $i3, 'perm_id');
				$result4 = mysql_query( "INSERT INTO $table6 (group_id, permission_id, enabled) VALUES ('$groupid', '$perm_id', '0')");
			}
			echo "<meta http-equiv='Refresh' content='0; URL=adminframe.php?item=adminshowgroups'>";
		}
		ELSE
		{
			echo "<p style='text-align:center; color:red';>Sie haben nicht gen&uuml;gend Berechtigungen oder der Gruppenname ist ung&uuml;ltig!</style><BR>";
		}
		echo "
		</p>
	</div>
	
	<div class='foot' id='foot'>
		<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
	</div>
</div>
</DIV></CENTER>
</BODY>
</HTML>";
		
?>