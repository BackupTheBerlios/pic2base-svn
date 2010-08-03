<?php
IF (!$_COOKIE['login'])
{
include '../../share/global_config.php';
//var_dump($sr);
  header('Location: ../../../index.php');
}

  mysql_connect ($db_server, $user, $PWD);
  mysql_select_db($db);
  $group_id = $_GET['group_id'];
  
  //echo "Gruppen-Nr: ".$group_id."<BR>";
  //Ermittlung, ob noch User zu der zu loeschenden Gruppe gehoeren:
  $result1 = mysql_query( "SELECT * FROM $table1 WHERE group_id = $group_id");
  echo mysql_error();
  $num1 = mysql_num_rows($result1);
  //echo "Gruppenmitglieder: ".$num1."<BR>";
  $result2 = mysql_query( "SELECT * FROM $table9 WHERE id = $group_id");
  $group_desc = mysql_result($result2, isset($i2), 'description');
  
  IF($num1 == '0')
  {
  	echo "<center>
  	<table class='normal' border='0'>
	<tr>
	<td colspan='4' style='font-size:12pt; text-align:center;'>Wollen Sie die Gruppe <u>".$group_desc."</u> wirklich l&ouml;schen?</td>
	</tr>
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='4'></TD>
	</TR>
	
	<tr>
	<td colspan='4'>&nbsp;</td>
	</tr>
	
	<tr>
	<td colspan='2' align='center'><input type='button' value='Ja' style='width:100px'; onClick='location.href=\"adminframe.php?item=del_group_exe&group_id=$group_id\"'></td>
	<td colspan='2' align='center'><input type='button' value='Nein' style='width:100px'; onClick='location.href=\"adminframe.php?item=adminshowgroups\"'></td>
	</tr>
  
  	<tr>
	<td colspan='4'>&nbsp;</td>
	</tr>
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='4'></TD>
	</TR>
  
	</table>
	</center>";
  }
  ELSE
  {
  	echo "<center>
  	<table class='normal' border='0'>
	<tr>
	<td colspan='4' style='font-size:12pt; text-align:center;'><font color='red'><b>ACHTUNG</b></font></td>
	</tr>
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='4'></TD>
	</TR>
	
	<tr>
	<td colspan='4' align='center'>Die folgenden User geh&ouml;ren noch zur gew&auml;hlten Gruppe <u>".$group_desc."</u>:<BR><BR>";
	FOR($i1='0'; $i1<$num1; $i1++)
	{
		$username = mysql_result($result1, $i1, 'username');
		echo $username."<BR>";
	}
	echo "<BR>
	Ordnen Sie diese User erst anderen Gruppen zu und l&ouml;schen Sie dann die gew&uuml;nschte Gruppe.<BR><BR></td>
	</tr>
	
	<tr>
	<td colspan='4' align='center'><input type='button' value='Zur&uuml;ck' style='width:100px'; onClick='location.href=\"adminframe.php?item=adminshowgroups\"'></td>
	</tr>
  
  	
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='4'></TD>
	</TR>
  
	</table>
	</center>";
  }
?>