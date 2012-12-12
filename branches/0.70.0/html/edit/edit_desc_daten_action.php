<?php
IF (!$_COOKIE['login'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../../index.php');
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-1">
	<TITLE>pic2base - Startseite</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: edit_desc_daten_action.php
 *
 * Copyright (c) 2005 - 2012 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 * #######################  ab version 0.60.4 (30.09.2012) nicht mehr verwendet  #############################
 */

unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = preg_split('#,#',$_COOKIE['login']);
//echo $c_username;
}
 
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$exiftool = buildExiftoolCommand($sr);

if ( array_key_exists('kat_id',$_GET) )
{
	$kat_id = $_GET['kat_id'];
}
if ( array_key_exists('ID',$_GET) )
{
	$ID = $_GET['ID'];
}
if ( array_key_exists('art',$_GET) )
{
	$art = $_GET['art'];
}
if ( array_key_exists('description',$_POST) )
{
	$description = $_POST['description'];
	$description = strip_tags($description);
	$description = str_replace('"', "'",$description);
}
if ( array_key_exists('pic_sel2',$_POST) )
{
	$pic_sel2 = $_POST['pic_sel2'];
}

$result1 = mysql_query( "SELECT * FROM $table1 WHERE username = '$c_username' AND aktiv = '1'");

//Variablen-Umbenennung fuer die Ruecksprung-Adresse:
$kat_back = $kat_id;
$ID_back = $ID;

echo "<div id='blend' style='display:block; z-index:99;'>
<IMG src='../../share/images/grey.png' style='z-index:100; position:absolute; top:0px; left:0px; width:100%; height:99%;' />
<img src=\"../../share/images/loading.gif\" style='position:absolute; top:200px; width:20px; z-index:101;' />
</div>";

echo "
<div class='page'>

	<p id='kopf'>pic2base :: Datensatz-Bearbeitung (&Auml;nderungen speichern)</p>
	
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>
		</div>
	</div>
	
	<div class='content'>";
flush();
	//Ermittlung der ausgewaehlten Checkboxen:
	//echo count($_POST)."<BR>";
	FOREACH ($_POST AS $key => $post)
	{
		IF (substr($key,0,3) == 'pic' OR substr($key,0,3) == 'PIC' )
		{
			$pic_ID[] = substr($key,7,strlen($key)-7);
		}
	}

	
	IF ( isset($pic_ID) AND count($pic_ID) > 0 )
	{
		FOREACH ($pic_ID AS $bild_id)
		{
		//echo $bild_id." <-Bild_id<BR>";
			$res1 = mysql_query( "SELECT Caption_Abstract FROM $table2 WHERE pic_id = '$bild_id'");
			$row = mysql_fetch_array($res1);
			$desc = $row['Caption_Abstract'];
			//$desc = htmlentities($desc);
			echo mysql_error();
			IF ($desc == '')
			{
				$Description = $description;
			}
			ELSE
			{
				$Description =$desc."\n".$description;
			}
			$res3 = mysql_query( "UPDATE $table2 SET Caption_Abstract = \"$Description\" WHERE pic_id = '$bild_id'");
			IF(!mysql_error())
			{
				//Log-Datei schreiben:
				$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
				fwrite($fh,date('d.m.Y H:i:s').": Beschreibung von Bild ".$bild_id." wurde von ".$c_username." modifiziert. (Zugriff von ".$_SERVER['REMOTE_ADDR']."\nalt: ".$desc.", neu: ".$Description."\n");
				fclose($fh);
			}
			ELSE
			{
				echo mysql_error();
			}
			$FN = $pic_path."/".restoreOriFilename($bild_id, $sr);
			$desc = utf8_encode($Description);
			//echo $FN.", ".$desc."<BR>";
			shell_exec($exiftool." -IPTC:Caption-Abstract=\"$desc\" ".$FN." -overwrite_original > /dev/null &");
		}
		IF (mysql_errno() == '0')
		{
			echo "<p style='color:green; font-size:12px; font-family:Helvitica,Arial;'>Daten&uuml;bernahme...</p>
			<meta http-equiv='refresh' content='0; url=edit_beschreibung.php?kat_id=$kat_back&ID=$ID_back'>";
		}
		ELSE
		{
			echo "Es ist ein Fehler aufgetreten!";
		}
	}
	ELSE
	{
		/* Wird dieser Teil noch gebraucht?  ############################################
		IF ($art == 'single_desc_edit')
		{
			$description = $description;
			$res3 = mysql_query( "UPDATE $table2 SET Caption_Abstract = \"$description\" WHERE pic_id = '$PIC_id'");
			$FN = $pic_path."/".restoreOriFilename($PIC_id, $sr);
			$desc = htmlentities($description);
			//echo $FN.", ".$desc."<BR>";
			shell_exec($exiftool." -IPTC:Caption-Abstract='$desc' ".$FN." -overwrite_original > /dev/null &");
			
			echo "<p style='color:green; font-size:12px; font-family:Helvitica,Arial;'>Daten&uuml;bernahme...</p>
			<meta http-equiv='refresh' content='0; url=edit_beschreibung.php?kat_id=$kat_back&ID=$ID_back'>";
		}
		ELSE
		{
			echo "<p class='zwoelfred' align='center'>Sie haben kein Bild zur Bearbeitung ausgew&auml;hlt!<BR><BR>
			Bitte w&auml;hlen Sie mindestens ein Bild aus<BR>oder verlassen Sie den vorhergehenden Dialog<BR>mit \"Abbrechen\"!</p>
			<meta http-equiv='refresh' content='5; url=edit_beschreibung.php?kat_id=$kat_back&ID=$ID_back'>";
		}
		*/
	}
	echo "
	</div>
	<br style='clear:both;' />
	<p id='fuss'><A style='margin-right:745px;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>".$cr."</p>
</div>";

mysql_close($conn);
?>
</DIV>
</CENTER>
</BODY>
</HTML>