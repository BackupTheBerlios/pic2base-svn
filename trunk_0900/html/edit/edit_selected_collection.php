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
	<TITLE>pic2base - Ausgew&auml;hlte Kollektion bearbeiten</TITLE>
	<META NAME="GENERATOR" CONTENT="Eclipse">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format2.css'>
	<link rel=stylesheet type="text/css" href='../../css/tooltips.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script language="JavaScript" src="../../share/functions/resize_elements.js"></script>
	<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
	<script language="JavaScript" src="../../share/functions/jquery-1.8.2.min.js"></script>
	<script language="JavaScript">
		jQuery.noConflict();
		jQuery(document).ready(checkWindowSize);
		jQuery(window).resize(checkWindowSize); 

		function sicher(coll_id, pic_id, uid)
		{
			check = confirm("Soll dieses Bild wirklich entfernt werden?");
			if(check == true)
			{
				//alert("Koll.: " + coll_id + ", Bild: " + pic_id + ",  UID: " + uid);
				removePicture2(coll_id, pic_id, uid);
			}
		}
		
	</script>	
</HEAD>

<BODY onLoad='document.collection.coll_name.focus();'>
<body>

<CENTER>

<DIV Class="klein">

<?php

if(array_key_exists('coll_id', $_GET))
{
	$coll_id = $_GET['coll_id'];
}
else
{
	$coll_id = 0;
}

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

	echo "
	<div class='page' id='page'>
	
		<div class='head' id='head'>
			pic2base :: Ausgew&auml;hlte Kollektion bearbeiten
			</div>
	
		<div class='navi' id='navi'>
			<div class='menucontainer'>";
				createNavi3_1($uid);
			echo "</div>
		</div>
	
		<div class='content' id='content'>
			<fieldset style='background-color:none; margin-top:10px;'>
			<legend style='color:blue; font-weight:bold;'>Ausgew&auml;hlte Kollektion</legend>
				<div id='scrollbox2' style='overflow-y:scroll;'>";	//var_dump($_REQUEST);
				
					if(hasPermission($uid, 'editallcolls', $sr))
					{
						$result1 = mysql_query("SELECT * FROM $table24 WHERE coll_id = '$coll_id'");
					}
					elseif(hasPermission($uid, 'editmycolls', $sr))
					{
						$result1 = mysql_query("SELECT * FROM $table24 WHERE coll_owner = '$uid' AND coll_id = '$coll_id'");
					}
					else
					{
						echo "<p style='margin-top:150px;'>Sie haben keine Berechtigung, Kollektionen zu bearbeiten.</p>";
					}
					
					$result2 = mysql_query("SELECT $table25.pic_id, $table25.coll_id, $table25.position, $table2.pic_id, $table2.FileNameV, $table2.FileNameHQ
					FROM $table2, $table25
					WHERE $table25.coll_id = '$coll_id'
					AND $table25.pic_id = $table2.pic_id
					ORDER BY $table25.position");
					echo mysql_error();
					
					$num1 = mysql_num_rows($result1);
					$num2 = mysql_num_rows($result2);	//echo $num2;
					
					if($num1 > 0)
					{
						$coll_id = mysql_result($result1, isset($i1), 'coll_id');
						$coll_name = mysql_result($result1, isset($i1), 'coll_name');
						$coll_description = mysql_result($result1, isset($i1), 'coll_description');
						
						echo "
						<form name='collection' method='post' action='save_collection_changes.php?coll_id=$coll_id'>
							<center>
								<table class='coll' border='0' style='margin-top:25px;'>
								
									<TR class='coll'>
										<TD style='background-color:darkred;' colspan = '2'></TD>
									</TR>
								
									<tr>
										<td style='width:30%'>Name</td>
										<td style='width:70%'>Beschreibung</td>
									</tr>
									
									<TR class='coll'>
										<TD style='background-color:darkred;' colspan = '2'></TD>
									</TR>
								
									<tr style='vertical-align:top;'>
										<td style='text-align:left;'><input type='text' name='coll_name' style='width:200px;' value='$coll_name'></td>
										<td style='text-align:left;'><textarea name='coll_description' style='width:500px; height:100px;' value=''>".$coll_description."</textarea></td>
									</tr>
									
									<TR class='coll'>
										<TD style='background-color:darkred;' colspan = '2'></TD>
									</TR>
									
									<tr>
										<td colspan='2' style='text-align:center;'>
											<div id='button_bar'>
												<input type='submit' style='margin-top:10px; margin-bottom:10px;' value='&Auml;nderungen speichern' title=''><input type='button' style='margin-left:10px;' value='Ohne &Auml;nderungen zur vorhergehenden Seite' onClick='location.href=\"edit_collection.php\"'>
											</div>
										</td>
									</tr>
									
								</table>
							</center>
						</form>";
						
						
						echo "
						<center>
						<table class='coll' border='0' style='margin-top:70px;'>
							
							<tr class='coll'>
								<td style='background-color:darkred;'></TD>
							</tr>
							
							<tr>
								<td>Derzeit enthaltene Bilder in der Kollektion \"".$coll_name."\"</td>
							</tr>
							
							<TR class='coll'>
								<TD style='background-color:darkred;'></TD>
							</TR>
							<tr>
								<td>
									<div id='coll' style='height:180px; overflow-x:scroll;'>";
										echo "
										<table border='0'>
											<tr>";
											for($i2=0; $i2<$num2; $i2++)
											{
												$pic_id = mysql_result($result2, $i2, 'pic_id');
												$FileNameV = mysql_result($result2, $i2, 'FileNameV');		//echo $FileNameV." / ";
												$FileNameHQ = mysql_result($result2, $i2, 'FileNameHQ');	//echo $FileNameHQ." / ";
												echo "
												<td>
													<div class='tooltip4' style='float:left;'>
														<p style='margin:0px; cursor:pointer;'>
														<img src='../../../images/vorschau/thumbs/$FileNameV' style='margin-right:5px; margin-bottom:5px; margin-top:5px; height:145px;' title='Hier klicken, um das Bild ".$pic_id." aus der Kollektion ".$coll_id." zu entfernen' onclick='sicher(\"$coll_id\", \"$pic_id\", \"$uid\");' />
															<span style='text-align:center; margin:0px;'>
																<img src='../../../images/vorschau/hq-preview/$FileNameHQ' style='height:300px; margin-right:0px; margin-bottom:0px; margin-top:0px;' />
															</span>
														</p>
													</div>
												</td>";
											}
											echo "
											</tr>
										</table>";
		
									echo "		
									</div>
								</td>
							</tr>
							
							<TR class='coll'>
									<TD style='background-color:darkred;'></TD>
								</TR>
								
								<tr>
									<td style='text-align:center;'><input type='button' style='margin-top:10px; margin-bottom:10px;' value='Weitere Bilder hinzuf&uuml;gen / Bilder entfernen' onClick='document.cookie = \"search_modus=collection; path=/\"; document.cookie = \"coll_id=$coll_id; path=/\"; location.href=\"../recherche/recherche0.php\"'></td>
								</tr>
							
						</table>
						</center>";
						
					}
					else
					{
						echo "Es wurden noch keine Kollektionen angelegt.";
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