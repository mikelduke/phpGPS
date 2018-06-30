# phpGPS
A php based webservice for GPS tracking with Google Maps integration
========

www.mikelduke.com


*******************************************************************************
A php based webservice for GPS tracking with Google Maps integration

Uses Bootstrap to display the Admin pages:
http://getbootstrap.com/

Get a Google Maps API Key:
https://developers.google.com/maps/signup
https://developers.google.com/maps/documentation/embed/get-api-key

*******************************************************************************


## Features
* Stores gps coordinates in a MySQL database, generates xml, and draws markers on to a Google Maps map.
* Draw Paths on the map using the gps entries
* Paths can be colored
* Entries can be linked to paths, to a device, and a device to an owner
* Multiple gps entry types with custom icons
* Edit markers on the map view by dragging to new locations
* Shows marker name and comment in small dialog on map view 
* Can have multiple users to admin the system
* Users can easily add new gps points either through the admin interface or using any external client capable of making HTTP GET requests.
* On Android, it is simple to create a task using the app [Tasker](https://play.google.com/store/apps/details?id=net.dinglisch.android.taskerm&hl=en) to generate the requests as desired. 
* To embed on a webpage: 
```<iframe src="view.php" height="520" width="520" seamless></iframe>```

Example Update URL:
	http://yoursite.com/phpGPS/addGpsEntry.php?key=1234&newEntry=Y&gps_devicename=DeviceID&gps_type_id=1&gps_path_id=1&gps_date_dt=11-13-2014&gps_date_time=22.31&gps_status=&gps_latitude=32&gps_longitude=-96&gps_altitude=160.0&gps_accuracy=57&gps_name=test%20spot&gps_comment=test%20comment&gps_address1=address%201&gps_address2=address%202&gps_address3=address%203&gps_city=city&gps_zipcode=567567&gps_state=state&gps_country=country

## Important Pages
* phpGPS_Settings.php   - Settings File
* generateXML.php       - Generates xml for use by google maps
* view.php              - Displays the map with markers, embeddedable in an iframe


## Requirements
* php 5+
* MySQL
* Webserver


## Install Instructions
1. Extract php files to webhost
1. Create Database for use by phpGPS
1. Enter database settings and other config in phpGPS_Settings.php
1. Open phpGPS/install/install.php in browser to create the necessary tables
1. Delete the install folder on webhost
1. Login as user admin/admin and change the default admin pass
1. Set up owners, devices, paths, etc as desired and start creating markers

# Demo VM
Requires Vagrant + VM Provider like VirtualBox + Google Maps API Key
* Clone this repo and make sure .sh files use unix line endings LF
* Set API Key in phpGPS_Settings.php
* Run ```vagrant up```
* Connect at http://localhost
* Login with user/password: admin/admin
