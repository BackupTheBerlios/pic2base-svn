<?php
IF (!$_COOKIE['login'])
{
include '../../share/global_config.php';
//var_dump($sr);
  header('Location: ../../../index.php');
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>pic2base - vorgemerkte Bilder l&ouml;schen</title>
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="AUTHOR" content="k. henneberg">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel=stylesheet type="text/css" href="../../css/format1.css">
  <link rel=stylesheet type="text/css" href='../../css/tooltips.css'>
  <link rel="shortcut icon" href="../../share/images/favicon.ico">
  <script type="text/javascript" src="../../ajax/inc/prototype.js"></script>
</head>

<!--
/*
 * Project: pic2base
 * File: del_pics1.php
 *
 * Copyright (c) 2005 - 2012 Klaus Henneberg
 *
 * Project owner:
 * Klaus Henneberg
 * Finkenweg 18
 * 38889 Blankenburg, BRD
 *
 * All files of this project are licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 *
 * @license http://www.opensource.org/licenses/osl-2.1.php Open Software License
 */
 -->

<BODY LANG="de-DE">
<DIV Class="klein">
<?php
unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
}

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';
include $sr.'/bin/share/functions/ajax_functions.php';

if(array_key_exists('user_id',$_GET))
{
	$user_id = $_GET['user_id']; 
}

echo "
<div class='page'>

	<p id='kopf'>pic2base :: vorgemerkte Bilder l&ouml;schen <span class='klein'>(User: $c_username)</span></p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
		include '../../html/admin/adminnavigation.php';
		echo "</div>
	</div>
	
	<div class='content'>
		<center>
		<table class='liste1' border='0'>
		<tbody>";

			//Ermittlung der Datensaetze, die zur Loeschung vorgesehen sind (aktiv = 0):
			$result0 = mysql_query("SELECT * FROM $table2 
			WHERE aktiv = '0' 
			ORDER BY pic_id, owner DESC");
			echo mysql_error();
			$num0 = mysql_num_rows($result0);
			IF($num0 > 0)
			{
				IF($num0 == 1)
				{
					$text = "Derzeit liegt ein Bild zum L&ouml;schen vor.";
				}
				ELSE
				{
					$text = "Derzeit liegen ".$num0." Bilder zum L&ouml;schen vor.";
				}
			}
			ELSE
			{
				$text = "Derzeit liegt kein Bild zum L&ouml;schen vor.";
			}
			
			echo "
			<TR style='height:3px;'>
			<TD colspan='6' bgcolor='#FF9900'></TD>
			</TR>
			
			<TR>
			<TD align='left' colspan='6'>".$text;
			IF($num0 > 0)
			{
				echo "<BR>Wenn Sie die Eintr&auml;ge in einer Zeile mit dem Mauszeiger &uuml;berfahren, erhalten Sie zus&auml;tzliche Informationen zu dem Datensatz.</TD>
				</TR>
				
				<TR style='height:3px;'>
				<TD colspan='6' bgcolor='#FF9900'></TD>
				</TR>
				
				<TR>
				<TD align='center'>Bild-ID</TD>
				<TD align='center'><b><FONT COLOR='green'>behalten</FONT></b></TD>
				<TD align='center'><b><FONT COLOR='red'>l&ouml;schen</FONT></b></TD>
				<TD align='center'>Kategorie</TD>
				<TD align='center'>Beschreibung</TD>
				<TD align='center'>Eigent&uuml;mer</TD>
				</TR>
				
				<TR style='height:3px;'>
				<TD colspan='6' bgcolor='#FF9900'></TD>
				</TR>
				
				<TR style='height:3px;'>
				<TD colspan='6'></TD>
				</TR>
				
				<TR style='height:3px;'>
				<TD colspan='6'></TD>
				</TR>";
				
				FOR($i0=0; $i0<$num0; $i0++)
				{
					$pic_id = mysql_result($result0, $i0, 'pic_id');
					$caption_abstract = mysql_result($result0, $i0, 'Caption_Abstract');
					$keywords = mysql_result($result0, $i0, 'keywords');
					$owner = mysql_result($result0, $i0, 'Owner');
					$result1 = mysql_query("SELECT username, vorname, name FROM $table1 WHERE id = '$owner'");
					$username = mysql_result($result1, isset($i1), 'username');
					$vorname = mysql_result($result1, isset($i1), 'vorname');
					$name = mysql_result($result1, isset($i1), 'name');
					$FileNameHQ = mysql_result($result0, $i0, 'FileNameHQ');
					$FileNameV = mysql_result($result0, $i0, 'FileNameV');
					//$image = "../../../images/vorschau/hq-preview/".$FileNameHQ; 
					$image = "../../../images/vorschau/thumbs/".$FileNameV; 
					//echo $image;
					
					echo "
					<TR id = 'picture$pic_id'>
					<TD style='width=30px; text-align: center;'>
						<div id='tooltip2'>
							<a href='#' style='text-decoration:none';>".$pic_id."<span style='text-align:left;'>
							<img src='$image' width='300px' />
							</span>
							</a>
						</div>
					</TD>
					<TD style='width=30px; text-align: center;'><SPAN style='cursor:pointer;' onClick=\"manage_picture('$pic_id', 'save')\"><img src='../../share/images/ok.gif' title='Bild $pic_id in der Datenbank belassen' /></span></TD>
					<TD style='width=30px; text-align: center;'><SPAN style='cursor:pointer;' onClick=\"if(confirm('Wollen Sie das Bild wirklich aus der Datenbank entfernen?')) manage_picture('$pic_id', 'delete')\"><img src='../../share/images/delete.gif' title='Bild $pic_id aus der Datenbank l&ouml;schen' /></a></TD>
					<TD style='width=265px; text-align: center;'><a href=#  style='text-decoration:none'; title='".$keywords."'>".substr($keywords,0,35)."</TD>
					<TD style='width=265px; text-align: center;'><a href=#  style='text-decoration:none'; title='".$caption_abstract."'>".substr($caption_abstract,0,35)."</TD>
					<TD style='width=50px; text-align: center;'><a href=#  style='text-decoration:none'; title='".$vorname." ".$name."'>".$username."</a></TD>
					</TR>";
				}
				echo "
				
				<TR style='height:10px;'>
				<TD colspan='6' align='center' bgcolor=''></TD>
				</TR>
				
				
				<TR style='height:3px;'>
				<TD colspan='6' align='center' bgcolor='#FF9900'></TD>
				</TR>";
			}
			ELSE
			{
				echo "</TD</TR>";
			}
			echo "
			<TR style='height:10px;'>
			<TD colspan='6' align='center' bgcolor=''><INPUT TYPE='button' VALUE='Zur&uuml;ck' onClick=\"location.href='../../html/admin/adminframe.php'\"></TD>
			</TR>
			
			<TR style='height:3px;'>
			<TD colspan='6' align='center' bgcolor='#FF9900'></TD>
			</TR>";
		
		echo "
		</tbody>
		</table>
		</center>
	</div>
	<br style='clear:both;' />

	<p id='fuss'><A style='margin-right:780px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>
</div>";
?>

</div>
</body>
</html>
