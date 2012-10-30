<?php
IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
	//var_dump($sr);
	header('Location: ../../index.php');
}

$sr = $_GET['sr'];

include $sr.'/bin/share/db_connect1.php';

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
	$text = 'Berechtigung erteilt';
}
ELSEIF($checked == 'checked')
{
	$new_status = '0';
	$checked = '';
	$text = 'keine Berechtigung';
}
//es wird kontrolliert, ob dem User durch die Gruppenzugehoerigkeit bereits das zu aendernde Recht zugewiesen wurde. Wenn ja, wird dies wie gewuenscht geaendert,
//wenn nein, wird dieses Recht zunaechst zugefuegt:
$result1 = mysql_query("SELECT * FROM $table7 WHERE permission_id = '$perm_id' AND user_id = '$user_id'");
if(mysql_num_rows($result1) == 1)
{
	$result2 = mysql_query( "UPDATE $table7 SET enabled = '$new_status' WHERE permission_id = '$perm_id' AND user_id = '$user_id'");
	echo mysql_error();
}
else
{
	$result2 = mysql_query( "INSERT INTO $table7 (enabled, permission_id, user_id) VALUES ('$new_status', '$perm_id', '$user_id')");
	echo mysql_error();
}
mysql_close($conn);

echo "<INPUT TYPE=CHECKBOX $checked value='$new_status' title= '$text' onClick='changeUserpermission(\"$user_id\",\"$perm_id\",\"$checked\",\"$sr\")'>";
?>