<?php
  echo "<CENTER>";
//var_dump($_GET);
  $id = $_GET['id']; // f�r register_globals = off
  
  mysql_connect ($db_server, $user, $PWD);
  mysql_select_db($db);
  $result = mysql_query("select * from $table9 WHERE id=".$id); //usergroups
  if (mysql_num_rows($result) == 1)
  {
    $desc = mysql_result ($result, 0, "description");
  }
  else
  {
    $desc = "[keine Gruppe gew&auml;hlt]";
  }
  if (hasPermission($c_username, 'adminlogin'))
  {
	echo "<table class='normal'>
	<tr>
	<td colspan='3' style='font-size:12pt; text-align:center;'>Benutzergruppe ".$desc."</td>
	</tr>
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='3'></TD>
	</TR>
		
	<tr>
	<td align=left colspan='3'>Berechtigungen:</td>
	</TR>
	
	<tr>
	<td width=250 align=left><b>Parameter</b></td>
	<td width=150 align=left><b>Erlaubnis</b></td>";
	if (hasPermission($c_username, 'adminlogin'))
  	{
  	  echo "<td width=100></td>";
  	}
  	echo "</tr>";

	$result = mysql_query("select * from permissions ORDER BY description");
	$num = mysql_num_rows($result);
	for ($i = 0; $i < $num; $i++)
	{
		echo "<tr>
		<td align=left>".mysql_result($result, $i, "description")."</td>";
		$result2 = mysql_query("select * from grouppermissions WHERE group_id=".$id." AND permission_id=".mysql_result($result, $i, "id"));
		if (mysql_num_rows($result2) == 1)
		{
			if (mysql_result($result2, 0, "enabled") == "1")
			{
				echo "<td align=left>Ja</td>";
			}
			else
			{
				echo "<td align=left>Nein</td>";
			}
		} 
		else
		{
			echo "<td align=left>Nein</td>";
		}
		if (hasPermission($c_username, 'adminlogin'))
		{
			echo "<td><a href=adminchangegrouppermissionflag.php?group_id=".$id."&permission_id=".mysql_result($result, $i, "id").">&Auml;ndern</a></td>";
		}
		echo "</tr>";
    	}
	echo "<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='3'></TD>
	</TR>";
  }


  if (hasPermission($c_username, 'showusers'))
  {
	echo "<TR><TD colspan='3'>Benutzer in dieser Gruppe:</TD></TR>";
	$result = mysql_query("select * from users WHERE group_id='".$id."' ORDER BY id");
	$num = mysql_num_rows($result);
	echo "<TR><TD colspan='3'>";
	for ($i = 0; $i < $num; $i++)
	{
		if ($i == 0)
		{
			echo mysql_result($result, $i, "username");
		} 
		else
		{
			echo ", ".mysql_result($result, $i, "username");
		}
	}
	echo "</TD></TR>";
  }
  echo "</TABLE>";
?>