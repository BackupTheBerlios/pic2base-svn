<?php
// Skript prueft, ob fuer die Vollbild-Vorschau ein gedrehtes Original-Bild existiert
// verwendet in preview_layer.php, Z. 192
include 'global_config.php';
if(array_key_exists('filename', $_POST))
{
	$FileName = $_POST['filename'];
	$verz=opendir($sr.'/images/originale/rotated');
	$n = 0;
	while($bilddatei=readdir($verz))
	{
		if($bilddatei != "." && $bilddatei != "..")
		{
			IF ($bilddatei == $FileName)
			{
				$n++;
			}
		}
	}
	if ($n > '0')
	{
		echo "originale/rotated/";	//Bild liegt gedreht vor
	}
	else
	{
		echo "originale/";			//Bild liegt N I C HT gedreht vor
	}
}
else
{
	echo "Es liegt ein Fehler vor.";
}
?>