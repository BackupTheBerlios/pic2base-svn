<?

function hasPermission($username, $permissionString)
{
  //dirty trick:
  @include '../../share/global_config.php';
  @include '../share/global_config.php';
  include $sr.'/bin/share/db_connect1.php';
  mysql_connect ($db_server, $user, $PWD);
  $result = mysql ($db,"select * from users WHERE username='".$username."'");
  //echo mysql_error();
  if (mysql_num_rows($result) == 1)
  {
    $userid = mysql_result ($result, 0, "id");
    $result = mysql ($db,"select * from permissions WHERE shortdescription='".$permissionString."'");
    if (mysql_num_rows($result) == 1)
    {
      $permissionID = mysql_result ($result, 0, "id");
      $result = mysql ($db,"select * from userpermissions WHERE user_id='".$userid."' AND permission_id='".$permissionID."'");
      if (mysql_num_rows($result) == 1)
      {
        return (mysql_result($result, 0, "enabled") == "1");
      }
    }
    // Test auf Benutzer-Gruppen
    $result = mysql ($db,"select * from users WHERE id='".$userid."'");
    $groupID = mysql_result($result, 0, "group_id");
    $result = mysql ($db,"select * from usergroups WHERE id=".mysql_result ($result, 0, "group_id"));
    if (mysql_num_rows($result) == 1)
    {
      $result = mysql ($db, "select * from permissions WHERE shortdescription='".$permissionString."'");
      if (mysql_num_rows($result) == 1)
      {
        $permissionID = mysql_result ($result, 0, "id");
        $result = mysql ($db,"select * from grouppermissions WHERE group_id='".$groupID."' AND permission_id='".$permissionID."'");
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
}

?>