# TBO Cancellation Fix - Quick Reference

## Problem
```
❌ Request failed with status code 400
   Endpoint: /hotelservice.svc/rest/SendChangeRequest
   Method: POST
   Payload: BookingId: 669667240173025
```

## Root Cause
❌ Using `tbo_booking_reference_number` instead of `tbo_booking_id` when calling SendChangeRequest

## Solution Applied

### File 1: `src/modules/hotels/providers/tbo-hotel.provider.ts`

```diff
  async cancelBooking(
+   bookingId: string,
    confirmationRef: string,
    reason: string,
  ) {
    // ...
    const request = {
      BookingMode: 5,
      RequestType: 4,
      Remarks: reason,
-     BookingId: parseInt(confirmationRef),
+     BookingId: parseInt(bookingId),
      EndUserIp: process.env.TBO_END_USER_IP || '192.168.1.1',
      TokenId: tokenId,
    };
  }
```

### File 2: `src/modules/itineraries/services/tbo-hotel-booking.service.ts`

```diff
  const cancellationResult = await this.tboProvider.cancelBooking(
+   booking.tbo_booking_id,
    booking.tbo_booking_reference_number,
    reason,
  );
```

## Database Fields

```
tbo_hotel_booking_confirmation table:
├── tbo_booking_id (String)               ✅ USE THIS for cancellation
├── tbo_booking_reference_number (String) → Reference only
└── tbo_hotel_booking_confirmation_ID (Int) → Local DB ID
```

## TBO API Contract

### Book Response (when booking)
```json
{
  "BookResult": {
    "BookingId": 669667240173025,        // ✅ Store as tbo_booking_id
    "BookingRefNo": "ABC-123-REF",       // Store as tbo_booking_reference_number
    ...
  }
}
```

### SendChangeRequest (when cancelling)
```json
{
  "BookingId": 669667240173025,  // ✅ Must use the numeric ID
  "RequestType": 4,
  "Remarks": "Hotel cancelled via voucher",
  ...
}
```

## Verification Checklist

- [x] Updated `cancelBooking()` method signature in TBOHotelProvider
- [x] Updated `cancelBooking()` call in TboHotelBookingService
- [x] Using `tbo_booking_id` instead of `tbo_booking_reference_number`
- [x] SendChangeRequest uses correct BookingId parameter
- [x] Database schema has both fields populated during booking

## Test Command

```bash
curl -X POST http://localhost:4006/api/v1/itineraries/11/hotel-vouchers \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "itineraryPlanId": 11,
    "vouchers": [{
      "hotelId": 1219121,
      "hotelDetailsIds": [385],
      "routeDates": ["2026-04-27"],
      "status": "cancelled"
    }]
  }'
```

## Expected Success Output

```
✅ Booking cancelled successfully - ChangeRequestId: xyz123
✅ TBO cancellation completed: [{"status":"success",...}]
```

## Files Modified

1. [src/modules/hotels/providers/tbo-hotel.provider.ts](src/modules/hotels/providers/tbo-hotel.provider.ts#L512)
2. [src/modules/itineraries/services/tbo-hotel-booking.service.ts](src/modules/itineraries/services/tbo-hotel-booking.service.ts#L525)

---

**Status:** ✅ FIXED - Ready for testing
