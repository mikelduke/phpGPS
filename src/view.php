<?php
/**
 * view.php
 * 
 * Embeddable page for displaying the google map with markers and paths
 * 
 * Note: Should keep page clean, without text and stuff since it could be 
 * embedded elsewhere. 
 */
  include "phpGPS.php";
  
  $args = "";
  if (isset($_GET['marker_id']) && $_GET['marker_id'] != "") {
    $args = $args . "?marker_id=" . $_GET['marker_id'];
  }
  
  $zoom = "";
  if (isset($_GET['zoom']) && $_GET['zoom'] != "") {
    $zoom = $_GET['zoom'];
    $zoom = phpGPS_DB::cleanInput($zoom);
  }
  
  $center = phpGPS_Settings::$_defaultCenterLat . ', ' . phpGPS_Settings::$_defaultCenterLong;
  if (isset($_GET['center']) && $_GET['center'] != "") {
    $center = $_GET['center'];
    $center = phpGPS_DB::cleanInput($center);
  }
  
  $edit = "false";
  if (isset($_GET['edit']) && $_GET['edit'] == "true") {
    $userGroups = array(
        "admin",
        "users"
    );
    
    $edit = "true";
    
    if (strlen($args > 0)) $args = $args . "&";
    else $args = "?";
    
    $args = $args . "showPathMarkers=true";
  }
?>

<!DOCTYPE html >
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title><?php echo phpGPS_Settings::$_title ?></title>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo phpGPS_Settings::$_devKey ?>"></script>
    <script type="text/javascript" src="phpGPS.js"></script>
    <script type="text/javascript">
    //<![CDATA[

    var map;
    var newMarkers = [];

    var customIcons = {
      restaurant: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png'
      },
      bar: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png'
      }
    };

    function load(center, zoom) {
      if (center == null || center == undefined) center = new google.maps.LatLng(<? echo $center ?>);
      if (zoom == null || zoom == undefined) zoom = <?php echo $zoom == "" ? phpGPS_Settings::$_defaultZoom : $zoom ?>; 
      map = new google.maps.Map(document.getElementById("map"), {
        center: center,
        zoom: zoom,
        mapTypeId: '<? echo phpGPS_Settings::$_defaultMapType ?>'
      });
      var infoWindow = new google.maps.InfoWindow;

      loadMap(map, infoWindow);
    }

    function loadMap(map, infoWindow) {
      //Call download function, then parses xml into marker objects with anon function
      downloadUrl("generateXML.php<?php echo $args?>", function(data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName("marker");
        for (var i = 0; i < markers.length; i++) {
          //load vars from xml
          var name    = markers[i].getAttribute("name");
          var comment = markers[i].getAttribute("comment");
          var address = markers[i].getAttribute("address");
          var point   = new google.maps.LatLng(
              parseFloat(markers[i].getAttribute("lat")),
              parseFloat(markers[i].getAttribute("lng")));
          var typeName = markers[i].getAttribute("type_name");
          var image    = markers[i].getAttribute("image")
          var iconInfo = customIcons[markers[i].getAttribute("custom_icon_name")] || {};
          var accuracy = parseFloat(markers[i].getAttribute("accuracy"));
          var markerID = markers[i].getAttribute("id");
          var pathID = "";
          if (markers[i].hasAttribute("path_id"))
            pathID = markers[i].getAttribute("path_id");

          //create marker
          var marker  = new google.maps.Marker({
            map: map,
            position: point,
            icon: image,
            draggable: <?php echo $edit?>,
            id: markerID,
            path_id: pathID
          });

          //create accuracy circle
          if (accuracy > 0) {
            var circle = {
              strokeColor: '#FF0000',
              strokeOpacity: 0.8,
              strokeWeight: 2,
              fillColor: '#FF0000',
              fillOpacity: 0.35,
              map: map,
              center: point,
              radius: accuracy
            };
            new google.maps.Circle(circle);
          }

          //load and attach marker dialog if comments are present
          if (name.length > 0 || comment.length > 0) {
            var html     = "";
            if (name.length > 0) html = "<b>" + name + "</b>";
            if (name.length > 0 && comment.length > 0) html += "<br/>\n";
            if (comment.length > 0) html += comment;
            bindInfoWindow(marker, map, infoWindow, html);
          }
          
          addDragListener(marker, map);
        }

        //generate paths for display
        var paths = xml.documentElement.getElementsByTagName("path");
        for (var i = 0; i < paths.length; i++) {
          var pathCoords = new Array();
          var coords = paths[i].getElementsByTagName("coord");
          if (coords != undefined) {
            for (var j = 0; j < coords.length; j++) {
              var coord = new google.maps.LatLng(parseFloat(coords[j].getAttribute("lat")), parseFloat(coords[j].getAttribute("lng")));
              pathCoords.push(coord);
            }
            if (pathCoords.length > 0) {
              var path = new google.maps.Polyline({
                path: pathCoords,
                geodesic: true,
                strokeColor: paths[i].getAttribute("color"),
                strokeOpacity: 1.0,
                strokeWeight: 2
              });
              
              path.setMap(map);
            }
          }
        }
      });

      addRightClickListener(map);
    }

    function bindInfoWindow(marker, map, infoWindow, html) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
    }

    function addDragListener(marker, map) {
      if (<?php echo $edit ?>) {
        google.maps.event.addListener(marker, 'dragend', function() {
          //alert("new Coords: " + marker.getPosition().lat() + "," + marker.getPosition().lng());
          if (confirm('Are you sure you want to save the new marker location?')) {
            downloadUrl("updateRecord.php?update=true&table=gps_entries" +
              "&where=gps_entry_id=" + marker.id + "&gps_latitude=" + marker.getPosition().lat() + 
              "&gps_longitude=" + marker.getPosition().lng(), 
              function(data) {
                //alert(data.responseText);
                if (marker.path_id != null)
                  load(marker.getMap().getCenter(), marker.getMap().getZoom());
              }
            );
          }
        });
      }
    }

    function addRightClickListener(map) {
      if (<?php echo $edit ?>) {
        google.maps.event.addListener(map, 'rightclick', function(event) {
          //alert("new Coords: " + marker.getPosition().lat() + "," + marker.getPosition().lng());
          if (true/*confirm('Are you sure you want to add a new marker?')*/) {
            addNewMarker(map, event.latLng);
          }
        });
      }
    }

    function addNewMarker(map, latLng) {
      //alert(latLng);
      var date = new Date();
      var name = date.getFullYear() + "-" + (date.getMonth()+1) + "-" + date.getDate() + " " + 
            date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds() + " " + newMarkers.length;
      var newMarker = new google.maps.Marker({
        position: latLng,
        map: map,
        name: name
      });
      newMarkers[newMarkers.length] = newMarker;
    }

    function saveMarkers() {
      if (newMarkers.length == 0) return;
      if (!confirm('Are you sure you want to save the new markers?')) return;
      
      for (var i = 0; i < newMarkers.length; i++) {
        var lat = newMarkers[i].getPosition().lat();
        var long = newMarkers[i].getPosition().lng();
        //TODO add passing in gps_date column, parsing from current date/time and adding editing for this field on edit marker page
        var url = "insertRecord.php?insert=true&table=gps_entries&gps_latitude=" + lat + "&gps_longitude=" + long + "&gps_entry_date=now()&gps_name=" + newMarkers[i].name;
        //prompt('', url);
        downloadUrl(url, function(data) {});
      }
      newMarkers = [];
      load(map.getCenter(), map.getZoom());
    }

    //]]>

    </script>
  </head>
  <body onload="load()">
    <div id="map" style="width: <?php echo phpGPS_Settings::$_windowW ?>px; height: <?php echo phpGPS_Settings::$_windowH ?>px"></div>
    <?php 
      if ($edit == "true") {
        echo "<a onclick='saveMarkers();' href='javascript:void(0);'>Save</a>";
      }
    ?>
  </body>
</html>