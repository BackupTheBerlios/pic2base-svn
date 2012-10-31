<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
	header('Location: ../../../index.php');
}
else
{
	$uid = $_COOKIE['uid'];
}

// abzuarbeitende Schritte:
// aus Tabelle pictures alle Bilder des alten Users auf neuen User uebertragen
// aus Tabelle users den entsprechenden User loeschen
// aus Tabelle userpermissions alle Eintraege des betreffenden Users loeschen
// Userverzeichnis loeschen
// kontrollieren, ob mind. noch ein Admin im System ist, anderenfalls pb wieder aktiv setzen
$users = $_POST['users'];
$id = $_GET['id'];

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');

//echo "User-ID: ".$users;
if (hasPermission($uid, 'adminlogin', $sr) AND $users !== '')
{
	$result0 = mysql_query( "SELECT username FROM $table1 WHERE id = '$id'");
	$benutzername = mysql_result($result0, isset($i0), 'username');
	//echo $ftp_path."/".$benutzername;
	
	$result = mysql_query("UPDATE $table2 SET Owner = '$users' WHERE Owner = '$id'");
	echo "&Auml;ndere in pictures den Owner von $id auf $users<BR>";
	$result2 = mysql_query( "DELETE FROM $table1 WHERE id = '$id'");
	echo "L&ouml;scha alle Eintr&auml;ge des Users $id aus der Users-Tabelle.<BR>";
	$result3 = mysql_query( "DELETE FROM $table7 WHERE user_id = '$id'");
	echo "L&ouml;sche aus Userrechte alles f&uuml;r User $id";
	
	$user_dir = $ftp_path."/".$id;
	$command = "rm -r ".$user_dir;
	shell_exec($command." > /dev/null &");

	$result4 = mysql_query("SELECT $table9.id, $table9.description, $table1.group_id 
	FROM $table9, $table1  
	WHERE $table9.description = 'Admin'
	AND $table9.id = $table1.group_id");
	$num4 = mysql_num_rows($result4);
	IF($num4 == 0)
	{
		$result5 = mysql_query("UPDATE $table1 SET aktiv = '1' WHERE username = 'pb'");
	}
	
	IF(mysql_error() == '')
	{
		//log-file schreiben:
		$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
		fwrite($fh,date('d.m.Y H:i:s').": Benutzer ".$benutzername." wurde von ".$username." geloescht. (Zugriff von ".$_SERVER['REMOTE_ADDR'].")\n");
		fclose($fh);
		echo "<meta http-equiv='Refresh' content='2; URL=adminframe.php?item=adminshowusers'>";
	}
	ELSE
	{
		echo "Es trat ein Fehler auf: ".mysql_error();
	}
}
ELSE
{
	echo "Bitte w&auml;hlen Sie einen neuen Eigent&uuml;mer f&uuml;r die Bilder aus!<BR><BR>
	<input type='button' Value='Zur&uuml;ck' OnClick='location.href=\"adminframe.php?item=adminshowuser&id=$id&del=1\"'>";
}
?>
