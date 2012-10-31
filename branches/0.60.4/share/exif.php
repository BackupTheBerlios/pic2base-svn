<?php
IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
	//var_dump($sr);
	header('Location: ../../index.php');
}

//echo "an exif.php &uuml;bergebene Daten: ".$target." ".$latitude." ".$longitude." ".$altitude."<BR>";
require_once("classes/pel-0.9.1/PelJpeg.php");

/* Dezimale Gradangabe in Grad / Minuten / Sekunden umwandeln */
function dec2dms($decimal)
{
	$decimal=abs($decimal);
	$degrees = floor($decimal);
	$decimal-=$degrees;
	$decimal*=60;
	$minutes = floor($decimal);
	$decimal-=$minutes;
	$decimal*=60;
	$milliseconds = floor($decimal*1000);
	return array($degrees,$minutes,$milliseconds);
}

/* Fuegt EXIF GPS-Tag zu JPEG Datei hinzu                       */
/* Existiert schon ein solches Tag wird das alte ueberschrieben */
/* Rueckgabewerte: 0 bei Erfolg, -1 bei Fehler                  */
function addGPSdata($infile,$outfile,$GPS_lat,$GPS_lon,$GPS_alt)
{
	try
	{
		$image = new PelJpeg($infile);
	}
	catch(Exception $exc)
	{
		return -1;
	}

	if($image instanceof PelJpeg)
	{
		if($image->isValid(new PelDataWindow($image->getBytes())))
		{
			$exif = $image->getExif();
			if($exif==null)
			{
				$exif = new PelExif();
				$image->setExif($exif);
				$exif->setTiff(new PelTiff());
			}

			$tiff = $exif->getTiff();
			$ifd0 = $tiff->getIfd();
			if($ifd0==null)
			{
				$ifd0 = new PelIFD(PelIfd::IFD0);
				$tiff->setIfd($ifd0);
			}
			/* Tags erzeugen */
			$subifd=new PelIfd(PelIfd::GPS);
			$GPS_latref=($GPS_lat<0)?"S":"N";
			$GPS_lonref=($GPS_lon<0)?"W":"E";
			$GPS_altref=($GPS_alt<0)?1:0;
			list($degrees,$minutes,$milliseconds)=dec2dms(abs($GPS_lat));
			$gpslat = new PelEntryRational(PelTag::GPS_LATITUDE, array($degrees,1),array($minutes,1),array($milliseconds,1000));
			list($degrees,$minutes,$milliseconds)=dec2dms(abs($GPS_lon));
			$gpslon = new PelEntryRational(PelTag::GPS_LONGITUDE, array($degrees,1),array($minutes,1),array($milliseconds,1000));
			echo ($GPS_alt * 1000);
			$gpsalt = new PelEntryRational(PelTag::GPS_ALTITUDE, array(abs(($GPS_alt * 1000)),1000));

			$gpslatref = new PelEntryAscii(PelTag::GPS_LATITUDE_REF, $GPS_latref);
			$gpslonref = new PelEntryAscii(PelTag::GPS_LONGITUDE_REF, $GPS_lonref);
			$gpsaltref = new PelEntryByte(PelTag::GPS_ALTITUDE_REF, $GPS_altref);
			$gpsversion = new PelEntryByte(PelTag::GPS_VERSION_ID, 2, 2, 0, 0);
			
			/* Daten eintragen.*/
			$subifd->addEntry($gpsversion);
			$subifd->addEntry($gpslat);
			$subifd->addEntry($gpslon);
			$subifd->addEntry($gpsalt);
			$subifd->addEntry($gpslatref);
			$subifd->addEntry($gpslonref);
			$subifd->addEntry($gpsaltref);

			$ifd0->addSubIfd($subifd);
			file_put_contents($outfile,$image->getBytes());
			return 0;
		}
	}
	return -1;
}
//addGPSdata("c:/test.jpg","c:/test2.jpg",45.975900000,11.287365000,939.8);

?>