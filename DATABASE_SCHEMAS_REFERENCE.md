# Database Table Schemas Reference

## dvi_itinerary_route_hotspot_details

Main table for timeline segments.

```sql
CREATE TABLE `dvi_itinerary_route_hotspot_details` (
  `route_hotspot_ID` bigint NOT NULL AUTO_INCREMENT,
  `itinerary_plan_ID` bigint NOT NULL,
  `itinerary_route_ID` bigint NOT NULL,
  `item_type` int NOT NULL DEFAULT 0 COMMENT '1-Refreshment|2-DirectTravel|3-SiteSeeingTravel|4-Hotspots|5-ToHotel|6-ReturnHotel|7-ReturnDeparture',
  `hotspot_order` int NOT NULL DEFAULT 0,
  `hotspot_ID` bigint NOT NULL DEFAULT 0,
  
  -- Entry costs (per hotspot, aggregated from pricebook)
  `hotspot_adult_entry_cost` decimal(10,2) DEFAULT 0,
  `hotspot_child_entry_cost` decimal(10,2) DEFAULT 0,
  `hotspot_infant_entry_cost` decimal(10,2) DEFAULT 0,
  `hotspot_foreign_adult_entry_cost` decimal(10,2) DEFAULT 0,
  `hotspot_foreign_child_entry_cost` decimal(10,2) DEFAULT 0,
  `hotspot_foreign_infant_entry_cost` decimal(10,2) DEFAULT 0,
  
  -- Total cost for all guests
  `hotspot_amout` decimal(10,2) DEFAULT 0,
  
  -- Travel timing
  `hotspot_traveling_time` time DEFAULT NULL COMMENT 'HH:MM:SS duration',
  `itinerary_travel_type_buffer_time` time DEFAULT NULL COMMENT 'Buffer after travel',
  
  -- Distance
  `hotspot_travelling_distance` varchar(200) DEFAULT NULL COMMENT 'Distance in km',
  
  -- Timeline boundaries
  `hotspot_start_time` time DEFAULT NULL COMMENT 'Segment start time',
  `hotspot_end_time` time DEFAULT NULL COMMENT 'Segment end time',
  
  -- Flags
  `allow_break_hours` int DEFAULT 0 COMMENT '1 if break between travel and hotspot',
  `allow_via_route` int DEFAULT 0 COMMENT '1 if via-route included',
  `via_location_name` varchar(255) DEFAULT NULL,
  `hotspot_plan_own_way` int DEFAULT 0,
  
  -- Audit
  `createdby` bigint NOT NULL DEFAULT 1,
  `createdon` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedon` datetime DEFAULT NULL,
  `status` int DEFAULT 1,
  `deleted` int DEFAULT 0,
  
  PRIMARY KEY (`route_hotspot_ID`),
  KEY `idx_plan` (`itinerary_plan_ID`),
  KEY `idx_route` (`itinerary_route_ID`),
  KEY `idx_hotspot` (`hotspot_ID`),
  KEY `idx_hotspot_order` (`hotspot_order`)
);
```

---

## dvi_itinerary_route_hotspot_parking_charge

Parking charges by hotspot and vehicle type.

```sql
CREATE TABLE `dvi_itinerary_route_hotspot_parking_charge` (
  `parking_charge_ID` bigint NOT NULL AUTO_INCREMENT,
  `itinerary_plan_ID` bigint NOT NULL,
  `itinerary_route_ID` bigint NOT NULL,
  `hotspot_ID` bigint NOT NULL,
  
  -- Vehicle info
  `vehicle_type` int NOT NULL COMMENT '1=Car|2=Bus|etc',
  `vehicle_qty` int DEFAULT 1,
  
  -- Charge
  `parking_charges_amt` decimal(10,2) DEFAULT 0,
  
  -- Audit
  `createdby` bigint NOT NULL DEFAULT 1,
  `createdon` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedon` datetime DEFAULT NULL,
  `status` int DEFAULT 1,
  `deleted` int DEFAULT 0,
  
  PRIMARY KEY (`parking_charge_ID`),
  KEY `idx_plan` (`itinerary_plan_ID`),
  KEY `idx_route` (`itinerary_route_ID`),
  KEY `idx_hotspot` (`hotspot_ID`),
  UNIQUE KEY `uniq_plan_route_hotspot_vehicle` 
    (`itinerary_plan_ID`, `itinerary_route_ID`, `hotspot_ID`, `vehicle_type`)
);
```

---

## dvi_itinerary_plan_details

Plan header with global settings.

```sql
CREATE TABLE `dvi_itinerary_plan_details` (
  `itinerary_plan_ID` bigint NOT NULL AUTO_INCREMENT,
  
  -- Dates
  `trip_start_date_and_time` datetime NOT NULL,
  `trip_end_date_and_time` datetime NOT NULL,
  
  -- Departure/Arrival
  `departure_type` int DEFAULT 0 COMMENT '1=Home|2=Airport|etc',
  `departure_location` varchar(255) DEFAULT NULL,
  `arrival_type` int DEFAULT 0,
  
  -- Guests
  `total_adult` int DEFAULT 0,
  `total_children` int DEFAULT 0,
  `total_infants` int DEFAULT 0,
  `nationality` int DEFAULT 0 COMMENT 'Nationality ID',
  `itinerary_preference` int DEFAULT 0 COMMENT 'Trip preference ID',
  
  -- Tickets
  `entry_ticket_required` int DEFAULT 0 COMMENT '1=Yes|0=No',
  
  -- Hotel
  `no_of_nights` int DEFAULT 0,
  `preferred_room_count` int DEFAULT 0,
  `total_extra_bed` int DEFAULT 0,
  `total_child_with_bed` int DEFAULT 0,
  `total_child_without_bed` int DEFAULT 0,
  
  -- Meals
  `meal_plan_breakfast` int DEFAULT 0,
  `meal_plan_lunch` int DEFAULT 0,
  `meal_plan_dinner` int DEFAULT 0,
  
  -- Budget
  `expecting_budget` decimal(12,2) DEFAULT 0,
  `quotation_status` int DEFAULT 0 COMMENT '0=Draft|1=Quoted|2=Approved',
  
  -- Audit
  `createdby` bigint NOT NULL DEFAULT 1,
  `createdon` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedon` datetime DEFAULT NULL,
  `status` int DEFAULT 1,
  `deleted` int DEFAULT 0,
  
  PRIMARY KEY (`itinerary_plan_ID`),
  KEY `idx_trip_dates` (`trip_start_date_and_time`, `trip_end_date_and_time`)
);
```

---

## dvi_itinerary_route_details

Per-day route information.

```sql
CREATE TABLE `dvi_itinerary_route_details` (
  `itinerary_route_ID` bigint NOT NULL AUTO_INCREMENT,
  `itinerary_plan_ID` bigint NOT NULL,
  
  -- Date & Time
  `itinerary_route_date` date NOT NULL,
  `route_start_time` time DEFAULT NULL COMMENT 'Route begins',
  `route_end_time` time DEFAULT NULL COMMENT 'Route ends',
  
  -- Location
  `location_id` bigint DEFAULT NULL,
  `location_name` varchar(255) DEFAULT NULL,
  `next_visiting_location` varchar(255) DEFAULT NULL,
  `direct_to_next_visiting_place` int DEFAULT 0,
  
  -- Audit
  `createdby` bigint NOT NULL DEFAULT 1,
  `createdon` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedon` datetime DEFAULT NULL,
  `status` int DEFAULT 1,
  `deleted` int DEFAULT 0,
  
  PRIMARY KEY (`itinerary_route_ID`),
  KEY `idx_plan_date` (`itinerary_plan_ID`, `itinerary_route_date`),
  KEY `idx_location` (`location_id`)
);
```

---

## dvi_hotspot_place

Hotspot master data.

```sql
CREATE TABLE `dvi_hotspot_place` (
  `hotspot_ID` bigint NOT NULL AUTO_INCREMENT,
  
  -- Basic info
  `hotspot_name` varchar(255) NOT NULL,
  `hotspot_location` varchar(255) DEFAULT NULL,
  `hotspot_city` varchar(100) DEFAULT NULL,
  
  -- Location
  `location_id` bigint DEFAULT NULL COMMENT 'City/region ID',
  `hotspot_latitude` decimal(10,8) DEFAULT NULL,
  `hotspot_longitude` decimal(11,8) DEFAULT NULL,
  
  -- Timing
  `default_stay_time_in_mins` int DEFAULT 60 COMMENT 'Recommended stay (minutes)',
  
  -- Priority
  `priority` int DEFAULT 999,
  
  -- Audit
  `createdby` bigint NOT NULL DEFAULT 1,
  `createdon` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedon` datetime DEFAULT NULL,
  `status` int DEFAULT 1,
  `deleted` int DEFAULT 0,
  
  PRIMARY KEY (`hotspot_ID`),
  KEY `idx_location` (`location_id`),
  KEY `idx_priority` (`priority`)
);
```

---

## dvi_stored_locations

Distance matrix between locations.

```sql
CREATE TABLE `dvi_stored_locations` (
  `location_ID` bigint NOT NULL AUTO_INCREMENT,
  
  -- Source
  `source_location` varchar(255) NOT NULL,
  `source_location_city` varchar(100) DEFAULT NULL,
  `source_location_state` varchar(100) DEFAULT NULL,
  `source_location_latitude` decimal(10,8) DEFAULT NULL,
  `source_location_longitude` decimal(11,8) DEFAULT NULL,
  
  -- Destination
  `destination_location` varchar(255) NOT NULL,
  `destination_location_city` varchar(100) DEFAULT NULL,
  `destination_location_state` varchar(100) DEFAULT NULL,
  `destination_location_latitude` decimal(10,8) DEFAULT NULL,
  `destination_location_longitude` decimal(11,8) DEFAULT NULL,
  
  -- Metrics
  `distance` decimal(8,2) DEFAULT NULL COMMENT 'Distance in km',
  `duration` varchar(50) DEFAULT NULL COMMENT 'Travel time text or seconds',
  `location_description` text DEFAULT NULL,
  
  -- Audit
  `createdby` bigint NOT NULL DEFAULT 1,
  `createdon` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedon` datetime DEFAULT NULL,
  `status` int DEFAULT 1,
  `deleted` int DEFAULT 0,
  
  PRIMARY KEY (`location_ID`),
  UNIQUE KEY `uniq_source_dest` (`source_location`, `destination_location`),
  KEY `idx_distance` (`distance`)
);
```

---

## dvi_hotspot_entry_pricebook

Entry costs by hotspot, nationality, and preference.

```sql
CREATE TABLE `dvi_hotspot_entry_pricebook` (
  `entry_pricebook_ID` bigint NOT NULL AUTO_INCREMENT,
  `hotspot_ID` bigint NOT NULL,
  
  -- Traveller type
  `nationality` int DEFAULT NULL COMMENT 'Nationality ID',
  `itinerary_pref` int DEFAULT NULL COMMENT 'Itinerary preference ID',
  
  -- Costs
  `adult_entry_cost` decimal(10,2) DEFAULT 0,
  `child_entry_cost` decimal(10,2) DEFAULT 0,
  `infant_entry_cost` decimal(10,2) DEFAULT 0,
  `foreign_adult_entry_cost` decimal(10,2) DEFAULT 0,
  `foreign_child_entry_cost` decimal(10,2) DEFAULT 0,
  `foreign_infant_entry_cost` decimal(10,2) DEFAULT 0,
  
  -- Audit
  `createdby` bigint NOT NULL DEFAULT 1,
  `createdon` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedon` datetime DEFAULT NULL,
  `status` int DEFAULT 1,
  `deleted` int DEFAULT 0,
  
  PRIMARY KEY (`entry_pricebook_ID`),
  KEY `idx_hotspot` (`hotspot_ID`),
  KEY `idx_nationality_pref` (`nationality`, `itinerary_pref`)
);
```

---

## dvi_hotspot_parking_charge_master

Parking charges by hotspot and vehicle type.

```sql
CREATE TABLE `dvi_hotspot_parking_charge_master` (
  `parking_master_ID` bigint NOT NULL AUTO_INCREMENT,
  `hotspot_ID` bigint NOT NULL,
  `vehicle_type` int NOT NULL COMMENT '1=Car|2=Bus|3=Tempo|etc',
  
  -- Charge per vehicle
  `parking_charge` decimal(10,2) DEFAULT 0,
  
  -- Audit
  `createdby` bigint NOT NULL DEFAULT 1,
  `createdon` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedon` datetime DEFAULT NULL,
  `status` int DEFAULT 1,
  `deleted` int DEFAULT 0,
  
  PRIMARY KEY (`parking_master_ID`),
  KEY `idx_hotspot_vehicle` (`hotspot_ID`, `vehicle_type`),
  UNIQUE KEY `uniq_hotspot_vehicle` (`hotspot_ID`, `vehicle_type`)
);
```

---

## dvi_itinerary_vehicle_details

Vehicles assigned to an itinerary plan.

```sql
CREATE TABLE `dvi_itinerary_vehicle_details` (
  `vehicle_detail_ID` bigint NOT NULL AUTO_INCREMENT,
  `itinerary_plan_ID` bigint NOT NULL,
  `vehicle_type_id` int NOT NULL,
  
  -- May contain multiple rows of same vehicle_type for multiple vehicles
  
  -- Audit
  `createdby` bigint NOT NULL DEFAULT 1,
  `createdon` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedon` datetime DEFAULT NULL,
  `status` int DEFAULT 1,
  `deleted` int DEFAULT 0,
  
  PRIMARY KEY (`vehicle_detail_ID`),
  KEY `idx_plan` (`itinerary_plan_ID`),
  KEY `idx_vehicle_type` (`vehicle_type_id`)
);
```

---

## dvi_itinerary_plan_hotel_details

Hotels selected for the plan.

```sql
CREATE TABLE `dvi_itinerary_plan_hotel_details` (
  `hotel_detail_ID` bigint NOT NULL AUTO_INCREMENT,
  `itinerary_plan_id` bigint NOT NULL,
  `itinerary_route_id` bigint NOT NULL,
  
  `hotel_id` bigint NOT NULL,
  
  -- Room details
  `room_id` bigint DEFAULT NULL,
  `room_type_id` int DEFAULT NULL,
  `no_of_rooms` int DEFAULT 1,
  `total_room_rate` decimal(12,2) DEFAULT 0,
  
  -- Audit
  `createdby` bigint NOT NULL DEFAULT 1,
  `createdon` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedon` datetime DEFAULT NULL,
  `status` int DEFAULT 1,
  `deleted` int DEFAULT 0,
  
  PRIMARY KEY (`hotel_detail_ID`),
  KEY `idx_plan_route` (`itinerary_plan_id`, `itinerary_route_id`),
  KEY `idx_hotel` (`hotel_id`)
);
```

---

## dvi_hotel (Master)

Hotel information.

```sql
CREATE TABLE `dvi_hotel` (
  `hotel_id` bigint NOT NULL AUTO_INCREMENT,
  
  `hotel_name` varchar(255) NOT NULL,
  `hotel_category` varchar(50) DEFAULT NULL COMMENT '1-Star|2-Star|etc',
  `hotel_city` varchar(100) DEFAULT NULL,
  `hotel_location` varchar(255) DEFAULT NULL,
  `hotel_latitude` decimal(10,8) DEFAULT NULL,
  `hotel_longitude` decimal(11,8) DEFAULT NULL,
  
  -- Audit
  `createdby` bigint NOT NULL DEFAULT 1,
  `createdon` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedon` datetime DEFAULT NULL,
  `status` int DEFAULT 1,
  `deleted` int DEFAULT 0,
  
  PRIMARY KEY (`hotel_id`),
  KEY `idx_city` (`hotel_city`)
);
```

---

## Query Templates

### Fetch Hotspots for Route
```sql
SELECT h.hotspot_ID, h.hotspot_name, h.default_stay_time_in_mins, h.priority
FROM dvi_hotspot_place h
WHERE h.location_id = :route_location_id
  AND h.deleted = 0 AND h.status = 1
ORDER BY h.priority ASC, h.hotspot_ID ASC
```

### Fetch Entry Costs for Hotspot
```sql
SELECT
  adult_entry_cost,
  child_entry_cost,
  infant_entry_cost,
  foreign_adult_entry_cost,
  foreign_child_entry_cost,
  foreign_infant_entry_cost
FROM dvi_hotspot_entry_pricebook
WHERE hotspot_ID = :hotspot_id
  AND (nationality = :nationality OR nationality IS NULL)
  AND (itinerary_pref = :itinerary_preference OR itinerary_pref IS NULL)
  AND deleted = 0 AND status = 1
LIMIT 1
```

### Fetch Parking for Hotspot+Vehicle
```sql
SELECT parking_charge
FROM dvi_hotspot_parking_charge_master
WHERE hotspot_ID = :hotspot_id
  AND vehicle_type = :vehicle_type
  AND deleted = 0 AND status = 1
```

### Fetch Vehicles for Plan
```sql
SELECT vehicle_type_id, COUNT(*) as qty
FROM dvi_itinerary_vehicle_details
WHERE itinerary_plan_ID = :plan_id
  AND deleted = 0 AND status = 1
GROUP BY vehicle_type_id
```

