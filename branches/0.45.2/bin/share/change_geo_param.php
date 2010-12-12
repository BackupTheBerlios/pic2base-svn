<?php
IF (!$_COOKIE['login'])
{
	include '../share/global_config.php';
	//var_dump($sr);
	header('Location: ../../index.php');
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
	xmlns:v="urn:schemas-microsoft-com:vml">
<?php
include 'global_config.php';
if ( array_key_exists('pic_id',$_REQUEST) )
{
	$pic_id = $_REQUEST['pic_id'];
}
IF(array_key_exists('lat',$_REQUEST))
{
	$lat = $_REQUEST['lat'];
}
IF(array_key_exists('long',$_REQUEST))
{
	$long = $_REQUEST['long'];
}
IF(array_key_exists('ort',$_REQUEST))
{
	$ort = $_REQUEST['ort'];
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
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>Geo-Daten bearbeiten...(Bild <?php echo $pic_id; ?>)</title>

<style type="text/css">
/*v\:*{behavior:url(#default#VML)}*/
BODY {
	font-family: Arial;
	font-size: small;
	background-color: #FFFFFF
}

A:hover {
	color: red;
	text-decoration: underline
}
</style>

<script
	src="http://maps.google.com/maps?file=api&v=2&key=<?php echo $gm_key; ?>"
	type="text/javascript"></script>

<script language="JavaScript">
function saveNewParam(newlocation, ort, loc_id, pic_id)
{
	Fenster2 = window.open('../html/recherche/save_new_param.php?location='+newlocation+'&ort='+ort+'&loc_id='+loc_id+'&pic_id='+pic_id, 'Speicherung2', "width=300,height=100,scrollbars,resizable=no,");
}
</script>
</head>

<body>
<?php
//var_dump($_REQUEST);
//echo "PicID: ".$pic_id."<BR>";
include 'db_connect1.php';
$result1 = mysql_query( "SELECT * FROM $table2 WHERE pic_id = '$pic_id'");
echo mysql_error();
$loc_id = mysql_result($result1,0,'loc_id');
//echo "LocID: ".$loc_id."<BR>";
IF($loc_id !== '0' AND $loc_id !== '')
{
	echo "Bild hat eine Loc_id.<BR>";
	$result12 = mysql_query( "SELECT * FROM $table12 WHERE loc_id = '$loc_id'");
	$long = mysql_result($result12,0, 'longitude');
	$lat = mysql_result($result12,0, 'latitude');
	$alt = mysql_result($result12,0, 'altitude');
	$loc = round($lat,6).",".round($long,6);
	$ort = htmlentities(mysql_result($result12,0, 'location'));
	//echo "Ort: ".$ort;
}
ELSE
{
	unset($parameter);
	$parameter = $_COOKIE['parameter'];
	$param = preg_split("/,/", $parameter);
	IF(count($param) > '2')
	{
		$lat = $param[0];
		$long = $param[1];
		//der erste und zweite Parameter sind die Koordinaten,
		//der dritte und alle weiteren gehoeren lt. Definition zur Ortsbezeichnung,
		//denn diese kann aus dem Ortsnamen und ggf durch Kommata getrennte Ergaenzungen bestehen
		//(z.B.: Blankenburg, Gehren)
		$ort = '';
		FOR($K=2; $K<count($param); $K++)
		{
			$ort .= htmlentities($param[$K]);
			IF($K<count($param) - 1)
			{
				$ort .= ',';
			}
		}
		$loc = round($lat,6).",".round($long,6);
	}

	IF($lat == '' OR $long == '' OR $loc == '')	//wenn der Cookie noch nichts mitbringt...
	{
		$lat = 51.791232;
		$long = 10.952811;
		$ort = 'Blankenburg';
		$loc = round($lat,6).",".round($long,6);
	}
	//echo "Parameter: ".htmlentities($parameter).", Breite: ".$lat.", Laenge: ".$long.", Ort: ".$ort."<BR>";
}
?>

<div id="map"
	style="position: absolute; top: 10px; left: 10px; width: 530px; height: 370px"></div>
<div id="loc" style="position: absolute; top: 390px; left: 10px;">
<form name='lok' method='post' action='save_param.php'>
<input type='button' value='Position u. Ort speichern'
	OnClick='saveNewParam(lok.location.value, lok.ort.value, lok.loc_id.value, lok.pic_id.value)'
	style='width: 160px;'> &#160;&#160; <input type="text" name="location"
	id="location" style="width: 140px;" value="<?php echo $loc; ?>">
&#160;&#160; <input type="text" name='ort' style="width: 170px;"
	value="<?php echo "$ort"; ?>"> <input type="hidden" name="loc_id"
	value="<?php echo $loc_id; ?>"> <input type="hidden" name="pic_id"
	value="<?php echo $pic_id; ?>">

</div>

<script type="text/javascript">
	var lat=<?php echo $lat;?>;
	var lon=<?php echo $long;?>;
	
	var map=new GMap2(document.getElementById("map"));
	map.setCenter(new GLatLng(lat,lon),10); 	//Breite, Laenge, Zoom-Stufe (2 - Ganz Europa; 8 - Raum Magdeburg)
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
	var markerD=new GMarker(point,{icon:icon,draggable:true});
	map.addOverlay(markerD);
	markerD.enableDragging();
	GEvent.addListener(markerD,"drag",function(){document.getElementById("location").value=markerD.getPoint().toUrlValue();});
</script>

</form>
</body>
</html>
