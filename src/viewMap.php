<?php
  include "phpGPS.php";
  $db = new phpGPS_DB();
  $con = $db->connectToDB();
  
  $userGroups = "all";
  
  $title = "View Map";
  include 'header.php';
?>

  <h3><?php echo $title; ?></h3>
  <hr />
  <iframe src="view.php" height="<?php echo phpGPS_Settings::$_windowH + phpGPS_Settings::$_embedAddition ?>" width="<?php echo phpGPS_Settings::$_windowW + phpGPS_Settings::$_embedAddition ?>" seamless></iframe>
  <br />
  <?php if($loginT != null && $loginT->getStatus() == true) { ?>
  Embed:
  <pre>
    <code>
      &lt;iframe src="view.php" height="<?php echo phpGPS_Settings::$_windowH + phpGPS_Settings::$_embedAddition ?>" width="<?php echo phpGPS_Settings::$_windowW + phpGPS_Settings::$_embedAddition ?>" seamless>&lt;/iframe>
    </code>
  </pre>
  <?php } ?>
  <br />
<?php include 'footer.php' ?>
