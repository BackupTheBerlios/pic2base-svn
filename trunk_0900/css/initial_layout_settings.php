<?php 
//
//Skript liefert Layout-Grundeinstellungen, bevor der Inhalt generiert wird
//

$fenster_breite = $_COOKIE['window_width'];
$fenster_hoehe = $_COOKIE['window_height'];	//echo $fenster_hoehe;

if($fenster_breite > 1000)
{
	$page_breite = ($fenster_breite - 40)."px";
	$head_breite = ($fenster_breite - 46)."px";
	$content_breite = ($fenster_breite - 168)."px";
	$spalte1_breite = (($fenster_breite - 162) / 2)."px";
	$spalte2_breite = (($fenster_breite - 161) / 2)."px";
	$foot_breite = ($fenster_breite - 46)."px";
	$logframe_breite = ($fenster_breite - 200)."px";
}
else
{
	$page_breite = "950px";
	$head_breite = "944px";
	$content_breite = "822px";
	$spalte1_breite = "414px";
	$spalte2_breite = "415px";
	$foot_breite = "944px";
	$logframe_breite = "780px";
}

if($fenster_hoehe > 743)
{
	$page_hoehe = ($fenster_hoehe - 40)."px";
	$navi_hoehe = ($fenster_hoehe - 107)."px";
	$content_hoehe = ($fenster_hoehe - 116)."px";
	$spalte1_hoehe = ($fenster_hoehe - 110)."px";
	$spalte2_hoehe = ($fenster_hoehe - 110)."px";
	$logframe_hoehe = ($fenster_hoehe - 146)."px";
}
else
{
	$page_hoehe = "703px";
	$navi_hoehe = "637px";
	$content_hoehe = "627px";
	$spalte1_hoehe = "633px";
	$spalte2_hoehe = "633px";
	$logframe_hoehe = "600px";
}


?>

<style type="text/css">

#page	{
			width:<?php echo $page_breite; ?>;
			height:<?php echo $page_hoehe; ?>;
		}
		
#head	{
			width:<?php echo $head_breite; ?>;
		}
		
#navi	{
			height:<?php echo $navi_hoehe; ?>;
		}
		
#content{
			width:<?php echo $content_breite; ?>;
			height:<?php echo $content_hoehe; ?>;
		}
		
#spalte1{
			width:<?php echo $spalte1_breite; ?>;
			height:<?php echo $spalte1_hoehe; ?>;
		}
		
#spalte2{
			width:<?php echo $spalte2_breite; ?>;
			height:<?php echo $spalte2_hoehe; ?>;
		}
		
#foot	{
			width:<?php echo $foot_breite; ?>;
		}
		
#logframe{
			width:<?php echo $logframe_breite; ?>;
			height:<?php echo $logframe_hoehe; ?>;
		}
		
</style>