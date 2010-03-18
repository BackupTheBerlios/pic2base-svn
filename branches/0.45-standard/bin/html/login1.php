<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Willkommen bei pic2base</title>
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="AUTHOR" content="k. henneberg">
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15">
  <link rel=stylesheet type='text/css' href='../css/format1.css'>
  <link rel="stylesheet" href="../css/lightbox.css" type="text/css" media="screen" />
  <link rel="shortcut icon" href="bin/share/images/favicon.ico">
  	<script type="text/javascript" src="../ajax/inc/prototype.js"></script>
	<script type="text/javascript" src="../ajax/inc/scriptaculous.js?load=effects,builder"></script>
	<script type="text/javascript" src="../ajax/inc/lightbox.js"></script>

  <meta http-equiv="Refresh" content="200; URL=../../index.php">
</head>

<!--
/*
 * Project: pic2base
 * File: login1.php
 *
 * Copyright (c) 2005 - 2006 Klaus Henneberg
 *
 * Project owner:
 * Klaus Henneberg
 * Finkenweg 18
 * 38889 Blankenburg, BRD
 *
 * All files of this project are licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 */
 -->
<DIV Class="klein">
 
<?php
$ACTION = $_SERVER['PHP_SELF'];
$link = "http://{$_SERVER['SERVER_NAME']}$ACTION";
include '../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
?>

<div class="page">

	<p id="kopf">pic2base :: Login</p>
	
	<div class="navi" style="clear:right;">
		<div class="menucontainer">
		<BR>
		</div>
	</div>
	
	<div class="content">
	<p style="margin:70px 0px; text-align:center">

	<form action='../pwd_check.php' method='POST' name='pwd'>
	<p class="mittel" align='left'>Bitte geben Sie hier Ihren Benutzernamen und Ihr Passwort ein:</p>
	<table class="schmal" border='0' align='center'>
	<tbody>
	<tr>
	<td class='normal'>Benutzername:  </td>
	<td class='normal'><input type="text" class="Feld150" name="username" size=12 tabindex="1"></td>
	</tr>
	<tr>
	<td class='normal'>Ihr Passwort:  </td>
	<td class='normal'><input type="password" class="Feld150" name="passwd" size=12 tabindex="2"></td>
	</tr>
	<tr>
	<td class='normal'><BR></td>
	<td class='normal'></td>
	</tr>
	<tr>
	<td class='normal'><input type="submit" value="Login" tabindex="3" style='width:80px;'></td>
	<td class='normal'><INPUT TYPE=button VALUE="Abbrechen" onclick="location.href='../../index.php'" tabindex="4" style='width:80px;'></td>
	</tr>
	</tbody>
	</table>
	<BR>
	&#160;&#160;
	</form>
	</p>
	</div>
	<br style="clear:both;" />

	<p id="fuss"><A style='margin-right:745px;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A><?php echo $cr; ?></p>

</div>
</DIV>
</body>
</html>
<script language="javascript">
document.pwd.username.focus();
</script>