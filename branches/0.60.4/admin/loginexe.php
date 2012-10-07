<?php
  mysql_connect ($db_host, $db_username, $db_password);
  $result = mysql_query("select * from users WHERE name='".$username."' AND password='".$pass."'");
  echo mysql_error();
  if (mysql_num_rows($result) == 1)
  {
    if (hasPermission(mysql_result ($result, 0, "id"), "adminlogin", ""))
    {
      echo "Login erfolgreich! <a href='index.php?item=showusers'>Hier gehts weiter.</a>";
    } 
    else
    {
      echo "Der Benutzer ist nicht berechtig, sich in den Admin-Bereich einzuloggen!";
    }
  } 
  else
  {
    echo "Login fehlgeschlagen!";
  }
?>