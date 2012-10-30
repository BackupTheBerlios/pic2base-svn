<?php 
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
  	header('Location: ../../../index.php');
}
else
{
	$uid = $_COOKIE['uid'];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - DB-Export</TITLE>
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
 * File: db_export_action.php
 *
 * Copyright (c) 2003 - 2012 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 */

if(array_key_exists('PWD',$_POST))
{
	$pwd = $_POST['PWD']; 
}

if(array_key_exists('db_user', $_POST))
{
	$db_user = $_POST['db_user'];
}

if(array_key_exists('method', $_POST))
{
	$method = $_POST['method'];
}

/*
unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
	$benutzername = $c_username;
}
*/

INCLUDE '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';

echo "
<div class='page'>

	<p id='kopf'>pic2base :: Admin-Bereich - Datenbank-Export</p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
		 include '../../html/admin/adminnavigation.php';
		echo "</div>
	</div>
	
	<div id='spalte1'>
	
	<font color='green'>
		<p style='margin-top:20px;'>
		<b>pic2base - Export</b>
		</p>
	
		<p style='margin-top:20px;'>
		<!--<b>Die Datenbank wird exportiert...</b>-->
		</p>
	</font>";
//	echo "User: ".$db_user.", PWD: ".$pwd.", Methode: ".$method."<BR><BR>";
	switch($method)
	{
		CASE 'sql':
			$statement = "/opt/lampp/bin/mysqldump -u$db_user -p$pwd -h localhost $db > $kml_dir/pic2base.sql";
			system($statement, $fp);
			if ($fp==0)
			{
				echo "<BR><font color='green'>Der Export verlief erfolgreich.<BR><BR>F&uuml;r den Download der Export-Datei<BR>(rechts-)klicken Sie bitte <a href='../../../userdata/$uid/kml_files/pic2base.sql'>hier</a>.</font>"; 
			}
			else
			{
				echo "<BR><font color='red'>Es ist ein Fehler aufgetreten.<BR>Bitte kontrollieren Sie Ihre Zugangsdaten.<BR>Achten Sie besonders darauf, einen MySQL-Benutzer<BR>mit <b>Admin-Rechten</b> einzutragen.</font>"; 
			}
		break;
		
		CASE 'xml':
			ini_set('memory_limit', '500M');
			//alle Tabellen ermitteln...
			$result1 = mysql_query('SHOW TABLES FROM pic2base') or die('... kann Tabellen nicht anzeigen...');
			if(mysql_num_rows($result1) > 0)
			{
			  //Ausgabe erzeugen
			  $tab = "\t";
			  $br = "\n";
			  $xml = '<?xml version="1.0" encoding="UTF-8"?>'.$br;
			  $xml.= '<database name="pic2base">'.$br;
			  
			  //fuer jede Tabelle...
			  while($table = mysql_fetch_row($result1))
			  {
				    //prep table out
				    $xml.= $tab.'<table name="'.$table[0].'">'.$br;
				    
				    //Zeilen auslesen
				    $records = mysql_query('SELECT * FROM '.$table[0]) or die('kann nichts auslesen aus Tabelle: '.$table[0]);
				    echo mysql_error();
//			    	echo "Tabelle ".$table[0]." hat ".mysql_num_fields($records)." Spalten.<BR>";
	
				    //Tabellenattribute
				    $attributes = array('name','blob','max_length','multiple_key','not_null','numeric','primary_key','table','type','unique_key','unsigned','zerofill');
				    $xml.= $tab.$tab.'<columns>'.$br;
				    $x = 0;
				    while($x < mysql_num_fields($records))
				    {
					      $meta = mysql_fetch_field($records, $x);
					      $xml.= $tab.$tab.$tab.'<column ';
					      foreach($attributes as $attribute)
					      {
					        $xml.= $attribute.'="'.$meta->$attribute.'" ';
					      }
					      $xml.= '/>'.$br;
					      $x++;
				    }
				    $xml.= $tab.$tab.'</columns>'.$br;
	
				    //Datensaetze einfuegen
				    $xml.= $tab.$tab.'<records>'.$br;
				    while($record = mysql_fetch_assoc($records))
				    {	
					      $xml.= $tab.$tab.$tab.'<record>'.$br;
					      foreach($record as $key=>$value)
					      {
					        $value = utf8_encode($value);	
					      	$xml.= $tab.$tab.$tab.$tab.'<'.$key.'>'.htmlspecialchars(stripslashes($value)).'</'.$key.'>'.$br;
					      }
					      $xml.= $tab.$tab.$tab.'</record>'.$br;
				    }
				    $xml.= $tab.$tab.'</records>'.$br;
				    $xml.= $tab.'</table>'.$br;
			  }
			  $xml.= '</database>';
			  
			  //Datei speichern
			  $handle = fopen($kml_dir.'/pic2base.xml','w+');
			  fwrite($handle,$xml);
			  fclose($handle);
			}
			 $fh = fopen($kml_dir.'/pic2base.xml','r');
			 if($fh)
			 {
			 	echo "<BR><font color='green'>Der Export verlief erfolgreich.<BR><BR>F&uuml;r den Download der Export-Datei<BR>(rechts-)klicken Sie bitte <a href='../../../userdata/$uid/kml_files/pic2base.xml'>hier</a>.</font>"; 
			 }
			 else
			 {
			 	echo "<BR><font color='red'>Es ist ein Fehler aufgetreten.<BR>Bitte kontaktieren Sie Ihren Systemadministrator.</font>"; 
			 }
			
		break;

		CASE 'csv':
			echo "... der CSV-Export ist noch nicht implementiert...";
		break;
	}
	
	
	echo "
	</div>	
	
	<DIV id='spalte2'>
		<p id='elf' style='background-color:white; padding: 5px; width: 395px; margin-top: 54px; margin-left: 5px;'>Wenn der Export erfolgreich war, liegt die Datei nun in Ihrem FTP-Bereich im Ordner \"kml_files\".<BR><BR>
		Alternativ k&ouml;nnen Sie die Datei auch &uuml;ber den Link in der linken Spalte herunterladen.</p>
	</DIV>
	
	<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>

</div>
</CENTER>
</BODY>";
?>