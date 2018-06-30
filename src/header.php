<?php
  include "login.php";
   
  if (isset($_GET['login'])) {
    login::showLogin();
    exit();
  }
  
  $type = "";
  $loginT = null;
  if (isset($userGroups) && isset($con)) {
    $loginT = new login($con, $userGroups);
    
    if (!$loginT->userStatus && $userGroups != "all") {
      echo "Login Failed<br>";
      login::showLogin();
      exit();
    } else {
      $type = $loginT->getType();
    }
  } else if (isset($userGroups)) {
    exit("Error connecting to user system");
  }
  
  function showActive($page) {
    $activePage = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
    //echo "\n\n$page:$activePage\n";
  
    $active = " class='active'";
  
    if ($page == $activePage) return $active;
    else return "";
  }
?>
<!DOCTYPE html >
<html lang="en">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="phpGPS, phpGPS Demo, gps, php, plugin, google maps" />
    <meta name="description" content="phpGPS Demo - Track yourself using Google Maps and a cellphone" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title; ?></title>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <script type="text/javascript" src="phpGPS.js"></script>
  </head>
<body>
  <nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php">phpGPS</a>
      </div>
  
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li<?php echo showActive("viewMap.php"); ?>><a href="viewMap.php">View <span class="sr-only">(current)</span></a></li>
<?php if($loginT != null && $loginT->getStatus() == true) { ?>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Edit <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <li<?php echo showActive("editMap.php"); ?>><a href="editMap.php">Edit Map</a></li>
              <li class="divider"></li>
              <li<?php echo showActive("editMarkers.php"); ?>><a href="editMarkers.php">Edit Markers</a></li>
              <li<?php echo showActive("editPaths.php"); ?>><a href="editPaths.php">Edit Paths</a></li>
              <li<?php echo showActive("editTypes.php"); ?>><a href="editTypes.php">Edit Marker Types</a></li>
              <li<?php echo showActive("editDevices.php"); ?>><a href="editDevices.php">Edit Devices</a></li>
              <li<?php echo showActive("editOwners.php"); ?>><a href="editOwners.php">Edit Owners</a></li>
              <li class="divider"></li>
              <li<?php echo showActive("generateXML.php"); ?>><a href="generateXML.php">View XML</a></li>
            </ul>
          </li>
<?php } ?>
<?php if ($loginT != null && $loginT->getType() == "admin") {?>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Admin <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <li<?php echo showActive("adminUsers.php"); ?>><a href="adminUsers.php">Users</a></li>
              <li<?php echo showActive("adminGroups.php"); ?>><a href="adminGroups.php">Groups</a></li>
            </ul>
          </li>
<?php } ?>
<?php if($loginT != null && $loginT->getStatus() == true) { ?>
<li><a href="index.php?out=1">Logout <span class="sr-only">(current)</span></a></li>
<?php } else { ?>
<li><a href="index.php?login=1">Login <span class="sr-only">(current)</span></a></li>
<?php } ?>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>