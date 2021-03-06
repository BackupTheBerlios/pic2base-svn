<?php
IF (!$_COOKIE['login'])
{
include '../share/global_config.php';
//var_dump($sr);
  header('Location: ../../index.php');
}

/*#####################################################################
wird verwendet, wenn Bilddaten bearbeit werden sollen und die Auswahl
ueber das Aufnahmedatum erfolgen soll
#######################################################################*/

include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/ajax_functions.php';
include_once $sr.'/bin/share/functions/main_functions.php';

$sel_one = "<IMG src='$inst_path/pic2base/bin/share/images/one.gif' width='22' height='11' hspace='0' vspace='0' border='0' title='einzelne Bilder dieses Datums ausw&auml;hlen'>";
$sel_all = "<IMG src='$inst_path/pic2base/bin/share/images/all.gif' width='22' height='11' hspace='0' vspace='0' border='0' title='alle Bilder dieses Datums ausw&auml;hlen'>";

//echo "Ermittlung des Anfangs (min.) und Enddatums (max)";

if(array_key_exists('pic_id',$_GET))
{
	$pic_id = $_GET['pic_id'];
}
ELSE
{
	$pic_id = 0;
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
ELSE
{
	$bewertung = '';
}
if(array_key_exists('show_mod',$_GET))
{
	$show_mod = $_GET['show_mod'];
}
else
{
	if(!isset($show_mod))
	{
		$show_mod = 0;
	}
}
	
$stat = createStatement($bewertung);
//echo $stat;
$base_file = 'edit_kat_daten';
$start1 = microtime();
list($start1msec,$start1sec) = explode(" ",$start1);

$result2 = mysql_query( "SELECT MAX(YEAR(DateTimeOriginal)) AS MAX_DTO, MIN(YEAR(DateTimeOriginal)) AS MIN_DTO 
FROM $table2 
WHERE YEAR(DateTimeOriginal) <> '0000'");
$Min_DT = mysql_result($result2, isset($i2), 'MIN_DTO');
$Max_DT = mysql_result($result2, isset($i2), 'MAX_DTO');
//echo "Fr&uuml;hestes Jahr: ".$Min_DT.", sp&auml;testes Jahr: ".$Max_DT."<BR>";

echo "<p id='elf' style='background-color:white; padding: 5px; margin-top: 4px; margin-left: 0px; text-align:center;'>Bildauswahl nach Aufnahmedatum<BR></p>
<TABLE id='kat'>
	<TR id='kat'>
	<TD id='kat1'>Jahr / Monat / Tag</TD>
	<TD id='kat2'></TD>
	<TD id='kat2'></TD>
	<TD id='kat2'>Anz.</TD>
	</TR>";
$runtime_sum = 0;
//Bestimmung der Jahrgaenge, in denen Bilder entstanden sind:
$result1 = mysql_query( "SELECT DISTINCT YEAR(DateTimeOriginal) AS DTO 
FROM $table2 
WHERE YEAR(DateTimeOriginal) <> '0000' 
ORDER BY YEAR(DateTimeOriginal) DESC");
$num1 = mysql_num_rows($result1);
FOR($i1 = '0'; $i1<$num1; $i1++)
{
	$D = mysql_result($result1, $i1, 'DTO');
	//Die einzelnen "Jahrgaenge" werden ermittelt:
	//Zur Laufzeitoptimierung wird kontrolliert, ob in dem betreffenden Jahr ueberhaupt Bilder vorhanden sind:
	$start2 = microtime();
	$result3 = mysql_query( "SELECT DISTINCT DateTimeOriginal, pic_id, note 
	FROM $table2 
	WHERE DateTimeOriginal LIKE '$D-%' 
	AND DateTimeOriginal <> '0000-00-00 00:00:00' 
	AND $stat");
	echo mysql_error();
	/*$end2 = microtime();
	list($start2msec,$start2sec) = explode(" ",$start2);
	list($end2msec,$end2sec) = explode(" ",$end2);
	$runtime2 = number_format((($end2msec + $end2sec) - ($start2msec + $start2sec)),2,'.',',');
	echo "MySQL-Abfrage-Laufzeit: ".$runtime2." Sek.<BR>";*/
	$num3 = mysql_num_rows($result3);	//Anzahl der Bilder im Jahr
	IF ($num3 > 0)
	{
		$jahr = date('Y', strtotime(mysql_result($result3, isset($i3), 'DateTimeOriginal')));
		$modus='zeit';
		IF(substr($show_mod,0,4) == $jahr)
		{
			$s_m = 'J';		//Anzeige-Modus (show-modus)
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
		<TR id='kat'>
		<TD id='kat1' style='background-color:#ff9900;'>
		<SPAN style='cursor:pointer;' onClick='getTimeTreeview2(\"$pic_id\",\"$mod\",\"$s_m\")'  title='Monatsansicht f&uuml;r $jahr &ouml;ffnen / schlie&szlig;en'>".$tree_img."</SPAN>
		<SPAN style='margin-left:3px;'>".$jahr."</SPAN>
		</TD>
		<TD id='kat2'><SPAN style='cursor:pointer;' onClick='getTimePreview2(\"$jahr\",0,0,0,\"$mod\",\"$modus\",\"$base_file\")'>".$sel_one."</SPAN></TD>
		<TD id='kat2'><SPAN style='cursor:pointer;' onClick='getTimePreview2(\"$jahr\",0,0,1,\"$mod\",\"$modus\",\"$base_file\")'>".$sel_all."</SPAN></TD>
		<TD id='kat2'>".$num3."</TD>
		</TR>";
		IF(substr($show_mod,0,4) == $jahr)
		{
			
			//Die einzelnen Monate im gewaehlten Jahr werden ermittelt:
			FOR($i_M='12'; $i_M>='1'; $i_M--)
			{
				IF($i_M < '10')
				{
					$i_M = '0'.$i_M;
				}
				//wurden in allen Monaten Bilder gemacht?
				$result9 = mysql_query( "SELECT DISTINCT YEAR(DateTimeOriginal), MONTH(DateTimeOriginal) 
				FROM $table2 
				WHERE (YEAR(DateTimeOriginal) = '$jahr' 
				AND MONTH(DateTimeOriginal) = '$i_M' 
				AND DateTimeOriginal <> '0000-00-00 00:00:00')");
				IF(mysql_num_rows($result9) > 0)
				{
					$result4 = mysql_query( "SELECT DISTINCT DateTimeOriginal, pic_id, note 
					FROM $table2 
					WHERE DateTimeOriginal LIKE '$jahr-$i_M%' 
					AND DateTimeOriginal <> '0000-00-00 00:00:00' 
					AND $stat 
					ORDER BY DateTimeOriginal DESC");
					$num4 = mysql_num_rows($result4);
				}
				ELSE
				{
					$num4 = 0;
				}
				//Wenn in dem Monat Bilder gemacht wurden - Ermittlung der Anzahl:
				IF($num4 > '0')
				{					
					$result5 = mysql_query( "SELECT DISTINCT DateTimeOriginal, pic_id, note
					FROM $table2 
					WHERE DateTimeOriginal LIKE '%$jahr-$i_M%' 
					AND DateTimeOriginal <> '0000-00-00 00:00:00' 
					AND $stat 
					ORDER BY DateTimeOriginal DESC");
					$num5 = mysql_num_rows($result5);
					//echo $num5." Bilder im Monat ".$i_M." im Jahr ".$jahr."<BR>";
					
					$month_number = date('m', strtotime(mysql_result($result5, isset($i5), 'DateTimeOriginal')));
					echo "<TR id='kat'>
					<TD id='kat1'>&#160;&#160;";
					$modus = 'zeit';
					$zeit = getMonthName($month_number)." ".$jahr;
					//echo $zeit;
					IF(substr($show_mod,0,4) == $jahr AND substr($show_mod,5,2) !== $month_number)
					{
						//echo "normale Monats-Darstellung: ";
						$s_m = $jahr."_".$month_number;	//$show_mod: Anzeige-Modus fuer Tages-Anzeige
						echo "
						<SPAN style='cursor:pointer;' onClick='getTimeTreeview2(\"$pic_id\",\"$mod\",\"$s_m\")'  title='Tagesauswahl f&uuml;r ".getMonthName($month_number)." &ouml;ffnen'>".$img_plus."</SPAN>
						<SPAN style='margin-left:3px;'>".getMonthName($month_number)."</SPAN>
						</TD>
						<TD id='kat2'><SPAN style='cursor:pointer;' onClick='getTimePreview2(\"$jahr\",\"$month_number\",0,0,\"$mod\",\"$modus\",\"$base_file\")'>".$sel_one."</SPAN></TD>
						<TD id='kat2'><SPAN style='cursor:pointer;' onClick='getTimePreview2(\"$jahr\",\"$month_number\",0,1,\"$mod\",\"$modus\",\"$base_file\")'>".$sel_all."</SPAN></TD>
						<TD id='kat2'>".$num5."</TD>
						</TR>";
					}
					ELSEIF(substr($show_mod,0,4) == $jahr AND substr($show_mod,5,2) == $month_number)
					{
						//echo "Monats- mit Tagesdarstellung: ";
						$y = substr($show_mod,0,4);	//Jahr
						$m = substr($show_mod,5,2);	//Monat
						//echo "Jahr: ".$y.", Monat: ".$m;
						IF($jahr == $y AND $month_number == $m)
						{
							$s_m = $jahr;
							echo "
							<SPAN style='cursor:pointer;' onClick='getTimeTreeview2(\"$pic_id\",\"$mod\",\"$s_m\")' title='Tagesansicht schlie&szlig;en'>".$img_minus."</SPAN>".getMonthName($month_number)."</SPAN>
							</TD>
							<TD id='kat2' colspan='2'></TD>
							</TR>";
							//Ermittlung der Tage, an welchen in diesem Monat/Jahr Aufnahmen gemacht wurden:
							$dat_arr = getRecDays($y,$m,$stat);	//rec_days - Tage, an denen Fotos gemacht wurden (absteigende Reihenfolge)
							FOREACH($dat_arr AS $datum)
							{
								$T = date('d',strtotime($datum));
								$aufn_dat = date('d.m.Y', strtotime($datum));
								$aufn_DAT = date('Y-m-d', strtotime($datum));
								$zeit_t = $T.".".$zeit;
								//Ermittlung der an diesem Tag gemachten Bilder:
								$result12 = mysql_query( "SELECT DISTINCT DateTimeOriginal, note, pic_id 
								FROM $table2 
								WHERE DateTimeOriginal LIKE '%".$aufn_DAT."%' 
								AND DateTimeOriginal <> '0000-00-00 00:00:00' 
								AND $stat 
								ORDER BY DateTimeOriginal");
								
								echo mysql_error();
								$num12 = mysql_num_rows($result12);
								echo "<TR id='kat'>
								<TD id='kat1'> &#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;<SPAN style='margin-left:2px;'>".$aufn_dat."</span></TD>";
								echo "
								<TD id='kat2'><SPAN style='cursor:pointer;' onClick='getTimePreview2(\"$jahr\",\"$month_number\",\"$T\",0,\"$mod\",\"$modus\",\"$base_file\",\"$sr\")'>".$sel_one."</span></TD>
								<TD id='kat2'><SPAN style='cursor:pointer;' onClick='getTimePreview2(\"$jahr\",\"$month_number\",\"$T\",1,\"$mod\",\"$modus\",\"$base_file\",\"$sr\")'>".$sel_all."</span></TD>
								<TD id='kat2'>".$num12."</TD>
								</TR>";
							}
						}
						ELSE
						{
							$s_m = $jahr."_".$month_number;
							echo "<SPAN style='cursor:pointer;' onClick='location.href=\"getTimeTreeview2.php?pic_id=$pic_id&mod=$mod&show_mod=$s_m\"'>".getMonthName($month_number)."</SPAN>
							</TD>
							<TD id='kat2'>
							<SPAN style='cursor:pointer;' onClick='getTimePreview2(\"$jahr\",\"$month_number\",0,0,\"$mod\",\"$modus\",\"$base_file\")'>
							<img src='../../share/images/ok.gif' width='15' height='15' title='Bilder anzeigen' />
							</SPAN>
							</TD>
							<TD id='kat2'>".$num5."</TD></TR>";
						}
					}
				}	
			}
		}
	}
}
//Ermittlung der Bilder, welche noch keinem Datum zugeordnet wurden:

$result7 = mysql_query( "SELECT DateTimeOriginal, note, pic_id 
FROM $table2 
WHERE DateTimeOriginal = '0000-00-00 00:00:00' 
AND $stat");

@$num7 = mysql_num_rows($result7);
IF($num7 == '')
{
	$num7 = 0;
}
echo "<TR id='kat'>
<TD id='kat1'>Sonstige Bilder</TD>
<TD id='kat2'></TD>
<TD id='kat2'></TD>
</TR>
<TR id='kat'>
<TD id='kat1' style=' background-color:#ff9900;'><SPAN style='margin-left:22px'>Bilder ohne Datumsangabe</SPAN></TD>
<TD id='kat2'><SPAN style='cursor:pointer;' onClick='getTimePreview2(0000,0,0,0,\"$mod\",\"$modus\",\"$base_file\")' title='Bilder anzeigen'>".$sel_one."</SPAN></TD>
<TD id='kat2'><SPAN style='cursor:pointer;' onClick='getTimePreview2(0000,0,0,1,\"$mod\",\"$modus\",\"$base_file\")' title='Bilder anzeigen'>".$sel_all."</SPAN></TD>
<TD id='kat2'>".$num7."</TD>
</TR>
</TABLE>";
$end99 = microtime();
list($end99msec,$end99sec) = explode(" ",$end99);
$runtime99 = number_format((($end99msec + $end99sec) - ($start1msec + $start1sec)),2,'.',',');
echo "<font color='lightgrey'>Skript-Laufzeit: ".$runtime99." Sek.</font>";
?>