<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Willkommen bei pic2base</title>
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="AUTHOR" content="k. henneberg">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel=stylesheet type='text/css' href='../css/format1.css'>
  <link rel="stylesheet" href="../css/lightbox.css" type="text/css" media="screen" />
  <link rel="shortcut icon" href="../share/images/favicon.ico">
  <meta http-equiv="Refresh" content="200; URL=../../index.php">
</head>

<body>

<?php

/*
 * Project: pic2base
 * File: login1.php
 *
 * Copyright (c) 2005 - 2012 Klaus Henneberg
 *
 * Project owner:
 * Klaus Henneberg
 * Finkenweg 18
 * 38889 Blankenburg, BRD
 *
 * All files of this project are licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 */


echo "
<DIV Class='klein'>";
$ACTION = $_SERVER['PHP_SELF'];
$link = "http://{$_SERVER['SERVER_NAME']}$ACTION";
include '../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';

IF($cr == NULL OR $inst_path == '/')
{
	//wenn der Installationspfad falsch deklariert wurde, kann die db_connect1.php nicht eingebunden werden und damit das Copyright nicht gefunden werden...
	echo "<center><fieldset style='width:800px; background-color:black; margin-top:50px;'><legend style='color:red; margin-left:30px; font-size:16px;'>Hinweis</legend><h1 style='color:red; margin-top:50px; margin-bottom:30px;'>Es liegt ein Problem vor!<BR><BR>Bitte passen Sie die Einstellung des Installationspfades<BR>
	in der Datei pic2base/bin/share/global_config.php in Zeile 8 korrekt an!</h1>
	<p style='font-size:14px; color:red; font-weight:bold;'>Derzeitiger Wert: \$inst_path=\"$inst_path\";</p>
	<BR>
	<input type='button' style='margin-bottom:20px;' value='Zur&uuml;ck'onClick='javascript:history.back()'>
	</fieldset></center>";
	break;
}

echo "
	<div class='page'>
	
		<p id='kopf'>pic2base :: Login</p>
		
		<div class='navi' style='clear:right;'>
			<div class='menucontainer'>
			<BR>
			</div>
		</div>
		
		<div class='content'>
		<p style='margin:70px 0px; text-align:center'>
	
		<FORM action='../pwd_check.php' method='POST' name='pwd'>
		<p class='mittel' align='left'>Bitte geben Sie hier Ihren Benutzernamen und Ihr Passwort ein:</p>
		<table class='schmal' border='0' align='center'>
		<tbody>
		<tr>
		<td class='normal'>Benutzername:  </td>
		<td class='normal'><input type='text' class='Feld150' name='username' size=12 tabindex='1'></td>
		</tr>
		<tr>
		<td class='normal'>Ihr Passwort:  </td>
		<td class='normal'><input type='password' class='Feld150' name='passwd' size=12 tabindex='2'></td>
		</tr>
		<tr>
		<td class='normal'><BR></td>
		<td class='normal'></td>
		</tr>
		<tr>
		<td class='normal'><input type='submit' value='Login' tabindex='3' style='width:80px;'></td>
		<td class='normal'><INPUT TYPE=button VALUE='Abbrechen' onclick=\"location.href='../../index.php'\" tabindex='4' style='width:80px;'></td>
		</tr>
		</tbody>
		</table>
		</FORM>
		</p>
		</div>
		<br style='clear:both;' />
	
		<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>".$cr."</p>
	
	</div>
</DIV>";
?>
</body>
<script language="javascript">
document.pwd.username.focus();
</script>
</html>
