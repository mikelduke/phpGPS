<?php
  include "phpGPS.php";
  $db = new phpGPS_DB();
  $con = $db->connectToDB();
  
  /* Test URL:
  setStatus.php?gps_entry_id=4
  */
  function setEntryStatus($con, $gps_entry_id, $gps_status) {
    $gps_entry_id = phpGPS_DB::cleanInput($gps_entry_id);
    
    $sql = 
      "update gps_entries \n" .
      "set gps_status = '$gps_status'\n" .
      "where gps_entry_id = $gps_entry_id\n" .
      ";";
    $sqlBR = str_replace("\n","<br />\n",$sql);
    echo $sqlBR . "<br />";
    
    if ($gps_entry_id != null && $gps_entry_id != "") {
      mysqli_query($con, $sql)
        or die(mysqli_error($con));
      return true;
    } else {
      return false;
    }
  }
  
  function setPathStatus($con, $gps_path_id, $gps_status) {
    $gps_path_id = phpGPS_DB::cleanInput($gps_path_id);
  
    $sql =
    "update gps_path \n" .
    "set gps_path_status = '$gps_status'\n" .
    "where gps_path_id = $gps_path_id\n" .
    ";";
    $sqlBR = str_replace("\n","<br />\n",$sql);
    echo $sqlBR . "<br />";
  
    if ($gps_path_id != null && $gps_path_id != "") {
      mysqli_query($con, $sql)
      or die(mysqli_error($con));
      return true;
    } else {
      return false;
    }
  }
  
  
  if (isset($_GET["gps_entry_id"]) && $_GET["gps_entry_id"] != "") {
    $gps_status = '';
    if (isset($_GET["gps_status"])) $gps_status = $_GET["gps_status"];
    else $gps_status = "";
    
    $res = setEntryStatus($con, $_GET["gps_entry_id"], $gps_status);
    if ($res) echo "<h2>Marker Status Changed!</h2>";
    else echo "<h2>Error Changing Status!</h2>";
    
  } else if (isset($_GET["gps_path_id"]) && $_GET["gps_path_id"] != "") {
    $gps_status = '';
    if (isset($_GET["gps_status"])) $gps_status = $_GET["gps_status"];
    else $gps_status = "";
    
    $res = setPathStatus($con, $_GET["gps_path_id"], $gps_status);
    if ($res) echo "<h2>Path Status Changed!</h2>";
    else echo "<h2>Error Changing Status!</h2>";
  } else {
    echo ("Invalid Option");
  }
  
?>
