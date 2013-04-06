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
?>