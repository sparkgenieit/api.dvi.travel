const mysql = require('mysql2/promise');

async function checkClosingTimes() {
  const connection = await mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'my@Richlabz123',
    database: 'dvi_travels'
  });

  try {
    console.log('\n=== CHECKING CLOSING TIMES FOR ROUTE 3 HOTSPOTS ===\n');

    const hotspots = [16, 18, 23, 25];
    const dayOfWeek = 1; // Tuesday

    for (const hid of hotspots) {
      const [info] = await connection.execute(`
        SELECT hotspot_ID, hotspot_name, hotspot_priority
        FROM dvi_hotspot_place
        WHERE hotspot_ID = ?
      `, [hid]);

      const [timing] = await connection.execute(`
        SELECT hotspot_start_time, hotspot_end_time
        FROM dvi_hotspot_timing
        WHERE hotspot_ID = ?
        AND hotspot_timing_day = ?
        AND deleted = 0
        ORDER BY hotspot_end_time
      `, [hid, dayOfWeek]);

      console.log(`Hotspot ${hid}: ${info[0].hotspot_name} (Priority ${info[0].hotspot_priority})`);
      if (timing.length === 0) {
        console.log(`  No timing restrictions\n`);
      } else {
        timing.forEach((t, idx) => {
          console.log(`  Slot ${idx + 1}: ${t.hotspot_start_time} - ${t.hotspot_end_time}`);
        });
        console.log(`  EARLIEST closing: ${timing[0].hotspot_end_time}\n`);
      }
    }

    console.log('Expected EDF order (same priority sorted by earliest closing):');
    console.log('  Priority 1: 18 (no timing) vs 16 (closes 18:00) → 16 first? Or 18?');
    console.log('  Priority 2: 25 (closes 12:30)');
    console.log('  Priority 3: 23 (?)');
    console.log('\nPHP actual: [18, 25, 16, 23, ...]');
    console.log('NestJS gets: [18, 16, 23, ...]');

  } catch (error) {
    console.error('Error:', error);
  } finally {
    await connection.end();
  }
}

checkClosingTimes();
