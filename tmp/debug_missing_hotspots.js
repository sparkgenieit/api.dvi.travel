const mysql = require('mysql2/promise');

async function debugMissingHotspots() {
  console.log('=== DEBUGGING MISSING HOTSPOTS 25 & 20 ===\n');
  
  const conn = await mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'my@Richlabz123',
    database: 'dvi_travels'
  });

  // Get Route 3 info
  const [routes] = await conn.execute(`
    SELECT * FROM dvi_itinerary_routes
    WHERE itinerary_plan_ID = 2 
    AND itinerary_route_from LIKE '%Pondicherry%'
    AND itinerary_route_to LIKE '%Airport%'
  `);
  
  const route = routes[0];
  console.log('Route 3 Info:');
  console.log(`  Date: ${route.itinerary_route_date}`);
  console.log(`  Start time: ${route.itinerary_route_start_time}`);
  console.log(`  End time: ${route.itinerary_route_end_time}`);
  console.log(`  From: ${route.itinerary_route_from}`);
  console.log(`  To: ${route.itinerary_route_to}\n`);

  // Get day of week for the route date
  const routeDate = new Date(route.itinerary_route_date);
  const phpDow = (routeDate.getDay() + 6) % 7; // Monday=0, Sunday=6
  console.log(`Day of week (PHP format): ${phpDow} (${['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'][phpDow]})\n`);

  // Check hotspot 25 timing on this day
  console.log('Hotspot 25 (Manakula Vinayagar Temple):');
  const [h25timing] = await conn.execute(`
    SELECT * FROM dvi_hotspot_timing
    WHERE hotspot_ID = 25 AND hotspot_timing_day = ?
  `, [phpDow]);
  console.log(h25timing);
  console.log();

  // Check hotspot 20 timing
  console.log('Hotspot 20 (Chunnambar Boathouse):');
  const [h20timing] = await conn.execute(`
    SELECT * FROM dvi_hotspot_timing
    WHERE hotspot_ID = 20 AND hotspot_timing_day = ?
  `, [phpDow]);
  console.log(h20timing);
  console.log();

  // Check hotspot locations
  console.log('Hotspot locations:');
  const [h25] = await conn.execute('SELECT hotspot_ID, hotspot_name, hotspot_location, hotspot_priority FROM dvi_hotspot_place WHERE hotspot_ID = 25');
  const [h20] = await conn.execute('SELECT hotspot_ID, hotspot_name, hotspot_location, hotspot_priority FROM dvi_hotspot_place WHERE hotspot_ID = 20');
  console.log('H25:', h25[0]);
  console.log('H20:', h20[0]);
  console.log();

  // Check if hotspot 25 end time (12:30) is before route end time
  const routeEndMinutes = parseInt(route.itinerary_route_end_time.split(':')[0]) * 60 + parseInt(route.itinerary_route_end_time.split(':')[1]);
  console.log(`Route end time in minutes: ${routeEndMinutes} (${route.itinerary_route_end_time})`);
  
  for (const t of h25timing) {
    if (t.hotspot_end_time) {
      const hsEndMinutes = parseInt(t.hotspot_end_time.split(':')[0]) * 60 + parseInt(t.hotspot_end_time.split(':')[1]);
      console.log(`H25 closes at ${t.hotspot_end_time} (${hsEndMinutes} minutes) - ${hsEndMinutes < routeEndMinutes ? 'BEFORE route end' : 'AFTER route end'}`);
    }
  }
  
  for (const t of h20timing) {
    if (t.hotspot_end_time) {
      const hsEndMinutes = parseInt(t.hotspot_end_time.split(':')[0]) * 60 + parseInt(t.hotspot_end_time.split(':')[1]);
      console.log(`H20 closes at ${t.hotspot_end_time} (${hsEndMinutes} minutes) - ${hsEndMinutes < routeEndMinutes ? 'BEFORE route end' : 'AFTER route end'}`);
    }
  }
  
  await conn.end();
}

debugMissingHotspots().catch(console.error);
