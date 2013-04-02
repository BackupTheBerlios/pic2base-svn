<html>
<head>
<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href='../../css/format2.css'>
<script language="JavaScript" src="../../share/functions/resize_elements.js"></script>
<script language="JavaScript" src="../../share/functions/jquery-1.8.2.min.js"></script>
<script language="JavaScript">
	jQuery.noConflict();
	jQuery(document).ready(checkWindowSize);
	jQuery(window).resize(checkWindowSize);
</script>
</head>
<body>
<?php

IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../index.php');
}
else
{
	$uid = $_COOKIE['uid'];
}

include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/permissions.php';

echo "
<fieldset style='background-color:none; margin-top:10px;'>
	<legend style='color:blue; font-weight:bold;'>Anlage einer neuen Kollektion</legend>
	<div id='scrollbox2' style='overflow-y:scroll;'>
		<center>
		<form name='new_collection' method='post' action='../../share/save_new_collection.php'>
			<table class='coll' border='0'>
				
				<TR class='coll' style='height:3px;'>
					<TD class='coll' style='background-color:darkred;' colspan = '5'></TD>
				</TR>
				
				<TR class='coll' style='height:10px;'>
					<TD class='coll' colspan = '5'></TD>
				</TR>
				
				<tr>
					<td colspan='2' class='coll'>Name der Kollektion:</td>
					<td colspan='3' style='text-align:center;' class='coll'><input type='text' name='coll_name' style='width:500px;'></td>
				</tr>
				
				<TR class='coll' style='height:10px;'>
					<TD class='coll' colspan = '5'></TD>
				</TR>
				
				<tr>
					<td colspan='2'  class='coll'>Beschreibung:</td>
					<td colspan='3' style='text-align:center;' class='coll'><textarea name='coll_description' rows='5' style='height:100px; width:500px;'></textarea></td>
				</tr>
				
				<TR class='coll' style='height:10px;'>
					<TD class='coll' colspan = '5'></TD>
				</TR>
				
				<tr>
					<td colspan='2'></td>
					<td colspan='3' style='text-align:center;' class='coll'><input type='submit' value='Speichern'><input type='button' value='Abbrechen' onClick='location.href=\"javascript:history.back()\"'></td>
				</tr>
				
				<TR class='coll' style='height:10px;'>
					<TD class='coll' colspan = '5'></TD>
				</TR>
				
				<TR class='coll' style='height:3px;'>
					<TD class='coll' style='background-color:darkred;' colspan = '5'></TD>
				</TR>
			
			</table>
		</form>
		</center>
	</div>
</fieldset>";
?>
</body>
</html>