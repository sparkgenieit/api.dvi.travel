# Route-Based Hotel Cancellation Implementation

## ✅ Implementation Complete

Successfully implemented route-based cancellation for hotel vouchers. The system now cancels **only selected route(s)** instead of the entire itinerary.

---

## Changes Made

### 1. ✅ Updated Voucher Payload Type
**File:** `src/modules/itineraries/hotel-voucher.service.ts`

**Change:** Added `routeId: number` to `CreateVoucherDto` interface

```typescript
export interface CreateVoucherDto {
  itineraryPlanId: number;
  vouchers: Array<{
    routeId: number;           // ← NEW FIELD
    hotelId: number;
    hotelDetailsIds: number[];
    routeDates: string[];
    confirmedBy: string;
    emailId: string;
    mobileNumber: string;
    status: string;
    invoiceTo: string;
    voucherTermsCondition: string;
  }>;
}
```

**Validation Added:**
- If a voucher has status `'cancelled'` but `routeId` is missing or invalid, throws `BadRequestException`
- Ensures data integrity for route-based cancellations

---

### 2. ✅ Updated createHotelVouchers() Logic
**File:** `src/modules/itineraries/hotel-voucher.service.ts`

**Key Changes:**

- **Collect Route IDs:** Uses `Set<number>` to collect all `routeIds` where `status === 'cancelled'`
- **Route-Scoped Cancellation:** After creating all voucher records, calls provider-specific route-based methods
- **Non-Breaking:** Old `cancelItineraryHotels()` methods remain untouched for backward compatibility

**Flow:**
```typescript
const routeIdsToCancel = new Set<number>();

// Validate and collect route IDs
for (const voucher of dto.vouchers) {
  if (voucher.status === 'cancelled' && !voucher.routeId) {
    throw new BadRequestException('routeId required for cancelled status');
  }
  if (voucher.status === 'cancelled') {
    routeIdsToCancel.add(voucher.routeId);
  }
}

// Cancel only selected routes
if (routeIdsToCancel.size > 0) {
  await this.tboHotelBooking.cancelItineraryHotelsByRoutes(
    dto.itineraryPlanId,
    Array.from(routeIdsToCancel),
    'Hotel cancelled via voucher'
  );
  await this.resavenueHotelBooking.cancelItineraryHotelsByRoutes(...);
  await this.hobseHotelBooking.cancelItineraryHotelsByRoutes(...);
}
```

---

### 3. ✅ TBO Service - Route-Based Cancellation
**File:** `src/modules/itineraries/services/tbo-hotel-booking.service.ts`

**New Method:** `cancelItineraryHotelsByRoutes()`

```typescript
async cancelItineraryHotelsByRoutes(
  itineraryPlanId: number,
  routeIds: number[],
  reason: string = 'Itinerary cancelled by user',
)
```

**Implementation:**
- Queries `tbo_hotel_booking_confirmation` table filtering:
  - `itinerary_plan_ID = itineraryPlanId`
  - `itinerary_route_ID IN [routeIds]`
  - `status = 1` (active)
  - `deleted = 0`
- Calls provider cancel API using numeric `tbo_booking_id`
- Updates DB: sets `status = 0`, appends cancellation response to `api_response`
- No-op if no bookings found for those routes
- Returns results array with `routeId` included for tracking

---

### 4. ✅ ResAvenue Service - Route-Based Cancellation
**File:** `src/modules/itineraries/services/resavenue-hotel-booking.service.ts`

**New Method:** `cancelItineraryHotelsByRoutes()`

```typescript
async cancelItineraryHotelsByRoutes(
  itineraryPlanId: number,
  routeIds: number[],
  reason: string = 'Hotel cancelled by user',
)
```

**Implementation:**
- Queries `resavenue_hotel_booking_confirmation` table filtering by plan ID and route IDs
- Reuses existing provider `cancelBooking()` API
- Updates DB with cancellation status and response
- No-op if none found
- Returns results array with `routeId` included

---

### 5. ✅ HOBSE Service - Route-Based Cancellation
**File:** `src/modules/itineraries/services/hobse-hotel-booking.service.ts`

**New Method:** `cancelItineraryHotelsByRoutes()`

```typescript
async cancelItineraryHotelsByRoutes(
  planId: number,
  routeIds: number[]
): Promise<void>
```

**Implementation:**
- Queries `hobse_hotel_booking_confirmation` table filtering:
  - `plan_id = planId`
  - `route_id IN [routeIds]`
  - `booking_status = 'confirmed'`
- Calls HOBSE API for each booking
- Updates DB: sets `booking_status = 'cancelled'`
- No-op if no confirmed bookings found for routes

---

## Expected Payload Format

UI sends cancellation request with `routeId`:

```json
{
  "itineraryPlanId": 11,
  "vouchers": [
    {
      "routeId": 132,
      "hotelId": 1687511,
      "hotelDetailsIds": [392],
      "routeDates": ["2026-04-29"],
      "confirmedBy": "test",
      "emailId": "x@y.com",
      "mobileNumber": "9999",
      "status": "cancelled",
      "invoiceTo": "gst_bill_against_dvi",
      "voucherTermsCondition": "..."
    },
    {
      "routeId": 133,
      "hotelId": 1687512,
      "hotelDetailsIds": [393],
      "routeDates": ["2026-04-30"],
      "confirmedBy": "test",
      "emailId": "x@y.com",
      "mobileNumber": "9999",
      "status": "cancelled",
      "invoiceTo": "gst_bill_against_dvi",
      "voucherTermsCondition": "..."
    }
  ]
}
```

**Result:** Only routes 132 and 133 are cancelled. Other routes remain active.

---

## Behavior & Guarantees

✅ **Single Route Cancellation**
- If UI cancels Day4 (routeId=132), only that route's bookings are cancelled
- Other routes in the itinerary remain unaffected

✅ **Multiple Route Cancellation**
- If UI sends two vouchers with routeIds=[132, 133], both routes are cancelled
- Other routes (if any) remain active

✅ **Provider Fallback**
- If a route has no booking in a provider table, that provider call is no-op (no error)
- Example: Route 132 may be booked with TBO but not with HOBSE → HOBSE call succeeds silently

✅ **Voucher Records Created**
- Hotel-vouchers DB records are created even for cancelled status
- Allows audit trail and UI display of cancellation

✅ **Backward Compatibility**
- Old `cancelItineraryHotels()` methods remain unchanged
- No impact on confirm-quotation flow
- Existing code paths unaffected

---

## Testing Checklist

### Scenario 1: Cancel Single Route
```
POST /api/v1/itineraries/11/hotel-vouchers
{
  "itineraryPlanId": 11,
  "vouchers": [
    {
      "routeId": 132,
      "status": "cancelled",
      ...
    }
  ]
}
```
**Expected:** Only Route 132 cancelled, Route 133 untouched ✓

### Scenario 2: Cancel Multiple Routes
```
POST /api/v1/itineraries/11/hotel-vouchers
{
  "itineraryPlanId": 11,
  "vouchers": [
    { "routeId": 132, "status": "cancelled", ... },
    { "routeId": 133, "status": "cancelled", ... }
  ]
}
```
**Expected:** Both routes cancelled ✓

### Scenario 3: Missing routeId on Cancelled Status
```
{
  "routeId": null,
  "status": "cancelled",
  ...
}
```
**Expected:** BadRequestException thrown ✓

### Scenario 4: No Bookings for Route in Provider
**Setup:** Route 132 has TBO booking but no HOBSE booking
**Expected:** 
- TBO cancellation succeeds
- HOBSE no-op (silent success)
- No error thrown ✓

---

## TypeScript Compilation

✅ **Status:** All files compile successfully
```bash
npx tsc --noEmit  # Exits with code 0 (success)
```

---

## Files Modified

| File | Change | Status |
|------|--------|--------|
| `src/modules/itineraries/hotel-voucher.service.ts` | Added routeId to DTO, route-based logic, validation | ✅ |
| `src/modules/itineraries/services/tbo-hotel-booking.service.ts` | Added cancelItineraryHotelsByRoutes() | ✅ |
| `src/modules/itineraries/services/resavenue-hotel-booking.service.ts` | Added cancelItineraryHotelsByRoutes() | ✅ |
| `src/modules/itineraries/services/hobse-hotel-booking.service.ts` | Added cancelItineraryHotelsByRoutes() | ✅ |

---

## Next Steps (for UI team)

1. **Update frontend payload** to include `routeId` in voucher objects
2. **Send route-specific cancellation** requests instead of plan-wide
3. **Parse routeId** from route selection/UI state
4. **Test cancellation** with single and multiple routes

---

## Notes

- **Confirm-quotation unchanged:** Booking confirmation flow unaffected
- **Future-proof:** Easy to extend to additional providers or routes
- **Provider no-ops safe:** If a route has no booking, provider calls gracefully succeed
- **Audit trail:** All cancellations logged and stored in DB with cancellation responses
