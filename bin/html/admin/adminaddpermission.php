<?
unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = split(',',$_COOKIE['login']);
	//echo $c_username;
}
include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include_once $sr.'/bin/share/functions/permissions.php';
if (hasPermission($c_username, 'adminlogin'))
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
	<td align=left>Bezeichnung:</td>
	<td><input type=text name=description></td>
	</tr>
	
	<tr>
	<td align=left>Kurzbezeichnung:</td>
	<td><input type=text name=shortdescription></td>
	</tr>
	
	<tr>
	<tr colspan='2'>&nbsp;</td>
	</tr>
	
	<TR>
	<TD colspan='2' align='center'><input type=submit value='Hinzufügen'>
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
	echo "Sie haben nicht genügend Berechtigungen!";
}
?>