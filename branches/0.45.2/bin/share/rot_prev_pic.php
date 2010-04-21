<script language="javascript" type="text/javascript" src="functions/ShowPicture.js"></script>
<?php
include 'global_config.php';

//var_dump($_REQUEST);

IF(array_key_exists('Orientation', $_REQUEST))
{
	$Orientation = $_REQUEST['Orientation'];
}

IF(array_key_exists('FileNameV', $_REQUEST))
{
	$FileNameV = $_REQUEST['FileNameV'];
}

IF(array_key_exists('pic_id', $_REQUEST))
{
	$pic_id = $_REQUEST['pic_id'];
}

IF(array_key_exists('fs_hoehe', $_REQUEST))
{
	$fs_hoehe = $_REQUEST['fs_hoehe'];
}

SWITCH($Orientation)
{
	case '3':
	//Das Vorschaubild muss 180 gedreht werden:
	$command = "/usr/bin/convert ".$pic_thumbs."/".$FileNameV." -rotate 180 ".$pic_thumbs."/".$FileNameV."";
		$output = shell_exec($command);
	break;
	
	case '6':
	//Das Vorschaubild muss 90 im Uhrzeigersinn gedreht werden:
	$command = "/usr/bin/convert ".$pic_thumbs."/".$FileNameV." -rotate 90 ".$pic_thumbs."/".$FileNameV."";
		$output = shell_exec($command);
	break;
	
	case '8':
	//echo "drehe Thumb-Bild ".$pic_thumbs."/".$FileNameV."<BR>";
	//Das Vorschaubild muss 90 entgegen dem Uhrzeigersinn gedreht werden:
	$command = "/usr/bin/convert ".$pic_thumbs."/".$FileNameV." -rotate 270 ".$pic_thumbs."/".$FileNameV."";
		$output = shell_exec($command);
	break;
}
$time = time();

echo "
<SPAN style='cursor:pointer;' onClick='rotPrevPic(\"8\", \"$FileNameV\", \"$pic_id\", \"$fs_hoehe\")'><img src=\"$inst_path/pic2base/bin/share/images/90-ccw.gif\" width=\"10\" height=\"10\" style='margin-right:10px;' title='Vorschaubild 90&#176; links drehen' /></span>
<SPAN style='cursor:pointer;' onMouseOver='getDetails(\"$pic_id\",\"$base_file\",\"$mod\",\"$form_name\")'>
<img src=\"$inst_path/pic2base/images/vorschau/thumbs/$FileNameV?time=$time\" style='height:$fs_hoehe;' />
</span>
<SPAN style='cursor:pointer;' onClick='rotPrevPic(\"6\", \"$FileNameV\", \"$pic_id\", \"$fs_hoehe\")'><img src=\"$inst_path/pic2base/bin/share/images/90-cw.gif\" width=\"10\" height=\"10\" style='margin-left:10px;' title='Vorschaubild 90&#176; rechts drehen' /></span>";

?>