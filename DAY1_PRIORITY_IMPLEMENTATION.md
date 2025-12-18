# Day-1 Priority Hotspots Implementation

## Overview

This implementation enforces the Day-1 (first route) logic to:
- Allow **max 3 priority hotspots** from the **source city only**
- Respect operating hours by **waiting for the next window same day** if a hotspot isn't currently open
- Schedule these hotspots **before travel to destination**
- Ensure **hotel travel (item_type 5) and close (item_type 6)** rows are present for the destination city

## Changes Made

### 1. City Name Normalization

**File**: `src/modules/itineraries/engines/helpers/timeline.builder.ts`

Added a new method `private normalizeCityName(name: string): string` that:
- Converts to lowercase
- Removes punctuation: `[.,()]`
- Removes keywords: `international|domestic|airport|railway|station|stn|junction|jn|central|egmore|terminus|bus stand`
- Collapses whitespace

**Purpose**: Ensures "Chennai International Airport" matches "Chennai" when comparing cities.

**Usage**: All location comparisons now use `this.normalizeCityName()` instead of inline logic.

---

### 2. Day-1 Top Priority Source Hotspots Fetcher

**Method**: `private async fetchDay1TopPrioritySourceHotspots(...)`

**Parameters**:
- `tx`: Transaction client
- `planId`: Plan ID
- `routeId`: Route ID
- `sourceCity`: Source location name
- `destinationCity`: Destination location name (unused in method, for context)

**Logic**:
1. Fetches all active hotspots with `hotspot_priority > 0` (priority hotspots only)
2. Filters to only those matching the **source city** (using normalized matching)
3. Calculates distance from source location coordinates
4. Sorts by priority ASC, then distance ASC
5. Returns **top 3 hotspots**

**Returns**: Array of `SelectedHotspot[]` (max 3 items)

---

### 3. Day-1 Different Cities Condition & Scheduling

**In `buildTimelineForPlan()`**:

#### Detection:
```typescript
const isDay1DifferentCities = isFirstRoute && 
  normalizedSourceCity && 
  normalizedDestinationCity && 
  normalizedSourceCity !== normalizedDestinationCity;
```

#### Scheduling Logic (Pseudo-code):
```
For each hotspot in selected (priority order):
  1. Calculate travel time to hotspot
  2. Get arrival time and end time
  3. Check operating hours for this day
  
  If operating hours allow NOW:
    - Add travel segment (item_type=3)
    - Add hotspot visit (item_type=4)
    - Update current time
  
  Else if hotspot opens later same day:
    - Add travel segment (ending at current arrival)
    - Wait: Advance time to next operating window start
    - Recalculate end time from new start time
    - If it fits in next window:
        - Add hotspot visit
        - Update current time
    - Else: Skip this hotspot
  
  Else (closed all day):
    - Skip this hotspot

  Add parking charges for visited hotspot
```

#### Key Features:
- **No multi-pass deferral** for Day-1: Process hotspots in strict priority order
- **Smart waiting**: If a hotspot opens later, advance time to next window within same day
- **No time cutoffs**: User can visit all 3 hotspots regardless of time constraints
- **Duplicate prevention**: Check `addedHotspotIds` to skip already-added hotspots

---

### 4. Location Matching with Normalization

**In `fetchSelectedHotspotsForRoute()`**:

Updated `containsLocation()` helper to normalize both sides:
```typescript
const containsLocation = (hotspotLocation: string | null, target: string | null): boolean => {
  const hotspotParts = hotspotLocation.split("|").map(p => this.normalizeCityName(p));
  const normalizedTarget = this.normalizeCityName(target);
  return hotspotParts.includes(normalizedTarget);
};
```

**Result**: Ensures consistent matching across all hotspot selections.

---

### 5. Hotel Rows at Destination

After processing up to 3 hotspots on Day-1:
- Add travel to hotel (item_type=5) at **destination city**
- Add hotel close/return (item_type=6)
- Same order for both rows (PHP parity)

---

### 6. Verification Script

**File**: `tmp/verify_day1_priority.ts`

**Usage**:
```bash
npx ts-node tmp/verify_day1_priority.ts <planId>
```

**What it does**:
1. Rebuilds timeline rows using `TimelineBuilder.buildTimelineForPlan()`
2. Queries Day-1 route (earliest route date)
3. Verifies:
   - ✅ item_type=4 (hotspot visits) count <= 3
   - ✅ All visited hotspots have priority > 0
   - ✅ Hotel travel and close rows exist (item_type 5 & 6)
   - ✅ All visits appear to be in source city
4. Prints summary table of hotspots
5. Exits with code 1 if any check fails

**Output Example**:
```
=== Day-1 Priority Hotspots Verification ===
Plan ID: 33959

Day-1 Route:
  Route ID: 980
  Date: 2025-12-24
  Location: Chennai → Alleppey

Timeline Summary:
  Total rows: 15
  Travel segments (type 3): 3
  Hotspot visits (type 4): 3
  Hotel travel (type 5): 1
  Hotel close (type 6): 1

Day-1 Hotspot Visits (3):
┌─────┬──────────┬─────────────────────────────────────────┬──────────┐
│ ID  │ Priority │ Location                                │ Duration │
├─────┼──────────┼─────────────────────────────────────────┼──────────┤
│ 456 │ 1        │ Chennai                                 │ 01:00:00 │
│ 789 │ 2        │ Chennai                                 │ 01:30:00 │
│ 234 │ 3        │ Chennai                                 │ 02:00:00 │
└─────┴──────────┴─────────────────────────────────────────┴──────────┘

=== Verification Checks ===

✅ CHECK 1: Day-1 hotspot visits <= 3 (actual: 3)
✅ CHECK 2: All visited hotspots have priority > 0
✅ CHECK 3: Hotel travel rows exist (type 5: 1, type 6: 1)
✅ CHECK 4: All visits appear to be in source city

=== Summary ===
Passed: 4/4
Failed: 0/4

✅ VERIFICATION PASSED
```

---

## Test Execution

To test the implementation:

```bash
# Terminal at project root: d:\wamp64\www\dvi_fullstack\dvi_backend

# Run verification for a specific plan (e.g., plan 33959)
npx ts-node tmp/verify_day1_priority.ts 33959

# You should see output showing <= 3 hotspots with priority > 0, plus hotel rows
```

---

## Acceptance Criteria Met

✅ **Day-1 (first route) where source city != destination city**:
- ✅ item_type=4 visits <= 3 (enforced by `fetchDay1TopPrioritySourceHotspots`)
- ✅ Those visits are priority hotspots (priority > 0 filtered)
- ✅ If a priority hotspot opens later, schedule it later same day (waiting logic)
- ✅ After hotspots, itinerary includes hotel travel (5 and 6) to destination city

✅ **TypeScript build has no errors in edited files**:
- ✅ `timeline.builder.ts`: Compiles without errors
- ✅ `verify_day1_priority.ts`: Compiles without errors

✅ **No server start/stop required**:
- ✅ Verification script uses PrismaClient directly
- ✅ No NestJS bootstrap
- ✅ No pm2 or npm run dev calls

---

## Files Modified

1. **src/modules/itineraries/engines/helpers/timeline.builder.ts**
   - Added `normalizeCityName()` method
   - Added `fetchDay1TopPrioritySourceHotspots()` method
   - Updated `buildTimelineForPlan()` with Day-1 different cities logic
   - Updated `fetchSelectedHotspotsForRoute()` location matching
   - Total changes: ~300 lines (methods + logic)

2. **tmp/verify_day1_priority.ts** (NEW)
   - DB verification script
   - ~200 lines

---

## Design Decisions

1. **Normalized City Names**: Single `normalizeCityName()` method ensures consistent comparison rules across all location logic.

2. **Max 3 Hotspots**: Hardcoded in `fetchDay1TopPrioritySourceHotspots()` limit, no configurable parameter.

3. **Operating Hours Waiting**: If a hotspot opens later the same day, advance time to that window and recheck. If it still doesn't fit, skip (not ideal to force impossible visits).

4. **No Multi-Pass on Day-1**: Day-1 hotspots are processed once in priority order (simpler, clearer). Other days still use multi-pass to fill gaps.

5. **Logging**: File-based logging to `d:/wamp64/www/dvi_fullstack/dvi_backend/tmp/hotspot_selection.log` for debugging.

---

## Next Steps (Optional)

- Monitor production behavior with the verification script on sample plans
- Adjust normalization rules if airport names don't match consistently
- Consider making the "3 hotspot limit" configurable if needed
- Add metrics/analytics on Day-1 hotspot scheduling success rate
