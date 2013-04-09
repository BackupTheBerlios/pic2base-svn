<script language="JavaScript">
  	jQuery.noConflict();
	jQuery(document).ready(checkWindowSize);
	jQuery(window).resize(checkWindowSize); 
</script>
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
	$result1 = mysql_query("SELECT * FROM $table24");
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
	<table class='coll' border='0' style='margin-top:25px; width:100%;'>
	
		<TR class='coll'>
			<TD colspan = '5' class='coll'><b>Suche nach</b></TD>
		</TR>
	
		<TR class='coll'>
			<TD style='background-color:darkred;' colspan = '5'></TD>
		</TR>
	
		<tr>
			<td style='width:25%' class='coll'>Name</td>
			<td style='width:65%' class='coll'>Beschreibung</td>
			<td style='width:10%' class='coll'>Aktion</td>
		</tr>
		
		<TR class='coll'>
			<TD style='background-color:darkred;' colspan = '5'></TD>
		</TR>
	
		<tr>
			<td style='text-align:left;' class='coll'><input type='text' name='coll_name' id='coll_name' onkeyup='searchCollection(this.value, \"coll_name\", \"recherche\")'></td>
			<td style='text-align:left;' class='coll'><input type='text' name='coll_description' id='coll_description' onkeyup='searchCollection(this.value, \"coll_description\", \"recherche\")'></td>
			<td></td>
		</tr>
		
		<TR class='coll'>
			<TD style='background-color:darkred;' colspan = '5'></TD>
		</TR>
		
	</table>
	
	<div id='search_result'>
		<table border='0' style='margin-top:25px; width:100%' >
		
			<TR class='coll'>
				<TD colspan = '5' class='coll'><b>Suchergebnis</b></TD>
			</TR>
		
			<TR class='coll'>
				<TD style='background-color:darkred;' colspan = '5'></TD>
			</TR>";

		for($i1=0; $i1<$num1; $i1++)
		{
			$coll_id = mysql_result($result1, $i1, 'coll_id');
			$coll_name = mysql_result($result1, $i1, 'coll_name');
			$coll_description = mysql_result($result1, $i1, 'coll_description');
			$coll_owner = mysql_result($result1, $i1, 'coll_owner');
			//Anzahl der Bilder der Kollektion:
			$result2 = mysql_query("SELECT * FROM $table25 WHERE coll_id = '$coll_id'");
			$num2 = mysql_num_rows($result2);
			
			echo "
			<tr style='vertical-align:top;'>
				<td style='width:25%' class='coll'>".$coll_name."</td>
				<td style='width:65%' class='coll'>".$coll_description."</td>
				<td style='width:10%; padding-left:20px;' class='coll'>";
					if($num2 > 0)
					{
						echo "
						<span style='cursor:pointer;'>
							<img src='../../share/images/eye.gif' title='Kollektion ansehen' onClick='location.href=\"view_collection.php?coll_id=$coll_id\"'>
						</span>";
					}
					if(hasPermission($uid, 'downloadallpics',$sr ) AND $num2 > 0)
					{
						echo "
						<span style='cursor:pointer;'>
							<img src='../../share/images/eye.gif' title='Kollektion in Ihren Downloadordner herunterladen (".$num2." Bilder)' onClick='location.href=\"../../html/recherche/copy_coll_pictures.php?coll_id=$coll_id\"'>
						</span>";
					}
					elseif(hasPermission($uid, 'downloadmypics',$sr ) AND ($coll_owner == $uid) AND $num2 > 0)
					{
						echo "
						<span style='cursor:pointer;'>
							<img src='../../share/images/download.gif' title='Kollektion in Ihren Downloadordner herunterladen (".$num2." Bilder)' onClick='location.href=\"../../html/recherche/copy_coll_pictures.php?coll_id=$coll_id\"'>
						</span>";
					}
					else
					{
						echo "";
					}
				echo "
				</td>
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
