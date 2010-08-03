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
	<TITLE>pic2base - Metadaten-Extraktion</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
	<!--<meta http-equiv="Refresh" Content="3600; URL=generate_exifdata0.php">-->
	
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: generate_exifdata0.php
 *
 * Copyright (c) 2006 - 2009 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 *Datei leist EXIF-Daten aus den Bild-Dateien aus und schreibt sie in die Tabelle meta_data
 */

unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
$benutzername = $c_username;
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';


echo "
<div class='page'>

<p id='kopf'>pic2base :: EXIF-Daten-Extraktion <span class='klein'>(User: ".$c_username.")</span></p>
	
<div class='navi' style='clear:right;'>
	<div class='menucontainer'>";
	include '../../html/admin/adminnavigation.php';
	echo "
	</div>
</div>
	
<div class='content'>
<p style='margin:120px 0px; text-align:center'>

<center>
Status der EXIF-Daten-Extraktion
	<div id='prog_bar' style='border:solid; border-color:red; width:500px; height:12px; margin-top:30px; text-align:left; vertical-align:middle'>
	<img src='../../share/images/green.gif' name='bar' />
	</div>
<p id='zaehler'>1</p>
</center>";

FOR($note='1'; $note<'6'; $note++)
{
	$result5 = mysql_query( "SELECT * FROM $table2 WHERE note = '$note' ORDER BY pic_id");
	$num5 = mysql_num_rows($result5);
	FOR($i5='0'; $i5<$num5; $i5++)
	{
		$pic_id = mysql_result($result5, $i5, 'pic_id');
		//echo "Datensatz: ".$pic_id;
		$result6 = mysql_query( "SELECT * FROM $table14 WHERE pic_id = '$pic_id'");
		IF(mysql_num_rows($result6) == 0)
		{
			//nur wenn es noch keinen Eintrag in der exif-data-Tabelle gibt, wird die Erfassung ausgef�hrt:
//			echo "Durchlauf ".$i5."; Datensatz ".$pic_id."<BR>~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~<BR>";
			$result7 = mysql_query( "INSERT INTO $table14 (pic_id) VALUES ('$pic_id')");
			//Ermittlung des Original-Dateinamens mit eindeutiger Bezeichnung:
			$FN = $pic_path."/".restoreOriFilename($pic_id, $sr);
			//echo "Datei-Name: ".$FN;
			
			$result8 = mysql_query( "SHOW COLUMNS FROM $table14");
			$struktur = array();
			$i = 0;
			if($result8 != false)
			{
				while($liste = mysql_fetch_row($result8))
				{
					$tab_fieldname[$i] = $liste[0];	//vorh. Tabellen-Feldname
					$tab_fieldtype[$i] = $liste[1];	//vorh. Tabellen-Feldtyp
					$i++;
				}
			}
			else die('Fehler bei der Datenbankabfrage');
			/*
			echo "Datei-Name: ".$FN."<BR><BR> In der EXIF-Daten-Tabelle bereits enthaltene Felder:<BR><BR>";
			$c = count($tab_fieldtype);
			FOR($j0='0'; $j0<$c; $j0++)
			{
				echo $tab_fieldname[$j0].", ".$tab_fieldtype[$j0]."<BR>";
			}
			*/
			//Beginn der Tabellen-Pr�fung, Anpassung und Daten�bernahme:
//			$et_path = '/usr/bin';
			$text = shell_exec($et_path."/exiftool -v2 ".$FN);
			$inf_arr = explode(chr(10), $text);

			FOREACH($inf_arr AS $IA)
			{
				// ##### Bestimmung von Feldname und Wert: #####
				$pos1 = strpos($IA, ')');
				$pos2 = strpos($IA, '=');
				IF($pos1 != '' AND $pos2 != '')
				{
					IF($pos2 != '')
					{
						$fieldname = str_replace('-','_',trim(substr($IA, ($pos1 + 1), ($pos2 - $pos1 - 1))));
						IF($fieldname !== '')
						{	
							//F�r einige Tags (Feldnamen) m�ssen die "Klartext-Werte" ausgelesen werden:
							$value = formatValues($fieldname, $FN, $IA, $pos2);
							//echo "Bild-Inhalt: Feldname: ".$fieldname.", Feld-Wert: ".$value."<BR>";
						}
					}
					$X = -1;
				}
				
				// ##### Bestimmung von Feldtyp und L�nge: #####
				$pos3 = strpos($IA, '- Tag ');
				IF($pos3 != '' AND $X == -1)
				{
					$pos4 = strpos($IA, '(');
					$pos5 = strpos($IA, ')');
					IF($pos4 != '' AND $pos5 != '')
					{
						$prop_arr = explode(', ', substr($IA, ($pos4 + 1), ($pos5 - $pos4 - 1)));
						$fieldlength = trim($prop_arr[0]);
						$fieldtype = trim($prop_arr[1]);
						//echo "Feldl&auml;nge: ".$fieldlength.", Feldtyp: ".$fieldtype."<BR>";
						$fieldlength = str_replace(' bytes', '', $fieldlength);
						//echo "Datensatz: ".$fieldname." | ".$value." | ".$fieldlength." | ".$fieldtype."<BR>";
						IF (!in_array($fieldname, $tab_fieldname))
						{
							//Wenn das Feld noch nicht in der Tabelle ist:
							SWITCH(substr($fieldtype, '0', '3'))
							{
								CASE 'int':
								$fieldtype = 'INT';
								break;
								
								CASE 'rat':
								CASE 'und':
								CASE 'str':
								CASE 'typ':
								$fieldtype = 'VARCHAR('.$fieldlength.')';
								break;
							}
							$result2 = mysql_query( "ALTER TABLE `$table14` ADD `$fieldname` $fieldtype NOT NULL");
							//echo "Fehler beim Erg�nzen des Feldes: ".mysql_error(),"<BR>";
						}
						ELSE
						{
							//Wenn das Feld in der Tabelle ist, wird Feldtyp und Feldl�nge gepr�ft:
							//Pr�fung Feldtyp:
							$pos6 = array_search($fieldname, $tab_fieldname);
							$ist_feldtyp = substr($tab_fieldtype[$pos6],'0','3');
							SWITCH(substr($fieldtype, '0', '3'))
							{
								CASE 'int':
								$soll_feldtyp = 'int';
								break;
								
								CASE 'rat':
								CASE 'und':
								CASE 'str':
								$soll_feldtyp = 'var';
								break;
							}
							//Pr�fung Feldl�nge:
							$pos7 = strpos($tab_fieldtype[$pos6],'(');
							$pos8 = strpos($tab_fieldtype[$pos6],')');
							$ist_feldlaenge = substr($tab_fieldtype[$pos6],($pos7 + 1),($pos8 - $pos7 - 1));
							$soll_feldlaenge = $fieldlength + 5;
							//echo "Ist-Laenge: ".$ist_feldlaenge."<BR>";
							
							IF(($ist_feldlaenge < $soll_feldlaenge) OR (($ist_feldtyp !== $soll_feldtyp) AND ($soll_feldtyp !== 'int')))
							{
								SWITCH(substr($soll_feldtyp, '0', '3'))
								{
									CASE 'int':
									IF($ist_feldlaenge < $soll_feldlaenge)
									{
										$fieldtype = 'VARCHAR('.$soll_feldlaenge.')';
									}
									ELSE
									{
										$fieldtype = 'INT';
									}
									break;
									
									CASE 'rat':
									CASE 'und':
									CASE 'str':
									CASE 'var':
									$fieldtype = 'VARCHAR('.$soll_feldlaenge.')';
									break;
								}
								//echo "Feldtyp muss f�r ".$fieldname." von ".$ist_feldtyp." auf ".$fieldtype." ge�ndert werden oder<BR>";
								//echo "Feldl&auml;nge muss von ".$ist_feldlaenge." auf ".$soll_feldlaenge." angepasst werden.<BR>";
								$result3 = mysql_query( "ALTER TABLE `$table14` CHANGE `$fieldname` `$fieldname` $fieldtype");
								//echo "Fehler beim �ndern der Feldl�nge: ".mysql_error(),"<BR><BR>";
							}
						}
					}
					$X = 1;
					//Speicherung der Daten in der EXIF-Tabelle:
					$result4 = mysql_query( "UPDATE $table14 SET $fieldname = '$value' WHERE pic_id = '$pic_id'");
					IF(mysql_error() !== '')
					{
						//echo "Fehler beim speichern: ".mysql_error()."<BR>~~~~~~~~~~~~~~~~~~~~~~<BR>";
					}
				}
			}
	
		}
			
		$laenge = (round((($i5 + 1) / $num5) * 500));
		$anteil = number_format(($laenge / 5),2,',','.');
		flush();
		//sleep(0.1);
		$text = "Bearbeite Bilder mit der Note ".$note."<BR>Bild ".$pic_id."<BR>Datensatz ".($i5 + 1)." von ".$num5."<BR>".$anteil."%<BR>".isset($status);
		?>
		<SCRIPT language="JavaScript">
		document.bar.src='../../share/images/green.gif';
		document.bar.width=<?echo $laenge;?>;
		document.bar.height='11';
		document.getElementById('zaehler').innerHTML='<?echo $text;?>';
		</SCRIPT>
		<?
		IF(($i5 + 1) == $num5)
		{
			echo "<meta http-equiv='Refresh', Content='2; URL=../../html/admin/adminframe.php'>";
		}
	}
}
?>
</p>
</div>
<br style="clear:both;" />
	<p id="fuss">
	<A style='margin-right:745px;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
	<?php echo $cr;?>
	</p>
</div>
</DIV>
</CENTER>
</BODY>
</HTML>