# Route-Based Cancellation - Code Change Summary

## 1. CreateVoucherDto Update

### Before:
```typescript
export interface CreateVoucherDto {
  itineraryPlanId: number;
  vouchers: Array<{
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

### After:
```typescript
export interface CreateVoucherDto {
  itineraryPlanId: number;
  vouchers: Array<{
    routeId: number;  // ← NEW
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

---

## 2. Voucher Creation Logic - Route Validation & Collection

### New validation:
```typescript
for (const voucher of dto.vouchers) {
  // Validation: if status is 'cancelled' but routeId is missing/invalid, throw error
  if (voucher.status === 'cancelled' && (!voucher.routeId || typeof voucher.routeId !== 'number')) {
    throw new BadRequestException(
      `Voucher with status 'cancelled' must have a valid routeId. Received: ${voucher.routeId}`,
    );
  }
  
  // ... existing voucher creation code ...
  
  // Collect route IDs that need cancellation
  if (voucher.status === 'cancelled') {
    routeIdsToCancel.add(voucher.routeId);  // ← COLLECT ROUTE ID
  }
}
```

---

## 3. Route-Based Cancellation Calls (NEW)

### Before:
```typescript
if (voucher.status === 'cancelled') {
  // Cancels ALL bookings for the entire itinerary
  const tboCancellationResults = await this.tboHotelBooking.cancelItineraryHotels(
    dto.itineraryPlanId,
    'Hotel cancelled via voucher',
  );
  // ... similar for ResAvenue, HOBSE
}
```

### After:
```typescript
if (routeIdsToCancel.size > 0) {
  const routeIdsArray = Array.from(routeIdsToCancel);
  
  // Cancel ONLY selected routes
  const tboCancellationResults = await this.tboHotelBooking.cancelItineraryHotelsByRoutes(
    dto.itineraryPlanId,
    routeIdsArray,  // ← ROUTE-SPECIFIC
    'Hotel cancelled via voucher',
  );
  
  const resavenueCancellationResults = await this.resavenueHotelBooking.cancelItineraryHotelsByRoutes(
    dto.itineraryPlanId,
    routeIdsArray,  // ← ROUTE-SPECIFIC
    'Hotel cancelled via voucher',
  );
  
  await this.hobseHotelBooking.cancelItineraryHotelsByRoutes(
    dto.itineraryPlanId,
    routeIdsArray,  // ← ROUTE-SPECIFIC
  );
}
```

---

## 4. TBO Service - NEW Method

### New method signature:
```typescript
async cancelItineraryHotelsByRoutes(
  itineraryPlanId: number,
  routeIds: number[],
  reason: string = 'Itinerary cancelled by user',
)
```

### Key implementation details:
```typescript
// Find bookings for SPECIFIC routes only
const bookings = await this.prisma.tbo_hotel_booking_confirmation.findMany({
  where: {
    itinerary_plan_ID: itineraryPlanId,
    itinerary_route_ID: { in: routeIds },  // ← ROUTE FILTER
    status: 1,
    deleted: 0,
  },
});

// For each booking, call provider cancel and update DB
for (const booking of bookings) {
  const cancellationResult = await this.tboProvider.cancelBooking(
    booking.tbo_booking_id,
    reason,
  );

  await this.prisma.tbo_hotel_booking_confirmation.update({
    where: { tbo_hotel_booking_confirmation_ID: booking.tbo_hotel_booking_confirmation_ID },
    data: {
      status: 0,
      updatedon: new Date(),
      api_response: {
        ...(booking.api_response as Record<string, any>),
        cancellation: cancellationResult as Record<string, any>,
        cancelledAt: new Date().toISOString(),
        cancelReason: reason,
      },
    },
  });

  // Result includes routeId
  results.push({
    bookingId: booking.tbo_hotel_booking_confirmation_ID,
    routeId: booking.itinerary_route_ID,  // ← ROUTE INFO IN RESPONSE
    tboBookingRef: booking.tbo_booking_reference_number,
    status: 'cancelled',
    // ... rest of result
  });
}
```

---

## 5. ResAvenue Service - NEW Method

### New method signature:
```typescript
async cancelItineraryHotelsByRoutes(
  itineraryPlanId: number,
  routeIds: number[],
  reason: string = 'Hotel cancelled by user',
)
```

### Key implementation:
```typescript
// Find bookings for SPECIFIC routes
const bookings = await this.prisma.resavenue_hotel_booking_confirmation.findMany({
  where: {
    itinerary_plan_ID: itineraryPlanId,
    itinerary_route_ID: { in: routeIds },  // ← ROUTE FILTER
    status: 1,
    deleted: 0,
  },
});

// Cancel each booking and update DB
for (const booking of bookings) {
  const cancellationResult = await this.resavenueProvider.cancelBooking(
    booking.resavenue_booking_reference,
    reason,
  );

  await this.prisma.resavenue_hotel_booking_confirmation.update({
    where: { resavenue_hotel_booking_confirmation_ID: booking.resavenue_hotel_booking_confirmation_ID },
    data: {
      status: 0,
      updatedon: new Date(),
      api_response: {
        ...(booking.api_response as Record<string, any>),
        cancellation: cancellationResult as Record<string, any>,
        cancelledAt: new Date().toISOString(),
        cancelReason: reason,
      },
    },
  });

  results.push({
    bookingId: booking.resavenue_hotel_booking_confirmation_ID,
    routeId: booking.itinerary_route_ID,  // ← ROUTE INFO IN RESPONSE
    resavenueBookingRef: booking.resavenue_booking_reference,
    status: 'cancelled',
    // ... rest of result
  });
}
```

---

## 6. HOBSE Service - NEW Method

### New method signature:
```typescript
async cancelItineraryHotelsByRoutes(
  planId: number,
  routeIds: number[]
): Promise<void>
```

### Key implementation:
```typescript
// Find bookings for SPECIFIC routes
const bookings = await this.prisma.hobse_hotel_booking_confirmation.findMany({
  where: {
    plan_id: planId,
    route_id: { in: routeIds },  // ← ROUTE FILTER
    booking_status: 'confirmed',
  },
});

if (bookings.length === 0) {
  this.logger.log(`No confirmed HOBSE bookings found for routes [${routeIds.join(',')}]`);
  return;  // ← NO-OP IF NO BOOKINGS
}

// Cancel each booking
for (const booking of bookings) {
  const cancellationResult = await this.hobseProvider.cancelBooking(
    booking.booking_id,
    'Hotel cancelled via voucher',
  );

  await this.prisma.hobse_hotel_booking_confirmation.update({
    where: { hobse_hotel_booking_confirmation_ID: booking.hobse_hotel_booking_confirmation_ID },
    data: {
      booking_status: 'cancelled',
      cancellation_response: cancellationResult as Record<string, any>,
      updated_at: new Date(),
    },
  });
}
```

---

## 7. Backward Compatibility

✅ **OLD methods unchanged:**
- `tboHotelBooking.cancelItineraryHotels()` - Still available, cancels ALL routes
- `resavenueHotelBooking.cancelItineraryHotels()` - Still available, cancels ALL routes
- `hobseHotelBooking.cancelItineraryHotels()` - Still available, cancels ALL routes

✅ **NEW methods added in parallel:**
- `tboHotelBooking.cancelItineraryHotelsByRoutes()` - Cancels SELECTED routes
- `resavenueHotelBooking.cancelItineraryHotelsByRoutes()` - Cancels SELECTED routes
- `hobseHotelBooking.cancelItineraryHotelsByRoutes()` - Cancels SELECTED routes

✅ **Confirm-quotation unaffected:**
- No changes to booking confirmation flow
- All existing endpoints and logic remain intact

---

## Example: Single Route Cancellation

### Request:
```json
POST /api/v1/itineraries/11/hotel-vouchers

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
    }
  ]
}
```

### Processing:
1. Validation: routeId=132 is present and valid ✓
2. Voucher created in DB with status='cancelled'
3. routeIds collected: {132}
4. `cancelItineraryHotelsByRoutes(11, [132], 'Hotel cancelled via voucher')` called for TBO
5. Query: Find TBO bookings where plan=11 AND route IN [132] AND status=1
6. For each found booking: cancel via API, update DB
7. Same for ResAvenue and HOBSE
8. Update voucher_cancellation_status

### Result:
- ✅ Only Route 132's hotels are cancelled
- ✅ Other routes in itinerary 11 remain active
- ✅ Audit trail created in DB
- ✅ Cancellation responses stored

---

## Example: Multi-Route Cancellation

### Request:
```json
{
  "itineraryPlanId": 11,
  "vouchers": [
    {
      "routeId": 132,
      "status": "cancelled",
      ...
    },
    {
      "routeId": 133,
      "status": "cancelled",
      ...
    }
  ]
}
```

### Processing:
1. Validations: routeIds 132 and 133 both valid ✓
2. Vouchers created for both routes
3. routeIds collected: {132, 133}
4. Provider methods called: `cancelItineraryHotelsByRoutes(11, [132, 133], ...)`
5. Each provider queries for bookings on routes 132 and 133
6. All found bookings cancelled

### Result:
- ✅ Routes 132 and 133 cancelled
- ✅ Other routes remain active
- ✅ Independent from each other (cancelling 132 doesn't affect 133 bookings)
