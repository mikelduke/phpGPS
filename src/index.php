<?php
/* 
 * index.php
 */

include "phpGPS.php";
$db = new phpGPS_DB();
$con = $db->connectToDB();

$userGroups = "all";

$title = "phpGPS";
include 'header.php';

?>
  <a href="generateXML.php">View XML</a><br />
  <a href="viewMap.php">View Map</a><br />
  <hr />
<?php if($loginT != null && $loginT->getStatus() == true) { ?>
  <a href="editOwners.php">Edit Owners</a><br />
  <a href="editDevices.php">Edit Devices</a><br />
  <a href="editTypes.php">Edit Marker Types</a><br />
  <a href="editPaths.php">Edit Paths</a><br />
  <a href="editMarkers.php">Edit Markers</a><br />
  <a href="editMap.php">Edit Map</a><br />
  <hr />
  <a href="addGpsEntry.php?key=<?php echo phpGPS_Settings::$_secretKey ?>&newEntry=Y&gps_device_id=1&gps_type_id=1&gps_path_id=1&gps_date_dt=11-13-2014&gps_date_time=22.31&gps_status=D&gps_latitude=32.86181604&gps_longitude=-96.76354452&gps_altitude=160.0&gps_accuracy=57&gps_name=test spot&gps_comment=test comment&gps_address1=address 1&gps_address2=address 2&gps_address3=address 3&gps_city=city&gps_zipcode=567567&gps_state=state&gps_country=country">Test Update with Info</a><br />
<?php } ?>
<?php include 'footer.php'; ?>
