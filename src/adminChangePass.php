<?php
  include "login.php";
  include "phpGPS.php";
  $db = new phpGPS_DB();
  $con = $db->connectToDB();
  
  //Check login info
  $userGroups = array(
      "admin",
      "users"
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
  
  //Load Vars from URL
  if (!isset($_GET['user']) || $_GET['user'] == "") {
    exit("user not set");
  }
  $userToChange = phpGPS_DB::cleanInput($_GET['user']);
  
  if (!isset($_GET['oldpass']) || $_GET['oldpass'] == "") {
    if ($loginT->getType() != "admin")
      exit("oldpass not set");
  }
  $oldPass = phpGPS_DB::cleanInput($_GET['oldpass']);
  
  if (!isset($_GET['newpass']) || $_GET['newpass'] == "") {
    exit("newpass not set");
  }
  $newPass = phpGPS_DB::cleanInput($_GET['newpass']);
  
  //if admin, or user with valid username and valid oldpass
  if ($loginT->getType() == "admin" 
      || ($loginT->getType() == "user" && $userToChange == $loginT->getUserId() 
          && login::checkPassForUser($con, $userToChange, $oldPass))) {
    //update pass
    $usersalt = generateRandomString(10);
    
    $newEncryptedPass = crypt($newPass, $usersalt);
    
    $updateSql = "update users set user_salt = '$usersalt', user_pass='$newEncryptedPass' where user_id='$userToChange'";
    mysqli_query($con, $updateSql)
      or die(mysqli_error($con));
  }
?>