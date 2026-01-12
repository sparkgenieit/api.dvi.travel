# Hotel Response Source - Visual Guide

## Where Does Each Endpoint Get Data?

```
┌─────────────────────────────────────────────────────────────────────┐
│                     REQUEST TO API                                  │
└─────────────────────────────────────────────────────────────────────┘
                                │
                    ┌───────────┴───────────┐
                    │                       │
    ┌───────────────▼──────────────┐   ┌──▼─────────────────────────┐
    │ /hotel_details/:quoteId      │   │ /hotel_room_details/:quoteId│
    └──────────────┬────────────────┘   └──┬──────────────────────────┘
                   │                       │
         ┌─────────▼─────────┐    ┌────────▼────────────┐
         │  TBO API SERVICE  │    │  DATABASE SERVICE   │
         └─────────┬─────────┘    └────────┬────────────┘
                   │                       │
           ┌───────▼────────┐      ┌──────▼──────────────────┐
           │ TBO Search API │      │ dvi_itinerary_plan_     │
           │ (External)     │      │ hotel_details table     │
           └─────────────────┘      └────────┬───────────────┘
                                             │
                                    ┌────────▼──────────┐
                                    │ JOIN with dvi_hotel
                                    │ (Get names, ratings)
                                    └────────┬──────────┘
                                             │
        ┌────────────────────────────────────┴──────────────┐
        │                                                   │
    ┌───▼──────────────────────┐      ┌────────────────────▼───┐
    │ Response: 16 Hotels      │      │ Response: 20 Rooms     │
    │ (4 categories × 4 routes)│      │ (5 hotels × 4 routes)  │
    └────────────────────────┬─┘      └────────────────────┬───┘
                             │                             │
                ┌────────────▼──────────────┐  ┌──────────▼────────┐
                │ "No Hotels Available"     │  │ MAMALLA HERITAGE   │
                │ (TBO doesn't have hotels) │  │ Grand Ashok        │
                │                           │  │ Hotel Parisutham   │
                │ Status: ❌ 0 hotels      │  │ The Madurai Resid. │
                │                           │  │ STAR PALACE        │
                └───────────────────────────┘  │                    │
                                               │ Status: ✅ 5 hotels
                                               └────────────────────┘
```

---

## Side-by-Side Comparison

```
┌──────────────────────────────┬──────────────────────────────┐
│   hotel_details              │   hotel_room_details         │
├──────────────────────────────┼──────────────────────────────┤
│ 🔄 SOURCE: TBO API           │ 💾 SOURCE: DATABASE          │
│                              │                              │
│ 🌐 Real-Time Generation      │ 📦 Cached/Saved Data         │
│                              │                              │
│ 🔍 Queries external API      │ 🔍 Queries local DB          │
│                              │                              │
│ ⏱️  Slower (~2-5 seconds)    │ ⚡ Fast (< 100ms)           │
│                              │                              │
│ 🎯 USE: Generate packages    │ 🎯 USE: View selections      │
│                              │                              │
│ ❌ For DVI2026011:           │ ✅ For DVI2026011:           │
│    "No Hotels Available"     │    5 Real Hotels            │
│    (TBO doesn't have these   │    (Pre-saved data)         │
│     cities)                  │                              │
└──────────────────────────────┴──────────────────────────────┘
```

---

## Data Flow for DVI2026011

### Path 1: Reading from TBO API (hotel_details)

```
hotel_details Endpoint
    ↓
Get plan (planId = 3)
    ↓
Get routes (5 destinations)
    ↓
For each route:
    - mapDestinationToCityCode("Mahabalipuram") → "126117"
    - queryHotelCodesFromDatabase(126117) → [1050100, 1050101, ...]
    - callTBOSearchAPI() → ❌ Returns 0 results
    ↓
Generate placeholder hotels
    ↓
Response: 16 rows of "No Hotels Available"
(4 categories × 4 routes needing hotels)
```

### Path 2: Reading from Database (hotel_room_details)

```
hotel_room_details Endpoint
    ↓
Get plan (planId = 3)
    ↓
Query dvi_itinerary_plan_hotel_details
    WHERE itinerary_plan_id = 3
    ↓
Found: 20 rows
    ↓
Extract hotel_ids: [277, 283, 335, 635, 356]
    ↓
JOIN with dvi_hotel table
    ↓
Get hotel names, details:
    277 → MAMALLA HERITAGE
    283 → Grand Ashok
    335 → Hotel Parisutham
    635 → The Madurai Residency
    356 → STAR PALACE
    ↓
Response: 20 rows with real hotel data
(5 hotels × 4 routes)
```

---

## Which Should You Use?

| Scenario | Use Endpoint | Reason |
|----------|--------------|--------|
| First-time generating hotels | `hotel_details` | Gets fresh options from TBO |
| Re-generating/refreshing options | `hotel_details` | Queries TBO API again |
| Viewing saved selections | `hotel_room_details` | Reads from database (faster) |
| Building UI for selected hotels | `hotel_room_details` | Shows what was confirmed |

---

## Current Status for DVI2026011

```
hotel_details: 
  - Calls TBO API
  - Returns: "No Hotels Available" (correct - TBO has no hotels for these cities)
  - Expected behavior: ✅ Working as designed

hotel_room_details:
  - Reads from database
  - Returns: 5 real hotels (MAMALLA HERITAGE, Grand Ashok, etc.)
  - Expected behavior: ✅ Working as designed
```

**Both endpoints are working correctly!** ✅
