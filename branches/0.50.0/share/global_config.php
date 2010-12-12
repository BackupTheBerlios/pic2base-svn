<?php
// alle Meldungen ausgeben:
error_reporting(E_ALL);
//#####################################################################################
//[Pfade] Globale Konfigurationseinstellungen
$doc_root = $_SERVER['DOCUMENT_ROOT'];						//DocumentRoot des Web-Servers
$inst_path = "";											//Pfad zwischen DocumentRoot und pic2base
$p2b_path = $doc_root.$inst_path."/";						//Pfad zur p2b-Wurzel
$sr = $_SERVER['DOCUMENT_ROOT'].$inst_path."/pic2base";		//Software-root
//#####################################################################################
//[Benutzerspezifische Pfade]
if(!isset($benutzername))
{
	$benutzername = '';
}
$ftp_path = $sr."/userdata";
$user_dir = $ftp_path.'/'.$benutzername;
$up_dir = $ftp_path.'/'.$benutzername.'/uploads';
$down_dir = $ftp_path.'/'.$benutzername.'/downloads';
$kml_dir = $ftp_path.'/'.$benutzername.'/kml_files';
//#####################################################################################
//[Datei-Ablagepfade]				
$pic_path = $sr."/images/originale";						//Ablage der Originale	
$pic_rot_path = $sr."/images/originale/rotated";			//Ablage der rotierten Vorschaubilder
$pic_hq_path = $sr."/images/vorschau/hq-preview";			//Ablage der HQ-Vorschaubilder
$pic_thumbs_path = $sr."/images/vorschau/thumbs";			//Ablage der kleinen Vorschaubilder
$hist_path = $sr."/images/histogramme";						//Histogramm-Ablage
$monochrome_path = $sr."/images/monochrome";				//Ablage der Monochrome-Vorschau
$track_path = $sr."/tracks";								//Ablage der Trackdaten fuer die Geo-Referenzierung
//#####################################################################################
//[unterstuetzte Dateiformate]
//alle unterstuetzten Datei-Formate (incl- RAW-Dateien):
$supported_filetypes = array('bmp','cgm','cr2','crw','dcm','dcr','dcx','eps','exr','fax','gif','html','jng','jpeg','jpg','mng','mrw','mvg','nef','orf','otb','palm','pbm','pcd','pcds','pcl','pcx','pdb','pdf','pgm','png','png8','png24','png32','pnm','ppm','ps','ps2','ps3','psd','raf','raw','rgb','rla','rle','sct','sfw','sgi','shtml','sun','svg','tga','tiff','txt','vicar','viff','wpg','xbm','xcf','xpm','x3f','ycbcr','ycbcra','yuv');

//unterstuetzten Datei-Formate (OHNE RAW-Dateien):
$supported_extensions = array('bmp','cgm','dcm','dcx','eps','exr','fax','gif','html','jng','jpeg','jpg','mng','mvg','otb','palm','pbm','pcd','pcds','pcl','pcx','pdb','pdf','pgm','png','png8','png24','png32','pnm','ppm','ps','ps2','ps3','psd','rla','rle','sct','sfw','sgi','shtml','sun','svg','tga','tiff','vicar','viff','wpg','xbm','xcf','xpm','yuv');
//#####################################################################################

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
	
	CASE '192.168.2.30':
	$gm_key = 'ABQIAAAAfok8Y2--ffLXF31zAx_DvxS8hLyd-_GDUlLQgu8j9skYQOSoIhS7kdDlVVPS_Uz2FnyXl57IHuq-0w';
	break;
}
?>