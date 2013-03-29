<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Kategorie hinzuf&uuml;gen</TITLE>
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

echo "
<body>
<DIV Class='klein'>
	<div id='page'>
	
		<div id='head'>
			pic2base :: Unterkategorie anlegen
		</div>
		
		<div id='navi'>
			<div class='menucontainer'>
			Text
			</div>
		</div>
		
		<div id='content'>
			<center>";
	
			$res0 = mysql_query( "SELECT * FROM $table4 WHERE kategorie='$kategorie' AND parent='$parent' AND level='$level'");
			IF (mysql_num_rows($res0) > 0)
			{
				echo "<p class='zwoelfred' style='margin-top: 150px'>Die Unterkategorie ist bereits vorhanden<BR>und wird daher nicht noch einmal angelegt!<BR><BR>
				Bitte einen Moment Geduld...</p><BR>
				</center>
				</div>
		
				<div id='foot'>
					<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
				</div>
	
				</div>
				</DIV>
				<meta http-equiv='Refresh' content='3, URL=kategorie0.php?kat_id=$kat_id&ID=$ID'>
				</body>
				</html>";
				return;
			}
			$res1 = mysql_query( "INSERT INTO $table4 (kategorie, parent, level) VALUES ('$kategorie', '$parent', '$level')");
			echo mysql_error();
			$kat_id = mysql_insert_id();	
			$res2 = mysql_query( "INSERT INTO $table11 (kat_id, info) VALUES ('$kat_id', '')");
			
			echo "<meta http-equiv='Refresh' content='0, URL=kategorie0.php?kat_id=$kat_id&ID=$ID'>
			</center>
		</div>
		
		<div id='foot'>
			<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
		</div>
	
	</div>
</DIV>";

mysql_close($conn);
?>
</BODY>
</HTML>