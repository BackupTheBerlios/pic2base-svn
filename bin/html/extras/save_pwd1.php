<?setcookie('login',$_REQUEST['username']);?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>pic2base - Zugangskontrolle</title>
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="AUTHOR" content="k. henneberg">
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15">
  <link rel=stylesheet type='text/css' href='../../css/format1.css'>
  <link rel="shortcut icon" href="../share/images/favicon.ico">
</head>

<!--
/*
 * Project: pic2base
 * File: save_pwd1.php
 *
 * Copyright (c) 2005 - 2008 Klaus Henneberg
 *
 * Project owner:
 * Klaus Henneberg
 * Finkenweg 18
 * 38889 Blankenburg, BRD
 *
 * All files of this project are licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 * @copyright 2005-2006 Klaus Henneberg
 * @author Klaus Henneberg
 * @package pic2base
 * @license http://www.opensource.org/licenses/osl-2.1.php Open Software License
 */
 -->
<DIV Class="klein">
 
<?

unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = split(',',$_COOKIE['login']);
//echo $c_username;
}


echo "
<div class='page'>

	<p id='kopf'>pic2base - &Auml;nderung der pers&ouml;nlichen Einstellungen</p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>
		</div>
	</div>
	
	<div class='content'>
	<p style='margin:170px 0px; text-align:center'>";
	
	//include 'share/db_connect1.php';
	include '../../share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	
	$result1 = mysql($db,"SELECT * FROM $table1 WHERE username = '$c_username' AND pwd = ENCRYPT('$old_pwd','$key') AND aktiv = '1'");
	echo mysql_error();
	$num1 = mysql_num_rows($result1);
	IF ($num1 > '0' AND $new_pwd_1 !== ''  AND $new_pwd_2 !== '' AND strlen($new_pwd_1) > '4' AND $new_pwd_1 === $new_pwd_2)
	{
		echo "<p class='mittel' align='center'>Neues Passwort wird gespeichert...</p>";
		
		$user_id = mysql_result($result1, $i1, 'id');
		$key = '0815';
		$ftp_passwd = crypt($new_pwd_1);
		//echo $ftp_passwd;
		//Benutzerdaten erfassen:
		$result2 = mysql($db, "UPDATE $table1 SET pwd = ENCRYPT('$new_pwd_1','$key'), ftp_passwd = '$ftp_passwd' WHERE id = '$user_id'");
		IF(mysql_error() == '')
		{
			shell_exec('/opt/lampp/lampp reloadftp');
			echo "<meta http-equiv='refresh' content = '1; URL=../start.php'>";
		}
		ELSE
		{
			echo mysql_error();
		}
	}
	else
	{
		IF($num1 < '1')
		{
			echo "
			<CENTER>
			<p class='zwoelfred' align='center'><b>Sie haben fehlerhafte Daten eingegeben!<BR><BR>
			Der Vorgang wird abgebrochen.</b></p><BR>
			<INPUT TYPE='button' Value='Zur&uuml;ck' OnClick='location.href=\"../../../index.php\"'>
			</CENTER>";
		}
		ELSEIF($new_pwd_1=='' OR $new_pwd_2=='' OR strlen($new_pwd_1)<'5' OR strlen($new_pwd_2)<'5' OR $new_pwd_1 !== $new_pwd_2)
		{
			echo "
			<CENTER>
			<p class='zwoelfred' align='center'><b>Ein leeres oder zu kurzes Passwort wird nicht akzeptiert!<BR><BR>
			Bitte tragen Sie ein Passwort mit mindestens 5 Zeichen L&auml;nge ein<BR>
			und achten Sie darauf, dass die beiden Eingaben f&uuml;r das neue Passwort &uuml;bereinstimmen.</b></p><BR>
			<INPUT TYPE='button' Value='Zur&uuml;ck' OnClick='javascript:history.back()'>
			</CENTER>";
		}
	}
	mysql_close($conn);
	echo "
	</p>
	</div>
	<br style='clear:both;' />
	<p id='fuss'><A style='margin-right:745px;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>".$cr."</p>
</div>
</DIV>
</body>
</html>";
?>
