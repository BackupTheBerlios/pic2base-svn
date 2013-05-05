<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
	header('Location: ../../../index.php');
}
$uid = $_COOKIE['uid'];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Kollektion bearbeiten</TITLE>
	<META NAME="GENERATOR" CONTENT="Eclipse">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format2.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script language="JavaScript" src="../../share/functions/resize_elements.js"></script>
	<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
	<script language="JavaScript" src="../../share/functions/jquery-1.8.2.min.js"></script>
	<script language="JavaScript">
		jQuery.noConflict();
		jQuery(document).ready(checkWindowSize);
		jQuery(window).resize(checkWindowSize); 

		function sicher(coll_id)
		{
			check = confirm("Soll diese Kollektion wirklich entfernt werden?");
			if(check == true)
			{
				location.href='../../share/del_coll_action.php?coll_id=' + coll_id;
			}
		}

		function searchCollection(wert, feld, modus)
		{
			if(wert === "")
			{
				location.reload();
			}
			else
			{
				refreshCollList(wert, feld, modus);
			}
		}
		
	</script>	
</HEAD>

<BODY onLoad='coll_name.focus();'>

<CENTER>

<DIV Class="klein">

<?php

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/ajax_functions.php';

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');

//log-file schreiben:
$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
fwrite($fh,date('d.m.Y H:i:s').": Kollektions-Bearbeitung wurde von ".$username." aufgerufen. (Zugriff von ".$_SERVER['REMOTE_ADDR'].")\n");
fclose($fh);

echo "<div class='page' id='page'>

	<div class='head' id='head'>
		pic2base :: Kollektion bearbeiten
		</div>

	<div class='navi' id='navi'>
		<div class='menucontainer'>";
			createNavi3_1($uid);
		echo "</div>
	</div>

	<div class='content' id='content'>
		<fieldset style='background-color:none; margin-top:10px;'>
		<legend style='color:blue; font-weight:bold;'>Auflistung aller vorhandenen Kollektionen</legend>
		<div id='scrollbox2' style='overflow-y:scroll;'>";
		
			if(hasPermission($uid, 'editallcolls', $sr))
			{
				$result1 = mysql_query("SELECT * FROM $table24 WHERE (locked = '0' OR coll_owner = '$uid') ORDER BY coll_id");
				//$result1 = mysql_query("SELECT * FROM $table24 ORDER BY coll_id");
			}
			elseif(hasPermission($uid, 'editmycolls', $sr))
			{
				$result1 = mysql_query("SELECT * FROM $table24 WHERE coll_owner = '$uid' ORDER BY coll_id");
			}
			else
			{
				echo "<p style='margin-top:150px; text-align:center; font-size:14px; font-weight:bold; color:red; text-decoration:underline'>Sie haben leider keine Berechtigung, Kollektionen zu bearbeiten.</p>
				<p style='text-align:center; margin-top:30px;'><input type='button' value=' Zur&uuml;ck' onclick='location.href=\"edit_start.php\"' />";
			}
			if(isset($result1))
			{		
				@$num1 = mysql_num_rows($result1);
				if($num1 > 0)
				{
					echo "
					<center>
					<table class='coll' border='0' style='margin-top:25px;'>
		
						<TR class='coll'>
							<TD colspan = '5'><b>Suche nach</b></TD>
						</TR>
					
						<TR class='coll'>
							<TD style='background-color:darkred;' colspan = '5'></TD>
						</TR>
					
						<tr>
							<td style='width:25%'>Name</td>
							<td style='width:63%'>Beschreibung</td>
							<td style='width:12%' align='center'>Aktion</td>
						</tr>
						
						<TR class='coll'>
							<TD style='background-color:darkred;' colspan = '5'></TD>
						</TR>
					
						<tr>
							<td style='text-align:left;'><input type='text' name='coll_name' id='coll_name' style='width:175px;' onkeyup='searchCollection(this.value, \"coll_name\", \"edit\")'></td>
							<td style='text-align:left;'><input type='text' name='coll_description' id='coll_description' style='width:450px;' onkeyup='searchCollection(this.value, \"coll_description\", \"edit\")'></td>
							<td></td>
						</tr>
						
						<TR class='coll'>
							<TD style='background-color:darkred;' colspan = '5'></TD>
						</TR>
						
					</table>
					
					<div id='search_result'>
						<table class='coll' border='0'>
							
							<TR class='coll'>
									<TD colspan = '5'><b>Suchergebnis</b></TD>
								</TR>	
							
							<TR class='coll'>
								<TD style='background-color:darkred;' colspan = '5'></TD>
							</TR>
							
							<TR class='coll' style='height:10px;'>
								<TD colspan = '5'></TD>
							</TR>";
						
							for($i1=0; $i1<$num1; $i1++)
							{
								$coll_id = mysql_result($result1, $i1, 'coll_id');
								$coll_name = mysql_result($result1, $i1, 'coll_name');
								$coll_description = mysql_result($result1, $i1, 'coll_description');
								$locked = mysql_result($result1, $i1, 'locked');
								// beinhaltet die Kollektion bereits Bilder?
								$result2 = mysql_query("SELECT * FROM $table25 WHERE coll_id = '$coll_id'");
								$num2 = mysql_num_rows($result2);
								if($locked == 1)
								{
									$button1 = "<span style='cursor:pointer;'><img src='../../share/images/forbidden.gif' title='Diese Kollektion ist zur Bearbeitung durch andere Benutzer gesperrt'></span>";
								}
								else
								{
									$button1 = "<span style='cursor:pointer;'><img src='../../share/images/allowed.gif' title='Diese Kollektion ist zur Bearbeitung durch andere Benutzer freigegeben'></span>";
								}
								echo "
								<tr>
									<td style='width:25%'>".$coll_name."</td>
									<td style='width:61%'>".$coll_description."</td>
									<td style='width:14%' colspan='3'>
									<span style='cursor:pointer;'><img src='../../share/images/edit.gif' style='margin-left:10px; margin-right:5px;' title='Kollektion bearbeiten, neue Bilder hinzuf&uuml;gen, Bilder l&ouml;schen...' onClick='location.href=\"edit_selected_collection.php?coll_id=$coll_id\"'></span>";
									if($num2 > 0)
									{
										echo "<span style='cursor:pointer;'><img src='../../share/images/arrange.gif' style='margin-right:5px;' title='Zum Sortiertisch, Pr&auml;sentation anzeigen' onClick='location.href=\"arrange_collection.php?coll_id=$coll_id\"'></span>";
									}
									else
									{
										echo "<span style='cursor:pointer;'><img src='../../share/images/arrange_disabled.gif' style='margin-right:5px;' title='Kollektion beinhaltet noch keine Bilder'></span>";
									}
									echo "
									<span style='cursor:pointer;'><img src='../../share/images/trash.gif'  style='margin-right:5px;' title='Diese Kollektion entfernen' onClick='sicher(\"$coll_id\");'></span>
									".$button1."
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
							<TR class='coll' style='height:10px;'>
								<TD class='coll' colspan = '5'></TD>
							</TR>
							
							<TR class='coll'>
								<TD style='background-color:darkred;' colspan = '5'></TD>
							</TR>
							
							<tr>
								<td colspan='5' style='text-align:center;'><input type='button' style='margin-top:10px; margin-bottom:10px;' value='Neue Kollektion anlegen' title='Eine neue (leere) Kollektion anlegen' onClick='createNewCollection(\"$uid\")'></td>
							</tr>
						
						</table>
						
					</div>
					</center>";
				}
				else
				{
					echo "
					<center>
						<table class='coll' border='0'>
							
							<TR class='coll'>
									<TD colspan = '5'></TD>
								</TR>	
							
							<TR class='coll'>
								<TD style='background-color:darkred;' colspan = '5'></TD>
							</TR>
							
							<TR class='coll' style='height:10px;'>
								<TD colspan = '5'><b>Es ist noch keine Kollektion vorhanden</b></TD>
							</TR>
							
							<TR class='coll' style='height:10px;'>
								<TD class='coll' colspan = '5'></TD>
							</TR>
							
							<TR class='coll'>
								<TD style='background-color:darkred;' colspan = '5'></TD>
							</TR>
							
							<tr>
								<td colspan='5' style='text-align:center;'><input type='button' style='margin-top:10px; margin-bottom:10px;' value='Neue Kollektion anlegen' title='Eine neue (leere) Kollektion anlegen' onClick='createNewCollection(\"$uid\")'></td>
							</tr>
						
						</table>
					</center>";
				}
			}
		echo "
		</div>
		</fieldset>
	</div>
	
	<div class='foot' id='foot'>
	<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
	</div>
	
</div>
</DIV>
</CENTER>
</BODY>
</HTML>";
?>