<?php
IF (!$_COOKIE['login'])
{
	include '../share/global_config.php';
	//var_dump($sr);
	header('Location: ../../index.php');
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Startseite</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../css/format1.css'>
	<link rel="shortcut icon" href="../share/images/favicon.ico">
	<style type="text/css">
	<!--
	.tablenormal	{
			width:450px;
			margin-left:175px;
			}
			
	.trflach	{
			height:3px;
			background-color:#FF9900
			}
			
	.tdleft	{
			width:120px;
			text-align:left;
			}
			
	.tdright	{
			width:280px;
			text-align:left;
			}
	-->
	</style>
</HEAD>

<script language="javascript" type="text/javascript" src="../share/functions/ShowPicture.js"></script>
<script language="JavaScript">
<!--
function delAllMetadata(c_username)
{
	//alert("User: "+c_username);
	Fenster1 = window.open('../share/del_all_metadata.php?c_username='+c_username, 'entferne Metadaten...', "width=300,height=70,scrollbars,resizable=no,");
	Fenster1.focus();
}
-->
</SCRIPT>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: start.php
 *
 * Copyright (c) 2006 - 2009 Klaus Henneberg
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
$benutzername = $c_username;
//echo $c_username;
}
 
include '../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/permissions.php';
//echo buildDcrawCommand($sr);
IF(array_key_exists('check',$_GET))
{
	$check = $_GET['check'];
}
if(!isset($check))
{
	$check = 0;
}
if(!isset($hinweis))
{
	$hinweis = '';
}

//wenn angemeldeter User Mitgl. der Admin-Gruppe ist, Pruefung, ob eine neuere Version verfuegbar ist:
IF(hasPermission($c_username, 'adminlogin') AND $check == '1')
{
	$file1 = 'http://www.pic2base.de/web/includes/conf.inc.php';
	@$fh = fopen($file1,'r');
	IF(!$fh)
	{
		$ol_text = "";
		$online_hinweis = "Es konnte keine &Uuml;berpr&uuml;fung auf Online-Updates erfolgen.<BR>M&ouml;glicherweise haben Sie keinen Internet-Zugang.";
	}
	ELSE
	{
		@$html = join('',file($file1));
		$needle_1 = "aktuelle Version:<BR>";
		$var1 = stristr($html,$needle_1);
		$var2 = substr($var1,21,6);
		$V2 = str_replace('.','',$var2);
		$install_ver = substr($version,0,6);
		$IV = str_replace('.','',$install_ver);
		//echo "verwendete Version: ".$install_ver." (".$IV."); aktuelle Version: ".$var2." (".$V2.")<BR>";
		$ol_text = "Online-Updates:";
		IF($V2 > $IV)
		{
			$online_hinweis = "Es ist ein Online-Update auf Version ".$var2." verf&uuml;gbar.<BR>F&uuml;r weitere Informationen klicken Sie bitte <A HREF='http://www.pic2base.de/downloads1.php' target='blank'>hier.</A>";
		}
		ELSE
		{
			$online_hinweis = "Es sind keine Online-Updates verf&uuml;gbar.";
		}
		fclose($fh);
	}
}
ELSE
{
	$ol_text = "<BR>";
	$online_hinweis = "";
}

//bei jedem Aufruf der Startseite wird der kml-Ordner des betreffenden Users geleert:
IF($c_username !== 'pb')
{
	$folder = opendir($ftp_path."/".$c_username."/kml_files");	
	while($datei_name=readdir($folder))
	{
		if($datei_name != "." && $datei_name != "..")
		{
			$datei_name = $ftp_path."/".$c_username."/kml_files/".$datei_name;
			if(!@unlink($datei_name))
			{
				echo "Konnte die Datei $datei_name nicht l&ouml;schen!<BR>";
			}
		}
	}
}

$result1 = mysql_query("SELECT * FROM $table1 WHERE username = '$c_username' AND aktiv = '1'");
$user_id = mysql_result($result1, isset($i1), 'id');

//Ermittlung wieviel User in der DB registriert sind:
$result2 = mysql_query("SELECT * FROM $table1");
$num_user = mysql_num_rows($result2);

//Ermittlung, wieviel Bilder insgesamt in der Datenbank sind:
$result10 = mysql_query("SELECT * FROM $table2");
@$num10 = mysql_num_rows($result10);
IF($num10 > '0')
{
	$hinweis10 = "Es befinden sich zur Zeit ".$num10." Bilder in der Datenbank.";
}
ELSE
{
	$hinweis10 = "Es befinden sich zur Zeit noch keine Bilder in der Datenbank.";
}
//Nur wenn der angemeldete User Erfassungs- und Downloadrechte hat, werden die folgenden Kontrollen durchgefuehrt:

	//Pruefung, ob Bilder ohne Kategorie-Zuweisung fuer den Benutzer vorliegen:
	$num_pic = '0';
	$result2 = mysql_query("SELECT * FROM $table2 WHERE Owner = '$user_id'");
	@$num2 = mysql_num_rows($result2);
	IF($num2 > '0')
	{
		$result3 = mysql_query("SELECT * FROM $table2 WHERE Owner = '$user_id' AND has_kat = '0'");
		$num_pic = mysql_num_rows($result3);
		//echo "Num_Pic: ".$num_pic."<BR>";
		
		$hinweis0 = "Sie haben bisher ".$num2." Bilder in die Datenbank gestellt.";
		IF($num_pic == '0')
		{
			$hinweis = "<span style='color:green;'>Jedes Ihrer Bilder wurde mindestens einer Kategorie zugeordnet.</span>";
		}
		ELSE
		{
			$hinweis = $num_pic." Ihrer Bilder sind noch ohne Kategorie-Zuweisung!<BR>
			Tipps f&uuml;r eine effektive Archivierung finden Sie <A HREF='help/help1.php?page=0'>hier</A>";
		}
	}
	ELSE
	{
		$hinweis0 = "Sie haben noch keine Bilder in die Datenbank gestellt.";
	}
	//Pruefung, ob Dateien im FTP-Upload-Ordner vorliegen:
	$n = 0;
	@$verz=opendir($up_dir);
	//Ermittlung, wieviel Bilddateien sich in dem angegebenen Ordner befinden und Abspeicherung der Dateinamen in einem Array:
	IF($verz)
	{
		while($datei=readdir($verz))
		{
			if($datei != "." && $datei != "..")
			{
				$info = pathinfo($datei);
				$extension = strtolower($info['extension']);
				IF(in_array($extension,$supported_filetypes) OR $extension == 'jpg')
				{
					$bild_datei[] = $datei;
					$n++;
				}
			}
		}
		$hinweis2 = "Es befinden sich ".$n." Datei(en) in Ihrem Upload-Ordner.<BR>";
	}
	
	//Pruefung, ob Dateien im FTP-Download-Ordner vorliegen:
	$m = 0;
	@$verz=opendir($ftp_path."/".$c_username."/downloads");
	//echo "V: ".$verz;
	//Ermittlung, wieviel Bilddateien sich in dem angegebenen Ordner befinden und Abspeicherung der Dateinamen in einem Array:
	IF($verz)
	{
		while($datei=readdir($verz))
		{
			if($datei != "." && $datei != "..")
			{
				$info = pathinfo($datei);
				$extension = strtolower($info['extension']);
				IF(in_array($extension,$supported_filetypes) OR $extension == 'jpg')
				{
					$bild_datei[] = $datei;
					$m++;
				}
			}
		}
		$hinweis3 = $m." Datei(en) liegen in Ihrem Download-Ordner: ";
	}

//Ermittlung der 'Top-Ten':
$result4 = mysql_query("SELECT * FROM $table2 WHERE ranking <>'' AND ranking >'0' ORDER BY ranking DESC LIMIT 10");
@$num4 = mysql_num_rows($result4);
//echo $num4;

echo "<div class='page'>

	<p id='kopf'>pic2base :: Startseite <span class='klein'>(User: ".$c_username.")</span></p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
		createNavi0($c_username);
		echo "</div>
	</div>
	
	<div class='content'>
	<span style='font-size:14px;'>";
	//$num2		Summe der Bilder des Users
	//$m		Dateien im Download-Ordner
	//$n		Dateien im Upload-Ordner
	//$num_pic	Bilder ohne Kat-Zuweisung
	//echo $m.", ".$n.", ".$num2.", ".$num_pic.", ".$num_user."<BR>";
	
	IF($num_pic > '0' OR $n > '0' OR $m > '0' OR $num2 > '0' OR $num_user > '2')
	{
		$p_info = pathinfo($_SERVER['PHP_SELF']);
		//$file_name = $p_info['basename'];
		echo "<BR><CENTER>
			<TABLE class='liste1' border='0'>
			<tbody>
			
			<TR class='normal' style='height:3px;'>
			<TD class='normal' align='center' bgcolor='#FF9900' colspan='10'>
			</TD>
			</TR>
			
			<TR class='normal'>
			<TD class='normal' align='center' style='height:50px;' colspan='3'></TD>
			<TD class='normal' align='center' style='height:50px;' colspan='7'>
			<p id='kopf' style='color:RGB(80,80,80); text-align:left; padding-left:0px;'>Informationen zum Datenbestand:</p>
			</TD>
			</TR>
			
			<TR class='normal' style='height:3px;'>
			<TD class='normal' align='center' bgcolor='#FF9900' colspan='10'></TD>
			</TR>
			
			<tr class='normal'>
			<td class='normal' align='center' colspan='10'>&nbsp;</TD>
			</TR>
			
			<tr class='normal'>
 			<TD class='normal' align='left' valign='top' width='243' colspan='3'>Gesamter Datenbestand:</TD>
			<td class='normal' align='left' valign='top' colspan='7'>".$hinweis10."</TD>
			</TR>
			
			<tr class='normal'>
			<td class='normal' align='center' style='height:20px;' colspan='10'>&nbsp;</TD>
			</TR>";
			
			IF(hasPermission($c_username, 'addpic'))
			{
				echo "
				<tr class='normal'>
	 			<TD class='normal' align='left' valign='top' width='243' colspan='3'>Ihr Datenbestand:</TD>
				<td class='normal' align='left' valign='top' colspan='7'>".$hinweis0."</TD>
				</TR>";
			}
			ELSE
			{
				echo "
				<tr class='normal'>
	 			<TD class='normal' align='left' valign='top' width='243' colspan='3'><BR></TD>
				<td class='normal' align='left' valign='top' colspan='7'><BR></TD>
				</TR>";
			}
			echo "
			<tr class='normal'>
			<td class='normal' align='center' style='height:20px;' colspan='10'>&nbsp;</TD>
			</TR>";

		
		IF ($num_pic > '0' AND hasPermission($c_username, 'addpic'))
		{
			echo "
			<tr class='normal'>
 			<TD class='normal' align='left' valign='top' width='243' colspan='3'>Bearbeitungsstatus:</TD>
			<TD class='normal' align='left' valign='top' colspan='7'>".$hinweis."</TD>
			</TR>
			
			<tr class='normal'>
			<td class='normal' align='center' style='height:20px;' colspan='10'>&nbsp;</TD>
			</TR>";
		}
		ELSE
		{
			echo "
			<tr class='normal'>
 			<TD class='normal' align='left' valign='top' width='243' colspan='3'><BR></TD>
			<TD class='normal' align='left' valign='top' colspan='7'><BR></TD>
			</TR>
			
			<tr class='normal'>
			<td class='normal' align='center' style='height:20px;' colspan='10'>&nbsp;</TD>
			</TR>";
		}
	
		IF($n > '0' AND hasPermission($c_username, 'addpic'))
		{
			echo "
			<Form name='folder' method='post' action='erfassung/stapel1.php'>
			
			<tr class='normal' style='height:50px;'>
			<TD class='normal' align='left' valign='top' colspan='3'>Uploads:</TD>
			<td class='normal' align='left' valign='top' colspan='7'>".$hinweis2."</TD>
			</TR>
			
			<TR>
			<TD class='normal' align='center' colspan='10'><INPUT type='submit' class='button1' name='upload' value='Upload starten'></TD>
			</TR>
			
			<INPUT type='hidden' name='ordner' value='$ftp_path/$c_username/uploads' size='70'>
			
			<tr class='normal'>
			<td class='normal' align='center' style='height:20px;' colspan='10'>&nbsp;
			</TD>
			</TR>
			
			</FORM>";
		}
		ELSE
		{
			IF(hasPermission($c_username, 'addpic'))
			{
				echo "
				<TR class='normal' style='height:25px;'>
				<TD class='normal' align='left' valign='top' colspan='3'>Uploads:</TD>
				<td class='normal' align='left' valign='top' colspan='7' style='color:green';>Ihr Upload-Ordner ist leer.</TD>
				</TR>
				
				<TR>
				<TD class='normal' align='center' colspan='10' style='height:30px;'><BR></TD>
				</TR>
				
				<INPUT type='hidden' name='ordner' value='$ftp_path/$c_username/uploads' size='70' readonly>
				
				<TR class='normal'>
				<TD class='normal' align='center' style='height:20px;' colspan='10'>&nbsp;</TD>
				</TR>";
			}
			ELSE
			{
				echo "
				<TR class='normal' style='height:25px;'>
				<TD class='normal' align='left' valign='top' colspan='3'><BR></TD>
				<td class='normal' align='left' valign='top' colspan='7' style='color:green';><BR></TD>
				</TR>
				
				<TR>
				<TD class='normal' align='center' colspan='10' style='height:30px;'><BR></TD>
				</TR>
				
				<TR class='normal'>
				<TD class='normal' align='center' style='height:20px;' colspan='10'>&nbsp;</TD>
				</TR>";
			}
		}
		
		IF($m > '0' AND (hasPermission($c_username, 'downloadmypics') OR hasPermission($c_username, 'downloadallpics')) AND !directDownload($c_username, $sr))
		{
			$download_path = 'ftp://'.$c_username."@".$_SERVER['SERVER_NAME'].'/downloads/';
			$html_path = 'http://'.$_SERVER['SERVER_NAME'].$inst_path."/pic2base/userdata/".$c_username.'/downloads/';
			//echo $download_path;
			echo "
			<tr class='normal' style='height:50px;'>
			<TD class='normal' align='left' valign='top' colspan='3'>Downloads:</TD>
			<td class='normal' align='left' valign='top' colspan='7' style='color:red';>".$hinweis3."
			<a href=".$download_path." target='_blank'>".$download_path."</a>
			</style></TD>
			</TR>
			
			<TR class='normal'>
			<TD class='normal' align='left' valign='top' colspan='3'></TD>
			<TD class='normal' align='left' colspan='7'>
			<INPUT type='button' tabindex='1' VALUE='Hier klicken, um alle Meta-Informationen aus diesen Dateien vor dem Download zu entfernen' onClick='delAllMetadata(\"$c_username\")'>
			</TD>
			</TR>";
		}
		ELSE
		{
			IF((hasPermission($c_username, 'downloadmypics') OR hasPermission($c_username, 'downloadallpics')) AND !directDownload($c_username, $sr))
			{	
				echo "
				<tr class='normal' style='height:50px;'>
				<TD class='normal' align='left' valign='top' colspan='3'>Downloads:</TD>
				<TD class='normal' align='left' valign='top' colspan='7' style='color:green';>Ihr Download-Ordner ist leer.</style></TD>
				</TR>
				
				<TR class='normal'>
				<TD class='normal' align='center' colspan='10'>&nbsp;</TD>
				</TR>";
			}
			ELSEIF((hasPermission($c_username, 'downloadmypics') OR hasPermission($c_username, 'downloadallpics')) AND directDownload($c_username, $sr))
			{
				echo "
				<tr class='normal' style='height:50px;'>
				<TD class='normal' align='left' valign='top' colspan='3'>Download-Modus:</TD>
				<TD class='normal' align='left' valign='top' colspan='7' style='color:green';>Direkter Download</style></TD>
				</TR>
				
				<TR class='normal'>
				<TD class='normal' align='center' colspan='10'>&nbsp;</TD>
				</TR>";
			}
			ELSE
			{
				echo "
				<tr class='normal' style='height:50px;'>
				<TD class='normal' align='left' valign='top' colspan='3'><BR></TD>
				<TD class='normal' align='left' valign='top' colspan='7' style='color:green';><BR></style></TD>
				</TR>
				
				<TR class='normal'>
				<TD class='normal' align='center' colspan='10'>&nbsp;</TD>
				</TR>";
			}
		}
		
		echo "	<TR class='normal' style='height:35px;'>
			<TD class='normal' align='left' valign='top' colspan='3'>".$ol_text."</TD>
			<TD class='normal' align='left' valign='top' colspan='7'>".$online_hinweis."</TD>
			</TR>
			
			<TR class='normal'>
			<TD class='normal' align='center' colspan='10'>&nbsp;</TD>
			</TR>
		
			<TR class='normal' style='height:3px;'>
			<TD class='normal' align='center' bgcolor='#FF9900' colspan='10'>
			</TD>
			</TR>
			
			<TR class='normal'>
			<TD class='normal' align='center' colspan='10'>&nbsp;
			</TD>
			</TR>
			
			<tr class='normal'>
			<td class='normal' align='left' colspan='3'>Die pic2base - TopTen:</TD>
			<td class='normal' align='center' colspan='7'></TD>
			</TR>
			
			<tr class='normal'>
			<td class='normal' align='center' colspan='10'>&nbsp;
			</TD>
			</TR>
			
			<tr class='normal'>";
			FOR($i4='0'; $i4<$num4; $i4++)
			{
				$file = mysql_result($result4, $i4, 'FileNameHQ');
				$ranking = mysql_result($result4, $i4, 'ranking');
				//$bild = $pic_hq_path."/".$file;
				$bild = "http://".$_SERVER['SERVER_NAME']."".$inst_path."/pic2base/images/vorschau/hq-preview/".$file;
				$param = getimagesize($pic_hq_path."/".$file);
				$width = $param[0];
				$height = $param[1];
				//echo $bild;
				$hoehe = 40;
				$breite = $hoehe / $height * $width;
				
				echo "<SPAN style='cursor:pointer;'><td class='normal' style='width:80px;'align='center'><a href='#' target=\"vollbild\" onclick=\"ZeigeBild('$bild', '$width', '$height', '', 'HQ', 'start');return false\"  title='$ranking Downloads; zur vergr&ouml;&#223;erten Ansicht'><div class='shadow5'><div class='shadow4'><div class='shadow3'><div class='shadow2'><div class='shadow'><img src='../../images/vorschau/hq-preview/$file' alt='Vorschaubild', width='$breite', height='$hoehe' border='0'></div></div></div></div></div></a></TD></span>";
			}
			//Leer-Raum affuellen, wenn weniger als 10 Bilder bisher heruntergeladen wurden:
			FOR($x='0'; $x<(10-$num4); $x++)
			{
				echo "<TD class='normal' style='width:80px;'></TD>";
			}
			
			echo "
			</TR>
			
			<TR class='normal'>
			<TD class='normal' align='center' colspan='10'>&nbsp;
			</TD>
			</TR>
			
			<TR class='normal' style='height:3px;'>
			<TD class='normal' align='center' bgcolor='#FF9900' colspan='10'>
			</TD>
			</TR>
			
			</TBODY>
			</TABLE>
			</CENTER>";
	}
	ELSE
	{
		//so lange keine Bilder in der DB sind, wird bei jedem Start geprueft, ob alle notwendigen Applikationen verfuegbar sind! Das kann dauern...
		echo "<div class='content'>
		<p style='margin-top:120px; margin-left:10px; text-align:center'>";
		checkSoftware($sr);
		echo "</p></div><br style='clear:both;' />";
	}
	
	//log-file schreiben:
	$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
	fwrite($fh,date('d.m.Y H:i:s')." ".isset($REMOTE_ADDR)." ".$_SERVER['PHP_SELF']." ".$_SERVER['HTTP_USER_AGENT']." ".$c_username."\n");
	fclose($fh);
	mysql_close($conn);
	
	echo "</span>
	</p>
	
	</div>
	<br style='clear:both;' />

	<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>

</div>
</DIV>
</CENTER>
</BODY>
</HTML>";
?>