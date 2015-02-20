-- Inserts Test data to database

-- Table of GPS Owners
insert into gps_owner (
  gps_owner_name,
  gps_owner_desc,
  gps_owner_email,
  gps_owner_address,
  gps_owner_website,
  gps_owner_phone
) VALUES (
  'Mikel Duke',
  '',
  '',
  '',
  'www.mikelduke.com',
  ''
);

-- Table of GPS Devices FKed to Owners
insert into gps_device (
  gps_device_local_id,
  gps_device_name,
  gps_device_desc,
  gps_device_comment,
  gps_owner_id
) VALUES (
  'KOT49H',
  'Note 3',
  'Samsung Galaxy Note 3',
  'My current cell phone',
  1
);

-- GPS Icon Types
insert into gps_type (
  gps_type_name,
  gps_type_desc,
  gps_type_image,
  gps_type_icon
) VALUES (
  'restaurant',
  'test restaurant type',
  '',
  'red'
);

-- GPS Path Info
insert into gps_path (
  gps_path_name,
  gps_path_desc,
  gps_type_id,
  gps_path_status,
  gps_path_color
) VALUES (
  'test path 1',
  'testing restaurant paths',
  1,
  NULL,
  'blue'
);

-- GPS Locations
insert into gps_entries (
  gps_entry_date,
  gps_device_id,
  gps_type_id,
  gps_path_id,
  gps_date,
  gps_status,
  gps_latitude,
  gps_longitude,
  gps_altitude,
  gps_accuracy,
  gps_name,
  gps_comment,
  gps_address1,
  gps_address2,
  gps_address3,
  gps_city,
  gps_zipcode,
  gps_state,
  gps_country
) VALUES (
  now(),
  1,
  1,
  1,
  now(),
  NULL,
  32.97572738,
  -96.7106512,
  140.0,
  180,
  'test name',
  'test comment',
  'addy1',
  'addy2',
  'addy3',
  'city',
  '34543',
  'texas',
  'USA'
);

insert into gps_entries (
  gps_entry_date,
  gps_device_id,
  gps_type_id,
  gps_path_id,
  gps_date,
  gps_status,
  gps_latitude,
  gps_longitude,
  gps_altitude,
  gps_accuracy,
  gps_name,
  gps_comment,
  gps_address1,
  gps_address2,
  gps_address3,
  gps_city,
  gps_zipcode,
  gps_state,
  gps_country
) VALUES (
  now(),
  1,
  1,
  1,
  now(),
  NULL,
  32.86172238,
  -96.76385136,
  108.0,
  48,
  'name 2',
  'test comment 2',
  '2-addy1',
  '2-addy2',
  '2-addy3',
  '2-city',
  '2-34543',
  '2-texas',
  '2-USA'
);

insert into user_types (
  user_type_name,
  user_type_desc
) VALUES (
  'admin',
  'Administrator'
);

insert into user_types (
  user_type_name,
  user_type_desc
) VALUES (
  'users',
  'Users'
);

insert into users (
  user_name,
  user_pass,
  user_salt,
  user_type_id
) VALUES (
  'admin',
  '00SLi00eTJrV2',
  '00',
  1
);

-- Test Selection

select 
  * 
from 
  gps_entries ge
  left join gps_device gd on ge.gps_device_id = gd.gps_device_id
where
  ge.gps_status <> 'H' or ge.gps_status IS NULL
order by
  ge.gps_date;

