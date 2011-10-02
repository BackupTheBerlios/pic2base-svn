<?php
IF (!$_COOKIE['login'])
{
include '../../share/global_config.php';
//var_dump($sr);
  header('Location: ../../../index.php');
}

if(array_key_exists('id',$_GET))
{
	$id = $_GET['id']; 
}
else
{
	break;
}
//##################################################################################################
//Datei wird im Admin-Bereich zur nachtraeglichen Aenderung von Ortsnamen verwendet#################
//##################################################################################################
  mysql_connect ($db_server, $user, $PWD);
  $result = mysql_query("select * from $table2 WHERE pic_id = $id");
  $num = mysql_num_rows($result);
  //$loc_id = mysql_result ($result, $i, "loc_id");
  $city = mysql_result ($result, $i, "City");
  echo "
  <center>
  <FORM name='location' method='post' action='adminframe.php?item=admineditlocationnameaction&pic_id=$id'>
  <table class='normal' border='0'>
	<tr>
	<td colspan='4' style='font-size:12pt; text-align:center;'>Ortsnamen bearbeiten</td>
	</tr>
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='4'></TD>
	</TR>
	
	<tr>
	<td colspan='4'>&nbsp;</td>
	</tr>
	
  	<TR>
	<TD align='CENTER' colspan='4' style='padding-left:20px;'>
	<INPUT TYPE='text' name='locationname_new' VALUE=\"$city\" style='width:400px;'></INPUT>
	<INPUT TYPE='hidden' name='locationname' VALUE=\"$city\" style='width:400px;'></INPUT>
	</TD>
	</TR>
	
	<tr>
	<td colspan='4'>&nbsp;</td>
	</tr>
	
	<TR>
	<TD align='CENTER' colspan='4' style='padding-left:20px;'>
	<INPUT TYPE=submit VALUE='Neue Ortsbezeichnung speichern'>
	<!--<INPUT TYPE='button' VALUE='Abbrechen' onClick='location.href=\"adminframe.php?item=editlocationname\"'>-->
	
	<INPUT TYPE='button' VALUE='Abbrechen' onClick='javascript:history.back()'>	
	</TD>
	</TR>
	
	<tr>
	<td colspan='4'>&nbsp;</td>
	</tr>
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='4'></TD>
	</TR>
  
  	<TR>
	<TD align='center' colspan = '4'></TD>
	</TR>
	</table>
	</FORM>
</center>";
?>
<script language="javascript">
document.location.locationname.focus();
</script>