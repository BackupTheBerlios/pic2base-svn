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
  <title>pic2base - Dublettenpr&uuml;fung</title>
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="AUTHOR" content="k. henneberg">
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15">
  <link rel=stylesheet type="text/css" href="../../css/format1.css">
  <link rel="shortcut icon" href="../../share/images/favicon.ico">
</head>

<!--
/*
 * Project: pic2base
 * File: doublettenliste1.php
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
<script type = text/javascript>
function showDelWarning(FileName, uid, pic_id)
{
	var check = confirm("Wollen Sie das Bild wirklich entfernen?");
	if(check == true)
	{
		window.open('../../share/delete_picture.php?FileName=' + FileName + '&uid=' + uid + '&pic_id=' + pic_id, 'Delete', 'width=600px, height=450px');
	}
}
</script>

<BODY LANG="de-DE">
<DIV Class="klein">
<?php
/*
unset($username);
IF ($_COOKIE['login'])
{
	list($c_username) = preg_split('#,#',$_COOKIE['login']);
}
*/
$ACTION = $_SERVER['PHP_SELF'];
$link = "http://{$_SERVER['SERVER_NAME']}$ACTION";

include '../../share/global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/main_functions.php';

$result0 = mysql_query("SELECT * FROM $table1 WHERE id = '$uid' AND aktiv = '1'");
$username = mysql_result($result0, isset($i0), 'username');

//$quelle = $_SERVER['HTTP_REFERER'];

if (empty($_SERVER['HTTP_REFERER']))
{
	$quelle =$_SERVER['HTTP_HOST'].str_replace('&', '&amp;',$_SERVER['REQUEST_URI']);
}
else
{
	$quelle = $_SERVER['HTTP_REFERER'];
}
//echo $quelle;
if(array_key_exists('user_id',$_GET))
{
	$user_id = $_GET['user_id']; 
}

if(array_key_exists('method',$_GET))
{
	$method = $_GET['method']; 
}
ELSE
{
	$method = '';
}
//echo $navi;
echo "
<div class='page'>

	<p id='kopf'>pic2base :: Doublettenpr&uuml;fung <span class='klein'>(User: $username)</span></p>
	
	<div class='navi' style='clear:right;'>
		<div class='menucontainer'>";
		IF(strstr($quelle, "edit_start.php") OR strstr($quelle, "stapel2.php") OR strstr($quelle, "doublettenliste1.php"))
		{
			createNavi3($uid);
		}
		ELSEIF(strstr($quelle, "db_wartung1.php"))
		{
			include '../admin/adminnavigation.php';
		}
		echo "</div>
	</div>
	
	<div class='content'>
		<center>
		<table class='liste1'>
		<tbody>";

			//Tabelle doubletten von alten Eintraegen bereinigen:
			$result = mysql_query("DELETE FROM $table21 WHERE user_id = '$user_id'");
			//Ermittlung der Datensaetze, bei denen der Original-Dateiname und die md5-Summe mehrfach gleich sind:
			$result0 = mysql_query("SELECT P1.pic_id, P1.FileNameOri, P1.md5sum, P1.aktiv 
			FROM $table2 AS P1, 
			(SELECT P.FileNameOri, P.md5sum FROM $table2 as P GROUP BY P.FileNameOri, P.md5sum HAVING COUNT( * ) > 1) 
			as P2 
			WHERE P1.FileNameOri = P2.FileNameOri 
			AND P1.md5sum = P2.md5sum
			AND P1.aktiv = '1' 
			ORDER BY P1.pic_id DESC");
			echo mysql_error();
			$num0 = mysql_num_rows($result0);
			$pid_arr = array();
			FOR($i0=0; $i0<$num0; $i0++)
			{
				$pid_dbl_ori = mysql_result($result0, $i0, 'P1.pic_id');		//Bild-ID des Referenzbildes, zu dem weitere Vorkommen existieren
				$FileNameOri = mysql_result($result0, $i0, 'P1.FileNameOri');	//dessen Original-Dateiname
				$md5sum = mysql_result($result0, $i0, 'P1.md5sum');				//dessen Pruefsumme
				$pid_arr[] = $pid_dbl_ori;										//Array der Bild-ID's
				
				$result0_1 = mysql_query("SELECT * FROM $table2 WHERE md5sum = '$md5sum' AND FileNameOri = '$FileNameOri' AND aktiv = '1'");
				echo mysql_error();
				$num0_1 = mysql_num_rows($result0_1);
				FOR($i0_1=0; $i0_1<$num0_1; $i0_1++)
				{
					$pid_dbl_kopie = mysql_result($result0_1, $i0_1, 'pic_id');	//Bild-ID der weiteren Vorkommen zu dem obigen Referenzbild
					IF($pid_dbl_ori !== $pid_dbl_kopie AND !in_array($pid_dbl_kopie, $pid_arr))
					{
						//echo "Bild ".$pid_dbl_ori." hat als Dublette Bild ".$pid_dbl_kopie."<BR>";
						IF($user_id !== NULL)
						{
							$result0_2 = mysql_query("INSERT INTO $table21 (new_pic_id, old_pic_id, user_id) VALUES ('$pid_dbl_ori', '$pid_dbl_kopie', '$user_id')");
						}
						echo mysql_error();
						$pid_arr[] = $pid_dbl_kopie;
					}
				}
			}
			
			IF($method == 'all')
			{
				//Variante, wenn die Dublettenpruefung vom Admin nach der DB-Wartung gestartet wurde:
				$result1 = mysql_query("SELECT * FROM $table21");
			}
			ELSEIF($method == '')
			{
				//Variante, wenn die Dublettenpruefung von einem normalen User aus dem Bearbeiten-Menue gestartet wurde:
				$result1 = mysql_query("SELECT * FROM $table21 WHERE user_id = '$user_id'");
			}
			
			$num1 = mysql_num_rows($result1);
			IF($num1 > 0)
			{
				echo "<TR style='height:3px;'>
				<TD colspan='4' bgcolor='#FF9900'></TD>
				</TR>
				
				<TR>
				<TD align='center'>neu erfasstes Bild</TD>
				<TD align='center'>in der DB vorhandenes Bild</TD>
				<TD colspan='2' align='center'>Option</TD>
				</TR>
				
				<TR style='height:3px;'>
				<TD colspan='4' bgcolor='#FF9900'></TD>
				</TR>
				
				<TR style='height:3px;'>
				<TD colspan='4'></TD>
				</TR>
				
				<TR style='height:10px;'>
				<TD colspan='4' bgcolor='darkgrey'><FONT COLOR='yellow'>Wenn Sie sicher sind, da&szlig; Sie keine Doubletten in der Datenbank haben wollen, k&ouml;nnen Sie alle mehrfachen Vorkommen mit einem Klick auf den Button am Ende der Seite auf einmal entfernen.<BR>
				Anderenfalls k&ouml;nnen Sie mit den Symbolen am rechten Bildrand f&uuml;r jedes Bild individuell entscheiden.</FONT></TD>
				</TR>
				
				<TR style='height:3px;'>
				<TD colspan='4'></TD>
				</TR>";
				
				FOR($i1=0; $i1<$num1; $i1++)
				{
					$new_pic_id = mysql_result($result1, $i1, 'new_pic_id');
					$result2 = mysql_query("SELECT * FROM $table2 WHERE pic_id = '$new_pic_id' AND aktiv = '1'");	// Daten des neu erfassten Bildes
					$FileName = mysql_result($result2, isset($i2), 'FileName');echo mysql_error();
					$old_pic_id = mysql_result($result1, $i1, 'old_pic_id');
					$result3 = mysql_query("SELECT * FROM $table2 WHERE pic_id = '$old_pic_id' AND aktiv = '1'");	// Daten der bereits in der DB enthaltenen Dublette
					//$FileName = '';
					IF($new_pic_id !== $old_pic_id)
					{
						//Ermittlung der Vorschaubildes der neuen Bild-Datei:
						$FileNameHQ_new = mysql_result($result2, isset($i2), 'FileNameHQ');
						$image_new = "../../../images/vorschau/hq-preview/".$FileNameHQ_new;
						
						//Ermittlung der Vorschaubildes der alten Bild-Datei:
						$FileNameHQ_old = mysql_result($result3, isset($i3), 'FileNameHQ');
						$image_old = "../../../images/vorschau/hq-preview/".$FileNameHQ_old;
						
						echo "
						<TR>
						<TD style='width=350px; text-align: center;'><img src='$image_new' width='350' title='Bild-ID: $new_pic_id' /></TD>
						<TD style='width=350px; text-align: center;'><img src='$image_old' width='350' title='Bild-ID: $old_pic_id' /></TD>
						<TD style='width=55px; text-align: center;'><a href='../../share/save_doublette.php?pic_id=$new_pic_id&user_id=$user_id'><img src='../../share/images/ok.gif' title='Bild in der Datenbank belassen' /></a></TD>
						<TD style='width=55px; text-align: center;'><A HREF = '#' onClick=\"showDelWarning('$FileName', '$uid', '$new_pic_id')\";><img src='../../share/images/delete.gif' title='Doublette aus der Datenbank entfernen' /></a></TD>
						</TR>";
					}
				}
				echo "
				<TR style='height:3px;'>
				<TD colspan='4' align='center' bgcolor='#FF9900'></TD>
				</TR>
				
				<TR style='height:10px;'>
				<TD colspan='4' align='center' bgcolor='yellow'><INPUT TYPE='button' VALUE='Keine Doubletten zulassen - alle doppelten Bilder aus der Datenbank entfernen' onClick=\"location.href='../../share/delete_all_dubls1.php?method=$method&user_id=$user_id&uid=$uid'\"></TD>
				</TR>
				
				<TR style='height:3px;'>
				<TD colspan='4' align='center' bgcolor='#FF9900'></TD>
				</TR>";
			}
			ELSE
			{
				echo "<TR style='height:100px;'>
				<TD colspan='4' align='center'>Es wurden keine Doubletten gefunden.</TD>
				</TR>";
			}
		
		echo "
		</tbody>
		</table>
		
		</center>
	</div>
	<br style='clear:both;' />

	<p id='fuss'><A style='margin-right:745px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank' title='pic2base im Web'>www.pic2base.de</A>".$cr."</p>
</div>";
?>

</div>
</body>
</html>
