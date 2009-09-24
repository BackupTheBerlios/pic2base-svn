<?
  mysql_connect ($db_server, $user, $PWD);
  $result = mysql ($db,"select * from $table9 ORDER BY description");
  $num = mysql_num_rows($result);
  echo "<center><table class='normal' border='0'>
	<tr>
	<td colspan='4' style='font-size:12pt; text-align:center;'>&Uuml;bersicht &uuml;ber alle verf&uuml;gbaren Benutzer-Gruppen</td>
	</tr>
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='4'></TD>
	</TR>
	
	<tr>
	<td colspan='4'>&nbsp;</td>
	</tr>";
	
  FOR ($i = 0; $i < $num; $i++)
  {
	$group_id = mysql_result ($result, $i, "id");
	$group_desc = mysql_result ($result, $i, "description");
	
	echo "<tr>
	<td align='center' colspan='2'><a href=adminframe.php?item=adminshowgroup&id=".$group_id.">".$group_desc."</a></td>
	<TD align='center' colspan = '2'><input type=button value='Gruppe l&ouml;schen' onClick='location.href=\"adminframe.php?item=del_group&group_id=$group_id\"'></TD>
	</tr>";
  }
  
  echo "<tr>
	<td colspan='4'>&nbsp;</td>
	</tr>
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='4'></TD>
	</TR>
  
  	<TR>
	<TD align='center' colspan = '4'>Klicken Sie auf einen Gruppen-Namen um deren Eigenschaften zu &auml;ndern</TD>
	</TR>
	</table></center>";
?>