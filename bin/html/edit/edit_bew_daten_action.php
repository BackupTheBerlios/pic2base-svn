<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
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
 * File: edit_bew_daten_action.php
 *
 * Copyright (c) 2005 - 2006 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 * @copyright 2003-2005 Klaus Henneberg
 * @author Klaus Henneberg
 * @package pic2base
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

$result1 = mysql_query( "SELECT * FROM $table1 WHERE username = '$c_username' AND aktiv = '1'");
$berechtigung = mysql_result($result1, $i1, 'berechtigung');

//Variablen-Umbenennung f�r die R�cksprung-Adresse:
$kat_back = $kat_id;
$ID_back = $ID;

echo "
<div class='page'>

	<p id='kopf'>pic2base :: Startseite</p>
	
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>
		</div>
	</div>
	
	<div class='content'>";
	
	//Ermittlung der ausgew�hlten Checkboxen:
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
			/*
			$res1 = mysql_query( "SELECT Description FROM $table2 WHERE pic_id = '$bild_id'");
			$desc = mysql_result($res1, $i1, 'Description');
			echo mysql_error();
			IF ($desc == '')
			{
				$Description = $description;
			}
			ELSE
			{
				//$Description =$desc."; ".$description;
				$Description =$desc."\n". $description;
			}
			*/
			$res2 = mysql_query( "UPDATE $table2 SET note = '$note' WHERE pic_id = '$bild_id'");
		}
		//echo mysql_errno();
		IF (mysql_errno() == '0')
		{
			echo "<p style='color:green; font-size:12px; font-family:Helvitica,Arial;'>Daten�bernahme...</p>
			<meta http-equiv='refresh' content='0; url=edit_bewertung.php?kat_id=$kat_back&ID=$ID_back'>";
			
		}
		ELSE
		{
			echo "Es ist ein Fehler aufgetreten!";
		}
	}
	ELSE
	{
		IF ($art == 'single_bewertung_edit')
		{
			//$description = strip_tags($description);
			$res2 = mysql_query( "UPDATE $table2 SET note = '$note' WHERE pic_id = '$PIC_id'");
			echo "<p style='color:green; font-size:12px; font-family:Helvitica,Arial;'>Daten�bernahme...</p>
			<meta http-equiv='refresh' content='0; url=edit_bewertung.php?kat_id=$kat_back&ID=$ID_back'>";
		}
		ELSE
		{
			echo "<p class='zwoelfred' align='center'>Sie haben kein Bild zur Bearbeitung ausgew�hlt!<BR><BR>
			Bitte w�hlen Sie mindestens ein Bild aus<BR>oder verlassen Sie den vorhergehenden Dialog<BR>mit \"Abbrechen\"!</p>
			<meta http-equiv='refresh' content='3; url=edit_bewertung.php?kat_id=$kat_back&ID=$ID_back'>";
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