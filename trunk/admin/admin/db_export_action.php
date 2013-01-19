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
	<META NAME="GENERATOR" CONTENT="eclipse">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format2.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script language="JavaScript" src="../../share/functions/resize_elements.js"></script>
	<script language="JavaScript" src="../../share/functions/jquery-1.8.2.min.js"></script>
	<script language="JavaScript">
	  	jQuery.noConflict()
		jQuery(document).ready(checkWindowSize);
		jQuery(window).resize(checkWindowSize); 
	</script>
</HEAD>

<BODY>

<CENTER>

<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: db_export_action.php
 *
 * Copyright (c) 2003 - 2013 Klaus Henneberg
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

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');

echo "
<div class='page' id='page'>

	<div class='head' id='head'>
		pic2base :: Admin-Bereich - Datenbank-Export
	</div>
	
	<div class='navi' id='navi'>
		<div class='menucontainer'>";
		 include '../../html/admin/adminnavigation.php';
		echo "</div>
	</div>
	
	<div id='spalte1'>
	
	<font color='green'>
		<p style='margin-top:20px;'>
		<b>Export - Ergebnis</b>
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
				echo "
				<fieldset style='background-color:none; margin-top:10px;'>
				<legend style='color:green; font-weight:bold;'>Gratulation!</legend>
					Der Export verlief erfolgreich.<BR>
					<BR>F&uuml;r den Download der Export-Datei<BR>(rechts-)klicken Sie bitte <a href='../../../userdata/$uid/kml_files/pic2base.sql'>hier</a>.
				</fieldset>";
				
				//log-file schreiben:
				$fh_log = fopen($p2b_path.'pic2base/log/p2b.log','a');
				fwrite($fh_log,date('d.m.Y H:i:s').": Die pic2base-DB wurde von ".$username." als SQL exportiert. (Zugriff von ".$_SERVER['REMOTE_ADDR'].")\n");
				fclose($fh_log); 
			}
			else
			{
				echo "
				<fieldset style='background-color:none; margin-top:10px;'>
				<legend style='color:red; font-weight:bold;'>Fehler!</legend>
					Es ist ein Fehler aufgetreten.<BR>Bitte kontrollieren Sie Ihre Zugangsdaten.<BR>
				Achten Sie besonders darauf, einen MySQL-Benutzer<BR>mit <b>Admin-Rechten</b> einzutragen.
				</fieldset>";
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
			 	echo "
			 	<fieldset style='background-color:none; margin-top:10px;'>
				<legend style='color:green; font-weight:bold;'>Gratulation!</legend>
					Der Export verlief erfolgreich.<BR><BR>F&uuml;r den Download der Export-Datei<BR>(rechts-)klicken Sie bitte <a href='../../../userdata/$uid/kml_files/pic2base.xml'>hier</a>.</font>
				</fieldset>"; 
			 	//log-file schreiben:
				$fh_log = fopen($p2b_path.'pic2base/log/p2b.log','a');
				fwrite($fh_log,date('d.m.Y H:i:s').": Die pic2base-DB wurde von ".$username." als XML exportiert. (Zugriff von ".$_SERVER['REMOTE_ADDR'].")\n");
				fclose($fh_log);  
			 }
			 else
			 {
			 	echo "
			 	<fieldset style='background-color:none; margin-top:10px;'>
				<legend style='color:red; font-weight:bold;'>Fehler!</legend>
					Es ist ein Fehler aufgetreten.<BR>Bitte kontaktieren Sie Ihren Systemadministrator.
				</fieldset>"; 
			 }
			
		break;

		CASE 'csv':
			echo "... der CSV-Export ist noch nicht implementiert...";
		break;
	}
	
	
	echo "
	</div>	
	
	<DIV id='spalte2'>
	
		<font color='#efeff7'>
			<p  style='margin-top:20px;'>.</p>
		</font>
		<fieldset style='background-color:none; margin-top:10px;'>
		<legend style='color:blue; font-weight:bold;'>Hinweis</legend>
			Wenn der Export erfolgreich war, liegt die Datei nun in Ihrem FTP-Bereich im Ordner \"kml_files\".<BR><BR>
		Alternativ k&ouml;nnen Sie die Datei auch &uuml;ber den Link in der linken Spalte herunterladen.
		</fieldset>
		
	</DIV>
	
	<div class='foot' id='foot'>
		<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
	</div>

</div>
</CENTER>
</BODY>";
?>