<?php

IF ($_COOKIE['uid'])
{
//	list($c_username) = preg_split('#,#',$_COOKIE['login']);
	$uid = $_COOKIE['uid'];
}

if (array_key_exists('pic_id',$_GET))
{
	$pic_id = $_GET['pic_id'];
}

if (array_key_exists('filetype',$_GET))
{
	$filetype = $_GET['filetype'];
}

$error_code = 0;

include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
SWITCH($filetype)
{
	CASE 'hq':
		$result31 = mysql_query("SELECT FileName FROM $table2 WHERE pic_id = '$pic_id'");
		if(mysql_error() !== "")
		{
			$error_code = 1;
		}
		$FileName = mysql_result($result31, isset($i31), 'FileName');
		$FILE = $pic_path."/".$FileName;
		$dest_path = $pic_hq_path;
		$max_len = 800;
		$FileNameHQ = resizeOriginalPicture($FILE, $dest_path, $max_len, $sr);
		$fileHQ = $pic_hq_path."/".$FileNameHQ;
		clearstatcache();  
		chmod ($fileHQ, 0700);
		clearstatcache();
		$fh = fopen($fileHQ, 'r');
		if(!$fh)
		{
			$error_code = 1;
		}
		fclose($fh);
		$obj1 = new stdClass();
		$obj1->errorCode = $error_code;
		$obj1->pic_id = $pic_id;
		$obj1->Userid = $uid;
		$obj1->filetype = $filetype;
		$output = json_encode($obj1);
		echo $output;	
	break;		
	
	CASE 'v':
		$result32 = mysql_query("SELECT FileNameHQ FROM $table2 WHERE pic_id = '$pic_id'");
		if(mysql_error() !== "")
		{
			$error_code = 1;
		}
		$FileNameHQ = mysql_result($result32, isset($i32), 'FileNameHQ');
		$FILE = $pic_hq_path."/".$FileNameHQ;
		$dest_path = $pic_thumbs_path;
		$max_len = 160;
		$FileNameV = createPreviewPicture($FILE, $dest_path, $max_len, $sr);
		$fileV = $pic_thumbs_path."/".$FileNameV;
		clearstatcache();  
		chmod ($fileV, 0700);
		clearstatcache();
		$fh = fopen($fileV, 'r');
		if(!$fh)
		{
			$error_code = 1;
		}
		fclose($fh);
		$obj1 = new stdClass();
		$obj1->errorCode = $error_code;
		$obj1->pic_id = $pic_id;
		$obj1->Userid = $uid;
		$obj1->filetype = $filetype;
		$output = json_encode($obj1);
		echo $output;	
	break;
	
	CASE 'hist_mono':
		$result33 = mysql_query("SELECT FileNameHQ FROM $table2 WHERE pic_id = '$pic_id'");
		echo mysql_error();
		$FileNameHQ = mysql_result($result33, isset($i33), 'FileNameHQ');
		//$mf - missing files -> Zahl der erneuerten Histogramme / monochrome Bilder
		$zv = generateHistogram($pic_id,$FileNameHQ,$sr);
		$obj1 = new stdClass();
		$obj1->errorCode = $error_code;
		$obj1->pic_id = $pic_id;
		$obj1->Userid = $uid;
		$obj1->filetype = $filetype;
		$output = json_encode($obj1);
		echo $output;	
	break;
}


?>