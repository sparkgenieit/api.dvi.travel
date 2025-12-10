# INVESTIGATION SUMMARY

## Problem
Route 2 (410/413) hotspots don't match PHP Plan 2 Route 179:
- **NestJS**: [4, 18, 25, 21]
- **PHP**: [4, 18, 21, 19, 17, 678]
- **Missing**: 19, 17, 678
- **Extra**: 25

## Key Findings

### 1. Wait Logic EXISTS (Mystery Source)
- **Confirmed**: NestJS DOES create wait breaks (`allow_break_hours=1`)
- Fresh optimization run creates:
  - Route 413, Order 5: 13:19-13:30 (wait for hotspot 18 lunch break)
  - Route 413, Order 8: 14:49-16:00 (wait for hotspot 25)
- **Source Code**: Cannot locate where these breaks are created!
  - Not in timeline.builder.ts hotspot loop
  - Not in refreshment.builder.ts (only for initial breaks)
  - Not in travel/hotspot/hotel builders
  - No database triggers
  - May be in a helper function or injected during row construction

### 2. PHP Comparison
- **PHP Plan 2 Route 179**: Has ZERO wait breaks with `allow_break_hours=1`
- PHP likely implements wait logic differently (not as separate break rows?)
- Or PHP avoids hotspots that require long waits

### 3. Hotspot Selection Issue
**Hotspot 25 (Manakula Vinayagar Temple)**:
- Location: "Pondicherry Airport|Pondicherry"
- Priority: 2 (HIGHER than 19 and 17!)
- Duration: 30 minutes
- Operating hours: 05:45-12:30, 16:00-20:00
- Requires 71-minute wait (14:49-16:00)

**Missing Hotspots**:
- Hotspot 19 (Promenade Beach): Priority 5
- Hotspot 17 (Sri Aurobindo Ashram): Priority 6
- Hotspot 678 (French Colony): Priority 0

**Current NestJS Sort Logic**:
```typescript
// Sorts by: priority ASC (lower number = higher priority), then distance ASC
// 0 priority goes last
```

**Issue**: NestJS correctly prioritizes hotspot 25 (priority 2) over 19/17 (priorities 5/6)
But PHP selects 19/17 and skips 25. Why?

## Hypotheses

1. **PHP Skip slong waits**: PHP may reject hotspots requiring >1 hour wait
2. **PHP sorts differently**: Maybe not just priority, could include:
   - Distance
   - Visit duration
   - Operating hours alignment
   - Time of day

## Next Steps

1. Check PHP sorting logic for DESTINATION hotspots
2. Check if PHP has max wait threshold
3. Analyze why PHP selects priority-5/6 over priority-2
4. Find where NestJS wait breaks are actually created!

