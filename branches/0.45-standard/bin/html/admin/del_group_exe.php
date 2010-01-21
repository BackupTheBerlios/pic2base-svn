<?
  mysql_connect ($db_server, $user, $PWD);
  mysql_select_db($db);
  $group_id = $_GET['group_id'];
  
  $result1 = mysql_query( "DELETE FROM $table9 WHERE id = $group_id");
  echo mysql_error();
  
  	echo "<center>
  	<table class='normal' border='0'>
  	
  	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='4'></TD>
	</TR>
	
	<tr>
	<td colspan='4'>&nbsp;</td>
	</tr>
  	
	<tr>
	<td colspan='4' style='font-size:12pt; text-align:center;'><font color='red'><b>Gruppe wurde gel&ouml;scht.</b></font></td>
	</tr>
	
	<tr>
	<td colspan='4'>&nbsp;</td>
	</tr>
	
	<tr>
	<td colspan='4' align='center'><input type='button' value='Zur&uuml;ck' style='width:100px'; onClick='location.href=\"adminframe.php?item=adminshowgroups\"'></td>
	</tr>
	
	<tr>
	<td colspan='4'>&nbsp;</td>
	</tr>
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='4'></TD>
	</TR>

	</table>
	</center>";
?>