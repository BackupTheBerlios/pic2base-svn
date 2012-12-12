<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../../index.php');
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';

$group_id = $_GET['group_id'];
 
$result1 = mysql_query( "DELETE FROM $table9 WHERE id = $group_id");
//echo mysql_error();

echo "<center>
<table class='normal' border='0'>
  	
<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='4'></TD>
</TR>
	
<tr>
	<td colspan='4'>&nbsp;</td>
</tr>";

if(mysql_error() == '')
{
	echo "	
	<tr>
		<td colspan='4' style='font-size:10pt; text-align:center;'><font color='green'>Gruppe wurde gel&ouml;scht.</font></td>
	</tr>
		
	<tr>
		<td colspan='4'>&nbsp;</td>
	</tr>
		
	<tr>
		<td colspan='4' align='center'><input type='button' value='Zur&uuml;ck' style='width:100px'; onClick='location.href=\"adminframe.php?item=adminshowgroups\"'></td>
	</tr>";
}
else
{
	echo "	
	<tr>
		<td colspan='4' style='font-size:10pt; text-align:center;'><font color='red'>Es trat ein Fehler auf: <BR>".mysql_error()."</font></td>
	</tr>
		
	<tr>
		<td colspan='4'>&nbsp;</td>
	</tr>
		
	<tr>
		<td colspan='4' align='center'><input type='button' value='Zur&uuml;ck' style='width:100px'; onClick='location.href=\"adminframe.php?item=adminshowgroups\"'></td>
	</tr>";
}

echo "
<tr>
	<td colspan='4'>&nbsp;</td>
</tr>
	
<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='4'></TD>
</TR>

</table>
</center>";
?>