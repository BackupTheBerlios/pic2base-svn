<?php

// alle Meldungen ausgeben:
error_reporting(E_ALL);

//[Pfade]
//Globale Konfigurationseinstellungen
$doc_root = $_SERVER['DOCUMENT_ROOT'];						//DocumentRoot des Web-Servers
$inst_path = "";										//Pfad zwischen DocumentRoot und pic2base
$p2b_path = $doc_root.$inst_path."/";						//Pfad zur p2b-Wurzel
$sr = $_SERVER['DOCUMENT_ROOT'].$inst_path."/pic2base";		//Software-root

$path_copy = '../../../images/originale';
$vorschau_verzeichnis = '../../../images/vorschau/thumbs';
$HQ_verzeichnis = '../../../images/vorschau/hq-preview';
$geo_path_copy = $sr."/tracks";
$ftp_path = $sr."/userdata";

// fuer register_globals = off
if(!isset($benutzername)){
	$benutzername = '';
}

$user_dir = $ftp_path.'/'.$benutzername;
$up_dir = $ftp_path.'/'.$benutzername.'/uploads';
$down_dir = $ftp_path.'/'.$benutzername.'/downloads';
$kml_dir = $ftp_path.'/'.$benutzername.'/kml_files';

$pic_path = $sr."/images/originale";
$pic_rot_path = $sr."/images/originale/rotated";
$pic_hq_preview = $sr."/images/vorschau/hq-preview";
$pic_thumbs = $sr."/images/vorschau/thumbs";
$hist_path = $sr."/images/histogramme";
$monochrome_path = $sr."/images/monochrome";

//alle unterstuetzten Datei-Formate (incl- RAW-Dateien):
$supported_filetypes = array('bmp','cgm','cr2','crw','dcm','dcr','dcx','eps','exr','fax','gif','html','jng','jpeg','jpg','mng','mrw','mvg','nef','orf','otb','palm','pbm','pcd','pcds','pcl','pcx','pdb','pdf','pgm','png','png8','png24','png32','pnm','ppm','ps','ps2','ps3','psd','raf','rgb','rla','rle','sct','sfw','sgi','shtml','sun','svg','tga','tiff','txt','vicar','viff','wpg','xbm','xcf','xpm','x3f','ycbcr','ycbcra','yuv');

//unterstuetzten Datei-Formate (OHNE RAW-Dateien):
$supported_extensions = array('bmp','cgm','dcm','dcx','eps','exr','fax','gif','html','jng','jpeg','jpg','mng','mvg','otb','palm','pbm','pcd','pcds','pcl','pcx','pdb','pdf','pgm','png','png8','png24','png32','pnm','ppm','ps','ps2','ps3','psd','rla','rle','sct','sfw','sgi','shtml','sun','svg','tga','tiff','vicar','viff','wpg','xbm','xcf','xpm','yuv');

//Standorte der externen Programme:
$dcraw_path = '/usr/bin';		//Pfad zu dcraw
$im_path = '/usr/bin';			//Pfad zum ImageMagick
$et_path = '/usr/bin';			//Pfad zum exiftool
$gpsb_path = '/usr/bin';	//Pfad zu GPSBabel
$md5sum_path = '/usr/bin';

//[GM-Keys; koennen von http://code.google.com/apis/maps/ bezogen werden]
SWITCH($_SERVER['SERVER_NAME'])
{
	CASE '192.168.2.1':
	$gm_key = 'ABQIAAAAfok8Y2--ffLXF31zAx_DvxSduOkDu6uk_blu4bAZehFc8UgIFRT8JXVB4Qo8Z7BeN-oy10_YBkCEFA';
	break;
	
	CASE '192.168.2.10':
	$gm_key = 'ABQIAAAAfok8Y2--ffLXF31zAx_DvxRdSLoRo6ahUq86u7GTF2QlyFPI7xRoeJelplUqg4Hoq9YI6gI7sblgsA';
	break;
	
	CASE '192.168.2.99':
	$gm_key = 'ABQIAAAAfok8Y2--ffLXF31zAx_DvxQzFTxXOsBYwafEKKcNomHI0QeIexQlwWPECOTW_QzuVlJa5lHsWk9-QA';
	break;
	
	CASE '192.168.1.42':
	$gm_key = 'ABQIAAAAfok8Y2--ffLXF31zAx_DvxTL1ycLECCulbociEzhtBQzqRCr-xRH6X16UDh4zV-IrM-ju-_dEeZovw';
	break;
	
	CASE '192.168.1.115':
	$gm_key = 'ABQIAAAAfok8Y2--ffLXF31zAx_DvxTriDCjuNilgxJNDvlkgyYpou4gKRTxHyyfoBFcwA06zZ_T1tbyjwrTLg';
	break;
	
	CASE '192.168.2.20':
	$gm_key = 'ABQIAAAAfok8Y2--ffLXF31zAx_DvxSuWditJXxW9FLuhokEclMC9S8ZkRRqV3gp37rBxwtpvj3XamxOWAhcxw';
	break;
	
	CASE '192.168.171.199':
	$gm_key = 'ABQIAAAAfok8Y2--ffLXF31zAx_DvxRzMcqr4P0Ekez4k72Qu7OM5_F2AxR0urMtZW3iDwHbzkSMKS-R8fYN7w';
	break;
}
?>