<?php
IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../index.php');
}

	INCLUDE 'global_config.php';
	include $sr.'/bin/share/db_connect1.php';
/*
---------------------------------------------------------------------------------
|																				|
|	Datei wird bei der Kategorie-Zuweisung zur Darstellung des kompletten       |
|   Kategorien-Baumes verwendet	(rechte Spalte)                                 |
|       																		|
---------------------------------------------------------------------------------
*/

$bg_color = 0;

if(array_key_exists('kat_id',$_GET))
{
	$kat_id = $_GET['kat_id']; // fuer register_globals = off
}
else
{
	if(!isset($kat_id))
	{
		$kat_id = '';
	}
}

$KAT_ID = $kat_id;

	SWITCH ($modus)
	{
		CASE 'navigation':
			//Erzeugung der Baumstruktur:
			//Beim ersten Aufruf der Seite wird nur das Wurzel-Element angezeigt.
			//  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			//$KAT_ID = $kat_id;
			//  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			//Ermittlung aller 'Knoten-Elemente' (Elemente, an denen in die Tiefe verzweigt wird)
			$knoten_arr[]=$kat_id;
			
			WHILE ($kat_id > '1')
			{
				$res0 = mysql_query("SELECT parent FROM $table4 WHERE kat_id='$kat_id'");
				//echo mysql_error();
				$kat_id = mysql_result($res0, $i0, 'parent');
				//echo "Kat-ID in der Funktion: ".$kat_id."<BR>";
				$knoten_arr[]=$kat_id;
			}
			$knoten_arr = array_reverse($knoten_arr);
		break;
		
		CASE 'complete_view':
			//zunaechst werden alle Knotenelemente des Baumes ermittelt:
			$res1 = mysql_query("SELECT * FROM $table4");
			$num1 = mysql_num_rows($res1);
			FOR ($i1=0; $i1<$num1; $i1++)
			{
				$kat_id = mysql_result($res1, $i1, 'kat_id');
				$parent = mysql_result($res1, $i1, 'parent');
				$kat_id_arr[]=$kat_id;
				$parent_arr[]=$parent;
			}
			$knoten_arr[] = '';
			FOREACH($kat_id_arr AS $KATID)
			{
				FOREACH($parent_arr AS $PID)
				{
					IF ($KATID == $PID AND !in_array($PID,$knoten_arr))
					{
						$knoten_arr[] = $PID;
					}
				}
			}
			$knoten_arr[] = array_reverse($knoten_arr);
		break;
	}
	
	echo "
	<center>
	<TABLE class='kat_b' id='kat_b'>";
	
	function getAllElements($kat_id, $knoten_arr, $KAT_ID, $bg_color)
	{
		INCLUDE 'global_config.php';
		include $sr.'/bin/share/db_connect1.php';
		
		$result10 = mysql_query("SELECT * FROM $table4 WHERE parent='$kat_id' ORDER BY kategorie");
		$num10 = mysql_num_rows($result10);
		FOR ($i10=0; $i10<$num10; $i10++)
		{
			$kategorie = mysql_result($result10, $i10, 'kategorie');
			$parent = mysql_result($result10, $i10, 'parent');
			$level = mysql_result($result10, $i10, 'level');
			$kat_id = mysql_result($result10, $i10, 'kat_id');
			$space='';
			FOR ($N=0; $N<$level; $N++)
			{
				$space .="&#160;&#160;&#160;&#160;&#160;";
			}
			SWITCH($level)
			{
				CASE '1':
				$bg_color = '#DDDD00';
				break;
				
				CASE '2':
				$bg_color = '#FFFF00';
				break;
				
				CASE '3':
				$bg_color = '#FFFF99';
				break;
				
				CASE '4':
				$bg_color = '#FFFFDD';
				break;
				
				CASE '5':
				$bg_color = '#FFFFFF';
				break;
			}
			
			$kat_id_pos = array_search($kat_id, $knoten_arr);
			if($kat_id_pos > 0)
			{
				$kat_id_back = $knoten_arr[$kat_id_pos - 1];
			}
			IF (in_array($kat_id, $knoten_arr))
			{
				
				//echo $kat_id_back;
				$img = "<IMG src='$inst_path/pic2base/bin/share/images/arrow.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
				echo "<TR style='background-color:$bg_color;'>
					<TD class='kat1_b' id='kat1_b'>
					".$space.$img."&#160;".$kategorie."
					</TD>
					
					<TD class='kat2_b'>
					<INPUT type='checkbox' name='kat$kat_id'>
					</TD>
					
					</TR>";
				getAllElements($kat_id, $knoten_arr, $KAT_ID, $bg_color);
			}
			ELSE
			{
				$img = "<IMG src='$inst_path/pic2base/bin/share/images/arrow.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
				echo "<TR style='background-color:$bg_color;'>
					<TD class='kat1_b'>
					".$space.$img."&#160;".$kategorie."
					</TD>
					
					<TD class='kat2_b'>
					<INPUT type='checkbox' name='kat$kat_id'>
					</TD>
					
					</TR>";
			}
		}
	}
	
	$result10 = mysql_query("SELECT * FROM $table4 WHERE kat_id='1'");
	$num10 = mysql_num_rows($result10);
	FOR ($i10=0; $i10<$num10; $i10++)
	{
		$kategorie = mysql_result($result10, $i10, 'kategorie');
		$parent = mysql_result($result10, $i10, 'parent');
		$level = mysql_result($result10, $i10, 'level');
		$kat_id = mysql_result($result10, $i10, 'kat_id');
		$space='';
		//echo $level;
		FOR ($N=0; $N<$level; $N++)
		{
			$space .="&#160;&#160;&#160;&#160;&#160;";
		}
		SWITCH($level)
		{
			CASE '1':
			$bg_color = '#DDDD00';
			break;
			
			CASE '2':
			$bg_color = '#FFFF00';
			break;
			
			CASE '3':
			$bg_color = '#FFFF99';
			break;
			
			CASE '4':
			$bg_color = '#FFFFDD';
			break;
			
			CASE '5':
			$bg_color = '#FFFFFF';
			break;
		}
		
		//Link fuer den Ruecksprung erzeugen, d.h. naechst hoeheren Knoten aufrufen:
		$kat_id_back = array_search($kat_id, $knoten_arr);
		IF (in_array($kat_id, $knoten_arr))
		{
			
			//echo "Space: ".$space."<BR>";
			//echo $kat_id_back;
			$img = "<IMG src='$inst_path/pic2base/bin/share/images/arrow.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
			echo 	"<TR style='background-color:$bg_color;'>
					<TD class='kat1_b'>
					".$space.$img."&#160;".$kategorie."
					</TD>
					
					<TD class='kat2_b'>
					</TD>
					</TR>";
			getAllElements($kat_id, $knoten_arr, $KAT_ID, $bg_color);
		}
		ELSE
		{
			//echo "Space: ".$space."|<BR>";
			$img = "<IMG src='$inst_path/pic2base/bin/share/images/arrow.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
			echo 	"<TR style='background-color:$bg_color;'>
					<TD class='kat1_b'>
					".$space.$img."&#160;".$kategorie."
					</TD>
					
					<TD class='kat2_b'>
					<INPUT type='checkbox' name='kat$kat_id'>
					</TD>
					</TR>";
		}
	}
	echo "</TABLE>
	</center>";
?>