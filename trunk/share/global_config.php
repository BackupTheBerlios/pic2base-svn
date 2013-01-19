<?php
// Steuerung der Fehlermeldungen:
error_reporting(E_ALL);
//###########################################################################################################
// [Pfade] Globale Konfigurationseinstellungen
$doc_root = $_SERVER['DOCUMENT_ROOT'];						//DocumentRoot des Web-Servers
$inst_path = "";											//Pfad zwischen DocumentRoot und pic2base
$p2b_path = $doc_root.$inst_path."/";						//Pfad zur p2b-Wurzel
$sr = $_SERVER['DOCUMENT_ROOT'].$inst_path."/pic2base";		//Software-root
$fs_hoehe = '76';											//Hoehe der Bilder im Filmstreifen in px
$step = 6;													//Anzahl der Bilder im Filmstreifen (Standard: 6)
$slider_width = 500;										//Breite des Slider-Bereichs (Standard: 500)
//############################################################################################################
// [Benutzerspezifische Pfade]
if(!isset($uid))
{
	$uid = '';
}
else
{
	$ftp_path = $sr."/userdata";
	$user_dir = $ftp_path.'/'.$uid;
	$up_dir = $ftp_path.'/'.$uid.'/uploads';
	$down_dir = $ftp_path.'/'.$uid.'/downloads';
	$kml_dir = $ftp_path.'/'.$uid.'/kml_files';
}
//#############################################################################################################
// [Datei-Ablagepfade]				
$pic_path = $sr."/images/originale";						//Ablage der Originale	
$pic_rot_path = $sr."/images/originale/rotated";			//Ablage der rotierten Vorschaubilder
$pic_hq_path = $sr."/images/vorschau/hq-preview";			//Ablage der HQ-Vorschaubilder
$pic_thumbs_path = $sr."/images/vorschau/thumbs";			//Ablage der kleinen Vorschaubilder
$hist_path = $sr."/images/histogramme";						//Histogramm-Ablage
$monochrome_path = $sr."/images/monochrome";				//Ablage der Monochrome-Vorschau
$track_path = $sr."/tracks";								//Ablage der Trackdaten fuer die Geo-Referenzierung
//#############################################################################################################
// [unterstuetzte Dateiformate]
// alle unterstuetzten Datei-Formate (incl- RAW-Dateien):
$supported_filetypes = array('bmp','cgm','cr2','crw','dcm','dcr','dcx','eps','exr','fax','gif','html','jng',
'jpeg','jpg','mng','mrw','mvg','nef','orf','otb','palm','pbm','pcd','pcds','pcl','pcx','pdb','pdf','pgm',
'png','png8','png24','png32','pnm','ppm','ps','ps2','ps3','psd','raf','raw','rgb','rla','rle','sct','sfw',
'sgi','shtml','sun','svg','tga','tiff','tif','txt','vicar','viff','wpg','xbm','xcf','xpm','x3f','ycbcr',
'ycbcra','yuv');

// unterstuetzten Datei-Formate (OHNE RAW-Dateien):
$supported_extensions = array('bmp','cgm','dcm','dcx','eps','exr','fax','gif','html','jng','jpeg','jpg','mng',
'mvg','otb','palm','pbm','pcd','pcds','pcl','pcx','pdb','pdf','pgm','png','png8','png24','png32','pnm','ppm',
'ps','ps2','ps3','psd','rla','rle','sct','sfw','sgi','shtml','sun','svg','tga','tiff','tif','vicar','viff',
'wpg','xbm','xcf','xpm','yuv');
//#############################################################################################################
// [php-Parameter]
ini_set('post_max_size', '50M');
ini_set('upload_max_filesize', '50M');
ini_set('max_execution_time', '30');
ini_set('memory_limit', '128M');
//ini_set('display_errors', 'Off');		// nur fuer Echtbetrieb
//#############################################################################################################
?>