# phpGPS

A PHP based web service for GPS tracking with Google Maps integration

========

www.mikelduke.com

*******************************************************************************
A PHP based web service for GPS tracking with Google Maps integration

Uses Bootstrap to display the Admin pages:
http://getbootstrap.com/

Get a Google Maps API Key:
https://developers.google.com/maps/signup
https://developers.google.com/maps/documentation/embed/get-api-key

*******************************************************************************

## Features

* Stores GPS coordinates in a MySQL database, generates XML, and draws markers on to a Google Maps map.
* Draw Paths on the map using the GPS entries
* Paths can be colored
* Entries can be linked-to paths, to a device, and a device to an owner
* Multiple GPS entry types with custom icons
* Edit markers on the map view by dragging to new locations
* Shows marker name and comment in small dialog on the map view 
* Can have multiple users to admin the system
* Users can easily add new GPS points either through the admin interface or using any external client capable of making HTTP GET requests.
* On Android, it is simple to create a task using the app [Tasker](https://play.google.com/store/apps/details?id=net.dinglisch.android.taskerm&hl=en) to generate the requests as desired. 
* To embed on a webpage: 
```<iframe src="view.php" height="520" width="520" seamless></iframe>```

Example Update URL:  
    `http://yoursite.com/phpGPS/addGpsEntry.php?key=1234&newEntry=Y&gps_devicename=DeviceID&gps_type_id=1&gps_path_id=1&gps_date_dt=11-13-2014&gps_date_time=22.31&gps_status=&gps_latitude=32&gps_longitude=-96&gps_altitude=160.0&gps_accuracy=57&gps_name=test%20spot&gps_comment=test%20comment&gps_address1=address%201&gps_address2=address%202&gps_address3=address%203&gps_city=city&gps_zipcode=567567&gps_state=state&gps_country=country`

*******************************************************************************

## Important Pages

* phpGPS_Settings.php   - Settings File
* generateXML.php       - Generates XML for use by google maps
* view.php              - Displays the map with markers, embeddable in an iframe

## Requirements

* php 5+
* MySQL
* Webserver

*******************************************************************************

## Install Instructions

1. Extract PHP files to WebHost
1. Create Database for use by phpGPS
1. Enter database settings and other configs in phpGPS_Settings.php or set using environment variables
1. Open phpGPS/install/install.php in browser to create the necessary tables
1. Delete the install folder on WebHost
1. Login as user admin/admin and change the default admin pass
1. Set up owners, devices, paths, etc as desired and start creating markers

### Demo VM

Requires Vagrant + VM Provider like VirtualBox + Google Maps API Key

* Clone this repo and make sure .sh files use UNIX line endings LF
* Set API Key in phpGPS_Settings.php
* Run ```vagrant up```
* Connect at `http://localhost`
* Login with user/password: admin/admin

*******************************************************************************

## Development

Dockerfile and Docker-compose configs are included for easy setup on Linux/Mac. Windows + Docker is not as seamless.

Use `docker-compose up` to automatically load a basic MySQL server and an apache+php server.
The application is mounted as a volume to allow for modifying PHP scripts without restarts.

* Install docker and docker-compose if not already present `sudo apt install docker docker-compose`
* Clone this repo, ensure Unix line endings are used
* Set API key in shell `export DEV_KEY=1234567890`
* Launch with `docker-compose up`
* Connect at `http://localhost:8080`
* Login with user/password: admin/admin
* Control+C or `docker-compose down` to stop
