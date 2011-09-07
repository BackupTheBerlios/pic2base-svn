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
 * Copyright (c) 2005 - 2009 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
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

//var_dump($_POST);
//var_dump($_GET);

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
}
if ( array_key_exists('pic_sel2',$_POST) )
{
	$pic_sel2 = $_POST['pic_sel2'];
}

$result1 = mysql_query( "SELECT * FROM $table1 WHERE username = '$c_username' AND aktiv = '1'");
//$berechtigung = mysql_result($result1, isset($i1), 'berechtigung');

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
		//echo $key." / ".$post."<BR>";
		IF (substr($key,0,3) == 'pic' OR substr($key,0,3) == 'PIC' )
		{
			//echo substr($key,7,strlen($key)-7)." <-pic_ID<BR>";
			$pic_ID[] = substr($key,7,strlen($key)-7);
		}
	}

	
	IF ( isset($pic_ID) AND count($pic_ID) > 0 )
	{
		FOREACH ($pic_ID AS $bild_id)
		{
		//echo $bild_id." <-Bild_id<BR>";
			$res1 = mysql_query( "SELECT Caption_Abstract FROM $table14 WHERE pic_id = '$bild_id'");
			$row = mysql_fetch_array($res1);
			$desc = $row['Caption_Abstract'];
			//$desc = mysql_result($res1, isset($i1), 'Caption_Abstract');
			echo mysql_error();
			IF ($desc == '')
			{
				$Description = $description;
			}
			ELSE
			{
				$Description =$desc."\n".$description;
			}
			$res3 = mysql_query( "UPDATE $table14 SET Caption_Abstract = '$Description' WHERE pic_id = '$bild_id'");
			$FN = $pic_path."/".restoreOriFilename($bild_id, $sr);
			$desc = htmlentities($Description);
			//echo $FN.", ".$desc."<BR>";
			shell_exec($exiftool." -IPTC:Caption-Abstract='$desc' ".$FN." -overwrite_original > /dev/null &");
		}
		IF (mysql_errno() == '0')
		{
			echo "<p style='color:green; font-size:12px; font-family:Helvitica,Arial;'>Daten&uuml;bernahme...</p>
			<meta http-equiv='refresh' content='1; url=edit_beschreibung.php?kat_id=$kat_back&ID=$ID_back'>";
			
		}
		ELSE
		{
			echo "Es ist ein Fehler aufgetreten!";
		}
	}
	ELSE
	{
		IF ($art == 'single_desc_edit')
		{
			$description = strip_tags($description);
			$res3 = mysql_query( "UPDATE $table14 SET Caption_Abstract = '$description' WHERE pic_id = '$PIC_id'");
			$FN = $pic_path."/".restoreOriFilename($PIC_id, $sr);
			$desc = htmlentities($Description);
			//echo $FN.", ".$desc."<BR>";
			shell_exec($exiftool." -IPTC:Caption-Abstract='$desc' ".$FN." -overwrite_original > /dev/null &");
			
			echo "<p style='color:green; font-size:12px; font-family:Helvitica,Arial;'>Daten&uuml;bernahme...</p>
			<meta http-equiv='refresh' content='1; url=edit_beschreibung.php?kat_id=$kat_back&ID=$ID_back'>";
		}
		ELSE
		{
			echo "<p class='zwoelfred' align='center'>Sie haben kein Bild zur Bearbeitung ausgew&auml;hlt!<BR><BR>
			Bitte w&auml;hlen Sie mindestens ein Bild aus<BR>oder verlassen Sie den vorhergehenden Dialog<BR>mit \"Abbrechen\"!</p>
			<meta http-equiv='refresh' content='3; url=edit_beschreibung.php?kat_id=$kat_back&ID=$ID_back'>";
		}
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