<?php
//####################################################
//
//informative Auflistung der moeglichen Berechtigungen
//
//####################################################
include '../../share/global_config.php';
IF (!$_COOKIE['uid'])
{
  	header('Location: ../../../index.php');
}
	include $sr.'/bin/share/db_connect1.php';
	$result = mysql_query("select * from $table8 ORDER BY perm_id DESC");
	$num = mysql_num_rows($result);
	echo "
	<center>
	<table class='normal' border='0'>
		<tr>
			<td colspan='4' style='font-size:12pt; text-align:center;'>Berechtigungen - informative Auflistung </td>
		</tr>
		
		<tr style='height:3px;'>
			<td class='normal' align='center' bgcolor='#FF9900' colspan='4'></TD>
		</TR>
		
		<tr>
			<td colspan='4'>&nbsp;</td>
		</tr>
		
		<tr>
			<td align=left colspan='2' width='50%'><b>Recht-Bezeichnung</b></td>
			<td align=left colspan='2' width='50%'><b>Kurzbezeichnung</b></td>
		</tr>";
		for ($i = 0; $i < $num; $i++)
		{
		  echo "<tr>
		  <td align=left colspan='2'>".utf8_encode(mysql_result ($result, $i, "description"))."</td>
		  <td align=left colspan='2'>".mysql_result ($result, $i, "perm_id")." - ".mysql_result ($result, $i, "shortdescription")."</td>
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