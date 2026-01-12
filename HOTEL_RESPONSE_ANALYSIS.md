## Hotel Response Source Analysis for DVI2026011

### Question: Where does the response come from - TBO API or DB?

**Answer: BOTH - It depends on the endpoint!**

---

## Endpoint Comparison

### 1️⃣ `/api/v1/itineraries/hotel_details/:quoteId`
- **Source**: 🔄 **TBO API (Real-time)**
- **Flow**: 
  - Calls TBOHotelProvider.search()
  - Queries tbo_hotel_master for available hotels
  - Calls TBO Search API
  - Returns fresh packages (Budget, Mid-Range, Premium, Luxury)
- **Caching**: Results are NOT automatically saved
- **Use Case**: Generate/preview hotel options

---

### 2️⃣ `/api/v1/itineraries/hotel_room_details/:quoteId`
- **Source**: 💾 **DATABASE Only**
- **Flow**:
  - Reads from `dvi_itinerary_plan_hotel_details` table
  - Joins hotel info from `dvi_hotel` table
  - Returns structured room details
- **Data Origin**: Hotels saved when:
  - User manually selected from `hotel_details` response, OR
  - System auto-selected and persisted
- **Use Case**: View saved/confirmed hotel selections

---

## Current Issue: DVI2026011 Data Structure

### What's in dvi_itinerary_plan_hotel_details:

```
Sample Record:
{
  "itinerary_plan_hotel_details_ID": 277,
  "hotel_id": 277,  ← Points to dvi_hotel table
  "hotel_category_id": 2,
  "hotel_required": 1,
  "total_room_cost": 3950,
  "total_hotel_cost": 3952,
  "group_type": 1,
  "itinerary_route_location": "Mahabalipuram"
  ... (pricing details)
}
```

### Hotel Name Resolution:
- `hotel_id: 277` is stored
- Hotel name must be looked up from `dvi_hotel` table using `hotel_id`
- If lookup fails → "No Hotels Available" placeholder is shown

---

## Response Flow for hotel_room_details

```
REQUEST: GET /api/v1/itineraries/hotel_room_details/DVI2026011
    ↓
1. Get plan from dvi_itinerary_plan_details (quoteId → planId=3)
    ↓
2. Fetch 20 records from dvi_itinerary_plan_hotel_details (planId=3)
    ↓
3. Extract hotel_ids from those records
    ↓
4. Join with dvi_hotel table to get hotel names, addresses, ratings
    ↓
5. Build response with:
   - Hotel details (from dvi_hotel)
   - Pricing details (from dvi_itinerary_plan_hotel_details)
    ↓
RESPONSE: ItineraryHotelRoomDetailsResponseDto
```

---

## Key Finding

✅ **Database contains 20 hotel records for DVI2026011**
- Plan ID: 3
- Records count: 20 (4 categories × 5 routes)
- Hotel ID stored: 277

⚠️ **The issue might be**: Hotel ID 277 lookup in `dvi_hotel` table returning NULL/not found
- This causes "No Hotels Available" message

---

## Next Steps to Debug

Check if hotel_id 277 exists in dvi_hotel:
```sql
SELECT * FROM dvi_hotel WHERE hotel_id = 277;
```

If missing → Hotels were never synced from TBO or manually created.

---

## Summary

| Question | Answer |
|----------|--------|
| Where does hotel_details response come from? | **TBO API** (real-time) |
| Where does hotel_room_details come from? | **Database** (dvi_itinerary_plan_hotel_details) |
| Is data cached? | Yes, in dvi_itinerary_plan_hotel_details |
| Why "No Hotels Available" in response? | Hotel lookup (dvi_hotel) failing for stored hotel_ids |
