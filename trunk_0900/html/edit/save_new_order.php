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
	<TITLE>pic2base - &Auml;nderungen speichern</TITLE>
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
		
	</script>	
</HEAD>

<BODY>

<CENTER>

<DIV Class="klein">

<?php
//var_dump($_REQUEST);
if(array_key_exists('coll_id', $_REQUEST))
{
	$coll_id = $_REQUEST['coll_id'];
}
else
{
	$coll_id = 0;
}
//echo "Koll-ID: ".$coll_id."<br><br>";
if(array_key_exists('save_mode', $_REQUEST))
{
	$save_mode = $_REQUEST['save_mode'];
}
//echo "Save-Mode: ".$save_mode."<BR><br>";
if(array_key_exists('picIdx', $_REQUEST))
{
	$picIdx = $_REQUEST['picIdx'];
}
//echo "PIC-IDX: ".$picIdx."<BR><br>";

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

if($save_mode == 'save')
{
	foreach( json_decode( $picIdx ) as $item )
	{
		//echo $item->idx." -> ".$item->picId."\n";
		$idx=$item->idx;
		$pic_id=$item->picId;
		echo $idx." - ".$pic_id."<BR>";
		$result0 = mysql_query("UPDATE $table25 SET position = '$idx' WHERE pic_id = '$pic_id' AND coll_id = '$coll_id'");
		echo mysql_error();
	}
	
	$result1 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
	$username = mysql_result($result1, isset($i1), 'username');
	
	//log-file schreiben:
	$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
	fwrite($fh,date('d.m.Y H:i:s').": Sortierreihenfolge der Kollektion ".$coll_id." wurde von ".$username." ge".utf8_decode(ä)."ndert. (Zugriff von ".$_SERVER['REMOTE_ADDR'].")\n");
	fclose($fh);
}

elseif($save_mode == 'save_as')
{
	echo "
	
	<DIV Class='klein'>
	<div id='page'>
	
		<div id='head'>
			pic2base :: Speichere Kollektion
		</div>
		
		<div id='navi'>
			<div class='menucontainer'>";
			createNavi3_2($uid);
			echo "
			</div>
		</div>
		
		<div class='content' id='content'>
	
			<fieldset style='background-color:none; margin-top:10px;'>
				<legend style='color:blue; font-weight:bold;'>Speichern der Kollektion unter neuem Namen</legend>
				<div id='scrollbox2' style='overflow-y:scroll;'>
					<center>
					<form name='new_collection' method='post' action='save_new_order_action.php'>
						<table class='coll' border='0'>
							
							<TR class='coll' style='height:3px;'>
								<TD class='coll' style='background-color:darkred;' colspan = '5'></TD>
							</TR>
							
							<TR class='coll' style='height:10px;'>
								<TD class='coll' colspan = '5'></TD>
							</TR>
							
							<tr>
								<td colspan='2' class='coll'>Neuer Name der Kollektion:</td>
								<td colspan='3' style='text-align:center;' class='coll'><input type='text' name='coll_name' style='width:500px;'></td>
							</tr>
							
							<TR class='coll' style='height:10px;'>
								<TD class='coll' colspan = '5'></TD>
							</TR>
							
							<tr>
								<td colspan='2'  class='coll'>Beschreibung:</td>
								<td colspan='3' style='text-align:center;' class='coll'><textarea name='coll_description' rows='5' style='height:100px; width:500px;'></textarea></td>
							</tr>
							
							<TR class='coll' style='height:10px;'>
								<TD class='coll' colspan = '5'>
								<input type='hidden' name='picIdx' value='$picIdx'</TD>
							</TR>
							
							<tr>
								<td colspan='2'></td>
								<td colspan='3' style='text-align:center;' class='coll'><input type='submit' value='Speichern'><input type='button' value='Abbrechen' onClick='location.href=\"edit_collection.php\"'></td>
							</tr>
							
							<TR class='coll' style='height:10px;'>
								<TD class='coll' colspan = '5'></TD>
							</TR>
							
							<TR class='coll' style='height:3px;'>
								<TD class='coll' style='background-color:darkred;' colspan = '5'></TD>
							</TR>
						
						</table>
					</form>
					</center>
				</div>
			</fieldset>
	
	</div>
		
		<div id='foot'>
			<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
		</div>
	
	</div>
</DIV>";
	
	echo "<form name='coll-params' method='post'>
	Neuer Name der Kollektion: <input id='coll_name' type='text'><BR>
	Beschreibung der neuen Kollektion: <input id='coll_desc' type='text'><br>
	<input type='button' value='speichern' onclick='saveNewCollection(getElementById(coll_name).value, getElementById(coll_desc).value)'>
	<input type='button' value='Abbrechen'>
	</form>";
}

?>
</BODY>
</HTML>