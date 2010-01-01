	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Bild-Details</title>
  <meta name="GENERATOR" content="Quanta Plus">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="Content-Style-Type" content="text/css">
  <link rel=stylesheet type="text/css" href='../css/format1.css'>
  <link rel="shortcut icon" href="images/favicon.ico">
</head>
<body style='background-color:#999999'>
<?php
// verwendet als Popup-Fenster mit den Detail-Infos zum Bild

include_once 'global_config.php';
include_once 'db_connect1.php';
include_once $sr.'/bin/share/functions/main_functions.php';
//$pic_id = '10';

//var_dump($_GET);
//var_dump($_POST);
if( array_key_exists('pic_id',$_GET) )
{
	$pic_id = $_GET['pic_id'];
	//echo "Bild-ID: ".$pic_id;
}

if( array_key_exists('view',$_REQUEST) )
{
	$view = $_REQUEST['view'];
}

$result0 = mysql_query( "SELECT * FROM $table2 WHERE pic_id = '$pic_id'");
$num0 = mysql_num_rows($result0);
$row = mysql_fetch_array($result0);
$FileNameHQ = $row['FileNameHQ'];
$FileName = $row['FileName'];
$FileNameOri = $row['FileNameOri'];
$Owner = $row['Owner'];
$ranking = $row['ranking'];
$note = $row['note'];
$md5sum = $row['md5sum'];
$loc_id = $row['loc_id'];

IF($loc_id !== '0' AND $loc_id !== '')
{
	$result1 = mysql_query( "SELECT * FROM $table12 WHERE loc_id = '$loc_id'");
	$location = mysql_result($result1, isset($i1), 'location');
}
$result2 = mysql_query( "SELECT * FROM $table1 WHERE id = '$Owner'");
$row = mysql_fetch_array($result2);
$vorname = $row['vorname'];
$name = $row['name'];
$u_name = $row['username'];

unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = preg_split('#,#',$_COOKIE['login']);
//echo $c_username;
}

//############   Erstellung der Histogramme, falls nicht bereits vorhanden:   #######################
// Histogramme werden im Ordner /images/histogramme als XXXXX_hist_f.jpg abgelegt, wobei XXXXX f�r pic_id steht und f f�r die Farbe (rot, gr�n, blau oder wei� -> rgbw)
generateHistogram($pic_id,$FileNameHQ,$sr);

//############   Histogramm-Erstellung beendet   ####################################################
$hist_file_r = $pic_id.'_hist_0.gif';
$hist_file_g = $pic_id.'_hist_1.gif';
$hist_file_b = $pic_id.'_hist_2.gif';
$hist_file = $pic_id.'_hist.gif';
//echo $hist_file;
echo "<TABLE border = '0' style='width:450px;background-color:#FFFFFF' align = 'center'>
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'>
		</TD>
	</TR>";
	
	IF($view == 'kompact')
	{
	echo "
	<TR class='normal'>
		<TD class='normal' colspan = '2'>
		Ausgew&auml;hlte Details zum Bild ".$pic_id." >>> <a href=\"details.php?pic_id=$pic_id&view=all\">alle</a> Details
		</TD>
	</TR>";
	}
	ELSEIF($view == 'all')
	{
	echo "
	<TR class='normal'>
		<TD class='normal' colspan = '2'>
		Alle Details zum Bild ".$pic_id." >>> zur <a href=\"details.php?pic_id=$pic_id&view=kompact\">Kompakt-Ansicht</a>
		</TD>
	</TR>";
	}
	
	echo "
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'>
		</TD>
	</TR>
	
	<TR class='normal' style='background-color:lightgreen;'>
		<TD class='normal' colspan = '2' align='left'>
		--- allgemeine Daten ----
		</TD>
	</TR>
	
	<TR style='background-color:#FFFFFF';>
		<TD class='liste2' style='width:225px;'>Original-Dateiname</TD>
		<TD class='liste2' style='width:225px;'>".$FileNameOri."</TD>
	</TR>
	
	<TR style='background-color:#DDDDDD';>
		<TD class='liste2' style='width:225px;'>Eigent&uuml;mer</TD>
		<TD class='liste2' style='width:225px;'>".$vorname." ".$name."</TD>
	</TR>
	
	<TR style='background-color:#FFFFFF';>
		<TD class='liste2' style='width:225px;'>Downloads</TD>
		<TD class='liste2' style='width:225px;'>".$ranking."</TD>
	</TR>
	
	<TR style='background-color:#DDDDDD';>
		<TD class='liste2' style='width:225px;'>Bewertung</TD>
		<TD class='liste2' style='width:225px;'>".$note."</TD>
	</TR>
	
	<TR style='background-color:#FFFFFF';>
		<TD class='liste2' style='width:225px;'>Pr&uuml;fsumme</TD>
		<TD class='liste2' style='width:225px;'>".$md5sum."</TD>
	</TR>
	
	<TR class='normal' style='background-color:lightgreen;'>
		<TD class='normal' colspan = '2' align='left'>
		--- Histogramme ----
		</TD>
	</TR>
	
	<TR>
	<TD colspan = '2'>
	
	<TABLE border='0' style='width:445px;' align = 'center'>
	<TR style='height:100px;'>
	<TD style='border-bottom:solid; border-width:5px; border-color:red; width:111px;'>
	<img src=\"../../images/histogramme/$hist_file_r\" width='103px' alt='Histogramm (R)' title='Rot-Histogramm' border='1' />
	</TD>
	<TD style='border-bottom:solid; border-width:5px; border-color:green; width:111px;'>
	<img src=\"../../images/histogramme/$hist_file_g\" width='103px' alt='Histogramm (G)' title='Gr&uuml;n-Histogramm' border='1' />
	</TD>
	<TD style='border-bottom:solid; border-width:5px; border-color:blue; width:111px;'>
	<img src=\"../../images/histogramme/$hist_file_b\" width='103px' alt='Histogramm (B)' title='Blau-Histogramm' border='1' />
	</TD>
	<TD style='border-bottom:solid; border-width:5px; border-color:lightgrey; width:111px;'>
	<img src=\"../../images/histogramme/$hist_file\" width='103px' alt='Histogramm' title='Grauwert-Histogramm' border='1' />
	</TD>
	</TR>
	</TABLE>
	
	</TD>
	</TR>";

//es werden all diejenigen Tags dargestellt, die das Bild mitgebracht hat und deren Daten in der Tabelle meta_data hinterlegt sind und zus�tzlich die editierbaren Felder:
//zuerst werden alle editierbaren Meta-Datenfelder ermittelt und in ein Array geschrieben:
$result4 = mysql_query( "SELECT field_name FROM $table5 WHERE writable = '1'");
$num4 = mysql_num_rows($result4);
IF($num4 > '0')
{
	$writable_fields = array();
	FOR($i4='0'; $i4<$num4; $i4++)
	{
		$field_name = mysql_result($result4, $i4, 'field_name');
		$writable_fields[$field_name] = $field_name;
	}
}
//print_r($writable_fields);

//dann werden alle in der Kompact-Ansicht lesbaren Meta-Datenfelder ermittelt und in ein Array geschrieben:

$result5 = mysql_query("SELECT field_name FROM $table5 WHERE viewable = '1'");

echo mysql_error();
$num5 = mysql_num_rows($result5);
IF($num5 > '0')
{
	$viewable_fields = array();
	FOR($i5='0'; $i5<$num5; $i5++)
	{
		$field_name = mysql_result($result5, $i5, 'field_name');
		$viewable_fields[$field_name] = $field_name;
	}
}
//print_r($viewable_fields);

//$file = $pic_path."/".$FileName;
$file = strtolower($pic_path."/".restoreOriFilename($pic_id, $sr));
//echo $file."<BR>";
$exif_daten = shell_exec($et_path."/exiftool -g -s -x 'Directory' ".$file);
//echo $exif_daten."<BR>";
$info_arr = explode(chr(10), $exif_daten);
//print_r($info_arr)."<BR>";

$n = 0;
//var_dump($info_arr);
if (!isset($editable))
{
	$editable = '0';
}
FOREACH($info_arr AS $IA)
{
	IF(bcmod($n,2) == 0)
	{
		$bgcolor = 'lightgrey';
	}
	ELSE
	{
		$bgcolor = 'white';
	}
	//echo $IA."<BR>";
	$pos = strpos($IA, ':');
	$tag = trim(substr($IA, 0, $pos));
	//$value = htmlspecialchars(trim(substr($IA, ($pos + 1))), ENT_QUOTES);
	$value = trim(substr($IA, ($pos + 1)));
	SWITCH($tag)
	{
		CASE 'GPSAltitude':
		$val_arr = explode(' ',$value);
		$value = $val_arr[0];
		break;
		
		CASE 'GPSLongitude':
		$val_arr = explode(' ',$value);
		$value = $val_arr[0] + ($val_arr[2] / 60) + ($val_arr[3] / 3600);
		break;
		
		CASE 'GPSLatitude':
		$val_arr = explode(' ',$value);
		$value = $val_arr[0] + ($val_arr[2] / 60) + ($val_arr[3] / 3600);
		break;
		
		CASE 'GPSPosition':
		$value = $location;
		break;
	}
	
	IF($tag == '' AND $value !== '')
	{
		echo "<TR class='normal' style='height:3px;' bgcolor = 'lightgreen'>";
		echo "<TD class='liste2' colspan = '2'>".$value."</TD>
		</TR>";
	}
	ELSEIF($tag !== '')
	{
		$TAG = str_replace('-','_',$tag);
		$result3 = mysql_query( "SELECT * FROM $table5 WHERE field_name = '$TAG'");
		$num3 = mysql_num_rows($result3);
		$color = 'black';
		IF($num3 == '1')
		{
			$writable = mysql_result($result3, isset($i3), 'writable');
			IF($writable == '1')
			{
				$color = 'red';
				$editable = '1';
				//wenn hier ein editierbares Tag dargestellt wird, wird dies aus dem Array aller editierbaren Tags entfernt:
				unset($writable_fields[$TAG]);
			}
			ELSE
			{
				$color = 'black';
			}
		}
		
		SWITCH($view)
		{
			case 'kompact':
			IF(in_array($tag,$viewable_fields))
			{
				echo "<TR class='normal' style='height:3px;' bgcolor = '$bgcolor';>";
				echo "<TD class='liste2' style='width:225px;'><FONT COLOR='$color'>".$tag."</FONT></TD>
				<TD class='liste2' style='width:225px;'>".$value."</TD>
				</TR>";
			}
			break;
			
			case 'all':
			echo "<TR class='normal' style='height:3px;' bgcolor = '$bgcolor';>";
			echo "<TD class='liste2' style='width:225px;'><FONT COLOR='$color'>".$tag."</FONT></TD>
			<TD class='liste2' style='width:225px;'>".$value."</TD>
			</TR>";
			break;
		}
	}
	$n++;
}

echo "
	<TR style='background-color:lightgreen;'>
		<TD class='liste2' style='width:450px;' colspan='2' >---- Link zum HQ-Bild ----</TD>
	</TR>
	
	<TR style='background-color:white;'>
		<TD class='liste2' style='width:450px;' colspan='2' >http://".$_SERVER['SERVER_NAME']."".$inst_path."/pic2base/images/vorschau/hq-preview/".$FileNameHQ."</TD>
	</TR>
	
	<TR style='background-color:lightgreen;'>
		<TD class='liste2' style='width:450px;' colspan='2' >---- monochrome Vorschau ----</TD>
	</TR>
	
	<TR style='background-color:white;'>
		<TD class='liste2' style='width:450px;text-align:center;' colspan='2' ><img src=\"../../images/monochrome/".$pic_id."_mono.jpg\" width='400px' alt='Monochrome-Ansicht' title='Monochrome-Ansicht' border='0' /></TD>
	</TR>
	
	<!--
	<TR style='background-color:white;'>
		<TD class='liste2' style='width:450px;' colspan='2' >Link zum <a href=http://".$_SERVER['SERVER_NAME'].$inst_path."/pic2base/images/originale/".restoreOriFilename($pic_id, $sr).">Originalbild</a></TD>
	</TR>
	-->
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'>
		</TD>
	</TR>";
	//echo count($writable_fields);
	IF(($editable == '1' OR count(isset($writable_fields)) > 0) AND $u_name === $c_username)
	{
		echo "
		<TR class='normal'>
			<TD class='normal'>Meta-Daten editieren&#160;&#160;
			<A HREF='edit_details.php?pic_id=$pic_id'><img src=\"images/edit.gif\" width=\"15\" height=\"15\" border='none' title='Meta-Daten editieren' style='vertical-align:sub;' /></A>
			</TD>
			<TD class='normal'>Fenster schliessen&#160;&#160;
			<A HREF='javascript:window.close()'><img src=\"images/close.gif\" width=\"15\" height=\"15\" border='none' title='Fenster schliessen' style='vertical-align:sub;' /></A>
			</TD>
		</TR>";
	}
	ELSE
	{
		echo "
		<TR class='normal'>
			<TD class='normal' colspan = '2' align='right'>Fenster schliessen&#160;&#160;
			<A HREF='javascript:window.close()'><img src=\"images/close.gif\" width=\"15\" height=\"15\" border='none' title='Fenster schliessen' style='vertical-align:sub; margin-right:10px;' /></A>
			</TD>
		</TR>";
	}
	echo "
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'>
		</TD>
	</TR>
</TABLE>";
//print_r($writable_fields);
?>
</body>
</html>