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

//==============================================
//											   |
//* Skript generiert die Filmstreifen-Ansicht  |
//											   |
//==============================================

IF(array_key_exists('kat_id', $_GET))
{
	$kat_id = $_GET['kat_id'];
}
IF(array_key_exists('mod', $_GET))
{
	$mod = $_GET['mod'];
}
IF(array_key_exists('pic_id', $_GET))
{
	$pic_id = $_GET['pic_id'];
}
IF(array_key_exists('modus', $_GET))
{
	$modus = $_GET['modus'];
}
IF(array_key_exists('base_file', $_GET))
{
	$base_file = $_GET['base_file'];
}
IF(array_key_exists('bewertung', $_GET))
{
	$bewertung = $_GET['bewertung'];
}
IF(array_key_exists('auswahl', $_GET))
{
	$auswahl = $_GET['auswahl'];
}
IF(array_key_exists('position', $_GET))
{
	$position = $_GET['position'];
}
IF(array_key_exists('jump', $_GET))
{
	$jump = $_GET['jump'];
}

$N = '';

if(array_key_exists('ID',$_GET))
{
	$ID = $_GET['ID'];
}
if( array_key_exists('desc1',$_GET))
{
	$desc1 = $_GET['desc1'];
}
if( array_key_exists('bed1',$_GET))
{
	$bed1 = $_GET['bed1'];
}
if( array_key_exists('desc2',$_GET))
{
	$desc2 = $_GET['desc2'];
}
if( array_key_exists('bed2',$_GET))
{
	$bed2 = $_GET['bed2'];
}
if( array_key_exists('desc3',$_GET))
{
	$desc3 = $_GET['desc3'];
}
if( array_key_exists('bed3',$_GET))
{
	$bed3 = $_GET['bed3'];
}
if( array_key_exists('desc4',$_GET))
{
	$desc4 = $_GET['desc4'];
}
if( array_key_exists('bed4',$_GET))
{
	$bed4 = $_GET['bed4'];
}
if( array_key_exists('desc5',$_GET))
{
	$desc5 = $_GET['desc5'];
}
if( array_key_exists('bed5',$_GET))
{
	$bed5 = $_GET['bed5'];
}

if(!isset($krit1))
{
	$krit1 = '';
}
if(!isset($krit2))
{
	$krit2 = '';
}

if( array_key_exists('j',$_GET))
{
	$j = $_GET['j'];
}
if( array_key_exists('m',$_GET))
{
	$m = $_GET['m'];
}
if( array_key_exists('t',$_GET))
{
	$t = $_GET['t'];
}
if( array_key_exists('zw1',$_GET))
{
	$zw1 = $_GET['zw1'];
}
if( array_key_exists('bedingung1',$_GET))
{
	$bedingung1 = $_GET['bedingung1'];
}
if( array_key_exists('zusatz1',$_GET))
{
	$zusatz1 = $_GET['zusatz1'];
}

if( array_key_exists('long',$_GET))
{
	$long = $_GET['long'];
}
if( array_key_exists('lat',$_GET))
{
	$lat = $_GET['lat'];
}
if( array_key_exists('radius1',$_GET))
{
	$radius1 = $_GET['radius1'];
}
if( array_key_exists('einheit1',$_GET))
{
	$einheit1 = $_GET['einheit1'];
}
if( array_key_exists('radius2',$_GET))
{
	$radius2 = $_GET['radius2'];
}
if( array_key_exists('einheit2',$_GET))
{
	$einheit2 = $_GET['einheit2'];
}
if( array_key_exists('alt',$_GET))
{
	$alt = $_GET['alt'];
}
if( array_key_exists('ort',$_GET))
{
	$ort = $_GET['ort'];
}

if( array_key_exists('form_name',$_GET))
{
	$form_name = $_GET['form_name'];
}
else
{
	$form_name ='';
}

if( array_key_exists('treestatus',$_GET))
{
	$treestatus = $_GET['treestatus'];
}

//Parameter der Experten-Suche1 (Kategorien):
if( array_key_exists('kat',$_GET))
{
	$kat = $_GET['kat'];
}

if( array_key_exists('op',$_GET))
{
	$op = $_GET['op'];
}

if( array_key_exists('kat1',$_GET))
{
	$kat1 = $_GET['kat1'];
}

if( array_key_exists('op1',$_GET))
{
	$op1 = $_GET['op1'];
}

if( array_key_exists('kat2',$_GET))
{
	$kat2 = $_GET['kat2'];
}

if( array_key_exists('op2',$_GET))
{
	$op2 = $_GET['op2'];
}

if( array_key_exists('kat3',$_GET))
{
	$kat3 = $_GET['kat3'];
}

if( array_key_exists('op3',$_GET))
{
	$op3 = $_GET['op3'];
}

if( array_key_exists('kat4',$_GET))
{
	$kat4 = $_GET['kat4'];
}

if( array_key_exists('op4',$_GET))
{
	$op4 = $_GET['op4'];
}

//echo $param;
//echo $mod;
//Auslesen der Vorschau-Bilder aus den EXIF-Daten
//verwendet in edit_kat_daten.php, recherche2.php, edit_beschreibung.php, remove_kat_daten
//echo "&Uuml;bergebene Parameter: kat_id: ".$kat_id.", ID: ".$ID.", mod: ".$mod.", pic_id: ".$pic_id.", modus: ".$modus;
//echo "Base-File: ".$base_file;
//echo "Bewertung: ".$bewertung;
//echo "Server-URL: ".$server_url;
//########################################################################################################################
//Darstellung der zu einer Kategorie zugehoerigen Bilder:

include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/permissions.php';

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');
$user_id = $uid;

$server_url = "http://{$_SERVER['SERVER_NAME']}$inst_path";

$hoehe_neu = '';
$breite_neu = '';
$previewLayerHtml = '';

if (!isset($zusatz))
{
	$zusatz = '';
}

echo '
<script language="javascript">
var c_username = "'.$username.'";
</script>
';

SWITCH ($modus)
{
	CASE 'edit':
	//echo "ID: ".$ID.", kat_id: ".$kat_id;
	// Bildbearbeitung ueber Bildauswahl nach Kategorie
	echo "<input type='hidden' name='ID' value='$ID'>"; //verwendet bei Kategoriezuweisung aufheben (remove_kat_daten.php)
	SWITCH ($ID)
	{
		CASE '':
		//Wenn noch keine Kategorie gewaehlt wurde:
		echo "<p class='gross' style='color:yellow; text-align:center;'>Bitte w&auml;hlen Sie zun&auml;chst in der linken Spalte Bilder einer Kategorie aus!</p>";
		break;
		//###########################################################################################################
		CASE '1':
		//Wenn die Wurzel-Kategorie gewaehlt wurde, werden alle Bilder angezeigt, denen noch keine Kategorie zugewiesen wurde:
		$result2 = mysql_query("SELECT DateTimeOriginal, ShutterCount, DateInsert, pic_id, FileName, FileNameHQ, FileNameV, has_kat, FileSize, Orientation, note, Owner, aktiv
		FROM $table2 
		WHERE Owner = '$user_id' 
		AND has_kat = '0'
		AND aktiv = '1' $krit2 
		ORDER BY DateTimeOriginal, ShutterCount, DateInsert");
		echo mysql_error();
		@$num2 = mysql_num_rows($result2);
		$N = $num2;
		SWITCH ($N)
		{
			CASE '0':
			CASE '':
			echo "<p class='gross' style='color:green; text-align:center;'>Jedem Bild wurde mind. eine Kategorie zugewiesen.</p>";
			break;
			
			CASE '1':
			echo "Es gibt ein Bild ohne Kategorie-Zuweisung.";
			break;
			
			default:
			echo $N." Bilder sind ohne Kategorie-Zuweisung.";
			break;
		}
		
		echo "	<TABLE border='0' align='center'>
			<TR>";
		
		IF ($N > '0')
		{
			FOR ($i2=0; $i2<$num2; $i2++)
			{
				$pic_id = mysql_result($result2, $i2, 'pic_id');
				$FileName = mysql_result($result2, $i2, 'FileName');
				$FileNameHQ = mysql_result($result2, $i2, 'FileNameHQ');
				$FileNameV = mysql_result($result2, $i2, 'FileNameV');
				$FileSize = mysql_result($result2, $i2, 'FileSize');
				//Initialisierung der Fehlerindikatoren:
				$error_breite = 0;
				$error_hoehe = 0;
				//abgeleitete Groessen:
				// Fehlerbehandlungen, falls Vorschaubilder fehlen oder der Datensatz korrupt ist:
				IF ($FileNameV == '')
				{
					$FileNameV = 'no_preview.jpg';
				}
				ELSE
				{
					@$parameter_v=getimagesize($sr.'/images/vorschau/hq-preview/'.$FileNameHQ);
				}	
				if(@$parameter_v[0])
				{
					$breite = $parameter_v[0];
					$breite_v = $breite * 5;
				}
				else
				{
					$error_breite = 1;
					$breite = 0;
				}
				
				if(@$parameter_v[1])
				{
					$hoehe = $parameter_v[1];
					$hoehe_v = $hoehe * 5;
				}
				else
				{
					$error_hoehe = 1;
					$hoehe = 0;
				}
				
				if(($error_breite + $error_hoehe) > 0)
				{
					$error_msg = "<font color='yellow'><span  style='cursor:pointer;' title='Es fehlen Vorschaubilder. Der Datensatz ist korrupt.'>Fehler bei Bild ".$pic_id."</span></font>";
				}
				else
				{
					$error_msg = "";
				}
				
				IF ($breite == 0 OR $hoehe == 0)
				{
					//echo "Keine Groessenangaben!";
					$breite_v = 800;
					$hoehe_v = 600;
				}
				ELSE
				{
					$hoehe_neu = $fs_hoehe;
					$breite_neu = number_format(($fs_hoehe * $breite / $hoehe),0,',','.');
				}
					
				echo mysql_error();
				echo "<TD align='center'>".$error_msg;
				getHQPreviewNow($pic_id, $hoehe_neu, $breite_neu, $base_file, $kat_id, $mod, $form_name);
				$PIC_ID[] = $pic_id;
			}
			echo "	
			</TR>
			<TR>";
			//nicht in alle Faellen werden die Checkboxen dargestellt:
			//echo $base_file;
			IF($base_file !== 'remove_kat_daten')
			{
				showCheckboxes($base_file, $auswahl, $result2, $num2, $sr);
			}
		}
		echo "	</TR>
			</TABLE>";
		break;
		//################################################################################################################
		default:
		//gueltig fuer alle Kategorien ausser Wurzel:
		//abhaengig von der Berechtigung werden die in Frage kommenden Bilder dargestellt:

		IF(hasPermission($uid, 'editallpics', $sr))
		{
			IF($treestatus == 'plus')
			{
				$result2 = mysql_query("SELECT $table2.*, $table10.* 
				FROM $table2, $table10 
				WHERE $table2.pic_id = $table10.pic_id 
				AND $table10.kat_id = '$ID'
				AND $table2.aktiv = '1' 
				$krit2 
				ORDER BY $table2.DateTimeOriginal, $table2.ShutterCount, $table2.DateInsert");
			}
			ELSEIF($treestatus == 'minus')
			{
				$result2 = mysql_query("SELECT $table10.pic_id, $table10.kat_id FROM $table10
				WHERE ($table10.kat_id = '$kat_id') 
				AND ($table10.pic_id <> ALL (SELECT pic_id 
				FROM $table10 LEFT JOIN $table4 
				ON ($table10.kat_id = $table4.kat_id) 
				WHERE parent = '$kat_id'))");
				echo mysql_error();
			}
		}
		ELSEIF(hasPermission($uid, 'editmypics', $sr))
		{
			IF($treestatus == 'plus')
			{
				$result2 = mysql_query( "SELECT $table2.*, $table10.* 
				FROM $table2, $table10 
				WHERE ($table2.pic_id = $table10.pic_id 
				AND $table10.kat_id = '$ID'
				AND $table2.aktiv = '1' 
				AND $table2.Owner = '$user_id' $krit2) 
				ORDER BY $table2.DateTimeOriginal, $table2.ShutterCount, $table2.DateInsert");
			}
			ELSEIF($treestatus == 'minus')
			{
				$result2 = mysql_query("SELECT $table10.pic_id, $table10.kat_id, $table2.Owner, $table2.pic_id 
				FROM $table10 INNER JOIN $table2
				ON ($table10.kat_id = '$kat_id')
				AND $table10.pic_id = $table2.pic_id 
				AND $table2.Owner = '$user_id' 
				AND ($table10.pic_id <> ALL (SELECT pic_id 
				FROM $table10 LEFT JOIN $table4 
				ON ($table10.kat_id = $table4.kat_id) WHERE parent = '$kat_id'))");
			}
		}
		echo mysql_error();
		$num2 = mysql_num_rows($result2);
		IF ($num2 == '0')
		{
			echo "<p class='gross' style='color:green; text-align:center;'>Es gibt keine Bilder, die der gew&auml;hlten Kategorien zugewiesen wurden!</p>";
			return;
		}
		ELSE
		{
			$result4 = mysql_query( "SELECT kategorie FROM $table4 WHERE kat_id='$ID'");
			$kategorie = mysql_result($result4, isset($i4), 'kategorie');
			//echo "Es gibt ".$num2." Bilder in der Kategorie \"".$kategorie."\"";
			echo $num2." Bilder in der Kategorie \"".$kategorie."\"";
			//Es wird eine zweizeilige Tabelle erzeugt, in deren oberer Zeile die Vorschaubilder zu sehen sind, 
			//in der unteren die jeweils dazugehoerigen Auswahlboxen:
			//der Normalfall - Es werden alle Bilder angezeigt, welche der gewaehlten Kategorie angehoeren
			echo "	<TABLE border='0' align='center'>
			<TR>";
			FOR ($i2=0; $i2<$num2; $i2++)
			{
				$pic_id = mysql_result($result2, $i2, 'pic_id');
				$res2_1 = mysql_query("SELECT FileName, FileNameHQ, FileNameV, FileSize FROM $table2 WHERE pic_id = '$pic_id'");
				$FileName = mysql_result($res2_1, isset($i2_1), 'FileName');
				$FileNameHQ = mysql_result($res2_1, isset($i2_1), 'FileNameHQ');
				$FileNameV = mysql_result($res2_1, isset($i2_1), 'FileNameV');
				$FileSize = mysql_result($res2_1, isset($i2_1), 'FileSize');
				//abgeleitete Groessen:
				IF ($FileNameV == '')
				{
					$FileNameV = 'no_preview.jpg';
				}
				ELSE
				{
					@$parameter_v=getimagesize($sr.'/images/vorschau/hq-preview/'.$FileNameHQ);
				}
				$breite = $parameter_v[0];
				$hoehe = $parameter_v[1];
				$breite_v = $breite * 5;
				$hoehe_v = $hoehe * 5;
				IF ($breite == 0 OR $hoehe == 0)
				{
					//echo "Keine Groessenangaben!";
					$breite_v = 800;
					$hoehe_v = 600;
				}
				ELSE
				{
					$hoehe_neu = $fs_hoehe;
					$breite_neu = number_format(($fs_hoehe * $breite / $hoehe),0,',','.');
				}
					
				echo mysql_error();
					
				echo "<TD align='center'>";
				getHQPreviewNow($pic_id, $hoehe_neu, $breite_neu, $base_file, $kat_id, $mod, $form_name);
			}
			echo "	</TR>";
			//nicht in alle Faellen werden die Checkboxen dargestellt:
			showCheckboxes($base_file, $auswahl, $result2, $num2, $sr);
		}
		echo "	</TR>
			</TABLE>";
		break;
	}
	break;
	
	//###############################################################################################################################

	CASE 'zeit':
	// Bildbearbeitung ueber Bildauswahl nach Aufnahmedatum
	$auswahl = $pic_id;  //hier wird die uebergebene pic_id zweckentfremdet verwendet!!!
	$kat_id = 0;
	SWITCH ($m)
	{
		CASE '0':
		//Anzeige der Jahrgaenge
		$krit1 = "$table2.DateTimeOriginal LIKE '$j%'";
		break;
		
		Case '00':
		$krit1 = "$table2.DateTimeOriginal = '0000-00-00 00:00:00'";
		break;
		
		default:
		IF($t == '0')
		{
			$krit1 = "$table2.DateTimeOriginal LIKE '$j-$m%'";
		}
		ELSE
		{
			$krit1 = "$table2.DateTimeOriginal LIKE '$j-$m-$t%'";
		}
		break;
	}
	//echo "Kriterium 1: ".$krit1;
	
	IF(hasPermission($uid, 'editallpics', $sr))
	{
		$result2 = mysql_query( "SELECT * FROM $table2
		WHERE aktiv = '1'
		AND $krit1 
		ORDER BY DateTimeOriginal, ShutterCount, DateInsert");
	}
	ELSEIF(hasPermission($uid, 'editmypics', $sr))
	{
		$result2 = mysql_query( "SELECT * FROM $table2 
		WHERE (Owner = '$user_id' 
		AND aktiv = '1'
		AND $krit1) 
		ORDER BY DateTimeOriginal, ShutterCount, DateInsert");	
	}
	echo mysql_error();
	$num2 = mysql_num_rows($result2);
	IF ($num2 == '0')
	{
		echo "<p class='gross' style='color:green; text-align:center;'>Es gibt keine Bilder...?</p>";
		return;
	}
	ELSE
	{
		//Es wird eine zweizeilige Tabelle erzeugt, in deren oberer Zeile die Vorschaubilder zu sehen sind, 
		//in der unteren die jeweils dazugehoerigen Auswahlboxen:
		//der Normalfall - Es werden alle Bilder angezeigt, welche der gewaehlten Kategorie angehoeren
		echo "	<TABLE border='0' align='center'>
		<TR>";
		FOR ($i2=0; $i2<$num2; $i2++)
		{
			$pic_id = mysql_result($result2, $i2, 'pic_id');
			$res2_1 = mysql_query("SELECT FileName, FileNameHQ, FileNameV, FileSize, Orientation 
			FROM $table2 
			WHERE pic_id = '$pic_id'");
			$FileName = mysql_result($res2_1, isset($i2_1), 'FileName');
			$FileNameHQ = mysql_result($res2_1, isset($i2_1), 'FileNameHQ');
			$FileNameV = mysql_result($res2_1, isset($i2_1), 'FileNameV');
			$FileSize = mysql_result($res2_1, isset($i2_1), 'FileSize');
			//abgeleitete Groessen:
			IF ($FileNameV == '')
			{
				$FileNameV = 'no_preview.jpg';
			}
			ELSE
			{
				@$parameter_v=getimagesize($sr.'/images/vorschau/hq-preview/'.$FileNameHQ);
			}
			$breite = $parameter_v[0];
			$hoehe = $parameter_v[1];
			$breite_v = $breite * 5;
			$hoehe_v = $hoehe * 5;
			IF ($breite == 0 OR $hoehe == 0)
			{
				//echo "Keine Groessenangaben!";
				$breite_v = 800;
				$hoehe_v = 600;
			}
			ELSE
			{
				$hoehe_neu = $fs_hoehe;
				$breite_neu = number_format(($fs_hoehe * $breite / $hoehe),0,',','.');
			}
				
			echo mysql_error();
				
			echo "<TD align='center'>";
			getHQPreviewNow($pic_id, $hoehe_neu, $breite_neu, $base_file, $kat_id, $mod, $form_name);
		}
		echo "	</TR>";
		//nicht in alle Faellen werden die Checkboxen dargestellt:
		showCheckboxes($base_file, $auswahl, $result2, $num2, $sr);
	}
	echo "	</TR>
		</TABLE>";
	break;

//##################################################################################################################
	
	CASE 'recherche':
//		$step = 6;	//Anzahl der im Filmstreifen dargestellten Bilder (Schrittweite)
		IF($bewertung !== '6')
		{
			//Bewertungskriterium wird in Vergleichsoperator und Wert zerlegt:
			//Groesser-Zeichen bedeutet: Der Notenwert ist hoeher, d.h die Note ist schlechter!
			$vgl_op = substr($bewertung,0,strlen($bewertung) - 1);
			IF($vgl_op == '<=')
			{
				$vgl_op = '>=';
			}
			ELSEIF($vgl_op == '>=')
			{
				$vgl_op = '<=';
			}
			$wert = substr($bewertung,-1);
			$krit2 = "AND $table2.note $vgl_op '$wert'";
		}
		ELSE
		{
			$krit2 = "";
		}

		SWITCH($mod)
		{
			//Ermittlung der Ergebnismengen: ges. Bildanzahl und hiervon geo-referenzierte
			CASE 'zeit':
			SWITCH ($m)
			{
				CASE '0':
				//Anzeige der Jahrgaenge
				$krit1 = "WHERE $table2.DateTimeOriginal LIKE '$j%'";
				break;
				
				Case '00':
				$krit1 = "WHERE $table2.DateTimeOriginal = '0000-00-00 00:00:00'";
				break;
				
				default:
				IF($t == '0')
				{
					$krit1 = "WHERE $table2.DateTimeOriginal LIKE '$j-$m%'";
				}
				ELSE
				{
					$krit1 = "WHERE $table2.DateTimeOriginal LIKE '$j-$m-$t%'";
				}
				break;
			}
			$statement = "SELECT DateTimeOriginal, ShutterCount, DateInsert, pic_id, ExifImageWidth, ExifImageHeight, Orientation, Owner, note, FileNameV, FileNameHQ, FileName, aktiv 
			FROM $table2 
			$krit1 
			$krit2 
			AND aktiv = '1' 
			ORDER BY DateTimeOriginal, ShutterCount, DateInsert";
			$pdf_statement = urlencode($statement);  //Statement wird zur Erzeugung der pdf-Galerie benoetigt	
			$result6_1 = mysql_query($statement);
			echo mysql_error();
			$num6_1 = mysql_num_rows($result6_1);  	//Gesamtzahl der gefundenen Bilder
			$kml_statement = "SELECT pic_id, City, GPSLongitude, GPSLatitude, GPSAltitude, note, FileNameHQ, Caption_Abstract, DateTimeOriginal, ShutterCount, DateInsert, aktiv 
			FROM $table2 
			$krit1 
			$krit2 
			AND aktiv = '1' 
			AND City <> 'Ortsbezeichnung'
			AND City <> '' 
			ORDER BY DateTimeOriginal, ShutterCount, DateInsert";
			$kml_cod_statement = urlencode($kml_statement); // wird bei der Erzeugung der kml-Datei verwendet
			$result8 = mysql_query( "$kml_statement");
			$num8 = mysql_num_rows($result8);	//Anzahl der geo-referenzierten Bilder
			echo mysql_error();
			
			$previewLayerHtml .= '
			<script language="javascript">
			var imageArray = new Array();
			self.getImageArray = function getImageArray()
			{
				  imageArray = [];';
				$previewLayerHtml .= generateImageArray($result6_1, $username, $uid, $sr);
				$previewLayerHtml .= '
				  return imageArray;
			}
			</script>';
			
			SWITCH ($num6_1)
			{
				CASE '0':
					$text1 = "";
				break;
				
				CASE '1':
					$text1 = "<div id='tooltip1'>Es wurde ein Bild gefunden.";
				break;
				
				default:
					$text1 = "<div id='tooltip1'>Es wurden ".$num6_1." Bilder gefunden.";
				break;
			}
		break;
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~	
		CASE 'kat':
			SWITCH ($ID)
			{
				CASE '':
				//Wenn noch keine Kategorie gewaehlt wurde:
				echo "<p class='gross' style='color:yellow; text-align:center;'>Bitte w&auml;hlen Sie zun&auml;chst in der linken Spalte Bilder einer Kategorie aus!</p>";
				break;
	
				CASE '1':
				//Wenn die Wurzel-Kategorie gewaehlt wurde, werden alle Bilder angezeigt, denen noch keine Kategorie zugewiesen wurde:
				$statement = "SELECT *	FROM $table2 
				WHERE has_kat = '0'
				AND aktiv = '1' $krit2 
				ORDER BY DateTimeOriginal, ShutterCount, DateInsert";
				$pdf_statement = urlencode($statement);
				$result6_1 = mysql_query($statement);
				$num6_1 = mysql_num_rows($result6_1);  	//Gesamtzahl der gefundenen Bilder (hier: ohne Kategorie-Zuweisung)
				/*
				//davon Ermittlung aller Bilder mit Geo-Referenzierung:
				$kml_statement = "SELECT * 
				FROM $table2
				WHERE has_kat = '0' 
				AND aktiv = '1'
				AND City <> 'Ortsbezeichnung'
				AND City <> '' 
				$krit2";
				//echo $kml_statement;
				$kml_cod_statement = urlencode($kml_statement); // wird bei der Erzeugung der kml-Datei verwendet
				$result8 = mysql_query( "$kml_statement");		// echo mysql_error();
				if($result8)
				{
					$num8 = mysql_num_rows($result8);
				}
				else
				{
					$num8 = 0;
				}
				*/
				if($num6_1 > 0)
				{
					//echo "Bilder ohne Kat.-Zuw: ".$num6_1." - ";
					$previewLayerHtml .= '
					<script language="javascript">
					var imageArray = new Array();
					self.getImageArray = function getImageArray()
					{
					  imageArray = [];
					';
					$previewLayerHtml .= generateImageArray($result6_1, $username, $uid, $sr);
					$previewLayerHtml .= '
					  return imageArray;
					}
					</script>
					';
				}
				$N = $num6_1;
				SWITCH ($num6_1)
				{
					CASE '0':
					echo "<p class='gross' style='color:green; text-align:center;'>Jedem Bild wurde mind. eine Kategorie zugewiesen.</p>";
					break;
				
					CASE '1':
					echo "Es gibt ein Bild ohne Kategorie-Zuweisung.";
					break;
				
					default:
					echo $num6_1." Bilder sind ohne Kategorie-Zuweisung.";
					break;
				}
				break;
// ################################################################################################################		
				default:
				//bei allen Kategorien ausser der Wurzel:
				//Ermittlung aller Bilder der Kategorie:
				$statement = "SELECT $table2.*, $table10.*
				FROM $table2, $table10 
				WHERE ($table2.pic_id = $table10.pic_id 
				AND $table10.kat_id = '$ID'
				AND $table2.aktiv = '1' 
				$krit2) 
				ORDER BY $table2.DateTimeOriginal, $table2.ShutterCount, $table2.DateInsert";
				$pdf_statement = urlencode($statement);
				$result6_1 = mysql_query("$statement");
				//davon Ermittlung aller Bilder mit Geo-Referenzierung:
				$kml_statement = "SELECT $table2.*, $table10.* 
				FROM $table2, $table10 
				WHERE ($table2.pic_id = $table10.pic_id 
				AND $table10.kat_id = '$ID' 
				AND $table2.aktiv = '1'
				AND City <> 'Ortsbezeichnung'
				AND City <> '' 
				$krit2)";
				$kml_cod_statement = urlencode($kml_statement); // wird bei der Erzeugung der kml-Datei verwendet
				$result8 = mysql_query( "$kml_statement");
				if($result8)
				{
					$num8 = mysql_num_rows($result8);			//Gesamtzahl der gefundenen Bilder mit Geo-Referenzierung
				}
				else
				{
					$num8 = 0;
				}
				$result4 = mysql_query( "SELECT kategorie FROM $table4 WHERE kat_id='$ID'");
				$kategorie = mysql_result($result4, isset($i4), 'kategorie');
				IF(strlen($kategorie) > 17)
				{
					$kategorie = substr($kategorie,0,15)."...";
				}
				echo mysql_error();
				if($result6_1)
				{
					$num6_1 = mysql_num_rows($result6_1);
					//echo $num6_1;  	//Gesamtzahl der gefundenen Bilder mit Kategorie-Zuweisung
					if($num6_1 > 0)
					{
						$previewLayerHtml .= '
						<script language="javascript">
						var imageArray = new Array();
						self.getImageArray = function getImageArray()
						{
						  imageArray = [];
						';
						$previewLayerHtml .= generateImageArray($result6_1, $username, $uid, $sr);
						$previewLayerHtml .= '
						  return imageArray;
						}
						</script>
						';
						$text1 = "<div id='tooltip1'>".$num6_1." Bilder in der Kategorie \"".$kategorie."\"";
					}
				}
				else
				{
					$num6_1 = 0;
					echo "<p class='gross' style='color:green; text-align:center;'>Es gibt keine Bilder, die der gew&auml;hlten Kategorie zugewiesen wurden!</p>";
				}		
				break;
			}
		break;
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~	
		CASE 'geo':
		include 'functions/geo_functions.php';
		//Ermittlung, welches der Formulare abgesendet wurde:
		//echo $bewertung;
		//echo "Ort: ".$ort.", Radius2: ".$radius2.", Einheit2: ".$einheit2.", Mod: ".$mod.", Modus: ".$modus.", BaseFile: ".$base_file.", FormName: ".$form_name.", Bewertung: ".$bewertung."<BR>";
		SWITCH($form_name)
		{
			CASE 'geo_rech1':
			//Suche nach geogr. Koordinaten und Umkreis
			//Pruefung auf Plausibilitaet der eingegebenen Daten:
			
			$LONG = $long; //die unveraenderten Werte werden weiter unten benoetigt!
			$long = str_replace(',','.',$long);
			IF(!preg_match('/^([0-9]{1,3})([\.]{0,1})([0-9]{0,9})$/',$long) OR ($long > abs(180)))
			{
				echo "<p class='gross' style='color:red; text-align:center;'>Die Angabe der geogr. L&auml;nge ist falsch!<BR>(Erlaubte Werte liegen zwischen -180&#176; und +180&#176;)</P>";
				return;
			}
			
			$LAT = $lat; //die unveraenderten Werte werden weiter unten benoetigt!
			$lat = str_replace(',','.',$lat);
			IF(!preg_match('/^([0-9]{1,3})([\.]{0,1})([0-9]{0,9})$/',$lat) OR ($lat > abs(90)))
			{
				echo "<p class='gross' style='color:red; text-align:center;'>Die Angabe der geogr. Breite ist falsch!<BR>(Erlaubte Werte liegen zwischen -90&#176; und +90&#176;)</P>";
				return;
			}
			
			$ALT = $alt; //die unveraenderten Werte werden weiter unten benoetigt!
			$alt = round(str_replace(',','.',$alt),0);
			IF(!preg_match('/^([0-9]{1,4})$/',$alt) OR ($alt >8850))
			{
				echo "<p class='gross' style='color:red; text-align:center;'>Die Angabe der H&ouml;he ist falsch!<BR>(Erlaubte Werte sind kleiner als die H&ouml;he des Mount Everest)</P>";
				return;
			}
			
			$RADIUS1 = $radius1; //die unveraenderten Werte werden weiter unten benoetigt!
			$radius = round(($einheit1 * str_replace(',','.',$radius1)),0);
			IF(!preg_match('/^([0-9]{1,5})$/',$radius1) OR ($radius >50000))
			{
				echo "<p class='gross' style='color:red; text-align:center;'>Die Angabe des Umkreises ist falsch! (Erlaubte Werte sind bis max. 50 km)</P>";
				return;
			}
			ELSE
			{
			//echo htmlentities("uebergebene Werte: Breite: ".$lat.", Laenge: ".$long.", Hoehe: ".$alt.", Umkreis: ".$radius." m, Ort. ".$ort.", Umkreis: ".$radius2." ".$einheit2)."<BR>";
			}
			
			//annaehernde Berechnung des Toleranzfeldes aus dem Radius:
			//geogr. Breite: WInkeldifferenz je m Abweichung: 0,000008999280058�
			$diff_lat = 0.000008999280058;
			$delta_lat = $radius * $diff_lat;
			$lat_min = $lat - $delta_lat;
			$lat_max = $lat + $delta_lat;
			//echo "Breite: ".$lat.", min. Breite: ".$lat_min.", max. Breite: ".$lat_max."<BR>";
			
			//geogr. Laenge: hier ist dei Winkelaenderung / Entfernun von der geogr. Breite abhaengig:
			$delta_long = getDeltaLong($lat, $radius);
			$long_min = $long - $delta_long;
			$long_max = $long + $delta_long;
			//echo htmlentities("Laenge: ".$long.", min. Laenge: ".$long_min.", max. Laenge: ".$long_max)."<BR>";
			
			//qudratischer Auswahlbereich:
			$result5 = mysql_query( "SELECT * FROM $table2 WHERE (GPSLongitude > '$long_min' AND GPSLongitude < '$long_max') AND (GPSLatitude > '$lat_min' AND GPSLatitude < '$lat_max') AND (GPSAltitude > '$alt') AND aktiv = '1'");
			echo mysql_error();
			//Festlegung fuer Pruefung, ob Punkt im KREIS liegt:
			$long_mittel = $long;
			$lat_mittel = $lat;
			break;
			//###############################################################################################
			CASE 'geo_rech2':
			//Suche nach Ortsbezeichnung und Umkreis
			//Bestimmung, welche Koordinaten dem gewaehlten Ort entsprechen und Ermittlung des arithmetischen Mittelwertes als 'gemeinsamer Mittelpunkt':
			$ORT = $ort;
			$result10 = mysql_query( "SELECT * FROM $table2 WHERE City = \"$ort\" AND aktiv = '1'");
			$num10 = mysql_num_rows($result10);
			IF($num10 == '0')
			{
				echo "<p class='gross' style='color:red; text-align:center;'>Es wurden keine Bilder gefunden.<BR>Bitte pr&uuml;fen Sie Ihre Eingaben.</P>";
				return;
			}
			$lat = '';
			$long = '';
			FOR ($i10='0'; $i10<$num10; $i10++)
			{
				$lat = $lat + mysql_result($result10, $i10, 'GPSLatitude');
				$long = $long + mysql_result($result10, $i10, 'GPSLongitude');
			}
			//echo "Summe Breite: ".$lat.", Summe Laenge: ".$long."<BR>";
			$lat_mittel = $lat / $num10;
			$long_mittel = $long / $num10;
			
			//Plausibilitaetspruefung:
			$RADIUS2 = $radius2;
			$radius = round(($einheit2 * str_replace(',','.',$radius2)),0);
			//echo $radius;
			IF(!preg_match('/^([0-9]{1,5})$/',$radius2) OR ($radius >50000))
			{
				echo "<p class='gross' style='color:red; text-align:center;'>Die Angabe des Umkreises ist falsch! (Erlaubte Werte sind bis max. 50 km)</P>";
				return;
			}
			//annaehernde Berechnung des Toleranzfeldes aus dem Radius:
			//geogr. Breite: Winkeldifferenz je m Abweichung: 0,000008999280058°
			$diff_lat = 0.000008999280058;
			$delta_lat = $radius * $diff_lat;
			$lat_min = $lat_mittel - $delta_lat;
			$lat_max = $lat_mittel + $delta_lat;
			//echo "Breite: ".$lat_mittel.", min. Breite: ".$lat_min.", max. Breite: ".$lat_max."<BR>";
			
			//geogr. Laenge: hier ist dei Winkelaenderung / Entfernung von der geogr. Breite abhaengig:
			$delta_long = getDeltaLong($lat_mittel, $radius);
			$long_min = $long_mittel - $delta_long;
			$long_max = $long_mittel + $delta_long;
			
			//qudratischer Auswahlbereich:
			$result5 = mysql_query( "SELECT * FROM $table2 
			WHERE (GPSLongitude > '$long_min' AND GPSLongitude < '$long_max') 
			AND (GPSLatitude > '$lat_min' AND GPSLatitude < '$lat_max')
			AND aktiv = '1'");
			echo mysql_error();
			break;
		}
		$num5 = mysql_num_rows($result5);
		IF ($num5 == '0')
		{
			echo "<p class='gross' style='color:green; text-align:center;'>Es gibt keine Bilder, die den gew&auml;hlten Bedingungen entsprechen!</p>";
			return;
		}
		ELSE
		{
			//echo "Anzahl der gefundenen Bilder im Rechteck: ".$num5."<BR>";
			$p_i_arr = array();
			FOR($i5=0; $i5<$num5; $i5++)
			{
				$pic_id = mysql_result($result5, $i5, 'pic_id');
				$longitude = mysql_result($result5, $i5, 'GPSLongitude');
				$latitude = mysql_result($result5, $i5, 'GPSLatitude');
				//Pruefung, ob der Punkt in einem KREIS-Bereich um das Zentrum herum liegt:
				$inside = isInCircle($longitude, $long_mittel, $latitude, $lat_mittel, $radius);
				IF($inside == 'true')
				{
					//echo "Punkt ist im Kreis ".$pic_id."<BR>";
					SWITCH($bewertung)
					{
						CASE '6':
							$result9 = mysql_query( "SELECT * FROM $table2
							WHERE GPSLongitude = '$longitude' AND GPSLatitude = '$latitude' AND aktiv = '1'");
						break;
						
						default:
							$result9 = mysql_query( "SELECT * FROM $table2
							WHERE GPSLongitude = '$longitude' AND GPSLatitude = '$latitude' AND aktiv = '1'
							$krit2");
							echo mysql_error();
						break;
					}
					$num9 = mysql_num_rows($result9);
					IF($num9 > 0)
					{
						//die gueltigen pic_id's werden zur weiteren Verwendung in ein Array geschrieben:
						$p_i_arr[] = $pic_id;
					}
				}
				ELSE
				{
					//echo "Punkt ist N I C H T im Kreis ".$pic_id."<BR>";
				}
			}
			//zeitl. Sortierung der pic_id's:
			$arr_werte = count($p_i_arr);
			IF($arr_werte !== 0)
			{
				IF($arr_werte == 1)
				{
					$bed = "WHERE pic_id = $p_i_arr[0]";
				}
				ELSE
				{
					FOR($k=0; $k<$arr_werte; $k++)
					{
						IF($k==0)
						{
							$bed = "WHERE pic_id = $p_i_arr[$k]";
						}
						ELSE
						{
							$bed .= " OR pic_id = $p_i_arr[$k]";
						}
					}
				}
				//echo $bed;
				$result99 = mysql_query("SELECT * FROM $table2 $bed AND aktiv = '1' ORDER BY DateTimeOriginal, ShutterCount, DateInsert");
				@$num99 = mysql_num_rows($result99);
				FOR($i99=0; $i99<$num99; $i99++)
				{
					$pic_id_arr[] = mysql_result($result99, $i99, 'pic_id');
				}
			}
		}

		IF($arr_werte !== 0)
		{
			function generateGeoImageArray($pic_id_arr, $userName, $uID, $softwareRoot)
			{
				//$start1 = microtime();					//Startzeit-Variable zur Laufzeitermittlung
				//flush();
				include $softwareRoot.'/bin/share/db_connect1.php';
				$res = "";
				$num_pic = count($pic_id_arr);	//Gesamtzahl der gefundenen Bilder
				for ($imageArrayIndex = 0; $imageArrayIndex < $num_pic; $imageArrayIndex++)
				{		
					$res1 = mysql_query("SELECT * FROM $table2 WHERE pic_id = '$pic_id_arr[$imageArrayIndex]'");
					
					$fileName = mysql_result($res1, 0, 'FileName');
					$fileNamePrefix = str_replace('.jpg', '', $fileName);
					$ratio = (mysql_result($res1, 0, 'ExifImageWidth') / mysql_result($res1, 0, 'ExifImageHeight'));
					if (mysql_result($res1, 0, 'Orientation') >= '5')
					{
						$ratio = 1.0 / $ratio;
					}
					$downloadStatus = 0;
					//Erzeugung der Download-Icons:
					$Owner = mysql_result($res1, 0, 'Owner');		
					
					$check = fileExists($fileNamePrefix, $uID);
					IF($check > 0)
					{
						//Die Datei befindet sich im Download-Ordner des Users und wird mit Klick auf das Icon geloescht:
						$downloadStatus = 100;
					}
					ELSE
					{
						//Die Datei befindet sich nicht im Download-Ordner des Users und wird mit Klick auf das Icon dort hin kopiert:
						IF(($uID == $Owner AND hasPermission($uID, 'downloadmypics', $softwareRoot)) OR hasPermission($uID, 'downloadallpics', $softwareRoot))
						{
							IF(directDownload($uID, $softwareRoot))
							{
								$downloadStatus = 1;
							}
							ELSE
							{
								$downloadStatus = 2;
							}
						}
						ELSE
						{
							$downloadStatus = 0;
						}
					}
					$res .= 'imageArray.push({fileName: "'.$fileNamePrefix.'", ratio: '.$ratio.', id: "'.$fileNamePrefix.'", downloadStatus: '.$downloadStatus.', Owner: '.$Owner.'});
					';
				}
				return $res;
			}
					
			$previewLayerHtml .= '
			<script language="javascript">
			var imageArray = new Array();
			self.getImageArray = function getImageArray()
			{
				imageArray = [];';
				$previewLayerHtml .= generateGeoImageArray($pic_id_arr, $username, $uid, $sr);
				$previewLayerHtml .= '
				  return imageArray;
			}
			</script>';
			
			$statement = $pic_id_arr;
			$kml_statement = '';
			FOR($x=0; $x<count($pic_id_arr); $x++)
			{
				$kml_statement .= " ".$pic_id_arr[$x];
			}
			$kml_cod_statement = urlencode(substr($kml_statement, 1)); // wird bei der Erzeugung der kml-Datei verwendet
			$pdf_statement = urlencode(substr($kml_statement, 1));
			$num6_1 = count($pic_id_arr);
			echo "<div id='tooltip1'>Es wurde(n) ".$num6_1." Bild(er) gefunden.";
		}
		ELSE
		{
			$pic_id_arr = NULL;
			$pdf_statement = '';
			$num6_1 = 0;
		}
		break;
			
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~	

		CASE 'desc':
		//Bereinigung der Text-Eingabe-Felder:
		$desc1 = trim(strip_tags($desc1));
		$desc2 = trim(strip_tags($desc2));
		$desc3 = trim(strip_tags($desc3));
		$desc4 = trim(strip_tags($desc4));
		$desc5 = trim(strip_tags($desc5));
		
		//Montage des SQL-Statements:
		$statement = 'SELECT * FROM '.$table2.' WHERE aktiv = "1" AND (Caption_Abstract LIKE ';
		
		IF($desc1 !=='')
		{
			$statement .= '\'%'.$desc1.'%\'';
			//echo $statement;
			SWITCH($bed1)
			{
				CASE '0':
				break;
				
				CASE '1':
				IF($desc2 !=='')
				{
					$statement .= ' AND Caption_Abstract LIKE \'%'.$desc2.'%\'';
				}
				break;
				
				CASE '2':
				IF($desc2 !=='')
				{
					$statement .= ' OR Caption_Abstract LIKE \'%'.$desc2.'%\'';
				}
				break;
			}
		
			SWITCH($bed2)
			{
				CASE '0':
				break;
				
				CASE '1':
				IF($desc3 !=='')
				{
					$statement .= ' AND Caption_Abstract LIKE \'%'.$desc3.'%\'';
				}
				break;
				
				CASE '2':
				IF($desc3 !=='')
				{
					$statement .= ' OR Caption_Abstract LIKE \'%'.$desc3.'%\'';
				}
				break;
			}
		
			SWITCH($bed3)
			{
				CASE '0':
				break;
				
				CASE '1':
				IF($desc4 !=='')
				{
					$statement .= ' AND Caption_Abstract LIKE \'%'.$desc4.'%\'';
				}
				break;
				
				CASE '2':
				IF($desc4 !=='')
				{
					$statement .= ' OR Caption_Abstract LIKE \'%'.$desc4.'%\'';
				}
				break;
			}
		
			SWITCH($bed4)
			{
				CASE '0':
				break;
				
				CASE '1':
				IF($desc5 !=='')
				{
					$statement .= ' AND Caption_Abstract LIKE \'%'.$desc5.'%\'';
				}
				break;
				
				CASE '2':
				IF($desc5 !=='')
				{
					$statement .= ' OR Caption_Abstract LIKE \'%'.$desc5.'%\'';
				}
				break;
			}
			IF($bewertung !== '6')
			{
				//Bewertungskriterium wird in Vergleichsoperator und Wert zerlegt:
				//Groesser-Zeichen bedeutet: Der Notenwert ist hoeher, d.h die Note ist schlechter!
				$vgl_op = substr($bewertung,0,strlen($bewertung) - 1);
				IF($vgl_op == '<=')
				{
					$vgl_op = '>=';
				}
				ELSEIF($vgl_op == '>=')
				{
					$vgl_op = '<=';
				}
				$wert = substr($bewertung,-1);
				$krit2 = "AND $table2.note $vgl_op '$wert'";
				$stat_all = $statement." ".$krit2;
				$pdf_statement = urlencode($stat_all);
				$stat_ref = $stat_all.") 
				AND (City <>'Ortsbezeichnung' OR City <>'') 
				$krit2 
				ORDER BY DateTimeOriginal, ShutterCount, DateInsert";
			}
			ELSE
			{
				$stat_all = $statement;
				$pdf_statement = urlencode($stat_all);
				$stat_ref = $stat_all.") 
				AND (City <>'Ortsbezeichnung' OR City <>'') 
				ORDER BY DateTimeOriginal, ShutterCount, DateInsert";
			}
			$result6_1 = mysql_query( $stat_all.") ORDER BY DateTimeOriginal, ShutterCount, DateInsert");
			
			$num6_1 = mysql_num_rows($result6_1);  	//Gesamtzahl der gefundenen Bilder		
			$previewLayerHtml .= '
			<script language="javascript">
			var imageArray = new Array();
			self.getImageArray = function getImageArray()
			{
				  imageArray = [];';
				$previewLayerHtml .= generateImageArray($result6_1, $username, $uid, $sr);
				$previewLayerHtml .= '
				  return imageArray;
			}
			</script>';

			$kml_cod_statement = urlencode($stat_ref); // wird bei der Erzeugung der kml-Datei verwendet
			$result8 = mysql_query( $stat_ref);
			@$num8 = mysql_num_rows($result8);
			//echo "Gesamtanzahl der georeferenzierten Bilder: ".$num8." Treffer<BR>";
			//echo mysql_error();
			$krit1 = substr($stat_all,22).")";
			IF ($num6_1 == '0')
			{
				echo "<p class='gross' style='color:green; text-align:center;'>Es gibt keine Bilder, die den gew&auml;hlten Suchbegriffen entsprechen!</p>";
				return;
			}
			ELSE
			{
				$text1 = "<div id='tooltip1'>Es wurden ".$num6_1." Bilder gefunden.";
			}
		}
		ELSE
		{
			echo "<p class='gross' style='color:red; text-align:center;'>Legen Sie bitte erst ein Suchkriterium fest!</p>";
			$num6_1 = '-1';
			return;
		}
		$statement = "$stat_all) ORDER BY $table2.DateTimeOriginal, $table2.ShutterCount, $table2.DateInsert";
		$pdf_statement = urlencode($statement);
		break;
//###################################################################################	
		CASE 'exif':
			//Statement: finde in pictures alles, bei dem in dem gewaehlten Feld der gewuenschte Wert vorkommt, die Qualitaet der gewaehlten Qualitaet entspricht , sortiert nach dem Aufnahmedatum. 
			$zusatzwert1 = $zw1; //der nicht veraenderte Zusatzwert wird weiterhin benoetigt!
			//$zw1 = str_replace('*', '', $zw1);
			//echo $zw1."<BR>";;
			IF($bedingung1 == 'LIKE')
			{
				$krit1 = "WHERE ".$table2.".".$zusatz1." LIKE '%".$zw1."%'";
			}
			ELSE
			{
				$krit1 = "WHERE ".$table2.".".$zusatz1." ".$bedingung1." '".$zw1."'";
			}
			
			$statement = "SELECT DateTimeOriginal, ShutterCount, DateInsert, ExifImageHeight, ExifImageWidth, Orientation, note, pic_id, FileNameV, FileNameHQ, FileName, aktiv, $zusatz1 
			FROM $table2 
			$krit1 
			$krit2 
			AND aktiv = '1' 
			ORDER BY DateTimeOriginal, ShutterCount, DateInsert";
			$pdf_statement = urlencode($statement);
			$result6_1 = mysql_query( "SELECT DateTimeOriginal, ShutterCount, DateInsert, ExifImageHeight, ExifImageWidth, Orientation, note, pic_id, Owner, FileName, aktiv, $zusatz1 
			FROM $table2 
			$krit1 
			$krit2 
			AND aktiv = '1' 
			ORDER BY DateTimeOriginal, ShutterCount, DateInsert");
			echo mysql_error();
			// $result8 liefert die Anz. georef. Bilder entspr. Kriterium:
			$kml_statement = "SELECT * FROM $table2 
			$krit1 
			AND aktiv = '1'
			AND City <> 'Ortsbezeichnung'
			AND City <> '' 
			$krit2";
			$kml_cod_statement = urlencode($kml_statement); // wird bei der Erzeugung der kml-Datei verwendet
			$result8 = mysql_query( "$kml_statement");		
			@$num8 = mysql_num_rows($result8);				// Anzahl der geo-referenzierten Bilder
			$num6_1 = mysql_num_rows($result6_1);  			// Gesamtzahl der gefundenen Bilder

			$previewLayerHtml .= '
			<script language="javascript">
			
			var imageArray = new Array();
			
			self.getImageArray = function getImageArray()
			{
			  imageArray = [];
			';
			$previewLayerHtml .= generateImageArray($result6_1, $username, $uid, $sr);
			$previewLayerHtml .= '
			  return imageArray;
			}
			</script>
			';
			
			SWITCH ($num6_1)
			{
				CASE '0':
				$text1 = "";
				//echo "Pos.: ".$position."Jahr: ".$jahr.", Monat: ".$month_number.", mod: ".$mod.". Modus: ".$modus."BaseFile: ".$base_file.", Bewertung: ".$bewertung;
				break;
				
				CASE '1':
				$text1 = "<div id='tooltip1'>Es wurde ein Bild gefunden.";
				break;
				
				default:
				$text1 = "<div id='tooltip1'>Es wurden ".$num6_1." Bilder gefunden.";
				break;
			}
		break;
//#################################################################################################################
		CASE'expert_k':
			//echo "Expertensuche";
			
			IF(array_key_exists('kat', $_POST))
			{
				$kat = $_POST['kat'];
			}
			
			IF(array_key_exists('op', $_POST))
			{
				$op = $_POST['op'];
			}
			
			IF(array_key_exists('kat1', $_POST))
			{
				$kat1 = $_POST['kat1'];
			}
			
			IF(array_key_exists('op1', $_POST))
			{
				$op1 = $_POST['op1'];
			}
			
			IF(array_key_exists('kat2', $_POST))
			{
				$kat2 = $_POST['kat2'];
			}
			
			IF(array_key_exists('op2', $_POST))
			{
				$op2 = $_POST['op2'];
			}
			
			IF(array_key_exists('kat3', $_POST))
			{
				$kat3 = $_POST['kat3'];
			}
			
			IF(array_key_exists('op3', $_POST))
			{
				$op3 = $_POST['op3'];
			}
			
			IF(array_key_exists('kat4', $_POST))
			{
				$kat4 = $_POST['kat4'];
			}
			
			IF(array_key_exists('op4', $_POST))
			{
				$op4 = $_POST['op4'];
			}
			
			IF(array_key_exists('bewertung', $_POST))
			{
				$bewertung = $_POST['bewertung'];
			}
			
			//echo " Kat1: ".$kat1." / ".$op1." Kat2: ".$kat2." / ".$op2." Kat3: ".$kat3." / ".$op3." Kat4: ".$kat4." / ".$op4." Kat: ".$kat." / ".$op."<BR><BR>";
			
			$kriterium = $table10.".kat_id = ".$kat;
			
			IF($kat1 !== '')
			{
				$kriterium1 = $table10.".kat_id = ".$kat1."  ". $op1." ";
			}
			ELSE
			{
				$kriterium1 = ''; $kriterium2 = ''; $kriterium3 = ''; $kriterium4 = '';
			}
			
			IF($kat2 !== '')
			{
				$kriterium2 = $table10.".kat_id = ".$kat2."  ". $op2." ";
			}
			ELSE
			{
				$kriterium2 = ''; $kriterium3 = ''; $kriterium4 = '';
			}
			
			IF($kat3 !== '')
			{
				$kriterium3 = $table10.".kat_id = ".$kat3."  ". $op3." ";
			}
			ELSE
			{
				$kriterium3 = ''; $kriterium4 = '';
			}
			
			IF($kat4 !== '')
			{
				$kriterium4 = $table10.".kat_id = ".$kat4."  ". $op4." ";
			}
			ELSE
			{
				$kriterium4 = '';
			}
			//Ermittlung aller Bilder lt. Kategorien und Bewertung:
			$statement = "SELECT * FROM $table2 LEFT JOIN $table10 ON ($table2.pic_id=$table10.pic_id) LEFT JOIN $table14 ON ($table2.pic_id=$table14.pic_id) WHERE ($kriterium1$kriterium2$kriterium3$kriterium4$kriterium) $krit2 GROUP BY $table2.pic_id ORDER BY $table14.DateTimeOriginal, $table14.ShutterCount, $table14.DateInsert";
			//echo "alle Bilder: ".$statement."<BR>";
			$Stat_ment =  urlencode($statement);
			$result6_1 = mysql_query("$statement");
			echo mysql_error();
		
//#########################################################
/*				
			function generateImageArray($sqlResult, $userName, $userId, $softwareRoot)
			{
				$res = "";
				$sqlResultNumRows = mysql_num_rows($sqlResult);
				for ($imageArrayIndex = 0; $imageArrayIndex < $sqlResultNumRows; $imageArrayIndex++)
				{
					$fileName = mysql_result($sqlResult, $imageArrayIndex, 'FileName');
					$fileNamePrefix = str_replace('.jpg', '', $fileName);
					$ratio = (mysql_result($sqlResult, $imageArrayIndex, 'ExifImageWidth') / mysql_result($sqlResult, $imageArrayIndex, 'ExifImageHeight'));
					if (mysql_result($sqlResult, $imageArrayIndex, 'Orientation') >= '5')
					{
						$ratio = 1.0 / $ratio;
					}
					$downloadStatus = 0;
					//Erzeugung der Download-Icons:
					$Owner = mysql_result($sqlResult, $imageArrayIndex, 'Owner');
					$check = fileExists($fileName, $userName);
					IF($check > 0)
					{
						//Die Datei befindet sich im Download-Ordner des Users und wird mit Klick auf das Icon geloescht:
						$downloadStatus = 100;
					}
					ELSE
					{
						//Die Datei befindet sich nicht im Download-Ordner des Users und wird mit Klick auf das Icon dort hin kopiert:
						IF(($userId == $Owner AND hasPermission($userName, 'downloadmypics', $softwareRoot)) OR hasPermission($userName, 'downloadallpics', $softwareRoot))
						{
							IF(directDownload($userName, $softwareRoot))
							{
								$downloadStatus = 1;
							}
							ELSE
							{
								$downloadStatus = 2;
							}
						}
						ELSE
						{
							$downloadStatus = 0;
						}
					}
					$res .= 'imageArray.push({fileName: "'.$fileNamePrefix.'", ratio: '.$ratio.', id: "'.mysql_result($sqlResult, $imageArrayIndex, 'pic_id').'", downloadStatus: '.$downloadStatus.', Owner: '.$Owner.'});
					';
				}
				return $res;
			}
*/
			$num6_1 = mysql_num_rows($result6_1);  //Gesamtzahl der gefundenen Bilder
			$previewLayerHtml .= '
			<script language="javascript">
			var imageArray = new Array();
			self.getImageArray = function getImageArray()
			{
			  imageArray = [];
			';
			$previewLayerHtml .= generateImageArray($result6_1, $username, $user_id, $sr);
			$previewLayerHtml .= '
			  return imageArray;
			}
			</script>
			';
//########################################################
			//davon Ermittlung aller Bilder mit Geo-Referenzierung:
			$kml_statement = "SELECT $table2.*, $table10.* 
			FROM $table2, $table10
			WHERE ($table2.pic_id = $table10.pic_id 
			AND ($kriterium1$kriterium2$kriterium3$kriterium4$kriterium) 
			AND $table2.City <>'Ortsbezeichnung' 
			AND $table2.City <>''
			$krit2)";
	//		echo "Zeile 1871: ".$kml_statement."<BR>";
			$kml_cod_statement = urlencode($kml_statement); // wird bei der Erzeugung der kml-Datei verwendet
			$result8 = mysql_query( "$kml_statement");
	
			if($result6_1)
			{
				$num6_1 = mysql_num_rows($result6_1);
			}
			else
			{
				$num6_1 = 0;
			}
			
			if($result8)
			{
				$num8 = mysql_num_rows($result8);
			}
			else
			{
				$num8 = 0;
			}
			IF ($num6_1 == '0')
			{
				echo "<p class='gross' style='color:green; text-align:center;'>Es gibt keine Bilder, die der gew&auml;hlten Kategorie zugewiesen wurden!</p>";
				return;
			}
			ELSE
			{
				echo "<div id='tooltip1'>".$num6_1." Bilder wurden gefunden";
			}
		break;
		
	}

//###########    Bestimmung der Position innerhalb des Filmstreifens und Erzeugung des Textes 'Bilder X bis Y':    ###############
		
	$steps = ceil($num6_1 / $step);
	SWITCH($jump)
	{
		//$step: Laenge des Filmstreifens (6 Bilder)
		//$steps Anz. 'ganzer' Filmstreifen
		//$num6_1 Anzahl der darzustellenden Bilder
		//$position: alter Startpunkt
		//$pos: neuer Startpunkt
		//$anf: erstes Bild im Bildstreifen
		//$end: letztes Bild im Bidstreifen
		
		CASE '-2':
		$position = '0';
		$pos = '0';
		$anf = '1';
		$end = $step;
		break;
		
		CASE '-1':
		IF($position > '0')
		{
			$position --;
		}
		$pos = $step * $position;
		$anf = ($position * $step + 1);
		$end = ($position * $step + $step);
		break;
		
		CASE '0':
		$pos = '0';
		$anf = '1';
		$end = $step;
		break;
		
		CASE '1':
		IF($position < ($steps - 1))
		{
			$position ++;
		}
		$pos = $step * $position;
		
		IF((($position + 1) * $step) > $num6_1)
		{
			$end = $num6_1;
		}
		ELSE
		{
			$end = (($position + 1) * $step);
		}
		$anf = ($position * $step + 1);
		
		break;
		
		CASE '2':
		$position = $steps - 1;
		$pos = $num6_1 - $step;
		$anf = ($pos + 1);
		$end = ($num6_1);
		break;
		
		CASE '99':
		$pos = $position * $step;
		$anf = ($position * $step + 1);
		$end = ($position * $step + $step);
		
		IF((($position + 1) * $step) > $num6_1)
		{
			$end = $num6_1;
		}
		ELSE
		{
			$end = (($position + 1) * $step);
		}
		break;
	}
	
	IF($anf < '10')
	{
		$anf = '00'.$anf;
	}
	ELSEIF($anf < '100' AND $anf > '9')
	{
		$anf = '0'.$anf;
	}
	IF($end < '10')
	{
		$end = '00'.$end;
	}
	ELSEIF($end < '100' AND $end > '9')
	{
		$end = '0'.$end;
	}
	$bereich = "Bilder ".$anf." bis ".$end;
	//echo $bereich."<BR>";
			
#########    Erzeugung der vollst. 'Filmstreifen-&Uuml;berschrift'     #####################################################

	SWITCH($mod)
	{
		CASE 'zeit':
		$action_2 = "getTimePreview(\"$j\",\"$m\",\"$t\",0,\"$mod\",\"$modus\",\"$base_file\",\"$bewertung\",\"$position\",-2)";
		$action_1 = "getTimePreview(\"$j\",\"$m\",\"$t\",0,\"$mod\",\"$modus\",\"$base_file\",\"$bewertung\",\"$position\",-1)";
		$action1 = "getTimePreview(\"$j\",\"$m\",\"$t\",0,\"$mod\",\"$modus\",\"$base_file\",\"$bewertung\",\"$position\",1)";
		$action2 = "getTimePreview(\"$j\",\"$m\",\"$t\",0,\"$mod\",\"$modus\",\"$base_file\",\"$bewertung\",\"$position\",2)";
		$filmstreifenUpdateFunc = "getTimePreview(\"$j\",\"$m\",\"$t\",0,\"$mod\",\"$modus\",\"$base_file\",\"$bewertung\",";
		break;
		
		CASE 'kat':
		$KAT_ID = $kat_id;
		$kat_id = $ID;
		$action_2 = "getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",0,\"$position\",-2)";
		$action_1 = "getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",0,\"$position\",-1)";
		$action1 = "getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",0,\"$position\",1)";
		$action2 = "getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",0,\"$position\",2)";
		$filmstreifenUpdateFunc = "getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",0,";
		break;
		
		CASE 'desc':
		$action_2 = "getDescPreview1(\"$desc1\", \"$bed1\", \"$desc2\", \"$bed2\", \"$desc3\", \"$bed3\", \"$desc4\", \"$bed4\", \"$desc5\", \"$mod\", \"$modus\", \"$base_file\", \"$bewertung\", \"$position\", -2)";
		$action_1 = "getDescPreview1(\"$desc1\", \"$bed1\", \"$desc2\", \"$bed2\", \"$desc3\", \"$bed3\", \"$desc4\", \"$bed4\", \"$desc5\", \"$mod\", \"$modus\", \"$base_file\", \"$bewertung\", \"$position\", -1)";
		$action1 = "getDescPreview1(\"$desc1\", \"$bed1\", \"$desc2\", \"$bed2\", \"$desc3\", \"$bed3\", \"$desc4\", \"$bed4\", \"$desc5\", \"$mod\", \"$modus\", \"$base_file\", \"$bewertung\", \"$position\", 1)";		
		$action2 = "getDescPreview1(\"$desc1\", \"$bed1\", \"$desc2\", \"$bed2\", \"$desc3\", \"$bed3\", \"$desc4\", \"$bed4\", \"$desc5\", \"$mod\", \"$modus\", \"$base_file\", \"$bewertung\", \"$position\", 2)";
		$filmstreifenUpdateFunc = "getDescPreview1(\"$desc1\", \"$bed1\", \"$desc2\", \"$bed2\", \"$desc3\", \"$bed3\", \"$desc4\", \"$bed4\", \"$desc5\", \"$mod\", \"$modus\", \"$base_file\", \"$bewertung\", ";
		break;
		
		CASE 'geo':
		IF($form_name == 'geo_rech1')
		{
			$action_2 = "getGeoPreview1(\"$LONG\", \"$LAT\", \"$ALT\", \"$RADIUS1\", \"$einheit1\", \"$mod\", \"$modus\", \"$base_file\", \"geo_rech1\", \"$bewertung\",\"$position\",-2)";
			$action_1 = "getGeoPreview1(\"$LONG\", \"$LAT\", \"$ALT\", \"$RADIUS1\", \"$einheit1\", \"$mod\", \"$modus\", \"$base_file\", \"geo_rech1\", \"$bewertung\",\"$position\",-1)";
			$action1 = "getGeoPreview1(\"$LONG\", \"$LAT\", \"$ALT\", \"$RADIUS1\", \"$einheit1\", \"$mod\", \"$modus\", \"$base_file\", \"geo_rech1\", \"$bewertung\",\"$position\",1)";
			$action2 = "getGeoPreview1(\"$LONG\", \"$LAT\", \"$ALT\", \"$RADIUS1\", \"$einheit1\", \"$mod\", \"$modus\", \"$base_file\", \"geo_rech1\", \"$bewertung\",\"$position\",2)";
			$filmstreifenUpdateFunc = "getGeoPreview1(\"$LONG\", \"$LAT\", \"$ALT\", \"$RADIUS1\", \"$einheit1\", \"$mod\", \"$modus\", \"$base_file\", \"geo_rech1\", \"$bewertung\",";
		}
		ELSEIF($form_name == 'geo_rech2')
		{
			$action_2 = "getGeoPreview2(\"$ORT\", \"$RADIUS2\", \"$einheit2\", \"$mod\", \"$modus\", \"$base_file\", \"geo_rech2\", \"$bewertung\",\"$position\",-2)";
			$action_1 = "getGeoPreview2(\"$ORT\", \"$RADIUS2\", \"$einheit2\", \"$mod\", \"$modus\", \"$base_file\", \"geo_rech2\", \"$bewertung\",\"$position\",-1)";
			$action1 = "getGeoPreview2(\"$ORT\", \"$RADIUS2\", \"$einheit2\", \"$mod\", \"$modus\", \"$base_file\", \"geo_rech2\", \"$bewertung\",\"$position\",1)";
			$action2 = "getGeoPreview2(\"$ORT\", \"$RADIUS2\", \"$einheit2\", \"$mod\", \"$modus\", \"$base_file\", \"geo_rech2\", \"$bewertung\",\"$position\",2)";
			$filmstreifenUpdateFunc = "getGeoPreview2(\"$ORT\", \"$RADIUS2\", \"$einheit2\", \"$mod\", \"$modus\", \"$base_file\", \"geo_rech2\", \"$bewertung\",";
		}
		$num8 = count($pic_id_arr);
		$num6_1 = $num8;
		break;
		
		CASE 'exif':
		$action_2 = "getExifPreview(\"$zusatz1\", \"$bedingung1\", \"$zusatzwert1\", \"$mod\", \"$modus\", \"$base_file\", \"$bewertung\",\"$position\",-2)";
		$action_1 = "getExifPreview(\"$zusatz1\", \"$bedingung1\", \"$zusatzwert1\", \"$mod\", \"$modus\", \"$base_file\", \"$bewertung\",\"$position\",-1)";
		$action1 = "getExifPreview(\"$zusatz1\", \"$bedingung1\", \"$zusatzwert1\", \"$mod\", \"$modus\", \"$base_file\", \"$bewertung\",\"$position\",1)";
		$action2 = "getExifPreview(\"$zusatz1\", \"$bedingung1\", \"$zusatzwert1\", \"$mod\", \"$modus\", \"$base_file\", \"$bewertung\",\"$position\",2)";
		$filmstreifenUpdateFunc = "getExifPreview(\"$zusatz1\", \"$bedingung1\", \"$zusatzwert1\", \"$mod\", \"$modus\", \"$base_file\", \"$bewertung\",";
		break;
		
		CASE 'expert_k':
		$action_2 = "getExpSearchPreview(\"$kat\", \"$op\", \"$kat1\", \"$op1\", \"$kat2\", \"$op2\", \"$kat3\", \"$op3\", \"$kat4\", \"$op4\", \"$mod\",\"$modus\",\"$base_file\",\"$bewertung\",\"$position\",-2)";
		$action_1 = "getExpSearchPreview(\"$kat\", \"$op\", \"$kat1\", \"$op1\", \"$kat2\", \"$op2\", \"$kat3\", \"$op3\", \"$kat4\", \"$op4\", \"$mod\",\"$modus\",\"$base_file\",\"$bewertung\",\"$position\",-1)";
		$action1 = "getExpSearchPreview(\"$kat\", \"$op\", \"$kat1\", \"$op1\", \"$kat2\", \"$op2\", \"$kat3\", \"$op3\", \"$kat4\", \"$op4\", \"$mod\",\"$modus\",\"$base_file\",\"$bewertung\",\"$position\",1)";
		$action2 = "getExpSearchPreview(\"$kat\", \"$op\", \"$kat1\", \"$op1\", \"$kat2\", \"$op2\", \"$kat3\", \"$op3\", \"$kat4\", \"$op4\", \"$mod\",\"$modus\",\"$base_file\",\"$bewertung\",\"$position\",2)";
		$filmstreifenUpdateFunc = "getExpSearchPreview(\"$kat\", \"$op\", \"$kat1\", \"$op1\", \"$kat2\", \"$op2\", \"$kat3\", \"$op3\", \"$kat4\", \"$op4\" ,\"$mod\",\"$modus\",\"$base_file\",\"$bewertung\",";	
		break;
	}
	//Link zum pdf-Druck (Ausdruck bis max. 1000 Bilder!)
	IF($num6_1 < '1001' AND $N == '') //$N == '' bedeutet: fuer Neuzugaenge wird keine Galerie angeboten
	{
		$pdf_link = "&#160;&#160;&#160;<span id='pdf_icon'>Galerie vorbereiten: <a href='#' style='cursor:pointer;' onClick='createPdfFile(\"$pdf_statement\",\"$sr\",\"$mod\",\"$num6_1\")'><img src=\"$inst_path/pic2base/bin/share/images/acroread_grey.png\" width=\"12\" height=\"12\" border=\"0\"  title='pdf-Datei erzeugen' /></a><span>";
	}
	ELSE
	{
		$pdf_link = '';
	}

	IF(isset($num8) AND $num8 > '0')
	{
		@$zusatz = "
			(".$num8." geo-ref.; <span id='ge_icon'>Anzeige vorbereiten: <a href='#' style='cursor:pointer;' onClick='createKmlFile(\"$kml_cod_statement\",\"$sr\",\"$mod\",\"$long_mittel\",\"$lat_mittel\",\"$radius\")'><img src=\"$inst_path/pic2base/bin/share/images/googleearth-icon_grey.png\" width=\"12\" height=\"12\" border=\"0\"  title='kml-Datei erzeugen'/><span>
			<strong>Zur Anzeige der Fotos in GoogleEarth ist es erforderlich, da&#223; GoogleEarth auf Ihrem Rechner installiert ist.</strong><br />
			<br />
			Ein kostenfreier Download steht unter http://earth.google.de zur Verf&uuml;gung.
			</a></SPAN> )</span>";

		IF($num6_1 > $step)
		{
			$zusatz .= "&#0160;&#0160;&#0160;
			<SPAN style='cursor:pointer;' title='Zum ersten Filmstreifen' onClick='$action_2'><img src=\"$inst_path/pic2base/bin/share/images/anfang.gif\" width=\"12\" height=\"12\" /></SPAN>
			<SPAN style='cursor:pointer;' title='einen Filmstreifen zur&uuml;ck' onClick='$action_1'><img src=\"$inst_path/pic2base/bin/share/images/zurueck.gif\" width=\"12\" height=\"12\" /></SPAN> $bereich 
			<SPAN style='cursor:pointer;' title='einen Filmstreifen vor' onClick='$action1'><img src=\"$inst_path/pic2base/bin/share/images/vor.gif\" width=\"12\" height=\"12\" /></SPAN>
			<SPAN style='cursor:pointer;' title='zum letzten Filmstreifen' onClick='$action2'><img src=\"$inst_path/pic2base/bin/share/images/ende.gif\" width=\"12\" height=\"12\" /></SPAN>".$pdf_link."</div>";
		}
		ELSE
		{
			$zusatz .= $pdf_link."</div>";
		}
	}
	ELSE
	{
		IF($num6_1 > $step)
		{
			$zusatz = "&#0160;&#0160;&#0160<SPAN style='cursor:pointer;' title='Zum ersten Filmstreifen' onClick='$action_2'><img src=\"$inst_path/pic2base/bin/share/images/anfang.gif\" width=\"12\" height=\"12\" /></SPAN>
			<SPAN style='cursor:pointer;' title='einen Filmstreifen zur&uuml;ck' onClick='$action_1'><img src=\"$inst_path/pic2base/bin/share/images/zurueck.gif\" width=\"12\" height=\"12\" /></SPAN> $bereich 
			<SPAN style='cursor:pointer;' title='einen Filmstreifen vor' onClick='$action1'><img src=\"$inst_path/pic2base/bin/share/images/vor.gif\" width=\"12\" height=\"12\" /></SPAN>
			<SPAN style='cursor:pointer;' title='zum letzten Filmstreifen' onClick='$action2'><img src=\"$inst_path/pic2base/bin/share/images/ende.gif\" width=\"12\" height=\"12\" /></SPAN>".$pdf_link."</div>";
		}
		ELSEIF($num6_1 > '0')
		{
			$zusatz .= $pdf_link."</div>";
		}
	}
	$zusatz .= "<script language='javascript'>
	self.gotoFilmstreifenPosition = function gotoFilmstreifenPosition(newPosition, picId)
	{
		currImageState.picId = picId;
		".$filmstreifenUpdateFunc." (Math.floor((newPosition) / 6) + 0), 99);
	}
	</script>";
	
	//echo $text1.$zusatz;
	
	IF($num6_1 == NULL AND $mod !== 'kat')
	{
		echo "<p class='gross' style='color:red; text-align:center;'>Es wurden keine Bilder gefunden.<BR>Bitte pr&uuml;fen Sie Ihre Eingaben.</P>";
	}

//#############   Aufbau des Statements fuer die limitierte Abfrage:   ###########################################################
		
	IF($num6_1 > $step)
	{
		$krit3 = "LIMIT $pos,$step";
	}
	ELSE
	{
		$krit3 = '';
	}
	
	SWITCH($mod)
	{
		CASE 'zeit':
			$result6 = mysql_query( "SELECT pic_id, FileName, FileNameHQ, FileNameV, Owner, DateTimeOriginal, ShutterCount, DateInsert, FileSize, aktiv 
			FROM $table2
			$krit1 
			$krit2 
			AND aktiv = '1'
			ORDER BY DateTimeOriginal, ShutterCount, DateInsert $krit3");
		break;
		
		CASE 'kat':
		IF($N>='0')
		{
			$statement = "SELECT *	FROM $table2 
			WHERE has_kat = '0'
			AND aktiv = '1' $krit2 
			ORDER BY DateTimeOriginal, ShutterCount, DateInsert $krit3";
//			echo "<BR>".$statement."<BR>";
			$result6 = mysql_query($statement);
		}
		else
		{
			$statement = "SELECT $table2.*, $table10.* 
			FROM $table2, $table10 
			WHERE ($table2.pic_id = $table10.pic_id 
			AND $table10.kat_id = '$ID'
			AND $table2.aktiv = '1' $krit2) 
			ORDER BY $table2.DateTimeOriginal, $table2.ShutterCount, $table2.DateInsert $krit3";
//			echo "<BR>".$statement."<BR>";
			$result6 = mysql_query($statement);
		}
		break;
		
		CASE 'desc':
			$stat = "SELECT * FROM $table2 $krit1 $krit2 AND aktiv = '1' ORDER BY DateTimeOriginal, ShutterCount, DateInsert $krit3";
//			echo "Zeile 2138: ".$stat."<BR>Krit.1: ".$krit1.", Krit2: ".$krit2.", Krit 3: ".$krit3."<BR>";
			$result6 = mysql_query( "SELECT * FROM $table2 
			$krit1 
			$krit2 
			AND aktiv = '1' 
			ORDER BY DateTimeOriginal, ShutterCount, DateInsert 
			$krit3");
		break;
		
		CASE 'geo':
			//echo $krit3;
			IF(count($pic_id_arr) > '1')
			{
				FOR($z1='0'; $z1<count($pic_id_arr); $z1++)
				{
					IF($z1 == '0')
					{
						$krit1 = "WHERE $table2.pic_id = '$pic_id_arr[$z1]'";
					}
					ELSE
					{
						$krit1 .= " OR $table2.pic_id = '$pic_id_arr[$z1]'";
					}
				}
			}
			ELSE
			{
				$krit1 = "WHERE $table2.pic_id = '$pic_id_arr[0]'";
			}
			//echo $krit1."  -  ".$krit2."<BR>";
			$result6 = mysql_query( "SELECT pic_id, FileName, FileNameHQ, FileNameV, Owner, note, DateTimeOriginal, ShutterCount, DateInsert, FileSize 
			FROM $table2
			$krit1 
			$krit2 
			ORDER BY DateTimeOriginal, ShutterCount, DateInsert 
			$krit3");
			echo mysql_error();
		break;
		
		CASE 'exif':
			$result6 = mysql_query( "SELECT pic_id, FileName, FileNameHQ, FileNameV, Owner, DateTimeOriginal, ShutterCount, DateInsert, FileSize, aktiv 
			FROM $table2 
			$krit1 
			$krit2 
			AND aktiv = '1' 
			ORDER BY DateTimeOriginal, ShutterCount, DateInsert 
			$krit3");
			echo mysql_error();
		break;
		
		CASE 'expert_k':
			$result6 = mysql_query($statement." ".$krit3);
			echo mysql_error();	
		break;
		
	}
	$num6 = mysql_num_rows($result6); 
		
//#############   Aufbau des Filmstreifens:   #############################################################################################
	//$N: Anz. der Bilder ohne Kat.-Zuweisung
	IF ($N >= '0' AND $mod == 'kat')
	{
		//Anzeige der Bilder ohne Kategorie-Zuweisung:
		if ( !isset($text1) )
		{
			$text1='';
		}
		echo $text1.$zusatz."	
		<TABLE border='0' align='center' width='780px'>
		<TR>";
		$rest = $step - $num6; 	//wenn weniger als $step Bilder gefunden wurden: Anzahl der aufzufuellenden Zellen
		$j = '0';				//Zaehlvariable fuer den Array-Index der Download-Icons

		FOR($i6='0'; $i6<$num6; $i6++)
		{
			//echo $pic_id."<BR>";
			$pic_id = mysql_result($result6, $i6, 'pic_id');
			$FileName = mysql_result($result6, isset($i6), 'FileName');
			$FileNameHQ = mysql_result($result6, isset($i6), 'FileNameHQ');
			$FileNameV = mysql_result($result6, isset($i6), 'FileNameV');
			$FileSize = mysql_result($result6, isset($i6), 'FileSize');
			//abgeleitete Groessen:
			IF ($FileNameV == '')
			{
				$FileNameV = 'no_preview.jpg';
			}
			ELSE
			{
				@$parameter_v=getimagesize($sr.'/images/vorschau/hq-preview/'.$FileNameHQ);
			}
			$size = ceil($FileSize / 1024);
			$breite = $parameter_v[0];
			$hoehe = $parameter_v[1];
			$breite_v = $breite;
			$hoehe_v = $hoehe;
			IF ($breite == 0 OR $hoehe == 0)
			{
				$breite_v = 800;
				$hoehe_v = 600;
			}
			ELSE
			{
				$hoehe_neu = $fs_hoehe;
				$breite_neu = $fs_hoehe * $breite / $hoehe;
			}
			$kat_id = 1;	//Erfordernis kontrollieren!!  *********************************************************
			echo "<TD align='center' colspan='1' width = '130px' style= 'padding-top:2px; padding-bottom:2px;'>
					<div id='pic$pic_id'>";
					getHQPreviewNow($pic_id, $hoehe_neu, $breite_neu, $base_file, isset($kat_id), $mod, $form_name);
			echo "</div>
			</TD>";
		
			//Erzeugung der Download-Icons:
			$Owner = mysql_result($result6, $i6, 'Owner');
			$check = fileExists($FileName, $uid);
			IF($check > '0')
			{
				//Die Datei befindet sich im Download-Ordner des Users und wird mit Klick auf das Icon geloescht:
				$icon[$j] = "<TD align='center' width='43'>
				<div id='box$pic_id'>
				<SPAN style='cursor:pointer;' onClick='delPicture(\"$FileName\",\"$username\",\"$pic_id\")'>
				<img src='$inst_path/pic2base/bin/share/images/selected.gif' width='12' height='12' hspace='0' vspace='0'/>
				</SPAN>	
				</div>
				</TD>";
			}
			ELSE
			{
				//Die Datei befindet sich nicht im Download-Ordner des Users und wird mit Klick auf das Icon dort hin kopiert:
				IF(($user_id == $Owner AND hasPermission($uid, 'downloadmypics', $sr)) OR hasPermission($uid, 'downloadallpics', $sr))
				{
					IF(directDownload($uid, $sr))
					{
						IF(hasPermission($uid, 'rotatepicture', $sr))
						{
							$icon[$i6] = "
							<TD align='center' width='43'>
							<div id='box$pic_id'>
							
							<SPAN style='cursor:pointer;' onClick='rotPrevPic(\"8\", \"$FileNameV\", \"$pic_id\", \"$fs_hoehe\")'>
							<img src=\"$inst_path/pic2base/bin/share/images/90-ccw.gif\" width=\"8\" height=\"8\" style='margin-right:10px;' title='Vorschaubild 90&#176; links drehen' /></span>
							
							<SPAN style='cursor:pointer;' onClick='window.open(\"$inst_path/pic2base/bin/share/download_picture.php?FileName=$FileName&pic_id=$pic_id\")'>
							<img src='$inst_path/pic2base/bin/share/images/download.gif' width='12' height='12' hspace='0' vspace='0' title='Bild direkt herunterladen'/></SPAN>
							
							<SPAN style='cursor:pointer;' onClick='rotPrevPic(\"6\", \"$FileNameV\", \"$pic_id\", \"$fs_hoehe\")'>
							<img src=\"$inst_path/pic2base/bin/share/images/90-cw.gif\" width=\"8\" height=\"8\" style='margin-left:10px;' title='Vorschaubild 90&#176; rechts drehen' /></span>
							
							</div>
							</TD>";
						}
						ELSE
						{
							$icon[$i6] = "
							<TD align='center' width='43'>
							<div id='box$pic_id'>

							<SPAN style='cursor:pointer;' onClick='window.open(\"$inst_path/pic2base/bin/share/download_picture.php?FileName=$FileName&pic_id=$pic_id\")'>
							<img src='$inst_path/pic2base/bin/share/images/download.gif' width='12' height='12' hspace='0' vspace='0' title='Bild direkt herunterladen'/></SPAN>
							
							</div>
							</TD>";
						}
					}
					ELSE
					{
						IF(hasPermission($uid, 'rotatepicture', $sr))
						{
							$icon[$i6] = "
							<TD align='center' width='43'>
							<div id='box$pic_id'>
							
							<SPAN style='cursor:pointer;' onClick='rotPrevPic(\"8\", \"$FileNameV\", \"$pic_id\", \"$fs_hoehe\")'><img src=\"$inst_path/pic2base/bin/share/images/90-ccw.gif\" width=\"8\" height=\"8\" style='margin-right:5px;' title='Vorschaubild 90&#176; links drehen' /></span>
							<SPAN style='cursor:pointer;' onClick='copyPicture(\"$FileName\",\"$uid\",\"$pic_id\")'><img src='$inst_path/pic2base/bin/share/images/download.gif' width='12' height='12' hspace='0' vspace='0' title='Bild in den FTP-Download-Ordner kopieren'/></SPAN>
							<SPAN style='cursor:pointer;' onClick='rotPrevPic(\"6\", \"$FileNameV\", \"$pic_id\", \"$fs_hoehe\")'><img src=\"$inst_path/pic2base/bin/share/images/90-cw.gif\" width=\"8\" height=\"8\" style='margin-left:5px;' title='Vorschaubild 90&#176; rechts drehen' /></span>
							
							</div>	
							</TD>";
						}
						ELSE
						{
							$icon[$i6] = "
							<TD align='center' width='43'>
							<div id='box$pic_id'>
							
							<SPAN style='cursor:pointer;' onClick='copyPicture(\"$FileName\",\"$uid\",\"$pic_id\")'>
							<img src='$inst_path/pic2base/bin/share/images/download.gif' width='12' height='12' hspace='0' vspace='0' title='Bild in den FTP-Download-Ordner kopieren'/></SPAN>
							
							</div>	
							</TD>";
						}
					}
				}
				ELSE
				{
					$icon[$j] = "<TD align='center' width='43'><BR></TD>";
				}
			}
			$j++;
		}
		//Leer-Raum mit Leer-Zellen auffuellen (Zelle mit Dummy-Bild zur Streckung gefuellt), wenn Bilder gefunden wurden:
		IF($num6 > '0')
		{
			FOR($i_r = '0'; $i_r<$rest; $i_r++)
			{
				echo "<TD align='center' colspan='1'><img src='$inst_path/pic2base/bin/share/images/no_pic.gif' width='124' height='10' /></TD>";
			}
		}
		
		echo "	
		</TR>
		<div id='checkboxen'>
		<TR>";
		IF( isset($icon) AND count($icon) > '0' )
		{
			FOREACH($icon AS $ICON)
			{
				echo $ICON;
			}
		}
		echo "
		</TR>
		</div>";
		
		//Tabellenzeile zur Anzeige des gewaehlten, 'aktiven' Bildes:
		echo "<TR>";
		FOR ($i6=0; $i6<$num6; $i6++)
		{
			$pic_id = mysql_result($result6, $i6, 'pic_id');
			echo "<TD align='center'><div id = 'picture_$pic_id' style='display:none';><img src='$inst_path/pic2base/bin/share/images/green.gif' width=\"50\" height=\"2\" /></div></TD>";
		}
		FOR($i_re = '0'; $i_re < $rest; $i_re++)
		{
			echo "<TD align='left' width='43'></TD>";
		}
		echo "</TR>";
		//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		
		echo "<TR><TD colspan='6'></TD></TR>";	//leere Slider-Zeile
		echo "
		</TABLE>";
	}
	ELSE //################################################################################################################
	{
		//normale Anzeige der Bilder lt. Suchkriterium:
		IF(!isset($text1))
		{
			$text1 = '';
		}
		echo $text1.$zusatz."	
		<TABLE border='0' align='center' width='780px'>
		<TR style='background-color:RGB(120,120,120,);' >";
		$rest = $step - $num6; //wenn weniger als $step Bilder gefunden wurden: Anzahl der aufzufuellenden Zellen
		//echo $rest;
		FOR ($i6=0; $i6<$num6; $i6++)
		{
			$pic_id = mysql_result($result6, $i6, 'pic_id');
			$FileName = mysql_result($result6, $i6, 'FileName');
			$FileNameHQ = mysql_result($result6, $i6, 'FileNameHQ');
			$FileNameV = mysql_result($result6, $i6, 'FileNameV');
			$FileSize = mysql_result($result6, $i6, 'FileSize');
			
			//abgeleitete Groessen:
			IF ($FileNameV == '')
			{
				$FileNameV = 'no_preview.jpg';
			}
			ELSE
			{
				@$parameter_v=getimagesize($sr.'/images/vorschau/hq-preview/'.$FileNameHQ);
			}
			$size = round($FileSize / 1024);		
			$breite = $parameter_v[0];
			$hoehe = $parameter_v[1];
			$breite_v = $breite;
			$hoehe_v = $hoehe;
			IF ($breite == 0 OR $hoehe == 0)
			{
				$breite_v = 800;
				$hoehe_v = 600;
			}
			ELSE
			{
				$hoehe_neu = $fs_hoehe;
				$breite_neu = number_format(($fs_hoehe * $breite / $hoehe),0,',','.');
			}
			//echo "Breite: ".$breite_neu.", H&ouml;he: ".$hoehe_neu;
			echo "<TD align='center' colspan='1' width = '130px' style= 'padding-top:2px; padding-bottom:2px;'>
			<div id='pic$pic_id'>";
			getHQPreviewNow($pic_id, $hoehe_neu, $breite_neu, $base_file, isset($kat_id), $mod, $form_name);
			echo "
			</div>
			</TD>";
			
			//Erzeugung der Download-Icons:
			$Owner = mysql_result($result6, $i6, 'Owner');
			//Pruefung, ob diese Datei bereits im Download-Ordner des angemeldeten Users liegt. Wenn nicht: Download-Icon mit link zur Kopier-Routine; wenn ja: selected-Icon mit Link zur Loesch-Routine:
			$check = fileExists($FileName, $uid);
			IF($check > '0')
			{
				//Die Datei befindet sich im Download-Ordner des Users und wird mit Klick auf das Icon geloescht:
				$icon[$i6] = "
				<TD align='center' width='43'>
				<div id='box$pic_id'>
				<SPAN style='cursor:pointer;' onClick='delPicture(\"$FileName\",\"$uid\",\"$pic_id\")'>
				<img src='$inst_path/pic2base/bin/share/images/selected.gif' width='12' height='12' hspace='0' vspace='0' title='Bild aus dem FTP-Download-Ordner entfernen' /></SPAN>	
				</div>
				</TD>";
			}
			ELSE
			{
				//Die Datei befindet sich nicht im Download-Ordner des Users und wird mit Klick auf das Icon dort hin kopiert:
				IF(($user_id == $Owner AND hasPermission($uid, 'downloadmypics', $sr)) OR hasPermission($uid, 'downloadallpics', $sr))
				{
					IF(directDownload($uid, $sr))
					{
						IF(hasPermission($uid, 'rotatepicture', $sr))
						{
							$icon[$i6] = "
							<TD align='center' width='43'>
							<div id='box$pic_id'>
							
							<SPAN style='cursor:pointer;' onClick='rotPrevPic(\"8\", \"$FileNameV\", \"$pic_id\", \"$fs_hoehe\")'>
							<img src=\"$inst_path/pic2base/bin/share/images/90-ccw.gif\" width=\"8\" height=\"8\" style='margin-right:10px;' title='Vorschaubild 90&#176; links drehen' /></span>
							
							<SPAN style='cursor:pointer;' onClick='window.open(\"$inst_path/pic2base/bin/share/download_picture.php?FileName=$FileName&pic_id=$pic_id\")'>
							<img src='$inst_path/pic2base/bin/share/images/download.gif' width='12' height='12' hspace='0' vspace='0' title='Bild direkt herunterladen'/></SPAN>
							
							<SPAN style='cursor:pointer;' onClick='rotPrevPic(\"6\", \"$FileNameV\", \"$pic_id\", \"$fs_hoehe\")'>
							<img src=\"$inst_path/pic2base/bin/share/images/90-cw.gif\" width=\"8\" height=\"8\" style='margin-left:10px;' title='Vorschaubild 90&#176; rechts drehen' /></span>
							
							</div>
							</TD>";
						}
						ELSE
						{
							$icon[$i6] = "
							<TD align='center' width='43'>
							<div id='box$pic_id'>

							<SPAN style='cursor:pointer;' onClick='window.open(\"$inst_path/pic2base/bin/share/download_picture.php?FileName=$FileName&pic_id=$pic_id\")'>
							<img src='$inst_path/pic2base/bin/share/images/download.gif' width='12' height='12' hspace='0' vspace='0' title='Bild direkt herunterladen'/></SPAN>
							
							</div>
							</TD>";
						}
					}
					ELSE
					{
						IF(hasPermission($uid, 'rotatepicture', $sr))
						{
							$icon[$i6] = "
							<TD align='center' width='43'>
							<div id='box$pic_id'>
							
							<SPAN style='cursor:pointer;' onClick='rotPrevPic(\"8\", \"$FileNameV\", \"$pic_id\", \"$fs_hoehe\")'><img src=\"$inst_path/pic2base/bin/share/images/90-ccw.gif\" width=\"8\" height=\"8\" style='margin-right:5px;' title='Vorschaubild 90&#176; links drehen' /></span>
							<SPAN style='cursor:pointer;' onClick='copyPicture(\"$FileName\",\"$uid\",\"$pic_id\")'><img src='$inst_path/pic2base/bin/share/images/download.gif' width='12' height='12' hspace='0' vspace='0' title='Bild in den FTP-Download-Ordner kopieren'/></SPAN>
							<SPAN style='cursor:pointer;' onClick='rotPrevPic(\"6\", \"$FileNameV\", \"$pic_id\", \"$fs_hoehe\")'><img src=\"$inst_path/pic2base/bin/share/images/90-cw.gif\" width=\"8\" height=\"8\" style='margin-left:5px;' title='Vorschaubild 90&#176; rechts drehen' /></span>
							
							</div>	
							</TD>";
						}
						ELSE
						{
							$icon[$i6] = "
							<TD align='center' width='43'>
							<div id='box$pic_id'>
							
							<SPAN style='cursor:pointer;' onClick='copyPicture(\"$FileName\",\"$uid\",\"$pic_id\")'>
							<img src='$inst_path/pic2base/bin/share/images/download.gif' width='12' height='12' hspace='0' vspace='0' title='Bild in den FTP-Download-Ordner kopieren'/></SPAN>
							
							</div>	
							</TD>";
						}
					}
				}
				ELSE
				{
					$icon[$i6] = "<TD align='center' width='43'><BR></TD>";
				}
			}
		}
		//Leer-Raum mit Leer-Zellen auffuellen (Zelle mit Dummy-Bild zur Streckung gefuellt), wenn Bilder gefunden wurden:
		IF($num6_1 > '0')
		{
			FOR($i_r = '0'; $i_r<$rest; $i_r++)
			{
				echo "<TD align='center' colspan='1'><img src='$inst_path/pic2base/bin/share/images/no_pic.gif' width='124' height='10' /></TD>";
			}
		}
		
		echo "	
		</TR>
		<div id='checkboxen'>
		<TR>";
		IF(isset($icon) and count($icon) > '0')
		{
			FOREACH ($icon AS $ICON)
			{
				echo $ICON;
			}
			FOR($i_re = '0'; $i_re < $rest; $i_re++)
			{
				echo "<TD align='left' width='43'></TD>";
			}
		}
		echo "
		</TR>
		</div>";
		//Tabellenzeile zur Anzeige des gewaehlten, 'aktiven' Bildes:
		echo "<TR>";
		FOR ($i6=0; $i6<$num6; $i6++)
		{
			$pic_id = mysql_result($result6, $i6, 'pic_id');
			echo "<TD align='center'><div id = 'picture_$pic_id' style='display:none';><img src='$inst_path/pic2base/bin/share/images/green.gif' width=\"50\" height=\"2\" /></div></TD>";
		}
		FOR($i_re = '0'; $i_re < $rest; $i_re++)
		{
			echo "<TD align='left' width='43'></TD>";
		}
		echo "</TR>";
//00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
		//Wenn mehr als 18 Bilder gefunden wurden, wird der Slider angezeigt:
		IF($num6_1 > 18 AND $num6_1 < 600)
		{
			echo "<TR><TD colspan = '6' align=center>";
			//Anzahl der Steps ist Anzahl der Bilder / 6:
//			$steps = $num6_1/6;
			$steps = $num6_1/$step;
			//es werden $steps Elemente zur Slider-bar zusammengefuegt, deren Gesamtbreite rund 500 Pixel betraegt:
			//Breite eines Slider-Elements:
//			$sl_width = 600 / $steps;
			$sl_width = $slider_width / $steps;
			FOR($s='0'; $s<$steps; $s++)
			{
				$ziel = $s*6 + 1;
				$ziel_ende = $ziel + 5;
				IF($ziel_ende > $num6_1)
				{
					$ziel_ende = $num6_1;
				}
				//echo $pos." - ".$s;
				$p = ceil($pos / $step);
				IF($p == $s)
				{
					$slider_img = $inst_path."/pic2base/bin/share/images/slider_2.gif";
				}
				ELSE
				{
					$slider_img = $inst_path."/pic2base/bin/share/images/slider_1.gif";
				}
				$position = floor($ziel/6);
				SWITCH ($mod)
				{
					CASE 'kat':
						echo "
						<SPAN style='cursor:pointer;' onClick='getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",0,\"$position\",99)' title='zeige Bilder $ziel bis $ziel_ende'>
						<img src=\"$slider_img\" width=\"$sl_width\" height=\"10\"  border='0' style='margin:-1px; padding:0px;' />
						</SPAN>";
					break;
					
					CASE 'zeit':
						echo "
						<SPAN style='cursor:pointer;' onClick='getTimePreview(\"$j\",\"$m\",\"$t\",0,\"$mod\",\"$modus\",\"$base_file\",\"$bewertung\",\"$position\",99)' title='zeige Bilder $ziel bis $ziel_ende'>
						<img src=\"$slider_img\" width=\"$sl_width\" height=\"10\"  border='0' style='margin:-1px; padding:0px;' />
						</SPAN>";
					break;
					
					CASE 'exif':
						echo "
						<SPAN style='cursor:pointer;'onClick='getExifPreview(exif_param.zusatz1.value, exif_param.bedingung1.value, exif_param.zusatzwert1.value, \"$mod\", \"$modus\", \"$base_file\", \"$bewertung\",\"$position\",99)'; title='zeige Bilder $ziel bis $ziel_ende'>
						<img src=\"$slider_img\" width=\"$sl_width\" height=\"10\"  border='0' style='margin:-1px; padding:0px;' />
						</SPAN>";
					break;
					
					CASE 'desc':
						echo "
						<SPAN style='cursor:pointer;' onClick='getDescPreview1(descr1.desc1.value, descr1.bed1.value, descr1.desc2.value, descr1.bed2.value, descr1.desc3.value,  descr1.bed3.value, descr1.desc4.value, descr1.bed4.value, descr1.desc5.value, \"$mod\", \"$modus\", \"$base_file\", \"$bewertung\",\"$position\",99)' title='zeige Bilder $ziel bis $ziel_ende'>
						<img src=\"$slider_img\" width=\"$sl_width\" height=\"10\"  border='0' style='margin:-1px; padding:0px;' />
						</SPAN>";
					break;
					
					CASE 'geo':
					IF($form_name == "geo_rech1")
					{
						echo "<SPAN style='cursor:pointer;' onClick='getGeoPreview1(geo_rech1.long.value, geo_rech1.lat.value, geo_rech1.alt.value, geo_rech1.radius1.value, geo_rech1.einheit1.value, \"$mod\", \"$modus\", \"$base_file\", \"geo_rech1\", \"$bewertung\",\"$position\",99)' title='zeige Bilder $ziel bis $ziel_ende'>
						<img src=\"$slider_img\" width=\"$sl_width\" height=\"10\"  border='0' style='margin:-1px; padding:0px;' />
						</SPAN>";
					}
					ELSEIF($form_name == "geo_rech2")
					{
						echo "<SPAN style='cursor:pointer;' onClick='getGeoPreview2(geo_rech2.ort.value, geo_rech2.radius2.value, geo_rech2.einheit2.value, \"$mod\", \"$modus\", \"$base_file\", \"geo_rech2\", \"$bewertung\",\"$position\",99)' title='zeige Bilder $ziel bis $ziel_ende'>
						<img src=\"$slider_img\" width=\"$sl_width\" height=\"10\"  border='0' style='margin:-1px; padding:0px;' />
						</SPAN>";
					}
					break;
					
					CASE 'expert_k':
						echo "
						<SPAN style='cursor:pointer;' onClick='getExpSearchPreview(\"$kat\", \"$op\", \"$kat1\", \"$op1\", \"$kat2\", \"$op2\", \"$kat3\", \"$op3\", \"$kat4\", \"$op4\", \"$mod\",\"$modus\",\"$base_file\",\"$bewertung\",\"$position\",99)' title='zeige Bilder $ziel bis $ziel_ende'>
						<img src=\"$slider_img\" width=\"$sl_width\" height=\"10\"  border='0' style='margin:-1px; padding:0px;' />
						</SPAN>";	
					break;
				}
			}
			echo "</TD></TR>";
		}
		ELSE
		{
			//leere Slider-Tabellenzeile:
			echo "<TR><TD colspan='6'></TD></TR>";
		}
		echo "</TABLE>";
	}
	break; //modus 'recherche' beendet
}

function getHQPreviewNow($pic_id, $hoehe_neu, $breite_neu, $base_file, $kat_id, $mod, $form_name)
{
//	echo "Vorschau...";
	global $ID;
	include 'db_connect1.php';
	include 'global_config.php';
	$res0 = mysql_query( "SELECT pic_id, FileName, FileNameHQ, FileNameV, ExifImageWidth, ExifImageHeight, ImageWidth, ImageHeight, Orientation 
	FROM $table2
	WHERE pic_id = '$pic_id' ");
	echo mysql_error();
	$FileName = mysql_result($res0, isset($i1), 'FileName');
	$FileNameHQ = mysql_result($res0, isset($i1), 'FileNameHQ');
	$FileNameV = mysql_result($res0, isset($i1), 'FileNameV');
	
	$Width = mysql_result($res0, isset($i1), 'ExifImageWidth');
	IF($Width == '0')
	{
		$Width = mysql_result($res0, isset($i1), 'ImageWidth');
	}
	
	$Height = mysql_result($res0, isset($i1), 'ExifImageHeight');
	IF($Height == '0')
	{
		$Height = mysql_result($res0, isset($i1), 'ImageHeight');
	}
	
	//Wenn Breite und Hoehe nicht ausgelesen werden konnten, werden die Werte zu Fuss ermittelt und in die Meta-Daten-Tabelle geschrieben:
	IF($Width == '0' OR $Height == '0')
	{
		@$parameter_o=getimagesize($sr.'/images/originale/'.$FileName);
		$Width = $parameter_o[0];
		$Height = $parameter_o[1];
		//echo $Width." x ".$Height."<BR>";
		$result25 = mysql_query( "UPDATE $table2 SET ExifImageHeight = '$Height', ImageHeight = '$Height', ExifImageWidth = '$Width', ImageWidth = '$Width' WHERE pic_id='$pic_id'");
		echo mysql_error();
	}
	
	IF ($FileNameV == '')
	{
		$FileNameV = 'no_preview.jpg';
	}
	IF(!file_exists($sr.'/images/vorschau/thumbs/'.$FileNameV))
	{
		echo "<FONT COLOR='yellow'>Vorschau-Datei $FileNameV fehlt.<BR>Administrator benachrichtigen.</FONT>";
		$hoehe = 0;
		$breite = 0;
	}
	ELSE
	{
		$parameter_v=getimagesize($sr.'/images/vorschau/thumbs/'.$FileNameV);
		$breite = $parameter_v[0] * 5;
		$hoehe = $parameter_v[1] * 5;
		$width_height=$parameter_v[3];
	}
	
	//echo "Breite: ".$breite.", H&ouml;he: ".$hoehe."<BR>";
	IF (($breite == 0 AND $hoehe == 0) OR ($breite == '' AND $hoehe == ''))
	{
		$breite = 800;
		$hoehe = 600;
	}
	//echo "Breite: ".$breite.", Hoehe: ".$hoehe."<BR>";
      	
   	//Fuer die Darstellung des Vollbildes wird eine mittlere Groesse unter Beachtung des Seitenverhaeltnisses errechnet:
   	//max. Ausdehnung: 1000px
   	$max = '1000';
   	$bild = $inst_path.'/pic2base/images/vorschau/hq-preview/'.$FileNameHQ;
	$ratio_pic = $breite / $hoehe;
	SWITCH ($base_file)
	{
		CASE 'edit_beschreibung':
			$result15 = mysql_query( "SELECT * FROM $table2 WHERE pic_id = '$pic_id'");
			$description = mysql_result($result15, isset($i15), 'Caption_Abstract');
			IF($description == '')
			{
				$description = 'keine';
			}
			IF(file_exists($sr.'/images/vorschau/thumbs/'.$FileNameV))
			{
				echo "<div id='tooltip1'><a href='#'><IMG SRC='$inst_path/pic2base/images/vorschau/thumbs/$FileNameV' alt='Vorschaubild' width='$breite_neu', height='$hoehe_neu' style='border:none;'><span style='text-align:left;'>vorhandene Bildbeschreibung:<BR>".$description."</span></a></div>";
			}
		break;
		
		CASE 'edit_bewertung':
			$result15 = mysql_query( "SELECT * FROM $table2 WHERE pic_id = '$pic_id'");
			$note = mysql_result($result15, isset($i15), 'note');
			IF($note == '')
			{
				$note = '0';
			}
			IF(file_exists($sr.'/images/vorschau/thumbs/'.$FileNameV))
			{
				echo "<SPAN style='cursor:pointer;'><a target=\"vollbild\" OnMouseOver=\"magnifyPic('$pic_id')\" onclick=\"ZeigeBild('$bild', '$breite', '$hoehe', '$ratio_pic', 'HQ', '');return false\"  title='Vergr&ouml;&#223;erte Ansicht'><div class='shadow_2'><IMG SRC='$inst_path/pic2base/images/vorschau/thumbs/$FileNameV' alt='Vorschaubild' width='$breite_neu', height='$hoehe_neu' border='0'></div></a></span>";
			}
		break;
		
		CASE 'recherche2':
			IF(file_exists($sr.'/images/vorschau/thumbs/'.$FileNameV))
			{
				echo "<SPAN style='cursor:pointer;' onMouseOver='getDetails(\"$pic_id\",\"$base_file\",\"$mod\",\"$form_name\")'><a href='JavaScript:openPreview(".'"../../../images/"'.", getImageArray, ".'"'.$FileName.'"'.", gotoFilmstreifenPosition);' title='zur vergr&ouml;sserten Vorschau'><div id='shadow_2$pic_id'><img src='$inst_path/pic2base/images/vorschau/thumbs/$FileNameV' alt='Vorschaubild', width='$breite_neu', height='$hoehe_neu', border='0'/></div></a></span>";
			}
		break;
		
		CASE 'edit_kat_daten':
		CASE 'remove_kat_daten':
			//Bestimmung der bereits zugewiesenen Kategorien:
			$zugew_kat = '';
			$result16 = mysql_query( "SELECT * FROM $table10 WHERE pic_id = '$pic_id'");
			$num16 = mysql_num_rows($result16);
			FOR($i16 = '0'; $i16<$num16; $i16++)
			{
				$kat_id = mysql_result($result16, $i16, 'kat_id');
				$result17 = mysql_query( "SELECT * FROM $table4 WHERE kat_id = '$kat_id'");
				$kategorie = mysql_result($result17, isset($i17), 'kategorie');
				IF($kat_id !== '1')
				{
				$zugew_kat .= $kategorie."<BR>";
				}
			}
			IF(file_exists($sr.'/images/vorschau/thumbs/'.$FileNameV))
			{
				echo "<div id='tooltip1'><a href='#' target=\"vollbild\" onclick=\"ZeigeBild('$bild', '$breite', '$hoehe', '$ratio_pic', 'HQ', '');return false\"  title='Vergr&ouml;&#223;erte Ansicht'><img src='$inst_path/pic2base/images/vorschau/thumbs/$FileNameV' alt='Vorschaubild', width='$breite_neu', height='$hoehe_neu' style='border:none;'><span style='text-align:left;'>bereits zugewiesene Kategorien::<BR>".$zugew_kat."</span></a></div>";
			}
		break;
	}
}

include "preview_layer.php";
echo $previewLayerHtml;

?>