# TBO Hotel Cancellation Fix - Complete Analysis

## Problem Summary
When trying to cancel TBO hotel bookings via the voucher endpoint, the API returns a **400 Bad Request** error from TBO's `SendChangeRequest` endpoint.

**Error from logs:**
```
[TBOHotelProvider] ❌ Cancel Booking Error: Request failed with status code 400
```

## Root Cause Analysis

The issue is in how the booking ID is being passed to the TBO cancellation API.

### Database Schema Issue
The `tbo_hotel_booking_confirmation` table has TWO different ID fields:

```prisma
model tbo_hotel_booking_confirmation {
  tbo_hotel_booking_confirmation_ID Int       @id  // Local database ID
  tbo_booking_id                    String?   // ✅ The numeric BookingId from TBO Book API
  tbo_booking_reference_number      String?   // Reference number for display/confirmation
  ...
}
```

### The Bug
**OLD CODE (WRONG):**
```typescript
// src/modules/itineraries/services/tbo-hotel-booking.service.ts
const cancellationResult = await this.tboProvider.cancelBooking(
  booking.tbo_booking_reference_number,  // ❌ WRONG - This is a reference, not the ID
  reason
);
```

The cancellation was using `tbo_booking_reference_number` instead of `tbo_booking_id`.

**Expected by TBO API:**
The `SendChangeRequest` endpoint expects the numeric `BookingId` field that was returned by the original `Book` API response, NOT the reference number.

## Solution

### Change 1: Update TBOHotelProvider.cancelBooking() method

**File:** `src/modules/hotels/providers/tbo-hotel.provider.ts`

**Before:**
```typescript
async cancelBooking(
  confirmationRef: string,
  reason: string,
): Promise<CancellationResult> {
  // ...
  const request = {
    BookingMode: 5,
    RequestType: 4,
    Remarks: reason,
    BookingId: parseInt(confirmationRef), // ❌ Wrong - parsing reference as ID
    EndUserIp: process.env.TBO_END_USER_IP || '192.168.1.1',
    TokenId: tokenId,
  };
}
```

**After:**
```typescript
async cancelBooking(
  bookingId: string,              // ✅ New parameter
  confirmationRef: string,        // For logging
  reason: string,
): Promise<CancellationResult> {
  // ...
  const request = {
    BookingMode: 5,
    RequestType: 4,
    Remarks: reason,
    BookingId: parseInt(bookingId), // ✅ Correct - using actual booking ID
    EndUserIp: process.env.TBO_END_USER_IP || '192.168.1.1',
    TokenId: tokenId,
  };
}
```

### Change 2: Update the cancellation call

**File:** `src/modules/itineraries/services/tbo-hotel-booking.service.ts`

**Before:**
```typescript
const cancellationResult = await this.tboProvider.cancelBooking(
  booking.tbo_booking_reference_number,  // ❌ Wrong field
  reason,
);
```

**After:**
```typescript
const cancellationResult = await this.tboProvider.cancelBooking(
  booking.tbo_booking_id,                // ✅ Correct field
  booking.tbo_booking_reference_number,  // For logging
  reason,
);
```

## Why This Works

1. **TBO Book API** returns:
   ```json
   {
     "BookingId": 669667240173025,
     "BookingRefNo": "ABC123-REFERENCE",
     ...
   }
   ```

2. **TBO SendChangeRequest** expects:
   ```json
   {
     "BookingId": 669667240173025,  // ✅ Numeric ID from Book response
     "RequestType": 4,
     "Remarks": "Cancellation reason",
     ...
   }
   ```

3. We store both values in the database:
   - `tbo_booking_id` ← stores the BookingId (used for future API calls)
   - `tbo_booking_reference_number` ← stores the reference (for display)

## Testing the Fix

### 1. Verify Code Changes
- ✅ `TBOHotelProvider.cancelBooking()` now accepts 3 parameters: `(bookingId, confirmationRef, reason)`
- ✅ Cancellation calls pass `booking.tbo_booking_id` as first parameter
- ✅ SendChangeRequest payload uses the correct numeric ID

### 2. Manual Test
Try cancelling a hotel voucher again:
```bash
POST /api/v1/itineraries/11/hotel-vouchers

{
  "itineraryPlanId": 11,
  "vouchers": [{
    "hotelId": 1219121,
    "hotelDetailsIds": [385],
    "routeDates": ["2026-04-27"],
    "status": "cancelled",
    ...
  }]
}
```

### 3. Expected Behavior
- ✅ No more 400 errors
- ✅ TBO API accepts the cancellation request
- ✅ Database records updated with cancellation status
- ✅ Proper error logging if TBO returns actual API errors

## Files Modified

1. [src/modules/hotels/providers/tbo-hotel.provider.ts](src/modules/hotels/providers/tbo-hotel.provider.ts#L512)
   - Updated `cancelBooking()` method signature
   - Fixed BookingId parameter parsing

2. [src/modules/itineraries/services/tbo-hotel-booking.service.ts](src/modules/itineraries/services/tbo-hotel-booking.service.ts#L525)
   - Updated cancellation call to pass `tbo_booking_id`
   - Added comment about the importance of correct field

## Additional Notes

- The `tbo_booking_id` field is populated during the booking process in `bookHotel()` method
- This is a critical fix that affects all hotel cancellations via TBO provider
- The same fix pattern should be applied if other cancellation endpoints have similar issues

## Summary

**The Fix:** Use the correct `tbo_booking_id` (numeric BookingId from TBO) instead of `tbo_booking_reference_number` when calling TBO's `SendChangeRequest` API for cancellations.

**Impact:** All TBO hotel booking cancellations will now work correctly without 400 errors.
