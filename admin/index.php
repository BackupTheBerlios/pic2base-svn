<html>
<body>
<?
  include "config/all.php";
  switch ($item)
  {
    case "":
    case "home":
      include "home.php";
      break;
    case "showusers":
      include "showusers.php";
      break;
    case "showusergroup":
      include "showusergroup.php";
      break;  
    case "showgrouppermissions":
      include "showgrouppermissions.php";
      break;
    case "loginexe":
      include "loginexe.php";
      break;
  }
?>
</body>
</html>