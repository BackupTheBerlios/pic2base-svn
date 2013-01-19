<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - FTP</TITLE>
	<META NAME="GENERATOR" CONTENT="Eclipse">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../css/format2.css'>
	<link rel="shortcut icon" href="../share/images/favicon.ico">
	<script language="JavaScript" src="../share/functions/resize_elements.js"></script>
	<script language="JavaScript" src="../share/functions/jquery-1.8.2.min.js"></script>
	<script language="JavaScript">
	  	jQuery.noConflict();
		jQuery(document).ready(checkWindowSize);
		jQuery(window).resize(checkWindowSize); 
	</script>
</HEAD>

<BODY>
<CENTER>
<DIV Class="klein">

<?

/*
 * Project: pic2base
 * File: index.php
 *
 * Copyright (c) 2003 - 2013 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 */

IF ($_COOKIE['uid'])
{
	$uid = $_COOKIE['uid'];
}

include '../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/permissions.php';

IF(hasPermission($uid, 'adminlogin', $sr))
{
	$navigation = 	"<a class='navi_blind'></a>
			<a class='navi' href='?action=about'>About</a>
			<a class='navi' href='?action=logs&logs=transfer'>Transfer</a>
			<a class='navi' href='?action=traffic'>Traffic</a>
			<a class='navi' href='?action=user_online'>User-Online</a>
			<a class='navi' href='../html/admin/adminframe.php'>Zur&uuml;ck</a>
			<a class='navi_blind'></a>
			<a class='navi' href='../html/start.php'>zur Startseite</a>
			<a class='navi' href='../html/help/help1.php?page=5'>Hilfe</a>
			<a class='navi' href='../../index.php'>Logout</a>";
}

echo "<div class='page' id='page'>

	<div class='head' id='head'>
		pic2base :: pic2base :: FTP-Verwaltung
	</div>
	
	<div class='navi' id='navi'>
		<div class='menucontainer'>".$navigation."</div>
	</div>
	
	<div class='content' id='content'>
		<p style='margin:120px 0px; text-align:center'>";

		if(!isset($_GET['action'])) $_GET['action'] = "";
		switch($_GET['action'])
		{
			default:
			echo "
			<br>
			Willkommen zum ProFTPd-Frontend!
			<BR><BR>
			Folgen Sie bitte der Navigationsleiste.
			<br><br>";
			break;
		
		// ----------------------------------------------------------
			
			case 'about':
			echo "
			<br>
			ProFTPd-Frontend
			<br><br> 
			Version: 0.1b
			<BR><BR>
			Ein ProFTPd - Tool
			<BR><BR>
			Autor: Michael Kremsier
			<BR>
			aka DJ DHG
			<br><br>
			Email: <a href='mailto:xampp@bysteini.de?subject=ProFTPd-Frontend'>xampp@bysteini.de</a>
			<br>
			Homepage: <a href='http://addons.xampp.org' target='_blank'>http://addons.xampp.org</a>
			<br><br>";
			break;
		
		// ----------------------------------------------------------
			/*
			case 'user':
			include('user.php');
			break;
			*/
		// ----------------------------------------------------------
			
			case 'logs':
			include('logs.php');
			break;
		
		// ----------------------------------------------------------	
		
			case 'traffic':
			include('config.inc.php');
			mysql_connect($sql_server, $sql_user, $sql_pw) or die ("DB-Fehler-Nummer" .mysql_errno(). "|| Meldung: ". mysql_error()); 
			mysql_select_db($sql_db) or die ("DB-Fehler-Nummer" .mysql_errno(). "|| Meldung: ". mysql_error());
			
			$traffic_up = "";
			$traffic_down = "";
			$result = MYSQL_QUERY("SELECT ul_bytes, dl_bytes FROM users")
			 or die ("DB-Fehler-Nummer" .mysql_errno(). "|| Meldung: ". mysql_error());
			while($row  =  mysql_fetch_row($result))  {
			$traffic_down = $traffic_down + $row[1];
			$traffic_up = $traffic_up + $row[0];
			}
			$traffic_down = number_format(round($traffic_down,2) /1024 / 1024,2,",","."); 
			$traffic_up = number_format(round($traffic_up,2) /1024 / 1024,2,",","."); 
			?>
			<DIV>
				<CENTER>
				<table class='normal' border='0'>
				
				<tr>
				<td colspan='3' style='font-size:12pt; text-align:center;'>Traffic:</td>
				</tr>
				
				<TR style='height:3px;'>
				<TD class='normal' align='center' bgcolor='darkred' colspan='3'></TD>
				</TR>
				
				<tr>
				<td colspan='3'>&nbsp;</td>
				</tr>
				
				<TR>
				<TD width='150'>Upload Traffic:</TD>
				<TD align='right'><?=$traffic_up?> MB </TD>
				<TD width='150' align='center'><img src="img/up.gif" alt="UP"></TD>
				</TR>
				
				<TR>
				<TD width='150'>Download Traffic:</TD>
				<TD align='right'><?=$traffic_down?> MB </TD>
				<TD width='150' align='center'><img src="img/down.gif" alt="DOWN"></TD>
				</TR>
				
				<tr>
				<td colspan='3'>&nbsp;</td>
				</tr>
				
				<TR style='height:3px;'>
				<TD class='normal' align='center' bgcolor='darkred' colspan='3'></TD>
				</TR>
				
				</TABLE>
				</CENTER>
			</DIV>
			<?PHP
			mysql_close();
			break;
		
		// ----------------------------------------------------------
			
			case 'user_online':
			if(!file_exists("/opt/lampp/var/proftpd.pid"))
			{
				echo "ProFTPd ist nicht gestartet!";
			}
			else
			{
				$str = shell_exec("/opt/lampp/bin/ftpwho -v");
				$str = str_replace("\n","<BR>",$str); 
				
				if(strstr($str,"standalone"))
				{
					$str = str_replace("standalone","<CENTER>Standalone",$str);
					
					if(strstr($str,"min<BR>"))
					{
						$str = str_replace("min<BR>","min</CENTER><BR><BR>",$str);
					}
					
					
					if(strstr($str,"no users connected"))
					{
						$str = str_replace("no users connected","<CENTER><BR>Niemand ist mit dem Server verbunden!</CENTER>",$str);
					}
					else
					{
						$str = str_replace("client","Client",$str);
						$str = str_replace("server","Server",$str);
						$str = str_replace("location","Pfad",$str);
						$str = str_replace("Service class","",$str);
						if (strstr($str,"LIST") or strstr($str,"STOR") or strstr($str,"RETR") or strstr($str,"idle") or strstr($str,"IDLE") or strstr($str,"authenticating") or strstr($str,"DELE"))
						{
							$str = str_replace("LIST","<FONT COLOR=\"green\">Verzeichnis auflisten</FONT>",$str);
							$str = str_replace("STOR","<FONT COLOR=\"green\">Upload</FONT>",$str);
							$str = str_replace("RETR","<FONT COLOR=\"green\">Download</FONT>",$str);
							$str = str_replace("idle","<FONT COLOR=\"green\">Unt&auml;tig</FONT>",$str);
							$str = str_replace("authenticating","<FONT COLOR=\"green\">Anmelden</FONT>",$str);
							$str = str_replace("DELE","<FONT COLOR=\"green\">L&ouml;sche</FONT>",$str);
							$str = str_replace("users","Benutzer",$str);
						}
					}
				}
			}			
			echo "<div style='text-align:center;'>".$str."</div>";	
			break;
			
		}

?>
	</p>
	</div>
	
	<div class='foot' id='foot'>
		<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
	</div>

</div>
</DIV>
</CENTER>
</BODY>
</HTML>