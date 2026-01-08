# TBO API Integration - Complete Endpoint Summary

## Overview
This document lists all API endpoints that integrate with TBO (Tektravels) API.

---

## 1. **ITINERARIES MODULE** - Hotel Details Endpoints

### A. `/api/v1/itineraries/hotel_details/:quoteId` (Production)
- **Method:** `GET`
- **Controller:** [itineraries.controller.ts](src/modules/itineraries/itineraries.controller.ts#L277)
- **Service:** `ItineraryHotelDetailsService.getHotelDetailsByQuoteId()`
- **Data Source:** **DATABASE** (Saved itinerary data)
- **Purpose:** Production frontend - retrieves hotel details from `dvi_itinerary_plan_hotel_details` table
- **Returns:** `ItineraryHotelDetailsResponseDto`
- **Status:** ✅ **DATABASE-BASED** (NOT using TBO API)

### B. `/api/v1/itineraries/hotel_api_details/:quoteId` (Staging)
- **Method:** `GET`
- **Controller:** [itineraries.controller.ts](src/modules/itineraries/itineraries.controller.ts#L324)
- **Service:** `ItineraryHotelDetailsTboService.getHotelDetailsByQuoteIdFromTbo()`
- **Data Source:** **TBO API** (Real-time hotel search)
- **Purpose:** Staging frontend - fetches dynamic hotel packages from TBO in real-time
- **Returns:** 4 price tier packages (Budget, Mid-Range, Premium, Luxury)
- **Returns:** `ItineraryHotelDetailsResponseDto`
- **TBO Integration:** ✅ **YES - USES TBO API**

### C. `/api/v1/itineraries/hotel_room_details/:quoteId`
- **Method:** `GET`
- **Controller:** [itineraries.controller.ts](src/modules/itineraries/itineraries.controller.ts#L371)
- **Service:** `ItineraryHotelDetailsService.getHotelRoomDetailsByQuoteId()`
- **Data Source:** **DATABASE**
- **Purpose:** Returns detailed room-level information from saved itinerary
- **Returns:** `ItineraryHotelRoomDetailsResponseDto`
- **Status:** ✅ **DATABASE-BASED** (NOT using TBO API)

---

## 2. **HOTELS MODULE** - Hotel Search & Management Endpoints

### A. `/api/v1/hotels/search` (Hotel Search - Uses TBO Provider)
- **Method:** `POST`
- **Controller:** [hotel-search.controller.ts](src/modules/hotels/controllers/hotel-search.controller.ts#L28)
- **Service:** `HotelSearchService.searchHotels()`
- **Provider:** `TBOHotelProvider` (Can support multiple providers: TBO, HOBSE, Revenue)
- **Data Source:** **TBO API** (Real-time search)
- **Purpose:** Generic hotel search endpoint - searches across configured providers
- **Request Body:**
  ```json
  {
    "cityCode": "string",
    "checkInDate": "date",
    "checkOutDate": "date",
    "roomCount": number,
    "guestCount": number,
    "providers": ["tbo"] (default),
    "preferences": { optional }
  }
  ```
- **Returns:** Array of `HotelSearchResult[]`
- **TBO Integration:** ✅ **YES - USES TBO API**

### B. `/api/v1/hotels/confirm` (Hotel Booking Confirmation)
- **Method:** `POST`
- **Controller:** [hotel-confirm.controller.ts](src/modules/hotels/controllers/hotel-confirm.controller.ts#L25)
- **Service:** `HotelConfirmService.confirmHotelBooking()`
- **Data Source:** **TBO API** (Booking confirmation via TBO)
- **Purpose:** Confirm hotel booking through TBO
- **TBO Integration:** ✅ **YES - USES TBO API**

### C. `/api/v1/hotels/payment/initiate` (Initiate Payment)
- **Method:** `POST`
- **Controller:** [hotel-confirm.controller.ts](src/modules/hotels/controllers/hotel-confirm.controller.ts#L41)
- **Service:** `HotelConfirmService.initiatePayment()`
- **Purpose:** Initiate payment for confirmed hotel booking
- **TBO Integration:** ✅ **YES - USES TBO API**

### D. `/api/v1/hotels/payment/finalize/:ref` (Finalize Payment)
- **Method:** `POST`
- **Controller:** [hotel-confirm.controller.ts](src/modules/hotels/controllers/hotel-confirm.controller.ts#L53)
- **Service:** `HotelConfirmService.finalizePayment()`
- **Purpose:** Finalize payment with Razorpay reference
- **TBO Integration:** ✅ **YES - USES TBO API**

### E. `/api/v1/hotels/confirmation/:ref` (Get Confirmation)
- **Method:** `GET`
- **Controller:** [hotel-confirm.controller.ts](src/modules/hotels/controllers/hotel-confirm.controller.ts#L71)
- **Service:** `HotelConfirmService.getConfirmation()`
- **Purpose:** Retrieve booking confirmation details from TBO
- **TBO Integration:** ✅ **YES - USES TBO API**

### F. `/api/v1/hotels/cancel/:ref` (Cancel Booking)
- **Method:** `POST`
- **Controller:** [hotel-confirm.controller.ts](src/modules/hotels/controllers/hotel-confirm.controller.ts#L84)
- **Service:** `HotelConfirmService.cancelBooking()`
- **Purpose:** Cancel hotel booking through TBO
- **TBO Integration:** ✅ **YES - USES TBO API**

### G. `/api/v1/hotels/sync/all` (Sync Master Data - TBO)
- **Method:** `POST`
- **Controller:** [hotel-master-sync.controller.ts](src/modules/hotels/controllers/hotel-master-sync.controller.ts#L18)
- **Service:** `TboSoapSyncService.syncAllCities()`
- **Purpose:** Sync all cities and hotels from TBO SOAP API (v7)
- **TBO Integration:** ✅ **YES - USES TBO SOAP API**

### H. `/api/v1/hotels/sync/city/:cityCode` (Sync Hotels by City - TBO)
- **Method:** `POST`
- **Controller:** [hotel-master-sync.controller.ts](src/modules/hotels/controllers/hotel-master-sync.controller.ts#L45)
- **Service:** `TboSoapSyncService.syncHotelsForCity()`
- **Purpose:** Sync hotels for a specific city from TBO SOAP API
- **TBO Integration:** ✅ **YES - USES TBO SOAP API**

### I. `/api/v1/hotels/sync/master-data/count` (Get Master Data Count)
- **Method:** `GET`
- **Controller:** [hotel-master-sync.controller.ts](src/modules/hotels/controllers/hotel-master-sync.controller.ts#L71)
- **Service:** `TboSoapSyncService.getHotelCount()`
- **Purpose:** Get count of hotels in master database
- **TBO Integration:** ✅ **YES - USES TBO SOAP API**

---

## 3. **TBO SUPPORT SERVICES** (Internal Services - Not direct endpoints)

### A. `TBOHotelProvider` Service
- **File:** [tbo-hotel.provider.ts](src/modules/hotels/providers/tbo-hotel.provider.ts)
- **Purpose:** Core TBO API integration provider
- **Methods:**
  - `search()` - Search hotels from TBO
  - `confirm()` - Confirm booking
  - `cancel()` - Cancel booking
  - `authenticate()` - Get TBO authentication token
- **TBO Endpoints Used:**
  - Authentication: `https://sharedapi.tektravels.com/SharedData.svc/rest/Authenticate`
  - Hotel Search: `https://affiliate.tektravels.com/HotelAPI/Search`
  - Booking: Various TBO booking endpoints

### B. `HotelSearchService` Service
- **File:** [hotel-search.service.ts](src/modules/hotels/services/hotel-search.service.ts)
- **Purpose:** Unified hotel search across multiple providers (TBO, HOBSE, Revenue)
- **Uses:** `TBOHotelProvider`

### C. `HotelConfirmService` Service
- **File:** [hotel-confirm.service.ts](src/modules/hotels/services/hotel-confirm.service.ts)
- **Purpose:** Hotel booking confirmation and payment handling
- **Uses:** `TBOHotelProvider` + Razorpay payment gateway

### D. `TboSoapSyncService` Service
- **File:** [tbo-soap-sync.service.ts](src/modules/hotels/services/tbo-soap-sync.service.ts)
- **Purpose:** Sync TBO master data (cities and hotels)
- **TBO Endpoints:** SOAP v7 APIs

### E. `ItineraryHotelDetailsTboService` Service
- **File:** [itinerary-hotel-details-tbo.service.ts](src/modules/itineraries/itinerary-hotel-details-tbo.service.ts)
- **Purpose:** Generate dynamic hotel packages from TBO for staging
- **Uses:** `HotelSearchService` which uses `TBOHotelProvider`

### F. `TboHotelBookingService` Service
- **File:** [tbo-hotel-booking.service.ts](src/modules/itineraries/services/tbo-hotel-booking.service.ts)
- **Purpose:** Handle TBO hotel bookings and confirmations
- **Uses:** TBO API endpoints

---

## Summary Table

| Endpoint | Method | Service | Data Source | TBO? | Purpose |
|----------|--------|---------|-------------|------|---------|
| `/itineraries/hotel_details/:id` | GET | ItineraryHotelDetailsService | Database | ❌ | Production hotel details |
| `/itineraries/hotel_api_details/:id` | GET | ItineraryHotelDetailsTboService | TBO API | ✅ | Staging dynamic packages |
| `/itineraries/hotel_room_details/:id` | GET | ItineraryHotelDetailsService | Database | ❌ | Room-level details |
| `/hotels/search` | POST | HotelSearchService | TBO API | ✅ | Generic hotel search |
| `/hotels/confirm` | POST | HotelConfirmService | TBO API | ✅ | Confirm booking |
| `/hotels/payment/initiate` | POST | HotelConfirmService | TBO API | ✅ | Initiate payment |
| `/hotels/payment/finalize/:ref` | POST | HotelConfirmService | TBO API | ✅ | Finalize payment |
| `/hotels/confirmation/:ref` | GET | HotelConfirmService | TBO API | ✅ | Get confirmation |
| `/hotels/cancel/:ref` | POST | HotelConfirmService | TBO API | ✅ | Cancel booking |
| `/hotels/sync/all` | POST | TboSoapSyncService | TBO SOAP | ✅ | Sync all master data |
| `/hotels/sync/city/:code` | POST | TboSoapSyncService | TBO SOAP | ✅ | Sync city hotels |
| `/hotels/sync/master-data/count` | GET | TboSoapSyncService | TBO SOAP | ✅ | Get hotel count |

---

## TBO API Credentials

**Location:** Environment variables
- `TBO_USERNAME` (default: 'Doview')
- `TBO_PASSWORD` (default: 'Doview@12345')
- `TBO_CLIENT_ID` (default: 'ApiIntegrationNew')
- `TBO_END_USER_IP` (default: '192.168.1.1')
- `TBO_USE_MOCK` (optional: enable mock responses)

**Production Endpoints:**
- REST API: `https://affiliate.tektravels.com/HotelAPI`
- Shared API: `https://sharedapi.tektravels.com`
- Hotel BE: `https://hotelbe.tektravels.com`

---

## Key Findings

✅ **Correctly Using TBO API:**
1. `/hotels/search` - Generic hotel search
2. `/hotels/confirm` - Hotel booking
3. `/hotels/payment/*` - Payment handling
4. `/hotels/cancel/:ref` - Cancellation
5. `/hotels/sync/*` - Master data sync
6. `/itineraries/hotel_api_details/:id` - Dynamic packages (staging)

✅ **Correctly Using DATABASE (NOT TBO):**
1. `/itineraries/hotel_details/:id` - Production endpoint (uses saved data)
2. `/itineraries/hotel_room_details/:id` - Room details (uses saved data)

---

Last Updated: January 5, 2026
