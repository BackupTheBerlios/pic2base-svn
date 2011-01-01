<?php
IF (!$_COOKIE['login'])
{
include '../share/global_config.php';
//var_dump($sr);
  header('Location: ../../index.php');
}

INCLUDE 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/share/functions/ajax_functions.php';

$sel_one = "<IMG src='$inst_path/pic2base/bin/share/images/one.gif' width='22' height='11' hspace='0' vspace='0' border='0' title='einzelne Bilder dieser Kategorie ausw&auml;hlen'>";
$sel_all = "<IMG src='$inst_path/pic2base/bin/share/images/all.gif' width='22' height='11' hspace='0' vspace='0' border='0' title='alle Bilder dieser Kategorie ausw&auml;hlen'>";
		
//Datei wird zur Navigation durch die Kategorien mit Hilfe einer Baumstruktur verwendet
//$ziel = $target_url;
//echo $base_file;
//echo $modus;
//Erzeugung der Baumstruktur:
//Beim ersten Aufruf der Seite wird nur das Wurzel-Element angezeigt.
//  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//var_dump($_REQUEST);

if(array_key_exists('mod',$_GET))
{
	$mod = $_GET['mod']; // fuer register_globals = off
}

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

$KAT_ID = $kat_id;		//$KAT_ID: uebergeordnete Kat., $kat_id: Unterkategorien

if(!isset($ID))
{
	$ID = '';
}

if(!isset($bewertung))
{
	$bewertung = '';
}

	//  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//Ermittlung aller 'Knoten-Elemente' (Elemente, an denen in die Tiefe verzweigt wird)
	$knoten_arr[]=$kat_id;
	
	WHILE ($kat_id > '1')
	{
		$res0 = mysql_query("SELECT parent FROM $table4 WHERE kat_id='$kat_id'");
		echo mysql_error();
		$kat_id = mysql_result($res0, isset($i0), 'parent');
		//echo "Kat-ID in der Funktion: ".$kat_id."<BR>";
		$knoten_arr[]=$kat_id;
	}
	$knoten_arr = array_reverse($knoten_arr);

	echo "<TABLE id='kat'>";
	//echo $bewertung;
	function getElements($kat_id, $knoten_arr, $KAT_ID, $ID, $mod, $modus, $base_file, $bewertung)
	{
		global $ziel;
		include 'global_config.php';
		include $sr.'/bin/share/db_connect1.php';
		INCLUDE $sr.'/bin/share/global_config.php';
		
		$sel_one = "<IMG src='$inst_path/pic2base/bin/share/images/one.gif' width='22' height='11' hspace='0' vspace='0' border='0' title='einzelne Bilder dieser Kategorie ausw&auml;hlen'>";
		$sel_all = "<IMG src='$inst_path/pic2base/bin/share/images/all.gif' width='22' height='11' hspace='0' vspace='0' border='0' title='alle Bilder dieser Kategorie ausw&auml;hlen'>";
		
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
				$space .="&#160;&#160;";
			}
			
			//zu welchen Kategorien gibt es Lexikon-Eintraege?
			$result13 = mysql_query("SELECT * FROM $table11 WHERE kat_id = '$kat_id' AND info <> ''");
			$num13 = mysql_num_rows($result13);
			IF($num13 !== 0)
			{
				$book = "<img src='$inst_path/pic2base/bin/share/images/book_green.gif' width='15' height='15' title='Kategorie-Informationen lesen / bearbeiten' />";
			}
			ELSE
			{
				$book = "<img src='$inst_path/pic2base/bin/share/images/book_grey.gif' width='15' height='15' title='Kategorie-Informationen hinzuf&uuml;gen' />";
			}
			
			$kat_id_pos = array_search($kat_id, $knoten_arr);
			if($kat_id_pos > 0)
			{
				$kat_id_back = $knoten_arr[$kat_id_pos - 1];
			}
			
			IF (in_array($kat_id, $knoten_arr))
			{
				$treestatus = 'minus';
				$img = "<IMG src='$inst_path/pic2base/bin/share/images/minus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
				echo 	"<TR id='kat'>
					<TD id='kat1'>
					".$space."<a href='$ziel?kat_id=$parent&mod=$mod&pic_id=0'>".$img."</a>&#160;";
				
				
				IF($base_file == 'edit_remove_kat' OR $base_file == 'recherche2' OR $base_file == 'edit_bewertung')
				{
					echo "<SPAN style='cursor:pointer;' onClick='getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",0,0,0,\"$treestatus\")' title='Hier klicken, um alle Bilder der Kategorie $kategorie anzuzeigen'>".$kategorie."</span></TD>";
					$sel_one = $book;
					echo "
					<TD>
					<span style='cursor:pointer;' onClick='showKatInfo(\"$kat_id\")'>".$sel_one."</SPAN></TD>
					<TD></TD>
					<TD></TD>
					</TR>";
				}
				ELSE
				{
					echo "<span style='cursor:pointer;' onClick=\"showKatInfo('$kat_id')\" title='Informationen zur Kategorie $kategorie' alt='Info' />".$kategorie."</span></TD>";
					echo "
					<TD>
					<SPAN style='cursor:pointer;' onClick='getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",0,0,0,\"$treestatus\")'>".$sel_one."</SPAN></TD>
					<TD>
					<SPAN style='cursor:pointer;' onClick='getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",1,0,0,\"$treestatus\")'>".$sel_all."</SPAN></TD>
					<TD></TD>
					</TR>";
				}
				
					
				getElements($kat_id, $knoten_arr, $KAT_ID, $ID, $mod, $modus, $base_file, $bewertung);
			}
			ELSE
			{
				$treestatus = 'plus';
				//Das plus wird nur gezeigt, wenn es weitere Unterkategorien gibt:
				$result11 = mysql_query("SELECT * FROM $table4 WHERE parent = '$kat_id'");
				$num11 = mysql_num_rows($result11);
				IF($num11 > 0)
				{
					$img = "<IMG src='$inst_path/pic2base/bin/share/images/plus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
					echo 	"<TR id='kat'>
					<TD id='kat1'>
					".$space."<a href='$ziel?kat_id=$kat_id&mod=$mod&pic_id=0'>".$img."</a>&#160;";
				}
				ELSE
				{
					$img = "<IMG src='$inst_path/pic2base/bin/share/images/platzhalter.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
					echo 	"<TR id='kat'>
					<TD id='kat1'>
					".$space."".$img."&#160;";
				}
				
				IF($base_file == 'edit_remove_kat' OR $base_file == 'recherche2' OR $base_file == 'edit_bewertung')
				{
					echo "<SPAN style='cursor:pointer;' onClick='getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",0,0,0,\"$treestatus\")' title='Hier klicken, um alle Bilder der Kategorie $kategorie anzuzeigen'>".$kategorie."</span></TD>";
					$sel_one = $book;
					echo "
					<TD>
					<span style='cursor:pointer;' onClick='showKatInfo(\"$kat_id\")'>".$sel_one."</SPAN></TD>
					<TD></TD>
					<TD style='font-size:12px;text-align:right;'>".getNumberOfPictures($kat_id, $modus, $bewertung, $treestatus)."</TD>
					</TR>";
				}
				ELSE
				{
					echo "<span style='cursor:pointer;' onClick=\"showKatInfo('$kat_id')\" title='Informationen zur Kategorie $kategorie' alt='Info' />".$kategorie."</span></TD>";
					echo "
					<TD>
					<SPAN style='cursor:pointer;' onClick='getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",0,0,0,\"$treestatus\")'>".$sel_one."</SPAN></TD>
					<TD>
					<SPAN style='cursor:pointer;' onClick='getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",1,0,0,\"$treestatus\")'>".$sel_all."</SPAN></TD>
					<TD style='font-size:12px;text-align:right;'>".getNumberOfPictures($kat_id, $modus, $bewertung, $treestatus)."</TD>
					</TR>";
				}
				
			}
		}
	}
//Beginn des Baum-Aufbaus:	
IF($KAT_ID=='' OR $KAT_ID == '0')
{
	$KAT_ID = '1';
	//echo $KAT_ID.", ".$modus;
}
	$result10 = mysql_query("SELECT * FROM $table4 WHERE kat_id='$KAT_ID'");
	$num10 = mysql_num_rows($result10);
	FOR ($i10=0; $i10<$num10; $i10++)
	{
		$kategorie = mysql_result($result10, $i10, 'kategorie');
		$parent = mysql_result($result10, $i10, 'parent');
		$level = mysql_result($result10, $i10, 'level');
		$kat_id = mysql_result($result10, $i10, 'kat_id');
		IF($level > '0')
		{
			$space="<a href='$ziel?kat_id=1&mod=$mod&pic_id=0' title='nach ganz oben'><img src='$inst_path/pic2base/bin/share/images/up.gif' width='11' height='11' border='0'></a>:";
		}
		//echo $level;
		FOR ($N=1; $N<$level; $N++)
		{
			$space .=":";
		}
		
		// zu welchen Kategorien gibt es Lexikon-Eintraege?
		$result13 = mysql_query("SELECT * FROM $table11 WHERE kat_id = '$kat_id' AND info <> ''");
		$num13 = mysql_num_rows($result13);
		IF($num13 !== 0)
		{
			$book = "<img src='$inst_path/pic2base/bin/share/images/book_green.gif' width='15' height='15' title='Kategorie-Informationen lesen / bearbeiten' />";
		}
		ELSE
		{
			$book = "<img src='$inst_path/pic2base/bin/share/images/book_grey.gif' width='15' height='15' title='Kategorie-Informationen hinzuf&uuml;gen' />";
		}
		
		//Link fuer den Ruecksprung erzeugen, d.h. noechst hoeheren Knoten aufrufen:
		$kat_id_back = array_search($kat_id, $knoten_arr);
		IF (in_array($kat_id, $knoten_arr))
		{
			$treestatus = 'minus';
			$img = "<IMG src='$inst_path/pic2base/bin/share/images/minus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
			echo 	"<TR id='kat'>
					<TD id='kat1'>";
			IF(!isset($space))
			{
				$space = '';
			}
			echo	$space."<a href='$ziel?kat_id=$parent&mod=$mod&pic_id=0'>".$img."</a>&#160;";
			
			
			IF($base_file == 'edit_remove_kat' OR $base_file == 'recherche2' OR $base_file == 'edit_bewertung')
			{
				//auf Kategorienamen liegt der Link zu den Bildern, auf dem Icon die Info zum Kat-Lexikon:
				echo "<SPAN style='cursor:pointer;' onClick='getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",0,0,0,\"$treestatus\")' title='Hier klicken, um alle Bilder der Kategorie $kategorie anzuzeigen'>".$kategorie."</span></TD>";
				IF($kat_id == '1')
				{
					$kat_info_link = "<IMG src='$inst_path/pic2base/bin/share/images/platzhalter.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
				}
				ELSE
				{
					$kat_info_link = "<span style='cursor:pointer;' onClick='showKatInfo(\"$kat_id\")'>".$book."</SPAN>";
				}
				echo "
				<TD>
				<span style='cursor:pointer;' onClick='showKatInfo(\"$kat_id\")'>".$kat_info_link."</SPAN></TD>
				<TD></TD>
				<TD style='font-size:12px;'>".getNumberOfPictures($kat_id, $modus, $bewertung, $treestatus)."</TD>";
			}
			ELSE
			{
				echo "<span style='cursor:pointer;' onClick=\"showKatInfo('$kat_id')\">".$kategorie."</span></TD>";
				echo "
				<TD>
				<SPAN style='cursor:pointer;' onClick='getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",0,0,0,\"$treestatus\")'>".$sel_one."</SPAN></TD>
				<TD>
				<SPAN style='cursor:pointer;' onClick='getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",1,0,0,\"$treestatus\")'>".$sel_all."</SPAN>
				</TD>
				<TD style='font-size:12px;'>".getNumberOfPictures($kat_id, $modus, $bewertung, $treestatus)."</TD>";
			}			
			
			getElements($kat_id, $knoten_arr, $KAT_ID, $ID, $mod, $modus, $base_file, $bewertung);
		}
		ELSE
		{
			$treestatus = 'plus';
			$img = "<IMG src='$inst_path/pic2base/bin/share/images/plus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
			echo 	"<TR id='kat'>
				<TD id='kat1'>";
			IF(!isset($space))
			{
				$space = '';
			}
			
			IF($base_file == 'edit_remove_kat' OR $base_file == 'recherche2' OR $base_file == 'edit_bewertung')
			{
				echo	$space."<a href='$ziel?kat_id=$kat_id&mod=$mod&pic_id=0'>".$img."</a>&#160;"."<SPAN style='cursor:pointer;' onClick='getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",0,0,0,\"$treestatus\")' title='Hier klicken, um alle Bilder der Kategorie $kategorie anzuzeigen'>".$kategorie."</span></TD>";
				IF($kat_id == '1')
				{
					$kat_info_link = "<IMG src='$inst_path/pic2base/bin/share/images/platzhalter.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
				}
				ELSE
				{
					$kat_info_link = "<span style='cursor:pointer;' onClick='showKatInfo(\"$kat_id\")'>".$book."</SPAN>";
				}
				echo "
				<TD>
				<span style='cursor:pointer;' onClick='showKatInfo(\"$kat_id\")'>".$kat_info_link."</SPAN></TD>
				<TD></TD>
				<TD style='font-size:12px;text-align:right;'></TD>
				</TR>";
			}
			ELSE
			{
				echo	$space."<a href='$ziel?kat_id=$kat_id&mod=$mod&pic_id=0'>".$img."</a>&#160;"."<span style='cursor:pointer;' onClick=\"showKatInfo('$kat_id')\" title='Informationen zur Kategorie $kategorie' alt='Info' />".$kategorie."</span></TD>";
				echo "
				<TD>
				<SPAN style='cursor:pointer;' onClick='getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",0,0,0,\"$treestatus\")'>".$sel_one."</SPAN></TD>
				<TD>
				<SPAN style='cursor:pointer;' onClick='getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\",\"$bewertung\",1,0,0,\"$treestatus\")'>".$sel_all."</SPAN>
				</TD>
				<TD style='font-size:12px;text-align:right;'></TD>
				</TR>";
			}
			
			if(!isset($bewertung))
			{
				$bewertung = '';
			}
		}
	}
	echo "</TABLE>";
?>