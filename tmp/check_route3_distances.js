const mysql = require('mysql2/promise');

function calculateDistance(lat1, lon1, lat2, lon2) {
  const R = 6371; // Earth's radius in km
  const dLat = (lat2 - lat1) * Math.PI / 180;
  const dLon = (lon2 - lon1) * Math.PI / 180;
  const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
    Math.sin(dLon / 2) * Math.sin(dLon / 2);
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  return R * c;
}

async function checkRoute3Distances() {
  const conn = await mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'my@Richlabz123',
    database: 'dvi_travels'
  });

  console.log('\n=== ROUTE 3 STARTING POINT ===\n');

  // Get Route 3 details for Plan 2
  const [route] = await conn.execute(`
    SELECT r.*, 
           h.hotel_name, 
           h.hotel_latitude, 
           h.hotel_longitude
    FROM dvi_itinerary_route_details r
    LEFT JOIN dvi_hotel_place h ON r.hotel_ID = h.hotel_ID
    WHERE r.route_ID = 429
  `);

  const startPoint = {
    lat: parseFloat(route[0].hotel_latitude),
    lng: parseFloat(route[0].hotel_longitude),
    name: route[0].hotel_name
  };

  console.log(`Starting from: ${startPoint.name}`);
  console.log(`Coordinates: ${startPoint.lat}, ${startPoint.lng}`);
  console.log('');

  // Get hotspot details
  const [hotspots] = await conn.execute(`
    SELECT hotspot_ID, hotspot_name, hotspot_location, hotspot_priority, 
           hotspot_latitude, hotspot_longitude
    FROM dvi_hotspot_place
    WHERE hotspot_ID IN (16, 18, 25)
    ORDER BY hotspot_ID
  `);

  console.log('=== DISTANCES FROM STARTING POINT ===\n');

  const results = hotspots.map(h => {
    const distance = calculateDistance(
      startPoint.lat,
      startPoint.lng,
      parseFloat(h.hotspot_latitude),
      parseFloat(h.hotspot_longitude)
    );

    return {
      ID: h.hotspot_ID,
      Name: h.hotspot_name,
      Priority: h.hotspot_priority,
      Location: h.hotspot_location,
      Distance: `${distance.toFixed(2)} km`
    };
  });

  console.table(results);

  await conn.end();
}

checkRoute3Distances().catch(console.error);
