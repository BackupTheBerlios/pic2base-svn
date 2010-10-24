<?php
IF (!$_COOKIE['login'])
{
include '../../share/global_config.php';
//var_dump($sr);
  header('Location: ../../../index.php');
}

  $id = $_GET['id']; 
  $del = $_GET['del'];
    
  if (hasPermission($c_username, 'adminlogin'))
  {
   
    mysql_connect ($db_server, $user, $PWD);
    $result = mysql_query("select * from $table1 WHERE id=".$id);
    $vorname = mysql_result ($result, 0, "vorname");
    $name = mysql_result ($result, 0, "name");
    IF($del == '1')
    {
    	$warnung = "<FONT color='red'><b><blink>L&Ouml;SCHE</blink></b></FONT>";
    }
    ELSE
    {
    	$warnung = '';
    }
    if (mysql_num_rows($result) == 1)
    {
		$user_id = $id;
    	$groupid = mysql_result ($result, 0, "group_id");
		$username = mysql_result ($result, 0, "username");
		echo "<center>
		<FORM name = 'del_user' method='post' action='adminframe.php?item=deleteuser&id=$id'>
		<table class='normal' border='0'>
		<tr>
		<td colspan='3' style='font-size:12pt; text-align:center;'>".$warnung." Benutzerprofil f&uuml;r User ".$username."</td>
		</tr>
		
		<tr style='height:3px;'>
		<td class='normal' align='center' bgcolor='#FF9900' colspan='3'></TD>
		</TR>
			
		<tr>
		<td align=left>Gruppe:</td>
		<td align=left colspan='2'>";
		
		$result2 = mysql_query("select * from usergroups WHERE id=".$groupid);
		if (mysql_num_rows($result2) == 1)
		{
			echo mysql_result ($result2, 0, "description");
		}
		else
		{
			echo "[keine Gruppe gew&auml;hlt]";
		}
    }
    echo "</td>
    </tr>
    
    <tr>
    <td align=left>Name, Vorname:</td>
    <td align=left colspan='2'>".$name.", ".$vorname."</td>
    </tr>
    
    <tr>
    <td align=left>Stra&szlig;e:</td>
    <td align=left colspan='2'>".mysql_result ($result, 0, "strasse")."</td>
    </tr>
    
    <tr>
    <td align=left>PLZ, Ort:</td>
    <td align=left colspan='2'>".mysql_result ($result, 0, "plz").", ".mysql_result ($result, 0, "ort")."</td>
    </tr>
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='3'></TD>
	</TR>";
    
   
  }
  if (hasPermission($c_username, 'adminlogin'))
  {
	echo "<tr>
	<td colspan='3' style='font-size:12pt; text-align:center;'>Erteilte Berechtigungen</td>
	</tr>
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='3'></TD>
	</TR>
		
	<tr>
	<td colspan='3'>&nbsp;</td>
	</tr>
	
	<tr>
	<td width=250 align=left><b>Parameter</b></td>
	<td width=100 align=center><b>Erlaubnis</b></td>";
	if (hasPermission($c_username, 'adminlogin'))
  	{
  	  echo "<td width=100 align=center><b>Status &auml;ndern</b></td>";
  	}
  	echo "
	</tr>";
	//include "../share/functions/permissions.php";
	$result = mysql_query("select * from permissions ORDER BY perm_id DESC");
	$num = mysql_num_rows($result);
	for ($i = 0; $i < $num; $i++)
	{
		$description = mysql_result($result, $i, "description");
		$perm_id = mysql_result($result, $i, "perm_id");
		IF ($description !== '')
		{
			echo "<tr>
			<td align=left>".$description."</td>";
			if (hasPermission($username, mysql_result($result, $i, "shortdescription")))
			{
				echo "<td align=center><img src='../../share/images/ok.gif' title='Berechtigung erteilt'></td>";
			}
			else
			{
				echo "<td align=center><img src='../../share/images/delete.gif' title='keine Berechtigung'></td>";
			}
			if (hasPermission($c_username, 'adminlogin'))
			{
				echo "<td align=center><a href=adminchangeuserpermissionflag.php?user_id=".$id."&perm_id=".$perm_id.">
				<img src='../../share/images/edit.gif' title='Erlaubnis-Status &auml;ndern' style='border:none'>
				</a>
				</td>";
			}
			ELSE
			{
				echo "<td align=center>&nbsp;</td>";
			}
			echo "</tr>";
		}
	}
	echo "<tr>
	<td colspan='3'>&nbsp;</td>
	</tr>

	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='3'></TD>
	</TR>";
  }
  IF($del == '1')
  {
  	echo "
  	<tr>
	<td colspan='3'>&nbsp;</td>
	</tr>
	
  	<TR>
  	<TD colspan='3' align='center'>An wen sollen die Rechte von ".$vorname." ".$name." &uuml;bertragen werden?</TD>
  	</TR>
  	
  	<TR align='center'>
  	<TD>Neuer Eigent&uuml;mer:</TD>
  	<TD colspan='2'><SELECT name='users' style='width:200px;'>
  	<OPTION Value = '' selected>--- neuen User ausw&auml;hlen ---</OPTION>";
  	$result3 = mysql_query( "SELECT * FROM $table1 WHERE id <> '$id'");
  	$num3 = mysql_num_rows($result3);
  	FOR($i3='0'; $i3<$num3; $i3++)
  	{
  	$Vorname = mysql_result($result3, $i3, 'vorname');
  	$Name = mysql_result($result3, $i3, 'Name');
  	$ID = mysql_result($result3, $i3, 'id');
  	echo "<OPTION value='$ID'>".$Vorname." ".$Name."</OPTION>";
  	}
  	echo "
  	</SELECT>
  	</TD>
  	</TR>
  	
  	<TR align='center'>
  	<TD><input type='button' Value='Abbrechen' OnClick='location.href=\"adminframe.php?item=adminshowusers\"'></TD>
  	<TD colspan='2'><input type='submit' Value='alten User l&ouml;schen und Rechte &uuml;bertragen'></TD>
  	</TR>
  	
  	<tr>
	<td colspan='3'>&nbsp;</td>
	</tr>
  	
  	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='3'></TD>
	</TR>";
  }
?>
</table>
</form>
</center>

