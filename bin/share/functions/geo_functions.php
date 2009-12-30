<?php

function getFileError($NUM)
{
	$err = "<font color='green'>Bei der Daten-&Uuml;bernahme gab es keine Fehler.</font><BR>";
	SWITCH($NUM)
	{
		CASE UPLOAD_ERR_OK:
		break;
		
		CASE UPLOAD_ERR_INI_SIZE:
		$err = "<font color='red'>Es wurde die in der php-ini festgelegte Dateigr��e �berschritten!</font>";
		break;
		
		CASE UPLOAD_ERR_FORM_SIZE:
		$err = "<font color='red'>Es wurde die im Formular festgelegte Dateigr��e �berschritten!</font>";
		break;
		
		CASE UPLOAD_ERR_PARTIAL:
		$err = "<font color='red'>Es wurde nur ein Teil der Datei hochgeladen (Abbruch)!</font>";
		break;
		
		CASE UPLOAD_ERR_NO_FILE:
		$err = "<font color='red'>Es wurde keine Datei hochgeladen!</font>";
		break;
	}
	return $err;
}

function getTrackPoints($delta,$pic_time,$datum)
{
	//Ermittlung aller Trackpunkte im Zeitfenster (Aufnahme-Zeitpunkt +/- delta Sekunden
	include '../../share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	$start_time = date('H:i:s', strtotime("$pic_time - $delta seconds"));
	$end_time = date('H:i:s', strtotime("$pic_time + $delta seconds"));
	//echo "Zeitfenster f�r Trackdaten: ".$start_time." - ".$end_time."<BR>";
	$result7 = mysql_query( "SELECT * FROM $table13 WHERE date = '$datum' AND time > '$start_time' AND time < '$end_time'");
	$num7 = mysql_num_rows($result7);
	//echo "Anz. Trackpunkte: ".$num7."<BR>";
	return $num7;
}

function findTrackData($delta,$pic_time,$datum,$pic_id)
{
	//Bestimmung des Trackpunktes mit der kleinsten zeitlichen Abweichung vom Aufnahme-Zeitpunkt:
	include '../../share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	IF(!isset($i10))
	{
		$i10 = 0;
	}
	$delta = $delta - 1;
	$start_time = date('H:i:s', strtotime("$pic_time - $delta seconds"));
	$end_time = date('H:i:s', strtotime("$pic_time + $delta seconds"));
	//echo "Zeitgrenzen fuer Trackdaten: ".$start_time." - ".$end_time."<BR>";
	$result8 = mysql_query( "SELECT * FROM $table13 WHERE date = '$datum' AND (time = '$start_time' OR time = '$end_time')");
	$num8 = mysql_num_rows($result8);
	//Wenn mehrere Trackpunkte gefunden werden, werden willkuerlich die Daten des ersten verwendet:
	$longitude = mysql_result($result8,0,'longitude'); 
	$latitude = mysql_result($result8,0,'latitude'); 
	$altitude = mysql_result($result8,0,'altitude'); 
	//in der 'locations' gespeichert und mit dem Bild 'pic_id' verknuepft:
	$result9 = mysql_query( "INSERT INTO $table12 (longitude, latitude, altitude) VALUES ('$longitude', '$latitude', '$altitude')");
	echo mysql_error();
	$result10 = mysql_query( "SELECT max(loc_id) FROM $table12");
	$loc_id = mysql_result($result10, $i10, 'max(loc_id)');
	//echo "<".$loc_id." >";
	$result11 = mysql_query( "UPDATE $table2 SET loc_id = '$loc_id' WHERE pic_id = '$pic_id'");
}

function gmtToLocalTime($date,$time,$timezone)
{
	//fkt. konvertiert eine GMT-Zeitangabe in die Zeit am Aufnahme-Ort:
	//Version mit manueller Korrektur der Zeitzone:
	//echo $timezone."<BR>";
	$akt_date_time = mktime(substr($time,0,2), substr($time,3,2), substr($time,6,2), substr($date,5,2), substr($date,8,2), substr($date,0,4));
	//echo "Aktuelle DateTime: ".$akt_date_time."<BR>";
	$korr_time = $akt_date_time + (3600 * $timezone); //Korr. UTC auf lokale Zeit
	$date_time_korr = strftime('%Y-%m-%d %H:%M:%S', $korr_time);
	//echo "korr. Zeit: ".$date_time_korr."<BR>";
	return $date_time_korr;
}

function getDeltaLong($lat, $radius1)
{
	$umf_lat = 40000000 * cos(deg2rad($lat));
	//echo "Umfang auf Breitenkreis ".$lat.": ".($umf_lat / 1000)." km<BR>";
	$diff_long = 360 / $umf_lat;
	//echo htmlentities("Winkel�anderung je m: ".$diff_long)." in Grad / m<BR>";
	$delta_long = $diff_long * $radius1;
	return $delta_long;
}

function isInCircle($longitude, $long_mittel, $latitude, $lat_mittel, $radius)
{
	//Entfernung pro Breitengrad: 111111,111 m
	$y_abst = pow((($latitude - $lat_mittel) * 111111.111),2); // in qm
	
	//Entfernung je L�ngengrad ist von der Erhebung �ber �quator (Breitengrad) abh�ngig:
	$x_abst = pow(($longitude - $long_mittel) * (40000000 * cos(deg2rad($latitude)) / 360),2); //in qm
	
	IF (pow($radius , 2) < ($x_abst + $y_abst))
	{
		$inside = 'false';
	}
	ELSE
	{
		$inside = 'true';
	}
	return $inside;
}

function deg2dec($value)
{
	//Fkt konvertiert die Geo-Koordinaten von Grad/Minite/sekunde nach dezimaler Schreibweise
	
	return $value;
}

function convertFile($sr,$data_logger,$info,$geo_file_name,$benutzername,$user_id,$timezone)
{
	include $sr.'/bin/share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	$error = 0;
	//var_dump($info);
	//Sony-CS1-log-Dateien werden von GPSBabel als nmea verarbeitet!
	SWITCH($data_logger)
	{
		// Bsp: gpsbabel -t -i nmea -f "Pfad/Datei.log" -o kml -F "Pfad/Datei.kml"
		CASE '1':
		//Routine f�r Sony CS1 (log)
		IF(strtolower($info['extension']) !== 'log')
		{
			echo "Es handelt sich nicht um eine SONY-CS1-Datei!<BR>";
			$error = '1';
		}
		ELSE
		{

			$file_format = 'nmea';
		}
		break;
		
		CASE '2':
		//Routine f�r Garmin GPSmap 60CS(x) tracklogs (.gpx)
		IF(strtolower($info['extension']) !== 'gpx')
		{
			echo "Es handelt sich nicht um eine Garmin GPSmap 60CS(x)-Datei!<BR>";
			$error = '1';
		}
		ELSE
		{

			$file_format = 'gpx';
			//echo $file_format."<br>";
		}
		break;
		
		CASE '5':
		//Routine fuer Alan Map500 tracklogs (.trl)
		
		break;
		
		CASE '6':
		//Routine fuer CarteSurTable data file
		
		break;
		
		CASE '17':
		//Routine fuer Garmin MapSource (gdb)
		IF(strtolower($info['extension']) !== 'gdb')
		{
			echo "Es handelt sich nicht um eine Garmin MapSource-Datei!<BR>";
			$error = '1';
		}
		ELSE
		{
			$file_format = 'gdb';
		}
		break;
		
		CASE '22':
		//Routine fuer Google Earth (Keyhole) Markup Language (kml)
		IF(strtolower($info['extension']) !== 'kml')
		{
			echo "Es handelt sich nicht um eine Google Earth (Keyhole) Markup Language-Datei!<BR>";
			$error = '1';
		}
		ELSE
		{
			$file_format = 'kml';
		}
		break;
	}
	
	IF($error !== '1')
	{
		//echo $geo_path_copy."/".$geo_file_name."<BR>";
		// ######################
		//Die p2b-trackfile.kml wird nur als strukturierte Zwischenablage verwendet, aus der die Geo-Daten in die Datenbank �berf�hrt werden!
		// ######################
		//$kml_file = shell_exec("gpsbabel -t -i ".$file_format." -f ".$geo_path_copy."/".$geo_file_name." -o kml -F ".$user_dir."/kml_files/p2b-trackfile.kml");
		$kml_file = shell_exec($gpsb_path."/gpsbabel -t -i ".$file_format." -f ".$geo_file_name." -o kml -F ".$user_dir."/kml_files/p2b-trackfile.kml");
		@$fh1 = fopen($user_dir."/kml_files/p2b-trackfile.kml", 'r');
		@$fh0 = fopen($user_dir."/kml_files/p2b-trackfile.kml", 'r');
		IF(!$fh1)
		{
			echo "Es ist ein Fehler bei der Datenkonvertierung aufgetreten.<BR>
			Die weitere Bearbeitung wird abgebrochen.<BR><BR>
			<input type='button' value='Zur&uuml;ck' onClick=javascript:history.back()>";
		}
		ELSE
		{
			
			//echo "parsen der konvertierten Track-Datei und speichern der Werte in der geo_tmp (table13):";
			//Initialisierung des Array-Positionszaehlers:
			$geo_arr = array();
			//$data_arr = array();
			$data_arr = array('0.00', '0.00', '0.00', '0000-00-00T00:00:00Z');
			$z = 0; //Indexz�hler des Geoarrays (0 - Anz. Datensaetz in der Log-Datei)
			
			WHILE(!feof($fh1))
			{
				//Zeilenweise wird der Datei-Inhalt in ein Array ueberfuehrt:
				$line = strip_tags(fgets($fh1, 1024));
				

				IF(stristr($line, 'Longitude:'))
				{
					IF($data_arr[0] == 0.00 AND $data_arr[1] == 0.00 AND $data_arr[2] == 0.00)
					{
						$lon_value = split(' ',$line);
						//print_r($lon_value)."<BR>";
						$data_arr[0] = $lon_value[17];
					}
					ELSE
					{
						$geo_arr[$z] = $data_arr;
						$z++;
						$lon_value = split(' ',$line);
						//print_r($lon_value)."<BR>";
						$data_arr[0] = $lon_value[17];
					}
				}
								
				IF(stristr($line, 'Latitude:'))
				{
					$lat_value = split(' ',$line);
					//print_r($lat_value)."<BR>";
					$data_arr[1] = $lat_value[17];
				}
				
				IF(stristr($line, 'Altitude:'))
				{
					$alt_value = split(' ',$line);
					//print_r($alt_value)."<BR>";
					$data_arr[2] = $alt_value[17];
				}
				
				IF(stristr($line, 'Time:'))
				{
					$time_value = split(' ',$line);
					//print_r($time_value)."<BR>";
					$data_arr[3] = $time_value[17];
				}
			}
			
			//Was steht in dem Array?
			//##################################################################
			//waehrend der Testphase:
			//$result0 = mysql_query( "DELETE FROM $table13");
			//##################################################################
			/*
			$anz1 = count($data_arr);
			echo "Anz. Elemente im Daten-Array: ".$anz1."<BR>";
			$anz2 = count($geo_arr);
			echo "Anz. Elemente im Geo-Array: ".$anz2."<BR>";
			*/
			FOREACH($geo_arr AS $data_arr)
			{
				//echo $data_arr[0]." | ".$data_arr[1]." | ".$data_arr[2]." | ".$data_arr[3]."<BR>";
				$altitude = number_format(($data_arr[2] * 0.3048),1,'.','');
				$date = substr($data_arr[3],0,10);
				IF($date == '')
				{
					$date = '0000-00-00';
				}
				
				$time = substr($data_arr[3],11,8);
				IF($time == '')
				{
					$time = '00:00:00';
				}
				$time_new = substr(gmtToLocalTime($date,$time,$timezone),11,8);	//zeitzonenkorrigierte Zeit
				$date_new = substr(gmtToLocalTime($date,$time,$timezone),0,10);	//zeitzonenkorrigiertes Datum
				//echo $altitude." / ".$date." / ".$date_new." / ".$time_new."<BR>";

				$result1 = mysql_query( "INSERT INTO $table13 (longitude, latitude, altitude, date, time, user_id) VALUES ('$data_arr[0]', '$data_arr[1]', '$altitude', '$date_new', '$time_new', '$user_id')");
				echo mysql_error();
				
			}
		}
	}
	ELSE
	{
		echo "Es wurden keine Daten in die Datenbank &uuml;bernommen!<BR><BR>
		<input type='button' value='Zur&uuml;ck' onClick=javascript:history.back()>";
	}
}
?>