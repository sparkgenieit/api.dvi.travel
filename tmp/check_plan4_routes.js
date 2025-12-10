const mysql = require('mysql2/promise');

async function main() {
  const connection = await mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'my@Richlabz123',
    database: 'dvi_travels',
  });

  try {
    // Get plan 4 routes with details
    const [routes] = await connection.query(`
      SELECT 
        dr.id,
        dr.from_location,
        dr.to_location,
        dr.distance,
        dr.duration_in_minutes,
        COUNT(hs.id) as hotspot_count
      FROM dvi_itinerary_routes dr
      LEFT JOIN dvi_hotspot_timing hs ON 
        hs.route_id = dr.id AND 
        hs.day = 1
      WHERE dr.itinerary_id = 4
      GROUP BY dr.id
      ORDER BY dr.id
    `);

    console.log('Plan 4 Routes:');
    console.log('─'.repeat(100));
    routes.forEach(r => {
      console.log(
        `Route ${r.id}: ${r.from_location} → ${r.to_location} ` +
        `| Distance: ${r.distance}km | Duration: ${r.duration_in_minutes}min | ` +
        `Hotspots available: ${r.hotspot_count}`
      );
    });

    // Get current itinerary details
    const [itinerary] = await connection.query(`
      SELECT 
        id,
        start_date,
        end_date,
        total_days,
        start_time,
        end_time
      FROM dvi_itineraries
      WHERE id = 4
    `);

    console.log('\nPlan 4 Itinerary Details:');
    console.log('─'.repeat(100));
    const itin = itinerary[0];
    console.log(`Start: ${itin.start_date} at ${itin.start_time}`);
    console.log(`End: ${itin.end_date} at ${itin.end_time}`);
    console.log(`Total Days: ${itin.total_days}`);

    // Check route_hotspot_details current state
    const [details] = await connection.query(`
      SELECT 
        route_id,
        COUNT(*) as total_items,
        SUM(CASE WHEN item_type IN (3, 4) THEN 1 ELSE 0 END) as hotspot_count,
        SUM(CASE WHEN item_type = 1 THEN 1 ELSE 0 END) as travel_count
      FROM dvi_itinerary_route_hotspot_details
      WHERE itinerary_plan_id = 4
      GROUP BY route_id
      ORDER BY route_id
    `);

    console.log('\nCurrent Route Details (Plan 4):');
    console.log('─'.repeat(100));
    details.forEach(d => {
      console.log(
        `Route ${d.route_id}: Total=${d.total_items}, Travel=${d.travel_count}, Hotspots=${d.hotspot_count}`
      );
    });

  } finally {
    await connection.end();
  }
}

main().catch(console.error);
