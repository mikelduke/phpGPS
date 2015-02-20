<?php
  include "login.php";
  include "phpGPS.php";
  $db = new phpGPS_DB();
  $con = $db->connectToDB();
  
  $userGroups = array(
      "admin",
  );
  
  $loginT = null;
  if (isset($con)) {
    $loginT = new login($con, $userGroups);
  
    if (!$loginT->userStatus && $userGroups != "admin") {
      echo "Login Failed<br>";
      login::showLogin();
      exit();
    } else {
      $type = $loginT->getType();
    }
  }
  
  //TODO add more secure default pass generation
  $defaultPass = "password";
  
  $username = "";
  $usersalt = "";
  $userpass = "";
  
  if (isset($_GET['user_name'])) {
    $username = $_GET['user_name'];
  }
  
  $usersalt = generateRandomString(10);
  $userpass = crypt($defaultPass, $usersalt);
  
  if ($username != "" && $usersalt != "" && $userpass != "") {
    $userSql = "insert into users (user_name, user_pass, user_salt, user_type_id) VALUES ('$username', '$userpass', '$usersalt', 1)";
    
    mysqli_query($con, $userSql)
    or die(mysqli_error($con));
    echo "Record Inserted";
  }
?>