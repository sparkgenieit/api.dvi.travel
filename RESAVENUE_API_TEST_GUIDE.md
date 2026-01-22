# ResAvenue Booking & Cancellation API Test Guide

## Overview

This guide provides complete testing for ResAvenue hotel booking and cancellation APIs through NestJS endpoints.

---

## Files Created

### 1. Test Script
**File:** `test-resavenue-booking-api.ts`
- Comprehensive test suite for ResAvenue APIs
- Tests search, booking, and cancellation flows
- Can be run standalone without backend modifications

### 2. Test Controller
**File:** `src/modules/itineraries/resavenue-test.controller.ts`
- Dedicated REST endpoints for testing ResAvenue provider
- Direct access to provider methods
- Health check endpoint

---

## Quick Start

### Run Test Script

```bash
cd d:\wamp64\www\dvi_fullstack\dvi_backend

# Run the test suite
npx tsx test-resavenue-booking-api.ts
```

### Configuration (.env)

```env
# Backend API (if testing through endpoints)
API_BASE_URL=http://localhost:4006
TEST_AUTH_TOKEN=your-jwt-token-here

# Test itinerary for booking/cancellation tests
TEST_ITINERARY_PLAN_ID=1

# ResAvenue credentials (already in .env)
RESAVENUE_BASE_URL=http://203.109.97.241:8080/ChannelController
RESAVENUE_USERNAME=testpmsk4@resavenue.com
RESAVENUE_PASSWORD=testpms@123
RESAVENUE_ID_CONTEXT=REV
```

---

## Test Endpoints

All endpoints are prefixed with `/api/v1/test/resavenue`

### 1. Health Check
**Endpoint:** `POST /api/v1/test/resavenue/health-check`

Verifies ResAvenue provider configuration.

**Request:**
```json
{}
```

**Response:**
```json
{
  "success": true,
  "message": "ResAvenue provider is configured",
  "config": {
    "baseUrl": "http://203.109.97.241:8080/ChannelController",
    "username": "✅ Set",
    "password": "✅ Set",
    "idContext": "REV"
  },
  "timestamp": "2026-01-20T00:00:00.000Z"
}
```

**cURL:**
```bash
curl -X POST http://localhost:4006/api/v1/test/resavenue/health-check \
  -H "Content-Type: application/json" \
  -d "{}"
```

---

### 2. Test Booking Confirmation
**Endpoint:** `POST /api/v1/test/resavenue/test-booking`

Directly tests ResAvenue provider's confirmBooking method.

**Request:**
```json
{
  "hotelCode": "RSAV_HOTEL_001",
  "checkInDate": "2026-02-20",
  "checkOutDate": "2026-02-22",
  "invCode": 1234,
  "rateCode": 5678,
  "numberOfRooms": 1,
  "guests": [
    {
      "firstName": "John",
      "lastName": "Doe",
      "email": "john.doe@test.com",
      "phone": "+919876543210"
    },
    {
      "firstName": "Jane",
      "lastName": "Doe",
      "email": "jane.doe@test.com",
      "phone": "+919876543211"
    }
  ]
}
```

**Response:**
```json
{
  "success": true,
  "message": "ResAvenue booking test completed",
  "data": {
    "confirmationReference": "DVI-1737331200000",
    "hotelCode": "RSAV_HOTEL_001",
    "checkIn": "2026-02-20",
    "checkOut": "2026-02-22",
    "totalPrice": 5000,
    "status": "Confirmed",
    "bookingDeadline": "2026-02-20",
    "cancellationPolicy": "As per hotel policy"
  },
  "timestamp": "2026-01-20T00:00:00.000Z"
}
```

**cURL:**
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
      {
        "firstName": "John",
        "lastName": "Doe",
        "email": "john.doe@test.com",
        "phone": "+919876543210"
      }
    ]
  }'
```

---

### 3. Test Booking Cancellation
**Endpoint:** `POST /api/v1/test/resavenue/test-cancellation`

Directly tests ResAvenue provider's cancelBooking method.

**Request:**
```json
{
  "bookingReference": "DVI-1737331200000",
  "reason": "Testing cancellation API"
}
```

**Response:**
```json
{
  "success": true,
  "message": "ResAvenue cancellation test completed",
  "data": {
    "cancellationRef": "DVI-1737331200000",
    "refundAmount": 0,
    "charges": 0,
    "refundDays": 5
  },
  "timestamp": "2026-01-20T00:00:00.000Z"
}
```

**cURL:**
```bash
curl -X POST http://localhost:4006/api/v1/test/resavenue/test-cancellation \
  -H "Content-Type: application/json" \
  -d '{
    "bookingReference": "DVI-1737331200000",
    "reason": "Testing cancellation API"
  }'
```

---

### 4. Test Get Confirmation Details
**Endpoint:** `POST /api/v1/test/resavenue/test-confirmation-details`

Retrieves booking details from ResAvenue.

**Request:**
```json
{
  "bookingReference": "DVI-1737331200000"
}
```

**Response:**
```json
{
  "success": true,
  "message": "ResAvenue confirmation details test completed",
  "data": {
    "confirmationRef": "DVI-1737331200000",
    "hotelName": "Test Hotel",
    "checkIn": "2026-02-20",
    "checkOut": "2026-02-22",
    "roomCount": 1,
    "totalPrice": 5000,
    "status": "Confirmed",
    "cancellationPolicy": "As per hotel policy"
  },
  "timestamp": "2026-01-20T00:00:00.000Z"
}
```

**cURL:**
```bash
curl -X POST http://localhost:4006/api/v1/test/resavenue/test-confirmation-details \
  -H "Content-Type: application/json" \
  -d '{
    "bookingReference": "DVI-1737331200000"
  }'
```

---

### 5. Test Booking Service
**Endpoint:** `POST /api/v1/test/resavenue/test-booking-service`

Tests ResAvenueHotelBookingService confirmBooking method.

**Request:**
```json
{
  "hotelCode": "RSAV_HOTEL_001",
  "checkInDate": "2026-02-20",
  "checkOutDate": "2026-02-22",
  "invCode": 1234,
  "rateCode": 5678,
  "numberOfRooms": 1,
  "guests": [
    {
      "firstName": "John",
      "lastName": "Doe",
      "email": "john.doe@test.com",
      "phone": "+919876543210"
    }
  ]
}
```

**Response:**
```json
{
  "success": true,
  "message": "ResAvenue booking service test completed",
  "data": {
    "confirmationReference": "DVI-1737331200000",
    "hotelCode": "RSAV_HOTEL_001",
    "status": "Confirmed"
  },
  "timestamp": "2026-01-20T00:00:00.000Z"
}
```

---

## Production Endpoints

### Confirm Itinerary with ResAvenue Hotel
**Endpoint:** `POST /api/v1/itineraries/confirm-quotation`

Books hotels through itinerary confirmation (production flow).

**Request:**
```json
{
  "itineraryPlanId": 1,
  "customerName": "Test Customer",
  "customerEmail": "test@example.com",
  "customerPhone": "+919876543210",
  "hotels": [
    {
      "routeId": 1,
      "hotelCode": "RSAV_HOTEL_001",
      "provider": "resavenue",
      "bookingCode": "1234-5678",
      "roomType": "Deluxe Room",
      "checkInDate": "2026-02-20",
      "checkOutDate": "2026-02-22",
      "numberOfRooms": 1,
      "guestNationality": "IN",
      "netAmount": 5000,
      "guests": [
        {
          "firstName": "John",
          "lastName": "Doe",
          "email": "john.doe@test.com",
          "phone": "+919876543210"
        }
      ]
    }
  ]
}
```

**Response:**
```json
{
  "confirmedPlanId": 123,
  "hotels": [
    {
      "routeId": 1,
      "hotelCode": "RSAV_HOTEL_001",
      "bookingRef": "DVI-1737331200000",
      "status": "confirmed"
    }
  ]
}
```

---

### Cancel Itinerary with ResAvenue Hotels
**Endpoint:** `POST /api/v1/itineraries/cancel`

Cancels all hotels in an itinerary (production flow).

**Request:**
```json
{
  "itineraryPlanId": 1,
  "reason": "Customer requested cancellation"
}
```

**Response:**
```json
{
  "success": true,
  "hotelCancellations": [
    {
      "bookingId": 1,
      "resavenueBookingRef": "DVI-1737331200000",
      "status": "cancelled",
      "cancellationRef": "DVI-1737331200000",
      "refundAmount": 4500,
      "charges": 500
    }
  ]
}
```

---

## ResAvenue API Details

### Base Configuration
- **Base URL:** `http://203.109.97.241:8080/ChannelController`
- **Endpoint:** `/PropertyDetails`
- **Method:** POST
- **Content-Type:** application/json

### Authentication
- **Username:** testpmsk4@resavenue.com
- **Password:** testpms@123
- **ID Context:** REV

### Request Format - Booking Confirmation
```xml
<OTA_HotelResNotifRQ>
  <Target>Production</Target>
  <Version>1.0</Version>
  <EchoToken>booking-{timestamp}</EchoToken>
  <TimeStamp>{ISO 8601 datetime}</TimeStamp>
  <HotelReservations>
    <HotelReservation>
      <UniqueID>
        <ID>{unique booking ref}</ID>
        <OTA>DVI</OTA>
        <BookingSource>DVI Journey Manager</BookingSource>
      </UniqueID>
      <ResStatus>Confirm</ResStatus>
      <RoomStays>
        <RoomStay>
          <TimeSpan>
            <Start>{YYYY-MM-DD}</Start>
            <End>{YYYY-MM-DD}</End>
          </TimeSpan>
          <BasicPropertyInfo>
            <HotelCode>{hotel code}</HotelCode>
          </BasicPropertyInfo>
          <RoomTypes>
            <RoomType>
              <NumberOfUnits>{room count}</NumberOfUnits>
              <RoomTypeCode>{InvCode}</RoomTypeCode>
            </RoomType>
          </RoomTypes>
          <RatePlans>
            <RatePlan>
              <RatePlanCode>{RateCode}</RatePlanCode>
            </RatePlan>
          </RatePlans>
        </RoomStay>
      </RoomStays>
      <ResGuests>
        <ResGuest>
          <Profiles>
            <ProfileInfo>
              <Profile>
                <Customer>
                  <PersonName>
                    <GivenName>{first name}</GivenName>
                    <Surname>{last name}</Surname>
                  </PersonName>
                  <Email>{email}</Email>
                  <Telephone>{phone}</Telephone>
                </Customer>
              </Profile>
            </ProfileInfo>
          </Profiles>
        </ResGuest>
      </ResGuests>
    </HotelReservation>
  </HotelReservations>
</OTA_HotelResNotifRQ>
```

### Request Format - Cancellation
```xml
<OTA_HotelResNotifRQ>
  <Target>Production</Target>
  <Version>1.0</Version>
  <EchoToken>cancel-{timestamp}</EchoToken>
  <TimeStamp>{ISO 8601 datetime}</TimeStamp>
  <HotelReservations>
    <HotelReservation>
      <UniqueID>
        <ID>{booking reference to cancel}</ID>
        <OTA>DVI</OTA>
      </UniqueID>
      <ResStatus>Cancel</ResStatus>
      <ResGlobalInfo>
        <SpecialRequest>{cancellation reason}</SpecialRequest>
      </ResGlobalInfo>
    </HotelReservation>
  </HotelReservations>
</OTA_HotelResNotifRQ>
```

### Response Format
```json
{
  "OTA_HotelResNotifRS": {
    "Status": "Success" | "Failure",
    "Remark": "Success message or error details",
    "HotelReservations": [
      {
        "HotelReservation": [{
          "UniqueID": { "ID": "DVI-1737331200000" },
          "ResStatus": "Confirmed" | "Cancel",
          "RoomStays": { ... },
          "ResGlobalInfo": {
            "Total": {
              "TotalBookingAmount": 5000,
              "CurrencyCode": "INR"
            }
          }
        }]
      }
    ]
  }
}
```

---

## Room Code Format

ResAvenue uses a combined code format: `{InvCode}-{RateCode}`

**Example:** `1234-5678`
- **InvCode:** 1234 (Room Type/Inventory Code)
- **RateCode:** 5678 (Rate Plan Code)

This is split when calling the API:
```typescript
const [invCode, rateCode] = roomCode.split('-');
```

---

## Database Tables

### resavenue_hotel_booking_confirmation
Stores ResAvenue booking confirmations.

**Key Columns:**
- `resavenue_hotel_booking_confirmation_ID` - Primary key
- `itinerary_plan_ID` - Link to itinerary
- `itinerary_route_ID` - Link to route
- `resavenue_hotel_code` - Hotel code
- `resavenue_booking_reference` - Booking ref from API
- `booking_code` - InvCode-RateCode
- `check_in_date` - Check-in date
- `check_out_date` - Check-out date
- `number_of_rooms` - Room count
- `net_amount` - Total amount
- `api_response` - Full API response JSON
- `status` - 1 = active, 0 = cancelled
- `deleted` - 0 = active, 1 = deleted

---

## Testing Workflow

### Complete Test Flow

```bash
# 1. Start backend
cd d:\wamp64\www\dvi_fullstack\dvi_backend
npm run start:dev

# 2. Health check
curl -X POST http://localhost:4006/api/v1/test/resavenue/health-check \
  -H "Content-Type: application/json" \
  -d "{}"

# 3. Test booking
curl -X POST http://localhost:4006/api/v1/test/resavenue/test-booking \
  -H "Content-Type: application/json" \
  -d '{
    "hotelCode": "RSAV_HOTEL_001",
    "checkInDate": "2026-02-20",
    "checkOutDate": "2026-02-22",
    "invCode": 1234,
    "rateCode": 5678,
    "numberOfRooms": 1,
    "guests": [{"firstName": "John", "lastName": "Doe"}]
  }'

# 4. Test cancellation (use booking reference from step 3)
curl -X POST http://localhost:4006/api/v1/test/resavenue/test-cancellation \
  -H "Content-Type: application/json" \
  -d '{
    "bookingReference": "DVI-1737331200000",
    "reason": "Test cancellation"
  }'

# 5. Run automated test suite
npx tsx test-resavenue-booking-api.ts
```

---

## Troubleshooting

### Issue: "No ResAvenue hotels found"
**Solution:** Check `dvi_hotel` table has entries with `resavenue_hotel_code` populated.

```sql
SELECT id, hotel_name, resavenue_hotel_code 
FROM dvi_hotel 
WHERE resavenue_hotel_code IS NOT NULL 
AND deleted = 0 
LIMIT 10;
```

### Issue: "Booking failed: Bad Request"
**Solutions:**
1. Verify ResAvenue credentials in .env
2. Check hotel code exists in ResAvenue system
3. Validate InvCode and RateCode are correct
4. Check date format (YYYY-MM-DD)

### Issue: "401 Unauthorized"
**Solutions:**
1. Verify RESAVENUE_USERNAME and RESAVENUE_PASSWORD
2. Check if credentials have expired
3. Contact ResAvenue support for access

### Issue: "Cancellation failed: Booking not found"
**Solutions:**
1. Verify booking reference is correct
2. Check if booking was already cancelled
3. Ensure booking exists in ResAvenue system

---

## Summary

✅ **Test Script Created:** `test-resavenue-booking-api.ts`  
✅ **Test Controller Created:** `resavenue-test.controller.ts`  
✅ **Test Endpoints:** 5 endpoints for comprehensive testing  
✅ **Production Endpoints:** Integrated in itinerary flow  
✅ **Documentation:** Complete API reference and examples  

**Next Steps:**
1. Run test script: `npx tsx test-resavenue-booking-api.ts`
2. Test endpoints with cURL or Postman
3. Verify bookings in database
4. Test cancellation flow
5. Integrate into production workflows
