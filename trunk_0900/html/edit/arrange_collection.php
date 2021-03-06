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
<html>

<head>
  <title>Kollektion umsortieren</title>
  <meta name="GENERATOR" content="eclipse">
  <meta name="AUTHOR" content="k. henneberg">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel=stylesheet type='text/css' href='../../css/format2.css'>
  <link rel="shortcut icon" href="../../share/images/favicon.ico">
  
  <script language="JavaScript" src="../../share/functions/resize_elements.js"></script>
  <script language="JavaScript" src="../../share/functions/jquery-1.8.2.min.js"></script>
  <script language="JavaScript" src="../../share/functions/jquery-ui.min.js"></script>
  <script language="JavaScript">
  	jQuery.noConflict();
	jQuery(document).ready(checkWindowSize);
	jQuery(window).resize(checkWindowSize); 

	jQuery(function() {
	jQuery( "#sortable" ).sortable({ 
		cursor: "move", 
		opacity: 0.7, 
		revert: true,
		stop: function (evt, ui)
				{
					//alert("neue Pos.: " + jQuery("#sortable li").index(ui.item) + ", alte Pos.: " + ui.item.attr('id'));
				}
		});
	jQuery( "#sortable" ).disableSelection();
	});
  </script>
  
  <style>	
	#sortable { list-style-type: none; margin: 0; padding: 0; }
	#sortable li { margin: 1px; padding: 1px; float: left; width: 200px; height: 200px; }
  </style>
  
</head>

<body>

<?php

/*
 * Project: pic2base
 * File: arrange_collection.php
 * Ansicht der ausgewaehlten Kollektion (Uebersicht aller Bilder)
 * Copyright (c) 2013 Klaus Henneberg
 *
 * Project owner:
 * Klaus Henneberg
 * Finkenweg 18
 * 38889 Blankenburg, BRD
 *
 * All files of this project are licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 */

if(array_key_exists('coll_id', $_GET))
{
	$coll_id = $_GET['coll_id'];
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/css/initial_layout_settings.php';

$max_size = 200;	//max. Ausdehnung des Vorschaubildbereichs in px

$result1 = mysql_query("SELECT $table25.pic_id, $table25.coll_id, $table24.coll_id, $table24.coll_name, $table2.pic_id, $table2.FileNameHQ 
FROM $table2, $table25, $table24 
WHERE $table25.coll_id = '$coll_id'
AND $table24.coll_id = $table25.coll_id
AND $table25.pic_id = $table2.pic_id
ORDER BY position");
$num1 = mysql_num_rows($result1);

$coll_name = mysql_result($result1, isset($i1), 'coll_name');
if(strlen($coll_name) > 65)
{
	$coll_name = substr($coll_name, 0, 65)."...";
}

echo "
<DIV Class='klein'>
	<div id='page'>
	
		<div id='head'>
			pic2base :: Ansicht der Kollektion \"".$coll_name."\"
		</div>
		
		<div id='navi'>
			<div class='menucontainer'>";
			createNavi3_2($uid);
			echo "
			</div>
		</div>
		
		<div id='content' style='background-color:darkgrey;'>
		
			<fieldset style='background-color:none; margin-top:10px;'>
			<legend style='color:blue; font-weight:bold;'>Ansicht der ausgew&auml;hlten Kollektion</legend>
			<div id='scrollbox3' style='overflow-y:scroll;'>
				<ul id='sortable'>";

					for($i1=0; $i1<$num1; $i1++)
					{
						$pic_id = mysql_result($result1, $i1, 'pic_id');
						$FileNameHQ = mysql_result($result1, $i1, 'FileNameHQ');
						$pic_size = getimagesize($sr."/images/vorschau/hq-preview/$FileNameHQ");
						$pic_breite = $pic_size[0];
						$pic_hoehe = $pic_size[1];

						if($pic_breite > $pic_hoehe)
						{
							$oberer_abstand = (($max_size - (176 / $pic_breite) * $pic_hoehe) / 2);	
							$rechter_abstand = (($max_size - 176) / 2);								
							echo "
							<li id=".$pic_id.">
								<img src='../../../images/vorschau/hq-preview/$FileNameHQ' style='width:176px; border-style:solid; border-bottom-width:".$oberer_abstand."px; border-top-width:".$oberer_abstand."px; border-right-width:".$rechter_abstand."px; border-left-width:".$rechter_abstand."px; border-radius:10px; border-color:#333333; margin:2px;' />
							</li>";
						}
						else
						{
							$oberer_abstand = (($max_size - 176) / 2);
							$rechter_abstand = (($max_size - (176 / $pic_hoehe) * $pic_breite) / 2);
							echo "
							<li id=".$pic_id.">
								<img src='../../../images/vorschau/hq-preview/$FileNameHQ' style='height:176px; border-style:solid; border-bottom-width:".$oberer_abstand."px; border-top-width:".$oberer_abstand."px; border-right-width:".$rechter_abstand."px; border-left-width:".$rechter_abstand."px; border-radius:10px; border-color:#333333; margin:2px;' />
							</li>";
						}
					}
	
				echo "
				</ul>
			</div>
			</fieldset>
			
			<fieldset style='background-color:none; margin-top:1px;'>
			<legend style='color:blue; font-weight:bold;'>Aktion</legend>
				<div id='scrollbox4' style='overflow-y:scroll;'><center>
					<input type='button' name='save' value='Neue Sortierung speichern' style='width:180px; margin-right:10px;' title='Speichert die neue Sortier-Reihenfolge dieser Kollektion' onclick='saveChanges($num1, $coll_id, \"save\")'>
					<input type='button' name='save_as' value='Sortierung speichern unter' style='width:180px; margin-right:10px;' title='Die umsortierte Kollektion wird unter einem neuen Namen gespeichert' onclick='saveChanges($num1, $coll_id, \"save_as\")'>
					<input type='button' name='slideshow' value='Dia-Show (Vorschau)' style='width:180px; margin-right:10px;' title='Dia-Show starten' onclick='location.href=\"show_presentation.php?coll_id=$coll_id&qual=lq\"'>
					<input type='button' name='cancel' value='Abbrechen und zur&uuml;ck' style='width:180px;' title='Die Sortierung wird verworfen' onclick='location.href=\"edit_collection.php\"'>
					</center>
				</div>
			</fieldset>
			
		</div>
		
		<div id='foot'>
			<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
		</div>
	
	</div>
</DIV>";
?>
<script language="Javascript">
function saveChanges(num1, coll_id, save_mode)
{
	
	//alert("save changes..." + num1 + " Bilder, Koll-ID: " + coll_id);
	var jsonPositions = Array();
	//alert( document.getElementById( "sortable" ) );
	var uListElement = document.getElementById( "sortable" );
	for( var i = 0; i < uListElement.children.length; ++i )
	{
		var objLi = uListElement.children[ i ];
		jsonPositions.push( {"idx":( i + 1 ),"picId":objLi.id} );
	}
	
	if(save_mode == "save")
	{
		//alert( JSON.stringify( jsonPositions ) );
		jQuery.ajax({
			  type: "POST",
			  url: "save_new_order.php",
			  data: "coll_id=" + coll_id + "&picIdx=" + JSON.stringify( jsonPositions ) + "&save_mode=" + save_mode,
			  success: function(response)
					  {
							//alert( "Neue Sortierung wurde gespeichert." + response );
							alert( "Neue Sortierung wurde gespeichert." );
					  }
			});
	}
	else if(save_mode == "save_as")
	{
		//location.href="save_new_order.php?coll_id=" + coll_id + "&picIdx=" + JSON.stringify( jsonPositions ) + "&save_mode=" + save_mode;
		
		//alert("Coll-ID: " + coll_id + ", Save-Mode: " + save_mode + ", Bild-Positionen: " + JSON.stringify( jsonPositions ));

		function postToUrl(coll_id, bild_positionen) 
		{
			 var form = document.createElement("form");
			 	 form.setAttribute("method", "POST");
			 	 form.setAttribute("action", "save_new_order.php");
				 
			 var hiddenField1 = document.createElement("input");
				 hiddenField1.setAttribute("type", "hidden");
				 hiddenField1.setAttribute("name", "coll_id");
				 hiddenField1.setAttribute("value", coll_id);
				 form.appendChild(hiddenField1);

			 var hiddenField2 = document.createElement("input");
				 hiddenField2.setAttribute("type", "hidden");
				 hiddenField2.setAttribute("name", "save_mode");
				 hiddenField2.setAttribute("value", "save_as");
				 form.appendChild(hiddenField2);

			 var hiddenField3 = document.createElement("input");
				 hiddenField3.setAttribute("type", "hidden");
				 hiddenField3.setAttribute("name", "picIdx");
				 hiddenField3.setAttribute("value", bild_positionen);
				 form.appendChild(hiddenField3);
	
			 document.body.appendChild(form);
			 form.submit();
			 return false;
		}
		
		postToUrl(coll_id, JSON.stringify( jsonPositions ));
	}
}
</script>
</body>
</html>
