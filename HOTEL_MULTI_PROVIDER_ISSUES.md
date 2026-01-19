# HOTEL BOOKING FLOW - ISSUES & FIXES

## ✅ WHAT'S ALREADY WORKING:

### Backend:
1. ✅ **HotelSearchService** returns hotels from BOTH TBO and ResAvenue
2. ✅ **Provider field** is set correctly:
   - TBO: `provider: 'tbo'`
   - ResAvenue: `provider: 'ResAvenue'`
3. ✅ **TboHotelBookingService** - Confirms and cancels TBO bookings
4. ✅ **ResAvenueHotelBookingService** - Confirms and cancels ResAvenue bookings
5. ✅ **Cancellation integration** - Both providers called in cancelHotels()

### Frontend:
1. ✅ **Provider badge** now displays on hotel cards (TBO or ResAvenue)
2. ✅ **HotelSearchResult** type includes provider field
3. ✅ **No hardcoded "TBO" assumptions** in hotel display

---

## ❌ REMAINING ISSUES:

### Issue 1: Frontend sends `tbo_hotels` hardcoded field name
**Location:** `ItineraryDetails.tsx` line 1712
```typescript
tbo_hotels: tboHotels.length > 0 ? tboHotels : undefined,
```

**Problem:** 
- Variable named `tbo_hotels` even for ResAvenue hotels
- Confusing and not multi-provider

**Fix:** Rename to generic `hotel_bookings` with provider field:
```typescript
hotel_bookings: hotelBookings.length > 0 ? hotelBookings : undefined,
```

Each hotel should have:
```typescript
{
  provider: 'tbo' | 'ResAvenue', // From search result
  hotelCode: string,
  bookingCode: string,
  routeId: number,
  passengers: [...],
  ...
}
```

---

### Issue 2: Backend only processes `tbo_hotels` array
**Location:** `itineraries.service.ts` line 1327
```typescript
if (!dto.tbo_hotels || dto.tbo_hotels.length === 0) {
  return baseResult;
}
```

**Problem:**
- Only looks for `tbo_hotels` field
- ResAvenue bookings would be ignored

**Fix:** 
1. Update DTO to accept `hotel_bookings` array
2. Group by provider and route to correct service:
```typescript
if (!dto.hotel_bookings || dto.hotel_bookings.length === 0) {
  return baseResult;
}

// Group by provider
const tboHotels = dto.hotel_bookings.filter(h => h.provider === 'tbo');
const resavenueHotels = dto.hotel_bookings.filter(h => h.provider === 'ResAvenue');

// Process TBO bookings
if (tboHotels.length > 0) {
  const tboResults = await this.tboHotelBooking.confirmItineraryHotels(...);
}

// Process ResAvenue bookings
if (resavenueHotels.length > 0) {
  const resavenueResults = await this.resavenueHotelBooking.confirmItineraryHotels(...);
}
```

---

### Issue 3: Frontend variable names assume TBO
**Location:** Multiple places in `ItineraryDetails.tsx`
- `selectedTboHotels` state (line 744)
- `setSelectedTboHotels` setter
- Comments say "TBO Hotel Selection State"

**Problem:** Misleading variable names

**Fix:** Rename to generic:
```typescript
const [selectedHotels, setSelectedHotels] = useState<{[routeId: number]: {...}}>({});
```

---

### Issue 4: Missing provider field in hotel selection
**Location:** `ItineraryDetails.tsx` line 1427
```typescript
setSelectedTboHotels(prev => ({
  ...prev,
  [routeId]: {
    hotelCode: ...,
    bookingCode: ...,
    // ❌ Missing: provider field
  }
}));
```

**Fix:** Include provider from search result:
```typescript
setSelectedHotels(prev => ({
  ...prev,
  [routeId]: {
    provider: hotelSearchResult.provider, // ✅ Add this
    hotelCode: ...,
    bookingCode: ...,
  }
}));
```

---

## 🎯 IMPLEMENTATION PRIORITY:

### HIGH PRIORITY (Breaks multi-provider):
1. ✅ Backend DTO - Accept `hotel_bookings` instead of `tbo_hotels`
2. ✅ Backend service - Route by provider field
3. ✅ Frontend - Send provider field in booking payload

### MEDIUM PRIORITY (Code clarity):
4. ⚠️ Rename `selectedTboHotels` → `selectedHotels`
5. ⚠️ Rename `tbo_hotels` variable → `hotel_bookings`
6. ⚠️ Update comments to be provider-agnostic

### LOW PRIORITY (Already working):
7. ✅ Display provider badge (DONE)
8. ✅ Cancellation handles both providers (DONE)

---

## 📋 VERIFICATION CHECKLIST:

After implementing fixes:
- [ ] Frontend sends `hotel_bookings` array with provider field
- [ ] Backend accepts `hotel_bookings` in DTO
- [ ] Backend routes TBO bookings to TboHotelBookingService
- [ ] Backend routes ResAvenue bookings to ResAvenueHotelBookingService
- [ ] Both booking services save to their respective tables
- [ ] Cancellation works for both providers
- [ ] No hardcoded "TBO" references in booking flow
- [ ] Provider badge displays correctly on hotel cards

---

## 🔍 CURRENT FLOW:

**Search:** ✅ Multi-provider (TBO + ResAvenue)
**Display:** ✅ Shows provider badge
**Selection:** ❌ Uses TBO-hardcoded variables
**Booking:** ❌ Only processes `tbo_hotels` array
**Cancellation:** ✅ Multi-provider (TBO + ResAvenue)

---

## 🎯 TARGET FLOW:

**Search:** ✅ Multi-provider (TBO + ResAvenue)
**Display:** ✅ Shows provider badge
**Selection:** ✅ Generic `selectedHotels` with provider field
**Booking:** ✅ Routes by provider dynamically
**Cancellation:** ✅ Multi-provider (TBO + ResAvenue)
