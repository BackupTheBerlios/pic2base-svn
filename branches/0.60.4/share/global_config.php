<?php
// alle oder keine Meldungen ausgeben:
error_reporting(E_ALL);
//error_reporting(0);
//#####################################################################################
//[Pfade] Globale Konfigurationseinstellungen
$doc_root = $_SERVER['DOCUMENT_ROOT'];						//DocumentRoot des Web-Servers
$inst_path = "";											//Pfad zwischen DocumentRoot und pic2base
//$inst_path = trim(str_replace('/pic2base/bin/share','',str_replace($_SERVER['DOCUMENT_ROOT'],'',dirname(__FILE__))));
$p2b_path = $doc_root.$inst_path."/";						//Pfad zur p2b-Wurzel
$sr = $_SERVER['DOCUMENT_ROOT'].$inst_path."/pic2base";		//Software-root
//$sr = trim(str_replace('/bin/share','',dirname(__FILE__)));
$fs_hoehe = '76';											//Hoehe der Bilder im Filmstreifen in px
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
$supported_filetypes = array('bmp','cgm','cr2','crw','dcm','dcr','dcx','eps','exr','fax','gif','html','jng','jpeg','jpg','mng','mrw','mvg','nef','orf','otb','palm','pbm','pcd','pcds','pcl','pcx','pdb','pdf','pgm','png','png8','png24','png32','pnm','ppm','ps','ps2','ps3','psd','raf','raw','rgb','rla','rle','sct','sfw','sgi','shtml','sun','svg','tga','tiff','tif','txt','vicar','viff','wpg','xbm','xcf','xpm','x3f','ycbcr','ycbcra','yuv');

//unterstuetzten Datei-Formate (OHNE RAW-Dateien):
$supported_extensions = array('bmp','cgm','dcm','dcx','eps','exr','fax','gif','html','jng','jpeg','jpg','mng','mvg','otb','palm','pbm','pcd','pcds','pcl','pcx','pdb','pdf','pgm','png','png8','png24','png32','pnm','ppm','ps','ps2','ps3','psd','rla','rle','sct','sfw','sgi','shtml','sun','svg','tga','tiff','tif','vicar','viff','wpg','xbm','xcf','xpm','yuv');
//#####################################################################################

?>