# HotspotDistanceCache CRUD API Documentation

## Base URL
```
http://localhost:4006/api/v1/hotspot-distance-cache
```

## Endpoints

### 1. List/Search Cache Entries
**GET** `/hotspot-distance-cache`

**Query Parameters:**
```typescript
page?: number          // Default: 1
size?: number          // Default: 50, Max: 10000
search?: string        // Search by hotspot names (from or to)
sortBy?: string        // Default: 'createdAt' | Options: 'id', 'distanceKm', 'speedKmph', 'createdAt'
sortOrder?: string     // Default: 'desc' | Options: 'asc', 'desc'
fromHotspotId?: number // Filter by from hotspot
toHotspotId?: number   // Filter by to hotspot
travelLocationType?: number // Filter by type (1=Local, 2=Outstation)
```

**Example Request:**
```bash
GET /hotspot-distance-cache?page=1&size=50&search=temple&sortOrder=asc
```

**Response:**
```json
{
  "total": 40,
  "page": 1,
  "size": 50,
  "pages": 1,
  "rows": [
    {
      "id": 1,
      "fromHotspotId": 4,
      "fromHotspotName": "Kapaleeshwarar Temple",
      "toHotspotId": 11,
      "toHotspotName": "Parthasarathy Temple",
      "travelLocationType": 1,
      "haversineKm": 3.5907,
      "correctionFactor": 1.5,
      "distanceKm": 5.3861,
      "speedKmph": 40,
      "travelTime": "00:08:06",
      "method": "HAVERSINE",
      "createdAt": "2025-12-26T12:30:00Z",
      "updatedAt": "2025-12-26T12:30:00Z"
    }
  ]
}
```

---

### 2. Get Form Options (Dropdowns)
**GET** `/hotspot-distance-cache/form-options`

**Response:**
```json
{
  "hotspots": [
    { "id": 4, "name": "Kapaleeshwarar Temple" },
    { "id": 5, "name": "Marina Beach" },
    { "id": 11, "name": "Parthasarathy Temple" }
  ],
  "travelTypes": [
    { "id": 1, "name": "Local" },
    { "id": 2, "name": "Outstation" }
  ]
}
```

---

### 3. Get Single Cache Entry
**GET** `/hotspot-distance-cache/:id`

**Example Request:**
```bash
GET /hotspot-distance-cache/1
```

**Response:**
```json
{
  "id": 1,
  "fromHotspotId": 4,
  "fromHotspotName": "Kapaleeshwarar Temple",
  "toHotspotId": 11,
  "toHotspotName": "Parthasarathy Temple",
  "travelLocationType": 1,
  "haversineKm": 3.5907,
  "correctionFactor": 1.5,
  "distanceKm": 5.3861,
  "speedKmph": 40,
  "travelTime": "00:08:06",
  "method": "HAVERSINE",
  "createdAt": "2025-12-26T12:30:00Z",
  "updatedAt": "2025-12-26T12:30:00Z"
}
```

---

### 4. Create New Cache Entry
**POST** `/hotspot-distance-cache`

**Request Body:**
```json
{
  "fromHotspotId": 4,
  "toHotspotId": 5,
  "travelLocationType": 1,
  "haversineKm": 20.5,
  "correctionFactor": 1.5,
  "distanceKm": 30.75,
  "speedKmph": 40,
  "travelTime": "00:46:07",
  "method": "HAVERSINE"
}
```

**Response:** `201 Created` with created entry

---

### 5. Update Cache Entry
**PUT** `/hotspot-distance-cache`

**Request Body:**
```json
{
  "id": 1,
  "haversineKm": 3.6,
  "distanceKm": 5.4,
  "speedKmph": 45,
  "travelTime": "00:07:12"
}
```

**Response:** `200 OK` with updated entry

---

### 6. Delete Cache Entry
**DELETE** `/hotspot-distance-cache/:id`

**Example Request:**
```bash
DELETE /hotspot-distance-cache/1
```

**Response:**
```json
{
  "ok": true,
  "message": "Cache entry deleted successfully"
}
```

---

### 7. Bulk Delete Cache Entries
**POST** `/hotspot-distance-cache/bulk-delete`

**Request Body:**
```json
{
  "ids": [1, 2, 3, 4, 5]
}
```

**Response:**
```json
{
  "ok": true,
  "deleted": 5,
  "message": "5 cache entries deleted"
}
```

---

### 8. Export to Excel
**GET** `/hotspot-distance-cache/export/excel`

**Query Parameters:**
```typescript
search?: string        // Optional search filter
fromHotspotId?: number // Optional from hotspot filter
toHotspotId?: number   // Optional to hotspot filter
travelLocationType?: number // Optional travel type filter
```

**Example Request:**
```bash
GET /hotspot-distance-cache/export/excel?search=temple
```

**Response:**
```json
{
  "ok": true,
  "fileName": "hotspot-distance-cache-2025-12-26.xlsx",
  "data": "base64-encoded-excel-file"
}
```

**How to download in Frontend:**
```javascript
const response = await fetch('/api/v1/hotspot-distance-cache/export/excel');
const json = await response.json();

if (json.ok) {
  const binaryString = atob(json.data);
  const bytes = new Uint8Array(binaryString.length);
  for (let i = 0; i < binaryString.length; i++) {
    bytes[i] = binaryString.charCodeAt(i);
  }
  
  const blob = new Blob([bytes], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
  const url = URL.createObjectURL(blob);
  const link = document.createElement('a');
  link.href = url;
  link.download = json.fileName;
  link.click();
}
```

---

## Search Examples

### Search by hotspot name
```bash
GET /hotspot-distance-cache?search=temple
GET /hotspot-distance-cache?search=beach
```

### Filter by specific hotspots
```bash
GET /hotspot-distance-cache?fromHotspotId=4&toHotspotId=5
```

### Filter by travel type
```bash
GET /hotspot-distance-cache?travelLocationType=1  # Local only
GET /hotspot-distance-cache?travelLocationType=2  # Outstation only
```

### Combine filters
```bash
GET /hotspot-distance-cache?search=temple&travelLocationType=1&page=1&size=25
```

---

## Excel Export Columns
1. ID
2. From Hotspot ID
3. From Hotspot Name
4. To Hotspot ID
5. To Hotspot Name
6. Travel Type (Local/Outstation)
7. Haversine KM
8. Correction Factor
9. Distance KM
10. Speed KMPH
11. Travel Time
12. Method
13. Created At
14. Updated At

---

## Error Responses

### 400 Bad Request
```json
{
  "statusCode": 400,
  "message": "fromHotspotId and toHotspotId are required",
  "error": "Bad Request"
}
```

### 404 Not Found
```json
{
  "statusCode": 404,
  "message": "Cache entry 999 not found",
  "error": "Not Found"
}
```

### 500 Internal Server Error
```json
{
  "statusCode": 500,
  "message": "Internal server error"
}
```

---

## Frontend Integration Notes

1. **For List View:** Use `/hotspot-distance-cache` with pagination
2. **For Form Options:** Call `/hotspot-distance-cache/form-options` on component mount
3. **For Search:** Use `search` query parameter for free-text search
4. **For Export:** Handle base64 decoding on client side for Excel download
5. **For Create/Update:** Validate hotspot IDs exist in the form-options first
