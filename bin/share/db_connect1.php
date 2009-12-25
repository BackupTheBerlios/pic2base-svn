<?

//[Programm-Version]
$version = "0.41 (15.05.2009)";

//[copyright-Vermerk in der Fußzeile]
$cr = "2006 - 2009 Logiqu";

//Zugangsdaten für den Datenbankzugriff (normaler User mit teilwesen Zugriffsbeschränkungen auf DB pic2base)
$db_server='localhost';
$user='pb';
$PWD='pic_base';
$db = 'pic2base';

@$conn = mysql_connect($db_server,$user,$PWD);
$table1 = 'users';		//Benutzerverzeichnis
$table2 = 'pictures';		//Tabelle der Bilddaten
$table3 = 'diary';		//Tabelle der Tagebuch-Eintraege
$table4 = 'kategorien';		//Kategorie-Tabelle;
$table5 = 'meta_protect';	//Tabelle der Schreibrechte auf die exif_data-Tabelle
$table6 = 'grouppermissions';
$table7 = 'userpermissions';
$table8 = 'permissions';	//Zugriffsrechte
$table9 = 'usergroups';
$table10 = 'pic_kat';		//Bild-Kategorie-Zuordnung
$table11 = 'kat_lex';		//Kategorie-Lexikon
$table12 = 'locations';		//Aufnahmestandorte
$table13 = 'geo_tmp';		//wird bei der automat. Georeferenzierung verwendet
$table14 = 'meta_data';		//Metadaten der Bilder
$table15 = 'tmp_tree';		//wird bei der Kategoriebaum-Umstrukturierung verwendet

//Für Ajax-Funktionalität (aus Beispielprojekt kopiert):
//   ******************************************************************************************************
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
//   ******************************************************************************************************
//Encryption-Key für PWD-Ver-/Entschlüsselung
$key = '0815';
?>
