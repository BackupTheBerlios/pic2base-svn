<?php //setcookie('login',$_REQUEST['username'],0,'/');?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>pic2base - Zugangskontrolle</title>
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="AUTHOR" content="k. henneberg">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel=stylesheet type='text/css' href='css/format1.css'>
  <link rel="shortcut icon" href="share/images/favicon.ico">
</head>

<body>

<?php

/*
 * Project: pic2base
 * File: pwd_check.php
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

echo "<DIV Class='klein'>"; 
//	$ACTION = $_SERVER['PHP_SELF'];
//	$link = "http://{$_SERVER['SERVER_NAME']}$ACTION";
	
	echo "
	<div class='page'>
	
		<p id='kopf'>pic2base :: Zugangskontrolle</p>
		
		<div class='navi' style='clear:right;'>
			<div class='menucontainer'>
			</div>
		</div>
		
		<div class='content'>
			<p style='margin:170px 0px; text-align:center'>";
			
				include 'share/global_config.php';
				include $sr.'/bin/share/db_connect1.php';
				
				// Fehlermeldung, falls ungueltige Daten eingegeben wurden:
				$text = "<CENTER>
				<p class='zwoelfred' align='center'><b>Sie haben fehlerhafte Zugangsdaten eingegeben!<BR>
				oder Ihr Account wurde gesperrt.<BR>
				Der Vorgang wird abgebrochen.</b>
				</p><BR>
				<INPUT TYPE='button' id='back' Value='Zur&uuml;ck' OnClick='location.href=\"../index.php\"'>
				</CENTER>
				<script type='text/javascript'>
				document.getElementById('back').focus();
				</script>";
			
				if( isset($_POST['username']) )
				{
					if( !empty($_POST['username']) )
					{
			                	$username = utf8_decode($_POST['username']);
			                    $passwd = utf8_decode($_POST['passwd']);
			                    $result1 = mysql_query("SELECT * FROM $table1 WHERE username = '$username' AND pwd = ENCRYPT('$passwd','$key') AND aktiv = '1'");
								//echo mysql_error();
								@$num1 = mysql_num_rows($result1);
								if ($num1 > '0')
								{
									$uid = mysql_result($result1, isset($i1), 'id');
									setcookie('uid',$uid,0,'/');
									echo "<CENTER>
									<p class='mittel'>Zugangspr&uuml;fung l&auml;uft...</p>
									<img src='share/images/loading.gif' width='20' height='20' />
									</CENTER>
									<meta http-equiv='refresh' content = '0; URL=html/start.php?check=1'>";
								}
								else
								{
									$num1 = 0;
							        echo $text;
								}
			        }
			        else
			        {
			        	$num1 = 0;
			        	echo $text;
			        }
				}
				
				@mysql_close($conn);
			
			echo "
			</p>
		</div>
		<br style='clear:both;'' />
	
		<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>".$cr."</p>
	
	</div>
</DIV>";
?>

</body>
</html>
