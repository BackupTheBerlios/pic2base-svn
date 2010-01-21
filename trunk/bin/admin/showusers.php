<center><h2>Benutzer</h2></center>
<?
  mysql_connect ($db_host, $db_username, $db_password);
  $result = mysql_query("select * from users ORDER BY name");
  $num = mysql_num_rows($result);
  echo "<center><table><tr><td>Benutzername</td><td>Gruppe</td></tr>";
  for ($i = 0; $i < $num; $i++)
  {
    echo "<tr>";
    echo "<td><a href=index.php?item=showusergroup&id=".mysql_result ($result, $i, "id").">".mysql_result ($result, $i, "name")."</a></td>";
    $result2 = mysql_query("select * from usergroups where id=".mysql_result ($result, $i, "group_id"));
    if (mysql_num_rows($result2) == 1)
    {
      echo "<td><a href=index.php?item=showgrouppermissions&id=".mysql_result ($result2, 0, "id").">".mysql_result ($result2, 0, "description")."</a></td>";
    }
    echo "</tr>";
  }
  echo "</table></center>";
?>