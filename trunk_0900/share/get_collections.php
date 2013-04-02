<?php
IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../index.php');
}
else
{
	$uid = $_COOKIE['uid'];
}

include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/ajax_functions.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/permissions.php';

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');

IF(hasPermission($uid, 'searchpic', $sr))
{
	$result1 = mysql_query("SELECT $table24.coll_id, $table24.coll_name, $table24.coll_description, $table24.coll_owner, $table1.id, $table1.username 
	FROM $table24, $table1
	WHERE $table24.coll_owner = $table1.id");
	
}
else
{
	echo "<p style='margin-top:150px;'>Sie haben keine Berechtigung, Kollektionen zu suchen!</p>";
}
echo mysql_error();

if(mysql_num_rows($result1) > 0)
{
	$num1 = mysql_num_rows($result1);
	echo "
	<table border='0' style='margin-top:25px; width:100%;'>
	
		<TR class='coll'>
			<TD colspan = '5'><b>Suche nach</b></TD>
		</TR>
	
		<TR class='coll'>
			<TD style='background-color:darkred;' colspan = '5'></TD>
		</TR>
	
		<tr>
			<td style='width:25%'>Name</td>
			<td style='width:65%'>Beschreibung</td>
			<td style='width:10%'>Aktion</td>
		</tr>
		
		<TR class='coll'>
			<TD style='background-color:darkred;' colspan = '5'></TD>
		</TR>
	
		<tr>
			<td style='text-align:left;'><input type='text' name='coll_name' id='coll_name' onkeyup='searchCollection(this.value, \"coll_name\", \"recherche\")'></td>
			<td style='text-align:left;'><input type='text' name='coll_description' id='coll_description' onkeyup='searchCollection(this.value, \"coll_description\", \"recherche\")'></td>
			<td></td>
		</tr>
		
		<TR class='coll'>
			<TD style='background-color:darkred;' colspan = '5'></TD>
		</TR>
		
	</table>
	
	<div id='search_result'>
		<table border='0' style='margin-top:25px;'>
		
			<TR class='coll'>
				<TD colspan = '5'><b>Suchergebnis</b></TD>
			</TR>
		
			<TR class='coll'>
				<TD style='background-color:darkred;' colspan = '5'></TD>
			</TR>";

		for($i1=0; $i1<$num1; $i1++)
		{
			$coll_id = mysql_result($result1, $i1, 'coll_id');
			$coll_name = mysql_result($result1, $i1, 'coll_name');
			$coll_description = mysql_result($result1, $i1, 'coll_description');
			$username = mysql_result($result1, $i1, 'username');
			
			
			echo "
			<tr style='vertical-align:top;'>
				<td style='width:25%'>".$coll_name."</td>
				<td style='width:65%'>".$coll_description."</td>
				<td style='width:10%'><span style='cursor:pointer;'><img src='../../share/images/eye.gif' title='Kollektion ansehen' onClick=''></span></td>
			</tr>";
		
			if($i1 < ($num1 - 1))
			{
				echo "
				<TR class='coll'>
				<TD style='background-color:lightgrey;' colspan = '5'></TD>
				</TR>";
			}
		}
		
		echo "
			<TR class='coll'>
				<TD style='background-color:darkred;' colspan = '5'></TD>
			</TR>
		</table>
	</div>";
}
else
{
	echo "<p style='margin-top:150px;'>Es wurden noch keine Kollektionen angelegt.</p>";
}
?>
<script type="text/javascript">
	document.getElementById("coll_name").focus();
</script>
