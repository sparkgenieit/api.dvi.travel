# Engine Logic: Hotspot Exclusion Fix

## Problem Statement
Hotspots were reappearing in itineraries after users deleted them and the timeline was rebuilt. The exclusion list mechanism was not working correctly.

## Root Cause Analysis

### Issue Location
**File:** `src/modules/itineraries/itineraries.service.ts` - `deleteHotspot()` method

### What Was Wrong
When a hotspot was deleted, the `deleteHotspot()` method was storing the **`route_hotspot_ID`** (e.g., 2720978) in the `excluded_hotspot_ids` array:

```typescript
// ❌ WRONG: Storing route_hotspot_ID
const excluded = (route?.excluded_hotspot_ids as number[]) || [];
if (!excluded.includes(hotspotId)) {  // hotspotId is route_hotspot_ID here
  excluded.push(hotspotId);
}
```

However, the selector was checking if **`hotspot_ID`** (e.g., 4) was in the exclusion list:

```typescript
// timeline.hotspot-selector.ts
for (const h of this.deps.allHotspots) {
  const hId = Number((h as any).hotspot_ID);  // Checking master hotspot_ID
  if (excludedHotspotIds.has(hId)) continue;  // But excluded list had route_hotspot_ID
  // ... add to timeline
}
```

**ID Mismatch Example:**
- Kapaleeshwarar Temple: `hotspot_ID = 4` (master ID)
- In route: `route_hotspot_ID = 2720978` (route-specific ID)
- Deletion stored: `2720978` in excluded list
- Selector checked: Does excluded list contain `4`? NO → Hotspot reappeared!

## Solution Implemented

### Code Change
**File:** `src/modules/itineraries/itineraries.service.ts`

Updated `deleteHotspot()` to fetch the actual hotspot record first and store the master `hotspot_ID`:

```typescript
async deleteHotspot(planId: number, routeId: number, hotspotId: number) {
  const userId = 1;

  await this.prisma.$transaction(async (tx) => {
    // ✅ Step 1: Fetch the hotspot record to get the actual hotspot_ID
    const hotspotRecord = await (tx as any).dvi_itinerary_route_hotspot_details.findUnique({
      where: {
        route_hotspot_ID: hotspotId,
      },
    });

    if (!hotspotRecord) {
      throw new BadRequestException('Hotspot not found');
    }

    const actualHotspotId = hotspotRecord.hotspot_ID; // Master hotspot ID

    // ... [deletion code] ...

    // ✅ Step 2: Add the actual hotspot_ID (not route_hotspot_ID) to excluded list
    const excluded = (route?.excluded_hotspot_ids as number[]) || [];
    if (!excluded.includes(actualHotspotId)) {
      excluded.push(actualHotspotId);
    }

    // Update route with excluded list
    await (tx as any).dvi_itinerary_route_details.update({
      where: { itinerary_route_ID: routeId },
      data: {
        excluded_hotspot_ids: excluded,
        updatedon: new Date(),
      },
    });

    // Trigger rebuild with exclusion list in place
    await this.hotspotEngine.rebuildRouteHotspots(tx, planId);
  });
}
```

## How It Works in the Engine

### 1. Deletion Flow
```
User deletes hotspot
    ↓
deleteHotspot() called with route_hotspot_ID
    ↓
Fetch hotspot record to get master hotspot_ID
    ↓
Hard delete from dvi_itinerary_route_hotspot_details
    ↓
Add master hotspot_ID to route.excluded_hotspot_ids
    ↓
Call rebuildRouteHotspots()
```

### 2. Rebuild Flow with Exclusion
```
rebuildRouteHotspots() called
    ↓
Delete active (deleted=0) hotspots
    ↓
Call timelineBuilder.buildTimelineForPlan()
    ↓
    └─→ Call selector.selectForRoute() for each route
        ↓
        Route object includes excluded_hotspot_ids
        ↓
        Build excludedHotspotIds Set from route.excluded_hotspot_ids
        ↓
        For each candidate hotspot:
            if (excludedHotspotIds.has(hotspot.hotspot_ID)) skip ✅
        ↓
Insert new timeline rows (without excluded hotspots)
```

### 3. Selector Logic (timeline.hotspot-selector.ts)
```typescript
// Read the exclusion list from route data
const routeExcluded = (route as any).excluded_hotspot_ids || [];
const excludedHotspotIds: Set<number> = new Set<number>(
  Array.isArray(routeExcluded) ? routeExcluded.map((id: any) => Number(id)) : [],
);

// Apply exclusion across all selection buckets
for (const h of this.deps.allHotspots) {
  const hId = Number((h as any).hotspot_ID);
  if (excludedHotspotIds.has(hId)) continue;  // ✅ Skip excluded hotspots
  // ... process hotspot for selection
}
```

## Data Flow Example

### Before Fix
```
Hotspot: Kapaleeshwarar Temple
  Master ID (hotspot_ID): 4
  Route Instance ID (route_hotspot_ID): 2720978

Delete Action:
  → Stores 2720978 in excluded_hotspot_ids

Rebuild:
  → Selector checks: Does excluded list contain 4?
  → NO → Hotspot reappears ❌
```

### After Fix
```
Hotspot: Kapaleeshwarar Temple
  Master ID (hotspot_ID): 4
  Route Instance ID (route_hotspot_ID): 2720978

Delete Action:
  → Fetches hotspot record to get hotspot_ID
  → Stores 4 in excluded_hotspot_ids

Rebuild:
  → Selector checks: Does excluded list contain 4?
  → YES → Hotspot properly excluded ✅
```

## Database Tables Involved

### dvi_itinerary_route_details
- **excluded_hotspot_ids** (JSON): Stores array of master hotspot IDs to exclude
- Example: `[4, 11, 5, 453]`

### dvi_itinerary_route_hotspot_details
- **route_hotspot_ID**: Unique ID for this route's instance of a hotspot
- **hotspot_ID**: Master hotspot ID (references dvi_hotspot_place)
- **deleted**: Soft delete flag (0 = active, 1 = soft deleted)

## Verification

### Database Check
After deletion, the database should show:
- ✅ Hotspot removed from active records (deleted = 0)
- ✅ Master hotspot_ID in route.excluded_hotspot_ids array
- ✅ Rebuild produces timeline without excluded hotspots

### Example Output
```
Route 207447:
  - excluded_hotspot_ids: [4]  (Master ID)
  - Active hotspots: [11, 5, 453, 454, 455, 456]
  - After rebuild: Still [11, 5, 453, 454, 455, 456] ✅
```

## Impact

- **Fixes:** Hotspots no longer reappear after deletion
- **Maintains:** All existing deletion workflow logic
- **Preserves:** Exclusion list across multiple rebuilds
- **Compatible:** Works with manual hotspots and auto-selection

## Related Files

- `src/modules/itineraries/itineraries.service.ts` - deleteHotspot() method (MODIFIED)
- `src/modules/itineraries/engines/helpers/timeline.hotspot-selector.ts` - selectForRoute() method (uses exclusion list)
- `src/modules/itineraries/engines/hotspot-engine.service.ts` - rebuildRouteHotspots() method (orchestrates rebuild)
