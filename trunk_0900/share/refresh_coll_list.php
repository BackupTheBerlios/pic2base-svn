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

if(array_key_exists('parameter', $_GET))
{
	$parameter = $_GET['parameter'];
}

if(array_key_exists('wert', $_GET))
{
	$wert = $_GET['wert'];
}

if(array_key_exists('modus', $_GET))
{
	$modus = $_GET['modus'];
}

include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/ajax_functions.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/permissions.php';

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');

	if($parameter == 'coll_name')
	{
		$result1 = mysql_query("SELECT $table24.coll_id, $table24.coll_name, $table24.coll_description, $table24.coll_owner, $table1.id, $table1.username 
		FROM $table24, $table1
		WHERE $table24.coll_owner = $table1.id
		AND $table24.coll_name LIKE '%$wert%'");
	}
	elseif($parameter == 'coll_description')
	{
		$result1 = mysql_query("SELECT $table24.coll_id, $table24.coll_name, $table24.coll_description, $table24.coll_owner, $table1.id, $table1.username 
		FROM $table24, $table1
		WHERE $table24.coll_owner = $table1.id
		AND $table24.coll_description LIKE '%$wert%'");
	}

echo mysql_error();

if(mysql_num_rows($result1) > 0)
{
	$num1 = mysql_num_rows($result1);
	if($modus == 'recherche')
	{
		echo "<table class='coll' border='0' style='margin-top:25px; width:100%;'>
		
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
				<td style='width:10%' class='coll'>
					<span style='cursor:pointer;'>
						<img src='../../share/images/eye.gif' title='Kollektion ansehen' onClick=''>
					</span>";
					if(hasPermission($uid, 'downloadallpics',$sr ))
					{
						echo "
						<span style='cursor:pointer;'>
							<img src='../../share/images/eye.gif' title='Kollektion in Ihren Downloadordner herunterladen (".$num2." Bilder)' onClick='location.href=\"../../share/copy_coll_pictures.php?coll_id=$coll_id\"'>
						</span>";
					}
					elseif(hasPermission($uid, 'downloadmypics',$sr ) AND ($coll_owner == $uid))
					{
						echo "
						<span style='cursor:pointer;'>
							<img src='../../share/images/download.gif' title='Kollektion in Ihren Downloadordner herunterladen (".$num2." Bilder)' onClick='location.href=\"../../share/copy_coll_pictures.php?coll_id=$coll_id\"'>
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
		</table>";
	}
	elseif($modus == 'edit')
	{
		echo "
		<table class='coll' border='0'>
	
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
			$username = mysql_result($result1, $i1, 'username');
			
			
			echo "
			<tr style='vertical-align:top;'>
				<td style='width:25%' class='coll'>".$coll_name."</td>
				<td style='width:63%' class='coll'>".$coll_description."</td>
				<td style='width:12%' colspan='3' class='coll'><span style='cursor:pointer;'><img src='../../share/images/edit.gif' style='margin-left:10px; margin-right:5px;' title='Kollektion bearbeiten, neue Bilder hinzuf&uuml;gen, Bilder l&ouml;schen...' onClick='location.href=\"edit_selected_collection.php?coll_id=$coll_id\"'></span>
				<span style='cursor:pointer;'><img src='../../share/images/arrange.gif' style='margin-right:5px;' title='Bilder anordnen, Anzeigedauer und &Uuml;berg&auml;nge festlegen' onClick=''></span>
				<span style='cursor:pointer;'><img src='../../share/images/trash.gif' title='Diese Kollektion entfernen' onClick='sicher(\"$coll_id\");'></span></td>
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
			
			<tr>
				<td colspan='5' style='text-align:center;'><input type='button' style='margin-top:10px; margin-bottom:10px;' value='Neue Kollektion anlegen' title='Eine neue (leere) Kollektion anlegen' onClick='createNewCollection(\"$uid\")'></td>
			</tr>
			
		</table>";	
	}
}
else
{
	echo "<p style='margin-top:50px;'>Es gibt keine Kollektionen entsprechend Ihres Suchkriteriums.</p>";
}
?>
