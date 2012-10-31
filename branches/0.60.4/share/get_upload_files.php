<?php
unset($username);
IF ($_COOKIE['uid'])
{
	$uid = $_COOKIE['uid'];
}

include 'global_config.php';

//Ermittlung der Anzahl von Dateien im Uploadordner des angemeldeten Users:
//echo "<BR>Upload-Ordner des Users: ".$up_dir."<BR><BR>";

$n = 0;				//Zaehlvariable fuer die zu bearbeitenden Bilder (Bilder im Upload-Ordner)
//Ermittlung, wieviel Bilddateien sich in dem angegebenen Ordner befinden und Abspeicherung der Dateinamen in einem Array:
$bild_datei = array();
$dh = opendir($up_dir);

$obj = new stdClass();

while($datei_name=readdir($dh))
{
	if($datei_name != "" && $datei_name != "." && $datei_name != "..")
	{
		$info = pathinfo($datei_name);
		$extension = strtolower($info['extension']);
		IF(in_array($extension,$supported_filetypes) OR $extension == 'jpg')
		{
			$bild_datei[] = $datei_name;
			$n++;	
		}
	}
}

$obj->anzahl = $n;
$obj->file_array = $bild_datei;
$output = json_encode($obj);
echo $output;
?>