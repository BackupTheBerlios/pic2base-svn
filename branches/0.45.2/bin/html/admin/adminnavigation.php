<?php
IF (!$_COOKIE['login'])
{
include '../../share/global_config.php';
//var_dump($sr);
  header('Location: ../../../index.php');
}

	if(array_key_exists('item',$_GET))
	{
		$item = $_GET['item']; 
	}
	else
	{
		$item = '';
	}
	
	include '../../share/global_config.php';
	include $sr.'/bin/share/functions/permissions.php';
	switch ($item)
	{
		case "":
		if (hasPermission($c_username, 'editkattree')) echo "<a class='navi' href='../../admin/admin/kategorie0.php' title='Verwaltung / Sortierung der Bildkategorien'>Kategorien</a>";
    		if (hasPermission($c_username, 'adminlogin')) echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=adminshowusers' title='Benutzer-Verwaltung'>Benutzer</a>";
    		if (hasPermission($c_username, 'adminlogin')) echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=adminshowgroups' title='Benutzergruppen-Verwaltung'>Gruppen</a>";
    		if (hasPermission($c_username, 'adminlogin')) echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=adminshowpermissions' title='Verwaltung der Zugangsberechtigungen'>Berechtigungen</a>";
    		//echo "<a class='navi' href='start.php'>Zur&uuml;ck</a>";
    		break;
    		
    		case "adminshowusers":
    		if (hasPermission($c_username, 'adminlogin')) echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=adminadduser' title='Benutzer hinzuf&uuml;gen'>Hinzuf&uuml;gen</a>";
    		echo "<a class='navi' href='adminframe.php'>Zur&uuml;ck</a>";
    		break;
    		
    		case "adminshowgroups":
    		if (hasPermission($c_username, 'adminlogin')) echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=adminaddusergroup' title='Gruppen hinzuf&uuml;gen'>Hinzuf&uuml;gen</a>";
    		echo "<a class='navi' href='adminframe.php'>Zur&uuml;ck</a>";
    		break;
    		
    		case "adminshowuser":
    		echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=adminshowusers'>Zur&uuml;ck</a>";
    		break;
    		
    		case "adminshowgroup":
    		echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=adminshowgroups'>Zur&uuml;ck</a>";
    		break;
    		
    		case "adminadduser":
    		echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=adminshowusers'>Zur&uuml;ck</a>";
    		break;
    		
    		case "adminaddusergroup":
    		echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=adminshowgroups'>Zur&uuml;ck</a>";
    		break;
    		
    		case "adminshowpermissions":
    		if (hasPermission($c_username, 'adminlogin')) echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=adminaddpermission'>Hinzuf&uuml;gen</a>";
    		echo "<a class='navi' href='adminframe.php'>Zur&uuml;ck</a>";
    		break;
    		
    		case "adminaddpermission":
    		//if (hasPermission($c_username, 'addpermission')) echo "<a class='navi' href='adminframe.php?item=adminaddpermission'>Hinzuf&uuml;gen</a>";
    		echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=adminshowpermissions'>Zur&uuml;ck</a>";
    		break;
    	}
  
    	echo "
    	<a class='navi' href='$inst_path/pic2base/bin/ftp_frontend/?' title='FTP-Statistik'>FTP-Statistik</a>
	<a class='navi' href='$inst_path/pic2base/log/p2b_log.php' title='pic2base-Logdatei'>p2b-Log</a>
	<!--<a class='navi' href='$inst_path/pic2base/bin/admin/admin/md5_add.php' title='kontrolliert auf fehlende Pr&uuml;fsummen'>md5-Check</a>-->
	<a class='navi' href='$inst_path/pic2base/bin/admin/admin/generate_histogram0.php' title='erstellt fehlende Histogramme'>Histogramme</a>
	<a class='navi' href='$inst_path/pic2base/bin/admin/admin/generate_exifdata0.php' title='&uuml;bertr&auml;gt fehlende Meta-Daten aus den Bildern in die DB'>Meta-Daten</a>
	<a class='navi' href='$inst_path/pic2base/bin/admin/admin/protect_metadata0.php' title='Festlegung editierbarer Meta-Daten'>Meta-Protect</a>
	<a class='navi' href='$inst_path/pic2base/bin/admin/admin/kompactview_metadata0.php' title='Welche Meta-Daten werden in der kompakten Detailansicht gezeigt?'>Meta-View</a>
	<a class='navi' href='$inst_path/pic2base/bin/admin/admin/check_software0.php' title='&uuml;berpr&uuml;ft, ob erforderliche Software installiert ist'>Software-Check</a>
	<a class='navi_blind'></a>
	<a class='navi' href='$inst_path/pic2base/bin/html/start.php'>zur Startseite</a>
	<a class='navi' href='$inst_path/pic2base/bin/html/help/help1.php?page=5'>Hilfe</a>
	<a class='navi' href='$inst_path/pic2base/index.php'>Logout</a>";
?>
<!--<a class='navi' href='../start.php'>Zur&uuml;ck</a>-->

