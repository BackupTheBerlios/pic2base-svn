<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>...speichere neue Kollektion</title>
  <meta name="GENERATOR" content="eclipse">
  <meta name="AUTHOR" content="k. henneberg">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel=stylesheet type='text/css' href='../css/format2.css'>
  <link rel="shortcut icon" href="../share/images/favicon.ico">
  <script language="JavaScript" src="../share/functions/resize_elements.js"></script>
  <script language="JavaScript" src="../share/functions/jquery-1.8.2.min.js"></script>
  <script language="JavaScript">
  	jQuery.noConflict();
	jQuery(document).ready(checkWindowSize);
	jQuery(window).resize(checkWindowSize); 
  </script>
</head>

<body>

<?php
IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../index.php');
}
else
{
	$uid = $_COOKIE['uid'];
}

if(array_key_exists('coll_name', $_POST))
{
	$coll_name = $_POST['coll_name'];
}
else
{
	$coll_name = "";
}

if(array_key_exists('coll_description', $_POST))
{
	$coll_description = $_POST['coll_description'];
}
else
{
	$coll_description = "";
}

/*
 * Project: pic2base
 * File: save_new_collection.php
 * Neue Kollektion speichern
 * Copyright (c) 2013 Klaus Henneberg
 *
 * Project owner:
 * Klaus Henneberg
 * Finkenweg 18
 * 38889 Blankenburg, BRD
 *
 * All files of this project are licensed under the terms of the Open Software License
 * http://www.opensource.org/licenses/osl-2.1.php
 */

include 'global_config.php';
include $sr.'/bin/share/db_connect1.php';
include $sr.'/bin/css/initial_layout_settings.php';

echo "
<DIV Class='klein'>
	<div id='page'>
	
		<div id='head'>
			pic2base :: Anlage einer neuen Kollektion
		</div>
		
		<div id='navi'>
			<div class='menucontainer'>
			
			</div>
		</div>
		
		<div id='content'>
			<fieldset style='background-color:none; margin-top:10px;'>
			<legend style='color:blue; font-weight:bold;'>Hinweise</legend>
			<div id='scrollbox0' style='overflow-y:scroll;'>";
				//echo "Name: ".$coll_name.", Beschreibung: ".$coll_description."<BR>";

				if($coll_name == "" OR $coll_description == "")
				{
					echo "<center><p style='margin-top:50px;'>Sie m&uuml;ssen den Kollektions-Namen und die Beschreibung eintragen!</p></center>";
					?>
					<script type="text/javascript">
					alert("Sie müssen den Kollektions-Namen und die Beschreibung eintragen!");
					history.back();
					</script>
					<?php
				}
				else
				{
					$create_date = date('Y-m-d H:i:s', time());
					//echo $create_date;
					$result1 = mysql_query("INSERT INTO $table24 (coll_name, coll_description, coll_owner, created, locked) VALUES ('$coll_name', '$coll_description', '$uid', '$create_date', '1')");
					
					if(mysql_error() == "")
					{
						echo "<center><p style='margin-top:50px;'>Speicherung l&auml;uft...</p></center>";
						echo "<meta http-equiv='refresh', content='1; URL=../html/edit/edit_collection.php'>";
					}
					else
					{
						echo "<center><p style='margin-top:50px;'>Es trat ein Fehler auf.</p><BR><BR>";
						echo mysql_error();
						echo "</center>";
					}
				}	
			echo "
			</div>
			</fieldset>
		</div>
		
		<div id='foot'>
			<A style='position:relative; top:8px; left:10px; font-size:10px; color:#eeeeee;' HREF='http://www.pic2base.de' target='blank'>www.pic2base.de</A>
		</div>
	
	</div>
</DIV>";
?>

</body>
</html>





<?php
/*
IF (!$_COOKIE['uid'])
{
	include '../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../index.php');
}
else
{
	$uid = $_COOKIE['uid'];
}

if(array_key_exists('coll_name', $_POST))
{
	$coll_name = $_POST['coll_name'];
}
else
{
	$coll_name = "";
}

if(array_key_exists('coll_description', $_POST))
{
	$coll_description = $_POST['coll_description'];
}
else
{
	$coll_description = "";
}

//echo "Name: ".$coll_name.", Beschreibung: ".$coll_description."<BR>";

if($coll_name == "" OR $coll_description == "")
{
	echo "<center><p style='margin-top:50px;'>Sie m&uuml;ssen den Kollektions-Namen und die Beschreibung eintragen!</p></center>";
	?>
	<script type="text/javascript">
	alert("Sie müssen den Kollektions-Namen und die Beschreibung eintragen!");
	history.back();
	</script>
	<?php
}
else
{
	include 'global_config.php';
	include $sr.'/bin/share/db_connect1.php';
	$create_date = date('Y-m-d H:i:s', time());
	//echo $create_date;
	$result1 = mysql_query("INSERT INTO $table24 (coll_name, coll_description, coll_owner, created, locked) VALUES ('$coll_name', '$coll_description', '$uid', '$create_date', '1')");
	
	if(mysql_error() == "")
	{
		echo "<meta http-equiv='refresh', content='0; URL=../html/edit/edit_collection.php'>";
	}
	else
	{
		echo "Es trat ein Fehler auf.<BR><BR>";
		echo mysql_error();
	}
}
*/
?>