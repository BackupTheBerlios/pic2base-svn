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
<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
<TITLE>pic2base - Histogramm-Kontrolle</TITLE>
<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel=stylesheet type="text/css" href='../../css/format1.css'>
<link rel="shortcut icon" href="../../share/images/favicon.ico">
<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
</HEAD>

<BODY LANG="de-DE" scroll="auto">

<CENTER>

<DIV Class="klein"><?php

/*
 * Project: pic2base
 * File: generate_histogram0.php
 *
 * Copyright (c) 2006 - 2009 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 * Skript erzeugt Histogramme und speichert sie im Histogramm-Ordner
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

$conv = buildConvertCommand($sr);

$result0 = mysql_query( "SELECT * FROM $table1 WHERE username = '$c_username'");
$user_id = mysql_result($result0, isset($i0), 'id');


echo "<div class='page'>

	<p id='kopf'>pic2base :: Histogramm-Erzeugung <span class='klein'>(User: <?echo $c_username;?>)</span></p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
include '../../html/admin/adminnavigation.php';
echo "
		</div>
	</div>
	
	<div class='content'>
	<p style='margin:120px 0px; text-align:center'>
	
	<center>
	Status der Histogramm-Erstellung
	<div id='prog_bar' style='border:solid; border-color:red; width:500px; height:12px; margin-top:30px; text-align:left; vertical-align:middle'>
	<img src='.$sr.'/bin/share/images/green.gif' name='bar' />
	</div>
	<p id='zaehler'>1</p>
	</center>";

FOR($note='1'; $note<'6'; $note++)
{
	$result4 = mysql_query( "SELECT * FROM $table2 WHERE note ='$note' ORDER BY pic_id");
	$num4 = mysql_num_rows($result4);
	//echo $num4." Bilder sind ohne Histogramm.<BR>";

	FOR($i4='0'; $i4<$num4; $i4++)
	{
			
		$pic_id = mysql_result($result4, $i4, 'pic_id');
		$FileName = mysql_result($result4, $i4, 'FileNameHQ');
			
		//Wenn mind. ein Bild nicht vorhanden ist, werden die Histogramme neu erstellt:
		$hist = $hist_path."/".$pic_id."_hist.gif";
		$hist_r = $hist_path."/".$pic_id."_hist_0.gif";
		$hist_g = $hist_path."/".$pic_id."_hist_1.gif";
		$hist_b = $hist_path."/".$pic_id."_hist_2.gif";
		IF(@!fopen($hist, 'r') OR @!fopen($hist_r, 'r') OR @!fopen($hist_g, 'r') OR @!fopen($hist_b, 'r'))
		{
			//$file = $pic_path."/".$FileName;
			$file = trim($pic_hq_path."/".$FileName);
			echo $file."<BR>";
			shell_exec($conv." ".$file." -separate histogram:".$hist_path."/".$pic_id."_hist_%d.gif");

			shell_exec($conv." ".$file." -colorspace Gray -quality 80% ".$monochrome_path."/".$pic_id."_mono.jpg");
			$file_mono = $monochrome_path."/".$pic_id."_mono.jpg";
			shell_exec($conv." ".$file_mono." -colorspace Gray histogram:".$hist_path."/".$pic_id."_hist.gif");

			$hist_file_r = $pic_id.'_hist_0.gif';
			shell_exec($conv." ".$hist_path."/".$hist_file_r." -fill red -opaque white ".$hist_path."/".$hist_file_r);
			//shell_exec($conv." ".$hist_path."/".$hist_file_r." ".$hist_path."/".$hist_file_r);

			$hist_file_g = $pic_id.'_hist_1.gif';
			shell_exec($conv." ".$hist_path."/".$hist_file_g." -fill green -opaque white ".$hist_path."/".$hist_file_g);

			$hist_file_b = $pic_id.'_hist_2.gif';
			shell_exec($conv." ".$hist_path."/".$hist_file_b." -fill blue -opaque white ".$hist_path."/".$hist_file_b);

			$hist_file = $pic_id.'_hist.gif';
			$result2 = mysql_query( "UPDATE $table2 SET FileNameHist = '$hist_file', FileNameHist_r = '$hist_file_r', FileNameHist_g = '$hist_file_g', FileNameHist_b = '$hist_file_b' WHERE pic_id = '$pic_id'");
			echo mysql_error();
		}
			
		$laenge = (round((($i4 + 1) / $num4) * 500));
		$anteil = number_format(((($i4 + 1) / $num4)*100),2,',','.');
		flush();
		//sleep(0.1);
		$text = "Durchlauf f&uuml;r Bilder mit der Note ".$note."<BR>Bild ".$pic_id."<BR>Datensatz ".$i4." von ".$num4."<BR>".$anteil."%";;
		?> <SCRIPT language="JavaScript">
			document.bar.src='../../share/images/green.gif';
			document.bar.width=<?php echo $laenge?>;
			document.bar.height='11';
			document.getElementById('zaehler').innerHTML='<?php echo $text?>';
			</SCRIPT> <?php
			IF(($i4 + 1) == $num4)
			{
				echo "<meta http-equiv='Refresh', Content='10; URL=../../html/admin/adminframe.php'>";
			}
	}
}
?>

</p>
</div>
<br style="clear: both;" />
<p id="fuss"><A style='margin-right: 745px;'
	HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A><?php echo $cr; ?></p>

</div>
</DIV>
</CENTER>
</BODY>
</HTML>
