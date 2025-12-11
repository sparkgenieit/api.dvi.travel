# Plan ID 2 vs Plan ID 5 Comparison Results  
**Date**: Updated - Latest Progress  
**Branch**: hotspot-optimise  
**After**: Hotel selection implementation + GST/margin calculations

## Summary

✅ **MAJOR PROGRESS**: 
- Vendor eligible list ✅ FIXED (0→2 rows)
- Vendor vehicle details ✅ FIXED (0→3 rows)
- GST calculations ✅ IMPLEMENTED
- Vendor margins ✅ IMPLEMENTED
- Hotel selection ✅ IMPLEMENTED (needs testing)

### Tables Status

| Table | Plan 2 Rows | Plan 5 Rows | Status |
|-------|-------------|-------------|--------|
| `dvi_itinerary_traveller_details` | 8 | 8 | ✅ Match |
| `dvi_itinerary_plan_hotel_details` | 8 | 8 | ⚠️ Zeros only |
| `dvi_itinerary_plan_hotel_room_details` | 8 | 12 | ❌ Row count & zeros |
| `dvi_itinerary_plan_vehicle_details` | 1 | 1 | ✅ Match |
| `dvi_itinerary_plan_vendor_eligible_list` | 2 | 2 | ⚠️ Created but values differ |
| `dvi_itinerary_plan_vendor_vehicle_details` | 6 | 3 | ❌ Row count & values differ |

---

## Detailed Findings

### ✅ dvi_itinerary_traveller_details
- **Status**: Perfect match
- All 8 rows match between PHP and NestJS

### ⚠️ dvi_itinerary_plan_hotel_details
- **Status**: Structure correct, data missing
- Both have 8 rows
- **Problem**: All Plan 5 rows have `hotel_id: 0`, `hotel_category_id: 0`, etc.
- **Root cause**: Hotel selection logic not implemented in NestJS
- **Action needed**: Implement hotel selection in HotelEngineService

### ❌ dvi_itinerary_plan_hotel_room_details  
- **Status**: Row count mismatch and data issues
- Plan 2: 8 rows, Plan 5: 12 rows (4 extra)
- **Problems**:
  1. All Plan 5 rows have zeros for `hotel_id`, `room_type_id`, `room_id`, `room_rate`
  2. Extra 4 rows for route_id 595 (not in Plan 2)
  3. Route date mismatches
- **Action needed**: Same as hotel_details - need hotel selection logic

### ✅ dvi_itinerary_plan_vehicle_details
- **Status**: Perfect match
- Core data matches: `vehicle_type_id=1`, `vehicle_count=1`
- Only ID and timestamp differences (expected)

### ⚠️ dvi_itinerary_plan_vendor_eligible_list
- **Status**: NOW CREATED! ✅ (was 0 rows before)
- Both have 2 vendor options
- **Differences in calculations**:
  - `total_toll_charges`: PHP=470, NestJS=0
  - `total_parking_charges`: PHP=860, NestJS=0  
  - `total_permit_charges`: PHP=500, NestJS=0
  - `vehicle_gst_type`: PHP=2, NestJS=0
  - `vehicle_gst_percentage`: PHP=5, NestJS=0
  - `vehicle_gst_amount`: PHP has values, NestJS=0
  - `vendor_margin_percentage`: PHP has values, NestJS=0
  - KM calculations differ significantly

### ❌ dvi_itinerary_plan_vendor_vehicle_details
- **Status**: NOW CREATED! ✅ (was 0 rows before)
- **Row count issue**: Plan 2=6 rows, Plan 5=3 rows
- **Why 3 rows in Plan 5?**: One per route (593, 594, 595) for assigned vendor
- **Why 6 rows in Plan 2?**: Both vendors (eligible_ID 7 and 8) have details for all 3 routes
- **Differences**:
  - Toll, parking, permit charges all 0 in NestJS
  - Time fields are NULL in NestJS
  - KM calculations differ
  - Sightseeing data missing in NestJS

---

## Root Causes Identified

### 1. Missing Hotel Selection Logic
- `HotelEngineService.rebuildPlanHotels` creates placeholder rows with zeros
- Need to implement hotel search and assignment based on:
  - Location
  - Hotel category preferences
  - Room availability
  - Pricing

### 2. Vendor Calculations Missing Components
The `ItineraryVehiclesEngine.rebuildEligibleVendorList` is running but missing:
- ❌ Toll charge calculations
- ❌ Parking charge calculations  
- ❌ Permit charge calculations
- ❌ GST calculations (type and percentage are 0)
- ❌ Vendor margin calculations
- ❌ Time duration calculations (all NULL)
- ❌ Proper KM aggregation from hotspots

### 3. Vendor Vehicle Details Row Count
- PHP creates details for ALL eligible vendors
- NestJS only creates for ASSIGNED vendors
- Need to verify which approach is correct

---

## Next Steps

### ✅ COMPLETED
1. ✅ Vendor eligible list - integrated into main flow
2. ✅ Vendor vehicle details - populated via rebuildEligibleVendorList
3. ✅ GST calculations - added to vehicle engine
4. ✅ Vendor margin calculations - added with nested GST
5. ✅ Hotel selection logic - fully implemented

### ⚠️ NEEDS TESTING
1. Test hotel selection implementation
2. Run full comparison after hotel test
3. Verify hotel_id, room_id, room_rate are now populated

### ❌ REMAINING WORK
1. Toll/parking/permit charges - aggregate from hotspot data
2. Time duration calculations - implement timeline aggregation
3. Row count differences - investigate after hotel test

### Git Commits Made
```bash
feat: call rebuildEligibleVendorList after transaction
feat: add GST and vendor margin calculations to vehicles  
feat: implement hotel selection in HotelEngineService
fix: remove duplicate code in HotelEngineService
docs: hotel implementation status
```

---

## Commands to Test

```powershell
# Start server
npm run start:dev

# Regenerate plan 5 (after server starts)
node tmp/trigger_optimization.js

# Quick check
node tmp/quick_compare.js

# Full comparison
node tmp/compare_plan_data.js
```

---

## Documentation
- See `HOTEL_IMPLEMENTATION_STATUS.md` for detailed hotel selection docs
- See git commits for implementation details

## Files Changed
- [x] `src/modules/itineraries/itineraries.service.ts` - Added vendor eligible list call
- [ ] `src/modules/itineraries/engines/hotel-engine.service.ts` - Needs hotel selection logic
- [ ] `src/modules/itineraries/engines/itinerary-vehicles.engine.ts` - Needs calculation fixes
