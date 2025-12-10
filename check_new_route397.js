const mysql = require('mysql2/promise');

async function checkNewRoute397() {
  const connection = await mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'my@Richlabz123',
    database: 'dvi_travels'
  });

  console.log('\n=== ROUTE 397 DETAILS ===\n');

  // Get route details
  const [routes] = await connection.execute(`
    SELECT 
      route_id, 
      CAST(route_start_time AS CHAR) as route_start_time,
      CAST(route_end_time AS CHAR) as route_end_time,
      start_from,
      end_destination
    FROM dvi_itinerary_route_details 
    WHERE route_id = 397
  `);

  console.log('Route Details:');
  console.log(routes[0]);

  // Get timeline
  const [timeline] = await connection.execute(`
    SELECT 
      CAST(start_time AS CHAR) as start_time,
      CAST(end_time AS CHAR) as end_time,
      place_name,
      hotspot_id
    FROM dvi_itinerary_route_hotspot_details 
    WHERE route_id = 397
    ORDER BY start_time
  `);

  console.log('\n=== TIMELINE ===');
  timeline.forEach(row => {
    console.log(`${row.start_time} - ${row.end_time}: ${row.place_name} (hotspot: ${row.hotspot_id})`);
  });

  // Get hotspot IDs only
  const [hotspots] = await connection.execute(`
    SELECT hotspot_id 
    FROM dvi_itinerary_route_hotspot_details 
    WHERE route_id = 397 AND hotspot_id IS NOT NULL
    ORDER BY start_time
  `);

  console.log('\n=== HOTSPOT IDs ONLY ===');
  console.log(hotspots.map(h => h.hotspot_id));

  await connection.end();
}

checkNewRoute397().catch(console.error);
