# ResAvenue - 3 Configured Cities Reference

## Summary

ResAvenue has **3 test hotels configured** across 3 cities in India:

| City       | Hotel Name       | ResAvenue Code | State              | Category |
|------------|------------------|----------------|-------------------|----------|
| Gwalior    | PMS Test Hotel   | **261**        | Madhya Pradesh    | 3-star   |
| Darjiling  | TM Globus        | **285**        | West Bengal       | 3-star   |
| Mumbai     | TMahal Palace    | **1098**       | Maharashtra       | 4-star   |

## Database Check

### Verify Hotels Exist

```sql
SELECT 
  hotel_id, 
  hotel_name, 
  hotel_city, 
  resavenue_hotel_code,
  hotel_code,
  status,
  deleted
FROM dvi_hotel 
WHERE resavenue_hotel_code IS NOT NULL 
AND deleted = 0
ORDER BY hotel_city;
```

Expected results:
```
hotel_id | hotel_name      | hotel_city | resavenue_hotel_code | status | deleted
---------|-----------------|------------|---------------------|--------|--------
XXX      | TM Globus       | Darjiling  | 285                 | 1      | 0
XXX      | PMS Test Hotel  | Gwalior    | 261                 | 1      | 0
XXX      | TMahal Palace   | Mumbai     | 1098                | 1      | 0
```

### If Hotels Missing - Insert Them

Run the insert script:
```bash
cd dvi_backend
npx ts-node insert-resavenue-hotels.ts
```

Or use SQL:
```bash
cd dvi_backend
mysql -u root -p dvi_travels < add-resavenue-hotels.sql
```

## Testing Each City

### 1. Test Gwalior (PMS Test Hotel - Code: 261)

**Search Hotels:**
```bash
curl -X POST http://localhost:4006/api/v1/hotels/search \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -d '{
    "cityCode": "Gwalior",
    "checkInDate": "2026-04-10",
    "checkOutDate": "2026-04-15",
    "roomCount": 1,
    "guestCount": 2,
    "providers": ["resavenue"]
  }'
```

**Expected Response:**
```json
{
  "success": true,
  "hotels": [
    {
      "hotelCode": "RESAVENUE-261",
      "hotelName": "PMS Test Hotel",
      "hotelCity": "Gwalior",
      "provider": "resavenue",
      "rooms": [
        {
          "roomCode": "386-524",
          "roomType": "Deluxe Double",
          "mealPlan": "AP",
          "price": 2500,
          "available": true
        }
      ]
    }
  ]
}
```

### 2. Test Darjiling (TM Globus - Code: 285)

**Search Hotels:**
```bash
curl -X POST http://localhost:4006/api/v1/hotels/search \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -d '{
    "cityCode": "Darjiling",
    "checkInDate": "2026-04-10",
    "checkOutDate": "2026-04-15",
    "roomCount": 1,
    "guestCount": 2,
    "providers": ["resavenue"]
  }'
```

### 3. Test Mumbai (TMahal Palace - Code: 1098)

**Search Hotels:**
```bash
curl -X POST http://localhost:4006/api/v1/hotels/search \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -d '{
    "cityCode": "Mumbai",
    "checkInDate": "2026-04-10",
    "checkOutDate": "2026-04-15",
    "roomCount": 1,
    "guestCount": 2,
    "providers": ["resavenue"]
  }'
```

## Understanding ResAvenue Room Codes

ResAvenue uses a **combined room code format**:
```
{InvCode}-{RateCode}
```

### Example from Gwalior (Hotel 261):

| Room Type       | InvCode | Rate Plan    | RateCode | Combined Code |
|-----------------|---------|--------------|----------|---------------|
| Deluxe Double   | 386     | AP (Meal)    | 524      | **386-524**   |
| Deluxe Double   | 386     | CP (Meal)    | 527      | **386-527**   |
| Deluxe Double   | 386     | EP (No Meal) | 1935     | **386-1935**  |
| Executive Double| 387     | AP (Meal)    | 524      | **387-524**   |
| Standard Double | 512     | CP (Meal)    | 527      | **512-527**   |

### When Booking:
- Frontend displays: `"roomCode": "386-524"`
- Backend splits it: `roomTypeCode: "386"`, `ratePlanCode: "524"`
- Sends to ResAvenue API as separate fields

## Complete Test Flow

### Step 1: Search Hotels
```javascript
// Test file: tmp/test-resavenue-api.js
const searchCriteria = {
  cityCode: 'Gwalior', // or 'Darjiling', 'Mumbai'
  checkInDate: '2026-04-10',
  checkOutDate: '2026-04-15',
  roomCount: 1,
  guestCount: 2,
  providers: ['resavenue']
};
```

### Step 2: Confirm Booking
Use actual room code from search results:
```javascript
const confirmData = {
  itineraryPlanId: 123, // Valid itinerary ID from your DB
  hotels: [
    {
      hotelCode: 'RESAVENUE-261', // From search results
      provider: 'resavenue',
      bookingCode: '386-524', // Actual room code from search
      checkInDate: '2026-04-10',
      checkOutDate: '2026-04-15',
      numberOfRooms: 1,
      netAmount: 2500,
      guestNationality: 'IN',
      guests: [
        {
          title: 'Mr',
          firstName: 'Test',
          lastName: 'Guest',
          email: 'test@example.com',
          phone: '+919876543210'
        }
      ]
    }
  ]
};
```

### Step 3: Cancel Booking
```javascript
const cancelData = {
  confirmedItineraryPlanId: 456 // From confirm response
};
```

## Architecture Notes

### Why No City Codes?
Unlike TBO (has `tbo_city_code`) and HOBSE (has `hobse_city_code`), ResAvenue does **not** use city-level codes in the schema. 

**Reason**: ResAvenue is a **Property Management System (PMS)**, not an OTA:
- Each hotel has its own unique code
- No city aggregation needed
- Direct property-to-property mapping

### Database Query Pattern
```typescript
// Provider searches by city name, not city code
const hotels = await prisma.dvi_hotel.findMany({
  where: {
    hotel_city: 'Gwalior', // City name, not code
    resavenue_hotel_code: { not: null },
    deleted: false,
    status: 1,
  },
});
```

## Quick Test Commands

### 1. Check if hotels exist in DB:
```bash
# Using Node.js (if mysql CLI not available)
cd dvi_backend
npx ts-node -e "
const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();
(async () => {
  const hotels = await prisma.dvi_hotel.findMany({
    where: { resavenue_hotel_code: { not: null }, deleted: false },
    select: { hotel_id: true, hotel_name: true, hotel_city: true, resavenue_hotel_code: true }
  });
  console.table(hotels);
  await prisma.\$disconnect();
})();
"
```

### 2. Run automated test:
```bash
cd dvi_backend
node tmp/test-resavenue-api.js
```

### 3. Test specific city via cURL:
```bash
# Gwalior
curl -X POST http://localhost:4006/api/v1/hotels/search \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $(grep JWT_SECRET .env | cut -d '=' -f2)" \
  -d '{"cityCode":"Gwalior","checkInDate":"2026-04-10","checkOutDate":"2026-04-15","roomCount":1,"guestCount":2,"providers":["resavenue"]}'
```

## Troubleshooting

### Issue: 400 - No hotels found
**Cause**: Hotels not in database
**Solution**: Run `npx ts-node insert-resavenue-hotels.ts`

### Issue: 500 - ResAvenue API error
**Cause**: Invalid credentials or hotel code not recognized by ResAvenue
**Solution**: 
1. Check `.env` for `RESAVENUE_USERNAME` and `RESAVENUE_PASSWORD`
2. Verify hotel code (261, 285, 1098) is valid in ResAvenue system

### Issue: Room codes not returning from search
**Cause**: ResAvenue API not returning room/rate details
**Solution**: Check backend logs for PropertyDetails API response

### Issue: Booking confirmation fails
**Cause**: Invalid DTO format or missing itinerary
**Solution**: Ensure DTO matches ConfirmQuotationDto with all required fields:
- `itinerary_plan_ID` (number)
- `agent` (number)
- `primary_guest_*` fields (string)
- `arrival_date_time`, `departure_date_time` (string)
- `price_confirmation_type` (string)

## Files Reference

| File | Purpose |
|------|---------|
| [RESAVENUE_SCHEMA_SETUP.md](RESAVENUE_SCHEMA_SETUP.md) | Full setup guide with schema changes |
| [insert-resavenue-hotels.ts](insert-resavenue-hotels.ts) | TypeScript script to insert 3 hotels |
| [add-resavenue-hotels.sql](add-resavenue-hotels.sql) | SQL script to insert 3 hotels |
| [tmp/test-resavenue-api.js](tmp/test-resavenue-api.js) | JavaScript test file for APIs |
| [test-resavenue-booking-api.ts](test-resavenue-booking-api.ts) | TypeScript automated test suite |
| [RESAVENUE_API_TEST_GUIDE.md](RESAVENUE_API_TEST_GUIDE.md) | Complete API reference (600+ lines) |

## Next Steps

1. ✅ **Verify Hotels in DB**: Run the check query above
2. ✅ **Insert if Missing**: Run `npx ts-node insert-resavenue-hotels.ts`
3. ✅ **Test Search**: Use Gwalior as test city (most reliable)
4. ⏳ **Test Booking**: Once search returns rooms, test confirm endpoint
5. ⏳ **Test Cancellation**: After successful booking, test cancel

---

**Last Updated**: Current Session  
**Status**: 3 cities configured, ready for testing  
**Primary Test City**: Gwalior (PMS Test Hotel - Code 261)
