<?php
/**
 * Self-contained script that is meant to be called from outside the phpGPS 
 * app to add markers to the map  
 */

  include "phpGPS.php";
  $db = new phpGPS_DB();
  $con = $db->connectToDB();
  
  /* 
   * Adds a new entry to the map if the right variables are populated
   *  
   * Test URL:
   * update.php?newEntry=Y&gps_device_id=1&gps_type_id=1&gps_path_id=1&gps_date_dt=11-13-2014&gps_date_time=22.31&gps_status=&gps_latitude=32.86181604&gps_longitude=-96.76354452&gps_altitude=160.0&gps_accuracy=57&gps_name=test spot&gps_comment=test comment&gps_address1=address 1&gps_address2=address 2&gps_address3=address 3&gps_city=city&gps_zipcode=567567&gps_state=state&gps_country=country
   */
  function newEntry($con) {
    //TODO add debug mode to hide the extra output statements
    $gps_entry_date = "now()";
    $gps_device_id  = ((isset($_GET["gps_device_id"]) && $_GET["gps_device_id"] != "") ? $_GET["gps_device_id"] : phpGPS_Settings::$_defaultDeviceID);
    $gps_type_id    = ((isset($_GET["gps_type_id"])   && $_GET["gps_type_id"] != "")   ? $_GET["gps_type_id"]   : phpGPS_Settings::$_defaultTypeID);
    $gps_path_id    = ((isset($_GET["gps_path_id"])   && $_GET["gps_path_id"] != "")   ? $_GET["gps_path_id"]   : "NULL");
    $gps_date_dt    = ((isset($_GET["gps_date_dt"])   && $_GET["gps_date_dt"] != "")   ? $_GET["gps_date_dt"]   : null);
    $gps_date_time  = ((isset($_GET["gps_date_time"]) && $_GET["gps_date_time"] != "") ? $_GET["gps_date_time"] : null);
    $gps_status     = ((isset($_GET["gps_status"])    && $_GET["gps_status"] != "")    ? $_GET["gps_status"]    : "NULL");
    $gps_latitude   = ((isset($_GET["gps_latitude"])  && $_GET["gps_latitude"] != "")  ? $_GET["gps_latitude"]  : null);
    $gps_longitude  = ((isset($_GET["gps_longitude"]) && $_GET["gps_longitude"] != "") ? $_GET["gps_longitude"] : null);
    $gps_altitude   = ((isset($_GET["gps_altitude"])  && $_GET["gps_altitude"] != "")  ? $_GET["gps_altitude"]  : "NULL");
    $gps_accuracy   = ((isset($_GET["gps_accuracy"])  && $_GET["gps_accuracy"] != "")  ? $_GET["gps_accuracy"]  : "NULL");
    $gps_name       = ((isset($_GET["gps_name"])      && $_GET["gps_name"] != "")      ? $_GET["gps_name"]      : "");
    $gps_comment    = ((isset($_GET["gps_comment"])   && $_GET["gps_comment"] != "")   ? $_GET["gps_comment"]   : "");
    $gps_address1   = ((isset($_GET["gps_address1"])  && $_GET["gps_address1"] != "")  ? $_GET["gps_address1"]  : "");
    $gps_address2   = ((isset($_GET["gps_address2"])  && $_GET["gps_address2"] != "")  ? $_GET["gps_address2"]  : "");
    $gps_address3   = ((isset($_GET["gps_address3"])  && $_GET["gps_address3"] != "")  ? $_GET["gps_address3"]  : "");
    $gps_city       = ((isset($_GET["gps_city"])      && $_GET["gps_city"] != "")      ? $_GET["gps_city"]      : "");
    $gps_zipcode    = ((isset($_GET["gps_zipcode"])   && $_GET["gps_zipcode"] != "")   ? $_GET["gps_zipcode"]   : "");
    $gps_state      = ((isset($_GET["gps_state"])     && $_GET["gps_state"] != "")     ? $_GET["gps_state"]     : "");
    $gps_country    = ((isset($_GET["gps_country"])   && $_GET["gps_country"] != "")   ? $_GET["gps_country"]   : "");
    $gps_date       = "now()"; //FIXME to generate mysql datetime from gps date and time vars
    $gps_latlong   = ((isset($_GET["gps_latlong"])  && $_GET["gps_latlong"] != "")  ? $_GET["gps_latlong"]  : null);
    $gps_devicename = ((isset($_GET["gps_devicename"])  && $_GET["gps_devicename"] != "")  ? $_GET["gps_devicename"]  : null);
    
    //Clean Inputs
    $gps_entry_date = phpGPS_DB::cleanInput($gps_entry_date);
    $gps_device_id  = phpGPS_DB::cleanInput($gps_device_id);
    $gps_type_id    = phpGPS_DB::cleanInput($gps_type_id);
    $gps_path_id    = phpGPS_DB::cleanInput($gps_path_id);
    $gps_date_dt    = phpGPS_DB::cleanInput($gps_date_dt);
    $gps_date_time  = phpGPS_DB::cleanInput($gps_date_time);
    $gps_status     = phpGPS_DB::cleanInput($gps_status);
    $gps_latitude   = phpGPS_DB::cleanInput($gps_latitude);
    $gps_longitude  = phpGPS_DB::cleanInput($gps_longitude);
    $gps_altitude   = phpGPS_DB::cleanInput($gps_altitude);
    $gps_accuracy   = phpGPS_DB::cleanInput($gps_accuracy);
    $gps_name       = phpGPS_DB::cleanInput($gps_name);
    $gps_comment    = phpGPS_DB::cleanInput($gps_comment);
    $gps_address1   = phpGPS_DB::cleanInput($gps_address1);
    $gps_address2   = phpGPS_DB::cleanInput($gps_address2);
    $gps_address3   = phpGPS_DB::cleanInput($gps_address3);
    $gps_city       = phpGPS_DB::cleanInput($gps_city);
    $gps_zipcode    = phpGPS_DB::cleanInput($gps_zipcode);
    $gps_state      = phpGPS_DB::cleanInput($gps_state);
    $gps_country    = phpGPS_DB::cleanInput($gps_country);
    $gps_date       = phpGPS_DB::cleanInput($gps_date);
    $gps_latlong    = phpGPS_DB::cleanInput($gps_latlong);
    $gps_devicename = phpGPS_DB::cleanInput($gps_devicename);
    
    //Split latlong to lat, long variables if its present, otherwise the separate vars will be used
    if ($gps_latlong != null && $gps_latlong != "") {
      $latlongAr = explode(",", $gps_latlong);
      if (sizeof($latlongAr) == 2) {
        $gps_latitude = $latlongAr[0];
        $gps_longitude = $latlongAr[1];
        
        echo "split to lat: $gps_latitude long: $gps_longitude<br>\n";
      } 
    }
    
    //lookup device id using device name
    if ($gps_devicename != null && $gps_devicename != "") {
      echo "devicename: $gps_devicename<br>\n";
      
      $devNameSql = "select gps_device_id from gps_device where gps_device_local_id = '$gps_devicename'";
      $result = mysqli_query($con, $devNameSql);
      if (mysqli_num_rows($result) > 0) {
        while ($deviceRow = @mysqli_fetch_assoc($result)) {
          $gps_device_id = $deviceRow['gps_device_id'];
        
          echo "gps name: $gps_devicename id: $gps_device_id<br>\n";
        }
      } else {
        $newDeviceSql = "insert into gps_device (gps_device_name, gps_device_local_id) VALUES ('New Device', '$gps_devicename')";
        mysqli_query($con, $newDeviceSql);
      }
    }
    
    //Validate Path and insert if needed
    $sql = "select gps_path_id from gps_path where gps_path_id = $gps_path_id";
    $result = mysqli_query($con, $sql);
    if ($result->num_rows == 0) {
      $newPathSql = 
        "insert into gps_path (\n" .
        " gps_path_id \n" .
        ") VALUES (\n" .
        "$gps_path_id);";
      mysqli_query($con, $newPathSql);
    }
    
    //Create and execute query string
    $sql = 
      "insert into gps_entries (\n" .
      "  gps_entry_date, \n" .
      "  gps_device_id, \n" .
      "  gps_type_id, \n" .
      "  gps_path_id, \n" .
      "  gps_date, \n" .
      "  gps_status, \n" .
      "  gps_latitude, \n" .
      "  gps_longitude, \n" .
      "  gps_altitude, \n" .
      "  gps_accuracy, \n" .
      "  gps_name, \n" .
      "  gps_comment, \n" .
      "  gps_address1, \n" .
      "  gps_address2, \n" .
      "  gps_address3, \n" .
      "  gps_city, \n" .
      "  gps_zipcode, \n" .
      "  gps_state, \n" .
      "  gps_country \n" .
      ") VALUES ( \n" .
      "  $gps_entry_date, \n" .
      "  $gps_device_id, \n" .
      "  $gps_type_id, \n" .
      "  $gps_path_id, \n" .
      "  $gps_date, \n" .
      "  '$gps_status', \n" .
      "  $gps_latitude, \n" .
      "  $gps_longitude, \n" .
      "  $gps_altitude, \n" .
      "  $gps_accuracy, \n" .
      "  '$gps_name', \n" .
      "  '$gps_comment', \n" .
      "  '$gps_address1', \n" .
      "  '$gps_address2', \n" .
      "  '$gps_address3', \n" .
      "  '$gps_city', \n" .
      "  '$gps_zipcode', \n" .
      "  '$gps_state', \n" .
      "  '$gps_country' \n" .
      ");";
    $sqlBR = str_replace("\n","<br />\n",$sql);
    echo $sqlBR . "<br />";
    
    if ($gps_device_id != null && $gps_date_dt != null && $gps_date_time != null && $gps_latitude != null && $gps_longitude != null) {
      mysqli_query($con, $sql)
        or die(mysqli_error($con));
      echo "Record Created!<br />\n";
    } else {
      echo "<h2>Missing Data!</h2>";
    }
  }
  
  function showHelp($con) {
    echo "<b>Device Names:</b><br>\n";
    $devNameSql = "select gps_device_local_id from gps_device order by gps_device_local_id";
    $result = mysqli_query($con, $devNameSql);
    while ($deviceRow = @mysqli_fetch_assoc($result)) {
      echo "\t" . $deviceRow['gps_device_local_id'] . "<br>\n";
    }
    echo "<br>\n";
    
    echo "<b>GPS Marker Types:</b><br>\n";
    $typeSql = "select gps_type_id, gps_type_name from gps_type order by gps_type_id";
    $result = mysqli_query($con, $typeSql);
    while ($row = @mysqli_fetch_assoc($result)) {
      echo "\t" . $row['gps_type_id'] . ": " . $row['gps_type_name'] . "<br>\n";
    }
    echo "<br>\n";
    
    echo "<b>GPS Paths:</b><br>\n";
    $typeSql = "select gps_path_id, gps_path_name from gps_path order by gps_path_id";
    $result = mysqli_query($con, $typeSql);
    while ($row = @mysqli_fetch_assoc($result)) {
      echo "\t" . $row['gps_path_id'] . ": " . $row['gps_path_name'] . "<br>\n";
    }
    echo "<br>\n";
    
    echo "<b>Marker Status:</b><br>\n";
    echo "\t*: Default\n<br>";
    echo "\tH: Hidden\n<br>";
    echo "\tP: Path Only\n<br>";
    echo "<br>\n";
  }
  
  if (isset($_GET['key']) && $_GET['key'] == phpGPS_Settings::$_secretKey) {
    if (isset($_GET["newEntry"]) && $_GET["newEntry"] == "Y") {
      echo ("New Entry<br /><br />\n");
      newEntry($con);
    } else if (isset($_GET["help"])) {
      showHelp($con);
    } else {
      echo ("Invalid Option");
    }
  } else {
    echo "Invalid Key!";
  }
  
?>
