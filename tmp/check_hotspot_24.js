const mysql = require('mysql2/promise');

async function main() {
  const conn = await mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'my@Richlabz123',
    database: 'dvi_travels'
  });

  console.log('\n=== CHECKING HOTSPOT 24 ===\n');
  
  // Get hotspot 24 details
  const [hotspot24] = await conn.query(`
    SELECT hotspot_ID, hotspot_name, hotspot_location, hotspot_priority,
           hotspot_opening_time, hotspot_closing_time, hotspot_visit_duration
    FROM dvi_hotspots
    WHERE hotspot_ID = 24
  `);
  
  console.log('Hotspot 24:', hotspot24[0]);
  
  // Check if it was selected in Route 3 of Plan 2
  const [plan2] = await conn.query(`
    SELECT COUNT(*) as count
    FROM dvi_itinerary_route_hotspot_details
    WHERE itinerary_plan_ID = 2
      AND itinerary_route_ID = 429
      AND item_type = 4
      AND hotspot_ID = 24
  `);
  
  console.log('\nPlan 2 Route 3 - Hotspot 24 selected?', plan2[0].count > 0);
  
  // Check timing of when it's visited in Plan 5
  const [plan5] = await conn.query(`
    SELECT hotspot_order, arrival_time, departure_time
    FROM dvi_itinerary_route_hotspot_details
    WHERE itinerary_plan_ID = 5
      AND itinerary_route_ID = 505
      AND item_type = 4
      AND hotspot_ID = 24
  `);
  
  if (plan5.length > 0) {
    console.log('\nPlan 5 Route 3 - Hotspot 24:');
    console.log('  Arrival Time:', plan5[0].arrival_time);
    console.log('  Departure Time:', plan5[0].departure_time);
    console.log('  Order:', plan5[0].hotspot_order);
  }
  
  // Check hotspots 16, 20, 23, 24 details
  console.log('\n=== COMPARING HOTSPOTS 16, 20, 23, 24 ===\n');
  
  const [hotspots] = await conn.query(`
    SELECT hotspot_ID, hotspot_name, hotspot_location, hotspot_priority,
           hotspot_opening_time, hotspot_closing_time, hotspot_visit_duration
    FROM dvi_hotspots
    WHERE hotspot_ID IN (16, 20, 23, 24)
    ORDER BY hotspot_ID
  `);
  
  for (const hs of hotspots) {
    console.log(`Hotspot ${hs.hotspot_ID}:`, {
      Name: hs.hotspot_name,
      Location: hs.hotspot_location,
      Priority: hs.hotspot_priority,
      Opening: hs.hotspot_opening_time,
      Closing: hs.hotspot_closing_time,
      Duration: hs.hotspot_visit_duration,
    });
  }

  // Check order they were selected in both plans
  console.log('\n=== ORDER COMPARISON ===\n');
  
  const [plan2Order] = await conn.query(`
    SELECT hotspot_order, hotspot_ID, arrival_time
    FROM dvi_itinerary_route_hotspot_details
    WHERE itinerary_plan_ID = 2
      AND itinerary_route_ID = 429
      AND item_type = 4
      AND hotspot_ID IN (16, 20, 23)
    ORDER BY hotspot_order
  `);
  
  console.log('Plan 2 Order:');
  plan2Order.forEach(row => {
    console.log(`  Order ${row.hotspot_order}: Hotspot ${row.hotspot_ID} at ${row.arrival_time}`);
  });
  
  const [plan5Order] = await conn.query(`
    SELECT hotspot_order, hotspot_ID, arrival_time
    FROM dvi_itinerary_route_hotspot_details
    WHERE itinerary_plan_ID = 5
      AND itinerary_route_ID = 505
      AND item_type = 4
      AND hotspot_ID IN (16, 20, 23, 24)
    ORDER BY hotspot_order
  `);
  
  console.log('\nPlan 5 Order:');
  plan5Order.forEach(row => {
    console.log(`  Order ${row.hotspot_order}: Hotspot ${row.hotspot_ID} at ${row.arrival_time}`);
  });

  await conn.end();
}

main().catch(console.error);

