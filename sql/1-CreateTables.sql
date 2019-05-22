-- Create Table for phpGPS

CREATE TABLE IF NOT EXISTS gps_owner (
  gps_owner_id        int     NOT NULL AUTO_INCREMENT,
  gps_owner_name      varchar(100),
  gps_owner_desc      varchar(500),
  gps_owner_email     varchar(100),
  gps_owner_address   varchar(200),
  gps_owner_website   varchar(200),
  gps_owner_phone     varchar(20),
  PRIMARY KEY (gps_owner_id)
);

CREATE TABLE IF NOT EXISTS gps_device (
  gps_device_id       int          NOT NULL AUTO_INCREMENT,  -- PK Unique Entry ID
  gps_device_local_id varchar(255),  -- Unique Device ID from Device
  gps_device_name     varchar(255),
  gps_device_desc     varchar(255),
  gps_device_comment  varchar(2000),
  gps_owner_id        int,
  PRIMARY KEY (gps_device_id),
  UNIQUE      (gps_device_local_id),
  FOREIGN KEY (gps_owner_id) REFERENCES gps_owner(gps_owner_id)
);

CREATE TABLE IF NOT EXISTS gps_type (
  gps_type_id       int NOT NULL AUTO_INCREMENT,
  gps_type_name     varchar(30),
  gps_type_desc     varchar(255),
  gps_type_image    varchar(2000),
  gps_type_icon     varchar(300),
  PRIMARY KEY       (gps_type_id)
);

CREATE TABLE IF NOT EXISTS gps_path (
  gps_path_id       int NOT NULL AUTO_INCREMENT,
  gps_path_name     varchar(100),
  gps_path_desc     varchar(500),
  gps_type_id       int,                  -- Default Path Icon type
  gps_path_status   varchar(1),
  gps_path_color    varchar(20),
  PRIMARY KEY  (gps_path_id),
  FOREIGN KEY  (gps_type_id) REFERENCES gps_type(gps_type_id)
);

CREATE TABLE IF NOT EXISTS gps_entries (
  gps_entry_id      int      NOT NULL AUTO_INCREMENT,  -- PK Unique Entry ID
  gps_entry_date    DATETIME NOT NULL,     -- Date the entry was made
  gps_device_id     int,                   -- ID field for use with multiple devices
  gps_type_id       int,
  gps_path_id       int,
  gps_path_sequence FLOAT(6, 3),           -- Sequence in path if points are added out of order
  gps_date          DATETIME NOT NULL,     -- Date of the GPS reading
  gps_status        varchar(1),            -- Field to hold status, ie H for Hide
  gps_latitude      FLOAT(12, 8) NOT NULL,
  gps_longitude     FLOAT(12, 8) NOT NULL,
  gps_altitude      FLOAT(6,1),
  gps_accuracy      int,
  gps_name          varchar(100),
  gps_comment       varchar(2000),
  gps_address1      varchar(500),
  gps_address2      varchar(500),
  gps_address3      varchar(500),
  gps_city          varchar(200),
  gps_zipcode       varchar(10),
  gps_state         varchar(50),
  gps_country       varchar(100),
  PRIMARY KEY       (gps_entry_id),
  FOREIGN KEY       (gps_device_id) REFERENCES gps_device(gps_device_id),
  FOREIGN KEY       (gps_type_id) REFERENCES gps_type(gps_type_id),
  FOREIGN KEY       (gps_path_id) REFERENCES gps_path(gps_path_id)
);

CREATE TABLE IF NOT EXISTS user_types (
  user_type_id      int NOT NULL AUTO_INCREMENT,
  user_type_name    varchar(20) NOT NULL,
  user_type_desc    varchar(200),
  PRIMARY KEY       (user_type_id),
  UNIQUE            (user_type_name)
);

CREATE TABLE IF NOT EXISTS users (
  user_id           int NOT NULL AUTO_INCREMENT,
  user_name         varchar(100) NOT NULL,
  user_pass         varchar(100) NOT NULL,
  user_salt         varchar(10)  NOT NULL,
  user_type_id      int,
  PRIMARY KEY       (user_id),
  FOREIGN KEY       (user_type_id) REFERENCES user_types(user_type_id),
  UNIQUE            (user_name)
);
