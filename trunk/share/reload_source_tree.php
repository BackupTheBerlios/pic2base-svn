<?php
IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
	header('Location: ../../index.php');
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>pic2base - Startseite</TITLE>
	<META NAME="GENERATOR" CONTENT="eclipse">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel=stylesheet type="text/css" href='../../css/format2.css'>
	<link rel="shortcut icon" href="../../share/images/favicon.ico">
</HEAD>

<BODY LANG="de-DE" scroll = "auto">

<?php
setlocale(LC_CTYPE, 'de_DE');
include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
/*
include 'db_connect1.php';
include 'global_config.php';
*/
// fuer register_globals = off
if(array_key_exists('kat_id_s',$_GET))
{
	$kat_id_s = $_GET['kat_id_s']; 
}
else
{
	$kat_id_s = 0;
}
//Erzeugung der Baumstruktur fuer Source-Kategorie:
//  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$KAT_ID_S = $kat_id_s;		//kat_id_s - Kategorie-ID der Source
//  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Ermittlung aller 'Knoten-Elemente' (Elemente, an denen in die Tiefe verzweigt wird)
$knoten_arr_s[]=$kat_id_s;
$kat_id_s_d = $kat_id_s;	//Die Source-Kat-ID muss unveraendert bis in den Destination-Bereich des Skripts durchgereicht werden, deshalb hier Umbenennung!
WHILE ($kat_id_s > '1')
{
	$res0 = mysql_query( "SELECT parent FROM $table4 WHERE kat_id='$kat_id_s'");
	echo mysql_error();
	$kat_id_s = mysql_result($res0, isset($i0), 'parent');
	$knoten_arr_s[]=$kat_id_s;
}
$knoten_arr_s = array_reverse($knoten_arr_s);

echo "<center>
<TABLE class='kat'>";

	function getElementsS($kat_id_s, $knoten_arr_s, $KAT_ID_S)
	{
		include 'db_connect1.php';
		INCLUDE 'global_config.php';
		include_once $sr.'/bin/share/functions/ajax_functions.php';
		$result10 = mysql_query( "SELECT * FROM $table4 WHERE parent='$kat_id_s' ORDER BY kategorie");
		$num10 = mysql_num_rows($result10);
		FOR ($i10=0; $i10<$num10; $i10++)
		{
			$kategorie = mysql_result($result10, $i10, 'kategorie');
			$parent = mysql_result($result10, $i10, 'parent');
			$level = mysql_result($result10, $i10, 'level');
			$kat_id_s = mysql_result($result10, $i10, 'kat_id');
			$space='';
			FOR ($N=0; $N<$level; $N++)
			{
				$space .="&#160;&#160;&#160;";
			}
			
			$kat_id_s_pos = array_search($kat_id_s, $knoten_arr_s);
			if($kat_id_s_pos > 0 )
			{
				$kat_id_s_back = $knoten_arr_s[$kat_id_s_pos - 1];
			}
			IF (in_array($kat_id_s, $knoten_arr_s))
			{
				//echo $kat_id_s_back;
				$img = "<IMG src='../../share/images/minus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
				echo 	"<TR class='kat'>
					<TD class='kat1'>
					".$space."<span style='cursor:pointer;' onClick='reloadSourceTree(\"$kat_id_s_back\")'>".$img."</span>&#160;".$kategorie."
					</TD>";
					
					IF($kat_id_s !== '1')
					{
						echo "
						<TD class='kat2'><input type='radio' name='kat_source' value=$kat_id_s onChange='reloadDestTree(1, \"$kat_id_s\")'>
						</TD>";
					}
					ELSE
					{
						echo "<TD class='kat2'><BR></TD>";
					}
					
					echo "</TR>";
				getElementsS($kat_id_s, $knoten_arr_s, $KAT_ID_S);
			}
			ELSE
			{
				$img = "<IMG src='../../share/images/plus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
				echo 	"<TR class='kat'>
					<TD class='kat1'>
					".$space."<span style='cursor:pointer;' onClick='reloadSourceTree(\"$kat_id_s\")'>".$img."</span>&#160;".$kategorie."
					</TD>";
					
					IF($kat_id_s !== '1')
					{
						echo "
						<TD class='kat2'><input type='radio' name='kat_source' value=$kat_id_s onChange='reloadDestTree(1, \"$kat_id_s\")'>
						</TD>";
					}
					ELSE
					{
						echo "<TD class='kat2'><BR></TD>";
					}
					
					echo "</TR>";
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
		$kat_id_s = mysql_result($result10, $i10, 'kat_id');
		$space='';
		//echo $level;
		FOR ($N=0; $N<$level; $N++)
		{
			$space .="&#160;&#160;&#160;";
		}
		
		//Link fuer den Ruecksprung erzeugen, d.h. naechst hoeheren Knoten aufrufen:
		$kat_id_s_back = array_search($kat_id_s, $knoten_arr_s);
		IF (in_array($kat_id_s, $knoten_arr_s))
		{
			
			//echo "Space: ".$space."<BR>";
			//echo $kat_id_s_back;
			$img = "<IMG src='../../share/images/minus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
			echo 	"<TR class='kat'>
				<TD class='kat1'>
				".$space."<span style='cursor:pointer;' onClick='reloadSourceTree(\"$kat_id_s_back\")'>".$img."</span>&#160;".$kategorie."
				</TD>";
					
				IF($kat_id_s !== '1')
				{
					echo "
					<TD class='kat2'><input type='radio' name='kat_source' value=$kat_id_s title=$kat_id_s onChange='reloadDestTree(1, \"$kat_id_s\")'>
					</TD>";
				}
				ELSE
				{
					echo "<TD class='kat2'><BR></TD>";
				}
				
				echo "</TR>";
			getElementsS($kat_id_s, $knoten_arr_s, $KAT_ID_S);
		}
		ELSE
		{
			//echo "Space: ".$space."|<BR>";
			$img = "<IMG src='../../share/images/plus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
			echo 	"<TR class='kat'>
				<TD class='kat1'>
				".$space."<span style='cursor:pointer;' onClick='reloadSourceTree(\"$kat_id_s\")'>".$img."</span>&#160;".$kategorie."
				</TD>";
					
				IF($kat_id_s !== '1')
				{
					echo "
					<TD class='kat2'><input type='radio' name='kat_source' value=$kat_id_s title=$kat_id_s onChange='reloadDestTree(1, \"$kat_id_s\")'>
					</TD>";
				}
				ELSE
				{
					echo "<TD class='kat2'><BR></TD>";
				}
				echo "</TR>";
		}
	}

echo "</TABLE>
</center>";
?>
</BODY>
</HTML>