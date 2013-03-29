<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
  	header('Location: ../../../index.php');
}

//#######################################################
//
//Liste aller angelegten Benutzergruppen
//
//#######################################################

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
$result = mysql_query("select * from $table9 ORDER BY description");
$num = mysql_num_rows($result);
echo "<center><table class='normal' border='0'>
<tr>
<td colspan='4' style='font-size:12pt; text-align:center;'>&Uuml;bersicht &uuml;ber alle verf&uuml;gbaren Benutzer-Gruppen</td>
</tr>

<tr style='height:3px;'>
<td class='normal' align='center' bgcolor='darkred' colspan='4'></TD>
</TR>

<tr>
<td colspan='4'>&nbsp;</td>
</tr>";

FOR ($i = 0; $i < $num; $i++)
{
	$group_id = mysql_result ($result, $i, "id"); //echo $group_id;
	$group_desc = mysql_result ($result, $i, "description");
	//Im Auslieferungszustand hat die Admin-Gruppe die id=1. Diese Gruppe kann nicht geloescht werden!
	
	echo "<tr>
	<td align='center' colspan='2'><a href=adminframe.php?item=adminshowgroup&id=".$group_id.">".$group_desc."</a></td>
	<TD align='center' colspan = '2'>";
	if ($group_id !== '1')
	{
		echo "<input type=button value='Gruppe l&ouml;schen' onClick='location.href=\"adminframe.php?item=del_group&group_id=$group_id\"'>";
	}
	else
	{
		echo "Diese Gruppe kann nicht gel&ouml;scht werden!";
	}
	echo "
	</TD>
	</tr>";
}
  
echo "<tr>
	<td colspan='4'>&nbsp;</td>
	</tr>
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='darkred' colspan='4'></TD>
	</TR>
  
  	<TR>
	<TD align='center' colspan = '4'>Klicken Sie auf einen Gruppen-Namen um deren Eigenschaften zu &auml;ndern</TD>
	</TR>
	</table>
</center>";
?>