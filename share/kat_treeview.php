<?php
	INCLUDE 'global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	include $sr.'/bin/share/functions/ajax_functions.php';
	
	$sel_one = "<IMG src='$inst_path/pic2base/bin/share/images/one.gif' width='22' height='11' hspace='0' vspace='0' border='0' title='einzelne Bilder dieser Kategorie ausw&auml;hlen'>";
	$sel_all = "<IMG src='$inst_path/pic2base/bin/share/images/all.gif' width='22' height='11' hspace='0' vspace='0' border='0' title='alle Bilder dieser Kategorie ausw&auml;hlen'>";
			
	//include 'functions/main_functions.php';
	//Datei wird zur Navigation durch die Kategorien mit Hilfe einer Baumstruktur verwendet
	//$ziel = $target_url;
	//echo $base_file;
	//Erzeugung der Baumstruktur:
	//Beim ersten Aufruf der Seite wird nur das Wurzel-Element angezeigt.
	//  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	$KAT_ID = $kat_id;		//$KAT_ID: übergeordnete Kat., $kat_id: Unterkategorien
	//  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//Ermittlung aller 'Knoten-Elemente' (Elemente, an denen in die Tiefe verzweigt wird)
	$knoten_arr[]=$kat_id;
	
	WHILE ($kat_id > '1')
	{
		$res0 = mysql($db, "SELECT parent FROM $table4 WHERE kat_id='$kat_id'");
		echo mysql_error();
		$kat_id = mysql_result($res0, $i0, 'parent');
		//echo "Kat-ID in der Funktion: ".$kat_id."<BR>";
		$knoten_arr[]=$kat_id;
	}
	$knoten_arr = array_reverse($knoten_arr);

	echo "<TABLE id='kat'>";
	//echo $bewertung;
	function getElements($kat_id, $knoten_arr, $KAT_ID, $ID, $mod, $modus, $base_file, $bewertung)
	{
		include 'global_config.php';
		include $sr.'/bin/share/db_connect1.php';
		INCLUDE $sr.'/bin/share/global_config.php';
		
		$sel_one = "<IMG src='$inst_path/pic2base/bin/share/images/one.gif' width='22' height='11' hspace='0' vspace='0' border='0' title='einzelne Bilder dieser Kategorie ausw&auml;hlen'>";
		$sel_all = "<IMG src='$inst_path/pic2base/bin/share/images/all.gif' width='22' height='11' hspace='0' vspace='0' border='0' title='alle Bilder dieser Kategorie ausw&auml;hlen'>";
		
		$result10 = mysql($db, "SELECT * FROM $table4 WHERE parent='$kat_id' ORDER BY kategorie");
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
				$space .="&#160;&#160;";
			}
			
			$result13 = mysql($db, "SELECT * FROM $table11 WHERE kat_id = '$kat_id' AND info <> ''");
			$num13 = mysql_num_rows($result13);
			
			IF($num13 !== 0)
			{
				$font_color = 'blue';
			}
			ELSE
			{
				$font_color = 'black';
			}
			
			$kat_id_pos = array_search($kat_id, $knoten_arr);
			$kat_id_back = $knoten_arr[$kat_id_pos - 1];
			
			IF (in_array($kat_id, $knoten_arr))
			{
				$img = "<IMG src='$inst_path/pic2base/bin/share/images/minus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
				echo 	"<TR id='kat'>
					<TD id='kat1'>
					".$space."<a href='$ziel?kat_id=$parent&mod=$mod&pic_id=0'>".$img."</a>&#160;";
					
					echo "<span style='cursor:pointer; color:$font_color' onClick=\"showKatInfo('$kat_id')\" title='Informationen zur Kategorie $kategorie' alt='Info' />".$kategorie."</span></TD>";
					IF($base_file == 'edit_remove_kat' OR $base_file == 'recherche2' OR $base_file == 'edit_bewertung')
					{
						$sel_one = "<img src='$inst_path/pic2base/bin/share/images/ok.gif' width='15' height='15' />";
						$sel_all = '&#160;';
					}
					echo "
					<TD>
					<SPAN style='cursor:pointer;' onClick='getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",0,0,0)'>".$sel_one."</SPAN></TD>
					<TD>
					<SPAN style='cursor:pointer;' onClick='getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",1,0,0)'>".$sel_all."</SPAN>
					</TD>
					<TD></TD>
					</TR>";
				getElements($kat_id, $knoten_arr, $KAT_ID, $ID, $mod, $modus, $base_file, $bewertung);
			}
			ELSE
			{
				$img = "<IMG src='$inst_path/pic2base/bin/share/images/plus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
				echo 	"<TR id='kat'>
					<TD id='kat1'>
					".$space."<a href='$ziel?kat_id=$kat_id&mod=$mod&pic_id=0'>".$img."</a>&#160;";
				
					echo "<span style='cursor:pointer; color:$font_color' onClick=\"showKatInfo('$kat_id')\" title='Informationen zur Kategorie $kategorie' alt='Info' />".$kategorie."</span></TD>";
					IF($base_file == 'edit_remove_kat' OR $base_file == 'recherche2' OR $base_file == 'edit_bewertung')
					{
						$sel_one = "<img src='$inst_path/pic2base/bin/share/images/ok.gif' width='15' height='15' />";
						$sel_all = '&#160;';
					}
					echo "
					<TD>
					<SPAN style='cursor:pointer;' onClick='getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",0,0,0)'>".$sel_one."</SPAN></TD>
					<TD>
					<SPAN style='cursor:pointer;' onClick='getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",1,0,0)'>".$sel_all."</SPAN>
					</TD>
					<TD style='font-size:12px;text-align:right;'>".getNumberOfPictures($kat_id, $modus, $bewertung)."</TD>
					</TR>";
			}
		}
	}
//Beginn des Baum-Aufbaus:	
IF($KAT_ID=='' OR $KAT_ID == '0')
{
	$KAT_ID = '1';
	//echo $KAT_ID.", ".$modus;
}
	$result10 = mysql($db, "SELECT * FROM $table4 WHERE kat_id='$KAT_ID'");
	$num10 = mysql_num_rows($result10);
	FOR ($i10=0; $i10<$num10; $i10++)
	{
		$kategorie = mysql_result($result10, $i10, 'kategorie');
		$parent = mysql_result($result10, $i10, 'parent');
		$level = mysql_result($result10, $i10, 'level');
		$kat_id = mysql_result($result10, $i10, 'kat_id');
		IF($level > '0')
		{
			$space="<a href='$ziel?kat_id=1&mod=$mod&pic_id=0' title='Top'><img src='$inst_path/pic2base/bin/share/images/up.gif' width='11' height='11' border='0'></a>:";
		}
		//echo $level;
		FOR ($N=1; $N<$level; $N++)
		{
			$space .=":";
		}
		
		$result13 = mysql($db, "SELECT * FROM $table11 WHERE kat_id = '$kat_id' AND info <> ''");
			$num13 = mysql_num_rows($result13);
			
			IF($num13 !== 0)
			{
				$font_color = 'blue';
			}
			ELSE
			{
				$font_color = 'black';
			}
		
		//echo $base_file;
		//Link für den Rücksprung erzeugen, d.h. nächst höheren Knoten aufrufen:
		$kat_id_back = array_search($kat_id, $knoten_arr);
		IF (in_array($kat_id, $knoten_arr))
		{
			$img = "<IMG src='$inst_path/pic2base/bin/share/images/minus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
			echo 	"<TR id='kat'>
				<TD id='kat1'>";
			
			echo	$space."<a href='$ziel?kat_id=$parent&mod=$mod&pic_id=0'>".$img."</a>&#160;";
				echo "<span style='cursor:pointer; color:$font_color' onClick=\"showKatInfo('$kat_id')\" title='Imformationen zur Kategorie $kategorie' alt='Info' />".$kategorie."</span></TD>";
				IF($base_file == 'edit_remove_kat' OR $base_file == 'recherche2' OR $base_file == 'edit_bewertung')
				{
					$sel_one = "<img src='$inst_path/pic2base/bin/share/images/ok.gif' width='15' height='15' />";
					$sel_all = '&#160;';
				}
				echo "
				<TD>
				<SPAN style='cursor:pointer;' onClick='getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",0,0,0)'>".$sel_one."</SPAN></TD>
				<TD>
				<SPAN style='cursor:pointer;' onClick='getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",1,0,0)'>".$sel_all."</SPAN>
				</TD>
				<TD style='font-size:12px;'><BR></TD>
				</TR>";
			getElements($kat_id, $knoten_arr, $KAT_ID, $ID, $mod, $modus, $base_file, $bewertung);
		}
		ELSE
		{
			$img = "<IMG src='$inst_path/pic2base/bin/share/images/plus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
			echo 	"<TR id='kat'>
				<TD id='kat1'>";
			
			echo	$space."<a href='$ziel?kat_id=$kat_id&mod=$mod&pic_id=0'>".$img."</a>&#160;"."<span style='cursor:pointer; color:$font_color' onClick=\"showKatInfo('$kat_id')\" title='Informationen zur Kategorie $kategorie' alt='Info' />".$kategorie."</span></TD>";
				IF($base_file == 'edit_remove_kat' OR $base_file == 'recherche2' OR $base_file == 'edit_bewertung')
				{
					$sel_one = "<img src='$inst_path/pic2base/bin/share/images/ok.gif' width='15' height='15' />";
					$sel_all = '&#160;';
				}
				echo "
				<TD>
				<SPAN style='cursor:pointer;' onClick='getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",0,0,0)'>".$sel_one."</SPAN></TD>
				<TD>
				<SPAN style='cursor:pointer;' onClick='getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",1,0,0)'>".$sel_all."</SPAN>
				</TD>
				<TD style='font-size:12px;text-align:right;'></TD>
				</TR>";
		}
	}
	echo "</TABLE>";
?>