<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base :: Datensatz-Bearbeitung</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../css/format1.css'>
	<link rel="shortcut icon" href="../share/images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>
<DIV Class="klein">

<?php

/*
 ##########################  DIESE DATEI WIRD MÖGLICHERWEISE N I C H T GENUTZT ########################################
 * Project: pic2base
 * File: vorlage.php
 *
 * Copyright (c) 2003 - 2005 Klaus Henneberg
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
 * @package INTRAPLAN
 * @license http://www.opensource.org/licenses/osl-2.1.php Open Software License
 */
//echo $mod;
function getHQPreviewNow($pic_id, $hoehe_neu, $breite_neu)
{
	//echo "Vorschau...";
	include 'db_connect1.php';
	$res0 = mysql_query( "SELECT * FROM $table2 WHERE pic_id='$pic_id'");
	$FileName = mysql_result($res0, $i1, 'FileName');
	$FileNameHQ = mysql_result($res0, $i1, 'FileNameHQ');
	$FileNameV = mysql_result($res0, $i1, 'FileNameV');
	$Width = mysql_result($res0, $i1, 'Width');
	$Height = mysql_result($res0, $i1, 'Height');
	$Orientation = mysql_result($res0, $i1, 'Orientation');	// 1: normal; 8: 90° nach rechts gedreht
	
	//abgeleitete Größen:
	
	SWITCH($Orientation)
	{
		CASE '1':
		
		break;
		
		CASE '8':
		$Height = mysql_result($res0, $i1, 'Width');
		$Width = mysql_result($res0, $i1, 'Height');
		break;
	}
	
	IF ($FileNameV == '')
	{
		$FileNameV = 'no_preview.jpg';
	}
	$filenameori = substr($FileNameOri,0,25);
	$size = round($FileSize / 1024);
	IF ($DateTime == '0000-00-00 00:00:00' OR $DateTime == '')
	{
		$datum = 'unbekannt';
	}
	ELSE
	{
		$datum = date('d.m.Y', strtotime($DateTime));
	}
	
	$parameter_v=getimagesize('../../images/vorschau/thumbs/'.$FileNameV);
	$breite = $parameter_v[0] * 5;
	$hoehe = $parameter_v[1] * 5;
	//echo "Breite: ".$breite.", Höhe: ".$hoehe."<BR>";
	IF ($breite == 0 AND $hoehe == 0)
	{
		$breite = 800;
		$hoehe = 600;
	}
	//echo "Breite: ".$breite.", Höhe: ".$hoehe."<BR>";
      	$width_height=$parameter_v[3];
      	//Für die Darstellung des Vollbildes wird eine mittlete Größe unter Beachtung des Seitenverhältnisses errechnet:
      	//max. Ausdehnung: 800px
      	$max = '1000';
      	IF ($Width >= $Height)
      	{
      		$breite = $max;
      		$hoehe = $breite * $Height / $Width;
      	}
      	ELSE
      	{
      		$hoehe = $max;
      		$breite = number_format(($hoehe * $Width / $Height),0,',','.');
      	}
      	$ratio_pic = $breite / $hoehe;
      	//echo "Breite: ".$breite.", Höhe: ".$hoehe."<BR>";
      	$bild = '../../images/vorschau/hq-preview/'.$FileNameHQ;
	echo "<a href='#' target=\"vollbild\" onclick=\"ZeigeBild('$bild', '$breite', '$hoehe', '$ratio_pic');return false\"  title='Vergr&ouml;&#223;erte Ansicht'><img src='../../images/vorschau/thumbs/$FileNameV' alt='Vorschaubild', width='$breite_neu', height='$hoehe_neu'></a>";?>
	
	<SCRIPT language="javascript">
	function ZeigeBild(bildname,breite,hoehe,ratio_pic)
	{
	anotherWindow = window.open("", "bildfenster", "");
	
	// Wird bereits ein Bild in der "Großansicht" angezeigt? - dann wird es geschlossen:
	if (anotherWindow != null)
	{
		//alert("Zu!");
		anotherWindow.close();
	}  
	
	ratio_screen = screen.width / screen.height;
		if(ratio_screen > 2.2)
		{
			//wenn mit zwei Bildschirmen gearbeitet wird:
			ratio_screen = ratio_screen / 2;
			sw = screen.width / 2;
		}
		
		if(ratio_screen >= ratio_pic)
		{
			breite = breite * screen.height / hoehe;
			hoehe = screen.height;
		}
		else
		{
			hoehe = hoehe * sw / breite;
			breite = sw;
		}
	
	var ref,parameter,dateiname,htmlcode,b=breite,h=hoehe;
	
	dateiname=bildname.substring(bildname.indexOf("/")+1,bildname.length);
	
	htmlcode="<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
	htmlcode+="<html style=\"height: 100%\">\n<head>\n<title>"+dateiname+"<\/title>\n";
	htmlcode+="<\/head>\n<body style=\"margin: 0; padding: 0; height: 100%\"><center>\n";
	htmlcode+="<img src=\""+bildname+"\" height=\"100%\" alt=\""+bildname+"\" title=\"[Mausklick schlie&szlig;t Fenster!]\" onclick=\"window.close()\">\n</center><\/body>\n<\/html>\n";
	
	parameter="width="+b+",height="+h+",screenX="+(screen.width-b)/2+",screenY="+(screen.height-h)/2+",left="+(screen.width-b)/2+",top="+(screen.height-h)/2;
	
	ref=window.open("","bildfenster",parameter);
	ref.document.open("text/html");
	ref.document.write(htmlcode);
	ref.document.close();
	ref.focus();
	}
	</Script>
	
	<?php
}
//echo $bewertung;
unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = split(',',$_COOKIE['login']);
//echo $c_username;
}
ELSE
{
	echo "bearbeitung1 meldet: Kein Cookie gesetzt?";
}

list($bewertung) = split(',',$_COOKIE['bewertung']);
IF($bew == '')
{
	$bew = '6';
}
//echo "Kontrolle: Bewertung: ".$bew."<BR>";

//include 'user_check1.php';
include 'db_connect1.php';
include 'functions/main_functions.php';

//log-file schreiben:
$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
fwrite($fh,date('d.m.Y H:i:s')." ".$REMOTE_ADDR." ".$_SERVER['PHP_SELF']." ".$_SERVER['HTTP_USER_AGENT']." ".$c_username."\n");
fclose($fh);

$result000 = mysql_query( "SELECT * FROM $table1 WHERE username = '$c_username' AND aktiv = '1'");
$berechtigung = mysql_result($result000, $i000, 'berechtigung');

//Festlegung der Datenanzeige:


SWITCH($bedingung1)
{
	CASE 'LIKE':
	$string1 = '%'.$zusatzwert1.'%';
	break;
	
	DEFAULT:
	$string1 = $zusatzwert1;
	BREAK;
}

if ($kriterium == "")
{
	$kriterium = $zusatz1." ".$bedingung1." '".$string1."'";
}
else
{
	$kriterium = stripslashes($kriterium);
}
//echo $kriterium;

SWITCH($bew)
{
	CASE '1':
	$bewert = "Recherche über sehr gute Bilder";
	break;
	
	CASE '2':
	$bewert = "Recherche über gute Bilder";
	break;
	
	CASE '3':
	$bewert = "Recherche über m&auml;ssige Bilder";
	break;
	
	CASE '4':
	$bewert = "Recherche über mangelhafte Bilder";
	break;
	
	CASE '5':
	$bewert = "Recherche über nicht bewertete Bilder";
	break;
	
	CASE '6':
	CASE '':
	$bewert = "Recherche über alle Bilder";
	$bewertung = '6';
	break;
}

IF($bew < '6')
{
	$result0 = mysql_query( "SELECT * FROM $table2 WHERE ($kriterium) AND note = '$bew'");
}
ELSE
{
	$result0 = mysql_query( "SELECT * FROM $table2 WHERE ($kriterium)");
}
//$result0 = mysql_query( "SELECT * FROM $table2 WHERE ($kriterium) AND note = '$bew'");
@$num0 = mysql_num_rows($result0);
$N = 5;			//Anzahl der anzuzeigenden Datensätze

IF ($num0 > $N)
{
	IF ($div + $N > $num0)
	{
		$bereichsende = $num0;
	}
	ELSE
	{
		$bereichsende = $div + $N;
	}
	$anzeigebereich = "; Anzeige der Datens&auml;tze ".($div + 1)." - ".$bereichsende;
}

//echo "Select-Klausel: ".$zusatz1.$bedingung1.$zusatzwert1."<BR>";
//echo "Select-Klausel: ".$kriterium."<BR>";

IF($bew < '6')
{
	$result1 = mysql_query( "SELECT * FROM $table2 WHERE ($kriterium) AND note = '$bew' ORDER BY 'DateTime' LIMIT $div, $N ");
}
ELSE
{
	$result1 = mysql_query( "SELECT * FROM $table2 WHERE ($kriterium) ORDER BY 'DateTime' LIMIT $div, $N");
}
//$result1 = mysql_query( "SELECT * FROM $table2 WHERE ($kriterium) LIMIT $div, $N");
@$num1 = mysql_num_rows($result1);
echo "
<div class='page'>";
	IF($num0 == '')
	{
		$num0 = '0';
	}
	SWITCH ($mod)
	{
		CASE 'edit':
		echo "<p id='kopf'>pic2base :: Datensatz-Bearbeitung <span class='klein'>(".$num0." Treffer".$anzeigebereich.")</span></p>";
		break;
		
		CASE 'exif':
		echo "<p id='kopf'>pic2base :: Datensatz-Recherche <span class='klein'>(eingestellte Bewertung: ".$bewert.", ".$num0." Treffer".$anzeigebereich.")</span></p>";
		break;
	}
	
	echo "	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
		createNavi2_2($c_username, $mod);
		echo "</div>
	</div>
	
	<div class='content'>";
	IF ($num0 == '0')
	{
	echo "
	<p style='margin:100px 0px; text-align:center'>
	<TABLE border = '0' class='liste1' align = 'center'>
	<TR class='liste1'>
	<TD class='normal' align='center'><font color=red>Ihre Suchanfrage ergab 0 Treffer!</font>
	</TD>
	</TR>
	</TABLE>
	</p>
	<p style='margin:100px 0px; text-align:center'></p>
	</div>";
	}
	ELSE
	{
	echo "
	<p style='margin:0px 0px; text-align:center'>
	<TABLE border = '0' class='liste1' align = 'center'>";
	
	$fs_hoehe = 90;
	$bgcolor = '#DDDDDD';
	FOR ($i1=0; $i1<$num1; $i1++)
	{
		$pic_id = mysql_result($result1, $i1, 'pic_id');
		$FileNameOri = mysql_result($result1, $i1, 'FileNameOri');
		$FileName = mysql_result($result1, $i1, 'FileName');
		$FileNameHQ = mysql_result($result1, $i1, 'FileNameHQ');
		$FileNameV = mysql_result($result1, $i1, 'FileNameV');
		$FileSize = mysql_result($result1, $i1, 'FileSize');
		$Width = mysql_result($result1, $i1, 'Width');
		$Height = mysql_result($result1, $i1, 'Height');
		$DateTime = mysql_result($result1, $i1, 'DateTime');		//Aufnahme-Datum
		$Orientation = mysql_result($result1, $i1, 'Orientation');	// 1: normal; 8: 90° nach rechts gedreht
		//abgeleitete Größen:
		IF ($FileNameV == '')
		{
			$FileNameV = 'no_preview.jpg';
		}
		$filenameori = substr($FileNameOri,0,25);
		$size = round($FileSize / 1024);
		IF ($DateTime == '0000-00-00 00:00:00')
		{
			$datum = 'unbekannt';
		}
		ELSE
		{
			$datum = date('d.m.Y', strtotime($DateTime));
		}
		
		IF ($bgcolor == '#FFFFFF')
		{
			$bgcolor = '#DDDDDD';
		}
		ELSE
		{
			$bgcolor = '#FFFFFF';
		}
		@$parameter_v=getimagesize('../../images/vorschau/hq-preview/'.$FileNameHQ);
		$breite = $parameter_v[0];
		$hoehe = $parameter_v[1];
		IF ($breite == 0 AND $hoehe == 0)
		{
			$breite = 800;
			$hoehe = 600;
		}
		ELSE
		{
			$hoehe_neu = $fs_hoehe;
			$breite_neu = number_format(($fs_hoehe * $breite / $hoehe),0,',','.');
		}
      		$width_height=$parameter_v[3];
      		$bild = '../../images/vorschau/hq-preview/'.$FileNameHQ;
		echo "
		<TR bgcolor = '$bgcolor'>
			<TR  class='liste2' bgcolor = '$bgcolor'>
				<TD class='liste2' width = '33%'>Original-Name</TD>
				<TD class='liste2' width = '33%'>$filenameori</TD>
				";
				SWITCH ($mod)
				{
					CASE 'edit':
					echo "<TD class='normal'rowspan='6' align='center'>
					<a href='bearbeiten2.php?mod=$mod&pic_id=$pic_id' title='Bilddaten bearbeiten'><img src='../share/images/edit.gif' style='border:none;'></a>
					</TD>";
					break;
					
					CASE 'exif':
					echo "<TD class='normal'rowspan='6' align='center'>
					<a href='../html/recherche/recherche1.php?mod=$mod&pic_id=$pic_id' title='Detaillierte Informationen zum Bild'><img src='../share/images/info.gif' style='border:none;'></a>
					</TD>";
					break;
				}
				
				echo "
				<TD class='normal' rowspan='6' align='center'>";
				getHQPreviewNow($pic_id, $hoehe_neu, $breite_neu);
				echo "
				</TD>
			</TR>
			<TR  class='liste2' bgcolor = '$bgcolor'>
				<TD class='liste2'>Int. Datei-Name</TD>
				<TD class='liste2'>$FileName</TD>
			</TR>
			<TR  class='liste2' bgcolor = '$bgcolor'>
				<TD class='liste2'>Datei-Größe (kB)</TD>
				<TD class='liste2'>$size</TD>
			</TR>
			<TR  class='liste2' bgcolor = '$bgcolor'>
				<TD class='liste2'>Bild-Breite (px)</TD>
				<TD class='liste2'>$Width</TD>
			</TR>
			<TR  class='liste2' bgcolor = '$bgcolor'>
				<TD class='liste2'>Bild-Höhe (px)</TD>
				<TD class='liste2'>$Height</TD>
			</TR>
			<TR  class='liste2' bgcolor = '$bgcolor'>
				<TD class='liste2'>Aufnahme-Datum</TD>
				<TD class='liste2'>$datum</TD>
			</TR>
		</TR>";
	}
	
	echo "
	<TR class='liste2'>
	<TD class='normal' align='center' colspan='4'>";

	IF ($num0 > $N)
	{	
		$crit = "";
		for ($i = 0; $i < strlen($kriterium); $i++)
		{
			$crit .= "%".bin2hex($kriterium[$i]);
		}
		
		function Ergebnisbereich($div, $num0, $N, $kriterium, $mod, $bew)
		{
			IF ($div !== '0')
			{
				$div1 = $div - $N;
				IF ($div1 < 0)
				{
					$div1 = 0;
				}
				ELSE
				{
				
				}
			}
			ELSE
			{
				$div1 = '0';
			}
			
			IF ($div < $num0 - $N)
			{
				$div2 = $div + $N;
			}
			ELSE
			{
				$div2 = $num0 - $N;
			}
			
			$div3 = $num0 - $N;
			
			echo "
			<A HREF='bearbeitung1.php?div=0&mod=$mod&kriterium=$kriterium&bew=$bew' title='zu den ersten $N Datensätzen'>
			<IMG src='../share/images/anfang.gif' width='15' height='15' border='0'>
			</A>
			
			<A HREF='bearbeitung1.php?div=$div1&mod=$mod&kriterium=$kriterium&bew=$bew' title='$N Datensätze zurück'>
			<IMG src='../share/images/zurueck.gif' width='15' height='15' border='0'>
			</A>
			
			<A HREF='bearbeitung1.php?div=$div2&mod=$mod&kriterium=$kriterium&bew=$bew' title='$N Datensätze vor'>
			<IMG src='../share/images/vor.gif' width='15' height='15' border='0'>
			</A>
			
			<A HREF='bearbeitung1.php?div=$div3&mod=$mod&kriterium=$kriterium&bew=$bew' title='zu den letzten $N Datensätzen'>
			<IMG src='../share/images/ende.gif' width='15' height='15' border='0'>
			</A>";
		}
		echo Ergebnisbereich($div, $num0, $N, $crit, $mod, $bew);
	}
	
echo"	</TD>
	</TR>
	</TABLE></p>
	</div>";
	}
	echo "
	<br style='clear:both;' />
	<p id='fuss'>".$cr."</p>
</div>";

mysql_close($conn);

?>
</DIV>
</CENTER>
</BODY>
</HTML>