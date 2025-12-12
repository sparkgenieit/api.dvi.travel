# Vehicle KM Calculation Investigation - 100% Parity Analysis

## Progress Summary

### ✅ COMPLETED FIXES

1. **Vendor Margin Percentage** (FIXED)
   - Issue: Hardcoded to 10% for all vendors
   - Fix: Fetch from `dvi_vendor_details.vendor_margin`
   - Result: Vendor 24 = 10%, Vendor 36 = 5% ✓

2. **Vehicle GST Settings** (FIXED)
   - Issue: Hardcoded GST type and percentage
   - Fix: Use `vendor_branch_gst_type` and `vendor_branch_gst` from `dvi_vendor_branches`
   - Result: Branch-specific GST calculations ✓

3. **Total Allowed KMs** (FIXED)
   - Issue: Calculated as 250 × 3 days instead of 250 × 2 outstation days
   - Fix: Two-phase calculation - count only travel_type=2 days
   - Result: 500 km (250 km/day × 2 outstation days) ✓

4. **Vehicle Origin** (FIXED)
   - Issue: Using `vendor_branch_name` instead of actual location
   - Fix: Fetch `source_location` from `dvi_stored_locations`
   - Result: 'Chennai Koyembedu' and 'Pondicherry' ✓

5. **Permit Charges Generation** (FIXED)
   - Issue: `rebuildPermitCharges()` never called
   - Fix: Added call after `rebuildRoutes()` in itineraries.service.ts
   - Result: Permit charges will now be generated for all new plans ✓

### ❌ REMAINING ISSUES - KM Calculations

#### Issue 1: Total Travelled KM Differences

**Plan 2 vs Plan 5 (Vendor 24):**
```
Route 427/866 (LOCAL):
  Plan 2: 43.93 km (running=21.46, pickup=15.79, sightseeing=22.47)
  Plan 5: 49.61 km (running=16.61, pickup=10.53, sightseeing=22.47)
  Base route KM: 16.61 km

Route 428/867 (OUTSTATION):
  Plan 2: 219.17 km (running=5.87, sightseeing=213.3)
  Plan 5: 379.97 km (running=151, sightseeing=228.97)
  Base route KM: 151 km

Route 429/868 (OUTSTATION):
  Plan 2: 274.04 km (running=208.20, sightseeing=65.84, drop=194.29)
  Plan 5: 171.08 km (running=3.5, sightseeing=38.06, drop=129.52)
  Base route KM: 3.5 km
```

**Totals:**
- Plan 2: 537.14 km
- Plan 5: 600.66 km
- Difference: 63.52 km

#### Analysis:

1. **Running KM Override in PHP**
   - Plan 2 Route 428: uses 5.87 km instead of base 151 km
   - Plan 2 Route 429: uses 208.20 km instead of base 3.5 km
   - This suggests PHP is calculating route distances based on:
     * Previous route destination
     * Hotspot waypoints
     * Via route logic

2. **Sightseeing KM Differences**
   - Route 428: 213.3 km (Plan 2) vs 228.97 km (Plan 5)
   - Route 429: 65.84 km (Plan 2) vs 38.06 km (Plan 5)
   - Likely due to different hotspot distance calculations

3. **Pickup/Drop KM Differences**
   - Pickup KM: 15.79 km (Plan 2) vs 10.53 km (Plan 5)
   - Drop KM: 194.29 km (Plan 2) vs 129.52 km (Plan 5)
   - Related to vehicle origin distance calculations

## Next Steps for Investigation

### 1. PHP Running KM Calculation Logic

Location: `legacy_php/ajax_latest_itineary_manage_vehicle_details.php`

Need to understand:
- How PHP overrides the base `no_of_km` from route table
- When it uses previous destination vs route start location
- How hotspots affect the running distance calculation
- Via route logic implementation

### 2. Sightseeing KM Calculation

Current NestJS logic: `vehicle-calculation.helpers.ts` → `calculateSightseeingKm()`

Need to verify:
- Hotspot distance aggregation method
- Whether PHP includes via_route distances
- Hotspot ordering and waypoint calculations

### 3. Pickup/Drop Distance Logic

Current NestJS logic:
- Pickup: Day 1 only, vehicle origin → first route start
- Drop: Last day only, last route end → vehicle origin

PHP may be calculating:
- Different origin points
- State boundary crossing distances
- Return journey calculations differently

## Testing Strategy

1. **Start NestJS server:**
   ```bash
   npm run start:dev
   ```

2. **Run optimization:**
   ```bash
   node tmp/trigger_optimization.js
   ```

3. **Verify permit charges:**
   ```bash
   node tmp/check_permit_issue.js
   ```

4. **Compare KM breakdown:**
   ```bash
   node tmp/analyze_km_diff.js
   ```

5. **Check hotspot calculations:**
   - Investigate hotspot distances in dvi_itinerary_route_hotspot_details
   - Compare hotspot_travelling_distance between plans

## Files to Investigate

1. **PHP Files:**
   - `legacy_php/ajax_latest_itineary_manage_vehicle_details.php` - Main vehicle calculation
   - Look for running_km calculation logic
   - Via route handling
   - Hotspot distance aggregation

2. **NestJS Files:**
   - `src/modules/itineraries/engines/vehicle-calculation.helpers.ts`
   - `calculateRouteVehicleDetails()` - Main entry point
   - `calculateSightseeingKm()` - Hotspot distances
   - `calculatePickupDistance()` / `calculateDropDistance()`

## Expected Outcomes After Full Fix

When 100% parity is achieved:
- `total_permit_charges`: 500 = 500 ✓
- `total_travelled_km`: ~537 km = ~537 km ✓
- `total_outstation_km`: ~493 km = ~493 km ✓
- `total_extra_kms`: 0 = 0 ✓
- `total_extra_local_kms_charge`: 0/1004.69 = 0/1004.69 ✓
- All eligible_list fields match exactly

## Current Status

**Phase 1 Complete:** Eligible list aggregation logic, vendor settings, allowed KMs, vehicle origin
**Phase 2 In Progress:** Permit charge generation (implemented, needs testing)
**Phase 3 Pending:** Route KM calculations (requires deep PHP analysis)
