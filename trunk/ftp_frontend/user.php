<div class="rbroundbox">
<div class="rbtop"><div></div></div>
<div class="rbcontent">

<?PHP
error_reporting(E_ALL);


if(!isset($_GET['user'])) $_GET['user'] = "";


switch($_GET['user'])
	{
	default:
	
	?>
<FORM method="POST" action="?action=user&amp;user=user2add">
<TABLE id="text">
<TR><TD>Username: </TD><TD><INPUT type="text" maxlength="12" name="username" size="20"></TD></TR>
<TR><TD>Passwort: </TD><TD><INPUT type="password" maxlength="12" name="passwort1" size="20"></TD></TR>
<TR><TD>Passwort: </TD><TD><INPUT type="password" maxlength="12" name="passwort2" size="20"></TD></TR>
<TR><TD>Pfad: </TD><TD><INPUT type="input" name="pfad" size="20" value="/opt/lampp/htdocs/"></TD></TR>
<TR><TD>Aktiv:</TD><TD><SELECT name="aktive" size="1">
<OPTION value="1">Aktiv</OPTION>
<OPTION value="0">Inaktiv</OPTION>
</SELECT>
</TD>			
</TR>
<TR><TD></TD>
<TD><INPUT type="submit" name="Submit" value="Submit"></TD></TR>
</TABLE>
<br><br>
Der Username muss mindestens 4 und darf maximal 12 Zeichen lang sein.<br>
Das Gleich gilt fuer das Passwort nur mit mindestens 6 Zeichen!<br>
Der Pfad muss existieren und die benoetigten Rechte haben!<br>
Aktiv/Inaktiv legt fest ob der User sich einloggen darf oder nicht.
<br><br>
</FORM>
	<?PHP
	break;

	case showuser:
		include('config.inc.php');
		include('function.php');
		mysql_connect($sql_server, $sql_user, $sql_pw) or die ("DB-Fehler-Nummer" .mysql_errno(). "|| Meldung: ". mysql_error()); 
		mysql_select_db($sql_db) or die ("DB-Fehler-Nummer" .mysql_errno(). "|| Meldung: ". mysql_error());
		$user = get_user();
		echo "<TABLE width=\"300\" align=\"center\" border=\"0\"><TR><TD valign=\"top\">
		<span id=\"text\">W&auml;hle den User aus:<br><br>\n";
		if(isset($_GET['del_user']))	
		{	
			foreach ($user as $value) 
			{
				echo "<a href=\"?action=user&amp;user=deluser&amp;user_to_del=$value\">$value</A>\n ";
			}
			echo "<br><br><br><B>Es folgt keine weitere Sicherheitsabfrage ob der User wirklich gel&ouml;scht werden soll!</B><br><br>
			Das l&ouml;schen eines User bewirkt auch das der von ihm verursachte Traffic nicht mehr mit angezeigt wird.<br><br>
			</span>";
		}
		else
		{
			foreach ($user as $value) 
			{
				echo "<a href=\"?action=user&amp;user=detailuser&amp;detailuser=$value\">$value</A> \n";
			}
		}
		
		echo "</TD></TR></TABLE>\n";
		mysql_close();
	break;
	
// ----------------------------------------------------------

	case detailuser:
	include('config.inc.php');
	include('function.php');
	mysql_connect($sql_server, $sql_user, $sql_pw) or die ("DB-Fehler-Nummer" .mysql_errno(). "|| Meldung: ". mysql_error()); 
	mysql_select_db($sql_db) or die ("DB-Fehler-Nummer" .mysql_errno(). "|| Meldung: ". mysql_error());	
	$info = user_detail($_GET['detailuser']);
	$info[4] = number_format(round($info[4],2) /1024 / 1024,2,".",","); 
	$info[5] = number_format(round($info[5],2) /1024 / 1024,2,".",","); 
	$newstatus = "";
	$text = "";
	if($info[2] == "1") 
	{
		$newstatus = "0";
		$text = "Account Sperren";
	}
	else
	{
		$newstatus = "1";
		$text = "Account Freischalten";
	}
	?>
	<TABLE id="text">
	<TR>
	<TD>Name:</TD><TD><?=$info[0]?></TD>
	</TR>
	<TR>
	<TD>Homedir:</TD><TD><?=$info[1]?></TD>
	</TR>
	<TR>
	<TD>Aktive:</TD><TD><?=$info[2]?></TD>
	</TR>
	
	<TR>
	<TD>Zuletzt eingelogt:</TD><TD><?=$info[3]?></TD>
	</TR>
	
	<TR>
	<TD>Logins:</TD><TD><?=$info[6]?></TD>
	</TR>
		
	<TR>
	<TD>Rundtrgeladen:</TD><TD><?=$info[4]?> Mb <img src="img/down.gif" alt="Down"></TD>
	</TR>
	<TR>
	<TD>Hochgeladen:</TD><TD><?=$info[5]?> Mb <img src="img/up.gif" alt="UP"></TD>
	</TR>
	</TABLE>
	<br><br>
	<SPAN>
	<a href="?action=user&amp;user=status&amp;change_status=<?=$newstatus?>&amp;username=<?=$info[0]?>"><?=$text?></a><BR>
	<a href="?action=logs&amp;logs=transfer&amp;userlog=<?=$info[0]?>">Logdateien einsehen</a><BR>
	</SPAN>
	<?PHP
	mysql_close();
	break;

// ----------------------------------------------------------	

	case status:
	include('config.inc.php');
	mysql_connect($sql_server, $sql_user, $sql_pw) or die ("DB-Fehler-Nummer" .mysql_errno(). "|| Meldung: ". mysql_error()); 
	mysql_select_db($sql_db) or die ("DB-Fehler-Nummer" .mysql_errno(). "|| Meldung: ". mysql_error());
	
	MYSQL_QUERY("UPDATE ftp_users SET enabled = '".$_GET['change_status']."' WHERE username = '".$_GET['username']."'") or die
	("DB-Fehler-Nummer" .mysql_errno(). "|| Meldung: ". mysql_error());
	
	echo "<span id=\"text\">Status wurde ge&auml;ndert!<BR><BR><a href=\"?\">Weiter... </A></span>";
	mysql_close();
	break;

}

?>
</div><!-- /rbcontent -->
<div class="rbbot"><div></div></div>
</div><!-- /rbroundbox -->



