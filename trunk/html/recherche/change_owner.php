<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../../index.php');
}
else
{
	$uid = $_COOKIE['uid'];
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Neuen Eigent&uuml;mer festlegen</title>
  <meta name="GENERATOR" content="Quanta Plus">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="Content-Style-Type" content="text/css">
  <link rel=stylesheet type="text/css" href='../../css/format1.css'>
  <link rel="shortcut icon" href="../../share/images/favicon.ico">
  <script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
</head>

<body style='background-color:#999999'>
<?php
// verwendet als Popup-Fenster zur Festlegung Bild-Eigentuemers

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/ajax_functions.php';

if ( array_key_exists('pic_id',$_GET) )
{
	$pic_id = $_GET['pic_id'];
}
//Daten des derzeitigen Owners ermitteln:
$result1 = mysql_query( "SELECT $table2.FileNameV, $table2.Owner, $table1.id, $table1.username, $table1.name, $table1.vorname, $table1.ort 
FROM $table2 INNER JOIN $table1 
ON $table1.id = $table2.Owner 
AND $table2.pic_id = '$pic_id'");
echo mysql_error();
$num1 = mysql_num_rows($result1);
$row = mysql_fetch_array($result1);
$FileNameV = $row['FileNameV'];
$owner = $row['Owner'];
$name = $row['name'];
$vorname = $row['vorname'];
$ort = $row['ort'];
IF($uid === $owner)
{
	//welche User gibt es noch ausser dem angemeldeten im System, der Bilder erfassen darf (permission 799)?
	$result2 = mysql_query("SELECT $table1.id, $table1.username, $table1.vorname, $table1.name, $table1.ort, $table1.aktiv, 
	$table7.user_id, $table7.permission_id, $table7.enabled
	FROM $table1 INNER JOIN $table7
	ON $table1.id = $table7.user_id
	AND $table1.aktiv = '1'
	AND $table1.username <> 'pb'
	AND $table7.enabled = '1'
	AND $table7.permission_id = '799'");
	$num2 = mysql_num_rows($result2);
	//echo "gefundene User: ".$num2;
	echo "<FORM name='n_owner' method='post' action='change_owner_action.php'>
	<TABLE border = '0' style='width:750px; height:400px; background-color:#FFFFFF' align = 'center'>
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '3'>
		</TD>
	</TR>
	
	<TR class='normal' style='height:25px;'>
		<TD class='normal' bgcolor='#FFFFFF' colspan = '3'>
		Treffen Sie hier bitte die Wahl f&uuml;r den neuen Bild-Eigent&uuml;mer f&uuml;r das Bild ".$pic_id.":
		</TD>
	</TR>
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '3'>
		</TD>
	</TR>
	
	<TR class='normal'>
		<TD class='normal' bgcolor='#EEEEAA' colspan='2' style='width:250px;'>aktuelle Daten</TD>
		<TD class='normal' bgcolor='#EEEEAA' style='width:500px;'>Liste der registrierten Benutzer</TD>
	</TR>	
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#EEEEAA' colspan = '2'></TD>
		<TD class='normal' bgcolor='#FF9900'></TD>
	</TR>
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FFFFFF' colspan='2'>
		<img src=\"$inst_path/pic2base/images/vorschau/thumbs/$FileNameV\">
		<BR>Derzeitiger Eigent&uuml;mer:<BR>".
		$vorname." ".$name."<BR>".$ort."</TD>
		<TD style='; background-color:#99FF99;'>
		<div id='other_user' style='width:430px; height:450px; overflow:auto; margin-left:30px;'>
		<fieldset style='margin-top:20px;'>
		<legend>weitere Benutzer</legend>
		<TABLE style='width:400px; border-style:none;'>
		<TR class='normal' >
		<TD class='normal' style='width:310px; text-align:left; padding-left:50px;'>";
		FOR($i2='0'; $i2<$num2; $i2++)
		{
			$id = mysql_result($result2, $i2, 'id');
			$name= mysql_result($result2, $i2, 'name');
			$vorname = mysql_result($result2, $i2, 'vorname');
			$ort = mysql_result($result2, $i2, 'ort');
			if(!($uid === $id))
			{
				echo "
				<INPUT type='radio' name='new_owner' value='$id'  style='margin-right:20px;'>".$vorname." ".$name.", ".$ort."<BR>
				<input type='hidden' name ='pic_id' value='$pic_id'>";
			}
		}
		
		echo "</TD>
		</TR>
		</TABLE>
		</fieldset>
		</div>
		</TD>
		
	</TR>
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#EEEEAA' colspan = '2'></TD>
		<TD class='normal' bgcolor='#FF9900'></TD>
	</TR>
	
	<TR class='normal'>
		<TD class='normal' bgcolor='#EEEEAA' colspan='2' style='width:250px;'>
		<input type='button' value='Fenster schliessen' onClick='javascript:window.close()' style='width:250px'>
		</TD>
		<TD class='normal' bgcolor='#EEEEAA' style='text-align:center;'>";
		if($i2 > 0)
		{
			echo "<input type='submit' value='Neuen Eigent&uuml;mer zuweisen' style='width:250px;'>";
		}
		echo "
		</TD>
	</TR>
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '3'>
		</TD>
	</TR>
	</TABLE>
	</FORM>";
}
ELSE
{
	echo "Es gibt ein Problem!";
}
?>
</body>
</html>