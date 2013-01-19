<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<TITLE>ProFTPd-Fronted</TITLE>
<meta http-equiv="refresh" content="2; url=user-online.php">
<meta http-equiv="cache-control" content="no-cache">
<link rel="stylesheet" type="text/css" href="proftpd.css">

</HEAD>
<BODY>
<TABLE border="0" cellpadding="5" cellspacing="0" width="100%">
<TR><TD bgcolor="#FFC125" height="10"><CENTER>ProFTPd - Verbindungen
<div id="user-php">
<a href="" onClick="NewWindow=window.close('user-online.php')">Informationsfenster schliessen</a>
</div>

</CENTER></TD></TR>

<TR>
<TD bgcolor="#EEE9E9" id="text">

<?PHP

if(!file_exists("/opt/lampp/var/proftpd.pid"))
{
	echo "ProFTPd ist nicht gestartet!";
}
else
{
	$str = shell_exec("/opt/lampp/bin/ftpwho -v");
	$str = str_replace("\n","<BR>",$str); 
	
	if(strstr($str,"standalone"))
	{
		$str = str_replace("standalone","<CENTER>Standalone",$str);
		
		if(strstr($str,"min<BR>"))
		{
			$str = str_replace("min<BR>","min</CENTER><BR><BR>",$str);
		}
		
		if(strstr($str,"no users connected"))
		{
			$str = str_replace("no users connected","<CENTER><BR>Niemand ist mit dem Server verbunden!</CENTER>",$str);
		}
		else
		{
			$str = str_replace("client","Client",$str);
			$str = str_replace("server","Server",$str);
			$str = str_replace("location","Pfad",$str);
			$str = str_replace("Service class","",$str);
			if (strstr($str,"LIST") or strstr($str,"STOR") or strstr($str,"RETR") or strstr($str,"idle") or strstr($str,"IDLE") or strstr($str,"authenticating") or strstr($str,"DELE"))
			{
				$str = str_replace("LIST","<FONT COLOR=\"green\">Verzeichnis auflisten</FONT>",$str);
				$str = str_replace("STOR","<FONT COLOR=\"green\">Upload</FONT>",$str);
				$str = str_replace("RETR","<FONT COLOR=\"green\">Download</FONT>",$str);
				$str = str_replace("idle","<FONT COLOR=\"green\">Unt&auml;tig</FONT>",$str);
				$str = str_replace("authenticating","<FONT COLOR=\"green\">Anmelden</FONT>",$str);
				$str = str_replace("DELE","<FONT COLOR=\"green\">L&ouml;sche</FONT>",$str);
				$str = str_replace("users","Benutzer",$str);
			}
		}
		echo "$str";
	}
}
?>
</TD></TR>
</TABLE>
</BODY>
</HTML>