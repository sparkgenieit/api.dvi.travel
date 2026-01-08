# Bruno Collection - Complete Backend Flow

## How to Use This Collection

This collection mirrors your backend API exactly. Follow the requests in order:

### Request Flow

```
00. Call Backend Endpoint
    ↓
    (Backend queries database, builds TBO payloads, returns hotel packages)
    ↓
02. Search Hotels - Mahabalipuram (Route 1)
02b. Search Hotels - Thanjavur (Route 2)
02c. Search Hotels - Madurai (Route 3)
02d. Search Hotels - Rameswaram (Route 4)
    ↓
(Compare results with backend response)
```

---

## Detailed Request Guide

### Step 1: Call Backend Endpoint

**File:** `00-BackendFlow.bru`

```http
GET http://localhost:4006/api/v1/itineraries/hotel_details/DVI2026011
```

**What the backend does:**
1. Queries `dvi_itinerary_plan_details` for plan ID (3) and nights (4)
2. Queries `dvi_itinerary_route_details` for routes
3. Maps destinations to TBO city codes from `dvi_cities`
4. **Prepares 4 TBO API payloads** (one per route)
5. Returns structured response with 4 price-tier packages

**Backend Response:**
```json
{
  "quoteId": "DVI2026011",
  "planId": 3,
  "hotelTabs": [
    {"groupType": 1, "label": "Budget Hotels", "totalAmount": 10000},
    {"groupType": 2, "label": "Mid-Range Hotels", "totalAmount": 15000},
    {"groupType": 3, "label": "Premium Hotels", "totalAmount": 20000},
    {"groupType": 4, "label": "Luxury Hotels", "totalAmount": 25000}
  ],
  "hotels": [
    {
      "groupType": 1,
      "itineraryRouteId": 84,
      "day": "Day 1 | 2026-03-26",
      "destination": "Mahabalipuram",
      "hotelName": "Hotel ABC",
      "totalHotelCost": 5000
    },
    ...
  ]
}
```

---

### Step 2-5: Make TBO API Calls

Each of these files contains the EXACT payload the backend sends to TBO:

**File: `02-SearchHotels.bru`**
```http
POST https://affiliate.tektravels.com/HotelAPI/Search
Authorization: Basic VEJPQXBpOlRCT0FwaUAxMjM=

{
  "CheckIn": "2026-03-26",
  "CheckOut": "2026-03-27",
  "CityCode": "126117",
  "GuestNationality": "IN",
  ...
}
```

**File: `02b-SearchHotels-Thanjavur.bru`**
```
CityCode: 139605
Date: 2026-03-27
```

**File: `02c-SearchHotels-Madurai.bru`**
```
CityCode: 127067
Date: 2026-03-28
```

**File: `02d-SearchHotels-Rameswaram.bru`**
```
CityCode: 133179
Date: 2026-03-29
```

---

## Authorization

All TBO API calls use **Basic Auth**:

```
Authorization: Basic VEJPQXBpOlRCT0FwaUAxMjM=
```

**Decoded:**
```
TBOApi:TBOApi@123
```

**Backend Code:**
```typescript
const basicAuth = Buffer.from('TBOApi:TBOApi@123').toString('base64');
headers: {
  'Authorization': `Basic ${basicAuth}`,
}
```

---

## Testing Workflow

### Option 1: Fully Automated (Bruno Variables)

1. Run `00-BackendFlow.bru` - extracts backend response data
2. Run `02-SearchHotels.bru` - uses extracted data
3. Run `02b-SearchHotels-Thanjavur.bru`
4. Run `02c-SearchHotels-Madurai.bru`
5. Run `02d-SearchHotels-Rameswaram.bru`

### Option 2: Manual Testing

1. Run `00-BackendFlow.bru` - see what backend does
2. Run each TBO request individually to see raw TBO responses
3. Compare with backend's hotel packages

### Option 3: Collection Run (All at Once)

In Bruno: `Run` → Select all requests → See full flow

---

## What Each Layer Does

### Layer 1: Backend API
- **Input:** Quote ID (DVI2026011)
- **Process:** Query DB, map cities, build TBO payloads
- **Output:** 4 price-tier hotel packages

### Layer 2: TBO API (4 calls)
- **Input:** City code, dates (from backend)
- **Process:** Search hotels, return availability
- **Output:** Hotels with prices and details

### Layer 3: Bruno Tests
- **Verify:** Backend ↔ TBO data consistency
- **Validate:** Prices, hotels, dates match
- **Debug:** See exact payloads sent/received

---

## Files in Collection

| File | Purpose |
|------|---------|
| `00-BackendFlow.bru` | Call backend, extract data |
| `01-Authenticate.bru` | Auth endpoint (optional) |
| `02-SearchHotels.bru` | TBO API - Mahabalipuram |
| `02b-SearchHotels-Thanjavur.bru` | TBO API - Thanjavur |
| `02c-SearchHotels-Madurai.bru` | TBO API - Madurai |
| `02d-SearchHotels-Rameswaram.bru` | TBO API - Rameswaram |
| `03-PreBook.bru` | PreBook endpoint |
| `04-Book.bru` | Book endpoint |
| `05-DVI-LocalEndpoint.bru` | Full validation test |
| `06-DynamicItineraryFlow.bru` | Flow with tests |

---

## Expected Results

When all requests succeed:

1. **00-BackendFlow:** ✅ 200 OK - Returns 4 hotel packages
2. **02-SearchHotels:** ✅ 200 OK - Returns hotels for Mahabalipuram
3. **02b:** ✅ 200 OK - Returns hotels for Thanjavur
4. **02c:** ✅ 200 OK - Returns hotels for Madurai
5. **02d:** ✅ 200 OK - Returns hotels for Rameswaram

**Verification:**
- Hotels from TBO match backend's hotel names
- Prices align with price tiers (Budget < Mid < Premium < Luxury)
- All 4 routes have hotels (or placeholders)

---

## Debugging

### If TBO returns 500 Error:
- Check date is in future
- Verify CityCode matches dvi_cities.tbo_city_code
- Confirm Authorization header is sent

### If backend returns empty hotels:
- Check dvi_cities table has TBO codes
- Verify routes exist in dvi_itinerary_route_details
- Look at backend logs for city mapping issues

### If prices don't match:
- Backend applies price-tier algorithm
- TBO returns raw prices
- Backend selects hotels by tier (not TBO order)

---

## Key Differences: Backend vs TBO API

| Aspect | Backend | TBO API |
|--------|---------|---------|
| **Input** | Quote ID | City code + dates |
| **DB Queries** | 3 queries | None |
| **TBO Calls** | 4 calls (1 per route) | 1 call per request |
| **Output** | 4 price tiers | Raw hotels |
| **Hotel Selection** | Price-tier algorithm | No selection |
| **Authorization** | Internal only | Basic Auth required |

---

## Ready to Test?

Start with: **`00-BackendFlow.bru`** 🚀
