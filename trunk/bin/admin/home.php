<center><h2>Home</h2></center>
<?
  if ($usename == "")
  {
    echo "<form method=post action='index.php?item=loginexe'><table><tr><td>Benutzername:</td><td><input type=text name=username></td></tr><tr><td>Passwort:</td><td><input type=password name=pass></td></tr><tr><td><input type=submit value='Einloggen'></td></tr></table>";
  } else
  {
    echo "<a href='index.php?item=showusers'>Alle Benutzer anzeigen</a>";
  }
?>
