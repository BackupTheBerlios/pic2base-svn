<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - DB-Export</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll = "auto" onLoad = 'getMissingFiles()'>

<CENTER>

<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: db_export_action.php
 *
 * Copyright (c) 2003 - 2012 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 */

if(array_key_exists('PWD',$_POST))
{
	$pwd = $_POST['PWD']; 
}

if(array_key_exists('db_user', $_POST))
{
	$db_user = $_POST['db_user'];
}

if(array_key_exists('method', $_POST))
{
	$method = $_POST['method'];
}


unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
	$benutzername = $c_username;
}
INCLUDE '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';

echo "
<div class='page'>

	<p id='kopf'>pic2base :: Admin-Bereich - Datenbank-Export</p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
		 include '../../html/admin/adminnavigation.php';
		echo "</div>
	</div>
	
	<div id='spalte1'>
	
	<font color='green'>
		<p style='margin-top:20px;'>
		<b>pic2base - Export</b>
		</p>
	
		<p style='margin-top:20px;'>
		<b>Die Datenbank wird exportiert...</b>
		</p>
	</font>";
//	echo "User: ".$db_user.", PWD: ".$pwd.", Methode: ".$method."<BR><BR>";
	switch($method)
	{
		CASE 'sql':
			$statement = "/opt/lampp/bin/mysqldump -u$db_user -p$pwd -h localhost $db > $kml_dir/pic2base.sql";
//			echo $statement."<BR><BR>";
			system($statement, $fp);
			if ($fp==0)
			{
				echo "<BR><font color='green'>Der Export verlief erfolgreich.<BR><BR>F&uuml;r den Download der Export-Datei<BR>(rechts-)klicken Sie bitte <a href='../../../userdata/$c_username/kml_files/pic2base.sql'>hier</a>.</font>"; 
			}
			else
			{
				echo "<BR><font color='red'>Es ist ein Fehler aufgetreten.<BR>Bitte kontrollieren Sie Ihre Zugangsdaten.<BR>Achten Sie besonders darauf, einen MySQL-Benutzer<BR>mit <b>Admin-Rechten</b> einzutragen.</font>"; 
			}
		break;
		
		CASE 'xml':
			
		break;

		CASE 'csv':
			
		break;
	}
	
	
	echo "
	</div>	
	
	<DIV id='spalte2'>
		<!--<p id='elf' style='background-color:white; padding: 5px; width: 365px; margin-top: 20px; margin-left: 20px;'>Export l&auml;uft...</p>-->
	</DIV>
	
	<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>

</div>
</CENTER>
</BODY>";
?>