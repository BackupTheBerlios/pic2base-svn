<?php
IF (!$_COOKIE['login'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
	header('Location: ../../../index.php');
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Startseite</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?php
unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = preg_split('#,#',$_COOKIE['login']);
//echo $c_username;
}

// fuer register_globals = off
$benutzername = $_POST['benutzername'];
$vorname = $_POST['vorname'];
$name = $_POST['name'];
$pwd = $_POST['pwd'];
$titel = $_POST['titel'];
$strasse = $_POST['strasse'];
$plz = $_POST['plz'];
$ort = $_POST['ort'];
$group = $_POST['group'];
//$berechtigung = $_POST['group'];
$telefon = $_POST['telefon'];
$email = $_POST['email'];
$internet = $_POST['internet'];
$language = $_POST['language'];

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';

?>

<div class="page">

	<p id="kopf">pic2base :: Admin-Bereich - Neuanlage eines Users</p>

	<div class="navi" style="clear:right;">
		<div class="menucontainer">
		<?php
		  include "adminnavigation.php";
		?>
		</div>
	</div>

	<div class="content">
		<p style="margin:70px 0px; text-align:center">
		<?php
		//Pruefung der Eingaben:
		IF(($benutzername !==  '') AND ($name !== '') AND ($vorname !== '') AND ($pwd !== ''))
		{
			$ben_name = trim($benutzername);
			$ben_name = strip_tags($ben_name);
			$ben_name = stripslashes($ben_name);
			$ben_name = stripcslashes($ben_name);
			//echo $ben_name."<BR>";
			
			$array_1 = array('utf8_decode(ä)', 'utf8_decode(ö)', 'utf8_decode(ü)', 'utf8_decode(Ä)', 'utf8_decode(Ö)', 'utf8_decode(Ü)', 'utf8_decode(ß)', '/', ' ');	//moegliche Vorkommen
			$array_2 = array('?', '?', '?', '?', '?', '?', '?', '?', '?');	//deren Ersetzungen
			
			for($x = 0; $x < count($array_1); $x++)
			{
				$ben_name = str_replace($array_1[$x], $array_2[$x], $ben_name);
			}
			
			IF(($ben_name == $benutzername) AND (strlen($ben_name) < 15))
			{
				//echo "G &Uuml; L T I G E R Name (".$ben_name.")<BR>";
				//echo $user_dir."<BR>".$up_dir."<BR>".$down_dir."<BR>".$kml_dir."<BR>";
				$key = '0815';
				$ftp_passwd = crypt($pwd);
				//Ermittlung der Apache-UID/GID:
				$result0 = mysql_query("SELECT apache_uid, apache_gid FROM $table16");
				$apache_uid = mysql_result($result0, isset($i0), 'apache_uid');
				$apache_gid = mysql_result($result0, isset($i0), 'apache_gid');
				//Benutzerdaten erfassen:
				$result1 = mysql_query( "INSERT INTO $table1 (username, titel, name, vorname, strasse, plz, ort, pwd, ftp_passwd, tel, email, internet, language, uid, gid, aktiv, user_dir, up_dir, down_dir, group_id) VALUES ('$benutzername', '$titel', '$name', '$vorname', '$strasse', '$plz', '$ort', ENCRYPT('$pwd','$key'), '$ftp_passwd', '$telefon', '$email', '$internet', '$language', '$apache_uid', '$apache_gid', '1', '$user_dir', '$up_dir', '$down_dir', '$group')");
				echo mysql_error();
				$result4 = mysql_query( "SELECT * FROM $table1 WHERE username = '$benutzername' AND name = '$name' AND vorname = '$vorname'");
				$user_id = mysql_result($result4, isset($i4), 'id');
				//Benutzerrechte eintragen:
				$result2 = mysql_query( "SELECT * FROM $table6 WHERE group_id = '$group' AND enabled = '1'");
				$num2 = mysql_num_rows($result2);
				//echo $num2."<BR>";
				FOR($i2=0; $i2<$num2; $i2++)
				{
					$perm_id = mysql_result($result2, $i2, 'permission_id');
					$result3 = mysql_query( "INSERT INTO $table7 (user_id, permission_id, enabled) VALUES ('$user_id', '$perm_id', '1')");
				}
				echo mysql_error()."<BR>";
				
				//anlegen des User-Ordners:
				clearstatcache();
				if(!file_exists($user_dir) AND mysql_error() == '')
				{
					mkdir($user_dir);
					chmod($user_dir,0700);
					clearstatcache();
				}
				ELSE
				{
					echo "Das User-Verzeichnis ist bereits vorhanden und/oder bei der Daten&uuml;bernahme ist ein Fehler aufgetreten!<BR><BR>
					<input type='button' value='Zur&uuml;ck' OnClick='location.href=\"adminframe.php?item=adminadduser\"'>";
					return;
				}
				
				//anlegen des User-Upload-Ordners:
				clearstatcache();
				if(!file_exists($up_dir) AND mysql_error() == '')
				{
					mkdir($up_dir);
					chmod($up_dir,0777);
					clearstatcache();
				}
				ELSE
				{
					echo "Das Upload-Verzeichnis ist bereits vorhanden und/oder bei der Daten&uuml;bernahme ist ein Fehler aufgetreten!<BR><BR>
					<input type='button' value='Zur&uuml;ck' OnClick='location.href=\"adminframe.php?item=adminadduser\"'>";
					return;
				}
				//anlegen des User-Download-Ordners:
				clearstatcache();
				if(!file_exists($down_dir) AND mysql_error() == '')
				{
					mkdir($down_dir);
					chmod($down_dir,0777);
					clearstatcache();
				}
				ELSE
				{
					echo "Das Download-Verzeichnis ist bereits vorhanden und/oder bei der Daten&uuml;bernahme ist ein Fehler aufgetreten!<BR><BR>
					<input type='button' value='Zur&uuml;ck' OnClick='location.href=\"adminframe.php?item=adminadduser\"'>";
					return;
				}
				
				//anlegen des User-kml-Ordners:
				clearstatcache();
				if(!file_exists($kml_dir) AND mysql_error() == '')
				{
					mkdir($kml_dir);
					chmod($kml_dir,0777);
					clearstatcache();
				}
				ELSE
				{
					echo "Das kml-Verzeichnis ist bereits vorhanden und/oder bei der Daten&uuml;bernahme ist ein Fehler aufgetreten!<BR><BR>
					<input type='button' value='Zur&uuml;ck' OnClick='location.href=\"adminframe.php?item=adminadduser\"'>";
					return;
				}
			}
			ELSE
			{
				echo "Der Benutzername ist ung&uuml;ltig.<BR>Hinweis:<BR>Der Benutzername darf keine Leerzeichen oder Sonderzeichen enthalten<BR>
				und darf h&uuml;chstens 15 Zeichen lang sein.!<BR><BR>
				<input type='button' value='Zur&uuml;ck' OnClick='javasript:history.back()'>";
				return;
			}
			//wenn der neue User erfolgreich angelegt wurde wird kontrolliert, ob mind. ein User mit Admin-Rechten existiert.
			//Wenn ja, wird der User pb inaktiv gesetzt (Sicherheit!)
			$result3 = mysql_query("SELECT $table9.id, $table9.description, $table1.group_id 
			FROM $table9, $table1  
			WHERE $table9.description = 'Admin'
			AND $table9.id = $table1.group_id");
			$num3 = mysql_num_rows($result3);
			IF($num3 > 0)
			{
				$result4 = mysql_query("UPDATE $table1 SET aktiv = '0' WHERE username = 'pb'");
			}
			//echo "Es gibt derzeit ".$num3." Benutzer mit Admin-Rechten.<BR>";
			echo "Benutzer wurde erfolgreich angelegt.<BR><BR>
			<input type='button' value='Zur&uuml;ck' OnClick='location.href=\"adminframe.php?item=adminadduser\"'>";
			
			//log-file schreiben:
			$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
			fwrite($fh,date('d.m.Y H:i:s').": Neuer Benutzer ".$ben_name." wurde von ".$c_username." angelegt. (Zugriff von ".$_SERVER['REMOTE_ADDR'].")\n");
			fclose($fh);
		}
		ELSE
		{
			echo "Bitte &uuml;berpr&uuml;fen Sie Ihre Eingaben.<BR>
			Die mit (*) gekennzeichneten Felder M&Uuml;SSEN ausgef&uuml;llt werden!<BR><BR>
			<input type='button' value='Zur&uuml;ck' OnClick='javascript:history.back()'>";
		}
		?>
		</p>
	</div>
	<br style="clear:both;" />

	<p id="fuss"><?php echo $cr; ?></p>

</div>
</DIV></CENTER>
</BODY>
</HTML>