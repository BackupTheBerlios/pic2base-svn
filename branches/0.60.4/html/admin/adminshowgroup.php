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
include $sr.'/bin/share/functions/ajax_functions.php';
echo "<CENTER>

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
</style>";

//var_dump($_GET);
$group_id = $_GET['id']; 	// fuer register_globals = off
$col_groups = 2;			//2 Spaltengruppen ; je Gruppe eine Spalte Parameter und eine Spalte Erlaubnis
$content = '';

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
$result = mysql_query("select * from $table9 WHERE id=".$group_id); //usergroups
if (mysql_num_rows($result) == 1)
{
    $desc = utf8_encode(mysql_result ($result, 0, "description"));
}
else
{
	$desc = "[keine Gruppe gew&auml;hlt]";
}

if (hasPermission($uid, 'adminlogin', $sr))
{
	
	echo "<table class='tablenormal' border='0'>
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='4'></TD>
	</TR>
	
	<tr>
	<td colspan='4' style='font-size:12pt; text-align:center;'>Erteilte Berechtigungen f&uuml;r Benutzergruppe >> ".$desc."</td>
	</tr>
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='4'></TD>
	</TR>
		
	<tr>
	<td colspan='4'>&nbsp;</td>
	</tr>";

	$result = mysql_query("select * from $table8 ORDER BY perm_id DESC");
	$num = mysql_num_rows($result);
	$rows = ceil($num/2);
	for ($r = 0; $r < $rows; $r++)
	{
		$i = $r * 2;
		$content = $content."<TR>";
		FOR($cg='0'; $cg<$col_groups; $cg++)
		{
			$perm_id = '';
			$i = ($r * 2) + $cg;
			@$description = trim(utf8_encode(mysql_result($result, $i, "description")));
			@$shortdescription = mysql_result($result, $i, "shortdescription");
			@$perm_id = mysql_result($result, $i, "perm_id");
			
			IF ($description !== '')
			{
				
				$content = $content."<td class='tdbreit'>".$description."</td>";
				
				IF (hasGroupPermission($group_id, $shortdescription))
				{
					$checked = 'checked';
					if ($group_id == '1' AND $shortdescription == 'adminlogin')
					{
						$status = 'disabled';
					}
					else
					{
						$status = 'enabled';
					}
					$text = 'Berechtigung erteilt';
				}
				ELSE
				{
					$checked = '';
					$status = 'enabled';
					$text = 'keine Berechtigung';	
				}
				$content = $content."<TD class='tdschmal'>
				<div id = '$perm_id'>
				<input type=checkbox $checked $status name='cb' title = '$text' onClick='changeGrouppermission(\"$group_id\", \"$perm_id\", \"$checked\", \"$sr\")'>
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
	echo $content."<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='4'></TD>
	</TR>";
}


if (hasPermission($uid, 'showusers', $sr))
{
	echo "<TR>
	<TD colspan='4'>Benutzer in dieser Gruppe:</TD>
	</TR>";
	$result = mysql_query("select * from users WHERE group_id='".$group_id."' ORDER BY id");
	$num = mysql_num_rows($result);
	IF($num == '0')
	{
		echo "<TR>
		<TD colspan='4'>keine</TD>
		</TR>";
	}
	ELSE
	{
		echo "<TR>
		<TD colspan='4'>";
		for ($i = 0; $i < $num; $i++)
		{
			if (mysql_result($result, $i, "username") !== 'pb')
			{
				echo mysql_result($result, $i, "username").($i < ($num - 1) ? ", " :  '');
			}
		}
		echo "</TD>
		</TR>";
	}
	echo "
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='4'></TD>
	</TR>
	
	<tr style='height:30px;'>
	<td class='normal' align='left' colspan='4'>
	<p>Hinweis:</p>
	<p>Ein Klick in das jeweilige Auswahlfeld &auml;ndert <b><u>sofort</u></b> und <b><u>ohne R&uuml;ckfrage</u></b> den Status der Berechtigung.<BR>
	Diese Berechtigungs&auml;nderung wird direkt an alle Mitglieder dieser Gruppe weitergereicht.</p></TD>
	</TR>";
}
echo "</TABLE>
</center>";
?>