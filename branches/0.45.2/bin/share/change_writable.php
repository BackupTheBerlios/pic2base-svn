<!--<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>-->
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
if ( array_key_exists('lfdnr',$_GET) )
{
	$lfdnr = $_GET['lfdnr'];
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

$result1 = mysql_query( "UPDATE $table5 SET writable = '$new_status' WHERE lfdnr = '$lfdnr'");
echo mysql_error();
mysql_close($conn);

echo "<INPUT TYPE=CHECKBOX '$checked' value='$new_status' onClick='changeWritable(\"$lfdnr\",\"$checked\",\"$sr\")'>";
?>