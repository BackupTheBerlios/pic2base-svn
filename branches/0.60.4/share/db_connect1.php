<?php
//[Programm-Version]
$version = "0.60.4"; // 
$rel = "11062012.1";
$vom = "(11.06.2012)";

//[copyright-Vermerk in der Fusszeile]
$cr = "<FONT COLOR=RGB(46,46,46)>______</FONT>2006 - 2012";

//Zugangsdaten fuer den Datenbankzugriff (normaler User mit teilweisen Zugriffsbeschraenkungen auf DB pic2base)
$db_server = 'localhost';
$user = 'pb';
$PWD = 'pic_base';
$db = 'pic2base';

@$conn = mysql_connect($db_server,$user,$PWD);
$table1 = 'users';					//Benutzerverzeichnis
$table2 = 'pictures';				//Tabelle der Bilddaten
$table3 = 'diary';					//Tabelle der Tagebuch-Eintraege
$table4 = 'kategorien';				//Kategorie-Tabelle;
$table5 = 'meta_protect';			//Tabelle der Schreib- und Leserechte auf die Meta-Daten der Bilder
$table6 = 'grouppermissions';		//Gruppenrechte
$table7 = 'userpermissions';		//Benutzerrechte
$table8 = 'permissions';			//Zugriffsrechte
$table9 = 'usergroups';				//Benutzergruppen
$table10 = 'pic_kat';				//Bild-Kategorie-Zuordnung
$table11 = 'kat_lex';				//Kategorie-Lexikon
$table12 = 'locations';				//Aufnahmestandorte
$table13 = 'geo_tmp';				//wird bei der automat. Georeferenzierung verwendet
$table14 = 'meta_data';				//Metadaten der Bilder, wird ab V 0.60.0 nicht mehr verwendet
$table15 = 'tmp_tree';				//wird bei der Kategoriebaum-Umstrukturierung verwendet
$table16 = 'pfade';					//speichert die Pfade zu den Hilfsprogrammen
$table17 = 'fileformats';			//enthaelt die von ImageMagick unterstuetzten Dateiformate
$table18 = 'data_logger';			//speichert unterstuetzte Logger-Typen
$table19 = 'timezone';				//Tabelle der Zeitzonen
$table20 = 'tag_trans';				//Uebersetzungen der Metadaten
$table21 = 'doubletten';			//temporaer mit Doubletten belegt
$table22 = 'IVE_V_pic_kat_dubls';	//temporaer bei der DB-Wartung verwendet

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
//echo phpinfo();
?>