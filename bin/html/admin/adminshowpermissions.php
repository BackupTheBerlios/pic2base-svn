
<?
	mysql_connect ($db_server, $user, $PWD);
	$result = mysql_query("select * from permissions ORDER BY description");
	$num = mysql_num_rows($result);
	echo "
	<center>
	<table class='normal'>
	<tr>
	<td colspan='4' style='font-size:12pt; text-align:center;'>Berechtigungen</td>
	</tr>
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='4'></TD>
	</TR>
	
	<tr>
	<td colspan='4'>&nbsp;</td>
	</tr>
	
	<tr>
	<td align=left><b>Bezeichnung</b></td>
	<td align=left width=200><b>Kurzbezeichnung</b></td>
	</tr>";
	for ($i = 0; $i < $num; $i++)
	{
	  echo "<tr>";
	  echo "<td align=left>".mysql_result ($result, $i, "description")."</td>";
	  echo "<td align=left>".mysql_result ($result, $i, "shortdescription")."</td>";
	  if (hasPermission($c_username, 'adminlogin'))
	  {
	  	echo "<td align=left width=100>Ändern</td>";
	  }
	  if (hasPermission($c_username, 'adminlogin'))
	  {
	  	echo "<td align=left>Löschen</td>";
	  }
	  echo "</tr>";
	}
	echo "
	<tr>
	<td colspan='4'>&nbsp;</td>
	</tr>
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='4'></TD>
	</TR>
	</table></center>";
?>