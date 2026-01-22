# ResAvenue Booking & Cancellation API - Quick Reference

## Files Created

1. **Test Script:** `test-resavenue-booking-api.ts`
   - Automated test suite for ResAvenue APIs
   - Tests booking, cancellation, and search
   - Run: `npx tsx test-resavenue-booking-api.ts`

2. **Test Controller:** `src/modules/itineraries/resavenue-test.controller.ts`
   - REST endpoints for testing ResAvenue provider
   - 5 test endpoints + health check
   - Base path: `/api/v1/test/resavenue`

3. **Documentation:** `RESAVENUE_API_TEST_GUIDE.md`
   - Complete API reference
   - cURL examples
   - Troubleshooting guide

---

## Quick Test Commands

### 1. Run Test Script
```bash
cd d:\wamp64\www\dvi_fullstack\dvi_backend
npx tsx test-resavenue-booking-api.ts
```

### 2. Test Endpoints (Backend must be running)

**Health Check:**
```bash
curl -X POST http://localhost:4006/api/v1/test/resavenue/health-check \
  -H "Content-Type: application/json" -d "{}"
```

**Test Booking:**
```bash
curl -X POST http://localhost:4006/api/v1/test/resavenue/test-booking \
  -H "Content-Type: application/json" \
  -d '{
    "hotelCode": "RSAV_HOTEL_001",
    "checkInDate": "2026-02-20",
    "checkOutDate": "2026-02-22",
    "invCode": 1234,
    "rateCode": 5678,
    "numberOfRooms": 1,
    "guests": [
      {"firstName": "John", "lastName": "Doe", "email": "test@test.com", "phone": "+919876543210"}
    ]
  }'
```

**Test Cancellation:**
```bash
curl -X POST http://localhost:4006/api/v1/test/resavenue/test-cancellation \
  -H "Content-Type: application/json" \
  -d '{
    "bookingReference": "DVI-1737331200000",
    "reason": "Test cancellation"
  }'
```

---

## Test Endpoints Available

| Endpoint | Method | Purpose |
|----------|--------|---------|
| `/api/v1/test/resavenue/health-check` | POST | Check provider configuration |
| `/api/v1/test/resavenue/test-booking` | POST | Test booking confirmation |
| `/api/v1/test/resavenue/test-cancellation` | POST | Test booking cancellation |
| `/api/v1/test/resavenue/test-confirmation-details` | POST | Get booking details |
| `/api/v1/test/resavenue/test-booking-service` | POST | Test booking service layer |

---

## Production Endpoints

| Endpoint | Method | Purpose |
|----------|--------|---------|
| `/api/v1/hotels/search` | POST | Search hotels (include ResAvenue) |
| `/api/v1/itineraries/confirm-quotation` | POST | Confirm itinerary with hotels |
| `/api/v1/itineraries/cancel` | POST | Cancel itinerary (includes hotels) |

---

## ResAvenue API Details

**Configuration:**
- Base URL: `http://203.109.97.241:8080/ChannelController`
- Endpoint: `/PropertyDetails`
- Username: `testpmsk4@resavenue.com`
- Password: `testpms@123`

**Room Code Format:** `InvCode-RateCode` (e.g., `1234-5678`)

**Booking Request:**
```json
{
  "OTA_HotelResNotifRQ": {
    "Target": "Production",
    "Version": "1.0",
    "ResStatus": "Confirm",
    "HotelReservations": { ... }
  }
}
```

**Cancellation Request:**
```json
{
  "OTA_HotelResNotifRQ": {
    "Target": "Production",
    "Version": "1.0",
    "ResStatus": "Cancel",
    "HotelReservations": { ... }
  }
}
```

---

## Implementation Summary

✅ **ResAvenueHotelProvider** - Complete provider implementation
- `confirmBooking()` - Creates booking via OTA_HotelResNotifRQ
- `cancelBooking()` - Cancels booking via OTA_HotelResNotifRQ
- `getConfirmation()` - Retrieves booking details
- `search()` - Searches hotels in database and checks availability

✅ **ResAvenueHotelBookingService** - Service layer for itinerary bookings
- `confirmItineraryHotels()` - Books multiple hotels for itinerary
- `cancelItineraryHotels()` - Cancels all ResAvenue bookings for itinerary
- `saveResAvenueBookingConfirmation()` - Persists bookings to database

✅ **Database** - `resavenue_hotel_booking_confirmation` table
- Stores booking details
- Links to itinerary plans and routes
- Tracks status (active/cancelled)

✅ **Multi-Provider Integration**
- Works alongside TBO and HOBSE providers
- Automatic routing based on provider field
- Unified search interface

---

## Next Steps

1. **Start Backend:**
   ```bash
   cd d:\wamp64\www\dvi_fullstack\dvi_backend
   npm run start:dev
   ```

2. **Test Health Check:**
   ```bash
   curl -X POST http://localhost:4006/api/v1/test/resavenue/health-check -H "Content-Type: application/json" -d "{}"
   ```

3. **Get ResAvenue Hotels from Database:**
   ```sql
   SELECT id, hotel_name, resavenue_hotel_code 
   FROM dvi_hotel 
   WHERE resavenue_hotel_code IS NOT NULL 
   LIMIT 10;
   ```

4. **Test Booking with Real Hotel Code:**
   - Replace `RSAV_HOTEL_001` with actual code
   - Update `invCode` and `rateCode` from search results
   - Call test-booking endpoint

5. **Verify in Database:**
   ```sql
   SELECT * FROM resavenue_hotel_booking_confirmation 
   ORDER BY resavenue_hotel_booking_confirmation_ID DESC 
   LIMIT 1;
   ```

---

## Key Features

🎯 **Direct API Testing** - Test ResAvenue provider without full itinerary flow  
🔧 **Dedicated Endpoints** - 5 test endpoints for each provider method  
📝 **Complete Documentation** - API reference, examples, troubleshooting  
✅ **Production Ready** - Integrated into itinerary booking/cancellation  
🔄 **Multi-Provider** - Works with TBO, ResAvenue, and HOBSE  

---

## Support Files

- **Test Script:** `test-resavenue-booking-api.ts` (400+ lines)
- **Test Controller:** `src/modules/itineraries/resavenue-test.controller.ts` (200+ lines)
- **Full Guide:** `RESAVENUE_API_TEST_GUIDE.md` (600+ lines)
- **Provider:** `src/modules/hotels/providers/resavenue-hotel.provider.ts` (728 lines)
- **Booking Service:** `src/modules/itineraries/services/resavenue-hotel-booking.service.ts` (265 lines)
