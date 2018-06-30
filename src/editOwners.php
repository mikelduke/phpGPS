<?php
  include "phpGPS.php";
  $db = new phpGPS_DB();
  $con = $db->connectToDB();
  
  $userGroups = array(
      "admin",
      "users"
  );
  
  function getDeviceNamesByOwner($con, $owner_id) {
    $devices = "";
    $deviceSQL = "select * from gps_device where gps_owner_id = " . $owner_id . " ORDER BY gps_device_name";
    $deviceResult = mysqli_query($con, $deviceSQL);
    if (!$deviceResult) {
      die('Invalid query: ' . mysql_error());
    }
    
    while ($devRow = @mysqli_fetch_assoc($deviceResult)) {
      $devices = $devices . ", " . $devRow['gps_device_name'];
    }
    if (strlen($devices) >= 2) $devices = substr($devices, 2);
    
    return $devices;
  }

  function showOwners($con) {
    // Select all the rows in the markers table
    $query = "SELECT \n" .
             "  * \n" .
             "FROM \n" .
             "  gps_owner gowner \n" .
             "ORDER BY \n" .
             "  gowner.gps_owner_id;";
    $result = mysqli_query($con, $query);
    if (!$result) {
      die('Invalid query: ' . mysql_error());
    }
    
    echo "<table border='1' style='width:100%' class='table'>\n";
    echo "<thead><tr>\n";
    echo "<th>id</th>\n";
    echo "<th>name</th>\n";
    echo "<th>desc</th>\n";
    echo "<th>email</th>\n";
    echo "<th>address</th>\n";
    echo "<th>website</th>\n";
    echo "<th>phone</th>\n";
    echo "<th>Devices</th>\n";
    echo "<th>delete</th>\n";
    echo "</tr></thead>\n";
    
    echo "<tbody>\n";
    while ($row = @mysqli_fetch_assoc($result)){
      echo "<td>" . $row['gps_owner_id']      . "</td>\n";
      echo getTableRow($row, "gps_owner_name", "gps_owner", "gps_owner_id", "", "");
      echo getTableRow($row, "gps_owner_desc", "gps_owner", "gps_owner_id", "", "");
      echo getTableRow($row, "gps_owner_email", "gps_owner", "gps_owner_id", "", "");
      echo getTableRow($row, "gps_owner_address", "gps_owner", "gps_owner_id", "", "");
      echo getTableRow($row, "gps_owner_website", "gps_owner", "gps_owner_id", "", " <a href='http://" . $row['gps_owner_website'] . "'>[GO]</a>");
      echo getTableRow($row, "gps_owner_phone", "gps_owner", "gps_owner_id", "", "");
      
      $devices = getDeviceNamesByOwner($con, $row['gps_owner_id']);
      echo "<td>" . $devices . "</td>\n";
      echo "<td><a onclick='updateDelete(\"gps_device\", \"gps_owner_id\", \"NULL\", \"gps_owner\", \"gps_owner_id=" . $row['gps_owner_id']  . "\", true)' href='javascript:void(0);'>[X]</a></td>\n";
      echo "</tr>\n";
    }
    echo "</tbody>\n";
    echo "</table>\n";
  }
?>
<?php 
  $title = "Edit Owners";
  include 'header.php'; 
?>
<h3><?php echo $title; ?></h3>
<hr />
<?php showOwners($con); ?>
<a onclick='downloadAndRefresh("insertRecord.php?insert=true&table=gps_owner&gps_owner_name=New Owner")' href='javascript:void(0);'>New Owner</a><br />

<?php include 'footer.php'; ?>
