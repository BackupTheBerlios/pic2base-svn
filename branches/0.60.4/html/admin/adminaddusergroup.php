<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../../index.php');
}
?>

<CENTER>
<FORM name='neue_gruppe' method='post' action='adminaddusergroupexe.php'>

<table class='normal'>
<tr>
<td colspan='2' align='center' style='font-size:12pt; text-align:center;'>
Neue Benutzergruppe hinzuf&uuml;gen
</td>
</FORM>

<tr style='height:3px;'>
<td class='normal' align='center' bgcolor='#FF9900' colspan='2'></TD>
</TR>

<tr>
<td colspan='2'>&nbsp;</td>
</tr>

<tr>
<td align=left>Gruppen-Name:
</td>
<TD><input type=text name=groupname>
</TD>
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
</CENTER>
<script language="javascript">
document.neue_gruppe.groupname.focus();
</script>