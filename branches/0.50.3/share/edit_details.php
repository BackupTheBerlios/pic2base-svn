<?php
IF (!$_COOKIE['login'])
{
	include '../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../index.php');
}
?>

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

$exiftool = buildExiftoolCommand($sr);

//var_dump($_GET);
if (array_key_exists('pic_id',$_GET))
{
	$pic_id = $_GET['pic_id'];
}
$result0 = mysql_query( "SELECT * FROM $table2 WHERE pic_id = '$pic_id'");
$num0 = mysql_num_rows($result0);
$row = mysql_fetch_array($result0);
$FileNameOri = $row['FileNameOri'];
$Owner = $row['Owner'];
//$FileNameOri = mysql_result($result0,isset($i0),'FileNameOri');
//$Owner = mysql_result($result0,isset($i0),'Owner');

$result2 = mysql_query( "SELECT * FROM $table1 WHERE id = '$Owner'");
$row = mysql_fetch_array($result2);
$vorname = $row['vorname'];
$name = $row['name'];
$u_name = $row['username'];
//$vorname = mysql_result($result2, $i2, 'vorname');
//$name = mysql_result($result2, $i2, 'name');
//$u_name = mysql_result($result2, $i2, 'username');

unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = preg_split('#,#',$_COOKIE['login']);
//echo $c_username;
}

//Ermittlung aller writable gesetzten Tags in der exif_protect-Tabelle (Obermenge):
$result1 = mysql_query( "SELECT * FROM $table5 WHERE writable = '1'");
$num1 = mysql_num_rows($result1);
//echo $num1."<br>";
FOR($i1='0'; $i1<$num1; $i1++)
{
	$field_name = mysql_result($result1, $i1, 'field_name');
	$writable_tags[$field_name] = $field_name;
}
//print_r($writable_tags);

echo "<FORM name='edit_details' method='post' action='save_meta_data_action.php'>

	<TABLE border = '0' style='width:450px;background-color:#FFFFFF' align = 'center'>
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'>
		</TD>
	</TR>
	
	<TR class='normal'>
		<TD class='normal' colspan = '2'>
		Alle editierbaren Details zum Bild ".$pic_id."
		</TD>
	</TR>
	
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
	
	<TR class='normal' style='background-color:lightgreen;'>
		<TD class='normal' colspan = '2' align='left'>
		--- editierbare Daten ----
		</TD>
	</TR>";
	
//Ermittlung aller Tags in der Bild-Datei
$FN = strtolower($pic_path."/".restoreOriFilename($pic_id, $sr));
//echo $file."<BR>";
$exif_daten = shell_exec($exiftool." -g -s -x 'Directory' ".$FN);
//echo $exif_daten."<BR>";
$info_arr = explode(chr(10), $exif_daten);
//Umschreibung des Info-Arrays in die Form Schl�ssel=$tag / Wert=$value
$INFO_ARR = array();

FOREACH($info_arr AS $IA)
{
	//echo $IA."<BR>";
	$pos = strpos($IA, ':');
	$tag = trim(substr($IA, 0, $pos));
	//$value = htmlspecialchars(trim(substr($IA, ($pos + 1))), ENT_QUOTES);
	$value = trim(substr($IA, ($pos + 1)));
	$INFO_ARR[$tag] = $value;
}
//print_r($INFO_ARR)."<BR>";
$n=0;
if ( isset($writable_tags) )
{
	FOREACH($writable_tags AS $WT)	//ueber alle in der Tabelle freigegebenen Tags wird geprueft, welche davon im Bild enthalten sind:
	{
		//Steuerung der Zeilen-Hintergrundfarbe
		IF(bcmod($n,2) == 0)
		{
			$bgcolor = 'lightgrey';
		}
		ELSE
		{
			$bgcolor = 'white';
		}
	
		$wt = str_replace('_','-',$WT);		//$WT Tag-Darstellungsform in der Tabelle (Unterstrich); $wt 							Tag-Darstellungsform im Bild (Bindestrich)
		IF(@$INFO_ARR[$wt] !== NULL)		//Wenn im Bild-Array das freigegebene Array vorkommt, wird der Wert 	
							//ermittelt, ggf. dieser zuvor formatiert
		{
			//echo "Tag in Tabelle: ".$WT.", Tag im Bild: ".$WT."<BR>";
			$value = $INFO_ARR[$wt];
			SWITCH($wt)
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
			//echo $wt." / ".$INFO_ARR[$wt]."<BR>";
			//Fallunterscheidungen:
			SWITCH($wt)
			{
				//Es handelt sich um ein Datumsfeld:
				CASE (stristr($wt,'date') == true):
				echo "	<TR class='normal' style='height:3px;' bgcolor = '$bgcolor';>
				<TD class='liste2' style='width:225px;'><FONT COLOR='red'>".$wt." <blink>*)</blink></FONT></TD>
				<TD class='liste2' style='width:225px;'><INPUT type='text' name='$wt' value='$value' style='width:220px; height:15px;'></TD>
				</TR>";
				break;
			
				//Es handelt sich um Textfelder:
				CASE ($wt == 'UserComment' OR $wt == 'Caption-Abstract' OR $wt == 'Copyright'):
				echo "	<TR class='normal' style='height:3px;' bgcolor = '$bgcolor';>
				<TD class='liste2' style='width:225px;'><FONT COLOR='red'>".$wt."</FONT></TD>
				<TD class='liste2' style='width:225px;'><textarea name='$wt' style='width:220px; height:100px;'>".$value."</textarea></TD>
				</TR>";
				break;
			
				default:
				echo "	<TR class='normal' style='height:3px;' bgcolor = '$bgcolor';>
				<TD class='liste2' style='width:225px;'><FONT COLOR='red'>".$wt."</FONT></TD>
				<TD class='liste2' style='width:225px;'><INPUT type='text' name='$wt' value='$value' style='width:220px; height:15px;'></TD>
				</TR>";
				break;
			}
		}
		ELSE
		{
			//echo "Tag ".$wt." ist NICHT im Bild-Array enthalten.<BR>";
			SWITCH($wt)
			{
				//Es handelt sich um ein Datumsfeld:
				CASE (stristr($wt,'date') == true):
				echo "	<TR class='normal' style='height:3px;' bgcolor = '$bgcolor';>
				<TD class='liste2' style='width:225px;'><FONT COLOR='red'>".$wt." <blink>*)</blink></FONT></TD>
				<TD class='liste2' style='width:225px;'><INPUT type='text' name='$wt' style='width:220px; height:15px;'></TD>
				</TR>";
				break;
			
				//Es handelt sich um Textfelder:
				CASE ($wt == 'UserComment' OR $wt == 'Caption-Abstract' OR $wt == 'Copyright'):
				echo "	<TR class='normal' style='height:3px;' bgcolor = '$bgcolor';>
				<TD class='liste2' style='width:225px;'><FONT COLOR='red'>".$wt."</FONT></TD>
				<TD class='liste2' style='width:225px;'><textarea name='$wt' style='width:220px; height:100px;'></textarea></TD>
				</TR>";
				break;
			
				default:
				echo "	<TR class='normal' style='height:3px;' bgcolor = '$bgcolor';>
				<TD class='liste2' style='width:225px;'><FONT COLOR='red'>".$wt."</FONT></TD>
				<TD class='liste2' style='width:225px;'><INPUT type='text' name='$wt' style='width:220px; height:15px;'></TD>
				</TR>";
				break;
			}
		}		
		$n++;
	}
}

echo "	
	<TR class='normal' style='background-color:yellow;'>
		<TD class='normal' colspan = '2' align='center'><b>--- W&#160;I&#160;C&#160;H&#160;T&#160;I&#160;G&#160;! ---</b></TD>
	</TR>
	
	<TR class='normal' style='background-color:yellow;'>
		<TD class='normal' align='left'><FONT COLOR='red'><blink>*)</blink></FONT>&#160;Format f&uuml;r Datum-Eingaben:</TD>
		<TD class='normal' align='left'>YYYY:MM:TT HH:MM:SS</TD>
		</TD>
	</TR>
	
	<TR class='normal' style='background-color:yellow;'>
		<TD class='normal' align='left'>Bei Text-Eingaben:</TD>
		<TD class='normal' align='left'>max. Textl&auml;nge beachten</TD>
	</TR>
	
	<TR class='normal' style='background-color:lightgreen;'>
		<TD class='normal' colspan = '2' align='left'>
		</TD>
	</TR>
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'>
		</TD>
	</TR>
	
	<TR class='normal'>
		<TD class='normal'><INPUT type='submit' value='Speichern' style='width:150px;'></TD>
		<TD class='normal'><INPUT type='button' value='Bearbeitung abbrechen' onClick='window.close()' style='width:150px;'></TD>
	</TR>
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'>
		</TD>
	</TR>
</TABLE>
<input type='hidden' name='pic_id' value='$pic_id'>
</FORM>";
//print_r($writable_tags);
?>
</body>
</html>