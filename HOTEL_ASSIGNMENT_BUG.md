# Hotel Assignment Issue Analysis

## Problem
Chennai is getting assigned "Mango Hill Shola Ooty" hotel (hotel_id: 610) which is located in Ooty, not Chennai.

## Root Cause

### Tables Involved
1. **dvi_itinerary_plan_hotel_details** - Stores hotel assignments per route
   - `itinerary_route_location` - City where hotel is needed (uses `next_visiting_location`)
   - `hotel_id` - Assigned hotel

2. **dvi_hotel** - Hotel master data
   - `hotel_id` - Primary key
   - `hotel_name` - Hotel name
   - `hotel_city` - City where hotel is located
   - `hotel_category` - Star rating (1-5)

3. **dvi_hotel_room_price_book** - Room prices by date
   - `hotel_id` - Foreign key
   - `day_1` through `day_31` - Price columns for each day of month
   - `month` - Month name
   - `year` - Year

4. **dvi_itinerary_route_details** - Route information
   - `location_name` - Starting location
   - `next_visiting_location` - Destination (WHERE HOTEL IS NEEDED)
   - `itinerary_route_date` - Travel date

## Code Flow

### File: hotel-engine.service.ts

```typescript
async rebuildPlanHotels(planId: number, tx: Tx, userId: number) {
  // For each route (except last one - departure day)
  for (let routeIndex = 0; routeIndex < routes.length; routeIndex++) {
    const r = routes[routeIndex];
    const city = r.next_visiting_location; // ✅ Correctly uses destination
    
    // For each group type (Recommended #1, #2, #3, #4)
    for (const groupType of [1, 2, 3, 4]) {
      // ❌ THIS IS WHERE THE BUG IS
      const hotel = await this.hotelPricing.pickHotelByCategory(
        preferredCategory,  // e.g., 3 (3-star)
        city,               // e.g., "Chennai"
        routeDate
      );
      
      // If hotel found, create room details
      // Creates record in dvi_itinerary_plan_hotel_details with:
      //   itinerary_route_location = r.next_visiting_location
      //   hotel_id = hotel.hotel_id
    }
  }
}
```

### File: hotel-pricing.service.ts

```typescript
async pickHotelByCategory(hotel_category: number, city?: string, onDate?: Date) {
  // Step 1: Try to find hotel in the specified city
  const hotels = await prisma.dvi_hotel.findMany({
    where: { 
      hotel_category: hotel_category,
      hotel_city: city  // e.g., "Chennai"
    }
  });
  
  if (hotels.length > 0) {
    // Filter to only hotels with valid rates for the date
    const validHotels = hotels.filter(h => hasValidRates(h.hotel_id, onDate));
    
    if (validHotels.length > 0) {
      return validHotels[Math.floor(Math.random() * validHotels.length)];
    }
    // ⚠️ No valid hotels in this city, fall through to fallback
  }
  
  // ❌ BUG: Fallback to ANY hotel in the category (ignores city!)
  const fallbacks = await prisma.dvi_hotel.findMany({
    where: { hotel_category: hotel_category }  // No city filter!
  });
  
  if (fallbacks.length > 0) {
    const validFallbacks = fallbacks.filter(h => hasValidRates(h.hotel_id, onDate));
    if (validFallbacks.length > 0) {
      // Returns random hotel from ANY city
      return validFallbacks[Math.floor(Math.random() * validFallbacks.length)];
    }
  }
  
  return null;
}
```

## Why Chennai Got Ooty Hotel

1. Route: Chennai → Bangalore (hotel needed in **Chennai**)
2. Requested category: 3 (3-star)
3. Date: 2025-12-24

### Search Process:
1. **Search for Chennai hotels in category 3**
   - Query: `SELECT * FROM dvi_hotel WHERE hotel_category = 3 AND hotel_city = 'Chennai'`
   - **No results found** OR **No valid rates for 2025-12-24**

2. **Fallback to ANY category 3 hotel**
   - Query: `SELECT * FROM dvi_hotel WHERE hotel_category = 3`
   - Returns all category 3 hotels including:
     - Ooty hotels (Mango Hill Shola Ooty)
     - Bangalore hotels
     - Mumbai hotels
     - etc.

3. **Filter by valid rates**
   - Check which hotels have non-zero price in `day_24` column for December 2025
   - "Mango Hill Shola Ooty" has valid rates

4. **Random selection**
   - Randomly picks "Mango Hill Shola Ooty" even though it's in Ooty, not Chennai

## The Fix

### Option 1: Never Fall Back to Different City (Strict)
```typescript
async pickHotelByCategory(hotel_category: number, city?: string, onDate?: Date) {
  // ... search in city ...
  
  if (validHotels.length > 0) {
    return validHotels[Math.floor(Math.random() * validHotels.length)];
  }
  
  // ✅ Don't fallback - return null if no hotel found in the city
  return null;
}
```

**Pros:** Prevents wrong city assignments  
**Cons:** May leave routes without hotels

### Option 2: Fall Back Only to Nearby Cities
```typescript
async pickHotelByCategory(hotel_category: number, city?: string, onDate?: Date) {
  // ... search in city ...
  
  if (validHotels.length === 0) {
    // Get nearby cities from a mapping
    const nearbyCities = getCitiesNearby(city); // e.g., Chennai → ["Mahabalipuram", "Kanchipuram"]
    
    for (const nearbyCity of nearbyCities) {
      const nearbyHotels = await findHotelsInCity(nearbyCity, hotel_category, onDate);
      if (nearbyHotels.length > 0) {
        return nearbyHotels[0];
      }
    }
  }
  
  return null;
}
```

**Pros:** Smart fallback  
**Cons:** Requires city proximity data

### Option 3: Add Warning Flag (Current + Alert)
```typescript
async pickHotelByCategory(hotel_category: number, city?: string, onDate?: Date) {
  const result = { hotel: null, isLocationMatch: true };
  
  // ... search in city ...
  
  if (validHotels.length > 0) {
    result.hotel = validHotels[Math.floor(Math.random() * validHotels.length)];
    result.isLocationMatch = true;
    return result;
  }
  
  // Fallback but mark as location mismatch
  const fallbacks = await findAnyHotels(hotel_category, onDate);
  if (fallbacks.length > 0) {
    result.hotel = fallbacks[0];
    result.isLocationMatch = false;  // ⚠️ City mismatch
    console.warn(`No hotels found in ${city}, using ${result.hotel.hotel_city} instead`);
    return result;
  }
  
  return result;
}
```

**Pros:** Maintains current behavior, adds visibility  
**Cons:** Still assigns wrong city hotels

## Recommended Fix

**Implement Option 1** - Return `null` if no hotel found in the requested city, then handle it gracefully in the UI.

```typescript
// hotel-pricing.service.ts
async pickHotelByCategory(hotel_category: number, city?: string | null, onDate?: Date) {
  const hotelCategory = Number(hotel_category) || 0;
  const cityTrim = (city ?? "").trim();
  
  if (!cityTrim) {
    // No city specified, fallback behavior OK
    return this.pickAnyHotelByCategory(hotelCategory, onDate);
  }
  
  // Try exact city match first
  const hotels = await this.prisma.dvi_hotel.findMany({
    where: { 
      hotel_category: hotelCategory,
      hotel_city: cityTrim,
      deleted: false,
      status: true
    }
  });
  
  if (hotels.length > 0 && onDate) {
    const validHotels = [];
    for (const h of hotels) {
      if (await this.hasValidRates(h.hotel_id, onDate)) {
        validHotels.push(h);
      }
    }
    
    if (validHotels.length > 0) {
      return validHotels[Math.floor(Math.random() * validHotels.length)];
    }
  }
  
  // ✅ FIX: Return null instead of falling back to different city
  console.warn(`No valid hotels found in ${cityTrim} for category ${hotelCategory} on ${onDate?.toISOString().slice(0,10)}`);
  return null;
}
```

## Testing Checklist

- [ ] Check hotel assignments for all routes
- [ ] Verify hotel_city matches itinerary_route_location
- [ ] Ensure no cross-city assignments (Chennai ≠ Ooty)
- [ ] Test with dates that have limited hotel availability
- [ ] Test with cities that have no hotels in requested category
- [ ] Verify fallback behavior is documented and intentional
