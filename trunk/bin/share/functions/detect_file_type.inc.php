<?php
function FileType($datei)
{
	$search1 = ".jpg";
	$search2 = ".gif";
	$search3 = ".jpeg";
	IF (strchr($datei,$search1) == '.jpg')
	{
		$file_type = "jpg";
	}
	ELSEIF (strchr($datei,$search2) == '.gif')
	{
		$file_type = "gif";
	}
	ELSEIF (strchr($datei,$search3) == '.jpeg')
	{
		$file_type = "jpeg";
	}
	RETURN $file_type;
}
?>