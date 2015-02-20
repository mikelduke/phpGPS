<?php
  include "phpGPS.php";
  $db = new phpGPS_DB();
  $con = $db->connectToDB();
  
  $userGroups = array(
      "admin",
      "users"
  );

  function showPaths($con) {
    // Select all the rows in the markers table
    $query = "SELECT \n" .
             "  * \n" .
             "FROM \n" .
             "  gps_path gp \n" .
             "ORDER BY \n" .
             "  gp.gps_path_id;";
    $result = mysqli_query($con, $query);
    if (!$result) {
      die('Invalid query: ' . mysql_error());
    }
    
    echo "<table border='1' style='width:100%' class='table'>\n";
    echo "<thead><tr>\n";
    echo "<th>id</th>\n";
    echo "<th>name</th>\n";
    echo "<th>desc</th>\n";
    //echo "<th>type</th>\n";
    echo "<th>status</th>\n";
    echo "<th>color</th>\n";
    echo "<th>delete</th>\n";
    echo "</tr></thead>\n";
    
    echo "<tbody>\n";
    while ($row = @mysqli_fetch_assoc($result)){
      echo "<td>" . $row['gps_path_id']    . "</td>\n";
      echo getTableRow($row, "gps_path_name", "gps_path", "gps_path_id", "", "");
      echo getTableRow($row, "gps_path_desc", "gps_path", "gps_path_id", "", "");
      //echo "<td>" . $row['gps_type_id']    . "</td>\n";
      echo "<td>\n". buildSetPathStatusDropDown($row['gps_path_status'], $row['gps_path_id']) . "</td>\n";
      echo getTableRow($row, "gps_path_color", "gps_path", "gps_path_id", "", "");
      echo "<td><a onclick='updateDelete(\"gps_entries\", \"gps_path_id\", \"NULL\", \"gps_path\", \"gps_path_id=" . $row['gps_path_id']  . "\", true)' href='javascript:void(0);'>[X]</a></td>\n";
      echo "</tr>\n";
    }
    echo "</tbody>\n";
    echo "</table>\n";
  }
  
  function buildSetPathStatusDropDown($selectedOption, $pathID) {
    //TODO Maybe Create status table in database, or at least move map to shared file
    $ret = "<div class='form-group'>\n" .
        "  <select class='form-control' id='status$pathID' " .
        "onchange='updateRecord(\"gps_path\", \"gps_path_status\", this.value, \"gps_path_id=$pathID\")'>\n";
  
    if ($selectedOption == "") $selected = " selected='selected'";
    else $selected = "";
    $ret = $ret . "    <option value=NULL$selected>Default</option>\n";
  
    $statuses = array(
      'H'  => "Hidden",
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
  $title = "Edit Paths";
  include 'header.php'; 
?>
<h3><?php echo $title; ?></h3>
<hr />
<? showPaths($con); ?>
<a onclick='downloadAndRefresh("insertRecord.php?insert=true&table=gps_path&gps_path_name=New Path")' href='javascript:void(0);'>New Path</a><br />
<?php include 'footer.php'; ?>
