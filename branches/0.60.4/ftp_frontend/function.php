<?PHP

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