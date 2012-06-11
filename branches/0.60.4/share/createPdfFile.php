<?php
unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
}
//$benutzername = $c_username;
include 'global_config.php';
include 'db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

IF($_GET['pdf_statement'])
{
	$pdf_statement = urldecode($_GET['pdf_statement']);
}

IF($_GET['mod'])
{
	$mod = $_GET['mod'];
}

IF($_GET['num6_1'])
{
	$num6_1 = $_GET['num6_1'];
}

$server_url = "http://{$_SERVER['SERVER_NAME']}$inst_path";


IF($num6_1 < '101')
{
	$bild = '1';		//HQ-Bilder werden verwendet
}
ELSEIF($num6_1 >= '101' AND $num6_1 < '1001')
{
	$bild = '0';		//Thumbs werden verwendet
}

IF($num6_1 < '1001')
{
	if (!isset($pdf_statement))
	{
		$pdf_statement = '';
	}	
	createContentFile($mod,$pdf_statement,$c_username,$bild);
}
	
echo " <FONT COLOR='#FF9900'>Galerie anzeigen:</FONT>&#160;&#160;&#160; <a href = '../../../userdata/klaus/kml_files/thumb-gallery.pdf'><img src=\"$inst_path/pic2base/bin/share/images/acroread.png\" width=\"12\" height=\"12\" border=\"0\"  title='Thumbnail-Galerie anzeigen' /></a></span>";

?>