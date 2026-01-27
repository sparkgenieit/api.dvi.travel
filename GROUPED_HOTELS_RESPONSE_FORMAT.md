# Grouped Hotels Response Format

## Overview
Hotels are now grouped by **route + tier**, with a **selected hotel** (cheapest) and **available alternatives**.

## Response Structure

```typescript
{
  "quoteId": "DVI20260110",
  "planId": 12,
  "hotelRatesVisible": true,
  "hotelTabs": [
    {
      "groupType": 1,
      "label": "Budget Hotels",
      "totalAmount": 14076.28
    }
  ],
  "hotels": [
    {
      "groupType": 1,
      "itineraryRouteId": 139,
      "day": "Day 1 | 2026-04-25",
      "destination": "Chennai",
      "date": "2026-04-25",
      
      // DEFAULT SELECTED HOTEL (Cheapest Option)
      "selectedHotel": {
        "hotelId": 40,
        "hotelName": "Demo Hotel 2",
        "category": 4,
        "roomType": "Suite",
        "mealPlan": "Europian Plan",
        "totalHotelCost": 3000,
        "totalHotelTaxAmount": 0,
        "provider": "HOBSE",
        "searchReference": "...",
        "bookingCode": "..."
      },
      
      // ALL AVAILABLE HOTELS (Sorted by Price)
      "availableHotels": [
        {
          "hotelId": 40,
          "hotelName": "Demo Hotel 2",
          "category": 4,
          "roomType": "Suite",
          "mealPlan": "Europian Plan",
          "totalHotelCost": 3000,
          "totalHotelTaxAmount": 0,
          "provider": "HOBSE",
          "searchReference": "...",
          "bookingCode": "..."
        },
        {
          "hotelId": 1277095,
          "hotelName": "Pride Hotel Chennai",
          "category": 3,
          "roomType": "Superior Room,1 Twin Bed,NonSmoking",
          "mealPlan": "-",
          "totalHotelCost": 5229,
          "totalHotelTaxAmount": 0,
          "provider": "tbo",
          "searchReference": "1277095!TB!3!TB!...",
          "bookingCode": "1277095!TB!3!TB!..."
        },
        {
          "hotelId": 1128903,
          "hotelName": "The Residency Towers",
          "category": 4,
          "roomType": "Premier Room,2 Twin Beds,NonSmoking",
          "mealPlan": "Breakfast Included",
          "totalHotelCost": 10412,
          "totalHotelTaxAmount": 0,
          "provider": "tbo",
          "searchReference": "1128903!TB!1!TB!...",
          "bookingCode": "1128903!TB!1!TB!..."
        }
      ]
    }
  ],
  "totalRoomCount": 4
}
```

## Frontend Usage

### Display Default Selection
```typescript
// Show the cheapest hotel (selected)
const displayHotel = hotelRow.selectedHotel;
```

### Show Alternatives on Click
```typescript
// When user clicks the hotel row
const alternatives = hotelRow.availableHotels;
// Display in modal/dropdown to let user switch
```

### Change Hotel Selection
```typescript
// When user selects a different hotel
const newSelected = hotelRow.availableHotels[index];
// Send to backend with new bookingCode/searchReference
```

## Benefits

✅ **One hotel per night per tier** - No information overload  
✅ **Always shows cheapest option** - Budget-conscious default  
✅ **Full flexibility** - User can switch to TBO or HOBSE alternatives  
✅ **Multiple providers** - TBO + HOBSE hotels in single interface  
✅ **Simple API structure** - Frontend knows exactly what to display

## API Endpoints

- `/api/v1/itineraries/hotel_details/{quoteId}` - Returns grouped hotels
- `/api/v1/itineraries/hotel_details_tbo/{quoteId}` - Alternative TBO endpoint

Both return the same grouped structure with `selectedHotel` + `availableHotels`.
