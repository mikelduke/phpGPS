<?php
  include "phpGPS.php";
  $db = new phpGPS_DB();
  $con = $db->connectToDB();
  
  $userGroups = array(
      "admin",
  );
  
  function showGroups($con) {
    // Select all the rows in the markers table
    $query = "SELECT \n" .
             "  user_type_id, \n" .
             "  user_type_name, \n" .
             "  user_type_desc \n" .
             "FROM \n" .
             "  user_types \n" .
             "ORDER BY \n" .
             "  user_type_id;";
    $result = mysqli_query($con, $query);
    if (!$result) {
      die('Invalid query: ' . mysql_error());
    }
    
    echo "<table border='1' style='width:100%' class='table'>\n";
    echo "<thead><tr>\n";
    echo "<th>id</th>\n";
    echo "<th>name</th>\n";
    echo "<th>desc</th>\n";
    echo "<th>delete</th>\n";
    echo "</tr></thead>\n";
    
    echo "<tbody>\n";
    while ($row = @mysqli_fetch_assoc($result)){
      
      echo "<td>" . $row['user_type_id']      . "</td>\n";
      echo getTableRow($row, "user_type_name", "user_types", "user_type_id", "", "");
      echo getTableRow($row, "user_type_desc", "user_types", "user_type_id", "", "");
      echo "<td><a onclick='deleteRecord(\"user_types\", \"user_type_id=" . $row['user_type_id'] . "\", true)' href='javascript:void(0);'>[X]</a></td>\n";
      echo "</tr>\n";
    }
    echo "</tbody>\n";
    echo "</table>\n";
  }
?>
<?php 
  $title = "Edit User Groups";
  include 'header.php'; 
?>
<h3><?php echo $title; ?></h3>
<hr />
<? showGroups($con); ?>
<a onclick='downloadAndRefresh("insertRecord.php?insert=true&table=user_types&user_type_name=New Type")' href='javascript:void(0);'>New Type</a><br />
<?php include 'footer.php'; ?>
