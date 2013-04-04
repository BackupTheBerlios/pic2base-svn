<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Layout-Test</title>
  <meta name="GENERATOR" content="eclipse">
  <meta name="AUTHOR" content="k. henneberg">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv='refresh'; content='5; url=../html/recherche/recherche2.php?pic_id=0&mod=collection'>
  <link rel=stylesheet type='text/css' href='../css/format2.css'>
  <link rel="shortcut icon" href="../share/images/favicon.ico">
  <script language="JavaScript" src="../share/functions/resize_elements.js"></script>
  <script language="JavaScript" src="../share/functions/jquery-1.8.2.min.js"></script>
  <script language="JavaScript">
  	jQuery.noConflict();
	jQuery(document).ready(checkWindowSize);
	jQuery(window).resize(checkWindowSize); 
  </script>
</head>

<body>

<?php
IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../index.php');
}

if ( array_key_exists('uid',$_COOKIE) )
{
	$uid = $_COOKIE['uid'];
}

if ( array_key_exists('coll_id',$_GET) )
{
	$coll_id = $_GET['coll_id'];
}
//#######################################################################################################################################################
//
//Datei wird verwendet, um alle Bilder einer Kollektion in den Download-Ordner des angemeldeten Users zu kopieren, falls dieser die Berechtigung dazu hat
//
//#######################################################################################################################################################

include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/permissions.php';

echo "
<DIV Class='klein'>
	<div id='page'>
	
		<div id='head'>
			pic2base :: Bilder kopieren
		</div>
		
		<div id='navi'>
			<div class='menucontainer'></div>
		</div>
		
		<div id='content'>		
			<fieldset style='background-color:none; margin-top:10px;'>
			<legend style='color:blue; font-weight:bold;'>Kopiere alle Bilder der Kollektion in den Download-Ordner...</legend>
			<div id='scrollbox0' style='overflow-y:scroll;'>
				<p style='margin-top:20px; text-align:center';>Bitte warten Sie einen Moment...</p>
				<center>
				<img src='images/loading.gif' style='margin-top:50px;'>
				</center>";
				
				$exiftool = buildExiftoolCommand($sr);
				$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
				$username = mysql_result($result0, isset($i0), 'username');
				
				//Bestimmung der Bilder der ausgewaehlten Kollektion:
				$result1 = mysql_query("SELECT $table25.pic_id, $table25.coll_id, $table2.pic_id, $table2.FileName
				FROM $table25, $table2
				WHERE $table2.pic_id = $table25.pic_id
				AND $table25.coll_id = '$coll_id'");
				if(mysql_error() !== '')
				{
					echo "Fehler bei der Bestimmung der Bilder: <br>"; echo mysql_error();
				}
				
				$num1 = mysql_num_rows($result1);
				//echo "Es wurden ".$num1." Bilder gefunden.<br>";
				
				for($i1=0; $i1<$num1; $i1++)
				{
					//echo "Durchlauf ".$i1."<br>";
					$FileName = mysql_result($result1, $i1, 'FileName');
					$pic_id = mysql_result($result1, $i1, 'pic_id');
					$datei = $pic_path."/".$FileName;
					$target = $ftp_path."/".$uid."/downloads/".$FileName;
					
					if(@copy($datei,$target))
					{
						$result2 = mysql_query( "UPDATE $table2 SET ranking = ranking + 1 WHERE pic_id = '$pic_id'");
						//Log-Datei schreiben:
						$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
						fwrite($fh,date('d.m.Y H:i:s').": Bild ".$pic_id." wurde von ".$username." heruntergeladen. (Zugriff von ".$_SERVER['REMOTE_ADDR'].")\n");
						fclose($fh);
					}
					else
					{
						echo "Konnte die Datei $FileName nicht kopieren!<BR>";
					}
					//es wird geprueft, ob ggf. ein Originalbild als NICHT-JPG vorliegt
					$file_info = pathinfo($datei);
					$base_name = substr($file_info['basename'],0,-4);
					//echo $base_name;
					$k = '0';
					FOREACH($supported_filetypes AS $sft)
					{
						IF(file_exists($pic_path."/".$base_name.".".$sft) AND $sft !== 'jpg')
						{
							copy($pic_path."/".$base_name.".".$sft, $ftp_path."/".$uid."/downloads/".$base_name.".".$sft);
							//die Meta-Daten dieses nicht-jpg-Bildes werden in das bereits herauskopierte jpg-Bild uebertragen:
							$command = $exiftool." -tagsFromFile ".$ftp_path."/".$uid."/downloads/".$base_name.".".$sft." ".$ftp_path."/".$uid."/downloads/".$FileName." -overwrite_original";
							//echo $command;
							shell_exec($command." > /dev/null &");
							$k++;
						}
					}
				}
				
				echo "
			</div>
			</fieldset>
		</div>
		
		<div id='foot'>
			<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
		</div>
	
	</div>
</DIV>";
?>