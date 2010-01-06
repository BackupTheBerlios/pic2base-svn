<?php setcookie('login','',time()-86400);
//header('Location: wartungshinweis.html'); 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Willkommen bei pic2base</title>
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="AUTHOR" content="k. henneberg">
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15">
  <link rel=stylesheet type='text/css' href='bin/css/format1.css'>
  <link rel="shortcut icon" href="bin/share/images/favicon.ico">
</head>

<?php
// php 5.3
/*
 * Project: pic2base
 * File: index.php
 *
 * Copyright (c) 2006 - 2007 Klaus Henneberg
 *
 * Project owner:
 * Klaus Henneberg
 * Finkenweg 18
 * 38889 Blankenburg, BRD
 *
 * All files of this project are licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 */
?>
<DIV Class="klein"> 
	
	<?php
	//Kontrolle, ob erforderliche Datenbank vorhanden ist:
	include 'bin/share/global_config.php';
	IF(@fopen($sr.'/bin/share/db_check1.php','r'))
	{
		include $sr.'/bin/share/db_check1.php';
		IF($text == '')
		{
			$text = "<img src=\"bin/share/images/himmel3.png\" width=\"800\" />";
		}
	}
	ELSE
	{
		$text = "<img src=\"bin/share/images/himmel3.png\" width=\"800\" />";
	}
	include $sr.'/bin/share/db_connect1.php';
	
	$ACTION = $_SERVER['PHP_SELF'];
	$link = "http://{$_SERVER['SERVER_NAME']}$ACTION";
	
	//var_dump($_REQUEST);
	@$database = mysql_pconnect($myhost,$myuser,$mypw);
	if ($database)
	{
		//log-file schreiben, wenn Zugang zur DB erfolgreich war:
		$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
		fwrite($fh,date('d.m.Y H:i:s')." ".$_SERVER['REMOTE_ADDR']." ".$_SERVER['PHP_SELF']." ".$_SERVER['HTTP_USER_AGENT']." p2b-Neustart\n");
		fclose($fh);
	}
	
	?>
	
	<div class="page">
	
		<p id="kopf">Willkommen bei pic2base <span class='klein'><?php echo "&#160;&#160;&#160;&#160;&#160;Version ".$version; ?></span></p>
		
		<div class="navi" style="clear:right;">
			<div class="menucontainer1">
			<span style="cursor:pointer;">
			<a class="navi" onClick='location.href="bin/html/login1.php"'>Login</a>
			</span>
			</div>
		</div>
		
		<div class="content">
		<p style="margin:115px 0px; text-align:center">
		
		<?php
		echo $text;
		//echo "<BR>".$_REQUEST['n'];
		//phpinfo();
		?>
		<noscript>
		pic2base verwendet in vielen Funktionen JavaScript.<BR>
		Um sinnvoll mit dieser Anwendung arbeiten zu k&ouml;nnen, ist es notwendig,<BR>
		da&szlig; Sie in Ihrem Browser JavaScript aktiviert haben.<BR>
		F&uuml;r Unterst&uuml;tzung fragen Sie bitte Ihnen Administrator.<BR><BR>
		Ihr pic2base-Team.
		</noscript>
		</p>
		</div>
		<br style="clear:both;" />
	
		<p id="fuss"><?php echo $cr; ?></p>
	
	</div>
</DIV>
</body>
</html>
