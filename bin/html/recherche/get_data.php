<?php
header('Content-Type: text/xml'); 
// php 5.3
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
$return_value = "<?xml version='1.0' encoding='ISO8859-1'?><entries>";

$rs = mysql_query("SELECT DISTINCT $id FROM $table2 WHERE $id <>'' ORDER BY $id");
echo mysql_error();
$num_rs = mysql_num_rows($rs);

FOR ($i_rs=0; $i_rs<$num_rs; $i_rs++)
{
	$ID = $i_rs + 1;
	$wert = mysql_result($rs, $i_rs, $id);
	/*$value = $wert;
	$field_type = mysql_field_type($rs, $id);
	IF ($field_type == 'datetime')
	{
		$wert = date('d.m.Y H:i:s',strtotime($wert));
	}
	*/
	$return_value .= "<data><id>$ID</id><name>$wert</name></data>\n";
}

mysql_close($conn);
$return_value .= "</entries>";
echo $return_value; 
?>