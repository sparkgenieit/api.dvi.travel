const mysql = require('mysql2/promise');

async function compareRoute3Detail() {
  const connection = await mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'my@Richlabz123',
    database: 'dvi_travels'
  });

  try {
    console.log('\n=== ROUTE 3 DETAILED COMPARISON ===\n');

    // Get Plan 2 Route 3
    const [plan2Rows] = await connection.execute(`
      SELECT 
        hotspot_order,
        item_type,
        hotspot_ID,
        hotspot_start_time,
        hotspot_end_time
      FROM dvi_itinerary_route_hotspot_details
      WHERE itinerary_plan_ID = 2
      AND itinerary_route_ID = 429
      AND deleted = 0
      ORDER BY route_hotspot_ID
    `);

    // Get Plan 5 Route 3
    const [plan5Rows] = await connection.execute(`
      SELECT 
        hotspot_order,
        item_type,
        hotspot_ID,
        hotspot_start_time,
        hotspot_end_time
      FROM dvi_itinerary_route_hotspot_details
      WHERE itinerary_plan_ID = 5
      AND itinerary_route_ID = 574
      AND deleted = 0
      ORDER BY route_hotspot_ID
    `);

    console.log('Plan 2 (PHP - CORRECT):');
    plan2Rows.forEach(r => {
      const types = {1: 'Refresh', 3: 'Travel', 4: 'Visit', 5: 'ToHotel', 6: 'AtHotel', 7: 'Return'};
      console.log(`  Order ${r.hotspot_order}: ${types[r.item_type]}${r.hotspot_ID ? ` (H${r.hotspot_ID})` : ''} ${r.hotspot_start_time}-${r.hotspot_end_time}`);
    });

    console.log('\nPlan 5 (NestJS - TESTING):');
    plan5Rows.forEach(r => {
      const types = {1: 'Refresh', 3: 'Travel', 4: 'Visit', 5: 'ToHotel', 6: 'AtHotel', 7: 'Return'};
      console.log(`  Order ${r.hotspot_order}: ${types[r.item_type]}${r.hotspot_ID ? ` (H${r.hotspot_ID})` : ''} ${r.hotspot_start_time}-${r.hotspot_end_time}`);
    });

    // Check operating hours for missing hotspots
    const [hotspot25] = await connection.execute(`
      SELECT 
        hotspot_timing_day,
        hotspot_start_time,
        hotspot_end_time
      FROM dvi_hotspot_timing
      WHERE hotspot_ID = 25
      AND deleted = 0
      ORDER BY hotspot_timing_day
    `);

    console.log('\n=== HOTSPOT 25 OPERATING HOURS ===');
    hotspot25.forEach(t => {
      const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
      console.log(`  ${days[t.hotspot_timing_day]}: ${t.hotspot_start_time} - ${t.hotspot_end_time}`);
    });

    // Check hotspot 20
    const [hotspot20] = await connection.execute(`
      SELECT 
        hotspot_timing_day,
        hotspot_start_time,
        hotspot_end_time
      FROM dvi_hotspot_timing
      WHERE hotspot_ID = 20
      AND deleted = 0
      ORDER BY hotspot_timing_day
    `);

    console.log('\n=== HOTSPOT 20 OPERATING HOURS ===');
    hotspot20.forEach(t => {
      const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
      console.log(`  ${days[t.hotspot_timing_day]}: ${t.hotspot_start_time} - ${t.hotspot_end_time}`);
    });

  } catch (error) {
    console.error('Error:', error);
  } finally {
    await connection.end();
  }
}

compareRoute3Detail();
