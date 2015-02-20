<?php
  include "../phpGPS.php";
  $db = new phpGPS_DB();
  $con = $db->connectToDB();
  
  function install($con) {
    // Select all the rows in the markers table
    $query = "drop table users;";
    $result = mysqli_query($con, $query);
    if (!$result) {
      die('Invalid query: ' . mysql_error());
    }
    
    $query = "drop table user_types;";
    $result = mysqli_query($con, $query);
    if (!$result) {
      die('Invalid query: ' . mysql_error());
    }
    
    $query = "drop table gps_entries;";
    $result = mysqli_query($con, $query);
    if (!$result) {
      die('Invalid query: ' . mysql_error());
    }
    
    $query = "drop table gps_path;";
    $result = mysqli_query($con, $query);
    if (!$result) {
      die('Invalid query: ' . mysql_error());
    }
    
    $query = "drop table gps_type;";
    $result = mysqli_query($con, $query);
    if (!$result) {
      die('Invalid query: ' . mysql_error());
    }
    
    $query = "drop table gps_device;";
    $result = mysqli_query($con, $query);
    if (!$result) {
      die('Invalid query: ' . mysql_error());
    }
    
    $query = "drop table gps_owner;";
    $result = mysqli_query($con, $query);
    if (!$result) {
      die('Invalid query: ' . mysql_error());
    }
    
    
    
    echo "Remove Database Tables Complete!";
  }
  
  install($con);
?>
