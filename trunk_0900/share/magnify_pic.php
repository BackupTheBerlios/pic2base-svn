<?php
IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
  	header('Location: ../../index.php');
}
$hor_preview_size = $_COOKIE['hor_preview_size'];
$vert_preview_size = $_COOKIE['vert_preview_size'];
include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
echo "<fieldset id='fieldset_spalte2' style='background-color:none; margin-top:10px;'>
		<legend style='color:blue; font-weight:bold;'>Vorschau des gew&auml;hlten Bildes</legend>";

		if( array_key_exists('pic_id',$_GET))
		{
			$pic_id = $_GET['pic_id'];
		}
		
		$result1 = mysql_query( "SELECT * FROM $table2 WHERE pic_id = '$pic_id'");
		echo mysql_error();
		$FileNameHQ = mysql_result($result1, 0, 'FileNameHQ');
		@$parameter_v=getimagesize($sr.'/images/vorschau/hq-preview/'.$FileNameHQ);
		$breite = $parameter_v[0];
		$hoehe = $parameter_v[1];
		$ratio = $breite / $hoehe;
		IF($ratio > '1')
		{
			//Breitformat
			$Breite = $hor_preview_size;
			$Hoehe = number_format($Breite / $ratio,0,'.',',');
		}
		ELSE
		{
			//Hochformat
			$Hoehe = $vert_preview_size;
			$Breite = number_format($Hoehe * $ratio,0,'.',',');
		}
		echo "<CENTER>
		<img src='$inst_path/pic2base/images/vorschau/hq-preview/$FileNameHQ' alt='$FileNameHQ' width='$Breite', height='$Hoehe'>
		</CENTER>
	</fieldset>";
?>