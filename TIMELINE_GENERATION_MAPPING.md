# Timeline Generation: PHP → NestJS Mapping

## Overview

For each itinerary plan, the system generates a **timeline** of segments stored in two main tables:

1. **`dvi_itinerary_route_hotspot_details`** – Timeline rows (travel, hotspot, hotel, etc.)
2. **`dvi_itinerary_route_hotspot_parking_charge`** – Parking charges by hotspot+vehicle

This document maps PHP's `includeHotspotInItinerary()` logic to NestJS builder classes.

---

## Core Data Sources

### Plan Header
**Table**: `dvi_itinerary_plan_details`

```sql
SELECT
  itinerary_plan_ID,
  departure_type,
  departure_location,
  entry_ticket_required,
  trip_start_date_and_time,
  trip_end_date_and_time,
  total_adult,
  total_children,
  total_infants,
  nationality,
  itinerary_preference
FROM dvi_itinerary_plan_details
WHERE deleted = 0 AND itinerary_plan_ID = :planId
```

### Route Details (Per-day)
**Table**: `dvi_itinerary_route_details`

```sql
SELECT
  itinerary_route_ID,
  itinerary_route_date,
  location_id,
  location_name,
  next_visiting_location,
  route_start_time,
  route_end_time
FROM dvi_itinerary_route_details
WHERE deleted = 0 AND status = 1 AND itinerary_plan_ID = :planId
ORDER BY itinerary_route_date ASC, itinerary_route_ID ASC
```

### Hotspots Available for Route Location
**Table**: `dvi_hotspot_place`

```sql
SELECT
  hotspot_ID,
  hotspot_location,
  hotspot_latitude,
  hotspot_longitude,
  default_stay_time_in_mins,
  priority
FROM dvi_hotspot_place
WHERE deleted = 0 AND status = 1 AND location_id = :route_location_id
ORDER BY priority ASC
```

### Distance & Travel Time
**Table**: `dvi_stored_locations`

```sql
SELECT
  location_ID,
  distance,
  duration,
  source_location_latitude,
  source_location_longitude,
  destination_location_latitude,
  destination_location_longitude
FROM dvi_stored_locations
WHERE source_location = :from_city AND destination_location = :to_city
```

Then applies **Haversine** calculation if needed (e.g., route to hotspot).

### Entry Costs
**Table**: `dvi_hotspot_entry_pricebook` (or similar)

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
  AND nationality = :nationality
  AND itinerary_pref = :itinerary_preference
  AND deleted = 0 AND status = 1
```

### Parking Charges
**Table**: `dvi_hotspot_parking_charge_master` (or similar)

```sql
SELECT
  parking_charge
FROM dvi_hotspot_parking_charge_master
WHERE hotspot_ID = :hotspot_id
  AND vehicle_type = :vehicle_type_id
  AND deleted = 0 AND status = 1
```

### Vehicle Details for Plan
**Table**: `dvi_itinerary_vehicle_details`

```sql
SELECT
  vehicle_type_id,
  COUNT(*) as vehicle_qty
FROM dvi_itinerary_vehicle_details
WHERE itinerary_plan_ID = :planId
  AND deleted = 0 AND status = 1
GROUP BY vehicle_type_id
```

### Hotel Details for Route
**Table**: `dvi_itinerary_plan_hotel_details` (joined with hotel master)

```sql
SELECT
  hotel_id,
  hotel_name,
  hotel_latitude,
  hotel_longitude,
  hotel_city
FROM dvi_itinerary_plan_hotel_details
LEFT JOIN dvi_hotel ON dvi_hotel.hotel_id = dvi_itinerary_plan_hotel_details.hotel_id
WHERE itinerary_plan_id = :planId
  AND itinerary_route_id = :routeId
  AND deleted = 0 AND status = 1
```

---

## dvi_itinerary_route_hotspot_details Field Mapping

### Per-`item_type` Logic

#### **item_type = 1 (Refreshment)**

Inserted at the **start of each route** to represent a break/rest.

| Field | Source | Value | Notes |
|-------|--------|-------|-------|
| `itinerary_plan_ID` | Input | `:planId` | From itinerary plan |
| `itinerary_route_ID` | `dvi_itinerary_route_details` | `:routeId` | From route loop |
| `item_type` | Constant | `1` | Refreshment segment |
| `hotspot_order` | Loop counter | `1, 2, 3, ...` | Sequential per route |
| `hotspot_ID` | N/A | `0` | Not a hotspot location |
| `hotspot_traveling_time` | Settings/Config | `01:00:00` | From global settings or fixed 1h |
| `itinerary_travel_type_buffer_time` | N/A | `00:00:00` | No buffer for break |
| `hotspot_travelling_distance` | N/A | `NULL` | Stationary |
| `hotspot_start_time` | `dvi_itinerary_route_details` | `route_start_time` | e.g. `09:00:00` |
| `hotspot_end_time` | Calculated | `start_time + 01:00:00` | e.g. `10:00:00` |
| `allow_break_hours` | UI/Settings | `0` | This row IS the break |
| `allow_via_route` | UI | `0` | N/A |
| `hotspot_plan_own_way` | UI | `0` | N/A |
| `createdby` | Session | `1` (example) | User/admin ID |
| `status` | Constant | `1` | Active |
| `deleted` | Constant | `0` | Not deleted |

**NestJS Builder**: `RefreshmentBuilder.build()`

---

#### **item_type = 3 (Travel Segment)**

Inserted for **travel between locations** (hotspot to hotspot, route to hotspot, etc.).

| Field | Source | Logic | Notes |
|-------|--------|-------|-------|
| `itinerary_plan_ID` | Input | `:planId` | From itinerary plan |
| `itinerary_route_ID` | Loop | `:routeId` | From route loop |
| `item_type` | Constant | `3` | Travel segment |
| `hotspot_order` | Loop counter | Incremented | `1, 2, 3, ...` per route |
| `hotspot_ID` | Master | `:hotspot_id` or `0` | Hotspot ID if destination is hotspot; 0 otherwise |
| `hotspot_traveling_time` | `calculateDistanceAndDuration()` | e.g. `00:34:00` | Travel duration (hours + minutes) |
| `itinerary_travel_type_buffer_time` | Global settings | `00:15:00` (example) | Buffer after travel (local vs outstation) |
| `hotspot_travelling_distance` | `calculateDistanceAndDuration()` | e.g. `8.42` | Distance in km |
| `hotspot_start_time` | Calculated | Previous `end_time` | e.g. `09:00:00` |
| `hotspot_end_time` | Calculated | `start_time + travel_time + buffer` | e.g. `09:34:00` |
| `allow_break_hours` | Data | `0` or `1` | 1 if a gap exists before hotspot opens |
| `allow_via_route` | UI | `0` or `1` | If route includes via-points |
| `via_location_name` | `dvi_stored_location_via_routes` | Name string or `NULL` | Via-route city if applicable |
| `createdby` | Session | `1` | User/admin ID |
| `status` | Constant | `1` | Active |
| `deleted` | Constant | `0` | Not deleted |

**NestJS Builder**: `TravelSegmentBuilder.buildTravelSegment()`

**Calculation Logic**:
```
1. Get source location (previous waypoint lat/lon)
2. Get destination location (hotspot or hotel lat/lon)
3. Determine travel_location_type (1=local, 2=outstation)
4. Call calculateDistanceAndDuration(src_lat, src_lon, dest_lat, dest_lon, type)
5. Extract: distance (km), duration (HH:MM:SS)
6. Add buffer_time based on type
7. Calculate end_time = start_time + travel_time + buffer_time
```

---

#### **item_type = 4 (Hotspot Visit)**

Inserted to record **staying at a hotspot location**.

| Field | Source | Logic | Notes |
|-------|--------|-------|-------|
| `itinerary_plan_ID` | Input | `:planId` | From itinerary plan |
| `itinerary_route_ID` | Loop | `:routeId` | From route loop |
| `item_type` | Constant | `4` | Hotspot/site visit |
| `hotspot_order` | Loop counter | Incremented | `1, 2, 3, ...` per route |
| `hotspot_ID` | Master | `:hotspot_id` | From `dvi_hotspot_place` |
| `hotspot_adult_entry_cost` | `dvi_hotspot_entry_pricebook` | e.g. `500` | Adult ticket price (₹ or currency) |
| `hotspot_child_entry_cost` | Pricebook | e.g. `300` | Child ticket price |
| `hotspot_infant_entry_cost` | Pricebook | e.g. `0` | Infant ticket (often free) |
| `hotspot_foreign_adult_entry_cost` | Pricebook | e.g. `1000` | Foreign adult price |
| `hotspot_foreign_child_entry_cost` | Pricebook | e.g. `600` | Foreign child price |
| `hotspot_foreign_infant_entry_cost` | Pricebook | e.g. `0` | Foreign infant price |
| `hotspot_amout` | Calculated | Sum of all tickets | e.g. `(2×500) + (1×300) + (0×0) = 1300` |
| `hotspot_traveling_time` | `dvi_hotspot_place` | e.g. `01:00:00` | `default_stay_time_in_mins` converted to time |
| `itinerary_travel_type_buffer_time` | N/A | `00:00:00` | No buffer at hotspot |
| `hotspot_travelling_distance` | N/A | `NULL` | Stationary |
| `hotspot_start_time` | Calculated | Previous `end_time` after wait | e.g. `11:00:00` |
| `hotspot_end_time` | Calculated | `start_time + stay_time` | e.g. `12:00:00` |
| `allow_break_hours` | Data | `0` or `1` | 1 if wait before hotspot opens |
| `allow_via_route` | N/A | `0` | N/A for hotspots |
| `createdby` | Session | `1` | User/admin ID |
| `status` | Constant | `1` | Active |
| `deleted` | Constant | `0` | Not deleted |

**NestJS Builder**: `HotspotSegmentBuilder.build()`

**Calculation Logic**:
```
1. Get hotspot details (lat, lon, default_stay_time_in_mins)
2. Convert stay_time_mins to HH:MM:SS format
3. Look up entry costs from pricebook (nationality + pref based)
4. Calculate hotspot_amout = sum of all tickets
5. Calculate end_time = start_time + stay_time
6. If entry_ticket_required = 0: all costs = 0
```

---

#### **item_type = 5 (Travel to Hotel)**

Inserted for **travel from last waypoint to hotel location** for that route's date.

| Field | Source | Logic | Notes |
|-------|--------|-------|-------|
| `itinerary_plan_ID` | Input | `:planId` | From itinerary plan |
| `itinerary_route_ID` | Loop | `:routeId` | From route loop |
| `item_type` | Constant | `5` | Travel to hotel |
| `hotspot_order` | Loop counter | Incremented | Per-route sequence |
| `hotspot_ID` | N/A | `0` | Not a hotspot |
| `hotspot_traveling_time` | `calculateDistanceAndDuration()` | e.g. `00:06:00` | Hotel travel duration |
| `itinerary_travel_type_buffer_time` | Global settings | `00:00:00` | Usually no buffer at hotel |
| `hotspot_travelling_distance` | Distance calc | e.g. `5.67` | Distance to hotel (km) |
| `hotspot_start_time` | Calculated | Previous `end_time` | e.g. `15:30:00` |
| `hotspot_end_time` | Calculated | `start_time + travel_time` | e.g. `15:36:00` |
| `createdby` | Session | `1` | User/admin ID |
| `status` | Constant | `1` | Active |
| `deleted` | Constant | `0` | Not deleted |

**NestJS Builder**: `HotelTravelBuilder.buildToHotel()`

**Hotel Lookup**:
```sql
SELECT hotel_id, hotel_latitude, hotel_longitude
FROM dvi_itinerary_plan_hotel_details
WHERE itinerary_plan_id = :planId
  AND itinerary_route_id = :routeId
  AND deleted = 0
```

---

#### **item_type = 6 (Return to Hotel / Hotel Closure)**

Inserted as a **closing segment at the hotel** (often 0 distance, 0 time, just marking end of route).

| Field | Source | Logic | Notes |
|-------|--------|-------|-------|
| `itinerary_plan_ID` | Input | `:planId` | From itinerary plan |
| `itinerary_route_ID` | Loop | `:routeId` | From route loop |
| `item_type` | Constant | `6` | Return to hotel (closure) |
| `hotspot_order` | Loop counter | Incremented | Per-route sequence |
| `hotspot_ID` | N/A | `0` | Not a hotspot |
| `hotspot_traveling_time` | Constant | `00:00:00` | No travel at hotel |
| `itinerary_travel_type_buffer_time` | N/A | `00:00:00` | No buffer |
| `hotspot_travelling_distance` | N/A | `NULL` | Stationary |
| `hotspot_start_time` | Calculated | Previous `end_time` | At hotel |
| `hotspot_end_time` | Same as start | `hotspot_start_time` | No duration |
| `createdby` | Session | `1` | User/admin ID |
| `status` | Constant | `1` | Active |
| `deleted` | Constant | `0` | Not deleted |

**NestJS Builder**: `HotelTravelBuilder.buildReturnToHotel()`

---

#### **item_type = 7 (Return to Departure Location)**

Inserted **only on the last route** to record return journey to the original departure point.

| Field | Source | Logic | Notes |
|-------|--------|-------|-------|
| `itinerary_plan_ID` | Input | `:planId` | From itinerary plan |
| `itinerary_route_ID` | Loop | `:routeId` | From last route |
| `item_type` | Constant | `7` | Return to departure |
| `hotspot_order` | Loop counter | Incremented | Last sequence |
| `hotspot_ID` | N/A | `0` | Not a hotspot |
| `hotspot_traveling_time` | `calculateDistanceAndDuration()` | e.g. `00:36:00` | Return travel time |
| `itinerary_travel_type_buffer_time` | Global settings | `02:00:00` (example) | Buffer before departure (e.g., 2h rest) |
| `hotspot_travelling_distance` | Distance calc | e.g. `9.01` | Return distance (km) |
| `hotspot_start_time` | Calculated | Previous `end_time` | e.g. `15:40:00` |
| `hotspot_end_time` | Calculated | `start_time + travel_time + buffer` | e.g. `16:16:00` |
| `createdby` | Session | `1` | User/admin ID |
| `status` | Constant | `1` | Active |
| `deleted` | Constant | `0` | Not deleted |

**NestJS Builder**: `ReturnSegmentBuilder.buildReturnToDeparture()`

**Return Location Lookup**:
```sql
SELECT departure_location, departure_type
FROM dvi_itinerary_plan_details
WHERE itinerary_plan_ID = :planId
```

---

## dvi_itinerary_route_hotspot_parking_charge Field Mapping

Parking charges are inserted **for each hotspot visit** (`item_type = 4`) that has parking.

| Field | Source | Value | Notes |
|-------|--------|-------|-------|
| `itinerary_plan_ID` | Input | `:planId` | From itinerary plan |
| `itinerary_route_ID` | Loop | `:routeId` | From route loop |
| `hotspot_ID` | Master | `:hotspot_id` | From hotspot that has parking |
| `vehicle_type` | `dvi_itinerary_vehicle_details` | Type ID | 1=Car, 2=Bus, etc. |
| `vehicle_qty` | `dvi_itinerary_vehicle_details` | Count | How many vehicles of this type |
| `parking_charges_amt` | Calculated | `parking_rate × vehicle_qty` | Total parking charge |
| `createdby` | Session | `1` | User/admin ID |
| `createdon` | System | `NOW()` | Insert timestamp |
| `status` | Constant | `1` | Active |
| `deleted` | Constant | `0` | Not deleted |

**NestJS Builder**: `ParkingChargeBuilder.buildForHotspot()`

**Parking Lookup**:
```sql
SELECT parking_charge
FROM dvi_hotspot_parking_charge_master
WHERE hotspot_ID = :hotspot_id
  AND vehicle_type = :vehicle_type_id
  AND deleted = 0 AND status = 1
```

---

## Execution Flow (NestJS)

```
hotspot-engine.service.ts :: rebuildRouteHotspots(planId, tx)
  ↓
timeline.builder.ts :: buildTimelineForPlan(tx, planId)
  ├─ Fetch plan header from dvi_itinerary_plan_details
  ├─ Fetch routes from dvi_itinerary_route_details
  └─ For each route:
      ├─ RefreshmentBuilder.build()  → item_type=1 row
      ├─ Fetch selected hotspots from dvi_hotspot_place (by location_id)
      └─ For each hotspot:
          ├─ TravelSegmentBuilder.buildTravelSegment()  → item_type=3 row
          ├─ HotspotSegmentBuilder.build()  → item_type=4 row
          │   └─ ParkingChargeBuilder.buildForHotspot()  → parking row
          └─ [Check for more hotspots...]
      ├─ HotelTravelBuilder.buildToHotel()  → item_type=5 row
      ├─ HotelTravelBuilder.buildReturnToHotel()  → item_type=6 row
      └─ If last route:
          └─ ReturnSegmentBuilder.buildReturnToDeparture()  → item_type=7 row
  ↓
hotspot-engine.service.ts :: Insert bulk rows into dvi_itinerary_route_hotspot_details & parking table
```

---

## Key Differences: PHP vs NestJS

| Aspect | PHP | NestJS |
|--------|-----|--------|
| **Hotspot Selection** | No pre-selection table; fetches all hotspots for route's location_id | Same: `fetchSelectedHotspotsForRoute()` queries by `location_id` |
| **Time Format** | PHP `strtotime()` / `date()` (string HH:MM:SS) | JavaScript `Date` objects via `TimeConverter` |
| **Distance/Duration** | `calculateDistanceAndDuration()` Haversine + speed | `DistanceHelper` (to be implemented) |
| **Entry Costs** | Per-traveller detail rows in separate table | Aggregated in hotspot row (for now) |
| **Parking** | Query parking master, multiply by vehicle count | `ParkingChargeBuilder` queries master |
| **Transaction** | Manual transaction handling | Prisma `tx` client |
| **Bulk Insert** | Individual INSERT in loop | `createMany()` for efficiency |

---

## TODO: Next Steps

1. **Implement `DistanceHelper`** – Calculate Haversine distance and travel time between coordinates.
2. **Implement `getHotspotLocationName()`** – Query actual hotspot location field.
3. **Implement `getHotelLocationNameForRoute()`** – Query actual hotel details.
4. **Add entry ticket detail rows** – Break down per-traveller costs (currently aggregated).
5. **Test with plan_id=2** – Verify all 7 item_types generate correctly.
6. **Add filtering logic** – Distance limits, time constraints, hotspot availability.

