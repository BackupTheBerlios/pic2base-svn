<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Kategorie-Informationen bearbeiten</title>
  <meta name="GENERATOR" content="Quanta Plus">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="Content-Style-Type" content="text/css">
  <link rel=stylesheet type="text/css" href='../css/format1.css'>
  <link rel="shortcut icon" href="images/favicon.ico">
</head>
<body style='background-color:#999999'>
<?php
// verwendet als Popup-Fenster mit den Kategorie-Infos

//var_dump($_REQUEST);

if(array_key_exists('kat_id',$_GET))
{
	$kat_id = $_GET['kat_id'];
}

include_once 'global_config.php';
include_once 'db_connect1.php';
include_once $sr.'/bin/share/functions/main_functions.php';
include_once("fckeditor/fckeditor.php");

$result0 = mysql_query( "SELECT $table4.kat_id, $table4.kategorie, $table11.info, $table11.kat_id  FROM $table4 INNER JOIN $table11 ON $table4.kat_id = '$kat_id' AND $table4.kat_id = $table11.kat_id");
echo mysql_error();
$num0 = mysql_num_rows($result0);
$row = mysql_fetch_array($result0);
//var_dump($row);
$kategorie = htmlentities($row['kategorie']);
//$kategorie = htmlentities(mysql_result($result0, isset($i0), 'kategorie'));
//$info = mysql_result($result0, isset($i0), 'info');
$info = $row['info'];

unset($username);
IF ($_COOKIE['login'])
{
list($c_username) = preg_split('#,#',$_COOKIE['login']);
//echo $c_username;
}

$result2 = mysql_query( "SELECT group_id FROM $table1 WHERE username = '$c_username'");
$row = mysql_fetch_array($result2);
//$group_id = mysql_result($result2, isset($i2), 'group_id');
$group_id = $row['group_id'];
IF($group_id == '1' OR $group_id == '3')	//Admin oder Fotograf
{
	$editable = '1';
}

echo "	<FORM action='edit_kat_info_action.php?kat_id=$kat_id' method='post' target='_blank'>
	<TABLE border = '0' style='width:650px;background-color:#FFFFFF' align = 'center'>
	<TR class='normal' style='height:3px;'>
		<TD class='normal' bgcolor='#FF9900' colspan = '2'>
		</TD>
	</TR>
	
	<TR class='normal'>
		<TD class='normal' colspan = '2'>
		Vorhandene Informationen zur Kategorie \"".$kategorie."\"
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