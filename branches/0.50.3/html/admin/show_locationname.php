<?php
IF (!$_COOKIE['login'])
{
include '../../share/global_config.php';
//var_dump($sr);
  header('Location: ../../../index.php');
}

  mysql_connect ($db_server, $user, $PWD);
  $result = mysql_query("select * from $table12 WHERE location <> 'Ortsbezeichnung' GROUP BY location ORDER BY location");
  $num = mysql_num_rows($result);
  echo "<center><table class='normal' border='0'>
	<tr>
	<td colspan='4' style='font-size:12pt; text-align:center;'>&Uuml;bersicht &uuml;ber alle vorhandenen Ortsnamen</td>
	</tr>
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='4'></TD>
	</TR>
	
	<tr>
	<td colspan='4'>&nbsp;</td>
	</tr>";
	
  FOR ($i = 0; $i < $num; $i++)
  {
	$loc_id = mysql_result ($result, $i, "loc_id");
	$location = mysql_result ($result, $i, "location");
	
	echo "<TR>
	<TD align='left' colspan='4' style='padding-left:20px;'><a href=adminframe.php?item=admineditlocation&id=".$loc_id.">".$location."</a></TD>
	</TR>";
  }
  
  echo "<tr>
	<td colspan='4'>&nbsp;</td>
	</tr>
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='4'></TD>
	</TR>
  
  	<TR>
	<TD align='center' colspan = '4'>Klicken Sie auf einen Ortsnamen um diesen zu bearbeiten</TD>
	</TR>
	</table></center>";
?>