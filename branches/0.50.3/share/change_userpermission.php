<?php
IF (!$_COOKIE['login'])
{
	include '../share/global_config.php';
	//var_dump($sr);
	header('Location: ../../index.php');
}

$sr = $_GET['sr'];

include $sr.'/bin/share/db_connect1.php';
//include $sr.'/bin/share/functions/ajax_functions.php';
//include $sr.'/bin/share/functions/main_functions.php';

//var_dump($_GET);
if ( array_key_exists('user_id',$_GET) )
{
	$user_id = $_GET['user_id'];
}
if ( array_key_exists('perm_id',$_GET) )
{
	$perm_id = $_GET['perm_id'];
}
if ( array_key_exists('checked',$_GET) )
{
	$checked = $_GET['checked'];
}

IF($checked == '')
{
	$new_status = '1';
	$checked = 'checked';
}
ELSEIF($checked == 'checked')
{
	$new_status = '0';
	$checked = '';
}

$result1 = mysql_query( "UPDATE $table7 SET enabled = '$new_status' WHERE permission_id = '$perm_id' AND user_id = '$user_id'");
echo mysql_error();
mysql_close($conn);

echo "<INPUT TYPE=CHECKBOX $checked value='$new_status' onClick='changeUserpermission(\"$user_id\",\"$perm_id\",\"$checked\",\"$sr\")'>";
?>