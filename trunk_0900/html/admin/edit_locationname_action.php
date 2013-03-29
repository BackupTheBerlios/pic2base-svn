<?php
IF (!$_COOKIE['uid'])
{
  	header('Location: ../../../index.php');
}

include '../../share/global_config.php';
include $sr.'/bin/share/functions/main_functions.php';
$exiftool = buildExiftoolCommand($sr);

if(array_key_exists('pic_id',$_GET))
{
	$pic_id = $_GET['pic_id']; 
}

if(array_key_exists('locationname',$_POST))
{
	$locationname = $_POST['locationname']; 
}

if(array_key_exists('locationname_new',$_POST))
{
	$locationname_new = $_POST['locationname_new'];
	$iptc_city = $locationname_new; 
}
//echo "alte Ortsbezeichnung: ".$locationname."<BR>";
//echo "neue Ortsbezeichnung: ".$locationname_new."<BR>";
//echo "IPTC-Ort: ".$iptc_city."<BR>";
//Nur wenn der Ortsname geaendert wurde, wird die Bearbeitung begonnen:

IF($locationname_new !== $locationname)
{  
	$locationname_statement = $locationname;   
	$result1 = mysql_query("SELECT City, pic_id
	  FROM $table2
	  WHERE City = '$locationname_statement'");
	  echo mysql_error();
	  $num1 = mysql_num_rows($result1);
	
	  echo "
	  <center>
	  <table class='normal' border='0'>
		<tr>
		<td colspan='4' style='font-size:12pt; text-align:center;'>Neuer Ortsname wird gespeichert</td>
		</tr>
		
		<tr style='height:3px;'>
		<td class='normal' align='center' bgcolor='darkred' colspan='4'></TD>
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
		<td class='normal' align='center' bgcolor='darkred' colspan='4'></TD>
		</TR>
	  
	  	<TR>
		<TD align='center' colspan = '4'></TD>
		</TR>
		</table>
	</center>";
	  
	flush();
	sleep(0);
  	
  	//Aktualisierung der Tabelle pictures:
  	$result2 = mysql_query("UPDATE $table2 SET City = \"$locationname_new\" WHERE City = \"$locationname_statement\"");
  	//Aenderung der Caption_Abstracts und IPTC-Eintraege fuer alle betroffenen Bilder:
  	FOR($i1=0; $i1<$num1; $i1++)
  	{
  		$pic_id = mysql_result($result1, $i1, 'pic_id');
  		$FN = strtolower($pic_path."/".restoreOriFilename($pic_id, $sr));
  		//Aktualisierung der Tabelle pictures.Caption_Abstract:
  		$result4 = mysql_query("SELECT Caption_Abstract FROM $table2 WHERE pic_id = '$pic_id'");
  		$CA = mysql_result($result4, isset($i4), 'Caption_Abstract');
  		//Textersetzung:
  		$search = 'Kamerastandort: '.$locationname;  		// alter Textbestandteil
  		$replace = 'Kamerastandort: '.$locationname_new;	// neue Textergaenzung
  		$CA_new = str_replace($search, $replace, $CA);		// das neue CaptionAbstract
  		IF($CA_new == $CA)									// Wenn es bisher keine Standort-Angabe gab...
  		{
  			$CA_new = $CA.", Kamerastandort: ".$locationname_new;
  		}
  		$result3 = mysql_query("UPDATE $table2 SET Caption_Abstract = \"$CA_new\" WHERE pic_id = '$pic_id'");
  		//IPTC.City aendern:
  		$iptc_city = strip_tags($iptc_city);
  		$command = " -IPTC:City=\"$iptc_city\" ".$FN." -overwrite_original -execute -IPTC:Caption-Abstract=\"$CA_new\" ".$FN." -overwrite_original > /dev/null &";
  		shell_exec($exiftool." ".$command);	
  	}
  	echo "<meta http-equiv='Refresh' Content='0; URL=adminframe.php?item=editlocationname'>";

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
