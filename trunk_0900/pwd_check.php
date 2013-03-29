<?php
if (!isset($_POST['username']))
{
	header('Location: ../index.php');
}
else
{
	include 'share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	
	$success = "<CENTER>
	<p class='mittel'>Zugangspr&uuml;fung l&auml;uft...</p>
	<img src='share/images/loading.gif' width='20' height='20' />
	</CENTER>
	<meta http-equiv='refresh' content = '0; URL=html/start.php?check=1'>";
	$error = "<CENTER>
	<p class='zwoelfred' align='center'><b>Sie haben fehlerhafte Zugangsdaten eingegeben!<BR>
	oder Ihr Account wurde gesperrt.<BR>
	Der Vorgang wird abgebrochen.</b>
	</p>
	<BR>
	<INPUT TYPE='button' id='back' Value='Zur&uuml;ck' OnClick='location.href=\"html/login1.php\"'>
	</CENTER>
	<script type='text/javascript'>
	document.getElementById('back').focus();
	</script>";
	if( !empty($_POST['username']) )
	{
		$username = $_POST['username'];
        $passwd = $_POST['passwd'];
        $result1 = mysql_query("SELECT * FROM $table1 WHERE username = '$username' AND pwd = ENCRYPT('$passwd','$key') AND aktiv = '1'");
		@$num1 = mysql_num_rows($result1);
		
		if ($num1 > '0')
		{
			$uid = mysql_result($result1, isset($i1), 'id');
			setcookie('uid',$uid,0,'/');
			$meldung = $success;
			@mysql_close($conn);
		}
		else
		{
			$num1 = 0;
		    $meldung = $error;
		}
	}
    else
    {
    	$num1 = 0;
        $meldung = $error;
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>pic2base - Zugangskontrolle</title>
  <meta name="GENERATOR" content="Eclipse">
  <meta name="AUTHOR" content="k. henneberg">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel=stylesheet type='text/css' href='css/format2.css'>
  <link rel="shortcut icon" href="share/images/favicon.ico">
  <script language="JavaScript" src="share/functions/resize_elements.js"></script>
  <script language="JavaScript" src="share/functions/jquery-1.8.2.min.js"></script>
  <script language="JavaScript">
  	jQuery.noConflict()
	jQuery(document).ready(checkWindowSize);
	jQuery(window).resize(checkWindowSize); 
  </script>
</head>

<body>

<?php

/*
 * Project: pic2base
 * File: pwd_check.php
 *
 * Copyright (c) 2005 - 2013 Klaus Henneberg
 *
 * Project owner:
 * Klaus Henneberg
 * Finkenweg 18
 * 38889 Blankenburg, BRD
 *
 * All files of this project are licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 */

include $sr.'/bin/css/initial_layout_settings.php';

echo "<DIV Class='klein'>
		<div class='page' id='page'>
	
			<div class='head' id='head'>
			pic2base :: Zugangskontrolle
			</div>
			
			<div class='navi' id='navi'>
				<div class='menucontainer'>
				</div>
			</div>
			
			<div class='content' id='content'>
				<p style='margin:80px 0px; text-align:center'>".$meldung."</p>
			</div>
		
			<div class='foot' id='foot'>
			<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
			</div>
	
		</div>
	</DIV>";
?>
</body>
</html>
