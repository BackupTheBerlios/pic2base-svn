<center><h2>Benutzergruppe wählen</h2></center>
<form method='post' action=''>
<center><table>
<tr><td width=100>Benutzer:</td><td>
<?php
  mysql_connect ($db_host, $db_username, $db_password);
  $result = mysql_query("select * from users WHERE id=".$id);
  if (mysql_num_rows($result) == 1)
  {
    echo mysql_result ($result, 0, "name");
  } else
  {
    echo "[kein Benutzer gewählt]";
  }
?>
</td></tr>
<tr><td>Gruppe:</td><td>

</td></tr>
<tr><td> </td><td><input type=submit value="Ändern"></td></tr>
</table>
</form>
<?php
  include "showuserpermissions.php";
?>