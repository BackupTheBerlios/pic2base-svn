<?php
IF (!$_COOKIE['login'])
{
include '../share/global_config.php';
//var_dump($sr);
  header('Location: ../../index.php');
}

//wird im Suchformular 'Suche nach EXIF-Daten' verwendet
include '../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
//var_dump($_GET);
if(array_key_exists('field',$_GET))
{
	$field = $_GET['field'];
}

if(array_key_exists('bewertung',$_GET))
{
	$bewertung = $_GET['bewertung'];
}

$stat = createStatement($bewertung);

IF($field !== '')
{
	echo "<SELECT class='Auswahl200' NAME='zusatzwert1'>";
	SWITCH($field)
	{
		//Behandlung der recherchierbaren Felder der pictures-Tabelle		
		CASE 'Owner':
		
			$result3 = mysql_query( "SELECT DISTINCT $field FROM $table2 WHERE $stat ORDER BY $field");
			$result3 = mysql_query( "SELECT *
			FROM $table2
			WHERE $table2.$stat 
			GROUP BY $table2.$field
			ORDER BY $table2.$field");
			$num3 = mysql_num_rows($result3);
			FOR($i3='0'; $i3<$num3; $i3++)
			{
				$value = mysql_result($result3, $i3, 'Owner');
				$result2 = mysql_query( "SELECT * FROM $table1 WHERE id = '$value'");
				$wert = htmlentities(mysql_result($result2, $i2, 'vorname')." ".mysql_result($result2, $i2, 'name'));
				$val = '*'.$value;
				echo "<option value='$val'>".$wert."</option>";
			}
		break;
		
		default:
		$result1 = mysql_query("SELECT $field, pic_id, note 
		FROM $table2
		WHERE $stat 
		GROUP BY $field
		ORDER BY $field");
		//echo mysql_error();
		$num1 = mysql_num_rows($result1);
		
		$wert_alt = '';
		FOR ($i1=0; $i1<$num1; $i1++)
		{
			$value = mysql_result($result1, $i1, $field);
			SWITCH($field)
			{
				CASE (stristr($field, 'Date') == true):
				$wert = date('d.m.Y', strtotime($value));
				$val = substr($value,0,10);
				break;
				
				CASE 'ColorMode':
				SWITCH($value)
				{
					CASE '0':
					CASE '':
					$wert = 'Monochrome';
					break;
					
					CASE 'COLOR':
					$wert = 'COLOR';
					break;
				}
				$val = $value;
				break;
				
				CASE 'Orientation':
				SWITCH($value)
				{
					CASE '0':
					$wert = 'ohne Angabe';
					break;
					
					CASE '1':
					$wert = 'Breitformat';
					break;
					
					CASE '2':
					$wert = 'Breitformat, hor. gespiegelt';
					break;
					
					CASE '3':
					$wert = '180&#176; gedreht';
					break;
					
					CASE '4':
					$wert = 'Breitformat, vert. gespiegelt';
					break;
					
					CASE '5':
					$wert = 'Breitformat, hor. gespiegelt u. 270&#167; gedreht';
					break;
					
					CASE '6':
					$wert = 'CW 90&#176; gedreht';
					break;
					
					CASE '7':
					$wert = 'hor. gespiegelt u. 90&#176; gedreht';
					break;
					
					CASE '8':
					$wert = 'CCW 90&#176; gedreht';
					break;
				}
				$val = $value;
				break;
				
				CASE 'Flash':
				SWITCH($value)
				{
					
					CASE '0':
					$wert = 'ohne Angabe';
					break;
					
					CASE '1':
					$wert = 'mit Blitz';
					break;
					
					CASE '5':
					$wert = 'mit Blitz, Return not detected';
					break;
					
					CASE '7':
					$wert = 'mit Blitz, Return detected';
					break;
					
					CASE '9':
					$wert = 'On';
					break;
					
					CASE '13':
					$wert = 'On, Return not detected';
					break;
					
					CASE '15':
					$wert = 'On, Return detected';
					break;
					
					CASE '16':
					$wert = 'Off';
					break;
					
					CASE '24':
					$wert = 'Auto, nicht ausgel&ouml;st';
					break;
					
					CASE '25':
					$wert = 'Auto, ausgel&ouml;st';
					break;
					
					CASE '29':
					$wert = 'Auto, ausgel&ouml;st, Return not detected';
					break;
					
					CASE '31':
					$wert = 'Auto, ausgel&ouml;st, Return detected';
					break;
					
					CASE '32':
					$wert = 'keine Blitzfunktion';
					break;
					
					CASE '65':
					$wert = 'ausgel&ouml;st, Red-eye reduction';
					break;
					
					CASE '69':
					$wert = 'ausgel&ouml;st, Red-eye reduction, Return not detected';
					break;
					
					CASE '71':
					$wert = 'ausgel&ouml;st, Red-eye reduction, Return detected';
					break;
					
					CASE '71':
					$wert = 'On, Red-eye reduction';
					break;
					
					CASE '77':
					$wert = 'On, Red-eye reduction, Return not detected';
					break;
					
					CASE '79':
					$wert = 'On, Red-eye reduction, Return detected';
					break;
					
					CASE '89':
					$wert = 'Auto, ausgel&ouml;st, Red-eye reduction';
					break;
					
					CASE '93':
					$wert = 'Auto, ausgel&ouml;st, Red-eye reduction, Return not detected';
					break;
					
					CASE '95':
					$wert = 'Auto, ausgel&ouml;st, Red-eye reduction, Return detected';
					break;
					
				}
				$val = $value;
				break;
				
				CASE 'ExposureProgram':
				SWITCH($value)
				{
					CASE '0':
					$wert = 'ohne Angabe';
					break;
					
					CASE '1':
					$wert = 'manuell';
					break;
					
					CASE '2':
					$wert = 'Programm AE';
					break;
					
					CASE '3':
					$wert = 'Blenden-Priorit&auml;t AE';
					break;
					
					CASE '4':
					$wert = 'Belichtungszeit-Priorit&auml;t AE';
					break;
					
					CASE '5':
					$wert = 'Kreativ - lange Belichtungszeit';
					break;
					
					CASE '6':
					$wert = 'Sport - kurze Belichtungszeit';
					break;
					
					CASE '7':
					$wert = 'Portrait';
					break;
					
					CASE '8':
					$wert = 'Landschaft';
					break;
				}
				$val = $value;
				break;
				
				CASE 'ResolutionUnit':
				SWITCH($value)
				{
					CASE '0':
					$wert = 'ohne Angabe';
					break;
					
					CASE '1':
					$wert = 'keine';
					break;
					
					CASE '2':
					$wert = 'Inches';
					break;
					
					CASE '3':
					$wert = 'Zentimeter';
					break;
				}
				$val = $value;
				break;
				
				CASE 'FileSize':
				SWITCH ($value)
				{
					CASE $value < 1024:
					$wert = $value." Byte";
					break;
					
					case $value < 1048576:
					$wert = number_format(($value / 1024),1,',','')." kB";
					break;
					
					case $value >= 1048576:
					$wert = number_format(($value / 1048576),1,',','')." MB";
					break;
				}
				$val = $value; 
				break;
				
				default:
				$wert = htmlentities($value);
				$val = $value;
				break;
			}
			//Pruefung, ob es schon einen Eintrag mit diesen Daten gab (bes. bei Datum interessant)
			IF($wert !== $wert_alt)
			{
				echo "<option value='$val'>".$wert."</option>";
				$wert_alt = $wert;
			}
			ELSE
			{
				
			}
		}
		break;
	}
}
echo "</SELECT>";
mysql_close($conn);
?>