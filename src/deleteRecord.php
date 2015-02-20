<?php
  include "phpGPS.php";
  $db = new phpGPS_DB();
  $con = $db->connectToDB();
  
  include "login.php";
  $userGroups = array(
      "admin",
  );
  
  $loginT = null;
  if (isset($con)) {
    $loginT = new login($con, $userGroups);
  
    if (!$loginT->userStatus && $userGroups != "admin") {
      echo "Login Failed<br>";
      login::showLogin();
      exit();
    } else {
      $type = $loginT->getType();
    }
  }
  
  $delete = false;
  if (isset($_GET['delete']) && $_GET['delete'] == "true") {
    $delete = true;
  }
  
  $viewQuery = false;
  if (isset($_GET['viewQuery']) && $_GET['viewQuery'] == "true") {
    $viewQuery = true;
  }
  
  if (!isset($_GET['table']) || $_GET['table'] == "") {
    exit("Table not set");
  }
  
  if (!isset($_GET['where']) || $_GET['where'] == "") {
    exit("Where not set");
  }
  
  $table = phpGPS_DB::cleanInput($_GET['table']);
  
  if (!in_array($table, phpGPS_DB::$_allowedTables))
    exit("Invalid Table!");
  
  $where = phpGPS_DB::cleanInput($_GET['where']);
  $sql = "DELETE FROM " . $table . " WHERE " . $where;
  
  if ($viewQuery) {
    $sqlBR = str_replace("\n","<br />\n",$sql);
    echo $sqlBR . "<br />";
  }
  
  if ($delete) {
    mysqli_query($con, $sql)
      or die(mysqli_error($con));
    echo "Record Deleted";
  }
?>
