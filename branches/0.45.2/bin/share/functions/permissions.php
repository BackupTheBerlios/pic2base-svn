<?php 

function hasPermission($username, $permissionString)
{
  //dirty trick:
  @include '../../share/global_config.php';
  @include '../share/global_config.php';
  @include '../bin/share/global_config.php';
  include $sr.'/bin/share/db_connect1.php';
  mysql_connect($db_server, $user, $PWD);
  mysql_select_db ($db);
  
//###################################################################################
  /*
  $result = mysql_query("select * from $table1 WHERE username='".$username."'");
  echo mysql_error();
  if (mysql_num_rows($result) == 1)
  {
    $userid = mysql_result ($result, 0, "id");
    $result = mysql_query("select * from $table8 WHERE shortdescription='".$permissionString."'");
    if (mysql_num_rows($result) == 1)
    {
      $permissionID = mysql_result ($result, 0, "perm_id");
      $result = mysql_query("select * from $table7 WHERE user_id='".$userid."' AND permission_id='".$permissionID."'");
      if (mysql_num_rows($result) == 1)
      {
        return (mysql_result($result, 0, "enabled") == "1");
      }
    }
    // Test auf Benutzer-Gruppen
    /*
    $result = mysql_query("select * from $table1 WHERE id='".$userid."'");
    $groupID = mysql_result($result, 0, "group_id");
    $result = mysql_query("select * from $table9 WHERE id=".mysql_result ($result, 0, "group_id"));
    if (mysql_num_rows($result) == 1)
    {
      $result = mysql_query("select * from $table8 WHERE shortdescription='".$permissionString."'");
      if (mysql_num_rows($result) == 1)
      {
        $permissionID = mysql_result ($result, 0, "id");
        $result = mysql_query("select * from $table6 WHERE group_id='".$groupID."' AND permission_id='".$permissionID."'");
        if (mysql_num_rows($result) == 1)
        {
          return (mysql_result($result, 0, "enabled") == "1");
        } else
        {
          return False;
        }
      } else
      {
        return False;
      }
    } else
    {
      return False;
    }
   
  } else
  {
    return False;
  }
  */
//##########################################################################################
//User muss aktiv sein und das erforderliche Recht haben:
$result1 = mysql_query("SELECT $table1.id, $table1.username, $table1.aktiv, $table7.user_id, $table7.permission_id, 
$table7.enabled, $table8.perm_id, $table8.shortdescription
		FROM $table1 inner join $table7 inner join $table8
		ON $table1.id = $table7.user_id 
		AND $table7.permission_id = $table8.perm_id
		AND $table1.username = '$username'
		AND $table1.aktiv = '1'
		AND $table8.shortdescription = '$permissionString' 
		AND $table7.enabled = '1'");
		echo mysql_error();
		IF(mysql_num_rows($result1) == 1)
		{
			return True;
		}
		ELSE
		{
			return False;
		}
}

?>