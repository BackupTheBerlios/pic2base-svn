<?php setcookie('login',$_REQUEST['username'],0,'/');?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
<title>pic2base - Zugangskontrolle</title>
<meta name="GENERATOR" content="Quanta Plus">
<meta name="AUTHOR" content="k. henneberg">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15">
<link rel=stylesheet type='text/css' href='css/format1.css'>
<link rel="shortcut icon" href="share/images/favicon.ico">
</head>

<!--
/*
 * Project: pic2base
 * File: pwd_check.php
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
<DIV Class="klein"><?php 
$ACTION = $_SERVER['PHP_SELF'];
$link = "http://{$_SERVER['SERVER_NAME']}$ACTION";

?>

<div class="page">

<p id="kopf">pic2base :: Zugangskontrolle</p>

<div class="navi" style="clear: right;">
<div class="menucontainer"></div>
</div>

<div class="content">
<p style="margin: 170px 0px; text-align: center"><?php 
//include 'share/db_connect1.php';
include 'share/global_config.php';
include $sr.'/bin/share/db_connect1.php';

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//var_dump($_POST);
//register_globels =  off - Variante:
if( isset($_POST['username']) )
{
	if( !empty($_POST['username']) )
	{
		$username=$_POST['username'];
		$passwd=$_POST['passwd'];
	}
}
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$result1 = mysql_query("SELECT * FROM $table1 WHERE username = '$username' AND pwd = ENCRYPT('$passwd','$key') AND aktiv = '1'");
//echo mysql_error();
@$num1 = mysql_num_rows($result1);
IF ($num1 > '0')
{
	echo "<CENTER>
		<p class='mittel'>Zugangspr&uuml;fung l&auml;uft...</p>
		<img src='share/images/loading.gif' width='32' height='32' />
		</CENTER>
		<meta http-equiv='refresh' content = '0; URL=html/start.php?check=1'>";
}
else
{
	echo "
		<CENTER>
		<p class='zwoelfred' align='center'><b>Sie haben fehlerhafte Zugangsdaten eingegeben!<BR>
		oder Ihr Account wurde gesperrt.<BR>
		Der Vorgang wird abgebrochen.</b></p><BR>
		<INPUT TYPE='button' Value='Zur&uuml;ck' OnClick='location.href=\"../index.php\"'>
		</CENTER>";
}
@mysql_close($conn);
?></p>
</div>
<br style="clear: both;" />

<p id="fuss"><A style='margin-right: 745px;'
	HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A><?php echo $cr; ?></p>

</div>
</DIV>
</body>
</html>
