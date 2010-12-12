<!-- Copyright 2005 by Michael Kremsier, webmaster@dj-dhg.de, GPL-licensed -->

<TABLE border="0" cellpadding="2" cellspacing="2" id="tab1">
	<TR>
		<TD>&#160;Username:&#160;</TD>
		<TD>&#160;Datei:&#160;</TD>
		<TD>&#160;Gr&ouml;sse in b:&#160;</TD>
		<TD>&#160;Host:&#160;</TD>
		<TD>&#160;IP:&#160;</TD>
		<TD>&#160;Aktion:&#160;</TD>
		<TD>&#160;Dauer:&#160;</TD>
		<TD>&#160;Zeit:&#160;</TD>
	</TR>

	<?php
	//var_dump($_GET);
	if (!isset($_GET['startp'])) {
		$_GET['startp'] = "";
		$_GET['startp'] = 0;
	}

	if(!isset($_GET['lpp'])) {
		$_GET['lpp'] = 50;
	}
	$lpp = 50;

	IF(!isset($_GET['userlog']))
	$_GET['userlog']="";

	include('config.inc.php');
	MYSQL_CONNECT("$sql_server","$sql_user","$sql_pw");
	MYSQL_SELECT_DB("$sql_db");

	$sql = "";
	if(!empty($_GET['userlog'])){
		$sql = "SELECT *
	FROM ftp_transfer 
	WHERE username = '".$_GET['userlog']."' 
	ORDER BY lokale_zeit DESC limit ".$_GET['startp'].",$lpp";

	}
	else{
		$sql = "SELECT *
	FROM ftp_transfer 
	ORDER BY lokale_zeit DESC limit ".$_GET['startp'].",$lpp";
	}

	$result = MYSQL_QUERY($sql);
	$line = 0;
	while ($row=mysql_fetch_array($result)) {
		if($line == 0){
			echo "<TR class=\"blau\">\n";
		}
		else{
			echo "<TR>\n";
		}

		//$row[1] = preg_replace("/", "/ ", $row[1]);
		$row[1] = preg_replace('#///#', '/ ', $row[1]);

		echo "
<TD>$row[0]</TD>\n
<TD>$row[1]</TD>\n
<TD>$row[2]</TD>\n
<TD>$row[3]</TD>\n
<TD>$row[4]</TD>\n
<TD>$row[5]</TD>\n
<TD>$row[6] sec.</TD>\n
<TD>$row[7]</TD>\n
</TR>\n";

		if($line == 0){
			$line = 1;
		}
		else{
			$line = 0;
		}


	}


	echo "</TABLE><BR>";
	echo "<TABLE cellpadding=\"20\" cellspacing=\"20\" BORDER=\"0\"><TR><TD>";
	if($_GET['startp'] > 0) {
		echo "<a href=\"?action=logs&amp;startp=0&amp;userlog=".$_GET['userlog']."&amp;\">[Erste Seite]</a> ";

	}


	if($_GET['userlog'] != ""){
		$sql1 = "select * from ftp_transfer WHERE username = '".$_GET['userlog']."'";
	}
	else{
		$sql1 = "select * from ftp_transfer";
	}

	$result1=mysql_query($sql1);
	$Anzahl=mysql_num_rows($result1);


	if($Anzahl>$_GET['lpp']) {
		$Seiten=intval($Anzahl/$lpp);
		if($Anzahl%$lpp) {
			$Seiten++;
		}
	}


	for ($i=1;$i<=$Seiten;$i++) {
		$fwd=($i-1)*$lpp;

		if($i < 4){
			echo "<a href=\"?action=logs&amp;startp=$fwd&amp;userlog=".$_GET['userlog']."&amp;\">$i</a> ";
		}
	}
	echo "...";

	$myposition = ($_GET['startp']/$lpp)+1;
	if(1 != $myposition){
		$weiter = $_GET['startp']-$lpp;
		echo "<a href=\"?action=logs&amp;startp=$weiter&amp;userlog=".$_GET['userlog']."&amp;\"> &lt;&lt;&lt;&lt; </a> ";  // links
	}

	echo "| Seite: $myposition |";
	$weiter2 = $_GET['startp']+$lpp;
	if($Seiten != $myposition){

		echo "<a href=\"?action=logs&amp;startp=$weiter2&amp;userlog=".$_GET['userlog']."&amp;\"> &gt;&gt;&gt;&gt; </a> "; // Rechts
	}


	echo "...";
	$b = $i - 4;
	for ($i=1;$i<=$Seiten;$i++) {
		$fwd=($i-1)*$lpp;

		if($i > $b){
			echo "<a href=\"?action=logs&amp;startp=$fwd&amp;userlog=".$_GET['userlog']."&amp;\">$i</a> ";
		}
	}





	if($_GET['startp'] < $Anzahl-$_GET['lpp']) {

		echo "<a href=\"?action=logs&amp;startp=$fwd&amp;userlog=".$_GET['userlog']."&amp;\">[Letzte Seite]</a> ";
	}




	MYSQL_CLOSE();
	?>

	</TR>
</TABLE>

