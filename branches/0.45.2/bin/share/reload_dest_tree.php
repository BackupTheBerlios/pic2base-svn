<?php
IF (!$_COOKIE['login'])
{
include '../share/global_config.php';
//var_dump($sr);
  header('Location: ../../index.php');
}

//echo "Quell-Kat-ID: ".$kat_id_s."<BR>";
include 'db_connect1.php';
INCLUDE 'global_config.php';

// fuer register_globals = off
if(array_key_exists('kat_id_s',$_GET))
{
	$kat_id_s = $_GET['kat_id_s']; 
}
else
{
	$kat_id_s = 0;
}

//Es muss sichergestellt werden, dass als Zielkategorie keine Kategorie unterhalb der Quellkategorie gewaehlt werden kann, denn diese wird ja gel�scht! $child_arr enth�lt alle 'verbotenen' Kategorien

//Bestimmung aller Unterkategorien der gewaehlten Quell-Kategorie:

$res1 = mysql_query("SELECT max(level) FROM $table4");
$max_level = mysql_result($res1, isset($i1), 'max(level)');
//echo "max. Level: ".$max_level."<BR>";
$result2 = mysql_query("SELECT * FROM $table4 WHERE parent = '$kat_id_s'");
$num2 = mysql_num_rows($result2);
unset($child_arr);
$child_arr[] = $kat_id_s;
IF($num2 > '0')
{
	$curr_level = mysql_result($result2, isset($i2), 'level');
	WHILE($curr_level <= $max_level)
	{
		FOREACH($child_arr AS $child)
		{
			$result3 = mysql_query("SELECT * FROM $table4 WHERE parent = '$child' AND level = '$curr_level'");
			$num3 = mysql_num_rows($result3);
			IF($num3 > '0')
			{
				FOR($i3='0'; $i3<$num3; $i3++)
				{
					$child_arr[] = mysql_result($result3, $i3, 'kat_id');	
				}
			}
		}
		$curr_level++;
	}
}
//Kontrolle:
/*
FOREACH($child_arr AS $child)
{
	echo $child."<BR>";
}
*/
//Erzeugung der Baumstruktur fuer Destination-Kategorie:
//Beim ersten Aufruf der Seite wird nur das Wurzel-Element angezeigt.
//  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// fuer register_globals = off
if(array_key_exists('kat_id_d',$_GET))
{
	$kat_id_d = $_GET['kat_id_d']; 
}
else
{
	//$kat_id_d = 0;
}

$KAT_ID_D = $kat_id_d;		//kat_id_d - Kategorie-ID der Destination
//  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Ermittlung aller 'Knoten-Elemente' (Elemente, an denen in die Tiefe verzweigt wird)
$knoten_arr_d[]=$kat_id_d;

WHILE ($kat_id_d > '1')
{
	$res0 = mysql_query("SELECT parent FROM $table4 WHERE kat_id='$kat_id_d'");
	echo mysql_error();
	$kat_id_d = mysql_result($res0, isset($i0), 'parent');
	//echo "Kat-ID in der Funktion: ".$kat_id_d."<BR>";
	$knoten_arr_d[]=$kat_id_d;
}
$knoten_arr_d = array_reverse($knoten_arr_d);
	
	echo "<TABLE id='kat'>
		<TR>
		<TD>Ziel-Kategorie</TD>
		</TR>";
	
	function getElementsD($kat_id_d, $knoten_arr_d, $KAT_ID_D, $child_arr, $kat_id_s)
	{
		include 'db_connect1.php';
		INCLUDE 'global_config.php';
		$result10 = mysql_query("SELECT * FROM $table4 WHERE parent='$kat_id_d' ORDER BY kategorie");
		$num10 = mysql_num_rows($result10);
		FOR ($i10=0; $i10<$num10; $i10++)
		{
			$kategorie = htmlentities(mysql_result($result10, $i10, 'kategorie'));
			$parent = mysql_result($result10, $i10, 'parent');
			$level = mysql_result($result10, $i10, 'level');
			$kat_id_d = mysql_result($result10, $i10, 'kat_id');
			IF(in_array($kat_id_d, $child_arr))
			{
				$status = 'disabled';
			}
			ELSE
			{
				$status = '';
			}
			$space='';
			FOR ($N=0; $N<$level; $N++)
			{
				$space .="&#160;&#160;&#160;";
			}
			
			$kat_id_d_pos = array_search($kat_id_d, $knoten_arr_d);
			if($kat_id_d_pos > 0 )
			{
				$kat_id_d_back = $knoten_arr_d[$kat_id_d_pos - 1];
			}
	
			IF (in_array($kat_id_d, $knoten_arr_d))
			{
				
				//echo $kat_id_d_back;
				$img = "<IMG src='../../share/images/minus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
				echo 	"<TR id='kat'>
					<TD id='kat1'>
					".$space."<span style='cursor:pointer'; onClick='reloadDestTree(\"$kat_id_d_back\", \"$kat_id_s\")'>".$img."</span>&#160;".$kategorie."
					</TD>";
					
					IF($kat_id_d !== '1')
					{
						echo "
						<TD id='kat2'><input type='radio' name='kat_dest' value=$kat_id_d title=$kat_id_d $status>
						</TD>";
					}
					ELSE
					{
						echo "<TD id='kat2'><BR></TD>";
					}
					
					echo "</TR>";
				getElementsD($kat_id_d, $knoten_arr_d, $KAT_ID_D, $child_arr, $kat_id_s);
			}
			ELSE
			{
				$img = "<IMG src='../../share/images/plus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
				echo 	"<TR id='kat'>
					<TD id='kat1'>
					".$space."<span style='cursor:pointer'; onClick='reloadDestTree(\"$kat_id_d\", \"$kat_id_s\")'>".$img."</span>&#160;".$kategorie."
					</TD>";
					
					IF($kat_id_d !== '1')
					{
						echo "
						<TD id='kat2'><input type='radio' name='kat_dest' value=$kat_id_d title=$kat_id_d $status>
						</TD>";
					}
					ELSE
					{
						echo "<TD id='kat2'><BR></TD>";
					}
					
					echo "</TR>";
			}
		}
	}
	
	$result10 = mysql_query("SELECT * FROM $table4 WHERE kat_id='1'");
	$num10 = mysql_num_rows($result10);
	FOR ($i10=0; $i10<$num10; $i10++)
	{
		$kategorie = htmlentities(mysql_result($result10, $i10, 'kategorie'));
		$parent = mysql_result($result10, $i10, 'parent');
		$level = mysql_result($result10, $i10, 'level');
		$kat_id_d = mysql_result($result10, $i10, 'kat_id');
		IF(in_array($kat_id_d, $child_arr))
		{
			$status = 'disabled';
		}
		ELSE
		{
			$status = '';
		}
		
		$space='';
		//echo $level;
		FOR ($N=0; $N<$level; $N++)
		{
			$space .="&#160;&#160;&#160;";
		}
		
		//Link fuer den Ruecksprung erzeugen, d.h. naechst hoeheren Knoten aufrufen:
		$kat_id_d_back = array_search($kat_id_d, $knoten_arr_d);
		IF (in_array($kat_id_d, $knoten_arr_d))
		{
			
			//echo "Space: ".$space."<BR>";
			//echo $kat_id_d_back;
			$img = "<IMG src='../../share/images/minus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
			echo 	"<TR id='kat'>
				<TD id='kat1'>
				".$space."<span style='cursor:pointer'; onClick='reloadDestTree(\"$kat_id_d_back\", \"$kat_id_s\")'>".$img."</span>&#160;".$kategorie."
				</TD>";
					
				IF($kat_id_d !== '1')
				{
					echo "
					<TD id='kat2'><input type='radio' name='kat_dest' value=$kat_id_d title=$kat_id_d $status>
					</TD>";
				}
				ELSE
				{
					echo "<TD id='kat2'><BR></TD>";
				}
				
				echo "</TR>";
			getElementsD($kat_id_d, $knoten_arr_d, $KAT_ID_D, $child_arr, $kat_id_s);
		}
		ELSE
		{
			//echo "Space: ".$space."|<BR>";
			$img = "<IMG src='../../share/images/plus.gif' width='11' height='11' hspace='0' vspace='0' border='0'>";
			echo 	"<TR id='kat'>
				<TD id='kat1'>
				".$space."<span style='cursor:pointer'; onClick='reloadDestTree(\"$kat_id_d\", \"$kat_id_s\")'>".$img."</span>&#160;".$kategorie."
				</TD>";
					
				IF($kat_id_d !== '1')
				{
					echo "
					<TD id='kat2'><input type='radio' name='kat_dest' value=$kat_id_d title=$kat_id_d $status>
					</TD>";
				}
				ELSE
				{
					echo "<TD id='kat2'><BR></TD>";
				}
					
				echo "</TR>";
		}
	}
	echo "</TABLE>";


?>