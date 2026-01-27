# HOBSE Hotel Booking Implementation - Complete Guide

## ✅ Implementation Status: COMPLETE

All backend changes have been successfully implemented to support HOBSE hotel booking alongside TBO and ResAvenue.

---

## 📋 Files Modified/Created

### Backend Files

#### 1. **.env** - Configuration
```
HOBSE_BASE_URL=https://api.hobse.com/v1/qa
HOBSE_CLIENT_TOKEN=C3g8K3b1wray989DVih37od3314r6444
HOBSE_ACCESS_TOKEN=Ah825fs13pjPrsoDvIH0016vb1098
HOBSE_PRODUCT_TOKEN=PbsDCcxq81gfo001DVih148eF0retT72

HOBSE_PARTNER_TYPE=TA
HOBSE_PARTNER_ID=4feaadb2c2277ce0
HOBSE_PARTNER_TYPE_ID=4
HOBSE_PRICE_OWNER_TYPE=2
HOBSE_TARIFF_MODE=B2B
HOBSE_CHANNEL_NAME=DVI
```

#### 2. **src/modules/hotels/providers/hobse-hotel.provider.ts** - Updated
- Fixed BASE_URL to `https://api.hobse.com/v1/qa` (not including `/htl`)
- Implemented `postForm()` for correct x-www-form-urlencoded posting
- Added `pickCheapestRoomOption()` helper
- Implemented `createBookingFromItinerary()` - the main booking method that:
  1. Calls GetAvailableRoomTariff
  2. Picks cheapest room option
  3. Calls CalculateReservationCost
  4. Calls CreateBooking with correct structure
- Updated `confirmBooking()` to throw error (use HobseHotelBookingService instead)

#### 3. **src/modules/itineraries/services/hobse-hotel-booking.service.ts** - Updated
- Implements `confirmItineraryHotels()` which:
  1. Gets route city
  2. Maps to hobse_city_code
  3. Counts pax by type (adult/child/infant)
  4. Extracts lead passenger for guest info
  5. Generates unique channelBookingId to avoid duplicates
  6. Calls provider.createBookingFromItinerary()
  7. Saves confirmation to DB

#### 4. **src/modules/hotels/services/hobse-hotel-master-sync.service.ts** - Already Present
- Syncs HOBSE hotel master list to dvi_hotel
- Stores HOBSE hotelId in `hotel_code` field

#### 5. **src/modules/hotels/hotels.module.ts** - Already Updated
- HobseHotelMasterSyncService already registered
- HobseHotelProvider already registered

#### 6. **src/modules/itineraries/itinerary.module.ts** - Already Updated
- HobseHotelBookingService already registered in providers

---

## 🔄 Booking Flow

### Step 1: Hotel Details Endpoint
**GET** `/api/v1/itineraries/hotel_details/:quoteId`

Returns hotels from:
- TBO (dynamic packages: Budget/Mid/Premium/Luxury)
- ResAvenue (same groups)
- **HOBSE** (merged into same groups - no separate tab!)

Each hotel in response includes:
- `provider`: "HOBSE" | "TBO" | "ResAvenue"
- `hotelCode`: HOBSE hotelId (string)
- `price`: Cheapest room rate
- `roomType`: Room name
- `mealPlan`: Rate plan name
- `bookingCode`: HOBSE hotelId for later API calls

### Step 2: Confirm Quotation Endpoint
**POST** `/api/v1/itineraries/confirm-quotation`

```json
{
  "planId": 1,
  "hotel_bookings": [
    {
      "routeId": 1,
      "provider": "HOBSE",
      "hotelCode": "f9545a8561b3ea2e",
      "bookingCode": "HOBSE",
      "roomType": "Deluxe",
      "numberOfRooms": 1,
      "checkInDate": "2026-02-07",
      "checkOutDate": "2026-02-09",
      "netAmount": 5600,
      "passengers": [
        {
          "paxType": 1,  // 1=adult, 2=child, 3=infant
          "firstName": "John",
          "lastName": "Doe",
          "title": "Mr",
          "email": "john@example.com",
          "phoneNo": "9876543210",
          "leadPassenger": true
        }
      ]
    }
  ],
  "primaryGuest": {
    "salutation": "Mr",
    "name": "John Doe",
    "phone": "9876543210",
    "email": "john@example.com"
  }
}
```

### Step 3: Backend Booking Flow
When booking service processes HOBSE hotels:

1. **Extract Route Info**
   - Find route by routeId
   - Get city name (next_visiting_location)

2. **Map to HOBSE City Code**
   - Query dvi_cities for city name
   - Get hobse_city_code

3. **Count Passengers**
   - Adults (paxType = 1)
   - Children (paxType = 2)
   - Infants (paxType = 3)

4. **Extract Guest Info**
   - Use lead passenger (leadPassenger = true)
   - Fallback to first passenger
   - Fallback to primaryGuest

5. **Generate Unique channelBookingId**
   - Format: `DVI-{planId}-{routeId}-{timestamp}`
   - Prevents "Duplicate channelBookingId" error

6. **Call Provider Booking**
   ```
   hobseProvider.createBookingFromItinerary({
     hotelId,
     cityId,
     checkInDate,
     checkOutDate,
     adultCount,
     childCount,
     infantCount,
     guest: { ... },
     channelBookingId
   })
   ```

7. **HOBSE API Calls (in sequence)**
   
   **a) GetAvailableRoomTariff**
   - Input: sessionId, dates, cityId, room occupancy, hotel filter
   - Output: roomOptions array with pricing
   
   **b) Pick Cheapest Room**
   - Select room with lowest totalCostWithTax
   - Extract: roomCode, occupancyTypeCode, ratePlanCode, tariff, tax
   
   **c) CalculateReservationCost**
   - Input: hotelId, dates, tariffMode, room details, partner info
   - Output: totalTariff, totalTax, totalReservationCost, cancelTerm
   
   **d) CreateBooking**
   - Input: bookingData (hotel + dates + amounts) + roomData + guestData + bookingSourceData
   - Output: bookingId (confirmation reference)

8. **Save to Database**
   - Create row in `hobse_hotel_booking_confirmation` table
   - Store: plan_id, route_id, hotel_code, booking_id, amounts, guest_count, api_response

---

## 🗄️ Database Tables

### hobse_hotel_booking_confirmation
```prisma
model hobse_hotel_booking_confirmation {
  hobse_hotel_booking_confirmation_ID Int    @id @default(autoincrement())
  plan_id                             Int
  route_id                            Int
  hotel_code                          String?
  booking_id                          String?
  check_in_date                       DateTime?
  check_out_date                      DateTime?
  room_count                          Int?
  guest_count                         Int?
  total_amount                        Float?
  currency                            String?
  booking_status                      String?
  api_response                        Json?
  created_at                          DateTime?
  updated_at                          DateTime?
  cancellation_response               Json?
}
```

### dvi_hotel (Updated)
- `hotel_code`: Now stores HOBSE hotelId for HOBSE hotels
- Synced by HobseHotelMasterSyncService

### dvi_cities (Must Have)
- `hobse_city_code`: Mapping from city name to HOBSE city code
- Required for booking flow city resolution

---

## 🧪 Testing

### 1. Test Hotel Search (Mixed Providers)
```bash
GET http://localhost:4006/api/v1/itineraries/hotel_details/DVI2026019
```

Expected: Hotels from TBO + ResAvenue + HOBSE in same 4 groups

### 2. Test HOBSE Master Sync (Optional)
```bash
POST http://localhost:4006/api/v1/hotels/sync/hobse/all
```

Response:
```json
{
  "success": true,
  "message": "HOBSE hotels synced into dvi_hotel successfully",
  "totalFromHobse": 10,
  "inserted": 8,
  "updated": 2,
  "skipped": 0
}
```

### 3. Test HOBSE Booking
```bash
POST http://localhost:4006/api/v1/itineraries/confirm-quotation
```

With HOBSE hotels in payload (see payload example above).

---

## ⚠️ Important Notes

1. **channelBookingId Must Be Unique**
   - HOBSE rejects duplicates with error: "Duplicate channelBookingId"
   - Implementation uses: `DVI-{planId}-{routeId}-{timestamp}`

2. **City Mapping Required**
   - dvi_cities must have hobse_city_code populated for target cities
   - Booking will fail if city not mapped

3. **Room Selection is Automatic**
   - Backend auto-selects cheapest room from GetAvailableRoomTariff
   - No frontend UI change needed for room selection

4. **No Separate HOBSE Tab**
   - HOBSE hotels merge into existing Budget/Mid/Premium/Luxury groups
   - Same response structure as TBO/ResAvenue

5. **Guest Info Priority**
   - Lead passenger data (if marked leadPassenger=true)
   - Then first passenger in array
   - Then primaryGuest data

---

## 🚀 Frontend Changes (Minimal)

### No changes required for basic functionality!

However, if you want frontend to pre-populate room details before sending to backend:

**Option 1 (Current - Recommended):**
- Frontend sends selected hotel with provider="HOBSE"
- Backend auto-selects cheapest room
- Frontend: No extra fields needed

**Option 2 (If needed later):**
- Frontend calls GET `/itineraries/hobse/tariff` to fetch room options
- Frontend displays room picker UI
- Frontend sends selected room code to backend
- Requires additional backend endpoint

---

## 📝 API Response Examples

### Hotel Details Response (Merged)
```json
{
  "quoteId": "DVI2026019",
  "planId": 1,
  "hotelRatesVisible": true,
  "hotelTabs": [
    { "groupType": 1, "label": "Budget", "totalAmount": 5000 },
    { "groupType": 2, "label": "Mid-Range", "totalAmount": 7500 },
    { "groupType": 3, "label": "Premium", "totalAmount": 10000 },
    { "groupType": 4, "label": "Luxury", "totalAmount": 15000 }
  ],
  "hotels": [
    {
      "groupType": 1,
      "itineraryRouteId": 1,
      "day": "1",
      "destination": "Bangalore",
      "hotelId": 123,
      "hotelName": "Demo Hotel 1",
      "category": 3,
      "roomType": "Deluxe",
      "mealPlan": "Continental Plan",
      "totalHotelCost": 5600,
      "totalHotelTaxAmount": 280,
      "provider": "HOBSE",
      "bookingCode": "f9545a8561b3ea2e",
      "searchReference": "hobse"
    }
  ]
}
```

### Booking Response
```json
{
  "provider": "HOBSE",
  "routeId": 1,
  "hotelId": "f9545a8561b3ea2e",
  "channelBookingId": "DVI-1-1-1704067200000",
  "dbId": 42,
  "status": "success"
}
```

---

## ✅ Checklist

- [x] .env updated with HOBSE credentials + config
- [x] hobse-hotel.provider.ts - createBookingFromItinerary() implemented
- [x] hobse-hotel-booking.service.ts - confirmItineraryHotels() implemented
- [x] Hotels module providers registered
- [x] Itinerary module services registered
- [x] Database table exists (hobse_hotel_booking_confirmation)
- [x] No separate HOBSE tab (merged into existing groups)
- [x] Unique channelBookingId generation
- [x] City code mapping required
- [x] Lead passenger extraction
- [x] Pax counting (adult/child/infant)
- [x] Room selection (auto-cheapest)

---

## 🔧 Troubleshooting

### "City not mapped to hobse_city_code"
- Add hobse_city_code to dvi_cities for route destination city

### "Duplicate channelBookingId"
- Ensure new Date().getTime() produces unique values
- Use timestamp, not just planId+routeId

### "No roomOptions returned"
- Verify hotel exists in HOBSE system for that city
- Verify date range is available
- Check API credentials in .env

### "Missing roomCode/occupancyTypeCode/ratePlanCode"
- These are auto-selected from cheapest option
- If getAvailableRoomTariff returns empty roomOptions, hotel unavailable

---

## 📞 Support

For API issues:
- Check HOBSE Postman collection for payload structures
- Verify all partner info fields in bookingSourceData
- Ensure channelBookingId is unique per booking attempt
