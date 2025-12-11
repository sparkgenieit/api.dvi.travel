const mysql = require('mysql2/promise');

async function checkOperatingHours() {
  const connection = await mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'my@Richlabz123',
    database: 'dvi_travels'
  });

  try {
    // Check hotspots 16, 18, 25 operating hours
    const [timing] = await connection.execute(`
      SELECT 
        h.hotspot_ID,
        h.hotspot_name,
        t.hotspot_timing_day,
        t.hotspot_start_time,
        t.hotspot_end_time
      FROM dvi_hotspot_place h
      LEFT JOIN dvi_hotspot_timing t ON h.hotspot_ID = t.hotspot_ID
      WHERE h.hotspot_ID IN (16, 18, 25)
      AND h.deleted = 0
      AND t.hotspot_timing_day = 1
      ORDER BY h.hotspot_ID
    `);

    console.log('\n=== OPERATING HOURS (Tuesday, day 1) ===\n');
    timing.forEach(row => {
      console.log(`Hotspot ${row.hotspot_ID}: ${row.hotspot_name}`);
      console.log(`  Open: ${row.hotspot_start_time} - Close: ${row.hotspot_end_time}\n`);
    });

    // Calculate when each would be visited if we follow priority ASC order [18, 16, 25]
    console.log('=== TIMELINE IF WE VISIT IN SQL ORDER [18, 16, 25] ===\n');
    console.log('Start time: 09:00:00');
    console.log('Hotspot 18: 09:00 travel → 09:17 visit 2hrs → 11:17 end');
    console.log('Hotspot 16: 11:17 travel → 12:14 visit 30min → 12:44 end');
    console.log('Hotspot 25: 12:44 travel → 13:03 visit 30min → 13:33 end');
    console.log('  ⚠️  Would visit at 13:03, but closes at 12:30! MISS IT!\n');

    console.log('=== TIMELINE IF WE VISIT IN PHP ORDER [18, 25, 16] ===\n');
    console.log('Start time: 09:00:00');
    console.log('Hotspot 18: 09:00 travel → 09:17 visit 2hrs → 11:17 end');
    console.log('Hotspot 25: 11:17 travel → 11:36 visit 30min → 12:06 end');
    console.log('  ✓  Visits at 11:36, well before 12:30 close time!');
    console.log('Hotspot 16: 12:06 travel → 12:14 visit 30min → 12:44 end\n');

    console.log('💡 INSIGHT: PHP must be re-ordering to respect closing times!');

  } catch (error) {
    console.error('Error:', error);
  } finally {
    await connection.end();
  }
}

checkOperatingHours();
