<?php
//[Programm-Version]
$version = "0.50.2"; // rel. 02022011.1
$vom = "(03.01.2011)";

//[copyright-Vermerk in der Fusszeile]
$cr = "2006 - 2011 Logiqu";

//Zugangsdaten fuer den Datenbankzugriff (normaler User mit teilweisen Zugriffsbeschraenkungen auf DB pic2base)
$db_server = 'localhost';
$user = 'pb';
$PWD = 'pic_base';
$db = 'pic2base';

@$conn = mysql_connect($db_server,$user,$PWD);
$table1 = 'users';				//Benutzerverzeichnis
$table2 = 'pictures';			//Tabelle der Bilddaten
$table3 = 'diary';				//Tabelle der Tagebuch-Eintraege
$table4 = 'kategorien';			//Kategorie-Tabelle;
$table5 = 'meta_protect';		//Tabelle der Schreibrechte auf die exif_data-Tabelle
$table6 = 'grouppermissions';	//Gruppenrechte
$table7 = 'userpermissions';	//Benutzerrechte
$table8 = 'permissions';		//Zugriffsrechte
$table9 = 'usergroups';			//Benutzergruppen
$table10 = 'pic_kat';			//Bild-Kategorie-Zuordnung
$table11 = 'kat_lex';			//Kategorie-Lexikon
$table12 = 'locations';			//Aufnahmestandorte
$table13 = 'geo_tmp';			//wird bei der automat. Georeferenzierung verwendet
$table14 = 'meta_data';			//Metadaten der Bilder
$table15 = 'tmp_tree';			//wird bei der Kategoriebaum-Umstrukturierung verwendet
$table16 = 'pfade';				//speichert die Pfade zu den Hilfsprogrammen
$table17 = 'fileformats';		//enthaelt die von ImageMagick unterstuetzten Dateiformate

$myhost=$db_server;
$myuser=$user;
$mypw=$PWD;
$mydb = $db;

@$database = mysql_pconnect($myhost,$myuser,$mypw);
if (!$database) 
{
	//echo "##ERROR##, could not connect to host $myhost<br>\n";
	//echo "##MySQL ERRNO: " . mysql_errno() . "<br>\n";
	//echo "##MySQL ERROR: " . mysql_error() . "<br>\n";
	return;
}
if (!mysql_select_db($mydb)) 
{
	//echo "##ERROR##, could not select database $mydb<br>\n";
	//echo "##MySQL ERRNO: " . mysql_errno() . "<br>\n";
	//echo "##MySQL ERROR: " . mysql_error() . "<br>\n";
	return;
}

mysql_query("SET CHARACTER SET latin1");
//Encryption-Key fuer PWD-Ver-/Entschluesselung
$key = '0815';
?>
