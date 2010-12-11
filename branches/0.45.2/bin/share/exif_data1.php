<?php
IF (!$_COOKIE['login'])
{
include '../share/global_config.php';
//var_dump($sr);
  header('Location: ../../index.php');
}

// PHP-Version ermitteln
$verstr=explode(".",phpversion());
$vernum=$verstr[0]*100+$verstr[1]*10+$verstr[2]*1;

//EXIF-Daten ermitteln:
if($vernum >= 420)
{
      // Daten aus den Bildern auslesen
      $bild = $pic_path."/".$new_filename;
      @$exifdata=exif_read_data($bild,"",true,false);

      if($exifdata["FILE"]["FileName"])$FileName=$exifdata["FILE"]["FileName"];
      if($exifdata["FILE"]["FileDateTime"])
      {
      	$FileDateTime=date("d.m.Y &#8211; H:i:s",$exifdata["FILE"]["FileDateTime"]);
      	$FileDateTime_EP = 1;	//Erweiterung _EP steht f�r exif_protect-Tabelle
      }
      if($exifdata["FILE"]["FileSize"])
      {
      	$FileSize=$exifdata["FILE"]["FileSize"];
      	$FileSize_EP = 1;
      }
      if($exifdata["FILE"]["FileType"])
      {
      	$FileType=$exifdata["FILE"]["FileType"];
      	$FileType_EP = 1;
      }
      if($exifdata["FILE"]["MimeType"])
      {
      	$MimeType=$exifdata["FILE"]["MimeType"];
      	$MimeType_EP = 1;
      }
      if($exifdata["COMPUTED"]["Height"])
      {
      	$Height=$exifdata["COMPUTED"]["Height"];
      	$Height_EP = 1;
      }
      if($exifdata["COMPUTED"]["Width"])
      {
      	$Width=$exifdata["COMPUTED"]["Width"];
      	$Width_EP = 1;
      }
      if($exifdata["COMPUTED"]["IsColor"])
      {
      	$IsColor=$exifdata["COMPUTED"]["IsColor"];
      	$IsColor_EP = 1;
      }
      if($exifdata["COMPUTED"]["ByteOrderMotorola"])
      {
      	$ByteOrderMotorola=$exifdata["COMPUTED"]["ByteOrderMotorola"];
      	$ByteOrderMotorola_EP = 1;
      }
      if($exifdata["COMPUTED"]["ApertureFNumber"])
      {
      	$ApertureFNumber=$exifdata["COMPUTED"]["ApertureFNumber"];
      	$ApertureFNumber_EP = 1;
      }
      if($exifdata["COMPUTED"]["UserComment"])
      {
      	$UserComment=$exifdata["COMPUTED"]["UserComment"];
      	$UserComment_EP	= 1;
      }
      if($exifdata["IFD0"]["Make"])
      {
      	$Make=$exifdata["IFD0"]["Make"];
      	$Make_EP = 1;
      }
      if($exifdata["IFD0"]["Model"])
      {
      	$Model=$exifdata["IFD0"]["Model"];
      	$Model_EP = 1;
      }
      if($exifdata["IFD0"]["Orientation"])
      {
      	$Orientation=$exifdata["IFD0"]["Orientation"];
      	$Orientation_EP = 1;
      }
      if($exifdata["IFD0"]["XResolution"])
      {
      	$XResolution=$exifdata["IFD0"]["XResolution"];
      	$XResolution_EP = 1;
      }
      if($exifdata["IFD0"]["YResolution"])
      {
      	$YResolution=$exifdata["IFD0"]["YResolution"];
      	$YResolution_EP = 1;
      }
      if($exifdata["IFD0"]["ResolutionUnit"])
      {
      	$ResolutionUnit=$exifdata["IFD0"]["ResolutionUnit"];
      	$ResolutionUnit_EP = 1;
      }
      if($exifdata["IFD0"]["Software"])
      {
      	$Software=$exifdata["IFD0"]["Software"];
      	$Software_EP = 1;
      }
      if($exifdata["IFD0"]["DateTime"])
      {
      	$DateTime=$exifdata["IFD0"]["DateTime"];
      	$DateTime_EP = 1;
      }
      if($exifdata["IFD0"]["YCbCrPositioning"])
      {
      	$YCbCrPositioning=$exifdata["IFD0"]["YCbCrPositioning"];
      	$YCbCrPositioning_EP = 1;
      }
      if($exifdata["IFD0"]["Exif_IFD_Pointer"])
      {
      	$Exif_IFD_Pointer=$exifdata["IFD0"]["Exif_IFD_Pointer"];
      	$Exif_IFD_Pointer_EP = 1;
      }
      if($exifdata["EXIF"]["ExposureTime"])
      {
      	$ExposureTime=$exifdata["EXIF"]["ExposureTime"];
      	$ExposureTime_EP = 1;
      }
      if($exifdata["EXIF"]["ExposureProgram"])
      {
      	$ExposureProgram=$exifdata["EXIF"]["ExposureProgram"];
      	$ExposureProgram_EP = 1;
      }
      if($exifdata["EXIF"]["FNumber"])
      {
      	$FNumber=$exifdata["EXIF"]["FNumber"];
      	$FNumber_EP = 1;
      }
      if($exifdata["EXIF"]["ExifVersion"])
      {
      	$ExifVersion=$exifdata["EXIF"]["ExifVersion"];
      	$ExifVersion_EP = 1;
      }
      if($exifdata["EXIF"]["DateTimeOriginal"])
      {
      	$DateTimeOriginal=$exifdata["EXIF"]["DateTimeOriginal"];
      	$DateTimeOriginal_EP = 1;
      }
      if($exifdata["EXIF"]["DateTimeDigitized"])
      {
      	$DateTimeDigitized=$exifdata["EXIF"]["DateTimeDigitized"];
      	$DateTimeDigitized_EP = 1;
      }
      //if(is_int($exifdata["EXIF"]["ComponentsConfiguration"]) OR is_string($exifdata["EXIF"]["ComponentsConfiguration"]))$ComponentsConfiguration=$exifdata["EXIF"]["ComponentsConfiguration"];
      if($exifdata["EXIF"]["CompressedBitsPerPixel"])
      {
      	$CompressedBitsPerPixel=$exifdata["EXIF"]["CompressedBitsPerPixel"];
      	$CompressedBitsPerPixel_EP = 1;
      }
      if($exifdata["EXIF"]["ExposureBiasValue"])
      {
      	$ExposureBiasValue=$exifdata["EXIF"]["ExposureBiasValue"];
      	$ExposureBiasValue_EP = 1;
      }
      if($exifdata["EXIF"]["MaxApertureValue"])
      {
      	$MaxApertureValue=$exifdata["EXIF"]["MaxApertureValue"];
      	$MaxApertureValue_EP = 1;
      }
      if($exifdata["EXIF"]["MeteringMode"])
      {
      	$MeteringMode=$exifdata["EXIF"]["MeteringMode"];
      	$MeteringMode_EP = 1;
      }
      if($exifdata["EXIF"]["LightSource"])
      {
      	$LightSource=$exifdata["EXIF"]["LightSource"];
      	$LightSource_EP = 1;
      }
      if($exifdata["EXIF"]["Flash"])
      {
      	$Flash=$exifdata["EXIF"]["Flash"];
      	$Flash_EP = 1;
      }
      if($exifdata["EXIF"]["FocalLength"])
      {
      	$FocalLength=$exifdata["EXIF"]["FocalLength"]; //echo $FocalLength."<BR>";
      	$FocalLength_EP = 1;
      }
      if($exifdata["EXIF"]["FileSource"])
      {
      	$FileSource=$exifdata["EXIF"]["FileSource"];  //echo $FileSource."<BR>";
      	$FileSource_EP = 1;
      }
      if($exifdata["EXIF"]["WhitePoint"])
      {
      	$WhitePoint=$exifdata["EXIF"]["WhitePoint"];  //echo $WhitePoint."<BR>";
      	$WhitePoint_EP = 1;
      }
      if($exifdata["EXIF"]["Gamma"])
      {
      	$Gamma=$exifdata["EXIF"]["Gamma"];  //echo $Gamma."<BR>";
      	$Gamma_EP = 1;
      }
      if($exifdata["COMPUTED"]["Copyright"])
      {
      	$Copyright=$exifdata["COMPUTED"]["Copyright"];  //echo $Copyright."<BR>";
      	$Copyright_EP = 1;
      }
      if($exifdata["EXIF"]["ISOSpeedRatings"])
      {
      	$ISOSpeedRatings=$exifdata["EXIF"]["ISOSpeedRatings"];  //echo $IsoSpeedRatings."<BR>";
      	$ISOSpeedRatings_EP = 1;
      }
      if($exifdata["EXIF"]["WhiteBalance"])
      {
      	$WhiteBalance=$exifdata["EXIF"]["WhiteBalance"];  //echo $WhiteBalance."<BR>";
      	$WhiteBalance_EP = 1;
      }
      if($exifdata["EXIF"]["FocalLengthIn35mmFilm"])
      {
      	$FocalLengthIn35mmFilm=$exifdata["EXIF"]["FocalLengthIn35mmFilm"];  //echo $FocalLengthIn35mmFilm."<BR>";
      	$FocalLengthIn35mmFilm_EP = 1;
      }
      if($exifdata["EXIF"]["DigitalZoomRatio"])
      {
      	$DigitalZoomRatio=$exifdata["EXIF"]["DigitalZoomRatio"];  //echo $DigitalZoomRatio."<BR>";
      	$DigitalZoomRatio_EP = 1;
      }
      
      if($exifdata["GPS"]["GPSLatitude"])
	{
		$GPSLatitude=$exifdata["GPS"]["GPSLatitude"];
		$GPSLatitude_EP = 1;
		IF(Is_Array($GPSLatitude))
		{
			$lat_0 = explode("/",$GPSLatitude[0]);
			$lat_0 = ($lat_0[0] / $lat_0[1]);
			
			$lat_1 = explode("/",$GPSLatitude[1]);
			$lat_1 = ($lat_1[0] / $lat_1[1]) / 60;
			
			$lat_2 = explode("/",$GPSLatitude[2]);
			$lat_2 = ($lat_2[0] / $lat_2[1]) / 3600;
			
			$GPSLatitude = $lat_0 + $lat_1 + $lat2;
			echo "Lat. in Dezimalform: ".$GPSLatitude."<BR>";
		}
	}
	if($exifdata["GPS"]["GPSLongitude"])
	{
		$GPSLongitude=$exifdata["GPS"]["GPSLongitude"];
		$GPSLongitude_EP = 1;
		IF(Is_Array($GPSLongitude))
		{
			$lon_0 = explode("/",$GPSLongitude[0]);
			$lon_0 = ($lon_0[0] / $lon_0[1]);
			
			$lon_1 = explode("/",$GPSLongitude[1]);
			$lon_1 = ($lon_1[0] / $lon_1[1]) / 60;
			
			$lon_2 = explode("/",$GPSLongitude[2]);
			$lon_2 = ($lon_2[0] / $lon_2[1]) / 3600;
			
			$GPSLongitude = $lon_0 + $lon_1 + $lon2;
			echo "Lon. in Dezimalform: ".$GPSLongitude."<BR>";
		}
	}
      /*
      if($exifdata["GPS"]["GPSLatitude"])
      {
      	$GPSLatitude=$exifdata["GPS"]["GPSLatitude"];  echo $GPSLatitude."<BR>";
      	$GPSLatitude_EP = 1;
      }
      if($exifdata["GPS"]["GPSLongitude"])
      {
      	$GPSLongitude=$exifdata["GPS"]["GPSLongitude"];  echo $GPSLongitude."<BR>";
      	$GPSLongitude_EP = 1;
      }
      */
      if($exifdata["GPS"]["GPSAltitude"])
      {
      	$GPSAltitude=$exifdata["GPS"]["GPSAltitude"];  //echo $GPSAltitude."<BR>";
      	$GPSAltitude_EP = 1;
      }
      if($exifdata["GPS"]["GPSTimeStamp"])
      {
      	$GPSTimeStamp=$exifdata["GPS"]["GPSTimeStamp"];  //echo $GPSTimeStamp."<BR>";
      	$GPSTimeStamp_EP = 1;
      }
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>