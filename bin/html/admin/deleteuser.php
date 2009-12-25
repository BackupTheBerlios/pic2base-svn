<?php
// abzuarbeitende Schritte:
// aus Tabelle pictures alle bilder des alten Users auf neuen User übertragen
// aus Tabelle users den entsprechenden User löschen
// aus Tabelle userpermissions alle Einträge des betreffenden Users löschen
// Userverzeichnis löschen
//
$users = $_POST['users']; // für register_globals = off
$id = $_GET['id']; // für register_globals = off

//echo "User-ID: ".$users;
if (hasPermission($c_username, 'adminlogin') AND $users !== '')
{
	
	
	mysql_connect ($db_server, $user, $PWD);
	$result0 = mysql_query( "SELECT username FROM $table1 WHERE id = '$id'");
	$benutzername = mysql_result($result0, isset($i0), 'username');
	//echo $ftp_path."/".$benutzername;
	
	$result = mysql_query("UPDATE $table2 SET Owner = '$users' WHERE Owner = '$id'");
	echo "&Auml;ndere in pictures den Owner von $id auf $users<BR>";
	$result2 = mysql_query( "DELETE FROM $table1 WHERE id = '$id'");
	echo "L&ouml;scha alle Eintr&auml;ge des Users $id aus der Users-Tabelle.<BR>";
	$result3 = mysql_query( "DELETE FROM $table7 WHERE user_id = '$id'");
	echo "L&ouml;sche aus Userrechte alles f&uuml;r User $id";
	
	$user_dir = $ftp_path."/".$benutzername;
	$command = "rm -r ".$user_dir;
	shell_exec($command);

	IF(mysql_error() == '')
	{
		echo "<meta http-equiv='Refresh' content='5; URL=adminframe.php?item=adminshowusers'>";
	}
	
}
ELSE
{
	echo "Bitte w&auml;hlen Sie einen neuen Eigent&uuml;mer f&uuml;r die Bilder aus!<BR><BR>
	<input type='button' Value='Zur&uuml;ck' OnClick='location.href=\"adminframe.php?item=adminshowuser&id=$id&del=1\"'>";
}
?>
