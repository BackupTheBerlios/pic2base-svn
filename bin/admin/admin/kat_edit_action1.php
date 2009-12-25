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

<?

/*
 * Project: pic2base
 * File: kat_edit_action1.php
 *
 * Copyright (c) 2003 - 2006 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 * @copyright 2003-2006 Klaus Henneberg
 * @author Klaus Henneberg
 * @package pic2base
 * @license http://www.opensource.org/licenses/osl-2.1.php Open Software License
 */

include '../../share/db_connect1.php';
INCLUDE '../../share/global_config.php';

//var_dump($_POST);
$ID = $_GET['ID']; // für register_globals = off
$kat_id = $_GET['kat_id']; // für register_globals = off
$kategorie = $_POST['kategorie']; // für register_globals = off

// *#*  echo "kategorie: ".$kategorie."<br>";

$res = mysql_query( "UPDATE $table4 SET kategorie='$kategorie' WHERE kat_id='$ID'");
echo mysql_error();
echo "<meta http-equiv='Refresh' content='0, URL=kat_edit.php?kat_id=$kat_id&ID=$ID'>";

mysql_close($conn);
?>
</BODY>
</HTML>