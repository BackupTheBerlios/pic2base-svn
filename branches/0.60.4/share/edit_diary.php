<?php
IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../index.php');
}
else
{
	$uid = $_COOKIE['uid'];
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Tagebuch-Eintrag bearbeiten</title>
  <meta name="GENERATOR" content="Quanta Plus">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="Content-Style-Type" content="text/css">
  <link rel=stylesheet type="text/css" href='../css/format1.css'>
  <link rel="shortcut icon" href="images/favicon.ico">
</head>
<body style='background-color:#999999'>
<?php
// verwendet als Popup-Fenster mit dem Tagebuch-Eintrag

if(array_key_exists('aufn_dat',$_GET))
{
	$aufn_dat = $_GET['aufn_dat'];
}

include_once 'global_config.php';
include_once 'db_connect1.php';
include_once $sr.'/bin/share/functions/main_functions.php';
include_once $sr.'/bin/share/functions/permissions.php';
include_once("fckeditor/fckeditor.php");

$result0 = mysql_query("SELECT * FROM $table3 WHERE datum = '$aufn_dat'");
echo mysql_error();
$num0 = mysql_num_rows($result0);
$row = mysql_fetch_array($result0);
//var_dump($row);
$aufn_DAT = explode('-', $aufn_dat); //lesbare Formatierung
$AD = $aufn_DAT[2].".".$aufn_DAT[1].".".$aufn_DAT[0];
$info = $row['info'];

IF(hasPermission($uid, 'editdiary', $sr))	//berechtigte User duerfen das Tagebuch editieren
{
	$editable = '1';
	$view = 'Default';
}
ELSE
{
	$view = 'Readonly';
}

echo "	<FORM action='edit_diary_action.php?aufn_dat=$aufn_dat' method='post'>
	<TABLE border = '0' style='width:650px;background-color:#FFFFFF' align = 'center'>
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'>
		</TD>
	</TR>
	
	<TR class='normal'>
		<TD class='normal' colspan = '2'>
		Vorhandener Tagebucheintrag zum ".$AD."
		</TD>
	</TR>
	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'>
		</TD>
	</TR>
	
	<TR class='normal'>
		<TD class='normal' colspan = '2' align='left'>";
		
		$oFCKeditor = new FCKeditor('FCKeditor1') ;
		$oFCKeditor->BasePath = 'fckeditor/' ;
		$oFCKeditor->Value = $info ;
		$oFCKeditor->ToolbarSet = $view ;	//legt fest, ob editierbar oder nur lesbar
		$oFCKeditor->Height = 685;
		$oFCKeditor->Create() ;
		
		echo "
		</TD>
	</TR>

	
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'>
		</TD>
	</TR>";
	
	IF( isset($editable) and $editable == '1' ) 
	{
		echo "
		<TR class='normal'>
			<TD class='normal'><INPUT type='submit' value='Speichern' style='width:150px;'></TD>
			<TD class='normal'><INPUT type='button' value='Fenster schlie&szlig;en' onClick='javascript:window.close()' style='width:150px;'></TD>
		</TR>";
	}
	ELSE
	{
		echo "
		<TR class='normal'>
			<TD class='normal'></TD>
			<TD class='normal'><INPUT type='button' value='Fenster schlie&szlig;en' onClick='javascript:window.close()' style='width:150px;'></TD>
		</TR>";
	}
	echo "
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'>
		</TD>
	</TR>
</TABLE>
</FORM>";

?>
</body>
</html>