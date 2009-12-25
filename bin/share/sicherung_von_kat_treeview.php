<?php
	include 'db_connect1.php';
	INCLUDE 'global_config.php';
	include 'functions/ajax_functions.php';
	//Datei wird zur Navigation durch die Kategorien mit Hilfe einer Baumstruktur verwendet
	//$ziel = $target_url;
	//echo $base_file;
	//Erzeugung der Baumstruktur:
	//Beim ersten Aufruf der Seite wird nur das Wurzel-Element angezeigt.
	//  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	$KAT_ID = $kat_id;
	//  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//Ermittlung aller 'Knoten-Elemente' (Elemente, an denen in die Tiefe verzweigt wird)
	$knoten_arr[]=$kat_id;
	
	WHILE ($kat_id > '1')
	{
		$res0 = mysql_query( "SELECT parent FROM $table4 WHERE kat_id='$kat_id'");
		echo mysql_error();
		$kat_id = mysql_result($res0, $i0, 'parent');
		//echo "Kat-ID in der Funktion: ".$kat_id."<BR>";
		$knoten_arr[]=$kat_id;
	}
	$knoten_arr = array_reverse($knoten_arr);

	echo "<TABLE id='kat'>";
	
	function getElements($kat_id, $knoten_arr, $KAT_ID, $ID, $mod, $modus, $base_file)
	{
		include 'db_connect1.php';
		INCLUDE 'global_config.php';
		//include 'functions/ajax_functions.php';
		$result10 = mysql_query( "SELECT * FROM $table4 WHERE parent='$kat_id' ORDER BY kategorie");
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
				$space .="&#160;&#160;&#160;";
			}
			
			$kat_id_pos = array_search($kat_id, $knoten_arr);
			$kat_id_back = $knoten_arr[$kat_id_pos - 1];
			
			IF (in_array($kat_id, $knoten_arr))
			{
				$img = "<IMG src='../../share/images/minus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
				echo 	"<TR id='kat'>
					<TD id='kat1'>
					".$space."<a href='$ziel?kat_id=$kat_id_back&mod=$mod&pic_id=0'>".$img."</a>&#160;";
					
					echo "<SPAN style='cursor:pointer;' onClick='getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\")'>".$kategorie."</SPAN>";
					
					echo "
					</TD>
					</TR>";
				getElements($kat_id, $knoten_arr, $KAT_ID, $ID, $mod, $modus, $base_file);
			}
			ELSE
			{
				$img = "<IMG src='../../share/images/plus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
				echo 	"<TR id='kat'>
					<TD id='kat1'>
					".$space."<a href='$ziel?kat_id=$kat_id&mod=$mod&pic_id=0'>".$img."</a>&#160;";
					
					echo "<SPAN style='cursor:pointer;' onClick='getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\")'>".$kategorie."</SPAN>";
					
					echo "
					</TD>
					</TR>";
			}
		}
	}
	
	$result10 = mysql_query( "SELECT * FROM $table4 WHERE kat_id='1'");
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
			$space .="&#160;&#160;&#160;";
		}
		
		//Link für den Rücksprung erzeugen, d.h. nächst höheren Knoten aufrufen:
		$kat_id_back = array_search($kat_id, $knoten_arr);
		IF (in_array($kat_id, $knoten_arr))
		{
			$img = "<IMG src='../../share/images/minus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
			echo 	"<TR id='kat'>
				<TD id='kat1'>
				".$space."<a href='$ziel?kat_id=$kat_id_back&mod=$mod&pic_id=0'>".$img."</a>&#160;";
				
				echo "<SPAN style='cursor:pointer;' onClick='getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\")'>".$kategorie."</SPAN>";
				
				echo "
				<BR>
				</TD>
				</TR>";
			getElements($kat_id, $knoten_arr, $KAT_ID, $ID, $mod, $modus, $base_file);
		}
		ELSE
		{
			$img = "<IMG src='../../share/images/plus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
			echo 	"<TR id='kat'>
				<TD id='kat1'>
				".$space."<a href='$ziel?kat_id=$kat_id&mod=$mod&pic_id=0'>".$img."</a>&#160;";
				
				echo "<SPAN style='cursor:pointer;' onClick='getPreview(\"$KAT_ID\",\"$kat_id\",\"$mod\",0,\"$modus\",\"$base_file\")'>".$kategorie."</SPAN>";
					
				echo "
				<BR>
				</TD>
				</TR>";
		}
	}
	echo "</TABLE>";
?>