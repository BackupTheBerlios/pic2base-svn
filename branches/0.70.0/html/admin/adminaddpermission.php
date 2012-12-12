<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
  	header('Location: ../../../index.php');
}
else
{
	$uid = $_COOKIE['uid'];
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include_once $sr.'/bin/share/functions/permissions.php';
if (hasPermission($uid, 'adminlogin', $sr))
{
	echo "
	<CENTER>
	<FORM method='post' action='adminaddpermissionexe.php'>
	
	<table class='normal'>
	<tr>
	<td colspan='2' align='center' style='font-size:12pt; text-align:center;'>
	Neue Berechtigung hinzuf&uuml;gen
	</td>
	</FORM>
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='2'></TD>
	</TR>
	
	<tr>
	<td colspan='2'>&nbsp;</td>
	</tr>
	
	<tr>
	<td colspan='2'><p style='color:red; text-align:center'>ACHTUNG! - Diese Funktion nur als Programmierhilfe verwenden!</p></td>
	</tr>
	
	<tr>
	<td align=left>Bezeichnung:</td>
	<td><input type=text name=description></td>
	</tr>
	
	<tr>
	<td align=left>Kurzbezeichnung:</td>
	<td><input type=text name=shortdescription></td>
	</tr>
	
	<tr>
	<td align=left>Berechtigungs-ID:</td>
	<td><input type=text name=permission_id></td>
	</tr>
	
	<tr>
	<tr colspan='2'>&nbsp;</td>
	</tr>
	
	<TR>
	<TD colspan='2' align='center'><input type=submit value='Hinzuf&uuml;gen'>
	</TD>
	</TR>
	
	<tr>
	<td colspan='2'>&nbsp;</td>
	</tr>
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='2'></TD>
	</TR>
	
	</table>
	</form>
	</CENTER>";

}
ELSE
{
	echo "Sie haben nicht gen&uuml;gend Berechtigungen!";
}
?>