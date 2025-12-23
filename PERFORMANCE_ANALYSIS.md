# Database Performance Analysis

## Current Performance Metrics
- **Original**: 26 seconds
- **After optimizations**: 7.6 seconds (71% improvement)
- **Remaining bottleneck**: Hotel Engine (3.3s) + Timeline (3.8s)

---

## 1. CRITICAL: Missing Composite Index (⚡ HIGH IMPACT)

### `dvi_stored_locations` - Distance Lookups
**Current Query Pattern** (45 times per request):
```sql
WHERE deleted = 0 
  AND status = 1 
  AND source_location = ? 
  AND destination_location = ?
ORDER BY location_ID DESC
LIMIT 1
```

**Problem**: No composite index covering this exact query pattern.

**Current Indexes**:
- ❌ `idx_stored_locations_deleted` (deleted only)
- ❌ `idx_stored_locations_status` (status only)
- ❌ `idx_main_source_location` (source_location only - length 768)
- ❌ `idx_main_destination_location` (destination_location only - length 768)
- ⚠️ `idx_loc_deleted_status` (deleted, status, location_ID) - missing location names

**Solution**: Add composite index with proper column order:
```prisma
@@index([deleted, status, source_location(length: 255), destination_location(length: 255)], 
  map: "idx_location_lookup_composite")
```

**Why this helps**:
- MySQL can use all 4 columns in WHERE clause
- Covers the exact query pattern
- Reduced length (255 vs 768) improves index size
- ORDER BY location_ID can use filesort (fast with filtered results)

**Expected Impact**: 
- Distance cache first miss: ~267ms → ~50ms (80% faster)
- Initial query for new city pairs becomes near-instant

---

## 2. Hotel Queries Optimization (⚡ MEDIUM IMPACT)

### A. `dvi_hotel` - Hotel Selection by Category + City
**Current Query Pattern** (12 times per request):
```sql
WHERE hotel_category = ? 
  AND hotel_city = ?
```

**Current Indexes**:
- ✅ `idx_hotel_hotel_category` (category only)
- ✅ `idx_hotel_hotel_city` (city only)
- ❌ No composite index

**Solution**:
```prisma
@@index([hotel_category, hotel_city], map: "idx_hotel_category_city")
```

**Expected Impact**: 20-30% faster hotel lookups

---

### B. `dvi_hotel_room_price_book` - Room Price Queries
**Current Query Pattern** (12 times per request):
```sql
WHERE hotel_id = ? 
  AND year = ? 
  AND month = ?
```

**Current Indexes**:
- ✅ `idx_hotel_room_price_book_hotel_id`
- ✅ `idx_hotel_room_price_book_year`
- ✅ `idx_hotel_room_price_book_month`
- ❌ No composite index

**Solution**:
```prisma
@@index([hotel_id, year, month], map: "idx_hotel_pricing_lookup")
```

**Expected Impact**: 30-40% faster room price queries

---

### C. `dvi_hotel_meal_price_book` - Meal Price Queries
**Current Query Pattern** (12 times per request):
```sql
WHERE hotel_id = ? 
  AND year = ? 
  AND month = ?
```

**Same solution as room prices**:
```prisma
@@index([hotel_id, year, month], map: "idx_hotel_meal_pricing_lookup")
```

**Expected Impact**: 30-40% faster meal price queries

---

## 3. Hotspot Timing Optimization (✅ ALREADY OPTIMIZED)

### `dvi_hotspot_timing` - Operating Hours
**Query Pattern**: Batch-fetched 6463 records once
**Current Index**: `@@index([hotspot_ID])` ✅ Adequate
**Status**: No action needed - already using batch fetch + map

---

## 4. Schema Design Issues (⚠️ LONG-TERM)

### Problem: Wide Calendar Tables
Both `dvi_hotel_room_price_book` and `dvi_hotel_meal_price_book` use anti-pattern:
- 31 columns: `day_1`, `day_2`, ..., `day_31`
- Separate indexes on each day column (31 indexes!)
- Wasteful: Not all months have 31 days

**Better Design** (requires migration):
```prisma
model dvi_hotel_room_prices {
  id         Int      @id @default(autoincrement())
  hotel_id   Int
  room_id    Int
  price_date Date     @db.Date
  rate       Float
  status     Int
  deleted    Int
  
  @@index([hotel_id, price_date])
  @@index([room_id, price_date])
}
```

**Benefits**:
- 1 composite index instead of 31 separate indexes
- Native date filtering
- Easier queries: `WHERE price_date = ?`
- Smaller index size
- Better query planner optimization

**Trade-off**: Requires data migration and code changes

---

## 5. Text Field Length Optimization (⚠️ MINOR IMPACT)

### `dvi_stored_locations`
**Current**: `source_location LONGTEXT`, `destination_location LONGTEXT`
**Problem**: LONGTEXT is overkill for city names, prevents efficient indexing
**Better**: `VARCHAR(255)` for city/location names

**Impact**: 
- Smaller indexes
- Faster string comparisons
- More efficient memory usage

---

## Implementation Priority

### Phase 1: Quick Wins (⚡ Immediate - 1 hour)
1. **Add composite index for distance lookups** (CRITICAL)
   ```sql
   CREATE INDEX idx_location_lookup_composite 
   ON dvi_stored_locations(deleted, status, source_location(255), destination_location(255));
   ```
   **Expected**: Additional 1-2 second improvement on cold starts

2. **Add hotel query composite indexes**
   ```sql
   CREATE INDEX idx_hotel_category_city ON dvi_hotel(hotel_category, hotel_city);
   CREATE INDEX idx_hotel_pricing_lookup ON dvi_hotel_room_price_book(hotel_id, year, month);
   CREATE INDEX idx_hotel_meal_pricing_lookup ON dvi_hotel_meal_price_book(hotel_id, year, month);
   ```
   **Expected**: 0.5-1 second improvement in hotel engine

### Phase 2: Code Optimization (Already Done! ✅)
- ✅ Distance caching (saved 10.5s)
- ✅ Hotel parallelization (saved 3s)
- ✅ Hotspot/timing batch fetch (saved 5s)

### Phase 3: Long-term (📅 Future - Days/Weeks)
- Normalize price book tables (day_1...day_31 → date column)
- Optimize text field lengths
- Consider read replicas for reporting queries

---

## Estimated Total Performance After Phase 1

**Current**: 7.6 seconds
**After indexes**: ~5-6 seconds (20-30% additional improvement)
**Total from original**: 26s → 5s (80% improvement)

---

## Testing Index Performance

Run this after adding indexes:
```sql
-- Check if index is used
EXPLAIN SELECT * FROM dvi_stored_locations 
WHERE deleted = 0 
  AND status = 1 
  AND source_location = 'Madurai' 
  AND destination_location = 'Rameswaram'
ORDER BY location_ID DESC LIMIT 1;

-- Should show: 
-- key: idx_location_lookup_composite
-- type: ref (good) or range (acceptable)
```

---

## Monitoring Queries

Add to application startup:
```typescript
// Log slow queries
await prisma.$executeRaw`SET GLOBAL slow_query_log = 'ON'`;
await prisma.$executeRaw`SET GLOBAL long_query_time = 0.1`; // Log queries > 100ms
```

Check slow query log:
```bash
cat /var/log/mysql/slow-query.log
```
