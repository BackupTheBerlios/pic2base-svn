<?PHP
/*
 function adduser($username, $pw1, $pfad, $aktive) {
 $pw = crypt($pw1);
 MYSQL_QUERY( "INSERT INTO users VALUES('$username','65534','65534','$pw','','$pfad','','0','0','$aktive','','','')")
 or die ("DB-Fehler-Nummer" .mysql_errno(). "|| Meldung: ". mysql_error());
 }
 */
function exist_user($user) {
	$result = MYSQL_QUERY("SELECT username FROM users WHERE username = '$user'")
	or die ("DB-Fehler-Nummer" .mysql_errno(). "|| Meldung: ". mysql_error());
	if(mysql_num_rows($result) ==1) {
		// Username ist bereits vergeben!
		return "1";
	}
	else{
		// Username ist noch nicht vergeben!
		return "0";
	}
}
/*
 function chuser($user) {
 $str = strlen($user);
 if($str > 4 or $str == 4)
 {
 //Wenn der Username lang genug ist, geben Status 1 zurueck
 return "1";
 }
 else
 //Wenn der Username zukurz ist, geben Status 0 zurueck
 {
 return "0";
 }
 }

 function chpass($pass1, $pass2) {
 if($pass1 != $pass2)
 {
 return "0";
 }
 else{
 $len = strlen($pass1);
 if($len > 6 or $len == 6) {
 return "1";
 }
 else{
 RETURN "2";
 }
 }

 }
 */
function get_user() {
	$user = array();
	$result = MYSQL_QUERY("SELECT username FROM users")  or die ("DB-Fehler-Nummer" .mysql_errno(). "|| Meldung: ". mysql_error());
	while($row  =  mysql_fetch_row($result))  {
		for($i=0;  $i < mysql_num_fields($result);  $i++)  {
			array_push($user, $row[$i]);
		}
	}
	RETURN $user;
}

function user_detail($username) {
	$user = array();
	$result = MYSQL_QUERY("SELECT username, user_dir, aktiv, last_login, dl_bytes, ul_bytes, count FROM users WHERE username = '$username'")  or die ("DB-Fehler-Nummer" .mysql_errno(). "|| Meldung: ". mysql_error());
	while($row  =  mysql_fetch_row($result))  {
		for($i=0;  $i < mysql_num_fields($result);  $i++)  {
			array_push($user, $row[$i]);
		}
	}
	RETURN $user;
}

?>