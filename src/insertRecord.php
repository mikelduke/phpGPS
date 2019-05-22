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
  
  $insert = false;
  if (isset($_GET['insert']) && $_GET['insert'] == "true") {
    $insert = true;
  }
  
  $viewQuery = false;
  if (isset($_GET['viewQuery']) && $_GET['viewQuery'] == "true") {
    $viewQuery = true;
  }
  
  if (!isset($_GET['table']) || $_GET['table'] == "") {
    exit("Table not set");
  }
  
  $table = phpGPS_DB::cleanInput($_GET['table']);
  if (!in_array($table, phpGPS_DB::$_allowedTables))
    exit("Invalid Table!"); //TODO add user level based table security
  
  $sql = "INSERT INTO " . $table;
  
  $hasValues = false;
  $columns = "";
  $values = "";
  foreach ($_GET as $key => $value) {
    if ($key != "insert" && $key != "table" && $key != "viewQuery") {
      $field = phpGPS_DB::cleanInput($key);
      $val = phpGPS_DB::cleanInput($value);
  
      if ($val != "NULL" && $val !== "now()") {
        $val = "'" . $val . "'";
      }
      if ($hasValues) {
        $columns = $columns . ", ";
        $values = $values . ", ";
      }
      $columns = $columns . $field;
      $values = $values . $val;
  
      $hasValues = true;
    }
  }
  
  $sql = $sql . " ($columns) VALUES ($values)";
  
  if (!$hasValues) {
    exit("No Fields set");
  }
  
  if ($viewQuery) {
    $sqlBR = str_replace("\n","<br />\n",$sql);
    echo $sqlBR . "<br />";
  }
  
  if ($insert) {
    mysqli_query($con, $sql)
    or die(mysqli_error($con));
    echo "Record Inserted";
  }
?>