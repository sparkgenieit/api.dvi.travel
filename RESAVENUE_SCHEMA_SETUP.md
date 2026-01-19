# ResAvenue Integration - Schema Changes & Setup

## 1. Schema Changes

### Prisma Schema Update
Added `resavenue_hotel_code` field to `dvi_hotel` model:

```prisma
model dvi_hotel {
  hotel_id                String?   @db.VarChar(200)
  tbo_hotel_code          String?   @db.VarChar(200) // Base TBO hotel code
  tbo_city_code           String?   @db.VarChar(32)   // TBO city code
  resavenue_hotel_code    String?   @db.VarChar(200) // ResAvenue hotel code ⬅️ NEW
  // ... other fields
  
  @@index([resavenue_hotel_code], map: "idx_hotel_resavenue_hotel_code") ⬅️ NEW INDEX
}
```

### Database Migration

**Using Prisma DB Push (Direct Schema Sync)**
```bash
cd dvi_backend
npx prisma db push
```

This will:
- Detect schema changes in `schema.prisma`
- Apply changes directly to database (adds `resavenue_hotel_code` column + index)
- Skip migration history files

## 2. Test Hotels Data

### 3 ResAvenue Test Properties

| Sr No | Property Name    | Hotel ID | City      | State           |
|-------|------------------|----------|-----------|-----------------|
| 1     | PMS Test Hotel   | 261      | Gwalior   | Madhya Pradesh  |
| 2     | TM Globus        | 285      | Darjiling | West Bengal     |
| 3     | TMahal Palace    | 1098     | Mumbai    | Maharashtra     |

## 3. Insert Hotels into Database

### Option A: Using TypeScript Script (Recommended)
```bash
cd dvi_backend
npx ts-node insert-resavenue-hotels.ts
```

This script will:
- ✅ Check if hotels already exist (prevents duplicates)
- ✅ Insert 3 ResAvenue test hotels
- ✅ Display summary with all ResAvenue hotels
- ✅ Use Prisma for type-safe operations

### Option B: Direct SQL Insert
Already included in `add-resavenue-hotels.sql` file

## 4. Verification

After running the migration and insert script:

```sql
-- Check ResAvenue hotels
SELECT 
    hotel_id,
    hotel_name,
    hotel_code,
    resavenue_hotel_code,
    hotel_city,
    hotel_state,
    status
FROM dvi_hotel 
WHERE resavenue_hotel_code IS NOT NULL
ORDER BY hotel_id DESC;

-- Should return 3 hotels:
-- PMS Test Hotel (261) - Gwalior
-- TM Globus (285) - Darjiling  
-- TMahal Palace (1098) - Mumbai
```

## 5. Files Created

1. **Schema Changes:**
   - ✅ `prisma/schema.prisma` - Updated with `resavenue_hotel_code` field

2. **Migration SQL:**
   - ✅ `add-resavenue-hotels.sql` - Complete SQL for schema + data

3. **TypeScript Insert Script:**
   - ✅ `insert-resavenue-hotels.ts` - Safe insertion with duplicate check

## 6. Next Steps

After schema changes are applied:

1. **Push Schema to Database:**
   ```bash
   cd dvi_backend
   npx prisma db push
   ```

2. **Run Insert Script:**
   ```bash
   npx ts-node insert-resavenue-hotels.ts
   ```

3. **Verify Hotels:**
   - Check database for 3 ResAvenue hotels
   - Verify `resavenue_hotel_code` values: 261, 285, 1098

4. **Backend Integration:**
   - Create ResAvenue provider service (similar to TBO)
   - Add ResAvenue API endpoints
   - Implement hotel search/booking flow

## 7. Field Mapping

### TBO vs ResAvenue Comparison

| Purpose          | TBO Field        | ResAvenue Field         |
|------------------|------------------|-------------------------|
| Hotel Identifier | `tbo_hotel_code` | `resavenue_hotel_code` |
| City Code        | `tbo_city_code`  | N/A (use `hotel_city`) |
| Search Method    | City-based (OTA) | Property-based (PMS)   |

**Key Difference:**
- **TBO:** Search by city → Get all hotels in that city
- **ResAvenue:** Query specific hotel by ID → Get availability/rates

## 8. Integration Architecture

```
Frontend Search Request
    ↓
Backend Controller
    ↓
Search Service
    ├── TBO Provider (city-based search)
    │   └── Queries TBO API by city code
    │
    └── ResAvenue Provider (property-based search)
        └── Queries DB for hotels in city
        └── For each hotel: Fetch inventory + rates from ResAvenue API
    ↓
Merge & Sort Results
    ↓
Return to Frontend
```

## 9. ResAvenue API Summary

Based on successful tests:

✅ **Property Details API** - Master data (room types, rate plans)
✅ **Inventory Fetch API** - Date-specific room availability (InvCount)
✅ **Rate Fetch API** - Date-specific pricing (Single/Double rates, MinStay, MaxStay)
✅ **Booking Push API** - Create bookings in PMS
✅ **Booking Cancel API** - Cancel bookings

**Test Results (2026-01-20):**
- Inventory Fetch: 200 OK - Returns availability for 3 rooms (386, 387, 512)
- Rate Fetch: 200 OK - Returns pricing for 2 rate plans (524, 1935)

## 10. Database Schema Design

### Hotel Source Identification

A hotel can have:
- `tbo_hotel_code` → Hotel available via TBO
- `resavenue_hotel_code` → Hotel available via ResAvenue
- Both → Hotel available via both providers

**Query Examples:**

```typescript
// Find all TBO hotels
const tboHotels = await prisma.dvi_hotel.findMany({
  where: { tbo_hotel_code: { not: null }, deleted: false }
});

// Find all ResAvenue hotels
const resavenueHotels = await prisma.dvi_hotel.findMany({
  where: { resavenue_hotel_code: { not: null }, deleted: false }
});

// Find hotels in Mumbai from ResAvenue
const mumbaiHotels = await prisma.dvi_hotel.findMany({
  where: {
    hotel_city: 'Mumbai',
    resavenue_hotel_code: { not: null },
    deleted: false
  }
});

// Find specific ResAvenue hotel
const hotel = await prisma.dvi_hotel.findFirst({
  where: { resavenue_hotel_code: '261' }
});
```

---

**Status:** Schema ready for migration ✅  
**Next:** Run migration → Insert hotels → Build ResAvenue provider service
