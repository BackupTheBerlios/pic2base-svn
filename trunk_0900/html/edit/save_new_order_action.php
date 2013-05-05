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
//var_dump($_POST);

if(array_key_exists('coll_name', $_POST))
{
	$coll_name = $_POST['coll_name'];
}

if(array_key_exists('coll_description', $_POST))
{
	$coll_description = $_POST['coll_description'];
}

if(array_key_exists('picIdx', $_POST))
{
	$picIdx = $_POST['picIdx'];
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

echo "
<DIV Class='klein'>
	<div id='page'>
	
		<div id='head'>
			pic2base :: Speichere neue Kollektion
		</div>
		
		<div id='navi'>
			<div class='menucontainer'>";
			//createNavi3_2($uid);
			echo "
			</div>
		</div>
		
		<div class='content' id='content'>
	
			<fieldset style='background-color:none; margin-top:10px;'>
				<legend style='color:blue; font-weight:bold;'>Speichern der umbenannten Kollektion l&auml;uft...</legend>
				<div id='scrollbox2' style='overflow-y:scroll;'>
					<center>";
			
//					echo "Koll-Name: ".$coll_name.", Koll-Desc: ".$coll_description."<br>";
//					echo "PIC-IDX: ".$picIdx."<BR>";
					
					if($coll_name == "" OR $coll_description == "")
					{
						echo "<center><p style='margin-top:50px;'>Sie m&uuml;ssen den Kollektions-Namen und die Beschreibung eintragen!</p></center>";
						?>
						<script type="text/javascript">
						alert("Sie müssen den Kollektions-Namen und die Beschreibung eintragen!");
						history.back();
						</script>
						<?php
					}
					else
					{
						$create_date = date('Y-m-d H:i:s', time());
						//echo $create_date;
						$result1 = mysql_query("INSERT INTO $table24 (coll_name, coll_description, coll_owner, created, locked) VALUES ('$coll_name', '$coll_description', '$uid', '$create_date', '1')");
						
						if(mysql_error() == "")
						{
							echo "<center><p style='margin-top:50px;'>Speicherung l&auml;uft...</p></center>";
							$new_coll_id = mysql_insert_id();
							//echo "neue Koll-ID: ".$new_coll_id."<br>";
							foreach( json_decode( $picIdx ) as $item )
							{
								//echo $item->idx." -> ".$item->picId."\n";
								$idx=$item->idx;
								$pic_id=$item->picId;
								//echo $idx." - ".$pic_id."<BR>";
								$result2 = mysql_query("INSERT INTO $table25 (coll_id, pic_id, duration, position, transition_id) VALUES ('$new_coll_id', '$pic_id', '5', '$idx', '1')");
								echo mysql_error();
							}
							echo "<meta http-equiv='refresh', content='1; URL=edit_collection.php'>";
						}
						else
						{
							echo "<center><p style='margin-top:50px;'>Es trat ein Fehler auf.</p><BR><BR>";
							echo mysql_error();
							echo "</center>";
						}
					}	
					
					echo "
					</center>
				</div>
			</fieldset>
	
		</div>
		
		<div id='foot'>
			<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
		</div>
	
	</div>
</DIV>";
	

$result3 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result3, isset($i3), 'username');

//log-file schreiben:
$fh = fopen($p2b_path.'pic2base/log/p2b.log','a');
fwrite($fh,date('d.m.Y H:i:s').": Kollektion ".$coll_id." wurde von ".$username." unter neuem Namen gespeichert. (Zugriff von ".$_SERVER['REMOTE_ADDR'].")\n");
fclose($fh);

?>
</BODY>
</HTML>