<?php
IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../index.php');
}
else
{
	$uid = $_COOKIE['uid'];
}
//###############################################################################################
//
// wird verwendet, wenn Bilder gesucht werden sollen und die Auswahl nach Aufnahmedatum erfolgt #
//
//###############################################################################################

//Ermittlung des Anfangs (min.) und Enddatums (max):

//var_dump($_REQUEST);
if(array_key_exists('pic_id',$_GET))
{
	$pic_id = $_GET['pic_id'];
}
if(array_key_exists('mod',$_GET))
{
	$mod = $_GET['mod'];
}
if(array_key_exists('s_m',$_GET))
{
	$s_m = $_GET['s_m'];
}
if(array_key_exists('bewertung',$_GET))
{
	$bewertung = $_GET['bewertung'];
}
if(array_key_exists('show_mod',$_GET))
{
	$show_mod = $_GET['show_mod'];
	if($show_mod !== 'J')
	{
		setcookie("show_mod", $show_mod, 0, "/");
	}
}
else
{
	if(!isset($show_mod))
	{
		$show_mod = 0;
	}
}

include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/ajax_functions.php';
include_once $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/css/initial_layout_settings.php';

$start1 = microtime();
	
//echo $bewertung;
$stat = createStatement($bewertung);
//echo $stat;
$base_file = 'recherche2';

list($start1msec,$start1sec) = explode(" ",$start1);

$result2 = mysql_query( "SELECT MAX(YEAR(DateTimeOriginal)) AS MAX_DTO, MIN(YEAR(DateTimeOriginal)) AS MIN_DTO 
FROM $table2 
WHERE YEAR(DateTimeOriginal) <> '0000'");
$Min_DT = mysql_result($result2, isset($i2), 'MIN_DTO');
$Max_DT = mysql_result($result2, isset($i2), 'MAX_DTO');
//echo "Fr&uuml;hestes Jahr: ".$Min_DT.", sp&auml;testes Jahr: ".$Max_DT."<BR>";
//########################################################################################################
echo "	<TABLE class='kat'>
		<TR class='kat'>
		<TD class='kat1'>Jahr / Monat / Tag</TD>
		<TD class='kat2'>Info</TD>
		<TD class='kat2'>Anz.</TD>
		</TR>";

$runtime_sum = 0;

//Bestimmung der Jahrgaenge, in denen Bilder entstanden sind:

$result1 = mysql_query( "SELECT DISTINCT YEAR(DateTimeOriginal) AS DTO, COUNT(*)
FROM $table2
WHERE YEAR(DateTimeOriginal) <> '0000'
AND aktiv = '1'
AND $stat 
GROUP BY YEAR(DateTimeOriginal)
ORDER BY YEAR(DateTimeOriginal) DESC");
$num1 = mysql_num_rows($result1);

//echo "Es gibt ".$num1." Jahrg&auml;nge.<BR>";

FOR($i1 = '0'; $i1<$num1; $i1++)
{
	$D = mysql_result($result1, $i1, 'DTO');
	//Die einzelnen "Jahrgaenge" werden ermittelt:
	//Zur Laufzeitoptimierung wird kontrolliert, ob in dem betreffenden Jahr ueberhaupt Bilder vorhanden sind:
	
	$num3 = mysql_result($result1,$i1,COUNT('*'));	

	IF ($num3 > 0)
	{
		$jahr = $D;
		$modus='recherche';
		IF(substr($show_mod,0,4) == $jahr)
		{
			$s_m = 'J';			//Anzeige-Modus (show-modus)
			$info_nr = '7';
			$tree_img = "<IMG src='$inst_path/pic2base/bin/share/images/minus.gif' width='11' height='11' hspace='3' vspace='0' border='0'>";
		}
		ELSE
		{
			$s_m = $jahr;		//Anzeige-Modus: Jahresuebersicht
			$info_nr = '1';
			$tree_img = "<IMG src='$inst_path/pic2base/bin/share/images/plus.gif' width='11' height='11' hspace='3' vspace='0' border='0'>";
		}
		//echo $s_m;
		$img_plus = "<IMG src='$inst_path/pic2base/bin/share/images/plus.gif' width='11' height='11' hspace='3' vspace='0' border='0'>";
		$img_minus = "<IMG src='$inst_path/pic2base/bin/share/images/minus.gif' width='11' height='11' hspace='3' vspace='0' border='0'>";
		echo "
		<TR class='kat'>
		<TD class='kat1' style='background-color:RGB(125,0,10); color:white;'>
		<SPAN style='cursor:pointer;' onClick='getTimeTreeview(\"$pic_id\",\"$mod\",\"$s_m\",\"$bewertung\")'  title='Monatsansicht f&uuml;r $jahr &ouml;ffnen / schlie&szlig;en'>".$tree_img."</SPAN>
		<SPAN style='cursor:pointer;' onClick='getTimePreview(\"$jahr\",0,0,0,\"$mod\",\"$modus\",\"$base_file\",\"$bewertung\",0,0)' title='alle Bilder des Jahres $jahr anzeigen'>".$jahr."</SPAN>
		</TD>
		<TD class='kat2'>
		</TD>
		<TD class='kat2'>".$num3."</TD>
		</TR>";
		
		IF(substr($show_mod,0,4) == $jahr)
		{				
			$result9 = mysql_query( "SELECT DATE_FORMAT(DateTimeOriginal,'%Y-%m'), COUNT(*), pic_id, note, DateTimeOriginal, aktiv
			FROM $table2
			WHERE DATE_FORMAT(DateTimeOriginal,'%Y') = '$jahr'
			AND DateTimeOriginal <> '0000-00-00 00:00:00'
			AND aktiv = '1'
			AND $stat
			GROUP BY DATE_FORMAT(DateTimeOriginal,'%Y-%m')
			ORDER BY DATE_FORMAT(DateTimeOriginal,'%Y-%m') DESC");
			echo mysql_error();
			
			$num9 = mysql_num_rows($result9); // Anzahl der Monate in denen Bilder angefertigt wurden
			
			IF($num9 > 0)
			{
				
				//Wenn in dem Monat Bilder gemacht wurden - Ermittlung der Anzahl:
				FOR($i9='0'; $i9<$num9; $i9++)
				{
					
					$anz = mysql_result($result9, $i9, COUNT('*'));
					$month_number = date('m', strtotime(mysql_result($result9, $i9, 'DateTimeOriginal')));
					echo "<TR class='kat'>
					<TD class='kat1'>&#160;&#160;";
					$modus = 'recherche';
					$zeit = getMonthName($month_number)." ".$jahr;
					//echo $zeit;
					IF(substr($show_mod,0,4) == $jahr AND substr($show_mod,5,2) !== $month_number)
					{
						//echo "normale Monats-Darstellung: ";
						$s_m = $jahr."_".$month_number;	//$show_mod: Anzeige-Modus fuer Tages-Anzeige
						echo "
						<SPAN style='cursor:pointer;' onClick='getTimeTreeview(\"$pic_id\",\"$mod\",\"$s_m\",\"$bewertung\")'  title='Tagesauswahl f&uuml;r ".getMonthName($month_number)." &ouml;ffnen'>".$img_plus."</SPAN>
						<SPAN style='cursor:pointer;' onClick='getTimePreview(\"$jahr\",\"$month_number\",0,0,\"$mod\",\"$modus\",\"$base_file\",\"$bewertung\",0,0)' title='alle Bilder vom ".getMonthName($month_number)." / ".$jahr." anzeigen'>".getMonthName($month_number)."</SPAN>
						</TD>
						<TD class='kat2'></TD>
						<TD class='kat2'>".$anz."</TD>
						</TR>";
					}
					ELSEIF(substr($show_mod,0,4) == $jahr AND substr($show_mod,5,2) == $month_number)
					{
						//echo "Monats- mit Tagesdarstellung: ";
						$y = substr($show_mod,0,4);	//Jahr
						$m = substr($show_mod,5,2);	//Monat
//						echo "Jahr: ".$y.", Monat: ".$m;
						IF($jahr == $y AND $month_number == $m)
						{
							$s_m = $jahr;
							echo "
							<SPAN style='cursor:pointer;' onClick='getTimeTreeview(\"$pic_id\",\"$mod\",\"$s_m\",\"$bewertung\")' title='Tagesansicht schlie&szlig;en'>".$img_minus."</SPAN>".getMonthName($month_number)."
							</TD>
							<TD class='kat2' colspan='2'></TD>
							</TR>";
							
							$result12 = mysql_query( "SELECT DATE_FORMAT(DateTimeOriginal,'%Y-%m-%d'), COUNT(*), pic_id, note, DateTimeOriginal, aktiv 
							FROM $table2 
							WHERE DateTimeOriginal LIKE '%".$y."-".$m."%' 
							AND DateTimeOriginal <> '0000-00-00 00:00:00'
							AND aktiv = '1' 
							AND $stat
							GROUP BY DATE_FORMAT(DateTimeOriginal,'%Y-%m-%d')
							ORDER BY DATE_FORMAT(DateTimeOriginal,'%Y-%m-%d')");
							$num12 = mysql_num_rows($result12);
							//echo "Es wurden ".$num12." Tage gefunden.<BR>";
							
							FOR($i12='0'; $i12<$num12; $i12++)
							{
								$datum = substr(mysql_result($result12, $i12, 'DateTimeOriginal'),0,10);
								$T = date('d',strtotime($datum));
								$aufn_dat = date('d.m.Y', strtotime($datum));
								$aufn_DAT = date('Y-m-d', strtotime($datum));
								
								//Ermittlung der Tagebuch-Eintraege:
								$result13 = mysql_query("SELECT * FROM $table3 WHERE datum = '$aufn_DAT' AND info <> ''");
								$num13 = mysql_num_rows($result13);
								
								IF($num13 !== 0)
								{
									$book = "<img src='$inst_path/pic2base/bin/share/images/book_green.gif' width='15' height='15' title='Tagebuch-Eintrag lesen / bearbeiten' />";
								}
								ELSE
								{
									$book = "<img src='$inst_path/pic2base/bin/share/images/book_grey.gif' width='15' height='15' title='Tagebuch-Eintrag hinzuf&uuml;gen' />";
								}
								
								//Ermittlung der an diesem Tag gemachten Bilder:
								$anz12 = mysql_result($result12, $i12, COUNT('*'));
								
								echo "<TR class='kat'>
								<TD class='kat1'> &#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;<SPAN style='cursor:pointer;' onClick='getTimePreview(\"$jahr\",\"$month_number\",\"$T\",0,\"$mod\",\"$modus\",\"$base_file\",\"$bewertung\",0,0)' title='alle Bilder vom $aufn_dat anzeigen'>".$aufn_dat."</span></TD>";
								echo "
								<TD class='kat2'>
								<span style='cursor:pointer;' onClick=\"showDiary('$aufn_DAT')\" />".$book."</SPAN>
								</TD>
								<TD class='kat2'>".$anz12."</TD>
								</TR>";
							}
						}
						ELSE
						{
							$s_m = $jahr."_".$month_number;
							echo "<SPAN style='cursor:pointer;' onClick='location.href=\"recherche2.php?pic_id=$pic_id&mod=$mod&show_mod=$s_m\"'>".getMonthName($month_number)."</SPAN>
							</TD>
							<TD class='kat2'>
							<SPAN style='cursor:pointer;' onClick='getTimePreview(\"$jahr\",\"$month_number\",0,0,\"$mod\",\"$modus\",\"$base_file\",\"$bewertung\",0,0)'>
							<img src='../../share/images/ok.gif' width='15' height='15' title='Bilder anzeigen' />
							</SPAN>
							</TD>
							<TD class='kat2'>".$num5."</TD></TR>";
						}
					}
				}	
			}
		}
	}
}
//Ermittlung der Bilder, welche noch keinem Datum zugeordnet wurden:

$result7 = mysql_query( "SELECT DateTimeOriginal, pic_id, note, aktiv 
FROM $table2 
WHERE DateTimeOriginal = '0000-00-00 00:00:00'
AND aktiv = '1' 
AND $stat");

$num7 = mysql_num_rows($result7);
echo "<TR class='kat'>
<TD class='kat1'>Sonstige Bilder</TD>
<TD class='kat2'></TD>
<TD class='kat2'></TD>
</TR>
<TR class='kat'>
<TD class='kat1' style='background-color:RGB(125,0,10); color:white;'><SPAN style='cursor:pointer; margin-left:22px' onClick='getTimePreview(0000,0,0,0,\"$mod\",\"$modus\",\"$base_file\",\"$bewertung\",0,0)' title='Bilder anzeigen'>Bilder ohne Datumsangabe</SPAN></TD>
<TD class='kat2'></TD>
<TD class='kat2'>".$num7."</TD>
</TR>
</TABLE>";
$end99 = microtime();
list($end99msec,$end99sec) = explode(" ",$end99);
$runtime99 = number_format((($end99msec + $end99sec) - ($start1msec + $start1sec)),2,'.',',');
echo "<font color='lightgrey'>Skript-Laufzeit: ".$runtime99." Sek.</font>";
//########################################################################################################
?>