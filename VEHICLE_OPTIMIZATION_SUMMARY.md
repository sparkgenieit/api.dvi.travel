# Vehicle Optimization Summary - December 12, 2025

## ✅ What Has Been Fixed

### 1. Structural Issue - ROW COUNTS NOW MATCH ✓
**Problem**: NestJS was only creating vehicle_details for assigned vendors  
**Solution**: Removed `itineary_plan_assigned_status: 1` filter  
**Result**: Both plan_id 2 and 5 now have 6 rows in vendor_vehicle_details  

**File Changed**: `src/modules/itineraries/engines/itinerary-vehicles.engine.ts`
- Line ~255: Updated comment to reflect creating details for ALL vendors
- Line ~1051-1058: Removed assigned status filter

### 2. Table Comparison Results
```
✅ dvi_itinerary_plan_vehicle_details: Both plans have 1 row
✅ dvi_itinerary_plan_vendor_eligible_list: Both plans have 2 rows  
✅ dvi_itinerary_plan_vendor_vehicle_details: Both plans have 6 rows (3 routes × 2 vendors)
```

## ❌ What Remains - FIELD VALUE DIFFERENCES

The calculations don't match because NestJS uses simplified logic while PHP has complex business rules for:

### Critical Calculation Differences

#### 1. **Vehicle Origin** (`vehicle_orign` field)
- **PHP**: "Chennai Koyembedu" (from dvi_stored_locations.source_location)
- **NestJS**: "DVI-CHENNAI" (from vendor branch or uppercased city)
- **Impact**: Display/reporting inconsistency

#### 2. **Total KMs** (`total_kms` field)
- **PHP**: 537.14 km (includes running + sightseeing + pickup + drop)
- **NestJS**: 171.11 km (only running distance)
- **Impact**: 68% undercount, affects pricing and reporting

#### 3. **Total Outstation KM** (`total_outstation_km`)
- **PHP**: 493.21 km (only counts OUTSTATION routes)
- **NestJS**: 171.11 km (counts all routes)
- **Impact**: Extra KM charges calculated incorrectly

#### 4. **Travel Type** (`travel_type` in vehicle_details)
- **PHP**: Per-route determination (LOCAL=1 vs OUTSTATION=2)
  - Route 427 (Chennai Airport → Chennai): LOCAL (type 1)
  - Route 428 (Chennai → Pondicherry): OUTSTATION (type 2)
  - Route 429 (Pondicherry → Airport): OUTSTATION (type 2)
- **NestJS**: All routes marked as OUTSTATION (type 2)
- **Impact**: Wrong pricing model applied, affects rental charges

#### 5. **Rental Charges** (`total_rental_charges`)
- **PHP**: 8800 (uses local pricing for route 1, outstation for routes 2-3)
- **NestJS**: 9600 (uses outstation pricing for all routes)
- **Impact**: 9% overcharge

#### 6. **Toll Charges** (`total_toll_charges`)
- **PHP**: 470 (queries dvi_toll_pricebook per route)
- **NestJS**: 0 (not implemented)
- **Impact**: 100% undercharge

#### 7. **Permit Charges** (`total_permit_charges`)
- **PHP**: 500 (calculated from state boundary crossings)
- **NestJS**: 0 (not implemented)
- **Impact**: 100% undercharge

#### 8. **Allowed KMs** (`total_allowed_kms`)
- **PHP**: 500 km (250 km/day × 2 outstation days)
- **NestJS**: 750 km (250 km/day × 3 total days)
- **Impact**: Incorrect extra KM threshold

#### 9. **Per-Route Distance Components**
**PHP calculates 4 types** per route:
- `total_running_km`: Direct route distance (21.46 km for route 1)
- `total_siteseeing_km`: Hotspot distances (22.47 km for route 1)  
- `total_pickup_km`: Vendor origin to pickup (15.79 km for route 1)
- `total_drop_km`: Last location to drop point (0 for route 1)

**NestJS only has**:
- `total_running_km`: Route distance (16.61 km)
- Others: null or 0

**Impact**: Missing 60% of distance data

## 📋 Implementation Resources Created

### 1. Helper Functions
**File**: `src/modules/itineraries/engines/vehicle-calculation.helpers.ts`

Contains TypeScript functions that mirror PHP utility functions:
- `calculateVehicleTollCharges()` - Query toll pricebook
- `calculateHotspotParkingCharges()` - Query parking pricebook
- `calculatePermitCharges()` - Query permit charges
- `getStoredLocationName()` - Get vehicle origin from stored locations
- `getStoredLocationCity()` - Get city from location name
- `determineTravelType()` - LOCAL vs OUTSTATION logic
- `calculateTotalHoursAndMinutes()` - PHP time format conversion
- `sumStringNumbers()` - Sum KM strings

### 2. Implementation Plan
**File**: `VEHICLE_PARITY_IMPLEMENTATION_PLAN.md`

Comprehensive 3-phase plan detailing:
- Root cause analysis for each discrepancy
- Required database queries
- Implementation steps
- Estimated effort: 11-16 hours

## 🎯 Recommendation

**Option A**: Continue with current implementation
- ✅ Structural integrity maintained
- ✅ Row counts match
- ❌ Field values don't match PHP
- **Use case**: If exact parity isn't critical for business logic

**Option B**: Full parity implementation  
- ✅ 100% match with PHP
- ✅ Correct pricing calculations
- ⏰ Requires 11-16 hours additional development
- **Use case**: If financial accuracy is critical

**Option C**: Hybrid approach
- ✅ Fix only critical calculations (toll, permit, pricing model)
- ⏰ Requires 4-6 hours
- **Use case**: Balance between accuracy and effort

## 📊 Current Test Data

### Plan 2 (PHP) - Vendor 24 Summary
```json
{
  "vehicle_orign": "Chennai Koyembedu",
  "total_kms": "537.13749921004",
  "total_outstation_km": "493.20522341568",
  "total_time": "25.1",
  "total_rental_charges": 8800,
  "total_toll_charges": 470,
  "total_parking_charges": 860,
  "total_permit_charges": 500,
  "total_allowed_kms": "500",
  "vehicle_grand_total": 12277.65
}
```

### Plan 5 (NestJS) - Vendor 24 Summary
```json
{
  "vehicle_orign": "DVI-CHENNAI",
  "total_kms": "171.108500090884",
  "total_outstation_km": "171.108500090884",
  "total_time": "57.57",
  "total_rental_charges": 9600,
  "total_toll_charges": 0,
  "total_parking_charges": 860,
  "total_permit_charges": 0,
  "total_allowed_kms": "750",
  "vehicle_grand_total": 12081.3
}
```

### Difference Summary
- Total KMs: -68% (537 vs 171)
- Rental Charges: +9% (8800 vs 9600)
- Toll Charges: -100% (470 vs 0)
- Permit Charges: -100% (500 vs 0)
- Grand Total: -1.6% (12,277 vs 12,081)

## 🚀 Next Steps

If proceeding with full parity (Option B):
1. Implement vehicle origin lookup
2. Implement per-route travel type determination  
3. Implement pickup/drop distance calculations
4. Switch to dual pricing model (local vs outstation)
5. Implement toll charge calculations
6. Implement permit charge calculations
7. Fix allowed KM calculation (count only outstation days)
8. Implement sightseeing KM calculations
9. Test and compare until 100% match

**Estimated Timeline**: 2-3 working days

---
*Generated: December 12, 2025*
*Status: Structural fix complete, calculation fixes pending*
