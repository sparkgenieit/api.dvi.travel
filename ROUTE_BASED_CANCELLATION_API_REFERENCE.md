# Hotel Voucher Cancellation API - Quick Reference

## Endpoint

```
POST /api/v1/itineraries/:id/hotel-vouchers
```

---

## Request Body Format (NEW)

```json
{
  "itineraryPlanId": 11,
  "vouchers": [
    {
      "routeId": 132,
      "hotelId": 1687511,
      "hotelDetailsIds": [392],
      "routeDates": ["2026-04-29"],
      "confirmedBy": "John Doe",
      "emailId": "john@example.com",
      "mobileNumber": "9999999999",
      "status": "cancelled",
      "invoiceTo": "gst_bill_against_dvi",
      "voucherTermsCondition": "Terms and conditions..."
    }
  ]
}
```

---

## Field Descriptions

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `itineraryPlanId` | number | Yes | Itinerary plan ID |
| `routeId` | number | Yes (if status='cancelled') | **NEW** - Route ID to cancel |
| `hotelId` | number | Yes | Hotel ID |
| `hotelDetailsIds` | number[] | Yes | Hotel detail IDs (one per route date) |
| `routeDates` | string[] | Yes | Dates in YYYY-MM-DD format |
| `confirmedBy` | string | Yes | Name of person confirming |
| `emailId` | string | Yes | Email for confirmation |
| `mobileNumber` | string | Yes | Mobile number |
| `status` | string | Yes | Either 'confirmed' or 'cancelled' |
| `invoiceTo` | string | Yes | One of: 'gst_bill_against_dvi', 'hotel_direct', 'agent' |
| `voucherTermsCondition` | string | Yes | Terms and conditions text |

---

## Validation Rules (NEW)

### ❌ Missing routeId for Cancelled Status
```json
{
  "status": "cancelled",
  "routeId": null
}
```
**Error:** `BadRequestException`
```
Voucher with status 'cancelled' must have a valid routeId. Received: null
```

### ✅ Valid Cancelled Voucher
```json
{
  "status": "cancelled",
  "routeId": 132
}
```
**Result:** Route 132 is cancelled only. Other routes unaffected.

### ✅ Confirmed Status (routeId optional)
```json
{
  "status": "confirmed",
  "routeId": 132
}
```
**Result:** Voucher created, NO cancellation triggered.

---

## Response Format

### Success (200 OK)
```json
{
  "success": true,
  "message": "Successfully created 1 hotel voucher(s)"
}
```

### Error - Missing routeId (400 Bad Request)
```json
{
  "statusCode": 400,
  "message": "Voucher with status 'cancelled' must have a valid routeId. Received: null",
  "error": "Bad Request"
}
```

---

## Cancellation Behavior

### Single Route Cancellation
**Request:**
```json
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

**System Flow:**
1. Creates voucher record (for audit trail)
2. Collects routeId: {132}
3. Calls `tboHotelBooking.cancelItineraryHotelsByRoutes(11, [132], ...)`
4. Calls `resavenueHotelBooking.cancelItineraryHotelsByRoutes(11, [132], ...)`
5. Calls `hobseHotelBooking.cancelItineraryHotelsByRoutes(11, [132], ...)`
6. Updates voucher cancellation status in DB

**Result:**
- ✅ Only Route 132 bookings cancelled
- ✅ Route 133, 134, etc. (if any) remain ACTIVE
- ✅ Refund/charge details stored in DB

---

### Multiple Route Cancellation
**Request:**
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
    },
    {
      "routeId": 134,
      "status": "confirmed",  // ← NOT cancelled
      ...
    }
  ]
}
```

**Result:**
- ✅ Routes 132 and 133 cancelled
- ✅ Route 134 remains ACTIVE
- ✅ All vouchers created for audit trail

---

### Provider No-Op Behavior
**Scenario:** Route 132 has TBO booking but NO HOBSE booking

**System Flow:**
1. `tboHotelBooking.cancelItineraryHotelsByRoutes(11, [132], ...)` → Finds booking → Cancels ✓
2. `resavenueHotelBooking.cancelItineraryHotelsByRoutes(11, [132], ...)` → No booking found → No-op ✓
3. `hobseHotelBooking.cancelItineraryHotelsByRoutes(11, [132], ...)` → No booking found → No-op ✓

**Result:** No error. System succeeds gracefully.

---

## Database Operations

### Hotel Voucher Record (Created)
```sql
INSERT INTO dvi_confirmed_itinerary_plan_hotel_voucher_details
(itinerary_plan_id, hotel_id, itinerary_plan_hotel_details_ID, 
 itinerary_route_date, hotel_booking_status, ...)
VALUES (11, 1687511, 392, '2026-04-29', 2, ...)
-- status 2 = 'cancelled'
```

### TBO Booking (Updated if found)
```sql
UPDATE tbo_hotel_booking_confirmation
SET status = 0, updatedon = NOW(), 
    api_response = JSON_MERGE_PATCH(api_response, '{"cancellation": {...}}')
WHERE itinerary_plan_ID = 11 
  AND itinerary_route_ID = 132
  AND status = 1
  AND deleted = 0
```

### ResAvenue Booking (Updated if found)
```sql
UPDATE resavenue_hotel_booking_confirmation
SET status = 0, updatedon = NOW(),
    api_response = JSON_MERGE_PATCH(api_response, '{"cancellation": {...}}')
WHERE itinerary_plan_ID = 11
  AND itinerary_route_ID = 132
  AND status = 1
  AND deleted = 0
```

### HOBSE Booking (Updated if found)
```sql
UPDATE hobse_hotel_booking_confirmation
SET booking_status = 'cancelled', updated_at = NOW(),
    cancellation_response = {...}
WHERE plan_id = 11
  AND route_id = 132
  AND booking_status = 'confirmed'
```

---

## Logging Output

### Example: Single Route Cancellation
```
Creating 1 hotel vouchers for plan 11
Processing voucher 0: routeId=132, routeDate=2026-04-29, hotelDetailsId=392
🚫 Cancelling selected route(s): 132 for itinerary 11
✅ TBO route cancellation completed: [{"routeId": 132, "status": "cancelled", ...}]
✅ ResAvenue route cancellation completed: [{"routeId": 132, "status": "cancelled", ...}]
✅ HOBSE route cancellation completed
```

### Example: Multiple Routes
```
Creating 2 hotel vouchers for plan 11
Processing voucher 0: routeId=132, ...
Processing voucher 1: routeId=133, ...
🚫 Cancelling selected route(s): 132,133 for itinerary 11
✅ TBO route cancellation completed: [{"routeId": 132, ...}, {"routeId": 133, ...}]
...
```

---

## Backward Compatibility

### Old Endpoint (Unchanged)
```
POST /api/v1/itineraries/:id/cancel
```
Still cancels **entire itinerary** using old methods:
- `cancelItineraryHotels()` - Not affected
- All existing code using this remains intact

### New Endpoint Flow (This Implementation)
```
POST /api/v1/itineraries/:id/hotel-vouchers
```
Now cancels **selected routes only** using:
- `cancelItineraryHotelsByRoutes()` - NEW methods
- Old methods still available if needed

---

## Error Handling

| Error | Cause | Handling |
|-------|-------|----------|
| 400 Bad Request | Missing routeId on cancelled status | Exception thrown, request rejected |
| 404 Not Found | Itinerary plan not found | Standard NestJS error |
| 500 Provider Error | API call to TBO/ResAvenue/HOBSE fails | Caught, logged, request continues |
| 400 No Bookings | Route has no bookings in provider | Silent no-op, no error |

---

## Frontend Implementation Checklist

- [ ] Parse route ID from current route selection
- [ ] Include routeId in voucher payload for cancellations
- [ ] Validate routeId exists before sending status='cancelled'
- [ ] Handle 400 Bad Request for missing routeId
- [ ] Display cancellation confirmation with route number
- [ ] Show refund/charge amounts from response (if available)
- [ ] Audit trail: Display voucher creation timestamp

---

## Example Frontend Code

```typescript
// Get selected route from UI
const selectedRoute = this.getSelectedRoute(); // { id: 132, name: 'Day 4' }

// Prepare cancellation payload
const cancelVoucher = {
  itineraryPlanId: this.itineraryId,
  vouchers: [
    {
      routeId: selectedRoute.id,  // ← IMPORTANT
      hotelId: hotel.id,
      hotelDetailsIds: [hotel.detailId],
      routeDates: [hotel.date],
      confirmedBy: this.userName,
      emailId: this.userEmail,
      mobileNumber: this.userPhone,
      status: 'cancelled',  // ← TRIGGER CANCELLATION
      invoiceTo: 'gst_bill_against_dvi',
      voucherTermsCondition: hotel.terms,
    }
  ]
};

// Send to API
this.http.post(`/api/v1/itineraries/${this.itineraryId}/hotel-vouchers`, cancelVoucher)
  .subscribe(
    response => {
      console.log('Cancellation successful:', response);
      // Show success message with route number
      alert(`Route ${selectedRoute.id} cancelled successfully!`);
    },
    error => {
      console.error('Cancellation failed:', error);
      // Handle error - show routeId validation error if present
      if (error.status === 400 && error.error.message.includes('routeId')) {
        alert('Route ID is required for cancellation');
      }
    }
  );
```
