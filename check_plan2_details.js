const mysql = require('mysql2/promise');

async function checkPlan2() {
  const connection = await mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '1234',
    database: 'dvi_travels'
  });

  console.log('Plan 2 Details:');
  console.log('===============\n');

  const [plan] = await connection.execute(`
    SELECT 
      itinerary_plan_id,
      arrival_point,
      departure_point,
      trip_start_date,
      trip_end_date,
      arrival_type,
      departure_type
    FROM dvi_itinerary_plan
    WHERE itinerary_plan_id = 2
  `);

  console.log('Plan:', plan[0]);

  console.log('\nRoutes:');
  const [routes] = await connection.execute(`
    SELECT 
      itinerary_route_ID,
      location_name,
      next_visiting_location,
      route_start_time,
      route_end_time,
      direct_to_next_visiting_place
    FROM dvi_itinerary_route
    WHERE itinerary_plan_ID = 2
    ORDER BY itinerary_route_ID
  `);

  routes.forEach((r, i) => {
    console.log(`\nRoute ${i+1} (ID ${r.itinerary_route_ID}):`);
    console.log(`  ${r.location_name} → ${r.next_visiting_location}`);
    console.log(`  Time: ${r.route_start_time} - ${r.route_end_time}`);
    console.log(`  Direct: ${r.direct_to_next_visiting_place}`);
  });

  await connection.end();
}

checkPlan2().catch(console.error);
