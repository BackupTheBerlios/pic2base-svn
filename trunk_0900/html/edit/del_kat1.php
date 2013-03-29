<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../../index.php');
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Kategorie l&ouml;schen</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<CENTER>

<DIV Class="klein">

<?php

/*
 * Project: pic2base
 * File: del_kat1.php
 *
 * Copyright (c) 2003 - 2011 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 */

IF(array_key_exists('kat_id', $_GET))
{
	$kat_id = $_GET['kat_id'];
}
IF(array_key_exists('pic_id', $_GET))
{
	$pic_id = $_GET['pic_id'];
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$result1 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result1, isset($i1), 'username');

$result2 = mysql_query("SELECT * FROM $table4 WHERE kat_id =  '$kat_id'");
$kategorie = mysql_result($result2, isset($i2), 'kategorie');
$parent = mysql_result($result2, isset($i2), 'parent');

?>

<div class="page">

	<p id="kopf">pic2base :: Kategoriezuweisung aufheben <span class='klein'>(User: <?php echo $username;?>)</span></p>
	
	<div class="navi" style="clear:right;">
		<div class="menucontainer">
		<?php
		createNavi3_1($uid);
		//echo $navigation;
		?>
		</div>
	</div>
	
	<div class="content">
	<p class="zwoelfred" style="margin:120px 0px; text-align:center">Wollen Sie wirklich die Kategoriezuweisung<BR> "<?php echo $kategorie;?>" <BR>f&uuml;r das gew&auml;hlte Bild aufheben?<BR><BR>
	Wenn Sie mit 'JA' fortfahren, werden gleichzeitig<BR>alle Unterkategorie-Zuweisungen mit aufgehoben!<BR><BR>
	<?php
	echo "
	<INPUT type='button' value='JA' onClick='location.href=\"del_kat_action.php?kat_id=$kat_id&pic_id=$pic_id&parent=$parent\"'>
	&#160;&#160;
	<INPUT type='button' value='Nein' onClick='location.href=\"edit_remove_kat.php?pic_id=0&mod=kat&kat_id=$parent\"'></p>
	</div>
	<br style='clear:both;' />

	<p id='fuss'><A style='margin-right:745px;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>".$cr."</p>

</div>";

mysql_close($conn);
?>
</DIV>
</CENTER>
</BODY>
</HTML>