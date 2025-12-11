# Vehicle Calculation PHP-to-NestJS Parity Implementation Plan

## Current Status
✅ **Structural Fix**: Vehicle details are now created for ALL vendors (not just assigned)
✅ **Row Counts Match**: plan_id 2 and plan_id 5 both have correct row counts
❌ **Field Value Differences**: Calculations don't match PHP logic

## Major Discrepancies

### 1. dvi_itinerary_plan_vendor_eligible_list

| Field | Plan 2 (PHP) | Plan 5 (NestJS) | Issue |
|-------|--------------|-----------------|-------|
| vehicle_orign | "Chennai Koyembedu" | "DVI-CHENNAI" | Different source for vendor origin name |
| total_kms | 537.137 | 171.108 | Missing sightseeing KMs, pickup/drop KMs |
| total_outstation_km | 493.205 | 171.108 | Not separating local vs outstation correctly |
| total_time | 25.1 | 57.57 | Different time calculation format/logic |
| total_rental_charges | 8800 | 9600 | Different pricing model |
| total_toll_charges | 470 | 0 | Toll calculation not implemented |
| total_parking_charges | 860 | 860 | ✓ Matches |
| total_permit_charges | 500 | 0 | Permit calculation not implemented |
| total_allowed_kms | 500 | 750 | Different day count logic |
| total_allowed_local_kms | 100 | 0 | Local KM tracking not implemented |

### 2. dvi_itinerary_plan_vendor_vehicle_details (per route)

| Field | Plan 2 Row 1 | Plan 5 Row 1 | Issue |
|-------|--------------|--------------|-------|
| travel_type | 1 (LOCAL) | 2 (OUTSTATION) | Travel type determination logic different |
| total_running_km | 21.46 | 16.61 | Missing pickup distance calculation |
| total_siteseeing_km | 22.47 | null | Sightseeing KMs not calculated |
| total_pickup_km | 15.79 | 0.00 | Pickup distance not calculated |
| total_travelled_km | 43.93 | 16.61 | Sum of all KM types |
| vehicle_rental_charges | 2400 | 9600 | Wrong pricing model (local vs outstation) |
| time_limit_id | 74 | 0 | Time limit pricing not used |

## Root Causes

### 1. **Vehicle Origin Name Source**
- **PHP**: Uses `getSTOREDLOCATIONDETAILS($vehicle_location_id, 'SOURCE_LOCATION')`
- **NestJS**: Uses vendor branch name or uppercased city name
- **Fix**: Query `dvi_stored_locations` table to get exact source_location name

### 2. **Travel Type Determination**
- **PHP**: Complex logic checking if source_city == destination_city == vehicle_origin_city
- **NestJS**: Uses plan-level itinerary_type directly
- **Fix**: Implement per-route travel type determination (see line ~460 in PHP)

### 3. **Distance Calculations**
- **PHP**: Calculates 4 types of distances:
  - `TOTAL_RUNNING_KM`: Direct route distance
  - `SIGHT_SEEING_TRAVELLING_KM`: Sum of hotspot distances
  - `TOTAL_PICKUP_KM`: Distance from vendor origin to pickup point (Day 1)
  - `TOTAL_DROP_KM`: Distance from last location to drop point (Last Day)
- **NestJS**: Only uses route.no_of_km
- **Fix**: Calculate all 4 distance types

### 4. **Pricing Model**
- **PHP**: Different pricing for LOCAL vs OUTSTATION
  - **LOCAL**: Uses time-based pricing from `dvi_vehicle_local_pricebook` with time_limit_id
  - **OUTSTATION**: Uses KM-based pricing from `dvi_vehicle_outstation_price_book`
- **NestJS**: Always uses outstation pricing
- **Fix**: Implement dual pricing model with travel_type check

### 5. **Toll Charges**
- **PHP**: Queries `dvi_toll_pricebook` for each route segment
- **NestJS**: Not implemented
- **Fix**: Call `calculateVehicleTollCharges()` helper

### 6. **Parking Charges**
- **PHP**: Queries `dvi_parking_pricebook` joined with hotspots for each route
- **NestJS**: Uses hardcoded 860 (probably from global setting)
- **Fix**: Call `calculateHotspotParkingCharges()` helper

### 7. **Permit Charges**
- **PHP**: Calculates based on state boundary crossings
- **NestJS**: Not implemented
- **Fix**: Call `calculatePermitCharges()` helper

### 8. **Time Calculations**
- **PHP**: Format is "HH.MM" where MM is actual minutes (e.g., "25.10" = 25 hours 10 minutes)
- **NestJS**: Uses different format or calculation
- **Fix**: Use `calculateTotalHoursAndMinutes()` helper

### 9. **Allowed KMs Calculation**
- **PHP**: `TOTAL_ITINEARY_ALLOWED_KM = PER_DAY_KM_LIMIT * total_outstation_day_count`
  - Counts only routes with travel_type = 2 (OUTSTATION)
- **NestJS**: Uses total route count * per_day_limit
- **Fix**: Filter by travel_type = 2 when counting days

## Implementation Steps

### Phase 1: Critical Path (High Priority)
1. ✅ Fix vehicle_details creation for all vendors
2. ⏳ Implement vehicle origin name lookup
3. ⏳ Implement travel type determination per route
4. ⏳ Implement pickup/drop distance calculations
5. ⏳ Implement dual pricing (local vs outstation)

### Phase 2: Calculations (Medium Priority)
6. ⏳ Implement toll charge calculations
7. ⏳ Implement permit charge calculations
8. ⏳ Implement sightseeing KM calculations
9. ⏳ Fix time format calculations
10. ⏳ Fix allowed KM calculations (count only outstation days)

### Phase 3: Details (Low Priority)
11. ⏳ Implement time limit pricing for local trips
12. ⏳ Implement early morning/late evening charges
13. ⏳ Implement driver charges
14. ⏳ Implement via route handling
15. ⏳ Implement extra KM tracking for local trips

## Files Requiring Changes

1. **src/modules/itineraries/engines/itinerary-vehicles.engine.ts**
   - Main vehicle calculation logic
   - Currently at line ~1000-1210

2. **src/modules/itineraries/engines/vehicle-calculation.helpers.ts** ✅ Created
   - Helper functions for calculations
   - Mirrors PHP utility functions

3. **Database Queries Needed**
   - `dvi_stored_locations` - for vehicle origin names
   - `dvi_toll_pricebook` - for toll charges
   - `dvi_parking_pricebook` - for parking charges
   - `dvi_vehicle_local_pricebook` - for local trip pricing
   - `dvi_vehicle_outstation_price_book` - for outstation pricing (already used)
   - `dvi_itinerary_hotspot_details` - for sightseeing locations
   - `dvi_itinerary_plan_route_permit_charge` - for permit costs

## Estimated Effort
- Phase 1: 4-6 hours
- Phase 2: 3-4 hours
- Phase 3: 2-3 hours
- Testing & Debugging: 2-3 hours
- **Total: 11-16 hours**

## Testing Strategy
1. Run optimization for plan_id 5
2. Compare each field with plan_id 2
3. Debug discrepancies one by one
4. Re-run until 100% match

## Current Progress
- ✅ Structural fix (vehicle details for all vendors)
- ✅ Helper functions created
- ⏳ Awaiting full implementation

---
*Last Updated: 2025-12-12*
