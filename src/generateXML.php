<?php
  include "phpGPS.php";
  $db = new phpGPS_DB();
  $con = $db->connectToDB();
  
  include "login.php";
  $userGroups = "all";
  
  $loginT = null;
  if (isset($con)) {
    $loginT = new login($con, $userGroups);
  }
  
  function parseToXML($htmlStr) {
    $xmlStr=str_replace('<','&lt;',$htmlStr);
    $xmlStr=str_replace('>','&gt;',$xmlStr);
    $xmlStr=str_replace('"','&quot;',$xmlStr);
    $xmlStr=str_replace("'",'&#39;',$xmlStr);
    $xmlStr=str_replace("&",'&amp;',$xmlStr);
    return $xmlStr;
  }
  
  function generateMarkers($con, $marker_id, $showPathMarkers, $markerDelay) {
    // Select all the rows in the markers table
    $markerSql = "";
    if ($marker_id != "") $markerSql = "ge.gps_entry_id = " . phpGPS_DB::cleanInput($marker_id);
    
    //build query, if marker is set, then show regardless of status
    $query = "SELECT \n" .
             "  * \n" .
             "FROM \n" .
             "  gps_entries ge \n" .
             "  left join gps_type gt on ge.gps_type_id = gt.gps_type_id \n";
    $query = $query .
             "WHERE \n";
    if ($markerSql == "") {
      $query = $query . 
             "  ((ge.gps_status <> 'H' ";
      if (!$showPathMarkers) $query = $query . "AND ge.gps_status <> 'P' ";
      $query = $query . ") or ge.gps_status IS NULL) \n"; //H is Hidden, P is Path Only
    } else {
      $query = $query .
             "  $markerSql \n";
    }
    if ($markerDelay != null && $markerDelay > 0) {
      $query = $query .
             " AND ge.gps_entry_date < NOW() - INTERVAL $markerDelay DAY \n";
    }
    
    $query = $query .
             "ORDER BY \n" .
             "  ge.gps_date;";
    $result = mysqli_query($con, $query);
    if (!$result) {
      die('Invalid query: ' . mysql_error());
    }
    
    //Exit if no results
    if ($result->num_rows == 0) return;

    //Start Marker XML
    echo '<markers>';

    // Iterate through the rows, printing XML nodes for each
    while ($row = @mysqli_fetch_assoc($result)){
      echo '<marker ';
      echo 'id="' . parseToXML($row['gps_entry_id']) . '" ';
      echo 'name="' . parseToXML($row['gps_name']) . '" ';
      echo 'comment="' . parseToXML($row['gps_comment']) . '" ';
      echo 'address="' . parseToXML($row['gps_address1']) . '" ';
      echo 'lat="' . $row['gps_latitude'] . '" ';
      echo 'lng="' . $row['gps_longitude'] . '" ';
      echo 'accuracy="' . $row['gps_accuracy'] . '" ';
      echo 'path_id="' . $row['gps_path_id'] . '" ';
      echo 'type_name="' . $row['gps_type_name'] . '" ';
      echo 'image="' . $row['gps_type_image'] . '" ';
      echo 'custom_icon_name="' . $row['gps_type_icon'] . '" ';
      echo '/>';
    }

    // End XML file
    echo '</markers>';
  }
  
  function generatePaths($con, $markerDelay) {
    $query = "SELECT \n" .
             "  *\n" .
             "FROM \n" .
             "  gps_path gp\n" .
             "WHERE \n" .
             "  (gp.gps_path_status <> 'H' OR gp.gps_path_status IS NULL) \n" .
             "ORDER BY \n" .
             "  gp.gps_path_id;";
             
    $pathResults = mysqli_query($con, $query);
    if (!$pathResults) {
      die('Invalid query: ' . mysql_error());
    }
    if ($pathResults->num_rows == 0) return;

    echo '<paths>';
    
    while ($path = @mysqli_fetch_assoc($pathResults)){
      $query = "SELECT \n" .
               "  * \n" .
               "FROM \n" .
               "  gps_entries ge \n" .
               "WHERE \n" .
               "  (ge.gps_status <> 'H' OR ge.gps_status IS NULL) \n" .
               "  AND ge.gps_path_id = " . $path['gps_path_id'] . "\n";
      if ($markerDelay != null && $markerDelay > 0) {
      $query = $query .
               "  AND ge.gps_entry_date < NOW() - INTERVAL $markerDelay DAY \n";
      }
      $query = $query .
               "ORDER BY \n" .
               "  ge.gps_date;";
      $result = mysqli_query($con, $query);
      if ($result->num_rows == 0) break;
      
      echo '<path ';
      echo 'id="'      . $path['gps_path_id']     . '" ';
      echo 'name="'    . $path['gps_path_name']   . '" ';
      echo 'desc="'    . $path['gps_path_desc']   . '" ';
      echo 'type_id="' . $path['gps_type_id']     . '" ';
      echo 'status="'  . $path['gps_path_status'] . '" ';
      echo 'color="'   . $path['gps_path_color']  . '" ';
      echo '>';
      
      // Iterate through the rows, printing XML nodes for each
      while ($row = @mysqli_fetch_assoc($result)){
        echo '<coord ';
        echo 'lat="' . $row['gps_latitude'] . '" ';
        echo 'lng="' . $row['gps_longitude'] . '" ';
        echo '/>';
      }
      echo "</path>";
    }

    // End XML file
    echo '</paths>';
  }
  
  $showPathMarkers = false;
  if (isset($_GET['showPathMarkers']) && $_GET['showPathMarkers'] == "true") $showPathMarkers = true;
  
  $markerDelay = 0;
  if ($loginT == null || $loginT->getStatus() == false) {
    $markerDelay = phpGPS_Settings::$_markerDelay;
  }
  
  header("Content-type: text/xml");
  echo "<mapInfo>\n";
  if (isset($_GET['marker_id']) && $_GET['marker_id'] != "") {
    generateMarkers($con, $_GET['marker_id'], $markerDelay);
  } else {
    generateMarkers($con, '', $showPathMarkers, $markerDelay);
    generatePaths($con, $markerDelay);
  }
  echo "</mapInfo>\n";
?>
