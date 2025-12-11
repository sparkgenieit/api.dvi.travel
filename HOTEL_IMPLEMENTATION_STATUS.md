# Hotel Selection Implementation Status

## Summary
Implemented hotel selection logic in NestJS to match PHP behavior for plan_id comparisons.

## Changes Made

### 1. hotel-engine.service.ts
- **Status**: ✅ IMPLEMENTED
- **Location**: `src/modules/itineraries/engines/hotel-engine.service.ts`
- **Changes**:
  - Injected `HotelPricingService` in constructor
  - Added hotel category parsing from plan details (supports comma-separated values)
  - Implemented hotel selection using `pickHotelByCategory(category, city)`
  - Added room price fetching from price books using `getRoomPrices(hotelId, date)`
  - Added meal price fetching using `getMealPrice(hotelId, date)`
  - Populated actual hotel data instead of zeros:
    - `hotel_id`: Real hotel ID from selection
    - `room_type_id`: Looked up from room master
    - `room_id`: First available room with rate > 0
    - `room_rate`: Actual room rate from price book
    - `breakfast_cost_per_person`: From meal pricing
    - `total_breafast_cost`: breakfast cost × total persons
  - Updated header records to use actual hotel_id and aggregated costs

### 2. itinerary.module.ts  
- **Status**: ✅ IMPLEMENTED
- **Location**: `src/modules/itineraries/itinerary.module.ts`
- **Changes**:
  - Added `HotelPricingService` to providers array
  - Ensures service is available for dependency injection

## Expected Results

### Before
```
Plan ID 5 (NestJS):
- hotel_id: 0
- room_id: 0
- room_rate: 0
- total_breafast_cost: 0
```

### After
```
Plan ID 5 (NestJS):
- hotel_id: [actual hotel ID based on category + city]
- room_id: [actual room ID from price book]
- room_rate: [actual rate from price book]
- total_breafast_cost: [breakfast cost × persons]
```

## Testing Instructions

1. **Start Server**:
   ```powershell
   npm run start:dev
   ```

2. **Wait for startup** (look for "Nest application successfully started")

3. **Trigger Plan 5 Regeneration**:
   ```powershell
   node tmp\trigger_optimization.js
   ```

4. **Quick Check**:
   ```powershell
   node tmp\quick_compare.js
   ```

5. **Full Comparison**:
   ```powershell
   node tmp\compare_plan_data.js
   ```

## Implementation Details

### Hotel Selection Flow
1. Get preferred_hotel_category from plan details (default: 2)
2. For each route:
   - Extract city from `location_name`
   - Call `HotelPricingService.pickHotelByCategory(category, city)`
   - If hotel found:
     - Fetch room prices for that hotel + date
     - Pick first room with rate > 0
     - Fetch meal prices  
     - Create room details with real data
   - If no hotel found:
     - Create placeholder with zeros

### Meal Cost Calculation
- Breakfast cost: `breakfast_cost_per_person × (total_adult + total_children + total_infants)`
- Currently only breakfast is required (lunch/dinner = 0)

### Header Aggregation
- Aggregates `total_room_cost` and `total_breafast_cost` from room details
- Sets `total_hotel_cost` = room cost + breakfast cost
- Looks up `hotel_id` from first room detail for consistency

## Git Commits
1. `feat: implement hotel selection in HotelEngineService` - Main implementation
2. `fix: remove duplicate code in HotelEngineService` - Cleaned up accidental duplication

## Next Steps
1. ✅ Test hotel selection with real data
2. Compare Plan 2 vs Plan 5 hotel fields
3. Fix any discrepancies in:
   - Hotel category logic
   - Room selection criteria  
   - Meal pricing
4. Address remaining issues:
   - Toll/parking/permit charges (still 0)
   - Time duration calculations (still NULL)

## Notes
- HotelPricingService already existed in codebase (used by PHP legacy)
- Implementation mirrors PHP logic: pick first hotel matching category/city
- Room selection uses first available room with non-zero rate
- All monetary calculations use Number() to avoid type issues
