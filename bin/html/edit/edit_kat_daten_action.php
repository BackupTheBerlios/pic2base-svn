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
 * File: edit_kat_daten_action.php
 *
 * Copyright (c) 2005 - 2009 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 */

unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = preg_split('#,#',$_COOKIE['login']);
//echo $c_username;
}
 
//var_dump($_GET);

if(array_key_exists('kat_id',$_GET))
{
	$kat_id = $_GET['kat_id']; 
}
else
{
	$kat_id = 0;
}
if(array_key_exists('ID',$_POST))
{
	$ID = $_POST['ID'];
}
else
{
	$ID = 0;
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$result1 = mysql_query( "SELECT * FROM $table1 WHERE username = '$c_username' AND aktiv = '1'");
$berechtigung = mysql_result($result1, isset($i1), 'berechtigung');

//Variablen-Umbenennung fuer die Ruecksprung-Adresse:
$kat_back = $kat_id;
$ID_back = $ID;


echo "<div id='blend' style='display:block; z-index:99;'>
<IMG src='../../share/images/grey.png' style='z-index:100; position:absolute; top:0px; left:0px; width:100%; height:99%;' />
<img src=\"../../share/images/loading.gif\" style='position:absolute; top:200px; width:40px; z-index:101;' />
</div>";


echo "
<div class='page'>

	<p id='kopf'>pic2base :: Kategorie-Zuweisung (&Auml;nderungen speichern)</p>
	
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>
		</div>
	</div>
	
	<div class='content'>";
flush();	
	//++++++++++++++++++++++++++++++++++++++++++++++++++
	
	//HINWEIS: Wenn einem Bild eine Kategorie zugewiesen werden soll, werden ihm gleichzeitig alle Eltern-Kategorien mit zugewiesen, denn ein Motiv in Blankenburg ist zwangsl�ufig in Sachsen-Anhalt, der BRD und Europa...
	
	//++++++++++++++++++++++++++++++++++++++++++++++++++
	
	//Ermittlung der ausgewaehlten Checkboxen:
	//echo count($_POST)."<BR>";
	FOREACH ($_POST AS $key => $post)
	{
		//echo "Schluessel / Wert: ".$key." / ".$post."<BR>";
		IF (substr($key,0,3) == 'pic')
		{
			//echo substr($key,7,strlen($key)-7)."<BR>";
			$pic_ID[] = substr($key,7,strlen($key)-7);
		}
		
		IF (substr($key,0,3) == 'kat')
		{
			//echo substr($key,3,strlen($key)-3)."<BR>";
			$kat_ID[] = substr($key,3,strlen($key)-3);
		}
	}

	//Fuer alle Elemente in dem Kategorie-Array werden die Eltern-Elemente bestimmt und ebenfalls in das Array geschrieben:
	
	IF (isset($kat_ID) AND count($kat_ID) > 0)
	{
		FOREACH($kat_ID AS $kat_id)
		{
			WHILE ($kat_id > '1')
			{
				$res0 = mysql_query( "SELECT parent FROM $table4 WHERE kat_id='$kat_id'");
				echo mysql_error();
				$kat_id = mysql_result($res0, isset($i0), 'parent');
				//echo "Kat-ID in der Funktion: ".$kat_id."<BR>";
				$KAT_ID[]=$kat_id; //$KAT_ID: Array mit den Eltern-Elementen
			}
		}
	}
	//Zuf�gen der Eltern-Elemente zu dem Kategorien-Array:
	@$kat_ID = array_merge($kat_ID, $KAT_ID);
	//echo "Kategorie-Anzahl: ".count($kat_ID)."<BR>";//$kat_ID ist das Array mit allen Kategorie-IDs und deren Eltern-Kategorie-IDs
	
	IF ( isset($pic_ID) AND count($pic_ID) > 0 AND count($kat_ID) > 0)
	{
		FOREACH ($pic_ID AS $bild_id)
		{
			$kategorie = '';
			FOREACH ($kat_ID AS $kat_id)
			{
				$res0 = mysql_query( "SELECT * FROM $table10 WHERE pic_id = '$bild_id' AND kat_id = '$kat_id'");
				IF (mysql_num_rows($res0) == '0')
				{
					$res1 = mysql_query( "INSERT INTO $table10 (pic_id, kat_id) VALUES ('$bild_id', '$kat_id')");
					IF($kat_id !== '1')
					{
						//Ermittlung aller Kategorien:
						$result2 = mysql_query( "SELECT kategorie FROM $table4 WHERE kat_id = '$kat_id'");
						//echo mysql_error()."<BR>";
						$kategorie = htmlentities(mysql_result($result2, 'kategorie'))." ".$kategorie;
					}
				}
			}
			//Kategorien werden ins Bild geschrieben:
			//Ermittlung des Dateinamens des Originalbildes:
			$FN = strtolower($pic_path."/".restoreOriFilename($bild_id, $sr));
			//echo $FN."<BR>";
			//eintragen der Kategorien in IPTC:Keywords
			shell_exec($et_path."/exiftool -IPTC:Keywords+='$kategorie' -overwrite_original ".$FN);
			
			//Aktualisierung des betreffenden Datensatzes in der exif_data Tabelle:
			$result3 = mysql_query( "UPDATE $table14 SET Keywords = '$kategorie' WHERE pic_id = '$bild_id'");
			$result4 = mysql_query( "UPDATE $table2 SET has_kat = '1' WHERE pic_id = '$bild_id'");
		}
		//echo mysql_errno();
		IF (mysql_errno() == '0')
		{
			echo "<p style='color:green; font-size:12px; font-family:Helvitica,Arial;'>Daten&uuml;bernahme...</p>
			<meta http-equiv='refresh' content='0; url=edit_kat_daten.php?kat_id=$kat_back&ID=$ID_back'>";
			
		}
		ELSE
		{
			echo "Es ist ein Fehler aufgetreten!";
		}
	}
	ELSE
	{
		echo "<p class='zwoelfred' align='center'>Es wurde kein Bild oder keine Kategorie ausgew&auml;hlt!<BR><BR>
		Bitte w&auml;hlen Sie mindestens ein Bild aus<BR>oder verlassen Sie den vorhergehenden Dialog<BR>mit \"Abbrechen\"!</p>
		<meta http-equiv='refresh' content='5; url=edit_kat_daten.php?kat_id=$kat_back&ID=$ID_back'>";
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