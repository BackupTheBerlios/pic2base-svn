<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-15">
	<TITLE>pic2base - Startseite</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
</HEAD>

<BODY>
<?PHP

/*
 * Project: pic2base
 * File: kat_edit_action1.php
 *
 * Copyright (c) 2003 - 2006 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 */

INCLUDE '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

//var_dump($_POST);
$ID = $_GET['ID']; 
$kat_id = $_GET['kat_id']; 
$kategorie = $_POST['kategorie']; 
$exiftool = buildExiftoolCommand($sr);

// *#*  echo "kategorie: ".$kategorie."<br>";
// zuerst wird der Kategoriename in der DB aktualisiert
$res = mysql_query( "UPDATE $table4 SET kategorie='$kategorie' WHERE kat_id='$ID'");
echo mysql_error();

//dann wird in allen Bildern, denen die Kategorie ($ID) zugewiesen wurde, der Meta-Daten-Eintrag aktualisiert:
//zuerst wird ermittelt, welche Bilder der betreffenden Kat. zugehoeren
$res2 = mysql_query("SELECT $table10.pic_id, $table10.kat_id, $table2.pic_id, $table2.FileName 
FROM $table10, $table2 
WHERE $table10.kat_id = '$ID' AND ($table10.pic_id = $table2.pic_id)");
FOR($i2='0'; $i2<mysql_num_rows($res2); $i2++)
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
			$kategorie = htmlentities($row3[3])." ".$kategorie;
		}
	}
	//echo $kategorie."<BR>";
	// und in die Meta-Daten des jpg- und Originalbildes geschrieben:
	$FN = $pic_path."/".$row2[3];
	shell_exec($exiftool." -IPTC:Keywords='$kategorie' -overwrite_original ".$FN);
	$FNO = strtolower($pic_path."/".restoreOriFilename($pic_id, $sr));
	shell_exec($exiftool." -IPTC:Keywords='$kategorie' -overwrite_original ".$FNO);
	
	//abschliessend wird die meta_data-Tabelle aktualisiert:
	$res4 = mysql_query( "UPDATE $table14 SET Keywords = '$kategorie' WHERE pic_id = '$pic_id'");
}

echo "<meta http-equiv='Refresh' content='0, URL=kat_edit.php?kat_id=$kat_id&ID=$ID'>";

mysql_close($conn);
?>
</BODY>
</HTML>