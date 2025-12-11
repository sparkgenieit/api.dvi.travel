const mysql = require('mysql2/promise');

async function checkSQLOrder() {
  const connection = await mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'my@Richlabz123',
    database: 'dvi_travels'
  });

  try {
    console.log('\n=== CHECKING SQL QUERY ORDER FOR ROUTE 3 HOTSPOTS ===\n');

    // This mimics the PHP SQL query for Plan 2 Route 3
    // Route 3: Pondicherry → Pondicherry Airport (Monday, day 1 = numeric 0)
    const location_name = 'Pondicherry';
    const next_visiting_name = 'Pondicherry Airport';
    const dayOfWeekNumeric = 1; // Tuesday (N format 2, then -1 = 1)

    const query = `
      SELECT 
        HOTSPOT_PLACE.hotspot_ID, 
        HOTSPOT_PLACE.hotspot_name, 
        HOTSPOT_PLACE.hotspot_location,
        HOTSPOT_PLACE.hotspot_latitude, 
        HOTSPOT_PLACE.hotspot_longitude, 
        HOTSPOT_PLACE.hotspot_priority
      FROM dvi_hotspot_place HOTSPOT_PLACE 
      LEFT JOIN dvi_hotspot_timing HOTSPOT_TIMING 
        ON HOTSPOT_TIMING.hotspot_ID = HOTSPOT_PLACE.hotspot_ID 
      WHERE HOTSPOT_PLACE.deleted = '0' 
        AND HOTSPOT_PLACE.status = '1' 
        AND HOTSPOT_TIMING.hotspot_timing_day = ?
        AND (
          HOTSPOT_PLACE.hotspot_location LIKE ? 
          OR HOTSPOT_PLACE.hotspot_location LIKE ?
        )
      GROUP BY HOTSPOT_PLACE.hotspot_ID 
      ORDER BY 
        CASE WHEN HOTSPOT_PLACE.hotspot_priority = 0 THEN 1 ELSE 0 END, 
        HOTSPOT_PLACE.hotspot_priority ASC
    `;

    const [rows] = await connection.execute(query, [
      dayOfWeekNumeric,
      `%${location_name}%`,
      `%${next_visiting_name}%`
    ]);

    console.log(`✓ SQL returned ${rows.length} hotspots in this order:\n`);

    rows.forEach((row, idx) => {
      const locationCount = row.hotspot_location.split('|').length;
      console.log(`${idx + 1}. Hotspot ${row.hotspot_ID}: ${row.hotspot_name}`);
      console.log(`   Priority: ${row.hotspot_priority}, Locations: ${locationCount}`);
      console.log(`   Location: ${row.hotspot_location}\n`);
    });

    const hotspotIDs = rows.map(r => r.hotspot_ID);
    console.log(`\nHotspot IDs from SQL: [${hotspotIDs.join(', ')}]`);
    console.log(`Plan 2 actual order:  [18, 25, 16, 23, 20, 676, 669]`);

    if (JSON.stringify(hotspotIDs) === JSON.stringify([18, 25, 16, 23, 20, 676, 669])) {
      console.log('\n✓ SQL ORDER MATCHES Plan 2! sortHotspots() is a no-op.');
    } else {
      console.log('\n✗ SQL ORDER DIFFERS - sortHotspots() must be doing something.');
    }

  } catch (error) {
    console.error('Error:', error);
  } finally {
    await connection.end();
  }
}

checkSQLOrder();
