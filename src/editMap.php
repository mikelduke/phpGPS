<?php
  include "phpGPS.php";
  $db = new phpGPS_DB();
  $con = $db->connectToDB();
  
  $userGroups = array(
      "admin",
      "users"
  );
  
  $title = "Edit Map";
  include 'header.php';
?>

  <h3><?php echo $title; ?></h3>
  <hr />
  <iframe src="view.php?edit=true" height="550" width="550" seamless></iframe>
  <br />
<?php include 'footer.php' ?>
