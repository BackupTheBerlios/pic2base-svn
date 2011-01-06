<?php
IF (!$_COOKIE['login'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../../index.php');
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Neue Konvertierungs-Parameter</title>
  <meta name="GENERATOR" content="Quanta Plus">
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Content-Style-Type" content="text/css">
  <link rel=stylesheet type="text/css" href='../../css/format1.css'>
  <link rel="shortcut icon" href="../../share/images/favicon.ico">
  <script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
</head>

<script language="JavaScript">
function eraseCookie(name, domain, path)
{
   var cook=name + "=; expires=Thu, 01-Jan-70 00:00:10 GMT; "
   cook += (domain) ? "domain="+domain : "";
   cook += (path) ? "path="+path : "";
   document.cookie = cook;
   //document.cookie = 'params=0;path=/admin/pic2base/bin/html/recherche/;expires=Thu, 01-Jan-70 00:00:01 GMT';
   //alert(cook);
   location.reload ();
}
</script>

<body style='background-color:#999999'>
<?php
// verwendet als Popup-Fenster zur Festlegung der neuen Konvertierungs-Parameter fuer das RAW-Bild

unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
	//echo $c_username;
	$benutzername = $c_username;
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/ajax_functions.php';

$exiftool = buildExiftoolCommand($sr);

//$pic_id = '10';

if ( array_key_exists('pic_id',$_GET) )
{
	$pic_id = $_GET['pic_id'];
}

$result0 = mysql_query( "SELECT * FROM $table2 WHERE pic_id = '$pic_id'");
$num0 = mysql_num_rows($result0);
$FileNameOri = mysql_result($result0, isset($i0), 'FileNameOri');
$FileName = mysql_result($result0, isset($i0), 'FileName');

$file = strtolower($pic_path."/".restoreOriFilename($pic_id, $sr));
$Orientation = trim(shell_exec($exiftool." -n -s -S '-Orientation' ".$file));

//Erzeugung des RAW-Dateinamens:
$FileNameArray = explode('.', $FileName);
$FileNamePrefix = $FileNameArray[0];	//der Teil des internen Dateinamens vor dem Punkt
$FileNameOriArray = explode('.', $FileNameOri);
$FileNameExtension = $FileNameOriArray[count($FileNameOriArray)-1]; //alles, was hinter dem letzten Punkt des Original-Dateinamens steht
$FileNameRaw = $FileNamePrefix.".".$FileNameExtension;
//echo "Raw-Dateiname: ".$FileNameRaw;

//wenn ein Cookie mit gespeicherten Parametern existiert, werden diese vor-ausgewaehlt:
//IF ($_COOKIE['params'])
IF(array_key_exists('params', $_COOKIE))
{
	$param_arr = preg_split('# #',$_COOKIE['params']);
	
	$pos1 = array_search('-H',$param_arr);
	IF($pos1 !== false)
	{
		$next_pos1 = $pos1 + 1;
		$hl_mode = $param_arr[$next_pos1];
		$hl_text = $hl_mode;
		//echo $hl_mode."<BR>";
	}
	ELSE
	{
		$hl_mode = ' ';
		$hl_text = 'Highlight-Clipping';
	}
	
	$pos2 = array_search('-b',$param_arr);
	IF($pos2 !== false)
	{
		$next_pos2 = $pos2 + 1;
		$gamma_mode = $param_arr[$next_pos2];
		//echo $gamma_mode."<BR>";
	}
	ELSE
	{
		$gamma_mode = 'automatisch';
	}
	
	$pos3 = array_search('-o',$param_arr);
	IF($pos3 !== false)
	{
		$next_pos3 = $pos3 + 1;
		$targ_color = "-o ".$param_arr[$next_pos3];
	}
	
	$pos4 = array_search('-q',$param_arr);
	IF($pos4 !== false)
	{
		$next_pos4 = $pos4 + 1;
		$col_inter = "-q ".$param_arr[$next_pos4];
	}
	
	$pos5 = array_search('-t',$param_arr);
	IF($pos5 !== false)
	{
		$next_pos5 = $pos5 + 1;
		$rota = $param_arr[$next_pos5];
		$rota_text = $rota;
	}
	ELSE
	{
		$rota = ' ';
		$rota_text = 'lt. Kamera';
	}
	
	IF(in_array('-w',$param_arr))
	{
		$wb_mode = '-w';
	}
	ELSEIF(in_array('-a',$param_arr))
	{
		$wb_mode = '-a';
	}
	
	IF(in_array('-h',$param_arr))
	{
		$konv_mode = '-h';
	}
	ELSE
	{
		$konv_mode = '';
	}
	
	$pos8 = array_search('-cont',$param_arr);
	IF($pos8 !== false)
	{
		$next_pos8 = $pos8 + 1;
		$contrast = $param_arr[$next_pos8];
		$contrast_val = $contrast;
	}
	ELSE
	{
		$contrast_val = '0';
	}

	echo "<FORM name='params'>
	<TABLE border = '1' style='width:750px; background-color:#FFFFFF' align = 'center'>
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '3'>
		</TD>
	</TR>
	
	<TR class='normal' style='height:25px;'>
		<TD class='normal' bgcolor='#FFFFFF' colspan = '3'>
		Legen Sie hier bitte die neuen Konvertierungs-Parameter fest:
		</TD>
	</TR>
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '3'>
		</TD>
	</TR>
	
	<TR class='normal' style='height:25px;'>
		<TD class='normal' bgcolor='#EEEEAA' colspan='2' style='width:250px;'>Gespeicherte Parameter</TD>
		<TD class='normal' bgcolor='#EEEEAA' style='width:500px;'>Vorschau f&uuml;r Bild ".$pic_id." (".$FileNameOri." / ".$FileName.")</TD>
	</TR>	
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#EEEEAA' colspan = '2'></TD>
		<TD class='normal' bgcolor='#FF9900'></TD>
	</TR>
	
	<TR class='normal' style='height:22px;'>
		<TD class='normal' bgcolor='#EEEEAA' style='text-align:left; vertical-align:center; width:130px'>Highlight-Mode:</TD>
		<TD class='normal' bgcolor='#EEEEAA'  style='text-align:left; vertical-align:center; width:120px'>".$hl_text."</TD>
		<TD class='normal' rowspan='16' style='width:500px; height:450px; background-color:#DDDDDD; text-align:center; vertical-align:middle'><div id='new_preview'>Die Erstellung der Vorschaubilder ist ein sehr aufw&auml;ndiger Prozess,<BR>welcher je nach Rechenleistung des Servers einige Zeit in Anspruch nimmt.<BR>Die Berechnung ist abgeschlossen, wenn Sie hier<BR>in der rechten Fensterh&auml;lfte das neu berechnete Bild sehen.<BR><BR>Haben Sie bitte ein wenig Geduld.</div></TD>
	</TR>
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'></TD>
	</TR>
	
	<TR class='normal' style='height:22px;'>
		<TD class='normal' bgcolor='#EEEEAA' style='width:130px;text-align:left; vertical-align:center'>Gamma-Korrektur:</TD>
		<TD class='normal' bgcolor='#EEEEAA' style='text-align:left; vertical-align:center'>".$gamma_mode."</TD>
	</TR>
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'></TD>
	</TR>
	
	<TR class='normal' style='height:22px;'>
		<TD class='normal' bgcolor='#EEEEAA' style='width:130px;text-align:left; vertical-align:center'>Ziel-Farbraum:</TD>
		<TD class='normal' bgcolor='#EEEEAA' style='text-align:left; vertical-align:center'>".$targ_color."</TD>
	</TR>
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'></TD>
	</TR>
	
	<TR class='normal' style='height:22px;'>
		<TD class='normal' bgcolor='#EEEEAA' style='width:130px;text-align:left; vertical-align:center'>Farb-Interpolation:</TD>
		<TD class='normal' bgcolor='#EEEEAA' style='text-align:left; vertical-align:center'>".$col_inter."</TD>
	</TR>
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'></TD>
	</TR>
	
	<TR class='normal' style='height:22px;'>
		<TD class='normal' bgcolor='#EEEEAA' style='width:130px;text-align:left; vertical-align:center'>
		Drehung/Spiegel.:
		</TD>
		<TD class='normal' bgcolor='#EEEEAA' style='text-align:left; vertical-align:center'>".$rota_text."
		</TD>
	</TR>
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'></TD>
	</TR>
	
	<TR class='normal' style='height:22px;'>
		<TD class='normal' bgcolor='#EEEEAA' style='width:130px;text-align:left; vertical-align:center'>
		Wei&szlig;abgleich:
		</TD>
		<TD class='normal' bgcolor='#EEEEAA' style='width:120px; text-align:left;  vertical-align:center'>".$wb_mode."
		</TD>
	</TR>
	
	<TR class='normal' style='height:22px;'>
		<TD class='normal' bgcolor='#EEEEAA' style='width:130px;text-align:left; vertical-align:center'>
		Farbtemperatur:
		</TD>
		<TD class='normal' bgcolor='#EEEEAA' style='width:120px; text-align:left;  vertical-align:center'>".$color_temp."
		</TD>
	</TR>
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'></TD>
	</TR>
	
	<TR class='normal' style='height:22px;'>
		<TD class='normal' bgcolor='#EEEEAA' style='width:130px;text-align:left; vertical-align:center'>
		Schnell-Konvertierung:
		</TD>
		<TD class='normal' bgcolor='#EEEEAA' style='width:120px; text-align:left;  vertical-align:center'>".$konv_mode."
		</TD>
	</TR>
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'></TD>
	</TR>
	
	<TR class='normal' style='height:22px;'>
		<TD class='normal' bgcolor='#EEEEAA' style='width:130px;text-align:left; vertical-align:center'>
		Kontrast:
		</TD>
		<TD class='normal' bgcolor='#EEEEAA' style='width:120px; text-align:left;  vertical-align:center'>".$contrast_val."
		</TD>
	</TR>";
	
	IF($gamma_mode == 'automatisch')
	{
		$gamma_mode = '';
	}
	echo "
	<INPUT TYPE='hidden' NAME = 'highlight' VALUE='$hl_mode'>
	<INPUT TYPE='hidden' NAME = 'gamma' VALUE='$gamma_mode'>
	<INPUT TYPE='hidden' NAME = 'color_space' VALUE='$targ_color'>
	<INPUT TYPE='hidden' NAME = 'color_interpol' VALUE='$col_inter'>
	<INPUT TYPE='hidden' NAME = 'rota' VALUE='$rota'>
	<INPUT TYPE='hidden' NAME = 'wb' VALUE='$wb_mode'>
	<INPUT TYPE='hidden' NAME = 'hsi' VALUE='$konv_mode'>
	<INPUT TYPE='hidden' NAME = 'contrast' VALUE='$contrast_val'>";
}
ELSE
{
	echo "<FORM name='params'>
	<TABLE border = '0' style='width:750px; background-color:#FFFFFF' align = 'center'>
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '3'>
		</TD>
	</TR>
	
	<TR class='normal' style='height:25px;'>
		<TD class='normal' bgcolor='#FFFFFF' colspan = '3'>
		Legen Sie hier bitte die neuen Konvertierungs-Parameter fest:
		</TD>
	</TR>
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '3'>
		</TD>
	</TR>
	
	<TR class='normal' style='height:25px;'>
		<TD class='normal' bgcolor='#EEEEAA' colspan='2' style='width:250px;'>Korrektur-Parameter</TD>
		<TD class='normal' bgcolor='#EEEEAA' style='width:500px;'>Vorschau f&uuml;r Bild ".$pic_id." (".$FileNameOri." / ".$FileName.")</TD>
	</TR>	
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'></TD>
		<TD class='normal' bgcolor='#FF9900'></TD>
	</TR>
	
	<TR class='normal'>
		<TD class='normal' bgcolor='#EEEEAA' style='text-align:left; vertical-align:center; width:130px'>
		Highlight-Mode:
		</TD>
		<TD class='normal' bgcolor='#EEEEAA'  style='text-align:left; vertical-align:center; width:120px'>
		<SELECT name='highlight' STYLE='WIDTH:120px;height:22px'>
		<option selected value=''>(0) Spitzlicht-Clipping</option>
		<option value='-H 1'>(1) Spitzlicht-Restaurierung</option>
		<option value='-H 2'>(2) Spitzlicht-Restaurierung</option>
		<option value='-H 3'>(3) Spitzlicht-Restaurierung</option>
		<option value='-H 4'>(4) Spitzlicht-Restaurierung</option>
		<option value='-H 5'>(5) Spitzlicht-Restaurierung</option>
		<option value='-H 6'>(6) Spitzlicht-Restaurierung</option>
		<option value='-H 7'>(7) Spitzlicht-Restaurierung</option>
		<option value='-H 8'>(8) Spitzlicht-Restaurierung</option>
		<option value='-H 9'>(9) Spitzlicht-Restaurierung</option>
		</select>
		</TD>
		<TD class='normal' rowspan='16' style='width:500px; height:450px; background-color:#DDDDDD; text-align:center; vertical-align:middle'>
		<div id='new_preview'>
		Die Erstellung der Vorschaubilder ist ein sehr aufw&auml;ndiger Prozess,<BR>
		der je nach Rechenleistung des Servers einige Zeit in Anspruch nimmt.<BR>
		Die Berechnung ist abgeschlossen, wenn Sie hier<BR> das neu berechnete Bild sehen.<BR><BR>
		Haben Sie bitte ein wenig Geduld.
		</div>
		</TD>
	</TR>
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'></TD>
	</TR>
	
	<TR class='normal'>
		<TD class='normal' bgcolor='#EEEEAA' style='width:130px;text-align:left; vertical-align:center'>
		Gamma-Korrektur:
		</TD>
		<TD class='normal' bgcolor='#EEEEAA' style='text-align:left; vertical-align:center'>
		<SELECT name=\"gamma\" STYLE='WIDTH:120px;height:22px'>
		<OPTION VALUE='' selected>automatisch</OPTION>";
		FOR($k=1; $k<=50; $k++)
		{
			$val = '-b '.number_format(($k/10),1,'.','.');
			echo "<OPTION VALUE='$val'>".substr($val,3,3)."</OPTION>";
		}
		echo "
  		</SELECT>
		</TD>
	</TR>
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'></TD>
	</TR>
	
	<TR class='normal'>
		<TD class='normal' bgcolor='#EEEEAA' style='width:130px;text-align:left; vertical-align:center'>
		Ziel-Farbraum:
		</TD>
		<TD class='normal' bgcolor='#EEEEAA' style='text-align:left; vertical-align:center'>
		<SELECT name=\"color_space\" STYLE='WIDTH:120px;height:22px'>
		<OPTION VALUE='-o 0'>RAW</OPTION>
		<OPTION selected VALUE='-o 1'>sRGB</OPTION>
		<OPTION VALUE='-o 2'>AdobeRGB</OPTION>
		<OPTION VALUE='-o 3'>Wide Gamut</OPTION>
		<OPTION VALUE='-o 4'>Kodak Pro FotoRGB</OPTION>
		<OPTION VALUE='-o 5'>CIE XYZ</OPTION>
  		</SELECT>
		</TD>
	</TR>
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'></TD>
	</TR>
	
	<TR class='normal'>
		<TD class='normal' bgcolor='#EEEEAA' style='width:130px;text-align:left; vertical-align:center'>
		Farb-Interpolation:
		</TD>
		<TD class='normal' bgcolor='#EEEEAA' style='text-align:left; vertical-align:center'>
		<SELECT name=\"color_interpol\" STYLE='WIDTH:120px;height:22px'>
		<OPTION VALUE='-q 0'>Bilineare Interpolation</OPTION>
		<OPTION VALUE='-q 1'>Reverse</OPTION>
		<OPTION VALUE='-q 2'>VNG (Variable Number of Gradients)</OPTION>
		<OPTION selected VALUE='-q 3'>AHD (Adaptive Homogeneity-Directed)</OPTION>
  		</SELECT>
		</TD>
	</TR>
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'></TD>
	</TR>
	
	<TR class='normal'>
		<TD class='normal' bgcolor='#EEEEAA' style='width:130px;text-align:left; vertical-align:center'>
		Drehung/Spiegel.:
		</TD>
		<TD class='normal' bgcolor='#EEEEAA' style='text-align:left; vertical-align:center'>
		<SELECT name=\"rota\" STYLE='WIDTH:120px;height:22px'>
		<OPTION selected value=''>lt. Kamera</OPTION>
		<OPTION VALUE='-t 0'>keine</OPTION>
		<OPTION VALUE='-t 1'>Spiegelung an der vert. Bildachse</OPTION>
		<OPTION VALUE='-t 2'>Spiegelung an der hor. Bildachse</OPTION>
		<OPTION VALUE='-t 3'>Drehung um 180&#176;</OPTION>
		<OPTION VALUE='-t 4'>Spiegelung an der vert. Bildachse und Drehung um 90&#176; CCW</OPTION>
		<OPTION VALUE='-t 5'>Drehung um 90&#176; CCW</OPTION>
		<OPTION VALUE='-t 6'>Drehung um 90&#176; CW</OPTION>
		<OPTION VALUE='-t 7'>Spiegelung an der vert. Bildachse und Drehung um 90&#176; CW</OPTION>
  		</SELECT>
		</TD>
	</TR>
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'></TD>
	</TR>
	
	<TR class='normal'>
		<TD class='normal' bgcolor='#EEEEAA' style='width:130px;text-align:left; vertical-align:center'>
		Wei&szlig;abgleich:
		</TD>
		<TD class='normal' bgcolor='#EEEEAA' style='width:120px; text-align:left;  vertical-align:center'>
		<SELECT name='wb' STYLE='WIDTH:120px;height:22px'>
		<option value='-a'>automatisch</option>
		<option selected value='-w'>von Kamera</option>
		</select>
		</TD>
	</TR>
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'></TD>
	</TR>
	
	<TR class='normal'>
		<TD class='normal' bgcolor='#EEEEAA' style='width:130px;text-align:left; vertical-align:center'>
		Schnell-Konvertierung:
		</TD>
		<TD class='normal' bgcolor='#EEEEAA' style='width:120px; text-align:left;  vertical-align:center'>
		<SELECT name='hsi' STYLE='WIDTH:120px;height:22px'>
		<option selected value='-h'>ja</option>
		<option value=''>nein</option>
		</select>
		</TD>
	</TR>
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'></TD>
	</TR>
	
	<TR class='normal'>
		<TD class='normal' bgcolor='#EEEEAA' style='width:130px;text-align:left; vertical-align:center'>
		Kontrast:
		</TD>
		<TD class='normal' bgcolor='#EEEEAA' style='width:120px; text-align:left;  vertical-align:center'>
		<SELECT name='contrast' STYLE='WIDTH:120px;height:22px'>
		<option value='-cont 5'>+5</option>
		<option value='-cont 4'>+4</option>
		<option value='-cont 3'>+3</option>
		<option value='-cont 2'>+2</option>
		<option value='-cont 1'>+1</option>
		<option selected value='-cont 0'>keine &Auml;nderung</option>
		<option value='-cont -1'>-1</option>
		<option value='-cont -2'>-2</option>
		<option value='-cont -3'>-3</option>
		<option value='-cont -4'>-4</option>
		<option value='-cont -5'>-5</option>
		</select>
		</TD>
	</TR>";
}
	
echo "	<TR class='normal' style='height:180px;'>
		<TD class='normal' bgcolor='#EEEEAA' colspan = '2' style='vertical-align:top'>
		<input type='button' value='Lade Standard-Parameter' onClick=\"eraseCookie('params','',''); location.href='select_params.php?pic_id=$pic_id'\" style='margin-top:30px; width:250px'>
		
		<input type='button' value='Speichere Parameter f&uuml;r diese Sitzung' onClick=\"document.cookie='params=' + ' ' + document.params.highlight.value + ' ' + document.params.gamma.value + ' ' + document.params.color_space.value + ' ' + document.params.color_interpol.value + ' ' + document.params.rota.value + ' ' + document.params.wb.value + ' ' + document.params.hsi.value + ' ' + document.params.contrast.value; location.reload ();\" style='margin-top:10px; width:250px'>
		
		<!--<input type='button' value='Zeige gespeicherte Parameter' onClick='alert(document.cookie)' style='margin-top:10px; width:250px'>-->
		
		<input type='button' value='L&ouml;sche gespeicherte Parameter' onClick=\"eraseCookie('params','','')\" style='margin-top:10px; width:250px'>
		
		<input type='button' value='Ansicht mit gew&auml;hlten Parametern' onClick=\"createNewPreview('$pic_id', '$c_username', document.params.highlight.value, document.params.gamma.value, document.params.color_space.value, document.params.color_interpol.value, document.params.rota.value, document.params.wb.value, document.params.hsi.value, document.params.contrast.value, '$FileNameRaw', 'tmp', '$Orientation')\" style='margin-top:10px; width:250px'>
		</TD>
	</TR>
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'></TD>
		<TD class='normal' bgcolor='#FF9900'></TD>
	</TR>
	
	<TR class='normal'>
		<TD class='normal' bgcolor='#EEEEAA' colspan='2' style='width:250px;'>
		<input type='button' value='Fenster schliessen' onClick='javascript:window.close()' style='width:250px'>
		</TD>
		<TD class='normal' bgcolor='#EEEEAA' style='text-align:center;'>
		<input type='button' value='Vorschaubilder neu erzeugen' onClick=\"createNewPreview('$pic_id', '$c_username', document.params.highlight.value, document.params.gamma.value, document.params.color_space.value, document.params.color_interpol.value, document.params.rota.value, document.params.wb.value, document.params.hsi.value, document.params.contrast.value, '$FileNameRaw', 'new', '$Orientation')\" style='width:250px;'>
		</TD>
	</TR>
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '3'>
		</TD>
	</TR>";

echo "
</TABLE>
</FORM>
<div id='blend' style='display:none; z-index:99;'>
<IMG src='../../share/images/grey.png' style='z-index:100; position:absolute; top:0px; left:0px; width:100%; height:100%;' />
<img src=\"../../share/images/loading.gif\" style='position:absolute; top:200px; width:20px; z-index:101;' />
</div>";
?>
</body>
</html>