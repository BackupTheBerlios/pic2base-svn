<?php
IF (!$_COOKIE['login'])
{
	include '../share/global_config.php';
	//var_dump($sr);
	header('Location: ../../index.php');
}

//var_dump($_REQUEST);

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
//echo $param;
//echo $mod;
//Auslesen der Vorschau-Bilder aus den EXIF-Daten
//verwendet in edit_kat_daten.php, recherche2.php, edit_beschreibung.php
//Festlegung der Hoehe der Bilder auf dem Filmstreifen:
$fs_hoehe = '80';
//echo "&Uuml;bergebene Parameter: kat_id: ".$kat_id.", ID: ".$ID.", mod: ".$mod.", pic_id: ".$pic_id.", modus: ".$modus;
//echo $base_file;
//echo $bewertung;
//echo "Server-URL: ".$server_url;
//########################################################################################################################
//Darstellung der zu einer Kategorie zugehoerigen Bilder:
include 'db_connect1.php';

unset($c_username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
}
$benutzername = $c_username;
$result15 = mysql_query( "SELECT id FROM $table1 WHERE username = '$c_username' AND aktiv = '1'");
$user_id = mysql_result($result15, isset($i15), 'id');

include 'global_config.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/permissions.php';

$server_url = "http://{$_SERVER['SERVER_NAME']}$inst_path";

$hoehe_neu = '';
$breite_neu = '';

if (!isset($zusatz))
{
	$zusatz = '';
}

SWITCH ($modus)
{
	CASE 'edit':
		//echo "ID: ".$ID.", kat_id: ".$kat_id;
		SWITCH ($ID)
		{
			CASE '':
				//Wenn noch keine Kategorie gewaehlt wurde:
				echo "<p class='gross' style='color:yellow; text-align:center;'>Bitte w&auml;hlen Sie zun&auml;chst in der linken Spalte Bilder einer Kategorie aus!</p>";
				break;
				//################################################################################################################
			CASE '1':
				//Wenn die Wurzel-Kategorie gewaehlt wurde, werden alle Bilder angezeigt, denen noch keine Kategorie zugewiesen wurde:
				$result2 = mysql_query( "SELECT $table14.DateTimeOriginal, $table14.ShutterCount, $table14.pic_id, $table2.pic_id, $table2.FileName, $table2.FileNameHQ, $table2.FileNameV, $table2.has_kat, $table14.FileSize, $table14.Orientation, $table2.note
		FROM $table14, $table2 
		WHERE ($table2.pic_id = $table14.pic_id 
		AND $table2.Owner = '$user_id' 
		AND $table2.has_kat = '0' $krit2) 
		ORDER BY $table14.DateTimeOriginal, $table14.ShutterCount");
				$num2 = mysql_num_rows($result2);
				$N = $num2;
				SWITCH ($N)
				{
					CASE '0':
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
					//FOREACH($diff AS $pic_id)
					//FOR($i2='0'; $i2<$num2; $i2++)
					FOR ($i2=0; $i2<$num2; $i2++)
					{
						$pic_id = mysql_result($result2, $i2, 'pic_id');
						$FileName = mysql_result($result2, $i2, 'FileName');
						$FileNameHQ = mysql_result($result2, $i2, 'FileNameHQ');
						$FileNameV = mysql_result($result2, $i2, 'FileNameV');
						$result24 = mysql_query( "SELECT FileSize FROM $table14 WHERE pic_id = '$pic_id'");
						$FileSize = mysql_result($result24, isset($i24), 'FileSize');
						$Orientation = mysql_result($result2, isset($i24), 'Orientation');	// 1: normal; 8: 90 CW
						//$Orientation = mysql_result($result2, isset($i2), 'Orientation');	// 1: normal; 8: 90 CW
						//abgeleitete Groessen:
						IF ($FileNameV == '')
						{
							//@$parameter_v=getimagesize('../../images/originale/'.$FileName);
							$FileNameV = 'no_preview.jpg';
						}
						ELSE
						{
							@$parameter_v=getimagesize($sr.'/images/vorschau/hq-preview/'.$FileNameHQ);
						}
						//$size = round($FileSize / 1024);
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
						$PIC_ID[] = $pic_id;
					}
					echo "
			</TR>
			<TR>";
					//nicht in alle Faellen werden die Checkboxen dargestellt:
						
					SWITCH($base_file)
					{
						CASE 'edit_remove_kat':
							//keine Anzeige der Checkboxen!
							break;

						CASE 'edit_beschreibung':
						Case 'edit_kat_daten':
							IF ($auswahl == '0')
							{
								$checked = '';
							}
							ELSE
							{
								$checked = 'checked';
							}

							echo "<TR>";
							FOREACH ($PIC_ID AS $pic_id)
							{
								echo "	<TD align='center'>
						<INPUT type='checkbox' name='pic_sel$pic_id' $checked>
						</TD>";
							}
							break;

						CASE 'edit_bewertung':
							echo "<TR>";
							FOREACH ($PIC_ID AS $pic_id)
							{
								echo "	<TD align='center'><div id = 'star_set$pic_id'>";
								showStars($pic_id);
								echo "</div></TD>";
							}
							break;
					}
				}

				echo "	</TR>
			</TABLE>";
				break;
				//################################################################################################################
			default:
				//gueltig fuer alle Kategorien ausser Wurzel:
				//abhaengig von der Berechtigung werden die in Frage kommenden Bilder dargestellt:

				IF(hasPermission($c_username, 'editallpics'))
				{
					IF($treestatus == 'plus')
					{
						$result2 = mysql_query( "SELECT $table2.*, $table10.*, $table14.* FROM $table14, $table2, $table10
				WHERE ($table2.pic_id = $table10.pic_id 
				AND $table14.pic_id = $table2.pic_id 
				AND $table10.kat_id = '$ID' $krit2) 
				ORDER BY $table14.DateTimeOriginal, $table14.ShutterCount");
					}
					ELSEIF($treestatus == 'minus')
					{
						$result2 = mysql_query("SELECT $table10.pic_id, $table10.kat_id FROM $table10
				WHERE ($table10.kat_id = '$kat_id') 
				AND ($table10.pic_id <> ALL (SELECT pic_id 
				FROM $table10 LEFT JOIN $table4 ON ($table10.kat_id = $table4.kat_id) 
				WHERE parent = '$kat_id'))");
						echo mysql_error();
					}
						
				}
				ELSEIF(hasPermission($c_username, 'editmypics'))
				{
					IF($treestatus == 'plus')
					{
						$result2 = mysql_query( "SELECT $table2.*, $table10.*, $table14.*
				FROM $table14, $table2, $table10 
				WHERE ($table2.pic_id = $table10.pic_id 
				AND $table14.pic_id = $table2.pic_id 
				AND $table10.kat_id = '$ID' 
				AND $table2.Owner = '$user_id' $krit2) 
				ORDER BY $table14.DateTimeOriginal, $table14.ShutterCount");
					}
					ELSEIF($treestatus == 'minus')
					{
						$result2 = mysql_query("SELECT $table10.pic_id, $table10.kat_id, $table2.Owner, $table2.pic_id
				FROM $table10 INNER JOIN $table2
				ON ($table10.kat_id = '$kat_id')
				AND $table10.pic_id = $table2.pic_id 
				AND $table2.Owner = '$user_id' 
				AND ($table10.pic_id <> ALL (SELECT pic_id 
				FROM $table10 LEFT JOIN $table4 ON ($table10.kat_id = $table4.kat_id) WHERE parent = '$kat_id'))");
					}
				}
				echo mysql_error();
				$num2 = mysql_num_rows($result2);
				IF ($num2 == '0')
				{
					echo "<p class='gross' style='color:green; text-align:center;'>Es gibt keine Bilder, die den gew&auml;hlten Kategorien zugewiesen wurden!</p>";
					return;
				}
				ELSE
				{
					$result4 = mysql_query( "SELECT kategorie FROM $table4 WHERE kat_id='$ID'");
					$kategorie = htmlentities(mysql_result($result4, isset($i4), 'kategorie'));
					echo "Es gibt ".$num2." Bilder in der Kategorie \"".$kategorie."\"";
					//Es wird eine zweizeilige Tabelle erzeugt, in deren oberer Zeile die Vorschaubilder zu sehen sind,
					//in der unteren die jeweils dazugehoerigen Auswahlboxen:
					//der Normalfall - Es werden alle Bilder angezeigt, welche der gewaehlten Kategorie angehoeren
					echo "	<TABLE border='0' align='center'>
			<TR>";
					FOR ($i2=0; $i2<$num2; $i2++)
					{
						$pic_id = mysql_result($result2, $i2, 'pic_id');
						$res2_1 = mysql_query("SELECT FileName, FileNameHQ, FileNameV FROM $table2 WHERE pic_id = '$pic_id'");
						$FileName = mysql_result($res2_1, isset($i2_1), 'FileName');
						$FileNameHQ = mysql_result($res2_1, isset($i2_1), 'FileNameHQ');
						$FileNameV = mysql_result($res2_1, isset($i2_1), 'FileNameV');
						$result24 = mysql_query( "SELECT FileSize, Orientation FROM $table14 WHERE pic_id = '$pic_id'");
						$FileSize = mysql_result($result24, isset($i24), 'FileSize');
						$Orientation = mysql_result($result24, isset($i24), 'Orientation');	// 1: normal; 8: 90 CW
						//$Orientation = mysql_result($result2, isset($i2), 'Orientation');	// 1: normal; 8: 90 CW
						//abgeleitete Groessen:
						IF ($FileNameV == '')
						{
							//@$parameter_v=getimagesize('../../images/originale/'.$FileName);
							$FileNameV = 'no_preview.jpg';
						}
						ELSE
						{
							@$parameter_v=getimagesize($sr.'/images/vorschau/hq-preview/'.$FileNameHQ);
						}
						//$size = round($FileSize / 1024);
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
					SWITCH($base_file)
					{
						CASE 'edit_remove_kat':
							//keine Anzeige der Checkboxen!
							break;

						CASE 'edit_beschreibung':
						Case 'edit_kat_daten':
							IF ($auswahl == '0')
							{
								$checked = '';
							}
							ELSE
							{
								$checked = 'checked';
							}
							echo "<TR>";
							FOR ($i2=0; $i2<$num2; $i2++)
							{
								$pic_id = mysql_result($result2, $i2, 'pic_id');
								echo "	<TD align='center'>
							<INPUT type='checkbox' name='pic_sel$pic_id' $checked>
							</TD>";
							}
							break;

						CASE 'edit_bewertung':
							echo "<TR>";
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
				echo "	</TR>
			</TABLE>";
				break;
		}
		break;

		//###############################################################################################################################

	CASE 'recherche':
		$step = 6;	//Anzahl der im Filmstreifen dargestellten Bilder (Schrittweite)

		IF($bewertung !== '6')
		{
			//Bewertungskriterium wird in Vergleichsoperator und Wert zerlegt:
			//Groesser-Zeichen bedeutet: Der Notenwert ist hoeher, d.h die Note ist schlechter!

			$op = substr($bewertung,0,strlen($bewertung) - 1);
			IF($op == '<=')
			{
				$op = '>=';
			}
			ELSEIF($op == '>=')
			{
				$op = '<=';
			}
			$wert = substr($bewertung,-1);
			$krit2 = "AND $table2.note $op '$wert'";
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
						$krit1 = "WHERE $table14.DateTimeOriginal LIKE '$j%'";
						break;
							
					Case '00':
						$krit1 = "WHERE $table14.DateTimeOriginal = '0000-00-00 00:00:00'";
						break;
							
					default:
						IF($t == '0')
						{
							$krit1 = "WHERE $table14.DateTimeOriginal LIKE '$j-$m%'";
						}
						ELSE
						{
							$krit1 = "WHERE $table14.DateTimeOriginal LIKE '$j-$m-$t%'";
						}
						break;
				}
				//echo $krit1;
				$statement = "SELECT $table14.DateTimeOriginal, $table14.ShutterCount, $table14.pic_id, $table2.pic_id, $table2.note, $table2.FileNameV, $table2.FileNameHQ, $table2.FileName FROM $table14, $table2 $krit1 AND $table2.pic_id = $table14.pic_id $krit2 ORDER BY $table14.DateTimeOriginal, $table14.ShutterCount";
				//echo $statement; //$Statement wird zur Erzeugung der pdf-Galerie benoetigt

				$result6_1 = mysql_query( "SELECT $table14.DateTimeOriginal, $table14.ShutterCount, $table14.pic_id, $table2.pic_id, $table2.Owner, $table2.note FROM $table14, $table2 $krit1 AND $table2.pic_id = $table14.pic_id $krit2 ORDER BY $table14.DateTimeOriginal, $table14.ShutterCount");
				echo mysql_error();

				$result8 = mysql_query( "SELECT $table2.pic_id, $table2.loc_id, $table2.note, $table2.FileNameHQ, $table14.Caption_Abstract, $table14.pic_id, $table14.DateTimeOriginal, $table14.ShutterCount FROM $table2 LEFT JOIN $table14 ON $table2.pic_id = $table14.pic_id $krit1 $krit2 AND $table2.loc_id <>'0' ORDER BY $table14.DateTimeOriginal, $table14.ShutterCount");
				echo mysql_error();

				$num6_1 = mysql_num_rows($result6_1);  	//Gesamtzahl der gefundenen Bilder
				$num8 = mysql_num_rows($result8);	//Anzahl der geo-referenzierten Bilder
				SWITCH ($num6_1)
				{
					CASE '0':
						$text1 = "Es wurde kein Bild gefunden.";
						//echo "Pos.: ".$position."Jahr: ".$jahr.", Monat: ".$month_number.", mod: ".$mod.". Modus: ".$modus."BaseFile: ".$base_file.", Bewertung: ".$bewertung;
						break;
							
					CASE '1':
						$text1 = "<div id='tooltip1'>Es wurde ein Bild gefunden.";
						break;
							
					default:
						$text1 = "<div id='tooltip1'>Es wurden ".$num6_1." Bilder gefunden.";
						break;
				}
				//echo "Es wurden ".$num6_1." Bilder gefunden, davon ".$num8." referenzierte.&#160;&#160;";
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
						$result6_1 = mysql_query( "SELECT $table14.DateTimeOriginal, $table14.ShutterCount, $table14.pic_id, $table2.pic_id, $table2.Owner, $table2.FileName, $table2.FileNameHQ, $table2.FileNameV, $table2.has_kat, $table14.FileSize, $table14.Orientation, $table2.note FROM $table14, $table2 WHERE ($table2.pic_id = $table14.pic_id AND $table2.has_kat = '0' $krit2) ORDER BY $table14.DateTimeOriginal, $table14.ShutterCount");
						$num6_1 = mysql_num_rows($result6_1);
						$N = $num6_1;

						SWITCH ($N)
						{
							CASE '0':
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
				<TR>
				</TABLE>";
						break;
							
					default:
						//bei allen Kategorien ausser der Wurzel:
						$statement = "SELECT $table2.*, $table10.*, $table14.* FROM $table14, $table2, $table10 WHERE ($table2.pic_id = $table10.pic_id AND $table14.pic_id = $table2.pic_id AND $table10.kat_id = '$ID' $krit2) ORDER BY $table14.DateTimeOriginal, $table14.ShutterCount";
							
						//Ermittlung aller Bilder der Kategorie:
						$result6_1 = mysql_query( "SELECT $table2.*, $table10.*, $table14.* FROM $table14, $table2, $table10 WHERE ($table2.pic_id = $table10.pic_id AND $table14.pic_id = $table2.pic_id AND $table10.kat_id = '$ID' $krit2) ORDER BY $table14.DateTimeOriginal, $table14.ShutterCount");

						//davon Ermittlung aller Bilder mit Geo-Referenzierung:
						$result8 = mysql_query( "SELECT $table2.*, $table10.*, $table14.* FROM $table2, $table10, $table14 WHERE ($table2.pic_id = $table10.pic_id AND $table14.pic_id = $table2.pic_id AND $table10.kat_id = '$ID' AND loc_id <>'0' $krit2)");

						$result4 = mysql_query( "SELECT kategorie FROM $table4 WHERE kat_id='$ID'");
						$kategorie = mysql_result($result4, isset($i4), 'kategorie');
						IF(strlen($kategorie) > 17)
						{
							$kategorie = htmlentities(substr($kategorie,0,15))."...";
						}
						ELSE
						{
							$kategorie = htmlentities($kategorie);
						}
						echo mysql_error();
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
						//echo "Num 8: ".$num8."<BR>";
						IF ($num6_1 == '0')
						{
							echo "<p class='gross' style='color:green; text-align:center;'>Es gibt keine Bilder, die den gew&auml;hlten Kategorie zugewiesen wurden!</p>";
							return;
						}
						ELSE
						{
							$text1 = "<div id='tooltip1'>Es gibt ".$num6_1." Bilder in der Kategorie \"".$kategorie."\"";
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
						$long = str_replace(',','.',$long);
						IF(!preg_match('/^([0-9]{1,3})([\.]{0,1})([0-9]{0,9})$/',$long) OR ($long > abs(180)))
						{
							echo "<p class='gross' style='color:red; text-align:center;'>Die Angabe der geogr. L&auml;nge ist falsch!<BR>(Erlaubte Werte liegen zwischen -180&#176; und +180&#176;)</P>";
							return;
						}
							
						$lat = str_replace(',','.',$lat);
						IF(!preg_match('/^([0-9]{1,3})([\.]{0,1})([0-9]{0,9})$/',$lat) OR ($lat > abs(90)))
						{
							echo "<p class='gross' style='color:red; text-align:center;'>Die Angabe der geogr. Breite ist falsch!<BR>(Erlaubte Werte liegen zwischen -90&#176; und +90&#176;)</P>";
							return;
						}
							
						$alt = round(str_replace(',','.',$alt),0);
						IF(!preg_match('/^([0-9]{1,4})$/',$alt) OR ($alt >8850))
						{
							echo "<p class='gross' style='color:red; text-align:center;'>Die Angabe der H&ouml;he ist falsch!<BR>(Erlaubte Werte sind kleiner als die H&ouml;he des Mount Everest)</P>";
							return;
						}
							
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
						$result5 = mysql_query( "SELECT * FROM $table12 WHERE (longitude > '$long_min' AND longitude < '$long_max') AND (latitude > '$lat_min' AND latitude < '$lat_max') AND (altitude > '$alt')");
						//echo mysql_error();
						//Festlegung fuer Pruefung, ob Punkt im KREIS liegt:
						$long_mittel = $long;
						$lat_mittel = $lat;
						break;
						//###############################################################################################
					CASE 'geo_rech2':
						//Suche nach Ortsbezeichnung und Umkreis
						//Bestimmun, welche Koordinaten dem gewaehlten Ort entsprechen und Ermittlung des arithmetischen Mittelwertes als 'gemeinsamer Mittelpunkt':
						//echo "an get_preview &uuml;bergebene Ortsbezeichnung: ".htmlentities($ort)."<BR>";
						$ort = utf8_decode($ort);
						$result10 = mysql_query( "SELECT * FROM $table12 WHERE location = '$ort'");
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
							$lat = $lat + mysql_result($result10, $i10, 'latitude');
							$long = $long + mysql_result($result10, $i10, 'longitude');
						}
						//echo "Summe Breite: ".$lat.", Summe Laenge: ".$long."<BR>";
						$lat_mittel = $lat / $num10;
						$long_mittel = $long / $num10;
						//echo htmlentities("mittlere Breite: ".$lat_mittel.", mittlere Laenge: ".$long_mittel)."<BR>";
							
						//Plausibilitaetspruefung:
						$radius = round(($einheit2 * str_replace(',','.',$radius2)),0);
						IF(!preg_match('/^([0-9]{1,5})$/',$radius2) OR ($radius >50000))
						{
							echo "<p class='gross' style='color:red; text-align:center;'>Die Angabe des Umkreises ist falsch! (Erlaubte Werte sind bis max. 50 km)</P>";
							return;
						}
						//annaehernde Berechnung des Toleranzfeldes aus dem Radius:
						//geogr. Breite: WInkeldifferenz je m Abweichung: 0,000008999280058�
						$diff_lat = 0.000008999280058;
						$delta_lat = $radius * $diff_lat;
						$lat_min = $lat_mittel - $delta_lat;
						$lat_max = $lat_mittel + $delta_lat;
						//echo "Breite: ".$lat_mittel.", min. Breite: ".$lat_min.", max. Breite: ".$lat_max."<BR>";
							
						//geogr. Laenge: hier ist dei Winkelaenderung / Entfernun von der geogr. Breite abhaengig:
						//include 'functions/main_functions.php';
						$delta_long = getDeltaLong($lat_mittel, $radius);
						$long_min = $long_mittel - $delta_long;
						$long_max = $long_mittel + $delta_long;
						//echo htmlentities("Laenge: ".$long_mittel.", min. Laenge: ".$long_min.", max. Laenge: ".$long_max)."<BR>";
							
						//qudratischer Auswahlbereich:
						$result5 = mysql_query( "SELECT * FROM $table12 WHERE (longitude > '$long_min' AND longitude < '$long_max') AND (latitude > '$lat_min' AND latitude < '$lat_max')");
						//echo mysql_error();
						break;
				}

				//Erzeugung des 'Mittlpunkt-Icons' fuer die Darstellung in GoogleEarth:
				$mp = '
		<Placemark>
		<name>Mittelpunkt</name>
		<description>pic2base-Praesentation</description>
		<styleUrl>#exampleBalloonStyle</styleUrl>
		
		<Style>
		<Icon>
		<href>'.$server_url.'/pic2base/bin/share/images/mp.png</href>
		<refreshMode>onInterval</refreshMode>
		<refreshInterval>3600</refreshInterval>
		<viewRefreshMode>onStop</viewRefreshMode>
		<viewBoundScale>0.5</viewBoundScale>
		</Icon>
		</Style>
		
		<Point>
		<coordinates>'.$long_mittel.','.$lat_mittel.',100</coordinates>
		<flyToView>1</flyToView>
		</Point>
		</Placemark>';
				//echo "Mittelpunkt: ".$mp."<BR>";
				$content = '<?xml version="1.0" encoding="UTF-8"?>
		<kml xmlns="http://earth.google.com/kml/2.1">
		<Document>
		<name>PB Foto-Tour</name>
		<open>1</open>';

				IF($lat_mittel !=='' AND $long_mittel !=='')
				{
					$content .=$mp;		//Bezugspunkt einfuegen
					//Umkreis mit Mittelpunkt = Bezugspunkt und Radius = radius zeichnen:
					//Koordinaten bestimmen:
					$values = '';
					FOR($i_winkel=0; $i_winkel<'361'; $i_winkel++)
					{
						//Berechnung der Polygon-Koordinaten:
						//Breite = f(alpha)
						$y = (sin(deg2rad($i_winkel)) * $radius) / 111111.111;
						$lat = $lat_mittel + $y;
						//echo $lat."<BR>";
						//Laenge (unter Zulassung einer leichten Ellipse)
						$umf_lat = 40000000 * cos(deg2rad($lat_mittel));
						$x_norm = $umf_lat / 360; //Strecke in m pro Grad bezogen auf die geogr. Breite des Kreis-Mittelpunktes
						$x = (cos(deg2rad($i_winkel)) * $radius) / $x_norm;
						$long = $long_mittel + $x;
						//echo $long."<BR>";
						$values .= $long.','.$lat."\n";
					}
					//echo $values."<BR>";
						
					$circle = "
			<Style id='yellowPoly'>
				<LineStyle>
					<width>5</width>
					<lineColor>yellow</lineColor>
				</LineStyle>
				<PolyStyle>
					<color>33ff0000</color>
				</PolyStyle>
			</Style>
			<Placemark>
				<name>Umkreis</name>
				<styleUrl>#yellowPoly</styleUrl>
				<Polygon>
					<extrude>1</extrude>
					<altitudeMode>clampToGround</altitudeMode>
					<outerBoundaryIs>
						<LinearRing>
							<coordinates>
							$values
							</coordinates>
						</LinearRing>
					</outerBoundaryIs>
				</Polygon>
			</Placemark>";
							$content .= $circle;
				}
				//echo $content;
				$num5 = mysql_num_rows($result5);
				IF ($num5 == '0')
				{
					echo "<p class='gross' style='color:green; text-align:center;'>Es gibt keine Bilder, die den gew&auml;hlten Bedingungen entsprechen!</p>";
					return;
				}
				ELSE
				{
					FOR($i5=0; $i5<$num5; $i5++)
					{
						$longitude = mysql_result($result5, $i5, 'longitude');
						$latitude = mysql_result($result5, $i5, 'latitude');
						//Pruefung, ob der Punkt in einem KREIS-Bereich um das Zentrum herum liegt:
						$inside = isInCircle($longitude, $long_mittel, $latitude, $lat_mittel, $radius);
						IF($inside == 'true')
						{
							//echo "Punkt ist im Kreis ".$radius."<BR>";
							$loc_id = mysql_result($result5, $i5, 'loc_id');
							//echo "LOC_ID: ".$loc_id."<BR>";
							SWITCH($bewertung)
							{
								CASE '6':
									$result9 = mysql_query( "SELECT * FROM $table2 WHERE loc_id = '$loc_id'");
									break;

								default:
									$result9 = mysql_query( "SELECT * FROM $table2 WHERE loc_id = '$loc_id' $krit2");
									break;
							}
							$num9 = mysql_num_rows($result9);
								
							FOR ($i9=0; $i9<$num9; $i9++)
							{
								//die pic_id's werden zur weiteren Verwendung in ein Array geschrieben:
								$pic_id_arr[] = mysql_result($result9, $i9, 'pic_id');
							}
						}
					}
						
				}
				$statement = $pic_id_arr;
				$num6_1 = count($pic_id_arr);
				echo "<div id='tooltip1'>Es wurde(n) ".$num6_1." Bild(er) gefunden.";
				break;
				//###################################################################################
			CASE 'desc':
				//Bereinigung der Text-Eingabe-Felder:
				$desc1 = utf8_decode(strip_tags($desc1));
				$desc2 = utf8_decode(strip_tags($desc2));
				$desc3 = utf8_decode(strip_tags($desc3));
				$desc4 = utf8_decode(strip_tags($desc4));
				$desc5 = utf8_decode(strip_tags($desc5));

				//Montage des SQL-Statements:
				$statement = 'SELECT '.$table2.'.*, '.$table14.'.* FROM '.$table2.', '.$table14.' WHERE '.$table2.'.pic_id = '.$table14.'.pic_id AND ('.$table14.'.Caption_Abstract LIKE ';

				IF($desc1 !=='')
				{
					$statement .= '\'%'.$desc1.'%\'';

					SWITCH($bed1)
					{
						CASE '0':
							//echo "Ausf&uuml;hrung der Abfrage: '".$statement."' nach erstem Kriterium";
							break;

						CASE '1':
							IF($desc2 !=='')
							{
								$statement .= ' AND '.$table14.'.Caption_Abstract LIKE \'%'.$desc2.'%\'';
							}
							break;

						CASE '2':
							IF($desc2 !=='')
							{
								$statement .= ' OR '.$table14.'.Caption_Abstract LIKE \'%'.$desc2.'%\'';
							}
							break;
					}

					SWITCH($bed2)
					{
						CASE '0':
							//echo "Ausf&uuml;hrung der Abfrage: '".$statement."' nach zweitem Kriterium";
							break;

						CASE '1':
							IF($desc3 !=='')
							{
								$statement .= ' AND '.$table14.'.Caption_Abstract LIKE \'%'.$desc3.'%\'';
							}
							break;

						CASE '2':
							IF($desc3 !=='')
							{
								$statement .= ' OR '.$table14.'.Caption_Abstract LIKE \'%'.$desc3.'%\'';
							}
							break;
					}

					SWITCH($bed3)
					{
						CASE '0':
							//echo "Ausf&uuml;hrung der Abfrage: '".$statement."' nach drittem Kriterium";
							break;

						CASE '1':
							IF($desc4 !=='')
							{
								$statement .= ' AND '.$table14.'.Caption_Abstract LIKE \'%'.$desc4.'%\'';
							}
							break;

						CASE '2':
							IF($desc4 !=='')
							{
								$statement .= ' OR '.$table14.'.Caption_Abstract LIKE \'%'.$desc4.'%\'';
							}
							break;
					}

					SWITCH($bed4)
					{
						CASE '0':
							//echo "Ausf&uuml;hrung der Abfrage: '".$statement."' nach viertem Kriterium";
							break;

						CASE '1':
							IF($desc5 !=='')
							{
								$statement .= ' AND '.$table14.'.Caption_Abstract LIKE \'%'.$desc5.'%\'';
							}
							break;

						CASE '2':
							IF($desc5 !=='')
							{
								$statement .= ' OR '.$table14.'.Caption_Abstract LIKE \'%'.$desc5.'%\'';
							}
							break;
					}

					IF($bewertung !== '6')
					{
						//Bewertungskriterium wird in Vergleichsoperator und Wert zerlegt:
						//Groesser-Zeichen bedeutet: Der Notenwert ist hoeher, d.h die Note ist schlechter!

						$op = substr($bewertung,0,strlen($bewertung) - 1);
						IF($op == '<=')
						{
							$op = '>=';
						}
						ELSEIF($op == '>=')
						{
							$op = '<=';
						}
						$wert = substr($bewertung,-1);
						$krit2 = "AND $table2.note $op '$wert'";
						$stat_all = $statement." ".$krit2;
						$stat_ref = $stat_all.") AND ($table2.loc_id <>'0' OR $table2.loc_id <>'')$krit2 ORDER BY $table14.DateTimeOriginal, $table14.ShutterCount";
					}
					ELSE
					{
						$stat_all = $statement;
						$stat_ref = $stat_all.") AND ($table2.loc_id <>'0' OR $table2.loc_id <>'') ORDER BY $table14.DateTimeOriginal, $table14.ShutterCount";
					}
						
					//echo $bewertung.", ".$stat_all.")<BR>";
					$result6_1 = mysql_query( $stat_all.") ORDER BY $table14.DateTimeOriginal, $table14.ShutterCount");
					$num6_1 = mysql_num_rows($result6_1);
					//echo "Gesamtanzahl der gefundenen Bilder: ".$num6_1." Treffer<BR>";
						
					//echo $stat_ref.")<BR>";
					$result8 = mysql_query( $stat_ref);
					$num8 = mysql_num_rows($result8);
					//echo "Gesamtanzahl der georeferenzierten Bilder: ".$num8." Treffer<BR>";
					//echo mysql_error();
					$krit1 = substr($stat_all,56).")";
					//echo $krit1."<BR>";
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
				$statement = "$stat_all) ORDER BY $table14.DateTimeOriginal, $table14.ShutterCount";
				break;
				//###################################################################################
			CASE 'exif':
				//Unterscheidung, in welcher Tabelle gesucht werden soll:
				IF(substr($zw1,'0','1') == '*')
				{
					//Recherche nach Nicht-Meta-Daten (in pictures)
					//Statement: finde in pictures alles, bei dem in dem gewaehlten Feld der gewuenschte Wert vorkommt, die Qualitaet der gewaehlten Qualitaet entspricht , sortiert nach dem Aufnahmedatum.
					$zw1 = str_replace('*', '', $zw1);
					IF($bedingung1 == 'LIKE')
					{
						$krit1 = "WHERE ".$table2.".".$zusatz1." LIKE '%".$zw1."%'";
					}
					ELSE
					{
						$krit1 = "WHERE ".$table2.".".$zusatz1." ".$bedingung1." '".$zw1."'";
					}
					//echo $krit1." / ".$krit2."<BR>";
					//echo "Zusatz1: ".$zusatz1."<BR>";
						
					$statement = "SELECT $table14.pic_id, $table14.DateTimeOriginal, $table14.ShutterCount, $table2.pic_id, $table2.FileNameV, $table2.FileNameHQ, $table2.FileName, $table2.$zusatz1 FROM $table14, $table2 $krit1 AND $table2.pic_id = $table14.pic_id $krit2 ORDER BY $table14.DateTimeOriginal, $table14.ShutterCount";
						
					$result6_1 = mysql_query( "SELECT $table14.pic_id, $table14.DateTimeOriginal, $table14.ShutterCount, $table2.pic_id, $table2.Owner, $table2.$zusatz1 FROM $table14, $table2 $krit1 AND $table2.pic_id = $table14.pic_id $krit2 ORDER BY $table14.DateTimeOriginal, $table14.ShutterCount");
					echo mysql_error();
					//$result8 liefert die Anz. georef. Bilder entspr. Kriterium:
					$result8 = mysql_query( "SELECT * FROM $table2 $krit1 AND $table2.loc_id <>'0' $krit2");
				}

				ELSE
				{
					//Recherche nach Meta-Daten, NICHT Geo-Daten! (in meta_data)
					IF($bedingung1 == 'LIKE')
					{
						$krit1 = "WHERE ".$table14.".".$zusatz1." LIKE '%".$zw1."%'";
					}
					ELSE
					{
						$krit1 = "WHERE ".$table14.".".$zusatz1." ".$bedingung1." '".$zw1."'";
					}
					//echo $krit1;
					$statement = "SELECT $table14.$zusatz1, $table14.pic_id, $table14.DateTimeOriginal, $table14.ShutterCount, $table2.pic_id, $table2.FileNameV, $table2.FileNameHQ, $table2.FileName FROM $table14, $table2 $krit1 AND $table2.pic_id = $table14.pic_id $krit2 ORDER BY $table14.DateTimeOriginal, $table14.ShutterCount";
						
					$result6_1 = mysql_query( "SELECT $table14.$zusatz1, $table14.pic_id, $table14.DateTimeOriginal, $table14.ShutterCount, $table2.pic_id, $table2.Owner FROM $table14, $table2 $krit1 AND $table2.pic_id = $table14.pic_id $krit2 ORDER BY $table14.DateTimeOriginal, $table14.ShutterCount");
					echo mysql_error();
						
					$result8 = mysql_query( "SELECT $table2.pic_id, $table2.loc_id, $table2.FileNameHQ, $table14.$zusatz1 FROM $table14, $table2 $krit1 AND $table2.loc_id <>'0' AND $table2.pic_id = $table14.pic_id $krit2");
				}

				$num6_1 = mysql_num_rows($result6_1);  	//Gesamtzahl der gefundenen Bilder
				$num8 = mysql_num_rows($result8);	//Anzahl der geo-referenzierten Bilder
				SWITCH ($num6_1)
				{
					CASE '0':
						$text1 = "Es wurde kein Bild gefunden.";
						//echo "Pos.: ".$position."Jahr: ".$jahr.", Monat: ".$month_number.", mod: ".$mod.". Modus: ".$modus."BaseFile: ".$base_file.", Bewertung: ".$bewertung;
						break;
							
					CASE '1':
						$text1 = "<div id='tooltip1'>Es wurde ein Bild gefunden.";
						break;
							
					default:
						$text1 = "<div id='tooltip1'>Es wurden ".$num6_1." Bilder gefunden.";
						break;
				}
				//echo "Es wurden ".$num6_1." Bilder gefunden, davon ".$num8." referenzierte.&#160;&#160;";
				break;
		}
			
		//###########    Erzeugung der thumb-druck.pdf-Datei fuer Thumb-Galerie-Druck:  ###############


		IF($num6_1 < '101')
		{
			$bild = '1';		//HQ-Bilder werden verwendet
		}
		ELSEIF($num6_1 >= '101' AND $num6_1 < '1001')
		{
			$bild = '0';		//Thumbs werden verwendet
		}
		IF($num6_1 < '1001')
		{
			if (!isset($statement))
			{
				$statement = '';
			}
			createContentFile($mod,$statement,$c_username,$bild);
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

		//###########    Erzeugung der kml-Datei:    #############################################################################

		IF($mod <> 'geo')
		{
			IF(isset($num8) AND $num8 > '0')
			{
				$content = '<?xml version="1.0" encoding="UTF-8"?>
			<kml xmlns="http://earth.google.com/kml/2.1">
			<Document>
			<name>Foto-Tour</name>
			<open>1</open>';
					
				FOR ($i8=0; $i8<$num8; $i8++)
				{
					$pic_id = mysql_result($result8, $i8, 'pic_id');
					$FileNameHQ = mysql_result($result8, $i8, 'FileNameHQ');
					$result21 = mysql_query( "SELECT Caption_Abstract FROM $table14 WHERE pic_id = '$pic_id'");
					$Description = utf8_encode(mysql_result($result21, '0', 'Caption_Abstract'));
					$loc_id = mysql_result($result8, $i8, 'loc_id');
					$result7 = mysql_query( "SELECT * FROM $table12 WHERE loc_id = '$loc_id'");
					$longitude = mysql_result($result7,isset($i7), 'longitude');
					$latitude = mysql_result($result7,isset($i7), 'latitude');
					$altitude = mysql_result($result7,isset($i7), 'altitude');
					$location = mysql_result($result7,isset($i7), 'location');
					//Skalierung der Bilder auf max. Seitenlaenge 300px:
					$max_size = '400';
					@$parameter_v=getimagesize($sr.'/images/vorschau/hq-preview/'.$FileNameHQ);
					$breite = $parameter_v[0];
					$hoehe = $parameter_v[1];
					$ratio = $breite / $hoehe;
					IF($ratio > '1')
					{
						$Breite = $max_size;
						$Hoehe = number_format($hoehe * $max_size / $breite,0,'.',',');
					}
					ELSE
					{
						$Hoehe = $max_size;
						$Breite = number_format($breite * $max_size / $hoehe,0,'.',',');
					}
						
					$content .= '<Style id="exampleBalloonStyle">
					<BalloonStyle>
					<bgColor>55ffffbb</bgColor>
					<text>
					<![CDATA[
					<b>'.$Description.'<br>
					<img src="'.$server_url.'/pic2base/images/vorschau/hq-preview/'.$FileNameHQ.'" width='.$Breite.'. height='.$Hoehe.' />
					<br>Bild-ID: '.$pic_id.'</b>
					]]>
					</text>
					</BalloonStyle>
					</Style>
					<Placemark>
					<name>'.$location.'</name>
					<description>pic2base-Praesentation</description>
					<styleUrl>#exampleBalloonStyle</styleUrl>
					
					<Style>
					<Icon>
					<href>'.$server_url.'/pic2base/bin/share/images/pb.png</href>
					<refreshMode>onInterval</refreshMode>
					<refreshInterval>3600</refreshInterval>
					<viewRefreshMode>onStop</viewRefreshMode>
					<viewBoundScale>0.5</viewBoundScale>
					</Icon>
					</Style>
					
					<Point>
					<coordinates>'.$longitude.','.$latitude.','.$altitude.'</coordinates>
					<flyToView>1</flyToView>
					</Point>
					</Placemark>';
				}
				$content .= '</Document>
			</kml>';
				//kml-Datei erzeugen und mit Inhalt ($content) f�llen
				$file = time().'.kml';
				$file_name = $kml_dir.'/'.$file;
				//echo $file_name;
				$fh = fopen($file_name,"w");
				fwrite($fh,$content);
				fclose($fh);
			}
		}
		ELSE
		{
			IF(count($pic_id_arr) > '0')
			{
				FOREACH ($pic_id_arr AS $PID)
				{
					$result8 = mysql_query( "SELECT * FROM $table2 WHERE pic_id = '$PID'");
					$num8 = mysql_num_rows($result8);
					FOR($i8='0'; $i8<$num8; $i8++)
					{
						$pic_id = mysql_result($result8, $i8, 'pic_id');
						$FileNameHQ = mysql_result($result8, $i8, 'FileNameHQ');
						$result20 = mysql_query( "SELECT * FROM $table14 WHERE pic_id = '$pic_id'");
						$Description = mysql_result($result20, 0, 'Caption_Abstract');
						$loc_id = mysql_result($result8, $i8, 'loc_id');
						$result7 = mysql_query( "SELECT * FROM $table12 WHERE loc_id = '$loc_id'");
						$longitude = mysql_result($result7,isset($i7), 'longitude');
						$latitude = mysql_result($result7,isset($i7), 'latitude');
						$altitude = mysql_result($result7,isset($i7), 'altitude');
						$location = mysql_result($result7,isset($i7), 'location');
						//Skalierung der Bilder auf max. Seitenl�nge 300px:
						$max_size = '400';
						@$parameter_v=getimagesize($sr.'/images/vorschau/hq-preview/'.$FileNameHQ);
						$breite = $parameter_v[0];
						$hoehe = $parameter_v[1];
						$ratio = $breite / $hoehe;
						IF($ratio > '1')
						{
							$Breite = $max_size;
							$Hoehe = number_format($hoehe * $max_size / $breite,0,'.',',');
						}
						ELSE
						{
							$Hoehe = $max_size;
							$Breite = number_format($breite * $max_size / $hoehe,0,'.',',');
						}

						$content .= '<Style id="exampleBalloonStyle">
						<BalloonStyle>
						<bgColor>55ffffbb</bgColor>
						<text><![CDATA[
						<b>'.$Description.'<br><img src="'.$server_url.'/pic2base/images/vorschau/hq-preview/'.$FileNameHQ.'" width='.$Breite.'. height='.$Hoehe.' /><br>Bild-ID: '.$pic_id.'</b>
						]]></text>
						</BalloonStyle>
						</Style>
						<Placemark>
						<name>'.$location.'</name>
						<description>pic2base-Praesentation</description>
						<styleUrl>#exampleBalloonStyle</styleUrl>
						
						<Style>
						<Icon>
						<href>'.$server_url.'/pic2base/bin/share/images/pb.png</href>
						<refreshMode>onInterval</refreshMode>
						<refreshInterval>3600</refreshInterval>
						<viewRefreshMode>onStop</viewRefreshMode>
						<viewBoundScale>0.5</viewBoundScale>
						</Icon>
						</Style>
						
						<Point>
						<coordinates>'.$longitude.','.$latitude.','.$altitude.'</coordinates>
						<flyToView>1</flyToView>
						</Point>
						</Placemark>';
					}
				}
				$content .= '</Document>
			</kml>';
				//kml-Datei erzeugen und mit Inhalt ($content) f�llen
				$file = time().'.kml';
				$file_name = $kml_dir.'/'.$file;
				//echo $file_name;
				$fh = fopen($file_name,"w");
				fwrite($fh,$content);
				fclose($fh);
			}
		}
			
		#########    Erzeugung der vollst. 'Filmstreifen-&Uuml;berschrift'     #####################################################

		SWITCH($mod)
		{
			CASE 'zeit':
				$action_2 = "getTimePreview(\"$j\",\"$m\",\"$t\",0,\"$mod\",\"$modus\",\"$base_file\",\"$bewertung\",\"$position\",-2)";
				$action_1 = "getTimePreview(\"$j\",\"$m\",\"$t\",0,\"$mod\",\"$modus\",\"$base_file\",\"$bewertung\",\"$position\",-1)";
				$action1 = "getTimePreview(\"$j\",\"$m\",\"$t\",0,\"$mod\",\"$modus\",\"$base_file\",\"$bewertung\",\"$position\",1)";
				$action2 = "getTimePreview(\"$j\",\"$m\",\"$t\",0,\"$mod\",\"$modus\",\"$base_file\",\"$bewertung\",\"$position\",2)";
				break;

			CASE 'kat':
				$KAT_ID = $kat_id;
				$kat_id = $ID;
				$action_2 = "getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",0,\"$position\",-2)";
				$action_1 = "getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",0,\"$position\",-1)";
				$action1 = "getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",0,\"$position\",1)";
				$action2 = "getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",0,\"$position\",2)";
				break;

			CASE 'desc':
				$action_2 = "getDescPreview1(descr1.desc1.value, descr1.bed1.value, descr1.desc2.value, descr1.bed2.value, descr1.desc3.value,  descr1.bed3.value, descr1.desc4.value, descr1.bed4.value, descr1.desc5.value, \"$mod\", \"$modus\", \"$base_file\", \"$bewertung\",\"$position\",-2)";
				$action_1 = "getDescPreview1(descr1.desc1.value, descr1.bed1.value, descr1.desc2.value, descr1.bed2.value, descr1.desc3.value,  descr1.bed3.value, descr1.desc4.value, descr1.bed4.value, descr1.desc5.value, \"$mod\", \"$modus\", \"$base_file\", \"$bewertung\",\"$position\",-1)";
				$action1 = "getDescPreview1(descr1.desc1.value, descr1.bed1.value, descr1.desc2.value, descr1.bed2.value, descr1.desc3.value,  descr1.bed3.value, descr1.desc4.value, descr1.bed4.value, descr1.desc5.value, \"$mod\", \"$modus\", \"$base_file\", \"$bewertung\",\"$position\",1)";
				$action2 = "getDescPreview1(descr1.desc1.value, descr1.bed1.value, descr1.desc2.value, descr1.bed2.value, descr1.desc3.value,  descr1.bed3.value, descr1.desc4.value, descr1.bed4.value, descr1.desc5.value, \"$mod\", \"$modus\", \"$base_file\", \"$bewertung\",\"$position\",2)";
				break;

			CASE 'geo':
				IF($num6_1 > '0')
				{
					$zusatz = "
			(Diese(s) in <a href='$inst_path/pic2base/userdata/$c_username/kml_files/$file'><img src=\"$inst_path/pic2base/bin/share/images/googleearth-icon.png\" width=\"12\" height=\"12\" border=\"0\" />
			<span>
			<strong>Zur Anzeige der Fotos in GoogleEarth ist es erforderlich, da&#223; GoogleEarth auf Ihrem Rechner installiert ist.</strong><br />
			<br />
			Ein kostenfreier Download steht unter http://earth.google.de zur Verf&uuml;gung.
			</span>
			</a>darstellen)";
				}
				ELSE
				{
					$zusatz='';
				}
				IF($form_name == 'geo_rech1')
				{
					$action_2 = "getGeoPreview1(geo_rech1.long.value, geo_rech1.lat.value, geo_rech1.alt.value, geo_rech1.radius1.value, geo_rech1.einheit1.value, \"$mod\", \"$modus\", \"$base_file\", \"geo_rech1\", \"$bewertung\",\"$position\",-2)";
					$action_1 = "getGeoPreview1(geo_rech1.long.value, geo_rech1.lat.value, geo_rech1.alt.value, geo_rech1.radius1.value, geo_rech1.einheit1.value, \"$mod\", \"$modus\", \"$base_file\", \"geo_rech1\", \"$bewertung\",\"$position\",-1)";
					$action1 = "getGeoPreview1(geo_rech1.long.value, geo_rech1.lat.value, geo_rech1.alt.value, geo_rech1.radius1.value, geo_rech1.einheit1.value, \"$mod\", \"$modus\", \"$base_file\", \"geo_rech1\", \"$bewertung\",\"$position\",1)";
					$action2 = "getGeoPreview1(geo_rech1.long.value, geo_rech1.lat.value, geo_rech1.alt.value, geo_rech1.radius1.value, geo_rech1.einheit1.value, \"$mod\", \"$modus\", \"$base_file\", \"geo_rech1\", \"$bewertung\",\"$position\",2)";
				}
				ELSEIF($form_name == 'geo_rech2')
				{
					$action_2 = "getGeoPreview2(geo_rech2.ort.value, geo_rech2.radius2.value, geo_rech2.einheit2.value, \"$mod\", \"$modus\", \"$base_file\", \"geo_rech2\", \"$bewertung\",\"$position\",-2)";
					$action_1 = "getGeoPreview2(geo_rech2.ort.value, geo_rech2.radius2.value, geo_rech2.einheit2.value, \"$mod\", \"$modus\", \"$base_file\", \"geo_rech2\", \"$bewertung\",\"$position\",-1)";
					$action1 = "getGeoPreview2(geo_rech2.ort.value, geo_rech2.radius2.value, geo_rech2.einheit2.value, \"$mod\", \"$modus\", \"$base_file\", \"geo_rech2\", \"$bewertung\",\"$position\",1)";
					$action2 = "getGeoPreview2(geo_rech2.ort.value, geo_rech2.radius2.value, geo_rech2.einheit2.value, \"$mod\", \"$modus\", \"$base_file\", \"geo_rech2\", \"$bewertung\",\"$position\",2)";
				}
				$num8 = count($pic_id_arr);
				$num6_1 = $num8;
				break;

			CASE 'exif':
				$action_2 = "getExifPreview(exif_param.zusatz1.value, exif_param.bedingung1.value, exif_param.zusatzwert1.value, \"$mod\", \"$modus\", \"$base_file\", \"$bewertung\",\"$position\",-2)";
				$action_1 = "getExifPreview(exif_param.zusatz1.value, exif_param.bedingung1.value, exif_param.zusatzwert1.value, \"$mod\", \"$modus\", \"$base_file\", \"$bewertung\",\"$position\",-1)";
				$action1 = "getExifPreview(exif_param.zusatz1.value, exif_param.bedingung1.value, exif_param.zusatzwert1.value, \"$mod\", \"$modus\", \"$base_file\", \"$bewertung\",\"$position\",1)";
				$action2 = "getExifPreview(exif_param.zusatz1.value, exif_param.bedingung1.value, exif_param.zusatzwert1.value, \"$mod\", \"$modus\", \"$base_file\", \"$bewertung\",\"$position\",2)";
				break;
		}
		//echo $action1;
		//Link zum pdf-Druck (Ausdruck bis max. 1000 Bilder!)
		//IF($num6_1 < '1001' AND $mod !== 'geo')

		IF($num6_1 < '1001')
		{
			$pdf_link = "&#160;&#160;&#160;<A HREF='$inst_path/pic2base/userdata/$c_username/kml_files/thumb-gallery.pdf' title='Thumbnail-Galerie drucken' target = '_blank'>
		<img src='$inst_path/pic2base/bin/share/images/acroread.png' width='12' height='12' border='0' /></A>";
		}
		ELSE
		{
			$pdf_link = '';
		}

		IF(isset($num8) AND $num8 > '0')
		{
			$zusatz = "
			(davon ".$num8." geo-ref.; Diese in <a href='$inst_path/pic2base/userdata/$c_username/kml_files/$file'>
			<img src=\"$inst_path/pic2base/bin/share/images/googleearth-icon.png\" width=\"12\" height=\"12\" border=\"0\" /><span>
			<strong>Zur Anzeige der Fotos in GoogleEarth ist es erforderlich, da&#223; GoogleEarth auf Ihrem Rechner installiert ist.</strong><br />
			<br />
			Ein kostenfreier Download steht unter http://earth.google.de zur Verf&uuml;gung.
			</a></span>
			 darstellen)";

			IF($num6_1 > $step)
			{
				$zusatz .= "&#0160;&#0160;&#0160;
			<SPAN style='cursor:pointer;' onClick='$action_2'><img src=\"$inst_path/pic2base/bin/share/images/anfang.gif\" width=\"12\" height=\"12\" /></SPAN>
			<SPAN style='cursor:pointer;' onClick='$action_1'><img src=\"$inst_path/pic2base/bin/share/images/zurueck.gif\" width=\"12\" height=\"12\" /></SPAN> $bereich 
			<SPAN style='cursor:pointer;' onClick='$action1'><img src=\"$inst_path/pic2base/bin/share/images/vor.gif\" width=\"12\" height=\"12\" /></SPAN>
			<SPAN style='cursor:pointer;' onClick='$action2'><img src=\"$inst_path/pic2base/bin/share/images/ende.gif\" width=\"12\" height=\"12\" /></SPAN>".$pdf_link."</div>";
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
				$zusatz = "&#0160;&#0160;&#0160<SPAN style='cursor:pointer;' onClick='$action_2'><img src=\"$inst_path/pic2base/bin/share/images/anfang.gif\" width=\"12\" height=\"12\" /></SPAN>
			<SPAN style='cursor:pointer;' onClick='$action_1'><img src=\"$inst_path/pic2base/bin/share/images/zurueck.gif\" width=\"12\" height=\"12\" /></SPAN> $bereich 
			<SPAN style='cursor:pointer;' onClick='$action1'><img src=\"$inst_path/pic2base/bin/share/images/vor.gif\" width=\"12\" height=\"12\" /></SPAN>
			<SPAN style='cursor:pointer;' onClick='$action2'><img src=\"$inst_path/pic2base/bin/share/images/ende.gif\" width=\"12\" height=\"12\" /></SPAN>".$pdf_link."";
			}
			ELSEIF($num6_1 > '0')
			{
				$zusatz .= $pdf_link;
			}
		}
		//echo $text1.$zusatz;

		//#############   Aufbau des Statements fuer die limiterte Abfrage:   ###########################################################

		IF($num6_1 > $step)
		{
			$krit3 = "LIMIT $pos,$step";
		}
		ELSE
		{
			$krit3 = '';
		}
		//echo "Z 1446 ".$krit3;
		SWITCH($mod)
		{
			CASE 'zeit':
				$result6 = mysql_query( "SELECT $table2.pic_id, $table2.FileName, $table2.FileNameHQ, $table2.FileNameV, $table2.Owner, $table14.DateTimeOriginal, $table14.ShutterCount, $table14.FileSize FROM $table2 LEFT JOIN $table14 ON $table2.pic_id = $table14.pic_id $krit1 $krit2 ORDER BY $table14.DateTimeOriginal, $table14.ShutterCount $krit3");
				break;

			CASE 'kat':
				$result6 = mysql_query( "SELECT $table2.*, $table14.*, $table10.* FROM $table2, $table10, $table14 WHERE ($table2.pic_id = $table10.pic_id AND $table14.pic_id = $table2.pic_id AND $table10.kat_id = '$ID' $krit2) ORDER BY $table14.DateTimeOriginal, $table14.ShutterCount $krit3");
				break;

			CASE 'desc':
				$result6 = mysql_query( "SELECT $table2.*, $table14.* FROM $table2, $table14 $krit1 $krit2 ORDER BY $table14.DateTimeOriginal, $table14.ShutterCount $krit3");
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
				$result6 = mysql_query( "SELECT $table2.pic_id, $table2.FileName, $table2.FileNameHQ, $table2.FileNameV, $table2.Owner, $table2.note, $table14.DateTimeOriginal, $table14.ShutterCount, $table14.FileSize FROM $table2 LEFT JOIN $table14 ON $table2.pic_id = $table14.pic_id $krit1 $krit2 ORDER BY $table14.DateTimeOriginal, $table14.ShutterCount $krit3");
				echo mysql_error();
				break;

			CASE 'exif':
				$result6 = mysql_query( "SELECT $table2.pic_id, $table2.FileName, $table2.FileNameHQ, $table2.FileNameV, $table2.Owner, $table14.pic_id, $table14.DateTimeOriginal, $table14.ShutterCount FROM $table2, $table14 $krit1 AND $table2.pic_id = $table14.pic_id $krit2 ORDER BY $table14.DateTimeOriginal, $table14.ShutterCount $krit3");
				echo mysql_error();
				break;

		}
		//echo "get_preview, Zeile 1345: ".$steps.", ".$position;
		$num6 = mysql_num_rows($result6);
		//	$N = $num6;

		//#############   Aufbau des Filmstreifens:   #################################################################################
		//$N: Anz. der Bilder ohne Kat.-Zuweisung
		IF ($N >= '0' AND $mod == 'kat')
		{
			//Anzeige der Bilder ohne Kategorie-Zuweisung:
			if ( isset($text1) )
			{
				echo $text1;
			}
			echo "	<TABLE border='0' align='center'>
		<TR>";	
			$j = '0';	//Zaehlvariable fuer den Array-Index der Download-Icons

			FOR($i6_1='0'; $i6_1<$num6_1; $i6_1++)
			{
				//echo $pic_id."<BR>";
				$pic_id = mysql_result($result6_1, $i6_1, 'pic_id');
				$result4 = mysql_query( "SELECT * FROM $table2 WHERE (pic_id = '$pic_id')");
				$FileName = mysql_result($result4, isset($i4), 'FileName');
				$FileNameHQ = mysql_result($result4, isset($i4), 'FileNameHQ');
				$FileNameV = mysql_result($result4, isset($i4), 'FileNameV');
				$result23 = mysql_query( "SELECT * FROM $table14 WHERE pic_id = '$pic_id'");
				$Orientation = mysql_result($result23, isset($i23), 'Orientation');	// 1: normal; 8: 90� nach rechts gedreht
				$FileSize = mysql_result($result23, isset($i23), 'FileSize');
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
					
				echo "<TD align='center' colspan='1' width = '130px' style= 'padding-top:2px; padding-bottom:2px;'>
			<div id='pic$pic_id'>";
				getHQPreviewNow($pic_id, $hoehe_neu, $breite_neu, $base_file, $kat_id, $mod, $form_name);
				echo "</div></TD>";

				//Erzeugung der Download-Icons:
				$Owner = mysql_result($result6_1, $i6_1, 'Owner');
				$check = fileExists($FileName, $c_username);
				IF($check > '0')
				{
					//Die Datei befindet sich im Download-Ordner des Users und wird mit Klick auf das Icon geloescht:
					$icon[$j] = "<TD align='center'>
				<div id='box$pic_id'>
				<SPAN style='cursor:pointer;' onClick='delPicture(\"$FileName\",\"$c_username\",\"$pic_id\")'>
				<img src='$inst_path/pic2base/bin/share/images/selected.gif' width='12' height='12' hspace='0' vspace='0'/>
				</SPAN>	
				</div>
				</TD>";
				}
				ELSE
				{
					//echo $Owner.", ".$user_id;
					//Die Datei befindet sich nicht im Download-Ordner des Users und wird mit Klick auf das Icon dort hin kopiert:
					IF(($user_id == $Owner AND hasPermission($c_username, 'downloadmypics')) OR hasPermission($c_username, 'downloadallpics'))
					{
						IF(directDownload($c_username, $sr))
						//IF($direkt_download > '0')
						{
							$icon[$j] = "
						<TD align='center'width='43'>
						<div id='box$pic_id'>
						<SPAN style='cursor:pointer;' onClick='window.open(\"$inst_path/pic2base/bin/share/download_picture.php?FileName=$FileName&c_username=$c_username&pic_id=$pic_id\")'>
						<img src='$inst_path/pic2base/bin/share/images/download.gif' width='12' height='12' hspace='0' vspace='0' title='Bild direkt herunterladen'/>
						</SPAN>
						</div>	
						</TD>";
						}
						ELSE
						{
							$icon[$j] = "
						<TD align='center'width='43'>
						<div id='box$pic_id'>
						<SPAN style='cursor:pointer;' onClick='copyPicture(\"$FileName\",\"$c_username\",\"$pic_id\")'>
						<img src='$inst_path/pic2base/bin/share/images/download.gif' width='12' height='12' hspace='0' vspace='0' title='Bild in den FTP-Download-Ordner kopieren'/>
						</SPAN>
						</div>	
						</TD>";
						}
					}
					ELSE
					{
						$icon[$j] = "<TD align='center' width='43'><BR></TD>";
					}
				}
				$j++;
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
		</div>
		</TABLE>";
			//		<TR><TD colspan = '6' align=center>Slider -1</TD></TR>
			//		</TABLE>";
		}
		ELSE
		{
			//normale Anzeige der Bilder lt. Suchkriterium:
			//echo $text1;
			IF(!isset($text1))
			{
				$text1 = '';
			}
			//echo "Z 1594; Num 6-1: ".$num6_1.", Num 8: ".$num8."<BR>";
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
				$result22 = mysql_query( "SELECT 'ImageDataSize' FROM $table14 WHERE pic_id = '$pic_id'");
				$FileSize = mysql_result($result22, isset($i22), 'ImageDataSize');
					
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
					
				IF($breite_neu < $hoehe_neu)
				{
					//falls die Ausrichtung falsch in den EXIF-Daten gespeichert wurde, kann das Vorschau-Bild nachtraeglich gedreht werden:
					echo "
				<TD align='center' colspan='1' width = '130px' style= 'padding-top:2px; padding-bottom:2px;'>
				<img src='$inst_path/pic2base/bin/share/images/no_pic.gif' width='124' height='0' />
				<div id='pic$pic_id'>
				<!-- <SPAN style='cursor:pointer;' onClick='rotPrevPic(\"8\", \"$FileNameV\", \"$pic_id\", \"$fs_hoehe\")'>
				<img src=\"$inst_path/pic2base/bin/share/images/90-ccw.gif\" width=\"8\" height=\"8\" style='margin-right:10px;' title='Vorschaubild 90&#176; links drehen' /></span> -->";
					getHQPreviewNow($pic_id, $hoehe_neu, $breite_neu, $base_file, isset($kat_id), $mod, $form_name);
					echo "
				<!-- <SPAN style='cursor:pointer;' onClick='rotPrevPic(\"6\", \"$FileNameV\", \"$pic_id\", \"$fs_hoehe\")'>
				<img src=\"$inst_path/pic2base/bin/share/images/90-cw.gif\" width=\"8\" height=\"8\" style='margin-left:10px;' title='Vorschaubild 90&#176; rechts drehen' /></span> -->
				</div>
				</TD>";
				}
				else
				{
					echo "<TD align='center' colspan='1' width = '130px' style= 'padding-top:2px; padding-bottom:2px;'>
				<div id='pic$pic_id'>";
					getHQPreviewNow($pic_id, $hoehe_neu, $breite_neu, $base_file, isset($kat_id), $mod, $form_name);
					echo "
				</div>
				</TD>";
				}

				//Erzeugung der Download-Icons:
				$Owner = mysql_result($result6, $i6, 'Owner');
				//Pruefung, ob diese Datei bereits im Download-Ordner des angemeldeten Users liegt. Wenn nicht: Download-Icon mit link zur Kopier-Routine; wenn ja: selected-Icon mit Link zur L�sch-Routine:
				$check = fileExists($FileName, $c_username);
				IF($check > '0')
				{
					//Die Datei befindet sich im Download-Ordner des Users und wird mit Klick auf das Icon geloescht:
					$icon[$i6] = "
				<TD align='center' width='43'>
				<div id='box$pic_id'>
				<SPAN style='cursor:pointer;' onClick='delPicture(\"$FileName\",\"$c_username\",\"$pic_id\")'>
				<img src='$inst_path/pic2base/bin/share/images/selected.gif' width='12' height='12' hspace='0' vspace='0'/>
				</SPAN>	
				</div>
				</TD>";
				}
				ELSE
				{
					//echo $Owner.", ".$user_id;
					//Die Datei befindet sich nicht im Download-Ordner des Users und wird mit Klick auf das Icon dort hin kopiert:
					//IF(hasPermission($c_username, 'adminlogin') OR hasPermission($c_username, 'downloadpic'))
					IF(($user_id == $Owner AND hasPermission($c_username, 'downloadmypics')) OR hasPermission($c_username, 'downloadallpics'))
					{
						IF(directDownload($c_username, $sr))
						//IF($direkt_download > '0')
						{
							$icon[$i6] = "
						<TD align='center'width='43'>
						<div id='box$pic_id'>
						<SPAN style='cursor:pointer;' onClick='window.open(\"$inst_path/pic2base/bin/share/download_picture.php?FileName=$FileName&c_username=$c_username&pic_id=$pic_id\")'>
						<img src='$inst_path/pic2base/bin/share/images/download.gif' width='12' height='12' hspace='0' vspace='0' title='Bild direkt herunterladen'/></SPAN>
						</div>	
						</TD>";
						}
						ELSE
						{
							$icon[$i6] = "
						<TD align='center'width='43'>
						<div id='box$pic_id'>
						<SPAN style='cursor:pointer;' onClick='copyPicture(\"$FileName\",\"$c_username\",\"$pic_id\")'>
						<img src='$inst_path/pic2base/bin/share/images/download.gif' width='12' height='12' hspace='0' vspace='0' title='Bild in den FTP-Download-Ordner kopieren'/></SPAN>
						</div>	
						</TD>";
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
					echo "<TD align='center' colspan='1'>
				<img src='$inst_path/pic2base/bin/share/images/no_pic.gif' width='124' height='10' /></TD>";
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
					echo "<TD align='left' width='43'><BR></TD>";
				}
			}
			echo "
		</TR>
		</div>";
			//Wenn mehr als 18 Bilder gefunden wurden, wird der Slider angezeigt:
			IF($num6_1 > 18 AND $num6_1 < 600)
			{
				echo "<TR><TD colspan = '6' align=center>";
				//Anzahl der Steps ist Anzahl der Bilder / 6:
				$steps = $num6_1/6;
				//echo $steps;
				//es werden $steps Elemente zur Slider-bar zusammengefuegt, deren Gesamtbreite rund 500 Pixel betraegt:
				//Breite eines Slider-Elements:
				$sl_width = 500 / $steps;
				//$action3 = "getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",0,\"$ziel\",-2)";
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
					}

				}
				echo "</TD></TR>";
			}
			ELSE
			{
				echo "<TR><TD></TD></TR>";
			}
			echo "</TABLE>";
		}
		break; //modus 'recherche' beendet
}

function getHQPreviewNow($pic_id, $hoehe_neu, $breite_neu, $base_file, $kat_id, $mod, $form_name)
{
	//echo "Vorschau...";
	global $ID;
	include 'db_connect1.php';
	include 'global_config.php';
	//$res0 = mysql_query( "SELECT * FROM $table2 WHERE pic_id='$pic_id'");
	$res0 = mysql_query( "SELECT $table2.pic_id, $table2.FileName, $table2.FileNameHQ, $table2.FileNameV, $table14.pic_id, $table14.ExifImageWidth, $table14.ExifImageHeight, $table14.ImageWidth, $table14.ImageHeight, $table14.Orientation FROM $table2, $table14 WHERE $table2.pic_id = '$pic_id' AND $table2.pic_id = $table14.pic_id");
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
		$parameter_o=getimagesize($sr.'/images/originale/'.$FileName);
		$Width = $parameter_o[0];
		$Height = $parameter_o[1];
		//echo $Width." x ".$Height."<BR>";
		$result25 = mysql_query( "UPDATE $table14 SET ExifImageHeight = '$Height', ImageHeight = '$Height', ExifImageWidth = '$Width', ImageWidth = '$Width' WHERE pic_id='$pic_id'");
		echo mysql_error();
	}

	IF ($FileNameV == '')
	{
		$FileNameV = 'no_preview.jpg';
	}

	$parameter_v=getimagesize($sr.'/images/vorschau/thumbs/'.$FileNameV);
	$breite = $parameter_v[0] * 5;
	$hoehe = $parameter_v[1] * 5;
	//echo "Breite: ".$breite.", H&ouml;he: ".$hoehe."<BR>";
	IF (($breite == 0 AND $hoehe == 0) OR ($breite == '' AND $hoehe == ''))
	{
		$breite = 800;
		$hoehe = 600;
	}
	//echo "Breite: ".$breite.", Hoehe: ".$hoehe."<BR>";
	$width_height=$parameter_v[3];
	//Fuer die Darstellung des Vollbildes wird eine mittlete Groesse unter Beachtung des Seitenverhaeltnisses errechnet:
	//max. Ausdehnung: 800px
	$max = '1000';
	$bild = $inst_path.'/pic2base/images/vorschau/hq-preview/'.$FileNameHQ;
	$ratio_pic = $breite / $hoehe;
	SWITCH ($base_file)
	{
		CASE 'edit_beschreibung':
			$result15 = mysql_query( "SELECT * FROM $table14 WHERE pic_id = '$pic_id'");
			$description = mysql_result($result15, isset($i15), 'Caption_Abstract');
			IF($description == '')
			{
				$description = 'keine';
			}
			//echo "<div id='tooltip1'><a href=edit_beschreibung.php?kat_id=$kat_id&pic_id=$pic_id&art=single_desc_edit&ID=$ID title='Nur Beschreibung dieses einen Bildes &auml;ndern'><IMG SRC='$inst_path/pic2base/images/vorschau/thumbs/$FileNameV' alt='Vorschaubild' width='$breite_neu', height='$hoehe_neu'><span style='text-align:left;'>vorhandene Bildbeschreibung:<BR>".htmlentities($description)."</span></a></div>";
			echo "<div id='tooltip1'>
		<a href='#'>
		<IMG SRC='$inst_path/pic2base/images/vorschau/thumbs/$FileNameV' alt='Vorschaubild' width='$breite_neu', height='$hoehe_neu'>
		<span style='text-align:left;'>vorhandene Bildbeschreibung:<BR>".htmlentities($description)."</span>
		</a>
		</div>";
			break;

		CASE 'edit_bewertung':
			$result15 = mysql_query( "SELECT * FROM $table2 WHERE pic_id = '$pic_id'");
			$note = mysql_result($result15, isset($i15), 'note');
			IF($note == '')
			{
				$note = '0';
			}

			echo "<SPAN style='cursor:pointer;'>
		<a target=\"vollbild\" OnMouseOver=\"magnifyPic('$pic_id')\" onclick=\"ZeigeBild('$bild', '$breite', '$hoehe', '$ratio_pic', 'HQ', '');return false\"  title='Vergr&ouml;&#223;erte Ansicht'>
		<IMG SRC='$inst_path/pic2base/images/vorschau/thumbs/$FileNameV' alt='Vorschaubild' width='$breite_neu', height='$hoehe_neu' border='0'>
		</a>
		</span>";
			break;

		CASE 'recherche2':
		CASE 'edit_remove_kat':
			echo "<SPAN style='cursor:pointer;' onMouseOver='getDetails(\"$pic_id\",\"$base_file\",\"$mod\",\"$form_name\")'>
		<a href='#' target=\"vollbild\" onclick=\"ZeigeBild('$bild', '$breite', '$hoehe', '$ratio_pic', 'HQ', '');return false\"  title='Vergr&ouml;&#223;erte Ansicht'>
		<img src='$inst_path/pic2base/images/vorschau/thumbs/$FileNameV' alt='Vorschaubild', width='$breite_neu', height='$hoehe_neu' border='0'>
		</a>
		</span>";
			break;

		CASE 'edit_kat_daten':

			//Bestimmung der bereits zugewiesenen Kategorien:

			$zugew_kat = '';
			$result16 = mysql_query( "SELECT * FROM $table10 WHERE pic_id = '$pic_id'");
			$num16 = mysql_num_rows($result16);
			FOR($i16 = '0'; $i16<$num16; $i16++)
			{
				$kat_id = mysql_result($result16, $i16, 'kat_id');
				$result17 = mysql_query( "SELECT * FROM $table4 WHERE kat_id = '$kat_id'");
				$kategorie = htmlentities(mysql_result($result17, isset($i17), 'kategorie'));
				IF($kat_id !== '1')
				{
					$zugew_kat .= $kategorie."<BR>";
				}
					
			}

			echo "<div id='tooltip1'>
		<a href='#' target=\"vollbild\" onclick=\"ZeigeBild('$bild', '$breite', '$hoehe', '$ratio_pic', 'HQ', '');return false\"  title='Vergr&ouml;&#223;erte Ansicht'>
		<img src='$inst_path/pic2base/images/vorschau/thumbs/$FileNameV' alt='Vorschaubild', width='$breite_neu', height='$hoehe_neu'>
		<span style='text-align:left;'>bereits zugewiesene Kategorien::<BR>".$zugew_kat."</span>
		</a>
		</div>";

			break;
	}
}

?>