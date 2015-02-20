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
  
  $update = false;
  if (isset($_GET['update']) && $_GET['update'] == "true") {
    $update = true;
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
  $sql = "UPDATE " . $table . " SET ";
  
  $hasValues = false;
  foreach ($_GET as $key => $value) {
    if ($key != "update" && $key != "table" && $key != "where" && $key != "viewQuery") {
      $field = phpGPS_DB::cleanInput($key);
      $val = phpGPS_DB::cleanInput($value);
      
      if ($val != "NULL") $val = "'" . $val . "'"; 
      if ($hasValues) $field = ", " . $field;
      $sql = $sql . "\n$field = $val";
      
      $hasValues = true;
    }
  }
  
  if (!$hasValues) {
    exit("No Fields set");
  }
  
  $sql = $sql . " \nWHERE " . $where . ";";
  
  if ($viewQuery) {
    $sqlBR = str_replace("\n","<br />\n",$sql);
    echo $sqlBR . "<br />";
  }
  
  if ($update) {
    mysqli_query($con, $sql)
      or die(mysqli_error($con));
    echo "Record Updated";
  }
?>