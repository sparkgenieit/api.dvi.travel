const mysql = require('mysql2/promise');

async function checkRoutes() {
  const connection = await mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '1234',
    database: 'dvi_travels'
  });

  console.log('Plan 5 Routes 352-354:');
  console.log('======================');

  const [routes] = await connection.execute(`
    SELECT 
      itinerary_route_ID,
      source_code,
      source_name,
      destination_code,
      destination_name,
      route_start_time,
      route_end_time,
      direct_to_next_visiting_place
    FROM dvi_itinerary_route
    WHERE itinerary_route_ID IN (352, 353, 354)
    ORDER BY itinerary_route_ID
  `);

  routes.forEach(route => {
    console.log(`\nRoute ${route.itinerary_route_ID}:`);
    console.log(`  ${route.source_name} → ${route.destination_name}`);
    console.log(`  Start: ${route.route_start_time}`);
    console.log(`  End: ${route.route_end_time}`);
    console.log(`  Direct: ${route.direct_to_next_visiting_place}`);
  });

  await connection.end();
}

checkRoutes().catch(console.error);
