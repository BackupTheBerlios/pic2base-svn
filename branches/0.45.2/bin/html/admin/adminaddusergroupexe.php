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
	<TITLE>pic2base - Startseite</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?php

unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = preg_split('#,#',$_COOKIE['login']);
//echo $c_username;
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';

?>

<div class="page">

	<p id="kopf">pic2base :: Admin-Bereich - Neuanlage einer Benutzergruppe</p>

	<div class="navi" style="clear:right;">
		<div class="menucontainer">
		<?php
		  include "adminnavigation.php";
		?>
		</div>
	</div>

	<div class="content">
		<p style="margin:70px 0px; text-align:center">
		<?php
		unset($username);
		IF ($_COOKIE['login'])
		{
		list($c_username) = split(',',$_COOKIE['login']);
		//echo $c_username;
		}
				
		$groupname = $_POST['groupname'];
		
		$groupname = strip_tags($groupname);
		if ((hasPermission($c_username, 'adminlogin')) AND ($groupname !== ''))
		{
			mysql_connect ($db_server, $user, $PWD);
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
		?>
		</p>
	</div>
	<br style="clear:both;" />

	<p id="fuss"><?php echo $cr; ?></p>

</div>
</DIV></CENTER>
</BODY>
</HTML>