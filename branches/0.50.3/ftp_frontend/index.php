<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - FTP</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../css/format1.css'>
	<link rel="shortcut icon" href="../share/images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?

/*
 * Project: pic2base
 * File: index.php
 *
 * Copyright (c) 2003 - 2010 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 */

unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
	//echo $c_username;
}

include '../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/permissions.php';

IF(hasPermission($c_username, 'adminlogin'))
{
	$navigation = 	"<a class='navi' href='?action=about'>About</a>
			<a class='navi' href='?action=logs&logs=transfer'>Transfer</a>
			<a class='navi' href='?action=traffic'>Traffic</a>
			<a class='navi' href='#' onClick='NewWindow=window.open(\"user-online.php\",\"NewWindow\",\"toolbar=no,location=no,directories=no,status=no,me  nubar=no,scrollbars=yes,resizable=yes,width=500,height=400,top=100,left=400\");'>User-Online</a>
			<a class='navi' href='../html/admin/adminframe.php'>Zur&uuml;ck</a>
			<a class='navi_blind'></a>
			<a class='navi' href='../html/start.php'>zur Startseite</a>
			<a class='navi' href='../html/help/help1.php?page=5'>Hilfe</a>
			<a class='navi' href='../../index.php'>Logout</a>";
}

echo "<div class='page'>

	<p id='kopf'>pic2base :: FTP-Verwaltung</p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>".$navigation."</div>
	</div>
	
	<div class='content'>
	<p style='margin:120px 0px; text-align:center'>";

if(!isset($_GET['action'])) $_GET['action'] = "";
switch($_GET['action'])
{
	default:
	echo "
	<br>
	Willkommen zum ProFTPd-Frontend!
	<BR><BR>
	<img src='img/warning.gif' alt='Warnung'>
	Achtung, dies ist eine Beta Version!
	<img src='img/warning.gif' alt='Warnung'>
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
	
	case 'user':
	include('user.php');
	break;

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
	<DIV><CENTER>
	<!--<TABLE id="text" align="center" cellpadding="0" cellspacing="5" border="1">-->
	<table class='normal' border='0'>
	
	<tr>
	<td colspan='3' style='font-size:12pt; text-align:center;'>Traffic:</td>
	</tr>
	
	<TR style='height:3px;'>
	<TD class='normal' align='center' bgcolor='#FF9900' colspan='3'></TD>
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
	<TD class='normal' align='center' bgcolor='#FF9900' colspan='3'></TD>
	</TR>
	
	</TABLE></CENTER></DIV>
	<?PHP
	mysql_close();
	break;

// ----------------------------------------------------------
	
}

?>
	</p>
	</div>
	<br style="clear:both;" />
	<p id="fuss"><?php echo $cr; ?></p>

</div>
</DIV>
</CENTER>
</BODY>
</HTML>