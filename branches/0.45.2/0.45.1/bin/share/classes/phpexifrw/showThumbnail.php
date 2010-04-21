<?php
 /**
  * Script to show thumbnail extracted from EXIF information of an JPEG Image
  *
  * Vinay Yadav (vinayRas) <vinay@sanisoft.com>
  * http://www.sanisoft.com/phpexifrw/
  *
  * Changes on 6-June-2003
  *
  */

 header("Content-Type: image/jpeg");

 $file = $_GET["file"];

 $chacheFolder = dirname(__FILE__)."/.cache_thumbs";

 if(file_exists("$chacheFolder/$file")) {
        $fp = fopen("$chacheFolder/$file","rb");
        $tmpStr = fread($fp,filesize("$chacheFolder/$file"));
        echo $tmpStr;
   exit;
 }

 /* assumed to get the filename with full path though GET method. */

 require("exif.inc");
 $er = new phpExifRW($file);
 $er->processFile();
 echo $er->getThumbnail();
?>
