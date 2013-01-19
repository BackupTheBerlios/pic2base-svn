<?php
//+++++++++++++++++++++++++++++++++++++++++++++++++++++
//Anzeige des ausgewaehlten Benutzers und dessen Rechte
//+++++++++++++++++++++++++++++++++++++++++++++++++++++
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
	header('Location: ../../../index.php');
}
include $sr.'/bin/share/functions/ajax_functions.php';
$user_id = $_GET['id']; //Benutzer-ID des anzuzeigenden Benutzers
$del = $_GET['del'];


if (hasPermission($uid, 'adminlogin', $sr))
{
   	include '../../share/db_connect1.php';
	$content = '';
	$result = mysql_query("select * from $table1 WHERE id=".$user_id);
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
		//$user_id = $id;
    	$groupid = mysql_result ($result, 0, "group_id");
		$username = mysql_result ($result, 0, "username");
		echo "
		<style type='text/css'>
		<!--
		.tablenormal	{
				width:720px;
				margin-left:0px;
				}
				
		.tdbreit	{
				width:200px;
				text-align:right;
				}
				
		.tdschmal	{
				width:40px;
				text-align:center;
				}
		-->
		</style>
		
		<center>
		<FORM name = 'del_user' method='post' action='adminframe.php?item=deleteuser&id=$user_id'>
		<table class='tablenormal' border='0'>
		
		<tr style='height:3px;'>
		<td class='normal' align='center' bgcolor='darkred' colspan='4'></TD>
		</TR>
		
		<tr>
		<td colspan='4' style='font-size:12pt; text-align:center;'>".$warnung." Benutzerprofil f&uuml;r >> ".$username."</td>
		</tr>
		
		<tr style='height:3px;'>
		<td class='normal' align='center' bgcolor='darkred' colspan='4'></TD>
		</TR>
			
		<tr>
		<td align=left>Gruppe:</td>
		<td align=left colspan='3'>";
		
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
    <td align=left colspan='3'>".$name.", ".$vorname."</td>
    </tr>
    
    <tr>
    <td align=left>Stra&szlig;e:</td>
    <td align=left colspan='3'>".mysql_result ($result, 0, "strasse")."</td>
    </tr>
    
    <tr>
    <td align=left>PLZ, Ort:</td>
    <td align=left colspan='3'>".mysql_result ($result, 0, "plz").", ".mysql_result ($result, 0, "ort")."</td>
    </tr>
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='darkred' colspan='4'></TD>
	</TR>";
    
	$col_groups = 2;	//2 Spaltengruppen ; je Gruppe eine Spalte Parameter und eine Spalte Erlaubnis
	echo "
	<tr>
	<td colspan='4' style='font-size:12pt; text-align:center;'>Erteilte Berechtigungen</td>
	</tr>
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='darkred' colspan='4'></TD>
	</TR>
		
	<tr>
	<td colspan='4'>&nbsp;</td>
	</tr>";
	
	$result = mysql_query("select * from permissions ORDER BY perm_id DESC");
	$num = mysql_num_rows($result);
	$rows = ceil($num/2);
	for ($r = 0; $r < $rows; $r++)
	{
		$i = $r * 2;
		$content = $content."<TR>";
		FOR($cg='0'; $cg<$col_groups; $cg++)
		{
			$i = ($r * 2) + $cg;
			@$description = trim(mysql_result($result, $i, "description"));
			@$shortdescription = mysql_result($result, $i, "shortdescription");
			@$perm_id = mysql_result($result, $i, "perm_id");
			IF ($description !== '')
			{
				$content = $content."<td class='tdbreit'>".$description."</td>";
				
				IF (hasPermission($user_id, $shortdescription, $sr))
				{
					$checked = 'checked';
					$text = 'Berechtigung erteilt';
				}
				ELSE
				{
					$checked = '';
					$text = 'keine Berechtigung';	
				}
				
				$content = $content."<TD class='tdschmal'>
				<div id = '$perm_id'>
				<input type=checkbox name='cb' $checked title= '$text' onClick='changeUserpermission(\"$user_id\", \"$perm_id\", \"$checked\", \"$sr\")'>
				</div>
				</td>";
			}
			ELSE
			{
				$content = $content."<td class='tdbreit'></td><TD class='tdschmal'></td>";
			}
		}
		$content = $content."</TR>";
	}
	echo $content."<tr>
	<td colspan='4'>&nbsp;</td>
	</tr>

	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='darkred' colspan='4'></TD>
	</TR>
	
	<tr style='height:30px;'>
	<td class='normal' align='left' colspan='4'>
	Hinweis: Ein Klick in das jeweilige Auswahlfeld &auml;ndert sofort <b>und ohne R&uuml;ckfrage</b> den Status der Berechtigung.</TD>
	</TR>";
}

// .. wenn der User geloescht werden soll:
IF($del == '1')
{
  	echo "
  	
	<TR style='height:3px;'>
	<td class='normal' align='center' bgcolor='darkred' colspan='4'></TD>
	</TR>
	
	<tr>
	<td colspan='4'>&nbsp;</td>
	</tr>
	
  	<TR>
  	<TD colspan='4' align='center'>An wen sollen die Bilder von ".$vorname." ".$name." &uuml;bertragen werden?</TD>
  	</TR>
  	
  	<TR align='center'>
  	<TD align='right' style='padding-right:10px; padding-bottom:10px'>Neuer Eigent&uuml;mer:</TD>
  	<TD align='left' style='padding-left:10px; padding-bottom:10px' colspan='3'>
  	<SELECT name='users' style='width:200px;'>
  	<OPTION Value = '' selected>--- neuen User ausw&auml;hlen ---</OPTION>";
  	$result3 = mysql_query( "SELECT * FROM $table1 WHERE id <> '$user_id'");
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
  	<TD align='right' style='padding-right:10px'><input type='button' Value='Abbrechen' OnClick='location.href=\"adminframe.php?item=adminshowusers\"'></TD>
  	<TD align='left'  style='padding-left:10px' colspan='3'><input type='submit' Value='$username l&ouml;schen und Bild-Rechte &uuml;bertragen'></TD>
  	</TR>
  	
  	<tr>
	<td colspan='4'>&nbsp;</td>
	</tr>
  	
  	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='darkred' colspan='4'></TD>
	</TR>";
}
?>
</table>
</form>
</center>

