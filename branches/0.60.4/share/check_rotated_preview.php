<?php
//Skript prueft, ob fuer die Vollbild-Vorschau ein gedrehtes Original-Bild existiert
if(array_key_exists('filename', $_POST))
{
	$FileName = $_POST['filename'];
	
	$verz=opendir($sr.'/images/originale/rotated');
	$n = 0;
	while($bilddatei=readdir($verz))
	{
		if($bilddatei != "." && $bilddatei != "..")
		{
			//$bildd=$bilder_verzeichnis."/".$bilddatei;
			//echo "Bild: ".$bilddatei."; Datei: ".$file_name."<BR>";
			IF ($bilddatei == $FileName)
			{
				$n++;
			}
		}
	}
//	echo "N: ".$n."<BR>";
	IF ($n > '0')
	{
		echo "originale/rotated/";
		//echo "<BR>Bild liegt gedreht vor.<BR>";
	}
	ELSE
	{
		echo "originale/";
		//echo "<BR>Bild liegt N I C HT gedreht vor.<BR>";
	}
	//echo $FileName;
}
else
{
	echo "nichts übergeben.";
}
?>