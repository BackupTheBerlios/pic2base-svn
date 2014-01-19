<HTML>
	<HEAD>
		<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
		<TITLE>pic2base - Geo-Referenzierung (Ortsnamen)</TITLE>
		<META NAME="GENERATOR" CONTENT="eclipse">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<link rel=stylesheet type="text/css" href='../../css/format2.css'>
		<link rel="shortcut icon" href="../../share/images/favicon.ico">
		<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
		<script language="JavaScript" src="../../share/functions/resize_elements.js"></script>
		<script language="JavaScript" src="../../share/functions/jquery-1.8.2.min.js"></script>
		<script language="JavaScript">
		  	jQuery.noConflict();
			jQuery(document).ready(checkWindowSize);
			jQuery(window).resize(checkWindowSize); 
		</script>
	</HEAD>

	<BODY LANG="de-DE" scroll = "auto">
<?php
echo "		
		<DIV Class='klein'>
			<div class='page' id='page'>
			
				<div id='head'>
					pic2base :: Ortszuweisung &uuml;berspringen
				</div>
				
				<div class='navi' id='navi'>
					<div class='menucontainer'>
					</div>
				</div>
				
				<div class='content' id='content'>
					<fieldset style='background-color:none; margin-top:10px;'>
					<legend style='color:blue; font-weight:bold;'>Die Ortszuweisung f&uuml;r dieses Bild wird &uuml;bersprungen</legend>
						<div id='scrollbox0' style='overflow-y:scroll;'>
						</div>
					</fieldset>
				</div>
				
				<div id='foot'>
					<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
				</div>
			
			</div>
		</DIV>";

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
		
		$result2 = mysql_query("UPDATE $table2 SET City = 'skipped', GPSAltitude = NULL, GPSLongitude = NULL, GPSLatitude = NULL WHERE pic_id = '$pic_id' AND Owner = '$uid'");
		echo mysql_error();
		//echo "Bild ".$pic_id." wird bearbeitet";
		//dann zurueck zur vorhergehenden Seite:
		echo "<meta http-equiv = 'refresh', content='0; url=edit_location_name.php' >";
		//11.11.2012 (kh.)
?>
	
	</BODY>
</HTML>