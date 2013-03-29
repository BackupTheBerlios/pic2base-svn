<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Startseite</TITLE>
	<META NAME="GENERATOR" CONTENT="eclipse">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format2.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script language="JavaScript" src="../../share/functions/resize_elements.js"></script>
	<script language="JavaScript" src="../../share/functions/jquery-1.8.2.min.js"></script>
	<script language="JavaScript">
	  	jQuery.noConflict();
		jQuery(document).ready(checkWindowSize);
		jQuery(window).resize(checkWindowSize); 
	</script>
</HEAD>

<BODY>
<DIV Class="klein">
<?PHP

/*
 * Project: pic2base
 * File: kat_edit_action1.php
 *
 * Copyright (c) 2003 - 2012 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 */

IF ($_COOKIE['uid'])
{
	$uid = $_COOKIE['uid'];
}

INCLUDE '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/css/initial_layout_settings.php';

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');

echo "
<div class='page' id='page'>

	<div id='head'>
		pic2base :: Stapelverarbeitung Kategorie-Umbenennung <span class='klein'>(User: ".$username.")</span>
	</div>
		
	<div class='navi' id='navi'>
		<div class='menucontainer'>
		</div>
	</div>
		
	<div class='content' id='content'>
		<span style='font-size:12px;'>
		<p style='margin:120px 0px; text-align:center'>
		
		<center>
			Status der Kategorie-Umbenennung:
			<div id='prog_bar' style='border:solid; border-color:red; width:500px; height:12px; margin-top:50px; text-align:left; vertical-align:middle'>
				<img src='../../share/images/green.gif' name='bar' />
			</div>
			<p id='meldung'>".isset($meldung)."</p>
		</center>
		
		</p>
		</span>
	</div>
	
	<div id='foot'>
		<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
	</div>
	
</div>";

ob_flush();
//var_dump($_POST);
$ID = $_GET['ID']; 
$kat_id = $_GET['kat_id']; 
$kategorie = $_POST['kategorie']; 
$exiftool = buildExiftoolCommand($sr);

// zuerst wird der Kategoriename in der DB aktualisiert
$res = mysql_query( "UPDATE $table4 SET kategorie=\"$kategorie\" WHERE kat_id='$ID'");
echo mysql_error();

//dann wird in allen Bildern, denen die Kategorie ($ID) zugewiesen wurde, der Meta-Daten-Eintrag aktualisiert:
//zuerst wird ermittelt, welche Bilder der betreffenden Kat. zugehoeren
$res2 = mysql_query("SELECT $table10.pic_id, $table10.kat_id, $table2.pic_id, $table2.FileName 
FROM $table10, $table2 
WHERE $table10.kat_id = '$ID' AND ($table10.pic_id = $table2.pic_id)");
$num2 = mysql_num_rows($res2);
FOR($i2='0'; $i2<$num2; $i2++)
{
	$row2 = mysql_fetch_row($res2);
	$pic_id = $row2[0];
	//echo $pic_id.": ";
	//dann wird bestimmt, welche Kategorien dem Bild weiter zugeordnet wurden
	$kategorie = '';
	$res3 = mysql_query("SELECT $table10.kat_id, $table10.pic_id, $table4.kat_id, $table4.kategorie 
	FROM $table10, $table4 
	WHERE $table10.pic_id = '$pic_id' AND ($table10.kat_id = $table4.kat_id)");
	FOR($i3='0'; $i3<mysql_num_rows($res3); $i3++)
	{
		//der neue keywords-Eintrag wird konstruiert:
		$row3 = mysql_fetch_row($res3);
		IF($row3[2]!=='1')
		{
			$kategorie = $row3[3]." ".$kategorie;
		}
	}
	//echo $kategorie."<BR>";
	// und in die Meta-Daten des jpg- und Originalbildes geschrieben:
	$FN = $pic_path."/".$row2[3];
	shell_exec($exiftool." -IPTC:Keywords='$kategorie' -overwrite_original ".$FN." > /dev/null &");
	$FNO = strtolower($pic_path."/".restoreOriFilename($pic_id, $sr));
	shell_exec($exiftool." -IPTC:Keywords='$kategorie' -overwrite_original ".$FNO." > /dev/null &");
	
	//abschliessend wird die meta_data-Tabelle aktualisiert:
	$res4 = mysql_query( "UPDATE $table2 SET Keywords = '$kategorie' WHERE pic_id = '$pic_id'");
	//Die Balkenlaenge wird errechnet:
	$width=round(500 * ($i2 + 1)/$num2);
	?>
	
	<SCRIPT language="JavaScript">
	document.bar.src='../../share/images/green.gif';
	document.bar.width=<?php echo $width?>;
	document.bar.height='11';
	</SCRIPT>
	
	<?php
	flush();
}

?>

	<SCRIPT language="JavaScript">
	document.getElementById('meldung').innerHTML='<?php echo "Fertig...";?>';
	</SCRIPT>
	
<?php 
		//Am Ende derfolgt der automatische Ruecksprung zur Kat.-Bearbeitungsseite:
echo "<meta http-equiv='Refresh' content='1, URL=kategorie0.php?kat_id=$kat_id'>";
mysql_close($conn);
?>

</DIV>
</BODY>
</HTML>