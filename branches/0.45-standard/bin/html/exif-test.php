<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Startseite</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../css/format1.css'>
	<link rel="shortcut icon" href="../share/images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>
<DIV Class="klein">

<?

/*
 * Project: pic2base
 * File: vorlage.php
 *
 * Copyright (c) 2003 - 2005 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 * @copyright 2003-2005 Klaus Henneberg
 * @author Klaus Henneberg
 * @package INTRAPLAN
 * @license http://www.opensource.org/licenses/osl-2.1.php Open Software License
 */

unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = preg_split('#,#',$_COOKIE['login']);
//echo $c_username;
}
include '../share/user_check1.php';
include '../share/db_connect1.php';



$bild="../../beispiel-bilder/IMG_0026.JPG";
//$exifdata=exif_read_data($bild);
$exifdata=exif_read_data($bild,"",true,false);

echo "
<div class='page'>

	<div class='title'>
	<!--<img src='' style='float:right;width:156; height:39;margin-left:3px;' alt='Logo'>-->
	<h1>pic2base - Startseite</h1>
	</div>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>
		<a class='navi' href='erfassung1.php'>Erfassung</a>
		<a class='navi' href='recherche1.php'>Recherche</a>
		<a class='navi' href='vorschau.php'>Bearbeitung</a>
		<a class='navi' href='hilfe1.php'>Hilfe</a>
		<a class='navi' href='index.php'>Logout</a>
		</div>
	</div>
	
	<div class='content'>
	<p style='margin:170px 0px; text-align:center'>";
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
      //print_r($exifdata);
      echo "</p>
	</div>
	<br style='clear:both;' />

	<div class='fuss'>
	<p>(C)2006 Logiqu</p>
	</div>

</div>";

mysql_close($conn);

?>
<p class="klein">- KH 09/2006 -</P>
</DIV>
</CENTER>
</BODY>
</HTML>