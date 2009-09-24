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

<?

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
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 * @license http://www.opensource.org/licenses/osl-2.1.php Open Software License
 */

unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = split(',',$_COOKIE['login']);
//echo $c_username;
}
 
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$result1 = mysql($db, "SELECT * FROM $table1 WHERE username = '$c_username' AND aktiv = '1'");
$berechtigung = mysql_result($result1, $i1, 'berechtigung');

//Variablen-Umbenennung für die Rücksprung-Adresse:
$kat_back = $kat_id;
$ID_back = $ID;

echo "<div id='blend' style='display:block; z-index:99;'>
<IMG src='../../share/images/grey.png' style='z-index:100; position:absolute; top:0px; left:0px; width:100%; height:99%;' />
<img src=\"../../share/images/loading.gif\" style='position:absolute; top:200px; width:40px; z-index:101;' />
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
	//Ermittlung der ausgewählten Checkboxen:
	//echo count($_POST)."<BR>";
	FOREACH ($_POST AS $key => $post)
	{
		//echo $key." / ".$post."<BR>";
		IF (substr($key,0,3) == 'pic')
		{
			//echo substr($key,7,strlen($key)-7)."<BR>";
			$pic_ID[] = substr($key,7,strlen($key)-7);
		}
	}

	
	IF (count($pic_ID) > 0)
	{
		FOREACH ($pic_ID AS $bild_id)
		{
			//echo $bild_id."<BR>";
			$res1 = mysql($db, "SELECT Caption_Abstract FROM $table14 WHERE pic_id = '$bild_id'");
			$desc = mysql_result($res1, $i1, 'Caption_Abstract');
			echo mysql_error();
			IF ($desc == '')
			{
				$Description = $description;
			}
			ELSE
			{
				$Description =$desc."\n".$description;
			}
			$res3 = mysql($db, "UPDATE $table14 SET Caption_Abstract = '$Description' WHERE pic_id = '$bild_id'");
			$FN = $pic_path."/".restoreOriFilename($bild_id, $sr);
			$desc = htmlentities($Description);
			//echo $FN.", ".$desc."<BR>";
			shell_exec($et_path."/exiftool -IPTC:Caption-Abstract='$desc' ".$FN." -overwrite_original");
		}
		IF (mysql_errno() == '0')
		{
			echo "<p style='color:green; font-size:12px; font-family:Helvitica,Arial;'>Datenübernahme...</p>
			<meta http-equiv='refresh' content='0; url=edit_beschreibung.php?kat_id=$kat_back&ID=$ID_back'>";
			
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
			$res3 = mysql($db, "UPDATE $table14 SET Caption_Abstract = '$description' WHERE pic_id = '$PIC_id'");
			$FN = $pic_path."/".restoreOriFilename($PIC_id, $sr);
			$desc = htmlentities($Description);
			//echo $FN.", ".$desc."<BR>";
			shell_exec($et_path."/exiftool -IPTC:Caption-Abstract='$desc' ".$FN." -overwrite_original");
			
			echo "<p style='color:green; font-size:12px; font-family:Helvitica,Arial;'>Datenübernahme...</p>
			<meta http-equiv='refresh' content='0; url=edit_beschreibung.php?kat_id=$kat_back&ID=$ID_back'>";
		}
		ELSE
		{
			echo "<p class='zwoelfred' align='center'>Sie haben kein Bild zur Bearbeitung ausgewählt!<BR><BR>
			Bitte wählen Sie mindestens ein Bild aus<BR>oder verlassen Sie den vorhergehenden Dialog<BR>mit \"Abbrechen\"!</p>
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