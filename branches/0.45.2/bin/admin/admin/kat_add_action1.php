<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
<TITLE>pic2base - Kategorie hinzuf&uuml;gen</TITLE>
<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel=stylesheet type="text/css" href='../../css/format1.css'>
<link rel="shortcut icon" href="../../share/images/favicon.ico">
</HEAD>

<?php

/*
 * Project: pic2base
 * File: kat_add_action1.php
 *
 * Copyright (c) 2003 - 2010 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 */

INCLUDE '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';

$kategorie = $_POST['kategorie'];
$parent = $_POST['parent'];
$level = $_REQUEST['level'];
$kat_id = $_REQUEST['kat_id'];
$ID = $_REQUEST['ID'];


$res0 = mysql_query( "SELECT * FROM $table4 WHERE kategorie='$kategorie' AND parent='$parent' AND level='$level'");
IF (mysql_num_rows($res0) > 0)
{
	echo "<p class='zwoelfred' style='margin-top: 150px'>Die Unterkategorie ist bereits vorhanden<BR>und wird daher nicht noch einmal angelegt!<BR><BR>
	Bitte einen Moment Geduld...</p><BR>
	<meta http-equiv='Refresh' content='3, URL=kategorie0.php?kat_id=$kat_id&ID=$ID'>";
	return;
}

$res1 = mysql_query( "INSERT INTO $table4 (kategorie, parent, level) VALUES ('$kategorie', '$parent', '$level')");
echo mysql_error();
$kat_id = mysql_insert_id();
$res2 = mysql_query( "INSERT INTO $table11 (kat_id, info) VALUES ('$kat_id', '')");
echo "<meta http-equiv='Refresh' content='0, URL=kategorie0.php?kat_id=$kat_id&ID=$ID'>";

mysql_close($conn);
?>
</BODY>
</HTML>
