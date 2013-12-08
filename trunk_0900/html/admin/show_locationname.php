<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../../index.php');
}
else
{
	$uid = $_COOKIE['uid'];
}
//#########################################################################
//
//Skript listet im Admin-Bereich alle Ortsbezeichnungen zur Bearbeitung auf
//
//#########################################################################
$result = mysql_query("select * from $table2 WHERE City <> 'Ortsbezeichnung' GROUP BY City ORDER BY City");
$num = mysql_num_rows($result);
echo "
<center>
<div id='names' style='width:530px;height:550px;overflow-y:scroll;'>
	<table class='normal' border='0'>
		<tr style='height:3px;'>
			<td class='normal' align='center' bgcolor='darkred' colspan='4'></TD>
		</TR>
		
		<tr>
			<td colspan='4' style='font-size:13pt; text-align:center;'>&Uuml;bersicht &uuml;ber alle vorhandenen Ortsnamen</td>
		</tr>
		
		<TR>
			<TD align='center' colspan = '4'>Klicken Sie auf einen Ortsnamen um diesen zu bearbeiten</TD>
		</TR>
		
		<tr style='height:3px;'>
			<td class='normal' align='center' bgcolor='darkred' colspan='4'></TD>
		</TR>
		
		<tr>
			<td colspan='4'>&nbsp;</td>
		</tr>";
	
		FOR ($i = 0; $i < $num; $i++)
		{
			$pic_id = mysql_result ($result, $i, "pic_id");
			$city = mysql_result ($result, $i, "City");
			
			echo "<TR>
			<TD align='left' colspan='4' style='padding-left:20px;'><a href=adminframe.php?item=admineditlocation&id=".$pic_id.">".$city."</a></TD>
			</TR>";
		}
  
echo "	<tr>
			<td colspan='4'>&nbsp;</td>
		</tr>
		
		<tr style='height:3px;'>
			<td class='normal' align='center' bgcolor='darkred' colspan='4'></TD>
		</TR>
		
		<tr>
			<td colspan='4'>&nbsp;</td>
		</tr>
	  
	</table>
	</div>
</center>";
?>