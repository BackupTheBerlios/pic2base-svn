<?php
IF (!$_COOKIE['login'])
{
	include '../../share/global_config.php';
  	header('Location: ../../../index.php');
}
?>

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

<?php

/*
 * Project: pic2base
 * File: remove_kat_daten_action.php
 *
 * Copyright (c) 2005 - 2012 Klaus Henneberg
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
 
//var_dump($_POST);
//var_dump($_GET);

if(array_key_exists('kat_id',$_GET))
{
	$parent = $_GET['kat_id']; 
}
else
{
	$parent = 0;
}
if(array_key_exists('ID',$_POST))
{
	$kat_id = $_POST['ID'];
}
else
{
	$kat_id = 0;
}
if(array_key_exists('mod',$_GET))
{
	$mod = $_GET['mod'];
}
else
{
	$mod = 0;
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$exiftool = buildExiftoolCommand($sr);

//ob_flush();
echo "
<div class='page'>

	<p id='kopf'>pic2base :: Kategorie-Zuweisung entfernen (&Auml;nderungen speichern)</p>
	
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>
		</div>
	</div>
	
	<div class='content'>";


echo "<div id='blend' style='display:block; z-index:99;'>
	<IMG src='../../share/images/grey.png' style='z-index:100; position:absolute; top:0px; left:0px; width:100%; height:99%;' />
	<img src=\"../../share/images/loading.gif\" style='position:relative; top:200px; left:400px; width:20px; z-index:101;' />
	<p style='color:white; position:relative; top:120px; left:250px; z-index:102;'>Die &Auml;nderungen werden ausgef&uuml;hrt, bitte warten Sie...</p>
	</div>";
ob_flush();
flush();
	//++++++++++++++++++++++++++++++++++++++++++++++++++
	
	//HINWEIS: Wenn einem Bild eine Kategorie-Zuweisung entfernt werden soll, werden gleichzeitig alle Child-Kategorien mit entfernt	
	
	//++++++++++++++++++++++++++++++++++++++++++++++++++
	
	//Ermittlung der ausgewaehlten Checkboxen:
	FOREACH ($_POST AS $key => $post)
	{
		IF (substr($key,0,3) == 'pic')
		{
			$pic_ID[] = substr($key,7,strlen($key)-7);
		}
	}
	
//	#########################################
	
	function getTree($kat_id,$pic_id) 
	{
	    include '../../share/global_config.php';
		include $sr.'/bin/share/db_connect1.php';
		$result2 = mysql_query("SELECT kat_id, kategorie FROM $table4 WHERE parent='".$kat_id."' ORDER BY kategorie");
	    while($einzeln = @mysql_fetch_assoc($result2)) 
	    {
	      if(hasChildKats($einzeln['kat_id'],$pic_id)) 
	      {
	        $KA = $einzeln['kat_id'];
	        $result3 = mysql_query("DELETE FROM $table10 WHERE pic_id = '$pic_id' AND kat_id = '$KA'");
	       	$KAE = getTree($einzeln['kat_id'],$pic_id);
	      } 
	      else 
	      {
	        $KA = $einzeln['kat_id'];
	        $result4 = mysql_query("DELETE FROM $table10 WHERE pic_id = '$pic_id' AND kat_id = '$KA'");
	      }
	    }
	}
	
	function hasChildKats($katID,$pic_id) 
	{
	    include '../../share/global_config.php';
		include $sr.'/bin/share/db_connect1.php';
		$result5 = mysql_query("SELECT kat_id FROM $table4 WHERE parent='".$katID."'");
	    if(mysql_num_rows($result5)>0) return true; else return false;
	}		
			
//	########################################

	//Alle Elemente in dem Bild-Array:
	IF (isset($pic_ID) AND count($pic_ID) > 0) //Array der ausgewaehletn Bilder
	{
		FOREACH($pic_ID AS $pic_id)
		{			
			$result1 = mysql_query("DELETE FROM $table10 WHERE pic_id = '$pic_id' AND kat_id = '$kat_id'");
			echo mysql_error();

			$KA = getTree($kat_id,$pic_id);
			
			//Kontrolle, ob dem Bild noch mindestens eine Kategorie zugewiesen ist, sonst: has_kat = 0
			$result6 = mysql_query("SELECT * FROM $table10 WHERE pic_id = '$pic_id'");
			$num6 = mysql_num_rows($result6);
			$exiftool = buildExiftoolCommand($sr);
			$FN = strtolower($pic_path."/".restoreOriFilename($pic_id, $sr));
			shell_exec($exiftool." -IPTC:Keywords='' -overwrite_original ".$FN);
			
			IF($num6 == '1')
			{
				$result6_1 = mysql_query("DELETE FROM $table10 WHERE pic_id = '$pic_id'");
				$result7 = mysql_query("UPDATE $table2 SET has_kat = '0' WHERE pic_id = '$pic_id'");
				echo mysql_error();
			}
			ELSE
			{
				if ( !isset($kw) )
				{
					$kw = '';
				}
				$result8 = mysql_query( "SELECT * FROM $table10 WHERE pic_id = '$pic_id'");
				echo mysql_error();
				@$num8 = mysql_num_rows($result8);
				
				FOR($i8='0'; $i8<$num8; $i8++)
				{
					$KAT_ID = mysql_result($result8, $i8, 'kat_id');
					IF($KAT_ID !== '1')
					{
						$result9 = mysql_query( "SELECT kategorie FROM $table4 WHERE kat_id = '$KAT_ID'");
						$keywords = mysql_result($result9, isset($i9), 'kategorie');
						$KW = utf8_encode($keywords);
						$command = $exiftool." -IPTC:Keywords+=\"$KW\" -overwrite_original ".$FN;
						shell_exec($command);
						$kw .= $keywords." ";
					}
				}
				
				//Log-Datei schreiben:
				$result10 = mysql_query("SELECT Keywords, pic_id FROM $table2 WHERE pic_id = '$pic_id'");
				$kategorie_alt = utf8_encode(mysql_result($result10, isset($i10), 'Keywords'));
				//$kategorie = $kategorie_alt."".$kategorie;	//die neue Kat-Zuweisung entspricht der alten zzgl. der neu hinzugekommenen Kategorien
				$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
				fwrite($fh,date('d.m.Y H:i:s').": Kategoriezuordnung von Bild ".$pic_id." wurde von ".$c_username." modifiziert. (Zugriff von ".$_SERVER['REMOTE_ADDR']."\nalt: ".$kategorie_alt.", neu: ".$kw."\n");
				fclose($fh);
				
				$result11 = mysql_query( "UPDATE $table2 SET Keywords = \"$kw\" WHERE pic_id = '$pic_id'");
				echo mysql_error();	
			}
		}
	}
	echo "
	</div>
	<br style='clear:both;' />
	<p id='fuss'><A style='margin-right:745px;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>".$cr."</p>
</div>";

mysql_close($conn);
echo "<meta http-equiv='Refresh', Content='0, remove_kat_daten.php?pic_id=0&mod=edit_remove&kat_id=$parent'>";

?>
</DIV>
</CENTER>
</BODY>
</HTML>