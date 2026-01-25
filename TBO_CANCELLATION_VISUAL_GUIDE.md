# TBO Cancellation Fix - Visual Explanation

## Database Schema

```
tbo_hotel_booking_confirmation
┌─────────────────────────────────────────┐
│ ID: 31 (Local Database ID)              │
├─────────────────────────────────────────┤
│ tbo_booking_id: "669667240173025"   ✅  │  ← Use THIS for cancellation
│ tbo_booking_reference_number:           │
│   "ABC-REFERENCE-12345"            ❌  │  ← Don't use this
├─────────────────────────────────────────┤
│ itinerary_plan_ID: 11                   │
│ status: 1 (active)                      │
│ ...                                      │
└─────────────────────────────────────────┘
```

## Data Flow - OLD (BROKEN)

```
┌─────────────────────────────────────────────────────────────────┐
│ 1. Hotel Booking Created                                        │
│                                                                 │
│   TBO Book API Response:                                       │
│   {                                                             │
│     "BookingId": 669667240173025,                              │
│     "BookingRefNo": "ABC-REFERENCE-12345"                      │
│   }                                                             │
│                                                                 │
│   Stored in Database:                                          │
│   tbo_booking_id = "669667240173025"    ✅                      │
│   tbo_booking_reference_number = "ABC-REFERENCE-12345"  ✅     │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│ 2. Cancellation Initiated                                       │
│                                                                 │
│   User calls: POST /api/v1/itineraries/11/hotel-vouchers       │
│   with status: "cancelled"                                      │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│ 3. OLD CODE (BROKEN)                                            │
│                                                                 │
│   const cancellationResult =                                    │
│     await this.tboProvider.cancelBooking(                       │
│       booking.tbo_booking_reference_number,  ❌ WRONG!          │
│       reason                                                    │
│     );                                                          │
│                                                                 │
│   Result:                                                       │
│   "ABC-REFERENCE-12345"  → parseInt() → NaN or invalid number  │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│ 4. SendChangeRequest Sent (INVALID)                             │
│                                                                 │
│   POST /hotelservice.svc/rest/SendChangeRequest                │
│   {                                                             │
│     "BookingId": NaN,  ❌ INVALID                               │
│     "RequestType": 4,                                           │
│     "Remarks": "Hotel cancelled via voucher",                   │
│     ...                                                         │
│   }                                                             │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│ 5. TBO API Rejects                                              │
│                                                                 │
│   Response: HTTP 400 Bad Request  ❌                             │
│                                                                 │
│   Error: Invalid BookingId parameter                            │
└─────────────────────────────────────────────────────────────────┘
```

## Data Flow - NEW (FIXED)

```
┌─────────────────────────────────────────────────────────────────┐
│ 1. Hotel Booking Created                                        │
│                                                                 │
│   TBO Book API Response:                                       │
│   {                                                             │
│     "BookingId": 669667240173025,                              │
│     "BookingRefNo": "ABC-REFERENCE-12345"                      │
│   }                                                             │
│                                                                 │
│   Stored in Database:                                          │
│   tbo_booking_id = "669667240173025"    ✅                      │
│   tbo_booking_reference_number = "ABC-REFERENCE-12345"  ✅     │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│ 2. Cancellation Initiated                                       │
│                                                                 │
│   User calls: POST /api/v1/itineraries/11/hotel-vouchers       │
│   with status: "cancelled"                                      │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│ 3. NEW CODE (FIXED)                                             │
│                                                                 │
│   const cancellationResult =                                    │
│     await this.tboProvider.cancelBooking(                       │
│       booking.tbo_booking_id,  ✅ CORRECT!                      │
│       booking.tbo_booking_reference_number,                     │
│       reason                                                    │
│     );                                                          │
│                                                                 │
│   Result:                                                       │
│   "669667240173025" → parseInt() → 669667240173025 (valid)     │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│ 4. SendChangeRequest Sent (VALID)                               │
│                                                                 │
│   POST /hotelservice.svc/rest/SendChangeRequest                │
│   {                                                             │
│     "BookingId": 669667240173025,  ✅ VALID                     │
│     "RequestType": 4,                                           │
│     "Remarks": "Hotel cancelled via voucher",                   │
│     ...                                                         │
│   }                                                             │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│ 5. TBO API Accepts                                              │
│                                                                 │
│   Response: HTTP 200 OK  ✅                                      │
│   {                                                             │
│     "HotelChangeRequestResult": {                               │
│       "ResponseStatus": 1,                                      │
│       "ChangeRequestId": "CHR-12345"                            │
│     }                                                           │
│   }                                                             │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│ 6. Database Updated                                             │
│                                                                 │
│   UPDATE tbo_hotel_booking_confirmation                         │
│   SET status = 0,  ✅ CANCELLED                                  │
│       api_response = {...cancellation details...}               │
│   WHERE id = 31;                                                │
└─────────────────────────────────────────────────────────────────┘
```

## Code Comparison

### OLD CODE (BROKEN)

```typescript
// File: src/modules/itineraries/services/tbo-hotel-booking.service.ts
const cancellationResult = await this.tboProvider.cancelBooking(
  booking.tbo_booking_reference_number,  // ❌ WRONG FIELD
  reason
);

// File: src/modules/hotels/providers/tbo-hotel.provider.ts
async cancelBooking(
  confirmationRef: string,  // Actually the reference, not the ID!
  reason: string,
) {
  const request = {
    BookingId: parseInt(confirmationRef),  // ❌ Parsing reference as ID
    ...
  };
}
```

**Result:** `parseInt("ABC-REFERENCE-12345")` → `NaN` → 400 error

### NEW CODE (FIXED)

```typescript
// File: src/modules/itineraries/services/tbo-hotel-booking.service.ts
const cancellationResult = await this.tboProvider.cancelBooking(
  booking.tbo_booking_id,  // ✅ CORRECT FIELD
  booking.tbo_booking_reference_number,  // For logging
  reason
);

// File: src/modules/hotels/providers/tbo-hotel.provider.ts
async cancelBooking(
  bookingId: string,  // ✅ Actually the numeric ID
  confirmationRef: string,  // For logging/reference
  reason: string,
) {
  const request = {
    BookingId: parseInt(bookingId),  // ✅ Parsing the actual ID
    ...
  };
}
```

**Result:** `parseInt("669667240173025")` → `669667240173025` → Success ✅

## Key Differences

| Aspect | OLD ❌ | NEW ✅ |
|--------|--------|--------|
| **First Parameter** | `tbo_booking_reference_number` | `tbo_booking_id` |
| **Parameter Type** | Single (reference) | Three (id, reference, reason) |
| **BookingId** | `parseInt(reference)` | `parseInt(bookingId)` |
| **Result** | NaN → 400 error | Valid number → Success |
| **TBO API** | Rejects request | Accepts request |

## Why This Happened

The developer confused:
- **Reference**: Human-readable identifier (e.g., "ABC-REF-12345")
- **ID**: Numeric identifier from API response (e.g., "669667240173025")

TBO API contract:
```
Book API Response:
  - Returns BookingId (numeric)
  - Returns BookingRefNo (reference)

SendChangeRequest API:
  - Expects BookingId (numeric)
  - NOT BookingRefNo
```

The code was passing BookingRefNo where BookingId was expected.

## The Fix Summary

```diff
- const cancellationResult = await this.tboProvider.cancelBooking(
-   booking.tbo_booking_reference_number,
-   reason,
- );

+ const cancellationResult = await this.tboProvider.cancelBooking(
+   booking.tbo_booking_id,
+   booking.tbo_booking_reference_number,
+   reason,
+ );

- async cancelBooking(
-   confirmationRef: string,
-   reason: string,
- ) {
+ async cancelBooking(
+   bookingId: string,
+   confirmationRef: string,
+   reason: string,
+ ) {

-   const request = {
-     BookingId: parseInt(confirmationRef),
+   const request = {
+     BookingId: parseInt(bookingId),
```

**That's it!** The fix is just using the correct field from the database.
