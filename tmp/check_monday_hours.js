const mysql = require('mysql2/promise');

async function checkMondayHours() {
  const connection = await mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'my@Richlabz123',
    database: 'dvi_travels'
  });

  console.log('=== Checking Hotspot Operating Hours for MONDAY (day 0) ===\n');

  // Check hotspots 25 and 20 on Monday
  const [hotspots] = await connection.execute(`
    SELECT 
      h.hotspot_ID,
      h.hotspot_title,
      h.hotspot_priority,
      ht.hotspot_timing_day,
      ht.hotspot_start_time,
      ht.hotspot_end_time,
      ht.hotspot_closed
    FROM dvi_hotspot h
    LEFT JOIN dvi_hotspot_timing ht ON h.hotspot_ID = ht.hotspot_ID 
      AND ht.hotspot_timing_day = 0
      AND ht.deleted = 0
    WHERE h.hotspot_ID IN (25, 20)
      AND h.deleted = 0
    ORDER BY h.hotspot_ID
  `);

  console.log('Hotspots 25 and 20 on MONDAY (day 0):');
  hotspots.forEach(h => {
    console.log(`\nHotspot ${h.hotspot_ID} - ${h.hotspot_title}`);
    console.log(`  Priority: ${h.hotspot_priority}`);
    if (h.hotspot_timing_day !== null) {
      console.log(`  Day: ${h.hotspot_timing_day} (Monday)`);
      console.log(`  Hours: ${h.hotspot_start_time || 'null'} - ${h.hotspot_end_time || 'null'}`);
      console.log(`  Closed: ${h.hotspot_closed}`);
    } else {
      console.log(`  ❌ NO OPERATING HOURS FOR MONDAY!`);
    }
  });

  // Also check Tuesday for comparison
  const [tuesdayHotspots] = await connection.execute(`
    SELECT 
      h.hotspot_ID,
      h.hotspot_title,
      ht.hotspot_timing_day,
      ht.hotspot_start_time,
      ht.hotspot_end_time,
      ht.hotspot_closed
    FROM dvi_hotspot h
    LEFT JOIN dvi_hotspot_timing ht ON h.hotspot_ID = ht.hotspot_ID 
      AND ht.hotspot_timing_day = 1
      AND ht.deleted = 0
    WHERE h.hotspot_ID IN (25, 20)
      AND h.deleted = 0
    ORDER BY h.hotspot_ID
  `);

  console.log('\n\n=== For comparison, TUESDAY (day 1) ===');
  tuesdayHotspots.forEach(h => {
    console.log(`\nHotspot ${h.hotspot_ID} - ${h.hotspot_title}`);
    if (h.hotspot_timing_day !== null) {
      console.log(`  Day: ${h.hotspot_timing_day} (Tuesday)`);
      console.log(`  Hours: ${h.hotspot_start_time || 'null'} - ${h.hotspot_end_time || 'null'}`);
      console.log(`  Closed: ${h.hotspot_closed}`);
    } else {
      console.log(`  ❌ NO OPERATING HOURS FOR TUESDAY!`);
    }
  });

  // Check Plan 2 Route 3 actual date
  const [route3] = await connection.execute(`
    SELECT 
      route_start_date_time,
      route_end_date_time,
      DAYOFWEEK(route_start_date_time) as day_of_week,
      (DAYOFWEEK(route_start_date_time) - 2 + 7) % 7 as php_day
    FROM dvi_itinerary_route
    WHERE plan_ID = 2 AND route_sequence = 3
  `);

  console.log('\n\n=== Plan 2 Route 3 Date ===');
  if (route3.length > 0) {
    const r = route3[0];
    console.log(`Start: ${r.route_start_date_time}`);
    console.log(`End: ${r.route_end_date_time}`);
    console.log(`MySQL DAYOFWEEK: ${r.day_of_week} (1=Sunday, 2=Monday...)`);
    console.log(`PHP day: ${r.php_day} (0=Monday, 1=Tuesday...)`);
  }

  await connection.end();
}

checkMondayHours().catch(console.error);
