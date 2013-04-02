<center><h2>Rechtevergabe f&uuml;r Benutzergruppe</h2></center>
<center>
Gruppe: 
<?php
  mysql_connect ($db_host, $db_username, $db_password);
  $result = mysql_query("select * from usergroups WHERE id=".$id);
  if (mysql_num_rows($result) == 1)
  {
    echo mysql_result ($result, 0, "description");
  } else
  {
    echo "[keine Gruppe gew&auml;hlt]";
  }
?>
<table><tr><td width=150>Parameter</td><td width=100 align=right>Erlaubnis</td></tr>
<?php
  $result = mysql_query("select * from permissions ORDER BY id");
  $num = mysql_num_rows($result);
  for ($i = 0; $i < $num; $i++)
  {
    echo "<tr>";
    echo "<td>".mysql_result($result, $i, "description")."</td>";
//    $result2 = mysql_query("select * from grouppermissions WHERE group_id=".$id." AND permission_id=".mysql_result($result, $i, "id"));
    $result2 = mysql_query("select * from grouppermissions WHERE group_id=".$id." AND permission_id=".mysql_result($result, $i, "id"));
    if (mysql_num_rows($result2) == 1)
    {
      if (mysql_result($result2, 0, "enabled") == "1")
      {
        echo "<td align=right>Ja</td>";
      } else
      {
        echo "<td align=right>Nein</td>";
      }
    } else
    {
      echo "<td align=right>Nein</td>";
    }
    echo "</tr>";
  }
?>
</table>