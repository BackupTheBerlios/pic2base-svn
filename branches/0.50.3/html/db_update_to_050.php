<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Update-Routine</title>
</head>
<body>
<?php
echo "<CENTER>
<p style='margin-top:100px;'>Sie beabsichtigen, das Update Ihrer pic2base-Datenbank von Version 0.42 auf 0.50.0 durchzuf&uuml;hren.<BR><BR>
Wenn Sie sicher sind, tragen Sie hier den Benutzernamen und das Passwort<BR>
eines Benutzers mit Admin-Rechten auf Ihrer MySQL-Datenbank ein<BR>
und klicken dann auf \"Update starten\":<BR><BR>
<b>Das Update kann - je nach Datenbestand - mehrere Stunden in Anspruch nehmen<BR>
und darf nicht unterbrochen werden!<BR><BR>
<b>Legen Sie vor dem Update unbedingt eine Sicherung Ihres Datenbestandes an!</b><BR><BR>
<FORM name='zugang' method='post' action='db_update_to_050_action.php'>
<TABLE>
<TR>
<TD>Benutzername:</TD><TD><input type = 'text' name = 'user'></TD>
</TR>

<TR>
<TD>Passwort:</TD><TD><input type = 'password' name = 'pwd'></TD>
</TR>

<TR>
<TD><input type = 'submit' value = 'Update starten'></TD>
<TD><input type = 'button' value = 'Update abbrechen' onClick='javascript:history.back()'></TD>
</TR>
</TABLE>
</p>
</CENTER>";
?>
</body>
</html>