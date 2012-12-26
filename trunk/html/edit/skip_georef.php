<HTML>
	<HEAD>
		<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
		<TITLE>pic2base - Geo-Referenzierung (Ortsnamen)</TITLE>
		<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<link rel=stylesheet type="text/css" href='../../css/format1.css'>
		<link rel="shortcut icon" href="../../share/images/favicon.ico">
		<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
	</HEAD>

	<BODY LANG="de-DE" scroll = "auto">
	
		<?php
		if ( array_key_exists('uid',$_GET) )
		{
			$uid = $_GET['uid'];
		}
		if ( array_key_exists('pic_id',$_GET) )
		{
			$pic_id = $_GET['pic_id'];
		}
		//echo "Referenzierung dieses Bildes wird &uuml;bersprungen...<BR>";
		include '../../share/global_config.php';
		include $sr.'/bin/share/db_connect1.php';
		// Wenn die Referenzierung eines einzelnen Bildes uebersprungen werden soll, wird diesem Bild voruebergehend die Ortsbezeichnung City ='skipped' zugewiesen.
		// Wenn alle Bilder referenziert wurden, oder wenn die Referenzierung abgebrochen wird, wird den Bildern mit City = 'skipped'  'Ortsbezeichnung' zugewiesen.
		
		$result2 = mysql_query("UPDATE $table2 SET City = 'Ortsbezeichnung', GPSAltitude = '', GPSLongitude = '', GPSLatitude = '' WHERE pic_id = '$pic_id' AND Owner = '$uid'");
		echo mysql_error();
		//echo "Bild ".$pic_id." wird bearbeitet";
		//dann zurueck zur vorhergehenden Seite:
		echo "<meta http-equiv = 'refresh', content='0; url=edit_location_name.php' >";
		//11.11.2012 (kh.)
		?>
	
	</BODY>
</HTML>