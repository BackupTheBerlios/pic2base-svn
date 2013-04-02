<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel=stylesheet type="text/css" href='../css/format1.css'>
		<link rel="shortcut icon" href="../share/images/favicon.ico">
		<title>DB-Update-Routine auf Version 0.90.0</title>
	</head>
	<body style='background-color:lightyellow;'>
	<?php
	echo "
	<CENTER>
		<fieldset style='width:700px; background-color:yellow; margin-top:50px;'>
		<legend style='color:blue; font-weight:bold;'>Update-Hinweis</legend>
			<p style='margin-top:20px;'>Sie beabsichtigen, das Update Ihrer pic2base-Datenbank von Version 0.80.0 auf 0.90.0 durchzuf&uuml;hren.<BR><BR>
				Wenn Sie sicher sind, tragen Sie unten den Benutzernamen und das Passwort<BR>
				eines Benutzers mit Admin-Rechten auf Ihrer MySQL-Datenbank ein<BR>
				und klicken dann auf \"Update starten\":<BR><BR>
				<b>Das Update kann - je nach Datenbestand und Rechnerleistung - einige Zeit in Anspruch nehmen<BR>
				und darf nicht unterbrochen werden!<BR><BR>
				<b style='color:red;'>Legen Sie vor dem Update unbedingt eine Sicherung der Datenbank und Ihres Datenbestandes an!</b><BR><BR>
				<FORM name='zugang' method='post' action='db_update_0800_to_0900_action.php'>
					<TABLE style='margin-bottom:20px;'>
						<TR>
							<TD>Benutzername:</TD><TD><input type = 'text' name = 'user' id='user'></TD>
						</TR>
					
						<TR>
							<TD>Passwort:</TD><TD><input type = 'password' name = 'pwd' id='pwd'></TD>
						</TR>
					
						<TR>
							<TD><input type = 'submit' value = 'Update starten'></TD>
							<TD><input type = 'button' value = 'Update abbrechen' onClick='javascript:history.back()'></TD>
						</TR>
					</TABLE>
				</FORM>
			</p>
		</fieldset>
	</CENTER>";
	?>
	<script type='text/javascript'>
		document.getElementById('user').focus();
	</script>
	</body>
</html>