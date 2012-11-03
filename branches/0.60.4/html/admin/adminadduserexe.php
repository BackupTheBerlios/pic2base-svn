<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
	header('Location: ../../../index.php');
}
else
{
	unset ($uid);
	$bearbeiter_id = $_COOKIE['uid'];	//hier anders genannt, um Verwechslungen mit dem anzulegenden User auszuschliessen!
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
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
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$bearbeiter_id' AND aktiv = '1'");
$bearbeitername = mysql_result($result0, isset($i0), 'username');		//Benutzername des angemeldeten Benutzers (Bearbeiters)

// fuer register_globals = off
$benutzername = $_POST['benutzername'];									//Benutzername des neu anzulegenden Benutzers
$vorname = $_POST['vorname'];
$name = $_POST['name'];
$pwd = $_POST['pwd'];
$titel = $_POST['titel'];
$strasse = $_POST['strasse'];
$plz = $_POST['plz'];
$ort = $_POST['ort'];
$group = $_POST['group'];
$telefon = $_POST['telefon'];
$email = $_POST['email'];
$internet = $_POST['internet'];
$language = $_POST['language'];

echo "

<div class='page'>

	<p id='kopf'>pic2base :: Admin-Bereich - Neuanlage eines Users</p>

	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
		  include "adminnavigation.php";
		echo "
		</div>
	</div>

	<div class='content'>
		<p style='margin:70px 0px; text-align:center'>";
		//Pruefung der Eingaben:
		IF(($benutzername !==  '') AND ($name !== '') AND ($vorname !== '') AND ($pwd !== ''))
		{
//			echo $benutzername;
			$ben_name = trim($benutzername);

			// einige Angaben muessen vor der Aufnahme in die DB utf8_decodiert werden:
			$ben_name = utf8_decode($ben_name);
			$vorname = utf8_decode($vorname); 
			$name = utf8_decode($name); 
			$pwd = utf8_decode($pwd);
			$strasse = utf8_decode($strasse);
			$ort = utf8_decode($ort);
			
			if(strlen($ben_name) < 15)
			{
				$ftp_passwd = crypt($pwd);
				//Ermittlung der Apache-UID/GID:
				$result0 = mysql_query("SELECT apache_uid, apache_gid FROM $table16");
				$apache_uid = mysql_result($result0, isset($i0), 'apache_uid');
				$apache_gid = mysql_result($result0, isset($i0), 'apache_gid');
				//Benutzerdaten erfassen:
				//Zuerst muss ein dummy-Datensatz erzeugt werden, anhand dessen die neue Benutzer-ID ermittelt werden kann.
				//Erst dann koennen die Benutzerverzeichnisse angelegt werden:
				$result1 = mysql_query( "INSERT INTO $table1 (username, titel, name, vorname, strasse, plz, ort, pwd, ftp_passwd, tel, email, internet, language, uid, gid, aktiv, group_id) VALUES ('$ben_name', '$titel', '$name', '$vorname', '$strasse', '$plz', '$ort', ENCRYPT('$pwd','$key'), '$ftp_passwd', '$telefon', '$email', '$internet', '$language', '$apache_uid', '$apache_gid', '1', '$group')");
				echo mysql_error();	//echo "<BR>Ben-Name: ".$ben_name."<BR>";
				$result4 = mysql_query( "SELECT * FROM $table1 WHERE username = '$ben_name' AND name = '$name' AND vorname = '$vorname'");
				echo mysql_error();
				$new_uid = mysql_result($result4, isset($i4), 'id'); //echo "Neue UID: ".$new_uid."<BR>";
				// Benutzerverzeichnisse anlegen:
				$user_dir = $ftp_path.'/'.$new_uid;
				$up_dir = $ftp_path.'/'.$new_uid.'/uploads'; //echo $up_dir;
				$down_dir = $ftp_path.'/'.$new_uid.'/downloads';
				$kml_dir = $ftp_path.'/'.$new_uid.'/kml_files';
				$result1_1 = mysql_query("UPDATE $table1 SET user_dir = '$user_dir', up_dir = '$up_dir', down_dir = '$down_dir' WHERE id = '$new_uid'");
				//Benutzerrechte eintragen:
				$result2 = mysql_query( "SELECT * FROM $table6 WHERE group_id = '$group' AND enabled = '1'");
				$num2 = mysql_num_rows($result2);
				//echo $num2."<BR>";
				FOR($i2=0; $i2<$num2; $i2++)
				{
					$perm_id = mysql_result($result2, $i2, 'permission_id');
					$result3 = mysql_query( "INSERT INTO $table7 (user_id, permission_id, enabled) VALUES ('$new_uid', '$perm_id', '1')");
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
				echo "Der Benutzername ist ung&uuml;ltig.<BR>Hinweis:<BR>Der Benutzername darf h&uuml;chstens 15 Zeichen lang sein.!<BR><BR>
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
			<input type='button' id='back' value='Zur&uuml;ck' OnClick='location.href=\"adminframe.php?item=adminadduser\"'>";
			?>
			<script type='text/javascript'>
			document.getElementById('back').focus();
			</script>
			<?php
			//log-file schreiben:
			$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
			fwrite($fh,date('d.m.Y H:i:s').": Neuer Benutzer ".$ben_name." wurde von ".$bearbeitername." angelegt. (Zugriff von ".$_SERVER['REMOTE_ADDR'].")\n");
			fclose($fh);
		}
		ELSE
		{
			echo "Bitte &uuml;berpr&uuml;fen Sie Ihre Eingaben.<BR>
			Die mit (*) gekennzeichneten Felder M&Uuml;SSEN ausgef&uuml;llt werden!<BR><BR>
			<input type='button' value='Zur&uuml;ck' OnClick='javascript:history.back()'>";
		}
		echo "
		</p>
	</div>
	<br style='clear:both;' />

	<p id='fuss'>".$cr."</p>

</div>
</DIV></CENTER>
</BODY>
</HTML>";

?>