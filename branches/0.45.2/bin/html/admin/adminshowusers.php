<?php
IF (!$_COOKIE['login'])
{
include '../../share/global_config.php';
//var_dump($sr);
  header('Location: ../../../index.php');
}

	include '../../share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	mysql_connect ($db_server, $user, $PWD);
	$result = mysql_query("select * from users ORDER BY username");
	$num = mysql_num_rows($result);
	echo "<center>
	
	<table class='normal' border='0'>
	<tr>
	<td colspan='6' style='font-size:12pt; text-align:center;'>&Uuml;bersicht &uuml;ber alle zur Zeit registrierten pic2base-User</td>
	</tr>
	
	<TR style='height:3px;'>
	<TD class='normal' align='center' bgcolor='#FF9900' colspan='6'></TD>
	</TR>
	
	<tr>
	<td colspan='5'>&nbsp;</td>
	</tr>
	
	<tr>
	<td width='125'>Benutzername</td>
	<td width='125'>jetzige Gruppe</td>
	<td width='50'>aktiv</td>
	<td width='150'>neue Gruppe</td>
	<td width='50'>Gruppe &auml;ndern</td>
	<td width='50' align='center'>User l&ouml;schen</td>
	</tr>";
	for ($i = 0; $i < $num; $i++)
	{
		$user_id = mysql_result ($result, $i, "id");
		$user_name = mysql_result ($result, $i, "username");
		$group_id = mysql_result ($result, $i, "group_id");
		$aktiv = mysql_result ($result, $i, "aktiv");
		($aktiv == '1')? $bgcolor = '':$bgcolor='yellow';
		echo "
		<FORM name='$user_id' method='post' action = 'make_changes.php?mod=user&id=$user_id'>
		<tr bgcolor=$bgcolor>
		<td><a href=adminframe.php?item=adminshowuser&id=".$user_id."&del=0 title='Details des Users anzeigen'>".$user_name."</a></td>";
		$result2 = mysql_query("select * from usergroups where id= '$group_id'");
		if (mysql_num_rows($result2) == 1)
		{
			$gr_id = mysql_result ($result2, 0, "id");
			$desc = mysql_result ($result2, 0, "description");
			echo "<td><a href=adminframe.php?item=adminshowgroup&id=".$gr_id." title='Details der Gruppe anzeigen'>".$desc."</a></td>";
		}
		$result3 = mysql_query( "SELECT * FROM $table9 ORDER BY 'id'");
		$num3 = mysql_num_rows($result3);
		IF($aktiv == '1')
		{
			$aktiv = 'ja';
			echo "
			<TD><A HREF='adminchangeuseractivity.php?user_id=$user_id' title='User aktiv / inaktiv setzen'>".$aktiv."</A></TD>
			<TD>
			<SELECT name='gruppe' style='width:100px';>";
			FOR($i3=0; $i3<$num3; $i3++)
			{
				$grp = mysql_result($result3, $i3, 'description');
				IF($grp == $desc)
				{
					$sel = 'selected';
				}
				ELSE
				{
					$sel = '';
				}
				$id = mysql_result($result3, $i3, 'id');
				echo "<option VALUE=$id $sel>".$grp."</option>";
			}
			echo "	
			</SELECT>
			</TD>
			<TD width='50'><input type=submit value='OK'></TD>
			<TD><input type='button' value='User l&ouml;schen' OnClick='location.href=\"adminframe.php?item=adminshowuser&id=$user_id&del=1\"'></TD>
			</tr>";
		}
		ELSE
		{
			$aktiv = 'nein';
			echo "
			<TD><A HREF='adminchangeuseractivity.php?user_id=$user_id'>".$aktiv."</A></TD>
			<TD></TD>
			<TD></TD>
			<TD><input type='button' value='User l&ouml;schen' OnClick='location.href=\"adminframe.php?item=adminshowuser&id=$user_id&del=1\"'></TD>
			</TR>";
		}
		
		echo "
		</FORM>";
	}
	echo "
	<tr>
	<td colspan='6'>&nbsp;</td>
	</tr>
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='6'></TD>
	</TR>
	</table>
	</center>";
?>