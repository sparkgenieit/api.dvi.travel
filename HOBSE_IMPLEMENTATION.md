# HOBSE Hotel Provider Implementation

## Overview
HOBSE is now integrated as a third hotel provider alongside TBO and ResAvenue. The implementation follows the same multi-provider architecture.

## Configuration

### Environment Variables
Add these to your `.env` file:

```env
# HOBSE API Configuration
HOBSE_BASE_URL=https://api.hobse.com
HOBSE_CLIENT_TOKEN=your_client_token_here
HOBSE_ACCESS_TOKEN=your_access_token_here
HOBSE_PRODUCT_TOKEN=your_product_token_here
```

### Database Setup

The implementation includes the `hobse_hotel_booking_confirmation` table. Run Prisma DB push to create it:

```bash
npx prisma db push
```

### City Mapping

Hotels are matched by city name. Ensure the `dvi_cities` table has `hobse_city_code` populated:

```sql
UPDATE dvi_cities SET hobse_city_code = 'Chennai' WHERE name = 'Chennai';
UPDATE dvi_cities SET hobse_city_code = 'Bangalore' WHERE name = 'Bangalore';
-- Add more city mappings as needed
```

## API Endpoints Implemented

### Core Data APIs
- ✅ `/htl/GetHotelList` - Get authorized hotels
- ✅ `/htl/GetHotelInfo` - Get detailed hotel information
- ✅ `/htl/GetCityDetail` - Get city and locality details
- ✅ `/htl/GetHotelRoomDetail` - Get room, occupancy, rate plan details

### Core Process APIs
- ✅ `/htl/GetAvailableRoomTariff` - Search room availability and pricing
- ✅ `/htl/CalculateReservationCost` - Calculate booking cost
- ✅ `/htl/CreateBooking` - Confirm hotel booking
- ✅ `/htl/GetBooking` - Get booking details
- ✅ `/htl/SetBookingStatus` - Cancel booking

## Architecture

### Files Created

1. **Provider**: `src/modules/hotels/providers/hobse-hotel.provider.ts`
   - Implements `IHotelProvider` interface
   - Handles all HOBSE API calls
   - Transforms HOBSE responses to standard format

2. **Booking Service**: `src/modules/itineraries/services/hobse-hotel-booking.service.ts`
   - Confirms hotel bookings for itineraries
   - Cancels bookings via HOBSE API
   - Saves booking confirmations to database

3. **Database Model**: `prisma/schema.prisma`
   - `hobse_hotel_booking_confirmation` table
   - Stores booking confirmations, cancellations, and API responses

### Module Registration

**HotelsModule** (`src/modules/hotels/hotels.module.ts`):
```typescript
providers: [
  TBOHotelProvider,
  ResAvenueHotelProvider,
  HobseHotelProvider, // ✅ Added
  // ...
]
```

**ItineraryModule** (`src/modules/itineraries/itinerary.module.ts`):
```typescript
providers: [
  TboHotelBookingService,
  ResAvenueHotelBookingService,
  HobseHotelBookingService, // ✅ Added
  // ...
]
```

**HotelSearchService** (`src/modules/hotels/services/hotel-search.service.ts`):
```typescript
providers = ['tbo', 'resavenue', 'hobse'] // ✅ HOBSE included by default
```

### Integration Points

#### 1. **Hotel Search**
When users search for hotels, HOBSE is queried in parallel with TBO and ResAvenue:

```typescript
// HotelSearchService automatically searches all providers
const results = await hotelSearchService.searchHotels({
  cityCode: 'CHE',
  checkInDate: '2026-02-01',
  checkOutDate: '2026-02-03',
  roomCount: 1,
  guestCount: 2,
  providers: ['tbo', 'resavenue', 'hobse'] // All three providers
});
```

Search results include `provider: 'HOBSE'` field to identify the source.

#### 2. **Hotel Booking**
When confirming an itinerary with hotels:

```typescript
// ItinerariesService.processConfirmationWithTboBookings
const hobseHotels = dto.hotel_bookings.filter(h => h.provider === 'HOBSE');

if (hobseHotels.length > 0) {
  const results = await hobseHotelBooking.confirmItineraryHotels(
    planId,
    hobseHotels,
    contactDetails
  );
}
```

Booking flow:
1. Filter hotels by provider field
2. Route HOBSE hotels to `HobseHotelBookingService`
3. Call HOBSE API: `CalculateReservationCost` → `CreateBooking`
4. Save confirmation to `hobse_hotel_booking_confirmation` table

#### 3. **Hotel Cancellation**
When cancelling an itinerary:

```typescript
// ItinerariesService.cancelHotels
try {
  await hobseHotelBooking.cancelItineraryHotels(itineraryPlanId);
  console.log('[HOBSE Cancellation] Successfully processed');
} catch (error) {
  console.error('[HOBSE Cancellation] Failed:', error.message);
  // Continue with DB updates even if API call fails
}
```

Cancellation flow:
1. Find all confirmed HOBSE bookings for the plan
2. Call HOBSE API: `SetBookingStatus` with status='cancelled'
3. Update `booking_status` to 'cancelled' in database
4. Store cancellation response in `cancellation_response` JSON field

## Database Schema

### hobse_hotel_booking_confirmation

```prisma
model hobse_hotel_booking_confirmation {
  hobse_hotel_booking_confirmation_ID Int       @id @default(autoincrement())
  plan_id                             Int       // Itinerary plan ID
  route_id                            Int       // Route ID
  hotel_code                          String    // HOBSE hotel ID
  booking_id                          String    // HOBSE booking reference
  check_in_date                       DateTime?
  check_out_date                      DateTime?
  room_count                          Int
  guest_count                         Int
  total_amount                        Float
  currency                            String    // Default: INR
  booking_status                      String    // pending/confirmed/cancelled
  api_response                        Json?     // Full booking response
  cancellation_response               Json?     // Cancellation details
  created_at                          DateTime
  updated_at                          DateTime?

  // Indexes for fast lookups
  @@index([plan_id])
  @@index([route_id])
  @@index([hotel_code])
  @@index([booking_id])
  @@index([booking_status])
}
```

## Request/Response Format

### HOBSE API Structure

All HOBSE APIs follow this wrapper format:

**Request:**
```json
{
  "hobse": {
    "version": "1.0",
    "datetime": "2026-01-20T16:26:21+05:30",
    "clientToken": "C123456",
    "accessToken": "A123456",
    "productToken": "P123456",
    "request": {
      "method": "htl/GetHotelList",
      "data": {
        "resultType": "json"
      }
    }
  }
}
```

**Response:**
```json
{
  "hobse": {
    "version": "1.0",
    "datetime": "2026-01-20T16:30:21+05:30",
    "response": {
      "status": {
        "success": "true",
        "code": "200",
        "message": "Result returned successfully"
      },
      "totalRecords": 2,
      "data": [ /* results */ ]
    }
  }
}
```

## Frontend Integration

The frontend already supports multi-provider hotels. No changes needed:

1. **Provider Badge**: Hotels show "HOBSE" badge on search results
2. **Provider Field**: Stored when user selects hotel
3. **Booking Payload**: Sent as part of `hotel_bookings` array with `provider: 'HOBSE'`

## Testing

### Test HOBSE Search
```bash
# In dvi_backend directory
npx ts-node test-hobse-search.ts
```

### Test HOBSE Booking
```bash
npx ts-node test-hobse-booking.ts
```

### Test Multi-Provider
```bash
npx ts-node test-multi-provider-booking.ts
```

## Troubleshooting

### Issue: No HOBSE Hotels Returned

**Solution:**
1. Check city mapping in `dvi_cities.hobse_city_code`
2. Verify HOBSE API credentials in `.env`
3. Check HOBSE API logs in console

### Issue: Booking Fails

**Solution:**
1. Verify required fields: `hotelCode`, `roomCode`, `occupancyCode`, `ratePlanCode`
2. Check HOBSE API response in `api_response` JSON field
3. Review error logs in backend console

### Issue: Cancellation Fails

**Solution:**
1. Verify booking exists with status='confirmed'
2. Check HOBSE API allows cancellation for this booking
3. Review `cancellation_response` JSON field for error details

## API Reference

### HobseHotelProvider Methods

```typescript
class HobseHotelProvider implements IHotelProvider {
  // Search hotels for a city
  async search(criteria: HotelSearchCriteria): Promise<HotelSearchResult[]>
  
  // Book a hotel
  async confirmBooking(details: HotelConfirmationDTO): Promise<HotelConfirmationResult>
  
  // Cancel a booking
  async cancelBooking(confirmationRef: string, reason: string): Promise<CancellationResult>
  
  // Get booking details
  async getConfirmation(confirmationRef: string): Promise<HotelConfirmationDetails>
}
```

### HobseHotelBookingService Methods

```typescript
class HobseHotelBookingService {
  // Confirm multiple hotels for an itinerary
  async confirmItineraryHotels(
    planId: number,
    hotels: HotelSelectionDto[],
    contactDetails: { name, email, phone }
  ): Promise<BookingResult[]>
  
  // Cancel all HOBSE hotels for an itinerary
  async cancelItineraryHotels(planId: number): Promise<void>
}
```

## Summary

✅ **Provider**: HobseHotelProvider implements all HOBSE APIs
✅ **Booking**: HobseHotelBookingService handles bookings and cancellations
✅ **Database**: hobse_hotel_booking_confirmation table stores confirmations
✅ **Search**: HOBSE included by default in multi-provider search
✅ **Routing**: Backend routes by provider field to correct service
✅ **Cancellation**: HOBSE API called when itinerary is cancelled
✅ **Frontend**: No changes needed - already supports multi-provider

The system now supports **three hotel providers**: TBO, ResAvenue, and HOBSE!
