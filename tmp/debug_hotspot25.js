const mysql = require('mysql2/promise');

async function checkHotspot25Timing() {
  const connection = await mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'my@Richlabz123',
    database: 'dvi_travels'
  });

  try {
    console.log('\n=== WHY IS HOTSPOT 25 BEING EXCLUDED? ===\n');

    // Get Route 3 details
    const [route] = await connection.execute(`
      SELECT route_start_time, route_end_time, itinerary_route_date
      FROM dvi_itinerary_route_details
      WHERE itinerary_plan_ID = 2 AND itinerary_route_ID = 429
    `);

    console.log('Route 3 (Pondicherry → Pondicherry Airport):');
    console.log(`  Start: ${route[0].route_start_time}`);
    console.log(`  End: ${route[0].route_end_time}`);
    console.log(`  Date: ${route[0].itinerary_route_date}`);

    const routeDate = new Date(route[0].itinerary_route_date);
    const dayOfWeek = (routeDate.getDay() + 6) % 7; // Tuesday = 1
    console.log(`  Day of week: ${dayOfWeek} (Tuesday)\n`);

    // Check hotspot 25 timing
    const [h25] = await connection.execute(`
      SELECT hotspot_name, hotspot_priority, hotspot_duration
      FROM dvi_hotspot_place
      WHERE hotspot_ID = 25
    `);

    console.log(`Hotspot 25: ${h25[0].hotspot_name}`);
    console.log(`  Priority: ${h25[0].hotspot_priority}`);
    console.log(`  Duration: ${h25[0].hotspot_duration}\n`);

    const [timing] = await connection.execute(`
      SELECT hotspot_start_time, hotspot_end_time, hotspot_closed, hotspot_open_all_time
      FROM dvi_hotspot_timing
      WHERE hotspot_ID = 25 AND hotspot_timing_day = ?
      AND deleted = 0
    `, [dayOfWeek]);

    console.log('Operating hours on Tuesday:');
    timing.forEach((t, idx) => {
      console.log(`  Slot ${idx + 1}: ${t.hotspot_start_time}-${t.hotspot_end_time} (closed=${t.hotspot_closed}, open_all=${t.hotspot_open_all_time})`);
    });

    // Simulate the timeline
    console.log('\n=== TIMELINE SIMULATION ===');
    console.log('After hotspot 18 (09:17 visit ends at 11:17):');
    console.log('  Travel to H25: ~19 min → arrive 11:36');
    console.log('  Visit H25: 30 min → 11:36 to 12:06');
    console.log('  Check: 11:36 >= 05:45? YES');
    console.log('  Check: 12:06 <= 12:30? YES');
    console.log('  ✅ Should fit in morning slot (05:45-12:30)');

    console.log('\nBut if H25 comes after H16:');
    console.log('  After H18 (11:17) → H16 visit ends at 12:44');
    console.log('  Travel to H25: arrive ~13:03');
    console.log('  Visit H25: 13:03 to 13:33');
    console.log('  Check: 13:03 >= 05:45? YES, but...');
    console.log('  Check: 13:33 <= 12:30? NO! (morning slot missed)');
    console.log('  Check: 13:03 >= 16:00? NO! (evening slot not yet)');
    console.log('  ❌ Would miss BOTH slots!');

    // Check hotspot 20
    console.log('\n=== CHECKING HOTSPOT 20 ===');
    const [h20] = await connection.execute(`
      SELECT hotspot_name, hotspot_priority, hotspot_duration
      FROM dvi_hotspot_place
      WHERE hotspot_ID = 20
    `);

    console.log(`Hotspot 20: ${h20[0].hotspot_name}`);
    console.log(`  Priority: ${h20[0].hotspot_priority}`);
    console.log(`  Duration: ${h20[0].hotspot_duration}`);

    const [timing20] = await connection.execute(`
      SELECT hotspot_start_time, hotspot_end_time
      FROM dvi_hotspot_timing
      WHERE hotspot_ID = 20 AND hotspot_timing_day = ? AND deleted = 0
    `, [dayOfWeek]);

    console.log('  Operating hours on Tuesday:');
    timing20.forEach(t => {
      console.log(`    ${t.hotspot_start_time}-${t.hotspot_end_time}`);
    });

    console.log('\n  In Plan 2: Visits at 13:27-14:27');
    console.log('  Check: 13:27 >= 09:30? YES');
    console.log('  Check: 14:27 <= 16:00? YES');
    console.log('  ✅ Fits in operating hours (09:30-16:00)');

  } catch (error) {
    console.error('Error:', error);
  } finally {
    await connection.end();
  }
}

checkHotspot25Timing();
