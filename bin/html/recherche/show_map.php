<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<?php
include '../../share/global_config.php';
//var_dump($_REQUEST);
IF(array_key_exists('lat',$_REQUEST))
{
	$lat = $_REQUEST['lat'];
}
IF(array_key_exists('long',$_REQUEST))
{
	$long = $_REQUEST['long'];
}
IF(array_key_exists('width',$_REQUEST))
{
	$width = $_REQUEST['width'];
}
IF(array_key_exists('height',$_REQUEST))
{
	$height = $_REQUEST['height'];
}
?>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>

<title>Kamera-Standort</title> 

<style type="text/css">
/*v\:*{behavior:url(#default#VML)}*/
BODY{font-family:Arial;font-size:small;background-color:#BDBEC6}
A:hover{color:red;text-decoration:underline}
</style> 

<script src="http://maps.google.com/maps?file=api&v=2&key=<?php echo $gm_key; ?>" type="text/javascript">
</script>

</head>

<body>

<div id="map" style="position: absolute; top:3px;left:3px;width:<?echo $width;?>px; height:<?echo $height;?>px"></div>

<script type="text/javascript">
var lat=<?php echo $lat;?>;
var lon=<?php echo $long;?>;

var map=new GMap2(document.getElementById("map"));
map.setCenter(new GLatLng(lat,lon),14); 	//Breite, Länge, Zoom-Stufe (2 - Ganz Europa; 8 - Raum Magdeburg)
map.addControl(new GMapTypeControl(1));		//Karte/Sat/Hybrid-Auswahl
map.addControl(new GLargeMapControl());		//Zoom-Controls
map.enableContinuousZoom();
map.enableDoubleClickZoom();

var icon=new GIcon();
icon.image="http://labs.google.com/ridefinder/images/mm_20_blue.png";
icon.shadow="http://labs.google.com/ridefinder/images/mm_20_shadow.png";
icon.iconSize=new GSize(12,20);
icon.shadowSize=new GSize(22,20);
icon.iconAnchor=new GPoint(6,20);
icon.infoWindowAnchor=new GPoint(5,1);

var point=new GLatLng(lat,lon);
var markerD=new GMarker(point,{icon:icon,draggable:false});
map.addOverlay(markerD);
//markerD.enableDragging();
//GEvent.addListener(markerD,"drag",function(){document.getElementById("location").value=markerD.getPoint().toUrlValue();});
</script>

</body>
</html>