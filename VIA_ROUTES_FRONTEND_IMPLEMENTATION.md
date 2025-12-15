# Via Routes Frontend Implementation Guide

## 🎯 Overview
This guide provides step-by-step instructions to add via routes functionality to your itinerary creation form.

---

## 📋 Prerequisites

**Backend Status:** ✅ Fully implemented
- API accepts `via_routes[]` in route payload
- Auto-saves/deletes via routes
- Includes via locations in hotspot search

**Frontend Work:** UI components to add/manage via routes

---

## 🔧 Implementation Steps

### Step 1: Update Route Interface/Type

**File:** `types/itinerary.ts` (or wherever route types are defined)

```typescript
// Add via route interface
export interface ViaRoute {
  itinerary_via_location_ID: number;
  itinerary_via_location_name: string;
}

// Update route interface to include via_routes
export interface ItineraryRoute {
  location_name: string;
  next_visiting_location: string;
  itinerary_route_date: string;
  no_of_days: number;
  no_of_km?: string;
  direct_to_next_visiting_place: number;
  via_route?: string;
  via_routes?: ViaRoute[];  // ← Add this field
}
```

---

### Step 2: Add Via Routes State Management

**File:** `CreateItinerary.tsx` (or your itinerary form component)

```typescript
// Add to your route state
const [routes, setRoutes] = useState<ItineraryRoute[]>([
  {
    location_name: '',
    next_visiting_location: '',
    itinerary_route_date: '',
    no_of_days: 1,
    direct_to_next_visiting_place: 1,
    via_route: '',
    via_routes: []  // ← Initialize empty array
  }
]);

// Function to add via route to a specific route
const addViaRoute = (routeIndex: number, viaLocation: { id: number; name: string }) => {
  setRoutes(prev => {
    const updated = [...prev];
    if (!updated[routeIndex].via_routes) {
      updated[routeIndex].via_routes = [];
    }
    
    updated[routeIndex].via_routes!.push({
      itinerary_via_location_ID: viaLocation.id,
      itinerary_via_location_name: viaLocation.name
    });
    
    // Update via_route string for display
    updated[routeIndex].via_route = updated[routeIndex].via_routes!
      .map(v => v.itinerary_via_location_name)
      .join(', ');
    
    return updated;
  });
};

// Function to remove via route
const removeViaRoute = (routeIndex: number, viaIndex: number) => {
  setRoutes(prev => {
    const updated = [...prev];
    updated[routeIndex].via_routes!.splice(viaIndex, 1);
    
    // Update via_route string
    updated[routeIndex].via_route = updated[routeIndex].via_routes!
      .map(v => v.itinerary_via_location_name)
      .join(', ');
    
    return updated;
  });
};
```

---

### Step 3: Create Via Route UI Component

**File:** `components/ViaRouteSelector.tsx` (new component)

```typescript
import React, { useState } from 'react';

interface ViaRouteSelectorProps {
  routeIndex: number;
  viaRoutes: ViaRoute[];
  onAddViaRoute: (routeIndex: number, location: { id: number; name: string }) => void;
  onRemoveViaRoute: (routeIndex: number, viaIndex: number) => void;
}

export const ViaRouteSelector: React.FC<ViaRouteSelectorProps> = ({
  routeIndex,
  viaRoutes,
  onAddViaRoute,
  onRemoveViaRoute
}) => {
  const [selectedLocation, setSelectedLocation] = useState<{ id: number; name: string } | null>(null);
  
  // You'll need to fetch locations from your API
  // For now, using example data
  const availableLocations = [
    { id: 101, name: 'Mahabalipuram' },
    { id: 102, name: 'Chidambaram' },
    { id: 103, name: 'Dindigul' },
    { id: 104, name: 'Tiruchendur' },
    // Add more locations from dvi_stored_locations table
  ];

  const handleAdd = () => {
    if (selectedLocation) {
      onAddViaRoute(routeIndex, selectedLocation);
      setSelectedLocation(null);
    }
  };

  return (
    <div className="via-route-selector">
      <label className="form-label">
        <i className="bi bi-signpost-2"></i> Via Route (Optional)
      </label>
      
      {/* Display existing via routes */}
      {viaRoutes && viaRoutes.length > 0 && (
        <div className="via-routes-list mb-2">
          {viaRoutes.map((via, index) => (
            <span key={index} className="badge bg-info me-2 mb-2">
              {via.itinerary_via_location_name}
              <button
                type="button"
                className="btn-close btn-close-white ms-2"
                onClick={() => onRemoveViaRoute(routeIndex, index)}
                aria-label="Remove"
              />
            </span>
          ))}
        </div>
      )}

      {/* Add new via route */}
      <div className="input-group">
        <select
          className="form-select"
          value={selectedLocation?.id || ''}
          onChange={(e) => {
            const loc = availableLocations.find(l => l.id === Number(e.target.value));
            setSelectedLocation(loc || null);
          }}
        >
          <option value="">Select intermediate stop...</option>
          {availableLocations.map(loc => (
            <option key={loc.id} value={loc.id}>
              {loc.name}
            </option>
          ))}
        </select>
        <button
          type="button"
          className="btn btn-outline-primary"
          onClick={handleAdd}
          disabled={!selectedLocation}
        >
          <i className="bi bi-plus-circle"></i> Add Stop
        </button>
      </div>
      
      <small className="text-muted">
        Add cities to visit between source and destination
      </small>
    </div>
  );
};
```

---

### Step 4: Integrate Component into Route Form

**File:** `CreateItinerary.tsx`

```typescript
import { ViaRouteSelector } from './components/ViaRouteSelector';

// Inside your route form section (for each route)
{routes.map((route, index) => (
  <div key={index} className="route-card mb-3">
    {/* Existing route fields */}
    <div className="row">
      <div className="col-md-6">
        <label>From</label>
        <input
          type="text"
          className="form-control"
          value={route.location_name}
          onChange={(e) => updateRoute(index, 'location_name', e.target.value)}
        />
      </div>
      
      <div className="col-md-6">
        <label>To</label>
        <input
          type="text"
          className="form-control"
          value={route.next_visiting_location}
          onChange={(e) => updateRoute(index, 'next_visiting_location', e.target.value)}
        />
      </div>
    </div>

    {/* ADD VIA ROUTES COMPONENT HERE */}
    <div className="row mt-3">
      <div className="col-12">
        <ViaRouteSelector
          routeIndex={index}
          viaRoutes={route.via_routes || []}
          onAddViaRoute={addViaRoute}
          onRemoveViaRoute={removeViaRoute}
        />
      </div>
    </div>

    {/* Rest of route fields... */}
  </div>
))}
```

---

### Step 5: Fetch Available Locations from API

**File:** `CreateItinerary.tsx`

```typescript
// Add state for locations
const [availableLocations, setAvailableLocations] = useState<Array<{ id: number; name: string }>>([]);

// Fetch locations on component mount
useEffect(() => {
  const fetchLocations = async () => {
    try {
      const response = await fetch('http://localhost:4006/api/v1/locations', {
        headers: {
          'Authorization': `Bearer ${yourAuthToken}`
        }
      });
      const data = await response.json();
      setAvailableLocations(data.map(loc => ({
        id: loc.location_id,
        name: loc.location_name
      })));
    } catch (error) {
      console.error('Failed to fetch locations:', error);
    }
  };

  fetchLocations();
}, []);
```

**Note:** You may need to create a locations endpoint if it doesn't exist. Alternatively, use your existing location dropdown/autocomplete data source.

---

### Step 6: Update API Submission

**File:** `CreateItinerary.tsx`

```typescript
// No changes needed! The via_routes array is already in your route object
// Just ensure you're sending the complete route object to the API

const handleSubmit = async () => {
  const payload = {
    plan: {
      // ... plan details
    },
    routes: routes.map(route => ({
      location_name: route.location_name,
      next_visiting_location: route.next_visiting_location,
      itinerary_route_date: route.itinerary_route_date,
      no_of_days: route.no_of_days,
      no_of_km: route.no_of_km || '',
      direct_to_next_visiting_place: route.direct_to_next_visiting_place,
      via_route: route.via_route || '',
      via_routes: route.via_routes || []  // ← Backend will process this
    })),
    vehicles: vehicles,
    travellers: travellers
  };

  const response = await fetch('http://localhost:4006/api/v1/itineraries', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${yourAuthToken}`
    },
    body: JSON.stringify(payload)
  });

  const result = await response.json();
  console.log('Itinerary created:', result);
};
```

---

## 🎨 Styling Suggestions

**File:** `styles/via-routes.css`

```css
.via-route-selector {
  padding: 1rem;
  background: #f8f9fa;
  border-radius: 0.5rem;
  margin-bottom: 1rem;
}

.via-routes-list {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.via-routes-list .badge {
  font-size: 0.875rem;
  padding: 0.5rem 0.75rem;
  display: inline-flex;
  align-items: center;
}

.via-routes-list .btn-close {
  font-size: 0.7rem;
  opacity: 0.8;
}

.via-routes-list .btn-close:hover {
  opacity: 1;
}

.via-route-selector .form-label {
  font-weight: 600;
  color: #495057;
  margin-bottom: 0.75rem;
}

.via-route-selector .form-label i {
  color: #0d6efd;
  margin-right: 0.5rem;
}
```

---

## 📱 Example UI Flow

```
┌─────────────────────────────────────────────┐
│ Route 1: Day 1                              │
├─────────────────────────────────────────────┤
│ From: Chennai International Airport         │
│ To: Pondicherry                             │
│                                             │
│ ┌─────────────────────────────────────────┐ │
│ │ 📍 Via Route (Optional)                 │ │
│ │                                         │ │
│ │ [x] Mahabalipuram                       │ │
│ │                                         │ │
│ │ ┌──────────────────┐  ┌──────────────┐ │ │
│ │ │ Select stop...  ▼│  │ [+] Add Stop │ │ │
│ │ └──────────────────┘  └──────────────┘ │ │
│ │                                         │ │
│ │ ℹ️  Add cities between source/destination│ │
│ └─────────────────────────────────────────┘ │
│                                             │
│ Date: 2025-12-12                            │
│ Distance: 160 km                            │
└─────────────────────────────────────────────┘
```

---

## ✅ Testing Checklist

- [ ] Can add via route to any route segment
- [ ] Can add multiple via routes to same segment
- [ ] Can remove via routes
- [ ] Via routes display correctly as badges
- [ ] API payload includes `via_routes[]` array
- [ ] Backend saves via routes (check database)
- [ ] Via route locations excluded from source/destination autocomplete
- [ ] Form validation prevents duplicate via routes

---

## 🔍 Debugging

**Check payload before submission:**
```typescript
console.log('Routes payload:', routes);
// Each route should have via_routes array

console.log('API payload:', JSON.stringify(payload, null, 2));
// Verify via_routes[] is included
```

**Verify backend response:**
```typescript
const result = await response.json();
console.log('Created plan:', result.planId);

// Check database
// SELECT * FROM dvi_itinerary_via_route_details WHERE itinerary_plan_ID = ?
```

---

## 🚀 Enhancements (Optional)

### 1. Autocomplete for Via Locations
```typescript
// Use same autocomplete component as source/destination
<LocationAutocomplete
  value={selectedLocation}
  onChange={setSelectedLocation}
  placeholder="Search for intermediate stop..."
  exclude={[route.location_name, route.next_visiting_location]}
/>
```

### 2. Drag & Drop Reordering
```typescript
// Use react-beautiful-dnd or similar
<DragDropContext onDragEnd={handleViaRouteDragEnd}>
  <Droppable droppableId="via-routes">
    {viaRoutes.map((via, index) => (
      <Draggable key={via.id} draggableId={`via-${index}`} index={index}>
        {/* Via route badge */}
      </Draggable>
    ))}
  </Droppable>
</DragDropContext>
```

### 3. Distance Preview
```typescript
// Show estimated distance with via routes
<small className="text-muted">
  Estimated: {calculateDistance(route)} km
  {route.via_routes?.length > 0 && (
    <span> (via {route.via_routes.length} stop{route.via_routes.length > 1 ? 's' : ''})</span>
  )}
</small>
```

### 4. Hotspot Preview
```typescript
// Show available hotspots in via locations
<button onClick={() => previewViaHotspots(via.itinerary_via_location_name)}>
  <i className="bi bi-eye"></i> Preview attractions
</button>
```

---

## 📞 Support

**Backend API:** Already implemented ✅
**Endpoint:** `POST /api/v1/itineraries`
**Documentation:** See Swagger docs at `http://localhost:4006/docs`

**Sample Request:**
```bash
curl -X POST http://localhost:4006/api/v1/itineraries \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "plan": { ... },
    "routes": [{
      "location_name": "Chennai",
      "next_visiting_location": "Pondicherry",
      "via_routes": [
        { "itinerary_via_location_ID": 101, "itinerary_via_location_name": "Mahabalipuram" }
      ]
    }],
    "vehicles": [...],
    "travellers": [...]
  }'
```

---

## 🎉 You're Done!

Once implemented, agents can:
- ✅ Add intermediate stops to any route
- ✅ See via locations as badges
- ✅ Remove via stops easily
- ✅ Submit to backend (auto-saves to database)
- ✅ System automatically includes via location hotspots

**Result:** Richer itineraries with more sightseeing coverage!
