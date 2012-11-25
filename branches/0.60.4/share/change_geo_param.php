<?php
IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
  	header('Location: ../../index.php');
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
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

<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<title>Geo-Daten bearbeiten...(Bild <?php echo $pic_id; ?>)</title> 

<style type="text/css">
/*v\:*{behavior:url(#default#VML)}*/
BODY{font-family:Arial;font-size:small;background-color:#FFFFFF}
A:hover{color:red;text-decoration:underline}
</style> 

<?php
//var_dump($_REQUEST);
include 'db_connect1.php';
$result1 = mysql_query( "SELECT * FROM $table2 WHERE pic_id = '$pic_id'");
echo mysql_error();
$vorh_location = mysql_result($result1,0,'City');
IF($vorh_location !== 'Ortsbezeichnung' AND $vorh_location !== '')
{
	//echo "Bild hat schon eine Ortsbezeichnung.<BR>";
	$long = mysql_result($result1,0, 'GPSLongitude');
	$lat = mysql_result($result1,0, 'GPSLatitude');
	$alt = mysql_result($result1,0, 'GPSAltitude');
	$loc = round($lat,6).",".round($long,6);
	$ort = mysql_result($result1,0, 'City');
}
ELSE
{
	//Bild hatte noch keine Geo-Referenzierung
	unset($parameter);

	@$parameter = $_COOKIE['parameter'];
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
			$ort .= $param[$K];
			IF($K<count($param) - 1)
			{
				$ort .= ',';
			}
		}
		$loc = round($lat,6).",".round($long,6);
	}
	ELSE
	{
		$loc = '';
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

<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">

var marker = null;

function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
}

function dragMarker(latlng)
{
	document.getElementById("location").value = roundNumber(marker.position.lat(), 6) + "," + roundNumber(marker.position.lng(), 6);
}

function initialize() 
{
	document.getElementsByName('send')[0].focus();
  var myLatlng = new google.maps.LatLng(<?php echo $lat.",".$long;?>);
  var myOptions = 
    {
    		zoom: 14,
    		center: myLatlng,
    		mapTypeId: google.maps.MapTypeId.ROADMAP
  	}
  var map = new google.maps.Map(document.getElementById("map"), myOptions);
  marker = new google.maps.Marker(
  	{
      position: myLatlng, 
      map: map,
      draggable: true
  	});   
  google.maps.event.addListener(marker, "drag", dragMarker);
  google.maps.event.addListener(marker, "dragend", dragMarker);
}
</script>
</head>

<body onload="javascript:initialize();">
 
<div id="map" style="position: absolute; top:10px;left:10px;width:530px; height:370px"></div>
<div id="loc" style="position: absolute; top:390px;left:10px;">
<form name = "lok" method = "post" action = "../html/recherche/save_new_param.php">
<input type="text" name = "location" id="location" style="width:140px; margin-right:10px;" value = "<?php echo $loc; ?>">
<input type="text" name = 'ort' style="width:180px; margin-right:10px;" value = "<?php echo $ort; ?>">
<input type = "hidden" name = "vorh_location" value = "<?php echo $vorh_location; ?>">
<input type = "hidden" name = "pic_id" value = "<?php echo $pic_id; ?>">
<input type="submit" name = "send" value="Position und Ort speichern" style="width:175px;">
</div>
</form>
</body>

</html>
