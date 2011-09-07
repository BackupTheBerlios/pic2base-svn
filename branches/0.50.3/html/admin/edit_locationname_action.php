<?php
IF (!$_COOKIE['login'])
{
	//var_dump($sr);
  	header('Location: ../../../index.php');
}

include '../../share/global_config.php';
include $sr.'/bin/share/functions/main_functions.php';
$exiftool = buildExiftoolCommand($sr);

if(array_key_exists('loc_id',$_GET))
{
	$loc_id = $_GET['loc_id']; 
}

if(array_key_exists('locationname',$_POST))
{
	$locationname = $_POST['locationname']; 
}

if(array_key_exists('locationname_new',$_POST))
{
	$locationname_new = $_POST['locationname_new']; 
}

$start1 = microtime(); //Beginn der Laufzeitmesseun

//Nur wenn der Ortsname geaendert wurde, wird die Bearbeitung begonnen:
IF($locationname_new !== $locationname)
{
  mysql_connect ($db_server, $user, $PWD);
  
  $result1 = mysql_query("SELECT $table12.location, $table12.loc_id, $table2.loc_id, $table2.pic_id
  FROM $table2 LEFT JOIN $table12
  ON $table2.loc_id = $table12.loc_id
  WHERE $table12.location = \"$locationname\"");
  $num1 = mysql_num_rows($result1);
  
$end1 = microtime();
list($start1msec, $start1sec) = explode(" ",$start1);
list($end1msec, $end1sec) = explode(" ",$end1);
$runtime1 = ($end1sec + $end1msec) - ($start1sec + $start1msec);
echo "Zeit f&uuml;r Ermittlung der Anzahl alter Ortsnamen: ".$runtime1."<BR>";

  echo "
  <center>
  <table class='normal' border='0'>
	<tr>
	<td colspan='4' style='font-size:12pt; text-align:center;'>Neuer Ortsname wird gespeichert</td>
	</tr>
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='4'></TD>
	</TR>
	
	<tr>
	<td colspan='4'>&nbsp;</td>
	</tr>
	
  	<TR>
	<TD align='CENTER' colspan='4' style='padding-left:20px;'>
	Es gibt ".$num1." Bilder mit dem Ortsnamen 
	".$locationname."<BR><BR>
	Dieser soll in ".$locationname_new." ge&auml;ndert werden.<BR><BR>
  	Dieser Vorgang wird eine Weile dauern und ist abgeschlossen,<BR>
  	wenn hier wieder die Startseite des Administrationsbereichs zu sehen ist.<BR><BR>
  	<FONT color='red'>Bitte unterbrechen Sie den Vorgang NICHT</FONT><BR>
	</TD>
	</TR>
	
	<tr>
	<td colspan='4'>&nbsp;</td>
	</tr>
	
	<tr style='height:3px;'>
	<td class='normal' align='center' bgcolor='#FF9900' colspan='4'></TD>
	</TR>
  
  	<TR>
	<TD align='center' colspan = '4'></TD>
	</TR>
	</table>
</center>";
  
  flush();
  sleep(0);
  	
  	//Aktualisierung der Tabelle locations:
  	$result2 = mysql_query("UPDATE $table12 SET location = \"$locationname_new\" WHERE location = \"$locationname\"");

$end2 = microtime();
list($end2msec, $end2sec) = explode(" ",$end2);
$runtime2 = ($end2sec + $end2msec) - ($start1sec + $start1msec);
echo "<center><BR>Orts-Tabelle (locations) wurde modifiziert: ".$runtime2."</center><BR><BR>";	
  	
  	FOR($i1=0; $i1<$num1; $i1++)
  	{
  		$pic_id = mysql_result($result1, $i1, 'pic_id');
//  	echo $pic_id.",  ";
  		$FN = strtolower($pic_path."/".restoreOriFilename($pic_id, $sr));
// 		echo $FN;
  		
  		//Aktualisierung der Tabelle meta_data.City:
  		$result3 = mysql_query("UPDATE $table14 SET City = \"$locationname_new\" WHERE pic_id = '$pic_id'");
  		
  		//Aktualisierung der Tabelle meta_data.Caption_Abstract:
  		$result4 = mysql_query("SELECT Caption_Abstract FROM $table14 WHERE pic_id = '$pic_id'");
  		$CA = mysql_result($result4, isset($i4), 'Caption_Abstract');
  		//Textersetzung:
  		$search = 'Kamerastandort: '.$locationname;
  		$replace = 'Kamerastandort: '.$locationname_new;
  		$CA_new = str_replace($search, $replace, $CA);
  		IF($CA_new == $CA)
  		{
  			$CA_new = $CA.", Kamerastandort: ".$locationname_new;
  		}
// 		echo $CA."<BR>".$CA_new."<BR><BR>";
  		$result3 = mysql_query("UPDATE $table14 SET Caption_Abstract = \"$CA_new\" WHERE pic_id = '$pic_id'");
  		
  		//IPTC.City aendern:
  		$CA_new = htmlentities($CA_new);
  		shell_exec($exiftool." -IPTC:city=\"$locationname_new\" ".$FN." -overwrite_original -execute -Caption-Abstract=\"$CA_new\" ".$FN." -overwrite_original > /dev/null &");
  	};
  	
$end3 = microtime();
list($end3msec, $end3sec) = explode(" ",$end3);
$runtime3 = ($end3sec + $end3msec) - ($start1sec + $start1msec);
echo "<center>Zeit bis Anpassung Tabelle meta_data und exif-Daten f&uuml;r alle Bilder: ".$runtime3."</center><BR>";	
  	
  	echo "<meta http-equiv='Refresh' Content='2; URL=adminframe.php'>";
  	
?>
<script language="javascript">
document.location.locationname.focus();
</script>
<?php
}
ELSE
{
	echo "Die alte Ortsbezeichnung wurde nicht ge&auml;ndert.<BR><BR>Also gibt es nichts zu tun.<BR><BR>
	<INPUT TYPE='button' VALUE='Zur&uuml;ck' onClick='javascript:history.back()'>";
}
