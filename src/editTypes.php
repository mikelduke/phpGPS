<?php
  include "phpGPS.php";
  $db = new phpGPS_DB();
  $con = $db->connectToDB();
  
  $userGroups = array(
      "admin",
      "users"
  );

  function showTypes($con) {
    // Select all the rows in the markers table
    $query = "SELECT \n" .
             "  * \n" .
             "FROM \n" .
             "  gps_type gt \n" .
             "ORDER BY \n" .
             "  gt.gps_type_id;";
    $result = mysqli_query($con, $query);
    if (!$result) {
      die('Invalid query: ' . mysql_error());
    }
    
    echo "<table border='1' style='width:100%' class='table'>\n";
    echo "<thead><tr>\n";
    echo "<th>id</th>\n";
    echo "<th>name</th>\n";
    echo "<th>desc</th>\n";
    echo "<th>image</th>\n";
    echo "<th>icon</th>\n";
    echo "<th>delete</th>\n";
    echo "</tr></thead>\n";
    
    echo "<tbody>\n";
    while ($row = @mysqli_fetch_assoc($result)){
      echo "<td>" . $row['gps_type_id']      . "</td>\n";
      echo getTableRow($row, "gps_type_name", "gps_type", "gps_type_id", "", "");
      echo getTableRow($row, "gps_type_desc", "gps_type", "gps_type_id", "", "");
      echo getTableRow($row, "gps_type_image", "gps_type", "gps_type_id", "", "");
      echo getTableRow($row, "gps_type_icon", "gps_type", "gps_type_id", "", "");
      echo "<td><a onclick='updateDelete(\"gps_entries\", \"gps_type_id\", \"NULL\", \"gps_type\", \"gps_type_id=" . $row['gps_type_id']  . "\", true)' href='javascript:void(0);'>[X]</a></td>\n";
      echo "</tr>\n";
    }
    echo "</tbody>\n";
    echo "</table>\n";
  }
?>
<?php 
  $title = "Edit Types";
  include 'header.php'; 
?>
<h3><?php echo $title; ?></h3>
<hr />
<?php showTypes($con); ?>
<a onclick='downloadAndRefresh("insertRecord.php?insert=true&table=gps_type&gps_type_name=New Marker Type")' href='javascript:void(0);'>New Marker Type</a><br />
<?php include 'footer.php'; ?>
