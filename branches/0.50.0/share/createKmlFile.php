<?php
unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
}
$benutzername = $c_username;
include 'global_config.php';
include 'db_connect1.php';
IF($_GET['kml_cod_statement'])
{
	$kml_cod_statement = $_GET['kml_cod_statement'];
}

IF($_GET['mod'])
{
	$mod = $_GET['mod'];
}

$server_url = "http://{$_SERVER['SERVER_NAME']}$inst_path";

IF($mod <> 'geo')
{
	$statement = str_replace('\\', '', urldecode($kml_cod_statement));
	$result8 = mysql_query("$statement");
	echo mysql_error();
	$num8 = mysql_num_rows($result8);	//Anzahl der geo-referenzierten Bilder lt. mitgegebenem Statement
	IF(isset($num8) AND $num8 > '0')
	{
		$content = '<?xml version="1.0" encoding="UTF-8"?>
		<kml xmlns="http://earth.google.com/kml/2.1">
		<Document>
		<name>pic2base-Fotos</name>
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
		//kml-Datei erzeugen und mit Inhalt ($content) fuellen
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
	$pic_id_arr = explode(' ', urldecode($kml_cod_statement));
	//print_r($pic_id_arr);
	IF(count($pic_id_arr) > '0')
	{
		$content = '<?xml version="1.0" encoding="UTF-8"?>
		<kml xmlns="http://earth.google.com/kml/2.1">
		<Document>
		<name>pic2base-Fotos</name>
		<open>1</open>';
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
		//kml-Datei erzeugen und mit Inhalt ($content) fuellen
		$file = time().'.kml';
		$file_name = $kml_dir.'/'.$file;
		$fh = fopen($file_name,"w");
		fwrite($fh,$content);
		fclose($fh);
	}
}
echo " <FONT COLOR='#FF9900'>Diese jetzt anzeigen:</FONT>&#160;&#160;&#160; <a href = '../../../userdata/klaus/kml_files/$file'><img src=\"$inst_path/pic2base/bin/share/images/googleearth-icon.png\" width=\"12\" height=\"12\" border=\"0\"  title='Bilder in GoogleEarth darstellen' /></a></span>";
?>