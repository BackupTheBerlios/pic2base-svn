<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - md5-Erzeugung</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?

/*
 * Project: pic2base
 * File: md5_add.php
 *
 * Copyright (c) 2003 - 2006 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 * @copyright 2003-2006 Klaus Henneberg
 * @author Klaus Henneberg
 * @package pic2base
 * @license http://www.opensource.org/licenses/osl-2.1.php Open Software License
 */

unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = preg_split('#,#',$_COOKIE['login']);
//echo $c_username;
}
 
include '../../share/db_connect1.php';
INCLUDE '../../share/global_config.php';

$result1 = mysql_query( "SELECT * FROM $table1 WHERE username = '$c_username' AND aktiv = '1'");
$berechtigung = mysql_result($result1, isset($i1), 'berechtigung');
SWITCH ($berechtigung)
{
	//Admin
	CASE $berechtigung == '1':
	$navigation = 	"<a class='navi' href='../../html/admin/adminframe.php'>Zur�ck</a>	
			<a class='navi' href='../../html/start.php'>zur Startseite</a>
			<a class='navi' href='../../html/help/help1.php?page=5'>Hilfe</a>";
	break;
	
	//alle anderen
	CASE $berechtigung > '1':
	$navigation = 	"<a class='navi' href='../../../index.php'>Logout</a>";
	break;
}


echo "
<div class='page'>

	<p id='kopf'>pic2base :: Admin-Bereich - Pr�fsummen erzeugen</p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
			echo $navigation;
		echo "
		</div>
	</div>
	
	<div id='spalte1'>
	<p  style='margin:200px 5px;'>";
	//Kontrolle, ob Feld 'md5sum' vorhanden ist:
	$z = 0;
	$res10_1 = mysql_query( "SHOW FIELDS FROM `pictures`");
	if (!$res10_1) 
	{
		echo 'Abfrage konnte nicht ausgef�hrt werden: ' . mysql_error();
		exit;
	}
	if (mysql_num_rows($res10_1) > 0) 
	{
		while ($row = mysql_fetch_assoc($res10_1)) 
		{
			//echo $row['Field']."<BR>";
			IF($row['Field'] == 'md5sum')
			{
				$z++;
			}
			//print_r($row);
		}
	}
	IF($z == 0)
	{
		$res10_2 = mysql_query( "ALTER TABLE `pictures` ADD `md5sum` varchar(50) NOT NULL default ''");
		IF(!mysql_error())
		{
			echo "Feld md5sum wurde der Tabelle &lt;pictures&gt; zugef&uuml;gt.<BR>";
			$res10_3 = mysql_query( "INSERT INTO `pb_column_info` (table_name, column_name, comment, suchfeld) VALUES ('pictures', 'md5sum', 'MD5-Pr�fsumme', '0')");
			IF(!mysql_error())
			{
				echo "Tabelle pb_column_info wurde aktualisiert.<BR>";
			}
			ELSE
			{
				echo mysql_error();
			}
		}
		ELSE
		{
			echo mysql_error();
		}
		
	}
	ELSE
	{
		echo "Das Feld \"md5sum\" existiert in der Tabelle &lt;pictures&gt;."; 
	}
	$Y = '0';
	$result2 = mysql_query( "SELECT * FROM $table2");
	$num2 = mysql_num_rows($result2);
	FOR($i2 = '0'; $i2<$num2; $i2++)
	{
		$pic_id = mysql_result($result2, $i2, 'pic_id');
		$md5 = mysql_result($result2, $i2, 'md5sum');
		IF($md5 == '')
		{
			$file_short = mysql_result($result2, $i2, 'FileName');
			$file = $sr."/images/originale/".$file_short;
			$command = $md5sum_path."/md5sum $file";
			//echo $command;
			$sum = explode(' ',shell_exec($command));
			//echo $file_short.", ".$sum[0]."<BR>";
			$result3 = mysql_query( "UPDATE $table2 SET md5sum = '$sum[0]' WHERE pic_id = '$pic_id'");
			echo mysql_error();
			$Y++;
		}
	}
	echo "
	<BR>Der Tabelleninhalt ist jetzt aktuell.<BR>".$Y." Eintr&auml;ge mu&szlig;ten neu erzeugt werden.<BR></P>
	</div>
	
	
	<div id='spalte2'>
		<center>
		</center>
	</div>
	
	<p id='fuss'>".$cr."</p>

</div>";

mysql_close($conn);
?>
<p class="klein">- KH 02/2008 -</P>
</DIV></CENTER>
</BODY>
</HTML>