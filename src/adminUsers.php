<?php
  include "phpGPS.php";
  $db = new phpGPS_DB();
  $con = $db->connectToDB();
  
  $userGroups = array(
      "admin",
  );
  
  function showUsers($con) {
    // Select all the rows in the markers table
    $query = "SELECT \n" .
             "  * \n" .
             "FROM \n" .
             "  users u \n" .
             "  left join user_types ut on u.user_type_id = ut.user_type_id \n" .
             "ORDER BY \n" .
             "  u.user_id;";
    $result = mysqli_query($con, $query);
    if (!$result) {
      die('Invalid query: ' . mysql_error());
    }
    
    echo "<table border='1' style='width:100%' class='table'>\n";
    echo "<thead><tr>\n";
    echo "<th>id</th>\n";
    echo "<th>name</th>\n";
    echo "<th>type</th>\n";
    echo "<th>reset pass</th>\n";
    echo "<th>delete</th>\n";
    echo "</tr></thead>\n";
    
    echo "<tbody>\n";
    while ($row = @mysqli_fetch_assoc($result)){
      $typeSQL = "SELECT user_type_id, user_type_name from user_types order by user_type_id";
      $typesDropDown = buildDropDown($con, $typeSQL, $row['user_type_id'], "type", "users", "user_type_id", "user_type_name", "user_id", $row['user_id'], false);
      
      echo "<td>" . $row['user_id']      . "</td>\n";
      echo getTableRow($row, "user_name", "users", "user_id", "", "");
      echo "<td>" . $typesDropDown  . "</td>\n";
      echo "<td><a onclick='newPass(" . $row['user_id'] . ")' href='javascript:void(0);'>reset pass</a></td>\n";
      echo "<td><a onclick='deleteRecord(\"users\", \"user_id=" . $row['user_id'] . "\", true)' href='javascript:void(0);'>[X]</a></td>\n";
      echo "</tr>\n";
    }
    echo "</tbody>\n";
    echo "</table>\n";
  }
?>
<?php 
  $title = "Edit Users";
  include 'header.php'; 
?>
<h3><?php echo $title; ?></h3>
<hr />
<? showUsers($con); ?>
<a onclick='downloadAndRefresh("adminAddUser.php?user_name=New User")' href='javascript:void(0);'>New User</a><br />
<?php include 'footer.php'; ?>
