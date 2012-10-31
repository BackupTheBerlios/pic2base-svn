<?php

/*
Datei enthaelt folgende Funktionen:

function OptionFields()										verwendet
function getMonthName($month_number)						verwendet
function createPreview($pic_id)								verwendet			Z. 423 - DEAKTIVIERT 05.10.2009
function rotatePreviewPictures($Orientation, $FileNameV)	verwendet (Stapel-Erfassung)	Z. 472
function rotateOriginalPictures($Orientation, $FileName)	verwendet (Stapel-Erfassung)	Z. 500
function createQuickPreview($Orientation, $FileNameOri)		verwendet (Bearbeitung - Quick-Preview)
function getPictureDetails($pic_id)							verwendet in edit_beschreibung	Z. 558 - DEAKTIVIERT 05.10.2009
function createPreviewPicture($File, $dest_path, $max_len, $sr)	verwendet
function resizeOriginalPicture($File, $dest_path, $max_len, $sr)	verwendet 			Z. 698
function createFullScreenView($pic_id, $quality)			(nicht) verwendet
function getDeltaLong($lat, $radius1);						verwendet
function getNumberOfPictures($kat_id)						verwendet in kat_treeview.php	Z. 721
function getAllChildIds($kat_id)							verwendet
function fileExists($FileName, $c_username)					verwendet
function createNavi(X)										verwendet in diversen		Z. 810
function createContentFile()								verwendet in get_preveiw.php
function showStars											verwendet in get_preview  	Z. 1345
function showBewertung										verwendet in Kopfzeile der Such-Formulare
function createStatement									verwendet in recherche2
function savePicture										verwendet in erfassung_action	Z. 1481
function getRecDays											verwendet in recherche2 (Zeit)	Z. 1737
function checkSoftware										verwendet in admin/admin/check_software0.php Z 1825
function generateHistogram()								verwendet in details, stapel1	Z. 1880
function formatValues()										verwendet in generate_exifdata0, 
function restoreOriFilename									verwendet in details, generate_exifdata0, 
function extractExifData									verwendet 			Z 2060
function directDownload										verwendet in get_previev	Z 1933
function showCheckboxes										verwendet in get_preview	

*/

function OptionFields($language)
{
	include '../../share/db_connect1.php';
	//include 'meta_tag_translation.php';
	//Welche Meta-Daten-Felder beinhaltet die Tabelle pictures?
	$result2 = mysql_query("SHOW FIELDS FROM $table2");
	$num2 = mysql_num_rows($result2);
	$result3 = mysql_query("SELECT * FROM $table2");
	$num3 = mysql_num_rows($result3);
	$CN = array();						//CN: ColumnName
	echo "<option selected value=''>  ~ Bitte Datenfeld ausw&auml;hlen ~</option>";
	FOR ($i2=0; $i2<$num2; $i2++)
	{
		$CN[] = mysql_field_name($result3,$i2);
	}
	//nach dem Leerfeld werden die recherchierbare Felder der Pictures-Tabelle angeboten, jedoch ohne die der IF-Bedingung:
	asort($CN);
	FOREACH($CN AS $cn)
	{
		IF(!stristr($cn,'GPS') 
		AND !stristr($cn,'City') 
		AND !stristr($cn,'loc_id_0')
		AND !stristr($cn,'FileNameHQ')
		AND !stristr($cn,'FileNameHist')
		AND !stristr($cn,'FileNameHist_r')
		AND !stristr($cn,'FileNameHist_g')
		AND !stristr($cn,'FileNameHist_b')
		AND !stristr($cn,'FileNameMono')
		AND !stristr($cn,'FileNameV')
		AND !stristr($cn,'has_kat')
		AND !stristr($cn,'md5sum')
		)
		{
			//ggf. Uebersetzung des angezeigten Feld-Namens:
			IF($language !== 'en')
			{
				$result3 = mysql_query("SELECT $cn FROM $table20 WHERE lang = '$language'");
				$tag_name = utf8_encode(mysql_result($result3, isset($i3), $cn));
				IF($tag_name == '')
				{
					$tag_name = $cn;
				}
			}
			
			echo "<option VALUE = '$cn'>".$tag_name."</option>";
		}
	}
}

function getMonthName($month_number)
{
	SWITCH ($month_number)
	{
		CASE '01':
		$month_name = 'Januar';
		break;
		
		CASE '02':
		$month_name = 'Februar';
		break;
		
		CASE '03':
		$month_name = 'M&auml;rz';
		break;
		
		CASE '04':
		$month_name = 'April';
		break;
		
		CASE '05':
		$month_name = 'Mai';
		break;
		
		CASE '06':
		$month_name = 'Juni';
		break;
		
		CASE '07':
		$month_name = 'Juli';
		break;
		
		CASE '08':
		$month_name = 'August';
		break;
		
		CASE '09':
		$month_name = 'September';
		break;
		
		CASE '10':
		$month_name = 'Oktober';
		break;
		
		CASE '11':
		$month_name = 'November';
		break;
		
		CASE '12':
		$month_name = 'Dezember';
		break;
	}
	RETURN $month_name;
}



function rotatePreviewPictures($Orientation, $FileNameV, $sr)
{
	include $sr.'/bin/share/global_config.php';
	
	$conv = buildConvertCommand($sr);
	//@$parameter_v=getimagesize($pic_thumbs_path."/".$FileNameV);
	SWITCH($Orientation)
	{
		case '3':
		//Das Vorschaubild muss 180 gedreht werden:
		$command = $conv." ".$pic_thumbs_path."/".$FileNameV." -rotate 180 ".$pic_thumbs_path."/".$FileNameV."";
 		$output = shell_exec($command);
		break;
		
		case '6':
		//Das Vorschaubild muss 90 im Uhrzeigersinn gedreht werden:
		$command = $conv." ".$pic_thumbs_path."/".$FileNameV." -rotate 90 ".$pic_thumbs_path."/".$FileNameV."";
 		$output = shell_exec($command);
		break;
		
		case '8':
		//Das Vorschaubild muss 90 entgegen dem Uhrzeigersinn gedreht werden:
		$command = $conv." ".$pic_thumbs_path."/".$FileNameV." -rotate 270 ".$pic_thumbs_path."/".$FileNameV."";
 		$output = shell_exec($command);
		break;
	}
	return $FileNameV;
}

function rotateOriginalPictures($Orientation, $FileNameHQ, $sr)
{
	include $sr.'/bin/share/global_config.php';
	
	$conv = buildConvertCommand($sr);
	//@$parameter_o=getimagesize($pic_hq_path."/".$FileNameHQ);
	SWITCH($Orientation)
	{
		case '3':
		//Das Vorschaubild muss 180 gedreht werden:
		$command = $conv." ".$pic_hq_path."/".$FileNameHQ." -rotate 180 ".$pic_hq_path."/".$FileNameHQ."";
		$output = shell_exec($command);
		break;
		
		case '6':
		//Das Vorschaubild muss 90 im Uhrzeigersinn gedreht werden:
		$command = $conv." ".$pic_hq_path."/".$FileNameHQ." -rotate 90 ".$pic_hq_path."/".$FileNameHQ."";
		$output = shell_exec($command);
		break;
		
		case '8':
		//Das Vorschaubild muss 90 entgegen dem Uhrzeigersinn gedreht werden:
		$command = $conv." ".$pic_hq_path."/".$FileNameHQ." -rotate 270 ".$pic_hq_path."/".$FileNameHQ."";
		$output = shell_exec($command);
		break;
		
	}
	return $FileNameHQ;
}

function createQuickPreview($Orientation,$FileName,$sr)
{
	include $sr.'/bin/share/global_config.php';
	
	$conv = buildConvertCommand($sr);
	
	@$parameter_o=getimagesize($pic_path."/".$FileName);
	SWITCH($Orientation)
	{
		case '3':
		//echo "Das Vorschaubild muss 180 gedreht werden<BR>";
		$command = $conv." ".$pic_path."/".$FileName." -rotate 180 ".$pic_rot_path."/".$FileName."";
 		$output = shell_exec($command);
		break;
		
		case '6':
		//echo "Das Vorschaubild muss 90 CW gedreht werden<BR>";
		$command = $conv." ".$pic_path."/".$FileName." -rotate 90 ".$pic_rot_path."/".$FileName."";
 		$output = shell_exec($command);
		break;
		
		case '8':
		//echo "Erzeuge Quick-Preview-Bild von ".$pic_path."/".$FileName."<BR> nach ".$pic_rot_path."/".$FileName."<BR>";
		//echo "Das Vorschaubild muss 270 CW gedreht werden<BR>";
		$command = $conv." ".$pic_path."/".$FileName." -rotate 270 ".$pic_rot_path."/".$FileName."";
 		$output = shell_exec($command);
		break;
	}
	return $FileName;
}

function createPreviewPicture($FILE, $dest_path, $max_len, $sr)
{
	include $sr.'/bin/share/global_config.php';
	
	$conv = buildConvertCommand($sr);
	
	//Wenn das Originalbild kein EXIF-Vorschaubild mitbringt, generiert diese Funktion aus der Quelle ein Vorschaubild, 
	//dessen max. Ausdehnung max_len Pixel betraegt und speichert dieses unter dem Destination-Pfad ab. 
	//-wird bei der Erfassung von Bildern angewendet.
	//egal was rein kommt, das Vorschaubild wird immer als jpg abgelegt:
	$file_nameV = str_replace('_hq.jpg','_v.jpg',basename($FILE));	//Variante, bei der Vorschau aus HQ erzeugt wird
    $command = $conv." -quality 80 ".$FILE." -resize ".$max_len."x".$max_len." +profile '*' ".$dest_path."/".$file_nameV."";
    $output = shell_exec($command);
	return $file_nameV;
}

function resizeOriginalPicture($FILE, $dest_path, $max_len, $sr)
{
	include $sr.'/bin/share/global_config.php';
	
	$conv = buildConvertCommand($sr);
	
	//Die Funktion generiert aus der Quelle ein HQ-Vorschaubild, dessen max. Ausdehnung max_len Pixel betraegt und speichert dieses unter dem Destination-Pfad ab.
	//egal was rein kommt, das Vorschaubild wird immer als jpg abgelegt:
	$file_nameT = str_replace('.jpg','_hq.jpg',basename($FILE));
    $command = $conv." -quality 80 -size ".$max_len."x".$max_len." ".$FILE." -resize ".$max_len."x".$max_len." +profile '*' ".$dest_path."/".$file_nameT."";
    $output = shell_exec($command);
	return $file_nameT;
}

function getNumberOfPictures($kat_id, $modus, $bewertung, $treestatus)
{
	IF ($_COOKIE['uid'])
	{
		$uid = $_COOKIE['uid'];
	}
	//echo "Modus: ".$modus.", User-ID: ".$uid."<BR>";
	//echo $treestatus;
	include '../../share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	$kat_arr[] = $kat_id;		//Kategorie-Nummern-Container
//	$result0 = mysql_query("SELECT * FROM $table1 WHERE username = '$c_username'");
//	$id = mysql_result($result0, isset($i0), 'id');
//	$id = $uid;
	
	$result1 = mysql_query("SELECT * FROM $table4 WHERE parent = '$kat_id'");
	$num1 = mysql_num_rows($result1);
	IF ($num1 > 0)
	{
		FOR($i1=0; $i1<$num1; $i1++)
		{
			$kat_id = mysql_result($result1, $i1, 'kat_id');
			//$kat_arr[] = $kat_id;
			getAllChildIds($kat_id);
		}
	}
	$anz = count($kat_arr);
	//echo "Elemente im Kat-Array: ".$anz."<BR>";
	$nop = '0';		//number of pictures :-)
	FOREACH($kat_arr AS $kat_nr)
	{
		IF($modus == 'edit')
		{
			//fuer die Oberkategorie (mit Minus) werden nur diejenigen Bilder angezeigt,
			//die keiner weiteren Unterkategorie zugeordnert sind.
			//Fuer Unterkategorien (mit Plus) werden die Bilder in dieser und aller weiteren Unterkategorien angezeigt
			//abhaengig von der Berechtigung wird die Anzahl der Bilder ermittelt:
			IF(hasPermission($uid, 'editallpics', $sr))
			{
				//Sonderfall Neuzugaenge: hier wird nur die Anz. der noch nicht kategorisierten Bilder ermittelt:
				$result3 = mysql_query("SELECT * FROM $table4 WHERE kat_id = '$kat_nr'");
				$parent = mysql_result($result3, isset($i3), 'parent');
				IF($parent == '0')
				{
					//es handelt sich um die oberste Kategorie (Neuzugenge)
					$result2 = mysql_query("SELECT * 
					FROM $table2 
					WHERE has_kat = '0'
					AND aktiv = '1'");
				}
				ELSE
				{
					IF($treestatus == 'plus')
					{
						$result2 = mysql_query("SELECT $table10.pic_id, $table10.kat_id, $table2.Owner, $table2.pic_id, $table2.aktiv 
						FROM $table10 INNER JOIN $table2 
						ON $table10.kat_id = '$kat_nr' 
						AND $table10.pic_id = $table2.pic_id
						AND $table2.aktiv = '1'");
					}
					ELSEIF($treestatus == 'minus')
					{
						$result2 = mysql_query("SELECT $table10.pic_id, $table10.kat_id FROM $table10
						WHERE ($table10.kat_id = '$kat_nr') 
						AND ($table10.pic_id <> ALL (SELECT pic_id 
						FROM $table10 LEFT JOIN $table4 ON ($table10.kat_id = $table4.kat_id) 
						WHERE parent = '$kat_nr'))");
						
						echo mysql_error();
						$num2 = mysql_num_rows($result2);
					}
				}
			}
			ELSEIF(hasPermission($uid, 'editmypics', $sr))
			{
				//Sonderfall Neuzugaenge: hier wird nur die Anz. der noch nicht kategorisierten Bilder ermittelt:
				$result3 = mysql_query("SELECT * FROM $table4 WHERE kat_id = '$kat_nr'");
				$parent = mysql_result($result3, isset($i3), 'parent');
				IF($parent == '0')
				{
					//es handelt sich um die oberste Kategorie (Neuzugenge)
					$result2 = mysql_query("SELECT * 
					FROM $table2 
					WHERE has_kat = '0'
					AND Owner = '$uid'
					AND aktiv = '1'");
				}
				ELSE
				{
					IF($treestatus == 'plus')
					{
						$result2 = mysql_query("SELECT $table10.pic_id, $table10.kat_id, $table2.Owner, $table2.pic_id, $table2.aktiv 
						FROM $table10 INNER JOIN $table2 
						ON $table10.kat_id = '$kat_nr' 
						AND $table10.pic_id = $table2.pic_id 
						AND $table2.Owner = '$uid'
						AND $table2.aktiv = '1'");
					}
					ELSEIF($treestatus == 'minus')
					{
						$result2 = mysql_query("SELECT $table10.pic_id, $table10.kat_id, $table2.Owner, $table2.pic_id, $table2.aktiv 
						FROM $table10 INNER JOIN $table2
						ON ($table10.kat_id = '$kat_nr')
						AND $table10.pic_id = $table2.pic_id 
						AND $table2.Owner = '$uid'
						AND $table2.aktiv = '1' 
						AND ($table10.pic_id <> ALL (SELECT pic_id 
						FROM $table10 LEFT JOIN $table4 ON ($table10.kat_id = $table4.kat_id) WHERE parent = '$kat_nr'))");
					}
				}
			}
			echo mysql_error();
			$nop = mysql_num_rows($result2);
		}
		ELSEIF($modus == 'recherche')
		{
			$stat = createStatement($bewertung);
			IF($bewertung !== '6')
			{
				//Sonderfall Neuzugaenge: hier wird nur die Anz. der noch nicht kategorisierten Bilder ermittelt:
				$result3 = mysql_query("SELECT * FROM $table4 WHERE kat_id = '$kat_nr'");
				$parent = mysql_result($result3, isset($i3), 'parent');
				IF($parent == '0')
				{
					//es handelt sich um die oberste Kategorie (Neuzugenge)
					$result2 = mysql_query("SELECT *
					FROM $table2
					WHERE has_kat = '0'
					AND aktiv = '1' 
					AND $stat"); echo mysql_error();
				}
				ELSE
				{
					$result2 = mysql_query("SELECT $table10.pic_id, $table10.kat_id, $table2.Owner, $table2.pic_id, $table2.note, $table2.aktiv 
					FROM $table10, $table2 
					WHERE ($table10.kat_id = '$kat_nr' 
					AND $table10.pic_id = $table2.pic_id
					AND $table2.aktiv = '1'
					AND $table2.$stat)"); echo mysql_error();
				}
			}
			ELSE
			{
				//Sonderfall Neuzugaenge: hier wird nur die Anz. der noch nicht kategorisierten Bilder ermittelt:
				$result3 = mysql_query("SELECT * FROM $table4 WHERE kat_id = '$kat_nr'");
				$parent = mysql_result($result3, isset($i3), 'parent');
				IF($parent == '0')
				{
					//es handelt sich um die oberste Kategorie (Neuzugenge)
					$result2 = mysql_query("SELECT * 
					FROM $table2 
					WHERE has_kat = '0'
					AND aktiv = '1'"); echo mysql_error();
				}
				ELSE
				{
					//$result2 = mysql_query("SELECT * FROM $table10 WHERE kat_id = '$kat_nr'");
					$result2 = mysql_query("SELECT $table10.pic_id, $table10.kat_id, $table2.Owner, $table2.pic_id, $table2.aktiv 
					FROM $table10, $table2 
					WHERE ($table10.kat_id = '$kat_nr' 
					AND $table10.pic_id = $table2.pic_id
					AND $table2.aktiv = '1')"); echo mysql_error();
				}
			}
			echo mysql_error();
			$nop = mysql_num_rows($result2);
		}
	}
	return $nop;	
}

function getAllChildIds($kat_id)
{
	global $table4;
//echo $table4." ### ".$kat_id;
//echo $kat_id." <-kat_id<br>";
	$result1 = mysql_query("SELECT * FROM $table4 WHERE parent = '$kat_id'");
//echo mysql_error();
	$num1 = mysql_num_rows($result1);
	IF ($num1 > 0)
	{
		FOR($i1=0; $i1<$num1; $i1++)
		{
			$kat_id = mysql_result($result1, $i1, 'kat_id');
			$kat_arr[] = $kat_id;
		}
	}
}

function fileExists($FileName, $uid)
{
	//Ermittlung, ob sich die Bilddatei sich im Download-Ordner befindet:
	include 'global_config.php';
	//echo $uid;
	$n = 0;
	$verz=opendir($ftp_path."/".$uid."/downloads/");
	while($datei=readdir($verz))
	{
		if($datei == $FileName)
		{
			$n++;
		}
	}
	return $n;
}

function createNavi0($uid)
{
	//Navigationsstruktur der Startseite
	include '../share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	if(!isset($navigation))
	{
		$navigation = '';
	}
	IF(hasPermission($uid, 'adminlogin', $sr) OR hasPermission($uid, 'editkattree', $sr) OR hasPermission($uid, 'editlocationname', $sr))
	{
		$navigation = "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php' title='zum Administrationsbereich'>Administration</a>";
		
		//Pruefung, ob Rechte auf log, images und userdata korrekt gesetzt sind (700 oder 770 fuer Entwicklung):
		$mod = decoct ( fileperms ( $p2b_path."pic2base/log/" ) );
		//echo $mod."<BR>";
		//echo substr($mod,-3)."<BR>";
		IF(!isset($text))
		{
			$text = '';
		}
		
		IF(substr($mod,-3) !== '700' AND substr($mod,-3) !== '770')
		{
			$text .= "FEHLER! Recht auf /log falsch gesetzt! Soll: 700; Ist: ".substr($mod,-3);
		}
		clearstatcache();
		$mod = decoct ( fileperms ( $p2b_path."pic2base/userdata/" ) );
		IF(substr($mod,-3) !== '700' AND substr($mod,-3) !== '770')
		{
			$text .= "FEHLER! Recht auf /userdata falsch gesetzt!<BR>Soll: 700; Ist: ".substr($mod,-3);
		}
		clearstatcache();
		$mod = decoct ( fileperms ( $p2b_path."pic2base/images/" ) );
		IF(substr($mod,-3) !== '700' AND substr($mod,-3) !== '770')
		{
			$text .= "FEHLER! Recht auf /images falsch gesetzt!<BR>Soll: 700; Ist: ".substr($mod,-3);
		}
		clearstatcache();
		$mod = decoct ( fileperms ( $p2b_path."pic2base/images/originale/" ) );
		IF(substr($mod,-3) !== '700' AND substr($mod,-3) !== '770')
		{
			$text .= "FEHLER! Recht auf /originale falsch gesetzt!<BR>Soll: 700; Ist: ".substr($mod,-3);
		}
		clearstatcache();
		$mod = decoct ( fileperms ( $p2b_path."pic2base/images/originale/rotated" ) );
		IF(substr($mod,-3) !== '700' AND substr($mod,-3) !== '770')
		{
			$text .= "FEHLER! Recht auf /rotated falsch gesetzt!<BR>Soll: 700; Ist: ".substr($mod,-3);
		}
		clearstatcache();
		$mod = decoct ( fileperms ( $p2b_path."pic2base/images/vorschau/" ) );
		IF(substr($mod,-3) !== '700' AND substr($mod,-3) !== '770')
		{
			$text .= "FEHLER! Recht auf /vorschau falsch gesetzt!<BR>Soll: 700; Ist: ".substr($mod,-3);
		}
		clearstatcache();
		$mod = decoct ( fileperms ( $p2b_path."pic2base/images/vorschau/hq-preview/" ) );
		IF(substr($mod,-3) !== '700' AND substr($mod,-3) !== '770')
		{
			$text .= "FEHLER! Recht auf /hq-preview falsch gesetzt!<BR>Soll: 700; Ist: ".substr($mod,-3);
		}
		clearstatcache();
		$mod = decoct ( fileperms ( $p2b_path."pic2base/images/vorschau/thumbs" ) );
		IF(substr($mod,-3) !== '700' AND substr($mod,-3) !== '770')
		{
			$text .= "FEHLER! Recht auf /thumbs falsch gesetzt!<BR>Soll: 700; Ist: ".substr($mod,-3);
		}
		clearstatcache();
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Administration</a>";
		$text = '';
	}
	
	IF(hasPermission($uid, 'editmyprofile', $sr)
	OR hasPermission($uid, 'editallprofiles', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/extras/einstellungen1.php' title='pers&ouml;nliche Einstellungen anpassen'>Einstellungen</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Einstellungen</a>";
	}
	
	IF(hasPermission($uid, 'addpic', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/erfassung/erfassung0.php' title='Bilder erfassen'>Erfassung</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Erfassung</a>";
	}
	
	IF(hasPermission($uid, 'editmypics', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/edit/edit_start.php' title='Bilddateien bearbeiten'>Bearbeitung</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Bearbeitung</a>";
	}
	
	IF(hasPermission($uid, 'searchpic', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/recherche/recherche0.php' title='nach Bilddateien suchen'>Suche</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Suche</a>";
	}
	
	$navigation .= "<a class='navi_blind'></a>";
	IF($text !== '')
	{
		$navigation .= "<a class='navi' href='#' title='$text'><blink>>> Hinweis <<</blink></a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_blind'></a>";
	}
	$navigation .= "	<a class='navi_blind' href='$inst_path/pic2base/bin/html/start.php'>Startseite</a>
			<a class='navi' href='$inst_path/pic2base/bin/html/help/help1.php?page=0' title='Online-Hilfe aufrufen'>Hilfe</a>
			<a class='navi' href='$inst_path/pic2base/index.php' title='vom Server abmelden'>Logout</a>";
	
	echo $navigation;
}

function createNavi1($uid)
{
	//Navigationsstruktur der Erfassungs-Startseite
	include '../../share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	include $sr.'/bin/share/functions/permissions.php';
	if(!isset($navigation))
	{
		$navigation = '';
	}
	IF(hasPermission($uid, 'adminlogin', $sr) OR hasPermission($uid, 'editkattree', $sr) OR hasPermission($uid, 'editlocationname', $sr))
	{
		$navigation = "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php' title='zum Administrationsbereich'>Administration</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Administration</a>";
	}
	IF(hasPermission($uid, 'editmyprofile', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/extras/einstellungen1.php' title='pers&ouml;nliche Einstellungen anpassen'>Einstellungen</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Einstellungen</a>";
	}
	IF(hasPermission($uid, 'addpic', $sr))
	{
		$navigation .= "<a class='navi_blind' href='$inst_path/pic2base/bin/html/erfassung/erfassung0.php' title='Bilder erfassen'>Erfassung</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Erfassung</a>";
	}
	IF(hasPermission($uid, 'editmypics', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/edit/edit_start.php' title='Bilddateien bearbeiten'>Bearbeitung</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Bearbeitung</a>";
	}
	IF(hasPermission($uid, 'searchpic', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/recherche/recherche0.php' title='nach Bilddateien suchen'>Suche</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Suche</a>";
	}
	
	$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/start.php' title='zur Startseite'>Zur&uuml;ck</a>
			<a class='navi_blind'></a>
			<a class='navi' href='$inst_path/pic2base/bin/html/start.php'>zur Startseite</a>
			<a class='navi' href='$inst_path/pic2base/bin/html/help/help1.php?page=1' title='Online-Hilfe aufrufen'>Hilfe</a>
			<a class='navi' href='$inst_path/pic2base/index.php' title='vom Server abmelden'>Logout</a>";
	echo $navigation;
}

function createNavi2($uid)
{
	//Navigationsstruktur der Recherche-Startseite (recherche0.php)
	include '../../share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
if(!isset($navigation))
	{
		$navigation = '';
	}
	IF(hasPermission($uid, 'adminlogin', $sr) OR hasPermission($uid, 'editkattree', $sr) OR hasPermission($uid, 'editlocationname', $sr))
	{
		$navigation = "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php' title='zum Administrationsbereich'>Administration</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Administration</a>";
	}
	IF(hasPermission($uid, 'editmyprofile', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/extras/einstellungen1.php' title='pers&ouml;nliche Einstellungen anpassen'>Einstellungen</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Einstellungen</a>";
	}
	IF(hasPermission($uid, 'addpic', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/erfassung/erfassung0.php' title='Bilder erfassen'>Erfassung</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Erfassung</a>";
	}
	IF(hasPermission($uid, 'editmypics', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/edit/edit_start.php' title='Bilddateien bearbeiten'>Bearbeitung</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Bearbeitung</a>";
	}
	IF(hasPermission($uid, 'searchpic', $sr))
	{
		$navigation .= "<a class='navi_blind' href='$inst_path/pic2base/bin/html/recherche/recherche0.php' title='nach Bilddateien suchen'>Suche</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Suche</a>";
	}
	
	$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/start.php' title='zur Startseite'>Zur&uuml;ck</a>
			<a class='navi_blind'></a>
			<a class='navi' href='$inst_path/pic2base/bin/html/start.php'>zur Startseite</a>
			<a class='navi' href='$inst_path/pic2base/bin/html/help/help1.php?page=2' title='Online-Hilfe aufrufen'>Hilfe</a>
			<a class='navi' href='$inst_path/pic2base/index.php' title='vom Server abmelden'>Logout</a>";
	echo $navigation;
}

function createNavi2_1($uid)
{
	//Navigationsstruktur der Recherche-Unter-Seite
	include '../../share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	include $sr.'/bin/share/functions/permissions.php';
	if(!isset($navigation))
	{
		$navigation = '';
	}
	IF(hasPermission($uid, 'adminlogin', $sr) OR hasPermission($uid, 'editkattree', $sr) OR hasPermission($uid, 'editlocationname', $sr))
	{
		$navigation = "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php' title='zum Administrationsbereich'>Administration</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Administration</a>";
	}
	
	IF(hasPermission($uid, 'editmyprofile', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/extras/einstellungen1.php' title='pers&ouml;nliche Einstellungen anpassen'>Einstellungen</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Einstellungen</a>";
	}
	
	IF(hasPermission($uid, 'addpic', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/erfassung/erfassung0.php' title='Bilder erfassen'>Erfassung</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Erfassung</a>";
	}
	
	IF(hasPermission($uid, 'editmypics', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/edit/edit_start.php' title='Bilddateien bearbeiten'>Bearbeitung</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Bearbeitung</a>";
	}
	
	IF(hasPermission($uid, 'searchpic', $sr))
	{
		$navigation .= "<a class='navi_blind' href='$inst_path/pic2base/bin/html/recherche/recherche0.php' title='nach Bilddateien suchen'>Suche</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Suche</a>";
	}
	
	$navigation .= "<a class='navi' href='recherche0.php'>Zur&uuml;ck</a>
			<a class='navi_blind'></a>
			<a class='navi' href='$inst_path/pic2base/bin/html/start.php'>zur Startseite</a>
			<a class='navi' href='$inst_path/pic2base/bin/html/help/help1.php?page=2' title='Online-Hilfe aufrufen'>Hilfe</a>
			<a class='navi' href='$inst_path/pic2base/index.php' title='vom Server abmelden'>Logout</a>";
	echo $navigation;
}

function createNavi3($uid)
{
	//Navigationsstruktur der Edit-Startseite
	include '../../share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	include $sr.'/bin/share/functions/permissions.php';
if(!isset($navigation))
	{
		$navigation = '';
	}
	IF(hasPermission($uid, 'adminlogin', $sr) OR hasPermission($uid, 'editkattree', $sr) OR hasPermission($uid, 'editlocationname', $sr))
	{
		$navigation = "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php' title='zum Administrationsbereich'>Administration</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Administration</a>";
	}
	IF(hasPermission($uid, 'editmyprofile', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/extras/einstellungen1.php' title='pers&ouml;nliche Einstellungen anpassen'>Einstellungen</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Einstellungen</a>";
	}
	IF(hasPermission($uid, 'addpic', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/erfassung/erfassung0.php' title='Bilder erfassen'>Erfassung</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Erfassung</a>";
	}
	IF(hasPermission($uid, 'editmypics', $sr))
	{
		$navigation .= "<a class='navi_blind' href='$inst_path/pic2base/bin/html/edit/edit_start.php' title='Bilddateien bearbeiten'>Bearbeitung</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Bearbeitung</a>";
	}
	IF(hasPermission($uid, 'searchpic', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/recherche/recherche0.php' title='nach Bilddateien suchen'>Suche</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Suche</a>";
	}
	
	$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/start.php' title='zur Startseite'>Zur&uuml;ck</a>
			<a class='navi_blind'></a>
			<a class='navi' href='$inst_path/pic2base/bin/html/start.php'>zur Startseite</a>
			<a class='navi' href='$inst_path/pic2base/bin/html/help/help1.php?page=3' title='Online-Hilfe aufrufen'>Hilfe</a>
			<a class='navi' href='$inst_path/pic2base/index.php' title='vom Server abmelden'>Logout</a>";
	echo $navigation;
}

function createNavi3_1($uid)
{
	//Navigationsstruktur der Edit-Unter-Seite
	include '../../share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	include $sr.'/bin/share/functions/permissions.php';
	if(!isset($navigation))
	{
		$navigation = '';
	}
	IF(hasPermission($uid, 'adminlogin', $sr) OR hasPermission($uid, 'editkattree', $sr) OR hasPermission($uid, 'editlocationname', $sr))
	{
		$navigation = "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php' title='zum Administrationsbereich'>Administration</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Administration</a>";
	}
	
	IF(hasPermission($uid, 'editmyprofile', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/extras/einstellungen1.php' title='pers&ouml;nliche Einstellungen anpassen'>Einstellungen</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Einstellungen</a>";
	}
	
	IF(hasPermission($uid, 'addpic', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/erfassung/erfassung0.php' title='Bilder erfassen'>Erfassung</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Erfassung</a>";
	}
	
	IF(hasPermission($uid, 'editmypics', $sr))
	{
		$navigation .= "<a class='navi_blind' href='$inst_path/pic2base/bin/html/edit/edit_start.php' title='Bilddateien bearbeiten'>Bearbeitung</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Bearbeitung</a>";
	}
	
	IF(hasPermission($uid, 'searchpic', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/recherche/recherche0.php' title='nach Bilddateien suchen'>Suche</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Suche</a>";
	}
	
	$navigation .= "<a class='navi' href='edit_start.php'>Zur&uuml;ck</a>
			<a class='navi_blind'></a>
			<a class='navi' href='$inst_path/pic2base/bin/html/start.php'>zur Startseite</a>
			<a class='navi' href='$inst_path/pic2base/bin/html/help/help1.php?page=4' title='Online-Hilfe aufrufen'>Hilfe</a>
 			<a class='navi' href='$inst_path/pic2base/index.php' title='vom Server abmelden'>Logout</a>";
	echo $navigation;
}

function createNavi4_1($uid)
{
	//Navigationsstruktur der Hilfe-Seite
	include '../../share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	include $sr.'/bin/share/functions/permissions.php';
if(!isset($navigation))
	{
		$navigation = '';
	}
	IF(hasPermission($uid, 'adminlogin', $sr) OR hasPermission($uid, 'editkattree', $sr) OR hasPermission($uid, 'editlocationname', $sr))
	{
		$navigation = "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php' title='zum Administrationsbereich'>Administration</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Administration</a>";
	}
	
	IF(hasPermission($uid, 'editmyprofile', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/extras/einstellungen1.php' title='pers&ouml;nliche Einstellungen anpassen'>Einstellungen</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Einstellungen</a>";
	}
	
	IF(hasPermission($uid, 'addpic', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/erfassung/erfassung0.php' title='Bilder erfassen'>Erfassung</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Erfassung</a>";
	}
	
	IF(hasPermission($uid, 'editmypics', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/edit/edit_start.php' title='Bilddateien bearbeiten'>Bearbeitung</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Bearbeitung</a>";
	}
	
	IF(hasPermission($uid, 'searchpic', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/recherche/recherche0.php' title='nach Bilddateien suchen'>Suche</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Suche</a>";
	}
	
	$navigation .= "<a class='navi' href='javascript:history.back()'>Zur&uuml;ck</a>
			<a class='navi_blind'></a>
			<a class='navi' href='$inst_path/pic2base/bin/html/start.php'>zur Startseite</a>
			<a class='navi_blind'>Hilfe</a>
			<a class='navi' href='$inst_path/pic2base/index.php' title='vom Server abmelden'>Logout</a>";
	echo $navigation;
}

function createNavi5($uid)
{
	//Navigationsstruktur der Einstellungs-Seite
	include '../../share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	include_once $sr.'/bin/share/functions/permissions.php';
	if(!isset($navigation))
	{
		$navigation = '';
	}
	IF(hasPermission($uid, 'adminlogin', $sr) OR hasPermission($uid, 'editkattree', $sr) OR hasPermission($uid, 'editlocationname', $sr))
	{
		$navigation = "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php' title='zum Administrationsbereich'>Administration</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy'>Administration</a>";
	}
	
	IF(hasPermission($uid, 'editmyprofile', $sr) OR hasPermission($uid, 'editallprofiles', $sr))
	{
		$navigation .= "<a class='navi_blind' href='$inst_path/pic2base/bin/html/extras/einstellungen1.php' title='pers&ouml;nliche Einstellungen anpassen'>Einstellungen</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Einstellungen</a>";
	}
	
	IF(hasPermission($uid, 'addpic', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/erfassung/erfassung0.php' title='Bilder erfassen'>Erfassung</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Erfassung</a>";
	}
	
	IF(hasPermission($uid, 'editmypics', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/edit/edit_start.php' title='Bilddateien bearbeiten'>Bearbeitung</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Bearbeitung</a>";
	}
	
	IF(hasPermission($uid, 'searchpic', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/recherche/recherche0.php' title='nach Bilddateien suchen'>Suche</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Suche</a>";
	}
	
	$navigation .= "<a class='navi' href='javascript:history.back()'>Zur&uuml;ck</a>
			<a class='navi_blind'></a>
			<a class='navi' href='$inst_path/pic2base/bin/html/start.php'>zur Startseite</a>
			<a class='navi' href='$inst_path/pic2base/bin/html/help/help1.php?page=6' title='Online-Hilfe aufrufen'>Hilfe</a>
			<a class='navi' href='$inst_path/pic2base/index.php' title='vom Server abmelden'>Logout</a>";
	echo $navigation;
}

function createNavi5_1($uid)
{
	//Navigationsstruktur der Kategorie-Sortierungsseite-Seite
	include '../../share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	include_once $sr.'/bin/share/functions/permissions.php';
	if(!isset($navigation))
	{
		$navigation = '';
	}
	IF(hasPermission($uid, 'adminlogin', $sr) OR hasPermission($uid, 'editkattree', $sr))
	{
		$navigation = "<a class='navi_blind' href='#'>Sortierung</a>
		<a class='navi' href='db_wartung1.php'>DB-Wartung</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy'>Administration</a>";
	}
	
	
	$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php'>Zur&uuml;ck</a>
			<a class='navi_blind'></a>
			<a class='navi_blind'></a>
			<a class='navi_blind'></a>
			<a class='navi_blind'></a>
			<a class='navi' href='$inst_path/pic2base/bin/html/start.php'>zur Startseite</a>
			<a class='navi' href='$inst_path/pic2base/bin/html/help/help1.php?page=5' title='Online-Hilfe aufrufen'>Hilfe</a>
			<a class='navi' href='$inst_path/pic2base/index.php' title='vom Server abmelden'>Logout</a>";
	echo $navigation;
}

function createNavi6($uid,$sr)
{
	//Navigationsstruktur der Log-Datei-Seite
	include $sr.'/bin/share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	include $sr.'/bin/share/functions/permissions.php';
	
	if(!isset($navigation))
	{
		$navigation = '';
	}
	
	IF(hasPermission($uid, 'adminlogin', $sr) OR hasPermission($uid, 'editkattree', $sr) OR hasPermission($uid, 'editlocationname', $sr))
	{
		$navigation = "<a class='navi_blind' href='$inst_path/pic2base/bin/html/admin/adminframe.php' title='zum Administrationsbereich'>Administration</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Administration</a>";
	}
	
	IF(hasPermission($uid, 'editmyprofile', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/extras/einstellungen1.php' title='pers&ouml;nliche Einstellungen anpassen'>Einstellungen</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Einstellungen</a>";
	}
	
	IF(hasPermission($uid, 'addpic', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/erfassung/erfassung0.php' title='Bilder erfassen'>Erfassung</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Erfassung</a>";
	}
	
	IF(hasPermission($uid, 'editmypics', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/edit/edit_start.php' title='Bilddateien bearbeiten'>Bearbeitung</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Bearbeitung</a>";
	}
	
	IF(hasPermission($uid, 'searchpic', $sr))
	{
		$navigation .= "<a class='navi' href='$inst_path/pic2base/bin/html/recherche/recherche0.php' title='nach Bilddateien suchen'>Suche</a>";
	}
	ELSE
	{
		$navigation .= "<a class='navi_dummy' href='#' title='nicht verf&uuml;gbar'>Suche</a>";
	}
	
	$navigation .= "<a class='navi' href='javascript:history.back()'>Zur&uuml;ck</a>
			<a class='navi_blind'></a>
			<a class='navi' href='$inst_path/pic2base/bin/html/start.php'>zur Startseite</a>
			<a class='navi' href='$inst_path/pic2base/bin/html/help/help1.php?page=6' title='Online-Hilfe aufrufen'>Hilfe</a>
			<a class='navi' href='$inst_path/pic2base/index.php' title='vom Server abmelden'>Logout</a>";
	echo $navigation;
}

function createContentFile($mod, $statement, $uid, $bild)
{
	//Erzeugung der pdf-Galerie
	//echo "Statement in Fkt: ".$statement;
	//$statement fuer exif, desc, zeit, kat(ausser Wurzel) uebergeben
	include '../share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	SWITCH($mod)
	{
		CASE 'geo':
		$statement = explode(' ', $statement);	//
		$num100 = count($statement);
		break;
		
		default:
		$result100 = mysql_query("$statement");
		@$num100 = mysql_num_rows($result100);
		break;
	}

	IF($statement !=='')
	{
		//echo $statement;
		//echo "gefundene Bilder: ".$num100."<BR>";
		
		//memory_limit dynamisch anpassen, falls der berechnete Wert groesser als der vorhandene Wert ist.
		$steps = ceil($num100 / 100);
		//echo "<font color='white'>".$steps." - </font>";
		$memory_avail = ini_get('memory_limit');
		$memory_value = $steps * 50;
		if($memory_value > $memory_avail)
		{
			$memory = $memory_value."M";
			ini_set('memory_limit', $memory);
		}
		//echo "<font color='white'>".ini_get('memory_limit')."</font>";
		
		$seiten = floor($num100 / 20) + 1;
		define('FPDF_FONTPATH','../share/fpdf/font/');
		require $sr.'/bin/share/fpdf/fpdf.php';
		$pdf=new FPDF('P','mm','A4');
		$pdf->SetLeftMargin(20);
		$pdf->SetFont('Arial','',10);
		$aktdatum = date('d.m.Y');
		FOR($seite='0'; $seite<$seiten; $seite++)
		{
			$pdf->AddPage();
			$pdf->Cell(0,5,'pic2base-Galerie',0,1,'C');
			$pdf->Cell(0,5,'Tipp: Mit einem Klick auf den Dateinamen erhalten Sie die vergr'.utf8_decode(ö).'sserte Ansicht des betreffenden Bildes.',0,1,'C');
			FOR($zeile='0'; $zeile<'5'; $zeile++)
			{
				$y_mitte = 50 + $zeile * 48;
				FOR($spalte='0'; $spalte<'4'; $spalte++)
				{
					$x_mitte = 45 + $spalte * 45;
					//Ermittlung des QH-/Ori-Dateinamens und der Bild-Masse
					$zaehler = $spalte + ($zeile * 4) + ($seite * 20);
					IF($zaehler < $num100)
					{
						//echo $zaehler;
						SWITCH($mod)
						{
							CASE 'geo':
							SWITCH($bild)
							{
								CASE '0':
								$result102 = mysql_query("SELECT * FROM $table2 WHERE pic_id = '$statement[$zaehler]'");
								$FileNameV = mysql_result($result102, 0, 'FileNameV');
								//$FileNameHQ = mysql_result($result102, $zaehler, 'FileNameHQ');
								@$parameter_v=getimagesize($sr.'/images/vorschau/thumbs/'.$FileNameV);
								break;
								
								CASE '1':
								$result102 = mysql_query("SELECT * FROM $table2 WHERE pic_id = '$statement[$zaehler]'");
								$FileNameHQ = mysql_result($result102, 0, 'FileNameHQ');
								@$parameter_v=getimagesize($sr.'/images/vorschau/hq-preview/'.$FileNameHQ);
								break;
							}
							$FileName = mysql_result($result102, 0, 'FileName');
							break;
							
							default:
							SWITCH($bild)
							{
								CASE '0':
								$FileNameV = mysql_result($result100, $zaehler, 'FileNameV');
								$FileNameHQ = mysql_result($result100, $zaehler, 'FileNameHQ');
								@$parameter_v=getimagesize($sr.'/images/vorschau/thumbs/'.$FileNameV);
								break;
								
								CASE '1':
								$FileNameHQ = mysql_result($result100, $zaehler, 'FileNameHQ');
								@$parameter_v=getimagesize($sr.'/images/vorschau/hq-preview/'.$FileNameHQ);
								break;
							}
							$FileName = mysql_result($result100, $zaehler, 'FileName');
							break;
						}
						
						$breite = $parameter_v[0];
						$hoehe = $parameter_v[1];
						//echo $hoehe." / ";
						IF($hoehe !== '0' AND $hoehe !== "" AND $hoehe !== NULL)
						{
							$ratio = $breite / $hoehe;
							IF($ratio > '1')
							{
								//Breitformat
								$Breite = 40;
								$Hoehe = number_format($Breite / $ratio,0,'.',',');
							}
							ELSE
							{
								//Hochformat
								$Hoehe = 40;
								$Breite = number_format($Hoehe * $ratio,0,'.',',');
							}
							$x0 = $x_mitte - $Breite / 2;
							$y0 = $y_mitte - $Hoehe / 2;
							SWITCH($bild)
							{
								CASE '0':
								$pdf->Image($sr.'/images/vorschau/thumbs/'.$FileNameV,$x0, $y0, $Breite, $Hoehe, 'jpg');
								break;
								
								CASE '1':
								$pdf->Image($sr.'/images/vorschau/hq-preview/'.$FileNameHQ,$x0, $y0, $Breite, $Hoehe, 'jpg');
								break;
							}
							//Ausgabe des Dateinamens:
							$x1 = $x_mitte - 15;
							$y1 = $y_mitte + 21;
							$pdf->SetXY($x1,$y1);
							$pdf->SetFont('Arial','',8);
							$pdf->Cell(30,5,$FileName,0,0,'C',5,'http://'.$_SERVER['SERVER_NAME'].$inst_path.'/pic2base/images/vorschau/hq-preview/'.$FileNameHQ);
							//$pdf->Cell(30,5,$FileName,0,0,C,0,'http://www.pic2base.de');
						}
						ELSE
						{
							echo "<FONT COLOR='yellow'>Ein oder mehrere Vorschaubilder stehen f&uuml;r die Thumbnail-Galerie nicht zur Verf&uuml;gung.<BR>Bitte lassen Sie von Ihrem Administrator die Datenbank-Wartung ausf&uuml;hren.</FONT>";
						}
					}
				}
			}
			$seiten_nr = $seite + 1;
			$pdf->SetXY(25,270);
			$pdf->Cell(0,6,'Druckdatum: '.$aktdatum.', Seite '.$seiten_nr.' von '.$seiten,0,1,'C');
			$pdf->SetFont('Arial','',10);
		}
		$pdf->Output($p2b_path.'pic2base/userdata/'.$uid.'/kml_files/thumb-gallery.pdf');
	}
}

function showStars($pic_id)
{
	include '../share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	$result1 = mysql_query("SELECT note FROM $table2 WHERE pic_id = '$pic_id'");
	$note = mysql_result($result1, 0, 'note');
	//echo $note;
	SWITCH($note)
	{
		CASE '1':
		//Note 1: beste Bewertung, d.h. 5 Sterne gelb!
		$stars = 	"<span style='Cursor:pointer';><img src=\"$inst_path/pic2base/bin/share/images/y_star.gif\" 			width=\"10\" height=\"10\" / title='Note \"Ungen&uuml;gend\" zuweisen' 						OnClick='saveNewNote(\"$pic_id\", \"5\")'>
				<img src=\"$inst_path/pic2base/bin/share/images/y_star.gif\" width=\"10\" height=\"10\" / title='Note \"Gen&uuml;gend\" zuweisen' OnClick='saveNewNote(\"$pic_id\", \"4\")'>
				<img src=\"$inst_path/pic2base/bin/share/images/y_star.gif\" width=\"10\" height=\"10\" / title='Note \"Befriedigend\" zuweisen' OnClick='saveNewNote(\"$pic_id\", \"3\")'>
				<img src=\"$inst_path/pic2base/bin/share/images/y_star.gif\" width=\"10\" height=\"10\" / title='Note \"Gut\" zuweisen' OnClick='saveNewNote(\"$pic_id\", \"2\")'>
				<img src=\"$inst_path/pic2base/bin/share/images/y_star.gif\" width=\"10\" height=\"10\" / title='Note \"Sehr gut\" zuweisen' OnClick='saveNewNote(\"$pic_id\", \"1\")'></span>";
		break;
		
		CASE '2':
		$stars = 	"<span style='Cursor:pointer';><img src=\"$inst_path/pic2base/bin/share/images/y_star.gif\" 			width=\"10\" 	height=\"10\" / title='Note \"Ungen&uuml;gend\" zuweisen' 					OnClick='saveNewNote(\"$pic_id\", \"5\")'>
				<img src=\"$inst_path/pic2base/bin/share/images/y_star.gif\" width=\"10\" height=\"10\" / title='Note \"Gen&uuml;gend\" zuweisen' OnClick='saveNewNote(\"$pic_id\", \"4\")'>
				<img src=\"$inst_path/pic2base/bin/share/images/y_star.gif\" width=\"10\" height=\"10\" / title='Note \"Befriedigend\" zuweisen' OnClick='saveNewNote(\"$pic_id\", \"3\")'>
				<img src=\"$inst_path/pic2base/bin/share/images/y_star.gif\" width=\"10\" height=\"10\" / title='Note \"Gut\" zuweisen' OnClick='saveNewNote(\"$pic_id\", \"2\")'>
				<img src=\"$inst_path/pic2base/bin/share/images/g_star.gif\" width=\"10\" height=\"10\" / title='Note \"Sehr gut\" zuweisen' OnClick='saveNewNote(\"$pic_id\", \"1\")'></span>";
		break;
		
		CASE '3':
		$stars = 	"<span style='Cursor:pointer';><img src=\"$inst_path/pic2base/bin/share/images/y_star.gif\" 			width=\"10\" height=\"10\" / title='Note \"Ungen&uuml;gend\" zuweisen' 						OnClick='saveNewNote(\"$pic_id\", \"5\")'>
				<img src=\"$inst_path/pic2base/bin/share/images/y_star.gif\" width=\"10\" height=\"10\" / title='Note \"Gen&uuml;gend\" zuweisen' OnClick='saveNewNote(\"$pic_id\", \"4\")'>
				<img src=\"$inst_path/pic2base/bin/share/images/y_star.gif\" width=\"10\" height=\"10\" / title='Note \"Befriedigend\" zuweisen' OnClick='saveNewNote(\"$pic_id\", \"3\")'>
				<img src=\"$inst_path/pic2base/bin/share/images/g_star.gif\" width=\"10\" height=\"10\" / title='Note \"Gut\" zuweisen' OnClick='saveNewNote(\"$pic_id\", \"2\")'>
				<img src=\"$inst_path/pic2base/bin/share/images/g_star.gif\" width=\"10\" height=\"10\" / title='Note \"Sehr gut\" zuweisen' OnClick='saveNewNote(\"$pic_id\", \"1\")'></span>";
		break;
		
		CASE '4':
		$stars = 	"<span style='Cursor:pointer';><img src=\"$inst_path/pic2base/bin/share/images/y_star.gif\" 			width=\"10\" height=\"10\" / title='Note \"Ungen&uuml;gend\" zuweisen' 						OnClick='saveNewNote(\"$pic_id\", \"5\")'>
				<img src=\"$inst_path/pic2base/bin/share/images/y_star.gif\" width=\"10\" height=\"10\" / title='Note \"Gen&uuml;gend\" zuweisen' OnClick='saveNewNote(\"$pic_id\", \"4\")'>
				<img src=\"$inst_path/pic2base/bin/share/images/g_star.gif\" width=\"10\" height=\"10\" / title='Note \"Befriedigend\" zuweisen' OnClick='saveNewNote(\"$pic_id\", \"3\")'>
				<img src=\"$inst_path/pic2base/bin/share/images/g_star.gif\" width=\"10\" height=\"10\" / title='Note \"Gut\" zuweisen' OnClick='saveNewNote(\"$pic_id\", \"2\")'>
				<img src=\"$inst_path/pic2base/bin/share/images/g_star.gif\" width=\"10\" height=\"10\" / title='Note \"Sehr gut\" zuweisen' OnClick='saveNewNote(\"$pic_id\", \"1\")'></span>";
		break;
		
		CASE '5':
		//Note 5: schlechteste Bewertung: 1 Stern gelb!
		$stars = 	"<span style='Cursor:pointer';><img src=\"$inst_path/pic2base/bin/share/images/y_star.gif\" 			width=\"10\" height=\"10\" / title='Note \"Ungen&uuml;gend\" zuweisen' 						OnClick='saveNewNote(\"$pic_id\", \"5\")'>
				<img src=\"$inst_path/pic2base/bin/share/images/g_star.gif\" width=\"10\" height=\"10\" / title='Note \"Gen&uuml;gend\" zuweisen' OnClick='saveNewNote(\"$pic_id\", \"4\")'>
				<img src=\"$inst_path/pic2base/bin/share/images/g_star.gif\" width=\"10\" height=\"10\" / title='Note \"Befriedigend\" zuweisen' OnClick='saveNewNote(\"$pic_id\", \"3\")'>
				<img src=\"$inst_path/pic2base/bin/share/images/g_star.gif\" width=\"10\" height=\"10\" / title='Note \"Gut\" zuweisen' OnClick='saveNewNote(\"$pic_id\", \"2\")'>
				<img src=\"$inst_path/pic2base/bin/share/images/g_star.gif\" width=\"10\" height=\"10\" / title='Note \"Sehr gut\" zuweisen' OnClick='saveNewNote(\"$pic_id\", \"1\")'></span>";
		break;
	}
	
	echo $stars;
}

function showBewertung($bewertung)
{
	global $bew;
	include '../../share/global_config.php';
	$g_s = "<img src=\"$inst_path/pic2base/bin/share/images/g_star.gif\" width=\"15\" height=\"15\">";
	$y_s = "<img src=\"$inst_path/pic2base/bin/share/images/y_star.gif\" width=\"15\" height=\"15\">";
	
	SWITCH($bewertung)
	{
		CASE '=1':
		//Groesser-Zeichen bedeutet: Der Notenwert ist hoeher, d.h die Note ist schlechter!
		$bew = "<span style='position:relative; top:3px';>$g_s$g_s$g_s$g_s$y_s</SPAN>";
		break;
		
		CASE '>=2':
		$bew = "<span style='position:relative; top:3px';>$g_s$g_s$g_s$y_s$y_s</SPAN>";
		break;
		
		CASE '=2':
		$bew = "<span style='position:relative; top:3px';>$g_s$g_s$g_s$y_s$g_s</SPAN>";
		break;
		
		CASE '<=2':
		$bew = "<span style='position:relative; top:3px';>$y_s$y_s$y_s$y_s$g_s</SPAN>";
		break;
		
		CASE '>=3':
		$bew = "<span style='position:relative; top:3px';>$g_s$g_s$y_s$y_s$y_s</SPAN>";
		break;
		
		CASE '=3':
		$bew = "<span style='position:relative; top:3px';>$g_s$g_s$y_s$g_s$g_s</SPAN>";
		break;
		
		CASE '<=3':
		$bew = "<span style='position:relative; top:3px';>$y_s$y_s$y_s$g_s$g_s</SPAN>";
		break;
		
		CASE '>=4':
		$bew = "<span style='position:relative; top:3px';>$g_s$y_s$y_s$y_s$y_s</SPAN>";
		break;
		
		CASE '=4':
		$bew = "<span style='position:relative; top:3px';>$g_s$y_s$g_s$g_s$g_s</SPAN>";
		break;
		
		CASE '<=4':
		$bew = "<span style='position:relative; top:3px';>$y_s$y_s$g_s$g_s$g_s</SPAN>";
		break;
		
		CASE '=5':
		$bew = "<span style='position:relative; top:3px';>$y_s$g_s$g_s$g_s$g_s</SPAN>";
		break;
		
		CASE '6':
		CASE '':
		$bew = "<span style='position:relative; top:3px';>$y_s$y_s$y_s$y_s$y_s</SPAN>";
		//$bewertung = '6';
		break;
	}
	return $bew;
}

function createStatement($bewertung)
{
	global $stat;
	SWITCH($bewertung)
	{
		CASE '=1':
		$stat = "note = '1'";
		break;
		
		CASE ">=2":
		$stat = "note <= '2'";
		break;
		
		CASE "=2":
		$stat = "note = '2'";
		break;
		
		CASE "<=2":
		$stat = "note >= '2'";
		break;
		
		CASE ">=3":
		$stat = "note <= '3'";
		break;
		
		CASE "=3":
		$stat = "note = '3'";
		break;
		
		CASE "<=3":
		$stat = "note >= '3'";
		break;
		
		CASE ">=4":
		$stat = "note <= '4'";
		break;
		
		CASE "=4":
		$stat = "note = '4'";
		break;
		
		CASE "<=4":
		$stat = "note >= '4'";
		break;
		
		CASE "=5":
		$stat = "note = '5'";
		break;
		
		CASE "6":
		CASE "":
		$stat = "note >= '0'";
		//$bewertung = '6';
		break;
		
		default:
		$stat = "note >= '0'";
	}
	return $stat;
}

function savePicture($pic_id,$anzahl,$user_id,$Orientation,$sr)
{
	include $sr.'/bin/share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	
	$md5 = buildMd5sumCommand($sr);
	// wenn eine lagerichtige Kopie angelegt wurde, wird diese weiter verwendet
	IF($Orientation == '3' OR $Orientation == '6' OR $Orientation == '8')
	{
		$FILE = $pic_rot_path."/".$pic_id.".jpg";
	}
	//anderenfalls das Original-jpg:
	ELSE
	{
		$FILE = $pic_path."/".$pic_id.".jpg";
	}
	//vom Original-JPG-Bild wird die Pruefsumme gebildet:
	$command = $md5." $FILE";
	$sum = explode(' ',shell_exec($command));
	//echo "Pr&uuml;fsumme: ".$sum[0]."<BR>";
	
	// 1) anlegen des hq-Bildes: (resamplen, speichern unter /vorschau/hq-preview)
	//HQ aus Original erzeugen
	$max_len = '800';				
	$FileNameHQ = resizeOriginalPicture($FILE, $pic_hq_path, $max_len, $sr);
	$FILEHQ = $pic_hq_path."/".$FileNameHQ;	
	//Thumb aus Performace-Gruenden aus HQ erzeugen
	// 2) Vorschaubild anlegen und im Ordner </vorschau/thumbs> speichern:
	$max_len = '160';
	$FileNameV = createPreviewPicture($FILEHQ, $pic_thumbs_path, $max_len, $sr);
	
	$result1 = mysql_query("UPDATE $table2 SET FileNameHQ = '$FileNameHQ', FileNameV = '$FileNameV', md5sum = '$sum[0]' WHERE pic_id = '$pic_id'");
	
	//die Datei-Attribute werden fuer alle hochgeladenen bzw. erzeugten Bilddateien auf 0700 gesetzt:
	//HQ-Datei:
	$fileHQ = $pic_hq_path."/".$FileNameHQ;
	clearstatcache();  
	chmod ($fileHQ, 0700);
	clearstatcache();
	//Vorschaubild:
	$fileV = $pic_thumbs_path."/".$FileNameV;
	clearstatcache();  
	chmod ($fileV, 0700);
	clearstatcache();
	// damit wurden alle Vorschaubilder (ob mit oder ohne Ausrichtungsinformation) lagerichtig, 
	// also aufrecht abgespeichert
}

/* ab Version 0.60.0 nicht mehr verwendet vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
function getRecDays($y,$m,$stat)
{
	include 'global_config.php';
	include 'db_connect1.php';
	//Bestimmung der Tage in dem Jahr $y und Monat $m, an welchem Aufnahmen angefertigt wurden: (abhaengig von der Bewertung!
	$dat_arr = array();
	
	$result11 = mysql_query("SELECT DISTINCT DateTimeOriginal, pic_id, note
	FROM $table2
	WHERE DateTimeOriginal LIKE '%".$y."-".$m."%' 
	AND $stat
	ORDER BY DateTimeOriginal");
	
	echo mysql_error();
	$num11 = mysql_num_rows($result11);
	FOR($i11='0'; $i11<$num11; $i11++)
	{
		$datum = substr(mysql_result($result11, $i11, 'DateTimeOriginal'),0,10);
		IF(!in_array($datum,$dat_arr))
		{
			$dat_arr[] = $datum;
		}
	}
	return $dat_arr;
	
}
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^*/

function generateHistogram($pic_id,$FileName,$sr)
{
	include $sr.'/bin/share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	//Wenn mind. ein Bild nicht vorhanden ist, werden die Histogramme neu erstellt:
	
	$file_mono = $monochrome_path."/".$pic_id."_mono.jpg";
	$hist = $hist_path."/".$pic_id."_hist.gif";
	$hist_r = $hist_path."/".$pic_id."_hist_0.gif";
	$hist_g = $hist_path."/".$pic_id."_hist_1.gif";
	$hist_b = $hist_path."/".$pic_id."_hist_2.gif";
	$conv = buildConvertCommand($sr);
	$mf = 0;								//$mf - missing files: Anzahl der fehlenden Dateien fuer Histogramme u. Monochrome Bilder
	
	
	//wieviel von den hist- und monochrome-Files fehlen tatsaechlich bei dem Bild mit der genannten pic_id?
	$mf = (file_exists($hist))? $mf : $mf + 1;
	$mf = (file_exists($hist_r))? $mf : $mf + 1;
	$mf = (file_exists($hist_g))? $mf : $mf + 1;
	$mf = (file_exists($hist_b))? $mf : $mf + 1;
	$mf = (file_exists($file_mono))? $mf : $mf + 1;
	
	$file = $pic_hq_path."/".$FileName; 	//<- aus Performance-Gruenden wird Histogr. aus HQ-Bild erstellt!
	shell_exec($conv." ".$file." -separate histogram:".$hist_path."/".$pic_id."_hist_%d.gif");
	
	if(!file_exists($hist))
	{
		shell_exec($conv." ".$file." -colorspace Gray -quality 80% ".$monochrome_path."/".$pic_id."_mono.jpg");
		$file_mono = $monochrome_path."/".$pic_id."_mono.jpg";
		$hist_file = $pic_id.'_hist.gif';
		shell_exec($conv." ".$file_mono." histogram:".$hist_path."/".$hist_file);
		$result1 = mysql_query("UPDATE $table2 SET FileNameHist = '$hist_file' WHERE pic_id = '$pic_id'");
		echo mysql_error();
		clearstatcache();
		chmod ($hist, 0700);
		clearstatcache();
		//$mf++;
	}
	if(!file_exists($hist_r))
	{
		$hist_file_r = $pic_id.'_hist_0.gif';
		shell_exec($conv." ".$hist_path."/".$hist_file_r." -fill red -opaque white ".$hist_path."/".$hist_file_r);
		$result2 = mysql_query("UPDATE $table2 SET FileNameHist_r = '$hist_file_r' WHERE pic_id = '$pic_id'");
		echo mysql_error();
		clearstatcache();
		chmod ($hist_r, 0700);
		clearstatcache();
		//$mf++;
	}
	if(!file_exists($hist_g))
	{
		$hist_file_g = $pic_id.'_hist_1.gif';
		shell_exec($conv." ".$hist_path."/".$hist_file_g." -fill green -opaque white ".$hist_path."/".$hist_file_g);
		$result3 = mysql_query("UPDATE $table2 SET FileNameHist_g = '$hist_file_g' WHERE pic_id = '$pic_id'");
		echo mysql_error();
		clearstatcache();
		chmod ($hist_g, 0700);
		clearstatcache();
		//$mf++;
	}
	if(!file_exists($hist_b))
	{
		$hist_file_b = $pic_id.'_hist_2.gif';
		shell_exec($conv." ".$hist_path."/".$hist_file_b." -fill blue -opaque white ".$hist_path."/".$hist_file_b);
		$result4 = mysql_query("UPDATE $table2 SET FileNameHist_b = '$hist_file_b' WHERE pic_id = '$pic_id'");
		echo mysql_error();
		clearstatcache();
		chmod ($hist_b, 0700);
		clearstatcache();
		//$mf++;
	}
	if(!file_exists($file_mono))
	{
		shell_exec($conv." ".$file." -colorspace Gray -quality 80% ".$monochrome_path."/".$pic_id."_mono.jpg");
		$mono_file = $pic_id."_mono.jpg";
		$result5 = mysql_query("UPDATE $table2 SET FileNameMono = '$mono_file' WHERE pic_id = '$pic_id'");
		echo mysql_error();
		clearstatcache();
		chmod ($file_mono, 0700);
		clearstatcache();
		//$mf++;
	}
	//zur Sicherheit werden die Eintraege in der Tabelle pictures fuer die hist- und mono-files nochmals geschrieben:
	if(file_exists($file_mono) AND file_exists($hist) AND file_exists($hist_r) AND file_exists($hist_g) AND file_exists($hist_b))
	{
		$mono_file = $pic_id."_mono.jpg";
		$hist_file_b = $pic_id.'_hist_2.gif';
		$hist_file_g = $pic_id.'_hist_1.gif';
		$hist_file_r = $pic_id.'_hist_0.gif';
		$hist_file = $pic_id.'_hist.gif';
		$result6 = mysql_query("UPDATE $table2 SET FileNameMono = '$mono_file', FileNameHist_b = '$hist_file_b', FileNameHist_g = '$hist_file_g', FileNameHist_r = '$hist_file_r', FileNameHist = '$hist_file' WHERE pic_id = '$pic_id'");
	}
	return $mf;
}

function getImageInfo($FileName)
{
	include 'global_config.php';
	//echo $pic_path."/".$FileName;
	$code = shell_exec($et_path."/exiftool -h -g -x 'Directory' ".$pic_path."/".$FileName);
	$code = str_replace("<table>", "<TABLE border = '0' style='width:450px;background-color:#FFFFFF' align = 'center'>", $code);
	$code = str_replace("<tr>", "<TR class='normal'>", $code);
	$code = str_replace("<td>", "<TD class='liste2' style='width:225px;'>", $code);
	$code = str_replace("<td colspan=2 bgcolor='#dddddd'>", "<TD class='normal' colspan = '2' align='left' bgcolor='lightgreen'>", $code);
	echo $code;
}

function formatValues($fieldname,$FN,$et_path)
{
	$exiftool = buildExiftoolCommand($sr);
	SWITCH($fieldname)
	{
		CASE 'DateTimeOriginal':
		$value = shell_exec($exiftool." -d '%Y-%m-%d %H:%M:%S' -DateTimeOriginal ".$FN);
		break;
		
		CASE 'GPSLatitude':
		$value = shell_exec($exiftool." -c '%.11f' -GPSLatitude -n ".$FN);
		break;
		
		CASE 'GPSLongitude':
		$value = shell_exec($exiftool." -c '%.11f' -GPSLongitude -n ".$FN);
		break;
		
		CASE 'GPSAltitude':
		$value = shell_exec($exiftool." -c '%.11f' -GPSAltitude -n ".$FN);
		break;
		
		CASE 'XResolution':
		$value = shell_exec($exiftool." -c '%.11f' -XResolution -n ".$FN);
		break;
		
		CASE 'YResolution':
		$value = shell_exec($exiftool." -c '%.11f' -YResolution -n ".$FN);
		break;
		
		CASE 'FNumber':
		$value = shell_exec($exiftool." -c '%.11f' -FNumber -n ".$FN);
		break;
		
		CASE 'ExposureTime':
		$value = shell_exec($exiftool." -c '%.5f' -ExposureTime -n ".$FN);
		break;
		
		CASE 'SensorPixelSize':
		$value = shell_exec($exiftool." -c '%.5f' -SensorPixelSize -n ".$FN);
		break;
		
		CASE 'SerialNumber':
		$value = shell_exec($exiftool." -c '%.5f' -SerialNumber -n ".$FN);
		break;
		
		CASE 'Lens':
		$value = shell_exec($exiftool." -c '%.11f' -Lens ".$FN);
		break;
		
		CASE 'UserComment':
		$value = shell_exec($exiftool." -UserComment ".$FN);
		break;
		
		CASE 'Orientation':
		$value = shell_exec($exiftool." -Orientation -n ".$FN);
		break;
		
		CASE 'FileSize':
		$value = shell_exec($exiftool." -FileSize -n ".$FN);
		break;
		
		default:
		$value = shell_exec($exiftool." -".$fieldname." ".$FN);
		break;
	}
	$info_arr = explode(' : ', $value);
	$value = trim($info_arr[1]);
	return $value;
}

function restoreOriFilename($pic_id, $sr)
{
	//erzeugt den eindeutigen Namen des Bildes in Original-Dateiformat
	include $sr.'/bin/share/db_connect1.php'; // FUER NACHTRAEGLICHE exif-Daten-Erzeugung
	$RES = mysql_query("SELECT FileName, FileNameOri FROM $table2 WHERE pic_id = '$pic_id'");
	mysql_error();
	$row = mysql_fetch_array($RES);
	$FileName = $row['FileName'];
	$FileName_base= explode('.', $FileName);
	$FileNameOri = $row['FileNameOri'];
	$FileNameOri_ext = explode('.', $FileNameOri);
	$FN = strtolower($FileName_base[0].".".$FileNameOri_ext[1]);
	return $FN;
}

function extractExifData($pic_id, $sr, $Orientation)
{
	include $sr.'/bin/share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	
	$exiftool = buildExiftoolCommand($sr);
	
	$result6 = mysql_query("SELECT * FROM $table2 WHERE pic_id = '$pic_id'");
	IF(mysql_num_rows($result6) == 1)
	{
		
		//Ermittlung des Original-Dateinamens mit eindeutiger Bezeichnung (z.B. 12345.nef):
		$FN = $pic_path."/".restoreOriFilename($pic_id, $sr);
		
		$result8 = mysql_query("SHOW COLUMNS FROM $table2");
		$i = 0;
		if($result8 != false)
		{
			while($liste = mysql_fetch_row($result8))
			{
				$tab_fieldname[$i] = $liste[0];	//vorh. Tabellen-Feldname
				$i++;
			}
		}
		else die('Fehler bei der Datenbankabfrage');
		// Fuer jedes Feld der Pictures-Tabelle wird ein evtl. vorhandener Datenwert aus dem Bild 
		// ausgelesen und in die Tabelle geschrieben:
		
		$text = shell_exec($exiftool." ".$FN);
		$inf_arr = explode(chr(10), $text);	//Zerlegung des Textes am Zeilenumbruch
		//echo count($inf_arr)." Meta-Angaben im Bild ".$FN."<BR><BR>";;
		
		FOREACH($inf_arr AS $IA)
		{
			$F_W = explode(' : ', $IA);	//Zerlegung der Zeilen in Feld und Wert
			$fieldname = $F_W[0];
			//Bereinigung des Feldnamen:
			$fieldname = str_replace(" ","",$fieldname);  //Leerzeichen entfernen
			$fieldname = str_replace("/","",$fieldname);  //Schraegstriche entfernen
			$fieldname = str_replace("-","_",$fieldname); //Bindestrich des Meta-Tags durch Unterstrich ersetzen, da Tabellen-Feldnamen nur Unterstriche aufweisen duerfen
			if ( count($F_W) > 1)
			{
				$value = trim($F_W[1]);
			}
			else
			{
				$value = '';
			}
			
			IF(in_array($fieldname,$tab_fieldname))
			{
				//$value = formatValues($fieldname,$FN,$et_path);
				SWITCH($fieldname)
				{
					CASE 'DateTimeOriginal':
					$tmp_value = explode(" ",$value);
					$value = str_replace(':','-',$tmp_value[0])." ".$tmp_value[1];
					break;
					
					CASE 'FileSize':
					$value = shell_exec($exiftool." -FileSize -n ".$FN);
					$fs_arr = explode(' : ', $value);
					$value = trim($fs_arr[1]);	
					break;
					
					CASE 'GPSLatitude':
					$value = shell_exec($exiftool." -c '%.11f' -GPSLatitude -n -s -s -s ".$FN);
					break;
					
					CASE 'GPSLongitude':
					$value = shell_exec($exiftool." -c '%.11f' -GPSLongitude -n -s -s -s ".$FN);
					break;
					
					CASE 'GPSAltitude':
					$value = shell_exec($exiftool." -c '%.11f' -GPSAltitude -n -s -s -s ".$FN);
					break;
				}
				//echo ">>> Feld ".$fieldname." hat den Wert ".$value."<BR>";	
				//Bildbreite- und Hoehe werden zur Sicherheit in 2 Felder (ExifImageHeight (Width) UND ImageHeight (WIdth)) geschrieben:
				IF(($fieldname == 'ExifImageHeight' OR $fieldname == 'ImageHeight') AND ($value !== '0' AND $value !== ''))
				{
					$result4 = mysql_query("UPDATE $table2 SET ExifImageHeight = '$value', ImageHeight = '$value' WHERE pic_id = '$pic_id'");
				}
				ELSEIF(($fieldname == 'ExifImageWidth' OR $fieldname == 'ImageWidth') AND ($value !== '0' AND $value !== ''))
				{
					$result4 = mysql_query("UPDATE $table2 SET ExifImageWidth = '$value', ImageWidth = '$value' WHERE pic_id = '$pic_id'");
				}
				ELSEIF(($fieldname == 'ExifImageHeight' OR $fieldname == 'ImageHeight' OR $fieldname == 'ExifImageWidth' OR $fieldname == 'ImageWidth') AND ($value == '0' OR $value == ''))
				{
					//keine Aktualisierung!
				}
				ELSEIF($fieldname == 'FileName')
				{
					//keine Aktualisierung!
				}
				ELSE
				{
					$value = utf8_decode($value);
					$result4 = mysql_query("UPDATE $table2 SET $fieldname = '$value' WHERE pic_id = '$pic_id'");
					$statement = "UPDATE $table2 SET $fieldname = '$value' WHERE pic_id = '$pic_id'";
				}
				IF(mysql_error() !== '')
				{
					echo "Fehler beim speichern der Meta-Daten (".$fieldname.", ".$value.", ".$statement."): ".mysql_error()."<BR>~~~~~~~~~~~~~~~~~~~~~~~~~~~<BR>";
				}
			}
		}
		// Wenn Breite, Hoehe oder Dateigroesse nicht aus den EXIF-Daten ermittelt werden konnte, 
		// wird versucht, dies per PHP-Routinen zu erledigen:
		$result9 = mysql_query("SELECT * FROM $table2 WHERE pic_id = '$pic_id'");
		$row = mysql_fetch_array($result9);
		$ImageWidth = $row['ImageWidth'];
		$ImageHeight = $row['ImageHeight'];
		$FileSize = $row['FileSize'];
		
		@$params=getimagesize($FN);
		$breite = $params[0];
		$hoehe = $params[1];
		clearstatcache();
		$FileSize = filesize($FN);	//Dateigroesse der Originaldatei in Byte
		
		IF($ImageWidth == '0' OR $ImageWidth == '')
		{
			$result10 = mysql_query("UPDATE $table2 SET ImageWidth = '$breite' WHERE pic_id = '$pic_id'");
		}
		
		IF($ImageHeight == '0' OR $ImageHeight == '')
		{
			$result11 = mysql_query("UPDATE $table2 SET ImageHeight = '$hoehe' WHERE pic_id = '$pic_id'");
		}
		
		IF($FileSize == '0' OR $FileSize == '')
		{
			$result12 = mysql_query("UPDATE $table2 SET FileSize = '$FileSize' WHERE pic_id = '$pic_id'");
		}
		$result13 = mysql_query("UPDATE $table2 SET Orientation = '$Orientation' WHERE pic_id = '$pic_id'");
		//echo mysql_error();
	}
}

function convertISO_ASCII($text)
{
	$array_1 = array('Ä', 'ä', 'Ö', 'ö', 'Ü', 'ü', 'ß');
	$array_2 = array('Ae', 'ae', 'Oe', 'oe', 'Ue', 'ue', 'ss');
	$anzahl = count($array_1);
	for($x = 0; $x < $anzahl; $x++)
	{
		$text = str_replace($array_1[$x], $array_2[$x], $text);
	}
	return $text;
}

function convertOrientationTextToNumber($value)
{
	SWITCH($value)
	{
		CASE 'Horizontal (normal)':
		$value = '1';
		break;
		
		CASE 'Mirror horizontal':
		$value = '2';
		break;
		
		CASE 'Rotate 180':
		$value = '3';
		break;
		
		CASE 'Mirror vertical':
		$value = '4';
		break;
		
		CASE 'Mirror horizontal and rotate 270 CW':
		$value = '5';
		break;
		
		CASE 'Rotate 90 CW':
		$value = '6';
		break;
		
		CASE 'Mirror horizontal and rotate 90 CW':
		$value = '7';
		break;
		
		CASE 'Rotate 270 CW':
		$value = '8';
		break;
	}
	return $value;
}

function checkSoftware($sr)
{
	include $sr.'/bin/share/db_connect1.php';
	include $sr.'/bin/share/global_config.php';
	//Kontrolle, ob erforderliche Software-Komponenten installiert sind:
	$et = shell_exec("which exiftool");
	$conv = shell_exec("which convert");
	$dc = shell_exec("which dcraw");
	$gb = shell_exec("which gpsbabel");
	$md = shell_exec("which md5sum");
	$apache_uid = posix_getuid();
	$apache_gid = posix_getgid();
	
	echo "	<TABLE class='tablenormal' border='0'>
		<TR>
		<TD colspan='2'>Ergebnis der Software-Kontrolle:</TD>
		</TR>
		
		<TR class='trflach'>
		<TD colspan='2'></TD>
		</TR>";
	flush();
	sleep(1);
	$error_count = 0;
	IF($et == NULL)
	{
		echo "<TR>
		<TD class='tdleft'>ExifTool</TD>
		<TD class='tdright'><a href='http://www.sno.phy.queensu.ca/~phil/exiftool/index.html' target='_blank'>wurde nicht gefunden</a></TD>
		</TR>";
		$error_count++;
	}
	ELSE
	{
		$v_et = shell_exec("exiftool -ver");
		echo "<TR>
		<TD class='tdleft'>ExifTool</TD>
		<TD class='tdright'><FONT COLOR='green'>ist in ".$et." installiert (Ver. ".$v_et.")</FONT></TD>
		</TR>";
	}
	flush();
	sleep(1);
	
	IF($conv == NULL)
	{
		echo "<TR>
		<TD class='tdleft'>ImageMagick</TD>
		<TD class='tdright'><a href='http://www.imagemagick.org/script/download.php' target='_blank'>wurde nicht gefunden</a></TD>
		</TR>";
		$error_count++;
	}
	ELSE
	{
		$v_conv = shell_exec("convert -version");
		echo "<TR>
		<TD class='tdleft'>Convert</TD>
		<TD class='tdright'><FONT COLOR='green'>ist in ".$conv." installiert (Ver. <a href='#' title = '$v_conv'>".substr($v_conv,20,6)."</a>)</FONT></TD>
		</TR>";
	}
	flush();
	sleep(1);
	
	IF($dc == NULL)
	{
		echo "<TR>
		<TD class='tdleft'>dcraw</TD>
		<TD class='tdright'><a href='http://www.cybercom.net/~dcoffin/dcraw/dcraw.c' target='_blank'>wurde nicht gefunden</a></TD>
		</TR>";
		$error_count++;
	}
	ELSE
	{
		$v_dc = shell_exec("dcraw");
		echo "<TR>
		<TD class='tdleft'>dcraw</TD>
		<TD class='tdright'><FONT COLOR='green'>ist in ".$dc." installiert (Ver. ".substr($v_dc,28,4).")</FONT></TD>
		</TR>";
	}
	flush();
	sleep(1);
	
	IF($gb == NULL)
	{
		echo "<TR>
		<TD class='tdleft'>GPSBabel</TD>
		<TD class='tdright'><a href='http://www.gpsbabel.org/download.html' target='_blank'>wurde nicht gefunden</a></TD>
		</TR>";
		$error_count++;
	}
	ELSE
	{
		$v_gb = shell_exec("gpsbabel -V");
		echo "<TR>
		<TD class='tdleft'>GPSBabel</TD>
		<TD class='tdright'><FONT COLOR='green'>ist in ".$gb." installiert (Ver. ".substr($v_gb,18,5).")</FONT></TD>
		</TR>";
	}
	flush();
	sleep(1);
	
	IF($md == NULL)
	{
		echo "<TR>
		<TD class='tdleft'>md5sum</TD>
		<TD class='tdright'><a href='http://www.gnu.org/software/coreutils/' target='_blank'>wurde nicht gefunden</a></TD>
		</TR>";
		$error_count++;
	}
	ELSE
	{
		$v_md = shell_exec("md5sum --version");
		echo "<TR>
		<TD class='tdleft'>md5sum</TD>
		<TD class='tdright'><FONT COLOR='green'>ist in ".$md." installiert (Ver. ".substr($v_md,23,3).")</FONT></TD>
		</TR>";
	}
	flush();
	sleep(1);
	
	// es wird kontrolliert, ob die aktuelle Version installiert ist. 
	// Hierzu wird versucht, die p2b_version aus der Tabelle pfade auszulesen. 
	// In diesem Feld steht ab Version 0.60.2 das Pruefkriterium fuer ein Software-Update:
	$result1_1 = mysql_query("SELECT p2b_version FROM $table16");
	@$p2b_version = mysql_result($result1_1, isset($i1_1), 'p2b_version');
	//echo $version."<BR>";	// Version, wie sie im neu eingespielten Installationspaket, in db_connect.php verzeichnet ist.
	//echo $p2b_version."<BR>"; // ist die aus der Tabelle 'pfade' ausgelesene, noch installierte Version
	
	//Fallunterscheidungen: Es kann immer nur ein Update von einer Version zur naechst hoeheren Version durchgefuehrt werden:
	$text = "";
	IF(($p2b_version == 'NULL' OR $p2b_version == '' OR $p2b_version == '0.00.0') AND ($version == '0.60.0'))
	{
		$text = "Es liegen Software-Updates vor.<BR>";
		$text .= "<BR>Sie verwenden jedoch noch die Version ".$version."<BR>";
		$text .= "Diese Version ist zu alt, um direkt ein Update auf Version ".$version." durchzuf&uuml;hren.<BR>";
		$text .= "Kontaktieren Sie f&uuml;r weitere Informationen bitte den <a href='http://www.pic2base.de/impressum1.php'>Hersteller</a>.";
		
	}
	ELSEIF($p2b_version == '0.60.1' AND $version == '0.60.2')
	{
		$text = "<BR><FONT COLOR='red'>Es ist ein Datenbank-Update erforderlich.</b><BR><BR>
		Momentan ist die Version ".$p2b_version." installiert, die mit dem Update auf die Version ".$version." aktualisiert werden kann.<BR><BR>
		Klicken Sie <a href='../../html/db_update_0601_to_0602.php'>hier</a>, um das Update auszuf&uuml;hren.</FONT><BR>";
	}
	ELSEIF($p2b_version == '0.60.1' AND $version > '0.60.2')
	{
		$text = "<BR><FONT COLOR='red'><b>Es liegt ein Update-Problem vor.</b><BR><BR>";
		$text .= "Updates &uuml;ber mehrere Versionen sind leider nicht m&ouml;glich.<BR>
		Momentan ist die Version ".$p2b_version." installiert. Von dieser k&ouml;nnen Sie nicht direkt auf Version ".$version." updaten.<BR>";
		$text .= "Bitte f&uuml;hren Sie zuerst das Update auf Version 0.60.2 durch.</FONT><BR>";
	}
	ELSEIF($p2b_version == '0.60.2' AND $version == '0.60.3')
	{
		$text = "<BR><FONT COLOR='red'>Es ist ein Datenbank-Update erforderlich.</b><BR><BR>
		Momentan ist die Version ".$p2b_version." installiert, die mit dem Update auf die Version ".$version." aktualisiert werden kann.<BR><BR>
		Klicken Sie <a href='../../html/db_update_0602_to_0603.php'>hier</a>, um das Update auszuf&uuml;hren.</FONT><BR>";
	}
	ELSEIF($p2b_version == '0.60.3' AND $version == '0.60.4')
	{
		$text = "<BR><FONT COLOR='red'>Es ist ein Datenbank-Update erforderlich.</b><BR><BR>
		Momentan ist die Version ".$p2b_version." installiert, die mit dem Update auf die Version ".$version." aktualisiert werden kann.<BR><BR>
		Klicken Sie <a href='../../html/db_update_0603_to_0604.php'>hier</a>, um das Update auszuf&uuml;hren.</FONT><BR>";
	}
	ELSEIF($p2b_version == '0.60.2' AND $version > '0.60.3')
	{
		$text = "<BR><FONT COLOR='red'><b>Es liegt ein Update-Problem vor.</b><BR><BR>";
		$text .= "Updates &uuml;ber mehrere Versionen sind leider nicht m&ouml;glich.<BR>
		Momentan ist die Version ".$p2b_version." installiert. Von dieser k&ouml;nnen Sie nicht direkt auf Version ".$version." updaten.<BR>";
		$text .= "Bitte f&uuml;hren Sie zuerst das Update auf Version 0.60.3 durch.</FONT><BR>";
	}
	ELSEIF($p2b_version == $version)
	{
		$text = "<BR><FONT COLOR='green'>Es ist kein Datenbank-Update erforderlich.</FONT><BR>";
	}
	
	//Speicherung der Software-Pfade in der Tabelle 'pfade':
	$result1 = mysql_query("DELETE FROM $table16");
	echo mysql_error();
	
	$result2 = mysql_query("INSERT INTO $table16 (dcraw_path, conv_path, et_path, gpsb_path, md5sum_path, apache_uid, apache_gid, p2b_version) 
	VALUES ('$dc', '$conv', '$et', '$gb', '$md', '$apache_uid', '$apache_gid', '$p2b_version')");
	
	echo "	<TR class='trflach'>
		<TD colspan='2'></TD>
		</TR>
		</TABLE>";
	IF($error_count !== 0)
	{
		echo "<CENTER>
		<TABLE style='width:500px; text-align:center;'>
		<TR>
		<TD><BR><u>WICHTIGER HINWEIS:</u><BR>Sollte eine der gelisteten Software-Komponenten nicht installiert sein, 
		holen Sie dies bitte <b>VOR</b> der ersten Benutzung von pic2base nach.<BR>
		Anderenfalls werden einige Funktionen fehlen oder fehlerhaft sein!<BR><BR>
		Sollte eine Software-Komponente hier als \"nicht installiert\" angezeigt werden,<BR>obwohl Sie sicher sind, da&szlig; sie vorhanden ist,<BR>
		kann es in wenigen Ausnahmef&auml;llen erforderlich sein,<BR>den pic2base-Server neu zu starten.<BR>
		Dies kann vor allem dann auftreten,<BR>wenn erforderliche Software-Komponenten nicht &uuml;ber den <BR>
		Paketmanager hinzugef&uuml;gt wurden.
		</TD>
		</TR>
		</TABLE>
		</CENTER>";
	}
	ELSE
	{
		// Wenn in der Tabelle users nur der user pb enthalten ist (Auslieferungszustand), erfolgt die Weiterleitung mit entspr. Hinweis zur Anlage des ersten echten Administrators:
		$result3 = mysql_query("SELECT * FROM $table1");
		$num3 = mysql_num_rows($result3);
		$username = mysql_result($result3, isset($i3), 'username');
		IF($num3 == 1 AND $username == 'pb')
		{
			echo "<CENTER>
			<TABLE style='width:500px; text-align:center;'>
			<TR>
			<TD><BR><FONT COLOR='green'>Die erforderlichen Software-Komponenten sind installiert.</font><BR>".$text."<BR><font color='red'>Legen Sie nun bitte den ersten Benutzer mit Admin-Berechtigung an.</font><BR><BR>
			<input type='button' value='Ersten Benutzer anlegen' onClick=\"location.href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=adminadduser'\">
			</TD>
			</TR>
			</TABLE>
			</CENTER>";
		}
		ELSEIF($num3 > 1)
		{
			echo "<CENTER>
			<TABLE style='width:500px; text-align:center;'>
			<TR>
			<TD><BR><FONT COLOR='green'>Die erforderlichen Software-Komponenten sind installiert.</font><BR>".$text."<BR>
			<input type='button' value='Zur Startseite' onClick=\"location.href='$inst_path/pic2base/bin/html/start.php'\"> 
			</TD>
			</TR>
			</TABLE>
			</CENTER>";
		}
	}
}

function directDownload($uid, $sr)
{
	include $sr.'/bin/share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	$res_direct_download = mysql_query("SELECT direkt_download FROM $table1 WHERE id = '$uid'");
	//echo mysql_error();
	$dir_down = mysql_result($res_direct_download, isset($i1), 'direkt_download');
	IF($dir_down == 1)
	{
		RETURN True;
	}
	ELSEIF($dir_down == 0)
	{
		RETURN False;
	}
}

/*
function getUserId($c_username, $sr)
{
	include $sr.'/bin/share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	$result1 = mysql_query("SELECT id FROM $table1 WHERE username = '$c_username'");
	$user_id = mysql_result($result1, isset($i1), 'id');
	return $user_id;
}
*/

function buildDcrawCommand($sr)
{
	include $sr.'/bin/share/db_connect1.php';
	$result1 = mysql_query("SELECT dcraw_path FROM $table16");
	$dcraw_path = trim(mysql_result($result1, isset($i1), 'dcraw_path'));	//trim beseitigt Fehler verursachende Leerzeichen!!!
	RETURN $dcraw_path;
}

function buildConvertCommand($sr)
{
	include $sr.'/bin/share/db_connect1.php';
	$result1 = mysql_query("SELECT conv_path FROM $table16");
	$conv_path = trim(mysql_result($result1, isset($i1), 'conv_path'));		//trim beseitigt Fehler verursachende Leerzeichen!!!
	RETURN $conv_path;
}

function buildExiftoolCommand($sr)
{
	include $sr.'/bin/share/db_connect1.php';
	$result1 = mysql_query("SELECT et_path FROM $table16");
	$et_path = trim(mysql_result($result1, isset($i1), 'et_path'));		//trim beseitigt Fehler verursachende Leerzeichen!!!
	RETURN $et_path;
}

function buildGpsbabelCommand($sr)
{
	include $sr.'/bin/share/db_connect1.php';
	$result1 = mysql_query("SELECT gpsb_path FROM $table16");
	$gpsb_path = trim(mysql_result($result1, isset($i1), 'gpsb_path'));		//trim beseitigt Fehler verursachende Leerzeichen!!!
	RETURN $gpsb_path;
}

function buildMd5sumCommand($sr)
{
	include $sr.'/bin/share/db_connect1.php';
	$result1 = mysql_query("SELECT md5sum_path FROM $table16");
	$md5sum_path = trim(mysql_result($result1, isset($i1), 'md5sum_path'));		//trim beseitigt Fehler verursachende Leerzeichen!!!
	RETURN $md5sum_path;
}

function showCheckboxes($base_file, $auswahl, $result2, $num2, $sr)
{
	include $sr.'/bin/share/db_connect1.php';
	SWITCH($base_file)
	{
		CASE 'edit_remove_kat':
		//keine Anzeige der Checkboxen!
		break;
		
		CASE 'edit_beschreibung':
		CASE 'edit_kat_daten':
		CASE 'remove_kat_daten':
			IF ($auswahl == '0')
			{
				$checked = '';
			}
			ELSE
			{
				$checked = 'checked';
			}
			FOR ($i2=0; $i2<$num2; $i2++)
			{
				$pic_id = mysql_result($result2, $i2, 'pic_id');
				echo "	<TD align='center'>
						<INPUT type='checkbox' name='pic_sel$pic_id' $checked>
						</TD>";
			}
		break;
		
		CASE 'edit_bewertung':
			FOR ($i2=0; $i2<$num2; $i2++)
			{
				$pic_id = mysql_result($result2, $i2, 'pic_id');
				echo "	<TD align='center'>
						<div id = 'star_set$pic_id'>";
						showStars($pic_id);
				echo "	</div>
						</TD>";
			}
		break;
	}
}
?>