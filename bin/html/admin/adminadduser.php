<?php

unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
	//echo $c_username;
}
include '../../share/global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	include_once $sr.'/bin/share/functions/permissions.php';
if (hasPermission($c_username, 'adminlogin'))
{
	echo "
	<center>
	<form method='post' name='aau' action='adminadduserexe.php'>
	<table class='normal'>
	<tr>
	<td colspan='2' align='center' style='font-size:12pt; text-align:center;'>
	Neuen Benutzer hinzuf�gen
	</td>
	</tr>
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='2'></TD>
	</TR>
	
	<tr>
	<td colspan='2'>&nbsp;</td>
	</tr>
	
	<tr>
	<td align=left colspan='2'>Hinweis: Der  Benutzername darf keine Leerzeichen oder Sonderzeichen enthalten<BR>
	und darf h�chstens 15 Zeichen lang sein.!<BR>
	Die mit * gekennzeichneten Felder M�SSEN ausgef�llt werden.
	</td>
	</tr>
	
	<tr>
	<td align=left colspan='2'>
	&nbsp;
	</td>
	</tr>
	
	<tr>
	<td align=left>
	Benutzername: *
	</td>
	<td>
	<input type=text name=benutzername class='Auswahl150'>
	</td>
	</tr>
	
	<tr>
	<td align=left>
	Titel:
	</td>
	<td>
	<input type=text name=titel class='Auswahl150'>
	</td>
	</tr>
	
	<tr>
	<td align=left>
	Name: *
	</td>
	<td><input type=text name=name class='Auswahl150'>
	</td>
	</tr>
	
	<tr>
	<td align=left>
	Vorname: *
	</td>
	<td>
	<input type=text name=vorname class='Auswahl150'>
	</td>
	</tr>
	
	<tr>
	<td align=left>
	Stra�e:
	</td>
	<td>
	<input type=text name=strasse class='Auswahl150'>
	</td>
	</tr>
	
	<tr>
	<td align=left>
	PLZ:
	</td>
	<td>
	<input type=text name=plz class='Auswahl150'>
	</td>
	</tr>
	
	<tr>
	<td align=left>
	Ort:
	</td>
	<td>
	<input type=text name=ort class='Auswahl150'>
	</td>
	</tr>
	
	<tr>
	<td align=left>
	PWD: *
	</td>
	<td>
	<input type=password name=pwd class='Auswahl150'>
	</td>
	</tr>
	
	<tr>
	<td align=left>
	Berechtigung:
	</td>
	<td>
	<select name='group' class='Auswahl150'>";
	$result1 = mysql_query("SELECT * FROM $table9 ORDER BY description");
	$num1 = mysql_num_rows($result1);
	FOR($i1='0'; $i1<$num1; $i1++)
	{
		$id = mysql_result($result1, $i1, 'id');
		$description = mysql_result($result1, $i1, 'description');
		IF($description == 'Gast')
		{
			$sel = 'selected';
		}
		ELSE
		{
			$sel = '';
		}
		echo "<option value=$id $sel>".$description."</option>";
	}
	echo "</SELECT>
	</td>
	</tr>
	
	<tr>
	<td align=left>
	Telefon:
	</td>
	<td>
	<input type=text name=telefon class='Auswahl150'>
	</td>
	</tr>
	
	<tr>
	<td align=left>
	e-Mail:
	</td>
	<td>
	<input type=text name=email class='Auswahl150'>
	</td>
	</tr>
	
	<tr>
	<td align=left>
	Internet:
	</td>
	<td>
	<input type=text name=internet class='Auswahl150'>
	</td>
	</tr>
	
	<tr>
	<td align=left colspan='2'>&nbsp;
	</td>
	</tr>
	
	<tr>
	<td align=center colspan='2'>
	<INPUT type='button' value='Abbrechen' OnClick='location.href=\"adminframe.php?item=adminshowusers\"'>&nbsp;&nbsp;<input type=submit value='Hinzuf�gen'>
	</td>
	</tr>
	
	<tr>
	<td colspan='2'>&nbsp;</td>
	</tr>
		
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='2'></TD>
	</TR>
	
	</table>
	</form>";
}
ELSE
{
	echo "Sie haben nicht gen�gend Berechtigungen!";
}
?>
<script language="javascript">
document.aau.benutzername.focus();
</script>