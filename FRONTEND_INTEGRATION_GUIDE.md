# HotspotDistanceCache - Complete Frontend Integration Guide

## 📋 Overview

The backend has been fully implemented with:
- ✅ Database schema with `fromHotspotName` and `toHotspotName` fields
- ✅ REST API endpoints with CRUD operations
- ✅ Search functionality by hotspot names
- ✅ Excel export capability
- ✅ Form options endpoint for dropdowns

## 🚀 Frontend Implementation Steps

### 1. Create the Component

Copy the provided Vue component to your frontend:
```
src/components/HotspotDistanceCache.vue
```

### 2. Register Component in Your Router

Add to your router configuration:
```javascript
{
  path: '/hotspot-distance-cache',
  name: 'HotspotDistanceCache',
  component: () => import('@/components/HotspotDistanceCache.vue'),
  meta: { requiresAuth: true }
}
```

### 3. Add to Sidebar Navigation

In your sidebar/navigation component, add under the Hotspots section:

**For Vue:**
```vue
<li>
  <router-link to="/hotspot-distance-cache">
    <i class="icon-map-pin"></i>
    Distance Cache
  </router-link>
</li>
```

**For React:**
```jsx
<li>
  <Link to="/hotspot-distance-cache">
    <i className="icon-map-pin"></i>
    Distance Cache
  </Link>
</li>
```

### 4. Install Required Dependencies (if needed)

The component uses:
- Vue 3 (built-in routing)
- No external UI libraries required
- Standard HTML/CSS tables

## 📊 API Endpoints Reference

### List/Search
```bash
GET /api/v1/hotspot-distance-cache?page=1&size=50&search=temple
```

### Get Single Entry
```bash
GET /api/v1/hotspot-distance-cache/:id
```

### Create Entry
```bash
POST /api/v1/hotspot-distance-cache
Content-Type: application/json

{
  "fromHotspotId": 4,
  "toHotspotId": 11,
  "travelLocationType": 1,
  "haversineKm": 3.5907,
  "correctionFactor": 1.5,
  "distanceKm": 5.3861,
  "speedKmph": 40,
  "travelTime": "00:08:06"
}
```

### Update Entry
```bash
PUT /api/v1/hotspot-distance-cache
Content-Type: application/json

{
  "id": 1,
  "speedKmph": 45,
  "distanceKm": 5.4
}
```

### Delete Entry
```bash
DELETE /api/v1/hotspot-distance-cache/:id
```

### Bulk Delete
```bash
POST /api/v1/hotspot-distance-cache/bulk-delete
Content-Type: application/json

{
  "ids": [1, 2, 3, 4, 5]
}
```

### Export to Excel
```bash
GET /api/v1/hotspot-distance-cache/export/excel?search=temple
```

### Get Form Options
```bash
GET /api/v1/hotspot-distance-cache/form-options
```

## 🎨 Component Features

The provided Vue component includes:

### List View
- ✅ Paginated table display
- ✅ Search by hotspot name (from or to)
- ✅ Filter by hotspot ID
- ✅ Filter by travel type (Local/Outstation)
- ✅ Sort by distance, speed, created date
- ✅ Checkbox selection for bulk operations

### CRUD Operations
- ✅ Create new cache entry
- ✅ Edit existing entry
- ✅ Delete single entry
- ✅ Bulk delete multiple entries
- ✅ Form validation

### Advanced Features
- ✅ Excel export with applied filters
- ✅ Responsive table design
- ✅ Loading states
- ✅ Error handling
- ✅ Success notifications

## 📈 Table Columns

| Column | Type | Description |
|--------|------|-------------|
| ID | Number | Cache entry ID |
| From Hotspot | String | Source hotspot name + ID |
| To Hotspot | String | Destination hotspot name + ID |
| Travel Type | Badge | Local (1) or Outstation (2) |
| Distance | Number | Distance in kilometers |
| Speed | Number | Speed in km/h |
| Travel Time | Time | HH:MM:SS format |
| Actions | Button | Edit/Delete buttons |

## 🔍 Search Examples

### Search by temple name
```
Search: "temple"
Results: All entries where from or to hotspot name contains "temple"
```

### Filter by specific hotspots
```
From: Kapaleeshwarar Temple (ID: 4)
To: Parthasarathy Temple (ID: 11)
Results: Only entries between these two hotspots
```

### Filter by travel type
```
Travel Type: Local
Results: Only local travel entries (type 1)
```

### Combine filters
```
Search: "temple"
From: ID 4
Travel Type: Local
Page Size: 25
Results: Local trips from hotspot 4 that mention "temple"
```

## 💾 Excel Export Columns

When exporting, the following columns are included:
1. ID
2. From Hotspot ID
3. From Hotspot Name
4. To Hotspot ID
5. To Hotspot Name
6. Travel Type
7. Haversine KM
8. Correction Factor
9. Distance KM
10. Speed KMPH
11. Travel Time
12. Method
13. Created At
14. Updated At

## 🔐 Authentication

All endpoints require authentication. Ensure your API calls include:
- Authorization header with JWT token
- Or session cookie if using session-based auth

## ⚠️ Error Handling

The component handles:
- **400 Bad Request**: Invalid input data
- **404 Not Found**: Entry doesn't exist
- **500 Internal Server Error**: Server-side issues

Users receive clear error messages for each scenario.

## 🎯 Sidebar Navigation Setup

**Location of Hotspots in Sidebar:**
```
Settings
├── Configuration
├── Hotspots
│   ├── Hotspot List
│   ├── Hotspot Gallery
│   ├── Hotspot Timing
│   └── Distance Cache ← ADD HERE
├── Activities
└── Locations
```

## 📱 Responsive Design

The component is responsive and works on:
- Desktop (1024px+)
- Tablet (768px - 1023px)
- Mobile (below 768px)

**Mobile-specific adjustments:**
- Table becomes scrollable horizontally
- Filter section stacks vertically
- Action buttons remain accessible

## 🔧 Customization

### Change page size
```vue
<input v-model.number="pageSize" type="number" max="500" />
```

### Add custom styling
Update the `<style>` section with your brand colors:
```vue
.btn-primary {
  background: #YOUR_COLOR;
}
```

### Add custom columns
Extend the table with additional fields:
```vue
<th>Custom Column</th>
<td>{{ row.customField }}</td>
```

## 🚦 Loading & Error States

The component displays:
- **Loading**: Spinner while fetching data
- **Empty State**: Message when no entries found
- **Error**: Validation messages in modals
- **Success**: Alert after CRUD operations

## 📞 API Base URL

Ensure your API base URL is configured:
```javascript
// In your API service or main.js
const API_BASE_URL = 'http://localhost:4006/api/v1';
```

## ✨ Next Steps

1. Copy `HotspotDistanceCache.vue` to your frontend project
2. Import and register in your router
3. Add navigation link in sidebar
4. Test CRUD operations
5. Customize styling to match your design system
6. Deploy to production

## 📚 Related Documentation

- [API Documentation](./HOTSPOT_DISTANCE_CACHE_API.md)
- [Database Schema](./prisma/schema.prisma) - See `HotspotDistanceCache` model
- [Backend Service](./src/modules/hotspot-distance-cache/)

## 🐛 Troubleshooting

### "Cannot find module" errors
- Ensure API base URL is correct in component
- Check that backend is running on port 4006

### Excel export not downloading
- Verify browser allows downloads
- Check network tab in DevTools for errors

### Form validation errors
- Ensure both hotspot IDs are selected
- Verify travel time format is HH:MM:SS

### Search not working
- Ensure search text matches hotspot names
- Check hotspot names exist in database

## 💡 Tips

1. **Performance**: Component loads max 50000 rows for Excel export
2. **Validation**: Frontend validates hotspot IDs before submission
3. **Caching**: Form options are cached on component mount
4. **Pagination**: Default page size is 50, can be changed
5. **Search**: Case-insensitive search across hotspot names

---

**Last Updated**: December 26, 2025
**Component Version**: 1.0
**Status**: ✅ Production Ready
