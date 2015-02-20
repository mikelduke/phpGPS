<?php
  include "phpGPS.php";
  $db = new phpGPS_DB();
  $con = $db->connectToDB();
  
  $userGroups = array(
      "admin",
      "users"
  );

  function showMarkers($con, $sort = "") {
    // Select all the rows in the markers table
    $query = "SELECT \n" .
             "  ge.*, \n" .
             "  gp.gps_path_name as gps_path_name, \n" .
             "  gt.gps_type_name as gps_type_name \n" .
             "FROM \n" .
             "  gps_entries ge \n" .
             "  left join gps_path gp on ge.gps_path_id = gp.gps_path_id \n" .
             "  left join gps_type gt on ge.gps_type_id = gt.gps_type_id \n" .
             "ORDER BY \n" .
             $sort . 
             "  ge.gps_date, ge.gps_entry_id;";
    $result = mysqli_query($con, $query);
    if (!$result) {
      die('Invalid query: ' . mysql_error());
    }
    
    echo "<table border='1' style='width:100%' class='table'>\n";
    echo "<thead><tr>\n";
    //echo "<td>hide</td>\n";
    echo "<th>id</th>\n";
    echo "<th>date</th>\n";
    echo "<th>name</th>\n";
    echo "<th>address</th>\n";
    echo "<th>lat</th>\n";
    echo "<th>long</th>\n";
    echo "<th>status</th>\n";
    echo "<th>path</th>\n";
    echo "<th>type</th>\n";
    echo "<th>device</th>\n";
    echo "<th>delete</th>\n";
    echo "</thead></tr>\n";
    
    echo "<tbody>\n";
    
    //TODO Add marker path sequence values
    while ($row = @mysqli_fetch_assoc($result)){
      
      $pathSQL = "SELECT gps_path_id, gps_path_name from gps_path \n" .
          "where (gps_path_status <> 'H' OR gps_path_status is null) \n" .
          "order by gps_path_id";
      $pathsDropDown = buildDropDown($con, $pathSQL, $row['gps_path_id'], "path", "gps_entries", "gps_path_id", "gps_path_name", "gps_entry_id", $row['gps_entry_id'], true);
      
      //load types drop down
      $typesSQL = "SELECT gps_type_id, gps_type_name \n" .
          "from gps_type \n" .
          "order by gps_type_id";
      $typesDropDown = buildDropDown($con, $typesSQL, $row['gps_type_id'], "type", "gps_entries", "gps_type_id", "gps_type_name", "gps_entry_id", $row['gps_entry_id'], true);
      
      //load device drop down
      $deviceSQL = "SELECT gps_device_id, gps_device_name from gps_device \n" .
          "order by gps_device_id";
      $deviceDropDown = buildDropDown($con, $deviceSQL, $row['gps_device_id'], "device", "gps_entries", "gps_device_id", "gps_device_name", "gps_entry_id", $row['gps_entry_id'], true);
      
      
      echo "<td><a href='view.php?marker_id=" . $row['gps_entry_id'] . "&zoom=15&center=" . $row['gps_latitude'] . "," . $row['gps_longitude'] . "&edit=true'>" . $row['gps_entry_id'] . "</a></td>\n";
      echo getTableRow($row, "gps_date", "gps_entries", "gps_entry_id", "", "");
      echo getTableRow($row, "gps_name", "gps_entries", "gps_entry_id", "", "");
      echo getTableRow($row, "gps_address1", "gps_entries", "gps_entry_id", "", "");
      echo getTableRow($row, "gps_latitude", "gps_entries", "gps_entry_id", "", "");
      echo getTableRow($row, "gps_longitude", "gps_entries", "gps_entry_id", "", "");
      echo "<td>\n". buildSetStatusDropDown($row['gps_status'], $row['gps_entry_id']) . "</td>\n";
      echo "<td>" . $pathsDropDown  . "</td>\n";
      echo "<td>" . $typesDropDown  . "</td>\n";
      echo "<td>" . $deviceDropDown . "</td>\n";
      echo "<td><a onclick='deleteRecord(\"gps_entries\", \"gps_entry_id=" . $row['gps_entry_id'] . "\", true)' href='javascript:void(0);'>[X]</a></td>\n";
      echo "</tr>\n";
    }
    echo "</tbody>\n";
    echo "</table>\n";
  }
  
  function buildSetStatusDropDown($selectedOption, $entryID) {
    //TODO Maybe Create status table in database, or at least move map to shared file
    $ret = "<div class='form-group'>\n" .
        "  <select class='form-control' id='status$entryID' " .
        "onchange='updateRecord(\"gps_entries\", \"gps_status\", this.value, \"gps_entry_id=$entryID\")'>\n";
  
    if ($selectedOption == "") $selected = " selected='selected'";
    else $selected = "";
    $ret = $ret . "    <option value=NULL$selected>Default</option>\n";
    
    $statuses = array(
      'H'  => "Hidden",
      'P'  => "Path Only" 
    );
    
    foreach ($statuses as $key => $value) {
      $selected = "";
      if ($key == $selectedOption) $selected = " selected='selected'";
      
      $ret = $ret . "    <option value='" . $key . "'$selected>";
      $ret = $ret . $statuses[$key] . "</option>\n";
    }
  
    $ret = $ret . "  </select>\n ".
                  "</div>\n";
    return $ret;
  }
?>
<?php 
  $title = "Edit Markers";
  include 'header.php'; 
?>

  <h3><?php echo $title; ?></h3>
  <hr />
  <? showMarkers($con, null); ?>
  <br />
<?php include 'footer.php' ?>
