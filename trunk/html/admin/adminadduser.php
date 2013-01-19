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

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include_once $sr.'/bin/share/functions/permissions.php';

//Kontrolle, ob bereits ein erster Admin angelegt wurde:
$result0 = mysql_query("SELECT * FROM $table1");
$num0 = mysql_num_rows($result0);
$username = mysql_result($result0, isset($i0), 'username');
IF($num0 == 1 AND $username == 'pb')
{
	$erstinstallation = 1;
}
ELSEIF($num0 > 1)
{
	$erstinstallation = 0;
}

if (hasPermission($uid, 'adminlogin', $sr))
{
	echo "
	<center>
	<form method='post' name='aau' action='adminadduserexe.php'>
	<table class='normal' border='0'>
	<tr>
	<td colspan='3' align='center' style='font-size:12pt; text-align:center;'>
	Neuen Benutzer hinzuf&uuml;gen
	</td>
	</tr>
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='darkred' colspan='3'></TD>
	</TR>
	
	<tr>
	<td colspan='3'>&nbsp;</td>
	</tr>
	
	<tr>
	<td align='center' colspan='3'><u>Hinweis:</u> Die mit * gekennzeichneten Felder M&Uuml;SSEN ausgef&uuml;llt werden.
	</td>
	</tr>
	
	<tr>
	<td align=left colspan='3'>
	&nbsp;
	</td>
	</tr>
	
	<tr>
	<td width = '100'></td>
	<td align=left width='150'>
	Benutzername: *
	</td>
	<td width='250'>
	<input type=text name=benutzername class='Auswahl150'>
	</td>
	</tr>
	
	<tr>
	<td width = '100'></td>
	<td align=left>
	Titel:
	</td>
	<td>
	<input type=text name=titel class='Auswahl150'>
	</td>
	</tr>
	
	<tr>
	<td width = '100'></td>
	<td align=left>
	Name: *
	</td>
	<td><input type=text name=name class='Auswahl150'>
	</td>
	</tr>
	
	<tr>
	<td width = '100'></td>
	<td align=left>
	Vorname: *
	</td>
	<td>
	<input type=text name=vorname class='Auswahl150'>
	</td>
	</tr>
	
	<tr>
	<td width = '100'></td>
	<td align=left>
	Stra&szlig;e:
	</td>
	<td>
	<input type=text name=strasse class='Auswahl150'>
	</td>
	</tr>
	
	<tr>
	<td width = '100'></td>
	<td align=left>
	PLZ:
	</td>
	<td>
	<input type=text name=plz class='Auswahl150'>
	</td>
	</tr>
	
	<tr>
	<td width = '100'></td>
	<td align=left>
	Ort:
	</td>
	<td>
	<input type=text name=ort class='Auswahl150'>
	</td>
	</tr>
	
	<tr>
	<td width = '100'></td>
	<td align=left>
	PWD: *
	</td>
	<td>
	<input type=password name=pwd class='Auswahl150'>
	</td>
	</tr>
	
	<tr>
	<td width = '100'></td>
	<td align=left>
	Berechtigung:
	</td>";
	IF($erstinstallation == 1)
	{
		echo "<td>
		<select name='group' class='Auswahl150'>
		<option value=1>Admin</option>
		</select>
		</td>";
	}
	ELSE
	{
		echo "<td>
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
		</td>";
	}
	echo "
	</tr>
	
	<tr>
	<td width = '100'></td>
	<td align=left>
	Telefon:
	</td>
	<td>
	<input type=text name=telefon class='Auswahl150'>
	</td>
	</tr>
	
	<tr>
	<td width = '100'></td>
	<td align=left>
	e-Mail:
	</td>
	<td>
	<input type=text name=email class='Auswahl150'>
	</td>
	</tr>
	
	<tr>
	<td width = '100'></td>
	<td align=left>
	Internet:
	</td>
	<td>
	<input type=text name=internet class='Auswahl150'>
	</td>
	</tr>
	
	<tr>
	<td width = '100'></td>
	<td align=left>
	Sprache:
	</td>
	<td>
	<SELECT name=language class='Auswahl150'>
	<option value='de'>Deutsch</option>
	<option value='en'>English</option>
	<option value='ru'>Russisch</option>
	<option value='cs'>Czech</option>
	<option value='es'>Espanol</option>
	<option value='fr'>Francias</option>
	<option value='it'>Italiano</option>
	<option value='ja'>Japanese</option>
	<option value='ko'>Korean</option>
	<option value='nl'>Nederlands</option>
	<option value='pl'>Polski</option>
	<option value='sv'>Svenska</option>
	<option value='tr'>T&uuml;rkce</option>
	</td>
	</tr>
	
	<tr>
	<td align=left colspan='3'>&nbsp;
	</td>
	</tr>
	
	<tr>
	<td align=center colspan='3'>
	<INPUT type='button' value='Abbrechen' OnClick='location.href=\"adminframe.php?item=adminshowusers\"'>&nbsp;&nbsp;<input type=submit value='Hinzuf&uuml;gen'>
	</td>
	</tr>
	
	<tr>
	<td colspan='3'>&nbsp;</td>
	</tr>
		
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='darkred' colspan='3'></TD>
	</TR>
	
	</table>
	</form>";
}
ELSE
{
	echo "Sie haben nicht gen&uuml;gend Berechtigungen!";
}
?>
<script language="javascript">
document.aau.benutzername.focus();
</script>