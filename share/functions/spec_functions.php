<?php
function getNikonD70Params($new_filename,$ext)
{
	//wird verwendet für D70, D80
	include '../../share/global_config.php';
	//Ermittlung der mitgelieferten Bildparameter:
	$file_info = pathinfo($new_filename);
	$base_name = $file_info['basename'];
	$file_name_raw = str_replace($file_info['extension'],'',$base_name).$ext;	
	$cmd = $dcraw_path."/dcraw -i -c -v ".$pic_path."/".$file_name_raw;
	$pic_param = shell_exec($cmd);
	$param_arr = split("\n",$pic_param);
	/*
	FOREACH($param_arr AS $parm)
	{
		echo $parm."<BR>";
	}
	*/
	$date = split(' ',str_replace(': ','',strstr($param_arr[2],':')));
	/*
	$z = '0';
	FOREACH($date AS $element)
	{
		echo $z.": ".$element."<BR>";
		$z++;
	}
	*/
	//Das ist wohl eine Eigenart der NIKON-Bauer!?!:
	IF($date[2] == '')
	{
		$tag = $date[3];
		$monat = $date[1];
		$jahr = $date[5];
		$zeit = $date[4];
	}
	ELSE
	{
		$tag = $date[2];
		$monat = $date[1];
		$jahr = $date[4];
		$zeit = $date[3];
	}
	//echo "Jahr: ".$jahr."<BR>";
	//echo "Monat: ".$monat."<BR>";
	//echo "Tag: ".$tag."<BR>";
	
	SWITCH($monat)
	{
		CASE 'Jan':
		$monat = '01';
		break;
		
		CASE 'Feb':
		$monat = '02';
		break;
		
		CASE 'Mar':
		$monat = '03';
		break;
		
		CASE 'Apr':
		$monat = '04';
		break;
		
		CASE 'May':
		$monat = '05';
		break;
		
		CASE 'Jun':
		$monat = '06';
		break;
		
		CASE 'Jul':
		$monat = '07';
		break;
		
		CASE 'Aug':
		$monat = '08';
		break;
		
		CASE 'Sep':
		$monat = '09';
		break;
		
		CASE 'Oct':
		$monat = '10';
		break;
		
		CASE 'Nov':
		$monat = '11';
		break;
		
		CASE 'Dec':
		$monat = '12';
		break;
	}
	
	IF($tag < '10')
	{
		$tag = '0'.$tag;
	}
	
	$dto = $jahr."-".$monat."-".$tag." ".$zeit;	//$dto : DateTimeOriginal
	//echo $dto."<BR>";
	$cam = str_replace(': ','',strstr($param_arr[3],':'));
	//echo $cam."<BR>";
	$iso = str_replace(': ','',strstr($param_arr[4],':'));
	//echo $iso."<BR>";
	$len = strlen(str_replace(': ','',strstr($param_arr[5],':'))) - 6;
	$shutter = substr(str_replace(': ','',strstr($param_arr[5],':')),0,$len);
	//echo $shutter."<BR>";
	$aperture = str_replace(': ','',strstr($param_arr[6],':'));
	//echo $aperture."<BR>";
	$focal_length = str_replace(': ','',strstr($param_arr[7],':'));
	//echo $focal_length."<BR>";
	$focal_length_35 = $focal_length * 1.5;
	return array($new_filename, $dto, $cam, $iso, $shutter, $aperture, $focal_length, $focal_length_35);
}
?>