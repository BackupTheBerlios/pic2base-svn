<?php
IF (!$_COOKIE['login'])
{
include '../../share/global_config.php';
//var_dump($sr);
  header('Location: ../../../index.php');
}
?>

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

<div id="map" style="position: absolute; top:3px;left:3px;width:<?php echo $width;?>px; height:<?php echo $height;?>px"></div>

<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
  function initialize() 
  {
    var myLatlng = new google.maps.LatLng(<?php echo $lat.",".$long;?>);
    var myOptions = 
        {
      		zoom: 14,
      		center: myLatlng,
      		mapTypeId: google.maps.MapTypeId.ROADMAP
    	}
    var map = new google.maps.Map(document.getElementById("map"), myOptions);

    var marker = new google.maps.Marker({
        position: myLatlng, 
        map: map,
        title:""
    });   
  }
</script>

</head>

<body onload="javascript:initialize();">
</body>
</html>