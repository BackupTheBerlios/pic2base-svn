<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Kollektion l&ouml;schen</title>
  <meta name="GENERATOR" content="eclipse">
  <meta name="AUTHOR" content="k. henneberg">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel=stylesheet type='text/css' href='../css/format2.css'>
  <link rel="shortcut icon" href="../share/images/favicon.ico">
  <script language="JavaScript" src="../share/functions/resize_elements.js"></script>
  <script language="JavaScript" src="../share/functions/jquery-1.8.2.min.js"></script>
  <script language="JavaScript">
  	jQuery.noConflict();
	jQuery(document).ready(checkWindowSize);
	jQuery(window).resize(checkWindowSize); 
  </script>
</head>

<body>

<?php

/*
 * Project: pic2base
 * File: del_coll_action.php
 * Skript zum loeschen einer Kollektion
 * Copyright (c) 2013 Klaus Henneberg
 *
 * Project owner:
 * Klaus Henneberg
 * Finkenweg 18
 * 38889 Blankenburg, BRD
 *
 * All files of this project are licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 */

if(array_key_exists('coll_id', $_GET))
{
	$coll_id = $_GET['coll_id'];
}
else
{
	?>
	<script type="text/javascript">
	alert("Es wurde keine Kollektion ausgewählt!");
	location.href='../html/edit/edit_collection.php';
	</script>
	<?php 
}

include '../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/css/initial_layout_settings.php';

echo "
<DIV Class='klein'>
	<div id='page'>
	
		<div id='head'>
			pic2base :: Kollektion l&ouml;schen
		</div>
		
		<div id='navi'>
			<div class='menucontainer'>
			</div>
		</div>
		
		<div id='content'>
			<fieldset style='background-color:none; margin-top:10px;'>
			<legend style='color:blue; font-weight:bold;'>Kollektion wird gel&ouml;scht...</legend>
			<div id='scrollbox2' style='overflow-y:scroll;'>";
				//echo $coll_id;
				$result1 = mysql_query("DELETE FROM $table24 WHERE coll_id = '$coll_id' LIMIT 1");	//echo mysql_error();
				if(mysql_error() == "")
				{
					echo "<center><p style='margin-top:50px;'>Die Kollektion wurde entfernt.</p></center>";
					?>
					<script type="text/javascript">
					alert("Die Kollektion wurde entfernt.");
					location.href='../html/edit/edit_collection.php';
					</script>
					<?php 
				}
				else
				{
					echo "<center><p style='margin-top:50px;'>Es trat der folgende Fehler auf:<BR><BR>";
					echo mysql_error();
					echo "<BR><BR>Die Kollektion wurde NICHT entfernt.<BR>
					Bitte informieren Sie Ihren Administrator.<BR><BR>
					<input type='button' value='Zur&uuml;ck zur Kollektions-Auswahl' onClick='location.href=\"../html/edit/edit_collection.php\"'></p></center>";
				}
			echo "
			</div>
			</fieldset>
		</div>
		
		<div id='foot'>
			<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
		</div>
	
	</div>
</DIV>";
?>

</body>
</html>
