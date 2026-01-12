# TBO API Payload Analysis for DVI2026011

## How the Backend Works

When you call: `GET http://localhost:4006/api/v1/itineraries/hotel_details/DVI2026011`

The backend performs these exact database queries and TBO API calls:

---

## Step 1: Get Itinerary Plan

```sql
SELECT * FROM dvi_itinerary_plan_details 
WHERE itinerary_quote_ID = 'DVI2026011' AND deleted = 0
```

**Result:**
- **Plan ID**: 3
- **No. of Nights**: 4
- **Quote ID**: DVI2026011

---

## Step 2: Get Routes

```sql
SELECT * FROM dvi_itinerary_route_details 
WHERE itinerary_plan_ID = 3 AND deleted = 0
ORDER BY itinerary_route_date ASC
```

**Result: 5 Routes**

| Route | Date | Departing From | Staying At | Hotel? |
|-------|------|----------------|------------|--------|
| 1 | 2026-03-26 | Chennai Airport | Mahabalipuram | ✅ |
| 2 | 2026-03-27 | Mahabalipuram | Thanjavur | ✅ |
| 3 | 2026-03-28 | Thanjavur | Madurai | ✅ |
| 4 | 2026-03-29 | Madurai | Rameswaram | ✅ |
| 5 | 2026-03-30 | Rameswaram | Madurai Airport | ❌ (Departure day, no hotel) |

---

## Step 3: Map Destinations to TBO City Codes

```sql
SELECT * FROM dvi_cities WHERE name = <destination>
```

**Results:**

| Route | Destination | TBO City Code |
|-------|-------------|--------------|
| 1 | Mahabalipuram | 126117 |
| 2 | Thanjavur | 139605 |
| 3 | Madurai | 127067 |
| 4 | Rameswaram | 133179 |

---

## Step 4: TBO API Calls

The backend makes **4 separate TBO API calls** (one per hotel route):

### Call 1: Mahabalipuram

```http
POST https://affiliate.tektravels.com/HotelAPI/Search
Authorization: Basic VEJPQXBpOlRCT0FwaUAxMjM=
Content-Type: application/json

{
  "CheckIn": "2026-03-26",
  "CheckOut": "2026-03-27",
  "HotelCodes": "",
  "CityCode": "126117",
  "GuestNationality": "IN",
  "PaxRooms": [
    {
      "Adults": 2,
      "Children": 0,
      "ChildrenAges": []
    }
  ],
  "ResponseTime": 23.0,
  "IsDetailedResponse": true,
  "Filters": {
    "Refundable": true,
    "NoOfRooms": 0,
    "MealType": "WithMeal",
    "OrderBy": 0,
    "StarRating": 0,
    "HotelName": null
  }
}
```

### Call 2: Thanjavur

```http
POST https://affiliate.tektravels.com/HotelAPI/Search
Authorization: Basic VEJPQXBpOlRCT0FwaUAxMjM=

{
  "CheckIn": "2026-03-27",
  "CheckOut": "2026-03-28",
  "CityCode": "139605",
  ...same as above...
}
```

### Call 3: Madurai

```http
POST https://affiliate.tektravels.com/HotelAPI/Search
Authorization: Basic VEJPQXBpOlRCT0FwaUAxMjM=

{
  "CheckIn": "2026-03-28",
  "CheckOut": "2026-03-29",
  "CityCode": "127067",
  ...same as above...
}
```

### Call 4: Rameswaram

```http
POST https://affiliate.tektravels.com/HotelAPI/Search
Authorization: Basic VEJPQXBpOlRCT0FwaUAxMjM=

{
  "CheckIn": "2026-03-29",
  "CheckOut": "2026-03-30",
  "CityCode": "133179",
  ...same as above...
}
```

---

## Step 5: Combine Results & Generate Price Tiers

The backend collects all hotel results from the 4 TBO API calls and generates:

- **4 price-tier packages**: Budget, Mid-Range, Premium, Luxury
- **Each package includes hotels for all 4 routes**
- **If a route has no hotels**: Adds placeholder with price 0

---

## Final Response Structure

```json
{
  "quoteId": "DVI2026011",
  "planId": 3,
  "hotelRatesVisible": true,
  "hotelTabs": [
    {
      "groupType": 1,
      "label": "Budget Hotels",
      "totalAmount": <sum of budget hotels across all 4 routes>
    },
    {
      "groupType": 2,
      "label": "Mid-Range Hotels",
      "totalAmount": <sum>
    },
    {
      "groupType": 3,
      "label": "Premium Hotels",
      "totalAmount": <sum>
    },
    {
      "groupType": 4,
      "label": "Luxury Hotels",
      "totalAmount": <sum>
    }
  ],
  "hotels": [
    {
      "groupType": 1,
      "itineraryRouteId": 84,
      "day": "Day 1 | 2026-03-26",
      "destination": "Mahabalipuram",
      "hotelName": "Hotel Name",
      "totalHotelCost": 5000,
      ...
    },
    ...more hotels...
  ],
  "totalRoomCount": <total hotel entries>
}
```

---

## Bruno Test Files

The test_bruno folder now contains:

1. **02-SearchHotels.bru** - Mahabalipuram (Route 1)
2. **02b-SearchHotels-Thanjavur.bru** - Thanjavur (Route 2)
3. **02c-SearchHotels-Madurai.bru** - Madurai (Route 3)
4. **02d-SearchHotels-Rameswaram.bru** - Rameswaram (Route 4)
5. **06-DynamicItineraryFlow.bru** - Full endpoint test with validation

You can now run each Bruno file to test the exact payloads the backend sends! ✅
