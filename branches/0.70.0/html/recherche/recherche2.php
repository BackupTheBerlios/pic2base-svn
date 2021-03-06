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

unset($parameter);
IF(array_key_exists('parameter', $_COOKIE))
{
	$parameter = $_COOKIE['parameter'];
	$param = preg_split('#,#', $parameter);
}
ELSE
{
	$param = '';
}
IF(count($param) == '3')
{
	$lat = $param[0];
	$long = $param[1];
	$ort = $param[2];
}
ELSE
{
	$lat = 51.791232;
	$long = 10.952811;
	$ort = 'Blankenburg';
}
//echo "Parameter: ".$parameter.", Breite: ".$lat.", Laenge: ".$long.", Ort: ".$ort."<BR>";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Datensatz-Recherche</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 1.0.2  (Linux)">
	<META http-equiv="Content-Style-Type" content="text/css">
	<META HTTP-EQUIV="pragma" CONTENT="no-cache">
	<META http-equiv="cache-control" content="no-cache">
	<link rel=stylesheet type="text/css" href='../../css/format1.css'>
	<link rel=stylesheet type="text/css" href='../../css/tooltips.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
	<script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
</HEAD>

<!--
/***********************************************************************************
 * Project: pic2base
 * File: recherche2.php
 *
 * Copyright (c) 2006 - 2011 Klaus Henneberg
 *
 * Project owner:
 * Dipl.-Ing. Klaus Henneberg
 * 38889 Blankenburg, BRD
 *
 * This file is licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 ***********************************************************************************/
 -->

<script language="javascript" type="text/javascript" src="../../share/calendar.js"></script>
<script language="javascript" type="text/javascript" src="../../share/functions/ShowPicture.js"></script>
<script language="javascript" type="text/javascript" src="../../share/functions/jquery-1.8.2.min.js"></script>

<script language="JavaScript">
jQuery.noConflict();
<!--

function showAllDetails(mod, pic_id)
{
	//alert("Bild-ID: "+pic_id);
	Fenster1 = window.open('../../share/details.php?view=kompact&pic_id='+pic_id, 'Details', "width=550,height=768,scrollbars,resizable=no,");
	Fenster1.focus();
}

function reloadPreviews(pic_id, uid)
{
	Fenster1 = window.open('select_params.php?pic_id='+pic_id, 'Parameter', "width=780,height=580,scrollbars,resizable=no,");
	Fenster1.focus();
}

function changeOwner(pic_id)
{
	Fenster1 = window.open('change_owner.php?pic_id='+pic_id, 'Parameter', "width=780,height=570,scrollbars,resizable=no,");
	Fenster1.focus();
}

function showMap(lat,long)
{
	Fenster1 = window.open('show_map.php?lat='+lat+'&long='+long+'&width='+544+'&height='+424, 'Karte', "width=550,height=430,resizable=no,");
	Fenster1.focus();
}

function changeGeoParam(pic_id)
{
	var lat = <?php echo $lat; ?>;
	var long = <?php echo $long; ?>;
	var ort = "<?php echo $ort; ?>";
	var Fenster1 = window.open('../../share/change_geo_param.php?pic_id='+pic_id+'&lat='+lat+'&long='+long+'&ort='+ort, 'Karte', "width=550,height=430,resizable=no,");
	Fenster1.focus();
}

function saveNewParam(newlocation, ort, loc_id, pic_id)
{
	Fenster1 = window.open('save_new_param.php?location='+newlocation+'&ort='+ort+'&loc_id='+loc_id+'&pic_id='+pic_id, 'Speicherung', "width=10,height=10,scrollbars,resizable=no,");
}

function showDelWarning(pic_id)
{
	var check = confirm("Wollen Sie das Bild wirklich entfernen?");
	if(check == true)
	{
		window.open('../../share/delete_picture.php?pic_id=' + pic_id, 'Delete', 'width=600px, height=450px');
	}
}

function rotatePictureSet(orientation, pic_id)
{
	Fenster1 = window.open('../../share/rotate_picture_set.php?orientation=' + orientation + '&pic_id='+pic_id, 'Speicherung', "width=500px,height=200px,scrollbars,resizable=no,");
}

//alert("Bildhoehe: "+screen.height);
function CloseWindow()
{
   //anotherWindow = window.open("", "bildfenster", "");
   // Wird bereits ein Bild in der "Grossansicht" angezeigt? - dann wird es geschlossen:
   if (anotherWindow != null)
   {
   	anotherWindow.close();
   }
}

function showKatInfo(kat_id)
{
	Fenster1 = window.open('../../share/edit_kat_info.php?kat_id='+kat_id, 'Kategorie-Informationen', "width=675,height=768,scrollbars,resizable=no,");
	Fenster1.focus();
}

function showDiary(aufn_dat)
{
	Fenster1 = window.open('../../share/edit_diary.php?aufn_dat='+aufn_dat, 'Tagebuch-Eintrag', "width=675,height=768,scrollbars,resizable=no,");
	Fenster1.focus();
}

-->
</script>

<BODY LANG="de-DE" scroll = "auto" onload="javascript:CloseWindow()">

<CENTER>

<DIV Class="klein">

<?php 

IF (array_key_exists('bewertung', $_COOKIE))
{
	list($bewertung) = preg_split('#,#',$_COOKIE['bewertung']);
}
ELSE
{
	$bewertung = '';
}
//echo "Kontrolle: Bewertung: ".$bewertung."<BR>";
IF($bewertung == '')
{
	$bewertung = '6';
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');

if ( array_key_exists('pic_id',$_GET) )
{
	$pic_id = $_GET['pic_id'];
}
if ( array_key_exists('kat_id',$_GET) )
{
	$kat_id = $_GET['kat_id'];
}
if ( array_key_exists('mod',$_GET) )
{
	$mod = $_GET['mod'];
}
if ( array_key_exists('s_m',$_GET) )
{
	$s_m = $_GET['s_m'];
}

$stat = createStatement($bewertung);
//echo $stat;
//Ermittlung der Usersprache:
$result1 = mysql_query("SELECT language FROM $table1 WHERE id = '$uid'");
$language = mysql_result($result1, isset($i1), 'language');

$base_file = 'recherche2';

echo "
<div class='page'>
	<p id='kopf'>pic2base :: Datensatz-Recherche <span class='klein'>(User: $username; eingestellte Bewertung: ".showBewertung($bewertung).")</span></p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
			createNavi2_1($uid);
		echo "</div>
	</div>";
//################################################################################################################
SWITCH ($mod)
	{
		CASE 'zeit':
		echo "
		<div id='spalte1F'>";
		$ziel = "../../html/recherche/recherche2.php";
		$base_file = 'recherche2';
		$mod='zeit';
		$modus='recherche';
		include '../../share/time_treeview.php';
		echo "
		</div>";
		break;
	//#####################################################################################################################	
		CASE 'kat':
		echo "
		<div id='spalte1F'>
		<p id='elf' style='background-color:white; padding: 5px; margin-top: 4px; margin-left: 0px; text-align:center;'>Bildsuche nach Kategorien<BR>";
		$ziel = "../../html/recherche/recherche2.php";
		$base_file = 'recherche2';
		$mod='kat';
		$modus='recherche';
		include '../../share/kat_treeview.php';
		echo "
		</div>";
		break;
	//#####################################################################################################################		
		CASE 'desc':
		include $sr.'/bin/share/functions/ajax_functions.php';
		echo "
		<div id='spalte1F'>
		<p id='elf' style='background-color:white; padding: 5px; margin-top: 4px; margin-left: 0px; text-align:center;'>Bildsuche nach Beschreibungs-Text<BR>";
		$ziel = "../../html/recherche/recherche2.php";
		$base_file = 'recherche2';
		$modus='recherche';
		$mod='desc';
		
		echo "<FORM name=\"descr1\" method=\"POST\">
		<TABLE id='desc'>
		<TR id='desc'>
			<TD id='desc' colspan='2' style='background-color:#ff9900;'>Die Bildbeschreibung enth&auml;lt folgende Textfragmente:</TD>
		</TR>
		
		<TR id='desc'>
			<TD id='desc' colspan='2' style='border-style:none;'>&nbsp;</TD>
		</TR>
		
		<TR id='desc'>
			<TD id='desc1'><INPUT type=\"text\" name=\"desc1\" class='Feld250'></TD>
			<TD id='desc2'>
			<SELECT name=\"bed1\">
                    		<option value='0'></option>
                   	 	<option value = '1'>und</option>
                    		<option value = '2'>oder</option>
                  	</SELECT>
                  	</TD>
		</TR>
		
		<TR id='desc'>
			<TD id='desc1'><INPUT type=\"text\" name=\"desc2\" class='Feld250'></TD>
			<TD id='desc2'>
			<SELECT name=\"bed2\">
                    		<option value='0'></option>
                   	 	<option value = '1'>und</option>
                    		<option value = '2'>oder</option>
                  	</SELECT>
                  	</TD>
		</TR>
		
		<TR id='desc'>
			<TD id='desc1'><INPUT type=\"text\" name=\"desc3\" class='Feld250'></TD>
			<TD id='desc2'>
			<SELECT name=\"bed3\">
                    		<option value='0'></option>
                   	 	<option value = '1'>und</option>
                    		<option value = '2'>oder</option>
                  	</SELECT>
                  	</TD>
		</TR>
		
		<TR id='desc'>
			<TD id='desc1'><INPUT type=\"text\" name=\"desc4\" class='Feld250'></TD>
			<TD id='desc2'>
			<SELECT name=\"bed4\">
                    		<option value='0'></option>
                   	 	<option value = '1'>und</option>
                    		<option value = '2'>oder</option>
                  	</SELECT>
                  	</TD>
		</TR>
		
		<TR id='desc'>
			<TD id='desc1'><INPUT type=\"text\" name=\"desc5\" class='Feld250'></TD>
			<TD id='desc2'>
			&nbsp;
                  	</TD>
		</TR>
		
		<TR id='desc'>
			<TD id='desc' style='border-style:none; height:15px;'></TD>
		</TR>
		
		<TR id='desc'>
			<TD id='desc' colspan='2' style='text-align:center;'>
			<INPUT type=\"button\" value=\"Suchen\" class='button1' onClick='getDescPreview1(descr1.desc1.value, descr1.bed1.value, descr1.desc2.value, descr1.bed2.value, descr1.desc3.value,  descr1.bed3.value, descr1.desc4.value, descr1.bed4.value, descr1.desc5.value, \"$mod\", \"$modus\", \"$base_file\", \"$bewertung\",0,0)'>
			</TD>
		</TR>
		 </FORM>
		 </TABLE>";
		
		echo "
		</div>";
		break;
	//#####################################################################################################################
		CASE 'geo':
		include $sr.'/bin/share/functions/ajax_functions.php';
		echo "
		<div id='spalte1F'>
		<p id='elf' style='background-color:white; padding: 5px; margin-top: 4px; margin-left: 0px; text-align:center;'>Bildsuche nach geografischen Koordinaten<BR>";
		$ziel = "../../html/recherche/recherche2.php";
		$base_file = 'recherche2';
		$mod='geo';
		$modus='recherche';
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		echo "<FORM name=\"geo_rech1\" method=\"POST\">
		<TABLE id='geo'>
		<TR id='geo'>
			<TD id='geo' colspan='2' style='background-color:#ff9900;'>Suche nach geogr. Koordinaten und Umkreis</TD>
		</TR>
		
		<TR id='geo'>
			<TD id='geo' colspan='2' style='border-style:none;'>&nbsp;</TD>
		</TR>
		
		<TR id='geo'>
			<TD id='geo1'>geogr. L&auml;nge (in Dez.-Grad)</TD>
			<TD id='geo2'><INPUT type=\"text\" name=\"long\" maxlength=\"12\" value = '10,982466667' class='Feld175'></TD>
		</TR>
		
		<TR id='geo'>
			<TD id='geo1'>geogr. Breite (in Dez.-Grad)</TD>
			<TD id='geo2'><INPUT type=\"text\" name=\"lat\" maxlength=\"12\" value = '51,79835' class='Feld175'></TD>
		</TR>
		
		<TR id='geo'>
			<TD id='geo1'>Ort liegt h&ouml;her als (m .NN)</TD>";
			IF( !(isset($alt)) OR $alt == '')
			{
				$alt = '0';
			}
			echo "
			<TD id='geo2'><INPUT type=\"text\" name=\"alt\" maxlength=\"4\" value = '$alt' class='Feld175'></TD>
		</TR>
		
		<TR id='geo'>
			<TD id='geo1'>Umkreis</TD>
			<TD id='geo2'><INPUT type=\"text\" name=\"radius1\" maxlength=\"6\" size=\"4\" value = '1' style='height:16px; vertical-align:bottom;'>
			<SELECT name=\"einheit1\" class='Auswahl' style='height:20px';>
			<option value = '1'>m</option>
			<option value = '1000' selected>km</option>
			</SELECT>
                        </TD>
		</TR>
		
		<TR id='geo'>
			<TD id='geo' style='border-style:none;'>&nbsp;</TD>
		</TR>
		
		<TR id='geo'>
			<TD id='geo' colspan='2' style='text-align:center;'>
			<INPUT type=\"button\" value=\"Nach Geo-Koordinaten suchen\" class='button4' onClick='getGeoPreview1(geo_rech1.long.value, geo_rech1.lat.value, geo_rech1.alt.value, geo_rech1.radius1.value, geo_rech1.einheit1.value,  \"$mod\", \"$modus\", \"$base_file\", \"geo_rech1\", \"$bewertung\",0,0)'>
			</TD>
		</TR>
		</TABLE>
		 </FORM>";
		 
		 //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		 
		 echo "
		 <TABLE id='geo'>
		 <TR id='geo'>
			<TD id='geo' colspan='2' style='border-style:none; height:30px;'>&nbsp;</TD>
		</TR>
		
		<FORM name=\"geo_rech2\" method=\"POST\">
		<TR id='geo'>
			<TD id='geo' colspan='2' style='background-color:#ff9900;'>Suche nach Ortsbezeichnung und Umkreis</TD>
		</TR>
		
		<TR id='geo'>
			<TD id='geo' colspan='2' style='border-style:none;'>&nbsp;</TD>
		</TR>
		
		<TR id='geo'>
			<TD id='geo1' style='width:60px;'>Ortsname</TD>
			<TD id='geo2'>
				<SELECT name=\"ort\" class='Auswahl270'>";
				$result9 = mysql_query( "SELECT DISTINCT City FROM $table2 WHERE City <>'Ortsbezeichnung' AND City <> '' ORDER BY City");
				echo mysql_error();
				$num9 = mysql_num_rows($result9);
				FOR ($i9=0; $i9<$num9; $i9++)
				{
					$city = mysql_result($result9, $i9, 'City');
					$result11 = mysql_query( "SELECT * FROM $table2 WHERE location='$city'");
					//$loc_id = mysql_result($result11, $i11, 'loc_id');
					echo "<option value=\"$city\">".$city."</option>";
				}
                echo "</SELECT>
			</TD>
		</TR>
		
		<TR id='geo'>
			<TD id='geo1'>Umkreis</TD>
			<TD id='geo2'><INPUT type=\"text\" name=\"radius2\" maxlength=\"4\" size=\"4\" value=\"1\"style='height:16px; vertical-align:bottom;'>
			<SELECT name=\"einheit2\" class='Auswahl' style='height:20px;'>
			<option value = '1'>m</option>
			<option value = '1000' selected>km</option>
            </SELECT>
            </TD>
		</TR>
				
		<TR id='geo'>
			<TD id='geo' colspan='2' style='border-style:none;'>&nbsp;</TD>
		</TR>
		
		<TR id='geo'>
			<TD id='geo' colspan='2' style='text-align:center;'>
			<INPUT type=\"button\" value=\"Nach Ortsbezeichnung suchen\" class='button4' onClick='getGeoPreview2(geo_rech2.ort.value, geo_rech2.radius2.value, geo_rech2.einheit2.value, \"$mod\", \"$modus\", \"$base_file\", \"geo_rech2\", \"$bewertung\",0,0)'></TD>
		</TR>
		 </FORM>
		</TABLE>";
	//~~~~~~~~~~~~~~~~~~~~~~~
		echo "
		</div>";
		break;
	//#####################################################################################################################
		CASE 'exif':
		//echo ini_get('memory_limit');
		ini_set('memory_limit', '250M');
		include $sr.'/bin/share/functions/ajax_functions.php';
		$base_file = 'recherche2';
		$mod='exif';
		$modus='recherche';
		echo "
		<div id='spalte1F'>
		<p id='elf' style='background-color:white; padding: 5px; margin-top: 4px; margin-left: 0px; text-align:center;'>Bildsuche nach Meta-Daten<BR>
		<FORM name= \"exif_param\" method='POST'>

		<TABLE id='geo' align='center' border = '0'>
				
			<TR class='normal' style='height:3px;'>
				<TD class='normal' bgcolor='#FF9900' colspan='2'>
				</TD>
			</TR>
			
			<TR>
				<TD width = 100% align = 'center' colspan='2'><BR></TD>
			</TR>
			
			<TR>
				<TD align='left' style='height:22px; font-size:12px;'>
				Meta-Daten-Feld:
				</TD>
				<TD align = 'right'>
				<SELECT name='zusatz1' class='Auswahl200' OnChange='getZusatzwert(document.exif_param.zusatz1.value, \"$bewertung\")'>";
				OptionFields($language);
				echo "</SELECT>
				</TD>
			</TR>
			
			<TR>
				<TD align='left' style='height:22px; font-size:12px;'>
				Bedingung:
				</TD>
				<TD align = 'right'>				
				<SELECT name='bedingung1' class='Auswahl200'>
					<OPTION VALUE='=' selected>ist gleich</OPTION>
					<OPTION VALUE='<>'>ist ungleich</OPTION>
					<OPTION VALUE='>'>ist gr&ouml;sser</OPTION>
					<OPTION VALUE='<'>ist kleiner</OPTION>
					<OPTION VALUE='LIKE'>enth&auml;lt</OPTION>
				</SELECT>
				</TD>
			</TR>
			
			<TR>
				<TD align='left' style='height:22px; font-size:12px;'>
				Kriterium:
				</TD>
				<TD align = 'right'>
				<div id='zw1'></div>
				</TD>
			</TR>
			
			<TR>
				<TD width = 100% align = 'center' colspan='3'><BR></TD>
			</TR>
			
			<TR>
				<TD width = 100% align = 'center' colspan='3'><INPUT type=\"button\" value=\"Suchen\" class='button1' onClick='getExifPreview(exif_param.zusatz1.value, exif_param.bedingung1.value, exif_param.zusatzwert1.value, \"$mod\", \"$modus\", \"$base_file\", \"$bewertung\",0,0)'>&#160;&#160;&#160;
				<INPUT TYPE='button' VALUE = 'Abbrechen' OnClick=\"location.href='recherche0.php'\">		
				</TD>
			</TR>
			
			<TR>
				<TD width = 100% align = 'center' colspan='2'><BR></TD>
			</TR>
			
			<TR>
				<TD width = 100% align = 'left' colspan='2'><p style='FONT-SIZE:8pt; color:blue;'>Beachten Sie bitte:<BR><BR>
				Nicht in jedem Fall ist es zweckm&auml;ssig, die Abfrage-Bedingung 'ist gr&ouml;sser' oder 'ist kleiner' zu w&auml;hlen.<BR>
				Liefert die Auswahl des Meta-Daten-Felds Zahlenwerte als Kriterium, kann man sehr wohl nach Vorkommen suchen, bei denen der Wert gr&ouml;sser oder kleiner als das ausgew&auml;hlte Kriterium ist.<BR>
				Liefert die Auswahl des Meta-Daten-Felds hingegen eine Zeichenkette, sollte die Bedingung 'ist gleich' oder 'enth&auml;lt' lauten, um zu nachvollziehbaren Treffern zu gelangen.</p></TD>
			</TR>
			
			<TR class='normal' style='height:3px;'>
				<TD class='normal' bgcolor='#FF9900' colspan='2'>
				</TD>
			</TR>
			
		
		</TABLE>
		
		</FORM>
		</div>";
		break;
	//#####################################################################################################################
		CASE 'expert_k':
		include $sr.'/bin/share/functions/ajax_functions.php';
		$base_file = 'recherche2';
		$mod='expert_k';
		$modus='recherche';
		$number = 0;
		
		echo "
		<div id='spalte1F'>
		<p id='elf' style='background-color:white; padding: 5px; margin-top: 4px; margin-left: 0px; text-align:center;'>Experten-Suche<BR>
		<FORM name= \"expert_param\" method='POST' action='experten_suche_kat1.php'>
		<TABLE id='desc' align='left' border = '0'>
				
			<TR class='normal' style='height:3px;'>
				<TD class='normal' bgcolor='#FF9900' colspan='4'>
				</TD>
			</TR>			
			
			<TR id='desc'>
				<TD class='normal' style='width:310px; text-align:left;' colspan = '4'>
				<p style='FONT-SIZE:8pt; color:blue;'>Beachten Sie die Bedeutung der Booleschen Operatoren:<BR><BR>
				<u>UND</u> bedeutet:<BR>
				Die gesuchten Bilder, geh&ouml;ren <b>sowohl</b> zur Kategorie A <b>und gleichzeitig</b> zur Kategorie B.<BR>
				<u>ODER</u> bedeutet:<BR>
				Die gesuchten Bilder k&ouml;nnen zur Kategorie A <b>oder</b> zur Kategorie B geh&ouml;ren.</p><BR>
				Gesucht werden Bilder, f&uuml;r die gilt:<BR>Es sind Bilder der Kategorie
				</TD>
			</TR>
			
			<TR class='normal'>
			<TD colspan='4' class='normal'><BR></TD>
			</TR>
			
			
			<TR>
				<TD class='normal' style='width:360px; text-align:left'; colspan='4'>
				<div id='parameter'>
				
				<input type='hidden' name='bewertung' value='$bewertung'>
				
				<input type='hidden' name='number1' value='$number1'>
				<input type='hidden' name='kat1' value='$kat1'>
				<input type='hidden' name='op1' value='$op1'>
				
				<input type='hidden' name='number2' value='$number2'>
				<input type='hidden' name='kat2' value='$kat2'>
				<input type='hidden' name='op2' value='$op2'>
				
				<input type='hidden' name='number3' value='$number3'>
				<input type='hidden' name='kat3' value='$kat3'>
				<input type='hidden' name='op3' value='$op3'>
				
				<input type='hidden' name='number4' value='$number4'>
				<input type='hidden' name='kat4' value='$kat4'>
				<input type='hidden' name='op4' value='$op4'>
				
				<input type='hidden' name='number' value='$number'>
				<SELECT name='kat' style='width:270px; margin-right:10px';>";
				$res1 = mysql_query( "SELECT * FROM $table4 ORDER BY kategorie");
				//echo mysql_error();
				IF(mysql_error() == '')
				{
					$num1 = mysql_num_rows($res1);
					FOR($i1=0; $i1<$num1; $i1++)
					{
						$kat_id = mysql_result($res1, $i1, 'kat_id');
						$kategorie = mysql_result($res1, $i1, 'kategorie');
						echo "<OPTION VALUE='$kat_id'>".$kategorie."</OPTION>";
					}
				}
				echo "
				</SELECT>
			
				<SELECT name='op' style='width:60px'; 
				onChange='createNextKrit(document.expert_param.number.value, document.expert_param.kat.value, document.expert_param.op.value,
				document.expert_param.number1.value, document.expert_param.kat1.value, document.expert_param.op1.value,
				document.expert_param.number2.value, document.expert_param.kat2.value, document.expert_param.op2.value,
				document.expert_param.number3.value, document.expert_param.kat3.value, document.expert_param.op3.value,
				document.expert_param.number4.value, document.expert_param.kat4.value, document.expert_param.op4.value,
				document.expert_param.bewertung.value)'>
				<OPTION value='' selected></OPTION>
				<OPTION value='AND'>UND</OPTION>
				<OPTION value='OR'>ODER</OPTION>
				</SELECT>
				</div>
				</TD>
			</TR>
			
			<TR>
			<TD colspan='4'><BR></TD>
			</TR>
			
			<TR>
			<TD colspan='4'>
			<INPUT TYPE='button' value='Formular leeren' OnClick='location.reload()'style='margin-right:10px;'>
			<INPUT TYPE='button' value='Suche abbrechen' OnClick='location.href=\"recherche0.php\"'style='margin-right:10px;'>
			<!--<INPUT TYPE='submit' value='Suchen'>-->
			<INPUT TYPE='button' value='Suchen' onClick='getExpSearchPreview(document.expert_param.kat.value, document.expert_param.op.value, document.expert_param.kat1.value, document.expert_param.op1.value, document.expert_param.kat2.value, document.expert_param.op2.value, document.expert_param.kat3.value, document.expert_param.op3.value, document.expert_param.kat4.value, document.expert_param.op4.value, \"$mod\", \"$modus\", \"$base_file\", \"$bewertung\",0,0)'>
			</TD>
			</TR>
			
			<TR class='normal' style='height:3px;'>
				<TD class='normal' bgcolor='#FF9900' colspan='4'>
				</TD>
			</TR>
		</TABLE>
		
		</FORM>
		
		</div>";
		break;
	}
//###############################################################################################################
	echo "
	<div id='spalte2F'>
		<p id='elf' style='background-color:white; padding: 5px; width: 365px; margin-top: 4px; margin-left: 10px;'>
		<b>Hinweis zur Anzeige der Bilder:</b>
		<BR><BR>Bei der Suche von Bildern nach dem Aufnahmedatum oder einer Kategorie gelangen Sie zum Suchergebnis, 
		indem Sie auf das Datum (Jahr, Monat oder Tag) oder den Kategorienamen klicken.<BR>
		Bei den anderen Suchm&ouml;glichkeiten f&uuml;llen Sie zuerst das entsprechende Formular aus.<BR>
		Wenn Sie ein Bild in der Filmstreifen-Ansicht mit der Maus &uuml;berfahren, erhalten Sie in der rechten oberen 
		Spalte einige Details zu diesem Bild angezeigt.<BR>Klicken Sie auf ein Bild in dem Filmstreifen, gelangen 
		Sie in den \"Bl&auml;tter\"-Modus.<BR>
		In diesem Modus haben Sie die M&ouml;glichkeit, sehr schnell alle gefundenen Bilder zu betrachten, sich das entsprechenden Bild in Originalqualit&auml;t
		anzusehen, oder - die entsprechende Berechtigung vorausgesetzt - das gesuchte Bild herunterzuladen.<BR> 
		Wenn Sie den \"Bl&auml;tter\"-Modus verlassen, gelangen Sie innerhalb der Filmstreifen-Ansicht an die Stelle,
		an der sich das zuletzt betrachtete Bild befindet.<BR>Dieses wird dann auch in der Detailansicht dargestellt.
		</p>
		
		<p id='elf' style='background-color:white; padding: 5px; width: 365px; margin-top: 4px; margin-left: 10px;'>
		<b>Hilfe zu den Suchm&ouml;glichkeiten:</b><BR><BR>
		Ausf&uuml;hrliche Hilfe zu den Suchm&ouml;glichkeiten finden Sie &uuml;ber den Button \"Hilfe\" in der 
		Navigationsleiste oder direkt <a href='../help/help1.php?page=2'>hier</a>.
	  	</p>
	  </div>";
//###############################################################################################################	
	echo "
	<div id='filmstreifen'>";
	
	SWITCH($mod)
	{
		CASE 'zeit':
		$modus='recherche';	//bedeutet, dass keine Checkboxen angezeigt werden und der Hinweistext entsprechend angepasst wird
		$mod='zeit';
		break;
		
		CASE 'kat':
		$modus='recherche';	//bedeutet, dass keine Checkboxen angezeigt werden und der Hinweistext entsprechend	angepasst wird
		$base_file = 'recherche2';
		$mod='kat';
		break;
		
		CASE 'desc':
		$modus='recherche';	//bedeutet, dass keine Checkboxen angezeigt werden und der Hinweistext entsprechend	angepasst wird
		$mod='desc';		
		break;
		
		CASE 'geo':
		$modus='recherche';	//bedeutet, dass keine Checkboxen angezeigt werden und der Hinweistext entsprechend	angepasst wird
		$mod='geo';		
		break;
	}
	
	echo "
	</div>";
//###############################################################################################################	
	echo "
	<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>
	
</div>

<div id='blend' style='display:none; z-index:99;'>
<IMG src='../../share/images/grey.png' style='z-index:100; position:absolute; top:0px; left:0px; width:100%; height:100%;' />
<img src=\"../../share/images/loading.gif\" style='position:absolute; top:30%; left:50%; width:20px; z-index:101;' />
</div>";

mysql_close($conn);

?>
</DIV>
</CENTER>
</BODY>
</HTML>