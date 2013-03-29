<?php
IF (!$_COOKIE['uid'])
{
	include '../../share/global_config.php';
	//var_dump($sr);
  	header('Location: ../../../index.php');
}
$uid = $_COOKIE['uid'];
$columns = $_COOKIE['columns'];

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
	if (hasPermission($uid, 'editkattree', $sr)) echo "<a class='navi' href='../../admin/admin/kategorie0.php' title='Verwaltung / Sortierung der Bildkategorien'>Kategorien</a>";
    if (hasPermission($uid, 'adminlogin', $sr)) echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=adminshowusers' title='Benutzer-Verwaltung'>Benutzer</a>";
    if (hasPermission($uid, 'adminlogin', $sr)) echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=adminshowgroups' title='Benutzergruppen-Verwaltung'>Gruppen</a>";
    if (hasPermission($uid, 'adminlogin', $sr)) echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=adminshowpermissions' title='Verwaltung der Zugangsberechtigungen'>Berechtigungen</a>";
    if (hasPermission($uid, 'editlocationname', $sr)) echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=editlocationname' title='georeferenzierte Ortsnamen bearbeiten'>Ortsnamen</a>";
    break;
    	
    case "adminshowusers":
    if (hasPermission($uid, 'adminlogin', $sr)) echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=adminadduser' title='Benutzer hinzuf&uuml;gen'>Hinzuf&uuml;gen</a>";
    echo "<a class='navi' href='adminframe.php'>Zur&uuml;ck</a>";
    break;
    	
    case "adminshowgroups":
    if (hasPermission($uid, 'adminlogin', $sr)) echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=adminaddusergroup' title='Gruppen hinzuf&uuml;gen'>Hinzuf&uuml;gen</a>";
    echo "<a class='navi_blind'><a class='navi' href='adminframe.php'>Zur&uuml;ck</a>";
    break;
    	
    case "adminshowuser":
    echo "</a><a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=adminshowusers'>Zur&uuml;ck</a>";
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
    if (hasPermission($uid, 'editkattree', $sr)) echo "<a class='navi' href='../../admin/admin/kategorie0.php' title='Verwaltung / Sortierung der Bildkategorien'>Kategorien</a>";
    if (hasPermission($uid, 'adminlogin', $sr)) echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=adminshowusers' title='Benutzer-Verwaltung'>Benutzer</a>";
    if (hasPermission($uid, 'adminlogin', $sr)) echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=adminshowgroups' title='Benutzergruppen-Verwaltung'>Gruppen</a>";
    if (hasPermission($uid, 'adminlogin', $sr)) echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php'>Zur&uuml;ck</a>";
    if (hasPermission($uid, 'editlocationname', $sr)) echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=editlocationname' title='georeferenzierte Ortsnamen bearbeiten'>Ortsnamen</a>";
    break;
    	
    case "adminaddpermission":
    echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=adminshowpermissions'>Zur&uuml;ck</a>";
    break;
    
    case "editlocationname":
    if (hasPermission($uid, 'editkattree', $sr)) echo "<a class='navi' href='../../admin/admin/kategorie0.php' title='Verwaltung / Sortierung der Bildkategorien'>Kategorien</a>";
    if (hasPermission($uid, 'adminlogin', $sr)) echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=adminshowusers' title='Benutzer-Verwaltung'>Benutzer</a>";
    if (hasPermission($uid, 'adminlogin', $sr)) echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=adminshowgroups' title='Benutzergruppen-Verwaltung'>Gruppen</a>";
    if (hasPermission($uid, 'adminlogin', $sr)) echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=adminshowpermissions' title='Verwaltung der Zugangsberechtigungen'>Berechtigungen</a>";
    echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php'>Zur&uuml;ck</a>";
    break;
    
    case "admineditlocation":
    if (hasPermission($uid, 'editkattree', $sr)) echo "<a class='navi' href='../../admin/admin/kategorie0.php' title='Verwaltung / Sortierung der Bildkategorien'>Kategorien</a>";
    if (hasPermission($uid, 'adminlogin', $sr)) echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=adminshowusers' title='Benutzer-Verwaltung'>Benutzer</a>";
    if (hasPermission($uid, 'adminlogin', $sr)) echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=adminshowgroups' title='Benutzergruppen-Verwaltung'>Gruppen</a>";
    if (hasPermission($uid, 'adminlogin', $sr)) echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=adminshowpermissions' title='Verwaltung der Zugangsberechtigungen'>Berechtigungen</a>";
    echo "<a class='navi' href='$inst_path/pic2base/bin/html/admin/adminframe.php?item=editlocationname'>Zur&uuml;ck</a>";
    break;
}
  
IF($item !== 'admineditlocationnameaction')
{
    IF(hasPermission($uid, 'adminlogin', $sr))
    {
	   	echo "
	   	<a class='navi' href='$inst_path/pic2base/bin/ftp_frontend/?' title='FTP-Statistik'>FTP-Statistik</a>
		<a class='navi' href='$inst_path/pic2base/bin/admin/admin/p2b_log.php' title='pic2base-Logdatei'>p2b-Log</a>
		<a class='navi' href='$inst_path/pic2base/bin/admin/admin/db_wartung1.php' title='Datenbank-Wartung mit Doublettenpr&uuml;fung'>DB-Wartung</a>
		<a class='navi' href='$inst_path/pic2base/bin/admin/admin/db_export1.php' title='Datenbank-Export als CSV-Datei'>DB-Export</a>
		<a class='navi' href='$inst_path/pic2base/bin/admin/admin/del_pics1.php' title='Vorgemerkte Datens&auml;tze l&ouml;schen'>Bilder l&ouml;schen</a>
		<a class='navi_blind'></a>
		<a class='navi' href='$inst_path/pic2base/bin/admin/admin/protect_metadata0.php?columns=$columns' title='Festlegung editierbarer Meta-Daten'>Meta-Protect</a>
		<a class='navi' href='$inst_path/pic2base/bin/admin/admin/kompactview_metadata0.php?columns=$columns' title='Welche Meta-Daten werden in der kompakten Detailansicht gezeigt?'>Meta-View</a>
		<a class='navi' href='$inst_path/pic2base/bin/admin/admin/check_software0.php' title='&uuml;berpr&uuml;ft, ob erforderliche Software installiert ist'>Software-Check</a>";
    }
    ELSE
    {
    	echo "	
		<a class='navi_blind'></a>
		<a class='navi_blind'></a>
		<a class='navi_blind'></a>
		<a class='navi_blind'></a>
		<a class='navi_blind'></a>
		<a class='navi_blind'></a>";
    }
    
	echo "	
	<a class='navi_blind'></a>
	<a class='navi' href='$inst_path/pic2base/bin/html/start.php'>zur Startseite</a>
	<a class='navi' href='$inst_path/pic2base/bin/html/help/help1.php?page=5'>Hilfe</a>
	<a class='navi' href='$inst_path/pic2base/index.php'>Logout</a>";
}
?>

