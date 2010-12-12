<?php
IF (!$_COOKIE['login'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
	header('Location: ../../../index.php');
}

mysql_connect ($db_server, $user, $PWD);
$result = mysql_query("select * from permissions ORDER BY perm_id DESC");
$num = mysql_num_rows($result);
echo "
	<center>
	<table class='normal' border='0'>
	<tr>
	<td colspan='4' style='font-size:12pt; text-align:center;'>Berechtigungen</td>
	</tr>
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='4'></TD>
	</TR>
	
	<tr>
	<td colspan='4'>&nbsp;</td>
	</tr>
	
	<tr>
	<td align=left colspan='2' width='50%'><b>Bezeichnung</b></td>
	<td align=left colspan='2' width='50%'><b>Kurzbezeichnung</b></td>
	</tr>";
for ($i = 0; $i < $num; $i++)
{
	echo "<tr>
	  <td align=left colspan='2'>".mysql_result ($result, $i, "description")."</td>
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
	</table></center>";
?>