<?
	include '../../share/global_config.php';
	include $sr.'/bin/share/functions/permissions.php';
	switch ($item)
	{
		case "":
		if (hasPermission($c_username, 'adminlogin')) echo "<a class='navi' href='../../admin/admin/kategorie0.php' title='Verwaltung / Sortierung der Bildkategorien'>Kategorien</a>";
    		if (hasPermission($c_username, 'adminlogin')) echo "<a class='navi' href='adminframe.php?item=adminshowusers' title='Benutzer-Verwaltung'>Benutzer</a>";
    		if (hasPermission($c_username, 'adminlogin')) echo "<a class='navi' href='adminframe.php?item=adminshowgroups' title='Benutzergruppen-Verwaltung'>Gruppen</a>";
    		if (hasPermission($c_username, 'adminlogin')) echo "<a class='navi' href='adminframe.php?item=adminshowpermissions' title='Verwaltung der Zugangsberechtigungen'>Berechtigungen</a>";
    		//echo "<a class='navi' href='start.php'>Zur�ck</a>";
    		break;
    		
    		case "adminshowusers":
    		if (hasPermission($c_username, 'adminlogin')) echo "<a class='navi' href='adminframe.php?item=adminadduser' title='Benutzer hinzuf&uuml;gen'>Hinzuf�gen</a>";
    		echo "<a class='navi' href='adminframe.php'>Zur�ck</a>";
    		break;
    		
    		case "adminshowgroups":
    		if (hasPermission($c_username, 'adminlogin')) echo "<a class='navi' href='adminframe.php?item=adminaddusergroup' title='Gruppen hinzuf&uuml;gen'>Hinzuf�gen</a>";
    		echo "<a class='navi' href='adminframe.php'>Zur�ck</a>";
    		break;
    		
    		case "adminshowuser":
    		echo "<a class='navi' href='adminframe.php?item=adminshowusers'>Zur�ck</a>";
    		break;
    		
    		case "adminshowgroup":
    		echo "<a class='navi' href='adminframe.php?item=adminshowgroups'>Zur�ck</a>";
    		break;
    		
    		case "adminadduser":
    		echo "<a class='navi' href='adminframe.php?item=adminshowusers'>Zur�ck</a>";
    		break;
    		
    		case "adminaddusergroup":
    		echo "<a class='navi' href='adminframe.php?item=adminshowgroups'>Zur�ck</a>";
    		break;
    		
    		case "adminshowpermissions":
    		if (hasPermission($c_username, 'adminlogin')) echo "<a class='navi' href='adminframe.php?item=adminaddpermission'>Hinzuf�gen</a>";
    		echo "<a class='navi' href='adminframe.php'>Zur�ck</a>";
    		break;
    		
    		case "adminaddpermission":
    		//if (hasPermission($c_username, 'addpermission')) echo "<a class='navi' href='adminframe.php?item=adminaddpermission'>Hinzuf�gen</a>";
    		echo "<a class='navi' href='adminframe.php?item=adminshowpermissions'>Zur�ck</a>";
    		break;
    	}
  
    	echo "
    	<a class='navi' href='$inst_path/pic2base/bin/ftp_frontend/?' title='FTP-Statistik'>FTP-Statistik</a>
	<a class='navi' href='$inst_path/pic2base/log/p2b_log.php' title='pic2base-Logdatei'>p2b-Log</a>
	<a class='navi' href='$inst_path/pic2base/bin/admin/admin/md5_add.php' title='kontrolliert auf fehlende Pr&uuml;fsummen'>md5-Check</a>
	<a class='navi' href='$inst_path/pic2base/bin/admin/admin/generate_histogram0.php' title='erstellt fehlende Histogramme'>Histogramme</a>
	<a class='navi' href='$inst_path/pic2base/bin/admin/admin/generate_exifdata0.php' title='&uuml;bertr&auml;gt fehlende Meta-Daten aus den Bildern in die DB'>Meta-Daten</a>
	<a class='navi' href='$inst_path/pic2base/bin/admin/admin/protect_metadata0.php' title='Festlegung editierbarer Meta-Daten'>Meta-Protect</a>
	<a class='navi' href='$inst_path/pic2base/bin/admin/admin/compact_view0.php' title='Festlegung der Kompaktansicht der Meta-Daten'>Meta-Ansicht</a>
	<a class='navi' href='$inst_path/pic2base/bin/admin/admin/check_software0.php' title='&uuml;berpr&uuml;ft, ob erforderliche Software installiert ist'>Software-Check</a>
	<a class='navi_blind'></a>
	<a class='navi' href='$inst_path/pic2base/bin/html/start.php'>zur Startseite</a>
	<a class='navi' href='$inst_path/pic2base/bin/html/help/help1.php?page=5'>Hilfe</a>
	<a class='navi' href='$inst_path/pic2base/index.php'>Logout</a>";
?>
<!--<a class='navi' href='../start.php'>Zur�ck</a>-->

