# Bruno Collection Quick Start

## 🚀 Start Here

### Step 1: Open Bruno
Open VS Code → Bruno Extension → Open Collection

### Step 2: Navigate to `test_bruno` folder

All `.bru` files will load automatically

### Step 3: Run Requests in Order

```
1. Run: 00-BackendFlow.bru
   ↓
   (See what YOUR BACKEND does)
   
2. Run: 07-RequestSequenceSummary.bru
   ↓
   (See complete flow diagram)
   
3. Run: 02-SearchHotels.bru
4. Run: 02b-SearchHotels-Thanjavur.bru
5. Run: 02c-SearchHotels-Madurai.bru
6. Run: 02d-SearchHotels-Rameswaram.bru
   ↓
   (See what TBO API returns for each route)
```

---

## 📊 File Map

```
test_bruno/
├── bruno.json                          ← Collection config
├── 00-BackendFlow.bru                 ← START HERE (Backend endpoint)
├── 01-Authenticate.bru                ← Optional (TBO auth)
├── 02-SearchHotels.bru                ← Route 1: Mahabalipuram
├── 02b-SearchHotels-Thanjavur.bru    ← Route 2: Thanjavur
├── 02c-SearchHotels-Madurai.bru      ← Route 3: Madurai
├── 02d-SearchHotels-Rameswaram.bru   ← Route 4: Rameswaram
├── 03-PreBook.bru
├── 04-Book.bru
├── 05-DVI-LocalEndpoint.bru
├── 06-DynamicItineraryFlow.bru
├── 07-RequestSequenceSummary.bru     ← Flow Diagram
├── auth-analysis.js                   ← Authorization breakdown
├── test-itinerary-flow.js             ← Database query analysis
├── BRUNO_GUIDE.md                     ← Full documentation
├── PAYLOAD_ANALYSIS.md                ← Payload details
└── QUICKSTART.md                      ← This file
```

---

## 🔄 The Flow

### Backend Makes 4 Database Queries + 4 TBO API Calls

```
Quote ID: DVI2026011
    ↓
Query: dvi_itinerary_plan_details
    ↓
Query: dvi_itinerary_route_details (5 routes)
    ↓
Map each destination to TBO city code
    ├─ Mahabalipuram → 126117
    ├─ Thanjavur → 139605
    ├─ Madurai → 127067
    ├─ Rameswaram → 133179
    └─ Madurai Airport → SKIP (departure)
    ↓
Make 4 TBO API calls
    ├─ POST /Search (CityCode: 126117)
    ├─ POST /Search (CityCode: 139605)
    ├─ POST /Search (CityCode: 127067)
    └─ POST /Search (CityCode: 133179)
    ↓
Generate 4 price-tier packages
    ├─ Budget Hotels
    ├─ Mid-Range Hotels
    ├─ Premium Hotels
    └─ Luxury Hotels
    ↓
Response: 200 OK
```

---

## ✅ What to Verify

1. **Backend Response (00-BackendFlow.bru)**
   - ✅ Status: 200 OK
   - ✅ hotelTabs: 4 items
   - ✅ hotels: Multiple entries
   - ✅ Prices: Budget < Mid < Premium < Luxury

2. **TBO Responses (02-02d)**
   - ✅ Status: 200 OK
   - ✅ Status.Code: 200
   - ✅ HotelResult: Array of hotels
   - ✅ Rooms: With pricing

3. **Data Consistency**
   - ✅ Hotels in backend response match TBO responses
   - ✅ Prices align with tiers
   - ✅ Dates match (26-Mar, 27-Mar, 28-Mar, 29-Mar)
   - ✅ City codes correct

---

## 🔐 Authorization

All TBO requests use:
```
Authorization: Basic VEJPQXBpOlRCT0FwaUAxMjM=
```

**This is:**
- Base64 encoded: `TBOApi:TBOApi@123`
- Hardcoded in backend (not from .env)
- Same for all TBO API calls

---

## 📝 Notes

- **Backend endpoint is local:** `http://localhost:4006/...`
- **TBO endpoints are production:** `https://affiliate.tektravels.com/...`
- **Quote ID:** DVI2026011 (must exist in your database)
- **Dates:** Must be future dates (currently Jan 2026, routes are Mar 2026)
- **Response time:** ~5-15 seconds (4 API calls to TBO)

---

## 🔧 Troubleshooting

### Backend returns 404
- Ensure backend is running on port 4006
- Check quote ID exists: `SELECT * FROM dvi_itinerary_plan_details WHERE itinerary_quote_ID = 'DVI2026011'`

### TBO returns 500
- Dates must be in future
- City code must be valid (check dvi_cities table)
- Authorization header must be present

### TBO returns empty hotels
- Valid response (no hotels available for that date/city)
- Try different quote ID with different dates

### Prices don't match
- Backend applies price-tier algorithm (not raw TBO prices)
- Each tier selects different hotels
- Totals are sums across 4 routes

---

## 📖 Full Documentation

- **BRUNO_GUIDE.md** - Complete testing guide
- **PAYLOAD_ANALYSIS.md** - Detailed payload breakdown
- **test-itinerary-flow.js** - Run: `node test_bruno/test-itinerary-flow.js`
- **auth-analysis.js** - Run: `node test_bruno/auth-analysis.js`

---

Ready? **Start with `00-BackendFlow.bru`** 🎯
