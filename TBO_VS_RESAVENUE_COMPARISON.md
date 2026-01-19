# TBO vs ResAvenue Implementation Comparison

## Executive Summary

Both TBO and ResAvenue providers implement the same `IHotelProvider` interface, ensuring consistent behavior across the multi-provider hotel search system. The main differences are in the API formats and authentication mechanisms.

## Architecture Comparison

### Common Interface (`IHotelProvider`)

```typescript
interface IHotelProvider {
  getName(): string;
  search(criteria, preferences?): Promise<HotelSearchResult[]>;
  confirmBooking(bookingDetails): Promise<HotelConfirmationResult>;
  cancelBooking(confirmationRef, reason): Promise<CancellationResult>;
  getConfirmation(confirmationRef): Promise<HotelConfirmationDetails>;
}
```

✅ **Both providers implement all 5 methods identically**

## Feature Comparison Table

| Feature | TBO (OTA) | ResAvenue (PMS) | Notes |
|---------|-----------|-----------------|-------|
| **Authentication** | TokenId (per request) | Username/Password/ID_Context | TBO requires auth before each API call |
| **Base URL** | Multiple (Search, Booking) | Single endpoint | TBO: 2 URLs, ResAvenue: 1 URL |
| **Search Endpoint** | `/Search` | `/PropertyDetails` (Inventory + Rates) | Different endpoints, same result |
| **Hotel Metadata** | From search response | PropertyDetails API | ResAvenue requires separate API call |
| **Room/Rate Codes** | From search results | PropertyDetails → Inventory/Rates | Both dynamic, no hardcoding |
| **Booking Flow** | PreBook → Book (2 steps) | Direct booking (1 step) | TBO requires room lock step |
| **Booking Format** | TBO-specific | Standard OTA (OTA_HotelResNotifRQ) | ResAvenue follows OTA standard |
| **Cancellation** | SendChangeRequest | OTA_HotelResNotifRQ (ResStatus=Cancel) | Same request format as booking |
| **Get Booking** | GetBookingDetail | Booking Pull (date range) | ResAvenue pulls by date, TBO by ID |
| **Multi-Provider** | ✅ Yes | ✅ Yes | Both work together seamlessly |

## Detailed Method Comparison

### 1. Authentication

#### TBO
```typescript
async authenticate() {
  const response = await this.http.post('/Authenticate', {
    ClientId: this.CLIENT_ID,
    UserName: this.USERNAME,
    Password: this.PASSWORD,
    EndUserIp: this.END_USER_IP
  });
  return response.data.TokenId;
}
```
- Returns TokenId valid for 30 minutes
- Must be included in every subsequent request
- Separate endpoint and method

#### ResAvenue
```typescript
// No separate authenticate method
// Credentials included in POS structure for each request
{
  POS: {
    Username: this.USERNAME,
    Password: this.PASSWORD,
    ID_Context: this.ID_CONTEXT
  }
}
```
- No token required
- Credentials sent with every request
- Part of request body

### 2. Search Implementation

#### TBO
```typescript
async search(criteria) {
  const tokenId = await this.authenticate();
  
  const searchRequest = {
    CheckInDate: criteria.checkInDate,
    CheckOutDate: criteria.checkOutDate,
    CityCode: criteria.cityCode,
    RoomGuests: [...],
    TokenId: tokenId
  };
  
  const response = await this.http.post('/Search', searchRequest);
  
  return response.data.HotelResult.map(hotel => ({
    provider: 'tbo',
    hotelCode: hotel.HotelCode,
    hotelName: hotel.HotelName,
    price: hotel.Price,
    roomTypes: hotel.RoomTypes,
    // ... etc
  }));
}
```

**Flow:** Authenticate → Search → Parse Results

#### ResAvenue
```typescript
async search(criteria) {
  // 1. Query database for ResAvenue hotels
  const hotels = await this.prisma.dvi_hotel.findMany({
    where: {
      hotel_city: criteria.cityCode,
      resavenue_hotel_code: { not: null }
    }
  });
  
  // 2. For each hotel, fetch live data
  const results = await Promise.all(
    hotels.map(hotel => this.searchHotel(hotel, criteria))
  );
  
  return results.filter(r => r !== null);
}

async searchHotel(hotel, criteria) {
  // 1. Get room/rate metadata
  const propertyDetails = await this.getPropertyDetails(hotelCode);
  
  // 2. Extract room/rate codes
  const invCodes = [...];
  const rateCodes = [...];
  
  // 3. Fetch inventory and rates in parallel
  const [inventories, rates] = await Promise.all([
    this.getInventory(hotelCode, dates, invCodes),
    this.getRates(hotelCode, dates, rateCodes)
  ]);
  
  // 4. Match availability with pricing
  return this.findAvailableRooms(inventories, rates, maps);
}
```

**Flow:** DB Query → PropertyDetails → Inventory+Rates → Match Results

**Key Difference:** TBO searches all hotels via API, ResAvenue searches only hotels in database

### 3. Booking Confirmation

#### TBO
```typescript
async confirmBooking(bookingDetails) {
  const tokenId = await this.authenticate();
  
  // Step 1: PreBook (lock room)
  const prebookResponse = await this.http.post('/PreBook', {
    CheckInDate: bookingDetails.checkInDate,
    HotelCode: bookingDetails.hotelCode,
    RoomCode: bookingDetails.rooms[0].roomCode,
    TokenId: tokenId
  });
  
  const prebookRefId = prebookResponse.data.PreBookRefId;
  
  // Step 2: Book (confirm reservation)
  const bookResponse = await this.http.post('/Book', {
    PreBookRefId: prebookRefId,
    GuestDetails: [...],
    TokenId: tokenId
  });
  
  return {
    confirmationReference: bookResponse.data.BookingRefId,
    // ...
  };
}
```

**Flow:** Authenticate → PreBook → Book → Return Confirmation

#### ResAvenue
```typescript
async confirmBooking(bookingDetails) {
  const [invCode, rateCode] = bookingDetails.rooms[0].roomCode.split('-');
  
  const bookingRequest = {
    OTA_HotelResNotifRQ: {
      Target: 'Production',
      HotelReservations: {
        HotelReservation: [{
          UniqueID: {
            ID: `DVI-${Date.now()}`,
            OTA: 'DVI'
          },
          ResStatus: 'Confirm',
          RoomStays: {
            RoomStay: [{
              TimeSpan: { Start: ..., End: ... },
              RoomTypes: { RoomTypeCode: invCode },
              RatePlans: { RatePlanCode: rateCode },
              GuestCounts: [...]
            }]
          },
          ResGuests: [...]
        }]
      }
    }
  };
  
  const response = await this.http.post('/PropertyDetails', bookingRequest);
  
  return {
    confirmationReference: bookingRequest.OTA_HotelResNotifRQ.HotelReservations.HotelReservation[0].UniqueID.ID,
    // ...
  };
}
```

**Flow:** Build Request → Send to API → Return Confirmation

**Key Difference:** TBO requires 2-step booking (PreBook + Book), ResAvenue is 1-step

### 4. Cancellation

#### TBO
```typescript
async cancelBooking(confirmationRef, reason) {
  const tokenId = await this.authenticate();
  
  const request = {
    BookingRefId: confirmationRef,
    RequestType: 4, // 4 = Cancellation
    Remarks: reason,
    TokenId: tokenId
  };
  
  const response = await this.http.post('/SendChangeRequest', request);
  
  return {
    cancellationRef: response.data.CancellationId,
    refundAmount: response.data.RefundAmount,
    charges: response.data.CancellationCharges
  };
}
```

#### ResAvenue
```typescript
async cancelBooking(confirmationRef, reason) {
  const cancellationRequest = {
    OTA_HotelResNotifRQ: {
      HotelReservations: {
        HotelReservation: [{
          UniqueID: { ID: confirmationRef },
          ResStatus: 'Cancel',
          ResGlobalInfo: {
            SpecialRequest: reason
          }
        }]
      }
    }
  };
  
  const response = await this.http.post('/PropertyDetails', cancellationRequest);
  
  return {
    cancellationRef: confirmationRef,
    refundAmount: 0,
    charges: 0
  };
}
```

**Key Difference:** Same OTA_HotelResNotifRQ format as booking, just different `ResStatus`

### 5. Get Confirmation Details

#### TBO
```typescript
async getConfirmation(confirmationRef) {
  const tokenId = await this.authenticate();
  
  const request = {
    BookingRefId: confirmationRef,
    TokenId: tokenId
  };
  
  const response = await this.http.post('/Getbookingdetail', request);
  
  return {
    confirmationRef: confirmationRef,
    hotelName: response.data.HotelName,
    checkIn: response.data.CheckInDate,
    totalPrice: response.data.TotalPrice,
    status: response.data.BookingStatus
  };
}
```

#### ResAvenue
```typescript
async getConfirmation(confirmationRef) {
  const pullRequest = {
    OTA_HotelResNotifRQ: {
      PropertyId: confirmationRef,
      FromDate: '2025-12-21', // Last 30 days
      ToDate: '2026-01-20'    // Today
    }
  };
  
  const response = await this.http.post('/PropertyDetails', pullRequest);
  
  const reservations = response.data.OTA_HotelResNotifRQ.HotelReservations;
  const booking = reservations.find(res => 
    res.HotelReservation[0].UniqueID.ID === confirmationRef
  );
  
  return {
    confirmationRef: confirmationRef,
    hotelName: booking.RoomStays.RoomStay[0].BasicPropertyInfo.HotelName,
    // ...
  };
}
```

**Key Difference:** TBO fetches by ID directly, ResAvenue pulls by date range and filters

## Data Flow Comparison

### TBO Search Flow
```
User Request
  ↓
HotelSearchService
  ↓
TBOHotelProvider.search()
  ↓
authenticate() → Get TokenId
  ↓
POST /Search → Get all hotels in city
  ↓
Parse results → Return HotelSearchResult[]
```

### ResAvenue Search Flow
```
User Request
  ↓
HotelSearchService
  ↓
ResAvenueHotelProvider.search()
  ↓
Query dvi_hotel table → Get hotels with resavenue_hotel_code
  ↓
For each hotel:
  ├─ getPropertyDetails() → Get room/rate metadata
  ├─ Extract active rooms/rates
  ├─ getInventory() → Get availability
  ├─ getRates() → Get pricing
  └─ findAvailableRooms() → Match & format
  ↓
Return HotelSearchResult[]
```

## Performance Comparison

### TBO
- **Latency:** Single API call for all hotels (~2-5 seconds)
- **Hotels Returned:** All hotels in city (can be 100+)
- **Room Options:** All available rooms returned at once
- **Caching:** TokenId cached for 30 minutes

### ResAvenue
- **Latency:** Multiple API calls per hotel (~1-2 seconds per hotel)
- **Hotels Returned:** Only hotels in database (controlled)
- **Room Options:** Only active rooms/rates
- **Caching:** No caching (PropertyDetails rarely changes)

**Optimization:** ResAvenue could cache PropertyDetails for 24 hours since room/rate metadata rarely changes

## Database Requirements

### TBO
- **Hotel Table:** Not required (searches via API)
- **TBO-specific Field:** None (uses HotelCode from API)
- **Data Sync:** Not needed

### ResAvenue
- **Hotel Table:** Required (dvi_hotel)
- **ResAvenue Field:** `resavenue_hotel_code` VARCHAR(200)
- **Data Sync:** Must insert hotels with ResAvenue codes
- **Index:** `@@index([resavenue_hotel_code])`

## Error Handling Comparison

### TBO
```typescript
try {
  const tokenId = await this.authenticate();
  // ... API calls
} catch (error) {
  this.logger.error(`TBO error: ${error.message}`);
  throw new InternalServerErrorException('TBO search failed');
}
```
- Catches authentication errors
- Throws standardized exceptions
- Logs with provider name

### ResAvenue
```typescript
try {
  const propertyDetails = await this.getPropertyDetails(hotelCode);
  if (!propertyDetails) {
    return null; // Skip hotel silently
  }
  // ... API calls
} catch (error) {
  this.logger.error(`ResAvenue error: ${error.message}`);
  return null; // Don't fail entire search
}
```
- Graceful degradation (skip failed hotels)
- Continues with other hotels
- Logs warnings vs errors

**Key Difference:** TBO fails entire search on error, ResAvenue skips individual hotels

## Testing Status

### TBO
- ✅ Search tested and working
- ✅ Authentication tested and working
- ✅ Booking confirmation tested and working
- ✅ Cancellation tested and working
- ✅ Get confirmation tested and working

### ResAvenue
- ✅ PropertyDetails API tested and working
- ✅ Inventory API tested and working
- ✅ Rate API tested and working
- ✅ Search integration tested and working
- ⚠️ Booking confirmation implemented (untested - sandbox limitations)
- ⚠️ Cancellation implemented (untested - sandbox limitations)
- ⚠️ Get confirmation implemented (untested - sandbox limitations)

## Production Readiness

### TBO Provider
- **Status:** ✅ Production Ready
- **Tested:** Full booking lifecycle
- **Dependencies:** TBO API credentials
- **Limitations:** None known

### ResAvenue Provider
- **Status:** ✅ Search Ready, 🔄 Booking Pending Test
- **Tested:** Search flow fully working
- **Dependencies:** ResAvenue PMS credentials, Database with hotel codes
- **Limitations:** Sandbox may not support booking creation

## Recommendations

### 1. Multi-Provider Strategy
✅ **Keep Both Providers Active**
- TBO for wide hotel coverage (OTA aggregator)
- ResAvenue for direct property management (PMS integration)
- Combined results offer best availability

### 2. Search Optimization
- Cache TBO TokenId for 30 minutes
- Cache ResAvenue PropertyDetails for 24 hours
- Run TBO and ResAvenue searches in parallel (already implemented)

### 3. Booking Priority
- Try TBO first (proven working)
- Fall back to ResAvenue if hotel only available there
- Allow user to choose provider if both available

### 4. Error Handling
- Continue search even if one provider fails
- Log provider-specific errors separately
- Show user which providers returned results

### 5. Testing Next Steps
1. Test ResAvenue booking in production environment
2. Verify sandbox vs production API differences
3. Implement retry logic for transient failures
4. Add circuit breaker for provider failures

## Conclusion

Both TBO and ResAvenue providers successfully implement the same interface, making them interchangeable from the service layer perspective. The main architectural difference is:

- **TBO:** API-first (searches via API, no database required)
- **ResAvenue:** Database-first (searches only configured hotels, fetches live data)

This hybrid approach provides maximum flexibility and coverage for the hotel search system.
