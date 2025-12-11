# Final Status Report - Plan ID Comparison

**Date**: December 11, 2025
**Branch**: hotspot-optimise

## ✅ Completed Work

### 1. **Vendor Eligible List Integration** 
- ✅ Added call to `ItineraryVehiclesEngine.rebuildEligibleVendorList` in main flow
- ✅ Vendor eligible list now creates 2 rows (was 0)
- ✅ Vendor vehicle details now creates 3 rows (was 0)

### 2. **Vendor Calculations - GST & Margins**
- ✅ Added vehicle_gst_type: 2
- ✅ Added vehicle_gst_percentage: 5
- ✅ Added vehicle_gst_amount calculation
- ✅ Added vendor_margin_percentage: 10  
- ✅ Added vendor_margin_gst_type: 2
- ✅ Added vendor_margin_gst_percentage: 5
- ✅ Added vendor_margin_amount calculation
- ✅ Added vendor_margin_gst_amount calculation
- ✅ vehicle_grand_total now includes all components

**Verification**:
```
Plan 2 (PHP): vehicle_gst_amount: 531.5, vendor_margin_amount: 1063
Plan 5 (NestJS): vehicle_gst_amount: 480, vendor_margin_amount: 960
```
Values differ because base amounts differ (toll/parking/permit still missing), but structure is correct.

### 3. **Commits Made**
1. Integration of vendor eligible list generation
2. Added GST and vendor margin calculations
3. Documentation and progress reports

---

## ⚠️ Remaining Work

### 1. **Vendor Charges - Still Missing**
These fields are still hardcoded to 0:
- `total_toll_charges` - Need to aggregate from route hotspots
- `total_parking_charges` - Need to aggregate from route hotspots  
- `total_permit_charges` - Need to calculate based on vehicle/route
- `total_driver_charges` - Usually 0, but structure exists

**Impact**: Grand totals differ from PHP because these charges aren't included.

### 2. **Hotel Selection - Not Implemented**
All hotel fields are 0:
- `hotel_id` = 0 (should be actual hotel ID)
- `room_type_id` = 0 (should be room type)
- `room_id` = 0 (should be room ID)
- `room_rate` = 0 (should be actual rate)
- All meal costs = 0

**Available Infrastructure**:
- ✅ `HotelPricingService` exists with methods:
  - `pickHotelByCategory(category, city)`
  - `getRoomPrices(hotel_id, date)`
  - `getMealPrice(hotel_id, date)`
  
**What's Needed**:
1. Inject `HotelPricingService` into `HotelEngineService`
2. In `rebuildPlanHotels`, after creating placeholder rows:
   - Get preferred hotel category from plan
   - For each route, pick hotel by location + category
   - Get room prices for the route date
   - Get meal prices for the route date
   - Update room_details with actual hotel_id, room_id, rates
   - Calculate totals and update header

### 3. **Row Count Differences**
- `hotel_room_details`: Plan 2 = 8 rows, Plan 5 = 12 rows (4 extra)
  - PHP creates rows only for routes that need hotels
  - NestJS creates rows for ALL routes × ALL group types
  - Need to check if hotel is required per route
  
- `vendor_vehicle_details`: Plan 2 = 6 rows, Plan 5 = 3 rows  
  - PHP creates details for all eligible vendors (both assigned and unassigned)
  - NestJS only creates for assigned vendors
  - Verify which approach is correct per PHP logic

### 4. **Time Calculations Missing**
In vendor_vehicle_details, these are NULL in NestJS:
- `total_running_time`
- `total_siteseeing_time`  
- `total_pickup_duration`
- `total_drop_duration`
- `total_travelled_time`
- `before_6_am_extra_time`
- `after_8_pm_extra_time`

Need to aggregate from hotspot timeline data.

---

## 📊 Current Comparison Results

| Table | Plan 2 | Plan 5 | Status |
|-------|--------|--------|--------|
| traveller_details | 8 | 8 | ✅ Match |
| vehicle_details | 1 | 1 | ✅ Match |
| vendor_eligible_list | 2 | 2 | ⚠️ Created, values differ |
| vendor_vehicle_details | 6 | 3 | ⚠️ Created, row count differs |
| hotel_details | 8 | 8 | ❌ All zeros |
| hotel_room_details | 8 | 12 | ❌ All zeros + row count |

---

## 🎯 Priority Next Steps

### High Priority (Blocking)
1. **Implement Hotel Selection** - All hotel data is zeros
   - Most visible user-facing issue
   - Infrastructure exists, just needs integration
   
2. **Fix Vendor Row Count** - Missing 3 vendor_vehicle_details rows
   - Determine if all eligibles should have details or just assigned

### Medium Priority (Calculation Accuracy)
3. **Add Toll/Parking/Permit Charges** - Currently all 0
   - Causes grand total differences
   - Need to aggregate from hotspot data

4. **Add Time Calculations** - All NULL
   - Not blocking but needed for completeness

### Low Priority (Data Quality)
5. **Fix Hotel Row Count** - 4 extra rows in NestJS
   - Check hotel_required flag per route

---

## 🔧 Files Modified
- `src/modules/itineraries/itineraries.service.ts` - Added vendor eligible call
- `src/modules/itineraries/engines/itinerary-vehicles.engine.ts` - Added GST/margin calcs

## 📁 Tools Created
- `tmp/compare_plan_data.js` - Full comparison script
- `tmp/quick_compare.js` - Quick field comparison
- `tmp/get_table_schemas.js` - Schema inspection
- `PLAN_COMPARISON_PROGRESS.md` - Progress documentation

---

## ✨ Key Achievement
**Vendor system is now functional!** Tables went from 0 rows to populated data with proper GST and margin calculations. This is a significant milestone in PHP-to-NestJS migration parity.
