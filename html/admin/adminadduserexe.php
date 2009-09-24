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

<?

unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = split(',',$_COOKIE['login']);
//echo $c_username;
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';

?>

<div class="page">

	<p id="kopf">pic2base :: Admin-Bereich - Neuanlage eines Users</p>

	<div class="navi" style="clear:right;">
		<div class="menucontainer">
		<?
		  include "adminnavigation.php";
		?>
		</div>
	</div>

	<div class="content">
		<p style="margin:70px 0px; text-align:center">
		<?
		//Prüfung der Eingaben:
		IF(($benutzername !==  '') AND ($name !== '') AND ($vorname !== '') AND ($pwd !== ''))
		{
			$ben_name = trim($benutzername);
			$ben_name = strip_tags($ben_name);
			$ben_name = stripslashes($ben_name);
			$ben_name = stripcslashes($ben_name);
			//echo $ben_name."<BR>";
			
			$array_1 = array('ä', 'ö', 'ü', 'Ä', 'Ö', 'Ü', 'ß', '/', ' ');	//mögliche Vorkommen
			$array_2 = array('?', '?', '?', '?', '?', '?', '?', '?', '?');	//deren Ersetzungen
			
			for($x = 0; $x < count($array_1); $x++)
			{
				$ben_name = str_replace($array_1[$x], $array_2[$x], $ben_name);
			}
			
			IF(($ben_name == $benutzername) AND (strlen($ben_name) < 15))
			{
				//echo "G Ü L T I G E R Name (".$ben_name.")<BR>";
				/*
				$result1 = mysql($db, "SELECT max(uid) FROM $table1");
				$max_uid = mysql_result($result1, $i1, 'max(uid)');
				$uid = $max_uid + 1;
				//echo $uid."<BR>";
				$gid = '1000'; //entspricht der Systemgruppe 'ftpuser'
				*/
				//echo $user_dir."<BR>".$up_dir."<BR>".$down_dir."<BR>".$kml_dir."<BR>";
				$key = '0815';
				$ftp_passwd = crypt($pwd);
				//echo $ftp_passwd;
				//Benutzerdaten erfassen:
				$result1 = mysql($db, "INSERT INTO $table1 (username, titel, name, vorname, strasse, plz, ort, pwd, ftp_passwd, tel, email, internet, uid, gid, aktiv, user_dir, up_dir, down_dir, berechtigung, group_id) VALUES ('$benutzername', '$titel', '$name', '$vorname', '$strasse', '$plz', '$ort', ENCRYPT('$pwd','$key'), '$ftp_passwd', '$telefon', '$email', '$internet', '65534', '65534', '1', '$user_dir', '$up_dir', '$down_dir','$berechtigung', '$group')");
				echo mysql_error();
				$result4 = mysql($db, "SELECT * FROM $table1 WHERE username = '$benutzername' AND name = '$name' AND vorname = '$vorname'");
				$user_id = mysql_result($result4, $i4, 'id');
				//Benutzerrechte eintragen:
				$result2 = mysql($db, "SELECT * FROM $table6 WHERE group_id = '$group' AND enabled = '1'");
				
				$num2 = mysql_num_rows($result2);
				//echo $num2."<BR>";
				FOR($i2=0; $i2<$num2; $i2++)
				{
					$perm_id = mysql_result($result2, $i2, 'permission_id');
					$result3 = mysql($db, "INSERT INTO $table7 (user_id, permission_id, enabled) VALUES ('$user_id', '$perm_id', '1')");
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
					echo "Das User-Verzeichnis ist bereits vorhanden und/oder bei der Datenübernahme ist ein Fehler aufgetreten!<BR><BR>
					<input type='button' value='Zurück' OnClick='location.href=\"adminframe.php?item=adminadduser\"'>";
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
					echo "Das Upload-Verzeichnis ist bereits vorhanden und/oder bei der Datenübernahme ist ein Fehler aufgetreten!<BR><BR>
					<input type='button' value='Zurück' OnClick='location.href=\"adminframe.php?item=adminadduser\"'>";
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
					echo "Das Download-Verzeichnis ist bereits vorhanden und/oder bei der Datenübernahme ist ein Fehler aufgetreten!<BR><BR>
					<input type='button' value='Zurück' OnClick='location.href=\"adminframe.php?item=adminadduser\"'>";
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
					echo "Das kml-Verzeichnis ist bereits vorhanden und/oder bei der Datenübernahme ist ein Fehler aufgetreten!<BR><BR>
					<input type='button' value='Zurück' OnClick='location.href=\"adminframe.php?item=adminadduser\"'>";
					return;
				}
			}
			ELSE
			{
				echo "Der Benutzername ist ungültig.<BR>Hinweis:<BR>Der Benutzername darf keine Leerzeichen oder Sonderzeichen enthalten<BR>
und darf höchstens 15 Zeichen lang sein.!<BR><BR>
				<input type='button' value='Zurück' OnClick='javasript:history.back()'>";
				return;
			}
			echo "Benutzer wurde erfolgreich angelegt.<BR><BR>
			<input type='button' value='Zurück' OnClick='location.href=\"adminframe.php?item=adminadduser\"'>";
		}
		ELSE
		{
			echo "Bitte &uuml;berpr&uuml;fen Sie Ihre Eingaben.<BR>
			Die mit (*) gekennzeichneten Felder M&Uuml;SSEN ausgef&uuml;llt werden!<BR><BR>
			<input type='button' value='Zurück' OnClick='javascript:history.back()'>";
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